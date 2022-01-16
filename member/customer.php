<?php
include_once('./_common.php');
require_once(G5_SHOP_PATH . '/settle_lg.inc.php');

if (!$is_member) {
    goto_url('/auth/login.php?url=' . urlencode('/member/customer.php'));
}

$sql_cnt_qna = "SELECT COUNT(*) AS CNT FROM lt_qa_content WHERE mb_id='{$member['mb_id']}' AND qa_type=0 AND it_id=''";
$cnt_qna = sql_fetch($sql_cnt_qna);

$perpage = 5;
if ($page > 1) $fr = ($page - 1) * $perpage . ",";
$total_count = $cnt_qna['CNT'];
$total_page  = ceil($total_count / $perpage);

$sql_qna = "SELECT q.*, (SELECT qa_content FROM lt_qa_content AS a WHERE a.qa_parent=q.qa_id AND a.qa_type=1) AS qa_answer FROM lt_qa_content AS q WHERE mb_id='{$member['mb_id']}' AND qa_type=0 AND it_id='' ORDER BY qa_datetime DESC LIMIT {$fr}{$perpage}";
$db_qna = sql_query($sql_qna);

$qstr = "";
$paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');

$sql_order = "SELECT od.od_id,od.od_cart_price,od_time,oi.it_name, COUNT(oi.od_id)-1 AS CNT_ITEM FROM lt_shop_order AS od LEFT JOIN lt_shop_order_item AS oi ON od.od_id=oi.od_id WHERE od.mb_id='{$member['mb_id']}' AND od.od_type IN ('O','R') GROUP BY od.od_id ORDER BY od.od_time DESC";
$db_order = sql_query($sql_order);

$contents = include_once(G5_VIEW_PATH . "/member.customer.list.php");

include_once G5_LAYOUT_PATH . "/layout.php";
