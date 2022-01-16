<?php
//$sub_menu = '100310';
$sub_menu = '93';
include_once('./_common.php');

// var_dump($_FILES);
// dd($_POST);

$referer = $_SERVER["HTTP_REFERER"];

$cut_url = explode("qstr=" , $referer);


$qstr=$cut_url[1];

$jo_id = $_POST['jo_id'];


$del_jo_sql = " delete from lt_job_order where jo_id = '$jo_id' ";
sql_query($del_jo_sql);
$del_pi_sql = " delete from lt_prod_info where jo_id = '$jo_id' ";
sql_query($del_pi_sql);


// goto_url("./new_goods_process.php?tabs=list&brands=&ipgos=&dpart_ipgos=&shootings=&reorders=&sfl=it_name&stx=&sc_it_time=&limit_list=10");
goto_url("./new_goods_process.php?".$qstr);



