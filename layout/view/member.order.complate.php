<?php
ob_start();
$g5_title = "주문완료";
echo G5_POSTCODE_JS;
?>
<link rel="stylesheet" href="/re/css/shop.css">

<!-- 컨텐츠 시작 -->
<style>
    #offset-nav-top {
        height: 90px;
        margin-bottom: 136px;
    }

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

    .btn.btn-cart-action {
        border-radius: 2px;
        background-color: #333333;
        color: #ffffff;
        font-size: 16px;
        font-weight: 400;
    }

    .btn-cart-action-mobile {
        width: 18px;
        height: 18px;
        background: url(/img/re/heart@3x.png) center center no-repeat;
        background-size: cover;
        border: unset;
        float: right;
    }

    .btn.btn-cart-action.white {
        background-color: #ffffff;
        border-color: #333333;
        color: #333333;
    }

    .order-info-title {
        font-size: 20px;
        font-weight: bold;
        color: #333333;
        line-height: 60px;
        border-bottom: 3px solid #333333;
        margin-bottom: 8px;
    }

    #order-member-info,
    #order-delivery-info,
    #order-discount-info,
    #order-payment-info {
        width: 840px;
    }

    #order-member-info>table>tbody>tr>th,
    #order-member-info>table>tbody>tr>td,
    #order-delivery-info>table>tbody>tr>th,
    #order-delivery-info>table>tbody>tr>td,
    #order-discount-info>table>tbody>tr>th,
    #order-discount-info>table>tbody>tr>td,
    #order-payment-info>table>tbody>tr>th,
    #order-payment-info>table>tbody>tr>td {
        line-height: 40px;
        font-size: 16px;
        color: #333333;
    }

    #order-member-info>table>tbody>tr>th,
    #order-delivery-info>table>tbody>tr>th,
    #order-discount-info>table>tbody>tr>th,
    #order-payment-info>table>tbody>tr>th {
        width: 120px;
        font-size: 14px;
        font-weight: 500;
    }

    #order-member-info>table,
    #order-delivery-info>table,
    #order-discount-info>table,
    #order-payment-info>table,
    #order-product-info>table {
        width: 100%;
    }

    #order-product-info>table>tbody>tr>th,
    #order-product-info>table>tbody>tr>td {
        border-bottom: 1px solid #333333;
        font-size: 16px;
        font-weight: 500;
        text-align: center;
        color: #3a3a3a;
    }

    #order-product-info>table>tbody>tr>th {
        line-height: 56px;
    }

    #order-product-info>table>tbody>tr>td {
        padding: 24px 0;
    }

    #order-delivery-info>table>tbody>tr>td {
        padding: 8px 0;
    }

    #order-discount-info>table>tbody>tr>td {
        font-size: 14px;
        font-weight: 500;
        line-height: 60px;
    }

    #order-delivery-info select,
    #order-delivery-info input[type="text"],
    #order-delivery-info input[type="number"] {
        width: 340px;
    }

    .select-order-memo-user {
        display: none;
    }

    .select-order-memo-user.active {
        display: block;
    }

    .btn-order-payment {
        width: 410px;
        height: 100px;
        border-radius: 2px;
        border: solid 1px #333333;
        font-size: 14px;
        font-weight: 500;
        text-align: center;
        color: #4c4c4c;
        background-color: #ffffff;
    }

    .btn-order-payment>.icon-payment {
        display: inline-block;
        width: 40px;
        height: 40px;
        background: url(/img/re/iconset_payment.png) 0 0 no-repeat;
        background-size: 80px;
    }

    .btn-order-payment.order-payment-bank>.icon-payment {
        background-position-x: -40px;
    }

    .btn-order-payment.active {
        background-color: #333333;
        color: #ffffff;
    }

    .btn-order-payment.active>.icon-payment {
        background-position-y: -40px;
    }

    .btn-cart-action-mobile {
        width: 18px;
        height: 18px;
        background: url(/img/re/heart@3x.png) center center no-repeat;
        background-size: cover;
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

    #table-order-delivery-info>tbody>tr>td {
        padding: unset !important;
    }

    #table-change-delivery-info {
        display: none;
    }

    #order-delivery-info.change #table-order-delivery-info {
        display: none;
    }

    #order-delivery-info.change #table-change-delivery-info {
        display: block;
    }

    .order-info-right table {
        width: 100%;
    }


    /* 배송지 팝업 CSS 끝 */

    #modal-order-address-form-button {
        font-size: 0;
        padding: 20px 0;
        text-align: center;
    }

    #modal-order-address-form-button>button:first-child {
        margin-right: 20px;
    }

    #modal-order-address-form-content th {
        font-size: 14px;
        font-weight: 400;
        padding-left: 20px;
    }

    #modal-order-address-form-content td {
        padding: 8px 0;
    }

    #modal-order-address-form-content tr:first-child>th,
    #modal-order-address-form-content tr:first-child>td {
        padding-top: 16px;
    }

    #modal-order-address-form-content input[type='text'],
    #modal-order-address-form-content input[type='number'] {
        width: 100%;
    }

    .order-address-no-result {
        font-size: 14px;
        line-height: 1.79;
        text-align: center;
        color: #333333;
    }


    #modal-order-coupon-mobile>.modal-dialog,
    #modal-order-address-form-mobile>.modal-dialog,
    #modal-order-address-mobile>.modal-dialog {
        max-width: unset;
        margin: unset;
        margin-top: calc(100vh - 557px);
    }

    #modal-order-address-mobile>.modal-dialog {
        margin-top: calc(100vh - 390px);
    }

    #modal-order-coupon-mobile>.modal-dialog>.modal-content,
    #modal-order-address-mobile>.modal-dialog>.modal-content,
    #modal-order-address-form-mobile>.modal-dialog>.modal-content {
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
        background-color: #ffffff;
        margin: unset;
        width: 100%;
        height: auto;
        padding: unset !important;
        margin-left: unset;

    }

    #modal-order-address-form-button-mobile>button,
    #modal-order-address-button-mobile>button {
        width: calc((100vw - 44px) / 2) !important;
    }

    #modal-order-address-form-button-mobile>button:first-child,
    #modal-order-address-button-mobile>button:first-child {
        margin-right: 14px;
    }

    #modal-order-address-form-button-mobile,
    #modal-order-address-button-mobile {
        position: fixed;
        width: 100%;
        height: 106px;
        bottom: 48px;
        background-color: #ffffff;
        border-top: 1px solid #e0e0e0;
        font-size: 0;
        padding-top: 24px;
        padding-left: 14px;
        z-index: 1000;
    }

    #modal-order-address-content-mobile {
        padding-bottom: 154px;
    }

    #modal-order-address-form-content-mobile th,
    #modal-order-address-form-content-mobile td {
        padding: 0 14px;
    }

    #modal-order-address-form-content-mobile th {
        font-size: 12px;
        padding-top: 16px;
    }

    /* 배송지 팝업 CSS 끝 */


    @media (max-width: 1366px) {
        #offset-nav-top {
            margin-bottom: unset;
        }

        #order-member-info-mobile,
        #order-delivery-info-mobile,
        #order-discount-info-mobile,
        #order-calculate-info-mobile,
        #order-payment-info-mobile,
        #order-calculate-info-mobile,
        #order-calculate-info-mobile>table {
            width: 100%;
        }

        .order-info-title {
            border-bottom: unset;
            margin-bottom: unset;
            font-size: 16px;
            font-weight: 500;
            font-stretch: normal;
            font-style: normal;
            line-height: normal;
            letter-spacing: normal;
            color: #333333;
            padding: 16px 14px;
        }

        #order-member-info-mobile>table>tbody>tr>th,
        #order-member-info-mobile>table>tbody>tr>td,
        #order-delivery-info-mobile>table>tbody>tr>th,
        #order-delivery-info-mobile>table>tbody>tr>td,
        #order-discount-info-mobile>table>tbody>tr>th,
        #order-discount-info-mobile>table>tbody>tr>td,
        #order-calculate-info-mobile>table>tbody>tr>th,
        #order-calculate-info-mobile>table>tbody>tr>td,
        #order-payment-info-mobile>table>tbody>tr>th,
        #order-payment-info-mobile>table>tbody>tr>td {
            line-height: 32px;
            font-size: 12px;
            color: #333333;
        }

        #order-member-info-mobile>table>tbody>tr>th,
        #order-delivery-info-mobile>table>tbody>tr>th,
        #order-discount-info-mobile>table>tbody>tr>th,
        #order-calculate-info-mobile>table>tbody>tr>th,
        #order-payment-info-mobile>table>tbody>tr>th {
            width: 70px;
            padding-left: 14px;
            font-size: 12px;
            font-weight: normal;
        }

        #order-discount-info-mobile>table>tbody>tr>th {
            font-size: 14px;
            font-weight: 500;
        }

        #order-calculate-info-mobile>table>tbody>tr>th {
            width: 120px;
        }

        #order-calculate-info-mobile>table>tbody>tr>td {
            text-align: right;
            font-size: 14px;
            font-weight: 500;
            padding-right: 14px;
        }

        .order-info-separator {
            border-top: 10px solid #f2f2f2;
        }

        .btn.btn-cart-action.small {
            width: 64px !important;
            height: 24px !important;
            font-size: 10px !important;
            padding: unset;
        }

        #order-delivery-info-preview {
            display: none;
            font-size: 12px;
            color: #333333;
            padding: 0 14px;
            line-height: 28px;
        }

        #form-order-delivery-info.active,
        #order-delivery-info-preview.active {
            display: block;
        }

        #form-order-delivery-info {
            display: none;
            padding: 0 14px;
        }

        .custom-control-label {
            margin-left: unset;
        }

        .custom-checkbox .custom-control-label::before,
        .custom-checkbox .custom-control-input:checked~.custom-control-label::after {
            width: 14px !important;
            height: 14px !important;
        }

        #table-order-delivery-info-mobile th {
            font-size: 12px;
            font-weight: 500;
            color: #3a3a3a;
            padding-top: 16px;
        }

        select,
        input[type="text"],
        input[type="number"] {
            padding: 0 10px;
        }

        .btn-order-payment {
            margin-bottom: 20px;
        }

        .swiper_item_brand img {
            vertical-align: middle;
        }


        #order-product-info-mobile {
            padding: 0 14px;
        }

        #order-product-info-mobile>table {
            width: 100%;
        }

        #order-product-info-mobile th {
            font-size: 12px !important;
            font-weight: normal;
        }

        #order-product-info-mobile th,
        #order-product-info-mobile td {
            font-size: 12px;
        }

        #order-product-info-mobile th>img {
            width: 75px;
            height: 75px;
        }

        #order-payment-info-mobile>table {
            width: 100%;
        }

        #order-payment-info-mobile th {
            width: 90px !important;
        }

        #order-payment-info-mobile td {
            font-size: 14px !important;
            text-align: right;
            padding-right: 14px;
        }

        .row-separator {
            border-top: 1px solid #e0e0e0;
        }

        .row-separator>td {
            height: 16px;
        }

        #order-product-info-mobile .row-separator:last-child {
            display: none;
        }
    }
</style>
<div id="list-wrapper">
    <div class="layout-offset">
        <div class="on-big" style="margin-bottom: 40px;">
            <div class="cart-step-title">주문서</div>
            <div style="text-align: center; font-size: 0; margin-top: 32px; margin-bottom: 8px;">
                <span class="icon-cart-step step-1"><span></span></span>
                <span class="icon-cart-step step-2" style="margin: 0 94px;"><span></span></span>
                <span class="icon-cart-step step-3 active"><span></span></span>
            </div>
            <div style="text-align: center; font-size: 0;">
                <span class="label-cart-step">장바구니</span>
                <span class="label-cart-step" style="margin: 0 96px;">주문서</span>
                <span class="label-cart-step active">주문완료</span>
            </div>
        </div>

        <div class="on-big" style="font-size: 16px; font-weight: 500; line-height: 1.56; text-align: center; color: #333333; margin-top: 80px;">
            주문이 완료되었습니다.<br>
            주문번호 : <?= $od['od_id'] ?>
        </div>
        <div class="on-small" style="font-size: 14px; font-weight: normal; line-height: 1.79; text-align: center; color: #3a3a3a; padding-bottom: 40px;">
            주문이 완료되었습니다.<br>
            주문번호 : <?= $od['od_id'] ?>
        </div>

        <div id="order-product-info" class="on-big">
            <div class="order-info-title">주문상품(<?= ($tot_rows) ?>)</div>
            <table>
                <tr>
                    <th colspan=2>상품정보</th>
                    <th style="width: 220px;">수량</th>
                    <th style="width: 260px;">주문금액</th>
                    <th style="width: 220px;">배송비</th>
                </tr>
                <?php while (false != ($row = sql_fetch_array($order_items))) : ?>
                    <?
                    if (isset($od_status_set_count[$row['ct_status']])) $od_status_set_count[$row['ct_status']]++;
                    $image = get_it_image($row['it_id'], 120, 120, '', '', $row['it_name']);
                    $ct_send_cost_str = "-";
                    $sql_sc = " SELECT SUM((b.its_final_price + a.io_price) * a.ct_qty) as price,
                                SUM((b.its_price + a.io_price) * a.ct_qty) as before_price,
                                SUM(a.ct_qty) as qty,
                                count(distinct a.ct_id) as ct_cnt
                                from lt_shop_cart as a
                                inner join lt_shop_item_sub as b on a.it_id = b.it_id and a.its_no = b.its_no
                                inner join lt_shop_item as c on a.it_id = c.it_id
                                where  a.od_id = '$od_id'
                                and    a.it_id = '{$row['it_id']}' ";

                    $sc = sql_fetch($sql_sc);
                    if ($sc) {
                        if ($row['it_sc_type'] == '2' && $de_individual_costs_use == '1') {

                            //선택설정(상품별 개별배송비 사용 필수) : 상품별로 배송비 부과
                            $ct_send_cost = (int) get_item_sendcost($row['it_id'], $sc['price'], $sc['qty'], $od_id, $sc['before_price']);
                            $rowspan = $sc['ct_cnt'];

                            if ($ct_send_cost > 0) $total_send_cost = (int) $total_send_cost - (int) $ct_send_cost;
                        } else {
                            $rowspan = $tot_rows;
                            $ct_send_cost = $total_send_cost;
                        }
                    }

                    if ($ct_send_cost == 0) $ct_send_cost_str = "무료";
                    else $ct_send_cost_str = number_format($ct_send_cost) . " 원";

                    $tot_rows--;

                    $it_discount = $row['before_price'] - $row['ct_price'];
                    $it_discount_ratio = $it_discount > 0 ? ($it_discount / $row['before_price']) * 100 : 0;
                    $it_before_price = ($row['ct_price'] + $row['io_price']) * $row['ct_qty'];
                    $tot_before_price += $row['before_price'] * $row['ct_qty'];
                    $tot_sell_price += $it_discount * $row['ct_qty'];

                    ?>
                    <tr>
                        <td style="width: 152px; cursor: pointer;" onclick="location.href='/shop/item.php?it_id=<?= $row['it_id'] ?>'">
                            <?= $image ?>
                        </td>
                        <td onclick="location.href='/shop/item.php?it_id=<?= $row['it_id'] ?>'" style="text-align: left; vertical-align: top; cursor: pointer;">
                            <div class="swiper_item_detail">
                                <div class="swiper_item_brand"><?= $row['it_brand'] ?> <span class ='hocName<?= $row['io_hoching'] ?>'></span></div>
                                <div class="swiper_item_name"><?= $row['it_name'] ?></div>
                                <div class="swiper_item_price_area">
                                    <span><?= number_format($row['ct_price']) ?><span style="font-size: 12px;">원</span></span>
                                    <? if ($it_discount_ratio > 0) : ?>
                                    <span class="price-del"><del><?= number_format($row['before_price']) ?></del>원</span>
                                    <span class="price-dis" style="color: #e65026;"><?= number_format($it_discount_ratio) ?>%</span>
                                    <? endif ?>
                                </div>
                            </div>
                        </td>
                        <td><?= number_format($row['ct_qty']) ?></td>
                        <td><?= number_format($it_before_price) ?></td>
                        <td><?= $ct_send_cost_str ?></td>
                    </tr>
                <?php endwhile ?>
            </table>
        </div>

        <div class="on-big" style="display: flex; justify-content: space-between; margin-top: 120px;">
            <div class="order-info-left">
                <div id="order-member-info">
                    <div class="order-info-title">주문자정보</div>
                    <table id="table-order-member-info">
                        <tr>
                            <th>이름</th>
                            <td><?= $member['mb_name'] ?></td>
                        </tr>
                        <tr>
                            <th>이메일</th>
                            <td><?= $member['mb_email'] ?></td>
                        </tr>
                        <tr>
                            <th>연락처</th>
                            <td><?= $member['mb_hp'] ?></td>
                        </tr>
                        <tr>
                            <td colspan=2 style="font-size: 12px; color: #999999;">
                                주문자 정보 변경은 <span style="font-size: 12px; color: #3a3a3a;">마이페이지 > 회원정보수정</span> 에서 수정하실 수 있습니다.
                            </td>
                        </tr>
                    </table>
                </div>
                <form name="formOrderDelivery" method="POST">
                    <input type="hidden" name="od_b_addr3" value="true">
                    <input type="hidden" name="od_b_addr_jibeon" value="true">
                    <div id="order-delivery-info">
                        <div class="order-info-title" style="display: flex; justify-content: space-between;">
                            <span>배송정보</span>
                            <span>
                                <button type="button" class="btn btn-cart-action white change-address">배송지변경</button>
                            </span>
                        </div>
                        <table id="table-order-delivery-info">
                            <tr>
                                <th>수령인</th>
                                <td>
                                    <?= $od['od_b_name'] ?>
                                </td>
                            </tr>
                            <tr>
                                <th>휴대폰번호</th>
                                <td>
                                    <?= $od['od_b_hp'] ?>
                                </td>
                            </tr>
                            <tr>
                                <th>배송지</th>
                                <td>
                                    <div>(<?= $od['od_b_zip1'] ?><?= $od['od_b_zip2'] ?>)</div>
                                    <div><?= $od['od_b_addr1'] ?> <?= $od['od_b_addr2'] ?></div>
                                </td>
                            </tr>
                            <tr>
                                <th>배송 메세지</th>
                                <td>
                                    <?= $od['od_memo'] ?>
                                </td>
                            </tr>
                        </table>
                        <table id="table-change-delivery-info">
                            <tr>
                                <th>수령인<span class="point-require">*</span></th>
                                <td>
                                    <input type="text" name="od_b_name" value="<?= $od['od_b_name'] ?>" placeholder="수령인 입력">
                                </td>
                            </tr>
                            <tr>
                                <th>휴대폰번호<span class="point-require">*</span></th>
                                <td style="font-size: 0;">
                                    <select name="od_b_hp_1" id="od_b_hp_1" style="width: 100px; margin-right: 20px;">
                                        <option value="010" <?= get_selected(substr($od['od_b_hp'], 0, 3), "010") ?>>010</option>
                                        <option value="011" <?= get_selected(substr($od['od_b_hp'], 0, 3), "011") ?>>011</option>
                                        <option value="016" <?= get_selected(substr($od['od_b_hp'], 0, 3), "016") ?>>016</option>
                                        <option value="017" <?= get_selected(substr($od['od_b_hp'], 0, 3), "017") ?>>017</option>
                                        <option value="018" <?= get_selected(substr($od['od_b_hp'], 0, 3), "018") ?>>018</option>
                                        <option value="019" <?= get_selected(substr($od['od_b_hp'], 0, 3), "019") ?>>019</option>
                                    </select>
                                    <input type="number" name="od_b_hp_2" value="<?= substr($od['od_b_hp'], 3) ?>" placeholder="휴대폰번호 입력" style="width: 220px;"> </td>
                            </tr>
                            <tr>
                                <th>배송지<span class="point-require">*</span></th>
                                <td>
                                    <input type="text" name="od_b_zip" value="<?= $od['od_b_zip1'] . $od['od_b_zip2'] ?>" placeholder="우편번호" onclick="win_zip('formOrderDelivery','od_b_zip' , 'od_b_addr1', 'od_b_addr2', 'od_b_addr3','od_b_addr_jibeon');" readonly aria-describedby="btn-mb-zip" value="<?= $od['od_b_zip'] ?>">
                                    <button class="btn btn-cart-action btn-black-2" type="button" style="margin-top: 0; vertical-align: top; margin-left: 14px;" onclick="win_zip('formOrderDelivery','od_b_zip' , 'od_b_addr1', 'od_b_addr2', 'od_b_addr3','od_b_addr_jibeon');">우편번호</button>
                                </td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>
                                    <input type="text" name="od_b_addr1" id="od_b_addr1" value="<?= $od['od_b_addr1'] ?>" placeholder="기본주소" style="width: 700px;" onclick="win_zip('formOrderDelivery','od_b_zip' , 'od_b_addr1', 'od_b_addr2', 'od_b_addr3','od_b_addr_jibeon');" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>
                                    <input type="text" name="od_b_addr2" id="od_b_addr2" value="<?= $od['od_b_addr2'] ?>" placeholder="상세주소" style="width: 700px;">

                                </td>
                            </tr>
                            <tr>
                                <th>배송 메세지</th>
                                <td>
                                    <select class="select-order-memo" name="od_memo" placeholder="배송 메세지를 선택해주세요" style="width: 700px;">
                                        <option>배송 메세지를 선택해주세요</option>
                                        <option value="배송 전 전화 혹은 문자 남겨주세요">배송 전 전화 혹은 문자 남겨주세요</option>
                                        <option value="부재 시 전화 혹은 문자 남겨주세요">부재 시 전화 혹은 문자 남겨주세요</option>
                                        <option value="부재 시 경비실에 맡겨주세요">부재 시 경비실에 맡겨주세요</option>
                                        <option value="부재 시 무인택배함에 넣어주세요">부재 시 무인택배함에 넣어주세요</option>
                                        <option value="부재 시 문앞에 놔주세요">부재 시 문앞에 놔주세요</option>
                                        <option value="user">직접입력</option>
                                    </select>
                                    <input style="width: calc(100% - 20px); margin-top: 8px;" class="select-order-memo-user" type="text" name="od_memo_user" placeholder="50자 이내로 입력해주세요." value="<?= $od['od_memo'] ?>">
                                </td>
                            </tr>
                            <tr>
                                <td colspan=2 style="text-align: right;">
                                    <button type="button" class="btn btn-cart-action white change-address">취소</button>
                                    <button type="submit" class="btn btn-cart-action">저장</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>

            <div class="cart-order-info order-info-right" style="padding: unset; border: unset; padding-left: 20px; padding-bottom: 100px;">
                <div class="order-info-title">결제금액</div>
                <table>
                    <tr>
                        <th>총 상품 금액</th>
                        <td id="confirm-od-price"><?= number_format($tot_before_price) ?>원</td>
                    </tr>
                    <? if ($cancel_price > 0) : ?>
                    <tr>
                        <th>취소금액</th>
                        <td><?= number_format($cancel_price) ?>원</td>
                    </tr>
                    <? endif ?>
                    <tr>
                        <th>즉시할인</th>
                        <td id="confirm-discount"><?= $tot_sell_price > 0 ? "-" . number_format($tot_sell_price) : 0 ?>원</td>
                    </tr>
                    <tr>
                        <th>쿠폰할인</th>
                        <td id="confirm-coupon"><?= $sale_price > 0 ? "-" . number_format($sale_price) : 0 ?>원</td>
                    </tr>
                    <tr>
                        <th>배송비</th>
                        <!-- <td id="confirm-sc-price"><?= $total_send_cost > 0 ? number_format($total_send_cost) : 0 ?>원</td> -->
                        <td id="confirm-sc-price">무료</td>
                    </tr>
                    <tr>
                        <th>포인트 사용</th>
                        <td id="confirm-point"><?= $od['od_receipt_point'] > 0 ? "-" . number_format($od['od_receipt_point']) : 0 ?>원</td>
                    </tr>
                    <tr>
                        <th style="font-size: 22px; font-weight: 500; color: #000000; border-top: 1px solid #333333;">총 결제 금액</th>
                        <td style="font-size: 26px; font-weight: 500; color: #000000; border-top: 1px solid #333333;" id="confirm-total"><?= number_format($tot_price) ?>원</td>
                    </tr>
                    <tr>
                        <td colspan=2 style="font-size: 12px; color: #333333; font-weight: 400; text-align: left; word-break: keep-all;">
                            <!-- 카드사별 이벤트 포인트 사용 등에 따라 실제 결제금액과 표기된 총 결제금액에 차이가 있을 수 있습니다. -->
                        </td>
                    </tr>
                </table>

                <div class="order-info-title" style="display: flex; justify-content: space-between; margin-top: 60px;">
                    <span>결제금액</span>
                    <span>
                        <? if ($disp_receipt) { ?>
                        <?
                            if ($od['od_settle_case'] == '휴대전화') {
                                $LGD_TID      = $od['od_tno'];
                                //$LGD_MERTKEY  = $config['cf_lg_mert_key'];
                                $LGD_HASHDATA = md5($LGD_MID . $LGD_TID . $LGD_MERTKEY);
                                $hp_receipt_script = 'showReceiptByTID(\'' . $LGD_MID . '\', \'' . $LGD_TID . '\', \'' . $LGD_HASHDATA . '\');';
                            ?>
                        <button type="button" class="btn btn-cart-action white" onclick="<?= $hp_receipt_script; ?>">영수증발급</button>
                        <? } ?>

                        <?
                            if ($od['od_settle_case'] == '신용카드' || is_inicis_order_pay($od['od_settle_case'])) {
                                $LGD_TID      = $od['od_tno'];
                                $LGD_HASHDATA = md5($LGD_MID . $LGD_TID . $LGD_MERTKEY);
                                $card_receipt_script = "showReceiptByTID(\'' . $LGD_MID . '\', \'' . $LGD_TID . '\', \'' . $LGD_HASHDATA . '\');";
                                $card_receipt_script = "showReceiptByTID('{$LGD_MID}','{$LGD_TID}','{$LGD_HASHDATA}');";
                            ?>
                        <button type="button" class="btn btn-cart-action white" onclick="<?= $card_receipt_script; ?>">영수증발급</button>
                        <? } ?>

                        <?
                            if ($od['od_settle_case'] == 'KAKAOPAY') {
                                $card_receipt_script = 'window.open(\'https://mms.cnspay.co.kr/trans/retrieveIssueLoader.do?TID=' . $od['od_tno'] . '&type=0\', \'popupIssue\', \'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=420,height=540\');';
                            ?>
                        <button type="button" class="btn btn-cart-action white" onclick="<?= $card_receipt_script; ?>">영수증발급</button>
                        <? } ?>
                        <? } ?>
                    </span>
                </div>
                <table>
                    <tr>
                        <th>결제수단</th>
                        <td style="font-size: 16px;"><?= $od['od_settle_case'] ?>(<?= $od['od_bank_account'] ?>)</td>
                    </tr>
                    <tr>
                        <th>주문접수일시</th>
                        <td style="font-size: 16px;"><?= date("Y.m.d H:i", strtotime($od['od_time'])) ?></td>
                    </tr>
                    <tr>
                        <th>결제완료일시</th>
                        <td style="font-size: 16px;"><?= date("Y.m.d H:i", strtotime($od['od_receipt_time'])) ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="on-big" style="text-align: center;">
            <a href="/"><button type="button" class="btn btn-cart-action" style="width: 340px; height: 50px; font-size: 18px; font-weight: 500;">계속 쇼핑하기</button></a>
        </div>

        <!-- 모바일 -->

        <div class="on-small">
            <div class="order-info-separator"></div>
            <div id="order-member-info-mobile">
                <div class="order-info-title">주문자정보</div>
                <table id="table-order-member-info-mobile">
                    <tr>
                        <th>이름</th>
                        <td><?= $member['mb_name'] ?></td>
                    </tr>
                    <tr>
                        <th>이메일</th>
                        <td><?= $member['mb_email'] ?></td>
                    </tr>
                    <tr>
                        <th>연락처</th>
                        <td><?= $member['mb_hp'] ?></td>
                    </tr>
                    <tr>
                        <td colspan=2 style="font-size: 10px; color: #999999; padding-left: 14px;">
                            주문자 정보 변경은 <span style="font-size: 10px; color: #3a3a3a;">마이페이지 > 회원정보수정</span> 에서 수정하실 수 있습니다.
                        </td>
                    </tr>
                </table>
            </div>
            <?php $order_items = sql_query($sql); ?>
            <div class="order-info-separator"></div>
            <div class="order-info-title">주문상품(<?= sql_num_rows($order_items) ?>)</div>
            <div id="order-product-info-mobile">
                <table>
                    <?php while (false != ($row = sql_fetch_array($order_items))) : ?>
                        <?
                        if (isset($od_status_set_count[$row['ct_status']])) $od_status_set_count[$row['ct_status']]++;
                        $image = get_it_image($row['it_id'], 120, 120, '', '', $row['it_name']);
                        $ct_send_cost_str = "-";
                        $sql_sc = " SELECT SUM((b.its_final_price + a.io_price) * a.ct_qty) as price,
                                SUM((b.its_price + a.io_price) * a.ct_qty) as before_price,
                                SUM(a.ct_qty) as qty,
                                count(distinct a.ct_id) as ct_cnt
                                from lt_shop_cart as a
                                inner join lt_shop_item_sub as b on a.it_id = b.it_id and a.its_no = b.its_no
                                inner join lt_shop_item as c on a.it_id = c.it_id
                                where  a.od_id = '$od_id'
                                and    a.it_id = '{$row['it_id']}' ";

                        $sc = sql_fetch($sql_sc);
                        if ($sc) {
                            if ($row['it_sc_type'] == '2' && $de_individual_costs_use == '1') {

                                //선택설정(상품별 개별배송비 사용 필수) : 상품별로 배송비 부과
                                $ct_send_cost = (int) get_item_sendcost($row['it_id'], $sc['price'], $sc['qty'], $od_id, $sc['before_price']);
                                $rowspan = $sc['ct_cnt'];

                                if ($ct_send_cost > 0) $total_send_cost = (int) $total_send_cost - (int) $ct_send_cost;
                            } else {
                                $rowspan = $tot_rows;
                                $ct_send_cost = $total_send_cost;
                            }
                        }

                        if ($ct_send_cost == 0) $ct_send_cost_str = "무료";
                        else $ct_send_cost_str = number_format($ct_send_cost) . " 원";

                        $tot_rows--;

                        $it_discount = $row['before_price'] - $row['ct_price'];
                        $it_discount_ratio = $it_discount > 0 ? ($it_discount / $row['before_price']) * 100 : 0;
                        $it_before_price = ($row['ct_price'] + $row['io_price']) * $row['ct_qty'];

                        $dataComplate[] = array("productCode" => $row['it_id'],"productName" => $row['it_name'],"price" => $row['before_price'],"dcPrice" => $row['ct_price'],"qty" => $row['ct_qty']);
                        $dataComplateString = json_encode($dataComplate);
                        ?>
                        <tr>
                            <th style="width: 89px;" onclick="location.href='/shop/item.php?it_id=<?= $row['it_id'] ?>'">
                                <?= $image ?>
                            </th>
                            <td style="text-align: left; vertical-align: top;" onclick="location.href='/shop/item.php?it_id=<?= $row['it_id'] ?>'">
                                <div class="swiper_item_detail">
                                    <div class="swiper_item_brand" style="font-size: 10px;"><?= $row['it_brand'] ?> <span class ='hocName<?= $row['io_hoching'] ?>'></span></div>
                                    <div class="swiper_item_name" style="font-size: 12px;"><?= $row['it_name'] ?></div>
                                    <div class="swiper_item_price_area">
                                        <div style="font-size: 12px;"><?= number_format($row['ct_price']) ?>원</div>
                                        <?
                                        $it_discount = $row['before_price'] - $row['ct_price'];
                                        $it_discount_ratio = ($it_discount / $row['before_price']) * 100;
                                        ?>
                                        <? if ($it_discount_ratio > 0) : ?>
                                        <div>
                                            <span class="price-del"><del><?= number_format($row['before_price']) ?></del>원</span>
                                            <span class="price-dis" style="color: #e65026; font-size: 10px;"><?= number_format($it_discount_ratio) ?>%</span>
                                        </div>
                                        <? endif ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>주문수량</th>
                            <td style="text-align: right; font-size: 14px; font-weight: 500; color: #333333; line-height: 40px;"><?= number_format($row['ct_qty']) ?>개</td>
                        </tr>
                        <tr>
                            <th style="vertical-align: top;">주문금액</th>
                            <td style="vertical-align: top; padding-bottom: 16px; text-align: right; font-size: 14px; font-weight: 500; color: #333333;"><?= number_format($it_before_price) ?>원</td>
                        </tr>
                        <tr class="row-separator">
                            <td colspan="2"></td>
                        </tr>
                    <?php endwhile ?>
                </table>
            </div>
            <div class="order-info-separator"></div>
            <div id="order-delivery-info-mobile">
                <div class="order-info-title" style="display: flex; justify-content: space-between;">
                    <span>배송정보</span>
                    <span>
                        <button type="button" id="change-invoice-mobile" class="btn btn-cart-action white small">배송지변경</button>
                    </span>
                </div>
                <div id="order-delivery-info-preview" class="active">
                    <div id="delivery-preview-name"><?= $od['od_b_name'] ?></div>
                    <div id="delivery-preview-hp"><?= $od['od_b_hp'] ?></div>
                    <div id="delivery-preview-address">(<?= $od['od_b_zip1'] . $od['od_b_zip2'] ?>)<?= $od['od_b_addr1'] . ' ' . $od['od_b_addr2'] ?></div>
                    <div id="delivery-preview-memo"><?= $od['od_memo'] ?></div>
                </div>
                <div id="form-order-delivery-info">
                    <table id="table-order-delivery-info-mobile">
                        <tr>
                            <th>수령인<span class="point-require">*</span></th>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="od_b_name_mobile" data-target="od_b_name" value="<?= $od['od_b_name'] ?>" placeholder="수령인 입력" style="width: 100%;">
                            </td>
                        </tr>
                        <tr>
                            <th>휴대폰번호<span class="point-require">*</span></th>
                        </tr>
                        <tr>
                            <td style="font-size: 0;">
                                <select name="od_b_hp_1_mobile" data-target="od_b_hp_1" id="od_b_hp_1_mobile" style="width: 100px; margin-right: 20px;">
                                    <option value="010" <?= get_selected($od_b_hp_1, "010") ?>>010</option>
                                    <option value="011" <?= get_selected($od_b_hp_1, "011") ?>>011</option>
                                    <option value="016" <?= get_selected($od_b_hp_1, "016") ?>>016</option>
                                    <option value="017" <?= get_selected($od_b_hp_1, "017") ?>>017</option>
                                    <option value="018" <?= get_selected($od_b_hp_1, "018") ?>>018</option>
                                    <option value="019" <?= get_selected($od_b_hp_1, "019") ?>>019</option>
                                </select>
                                <input type="number" name="od_b_hp_2_mobile" data-target="od_b_hp_2" value="<?= $od_b_hp_2 ?>" placeholder="휴대폰번호 입력" style="width: 220px;">
                            </td>
                        </tr>
                        <tr>
                            <th>배송지<span class="point-require">*</span></th>
                        </tr>
                        <tr>
                            <td style="display: flex; justify-content: space-between;">
                                <input type="text" name="od_b_zip_mobile" data-target="od_b_zip" value="<?= $od_b_zip ?>" placeholder="우편번호" onclick="win_zip('formOrderDelivery','od_b_zip_mobile', 'od_b_addr1_mobile', 'od_b_addr2_mobile', 'od_b_addr3','od_b_addr_jibeon');" readonly aria-describedby="btn-mb-zip" value="<?= $od_b_zip ?>" style="width: calc(100% - 90px);">
                                <button class="btn btn-cart-action btn-black-2" type="button" style="margin-top: 0; vertical-align: top; margin-left: 10px; width: 80px !important;" onclick="win_zip('formOrderDelivery','od_b_zip_mobile' , 'od_b_addr1_mobile', 'od_b_addr2_mobile', 'od_b_addr3','od_b_addr_jibeon');">우편번호</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="od_b_addr1_mobile" data-target="od_b_addr1" id="od_b_addr1_mobile" style="width: 100%; margin-top: 10px;" value="<?= $od_b_addr1 ?>" placeholder="기본주소" onclick="win_zip('formOrderDelivery','od_b_zip_mobile' , 'od_b_addr1_mobile', 'od_b_addr2_mobile', 'od_b_addr3','od_b_addr_jibeon');" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="od_b_addr2_mobile" data-target="od_b_addr2" id="od_b_addr2_mobile" style="width: 100%; margin-top: 10px;" value="<?= $od_b_addr2 ?>" placeholder="상세주소">
                            </td>
                        </tr>
                    </table>
                    <div class="custom-checkbox" style="padding-left: 22px; padding-top: 10px;">
                        <input type="checkbox" id="check-append-address-moblie" class="custom-control-input" name="ad_append_mobile" data-target="od_append">
                        <label class="custom-control-label" for="check-append-address-moblie" style="line-height: normal; font-size: 12px; color: #7f7f7f;">배송지목록에 추가</label>
                    </div>
                    <div class="custom-checkbox" style="height: 28px; padding: 0 14px; padding-left: 36px;">
                        <input type="checkbox" id="check-default-address-moblie" class="custom-control-input" name="ad_default_mobile" data-target="od_default">
                        <label class="custom-control-label" for="check-default-address-moblie" style="line-height: normal; font-size: 12px; color: #7f7f7f;">기본배송지로 저장</label>
                    </div>
                    <div style="font-size: 12px; font-weight: 500; color: #3a3a3a; padding-top: 16px; padding-left: 14px;">배송 메세지</div>
                    <div style="padding: 0 14px; padding-bottom: 28px;">
                        <select class="select-order-memo" name="od_memo_mobile" data-target="od_memo" placeholder="배송 메세지를 선택해주세요" style="width: 100%;">
                            <option>배송 메세지를 선택해주세요</option>
                            <option value="배송 전 전화 혹은 문자 남겨주세요">배송 전 전화 혹은 문자 남겨주세요</option>
                            <option value="부재 시 전화 혹은 문자 남겨주세요">부재 시 전화 혹은 문자 남겨주세요</option>
                            <option value="부재 시 경비실에 맡겨주세요">부재 시 경비실에 맡겨주세요</option>
                            <option value="부재 시 무인택배함에 넣어주세요">부재 시 무인택배함에 넣어주세요</option>
                            <option value="부재 시 문앞에 놔주세요">부재 시 문앞에 놔주세요</option>
                            <option value="user">직접입력</option>
                        </select>
                        <input style="width: 100%; margin-top: 8px;" class="select-order-memo-user" type="text" name="od_memo_user_mobile" data-target="od_memo_user" placeholder="50자 이내로 입력해주세요.">
                    </div>
                </div>
            </div>
            <div class="order-info-separator"></div>
            <div id="order-calculate-info-mobile">
                <div class="order-info-title">결제내역</div>
                <table>
                    <tr>
                        <th>총 상품 금액</th>
                        <td id="confirm-od-price-mobile"><?= number_format($tot_before_price) ?>원</td>
                    </tr>
                    <? if ($cancel_price > 0) : ?>
                    <tr>
                        <th>취소금액</th>
                        <td><?= number_format($cancel_price) ?>원</td>
                    </tr>
                    <? endif ?>
                    <tr>
                        <th>즉시할인</th>
                        <td id="confirm-discount-mobile"><?= $tot_sell_price > 0 ? "-" . number_format($tot_sell_price) : 0 ?>원</td>
                    </tr>
                    <tr>
                        <th>쿠폰할인</th>
                        <td id="confirm-coupon-mobile"><?= $sale_price > 0 ? "-" . number_format($sale_price) : 0 ?>원</td>
                    </tr>
                    <tr>
                        <th>배송비</th>
                        <!-- <td id="confirm-sc-price-mobile"><?= $total_send_cost > 0 ? "-" . number_format($total_send_cost) : 0 ?>원</td> -->
                        <td id="confirm-sc-price-mobile">무료</td>
                    </tr>
                    <tr>
                        <th>포인트 사용</th>
                        <td id="confirm-point-mobile"><?= $od['od_receipt_point'] > 0 ? "-" . number_format($od['od_receipt_point']) : 0 ?>원</td>
                    </tr>
                    <tr>
                        <th style="font-size: 16px; font-weight: 500; color: #000000; border-top: 1px solid #e0e0e0; padding-top: 10px;">총 결제 금액</th>
                        <td style="font-size: 18px; font-weight: 500; color: #000000; border-top: 1px solid #e0e0e0; padding-top: 10px;" id="confirm-total-mobile"><?= number_format($tot_price) ?>원</td>
                    </tr>
                    <tr>
                        <td colspan=2 style="font-size: 10px; color: #333333; font-weight: 400; text-align: left; word-break: keep-all; line-height: 18px;padding: 20px 14px;">
                            <!-- 카드사별 이벤트 포인트 사용 등에 따라 실제 결제금액과 표기된 총 결제금액에 차이가 있을 수 있습니다. -->
                        </td>
                    </tr>
                </table>
            </div>
            <div class="order-info-separator"></div>
            <div class="order-info-title" style="display: flex; justify-content: space-between;">
                <span>결제수단</span>
                <span>
                    <? if ($disp_receipt) { ?>
                    <?
                        if ($od['od_settle_case'] == '휴대전화') {
                            $LGD_TID      = $od['od_tno'];
                            //$LGD_MERTKEY  = $config['cf_lg_mert_key'];
                            $LGD_HASHDATA = md5($LGD_MID . $LGD_TID . $LGD_MERTKEY);
                            $hp_receipt_script = 'showReceiptByTID(\'' . $LGD_MID . '\', \'' . $LGD_TID . '\', \'' . $LGD_HASHDATA . '\');';
                        ?>
                    <button type=" button" class="btn btn-cart-action white small" onclick="<?= $hp_receipt_script; ?>">영수증발급</button>
                    <? } ?>

                    <?
                        if ($od['od_settle_case'] == '신용카드' || is_inicis_order_pay($od['od_settle_case'])) {
                            $LGD_TID      = $od['od_tno'];
                            $LGD_HASHDATA = md5($LGD_MID . $LGD_TID . $LGD_MERTKEY);
                            $card_receipt_script = "showReceiptByTID(\'' . $LGD_MID . '\', \'' . $LGD_TID . '\', \'' . $LGD_HASHDATA . '\');";
                            $card_receipt_script = "showReceiptByTID('{$LGD_MID}','{$LGD_TID}','{$LGD_HASHDATA}');";
                        ?>
                    <button type="button" class="btn btn-cart-action white small" onclick="<?= $card_receipt_script; ?>">영수증발급</button>
                    <? } ?>

                    <?
                        if ($od['od_settle_case'] == 'KAKAOPAY') {
                            $card_receipt_script = 'window.open(\'https://mms.cnspay.co.kr/trans/retrieveIssueLoader.do?TID=' . $od['od_tno'] . '&type=0\', \'popupIssue\', \'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=420,height=540\');';
                        ?>
                    <button type="button" class="btn btn-cart-action white small" onclick="<?= $card_receipt_script; ?>">영수증발급</button>
                    <? } ?>
                    <? } ?>
                </span>
            </div>
            <div id="order-payment-info-mobile">
                <table>
                    <tr>
                        <th>결제수단</th>
                        <td><?= $od['od_settle_case'] ?>(<?= $od['od_bank_account'] ?>)</td>
                    </tr>
                    <tr>
                        <th>주문접수일시</th>
                        <td><?= date("Y.m.d H:i", strtotime($od['od_time'])) ?></td>
                    </tr>
                    <tr>
                        <th>결제완료일시</th>
                        <td><?= date("Y.m.d H:i", strtotime($od['od_receipt_time'])) ?></td>
                    </tr>
                </table>
            </div>
            <div class="order-info-separator"></div>
            <div style="padding: 0 14px;">
                <a href="/">
                    <button type="button" class="btn btn-cart-action white" style="width: 100%; margin-top: 24px; font-size: 18px;">계속 쇼핑하기</button>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- 배송지추가/수정 모바일 -->
<div class="modal fade bottom" id="modal-order-address-form-mobile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 100%; height: auto; margin-left: unset; padding: unset !important;">
            <div style="text-align: center; height: 50px; border-bottom: 1px solid #e0e0e0; font-size: 18px; font-weight: 500; color: #090909; line-height: 50px;">
                <span class="order-address-update-title">배송지변경</span>
                <button type="button" data-dismiss="modal" aria-label="close" style="width: 18px; height: 18px; background: url(/img/re/cancle@3x.png) center center no-repeat; background-size: cover; position: absolute; right: 12px; top: 15px; border: unset;"></button>
            </div>
            <form name="formOrderAddressUpdateMobile" method="POST">
                <div id="modal-order-address-form-content-mobile">
                    <input type="hidden" name="od_b_mobile" value="true">
                    <input type="hidden" name="od_b_addr3" value="true">
                    <input type="hidden" name="od_b_addr_jibeon" value="true">
                    <table>
                        <tr>
                            <th style="width: 140px;">수령인<span class="point-require">*</span></th>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" placeholder="수령인 입력" name="od_b_name" required style="width: 100%;" value="<?= $od['od_b_name'] ?>">
                            </td>
                        </tr>
                        <tr>
                            <th>휴대폰번호<span class="point-require">*</span></th>
                        </tr>
                        <tr>
                            <td style="font-size: 0;">
                                <select style="width: 100px; margin-right: 14px;" name="od_b_hp_1">
                                    <option value="010" <?= get_selected(substr($od['od_b_hp'], 0, 3), "010") ?>>010</option>
                                    <option value="011" <?= get_selected(substr($od['od_b_hp'], 0, 3), "011") ?>>011</option>
                                    <option value="016" <?= get_selected(substr($od['od_b_hp'], 0, 3), "016") ?>>016</option>
                                    <option value="017" <?= get_selected(substr($od['od_b_hp'], 0, 3), "017") ?>>017</option>
                                    <option value="018" <?= get_selected(substr($od['od_b_hp'], 0, 3), "018") ?>>018</option>
                                    <option value="019" <?= get_selected(substr($od['od_b_hp'], 0, 3), "019") ?>>019</option>
                                </select>
                                <input type="number" placeholder="휴대폰번호 입력" name="od_b_hp_2" style="width: calc(100% - 114px)" required value="<?= substr($od['od_b_hp'], 3) ?>">
                            </td>
                        </tr>
                        <tr>
                            <th>배송지<span class="point-require">*</span></th>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 10px; font-size: 0;">
                                <input type="text" name="od_b_zip" placeholder="우편번호" onclick="win_zip('formOrderAddressUpdateMobile','od_b_zip' , 'od_b_addr1', 'od_b_addr2', 'od_b_addr3','od_b_addr_jibeon');" style="width: calc(100vw - 118px);" readonly required value="<?= $od['od_b_zip1'] ?><?= $od['od_b_zip2'] ?>">
                                <button class="btn btn-cart-action btn-black-2" type="button" style="margin-top: 0; vertical-align: top; margin-left: 10px; width: 80px !important;" onclick="win_zip('formOrderAddressUpdateMobile','od_b_zip' , 'od_b_addr1', 'od_b_addr2', 'od_b_addr3','od_b_addr_jibeon');">우편번호</button>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 10px;">
                                <input type="text" name="od_b_addr1" placeholder="기본주소" style="width: 100%;" onclick="win_zip('formOrderAddressUpdateMobile','od_b_zip' , 'od_b_addr1', 'od_b_addr2', 'od_b_addr3','od_b_addr_jibeon');" readonly required value="<?= $od['od_b_addr1'] ?>">
                            </td>
                        </tr>
                        <tr>
                            <td style=" padding-bottom: 10px;">
                                <input type="text" name="od_b_addr2" placeholder="상세주소" style="width: 100%;" required value="<?= $od['od_b_addr2'] ?>">
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="modal-order-address-form-button-mobile">
                    <button type="button" class="btn btn-cart-action white" onclick="$('#modal-order-address-form-mobile').modal('hide')">취소</button>
                    <button type="submit" class="btn btn-cart-action">저장</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?
// LG 현금영수증 JS
if ($od['od_pg'] == 'lg') {
    if ($default['de_card_test'] && $od['od_type'] != 'R') {
        echo '<script type="text/javascript" src="http://pgweb.uplus.co.kr:7085/WEB_SERVER/js/receipt_link.js"></script>' . PHP_EOL;
    } else {
        echo '<script type="text/javascript" src="https://pgweb.uplus.co.kr/WEB_SERVER/js/receipt_link.js"></script>' . PHP_EOL;
    }
}

$tmp_ods = array();
foreach ($od_status_set_count as $cs => $cc) $tmp_ods[] = $cc;
?>
<script type="text/javascript">
    const od_status_count = [<?= implode(',', $tmp_ods) ?>];
    $(".order-detail-circle-count").each(function(idx, elem) {
        $(elem).text(od_status_count[idx]);
    });

    $(".change-address").on("click", function() {
        $("#order-delivery-info").toggleClass("change");
    });

    $("#change-invoice-mobile").on("click", function() {
        return $("#modal-order-address-form-mobile").modal("show");
    });

    $(".select-order-memo").on("change", function() {
        if ($(this).find("option:selected").val() == "user") {
            $(".select-order-memo-user").addClass("active");
        } else {
            $(".select-order-memo-user").removeClass("active");
        }
    });
</script>
<!-- Enliple Tracker Start 0514 google -->
<script type="text/javascript">
let broswerTrackerMOC = navigator.userAgent;
let deviceTrackerMOC = "W";
if (broswerTrackerMOC.indexOf("Mobile")>-1) { 
    deviceTrackerMOC = "M";
}
let dataComplateString = `<? echo $dataComplateString ?>`;
let dataComplateJson = JSON.parse(dataComplateString);
let odId = '<? echo $od_id ?>';
let totPrice = '<? echo $tot_price ?>';
var ENP_VAR = { conversion: { product: [] } };
let totalQty = 0;
ga('create', 'UA-153591131-1');
ga('require', 'ec');

for (let i =0; i < dataComplateJson.length ; i++) {
    totalQty += Number(dataComplateJson[i].qty)
    ENP_VAR.conversion.product.push(dataComplateJson[i]);
    ga('ec:addProduct', {
    'id': dataComplateJson[i].productCode,
    'name': dataComplateJson[i].productName,
    'category': '',
    'brand': '',
    'variant': '',
    'price': dataComplateJson[i].dcPrice,
    'quantity': dataComplateJson[i].qty,
    });
}
ga('ec:setAction', 'purchase', {
    'id': odId,
    'affiliation': '',
    'revenue': totPrice,
    'tax': '',
    'shipping': '',
    'coupon': ''    
});
ga('send', 'pageview');     

totalQty = String(totalQty);

ENP_VAR.conversion.ordCode= odId;
ENP_VAR.conversion.totalPrice = totPrice;
ENP_VAR.conversion.totalQty = totalQty;

	(function(a,g,e,n,t){a.enp=a.enp||function(){(a.enp.q=a.enp.q||[]).push(arguments)};n=g.createElement(e);n.async=!0;n.defer=!0;n.src="https://cdn.megadata.co.kr/dist/prod/enp_tracker_self_hosted.min.js";t=g.getElementsByTagName(e)[0];t.parentNode.insertBefore(n,t)})(window,document,"script");
enp('create', 'conversion', 'litandard', { device: deviceTrackerMOC }); // W:웹, M: 모바일, B: 반응형
enp('send', 'conversion', 'litandard');
</script>
<!-- Enliple Tracker End -->
<!-- START NEXDI 0222 -->
<script type="text/javascript" charset="UTF-8" src="//t1.daumcdn.net/adfit/static/kp.js"></script>
<script type="text/javascript">
     kakaoPixel('2967409213611789029').pageView();
     kakaoPixel('2967409213611789029').purchase('<? echo $tot_price ?>');
</script>
<!-- END NEXDI 0222 -->
<!-- START NEXDI 0326 -->
<!-- 전환페이지 설정 -->
<script type="text/javascript" src="//wcs.naver.net/wcslog.js"></script>
<script type="text/javascript">
var _nasa={};
if(window.wcs) _nasa["cnv"] = wcs.cnv("1","<? echo $tot_price ?>"); // 전환유형, 전환가치 설정해야함. 설치매뉴얼 참고
</script>
<!-- END NEXDI 0326 -->
<!-- START NEXDI 0720 -->
<!-- Event snippet for 구매 conversion page -->
<script>
  gtag('event', 'conversion', {
      'send_to': 'AW-336156343/iIv7CPavlNcCELetpaAB',
      'value': <? echo $tot_price ?>,     
      'currency': 'KRW',
});
</script>

<!-- END NEXDI 0720 -->

<!-- Facebook Pixel Code 0720 -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '1185455058605170');
  fbq('track', 'PageView');

	fbq('track', 'Purchase', {value: <? echo $tot_price ?>, currency: 'KRW'});

</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=772782013392105&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code 0720-->

<!-- Facebook Pixel Code 1110 -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '427587602051149');
  fbq('track', 'PageView');

	fbq('track', 'Purchase', {value: <? echo $tot_price ?>, currency: 'KRW'});

</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=772782013392105&ev=PageView&noscript=1"
/></noscript>

<!-- End Facebook Pixel Code 1110-->

<?
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>