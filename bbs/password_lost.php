<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');

if ($is_member) {
    alert("이미 로그인중입니다.");
}

$g5['title'] = '회원정보 찾기';
include_once(G5_PATH.'/head.sub.php');

$stage1_url = G5_HTTPS_BBS_URL."/ajax.password_lost.php";
$stage3_url = G5_HTTPS_BBS_URL."/ajax.password_authkey_certify.php";
include_once($member_skin_path.'/password_lost.skin.php');

include_once(G5_PATH.'/tail.sub.php'); 
?>