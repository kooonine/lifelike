<?php
include_once('./_common.php');

if (!$is_member) {
    goto_url('/auth/login.php?url=' . urlencode('/member/point.php'));
}

// 잔여포인트
$expire_limit = date("Ymd", strtotime("+ 30 day"));
$sql_point_expire = "SELECT (SUM(po_point)-SUM(po_use_point)) AS point_expire FROM lt_point WHERE mb_id='{$member['mb_id']}' AND po_expired=0 AND po_expire_date <= '{$expire_limit}'";
$db_point_expire = sql_fetch($sql_point_expire);
$point_expire = $db_point_expire['point_expire'] ? $db_point_expire['point_expire'] : 0;

$sql_point_income = "SELECT SUM(ct_point) AS point_income FROM lt_shop_cart WHERE mb_id='{$member['mb_id']}' AND ct_status IN ('주문','결제완료','상품준비중','배송중','배송완료')";
$db_point_income = sql_fetch($sql_point_income);
$point_income = $db_point_income['point_income'] > 0 ? $db_point_income['point_income'] : 0;


$sql_common =  " FROM lt_point WHERE mb_id='{$member['mb_id']}'";

$startdate = empty($startdate) ? date("Y-m-d", strtotime("-3 month")) : $startdate;
$enddate = empty($enddate) ? date("Y-m-d") : $enddate;

if (!empty($startdate)) $sql_common .= " AND po_datetime >= '{$startdate}'";
if (!empty($enddate)) $sql_common .= " AND po_datetime <= '{$enddate} 23:59:59'";

$sql_point_count = "SELECT COUNT(*) CNT" . $sql_common;
$db_point_count = sql_fetch($sql_point_count);

$perpage = 10;
if ($page > 1) $fr = ($page - 1) * $perpage . ",";
$total_count = $db_point_count['CNT'];
$total_page  = ceil($total_count / $perpage);

$sql_point = "SELECT mb_id,po_datetime,po_content,po_point,po_rel_table,po_rel_id,po_expire_date " . $sql_common . " ORDER BY po_datetime DESC LIMIT {$fr}{$perpage}";
$db_point = sql_query($sql_point);

$qstr = "";
$paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');

if ($ajax == "true") {
    $contents = include_once(G5_VIEW_PATH . "/member.point.list.item.php");
    die($contents);
}
$contents = include_once(G5_VIEW_PATH . "/member.point.list.php");

include_once G5_LAYOUT_PATH . "/layout.php";
