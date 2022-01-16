<?php
$sub_menu = '930200';
include_once('./_common.php');


auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '신규제품개발현황';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

if($od_type == "") $od_type = "L";

$tabs = $_GET['tabs'];


$sql_search = " where (1) ";

if ($stx) {
    $sql_search .= " and ps_it_name like '%{$stx}%' ";
}
if ($brands) {
    $brand_item = implode("','", explode(',', $brands));
    $sql_search .= " and ps_brand in ('{$brand_item}') ";
}


if ($sc_it_time != "") {
    $sc_it_times = explode("~", $sc_it_time);
    $fr_sc_it_time = trim($sc_it_times[0]);
    $to_sc_it_time = trim($sc_it_times[1]);

    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_sc_it_time) ) $fr_sc_it_time = '';
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_sc_it_time) ) $to_sc_it_time = '';

    if ($fr_sc_it_time && $to_sc_it_time) {
        $sql_search .= " and ps_reg_date between '$fr_sc_it_time 00:00:00' and '$to_sc_it_time 23:59:59' ";
    }
}

if ($mb_today_login != "") {
    $mb_today_logins = explode("~", $mb_today_login);
    $fr_mb_today_login = trim($mb_today_logins[0]);
    $to_mb_today_login = trim($mb_today_logins[1]);

    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_mb_today_login) ) $fr_mb_today_login = '';
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_mb_today_login) ) $to_mb_today_login = '';

    if ($fr_mb_today_login && $to_mb_today_login) {
        $sql_search .= " and ps_reg_date between '$fr_mb_today_login 00:00:00' and '$to_mb_today_login 23:59:59' ";
    }
}

if(!$ipgos){
    $sql_search .= " and ps_ipgo_status = 'N' ";
}else{
    if ($ipgos == 'Y') {
        $sql_search .= " and ps_ipgo_status = 'Y' ";
    }else{
        $ipgos_item = implode("','", explode(',', $ipgos));
        $sql_search .= " and ps_ipgo_status in ('{$ipgos_item}') ";
    }
}
if (!$shootings) {
    //$sql_search .= " and ps_shooting_yn in ('N','Y') ";
}else{
    if ($shootings == 'Y') {
        $sql_search .= " and ps_shooting_yn = 'Y' ";
    }else{
        $shootings_item = implode("','", explode(',', $shootings));
        $sql_search .= " and ps_shooting_yn in ('{$shootings_item}') ";
    }
}
if (!$reorders) {
    $sql_search .= " and ps_re_order in ('N','Y') ";
}else{
    if ($reorders == 'Y') {
        $sql_search .= " and ps_re_order = 'Y' ";
    }else{
        $reorders_item = implode("','", explode(',', $reorders));
        $sql_search .= " and ps_re_order in ('{$reorders_item}') ";
    }
}

// 테이블의 전체 레코드수만 얻음
$cnt_sql = "select count(*) as cnt from lt_prod_schedule {$sql_search}";
$cnt_row = sql_fetch($cnt_sql);
$total_count = $cnt_row['cnt'];


if($limit_list) $rows = $limit_list;
else $rows = $config['cf_page_rows'];
// $rows=4;

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

//행 병ㅎ합
$abs_sql = "select * from lt_prod_schedule {$sql_search} GROUP BY ps_it_name ORDER BY ps_id ASC";
$abs_result = sql_query($abs_sql);

$sql = "select * from lt_prod_schedule {$sql_search} ORDER BY  ps_origin_ps_id IS NULL ASC , ps_origin_ps_id ASC, ps_id ASC limit $from_record, $rows ";

$result = sql_query($sql);

$qstr= "tabs=list&amp;brands=".$brands."&amp;ipgos=".$ipgos."&amp;shootings=".$shootings."&amp;reorders=".$reorders."&amp;sfl=it_name&amp;stx=".$stx."&amp;sc_it_time=".$sc_it_time."&amp;limit_list=".$limit_list."&amp;page=".$page;


// $qstr  = $qstr.'&amp;sca='.$sca.'&amp;page='.$page.'&amp;save_stx='.$stx;



$sql_sample = "SELECT YEAR(ps_sample_date) s_year, MONTH(ps_sample_date) s_month, DAY(ps_sample_date) s_day, a.* FROM lt_prod_schedule a  WHERE ps_sample_date  ORDER BY s_year ASC, s_month ASC, s_day ASC";
$result_sample = sql_query($sql_sample);

$sample_data = array();
for ($i = 0; $row_sample = sql_fetch_array($result_sample); $i++) {
    $sample_data[$i] = $row_sample;
}

$sql_bipgo = "SELECT YEAR(ps_ipgo_date) s_year, MONTH(ps_ipgo_date) s_month, DAY(ps_ipgo_date) s_day, a.* FROM lt_prod_schedule a  WHERE ps_ipgo_date  ORDER BY s_year ASC, s_month ASC, s_day ASC";
$result_bipgo= sql_query($sql_bipgo);
$bipgo_data = array();
for ($i = 0; $row_bipgo = sql_fetch_array($result_bipgo); $i++) {
    $bipgo_data[$i] = $row_bipgo;
}

$sql_aipgo = "SELECT YEAR(ps_real_ipgo_date) s_year, MONTH(ps_real_ipgo_date) s_month, DAY(ps_real_ipgo_date) s_day, a.* FROM lt_prod_schedule a  WHERE ps_real_ipgo_date  ORDER BY s_year ASC, s_month ASC, s_day ASC";
$result_aipgo = sql_query($sql_aipgo);
$aipgo_data = array();
for ($i = 0; $row_aipgo = sql_fetch_array($result_aipgo); $i++) {
    $aipgo_data[$i] = $row_aipgo;
}

//엑셀
                
$job_headers = array('NO','브랜드명','상품명','작성일','품종','품목(아이템)','사이즈','소재(품질표시)','디자인이미지','원자재정보','부자재','봉제공임','주입비용','포장비','생산원가','생산관리비','총원가');
$job_bodys = array('NO','jo_brand','jo_it_name','jo_reg_date','jo_prod_type','jo_prod_name','jo_size','jo_soje','jo_design_img','jo_mater_info','jo_sub_mater','jo_bongje','jo_juip_price','jo_pack_price','jo_prod_origin_price','jo_prod_control_price','jo_total_origin_price');

$proposal_headers = array('NO','브랜드명','상품명','작성일','품목아이템','구분','연도','시즌','생산구분','색상','출하시기','제품입고처','원산지','제조사','수입자','판매자','기획읜도','원자재 매입처','임가공(수입)','완제품아이템','실적참고데이터');
$proposal_bodys = array('NO','ip_brand','ip_it_name','ip_reg_date','ip_prod_name','ip_gubun','ip_year','ip_season','ip_prod_gubun','ip_color','ip_clha_date','ip_item_ipgoer','ip_mater','ip_maker','ip_importer','ip_seller','ip_proposal_memo','ip_mater_purchace','ip_processing','ip_finished','ip_performance');

$info_headers = array('NO','중분류','재고연령','러닝/아웃','시즌','상품명','상품약어','모델명','모델no','자체상품코드','브랜드명','카테고리','원산지','생산연도','남녀구분','배송비','원가','실판매가','tag가','제품소재','색상','사이즈','치수','제조국','세탁방법','kc안전인증 대상유무','수입여부','상품무게','상품 사로세로높이','충전재','자사몰스타일','프라우덴 우모사용 유/무','필파워','필파워 인증서 유/무','원단상세정보 1-기업정보','원단상세정보 2 - 시험성적서 유/무','원단상세정보 3- OEKO-TEX 인증 유/무','담당자','상품기술서이미지경로1','상품기술서이미지경로2','상품기술서이미지경로3','상품기술서이미지경로4','동영상경로1','동영상경로2','동영상경로3','동영상경로4','제품원본이미지경로','상품설명','셀링포인트1','셀링포인트2','셀링포인트3','제품정보1','제품정보2','제품정보3','제품정보4','제품정보5','제품정보6','제품정보7','제품정보8','제품정보9','제품정보10','비고');
$info_bodys = array('NO','pi_sub_category','pi_jego_age','pi_running_out','pi_season','pi_it_name','pi_it_sub_name','pi_model_name','pi_model_no','pi_company_it_id','pi_brand','pi_category','pi_mater','pi_prod_date','pi_age_gubun','pi_delivery_price','pi_origin_price','pi_sale_price','pi_tag_price','pi_item_soje','pi_color','pi_size','pi_cisu','pi_maker','pi_laundry','pi_kc_safe_yn','pi_soip_yn','pi_prod_weight','pi_xyz','pi_charge','pi_ll_style','pi_prauden_umu_yn','pi_pilpower','pi_pilpower_safe_yn','pi_info1','pi_info2','pi_info3','pi_manager','pi_img','pi_img2','pi_img3','pi_img4','pi_video1','pi_video2','pi_video3','pi_video4','pi_origin_image','pi_detail_info','pi_selling1','pi_selling2','pi_selling3','pi_prod_info1','pi_prod_info2','pi_prod_info3','pi_prod_info4','pi_prod_info5','pi_prod_info6','pi_prod_info7','pi_prod_info8','pi_prod_info9','pi_prod_info10','etc');

$schedule_headers = array('NO','구분','목표납기(제품기획)','SO(OK)','브랜드','생산구분','상품명','품목(아이템)','사이즈','코드','업체명','결재승인일자','원당생산업체','원단발주','원단납기예정','원단검품(시험성적)','생산발주','샘플예정일','입고예정일','제품기획승인일자','실제입고일','작성일');
$schedule_bodys = array('NO','ps_gubun','ps_limit_date','ps_os','ps_brand','ps_prod_gubun','ps_it_name','ps_prod_name','ps_size','ps_code','ps_company_name','ps_approval_date','ps_prod_company','ps_balju','ps_expected_limit_date','ps_gumpum','ps_prod_balju','ps_sample_date','ps_ipgo_date','ps_prod_proprosal_date','ps_real_ipgo_date','ps_reg_date');

$enc = new str_encrypt();

$job_headers = $enc->encrypt(json_encode_raw($job_headers));
$job_bodys = $enc->encrypt(json_encode_raw($job_bodys));

$proposal_headers = $enc->encrypt(json_encode_raw($proposal_headers));
$proposal_bodys = $enc->encrypt(json_encode_raw($proposal_bodys));

$info_headers = $enc->encrypt(json_encode_raw($info_headers));
$info_bodys = $enc->encrypt(json_encode_raw($info_bodys));

$schedule_headers = $enc->encrypt(json_encode_raw($schedule_headers));
$schedule_bodys = $enc->encrypt(json_encode_raw($schedule_bodys));


?>




<div class="container">

	<ul class="tabs">
		<li class="tab-link <?=$tabs ? '' : 'current' ?>" data-tab="tab-1">캘린더</li>
		<li class="tab-link <?=$tabs == 'list' ? 'current' : '' ?>" data-tab="tab-2">리스트</li>
	</ul>

	<div id="tab-1" class="tab-content <?=$tabs ? '' : 'current' ?>">
        <div class="cal_top">
            <a href="#" id="movePrevMonth"><span id="prevMonth" class="cal_tit">&lt;</span></a>
            <span id="cal_top_year"></span>
            <span id="cal_top_month"></span>
            <a href="#" id="moveNextMonth"><span id="nextMonth" class="cal_tit">&gt;</span></a>
        </div>
        <div class="process_type">
            <div><span style="color:#f90000">상품명</span>: 샘플예정일</div>
            <div><span style="color:#1c5eff">상품명</span>: 출시예정일</div>
            <div><span style="color:#62c500">상품명</span>: 출시확정일</div>
        </div>
        <div id="cal_tab" class="cal">
        </div>
	</div>
	<div id="tab-2" class="tab-content <?=$tabs == 'list' ? 'current' : '' ?>">
        <form id="new_goods_form" name="new_goods_form" class="local_sch01 local_sch" onsubmit="" method="get">
            <input type="hidden" name = "tabs" value='list'>
            <input type="hidden" name = "brands" value='<?=$brands?>' id="brands">
            <input type="hidden" name = "ipgos" value='<?=$ipgos?>' id="ipgos">
            <input type="hidden" name = "shootings" value='<?=$shootings?>' id="shootings">
            <input type="hidden" name = "reorders" value='<?=$reorders?>' id="reorders">
            <div class="tbl_frm01 tbl_wrap">
                <table class="new_goods_list">
                <colgroup>
                <col class="grid_4">
                <col>
                <col class="grid_3">
                </colgroup>
                
                <tr>
                    <th scope="row">검색분류</th>
                    <td colspan="2">
                        <label for="sfl" class="sound_only">검색대상</label>
                        <select name="sfl" id="sfl">
                            <option value="it_name" <?php echo get_selected($sfl, 'it_name'); ?>>상품명</option>
                            <!-- <option value="it_id" <?php echo get_selected($sfl, 'it_id'); ?>>상품코드</option>
                            <option value="its_sap_code" <?php echo get_selected($sfl, 'its_sap_code'); ?>>SAP코드</option> -->
                        </select>
                        <label for="stx" class="sound_only">검색어</label>
                        <input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" class="frm_input">
                </td>
                </tr>
                <tr>
                    <th scope="row">일자</th>
                    <td colspan="2">
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <input type='text' class="form-control" id="it_time" name="sc_it_time" value=""/>
                            <i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 10px;right: 24px;top: auto;cursor: pointer;"></i>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                            <div class="btn-group" >
                                <button type="button" class="btn btn_02" name="dateBtn" data="all">전체</button>
                                <button type="button" class="btn btn_02" name="dateBtn" data="today">오늘</button>
                                <button type="button" class="btn btn_02" name="dateBtn" data="3d">3일</button>
                                <button type="button" class="btn btn_02" name="dateBtn" data="1w">1주</button>
                                <button type="button" class="btn btn_02" name="dateBtn" data="1m">1개월</button>
                                <button type="button" class="btn btn_02" name="dateBtn" data="3m">3개월</button>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">브랜드</th>
                    <td colspan="2">
                    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                        
                        <label><input type="checkbox" value="" id="brand_0" <?php if(!$brands) echo "checked"; ?>>전체</label>&nbsp;&nbsp;
                        <label><input type="checkbox" value="소프라움" id="brand_1" class="brand" <?php if(substr_count($brands, '소프라움') >= 1) echo "checked"; ?> >소프라움</label>&nbsp;&nbsp;
                        <label><input type="checkbox" value="쉐르단" id="brand_2" class="brand" <?php if(substr_count($brands, '쉐르단') >= 1) echo "checked"; ?> >쉐르단</label>&nbsp;&nbsp;
                        <label><input type="checkbox" value="랄프로렌홈" id="brand_3" class="brand" <?php if(substr_count($brands, '랄프로렌홈') >= 1) echo "checked"; ?> >랄프로렌홈</label>&nbsp;&nbsp;
                        <label><input type="checkbox" value="베온트레" id="brand_4" class="brand" <?php if(substr_count($brands, '베온트레') >= 1) echo "checked"; ?> >베온트레</label>&nbsp;&nbsp;
                        <label><input type="checkbox" value="링스티드던" id="brand_5" class="brand" <?php if(substr_count($brands, '링스티드던') >= 1) echo "checked"; ?> >링스티드던</label>&nbsp;&nbsp;
                        <label><input type="checkbox" value="로자리아" id="brand_6" class="brand" <?php if(substr_count($brands, '로자리아') >= 1) echo "checked"; ?> >로자리아</label>&nbsp;&nbsp;
                        <label><input type="checkbox" value="그라치아노" id="brand_7" class="brand" <?php if(substr_count($brands, '그라치아노') >= 1) echo "checked"; ?> >그라치아노</label>&nbsp;&nbsp;
                        <label><input type="checkbox" value="시뇨리아" id="brand_8" class="brand" <?php if(substr_count($brands, '시뇨리아') >= 1) echo "checked"; ?> >시뇨리아</label>&nbsp;&nbsp;
                        <label><input type="checkbox" value="플랫폼일반" id="brand_9" class="brand" <?php if(substr_count($brands, '플랫폼일반') >= 1) echo "checked"; ?> >플랫폼일반</label>&nbsp;&nbsp;
                        <label><input type="checkbox" value="플랫폼렌탈" id="brand_10" class="brand" <?php if(substr_count($brands, '플랫폼렌탈') >= 1) echo "checked"; ?> >플랫폼렌탈</label>&nbsp;&nbsp;
                        <label><input type="checkbox" value="온라인" id="brand_11" class="brand" <?php if(substr_count($brands, '온라인') >= 1) echo "checked"; ?> >온라인</label>&nbsp;&nbsp;
                        <label><input type="checkbox" value="템퍼" id="brand_12" class="brand" <?php if(substr_count($brands, '템퍼') >= 1) echo "checked"; ?> >템퍼</label>&nbsp;&nbsp;


                    </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">입고여부</th>
                    <td colspan="2">
                    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                        <label><input type="checkbox" value="N" id="ipgoyn_0" class="ipgoyn" <?php if(!$ipgos || (substr_count($ipgos, 'N') >= 1) ) echo "checked"; ?> >입고완료제외</label>&nbsp;&nbsp;
                        <label><input type="checkbox" value="Y" id="ipgoyn_1" class="ipgoyn" <?php if(substr_count($ipgos, 'Y') >= 1) echo "checked"; ?> >입고완료포함</label>&nbsp;&nbsp;
                    </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">촬영완료여부</th>
                    <td colspan="2">
                    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                        <label><input type="checkbox" value="Y" id="shootingyn_0" class="shooting" <?php if(!$shootings || (substr_count($shootings, 'Y') >= 1) ) echo "checked"; ?> >완료</label>&nbsp;&nbsp;
                        <label><input type="checkbox" value="N" id="shootingyn_1" class="shooting" <?php if(!$shootings ||substr_count($shootings, 'N') >= 1) echo "checked"; ?> >미완료</label>&nbsp;&nbsp;
                    </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">리오더</th>
                    <td colspan="2">
                    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                        <label><input type="checkbox" class="reorder" value="N" id="reorder_0" <?php if(!$reorders || (substr_count($reorders, 'N') >= 1) ) echo "checked"; ?>  >일반</label>&nbsp;&nbsp;
                        <label><input type="checkbox" class="reorder" value="Y" id="reorder_1" <?php if(!$reorders || (substr_count($reorders, 'Y') >= 1) ) echo "checked"; ?>  >리오더</label>&nbsp;&nbsp;
                    </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">보기</th>
                    <td colspan="2">
                    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                        <select name="limit_list" id="limit_list">
                            <option value="50" <?php if(!$limit_list ||(substr_count($limit_list, '50') >= 1)) echo "selected"; ?>>50개</option>
                            <option value="100" <?php if(substr_count($limit_list, '100') >= 1) echo "selected"; ?>>100개</option>
                            <option value="200" <?php if(substr_count($limit_list, '200') >= 1) echo "selected"; ?>>200개</option>
                            <option value="500" <?php if(substr_count($limit_list, '500') >= 1) echo "selected"; ?>>500개</option>
                            <option value="1000" <?php if(substr_count($limit_list, '1000') >= 1) echo "selected"; ?>>1000개</option>
                        </select>
                    </div>
                    </td>
                </tr>
                </table>
            </div>
            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                    <button class="btn btn_02 search-reset" type="button" id="btn_clear">초기화</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i>검색</button>
                </div>
            </div>
        </form>



        <form name="new_goods_result" id="new_goods_result" method="post" autocomplete="off">
            <input type="hidden" name="search_od_status" value="<?= $od_status; ?>">
            <input type="hidden" name="od_status" id="post_od_status">
            <input type="hidden" name="token" value="<?= $token ?>">

            <div class="tbl_head01 tbl_wrap" id="topscroll"  style="width:100%; margin:0px; overflow-x:scroll;">
                <div class="div1" style="width:124%; height:20px;"></div>
            </div>

            <div class="tbl_head01 tbl_wrap" id="bottomscroll"  style="overflow-x:scroll;">
                <table id="sodr_list" style="width:124%">
                    <thead>
                        <tr>
                            <th scope="col"  rowspan = "2">
                                <label for="chkall" class="sound_only">선택 전체</label>
                                <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                            </th>
                            <th scope="col" rowspan = "2">자체<br />상품코드</th>
                            <th scope="col" style="width:80px;"rowspan = "2">브랜드</th>
                            <th scope="col" style="width:150px;" rowspan = "2">상품명</th>
                            <th scope="col" rowspan = "2">코드</th>
                            <th scope="col" rowspan = "2">입고여부</th>
                            <th scope="col" rowspan = "2">촬영완료</th>
                            <th scope="col" rowspan = "2">샘플예정일</th>
                            <th scope="col" rowspan = "2">출시예정일</th>
                            <th scope="col" rowspan = "2">출시확정일</th>
                            <th scope="col" rowspan = "2">제품기획<br>승인일자</th>
                            <th scope="col" rowspan = "2">원단발주</th>
                            <th scope="col" rowspan = "2">원단납기예정</th>
                            <th scope="col" rowspan = "2">원단검품<br>(시험성적)</th>
                            <th scope="col" rowspan = "2">생산발주</th>
                            <th scope="col" style="width:100px;" rowspan = "2">생산수량</th>
                            <th scope="col" rowspan = "2">작업지시서<br>(아이템)</th>
                            <th scope="col" rowspan = "2">작업지시서<br>(사이즈)</th>
                            <th scope="col" rowspan = "2">제품기획서</th>
                            <th scope="col" rowspan = "2">상품정보집</th>
                            <th scope="col" rowspan = "2">생산일정</th>
                            <th scope="col" rowspan = "2">작지복사</th>
                            <th scope="col" rowspan = "1" colspan = "5">상품정보집 진행현황</th>
                        </tr>
                        <tr>
                            <th scope="col">사이즈</th>
                            <th scope="col">디자인팀</th>
                            <th scope="col">품랫폼팀<br>(MD)</th>
                            <th scope="col">플랫폼팀<br>(디자인)</th>
                            <th scope="col">플랫폼팀<br>(마케팅)</th>        
                        </tr>
                    </thead>
                    <tbody>
                        <?
                        for ($i = 0; $row = sql_fetch_array($result); $i++) {
                            $ipgo_status = '미입고';
                            if($row['ps_ipgo_status'] =='Y'){
                                $ipgo_status = '입고완료';
                            }
                            $shooting_status = '미완료';
                            if($row['ps_shooting_yn'] =='Y'){
                                $shooting_status = '완료';
                            }
                        ?>
                            <tr>
                                
                                <td>
                                    <input type="hidden" name="ps_id[<?= $i ?>]" value="<?= $row['ps_id'] ?>" id="od_id_<?= $i ?>">
                                    <input type="hidden" name="ps_re_order[<?= $i ?>]" value="<?= $row['ps_re_order'] ?>" id="ps_re_order_<?= $i ?>">
                                    <input type="checkbox" name="chk[]" value="<?= $i ?>" id="chk_<?= $i ?>">
                                </td>
                                <td>
                                    <label class="sound_only">자체상품코드</label>
                                    <?if($row['ps_re_order'] == 'N') : ?>
                                        <?=str_pad($row['ps_id'], 5, "0", STR_PAD_LEFT)?>
                                    <?else:?>
                                        <?if($row['ps_re_order'] == 'Y' && $row['ps_reorder_id'] != '') : ?>
                                            <?=str_pad($row['ps_origin_ps_id'], 5, "0", STR_PAD_LEFT)?>_<?=$row['ps_reorder_id']?>
                                        <?else : ?>
                                            -
                                        <?endif?>
                                    <?endif?>
                                </td>
                                <td headers="odrstat">
                                    <label class="sound_only">브랜드</label>
                                    <?= $row['ps_brand']; ?>
                                </td>
                                <td>
                                    <label class="sound_only">상품명</label>
                                    <?if($row['ps_re_order'] == 'N') : ?>
                                        <?=$row['ps_it_name']?>
                                    <?else:?>
                                        <?if($row['ps_re_order'] == 'Y' && $row['ps_reorder_id'] != '') : ?>
                                            (신)<?=$row['ps_it_name']?>
                                        <?else : ?>
                                            (기존)<?=$row['ps_it_name']?>
                                        <?endif?>
                                    <?endif?>
                                </td>
                                <td>
                                    <label class="sound_only">코드</label>
                                    <?= $row['ps_code'] ?>
                                </td>
                                <td>
                                    <label class="sound_only">입고여부</label>
                                    <?= $ipgo_status ?>
                                </td>
                                <td>
                                    <label class="sound_only">촬영완료</label>
                                    <?= $shooting_status ?>
                                </td>
                                <td style="color:red;">
                                    <label class="sound_only">샘플예정일</label>
                                    <?= $row['ps_sample_date'] ?  date("Y.m.d", strtotime($row['ps_sample_date'])) : '' ?>
                                </td>
                                <td style="color:blue;">
                                    <label class="sound_only">출시예정일</label>
                                    <?= $row['ps_ipgo_date'] ?  date("Y.m.d", strtotime($row['ps_ipgo_date'])) : '' ?>
                                </td>
                                <td style="color:green;">
                                    <label class="sound_only">출시확정일</label>
                                    <?= $row['ps_real_ipgo_date'] ?  date("Y.m.d", strtotime($row['ps_real_ipgo_date'])) : '' ?>
                                </td>
                                <td>
                                    <label class="sound_only">제품기획승인일자</label>
                                    <?= $row['ps_prod_proprosal_date'] ?  date("Y.m.d", strtotime($row['ps_prod_proprosal_date'])) : '' ?>
                                </td>
                                <td>
                                    <label class="sound_only">원단발주</label>
                                    <?= $row['ps_balju'] ?  date("Y.m.d", strtotime($row['ps_balju'])) : '' ?>
                                </td>
                                <td>
                                    <label class="sound_only">원단납기예정</label>
                                    <?= $row['ps_expected_limit_date'] ?  date("Y.m.d", strtotime($row['ps_expected_limit_date'])) : '' ?>
                                </td>
                                <td>
                                    <label class="sound_only">원단검품(시험성적)</label>
                                    <?= $row['ps_gumpum'] ?  date("Y.m.d", strtotime($row['ps_gumpum'])) : '' ?>
                                </td>
                                <td>
                                    <label class="sound_only">생산발주</label>
                                    <?= $row['ps_prod_balju'] ?  date("Y.m.d", strtotime($row['ps_prod_balju'])) : '' ?>
                                </td>
                                <td>
                                    <label class="sound_only">생산수량</label>
                                    <?
                                    $ps_sizes = array();
                                    if (!empty($row['ps_size'])) {
                                        $ps_sizes = json_decode($row['ps_size'], true);
                                    }
                                    ?>
                                    <?if (!empty($ps_sizes)) :?>
                                    <?php foreach ($ps_sizes as $si => $ps_size) : ?>
                                        
                                        <p><?=$ps_size['size']?> : <?=$ps_size['qty']?></p>
                                        
                                    <?php endforeach ?>
                                    <?endif?>
                                    
                                </td>
                                <td class="button-g">
                                    <?if ($row['ps_re_order'] == 'N') :?>
                                        <label class="sound_only">작업지시서(아이템)</label>
                                        <?
                                        $jo_item_sql ="select * from lt_job_order where ps_id ={$row['ps_id']}";
    
                                        $jo_item_result= sql_query($jo_item_sql);
                                        $jo_item = 0;
                                        for ($ji = 0; $jo_row = sql_fetch_array($jo_item_result); $ji++) {
                                            if($ji == 0){
                                                $jo_item = $jo_row['jo_id'];
                                            }
                                        ?>
                                            <div><a><?=$jo_row['jo_prod_name'] ? $jo_row['jo_prod_name'] : '임시' ?></a></div>
                                        <?}?>
                                        <!--<button><a href="./job.order.update.form.temp<?= $jo_row['jo_temp']?>.php?w=&amp;ps_id=<?php echo $row['ps_id']; ?>">등록</a></button> -->
                                        <button onclick ="create_job_order('item','<?php echo $jo_item; ?>' , '<?php echo $row['ps_id']; ?>')" ><a href="#">등록</a></button>
                                    <?else :?>

                                    <?endif?>
                                </td>
                                <td class="button-g">
                                    <?if ($row['ps_re_order'] == 'N') :?>
                                        <label class="sound_only">작업지시서(사이즈)</label>
                                        <?
                                        $jo_size_sql ="select * from lt_job_order where ps_id ={$row['ps_id']}";
    
                                        $jo_size_result= sql_query($jo_size_sql);
                                        $jo_size = 0;
                                        for ($jis = 0; $jo_row = sql_fetch_array($jo_size_result); $jis++) {
                                            if($jis == 0){
                                                $jo_size = $jo_row['jo_id'];
                                            }
                                        ?>
                                            <button><a href="./job.order.update.form.temp<?= $jo_row['jo_temp']?>.php?w=u&amp;ps_id=<?php echo $row['ps_id']; ?>&amp;jo_id=<?= $jo_row['jo_id'] ?>"><?=$jo_row['jo_size_code'] ? $jo_row['jo_size_code'] : '임시' ?></a></button>
                                        <?}?>
                                        <!-- <button><a href="./job.order.update.form.temp<?= $jo_row['jo_temp']?>.php?w=&amp;ps_id=<?php echo $row['ps_id']; ?>">등록</a></button> -->
                                        <button onclick ="create_job_order('size','<?php echo $jo_size; ?>' , '<?php echo $row['ps_id']; ?>')" ><a href="#">등록</a></button>
                                    <?else :?>

                                    <?endif?>
                                </td>
                                <td class="button-g">
                                    <?if ($row['ps_re_order'] == 'N') :?>
                                        <label class="sound_only">제품기획서</label>
                                        <?
                                        $ip_sql ="select * from lt_item_proposal where ip_it_name ='{$row['ps_it_name']}'";
    
                                        $ip_result= sql_query($ip_sql);
                                        $ip_data = sql_fetch($ip_sql);
    
                                        for ($ip = 0; $ip_row = sql_fetch_array($ip_result); $ip++) {
                                        ?>
    
                                            <button><a href="./item.proposal.update.form.temp<?= $ip_row['ip_temp']?>.php?w=u&amp;it_name=<?=$ip_row['ip_it_name']?>&amp;ip_id=<?php echo $ip_row['ip_id']; ?>">수정</a></button>
                                        <?}?>
                                        <?if($ip == 0) : ?>
                                            <!-- <button onclick ="create_proposal()" ><a href="./item.proposal.update.form.php?w=&amp;ps_id=<?php echo $row['ps_id']; ?>">등록</a></button> -->
                                            <button onclick ="create_proposal('<?php echo $row['ps_it_name']; ?>')" ><a href="#">등록</a></button>
                                        <?endif?>
                                    <?else :?>

                                    <?endif?>
                                </td>
                                <td class="button-g">
                                    <?if ($row['ps_re_order'] == 'N') :?>
                                        <label class="sound_only">상품정보집</label>
                                        <?
                                        $pi_sql ="select * from lt_prod_info where ps_id ={$row['ps_id']} and pi_size_name is not null";

                                        $pi_result= sql_query($pi_sql);
                                        $pi_result1= sql_query($pi_sql);
                                        $pi_result2= sql_query($pi_sql);
                                        $pi_result3= sql_query($pi_sql);
                                        $pi_result4= sql_query($pi_sql);
                                        $pi_result5= sql_query($pi_sql);
                                        for ($pi = 0; $pi_row = sql_fetch_array($pi_result); $pi++) {
                                        ?>
                                            <button><a href="./item.info.update.form.php?w=u&amp;ps_id=<?php echo $row['ps_id']; ?>&amp;pi_id=<?= $pi_row['pi_id'] ?>"><?=$pi_row['pi_size_name']?></a></button>
                                        <?}?>
                                        <!-- <button><a href="./item.info.update.form.php?w=&amp;ps_id=<?php echo $row['ps_id']; ?>">등록</a></button> -->
                                    <?else :?>

                                    <?endif?>
                                </td>
                                <td class="button-g">
                                    <?if ($row['ps_re_order'] == 'N') :?>
                                        <label class="sound_only">생산일정</label>
                                        <button><a href="./prod.schedule.update.form.php?w=u&amp;ps_id=<?php echo $row['ps_id']; ?>">수정</a></button>
                                    <?else :?>
                                        <label class="sound_only">생산일정</label>
                                        <button><a href="./reorder.prod.schedule.update.form.php?w=u&amp;ps_id=<?php echo $row['ps_id']; ?>">수정</a></button>
                                    <?endif?>
                                </td>

                                <td class="button-g">
                                    <label class="sound_only">작지복사</label>
                                    <button><a href="./job.order.update.form.php?w=copy&amp;ps_id=<?php echo $row['ps_id']; ?>">복사</a></button>
                                </td>
                                
                                <td>
                                    <label class="sound_only">사이즈</label>
                                    <table class="infos_ing_table">
                                    <?for ($pi1 = 0; $pi_row1 = sql_fetch_array($pi_result1); $pi1++) {?>
                                        <tr><td><?=$pi_row1['pi_size_name']?></td></tr>
                                    <?}?>
                                    </table>
                                    
                                    
                                </td>

                                <td>
                                    <label class="sound_only">디자인팀</label>
                                    <table class="infos_ing_table">
                                    <?for ($pi2 = 0; $pi_row2 = sql_fetch_array($pi_result2); $pi2++) {?>
                                        <tr><td>
                                        <?if($pi_row2['pi_sub_category']  && $pi_row2['pi_running_out'] && $pi_row2['pi_season']
                                        && $pi_row2['pi_mater']&& $pi_row2['pi_prod_date']&& $pi_row2['pi_age_gubun']&& $pi_row2['pi_origin_price']&& $pi_row2['pi_sale_price']&& $pi_row2['pi_tag_price']
                                        && $pi_row2['pi_item_soje']&& $pi_row2['pi_color']&& $pi_row2['pi_size']&& $pi_row2['pi_cisu']&& $pi_row2['pi_maker']&& $pi_row2['pi_laundry']
                                        && $pi_row2['pi_prod_weight']&& $pi_row2['pi_xyz']&& $pi_row2['pi_ll_style']
                                        && $pi_row2['pi_info1']&& $pi_row2['pi_info2']&& $pi_row2['pi_info3']
                                        && $pi_row2['pi_detail_info']&& $pi_row2['pi_selling1']&& $pi_row2['pi_selling2']&& $pi_row2['pi_selling3']&& $pi_row2['pi_prod_info1']&& $pi_row2['pi_prod_info2']
                                        && $pi_row2['pi_prod_info3']
                                        ) : ?>
                                        ●
                                        <?else :?>
                                        &nbsp;
                                        <?endif?>
                                    <?}?>
                                    </table>
                                    
                                    
                                </td>
                                <td>
                                    <label class="sound_only">플랫폼팀md</label>
                                    <table class="infos_ing_table">
                                    <?for ($pi3 = 0; $pi_row3 = sql_fetch_array($pi_result3); $pi3++) {?>
                                        <tr><td>
                                        <?if($pi_row3['pi_it_sub_name'] && $pi_row3['pi_it_name'] && $pi_row3['pi_model_name'] && $pi_row3['pi_model_no'] && $pi_row3['pi_company_it_id'] && $pi_row3['pi_brand']) : ?>
                                        ●
                                        <?else :?>
                                        &nbsp;
                                        <?endif?>
                                        </td></tr>
                                    <?}?>
                                    </table>
                                </td>
                                <td>
                                    <label class="sound_only">플랫폼팀디자인</label>
                                    <table class="infos_ing_table">
                                    <?for ($pi4 = 0; $pi_row4 = sql_fetch_array($pi_result4); $pi4++) {?>
                                        <?
                                            $pi_img_chk = 0;
                                            $pi_imgs = array();
                                            if (!empty($pi_row4['pi_img'])) {
                                                $pi_imgs = json_decode($pi_row4['pi_img'], true);
                                            }
                                            foreach ($pi_imgs as $imgs => $Pimgs) {
                                                if($Pimgs['img']){
                                                    $pi_img_chk++;
                                                }
                                            }
                                        ?>
                                        <tr><td>
                                        <?if($pi_img_chk >0) : ?>
                                        ●
                                        <?else :?>
                                        &nbsp;
                                        <?endif?>
                                        </td></tr>
                                    <?}?>
                                    </table>
                                </td>
                                <td>
                                    <label class="sound_only">플랫폼팀마케팅</label>
                                    <table class="infos_ing_table">
                                    <?for ($pi5 = 0; $pi_row5 = sql_fetch_array($pi_result5); $pi5++) {?>
                                        <tr><td>
                                        <?if($pi_row5['pi_video1'] || $pi_row5['pi_video1'] || $pi_row5['pi_video1'] || $pi_row5['pi_video1']) : ?>
                                        ●
                                        <?else :?>
                                        &nbsp;
                                        <?endif?>
                                        </td></tr>
                                    <?}?>
                                    </table>
                                </td>
                                
                            </tr>
                        <?
                        }
                        sql_free_result($result);
                        if ($i == 0)
                            echo '<tr><td colspan="23" class="empty_table">자료가 없습니다.</td></tr>';
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="local_cmd01 local_cmd">
                <div style="float: left">
                    <div class="btn btn_02" style="height: 55px;" onclick ="new_prod_reorder()">신상품 리오더 등록<br>(단일상품 선택 가능)</div>
                    <div class="btn btn_02" style="height: 55px; line-height:45px;" onclick ="location.href='./reorder.prod.schedule.update.form.php?w=re'">기존상품 리오더 등록</div>
                    <div class="btn btn_02" style="height: 55px;" onclick ="ipgo_complate()">입고처리완료<br>(다중상품 선택 가능)</div>
                    <div class="btn btn_02" style="height: 55px;" onclick ="shooting_complate()">촬영완료<br>(다중상품 선택 가능)</div>
                    <div class="btn btn_02" style="height: 55px;" onclick ="down_excel()">엑셀다운로드<br>(다중상품 선택 가능)</div>
                    <!-- <input type="button" value="주문취소(CS)" class="btn btn_02" onclick="forderlist_submit('주문취소');">
                    <input type="button" value="교환요청(CS)" class="btn btn_02" onclick="forderlist_submit('교환요청');" style="display: none;">
                    <input type="button" value="반품요청(CS)" class="btn btn_02" onclick="forderlist_submit('반품요청');"> -->
                </div>
                <div style="float: right">
                    <!-- <div class="btn btn_02" style="height: 55px; line-height:45px;" onclick ="location.href='./job.order.update.form.php?w='">최초작업지시서 등록</div> -->
                    <div class="btn btn_02" style="height: 55px; line-height:45px;" onclick ="create_job_order()">최초작업지시서 등록</div>
                    
                    <!-- <a href="<?= G5_ADMIN_URL ?>/cron/cron_samjin_ordercheck.php" target="_blank" class="btn btn_01">삼진동기화</a>
                    <a href="<?= G5_ADMIN_URL ?>/cron/cron_invoice.php" target="_blank"><input type="button" value="택배동기화" class="btn btn_01"></a>

                    <input type="button" value="주문확인" class="btn btn_02" onclick="forderlist_submit('주문확인');">
                    <input type="button" value="EXCEL" class="btn btn_02 excel_download">
                    <input type="button" value="EXCEL 정산용" class="btn btn_02 excel_download excel_invoice"> -->
                </div>
            </div>

        </form>
        <?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

    </div>
</div>



<div class="modal fade" id="down_load_excel_pop" tabindex="-1" role="dialog" aria-labelledby="down_load_excel_pop">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">엑셀다운로드</h4>
            </div>
            <div class="modal-body">
                <div id="excel_list">
                    <div class="excel_button_area">
                        <input type="hidden" name="excel_ps_id" id="excel_ps_id" value="">
                        <div class="excel_box" onclick="excel_down('job')">작업지시서
                        </div>
                        <div class="excel_box" onclick="excel_down('proposal')">제품기획서</div>
                        <div class="excel_box" onclick="excel_down('info')">상품정보집</div>
                        <div class="excel_box" onclick="excel_down('schedule')">생산일정</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="create_job_order_pop" tabindex="-1" role="dialog" aria-labelledby="create_job_order_pop">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">작업지시서 템플릿 선택</h4>
            </div>
            <div class="modal-body">
                <div id="excel_list">
                    <div class="excel_button_area">
                        <input type="hidden" name="jo_create_type" id="jo_create_type" value=""/>
                        <input type="hidden" name="jo_create_jo_id" id="jo_create_jo_id" value=""/>
                        <input type="hidden" name="jo_create_ps_id" id="jo_create_ps_id" value=""/>
                        <div class="excel_box" onclick ="go_job_order_temp(1)">국내가공완선-속통</div>
                        <div class="excel_box" onclick ="go_job_order_temp(2)">해외반제-국내완성</div>
                        <div class="excel_box" onclick ="go_job_order_temp(3)">해외임가공-국내완성</div>
                        <div class="excel_box" onclick ="go_job_order_temp(4)">해외완제품-속통,커버 통일</div>
                        <div class="excel_box" onclick ="go_job_order_temp(5)">국내가공완성-커버</div>
                        <div class="excel_box" onclick ="go_job_order_temp(6)">국내완제품-커버</div>
                        <div class="excel_box" onclick ="go_job_order_temp(7)">해외임가공-커버</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="create_proposal_pop" tabindex="-1" role="dialog" aria-labelledby="create_proposal_pop">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">제품기획서 템플릿 선택</h4>
            </div>
            <div class="modal-body">
                <div id="excel_list">
                    <div class="excel_button_area">
                        <input type="hidden" name="ip_it_name_item" id="ip_it_name_item" value=""/>
                        <div class="excel_box" onclick ="go_proposal_temp(1)">국내 임가&완제_커버</div>
                        <div class="excel_box" onclick ="go_proposal_temp(2)">국내 임가&완제_커버_온라인전용</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    

    /* 탭 */
    ul.tabs{
        margin: 0px;
        padding: 0px;
        list-style: none;
    }
    ul.tabs li{
        background: none;
        color: #222;
        display: inline-block;
        padding: 10px 15px;
        cursor: pointer;
    }

    ul.tabs li.current{
        background: #ededed;
        color: #222;
    }

    .tab-content{
        display: none;
        background: #ededed;
        padding: 15px;
        position :relative;
    }

    .tab-content.current{
        display: inherit;
    }


    /* 캘린더 */
    .calendar th{text-align:center;height: 60px;  background-color: #f1f1f1;border: 1px solid #979797;  font-size: 24px;  font-weight: 500;  color: #000000;}
    .cal_top{
        text-align: center;
        font-size: 30px;
        height : 125px;
        line-height : 125px;
    }    
    .cal{
        text-align: center; 
    }
    table.calendar{
        border: 1px solid black;
        display: inline-table;
        text-align: left;
    }
    table.calendar td{
        vertical-align: top;
        border: 1px solid #979797;
        width: 100px;
        background-color: #ffffff;
        position: relative;
    }
    table.calendar td .cal-day{
        text-align : right;
        height : 22px;
        font-size: 18px;
        font-weight: 600;
        color: #6d7278;
    }
    .cal-schedule {height : 113px; overflow-y : auto; word-break:break-all; width : 227px; padding: 10px;  }

    .process_type {
        font-size: 16px;
        font-weight: 500;
        color: #000000;
        position : absolute;
        right : 15px;
        top : 30px;
        padding : 10px;
        border: 1px solid #979797;
    }
    
    /* 리스트 */
    .new_goods_list{
        background-color : #ffffff;
        
        border-collapse: collapse;
    }
    .new_goods_list th, td {
        
    }

    button{margin:0; padding : 0;}
    .button-g button{margin: 2px auto;}
    .button-g button a {display: block;}


    button a {
        margin:0; padding : 0;
    }

    .infos_ing_table tbody > tr {background : none !important;}
    .infos_ing_table tbody > tr td{border : 0 !important;}

    .excel_button_area{display:flex; justify-content: space-evenly; height : 500px;}
    .excel_box{
        width: 200px;
        height: 50px;
        text-align: center;
        border: 1px solid #333333;
        line-height: 50px;
        background: #e0e0e0;
        cursor: pointer;
        color: #333333;
    }

</style>

<script>
    var today = null;
    var year = null;
    var month = null;
    var firstDay = null;
    var lastDay = null;
    var $tdDay = null;
    var $tdSche = null;
    var jsonData = null;
    $(document).ready(function() {
        drawCalendar();
        initDate();
        drawDays();
        drawSche();
        $("#movePrevMonth").on("click", function(){movePrevMonth();});
        $("#moveNextMonth").on("click", function(){moveNextMonth();});
        $("#topscroll").scroll(function(){
            $("#bottomscroll").scrollLeft($("#topscroll").scrollLeft());
        });
        $("#bottomscroll").scroll(function(){
            $("#topscroll").scrollLeft($("#bottomscroll").scrollLeft());
        });
    });
    
    //Calendar 그리기
    function drawCalendar(){
        var setTableHTML = "";
        setTableHTML+='<table class="calendar">';
        setTableHTML+='<tr><th>일</th><th>월</th><th>화</th><th>수</th><th>목</th><th>금</th><th>토</th></tr>';
        for(var i=0;i<6;i++){
            setTableHTML+='<tr height="135">';
            for(var j=0;j<7;j++){
                setTableHTML+='<td style="text-overflow:ellipsis;overflow:hidden;white-space:nowrap">';
                setTableHTML+='    <div class="cal-day"></div>';
                setTableHTML+='    <div class="cal-schedule"></div>';
                setTableHTML+='</td>';
            }
            setTableHTML+='</tr>';
        }
        setTableHTML+='</table>';
        $("#cal_tab").html(setTableHTML);
    }
    
    //날짜 초기화
    function initDate(){
        $tdDay = $("td div.cal-day")
        $tdSche = $("td div.cal-schedule")
        dayCount = 0;
        today = new Date();
        year = today.getFullYear();
        month = today.getMonth()+1;
        if(month < 10){month = "0"+month;}
        firstDay = new Date(year,month-1,1);
        lastDay = new Date(year,month,0);
    }
    
    //calendar 날짜표시
    function drawDays(){
        $("#cal_top_year").text(year);
        $("#cal_top_month").text(month);
        for(var i=firstDay.getDay();i<firstDay.getDay()+lastDay.getDate();i++){
            $tdDay.eq(i).text(++dayCount);
        }
        for(var i=0;i<42;i+=7){
            $tdDay.eq(i).css("color","red");
        }
        for(var i=6;i<42;i+=7){
            $tdDay.eq(i).css("color","blue");
        }
    }
    
    //calendar 월 이동
    function movePrevMonth(){
        month--;
        if(month<=0){
            month=12;
            year--;
        }
        if(month<10){
            month=String("0"+month);
        }
        getNewInfo();
        }
    
    function moveNextMonth(){
        month++;
        if(month>12){
            month=1;
            year++;
        }
        if(month<10){
            month=String("0"+month);
        }
        getNewInfo();
    }
    
    //정보갱신
    function getNewInfo(){
        for(var i=0;i<42;i++){
            $tdDay.eq(i).text("");
            $tdSche.eq(i).text("");
        }
        dayCount=0;
        firstDay = new Date(year,month-1,1);
        lastDay = new Date(year,month,0);
        drawDays();
        drawSche();
    }
    
    
    //2019-08-27 추가본
    
    //데이터 등록
    // function setData(){
    //     jsonData = 
    //     {
    //         "2020":{
    //             "08":{
    //                 "7":"칠석"
    //                 ,"15":[
    //                     "광복절","ㅇㄹ" ,"ㅁㄴㅇㄹ"
    //                 ]
                    
    //                 ,"23":"처서"
    //             }
    //             ,"09":{
    //                 "13":"추석"
    //                 ,"23":"추분"
    //             }
    //         }
    //     }
    // }
    
    //스케줄 그리기
    function drawSche(){
        // setData();
        var sample = <? echo json_encode($sample_data)?>;
        var bipgo = <? echo json_encode($bipgo_data)?>;
        var aipgo = <? echo json_encode($aipgo_data)?>;
        var dateMatch = null;
        for(var i=firstDay.getDay();i<firstDay.getDay()+lastDay.getDate()+1;i++){
            var txt = "";
            var sam_txt = "";
            var bi_txt = "";
            var ai_txt = "";
            var one_txt = "";

            var sam_name = "";
            var bi_name = "";
            var ai_name = "";
            
            for(var k = 0 ; k < sample.length; k++){
                if((sample[k]['s_year'] * 1) == (year*1) && (sample[k]['s_month']*1) == (month*1) && (firstDay.getDay() + (sample[k]['s_day']*1)) == i  ){
                    if(sample[k]['ps_re_order'] == 'N'){
                        sam_name = sample[k]['ps_it_name'];
                    }else{
                        if(sample[k]['ps_re_order'] == 'Y' && sample[k]['ps_reorder_id'] >0){
                            sam_name =  sample[k]['ps_it_name'] + ' R' + sample[k]['ps_reorder_id'];
                        }else{
                            sam_name =  sample[k]['ps_it_name'] + 'R';
                        }
                    }
                    arr_txt = "<div><a style='color:#f90000;' href='/adm/shop_admin/new_goods/new_goods_process.php?tabs=list' target ='_blank'>" + sam_name+ "</a></div>";
                    //dateMatch = firstDay.getDay() + i -1;
                    dateMatch = i-1;
                    $tdSche.eq(dateMatch).append(arr_txt);
                }
            }
            for(var j = 0 ; j < bipgo.length; j++){
                if((bipgo[j]['s_year'] * 1) == (year*1) && (bipgo[j]['s_month']*1) == (month*1) && (firstDay.getDay() + (bipgo[j]['s_day']*1)) == i ){
                    //alert(items[k]['s_day'])
                    if(bipgo[j]['ps_re_order'] == 'N'){
                        bi_name = bipgo[j]['ps_it_name'];
                    }else{
                        if(bipgo[j]['ps_re_order'] == 'Y' && bipgo[j]['ps_reorder_id'] >0 ){
                            bi_name =  bipgo[j]['ps_it_name'] + ' R' + bipgo[j]['ps_reorder_id'];
                        }else{
                            bi_name =  bipgo[j]['ps_it_name'] + 'R';
                        }
                    }
                    bi_txt = "<div><a style='color:#1c5eff;' href='/adm/shop_admin/new_goods/new_goods_process.php?tabs=list' target ='_blank'>" + bi_name+ "</a></div>";
                    //dateMatch = firstDay.getDay() + i -1;
                    dateMatch =  i -1;
                    $tdSche.eq(dateMatch).append(bi_txt);
                }
            }
            
            for(var l = 0 ; l < aipgo.length; l++){
                if((aipgo[l]['s_year'] * 1) == (year*1) && (aipgo[l]['s_month']*1) == (month*1) && (firstDay.getDay() +  (aipgo[l]['s_day']*1)) == i ){
                    //alert(items[l]['s_day'])
                    if(aipgo[l]['ps_re_order'] == 'N'){
                        ai_name = aipgo[l]['ps_it_name'];
                    }else{
                        if(aipgo[l]['ps_re_order'] == 'Y' && aipgo[l]['ps_reorder_id'] > 0){
                            ai_name =  aipgo[l]['ps_it_name'] + ' R' + aipgo[l]['ps_reorder_id'];
                        }else{
                            ai_name =  aipgo[l]['ps_it_name'] + 'R';
                        }
                    }
                    ai_txt = "<div><a style='color:#62c500;' href='/adm/shop_admin/new_goods/new_goods_process.php?tabs=list' target ='_blank'>" + ai_name+ "</a></div>";
                    dateMatch =  i - 1;
                    $tdSche.eq(dateMatch).append(ai_txt);
                }
            }
            //txt =jsonData[year];
            // if(txt){
            //     txt = jsonData[year][month];
            //     if(txt){
            //         txt = jsonData[year][month][i];
            //         if(Array.isArray(txt)){
            //             for(var j = 0 ; j < txt.length; j++){
            //                 arr_txt = "<div><a href='https://www.naver.com' target ='_blank'>" + txt[j]+ "</a></div>";
            //                 dateMatch = firstDay.getDay() + i -1;
            //                 $tdSche.eq(dateMatch).append(arr_txt);
            //             }
            //         }else{
            //             if(txt){
            //                 one_txt ="<div><a href='https://www.naver.com' target ='_blank'>" + txt + "</a></div>";
            //             }
            //             dateMatch = firstDay.getDay() + i -1; 
            //             $tdSche.eq(dateMatch).append(one_txt);
            //         }
                    
            //     }
            // }
        }
    }

    $('ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
        $("#"+tab_id).addClass('current');
        var newURL =  window.location.href.split("?");
        if(tab_id ==  'tab-2'){
          window.location.href  =  newURL[0]+'?tabs=list';
        }else{
            window.location.href  =  newURL[0];
        }
    });

    // function job_order_update(pid,size){
    //     window.location.href="./job.order.update.php?w=u&ps_id"+pid+"$jo_size="+size;
    // }

    // 일자
    $('#it_time').daterangepicker({
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
    	/*,ranges: {
	           '오늘': [moment(), moment()],
	           '3일': [moment().subtract(2, 'days'), moment()],
	           '1주': [moment().subtract(6, 'days'), moment()],
	           '1개월': [moment().subtract(1, 'month'), moment()],
	           '3개월': [moment().subtract(3, 'month'), moment()],
	           '이번달': [moment().startOf('month'), moment().endOf('month')],
	           '마지막달': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	        }*/
	});
	//alert($("button[name='dateBtn'].btn_03").attr("data"));
	$('#it_time').val("<?php echo $sc_it_time ?>");
	
	//날짜 버튼
	$("button[name='dateBtn']").click(function(){
		
		var d = $(this).attr("data");
		if(d == "all") {
			$('#it_time').val("");
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
    
    		$('#it_time').data('daterangepicker').setStartDate(startD);
    		$('#it_time').data('daterangepicker').setEndDate(endD);
		}
	
	});

    $(".brand").change(function(){
        var ps_brands = "";
        $("#brand_0").attr('checked',false);
        $("input.brand:checked").each(function(){
            //alert($(this).val());
            if(ps_brands != "") ps_brands += ",";
            ps_brands += $(this).val();

        });
        $("#brands").val(ps_brands);
    });
    $("#brand_0").change(function(){
        if($("input#brand_0:checked")){
            $(".brand").attr('checked',false);
            $("#brands").val('');
        }
    });

    $(".ipgoyn").change(function(){
        var ps_ipgo = "";
        $("input.ipgoyn:checked").each(function(){
            //alert($(this).val());
            if(ps_ipgo != "") ps_ipgo += ",";
            ps_ipgo += $(this).val();

        });
        $("#ipgos").val(ps_ipgo);
    });
    $(".shooting").change(function(){
        var ps_shooting = "";
        $("input.shooting:checked").each(function(){
            //alert($(this).val());
            if(ps_shooting != "") ps_shooting += ",";
            ps_shooting += $(this).val();

        });
        $("#shootings").val(ps_shooting);
    });

    $(".reorder").change(function(){
        var ps_reorder = "";
        $("input.reorder:checked").each(function(){
            //alert($(this).val());
            if(ps_reorder != "") ps_reorder += ",";
            ps_reorder += $(this).val();

        });
        $("#reorders").val(ps_reorder);
    });

    $('.search-reset').on("click",function() {
        $('.stx').val('');
        $('.sc_it_time').val('');
        
        $(".brand").attr('checked',false);
        $("#brand_0").attr('checked',true);
        $("#brands").val('');
        $(".ipgoyn").attr('checked',false);
        $("#ipgoyn_0").attr('checked',true);
        $("#ipgos").val('');
        $(".reorder").attr('checked',true);
        $("#reorders").val('');
        $(".shooting").attr('checked',true);
        $("#shootings").val('');

        
    });

    function new_prod_reorder(){
        if (!is_checked("chk[]")) {
	        alert("리오더 할 상품을 선택해주세요.");
	        return false;
	    }
        if($("input[name='chk[]']:checked").length > 1){
            alert("단일 상품만 선택해주세요.");
	        return false;
        }
        var chk = $("input[name='chk[]']:checked").val();
        var ps_re_order = $("input[name='ps_re_order["+chk+"]']").val();
        if(ps_re_order =='Y'){
            alert("리오더 상품은 선택할 수 없습니다.");
	        return false;
        }
        var ps_id = $("input[name='ps_id["+chk+"]']").val();

        location.href='./reorder.prod.schedule.update.form.php?w=&ps_id='+ps_id;
    }

    function down_excel(){
        if (!is_checked("chk[]")) {
	        alert("엑셀 다운로드 할 상품을 선택해주세요.");
	        return false;
        }
        $('#down_load_excel_pop').modal('show');
    }

    function create_job_order(type,jo_id,ps_id){
        $('#jo_create_type').val(type);
        $('#jo_create_jo_id').val(jo_id);
        $('#jo_create_ps_id').val(ps_id);
        $('#create_job_order_pop').modal('show');
    }
    function go_job_order_temp(temp){
        let type = $('#jo_create_type').val();
        let jo_id = $('#jo_create_jo_id').val();
        let ps_id = $('#jo_create_ps_id').val();
        if(type=='item'){
            ps_id = '';
        }
        if(temp == 1){
            location.href='./job.order.update.form.temp1.php?w=&ps_id='+ps_id+'&type='+type+'&be_jo_id='+jo_id;
        }else if(temp == 2){
            location.href='./job.order.update.form.temp2.php?w=&ps_id='+ps_id+'&type='+type+'&be_jo_id='+jo_id;
        }else if(temp == 3){
            location.href='./job.order.update.form.temp3.php?w=&ps_id='+ps_id+'&type='+type+'&be_jo_id='+jo_id;
        }else if(temp == 4){
            location.href='./job.order.update.form.temp4.php?w=&ps_id='+ps_id+'&type='+type+'&be_jo_id='+jo_id;
        }else if(temp == 5){
            location.href='./job.order.update.form.temp5.php?w=&ps_id='+ps_id+'&type='+type+'&be_jo_id='+jo_id;
        }else if(temp == 6){
            location.href='./job.order.update.form.temp6.php?w=&ps_id='+ps_id+'&type='+type+'&be_jo_id='+jo_id;
        }else if(temp == 7){
            location.href='./job.order.update.form.temp7.php?w=&ps_id='+ps_id+'&type='+type+'&be_jo_id='+jo_id;
        }
    }
    


    function create_proposal(item_name){
        $('#ip_it_name_item').val(item_name);
        $('#create_proposal_pop').modal('show');
    }
    function go_proposal_temp(temp){
        let it_name = $('#ip_it_name_item').val();
        if(temp == 1){
            location.href='./item.proposal.update.form.temp1.php?w=&it_name='+it_name;
        }else{
            location.href='./item.proposal.update.form.temp2.php?w=&it_name='+it_name;
        }
    }

    function excel_down(type){
        var chk = $("input[name='chk[]']:checked").val();
        var $select = new Array();
        $("#excel_ps_id").val('');

        $("input[name='chk[]']:checked").each(function() {
            var ps_id = $("input[name='ps_id["+this.value+"]']").val();
            $select.push(ps_id);

        });

        var selects = $select.join(",");
        if ($("#excel_ps_id").val() != "") selects += "," + $("#excel_ps_id").val();
        $("#excel_ps_id").val(selects);


        var excel_sql = "";
        var excel_type = "";

        var headerdata = "";
        var bodydata = "";

        if(type == 'job'){
            excel_sql = "select * from lt_job_order where ps_id in ( "+selects +" )";
            headerdata = $('<input type="hidden" value="<?=$job_headers?>" name="headerdata">');
            bodydata = $('<input type="hidden" value="<?=$job_bodys?>" name="bodydata">');
            excel_type = '작업지시서';
        }else if(type == 'proposal'){
            excel_sql = "select * from lt_item_proposal where ps_id in ( "+selects +" )";
            headerdata = $('<input type="hidden" value="<?=$proposal_headers?>" name="headerdata">');
            bodydata = $('<input type="hidden" value="<?=$proposal_bodys?>" name="bodydata">');
            excel_type = '제품기획서';
        }else if(type == 'info'){
            excel_sql = "select * from lt_prod_info where ps_id in ( "+selects +" )";
            headerdata = $('<input type="hidden" value="<?=$info_headers?>" name="headerdata">');
            bodydata = $('<input type="hidden" value="<?=$info_bodys?>" name="bodydata">');
            excel_type = '상품정보집';
        }else if(type == 'schedule'){
            excel_sql = "select * from lt_prod_schedule where ps_id in ( "+selects +" )";
            headerdata = $('<input type="hidden" value="<?=$schedule_headers?>" name="headerdata">');
            bodydata = $('<input type="hidden" value="<?=$schedule_bodys?>" name="bodydata">');
            excel_type = '생산일정';
        }

        var $form = $('<form></form>');     
        $form.attr('action', '<?=G5_ADMIN_URL?>/ajax.excel_download.new.goods.php');
        $form.attr('method', 'post');
        $form.appendTo('body');
        
        var exceldata = $('<input type="hidden" value="'+excel_sql+'" name="exceldata">');
        
        var excelnamedata = $('<input type="hidden" value="'+excel_type+'" name="excelnamedata">');
        $form.append(exceldata).append(headerdata).append(bodydata).append(excelnamedata);
        $form.submit();

    }
    //입고완료처리
    function ipgo_complate(){
        var chk = $("input[name='chk[]']:checked").val();
        var complete = false;
        //var ps_id = $("input[name='ps_id["+chk+"]']").val();

        $("input[name='chk[]']:checked").each(function() {
            var ps_id = $("input[name='ps_id["+this.value+"]']").val();            
            $.ajax({
                url:'./prod.schedule.ipgo.update.php',
                type:'post',
                async: false,
                data:{ps_id : ps_id },
                
                error:function(error){
                    complete = false;  
                },
                success:function(response){
                    complete = true;                  
                }
            });
        });
        if(complete == true){
            alert("입고처리가 완료되었습니다.");
            location.reload();
        }

    }
    //촬영완료처리
    function shooting_complate(){
        var chk = $("input[name='chk[]']:checked").val();
        var complete = false;
        //var ps_id = $("input[name='ps_id["+chk+"]']").val();

        $("input[name='chk[]']:checked").each(function() {
            var ps_id = $("input[name='ps_id["+this.value+"]']").val();            
            $.ajax({
                url:'./prod.schedule.shooting.update.php',
                type:'post',
                async: false,
                data:{ps_id : ps_id },
                
                error:function(error){
                    complete = false;  
                },
                success:function(response){
                    complete = true;                  
                }
            });
        });
        if(complete == true){
            alert("촬영상태가 완료되었습니다.");
            location.reload();
        }

    }

    $("#new_goods_form").submit(function(){
        
        let tab_gu = 'list';
        $.ajax({
                type : "GET",
                //url : "/new_goods_process.php",
                //dataType : "text",
                data : {tabs : tab_gu},
                error : function() {
                    alert('통신실패!!');
                },
                success : function(data) {
                    $('#Context').html(data);
                }
        
            });
    });

</script>


<?php 
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
