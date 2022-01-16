<?php
include_once('./_common.php');
require_once(G5_SHOP_PATH . '/settle_lg.inc.php');

if (!$is_member) {
    goto_url('/auth/login.php?url=' . urlencode('/member/order.php'));
}

$od_id = isset($od_id) ? preg_replace('/[^A-Za-z0-9\-_]/', '', strip_tags($od_id)) : 0;

if (!empty($od_id)) {
    // 배송지 변경 처리
    if (!empty($_POST['od_b_name'])) {
        $od_memo = $_POST['od_memo'] == 'user' ? $_POST['od_memo_user'] : $_POST['od_memo'];
        $od_b_zip1 = substr($_POST['od_b_zip'], 0, 3);
        $od_b_zip2 = substr($_POST['od_b_zip'], 3, 3);
        $sql_update_invoice_info = "UPDATE lt_shop_order SET
        od_b_name='{$_POST['od_b_name']}',
        od_b_hp='{$_POST['od_b_hp_1']}{$_POST['od_b_hp_2']}',
        od_b_zip1='{$od_b_zip1}',
        od_b_zip2='{$od_b_zip2}',
        od_b_addr1='{$_POST['od_b_addr1']}',
        od_b_addr2='{$_POST['od_b_addr2']}'";

        if (empty($_POST['od_b_mobile'])) {
            $sql_update_invoice_info .= ",od_memo='{$od_memo}'";
        }
        $sql_update_invoice_info .= " WHERE od_id='{$od_id}'";

        sql_query($sql_update_invoice_info);
        goto_url("/member/order.php?od_id=" . $od_id);
    }

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
    // $tot_price = $od['od_cart_price']
    //     + $od['od_send_cost']
    //     - $od['od_cart_coupon'] - $od['od_coupon'] - $od['od_send_coupon']
    //     - $od['od_cancel_price'] - $od['od_receipt_point'];
     $tot_price = $od['od_cart_price']
        + $od['od_send_cost']
        - $od['od_cart_coupon'] - $od['od_coupon']
        - $od['od_cancel_price'] - $od['od_receipt_point'];

    //쿠폰 할인 금액(쿠폰 사용금액 //제품쿠폰, 주문쿠폰, 배송비쿠폰)
    // $sale_price = $od['od_cart_coupon'] + $od['od_coupon'] + $od['od_send_coupon'];
    $sale_price = $od['od_cart_coupon'] + $od['od_coupon'];

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

    $sql = "SELECT a.ct_id,
		a.it_id,
		a.it_name,
		a.ct_price,
		a.ct_point,
		a.ct_qty,
		a.ct_status,
		a.ct_send_cost,
		a.it_sc_type,
		b.it_brand,
		b.ca_id,
		b.ca_id2,
		b.ca_id3,
		b.it_notax,
        b.it_point_type,
        b.it_point,
		c.io_hoching,
		(s.its_price + a.io_price) AS before_price,
        if(ct_status IN ( '결제완료', '상품준비중', '배송중', '배송완료', '구매완료' ), 0, 1) as ct_status_order
		FROM {$g5['g5_shop_cart_table']} a LEFT JOIN {$g5['g5_shop_item_table']} b ON ( a.it_id = b.it_id )
		LEFT JOIN {$g5['g5_shop_item_option_table']} AS c ON ( a.it_id=c.it_id AND a.io_sapcode_color_gz = c.io_sapcode_color_gz )
		INNER JOIN lt_shop_item_sub AS s ON a.it_id = s.it_id AND a.its_no = s.its_no
		WHERE a.od_id = '{$od['od_id']}'";
    $sql .= " GROUP BY a.it_id ";
    $sql .= " ORDER BY it_id, it_sc_type, ct_status_order";
    $result = sql_query($sql);

    $order_items = sql_query($sql);
    $tot_rows = sql_num_rows($order_items);
    $rowspan = 0;
    $rowspanCnt = 0;
    $total_send_cost = (int) $od['od_send_cost'];
    $tot_before_price = 0;
    $tot_sell_price = 0;

    $contents = include_once(G5_VIEW_PATH . "/member.order.complate.php");
} else {
    alert("조회하실 주문서가 없습니다.", "/");
}

include_once G5_LAYOUT_PATH . "/layout.php";
