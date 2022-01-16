<?
include_once('./_common.php');
/*
// 세션에 저장된 토큰과 폼으로 넘어온 토큰을 비교하여 틀리면 에러
if ($token && get_session("ss_token") == $token) {
	// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
	set_session("ss_token", "");
} else {
	set_session("ss_token", "");
	alert("토큰 에러");
}
*/

$od = sql_fetch(" select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' and mb_id = '{$member['mb_id']}' ");

if (!$od['od_id']) {
	alert("존재하는 주문이 아닙니다.");
}

$od_receipt_price_company = (int) $od['od_receipt_price'] - (int) $od['od_refund_price'] - (int) $od['od_cancel_price'];
// 주문상품의 상태가 주문인지 체크
if ($od['od_type'] == "O") {
	//제품의 경우 입점몰여부 체크해서 부분 취소로 진행할 수 있도록 함.
	$sql = " select COUNT(*) as od_count1, 
					SUM(IF(ct_status in ('주문','결제완료','계약등록','세탁신청','보관신청','수선신청'), 1, 0)) as od_count2,
					SUM(if(od_id = '$od_id', 1, 0)) od_count3,
					SUM(IF(ct_status in ('주문','결제완료','계약등록','세탁신청','보관신청','수선신청') and od_id = '$od_id', 1, 0)) as od_count4
				from lt_shop_cart
				where od_id in (select od_id from lt_shop_order where main_od_id ='{$od['main_od_id']}' )";
	$ct = sql_fetch($sql);
	//입점몰 포함 취소건일 경우
	if ($ct['od_count1'] != $ct['od_count3']) {
		$od_company = sql_fetch(" select sum(od_receipt_price - od_refund_price) as od_receipt_price  from lt_shop_order where main_od_id ='{$od['main_od_id']}'");
		$od_receipt_price_company = (int) $od_company['od_receipt_price'];
	}
} else {
	$sql = " select SUM(IF(ct_status in ('주문','결제완료','계약등록','세탁신청','보관신청','수선신청'), 1, 0)) as od_count2,
					COUNT(*) as od_count1
				from {$g5['g5_shop_cart_table']}
				where od_id = '$od_id' ";
	$ct = sql_fetch($sql);
}

$uid = md5($od['od_id'] . $od['od_time'] . $od['od_ip']);

// if ($od['od_cancel_price'] > 0 || $ct['od_count2'] == 0) {
// 	alert("취소할 수 있는 주문이 아닙니다.", "", true, true);
// }

$count = 1;

switch ($od['od_type']) {
	case 'O':
		$od_type_name = '주문';
		$count = count($_POST['chk']);
		if (!$count) {
			alert('취소할 주문을 1개 이상 선택해 주세요.');
		}
		require_once('./settle_lg.inc.php');
		break;
	case 'R':
		$od_type_name = '계약';
		$count = count($_POST['chk']);
		if (!$count) {
			alert('취소할 주문을 1개 이상 선택해 주세요.');
		}
		require_once('./settle_lg2.inc.php');
		break;
	case 'L':
		$od_type_name = '세탁';
		require_once('./settle_lg3.inc.php');
		break;
	case 'K':
		$od_type_name = '세탁보관';
		require_once('./settle_lg3.inc.php');
		break;
	case 'S':
		$od_type_name = '수선';
		require_once('./settle_lg3.inc.php');
		break;
	default:
		$od_type_name = '주문';
		$count = count($_POST['chk']);
		if (!$count) {
			alert('취소할 주문을 1개 이상 선택해 주세요.');
		}
		require_once('./settle_lg.inc.php');
		break;
}

$cnt_order_item = 0;
$cnt_cancel_item = 0;
$cnt_cart_item = array();
$ordered_items = array();

$sql_price_order_item = "SELECT SUM(ct_price) AS price_ordered, SUM(IF(ct_status IN ('배송중','배송완료','구매확정'),1,0)) AS unable FROM lt_shop_order_item WHERE od_id={$od_id} AND ct_status IN ('주문','결제완료','계약등록','세탁신청','보관신청','수선신청')";
$sql_cart_order_item = "SELECT * FROM lt_shop_order_item WHERE od_id={$od_id} AND ct_status IN ('주문','결제완료','계약등록','세탁신청','보관신청','수선신청')";
$db_price_order_item = sql_fetch($sql_price_order_item);
$db_cart_order_item = sql_query($sql_cart_order_item);

//부분취소 전체 취소 체크
$db_origin_order_sql = "SELECT count(*) AS CNT FROM lt_shop_order_item WHERE od_id = {$od_id} ";
$db_origin_order_item = sql_fetch($db_origin_order_sql);

$db_origin_order_cart_sql = "SELECT count(*) AS CNT FROM lt_shop_cart WHERE od_id = {$od_id} ";
$db_origin_order_cart = sql_fetch($db_origin_order_cart_sql);

while (false != ($cart_order_item = sql_fetch_array($db_cart_order_item))) {
	$tmp_ordered_item = array();
	$tmp_ordered_item['od_id'] = $cart_order_item['od_id'];
	$tmp_ordered_item['ct_id'] = $cart_order_item['ct_id'];
	$tmp_ordered_item['ct_price'] = $cart_order_item['ct_price'];
	$tmp_ordered_item['od_sub_id'] = $cart_order_item['od_sub_id'];
	$tmp_ordered_item['price_ordered'] = $db_price_order_item['price_ordered'];
	$tmp_ordered_item['ratio'] = ($cart_order_item['ct_price'] / $db_price_order_item['price_ordered']) * 100;
	$tmp_ordered_item['canceled'] = false;

	$ordered_items[] = $tmp_ordered_item;
	$cnt_order_item++;

	if (!isset($cnt_cart_item[$cart_order_item['ct_id']])) {
		$cnt_cart_item[$cart_order_item['ct_id']] = 0;
	}
	$cnt_cart_item[$cart_order_item['ct_id']]++;
}
$cnt_cancel_cart = 0;
foreach ($_POST['chk'] as $ct_id) {
	$cnt_cancel_cart+=1;
	$cnt_cancel_item += $_POST['ct_qty'][$ct_id];
}

// echo "<pre>";
// var_dump($cnt_cart_item);
// var_dump($cnt_cancel_item);
// var_dump($cnt_order_item);
// var_dump($ordered_items);
// var_dump($db_price_order_item);
// exit();


// if (($cnt_cancel_item == $cnt_order_item && $db_price_order_item['unable'] == 0) || $db_origin_order_cart['CNT'] == $cnt_cancel_cart ) {
if ($db_origin_order_cart['CNT'] == $cnt_cancel_cart ) {
	//결제완료 건수 == 취소건수 : 전체취소
	//PG 결제 취소
	if ($od['od_tno']) {
		$LGD_TID    = $od['od_tno'];        //LG유플러스으로 부터 내려받은 거래번호(LGD_TID)
		$xpay = new XPay($configPath, $CST_PLATFORM);
		// Mert Key 설정
		$xpay->set_config_value('t' . $LGD_MID, $LGD_MERTKEY);
		$xpay->set_config_value($LGD_MID, $LGD_MERTKEY);
		$xpay->Init_TX($LGD_MID);

		$xpay->Set("LGD_TXNAME", "Cancel");
		$xpay->Set("LGD_TID", $LGD_TID);

		if ($xpay->TX()) {
			//1)결제취소결과 화면처리(성공,실패 결과 처리를 하시기 바랍니다.)
			/*
			echo "결제 취소요청이 완료되었습니다.  <br>";
			echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
			echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
			*/
		} else {
			//2)API 요청 실패 화면처리
			$msg = "결제 취소요청이 실패하였습니다.\\n";
			$msg .= "TX Response_code = " . $xpay->Response_Code() . "\\n";
			$msg .= "TX Response_msg = " . $xpay->Response_Msg();

			alert($msg);
		}
	}

	// 장바구니 자료 취소
	sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '주문취소', ct_status_claim = '주문취소', ct_point_save  = NULL where od_id = '$od_id' ");

	// 주문 취소
	$cancel_select      = clean_xss_tags($cancel_select);
	$cancel_memo      = '[' . $cancel_select . ']' . clean_xss_tags($cancel_memo);
	$cancel_price = $od['od_receipt_price'];

	$sql = " update {$g5['g5_shop_order_table']}
				set od_receipt_price = '0',
					od_receipt_point = '0',
					od_misu = '0',
					od_cancel_price = '$cancel_price',
					od_cart_coupon = '0',
					od_coupon = '0',
					od_send_coupon = '0',
					od_refund_price = '0',
					od_status = '주문취소',
					od_status_claim = '주문취소',
					od_status_claim_date = '" . G5_TIME_YMDHIS . "',
					od_shop_memo = concat(od_shop_memo,\"\\n주문자 본인 직접 취소 - " . G5_TIME_YMDHIS . " (취소이유 : {$cancel_memo})\"),
					od_receipt_refund_price_ori = 0,
					od_cart_coupon_ori = 0,
					od_coupon_ori = 0
				where od_id = '$od_id' ";
	sql_query($sql);

	// 주문취소 회원의 포인트를 되돌려 줌
	if ($od['od_receipt_point'] > 0)
		insert_point($member['mb_id'], $od['od_receipt_point'], "주문번호 $od_id 본인 취소", "@order", $od_id, "본인 취소");

	//주문취소 회원의 쿠폰도 되돌려 줌 => 쿠폰 사용 기록 삭제
	require_once(G5_LIB_PATH . "/coupon.lib.php");
	$libCoupon = new coupon;
	$coupon = sql_query("SELECT cp_id FROM lt_shop_coupon_log WHERE od_id={$od_id}");
	while (false != ($cart_cp = sql_fetch_array($coupon))) {
		$libCoupon->returnCoupon($cart_cp['cp_id']);
	}

	$sql = " insert into lt_shop_order_history
			(od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim, cancel_select)
		 values
			('$od_id', 1, '[전체주문취소] " . $cancel_memo . "', '" . G5_TIME_YMDHIS . "', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}', '주문취소', '$cancel_select'); ";
	sql_query($sql);


	switch ($od['od_type']) {
		case 'L':
		case 'K':
		case 'S':
			$ct = sql_fetch(" select * from {$g5['g5_shop_cart_table']} where od_id = '$od_id' ");

			//대상제품의 상태 초기화
			sql_query("update lt_shop_order_item set ct_status = '' where ct_id = '{$ct['buy_ct_id']}' and od_sub_id  = '{$ct['buy_od_sub_id']}'");
			break;
	}

	$cart_item_all = sql_query(" select * from {$g5['g5_shop_cart_table']} where od_id = '$od_id' ");
	for ($i = 0; $rowCart = sql_fetch_array($cart_item_all); $i++) { 
		$itIDC = $rowCart['it_id'];
		$ctIDC = $rowCart['ct_id'];
		$ctQtyC = $rowCart['ct_qty'];
		$ioIdC = $rowCart['io_id'];
		$ioTypeC = $cart_item['io_type'];
		// 재고 추가 !
		if ($ioIdC) {
			$sql = " update {$g5['g5_shop_item_option_table']}
						set io_stock_qty = io_stock_qty + '{$ctQtyC}'
						where it_id = '{$itIDC}'
						  and io_id = '{$ioIdC}'
						  and io_type = '{$ioTypeC}' ";
		} else {
			$sql = " update {$g5['g5_shop_item_table']}
						set it_stock_qty = it_stock_qty + '{$ctQtyC}'
						where it_id = '{$itIDC}' ";
		}
		sql_query($sql);
		$stock_use = 2;
		$stockSql = " update {$g5['g5_shop_cart_table']} set ct_stock_use  = '$stock_use' where ct_id = '{$ctIDC}' ";
		sql_query($stockSql);
	}

	// 주문취소 기록
	sql_query("UPDATE lt_shop_order_item SET ct_status='주문취소' WHERE od_id='{$od_id}'");

	$sql_coupons = "SELECT * FROM lt_shop_coupon_log WHERE od_id='{$od_id}'";
	$db_coupons = sql_query($sql_coupons);
	while (false != ($oc = sql_fetch_array($db_coupons))) {
		$libCoupon->returnCoupon($oc['cp_id']);
	}

	$od_type = $od['od_type'];
	if ($od_type == "O") {
		include_once(G5_SHOP_PATH . '/ordermail1.inc.php');
		$arr_change_data = array();
		$arr_change_data["od_list"] = $list;
		$arr_change_data['od_type'] = $od_type;
		$arr_change_data['od_id'] = $od_id;
		$arr_change_data['취소금액'] = number_format($cancel_price) . "원";
		msg_autosend('주문', '취소 완료', $od['mb_id'], $arr_change_data);
	}
} else {
	$price_ordered_total = $od['od_cart_price'];
	$price_cancel_total = 0;
	$point_cancel_total = 0;
	$coupon_cancel_total = 0;

	$set_cancel_oi_id = array();
	$cancel_cart_coupon = array();

	foreach ($_POST['chk'] as $ct_id) {
		$cc_qty = $_POST['ct_qty'][$ct_id];	
	}
	$price_cancel_total = $_POST['pieceRefund'];
	$pieceCartCoupon = $_POST['pieceCartCoupon'];
	$tax_mny = $price_cancel_total;
	$od_receipt_price_company = (int) $od['od_receipt_price'] - $od['od_cancel_price'];
	if ($tax_mny == 0) {
		alert('취소할 금액이 없습니다.');
	}
	if($od['od_cancel_price'] + $tax_mny == $od['od_receipt_price'] + 1){
		$tax_mny = $tax_mny -1;
	}

	$cancel_select      = clean_xss_tags($cancel_select);
	$cancel_memo      = '[' . $cancel_select . ']' . clean_xss_tags($cancel_memo);
	/*
	 * [결제 부분취소 요청 페이지]
	 *
	 * LG유플러스으로 부터 내려받은 거래번호(LGD_TID)를 가지고 취소 요청을 합니다.(파라미터 전달시 POST를 사용하세요)
	 * (승인시 LG유플러스으로 부터 내려받은 PAYKEY와 혼동하지 마세요.)
	 */

	$LGD_TID              		= $od['od_tno'];			  		                            //LG유플러스으로 부터 내려받은 거래번호(LGD_TID)
	$LGD_CANCELAMOUNT     		= (int) $tax_mny;                                                //부분취소 금액
	//$LGD_REMAINAMOUNT     		= (int)$od['od_receipt_price'] - (int)$od['od_refund_price'];   //취소전 남은금액
	$LGD_REMAINAMOUNT     		= $od_receipt_price_company;
	$LGD_CANCELTAXFREEAMOUNT    = (int) $free_mny;                                               //면세대상 부분취소 금액 (과세/면세 혼용상점만 적용)
	$LGD_CANCELREASON     		= $cancel_memo;                                                    //취소사유
	$LGD_RFACCOUNTNUM           = $_POST['LGD_RFACCOUNTNUM'];	 		                        //환불계좌 번호(가상계좌 환불인경우만 필수)
	$LGD_RFBANKCODE             = $_POST['LGD_RFBANKCODE'];	 		                            //환불계좌 은행코드(가상계좌 환불인경우만 필수)
	$LGD_RFCUSTOMERNAME         = $_POST['LGD_RFCUSTOMERNAME']; 		                        //환불계좌 예금주(가상계좌 환불인경우만 필수)
	$LGD_RFPHONE                = $_POST['LGD_RFPHONE'];		 		                        //요청자 연락처(가상계좌 환불인경우만 필수)

	$xpay = new XPay($configPath, $CST_PLATFORM);

	// Mert Key 설정
	$xpay->set_config_value('t' . $LGD_MID, $LGD_MERTKEY);
	$xpay->set_config_value($LGD_MID, $LGD_MERTKEY);

	$xpay->Init_TX($LGD_MID);

	$xpay->Set("LGD_TXNAME",                "PartialCancel");
	$xpay->Set("LGD_TID",                   $LGD_TID);
	$xpay->Set("LGD_CANCELAMOUNT",          $LGD_CANCELAMOUNT);
	$xpay->Set("LGD_REMAINAMOUNT",          $LGD_REMAINAMOUNT);
	$xpay->Set("LGD_CANCELTAXFREEAMOUNT",   $LGD_CANCELTAXFREEAMOUNT);
	$xpay->Set("LGD_CANCELREASON",          $LGD_CANCELREASON);
	$xpay->Set("LGD_RFACCOUNTNUM",          $LGD_RFACCOUNTNUM);
	$xpay->Set("LGD_RFBANKCODE",            $LGD_RFBANKCODE);
	$xpay->Set("LGD_RFCUSTOMERNAME",        $LGD_RFCUSTOMERNAME);
	$xpay->Set("LGD_RFPHONE",               $LGD_RFPHONE);
	$xpay->Set("LGD_REQREMAIN",             "0");
	$xpay->Set("LGD_ENCODING",              "UTF-8");
	
	// -------------
	// 주문한거 3개가 나오는구먼

	/*
	 * 1. 결제 부분취소 요청 결과처리
	 *
	 */
	if ($xpay->TX()) {
		// 1)결제 부분취소결과 처리
		if ('0000' == $xpay->Response_Code()) {
			$mod_mny = (int) $tax_mny + (int) $free_mny;

			//주문취소 기록
			require_once(G5_LIB_PATH . "/coupon.lib.php");
			$libCoupon = new coupon;
			$cpPrice = 0;
			foreach ($_POST['chk'] as $ct_id) {
				$cart_item = sql_fetch("SELECT * FROM " . $g5['g5_shop_cart_table'] . " WHERE ct_id=" . $ct_id);
				$it_name = $cart_item['it_name'];
				$itID = $cart_item['it_id'];
				$ctQty = $cart_item['ct_qty'];
				$ioId = $cart_item['io_id'];
				$ioType = $cart_item['io_type'];
				$cpPrice += $cart_item['cp_price_ori'];
				$sql_set = array();

				// 카트 상태 변경
				
				// $sql_set[] = "UPDATE {$g5['g5_shop_cart_table']} set ct_status = '주문취소', ct_status_claim = '주문취소' where ct_id = '{$ct_id}' ";
				// 장바구니 자료 취소
				sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '주문취소', ct_status_claim = '주문취소', ct_point_save  = NULL where ct_id = '{$ct_id}' ");

				//발주서 출고 전 취소
				
				$ord_view = "update sabang_lt_order_view set ov_order_status = '출고전취소' , ov_distribution_status = '출고전취소' , ov_update_datetime = '{$now_date}' where ov_IDX = '{$ct_id}'";
				sql_query($ord_view);
				$ord_form = "update sabang_lt_order_form set dpartner_stat = '출고전취소' , update_dt = '{$now_date}'  where sabang_ord_no = '{$ct_id}'";
				sql_query($ord_form);

				// 재고 추가 !
				if ($ioId) {
					$sql = " update {$g5['g5_shop_item_option_table']}
								set io_stock_qty = io_stock_qty + '{$ctQty}'
								where it_id = '{$itID}'
								  and io_id = '{$ioId}'
								  and io_type = '{$ioType}' ";
				} else {
					$sql = " update {$g5['g5_shop_item_table']}
								set it_stock_qty = it_stock_qty + '{$ctQty}'
								where it_id = '{$itID}' ";
				}
				sql_query($sql);
				$stock_use = 2;
				$stockSql = " update {$g5['g5_shop_cart_table']} set ct_stock_use  = '$stock_use' where ct_id = '{$ct_id}' ";
				sql_query($stockSql);
				// 주문취소 기록
				foreach ($ordered_items as $oi) {
					// if ($oi['ct_id'] == $ct_id && $oi['canceled'])
					if ($oi['ct_id'] == $ct_id) {
						// $sql_set[] = "UPDATE lt_shop_order_item SET ct_status='주문취소' WHERE od_id='{$od_id}' AND od_sub_id='{$oi['od_sub_id']}'";
						$sql_set[] = "UPDATE lt_shop_order_item SET ct_status='주문취소' WHERE od_id='{$od_id}' AND ct_id='{$ct_id}'";
						$sql_set[] = "INSERT into lt_shop_order_history (od_id, od_sub_id, ct_id, it_name, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim, cancel_select)
									  VALUES ('{$od_id}', '{$oi['od_sub_id']}', '{$ct_id}', '{$it_name}', 1, '[주문취소] " . $cancel_memo . "', '" . G5_TIME_YMDHIS . "', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}', '주문취소', '{$cancel_select}'); ";
					}
				}

				foreach ($sql_set as $sql) {
					$sql_r = sql_query($sql);
					// var_dump($sql);
					// var_dump($sql_r);
				}
				// 최종 잔여금액 주문서 쿠폰 사용가능 금액 비교 필요
			}

			$tmp_sql_order = "UPDATE lt_shop_order SET od_cancel_price=od_cancel_price+{$mod_mny} ,od_status_claim_date = '" .G5_TIME_YMDHIS . "' ";

			// 포인트 반환 관련 처리
			if ($od['od_receipt_point']  > 0 && $_POST['piecePointCheck'] == 1) {
				insert_point($member['mb_id'], $od['od_receipt_point'], '주문취소 ' . $it_name . ' 반환', '@cart', $ct_id);
				$sql_set[] = "UPDATE lt_member SET mb_point=mb_point + {$od['od_receipt_point']} WHERE mb_id='{$member['mb_id']}'";
				$tmp_sql_order .= ", od_receipt_point=od_receipt_point - {$od['od_receipt_point']}";
			}
			$tmp_sql_order = $tmp_sql_order . " WHERE od_id='{$od_id}'";
			sql_query($tmp_sql_order);

			// 취소금액기록
			$tno = $xpay->Response("LGD_TID", 0);
			if (!$cpPrice) $cpPrice = 0;
			if (!$pieceCartCoupon) $pieceCartCoupon = 0;
			$sql_a = "UPDATE {$g5['g5_shop_order_table']}
					SET 
						od_shop_memo = concat(od_shop_memo, \"$cancel_memo\"),
						od_receipt_refund_price_ori = od_receipt_refund_price_ori-{$mod_mny},
						od_coupon_ori = od_coupon_ori-{$pieceCartCoupon},
						od_cart_coupon_ori = od_cart_coupon_ori-{$cpPrice}
					WHERE od_id = '{$od['od_id']}'
				  	AND od_tno = '$tno' ";

			/*
			if ($ct['od_count4'] == $count) {
				//입점몰 복합결제일 경우 부분취소지만 해당 주문건은 전체 주문취소로 변경함.
				$sql = " update {$g5['g5_shop_order_table']}
				set od_receipt_price = '0',
					od_receipt_point = '0',
					od_misu = '0',
					od_cancel_price = od_refund_price + '$mod_mny',
					od_cart_coupon = '0',
					od_coupon = '0',
					od_send_coupon = '0',
					od_refund_price = '0',
					od_status = '',
					od_status_claim = '주문취소',
					od_status_claim_date = '" . G5_TIME_YMDHIS . "',
					od_shop_memo = concat(od_shop_memo,\"\\n[부분취소] 주문자 본인 직접 취소 - " . G5_TIME_YMDHIS . " (취소이유 : {$cancel_memo})\")
				where od_id = '$od_id' ";
			}
			*/

			
			sql_query($sql_a);

			//모든 상품 주문 취소 상태
			$all_cancel = "SELECT * FROM lt_shop_order WHERE od_id = '{$od_id}'";
			$all_cancel_order = sql_fetch($all_cancel);
			if($all_cancel_order['od_receipt_price'] == $all_cancel_order['od_cancel_price']){
				$all_cancel_order_stat = "UPDATE {$g5['g5_shop_order_table']} SET od_status= '주문취소' WHERE od_id = {$od_id}";
				sql_query($all_cancel_order_stat);
			}

			// 허들 깨지면 check
			$pieceHurdleCheck = $_POST['pieceHurdleCheck'];
			if ($pieceHurdleCheck ==1) {
				$hurdleCheck = "UPDATE lt_shop_coupon_log SET cp_hurdle_check= 1 WHERE od_id = {$od_id} AND ct_id IS NULL";
				sql_query($hurdleCheck);
			}


			$od_type = $od['od_type'];
			if ($od_type == "O") {
				include_once(G5_SHOP_PATH . '/ordermail1.inc.php');
				$arr_change_data = array();
				$arr_change_data["od_list"] = $list;
				$arr_change_data['od_type'] = $od_type;
				$arr_change_data['od_id'] = $od_id;
				$arr_change_data['취소금액'] = number_format($mod_mny) . "원";
				msg_autosend('주문', '취소 완료', $od['mb_id'], $arr_change_data);
			}

			// 미수금 등의 정보 업데이트
			/*
			$info = get_order_info($od_id);

			$sql = " update {$g5['g5_shop_order_table']}
				set od_misu     = '{$info['od_misu']}',
					od_tax_mny  = '{$info['od_tax_mny']}',
					od_vat_mny  = '{$info['od_vat_mny']}',
					od_free_mny = '{$info['od_free_mny']}'
				where od_id = '$od_id' ";
			sql_query($sql);
			*/
		} else {
			alert($xpay->Response_Msg() . ' 코드 : ' . $xpay->Response_Code());
		}
	} else {
		//2)API 요청 실패 화면처리
		/*
		 echo "결제 부분취소 요청이 실패하였습니다.  <br>";
		 echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
		 echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
		 */

		alert('결제 부분취소 요청이 실패하였습니다.\\n\\n' . $xpay->Response_Code() . ' : ' . $xpay->Response_Msg());
	}
}

goto_url("/member/order.cancel.complate.php?action=" . $act . "&od_id=" . $od_id . "&ct_id=" . implode(',', $_POST['chk']));