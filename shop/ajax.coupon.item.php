<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/json.lib.php');

if (empty($it)) die(json_encode(array('error' => 'NOT FOUND IT')));

$result = array();
switch ($method) {
    case 4:     // 브랜드
        $sql_items = "SELECT b.br_id, b.br_name FROM lt_shop_item AS it LEFT JOIN lt_brand AS b ON it.it_brand=b.br_name_en GROUP BY it.it_brand ORDER BY it.it_brand";
        break;
    default:
        $sql_items = "SELECT * FROM lt_shop_item WHERE it_id IN ({$it}) ORDER BY it_name";
        break;
}

$db_items = sql_query($sql_items);

switch ($method) {
    case 4:     // 브랜드
        while (false != ($item = sql_fetch_array($db_items))) {
            $result[$item['br_id']] = array(
                "subject" => $item['br_name'],
                "link" => "/shop/brand.php?br_id=" . $item['br_id']
            );
        }
        break;
    default:
        while (false != ($item = sql_fetch_array($db_items))) {
            $result[$item['it_id']] = array(
                "subject" => $item['it_name'],
                "link" => "/shop/item.php?it_id=" . $item['it_id']
            );
        }
        break;
}

die(json_encode(array('result' => $result, 'error' => '')));
