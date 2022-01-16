<?php
include_once './../common.php';
require_once G5_PATH . "/auth/pass.inc.php";
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$si = isset($si) ? $si : 0;
$sp = isset($sp) ? $sp : 0;

$active = isset($_GET['active']) ? $_GET['active'] : "i";

if ($active == "i") {
    $btnActiveId = "active";
} else {
    $btnActivePw = "active";
}

$validation = array();

if ($active == "i") {
    switch ($si) {
        case 1:
            $mb_dupinfo = trim($_POST['mb_dupinfo']);
            $sql_member = "SELECT mb_id, mb_datetime, mb_email FROM {$g5['member_table']} WHERE mb_dupinfo='{$mb_dupinfo}'";
            $mb = sql_fetch($sql_member);

            if (empty($mb['mb_id'])) {
                $si = 2;
            } else {
                $sql_check_social_member = "SELECT * FROM lt_member_social_profiles WHERE mb_id = '{$mb['mb_id']}'";
                $member_social = sql_fetch($sql_check_social_member);
                $is_social_login = !empty($member_social['mb_id']);
            }
            break;
        default:
            break;
    }
} else {
    switch ($sp) {
        case 1:
            $mb_dupinfo = trim($_POST['mb_dupinfo']);
            $sql_member = "SELECT mb_id, mb_datetime FROM {$g5['member_table']} WHERE mb_dupinfo='{$mb_dupinfo}'";
            $mb = sql_fetch($sql_member);
            $check = $mb_id == $mb['mb_id'];
            if (empty($check)) $validation[] = "form-mb-id";
            break;
        case 2:
            if (!is_valid_password($mb_password)) {
                $validation[] = "form-mb-password";
            }
            if ($mb_password != $mb_password_re) {
                $validation[] = "form-mb-password-re";
            }

            if (empty($validation)) {
                $mb_dupinfo = trim($_POST['dupinfo']);
                $re_password = get_encrypt_string($mb_password);
                $sql_re_password = "UPDATE {$g5['member_table']} SET mb_password='{$re_password}' WHERE mb_dupinfo='{$mb_dupinfo}'";
                $pass_result = sql_query($sql_re_password);
            }

            break;
        default:
            break;
    }
}

// 폼 데이터 체크. 오류있으면 뒤로가기
if (!empty($validation)) {
    set_session("fv", json_encode($validation));
    include_once G5_VIEW_PATH . "/back.php";
}

$sub_id = include_once(G5_VIEW_PATH . "/auth/login.account.id" . $si . ".php");
$sub_ps = include_once(G5_VIEW_PATH . "/auth/login.account.pw" . $sp . ".php");

$contents = include_once(G5_VIEW_PATH . "/auth/login.account.php");

include_once G5_LAYOUT_PATH . "/layout.php";
set_session("fv", "[]");
