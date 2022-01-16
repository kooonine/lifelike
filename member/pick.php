<?php
include_once('./_common.php');
require_once(G5_LIB_PATH . '/badge.lib.php');

$type = isset($type) ? $type : "item";

$picked = array('ITEM' => array(), 'BRAND' => array(), 'EVENT' => array());
$sql_picked_common = "SELECT * FROM {$g5['g5_shop_wish_table']} WHERE mb_id='{$member['mb_id']}' AND wi_type='{$type}'";
$db_picked_common = sql_query($sql_picked_common);

$perpage = 16;
switch ($type) {
    case "brand":
        $perpage = 4;
        break;
    case "event":
        $perpage = 6;
        break;
}

if ($page > 1) $fr = ($page - 1) * $perpage . ",";
$total_count = $db_picked_common->num_rows;
$total_page  = ceil($total_count / $perpage);

$sql_picked = $sql_picked_common . " ORDER BY wi_time DESC LIMIT {$fr}{$perpage}";
$db_picked = sql_query($sql_picked);

$qstr = "type=" . $type;
$paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');

$contents = include_once(G5_VIEW_PATH . "/member.pick.php");
include_once G5_LAYOUT_PATH . "/layout.php";
