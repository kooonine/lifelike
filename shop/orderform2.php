<?php
include_once('./_common.php');

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

$od_id = isset($od_id) ? preg_replace('/[^A-Za-z0-9\-_]/', '', strip_tags($od_id)) : 0;
if (!$is_member) {
    alert("잘못된 접근입니다.", G5_SHOP_URL);
}

$sql = "select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
if($is_member && !$is_admin)
    $sql .= " and mb_id = '{$member['mb_id']}' ";

$od = sql_fetch($sql);

if (!$od['od_id'] || $od['od_status'] != '제품확인') {
    alert("잘못된 접근입니다.", G5_SHOP_URL);
}

// 모바일 주문인지
$is_mobile_order = is_mobile();
$sw_direct = 1;
set_session("ss_direct", $sw_direct);

$od_id = $od['od_id'];
set_session('ss_order_id', $od_id);

$g5['title'] = '수선비용 결제';
    
if(G5_IS_MOBILE)
    include_once(G5_MSHOP_PATH.'/_head.php');
else
    include_once(G5_SHOP_PATH.'/_head.php');

// 기기별 주문폼 include
if($is_mobile_order) {
    $order_action_url = G5_HTTPS_SHOP_URL.'/orderformupdate.s2.php';
    require_once(G5_MSHOP_PATH.'/orderform.sub.s2.php');
} else {
    
    $order_action_url = G5_HTTPS_SHOP_URL.'/orderformupdate.s2.php';
    require_once(G5_SHOP_PATH.'/orderform.sub.s2.php');
}

if(G5_IS_MOBILE)
    include_once(G5_MSHOP_PATH.'/_tail.php');
else
    include_once(G5_SHOP_PATH.'/_tail.php');
?>
