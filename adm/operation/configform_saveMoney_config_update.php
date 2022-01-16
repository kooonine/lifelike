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
                de_point_days = '{$_POST['de_point_days']}',
                de_point_name = '{$_POST['de_point_name']}',
                de_point_unit = '{$_POST['de_point_unit']}',
                de_settle_point_unit = '{$_POST['de_settle_point_unit']}',
                de_settle_point_unit_type = '{$_POST['de_settle_point_unit_type']}',
                de_point_display_type = '{$_POST['de_point_display_type']}',
                
                de_prd_price_for_save_type = '{$_POST['de_prd_price_for_save_type']}',
                de_point_percent = '{$_POST['de_point_percent']}',
                de_point_use_standard = '{$_POST['de_point_use_standard']}',
                de_point_use_standard_unit = '{$_POST['de_point_use_standard_unit']}',
                de_point_use_standard_unit_type = '{$_POST['de_point_use_standard_unit_type']}',

                de_use_product_max_point = '{$_POST['de_use_product_max_point']}',
                de_settle_min_point = '{$_POST['de_settle_min_point']}',
                de_use_max_point_type = '{$_POST['de_use_max_point_type']}',
                de_settle_max_point = '{$_POST['de_settle_max_point']}',
                de_use_point_min_price = '{$_POST['de_use_point_min_price']}',
                de_use_point_max_price = '{$_POST['de_use_point_max_price']}',
                de_use_except_item_limit_use = '{$_POST['de_use_except_item_limit_use']}',
                de_use_except_it_id_list = '{$_POST['de_use_except_it_id_list']}',
                de_use_except_ca_limit_use = '{$_POST['de_use_except_ca_limit_use']}',
                de_use_except_ca_id_list = '{$_POST['de_use_except_ca_id_list']}'
                ";

if (false) {
    //Test시 사용
    echo $sql;
} else {

    sql_query($sql);
}

$sql = " update {$g5['config_table']}
            set
                cf_point_term = '{$_POST['cf_point_term']}',
                cf_register_point = '{$_POST['cf_register_point']}',
                cf_use_recommend = '{$_POST['cf_use_recommend']}',
                cf_recommend_point = '{$_POST['cf_recommend_point']}',
                               
                cf_review_write_point = '{$_POST['cf_review_write_point']}',
                cf_review_photo_point = '{$_POST['cf_review_photo_point']}',
                cf_review_first_point = '{$_POST['cf_review_first_point']}',
                cf_extra_point_1 = '{$_POST['cf_extra_point_1']}',
                cf_extra_point_2 = '{$_POST['cf_extra_point_2']}',
                cf_extra_point_3 = '{$_POST['cf_extra_point_3']}',
                cf_install_point = '{$_POST['cf_install_point']}'
                ";

if (false) {
    //Test시 사용
    echo $sql;
} else {

    sql_query($sql);
    goto_url("./configform_saveMoney_config.php");
}
