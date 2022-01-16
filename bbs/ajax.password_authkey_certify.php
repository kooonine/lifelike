<?php
include_once('./_common.php');
header('Content-Type: application/json');
// 봇의 메일 링크 크롤링을 방지합니다.
if(function_exists('check_mail_bot')){ check_mail_bot($_SERVER['REMOTE_ADDR']); }

// 오류시 공히 Error 라고 처리하는 것은 회원정보가 있는지? 비밀번호가 틀린지? 를 알아보려는 해킹에 대비한것

$mb_no = trim($_POST['mb_no']);
$mb_nonce = trim($_POST['mb_nonce']);
$auth_key = trim($_POST['auth_key']);

// 회원아이디가 아닌 회원고유번호로 회원정보를 구한다.
$sql = " select mb_id, mb_lost_certify from {$g5['member_table']} where mb_no = '$mb_no' ";
$view_text = '';
$mb  = sql_fetch($sql);
if (strpos($mb['mb_lost_certify'],$auth_key) == 0) {
    $view_text .= '<script>';
    $view_text .= 'alert("인증번호 불일치")';
    $view_text .= '</script>';
    $result = array("result" => "F", "view_text" => $view_text);
} else {
    
    $result = array("result" => "S");
}
$output =  json_encode($result);
// 출력
echo  urldecode($output);
?>
