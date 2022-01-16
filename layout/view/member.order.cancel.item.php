<?php
$order = $od;
$sql_cart_status = $action == "cancel" ? "'주문','결제완료'" : "'배송중','배송완료','구매확정'";
$sql_cnt_cart_item = "SELECT COUNT(*) AS CNT from {$g5['g5_shop_cart_table']} WHERE od_id='{$order['od_id']}'";
$cnt_cart = sql_fetch($sql_cnt_cart_item);
$cnt_cart_item = $cnt_cart['CNT'];
$sql_cart_item = "SELECT ct.*, io.io_hoching, (SELECT COUNT(*) FROM {$g5['g5_shop_order_item_table']} AS oi WHERE oi.ct_id=ct.ct_id AND oi.ct_status IN ({$sql_cart_status}) GROUP BY oi.ct_id) AS oi_qty FROM {$g5['g5_shop_cart_table']} AS ct LEFT JOIN {$g5['g5_shop_item_option_table']} AS io ON ct.it_id=io.it_id AND ct.io_id=io.io_id WHERE od_id='{$order['od_id']}' AND ct.ct_qty > 0 ORDER BY io_type, ct_id";
$db_cart_item = sql_query($sql_cart_item);

$total_item_price = 0;
$total_order_price = 0;

while (false != ($cart_item = sql_fetch_array($db_cart_item))) {
    $od_image =  get_it_image($cart_item['it_id'], 120, 120);
    $it_name = get_text($cart_item['it_name']);
    $it_id = get_text($cart_item['it_id']);

    $sql_order_item = "SELECT * FROM {$g5['g5_shop_item_table']} WHERE it_id='{$it_id}' ORDER BY it_id LIMIT 1";
    $order_item = sql_fetch($sql_order_item);
    
    $total_item_price += ($order_item['it_price'] + $order_item['it_discount_price']) * $cart_item['ct_qty'];
    $total_order_price +=  $order_item['it_discount_price'] * $cart_item['ct_qty'];
    $sql_cart_status_piece = $action == "cancel" ? $cart_item['ct_status'] == '주문' || $cart_item['ct_status'] == '결제완료' : $cart_item['ct_status'] == '배송중' || $cart_item['ct_status'] == '배송완료' || $cart_item['ct_status'] == '구매확정';
    
    if ($sql_cart_status_piece) {
?>
    <tr class="on-big" style="height: 168px;">
        <td>
            <div class="custom-checkbox check">
                <input type="checkbox" class="custom-control-input checkbox-cancel" name="chk[<?= $cart_item['ct_id'] ?>]" value="<?= $cart_item['ct_id'] ?>" id="ct_chk_<?= $cart_item['ct_id'] ?>" <?= $cnt_cart_item == 1 ? "checked" : "" ?>>
                <label class="custom-control-label" for="ct_chk_<?= $cart_item['ct_id'] ?>"></label>
            </div>
        </td>
        <td style="text-align: left; padding: 58px 0; width: 136px;" onclick="location.href='/member/order.php?od_id=<?= $order['od_id'] ?>'"><?= $od_image ?></td>
        <td class="table_item_contents" style="font-size: 18px; text-align: left; padding: 58px 0;">
            <div class="brand"><?= $order_item['it_brand'] ?>
                <img style="height: 14px; width: 14px;" src="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png" srcset="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@2x.png 2x,/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png 3x">
            </div>
            <div class="name" style="margin: 5px 0 8px 0;" onclick="location.href='/member/order.php?od_id=<?= $order['od_id'] ?>'"><?= $it_name ?></div>
            <div class="price">
                <?= display_price(get_price($order_item), $order_item['it_tel_inq']) ?><span>원</span>
                <?php
                $it_price = $order_item['it_price'];
                $it_sale_price = $order_item['it_discount_price'];
                if (!empty($it_sale_price)) {
                    $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                ?>
                    <span class="price-tag"><del><?= number_format($it_price + $it_sale_price) ?></del><span>원</span></span>
                    <span class="price-dis"><?= number_format($discount_ratio) ?>%</span>
                <?
                }
                ?>
            </div>
        </td>
        <td class="order_price" style="width: 140px;">
            <!-- <?= number_format($cart_item['ct_price'] * $cart_item['ct_qty']) ?>원<br> -->
            <?= number_format($cart_item['ct_price'] * $cart_item['ct_qty'] - $cart_item['cp_price']) ?>원<br>
            <!-- <?= number_format($cart_item['ct_price'] * $cart_item['ct_qty'] - $order['od_coupon'] - $order['od_cart_coupon'] - $order['od_send_coupon'] - $order['od_receipt_point'] + $order['od_send_cost'] + $order['od_send_cost2']  ) ?>원<br> -->
            <!-- <span style="font-size: 14px; font-weight: 500; color: #a9a9a9;">(<?= number_format($cart_item['ct_qty']) ?>개)</span> -->
        </td>
        <td class="order_id" style="width: 220px;">
            <input type="hidden" name="ct_id[<?= $cart_item['ct_id'] ?>]">
            <input type="hidden" name="it_name[<?= $cart_item['ct_id'] ?>]">
            <span style="font-size: 18px;" id ="refundsNum[<?= $cart_item['ct_id'] ?>]"><?= $cart_item['ct_qty'] ?></span><span style="font-size: 18px;">개</span>
            <!-- <p style="font-size: 18px;"><span id ="refundsNum[<?= $cart_item['ct_id'] ?>]"><?= $cart_item['ct_qty'] ?></span>개</p> -->
            <!-- <select name="ct_qty[<?= $cart_item['ct_id'] ?>]" style="width: 100px;">
                <? if ($cart_item['oi_qty'] > 1) : ?>
                    <option value=0>선택</option>
                    <? for ($q = 1; $q <= $cart_item['oi_qty']; $q++) :  ?>
                        <option value="<?= $q ?>"><?= $q ?>개</option>
                    <? endfor ?>
                <? else : ?>
                    <option value="1">1개</option>
                <? endif ?>
            </select> -->


        </td>
    </tr>
    <tr class="on-small">
        <td style="padding: 20px 14px; border: unset; height: auto; padding-top: unset;">
            <div style=" border-top: 1px solid var(--very-light-pink);"></div>
        </td>
    </tr>
    <tr class="on-small">
        <td style="padding: 0 14px; border: unset;">
            <div style="display: flex;">
                <div class="order_item_contents_img"><?= $od_image ?></div>
                <div class="order_item_contents_info" style="display: flex; flex-direction: column; width: 100%;">
                    <div style="height: 76px;">
                        <div class="order_item_contents_brand" style="display: flex; justify-content: space-between;">
                            <span>
                                <?= $order_item['it_brand'] ?> <img src="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png" srcset="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@2x.png 2x,/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png 3x">
                            </span>
                            <span class="custom-checkbox check">
                                <input type="checkbox" class="custom-control-input checkbox-cancel-mobile" data-mobile="true" name="chk[<?= $cart_item['ct_id'] ?>]" value="<?= $cart_item['ct_id'] ?>" id="ct_chk_<?= $cart_item['ct_id'] ?>_m" <?= $cnt_cart_item == 1 ? "checked" : "" ?>>
                                <label class="custom-control-label" for="ct_chk_<?= $cart_item['ct_id'] ?>_m"></label>
                            </span>
                        </div>
                        <div class="order_item_contents_name"><?= $it_name ?></div>
                        <div class="order_item_contents_price">
                            <?= display_price(get_price($order_item), $order_item['it_tel_inq']) ?><span>원</span>
                            <?php
                            $it_price = $order_item['it_price'];
                            $it_sale_price = $order_item['it_discount_price'];
                            if (!empty($it_sale_price)) {
                                $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                            ?>
                                <span class="price-tag"><del><?= number_format($it_price + $it_sale_price) ?></del><span>원</span></span>
                                <span class="price-dis"><?= number_format($discount_ratio) ?>%</span>
                            <?
                            }
                            ?>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div>
                            <p style="font-size: 12px;"><span id ="refundsNum[<?= $cart_item['ct_id'] ?>]"><?= $cart_item['ct_qty'] ?></span>개</p>
                            <!-- <select name="ct_qty[<?= $cart_item['ct_id'] ?>]" style="width: 100px;" data-mobile="true">
                                <? if ($cart_item['oi_qty'] > 1) : ?>
                                    <option value=0>선택</option>
                                    <? for ($q = 1; $q <= $cart_item['oi_qty']; $q++) :  ?>
                                        <option value="<?= $q ?>"><?= $q ?>개</option>
                                    <? endfor ?>
                                <? else : ?>
                                    <option value="1">1개</option>
                                <? endif ?>
                            </select> -->
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    <? $cnt_cart_item = 0 ?>
<? } } ?>