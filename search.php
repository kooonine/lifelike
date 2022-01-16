<?php
include_once './common.php';
require_once(G5_LIB_PATH . '/badge.lib.php');
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$type = $type ? $type : "item";
$snum = array(
    'total' => 0,
    'item' => 0,
    'brand' => 0,
    'event' => 0,
    'campaign' => 0
);

$skeyword = trim(clean_xss_tags($skeyword));
if($skeyword=='') $skeyword = $skeyword2;
if ($skeyword) {
    $search_keyword = explode(' ', $skeyword);
    $arr_search_keyword = array(
        'it_name' => array(),
        'br_name' => array(),
        'desc' => array(),
        'subject' => array(),
        'search_word' => array()
    );
    foreach ($search_keyword as $sk) {
        $arr_search_keyword['it_name'][] = "it_name LIKE '%{$sk}%'";
        $arr_search_keyword['br_name'][] = "br_name LIKE '%{$sk}%'";
        $arr_search_keyword['desc'][] = "cp_desc LIKE '%{$sk}%'";
        $arr_search_keyword['subject'][] = "cp_subject LIKE '%{$sk}%'";
        $arr_search_keyword['search_word'][] = "it_search_word LIKE '%{$sk}%'";
    }

    $sql_common_search_item = "SELECT __FIELD__ FROM lt_shop_item WHERE it_use=1 AND it_total_size = 1 AND (it_name LIKE '%{$skeyword}%' OR it_search_word LIKE '%{$skeyword}%')";
    $sql_common_search_brand = "SELECT __FIELD__ FROM lt_brand WHERE br_use=1 AND (br_name LIKE '%{$skeyword}%')";
    $sql_common_search_event = "SELECT __FIELD__ FROM lt_campaign WHERE cp_use=1 AND (cp_subject LIKE '%{$skeyword}%' OR cp_desc LIKE '%{$skeyword}%')";

    $sql_common_search_item = "SELECT __FIELD__ FROM lt_shop_item WHERE it_use=1 AND it_total_size = 1 AND ((" . implode(' AND ', $arr_search_keyword['it_name']) . ") OR (" . implode(' AND ', $arr_search_keyword['search_word']) . "))";
    $sql_common_search_brand = "SELECT __FIELD__ FROM lt_brand WHERE br_use=1 AND (" . implode(' AND ', $arr_search_keyword['br_name']) . ")";
    $sql_common_search_event = "SELECT __FIELD__ FROM lt_campaign WHERE cp_use=1 AND ((" . implode(' AND ', $arr_search_keyword['subject']) . ") OR (" . implode(' AND ', $arr_search_keyword['desc']) . "))";

    $count_search_item = sql_fetch(str_replace('__FIELD__', 'COUNT(*) AS CNT', $sql_common_search_item));
    $count_search_brand = sql_fetch(str_replace('__FIELD__', 'COUNT(*) AS CNT', $sql_common_search_brand));
    $count_search_event = sql_fetch(str_replace('__FIELD__', 'COUNT(*) AS CNT', $sql_common_search_event));

    $snum['item'] = $count_search_item['CNT'];
    $snum['brand'] = $count_search_brand['CNT'];
    $snum['event'] = $count_search_event['CNT'];
    $snum['campaign'] = $count_search_event['CNT'];
    $snum['total'] = $snum['item'] + $snum['brand'] + $snum['campaign'];

    $perpage = 12;
    if ($page > 1) $fr = ($page - 1) * $perpage . ",";

    switch ($type) {
        case "brand";
            $sql_search_brand = str_replace('__FIELD__', '*', $sql_common_search_brand) . " LIMIT {$fr}{$perpage}";
            $result = sql_query($sql_search_brand);
            break;
        case "campaign";
            $sql_search_event = str_replace('__FIELD__', '*', $sql_common_search_event) . " LIMIT {$fr}{$perpage}";
            $result = sql_query($sql_search_event);
            break;
        default;
            $sql_search_item = str_replace('__FIELD__', '*', $sql_common_search_item) . " LIMIT {$fr}{$perpage}";
            $result = sql_query($sql_search_item);
            break;
    }

    $total_page  = ceil($snum[$type] / $perpage);

    $qstr .= 'type=' . $type;
    $qstr .= '&amp;skeyword=' . $skeyword;
    $paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');
} else if ($cp_id) {
    goto_url("/event/view.php?cp_id=" . $cp_id);
}

$contents = include_once(G5_VIEW_PATH . "/search.php");

include_once G5_LAYOUT_PATH . "/layout.php";
