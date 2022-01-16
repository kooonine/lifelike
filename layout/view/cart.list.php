<?php
ob_start();
$g5_title = "장바구니";
?>
<!-- 장바구니 시작 { -->
<link rel="stylesheet" href="/re/css/shop.css">
<script src="<?= G5_JS_URL; ?>/shop.js"></script>
<script src="<?= G5_JS_URL; ?>/shop.override.js"></script>

<!-- 컨텐츠 시작 -->
<style>
    .layout-offset {
        padding-left: 0;
        padding-right: 0;
    }

    .cart-product-brand {
        font-size: 16px;
        font-weight: 600;
        color: #7f7f7f;
    }

    .cart-product-name {
        font-size: 18px;
        font-weight: 500;
        color: #000000;
    }

    .cart-product-price {
        font-size: 20px;
        font-weight: bold;
        color: #00bbb4;
    }

    .cart-product-discount {
        font-size: 16px;
        font-weight: 600;
        color: #7f7f7f;
    }

    .cart-product-price>span {
        font-size: 12px;
    }

    .cart-product-discount>span {
        font-size: 14px;
    }

    .cart-product-discount-ratio {
        font-size: 16px !important;
        font-weight: 600;
        color: #e65026;
    }

    .cart-step-title {
        font-size: 26px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: 25px;
        letter-spacing: normal;
        text-align: center;
        color: #0f0f0f;
    }

    .icon-cart-step {
        display: inline-block;
        width: 47px;
        height: 47px;
        background-color: #999999;
        font-size: 26px;
        font-weight: 300;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: center;
        color: #ffffff;
        border-radius: 47px;
        line-height: 54px;
    }

    .icon-cart-step.active {
        background-color: #333333;
    }

    .icon-cart-step>span {
        display: inline-block;
        width: 27px;
        height: 27px;
        background: url(/img/re/iconset_cart.png) left center no-repeat;
        background-size: cover;
    }

    .icon-cart-step.step-2>span {
        background-position-x: -27px;
    }

    .icon-cart-step.step-3>span {
        background-position-x: -54px;
    }

    .label-cart-step {
        font-size: 14px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: center;
        color: #999999;
    }

    .label-cart-step.active {
        color: #333333;
    }

    .swiper_item_detail {
        margin-top: unset;
    }

    #table-cart-calc {
        margin-top: 100px;
        border-top-width: 1px;
    }

    #table-cart-calc>tbody>tr>th {
        border: 3px solid #333333;
        border-width: 0;
        vertical-align: bottom;
        font-size: 18px;
        color: #656565;
    }

    #table-cart-calc>tbody>tr>td {
        height: auto;
        padding: 8px 0 40px 0;
        border-bottom: 3px solid #333333;
        font-size: 24px;
        font-weight: 600;
        color: #333333;
    }

    .btn-cart-action-mobile {
        width: 18px;
        height: 18px;
        background: url(/img/re/heart@3x.png) center center no-repeat;
        background-size: 18px;
        border: unset;
        float: right;
    }

    .btn-cart-action-mobile.cart-action-remove {
        background: url(/img/re/x_gr@3x.png) center center no-repeat;
        background-size: 24px;
    }

    .btn-cart-action-mobile.cart-action-wish.picked {
        background-image: url(/img/re/picked_heart@3x.png);
    }
</style>
<div id="list-wrapper">
    <div class="layout-offset">
        <div class="on-big" style="margin-bottom: 40px;">
            <div class="cart-step-title">장바구니</div>
            <div style="text-align: center; font-size: 0; margin-top: 32px; margin-bottom: 8px;">
                <span class="icon-cart-step step-1 active"><span></span></span>
                <span class="icon-cart-step step-2" style="margin: 0 94px;"><span></span></span>
                <span class="icon-cart-step step-3"><span></span></span>
            </div>
            <div style="text-align: center; font-size: 0;">
                <span class="label-cart-step active">장바구니</span>
                <span class="label-cart-step" style="margin: 0 96px;">주문서</span>
                <span class="label-cart-step">주문완료</span>
            </div>
        </div>

        <div id="cart-list">
            <div class="cart-list-wrapper">
                <? if ($result->num_rows > 0) : ?>
                    <form name="frmcartlist" id="sod_bsk_list" class="2017_renewal_itemform" method="post" action="<?= $cart_action_url; ?>">
                        <input type="hidden" name="od_type" value="<?= $od_type ?>" />
                        <table>
                            <tr class="on-big">
                                <th colspan=2 style="width: 170px; text-align: left; padding-left: 14px;">
                                    <? if ($cart_count) : ?>
                                        <div class="custom-checkbox check">
                                            <input type="checkbox" class="custom-control-input ct_all" style="padding-left: 6px;" name="ct_all" value="1" id="ct_all" checked>
                                            <label class="custom-control-label" for="ct_all" style="font-size: 16px; font-weight: 500; color: #333333; padding-left: 8px;">전체선택<span id="count-checked">(<?= $cart_count ?>/<?= $cart_count ?>)</span></label>
                                        </div>
                                    <? endif ?>
                                </th>
                                <th>상품 정보</th>
                                <th style="width: 220px; padding-left: 20px;">수량</th>
                                <th style="width: 220px; padding-left: 20px;">주문금액</th>
                                <th style="width: 220px; padding-left: 20px;">선택</th>
                            </tr>
                            <tr class="on-small">
                                <td colspan=2 style="height: 38px; background-color: #f2f2f2; border-bottom: unset;">
                                    <div class="custom-checkbox check" style="text-align: left; padding: 0 14px; font-size: 12px; color: #777777; font-weight: normal;">
                                        <input type="checkbox" class="custom-control-input mo_ct_all" name="mo_ct_all" value="1" id="mo_ct_all" checked>
                                        <label class="custom-control-label" for="mo_ct_all">전체선택<span id="count-checked-mobile">(<?= $cart_count ?>/<?= $cart_count ?>)</label>
                                        <span style="float: right; font-size: 12px; font-weight: normal; color: #565656; border-bottom: 1px solid #565656;" onclick="return form_check('seldelete', true);">선택삭제</span>
                                    </div>
                                </td>
                            </tr>
                            <?php
                            $tot_point = 0;
                            $tot_sell_price = 0;
                            $tot_sell_rental_price = 0;
                            $tot_sell_rental_price_all = 0;
                            $tot_discount_price = 0;
                            $it_send_cost = 0;
                            $image_width = 120;
                            $image_height = 120;

                            for ($i = 0; $row = sql_fetch_array($result); $i++) {
                                // 합계금액 계산
                                $sql = " select SUM((a.ct_price + a.io_price) * a.ct_qty) as price,
					SUM((b.its_price + a.io_price) * a.ct_qty) as before_price,
					SUM((a.ct_rental_price + a.io_price) * a.ct_qty) as rental_price,
					SUM(a.ct_point * a.ct_qty) as point,
					SUM(a.ct_qty) as qty
					from   lt_shop_cart as a
					inner join lt_shop_item_sub as b on a.it_id = b.it_id and a.its_no = b.its_no
					where  a.ct_id = '{$row['ct_id']}'
					and    a.od_id = '$s_cart_id' ";
                                $sum = sql_fetch($sql);
                                if ($i == 0) {
                                    // 계속쇼핑
                                    $continue_ca_id = $row['ca_id'];
                                }
                                $image = get_it_image($row['it_id'], $image_width, $image_height);
                                $image_mo = $image;

                                $price_plus = '';
                                if ($row['io_price'] >= 0) {
                                    $price_plus = '+';
                                }
                                $it_options = get_text($row['ct_option']) . ' / ' . $row['ct_qty'] . '개' . PHP_EOL;
                                $point      = $sum['point'];
                                $sell_price = $sum['price'];
                                $sell_rental_price = $sum['rental_price'];

                                $it_price = $row['ct_price'];
                                $it_sale_price = $row['it_discount_price'];
                                $it_discount_ratio = 0;

                                if ($row['it_item_type'] == "1") $it_price = $row['ct_rental_price'];

                                if (!empty($it_sale_price)) {
                                    $it_discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                                }
                            ?>
                                <tr class="on-big">
                                    <td class="chk_order" style="width: 50px; padding-left: 6px;">
                                        <? if ($config['cf_use_point'] && $sum['point']) : ?>
                                            <input type="hidden" name="it_point[<?= $i; ?>]" value="<?= $sum['point']; ?>">
                                        <? endif ?>
                                        <input type="hidden" name="nexi[]" value="<?= $i; ?>">
                                        <input type="hidden" name="ct_id[<?= $i; ?>]" value="<?= $row['ct_id']; ?>">
                                        <input type="hidden" name="it_id[<?= $i; ?>]" value="<?= $row['it_id']; ?>">
                                        <input type="hidden" name="it_name[<?= $i; ?>]" value="<?= get_text($row['it_name']); ?>">
                                        <input type="hidden" name="it_send_cost[<?= $i; ?>]" value="<?= $it_send_cost ?>">
                                        <input type="hidden" name="it_price[<?= $i; ?>]" class="it_price" value="<?= $it_price ?>">
                                        <input type="hidden" name="it_discount[<?= $i; ?>]" class="it_discount" value="<?= $it_price + $it_sale_price ?>">
                                        <input type="hidden" name="io_price[<?= $i; ?>]" class="io_price" value="<?= $row['io_price'] ?>">
                                        <input type="hidden" name="io_type" value="<?= $row['io_type'] ?>">
                                        <div class="custom-checkbox check">
                                            <? if ($row['it_soldout'] == 0) : ?>
                                                <input type="checkbox" class="custom-control-input ct_chk" name="ct_chk[<?= $i; ?>]" value="1" id="ct_chk_<?= $i; ?>" checked>
                                            <? else : ?>
                                                <input type="checkbox" class="custom-control-input ct_chk" name="ct_chk[<?= $i; ?>]" value="1" id="ct_chk_<?= $i; ?>" disabled>
                                            <? endif ?>
                                            <label class="custom-control-label" for="ct_chk_<?= $i; ?>"></label>
                                        </div>
                                    </td>
                                    <td style="width: 120px; text-align: left;">
                                        <a href="/shop/item.php?it_id=<?= $row['it_id'] ?>"><?= $image; ?> </a>
                                    </td>
                                    <td class="cart-product-info">
                                        <a href="/shop/item.php?it_id=<?= $row['it_id'] ?>">
                                            <div class="swiper_item_detail">
                                                <div class="swiper_item_brand"><?= $row['it_brand'] ?> <span class ='hocName<?= $row['io_hoching'] ?>'></span></div>
                                                <div class="swiper_item_name"><?= $row['it_name'] ?></div>
                                                <div class="swiper_item_price_area">
                                                    <span><?= number_format($it_price) ?><span style="font-size: 12px;">원</span></span>
                                                    <? if ($it_discount_ratio > 0) : ?>
                                                        <span class="price-del"><del><?= number_format($it_price + $it_sale_price) ?></del>원</span>
                                                        <span class="price-dis" style="color: #e65026;"><?= number_format($it_discount_ratio) ?>%</span>
                                                    <? endif ?>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <? if ($row['it_soldout'] == 0) : ?>
                                        <td style="padding-left: 20px;">
                                            <div class="count-control">
                                                <button type="button" class="count-minus"></button>
                                                <input type="text" name="ct_qty[<?= $row['ct_id'] ?>]" value="<?= $row['ct_qty'] ?>" readonly>
                                                <button type="button" class="count-plus"></button>
                                            </div>
                                        <td style="padding-left: 20px;">
                                            <div><span class="cart-item-price-total-<?= $i ?>"><?= number_format($sell_price) ?></span><span>원</span></div>
                                            <div>
                                                <button type="button" class="btn btn-cart" ct_id="<?= $row['ct_id'] ?>" style="margin-top: 16px;" onclick="buyOne(<?= $i ?>,<?= $row['ct_id'] ?>)">바로구매</button>
                                            </div>
                                        </td>
                                    <? else : ?>
                                        <td style="padding-left: 20px;">
                                            <div class="count-control">
                                                <button type="button" class="count-minus" disabled><span class="blind">감소</span></button>
                                                <input type="text" name="ct_qty[<?= $row['ct_id'] ?>]" value="<?= $row['ct_qty'] ?>" readonly disabled>
                                                <button type="button" class="count-plus" disabled><span class="blind">증가</span></button>
                                            </div>
                                        <td style="padding-left: 20px;">
                                            <div><span class="cart-item-price-total-<?= $i ?>"><?= number_format($sell_price) ?></span><span>원</span></div>
                                            <div>
                                                <button type="button" class="btn btn-cart-action btn-toggle-instock" data-mb_id="<?= $member['mb_id'] ?>" data-brand="<?= $row['it_brand'] ?>" data-item="<?= $row['it_id'] ?>" data-name="<?= $row['it_name'] ?>" style="margin-top: 16px; border-radius: 2px;">재입고알림</button>
                                            </div>
                                        </td>
                                    <? endif ?>
                                    <td style="padding-left: 20px; line-height: 52px;">
                                        <button type="button" class="btn btn-cart-action cart-action-wish" data-type="item" data-pick="<?= $row['it_id'] ?>">위시리스트</button>
                                        <button type="button" class="btn btn-cart-action cart-action-remove" onclick="removeOne(<?= $i ?>)">삭제</button>
                                    </td>
                                </tr>
                                <tr class="on-small">
                                    <td style="width: 75px; padding-left: 14px; height: 55px;">
                                        <div class="custom-checkbox check">
                                            <? if ($row['it_soldout'] == 0) : ?>
                                                <input type="checkbox" class="custom-control-input mo_ct_chk" data-idx=<?= $i; ?> name="mo_ct_chk[<?= $i; ?>]" value="1" id="mo_ct_chk_<?= $i; ?>" checked>
                                            <? else : ?>
                                                <input type="checkbox" class="custom-control-input mo_ct_chk" data-idx=<?= $i; ?> name="mo_ct_chk[<?= $i; ?>]" value="1" id="mo_ct_chk_<?= $i; ?>" disabled>
                                            <? endif ?>
                                            <!-- <input type="checkbox" class="custom-control-input mo_ct_chk" data-idx=<?= $i; ?> name="mo_ct_chk[<?= $i; ?>]" value="1" id="mo_ct_chk_<?= $i; ?>" <?= $row['it_soldout'] == 1 ? "disabled" : "checked" ?>> -->
                                            <label class="custom-control-label" for="mo_ct_chk_<?= $i; ?>"></label>
                                        </div>
                                    </td>
                                    <td style="padding-right: 14px; height: 55px;">
                                        <button type="button" class="btn-cart-action-mobile cart-action-remove" onclick="removeOne(<?= $i ?>)"></button>
                                        <button type="button" class="btn-cart-action-mobile cart-action-wish" data-type="item" data-pick="<?= $row['it_id'] ?>" style="margin-right: 16px;"></button>
                                    </td>
                                </tr>
                                <tr class="on-small">
                                    <td class="cart-image-mobile" onclick="location.href='/shop/item.php?it_id=<?= $row['it_id'] ?>'">
                                        <?= $image_mo; ?>
                                    </td>
                                    <td class=" cart-product-info" style="padding-left: 8px">
                                        <div class="swiper-contents-item-group">
                                            <div class="swiper-contents-item-brand"><?= $row['it_brand'] ?> <span class ='hocName<?= $row['io_hoching'] ?>'></span></div>
                                            <a href="/shop/item.php?it_id=<?= $row['it_id'] ?>">
                                                <div class="swiper-contents-item-name" style="width: 220px;  overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?= $row['it_name'] ?> 글자수 줄이기 !!!</div>
                                            </a>
                                            <span><?= number_format($it_price) ?><span style="font-size: 12px;">원</span></span>
                                            <? if ($it_discount_ratio > 0) : ?>
                                                <div class="swiper-contents-item-price price-tag">
                                                    <span class="price-del"><del><?= number_format($it_price + $it_sale_price) ?></del>원</span>
                                                    <span class="price-dis" style="color: #e65026;"><?= number_format($it_discount_ratio) ?>%</span>
                                                </div>
                                            <? endif ?>
                                        </div>
                                    </td>
                                </tr>

                                <? if ($row['it_soldout'] == 0) : ?>
                                    <tr class="on-small">
                                        <td style="height: 40px; padding-top: 8px; border-bottom: unset; font-size: 12px; font-weight: normal; padding-left: 14px;">주문수량</td>
                                        <td style="height: 40px; padding-top: 8px; border-bottom: unset; text-align: right; padding-right: 14px;">
                                            <div class="count-control">
                                                <button type="button" class="count-minus is-small" style="padding: unset;"></button>
                                                <input type="text" data-target="ct_qty[<?= $row['ct_id'] ?>]" value="<?= $row['ct_qty'] ?>" readonly>
                                                <button type="button" class="count-plus is-small" style="padding: unset;"></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="on-small">
                                        <td style="height: 40px; border-bottom: unset; font-size: 12px; font-weight: normal; padding-left: 14px;">주문금액</td>
                                        <td style="height: 40px; border-bottom: unset; text-align: right; padding-right: 14px; font-size: 14px; font-weight: 600;">
                                            <span class="cart-item-price-total-<?= $i ?>"><?= number_format($sell_price) ?></span>원
                                        </td>
                                    </tr>
                                    <tr class="on-small" style="height: auto;">
                                        <td colspan=2 style="height: 64px; text-align: center; border-bottom: 8px solid var(--very-light-pink); padding: 0 14px 20px 14px;">
                                            <button type="button" class="btn btn-cart-action" style="width: 100%; font-size: 14px; border-radius: 2px;" ct_id="<?= $row['ct_id'] ?>" onclick="buyOne(<?= $i ?>,<?= $row['ct_id'] ?>)">바로구매</button>
                                        </td>
                                    </tr>
                                <? else : ?>
                                    <tr class="on-small">
                                        <td style="height: 40px; padding-top: 8px; border-bottom: unset; font-size: 12px; font-weight: normal; padding-left: 14px; color: #999999;">주문수량</td>
                                        <td style="height: 40px; padding-top: 8px; border-bottom: unset; text-align: right; padding-right: 14px;">
                                            <div class="count-control">
                                                <button type="button" class="count-minus is-small" style="padding: unset;" disabled><span class="blind">감소</span></button>
                                                <input type="text" data-target="ct_qty[<?= $row['ct_id'] ?>]" value="<?= $row['ct_qty'] ?>" readonly disabled>
                                                <button type="button" class="count-plus is-small" style="padding: unset;" disabled><span class="blind">증가</span></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="on-small">
                                        <td style="height: 40px; border-bottom: unset; font-size: 12px; font-weight: normal; padding-left: 14px; color: #999999;">주문금액</td>
                                        <td style="height: 40px; border-bottom: unset; text-align: right; padding-right: 14px; font-size: 14px; font-weight: 600; color: #999999;">
                                            <span class="cart-item-price-total-<?= $i ?>"><?= number_format($sell_price) ?></span>원
                                        </td>
                                    </tr>
                                    <tr class="on-small" style="height: auto;">
                                        <td colspan=2 style="height: 64px; text-align: center; border-bottom: 8px solid var(--very-light-pink); padding: 0 14px 20px 14px;">
                                            <button type="button" class="btn btn-cart-action btn-toggle-instock" data-mb_id="<?= $member['mb_id'] ?>" data-screen="mobile" data-brand="<?= $row['it_brand'] ?>" data-item="<?= $row['it_id'] ?>" data-name="<?= $row['it_name'] ?>" style="width: 100%; font-size: 14px; border-radius: 2px;" ct_id="<?= $row['ct_id'] ?>">재입고알림</button>
                                        </td>
                                    </tr>
                                <? endif; ?>
                            <?
                                $tot_point      += $point;
                                $tot_sell_price += $sell_price;
                                $tot_discount_price += $it_discount;
                                $tot_sell_rental_price += $sell_rental_price;
                                $tot_sell_rental_price_all += $sell_rental_price * (int) $row['ct_item_rental_month'];
                            } // for 끝
                            $send_cost = get_sendcost($s_cart_id, 0);
                            ?>
                            <tr class="on-big">
                                <td colspan=5 style="height: auto; font-size: 12px; font-weight: normal; line-height: 1.5; color: #333333; text-align: left; padding: 20px 0 0 0; vertical-align: top; border: none;">
                                    <div><span style="display: inline-block; width: 3px; height: 3px; border-radius: 3px; border: 1px solid #333333; margin-right: 4px; vertical-align: middle;"></span>장바구니 상품은 30일간 보관됩니다.</div>
                                    <div><span style="display: inline-block; width: 3px; height: 3px; border-radius: 3px; border: 1px solid #333333; margin-right: 4px; vertical-align: middle;"></span>쿠폰, 적립금 등 추가 할인 혜택은 다음 화면인 주문서 페이지에서 적용 가능합니다.</div>
                                </td>
                                <td style="height: auto; text-align: center; vertical-align: top; padding: 16px 0 0 20px; height: auto; border-bottom: none;">
                                    <button type="button" class="btn btn-cart-action" style="margin-top: unset; border-radius: 2px;" onclick="return form_check('seldelete');">선택삭제</button>
                                </td>
                            </tr>
                        </table>

                        <?
                        $tot_price = $tot_sell_price + $send_cost; // 총계 = 주문상품금액합계 + 배송비
                        ?>
                        <table id="table-cart-calc" class="on-big">
                            <colgroup>
                                <col style="width: 220px;">
                                <col style="width: 140px;">
                                <col style="width: 220px;">
                                <col style="width: 140px;">
                                <col style="width: 220px;">
                                <col style="width: 140px;">
                                <col style="width: 340px;">
                            </colgroup>
                            <tr>
                                <th class="sit_tot_count">총 상품금액</th>
                                <th rowspan=2 style="vertical-align: middle; border-bottom-width: 3px;">
                                    <span class="icon-cart-calc calc-minus"></span>
                                </th>
                                <th>즉시할인</th>
                                <th rowspan=2 style="vertical-align: middle; border-bottom-width: 3px;">
                                    <span class="icon-cart-calc calc-plus"></span>
                                </th>
                                <th>배송비<span class="icon-tooltip" data-container="body" data-toggle="popover" data-placement="right" data-content="주문금액 <?= number_format($default['de_send_cost_limit']) ?>원 이상 무료"></span></th>
                                <th rowspan=2 style="vertical-align: middle; border-bottom-width: 3px;">
                                    <span class="icon-cart-calc calc-eq"></span>
                                </th>
                                <th>총 주문금액</th>
                            </tr>
                            <tr>
                                <td class="sit_tot_price">0원</td>
                                <td class="sit_tot_discount">0원</td>
                                <td class="sit_tot_send_price">0원</td>
                                <td class="sit_tot_pay_price">0원</td>
                            </tr>
                        </table>
                        <div class="on-big" style="font-size: 14px; font-weight: 500; font-stretch: normal; font-style: normal; line-height: normal; letter-spacing: normal; text-align: right; color: #f93f00; padding-top: 8px;">
                            주문하기를 클릭하신 후 주문서에서 쿠폰이 적용된 가격을 확인하실수 있습니다.
                        </div>
                        <div class="cart-list-action on-big">
                            <input type="hidden" name="url" value="<?= G5_SHOP_URL; ?>/orderform.php">
                            <input type="hidden" name="act" value="">
                            <input type="hidden" name="records" value="<?= $i; ?>">
                            <button type="button" class="btn btn-big" style="border-color: var(--black-two); margin-right: 20px;" onclick="return location.href='/'">계속 쇼핑하기</button>
                            <button type="button" class="btn btn-big btn-cart" onclick="return form_check('cartBuy')">주문하기</button>
                        </div>
                        <table class="on-small">
                            <colgroup>
                                <col>
                                <col style="width: 40%">
                            </colgroup>
                            <tr>
                                <td style="height: 36px; border-bottom: unset; color: #333333; padding-left: 14px; padding-top: 20px;">총 상품금액(<span id="label-cart-count">0</span>종)</td>
                                <td style="height: 36px; border-bottom: unset; text-align: right; font-size: 14px; font-weight: 500; padding-right: 14px; padding-top: 20px;" class="sit_tot_price">0원</td>
                            </tr>
                            <tr>
                                <td style="height: 36px; border-bottom: unset; color: #333333; padding-left: 14px;">즉시 할인</td>
                                <td style="height: 36px; border-bottom: unset; text-align: right; font-size: 14px; font-weight: 500; padding-right: 14px;" class="sit_tot_discount">0원</td>
                            </tr>
                            <tr>
                                <td style="height: 36px; border-bottom: unset; color: #333333; padding-left: 14px;">배송비<span class="icon-tooltip" data-container="body" data-toggle="popover" data-placement="right" data-content="주문금액 <?= number_format($default['de_send_cost_limit']) ?>원 이상 무료"></span></td>
                                <td style="height: 36px; border-bottom: unset; text-align: right; font-size: 14px; font-weight: 500; padding-right: 14px;" class="sit_tot_send_price">0원</td>
                            </tr>
                            <tr>
                                <td style="height: 42px; border-bottom: unset; color: #333333; padding-left: 14px; font-size: 16px; font-weight: 500;">총 주문금액</td>
                                <td style="height: 42px; border-bottom: unset; text-align: right; font-size: 18px; font-weight: 500; padding-right: 14px;" class="sit_tot_pay_price">0원</td>
                            </tr>
                        </table>
                        <div class="cart-list-action on-small" style="padding: 0 14px; margin-top: 20px;">
                            <div style="font-size: 12px; font-weight: 600; text-align: center; color: #f93f00; padding-bottom: 8px;">주문서에서 쿠폰이 적용된 가격을 확인하실수 있습니다.</div>
                            <button class="btn btn-cart-action" style="width: 100%; font-size: 14px; background-color: #333333; color: #ffffff; border-radius: 2px;" onclick="return form_check('cartBuy')">주문하기</button>
                            <div style="font-size: 10px; color: #acacac; text-align: left; font-weight: 300; padding-left: 7px; text-indent: -7px; margin-top: 8px;"><span style="display: inline-block; width: 3px; height: 3px; border-radius: 3px; border: 1px solid #acacac; margin-right: 4px; vertical-align: middle;"></span>장바구니 상품은 30일간 보관됩니다.</div>
                            <div style="font-size: 10px; color: #acacac; text-align: left; font-weight: 300; padding-left: 7px; text-indent: -7px;"><span style="display: inline-block; width: 3px; height: 3px; border-radius: 3px; border: 1px solid #acacac; margin-right: 4px; vertical-align: middle;"></span>쿠폰, 적립금 등 추가 할인 혜택은 다음 화면인 주문서 페이지에서 적용 가능합니다.</div>
                        </div>
                    </form>
                <? else : ?>
                    <div style="font-size: 14px; line-height: 1.43; text-align: center; color: #565656; margin-top: 74px; margin-bottom: 64px;">장바구니에 담긴 상품이 없습니다</div>
                    <div class="on-big" style="text-align: center;">
                        <button type="button" class="btn btn-big btn-black" style="width: 340px; font-size: 18px;" onclick="return location.href='/'">계속 쇼핑하기</button>
                    </div>
                    <div class="on-small" style="text-align: center; padding: 0 14px;">
                        <button type="button" class="btn btn-big btn-black" style="width: 100%; font-size: 14px;" onclick="return location.href='/'">계속 쇼핑하기</button>
                    </div>
                <? endif ?>
            </div>
        </div>
    </div>
    </form>
</div>
<div class="offset-contents-bottom"></div>
<? if ($result->num_rows > 0) : ?>
    <div class="on-small" style="width: 100%; height: 50px; line-height: 50px; color: #ffffff; background-color: #f93f00; font-size: 18px; font-weight: 500; text-align: center; position: fixed; bottom: calc(0px + env(safe-area-inset-bottom)); z-index: 2000;" onclick="return form_check('cartBuy')">
        총 <span id="cart-total-price"></span>원 주문하기
    </div>
<? endif ?>
<form name="fitem" method="post">
    <input type="hidden" name="it_id" value="">
    <input type="hidden" name="od_type" value="<? $od_type ?>">
    <input type="hidden" name="sw_direct">
    <input type="hidden" name="url">
</form>

<script>
    $(function() {
        // 가격계산
        function price_calculate() {
            const $el_it_check = $("input.ct_chk");
            const $el_it_price = $("input.it_price");
            const $el_it_discount = $("input.it_discount");
            const $el_prc = $("input.io_price");
            const $el_qty = $("input[name^=ct_qty]");
            const $el_type = $("input[name^=io_type]");

            let it_price, it_discount, price, type, qty;
            let total = 0;
            let discount_total = 0;
            let count = 0;
            let it_total = 0;
            let it_discount_total = 0;

            $el_prc.each(function(index) {
                if ($el_it_check.eq(index).is(":checked")) {
                    type = $el_type.eq(index).val();
                    price = parseInt($(this).val());
                    qty = parseInt($el_qty.eq(index).val());
                    it_price = parseInt($el_it_price.eq(index).val());
                    it_discount = parseInt($el_it_discount.eq(index).val());

                    // 주문금액 변경
                    it_total = (it_price + price) * qty;
                    it_discount_total = (it_discount * qty) - it_total;
                    discount_total += it_discount_total;

                    $(".cart-item-price-total-" + index).text(number_format(it_total));
                    count++;
                    // 총 상품금액 변경
                    if (type == 0) { // 선택옵션
                        total += (it_price + price) * qty;
                    } else { // 추가옵션
                        total += price * qty;
                    }
                }
            });

            let send_price = 0;
            if (total > 0 && total < <?= $default['de_send_cost_limit'] ?>) {
                send_price = <?= $default['de_send_cost_list'] ?>;
            }

            $("#label-cart-count").html(count);
            $(".sit_tot_price").html(number_format((total + discount_total) * 1) + "원");
            $(".sit_tot_discount").html(number_format(discount_total * 1) + "원");
            $(".sit_tot_send_price").html(number_format(send_price * 1) + "원");
            $(".sit_tot_pay_price").html(number_format((total * 1) + send_price) + "원");
            $("#cart-total-price").html(number_format((total * 1) + send_price));
        }

        var close_btn_idx;

        $(".ct_chk").on("change", function() {
            let cart_select_count = 0;

            $(this).attr("checked", $(this).is(":checked"));
            if (!$(this).is(":checked")) $("input:checkbox[name=ct_all]").prop("checked", false); 
            if ($(this).is(":checked")) {
                let checkAll = 0;
                $("input[type=checkbox]").each(function(ci, cb) { 
                    var checkId = $(cb).attr("id");
                    var checkNum = checkId.indexOf('ct_chk');
                    if (checkNum ==0) {
                        if(!$(`input:checkbox[id='${checkId}']`).is(":checked")) {
                            checkAll = 1;
                            return false;
                        } 
                    }
                })
                if (checkAll == 0) $("input:checkbox[name=ct_all]").prop("checked", true);
            }
            $("input[type=checkbox]").each(function(ci, cb) { 
                    var checkId = $(cb).attr("id");
                    var checkNum = checkId.indexOf('ct_chk');
                    if (checkNum ==0) {
                        if($(`input:checkbox[id='${checkId}']`).is(":checked")) {
                            cart_select_count += 1;
                        } 
                    }
            })
            $("#count-checked").html(`(${cart_select_count}/<?= $cart_count ?>)`);
            return price_calculate();
        });

        $(".mo_ct_chk").on("change", function() {
            let cart_select_count = 0;
            var mo_id = $(this).attr("id");
            var mo_id = mo_id.substr(3,);

            $(this).attr("checked", $(this).is(":checked"));
            $(`input:checkbox[id='${mo_id}']`).prop('checked', $(this).is(":checked"));

            if (!$(this).is(":checked")) $("input:checkbox[name=mo_ct_all]").prop("checked", false);
            if ($(this).is(":checked")) {
                let checkAllMo = 0;
                $("input[type=checkbox]").each(function(ci, cb) { 
                    var checkId = $(cb).attr("id");
                    var checkNum = checkId.indexOf('ct_chk');
                    if (checkNum ==3) {
                        if(!$(`input:checkbox[id='${checkId}']`).is(":checked")) {
                            checkAllMo = 1;
                            return false;
                        } 
                    }
                })
                if (checkAllMo == 0) $("input:checkbox[name=mo_ct_all]").prop("checked", true);
            }
            $("input[type=checkbox]").each(function(ci, cb) { 
                    var checkId = $(cb).attr("id");
                    var checkNum = checkId.indexOf('ct_chk');
                    if (checkNum ==3) {
                        if($(`input:checkbox[id='${checkId}']`).is(":checked")) {
                            cart_select_count += 1;
                        } 
                    }
            })
            $("#count-checked-mobile").html(`(${cart_select_count}/<?= $cart_count ?>)`);
            return price_calculate();
        });

        // 선택사항수정
        $(".mod_options").click(function() {
            var it_id = $(this).attr("id").replace("mod_opt_", "");
            var $this = $(this);
            close_btn_idx = $(".mod_options").index($(this));

            $.post(
                "./cartoption.php", {
                    it_id: it_id
                },
                function(data) {
                    $("#mod_option_frm").remove();
                    $this.after("<div id=\"mod_option_frm\"></div>");
                    $("#mod_option_frm").html(data);
                    price_calculate();
                }
            );
        });

        // 모두선택
        $(".ct_all,.mo_ct_all").click(function() {
            const checked = $(this).is(":checked");
            $("input[type=checkbox]").each(function(ci, cb) {
                var checkId = $(cb).attr("id");
                if ($(cb).attr("disabled") === undefined) {
                    $(`input:checkbox[id='${checkId}']`).prop('checked', checked);
                }
            });
            let allCount = 0;
            if (checked) allCount = <?= $cart_count ?>;
            $("#count-checked").html(`(${allCount}/<?= $cart_count ?>)`);
            $("#count-checked-mobile").html(`(${allCount}/<?= $cart_count ?>)`);
            return price_calculate();
        });

        $(".count_delete").click(function() {
            var $this = $(this);
            var ct_id = $this.attr("ct_id");

            if (confirm("선택하신 옵션항목을 삭제하시겠습니까?")) {

                $this.addClass("disabled").attr("disabled", true);
                $.ajax({
                    url: g5_url + "/shop/ajax.cartupdate.php",
                    type: "POST",
                    data: {
                        "act": "del",
                        "ct_id": ct_id
                    },
                    dataType: "json",
                    async: false,
                    cache: false,
                    success: function(data) {
                        if (data.error != "") {
                            $this.removeClass("disabled").attr("disabled", false);
                            alert(data.error);
                            return false;
                        }

                        $this.attr("disabled", false);
                        //alert("삭제되었습니다.");
                        location.href = "<?= G5_SHOP_URL . '/cart.php?od_type=' . $od_type ?>";
                    }
                });

            }
        });

        $(".count_mod").click(function() {
            var $this = $(this);
            var ct_id = $this.attr("ct_id");
            var ct_qty = $("input[name='ct_qty[" + ct_id + "]']").val();

            if (confirm("선택하신 옵션항목 수량을 적용하시겠습니까?")) {

                $this.addClass("disabled").attr("disabled", true);
                $.ajax({
                    url: g5_url + "/shop/ajax.cartupdate.php",
                    type: "POST",
                    data: {
                        "act": "mod",
                        "ct_id": ct_id,
                        "ct_qty": ct_qty
                    },
                    dataType: "json",
                    async: false,
                    cache: false,
                    success: function(data) {
                        if (data.error != "") {
                            $this.removeClass("disabled").attr("disabled", false);
                            alert(data.error);
                            return false;
                        }

                        $this.attr("disabled", false);
                        location.href = "<?= G5_SHOP_URL . '/cart.php?od_type=' . $od_type ?>";

                        //alert("수정되었습니다.");
                        //price_calculate();
                    }
                });

            }
        });

        // 옵션수정 닫기
        $(document).on("click", "#mod_option_close", function() {
            $("#mod_option_frm").remove();
            $("#win_mask, .window").hide();
            $(".mod_options").eq(close_btn_idx).focus();
        });
        $("#win_mask").click(function() {
            $("#mod_option_frm").remove();
            $("#win_mask").hide();
            $(".mod_options").eq(close_btn_idx).focus();
        });

        $(document).on("change", "select.cart_it_option", function() {
            var val = $(this).val();

            var info = val.split(",");
            // 재고체크
            if (parseInt(info[2]) < 1) {
                alert("선택하신 선택옵션상품은 재고가 부족하여 구매할 수 없습니다.");
                $(this).val("");
                return false;
            }

            var its_no = $(this).attr("its_no");
            add_sel_option_mobile_chk(its_no);
        });

        $(document).on("change", "select.cart_it_supply", function() {
            var its_no = $(this).attr("its_no");
            add_sel_option_mobile_chk(its_no);
        });

        $(".count-control > button").on("click", function() {
            const target = $(this).parent().find("input");

            if ($(this).hasClass("count-minus")) {
                if (target.val() > 1) target.val(target.val() - 1);
            } else {
                target.val((target.val() * 1) + 1);
            }

            if ($(this).hasClass("is-small")) {
                const $button = $(this);
                const ct_id = /\[([0-9].*)\]/.exec(target.data("target"))[1];
                const ct_qty = target.val();
                const masterTarget = $("input[name='ct_qty[" + ct_id + "]']");

                masterTarget.val(ct_qty);

            }
            price_calculate();
        });

        price_calculate();
    });


    function fsubmit_check(f) {
        if ($("input[name^=ct_chk]:checked").length < 1) {
            alert("구매하실 상품을 하나이상 선택해 주십시오.");
            return false;
        }

        return true;
    }

    //리뉴얼 픽
    $(".cart-action-wish").on("click", function(e) {
        e.stopPropagation();
        const $btnPick = $(this);
        const action = $btnPick.hasClass("picked") ? "unpick" : "pick";
        const type = $btnPick.data("type");
        const pickid = $btnPick.data("pick");

        $.post("/shop/ajax.pick.php", {
            action: action,
            type: type,
            id: pickid
        }, function(response) {
            if (response.result == true) {
                $btnPick.toggleClass("picked");
            } else {
                switch (response.error) {
                    case "NOT_FOUND_MEMBER":
                        return openLogin();
                        break;
                    default:
                        return alert(response.error);
                        break;
                }
            }
        }, "json");
        e.preventDefault();
    });


    function form_check(act, mobile) {
        var f = document.frmcartlist;
        var cnt = f.records.value;
        if (act == "cartBuy") { 
            // 주문 전 장바구니 수량 변경 적용
            const regCtId = /[0-9]+/;
            const $itemQtySet = $("input[name^=ct_qty]");
            var ct_id_obj = {};
            $itemQtySet.each(function(qi, qe) { 
                const regCtIdResult = regCtId.exec($(qe).attr("name"));
                const ct_id = regCtIdResult[0];
                const ct_qty = $(qe).val();
                if (!$(qe).attr("disabled")) { 
                    ct_id_obj[ct_id] = ct_qty;
                }
            });
            $.ajax({
                url: g5_url + "/shop/ajax.cartupdate.php",
                type: "POST",
                data: {
                    "act": "mod",
                    "ct_id": 'ct_id',
                    "ct_qty": 'ct_qty',
                    "ct_json":ct_id_obj
                },
                dataType: "json",
                async: false,
                cache: false,
                success: function(data) {
                    if (data.error != "") {
                        // toggleLoading();
                        alert(data.error);
                        return false;
                    }
                }
            });
            act = "buy";
            f.act.value = act;
            f.submit();
        }
        else if (act == "buy") {
            // 스플래시 팝업
            // toggleLoading();

            // 주문 전 장바구니 수량 변경 적용
            const regCtId = /[0-9]+/;
            const $itemQtySet = $("input[name^=ct_qty]");

            $itemQtySet.each(function(qi, qe) {

                if (!$(qe).attr("disabled")) {
                    const regCtIdResult = regCtId.exec($(qe).attr("name"));
                    const ct_id = regCtIdResult[0];
                    const ct_qty = $(qe).val();
                    $.ajax({
                        url: g5_url + "/shop/ajax.cartupdate.php",
                        type: "POST",
                        data: {
                            "act": "mod",
                            "ct_id": ct_id,
                            "ct_qty": ct_qty
                        },
                        dataType: "json",
                        async: false,
                        cache: false,
                        success: function(data) {
                            if (data.error != "") {
                                // toggleLoading();
                                alert(data.error);
                                return false;
                            }
                        }
                    });
                }
            });

            f.act.value = act;
            f.submit();
        } else if (act == "alldelete") {
            if (confirm("선택하신 제품을 장바구니에서 삭제하시겠습니까?")) {
                f.act.value = act;
                f.submit();
            }
        } else if (act == "seldelete") {

            if (mobile == true) {
                const mo_check = $("input[name^=mo_ct_chk]:checked");
                $("input[name^=ct_chk]").attr("checked", false);
                $(mo_check).each(function(idx) {
                    $("input[name^=ct_chk]").eq($(mo_check[idx]).data("idx")).attr("checked", "checked");
                });
            }
            if ($("input[name^=ct_chk]:checked").length < 1) {
                alert("삭제하실 상품을 하나이상 선택해 주십시오.");
                return false;
            }

            if (confirm("선택하신 제품을 장바구니에서 삭제하시겠습니까?")) {
                f.act.value = act;
                f.submit();
            }
        } else if (act == "soldoutdelete") {
            f.act.value = act;
            f.submit();
        }

        return true;
    }

    function buyOne(itemIdx,ctId) {
        var f = document.frmcartlist;
        var ct_qty = $(`input[name="ct_qty[${ctId}]"]`).val();
        $("input[name^=ct_chk]").prop("checked", false);
        $("input[name^=ct_chk]").eq(itemIdx).prop("checked", "checked");
        // 스플래시 팝업
        // toggleLoading();

        $.ajax({
            url: g5_url + "/shop/ajax.cartupdate.php",
            type: "POST",
            data: {
                "act": "mod",
                "ct_id": ctId,
                "ct_qty": ct_qty
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(data) {
                if (data.error != "") {
                    toggleLoading();
                    alert(data.error);
                    return false;
                }
            }
        });

        // $itemQtySet.each(function(qi, qe) {
        //     console.log('1');
        //     console.log('qi:',qi);
        //     if (!$(qe).attr("disabled")) {
        //         console.log('2');
        //         console.log('qe:',qe);
        //         const regCtIdResult = regCtId.exec($(qe).attr("name"));
        //         const ct_id = regCtIdResult[0];
        //         const ct_qty = $(qe).val();
        //         $.ajax({
        //             url: g5_url + "/shop/ajax.cartupdate.php",
        //             type: "POST",
        //             data: {
        //                 "act": "mod",
        //                 "ct_id": ct_id,
        //                 "ct_qty": ct_qty
        //             },
        //             dataType: "json",
        //             async: false,
        //             cache: false,
        //             success: function(data) {
        //                 console.log('????');
        //                 if (data.error != "") {
        //                     toggleLoading();
        //                     alert(data.error);
        //                     return false;
        //                 }
        //             }
        //         });
        //     }
        // });
        // return;
        f.act.value = 'buy';
        f.submit();
    }

    function removeOne(itemIdx) {
        var f = document.frmcartlist;
        // $("input[name^=ct_chk]").attr("checked", false);
        // $("input[name^=ct_chk]").eq(itemIdx).removeAttr("disabled");
        // $("input[name^=ct_chk]").eq(itemIdx).attr("checked", "checked");

        $("input[id^=ct_chk]").prop("checked", false);
        $("input[name^=ct_chk]").removeAttr("disabled");
        $("input[id^=ct_chk]").eq(itemIdx).prop('checked', true);
        f.act.value = 'seldelete';
        f.submit();
    }

    function add_sel_option_mobile_chk(its_no) {
        var add_exec = true;

        var $sel_it_option = $("select[name='sel_it_option[]'][its_no='" + its_no + "']");
        if ($sel_it_option.val() == "") add_exec = false;

        var $sel_it_supply = $("select[name='sel_it_supply[]'][its_no='" + its_no + "']");
        if ($sel_it_supply.size() > 0) {
            $sel_it_supply.each(function() {
                if ($(this).val() == "") add_exec = false;
            });
        }

        //add_option
        if (add_exec) {
            var id = "";
            var value, info, sel_opt, item, price, stock, run_error = false;
            var option = sep = "";

            var it_price = parseInt($("input[name='its_final_price[]'][its_no='" + its_no + "']").val());
            //var it_price = parseInt($("input#it_price").val());
            var item = $sel_it_option.closest("li").find("span[id^=spn_it_option]").text();

            value = $sel_it_option.val();
            info = value.split(",");
            sel_opt = info[0];
            id = sel_opt;
            option += sep + item + ":" + sel_opt;

            price = info[1];
            stock = info[2];

            $sel_it_supply.each(function() {
                //if($(this).val() == "") add_exec = false;
                value = $(this).val();
                info = value.split(",");
                sel_opt = info[0].split(chr(30))[1];

                //id += chr(30)+sel_opt;
                sep = " , ";
                option += sep + sel_opt;
                price = parseInt(price) + parseInt(info[1]);
            });

            //alert(option);

            if (same_option_check(option))
                return;

            add_sel_option_mobile(0, id, option, price, stock, it_price);
        }
    }

    function add_sel_option_mobile(type, id, option, price, stock, it_price) {
        var item_code = $("input[name='it_id[]']").val();
        var opt = "";
        var li_class = "sit_opt_list";
        if (type)
            li_class = "sit_spl_list";

        var opt_prc;
        if (parseInt(price) >= 0)
            opt_prc = number_format(it_price) + "원 (+" + number_format(String(price)) + "원)";
        else
            opt_prc = number_format(it_price) + "원 (" + number_format(String(price)) + "원)";

        opt += "<li class=\"" + li_class + "\">";
        opt += "<input type=\"hidden\" name=\"io_type[" + item_code + "][]\" value=\"" + type + "\">";
        opt += "<input type=\"hidden\" name=\"io_id[" + item_code + "][]\" value=\"" + id + "\">";
        opt += "<input type=\"hidden\" name=\"io_value[" + item_code + "][]\" value=\"" + option + "\">";
        opt += "<input type=\"hidden\" class=\"it_price\" value=\"" + it_price + "\">";
        opt += "<input type=\"hidden\" class=\"io_price\" value=\"" + price + "\">";
        opt += "<input type=\"hidden\" class=\"io_stock\" value=\"" + stock + "\">";
        opt += "<div class=\"cont\"><p class=\"txt\"><span>" + option + "</span></p>";
        opt += "<span style=\"\">" + opt_prc + "</span></div>";

        opt += "<div class=\"cont alignR\"><div class=\"count_control\">";
        opt += "<em class=\"num\"><input type=\"text\" name=\"ct_qty[" + item_code + "][]\" value=\"1\" class=\"frm_input\" size=\"5\" style=\"height:18px;\"></em>";

        opt += "<button type=\"button\" class=\"count_minus\"><span class=\"blind\">감소</span></button>";
        opt += "<button type=\"button\" class=\"count_plus\"><span class=\"blind\">증가</span></button>";

        opt += "</div>";
        opt += "<button type=\"button\" class=\"count_del\"><span class=\"blind\">삭제</span></button>";

        opt += "</div></li>";

        if ($("#sit_sel_option > ul").size() < 1) {
            $("#sit_sel_option").html("<ul id=\"sit_opt_added\"></ul>");
            $("#sit_sel_option > ul").html(opt);
        } else {
            if (type) {
                if ($("#sit_sel_option .sit_spl_list").size() > 0) {
                    $("#sit_sel_option .sit_spl_list:last").after(opt);
                } else {
                    if ($("#sit_sel_option .sit_opt_list").size() > 0) {
                        $("#sit_sel_option .sit_opt_list:last").after(opt);
                    } else {
                        $("#sit_sel_option > ul").html(opt);
                    }
                }
            } else {
                if ($("#sit_sel_option .sit_opt_list").size() > 0) {
                    $("#sit_sel_option .sit_opt_list:last").after(opt);
                } else {
                    if ($("#sit_sel_option .sit_spl_list").size() > 0) {
                        $("#sit_sel_option .sit_spl_list:first").before(opt);
                    } else {
                        $("#sit_sel_option > ul").html(opt);
                    }
                }
            }
        }

        price_calculate();
    }

    function item_wish(f, it_id) {
        if ($(".pick[it_id='" + it_id + "']").attr("class").indexOf("on") < 0) {
            $.post(
                "<?= G5_SHOP_URL; ?>/wishupdate2.php", {
                    it_id: it_id
                },
                function(data) {
                    var responseJSON = JSON.parse(data);
                    if (responseJSON.result == "S") {

                        if (confirm("관심상품에 저장되었습니다. 보러가시겠습니까?")) location.href = '<?= G5_SHOP_URL; ?>/wishlist.php';

                        $(".pick[it_id='" + it_id + "']").addClass("on");
                    } else {
                        alert(responseJSON.alert);
                        return false;
                    }
                }
            );
        } else {
            $.post(
                "<?= G5_SHOP_URL; ?>/wishupdate2.php", {
                    it_id: it_id,
                    w: 'r'
                },
                function(data) {
                    var responseJSON = JSON.parse(data);
                    if (responseJSON.result == "S") {
                        $(".pick[it_id='" + it_id + "']").removeClass("on");
                    } else {
                        alert(responseJSON.alert);
                        return false;
                    }
                }
            );
        }
    }
</script>
<!-- Enliple Tracker Start -->
<script type="text/javascript">
    var ENP_VAR = { conversion: { product: [] } };
    let nexiLen = $("input[name='nexi[]']").length;
    let ct_id = '';
    let it_id = '';
    let it_name = '';
    let it_price = '';
    let it_discount = '';
    let ct_qty = '';
    let totalQty = 0;
    let totalItPrice = 0;
 	for (i=0; i<nexiLen; i++)
 	{

        ct_id = $("input[name='ct_id["+i+"]']").val();
        it_id = $("input[name='it_id["+i+"]']").val();
        it_name = $("input[name='it_name["+i+"]']").val();
        it_price = $("input[name='it_price["+i+"]']").val();
        it_discount = $("input[name='it_discount["+i+"]']").val();
        ct_qty = $("input[name='ct_qty["+ct_id+"]']").val();
        totalQty = totalQty + Number(ct_qty); 
        totalItPrice = totalItPrice + Number(it_price); 
        ENP_VAR.conversion.product.push(
            {
                productCode : it_id,
                productName : it_name,
                price : it_discount,
                dcPrice : it_price,
                qty : ct_qty
            }
        );
 	}
    ENP_VAR.conversion.totalPrice = totalItPrice;  // 없는 경우 단일 상품의 정보를 이용해 계산
    ENP_VAR.conversion.totalQty = totalQty;  // 없는 경우 단일 상품의 정보를 이용해 계산
    let broswerTrackerMOC = navigator.userAgent;
    let deviceTrackerMOC = "W";
    if (broswerTrackerMOC.indexOf("Mobile")>-1) { 
        deviceTrackerMOC = "M";
    }
    (function(a,g,e,n,t){a.enp=a.enp||function(){(a.enp.q=a.enp.q||[]).push(arguments)};n=g.createElement(e);n.async=!0;n.defer=!0;n.src="https://cdn.megadata.co.kr/dist/prod/enp_tracker_self_hosted.min.js";t=g.getElementsByTagName(e)[0];t.parentNode.insertBefore(n,t)})(window,document,"script");
    enp('create', 'conversion', 'litandard', { device: deviceTrackerMOC, paySys: 'naverPay' }); // W:웹, M: 모바일, B: 반응형
</script>
<!-- Enliple Tracker End -->

<script type="text/javascript" src="//wcs.naver.net/wcslog.js"></script>
<script type="text/javascript">
var _nasa={};
if(window.wcs) _nasa["cnv"] = wcs.cnv("3","1");
</script>
<?php
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>