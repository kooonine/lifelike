<?php
ob_start();
?>
<div class="event-item-set-wrapper">
    <? if (!empty($is_subject)) : ?>
        <!-- <div class="event-item-set-subject"><?= $is_subject ?></div> -->
    <? endif ?>
    <div class="product-list">
        <? for ($i = 0; $row = sql_fetch_array($db_items); $i++) : ?>
            <div class="product-list-item-wrapper event type_<?=$row['area']?>">
                <?php
                require_once(G5_LIB_PATH . '/badge.lib.php');
                $badge = new badge($row);
                $badgeHtml = $badge->makeHtml();
                $thumb = get_it_image_path($row['it_id'], 600, 600, '', '', stripslashes($row['it_name']));

                $totalSpecial001 = "SELECT io_order_no FROM lt_shop_item_option WHERE it_id = '{$row['it_id']}' LIMIT 1";
                $totalSpecial001_1= sql_fetch($totalSpecial001); 
                $totalSpecial002 = "SELECT * FROM lt_shop_item a LEFT JOIN lt_shop_item_option b ON (a.it_id = b.it_id) WHERE b.io_order_no = '{$totalSpecial001_1['io_order_no']}' AND it_use = 1  ORDER BY it_price ASC, it_size_info DESC";
                $totalSpecial002_2 = sql_query($totalSpecial002);



                ?>
                <a href="/shop/item.php?it_id=<?= $row['it_id']; ?>">
                    <div class="product-list-item-thumb thumb-lazy" data-image="<?= $thumb ?>" style='background-image: url("<?= $thumb ?>");  background-size : cover;'>
                        <span class="btn-pick-heart <?= in_array($row['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $row['it_id'] ?>></span>
                    </div>
                    <div class="product-list-item-info">
                        <div class="product-list-item-brand">
                            <?php echo empty($row['it_brand']) ? "LIFELIKE" : $row['it_brand'] ?>
                            <!-- <span class ='hocName<?= $row['io_hoching'] ?>'></span> -->
                            <? for ($tss = 0; $tsR = sql_fetch_array($totalSpecial002_2); $tss++) : 
                                if ($tsR['it_soldout'] == 1 || $tsR['io_stock_qty'] < 1 ) { ?>
                                    <span class ='hocOutName<?= $tsR['io_hoching'] ?>'></span>
                               <? } else {?>
                                    <span class ='hocName<?= $tsR['io_hoching'] ?>'></span>
                                <? }
                                ?>
                            <? endfor; 
                                $oneSize = '원 ~';
                                if ($tss == 1) $oneSize = '원'
                            ?>
                        </div>
                        
                        <div class="product-list-item-name">
                            <?= stripslashes($row['it_name']) ?>
                        </div>
                        <div class="product-list-item-saleprice">
                            <?= stripslashes(display_price(get_price($row), $row['it_tel_inq'])) ?><?= $oneSize?>
                        </div>
                        <?php
                        if ($row['it_discount_price'] != '' && $row['it_discount_price'] != '0') {
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
                        <div class="product-list-item-label"><?= $badgeHtml->html ?></div>
                    </div>
                </a>
            </div>
        <? endfor; ?>
    </div>
</div>
<?php
$products = ob_get_contents();
ob_end_clean();
return $products;
?>