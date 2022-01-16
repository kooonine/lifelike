<?php
include_once('./../common.php');


$kind = $_POST['kind'];
$device = $_POST['device'];

$sql = "INSERT  lt_main_view_count
SET     vc_kind   = '".$kind."'
        ,vc_device   = '".$device."'
        ,vc_date   = '".G5_TIME_YMD."'
ON DUPLICATE KEY UPDATE
        vc_count   = vc_count+1
";
// ddd djfaksk tatatavatavs
sql_query($sql);  

$result = 'success';
die(json_encode($result, JSON_UNESCAPED_UNICODE));