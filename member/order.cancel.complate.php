<?php
include_once('./_common.php');
require_once(G5_SHOP_PATH . '/settle_lg.inc.php');

if (!$is_member) {
    goto_url('/auth/login.php?url=' . urlencode('/member/order.php'));
}

$od_id = isset($od_id) ? preg_replace('/[^A-Za-z0-9\-_]/', '', strip_tags($od_id)) : 0;
$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

if (!empty($od_id)) {
    $sql_order = "SELECT * FROM {$g5['g5_shop_order_table']} WHERE mb_id = '{$member['mb_id']}' AND od_id='{$od_id}'";
    $od = sql_fetch($sql_order);

    if (!$od['od_id'] || !$is_member) {
        alert("조회하실 주문서가 없습니다.", "/");
    }

    $page_prefix = "주문취소가";
    switch ($action) {
        case "return":
            $page_prefix = "반품요청이";
            break;
        case "change":
            $page_prefix = "교환요청이";
            break;
    }

    $page_title = "취소완료";
    switch ($action) {
        case "return":
            $page_title = "반품요청완료";
            break;
        case "change":
            $page_title = "교환요청완료";
            break;
    }

    $contents = include_once(G5_VIEW_PATH . "/member.order.cancel.complate.php");
}

include_once G5_LAYOUT_PATH . "/layout.php";
