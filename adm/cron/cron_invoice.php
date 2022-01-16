<? #!/usr/local/php53/bin/php
$chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
$root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));

include_once($root_path . '/../../common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');
require_once(G5_LIB_PATH . '/Unirest.php');

//크론5 : 배송중 => 배송완료 처리/배송상태 업데이트

function invoice_check($invc_co = "", $invc_no = "")
{
    global $default, $request, $company, $outputs;
    $complete = false;

    if ($default['de_card_test']) {
        //test 전부 배달완료처리
        $complete = true;
    } else {
        $code = array_search($invc_co, $company);
        $tracking_url = sprintf("%s?t_key=%s&t_code=%s&t_invoice=%s", $default['de_tracking_api'], $default['de_tracking_api_key'], $code, $invc_no);
        $tracking_response = $request->get($tracking_url);

        if ($tracking_response->code === 200) {
            $complete = $tracking_response->body->complete;
        } else {
            $outputs = sprintf("%s : %s(%s)", date('Y-m-d H:i:s', time()), $tracking_response->msg, $tracking_response->code);
        }
    }

    return $complete;
}

$outputs = array();
$company = array();
$request = new Unirest;
$response = $request->get($default['de_tracking_api_company'] . '?t_key=' . $default['de_tracking_api_key']);
if ($response->code === 200) {
    foreach ($response->body->Company as $com) {
        $company[$com->Code] = $com->Name;
    }
}

if (empty($company)) {
    $outputs[] = date('Y-m-d H:i:s', time()) . " : 택배사 목록 조회 실패($response->msg($response->code))";
    print_raw($outputs);
    exit();
}

$outputs[] = date('Y-m-d H:i:s', time()) . " : 배송 조회 동기화 시작";
$outputs[] = date('Y-m-d H:i:s', time()) . " : 수거 배송 동기화";
// 2. 수거 배송 완료 처리
$current_status = "수거중";
$change_status = "수거완료";
$sql = " select od_type, od_id, od_pickup_invoice, od_pickup_delivery_company from lt_shop_order where od_status = '{$current_status}' ";
$box_result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($box_result); $i++) {
    $od_id = $row['od_id'];
    $invc_co = $row['od_pickup_delivery_company'];
    $invc_no = $row['od_pickup_invoice'];
    if (invoice_check($invc_co, $invc_no)) {
        $sql = " update {$g5['g5_shop_order_table']} set od_status = '{$change_status}' where od_id = '{$od_id}' and od_status = '{$current_status}' ";
        sql_query($sql, true);

        $sql = " update {$g5['g5_shop_cart_table']} set ct_status = '{$change_status}' where od_id = '{$od_id}' and ct_status = '{$current_status}' ";
        sql_query($sql, true);

        $outputs[] = sprintf("%s : %s(%s - %s)", $od_id, $change_status, $invc_no, $invc_co);

        include(G5_SHOP_PATH . '/ordermail1.inc.php');
        $arr_change_data = array();
        $arr_change_data['고객명'] = $row['od_name'];
        $arr_change_data['이름'] = $row['od_name'];
        $arr_change_data['보낸분'] = $row['od_name'];
        $arr_change_data['받는분'] = $row['od_b_name'];;
        $arr_change_data['주문번호'] = $od_id;
        $arr_change_data['주문금액'] = number_format($ttotal_price + $od_send_cost + $od_send_cost2);
        $arr_change_data['결제금액'] = number_format($od_receipt_price);
        $arr_change_data['회원아이디'] = $row['mb_id'];
        $arr_change_data['회사명'] = $default['de_admin_company_name'];
        $arr_change_data["아이디"] = $row['mb_id'];
        $arr_change_data["고객명(아이디)"] = $row['od_name'] . "(" . $row['mb_id'] . ")";
        $arr_change_data["od_list"] = $list;
        $arr_change_data['od_type'] = $row['od_type'];
        $arr_change_data['od_id'] = $od_id;

        if ($row['od_type'] == "L") {
            msg_autosend('세탁', '세탁 수거 완료', $row['mb_id'], $arr_change_data);
        } else if ($row['od_type'] == "K") {
            msg_autosend('세탁보관', '세탁보관 수거완료', $row['mb_id'], $arr_change_data);
        } else if ($row['od_type'] == "S") {
            msg_autosend('수선', '수선 수거 완료', $row['mb_id'], $arr_change_data);
        }
    }
}

// 3. 일반 배송 완료 처리
$outputs[] = date('Y-m-d H:i:s', time()) . " : 일반 배송 동기화";
$current_status = "배송중";
$change_status = "배송완료";
// $sql = " select od_type, od_id, od_invoice, od_delivery_company, od_name, mb_id, od_b_name  from lt_shop_order where od_status = '{$current_status}'";
$sql = "select cart.ct_delivery_company, cart.ct_invoice, cart.ct_id, sorder.od_type, sorder.od_id, sorder.od_invoice, sorder.od_delivery_company, sorder.od_name, sorder.mb_id, sorder.od_b_name  from lt_shop_order AS sorder LEFT JOIN lt_shop_cart AS cart ON sorder.od_id = cart.od_id where (sorder.od_status = '{$current_status}' || cart.ct_status = '{$current_status}')";
$box_result = sql_query($sql);

for ($i = 0; $row = sql_fetch_array($box_result); $i++) {
    $od_id = $row['od_id'];
    $ct_id = $row['ct_id'];
    $invc_co = $row['od_delivery_company'];
    $invc_no = $row['od_invoice'];
    if (!$invc_co) {
        $invc_co = $row['ct_delivery_company'];
    }
    if (!$invc_no) {
        $invc_no = $row['ct_invoice'];
    }
    if (invoice_check($invc_co, $invc_no)) {
        $od_type = $row['od_type'];

        include(G5_SHOP_PATH . '/ordermail1.inc.php');
        $arr_change_data = array();
        $arr_change_data['고객명'] = $row['od_name'];
        $arr_change_data['이름'] = $row['od_name'];
        $arr_change_data['보낸분'] = $row['od_name'];
        $arr_change_data['주문번호'] = $od_id;
        $arr_change_data['총주문금액'] = number_format($ttotal_price + $od_send_cost);
        $arr_change_data['회원아이디'] = $member['mb_id'];
        $arr_change_data["od_list"] = $list;
        $arr_change_data['od_type'] = $od_type;
        $arr_change_data['od_id'] = $od_id;

        if ($od_type == "O") {
            $change_status = "배송완료";
            $arr_change_data['button'] = array(
                "type" => "웹링크",
                "txt" => "주문내역 확인하기",
                "link" => "https://lifelike.co.kr/member/order.php?od_id=" . $arr_change_data['od_id']
            );
            msg_autosend('주문', '배송 완료', $row['mb_id'], $arr_change_data);
        } else if ($od_type == "R") {
            $change_status = "배송완료";
            msg_autosend('리스', '배송 완료', $row['mb_id'], $arr_change_data);
        } else if ($od_type == "L") {
            $change_status = "세탁완료";
            //2. 세탁/보관 처리후 고객에게 배송완료 (SM_ADD_CLEANING_FINISH)
            SM_ADD_CLEANING_FINISH($od_id);

            $ct = sql_fetch(" select buy_ct_id,buy_od_sub_id,ct_free_laundry_use from lt_shop_cart where od_id = '$od_id' ");

            //구매제품 상태 초기화, 무료세탁일 경우 사용 처리
            sql_query("update lt_shop_order_item set ct_status = '', ct_free_laundry_use=ct_free_laundry_use+'{$ct['ct_free_laundry_use']}' where ct_id = '{$ct['buy_ct_id']}' and od_sub_id  = '{$ct['buy_od_sub_id']}'");

            msg_autosend('세탁', '세탁 배송 완료', $row['mb_id'], $arr_change_data);
        } else if ($od_type == "K") {
            $change_status = "보관완료";
            //2. 세탁/보관 처리후 고객에게 배송완료 (SM_ADD_CLEANING_FINISH)
            SM_ADD_CLEANING_FINISH($od_id);

            $ct = sql_fetch(" select buy_ct_id,buy_od_sub_id,ct_free_laundry_use from lt_shop_cart where od_id = '$od_id' ");

            //구매제품 상태 초기화, 무료세탁일 경우 사용 처리
            sql_query("update lt_shop_order_item set ct_status = '', ct_free_laundry_use=ct_free_laundry_use+'{$ct['ct_free_laundry_use']}' where ct_id = '{$ct['buy_ct_id']}' and od_sub_id  = '{$ct['buy_od_sub_id']}'");

            msg_autosend('세탁보관', '배송 완료', $row['mb_id'], $arr_change_data);
        } else if ($od_type == "S") {
            $change_status = "수선완료";
            //4. 수선 완료완료후 고객에게 배송됨 (SM_ADD_REPAIR_FINISH)
            if ($row['od_status_claim'] == "") {
                //클레임이 아닌 건은 완료처리.
                SM_ADD_REPAIR_FINISH($od_id);
            }

            $ct = sql_fetch(" select buy_ct_id,buy_od_sub_id from lt_shop_cart where od_id = '$od_id' ");

            //구매제품 상태 초기화 - 수선완료
            sql_query("update lt_shop_order_item set ct_status = '' where ct_id = '{$ct['buy_ct_id']}' and od_sub_id  = '{$ct['buy_od_sub_id']}'");

            msg_autosend('수선', '수선 배송 완료', $row['mb_id'], $arr_change_data);
        }

        $update_sql = array();
        // 주문서 배송상태 업데이트
        $update_sql[] = " update {$g5['g5_shop_order_table']} set od_status = '{$change_status}' where od_id = '{$od_id}' and od_status = '{$current_status}' ";
        // 장바구니 배송상태 업데이트
        $update_sql[] = " update {$g5['g5_shop_cart_table']} set ct_status = '{$change_status}' where od_id = '{$od_id}' and ct_status = '{$current_status}' AND ct_id = '{$ct_id}' ";

        foreach ($update_sql as $sql) {
            sql_query($sql, true);
        }

        $outputs[] = sprintf("%s : %s(%s - %s)", $od_id, $change_status, $invc_no, $invc_co);
    }
}


// 4. 오래된 배송건 자동으로 강제 배송완료 처리 (CJ가 아닌 택배) 배송 15일이 지난 건. 
// $outputs[] = date('Y-m-d H:i:s', time()) . " : 15일 지난 배송 동기화";
// $current_status = "배송중";
// $change_status = "배송완료";
// // $sql = " select od_type, od_id, od_invoice, od_delivery_company from lt_shop_order where od_status = '{$current_status}' and od_invoice_time is not null && od_invoice_time <= DATE_ADD(now(), INTERVAL -15 day) ";
// $sql = "select cart.ct_delivery_company, cart.ct_invoice, cart.ct_id, sorder.od_type, sorder.od_id, sorder.od_invoice, sorder.od_delivery_company from lt_shop_order AS sorder LEFT JOIN lt_shop_cart AS cart ON sorder.od_id = cart.od_id WHERE (sorder.od_status = '{$current_status}' || cart.ct_status = '{$current_status}') && ( (sorder.od_invoice_time is not null && sorder.od_invoice_time != '0000-00-00 00:00:00' && sorder.od_invoice_time <= DATE_ADD(now(), INTERVAL -15 day) ) || (cart.ct_invoice_time is not null && cart.ct_invoice_time != '0000-00-00 00:00:00' && cart.ct_invoice_time <= DATE_ADD(now(), INTERVAL -15 day)) ) ";
// $box_result = sql_query($sql);
// for ($i = 0; $row = sql_fetch_array($box_result); $i++) {
//     $od_id = $row['od_id'];
//     $invc_co = $row['od_delivery_company'];
//     $invc_no = $row['od_invoice'];

//     $ct_id = $row['ct_id'];
//     if (!$invc_co) {
//         $invc_co = $row['ct_delivery_company'];
//     }
//     if (!$invc_no) {
//         $invc_no = $row['ct_invoice'];
//     }
//     $update_sql = array();
//     // 주문서 배송상태 업데이트
//     $update_sql[] = " update {$g5['g5_shop_order_table']} set od_status = '{$change_status}' where od_id = '{$od_id}' and od_status = '{$current_status}' ";
//     // 장바구니 배송상태 업데이트
//     $update_sql[] = " update {$g5['g5_shop_cart_table']} set ct_status = '{$change_status}' where od_id = '{$od_id}' and ct_status = '{$current_status}' AND ct_id = '{$ct_id}' ";

//     foreach ($update_sql as $sql) {
//         sql_query($sql, true);
//     }
//     $outputs[] = sprintf("%s : %s(%s - %s)", $od_id, $change_status, $invc_no, $invc_co);
// }

$outputs[] = date('Y-m-d H:i:s', time()) . " : 배송 조회 동기화 종료";

print_raw($outputs);

/*
// 구버전 내용 주석처리 - 200130 balance@panpacific.co.kr

// 1. 수거박스 배송 완료 처리
$current_status = "수거박스배송";
$change_status = "박스배송완료";
$sql = " select od_type, od_id, od_boxsend_invoice from lt_shop_order where od_status = '{$current_status}' ";
$box_result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($box_result); $i++) {
    $od_id = $row['od_id'];
    $invc_no = $row['od_boxsend_invoice'];
    if (invoice_check($invc_no)) {
        $sql = " update {$g5['g5_shop_order_table']} set od_status = '{$change_status}' where od_id = '{$od_id}' and od_status = '{$current_status}' ";
        sql_query($sql, true);

        $sql = " update {$g5['g5_shop_cart_table']} set ct_status = '{$change_status}' where od_id = '{$od_id}' and ct_status = '{$current_status}' ";
        sql_query($sql, true);

        echo "<br/>수거박스 배송 완료 : " . $od_id . " / 운송장번호 : " . $invc_no . "<br>";

        include(G5_SHOP_PATH . '/ordermail1.inc.php');
        $arr_change_data = array();
        $arr_change_data['고객명'] = $row['od_name'];
        $arr_change_data['이름'] = $row['od_name'];
        $arr_change_data['보낸분'] = $row['od_name'];
        $arr_change_data['받는분'] = $row['od_b_name'];;
        $arr_change_data['주문번호'] = $od_id;
        $arr_change_data['주문금액'] = number_format($ttotal_price + $od_send_cost + $od_send_cost2);
        $arr_change_data['결제금액'] = number_format($od_receipt_price);
        $arr_change_data['회원아이디'] = $row['mb_id'];
        $arr_change_data['회사명'] = $default['de_admin_company_name'];
        $arr_change_data["아이디"] = $row['mb_id'];
        $arr_change_data["고객명(아이디)"] = $row['od_name'] . "(" . $row['mb_id'] . ")";
        $arr_change_data["od_list"] = $list;
        $arr_change_data['od_type'] = $row['od_type'];
        $arr_change_data['od_id'] = $od_id;

        if ($row['od_type'] == "L") {
            msg_autosend('세탁', '세탁 박스배송 완료', $row['mb_id'], $arr_change_data);
        } else if ($row['od_type'] == "K") {
            msg_autosend('세탁보관', '세탁 박스배송 완료', $row['mb_id'], $arr_change_data);
        }
    }
}

*/
