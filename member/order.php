<?php
include_once('./_common.php');
require_once(G5_SHOP_PATH . '/settle_lg.inc.php');

if (!$is_member) {
    $back_url = '/member/order.php';
    if (!empty($od_id)) $back_url .= '?od_id=' . $od_id;
    goto_url('/auth/login.php?url=' . urlencode($back_url));
}

$od_id = isset($od_id) ? preg_replace('/[^A-Za-z0-9\-_]/', '', strip_tags($od_id)) : 0;

$od_status_button_set = array(
    '주문' => array(
        "cancel" => "주문취소",
        // "change-invoice" => "배송지변경"
    ),
    '결제완료' => array(
        "cancel" => "주문취소",
        // "change-invoice" => "배송지변경"
    ),
    '배송중' => array(
        "invoice" => "배송조회",
        // "change" => "교환신청",
        "return" => "반품신청"
    ),
    '배송완료' => array(
        "invoice" => "배송조회",
        // "change" => "교환신청",
        "return" => "반품신청",
        "confirm" => "구매확정"
    ),
    '구매완료' => array(
        "invoice" => "배송조회"
    ),
    '교환신청' => array(
        "cancel-change" => "교환철회"
    ),
    '반품신청' => array(
        "cancel-return" => "반품철회"
    )
);

$od_status_button_set_mobile = array(
    '주문' => array(
        "cancel" => "주문취소",
        // "change-invoice" => "배송지변경"
    ),
    '결제완료' => array(
        "cancel" => "주문취소",
        // "change-invoice" => "배송지변경"
    ),
    '배송중' => array(
        // "change" => "교환신청",
        "return" => "반품신청"
    ),
    '배송완료' => array(
        "confirm" => "구매확정",
        // "change" => "교환신청",
        "return" => "반품신청",
    ),
    '교환신청' => array(
        "cancel-change" => "교환철회"
    ),
    '반품신청' => array(
        "cancel-return" => "반품철회"
    ),
);

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


    $contents = include_once(G5_VIEW_PATH . "/member.order.view.php");
} else {
    $startdate = empty($startdate) ? date("Y-m-d", strtotime("-3 month")) : $startdate;
    $enddate = empty($enddate) ? date("Y-m-d") : $enddate;

    $sql_cnt_order = "SELECT COUNT(*) AS CNT FROM {$g5['g5_shop_order_table']} WHERE mb_id='{$member['mb_id']}' AND (od_type='O' OR od_type='R')";
    if (!empty($startdate)) $sql_cnt_order .= " AND od_time >= '{$startdate}'";
    if (!empty($enddate)) $sql_cnt_order .= " AND od_time <= '{$enddate} 23:59:59'";

    $cnt_order = sql_fetch($sql_cnt_order);

    $perpage = 10;
    if ($page > 1) $fr = ($page - 1) * $perpage . ",";
    $total_count = $cnt_order['CNT'];
    $total_page  = ceil($total_count / $perpage);

    $sql_order = "SELECT * FROM {$g5['g5_shop_order_table']} WHERE mb_id = '{$member['mb_id']}' AND (od_type='O' OR od_type='R')";
    if (!empty($startdate)) $sql_order .= " AND od_time >= '{$startdate}'";
    if (!empty($enddate)) $sql_order .= " AND od_time <= '{$enddate} 23:59:59'";
    $sql_order .= " GROUP BY od_id ORDER BY od_time DESC LIMIT {$fr}{$perpage}";
    $db_order = sql_query($sql_order);

    $qstr = "";
    // $qstr .= 'pick=' . $pick;
    // $qstr .= '&amp;filter=' . $filter;
    $paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');

    if ($ajax == true) {
        $contents = include_once(G5_VIEW_PATH . "/member.order.list.item.php");
        die($contents);
    }

    $contents = include_once(G5_VIEW_PATH . "/member.order.list.php");
}

include_once G5_LAYOUT_PATH . "/layout.php";
