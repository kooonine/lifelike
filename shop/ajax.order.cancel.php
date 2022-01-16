<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/json.lib.php');

$result = array(
    "result" => false,
    "data" => array(),
    "error" => "",
);

if (!$member['mb_id']) {
    $result['error'] = "회원 로그인 후 이용해 주십시오.";
    die(json_encode($result));
}

if (empty($odid)) {
    $result['error'] = "올바른 방법으로 이용해 주십시오.";
    die(json_encode($result));
}

if (empty($ctid)) {
    $result['error'] = "신청하실 상품을 선택해주세요.";
    die(json_encode($result));
}

$count_order_cart = sql_fetch("SELECT COUNT(*) AS CNT FROM lt_shop_cart WHERE od_id='{$odid}' AND ct_status IN ('주문','결제완료','배송중','배송완료')");
$count_cart = $count_order_cart['CNT'];

// 부분취소용 카운터 만들어봄
$count_order_cart_piece = sql_fetch("SELECT COUNT(*) AS CNT FROM lt_shop_cart WHERE od_id='{$odid}'");
$count_cart_piece = $count_order_cart_piece['CNT'];

$count_order_cart_piece_cancel = sql_fetch("SELECT COUNT(*) AS CNT FROM lt_shop_cart WHERE od_id='{$odid}' AND ct_status IN ('주문취소','수거완료','반품수거중','반품요청')");
$count_cart_piece_cancel = $count_order_cart_piece_cancel['CNT'];

$pieceRefund = 0;

$tot_order_send = 0;
$tot_cart_price = 0;
$tot_coupon_price = 0;
$tot_return_point = 0;
$tot_item_price =0;
$tot_item_discount =0;
$tot_return_send = $action == 'return' && $free == 'false' ? $default['de_return_costs'] : 0;

$sql_order = "SELECT * FROM lt_shop_order WHERE od_id='{$odid}'";
$order = sql_fetch($sql_order);

$target_ct_id = array();
foreach ($ctid as $tmp_ct) {
    if (!in_array($tmp_ct['ct_id'], $target_ct_id)) {
        $target_ct_id[$tmp_ct['ct_id']] = $tmp_ct['qty'] * 1;
    }
}

$pieceCheck = 1;
if ($count_cart_piece==count($target_ct_id)) {
    $pieceCheck = 0;
}

foreach ($target_ct_id as $ct_id => $qty) {
    $sql_cart = "SELECT * FROM lt_shop_cart WHERE ct_id='{$ct_id}'";
    $cart = sql_fetch($sql_cart);

    $sql_item = "SELECT * FROM lt_shop_item WHERE it_id='{$cart['it_id']}'";
    $select_item = sql_fetch($sql_item);

    $tot_item_price += ($select_item['it_price'] + $select_item['it_discount_price']) * $qty;
    $tot_item_discount += $select_item['it_discount_price'] * $qty;
    
    // $pieceRefund += $cart['ct_receipt_price'];
    $pieceRefund += $cart['ct_price'] * $cart['ct_qty'] - $cart['cp_price'];
    
//--------------------------------------------
    // $tot_cart_price += $cart['ct_price'] * $cart['ct_qty'];
    $tot_cart_price += $cart['ct_price'] * $qty;

    $sql_cart_coupon = "SELECT * FROM lt_shop_coupon_log WHERE od_id='{$odid}' AND ct_id='{$ct_id}'";
    $db_coupon = sql_query($sql_cart_coupon);
    while (false != ($coupon = sql_fetch_array($db_coupon))) {
        $tot_coupon_price += $coupon['cp_price'] / $cart['ct_qty'] * $qty;
    }
}

// 할인 계산후 장바구니 쿠폰 적용여부 재계산
$sql_order_coupon = "SELECT cl.*, cl.cp_price AS real_cp_price, cp.* FROM lt_shop_coupon_log AS cl JOIN lt_shop_coupon AS cp ON cl.cp_id = cp.cp_id WHERE cl.od_id='{$odid}' AND cl.ct_id IS NULL";
$order_coupon = sql_fetch($sql_order_coupon);

$cart_coupon = round(((int) $tot_cart_price - $tot_coupon_price) / ((int) $order['od_receipt_price'] + $order['od_coupon']) * $order['od_coupon'] , 0);

$pieceCartRat = $pieceRefund / ($order['od_receipt_price'] + $order['od_receipt_point_cancel'] + $order['od_coupon_cancel'] + $order['od_send_coupon_cancel'] - ($order['od_send_cost']) );  // 해당 상품금액 / 전체 결제금액 비율구하기  // 이걸 바꺼야함
// $pieceCartRat = $pieceRefund / ($order['od_receipt_price'] + $order['od_receipt_point_cancel'] + $order['od_coupon_cancel'] + $order['od_cart_coupon_cancel'] + $order['od_send_coupon_cancel'] - ($order['od_send_cost']) );  // 해당 상품금액 / 전체 결제금액 비율구하기  // 이걸 바꺼야함
$pieceCartCoupon = round($order['od_coupon_cancel'] * $pieceCartRat,0);  // 해당 상품 깍아줄 금액
$pieceHurdle = ($order['od_receipt_price'] + $order['od_coupon_cancel']) - $pieceRefund; // 허들 값 확인
$pieceHurdleCheck = 0;
if ($order_coupon['cp_minimum'] > $pieceHurdle) {  
    $pieceCartCoupon = $order['od_coupon_cancel'];
    $pieceHurdleCheck = 1;
    // HurdleCheck

}
if($order_coupon['cp_hurdle_check'] == 1) {
    $pieceCartCoupon = 0;
}

$piecePointCheck = 0;
if ($count_cart_piece == count($target_ct_id) || $count_cart_piece == count($target_ct_id) + $count_cart_piece_cancel) { 
    $tot_return_point = $order['od_receipt_point'];
    $tot_order_send = $order['od_send_cost'];
    $pieceRefund = $pieceRefund - $pieceCartCoupon - $order['od_receipt_point_cancel'] + $order['od_send_cost'];
    $pieceTotDis = $tot_item_discount + $pieceCartCoupon + $order['od_receipt_point_cancel'] + $order['od_send_coupon_cancel'] + $tot_coupon_price;
    $piecePointCheck = 1;
} else { 
    $tot_order_send = 0;
    $pieceRefund = $pieceRefund - $pieceCartCoupon;
    $pieceTotDis = $tot_item_discount + $pieceCartCoupon + $tot_coupon_price;
}


$data = array(
    "order" => array(
        "point" => (int) $tot_return_point,
        "send" => (int) $tot_order_send
    ),
    "cancel" => array(
        "cart_coupon" => (int) $cart_coupon,
        "it_price" => (int) $tot_item_price,
        "discount" => (int) $tot_item_discount,
        "tot_discount" => (int) $tot_item_discount + $tot_coupon_price + $tot_return_point + (int) $cart_coupon,
        "price" => (int) $tot_cart_price,
        "coupon" => (int) $tot_coupon_price,
        "tot_coupon" =>  (int) $tot_coupon_price + (int) $cart_coupon,
        "refund" => (int) $tot_cart_price + $tot_order_send - $tot_coupon_price - $tot_return_send - $tot_return_point -  (int) $cart_coupon,
        "point" => (int) $tot_return_point,
        "send" => (int) $tot_return_send,
        
        //-------------------------------------------------------
        "pieceCheck" => $pieceCheck, // 부분취소 체크하는거 
        "pieceRefund" => $pieceRefund - $tot_return_send,  // 부분취소 해줄 금액
        "pieceDiscount" => $pieceTotDis, // 부분취소 물건 전체 할인 금액
        "pieceHurdleCheck" => $pieceHurdleCheck, // 허들 무너진거 체크
        "piecePointCheck" => $piecePointCheck, // 포인트 체크
        
        "pieceSend" => $order['od_send_cost'], // 부분취소 배송료 필요없어보임
        "pieceCoupon" => (int) $tot_coupon_price + $pieceCartCoupon + $tot_return_point,
        "piecetTotCoupon" =>  (int) $tot_coupon_price + (int) $pieceCartCoupon,
        "pieceCartCoupon" => (int) $pieceCartCoupon,
        "piecePoint" => (int) $tot_return_point,
        "picecSend" => (int) $tot_return_send,
    )
);

$result['result'] = true;
$result['data'] = $data;

die(json_encode($result));
