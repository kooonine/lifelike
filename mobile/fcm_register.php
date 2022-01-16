<?php
include_once('../common.php');

if($_REQUEST["fcmToken"])
{
    $token = clean_xss_tags(trim($_REQUEST["fcmToken"]));
    $device = clean_xss_tags(trim($_REQUEST["device"])); //android, ios
    $userinfo = clean_xss_tags(trim($_REQUEST["userinfo"])); 
    $notiCheck = clean_xss_tags(trim($_REQUEST["notiCheck"]));
    $sql = "INSERT  lt_app_users
    SET     token   = '".$token."'
            ,device   = '".$device."'
            ,userinfo   = '".$userinfo."'
            ,regdate   = '".G5_TIME_YMDHIS."'
    ON DUPLICATE KEY UPDATE
            token   = '".$token."'
            ,device   = '".$device."'
            ,userinfo   = '".$userinfo."'
            ,updatedate   = '".G5_TIME_YMDHIS."'
    ";
    if ($device == 'ios') {
        $sql = "INSERT  lt_app_users
        SET     token   = '".$token."'
                ,device   = '".$device."'
                ,userinfo   = '".$userinfo."'
                ,push_check   = 1
                ,regdate   = '".G5_TIME_YMDHIS."'
        ON DUPLICATE KEY UPDATE
                token   = '".$token."'
                ,device   = '".$device."'
                ,userinfo   = '".$userinfo."'
                ,push_check   = '".$notiCheck."'
                ,updatedate   = '".G5_TIME_YMDHIS."'
        ";
    } 

    $result = sql_query($sql, false);
    
    if($result){
        echo json_encode(array("result" => "S", "fcmToken" => $token));
    } else {
        echo json_encode($result = array("result" => "F", "fcmToken" => $token, "resultMsg" => mysql_error()));
    }
}
?>
