<?php
$sub_menu = '400100';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

check_admin_token();

//
// 영카트 default
//
$sql = " update {$g5['config_table']}
            set 
				cf_stipulation = '{$_POST['cf_stipulation']}',
				cf_privacy = '{$_POST['cf_privacy']}',
				cf_user_privacy = '{$_POST['cf_user_privacy']}',
				cf_collection_privacy = '{$_POST['cf_collection_privacy']}',
				cf_contract_cancel_use = '{$_POST['cf_contract_cancel_use']}',
				cf_contract_cancel = '{$_POST['cf_contract_cancel']}'
                ";

if(false)
{
	//Test시 사용
	echo $sql;

} else {

sql_query($sql);
goto_url("./configform_stipulation.php");
}
?>
