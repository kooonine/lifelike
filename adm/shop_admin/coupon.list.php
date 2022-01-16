<?php
$sub_menu = '400810';
include_once('./_common.php');

if (!$is_member) die("ACCESS DINIED");

$sql = "SELECT cp_id FROM {$g5['g5_shop_coupon_table']} WHERE cz_id='{$cz_id}' AND mb_id=''";
$db_coupon = sql_query($sql);

while (false != ($coupon = sql_fetch_array($db_coupon))) {
    echo $coupon['cp_id'] . "<br>";
}
