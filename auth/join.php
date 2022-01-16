<?php
include_once './../common.php';
require_once G5_PATH . "/auth/pass.inc.php";
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$url = strip_tags($_GET['url']);
check_url_host($url);

if (function_exists('social_check_login_before')) {
    $social_login_html = social_check_login_before();
}

// 이미 로그인 중이라면
if ($is_member) {
    if ($url)
        goto_url($url);
    else
        goto_url(G5_URL);
}

$login_url        = login_url($url);
$login_action_url = G5_HTTPS_BBS_URL . "/login_check.php";

// 소셜로그인 관련처리
$social_pop_once = false;
$self_url = "/auth/login.php";

//새창을 사용한다면
if (G5_SOCIAL_USE_POPUP) {
    $self_url = G5_SOCIAL_LOGIN_URL . '/popup.php';
}

$def['mb_id'] = get_cookie("ck_mb_id");
if (!empty($def['mb_id'])) {
    $def['save_email'] = "checked";
}

$g5_title = "회원가입";
$contents = include_once(G5_VIEW_PATH . "/auth/join.php");

include_once G5_LAYOUT_PATH . "/layout.php";
