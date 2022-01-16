<?php
include_once('./_common.php');

if (!$is_member) {
    goto_url('/auth/login.php?url=' . urlencode('/member/coupon.php'));
}


// 쿠폰
$sql_count_coupon = "SELECT COUNT(*) AS CNT, (IF(cp.cz_id > 0, CONCAT('Z',cp.cz_id), CONCAT('M',cp.cm_no))) AS CID FROM {$g5['g5_shop_coupon_table']} AS cp LEFT JOIN {$g5['g5_shop_coupon_log_table']} AS cl ON cp.cp_id=cl.cp_id WHERE cl.cl_id IS NULL AND cp.mb_id IN ( '{$member['mb_id']}', '전체회원' ) AND cp_start <= '" . G5_TIME_YMD . "' AND cp_end >= '" . G5_TIME_YMD . "' GROUP BY CID";
$db_count_coupon = sql_query($sql_count_coupon);

$perpage = 10;
if ($page > 1) $fr = ($page - 1) * $perpage . ",";
$total_count = $db_count_coupon->num_rows;
$total_page  = ceil($total_count / $perpage);

$sql_coupon = "SELECT cp.*, COUNT(*) AS CNT_COUPON, SUM(IF(cp.od_id != 0, 1, 0)) AS CNT_USED, (IF(cp.cz_id > 0, CONCAT('Z',cp.cz_id), CONCAT('M',cp.cm_no))) AS CID FROM {$g5['g5_shop_coupon_table']} AS cp LEFT JOIN {$g5['g5_shop_coupon_log_table']} AS cl ON cp.cp_id=cl.cp_id WHERE cp.mb_id IN ( '{$member['mb_id']}', '전체회원' ) AND cp_start <= '" . G5_TIME_YMD . "' AND cp_end >= '" . G5_TIME_YMD . "' GROUP BY CID ORDER BY cp_datetime DESC LIMIT {$fr}{$perpage}";
$db_coupon = sql_query($sql_coupon);

$qstr = "";
$paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');

$startdate = empty($startdate) ? date("Y-m-d", strtotime("-3 month")) : $startdate;
$enddate = empty($enddate) ? date("Y-m-d") : $enddate;

$contents = include_once(G5_VIEW_PATH . "/member.coupon.list.php");

include_once G5_LAYOUT_PATH . "/layout.php";
