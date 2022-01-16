<?php
include_once('./_common.php');
require_once(G5_SHOP_PATH . '/settle_lg.inc.php');

if (!$is_member) {
    goto_url('/auth/login.php?url=' . urlencode('/member/notice.php'));
}

$sql_notice = "SELECT * FROM lt_write_notice WHERE wr_1='1'";
$sql_where = array();
if (!empty($filter)) $sql_where[] = "ca_name='" . addslashes($filter) . "'";
if (!empty($sql_where)) $sql_notice .= " AND " . implode(' AND ', $sql_where);

$db_count = sql_query($sql_notice);

$perpage = 10;
if ($page > 1) $fr = ($page - 1) * $perpage . ",";
$total_count = $db_count->num_rows;
$total_page  = ceil($total_count / $perpage);

$qstr = "filter={$filter}";
$paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');

$sql_notice .= " ORDER BY wr_datetime DESC LIMIT {$fr}{$perpage}";
$db_notice = sql_query($sql_notice);

$contents = include_once(G5_VIEW_PATH . "/member.notice.list.php");

include_once G5_LAYOUT_PATH . "/layout.php";
