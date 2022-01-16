<?
include_once('./_common.php');

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

// 주문상품 재고체크 js 파일
add_javascript('<script src="'.G5_JS_URL.'/shop.order.js"></script>', 0);

if(!$od_type) $od_type = "O";

// 모바일 주문인지
$is_mobile_order = is_mobile();

$g5['title'] = '위약금 납부';

$order_action_url = G5_HTTPS_SHOP_URL.'/orderformupdate.out.r.php';

// 기기별 주문폼 include
if($is_mobile_order) {
    
    include_once(G5_MSHOP_PATH.'/_head.php');
	require_once(G5_MSHOP_PATH.'/orderform.out.r.php');
	include_once(G5_MSHOP_PATH.'/_tail.php');
	
} else {
    include_once(G5_SHOP_PATH.'/_head.php');
	require_once(G5_SHOP_PATH.'/orderform.out.r.php');
	include_once(G5_SHOP_PATH.'/_tail.php');
}

?>
