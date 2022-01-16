<?php
include_once('./../common.php');

require_once(G5_LIB_PATH . '/badge.lib.php');
require_once(G5_LIB_PATH . '/shop.lib.php');

$bs_ca = $_GET["bs_ca"];

if(empty($bs_ca)){
    $bs_ca = '00';
}
// $sql_common = " FROM lt_campaign WHERE cp_use=1";
// if ($pick == "true") {
//     $sql_event_picked = "SELECT GROUP_CONCAT(it_id) AS picked FROM lt_shop_wish WHERE mb_id='{$member['mb_id']}' AND wi_type='event' GROUP BY mb_id";
//     $picked = sql_fetch($sql_event_picked);
//     $sql_common .= " AND cp_id IN ({$picked['picked']})";
// }

// $perpage = 9;
// if ($page > 1) $fr = ($page - 1) * $perpage . ",";
// $sql_event = $sql_common . " ORDER BY cp_sort, cp_create_date DESC LIMIT {$fr}{$perpage}";
// $db_event = sql_query("SELECT *" . $sql_event);

// $sql_count_total = "SELECT COUNT(*) AS CNT" . $sql_common;
// $count_total = sql_fetch($sql_count_total);
// $total_count = $count_total['CNT'];
// $total_page  = ceil($total_count / $perpage);

// $qstr .= 'pick=' . $pick;
// $qstr .= '&amp;filter=' . $filter;
// $paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');
$sql_common = "FROM lt_best_item  a ,
lt_shop_item b,
lt_shop_item_option ioc
WHERE (a.it_id = b.it_id
AND a.it_id = ioc.it_id
AND a.bs_category = '".$bs_ca."'
AND b.it_use = 1
AND b.it_soldout = 0
AND ioc.io_stock_qty > 0
)";

$rownum_sql = "set @rownum:=0";
$re_rownum_sql = sql_fetch($rownum_sql);

$sql_count_total = "SELECT COUNT(*) AS CNT " . $sql_common;
$count_total = sql_fetch($sql_count_total);
$total_count = $count_total['CNT'];

$qu_limit = 50 - $total_count;

switch ($bs_ca){
    case '00' :
        $target_category = "10";
        break;
    case '10' :
        $target_category = "1010";
        break;
    case '20' :
        $target_category = "1020";
        break;
    case '30' :
        $target_category = "1030";
        break;
    case '40' :
        $target_category = "1040";
        break;
    default :
        $target_category = "10";
}

$sql_best = "SELECT bb.io_hoching , aa.* 
FROM ((SELECT a.sort AS 1sort, b.* 
FROM lt_best_item  a ,
lt_shop_item b,
lt_shop_item_option ioc
WHERE (a.it_id = b.it_id
AND a.it_id = ioc.it_id
AND a.bs_category = '".$bs_ca."'
AND b.it_use = 1
AND b.it_soldout = 0
AND ioc.io_stock_qty > 0
))
UNION
(SELECT @ROWNUM:=@ROWNUM+1 AS 1sort, c.* FROM lt_shop_item c, lt_shop_item_option ioc2
WHERE (@ROWNUM:=".$total_count.")=".$total_count." AND it_use = 1 AND it_soldout = 0 AND c.it_id NOT IN (SELECT lt_best_item.it_id FROM lt_best_item WHERE bs_category = '".$bs_ca."') AND ca_id LIKE '".$target_category."%' AND c.it_id = ioc2.it_id AND c.it_use=1  AND ioc2.io_use= 1 AND ioc2.io_stock_qty > 0 AND it_total_size= 1  ORDER BY it_sales_num DESC, it_order DESC, c.it_update_time DESC, c.it_time DESC LIMIT " .$qu_limit.")
ORDER BY 1sort ASC ) aa
LEFT JOIN lt_shop_item_option bb ON (aa.it_id=bb.it_id)
WHERE aa.it_use=1  AND bb.io_use= 1 AND bb.io_stock_qty > 0
";


$db_event = sql_query($sql_best);


$contents = include_once(G5_VIEW_PATH . "/best.list.php");
include_once G5_LAYOUT_PATH . "/layout.php";
