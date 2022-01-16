<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/register.lib.php');

if (!$is_member) {
    goto_url('/auth/login.php?url=' . urlencode('/member/withdraw.php'));
}
$mb = get_member($member['mb_id']);
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
            if (!check_password($mb_password, $mb['mb_password'])) alert("비밀번호가 일치하지 않습니다.", "", true, true);
        }

        $_SESSION['ss_mb_id'] = $member['mb_id'];
        $_SESSION['ss_info_cert'] = $member['mb_password'];
        break;
}

if ($confirm) {
    // 진행중인 주문이 있으면 탈퇴 못함
    $sql_count_order = "SELECT COUNT(*) AS CNT FROM lt_shop_order_item WHERE ct_status IN ('결제완료','배송중','배송완료','상품준비중','반품요청','반품수거중','반품승인','교환요청','교환수거중','교환승인') AND mb_id = '{$member['mb_id']}'";
    $count_order = sql_fetch($sql_count_order);

    if ($count_order['CNT'] > 0) {
        alert("배송/반품/교환 진행중인 주문이 있습니다. 주문 완료후 탈퇴 가능합니다.");
        exit();
    }

    // 탈퇴 처리 루트
    // 입력정보 저장
    $mb_4 = implode(',', $_POST["mb_4"]);
    $mb_5 = $_POST["mb_5"];
    $sql = "UPDATE {$g5['member_table']} set mb_leave_date = '" . G5_TIME_YMD . "', mb_4 = '{$mb_4}', mb_5 = '{$mb_5}' where mb_id = '{$member['mb_id']}' ";
    sql_query($sql);

    $arr_change_data = array();
    msg_autosend('회원', '회원 탈퇴', $member['mb_id'], $arr_change_data);

    //소셜로그인 해제
    if (function_exists('social_member_link_delete')) {
        social_member_link_delete($member['mb_id']);
    }

    // 로그아웃
    unset($_SESSION['ss_mb_id']);

    alert(G5_TIME_YMD . " 탈퇴가 완료되었습니다. 이용해주셔서 감사합니다.", "/");
} elseif ($step == 0) {
    $contents = include_once(G5_VIEW_PATH . "/member.withdraw.0.php");
} else {
    $count = array();
    // 주문
    $sql_cnt_order = "SELECT COUNT(DISTINCT od.od_id) AS CNT FROM {$g5['g5_shop_order_table']} AS od LEFT JOIN {$g5['g5_shop_cart_table']} AS ct ON od.od_id = ct.od_id WHERE od.mb_id ='{$member['mb_id']}' AND ct.ct_status NOT IN ('구매완료','구매확정','주문취소','교환완료','반품완료') AND od.od_status NOT IN ('구매완료','구매확정','주문취소','교환완료','반품완료') AND (od.od_type='O' OR od.od_type='R')";
    // $sql_cnt_order = "SELECT COUNT(*) AS CNT FROM {$g5['g5_shop_order_table']} WHERE mb_id='{$member['mb_id']}' AND (od_type='O' OR od_type='R')";
    $cnt_order = sql_fetch($sql_cnt_order);
    $count['order'] = $cnt_order['CNT'];

    // 쿠폰
    $sql_count_coupon = "SELECT COUNT(*) AS CNT FROM {$g5['g5_shop_coupon_table']} WHERE mb_id = '{$member['mb_id']}' AND od_id = 0 AND cp_start <= '" . G5_TIME_YMD . "' AND cp_end >= '" . G5_TIME_YMD . "' ";
    // $sql_count_coupon = "SELECT COUNT(*) AS CNT FROM {$g5['g5_shop_coupon_table']} AS cp LEFT JOIN {$g5['g5_shop_coupon_log_table']} AS cl ON cp.cp_id=cl.cp_id WHERE cl.cl_id IS NULL AND cp.mb_id IN ( '{$member['mb_id']}', '전체회원' ) AND cp_start <= '" . G5_TIME_YMD . "' AND cp_end >= '" . G5_TIME_YMD . "' ";
    $db_count_coupon = sql_fetch($sql_count_coupon);
    $count['coupon'] = $db_count_coupon['CNT'];

    $contents = include_once(G5_VIEW_PATH . "/member.withdraw.php");
}
include_once G5_LAYOUT_PATH . "/layout.php";
