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
				de_user_reg_info = '{$_POST['de_user_reg_info']}'
				,de_order_info = '{$_POST['de_order_info']}'
				,de_pay_info = '{$_POST['de_pay_info']}'
				,de_shipping_info = '{$_POST['de_shipping_info']}'
				,de_exchange_info = '{$_POST['de_exchange_info']}'
				,de_refund_info = '{$_POST['de_refund_info']}'
				,de_point_info = '{$_POST['de_point_info']}'
				,de_baesong_content_use = '{$_POST['de_baesong_content_use']}'
				,de_baesong_content = '{$_POST['de_baesong_content']}'
                ";

if(false)
{
	//Test시 사용
	echo $sql;

} else {

sql_query($sql);
goto_url("./configform_etc.php");
}
?>
