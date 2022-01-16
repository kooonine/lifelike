<?php
$chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
$root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));

include_once($root_path . '/../../common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');
require_once(G5_SHOP_PATH . '/settle_lg2.inc.php');

$run_payment = $_REQUEST['run'] == 'true' ? true : false;

if (isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == "true") $run_payment = true;

$od_pg = "lg";
$rtnData = array();
$sql_prefix = "SELECT r.*,o.*,r.rt_payment_count AS rental_pay_count
                FROM {$g5['g5_shop_rental_table']} AS r
                JOIN {$g5['g5_shop_rental_order_table']} AS ro ON r.rt_id=ro.rt_id
                JOIN {$g5['g5_shop_order_table']} AS o ON ro.od_id=o.od_id ";

// 정기결제 - 리스건에 대한 정기 결제

//미납분처리
//① 최초 월요금은 ‘고객’의 계약등록시점이며 이후 계약기간 동안 월정액을 청구합니다.
//② 월요금은 ‘고객’의 계약등록시점일을 기준으로 익월 같은 일자에 청구되며, 전월 미납금액이 있을 경우에는 미납금액을 포함하여 당월에 청구할 수 있습니다.

// 자동 리스시작 처리 시작 - 191104 balance@panpacific.co.kr
// 주문상태가 "배송완료"인 주문(order)중 7일이 경과한 주문의 상태를 변경
$sql_invoice_check = $sql_prefix . " WHERE o.od_status='배송완료'
                    AND DATE_ADD(o.od_invoice_time, INTERVAL 7 DAY) <= DATE_FORMAT(NOW(), '%Y-%m-%d')";
$db_invoice_check = sql_query($sql_invoice_check);

if (sql_num_rows($db_invoice_check) > 0) {
    $now = G5_TIME_YMDHIS;
    $ct_status = '리스중';
    while (($row = sql_fetch_array($db_invoice_check)) != false) {

        // 재고 감소 처리
        $ct_sql = "SELECT * FROM {$g5['g5_shop_cart_table']} WHERE od_id = '{$row['od_id']}'";
        $ct_result = sql_query($ct_sql);
        for ($ci = 0; $ct = sql_fetch_array($ct_result); $ci++) {
            // 재고를 사용하지 않았다면
            $stock_use = $row['ct_stock_use'];
            if (!$row['ct_stock_use']) {
                $stock_use = 1;
                $sql = "UPDATE {$g5['g5_shop_item_option_table']}
                        SET io_stock_qty = io_stock_qty - '{$ct['ct_qty']}'
                        WHERE it_id = '{$ct['it_id']}'
                        AND io_id = '{$ct['io_id']}'
                        AND io_type = '{$ct['io_type']}' ";
                sql_query($sql);
            }

            // 히스토리에 등록 (작업|아이디|시간|IP|그리고 나머지 자료)

            $ct_history = "\n$ct_status|SYSTEM|{$now}|{$_SERVER['SERVER_ADDR']}";
            $sql = "UPDATE {$g5['g5_shop_cart_table']}
                    SET ct_stock_use = '{$stock_use}', ct_status = '{$ct_status}', ct_history    = CONCAT(IFNULL(ct_history,''),'{$ct_history}')
                    WHERE od_id = '{$row['od_id']}'
                    AND ct_id = '{$ct['ct_id']}'";
            sql_query($sql);
            sql_query("UPDATE lt_shop_order_item SET ct_status = '$ct_status' WHERE od_id = '{$row['od_id']}' AND ct_id  = '{$ct['ct_id']}'");
        }

        //리스계약서 PDF 파일 생성
        $sql = "UPDATE {$g5['g5_shop_order_table']}
                SET od_status = '{$ct_status}', rt_rental_startdate = '" . G5_TIME_YMD . "'
                WHERE od_id = '{$row['od_id']}'";
        sql_query($sql);
        array_push($rtnData, array("RESULT" => '주문번호:' . $row['od_id'] . ', 리스시작: ' . G5_TIME_YMD . ' - ' . $row['od_invoice_time'] . '배송완료'));
    }
}

// 자동 리스시작 처리 끝

// 주문취소 주문(order)의 계약(rental)의 상태를 변경
$sql_change_status = "SELECT r.rt_id, r.rt_status, o.rt_rental_startdate, (SELECT st_no FROM lt_shop_order_status WHERE st_str=r.rt_status) AS rt_status_num, o.od_status, (SELECT st_no FROM lt_shop_order_status WHERE st_str=o.od_status) AS od_status_num
                      FROM lt_shop_rental AS r
                      JOIN lt_shop_rental_order AS ro ON r.rt_id=ro.rt_id
                      JOIN lt_shop_order AS o ON ro.od_id=o.od_id
                      GROUP BY ro.rt_id HAVING rt_status_num != od_status_num
                      ORDER BY od_status_num DESC";
$db_change_status = sql_query($sql_change_status);

if (sql_num_rows($db_change_status) > 0) {
    while (($row = sql_fetch_array($db_change_status)) != false) {
        $sql_update_rental_status = "UPDATE {$g5['g5_shop_rental_table']} SET rt_status='{$row['od_status']}'";
        if ($row['od_status'] == '리스중' || $row['od_status'] == '리스완료') {
            $sql_update_rental_status .= ", rt_rental_startdate='{$row['rt_rental_startdate']}'";
        }
        $sql_update_rental_status .= " WHERE rt_id={$row['rt_id']}";
        sql_query($sql_update_rental_status);

        echo $row['rt_id'] . " : " . $row['rt_status'] . " => " . $row['od_status'] . "<br>";
    }
}

// 주문상태가 리스중 OR 리스완료인 주문(order)의 계약(rental)의 상태를 변경
$sql_order_status = $sql_prefix . " WHERE o.od_status IN ('리스중','리스완료') AND r.rt_status != o.od_status GROUP BY ro.rt_id";
$db_order_status = sql_query($sql_order_status);

if (sql_num_rows($db_order_status) > 0) {
    while (($row = sql_fetch_array($db_order_status)) != false) {
        $sql_update_rental_status = "UPDATE {$g5['g5_shop_rental_table']} SET rt_status='{$row['od_status']}', rt_rental_startdate='{$row['rt_rental_startdate']}' WHERE rt_id={$row['rt_id']}";
        sql_query($sql_update_rental_status);
    }
}

// 오늘이 결제일이고 다음 결제일이 오늘보다 작거나 같음.
// (리스시작일 + 결재횟수 개월 = 다음결제일) <= 오늘
// billday(결재일) 체크 제거. 그 다음 조건문에서 확인가능 함
/*
$sql_rentals = $sql_prefix . " WHERE rt_status = '리스중'
                AND DATE_FORMAT(NOW(), '%e') = r.rt_billday
                AND DATE_ADD(DATE_ADD(r.rt_rental_startdate, interval IF(RIGHT(r.rt_rental_startdate, 2) > 28, (RIGHT(r.rt_rental_startdate, 2)-28) * -1, 0) day), interval r.rt_payment_count month) <= DATE_FORMAT(NOW(), '%Y-%m-%d')
                GROUP BY r.rt_id";
                */
$sql_rentals = $sql_prefix . " WHERE rt_status = '리스중'
                AND DATE_ADD(DATE_ADD(r.rt_rental_startdate, interval IF(RIGHT(r.rt_rental_startdate, 2) > 28, (RIGHT(r.rt_rental_startdate, 2)-28) * -1, 0) day), interval r.rt_payment_count month) <= DATE_FORMAT(NOW(), '%Y-%m-%d')
                GROUP BY r.rt_id";

// 계약번호를 넘겨받은 경우
if (isset($rt_id)) {
    $rt_id = preg_replace('/[^0-9]/', '', $od_id);
    $sql_rentals = $sql_prefix . " WHERE rt_status = '리스중' AND r.rt_id={$rt_id}";
}

$db_rentals = sql_query($sql_rentals);

if (sql_num_rows($db_rentals) > 0) {
    if (!$run_payment) {
        $run_count = 1;
?>
        <style>
            #tbl-admin-cron {
                border-spacing: unset;
                border: 1px solid #DDDDDD;
                border-width: 0 1px 1px 0;
                font-size: 12px;
            }

            #tbl-admin-cron th,
            #tbl-admin-cron td {
                border: 1px solid #DDDDDD;
                border-width: 1px 0 0 1px;
                padding: 4px;
                text-align: center;
            }
        </style>
        <table id='tbl-admin-cron'>
            <tr>
                <th>순번</th>
                <th>계약번호</th>
                <th>납부일</th>
                <th>계약자</th>
                <th>납부회차</th>
                <th>납부금액</th>
            </tr>
    <?
    }
    while (($row = sql_fetch_array($db_rentals)) != false) {
        if (!$run_payment) {
            echo "<tr><td>{$run_count}</td><td>{$row['rt_id']}</td><td>{$row['rt_rental_startdate']}</td><td>{$row['rt_name']}</td><td>{$row['rental_pay_count']}</td><td>{$row['rt_rental_price']}</td></tr>";
            $run_count++;
            if ($run_count > $db_rentals->num_rows) {
                echo "<tr><td colspan=6 style='text-align: center;'><a href='?run=true'><button>결제진행</button></a></td></tr></table>";
                exit();
            }
            continue;
        }
        $rt_id = $row['rt_id'];
        $rt_payment_count = (int) $row['rental_pay_count'] + 1;

        //납부회차 종료 건
        if ($rt_payment_count > (int) $row['rt_month']) {
            $sql_orders = $sql_prefix . " WHERE r.rt_id={$rt_id}";
            $db_orders = sql_query($sql_orders);
            $sql_update_rental_finish = array();

            while (($od = sql_fetch_array($db_orders)) != false) {
                $sql_update_rental_finish[] = "UPDATE lt_shop_order SET od_status = '리스완료' where od_id = '{$od['od_id']}' and od_status = '리스중' ";
                $sql_update_rental_finish[] = "UPDATE lt_shop_cart SET ct_status = '리스완료' where od_id = '{$od['od_id']}' and ct_status = '리스중' ";
                $sql_update_rental_finish[] = "UPDATE lt_shop_order_item SET ct_status = '리스완료' where od_id = '{$od['od_id']}' and ct_status = '리스중' ";
            }
            $sql_update_rental_finish[] = "UPDATE lt_shop_rental SET rt_status = '리스완료' where rt_id = '{$rt_id}' and rt_status = '리스중' ";
            foreach ($sql_update_rental_status as $sf) sql_query($sf, true);

            include(G5_SHOP_PATH . '/ordermail1.inc.php');
            $arr_change_data = array();
            $arr_change_data['고객명'] = $row['rt_name'];
            $arr_change_data['이름'] = $row['rt_name'];
            $arr_change_data['보낸분'] = $row['rt_name'];
            $arr_change_data['받는분'] = $row['rt_b_name'];
            $arr_change_data['주문번호'] = $rt_id;
            $arr_change_data['주문금액'] = number_format($row['rt_rental_price']);
            $arr_change_data['결제금액'] = number_format($row['rt_rental_price']);
            $arr_change_data['회원아이디'] = $row['mb_id'];
            $arr_change_data['회사명'] = $default['de_admin_company_name'];
            $arr_change_data["아이디"] = $row['mb_id'];
            $arr_change_data["고객명(아이디)"] = $row['rt_name'] . "(" . $row['mb_id'] . ")";
            $arr_change_data["od_list"] = $list;
            $arr_change_data['od_type'] = $row['rt_type'];
            $arr_change_data['od_id'] = $row_id;

            msg_autosend('리스', '계약 완료', $row['mb_id'], $arr_change_data);
            array_push($rtnData, array("RESULT" => '주문번호:' . $rt_id . ', 리스완료: ' . $row['rental_pay_count'] . '회 납부완료'));
            continue;
        }

        //납부회차 종료 건 끝

        // 결제 시도
        // 결제 회차 2자리로 변경 - 200106 balance@panpacific.co.kr
        $LGD_OID = sprintf("%s-%02d", $rt_id, $rt_payment_count);
        $LGD_BILLKEY = $row['rt_lgd_billkey'];   //LG유플러스 빌링키
        $LGD_AMOUNT = $row['rt_rental_price'];

        $xpay = new XPay($configPath, $CST_PLATFORM);

        // Mert Key 설정
        $xpay->set_config_value('t' . $LGD_MID, $LGD_MERTKEY);
        $xpay->set_config_value($LGD_MID, $LGD_MERTKEY);

        $xpay->Init_TX($LGD_MID);

        $xpay->Set("LGD_TXNAME", "CardAuth");
        $xpay->Set("LGD_OID", $LGD_OID);
        $xpay->Set("LGD_AMOUNT", $LGD_AMOUNT);
        $xpay->Set("LGD_PAN", $LGD_BILLKEY);
        $xpay->Set("LGD_INSTALL", "00");
        $xpay->Set("LGD_BUYERIP", $_SERVER["REMOTE_ADDR"]);
        $xpay->Set("VBV_ECI", "010");

        if ($xpay->TX()) {
            if ('0000' == $xpay->Response_Code()) {
                //최종결제요청 결과 성공 DB처리
                $tno             = $xpay->Response('LGD_TID', 0);
                $amount          = $xpay->Response('LGD_AMOUNT', 0);
                $app_time        = $xpay->Response('LGD_PAYDATE', 0);
                $bank_name       = $xpay->Response('LGD_FINANCENAME', 0);
                $depositor       = $xpay->Response('LGD_PAYER', 0);
                $account         = $xpay->Response('LGD_FINANCENAME', 0) . ' ' . $xpay->Response('LGD_ACCOUNTNUM', 0) . ' ' . $xpay->Response('LGD_SAOWNER', 0);
                $commid          = $xpay->Response('LGD_FINANCENAME', 0);
                $mobile_no       = $xpay->Response('LGD_TELNO', 0);
                $app_no          = $xpay->Response('LGD_FINANCEAUTHNUM', 0);
                $card_name       = $xpay->Response('LGD_FINANCENAME', 0);
                $pay_type        = $xpay->Response('LGD_PAYTYPE', 0);
                $escw_yn         = $xpay->Response('LGD_ESCROWYN', 0);
                $card_num        = $xpay->Response('LGD_CARDNUM', 0);

                $od_tno             = $tno;
                $od_app_no          = $app_no;
                $od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
                $od_bank_account    = $card_name . "/" . $card_num;
                $pg_price           = $amount;
                $od_receipt_price   = $amount;
                $od_tax_flag      = $default['de_tax_flag_use'];

                $od_escrow = 0;
                $od_tax_mny = round($amount / 1.1);
                $od_vat_mny = $amount - $od_tax_mny;
                $od_free_mny = 0;

                if ($tno && $default['de_card_test']) {
                    //정상카드 확인을 위한 Test결제 성공 => 환불처리 진행 X
                    //최소 계약등록시 첫달 리스요금 결제, 테스트 결제 시 환불처리
                    $cancel_msg = '테스트 결제 내역 환불처리 ';
                    $xpay->Rollback($cancel_msg . " [TID:" . $xpay->Response("LGD_TID", 0) . ",MID:" . $xpay->Response("LGD_MID", 0) . ",OID:" . $xpay->Response("LGD_OID", 0) . "]");
                    if ("0000" == $xpay->Response_Code()) {
                        //$pg_price           = 0; //결제된 금액은 없음
                        //$od_receipt_price   = 0; //결제된 금액은 없음
                        //echo "자동취소가 정상적으로 완료 되었습니다.<br>";
                    } else {
                        array_push($rtnData, array("RESULT" => "카드 등록이 정상적으로 처리되지 않았습니다."));
                        continue;
                    }
                }

                // 결제 정보에 입력
                $sql = "INSERT lt_shop_order_add_receipt
                        SET od_id             = '$rt_id'
                        ,od_type              = '{$row['rt_type']}'
                        ,mb_id                = '{$row['mb_id']}'
                        ,od_send_cost         = 0
                        ,od_receipt_price     = '$od_receipt_price'
                        ,od_cancel_price      = 0
                        ,od_receipt_point     = 0
                        ,od_refund_price      = 0
                        ,od_bank_account      = '$od_bank_account'
                        ,od_deposit_name      = ''
                        ,od_receipt_time      = '$od_receipt_time'
                        ,od_misu              = 0
                        ,od_pg_id             = 'lg2'
                        ,od_settle_case       = '신용카드'
                        ,od_mobile            = 0
                        ,od_pg                = '$od_pg'
                        ,od_tno               = '$od_tno'
                        ,od_app_no            = '$od_app_no'
                        ,od_escrow            = '$od_escrow'
                        ,od_tax_flag          = '$od_tax_flag'
                        ,od_tax_mny           = '$od_tax_mny'
                        ,od_vat_mny           = '$od_vat_mny'
                        ,od_free_mny          = '$od_free_mny'
                        ,od_ip                = '$REMOTE_ADDR'
                        ,od_mb_id             = 'admin'
                        ,od_receipt_type      = 'rental'
                        ,od_test              = '0'
                        ,od_receipt_rental_month = '$rt_payment_count'";
                $result = sql_query($sql, false);

                // 주문정보 입력 오류시 결제 취소
                if (!$result) {
                    if ($tno) {
                        $cancel_msg = '주문정보 입력 오류';
                        include G5_SHOP_PATH . '/lg2/xpay_cancel.php';
                    }

                    array_push($rtnData, array("RESULT" => '<p>고객님의 주문 정보를 처리하는 중 오류가 발생해서 주문이 완료되지 않았습니다.</p><p>' . strtoupper($od_pg) . '를 이용한 전자결제(신용카드, 계좌이체, 가상계좌 등)은 자동 취소되었습니다.'));
                    continue;
                }

                $rt_receipt_price = ($rt_payment_count == 1) ? $od_receipt_price : "rt_receipt_price + {$od_receipt_price}";
                $sql = "UPDATE {$g5['g5_shop_rental_table']}
                        SET rt_receipt_price  = {$rt_receipt_price},
                            rt_payment_count  = {$rt_payment_count},
                            rt_payment_status = '정상납부'
                        WHERE rt_id = '$rt_id'";

                $result = sql_query($sql, false);

                $sql_orders = $sql_prefix . " WHERE r.rt_id={$rt_id}";
                $db_orders = sql_query($sql_orders);

                while (($od = sql_fetch_array($db_orders)) != false) {
                    SM_ADD_RENTAL_IBKEUM($od['od_id']);
                }

                array_push($rtnData, array("RESULT" => '주문번호:' . $rt_id . ', 납부회차: ' . $rt_payment_count . ', 정상 납부금액 : ' . $od_receipt_price));

                include(G5_SHOP_PATH . '/ordermail1.inc.php');
                $arr_change_data = array();
                $arr_change_data['고객명'] = $row['od_name'];
                $arr_change_data['이름'] = $row['od_name'];
                $arr_change_data['보낸분'] = $row['od_name'];
                $arr_change_data['받는분'] = $row['od_b_name'];;
                $arr_change_data['주문번호'] = $rt_id;
                $arr_change_data['주문금액'] = number_format($od_receipt_price);
                $arr_change_data['결제금액'] = number_format($od_receipt_price);
                $arr_change_data['회원아이디'] = $row['mb_id'];
                $arr_change_data['회사명'] = $default['de_admin_company_name'];
                $arr_change_data["아이디"] = $row['mb_id'];
                $arr_change_data["고객명(아이디)"] = $row['od_name'] . "(" . $row['mb_id'] . ")";
                $arr_change_data["od_list"] = $list;
                $arr_change_data['od_type'] = $row['od_type'];
                $arr_change_data['od_id'] = $rt_id;

                msg_autosend('리스', '리스중 결제시점', $rt['mb_id'], $arr_change_data);
            } else {
                array_push($rtnData, array("RESULT" => '주문번호:' . $rt_id . '-' . $rt_payment_count . ' 결제실패 : ' . $xpay->Response_Msg() . ' 코드 : ' . $xpay->Response_Code()));

                $sql = "UPDATE {$g5['g5_shop_rental_table']}
                        SET rt_payment_status = '미납'
                        WHERE rt_id = '$rt_id'
                    ";
            }
        } else {
            array_push($rtnData, array("RESULT" => '주문번호:' . $rt_id . '-' . $rt_payment_count . ' 결제실패 : ' . $xpay->Response_Msg() . ' 코드 : ' . $xpay->Response_Code()));

            $sql = "UPDATE {$g5['g5_shop_rental_table']}
                        SET rt_payment_status = '미납'
                        WHERE rt_id = '$rt_id'
                    ";
        }
    }
} else {
    array_push($rtnData, array("RESULT" => "납부 처리할 리스 계약건이 없습니다."));
}

echo json_encode_raw($rtnData);
    ?>