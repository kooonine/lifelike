<?php
$sub_menu = "200820";
include_once('./_common.php');

check_demo();

auth_check($auth[substr($sub_menu,0,2)], 'd');
// 관리자 하드코딩 추가
if ($is_admin != 'super' && $member['mb_id'] != 'dmswls0505')
    alert('최고관리자만 접근 가능합니다.');

$pass = trim($_POST['pass']);

if (!$pass)
    alert('관리자 비밀번호를 입력해 주십시오.');

// 관리자 비밀번호 비교
$admin = get_admin('super');
if (!check_password($pass, $admin['mb_password']))
    alert('관리자 비밀번호가 일치하지 않습니다.');

$sql_in_mb = "SELECT mb_id,mb_password FROM lt_member WHERE mb_id='{$mb_id}'";
$in_mb = sql_fetch($sql_in_mb);

if (empty($in_mb['mb_password']))
    alert('사용자를 찾을 수 없습니다.', '', true, true);

session_unset(); // 모든 세션변수를 언레지스터 시켜줌
session_destroy(); // 세션해제함

$key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['SERVER_SOFTWARE'] . $_SERVER['HTTP_USER_AGENT'] . $in_mb['mb_password']);
set_cookie('ck_mb_id', $in_mb['mb_id'], 600);
set_cookie('ck_auto', $key, 600);
?>

<a href="/auth/login.php"><button>로그인</button></a>