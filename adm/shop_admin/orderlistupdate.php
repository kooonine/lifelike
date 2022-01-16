<?php
$sub_menu = '400400';
include_once('./_common.php');
include_once('./admin.shop.lib.php');
include_once(G5_LIB_PATH . '/mailer.lib.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

check_admin_token();

define("_ORDERMAIL_", true);

//print_r2($_POST); exit;

$sms_count = 0;
$sms_messages = array();

// $invoice      = $_POST['od_invoice'];
// $invoice_time = $_POST['od_invoice_time'];
// $delivery_company = $_POST['od_delivery_company'];

for ($i = 0; $i < count($_POST['chk']); $i++) {

	// 실제 번호를 넘김
	$k     = $_POST['chk'][$i];
	$k1     = $_POST['chkInvoice'][$i];
	$od_id = $_POST['od_id'][$k];
	// koo 추후에 변경 할수있음 !!!!
	$ct_id = $_POST['ct_id'][$k][$k1];
	$invoice = $_POST['od_invoice'][$k][$k1];
	$invoice_time = $_POST['od_invoice_time'][$k][$k1];
	$delivery_company = $_POST['od_delivery_company'][$k][$k1];

	include(G5_SHOP_PATH . '/ordermail1.inc.php');

	// 쿼리 좀만 수정해보자 ㅋㅋㅋ
	$od = sql_fetch(" select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ");
	if (!$od) continue;
	$ctT = sql_fetch(" select * from {$g5['g5_shop_cart_table']} where ct_id = '$ct_id' ");

	$arr_change_data = array();
	$arr_change_data['고객명'] = $od['od_name'];
	$arr_change_data['이름'] = $od['od_name'];
	$arr_change_data['보낸분'] = $od['od_name'];
	$arr_change_data['받는분'] = $od['od_b_name'];;
	$arr_change_data['주문번호'] = $od_id;
	$arr_change_data['주문금액'] = number_format($ttotal_price + $od_send_cost + $od_send_cost2);
	$arr_change_data['결제금액'] = number_format($od_receipt_price);
	$arr_change_data['회원아이디'] = $member['mb_id'];
	$arr_change_data['회사명'] = $default['de_admin_company_name'];
	$arr_change_data["아이디"] = $member['mb_id'];
	$arr_change_data["고객명(아이디)"] = $member['mb_name'] . "(" . $member['mb_id'] . ")";
	$arr_change_data["od_list"] = $list;
	$arr_change_data['od_type'] = $od['od_type'];
	$arr_change_data['od_id'] = $od_id;

	//change_order_status($od['od_status'], $_POST['od_status'], $od);
	//echo $od_id . "<br>";
	$current_status = $ctT['ct_status'];
	if (!$current_status) {
		$current_status = $od['od_status'];
	}
	$change_status  = $_POST['od_status'];
	$od_type = $od['od_type'];

	// ------------------------------------------0-0------------------------
	// $ct = sql_fetch(" select * from {$g5['g5_shop_cart_table']} where od_id = '$od_id' AND ct_id ='$ct_id' ");
	// if (!$ct) continue;
	// $ct_current_status = $cd['ct_status'];

	// ---00-0-0-0-  dk whwrkxek ssi bal

	switch ($current_status) {
		case '주문':
			if ($change_status != '결제완료') continue;
			if ($od['od_settle_case'] != '무통장') continue;
			change_status($od_id, '주문', '결제완료');
			order_update_receipt($od_id);

			// SMS
			if ($config['cf_sms_use'] == 'icode' && $_POST['send_sms'] && $default['de_sms_use4']) {
				$sms_contents = conv_sms_contents($od_id, $default['de_sms_cont4']);
				if ($sms_contents) {
					$receive_number = preg_replace("/[^0-9]/", "", $od['od_hp']);	// 수신자번호
					$send_number = preg_replace("/[^0-9]/", "", $default['de_admin_company_tel']); // 발신자번호

					if ($receive_number)
						$sms_messages[] = array('recv' => $receive_number, 'send' => $send_number, 'cont' => $sms_contents);
				}
			}
			// 메일
			if ($config['cf_email_use'] && $_POST['od_send_mail'])
				include './ordermail.inc.php';

			break;

		case '결제완료':
			if ($change_status != '상품준비중') continue;

			$sql = " update {$g5['g5_shop_order_table']} set od_status = '{$change_status}' where od_id = '{$od_id}' and od_status = '{$current_status}' ";
			sql_query($sql, true);

			$sql = " update {$g5['g5_shop_cart_table']} set ct_status = '{$change_status}' where od_id = '{$od_id}' AND ct_id = '{$ct_id}' and ct_status = '{$current_status}' ";
			sql_query($sql, true);


			if ($od['company_code'] == "") {
				//라이프라이크 제품 => 삼진으로 요청
				SM_FINISH_SALE_DATA($od_id, $change_status);
			}

			break;

		case '계약등록':
			if ($change_status != '상품준비중') continue;

			// 리스제품 출고프로세스 추가 이전까지 삼진 동기화 중지 - 190904 balance@panpacific.co.kr
			change_status($od_id, '결제완료', '상품준비중');

			$sql = " update {$g5['g5_shop_order_table']} set od_status = '{$change_status}' where od_id = '{$od_id}' and od_status = '{$current_status}' ";
			sql_query($sql, true);

			$sql = " update {$g5['g5_shop_cart_table']} set ct_status = '{$change_status}' where od_id = '{$od_id}' and ct_status = '{$current_status}' ";
			sql_query($sql, true);


			//삼진 연동 - 정보전달
			$result_sm = SM_FINISH_RENTAL_DATA($od_id, $change_status);

			//echo print_r2($result_sm);
			break;
		case '상품준비중':
			if ($change_status == '결제완료') {
				$sql = " update {$g5['g5_shop_cart_table']} set ct_status = '{$change_status}' where od_id = '{$od_id}' AND ct_id = '{$ct_id}'";
				sql_query($sql, true);
				break;
			} else {
				if ($change_status != '배송중') continue;
				$delivery['invoice'] = $invoice;
				$delivery['invoice_time'] = $invoice_time;
				$delivery['delivery_company'] = $delivery_company;
				if ($od_type == "O") { 
					order_update_delivery_single($od_id, $od['mb_id'], $change_status, $delivery, $ct_id);
					change_status_single($od_id, '상품준비중', '배송중', $ct_id);
				} else {
					order_update_delivery($od_id, $od['mb_id'], $change_status, $delivery);
					change_status($od_id, '상품준비중', '배송중');	
				}
				if ($od['od_type'] == "O") {
					msg_autosend('주문', '배송 시작', $od['mb_id'], $arr_change_data);
				} else if ($od['od_type'] == "R") {
					msg_autosend('리스', '배송 시작', $od['mb_id'], $arr_change_data);
				}

				// 에스크로 배송
				if ($_POST['send_escrow'] && $od['od_tno'] && $od['od_escrow']) {
					$escrow_tno  = $od['od_tno'];
					$escrow_numb = $invoice;
					$escrow_corp = $delivery_company;

					include(G5_SHOP_PATH . '/' . $od['od_pg'] . '/escrow.register.php');
				}
				break;
			}

		case '배송중':
			if ($change_status == '배송중') {
				if ($od_type == "O") { 
					$delivery['invoice'] = str_replace('-','',$invoice);
					$delivery['invoice_time'] = $invoice_time;
					$delivery['delivery_company'] = $delivery_company;
					order_update_delivery_single($od_id, $od['mb_id'], $change_status, $delivery, $ct_id);
					break;
				}
				break; 
			}
			if ($change_status != '배송완료') continue;

			if ($od_type == "O" || $od_type == "R") {
				change_status($od_id, '배송중', '배송완료',$ct_id);

				// 완료인 경우에만 제품구입 합계수량을 제품테이블에 저장한다.
				$sql2 = " select it_id from {$g5['g5_shop_cart_table']} where od_id = '$od_id' and ct_status = '배송완료' group by it_id ";
				$result2 = sql_query($sql2);
				for ($k = 0; $row2 = sql_fetch_array($result2); $k++) {
					$sql3 = " select sum(ct_qty) as sum_qty from {$g5['g5_shop_cart_table']} where it_id = '{$row2['it_id']}' and ct_status = '배송완료' ";
					$row3 = sql_fetch($sql3);

					$sql4 = " update {$g5['g5_shop_item_table']} set it_sum_qty = '{$row3['sum_qty']}' where it_id = '{$row2['it_id']}' ";
					sql_query($sql4);
				}
			}
			if ($od_type == "L" || $od_type == "K") {

				if ($od_type == "L") change_status($od_id, '배송중', '세탁완료');
				if ($od_type == "K") change_status($od_id, '배송중', '보관완료');

				$ct = sql_fetch(" select buy_ct_id,buy_od_sub_id,ct_free_laundry_use from lt_shop_cart where od_id = '{$od_id}' ");

				//구매제품 상태 초기화, 무료세탁일 경우 사용 처리
				sql_query("update lt_shop_order_item set ct_status = '', ct_free_laundry_use=ct_free_laundry_use+'{$ct['ct_free_laundry_use']}' where ct_id = '{$ct['buy_ct_id']}' and od_sub_id  = '{$ct['buy_od_sub_id']}'");

				//2. 세탁/보관 처리후 고객에게 배송완료 (SM_ADD_CLEANING_FINISH)
				SM_ADD_CLEANING_FINISH($od_id);
			}
			if ($od_type == "S") {
				//4. 수선 완료완료후 고객에게 배송됨 (SM_ADD_REPAIR_FINISH)
				SM_ADD_REPAIR_FINISH($od_id);
			}
			/*
			$sql2 = " select it_id, sum(ct_qty) as sum_qty from {$g5['g5_shop_cart_table']} where od_id = '$od_id' and ct_status = '완료' group by it_id ";
			$result2 = sql_query($sql2);
			for ($k=0; $row2=sql_fetch_array($result2); $k++) {
				$sql3 = " update {$g5['g5_shop_item_table']} set it_sum_qty = it_sum_qty + '{$row2['sum_qty']}' where it_id = '{$row2['it_id']}' ";
				sql_query($sql3);
			}
			*/

			if ($od['od_type'] == "O") {
				$arr_change_data['button'] = array(
					"type" => "웹링크",
					"txt" => "주문내역 확인하기",
					"link" => "https://lifelike.co.kr/member/order.php?od_id=" . $arr_change_data['od_id']
				);
				msg_autosend('주문', '배송 완료', $od['mb_id'], $arr_change_data);
			} else if ($od['od_type'] == "R") {
				msg_autosend('리스', '배송 완료', $od['mb_id'], $arr_change_data);
			} else if ($od['od_type'] == "L") {
				msg_autosend('세탁', '세탁 배송 완료', $od['mb_id'], $arr_change_data);
			} else if ($od['od_type'] == "K") {
				msg_autosend('세탁보관', '배송 완료', $od['mb_id'], $arr_change_data);
			} else if ($od['od_type'] == "S") {
				msg_autosend('수선', '수선 배송 완료', $od['mb_id'], $arr_change_data);
			}

			break;
		case '세탁신청':
		case '보관신청':
			if ($change_status != '수거박스배송') continue;

			change_status($od_id, $current_status, $change_status);

			//세탁,보관의 수거박스 배송 -> 삼진연동
			SM_ADD_CLEANING_REQUEST($od_id);
			//택배 삼진->고객 박스배송 의뢰 (택배의뢰 안함, 삼진 DB연동으로 운송장번호 업데이트로 변경)

			break;
		case '수선신청':
			if ($change_status != '수거중') continue;
			/*
			$sql = " update {$g5['g5_shop_order_table']} set od_pickup_delivery_company = '{$delivery_company}', od_pickup_invoice = '{$invoice}', od_pickup_invoice_time = '{$invoice_time}' where od_id = '$od_id' ";
			sql_query($sql);
			change_status($od_id, $current_status, $change_status);
			*/

			//택배 고객->리탠다드 택배수거 의뢰
			pickup_send($od_id, $current_status, $change_status);

			break;
		case '수거박스배송':
			//박스배송완료
			//수거중
			if ($change_status != '박스배송완료' && $change_status != '수거중') continue;

			if ($change_status == '수거중') {

				//택배 고객->펭귄 택배수거 의뢰
				pickup_send($od_id, $current_status, $change_status);
			} else {
				//$sql = " update {$g5['g5_shop_order_table']} set od_pickup_delivery_company = '{$delivery_company}', od_pickup_invoice = '{$invoice}', od_pickup_invoice_time = '{$invoice_time}' where od_id = '$od_id' ";
				//sql_query($sql);

				change_status($od_id, $current_status, $change_status);
			}
			break;
		case '박스배송완료':
			if ($change_status != '수거중') continue;

			//택배 고객->펭귄 택배수거 의뢰
			pickup_send($od_id, $current_status, $change_status);
			break;
		case '수거중':
			if ($change_status == '수거완료') change_status($od_id, '수거중', '수거완료');
			/*if ($change_status == '수거중') {
				$sql = " update {$g5['g5_shop_order_table']} set od_pickup_delivery_company = '{$delivery_company}', od_pickup_invoice = '{$invoice}', od_pickup_invoice_time = '{$invoice_time}' where od_id = '$od_id' ";
				sql_query($sql);
			}*/

			break;
		case '수거완료':
			if ($change_status != '세탁중') continue;
			change_status($od_id, '수거완료', '세탁중');
			break;
		case '세탁중':
			if ($change_status == '보관중') {

				change_status($od_id, '세탁중', '보관중');
				continue;
			}
			if ($change_status != '배송중') continue;

			$sql = " update {$g5['g5_shop_order_table']} set od_delivery_company = '{$delivery_company}', od_invoice = '{$invoice}', od_invoice_time = '{$invoice_time}' where od_id = '$od_id' ";
			sql_query($sql);

			change_status($od_id, '세탁중', '배송중');

			// SMS
			if ($config['cf_sms_use'] == 'icode' && $_POST['send_sms'] && $default['de_sms_use5']) {
				$sms_contents = conv_sms_contents($od_id, $default['de_sms_cont5']);
				if ($sms_contents) {
					$receive_number = preg_replace("/[^0-9]/", "", $od['od_hp']);	// 수신자번호
					$send_number = preg_replace("/[^0-9]/", "", $default['de_admin_company_tel']); // 발신자번호

					if ($receive_number)
						$sms_messages[] = array('recv' => $receive_number, 'send' => $send_number, 'cont' => $sms_contents);
				}
			}

			// 메일
			if ($config['cf_email_use'] && $_POST['od_send_mail'])
				include './ordermail.inc.php';

			// 에스크로 배송
			if ($_POST['send_escrow'] && $od['od_tno'] && $od['od_escrow']) {
				$escrow_tno  = $od['od_tno'];
				$escrow_numb = $invoice;
				$escrow_corp = $delivery_company;

				include(G5_SHOP_PATH . '/' . $od['od_pg'] . '/escrow.register.php');
			}
			break;
		case '보관중':
		case '보관완료':
			if ($change_status != '배송중') continue;

			$sql = " update {$g5['g5_shop_order_table']} set od_delivery_company = '{$delivery_company}', od_invoice = '{$invoice}', od_invoice_time = '{$invoice_time}' where od_id = '$od_id' ";
			sql_query($sql);

			change_status($od_id, $current_status, '배송중');
			break;
		case '수선중':
			if ($change_status != '배송중') continue;

			$sql = " update {$g5['g5_shop_order_table']} set od_delivery_company = '{$delivery_company}', od_invoice = '{$invoice}', od_invoice_time = '{$invoice_time}' where od_id = '$od_id' ";
			sql_query($sql);

			change_status($od_id, $current_status, '배송중');
			break;
		case '리스중':
			if ($change_status != '리스완료') continue;
			//change_status($od_id, '리스완료', '리스중');

			$sql = " update {$g5['g5_shop_order_table']} set od_status = '{$change_status}' where od_id = '{$od_id}' and od_status = '{$current_status}' ";
			sql_query($sql, true);

			$sql = " update {$g5['g5_shop_cart_table']} set ct_status = '{$change_status}' where od_id = '{$od_id}' and ct_status = '{$current_status}' ";
			sql_query($sql, true);

			break;
	} // switch end


	/*
	// 주문정보
	$info = get_order_info($od_id);
	if(!$info) continue;

	$sql = " update {$g5['g5_shop_order_table']}
				set od_misu         = '{$info['od_misu']}',
					od_tax_mny      = '{$info['od_tax_mny']}',
					od_vat_mny      = '{$info['od_vat_mny']}',
					od_free_mny     = '{$info['od_free_mny']}',
					od_send_cost    = '{$info['od_send_cost']}'
				where od_id = '$od_id' ";
	sql_query($sql, true);
*/
}

// SMS
$sms_count = count($sms_messages);
if ($sms_count > 0) {
	if ($config['cf_sms_type'] == 'LMS') {
		include_once(G5_LIB_PATH . '/icode.lms.lib.php');

		$port_setting = get_icode_port_type($config['cf_icode_id'], $config['cf_icode_pw']);

		// SMS 모듈 클래스 생성
		if ($port_setting !== false) {
			$SMS = new LMS;
			$SMS->SMS_con($config['cf_icode_server_ip'], $config['cf_icode_id'], $config['cf_icode_pw'], $port_setting);

			for ($s = 0; $s < $sms_count; $s++) {
				$strDest     = array();
				$strDest[]   = $sms_messages[$s]['recv'];
				$strCallBack = $sms_messages[$s]['send'];
				$strCaller   = iconv_euckr(trim($default['de_admin_company_name']));
				$strSubject  = '';
				$strURL      = '';
				$strData     = iconv_euckr($sms_messages[$s]['cont']);
				$strDate     = '';
				$nCount      = count($strDest);

				$res = $SMS->Add($strDest, $strCallBack, $strCaller, $strSubject, $strURL, $strData, $strDate, $nCount);

				$SMS->Send();
				$SMS->Init(); // 보관하고 있던 결과값을 지웁니다.
			}
		}
	} else {
		include_once(G5_LIB_PATH . '/icode.sms.lib.php');

		$SMS = new SMS; // SMS 연결
		$SMS->SMS_con($config['cf_icode_server_ip'], $config['cf_icode_id'], $config['cf_icode_pw'], $config['cf_icode_server_port']);

		for ($s = 0; $s < $sms_count; $s++) {
			$recv_number = $sms_messages[$s]['recv'];
			$send_number = $sms_messages[$s]['send'];
			$sms_content = iconv_euckr($sms_messages[$s]['cont']);

			$SMS->Add($recv_number, $send_number, $config['cf_icode_id'], $sms_content, "");
		}

		$SMS->Send();
		$SMS->Init(); // 보관하고 있던 결과값을 지웁니다.
	}
}

$qstr  = "sort1=$sort1&amp;sort2=$sort2&amp;sel_field=$sel_field&amp;search=$search";
$qstr .= "&amp;od_type=$od_type";
$qstr .= "&amp;od_status=$od_status";
$qstr .= "&amp;od_settle_case=$od_settle_case";
$qstr .= "&amp;od_misu=$od_misu";
$qstr .= "&amp;od_cancel_price=$od_cancel_price";
$qstr .= "&amp;od_receipt_price=$od_receipt_price";
$qstr .= "&amp;od_receipt_point=$od_receipt_point";
$qstr .= "&amp;od_receipt_coupon=$od_receipt_coupon";
//$qstr .= "&amp;page=$page";

//exit;
if ($is_admin == "brand") {
	goto_url("./orderlist.brand.php?$qstr");
} else {
	goto_url("./orderlist.php?$qstr");
}
