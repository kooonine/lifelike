<?php
include_once('./_common.php');
require_once(G5_SHOP_PATH . '/settle_lg.inc.php');

if (!$is_member) {
    goto_url('/auth/login.php?url=' . urlencode('/member/cancel.php'));
}

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

$contents = include_once(G5_VIEW_PATH . "/member.claim.list.php");

include_once G5_LAYOUT_PATH . "/layout.php";
