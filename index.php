<?php
include_once './_common.php';
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
define('_INDEX_', true);

$sql_campaign = "SELECT * FROM lt_campaign WHERE cp_use=1 AND FIND_IN_SET('MAIN',cp_banner)>0 AND cp_start_date <= NOW() AND cp_end_date >= NOW() ORDER BY cp_sort ASC";
$db_campaign = sql_query($sql_campaign);
$campaign = array();
for ($ci = 0; $cmp = sql_fetch_array($db_campaign); $ci++) {
    if (!isset($campaign[$cmp['cp_category']])) $campaign[$cmp['cp_category']] = array();
    $campaign[$cmp['cp_category']][] = $cmp;
}

$campaign_rand = array('MD', 'NEW', 'BEST', 'HOT');
foreach ($campaign_rand as $cr) {
    if (isset($campaign[$cr])) shuffle($campaign[$cr]);
}

$sql_brand = "SELECT * FROM lt_brand WHERE br_use=1 ORDER BY RAND()";
$db_brand = sql_query($sql_brand);
$brands = array();

while (($brand = sql_fetch_array($db_brand)) != false) {
    $sql_brand_items = "SELECT * FROM {$g5['g5_shop_item_table']} WHERE it_use=1 AND it_brand='{$brand['br_name_en']}' ORDER BY it_time DESC LIMIT 3";
    $db_brand_items = sql_query($sql_brand_items);
    if ($db_brand_items->num_rows >= 3) {
        $tmp_brand = $brand;
        $tmp_brand['ITEMS'] = array();
        for ($bii = 0; $bii < 3; $bii++) {
            $tmp_brand['ITEMS'][] = sql_fetch_array($db_brand_items);
        }
        $brands[] = $tmp_brand;
    }

    if (count($brands) >= 3) break;
}

$season_best = array(
    101010 => array(),
    101020 => array(),
    101050 => array(),
);

foreach ($season_best as $si => $sb) {
    $sql_best_item = "SELECT * FROM {$g5['g5_shop_item_table']} WHERE it_use=1 AND ca_id='{$si}' ORDER BY it_hit DESC limit 5";
    $db_best_item = sql_query($sql_best_item);
    while (false != ($sbi = sql_fetch_array($db_best_item))) {
        $season_best[$si][] = $sbi;
    }
}

$contents = include_once(G5_VIEW_PATH . "/index.php");

include_once(G5_LAYOUT_PATH . "/layout.php");
