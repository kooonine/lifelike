<?php
$sub_menu = '95';
include_once('./_common.php');

$s = auth_check($auth[substr($sub_menu,0,2)], 'mallAcc');
if ($s =='입력, 추가, 생성, 수정 권한이 없습니다.') die(json_encode($s));

$sql = " UPDATE sabang_mall_code SET smc_account = '$id', smc_account2 = '$id2', smc_password = '$pass1', smc_password2 = '$pass2'  WHERE smc_sb_name ='$mallName' ";
sql_query($sql);

// goto_url('./mall_account.php');
die(json_encode('success'));
return;
?>


