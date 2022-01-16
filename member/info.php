<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/register.lib.php');
if (!$is_member) {
    goto_url('/auth/login.php?url=' . urlencode('/member/info.php'));
}

$sql_check_social_member = "SELECT * FROM lt_member_social_profiles WHERE mb_id = '{$member['mb_id']}'";
$member_social = sql_fetch($sql_check_social_member);

$is_social_login = !empty($member_social['mb_id']);

$step = isset($step) ? $step : 0;
if ($step == 0 && $_SESSION['ss_info_cert'] == $member['mb_password'] || $is_social_login) $step = 1;

switch ($step) {
    case 1:
        if ($_SESSION['ss_info_cert'] == false && $is_social_login == false) {
            $mb_password = trim($_POST['mb_password']);
            $msg = valid_mb_password($mb_password);
            if ($msg = valid_mb_password($mb_password)) alert($msg, "", true, true);
            if (!check_password($mb_password, $member['mb_password'])) alert("비밀번호가 일치하지 않습니다.", "", true, true);
        }

        include_once G5_PATH . "/auth/pass.inc.php";
        $_SESSION['ss_mb_id'] = $member['mb_id'];
        $_SESSION['ss_info_cert'] = $member['mb_password'];
        break;
}

$bank_set = array(
    "bank" => array(
        "산업",
        "기업",
        "국민",
        "외환",
        "수협",
        "수출입",
        "신농협중앙",
        "JP모건",
        "SC",
        "농협중앙",
        "농협",
        "축협중앙",
        "우리",
        "신한",
        "시티",
        "HSBC",
        "SC제일",
        "대구",
        "부산",
        "충청",
        "광주",
        "제주",
        "경기",
        "전북",
        "강원",
        "경남",
        "충북",
        "새마을금고",
        "삼림조합",
        "우체국",
        "신용보증",
        "KEB하나",
        "케이뱅크",
        "카카오뱅크",
        "신협",
        "도이치뱅크",
        "상호저축은행",
        "신용협동조합",
        "BOA"
    ),
    "cma" => array(
        "현대증권",
        "미래에셋증권",
        "한국투자증권",
        "우리투자증권",
        "하이투자증권",
        "HMC투자증권",
        "SK증권",
        "대신증권",
        "하나대투증권",
        "신한금융증권",
        "동부증권",
        "유진투자증권",
        "메리츠증권",
        "신영증권",
        "한화증권",
        "삼성증권",
        "대우증권",
        "유안타증권",
        "동양종합증권"
    ),
);

$contents = include_once(G5_VIEW_PATH . "/member.info." . $step . ".php");
include_once G5_LAYOUT_PATH . "/layout.php";
