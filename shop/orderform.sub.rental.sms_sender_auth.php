<?php
include_once('./_common.php');
require_once(G5_LIB_PATH.'/Unirest.php');

$auth_phoneNumber = $_POST['auth_phoneNumber'];
$mb_name = urldecode($_POST['name']);

header('Content-Type: application/json');
if($auth_phoneNumber == '' ){
    $view_text = '<script>';
    $view_text .='alert("모든 정보를 입력해주세요")';
    $view_text .='</script>';
    $result = array("result" => "F", "view_text" => $view_text);
    $output =  json_encode($result);
    
    // 출력
    echo  urldecode($output);
    
}else {
    // 임시비밀번호 발급
    $auth_key = rand(100000, 999999);
    // 어떠한 회원정보도 포함되지 않은 일회용 난수를 생성하여 인증에 사용
    $mb_nonce = md5(pack('V*', rand(), rand(), rand(), rand()));
    
    $sql  = " select  * from lt_sms_form where   sf_cate = '회원' and sf_title = '본인확인 인증번호 발송' and sf_user_use = 1 and sf_type = 'sms' ";
    $sms_msg = sql_fetch($sql);
    $msg = $sms_msg['sf_user_msg'];
    
    $msg = str_replace('{NAME}',$mb_name ,$msg);
    $msg = str_replace('{인증번호}',$auth_key,$msg);
    
    $send_phone = $default['de_sms_hp'];
    $send_phone = preg_replace("/[^0-9]/", "", $send_phone);
    
    $sms_recive = 'except';
    
    $send_time = '';
    
    $dest_phone = $auth_phoneNumber;
    $dest_phone = preg_replace("/[^0-9]/", "", $dest_phone);
    
    $msg_title = "[".$config['cf_title']."]";
    $msg_body = $msg;
    
    $message_type = 'sms';
    
    $param = array('send_time' => $send_time
        ,'dest_phone' => $dest_phone
        ,'dest_name' => $mb_name
        ,'send_phone' => $send_phone
        ,'send_name' => '시스템'
        ,'subject' => $msg_title
        ,'msg_body' => $msg_body
    );
    
    $response = Unirest::post("http://api.apistore.co.kr/ppurio/1/message/sms/".API_STORE_ID,
        array(
            "x-waple-authorization" => API_STORE_KEY
        ),
        $param
        );
    
    //echo print_r2($response);
    $body = get_object_vars($response->body);
    
    //$body = sms_send($message_type, $send_time, $send_phone, $dest_phone, "", "", $msg_title, $msg_body, null);
    
    //echo print_r2($body);
    
    
    $sql = " insert into lt_sms_sendhistory
         set sh_sendtype = '자동발송',
            sf_type = 'sms',
            msg_type = '{$message_type}',
            send_time = '{$send_time}',
            send_phone = '{$send_phone}',
            dest_phone = '{$dest_phone}',
            donotcall_use = '{$sms_recive}',
            send_name = '',
            dest_name = '',
            msg_title = '{$msg_title}',
            msg_body = '$mb_nonce $auth_key',
            smsExcel = '',
            result_code = '{$body['result_code']}',
            result_msg = '{$body['result_msg']}',
            cmid = '{$body['cmid']}',
            sh_datetime = '".G5_TIME_YMDHIS."'
        ";
    sql_query($sql);
    
    if($body['result_code'] == '200')
    {
        
        $view_text .= '<script>';
        $view_text .= 'alert("인증번호가 고객님 휴대전화번호로 발송 되었습니다.")';
        $view_text .= '</script>';
        $result = array("result" => "S", "view_text" => $view_text);
    }
    else
    {
        $view_text .= '<script>';
        $view_text .= 'alert("인증번호 발송을 실패하였습니다.")';
        $view_text .= '</script>';
        $result = array("result" => "F", "view_text" => $view_text);
    }
    
    
    $output =  json_encode($result);
    // 출력
    echo  urldecode($output);
    
}?>