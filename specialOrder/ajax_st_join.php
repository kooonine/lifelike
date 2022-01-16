<?php
include_once('./_common.php');

$sq_sql = "SELECT SUBSTR(st_code,-4) AS CNT FROM b2b_store_list  WHERE cp_code = '{$_POST['cp_code']}' ORDER BY st_code DESC LIMIT 1 " ; 
$sq_res = sql_fetch($sq_sql); 

$sqens  = (intval($sq_res['CNT']) + 1);

$st_code = $_POST['cp_code'] . sprintf('%04d',$sqens); 

$st_password  = get_encrypt_string($_POST['password']);

$st_number = $_POST['st_num1'] .'-'.$_POST['st_num2'] .'-'.$_POST['st_num3'];
$st_tel = $_POST['st_tel1'] .'-'.$_POST['st_tel2'] .'-'.$_POST['st_tel3'] ;

$sql_common = "";

$sql_common .= "cp_name = '{$_POST['cp_name']}' , ";
$sql_common .= "cp_code = '{$_POST['cp_code']}' , ";
$sql_common .= "st_name = '{$_POST['st_name']}' , ";
$sql_common .= "st_code = '{$st_code}' , ";
$sql_common .= "reg_date = now() , ";
$sql_common .= "st_owner = '{$_POST['st_owner']}' , ";
$sql_common .= "st_number = '{$st_number}' , ";
$sql_common .= "st_password = '{$st_password}' , ";
$sql_common .= "st_tel = '{$st_tel}' , ";
$sql_common .= "st_zip = '{$_POST['st_zip']}' , ";
$sql_common .= "st_addr1 = '{$_POST['st_addr1']}' , ";
$sql_common .= "st_addr2 = '{$_POST['st_addr2']}' , ";
$sql_common .= "st_comform = 'N' , ";
$sql_common .= "st_shutdown = 'N'  ";

$add_st_sql = "INSERT INTO b2b_store_list SET ". $sql_common ;
sql_query($add_st_sql);

goto_url("./main.php");







