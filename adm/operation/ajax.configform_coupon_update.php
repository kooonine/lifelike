<?php
$sub_menu = "200180";
include_once('./_common.php');
auth_check($auth[substr($sub_menu,0,2)], "w");

if(!empty($_POST)) {
    
    if($_POST['cmd'] == 'stop')
    {
        $cm_no = $_POST['cm_no'];
        $stop_type = $_POST['stop_type'];
        $cm_status_sdate = $_POST['cm_status_sdate'];
        
        $sql = "update lt_shop_coupon_mng set ";
        
        if($stop_type == "stop") {
            $sql .= " cm_status_sdate = '".$cm_status_sdate.":00', cm_status_edate=null ";
        }
        else if($stop_type == "now") {
            $sql .= " cm_status = '발급중지' ,cm_status_sdate = '".G5_TIME_YMDHIS."', cm_status_edate=null ";
        }
        
        $sql .= "where cm_no = '{$cm_no}'";
        sql_query($sql);
        
        $result -> result = "S";
        echo json_encode_raw($result,JSON_UNESCAPED_UNICODE);
    } 
    else if($_POST['cmd'] == 'restart')
    {
        $cm_no = $_POST['cm_no'];
        $restart_type = $_POST['restart_type'];
        $cm_status_edate = $_POST['cm_status_edate'];
        
        $sql = "update lt_shop_coupon_mng set ";
        if($restart_type == "reservation") {
            $sql .= " cm_status_edate = '".$cm_status_edate.":00'";
        }
        else if($restart_type == "now") {
            $sql .= " cm_status = '발급중',cm_status_sdate =null ,cm_status_edate = null ";
        }
        
        $sql .= "where cm_no = '{$cm_no}'";
        
        sql_query($sql);
        
        $result -> result = "S";
        echo json_encode_raw($result,JSON_UNESCAPED_UNICODE);
    }
    else if($_POST['cmd'] == 'copy')
    {
        $cm_no = $_POST['cm_no'];
        
        $sql = " insert into lt_shop_coupon_mng (
                cm_subject,cm_summary,cm_type,cm_price,cm_trunc,cm_maximum,cm_target_type
                ,cm_create_time,cm_start,cm_target_type2,cm_end_time,cm_use_device
                ,cm_method,cm_item_type,cm_category_type,cm_use_type,cm_minimum,cm_use_price_type
                ,cm_duple_item_use,cm_login_send,cm_sms_send,cm_item_it_id_list,cm_item_ca_id_list,cm_status,cm_datetime
                ) 
                select 
                concat('[복사]',cm_subject) as cm_subject,cm_summary,cm_type,cm_price,cm_trunc,cm_maximum,cm_target_type
                ,cm_create_time,cm_start,cm_target_type2,cm_end_time,cm_use_device
                ,cm_method,cm_item_type,cm_category_type,cm_use_type,cm_minimum,cm_use_price_type
                ,cm_duple_item_use,cm_login_send,cm_sms_send,cm_item_it_id_list,cm_item_ca_id_list,'발급중' as cm_status,'".G5_TIME_YMDHIS."' as cm_datetime 
                from lt_shop_coupon_mng
                where cm_no = '{$cm_no}'
                ";
        
        sql_query($sql);
        $result -> result = "S";
        echo json_encode_raw($result,JSON_UNESCAPED_UNICODE);
        
    }
} else {
    $result -> result = "F";
    echo json_encode_raw($result,JSON_UNESCAPED_UNICODE);
}
?>