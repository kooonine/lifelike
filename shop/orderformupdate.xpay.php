<?
include_once('./_common.php');
include_once(G5_LIB_PATH . '/mailer.lib.php');
require_once(G5_LIB_PATH . '/coupon.lib.php');

$libCoupon = new coupon;

//dd($_POST);

//이니시스 lpay 요청으로 왔다면 $default['de_pg_service'] 값을 이니시스로 변경합니다.
if ($od_settle_case == 'lpay') {
	$default['de_pg_service'] = 'inicis';
}

if (($od_settle_case != '무통장' && $od_settle_case != 'KAKAOPAY') && $default['de_pg_service'] == 'lg' && !$_POST['LGD_RESPCODE'])
	alert('결제등록 요청 후 주문해 주십시오.');

// 장바구니가 비어있는가?
if (get_session("ss_direct"))
	$tmp_cart_id = get_session('ss_cart_direct');
else
	$tmp_cart_id = get_session('ss_cart_id');

if (get_cart_count($tmp_cart_id) == 0) // 장바구니에 담기
	alert('장바구니가 비어 있습니다.\\n\\n이미 주문하셨거나 장바구니에 담긴 상품이 없는 경우입니다.', G5_SHOP_URL . '/cart.php');

$error = "";
// 장바구니 상품 재고 검사
$sql = " select it_id, ct_qty, it_name, io_id, io_type, ct_option from {$g5['g5_shop_cart_table']} where od_id = '$tmp_cart_id' and ct_select = '1' ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
	// 상품에 대한 현재고수량
	if ($row['io_id']) {
		$it_stock_qty = (int) get_option_stock_qty($row['it_id'], $row['io_id'], $row['io_type']);
	} else {
		$it_stock_qty = (int) get_it_stock_qty($row['it_id']);
	}
	// 장바구니 수량이 재고수량보다 많다면 오류
	if ($row['ct_qty'] > $it_stock_qty)
		$error .= "{$row['ct_option']} 의 재고수량이 부족합니다. 현재고수량 : $it_stock_qty 개\\n\\n";
}

if ($i == 0) {
	alert('장바구니가 비어 있습니다.\\n\\n이미 주문하셨거나 장바구니에 담긴 상품이 없는 경우입니다.', G5_SHOP_URL . '/cart.php');
}

if ($error != "") {
	$error .= "다른 고객님께서 {$od_name}님 보다 먼저 주문하신 경우입니다. 불편을 끼쳐 죄송합니다.";
	alert($error);
}

$i_price     = (int) $_POST['od_price'];
$i_send_cost  = (int) $_POST['od_send_cost'];
$i_send_cost2  = (int) $_POST['od_send_cost2'];
$i_send_coupon  = (int) $_POST['od_send_coupon'];
$i_temp_point = (int) $_POST['od_temp_point'];

// 주문금액이 상이함
$sql = " select SUM((a.ct_price + a.io_price) * a.ct_qty) as od_price
                , SUM((b.its_price + a.io_price) * a.ct_qty) as before_price
                , COUNT(distinct a.it_id) as cart_count 
            from  lt_shop_cart as a
                  left join lt_shop_item_sub as b on a.it_id = b.it_id and a.its_no = b.its_no 
            where a.od_id = '$tmp_cart_id' 
            and   a.ct_select = '1' ";

$row = sql_fetch($sql);

$cart_count = $row['cart_count'];
$tot_before_price = $row['before_price'];
$tot_ct_price = $row['od_price'];
$tot_od_price = $tot_ct_price;

// 쿠폰 할인금액 계산
$tot_cp_price = 0;
$arr_used_coupon = array();

if ($is_member) {
	$tot_it_cp_price = 0;
	$tot_od_cp_price = 0;
	$tot_sc_cp_price = 0;
	// 상품쿠폰
	
	$cp_ids = explode(',', $_POST['cp_id']);
	$it_ids = explode(',', $_POST['it_id']);

	$pattern = '/([a-z|A-Z|._~])+/';

	$it_cp_cnt = count($cp_ids);
	for ($i = 0; $i < $it_cp_cnt; $i++) {
		$it_id = preg_replace("/[^a-zA-Z0-9]/", "", $it_ids[$i]);
		// $tmp_dc_price = 0;

		// 상품금액
		$sql = "SELECT ct_id, SUM( IF(io_type = '1', io_price * ct_qty, (ct_price + io_price) * ct_qty)) AS sum_price
					FROM {$g5['g5_shop_cart_table']}
					WHERE od_id = '$tmp_cart_id'
					AND it_id = '$it_id'
					AND ct_select = '1' ";
		$ct = sql_fetch($sql);
		$item_price = $ct['sum_price'];
		$tmp_cid = explode(',', preg_replace("/[^a-zA-Z0-9]/", "", $cp_ids[$i]));

		foreach ($tmp_cid as $cid) {
			$cp = $libCoupon->setCoupon($cid);
			if ($cp == false) {
				continue;
			}

			// 사용한 쿠폰인지
			if (is_used_coupon($member['mb_id'], $cp['cp_id'])) {
				continue;
			}

			// 분류할인인지
			if ($cp['cp_method'] == 1) {
				$sql2 = " select it_id, ca_id, ca_id2, ca_id3 from {$g5['g5_shop_item_table']} where it_id = '$it_id' ";
				$row2 = sql_fetch($sql2);
				if (!$row2['it_id']) {
					continue;
				}
				if ($row2['ca_id'] != $cp['cp_target'] && $row2['ca_id2'] != $cp['cp_target'] && $row2['ca_id3'] != $cp['cp_target']) {
					continue;
				}
			} else {
				if (strpos($cp['cp_target'], $it_id) === false) {
					continue;
				}
			}

			if ($cp['cp_minimum'] > $item_price) {
				continue;
			}

			// 중복 할인을 위해 쿠폰을 종류별로 재정렬

			$cp_sort_key = $libCoupon->sort[$cp['cp_method']];
			if (!isset($arr_used_coupon[$cp_sort_key])) $arr_used_coupon[$cp_sort_key] = array();
			$arr_used_coupon[$cp_sort_key][] = array(
				'cp' => $cp,
				'ct_id' => $ct['ct_id'],
				'item_price' => $item_price
			);
		}
	}

	$tmp_it_dc = array();

	// dd($arr_used_coupon);

	foreach ($arr_used_coupon as $sort_key => $it_cp) {
		foreach ($it_cp as $cp) {
			if (!isset($tmp_it_dc[$cp['ct_id']])) $tmp_it_dc[$cp['ct_id']] = 0;

			// 할인금액 계산

			// var_dump($tmp_it_dc[$cp['ct_id']]);
			$dc = $libCoupon->calcDiscountPrice($cp['item_price'] - $tmp_it_dc[$cp['ct_id']], $cp['cp']['cp_price'], $cp['cp']['cp_type'] == 1, $cp['cp']['cp_trunc'], $cp['cp']['cp_maximum']);
			$tmp_it_dc[$cp['ct_id']] += $dc;
			$tot_it_cp_price += $dc;
		}
	}

	$tot_od_price -= $tot_it_cp_price;

	// 주문서 쿠폰
	if ($_POST['od_cp_id']) {
		$od_coupons = explode(',', $_POST['od_cp_id']);

		$dc = 0;

		foreach ($od_coupons as $od_coupon) {
			$sql = "SELECT  a.*, b.cm_use_price_type
                FROM lt_shop_coupon AS a LEFT JOIN lt_shop_coupon_mng AS b ON a.cm_no=b.cm_no
                WHERE a.cp_id = '{$od_coupon}'
                AND a.mb_id IN ( '{$member['mb_id']}', '전체회원' )
                AND a.cp_start <= '" . G5_TIME_YMD . "'
                AND a.cp_end >= '" . G5_TIME_YMD . "'
                AND a.cp_minimum <= $tot_od_price";
			$cp = sql_fetch($sql);

			// 사용한 쿠폰인지
			$cp_used = is_used_coupon($member['mb_id'], $cp['cp_id']);

			if (!$cp_used && $cp['cp_id'] && ($cp['cp_minimum'] <= $tot_od_price)) {
				$dc += $libCoupon->calcDiscountPrice($cp['cm_use_price_type'] ? $tot_od_price : $tot_od_price, $cp['cp_price'], $cp['cp_type'] == 1, $cp['cp_trunc'], $cp['cp_maximum']);
				if ($tot_od_price < $dc) {
					die('Order coupon error.');
				}
			}

			$cp_sort_key = $libCoupon->sort[$cp['cp_method']];
			if (!isset($arr_used_coupon[$cp_sort_key])) $arr_used_coupon[$cp_sort_key] = array();
			$arr_used_coupon[$cp_sort_key][] = array(
				'cp' => $cp
			);
		}
		if ($dc < 0) $dc = 0;
		$tot_od_cp_price = $dc;
		$tot_od_price -= $tot_od_cp_price;
	}

	// 배송비 쿠폰
	if ($_POST['od_send_cp_id']) {
		$od_coupons = explode(',', $_POST['od_send_cp_id']);

		$dc = 0;

		foreach ($od_coupons as $od_coupon) {
			$sql = "SELECT  a.*, b.cm_use_price_type
                FROM lt_shop_coupon AS a LEFT JOIN lt_shop_coupon_mng AS b ON a.cm_no=b.cm_no
                WHERE a.cp_id = '{$od_coupon}'
                AND a.mb_id IN ( '{$member['mb_id']}', '전체회원' )
                AND a.cp_start <= '" . G5_TIME_YMD . "'
                AND a.cp_end >= '" . G5_TIME_YMD . "'
                AND a.cp_minimum <= $tot_od_price";
			$cp = sql_fetch($sql);

			// 사용한 쿠폰인지
			$cp_used = is_used_coupon($member['mb_id'], $cp['cp_id']);

			if (!$cp_used && $cp['cp_id'] && ($cp['cp_minimum'] <= $tot_od_price)) {
				$dc += $libCoupon->calcDiscountPrice($cp['cm_use_price_type'] ? $tot_od_price : $tot_ct_price, $cp['cp_price'], $cp['cp_type'] == 1, $cp['cp_trunc'], $cp['cp_maximum']);
				if ($tot_od_price < $dc) {
					die('Order coupon error.');
				}
			}

			$cp_sort_key = $libCoupon->sort[$cp['cp_method']];
			if (!isset($arr_used_coupon[$cp_sort_key])) $arr_used_coupon[$cp_sort_key] = array();
			$arr_used_coupon[$cp_sort_key][] = array(
				'cp' => $cp
			);
		}

		$tot_sc_cp_price = $dc;
		$tot_od_price -= $tot_sc_cp_price;
	}

	$tot_cp_price = $tot_it_cp_price + $tot_od_cp_price + $tot_sc_cp_price;
}

// 전송된 주문금액과 계산결과 비교
if (((int) $tot_ct_price - $tot_cp_price - $i_temp_point) != ($i_price - $i_send_cost)) {
	alert("결재금액과 할인적용 결과가 상이합니다. 관리자에게 문의바랍니다");
	//alert("결재금액과 할인적용 결과가 상이합니다. 관리자에게 문의바랍니다".$tot_ct_price ."/". $tot_cp_price ."/". $i_temp_point."/". $i_price ."/". $i_send_cost);
	//die("Error.");
	// var_dump($tot_ct_price);
	// var_dump($tot_cp_price);
	// var_dump($i_temp_point);
	// var_dump($i_send_cost);
	// var_dump($i_price);
}

// 배송비가 상이함
$send_cost = get_sendcost($tmp_cart_id);
if ((int) ($send_cost) !== (int) ($i_send_cost)) {
	die("Error..");
}

// $tot_sc_cp_price = 0;
// if ($is_member && $send_cost > 0) {
// 	// 배송쿠폰
// 	if ($_POST['sc_cp_id']) {
// 		$sql = " select cp_id, cp_type, cp_price, cp_trunc, cp_minimum, cp_maximum
// 					from {$g5['g5_shop_coupon_table']}
// 					where cp_id = '{$_POST['sc_cp_id']}'
// 					  and mb_id IN ( '{$member['mb_id']}', '전체회원' )
// 					  and cp_start <= '" . G5_TIME_YMD . "'
// 					  and cp_end >= '" . G5_TIME_YMD . "'
// 					  and cp_method = '3' ";
// 		$cp = sql_fetch($sql);

// 		// 사용한 쿠폰인지
// 		$cp_used = is_used_coupon($member['mb_id'], $cp['cp_id']);

// 		$dc = 0;
// 		if (!$cp_used && $cp['cp_id'] && ($cp['cp_minimum'] <= $tot_od_price)) {
// 			if ($cp['cp_type']) {
// 				$dc = floor(($send_cost * ($cp['cp_price'] / 100)) / $cp['cp_trunc']) * $cp['cp_trunc'];
// 			} else {
// 				$dc = $cp['cp_price'];
// 			}

// 			if ($cp['cp_maximum'] && $dc > $cp['cp_maximum'])
// 				$dc = $cp['cp_maximum'];

// 			if ($dc > $send_cost)
// 				$dc = $send_cost;

// 			$tot_sc_cp_price = $dc;

// 			$cp_sort_key = $libCoupon->sort[$cp['cp_method']];
// 			if (!isset($arr_used_coupon[$cp_sort_key])) $arr_used_coupon[$cp_sort_key] = array();
// 			$arr_used_coupon[$cp_sort_key][] = array(
// 				'cp' => $cp
// 			);
// 		}
// 	}
// }

if ((int)($send_cost - $tot_sc_cp_price) !== (int)($i_send_cost - $i_send_coupon)) {
	die("Error..");
}

// 추가배송비가 상이함
$od_b_zip   = preg_replace('/[^0-9]/', '', $_POST['od_b_zip']);
$od_b_zip1  = substr($od_b_zip, 0, 3);
$od_b_zip2  = substr($od_b_zip, 3);
$zipcode = $od_b_zip;
$sql = " select sc_id, sc_price from {$g5['g5_shop_sendcost_table']} where sc_zip1 <= '$zipcode' and sc_zip2 >= '$zipcode' ";
$tmp = sql_fetch($sql);
if (!$tmp['sc_id'])
	$send_cost2 = 0;
else
	$send_cost2 = (int)$tmp['sc_price'];
if ($send_cost2 !== $i_send_cost2)
	die("Error...");

// 결제포인트가 상이함
// 회원이면서 포인트사용이면

$max_temp_point = 0;

if ($is_member && $config['cf_use_point']) {
	if ($member['mb_point'] >= $default['de_settle_min_point']) {
		$max_temp_point = (int) $default['de_settle_max_point'];
		if ($max_temp_point > (int) $tot_od_price) {
			$max_temp_point = (int) $tot_od_price;
		}
		if ($max_temp_point > (int) $member['mb_point']) {
			$max_temp_point = (int) $member['mb_point'];
		}
		$point_unit = (int) $default['de_settle_point_unit'];
		$max_temp_point = (int) ((int) ($max_temp_point / $point_unit) * $point_unit);
	}
}
if (($i_temp_point > (int) $max_temp_point || $i_temp_point < 0) && $config['cf_use_point']) {
	die("Error....");
}

if ($i_temp_point) {
	if ($member['mb_point'] < $i_temp_point)
		alert('회원님의 포인트가 부족하여 포인트로 결제 할 수 없습니다.');
}


// $i_price = $i_price + $i_send_cost + $i_send_cost2 - $i_send_coupon;
// $order_price = $tot_od_price + $send_cost + $send_cost2 - $i_temp_point - $i_send_coupon;
// 
$order_price = $tot_od_price + $send_cost + $send_cost2 - $i_temp_point;

$od_settle_case="신용카드";

$od_status = '주문';
$od_tno    = '';
if ($od_settle_case == "무통장") {
	$od_receipt_point   = $i_temp_point;
	$od_receipt_price   = 0;
	$od_misu            = $i_price - $od_receipt_price;
	if ($od_misu == 0) {
		$od_status      = '결제완료';
		$od_receipt_time = G5_TIME_YMDHIS;
	}
} else if ($od_settle_case == "계좌이체") {
	switch ($default['de_pg_service']) {
		case 'lg':
			include G5_SHOP_PATH . '/lg/xpay_result.php';
			break;
		case 'inicis':
			include G5_SHOP_PATH . '/inicis/inistdpay_result.php';
			break;
		default:
			include G5_SHOP_PATH . '/kcp/pp_ax_hub.php';
			$bank_name  = iconv("cp949", "utf-8", $bank_name);
			break;
	}

	$od_tno				= $tno;
	$od_receipt_price	= $amount;
	$od_receipt_point	= $i_temp_point;
	$od_receipt_time	= preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
	$od_bank_account	= $od_settle_case;
	$od_deposit_name	= $od_name;
	$od_bank_account	= $bank_name;
	$pg_price			= $amount;
	$od_misu            = $i_price - $od_receipt_price;
	if ($od_misu == 0)
		$od_status		= '결제완료';
} else if ($od_settle_case == "가상계좌") {
	switch ($default['de_pg_service']) {
		case 'lg':
			include G5_SHOP_PATH . '/lg/xpay_result.php';
			break;
		case 'inicis':
			include G5_SHOP_PATH . '/inicis/inistdpay_result.php';
			$od_app_no = $app_no;
			break;
		default:
			include G5_SHOP_PATH . '/kcp/pp_ax_hub.php';
			$bankname   = iconv("cp949", "utf-8", $bankname);
			$depositor  = iconv("cp949", "utf-8", $depositor);
			break;
	}

	$od_receipt_point   = $i_temp_point;
	$od_tno             = $tno;
	$od_receipt_price   = 0;
	$od_bank_account    = $bankname . ' ' . $account;
	$od_deposit_name    = $depositor;
	$pg_price           = $amount;
	$od_misu            = $i_price - $od_receipt_price;
} else if ($od_settle_case == "휴대전화") {
	switch ($default['de_pg_service']) {
		case 'lg':
			include G5_SHOP_PATH . '/lg/xpay_result.php';
			break;
		case 'inicis':
			include G5_SHOP_PATH . '/inicis/inistdpay_result.php';
			break;
		default:
			include G5_SHOP_PATH . '/kcp/pp_ax_hub.php';
			break;
	}

	$od_tno             = $tno;
	$od_receipt_price   = $amount;
	$od_receipt_point   = $i_temp_point;
	$od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
	$od_bank_account    = $commid . ($commid ? ' ' : '') . $mobile_no;
	$pg_price           = $amount;
	$od_misu            = $i_price - $od_receipt_price;
	if ($od_misu == 0)
		$od_status      = '결제완료';
} else if ($od_settle_case == "신용카드") {
	switch ($default['de_pg_service']) {
		case 'lg':
			include G5_SHOP_PATH . '/lg/xpay_result.php';
			break;
		case 'inicis':
			include G5_SHOP_PATH . '/inicis/inistdpay_result.php';
			break;
		default:
			include G5_SHOP_PATH . '/kcp/pp_ax_hub.php';
			$card_name  = iconv("cp949", "utf-8", $card_name);
			break;
	}

	$od_tno             = $tno;
	$od_app_no          = $app_no;
	$od_receipt_price   = $amount;
	$od_receipt_point   = $i_temp_point;
	$od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
	$od_bank_account    = $card_name;
	$pg_price           = $amount;
	$od_misu            = $i_price - $od_receipt_price;
	if ($od_misu == 0)
		$od_status      = '결제완료';
} else if ($od_settle_case == "간편결제" || ($od_settle_case == "lpay" && $default['de_pg_service'] === 'inicis')) {
	switch ($default['de_pg_service']) {
		case 'lg':
			include G5_SHOP_PATH . '/lg/xpay_result.php';
			break;
		case 'inicis':
			include G5_SHOP_PATH . '/inicis/inistdpay_result.php';
			break;
		default:
			include G5_SHOP_PATH . '/kcp/pp_ax_hub.php';
			$card_name  = iconv("cp949", "utf-8", $card_name);
			break;
	}

	$od_tno             = $tno;
	$od_app_no          = $app_no;
	$od_receipt_price   = $amount;
	$od_receipt_point   = $i_temp_point;
	$od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
	$od_bank_account    = $card_name;
	$pg_price           = $amount;
	$od_misu            = $i_price - $od_receipt_price;
	if ($od_misu == 0)
		$od_status      = '결제완료';
} else if ($od_settle_case == "KAKAOPAY") {
	include G5_SHOP_PATH . '/kakaopay/kakaopay_result.php';

	$od_tno             = $tno;
	$od_app_no          = $app_no;
	$od_receipt_price   = $amount;
	$od_receipt_point   = $i_temp_point;
	$od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
	$od_bank_account    = $card_name;
	$pg_price           = $amount;
	$od_misu            = $i_price - $od_receipt_price;
	if ($od_misu == 0)
		$od_status      = '결제완료';
} else {
	die("od_settle_case Error!!!");
}

$od_pg = $default['de_pg_service'];
if ($od_settle_case == 'KAKAOPAY') {
	$od_pg = 'KAKAOPAY';
}

// 주문금액과 결제금액이 일치하는지 체크
if ($tno) {
	if ((int) $order_price !== (int) $pg_price) {
		$cancel_msg = '결제금액 불일치';
		switch ($od_pg) {
			case 'lg':
				include G5_SHOP_PATH . '/lg/xpay_cancel.php';
				break;
			case 'inicis':
				include G5_SHOP_PATH . '/inicis/inipay_cancel.php';
				break;
			case 'KAKAOPAY':
				$_REQUEST['TID']               = $tno;
				$_REQUEST['Amt']               = $amount;
				$_REQUEST['CancelMsg']         = $cancel_msg;
				$_REQUEST['PartialCancelCode'] = 0;
				include G5_SHOP_PATH . '/kakaopay/kakaopay_cancel.php';
				break;
			default:
				include G5_SHOP_PATH . '/kcp/pp_ax_hub_cancel.php';
				break;
		}

		die("Receipt Amount Error");
	}
}

if ($is_member) {
	$od_pwd = $member['mb_password'];
} else {
	$od_pwd = get_encrypt_string($_POST['od_pwd']);
}

// 주문번호를 얻는다.
$od_id = $od_id_res;
// $od_id = get_session('ss_order_id');

if (!$od_id) {
	die("주문번호가 없습니다.");
}

$od_escrow = 0;
if ($escw_yn == 'Y') {
	$od_escrow = 1;
}

// 복합과세 금액
$od_tax_mny			= round($i_price / 1.1);
$od_vat_mny			= $i_price - $od_tax_mny;
$od_free_mny		= 0;
if ($default['de_tax_flag_use']) {
	$od_tax_mny		= (int) $_POST['comm_tax_mny'];
	$od_vat_mny		= (int) $_POST['comm_vat_mny'];
	$od_free_mny	= (int) $_POST['comm_free_mny'];
}

$od_email			= get_email_address($_POST['$od_email']);
$od_name			= clean_xss_tags($_POST['$od_name']);
$od_tel				= clean_xss_tags($_POST['$od_tel']);
$od_hp				= clean_xss_tags($_POST['$od_hp']);
$od_zip				= preg_replace('/[^0-9]/', '', $_POST['$od_zip']);
$od_zip1			= substr($od_zip, 0, 3);
$od_zip2			= substr($od_zip, 3);
$od_addr1			= clean_xss_tags($_POST['$od_addr1']);
$od_addr2			= clean_xss_tags($_POST['$od_addr2']);
$od_addr3			= clean_xss_tags($_POST['$od_addr3']);
$od_addr_jibeon		= preg_match("/^(N|R)$/", $_POST['$od_addr_jibeon']) ? $_POST['$od_addr_jibeon'] : '';
$od_b_name			= clean_xss_tags($_POST['$od_b_name']);
$od_b_tel			= clean_xss_tags($_POST['$od_b_tel']);
$od_b_zip			= preg_replace('/[^0-9]/', '', $_POST['$od_b_zip']);
$od_b_zip1			= substr($_POST['$od_b_zip'], 0, 3);
$od_b_zip2			= substr($_POST['$od_b_zip'], 3);
$od_b_addr1			= clean_xss_tags($_POST['$od_b_addr1']);
$od_b_addr2			= clean_xss_tags($_POST['$od_b_addr2']);
$od_b_addr3			= clean_xss_tags($_POST['$od_b_addr3']);
$od_b_addr_jibeon	= preg_match("/^(N|R)$/", $_POST['$od_b_addr_jibeon']) ? $_POST['$od_b_addr_jibeon'] : '';
$od_deposit_name	= clean_xss_tags($_POST['$od_deposit_name']);
$od_tax_flag		= $default['$de_tax_flag_use'];
$od_memo			= clean_xss_tags($_POST['$od_memo']);
if ($od_memo == "user") $od_memo = clean_xss_tags($_POST['$od_memo_user']);
$od_b_hp			= hyphen_hp_number($_POST['$od_b_hp_1'] . $_POST['$od_b_hp_2']);

//추가>
$od_type			= $_POST['$od_type'];
$od_pcmobile            = $_POST['$od_pcmobile'];

// 주문서에 입력
$sql = " insert {$g5['g5_shop_order_table']}
	set od_id         = '$od_id',
	od_type           = '$od_type',
	mb_id             = '{$member['mb_id']}',
	od_pwd            = '$od_pwd',
	od_name           = '$od_name',
	od_email          = '$od_email',
	od_tel            = '$od_tel',
	od_hp             = '$od_hp',
	od_zip1           = '$od_zip1',
	od_zip2           = '$od_zip2',
	od_addr1          = '$od_addr1',
	od_addr2          = '$od_addr2',
	od_addr3          = '$od_addr3',
	od_addr_jibeon    = '$od_addr_jibeon',
	od_b_name         = '$od_b_name',
	od_b_tel          = '$od_b_tel',
	od_b_hp           = '$od_b_hp',
	od_b_zip1         = '$od_b_zip1',
	od_b_zip2         = '$od_b_zip2',
	od_b_addr1        = '$od_b_addr1',
	od_b_addr2        = '$od_b_addr2',
	od_b_addr3        = '$od_b_addr3',
	od_b_addr_jibeon  = '$od_b_addr_jibeon',
	od_deposit_name   = '$od_deposit_name',
	od_memo           = '$od_memo',
	od_cart_count     = '$cart_count',
	od_cart_price     = '$tot_ct_price',
	od_cart_coupon    = '$tot_it_cp_price',
	od_send_cost      = '$od_send_cost',
	od_send_coupon    = '$tot_sc_cp_price',
	od_send_cost2     = '$od_send_cost2',
	od_coupon         = '$tot_od_cp_price',
	od_receipt_price  = '$od_receipt_price',
	od_receipt_point  = '$od_receipt_point',
	od_bank_account   = '$od_bank_account',
	od_receipt_time   = '$od_receipt_time',
	od_misu           = '$od_misu',
	od_pg             = '$od_pg',
	od_tno            = '$od_tno',
	od_app_no         = '$od_app_no',
	od_escrow         = '$od_escrow',
	od_tax_flag       = '$od_tax_flag',
	od_tax_mny        = '$od_tax_mny',
	od_vat_mny        = '$od_vat_mny',
	od_free_mny       = '$od_free_mny',
	od_status         = '$od_status',
	od_shop_memo      = '',
	od_hope_date      = '$od_hope_date',
	od_time           = '" . G5_TIME_YMDHIS . "',
	od_ip             = '$REMOTE_ADDR',
	od_settle_case    = '$od_settle_case',
	od_test           = '{$default['de_card_test']}',
	od_pcmobile 	  = '$od_pcmobile',
	od_cart_coupon_cancel = '$tot_it_cp_price',
	od_coupon_cancel = '$tot_od_cp_price',
	od_send_coupon_cancel = '$tot_sc_cp_price',
	od_receipt_point_cancel = '$od_receipt_point',
	od_receipt_price_ori  = '$od_receipt_price',
	od_receipt_refund_price_ori = '$od_receipt_price',
	od_cart_coupon_ori = '$tot_it_cp_price',
	od_coupon_ori = '$tot_od_cp_price'
 	";
$result = sql_query($sql, false);

// 주문정보 입력 오류시 결제 취소
if (!$result) {
	if ($tno) {
		$cancel_msg = '주문정보 입력 오류';
		switch ($od_pg) {
			case 'lg':
				include G5_SHOP_PATH . '/lg/xpay_cancel.php';
				break;
			case 'inicis':
				include G5_SHOP_PATH . '/inicis/inipay_cancel.php';
				break;
			case 'KAKAOPAY':
				$_REQUEST['TID']               = $tno;
				$_REQUEST['Amt']               = $amount;
				$_REQUEST['CancelMsg']         = $cancel_msg;
				$_REQUEST['PartialCancelCode'] = 0;
				include G5_SHOP_PATH . '/kakaopay/kakaopay_cancel.php';
				break;
			default:
				include G5_SHOP_PATH . '/kcp/pp_ax_hub_cancel.php';
				break;
		}
	}

	// 관리자에게 오류 알림 메일발송
	$error = 'order';
	include G5_SHOP_PATH . '/ordererrormail.php';

	die('<p>고객님의 주문 정보를 처리하는 중 오류가 발생해서 주문이 완료되지 않았습니다.</p><p>' . strtoupper($od_pg) . '를 이용한 전자결제(신용카드, 계좌이체, 가상계좌 등)은 자동 취소되었습니다.');
}
$od_memo = nl2br(htmlspecialchars2(stripslashes($od_memo))) . "&nbsp;";


//입점몰처리 필요
$sql = " select ifnull(company_code,'') as company_code
	, sum((ct_price + io_price) * ct_qty) as od_receipt_price
	, count(ct_id) as od_cart_count
	from lt_shop_cart
	where od_id = '$tmp_cart_id' and ct_select = '1'
	group by ifnull(company_code,'')
	order by ifnull(company_code,'') asc ";
$company_result = sql_query($sql);
$company_count = sql_num_rows($company_result);

// 장바구니 상태변경
// 신용카드로 주문하면서 신용카드 포인트 사용하지 않는다면 포인트 부여하지 않음
$cart_status = $od_status;

//입점몰별 주문 쪼개기?
$first = true;
$company_od_id_list = array();
while ($row = sql_fetch_array($company_result)) {
	$c_company_code = $row['company_code'];
	if ($first) {
		$c_od_id = $od_id;
		$first = false;
		//첫번째 상품이 있는 업체 (리탠다드, 1번째 입점몰은 주문번호 유지)
		$sql = "update {$g5['g5_shop_order_table']} set company_code = '$c_company_code', od_receipt_price = od_receipt_price - (select ifnull(sum((ct_price + io_price) * ct_qty),0) from lt_shop_cart where od_id = '$tmp_cart_id' and ct_select = '1' and company_code != '$c_company_code'), od_cart_price = od_cart_price  - (select ifnull(sum((ct_price + io_price) * ct_qty),0) from lt_shop_cart where od_id = '$tmp_cart_id' and ct_select = '1' and company_code != '$c_company_code'), main_od_id = '$od_id' where od_id = '$od_id'";
		$result = sql_query($sql);
		if (!$result) {
			if ($tno) {
				$cancel_msg = '주문상태 변경 오류';
				include G5_SHOP_PATH . '/lg/xpay_cancel.php';
			}
			// 주문삭제
			sql_query(" delete from {$g5['g5_shop_order_table']} where od_id = '$od_id' ");
			sql_query(" delete from {$g5['g5_shop_order_table']} where main_od_id = '$od_id' ");
			die('<p>고객님의 주문 정보를 처리하는 중 오류가 발생해서 주문이 완료되지 않았습니다.</p><p>LG를 이용한 전자결제(신용카드, 계좌이체, 가상계좌 등)은 자동 취소되었습니다...');
		}
	} else {
		$company_code = $row['company_code'];
		$new_od_id = get_new_od_id();
		$new_od_receipt_price = $row['od_receipt_price'];
		$new_od_cart_count = $row['od_cart_count'];
		$new_od_send_cost = 0;
		$new_od_tax_mny = round($new_od_receipt_price / 1.1);
		$new_od_vat_mny = $new_od_receipt_price - $new_od_tax_mny;

		$c_od_id = $new_od_id;

		$sql = " insert {$g5['g5_shop_order_table']}
			set od_id             = '$new_od_id',
			main_od_id        = '$od_id',
			od_type           = '$od_type',
			mb_id             = '{$member['mb_id']}',
			od_pwd            = '$od_pwd',
			od_name           = '$od_name',
			od_email          = '$od_email',
			od_tel            = '$od_tel',
			od_hp             = '$od_hp',
			od_zip1           = '$od_zip1',
			od_zip2           = '$od_zip2',
			od_addr1          = '$od_addr1',
			od_addr2          = '$od_addr2',
			od_addr3          = '$od_addr3',
			od_addr_jibeon    = '$od_addr_jibeon',
			od_b_name         = '$od_b_name',
			od_b_tel          = '$od_b_tel',
			od_b_hp           = '$od_b_hp',
			od_b_zip1         = '$od_b_zip1',
			od_b_zip2         = '$od_b_zip2',
			od_b_addr1        = '$od_b_addr1',
			od_b_addr2        = '$od_b_addr2',
			od_b_addr3        = '$od_b_addr3',
			od_b_addr_jibeon  = '$od_b_addr_jibeon',
			od_deposit_name   = '$od_deposit_name',
			od_memo           = '$od_memo',
			od_cart_count     = '$new_od_cart_count',
			od_cart_price     = '$new_od_receipt_price',
			od_cart_coupon    = '0',
			od_send_cost      = '$new_od_send_cost',
			od_send_coupon    = '0',
			od_send_cost2     = '0',
			od_coupon         = '0',
			od_receipt_price  = '$new_od_receipt_price',
			od_receipt_point  = '0',
			od_bank_account   = '$od_bank_account',
			od_receipt_time   = '$od_receipt_time',
			od_misu           = '0',
			od_pg             = '$od_pg',
			od_tno            = '$od_tno',
			od_app_no         = '$od_app_no',
			od_escrow         = '$od_escrow',
			od_tax_flag       = '$od_tax_flag',
			od_tax_mny        = '$new_od_tax_mny',
			od_vat_mny        = '$new_od_vat_mny',
			od_free_mny       = '0',
			od_status         = '$od_status',
			od_shop_memo      = '',
			od_hope_date      = '$od_hope_date',
			od_time           = '" . G5_TIME_YMDHIS . "',
			od_mobile         = '1',
			od_ip             = '$REMOTE_ADDR',
			od_settle_case    = '$od_settle_case',
			od_test           = '{$default['de_card_test']}',
			company_code      = '{$company_code}',
			od_pcmobile 	  = '$od_pcmobile',
			od_cart_coupon_cancel = '0',
			od_coupon_cancel = '0',
			od_send_coupon_cancel = '0',
			od_receipt_point_cancel = '0',
			od_receipt_price_ori  = '$new_od_receipt_price',
			od_receipt_refund_price_ori = '$new_od_receipt_price',
			od_cart_coupon_ori = '0',
			od_coupon_ori = '0'
			";
		$result = sql_query($sql);
		if (!$result) {
			if ($tno) {
				$cancel_msg = '주문상태 변경 오류';
				include G5_SHOP_PATH . '/lg/xpay_cancel.php';
			}
			// 주문삭제
			sql_query(" delete from {$g5['g5_shop_order_table']} where od_id = '$od_id' ");
			sql_query(" delete from {$g5['g5_shop_order_table']} where main_od_id = '$od_id' ");
			die('<p>고객님의 주문 정보를 처리하는 중 오류가 발생해서 주문이 완료되지 않았습니다.</p><p>LG를 이용한 전자결제(신용카드, 계좌이체, 가상계좌 등)은 자동 취소되었습니다..');
		}

		array_push($company_od_id_list, $new_od_id);
	}

	$sql = "update {$g5['g5_shop_cart_table']}
		set od_id = '$c_od_id',
		od_type = '$od_type',
		ct_receipt_price = ((ct_price + io_price) * ct_qty),
		ct_status = '$cart_status'
		where od_id = '$tmp_cart_id'
		and company_code='$c_company_code'
		and ct_select = '1' ";
	$result = sql_query($sql);

	$cartSelcetSql = "select * from {$g5['g5_shop_cart_table']} where od_id = '$c_od_id'";
	$ct_result = sql_query($cartSelcetSql);
	for ($i = 0; $ct = sql_fetch_array($ct_result); $i++) { 
		$stock_use = $ct['ct_stock_use'];
		if (!$ct['ct_stock_use'] || $ct['ct_stock_use'] == 0) { 
			$stock_use = 1;
			$ctIdStock = $ct['ct_id'];
			$ctQtyStock = $ct['ct_qty'];
			$ctItidStock = $ct['it_id'];
			$ctIoidStock = $ct['io_id'];
			$ctIotypeStock = $ct['io_type'];

			if ($ct['io_id']) {
				$sql = " update {$g5['g5_shop_item_option_table']}
							set io_stock_qty = io_stock_qty - '{$ctQtyStock}'
							where it_id = '{$ctItidStock}'
							  and io_id = '{$ctIoidStock}'
							  and io_type = '{$ctIotypeStock}' ";
			} else {
				$sql = " update {$g5['g5_shop_item_table']}
							set it_stock_qty = it_stock_qty - '{$ctQtyStock}'
							where it_id = '{$ctItidStock}' ";
			}
			sql_query($sql);

			$sql = "update {$g5['g5_shop_cart_table']} set ct_stock_use  = '$stock_use' where ct_id = '$ctIdStock' ";
			sql_query($sql);

			$sql = "update {$g5['g5_shop_item_table']} set it_sales_num  =  it_sales_num + '{$ctQtyStock}' where it_id = '{$ctItidStock}' ";
			sql_query($sql);
		}
		$sel = "SELECT * FROM lt_shop_item  WHERE it_id = '{$ct["it_id"]}' limit 1";
		$si = sql_fetch($sel);
		if ($si && $point_check == 1) {
			$ip = $si['it_point'];
			$ipt = $si['it_point_type'];
			if($ipt =='2') {
				$point = '(((ct_price + io_price) / 100) * '.$si['it_point']. ') * ct_qty';
			} elseif ($ipt =='0') {
				$point = $si['it_point']. '* ct_qty';
			} elseif ($ipt =='9') {
				$point = 0;
			} else {
				$point = '(((ct_price + io_price) / 100) * '.$default['de_point_percent']. ') * ct_qty';
			}
			$sql = "update {$g5['g5_shop_cart_table']} set ct_point_save = $point where ct_id = '{$ct["ct_id"]}' ";
			sql_query($sql);
			$sql = "update {$g5['g5_shop_order_table']} set od_point_save = 1 where od_id = '{$ct["od_id"]}' ";
			sql_query($sql);
		} else {
		}
	}

	// 주문정보 입력 오류시 결제 취소
	if (!$result) {
		if ($tno) {
			$cancel_msg = '주문상태 변경 오류';
			include G5_SHOP_PATH . '/lg/xpay_cancel.php';
		}

		// 관리자에게 오류 알림 메일발송
		$error = 'status';
		include G5_SHOP_PATH . '/ordererrormail.php';

		// 주문삭제
		sql_query(" delete from {$g5['g5_shop_order_table']} where od_id = '$od_id' ");
		sql_query(" delete from {$g5['g5_shop_order_table']} where main_od_id = '$od_id' ");

		die('<p>고객님의 주문 정보를 처리하는 중 오류가 발생해서 주문이 완료되지 않았습니다.</p><p>' . strtoupper($od_pg) . '를 이용한 전자결제(신용카드, 계좌이체, 가상계좌 등)은 자동 취소되었습니다.');
	}

	//주문상품 상세 정보 저장 => lt_shop_order_item (수량별 1개씩 데이타가 생성됨. RFID, 세탁,보관,수선 추적을 위함)
	$sql = " select   a.ct_id, a.od_type, a.od_id, a.mb_id
		, a.it_id,a.it_name,a.ct_status,a.ct_price,a.ct_option,a.ct_qty
		, a.io_id,a.io_type,a.io_price,a.ct_time,a.ct_receipt_price,a.ct_status_claim
		, b.its_sap_code, b.its_order_no, a.its_no
		, c.io_sapcode_color_gz,c.io_order_no,c.io_color_name,c.io_hoching,c.io_sap_price
		, b.its_free_laundry, b.its_free_laundry_delivery_price, b.its_laundry_use, b.its_laundry_price, b.its_laundry_delivery_price
		, b.its_laundrykeep_use, b.its_laundrykeep_lprice, b.its_laundrykeep_kprice, b.its_laundrykeep_delivery_price
		, b.its_repair_use, b.its_repair_price, b.its_repair_delivery_price
		, b.its_zbox_name, b.its_zbox_price
		from lt_shop_cart as a
        		inner join lt_shop_item_option as c
        		  on a.it_id = c.it_id and a.io_id = c.io_id and a.its_no = c.its_no and a.io_sapcode_color_gz = c.io_sapcode_color_gz
        		inner join lt_shop_item_sub as b
        		  on a.its_no = b.its_no
		where a.od_id = '$c_od_id'
		and a.ct_select = '1'
		order by a.it_id, a.ct_id";

	$od_sub_result = sql_query($sql);
	$od_sub_id = 1;

	$sql = " INSERT INTO lt_shop_order_item
		( od_sub_id,  od_id, ct_id, od_type, mb_id
		, it_id, it_name, ct_status, ct_price, ct_option
		, io_id, io_type, io_price, ct_time, ct_receipt_price, ct_status_claim
		, its_sap_code, its_order_no, its_no
		, io_sapcode_color_gz,io_order_no,io_color_name,io_hoching,io_sap_price
		, ct_free_laundry, ct_free_laundry_delivery_price
		, ct_laundry_use, ct_laundry_price, ct_laundry_delivery_price
		, ct_laundrykeep_use, ct_laundrykeep_lprice, ct_laundrykeep_kprice, ct_laundrykeep_delivery_price
		, ct_repair_use, ct_repair_price, ct_repair_delivery_price
		, ct_zbox_name, ct_zbox_price
		)
		VALUES  ";

	$comma = '';
	for ($i = 0; $row = sql_fetch_array($od_sub_result); $i++) {
		for ($j = 0; $j < (int) $row['ct_qty']; $j++) {

			$ct_receipt_price = (int) $row['ct_receipt_price'] / (int) $row['ct_qty'];

			$sql .= $comma . "( right(concat('0000','$od_sub_id'),4), '{$row['od_id']}', '{$row['ct_id']}', '{$row['od_type']}', '{$row['mb_id']}'
				, '{$row['it_id']}', '{$row['it_name']}', '{$row['ct_status']}', '{$row['ct_price']}', '{$row['ct_option']}'
				, '{$row['io_id']}', '{$row['io_type']}', '{$row['io_price']}', '{$row['ct_time']}', '{$ct_receipt_price}', '{$row['ct_status_claim']}'
				, '{$row['its_sap_code']}', '{$row['its_order_no']}', '{$row['its_no']}'
				, '{$row['io_sapcode_color_gz']}', '{$row['io_order_no']}', '{$row['io_color_name']}', '{$row['io_hoching']}', '{$row['io_sap_price']}'
				, '{$row['its_free_laundry']}',  '{$row['its_free_laundry_delivery_price']}'
				, '{$row['its_laundry_use']}',  '{$row['its_laundry_price']}',  '{$row['its_laundry_delivery_price']}'
				, '{$row['its_laundrykeep_use']}',  '{$row['its_laundrykeep_lprice']}',  '{$row['its_laundrykeep_kprice']}',  '{$row['its_laundrykeep_delivery_price']}'
				, '{$row['its_repair_use']}',  '{$row['its_repair_price']}',  '{$row['its_repair_delivery_price']}'
				, '{$row['its_zbox_name']}',  '{$row['its_zbox_price']}'
			)";
			$comma = ',';
			$od_sub_id++;
		}
	}
	if ($comma != '') $result = sql_query($sql);
	// 주문정보 입력 오류시 결제 취소
	if (!$result) {
		if ($tno) {
			$cancel_msg = '주문상태 변경 오류';
			switch ($od_pg) {
				case 'lg':
					include G5_SHOP_PATH . '/lg/xpay_cancel.php';
					break;
				case 'inicis':
					include G5_SHOP_PATH . '/inicis/inipay_cancel.php';
					break;
				case 'KAKAOPAY':
					$_REQUEST['TID']               = $tno;
					$_REQUEST['Amt']               = $amount;
					$_REQUEST['CancelMsg']         = $cancel_msg;
					$_REQUEST['PartialCancelCode'] = 0;
					include G5_SHOP_PATH . '/kakaopay/kakaopay_cancel.php';
					break;
				default:
					include G5_SHOP_PATH . '/kcp/pp_ax_hub_cancel.php';
					break;
			}
		}

		// 관리자에게 오류 알림 메일발송
		$error = 'status';
		include G5_SHOP_PATH . '/ordererrormail.php';

		// 주문삭제
		sql_query(" delete from {$g5['g5_shop_order_table']} where od_id = '$od_id' ");

		die('<p>고객님의 주문 정보를 처리하는 중 오류가 발생해서 주문이 완료되지 않았습니다.</p><p>' . strtoupper($od_pg) . '를 이용한 전자결제(신용카드, 계좌이체, 가상계좌 등)은 자동 취소되었습니다.....');
	}
}

// 회원이면서 포인트를 사용했다면 테이블에 사용을 추가
if ($is_member && $od_receipt_point)
	insert_point($member['mb_id'], (-1) * $od_receipt_point, "주문번호 $od_id 결제", "@order", $od_id, "결제");


// 쿠폰사용내역기록
if ($is_member && count($arr_used_coupon) > 0) {
	foreach ($arr_used_coupon as $method => $coupons) {
		foreach ($coupons as $coupon) {
			if (isset($coupon['ct_id'])) {
				$libCoupon->useCoupon($coupon['cp']['cp_id'], $od_id, $coupon['ct_id']);
			} else {
				$libCoupon->useCoupon($coupon['cp']['cp_id'], $od_id);
			}
		}
	}
}
$cartSelcetSql2 = "select * from {$g5['g5_shop_cart_table']} where od_id = '$c_od_id'";
$ct_result2 = sql_query($cartSelcetSql2);
for ($i2 = 0; $ct2 = sql_fetch_array($ct_result2); $i2++) { 
	$pieceRefund = $ct2['ct_price'] * $ct2['ct_qty'] - $ct2['cp_price'];
	$pieceCartRat = $pieceRefund / ($od_receipt_price + $od_receipt_point + $tot_od_cp_price + $tot_sc_cp_price - ($od_send_cost) );  
	$pieceCartCoupon = round($tot_od_cp_price * $pieceCartRat,0);
	$pieceCartPoint = round($od_receipt_point * $pieceCartRat,0);
	$ct_cart_price_ori = $pieceRefund - $pieceCartCoupon - $pieceCartPoint;
	$ctIdStock = $ct2['ct_id'];
	$sql = "update {$g5['g5_shop_cart_table']} set ct_cart_coupon_price = '$pieceCartCoupon', ct_cart_price_ori = '$ct_cart_price_ori' where ct_id = '$ctIdStock' ";
	sql_query($sql);
}

include(G5_SHOP_PATH . '/ordermail1.inc.php');

$arr_change_data = array();
$arr_change_data['고객명'] = $od_name;
$arr_change_data['이름'] = $od_name;
$arr_change_data['보낸분'] = $od_name;
$arr_change_data['주문번호'] = $od_id;
$arr_change_data['총주문금액'] = number_format($tot_ct_price + $od_send_cost);
$arr_change_data['회원아이디'] = $member['mb_id'];
$arr_change_data["od_list"] = $list;
$arr_change_data['od_type'] = $od_type;
$arr_change_data['od_id'] = $od_id;

$arr_change_data['button'] = array(
	"type" => "웹링크",
	"txt" => "주문상세보기",
	"link" => "https://www.lifelike.co.kr/member/order.php?od_id=" . $arr_change_data['od_id']
);

msg_autosend('주문', '결제 완료', $member['mb_id'], $arr_change_data);
//include_once(G5_SHOP_PATH.'/ordermail2.inc.php');
if (count($company_od_id_list) > 0) {
	$org_od_id = $od_id;
	//입점몰 메시징 처리
	for ($i = 0; $i < count($company_od_id_list); $i++) {

		$od_id = $company_od_id_list[$i];
		include(G5_SHOP_PATH . '/ordermail1.inc.php');
		$arr_change_data = array();
		$arr_change_data['고객명'] = $od_name;
		$arr_change_data['이름'] = $od_name;
		$arr_change_data['보낸분'] = $od_name;
		$arr_change_data['주문번호'] = $od_id;
		$arr_change_data['총주문금액'] = number_format($tot_ct_price + $od_send_cost);
		$arr_change_data['회원아이디'] = $member['mb_id'];
		$arr_change_data["od_list"] = $list;
		$arr_change_data['od_type'] = $od_type;
		$arr_change_data['od_id'] = $od_id;
		msg_autosend('주문', '결제 완료', $member['mb_id'], $arr_change_data);
	}
	$od_id = $org_od_id;
}

// orderview 에서 사용하기 위해 session에 넣고
$uid = md5($od_id . G5_TIME_YMDHIS . $REMOTE_ADDR);
set_session('ss_orderview_uid', $uid);

// 주문 정보 임시 데이터 삭제
if ($od_pg == 'inicis') {
	$sql = " delete from {$g5['g5_shop_order_data_table']} where od_id = '$od_id' and dt_pg = '$od_pg' ";
	sql_query($sql);
}

// 주문번호제거
set_session('ss_order_id', '');

// 기존자료 세션에서 제거
if (get_session('ss_direct'))
	set_session('ss_cart_direct', '');

// 배송지처리
if ($is_member) {
	$sql = "SELECT * from {$g5['g5_shop_order_address_table']}
	WHERE mb_id = '{$member['mb_id']}'
	AND ad_name = '$od_b_name'
	AND ad_tel = '$od_b_tel'
	AND ad_hp = '$od_b_hp'
	AND ad_zip1 = '$od_b_zip1'
	AND ad_zip2 = '$od_b_zip2'
	AND ad_addr1 = '$od_b_addr1'
	AND ad_addr2 = '$od_b_addr2'
	AND ad_addr3 = '$od_b_addr3' ";
	$row = sql_fetch($sql);

	// 기본배송지 체크
	if ($ad_default || $ad_append) {
		if ($ad_default) {
			// 기존 기본 배송지 정보 초기화
			$sql = "UPDATE {$g5['g5_shop_order_address_table']} SET ad_default = '0' WHERE mb_id = '{$member['mb_id']}' ";
			sql_query($sql);
		}

		$ad_subject = clean_xss_tags($od_b_name);

		if ($row['ad_id']) {
			$sql = "UPDATE {$g5['g5_shop_order_address_table']}
					SET ad_default = '$ad_default',
					    ad_subject = '$ad_subject',
					    ad_jibeon  = '$od_b_addr_jibeon'
					WHERE mb_id = '{$member['mb_id']}'
					AND ad_id = '{$row['ad_id']}' ";
		} else {
			$sql = "INSERT INTO {$g5['g5_shop_order_address_table']}
					SET  mb_id   = '{$member['mb_id']}',
					ad_subject  = '$ad_subject',
					ad_default  = '$ad_default',
					ad_name     = '$od_b_name',
					ad_tel      = '$od_b_tel',
					ad_hp       = '$od_b_hp',
					ad_zip1     = '$od_b_zip1',
					ad_zip2     = '$od_b_zip2',
					ad_addr1    = '$od_b_addr1',
					ad_addr2    = '$od_b_addr2',
					ad_addr3    = '$od_b_addr3',
					ad_jibeon   = '$od_b_addr_jibeon' ";
		}

		sql_query($sql);
	}
}

// 네이버 분석스크립트 삽입
echo '
<script type="text/javascript" src="https://wcs.naver.net/wcslog.js"></script> 
<script type="text/javascript"> 
	var _nasa={};
	_nasa["cnv"] = wcs.cnv("1","' . $tot_ct_price + $od_send_cost . '"); // 전환유형, 전환가치 설정해야함. 설치매뉴얼 참고
	if (!wcs_add) var wcs_add={};
	wcs_add["wa"] = "s_3a59b688a58f";
	if (!_nasa) var _nasa={};
	wcs.inflow();
	wcs_do(_nasa);
</script>
';
goto_url('/member/order.complate.php?od_id=' . $od_id);
