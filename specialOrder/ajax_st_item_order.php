<?php
include_once('./_common.php');

$ord_date = date("Ymdhis");



foreach ($_POST["it_name"] as $b2b => $b2b_ord) {

    $order_no = $ord_date."_".($b2b+1);
    $receive_tel = $_POST['receive_tel1'] .'-'.$_POST['receive_tel2'] .'-'.$_POST['receive_tel3'] ;

    $color = trim($_POST['color'][$b2b]);
    $size = trim($_POST['size'][$b2b]);

    $order_qty = preg_replace('/,/', '',$_POST["order_qty"][$b2b]);
    $normal_price = preg_replace('/,/', '',$_POST["normal_price"][$b2b]);
    $supply_price = preg_replace('/,/', '',$_POST["supply_price"][$b2b]);
    $order_price = preg_replace('/,/', '',$_POST["order_price"][$b2b]);

    $ord_common = "";
    
    $ord_common .= "cp_name = '{$_POST['cp_name']}', ";
    $ord_common .= "cp_code = '{$_POST['cp_code']}', ";
    $ord_common .= "order_no = '{$order_no}', ";
    $ord_common .= "order_status = '주문접수', ";
    $ord_common .= "reg_date = now(), ";
    $ord_common .= "order_qty = '{$order_qty}', ";
    $ord_common .= "st_name = '{$_POST['st_name']}', ";
    $ord_common .= "st_tel = '{$_POST['st_tel']}', ";
    $ord_common .= "it_name = '{$_POST['it_name'][$b2b]}', ";
    $ord_common .= "receive_name = '{$_POST['receive_name']}', ";
    $ord_common .= "receive_tel = '{$receive_tel}', ";
    $ord_common .= "receive_zip = '{$_POST['receive_zip']}', ";
    $ord_common .= "receive_addr1 = '{$_POST['receive_addr1']}', ";
    $ord_common .= "receive_addr2 = '{$_POST['receive_addr2']}', ";
    $ord_common .= "samjin_it_name = '{$_POST['samjin_it_name'][$b2b]}', ";
    $ord_common .= "samjin_code = '{$_POST['samjin_code'][$b2b]}', ";
    $ord_common .= "sap_code = '{$_POST['sap_code'][$b2b]}', ";
    $ord_common .= "color = '{$color}', ";
    $ord_common .= "size = '{$size}', ";
    $ord_common .= "normal_price = '$normal_price', ";
    $ord_common .= "supply_price = '$supply_price', ";
    $ord_common .= "order_price = '{$order_price}', ";
    $ord_common .= "dpart_type = '택배', ";
    $ord_common .= "deliver_type = '신용', ";
    $ord_common .= "mall_code = '특판 건', ";
    $ord_common .= "supply_cp = '특판' ";
    
    
    $in_sql = "insert into b2b_order set " . $ord_common;

    sql_query($in_sql);
}


// goto_url("./st_item_order.php");







