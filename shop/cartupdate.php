<?
include_once('./_common.php');

// 보관기간이 지난 상품 삭제
cart_item_clean();

// cart id 설정
set_cart_id($sw_direct);

if ($sw_direct) {
	$tmp_cart_id = get_session('ss_cart_direct');
} else {
	$tmp_cart_id = get_session('ss_cart_id');
}

// 브라우저에서 쿠키를 허용하지 않은 경우라고 볼 수 있음.
if (!$tmp_cart_id) {
	alert('더 이상 작업을 진행할 수 없습니다.\\n\\n브라우저의 쿠키 허용을 사용하지 않음으로 설정한것 같습니다.\\n\\n브라우저의 인터넷 옵션에서 쿠키 허용을 사용으로 설정해 주십시오.\\n\\n그래도 진행이 되지 않는다면 쇼핑몰 운영자에게 문의 바랍니다.');
}

$tmp_cart_id = preg_replace('/[^a-z0-9_\-]/i', '', $tmp_cart_id);

// 레벨(권한)이 상품구입 권한보다 작다면 상품을 구입할 수 없음.
if ($member['mb_level'] < $default['de_level_sell']) {
	alert('상품을 구입할 수 있는 권한이 없습니다.');
}

if ($is_member && $member['mb_block_shop'] == '1') {
	alert('상품을 구입하실 수 없습니다.');
}

if ($act == "buy") {
	if (!count($_POST['ct_chk'])) {
		alert("주문하실 상품을 하나이상 선택해 주십시오.");
	}

	// 선택필드 초기화
	$sql = " update " . $g5['g5_shop_cart_table'] . " set ct_select = '0' where od_id = '" . $tmp_cart_id . "' ";
	sql_query($sql);

	$fldcnt = count($_POST['it_id']);
	for ($i = 0; $i < $fldcnt; $i++) {
		$ct_chk = $_POST['ct_chk'][$i];
		if ($ct_chk) {
			$it_id = $_POST['it_id'][$i];

			// 주문 상품의 재고체크
			$sql = " select ct_qty, it_name, ct_option, io_id, io_type from " . $g5['g5_shop_cart_table'] . " where od_id = '" . $tmp_cart_id . "' and it_id = '" . $it_id . "' ";
			$result = sql_query($sql);

			for ($k = 0; $row = sql_fetch_array($result); $k++) {
				// $sql = " select SUM(ct_qty) as cnt from " . $g5['g5_shop_cart_table'] . "
				// 		  where od_id <> '" . $tmp_cart_id . "'
				// 			and it_id = '" . $it_id . "'
				// 			and io_id = '" . $row['io_id'] . "'
				// 			and io_type = '" . $row['io_type'] . "'
				// 			and ct_stock_use = 0
				// 			and ct_status = '쇼핑'
				// 			and ct_select = '1' ";
				// $sum = sql_fetch($sql);
				// $sum_qty = $sum['cnt'];
				$sum_qty = 0;

				// 재고 구함
				$ct_qty = $row['ct_qty'];
				if (!$row['io_id']) {
					$it_stock_qty = get_it_stock_qty($it_id);
				} else {
					$it_stock_qty = get_option_stock_qty($it_id, $row['io_id'], $row['io_type']);
				}

				if ($ct_qty + $sum_qty > $it_stock_qty) {
					$item_option = $row['it_name'];
					if ($row['io_id']) {
						$item_option .= '(' . $row['ct_option'] . ')';
					}
					alert($item_option . " 의 재고수량이 부족합니다.\\n\\n현재 재고수량 : " . number_format($it_stock_qty - $sum_qty) . " 개");
				}
			}

			$sql = " update " . $g5['g5_shop_cart_table'] . " set ct_select = '1', ct_select_time = '" . G5_TIME_YMDHIS . "' where od_id = '" . $tmp_cart_id . "' and it_id = '" . $it_id . "' ";
			sql_query($sql);
		}
	}

	goto_url(G5_SHOP_URL . '/orderform.php?od_type=' . $od_type);
	// goto_url(G5_SHOP_URL . '/orderform.old.php?od_type=' . $od_type);
} else if ($act == "alldelete") {
	// 모두 삭제이면
	$sql = "delete from " . $g5['g5_shop_cart_table'] . " where od_id = '" . $tmp_cart_id . "' ";
	sql_query($sql);
	goto_url(G5_SHOP_URL . '/cart.php');
} else if ($act == "soldoutdelete") {
	// 품절 상품 삭제
	$delcount = 0;
	$sql = "SELECT ct_id,it_id,io_id,io_type,ct_option FROM " . $g5['g5_shop_cart_table'] . " WHERE od_id = '" . $tmp_cart_id . "' ";
	$citems = sql_query($sql);
	while (false != ($citem = sql_fetch_array($citems))) {
		$tmp_it_stock_qty = get_option_stock_qty2($citem['it_id'], $citem['io_id'], $citem['io_type'], $citem['ct_option']);
		if ($tmp_it_stock_qty <= 0) {
			sql_query("DELETE FROM " . $g5['g5_shop_cart_table'] . " WHERE ct_id = '" . $citem['ct_id'] . "' ");
			$delcount++;
		}
	}
	if ($delcount > 0)
		alert("상품이 삭제되었습니다.");
	else
		alert("품절상품이 없습니다.");
	goto_url(G5_SHOP_URL . '/cart.php');
} else if ($act == "seldelete") {
	// 선택삭제
	if (!count($_POST['ct_chk'])) {
		alert("삭제하실 상품을 하나이상 선택해 주십시오.");
	}

	$fldcnt = count($_POST['ct_id']);
	for ($i = 0; $i < $fldcnt; $i++) {
		$ct_chk = $_POST['ct_chk'][$i];
		if ($ct_chk) {
			$ct_id = $_POST['ct_id'][$i];
			$sql = " delete from " . $g5['g5_shop_cart_table'] . " where ct_id = '" . $ct_id . "' and od_id = '" . $tmp_cart_id . "' ";
			sql_query($sql);
		}
	}

	goto_url(G5_SHOP_URL . '/cart.php');
} else if ($act == "care") {
	// 케어
	// 바로구매에 있던 장바구니 자료를 지운다.
	if ($sw_direct)
		sql_query(" delete from " . $g5['g5_shop_cart_table'] . " where od_id = '" . $tmp_cart_id . "' and ct_select = 1 ", false);

	$ct_select = 1;
	$ct_select_time = G5_TIME_YMDHIS;
	$remote_addr = get_real_client_ip();

	$sql = " INSERT INTO lt_shop_cart ( od_id, od_type, mb_id, it_id, it_name, it_sc_type, it_sc_method, it_sc_price, it_sc_minimum, it_sc_qty, ct_status, ct_price, ct_point, ct_point_use, ct_stock_use, ct_option, ct_qty, ct_notax, io_id, io_type, io_price, ct_time, ct_ip, ct_send_cost, ct_direct, ct_select, ct_select_time, its_sap_code, its_order_no, its_no, io_sapcode_color_gz, io_order_no, io_color_name, io_hoching, io_sap_price, rf_serial, ct_rental_price, ct_item_rental_month, ct_free_laundry, ct_free_laundry_delivery_price, ct_laundry_use, ct_laundry_price, ct_laundry_delivery_price, ct_laundrykeep_use, ct_laundrykeep_lprice, ct_laundrykeep_kprice, ct_laundrykeep_delivery_price, ct_repair_use, ct_repair_price, ct_repair_delivery_price, buy_ct_id, buy_od_sub_id)
			";
	$sql .= "
		select
			'" . $tmp_cart_id . "',
			'" . $od_type . "',
			a.mb_id,
			a.it_id,
			a.it_name,
			a.it_sc_type,
			a.it_sc_method,
			a.it_sc_price,
			a.it_sc_minimum,
			a.it_sc_qty,
			'쇼핑',
			a.ct_price,
			a.ct_point,
			a.ct_point_use,
			a.ct_stock_use,
			a.ct_option,
			a.ct_qty,
			a.ct_notax,
			a.io_id,
			a.io_type,
			a.io_price,
			'" . $ct_select_time . "',
			'" . $remote_addr . "',
			a.ct_send_cost,
			a.ct_direct,
			'" . $ct_select . "',
			'" . $ct_select_time . "',
			b.its_sap_code,
			b.its_order_no,
			b.its_no,
			b.io_sapcode_color_gz,
			b.io_order_no,
			b.io_color_name,
			b.io_hoching,
			b.io_sap_price,
			b.rf_serial,
			b.ct_rental_price,
			b.ct_item_rental_month,
			b.ct_free_laundry,
			b.ct_free_laundry_delivery_price,
			b.ct_laundry_use,
			b.ct_laundry_price,
			b.ct_laundry_delivery_price,
			b.ct_laundrykeep_use,
			b.ct_laundrykeep_lprice,
			b.ct_laundrykeep_kprice,
			b.ct_laundrykeep_delivery_price,
			b.ct_repair_use,
			b.ct_repair_price,
			b.ct_repair_delivery_price,
			'" . $ct_id . "',
			'" . $od_sub_id . "'
		from
			lt_shop_cart as a
			inner join lt_shop_order_item as b on a.ct_id = b.ct_id
		where
			a.ct_id = '" . $ct_id . "'
			and b.od_sub_id = '" . $od_sub_id . "'
	";
	sql_query($sql);
} else {
	// 장바구니에 담기
	$count = count($_POST['it_id']);
	if ($count < 1) {
		alert('장바구니에 담을 상품을 선택하여 주십시오.');
	}

	$ct_count = 0;
	for ($i = 0; $i < $count; $i++) {
		// 보관함의 상품을 담을 때 체크되지 않은 상품 건너뜀
		if ($act == 'multi' && !$_POST['chk_it_id'][$i]) {
			continue;
		}

		$it_id = $_POST['it_id'][$i];
		$opt_count = count($_POST['io_id'][$it_id]);

		if ($opt_count && $_POST['io_type'][$it_id][0] != 0) {
			alert('상품의 선택옵션을 선택해 주십시오.');
		}
		if (!$sw_direct) {
			for ($k = 0; $k < $opt_count; $k++) {
				if ($_POST['ct_qty'][$it_id][$k] < 1) {
					alert('수량은 1 이상 입력해 주십시오.');
				}
			}
		} else {
			if ($_POST['ct_qty'][$it_id][0] < 1) {
				alert('수량은 1 이상 입력해 주십시오.');
			}
		}


		// 상품정보
		$sql = " select * from {$g5['g5_shop_item_table']} where it_id = '" . $it_id . "' ";
		$it = sql_fetch($sql);
		if (!$it['it_id']) {
			alert('상품정보가 존재하지 않습니다.');
		}

		// 바로구매에 있던 장바구니 자료를 지운다.
		if ($i == 0 && $sw_direct) {
			sql_query(" delete from " . $g5['g5_shop_cart_table'] . " where od_id = '" . $tmp_cart_id . "' and ct_direct = 1 ", false);
		}

		// 최소, 최대 수량 체크
		if ($it['it_buy_min_qty'] || $it['it_buy_max_qty']) {
			$sum_qty = 0;
			for ($k = 0; $k < $opt_count; $k++) {
				if ($_POST['io_type'][$it_id][$k] == 0)
					$sum_qty += (int) $_POST['ct_qty'][$it_id][$k];
			}

			if ($it['it_buy_min_qty'] > 0 && $sum_qty < $it['it_buy_min_qty']) {
				alert($it['it_name'] . '의 선택옵션 개수 총합 ' . number_format($it['it_buy_min_qty']) . '개 이상 주문해 주십시오.');
			}

			if ($it['it_buy_max_qty'] > 0 && $sum_qty > $it['it_buy_max_qty']) {
				alert($it['it_name'] . '의 선택옵션 개수 총합 ' . number_format($it['it_buy_max_qty']) . '개 이하로 주문해 주십시오.');
			}

			// 기존에 장바구니에 담긴 상품이 있는 경우에 최대 구매수량 체크
			if ($it['it_buy_max_qty'] > 0) {
				$sql4 = " select sum(ct_qty) as ct_sum from " . $g5['g5_shop_cart_table'] . " where od_id = '" . $tmp_cart_id . "' and it_id = '" . $it_id . "' and io_type = '0' and ct_status = '쇼핑' ";
				$row4 = sql_fetch($sql4);

				if (($sum_qty + $row4['ct_sum']) > $it['it_buy_max_qty']) {
					alert($it['it_name'] . '의 선택옵션 개수 총합 ' . number_format($it['it_buy_max_qty']) . '개 이하로 주문해 주십시오.', './cart.php');
				}
			}
		}

		//--------------------------------------------------------
		//  재고 검사, 바로구매일 때만 체크
		//--------------------------------------------------------
		// 이미 주문폼에 있는 같은 상품의 수량합계를 구한다.
		if ($sw_direct) {
			// $opt_count = 0;
			for ($k = 0; $k < $opt_count; $k++) {
				$io_id = preg_replace(G5_OPTION_ID_FILTER, '', $_POST['io_id'][$it_id][$k]);
				$io_type = preg_replace('#[^01]#', '', $_POST['io_type'][$it_id][$k]);
				$io_value = $_POST['io_value'][$it_id][$k];

				$sql = " select SUM(ct_qty) as cnt from " . $g5['g5_shop_cart_table'] . "
						  where od_id <> '" . $tmp_cart_id . "'
							and it_id = '" . $it_id . "'
							and io_id = '" . $io_id . "'
							and io_type = '" . $io_type . "'
							and ct_stock_use = 0
							and ct_status = '쇼핑'
							and ct_select = '1' ";
				$row = sql_fetch($sql);
				$sum_qty = $row['cnt'];

				// 재고 구함
				$ct_qty = (int) $_POST['ct_qty'][$it_id][$k];
				if (!$io_id) {
					$it_stock_qty = get_it_stock_qty($it_id);
				} else {
					$it_stock_qty = get_option_stock_qty($it_id, $io_id, $io_type);
				}

				if ($ct_qty + $sum_qty > $it_stock_qty) {
					alert($io_value . " 의 재고수량이 부족합니다.\\n\\n현재 재고수량 : " . number_format($it_stock_qty - $sum_qty) . " 개");
					return;
				}
			}
		}
		//--------------------------------------------------------

		// 옵션수정일 때 기존 장바구니 자료를 먼저 삭제
		if ($act == 'optionmod')
			sql_query(" delete from " . $g5['g5_shop_cart_table'] . " where od_id = '" . $tmp_cart_id . "' and it_id = '" . $it_id . "' ");

		// 장바구니에 Insert
		// 바로구매일 경우 장바구니가 체크된것으로 강제 설정
		if ($sw_direct) {
			$ct_select = 1;
			$ct_select_time = G5_TIME_YMDHIS;
		} else {
			$ct_select = 0;
			$ct_select_time = '0000-00-00 00:00:00';
		}

		// 장바구니에 Insert
		$comma = '';
		$sql = " INSERT INTO " . $g5['g5_shop_cart_table'] . " ( od_id, od_type, mb_id, it_id, it_name, it_sc_type, it_sc_method, it_sc_price, it_sc_minimum, it_sc_qty, ct_status, ct_price, ct_point, ct_point_use, ct_stock_use, ct_option, ct_qty, ct_notax, io_id, io_type, io_price, ct_time, ct_ip, ct_send_cost, ct_direct, ct_select, ct_select_time, its_no, ct_rental_price, ct_item_rental_month, company_code, io_sapcode_color_gz ) VALUES ";
		// if ($sw_direct) $opt_count = 1;
		for ($k = 0; $k < $opt_count; $k++) {
			$io_id = preg_replace(G5_OPTION_ID_FILTER, '', $_POST['io_id'][$it_id][$k]);
			$io_type = preg_replace('#[^01]#', '', $_POST['io_type'][$it_id][$k]);
			$io_value = $_POST['io_value'][$it_id][$k];
			$io_supply = $_POST['io_supply'][$it_id][$k];
			$ct_qty = (int) $_POST['ct_qty'][$it_id][$k];
			$its_no = $_POST['its_no'][$it_id][$k];
			// 선택옵션정보가 존재하는데 선택된 옵션이 없으면 건너뜀
			$iosql = " select * from lt_shop_item_option where it_id = '" . $it_id . "' and its_no = '" . $its_no . "' AND io_id = '" . $io_id . "' and io_type = '0' ";
			$iorow = sql_fetch($iosql);
			$io_sapcode_color_gz = $iorow['io_sapcode_color_gz'];

			if ($iorow && $io_id == '') {
				continue;
			}

			// 구매할 수 없는 옵션은 건너뜀
			if ($io_id && !$iorow['io_use']) {
				continue;
			}

			//선택된 옵션의 추가금액
			$io_price = $iorow['io_price'];

			//추가옵션의 추가금액 계산
			if ($io_supply && $io_supply != '') {
				$io_supplys = explode(",", $io_supply);
				for ($s = 0; $s < count($io_supplys); $s++) {
					$io_supplys_id = $io_supplys[$s];
					if (trim($io_supplys_id)) {
						$supply_sql = " select * from lt_shop_item_option where it_id = '" . $it_id . "' and its_no = '" . $its_no . "' AND io_id = '" . $io_supplys_id . "' and io_type = '1' ";
						$supply_result = sql_fetch($supply_sql);
						$io_price += $supply_result['io_price'];
					}
				}
			}

			// 구매가격이 음수인지 체크
			if ($io_type) {
				if ((int) $io_price < 0) {
					alert('구매금액이 음수인 상품은 구매할 수 없습니다.');
					return;
				}
			} else {
				if ((int) $it['it_price'] + (int) $io_price < 0) {
					alert('구매금액이 음수인 상품은 구매할 수 없습니다.');
					return;
				}
			}

			// 동일옵션의 상품이 있으면 수량 더함
			$sql2 = "select ct_id, io_type, ct_qty from " . $g5['g5_shop_cart_table'] . " where od_id = '" . $tmp_cart_id . "' and it_id = '" . $it_id . "' and io_id = '" . $io_id . "' and ct_option = '" . $io_value . "' and ct_status = '쇼핑' ";
			$row2 = sql_fetch($sql2);
			if ($row2['ct_id']) {
				// 재고체크
				$tmp_ct_qty = $row2['ct_qty'];
				if (!$io_id) {
					$tmp_it_stock_qty = get_it_stock_qty($it_id);
				} else {
					$tmp_it_stock_qty = get_option_stock_qty2($it_id, $io_id, $row2['io_type'], $io_value);
				}

				if ($tmp_ct_qty + $ct_qty > $tmp_it_stock_qty) {
					alert($io_value . " 의 재고수량이 부족합니다.\\n\\n현재 재고수량 : " . number_format($tmp_it_stock_qty) . " 개");
					return;
				}

				$sql3 = " update " . $g5['g5_shop_cart_table'] . " set ct_qty = ct_qty + '" . $ct_qty . "' where ct_id = '" . $row2['ct_id'] . "' ";
				sql_query($sql3);
				continue;
			}

			// 포인트
			$point = 0;
			if ($config['cf_use_point']) {
				if ($io_type == 0) {
					$point = get_item_point($it, $io_id);
				} else {
					$point = $it['it_supply_point'];
				}

				if ($point < 0)
					$point = 0;
			}

			// 배송비결제
			if ($it['it_sc_type'] == 1) {
				$ct_send_cost = 2; // 무료
			} else if ($it['it_sc_type'] > 1 && $it['it_sc_method'] == 1) {
				$ct_send_cost = 1; // 착불
			}

			$io_value = sql_real_escape_string(strip_tags($io_value));
			$remote_addr = get_real_client_ip();

			$od_type = "";
			if ($it['it_item_type'] == '0') $od_type = "O";
			else if ($it['it_item_type'] == '1') $od_type = "R";


			$subsql = " select its_sap_code, its_order_no, its_item, its_rental_price, its_price, its_option_subject, its_supply_subject, its_final_price, its_final_rental_price, its_discount_type, its_discount from lt_shop_item_sub where its_no = '" . $its_no . "' ";
			$subrow = sql_fetch($subsql);

			$sql .= $comma . "( '" . $tmp_cart_id . "', '" . $od_type . "', '" . $member['mb_id'] . "', '" . $it['it_id'] . "', '" . addslashes($it['it_name']) . "', '" . $it['it_sc_type'] . "', '" . $it['it_sc_method'] . "'
                                , '" . $it['it_sc_price'] . "', '" . $it['it_sc_minimum'] . "', '" . $it['it_sc_qty'] . "', '쇼핑', '" . $subrow['its_final_price'] . "', '" . $point . "', '0', '0'
                                , '" . $io_value . "', '" . $ct_qty . "', '" . $it['it_notax'] . "', '" . $io_id . "', '" . $io_type . "', '" . $io_price . "'
                                , '" . G5_TIME_YMDHIS . "', '" . $remote_addr . "', '" . $ct_send_cost . "', '" . $sw_direct . "', '" . $ct_select . "', '" . $ct_select_time . "'
                                , '" . $its_no . "', '" . $subrow['its_final_rental_price'] . "', '" . $it_item_rental_month . "', '" . $it['ca_id3'] . "', '" . $io_sapcode_color_gz . "'
							)";
			$comma = ' , ';
			$ct_count++;
		}

		if ($ct_count > 0)
			sql_query($sql);
	}
}

// start NEXDI 0329
// 전환페이지 설정
// 네이버 추적 스크립트
$nexdiCart = '
<script type="text/javascript" src="//wcs.naver.net/wcslog.js"></script>
<script type="text/javascript">
var _nasa={};
if(window.wcs) _nasa["cnv"] = wcs.cnv("3","1");
</script>
';
// end NEXDI 0329
// start NEXDI 0720
$nexdiCart0720 = "
<script>
  gtag('event', 'conversion', {'send_to': 'AW-336156343/psNkCL-3lNcCELetpaAB'});
</script>
";
// end NEXDI 0720
// start Face 0720
$faceCart0720 = "
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '1185455058605170');
  fbq('track', 'PageView');

	fbq('track', 'AddToCart');

</script>
<noscript><img height='1' width='1' style='display:none'
  src='https://www.facebook.com/tr?id=772782013392105&ev=PageView&noscript=1'
/></noscript>
";
// end Face 0720
$wcslog = '
<script type="text/javascript" src="https://wcs.naver.net/wcslog.js"></script> 
<script type="text/javascript"> 
	var _nasa={};
	_nasa["cnv"] = wcs.cnv("3","10"); // 전환유형, 전환가치 설정해야함. 설치매뉴얼 참고
	if (!wcs_add) var wcs_add={};
	wcs_add["wa"] = "s_3a59b688a58f";
	if (!_nasa) var _nasa={};
	wcs.inflow();
	wcs_do(_nasa);
</script>
';
// Facebook Pixel Code 1110
$faceCart1110 = "<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '427587602051149');
fbq('track', 'PageView');

  fbq('track', 'AddToCart');

</script>
<noscript><img height='1' width='1' style='display:none'
src='https://www.facebook.com/tr?id=772782013392105&ev=PageView&noscript=1'
/></noscript>
";
// End Facebook Pixel Code 1110

// 바로 구매일 경우
if ($sw_direct) {
	goto_url(G5_SHOP_URL . "/orderform.php?sw_direct=" . $sw_direct);
} else {
	echo '<!-- popup -->
				<section class="popup_container layer" id="popup_cart">
					<div class="inner_layer" style="margin-top: -300px;">
						<div class="content ">
							<div class="guide_box ico ico_cart">
								<p>선택하신 제품이<br>장바구니에 담겼습니다.</p>
							</div>

							<div class="btn_group two">
								<button type="button" class="btn big border" onclick="$(\'#popup\').empty();"><span>쇼핑 계속</span></button>
								<button type="button" class="btn big green" onclick="location.href=\'' . G5_SHOP_URL . '/cart.php' . '\'"><span>장바구니 확인</span></button>
							</div>
						</div>
						<a href="#" class="btn_closed" onclick="$(\'#popup\').empty();"><span class="blind">닫기</span></a>
					</div>
				</section>
				<!-- //popup -->';
	return;

	goto_url(G5_SHOP_URL . '/cart.php');
}
?>