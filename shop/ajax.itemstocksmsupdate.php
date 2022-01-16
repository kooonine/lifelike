<?php
include_once('./_common.php');

$result = array(
    "result" => false,
    "error" => ""
);

$it_id = $_POST['item'];
$ss_hp = $_POST['phone'];

if (empty($it_id) || empty($ss_hp)) {
    $result['error'] = "EMPTY_PARAMS";
    die(json_encode($result));
}

// 상품정보
$sql = "SELECT it_id, it_name, it_soldout, it_stock_sms
        FROM {$g5['g5_shop_item_table']}
        WHERE it_id = '$it_id' ";
$it = sql_fetch($sql);

if (!$it['it_id']) {
    $result['error'] = "NOT_FOUND_ITEM";
    die(json_encode($result));
}
if ($it['it_soldout'] == 0 || $it['it_stock_sms'] == 1) {
    $result['error'] = "IMPOSSIBLE_ITEM";
    die(json_encode($result));
}
// 중복등록 체크
$sql = "SELECT count(*) AS cnt
        FROM {$g5['g5_shop_item_stocksms_table']}
        WHERE it_id = '$it_id'
        AND ss_hp = '$ss_hp'
        AND ss_send = '0' ";
$row = sql_fetch($sql);

if ($row['cnt']) {
    $result['error'] = "ALREADY_EXISTS";
    die(json_encode($result));
}

// 정보입력
$sql = "INSERT into {$g5['g5_shop_item_stocksms_table']}
        SET it_id='$it_id', ss_hp='$ss_hp', ss_ip='{$_SERVER['REMOTE_ADDR']}', ss_datetime='" . G5_TIME_YMDHIS . "'";
sql_query($sql);

$result['result'] = true;
die(json_encode($result));
