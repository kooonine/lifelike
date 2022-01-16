<?php
include_once('./_common.php');
require_once(G5_SHOP_PATH . '/settle_lg.inc.php');

if (!$is_member) {
    goto_url('/auth/login.php?url=' . urlencode('/member/review.php'));
}


// 작성가능 주문
$sql_cnt_order = "SELECT COUNT(*) AS CNT FROM lt_shop_order_item WHERE mb_id='{$member['mb_id']}' AND ct_status IN ('구매완료','배송완료','구매확정','결제완료')";
$sql_cnt_order = "SELECT COUNT(*) AS CNT
              FROM {$g5['g5_shop_cart_table']} AS ct
              JOIN {$g5['g5_shop_item_table']} AS it ON ct.it_id=it.it_id
              LEFT JOIN {$g5['g5_shop_item_use_table']} AS its ON ct.it_id=its.it_id AND ct.ct_id=its.ct_id
              WHERE ct.mb_id='{$member['mb_id']}' AND ct_status IN ('구매완료','배송완료','구매확정') AND its.is_id IS NULL";
$cnt_order = sql_fetch($sql_cnt_order);

$perpage = 5;
if ($page > 1) $fr = ($page - 1) * $perpage . ",";
$total_count = $cnt_order['CNT'];
$total_page  = ceil($total_count / $perpage);
$sql_order = "SELECT od_id,it_id,it_name,ct_option,ct_id FROM lt_shop_order_item WHERE mb_id='{$member['mb_id']}' AND ct_status IN ('구매완료','배송완료','구매확정') GROUP BY ct_id ORDER BY ct_time DESC LIMIT {$fr}{$perpage}";
$sql_order = "SELECT ct.*, it.it_brand, it.it_price AS org_price, it.it_discount_price AS discount_price
              FROM {$g5['g5_shop_cart_table']} AS ct
              JOIN {$g5['g5_shop_item_table']} AS it ON ct.it_id=it.it_id
              LEFT JOIN {$g5['g5_shop_item_use_table']} AS its ON ct.it_id=its.it_id AND ct.ct_id=its.ct_id
              WHERE ct.mb_id='{$member['mb_id']}' AND ct_status IN ('구매완료','배송완료','구매확정') AND its.is_id IS NULL
              GROUP BY ct_id ORDER BY ct_time DESC LIMIT {$fr}{$perpage}";

$db_order = sql_query($sql_order);

$qstr = "";
$paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . "?type=possible");


// 작성한 리뷰
$sql_cnt_review = "SELECT COUNT(*) AS CNT FROM lt_shop_item_use WHERE mb_id='{$member['mb_id']}'";
$cnt_review = sql_fetch($sql_cnt_review);

if ($repage > 1) $fr = ($repage - 1) * $perpage . ",";
$total_count = $cnt_review['CNT'];
$total_page  = ceil($total_count / $perpage);

$sql_review = "SELECT iu.it_id,iu.ct_id,iu.is_time,it.it_brand,it.it_discount_price, iu.is_score, iu.is_content,iu.is_id,iu.is_name,it_size FROM lt_shop_item_use AS iu JOIN {$g5['g5_shop_item_table']} AS it ON iu.it_id=it.it_id WHERE mb_id='{$member['mb_id']}' ORDER BY is_time DESC LIMIT {$fr}{$perpage}";
$db_review = sql_query($sql_review);


$repaging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $repage, $total_page, $_SERVER['SCRIPT_NAME'] . "?type=done");

$contents = include_once(G5_VIEW_PATH . "/member.review.list.php");

include_once G5_LAYOUT_PATH . "/layout.php";
