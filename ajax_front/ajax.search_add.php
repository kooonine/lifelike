<?php
include_once('./../common.php');

$page = $_POST['page'];
$searchKey = $_POST['searchKey'];

$search_keyword = explode(' ', $searchKey);
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



$perpage = 12;
if ($page > 1) $fr = ($page - 1) * $perpage . ",";

$sql_common_search_item = "SELECT __FIELD__ FROM lt_shop_item WHERE it_use=1 AND it_total_size =1 AND ((" . implode(' AND ', $arr_search_keyword['it_name']) . ") OR (" . implode(' AND ', $arr_search_keyword['search_word']) . "))";
$count_search_item = sql_fetch(str_replace('__FIELD__', 'COUNT(*) AS CNT', $sql_common_search_item));
$snum['item'] = $count_search_item['CNT'];
$sql_search_item = str_replace('__FIELD__', '*', $sql_common_search_item) . " LIMIT {$fr}{$perpage}";
$result = sql_query($sql_search_item);







$rows = 12;
$total_page  = ceil($snum[$type] / $perpage);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

?>

<? for ($i = 0; $row = sql_fetch_array($result); $i++) : ?>
    <div class="product-list-item-wrapper<?= $i == 0 ? " first" : "" ?>" style="margin-right: 22px; margin-left: -18px;">
        <a href="/shop/item.php?it_id=<?= $row['it_id']; ?>">
            <?php
            $thumb = get_it_image_path($row['it_id'], 300, 300, '', '', stripslashes($row['it_name']));
            $sql_option = "SELECT io_no, io_hoching FROM lt_shop_item_option WHERE it_id = '{$row['it_id']}' ORDER BY io_no LIMIT 1 ";
            $option_item = sql_fetch($sql_option);
            // if ($option_item['io_hoching'] == 'MS'|| $option_item['io_hoching'] == 'L') $option_item['io_hoching'] = 'S';
            $totalSearchA001 = "SELECT io_order_no FROM {$g5['g5_shop_item_option_table']} WHERE it_id = '{$row['it_id']}' LIMIT 1";
            $totalSearchA001_1= sql_fetch($totalSearchA001); 
            $totalSearchA002 = "SELECT * FROM {$g5['g5_shop_item_table']} a LEFT JOIN {$g5['g5_shop_item_option_table']} b ON (a.it_id = b.it_id) WHERE b.io_order_no = '{$totalSearchA001_1['io_order_no']}' AND it_use = 1  ORDER BY it_price ASC, it_size_info DESC";
            $totalSearchA002_2 = sql_query($totalSearchA002); 
            $totalSearchA002_3 = sql_query($totalSearchA002); 
            $soldOut = 1;
            for ($tsA = 0; $totalSearchARow2 = sql_fetch_array($totalSearchA002_3); $tsA++) {
                if ($totalSearchARow2['it_soldout'] == 1 || $totalSearchARow2['io_stock_qty'] < 1 ) {
                } else {
                    $soldOut = 0;
                }
            }



            ?>
            
            <div class="product-list-item-thumb" data-image="<?= $thumb ?>" style="background-image: url(<?= $thumb ?>)">
                <span class="btn-pick-heart <?= in_array($row['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $row['it_id'] ?>></span>
            </div>
            <div class=" product-list-item-brand">
                <?php echo empty($row['it_brand']) ? "LIFELIKE" : $row['it_brand'] ?>

                <? for ($tsAs = 0; $tsAR = sql_fetch_array($totalSearchA002_2); $tsAs++) : 
                    if ($tsAR['it_soldout'] == 1 || $tsAR['io_stock_qty'] < 1 ) { ?>
                        <span class ='hocOutName<?= $tsAR['io_hoching'] ?>'></span>
                   <? } else {?>
                        <span class ='hocName<?= $tsAR['io_hoching'] ?>'></span>
                    <? }
                    ?>
                <? endfor; 
                    $oneSize = '원 ~';
                    if ($tsAs == 1) $oneSize = '원'
                ?>
            </div>
            <!-- <div class="product-list-item-label"><?= $badgeHtml->html ?></div> -->
            <div class="product-list-item-name">
                <?= stripslashes($row['it_name']) ?>
            </div>
            <div class="product-list-item-saleprice">
                <?= display_price(get_price($row), $row['it_tel_inq']) ?><span><?= $oneSize ?></span>
            </div>
            <?php
            if (!empty($row['it_discount_price'])) {
                $it_price = $row['it_price'];
                $it_sale_price = $row['it_discount_price'];
                $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
            ?>
                <div class="product-list-item-price">
                    <span class="price-tag"><del><?= number_format($it_price + $it_sale_price) ?></del><span>원</span></span>
                    <span class="price-dis">(<?= number_format($discount_ratio) ?>%)</span>
                </div>
            <?
            }
            ?>
        </a>
    </div>
<? endfor; ?>