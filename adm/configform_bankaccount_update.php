<?php
$sub_menu = '400100';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

check_admin_token();

//
// 영카트 default
//
$sql = " update {$g5['g5_shop_default_table']}
            set 
				de_bank_account = '{$_POST['de_bank_account']}'
                ";

if(false)
{
	//Test시 사용
	echo $sql;

} else {

sql_query($sql);
goto_url("./configform_bankaccount.php");
}
?>
