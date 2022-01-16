<?php
include_once('./_common.php');

$return = array('title' => '', 'desc' => '', 'footer' => '', 'body' => '', 'error' => '', 'result' => false);
$types = array(
    1 => '계약확인',
    2 => '배송중',
    3 => '배송완료',
    4 => '계약취소(CS)',
    5 => '주문철회(CS)',
    6 => '해지요청(CS)'
);

$rt_id = $_REQUEST['rt_id'];
$type = in_array($_REQUEST['type'], array_keys($types)) ? $_REQUEST['type'] : 1;
$title = $types[$type];

// 계약정보
$sql_rental = "SELECT * FROM {$g5['g5_shop_rental_table']} WHERE rt_id={$rt_id}";
$db_rental = sql_query($sql_rental);

if (empty($db_rental->num_rows)) {
    $return['error'] = "계약내용을 찾을 수 없습니다";
    return_json($return);
}

$rental = sql_fetch($sql_rental);

// 주문목록
$sql_order = "SELECT r.*, a.od_id, a.ct_id, a.it_id, a.it_name, a.cp_price, a.ct_send_cost, a.it_sc_type, a.od_type, a.io_sapcode_color_gz as option_code
                , a.ct_option, a.ct_qty, a.ct_price, a.ct_point, a.ct_status, a.io_type, a.io_price, a.ct_rental_price, a.ct_item_rental_month, a.ct_keep_month, a.ct_receipt_price
                , b.it_option_subject, b.it_supply_subject, d.od_sub_id, IF(d.rf_serial != '', d.rf_serial, a.rf_serial) AS RFID, c.rt_rental_startdate, c.rt_month
		      FROM {$g5['g5_shop_rental_order_table']} AS r
		      	INNER JOIN {$g5['g5_shop_cart_table']} AS a ON r.od_id = a.od_id
		      	INNER JOIN {$g5['g5_shop_item_table']} AS b ON a.it_id = b.it_id
		      	INNER JOIN {$g5['g5_shop_rental_table']} AS c ON r.rt_id = c.rt_id
		      	INNER JOIN {$g5['g5_shop_order_item_table']} AS d ON a.ct_id = d.ct_id AND d.od_id = a.od_id
		      WHERE r.rt_id = '{$rt_id}'
              ORDER BY a.od_id, a.it_id, d.od_sub_id";

$db_order = sql_query($sql_order);
$error = "";

include_once("./claim." . $type . ".php");

if (!empty($error)) {
    $return['result'] = true;
} else {
    $return['error'] = $error;
}


return_json($return);
