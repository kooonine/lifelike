<?php
include_once('./_common.php');
require_once(G5_SHOP_PATH . '/settle_lg.inc.php');

if (!$is_member) {
    goto_url('/auth/login.php?url=' . urlencode('/member/order.php'));
}

$od_id = isset($od_id) ? preg_replace('/[^A-Za-z0-9\-_]/', '', strip_tags($od_id)) : 0;
$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);
// dd($token);

$od_status_button_set = array(
    '주문' => array(
        "cancel-order" => "주문취소",
        "change-invoice" => "배송지변경"
    ),
    '결제완료' => array(
        "cancel-order" => "주문취소",
        "change-invoice" => "배송지변경"
    ),
    '배송중' => array(
        "order-invoice" => "배송조회",
        // "order-change" => "교환신청",
        "order-return" => "반품신청"
    ),
    '배송완료' => array(
        "order-invoice" => "배송조회",
        // "order-change" => "교환신청",
        "order-return" => "반품신청",
        "order-confirm" => "구매확정"
    ),
    '구매완료' => array(
        "order-invoice" => "배송조회"
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
        "cancel-order" => "주문취소",
        "change-invoice" => "배송지변경"
    ),
    '결제완료' => array(
        "cancel-order" => "주문취소",
        "change-invoice" => "배송지변경"
    ),
    '배송중' => array(
        // "order-change" => "교환신청",
        "order-return" => "반품신청"
    ),
    '배송완료' => array(
        "order-confirm" => "구매확정",
        // "order-change" => "교환신청",
        "order-return" => "반품신청",
    ),
    '교환신청' => array(
        "cancel-change" => "교환철회"
    ),
    '반품신청' => array(
        "cancel-return" => "반품철회"
    )
);

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

    $page_prefix = "취소";
    $cancel_point_title = "적립포인트";
    switch ($action) {
        case "return":
            $page_prefix = "반품";
            $cancel_point_title = "적립포인트";
            break;
        case "change":
            $page_prefix = "교환";
            break;
    }
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

    $contents = include_once(G5_VIEW_PATH . "/member.order.cancel.view.php");
} else {
    $startdate = empty($startdate) ? date("Y-m-d", strtotime("-3 month")) : $startdate;
    $enddate = empty($enddate) ? date("Y-m-d") : $enddate;

    $is_claim = false;

    $sql_common = " FROM lt_shop_order_history AS sh LEFT JOIN {$g5['g5_shop_order_table']} AS o ON o.od_id=sh.od_id LEFT JOIN {$g5['g5_shop_cart_table']} AS c ON c.ct_id=sh.ct_id  WHERE o.mb_id = '{$member['mb_id']}'";
    if (isset($is_claim) && $is_claim != "") $sql_common .= " AND sh.ct_status_claim IN ('주문취소','교환','반품','철회','해지') ";
    if (isset($is_care) && $is_care != "") $sql_common .= " AND ((o.od_type = 'R' AND o.od_status = '리스중') OR o.od_type IN ('L','K','S')) ";
    if (isset($filter) && $filter != "") $sql_common .= " AND sh.ct_status_claim = '{$filter}' ";
    if (isset($od_type) && $od_type != "") $sql_common .= " AND o.od_type = '{$od_type}' ";
    $sql_common .= " AND (c.ct_status LIKE '%반품%' OR c.ct_status LIKE '%취소%') ";
    if (!empty($startdate)) $sql_common .= " AND sh_time >= '{$startdate}'";
    if (!empty($enddate)) $sql_common .= " AND sh_time <= '{$enddate} 23:59:59'";

    $sql_claim_count = "SELECT COUNT(*) AS CNT, CONCAT(sh.od_id,'-',sh.ct_id) AS sh_od_ct " . $sql_common . " GROUP BY sh_od_ct";
    $cnt_claim = sql_query($sql_claim_count);

    // $cnt_order = sql_fetch($sql_cnt_order);
    $perpage = 10;

    if ($page > 1) $fr = ($page - 1) * $perpage . ",";
    $total_count = $cnt_claim->num_rows;
    $total_page  = ceil($total_count / $perpage);

    $sql_order_claim = "SELECT *, (o.od_cart_coupon + o.od_coupon + o.od_send_coupon) AS couponprice, MIN(sh.sh_time) AS first_claim_datetime, CONCAT(sh.od_id,'-',sh.ct_id) AS sh_od_ct" . $sql_common . " GROUP BY sh_od_ct ORDER BY o.od_status_claim_date DESC LIMIT {$fr}{$perpage}";

    $db_order_claim = sql_query($sql_order_claim);

    $qstr = "";
    $paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');

    if ($ajax == true) {
        $contents = include_once(G5_VIEW_PATH . "/member.order.cancel.list.item.php");
        die($contents);
    }

    $contents = include_once(G5_VIEW_PATH . "/member.order.cancel.list.php");

    /*
    $od_claim_status = array(
        '주문취소',
        '반품요청',
        '반품수거중',
        '반품수거',
        '반품완료',
        '교환요청',
        '교환수거중',
        '교환완료'
    );
    $is_claim = false;
    
    $sql_common = " FROM lt_shop_order_history AS sh LEFT JOIN {$g5['g5_shop_order_table']} AS o ON o.od_id=sh.od_id WHERE o.mb_id = '{$member['mb_id']}'";
    if (isset($is_claim) && $is_claim != "") $sql_common .= " AND sh.ct_status_claim IN ('주문취소','교환','반품','철회','해지') ";
    if (isset($is_care) && $is_care != "") $sql_common .= " AND ((o.od_type = 'R' AND o.od_status = '리스중') OR o.od_type IN ('L','K','S')) ";
    if (isset($filter) && $filter != "") $sql_common .= " AND sh.ct_status_claim = '{$filter}' ";
    if (isset($od_type) && $od_type != "") $sql_common .= " AND o.od_type = '{$od_type}' ";
    
    $sql_claim_count = "SELECT COUNT(*) AS CNT, CONCAT(sh.od_id,'-',sh.ct_id) AS sh_od_ct " . $sql_common . " GROUP BY sh_od_ct";
    $cnt_claim = sql_query($sql_claim_count);
    
    $perpage = 10;
    if ($page > 1) $fr = ($page - 1) * $perpage . ",";
    $total_count = $cnt_claim->num_rows;
    $total_page  = ceil($total_count / $perpage);
    
    $sql_order_claim = "SELECT *, (o.od_cart_coupon + o.od_coupon + o.od_send_coupon) AS couponprice, MIN(sh.sh_time) AS first_claim_datetime, CONCAT(sh.od_id,'-',sh.ct_id) AS sh_od_ct" . $sql_common . " GROUP BY sh_od_ct ORDER BY o.od_status_claim_date DESC LIMIT {$fr}{$perpage}";
    $db_order_claim = sql_query($sql_order_claim);
    
    $qstr = "";
    $paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');
  */
}

include_once G5_LAYOUT_PATH . "/layout.php";
