<?php
include_once('./_common.php');
header('Content-Type: application/json');
// 봇의 메일 링크 크롤링을 방지합니다.
if(function_exists('check_mail_bot')){ check_mail_bot($_SERVER['REMOTE_ADDR']); }

// 오류시 공히 Error 라고 처리하는 것은 회원정보가 있는지? 비밀번호가 틀린지? 를 알아보려는 해킹에 대비한것

$auth_phoneNumber = trim($_POST['auth_phoneNumber']);
$auth_key = trim($_POST['auth_key']);

$auth_phoneNumber = preg_replace("/[^0-9]/", "", $auth_phoneNumber);

if(strlen($auth_key) != 6){
    $view_text .= '<script>';
    $view_text .= 'alert("인증번호 불일치")';
    $view_text .= '</script>';
    $result = array("result" => "F", "view_text" => $view_text);
}else{

    // 회원아이디가 아닌 회원고유번호로 회원정보를 구한다.
    $sql = " select msg_body from lt_sms_sendhistory where dest_phone = '$auth_phoneNumber' and sh_datetime < now() and sh_datetime > DATE_SUB(now(), INTERVAL 3 MINUTE) order by sh_no desc limit 1";
    $view_text = '';
    $mb  = sql_fetch($sql);
    if (strpos($mb['msg_body'],$auth_key) == 0) {
        $view_text .= '<script>';
        $view_text .= 'alert("인증번호 불일치")';
        $view_text .= '</script>';
        $result = array("result" => "F", "view_text" => $view_text);
    } else {
        
        $view_text .= '<script>';
        $view_text .= 'alert("인증이 완료되었습니다.")';
        $view_text .= '</script>';
        $result = array("result" => "S", "view_text" => $view_text);
    }
}
$output =  json_encode($result);
// 출력
echo  urldecode($output);
?>
