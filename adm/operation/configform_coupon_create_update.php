<?php
$sub_menu = '200180';
include_once('./_common.php');

if ($w == "u" || $w == "d")
    check_demo();

if ($w == 'd')
    auth_check($auth[substr($sub_menu,0,2)], "d");
else
    auth_check($auth[substr($sub_menu,0,2)], "w");

check_admin_token();


if ($w == "") {
    $cm_use_device = implode(",", $_POST['cm_use_device']);

    $sql_common = " cm_subject = '{$_POST['cm_subject']}',
                cm_summary = '{$_POST['cm_summary']}',
                cm_type = '{$_POST['cm_type']}',
                cm_price = '{$_POST['cm_price']}',
                cm_trunc = '{$_POST['cm_trunc']}',
                cm_maximum = '{$_POST['cm_maximum']}',
                cm_target_type = '{$_POST['cm_target_type']}',
                cm_use_device = '{$cm_use_device}',
                cm_method = '{$_POST['cm_method']}',
                cm_item_type = '{$_POST['cm_item_type']}',
                cm_category_type = '{$_POST['cm_category_type']}',
                cm_use_type = '{$_POST['cm_use_type']}',
                cm_minimum = '{$_POST['cm_minimum']}',
                cm_use_price_type = '{$_POST['cm_use_price_type']}',
                cm_duple_item_use = '{$_POST['cm_duple_item_use']}',
                cm_login_send = '{$_POST['cm_login_send']}',
                cm_sms_send = '{$_POST['cm_sms_send']}',
                cm_status = '{$_POST['cm_status']}',
                cm_weekday = '" . implode(',', $_POST['cm_weekday']) . "',
                cm_week = '" . implode(',', $_POST['cm_week']) . "',
                cm_item_it_id_list = '{$_POST['cm_item_it_id_list']}',
                cm_item_ca_id_list = '{$_POST['cm_item_ca_id_list']}',
                cm_datetime = '" . G5_TIME_YMDHIS . "'
                 ";

    if ($_POST['cm_target_type'] == '0') {
        //대상자 지정발급
        //발급시점
        $sql_common .= " ,cm_create_time = '{$_POST['cm_create_time']}' ";

        if ($_POST['cm_create_time'] == '1') {
            //지정한 시점에 발급
            $sql_common .= " ,cm_start = '{$_POST['cm_start']}' ";
        }
    } else if ($_POST['cm_target_type'] == '1') {
        //조건부 자동발급
        $sql_common .= " ,cm_target_type2 = '{$_POST['cm_target_type2']}' ";

        if ($_POST['cm_target_type2'] == '생일') {
            //생일은 즉시 발급
            $sql_common .= " ,cm_create_time = '0' ";
        } elseif ($_POST['cm_target_type2'] == '생일') {

            //발급시점
            if ($_POST['cm_create_time1'] == '1') {
                // X 일전에 발급
                $sql_common .= " ,cm_create_time = '{$_POST['cm_create_time2']}' ";
            } else {
                // 생일 당일 발급
                $sql_common .= " ,cm_create_time = '0' ";
            }
        }
    }

    //사용기간
    if ($_POST['cm_end_time'] == '1') {
        $sql_common .= " ,cm_end_time = '{$_POST['cm_end_time1']}' ";
    } else {
        $sql_common .= " ,cm_end_time = '{$_POST['cm_end_time']}' ";
    }
    //cm_end_time1

    $sql = " insert lt_shop_coupon_mng set $sql_common ";
    sql_query($sql);

    $cm_no = sql_insert_id();
} else if ($w == "u") {

    $sql_common = " cm_item_type = '{$_POST['cm_item_type']}',
                    cm_category_type = '{$_POST['cm_category_type']}',
                    cm_item_it_id_list = '{$_POST['cm_item_it_id_list']}',
                    cm_item_ca_id_list = '{$_POST['cm_item_ca_id_list']}',
                    cm_subject = '{$_POST['cm_subject']}',
                    cm_summary = '{$_POST['cm_summary']}'
                    ";

    $sql = " update lt_shop_coupon_mng set $sql_common where cm_no = '$cm_no' ";
    sql_query($sql);
} else if ($w == "d") {
    $sql = " delete from lt_shop_coupon_mng where cm_no = '$cm_no' ";
    sql_query($sql);
}

if ($w == "d") {
    goto_url('./configform_coupon_list.php');
} else {
    goto_url("./configform_coupon_detail.php?cm_no=$cm_no");
}
