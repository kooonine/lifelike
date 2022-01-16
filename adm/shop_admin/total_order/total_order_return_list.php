<?php
$sub_menu = '41';
include_once('./_common.php');
include_once(G5_LIB_PATH . '/PHPExcel.php');
include_once(G5_LIB_PATH . '/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');


auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '반품 리스트';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

// echo '주문서';
// echo '<br> ov_search_type1  : '.$ov_search_type;

// dd($ov_mapping_status_case);

$orderWhere = "";
if ($ov_search_type) {
    if ($ov_search_type =='ov_tel') {
        // $orderWhere .= " AND (sabang_lt_order_form.receive_tel LIKE '%$ov_search_keyword%' OR sabang_lt_order_form.receive_cel LIKE '%$ov_search_keyword%')"; 
        $orderWhere .= " AND (sabang_lt_order_form.receive_tel LIKE '%$ov_search_keyword%' OR sabang_lt_order_form.receive_cel LIKE '%$ov_search_keyword%' OR sabang_return_origin.RECEIVE_TEL LIKE '%$ov_search_keyword%' OR sabang_return_origin.RECEIVE_CEL LIKE '%$ov_search_keyword%')"; 
    } else $orderWhere .= " AND $ov_search_type LIKE '%$ov_search_keyword%'"; 
}

if(in_array('전체', $ov_dpartner_case) || !$ov_dpartner_case) {

} else if ((in_array('경민실업', $ov_dpartner_case) && in_array('어시스트', $ov_dpartner_case))) {
    $orderWhere .= " AND (sabang_lt_order_form.dpartner_id = '경민실업' OR sabang_lt_order_form.dpartner_id = '어시스트')"; 
} else if (in_array('경민실업', $ov_dpartner_case)) {
    $orderWhere .= " AND sabang_lt_order_form.dpartner_id = '경민실업'"; 
} else if (in_array('어시스트', $ov_dpartner_case)) { 
    $orderWhere .= " AND sabang_lt_order_form.dpartner_id = '어시스트'"; 
}

if(in_array('전체', $auto_check_case) || !$auto_check_case) {

} else {
    $checkNum = 0;
    if (in_array('자동', $auto_check_case)) {
        if($checkNum ==0) $orderWhere .= " AND (auto_check = '자동'";
        else $orderWhere .= " OR auto_check = '자동'";
        $checkNum = 1;
    } 
    if (in_array('수동', $auto_check_case)) {
        if($checkNum ==0) $orderWhere .= " AND (auto_check = '수동'";
        else $orderWhere .= " OR auto_check = '수동'";
        $checkNum = 1;
    }
    if($checkNum ==1) $orderWhere .= ")";
} 


if(in_array('전체', $return_status_case) || !$return_status_case) {

} else {
    $checkNum = 0;
    if (in_array('반품접수', $return_status_case)) {
        if($checkNum ==0) $orderWhere .= " AND (return_status = '반품접수'";
        else $orderWhere .= " OR return_status = '반품접수'";
        $checkNum = 1;
    } 
    if (in_array('입고확인', $return_status_case)) {
        if($checkNum ==0) $orderWhere .= " AND (return_status = '입고확인'";
        else $orderWhere .= " OR return_status = '입고확인'";
        $checkNum = 1;
    }
    if (in_array('환불완료', $return_status_case)) {
        if($checkNum ==0) $orderWhere .= " AND (return_status = '환불완료'";
        else $orderWhere .= " OR return_status = '환불완료'";
        $checkNum = 1;
    }
    if (in_array('반품완료', $return_status_case)) {
        if($checkNum ==0) $orderWhere .= " AND (return_status = '반품완료'";
        else $orderWhere .= " OR return_status = '반품완료'";
        $checkNum = 1;
    }
    if($checkNum ==1) $orderWhere .= ")";
} 

if(in_array('전체', $return_reason_case) || !$return_reason_case) {

} else {
    $checkNum = 0;
    if (in_array('정상', $return_reason_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ro_reason = '정상'";
        else $orderWhere .= " OR ro_reason = '정상'";
        $checkNum = 1;
    } 
    if (in_array('불량', $return_reason_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ro_reason = '불량'";
        else $orderWhere .= " OR ro_reason = '불량'";
        $checkNum = 1;
    }
    if (in_array('오배송', $return_reason_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ro_reason = '오배송'";
        else $orderWhere .= " OR ro_reason = '오배송'";
        $checkNum = 1;
    }
    if (in_array('기타', $return_reason_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ro_reason IS NOT NULL AND ro_reason != '' AND ro_reason != '정상' AND ro_reason != '불량' AND ro_reason != '오배송'";
        else $orderWhere .= " OR (ro_reason IS NOT NULL AND ro_reason != '' AND ro_reason != '정상' AND ro_reason != '불량' AND ro_reason != '오배송')";
        $checkNum = 1;
    }
    if($checkNum ==1) $orderWhere .= ")";
}

if(in_array('전체', $mall_name_case) || !$mall_name_case) {

} else {
    $checkNum = 0;
    if (in_array('자사몰', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '자사몰'";
        else $orderWhere .= " OR mall_name = '자사몰'";
        $checkNum = 1;
    } 
    if (in_array('29CM', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '29CM'";
        else $orderWhere .= " OR mall_name = '29CM'";
        $checkNum = 1;
    }
    if (in_array('패션플러스', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '패션플러스'";
        else $orderWhere .= " OR mall_name = '패션플러스'";
        $checkNum = 1;
    }
    if (in_array('이마트(신)', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '이마트(신)'";
        else $orderWhere .= " OR mall_name = '이마트(신)'";
        $checkNum = 1;
    }
    if (in_array('옥션', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '옥션'";
        else $orderWhere .= " OR mall_name = '옥션'";
        $checkNum = 1;
    } 
    if (in_array('오늘의집', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '오늘의집'";
        else $orderWhere .= " OR mall_name = '오늘의집'";
        $checkNum = 1;
    }
    if (in_array('LG패션', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = 'LG패션'";
        else $orderWhere .= " OR mall_name = 'LG패션'";
        $checkNum = 1;
    }
    if (in_array('하프클럽(신)', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '하프클럽(신)'";
        else $orderWhere .= " OR mall_name = '하프클럽(신)'";
        $checkNum = 1;
    }
    if (in_array('굳닷컴', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '굳닷컴'";
        else $orderWhere .= " OR mall_name = '굳닷컴'";
        $checkNum = 1;
    } 
    if (in_array('한샘(신)', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '한샘(신)'";
        else $orderWhere .= " OR mall_name = '한샘(신)'";
        $checkNum = 1;
    }
    if (in_array('현대홈쇼핑(신)', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '현대홈쇼핑(신)'";
        else $orderWhere .= " OR mall_name = '현대홈쇼핑(신)'";
        $checkNum = 1;
    }
    if (in_array('AKmall(신)', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = 'AKmall(신)'";
        else $orderWhere .= " OR mall_name = 'AKmall(신)'";
        $checkNum = 1;
    }
    if (in_array('GS shop', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = 'GS shop'";
        else $orderWhere .= " OR mall_name = 'GS shop'";
        $checkNum = 1;
    } 
    if (in_array('롯데온', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '롯데온'";
        else $orderWhere .= " OR mall_name = '롯데온'";
        $checkNum = 1;
    }
    if (in_array('신세계몰(신)', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '신세계몰(신)'";
        else $orderWhere .= " OR mall_name = '신세계몰(신)'";
        $checkNum = 1;
    }
    if (in_array('CJ온스타일', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name IN ('CJOshopping (신)','CJ온스타일')";
        else $orderWhere .= " OR mall_name IN ('CJOshopping (신)','CJ온스타일')";
        $checkNum = 1;
    }
    if (in_array('롯데홈쇼핑(신)', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '롯데홈쇼핑(신)'";
        else $orderWhere .= " OR mall_name = '롯데홈쇼핑(신)'";
        $checkNum = 1;
    } 
    if (in_array('쿠팡', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '쿠팡'";
        else $orderWhere .= " OR mall_name = '쿠팡'";
        $checkNum = 1;
    }
    if (in_array('위메프(신)', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '위메프(신)'";
        else $orderWhere .= " OR mall_name = '위메프(신)'";
        $checkNum = 1;
    }
    if (in_array('티몬', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '티몬'";
        else $orderWhere .= " OR mall_name = '티몬'";
        $checkNum = 1;
    }
    if (in_array('11번가', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '11번가'";
        else $orderWhere .= " OR mall_name = '11번가'";
        $checkNum = 1;
    } 
    if (in_array('지마켓', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '지마켓'";
        else $orderWhere .= " OR mall_name = '지마켓'";
        $checkNum = 1;
    }
    if (in_array('카카오', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '카카오'";
        else $orderWhere .= " OR mall_name = '카카오'";
        $checkNum = 1;
    }
    if (in_array('카카오메이커스', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '카카오메이커스'";
        else $orderWhere .= " OR mall_name = '카카오메이커스'";
        $checkNum = 1;
    }
    if (in_array('스마트스토어', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '스마트스토어'";
        else $orderWhere .= " OR mall_name = '스마트스토어'";
        $checkNum = 1;
    }
    if (in_array('카카오톡스토어', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '카카오톡스토어'";
        else $orderWhere .= " OR mall_name = '카카오톡스토어'";
        $checkNum = 1;
    }
    if (in_array('카카오선물하기', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '카카오선물하기'";
        else $orderWhere .= " OR mall_name = '카카오선물하기'";
        $checkNum = 1;
    }
    if (in_array('집꾸미기(3)', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '집꾸미기(3)'";
        else $orderWhere .= " OR mall_name = '집꾸미기(3)'";
        $checkNum = 1;
    }
    if (in_array('SSF SHOP', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = 'SSF SHOP'";
        else $orderWhere .= " OR mall_name = 'SSF SHOP'";
        $checkNum = 1;
    }
    if (in_array('텐바이텐', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '텐바이텐'";
        else $orderWhere .= " OR mall_name = '텐바이텐'";
        $checkNum = 1;
    }
    if (in_array('현대리바트(신)', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '현대리바트(신)'";
        else $orderWhere .= " OR mall_name = '현대리바트(신)'";
        $checkNum = 1;
    }
    if (in_array('이랜드몰', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '이랜드몰'";
        else $orderWhere .= " OR mall_name = '이랜드몰'";
        $checkNum = 1;
    }
    if (in_array('브랜디', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '브랜디'";
        else $orderWhere .= " OR mall_name = '브랜디'";
        $checkNum = 1;
    }
    if (in_array('한섬_EQL', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '한섬_EQL'";
        else $orderWhere .= " OR mall_name = '한섬_EQL'";
        $checkNum = 1;
    }
    if (in_array('코오롱FNC', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (mall_name = '코오롱FNC'";
        else $orderWhere .= " OR mall_name = '코오롱FNC'";
        $checkNum = 1;
    }
    if($checkNum ==1) $orderWhere .= ")";
}



// $degressWhere = '';
if ($sc_it_time != "") {
    if($sc_it_time =='  ') {
        // echo '<br> sc_it_time33 : '.$sc_it_time.'<br>';
    } else {
        $sc_it_time = str_replace('-' , '', $sc_it_time);

        $sc_it_times = explode("~", $sc_it_time);
        $fr_sc_it_time = trim($sc_it_times[0]);
        $to_sc_it_time = trim($sc_it_times[1]);
        if(! preg_match("/^[0-9]{4}(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_sc_it_time) ) $fr_sc_it_time = '';
        if(! preg_match("/^[0-9]{4}(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_sc_it_time) ) $to_sc_it_time = '';
        if ($fr_sc_it_time==$to_sc_it_time) {
        }
        $fr_sc_it_time .= '00';
        $to_sc_it_time .= '24';
        if ($fr_sc_it_time && $to_sc_it_time) {
            $orderWhere .= " AND sabang_return_origin.reg_date BETWEEN '$fr_sc_it_time' AND '$to_sc_it_time' ";
            // $degressWhere = " WHERE sabang_return_origin.reg_date BETWEEN '$fr_sc_it_time' AND '$to_sc_it_time' ";
        }
    }

} else {
    // $toDate = date("Ymd");
    // $orderWhere .= " AND sabang_return_origin.reg_date LIKE '$toDate%'"; 
    // $sc_it_time = date("Y-m-d").' ~ '.date("Y-m-d");
    // // $degressWhere = " WHERE sabang_return_origin.reg_date LIKE '$toDate%'";

    $weekOneDate = date('Ymd', strtotime('-6 days', G5_SERVER_TIME));
    $toDate = date("Ymd");
    $weekOneDate .= '00';
    $toDate .= '24';
    $orderWhere .= " AND sabang_return_origin.reg_date BETWEEN '$weekOneDate' AND '$toDate' ";
    $sc_it_time = date('Y-m-d', strtotime('-6 days', G5_SERVER_TIME)).' ~ '.date("Y-m-d");
    // $degressWhere = " WHERE sabang_return_origin.reg_date LIKE '$toDate%'";
}


// echo '$orderWhere : '.$orderWhere;
$totalSql = "SELECT count(*) AS CNT FROM sabang_return_origin LEFT JOIN sabang_lt_order_form ON sabang_return_origin.sno = sabang_lt_order_form.sno WHERE sro_id IS NOT NULL $orderWhere";
// $sql = " select count(od_id) as cnt " . $sql_common;
$countRow = sql_fetch($totalSql);
$total_count = $countRow['CNT'];
// echo '<br>총건수 : '.$total_count.'<br>';
if ($outputCount < 1 || !$outputCount) {
	$outputCount = 50;
}
$total_page  = ceil($total_count / $outputCount);
if ($page < 1 || !$page) {
	$page = 1;
}
$from_record = ($page - 1) * $outputCount;

// $listSql = "SELECT *, LEFT(sabang_return_origin.reg_date,8) AS return_reg_date FROM sabang_return_origin LEFT JOIN sabang_lt_order_form ON sabang_return_origin.sno = sabang_lt_order_form.sno WHERE sro_id IS NOT NULL $orderWhere ORDER BY sabang_return_origin.reg_date DESC, sabang_lt_order_form.mall_id DESC";
$listSql = "SELECT *, LEFT(sabang_return_origin.reg_date,8) AS return_reg_date, IF(ro_tel_check =1, sabang_return_origin.RECEIVE_CEL, sabang_lt_order_form.receive_cel) AS re_cel , IF(ro_tel_check =1, sabang_return_origin.RECEIVE_TEL, sabang_lt_order_form.receive_tel) AS re_tel FROM sabang_return_origin LEFT JOIN sabang_lt_order_form ON sabang_return_origin.sno = sabang_lt_order_form.sno WHERE sro_id IS NOT NULL $orderWhere ORDER BY sabang_return_origin.reg_date DESC, sabang_lt_order_form.mall_id DESC";
$listQuery = sql_query($listSql);


$headers = array('NO','반품상태','자동/수동','쇼핑몰명','원송장','반품접수일','회수송장','삼진품목명','색상','사이즈','주문수량','입고수량','박스수량','쇼핑몰주문번호','사방넷주문번호','주문자명','주문자전화번호1','주문자전화번호2','수취인명','수취인주소','배송메세지','수취인우편번호','수취인전화번호1','수취인전화번호2','입고사유','반품번호');
$bodys = array('NO','return_status','auto_check','mall_name','order_invoice','return_reg_date','ro_invoice','samjin_name','order_it_color','order_it_size','order_it_cnt','ro_cnt','order_box_cnt','mall_order_no','sabang_ord_no','order_name','order_cel','order_tel','receive_name','receive_addr','order_meg','receive_zipcode','re_cel','re_tel','ro_reason','sro_id');

$enc = new str_encrypt();

$excel_sql = $enc->encrypt($excel_sql);
$headers = $enc->encrypt(json_encode_raw($headers));
$bodys = $enc->encrypt(json_encode_raw($bodys));
// $summaries = $enc->encrypt(json_encode_raw($summaries));


$qstr2= "ov_search_type=".$ov_search_type."&amp;ov_search_keyword=".$ov_search_keyword."&amp;ov_dpartner_case=".$ov_dpartner_case."&amp;auto_check_case=".$auto_check_case."&amp;return_status_case=".$return_status_case."&amp;return_reason_case=".$return_reason_case."&amp;mall_name_case=".$mall_name_case."&amp;sc_it_time=".$sc_it_time."&amp;outputCount=".$outputCount."&amp;page=".$page;


?>

<!-- 검색 따로만들래 !! -->
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
            <form id="orderMainTable" name="frmorderlist" class="local_sch01 local_sch" onsubmit="$('#page').val('1');" method="get" style="text-align: left !important;">
            	<div class="tbl_frm01 tbl_wrap">
            		<table>
            			<colgroup>
            				<col class="grid_4">
            				<col>
            				<col class="grid_3">
                        </colgroup>
                        <!-- 검색분류 -->
            			<tr>
            				<th scope="row">검색분류</th>
            				<td colspan="2">
            					<div class="col-lg-1 col-md-3 col-sm-12 col-xs-12" style="z-index: 2;">
            						<select name="ov_search_type" id="ov_search_type" style="width: 120px;">
                                        <option value="samjin_name" <?= get_selected($ov_search_type, 'samjin_name'); ?>>삼진품목명</option>
                                        <option value="ov_mall_code" <?= get_selected($ov_search_type, 'ov_mall_code'); ?>>자체상품코드</option>
                                        <option value="samjin_code" <?= get_selected($ov_search_type, 'samjin_code'); ?>>삼진코드</option>
                                        <option value="mall_order_no" <?= get_selected($ov_search_type, 'mall_order_no'); ?>>쇼핑몰주문번호</option>
                                        <option value="sabang_ord_no" <?= get_selected($ov_search_type, 'sabang_ord_no'); ?>>사방넷주문번호</option>
                                        <option value="order_name" <?= get_selected($ov_search_type, 'order_name'); ?>>주문자명</option>
                                        <option value="sabang_lt_order_form.order_invoice" <?= get_selected($ov_search_type, 'sabang_lt_order_form.order_invoice'); ?>>송장번호</option>
                                        <option value="ov_tel" <?= get_selected($ov_search_type, 'ov_tel'); ?>>전화번호</option>
                                        <option value="sabang_lt_order_form.receive_name" <?= get_selected($ov_search_type, 'sabang_lt_order_form.receive_name'); ?>>수취인명</option>
                                    </select>
            					</div>
            					<div class="col-lg-4 col-md-3 col-sm-1 col-xs-12" style="z-index: 1;">
                                    <input type="text" name="ov_search_keyword" value="<?= $ov_search_keyword; ?>" id="ov_search_keyword" class="frm_input" size="58" autocomplete="off">
            					</div>
            				</td>
                        </tr>
                        <!-- 일자 -->
            			<tr>
                            <th scope="row">일자</th>
                            <td colspan="2">
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <input type='text' class="form-control" id="it_time" name="sc_it_time" value="" autocomplete="off"/>
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 10px;right: 24px;top: auto;cursor: pointer;"></i>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
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
                        <!-- 물류처 -->
            			<tr>
            				<th scope="row">물류처</th>
            				<td colspan="2">
            					<div class="col-lg-1 col-md-6 col-sm-12 col-xs-12">
                                    <input onclick='allCheck("ov_dpartner_case")' type="checkbox" name="ov_dpartner_case[]" value="전체" id="ov_dpartner_case01" <?php if(in_array('전체', $ov_dpartner_case) || !$ov_dpartner_case) echo "checked"; ?>>
                                    <label for="ov_dpartner_case01">전체</label>
            						<input type="checkbox" name="ov_dpartner_case[]" value="경민실업" id="ov_dpartner_case02" <?php if(in_array('전체', $ov_dpartner_case) || in_array('경민실업', $ov_dpartner_case) || !$ov_dpartner_case) echo "checked"; ?>>
            						<label for="ov_dpartner_case02">경민실업</label>
            						<input type="checkbox" name="ov_dpartner_case[]" value="어시스트" id="ov_dpartner_case03" <?php if(in_array('전체', $ov_dpartner_case) || in_array('어시스트', $ov_dpartner_case) || !$ov_dpartner_case) echo "checked"; ?>>
                                    <label for="ov_dpartner_case03">어시스트</label>
                                    <input type="checkbox" name="ov_dpartner_case[]" value="본사" id="ov_dpartner_case04" <?php if(in_array('전체', $ov_dpartner_case) || in_array('본사', $ov_dpartner_case) || !$ov_dpartner_case) echo "checked"; ?>>
            						<label for="ov_dpartner_case04">본사</label>
            					</div>
            				</td>
                        </tr>
                        
                        <!-- 상태 -->
                        <tr>
            				<th scope="row">반품상태</th>
            				<td colspan="2">
            					<div class="col-lg-1 col-md-6 col-sm-12 col-xs-12">
                                    <input onclick='allCheck("return_status_case")' type="checkbox" name="return_status_case[]" value="전체" id="return_status_case01" <?php if(in_array('전체', $return_status_case) || !$return_status_case) echo "checked"; ?>>
                                    <label for="return_status_case01">전체</label>
            						<input type="checkbox" name="return_status_case[]" value="반품접수" id="return_status_case02" <?php if(in_array('전체', $return_status_case) || in_array('반품접수', $return_status_case) || !$return_status_case) echo "checked"; ?>>
                                    <label for="return_status_case02">반품접수</label>
                                    <input type="checkbox" name="return_status_case[]" value="접수완료" id="return_status_case03" <?php if(in_array('전체', $return_status_case) || in_array('접수완료', $return_status_case) || !$return_status_case) echo "checked"; ?>>
                                    <label for="return_status_case03">접수완료</label>
            						<input type="checkbox" name="return_status_case[]" value="입고확인" id="return_status_case04" <?php if(in_array('전체', $return_status_case) || in_array('입고확인', $return_status_case) || !$return_status_case) echo "checked"; ?>>
                                    <label for="return_status_case04">입고확인</label>
                                    <input type="checkbox" name="return_status_case[]" value="환불완료" id="return_status_case05" <?php if(in_array('전체', $return_status_case) || in_array('환불완료', $return_status_case) || !$return_status_case) echo "checked"; ?>>
            						<label for="return_status_case05">환불완료</label>
                                    <input type="checkbox" name="return_status_case[]" value="반품완료" id="return_status_case06" <?php if(in_array('전체', $return_status_case) || in_array('반품완료', $return_status_case) || !$return_status_case) echo "checked"; ?>>
            						<label for="return_status_case06">반품완료</label>
            					</div>
            				</td>
                        </tr>
                        <!-- 자동/수동 -->
                        <tr>
            				<th scope="row">자동/수동</th>
            				<td colspan="2">
            					<div class="col-lg-1 col-md-6 col-sm-12 col-xs-12">
                                    <input onclick='allCheck("auto_check_case")' type="checkbox" name="auto_check_case[]" value="전체" id="auto_check_case01" <?php if(in_array('전체', $auto_check_case) || !$auto_check_case) echo "checked"; ?>>
                                    <label for="auto_check_case01">전체</label>
            						<input type="checkbox" name="auto_check_case[]" value="자동" id="auto_check_case02" <?php if(in_array('전체', $auto_check_case) || in_array('자동', $auto_check_case) || !$auto_check_case) echo "checked"; ?>>
            						<label for="auto_check_case02">자동</label>
            						<input type="checkbox" name="auto_check_case[]" value="수동" id="auto_check_case03" <?php if(in_array('전체', $auto_check_case) || in_array('수동', $auto_check_case) || !$auto_check_case) echo "checked"; ?>>
                                    <label for="auto_check_case03">수동</label>
            					</div>
            				</td>
                        </tr>
                        <!-- 입고사유 -->
                        <tr>
            				<th scope="row">입고사유</th>
            				<td colspan="2">
            					<div class="col-lg-1 col-md-6 col-sm-12 col-xs-12">
                                    <input onclick='allCheck("return_reason_case")' type="checkbox" name="return_reason_case[]" value="전체" id="return_reason_case01" <?php if(in_array('전체', $return_reason_case) || !$return_reason_case) echo "checked"; ?>>
                                    <label for="return_reason_case01">전체</label>
            						<input type="checkbox" name="return_reason_case[]" value="정상" id="return_reason_case02" <?php if(in_array('전체', $return_reason_case) || in_array('정상', $return_reason_case) || !$return_reason_case) echo "checked"; ?>>
            						<label for="return_reason_case02">정상</label>
            						<input type="checkbox" name="return_reason_case[]" value="불량" id="return_reason_case03" <?php if(in_array('전체', $return_reason_case) || in_array('입고확인', $return_reason_case) || !$return_reason_case) echo "checked"; ?>>
                                    <label for="return_reason_case03">불량</label>
                                    <input type="checkbox" name="return_reason_case[]" value="오배송" id="return_reason_case04" <?php if(in_array('전체', $return_reason_case) || in_array('반품완료', $return_reason_case) || !$return_reason_case) echo "checked"; ?>>
                                    <label for="return_reason_case04">오배송</label>
                                    <input type="checkbox" name="return_reason_case[]" value="기타" id="return_reason_case05" <?php if(in_array('전체', $return_reason_case) || in_array('반품완료', $return_reason_case) || !$return_reason_case) echo "checked"; ?>>
            						<label for="return_reason_case05">기타</label>
            					</div>
            				</td>
                        </tr>
                        
                        <!-- 쇼핑몰명 -->
                        <tr>
            				<th scope="row">쇼핑몰명</th>
            				<td colspan="2">
            					<div class="col-lg-1 col-md-6 col-sm-12 col-xs-12">
                                    <input onclick='allCheck("mall_name_case")' type="checkbox" name="mall_name_case[]" value="전체" id="mall_name_case01" <?php if(in_array('전체', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case01">전체</label>
            						<input type="checkbox" name="mall_name_case[]" value="자사몰" id="mall_name_case02" <?php if(in_array('전체', $mall_name_case) || in_array('자사몰', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
            						<label for="mall_name_case02">자사몰</label>
            						<input type="checkbox" name="mall_name_case[]" value="29CM" id="mall_name_case03" <?php if(in_array('전체', $mall_name_case) || in_array('29CM', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case03">29CM</label>
                                    <input type="checkbox" name="mall_name_case[]" value="패션플러스" id="mall_name_case04" <?php if(in_array('전체', $mall_name_case) || in_array('패션플러스', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case04">패션플러스</label>
                                    <input type="checkbox" name="mall_name_case[]" value="이마트(신)" id="mall_name_case05" <?php if(in_array('전체', $mall_name_case) || in_array('이마트(신)', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case05">이마트(신)</label>
                                    <input type="checkbox" name="mall_name_case[]" value="옥션" id="mall_name_case06" <?php if(in_array('전체', $mall_name_case) || in_array('옥션', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
            						<label for="mall_name_case06">옥션</label>
            						<input type="checkbox" name="mall_name_case[]" value="오늘의집" id="mall_name_case07" <?php if(in_array('전체', $mall_name_case) || in_array('오늘의집', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case07">오늘의집</label>
                                    <input type="checkbox" name="mall_name_case[]" value="LG패션" id="mall_name_case08" <?php if(in_array('전체', $mall_name_case) || in_array('LG패션', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case08">LG패션</label>
                                    <input type="checkbox" name="mall_name_case[]" value="하프클럽(신)" id="mall_name_case09" <?php if(in_array('전체', $mall_name_case) || in_array('하프클럽(신)', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case09">하프클럽(신)</label>
                                    <input type="checkbox" name="mall_name_case[]" value="굳닷컴" id="mall_name_case10" <?php if(in_array('전체', $mall_name_case) || in_array('굳닷컴', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
            						<label for="mall_name_case10">굳닷컴</label>
            						<input type="checkbox" name="mall_name_case[]" value="한샘(신)" id="mall_name_case11" <?php if(in_array('전체', $mall_name_case) || in_array('한샘(신)', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case11">한샘(신)</label>
                                    <input type="checkbox" name="mall_name_case[]" value="현대홈쇼핑(신)" id="mall_name_case12" <?php if(in_array('전체', $mall_name_case) || in_array('현대홈쇼핑(신)', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case12">현대홈쇼핑(신)</label>
                                    <input type="checkbox" name="mall_name_case[]" value="AKmall(신)" id="mall_name_case13" <?php if(in_array('전체', $mall_name_case) || in_array('AKmall(신)', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case13">AKmall(신)</label>
                                    <input type="checkbox" name="mall_name_case[]" value="GS shop" id="mall_name_case14" <?php if(in_array('전체', $mall_name_case) || in_array('GS shop', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
            						<label for="mall_name_case14">GS shop</label>
                                    <input type="checkbox" name="mall_name_case[]" value="롯데온" id="mall_name_case15" <?php if(in_array('전체', $mall_name_case) || in_array('롯데온', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case15">롯데온</label>
                                    <input type="checkbox" name="mall_name_case[]" value="신세계몰(신)" id="mall_name_case16" <?php if(in_array('전체', $mall_name_case) || in_array('신세계몰(신)', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case16">신세계몰(신)</label>
                                    <input type="checkbox" name="mall_name_case[]" value="CJ온스타일" id="mall_name_case17" <?php if(in_array('전체', $mall_name_case) || in_array('CJ온스타일', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case17">CJ온스타일</label>
                                    <br>
                                    <input type="checkbox" name="mall_name_case[]" value="롯데홈쇼핑(신)" id="mall_name_case18" <?php if(in_array('전체', $mall_name_case) || in_array('롯데홈쇼핑(신)', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
            						<label for="mall_name_case18">롯데홈쇼핑(신)</label>
            						<input type="checkbox" name="mall_name_case[]" value="쿠팡" id="mall_name_case19" <?php if(in_array('전체', $mall_name_case) || in_array('쿠팡', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case19">쿠팡</label>
                                    <input type="checkbox" name="mall_name_case[]" value="위메프(신)" id="mall_name_case20" <?php if(in_array('전체', $mall_name_case) || in_array('위메프(신)', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case20">위메프(신)</label>
                                    <input type="checkbox" name="mall_name_case[]" value="티몬" id="mall_name_case21" <?php if(in_array('전체', $mall_name_case) || in_array('티몬', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case21">티몬</label>
                                    <input type="checkbox" name="mall_name_case[]" value="11번가" id="mall_name_case22" <?php if(in_array('전체', $mall_name_case) || in_array('11번가', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
            						<label for="mall_name_case22">11번가</label>
            						<input type="checkbox" name="mall_name_case[]" value="지마켓" id="mall_name_case23" <?php if(in_array('전체', $mall_name_case) || in_array('지마켓', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case23">지마켓</label>
                                    <input type="checkbox" name="mall_name_case[]" value="카카오" id="mall_name_case24" <?php if(in_array('전체', $mall_name_case) || in_array('카카오', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case24">카카오</label>
                                    <input type="checkbox" name="mall_name_case[]" value="카카오메이커스" id="mall_name_case25" <?php if(in_array('전체', $mall_name_case) || in_array('카카오메이커스', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
            						<label for="mall_name_case25">카카오메이커스</label>
                                    <input type="checkbox" name="mall_name_case[]" value="스마트스토어" id="mall_name_case26" <?php if(in_array('전체', $mall_name_case) || in_array('스마트스토어', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
            						<label for="mall_name_case26">스마트스토어</label>
            						<input type="checkbox" name="mall_name_case[]" value="카카오톡스토어" id="mall_name_case27" <?php if(in_array('전체', $mall_name_case) || in_array('카카오톡스토어', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case27">카카오톡스토어</label>
                                    <input type="checkbox" name="mall_name_case[]" value="카카오선물하기" id="mall_name_case28" <?php if(in_array('전체', $mall_name_case) || in_array('카카오선물하기', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case28">카카오선물하기</label>
                                    <input type="checkbox" name="mall_name_case[]" value="집꾸미기(3)" id="mall_name_case29" <?php if(in_array('전체', $mall_name_case) || in_array('집꾸미기(3)', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case29">집꾸미기(3)</label>
                                    <input type="checkbox" name="mall_name_case[]" value="SSF SHOP" id="mall_name_case30" <?php if(in_array('전체', $mall_name_case) || in_array('SSF SHOP', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case30">SSF SHOP</label>
                                    <input type="checkbox" name="mall_name_case[]" value="텐바이텐" id="mall_name_case31" <?php if(in_array('전체', $mall_name_case) || in_array('텐바이텐', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case31">텐바이텐</label>
                                    <input type="checkbox" name="mall_name_case[]" value="현대리바트(신)" id="mall_name_case32" <?php if(in_array('전체', $mall_name_case) || in_array('현대리바트(신)', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case32">현대리바트(신)</label>
                                    <br>
                                    <input type="checkbox" name="mall_name_case[]" value="이랜드몰" id="mall_name_case33" <?php if(in_array('전체', $mall_name_case) || in_array('이랜드몰', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case33">이랜드몰</label>
                                    <input type="checkbox" name="mall_name_case[]" value="브랜디" id="mall_name_case34" <?php if(in_array('전체', $mall_name_case) || in_array('브랜디', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case34">브랜디</label>
                                    <input type="checkbox" name="mall_name_case[]" value="한섬_EQL" id="mall_name_case35" <?php if(in_array('전체', $mall_name_case) || in_array('한섬_EQL', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case35">한섬_EQL</label>
                                    <input type="checkbox" name="mall_name_case[]" value="코오롱FNC" id="mall_name_case36" <?php if(in_array('전체', $mall_name_case) || in_array('코오롱FNC', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                                    <label for="mall_name_case35">코오롱FNC</label>
            					</div>
            				</td>
            			</tr>

                        <!-- 보기 -->
                        <tr>
            				<th scope="row">보기</th>
            				<td colspan="2">
            					<div class="col-lg-1 col-md-6 col-sm-12 col-xs-12">
                                    <select name="outputCount" id="outputCount" style="width: 80px;">
                                        <option value=50 <?= get_selected($outputCount, 50); ?>>50개</option>
                                        <option value=100 <?= get_selected($outputCount, 100); ?>>100개</option>
                                        <option value=200 <?= get_selected($outputCount, 200); ?>>200개</option>
                                        <option value=300 <?= get_selected($outputCount, 300); ?>>300개</option>
                                        <option value=400 <?= get_selected($outputCount, 400); ?>>400개</option>
                                        <option value=500 <?= get_selected($outputCount, 500); ?>>500개</option>
                                    </select>
            					</div>
            				</td>
            			</tr>
            		</table>
            	</div>

            	<div class="form-group">
            		<div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <!-- <button class="btn btn_02" type="reset1" onclick="outputCountReset()">초기화</button> -->
                        <input type="button" value="초기화" class="btn btn_02" onclick="outputCountReset()">
            			<button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i>검색</button>
            		</div>
            	</div>
            </form>
            <div class="local_ov01 local_ov">
	            <span class="btn_ov01">[ 검색결과 <?= number_format($total_count); ?>건 ]</span>
            </div>
            <div class="local_cmd01 local_cmd">
                <div style="float: left">
                    <input type="button" value="엑셀다운로드" class="btn btn_02" id="excel_download1">
                    <input type="button" value="입고확인" class="btn btn_02" onclick="receiveConfirm()">
                    <input type="button" value="수동접수" class="btn btn_02" onclick="manualConfirm()">
                    <input type="button" value="접수완료" class="btn btn_02" onclick="registerConfirm()">
                    <input type="button" value="환불완료" class="btn btn_02" onclick="refundCompleted()">
                    <input type="button" value="저장" class="btn btn_02" onclick="telSave()">
                    <!-- <input type="button" value="회수송장대량등록" class="btn btn_02" onclick="returnInvocieAll()"> -->
                    <input type='file' name ="upload_excel_invoice" id='upload_excel_invoice' />
                    <div class="btn btn_02" style="height: 30px;" id="upload_excel_return_invoice_all">회수송장대량등록</div>
                    <?if( $member['mb_id'] == 'ny0606' || $member['mb_id'] == 'enskwkdsla12' || $member['mb_id'] == 'sbs608' || $member['mb_id'] == 'jjungil324') : ?>
                        <input type="button" value="반품삭제" class="btn btn_02" onclick="refundDelete()">
                    <?endif ?>
                </div>
                <div style="float: right">
                    <input type='file' name ="upload_excel" id='upload_excel' />
                    <div class="btn btn_02" style="height: 30px;" id="upload_excel_return_btn">엑셀반품업로드</div>
                </div>
            </div>
        </div>
    </div>
</div>



<style>
    th, td {
        white-space: nowrap;
        /* text-align: center !important; */
    }
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
    table.dataTable tr, td{
        border: 1px solid lightgray;
    }
    table.dataTable tr > td{
        border: 1px solid lightgray;
    }
    table.dataTable tr > th {
        border: 1px solid lightgray;
        background:#eeeeee;
    }
    #total_order_body{font-size : 13px;}
    /*table.dataTable thead > tr > td {
        width: 80px;
    }*/
    #upload_excel{display:none;}
    #upload_excel_invoice{display:none;}
</style>
<link rel="stylesheet" href="./fixed_table.css">
<body id="total_order_body">
    <table id="reportTb" class="display" style="width:100%;">
        <div class="tbl_head01 tbl_wrap" id="topscroll"  style="width:100%; margin:0px; overflow-x:scroll;">
            <div class="div1" style="width:1950px; height:20px;"></div>
        </div>    
        <thead>
            <tr>
                <th>
                <input onclick='allCheck2("chk_")' type="checkbox" id ='allchk'>
                </th>
                <th>반품상태</th>
                <th>자동/수동</th>
                <th>쇼핑몰명</th>
                <th>원송장</th>
                <th>반품접수일</th>
                <th>회수송장</th>
                <th>삼진품목명</th>
                <th>색상</th>
                <th>사이즈</th>
                <th>주문수량</th>
                <th>입고수량</th>
                <th>박스수량</th>
                <th>쇼핑몰주문번호<br>사방넷주문번호</th>
                <th>주문자명</th>
                <th>주문자<br>전화번호1</th>
                <th>주문자<br>전화번호2</th>
                <th>수취인명</th>
                <th>수취인주소</th>
                <th>배송메세지</th>
                <th>수취인<br>우편번호</th>
                <th>수취인<br>전화번호1</th>
                <th>수취인<br>전화번호2</th>
                <th>메모</th>
                <th>입고사유</th>
            
            </tr>
        </thead>
        <tbody id="revenue-status">
            
        <?
            for ($k = 0; $row = sql_fetch_array($listQuery); $k++) {  
        ?>
            <tr>  
                <td>
                    <input type="checkbox" name="chk[]" value="<?= $row['sro_id'] ?>" id="chk_<?= $k ?>", mall_name ="<?= $row['mall_name'] ?>" return_status="<?= $row['return_status'] ?>" auto_check ="<?= $row['auto_check'] ?>" ov_order_status ="<?= $row['ov_order_status'] ?>" ov_distribution_status ="<?= $row['ov_distribution_status'] ?>" ov_options_modify = "<?= $row['ov_options_modify'] ?>">
                </td>
                <td><? echo $row['return_status'] ?></td>
                <td><? echo $row['auto_check'] ?></td>
                <td><? echo $row['mall_name'] ?></td>
                <td><span style = "cursor: pointer;" onclick="mainOrderChange(<? echo $row['sabang_ord_no'] ?>)" id="ordCp_<?= $row['order_invoice'] ?>"><?= $row['order_invoice'] ?></span><br>
                    <? if ($row['order_invoice']) { ?>
                    <button onclick="ordCopy('<?= $row['order_invoice'] ?>')">복사</button>
                    <? } ?>
                </td>
                <td><? echo $row['return_reg_date'] ?></td>
                <td>
                    <input size="16" type="text" name="invoiceModi[]" id="invoiceModi_<?= $row['sro_id'] ?>" value = <? echo $row['ro_invoice'] ?>>    
                </td>
                <td><? echo $row['samjin_name'] ?></td>
                <td><? echo $row['order_it_color'] ?></td>
                <td><? echo $row['order_it_size'] ?></td>
                <td><? echo $row['order_it_cnt'] ?></td>
                <td>
                    <select name="return_cnt[]" id="return_cnt_<?= $row['sro_id'] ?>">
                        <?php for ($i=1; $i<=(int)$row['order_it_cnt']; $i++) { ?>
                            <option value="<?php echo $i ?>" <?= get_selected($row['ro_cnt'], $i); ?>><?php echo $i ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td><? echo $row['order_box_cnt'] ?></td>
                <td><? echo $row['mall_order_no'] ?><br>
                    <? echo $row['sabang_ord_no'] ?>
                    <br><? echo $row['sro_id'] ?>
                </td>
                <td><? echo $row['order_name'] ?></td>
                <td><? echo $row['order_cel'] ?></td>
                <td><? echo $row['order_tel'] ?></td>

                <td><? echo $row['receive_name'] ?></td>
                <td><? echo $row['receive_addr'] ?></td>
                <td><? echo $row['order_meg'] ?></td>

                <td><? echo $row['receive_zipcode'] ?></td>
                <td>
                    <input size="16" type="text" name="receiveCel[]" id="receiveCel_<?= $row['sro_id'] ?>" value = <? echo $row['re_cel'] ?>>
                </td>
                <td>
                    <input size="16" type="text" name="receiveTel[]" id="receiveTel_<?= $row['sro_id'] ?>" value = <? echo $row['re_tel'] ?>>
                </td>
                <td>
                    <? 
                        $sroId = $row['sro_id'];    
                        $memoSql = "SELECT count(*) AS CNT FROM sabang_return_memo WHERE sro_id = $sroId";
                        $memoF = sql_fetch($memoSql);
                        $memoCnt = $memoF['CNT'];
                        if ($memoCnt > 0) { ?>
                            <button style="background-color: #B2CCFF; border: 3px solid #B2CCFF;" onclick="showMemo(<? echo $row['sro_id'] ?>)">메모</button>
                        <?} else {?>
                            <button onclick="showMemo(<? echo $row['sro_id'] ?>)">메모</button>
                        <? } ?>
                </td>
                <td><? echo $row['ro_reason'] ?></td>
            </tr>
        <? 
            }
        ?>
        </tbody>
    </table>
    <?= get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr2&amp;page="); ?>
</body>
<script src="./fixed_table.js"></script>
<script>
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
            $('#it_time').val("  ");
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
    $(document).ready(function () {
        var table = $('#reportTb').DataTable({
            scrollY: "650px",
            scrollX: true,
            scrollCollapse: true,
            ordering: false,
            info: false,
            paging: false,
            searching: false,
            createdRow: function (row, data, dataIndex) {
                //ROWSPAN
                if (dataIndex == 0) {
                    // $('td:eq(0)', row).attr('rowspan', 9);
                }
                else {
                    // if (data[1] === '대한항공') {
                    //     $('td:eq(0)', row).attr('rowspan', 9);
                    // }
                    // else {
                    //     $('td:eq(0)', row).css('display', 'none');
                    // }
                }
                //COLSPAN
                if (data[1] === '합계') {
                    // $('td:eq(1)', row).attr('colspan', 2);
                }
                //CSS셋팅
                $('th.sorting_disabled').attr('style','text-align : center !important');
                $('td:not(:eq(0))', row).css('text-align', 'center');
                $('td:eq(0)', row).css('text-align', 'center');
                $('td:eq(1)', row).css('text-align', 'center');
                // $('td:eq(9)', row).css('width', '300px;');
                // $('th:eq(9)', row).css('width', '300px;');
            },
            fixedColumns: {
                leftColumns: 2
            }
        });
        $("#topscroll .div1").css('width',$("#reportTb").innerWidth() +'px');

        $("#topscroll").scroll(function(){
            $(".dataTables_scrollBody").scrollLeft($("#topscroll").scrollLeft());
        });
        $(".dataTables_scrollBody").scroll(function(){
            $("#topscroll").scrollLeft($(".dataTables_scrollBody").scrollLeft());
        });
        window.addEventListener("keydown", (e) => {
            if (e.keyCode == 13) {
                document.getElementById('orderMainTable').submit();
            }
        })
        $('#upload_excel').hide();
        $('#upload_excel_invoice').hide();
        $('#upload_excel_return_btn').on('click', function () {
            var check = confirm("엑셀로 수기 반품 업로드 하시겠습니까? \n 반품 엑셀 파일을 다시한번 확인 바랍니다!");
            if(check){
                $('#upload_excel').click();
            }
            
        });
        $('#upload_excel_return_invoice_all').on('click', function () {
            var check = confirm("회수송장 대량 업로드 하시겠습니까? \n 엑셀 파일을 다시한번 확인 바랍니다!");
            if(check){
                $('#upload_excel_invoice').click();
            }
        });
        $('#upload_excel').change(function () {
            var file = this.files[0];
            var reader = new FileReader();
            reader.onloadend = function () {
            //    $('#main_pf_foto_img').attr('src', reader.result);
            }
            if (file) {
                reader.readAsDataURL(file);
            } else {
            }
            etc_mall_excel_upload();
        });
        $('#upload_excel_invoice').change(function () {
            var file = this.files[0];
            var reader = new FileReader();
            reader.onloadend = function () {
            //    $('#main_pf_foto_img').attr('src', reader.result);
            }
            if (file) {
                reader.readAsDataURL(file);
            } else {
            }
            upload_excel_return_invoice_all();
        });
    });

    function ordCopy(e=false) {
        var text = document.getElementById("ordCp_"+e).innerText;
        var createInput = document.createElement("input");
        createInput.setAttribute("type", "text");
        document.getElementById("ordCp_"+e).appendChild(createInput);
        createInput.value = text;
        createInput.select();
        document.execCommand('copy');
        document.getElementById("ordCp_"+e).removeChild(createInput);
        alert('복사되었습니다.');
    }

    function etc_mall_excel_upload(){
        var $excelfile = $("#upload_excel");
            
        var $form = $('<form></form>');     
        $form.attr('action', './upload_etc_mall_order.php');
        $form.attr('method', 'post');
        $form.attr('enctype', 'multipart/form-data');
        $form.appendTo('body');
        $form.append($excelfile);

        $form.submit();
        
    }

    function upload_excel_return_invoice_all(){
        
        var $excelfile = $("#upload_excel_invoice");
            
        var $form = $('<form></form>');     
        $form.attr('action', './upload_return_invoice_all.php');
        $form.attr('method', 'post');
        $form.attr('enctype', 'multipart/form-data');
        $form.appendTo('body');
        $form.append($excelfile);

        $form.submit();
    }

    $(function(){
        $("#excel_download1, #excel_download2").click(function(){
            if (!is_checked("chk[]")) {
			alert("하나 이상 선택하세요.");
			return false;
            }
        
            var $form = $('<form></form>');     
            // $form.attr('action', '<?=G5_ADMIN_URL?>/ajax.excel_download.php');
            $form.attr('action', '<?=G5_ADMIN_URL?>/ajax.excel_download.total_order_view.php');
            $form.attr('method', 'post');
            $form.appendTo('body');

            var excel_obj = '';
            $("input[name='chk[]']:checked").each(function (index) {
                if (index != 0) {
                    excel_obj += ',';
                }
                excel_obj += $(this).val();            
            });
            // var excel_sql = "SELECT * FROM sabang_lt_order_view WHERE slov_id IN ("+excel_obj+")  AND (sub_slov_id = 0 <?= $orderWhere?>) ORDER BY ov_mall_id DESC, receive_date DESC, ov_order_id DESC, ov_ct_id ASC, slov_id DESC LIMIT <?= $from_record?>, <?= $outputCount?>";
            // var excel_sql = "SELECT sabang_lt_order_view.*, form.order_invoice FROM sabang_lt_order_view LEFT JOIN sabang_lt_order_form AS form ON form.mall_order_no = ov_order_id AND form.samjin_name = ov_samjin_name WHERE slov_id IN ("+excel_obj+") OR sub_slov_id IN ("+excel_obj+")  <?= $orderWhere?> ORDER BY ov_mall_id DESC, receive_date DESC, ov_order_id DESC, ov_ct_id ASC, sub_slov_id ASC, slov_id DESC LIMIT <?= $from_record?>, <?= $outputCount?>";
            // excel_sql = "select * from sabang_lt_order_form where sno in ( "+excel_sql +" ) order by mall_id ASC";
            var excel_sql = "SELECT *, LEFT(sabang_return_origin.reg_date,8) AS return_reg_date, IF(ro_tel_check =1, sabang_return_origin.RECEIVE_CEL, sabang_lt_order_form.receive_cel) AS re_cel , IF(ro_tel_check =1, sabang_return_origin.RECEIVE_TEL, sabang_lt_order_form.receive_tel) AS re_tel FROM sabang_return_origin LEFT JOIN sabang_lt_order_form ON sabang_return_origin.sno = sabang_lt_order_form.sno WHERE sro_id IS NOT NULL AND  sro_id IN ("+excel_obj+")  <?= $orderWhere?> ORDER BY sabang_return_origin.reg_date DESC, sabang_lt_order_form.mall_id DESC";
            
            var exceldata = $('<input type="hidden" value="'+excel_sql+'" name="exceldata">');
            var headerdata = $('<input type="hidden" value="<?=$headers?>" name="headerdata">');
            var bodydata = $('<input type="hidden" value="<?=$bodys?>" name="bodydata">');
            var excelnamedata = $('<input type="hidden" value="반품리스트" name="excelnamedata">');
            $form.append(exceldata).append(headerdata).append(bodydata).append(excelnamedata);
            $form.submit();
        });
    })
    function mainOrderChange(e=false) {
        location.href="./total_order.php?return="+e;
    }
    function allCheck(e=false) {
        let allResult = false;
        if (e!='chk_') allResult = $('#'+e+'01').prop("checked");
        else allResult = $("input:checkbox[id='allchk']").is(":checked");
        if (allResult) {
            $("input:checkbox[id^='"+e+"']").prop("checked",true);
        } else {
            $("input:checkbox[id^='"+e+"']").prop("checked",false);
        }
    }
    function allCheck2(e=false) { 
        if ($("input:checkbox[id='allchk']").is(':checked')) {
            $(".DTFC_LeftBodyLiner input[name='chk[]']").prop("checked" , true);
        } else {
            $(".DTFC_LeftBodyLiner input[name='chk[]']").prop("checked" , false);
        }

    }

    function outputCountReset() {
        $("input:checkbox[id*='_case']").prop("checked",true);
        $("#ov_search_type").val('ov_it_name');
        $("#ov_search_keyword").val('');
        $("#outputCount").val(50);
    }

    // 이걸 메모로 변경하자
    function showMemo(e) {
        $.post(
            "total_order_return_memo.php", {
            sro_id: e
          },
          function(data) {
            $("#dvDetail").empty().html(data);
          }
        );
        $('#detail_modal').modal('show');
    }
    function receiveConfirm() {
        if (!is_checked("chk[]")) {
            alert("하나 이상 선택하세요.");
            return false;
        }
        $('#detail_modal2').modal('show');
    }

    function registerConfirm() {
        if (!is_checked("chk[]")) {
            alert("하나 이상 선택하세요.");
            return false;
        }
        let select_obj = '';
        let return_status = '';
        $("input[name='chk[]']:checked").each(function (index) {  
            return_status = $(this).attr("return_status");
            if (return_status != '반품접수') {
                alert('반품상태를 확인해주세요');
                select_obj = false;
                return false;
            }
            if (index != 0) {
                select_obj += ',';
            }
            select_obj += $(this).val();
        })
        if (!select_obj) return false;
        $.ajax({
            url: "./total_order_return_ajax.php",
            method: "POST",
            data: {
                'sro_idRegister' : select_obj
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(result) {
                // console.log(result);
                location.reload();
            }
        });
    }
    function manualConfirm() { 
        if (!is_checked("chk[]")) {
            alert("하나 이상 선택하세요.");
            return false;
        }
        let autoCofi = confirm("수동접수 하시겠습니까?");
        if(autoCofi){
            var select_obj = '';
            let auto_check = '';
            $("input[name='chk[]']:checked").each(function (index) { 
                auto_check = $(this).attr("auto_check");
                if (auto_check == '수동') {
                    alert('이미 수동인 반품건은 수동접수 불가합니다.');
                    return false;
                }
                if (index != 0) {
                    select_obj += ',';
                }
                select_obj += $(this).val();
            })
            if (auto_check=='수동') return; 
            $.ajax({
                url: "./total_order_return_ajax.php",
                method: "POST",
                data: {
                    'sro_idManual' : select_obj
                },
                dataType: "json",
                async: false,
                cache: false,
                success: function(result) {
                    // console.log(result);
                    location.reload();
                }
            });
        }

    }
    function refundCompleted() {
        if (!is_checked("chk[]")) {
            alert("하나 이상 선택하세요.");
            return false;
        }
        let compleCofi = confirm("환불완료 하시겠습니까?");
        if(compleCofi) { 
            let return_status = '';
            let return_obj = '';
            $("input[name='chk[]']:checked").each(function (index) {  
                return_status = $(this).attr("return_status");
                if (return_status == '입고확인') {
                    if (index != 0) {
                        return_obj += ',';
                    }
                    return_obj += $(this).val();
                } else {
                    alert('반품상태를 확인해주세요.');
                    return false;
                }
            })
            $.ajax({
                url: "./total_order_return_ajax.php",
                method: "POST",
                data: {
                    'sro_idReturnCom' : return_obj
                },
                dataType: "json",
                async: false,
                cache: false,
                success: function(result) {
                    // console.log(result);
                    location.reload();
                }
            });
        }
    }
    function telSave() {
        if (!is_checked("chk[]")) {
            alert("하나 이상 선택하세요.");
            return false;
        }
        let saveCofi = confirm("수취인 연락처 저장하시겠습니까?");
        if(saveCofi) { 
            let mappingTel = {};
            let mappingCel = {};

            $("input[name='chk[]']:checked").each(function (index) {  
                mappingCel[$(this).val()] = $(`#receiveCel_${$(this).val()}`).val();
                mappingTel[$(this).val()] = $(`#receiveTel_${$(this).val()}`).val();
            })

            $.ajax({
                url: "./total_order_return_ajax.php",
                method: "POST",
                data: {
                    'mappingCel' : mappingCel,
                    'mappingTel' : mappingTel
                },
                dataType: "json",
                async: false,
                cache: false,
                success: function(result) {
                    // console.log(result);
                    location.reload();
                }
            });
        }
    }
    function refundDelete() {
        
        if (!is_checked("chk[]")) {
            alert("하나 이상 선택하세요.");
            return false;
        }
        let deleteCofi = confirm("반품삭제 하시겠습니까?");
        if(deleteCofi) { 
            let mall_name = '';
            let return_obj = '';
            $("input[name='chk[]']:checked").each(function (index) {  
                mall_name = $(this).attr("mall_name");
                if (mall_name == '카카오메이커스' || mall_name == '카카오선물하기' || mall_name == '카카오톡스토어') {
                    if (index != 0) {
                        return_obj += ',';
                    }
                    return_obj += $(this).val();
                } else {
                    alert('사방넷 또는 자사몰 반품건은 삭제할수 없습니다.\n수기업로드한 반품건만 선택하여 삭제해주세요.');
                    return false;
                }
            })
            $.ajax({
                url: "./total_order_return_ajax.php",
                method: "POST",
                data: {
                    'sro_idDelete' : return_obj
                },
                dataType: "json",
                async: false,
                cache: false,
                success: function(result) {
                    // console.log(result);
                    location.reload();
                }
            });
        }
    }
</script>
<!-- 상세보기 !!!!!!!!!!! -->
<div class="modal fade" id="detail_modal" tabindex="-1" role="dialog" aria-labelledby="detail_modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content"  style ="width: 750px; margin-left: 200px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">메모</h4>
      </div>
      <div class="modal-body" style="text-align: center;">
        <div class="tbl_frm01 tbl_wrap" id="dvDetail">
        </div>
        <!-- 몸체를 만들자 -->
        <!-- <div class="" role="tabpanel" data-example-id="togglable-tabs">
          <div id="myTabContent" class="tab-content">
            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="all-tab">
            </div>
            <div role="tabpanel" class="tab-pane fade active in" id="tab_content2" aria-labelledby="stay-tab">
            </div>
            <div role="tabpanel" class="tab-pane fade active in" id="tab_content3" aria-labelledby="success-tab">
            </div>
            <div role="tabpanel" class="tab-pane fade active in" id="tab_content4" aria-labelledby="fail-tab">
            </div>
          </div>
          <div class="tbl_frm01 tbl_wrap" id="dvDetail">
          </div>
        </div> -->
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button> -->
      </div>
    </div>
  </div>
</div>

<!-- 버튼 하나더?? -->
<div class="modal fade" id="detail_modal2" tabindex="-1" role="dialog" aria-labelledby="detail_modal2">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content"  style ="width: 350px; margin-left: 450px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">입고확인</h4>
      </div>
      <div class="modal-body">
        <div class="tbl_frm01 tbl_wrap" id="dvDetail2">
            <!-- <div class="col-lg-1 col-md-6 col-sm-12 col-xs-12"> -->
                <input type="radio" name="ro_reason" value="정상" id="ro_reason01" checked="checked">
				<label for="ro_reason01">정상</label>
				<input type="radio" name="ro_reason" value="불량" id="ro_reason02" >
				<label for="ro_reason02">불량</label>
				<input type="radio" name="ro_reason" value="오배송" id="ro_reason03" >
				<label for="ro_reason03">오배송</label>
				<input type="radio" name="ro_reason" value="기타" id="ro_reason04">
                <label for="ro_reason04">기타</label>
                <br><br>
                <input type="text" id="etcText" name="etcText" style="display:none;" class="frm_input" size="35">
            <!-- </div> -->
        </div>
        <!-- 몸체를 만들자 -->
        <!-- <div class="" role="tabpanel" data-example-id="togglable-tabs">
          <div id="myTabContent" class="tab-content">
            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="all-tab">
            </div>
            <div role="tabpanel" class="tab-pane fade active in" id="tab_content2" aria-labelledby="stay-tab">
            </div>
            <div role="tabpanel" class="tab-pane fade active in" id="tab_content3" aria-labelledby="success-tab">
            </div>
            <div role="tabpanel" class="tab-pane fade active in" id="tab_content4" aria-labelledby="fail-tab">
            </div>
          </div>
          <div class="tbl_frm01 tbl_wrap" id="dvDetail">
          </div>
        </div> -->
        </div>
        <div class="modal-footer" style="text-align: center;">
            <input type="button" value="취소" class="btn btn_02" class="close" data-dismiss="modal" aria-label="Close">
            <input type="button" value="저장" class="btn btn_03" onclick="saveReason()">
        </div>
    </div>
  </div>
</div>
<script>
    $('input[name="ro_reason"]').change(function () { 
        let radioVal = document.querySelector('input[name="ro_reason"]:checked').value;
        if (radioVal == '기타') {
            $('#etcText').show();
            document.getElementById('etcText').focus();
        }
        else $('#etcText').hide();
    })
    function saveReason() {
        let radioSaveVal = document.querySelector('input[name="ro_reason"]:checked').value;
        if (radioSaveVal == '기타') {
            let etcTextVal = document.getElementById('etcText').value;
            if (!etcTextVal || etcTextVal == null || etcTextVal == '') {
                alert('텍스트를 입력해주세요');
                return false;    
            } 
            radioSaveVal = etcTextVal;
        }
        let mappingInvoie = {};
        let mappingRoCnt = {};
        let mappingErr = false;
        $("input[name='chk[]']:checked").each(function (index) { 
            mappingInvoie[$(this).val()] = $(`#invoiceModi_${$(this).val()}`).val();
            mappingRoCnt[$(this).val()] = $(`#return_cnt_${$(this).val()}`).val();
            return_status = $(this).attr("return_status");
            if (return_status != '접수완료' && return_status != '입고확인' && return_status != '반품접수' && return_status != '반품완료') {
                alert('반품상태를 확인해주세요');
                mappingErr = true;
                return false;
            }
            if (mappingInvoie[$(this).val()] == '' || mappingInvoie[$(this).val()] == null) {
                alert("회수송장을 입력해주세요.");
                mappingErr = true;
                return false;
            }
        })
        if (mappingErr) {
            $('#detail_modal2').modal('hide');
            return;
        }
        $.ajax({
            url: "./total_order_return_ajax.php",
            method: "POST",
            data: {
                'reason' : radioSaveVal,
                'mappingInvoie': mappingInvoie,
                'mappingRoCnt' : mappingRoCnt
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(result) {
                // console.log(result);
                location.reload();

            }
        });

    }
    function saveMemo(e) {
        let memoTextVal = document.getElementById('memoText').value;
        if (!memoTextVal || memoTextVal == null || memoTextVal == '') {
            alert('텍스트를 입력해주세요');
            return false;    
        } 
        $.ajax({
            url: "./total_order_return_ajax.php",
            method: "POST",
            data: {
                'sro_id' : e,
                'memoTextVal' : memoTextVal
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(result) {
                showMemo(e);
            }
        });

    }
</script>

<?php 
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
