<?php
$sub_menu = '930200';
include_once('./_common.php');


auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '신규제품개발현황';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

if($od_type == "") $od_type = "L";

$tabs = $_GET['tabs'];

$txt1= $_GET['stx'];

$sql_search = " where (1) and ps_display = 'Y' ";

if ($stx) {
    switch($sfl){
            
        case 'sap_code':
            preg_match_all("/[^() || \-\ \/\,]+/", $txt1,$sap_cd_list);
            $sap_cd_in_list = empty($sap_cd_list[0])?'NULL':"'".join("','", $sap_cd_list[0])."'";
            $sql_search.= " AND CONCAT( ps_code_gubun , ps_code_brand ,ps_code_year , ps_code_season,ps_code_item_type,ps_code_index, ps_code_item_name ) IN({$sap_cd_in_list}) ";
            break;
        
        case 'it_name':
            $sql_search .= " and ps_it_name like '%{$txt1}%' ";
            break;

        case 'sap_code_w':
            $s_codes = "SELECT jo_id_code FROM lt_job_order where jo_id_code like '%{$txt1}%' ";
            $s_result = sql_query($s_codes);
            
            for($sii = 0; $s_row = sql_fetch_array($s_result); $sii++ ){
                if($sii == 0){
                    if( !empty($s_row['jo_id_code'])){
                        $sap_cd_in_lists .="'".$s_row['jo_id_code']."'" ; 
                    }
                }else{
                    $sap_cd_in_lists .=",'".$s_row['jo_id_code']."'" ; 
                }
            }

            $sql_search .= " AND CONCAT( ps_code_gubun , ps_code_brand ,ps_code_year , ps_code_season,ps_code_item_type,ps_code_index, ps_code_item_name ) IN({$sap_cd_in_lists}) ";
            break;
    }
    
}

$all_brand = false;
if ($brands) {
    $brand_item = implode("','", explode(',', $brands));
    $sql_search .= " and ps_brand in ('{$brand_item}') ";
}else{
    $all_brand=true;
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


if(!$code_year){
    // $sql_search .= " and ps_ipgo_status = 'N' ";
}else{
    if(strpos($code_year , '>') === false){
        $year = substr($code_year,-2);
        $sql_search .= " and ps_code_year = '{$year}' ";
    }else{
        
        $year .= substr($code_year,-2);
        $sql_search .= " and ps_code_year >= {$year}";
    }
    
    
}

if(!$code_season){
    // $sql_search .= " and ps_ipgo_status = 'N' ";
}else{
    $season = $code_season;
    $sql_search .= " and ps_code_season = '{$season}' ";
}

if(!$ipgos){
    // $sql_search .= " and ps_ipgo_status = 'N' ";
}else{
    if ($ipgos == 'Y') {
        $sql_search .= " and ps_ipgo_status = 'Y' ";
    }else{
        $ipgos_item = implode("','", explode(',', $ipgos));
        $sql_search .= " and ps_ipgo_status in ('{$ipgos_item}') ";
    }
}
if(!$dpart_ipgos){
    // $sql_search .= " and ps_ipgo_status = 'N' ";
}else{
    if ($dpart_ipgos == 'Y') {
        $sql_search .= " and ps_dpart_stock = 'Y' ";
    }else{
        $dpart_ipgos_item = implode("','", explode(',', $dpart_ipgos));
        $sql_search .= " and ps_dpart_stock in ('{$dpart_ipgos_item}') ";
    }
}
if (!$shootings) {
    //$sql_search .= " and ps_shooting_yn in ('N','Y') ";
}else{
    if ($shootings == 'Y') {
        $sql_search .= " and ps_shooting_yn = 'Y' and ps_code_year >= 21 ";
    }else if($shootings == 'N'){
        $sql_search .= " and ps_shooting_yn = 'N' and ps_code_year >= 21  ";
    }
}
//
if (!$gumsus) {
    //$sql_search .= " and ps_shooting_yn in ('N','Y') ";
}else{
    if ($gumsus == 'Y') {
        $sql_search .= " and ps_gumsu in (100,300) ";
    }else if ($gumsus == 'N'){
        $sql_search .= " and (ps_gumsu in (400,500) or (ps_gumsu = 200  and ps_gumsu_sub in (100,300) )) ";
        
    }else{
        //$sql_search .= " and ps_gumsu in () ";
    }
}
if (!$gumsu_subs) {
    //$sql_search .= " and ps_shooting_yn in ('N','Y') ";
}else{
    if ($gumsu_subs == 'Y') {
        $sql_search .= " and ps_gumsu_sub in (100,300) ";
    }else if ($gumsu_subs == 'N'){
        $sql_search .= " and ps_gumsu_sub in (400,500) ";
        
    }else{
        //$sql_search .= " and ps_gumsu in () ";
    }
}
if (!$item_details) {
    //$sql_search .= " and ps_shooting_yn in ('N','Y') ";
}else{
    if ($item_details == 'Y') {
        $sql_search .= " and ps_item_detail in (100,300) ";
    }else if ($item_details == 'N'){
        $sql_search .= " and ps_item_detail in (200,300) ";
    }
}
if (!$sabangs) {
    //$sql_search .= " and ps_shooting_yn in ('N','Y') ";
}else{
    if ($sabangs == 'Y') {
        $sql_search .= " and ps_sabang_send in (100,300) ";
    }else if ($sabangs == 'N'){
        $sql_search .= " and ps_sabang_send in (200,300) ";
    }
}
if (!$fixeds) {
    //$sql_search .= " and ps_shooting_yn in ('N','Y') ";
}else{
    if ($fixeds == 'Y') {
        $sql_search .= " and ps_price_fixed in (100,300) ";
    }else if ($fixeds == 'N'){
        $sql_search .= " and ps_price_fixed in (200,300) ";
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


if (!$chain_items) {
    
}else{
    if ($chain_items == 'Y') {
        $sql_search .= " and ps_chain_gb = 'Y' ";
    }else{
        
    }
}

if (!$ps_user_chks) {
    
}else{
    if (!empty($ps_user_chks)) {
        $ps_user_chks_item = implode("','", explode(',', $ps_user_chks));
        $sql_search .= " and ps_user in ('{$ps_user_chks_item}') ";
        
    }else{
        
    }
}

// $chain_orderby .= " PS.ps_chain_gb DESC ,  PS.ps_chain_code ASC , " ;


// 테이블의 전체 레코드수만 얻음
$cnt_sql = "SELECT COUNT(*) AS cnt FROM (SELECT ps_it_name  FROM lt_prod_schedule {$sql_search}  GROUP BY ps_it_name) pin";
$cnt_row = sql_fetch($cnt_sql);
$total_count = $cnt_row['cnt'];


if($limit_list) $rows = $limit_list;
else $rows = $config['cf_page_rows'];
// $rows=4;

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

//행 병합  ver 1
// $abs_sql = "select MIN(ps_id) AS sort, PS.* from lt_prod_schedule AS PS {$sql_search} GROUP BY ps_it_name ORDER BY sort DESC ";
// $abs_result = sql_query($abs_sql);

//행 병ㅎ합 ver 1.1 수정 20201111
$abs_sql = "select MIN(PS.ps_origin_ps_id) AS sort , PS.* from lt_prod_schedule AS PS {$sql_search} GROUP BY ps_it_name ORDER BY {$chain_orderby} sort DESC limit $from_record, $rows ";
$abs_result = sql_query($abs_sql);

$abs_result1 = sql_query($abs_sql);


$sub_table_data ;
for ($tdi = 0; $row_sub_table_data = sql_fetch_array($abs_result1); $tdi++) {
    if(!empty($sub_table_data)){
        $sub_table_data .=  ',';
    }
    $sub_table_data .= "'". $row_sub_table_data['ps_it_name'] ."'";
}

// if (!empty($stx)) {
//     $sql_search .= " and ps_it_name IN ('{$sub_table_data}') ";
// }


//$sql = "select * from lt_prod_schedule {$sql_search} ORDER BY  ps_origin_ps_id IS NULL ASC , ps_origin_ps_id ASC, ps_id ASC limit $from_record, $rows ";

//$result = sql_query($sql);

$qstr= "tabs=list&amp;brands=".$brands."&amp;code_year=".$code_year."&amp;code_season=".$code_season."&amp;ipgos=".$ipgos."&amp;dpart_ipgos=".$dpart_ipgos."&amp;shootings=".$shootings."&amp;gumsus=".$gumsus."&amp;gumsu_subs=".$gumsu_subs."&amp;item_details=".$item_details."&amp;sabangs=".$sabangs."&amp;fiexeds=".$fiexeds."&amp;folds=".$folds."&amp;reorders=".$reorders."&amp;chain_items=".$chain_items."&amp;sfl=it_name&amp;stx=".$txt1."&amp;sc_it_time=".$sc_it_time."&amp;limit_list=".$limit_list."&amp;page=".$page;


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
                
$job_headers = array('NO','브랜드명','상품명','제품코드','담당자','작성일','품종','품목(아이템)','사이즈','시즌','소재(품질표시)','디자인이미지','원자재정보','부자재','봉제공임','주입비용','포장비','생산원가','생산관리비','총원가');
$job_bodys = array('NO','jo_brand','jo_it_name','jo_id_code','jo_user','jo_reg_date','jo_prod_type','jo_prod_name','jo_size_code','jo_season','jo_soje','jo_design_img','jo_mater_info','jo_sub_mater','jo_bongje','jo_juip_price','jo_pack_price','jo_prod_origin_price','jo_prod_control_price','jo_total_origin_price');

$proposal_headers = array('NO','브랜드명','상품명','작성일','품목아이템','구분','연도','시즌','생산구분','색상','출하시기','제품입고처','원산지','제조사','수입자','판매자','기획읜도','원자재 매입처','임가공(수입)','완제품아이템','실적참고데이터');
$proposal_bodys = array('NO','ip_brand','ip_it_name','ip_reg_date','ip_prod_name','ip_gubun','ip_year','ip_season','ip_prod_gubun','ip_color','ip_clha_date','ip_item_ipgoer','ip_mater','ip_maker','ip_importer','ip_seller','ip_proposal_memo','ip_mater_purchace','ip_processing','ip_finished','ip_performance');

$info_headers = array('NO','중분류','디자인컨셉','디자인스타일','재고연령','러닝/아웃','시즌','상품명(사방넷상품명)','상품약어(삼진상품명)','모델명(삼진코드)','모델no(SAP코드)','자체상품코드(SAP색상사이즈)','브랜드명','카테고리','원산지','남녀구분','배송비','원가','1차판매가','2차판매가','TAG가','제품소재','색상','사이즈(코드)','치수','제조국','세탁방법','kc안전인증 대상유무','수입여부','상품무게','상품 가로세로높이','충전재','자사몰스타일','프라우덴 우모사용 유/무','항균가공 정보','항균가공 정보(기타)','필파워','필파워 인증서 유/무','원단상세정보 1-기업정보','원단상세정보 2 - 시험성적서 유/무','원단상세정보 2-1','원단상세정보 3- OEKO-TEX 인증 유/무','상품기술서이미지경로1','상품기술서이미지경로2','상품기술서이미지경로3','상품기술서이미지경로4','상품기술서이미지경로전체','동영상경로1','동영상경로2','동영상경로3','동영상경로4','제품원본이미지경로','상품설명','셀링포인트1','셀링포인트2','셀링포인트3','제품정보1','제품정보2','제품정보3','제품정보4','제품정보5','제품정보6','제품정보7','제품정보8','제품정보9','제품정보10','비고');
$info_bodys = array('NO','pi_sub_category','pi_design_style','pi_design_style_sub','pi_jego_age','pi_running_out','pi_season','pi_it_name','pi_it_sub_name','pi_model_name','pi_model_no','pi_company_it_id','pi_brand','pi_category','pi_mater','pi_age_gubun','pi_delivery_price','pi_origin_price','pi_sale_price','pi_sale_price2','pi_tag_price','pi_item_soje','pi_color','pi_size_name','pi_cisu','pi_maker','pi_laundry','pi_kc_safe_yn','pi_soip_yn','pi_prod_weight','pi_xyz','pi_charge','pi_ll_style','pi_prauden_umu_yn','pi_hangkun_info','pi_hangkun_info_txt','pi_pilpower','pi_pilpower_safe_yn','pi_info1','pi_info2','pi_info2_1','pi_info3','pi_img','pi_img2','pi_img3','pi_img4','pi_img_total','pi_video1','pi_video2','pi_video3','pi_video4','pi_origin_image','pi_detail_info','pi_selling1','pi_selling2','pi_selling3','pi_prod_info1','pi_prod_info2','pi_prod_info3','pi_prod_info4','pi_prod_info5','pi_prod_info6','pi_prod_info7','pi_prod_info8','pi_prod_info9','pi_prod_info10','etc');

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

function strtr_kh($str){
	$trans = array("(" => "", ")" => "" , "." => "");
	$str = strtr($str, $trans);
	return $str;
}
$allowAdmin = "select * from lt_admin where mb_id = '{$member['mb_id']}'";
$allowA = sql_fetch($allowAdmin);



$manager = "SELECT * FROM lt_member AS ltm LEFT JOIN lt_admin AS lta ON ltm.mb_id = lta.mb_id WHERE lta.mb_dept = '디자인팀'";
$manager_res = sql_query($manager);

?>

<script src="<?= G5_JS_URL ?>/swiper/swiper.min.js" type="text/javascript" charset="utf-8"></script>


<div class="container">

	<ul class="tabs">
		<li class="tab-link <?=$tabs == 'list' ? 'current' : '' ?>" data-tab="tab-2">리스트</li>
		<li class="tab-link <?=$tabs ? '' : 'current' ?>" data-tab="tab-1">캘린더</li>
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
            <input type="hidden" name = "code_year" value='<?=$code_year?>' id="code_year">
            <input type="hidden" name = "code_season" value='<?=$code_season?>' id="code_season">
            <input type="hidden" name = "ipgos" value='<?=$ipgos?>' id="ipgos">
            <input type="hidden" name = "dpart_ipgos" value='<?=$dpart_ipgos?>' id="dpart_ipgos">
            <input type="hidden" name = "shootings" value='<?=$shootings?>' id="shootings">
            <input type="hidden" name = "gumsus" value='<?=$gumsus?>' id="gumsus">
            <input type="hidden" name = "gumsu_subs" value='<?=$gumsu_subs?>' id="gumsu_subs">
            <input type="hidden" name = "item_details" value='<?=$item_details?>' id="item_details">
            <input type="hidden" name = "sabangs" value='<?=$sabangs?>' id="sabangs">
            <input type="hidden" name = "fixeds" value='<?=$fixeds?>' id="fixeds">
            <input type="hidden" name = "reorders" value='<?=$reorders?>' id="reorders">
            <input type="hidden" name = "chain_items" value='<?=$chain_items?>' id="chain_items">
            <input type="hidden" name = "ps_user_chks" value='<?=$ps_user_chks?>' id="ps_user_chks">
            <input type="hidden" name = "folds" value='<?=$folds?>' id="folds">
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
                            <option value="sap_code" <?php echo get_selected($sfl, 'sap_code'); ?>>삼진코드(다중)</option>
                            <option value="sap_code_w" <?php echo get_selected($sfl, 'sap_code_w'); ?>>삼진코드(문자열)</option>
                            <!-- 
                            <option value="its_sap_code" <?php echo get_selected($sfl, 'its_sap_code'); ?>>SAP코드</option> -->
                        </select>
                        <label for="stx" class="sound_only">검색어</label>
                        <input type="text" style="width:80%;" name="stx" value="<?php echo $txt1; ?>" id="stx" class="frm_input" onkeydown="enterSearch();">
                </td>
                </tr>
                <tr>
                    <th scope="row">일자</th>
                    <td colspan="2">
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <input type='text' class="form-control" id="it_time" name="sc_it_time" value="" onkeydown="enterSearch();"/>
                            <i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 10px;right: 24px;top: auto;cursor: pointer;"></i>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                            <div class="btn-group" >
                                <button type="button" onkeydown="enterSearch();" class="btn btn_02" name="dateBtn" data="all">전체</button>
                                <button type="button" onkeydown="enterSearch();" class="btn btn_02" name="dateBtn" data="today">오늘</button>
                                <button type="button" onkeydown="enterSearch();" class="btn btn_02" name="dateBtn" data="3d">3일</button>
                                <button type="button" onkeydown="enterSearch();" class="btn btn_02" name="dateBtn" data="1w">1주</button>
                                <button type="button" onkeydown="enterSearch();" class="btn btn_02" name="dateBtn" data="1m">1개월</button>
                                <button type="button" onkeydown="enterSearch();" class="btn btn_02" name="dateBtn" data="3m">3개월</button>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">브랜드</th>
                    <td colspan="2">
                    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                        
                        <label><input type="checkbox" onkeydown="enterSearch();" value="" id="brand_0"  <?php if(!$brands) echo "checked"; ?>>전체</label>&nbsp;&nbsp;
                        <label><input type="checkbox" onkeydown="enterSearch();" value="소프라움" id="brand_1" class="brand" <?php if((substr_count($brands, '소프라움') >= 1) || $all_brand) echo "checked"; ?>  >소프라움</label>&nbsp;&nbsp;
                        <label><input type="checkbox" onkeydown="enterSearch();" value="쉐르단" id="brand_2" class="brand" <?php if((substr_count($brands, '쉐르단') >= 1) || $all_brand) echo "checked"; ?> >쉐르단</label>&nbsp;&nbsp;
                        <label><input type="checkbox" onkeydown="enterSearch();" value="랄프로렌홈" id="brand_3" class="brand" <?php if((substr_count($brands, '랄프로렌홈') >= 1)|| $all_brand) echo "checked"; ?> >랄프로렌홈</label>&nbsp;&nbsp;
                        <label><input type="checkbox" onkeydown="enterSearch();" value="베온트레" id="brand_4" class="brand" <?php if((substr_count($brands, '베온트레') >= 1) || $all_brand) echo "checked"; ?> >베온트레</label>&nbsp;&nbsp;
                        <label><input type="checkbox" onkeydown="enterSearch();" value="링스티드던" id="brand_5" class="brand" <?php if((substr_count($brands, '링스티드던') >= 1) || $all_brand) echo "checked"; ?> >링스티드던</label>&nbsp;&nbsp;
                        <label><input type="checkbox" onkeydown="enterSearch();" value="로자리아" id="brand_6" class="brand" <?php if((substr_count($brands, '로자리아') >= 1) || $all_brand) echo "checked"; ?> >로자리아</label>&nbsp;&nbsp;
                        <label><input type="checkbox" onkeydown="enterSearch();" value="그라치아노" id="brand_7" class="brand" <?php if((substr_count($brands, '그라치아노') >= 1) || $all_brand) echo "checked"; ?> >그라치아노</label>&nbsp;&nbsp;
                        <label><input type="checkbox" onkeydown="enterSearch();" value="시뇨리아" id="brand_8" class="brand" <?php if((substr_count($brands, '시뇨리아') >= 1) || $all_brand) echo "checked"; ?> >시뇨리아</label>&nbsp;&nbsp;
                        <label><input type="checkbox" onkeydown="enterSearch();" value="플랫폼일반" id="brand_9" class="brand" <?php if((substr_count($brands, '플랫폼일반') >= 1) || $all_brand) echo "checked"; ?> >플랫폼일반</label>&nbsp;&nbsp;
                        <label><input type="checkbox" onkeydown="enterSearch();" value="플랫폼렌탈" id="brand_10" class="brand" <?php if((substr_count($brands, '플랫폼렌탈') >= 1) || $all_brand) echo "checked"; ?> >플랫폼렌탈</label>&nbsp;&nbsp;
                        <label><input type="checkbox" onkeydown="enterSearch();" value="온라인" id="brand_11" class="brand" <?php if((substr_count($brands, '온라인') >= 1) || $all_brand) echo "checked"; ?> >온라인</label>&nbsp;&nbsp;
                        <label><input type="checkbox" onkeydown="enterSearch();" value="템퍼" id="brand_12" class="brand" <?php if((substr_count($brands, '템퍼') >= 1) || $all_brand) echo "checked"; ?> >템퍼</label>&nbsp;&nbsp;


                    </div>
                    </td>
                </tr>
                <tr class="fold">
                    <th scope="row">시즌</th>
                    <td colspan="2">
                    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12" style="display : flex;">
                        <div>
                            <select name="ps_code_year" id="ps_code_year" >
                                <option value="">선택</option>
                                <?for($yyi = 0; $yyi < 51; $yyi++){?>
                                <option value="<?=($yyi+2000)?>" <?= $code_year == ($yyi+2000) ? "selected" : "" ?>><?=($yyi+2000)?>년</option>
                                <?}?>
                            </select>
                        </div>
                        <div>
                            <select  name="ps_code_season" id="ps_code_season" >
                                <option value="" <?= $code_season == '' ? "selected" : "" ?>>선택</option>
                                <option value="S" <?= $code_season == 'S' ? "selected" : "" ?>>SS</option>
                                <option value="H" <?= $code_season == 'H' ? "selected" : "" ?>>HS</option>
                                <option value="F" <?= $code_season == 'F' ? "selected" : "" ?>>FW</option>
                                <option value="A" <?= $code_season == 'A' ? "selected" : "" ?>>AA</option>
                            </select>
                        </div>
                    </div>
                    </td>
                </tr>
                <tr class="fold">
                    <th scope="row">입고여부</th>
                    <td colspan="2">
                    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                        <label><input type="checkbox" onkeydown="enterSearch();" value="N" id="ipgoyn_0" class="ipgoyn" <?php if(!$ipgos || (substr_count($ipgos, 'N') >= 1) ) echo "checked"; ?> >입고완료제외</label>&nbsp;&nbsp;
                        <label><input type="checkbox" onkeydown="enterSearch();" value="Y" id="ipgoyn_1" class="ipgoyn" <?php if(!$ipgos ||substr_count($ipgos, 'Y') >= 1) echo "checked"; ?> >입고완료포함</label>&nbsp;&nbsp;
                    </div>
                    </td>
                </tr>
                <tr class="fold">
                    <th scope="row">창고입고여부</th>
                    <td colspan="2">
                    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                        <label><input type="checkbox" onkeydown="enterSearch();" value="Y" id="dpart_ipgoyn_0" class="dpart_ipgoyn" <?php if(!$dpart_ipgos || (substr_count($dpart_ipgos, 'Y') >= 1) ) echo "checked"; ?> >창고입고</label>&nbsp;&nbsp;
                        <label><input type="checkbox" onkeydown="enterSearch();" value="N" id="dpart_ipgoyn_1" class="dpart_ipgoyn" <?php if(!$dpart_ipgos ||substr_count($dpart_ipgos, 'N') >= 1) echo "checked"; ?> >창고미입고</label>&nbsp;&nbsp;
                    </div>
                    </td>
                </tr>
                <tr class="fold">
                    <th scope="row">촬영완료여부</th>
                    <td colspan="2">
                    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                        <label><input type="checkbox" onkeydown="enterSearch();" value="Y" id="shootingyn_0" class="shooting" <?php if(!$shootings || (substr_count($shootings, 'Y') >= 1) ) echo "checked"; ?> >완료</label>&nbsp;&nbsp;
                        <label><input type="checkbox" onkeydown="enterSearch();" value="N" id="shootingyn_1" class="shooting" <?php if(!$shootings ||substr_count($shootings, 'N') >= 1) echo "checked"; ?> >미완료</label>&nbsp;&nbsp;
                    </div>
                    </td>
                </tr>
                <tr class="fold">
                    <th scope="row">검수여부</th>
                    <td colspan="2">
                    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                        <label><input type="checkbox" value="Y" id="gumsus_0" class="gumsu" <?php if(!$gumsus || (substr_count($gumsus, 'Y') >= 1) ) echo "checked"; ?> >검수완료</label>&nbsp;&nbsp;
                        <label><input type="checkbox" value="N" id="gumsus_1" class="gumsu" <?php if(!$gumsus ||substr_count($gumsus, 'N') >= 1) echo "checked"; ?> >미검수</label>&nbsp;&nbsp;
                        <label><input type="checkbox" value="N" id="gumsus_sub_0" class="gumsu_sub" <?php if(!$gumsu_subs ||substr_count($gumsu_subs, 'N') >= 1) echo "checked"; ?> >수정요청</label>&nbsp;&nbsp;
                        <label><input type="checkbox" value="Y" id="gumsus_sub_1" class="gumsu_sub" <?php if(!$gumsu_subs ||substr_count($gumsu_subs, 'Y') >= 1) echo "checked"; ?> >수정완료</label>&nbsp;&nbsp;
                    </div>
                    </td>
                </tr>
                <tr class="fold">
                    <th scope="row">상세기술서완료여부</th>
                    <td colspan="2">
                    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                        <label><input type="checkbox" value="Y" id="item_detail_0" class="item_detail" <?php if(!$item_details || (substr_count($item_details, 'Y') >= 1) ) echo "checked"; ?> >완료</label>&nbsp;&nbsp;
                        <label><input type="checkbox" value="N" id="item_detail_1" class="item_detail" <?php if(!$item_details ||substr_count($item_details, 'N') >= 1) echo "checked"; ?> >미완료</label>&nbsp;&nbsp;
                    </div>
                    </td>
                </tr>
                <tr class="fold">
                    <th scope="row">사방넷등록여부</th>
                    <td colspan="2">
                    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                        <label><input type="checkbox" value="Y" id="sabang_0" class="sabang" <?php if(!$sabangs || (substr_count($sabangs, 'Y') >= 1) ) echo "checked"; ?> >완료</label>&nbsp;&nbsp;
                        <label><input type="checkbox" value="N" id="sabang_1" class="sabang" <?php if(!$sabangs ||substr_count($sabangs, 'N') >= 1) echo "checked"; ?> >미완료</label>&nbsp;&nbsp;
                    </div>
                    </td>
                </tr>
                <tr class="fold">
                    <th scope="row">원가확정</th>
                    <td colspan="2">
                    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                        <label><input type="checkbox" value="Y" id="fixed_0" class="fixed" <?php if(!$fixeds || (substr_count($fixeds, 'Y') >= 1) ) echo "checked"; ?> >완료</label>&nbsp;&nbsp;
                        <label><input type="checkbox" value="N" id="fixed_1" class="fixed" <?php if(!$fixeds ||substr_count($fixeds, 'N') >= 1) echo "checked"; ?> >미완료</label>&nbsp;&nbsp;
                    </div>
                    </td>
                </tr>
                <tr class="fold">
                    <th scope="row">리오더</th>
                    <td colspan="2">
                    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                        <label><input type="checkbox" onkeydown="enterSearch();" class="reorder" value="N" id="reorder_0" <?php if(!$reorders || (substr_count($reorders, 'N') >= 1) ) echo "checked"; ?>  >일반</label>&nbsp;&nbsp;
                        <label><input type="checkbox" onkeydown="enterSearch();" class="reorder" value="Y" id="reorder_1" <?php if(!$reorders || (substr_count($reorders, 'Y') >= 1) ) echo "checked"; ?>  >리오더</label>&nbsp;&nbsp;
                    </div>
                    </td>
                </tr>
                <tr class="fold">
                    <th scope="row">연관상품</th>
                    <td colspan="2">
                    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                        <label><input type="checkbox" onkeydown="enterSearch();" class="chain_item" value="N" id="chain_item_0" <?php if(!$chain_items || (substr_count($chain_items, 'N') >= 1) ) echo "checked"; ?>  >전체</label>&nbsp;&nbsp;
                        <label><input type="checkbox" onkeydown="enterSearch();" class="chain_item" value="Y" id="chain_item_1" <?php if(!$chain_items || (substr_count($chain_items, 'Y') >= 1) ) echo "checked"; ?>  >연관상품</label>&nbsp;&nbsp;
                    </div>
                    </td>
                </tr>
                <?if ($member['mb_id'] == 'admin' || $member['mb_id'] == 'sbs608') : ?>
                <tr class="fold">
                    <th scope="row">담당자</th>
                    <td colspan="2">
                    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                        <label><input type="checkbox" onkeydown="enterSearch();" value="" id="ps_user_chk_0"  <?php if(!$ps_user_chks) echo "checked"; ?>>전체</label>&nbsp;&nbsp;
                        <?for ($ltm = 0; $m_row = sql_fetch_array($manager_res); $ltm++) {?>
                        <label><input type="checkbox" onkeydown="enterSearch();" class="ps_user_chk" value="<?=$m_row['mb_name']?>" id="ps_user_chk_<?=$ltm+1?>" <?php if(!$ps_user_chks || (substr_count($ps_user_chks, $m_row['mb_name']) >= 1) ) echo "checked"; ?>  ><?=$m_row['mb_name']?></label>&nbsp;&nbsp;
                        <?}?>
                    </div>
                    </td>
                </tr>
                <?endif?>
                <tr class="fold">
                    <th scope="row">보기</th>
                    <td colspan="2">
                    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                        <select name="limit_list" id="limit_list" onkeydown="enterSearch();">
                            <option value="5" <?php if(!$limit_list ||(substr_count($limit_list, '5') >= 1)) echo "selected"; ?>>5개</option>
                            <option value="10" <?php if(substr_count($limit_list, '10') >= 1) echo "selected"; ?>>10개</option>
                            <option value="20" <?php if(substr_count($limit_list, '20') >= 1) echo "selected"; ?>>20개</option>
                            <option value="30" <?php if(substr_count($limit_list, '30') >= 1) echo "selected"; ?>>30개</option>
                            <option value="50" <?php if(substr_count($limit_list, '50') >= 1) echo "selected"; ?>>50개</option>
                        </select>
                    </div>
                    </td>
                </tr>
                
                </table>
            </div>
            <div>
                <div style="padding:0px;" id="fold_name" class="fold_up" onclick="fold_up_down()"><a id="fold_btn"></a></div>
            </div>
            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                    <button class="btn btn_02 search-reset" type="button" id="btn_clear">초기화</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i>검색</button>
                </div>
            </div>
        </form>


        <div class="local_ov01 local_ov">
            <span class="btn_ov01">[ 총 건수 : <?= number_format($total_count); ?>건 ]</span>
        </div>
    
        <? 
        $buttonName = '판가 업로드';
        if ($member['mb_id'] == 'jeongwseong')  $buttonName = '우성우성';
        if ($is_admin == 'super' || $member['mb_id'] == 'jeongwseong') { ?>
            <input type='file' name ="upload_excel" id='upload_excel'  style="display:none;"/>
            <div class="btn btn_02" style="height: 30px;" id="upload_price_btn"><? echo  $buttonName?></div>
	    <? } ?>

        <form name="new_goods_result" id="new_goods_result" method="post" autocomplete="off">
            <input type="hidden" name="search_od_status" value="<?= $od_status; ?>">
            <input type="hidden" name="od_status" id="post_od_status">
            <input type="hidden" name="token" value="<?= $token ?>">

            <div class="tbl_head01 tbl_wrap" id="topscroll"  style="width:100%; margin:0px; overflow-x:scroll;">
                <div class="div1" style="width:<?=$allowA['mb_dept'] == '플랫폼팀(MD)' ? '3247px;' : '2747px;'  ?> height:20px;"></div>
            </div>
            
            <div class="tbl_head01 tbl_wrap" id="bottomscroll"  style="overflow-x:scroll;">
                <table id="sodr_list" style="width: <?=$allowA['mb_dept'] == '플랫폼팀(MD)' ? '3247px' : '2747px'  ?>">
                    <colgroup>
                        <col width="25px"/>
                        <col width="175px"/>
                        <col width="80"/>
                        <col width="120px"/>
                        <col width="50px"/>
                        <col width="73px"/>
                        <?if($allowA['mb_dept'] == '플랫폼팀(디자인)'  || $allowA['mb_dept'] == '플랫폼팀(MD)' || $allowA['mb_dept'] == '디자인팀') :?>
                            <col width="73px"/>
                            <col width="73px"/>
                            <col width="113px"/>
                            <col width="93px"/>
                            <?if($allowA['mb_dept'] == '플랫폼팀(MD)') :?>
                            <col width="500px"/>
                            <?endif?>
                            <col width="80px"/>
                            <col width="80px"/>
                            <col width="80px"/>
                            <col width="73px"/>
                            <col width="73px"/>
                            <col width="73px"/>
                            <col width="73px"/>
                            <col width="73px"/>
                            <col width="73px"/>
                            <col width="73px"/>
                            <col width="73px"/>
                            <col width="73px"/>
                        <?else :?>
                            <col width="80px"/>
                            <col width="80px"/>
                            <col width="80px"/>
                            <col width="73px"/>
                            <col width="73px"/>
                            <col width="73px"/>
                            <col width="73px"/>
                            <col width="73px"/>
                            <col width="73px"/>
                            <col width="73px"/>
                            <col width="73px"/>
                            <col width="73px"/>
                            <col width="73px"/>
                            <col width="73px"/>
                            <col width="113px"/>
                            <col width="93px"/>
                        <?endif?>
                        
                        <col width="73px"/>
                        <col width="73px"/>
                        <col width="53px"/>
                        <col width="73px"/>
                        <col width="68px"/>
                        <col width="66px"/>
                        <col width="66px"/>
                        <col width="66px"/>
                        <col width="73px"/>
                        <col width="73px"/>
                        <col width="73px"/>
                        <col width="73px"/>
                        <col width="73px"/>
                        <col width="73px"/>
                        <col width="50px"/>
                    </colgroup>
                    <thead>
                        <tr>
                            <th scope="col"  rowspan = "2" class="headcol">
                                <label for="chkall" class="sound_only">선택 전체</label>
                                <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                            </th>
                            <th  scope="col" rowspan = "2">상품명</th>
                            <th scope="col" rowspan = "2">브랜드</th>
                            <th  scope="col" rowspan = "2">삼진코드</th>
                            <th scope="col" rowspan = "2">대표<br>이미지</th>
                            <th scope="col" rowspan = "2">입고여부</th>
                            <?if($allowA['mb_dept'] == '플랫폼팀(디자인)'  || $allowA['mb_dept'] == '플랫폼팀(MD)' || $allowA['mb_dept'] == '디자인팀' ) :?>
                                <th scope="col" rowspan = "2">작업지시서<br>(아이템)</th>
                                <th scope="col" rowspan = "2">작업지시서<br>(사이즈)</th>
                                <th scope="col" rowspan = "2">제품기획서</th>
                                <th scope="col" rowspan = "2">상품정보집</th>
                                <?if($allowA['mb_dept'] == '플랫폼팀(MD)') :?>
                                <th scope="col" rowspan = "2">사방넷</th>
                                <?endif?>
                                <th scope="col" rowspan = "2">TAG가</th>
                                <th scope="col" rowspan = "2">1차판매가</th>
                                <th scope="col" rowspan = "2">2차판매가</th>
                                <th scope="col" rowspan = "2">원가</th>
                                <th scope="col" rowspan = "2">창고재고</th>
                                <th scope="col" rowspan = "2">촬영완료</th>
                                <th scope="col" rowspan = "2">검수</th>
                                <th scope="col" rowspan = "2">상세<br>기술서</th>
                                <th scope="col" rowspan = "2">사방넷</th>
                                <th scope="col" rowspan = "2">원가확정</th>
                                <th scope="col" rowspan = "2">전용상품</th>
                                <th scope="col" rowspan = "2">샘플예정일</th>
                                <th scope="col" rowspan = "2">출시확정일</th>
                                <th scope="col" rowspan = "2">생산수량</th>
                            
                            <?else :?>
                                <th scope="col" rowspan = "2">TAG가</th>
                                <th scope="col" rowspan = "2">1차판매가</th>
                                <th scope="col" rowspan = "2">2차판매가</th>
                                <th scope="col" rowspan = "2">원가</th>
                                <th scope="col" rowspan = "2">창고재고</th>
                                <th scope="col" rowspan = "2">촬영완료</th>
                                <th scope="col" rowspan = "2">검수</th>
                                <th scope="col" rowspan = "2">상세<br>기술서</th>
                                <th scope="col" rowspan = "2">사방넷</th>
                                <th scope="col" rowspan = "2">원가확정</th>
                                <th scope="col" rowspan = "2">전용상품</th>
                                <th scope="col" rowspan = "2">샘플예정일</th>
                                <th scope="col" rowspan = "2">출시확정일</th>
                                <th scope="col" rowspan = "2">생산수량</th>
                                <th  scope="col" rowspan = "2">작업지시서<br>(아이템)</th>
                                <th scope="col" rowspan = "2">작업지시서<br>(사이즈)</th>
                                <th scope="col" rowspan = "2">제품기획서</th>
                                <th scope="col" rowspan = "2">상품정보집</th>
                            <?endif?>
                            <th scope="col" rowspan = "2">생산<br>일정</th>
                            <!-- <th scope="col" rowspan = "2">작지<br>복사</th> -->
                            <th scope="col" rowspan = "1" colspan = "5">상품정보집 진행현황</th>
                            <th scope="col" rowspan = "2">출시예정일</th>
                            <th scope="col" rowspan = "2">제품기획<br>승인일자</th>
                            <th scope="col" rowspan = "2">원단발주</th>
                            <th scope="col" rowspan = "2">원단납기<br>예정</th>
                            <th scope="col" rowspan = "2">원단검품<br>(시험성적)</th>
                            <th scope="col" rowspan = "2">생산발주</th>
                            <th scope="col" rowspan = "2">작성자</th>
                        </tr>
                        <tr>
                            <th scope="col">사이즈</th>
                            <th scope="col">디자인팀</th>
                            <th scope="col">플랫폼팀<br>(MD)</th>
                            <th scope="col">플랫폼팀<br>(디자인)</th>
                            <th scope="col">플랫폼팀<br>(마케팅)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?
                        for ($ai = 0; $abs_row = sql_fetch_array($abs_result); $ai++) {
                            $item_cnt_count = 0;
                            $jo_item = 0;
                            $ch_cnt = 0;
                            if(!empty($abs_row['ps_chain_gb'])){
                                if(!empty($abs_row['ps_chain_code'])){
                                    $chain_gb = "ch_child";
                                }else{
                                    $chain_gb = "ch_parents";
                                }
                            }else{
                                $chain_gb = "";
                            }
                        ?>
                        <tr class="<?=$chain_gb?>">
                            <td id="new_goods_sub_row_td" class="headcol check_input_group_td check_input_group_td_<?=$ai?>">
                                <table id="new_goods_sub_row_table" class="check_input_group_table_<?=$ai?>" style="width : 100%;">
                                <?
                                $ch_sql = "select * from lt_prod_schedule {$sql_search} and ps_it_name IN ({$sub_table_data}) ORDER BY  ps_origin_ps_id IS NULL ASC , ps_origin_ps_id ASC, ps_id ASC ";


                                $ch_sql_cnt = "select count(*) AS CNT from lt_prod_schedule {$sql_search} and ps_it_name IN ({$sub_table_data}) ORDER BY  ps_origin_ps_id IS NULL ASC , ps_origin_ps_id ASC, ps_id ASC  ";

                                $ch_result = sql_query($ch_sql);
                                $ch_result_cnt = sql_fetch($ch_sql_cnt);

                                
                                
                                $copy_list_items = '';
                                for ($ch_i = 0; $ch_row = sql_fetch_array($ch_result); $ch_i++) {
                                    if( $abs_row['ps_it_name'] == $ch_row['ps_it_name']){
                                        $ch_cnt++;
                                ?>
                                    <tr class="reset_tr_<?=$ch_i?>">
                                        <td>
                                            <input type="hidden" name="name[<?= $ch_i ?>]" value="<?=preg_replace("/\s+/","",strtr_kh($abs_row['ps_it_name']))?>" id="name_<?=preg_replace("/\s+/","",strtr_kh($abs_row['ps_it_name']))?>">
                                            <input type="hidden" name="ps_id[<?= $ch_i ?>]" value="<?= $ch_row['ps_id'] ?>" id="od_id_<?= $ch_i ?>">
                                            <input type="hidden" name="ps_re_order[<?= $ch_i ?>]" value="<?= $ch_row['ps_re_order'] ?>" id="ps_re_order_<?= $ch_i ?>">
                                            <input type="checkbox" name="chk[]" class="<?=preg_replace("/\s+/","",strtr_kh($abs_row['ps_it_name']))?>" value="<?= $ch_i ?>" id="chk_<?= $ch_i ?>">
                                        </td>
                                    </tr>
                                <?
                                        if($copy_list_items != "") $copy_list_items .= ",";
                                        $copy_list_items .= $ch_row['ps_id'] ;
                                    }
                                }
                                
                                ?>
                                <input type="hidden" id="row_<?=preg_replace("/\s+/","",strtr_kh($abs_row['ps_it_name']))?>" value = "<?=$ch_cnt?>">
                                </table>
                                
                            </td>
                            <td class="prod_copy_area" id="prod_copy_<?=preg_replace("/\s+/","",strtr_kh($abs_row['ps_it_name']))?>" data-item-name = "<?=$abs_row['ps_it_name']?>">
                                <label class="sound_only">상품명</label>
                                <?=$abs_row['ps_it_name']?>
                                <!-- <?if($abs_row['ps_re_order'] == 'N') : ?>
                                    <?=$abs_row['ps_it_name']?>
                                <?else:?>
                                    <?if($abs_row['ps_re_order'] == 'Y' && $abs_row['ps_reorder_id'] != '') : ?>
                                        (신)<?=$abs_row['ps_it_name']?>
                                    <?else : ?>
                                        (기존)<?=$abs_row['ps_it_name']?>
                                    <?endif?>
                                <?endif?> -->
                            </td>
                            <td headers="odrstat">
                                <label class="sound_only">브랜드</label>
                                <?= $abs_row['ps_brand']; ?>
                            </td>
                            
                            <td colspan="35" id="new_goods_sub_row_td">
                                <table id="new_goods_sub_row_table" style="width : <?=$allowA['mb_dept'] == '플랫폼팀(MD)' ?  '3028px;' : '2528px;' ?> "  class="check_input_group_table_<?=$ai?>">
                                <colgroup>
                                    <col width="120px"/>
                                    <col width="50px"/>
                                    <col width="73px"/>
                                    <?if($allowA['mb_dept'] == '플랫폼팀(디자인)'  || $allowA['mb_dept'] == '플랫폼팀(MD)' || $allowA['mb_dept'] == '디자인팀') :?>
                                        <col width="73px"/>
                                        <col width="73px"/>
                                        <col width="113px"/>
                                        <col width="93px"/>
                                        <?if($allowA['mb_dept'] == '플랫폼팀(MD)') :?>
                                        <col width="500px"/>
                                        <?endif?>
                                        <col width="80px"/>
                                        <col width="80px"/>
                                        <col width="80px"/>
                                        <col width="73px"/>
                                        <col width="73px"/>
                                        <col width="73px"/>
                                        <col width="73px"/>
                                        <col width="73px"/>
                                        <col width="73px"/>
                                        <col width="73px"/>
                                        <col width="73px"/>
                                        <col width="73px"/>
                                    
                                    <?else:?>
                                        <col width="80px"/>
                                        <col width="80px"/>
                                        <col width="80px"/>
                                        <col width="73px"/>
                                        <col width="73px"/>
                                        <col width="73px"/>
                                        <col width="73px"/>
                                        <col width="73px"/>
                                        <col width="73px"/>
                                        <col width="73px"/>
                                        <col width="73px"/>
                                        <col width="73px"/>
                                        <col width="73px"/>
                                        <col width="73px"/>
                                        <col width="113px"/>
                                        <col width="93px"/>
                                    <?endif?>

                                    <col width="73px"/>
                                    <col width="73px"/>
                                    <col width="53px"/>
                                    <col width="73px"/>
                                    <col width="68px"/>
                                    <col width="66px"/>
                                    <col width="66px"/>
                                    <col width="66px"/>
                                    <col width="73px"/>
                                    <col width="73px"/>
                                    <col width="73px"/>
                                    <col width="73px"/>
                                    <col width="73px"/>
                                    <col width="73px"/>
                                    <col width="50px"/>
                                    
                                </colgroup>
                                <?
                                $sql = "select * from lt_prod_schedule {$sql_search} and ps_it_name IN ({$sub_table_data}) ORDER BY  ps_origin_ps_id IS NULL ASC , ps_origin_ps_id ASC, ps_id ASC  ";
                                $cnt_sql = "select count(*) cnt from lt_prod_schedule {$sql_search} and ps_it_name IN ({$sub_table_data}) ORDER BY  ps_origin_ps_id IS NULL ASC , ps_origin_ps_id ASC, ps_id ASC  ";
                                
                                $result = sql_query($sql);
                                $cnt_result = sql_fetch($cnt_sql);
                                $count_item = $cnt_result['cnt'];

                                
                                
                                
                                for ($i = 0; $row = sql_fetch_array($result); $i++) {
                                    if( $abs_row['ps_it_name'] == $row['ps_it_name']){
                                        $item_cnt_count++;
                                        $ipgo_status = '미입고';
                                        if($row['ps_ipgo_status'] =='Y'){
                                            $ipgo_status = '입고완료';
                                        }
                                        $shooting_status = '미완료';
                                        if($row['ps_shooting_yn'] =='Y'){
                                            $shooting_status = '완료';
                                        }
                                        $online_offline = '';
                                        if($row['ps_online'] =='N'){
                                            $online_offline = '오프라인';
                                        }
                                ?>
                                    <tr class="rigit_click_prod_main reset_tr_origin reset_tr_origin_<?=$i?>" id="reset_tr_origin_<?=$i?>">
                                        <td class="prod_item_copy" id="prod_item_copy_<?=$row['ps_id']?>" data-item-ps-id = "<?=$row['ps_id']?>">
                                            <label class="sound_only">삼진코드</label>
                                            <?if($row['ps_re_order'] == 'N') : ?>
                                                <?=$row['ps_code_gubun']?><?=$row['ps_code_brand']?><?=$row['ps_code_year']?><?=$row['ps_code_season']?><?=$row['ps_code_item_type']?><?=$row['ps_code_index']?><?=$row['ps_code_item_name']?>
                                            <?else:?>
                                                <?if($row['ps_re_order'] == 'Y' && $row['ps_reorder_id'] != '') : ?>
                                                    (신)<?=$abs_row['ps_code_gubun']?><?=$abs_row['ps_code_brand']?><?=$abs_row['ps_code_year']?><?=$abs_row['ps_code_season']?><?=$abs_row['ps_code_item_type']?><?=$abs_row['ps_code_index']?><?=$abs_row['ps_code_item_name']?>
                                                <?else : ?>
                                                    (기존)<?=$abs_row['ps_code_gubun']?><?=$abs_row['ps_code_brand']?><?=$abs_row['ps_code_year']?><?=$abs_row['ps_code_season']?><?=$abs_row['ps_code_item_type']?><?=$abs_row['ps_code_index']?><?=$abs_row['ps_code_item_name']?>
                                                <?endif?>
                                            <?endif?>
                                        </td>

                                        <td>
                                            <label class="sound_only">대표이미지</label>
                                            <form id="ajaxfrom_<?=$row['ps_id']?>"  method="post" >
                                                <input type='file' name ="prod_main_imgs[]" id="update_imgs_<?=$row['ps_id']?>" class='file_main file_main_<?=$row['ps_id']?>' multiple />
                                            
                                            <?
                                                $ps_prod_main_imgs_set = array();
                                                if (!empty($row['ps_prod_main_imgs'])) {
                                                    $ps_prod_main_imgs_set = json_decode($row['ps_prod_main_imgs'], true);
                                                }
                                            ?>
                                            <div class="prod_main_img_area" onclick="prod_main_img(this,'<?=$ps_prod_main_imgs_set?>')" id="prod_main_img_area_<?=$row['ps_id']?>" data-prod-img= "<?=$row['ps_id']?>" >
                                                
                                                <?if($ps_prod_main_imgs_set[0]['img']):?>
                                                    <?php foreach ($ps_prod_main_imgs_set as $psmi => $main_imgs) : ?>
                                                        <input type="hidden" class="group_prod_main_img_<?=$row['ps_id']?>" data-imgs-idx ="<?=$psmi?>"  id="arr_prod_main_img_<?=$psmi?>"  value="<?=$main_imgs['img']?>"> 
                                                    <?php endforeach ?>

                                                <?endif?>
                                                <img style="margin: 0 auto; display: block;" class="prod_main_pf_foto_img prod_main_pf_foto_img_<?=$row['ps_id']?>" <?if($ps_prod_main_imgs_set[0]['img']):?> src="<?=G5_URL?>/data/new_goods/<?=$ps_prod_main_imgs_set[0]['img']?>" <?endif?>>
                                            </div>  
                                            </form>
                                            
                                        </td>
                                        
                                        <td>
                                            <label class="sound_only">입고여부</label>
                                            <?= $ipgo_status ?>
                                        </td>
                                        
                                        <?if($allowA['mb_dept'] == '플랫폼팀(디자인)'  || $allowA['mb_dept'] == '플랫폼팀(MD)' || $allowA['mb_dept'] == '디자인팀') :?>
                                            <td class="button-g add_file_area" data-add-file = "<?=$row['ps_id']?>" id = "add_file_area_id_<?=$row['ps_id']?>">
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">작업지시서(아이템)</label>
                                                    <?
                                                    $jo_item_sql ="select * from lt_job_order where ps_id ={$row['ps_id']} GROUP BY jo_prod_name";
                                                    $jo_item_result= sql_query($jo_item_sql);

                                                    $jo_copy_order = "select * from lt_job_order where jo_it_name ='{$row['ps_it_name']}' GROUP BY jo_prod_name ORDER BY jo_id ASC LIMIT 1";
                                                    $jo_copy_order_result= sql_fetch($jo_copy_order);
                                                    
                                                    for ($ji = 0; $jo_row = sql_fetch_array($jo_item_result); $ji++) {
                                                        
                                                        if($ji == 0){
                                                            $jo_item = $jo_copy_order_result['jo_id'];
                                                        }
                                                    ?>
                                                        <div>
                                                            <a style="cursor: pointer; <?= $row['ps_add_info_file'] ? 'color : red' : '' ?>" onclick="add_info_file('<?php echo $row['ps_id']; ?>')"><?=$jo_row['jo_prod_name'] ? $jo_row['jo_prod_name'] : '임시' ?></a>
                                                            <input type="file" style="display:none;" class="add_info_file" id="add_info_file_<?= $row['ps_id']; ?>">
                                                            <input type="hidden" id="add_file_name_<?= $row['ps_id']; ?>" value = "<?= $row['ps_add_info_file']; ?>" >
                                                        </div>
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
                                                    $jo_size_sql ="select * from lt_job_order where ps_id ={$row['ps_id']} ORDER BY jo_id ASC";
                
                                                    $jo_size_result= sql_query($jo_size_sql);
                                                    $jo_size = 0;
                                                    $jo_frist_temp = 0;
                                                    for ($jis = 0; $jo_row = sql_fetch_array($jo_size_result); $jis++) {
                                                        if($jis == 0){
                                                            $jo_size = $jo_row['jo_id'];
                                                            $jo_frist_temp = $jo_row['jo_temp'];
                                                        }
                                                    ?>
                                                        <button class='jo_item_size_btn' data-isb-id = '<?= $jo_row['jo_id']?>' id = "jo_item_size_btn_<?=$jo_row['jo_id']?>" style="color:#000;" type="button" onclick="location.href='./job.order.update.form.temp<?= $jo_row['jo_temp']?>.php?w=u&amp;ps_id=<?php echo $row['ps_id']; ?>&amp;jo_id=<?= $jo_row['jo_id'] ?>&amp;qstr=<?= $qstr?>'"><?=$jo_row['jo_size_code'] ? $jo_row['jo_size_code'] : '임시' ?></button>
                                                    <?}?>
                                                    <!-- <button><a href="./job.order.update.form.temp<?= $jo_row['jo_temp']?>.php?w=&amp;ps_id=<?php echo $row['ps_id']; ?>">등록</a></button> -->
                                                    <button onclick ="go_create_job_order('size','<?php echo $jo_size; ?>' , '<?php echo $row['ps_id']; ?>','<?=$jo_frist_temp?>')" ><a href="#">등록</a></button>
                                                    <button onclick ="go_create_job_order('copy','<?php echo $jo_size; ?>' , '<?php echo $row['ps_id']; ?>','<?=$jo_frist_temp?>')" ><a href="#">복사</a></button>
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <?
                                            $ip_sql ="select * from lt_item_proposal where ip_it_name ='{$row['ps_it_name']}'";
                                            $ip_cnt_sql ="select count(*) cnt from lt_prod_schedule where ps_it_name ='{$row['ps_it_name']}'";
        
                                            $ip_result= sql_query($ip_sql);
                                            $ip_data = sql_fetch($ip_cnt_sql);
                                            $count_ip_items = $ip_data['cnt'];
                                            ?>
                                            <?if($item_cnt_count == 1) : ?>
                                            <td rowspan="<?=$count_ip_items?>"  class="noline_td button-g <?=$item_cnt_count?>">
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">제품기획서</label>
                                                    <?
                                                    // $ip_sql ="select * from lt_item_proposal where ip_it_name ='{$row['ps_it_name']}'";
                
                                                    // $ip_result= sql_query($ip_sql);
                                                    // $ip_data = sql_fetch($ip_sql);
                
                                                    for ($ip = 0; $ip_row = sql_fetch_array($ip_result); $ip++) {
                                                    ?>
                
                                                        <button style="color:#000;" type="button" onclick="location.href='./item.proposal.update.form.temp<?= $ip_row['ip_temp']?>.php?w=u&amp;it_name=<?=$ip_row['ip_it_name']?>&amp;ip_id=<?php echo $ip_row['ip_id']; ?>&amp;qstr=<?= $qstr?>'">수정</button>
                                                    <?}?>
                                                    <?if($ip == 0) : ?>
                                                        <!-- <button onclick ="create_proposal()" ><a href="./item.proposal.update.form.php?w=&amp;ps_id=<?php echo $row['ps_id']; ?>">등록</a></button> -->
                                                        <button onclick ="create_proposal('<?php echo $row['ps_it_name']; ?>')" ><a href="#">등록</a></button>
                                                    <?endif?>
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <?endif?>
                                            <td class="button-g">
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">상품정보집</label>
                                                    <?
                                                    $pi_sql ="select * from lt_prod_info where ps_id ={$row['ps_id']} and pi_size_name is not null ORDER BY jo_id ASC";

                                                    $pi_result= sql_query($pi_sql);
                                                    $pi_result1= sql_query($pi_sql);
                                                    $pi_result2= sql_query($pi_sql);
                                                    $pi_result3= sql_query($pi_sql);
                                                    $pi_result4= sql_query($pi_sql);
                                                    $pi_result5= sql_query($pi_sql);
                                                    for ($pi = 0; $pi_row = sql_fetch_array($pi_result); $pi++) {
                                                    ?>
                                                        <button style="color:#000;" type="button" onclick="location.href='./item.info.update.form.php?w=u&amp;ps_id=<?php echo $row['ps_id']; ?>&amp;pi_id=<?= $pi_row['pi_id'] ?>&amp;jo_id=<?= $pi_row['jo_id'] ?>&amp;qstr=<?= $qstr?>'"><?=$pi_row['pi_size_name']?></button>
                                                    <?}?>
                                                    <!-- <button><a href="./item.info.update.form.php?w=&amp;ps_id=<?php echo $row['ps_id']; ?>">등록</a></button> -->
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <?if($allowA['mb_dept'] == '플랫폼팀(MD)') :?>
                                            <td>

                                                <label class="sound_only">상품명(사방넷상품명)</label>
                                                <?
                                                $pi_sabang_sql ="select * from lt_prod_info where ps_id ={$row['ps_id']} ORDER BY jo_id ASC";                                              
                                                $pi_sabang_result= sql_query($pi_sabang_sql);
                                                for ($pisb = 0; $pi_sb_row = sql_fetch_array($pi_sabang_result); $pisb++) {
                                                ?>
                                                    <p style="margin:0;"> <?=$pi_sb_row['pi_it_name']?></p>
                                                <?}?>

                                            </td>
                                            <?endif?>
                                            <td>
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">TAG가</label>
                                                    <?
                                                    $pi_1_sql ="select * from lt_prod_info where ps_id ={$row['ps_id']} and pi_size_name is not null ORDER BY jo_id ASC";                                              
                                                    $pi_1_result= sql_query($pi_1_sql);
                                                    for ($pi1 = 0; $pi_1_row = sql_fetch_array($pi_1_result); $pi1++) {
                                                    ?>
                                                        <p style="margin:0;"><?=$pi_1_row['pi_size_name']?> : <?=number_format($pi_1_row['pi_tag_price'])?></p>
                                                    <?}?>
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <td>
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">1차판매가</label>
                                                    <?
                                                    $pi_1_sql ="select * from lt_prod_info where ps_id ={$row['ps_id']} and pi_size_name is not null ORDER BY jo_id ASC";                                              
                                                    $pi_1_result= sql_query($pi_1_sql);
                                                    for ($pi1 = 0; $pi_1_row = sql_fetch_array($pi_1_result); $pi1++) {
                                                    ?>
                                                        <p style="margin:0;"><?=$pi_1_row['pi_size_name']?> : <?=number_format($pi_1_row['pi_sale_price'])?></p>
                                                    <?}?>
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <td>
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">2차판매가</label>
                                                    <?
                                                    $pi_2_sql ="select * from lt_prod_info where ps_id ={$row['ps_id']} and pi_size_name is not null ORDER BY jo_id ASC";

                                                    $pi_2_result= sql_query($pi_2_sql);
                                                    for ($pi2 = 0; $pi_2_row = sql_fetch_array($pi_2_result); $pi2++) {
                                                    ?>
                                                        <p style="margin:0;"><?=$pi_2_row['pi_size_name']?> : <?=number_format($pi_2_row['pi_sale_price2'])?></p>
                                                    <?}?>
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <td>
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">원가</label>
                                                    <?
                                                    $pi_2_sql ="select * from lt_prod_info where ps_id ={$row['ps_id']} and pi_size_name is not null ORDER BY jo_id ASC";

                                                    $pi_2_result= sql_query($pi_2_sql);
                                                    for ($pi2 = 0; $pi_2_row = sql_fetch_array($pi_2_result); $pi2++) {
                                                    ?>
                                                        <p style="margin:0;"><?=$pi_2_row['pi_size_name']?> : <?=number_format($pi_2_row['pi_origin_price'])?></p>
                                                    <?}?>
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <td>
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">재고</label>
                                                    <?
                                                    $pi_s_sql ="select * from lt_prod_info where ps_id ={$row['ps_id']} and pi_size_name is not null ORDER BY jo_id ASC";

                                                    $pi_s_result= sql_query($pi_s_sql);
                                                    for ($pis = 0; $pi_s_row = sql_fetch_array($pi_s_result); $pis++) {
                                                    ?>
                                                        <p style="margin:0;"><?=$pi_s_row['pi_size_name']?> : <?=number_format($pi_s_row['pi_samjin_stock'])?></p>
                                                    <?}?>
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <td>
                                                <label class="sound_only">촬영완료</label>
                                                <?= $shooting_status ?>
                                            </td>
                                            <td>
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">검수</label>
                                                    <?
                                                    
                                                        if($row['ps_gumsu'] == '100'){
                                                            $ps_gumsu = '완료';
                                                        }else{
                                                            $ps_gumsu = '미완료';
                                                        }
                                                    ?>
                                                    <?if($row['ps_gumsu'] == '100') : ?>
                                                        <p style="margin:0;"><?=$ps_gumsu?></p>
                                                    <?else : ?>
                                                        <p style="margin:0; <?=$row['ps_gumsu_sub']=='300' ? 'color: #f0ad4e;' : '' ?> <?=$row['ps_gumsu_sub']=='400' ? 'color: #f0ad4e;' : '' ?> <?=$row['ps_gumsu_sub']=='100' ? 'color: #5cbfbb;' : '' ?>"><?=$ps_gumsu?></p>
                                                    <?endif?>
                                                    
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <td>
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">상세기술서</label>
                                                    <?
                                                    
                                                        if($row['ps_item_detail'] == '100'){
                                                            $ps_item_detail = '완료';
                                                        }else{
                                                            $ps_item_detail = '미완료';
                                                        }
                                                    ?>
                                                    <p style="margin:0;"><?=$ps_item_detail?></p>
                                                    
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <td>
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">사방넷</label>
                                                    <?
                                                    $pi_sb_sql ="select * from lt_prod_info where ps_id ={$row['ps_id']} and pi_size_name is not null ORDER BY jo_id ASC";

                                                    $pi_sb_result= sql_query($pi_sb_sql);
                                                    for ($psb = 0; $pi_sb_row = sql_fetch_array($pi_sb_result); $psb++) {
                                                        if($pi_sb_row['pi_sabang_send'] == '100'){
                                                            $pi_sabang_send = '완료';
                                                        }else{
                                                            $pi_sabang_send = '미완료';
                                                        }
                                                    ?>
                                                        <p style="margin:0;"><?=$pi_sb_row['pi_size_name']?> : <?=$pi_sabang_send?></p>
                                                    <?}?>
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <td>
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">원가확정</label>
                                                    <?
                                                    $pi_pf_sql ="select * from lt_job_order where ps_id ={$row['ps_id']} and jo_size_code is not null ORDER BY jo_id ASC";

                                                    $pi_pf_result= sql_query($pi_pf_sql);
                                                    for ($ppf = 0; $pi_pf_row = sql_fetch_array($pi_pf_result); $ppf++) {
                                                        if($pi_pf_row['jo_price_fixed'] == '100'){
                                                            $jo_price_fixed = '완료';
                                                        }else{
                                                            $jo_price_fixed = '미완료';
                                                        }
                                                    ?>
                                                        <p style="margin:0;"><?=$pi_pf_row['jo_size_code']?> : <?=$jo_price_fixed?></p>
                                                    <?}?>
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <td>
                                                <label class="sound_only">전용상품</label>
                                                <?= $online_offline ?>
                                            </td>
                                            <td style="color:red;">
                                                <label class="sound_only">샘플예정일</label>
                                                <?= strtotime($row['ps_sample_date']) > 0 ?  date("Y.m.d", strtotime($row['ps_sample_date'])) : '' ?>
                                            </td>
                                            <td style="color:green;">
                                                <label class="sound_only">출시확정일</label>
                                                <?= strtotime($row['ps_real_ipgo_date']) > 0 ?  date("Y.m.d", strtotime($row['ps_real_ipgo_date'])) : '' ?>
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
                                                    
                                                    <p style="margin:0;"><?=$ps_size['size']?> : <?=$ps_size['qty']?></p>
                                                    
                                                <?php endforeach ?>
                                                <?endif?>
                                                
                                            </td>
                                            
                                        <?else :?>    
                                            <td>
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">TAG가</label>
                                                    <?
                                                    $pi_1_sql ="select * from lt_prod_info where ps_id ={$row['ps_id']} and pi_size_name is not null ORDER BY jo_id ASC";                                              
                                                    $pi_1_result= sql_query($pi_1_sql);
                                                    for ($pi1 = 0; $pi_1_row = sql_fetch_array($pi_1_result); $pi1++) {
                                                    ?>
                                                        <p style="margin:0;"><?=$pi_1_row['pi_size_name']?> : <?=number_format($pi_1_row['pi_tag_price'])?></p>
                                                    <?}?>
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <td>
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">1차판매가</label>
                                                    <?
                                                    $pi_1_sql ="select * from lt_prod_info where ps_id ={$row['ps_id']} and pi_size_name is not null ORDER BY jo_id ASC";                                              
                                                    $pi_1_result= sql_query($pi_1_sql);
                                                    for ($pi1 = 0; $pi_1_row = sql_fetch_array($pi_1_result); $pi1++) {
                                                    ?>
                                                        <p style="margin:0;"><?=$pi_1_row['pi_size_name']?> : <?=number_format($pi_1_row['pi_sale_price'])?></p>
                                                    <?}?>
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <td>
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">2차판매가</label>
                                                    <?
                                                    $pi_2_sql ="select * from lt_prod_info where ps_id ={$row['ps_id']} and pi_size_name is not null ORDER BY jo_id ASC";

                                                    $pi_2_result= sql_query($pi_2_sql);
                                                    for ($pi2 = 0; $pi_2_row = sql_fetch_array($pi_2_result); $pi2++) {
                                                    ?>
                                                        <p style="margin:0;"><?=$pi_2_row['pi_size_name']?> : <?=number_format($pi_2_row['pi_sale_price2'])?></p>
                                                    <?}?>
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <td>
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">원가</label>
                                                    <?
                                                    $pi_2_sql ="select * from lt_prod_info where ps_id ={$row['ps_id']} and pi_size_name is not null ORDER BY jo_id ASC";

                                                    $pi_2_result= sql_query($pi_2_sql);
                                                    for ($pi2 = 0; $pi_2_row = sql_fetch_array($pi_2_result); $pi2++) {
                                                    ?>
                                                        <p style="margin:0;"><?=$pi_2_row['pi_size_name']?> : <?=number_format($pi_2_row['pi_origin_price'])?></p>
                                                    <?}?>
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <td>
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">재고</label>
                                                    <?
                                                    $pi_s_sql ="select * from lt_prod_info where ps_id ={$row['ps_id']} and pi_size_name is not null ORDER BY jo_id ASC";

                                                    $pi_s_result= sql_query($pi_s_sql);
                                                    for ($pis = 0; $pi_s_row = sql_fetch_array($pi_s_result); $pis++) {
                                                    ?>
                                                        <p style="margin:0;"><?=$pi_s_row['pi_size_name']?> : <?=number_format($pi_s_row['pi_samjin_stock'])?></p>
                                                    <?}?>
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <td>
                                                <label class="sound_only">촬영완료</label>
                                                <?= $shooting_status ?>
                                            </td>
                                            <td>
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">검수</label>
                                                    <?
                                                    
                                                        if($row['ps_gumsu'] == '100'){
                                                            $ps_gumsu = '완료';
                                                        }else{
                                                            $ps_gumsu = '미완료';
                                                        }
                                                    ?>
                                                    <?if($row['ps_gumsu'] == '100') : ?>
                                                        <p style="margin:0;"><?=$ps_gumsu?></p>
                                                    <?else : ?>
                                                        <p style="margin:0; <?=$row['ps_gumsu_sub']=='300' ? 'color: #f0ad4e;' : '' ?> <?=$row['ps_gumsu_sub']=='400' ? 'color: #f0ad4e;' : '' ?> <?=$row['ps_gumsu_sub']=='100' ? 'color: #5cbfbb;' : '' ?>"><?=$ps_gumsu?></p>
                                                    <?endif?>
                                                    
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <td>
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">상세기술서</label>
                                                    <?
                                                    
                                                        if($row['ps_item_detail'] == '100'){
                                                            $ps_item_detail = '완료';
                                                        }else{
                                                            $ps_item_detail = '미완료';
                                                        }
                                                    ?>
                                                    <p style="margin:0;"><?=$ps_item_detail?></p>
                                                    
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <td>
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">사방넷</label>
                                                    <?
                                                    $pi_sb_sql ="select * from lt_prod_info where ps_id ={$row['ps_id']} and pi_size_name is not null ORDER BY jo_id ASC";

                                                    $pi_sb_result= sql_query($pi_sb_sql);
                                                    for ($psb = 0; $pi_sb_row = sql_fetch_array($pi_sb_result); $psb++) {
                                                        if($pi_sb_row['pi_sabang_send'] == '100'){
                                                            $pi_sabang_send = '완료';
                                                        }else{
                                                            $pi_sabang_send = '미완료';
                                                        }
                                                    ?>
                                                        <p style="margin:0;"><?=$pi_sb_row['pi_size_name']?> : <?=$pi_sabang_send?></p>
                                                    <?}?>
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <td>
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">원가확정</label>
                                                    <?
                                                    $pi_pf_sql ="select * from lt_job_order where ps_id ={$row['ps_id']} and jo_size_code is not null ORDER BY jo_id ASC";

                                                    $pi_pf_result= sql_query($pi_pf_sql);
                                                    for ($ppf = 0; $pi_pf_row = sql_fetch_array($pi_pf_result); $ppf++) {
                                                        if($pi_pf_row['jo_price_fixed'] == '100'){
                                                            $jo_price_fixed = '완료';
                                                        }else{
                                                            $jo_price_fixed = '미완료';
                                                        }
                                                    ?>
                                                        <p style="margin:0;"><?=$pi_pf_row['jo_size_code']?> : <?=$jo_price_fixed?></p>
                                                    <?}?>
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <td>
                                                <label class="sound_only">전용상품</label>
                                                <?= $online_offline ?>
                                            </td>
                                            <td style="color:red;">
                                                <label class="sound_only">샘플예정일</label>
                                                <?= strtotime($row['ps_sample_date']) > 0 ?  date("Y.m.d", strtotime($row['ps_sample_date'])) : '' ?>
                                            </td>
                                            <td style="color:green;">
                                                <label class="sound_only">출시확정일</label>
                                                <?= strtotime($row['ps_real_ipgo_date']) > 0 ?  date("Y.m.d", strtotime($row['ps_real_ipgo_date'])) : '' ?>
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
                                                    
                                                    <p style="margin:0;"><?=$ps_size['size']?> : <?=$ps_size['qty']?></p>
                                                    
                                                <?php endforeach ?>
                                                <?endif?>
                                                
                                            </td>
                                            <td class="button-g add_file_area" data-add-file = "<?=$row['ps_id']?>" id = "add_file_area_id_<?=$row['ps_id']?>">
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">작업지시서(아이템)</label>
                                                    <?
                                                    $jo_item_sql ="select * from lt_job_order where ps_id ={$row['ps_id']} GROUP BY jo_prod_name";
                                                    $jo_item_result= sql_query($jo_item_sql);

                                                    $jo_copy_order = "select * from lt_job_order where jo_it_name ='{$row['ps_it_name']}' GROUP BY jo_prod_name ORDER BY jo_id ASC LIMIT 1";
                                                    $jo_copy_order_result= sql_fetch($jo_copy_order);
                                                    
                                                    for ($ji = 0; $jo_row = sql_fetch_array($jo_item_result); $ji++) {
                                                        
                                                        if($ji == 0){
                                                            $jo_item = $jo_copy_order_result['jo_id'];
                                                        }
                                                    ?>
                                                        <div>
                                                            <a style="cursor: pointer; <?= $row['ps_add_info_file'] ? 'color : red' : '' ?>" onclick="add_info_file('<?php echo $row['ps_id']; ?>')"><?=$jo_row['jo_prod_name'] ? $jo_row['jo_prod_name'] : '임시' ?></a>
                                                            <input type="file" style="display:none;" class="add_info_file" id="add_info_file_<?= $row['ps_id']; ?>">
                                                            <input type="hidden" id="add_file_name_<?= $row['ps_id']; ?>" value = "<?= $row['ps_add_info_file']; ?>" >
                                                        </div>
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
                                                    $jo_size_sql ="select * from lt_job_order where ps_id ={$row['ps_id']} ORDER BY jo_id ASC";
                
                                                    $jo_size_result= sql_query($jo_size_sql);
                                                    $jo_size = 0;
                                                    $jo_frist_temp = 0;
                                                    for ($jis = 0; $jo_row = sql_fetch_array($jo_size_result); $jis++) {
                                                        if($jis == 0){
                                                            $jo_size = $jo_row['jo_id'];
                                                            $jo_frist_temp = $jo_row['jo_temp'];
                                                        }
                                                    ?>
                                                        <button class='jo_item_size_btn' data-isb-id = '<?= $jo_row['jo_id']?>' id = "jo_item_size_btn_<?=$jo_row['jo_id']?>" style="color:#000;" type="button" onclick="location.href='./job.order.update.form.temp<?= $jo_row['jo_temp']?>.php?w=u&amp;ps_id=<?php echo $row['ps_id']; ?>&amp;jo_id=<?= $jo_row['jo_id'] ?>&amp;qstr=<?= $qstr?>'"><?=$jo_row['jo_size_code'] ? $jo_row['jo_size_code'] : '임시' ?></button>
                                                    <?}?>
                                                    <!-- <button><a href="./job.order.update.form.temp<?= $jo_row['jo_temp']?>.php?w=&amp;ps_id=<?php echo $row['ps_id']; ?>">등록</a></button> -->
                                                    <button onclick ="go_create_job_order('size','<?php echo $jo_size; ?>' , '<?php echo $row['ps_id']; ?>','<?=$jo_frist_temp?>')" ><a href="#">등록</a></button>
                                                    <button onclick ="go_create_job_order('copy','<?php echo $jo_size; ?>' , '<?php echo $row['ps_id']; ?>','<?=$jo_frist_temp?>')" ><a href="#">복사</a></button>
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <?
                                            $ip_sql ="select * from lt_item_proposal where ip_it_name ='{$row['ps_it_name']}'";
                                            $ip_cnt_sql ="select count(*) cnt from lt_prod_schedule where ps_it_name ='{$row['ps_it_name']}'";
        
                                            $ip_result= sql_query($ip_sql);
                                            $ip_data = sql_fetch($ip_cnt_sql);
                                            $count_ip_items = $ip_data['cnt'];
                                            ?>
                                            <?if($item_cnt_count == 1) : ?>
                                            <td rowspan="<?=$count_ip_items?>"  class="noline_td button-g <?=$item_cnt_count?>">
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">제품기획서</label>
                                                    <?
                                                    // $ip_sql ="select * from lt_item_proposal where ip_it_name ='{$row['ps_it_name']}'";
                
                                                    // $ip_result= sql_query($ip_sql);
                                                    // $ip_data = sql_fetch($ip_sql);
                
                                                    for ($ip = 0; $ip_row = sql_fetch_array($ip_result); $ip++) {
                                                    ?>
                
                                                        <button style="color:#000;" type="button" onclick="location.href='./item.proposal.update.form.temp<?= $ip_row['ip_temp']?>.php?w=u&amp;it_name=<?=$ip_row['ip_it_name']?>&amp;ip_id=<?php echo $ip_row['ip_id']; ?>&amp;qstr=<?= $qstr?>'">수정</button>
                                                    <?}?>
                                                    <?if($ip == 0) : ?>
                                                        <!-- <button onclick ="create_proposal()" ><a href="./item.proposal.update.form.php?w=&amp;ps_id=<?php echo $row['ps_id']; ?>">등록</a></button> -->
                                                        <button onclick ="create_proposal('<?php echo $row['ps_it_name']; ?>')" ><a href="#">등록</a></button>
                                                    <?endif?>
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                            <?endif?>
                                            <td class="button-g">
                                                <?if ($row['ps_re_order'] == 'N') :?>
                                                    <label class="sound_only">상품정보집</label>
                                                    <?
                                                    $pi_sql ="select * from lt_prod_info where ps_id ={$row['ps_id']} and pi_size_name is not null ORDER BY jo_id ASC";

                                                    $pi_result= sql_query($pi_sql);
                                                    $pi_result1= sql_query($pi_sql);
                                                    $pi_result2= sql_query($pi_sql);
                                                    $pi_result3= sql_query($pi_sql);
                                                    $pi_result4= sql_query($pi_sql);
                                                    $pi_result5= sql_query($pi_sql);
                                                    for ($pi = 0; $pi_row = sql_fetch_array($pi_result); $pi++) {
                                                    ?>
                                                        <button style="color:#000;" type="button" onclick="location.href='./item.info.update.form.php?w=u&amp;ps_id=<?php echo $row['ps_id']; ?>&amp;pi_id=<?= $pi_row['pi_id'] ?>&amp;jo_id=<?= $pi_row['jo_id'] ?>&amp;qstr=<?= $qstr?>'"><?=$pi_row['pi_size_name']?></button>
                                                    <?}?>
                                                    <!-- <button><a href="./item.info.update.form.php?w=&amp;ps_id=<?php echo $row['ps_id']; ?>">등록</a></button> -->
                                                <?else :?>

                                                <?endif?>
                                            </td>
                                        <?endif?>


                                        
                                        
                                        
                                        <td class="button-g">
                                            <?if ($row['ps_re_order'] == 'N') :?>
                                                <label class="sound_only">생산일정</label>
                                                <button style="color:#000;" type="button" onclick="location.href='./prod.schedule.update.form.php?w=u&amp;ps_id=<?php echo $row['ps_id']; ?>&amp;qstr=<?= $qstr?>'">수정</button>
                                            <?else :?>
                                                <label class="sound_only">생산일정</label>
                                                <button style="color:#000;" type="button" onclick="location.href='./reorder.prod.schedule.update.form.php?w=u&amp;ps_id=<?php echo $row['ps_id']; ?>&amp;qstr=<?= $qstr?>'">수정</button>
                                            <?endif?>
                                        </td>

                                        <!-- <td class="button-g">
                                            <label class="sound_only">작지복사</label>
                                            <button><a href="./job.order.update.form.php?w=copy&amp;ps_id=<?php echo $row['ps_id']; ?>">복사</a></button>
                                        </td> -->
                                        
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
                                                && $pi_row2['pi_brand'] && $pi_row2['pi_age_gubun']&& $pi_row2['pi_origin_price']&& $pi_row2['pi_tag_price']
                                                && $pi_row2['pi_item_soje']&& $pi_row2['pi_color']&& $pi_row2['pi_size']&& $pi_row2['pi_cisu']&& $pi_row2['pi_maker']&& $pi_row2['pi_laundry']
                                                && $pi_row2['pi_xyz']&& $pi_row2['pi_ll_style']
                                                && $pi_row2['pi_info1']&& $pi_row2['pi_info2']&& $pi_row2['pi_info2_1']
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
                                                <?if($pi_row3['pi_it_sub_name'] && $pi_row3['pi_it_name'] && $pi_row3['pi_model_name'] && $pi_row3['pi_model_no'] && $pi_row3['pi_company_it_id']&& $pi_row3['pi_sale_price']&& $pi_row3['pi_sale_price2']) : ?>
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

                                        <td style="color:blue;">
                                            <label class="sound_only">출시예정일</label>
                                            <?= strtotime($row['ps_ipgo_date']) > 0 ?  date("Y.m.d", strtotime($row['ps_ipgo_date'])) : '' ?>
                                        </td>
                                        <td>
                                            <label class="sound_only">제품기획승인일자</label>
                                            <?= strtotime($row['ps_prod_proprosal_date']) > 0 ?  date("Y.m.d", strtotime($row['ps_prod_proprosal_date'])) : '' ?>
                                        </td>
                                        <td>
                                            <label class="sound_only">원단발주</label>
                                            <?= strtotime($row['ps_balju']) > 0 ?  date("Y.m.d", strtotime($row['ps_balju'])) : '' ?>
                                        </td>
                                        <td>
                                            <label class="sound_only">원단납기예정</label>
                                            <?= strtotime($row['ps_expected_limit_date']) > 0 ?  date("Y.m.d", strtotime($row['ps_expected_limit_date'])) : '' ?>
                                        </td>
                                        <td>
                                            <label class="sound_only">원단검품(시험성적)</label>
                                            <?= strtotime($row['ps_gumpum']) > 0 ?  date("Y.m.d", strtotime($row['ps_gumpum'])) : '' ?>
                                        </td>
                                        <td>
                                            <label class="sound_only">생산발주</label>
                                            <?= strtotime($row['ps_prod_balju']) > 0 ?  date("Y.m.d", strtotime($row['ps_prod_balju'])) : '' ?>
                                        </td>
                                        <td>
                                            <label class="sound_only">작성자</label>
                                            <?if($member['mb_id'] == 'admin' || $member['mb_id'] == 'sbs608') :?>
                                                <?=$row['ps_user']?>
                                            <?endif?>
                                        </td>
                                        
                                    </tr>
                                    <?
                                        }
                                    }
                                    $item_cnt_count=0;
                                    ?>
                                </table>

                            </td>
                        </tr>
                        

                    
                        <?
                        }
                        sql_free_result($abs_result);
                        if ($ai == 0)
                            echo '<tr><td colspan="23" class="empty_table">자료가 없습니다.</td></tr>';
                        ?>
                    </tbody>
                    </div>
                </table>


            <?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
            <div class="local_cmd01 local_cmd" style="margin-top : 20px;">
                <div style="float: left;">
                    <div class="btn btn_02" style="height: 55px; font-size: 13px; <?
                    if ($is_admin != 'super' && $allowA['mb_dept'] != '상품MD팀')  echo 'display:none';
                    ?>" onclick ="new_prod_reorder()">신상품 리오더 등록<br>(단일상품 선택 가능)</div>
                    <div class="btn btn_02" style="height: 55px; line-height:45px; font-size: 13px; <? 
                    if ($is_admin != 'super' && $allowA['mb_dept'] != '상품MD팀')  echo 'display:none';
                    ?>" onclick ="location.href='./reorder.prod.schedule.update.form.php?w=re'">기존상품<br>리오더 등록</div>
                    <div class="btn btn_02" style="height: 55px; font-size: 13px; <? 
                    if ($is_admin != 'super' && $allowA['mb_dept'] != '상품MD팀')  echo 'display:none';
                    ?>" onclick ="ipgo_complate()">입고처리완료<br>(복수선택가능)</div>
                    <div class="btn btn_02" style="height: 55px; font-size: 13px; <? 
                    if ($is_admin != 'super' && $allowA['mb_dept'] != '플랫폼팀(디자인)' && $allowA['mb_dept'] != '디자인팀')  echo 'display:none';
                    ?>" onclick ="shooting_complate()">촬영완료<br>(복수선택가능)</div>
                    <div class="btn btn_02" style="height: 55px; font-size: 13px; <? 
                    if ($is_admin != 'super' && $member['mb_id'] != 'kitune90')  echo 'display:none';
                    ?>" onclick ="online_offline()">전용상품여부<br>(복수선택가능)</div>
                    <div class="btn btn_02" style="height: 55px; font-size: 13px;" onclick ="down_excel()">엑셀다운로드<br>(복수선택가능)</div>
                    <div class="btn btn_02" style="height: 55px; font-size: 13px;" onclick ="down_excel_all()">엑셀다운로드<br>(전체)</div>
                    <div class="btn btn_02" style="height: 55px; font-size: 13px; <? 
                    if ($is_admin != 'super' && $allowA['mb_dept'] != '플랫폼팀(디자인)' && $allowA['mb_dept'] != '디자인팀' && $allowA['mb_dept'] != '상품MD팀')  echo 'display:none';
                    ?>" onclick ="del_row()">행 삭제<br>(복수선택가능)</div>
                    <div class="btn btn_02" style="height: 55px; font-size: 13px; <? 
                    if ($is_admin != 'super' && $member['mb_id'] != 'lisa31' && $member['mb_id'] != 'sasa1066')  echo 'display:none';
                    ?>" onclick ="send_overseas()">해외이동<br>(복수가능)</div>
                    <div class="btn btn_02" style="height: 55px; font-size: 13px; <? 
                    if ($is_admin != 'super' && $allowA['mb_dept'] != '상품MD팀')  echo 'display:none';
                    ?>" onclick ="send_cover('C')">커버이동<br>(복수가능)</div>
                    <div class="btn btn_02" style="height: 55px; font-size: 13px; <? 
                    if ($is_admin != 'super' && $allowA['mb_dept'] != '상품MD팀')  echo 'display:none';
                    ?>" onclick ="send_cover('S')">속통이동<br>(복수가능)</div>
                    <div class="btn btn_02" style="height: 55px; font-size: 13px; <? 
                    if ($is_admin != 'super' && $allowA['mb_dept'] != '상품MD팀')  echo 'display:none';
                    ?>" onclick ="send_cover('M')">메모리폼이동<br>(복수가능)</div>
                    <!-- <input type="button" value="주문취소(CS)" class="btn btn_02" onclick="forderlist_submit('주문취소');">
                    <input type="button" value="교환요청(CS)" class="btn btn_02" onclick="forderlist_submit('교환요청');" style="display: none;">
                    <input type="button" value="반품요청(CS)" class="btn btn_02" onclick="forderlist_submit('반품요청');"> -->
                </div>
                <div style="float: right">
                    <!-- <div class="btn btn_02" style="height: 55px; line-height:45px;" onclick ="location.href='./job.order.update.form.php?w='">최초작업지시서 등록</div> -->
                    <div class="btn btn_02" style="height: 55px; line-height:45px; font-size: 13px; <? 
                    if ($is_admin != 'super' && $allowA['mb_dept'] != '플랫폼팀(디자인)' && $allowA['mb_dept'] != '디자인팀' && $allowA['mb_dept'] != '생산팀' && $allowA['mb_dept'] != '상품MD팀')  echo 'display:none';
                    ?>" onclick ="create_job_order()">최초작업지시서 등록</div>
                    
                    <!-- <a href="<?= G5_ADMIN_URL ?>/cron/cron_samjin_ordercheck.php" target="_blank" class="btn btn_01">삼진동기화</a>
                    <a href="<?= G5_ADMIN_URL ?>/cron/cron_invoice.php" target="_blank"><input type="button" value="택배동기화" class="btn btn_01"></a>

                    <input type="button" value="주문확인" class="btn btn_02" onclick="forderlist_submit('주문확인');">
                    <input type="button" value="EXCEL" class="btn btn_02 excel_download">
                    <input type="button" value="EXCEL 정산용" class="btn btn_02 excel_download excel_invoice"> -->
                </div>
            </div>

        </form>
        

    </div>
</div>

<!-- 제품 전체 복사 -->
<div class="modal fade" id="item_copy_name_renew" tabindex="-1" role="dialog" aria-labelledby="item_copy_name_renew">
    <div class="modal-dialog  modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">상품 복사</h4>
            </div>
            <div class="modal-body">
                
                <lable style = "font-size: 15px;color: blue;"><input type="checkbox" name="chain_item" id="chain_item">  연관상품여부</lable>
                <div style = "margin-top:5px;">상품명을 입력해주세요.</div>
                <input type="text" style = "width:100%;" name="new_it_name" id="new_it_name" value="">
                
            </div>
            <div class="modal-footer">
                <button class="btn btn-dack" data-dismiss="modal" aria-label="Close">취소</button>
                <button class="btn btn-success" onclick = "copyItem2()">확인</button>
            </div>
        </div>
    </div>
</div>

<!-- 제품 아이템별 복사 -->
<div class="modal fade" id="prod_item_copy_name" tabindex="-1" role="dialog" aria-labelledby="prod_item_copy_name">
    <div class="modal-dialog  modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">상품 아이템별 복사</h4>
            </div>
            <div class="modal-body">
                
                <lable style = "font-size: 15px;color: blue;"><input type="checkbox" name="chain_item" id="chain_item"> 연관상품여부</lable>
                <div style = "margin-top:5px;">이동할 상품명을 입력해주세요.</div>
                <input type="text" style = "width:100%;" name="goto_item_name" id="goto_item_name" value="">
                
            </div>
            <div class="modal-footer">
                <button class="btn btn-dack" data-dismiss="modal" aria-label="Close">취소</button>
                <button class="btn btn-success" onclick = "copyItem3()">확인</button>
            </div>
        </div>
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
<div class="modal fade" id="all_down_load_excel_pop" tabindex="-1" role="dialog" aria-labelledby="all_down_load_excel_pop">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">엑셀다운로드(전체)</h4>
            </div>
            <div class="modal-body">
                <div id="excel_list">
                    <div class="excel_button_area">
                        <input type="hidden" name="excel_ps_id" id="excel_ps_id" value="">
                        <div class="excel_box" onclick="excel_down_all('job')">작업지시서
                        </div>
                        <div class="excel_box" onclick="excel_down_all('proposal')">제품기획서</div>
                        <div class="excel_box" onclick="excel_down_all('info')">상품정보집</div>
                        <div class="excel_box" onclick="excel_down_all('schedule')">생산일정</div>
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
                        <div class="excel_box" onclick ="go_job_order_temp(1)">국내가공완성-속통</div>
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

<ul class="contextmenu">
  <li><a href="#" onclick="removeImg_prod_main_img()">이미지삭제</a></li>
</ul>

<ul class="contextmenu1">
  <li><a href="#" onclick="copy_item_new()">복사하기</a></li>
  <!-- <li><a href="#" onclick="copyItem()">복사하기</a></li> -->
</ul>
<ul class="contextmenu2">
  <li><a href="#" onclick="add_file_delete()">첨부파일삭제</a></li>
</ul>
<ul class="contextmenu3">
  <li><a href="#" onclick="select_size_delete()">선택사이즈삭제</a></li>
</ul>
<ul class="contextmenu4">
  <li><a href="#" onclick="select_item_copy()">아이템복사</a></li>
</ul>


<div class="modal fade" id="preview_imgs" tabindex="-1" role="dialog" aria-labelledby="preview_imgs">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">대표이미지</h4>
            </div>

            <div class="modal-body">
                <div id="imgs">
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
    .cal-schedule {height : 113px; overflow-y : auto; word-break:break-all; width : 227px; padding: 10px; font-size : 11px;  }

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
    .new_goods_list th {
        width : 15%;   
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
    /* #hidden_table{display : none;} */
    #fold_btn {
        cursor: pointer;
        background-color: #c1c1c1;
        border: 1px solid #e5e5e5;
        padding: 3px;
        border-radius: 5px;
    }

</style>

<? if($folds == 'up') :?>
<style>
    .fold {display : none;}
</style>
<?endif ?>

<script>
    var today = null;
    var year = null;
    var month = null;
    var firstDay = null;
    var lastDay = null;
    var $tdDay = null;
    var $tdSche = null;
    var jsonData = null;

    var select_prod_img_idx = 0;
    var select_copy_item = "";
    var select_copy_item2 = "";
    var select_add_file = "";
    var select_item_size_id = 0;
    

    $(document).ready(function() {
        var fold_yn = $("#folds").val();
        if(fold_yn == 'up'){
            $("#folds").val("up");
            $("#fold_name").hasClass("fold_up");
            $("#fold_btn").text("OPEN");
        }else{
            $("#folds").val("down");
            $("#fold_name").removeClass();
            $("#fold_btn").text("CLOSE");
        }

        drawCalendar();
        initDate();
        drawDays();
        drawSche();
        $("#movePrevMonth").on("click", function(){movePrevMonth();});
        $("#moveNextMonth").on("click", function(){moveNextMonth();});
        $("#topscroll").scroll(function(){
            $("#bottomscroll").scrollLeft($("#topscroll").scrollLeft());
            // $("#topscroll2").scrollLeft($("#topscroll").scrollLeft());
        });
        // $("#topscroll2").scroll(function(){
        //     $("#bottomscroll").scrollLeft($("#topscroll2").scrollLeft());
        //     $("#topscroll1").scrollLeft($("#topscroll2").scrollLeft());
        // });
        $("#bottomscroll").scroll(function(){
            $("#topscroll").scrollLeft($("#bottomscroll").scrollLeft());
            // $("#topscroll2").scrollLeft($("#bottomscroll").scrollLeft());
        });
        
        check_input_group();

        //Show contextmenu:
        $(document).contextmenu(function(e){
            $(".contextmenu").hide();
            $(".contextmenu1").hide();
            $(".contextmenu2").hide();
            $(".contextmenu3").hide();
            $(".contextmenu4").hide();
            select_data = e.target;
            select_row = e.target.closest('div');
            select_row1 = e.target.closest('td');
            select_row2 = e.target.closest('.add_file_area');
            select_row3 = e.target.closest('.jo_item_size_btn');
            select_row4 = e.target.closest('.prod_item_copy');
            
            select_prod_img_idx = $('#'+select_row.id).attr('data-prod-img');
            select_copy_item = $('#'+select_row1.id).attr('data-item-name');
            if(select_row4) select_copy_item2 = $('#'+select_row4.id).attr('data-item-ps-id');
            if(select_row2) select_add_file = $('#'+select_row2.id).attr('data-add-file');

            if(select_row3) select_item_size_id = $('#'+select_row3.id).attr("data-isb-id");

            //Get window size:
            var winWidth = $(document).width();
            var winHeight = $(document).height();
            //Get pointer position:
            var posX = e.pageX;
            var posY = e.pageY;
            //Get contextmenu size:
            var menuWidth = $(".contextmenu").width();
            var menuHeight = $(".contextmenu").height();
            if(select_row.className.indexOf('prod_main_img_area') > -1){
                menuWidth = $(".contextmenu").width();
                menuHeight = $(".contextmenu").height();
            }
            if(select_row1.className.indexOf('prod_copy_area') > -1){
                menuWidth = $(".contextmenu1").width();
                menuHeight = $(".contextmenu1").height();
            }
            if(select_row2 && select_row2.className.indexOf('add_file_area') > -1){
                menuWidth = $(".contextmenu2").width();
                menuHeight = $(".contextmenu2").height();
            }
            if(select_row3 && select_row3.className.indexOf('jo_item_size_btn') > -1){
                menuWidth = $(".contextmenu3").width();
                menuHeight = $(".contextmenu3").height();
            }
            if(select_row4 && select_row4.className.indexOf('prod_item_copy') > -1){
                menuWidth = $(".contextmenu4").width();
                menuHeight = $(".contextmenu4").height();
            }
            
            //Security margin:
            var secMargin = 10;
            //Prevent page overflow:
            if(posX + menuWidth + secMargin >= winWidth
            && posY + menuHeight + secMargin >= winHeight){
            //Case 1: right-bottom overflow:
            posLeft = posX - menuWidth - secMargin + "px";
            posTop = posY - menuHeight - secMargin + "px";
            }
            else if(posX + menuWidth + secMargin >= winWidth){
            //Case 2: right overflow:
            posLeft = posX - menuWidth - secMargin + "px";
            posTop = posY + secMargin + "px";
            }
            else if(posY + menuHeight + secMargin >= winHeight){
            //Case 3: bottom overflow:
            posLeft = posX + secMargin + "px";
            posTop = posY - menuHeight - secMargin + "px";
            }
            else {
            //Case 4: default values:
            posLeft = posX + secMargin + "px";
            posTop = posY + secMargin + "px";
            };
            //Display contextmenu:

            if(select_row.className.indexOf('prod_main_img_area') > -1){
                $(".contextmenu").css({
                "left": posLeft,
                "top": posTop
                }).show();
            }
            if(select_row1.className.indexOf('prod_copy_area') > -1){
                $(".contextmenu1").css({
                "left": posLeft,
                "top": posTop
                }).show();
            }
            if(select_row2 && select_row2.className.indexOf('add_file_area') > -1){
                $(".contextmenu2").css({
                "left": posLeft,
                "top": posTop
                }).show();
            }
            if(select_row3 && select_row3.className.indexOf('jo_item_size_btn') > -1){
                $(".contextmenu3").css({
                "left": posLeft,
                "top": posTop
                }).show();
            }
            if(select_row4 && select_row4.className.indexOf('prod_item_copy') > -1){
                $(".contextmenu4").css({
                "left": posLeft,
                "top": posTop
                }).show();
            }
            // $(".contextmenu").css({
            // "left": posLeft,
            // "top": posTop
            // }).show();
            //Prevent browser default contextmenu.
            return false;
        });
        //Hide contextmenu:
        $(document).click(function(){
            $(".contextmenu").hide();
        });
        $(document).click(function(){
            $(".contextmenu1").hide();
        });
        $(document).click(function(){
            $(".contextmenu2").hide();
        });
        $(document).click(function(){
            $(".contextmenu3").hide();
        });
        $(document).click(function(){
            $(".contextmenu4").hide();
        });

        //대표이미지
        $('.file_main').hide();
        //상품 자료 pdf , xls 등등 파일
        // $('.add_info_file').hide();

        $('#prod_main_pf_foto').on('click', function () {$('.file_main').click();});
        $('.file_main').change(function () {
            var file = this.files[0];
            var reader = new FileReader();
            reader.onloadend = function () {
               $('#prod_main_pf_foto_img').attr('src', reader.result);
            }
            if (file) {
                reader.readAsDataURL(file);
            } else {
            }
        });
        
        //스크롤 감지
        $(window).on('scroll',function() {
            // console.log($("#h1")[0].clientWidth);
            $("#topscroll").css("position","static");
            $("#topscroll").css("top","none");
            // $("#hidden_table").css("display","none");
            var scrolltop = $(window).scrollTop();
            if(scrolltop > $("#topscroll").offset().top){
                var position = (scrolltop - $("#topscroll").offset().top) + $("#topscroll").offset().top - 184 ;
                //console.log(position , scrolltop , $("#topscroll").offset().top);
                $("#topscroll").css("position","absolute");
                $("#topscroll").css("width","98%");
                $("#topscroll").css("top",position+"px");
                // $("#hidden_table").css("display","block");
                // $("#hidden_table").css("position","absolute");
                // $("#hidden_table").css("top",(position + 35)+"px");
            }else{
                $("#topscroll").css("width","100%");
            }

        });

        
      

    });

    function preview_Imgs(target){
        
        let index = $(".group_prod_main_img_"+target).last().data("imgs-idx");
        
        let imgshtml = '';
        // imgshtml+= ' <div class="product-info-gallery gallery-view">';
        // imgshtml+= '    <div class="swiper-container gallery-top">';
        // imgshtml+= '        <div class="swiper-wrapper"> ';
        // for(var i = 0 ; i<=index; i++){   
        // imgshtml+= '            <div class="swiper-slide"><img src="/data/new_goods/'+$(".group_prod_main_img_"+target+"#arr_prod_main_img_"+i).val()+'"></div>';
        // }                
        // imgshtml+= '        </div>';
        // imgshtml+= '    </div>';
        // imgshtml+= '</div>';

        imgshtml+= '<div class="top_imgs_area">';
        
        imgshtml+= '<div class="top_imgs_group"><img  class="top_imgs" id="top_img_item" src="/data/new_goods/'+$(".group_prod_main_img_"+target+"#arr_prod_main_img_0").val()+'">';
        imgshtml+= '<input type="hidden" id = "frist_main_img" value = "/data/new_goods/'+$(".group_prod_main_img_"+target+"#arr_prod_main_img_0").val()+'"';
        imgshtml+= '</div>';
        imgshtml+= '</div>';
        imgshtml+= '<div class="thumbs_imgs_group">';
        for(var i = 0 ; i<=index; i++){   
        imgshtml+= '    <div  class="thumbs_imgs_area"><img onmouseover="bigImg(this)" onmouseout="normalImg(this)" class="thumbs_imgs" src="/data/new_goods/'+$(".group_prod_main_img_"+target+"#arr_prod_main_img_"+i).val()+'"></div>';
        }                
        imgshtml+= '</div>';
        
        
        
        $("#imgs").empty().append(imgshtml);

        $("#preview_imgs").modal('show');
    }

    //로딩
    function LoadingWithMask() {
        //화면의 높이와 너비를 구합니다.
        var maskHeight = $(document).height();
        var maskWidth  = window.document.body.clientWidth;
        
        //화면에 출력할 마스크를 설정해줍니다.
        var mask       ="<div id='mask' style='position:absolute; z-index:9000; background-color:#000000; display:none; left:0; top:0;'></div>";
        var loadingImg ='';
        
        loadingImg +=" <img src='/img/re/Spinner.gif' style='position: relative; top:300px; display: block; margin: 20% auto;'/>";
    
        //화면에 레이어 추가
        $('body')
            .append(mask)
    
        //마스크의 높이와 너비를 화면 것으로 만들어 전체 화면을 채웁니다.
        $('#mask').css({
                'width' : maskWidth,
                'height': maskHeight,
                'opacity' :'0.3'
        });
    
        //마스크 표시
        $('#mask').show();
    
        //로딩중 이미지 표시
        $('#mask').append(loadingImg);
        $('#loadingImg').show();
    }

    function closeLoadingWithMask() {
        $('#mask, #loadingImg').hide();
        $('#mask, #loadingImg').remove(); 
    }

    function bigImg(x) {
        $("#top_img_item").attr('src', x.src);
    }

    function normalImg(x) {
        $("#top_img_item").attr('src', $("#frist_main_img").val());
        
    }

    function prod_main_img(elem){
        var complete = false; 
        let target = $(elem).data("prod-img");
        var chk_img = $('.prod_main_pf_foto_img_'+target).attr( 'src' );
        if(chk_img){
            preview_Imgs(target);
        }else {
            $('.file_main_'+target).click();
            $('.file_main_'+target).change(function () {
                var file = this.files[0];
                var reader = new FileReader();
                reader.onloadend = function () {
                   $('.prod_main_pf_foto_img_'+target).attr('src', reader.result);
                }
                if (file) {
                    reader.readAsDataURL(file);
                } else {
                }

                var ps_id = target;            
                var files = this.files[0];
                var formData = new FormData();
             
                formData.append("ps_id", target);
                
                
                // formData.append("file", files);
                for(var i=0; i<$('#update_imgs_'+target)[0].files.length; i++){
                   
                    formData.append('file['+i+']', $('#update_imgs_'+target)[0].files[i]);
                }
                LoadingWithMask();
              
                $.ajax({
                    url:'./prod.schedule.mainImg.update.php',
                    type:'post',
                    processData: false,
                    contentType:false,
                    async: false,
                    data: formData,
                    
                    
                    success:function(data){
                        complete = true;                  
                    }
                });


                if(complete == true){
                    location.reload();
                }
                
            });
            
            
            
        }
    }
    function removeImg_prod_main_img(){
        var complete = false; 
        var ps_id = select_prod_img_idx;
        $('.file_main_'+select_prod_img_idx).val('');
        $("input[name ='prod_main_imgs[1]']").val('');
        $('.prod_main_pf_foto_img_'+select_prod_img_idx).removeAttr('src');
        LoadingWithMask();
        $.ajax({
            url:'./prod.schedule.mainImg.delete.php',
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

        if(complete == true){
            location.reload();
        }

    }

    function check_input_group(){
        var fileValue = $(".check_input_group_td").length;
        var fileData = new Array(fileValue);

        var fileValue_tr = $(".reset_tr_origin").length;
        var fileData_tr = new Array(fileValue_tr);

        let hei = 0;
        let tr_hei = 0;
        for(var i=0; i<fileValue; i++){                          
            hei = $(".check_input_group_td_"+i).height();
            $(".check_input_group_table_"+i).css('height' ,(hei-1)+'px'); 
        }

        // for(var si=0; si<fileValue_tr; si++){ 
        //     let tr_hei = 0;                         
        //     tr_hei = $("#reset_tr_origin_"+si).height();
        //     console.log(tr_hei-1);
        //     $(".reset_tr_"+si).css('height' ,(tr_hei-1)+'px'); 
        // }
        
    }
    
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
                    $sam_item_name = sample[k]['ps_prod_name'];
                    if(sample[k]['ps_re_order'] == 'N'){
                        sam_name = sample[k]['ps_it_name'];
                    }else{
                        if(sample[k]['ps_re_order'] == 'Y' && sample[k]['ps_reorder_id'] >0){
                            sam_name =  sample[k]['ps_it_name'] + ' R' + sample[k]['ps_reorder_id'];
                        }else{
                            sam_name =  sample[k]['ps_it_name'] + 'R';
                        }
                    }
                    arr_txt = "<div><a style='color:#f90000;' href='/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&stx="+sam_name+"&limit_list=10' target ='_blank'>" + sam_name+ " / " + $sam_item_name+"</a></div>";
                    //dateMatch = firstDay.getDay() + i -1;
                    dateMatch = i-1;
                    $tdSche.eq(dateMatch).append(arr_txt);
                }
            }
            for(var j = 0 ; j < bipgo.length; j++){
                if((bipgo[j]['s_year'] * 1) == (year*1) && (bipgo[j]['s_month']*1) == (month*1) && (firstDay.getDay() + (bipgo[j]['s_day']*1)) == i ){
                    //alert(items[k]['s_day'])
                    $b_item_name = bipgo[j]['ps_prod_name'];
                    if(bipgo[j]['ps_re_order'] == 'N'){
                        bi_name = bipgo[j]['ps_it_name'];
                    }else{
                        if(bipgo[j]['ps_re_order'] == 'Y' && bipgo[j]['ps_reorder_id'] >0 ){
                            bi_name =  bipgo[j]['ps_it_name'] + ' R' + bipgo[j]['ps_reorder_id'];
                        }else{
                            bi_name =  bipgo[j]['ps_it_name'] + 'R';
                        }
                    }
                    bi_txt = "<div><a style='color:#1c5eff;' href='/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&stx="+bi_name+"&limit_list=10' target ='_blank'>" + bi_name+" / " + $b_item_name+ "</a></div>";
                    //dateMatch = firstDay.getDay() + i -1;
                    dateMatch =  i -1;
                    $tdSche.eq(dateMatch).append(bi_txt);
                }
            }
            
            for(var l = 0 ; l < aipgo.length; l++){
                if((aipgo[l]['s_year'] * 1) == (year*1) && (aipgo[l]['s_month']*1) == (month*1) && (firstDay.getDay() +  (aipgo[l]['s_day']*1)) == i ){
                    //alert(items[l]['s_day'])
                    $a_item_name = aipgo[l]['ps_prod_name'];
                    if(aipgo[l]['ps_re_order'] == 'N'){
                        ai_name = aipgo[l]['ps_it_name'];
                    }else{
                        if(aipgo[l]['ps_re_order'] == 'Y' && aipgo[l]['ps_reorder_id'] > 0){
                            ai_name =  aipgo[l]['ps_it_name'] + ' R' + aipgo[l]['ps_reorder_id'];
                        }else{
                            ai_name =  aipgo[l]['ps_it_name'] + 'R';
                        }
                    }
                    ai_txt = "<div><a style='color:#62c500;' href='/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&stx="+ai_name+"&limit_list=10' target ='_blank'>" + ai_name + " / " + $a_item_name+ "</a></div>";
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
          window.location.href  =  newURL[0]+'?tabs=list&brands=&ipgos=&dpart_ipgos=&shootings=&gumsus=&gumsu_subs=&item_details=&sabangs=&fixeds=&folds=up&reorders=&chain_items=&sfl=it_name&stx=&sc_it_time=&limit_list=10';
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
        if($("#brand_0").is(":checked")){
            $(".brand").prop('checked',true);
            $("#brands").val('');
        }else{
            $(".brand").prop('checked',false);
        }
    });

    $(".ps_user_chk").change(function(){
        var ps_user_chks = "";
        $("#ps_user_chk_0").attr('checked',false);
        $("input.ps_user_chk:checked").each(function(){
            //alert($(this).val());
            if(ps_user_chks != "") ps_user_chks += ",";
            ps_user_chks += $(this).val();

        });
        $("#ps_user_chks").val(ps_user_chks);
    });
    $("#ps_user_chk_0").change(function(){
        if($("#ps_user_chk_0").is(":checked")){
            $(".ps_user_chk").prop('checked',true);
            $("#ps_user_chks").val('');
        }else{
            $(".ps_user_chk").prop('checked',false);
        }
    });

    $("#ps_code_year").change(function(){
        var ps_code_year = $(this).val();
        $("#code_year").val(ps_code_year);
    });
    $("#ps_code_season").change(function(){
        var ps_code_season = $(this).val();
        $("#code_season").val(ps_code_season);
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

    $(".dpart_ipgoyn").change(function(){
        var dpart_ps_ipgo = "";
        $("input.dpart_ipgoyn:checked").each(function(){
            //alert($(this).val());
            if(dpart_ps_ipgo != "") dpart_ps_ipgo += ",";
            dpart_ps_ipgo += $(this).val();

        });
        $("#dpart_ipgos").val(dpart_ps_ipgo);
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

    //
    $(".gumsu").change(function(){
        var ps_gumsu = "";
        $("input.gumsu:checked").each(function(){
            //alert($(this).val());
            if(ps_gumsu != "") ps_gumsu += ",";
            ps_gumsu += $(this).val();

        });
        $("#gumsus").val(ps_gumsu);
    });
    $(".gumsu_sub").change(function(){
        var ps_gumsu_sub = "";
        $("input.gumsu_sub:checked").each(function(){
            //alert($(this).val());
            if(ps_gumsu_sub != "") ps_gumsu_sub += ",";
            ps_gumsu_sub += $(this).val();

        });
        $("#gumsu_subs").val(ps_gumsu_sub);
    });
    $(".item_detail").change(function(){
        var ps_item_detail = "";
        $("input.item_detail:checked").each(function(){
            //alert($(this).val());
            if(ps_item_detail != "") ps_item_detail += ",";
            ps_item_detail += $(this).val();

        });
        $("#item_details").val(ps_item_detail);
    });
    $(".sabang").change(function(){
        var ps_sabang = "";
        $("input.sabang:checked").each(function(){
            //alert($(this).val());
            if(ps_sabang != "") ps_sabang += ",";
            ps_sabang += $(this).val();

        });
        $("#sabangs").val(ps_sabang);
    });
    $(".fixed").change(function(){
        var ps_fixed = "";
        $("input.fixed:checked").each(function(){
            //alert($(this).val());
            if(ps_fixed != "") ps_fixed += ",";
            ps_fixed += $(this).val();

        });
        $("#fixeds").val(ps_fixed);
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
    $(".chain_item").change(function(){
        var ps_chain_item = "";
        $("input.chain_item:checked").each(function(){
            //alert($(this).val());
            if(ps_chain_item != "") ps_chain_item += ",";
            ps_chain_item += $(this).val();

        });
        $("#chain_items").val(ps_chain_item);
    });

    $('.search-reset').on("click",function() {
        $('.stx').val('');
        $('.sc_it_time').val('');
        
        $(".brand").attr('checked',false);
        $("#brand_0").attr('checked',true);
        $("#brands").val('');
        $(".ipgoyn").attr('checked',false);
        $("#ipgoyn_0").attr('checked',true);
        $("#ipgoyn_1").attr('checked',true);
        $("#ipgos").val('');
        
        $(".reorder").attr('checked',true);
        $("#reorders").val('');
        $(".chain_item").attr('checked',true);
        $("#chain_items").val('');
        $(".shooting").attr('checked',true);
        $("#shootings").val('');

        
    });

    function copyItem(){
        // console.log(item_name);
        // console.log(items_ps);
        var complete = false;
        var copy_item_name = select_copy_item;

        // var ps_ids = items_ps.split(',');

        var name_value = prompt("상품명을 입력해주세요.", "");

        if(name_value) {
            var check = confirm("상품명 [ "+name_value+" ] 로 복사 진행하시겠습니까?");
            /* if(check == true) else false */
            if(check){
                // var item_name = item_name;
                // for(var i =0; i <ps_ids.length; i++){
                LoadingWithMask();
                $.ajax({
                    url:'./job.order.copy.update.php',
                    type:'post',
                    async: false,
                    data:{origin_name : copy_item_name , new_name :  name_value },
                    
                    error:function(error){
                        complete = false;   
                    },
                    success:function(response){
                        complete = true;                                
                    }
                });
                if(complete == true){
                    alert("복사되었습니다.");
                    location.reload();
                }                    
            }

        }
    }
    function copyItem2(){
        var complete = false;
        var copy_item_name = select_copy_item;

        
        var chain_item = is_checked("chain_item");
        var name_value = $("#new_it_name").val();

        if(name_value) {
            var check = confirm("상품명 [ "+name_value+" ] 로 복사 진행하시겠습니까?");
            /* if(check == true) else false */
            if(check){
                // var item_name = item_name;
                // for(var i =0; i <ps_ids.length; i++){
                LoadingWithMask();
                $.ajax({
                    url:'./job.order.copy.update.php',
                    type:'post',
                    async: false,
                    data:{origin_name : copy_item_name , new_name :  name_value , chain_item : chain_item },
                    
                    error:function(error){
                        complete = false;   
                    },
                    success:function(response){
                        complete = true;                                
                    }
                });
                if(complete == true){
                    alert("복사되었습니다.");
                    location.reload();
                }                    
            }

        }
    }

    function copyItem3(){
        var complete = false;
        var copy_item_ps = select_copy_item2;

        
        var chain_item = is_checked("chain_item");
        var name_value = $("#goto_item_name").val();
        
        if(name_value) {
            var check = confirm("상품명 [ "+name_value+" ] 로 복사 이송 진행하시겠습니까?");
            /* if(check == true) else false */
            if(check){
                
                LoadingWithMask();
                $.ajax({
                    url:'./job.order.copy.update_item.php',
                    type:'post',
                    async: false,
                    data:{origin_ps_id : copy_item_ps , new_name :  name_value , chain_item : chain_item },
                    
                    error:function(error){
                        complete = false;   
                    },
                    success:function(response){
                        complete = true;                                
                    }
                });
                if(complete == true){
                    alert("복사되었습니다.");
                    location.reload();
                }                    
            }

        }
    }

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
    function down_excel_all(){
        $('#all_down_load_excel_pop').modal('show');
    }

    function del_row(){
        if (!is_checked("chk[]")) {
            alert("삭제할 아이템 선택해주세요.");
	        return false;
        }
        // var count = $("input[name='chk[]']:checked").length;
        var alert_count = 0;

        $("input[name='chk[]']:checked").each(function() {
            var ps_id = $("input[name='ps_id["+this.value+"]']").val();    
            var name =  $("input[name='name["+this.value+"]']").val();   
            var it_name = $("#row_"+trim(name)).val();
            var count = $("input[class='"+trim(name)+"']:checked").length;

            var ckconfirm = confirm("해당 아이템 삭제 하시겠습니까?");
            if(ckconfirm){
                $.ajax({
                    url:'./new_goods_process_delete.php',
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
            }
            // if(it_name == count){
            //     if(alert_count == 0 ){
            //         alert("전체 아이템은 삭제 할수 없습니다.");
            //         alert_count = 1;
            //         return false;
            //     }
            // }
            // if(it_name > count){
            //     if(alert_count == 0 ){
            //         var ckconfirm = confirm("해당 아이템 삭제 하시겠습니까?");
            //         if(ckconfirm){
            //             alert_count = 1;
            //         }else{
            //             alert_count = 2;
            //         }
            //     }
            // }
            // if(alert_count == 1){

            //     $.ajax({
            //         url:'./new_goods_process_delete.php',
            //         type:'post',
            //         async: false,
            //         data:{ps_id : ps_id },
                    
            //         error:function(error){
            //             complete = false;  
            //         },
            //         success:function(response){
            //             complete = true;                  
            //         }
            //     });
            // }
            
            
        });
        if(complete == true){
            alert("삭제 되었습니다.");
            location.reload();
        }
    }

    function copy_item_new(){
        $('#item_copy_name_renew').modal('show');
    }

    function select_item_copy(){
        $('#prod_item_copy_name').modal('show');
    }

    function send_overseas(){
        complete = false; 
        // var new_name = $("#new_it_name").val();
        // var chain_item = is_checked("chain_item");

        // if(new_name){
        //     console.log(new_name , chain_item );
        // }
        if (!is_checked("chk[]")) {
            alert("해외이동 상품 선택해주세요.");
	        return false;
        }
        var ckconfirm = confirm("해당 상품을 해외파트 DB로 이관 하시겠습니까?");
        if(ckconfirm){
            $("input[name='chk[]']:checked").each(function() {
                var ps_id = $("input[name='ps_id["+this.value+"]']").val();    
                // alert(ps_id);
                LoadingWithMask();
                $.ajax({
                    url:'./send_db_overseas.php',
                    type:'post',
                    data:{ps_id : ps_id },
                    dataType : 'json',
                    // async: false,
                    success:function(result){
                        // console.log(result);
                        if (result.indexOf('201') !== -1) {
                            alert("해당 상품 이동 실패! \n 제조업체 누락 \n 해당 상품 작업지시서 제조업체 확인 바랍니다.");
                            location.reload();
                        }else if (result.indexOf('202') !== -1){
                            alert("해당 상품 이동 실패! \n 제조국 누락 \n 해당 상품 제품기획서 제조국 확인 바랍니다.");
                            location.reload();
                        }else if (result.indexOf('203') !== -1){
                            alert("해당 상품 이동 실패! \n 삼진누락 \n 삼진전산에서 해당 코드 찾을 수 없습니다.");
                            location.reload();
                        }else if (result.indexOf('100') !== -1){
                            alert("처리되었습니다.");
                            location.reload();
                        }
                    },error:function(error){
                        // console.log(error);
                        complete = false;  
                    }
                });
            });
        }
        if(complete == true){
            alert("처리 되었습니다.");
            location.reload();
        }

    }
    function send_cover(type){
        complete = false; 
        // var new_name = $("#new_it_name").val();
        // var chain_item = is_checked("chain_item");

        // if(new_name){
        //     console.log(new_name , chain_item );
        // }
        if (!is_checked("chk[]")) {
            alert("상품DB전산화 리오도 이동 상품 선택해주세요.");
	        return false;
        }
        var ckconfirm = '';
        var item_type = '';
        if(type == 'C'){
            item_type = "C";
            ckconfirm = confirm("해당 상품을 커버 파트 DB로 이관 하시겠습니까?");
        }else if(type == 'S'){
            item_type = "S";
            ckconfirm = confirm("해당 상품을 속통 파트 DB로 이관 하시겠습니까?");
        }else if(type == 'M'){
            item_type = "M";
            ckconfirm = confirm("해당 상품을 메모리폼 파트 DB로 이관 하시겠습니까?");
        }
        if(ckconfirm){
            $("input[name='chk[]']:checked").each(function() {
                var ps_id = $("input[name='ps_id["+this.value+"]']").val();    
                // alert(ps_id);
                LoadingWithMask();
                $.ajax({
                    url:'./send_db_cover.php',
                    type:'post',
                    data:{ps_id : ps_id , item_type : item_type},
                    dataType : 'json',
                    // async: false,
                    success:function(result){
                        // console.log(result);
                        if (result.indexOf('201') !== -1) {
                            alert("해당 상품 이동 실패! \n 제조업체 누락 \n 해당 상품 작업지시서 제조업체 확인 바랍니다.");
                            location.reload();
                        }else if (result.indexOf('202') !== -1){
                            alert("해당 상품 이동 실패! \n 제조국 누락 \n 해당 상품 제품기획서 제조국 확인 바랍니다.");
                            location.reload();
                        }else if (result.indexOf('203') !== -1){
                            alert("해당 상품 이동 실패! \n 삼진누락 \n 삼진전산에서 해당 코드 찾을 수 없습니다.");
                            location.reload();
                        }else if (result.indexOf('100') !== -1){
                            alert("처리되었습니다.");
                            location.reload();
                        }
                    },error:function(error){
                        // console.log(error);
                        complete = false;  
                    }
                });
            });
        }
        if(complete == true){
            alert("처리 되었습니다.");
            location.reload();
        }

    }

    function getParam(sname) {
        var params = location.search.substr(location.search.indexOf("?") + 1);
        var sval = "";
        params = params.split("&");
        for (var i = 0; i < params.length; i++) {
            temp = params[i].split("=");
            if ([temp[0]] == sname) { sval = temp[1]; }
        }
        return sval;
    }

    function create_job_order(type,jo_id,ps_id){
        $('#jo_create_type').val(type);
        $('#jo_create_jo_id').val(jo_id);
        $('#jo_create_ps_id').val(ps_id);

        $('#create_job_order_pop').modal('show');
    }
    function go_create_job_order(type,jo_id,ps_id,temp){
        $('#jo_create_type').val(type);
        $('#jo_create_jo_id').val(jo_id);
        $('#jo_create_ps_id').val(ps_id);
        go_job_order_temp(temp);
    }

    function go_job_order_temp(temp){
        let page = getParam("page");
        let type = $('#jo_create_type').val();
        let jo_id = $('#jo_create_jo_id').val();
        let ps_id = $('#jo_create_ps_id').val();
        if(type=='item'){
            ps_id = '';
        }
        if(temp == 1){
            location.href='./job.order.update.form.temp1.php?w=&ps_id='+ps_id+'&type='+type+'&be_jo_id='+jo_id+'&qstr='+"<? echo str_replace('amp;','', $qstr); ?>";
        }else if(temp == 2){
            location.href='./job.order.update.form.temp2.php?w=&ps_id='+ps_id+'&type='+type+'&be_jo_id='+jo_id+'&qstr='+"<? echo str_replace('amp;','', $qstr); ?>";
        }else if(temp == 3){
            location.href='./job.order.update.form.temp3.php?w=&ps_id='+ps_id+'&type='+type+'&be_jo_id='+jo_id+'&qstr='+"<? echo str_replace('amp;','', $qstr); ?>";
        }else if(temp == 4){
            location.href='./job.order.update.form.temp4.php?w=&ps_id='+ps_id+'&type='+type+'&be_jo_id='+jo_id+'&qstr='+"<? echo str_replace('amp;','', $qstr); ?>";
        }else if(temp == 5){
            location.href='./job.order.update.form.temp5.php?w=&ps_id='+ps_id+'&type='+type+'&be_jo_id='+jo_id+'&qstr='+"<? echo str_replace('amp;','', $qstr); ?>";
        }else if(temp == 6){
            location.href='./job.order.update.form.temp6.php?w=&ps_id='+ps_id+'&type='+type+'&be_jo_id='+jo_id+'&qstr='+"<? echo str_replace('amp;','', $qstr); ?>";
        }else if(temp == 7){
            location.href='./job.order.update.form.temp7.php?w=&ps_id='+ps_id+'&type='+type+'&be_jo_id='+jo_id+'&qstr='+"<? echo str_replace('amp;','', $qstr); ?>";
        }
    }
    


    function create_proposal(item_name){
        $('#ip_it_name_item').val(item_name);
        $('#create_proposal_pop').modal('show');
    }
    function go_proposal_temp(temp){
        let page = getParam("page");
        let it_name = $('#ip_it_name_item').val();
        if(temp == 1){
            location.href='./item.proposal.update.form.temp1.php?w=&it_name='+it_name+'&qstr='+"<? echo str_replace('amp;','', $qstr); ?>";
        }else{
            location.href='./item.proposal.update.form.temp2.php?w=&it_name='+it_name+'&qstr='+"<? echo str_replace('amp;','', $qstr); ?>";
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
    //엑셀 전체 다운로드
    function excel_down_all(type){
        
        var excel_sql = "";
        var excel_type = "";

        var headerdata = "";
        var bodydata = "";

        if(type == 'job'){
            excel_sql = "select * from lt_job_order ";
            headerdata = $('<input type="hidden" value="<?=$job_headers?>" name="headerdata">');
            bodydata = $('<input type="hidden" value="<?=$job_bodys?>" name="bodydata">');
            excel_type = '작업지시서';
        }else if(type == 'proposal'){
            excel_sql = "select * from lt_item_proposal ";
            headerdata = $('<input type="hidden" value="<?=$proposal_headers?>" name="headerdata">');
            bodydata = $('<input type="hidden" value="<?=$proposal_bodys?>" name="bodydata">');
            excel_type = '제품기획서';
        }else if(type == 'info'){
            excel_sql = "select * from lt_prod_info ";
            headerdata = $('<input type="hidden" value="<?=$info_headers?>" name="headerdata">');
            bodydata = $('<input type="hidden" value="<?=$info_bodys?>" name="bodydata">');
            excel_type = '상품정보집';
        }else if(type == 'schedule'){
            excel_sql = "select * from lt_prod_schedule ";
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
    //전용상품 처리 온라인 오프라인
    function online_offline(){
        var chk = $("input[name='chk[]']:checked").val();
        var complete = false;
        //var ps_id = $("input[name='ps_id["+chk+"]']").val();

        $("input[name='chk[]']:checked").each(function() {
            var ps_id = $("input[name='ps_id["+this.value+"]']").val();            
            $.ajax({
                url:'./prod.schedule.online.update.php',
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
            alert("오프라인 전용상품으로 처리되었습니다.");
            location.reload();
        }

    }

    $("#new_goods_form").submit(function(){
        LoadingWithMask();
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
    function enterSearch() {
        if (window.event.keyCode == 13) {
        	document.getElementById('new_goods_form').submit();
    	}
    }

    function fold_up_down(){
        var chk_fold = $("#fold_name").hasClass("fold_up");
        if(chk_fold){
            $("#fold_name").removeClass();
            $(".fold").css("display","table-row");
            $("#folds").val("down");
            $("#fold_btn").text("CLOSE");
        }else{
            $("#fold_name").addClass("fold_up");
            $(".fold").css("display","none");
            $("#folds").val("up");
            $("#fold_btn").text("OPEN");
        }
    }

    // 엑셀 업로드 !
    $('#upload_price_btn').on('click', function () {
        var check = confirm("판가 엑셀 업로드 하시겠습니까?");
        if(check){
            $('#upload_excel').click();
        }
    });
    $('#upload_excel').change(function () {
        var file = this.files[0];
        var reader = new FileReader();
        reader.onloadend = function () {
        }
        if (file) {
            reader.readAsDataURL(file);
        } else {
        }
        priceExcel();
    });
    function priceExcel(){
        var $excelfile = $("#upload_excel");
            
        var $form = $('<form></form>');     
        $form.attr('action', './new_goods_process_excel_upload.php');
        $form.attr('method', 'post');
        $form.attr('enctype', 'multipart/form-data');
        $form.appendTo('body');
        $form.append($excelfile);
        // return
        $form.submit();
        
    }

    var originPs_id = '';

    function add_info_file(ps_id){
        $file_chk = $("#add_file_name_"+ps_id).val();

        if($file_chk == '' ){

            $('#add_info_file_'+ps_id).click();
            $('#add_info_file_'+ps_id).change(function () {
                LoadingWithMask();
                var file = this.files[0];
                var reader = new FileReader();
                reader.onloadend = function () {
                    // $('.add_info_file_'+ps_id).attr('src', reader.result);
                }
                if (file) {
                    
                    reader.readAsDataURL(file);
                } else {
                }
    
                // var ps_id = ps_id;            
                var files = this.files[0];
                var formData = new FormData();
                
                formData.append("ps_id", ps_id);
                formData.append("type", 'add');
                
                
                // formData.append("file", files);
                formData.append('file[0]', $('#add_info_file_'+ps_id)[0].files[0]);
                // for(var i=0; i<$('#add_info_file_'+ps_id)[0].files.length; i++){
                    
                // }
                
                
                $.ajax({
                    url:'./new_goods_process_add_info_upload.php',
                    type:'post',
                    processData: false,
                    contentType:false,
                    async: false,
                    data: formData,
                    
                    
                    success:function(data){
                        console.log(data);
                        complete = true;                  
                    }
                });
    
    
                if(complete == true){
                    location.reload();
                }
                
            });
        }else{

            var check = confirm("첨부파일을 여시겠습니까?");
            if(check){
                window.open("https://lifelikecdn.co.kr/new_goods/job_order_info/"+$file_chk);
            }

    
        }
    }
    function add_file_delete(){
        LoadingWithMask();
        var del_item = select_add_file;
        
        var formData = new FormData();
        
        formData.append("ps_id", del_item);
        formData.append("type", 'delete');

        $.ajax({
            url:'./new_goods_process_add_info_upload.php',
            type:'post',
            processData: false,
            contentType:false,
            async: false,
            data: formData,
            
            
            success:function(data){
                console.log(data);
                complete = true;                  
            }
        });
        if(complete == true){
            location.reload();
        }
    }
    function select_size_delete(){
        var del_item = select_item_size_id;
        
        var formData = new FormData();
        
        formData.append("jo_id", del_item);
        formData.append("type", 'delete');

        $.ajax({
            url:'./job_order_select_size_delete.php',
            type:'post',
            processData: false,
            contentType:false,
            async: false,
            data: formData,
            
            
            success:function(data){
                console.log(data);
                complete = true;                  
            }
        });
        if(complete == true){
            location.reload();
        }
    }
</script>


<?php 
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
