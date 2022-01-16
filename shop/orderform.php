<?
include_once('./_common.php');
include_once(G5_LIB_PATH . '/coupon.lib.php');

if (!$is_member) {
	goto_url('/auth/login.php?url=' . urlencode($_SERVER['HTTP_REFERER']));
}

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

// 주문상품 재고체크 js 파일
add_javascript('<script src="' . G5_JS_URL . '/shop.order.js"></script>', 0);

if (!$od_type) $od_type = "O";

$sw_direct = preg_replace('/[^a-z0-9_]/i', '', $sw_direct);
// 모바일 주문인지
$is_mobile_order = is_mobile();

// 바로구매여부 확인
set_session("ss_direct", $sw_direct);
if ($sw_direct) {
	$tmp_cart_id = get_session('ss_cart_direct');
} else {
	$tmp_cart_id = get_session('ss_cart_id');
}

// 장바구니가 비어있는가?
if (get_cart_count($tmp_cart_id) == 0)
	alert('장바구니가 비어 있습니다.', G5_SHOP_URL . '/cart.php');

// 새로운 주문번호 생성
$od_id = get_shop_uniqid();

set_session('ss_order_id', $od_id);
$s_cart_id = $tmp_cart_id;
if ($default['de_pg_service'] == 'inicis' || $default['de_inicis_lpay_use'])
	set_session('ss_order_inicis_id', $od_id);

$g5['title'] = '주문서 작성';
if ($od_type == "R") {
	$g5['title'] = '리스 계약서 작성';
}

// 희망배송일 지정
if ($default['de_hope_date_use']) {
	include_once(G5_PLUGIN_PATH . '/jquery-ui/datepicker.php');
}
$proCheck = 0;
$order_items = array();
function wedding($member_coupons,$capaign_coupons,$mbId) {
	global $order_items;
	$libCoupon = new coupon();
	$coupons2 = array();
	global $proCheck;
	foreach ($capaign_coupons as $cc) { 
		$proCheck = 0;
		$itemCheck = 0;
		$itemSet = json_decode($cc['cp_item_set'],true);

	}
	// foreach ($member_coupons as $mc) {
	// 	$proCheck = 0;
	// 	$itemCheck = 0;

	// 	if ($mc['cp_promotion'] != 0) {
	// 		$cp_promotion = $mc['cp_promotion'];
	// 		$promotionSql = "SELECT cp_item_set from lt_campaign WHERE cp_id = $cp_promotion";
	// 		$promotionResult = sql_fetch($promotionSql);
	// 		$itemSet = json_decode($promotionResult['cp_item_set'],true);

	foreach ($capaign_coupons as $cc) { 
		$proCheck = 0;
		$itemCheck = 0;
		$itemSet = json_decode($cc['cp_item_set'],true);

		for($i=1; $i<count($itemSet)+1; $i++) {
			if ($proCheck == 1) break;
			$itemCount = $itemSet[$i]['number'];
			$itemCount_check = $itemSet[$i]['number'];
			$itemId = $itemSet[$i]['item'];
			$itemIdArr = explode( ',', $itemId );
			$orderCount = 0;
			foreach ($order_items as $oi) {  
				$orderCount ++;
				if ($proCheck == 1) break;
				$searchId = $oi['it_id'];
				$searchQty = $oi['ct_qty'];
				$searchRes = array_search($searchId,$itemIdArr);
				if ($searchRes===0 || $searchRes!=false) {
					$itemCount -= $searchQty;
				}
				if ($itemCount <= 0) {
					$itemCheck++;
					if($itemCheck==count($itemSet)) {
						$proCheck=2;
					}
					break;
				}
				if ($orderCount == count($order_items)) { 
					// 쿠폰 하나 실패로 가야함!!!
					$proCheck == 1;
					break;
				}
			}
		}
		if ($proCheck==2) {
			$sczSql = "SELECT * FROM lt_shop_coupon_zone WHERE cp_promotion = '{$cc['cp_id']}' limit 1";
			$sczFetch = sql_fetch($sczSql);
	
			if (!$sczFetch) break;
			$scSql = "SELECT sc.*, scz.cp_promotion FROM lt_shop_coupon AS sc LEFT JOIN lt_shop_coupon_zone AS scz ON sc.cz_id = scz.cz_id WHERE sc.mb_id='{$mbId}' AND sc.cz_id = '{$sczFetch['cz_id']}' ORDER BY sc.cp_datetime limit 1";
			$scFetch = sql_fetch($scSql);
			if (!$scFetch) {
				$cp_id = get_coupon_id();
				
				$couponInsert = " INSERT INTO lt_shop_coupon
				( cp_id, cp_subject, cp_desc, cp_method, cp_target, mb_id, cz_id, cp_start, cp_end, cp_type, cp_price, cp_trunc, cp_minimum, cp_maximum, cp_datetime, cp_weekday, cp_week, cp_promotion )
				VALUES
				( '$cp_id', '{$sczFetch['cz_subject']}', '{$sczFetch['cz_desc']}', '{$sczFetch['cp_method']}', '{$sczFetch['cp_target']}', '$mbId', '{$sczFetch['cz_id']}', '{$sczFetch['cz_start']}', '{$sczFetch['cz_end']}', '{$sczFetch['cp_type']}', '{$sczFetch['cp_price']}', '{$sczFetch['cp_trunc']}', '{$sczFetch['cp_minimum']}', '{$sczFetch['cp_maximum']}', '" . G5_TIME_YMDHIS . "', '{$sczFetch['cp_weekday']}', '{$sczFetch['cp_week']}', '{$sczFetch['cp_promotion']}' ) ";
				 $couponResult = sql_query($couponInsert);
				 if (!$couponResult) break;
				 $scFetch = sql_fetch($scSql);
			} else {
				if ($scFetch['od_id'] && $scFetch['od_id'] !=0 ) break;
			}
			for($j=0; $j<count($order_items); $j++) { 
				if ( strpos($scFetch['cp_target'], $order_items[$j]['it_id']) !== false) { 
					if (!isset($coupons2[$scFetch['cp_method']])) $coupons2[$scFetch['cp_method']] = array();
					$tmp_discount = $libCoupon->calcDiscountPrice($order_items[$j]['view']['sell_price'], $scFetch['cp_price'], $scFetch['cp_type'] == 1, $scFetch['cp_trunc'], $scFetch['cp_maximum']);
					$scFetch['discounted_price'] = $order_items[$j]['sell_price'] - $tmp_discount;
					$scFetch['discount_price'] = $tmp_discount;
					$coupons2[$scFetch['cp_method']][0] = $scFetch;
					$order_items[$j]['view']['coupons'] = $coupons2;
				}
			}	
		}
	}
	return false;
};

$multi_company = false;
$tot_point = 0;
$tot_sell_price = 0;
$tot_before_price = 0;

$goods = $goods_it_id = "";
$goods_count = -1;

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
		b.it_brand,
		b.ca_id,
		b.ca_id2,
		b.ca_id3,
		b.it_notax,
		b.it_point_type,
        b.it_point,
		c.io_hoching,
		(s.its_price + a.io_price) AS before_price
		FROM {$g5['g5_shop_cart_table']} a LEFT JOIN {$g5['g5_shop_item_table']} b ON ( a.it_id = b.it_id )
		LEFT JOIN {$g5['g5_shop_item_option_table']} AS c ON ( a.it_id=c.it_id AND a.io_sapcode_color_gz = c.io_sapcode_color_gz )
		INNER JOIN lt_shop_item_sub AS s ON a.it_id = s.it_id AND a.its_no = s.its_no
		WHERE a.od_id = '$s_cart_id'
		AND a.od_type = '$od_type'
		AND a.ct_select = '1' ";
$sql .= " group by a.it_id ";
$sql .= " order by a.ct_id ";
$result = sql_query($sql);

$good_info = '';
$it_send_cost = 0;
$it_cp_count = 0;

$comm_tax_mny = 0; // 과세금액
$comm_vat_mny = 0; // 부가세
$comm_free_mny = 0; // 면세금액
$tot_tax_mny = 0;
$ca_id3 = '';
$pointCheck = 0;
if ($result->num_rows > 0) {
	$libCoupon = new coupon();
	global $order_items;
	$member_coupons = $libCoupon->getMemberCoupon($member['mb_id']);
	$capaign_coupons = $libCoupon->getCampaignCoupon($member['mb_id']);
	for ($i = 0; $row = sql_fetch_array($result); $i++) {
		$coupons = array();
		$tmp_order_item = $row;
		if ($i != 0 && $ca_id3 != $row['ca_id3']) $multi_company = true;
		$ca_id3 = $row['ca_id3'];

		// 합계금액 계산
		$sql = "SELECT SUM((a.ct_price + a.io_price) * a.ct_qty) AS price,
                SUM((b.its_price + a.io_price) * a.ct_qty) AS before_price,
                SUM(a.ct_point * a.ct_qty) AS point,
                SUM(a.ct_qty) AS qty
				FROM lt_shop_cart AS a
				LEFT JOIN lt_shop_item_sub AS b ON a.it_id = b.it_id AND a.its_no = b.its_no
				WHERE a.it_id = '{$row['it_id']}'
				AND a.od_id = '$s_cart_id' ";
		$sum = sql_fetch($sql);

		if (!$goods) {
			$goods = preg_replace("/\'|\"|\||\,|\&|\;/", "", $row['it_name']);
			$goods_it_id = $row['it_id'];
		}
		$goods_count++;

		// 에스크로 상품정보
		if ($default['de_escrow_use']) {
			if ($i > 0)
				$good_info .= chr(30);
			$good_info .= "seq=" . ($i + 1) . chr(31);
			$good_info .= "ordr_numb={$od_id}_" . sprintf("%04d", $i) . chr(31);
			$good_info .= "good_name=" . addslashes($row['it_name']) . chr(31);
			$good_info .= "good_cntx=" . $row['ct_qty'] . chr(31);
			$good_info .= "good_amtx=" . $row['ct_price'] . chr(31);
		}

		$image = get_it_image($row['it_id'], 120, 120);

		$it_name = '<strong>' . stripslashes($row['it_name']) . '</strong>';
		$it_options = print_item_options($row['it_id'], $s_cart_id);

		// 복합과세금액
		if ($default['de_tax_flag_use']) {
			if ($row['it_notax']) {
				$comm_free_mny += $sum['price'];
			} else {
				$tot_tax_mny += $sum['price'];
			}
		}

		$sell_price = $sum['price'];
		if ($row['it_point_type'] == '2') {
			$point = ($sell_price / 100) * $row['it_point'];
			$pointCheck = 1;
		} elseif($row['it_point_type'] == '0') {
			$point = $row['it_point'] * $row['ct_qty'];
			$pointCheck = 1;
		} elseif($row['it_point_type'] == '9') { 
			$point = 0;
			$pointCheck = 1;
		} else {
			$point = ($sell_price / 100) * $default['de_point_percent'];
		}
		$before_price = $sum['before_price'];
		// 쿠폰

		// $libCoupon->setItem($row['it_id']);
		// $item_coupons = $libCoupon->getCouponList();

		// if (!empty($item_coupons)) {
		// 	foreach ($item_coupons as $ic) {
		// 		foreach ($member_coupons as $mc) {
		// 			if ($mc['cz_id'] == $ic['cz_id']) {
		// 				if (!isset($coupons[$mc['cp_method']])) $coupons[$mc['cp_method']] = array();
		// 				$tmp_discount = $libCoupon->calcDiscountPrice($sell_price, $mc['cp_price'], $mc['cp_type'] == 1, $mc['cp_trunc']);
		// 				$mc['discounted_price'] = $sell_price - $tmp_discount;
		// 				$mc['discount_price'] = $tmp_discount;
		// 				$coupons[$mc['cp_method']][] = $mc;
		// 			}
		// 		}
		// 	}
		// }

				foreach ($member_coupons as $mc) {
					if (!$mc['cp_promotion'] || $mc['cp_promotion'] == 0 ) {
						if ( strpos($mc['cp_target'], $row['it_id']) !== false) {
							if (!isset($coupons[$mc['cp_method']])) $coupons[$mc['cp_method']] = array();
							$tmp_discount = $libCoupon->calcDiscountPrice($sell_price, $mc['cp_price'], $mc['cp_type'] == 1, $mc['cp_trunc'], $mc['cp_maximum']);
							$mc['discounted_price'] = $sell_price - $tmp_discount;
							$mc['discount_price'] = $tmp_discount;
							$coupons[$mc['cp_method']][] = $mc;
						}
					} 
				}

		$tmp_order_item['view'] = array();
		$tmp_order_item['view']['image'] = $image;
		$tmp_order_item['view']['sell_price'] = $sell_price;
		$tmp_order_item['view']['it_name'] = $it_name;
		$tmp_order_item['view']['it_options'] = $it_options;
		$tmp_order_item['view']['cp_button'] = $cp_button;
		$tmp_order_item['view']['sendcost'] = get_sendcost($row['ct_id']);
		$tmp_order_item['view']['sum'] = $sum;
		$tmp_order_item['view']['coupons'] = $coupons;

		$tot_point      += $point;
		$tot_sell_price += $sell_price;
		$tot_before_price += $before_price;

		$order_items[] = $tmp_order_item;
	} // for 끝
	$weddingRes = wedding($member_coupons,$capaign_coupons,$member['mb_id']); 
	// 배송비 계산
	$send_cost = get_sendcost($s_cart_id);
	$it_send_cost += $send_cost;
	// 복합과세처리
	if ($default['de_tax_flag_use']) {
		$comm_tax_mny = round(($tot_tax_mny + $send_cost) / 1.1);
		$comm_vat_mny = ($tot_tax_mny + $send_cost) - $comm_tax_mny;
	}
	if ($goods_count) $goods .= ' 외 ' . $goods_count . '건';
	$tot_price = $tot_sell_price + $send_cost; // 총계 = 주문상품금액합계 + 배송비 
} else {
	alert('장바구니가 비어 있습니다1.', G5_SHOP_URL . '/cart.php');
}

$member_coupon = array(
	2 => array(),
	3 => array()
);
foreach ($member_coupons as $mc) {
	if (
		isset($member_coupon[$mc['cp_method']]) && empty($mc['od_id']) && $tot_price >= $mc['cp_minimum'] &&
		(strlen($mc['cp_weekday']) == 0 || (strpos($mc['cp_weekday'], date("w")) !== false)) &&
		(strlen($mc['cp_week']) == 0 || (strpos($mc['cp_week'], G5_WEEK_NUM) !== false))
	) $member_coupon[$mc['cp_method']][] = $mc;
}

$order_action_url = G5_HTTPS_SHOP_URL . '/orderformupdate.php';
$contents = include_once(G5_VIEW_PATH . "/orderform.sub.php");
include_once G5_LAYOUT_PATH . "/layout.php";
