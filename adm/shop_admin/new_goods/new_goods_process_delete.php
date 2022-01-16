<?php
//$sub_menu = '100310';
$sub_menu = '93';
include_once('./_common.php');

// var_dump($_FILES);
// dd($_POST);

$referer = $_SERVER["HTTP_REFERER"];

$cut_url = explode("qstr=" , $referer);


$qstr=$cut_url[1];

$ps_id = $_POST['ps_id'];

$del_ps_sql = " delete from lt_prod_schedule where ps_id = '$ps_id' ";
sql_query($del_ps_sql);
$del_jo_sql = " delete from lt_job_order where ps_id = '$ps_id' ";
sql_query($del_jo_sql);
$del_pi_sql = " delete from lt_prod_info where ps_id = '$ps_id' ";
sql_query($del_pi_sql);

$ip_item = "SELECT ps_it_name FROM lt_prod_schedule where ps_id = '$ps_id' limit 1 ";
$ip_item_name = sql_fetch($ip_item);
$del_ip_sql = " delete from lt_item_proposal where ip_it_name = '{$ip_item_name['ip_it_name']}' ";
sql_query($del_ip_sql);


// goto_url("./new_goods_process.php?tabs=list&brands=&ipgos=&dpart_ipgos=&shootings=&reorders=&sfl=it_name&stx=&sc_it_time=&limit_list=10");
goto_url("./new_goods_process.php?".$qstr);



