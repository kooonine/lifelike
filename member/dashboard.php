<?php
include_once('./_common.php');
require_once(G5_LIB_PATH . '/badge.lib.php');

$sql_cnt_order = "SELECT COUNT(*) AS CNT FROM {$g5['g5_shop_order_table']} WHERE mb_id='{$member['mb_id']}' AND (od_type='O' OR od_type='R')";
$cnt_order = sql_fetch($sql_cnt_order);

$sql_recent_order = "SELECT * FROM {$g5['g5_shop_order_table']} WHERE mb_id='{$member['mb_id']}' AND (od_type='O' OR od_type='R') ORDER BY od_time DESC LIMIT 3";
$db_recent_order = sql_query($sql_recent_order);

$db_recent_morder = sql_query($sql_recent_order);


$sql_order_status1 = "SELECT COUNT(*) AS cnt FROM {$g5['g5_shop_order_table']} WHERE mb_id='{$member['mb_id']}' AND od_status = '결제완료' ";
$sql_order_status2 = "SELECT COUNT(*) AS cnt FROM {$g5['g5_shop_order_table']} WHERE mb_id='{$member['mb_id']}' AND od_status = '배송준비중' ";
$sql_order_status3 = "SELECT COUNT(*) AS cnt FROM {$g5['g5_shop_order_table']} WHERE mb_id='{$member['mb_id']}' AND od_status = '배송중' ";
$sql_order_status4 = "SELECT COUNT(*) AS cnt FROM {$g5['g5_shop_order_table']} WHERE mb_id='{$member['mb_id']}' AND od_status = '배송완료' ";

$cnt_order1 = sql_fetch($sql_order_status1);
$cnt_order2 = sql_fetch($sql_order_status2);
$cnt_order3 = sql_fetch($sql_order_status3);
$cnt_order4 = sql_fetch($sql_order_status4);

$picked = array('ITEM' => array(), 'BRAND' => array(), 'EVENT' => array());
$sql_picked = "SELECT * FROM {$g5['g5_shop_wish_table']} WHERE mb_id='{$member['mb_id']}' ORDER BY wi_time DESC";
$db_picked = sql_query($sql_picked);

if ($db_picked->num_rows > 0) {
    while (false != ($pitem = sql_fetch_array($db_picked))) {
        $pk = strtoupper($pitem['wi_type']);
        $tmp_item = null;
        switch ($pk) {
            case 'ITEM':
                $tmp_item = sql_fetch("SELECT * FROM lt_shop_item WHERE it_use = 1 AND it_id='" . $pitem['it_id'] . "' GROUP BY it_id");
                break;
            case 'BRAND':
                $tmp_item = sql_fetch("SELECT * FROM lt_brand WHERE br_use = 1 AND br_id='" . $pitem['it_id'] . "' GROUP BY br_id");
                break;
            case 'EVENT':
                $tmp_item = sql_fetch("SELECT * FROM lt_campaign WHERE cp_use = 1 AND cp_id='" . $pitem['it_id'] . "' GROUP BY cp_id");
                break;
        }

        if (!empty($tmp_item)) {
            $picked[$pk][] = $tmp_item;
        }
    }
}

$contents = include_once(G5_VIEW_PATH . "/member.dashboard.php");
include_once G5_LAYOUT_PATH . "/layout.php";
