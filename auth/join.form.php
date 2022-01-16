<?php
include_once './../common.php';
require_once G5_PATH . "/auth/pass.inc.php";
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$url = strip_tags($_GET['url']);
check_url_host($url);

// 이미 로그인 중이라면
if ($is_member) {
    if ($url)
        goto_url($url);
    else
        goto_url(G5_URL);
}

$login_url        = login_url($url);
$login_action_url = G5_HTTPS_BBS_URL . "/login_check.php";

$is_social_join = !empty($_POST['provider']);
$join_form_action = $is_social_join ? "/plugin/social/register_member_update.php" : G5_HTTPS_BBS_URL . "/register_form_update.php";


// PASS 인증기반 중복 가입 여부 확인
$dupinfo = $_POST['mb_dupinfo'];
$sql_dup_member = "SELECT mb_id FROM lt_member WHERE mb_dupinfo='{$dupinfo}'";
$dup_member = sql_fetch($sql_dup_member);

if (!empty($dup_member['mb_id'])) {
    // 기 가입여부 확인시 아이디 찾기 페이지로 이동
    alert("입력하신 본인확인 정보로 가입된 내역이 존재합니다.\\n아이디 찾기를 진행해주세요.", "/auth/login.account.php");
}


$display_info = array();
$display_info['hp'] = hyphen_hp_number($_POST['mb_hp']);
$display_info['birth'] = sprintf("%04d.%02d.%02d", substr($_POST['mb_birth'], 0, 4), substr($_POST['mb_birth'], 4, 2), substr($_POST['mb_birth'], 6, 2));
$display_info['sex'] = $_POST['mb_sex'] == "M" ? "남자" : "여자";

$g5_title = "회원가입";
$contents = include_once(G5_VIEW_PATH . "/auth/join.form.php");

include_once G5_LAYOUT_PATH . "/layout.php";
