<?php
ob_start();
include_once G5_LAYOUT_PATH . "/nav.member.php";
$g5_title = "마이페이지";
?>
<link rel="stylesheet" href="/re/css/event.css">

<style>
    #member-dashboard-product-wrapper {
        display: grid;
        grid-template-columns: repeat(4, calc((100% - 60px) / 4));
        grid-template-rows: 420px;
        grid-auto-columns: calc((100% - 60px) / 4);
        grid-auto-rows: 420px;
        grid-column-gap: 20px;
        grid-row-gap: 20px;
        grid-auto-flow: row;
    }

    .member-dashboard-brand-wrapper {
        display: grid;
        grid-template-columns: repeat(2, calc((100% - 20px) / 2));
        grid-template-rows: 280px;
        grid-auto-columns: calc((100% - 20px) / 2);
        grid-auto-rows: 280px;
        grid-column-gap: 20px;
        grid-row-gap: 20px;
        grid-auto-flow: row;
    }

    .member-dashboard-brand-wrapper>.brand-list-cell {
        width: 100%;
        height: 280px;
    }

    #member-dashboard-product-wrapper-mobile {
        display: grid;
        grid-template-columns: repeat(2, calc((100% - 14px) / 2));
        grid-template-rows: calc(80vw);
        grid-auto-columns: calc((100% - 14px) / 2);
        grid-auto-rows: calc(80vw);
        grid-column-gap: 14px;
        grid-row-gap: 14px;
        grid-auto-flow: row;
        padding: 0 14px;
    }

    #member-dashboard-product-wrapper-mobile>div {
        width: 100%;
        height: 100%;
    }

    #member-dashboard-product-wrapper-mobile>div>a {
        width: 100%;
        height: 100%;
    }

    #member-dashboard-product-wrapper>div>a>.product-list-item-thumb {
        width: 100%;
        height: 0;
        padding-top: 100%;
    }

    #member-dashboard-product-wrapper-mobile>div>a>.product-list-item-thumb {
        width: 100%;
        height: 0;
        padding-top: 100%;
    }

    #member-dashboard-product-wrapper-mobile>div>a>.product-list-item-thumb>.btn-pick {
        position: absolute;
        top: 0;
        right: 0;
    }

    @media (max-width: 1366px) {
        #member-dashboard-product-wrapper {
            grid-template-columns: repeat(2, calc((100% - 28px) / 2));
            grid-template-rows: 420px;
            grid-auto-columns: calc((100% - 28px) / 2);
            grid-auto-rows: 420px;
            grid-column-gap: 14px;
            grid-row-gap: 14px;
            grid-auto-flow: row;
        }
    }
</style>

<div id="member-content-wrapper" class="on-big">
    <div class="member-content-title on-big" style="display: flex; justify-content: space-between;">
        <span>최근 주문 내역</span>
        <? if ($db_recent_order->num_rows > 0) : ?>
            <span style="font-size: 16px; font-weight: 500;"><a href="/member/order.php?page=1">더보기 <img src="/img/re/bl_right.png" srcset="/img/re/bl_right@2x.png 2x,/img/re/bl_right@3x.png 3x"></a></span>
        <? endif ?>
    </div>
    <div class="member-content-section on-big">
        <div style="border-top: 3px solid #333333;" class="on-big"></div>
        <? if ($db_recent_order->num_rows > 0) : ?>
            <table>
                <tr>
                    <th style="width: 240px; padding-left: unset;">주문일</th>
                    <th colspan=2>주문 내역</th>
                    <th>주문번호</th>
                    <th>결제금액</th>
                </tr>
                <? for ($oi = 0; $order = sql_fetch_array($db_recent_order); $oi++) : ?>
                    <?php
                    $sql_cnt_cart_item = "SELECT COUNT(*) AS CNT from {$g5['g5_shop_cart_table']} WHERE od_id='{$order['od_id']}'";
                    $cnt_cart_item = sql_fetch($sql_cnt_cart_item);
                    $sql_cart_item = "SELECT ct.it_name, ct.ct_option, ct.it_id, ct.io_id, ct.ct_keep_month, ct.ct_id, ct.ct_qty, ct.ct_price, io.io_hoching FROM {$g5['g5_shop_cart_table']} AS ct LEFT JOIN {$g5['g5_shop_item_option_table']} AS io ON ct.it_id=io.it_id AND ct.io_id=io.io_id WHERE od_id='{$order['od_id']}' ORDER BY ct.io_type, ct.ct_id LIMIT 1 ";
                    $cart_item = sql_fetch($sql_cart_item);


                    $od_image =  get_it_image($cart_item['it_id'], 120, 120);

                    $it_id = get_text($cart_item['it_id']);

                    $sql_order_item = "SELECT * FROM {$g5['g5_shop_item_table']} WHERE it_id='{$it_id}' ORDER BY it_id LIMIT 1 ";
                    $order_item = sql_fetch($sql_order_item);

                    $it_name = get_text($order_item['it_name']);

                    if ($cnt_cart_item['CNT'] > 1) $it_name .= "외  " . ((int) $cnt_cart_item['CNT'] - 1) . "건";
                    ?>
                    <tr style="cursor: pointer;  height: 168px;" onclick=location.href="/member/order.php?od_id=<?= $order['od_id'] ?>">
                        <td style="width: 240px; padding-left: unset;"><?= date("Y.m.d", strtotime($order['od_time'])) ?></td>
                        <td style="width: 120px; padding-left: unset;"><?= $od_image ?></td>
                        <td class="table_item_contents" style="font-size: 18px; text-align: left;">
                            <div class="brand"><?= $order_item['it_brand'] ?>
                                <span class ='hocName<?= $row['io_hoching'] ?>'></span>
                            </div>
                            <div class="bame" style="width:420px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:inline-block;"><?= $it_name ?></div>
                            <div class="price">
                                <?= display_price(get_price($order_item), $order_item['it_tel_inq']); ?><span>원</span>
                                <?php
                                if ($order_item['it_discount_price'] != '' && $order_item['it_discount_price'] != '0') : ?>
                                    <?php
                                    $it_price = $order_item['it_price'];
                                    $it_sale_price = $order_item['it_discount_price'];
                                    $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                                    ?>
                                    <span class="price-tag"><del><?= number_format($it_price + $it_sale_price) ?></del><span>원</span></span>
                                    <span class="price-dis"><?= number_format($discount_ratio) ?>%</span>
                                <? endif ?>
                            </div>
                        </td>
                        <td style="width: 260px;" class="order_id"><?= $order['od_id'] ?></td>
                        <!-- <td style="width: 220px;" class="order_price"><?= number_format($order['od_cart_price']) ?>원</td> -->

                        <td style="width: 220px;" class="order_price"><?= number_format($cart_item['ct_price'] * $cart_item['ct_qty'] - $cart_item['cp_price']) ?>원</td>
                        <!-- <td style="width: 220px;" class="order_price"><?= number_format($cart_item['ct_price'] * $cart_item['ct_qty'] - $order['od_coupon'] - $order['od_cart_coupon'] - $order['od_send_coupon'] - $order['od_receipt_point'] + $order['od_send_cost'] + $order['od_send_cost2']) ?>원</td> -->
                        <!-- <td style="width: 220px;" class="order_price"><?= number_format($order['od_receipt_price']) ?>원</td> -->
                    </tr>
                <? endfor ?>

            </table>
        <? else : ?>
            <div class="member-no-content">
                최근 주문내역이 없습니다
            </div>
        <? endif ?>
    </div>
    <div class="member-content-title on-big" style="display: flex; justify-content: space-between;">
        <span><?= $member['mb_name'] ?>님의 위시리스트</span>
        <span style="font-size: 16px; font-weight: 500;"><a href="/member/pick.php">더보기 <img src="/img/re/bl_right.png" srcset="/img/re/bl_right@2x.png 2x,/img/re/bl_right@3x.png 3x"></a></span>
    </div>
    <div class="member-content-section">
        <div style="border-top: 3px solid #333333; margin-bottom: 20px;" class="on-big"></div>
        <? if ($cnt_pick['CNT_ITEM'] > 0) : ?>
            <div id="member-dashboard-product-wrapper">
                <? foreach ($picked['ITEM'] as $pi => $row) : ?>
                    <?php
                    $row = $picked['ITEM'][$pi];
                    $badge = new badge($row);
                    $badgeHtml = $badge->makeHtml();
                    $thumb = get_it_image_path($row['it_id'], 300, 300, '', '', stripslashes($row['it_name']));
                    ?>
                    <div style="height: 420px; float: left; <?= $pi == 3 ? "width: 280px; " : "width: 300px; padding-right: 15px;"?>">
                        <a href="/shop/item.php?it_id=<?= $row['it_id']; ?>">
                            <div class="product-list-item-thumb" data-image="<?= $thumb ?>" style="background-image: url(<?= $thumb ?>)">
                                <span class="btn-pick-heart picked" data-type="item" data-pick=<?= $row['it_id'] ?>></span>
                            </div>
                            <div class=" product-list-item-brand">
                                <?php echo empty($row['it_brand']) ? "LIFELIKE" : $row['it_brand'] ?>
                            </div>
                            <div class="product-list-item-name">
                                <?= stripslashes($row['it_name']) ?>
                            </div>
                            <div class="product-list-item-saleprice">
                                <?= display_price(get_price($row), $row['it_tel_inq']) ?><span>원</span>
                                <?php
                                if (!empty($row['it_discount_price'])) {
                                    $it_price = $row['it_price'];
                                    $it_sale_price = $row['it_discount_price'];
                                    $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                                ?>
                                    <span class="price-tag"><del><?= number_format($it_price + $it_sale_price) ?></del><span>원</span></span>
                                    <span class="price-dis">(<?= number_format($discount_ratio) ?>%)</span>
                                <?
                                }
                                ?>
                            </div>
                            <div class="product-list-item-label"><?= $badgeHtml->html ?></div>
                        </a>
                    </div>
                    <? if ($pi == 3) break; ?>
                <? endforeach ?>
            </div>
        <? else : ?>
            <div class="notice-more-pick" style="padding-bottom: 60px;">
                <div>위시리스트에 담긴 상품이 없습니다.</div>
            </div>
        <? endif ?>
    </div>
</div>
<div class="member-dashboard-pick-title" style="border-bottom: 1px solid #333333; margin: 20px 0;"></div>
<div id="nav-small-member-wrapper" class="on-small" style="width:100%">
    <div class="order_list">최근 주문 내역</div>
    <? for ($moi = 0; $morder = sql_fetch_array($db_recent_morder); $moi++) : ?>

        <?php
        $msql_cnt_cart_item = "SELECT COUNT(*) AS CNT from {$g5['g5_shop_cart_table']} WHERE od_id='{$morder['od_id']}'";
        $mcnt_cart_item = sql_fetch($msql_cnt_cart_item);
        $msql_cart_item = "SELECT ct.it_name, ct.ct_option, ct.it_id, ct.io_id, ct.ct_keep_month, ct.ct_id,ct.ct_price,ct.ct_qty, io.io_hoching FROM {$g5['g5_shop_cart_table']} AS ct LEFT JOIN {$g5['g5_shop_item_option_table']} AS io ON ct.it_id=io.it_id AND ct.io_id=io.io_id WHERE od_id='{$morder['od_id']}' ORDER BY ct.io_type, ct.ct_id LIMIT 1 ";
        $mcart_item = sql_fetch($msql_cart_item);
        $mod_image = get_it_image($mcart_item['it_id'], 75, 75);
        $mit_id = get_text($mcart_item['it_id']);
        // $msql_order_item = "SELECT * FROM {$g5['g5_shop_item_table']} WHERE it_id='{$mit_id}' AND it_use = 1  ORDER BY it_id LIMIT 1 ";
        $msql_order_item = "SELECT * FROM {$g5['g5_shop_item_table']} WHERE it_id='{$mit_id}' ORDER BY it_id LIMIT 1 ";
        $morder_item = sql_fetch($msql_order_item);
        $mit_name = get_text($morder_item['it_name']);

        if ($mcnt_cart_item['CNT'] > 1) $mit_name .= "외  " . ((int) $mcnt_cart_item['CNT'] - 1) . "건";
        ?>
        <div class="order_item_contents_wapper" id="order_item_contents_wapper_<?= $moi + 1 ?>" style="cursor: pointer; margin: 0 14px; " onclick=location.href="/member/order.php?od_id=<?= $morder['od_id'] ?>">
            <div class="order_item_contents">
                <div class="order_item_contents_img"><?= $mod_image ?></div>
                <div class="order_item_contents_info">
                    <div class="order_item_contents_brand"><?= $morder_item['it_brand'] ?>
                        <span class ='hocName<?= $mcart_item['io_hoching'] ?>'></span>
                    </div>
                    <div class="order_item_contents_name" style="width:250px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:inline-block;"><?= $mit_name ?></div>
                    <div class="order_item_contents_price">
                        <div style='font-size: 15px; font-weight: 500; margin-top:22px'><?= number_format($mcart_item['ct_price'] * $mcart_item['ct_qty'] - $mcart_item['cp_price']) ?><span>원</span></div>
                        <!-- <div style='font-size: 15px; font-weight: 500; margin-top:22px'><?= number_format($mcart_item['ct_price'] * $mcart_item['ct_qty'] - $morder['od_coupon'] - $morder['od_cart_coupon'] - $morder['od_send_coupon'] - $morder['od_receipt_point'] + $morder['od_send_cost'] + $morder['od_send_cost2']) ?><span>원</span></div> -->
                    </div>
                </div>
            </div>
        </div>
    <? endfor ?>

    <div class="area_gubun_line"></div>
    <div class="mypage_contents_group">
        <div class="order_list">주문내역</div>
        <div class="more_list"><a href="/member/order.php">상세보기&nbsp;<img src="/img/re/bl_right.png" srcset="/img/re/bl_right@2x.png 2x,/img/re/bl_right@3x.png 3x"></a></div>
    </div>
    <ul class="order_list_contents_ul">
        <li>
            <div><?= $cnt_order1['cnt'] ?></div>
            결제완료
        </li>
        <li class="dot">...</li>
        <li>
            <div><?= $cnt_order2['cnt'] ?></div>
            배송준비중
        </li>
        <li class="dot">...</li>
        <li>
            <div><?= $cnt_order3['cnt'] ?></div>
            배송중
        </li>
        <li class="dot">...</li>
        <li>
            <div><?= $cnt_order4['cnt'] ?></div>
            배송완료
        </li>
    </ul>

    <div class="area_gubun_line"></div>
    <div class="mypage_contents_group">
        <div class="order_list">취소/반품/교환</div>
        <div class="more_list"><a href="/member/order.cancel.php">상세보기&nbsp;<img src="/img/re/bl_right.png" srcset="/img/re/bl_right@2x.png 2x,/img/re/bl_right@3x.png 3x"></a></div>
    </div>
    <div class="area_gubun_line"></div>
    <div>
        <div class="mypage_contents_group">
            <div class="order_list">위시리스트</div>
            <div class="more_list"><a href="/member/pick.php">상세보기&nbsp;<img src="/img/re/bl_right.png" srcset="/img/re/bl_right@2x.png 2x,/img/re/bl_right@3x.png 3x"></a></div>
        </div>
        <? if ($cnt_pick['CNT_ITEM'] > 0) : ?>
            <div id="member-dashboard-product-wrapper-mobile" class="m_pick_list product-list">
                <? foreach ($picked['ITEM'] as $pi => $row) : ?>
                    <?php
                    $row = $picked['ITEM'][$pi];
                    $badge = new badge($row);
                    $badgeHtml = $badge->makeHtml();
                    $thumb = get_it_image_path($row['it_id'], 500, 500, '', '', stripslashes($row['it_name']));
                    ?>
                    <div>
                        <a href="/shop/item.php?it_id=<?= $row['it_id']; ?>">
                            <div class="product-list-item-thumb" data-image="<?= $thumb ?>" style="background-image: url(<?= $thumb ?>)"></div>
                            <div class=" product-list-item-brand">
                                <?php echo empty($row['it_brand']) ? "LIFELIKE" : $row['it_brand'] ?>
                            </div>
                            <div class="product-list-item-name">
                                <?= stripslashes($row['it_name']) ?>
                            </div>
                            <div class="product-list-item-saleprice">
                                <?= display_price(get_price($row), $row['it_tel_inq']) ?><span>원</span>
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
                            <div class="product-list-item-label"><?= $badgeHtml->html ?></div>
                        </a>
                    </div>
                    <? if ($pi == 3) break; ?>
                <? endforeach ?>
            </div>
        <? else : ?>
            <div class="notice-more-pick" style="padding-bottom: 60px;">
                <div>위시리스트에 담긴 상품이 없습니다.</div>
            </div>
        <? endif ?>

    </div>

    <div class="area_gubun_line"></div>

    <div class="mypage_footer_btn_group">
        <div class="btn_box">
            <a href="/member/review.php">
                상품리뷰
                <span class="review_count"><?= $review_count ?></span>

            </a>
        </div>

        <div class="btn_box"><a href="/member/customer.php">1:1문의</a></div>
        <div class="btn_box"><a href="/member/qna.php">Q&A</a></div>
    </div>
</div>
</div>

<!-- nav.member.php end -->
</div>

<?php
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>