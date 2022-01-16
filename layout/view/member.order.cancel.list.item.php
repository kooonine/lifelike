<? for ($oi = 0; $claim = sql_fetch_array($db_order_claim); $oi++) : ?>
    <?php
    if ($claim['ct_id'] == "0") {
        $sql_ct_parted = "SELECT GROUP_CONCAT(sh.ct_id) AS ct_id_parted FROM lt_shop_order_history AS sh WHERE sh.od_id='{$claim['od_id']}' AND sh.ct_id != 0";
        $db_ct_parted = sql_fetch($sql_ct_parted);

        $sql_order_cart = "SELECT ct.*,it.*,io.io_hoching,od.od_pickup_delivery_company,od.od_pickup_invoice,od_cancel_price,od_refund_price FROM lt_shop_cart AS ct
                        LEFT JOIN lt_shop_item AS it ON ct.it_id=it.it_id
                        LEFT JOIN lt_shop_item_option AS io ON ct.it_id=io.it_id AND ct.io_id=io.io_id
                        LEFT JOIN lt_shop_order AS od ON ct.od_id=od.od_id
                        WHERE ct.od_id='{$claim['od_id']}'";
        if (!empty($db_ct_parted['ct_id_parted'])) {
            $sql_order_cart .= " AND ct.ct_id NOT IN ({$db_ct_parted['ct_id_parted']})";
        }
        $sql_order_cart .= " GROUP BY ct.it_id";
    } else {
        // $sql_order_cart = "SELECT it.*,ct.*,(SELECT it_brand FROM lt_shop_item AS si WHERE si.it_id=ct.it_id) FROM lt_shop_cart AS ct LEFT JOIN lt_shop_order_item AS it ON ct.it_id=it.it_id WHERE ct.od_id='{$claim['od_id']}' AND ct.ct_id='{$claim['ct_id']}' GROUP BY ct.it_id";
        $sql_order_cart = "SELECT ct.*,it.*,io.io_hoching,od.od_pickup_delivery_company,od.od_pickup_invoice,od_cancel_price,od_refund_price FROM lt_shop_cart AS ct
                        LEFT JOIN lt_shop_item AS it ON ct.it_id=it.it_id
                        LEFT JOIN lt_shop_item_option AS io ON ct.it_id=io.it_id AND ct.io_id=io.io_id
                        LEFT JOIN lt_shop_order AS od ON ct.od_id=od.od_id
                        WHERE ct.od_id='{$claim['od_id']}' AND ct.ct_id='{$claim['ct_id']}' GROUP BY ct.it_id";
    }

    $db_order_cart = sql_query($sql_order_cart);
    $cnt_cart_item = $db_order_cart->num_rows;

    while (false != ($cart_item = sql_fetch_array($db_order_cart))) {
        $od_image =  get_it_image($cart_item['it_id'], 120, 120);
        $it_name = get_text($cart_item['it_name']);
        $it_id = get_text($cart_item['it_id']);
    ?>
        <tr class="on-big" style="cursor: pointer;  height: 168px;">
            <? if ($cnt_cart_item > 0) : ?>
                <td rowspan=<?= $cnt_cart_item ?> style="width: 240px;" onclick="location.href='/member/order.php?od_id=<?= $claim['od_id'] ?>'">
                    <span style="border-bottom: 1px solid #333333;"><?= $claim['od_id'] ?></span><br>
                    <span style="font-size: 14px; font-weight: 500; color: #9f9f9f;">(<?= date("Y.m.d", strtotime($claim['od_time'])) ?>)</span>
                </td>
            <? endif ?>
            <td style="text-align: left; padding: 58px 0; width: 136px;" onclick="location.href='/member/order.php?od_id=<?= $claim['od_id'] ?>'"><?= $od_image ?></td>
            <td class="table_item_contents" style="font-size: 18px; text-align: left; padding: 58px 0;">
                <div class="brand"><?= $cart_item['it_brand'] ?>
                    <img style="height: 14px; width: 14px;" src="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png" srcset="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@2x.png 2x,/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png 3x">
                </div>
                <div class="name" style="margin: 5px 0 8px 0;" onclick="location.href='/member/order.php?od_id=<?= $claim['od_id'] ?>'"><?= $it_name ?></div>
                <div class="price">
                    <?= display_price(get_price($cart_item), $cart_item['it_tel_inq']) ?><span>원</span>
                    <?php
                    $it_price = $cart_item['it_price'];
                    $it_sale_price = $cart_item['it_discount_price'];
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
                    <button type="button" class="btn-member btn-sm btn-gray">1:1문의</button>
                    <? if ($cart_item['ct_status'] == '배송완료' || $cart_item['ct_status'] == '구매확정' || $cart_item['ct_status'] == '구매완료') : ?>
                        <button type="button" class="btn-member btn-sm btn-orange">상품리뷰</button>
                    <? endif ?>
                </div>
            </td>
            <td class="order_price" style="width: 140px;">
                <?= number_format($cart_item['ct_price'] * $cart_item['ct_qty'] - $cart_item['cp_price']) ?>원<br>
                <span style="font-size: 14px; font-weight: 500; color: #a9a9a9;">(<?= number_format($cart_item['ct_qty']) ?>개)</span>
            </td>
            <td class="order_id" style="width: 220px;">
                <div style="font-size: 18px; font-weight: 500;"><?= $cart_item['ct_status'] ?></div>
                <? if(!empty($od_status_button_set[$cart_item['ct_status']])) { 
                    foreach ($od_status_button_set[$cart_item['ct_status']] as $action => $label) : ?>
                    <div style="margin-top: 12px;">
                        <button type="button" class="btn-member btn-sm btn-gray btn-round btn-order-action" data-action="<?= $action ?>" data-odid=<?= $claim['od_id'] ?> data-ctid=<?= $cart_item['ct_id'] ?>><?= $label ?></button>
                    </div>
                    <? endforeach ?>
                <? } ?>
            </td>
        </tr>

        <? if ($cnt_cart_item > 0) : ?>
            <tr class="on-small">
                <td style="padding: 8px 14px; height: auto; border: unset; font-size: 16px; border-top: 8px solid var(--very-light-pink);">
                    <div style="display: flex; justify-content: space-between;">
                        <span>
                            <span><?= $claim['od_status'] ?></span>
                            <span><?= date("Y.m.d", strtotime($claim['od_time'])) ?></span>
                        </span>
                        <span><a href="/member/order.php?od_id=<?= $claim['od_id'] ?>" style="font-size: 12px; color: #959595; font-weight: 300;">상세보기<img src="/img/re/right_gr@3x.png" style="height: 10px; vertical-align: unset; margin-left: 4px;"></a></span>
                    </div>
                </td>
            </tr>
        <? else : ?>
            <tr class="on-small">
                <td style="padding: 20px 14px; border: unset; height: auto; padding-top: unset;">
                    <div style=" border-top: 1px solid var(--very-light-pink);"></div>
                </td>
            </tr>
        <? endif ?>
        <tr class="on-small">
            <td style="padding: 0 14px; border: unset;">
                <div style="display: flex;">
                    <div class="order_item_contents_img"><?= $od_image ?></div>
                    <div class="order_item_contents_info">
                        <div class="order_item_contents_brand"><?= $cart_item['it_brand'] ?>
                            <img src="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png" srcset="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@2x.png 2x,/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png 3x">
                        </div>
                        <div class="order_item_contents_name"><?= $it_name ?></div>
                        <div class="order_item_contents_price">
                            <div style='font-size: 15px; font-weight: 500; margin-top:67px'>
                            <!-- <?= number_format($cart_item['ct_price'] * $cart_item['ct_qty'] - $order['od_coupon'] - $order['od_cart_coupon'] - $order['od_send_coupon'] - $order['od_receipt_point'] + $order['od_send_cost'] + $order['od_send_cost2']) ?><span>원</span> -->
                            <!-- <?= number_format($cart_item['od_cancel_price']) ?><span>원</span>  -->
                            <? if ($cart_item['od_cancel_price'] > 0) : ?>
                                <?= number_format($cart_item['od_cancel_price']) ?><span>원</span> 
                            <? elseif($cart_item['ct_status']=='반품요청'): ?>
                            <? else : ?>
                                <?= number_format($cart_item['od_refund_price']) ?><span>원</span> 
                            <? endif ?>
                        </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="on-small">
            <td style="padding: 0 14px; border: unset;">
                <div style="background-color: #f2f2f2; display: flex; justify-content: space-evenly;">
                    <? if ($cart_item['ct_status'] != '주문' && $cart_item['ct_status'] != '결제완료' && $cart_item['ct_status'] != '상품준비중' && $cart_item['ct_status'] != '주문취소') :  ?>
                        <a href="/common/tracking.php?invc_co=<?= $claim["od_delivery_company"] ?>&invc_no=<?= $claim["od_invoice"] ?>&view_popup=1" target="_blank">
                            <span class="order-action-mobile btn-order-action order-action-invoice" data-odid=<?= $claim['od_id'] ?> data-ctid=<?= $cart_item['ct_id'] ?>>배송조회</span>
                        </a>
                        <span class="order-action-mobile-separator"></span>
                    <? endif ?>
                    <span class="order-action-mobile btn-order-action " data-odid=<?= $claim['od_id'] ?> data-ctid=<?= $cart_item['ct_id'] ?>>1:1문의</span>
                    <span class="order-action-mobile-separator"></span>
                    <? if ($cart_item['ct_status'] == '배송완료' || $cart_item['ct_status'] == '구매확정') : ?>
                        <span class="order-action-mobile btn-order-action " data-odid=<?= $claim['od_id'] ?> data-ctid=<?= $cart_item['ct_id'] ?>>상품리뷰</span>
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
                            <button type="button" style="font-size: 14px;" class="btn-member btn-white btn-order-action" data-action="<?= $action ?>" data-odid=<?= $claim['od_id'] ?> data-ctid=<?= $cart_item['ct_id'] ?>><?= $label ?></button>
                        <? endforeach ?>
                    </div>
                </td>
            </tr>
        <? endif ?>
        <? $cnt_cart_item = 0 ?>
    <? } ?>
<? endfor ?>