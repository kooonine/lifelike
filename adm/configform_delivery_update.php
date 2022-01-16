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
                de_send_type = '{$_POST['de_send_type']}',
                de_delivery_company = '{$_POST['de_delivery_company']}',
                de_send_term_start = '{$_POST['de_send_term_start']}',
                de_send_term_end = '{$_POST['de_send_term_end']}',
                de_send_cost_case = '{$_POST['de_send_cost_case']}',
                de_send_cost_limit = '{$_POST['de_send_cost_limit']}',
                de_send_cost_list = '{$_POST['de_send_cost_list']}',
                de_send_condition = '{$_POST['de_send_condition']}',
                de_send_prepayment = '{$_POST['de_send_prepayment']}',
                de_return_zip = '{$_POST['de_return_zip']}',
                de_return_address1 = '{$_POST['de_return_address1']}',
                de_return_address2 = '{$_POST['de_return_address2']}',
                de_individual_costs_use = '{$_POST['de_individual_costs_use']}',
                de_tracking_api = '{$_POST['de_tracking_api']}',
                de_tracking_api_company = '{$_POST['de_tracking_api_company']}',
                de_tracking_api_key = '{$_POST['de_tracking_api_key']}',
                de_return_costs = '{$_POST['de_return_costs']}',
                de_roundtrip_costs = '{$_POST['de_roundtrip_costs']}'
                ";

if (false) {
    //Test시 사용
    echo $sql;
} else {

    sql_query($sql);
    goto_url("./configform_delivery.php");
}
