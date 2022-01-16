<?php
include_once('./_common.php');
require_once(G5_SHOP_PATH . '/settle_lg.inc.php');

if (!$is_member) {
    goto_url('/auth/login.php?url=' . urlencode('/member/order.php'));
}

$od_id = isset($od_id) ? preg_replace('/[^A-Za-z0-9\-_]/', '', strip_tags($od_id)) : 0;

if ($step == 2) {
    if (empty($od_id) || empty($ct_id)) {
        alert("제품을 선택해주세요.", "", true, true);
    }

    $sql_order = "SELECT * FROM {$g5['g5_shop_order_table']} WHERE od_id = '$od_id' AND mb_id = '{$member['mb_id']}'";
    $od = sql_fetch($sql_order);
    if (!$od['od_id']) {
        alert("조회하실 주문서가 없습니다.", G5_URL);
    }

    $sql_order_cart = "SELECT a.*, ((b.its_price + a.io_price) * a.ct_qty) AS before_price
						FROM lt_shop_cart AS a
						LEFT JOIN lt_shop_item_sub AS b ON a.it_id = b.it_id ANd a.its_no = b.its_no
						LEFT JOIN lt_shop_item c ON ( a.it_id = c.it_id )
						WHERE a.od_id = '{$od_id}'
						AND a.ct_status IN ('주문', '결제완료', '계약등록', '상품준비중', '세탁신청', '보관신청', '수선신청')
						ORDER BY c.ca_id3 DESC, a.it_sc_type DESC, a.it_id ";
    $db_order_cart = sql_query($sql_order_cart);

    $partable = (($od['od_cart_coupon'] + $od['od_coupon'] + $od['od_send_coupon'] + $od['od_receipt_point']) > 0) ? false : true;

    if ($partable == false && $db_order_cart->num_rows > count($ct_id)) {
        alert("쿠폰 또는 포인트 결제가 포함된 주문은 전체 취소만 가능합니다.", "", true, true);
    } else {
        // 주문 금액 정보
        // 총계 = 주문상품금액합계 + 배송비 - 제품 할인 - 결제할인 - 배송비할인
        $tot_price = $od['od_cart_price'] + $od['od_send_cost']
            - $od['od_cart_coupon'] - $od['od_coupon'] - $od['od_send_coupon']
            - $od['od_cancel_price'];
        $sale_price = $od['od_cart_coupon'] + $od['od_coupon'] + $od['od_send_coupon'];
        $receipt_price = $od['od_receipt_price'] + $od['od_receipt_point'];
        $cancel_price = $od['od_cancel_price'];

        $misu = true;
        $misu_price = $tot_price - $receipt_price - $cancel_price;
        if ($misu_price == 0 && ($od['od_cart_price'] > $od['od_cancel_price'])) {
            $misu = false; // 미수금 없음
        } else {
            $wanbul = display_price($receipt_price);
        }

        // 결제정보처리
        $od_receipt_price = display_price($od['od_receipt_price']);

        $app_no_subj = '';
        $disp_bank = true;
        $disp_receipt = false;
        $easy_pay_name = '';
        if ($od['od_settle_case'] == '신용카드' || $od['od_settle_case'] == 'KAKAOPAY' || is_inicis_order_pay($od['od_settle_case'])) {
            $app_no_subj = '승인번호';
            $app_no = $od['od_app_no'];
            $disp_bank = false;
            $disp_receipt = true;
        } else if ($od['od_settle_case'] == '간편결제') {
            $app_no_subj = '승인번호';
            $app_no = $od['od_app_no'];
            $disp_bank = false;
            switch ($od['od_pg']) {
                case 'lg':
                    $easy_pay_name = 'PAYNOW';
                    break;
                case 'inicis':
                    $easy_pay_name = 'KPAY';
                    break;
                case 'kcp':
                    $easy_pay_name = 'PAYCO';
                    break;
                default:
                    break;
            }
        } else if ($od['od_settle_case'] == '휴대전화') {
            $app_no_subj = '휴대전화번호';
            $app_no = $od['od_bank_account'];
            $disp_bank = false;
            $disp_receipt = true;
        } else if ($od['od_settle_case'] == '가상계좌' || $od['od_settle_case'] == '계좌이체') {
            $app_no_subj = '거래번호';
            $app_no = $od['od_tno'];
        }

        $od_last_price = $od['od_receipt_price'] - $od['od_refund_price'];
        $tot_sell_price = 0;
        $tot_send_cost = 0;
        $tot_point = 0;

        $target = array();
        while (false != ($order_cart = sql_fetch_array($db_order_cart))) {
            if (in_array($order_cart['ct_id'], $ct_id)) {
                $opt_price = $order_cart['ct_price'] + $order_cart['io_price'];
                $sell_price = $opt_price * $order_cart['ct_qty'];
                $point = $order_cart['ct_point'] * $order_cart['ct_qty'];
                $tot_sell_price += $sell_price;
                $tot_point += $point;

                $opt_rental_price = $order_cart['ct_rental_price'] + $order_cart['io_price'];
                $sell_rental_price = $opt_rental_price * $order_cart['ct_qty'];

                $ct_send_cost = $order_cart['ct_send_cost'];
                if ($od['od_type'] == "O") {
                    $ct_send_cost = get_item_sendcost($order_cart['it_id'], $sell_price, $order_cart['ct_qty'], $od_id, $order_cart['before_price']);
                }
                $tot_send_cost += $tot_send_cost;

                $tmp_target = array(
                    'ct_id' => $order_cart['ct_id'],
                    'it_name' => $order_cart['it_name'],
                    'stotal' => $sell_price,
                    'ct_send_cost' => $ct_send_cost,
                );

                $target[] = $tmp_target;
            }
        }

        $token = md5(uniqid(rand(), true));
        set_session("ss_token", $token);

        $contents = include_once(G5_VIEW_PATH . "/member.order.cancel.confirm.php");
    }
} else {
    if (!empty($od_id)) {
        $sql_order = "SELECT * FROM {$g5['g5_shop_order_table']} WHERE mb_id = '{$member['mb_id']}' AND od_id='{$od_id}'";
        $od = sql_fetch($sql_order);

        if (!$od['od_id'] || !$is_member) {
            alert("조회하실 주문서가 없습니다.", "/");
        }

        /*
			총계 = 주문제품금액합계
			+ 배송비(기본 + 추가)
			- 쿠폰 금액(쿠폰 사용금액 //제품쿠폰, 주문쿠폰, 배송비쿠폰)
			- 취소금액
			 */
        $tot_price = $od['od_cart_price']
            + $od['od_send_cost']
            - $od['od_cart_coupon'] - $od['od_coupon'] - $od['od_send_coupon']
            - $od['od_cancel_price'];

        //쿠폰 할인 금액(쿠폰 사용금액 //제품쿠폰, 주문쿠폰, 배송비쿠폰)
        $sale_price = $od['od_cart_coupon'] + $od['od_coupon'] + $od['od_send_coupon'];

        //총 결제금액(결제금액 + 적립금결제)
        $receipt_price = $od['od_receipt_price']
            + $od['od_receipt_point'];
        //주문취소금액
        $cancel_price = $od['od_cancel_price'];

        //미수금액 (총금액 - 결제금액 - 취소금액) : 수선-추가금 결제요청시 발생함.
        $misu = true;
        $misu_price = $tot_price - $receipt_price - $cancel_price;

        if ($misu_price == 0 && ($od['od_cart_price'] > $od['od_cancel_price'])) {
            $wanbul = " (완불)";
            $misu = false;
            // 미수금 없음
        } else {
            $wanbul = display_price($receipt_price);
        }

        // 결제정보처리
        if ($od['od_receipt_price'] > 0 || $od['od_type'] != "S") {
            $od_receipt_price = display_price($od['od_receipt_price']);
        } else {
            //수선일떄 발생.
            $od_receipt_price = '후불';
        }

        $app_no_subj = '';
        $disp_bank = false;
        $disp_receipt = false;
        $easy_pay_name = '';
        if ($od['od_settle_case'] == '신용카드' || $od['od_settle_case'] == 'KAKAOPAY' || is_inicis_order_pay($od['od_settle_case'])) {
            $app_no_subj = '승인번호';
            $app_no = $od['od_app_no'];
            $disp_bank = false;
            $disp_receipt = true;
        } else if ($od['od_settle_case'] == '간편결제') {
            $app_no_subj = '승인번호';
            $app_no = $od['od_app_no'];
            $disp_bank = false;
            switch ($od['od_pg']) {
                case 'lg':
                    $easy_pay_name = 'PAYNOW';
                    break;
                case 'inicis':
                    $easy_pay_name = 'KPAY';
                    break;
                case 'kcp':
                    $easy_pay_name = 'PAYCO';
                    break;
                default:
                    break;
            }
        } else if ($od['od_settle_case'] == '휴대전화') {
            $app_no_subj = '휴대전화번호';
            $app_no = $od['od_bank_account'];
            $disp_bank = false;
            $disp_receipt = true;
        } else if ($od['od_settle_case'] == '가상계좌' || $od['od_settle_case'] == '계좌이체') {
            $app_no_subj = '거래번호';
            $app_no = $od['od_tno'];
        }

        $od_status_set = array('결제 대기', '결제 완료', '상품준비중', '배송중', '배송 완료', '구매 확정');
        $od_status_set_count = array(
            '주문' => 0,
            '결제완료' => 0,
            '상품준비중' => 0,
            '배송중' => 0,
            '배송완료' => 0,
            '구매완료' => 0
        );

        $display_delivery_set = array('배송중', '배송완료', '구매완료', '리스중');

        $contents = include_once(G5_VIEW_PATH . "/member.order.cancel.php");
    } else {
        $sql_cnt_order = "SELECT COUNT(*) AS CNT FROM {$g5['g5_shop_order_table']} WHERE mb_id='{$member['mb_id']}' AND (od_type='O' OR od_type='R')";
        $cnt_order = sql_fetch($sql_cnt_order);

        $perpage = 10;
        if ($page > 1) $fr = ($page - 1) * $perpage . ",";
        $total_count = $cnt_order['CNT'];
        $total_page  = ceil($total_count / $perpage);

        $sql_order = "SELECT * FROM {$g5['g5_shop_order_table']} WHERE mb_id = '{$member['mb_id']}' AND (od_type='O' OR od_type='R') GROUP BY od_id ORDER BY od_time DESC LIMIT {$fr}{$perpage}";
        $db_order = sql_query($sql_order);

        $qstr = "";
        // $qstr .= 'pick=' . $pick;
        // $qstr .= '&amp;filter=' . $filter;
        $paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');

        $contents = include_once(G5_VIEW_PATH . "/member.order.cancel.php");
    }
}

include_once G5_LAYOUT_PATH . "/layout.php";
