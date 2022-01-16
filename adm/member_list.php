<?php
$sub_menu = "200100";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

$g5['title'] = '회원정보조회';
include_once('./admin.head.php');

$sql_common = " from {$g5['member_table']} as a";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_point' :
            $sql_search .= " ({$sfl} >= '{$stx}') ";
            break;
        case 'mb_level' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        case 'mb_tel' :
        case 'mb_hp' :
            $sql_search .= " ({$sfl} like '%{$stx}') ";
            break;
        case 'mb_name':
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if(isset($mb_9) && $mb_9 != ""){
    if($mb_9 == "pc") {
        $sql_search .= " and mb_9 = '0' ";
    } else {
        $sql_search .= " and mb_9 in ('1','2') ";
    }
}

if(isset($mb_sex) && $mb_sex != ""){
    $sql_search .= " and mb_sex = '{$mb_sex}' ";
}

if(isset($provider) && $provider == "lifelike"){
    $sql_search .= " and mb_id not in (select mb_id from lt_member_social_profiles) ";
}
if(isset($provider) && $provider != "lifelike" && $provider != ""){
    $sql_search .= " and mb_id in (select mb_id from lt_member_social_profiles where provider = '{$provider}') ";
}
if(isset($tier) && $tier != ""){
    $sql_search .= " and mb_tier = '{$tier}' ";
}

if ($is_admin != 'super'){
    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";
}

if ($mb_datetime != "") {
    $mb_datetimes = explode("~", $mb_datetime);
    $fr_mb_datetime = trim($mb_datetimes[0]);
    $to_mb_datetime = trim($mb_datetimes[1]);

    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_mb_datetime) ) $fr_mb_datetime = '';
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_mb_datetime) ) $to_mb_datetime = '';

    if ($fr_mb_datetime && $to_mb_datetime) {
        $sql_search .= " and mb_datetime between '$fr_mb_datetime 00:00:00' and '$to_mb_datetime 23:59:59' ";
    }
}

if ($mb_today_login != "") {
    $mb_today_logins = explode("~", $mb_today_login);
    $fr_mb_today_login = trim($mb_today_logins[0]);
    $to_mb_today_login = trim($mb_today_logins[1]);

    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_mb_today_login) ) $fr_mb_today_login = '';
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_mb_today_login) ) $to_mb_today_login = '';

    if ($fr_mb_today_login && $to_mb_today_login) {
        $sql_search .= " and mb_today_login between '$fr_mb_today_login 00:00:00' and '$to_mb_today_login 23:59:59' ";
    }
}

if ($od_time != "") {
    $od_times = explode("~", $od_time);
    $fr_date = trim($od_times[0]);
    $to_date = trim($od_times[1]);

    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = '';
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = '';

    if ($fr_date && $to_date) {
        $sql_search .= " and mb_id in (select mb_id from lt_shop_order where od_time between '$fr_date 00:00:00' and '$to_date 23:59:59' ) ";
    }
}

if(!isset($mb_10)){
    $mb_10 = "0,1";
}

if($mb_10 != ""){
    $mb_10s = explode(",", $mb_10);
    $sql_search_mb_10 = "";
    $sql_search_where = "";
    for ($i = 0; $i < count($mb_10s); $i++) {
        if($mb_10s[$i] != ""){

            switch ($mb_10s[$i]) {
                case "0":
                    $sql_search_mb_10 .= $sql_search_where." (mb_10 <> '1' and mb_leave_date = '' and mb_intercept_date = '' and mb_block_write = '0' and  mb_block_shop = '0' and  mb_block_login = '0') ";
                    $sql_search_where = " or ";
                    ;
                    break;
                case "1":
                    $sql_search_mb_10 .= $sql_search_where." (mb_10 = '1' and mb_leave_date = '' and mb_intercept_date = '' and mb_block_write = '0' and  mb_block_shop = '0' and  mb_block_login = '0')";
                    $sql_search_where = " or ";
                    ;
                    break;
                case "2": //탈퇴회원
                    $sql_search_mb_10 .= $sql_search_where." mb_leave_date <> '' ";
                    $sql_search_where = " or ";
                    ;
                    break;
                case "3": //휴먼예정회원 cf_3 설정날짜 이전 로그인 사용자는 휴먼대상자
                    $fr_mb_today_login = date_create(G5_TIME_YMD);
                    date_add($fr_mb_today_login, date_interval_create_from_date_string('-'.$config['cf_3'].' days'));
                    $fr_mb_today_login = date_format($fr_mb_today_login,"Y-m-d");
                    $sql_search_mb_10 .= $sql_search_where." (mb_today_login <= '".$fr_mb_today_login."' and mb_datetime <= '".$fr_mb_today_login."') ";
                    $sql_search_where = " or ";
                    ;
                    break;
                case "4": //불량회원
                    $sql_search_mb_10 .= $sql_search_where." mb_intercept_date <> '' or mb_block_write = '1' or  mb_block_shop = '1' or  mb_block_login = '1'  ";
                    $sql_search_where = " or ";
                    ;
                    break;
            }


        }
    }

    if($sql_search_mb_10 != ""){
        $sql_search .= " and (".$sql_search_mb_10.")";
    }
}

if (!$sst) {
    $sst = "mb_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

if($page_rows) $rows = $page_rows;
else $rows = $config['cf_page_rows'];

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// 탈퇴회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

// 차단회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$intercept_count = $row['cnt'];

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'?mb_10=" class="ov_listall">전체목록</a>';

$sql = " select
            (select count(*) from lt_shop_order where lt_shop_order.mb_id = a.mb_id and od_status in ('결제완료','상품준비중','배송완료','배송중','리스중','보관중','세탁완료','수선완료','구매확정')) as odcnt
            ,ifnull((select sum(od_receipt_price) from lt_shop_order where lt_shop_order.mb_id = a.mb_id and od_status in ('결제완료','상품준비중','배송완료','배송중','리스중','보관중','세탁완료','수선완료','구매확정')),0) as odprice
            ,a.*
            ,if(a.mb_mailling = '1','Y','N') ex_mailling
            ,if(a.mb_sms = '1','Y','N') ex_sms
            ,if(a.mb_10 = '1','사업자','일반') mb_10_nm
        {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";

// $sql = " select
//                 (select count(*) from lt_qa_content where a.mb_id = lt_qa_content.mb_id and qa_datetime >= DATE_ADD(now(), INTERVAL -7 day) ) as qacnt
//                 ,(select count(*) from lt_shop_order where lt_shop_order.mb_id = a.mb_id and od_status in ('결제완료','상품준비중','배송완료','배송중','리스중','보관중','세탁완료','수선완료')) as odcnt
//                 ,(select count(*) from lt_shop_order where lt_shop_order.mb_id = a.mb_id and od_status_claim is not null and od_status_claim != '') as odclaimcnt
//                 ,ifnull((select sum(od_receipt_price) from lt_shop_order where lt_shop_order.mb_id = a.mb_id and od_status in ('결제완료','상품준비중','배송완료','배송중','리스중','보관중','세탁완료','수선완료')),0) as odprice
//                 ,a.*
//                 ,if(a.mb_mailling = '1','Y','N') ex_mailling
//                 ,if(a.mb_sms = '1','Y','N') ex_sms
//                 ,if(a.mb_10 = '1','사업자','일반') mb_10_nm
//           {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";

$result = sql_query($sql);

$colspan = 16;
$token = get_admin_token();

$excel_sql = $sql;
                
if(substr_count($sql, "limit")){
    $sqls = explode('limit', $excel_sql);
    $excel_sql = $sqls[0];
}
// 등급 SQL 
$tierSql = " SELECT mr_rating FROM lt_member_rating ORDER BY mr_start_amount ASC ";
$tierName = sql_query($tierSql);

$headers = array('NO', '가입일','구분', '이름','아이디','등급','휴대전화', '메일주소', '성별', '생년월일', '적립금', '구매건수','구매금액','이메일','SMS','회원등급','접속시간');
$bodys = array('NO', 'mb_datetime','mb_10_nm', 'mb_name','mb_id','mb_level', 'mb_hp', 'mb_email', 'mb_sex', 'mb_birth', 'mb_point', 'odcnt', 'odprice','ex_mailling','ex_sms','mb_tier','mb_today_login');

$enc = new str_encrypt();

$excel_sql = $enc->encrypt($excel_sql);
$headers = $enc->encrypt(json_encode_raw($headers));
$bodys = $enc->encrypt(json_encode_raw($bodys));


$qstr = "sort1=".urlencode($sort1)."&amp;sort2=".urlencode($sort2)."&amp;sfl=".urlencode($sfl)."&amp;stx=".urlencode($stx)."&amp;mb_sex=".urlencode($mb_sex);
$qstr .="&amp;od_time=".urlencode($od_time)."&amp;mb_today_login=".urlencode($mb_today_login)."&amp;mb_datetime=".urlencode($mb_datetime)."&amp;mb_9=".urlencode($mb_9)."&amp;mb_10=".urlencode($mb_10)."&amp;provider=".urlencode($provider)."&amp;tier=".urlencode($tier);
$qstr .="&amp;page_rows=".urlencode($page_rows);
?>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get" onsubmit="$('#page').val('1');">
<input type="hidden" name="sort1" value="<?php echo $sort1; ?>">
<input type="hidden" name="sort2" value="<?php echo $sort2; ?>">
<input type="hidden" name="page"  id="page" value="<?php echo $page; ?>">
<input type="hidden" name="mb_10"  id="mb_10" value="<?php echo $mb_10; ?>">

<div class="tbl_frm01 tbl_wrap">
    <table>
	<colgroup>
    <col class="grid_4">
    <col>
    <col class="grid_3">
    </colgroup>

    <tr>
        <th scope="row" style="width:15%;">개인정보</th>
		<td colspan="2">
        <select name="sfl" id="sfl">
            <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>회원아이디</option>
            <option value="mb_nick"<?php echo get_selected($_GET['sfl'], "mb_nick"); ?>>닉네임</option>
            <option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>이름</option>
            <option value="mb_email"<?php echo get_selected($_GET['sfl'], "mb_email"); ?>>E-MAIL</option>
            <option value="mb_tel"<?php echo get_selected($_GET['sfl'], "mb_tel"); ?>>연락처</option>
            <option value="mb_hp"<?php echo get_selected($_GET['sfl'], "mb_hp"); ?>>휴대전화번호</option>
            <option value="mb_point"<?php echo get_selected($_GET['sfl'], "mb_point"); ?>>적립금</option>
            <option value="mb_datetime"<?php echo get_selected($_GET['sfl'], "mb_datetime"); ?>>가입일시</option>
            <option value="mb_ip"<?php echo get_selected($_GET['sfl'], "mb_ip"); ?>>IP</option>
            <option value="mb_recommend"<?php echo get_selected($_GET['sfl'], "mb_recommend"); ?>>추천인</option>
        </select>
		<input type="text" name="stx" value="<?php echo $stx ?>" id="stx"  class=" frm_input">
		</td>
	</tr>
    <tr>
        <th scope="row">회원구분</th>
		<td colspan="2">
			<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                <input type="checkbox" value="0" id="mb_10_0" class="mb_10" <?php if(substr_count($mb_10, '0') >= 1) echo "checked"; ?>>
                <label for="mb_10_0">일반회원</label>
                <input type="checkbox" value="1" id="mb_10_1" class="mb_10" <?php if(substr_count($mb_10, '1') >= 1) echo "checked"; ?>>
                <label for="mb_10_1">사업자회원</label>
                <input type="checkbox" value="2" id="mb_10_2" class="mb_10" <?php if(substr_count($mb_10, '2') >= 1) echo "checked"; ?>>
                <label for="mb_10_2">탈퇴회원</label>
                <input type="checkbox" value="3" id="mb_10_3" class="mb_10" <?php if(substr_count($mb_10, '3') >= 1) echo "checked"; ?>>
                <label for="mb_10_3">휴면예정회원</label>
                <input type="checkbox" value="4" id="mb_10_4" class="mb_10" <?php if(substr_count($mb_10, '4') >= 1) echo "checked"; ?>>
                <label for="mb_10_4">불량회원</label>
            </div>
		</td>
	</tr>
    <tr>
        <th scope="row">성별</th>
		<td colspan="2">
		<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
			<?php if(!isset($mb_sex)) $mb_sex = ''; ?>
            <input type="radio" name="mb_sex" value="" id="mb_sex" <?php echo get_checked($mb_sex, '');?>>
            <label for="mb_sex">전체</label>
            <input type="radio" name="mb_sex" value="M" id="mb_sexM" <?php echo get_checked($mb_sex, 'M');  ?>>
            <label for="mb_sexM">남성</label>
            <input type="radio" name="mb_sex" value="F" id="mb_sexF" <?php echo get_checked($mb_sex, 'F');  ?>>
            <label for="mb_sexF">여성</label>
		</div>
		</td>
	</tr>
    <tr>
        <th scope="row">주문일</th>
		<td colspan="2">
        	<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            	<input type='text' class="form-control" id="od_time" name="od_time" value=""/>
            	<i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 10px;right: 24px;top: auto;cursor: pointer;"></i>
        	</div>
		</td>
	</tr>
    <tr>
        <th scope="row">접속일</th>
		<td colspan="2">
        	<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            	<input type='text' class="form-control" id="mb_today_login" name="mb_today_login" value=""/>
            	<i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 10px;right: 24px;top: auto;cursor: pointer;"></i>
        	</div>
		</td>
	</tr>
    <tr>
        <th scope="row">가입일</th>
		<td colspan="2">
        	<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            	<input type='text' class="form-control" id="mb_datetime" name="mb_datetime" value=""/>
            	<i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 10px;right: 24px;top: auto;cursor: pointer;"></i>
        	</div>
        	<div class="btn-group col-lg-8 col-md-6 col-sm-12 col-xs-12" >
                <button type="button" class="btn btn_02" name="dateBtn" data="all">전체</button>
                <button type="button" class="btn btn_02" name="dateBtn" data="today">오늘</button>
                <button type="button" class="btn btn_02" name="dateBtn" data="3d">3일</button>
                <button type="button" class="btn btn_02" name="dateBtn" data="1w">1주</button>
                <button type="button" class="btn btn_02" name="dateBtn" data="1m">1개월</button>
                <button type="button" class="btn btn_02" name="dateBtn" data="3m">3개월</button>
             </div>
		</td>
	</tr>
    <tr>
        <th scope="row">가입경로</th>
		<td colspan="2">
		<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
			<?php if(!isset($mb_9)) $mb_9 = ''; ?>
            <input type="radio" name="mb_9" value="" id="mb_9" <?php echo get_checked($mb_9, '');?>>
            <label for="mb_9">전체</label>
            <input type="radio" name="mb_9" value="pc" id="mb_9_pc" <?php echo get_checked($mb_9, 'pc');  ?>>
            <label for="mb_9_pc">PC</label>
            <input type="radio" name="mb_9" value="mobile" id="mb_9_m" <?php echo get_checked($mb_9, 'mobile');  ?>>
            <label for="mb_9_m">모바일</label>
		</div>
		</td>
	</tr>
    <tr>
        <th scope="row">가입채널</th>
		<td colspan="2">
			<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
			<?php if(!isset($provider)) $provider = ''; ?>
                <input type="radio" name="provider" value="" id="provider" <?php echo get_checked($provider, ''); ?>>
                <label for="provider">전체</label>
                <input type="radio" name="provider" value="lifelike" id="provider0" <?php echo get_checked($provider, 'lifelike'); ?>>
                <label for="provider0">라이프라이크</label>
                <input type="radio" name="provider" value="naver" id="provider1" <?php echo get_checked($provider, 'naver'); ?>>
                <label for="provider1">네이버</label>
                <input type="radio" name="provider" value="kakao" id="provider2" <?php echo get_checked($provider, 'kakao'); ?>>
                <label for="provider2">카카오</label>
                <input type="radio" name="provider" value="facebook" id="provider3" <?php echo get_checked($provider, 'facebook'); ?>>
                <label for="provider3">페이스북</label>
            </div>
		</td>
    </tr>
    <tr>
        <th scope="row">등급</th>
		<td colspan="2">
			<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">

            <?php if(!isset($tier)) $tier = ''; ?>
            <input type="radio" name="tier" value="" id="tier" <?php echo get_checked($tier, ''); ?>>
            <label for="tier">전체</label>
            <?php    
                for ($j=0; $tn=sql_fetch_array($tierName); $j++) {
            ?>
                <input type="radio" name="tier" value="<?= $tn['mr_rating'] ?>" id="tier<?= $j?>" <?php echo get_checked($tier, $tn['mr_rating']); ?>>
                <label for="tier<?= $j?>"><?= $tn['mr_rating'] ?></label>

            <?php } ?>
            </div>
		</td>
	</tr>
	</table>
</div>
<div class="form-group">
    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
    	<button class="btn btn_02" type="reset" id="btn_clear">초기화</button>
    	<button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i>검색</button>
    </div>
</div>

<div class="tbl_head01 tbl_wrap">
<div class="pull-left">
    <div class="local_ov01 local_ov">
        <?php echo $listall ?>
        <span class="btn_ov01"><span class="ov_txt">총회원수 </span><span class="ov_num"> <?php echo number_format($total_count) ?>명 </span></span>
        <a href="?sst=mb_intercept_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01"> <span class="ov_txt">차단 </span><span class="ov_num"><?php echo number_format($intercept_count) ?>명</span></a>
        <a href="?sst=mb_leave_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01"> <span class="ov_txt">탈퇴  </span><span class="ov_num"><?php echo number_format($leave_count) ?>명</span></a>
    </div>
</div>
<div class="pull-right">
  <select name="page_rows" onchange="$('#fsearch').submit();">
    <option value="10" <?php echo get_selected($page_rows, '10') ; ?> >10개씩 보기</option>
    <option value="20" <?php echo get_selected($page_rows, '20') ; ?> >20개씩 보기</option>
    <option value="30" <?php echo get_selected($page_rows, '30') ; ?> >30개씩 보기</option>
  </select>
</div>
</div>

</form>




<form name="fmemberlist" id="fmemberlist" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token?>">
<input type="hidden" id="act_button" name="act_button" value="">

<div class="local_cmd01 local_cmd">
	<div style="float: left">
	<input type="submit" name="act_button" value="삭제" onclick="document.pressed=this.value" class="btn btn_02">
	<input type="button" value="불량회원 설정" class="btn btn_02" onclick="fmemberlist_btn_click('불량회원');">
	</div>
	<div style="float: right">
	<input type="button" value="개별메일전송" class="btn btn_02" onclick="fmemberlist_btn_click('EMAIL');">
	<input type="button" value="SMS보내기" class="btn btn_02" onclick="fmemberlist_btn_click('SMS');">
	<input type="button" value="엑셀다운로드" class="btn btn_02" id="excel_download1">
	</div>
</div>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" id="mb_list_chk">
            <label for="chkall" class="sound_only">회원 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col" id="mb_list_auth" style="width: 50px;">알림</th>
        <th scope="col" id="mb_list_join"><?php echo subject_sort_link('mb_datetime', '', 'desc') ?>가입일</a></th>
        <th scope="col" id="mb_list_auth" style="width: 50px;">구분</th>
        <th scope="col" id="mb_list_name"><?php echo subject_sort_link('mb_name') ?>이름</a></th>
        <th scope="col" id="mb_list_id"><?php echo subject_sort_link('mb_id') ?>아이디</a></th>
        <th scope="col" id="mb_list_deny" style="width: 50px;"><?php echo subject_sort_link('mb_level', '', 'desc') ?>등급</a></th>
        <th scope="col" id="mb_list_mobile">휴대전화</th>
        <th scope="col" id="mb_list_email"><?php echo subject_sort_link('mb_email', '', 'desc') ?>메일주소</a></th>
        <th scope="col" id="mb_list_sex"style="width: 50px;">성별</th>
        <th scope="col" id="mb_list_auth" style="width: 80px;">생년월일</th>
        <th scope="col" id="mb_list_point"><?php echo subject_sort_link('mb_point', '', 'desc') ?> 적립금</a></th>
        <th scope="col" id="mb_list_auth" style="width: 70px;">구매건수</th>
        <th scope="col" id="mb_list_auth" style="width: 80px;">구매금액</th>
        <th scope="col" id="mb_list_email_check" style="width: 50px;">EMAIL</th>
        <th scope="col" id="mb_list_sms_checkh" style="width: 50px;">SMS</th>
        <th scope="col" id="mb_list_tier_check" style="width: 100px;">회원등급</th>
        <th scope="col" id="mb_list_tier_check" style="width: 100px;">접속 시간</th>
        <th scope="col" id="mb_list_mng" style="width: 66px;">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {

        if ($is_admin == 'group') {
            $s_mod = '';
        } else {
            $s_mod = '<a href="./member_form.php?'.$qstr.'&amp;w=u&amp;mb_id='.$row['mb_id'].'" class="btn btn_03 member_form" target="_blank">수정</a>';
        }

        $s_mod .= '<input type="button" class="btn btn_02 mb_memo" value="메모" mb_id="'.$row['mb_id'].'" ">';

        $leave_date = $row['mb_leave_date'] ? $row['mb_leave_date'] : date('Ymd', G5_SERVER_TIME);
        $intercept_date = $row['mb_intercept_date'] ? $row['mb_intercept_date'] : date('Ymd', G5_SERVER_TIME);

        $mb_nick = get_sideview($row['mb_id'], get_text($row['mb_nick']), $row['mb_email'], $row['mb_homepage']);

        $mb_id = $row['mb_id'];
        $leave_msg = '';
        $intercept_msg = '';
        $intercept_title = '';
        if ($row['mb_leave_date']) {
            $mb_id = $mb_id;
            $leave_msg = '<span class="mb_leave_msg">탈퇴</span>';
        }
        else if ($row['mb_intercept_date'] || $row['mb_block_write'] || $row['mb_block_shop'] ||  $row['mb_block_login']) {
            $mb_id = $mb_id;
            $intercept_msg = '<span class="mb_intercept_msg">차단</span>';
        }

        $address = $row['mb_zip1'] ? print_address($row['mb_addr1'], $row['mb_addr2'], $row['mb_addr3'], $row['mb_addr_jibeon']) : '';

        $bg = 'bg'.($i%2);

        $sql1 = "SELECT count(*) AS qacnt FROM lt_qa_content WHERE mb_id = '{$row['mb_id']}' AND qa_datetime >= DATE_ADD(now(), INTERVAL -7 day)";
        $sql2 = "SELECT count(*) AS odclaimcnt FROM lt_shop_order WHERE mb_id = '{$row['mb_id']}' AND od_status_claim is not null AND od_status_claim != ''";
        $row1 = sql_fetch($sql1);
        $qacnt = $row1['qacnt'];
        $row2 = sql_fetch($sql2);
        $odclaimcnt = $row2['odclaimcnt'];
    ?>

    <tr class="<?php echo $bg; ?>">
        <td headers="mb_list_chk" class="td_chk">
            <input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>" id="mb_id_<?php echo $i ?>">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['mb_name']); ?> <?php echo get_text($row['mb_nick']); ?>님</label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </td>
        <td headers="mb_list_auth"><?php
                if($odclaimcnt > 0) echo 'CS';
                else if($qacnt > 0) echo 'NEW';
            ?></td>
        <td headers="mb_list_join" class="td_date"><?php echo substr($row['mb_datetime'],2,8); ?></td>
        <td headers="mb_list_auth"><?php
                if ($leave_msg || $intercept_msg) echo '['.$leave_msg.' '.$intercept_msg.'] ';

                if($row['mb_10'] == '1') echo '사업자';
                else echo '일반';
            ?></td>
        <td headers="mb_list_name" class="td_mbname"><?php echo get_text($row['mb_name']); ?></td>
        <td headers="mb_list_id" class="td_name sv_use">
            <?php echo $mb_id ?>
            <?php
            //소셜계정이 있다면
            if(function_exists('social_login_link_account')){
                if( $my_social_accounts = social_login_link_account($row['mb_id'], false, 'get_data') ){

                    echo '<div class="member_social_provider sns-wrap-over sns-wrap-32">';
                    foreach( (array) $my_social_accounts as $account){     //반복문
                        if( empty($account) || empty($account['provider']) ) continue;

                        $provider = strtolower($account['provider']);
                        $provider_name = social_get_provider_service_name($provider);

                        echo '<span class="sns-icon sns-'.$provider.'" title="'.$provider_name.'">';
                        echo '<span class="ico"></span>';
                        echo '<span class="txt">'.$provider_name.'</span>';
                        echo '</span>';
                    }
                    echo '</div>';
                }
            }
            ?>
        </td>
        <td headers="mb_list_auth" class="td_mbstat">
            <?php echo $row['mb_level'] ?>
        </td>
        <td headers="mb_list_mobile" class="td_tel"><?php echo get_text($row['mb_hp']); ?></td>
        <td headers="mb_list_email" class="td_tel"><?php echo get_text($row['mb_email']); ?></td>
        <td headers="mb_list_sex" ><?php if(get_text($row['mb_sex']) == "F") echo "여성"; elseif(get_text($row['mb_sex']) == "M") echo"남성"; ?></td>
        <td headers="mb_list_sex" ><?php echo $row['mb_birth']?></td>
        <td headers="mb_list_point" class="td_num"><a href="<?php echo G5_ADMIN_URL?>/operation/configform_saveMoney_management.php?sfl=mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo number_format($row['mb_point']) ?></a></td>

        <td headers="mb_list_auth" ><?php echo number_format($row['odcnt']) ?></td>
        <td headers="mb_list_auth" ><?php echo number_format($row['odprice']) ?></td>
        <td headers="mb_list_email_check" >
        <?php 
        if ($row['mb_mailling'] ==1) {
            echo ('Y');
        } else {
            echo ('N');
        } ?>
        </td>
        <td headers="mb_list_sms_checkh" >
            <?php 
            if ($row['mb_sms'] ==1) {
                echo ('Y');
            } else {
                echo ('N');
            }
            ?>
        </td>
        <td headers="mb_list_tier_check"><?= $row['mb_tier'] ?></td>
        <td headers="mb_list_today_login"><?= $row['mb_today_login'] ?></td>
        <td headers="mb_list_mng" class="td_mng td_mng_s"><?php echo $s_mod ?><?php echo $s_grp ?></td>
    </tr>

    <?php
    }
    if ($i == 0)
        echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
</div>

<div class="local_cmd01 local_cmd">
	<div style="float: left">
	<input type="submit" name="act_button" value="삭제" onclick="document.pressed=this.value" class="btn btn_02">
	<input type="button" value="불량회원 설정" class="btn btn_02" onclick="fmemberlist_btn_click('불량회원');">
	</div>
	<div style="float: right">
	<input type="button" value="개별메일전송" class="btn btn_02" onclick="fmemberlist_btn_click('EMAIL');">
	<input type="button" value="SMS보내기" class="btn btn_02" onclick="fmemberlist_btn_click('SMS');">
	<input type="button" value="엑셀다운로드" class="btn btn_02" id="excel_download2">
	</div>
</div>


<!-- Modal : 불량회원 설정 -->
<div id="modal_intercept" class="modal fade" role="dialog">
<div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">불량회원설정 팝업</h4>
  </div>
  <div class="modal-body">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th rowspan='2'>설정여부</th>
            <td colspan="2">
              <label><input type="radio" name="mb_intercept" value="1" id="mb_intercept1" checked="checked" onclick="$('#mb_block_login').prop('checked',true);" >설정</label>
              <label><input type="radio" name="mb_intercept" value="0" id="mb_intercept0" onclick="$('.mb_block').prop('checked',false); ">설정안함</label>
            </td>
        </tr>
        <tr>
            <td colspan="2">
            <label><input type="checkbox" class="mb_block" name="mb_block_login" value="1" id="mb_block_login" checked="checked">로그인 차단</label>
            <label><input type="checkbox" class="mb_block" name="mb_block_shop" value="1" id="mb_block_shop">구매 차단</label>
            <label><input type="checkbox" class="mb_block" name="mb_block_write" value="1" id="mb_block_write">글쓰기 차단</label>

            </td>
        </tr>
        <tr>
          <th scope="row">설정 사유</th>
          <td colspan="2">
            <input type="text" name="mb_7" value="" id="mb_7" class="required frm_input" size="50" maxlength="120">
          </td>
        </tr>
        </tbody>
        </table>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
    <input type="submit" name="act_button" value="불량회원설정" onclick="document.pressed=this.value" class="btn btn-success">
  </div>
</div>
<!-- Modal content-->
</div>
</div>
<!-- Modal : 스팸신고 -->

<div id="modal_mb_memo" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
        	<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		<h4 class="modal-title">메모</h4>
        	</div>
        	<div class="modal-body">
				<div class="tbl_frm01 tbl_wrap">
					<table>
					<tr>
						<th scope="row">아이디</th>
						<td id="mb_memo_id"></td>
					<tr>
						<th scope="row" rowspan="2">메모</th>
						<td><textarea name="mm_memo" id="mm_memo" class="frm_input" style="width: 100%"></textarea></td>
					</tr>
                    <tr>
                        <td>
                	        <label><input type="checkbox" name="is_important" value="1" id="is_important" > 중요메모</label>
                        </td>
                    </tr>
					</table>
				</div>
			</div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
    		  <input type="button" class="btn btn-success" id="btn_mb_memo" value="저장"></input>
            </div>
		</div>
	</div>
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

	</div>
	</div>
</div>

<script>
$(function(){

	$("#excel_download1, #excel_download2").click(function(){
		var $form = $('<form></form>');     
		$form.attr('action', '<?=G5_ADMIN_URL?>/ajax.excel_download.php');
	    $form.attr('method', 'post');
	    $form.appendTo('body');
	    
	    var exceldata = $('<input type="hidden" value="<?=$excel_sql?>" name="exceldata">');
	    var headerdata = $('<input type="hidden" value="<?=$headers?>" name="headerdata">');
	    var bodydata = $('<input type="hidden" value="<?=$bodys?>" name="bodydata">');
	    $form.append(exceldata).append(headerdata).append(bodydata);
	    $form.submit();
	});
	
    $('#mb_datetime,#mb_today_login,#od_time').daterangepicker({
    	"autoApply": true,
    	"opens": "right",
    	locale: {
            "format": "YYYY-MM-DD",
            "separator": " ~ ",
            "applyLabel": "선택",
            "cancelLabel": "취소",
            "fromLabel": "시작일자",
            "toLabel": "종료일자",
            "customRangeLabel": "직접선택",
            "weekLabel": "W",
            "daysOfWeek": ["일","월","화","수","목","금","토"],
            "monthNames": ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],
            "firstDay": 1
        }
    });
    <?php
       if($mb_datetime !='') echo "$('#mb_datetime').val('".$mb_datetime."');";
       else echo "$('#mb_datetime').val('');";

       if($od_time !='') echo "$('#od_time').val('".$od_time."');";
       else echo "$('#od_time').val('');";

       if($mb_today_login !='') echo "$('#mb_today_login').val('".$mb_today_login."');";
       else echo "$('#mb_today_login').val('');";
    ?>

    $(".mb_10").change(function(){

    	var mb_10 = "";
        var sep = "";
        $("input.mb_10:checked").each(function(){
        	//alert($(this).val());
			if(mb_10 != "") mb_10 += ",";
        	mb_10 += $(this).val();

        });

        $("#mb_10").val(mb_10)

    });

    //날짜 버튼
    $("button[name='dateBtn']").click(function(){

    	var d = $(this).attr("data");
    	if(d == "all") {
    		$('#mb_datetime').val("");
    	} else {
    		var startD = moment();
    		var endD = moment();

    		if(d == "3d") {
    			startD = moment().subtract(2, 'days');
    			endD = moment();

    		} else if(d == "1w") {
    			startD = moment().subtract(6, 'days');
    			endD = moment();

    		} else if(d == "1m") {
    			startD = moment().subtract(1, 'month');
    			endD = moment();

    		} else if(d == "3m") {
    			startD = moment().subtract(3, 'month');
    			endD = moment();
    		}

    		$('#mb_datetime').data('daterangepicker').setStartDate(startD);
    		$('#mb_datetime').data('daterangepicker').setEndDate(endD);
    	}
    });

    $(".member_form").on("click", function() {
        var url = this.href;
        window.open(url, "member_form", "left=100,top=100,width=1500,height=800,scrollbars=1");
        return false;
    });

    $("#btn_mb_intercept").on("click", function() {




    });

    $(".mb_memo").on("click", function() {
        var $this = $(this);
        var mb_id = $this.attr("mb_id");

        $("#mb_memo_id").text(mb_id);
        $("#modal_mb_memo").modal('show');

        return false;
    });

    $("#btn_mb_memo").on("click", function() {
        var mm_memo = $("#mm_memo").val();
        var is_important = $("#is_important").val();
        var mb_id = $("#mb_memo_id").text();

		$.post(
                "ajax.member_list_update.php",
                {	act_button : "메모"
                    , mb_id:  mb_id
                    , mm_memo:  mm_memo
                    , is_important: is_important
                    },
                function(data) {
                	var responseJSON = JSON.parse(data);
                	if(responseJSON.result == "S"){
                		$("#fsearch").submit();
                    }else {
                    	alert("오류가 발생했습니다. 다시 시도해주시기 바랍니다.");
                        return false;
                	}
                }
            );


    });
    window.addEventListener("keydown", (e) => {
        if (e.keyCode == 13) {
            document.getElementById('fsearch').submit();
        }
    })

});

function fmemberlist_btn_click(btnStatus)
{
    if (!is_checked("chk[]")) {
        alert(btnStatus+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

	switch (btnStatus)
    {
        case "불량회원" :
        	$("#modal_intercept").modal("show");
        	break;
        case "MEMO" :
        	$("#modal_intercept").modal("show");
        	break;
        case "EMAIL" :
        	document.pressed="EMAIL";
        	$("#fmemberlist").submit();
        case "SMS" :
        	document.pressed="SMS";
        	$("#fmemberlist").submit();
        	break;
    }
}

function fmemberlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "삭제") {
        if(!confirm("선택한 회원을 탈퇴/삭제 처리 하시겠습니까? \n탈퇴/삭제된 아이디는 복구가 불가능 합니다.")) {
            return false;
        }
    }
    else if(document.pressed == "불량회원설정") {

    	//alert($("input[name='mb_intercept']:checked").val());
        
		if($("input[name='mb_intercept']:checked").val() == "1" && $("#mb_7").val() == ""){
			alert("설정 사유를 입력해 주세요");
			$("#mb_7").focus();
			return false;
		}

    }
    else if(document.pressed == "EMAIL") {
    	$("#act_button").val("EMAIL");
    	$("#fmemberlist").attr("action","<?php echo G5_ADMIN_URL?>/operation/configform_sendEmail.php");
    	$("#fmemberlist").attr("target","_blank");

    }
    else if(document.pressed == "SMS") {
    	$("#act_button").val("SMS");
    	$("#fmemberlist").attr("action","<?php echo G5_ADMIN_URL?>/operation/configform_sms_send.php");
    	$("#fmemberlist").attr("target","_blank");

    } else {
    	return false;
    }

    return true;
}


</script>

<?php
include_once ('./admin.tail.php');
?>
