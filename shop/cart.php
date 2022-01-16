<?
include_once('./_common.php');
include_once(G5_SHOP_PATH . '/settle_naverpay.inc.php');

if (!$is_member) goto_url("/auth/login.php");

// 보관기간이 지난 상품 삭제
cart_item_clean();

// cart id 설정
set_cart_id($sw_direct);
set_session("ss_direct", $sw_direct);

$s_cart_id = get_session('ss_cart_id');
// 선택필드 초기화
$sql = " update {$g5['g5_shop_cart_table']} set ct_select = '0' where od_id = '$s_cart_id' ";
sql_query($sql);

$cart_action_url = G5_SHOP_URL . '/cartupdate.php';

$g5['title'] = '장바구니';
if (!$od_type) $od_type = "O";

// $s_cart_id 로 현재 장바구니 자료 쿼리
$sql = "SELECT a.ct_id,
a.it_id,
a.it_name,
a.ct_price,
a.ct_point,
a.ct_qty,
a.ct_status,
a.ct_send_cost,
a.it_sc_type,
a.ct_rental_price,
a.ct_item_rental_month,
a.ct_option,
a.io_price,
a.io_type,
b.it_price,
b.it_discount_price,
b.ca_id,
b.it_item_type,
b.it_brand,
b.it_stock_qty,
b.it_soldout,
c.io_hoching
FROM {$g5['g5_shop_cart_table']} AS a LEFT JOIN {$g5['g5_shop_item_table']} AS b ON ( a.it_id = b.it_id ) LEFT JOIN {$g5['g5_shop_item_option_table']} AS c ON ( a.it_id=c.it_id AND a.io_sapcode_color_gz = c.io_sapcode_color_gz )
WHERE a.od_id = '$s_cart_id' AND a.od_type = '$od_type' ";

$sql .= " ORDER BY a.it_sc_type, a.it_id ";

$result = sql_query($sql);
$cart_count = sql_num_rows($result);
$contents = include_once(G5_VIEW_PATH . "/cart.list.php");
include_once G5_LAYOUT_PATH . "/layout.php";
