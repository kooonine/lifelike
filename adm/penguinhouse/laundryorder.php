<?php
ob_start();
include_once ('../../common.php');

$result = -1;
$rtnDataArr = array();
$sendtype = "";

if(isset($_POST['TR_NAME']))
{
    $TR_NAME = $_POST['TR_NAME'];
    $sendtype = "post";
} 
elseif(isset($_GET['TR_NAME']))
{
    $TR_NAME = $_GET['TR_NAME'];
    $sendtype = "get";
} else {
    $data = json_decode(file_get_contents('php://input'), true);
    $TR_NAME = $data["TR_NAME"];
    $sendtype = "curl";
}

if(isset($_POST['RFID']))
{
    $RFID = $_POST['RFID'];
} 
else if(isset($_GET['RFID']))
{
    $RFID = $_GET['RFID'];
} else {
    $data = json_decode(file_get_contents('php://input'), true);
    $RFID = $data["RFID"];
}

if(isset($TR_NAME) && $TR_NAME == "LAUNDRY_ORDER_POST")
{
    $sql  = " select a.*, b.*, c.ca_id, d.ca_name, c.it_basic
                from {$g5['g5_shop_order_table']} as a 
                     inner join {$g5['g5_shop_cart_table']} as b
                        on a.od_id = b.od_id 
                     inner join {$g5['g5_shop_item_table']} as c
                        on b.it_id = c.it_id
                     inner join {$g5['g5_shop_category_table']} as d
                        on c.ca_id = d.ca_id
                where   a.od_type IN ('L','K')
                and     a.od_status IN('결제완료', '세탁신청', '보관신청','수거박스배송','수거중','수거완료')
                ";
    
    if(isset($RFID) && $RFID != '') {
        $sql  .= " and rf_serial = '$RFID' ";
    }
    
    $rs = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($rs); $i++)
    {
        
        $disp_od_id = $row['od_type'].'-'.substr($row['od_id'],0,8).'-'.substr($row['od_id'],8,6);
        
        $rtnData = array();
        $rtnData["RESULT"] = 1;
        $rtnData["TR_NAME"] = $TR_NAME;
        $rtnData["RFID"] = $row['rf_serial'];
        
        $rtnData["USER_NAME"] = $row['od_name'];
        $rtnData["USER_TEL"] = $row['od_hp'];
        
        $rtnData["USER_ADD"] = $row['od_b_zip1'].$row['od_b_zip2'].' '.$row['od_b_addr1'].' '.$row['od_b_addr2'];
        $rtnData["USER_IN_DATE"] = substr($row['od_time'],0,10);
        
        $rtnData["STRG_YN"] = ($row['od_type'] == "K")?"Y":"N";
        $rtnData["STRG_MONTH"] = $row['ct_keep_month'];
        $rtnData["STRG_PICKUP_DATE"] = substr($row['od_hope_date'],0,10);
        
        $rtnData["ORDER_NO"] = $disp_od_id.'-'.$row['od_sub_id'];
        $rtnData["ITEM_NAME"] = $row['it_name'];
        $rtnData["CA_ID"] = $row['ca_id'];
        $rtnData["CA_NAME"] = $row['ca_name'];
        $rtnData["ITEM_DETAIL_NAME"] = $row['it_basic'];
        $rtnData["ITEM_SIZE"] = $row['ct_option'];
        
        $rtnData["INVOICE_NO"] = $row['od_pickup_invoice'];
        $rtnData["CUST_MEMO"] = $row['cust_memo'];
        
        $cust_file = json_decode($row['cust_file'], true);
        $cust_file_array = array();
        for ($i = 0; $i < count($cust_file); $i++) {
            $cust_file_array[] = G5_DATA_URL.'/file/order/'.$row['od_id'].'/'.$cust_file[$i]['file'];
        }

        $rtnData["CUST_FILE"] = $cust_file_array;
        
        $rtnData["sendtype"] = $sendtype;
        
        $rtnDataArr[] = $rtnData;
    }
    
} else {
    $rtnData = array();
    $rtnData["RESULT"] = -1;
    $rtnData["RESULTMSG"] = "ERROR PARAM";
    $rtnDataArr[] = $rtnData;
}

echo  json_encode($rtnDataArr);
$length=ob_get_length();
header('Content-Type: application/json');
header("Content-Length: $length");
header('Access-Control-Allow-Origin: *');
ob_end_flush();
?>