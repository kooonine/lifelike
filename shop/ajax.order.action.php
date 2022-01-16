<?php
include_once('./_common.php');

$result = array(
    "result" => false,
    "data" => array(),
    "error" => ""
);

$od = sql_fetch(" select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' and mb_id = '{$member['mb_id']}' ");

if (!$od['od_id']) {
    $result['error'] = "해당 주문번호의 주문을 찾을 수 없습니다.";

    die(json_encode($result));
}

if ($act) {
    switch ($act) {
        case 'delivery':
            $od_b_zip   = preg_replace('/[^0-9]/', '', $od_b_zip);
            $od_b_zip1  = substr($od_b_zip, 0, 3);
            $od_b_zip2  = substr($od_b_zip, 3);

            $od_b_name        = clean_xss_tags($od_b_name);
            $od_b_tel         = clean_xss_tags($od_b_tel);
            $od_b_hp          = clean_xss_tags($od_b_hp);
            $od_b_addr1       = clean_xss_tags($od_b_addr1);
            $od_b_addr2       = clean_xss_tags($od_b_addr2);
            $od_b_addr3       = clean_xss_tags($od_b_addr3);
            $od_b_addr_jibeon = preg_match("/^(N|R)$/", $od_b_addr_jibeon) ? $od_b_addr_jibeon : '';

            $od_memo          = clean_xss_tags($od_memo);
            $od_memo = nl2br(htmlspecialchars2(stripslashes($od_memo)));

            $sql = " update {$g5['g5_shop_order_table']}
            set od_b_name = '$od_b_name',
                od_b_hp = '$od_b_hp',
                od_b_tel = '$od_b_tel',
                od_b_zip1 = '$od_b_zip1',
                od_b_zip2 = '$od_b_zip2',
                od_b_addr1 = '$od_b_addr1',
                od_b_addr2 = '$od_b_addr2',
                od_b_addr3 = '$od_b_addr3',
                od_b_addr_jibeon = '$od_b_addr_jibeon',
                od_memo = '$od_memo'
            where od_id = '$od_id' ";
            sql_query($sql);

            $result['result'] = true;

            break;
        case 'confirm':
            if ($od['od_type'] == "O") {
                $ct_status = '구매확정';
                
                // 재고에서 뺀다.
                $ct_sql = " select * from {$g5['g5_shop_cart_table']} where od_id = '$od_id' AND ct_id ='$ct_id' and ct_status = '배송완료' ";
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

                    $sql = " update {$g5['g5_shop_order_table']}
                    set od_status = '$ct_status'
                    where od_id = '$od_id' ";
                    sql_query($sql);
                }
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
                // insert_point($member['mb_id'], $order_point, '주문번호 ' . $od_id . ' 적립', '@order', $member['mb_id'], G5_TIME_YMD);
                insert_point($member['mb_id'], $order_point, '주문번호 ' . $od_id . ' 적립', '@order', $od_id, G5_TIME_YMD);

                $result['result'] = true;
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
            }

            break;
        case '교환철회':
            $ct_status = '배송완료';
            sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '$ct_status', ct_status_claim = '' where od_id = '$od_id' and ct_status = '교환요청' ");

            $sql = " insert into lt_shop_order_history
                            (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim)
                         values
                            ('$od_id', 1, '주문자 본인 교환철회', '" . G5_TIME_YMDHIS . "', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','교환철회'); ";
            sql_query($sql);

            $sql = " update {$g5['g5_shop_order_table']}
                    set  od_status = '$ct_status'
                        ,od_status_claim   = ''
                        , od_send_cost2=''
                    where od_id = '$od_id' ";
            sql_query($sql);

            break;
        case '반품철회':
            $ct_status = '배송완료';
            sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '$ct_status', ct_status_claim = '' where od_id = '$od_id' and ct_status = '반품요청' ");

            $sql = " insert into lt_shop_order_history
                            (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim)
                         values
                            ('$od_id', 1, '주문자 본인 반품철회', '" . G5_TIME_YMDHIS . "', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','반품철회'); ";
            sql_query($sql);

            $sql = " update {$g5['g5_shop_order_table']}
                    set  od_status = '$ct_status'
                        ,od_status_claim   = ''
                        , od_send_cost2=''
                    where od_id = '$od_id' ";
            sql_query($sql);

            break;
        case '철회취소':
            $ct_status = '배송완료';
            sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '$ct_status', ct_status_claim = '' where od_id = '$od_id' and ct_status = '철회요청' ");

            $sql = " insert into lt_shop_order_history
                            (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim)
                         values
                            ('$od_id', 1, '주문자 본인 철회취소', '" . G5_TIME_YMDHIS . "', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','철회취소'); ";
            sql_query($sql);

            $sql = " update {$g5['g5_shop_order_table']}
                    set  od_status = '$ct_status'
                        ,od_status_claim   = ''
                        , od_send_cost2=''
                    where od_id = '$od_id' ";
            sql_query($sql);

            break;
        case '해지취소':
            $ct_status = '리스중';
            sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '$ct_status', ct_status_claim = '' where od_id = '$od_id' and ct_status in ('해지요청','해지결제요청') ");

            $sql = " insert into lt_shop_order_history
                            (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim)
                         values
                            ('$od_id', 1, '주문자 본인 해지취소', '" . G5_TIME_YMDHIS . "', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','해지취소'); ";
            sql_query($sql);


            $sql = " update {$g5['g5_shop_order_table']}
                    set  od_status = '$ct_status'
                        ,od_status_claim   = ''
                        ,od_status_claim_date = null
                        ,od_hope_date = ''
                        ,od_contractout = ''
                        ,od_contractout_cust_file = ''
                        ,od_penalty = ''
                    where od_id = '$od_id' ";
            sql_query($sql);

            break;
        case '리스시작하기':

            $ct_status = '리스중';

            // 재고에서 뺀다.
            $ct_sql = " select * from {$g5['g5_shop_cart_table']} where od_id = '$od_id' ";
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
                        ct_history    = CONCAT(ifnull(ct_history,''),'$ct_history')
                where od_id = '$od_id'
                and ct_id  = '{$ct['ct_id']}' ";
                sql_query($sql);

                sql_query("update lt_shop_order_item set ct_status = '$ct_status' where od_id = '$od_id' and ct_id  = '{$ct['ct_id']}'");
            }
            //리스계약서 PDF 파일 생성

            $sql = " update {$g5['g5_shop_order_table']}
                    set od_status = '$ct_status'
                        ,rt_rental_startdate = '" . G5_TIME_YMD . "'
                    where od_id = '$od_id' ";
            sql_query($sql);

            alert(G5_TIME_YMD . "일 기준으로 리스가 시작 되었습니다.", G5_SHOP_URL . "/orderinquiryview.php?od_id=$od_id&amp;uid=$uid", false);

            break;
    }
}

die(json_encode($result));
