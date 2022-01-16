<?
$g5_title = "주문서";
$addr_list = '';
$sep = chr(30);
$sql = " select * from {$g5['g5_shop_order_address_table']} where mb_id = '{$member['mb_id']}' order by ad_default desc, ad_id desc limit 1 ";
$row = sql_fetch($sql);

if (!$row['ad_id']) {
    $od_b_name = get_text($member['mb_name']);
    $od_b_hp = get_text($member['mb_hp']);
    $od_b_tel = get_text($member['mb_tel']);
    $od_b_zip = $member['mb_zip1'] . $member['mb_zip2'];
    $od_b_addr1 = get_text($member['mb_addr1']);
    $od_b_addr2 = get_text($member['mb_addr2']);
    $od_b_addr3 = get_text($member['mb_addr3']);
    $od_b_addr_jibeon = get_text($member['mb_addr_jibeon']);
} else {
    $od_b_name = get_text($row['ad_name']);
    $od_b_hp = $row['ad_hp'];
    $od_b_tel = $row['ad_tel'];
    $od_b_zip = $row['ad_zip1'] . $row['ad_zip2'];
    $od_b_addr1 = $row['ad_addr1'];
    $od_b_addr2 = $row['ad_addr2'];
    $od_b_addr3 = $row['ad_addr3'];
    $od_b_addr_jibeon = $row['ad_jibeon'];
}

$od_b_hp = hyphen_hp_number($od_b_hp);
$tmp_od_b_hp = explode('-', $od_b_hp);
$od_b_hp_1 = $tmp_od_b_hp[0];
$od_b_hp_2 = $tmp_od_b_hp[1] . $tmp_od_b_hp[2];

$oc_cnt = $sc_cnt = 0;
if ($is_member) {
    // 주문쿠폰
    $sql = " select cp_id
						from {$g5['g5_shop_coupon_table']}
						where mb_id IN ( '{$member['mb_id']}', '전체회원' )
						and cp_method in ('0', '2', '4')
						and cp_start <= '" . G5_TIME_YMD . "'
						and cp_end >= '" . G5_TIME_YMD . "'
						and cp_minimum <= '$tot_sell_price' ";
    $res = sql_query($sql);

    for ($k = 0; $cp = sql_fetch_array($res); $k++) {
        if (is_used_coupon($member['mb_id'], $cp['cp_id']))
            continue;

        $oc_cnt++;
    }

    if ($send_cost > 0) {
        // 배송비쿠폰
        $sql = " select cp_id
							from {$g5['g5_shop_coupon_table']}
							where mb_id IN ( '{$member['mb_id']}', '전체회원' )
							and cp_method = '3'
							and cp_start <= '" . G5_TIME_YMD . "'
							and cp_end >= '" . G5_TIME_YMD . "'
							and cp_minimum <= '$tot_sell_price' ";
        $res = sql_query($sql);

        for ($k = 0; $cp = sql_fetch_array($res); $k++) {
            if (is_used_coupon($member['mb_id'], $cp['cp_id']))
                continue;

            $sc_cnt++;
        }
    }
}

ob_start();
echo G5_POSTCODE_JS;
?>


<link rel="stylesheet" href="/re/css/shop.css">
<script src="<?= G5_JS_URL; ?>/shop.js"></script>
<script src="<?= G5_JS_URL; ?>/shop.order.js"></script>
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

    .btn.btn-cart-action.white.active {
        background-color: #333333;
        border-color: #333333;
        color: #ffffff;
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

    #order-receipt {
        background-color: #ffffff;
    }

    html.targetInVisible #order-receipt {
        position: fixed;
        top: 150px;
    }

    @media (max-width: 1366px) {

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
            padding: 20px 14px;
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
<form name="forderform" id="forderform" method="post" action="<?= $order_action_url; ?>" autocomplete="off">    
    <input type="hidden" name="od_pcmobile" value="pc">
    <input type="hidden" name="od_b_hp" id="od_b_hp" value="<?= $od_b_hp ?>">
    <input type="hidden" name="od_b_addr3" id="od_b_addr3" value="<?= $od_b_addr3 ?>">
    <input type="hidden" name="od_b_addr_jibeon" id="od_b_addr_jibeon" value="<?= $od_b_addr_jibeon ?>">

    <input type="hidden" name="od_name" value="<?= get_text($member['mb_name']); ?>">
    <input type="hidden" name="od_price" value="<?= $tot_sell_price; ?>" id="order-od-price">
    <input type="hidden" name="org_od_price" value="<?= $tot_sell_price; ?>" id="order-org-price">
    <input type="hidden" name="org_before_price" value="<?= $tot_sell_price; ?>">
    <input type="hidden" name="od_send_cost" value="<?= $send_cost; ?>">
    <input type="hidden" name="od_send_cost2" value="0">
    <input type="hidden" name="item_coupon" value="0">
    <input type="hidden" name="od_coupon" value="0">
    <input type="hidden" name="od_send_coupon" value="0">
    <input type="hidden" name="od_send_cp_id" value="">
    <input type="hidden" name="od_goods_name" value="<?= $goods; ?>">
    <input type="hidden" name="od_type" value="<?= $od_type ?>" />
    <input type="hidden" name="od_tel" value="<?= get_text($member['mb_tel']) ?>" id="od_tel">
    <input type="hidden" name="od_zip" value="<?= $member['mb_zip1'] . $member['mb_zip2']; ?>" id="od_zip">
    <input type="hidden" name="od_addr1" value="<?= get_text($member['mb_addr1']) ?>" id="od_addr1">
    <input type="hidden" name="od_addr2" value="<?= get_text($member['mb_addr2']) ?>" id="od_addr2">
    <input type="hidden" name="od_addr3" value="<?= get_text($member['mb_addr3']) ?>" id="od_addr3">
    <input type="hidden" name="od_addr_jibeon" value="<?= get_text($member['mb_addr_jibeon']); ?>">
    <input type="hidden" name="od_hp" value="<?= get_text($member['mb_hp']); ?>">
    <input type="hidden" name="od_email" value="<?= $member['mb_email']; ?>">
    <input type="hidden" name="max_temp_point" value="<?= $member['mb_point']; ?>">
    <input type="hidden" name="od_temp_point" value=0>
    <input type="hidden" name="od_cp_id" value="[]">

    <input type="hidden" name="point_check" value="<?= $pointCheck ?>">

    <input type="radio" id="od_settle_card" name="od_settle_case" hidden value="신용카드" checked>
    <!-- <input type="radio" id="od_settle_iche" name="od_settle_case" hidden value="계좌이체"> -->

    <div id="list-wrapper">
        <div class="layout-offset">
            <div class="on-big" style="margin-bottom: 40px;">
                <div class="cart-step-title">주문서</div>
                <div style="text-align: center; font-size: 0; margin-top: 32px; margin-bottom: 8px;">
                    <span class="icon-cart-step step-1"><span></span></span>
                    <span class="icon-cart-step step-2 active" style="margin: 0 94px;"><span></span></span>
                    <span class="icon-cart-step step-3"><span></span></span>
                </div>
                <div style="text-align: center; font-size: 0;">
                    <span class="label-cart-step">장바구니</span>
                    <span class="label-cart-step active" style="margin: 0 96px;">주문서</span>
                    <span class="label-cart-step">주문완료</span>
                </div>
            </div>

            <div id="order-product-info" class="on-big">
                <div class="order-info-title">주문상품(<?= count($order_items) ?>)</div>
                <table>
                    <tr>
                        <th colspan=2>상품정보</th>
                        <th style="width: 220px;">수량</th>
                        <th style="width: 260px;">주문금액</th>
                        <th style="width: 220px;">배송비</th>
                    </tr>
                    <?php foreach ($order_items as $oi => $item) :
                        $dataCart[] = array("productCode" => $item['it_id'],"productName" => $item['it_name'],"price" => $item['before_price'],"dcPrice" => $item['ct_price'],"qty" => $item['ct_qty']);
                        $dataCartString = json_encode($dataCart);
                    ?>
                        <tr>
                            <td colspan=2 style="display: none;">
                                <input type="hidden" name="it_id[<?= $oi; ?>]" value="<?= $item['it_id']; ?>">
                                <input type="hidden" name="it_name[<?= $oi; ?>]" value="<?= get_text($item['it_name']); ?>">
                                <input type="hidden" name="it_price[<?= $oi; ?>]" value="<?= $item['view']['sell_price']; ?>">
                                <input type="hidden" id="order-coupon-id-<?= $item['ct_id'] ?>" name="cp_id[<?= $oi; ?>]" value="">
                                <input type="hidden" id="order-coupon-price-<?= $item['ct_id'] ?>" name="cp_price[<?= $oi; ?>]" value="0">
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 152px;">
                                <?= $item['view']['image'] ?>
                            </td>
                            <td style="text-align: left; vertical-align: top;">
                                <div class="swiper_item_detail">
                                    <div class="swiper_item_brand"><?= $item['it_brand'] ?> <span class ='hocName<?= $item['io_hoching'] ?>'></span></div>
                                    <div class="swiper_item_name"><?= $item['it_name'] ?></div>
                                    <div class="swiper_item_price_area">
                                        <span><?= number_format($item['ct_price']) ?><span style="font-size: 12px;">원</span></span>
                                        <?
                                        $it_discount = $item['before_price'] - $item['ct_price'];
                                        $it_discount_ratio = ($it_discount / $item['before_price']) * 100;
                                        ?>
                                        <?php if ($it_discount_ratio > 0) : ?>
                                            <span class="price-del"><del><?= number_format($item['before_price']) ?></del>원</span>
                                            <span class="price-dis" style="color: #e65026;"><?= number_format($it_discount_ratio) ?>%</span>
                                        <? endif ?>
                                    </div>
                                </div>
                            </td>
                            <td><?= number_format($item['ct_qty']) ?></td>
                            <td><?= number_format($item['view']['sum']['price']) ?></td>
                            <td><?= $send_cost == 0 ? "무료" : number_format($send_cost) ?></td>
                        </tr>
                    <?php endforeach ?>
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
                    <div id="order-delivery-info">
                        <div class="order-info-title" style="display: flex; justify-content: space-between;">
                            <span>배송정보</span>
                            <span>
                                <button type="button" class="btn btn-cart-action white" onclick=clearDeliveryInfo()>새로입력</button>
                                <button type="button" class="btn btn-cart-action white" onclick=openDeliveryModal()>배송지목록</button>
                            </span>
                        </div>
                        <table id="table-order-delivery-info">
                            <tr>
                                <th>수령인<span class="point-require">*</span></th>
                                <td>
                                    <input type="text" name="od_b_name" value="<?= $od_b_name ?>" placeholder="수령인 입력">
                                </td>
                            </tr>
                            <tr>
                                <th>휴대폰번호<span class="point-require">*</span></th>
                                <td style="font-size: 0;">
                                    <select name="od_b_hp_1" id="od_b_hp_1" style="width: 100px; margin-right: 20px;">
                                        <option value="010" <?= get_selected($od_b_hp_1, "010") ?>>010</option>
                                        <option value="011" <?= get_selected($od_b_hp_1, "011") ?>>011</option>
                                        <option value="016" <?= get_selected($od_b_hp_1, "016") ?>>016</option>
                                        <option value="017" <?= get_selected($od_b_hp_1, "017") ?>>017</option>
                                        <option value="018" <?= get_selected($od_b_hp_1, "018") ?>>018</option>
                                        <option value="019" <?= get_selected($od_b_hp_1, "019") ?>>019</option>
                                    </select>
                                    <input type="number" name="od_b_hp_2" value="<?= $od_b_hp_2 ?>" placeholder="휴대폰번호 입력" style="width: 220px;">
                                </td>
                            </tr>
                            <tr>
                                <th>배송지<span class="point-require">*</span></th>
                                <td>
                                    <input type="text" name="od_b_zip" value="<?= $od_b_zip ?>" placeholder="우편번호" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3','od_b_addr_jibeon');" readonly aria-describedby="btn-mb-zip" value="<?= $od_b_zip ?>">
                                    <button class="btn btn-cart-action btn-black-2" type="button" style="margin-top: 0; vertical-align: top; margin-left: 14px;" onclick="win_zip('forderform','od_b_zip' , 'od_b_addr1', 'od_b_addr2', 'od_b_addr3','od_b_addr_jibeon');">우편번호</button>
                                </td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>
                                    <input type="text" name="od_b_addr1" id="od_b_addr1" value="<?= $od_b_addr1 ?>" placeholder="기본주소" style="width: 700px;" onclick="win_zip('forderform','od_b_zip' , 'od_b_addr1', 'od_b_addr2', 'od_b_addr3','od_b_addr_jibeon');" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>
                                    <input type="text" name="od_b_addr2" id="od_b_addr2" value="<?= $od_b_addr2 ?>" placeholder="상세주소" style="width: 700px;">

                                </td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>
                                    <div class="custom-checkbox" style="height: 28px;">
                                        <input type="checkbox" id="check-default-address" class="custom-control-input" name="ad_default">
                                        <label class="custom-control-label" for="check-default-address" style="line-height: normal; font-size: 14px;">기본배송지로 저장</label>
                                    </div>
                                    <div class="custom-checkbox" style="height: 22px;">
                                        <input type="checkbox" id="check-append-address" class="custom-control-input" name="ad_append">
                                        <label class="custom-control-label" for="check-append-address" style="line-height: normal; font-size: 14px;">배송지목록에 추가</label>
                                    </div>
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
                                    <input style="width: calc(100% - 20px); margin-top: 8px;" class="select-order-memo-user" type="text" name="od_memo_user" placeholder="50자 이내로 입력해주세요.">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="order-discount-info">
                        <div class="order-info-title">추가할인</div>
                        <table>
                            <tr>
                                <th>쿠폰할인</th>
                                <td>
                                    <input type="text" id="order-discount-coupon" value="0" style="width: 240px;" readonly><span style="width: 110px; padding-left: 8px; display: inline-block;">원</span> <button type="button" class="btn btn-cart-action btn-order-open-coupon">쿠폰조회</button>
                                </td>
                            </tr>
                            <tr>
                                <th>포인트</th>
                                <td>
                                    <input type="text" id="order-discount-point" class="order-point-use" value="0" style="width: 240px;"><span style="width: 110px; padding-left: 8px; display: inline-block;">원</span> <button type="button" class="btn btn-cart-action btn-point-use">최대적용</button>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="line-height: normal;">보유 포인트 <?= number_format($member['mb_point']) ?>P</td>
                            </tr>
                            <tr>
                                <td colspan=2>
                                    <div style="font-size: 12px; font-weight: normal; font-stretch: normal; font-style: normal; line-height: 1.5; color: #6b6b6b; padding-top: 24px;">
                                        <span style="display: inline-block; width: 3px; height: 3px; border-radius: 3px; border: 1px solid #acacac; margin-right: 4px; vertical-align: middle;"></span>
                                        <?php if ($default['de_use_point_min_price'] > 0) : ?>
                                            포인트는 결제예정금액이 <?= number_format($default['de_use_point_min_price']) ?>원 이상일 때<?php if ($default['de_settle_min_point'] > 0) : ?>, 최소 <?= number_format($default['de_settle_min_point']) ?>P 보유 시<? endif ?>
                                        <? endif ?>
                                        사용할 수 있습니다.
                                        <br>
                                        <span style="display: inline-block; width: 3px; height: 3px; border-radius: 3px; border: 1px solid #acacac; margin-right: 4px; vertical-align: middle;"></span>
                                        최소결제금액은 1,000원입니다.
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="order-payment-info">
                        <div class="order-info-title" style="line-height: unset; padding: 40px 0 16px 0; margin-bottom: 16px; display: flex; justify-content: space-between;">
                            <span>결제수단</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                        <span style="font-size: 16px; font-weight: bold;">신용카드</span>
                        <span id="btn-toggle-benefit" style="font-size: 16px; font-weight: normal; color: #333333; cursor: pointer;"><span style="text-decoration: underline;">무이자혜택 안내</span> ></span>
                            <!-- <button type="button" class="btn-order-payment order-payment-card active" data-payment='od_settle_card' onclick="selectPayment(this)"> -->
                                <!-- <span class="icon-payment"></span><br>신용카드</button> -->
                            <!-- <button type="button" class="btn-order-payment order-payment-bank" data-payment='od_settle_iche' onclick="selectPayment(this)">
                                <span class="icon-payment"></span><br>실시간계좌이체</button> -->
                        </div>
                    </div>
                </div>
                <div id="order-receipt" class="cart-order-info order-info-right">
                    <div class="order-info-title" style="line-height: unset; padding-bottom: 16px;">결제금액</div>
                    <table>
                        <tr>
                            <th>총 상품 금액</th>
                            <td id="confirm-od-price"><?= number_format($tot_before_price) ?>원</td>
                        </tr>
                        <tr>
                            <th>즉시할인</th>
                            <td id="confirm-discount"><?= number_format($tot_before_price - $tot_sell_price) ?>원</td>
                        </tr>
                        <tr>
                            <th>쿠폰할인</th>
                            <td id="confirm-coupon">0원</td>
                        </tr>
                        <tr>
                            <th>배송비</th>
                            <!-- <td id="confirm-sc-price"><?= number_format($send_cost) ?>원</td> -->
                            <td id="confirm-sc-price">무료</td>
                        </tr>
                        <tr>
                            <th>포인트 사용</th>
                            <td id="confirm-point">0원</td>
                        </tr>
                        <tr>
                            <th style="font-size: 22px; font-weight: 500; color: #000000; border-top: 1px solid #333333;">총 결제 금액</th>
                            <td style="font-size: 26px; font-weight: 500; color: #000000; border-top: 1px solid #333333;" id="confirm-total">0원</td>
                        </tr>
                        <tr>
                            <th style="font-size: 14px; font-weight: 500; color: #f93f00; padding-bottom: unset; vertical-align: bottom;">
                                <!-- <? if ($item['it_point_type'] == '2') { ?>
                                    적립예정포인트<span class="icon-tooltip" data-container="body" data-toggle="popover" data-placement="right" data-content="결제예정 금액의 <?= $item['it_point'] ?>% 적립"></span></th>
                                <?} else if ($item['it_point_type'] == '0') {?>
                                    적립예정포인트<span class="icon-tooltip" data-container="body" data-toggle="popover" data-placement="right" data-content="<?= $item['it_point'] ?>포인트 적립"></span></th>
                                <?} else if ($item['it_point_type'] == '9') {?>
                                    -
                                <?} else {?>
                                    적립예정포인트<span class="icon-tooltip" data-container="body" data-toggle="popover" data-placement="right" data-content="결제예정 금액의 <?= $default['de_point_percent'] ?>% 적립"></span></th>
                                <?}?> -->
                                적립예정포인트<span class="icon-tooltip" data-container="body" data-toggle="popover" data-placement="right" data-content="결제예정 금액의 <?= $default['de_point_percent'] ?>% 적립 (예외상품있음)"></span></th>
                            <td style="font-size: 16px; font-weight: 500; color: #f93f00; padding-bottom: unset; vertical-align: bottom;" id="confirm-refund"><?= number_format($tot_point) ?>P</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px; font-weight: 500; color: #f54600; height: unset;" colspan=2>(구매확정 후 즉시 지급)</td>
                        </tr>
                        <tr>
                            <th colspan=2>
                                <div class="custom-checkbox check" style="margin-left: 3px; font-size: 12px; font-weight: normal;">
                                    <input type="checkbox" class="custom-control-input order_confirm" name="order_confirm" value="1" id="order_confirm">
                                    <label class="custom-control-label" for="order_confirm" style="font-size:12px; color: #424242;">
                                        (필수) 전자상거래법 제 8조 2항에 근거하여 주문할 상품의 상품명, 가격, 배송정보 등 판매조건을 확인하였으며, 구매진행에 동의합니다.
                                    </label>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th colspan=2>
                                <div style="display: flex; justify-content: space-between;">
                                    <div class="custom-checkbox check" style="margin-left: 3px; font-size: 12px; font-weight: normal;">
                                        <input type="checkbox" class="custom-control-input chk_user_privacy" name="chk_user_privacy" value="1" id="chk_user_privacy">
                                        <label class="custom-control-label" for="chk_user_privacy" style="font-size:12px; color: #424242;">(필수) 개인정보 수집/이용 동의</label>
                                    </div>
                                    <span style="cursor: pointer; font-size: 12px;" id="btn_user_privacy" onclick="modal_privacy('modal_privacy_on-big')">상세보기 ></span>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <td colspan=2>
                                <button type="button" class="btn btn-cart-action" style="width: 100%; margin-top: 24px; font-size: 18px;" onclick=forderform_check(this.form)>주문하기</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- 모바일 -->

            <div class="on-small">
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
                <div class="order-info-separator"></div>
                <div class="order-info-title">주문상품(<?= count($order_items) ?>)</div>
                <div id="order-product-info-mobile">
                    <table>
                        <?php foreach ($order_items as $oi => $item) : ?>
                            <tr>
                                <th style="width: 89px;">
                                    <?= $item['view']['image'] ?>
                                </th>
                                <td style="text-align: left; vertical-align: top;">
                                    <div class="swiper_item_detail">
                                        <div class="swiper_item_brand" style="font-size: 10px;"><?= $item['it_brand'] ?> <span class ='hocName<?= $item['io_hoching'] ?>'></span></div>
                                        <div class="swiper_item_name" style="font-size: 12px;  width:250px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:inline-block;"><?= $item['it_name'] ?></div>
                                        <div class="swiper_item_price_area">
                                            <div style="font-size: 12px;"><?= number_format($item['ct_price']) ?>원</div>
                                            <?
                                            $it_discount = $item['before_price'] - $item['ct_price'];
                                            $it_discount_ratio = ($it_discount / $item['before_price']) * 100;
                                            ?>
                                            <?php if ($it_discount_ratio > 0) : ?>
                                                <div>
                                                    <span class="price-del"><del><?= number_format($item['before_price']) ?></del>원</span>
                                                    <span class="price-dis" style="color: #e65026; font-size: 10px;"><?= number_format($it_discount_ratio) ?>%</span>
                                                </div>
                                            <? endif ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>주문수량</th>
                                <td style="text-align: right; font-size: 14px; font-weight: 500; color: #333333; line-height: 40px;"><?= number_format($item['ct_qty']) ?>개</td>
                            </tr>
                            <tr>
                                <th style="vertical-align: top;">주문금액</th>
                                <td style="vertical-align: top; padding-bottom: 16px; text-align: right; font-size: 14px; font-weight: 500; color: #333333;"><?= number_format($item['view']['sum']['price']) ?>원</td>
                            </tr>
                            <tr class="row-separator">
                                <td colspan="2"></td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                </div>
                <div class="order-info-separator"></div>
                <div id="order-delivery-info-mobile">
                    <div class="order-info-title" style="display: flex; justify-content: space-between;">
                        <span>배송정보</span>
                        <span>
                            <button type="button" class="btn btn-cart-action white small" onclick=openDeliveryForm()>새로입력</button>
                            <button type="button" class="btn btn-cart-action white small" onclick=openDeliveryModal(true)>배송지목록</button>
                        </span>
                    </div>
                    <div id="order-delivery-info-preview" class="<?= !empty($od_b_addr1) ? "active" : "" ?>">
                        <div id="delivery-preview-name"><?= $od_b_name ?></div>
                        <div id="delivery-preview-hp"><?= $od_b_hp ?></div>
                        <div id="delivery-preview-address">(<?= $od_b_zip ?>)<?= $od_b_addr1 . ' ' . $od_b_addr2 ?></div>
                    </div>
                    <div id="form-order-delivery-info">
                        <table id="table-order-delivery-info-mobile">
                            <tr>
                                <th>수령인<span class="point-require">*</span></th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="od_b_name_mobile" data-target="od_b_name" value="<?= $od_b_name ?>" placeholder="수령인 입력" style="width: 100%;">
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
                                    <input type="text" name="od_b_zip_mobile" data-target="od_b_zip" value="<?= $od_b_zip ?>" placeholder="우편번호" onclick="win_zip('forderform','od_b_zip_mobile', 'od_b_addr1_mobile', 'od_b_addr2_mobile', 'od_b_addr3','od_b_addr_jibeon');" readonly aria-describedby="btn-mb-zip" value="<?= $od_b_zip ?>" style="width: calc(100% - 90px);">
                                    <button class="btn btn-cart-action btn-black-2" type="button" style="margin-top: 0; vertical-align: top; margin-left: 10px; width: 80px !important;" onclick="win_zip('forderform','od_b_zip_mobile' , 'od_b_addr1_mobile', 'od_b_addr2_mobile', 'od_b_addr3','od_b_addr_jibeon');">우편번호</button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="od_b_addr1_mobile" data-target="od_b_addr1" id="od_b_addr1_mobile" style="width: 100%; margin-top: 10px;" value=" <?= $od_b_addr1 ?>" placeholder="기본주소" onclick="win_zip('forderform','od_b_zip_mobile' , 'od_b_addr1_mobile', 'od_b_addr2_mobile', 'od_b_addr3','od_b_addr_jibeon');" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="od_b_addr2_mobile" data-target="od_b_addr2" id="od_b_addr2_mobile" style="width: 100%; margin-top: 10px;" value=" <?= $od_b_addr2 ?>" placeholder="상세주소">
                                </td>
                            </tr>
                        </table>
                        <div class="custom-checkbox" style="padding-left: 22px; padding-top: 10px;">
                            <input type="checkbox" id="check-append-address-moblie" class="custom-control-input" name="ad_append_mobile" data-target="od_append">
                            <label class="custom-control-label" for="check-append-address-moblie" style="line-height: normal; font-size: 12px; color: #7f7f7f;">배송지목록에 추가</label>
                        </div>
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
                <div class="order-info-separator"></div>
                <div id="order-discount-info-mobile">
                    <div class="order-info-title">추가할인</div>
                    <table>
                        <tr>
                            <th>쿠폰할인</th>
                        </tr>
                        <tr>
                            <td style="display: flex; justify-content: space-between; padding: 0 14px;">
                                <span style="font-size: 16px; font-weight: 500; line-height: 44px; width: calc(100vw - 108px);"><input type="text" style="width: calc(100% - 35px);" id="order-discount-coupon-mobile" value="0" readonly>&nbsp;원</span><button type="button" class="btn btn-cart-action btn-order-open-coupon-mobile" style="width: 80px !important;">쿠폰조회</button>
                            </td>
                        </tr>
                        <tr>
                            <th style="padding-top: 14px;">포인트</th>
                        </tr>
                        <tr>
                            <td style="display: flex; justify-content: space-between; padding: 0 14px;">
                                <span style="font-size: 16px; font-weight: 500; line-height: 44px; width: calc(100vw - 108px);"><input type="text" style="width: calc(100% - 35px);" id="order-discount-point-mobile" class="order-point-use" value="0">&nbsp;원</span><button type="button" class="btn btn-cart-action btn-point-use" style="width: 80px !important;">최대적용</button>
                            </td>
                        </tr>
                        <tr>
                            <td style="line-height: normal; font-size: 10px; padding-left: 14px;">보유 포인트 <?= number_format($member['mb_point']) ?>P</td>
                        </tr>
                        <tr>
                            <td style="padding: 20px 14px;">
                                <div style="font-size: 10px; line-height: 1.8; color: #3a3a3a;">
                                    <span style="display: inline-block; width: 3px; height: 3px; border-radius: 3px; border: 1px solid #acacac; margin-right: 4px; vertical-align: middle;"></span>
                                    쿠폰을 먼저 선택한 후 포인트를 사용해주세요.
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="order-info-separator"></div>
                <div id="order-calculate-info-mobile">
                    <div class="order-info-title" style="line-height: unset; padding-bottom: 16px;">결제금액</div>
                    <table>
                        <tr>
                            <th>총 상품 금액</th>
                            <td id="confirm-od-price-mobile"><?= number_format($tot_before_price) ?>원</td>
                        </tr>
                        <tr>
                            <th>즉시할인</th>
                            <td id="confirm-discount-mobile"><?= number_format($tot_before_price - $tot_sell_price) ?>원</td>
                        </tr>
                        <tr>
                            <th>쿠폰할인</th>
                            <td id="confirm-coupon-mobile">0원</td>
                        </tr>
                        <tr>
                            <th>배송비</th>
                            <!-- <td id="confirm-sc-price-mobile"><?= number_format($send_cost) ?>원</td> -->
                            <td id="confirm-sc-price-mobile">무료</td>
                        </tr>
                        <tr>
                            <th>포인트 사용</th>
                            <td id="confirm-point-mobile">0원</td>
                        </tr>
                        <tr>
                            <th style="font-size: 16px; font-weight: 500; color: #000000; border-top: 1px solid #e0e0e0;">총 결제 금액</th>
                            <td style="font-size: 18px; font-weight: 500; color: #000000; border-top: 1px solid #e0e0e0;" id="confirm-total-mobile">0원</td>
                        </tr>
                        <tr>
                            <th style="font-size: 12px; font-weight: 500; color: #f93f00; padding-bottom: unset; vertical-align: bottom;">
                                적립예정포인트<span class="icon-tooltip" data-container="body" data-toggle="popover" data-placement="right" data-content="결제예정 금액의 <?= $default['de_point_percent'] ?>% 적립 (예외상품있음)"></span></th>
                            <td style="font-size: 14px; font-weight: 500; color: #f93f00; padding-bottom: unset; vertical-align: bottom;" id="confirm-refund-mobile"><?= number_format($tot_point) ?>P</td>
                        </tr>
                    </table>

                    <div class="order-info-separator"></div>
                    <div class="order-info-title" style="display: flex; justify-content: space-between;">
                        <span>결제수단 : 신용카드</span>
                        <span id="btn-toggle-benefit-mobile" style="font-size: 12px; font-weight: normal; color: #333333; padding-top: 4px;"><span style="padding-bottom: 0px; border-bottom: 1px solid #333333;">무이자혜택 안내</span> ></span>
                    </div>
                    <div id="order-payment-info-mobile">
                        <div style="padding: 0 14px;">
                                                <!-- <span>신용카드</span> -->
                            <!-- <button type="button" class="btn btn-cart-action white btn-order-payment active" data-payment='od_settle_card' onclick="selectPayment(this)">신용카드</button> -->
                            <!-- <button type="button" class="btn btn-cart-action white btn-order-payment" data-payment='od_settle_iche' onclick="selectPayment(this)">실시간계좌이체</button> -->
                        </div>
                    </div>
                    <div class="order-info-separator"></div>
                    <div style="padding: 20px 14px 0 33px; padding-bottom: unset;">
                        <div class="custom-checkbox check" style="margin-left: 3px; font-size: 12px; font-weight: normal;">
                            <input type="checkbox" class="custom-control-input order-confirm-mobile" name="order-confirm-mobile" value="1" id="order-confirm-mobile">
                            <label class="custom-control-label" for="order-confirm-mobile" style="font-size:12px; color: #424242;">
                                (필수) 전자상거래법 제 8조 2항에 근거하여 주문할 상품의 상품명, 가격, 배송정보 등 판매조건을 확인하였으며, 구매진행에 동의합니다.
                            </label>
                        </div>
                    </div>
                    <div style="padding: 20px 14px 20px 33px;">
                        <div style="display: flex; justify-content: space-between;">
                            <div class="custom-checkbox check" style="margin-left: 3px; font-size: 12px; font-weight: normal;">
                                <input type="checkbox" class="custom-control-input chk-user-privacy-mobile" name="chk-user-privacy-mobile" value="1" id="chk-user-privacy-mobile">
                                <label class="custom-control-label" for="chk-user-privacy-mobile" style="font-size:12px; color: #424242;">(필수) 개인정보 수집/이용 동의</label>
                            </div>
                            <span style="cursor: pointer; font-size: 12px;" onclick="modal_privacy('modal_privacy_on-small')">상세보기 ></span>
                        </div>
                    </div>
                    <div class="order-info-separator"></div>
                    <div style="padding: 0 14px;">
                        <!-- <button type="button" class="btn btn-cart-action" style="width: 100%; margin-top: 24px; font-size: 18px;" onclick=forderform_check(this.form)>주문하기</button> -->
                        <button type="button" class="btn btn-cart-action" style="width: 100%; margin-top: 24px; font-size: 18px;" onclick=checkOrderMobile(this.form)>주문하기</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    // 결제대행사별 코드 include (결제대행사 정보 필드)
    require_once(G5_SHOP_PATH . '/settle_' . $default['de_pg_service'] . '.inc.php');

    // 결제대행사별 코드 include (스크립트 등)
    require_once(G5_SHOP_PATH . '/' . $default['de_pg_service'] . '/orderform.1.php');
    require_once(G5_SHOP_PATH . '/' . $default['de_pg_service'] . '/orderform.2.php');

    if ($is_kakaopay_use) {
        require_once(G5_SHOP_PATH . '/kakaopay/orderform.1.php');
    }

    if ($is_kakaopay_use) {
        require_once(G5_SHOP_PATH . '/kakaopay/orderform.2.php');
    }
    ?>
</form>

<? include_once G5_LAYOUT_PATH . "/modal.order.php"; ?>

<script type="text/javascript">
    function clearDeliveryInfo() {
        const $tableDeliveryInfo = $("#table-order-delivery-info");

        $tableDeliveryInfo.find("input[type='text']").val("");
        $tableDeliveryInfo.find("input[type='number']").val("");
        $tableDeliveryInfo.find("input[type='checkbox']").prop("checked", false);
        $tableDeliveryInfo.find("select").each(function(si, se) {
            $(se).find("option").eq(0).prop("selected", true);
        });
        $(".select-order-memo-user").removeClass("active");

        $("#od_b_addr_hp").val("");
        $("#od_b_addr_addr3").val("");
        $("#od_b_addr_jibeon").val("");
    }

    function openDeliveryModal(mobile) {
        if (mobile) {
            $("#modal-order-address-mobile").modal("show");
        } else {
            $("#modal-order-address").modal("show");
        }
    }

    function openDeliveryForm() {
        $("#form-order-delivery-info").find("input").val("");
        $("#form-order-delivery-info").find("select").val("010");
        $("#order-delivery-info-preview").removeClass("active");
        $("#form-order-delivery-info").addClass("active");
    }

    function selectPayment(btn) {
        $(".btn-order-payment").removeClass("active");
        $(btn).addClass("active");
        $("#" + $(btn).data("payment")).click();
    }

    function modal_privacy(modal) {
        $("#" + modal).modal("show");
    }

    $("#form-order-delivery-info").find("input").on("change", function() {
        const f = document.forderform;
        const target = document.getElementsByName($(this).data("target"));
        target[0].value = $(this).val();
    });

    $(".select-order-memo").on("change", function() {
        if ($(this).find("option:selected").val() == "user") {
            $(".select-order-memo-user").addClass("active");
        } else {
            $(".select-order-memo-user").removeClass("active");
        }
    });

    $(".btn-order-open-coupon").on("click", function() {
        $("#modal-order-coupon").modal("show");
    });
    $(".btn-order-open-coupon-mobile").on("click", function() {
        updateCouponPreview(true);
        $("#modal-order-coupon-mobile").modal("show");
    });
</script>
<script>
    var zipcode = "";
    var form_action_url = "<?= $order_action_url; ?>";

    $(function() {
        var $cp_btn_el;
        var $cp_row_el;

        $(document).on("click", ".cp_apply", function() {
            var $el = $(this).closest("tr");
            var cp_id = $el.find("input[name='f_cp_id[]']").val();
            var price = $el.find("input[name='f_cp_prc[]']").val();
            var subj = $el.find("input[name='f_cp_subj[]']").val();
            var sell_price;

            if (parseInt(price) == 0) {
                if (!confirm(subj + "쿠폰의 할인 금액은 " + price + "원입니다.\n쿠폰을 적용하시겠습니까?")) {
                    return false;
                }
            }

            // 이미 사용한 쿠폰이 있는지
            var cp_dup = false;
            var cp_dup_idx;
            var $cp_dup_el;
            $("input[name^=cp_id]").each(function(index) {
                var id = $(this).val();

                if (id == cp_id) {
                    cp_dup_idx = index;
                    cp_dup = true;
                    $cp_dup_el = $(this).closest("tr");;

                    return false;
                }
            });

            if (cp_dup) {
                var it_name = $("input[name='it_name[" + cp_dup_idx + "]']").val();
                if (!confirm(subj + "쿠폰은 " + it_name + "에 사용되었습니다.\n" + it_name + "의 쿠폰을 취소한 후 적용하시겠습니까?")) {
                    return false;
                } else {
                    coupon_cancel($cp_dup_el);
                    $("#cp_frm").remove();
                    $cp_dup_el.find(".cp_btn").text("적용").focus();
                    $cp_dup_el.find(".cp_cancel").remove();
                }
            }

            var $s_el = $cp_row_el.find(".total_price");;
            sell_price = parseInt($cp_row_el.find("input[name^=it_price]").val());
            sell_price = sell_price - parseInt(price);
            if (sell_price < 0) {
                alert("쿠폰할인금액이 상품 주문금액보다 크므로 쿠폰을 적용할 수 없습니다.");
                return false;
            }
            $s_el.text(number_format(String(sell_price)));
            $cp_row_el.find("input[name^=cp_id]").val(cp_id);
            $cp_row_el.find("input[name^=cp_price]").val(price);

            calculate_total_price();
            $("#cp_frm").remove();
            $cp_btn_el.text("변경").focus();
            if (!$cp_row_el.find(".cp_cancel").size())
                $cp_btn_el.after("<button type=\"button\" class=\"cp_cancel\">취소</button>");
        });

        $(document).on("change", ".order-coupon-cart", function() {
            // console.log($(this).children("option:checked"));

            // return false;
            // var cp_id = $el.find("input[name='o_cp_id[]']").val();
            // var price = parseInt($el.find("input[name='o_cp_prc[]']").val());
            // var subj = $el.find("input[name='o_cp_subj[]']").val();
            let cp_id = $(this).children("option:checked").data('cp_id');
            let item_id = $(this).children("option:checked").data('it_id');
            let discount = $(this).children("option:checked").data('cp_dis');
            let subj = $(this).children("option:checked").data('cp_subj');
            let coupon_price = parseInt($(this).children("option:checked").data('cp_dc'));
            let item_coupon = parseInt($("input[name=item_coupon]").val());
            let od_price = parseInt($("input[name=org_od_price]").val()) - item_coupon;
            let send_cost = $("input[name=od_send_cost]").val();
            let price = 0;

            let coupons = JSON.parse($("input[name=od_cp_id]").val());

            coupons.push({
                id: cp_id,
                item: item_id,
                price: coupon_price
            });

            tempCPID = [];
            tempCPITEM = [];
            $.each(coupons, function(ci, cp) {
                if (tempCPID.indexOf(cp.id) >= 0) {
                    coupons.splice(ci, 1);
                } else if (tempCPITEM.indexOf(cp.item) >= 0) {
                    coupons.splice(ci, 1);
                } else {
                    tempCPID.push(cp.id);
                    tempCPITEM.push(cp.item);
                    price = price + coupon_price;
                }
            });

            if (!price) price = 0;

            // console.log(cp_id, price, discount, subj, send_cost, item_coupon, od_price);

            if (od_price - price <= 0) {
                alert("쿠폰할인금액이 주문금액보다 크므로 쿠폰을 적용할 수 없습니다.");
                return false;
            }

            $("#sc_coupon_btn").text("조회");
            $("#sc_coupon_cancel").remove();

            $("input[name=sc_cp_id]").val("");
            $("input[name=od_price]").val(od_price - price);
            $("input[name=od_cp_id]").val(JSON.stringify(coupons));
            $("input[name=od_coupon]").val(price);
            $("input[name=od_send_coupon]").val(0);

            $("#od_cp_price").text(number_format(String(price))); // 주문서 쿠폰 할인 텍스트
            $("#sc_cp_price").text(0); // 배송비 쿠폰 할인 텍스트

            calculate_order_price();
            // $("#od_coupon_btn").after("<button type=\"button\" id=\"od_coupon_cancel\" class=\"btn small gray round cp_cancel\">취소</button>");
        });

        $("#od_settle_bank").on("click", function() {
            $("[name=od_deposit_name]").val($("[name=od_name]").val());
            $("#settle_bank").show();
        });

        $("#od_settle_iche,#od_settle_card,#od_settle_vbank,#od_settle_hp,#od_settle_easy_pay,#od_settle_kakaopay").bind("click", function() {
            $("#settle_bank").hide();

            $("input[name='submitChecked']").val($("#od_tot_price").text() + "원 결제");
        });

        // 배송지선택
        $("input[name=ad_sel_addr]").on("click", function() {
            var addr = $(this).val().split(String.fromCharCode(30));

            if (addr[0] == "same") {
                gumae2baesong();
            } else {
                if (addr[0] == "new") {
                    for (i = 0; i < 10; i++) {
                        addr[i] = "";
                    }
                }

                var f = document.forderform;
                f.od_b_name.value = addr[0];
                f.od_b_tel.value = addr[1];
                f.od_b_hp.value = addr[2];
                f.od_b_zip.value = addr[3] + addr[4];
                f.od_b_addr1.value = addr[5];
                f.od_b_addr2.value = addr[6];
                f.od_b_addr3.value = addr[7];
                f.od_b_addr_jibeon.value = addr[8];
                f.ad_subject.value = addr[9];

                var zip1 = addr[3].replace(/[^0-9]/g, "");
                var zip2 = addr[4].replace(/[^0-9]/g, "");

                var code = String(zip1) + String(zip2);

                if (zipcode != code) {
                    //calculate_sendcost(code);
                }

                ad_subject_change();
            }
        });

        // 배송지목록
        $("#order_address, #order_address1").on("click", function() {
            var url = this.href;
            window.open(url, "win_address", "left=100,top=100,width=800,height=600,scrollbars=1");
            return false;
        });

        $(".btn-point-use").on("click", function() {
            const f = document.forderform;
            var od_price_max = parseInt(f.od_price.value);
            var temp_point_max = parseInt(f.od_temp_point.value);
            $("input[name=od_price]").val(od_price_max+temp_point_max);
            f.od_price.value = od_price_max+temp_point_max;
            calculate_temp_point();
            var max_point = parseInt(f.max_temp_point.value);
            if (max_point > temp_point_max+od_price_max -1000) { 
                max_point = temp_point_max+od_price_max -1000
            }
            // $(this).prev("input").val(number_format(String(max_point)));
            $("#order-discount-point").val(number_format(String(max_point)));
            $("#order-discount-point-mobile").val(number_format(String(max_point)));

            f.od_temp_point.value = max_point;

            calculate_order_price();
            payment_check(f);
        });
        $(".order-point-use").on("focusin", function() { 
            var f = document.forderform;
            var od_price_click = parseInt(f.od_price.value);
            var temp_point_click = parseInt(f.od_temp_point.value);
            var temp_point_rep = $(this).val();
            temp_point_rep = temp_point_rep.replace(/\,/g,'');
            $(this).val(parseInt(temp_point_rep));
            $("input[name=od_price]").val(od_price_click+temp_point_click);
        });
        $(".order-point-use").on("blur", function() {
            var f = document.forderform;
            var od_price = parseInt(f.od_price.value);
            var max_point = parseInt(f.max_temp_point.value);
            var point_unit = parseInt(<?= $default['de_settle_point_unit']; ?>);
            var temp_point = parseInt($(this).val());
            const target = $(this);
            
            var max_point_def = parseInt(<?= $default['de_settle_max_point']; ?>);
            if (max_point > max_point_def) max_point = max_point_def;
            
            if ($(this).val()) {
                if (temp_point < 0) {
                    alert("적립금를 0 이상 입력하세요.");
                    target.val(0);
                    return false;
                }
                if (temp_point > max_point) temp_point = max_point;
                if (temp_point > od_price-1000) { 
                    temp_point = od_price-1000;
                }


                // if (temp_point > od_price-100) {
                //     alert("상품 주문금액(배송비 제외) 보다 많이 적립금결제할 수 없습니다.1");
                //     temp_point = od_price-100;
                //     target.val(od_price-100);
                //     // return false;
                // }
                // if (temp_point > <?= (int) $member['mb_point']; ?>) {
                //     alert("회원님의 적립금보다 많이 결제할 수 없습니다.");
                //     target.val(0);
                //     return false;
                // }

                // if (temp_point > max_point) {
                //     alert(max_point + "원 이상 결제할 수 없습니다.");
                //     target.val(0);
                //     return false;
                // }

                if (parseInt(parseInt(temp_point / point_unit) * point_unit) != temp_point) {
                    alert("적립금를 " + String(point_unit) + "원 단위로 입력하세요.");
                    target.val(0);
                    return false;
                }
            }
            f.od_temp_point.value = temp_point;
            target.val(number_format(temp_point));

            payment_check(f);
            calculate_temp_point();
        });

    });

    function ad_subject_change() {
        $("#addr").text("[" + $("#od_b_zip").val() + "]" + $("#od_b_addr1").val() + " " + $("#od_b_addr2").val());
        $("#spn_od_b_name").text($("#od_b_name").val());
        $("#spn_od_b_tel").text($("#od_b_tel").val());
        $("#spn_od_b_hp").text($("#od_b_hp").val());
        $("#spn_ad_subject").text($("#od_b_name").val());
    }

    function coupon_cancel($el) {
        var $dup_sell_el = $el.find(".total_price");
        var $dup_price_el = $el.find("input[name^=cp_price]");
        var org_sell_price = $el.find("input[name^=it_price]").val();

        $dup_sell_el.text(number_format(String(org_sell_price)));
        $dup_price_el.val(0);
        $el.find("input[name^=cp_id]").val("");
    }

    function calculate_total_price() {
        var $it_prc = $("input[name^=it_price]");
        var $cp_prc = $("input[name^=cp_price]");
        var tot_sell_price = sell_price = tot_cp_price = 0;
        var it_price, cp_price, it_notax;
        var tot_mny = comm_tax_mny = comm_vat_mny = comm_free_mny = tax_mny = vat_mny = 0;
        var send_cost = parseInt($("input[name=od_send_cost]").val());

        $it_prc.each(function(index) {
            it_price = parseInt($(this).val());
            cp_price = parseInt($cp_prc.eq(index).val());
            sell_price += it_price;
            tot_cp_price += cp_price;
        });

        tot_sell_price = sell_price - tot_cp_price + send_cost;

        $("#ct_tot_coupon").text(number_format(String(tot_cp_price)));
        $("#ct_tot_price").text(number_format(String(tot_sell_price)));

        $("input[name=good_mny]").val(tot_sell_price);
        $("input[name=od_price]").val(sell_price - tot_cp_price);
        $("input[name=item_coupon]").val(tot_cp_price);
        $("input[name=od_coupon]").val(0);
        $("input[name=od_send_coupon]").val(0);
        $("input[name=od_temp_point]").val(0);
        <?php if ($temp_point > 0 && $is_member) { ?>
            calculate_temp_point();
        <?php } ?>
        calculate_order_price();
    }

    function calculate_order_price(withPoint) {
        const back_point = <?= $default['de_point_percent'] ?>;
        <?php if ($temp_point > 0 && $is_member) { ?>
            if (withPoint == true) calculate_temp_point();
        <?php } ?>

        const use_point = parseInt($("input[name=od_temp_point]").val());
        const sell_price = parseInt($("input[name=org_od_price]").val());
        const send_cost = parseInt($("input[name=od_send_cost]").val());
        const send_cost2 = parseInt($("input[name=od_send_cost2]").val());
        const send_coupon = parseInt($("input[name=od_send_coupon]").val());
        const coupon_price = parseInt($("input[name=od_coupon]").val()) + parseInt($("input[name=item_coupon]").val()) + parseInt($("input[name=od_send_coupon]").val());
        const tot_price = sell_price + send_cost + send_cost2 - use_point - coupon_price;
        //const tot_price = sell_price + send_cost + send_cost2 - send_coupon - use_point - coupon_price;
        $("input[name=good_mny]").val(tot_price);
        $("input[name=od_price]").val(tot_price);
        $("input[name='submitChecked']").val(number_format(String(tot_price)) + "원 결제");

        // 쿠폰할인 텍스트
        $("#confirm-coupon").text(number_format(String(coupon_price)) + "원");
        $("#confirm-coupon-mobile").text(number_format(String(coupon_price)) + "원");
        // 적립금 텍스트
        $("#confirm-point").text(number_format(String(use_point)) + "원");
        $("#confirm-point-mobile").text(number_format(String(use_point)) + "원");
        // 총 결제금액 변경
        $("#confirm-total").text(number_format(tot_price) + "원");
        $("#confirm-total-mobile").text(number_format(tot_price) + "원");
        let pointCheck = $("input[name=point_check]").val();
        if (pointCheck == 1) {
        } else {
            $("#confirm-refund").text(number_format(Math.round((tot_price / 100) * back_point)) + "P");
            $("#confirm-refund-mobile").text(number_format(Math.round((tot_price / 100) * back_point)) + "P");
        }
    }

    function calculate_temp_point() {
        var sell_price = parseInt($("input[name=od_price]").val());
        var mb_point = parseInt(<?= $member['mb_point']; ?>);
        var max_point = parseInt(<?= $default['de_settle_max_point']; ?>);
        var point_unit = parseInt(<?= $default['de_settle_point_unit']; ?>);
        var temp_point = max_point;

        if (temp_point > sell_price)
            temp_point = sell_price;

        if (temp_point > mb_point)
            temp_point = mb_point;

        temp_point = parseInt(temp_point / point_unit) * point_unit;

        $("input[name=max_temp_point]").val(temp_point);

        calculate_order_price(false);
    }

    function calculate_sendcost(code) {
        $.post(
            "./ordersendcost.php", {
                zipcode: code
            },
            function(data) {
                $("input[name=od_send_cost2]").val(data);
                $("#od_send_cost2").text(number_format(String(data)));

                zipcode = code;

                calculate_order_price();
            }
        );
    }

    function calculate_tax() {
        var $it_prc = $("input[name^=it_price]");
        var $cp_prc = $("input[name^=cp_price]");
        var sell_price = tot_cp_price = 0;
        var it_price, cp_price, it_notax;
        var tot_mny = comm_free_mny = tax_mny = vat_mny = 0;
        var send_cost = parseInt($("input[name=od_send_cost]").val());
        var send_cost2 = parseInt($("input[name=od_send_cost2]").val());
        var od_coupon = parseInt($("input[name=od_coupon]").val());
        var send_coupon = parseInt($("input[name=od_send_coupon]").val());
        var temp_point = 0;

        $it_prc.each(function(index) {
            it_price = parseInt($(this).val());
            cp_price = parseInt($cp_prc.eq(index).val());
            sell_price += it_price;
            tot_cp_price += cp_price;
            it_notax = $("input[name^=it_notax]").eq(index).val();
            if (it_notax == "1") {
                comm_free_mny += (it_price - cp_price);
            } else {
                tot_mny += (it_price - cp_price);
            }
        });

        if ($("input[name=od_temp_point]").size())
            temp_point = parseInt($("input[name=od_temp_point]").val());

        tot_mny += (send_cost + send_cost2 - od_coupon - send_coupon - temp_point);
        if (tot_mny < 0) {
            comm_free_mny = comm_free_mny + tot_mny;
            tot_mny = 0;
        }

        tax_mny = Math.round(tot_mny / 1.1);
        vat_mny = tot_mny - tax_mny;
        $("input[name=comm_tax_mny]").val(tax_mny);
        $("input[name=comm_vat_mny]").val(vat_mny);
        $("input[name=comm_free_mny]").val(comm_free_mny);
    }

    var temp_point = 0;

    function forderform_check(f) {

        // 재고체크
        var stock_msg = order_stock_check();
        if (stock_msg != "") {
            alert(stock_msg);
            return false;
        }

        errmsg = "";
        errfld = "";
        var deffld = "";

        check_field(f.od_name, "주문하시는 분 이름을 입력하십시오.");
        check_field(f.od_hp, "주문하시는 분 휴대전화 번호를 입력하십시오.");
        clear_field(f.od_email);
        if (f.od_email.value == '' || f.od_email.value.search(/(\S+)@(\S+)\.(\S+)/) == -1)
            error_field(f.od_email, "E-mail을 바르게 입력해 주십시오.");

        if (typeof(f.od_hope_date) != "undefined") {
            clear_field(f.od_hope_date);
            if (!f.od_hope_date.value)
                error_field(f.od_hope_date, "희망배송일을 선택하여 주십시오.");
        }

        check_field(f.od_b_name, "받으시는 분 이름을 입력하십시오.");
        check_field(f.od_b_hp, "받으시는 분 휴대전화 번호를 입력하십시오.");
        check_field(f.od_b_hp_2, "받으시는 분 휴대전화 번호를 입력하십시오.");
        check_field(f.od_b_addr1, "주소검색을 이용하여 받으시는 분 주소를 입력하십시오.");
        //check_field(f.od_b_addr2, "받으시는 분의 상세주소를 입력하십시오.");
        check_field(f.od_b_zip, "");
        var od_settle_bank = document.getElementById("od_settle_bank");
        if (od_settle_bank) {
            if (od_settle_bank.checked) {
                check_field(f.od_bank_account, "계좌번호를 선택하세요.");
                check_field(f.od_deposit_name, "입금자명을 입력하세요.");
            }
        }

        // 배송비를 받지 않거나 더 받는 경우 아래식에 + 또는 - 로 대입
        f.od_send_cost.value = parseInt(f.od_send_cost.value);

        if (errmsg) {
            alert(errmsg);
            errfld.focus();
            return false;
        }

        var settle_case = document.getElementsByName("od_settle_case");
        var settle_check = false;
        var settle_method = "";
        for (i = 0; i < settle_case.length; i++) {
            if (settle_case[i].checked) {
                settle_check = true;
                settle_method = settle_case[i].value;
                break;
            }
        }
        if (!settle_check) {
            alert("결제방식을 선택하십시오.");
            return false;
        }

        if ($(".order_confirm:checked").length <= 0) {
            alert("상품정보 확인 동의후 구매 가능합니다.");
            return false;
        }
        if ($(".chk_user_privacy:checked").length <= 0) {
            alert("개인정보 수집 • 이용에 동의하셔야 구매 가능합니다.");
            return false;
        }

        var od_price = parseInt(f.od_price.value);
        var send_cost = parseInt(f.od_send_cost.value);
        var send_cost2 = parseInt(f.od_send_cost2.value);
        var send_coupon = parseInt(f.od_send_coupon.value);
        var coupon_price = parseInt(f.item_coupon.value) + parseInt(f.od_coupon.value);

        var max_point = 0;
        if (typeof(f.max_temp_point) != "undefined")
            max_point = parseInt(f.max_temp_point.value);

        if (typeof(f.od_temp_point) != "undefined") {
            if (f.od_temp_point.value) {
                var point_unit = parseInt(<?= $default['de_settle_point_unit']; ?>);
                temp_point = parseInt(f.od_temp_point.value);

                if (temp_point < 0) {
                    alert("적립금를 0 이상 입력하세요.");
                    f.od_temp_point.select();
                    return false;
                }

                // if (temp_point > od_price) {
                //     alert("상품 주문금액(배송비 제외) 보다 많이 적립금결제할 수 없습니다.");
                //     f.od_temp_point.select();
                //     return false;
                // }

                if (temp_point > <?= (int) $member['mb_point']; ?>) {
                    alert("회원님의 적립금보다 많이 결제할 수 없습니다.");
                    f.od_temp_point.select();
                    return false;
                }

                if (temp_point > max_point) {
                    alert(max_point + "점 이상 결제할 수 없습니다.");
                    f.od_temp_point.select();
                    return false;
                }

                if (parseInt(parseInt(temp_point / point_unit) * point_unit) != temp_point) {
                    alert("적립금를 " + String(point_unit) + "점 단위로 입력하세요.");
                    f.od_temp_point.select();
                    return false;
                }

                // pg 결제 금액에서 적립금 금액 차감
                if (settle_method != "무통장") {
                    // f.good_mny.value = od_price + send_cost + send_cost2 - send_coupon - temp_point;
                }
            }
        }

        var tot_price = od_price;

        if (document.getElementById("od_settle_iche")) {
            if (document.getElementById("od_settle_iche").checked) {
                if (tot_price < 150) {
                    alert("계좌이체는 150원 이상 결제가 가능합니다.");
                    return false;
                }
            }
        }

        if (document.getElementById("od_settle_card")) {
            if (document.getElementById("od_settle_card").checked) {
                if (tot_price < 1000) {
                    alert("신용카드는 1000원 이상 결제가 가능합니다.");
                    return false;
                }
            }
        }

        if (document.getElementById("od_settle_hp")) {
            if (document.getElementById("od_settle_hp").checked) {
                if (tot_price < 350) {
                    alert("휴대전화은 350원 이상 결제가 가능합니다.");
                    return false;
                }
            }
        }

        <?php if ($default['de_tax_flag_use']) { ?>
            calculate_tax();
        <?php } ?>

        <?php if ($default['de_pg_service'] == 'inicis') { ?>
            if (f.action != form_action_url) {
                f.action = form_action_url;
                f.removeAttribute("target");
                f.removeAttribute("accept-charset");
            }
        <?php } ?>

        // 카카오페이 지불
        if (settle_method == "KAKAOPAY") {
            <?php if ($default['de_tax_flag_use']) { ?>
                f.SupplyAmt.value = parseInt(f.comm_tax_mny.value) + parseInt(f.comm_free_mny.value);
                f.GoodsVat.value = parseInt(f.comm_vat_mny.value);
            <?php } ?>
            getTxnId(f);
            return false;
        }

        var form_order_method = '';

        if (settle_method == "lpay") { //이니시스 L.pay 이면 ( 이니시스의 삼성페이는 모바일에서만 단독실행 가능함 )
            form_order_method = 'samsungpay';
        }

        if (jQuery(f).triggerHandler("form_sumbit_order_" + form_order_method) !== false) {

            // pay_method 설정
            <?php if ($default['de_pg_service'] == 'kcp') { ?>
                f.site_cd.value = f.def_site_cd.value;
                f.payco_direct.value = "";
                switch (settle_method) {
                    case "계좌이체":
                        f.pay_method.value = "010000000000";
                        break;
                    case "가상계좌":
                        f.pay_method.value = "001000000000";
                        break;
                    case "휴대전화":
                        f.pay_method.value = "000010000000";
                        break;
                    case "신용카드":
                        f.pay_method.value = "100000000000";
                        break;
                    case "간편결제":
                        <?php if ($default['de_card_test']) { ?>
                            f.site_cd.value = "S6729";
                        <?php } ?>
                        f.pay_method.value = "100000000000";
                        f.payco_direct.value = "Y";
                        break;
                    default:
                        f.pay_method.value = "무통장";
                        break;
                }
            <?php } else if ($default['de_pg_service'] == 'lg') { ?>
                f.LGD_EASYPAY_ONLY.value = "";
                if (typeof f.LGD_CUSTOM_USABLEPAY === "undefined") {
                    var input = document.createElement("input");
                    input.setAttribute("type", "hidden");
                    input.setAttribute("name", "LGD_CUSTOM_USABLEPAY");
                    input.setAttribute("value", "");
                    f.LGD_EASYPAY_ONLY.parentNode.insertBefore(input, f.LGD_EASYPAY_ONLY);
                }

                switch (settle_method) {
                    case "계좌이체":
                        f.LGD_CUSTOM_FIRSTPAY.value = "SC0030";
                        f.LGD_CUSTOM_USABLEPAY.value = "SC0030";
                        break;
                    case "가상계좌":
                        f.LGD_CUSTOM_FIRSTPAY.value = "SC0040";
                        f.LGD_CUSTOM_USABLEPAY.value = "SC0040";
                        break;
                    case "휴대전화":
                        f.LGD_CUSTOM_FIRSTPAY.value = "SC0060";
                        f.LGD_CUSTOM_USABLEPAY.value = "SC0060";
                        break;
                    case "신용카드":
                        f.LGD_CUSTOM_FIRSTPAY.value = "SC0010";
                        f.LGD_CUSTOM_USABLEPAY.value = "SC0010";
                        break;
                    case "간편결제":
                        var elm = f.LGD_CUSTOM_USABLEPAY;
                        if (elm.parentNode)
                            elm.parentNode.removeChild(elm);
                        f.LGD_EASYPAY_ONLY.value = "PAYNOW";
                        break;
                    default:
                        f.LGD_CUSTOM_FIRSTPAY.value = "무통장";
                        break;
                }
            <?php } else if ($default['de_pg_service'] == 'inicis') { ?>
                switch (settle_method) {
                    case "계좌이체":
                        f.gopaymethod.value = "DirectBank";
                        break;
                    case "가상계좌":
                        f.gopaymethod.value = "VBank";
                        break;
                    case "휴대전화":
                        f.gopaymethod.value = "HPP";
                        break;
                    case "신용카드":
                        f.gopaymethod.value = "Card";
                        f.acceptmethod.value = f.acceptmethod.value.replace(":useescrow", "");
                        break;
                    case "간편결제":
                        f.gopaymethod.value = "Kpay";
                        break;
                    case "lpay":
                        f.gopaymethod.value = "onlylpay";
                        f.acceptmethod.value = f.acceptmethod.value + ":cardonly";
                        break;
                    default:
                        f.gopaymethod.value = "무통장";
                        break;
                }
            <?php } ?>

            // 결제정보설정
            <?php if ($default['de_pg_service'] == 'kcp') { ?>
                f.buyr_name.value = f.od_name.value;
                f.buyr_mail.value = f.od_email.value;
                f.buyr_tel1.value = f.od_tel.value;
                f.buyr_tel2.value = f.od_hp.value;
                f.rcvr_name.value = f.od_b_name.value;
                f.rcvr_tel1.value = f.od_b_tel.value;
                f.rcvr_tel2.value = f.od_b_hp.value;
                f.rcvr_mail.value = f.od_email.value;
                f.rcvr_zipx.value = f.od_b_zip.value;
                f.rcvr_add1.value = f.od_b_addr1.value;
                f.rcvr_add2.value = f.od_b_addr2.value;

                if (f.pay_method.value != "무통장") {
                    jsf__pay(f);
                } else {
                    f.submit();
                }
            <?php } ?>
            <?php if ($default['de_pg_service'] == 'lg') { ?>
                f.LGD_BUYER.value = f.od_name.value;
                f.LGD_BUYEREMAIL.value = f.od_email.value;
                f.LGD_BUYERPHONE.value = f.od_hp.value;
                f.LGD_AMOUNT.value = f.good_mny.value;
                f.LGD_RECEIVER.value = f.od_b_name.value;
                f.LGD_RECEIVERPHONE.value = f.od_b_hp.value;
                <?php if ($default['de_escrow_use']) { ?>
                    f.LGD_ESCROW_ZIPCODE.value = f.od_b_zip.value;
                    f.LGD_ESCROW_ADDRESS1.value = f.od_b_addr1.value;
                    f.LGD_ESCROW_ADDRESS2.value = f.od_b_addr2.value;
                    f.LGD_ESCROW_BUYERPHONE.value = f.od_hp.value;
                <?php } ?>
                <?php if ($default['de_tax_flag_use']) { ?>
                    f.LGD_TAXFREEAMOUNT.value = f.comm_free_mny.value;
                <?php } ?>

                if (f.LGD_CUSTOM_FIRSTPAY.value != "무통장") {
                    launchCrossPlatform(f);
                } else {
                    f.submit();
                }
            <?php } ?>
            <?php if ($default['de_pg_service'] == 'inicis') { ?>
                f.price.value = f.good_mny.value;
                <?php if ($default['de_tax_flag_use']) { ?>
                    f.tax.value = f.comm_vat_mny.value;
                    f.taxfree.value = f.comm_free_mny.value;
                <?php } ?>
                f.buyername.value = f.od_name.value;
                f.buyeremail.value = f.od_email.value;
                f.buyertel.value = f.od_hp.value ? f.od_hp.value : f.od_tel.value;
                f.recvname.value = f.od_b_name.value;
                f.recvtel.value = f.od_b_hp.value ? f.od_b_hp.value : f.od_b_tel.value;
                f.recvpostnum.value = f.od_b_zip.value;
                f.recvaddr.value = f.od_b_addr1.value + " " + f.od_b_addr2.value;

                if (f.gopaymethod.value != "무통장") {
                    // 주문정보 임시저장
                    var order_data = $(f).serialize();
                    var save_result = "";
                    $.ajax({
                        type: "POST",
                        data: order_data,
                        url: g5_url + "/shop/ajax.orderdatasave.php",
                        cache: false,
                        async: false,
                        success: function(data) {
                            save_result = data;
                        }
                    });

                    if (save_result) {
                        alert(save_result);
                        return false;
                    }

                    if (!make_signature(f))
                        return false;

                    paybtn(f);
                } else {
                    f.submit();
                }
            <?php } ?>

        }

    }

    function checkOrderMobile(f) {
        const formOrder = document.forderform;

        formOrder.od_b_name.value = formOrder.od_b_name_mobile.value;
        formOrder.od_b_zip.value = formOrder.od_b_zip_mobile.value;
        formOrder.od_b_addr1.value = formOrder.od_b_addr1_mobile.value;
        formOrder.od_b_addr2.value = formOrder.od_b_addr2_mobile.value;
        formOrder.od_b_hp_1.value = formOrder.od_b_hp_1_mobile.value;
        formOrder.od_b_hp_2.value = formOrder.od_b_hp_2_mobile.value;
        formOrder.od_memo.value = formOrder.od_memo_mobile.value;
        formOrder.od_memo_user.value = formOrder.od_memo_user_mobile.value;
        // formOrder.ad_append.checked = formOrder.ad_append_mobile.checked;
        formOrder.ad_default.checked = formOrder.ad_default_mobile.checked;
        let broswerInfo = navigator.userAgent;
        if (broswerInfo.indexOf("APP_ANDROID")>-1) formOrder.od_pcmobile.value = 'android';
        else if (broswerInfo.indexOf("APP_IOS")>-1) formOrder.od_pcmobile.value = 'ios';
        else formOrder.od_pcmobile.value = 'mobile';

        $("#order_confirm").prop("checked", $("#order-confirm-mobile").prop("checked"));
        $("#chk_user_privacy").prop("checked", $("#chk-user-privacy-mobile").prop("checked"));
        return forderform_check(f);
    }

    //결제체크
    function payment_check(f) {
        var max_point = 0;
        var od_price = parseInt(f.od_price.value);
        var send_cost = parseInt(f.od_send_cost.value);
        var send_cost2 = parseInt(f.od_send_cost2.value);
        var send_coupon = parseInt(f.od_send_coupon.value);
        temp_point = 0;

        if (typeof(f.max_temp_point) != "undefined")
            var max_point = parseInt(f.max_temp_point.value);

        if (typeof(f.od_temp_point) != "undefined") {
            if (f.od_temp_point.value) {
                var point_unit = parseInt(<?= $default['de_settle_point_unit']; ?>);
                temp_point = parseInt(f.od_temp_point.value);

                if (temp_point < 0) {
                    alert("적립금를 0 이상 입력하세요.");
                    f.od_temp_point.select();
                    return false;
                }

                // if (temp_point > od_price) {
                //     alert("상품 주문금액(배송비 제외) 보다 많이 적립금결제할 수 없습니다.");
                //     f.od_temp_point.select();
                //     return false;
                // }

                if (temp_point > <?= (int) $member['mb_point']; ?>) {
                    alert("회원님의 적립금보다 많이 결제할 수 없습니다.");
                    f.od_temp_point.select();
                    return false;
                }

                if (temp_point > max_point) {
                    alert(max_point + "원 이상 결제할 수 없습니다.");
                    f.od_temp_point.select();
                    return false;
                }

                if (parseInt(parseInt(temp_point / point_unit) * point_unit) != temp_point) {
                    alert("적립금를 " + String(point_unit) + "원 단위로 입력하세요.");
                    f.od_temp_point.select();
                    return false;
                }

            }
        }

        var tot_price = od_price + send_cost + send_cost2 - send_coupon - temp_point;

        $("#od_coupon_cost").text(number_format(String(parseInt($("input[name=od_coupon]").val()))));
        $("#od_point_cost").text(number_format(String(temp_point)));

        $("#od_tot_price").text(number_format(String(tot_price)));
        $("input[name='submitChecked']").val(number_format(String(tot_price)) + "원 결제");


        if (document.getElementById("od_settle_iche")) {
            if (document.getElementById("od_settle_iche").checked) {
                if (tot_price < 150) {
                    alert("계좌이체는 150원 이상 결제가 가능합니다.");
                    return false;
                }
            }
        }

        if (document.getElementById("od_settle_card")) {
            if (document.getElementById("od_settle_card").checked) {
                if (tot_price < 1000) {
                    alert("신용카드는 1000원 이상 결제가 가능합니다.");
                    return false;
                }
            }
        }

        if (document.getElementById("od_settle_hp")) {
            if (document.getElementById("od_settle_hp").checked) {
                if (tot_price < 350) {
                    alert("휴대전화은 350원 이상 결제가 가능합니다.");
                    return false;
                }
            }
        }

        <?php if ($default['de_tax_flag_use']) { ?>
            calculate_tax();
        <?php } ?>

        return true;
    }

    // 구매자 정보와 동일합니다.
    function gumae2baesong() {
        var f = document.forderform;

        f.od_b_name.value = f.od_name.value;
        f.od_b_tel.value = f.od_tel.value;
        f.od_b_hp.value = f.od_hp.value;
        f.od_b_zip.value = f.od_zip.value;
        f.od_b_addr1.value = f.od_addr1.value;
        f.od_b_addr2.value = f.od_addr2.value;
        f.od_b_addr3.value = f.od_addr3.value;
        f.od_b_addr_jibeon.value = f.od_addr_jibeon.value;

        //calculate_sendcost(String(f.od_b_zip.value));

        ad_subject_change();
    }

    $(document).ready(function() {
        if ("IntersectionObserver" in window) {
            const targetElem = [].slice.call(document.querySelectorAll("div#order-product-info"));
            const navTopScroll = document.querySelector("html");
            const targetObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        navTopScroll.classList.remove("targetInVisible");
                    } else {
                        navTopScroll.classList.add("targetInVisible");
                    }
                });
            });

            targetElem.forEach(function(te) {
                targetObserver.observe(te);
            });

        }

        updateReceiptPosition();

        if ($("#order-delivery-info-preview").hasClass("active") == false) {
            openDeliveryForm();
        }
    });

    function updateReceiptPosition() {
        const $receiptElem = $("#order-receipt");
        const $offsetElem = $("#list-wrapper");
        const targetLeft = $offsetElem.offset().left + $offsetElem.width() - 500;

        $receiptElem.css("left", targetLeft);
    }

    $(window).on("resize", _.throttle(function(e) {
        updateReceiptPosition();
    }, 500));

    <?php if ($default['de_hope_date_use']) { ?> $(function() {
            $("#od_hope_date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                showButtonPanel: true,
                yearRange: "c-99:c+99",
                minDate: "+<?= (int) $default['de_hope_date_after']; ?>d;",
                maxDate: "+<?= (int) $default['de_hope_date_after'] + 6; ?>d;"
            });
        });
    <?php } ?>

    calculate_order_price();
</script>
<!-- Enliple Tracker Start -->
<script type="text/javascript">
    let broswerTrackerOS = navigator.userAgent;
    let deviceTrackerOS = "W";
    if (broswerTrackerOS.indexOf("Mobile")>-1) { 
        deviceTrackerOS = "M";
    }
    let dataCartString = `<? echo $dataCartString ?>`;
    let dataCartJson = JSON.parse(dataCartString);
    var ENP_VAR = { conversion: { product: [] } };

    let totalPrice = $("#confirm-total").text();
    totalPrice = totalPrice.replace(/,|원/g, ""); 
    let totalQty = 0;
    for (let i =0; i < dataCartJson.length ; i++) {
        totalQty += Number(dataCartJson[i].qty)
        ENP_VAR.conversion.product.push(dataCartJson[i]);
    }
    totalQty = String(totalQty);


	ENP_VAR.conversion.totalPrice = totalPrice;  // 없는 경우 단일 상품의 정보를 이용해 계산
	ENP_VAR.conversion.totalQty = totalQty;  // 없는 경우 단일 상품의 정보를 이용해 계산

	(function(a,g,e,n,t){a.enp=a.enp||function(){(a.enp.q=a.enp.q||[]).push(arguments)};n=g.createElement(e);n.async=!0;n.defer=!0;n.src="https://cdn.megadata.co.kr/dist/prod/enp_tracker_self_hosted.min.js";t=g.getElementsByTagName(e)[0];t.parentNode.insertBefore(n,t)})(window,document,"script");
	enp('create', 'conversion', 'litandard', { device: deviceTrackerOS, paySys: 'xpay' }); // W:웹, M: 모바일, B: 반응형
</script>
<!-- Enliple Tracker End -->

<?php
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>