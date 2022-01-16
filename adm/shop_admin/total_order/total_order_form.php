<?php
$sub_menu = '41';
include_once('./_common.php');
include_once(G5_LIB_PATH . '/PHPExcel.php');
include_once(G5_LIB_PATH . '/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');


auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '발주서 리스트';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');



$sql_search = " where (1)";

if($sfl){
    if ($keyword) {
        if ($sfl =='receive_cel') {
            $sql_search .= " AND (receive_cel LIKE '%$keyword%' OR receive_tel LIKE '%$keyword%' )"; 
        } else if($sfl =='samjin_it_name' || $sfl =='it_name'  || $sfl =='receive_name'  ) {
            $sql_search .= " AND $sfl LIKE '%$keyword%'";
        } else{
            preg_match_all("/[^() ||  \/\,\n]+/", $keyword,$list);
            $in_list = empty($list[0])?'NULL':"'".join("','", $list[0])."'";
            $sql_search .= " AND $sfl IN ({$in_list})"; 
        }
    }
}


if ($sc_it_time != "") {
    $sc_it_times = explode("~", $sc_it_time);
    $fr_sc_it_time = trim($sc_it_times[0]);
    $to_sc_it_time = trim($sc_it_times[1]);

    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_sc_it_time) ) $fr_sc_it_time = '';
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_sc_it_time) ) $to_sc_it_time = '';

    $timestamp1 = strptime($fr_sc_it_time, '%Y-%m-%d');
    $timestamp2 = strptime($to_sc_it_time, '%Y-%m-%d');
    

    $fr_sc_it_time = mktime(0, 0, 0, $timestamp1['tm_mon']+1, $timestamp1['tm_mday'], $timestamp1['tm_year']+1900);
    $to_sc_it_time = mktime(0, 0, 0, $timestamp2['tm_mon']+1, $timestamp2['tm_mday']+1, $timestamp2['tm_year']+1900);

    if($sc_it_time == " ") {
        $sql_search .= " and reg_dt is not null ";
    }else{
        if ($fr_sc_it_time && $to_sc_it_time) {
            $sql_search .= " and reg_dt between   FROM_UNIXTIME({$fr_sc_it_time}) and  FROM_UNIXTIME({$to_sc_it_time}) ";
        }
    }
    
}else{
    $toDate = date("Y-m-d");

    $timestamp1 = strptime($toDate, '%Y-%m-%d');

    $fr_sc_it_time = mktime(0, 0, 0, $timestamp1['tm_mon']+1, $timestamp1['tm_mday'], $timestamp1['tm_year']+1900);
    $to_sc_it_time = mktime(0, 0, 0, $timestamp1['tm_mon']+1, $timestamp1['tm_mday']+1, $timestamp1['tm_year']+1900);

    $sql_search .= " and reg_dt between   FROM_UNIXTIME({$fr_sc_it_time}) and  FROM_UNIXTIME({$to_sc_it_time}) ";
    $sc_it_time = $toDate.' ~ '.$toDate;
}

if($invoice_up_dt != ""){
    $sql_search .= " and invoice_up_dt between '$invoice_up_dt 00:00:00' and '$invoice_up_dt 23:59:59' ";
}

if ($mb_today_login != "") {
    $mb_today_logins = explode("~", $mb_today_login);
    $fr_mb_today_login = trim($mb_today_logins[0]);
    $to_mb_today_login = trim($mb_today_logins[1]);

    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_mb_today_login) ) $fr_mb_today_login = '';
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_mb_today_login) ) $to_mb_today_login = '';

    if ($fr_mb_today_login && $to_mb_today_login) {
        $sql_search .= " and reg_dt between '$fr_mb_today_login 00:00:00' and '$to_mb_today_login 23:59:59' ";
    }
}

if (!$dpartner_ids) {
    //$sql_search .= " and dparner_id in ('경민실업','어시스트','본사') ";
}else{
    if ($dpartner_ids == '경민실업') {
        $sql_search .= " and dpartner_id = '경민실업' ";
    }else{
        $dpartner_ids_item = implode("','", explode(',', $dpartner_ids));
        $sql_search .= " and dpartner_id in ('{$dpartner_ids_item}') ";
    }
}

if (!$dpartner_stats) {
    //$sql_search .= " and dparner_id in ('경민실업','어시스트','본사') ";
}else{
    if ($dpartner_stats == '정상') {
        $sql_search .= " and dpartner_stat = '정상' ";
    }else{
        $dpartner_stats_item = implode("','", explode(',', $dpartner_stats));
        $sql_search .= " and dpartner_stat in ('{$dpartner_stats_item}') ";
    }
}


if (!$invoice_yns) {
    //$sql_search .= " and dparner_id in ('경민실업','어시스트','본사') ";
}else{
    if ($invoice_yns == '송장입력') {
        $sql_search .= " and NULLIF(order_invoice,'') ";
    }else if ($invoice_yns == '송장미입력'){
        
        $sql_search .= " and (order_invoice is null or order_invoice='' )";
    }else{
        
    }
}

if (!$sms_yns) {

}else{
    if ($sms_yns == '1') {
        $sql_search .= " and form_sms_check ='1' ";
    }else if ($sms_yns == '2'){
        
        $sql_search .= " and form_sms_check ='0' ";
    }else{
        
    }
}

$degress_sql = "SELECT degress FROM  sabang_lt_order_form {$sql_search} GROUP BY degress";
$degress_result = sql_query($degress_sql);

//차수
if ($degress > 0) {
    if ($degress == '') {
        // $sql_search .= " and degress is not null ";
    }else{
        $sql_search .= " and degress = {$degress} ";
    }
}else{

}


// 테이블의 전체 레코드수만 얻음
$cnt_sql = "SELECT COUNT(*) AS cnt FROM  sabang_lt_order_form {$sql_search}";
$cnt_row = sql_fetch($cnt_sql);

$total_count = $cnt_row['cnt'];

if($limit_list) $rows = $limit_list;
// else $rows = $config['cf_page_rows'];
else $rows = 50;
// $rows=4;

// $total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
// if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
// $from_record = ($page - 1) * $rows; // 시작 열을 구함

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산

if ($total_page < 2 || empty($page)) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$sql = "select *, replace(concat(samjin_code,order_it_color,samjin_barcode_size),' ','') AS samjin_code_modi from sabang_lt_order_form {$sql_search} order by order_date ASC , mall_order_no ASC , sabang_ord_no ASC limit $from_record, $rows";

$result = sql_query($sql);

$qstr= "dpartner_ids=".$dpartner_ids."&amp;dpartner_stats=".$dpartner_stats."&amp;invoice_yns=".$invoice_yns."&amp;sms_yns=".$sms_yns."&amp;sfl=".$sfl."&amp;keyword=".$keyword."&amp;sc_it_time=".$sc_it_time."&amp;limit_list=".$limit_list."&amp;degress=".$degress."&amp;page=".$page;

$oform_headers = array('받는분','연락처','','우편번호','주소','품목명','색상','사이즈','주문수량','박스수량','타입','운임','배송요청사항','보내는분','보내는분연락처','주소','고객주문번호','품목코드','운송장번호','창고번호','제휴사','결재번호','SMS발송여부','SAMJIN_CODE','쇼핑몰주문번호');
$oform_bodys = array('receive_name','receive_cel','','receive_zipcode','receive_addr','samjin_it_name','order_it_color','order_it_size','order_it_cnt','order_box_cnt','order_type','order_unim','order_meg','sender','sender_tel','sender_addr','sabang_ord_no','samjin_code_modi','order_invoice','warehouse_no','mall_name','sub_order_id','form_sms_check','samjin_code','mall_order_no');

$enc = new str_encrypt();

$oform_headers = $enc->encrypt(json_encode_raw($oform_headers));
$oform_bodys = $enc->encrypt(json_encode_raw($oform_bodys));


?>
<body id="total_order_body">
<!-- <div style="background-color : #fff;"> -->
<div class="x_panel">
    <form id="new_goods_form" name="new_goods_form" class="local_sch01 local_sch" onsubmit="" method="post">
        <input type="hidden" name = "dpartner_ids" value='<?=$dpartner_ids?>' id="dpartner_ids">
        <input type="hidden" name = "dpartner_stats" value='<?=$dpartner_stats?>' id="dpartner_stats">
        <input type="hidden" name = "invoice_yns" value='<?=$invoice_yns?>' id="invoice_yns">
        <input type="hidden" name = "sms_yns" value='<?=$sms_yns?>' id="sms_yns">
        
        <div class="tbl_frm01 tbl_wrap">
            <table class="new_goods_list">
            <colgroup>
            <!-- <col class="grid_4"> -->
            <!-- <col> -->
            <!-- <col class="grid_3"> -->
            </colgroup>
            
            <tr>
                <th scope="row">검색분류</th>
                <td colspan="2">
                    <label for="sfl" class="sound_only">검색대상</label>
                    <select name="sfl" id="sfl">
                        <!-- <option value="it_name" <?php echo get_selected($sfl, 'it_name'); ?>>상품명</option> -->
                        <option value="samjin_it_name" <?php echo get_selected($sfl, 'samjin_it_name'); ?>>삼진품목명</option>
                        <option value="samjin_code" <?php echo get_selected($sfl, 'samjin_code'); ?>>삼진코드</option>
                        <option value="mall_order_no" <?php echo get_selected($sfl, 'mall_order_no'); ?>>쇼핑몰주문번호</option>
                        <option value="sabang_ord_no" <?php echo get_selected($sfl, 'sabang_ord_no'); ?>>사방넷주문번호</option>
                        <option value="order_name" <?php echo get_selected($sfl, 'order_name'); ?>>주문자명</option>
                        <option value="order_invoice" <?php echo get_selected($sfl, 'order_invoice'); ?>>송장번호</option>
                        <option value="receive_cel" <?php echo get_selected($sfl, 'receive_cel'); ?>>전화번호</option>
                        <option value="receive_name" <?php echo get_selected($sfl, 'receive_name'); ?>>수취인명</option>
                    </select>
                    <label for="keyword" class="sound_only">검색어</label>
                    <input type="text" style="width : 90%;" name="keyword" value="<?php echo $keyword; ?>" onkeydown = "serachEnter(event)" id="keyword" class="frm_input">
                </td>
            </tr>
            <tr>
                <th scope="row">일자</th>
                <td colspan="2">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <input type='text' class="form-control" id="it_time" name="sc_it_time" value="" autocomplete="off"/>
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
                <th scope="row">물류처</th>
                <td colspan="2">
                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                    <label><input type="checkbox" value=""  id="dpartner_id_0"  <?php if(!$dpartner_ids) echo "checked"; ?>>전체</label>&nbsp;&nbsp;
                    <label><input type="checkbox" value="경민실업" id="dpartner_id_1" class="dpartner_id" <?php if(!$dpartner_ids || (substr_count($dpartner_ids, '경민실업') >= 1) ) echo "checked"; ?> >경민실업</label>&nbsp;&nbsp;
                    <label><input type="checkbox" value="어시스트" id="dpartner_id_2" class="dpartner_id" <?php if(!$dpartner_ids || (substr_count($dpartner_ids, '어시스트') >= 1) ) echo "checked"; ?> >어시스트</label>&nbsp;&nbsp;
                    <label><input type="checkbox" value="본사" id="dpartner_id_3" class="dpartner_id" <?php if(!$dpartner_ids || (substr_count($dpartner_ids, '본사') >= 1) ) echo "checked"; ?> >본사</label>&nbsp;&nbsp;
                </div>
                </td>
            </tr>
            <tr>
                <th scope="row">물류상태</th>
                <td colspan="2">
                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                    <label><input type="checkbox" value="" id="dpartner_stat_0"  <?php if(!$dpartner_stats) echo "checked"; ?>>전체</label>&nbsp;&nbsp;
                    <label><input type="checkbox" class="dpartner_stat" value="정상" id="dpartner_stat_0" <?php if(!$dpartner_stats || (substr_count($dpartner_stats, '정상') >= 1) ) echo "checked"; ?>  >정상</label>&nbsp;&nbsp;
                    <!-- <label><input type="checkbox" class="dpartner_stat" value="합포불가" id="dpartner_stat_1" <?php if(!$dpartner_stats || (substr_count($dpartner_stats, '합포불가') >= 1) ) echo "checked"; ?>  >합포불가</label>&nbsp;&nbsp; -->
                    <label><input type="checkbox" class="dpartner_stat" value="물류품절" id="dpartner_stat_2" <?php if(!$dpartner_stats || (substr_count($dpartner_stats, '물류품절') >= 1) ) echo "checked"; ?>  >물류품절</label>&nbsp;&nbsp;
                    <label><input type="checkbox" class="dpartner_stat" value="출고전취소" id="dpartner_stat_3" <?php if(!$dpartner_stats || (substr_count($dpartner_stats, '출고전취소') >= 1) ) echo "checked"; ?>  >출고전취소</label>&nbsp;&nbsp;
                </div>
                </td>
            </tr>
            <tr>
                <th scope="row">송장여부</th>
                <td colspan="2">
                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                    <label><input type="checkbox" value="" id="invoice_yn_0"  <?php if(!$invoice_yns) echo "checked"; ?>>전체</label>&nbsp;&nbsp;
                    <label><input type="checkbox" class="invoice_yn" value="송장입력" id="invoice_yn_1" <?php if(!$invoice_yns || (substr_count($invoice_yns, '송장입력') >= 1) ) echo "checked"; ?>  >송장입력</label>&nbsp;&nbsp;
                    <label><input type="checkbox" class="invoice_yn" value="송장미입력" id="invoice_yn_2" <?php if(!$invoice_yns || (substr_count($invoice_yns, '송장미입력') >= 1) ) echo "checked"; ?>  >송장미입력</label>&nbsp;&nbsp;
                    
                    <input style="<?=$invoice_yns == '송장입력' ? 'visibility : visible;':'visibility : hidden;'?>" type="text" name = "invoice_up_dt"  id = "invoice_up_dt" value="<?=$invoice_up_dt?>">
                    
                </div>
                </td>
            </tr>
            <tr>
                <th scope="row">SMS 발송여부</th>
                <td colspan="2">
                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                    <label><input type="checkbox" value="" id="sms_yn_0"  <?php if(!$sms_yns) echo "checked"; ?>>전체</label>&nbsp;&nbsp;
                    <label><input type="checkbox" class="sms_yn" value="1" id="sms_yn_1" <?php if(!$sms_yns || (substr_count($sms_yns, '1') >= 1) ) echo "checked"; ?>  >발송</label>&nbsp;&nbsp;
                    <label><input type="checkbox" class="sms_yn" value="2" id="sms_yn_2" <?php if(!$sms_yns || (substr_count($sms_yns, '2') >= 1) ) echo "checked"; ?>  >미발송</label>&nbsp;&nbsp;
                </div>
                </td>
            </tr>
            <?
            $sc_it_times = explode("~", $sc_it_time);
            $st_time = trim($sc_it_times[0]);
            $ed_time = trim($sc_it_times[1]);
            if($st_time == $ed_time && !empty($st_time) ) : 
            ?>
            <tr>
                <th scope="row">차수</th>
                <td colspan="2">
                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                    <select name="degress" id="degress">
                        <option value="" <?php if($row_deg['degress'] == '') echo "selected"; ?> >전체</option>
                        <?if(!empty($degress_result)):?>
                            <?for($dgi = 0 ; $row_deg = sql_fetch_array($degress_result); $dgi++ ):?>
                                <option value="<?=$row_deg['degress']?>" <?php if($row_deg['degress'] == $degress) echo "selected"; ?> ><?=$row_deg['degress']?></option>
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
                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                    <select name="limit_list" id="limit_list">
                        <option value="50" <?php if(!$limit_list ||(substr_count($limit_list, '50') >= 1)) echo "selected"; ?>>50개</option>
                        <option value="100" <?php if(substr_count($limit_list, '100') >= 1) echo "selected"; ?>>100개</option>
                        <option value="200" <?php if(substr_count($limit_list, '200') >= 1) echo "selected"; ?>>200개</option>
                        <option value="300" <?php if(substr_count($limit_list, '300') >= 1) echo "selected"; ?>>300개</option>
                        <option value="400" <?php if(substr_count($limit_list, '400') >= 1) echo "selected"; ?>>400개</option>
                        <option value="500" <?php if(substr_count($limit_list, '500') >= 1) echo "selected"; ?>>500개</option>
                        <option value="1500" <?php if(substr_count($limit_list, '1500') >= 1) echo "selected"; ?>>1500개</option>
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


<style>
        th, td {
            white-space: nowrap;
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
        /*table.dataTable thead > tr > td {
            width: 80px;
        }*/
        .dpartner_so{
            background-color:#d9d9d9 !important;
        }
        .order_for_cancel{
            background-color:#e6e0ec !important;
        }

        .head_th tr th {text-align : center;}

        .dataTables_scrollBody input.ab {display : none;}
        
        /* .dataTables_scrollBody input.chk_all {display : none;} */
        .list-paging{margin-top : 20px;}
        #total_order_body{font-size : 13px;}
    </style>
<link rel="stylesheet" href="./fixed_table.css">
<div class="local_ov01 local_ov">
	<span class="btn_ov01">[ 검색결과 <?= number_format($total_count); ?>건 ]</span>
</div>
<form>
    <input type ="hidden" name ="excel_sno" id="excel_sno" >
    <div class="local_cmd01 local_cmd" style="margin-top : 20px;">
        <div class="btn btn_02" style="height: 30px;" onclick ="down_excel()">엑셀다운로드</div>
        <input type='file' name ="upload_excel" id='upload_excel' />
        <div class="btn btn_02" style="height: 30px;" id="upload_excel_btn">엑셀업로드
        </div>
        <div class="btn btn_02" style="height: 30px;" id="dpartner_sold_out">물류품절
            <input type ="hidden" name ="sold_out_sno" id="sold_out_sno" >
        </div>
        <?if($member['mb_id'] == 'sbs608' || $member['mb_id'] == 'ny0606' || $member['mb_id'] == 'enskwkdsla12') : ?>
        <div class="btn btn_02" style="height: 30px;" id="dpartner_re_order">재출고
            <input type ="hidden" name ="re_order_sno" id="re_order_sno" >
        </div>
        <div class="btn btn_02" style="height: 30px;" id="delivery_sum"  onclick ="delivery_sum()">합포정렬</div>
        <div class="btn btn_02" style="height: 30px;" id="order_error"  onclick ="order_error()">출고오류</div>
        <?endif?> 
        <div class="btn btn_02" style="height: 30px;" id="form_sms"  onclick ="form_sms()">품절SMS발송</div>
        <!-- <div class="btn btn_02" style="height: 30px;" id="hand_del"  onclick ="handDel()">수기취소</div> -->
    </div>
    <table id="reportTb" class="display" style="width:100%">
        <div class="tbl_head01 tbl_wrap" id="topscroll"  style="width:100%; margin:0px; overflow-x:scroll;">
            <div class="div1" style="width:2150px; height:20px;"></div>
        </div>
        <thead class="head_th">
            <tr>
                <th>
                    <label for="chkall" class="sound_only">선택 전체</label>
                    <input type="checkbox" name="chkall"  id="chkall" class="chk_all" onclick="all_chk(this.form)"   />
                </th>
                <th>주문일자</th>
                <th>사방넷주문번호</th>
                <th>쇼핑몰주문번호</th>
                <th>수취인명</th>
                <th>삼진코드</th>
                <th>색상</th>
                <th>사이즈</th>
                <th>주문수량</th>
                <th>박스수량</th>
                <th>물류상태</th>
                <th>연락처1</th>
                <th>연락처2</th>
                <th>우편번호</th>
                <th>주소</th>
                <th>배송요청사항</th>
                <th>물류처</th>
                <th>창고번호</th>
                <th>SMS발송여부</th>
                <th>타입</th>
                <th>삼진품목명</th>
                <th>송장번호</th>
                <!-- <th>삼진품목명</th> -->
                <!-- <th>SAP_CODE</th> -->
                
                <th style="display : none;">합포여부</th>
                <th>운밈</th>
                <th>보내는분</th>
                <th>보내는분연락처</th>
                <th>주소</th>
                
                <th>택배사코드</th>
                <th>메모</th>
                <!-- <th>송장번호</th> -->
            </tr>
        </thead>
        <tbody id="revenue-status">
            <?if(!empty($result)):?>
            <?for($ofi = 0 ; $row_ord = sql_fetch_array($result); $ofi++) {
                $invcCo = '';
                if ($row_ord['tak_code'] == '003') {
                    $invcCo = 'CJ대한통운';
                } else if ($row_ord['tak_code'] == '002') {
                    $invcCo = '롯데택배';
                } else if ($row_ord['tak_code'] == '001') { 
                    $invcCo = '대한통운';
                } else if ($row_ord['tak_code'] == '004') { 
                    $invcCo = '한진택배';
                } else if ($row_ord['tak_code'] == '005') { 
                    $invcCo = 'KGB택배';
                } else if ($row_ord['tak_code'] == '006') { 
                    $invcCo = '동부택배';
                } else if ($row_ord['tak_code'] == '007') { 
                    $invcCo = '로젠택배';
                } else if ($row_ord['tak_code'] == '008') { 
                    $invcCo = '옐로우캡택배';
                } else if ($row_ord['tak_code'] == '009') { 
                    $invcCo = '우체국택배';
                } else if ($row_ord['tak_code'] == '010') { 
                    $invcCo = '하나로택배';
                } else if ($row_ord['tak_code'] == '013') { 
                    $invcCo = '경동택배';
                } else if ($row_ord['tak_code'] == '014') { 
                    $invcCo = '일양로직스';
                } else if ($row_ord['tak_code'] == '016') { 
                    $invcCo = '천일택배';
                } else if ($row_ord['tak_code'] == '017') { 
                    $invcCo = '동부익스프레스';
                }
            ?>
            <tr class="<?if($row_ord['dpartner_stat'] == '물류품절'):?>dpartner_so<?endif?> <?if($row_ord['dpartner_stat'] == '출고전취소'):?>order_for_cancel<?endif?>">
                <td>
                    <input type="checkbox" name="chk[]" class="ab chk_<?=$ofi?>" value="<?=$ofi?>">
                    <input type="hidden" name="sno[<?=$ofi?>]" value="<?=$row_ord['sno']?>" id="sno_<?=$ofi?>">
                    <input type="hidden" name="sms[<?=$ofi?>]" value="<?=$row_ord['form_sms_check']?>" id="sms_<?=$ofi?>">
                </td>
                <td><?=substr($row_ord['order_date'],0,4)?>-<?=substr($row_ord['order_date'],4,2)?>-<?=substr($row_ord['order_date'],6,2)?></td>
                <td><?=$row_ord['sabang_ord_no']?></td>
                <td><?=$row_ord['mall_order_no']?></td>
                <td><?=$row_ord['receive_name']?></td>
                <td><?=$row_ord['samjin_code_modi']?></td>
                <td><?=$row_ord['order_it_color']?></td>
                <td><?=$row_ord['samjin_barcode_size'] ? $row_ord['samjin_barcode_size'] : $row_ord['order_it_size']?></td>
                <td><?=$row_ord['order_it_cnt']?></td>
                <td><?=$row_ord['order_box_cnt']?></td>
                <td><?=$row_ord['dpartner_stat']?></td>
                <td><?=$row_ord['receive_tel']?></td>
                <td><?=$row_ord['receive_cel']?></td>
                <td><?=$row_ord['receive_zipcode']?></td>
                <td><?=$row_ord['receive_addr']?></td>
                <td><?=$row_ord['dpartner_id']?></td>
                <td><?=$row_ord['warehouse_no']?></td>
                <td><?=$row_ord['order_meg']?></td>
                <td><? if ($row_ord['form_sms_check'] ==0) echo '미발송'; else echo '발송';?></td>
                <td><?=$row_ord['order_type']?></td>
                <td><?=$row_ord['samjin_it_name']?></td>
                <td> 
                    <a href='<?= G5_URL ?>/common/tracking.php?invc_no=<?= $row_ord['order_invoice'] ?>&invc_co=<?= $invcCo ?>&view_popup=1' target='_blank' class="form_invoice">
                        <?=$row_ord['order_invoice']?>
                    </a>
                </td>
                <!-- <td><?=$row_ord['sap_code']?></td> -->
                
                <td style="display : none;"><?=$row_ord['order_sum_sno']?></td>
                <td><?=$row_ord['order_unim']?></td>
                <td><?=$row_ord['sender']?></td>
                <td><?=$row_ord['sender_tel']?></td>
                <td><?=$row_ord['sender_addr']?></td>
                
                
                <td><?=$row_ord['tak_code']?></td>
                <!-- <td><?=$row_ord['order_invoice']?></td> -->
                <td title="<?=$row_ord['order_memo']?>">
                    <?if($row_ord['sub_slov_id'] == 0) : ?>
                        <input type="hidden" id="order_memo_<?=$row_ord['sabang_ord_no']?>" value = "<?=$row_ord['order_memo']?>">
                        <a onclick="showMemo('<?=$row_ord['sabang_ord_no']?>')" title="<?=$row_ord['order_memo']?>" style="cursor:pointer;"><?=$row_ord['order_memo'] ? 'ⓘ' : ''?>메모</a>
                    <?endif?>
                </td>
            </tr>
            <?}?>
            <?endif?>
        </tbody>
    </table>
    
    </form>
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
</div>

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
</body>

    <script src="./fixed_table.js"></script>

    <script>
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

    
    
        $( "#invoice_up_dt" ).datetimepicker({
            locale: 'ko',
            format: 'YYYY-MM-DD',
        });
        
        //날짜 버튼
        
        $("button[name='dateBtn']").click(function(){
            
            var d = $(this).attr("data");
            if(d == "all") {
                $('#it_time').val(" ");
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

        $(".dpartner_id").change(function(){
            var dpartnerId = "";
            $("input.dpartner_id:checked").each(function(){
                //alert($(this).val());
                if(dpartnerId != "") dpartnerId += ",";
                dpartnerId += $(this).val();

            });
            $("#dpartner_ids").val(dpartnerId);
        });
        $("#dpartner_id_0").change(function(){
            if($("#dpartner_id_0").is(":checked")){
                $(".dpartner_id").prop('checked',true);
                $("#dpartner_ids").val('');
            }else{
                $(".dpartner_id").prop('checked',false);
            }
        });
        $(".dpartner_stat").change(function(){
            var dpartnerStat = "";
            $("input.dpartner_stat:checked").each(function(){
                //alert($(this).val());
                if(dpartnerStat != "") dpartnerStat += ",";
                dpartnerStat += $(this).val();

            });
            $("#dpartner_stats").val(dpartnerStat);
        });
        $("#dpartner_stat_0").change(function(){
            if($("#dpartner_stat_0").is(":checked")){
                $(".dpartner_stat").prop('checked',true);
                $("#dpartner_stats").val('');
            }else{
                $(".dpartner_stat").prop('checked',false);
            }
        });

        $(".invoice_yn").change(function(){
            var InvoiceYn = "";
            $("input.invoice_yn:checked").each(function(){
                //alert($(this).val());
                if(InvoiceYn != "") InvoiceYn += ",";
                InvoiceYn += $(this).val();

            });
            $("#invoice_yns").val(InvoiceYn);

            if(InvoiceYn == '송장입력'){
                $("#invoice_up_dt").css('visibility','visible');
            }else{
                $("#invoice_up_dt").css('visibility','hidden');
            }
        });
        $("#invoice_yn_0").change(function(){
            if($("#invoice_yn_0").is(":checked")){
                $(".invoice_yn").prop('checked',true);
                $("#invoice_yns").val('');
            }else{
                $(".invoice_yn").prop('checked',false);
            }
        });

        $(".sms_yn").change(function(){
            var smsYn = "";
            $("input.sms_yn:checked").each(function(){
                if(smsYn != "") smsYn += ",";
                smsYn += $(this).val();
            });
            $("#sms_yns").val(smsYn);
        });
        $("#sms_yn_0").change(function(){
            if($("#sms_yn_0").is(":checked")){
                $(".sms_yn").prop('checked',true);
                $("#sms_yns").val('');
            }else{
                $(".sms_yn").prop('checked',false);
            }
        });
        
        $(document).ready(function () {
            var count1 = 0;
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
                    if (data[16]) {
                        if(count1 == 0){
                            count1 = data[16];
                            // $('td:eq(15)', row).attr('rowspan', data[16]);
                            count1--;
                        }else{
                            if(count1 < data[16]){
                                // $('td:eq(15)', row).css('display', 'none');     
                                count1--;
                            }
                        }
                    }
                    else {
                        // $('td:eq(12)', row).attr('rowspan', 0);
                        // $('td:eq(12)', row).css('display', 'none');
                    }
                    // if (dataIndex == 0) {
                    //     //$('td:eq(0)', row).attr('rowspan', 9);
                    // }
                    // else {
                    //     if (data[3] === "강신호") {
                    //         $('td:eq(12)', row).attr('rowspan', 2);
                    //     }
                    //     else {
                    //         //$('td:eq(12)', row).attr('rowspan', 2);
                    //         //$('td:eq(12)', row).css('display', 'none');
                    //     }
                    // }
                    //COLSPAN
                    if (data[1] === '합계') {
                        // $('td:eq(1)', row).attr('colspan', 2);
                    }

                    //CSS셋팅
                    $('td:not(:eq(0))', row).css('text-align', 'center');
                    $('td:eq(0)', row).css('text-align', 'center');
                    $('td:eq(14)', row).css('text-align', 'left');
                    $('td:eq(26)', row).css('text-align', 'left');
                },
                fixedColumns: {
                    leftColumns: 1
                }

            });

            // $(".DTFC_LeftBodyLiner").css('height' , '100%');
            // $(".DTFC_LeftBodyLiner").css('max-height' , '100%');

            $('#upload_excel').hide();
            $('#upload_excel_btn').on('click', function () {$('#upload_excel').click();});

            $('#upload_excel').change(function () {
                if (confirm("정말 등록하시겠습니까?") == true){  
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onloadend = function () {
                //    $('#main_pf_foto_img').attr('src', reader.result);
                    }
                    if (file) {
                        reader.readAsDataURL(file);
                    } else {
                    }
                    upload_excel();

                } else {
                    $("#upload_excel").val("");
                }
                // var file = this.files[0];
                // var reader = new FileReader();
                // reader.onloadend = function () {
                // //    $('#main_pf_foto_img').attr('src', reader.result);
                // }
                // if (file) {
                //     reader.readAsDataURL(file);
                // } else {
                // }
                // upload_excel();
            });

            $('#dpartner_sold_out').on('click', function () {
                if (!is_checked("chk[]")) {
                    alert("주문 건을 선택해주세요.");
                    return false;
                }
                // var $select = new Array();
                // $("#sold_out_sno").val('');

                // $("input[name='chk[]']:checked").each(function() {
                //     var sno = $("input[name='sno["+this.value+"]']").val();
                //     $select.push(sno);
                // });
                // var selects = $select.join(",");
                // if ($("#sold_out_sno").val() != "") selects += "," + $("#sold_out_sno").val();
                // $("#sold_out_sno").val(selects);

                $("input[name='chk[]']:checked").each(function() {
                    var sno = $(".DTFC_LeftBodyLiner input[name='sno["+this.value+"]']").val();
                    var type = "soldout";      
                    $.ajax({
                        url:'./ajax.total_order_form.php',
                        type:'post',
                        async: false,
                        data:{sno : sno , type : type },
                        
                        error:function(error){
                            complete = false;  
                        },
                        success:function(response){
                            complete = true;                  
                        }
                    });
                });
                if(complete == true){
                    alert("물류품절 처리가 되었습니다.");
                    location.reload();
                }
            });
            $('#dpartner_re_order').on('click', function () {
                if (!is_checked("chk[]")) {
                    alert("주문 건을 선택해주세요.");
                    return false;
                }
                $("input[name='chk[]']:checked").each(function() {
                    var sno = $(".DTFC_LeftBodyLiner input[name='sno["+this.value+"]']").val();
                    var type = "reorder";      
                    $.ajax({
                        url:'./ajax.total_order_form.php',
                        type:'post',
                        async: false,
                        data:{sno : sno , type : type },
                        
                        error:function(error){
                            complete = false;  
                        },
                        success:function(response){
                            complete = true;                  
                        }
                    });
                });
                if(complete == true){
                    alert("해당 주문이 재 출고 처리 되었습니다. \n 주문서에서 해당 주문건 다시 출고확정 처리 바랍니다.");
                    location.reload();
                }
            });
            

            $("#topscroll .div1").css('width',$("#reportTb").innerWidth() +'px');

            $("#topscroll").scroll(function(){
                $(".dataTables_scrollBody").scrollLeft($("#topscroll").scrollLeft());
            });
            $(".dataTables_scrollBody").scroll(function(){
                $("#topscroll").scrollLeft($(".dataTables_scrollBody").scrollLeft());
            });

        });

        function serachEnter (e){
            if (e.keyCode == 13) {
                document.getElementById('new_goods_form').submit();
            }
        }

        function all_chk(f){
            if($(".chk_all").hasClass("allchks")){
                $(".chk_all").removeClass("allchks");
                $(".DTFC_LeftBodyLiner input[name='chk[]']").prop("checked" , false);
            }else{
                $(".chk_all").addClass("allchks");
                $(".DTFC_LeftBodyLiner input[name='chk[]']").prop("checked" , true);
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

       function delivery_sum(){
            $.ajax({
                url:'./ajax.total_order_form_sum_sort.php',
                type:'post',
                async: false,
                
                error:function(error){
                    complete = false;  
                },
                success:function(response){
                    complete = true;                  
                }
            });

       }
       function order_error(){
            if (!is_checked("chk[]")) {
                alert("주문 건을 선택해주세요.");
                return false;
            }
            if (confirm("정말 수정 하시겠습니까? \n 해당 주문에 판매등록, 출고지시 이력이 삭제 됩니다.") == true){
                $("input[name='chk[]']:checked").each(function() {
                    var sno = $(".DTFC_LeftBodyLiner input[name='sno["+this.value+"]']").val();
                    var type = "error";  
                       
                    $.ajax({
                        url:'./ajax.total_order_form.php',
                        type:'post',
                        async: false,
                        data:{sno : sno , type : type },
                        
                        error:function(error){
                            complete = false;  
                        },
                        success:function(response){
                            complete = true;                  
                        }
                    });
                });
                if(complete == true){
                    alert(" 처리가 되었습니다.");
                    location.reload();
                }
            }
        }
        function form_sms(){
            if (!is_checked("chk[]")) {
                alert("주문 건을 선택해주세요.");
                return false;
            }  
            let smsC = [];
            let sno = '';
            let smsCheck = '';
    
            if (confirm("품절 SMS 발송하시겠습니까? \n ") == true) { 
                var type = "sms";
                $("input[name='chk[]']:checked").each(function() {
                    smsCheck = $(".DTFC_LeftBodyLiner input[name='sms["+this.value+"]']").val();
                    if (smsCheck == 1) return false;
                
                    sno = $(".DTFC_LeftBodyLiner input[name='sno["+this.value+"]']").val();
                    smsC.push(sno)
                });
                if (smsCheck == 1) {
                    alert('품절 SMS 발송한 상품이 포함되어있습니다.');
                    return false;
                } 
                
                $.ajax({
                    url:'./ajax.total_order_form.php',
                    type:'post',
                    async: false,
                    data:{sno : smsC , type : type },

                    error:function(error){
                        complete = false;  
                    },
                    success:function(response){
                        complete = true;                  
                    }
                });
                if(complete == true){
                    alert("SMS 발송 되었습니다.");
                    location.reload();
                }
            }
            return;
        }

        // function handDel(){
        //     if (!is_checked("chk[]")) {
        //         alert("주문 건을 선택해주세요.");
        //         return false;
        //     }
        //     let falseCheck = true;
        //     let select_obj = '';
        //     if (confirm("수기취소 하시겠습니까?") == true){
        //         $("input[name='chk[]']:checked").each(function() {
        //             var sno = $(".DTFC_LeftBodyLiner input[name='sno["+this.value+"]']").val();
        //             let mall_id = $(this).attr("mall_id");
        //             let dpartner_stat = $(this).attr("dpartner_stat");
        //             if (mall_id !='19978' || dpartner_stat =='출고전취소') {
        //                 alert("수기취소 할 수 없는 상태입니다.");
        //                 falseCheck = false;
        //                 return false;
        //             }
        //             if (select_obj != '') {
        //                 select_obj += ',';
        //             }
        //             select_obj += sno;
        //         });
        //         if(!falseCheck) {
        //             return false;
        //         }
        //         let type = "handDel";  
        //         $.ajax({
        //             url:'./ajax.total_order_form.php',
        //             type:'post',
        //             async: false,
        //             data:{sno : select_obj , type : type },
                    
        //             error:function(error){
        //                 complete = false;  
        //             },
        //             success:function(response){
        //                 complete = true;                  
        //             }
        //         });

        //         if(complete == true){
        //             alert(" 처리가 되었습니다.");
        //             location.reload();
        //         }
        //     }
        // }

        function down_excel(){

            if (!is_checked("chk[]")) {
                alert("엑셀 다운로드 할 상품을 선택해주세요.");
                return false;
            }
            
            var $select = new Array();
            $("#excel_sno").val('');

            $("input[name='chk[]']:checked").each(function() {
                var sno = $(".DTFC_LeftBodyLiner input[name='sno["+this.value+"]']").val();
                $select.push(sno);

            });

            var selects = $select.join(",");
            if ($("#excel_sno").val() != "") selects += "," + $("#excel_sno").val();
            $("#excel_sno").val(selects);

            excel_sql = "select * , replace(concat(samjin_code,order_it_color,samjin_barcode_size),' ','') AS samjin_code_modi from sabang_lt_order_form where sno in ( "+selects +" ) order by order_date ASC , mall_order_no ASC";
            headerdata = $('<input type="hidden" value="<?=$oform_headers?>" name="headerdata">');
            bodydata = $('<input type="hidden" value="<?=$oform_bodys?>" name="bodydata">');
            excel_type = '발주서';

            var $form = $('<form></form>');     
            $form.attr('action', '<?=G5_ADMIN_URL?>/ajax.excel_download.total_order_form.php');
            $form.attr('method', 'post');
            $form.appendTo('body');
            
            var exceldata = $('<input type="hidden" value="'+excel_sql+'" name="exceldata">');
            
            var excelnamedata = $('<input type="hidden" value="'+excel_type+'" name="excelnamedata">');
            $form.append(exceldata).append(headerdata).append(bodydata).append(excelnamedata);
            $form.submit();

        }
        
        function upload_excel(){
            var $excelfile = $("#upload_excel");
            
            var $form = $('<form></form>');     
            $form.attr('action', './upload_total_order_form.php');
            $form.attr('method', 'post');
            $form.attr('enctype', 'multipart/form-data');
            $form.appendTo('body');
            $form.append($excelfile);

            $form.submit();
            
        }
        $(".form_invoice").on("click", function() {
			var $this = $(this);
			var url = $this.attr("href");
			window.open(url, "invoice_view", "left=100,top=100,width=600,height=600,scrollbars=1");
			return false;
		});
    </script>
<?php 
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
