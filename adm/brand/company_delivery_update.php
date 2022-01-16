<?php
$sub_menu = '92';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

check_admin_token();


$sql = " update lt_member_company
            set 
                cp_send_type = '{$_POST['cp_send_type']}',
                cp_delivery_company = '{$_POST['cp_delivery_company']}',
                cp_send_term_start = '{$_POST['cp_send_term_start']}',
                cp_send_term_end = '{$_POST['cp_send_term_end']}',
                cp_send_cost_case = '{$_POST['cp_send_cost_case']}',
                cp_send_cost_limit = '{$_POST['cp_send_cost_limit']}',
                cp_send_cost_list = '{$_POST['cp_send_cost_list']}',
                cp_send_condition = '{$_POST['cp_send_condition']}',
                cp_send_prepayment = '{$_POST['cp_send_prepayment']}',
                cp_return_zip = '{$_POST['cp_return_zip']}',
                cp_return_address1 = '{$_POST['cp_return_address1']}',
                cp_return_address2 = '{$_POST['cp_return_address2']}',
                cp_individual_costs_use = '{$_POST['cp_individual_costs_use']}',
                cp_return_costs = '{$_POST['cp_return_costs']}',
                cp_roundtrip_costs = '{$_POST['cp_roundtrip_costs']}'
        where mb_id = '{$member['mb_id']}' ";

if(false)
{
	//Test시 사용
	echo $sql;

} else {

sql_query($sql);
goto_url("./company_delivery.php");
}
?>
