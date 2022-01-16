<?php
//$sub_menu = '100310';
$sub_menu = '93';
include_once('./_common.php');

// var_dump($_FILES);
// dd($_POST);

$ps_id = $_POST['ps_id'];


$sql_common ="ps_shooting_yn  = 'Y'";


$sql = " update lt_prod_schedule set $sql_common where ps_id = '$ps_id' ";
sql_query($sql);


goto_url("./new_goods_process.php?tabs=list");
