<?php
include_once './../common.php';
require_once G5_PATH . "/auth/pass.inc.php";
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$url = strip_tags($_GET['url']);
check_url_host($url);

// 인증 이후 처리
if (!empty($mb_dupinfo)) {
    $mb = get_member($mb_id);
    $mkey = md5($mb['mb_id'] . $mb['mb_ip'] . $mb['mb_datetime']);
    if ($token == $mkey) {
        $sql_member_update = "UPDATE lt_member SET
                              mb_certify='{$_POST["mb_certify"]}',
                              mb_dupinfo='{$_POST["mb_dupinfo"]}',
                              mb_sex='{$_POST["mb_sex"]}',
                              mb_id='{$_POST["mb_id"]}',
                              mb_name='{$_POST["mb_name"]}',
                              mb_hp='{$_POST["mb_hp"]}',
                              mb_birth='{$_POST["mb_birth"]}',
                              ='{$_POST["mb_sms"]}',
                              mb_mailling='{$_POST["mb_mailling"]}',
                              mb_10='{$_POST["mb_10"]}'
                              WHERE mb_id='{$_POST['mb_id']}'";

        sql_query($sql_member_update);

        // goto login with url
        alert("인증정보가 업데이트 되었습니다. 다시 로그인 해주세요.", "/auth/login.php?url=" . $url);
    } else {
        alert("데이터 변조가 감지되었습니다. 다시 시도해주세요.");
    }
}

$g5_title = "로그인";
$contents = include_once(G5_VIEW_PATH . "/auth/login.pass.php");

include_once G5_LAYOUT_PATH . "/layout.php";
