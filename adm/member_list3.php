<?php
$sub_menu = "200100";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

$g5['title'] = '탈퇴회원정보조회';
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


if ($mb_datetime != "") {
    $mb_datetimes = explode("~", $mb_datetime);
    $fr_mb_datetime = trim($mb_datetimes[0]);
    $to_mb_datetime = trim($mb_datetimes[1]);

    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_mb_datetime) ) $fr_mb_datetime = '';
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_mb_datetime) ) $to_mb_datetime = '';

    if ($fr_mb_datetime && $to_mb_datetime) {
        $sql_search .= " and mb_leave_date between '$fr_mb_datetime 00:00:00' and '$to_mb_datetime 23:59:59' ";
    }
}

$sql_search .= " and mb_leave_date <> ''";

if (!$sst) {
    $sst = "mb_leave_date";
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

$sql = " select a.*
          {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";

$result = sql_query($sql);

$colspan = 16;
$token = get_admin_token();



$qstr = "sort1=".urlencode($sort1)."&amp;sort2=".urlencode($sort2)."&amp;sfl=".urlencode($sfl)."&amp;stx=".urlencode($stx)."&amp;mb_sex=".urlencode($mb_sex);
$qstr .="&amp;od_time=".urlencode($od_time)."&amp;mb_today_login=".urlencode($mb_today_login)."&amp;mb_datetime=".urlencode($mb_datetime)."&amp;mb_9=".urlencode($mb_9)."&amp;mb_10=".urlencode($mb_10)."&amp;provider=".urlencode($provider);
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
        <th scope="row">탈퇴일</th>
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
        <span class="btn_ov01"><span class="ov_txt">검색결과 </span><span class="ov_num"> <?php echo number_format($total_count) ?>명 </span></span>
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
<input type="hidden" name="act_button" value="">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" id="mb_list_name"><?php echo subject_sort_link('mb_name') ?>이름</a></th>
        <th scope="col" id="mb_list_id"><?php echo subject_sort_link('mb_id') ?>아이디</a></th>
        <th scope="col" id="mb_list_join"><?php echo subject_sort_link('mb_datetime', '', 'desc') ?>탈퇴일</a></th>

        <th scope="col" id="mb_list_mobile">사유</th>
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
    ?>

    <tr class="<?php echo $bg; ?>">
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
        <td headers="mb_list_join" class="td_datetime"><?php echo $row['mb_leave_date']; ?></td>
        <td headers="mb_list_auth" class="td_itname">
            <?php echo $row['mb_4'] ?>
            <?php echo $row['mb_5'] ?>

            <?php echo $row['mb_3'] ?>
        </td>
    </tr>

    <?php
    }
    if ($i == 0)
        echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
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
        	document.pressed="개별메일전송";
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
		if($("#mb_7").val() == ""){
			alert("설정 사유를 입력해 주세요");
			$("#mb_7").focus();
			return false;
		}

    }
    else if(document.pressed == "개별메일전송") {
    	$("#fmemberlist").attr("action","<?php echo G5_ADMIN_URL?>/operation/configform_sendEmail.php");
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
