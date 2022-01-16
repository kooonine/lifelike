<? #!/usr/local/php53/bin/php
$chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
$root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));

include_once($root_path . '/../../common.php');

// 크론4 : 구매확정처리
$current_status = "배송완료";
$sql = " select * from lt_shop_order where od_status = '{$current_status}' and od_invoice_time <= DATE_ADD(now(), INTERVAL -7 day) ";
$result = sql_query($sql);
$outputs = array();
$outputs[] = date('Y-m-d H:i:s', time()) . " : 구매완료 동기화 시작";
for ($i = 0; $od = sql_fetch_array($result); $i++) {
    $od_id = $od['od_id'];

    if ($od['od_type'] == "O") {

        $ct_status = '구매확정';

        // 재고에서 뺀다.
        $ct_sql = " select * from {$g5['g5_shop_cart_table']} where od_id = '$od_id' and  ct_status = '{$current_status}' ";
        $ct_result = sql_query($ct_sql);
        for ($i = 0; $ct = sql_fetch_array($ct_result); $i++) {
            // 재고를 사용하지 않았다면
            $stock_use = $row['ct_stock_use'];
            if (!$row['ct_stock_use']) {
                $stock_use = 1;
                $sql = " update {$g5['g5_shop_item_option_table']}
                                set io_stock_qty = io_stock_qty - '{$ct['ct_qty']}'
                                where it_id = '{$ct['it_id']}'
                                  and io_id = '{$ct['io_id']}'
                                  and io_type = '{$ct['io_type']}' ";
                sql_query($sql);
            }

            // 히스토리에 남김
            // 히스토리에 남길때는 작업|아이디|시간|IP|그리고 나머지 자료
            $now = G5_TIME_YMDHIS;
            $ct_history = "\n$ct_status|{$member['mb_id']}|$now|$REMOTE_ADDR";

            $sql = " update {$g5['g5_shop_cart_table']}
                        set ct_stock_use  = '$stock_use',
                            ct_status     = '$ct_status',
                            ct_history    = CONCAT(ct_history,'$ct_history')
                    where od_id = '$od_id'
                    and ct_id  = '{$ct['ct_id']}' ";
            sql_query($sql);

            sql_query("update lt_shop_order_item set ct_status = '$ct_status' where od_id = '$od_id' and ct_id  = '{$ct['ct_id']}'");
        }

        $sql = " update {$g5['g5_shop_order_table']}
                    set od_status = '$ct_status'
                    where od_id = '$od_id' ";
        sql_query($sql);

        // 포인트 적립
        $selOd = "SELECT od_point_save FROM lt_shop_order WHERE od_id = '$od_id' LIMIT 1";
        $so = sql_fetch($selOd);
        if($so['od_point_save'] == 1) {
            $poSel = "SELECT  SUM(ct_point_save) AS poSum FROM lt_shop_cart  WHERE od_id = '$od_id'";
            $sp = sql_fetch($poSel);
            $order_point = $sp['poSum'];
        } else {
            $order_point = ($od['od_receipt_price'] - $od['od_cancel_price'] - $od['od_refund_price']) / 100 * $default['de_point_percent'];
        }


        // $order_point = ($od['od_receipt_price'] - $od['od_cancel_price'] - $od['od_refund_price']) / 100 * $default['de_point_percent'];
        // insert_point($od['mb_id'], $order_point, '주문번호 ' . $od_id . ' 적립', '@order', $od['mb_id'], G5_TIME_YMD);
        insert_point($od['mb_id'], $order_point, '주문번호 ' . $od_id . ' 적립', '@order', $od['od_id'], G5_TIME_YMD);
        $outputs[] = date('Y-m-d H:i:s', time()) . sprintf(" : %s - %s", $od_id, $ct_status);
    } else if ($od['od_type'] == "R") {
        $ct_status = '리스중';

        // 재고에서 뺀다.
        $ct_sql = " select * from {$g5['g5_shop_cart_table']} where od_id = '$od_id' and  ct_status = '{$current_status}' ";
        $ct_result = sql_query($ct_sql);
        $now = G5_TIME_YMDHIS;

        for ($i = 0; $ct = sql_fetch_array($ct_result); $i++) {
            // 재고를 사용하지 않았다면
            $stock_use = $row['ct_stock_use'];
            if (!$row['ct_stock_use']) {
                $stock_use = 1;
                $sql = " update {$g5['g5_shop_item_option_table']}
                            set io_stock_qty = io_stock_qty - '{$ct['ct_qty']}'
                            where it_id = '{$ct['it_id']}'
                              and io_id = '{$ct['io_id']}'
                              and io_type = '{$ct['io_type']}' ";
                sql_query($sql);
            }

            // 히스토리에 남김
            // 히스토리에 남길때는 작업|아이디|시간|IP|그리고 나머지 자료
            $ct_history = "\n$ct_status|{$member['mb_id']}|$now|$REMOTE_ADDR";

            $sql = " update {$g5['g5_shop_cart_table']}
                    set ct_stock_use  = '$stock_use',
                        ct_status     = '$ct_status',
                        ct_history    = CONCAT(ifnull(ct_history,''),'$ct_history')
                where od_id = '$od_id'
                and ct_id  = '{$ct['ct_id']}' ";
            sql_query($sql);

            sql_query("update lt_shop_order_item set ct_status = '$ct_status' where od_id = '$od_id' and ct_id  = '{$ct['ct_id']}'");
        }

        $sql = " update {$g5['g5_shop_order_table']}
                    set od_status = '$ct_status'
                        ,rt_rental_startdate = '" . G5_TIME_YMD . "'
                    where od_id = '$od_id' ";
        sql_query($sql);
        $outputs[] = date('Y-m-d H:i:s', time()) . sprintf(" : %s - %s", $od_id, $ct_status);
    } else {
        if ($od['od_type'] == "L") $ct_status = '세탁완료';
        elseif ($od['od_type'] == "K") $ct_status = '보관완료';
        elseif ($od['od_type'] == "S") $ct_status = '수선완료';

        $ct = sql_fetch(" select * from {$g5['g5_shop_cart_table']} where od_id = '$od_id' and mb_id = '{$member['mb_id']}' ");

        // 히스토리에 남김
        // 히스토리에 남길때는 작업|아이디|시간|IP|그리고 나머지 자료
        $now = G5_TIME_YMDHIS;
        $ct_history = "\n$ct_status|{$member['mb_id']}|$now|$REMOTE_ADDR";

        $sql = " update {$g5['g5_shop_cart_table']}
                        set ct_status     = '$ct_status',
                            ct_history    = CONCAT(ct_history,'$ct_history')
                    where od_id = '$od_id'
                    and ct_id  = '{$ct['ct_id']}' ";
        sql_query($sql);

        if ($od['od_type'] == "L" || $od['od_type'] == "K") {
            sql_query("update lt_shop_order_item set ct_status = '', ct_free_laundry_use=ct_free_laundry_use+'{$ct['ct_free_laundry_use']}' where ct_id = '{$ct['buy_ct_id']}' and od_sub_id  = '{$ct['buy_od_sub_id']}'");
        } else {
            sql_query("update lt_shop_order_item set ct_status = '' where ct_id = '{$ct['buy_ct_id']}' and od_sub_id  = '{$ct['buy_od_sub_id']}'");
        }

        $sql = " update {$g5['g5_shop_order_table']}
                    set od_status = '$ct_status'
                    where od_id = '$od_id' ";
        sql_query($sql);
        $outputs[] = date('Y-m-d H:i:s', time()) . sprintf(" : %s - %s", $od_id, $ct_status);
    }
}
$outputs[] = date('Y-m-d H:i:s', time()) . " : 구매완료 동기화 끝";
print_raw($outputs);