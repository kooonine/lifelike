<? for ($oi = 0; $order = sql_fetch_array($db_order); $oi++) : ?>
    <?php
    $sql_cnt_cart_item = "SELECT COUNT(*) AS CNT from {$g5['g5_shop_cart_table']} WHERE od_id='{$order['od_id']}'";
    $cnt_cart = sql_fetch($sql_cnt_cart_item);
    $cnt_cart_item = $cnt_cart['CNT'];
    $sql_cart_item = "SELECT ct.*, io.io_hoching FROM {$g5['g5_shop_cart_table']} AS ct LEFT JOIN {$g5['g5_shop_item_option_table']} AS io ON ct.it_id=io.it_id AND ct.io_id=io.io_id WHERE od_id='{$order['od_id']}' ORDER BY io_type, ct_id";
    $db_cart_item = sql_query($sql_cart_item);

    while (false != ($cart_item = sql_fetch_array($db_cart_item))) {
        $od_image = get_it_image($cart_item['it_id'], 120, 120);
        $it_name = get_text($cart_item['it_name']);
        $it_id = get_text($cart_item['it_id']);

        $sql_order_item = "SELECT * FROM {$g5['g5_shop_item_table']} WHERE it_id='{$it_id}' ORDER BY it_id LIMIT 1";
        $order_item = sql_fetch($sql_order_item);
    ?>
        <tr class="on-big" style="height: 168px;">
            <? if ($cnt_cart_item > 0) : ?>
                <td rowspan=<?= $cnt_cart_item ?> style="width: 240px; cursor: pointer;" onclick="location.href='/member/order.php?od_id=<?= $order['od_id'] ?>'">
                    <span style="border-bottom: 1px solid #333333;"><?= $order['od_id'] ?></span><br>
                    <span style="font-size: 14px; font-weight: 500; color: #9f9f9f;">(<?= date("Y.m.d", strtotime($order['od_time'])) ?>)</span>
                </td>
            <? endif ?>
            <td style="text-align: left; padding: 58px 0; width: 136px; cursor: pointer;" onclick="location.href='/member/order.php?od_id=<?= $order['od_id'] ?>'"><?= $od_image ?></td>
            <td class="table_item_contents" style="font-size: 18px; text-align: left; padding: 58px 0;">
                <div class="brand"><?= $order_item['it_brand'] ?> <span class ='hocName<?= $cart_item['io_hoching'] ?>'></span>
                    <!-- <img style="height: 14px; width: 14px;" src="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png" srcset="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@2x.png 2x,/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png 3x"> -->
                </div>
                <div class="name" style="margin: 5px 0 8px 0; cursor: pointer; width:420px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:inline-block;" onclick="location.href='/member/order.php?od_id=<?= $order['od_id'] ?>'"><?= $it_name ?></div>
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
                <div style="padding-top: 23px;">
                    <button type="button" class="btn-member btn-sm btn-gray" onclick="writeQuestion('<?= $cart_item['od_id'] ?>')" data-odid=<?= $order['od_id'] ?> data-ctid=<?= $cart_item['ct_id'] ?>>1:1문의</button>
                    <? 
                        $reviewYnSql = "SELECT COUNT(*) AS CNT from lt_shop_item_use WHERE ct_id='{$cart_item['ct_id']}'";
                        $reviewYn = sql_fetch($reviewYnSql);
                        $reviewCnt = $reviewYn['CNT'];
        
                        if (($cart_item['ct_status'] == '배송완료' || $cart_item['ct_status'] == '구매확정' || $cart_item['ct_status'] == '구매완료') && $reviewYn['CNT'] == 0) : ?>
                            <button type="button" class="btn-member btn-sm btn-orange" onclick="writeReview('<?= $cart_item['ct_id'] ?>')">상품리뷰</button>
                        <? endif ?>
                </div>
            </td>
            <td class="order_price" style="width: 140px;">
                <!-- <?= number_format($cart_item['ct_price'] * $cart_item['ct_qty'] - $order['od_coupon'] - $order['od_cart_coupon'] - $order['od_send_coupon'] - $order['od_receipt_point'] + $order['od_send_cost'] + $order['od_send_cost2']  ) ?>원<br> -->
                <?= number_format($cart_item['ct_price'] * $cart_item['ct_qty'] - $cart_item['cp_price']   ) ?>원<br>
                <!-- <?= number_format($order['od_receipt_price']) ?>원<br> -->
                <span style="font-size: 14px; font-weight: 500; color: #a9a9a9;">(<?= number_format($cart_item['ct_qty']) ?>개)</span>
            </td>
            <td class="order_id" style="width: 220px;">
                <div style="font-size: 18px; font-weight: 500;"><?= $cart_item['ct_status'] ?></div>
                <? if(!empty($od_status_button_set[$cart_item['ct_status']])) { 
                    foreach ($od_status_button_set[$cart_item['ct_status']] as $action => $label) : ?>
                    <div style="margin-top: 12px;">
                        <button type="button" class="btn-member btn-sm btn-gray btn-round btn-order-action" data-action="<?= $action ?>" data-odid=<?= $order['od_id'] ?> data-ctid=<?= $cart_item['ct_id'] ?> data-invoice='<?= $cart_item['ct_invoice'] ?>' data-invoice-co='<?= $cart_item['ct_delivery_company'] ?>'><?= $label ?></button>
                    </div>
                <? endforeach ?>
                <? }?>
            </td>
        </tr>

        <tr class="on-small">
            <td style="padding: 8px 14px; height: auto; border: unset; font-size: 16px; border-top: 8px solid var(--very-light-pink);">
                <div style="display: flex; justify-content: space-between;">
                    <span>
                        <span><?= $cart_item['ct_status'] ?></span>
                        <span><?= date("Y.m.d", strtotime($order['od_time'])) ?></span>
                    </span>
                    <span><a href="/member/order.php?od_id=<?= $order['od_id'] ?>" style="font-size: 12px; color: #959595; font-weight: 300;">상세보기<img src="/img/re/right_gr@3x.png" style="height: 10px; vertical-align: unset; margin-left: 4px;"></a></span>
                </div>
            </td>
        </tr>

        <tr class="on-small">
            <td style="padding: 0 14px; border: unset;">
                <div style="display: flex;">
                    <div class="order_item_contents_img"><?= $od_image ?></div>
                    <div class="order_item_contents_info">
                        <a href="/member/order.php?od_id=<?= $order['od_id'] ?>">
                            <div class="order_item_contents_brand"><?= $order_item['it_brand'] ?> <span class ='hocName<?= $cart_item['io_hoching'] ?>'></span>
                                <!-- <img src="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png" srcset="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@2x.png 2x,/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png 3x"> -->
                            </div>
                            <div class="order_item_contents_name" style="width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:inline-block;"><?= $it_name ?></div>
                            <div class="order_item_contents_price">
                                <div style='font-size: 15px; font-weight: 500; margin-top:67px'><?= number_format($cart_item['ct_price'] * $cart_item['ct_qty'] - $cart_item['cp_price']) ?><span>원</span><span style='font-size: 14px';> (<?=$cart_item['ct_qty'] ?>개)</span></div>
                                <!-- <div style='font-size: 15px; font-weight: 500; margin-top:67px'><?= number_format($cart_item['ct_price'] * $cart_item['ct_qty'] - $order['od_coupon'] - $order['od_cart_coupon'] - $order['od_send_coupon'] - $order['od_receipt_point'] + $order['od_send_cost'] + $order['od_send_cost2']) ?><span>원</span></div> -->
                            </div>
                        </a>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="on-small">
            <td style="padding: 0 14px; border: unset;">
                <div style="background-color: #f2f2f2; display: flex; justify-content: space-evenly;">
                    <? if ($cart_item['ct_status'] != '주문' && $cart_item['ct_status'] != '결제완료' && $cart_item['ct_status'] != '상품준비중' && $cart_item['ct_status'] != '주문취소') :  ?>
                        <a href="/common/tracking.php?invc_co=<?= $order['od_delivery_company'] ? $order['od_delivery_company'] : $cart_item['ct_delivery_company'] ?>&invc_no=<?= $cart_item['ct_invoice'] ?>&view_popup=1" target="_blank">
                            <span class="order-action-mobile btn-order-action order-action-invoice" data-odid=<?= $order['od_id'] ?> data-ctid=<?= $cart_item['ct_id'] ?>>배송조회</span>
                        </a>
                        <span class="order-action-mobile-separator"></span>
                    <? endif ?>
                    <span class="order-action-mobile btn-order-action " data-odid=<?= $order['od_id'] ?> data-ctid=<?= $cart_item['ct_id'] ?> onclick="writeQuestion('<?= $cart_item['od_id'] ?>')">1:1문의</span>
                    <span class="order-action-mobile-separator"></span>
                    <? if (($cart_item['ct_status'] == '배송완료' || $cart_item['ct_status'] == '구매확정' || $cart_item['ct_status'] == '구매완료') && $reviewYn['CNT'] == 0) : ?>
                        <span class="order-action-mobile btn-order-action " data-odid=<?= $order['od_id'] ?> data-ctid=<?= $cart_item['ct_id'] ?> onclick="writeReview('<?= $cart_item['ct_id'] ?>')">상품리뷰</span>
                        <span class="order-action-mobile-separator"></span>
                    <? endif ?>
                </div>
            </td>
        </tr>
        <? if (isset($od_status_button_set_mobile[$cart_item['ct_status']])) : ?>
            <tr class="on-small">
                <td style="padding: 20px 14px; border: unset; height: auto; padding-top: unset;">
                    <div style="display: flex; justify-content: space-between;">
                        <? foreach ($od_status_button_set_mobile[$cart_item['ct_status']] as $action => $label) : ?>
                            <button type="button" style="font-size: 14px;" class="btn-member btn-white btn-order-action" data-action="<?= $action ?>" data-odid=<?= $order['od_id'] ?> data-ctid=<?= $cart_item['ct_id'] ?>><?= $label ?></button>
                        <? endforeach ?>
                    </div>
                </td>
            </tr>
        <? endif ?>
        <? $cnt_cart_item = 0 ?>
    <? } ?>
<? endfor ?>