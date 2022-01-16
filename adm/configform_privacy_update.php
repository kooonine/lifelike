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
				cf_user_thirdparty_privacy_use = '{$_POST['cf_user_thirdparty_privacy_use']}',
				cf_user_thirdparty_privacy = '{$_POST['cf_user_thirdparty_privacy']}',
				cf_consent_privacy_use = '{$_POST['cf_consent_privacy_use']}',
				cf_consent_privacy = '{$_POST['cf_consent_privacy']}'
                ";

if(false)
{
	//Test시 사용
	echo $sql;

} else {

sql_query($sql);
goto_url("./configform_privacy.php");
}
?>
