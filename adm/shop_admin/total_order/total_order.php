<?php
$sub_menu = '41';
include_once('./_common.php');
include_once(G5_LIB_PATH . '/PHPExcel.php');
include_once(G5_LIB_PATH . '/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');


auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '주문서 리스트';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

// echo '주문서';
// echo '<br> ov_search_type1  : '.$ov_search_type;
$orderWhere = "";


if ($ov_search_type) {
    if ($ov_search_type =='ov_tel') {
        $orderWhere .= " AND (ov_order_tel LIKE '%$ov_search_keyword%' OR ov_receive_tel LIKE '%$ov_search_keyword%' )"; 
    } else if($ov_search_type =='ov_it_name' || $ov_search_type =='ov_samjin_name'  ) {
        $orderWhere .= " AND $ov_search_type LIKE '%$ov_search_keyword%'";
    } else{
        preg_match_all("/[^() || \/\,]+/", $ov_search_keyword,$list);
        $in_list = empty($list[0])?'NULL':"'".join("','", $list[0])."'";
        $orderWhere .= " AND $ov_search_type IN ({$in_list})"; 
    }
}

if(in_array('전체', $ov_dpartner_case) || !$ov_dpartner_case) {

} else if ((in_array('경민실업', $ov_dpartner_case) && in_array('어시스트', $ov_dpartner_case))) {
    $orderWhere .= " AND (ov_dpartner = '경민실업' OR ov_dpartner = '어시스트')"; 
} else if (in_array('경민실업', $ov_dpartner_case)) {
    $orderWhere .= " AND ov_dpartner = '경민실업'"; 
} else if (in_array('어시스트', $ov_dpartner_case)) { 
    $orderWhere .= " AND ov_dpartner = '어시스트'"; 
}

if(in_array('전체', $ov_order_status_case) || !$ov_order_status_case) {

} else {
    $checkNum = 0;
    if (in_array('신규주문', $ov_order_status_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_order_status = '신규주문'";
        else $orderWhere .= " OR ov_order_status = '신규주문'";
        $checkNum = 1;
    } 
    if (in_array('주문확인', $ov_order_status_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_order_status = '주문확인'";
        else $orderWhere .= " OR ov_order_status = '주문확인'";
        $checkNum = 1;
    } 
    if (in_array('출고전취소', $ov_order_status_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_order_status = '출고전취소'";
        else $orderWhere .= " OR ov_order_status = '출고전취소'";
        $checkNum = 1;
    }
    if (in_array('반품접수', $ov_order_status_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_order_status = '반품접수'";
        else $orderWhere .= " OR ov_order_status = '반품접수'";
        $checkNum = 1;
    }
    if (in_array('반품완료', $ov_order_status_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_order_status = '반품완료'";
        else $orderWhere .= " OR ov_order_status = '반품완료'";
        $checkNum = 1;
    }
    if (in_array('입고완료', $ov_order_status_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_order_status = '입고완료'";
        else $orderWhere .= " OR ov_order_status = '입고완료'";
        $checkNum = 1;
    }
    if (in_array('환불완료', $ov_order_status_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_order_status = '환불완료'";
        else $orderWhere .= " OR ov_order_status = '환불완료'";
        $checkNum = 1;
    }
    if (in_array('품절취소', $ov_order_status_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_order_status = '품절취소'";
        else $orderWhere .= " OR ov_order_status = '품절취소'";
        $checkNum = 1;
    }
    if($checkNum ==1) $orderWhere .= ")";
} 

if(in_array('전체', $ov_distribution_status_case) || !$ov_distribution_status_case) {

} else {
    $checkNum = 0;
    if (in_array('정상', $ov_distribution_status_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_distribution_status = '정상' OR ov_distribution_status = '' OR ov_distribution_status IS NULL";
        else $orderWhere .= " OR ov_distribution_status = '정상' OR ov_distribution_status = '' OR ov_distribution_status IS NULL";
        $checkNum = 1;
    } 
    if (in_array('출고확정', $ov_distribution_status_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_distribution_status = '출고확정'";
        else $orderWhere .= " OR ov_distribution_status = '출고확정'";
        $checkNum = 1;
    } 
    if (in_array('품절', $ov_distribution_status_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_distribution_status = '품절'";
        else $orderWhere .= " OR ov_distribution_status = '품절'";
        $checkNum = 1;
    }
    if (in_array('물류품절', $ov_distribution_status_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_distribution_status = '물류품절'";
        else $orderWhere .= " OR ov_distribution_status = '물류품절'";
        $checkNum = 1;
    }
    if (in_array('출고보류', $ov_distribution_status_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_distribution_status = '출고보류'";
        else $orderWhere .= " OR ov_distribution_status = '출고보류'";
        $checkNum = 1;
    }
    if($checkNum ==1) $orderWhere .= ")";
} 


if(in_array('전체', $ov_mapping_status_case) || !$ov_mapping_status_case || (in_array('오매핑', $ov_mapping_status_case) && in_array('매핑', $ov_mapping_status_case))) {

} else if (in_array('오매핑', $ov_mapping_status_case)) {
    $orderWhere .= " AND (ov_options_modify = '' OR ov_options_modify IS NULL)";
} else if (in_array('매핑', $ov_mapping_status_case)) { 
    $orderWhere .= " AND (ov_options_modify != '' OR ov_options_modify IS NOT NULL)";
}

if(in_array('전체', $ov_sms_check_case) || !$ov_sms_check_case || (in_array('발송', $ov_sms_check_case) && in_array('미발송', $ov_sms_check_case))) {

} else if (in_array('발송', $ov_sms_check_case)) {
    $orderWhere .= " AND (ov_sms_check = '1')";
} else if (in_array('미발송', $ov_sms_check_case)) { 
    $orderWhere .= " AND (ov_sms_check = '0')";
}

if(in_array('전체', $mall_name_case) || !$mall_name_case) {

} else {
    $checkNum = 0;
    if (in_array('자사몰', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '자사몰'";
        else $orderWhere .= " OR ov_mall_name = '자사몰'";
        $checkNum = 1;
    } 
    if (in_array('29CM', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '29CM'";
        else $orderWhere .= " OR ov_mall_name = '29CM'";
        $checkNum = 1;
    }
    if (in_array('패션플러스', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '패션플러스'";
        else $orderWhere .= " OR ov_mall_name = '패션플러스'";
        $checkNum = 1;
    }
    if (in_array('이마트(신)', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '이마트(신)'";
        else $orderWhere .= " OR ov_mall_name = '이마트(신)'";
        $checkNum = 1;
    }
    if (in_array('옥션', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '옥션'";
        else $orderWhere .= " OR ov_mall_name = '옥션'";
        $checkNum = 1;
    } 
    if (in_array('오늘의집', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '오늘의집'";
        else $orderWhere .= " OR ov_mall_name = '오늘의집'";
        $checkNum = 1;
    }
    if (in_array('LG패션', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = 'LG패션'";
        else $orderWhere .= " OR ov_mall_name = 'LG패션'";
        $checkNum = 1;
    }
    if (in_array('하프클럽(신)', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '하프클럽(신)'";
        else $orderWhere .= " OR ov_mall_name = '하프클럽(신)'";
        $checkNum = 1;
    }
    if (in_array('굳닷컴', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '굳닷컴'";
        else $orderWhere .= " OR ov_mall_name = '굳닷컴'";
        $checkNum = 1;
    } 
    if (in_array('한샘(신)', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '한샘(신)'";
        else $orderWhere .= " OR ov_mall_name = '한샘(신)'";
        $checkNum = 1;
    }
    if (in_array('현대홈쇼핑(신)', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '현대홈쇼핑(신)'";
        else $orderWhere .= " OR ov_mall_name = '현대홈쇼핑(신)'";
        $checkNum = 1;
    }
    if (in_array('AKmall(신)', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = 'AKmall(신)'";
        else $orderWhere .= " OR ov_mall_name = 'AKmall(신)'";
        $checkNum = 1;
    }
    if (in_array('GS shop', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = 'GS shop'";
        else $orderWhere .= " OR ov_mall_name = 'GS shop'";
        $checkNum = 1;
    } 
    if (in_array('롯데온', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '롯데온'";
        else $orderWhere .= " OR ov_mall_name = '롯데온'";
        $checkNum = 1;
    }
    if (in_array('신세계몰(신)', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '신세계몰(신)'";
        else $orderWhere .= " OR ov_mall_name = '신세계몰(신)'";
        $checkNum = 1;
    }
    if (in_array('CJ온스타일', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name IN ('CJOshopping (신)','CJ온스타일') ";
        else $orderWhere .= " OR ov_mall_name IN ('CJOshopping (신)','CJ온스타일')";
        $checkNum = 1;
    }
    if (in_array('롯데홈쇼핑(신)', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '롯데홈쇼핑(신)'";
        else $orderWhere .= " OR ov_mall_name = '롯데홈쇼핑(신)'";
        $checkNum = 1;
    } 
    if (in_array('쿠팡', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '쿠팡'";
        else $orderWhere .= " OR ov_mall_name = '쿠팡'";
        $checkNum = 1;
    }
    if (in_array('위메프(신)', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '위메프(신)'";
        else $orderWhere .= " OR ov_mall_name = '위메프(신)'";
        $checkNum = 1;
    }
    if (in_array('티몬', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '티몬'";
        else $orderWhere .= " OR ov_mall_name = '티몬'";
        $checkNum = 1;
    }
    if (in_array('11번가', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '11번가'";
        else $orderWhere .= " OR ov_mall_name = '11번가'";
        $checkNum = 1;
    } 
    if (in_array('지마켓', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '지마켓'";
        else $orderWhere .= " OR ov_mall_name = '지마켓'";
        $checkNum = 1;
    }
    if (in_array('카카오', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '카카오'";
        else $orderWhere .= " OR ov_mall_name = '카카오'";
        $checkNum = 1;
    }
    if (in_array('카카오메이커스', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '카카오메이커스'";
        else $orderWhere .= " OR ov_mall_name = '카카오메이커스'";
        $checkNum = 1;
    }
    if (in_array('스마트스토어', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '스마트스토어'";
        else $orderWhere .= " OR ov_mall_name = '스마트스토어'";
        $checkNum = 1;
    }
    if (in_array('카카오톡스토어', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '카카오톡스토어'";
        else $orderWhere .= " OR ov_mall_name = '카카오톡스토어'";
        $checkNum = 1;
    }
    if (in_array('카카오선물하기', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '카카오선물하기'";
        else $orderWhere .= " OR ov_mall_name = '카카오선물하기'";
        $checkNum = 1;
    }
    if (in_array('집꾸미기(3)', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '집꾸미기(3)'";
        else $orderWhere .= " OR ov_mall_name = '집꾸미기(3)'";
        $checkNum = 1;
    }
    if (in_array('SSF SHOP', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = 'SSF SHOP'";
        else $orderWhere .= " OR ov_mall_name = 'SSF SHOP'";
        $checkNum = 1;
    }
    if (in_array('텐바이텐', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '텐바이텐'";
        else $orderWhere .= " OR ov_mall_name = '텐바이텐'";
        $checkNum = 1;
    }
    if (in_array('현대리바트(신)', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '현대리바트(신)'";
        else $orderWhere .= " OR ov_mall_name = '현대리바트(신)'";
        $checkNum = 1;
    }
    if (in_array('이랜드몰', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '이랜드몰'";
        else $orderWhere .= " OR ov_mall_name = '이랜드몰'";
        $checkNum = 1;
    }
    if (in_array('브랜디', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '브랜디'";
        else $orderWhere .= " OR ov_mall_name = '브랜디'";
        $checkNum = 1;
    }
    if (in_array('한섬_EQL', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '한섬_EQL'";
        else $orderWhere .= " OR ov_mall_name = '한섬_EQL'";
        $checkNum = 1;
    }
    if (in_array('코오롱FNC', $mall_name_case)) {
        if($checkNum ==0) $orderWhere .= " AND (ov_mall_name = '코오롱FNC'";
        else $orderWhere .= " OR ov_mall_name = '코오롱FNC'";
        $checkNum = 1;
    }
    if($checkNum ==1) $orderWhere .= ")";
}

$degressCheck = false;
$degressWhere = '';
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
            $degressCheck = true;
        }
        $fr_sc_it_time .= '00';
        $to_sc_it_time .= '24';
        if ($fr_sc_it_time && $to_sc_it_time) {
            $orderWhere .= " AND receive_date BETWEEN '$fr_sc_it_time' AND '$to_sc_it_time' ";
            $degressWhere = " WHERE receive_date BETWEEN '$fr_sc_it_time' AND '$to_sc_it_time' ";
        }
    }

} else {
    $toDate = date("Ymd");
    $orderWhere .= " AND receive_date LIKE '$toDate%'"; 
    $sc_it_time = date("Y-m-d").' ~ '.date("Y-m-d");
    $degressCheck = true;
    $degressWhere = " WHERE receive_date LIKE '$toDate%'";
}

$degress_sql = "SELECT ov_collection_degress FROM sabang_lt_order_view $degressWhere GROUP BY ov_collection_degress";
$degress_result = sql_query($degress_sql);

//차수
if ($degress > 0) {
    if ($degress == '') {
    }else{
        $orderWhere .= " AND (ov_collection_degress = $degress)";
    }
}else{

}
if ($return && $return != '') $orderWhere=" AND ov_IDX =$return ";

// echo '$orderWhere : '.$orderWhere;
$totalSql = "SELECT count(*) AS CNT FROM sabang_lt_order_view WHERE sub_slov_id = 0 $orderWhere";


// $sql = " select count(od_id) as cnt " . $sql_common;
$countRow = sql_fetch($totalSql);
$total_count = $countRow['CNT'];
// echo '<br>총건수 : '.$total_count.'<br>';
if ($outputCount < 1 || !$outputCount) {
	$outputCount = 200;
}
$rows = $outputCount; 
// $total_page  = ceil($total_count / $outputCount);
// if ($page < 1 || !$page) {
// 	$page = 1;
// }
// $from_record = ($page - 1) * $outputCount;


$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산

if ($total_page < 2 || empty($page)) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$listSql = "SELECT * FROM sabang_lt_order_view WHERE sub_slov_id = 0 $orderWhere ORDER BY ov_idx ASC, ov_mall_id DESC,  ov_order_id DESC, ov_od_time DESC, ov_ct_id ASC, slov_id DESC LIMIT $from_record, $outputCount";
$listQuery = sql_query($listSql);



$excel_sql = "SELECT * FROM sabang_lt_order_view WHERE sub_slov_id = 0 $orderWhere ORDER BY ov_idx ASC, ov_mall_id DESC,  ov_order_id DESC, ov_od_time DESC, ov_ct_id ASC, slov_id DESC LIMIT $from_record, $outputCount";

if (substr_count($sql, "limit")) {
	$sqls = explode('limit', $excel_sql);
	$excel_sql = $sqls[0];
}
// SELECT * FROM sabang_lt_order_view WHERE samjin_link_check = 1  ORDER BY receive_date DESC LIMIT 0, 200
// ??? ??

// dd($excel_sql);
$headers = array('NO','주문상태','제휴사','거래처','주문일자','쇼핑몰주문번호','사방넷주문번호','주문자정보','자체상품코드','사방넷상품코드','상품명','삼진품목명','삼진코드','사방넷수량','주문수량','옵션명 (원본)','옵션명 (수정)','주문금액','결제금액','물류처','택배사','재고(경민)','재고(어시스트)','물류상태','SMS발송여부','색상','사이즈','주문자연락처','수령자','수령자연락처','우편번호','주소','운송장');
$bodys = array('NO','ov_order_status','ov_mall_name','ov_mall_id','ov_od_time','ov_order_id','ov_IDX','ov_order_name','ov_mall_code','ov_sabang_code','ov_it_name','ov_samjin_name','ov_samjin_code','ov_qty','ov_qty_form','ov_options','ov_options_modify','ov_total_cost','ov_pay_cost','ov_dpartner','ov_delivery_company','ov_stock1','ov_stock2','ov_distribution_status','ov_sms_check','ov_color','ov_size','ov_order_hp','ov_receive_name','ov_receive_hp','ov_receive_zip','ov_receive_addr','order_invoice');
// $summaries = array('ct_qty', 'opt_price', 'send_cost');
//  상품명부터 뽑아보자 !!
$enc = new str_encrypt();

$excel_sql = $enc->encrypt($excel_sql);
$headers = $enc->encrypt(json_encode_raw($headers));
$bodys = $enc->encrypt(json_encode_raw($bodys));
// $summaries = $enc->encrypt(json_encode_raw($summaries));


$qstr2= "ov_search_type=".$ov_search_type."&amp;ov_search_keyword=".$ov_search_keyword."&amp;ov_dpartner_case=".$ov_dpartner_case."&amp;ov_order_status_case=".$ov_order_status_case."&amp;ov_distribution_status_case=".$ov_distribution_status_case."&amp;ov_mapping_status_case=".$ov_mapping_status_case."&amp;ov_sms_check_case=".$ov_sms_check_case."&amp;mall_name_case=".$mall_name_case."&amp;sc_it_time=".$sc_it_time."&amp;outputCount=".$outputCount."&amp;degress=".$degress."&amp;page=".$page;


?>

<!-- 검색 따로만들래 !! -->
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
            <form id="orderMainTable" name="frmorderlist" class="local_sch01 local_sch" onsubmit="$('#page').val('1');" method="post" style="text-align: left !important;">
            	<div class="tbl_frm01 tbl_wrap">
            		<table>
            			<colgroup>
            				<col class="grid_4">
            				<col>
            				<col class="grid_3">
            			</colgroup>
            			<tr>
            				<th scope="row">검색분류</th>
            				<td colspan="2">

            						<select name="ov_search_type" id="ov_search_type" style="width: 120px;">
                                        <option value="ov_it_name" <?= get_selected($ov_search_type, 'ov_it_name'); ?>>상품명</option>
                                        
                                        <option value="ov_samjin_name" <?= get_selected($ov_search_type, 'ov_samjin_name'); ?>>삼진품목명</option>
                                        <option value="ov_mall_code" <?= get_selected($ov_search_type, 'ov_mall_code'); ?>>자체상품코드</option>
                                        <option value="ov_samjin_code" <?= get_selected($ov_search_type, 'ov_samjin_code'); ?>>삼진코드</option>
                                        <option value="ov_order_id" <?= get_selected($ov_search_type, 'ov_order_id'); ?>>쇼핑몰주문번호</option>
                                        <option value="ov_IDX" <?= get_selected($ov_search_type, 'ov_IDX'); ?>>사방넷주문번호</option>
                                        <option value="ov_order_name" <?= get_selected($ov_search_type, 'ov_order_name'); ?>>주문자명</option>
                                        <option value="ov_invoice_no" <?= get_selected($ov_search_type, 'ov_invoice_no'); ?>>송장번호</option>
                                        <option value="ov_tel" <?= get_selected($ov_search_type, 'ov_tel'); ?>>전화번호</option>
                                        <option value="ov_receive_name" <?= get_selected($ov_search_type, 'ov_receive_name'); ?>>수취인명</option>

            						</select>


                                    <input type="text" style="width : 90%;" name="ov_search_keyword" value="<?= $ov_search_keyword; ?>" id="ov_search_keyword" onkeydown = "serachEnter(event)" class="frm_input" autocomplete="off">

            				</td>
            			</tr>
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
            					</div>
            				</td>
            			</tr>
            			<tr>
            				<th scope="row">주문상태</th>
            				<td colspan="2">
            					<div class="col-lg-1 col-md-6 col-sm-12 col-xs-12">
                                    <input onclick='allCheck("ov_order_status_case")' type="checkbox" name="ov_order_status_case[]" value="전체" id="ov_order_status_case01" <?php if(in_array('전체', $ov_order_status_case) || !$ov_order_status_case) echo "checked"; ?>>
            						<label for="ov_order_status_case01">전체</label>
            						<input type="checkbox" name="ov_order_status_case[]" value="신규주문" id="ov_order_status_case02" <?php if(in_array('전체', $ov_order_status_case) || in_array('신규주문', $ov_order_status_case) || !$ov_order_status_case) echo "checked"; ?>>
            						<label for="ov_order_status_case02">신규주문</label>
            						<input type="checkbox" name="ov_order_status_case[]" value="주문확인" id="ov_order_status_case03" <?php if(in_array('전체', $ov_order_status_case) || in_array('주문확인', $ov_order_status_case) || !$ov_order_status_case) echo "checked"; ?>>
                                    <label for="ov_order_status_case03">주문확인</label>
                                    <input type="checkbox" name="ov_order_status_case[]" value="출고전취소" id="ov_order_status_case04" <?php if(in_array('전체', $ov_order_status_case) || in_array('출고전취소', $ov_order_status_case) || !$ov_order_status_case) echo "checked"; ?>>
            						<label for="ov_order_status_case04">출고전취소</label>
                                    <input type="checkbox" name="ov_order_status_case[]" value="반품접수" id="ov_order_status_case05" <?php if(in_array('전체', $ov_order_status_case) || in_array('반품접수', $ov_order_status_case) || !$ov_order_status_case) echo "checked"; ?>>
            						<label for="ov_order_status_case05">반품접수</label>
                                    <input type="checkbox" name="ov_order_status_case[]" value="반품완료" id="ov_order_status_case06" <?php if(in_array('전체', $ov_order_status_case) || in_array('반품완료', $ov_order_status_case) || !$ov_order_status_case) echo "checked"; ?>>
            						<label for="ov_order_status_case06">반품완료</label>
                                    <input type="checkbox" name="ov_order_status_case[]" value="입고완료" id="ov_order_status_case07" <?php if(in_array('전체', $ov_order_status_case) || in_array('입고완료', $ov_order_status_case) || !$ov_order_status_case) echo "checked"; ?>>
            						<label for="ov_order_status_case07">입고완료</label>
                                    <input type="checkbox" name="ov_order_status_case[]" value="환불완료" id="ov_order_status_case08" <?php if(in_array('전체', $ov_order_status_case) || in_array('환불완료', $ov_order_status_case) || !$ov_order_status_case) echo "checked"; ?>>
            						<label for="ov_order_status_case08">환불완료</label>
                                    <input type="checkbox" name="ov_order_status_case[]" value="품절취소" id="ov_order_status_case09" <?php if(in_array('전체', $ov_order_status_case) || in_array('품절취소', $ov_order_status_case) || !$ov_order_status_case) echo "checked"; ?>>
            						<label for="ov_order_status_case09">품절취소</label>
            					</div>
            				</td>
            			</tr>
            			<tr>
            				<th scope="row">물류상태</th>
            				<td colspan="2">
            					<div class="col-lg-1 col-md-6 col-sm-12 col-xs-12">
            						<input onclick='allCheck("ov_distribution_status_case")' type="checkbox" name="ov_distribution_status_case[]" value="전체" id="ov_distribution_status_case01" <?php if(in_array('전체', $ov_distribution_status_case) || !$ov_distribution_status_case) echo "checked"; ?>>
                                    <label for="ov_distribution_status_case01">전체</label>
                                    <input type="checkbox" name="ov_distribution_status_case[]" value="정상" id="ov_distribution_status_case02" <?php if(in_array('전체', $ov_distribution_status_case) || in_array('정상', $ov_distribution_status_case) || !$ov_distribution_status_case) echo "checked"; ?>>
            						<label for="ov_distribution_status_case02">정상</label>
            						<input type="checkbox" name="ov_distribution_status_case[]" value="출고확정" id="ov_distribution_status_case03" <?php if(in_array('전체', $ov_distribution_status_case) || in_array('출고확정', $ov_distribution_status_case) || !$ov_distribution_status_case) echo "checked"; ?>>
            						<label for="ov_distribution_status_case03">출고확정</label>
            						<input type="checkbox" name="ov_distribution_status_case[]" value="품절" id="ov_distribution_status_case04" <?php if(in_array('전체', $ov_distribution_status_case) || in_array('품절', $ov_distribution_status_case) || !$ov_distribution_status_case) echo "checked"; ?>>
                                    <label for="ov_distribution_status_case04">품절</label>
                                    <input type="checkbox" name="ov_distribution_status_case[]" value="물류품절" id="ov_distribution_status_case05" <?php if(in_array('전체', $ov_distribution_status_case) || in_array('물류품절', $ov_distribution_status_case) || !$ov_distribution_status_case) echo "checked"; ?>>
                                    <label for="ov_distribution_status_case05">물류품절</label>
                                    <input type="checkbox" name="ov_distribution_status_case[]" value="출고보류" id="ov_distribution_status_case06" <?php if(in_array('전체', $ov_distribution_status_case) || in_array('출고보류', $ov_distribution_status_case) || !$ov_distribution_status_case) echo "checked"; ?>>
            						<label for="ov_distribution_status_case06">출고보류</label>
            					</div>
            				</td>
                        </tr>
                        <tr>
            				<th scope="row">매핑여부</th>
            				<td colspan="2">
            					<div class="col-lg-1 col-md-6 col-sm-12 col-xs-12">
            						<input onclick='allCheck("ov_mapping_status_case")'type="checkbox" name="ov_mapping_status_case[]" value="전체" id="ov_mapping_status_case01" <?php if(in_array('전체', $ov_mapping_status_case) || !$ov_mapping_status_case) echo "checked"; ?>>
            						<label for="ov_mapping_status_case01">전체</label>
            						<input type="checkbox" name="ov_mapping_status_case[]" value="오매핑" id="ov_mapping_status_case02" <?php if(in_array('전체', $ov_mapping_status_case) || in_array('오매핑', $ov_mapping_status_case) || !$ov_mapping_status_case) echo "checked"; ?>>
            						<label for="ov_mapping_status_case02">오매핑</label>
            						<input type="checkbox" name="ov_mapping_status_case[]" value="매핑" id="ov_mapping_status_case03" <?php if(in_array('전체', $ov_mapping_status_case) || in_array('매핑', $ov_mapping_status_case) || !$ov_mapping_status_case) echo "checked"; ?>>
                                    <label for="ov_mapping_status_case03">매핑</label>
            					</div>
            				</td>
            			</tr>
            			<tr>
            				<th scope="row">SMS 발송여부</th>
            				<td colspan="2">
            					<div class="col-lg-1 col-md-6 col-sm-12 col-xs-12">
            						<input onclick='allCheck("ov_sms_check_case")' type="checkbox" name="ov_sms_check_case[]" value="전체" id="ov_sms_check_case01" <?php if(in_array('전체', $ov_sms_check_case) || !$ov_sms_check_case) echo "checked"; ?>>
            						<label for="ov_sms_check_case01">전체</label>
            						<input type="checkbox" name="ov_sms_check_case[]" value='발송' id="ov_sms_check_case02" <?php if(in_array('전체', $ov_sms_check_case) || in_array('발송', $ov_sms_check_case) || !$ov_sms_check_case) echo "checked"; ?>>
            						<label for="ov_sms_check_case02">발송</label>
            						<input type="checkbox" name="ov_sms_check_case[]" value='미발송' id="ov_sms_check_case03" <?php if(in_array('전체', $ov_sms_check_case) || in_array('미발송', $ov_sms_check_case) || !$ov_sms_check_case) echo "checked"; ?>>
            						<label for="ov_sms_check_case03">미발송</label>
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
                                    <label for="mall_name_case36">코오롱FNC</label>
            					</div>
            				</td>
            			</tr>

                        <?
                        if($degressCheck) : 
                        ?>
                        <tr>
                            <th scope="row">차수</th>
                            <td colspan="2">
                            <div class="col-lg-1 col-md-6 col-sm-12 col-xs-12">
                                <select name="degress" id="degress" style="width: 110px;">
                                    <option value="" <?= get_selected($degress, ""); ?>>전체</option>
                                    <?if(!empty($degress_result)):?>
                                        <?for($cdi = 0 ; $cd = sql_fetch_array($degress_result); $cdi++ ):?>
                                            <option value="<?=$cd['ov_collection_degress']?>" <?= get_selected($degress, $cd['ov_collection_degress']);?>> <?=$cd['ov_collection_degress']?></option>
                                        <?endfor?>
                                    <?endif?>
                                </select>
                            </div>
                            </td>
                        </tr>
                        <?endif?>
                        <tr>
            				<th scope="row">보기</th>
            				<td colspan="2">
            					<div class="col-lg-1 col-md-6 col-sm-12 col-xs-12">
                                    <select name="outputCount" id="outputCount" style="width: 110px;">
                                        <option value=100 <?= get_selected($outputCount, 100); ?>>100개</option>
                                        <option value=200 <?= get_selected($outputCount, 200); ?>>200개</option>
                                        <option value=300 <?= get_selected($outputCount, 300); ?>>300개</option>
                                        <option value=400 <?= get_selected($outputCount, 400); ?>>400개</option>
                                        <option value=500 <?= get_selected($outputCount, 500); ?>>500개</option>
                                        <option value=1000 <?= get_selected($outputCount, 1000); ?>>1000개</option>
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
                    <input type="button" value="출고보류" class="btn btn_02" onclick="orderTransfer('hold')">
                    <input type="button" value="매핑처리" class="btn btn_02" onclick="orderTransfer('mapping')">
                    <input type="button" value="정상처리" class="btn btn_02" onclick="orderTransfer('etc')">
                    <input type="button" value="출고확정" class="btn btn_03" onclick="orderTransfer('decide')">
                    <? if ($is_admin == 'super' || $member['mb_id'] == 'jeongwseong' || $member['mb_id'] == 'ny0606' || $member['mb_id'] == 'enskwkdsla12') { ?>
                        <input type="button" value="주문수집" class="btn btn_02" onclick="orderTransfer('collection')">
                    <?}?>
                    <input type="button" value="수기취소" class="btn btn_02" onclick="orderTransfer('handCancel')">
                    <input type="button" value="품절SMS발송" class="btn btn_02" onclick="orderTransfer('smsView')">
                    <? if ($is_admin == 'super' || $member['mb_id'] == 'enskwkdsla12') { ?>
                    <input type="button" value="품절취소" class="btn btn_02" onclick="orderTransfer('soldoutCancel')">
                    <?}?>
	            </div>
                <div style="float: right">
                <input type='file' name ="upload_excel" id='upload_excel' />
                    <!-- <div class="btn btn_02" style="height: 30px;" id="upload_excel_return_btn">엑셀반품업로드</div> -->
                    <a  class="btn btn_02" href="./excel_sample_etc_20201228.xls">수기주문양식</a>
                    <input type='file' name ="upload_excel" id='upload_excel' />
                    <div class="btn btn_02" style="height: 30px;" id="upload_excel_btn">엑셀주문업로드
                    <!-- <input type="button" value="엑셀업로드" class="btn btn_02" onclick="etc_mall_excel_upload()"> -->
	            </div>
            </div>

    
        </div>
    <!-- </div> -->
<!-- </div> -->




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
                <th>주문상태</th>
                <th>제휴사<br>거래처</th>
                <th>주문일자</th>
                <th>쇼핑몰주문번호<br>사방넷주문번호</th>
                <th>주문자정보</th>
                <th>자체상품코드<br>사방넷상품코드</th>
                <th>상품명</th>
                <th>옵션명 (원본)</th>
                <th>삼진품목명</th>
                <th>색상</th>
                <th>사이즈</th>
                <th>옵션명 (수정)</th>
                <th>삼진코드</th>
                <th>사방넷수량</th>
                <th>주문금액<br>결제금액</th>
                <th>물류처</th>
                <th>택배사</th>
                <th>재고</th>
                <th>물류상태</th>
                <th>SMS발송여부</th>
                <th>메모</th>
            </tr>
        </thead>
        <tbody id="revenue-status">
            
        <?
            for ($k = 0; $row = sql_fetch_array($listQuery); $k++) {  
        ?>
            <tr style="background-color: <?php if(strpos($row['ov_it_name'],'옥의티') !== false) echo "#D4F4FA"; else if(strpos($row['ov_it_name'],'리퍼') !== false && $row['ov_mall_id'] == '19963') echo "#D4F4FA"; else if($row['ov_options_modify'] == '') echo "#B7F0B1"; else if($row['ov_distribution_status'] == '출고보류') echo "#E8D9FF";  ?> <?=$row['order_memo'] ? '#fffbe5' : ''?> ;">  
                <td>
                    <input type="checkbox" name="chk[]" value="<?= $row['slov_id'] ?>" id="chk_<?= $k ?>" ov_order_status ="<?= $row['ov_order_status'] ?>" ov_distribution_status ="<?= $row['ov_distribution_status'] ?>" ov_options_modify = "<?= $row['ov_options_modify'] ?>" ov_mall_id="<?= $row['ov_mall_id'] ?>" ov_sms_check="<?= $row['ov_sms_check'] ?>">
                </td>
                <td><? echo $row['ov_order_status'] ?></td>
                <td><? echo $row['ov_mall_name'] ?><br>
                    <? echo $row['ov_mall_id'] ?>
                </td>
                <td><?=substr($row['ov_od_time'],0,4)?>-<?=substr($row['ov_od_time'],4,2)?>-<?=substr($row['ov_od_time'],6,2)?></td>
                <td><? echo $row['ov_order_id'] ?><br>
                    <? echo $row['ov_IDX'] ?>
                </td>
                <td style="cursor:pointer" onclick="showDetailInfo(<?php echo $row['slov_id'] ?>)"><? echo $row['ov_order_name'] ?></td>
                <td><? echo $row['ov_mall_code'] ?><br>
                    <? echo $row['ov_sabang_code'] ?>
                </td>
                <td><? echo iconv_substr($row['ov_it_name'], 0, 28, "utf-8") ?><br>
                    <? echo iconv_substr($row['ov_it_name'], 28, 100, "utf-8") ?>
                </td>
                <td>
                    <!-- <? echo iconv_substr($row['ov_options'], 0, 28, "utf-8") ?><br>
                    <? echo iconv_substr($row['ov_options'], 28, 100, "utf-8") ?> -->
                    
                    
                    <? if(strpos($row['ov_options'] , chr(10)) === false){
                        echo iconv_substr($row['ov_options'], 0, 28, "utf-8");
                        echo '<br>';
                        echo iconv_substr($row['ov_options'], 28, 100, "utf-8");


                    }else {
                        echo nl2br($row['ov_options']);
                    }   ?>
                    
                </td>
                <td>
                    <? 
                        if ($row['ov_set_check'] == '002') {
                            $sqlsn = "SELECT ov_samjin_name FROM sabang_lt_order_view WHERE slov_id = '{$row['slov_id']}' OR sub_slov_id = '{$row['slov_id']}' ORDER BY slov_id ASC"; 
                            $res_sqlsn = sql_query($sqlsn);
                            for ($sn = 0; $snRow = sql_fetch_array($res_sqlsn); $sn++) {  
                                echo $snRow['ov_samjin_name'];
                    ?>
                            <br>
                    <?      }
                        } else {
                            echo $row['ov_samjin_name'];
                        ?>       
                    <? }
                ?>
                </td>
                <td>
                    <? 
                        if ($row['ov_set_check'] == '002') {
                            $sqloc = "SELECT ov_color FROM sabang_lt_order_view WHERE slov_id = '{$row['slov_id']}' OR sub_slov_id = '{$row['slov_id']}' ORDER BY slov_id ASC"; 
                            $res_sqloc = sql_query($sqloc);
                            for ($oc = 0; $ocRow = sql_fetch_array($res_sqloc); $oc++) {  
                                echo $ocRow['ov_color'];
                    ?>
                            <br>
                    <?      }
                        } else {
                            echo $row['ov_color'];
                        ?>       
                    <? }
                ?>
                </td>
                <td>
                    <? 
                        if ($row['ov_set_check'] == '002') {
                            $sqlos = "SELECT ov_size FROM sabang_lt_order_view WHERE slov_id = '{$row['slov_id']}' OR sub_slov_id = '{$row['slov_id']}' ORDER BY slov_id ASC"; 
                            $res_sqlos = sql_query($sqlos);
                            for ($os = 0; $osRow = sql_fetch_array($res_sqlos); $os++) {  
                                echo $osRow['ov_size'];
                    ?>
                            <br>
                    <?      }
                        } else {
                            echo $row['ov_size'];
                        ?>       
                    <? }
                ?>
                </td>

                <td>
                    <? 
                        if ($row['ov_set_check'] == '002') {
                            // $ov_order_id = $row['ov_order_id'];
                            // $ov_mall_id = $row['ov_mall_id'];
                            $selSql = "SELECT ov_options_modify FROM sabang_lt_order_view WHERE slov_id = '{$row['slov_id']}' OR sub_slov_id = '{$row['slov_id']}' ORDER BY slov_id ASC"; 
                            $selQuery = sql_query($selSql);
                            for ($q = 0; $opRow = sql_fetch_array($selQuery); $q++) {  
                                $num = $q+1;
                                $ov_options_modify= '';
                                $ov_options_modify .= $num.'.';
                                $ov_options_modify .= '('.$opRow['ov_options_modify'].')';
                                ?>
                                <!-- <input size="30" type="text" name="optionsModi[]" id="optionsModi_<?= $row['slov_id'] ?>" value = <? echo $ov_options_modify ?> <?php echo ($ov_options_modify != '')?'disabled':''; ?>  > -->
                                <input size="30" type="text" name="optionsModi[]" id="optionsModi_<?= $row['slov_id'] ?>" value = <? echo $ov_options_modify ?>>
                                <br>
                            <? }
                        } else {
                                if ($row['ov_options_modify']!='') {
                                    $ov_options_modify = '1.('.$row['ov_options_modify'].')';
                                } else $ov_options_modify = '';
                             ?>
                                <!-- <input size="30" type="text" name="optionsModi[]" id="optionsModi_<?= $row['slov_id'] ?>" value = <? echo $ov_options_modify ?> <?php echo ($ov_options_modify != '')?'disabled':''; ?>> -->
                                <input size="30" type="text" name="optionsModi[]" id="optionsModi_<?= $row['slov_id'] ?>" value = <? echo $ov_options_modify ?>>
                       <? }
                    ?>

                </td>
                <td>
                    <? 
                        if ($row['ov_set_check'] == '002') {
                            $sqlsc = "SELECT ov_samjin_code FROM sabang_lt_order_view WHERE slov_id = '{$row['slov_id']}' OR sub_slov_id = '{$row['slov_id']}' ORDER BY slov_id ASC"; 
                            $res_sqlsc = sql_query($sqlsc);
                            for ($sc = 0; $scRow = sql_fetch_array($res_sqlsc); $sc++) {  
                                echo $scRow['ov_samjin_code'];
                    ?>
                            <br>
                    <?      }
                        } else {
                            echo $row['ov_samjin_code'];
                        ?>       
                    <? } ?>
                </td>
                <td>
                    <? echo $row['ov_qty'] ?>
                </td>
                
                <td><? echo number_format($row['ov_total_cost']) ?><br>
                    <? echo number_format($row['ov_pay_cost']) ?>
                </td>
                <td>
                    <?
                        if ($row['ov_set_check'] == '002') {
                            $selSql = "SELECT ov_dpartner,slov_id FROM sabang_lt_order_view WHERE slov_id = '{$row['slov_id']}' OR sub_slov_id = '{$row['slov_id']}' ORDER BY slov_id ASC"; 
                            $selQuery = sql_query($selSql);
                            for ($dp = 0; $dpRow = sql_fetch_array($selQuery); $dq++) {  
                                $ov_dpartner = $dpRow['ov_dpartner'];
                                $slov_idDp = $dpRow['slov_id'];

                                // 여기서 조건하나 더 걸자
                                if ($row['ov_order_status'] == '주문확인') { ?>
                                <select name="ov_dpartner_type[]" id="ov_dpartner_type_<?= $slov_idDp ?>" style="width: 100px; height:22px;">
                                    <option value="<?=$ov_dpartner ?>"><? echo $ov_dpartner; ?></option>
            		            </select>
                                <br>
                                <?} else {

                    ?>
                                <select name="ov_dpartner_type[]" id="ov_dpartner_type_<?= $slov_idDp ?>" style="width: 100px; height:22px;" onchange="chageDpartner('<? echo $ov_dpartner; ?>','<? echo $slov_idDp ?>')">
                                    <? if ($ov_dpartner == '' || $ov_dpartner == NULL) { ?>
                                        <option value="" <?= get_selected($ov_dpartner, ''); ?>></option>
                                    <? } ?>    
                                    <option value="경민실업" <?= get_selected($ov_dpartner, '경민실업'); ?>>경민실업</option>
                                    <option value="어시스트" <?= get_selected($ov_dpartner, '어시스트'); ?>>어시스트</option>
                                    <option value="본사" <?= get_selected($ov_dpartner, '본사'); ?>>본사</option>
            		            </select>
                                <br>
                            <? }}
                        } else { 
                            if ($row['ov_order_status'] == '주문확인') { ?>
                                <select name="ov_dpartner_type[]" id="ov_dpartner_type_<?= $row['slov_id'] ?>" style="width: 100px; height:22px;">
                                <option value="<?=$row['ov_dpartner'] ?>"><? echo $row['ov_dpartner']; ?></option>
                                </select>
                            <?} else {

                             ?>
                                <select name="ov_dpartner_type[]" id="ov_dpartner_type_<?= $row['slov_id'] ?>" style="width: 100px; height:22px;" onchange="chageDpartner('<? echo $row['ov_dpartner']; ?>','<? echo $row['slov_id'] ?>')">
                                    <? if ($row['ov_dpartner'] == '' || $row['ov_dpartner'] == NULL) { ?>
                                        <option value="" <?= get_selected($row['ov_dpartner'], ''); ?>></option>
                                    <? } ?> 
                                    <option value="경민실업" <?= get_selected($row['ov_dpartner'], '경민실업'); ?>>경민실업</option>
                                    <option value="어시스트" <?= get_selected($row['ov_dpartner'], '어시스트'); ?>>어시스트</option>
                                    <option value="본사" <?= get_selected($row['ov_dpartner'], '본사'); ?>>본사</option>
            		            </select>
                        <? }}
                    ?>
                </td>
                <td>
                    <?
                        if ($row['ov_set_check'] == '002') {
                            $selSql = "SELECT ov_delivery_company_code, slov_id, ov_delivery_company FROM sabang_lt_order_view WHERE slov_id = '{$row['slov_id']}' OR sub_slov_id = '{$row['slov_id']}' ORDER BY slov_id ASC"; 
                            $selQuery = sql_query($selSql);
                            for ($dc = 0; $dcRow = sql_fetch_array($selQuery); $dc++) {  
                                $ov_delivery_company_code = $dcRow['ov_delivery_company_code'];
                                $ov_delivery_company = $dcRow['ov_delivery_company'];
                                $slov_idDC = $dcRow['slov_id'];
                                if ($row['ov_order_status'] == '주문확인') { ?>
                                     <select name="ov_delivery_type[]" id="ov_delivery_type_<?= $slov_idDC ?>" style="width: 120px; height:22px;">
                                        <option value="<?=$ov_delivery_company_code ?>"><? echo $ov_delivery_company; ?></option>
                                    </select>
                                    <br>
                                    <?} else { 
                                
                    ?>
                                    <div>
                                    <select name="ov_delivery_type[]" id="ov_delivery_type_<?= $slov_idDC ?>" style="width: 120px; height:22px;">
                                        <option value="003" <?= get_selected($ov_delivery_company_code, '003'); ?>>CJ대한통운</option>
                                        <option value="002" <?= get_selected($ov_delivery_company_code, '002'); ?>>롯데택배</option> 
                                        <option value="001" <?= get_selected($ov_delivery_company_code, '001'); ?>>대한통운</option>
                                        <option value="004" <?= get_selected($ov_delivery_company_code, '004'); ?>>한진택배</option>
                                        <option value="005" <?= get_selected($ov_delivery_company_code, '005'); ?>>KGB택배</option>
                                        <option value="006" <?= get_selected($ov_delivery_company_code, '006'); ?>>동부택배</option>
                                        <option value="007" <?= get_selected($ov_delivery_company_code, '007'); ?>>로젠택배</option>
                                        <option value="008" <?= get_selected($ov_delivery_company_code, '008'); ?>>옐로우캡택배</option>
                                        <option value="009" <?= get_selected($ov_delivery_company_code, '009'); ?>>우체국택배</option>
                                        <option value="010" <?= get_selected($ov_delivery_company_code, '010'); ?>>하나로택배</option>
                                        <option value="013" <?= get_selected($ov_delivery_company_code, '013'); ?>>경동택배</option>
                                        <option value="014" <?= get_selected($ov_delivery_company_code, '014'); ?>>일양로직스</option>
                                        <option value="016" <?= get_selected($ov_delivery_company_code, '016'); ?>>천일백배</option>
                                        <option value="017" <?= get_selected($ov_delivery_company_code, '017'); ?>>동부익스프레스</option>
                                    </select>
                                    </div>
                                    <div name ="saveCompanyDiv_<?= $slov_idDC ?>">
                                        <button name ="saveCompany[]" id="saveCompany_<?= $slov_idDC ?>" onclick="orderTransfer('companySave','<?= $slov_idDC ?>')">저장</button>
                                    </div>
                            <?} }
                        } else {
                            if ($row['ov_order_status'] == '주문확인') { ?>
                                <select name="ov_delivery_type[]" id="ov_delivery_type_<?= $row['slov_id'] ?>" style="width: 120px; height:22px;">
                                <option value="<?=$row['ov_delivery_company_code'] ?>"><? echo $row['ov_delivery_company']; ?></option>
                                </select>
                            <?} else {
                             ?>
                                <select name="ov_delivery_type[]" id="ov_delivery_type_<?= $row['slov_id'] ?>" style="width: 120px; height:22px;">
                                    <option value="003" <?= get_selected($row['ov_delivery_company_code'], '003'); ?>>CJ대한통운</option>
                                    <option value="002" <?= get_selected($row['ov_delivery_company_code'], '002'); ?>>롯데택배</option> 
                                    <option value="001" <?= get_selected($row['ov_delivery_company_code'], '001'); ?>>대한통운</option>
                                    <option value="004" <?= get_selected($row['ov_delivery_company_code'], '004'); ?>>한진택배</option>
                                    <option value="005" <?= get_selected($row['ov_delivery_company_code'], '005'); ?>>KGB택배</option>
                                    <option value="006" <?= get_selected($row['ov_delivery_company_code'], '006'); ?>>동부택배</option>
                                    <option value="007" <?= get_selected($row['ov_delivery_company_code'], '007'); ?>>로젠택배</option>
                                    <option value="008" <?= get_selected($row['ov_delivery_company_code'], '008'); ?>>옐로우캡택배</option>
                                    <option value="009" <?= get_selected($row['ov_delivery_company_code'], '009'); ?>>우체국택배</option>
                                    <option value="010" <?= get_selected($row['ov_delivery_company_code'], '010'); ?>>하나로택배</option>
                                    <option value="013" <?= get_selected($row['ov_delivery_company_code'], '013'); ?>>경동택배</option>
                                    <option value="014" <?= get_selected($row['ov_delivery_company_code'], '014'); ?>>일양로직스</option>
                                    <option value="016" <?= get_selected($row['ov_delivery_company_code'], '016'); ?>>천일백배</option>
                                    <option value="017" <?= get_selected($row['ov_delivery_company_code'], '017'); ?>>동부익스프레스</option>
                                </select>
                                <div name ="saveCompanyDiv_<?= $row['slov_id'] ?>">
                                    <button name ="saveCompany[]" id="saveCompany_<?= $row['slov_id'] ?>" onclick="orderTransfer('companySave','<?= $row['slov_id'] ?>')">저장</button>
                                </div>
                        <? }}
                    ?>
                    <!-- <br> -->
                    <!-- <button name ="saveCompany[]" id="saveCompany_<?= $row['slov_id'] ?>" style="margin-top: 10px;" onclick="orderTransfer('companySave','<?= $row['slov_id'] ?>')">저장</button>
                    <input type="text" name="delivery_company[]" id="delivery_company_<?= $row['slov_id'] ?>" value = <? echo $row['ov_delivery_company'] ?>> -->
                </td>
                <td>
                    <? 
                        if ($row['ov_set_check'] == '002') {
                            $selSql = "SELECT ov_stock1,ov_stock2,ov_stock3 FROM sabang_lt_order_view WHERE slov_id = '{$row['slov_id']}' OR sub_slov_id = '{$row['slov_id']}' ORDER BY slov_id ASC"; 
                            $selQuery = sql_query($selSql);
                            for ($os = 0; $osRow = sql_fetch_array($selQuery); $os++) {  
                                $ov_stock1Os = $osRow['ov_stock1'];
                                $ov_stock2Os = $osRow['ov_stock2'];
                                $ov_stock3Os = $osRow['ov_stock3'];
                                ?>
                                <?if($ov_stock3Os > 0 ){
                                    echo '본사 : '. $ov_stock3Os. '<br>' ;
                                }else {?>
                                경민 : <? echo $ov_stock1Os ?> , 어시스트 : <? echo $ov_stock2Os ?><br>
                                <?}?>
                            <? }
                        } else {
                             ?>
                             <?if($row['ov_stock3'] > 0 ){
                                    echo '본사 : '. $row['ov_stock3'] . '<br>' ;
                            }else {?>
                                경민 : <? echo $row['ov_stock1'] ?> , 어시스트 : <? echo $row['ov_stock2'] ?>
                            <?}?>
                                
                       <? }
                    ?>
                </td>
                <td><? echo $row['ov_distribution_status'] ?></td>
                <td><? if ($row['ov_sms_check'] == 0) echo '미발송'; else echo '발송'; ?></td>
                <td title="<?=$row['order_memo']?>">
                    <?if($row['sub_slov_id'] == 0) : ?>
                        <input type="hidden" id="order_memo_<?=$row['ov_IDX']?>" value = "<?=$row['order_memo']?>">
                        <a onclick="showMemo('<?=$row['ov_IDX']?>')" title="<?=$row['order_memo']?>" style="cursor:pointer;"><?=$row['order_memo'] ? 'ⓘ' : ''?>메모</a>
                    <?endif?>
                </td>
            </tr>
        <? 
            }
        ?>
        </tbody>
    </table>
    <?= get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr2&amp;page="); ?>
</div>
</div>
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
                leftColumns: 3
            }
        });
        $("#topscroll .div1").css('width',$("#reportTb").innerWidth() +'px');

        $("#topscroll").scroll(function(){
            $(".dataTables_scrollBody").scrollLeft($("#topscroll").scrollLeft());
        });
        $(".dataTables_scrollBody").scroll(function(){
            $("#topscroll").scrollLeft($(".dataTables_scrollBody").scrollLeft());
        });

        var dpartnerSel = document.getElementsByName('ov_dpartner_type[]');
	    for (i=0; i<dpartnerSel.length; i++)
	    {
            let dpartnerName = $("#"+dpartnerSel[i].id+" option:selected").val();
            let deliveryId = dpartnerSel[i].id.substr(17,);
            let option = null;
            if (dpartnerName == '' || dpartnerName == null) {
                $('#ov_delivery_type_'+deliveryId).empty();
            } else if (dpartnerName == '경민실업') {
                option = $("<option value='003'>CJ대한통운</option>");
                $('#ov_delivery_type_'+deliveryId).empty();
                $('#ov_delivery_type_'+deliveryId).append(option);

            } else if (dpartnerName == '어시스트') {
                option = $("<option value='003'>CJ대한통운</option>");
                $('#ov_delivery_type_'+deliveryId).empty();
                $('#ov_delivery_type_'+deliveryId).append(option);
            } else {
                // let selectLen = $("#ov_delivery_type_"+deliveryId+" option").length;
                // if (selectLen != 1) {
                    // option = $(`<option value='003'>CJ택배</option>
                    // <option value='002'>롯데택배</option>
                    // <option value="001">대한통운</option>
                    // <option value="004">한진택배</option>
                    // <option value="005">KGB택배</option>
                    // <option value="006">동부택배</option>
                    // <option value="007">로젠택배</option>
                    // <option value="008">옐로우캡택배</option>
                    // <option value="009">우체국택배</option>
                    // <option value="010">하나로택배</option>
                    // <option value="013">경동택배</option>
                    // <option value="014">일양로직스</option>
                    // <option value="016">천일백배</option>
                    // <option value="017">동부익스프레스</option>
                    // `);
                    // $('#ov_delivery_type_'+deliveryId).empty();
                    // $('#ov_delivery_type_'+deliveryId).append(option);
                // }
            }
	    }
        
        $('#upload_excel').hide();
        $('#upload_excel_return_btn').on('click', function () {
            var check = confirm("엑셀로 수기 반품 업로드(카카오) 하시겠습니까? \n 반품 엑셀 파일을 다시한번 확인 바랍니다!");
            if(check){
                $('#upload_excel').click();
            }
            
        });
        $('#upload_excel_btn').on('click', function () {
            var check = confirm("엑셀로 수기 주문 업로드(카카오) 하시겠습니까? \n 주문 엑셀 파일을 다시한번 확인 바랍니다!");
            if(check){
                $('#upload_excel').click();
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
        // window.addEventListener("keydown", (e) => {
        //     if (e.keyCode == 13) {
        //         document.getElementById('orderMainTable').submit();
        //     }
        // })
    });
    function serachEnter (e){
        if (e.keyCode == 13) {
            document.getElementById('new_goods_form').submit();
        }
    }
    $(function(){
        $("[id^='saveCompany_']").hide();
        $("[name^='saveCompanyDiv']").hide();

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
            var excel_sql = "SELECT *, ov_invoice_no AS order_invoice FROM sabang_lt_order_view WHERE slov_id IN ("+excel_obj+") OR sub_slov_id IN ("+excel_obj+") ORDER BY ov_idx ASC, ov_mall_id DESC,  ov_order_id DESC, ov_od_time DESC, ov_ct_id ASC, ov_IDX DESC, slov_id ASC, sub_slov_id ASC ";
            // var excel_sql = "SELECT *, ov_invoice_no AS order_invoice FROM sabang_lt_order_view WHERE slov_id IN ("+excel_obj+") OR sub_slov_id IN ("+excel_obj+") ORDER BY ov_mall_id DESC, receive_date DESC, ov_order_id DESC, ov_ct_id ASC, sub_slov_id ASC, slov_id DESC ";
            // var excel_sql = "SELECT *, (SELECT order_invoice FROM sabang_lt_order_form WHERE mall_order_no = ov_order_id AND samjin_name = ov_samjin_name  LIMIT 1) AS order_invoice FROM sabang_lt_order_view WHERE slov_id IN ("+excel_obj+") OR sub_slov_id IN ("+excel_obj+") ORDER BY ov_mall_id DESC, receive_date DESC, ov_order_id DESC, ov_ct_id ASC, sub_slov_id ASC, slov_id DESC ";
            // var excel_sql = `SELECT sabang_lt_order_view.*, form.order_invoice 
            // FROM sabang_lt_order_view LEFT JOIN sabang_lt_order_form AS form ON form.mall_order_no = ov_order_id AND form.samjin_name = ov_samjin_name 
            // WHERE slov_id IN ("+excel_obj+") OR sub_slov_id IN ("+excel_obj+")
            // ORDER BY ov_mall_id DESC, receive_date DESC, ov_order_id DESC, ov_ct_id ASC, sub_slov_id ASC, slov_id DESC`;
            
            
            // excel_sql = "select * from sabang_lt_order_form where sno in ( "+excel_sql +" ) order by mall_id ASC";

            // return;
            // var exceldata = $('<input type="hidden" value="<?=$excel_sql?>" name="exceldata">');

            var exceldata = $('<input type="hidden" value="'+excel_sql+'" name="exceldata">');
            var headerdata = $('<input type="hidden" value="<?=$headers?>" name="headerdata">');
            var bodydata = $('<input type="hidden" value="<?=$bodys?>" name="bodydata">');
            var excelnamedata = $('<input type="hidden" value="주문서" name="excelnamedata">');
            $form.append(exceldata).append(headerdata).append(bodydata).append(excelnamedata);
            $form.submit();
        });
    })
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
            // $("input[name='chk[]']").prop("checked", true);
            $(".DTFC_LeftBodyLiner input[name='chk[]']").prop("checked" , true);
        } else {
            // $("input[name='chk[]']").prop("checked", false);
            $(".DTFC_LeftBodyLiner input[name='chk[]']").prop("checked" , false);
        }

        // testchk
    }

    function outputCountReset() {
        $("input:checkbox[id*='_case']").prop("checked",true);
        $("#ov_search_type").val('ov_it_name');
        $("#ov_search_keyword").val('');
        $("#outputCount").val(200);
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

    // 츨고 확정 !!
    function orderTransfer(e,e2=false) {
        if(e == 'hold'){
            var check = confirm("출고보류 하시겠습니까?");            
        }else if(e == 'mapping'){
            var check = confirm("매핑처리 하시겠습니까?");            
        }else if(e == 'decide'){
            var check = confirm("출고확정 하시겠습니까?");            
        } else if(e == 'companySave'){ 
            var check = confirm("저장 하시겠습니까?");     
        } else if (e == 'etc') {
            var check = confirm("물류상태 변경하시겠습니까?");  
        } else if (e == 'collection') {
            var check = confirm("주문수집 하시겠습니까?");  
        } else if (e == 'handCancel') {
            var check = confirm("수기취소 하시겠습니까?");
        }else if (e == 'smsView') {
            var check = confirm("품절 SMS 발송 하시겠습니까?");
        }else if (e == 'soldoutCancel') {
            var check = confirm("품절취소 변경하시겠습니까?");
        }
        if(check){
            if (e =='collection') {
                $.ajax({
                url: "/adm/cron/cron_sabang_order_collection.php",
                method: "POST",
                data: {
                },
                dataType: "json",
                async: false,
                cache: false,
                success: function(result) {
                    location.reload();
                    // console.log(result);
                }
                });
                return;
            }
        
            if (e2) {
                let changeCompany = $(`#ov_delivery_type_${e2} option:selected`).val();
                let changeDpartner = $(`#ov_dpartner_type_${e2} option:selected`).val();
                let changeCompanyName = $(`#ov_delivery_type_${e2} option:selected`).text();

                $.ajax({
                url: "./total_order_remove.php",
                method: "POST",
                data: {
                    "slov_id": e2,
                    'buttonType' : e,
                    'saveCom' : changeCompany,
                    'saveComName' : changeCompanyName,
                    'saveDpart' : changeDpartner
                },
                dataType: "json",
                async: false,
                cache: false,
                success: function(result) {
                    location.reload();
                }
                });
                return;
            }
            if (!is_checked("chk[]")) {
                alert("하나 이상 선택하세요.");
                return false;
            }
            var select_obj = '';
            var mappingOpt = {};
            var saveCom = {};
            let falseCheck = true;
            
            let smsC = [];
            let smsCheck = '';
            
            $("input[name='chk[]']:checked").each(function (index) {
                let ov_order_status = $(this).attr("ov_order_status");
                let ov_distribution_status = $(this).attr("ov_distribution_status");
                let ov_options_modify = $(this).attr("ov_options_modify");
                let ov_sms_check = $(this).attr("ov_sms_check");
                if (e == 'handCancel') {
                    let ov_mall_id = $(this).attr("ov_mall_id");
                    if ((ov_order_status !='신규주문' && ov_order_status !='주문확인') || ov_order_status =='출고전취소' || ov_order_status =='품절취소' || ov_distribution_status =='출고전취소' ) {
                        alert("수기취소 할 수 없는 상태입니다.");
                        falseCheck = false;
                        return false;
                    }
                }
                if (e == 'hold') {
                    if (ov_distribution_status =='출고확정' || ov_order_status =='품절취소') {
                        falseCheck = false;
                        alert("출고보류 할 수 없는 상태입니다.");
                        return false;
                    }
                }
                if (e == 'decide') {
                    if (ov_order_status =='주문확인' || ov_order_status =='출고전취소' || ov_order_status =='품절취소' || ov_distribution_status =='품절' || ov_distribution_status =='출고보류' || ov_distribution_status =='출고확정' || ov_options_modify == '' || ov_options_modify == null || ov_distribution_status == '물류품절') {
                        falseCheck = false;
                        alert("출고확정 할 수 없는 상태입니다.");
                        return false;
                    }
                    if (ov_distribution_status != '품절') {
                        if (select_obj != '') {
                            select_obj += ',';
                        }
                        select_obj += $(this).val();
                    }
                }
                if (e == 'mapping') {
                    if (ov_order_status =='주문확인' || ov_order_status =='품절취소') {
                        falseCheck = false;
                        alert("매핑처리 할 수 없는 상태입니다.");
                        return false;
                    }
                    mappingOpt[$(this).val()] = $(`#optionsModi_${$(this).val()}`).val();
                    if (mappingOpt[$(this).val()] == '' || mappingOpt[$(this).val()] == null) {
                        falseCheck = false;
                        alert("옵션명을 확인해주세요.");
                        return false;
                    }
                }
                if (e == 'etc') {
                    if (ov_order_status =='주문확인') {
                        falseCheck = false;
                        alert("출고확정 주문은 상태 변경 불가 합니다.");
                        return false;
                    }
                }             
                if (e == 'smsView') { 
                    if (ov_order_status !='신규주문') {
                        alert('품절 SMS 발송할 상품을 확인해주세요.');
                        falseCheck = false;
                        return false;
                    }
                    if (ov_sms_check =='1') {
                        alert('품절 SMS 발송한 상품이 포함되어있습니다.');
                        falseCheck = false;
                        return false;
                    }
                    smsC.push($(this).val());
                    select_obj = smsC;
                }
                if (e != 'decide' && e != 'smsView') {
                    if (index != 0) {
                        select_obj += ',';
                    }
                    select_obj += $(this).val();
                }  
            });
            if(!falseCheck) {
                return false;
            }

            if (select_obj == '') {
                alert("선택값을 확인해주세요.");
                return false;
            }
            $.ajax({
                url: "./total_order_remove.php",
                method: "POST",
                data: {
                    "slov_id": select_obj,
                    'buttonType' : e,
                    'mappingOpt' : mappingOpt,
                    'saveCom' : saveCom
                },
                dataType: "json",
                async: false,
                cache: false,
                success: function(result) {
                    if (result.indexOf('매핑') !== -1) {
                        alert(result);
                        location.reload();
                    } else {
                        //console.log(result);
                        location.reload();
                    }
                }
            });
            return;
        }else{
            return false;
        }
    }
    function showDetailInfo(e) {
        $.post(
            "total_order_info.php", {
            slov_id: e
          },
          function(data) {
            $("#dvDetail").empty().html(data);
          }
        );

        $('#detail_modal').modal('show');
    }
    function chageDpartner(e1,e2){
        let options = null;
        let changeValue = $(`#ov_dpartner_type_${e2} option:selected`).val();
        //  옵션도 변경 !!!
        if (changeValue == '경민실업') {
            option = $("<option value='003'>CJ대한통운</option>");
            $('#ov_delivery_type_'+e2).empty();
            $('#ov_delivery_type_'+e2).append(option);
        } else if (changeValue == '어시스트') {
            option = $("<option value='003'>CJ대한통운</option>");
            $('#ov_delivery_type_'+e2).empty();
            $('#ov_delivery_type_'+e2).append(option);
        } else {
            option = $(`<option value='003'>CJ대한통운</option>
            <option value='002'>롯데택배</option>
            <option value="001">대한통운</option>
            <option value="004">한진택배</option>
            <option value="005">KGB택배</option>
            <option value="006">동부택배</option>
            <option value="007">로젠택배</option>
            <option value="008">옐로우캡택배</option>
            <option value="009">우체국택배</option>
            <option value="010">하나로택배</option>
            <option value="013">경동택배</option>
            <option value="014">일양로직스</option>
            <option value="016">천일백배</option>
            <option value="017">동부익스프레스</option>
            `);
            $('#ov_delivery_type_'+e2).empty();
            $('#ov_delivery_type_'+e2).append(option);
            
        }
        if (e1 != changeValue) {
            $(`#saveCompany_${e2}`).show();
            $(`[name='saveCompanyDiv_${e2}']`).show();
        } else {
            $(`#saveCompany_${e2}`).hide();
            $(`[name='saveCompanyDiv_${e2}']`).hide();
        }
    }

    function showMemo(idx) {
        if(idx){
            var memo = $("#order_memo_"+idx).val();
            $("#memoDetail").empty().html("<input type='hidden' id ='idx' value='"+idx+"'><textarea id = 'order_memo' style='width: 100%; border: 1px solid;' type='text'>"+memo+"</textarea>");
        }else{
            $("#memoDetail").empty().html("<input type='hidden' id ='idx' value='"+idx+"'><textarea id = 'order_memo' style='width: 100%; border: 1px solid;' type='text' ></textarea>");
        }
        $('#memo_modal').modal('show');
    }

    function saveMemo(){
        var write_memo = $('#order_memo').val();
        var sabang_idx = $('#idx').val();

        if(write_memo && sabang_idx){
            $.ajax({
                url : './total_order_meno_form_view.php',
                type: 'post',
                async : false,
                data : {order_memo : write_memo , sabang_idx : sabang_idx},

                error:function(error){
                    complete = false;  
                },
                success:function(response){
                    // console.log(response);  
                    complete = true; 
                }
            });
            if(complete == true){
                alert("수정 완료 되었습니다.");
                location.reload();
            }
        }
    }
    
</script>
<!-- 상세보기 !!!!!!!!!!! -->
<div class="modal fade" id="detail_modal" tabindex="-1" role="dialog" aria-labelledby="detail_modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content"  style ="width: 1450px; margin-left: -120px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">주문자정보</h4>
      </div>
      <div class="modal-body">
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
        <br><br><br>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
    </div>
  </div>
</div>

<!-- 메모 -->
<div class="modal fade" id="memo_modal" tabindex="-1" role="dialog" aria-labelledby="memo_modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"  style ="width: 750px; margin-left: 200px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">메모</h4>
            </div>
            <div class="modal-body" style="text-align: center;">
                <div class="tbl_frm01 tbl_wrap" id="memoDetail">
                    
                </div>

            </div>
            <div class="modal-footer" style="text-align: center;">
                <input type="button" value="취소" class="btn btn_02" class="close" data-dismiss="modal" aria-label="Close">
                <input type="button" value="저장" class="btn btn_03" onclick="saveMemo()">
            </div>
        </div>
    </div>
</div>
<?php 
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
