<?php
include_once('./_common.php');

if (!$is_member){
    alert('로그인 한 회원만 접근하실 수 있습니다.', G5_BBS_URL.'/login.php');
}

/*
if ($url)
    $urlencode = urlencode($url);
else
    $urlencode = urlencode($_SERVER[REQUEST_URI]);
*/

$url = G5_BBS_URL.'/member_leave.php';


$g5['title'] = '회원 탈퇴';
include_once('./_head.sub.php');


include_once($member_skin_path.'/member_leave.skin.php');

include_once('./_tail.sub.php');
?>
