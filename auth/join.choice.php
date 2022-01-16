<?php
include_once './../common.php';
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$url = strip_tags($_GET['url']);
check_url_host($url);

$def['mb_id'] = get_cookie("ck_mb_id");
if (!empty($def['mb_id'])) {
    $def['save_email'] = "checked";
}

$g5_title = "회원가입";
$contents = include_once(G5_VIEW_PATH . "/auth/join.choice.php");

include_once G5_LAYOUT_PATH . "/layout.php";
