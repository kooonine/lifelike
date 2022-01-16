<?php
include_once './../common.php';
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$url = strip_tags($_GET['url']);
check_url_host($url);

$g5_title = "회원가입";
$contents = include_once(G5_VIEW_PATH . "/auth/join.complate.php");

include_once G5_LAYOUT_PATH . "/layout.php";
