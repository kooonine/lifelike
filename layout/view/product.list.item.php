<div class="product-list">
    <?
    // PICK 리스트
    $g5_picked = array(
        'ITEM' => array(),
        'BRAND' => array(),
        'EVENT' => array()
    );
    
    if ($member['mb_id']) {
        $sql_picked = "SELECT * FROM lt_shop_wish WHERE wi_type = 'item' and mb_id='{$member['mb_id']}'";
        $db_picked = sql_query($sql_picked);
        while (false != ($tp = sql_fetch_array($db_picked))) {
            
            $g5_picked['ITEM'][] = $tp['it_id'];
        }
    }
    ?>
    <?php if ($result->num_rows > 0) : ?>
        <? for ($i = 0; $row = sql_fetch_array($result); $i++) : ?>
            <?= $i > 0 ? "</div>" : "" ?><div class="product-list-item-wrapper">
                <?php if ($this->href) : ?><a href="<?= $this->href . $row['it_id']; ?>"><?php endif ?>
                    <?php
                    $badge = new badge($row);
                    $badgeHtml = $badge->makeHtml();
                    $thumb = get_it_image_path($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name']));
                    $it_idOP = $row['it_id'];

                    $ioQty = "SELECT io_stock_qty FROM lt_shop_item_option WHERE it_id = '$it_idOP' LIMIT 1";
                    $ioStoQty= sql_fetch($ioQty); 

                    $total001 = "SELECT io_order_no FROM {$g5['g5_shop_item_option_table']} WHERE it_id = '$it_idOP' LIMIT 1";
                    $total001_1= sql_fetch($total001); 
                    $total002 = "SELECT * FROM {$g5['g5_shop_item_table']} a LEFT JOIN {$g5['g5_shop_item_option_table']} b ON (a.it_id = b.it_id) WHERE b.io_order_no = '{$total001_1['io_order_no']}' AND it_use = 1  ORDER BY it_price ASC, it_size_info DESC";
                    $total002_2 = sql_query($total002); 
                    $total002_3 = sql_query($total002); 
                    $soldOut = 1;
                    for ($j = 0; $totalRow2 = sql_fetch_array($total002_3); $j++) {
                        if ($totalRow2['it_soldout'] == 1 || $totalRow2['io_stock_qty'] < 1 ) {
                        } else {
                            $soldOut = 0;
                        }
                    }
                    if ($row['io_hoching'] == 'MS' || $row['io_hoching'] == 'L') $row['io_hoching'] = 'S';
                    ?>
                    <? if ($soldOut == 1) : ?>
                        <div class="product-list-item-thumb<?php if ($i >= 8) : ?> thumb-lazy<? endif ?>" <?php if ($this->view_it_img) : ?> data-image="<?= $thumb ?>"  style="background-image: url(<?= $thumb ?>); background-size : cover;" <? endif ?>>
                            <div class="soldout_thumb"><p style="opacity: 1; color: black;">일시 품절</p></div>
                            <span class="btn-pick-heart <?= in_array($row['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $row['it_id'] ?>></span>
                        </div>
                    <? else : ?>
                        <div class="product-list-item-thumb<?php if ($i >= 8) : ?> thumb-lazy<? endif ?>" <?php if ($this->view_it_img) : ?> data-image="<?= $thumb ?>"  style="background-image: url(<?= $thumb ?>); background-size : cover;" <? endif ?>>
                            <span class="btn-pick-heart <?= in_array($row['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $row['it_id'] ?>></span>
                        </div>
                        
                    <? endif ?>
                    <div class="product-list-item-info">
                        <div class="product-list-item-brand">
                            <?php echo empty($row['it_brand']) ? "LIFELIKE" : $row['it_brand'] ?>
                        <? for ($j = 0; $totalRow = sql_fetch_array($total002_2); $j++) : 
                            if ($totalRow['it_soldout'] == 1 || $totalRow['io_stock_qty'] < 1 ) { ?>
                                <span class ='hocOutName<?= $totalRow['io_hoching'] ?>'></span>
                            <? } else {?>
                                <span class ='hocName<?= $totalRow['io_hoching'] ?>'></span>
                            <? }
                            
                            ?>
                        <? endfor; ?>
                        </div>
                        
                        <div class="product-list-item-name">
                            <?php if ($this->view_it_name) {
                                echo stripslashes($row['it_name']);
                            } ?>
                        </div>
                        <div class="product-list-item-saleprice on-big">
                            <?php if ($this->view_it_price) {
                                echo display_price(get_price($row), $row['it_tel_inq']);
                            } 
                            $oneSize = '원 ~';
                            if ($j == 1) $oneSize = '원'?><span><?= $oneSize?></span>
                        
                        <?php
                        if ($row['it_discount_price'] != '' && $row['it_discount_price'] != '0') {
                            $it_price = $row['it_price'];
                            $it_sale_price = $row['it_discount_price'];
                            $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                        ?>
                            
                                <span class="price-tag"><del><?= number_format($it_price + $it_sale_price) ?></del><span>원</span></span>
                                <span class="price-dis"><?= number_format($discount_ratio) ?>%</span>
                            
                        <?
                        }
                        ?>
                        </div>                        

                        <div class="product-list-item-saleprice on-small">
                            <?php if ($this->view_it_price) {
                                echo display_price(get_price($row), $row['it_tel_inq']);
                            } ?><span><?= $oneSize?></span>
                        </div>
                        <?php
                        if ($row['it_discount_price'] != '' && $row['it_discount_price'] != '0') {
                            $it_price = $row['it_price'];
                            $it_sale_price = $row['it_discount_price'];
                            $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                        ?>
                            <div class="product-list-item-price on-small">
                                <span class="price-tag"><del><?= number_format($it_price + $it_sale_price) ?></del><span>원</span></span>
                                <span class="price-dis"><?= number_format($discount_ratio) ?>%</span>
                            </div>
                        <?
                        }
                        ?>
                        <div class="product-list-item-label"><?= $badgeHtml->html ?></div>
                        <?php if ($this->href) : ?>
                        </a><?php endif ?>
                    </div>
        <? endfor; ?>
        </div>
    <?php endif ?>
</div>