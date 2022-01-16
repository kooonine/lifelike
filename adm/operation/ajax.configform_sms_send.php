<?php
$sub_menu = "200150";
include_once('./_common.php');
require_once(G5_LIB_PATH.'/Unirest.php');

//check_admin_token();

auth_check($auth[substr($sub_menu,0,2)], "w");

if(!empty($_POST)) {
    $send_phone = $_POST['send_phone'];
    $sms_recive = $_POST['sms_recive'];
    
    $sendType = $_POST['sendType'];
    $send_time = $_POST['send_time'];
    
    $dest_phone = $_POST['dest_phone'];
    $msg_type = $_POST['msg_type'];
    $msg_title = $_POST['msg_title'];
    $msg_body = $_POST['msg_body'];
    
    $message_type = "sms";
    
    $param = array(send_time => $send_time
        ,dest_phone => $dest_phone
        ,dest_name => $dest_name
        ,send_phone => $send_phone
        ,send_name => $send_name
        ,subject => $msg_title
        ,msg_body => $msg_body
    );
    
    $response = Unirest::post("http://api.apistore.co.kr/ppurio/1/message/sms/".API_STORE_ID,
        array(
            "x-waple-authorization" => API_STORE_KEY
        ),
        $param
        );
    //echo print_r2($param);
    
    //echo print_r2($response);
    $body = get_object_vars($response->body);
    
    //$body = sms_send($message_type, $send_time, $send_phone, $dest_phone, "", "", $msg_title, $msg_body, null);
    
    //echo print_r2($body);
    
    $sql = " insert into lt_sms_sendhistory
                 set sh_sendtype = '개별발송',
                    sf_type = 'sms',
                    msg_type = '{$message_type}',
                    send_time = '{$send_time}',
                    send_phone = '{$send_phone}',
                    dest_phone = '{$dest_phone}',
                    donotcall_use = '{$sms_recive}',
                    send_name = '',
                    dest_name = '',
                    msg_title = '{$msg_title}',
                    msg_body = '{$msg_body}',
                    smsExcel = '',
                    result_code = '{$body['result_code']}',
                    result_msg = '{$body['result_msg']}',
                    cmid = '{$body['cmid']}',
                    sh_datetime = '".G5_TIME_YMDHIS."'
                ";
    sql_query($sql);
    
    if($body['result_code'] == '200')
    {
        alert('메시지 발송을 완료했습니다.', './configform_sms_send.php', false);
    }
    else
    {
        alert('메시지 발송에 실패했습니다.');
    }
} else {
    alert('잘못된 접근입니다.');
}

?>