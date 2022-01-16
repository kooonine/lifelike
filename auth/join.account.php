<?php
include_once './../common.php';
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

$contents = include_once(G5_VIEW_PATH . "/auth/login.findaccount.php");

include_once G5_LAYOUT_PATH . "/layout.php";
