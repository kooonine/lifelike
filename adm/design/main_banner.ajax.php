<?php
include_once('./_common.php');


$contents = $_POST['contents'];

foreach($contents as $cont) {
    $bannerSql = "UPDATE lt_banner_new SET ba_sequence = '{$cont['i']}' WHERE cp_id = '{$cont['cpId']}'";
    $bannerResult = sql_query($bannerSql);
}

?>

