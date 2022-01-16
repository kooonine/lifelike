<?php
include_once('./../common.php');
require_once(G5_LIB_PATH . '/badge.lib.php');
$sql_common = " FROM lt_campaign WHERE cp_use=1 AND (cp_end_date != '0000-00-00 00:00:00' AND cp_end_date > NOW())";
if ($pick == "true") {
    $sql_event_picked = "SELECT GROUP_CONCAT(it_id) AS picked FROM lt_shop_wish WHERE mb_id='{$member['mb_id']}' AND wi_type='event' GROUP BY mb_id";
    $picked = sql_fetch($sql_event_picked);
    $sql_common .= " AND cp_id IN ({$picked['picked']})";
}

$perpage = 9;
if ($page > 1) $fr = ($page - 1) * $perpage . ",";
$sql_event = $sql_common . " ORDER BY cp_sort, cp_create_date DESC LIMIT {$fr}{$perpage}";
$db_event = sql_query("SELECT *" . $sql_event);

$sql_count_total = "SELECT COUNT(*) AS CNT" . $sql_common;
$count_total = sql_fetch($sql_count_total);
$total_count = $count_total['CNT'];
$total_page  = ceil($total_count / $perpage);

$qstr .= 'pick=' . $pick;
$qstr .= '&amp;filter=' . $filter;
$paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');

$contents = include_once(G5_VIEW_PATH . "/campaign.list.php");
include_once G5_LAYOUT_PATH . "/layout.php";
