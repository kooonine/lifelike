<? #!/usr/local/php53/bin/php
$chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
$root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));

include_once($root_path . '/../../common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

$test = false;
$outputs = array();

//크론1 : 상품준비중 => 배송중, RFID 변경
$connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
$g5['connect_samjindb'] = $connect_db;

$outputs[] = date('Y-m-d H:i:s', time()) . " : 삼진 동기화 시작";

//1. 제품
$outputs[] = date('Y-m-d H:i:s', time()) . " : 일반판매";
$sql = " select od_type, od_id, mb_id from lt_shop_order where od_type = 'O' and od_status = '상품준비중' and ifnull(od_invoice,'') = '' and ifnull(company_code,'') = '' ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
	$disp_od_id = $row['od_type'] . '-' . substr($row['od_id'], 0, 8) . '-' . substr($row['od_id'], 8, 6);

	$wmsql = " SELECT	INV_NO, convert(varchar(19), CHOOLGO_DATE, 120) as CHOOLGO_DATE  ";
	$wmsql .= " FROM	S_MALL_SALE_MAIN_MASTER";
	$wmsql .= " where SM_SERIAL = N'{$disp_od_id}' ";

	$wmresult = mssql_sql_fetch($wmsql);
	if ($wmresult['INV_NO'] != '') {
		//sql_query("update lt_shop_order set od_boxsend_invoice = '{$wmresult['INV_NO']}' where od_id = '{$row['od_id']}'; ");

		$change_status = '배송중';
		$delivery['invoice'] = $wmresult['INV_NO'];
		$delivery['invoice_time'] = $wmresult['CHOOLGO_DATE'];
		$delivery['delivery_company'] = "롯데택배";

		$sql = " update {$g5['g5_shop_order_table']} set od_status = '{$change_status}', od_delivery_company = '{$delivery['delivery_company']}', od_invoice = '{$delivery['invoice']}', od_invoice_time = '{$delivery['invoice_time']}' where od_id = '{$row['od_id']}' and od_status = '상품준비중' ";
		if ($test) {
			$outputs[] = date('Y-m-d H:i:s', time()) . " : " . $sql;
		} else {
			sql_query($sql, true);
		}

		$sql = " update {$g5['g5_shop_cart_table']} set ct_status = '{$change_status}' where od_id = '{$row['od_id']}' and ct_status = '상품준비중' ";
		if ($test) {
			$outputs[] = date('Y-m-d H:i:s', time()) . " : " . $sql;
		} else {
			sql_query($sql, true);
		}
		$outputs[] = sprintf("%s : %s / %s / %s", "출고(OD_ID/INV_NO/CHOOLGO_DATE)", $disp_od_id, $wmresult['INV_NO'], $wmresult['CHOOLGO_DATE']);

		//RFID 처리
		$wmdsql = " SELECT	RF_SERIAL, SM_SERIAL_1, SM_SERIAL_2 ";
		$wmdsql .= " FROM	S_MALL_SALE_MAIN_DETAIL";
		$wmdsql .= " where   SM_SERIAL_1 = N'{$disp_od_id}' AND RF_SERIAL is not null ";

		$wmdresult = mssql_sql_query($wmdsql);
		for ($i = 0; $md = mssql_sql_fetch_array($wmdresult); $i++) {
			$sql = " update lt_shop_order_item set RF_SERIAL = '{$md['RF_SERIAL']}' where od_id = '{$row['od_id']}' and od_sub_id = '{$md['SM_SERIAL_2']}' ";
			if ($test) {
				$outputs[] = date('Y-m-d H:i:s', time()) . " : " . $sql;
			} else {
				sql_query($sql, true);
			}
			$outputs[] = sprintf("%s : %s / %s / %s", "RFID(OD_ID/상품ID/RFID)", $disp_od_id, $wmresult['SM_SERIAL_2'], $wmresult['RF_SERIAL']);
		}

		include(G5_SHOP_PATH . '/ordermail1.inc.php');
		$arr_change_data = array();
		$arr_change_data['고객명'] = $od['od_name'];
		$arr_change_data['이름'] = $od['od_name'];
		$arr_change_data['보낸분'] = $od['od_name'];
		$arr_change_data['받는분'] = $od['od_b_name'];;
		$arr_change_data['주문번호'] = $od_id;
		$arr_change_data['주문금액'] = number_format($ttotal_price + $od_send_cost + $od_send_cost2);
		$arr_change_data['결제금액'] = number_format($od_receipt_price);
		$arr_change_data['회원아이디'] = $od['mb_id'];
		$arr_change_data['회사명'] = $default['de_admin_company_name'];
		$arr_change_data["아이디"] = $od['mb_id'];
		$arr_change_data["고객명(아이디)"] = $od['od_name'] . "(" . $od['mb_id'] . ")";
		$arr_change_data["od_list"] = $list;
		$arr_change_data['od_type'] = $od['od_type'];
		$arr_change_data['od_id'] = $od_id;

		msg_autosend('주문', '배송 시작', $od['mb_id'], $arr_change_data);
	} else {
		$outputs[] = sprintf("%s : %s", "미등록(OD_ID)", $disp_od_id);
	}
}

//2. 리스
$outputs[] = date('Y-m-d H:i:s', time()) . " : 리스판매";
$sql = " select * from lt_shop_order where od_type = 'R' and od_status = '상품준비중' and ifnull(od_invoice,'') = ''  ";
// $sql = " select * from lt_shop_order where od_type = 'R' and od_id='20190930000012'  ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
	$disp_od_id = $row['od_type'] . '-' . substr($row['od_id'], 0, 8) . '-' . substr($row['od_id'], 8, 6);

	$wmsql = " SELECT	INV_NO, convert(varchar(19), CHOOLGO_DATE, 120) as CHOOLGO_DATE  ";
	$wmsql .= " FROM	S_MALL_SALE_MAIN_MASTER";
	$wmsql .= " where SM_SERIAL = N'{$disp_od_id}' ";

	$wmresult = mssql_sql_fetch($wmsql);
	if ($wmresult['INV_NO'] != '') {
		$change_status = '배송중';
		//sql_query("update lt_shop_order set od_boxsend_invoice = '{$wmresult['INV_NO']}' where od_id = '{$row['od_id']}'; ");

		$delivery['invoice'] = $wmresult['INV_NO'];
		$delivery['invoice_time'] = $wmresult['CHOOLGO_DATE'];
		$delivery['delivery_company'] = "롯데택배";

		$sql = " update {$g5['g5_shop_order_table']} set od_status = '{$change_status}', od_delivery_company = '{$delivery['delivery_company']}', od_invoice = '{$delivery['invoice']}', od_invoice_time = '{$delivery['invoice_time']}' where od_id = '{$row['od_id']}' and od_status = '상품준비중' ";
		if ($test) {
			$outputs[] = date('Y-m-d H:i:s', time()) . " : " . $sql;
		} else {
			sql_query($sql, true);
		}

		$sql = " update {$g5['g5_shop_cart_table']} set ct_status = '{$change_status}' where od_id = '{$row['od_id']}' and ct_status = '상품준비중' ";
		if ($test) {
			$outputs[] = date('Y-m-d H:i:s', time()) . " : " . $sql;
		} else {
			sql_query($sql, true);
		}
		$outputs[] = sprintf("%s : %s / %s / %s", "출고(OD_ID/INV_NO/CHOOLGO_DATE)", $disp_od_id, $wmresult['INV_NO'], $wmresult['CHOOLGO_DATE']);

		//RFID 처리
		$wmdsql = " SELECT	RF_SERIAL, SM_SERIAL_1, SM_SERIAL_2 ";
		$wmdsql .= " FROM	S_MALL_SALE_MAIN_DETAIL";
		$wmdsql .= " where   SM_SERIAL_1 = N'{$disp_od_id}' AND RF_SERIAL is not null ";

		$wmdresult = mssql_sql_query($wmdsql);
		for ($i = 0; $md = mssql_sql_fetch_array($wmdresult); $i++) {
			$sql = " update lt_shop_order_item set RF_SERIAL = '{$md['RF_SERIAL']}' where od_id = '{$row['od_id']}' and od_sub_id = '{$md['SM_SERIAL_2']}' ";
			if ($test) {
				$outputs[] = date('Y-m-d H:i:s', time()) . " : " . $sql;
			} else {
				sql_query($sql, true);
			}
			$outputs[] = sprintf("%s : %s / %s / %s", "RFID(OD_ID/상품ID/RFID)", $disp_od_id, $wmresult['SM_SERIAL_2'], $wmresult['RF_SERIAL']);
		}

		$od = $row;
		$od_id = $row['od_id'];
		include(G5_SHOP_PATH . '/ordermail1.inc.php');

		$arr_change_data = array();
		$arr_change_data['고객명'] = $od['od_name'];
		$arr_change_data['이름'] = $od['od_name'];
		$arr_change_data['보낸분'] = $od['od_name'];
		$arr_change_data['받는분'] = $od['od_b_name'];;
		$arr_change_data['주문번호'] = $od['od_id'];
		$arr_change_data['주문금액'] = number_format($ttotal_price + $od_send_cost + $od_send_cost2);
		$arr_change_data['결제금액'] = number_format($od_receipt_price);
		$arr_change_data['회원아이디'] = $od['mb_id'];
		$arr_change_data['회사명'] = $default['de_admin_company_name'];
		$arr_change_data["아이디"] = $od['mb_id'];
		$arr_change_data["고객명(아이디)"] = $od['od_name'] . "(" . $od['mb_id'] . ")";
		$arr_change_data["od_list"] = $list;
		$arr_change_data['od_type'] = $od['od_type'];
		$arr_change_data['od_id'] = $od['od_id'];
		msg_autosend('리스', '배송 시작', $od['mb_id'], $arr_change_data);
	} else {
		$outputs[] = sprintf("%s : %s", "미등록(OD_ID)", $disp_od_id);
	}
}


//RFID가 없는 주문건 처리
$outputs[] = date('Y-m-d H:i:s', time()) . " : RFID 갱신";
$sql = " select a.od_type, a.od_id, a.od_sub_id, b.od_status
			from  lt_shop_order_item a
				  inner join lt_shop_order b
					on a.od_id = b.od_id
			where a.RF_SERIAL = ''
			and   a.od_type = 'R'
			and   b.od_status in ('상품준비중','배송중','배송완료','구매완료','리스중') ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
	$disp_od_id = $row['od_type'] . '-' . substr($row['od_id'], 0, 8) . '-' . substr($row['od_id'], 8, 6);

	//RFID 처리
	$wmdsql = " SELECT	RF_SERIAL, SM_SERIAL_1, SM_SERIAL_2 ";
	$wmdsql .= " FROM	S_MALL_SALE_MAIN_DETAIL";
	$wmdsql .= " where  SM_SERIAL_1 = N'{$disp_od_id}' AND SM_SERIAL_2 = N'{$row['od_sub_id']}' AND RF_SERIAL is not null ";

	$md = mssql_sql_fetch($wmdsql);
	if ($md) {
		$sql = " update lt_shop_order_item set RF_SERIAL = '{$md['RF_SERIAL']}' where od_id = '{$row['od_id']}' and od_sub_id = '{$md['SM_SERIAL_2']}' ";
		if ($test) {
			$outputs[] = date('Y-m-d H:i:s', time()) . " : " . $sql;
		} else {
			sql_query($sql, true);
		}
		$outputs[] = sprintf("%s : %s / %s / %s", "RFID 갱신(OD_ID/상품ID/RFID)", $disp_od_id, $wmresult['od_sub_id'], $wmresult['RF_SERIAL']);
	} else {
		// echo "<br/>주문번호 : " . $disp_od_id . " / 제품 주문 일련번호 : " . $row['od_sub_id'] . " / RFID 조회 안됨<br>";
	}
}

//5.전송안된 주문 재전송
//제품
$outputs[] = date('Y-m-d H:i:s', time()) . " : 재전송(일반)";
$sql = " select od_type, od_id from lt_shop_order where od_type = 'O' and   od_status in ('상품준비중') and ifnull(od_invoice,'') = '' and ifnull(company_code,'') = '' and ifnull(od_samjin_chk,'') not in ('0','1','44','61') ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
	$disp_od_id = $row['od_type'] . '-' . substr($row['od_id'], 0, 8) . '-' . substr($row['od_id'], 8, 6);

	$r = SM_FINISH_SALE_DATA($row['od_id'], "상품준비중");
	$outputs[] = sprintf("%s : %s / %s / %s", "재전송(OD_ID/RSLT_CODE/RSLT_ITEM)", $disp_od_id, $r['RSLT_CODE'], $r['RSLT_ITEM']);
}
//리스
$outputs[] = date('Y-m-d H:i:s', time()) . " : 재전송(리스)";
$sql = " select od_type, od_id from lt_shop_order where od_type = 'R' and   od_status in ('상품준비중') and ifnull(od_invoice,'') = '' and ifnull(company_code,'') = '' and ifnull(od_samjin_chk,'') not in ('0','1','43','44','61') ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
	$disp_od_id = $row['od_type'] . '-' . substr($row['od_id'], 0, 8) . '-' . substr($row['od_id'], 8, 6);

	$r = SM_FINISH_RENTAL_DATA($row['od_id'], "상품준비중");
	$outputs[] = sprintf("%s : %s / %s / %s", "재전송(OD_ID/RSLT_CODE/RSLT_ITEM)", $disp_od_id, $r['RSLT_CODE'], $r['RSLT_ITEM']);
}

//세탁/보관
$outputs[] = date('Y-m-d H:i:s', time()) . " : 재전송(세탁/보관)";
$sql = " select od_type, od_id from lt_shop_order where od_type in ('L','K') and   od_status in ('수거박스배송') and ifnull(od_boxsend_invoice,'') = '' and ifnull(od_samjin_chk,'') not in ('0','1','43','44','61') ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
	$disp_od_id = $row['od_type'] . '-' . substr($row['od_id'], 0, 8) . '-' . substr($row['od_id'], 8, 6);

	$r = SM_ADD_CLEANING_REQUEST($row['od_id']);
	$outputs[] = sprintf("%s : %s / %s / %s", "재전송(OD_ID/RSLT_CODE/RSLT_ITEM)", $disp_od_id, $r['RSLT_CODE'], $r['RSLT_ITEM']);
}
$sql = " select od_type, od_id from lt_shop_order where od_type in ('L','K') and od_status in ('배송완료','세탁완료','보관완료','서비스완료') and ifnull(od_boxsend_invoice,'') = '' and ifnull(od_samjin_chk,'') not in ('0','1','43','44','61') ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
	$disp_od_id = $row['od_type'] . '-' . substr($row['od_id'], 0, 8) . '-' . substr($row['od_id'], 8, 6);

	$r = SM_ADD_CLEANING_FINISH($row['od_id']);
	$outputs[] = sprintf("%s : %s / %s / %s", "재전송(OD_ID/RSLT_CODE/RSLT_ITEM)", $disp_od_id, $r['RSLT_CODE'], $r['RSLT_ITEM']);
}

//수선
$outputs[] = date('Y-m-d H:i:s', time()) . " : 재전송(수선)";
$sql = " select od_type, od_id from lt_shop_order where od_type = 'S' and ifnull(od_samjin_chk,'') in ('43') ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
	$disp_od_id = $row['od_type'] . '-' . substr($row['od_id'], 0, 8) . '-' . substr($row['od_id'], 8, 6);

	$r = SM_ADD_REPAIR_REQUEST($row['od_id']);
	$outputs[] = sprintf("%s : %s / %s / %s", "재전송(OD_ID/RSLT_CODE/RSLT_ITEM)", $disp_od_id, $r['RSLT_CODE'], $r['RSLT_ITEM']);
}

$sql = " select od_type, od_id from lt_shop_order where od_type = 'S' and od_status in ('수선중') and ifnull(od_boxsend_invoice,'') = '' and ifnull(od_samjin_chk,'') not in ('0','1','43','44','61') ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
	$disp_od_id = $row['od_type'] . '-' . substr($row['od_id'], 0, 8) . '-' . substr($row['od_id'], 8, 6);

	$r = SM_ADD_REPAIR_REQUEST($row['od_id']);
	$outputs[] = sprintf("%s : %s / %s / %s", "재전송(OD_ID/RSLT_CODE/RSLT_ITEM)", $disp_od_id, $r['RSLT_CODE'], $r['RSLT_ITEM']);
}

$sql = " select od_type, od_id from lt_shop_order where od_type = 'S' and od_status in ('배송완료','수선완료','서비스완료') and ifnull(od_boxsend_invoice,'') = '' and ifnull(od_samjin_chk,'') not in ('0','1','43','44','61','87') ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
	$disp_od_id = $row['od_type'] . '-' . substr($row['od_id'], 0, 8) . '-' . substr($row['od_id'], 8, 6);

	$r = SM_ADD_REPAIR_FINISH($row['od_id']);
	$outputs[] = sprintf("%s : %s / %s / %s", "재전송(OD_ID/RSLT_CODE/RSLT_ITEM)", $disp_od_id, $r['RSLT_CODE'], $r['RSLT_ITEM']);
}

$outputs[] = date('Y-m-d H:i:s', time()) . " : 동기화 종료";
print_raw($outputs);

/*
// 미사용 기능정리 - 200131 - balance@panpacific.co.kr

//3. 세탁/보관 박스배송
$sql = " select od_type, od_id from lt_shop_order where od_status = '수거박스배송' and ifnull(od_boxsend_invoice,'') = ''  ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
	$disp_od_id = $row['od_type'] . '-' . substr($row['od_id'], 0, 8) . '-' . substr($row['od_id'], 8, 6);

	$wmsql = " SELECT	INV_NO ";
	$wmsql .= " FROM	S_MALL_WASHING_MASTER";
	$wmsql .= " where SM_SERIAL = N'{$disp_od_id}' ";

	$wmresult = mssql_sql_fetch($wmsql);
	if ($wmresult['INV_NO'] != '') {
		$sql = "update lt_shop_order set od_boxsend_invoice = '{$wmresult['INV_NO']}' where od_id = '{$row['od_id']}'; ";
		if ($test) {
			echo $sql . "<br>";
		} else {
			sql_query($sql, true);
		}
		echo "<br/>세탁/보관 박스배송 주문번호 : " . $disp_od_id . " / 삼진운송장번호 : " . $wmresult['INV_NO'] . "<br>";

		include(G5_SHOP_PATH . '/ordermail1.inc.php');
		$arr_change_data = array();
		$arr_change_data['고객명'] = $od['od_name'];
		$arr_change_data['이름'] = $od['od_name'];
		$arr_change_data['보낸분'] = $od['od_name'];
		$arr_change_data['받는분'] = $od['od_b_name'];;
		$arr_change_data['주문번호'] = $od_id;
		$arr_change_data['주문금액'] = number_format($ttotal_price + $od_send_cost + $od_send_cost2);
		$arr_change_data['결제금액'] = number_format($od_receipt_price);
		$arr_change_data['회원아이디'] = $od['mb_id'];
		$arr_change_data['회사명'] = $default['de_admin_company_name'];
		$arr_change_data["아이디"] = $od['mb_id'];
		$arr_change_data["고객명(아이디)"] = $od['od_name'] . "(" . $od['mb_id'] . ")";
		$arr_change_data["od_list"] = $list;
		$arr_change_data['od_type'] = $od['od_type'];
		$arr_change_data['od_id'] = $od_id;

		if ($od['od_type'] == "L") {
			msg_autosend('세탁', '세탁 박스배송 시작', $od['mb_id'], $arr_change_data);
		} else if ($od['od_type'] == "K") {
			msg_autosend('세탁보관', '세탁 박스배송 시작', $od['mb_id'], $arr_change_data);
		}
	} else {
		echo "<br/>세탁/보관 박스배송 주문번호 : " . $disp_od_id . " / 삼진운송장번호 미등록 <br>";
	}
}
*/
