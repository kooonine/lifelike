<?php
include_once ('../../common.php');
require_once(G5_LIB_PATH.'/Unirest.php');

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

$result = -1;
$rtnDataArr = array();
    
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
                and rf_serial = '{$RFID}'
            ";

$row = sql_fetch($sql);
    
$disp_od_id = $row['od_type'].'-'.substr($row['od_id'],0,8).'-'.substr($row['od_id'],8,6);

$rtnData = array();
$rtnData["TR_NAME"] = "LAUNDRY_ORDER_POST";
$rtnData["RFID"] = $row['rf_serial'];

$rtnData["USER_NAME"] = $row['od_name'];
$rtnData["USER_TEL"] = $row['od_hp'];

$rtnData["USER_ADD"] = $row['od_b_zip1'].$row['od_b_zip2'].' '.$row['od_b_addr1'].' '.$row['od_b_addr2'];
$rtnData["USER_IN_DATE"] = substr($row['od_time'],0,10);

$rtnData["STRG_YN"] = ($row['od_type'] == "K")?"Y":"N";
$rtnData["STRG_MONTH"] = $row['ct_keep_month'];
$rtnData["STRG_PICKUP_DATE"] = substr($row['od_pickup_invoice_time'],0,10);

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


$response = Unirest::post(PENGUIN_HOST,
    array(
        "Content-type" => "application/json"
    ),
    json_encode($rtnData)
    );

$json_params = json_encode($rtnData);
$json_result = json_encode($response->raw_body);

sql_query("INSERT INTO lt_samjin_history (sp_name, params, result, regDate) VALUES ('".PENGUIN_HOST."', '$json_params', '$json_result', '".G5_TIME_YMDHIS."')", true);


echo $response->raw_body;

?>