<?
$addr_list = '';
$sep = chr(30);
$sql = " select * from {$g5['g5_shop_order_address_table']} where mb_id = '{$member['mb_id']}' order by ad_default desc limit 1 ";
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
</style>
<form name="forderform" id="forderform" method="post" action="<?= $order_action_url; ?>" autocomplete="off">
    <input type="hidden" name="od_b_hp" id="od_b_hp" value="<?= $od_b_hp ?>">
    <input type="hidden" name="od_b_addr3" id="od_b_addr3" value="<?= $od_b_addr3 ?>">
    <input type="hidden" name="od_b_addr_jibeon" id="od_b_addr_jibeon" value="<?= $od_b_addr_jibeon ?>">

    <input type="hidden" name="od_name" value="<?= get_text($member['mb_name']); ?>">
    <input type="hidden" name="od_price" value="<?= $tot_sell_price; ?>">
    <input type="hidden" name="org_od_price" value="<?= $tot_sell_price; ?>">
    <input type="hidden" name="org_before_price" value="<?= $tot_sell_price; ?>">
    <input type="hidden" name="od_send_cost" value="<?= $send_cost; ?>">
    <input type="hidden" name="od_send_cost2" value="0">
    <input type="hidden" name="item_coupon" value="0">
    <input type="hidden" name="od_coupon" value="0">
    <input type="hidden" name="od_send_coupon" value="0">
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

    <input type="radio" id="od_settle_card" name="od_settle_case" hidden value="신용카드">
    <input type="radio" id="od_settle_iche" name="od_settle_case" hidden value="계좌이체">

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

            <div id="order-product-info">
                <div class="order-info-title">주문상품(<?= count($order_items) ?>)</div>
                <table id="table-order-product-info">
                    <tr>
                        <th colspan=2>상품정보</th>
                        <th>수량</th>
                        <th>주문금액</th>
                        <th>배송비</th>
                    </tr>
                    <?php foreach ($order_items as $oi => $item) : ?>
                        <tr>
                            <td><?= $item['view']['image'] ?></td>
                            <td>
                                <div class="swiper_item_detail">
                                    <div class="swiper_item_brand"><?= $item['it_brand'] ?> <img src="/img/re/size_lable/<?= replace_hoching($item['io_hoching']) ?>.png" srcset="/img/re/size_lable/<?= replace_hoching($item['io_hoching']) ?>@2x.png 2x,/img/re/size_lable/<?= replace_hoching($item['io_hoching']) ?>@3x.png 3x"></div>
                                    <div class="swiper_item_name"><?= $item['it_name'] ?></div>
                                    <div class="swiper_item_price_area">
                                        <span><?= number_format($item['ct_price']) ?><span style="font-size: 12px;">원</span></span>
                                        <?
                                        $it_discount = $item['before_price'] - $item['ct_price'];
                                        $it_discount_ratio = ($it_discount / $item['before_price']) * 100;
                                        ?>
                                        <? if ($it_discount_ratio > 0) : ?>
                                        <span class="price-del"><del><?= number_format($item['before_price']) ?></del>원</span>
                                        <span class="price-dis" style="color: #e65026;"><?= number_format($it_discount_ratio) ?>%</span>
                                        <? endif ?>
                                    </div>
                                </div>
                            </td>
                            <td><?= number_format($item['ct_qty']) ?></td>
                            <td><?= number_format($item['view']['sum']['price']) ?></td>
                            <td><?= $item['view']['sendcost'] == 0 ? "무료" : number_format($item['view']['sendcost']) ?></td>
                        </tr>
                    <?php endforeach ?>
                </table>
            </div>

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
                        <td colspan=2>
                            주문자 정보 변경은 <span>마이페이지 > 회원정보수정</span> 에서 수정하실 수 있습니다.
                        </td>
                    </tr>
                </table>
            </div>
            <div id="order-delivery-info">
                <div class="order-info-title">배송정보
                    <button type="button" class="btn btn-cart-action btn-order-address-clear">새로입력</button>
                    <button type="button" class="btn btn-cart-action btn-order-address-list">배송지목록</button>
                </div>
                <table id="table-order-member-info">
                    <tr>
                        <th>수령인<span class="point-require">*</span></th>
                        <td>
                            <input type="text" name="od_b_name" value="<?= $od_b_name ?>" placeholder="수령인 입력">
                        </td>
                    </tr>
                    <tr>
                        <th>휴대폰번호<span class="point-require">*</span></th>
                        <td>
                            <select name="od_b_hp_1" id="od_b_hp_1">
                                <option value="010">010</option>
                                <option value="011">011</option>
                                <option value="016">016</option>
                                <option value="017">017</option>
                                <option value="018">018</option>
                                <option value="019">019</option>
                            </select>
                            <input type="number" name="od_b_hp_2" value="<?= $od_b_hp_2 ?>" placeholder="휴대폰번호 입력">
                        </td>
                    </tr>
                    <tr>
                        <th>배송지<span class="point-require">*</span></th>
                        <td>
                            <input type="text" name="od_b_zip" value="" placeholder="우편번호" onclick="win_zip('forderform','od_b_zip' , 'od_b_addr1', 'od_b_addr2', 'od_b_addr3','od_b_addr_jibeon');" readonly aria-describedby="btn-mb-zip" value="<?= $od_b_zip ?>">
                            <button class="btn btn-cart-action btn-black-2" type="button" style="margin-top: 0" onclick="win_zip('forderform','od_b_zip' , 'od_b_addr1', 'od_b_addr2', 'od_b_addr3','od_b_addr_jibeon');">우편번호 검색</button>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <input type="text" name="od_b_addr1" id="od_b_addr1" value="<?= $od_b_addr1 ?>" placeholder="기본주소">
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <input type="text" name="od_b_addr2" id="od_b_addr2" value="<?= $od_b_addr2 ?>" placeholder="상세주소">

                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <div class="custom-checkbox">
                                <input type="checkbox" id="check-append-default" class="custom-control-input" name="ad_default">
                                <label class="custom-control-label" for="check-default-address">기본배송지로 저장</label>
                            </div>
                            <div class="custom-checkbox">
                                <input type="checkbox" id="check-append-address" class="custom-control-input" name="ad_append">
                                <label class="custom-control-label" for="check-append-address">배송지목록에 추가</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>배송 메세지</th>
                        <td>
                            <select class="select-order-memo" name="od_memo" placeholder="배송 메세지를 선택해주세요">
                                <option>배송 메세지를 선택해주세요</option>
                                <option value="배송 전 전화 혹은 문자 남겨주세요">배송 전 전화 혹은 문자 남겨주세요</option>
                                <option value="부재 시 전화 혹은 문자 남겨주세요">부재 시 전화 혹은 문자 남겨주세요</option>
                                <option value="부재 시 경비실에 맡겨주세요">부재 시 경비실에 맡겨주세요</option>
                                <option value="부재 시 무인택배함에 넣어주세요">부재 시 무인택배함에 넣어주세요</option>
                                <option value="부재 시 문앞에 놔주세요">부재 시 문앞에 놔주세요</option>
                                <option value="user">직접입력</option>
                            </select>
                            <input type="text" name="od_memo_user" placeholder="50자 이내로 입력해주세요.">
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
                            <input type="text" id="order-discount-coupon" value="0">원 <button type="button" class="btn btn-cart-action btn-order-open-coupon">쿠폰조회</button>
                        </td>
                    </tr>
                    <tr>
                        <th>포인트</th>
                        <td>
                            <input type="text" id="order-discount-point" value="0">원 <button type="button" class="btn btn-cart-action btn-order-use-point">최대적용</button>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>보유 포인트 <?= number_format($member['mb_point']) ?>P</td>
                    </tr>
                    <tr>
                        <td colspan=2>
                            <div><span style="display: inline-block; width: 3px; height: 3px; border-radius: 3px; border: 1px solid #acacac; margin-right: 4px; vertical-align: middle;"></span>포인트는 결제예정금액이 10,000원 이상일 때, 최소 1,000P 보유 시 사용할 수 있습니다.</div>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="order-payment-info">
                <div class="order-info-title">
                    결제수단선택
                    <a href="">
                        <span>무이자혜택 안내</span>
                        <span></span>
                    </a>
                </div>
                <div>
                    <button type="button" class="btn-order-payment order-payment-card" data-payment='od_settle_card' onclick="selectPayment(this)">신용카드</button>
                    <button type="button" class="btn-order-payment order-payment-bank" data-payment='od_settle_iche' onclick="selectPayment(this)">실시간계좌이체</button>
                </div>
            </div>

            <div class="cart-order-info order-info-right on-big">
                <table>
                    <tr>
                        <th colspan=2>상품 정보</th>
                    </tr>
                    <?php foreach ($order_items as $oi => $item) : ?>
                        <tr>
                            <td>
                                <?= $item['view']['image']; ?>
                            </td>
                            <td style="padding-left: 16px;">
                                <div style="font-size: 16px;"><?= $item['brand']; ?></div>
                                <div style="font-size: 18px;"><?= $item['view']['it_name']; ?></div>
                                <div style="font-size: 12px;"><?= $item['view']['it_options']; ?></div>
                                <div style="color: #00bbb4;font-size: 16px;font-weight: 600;"><?= number_format($item['view']['sell_price']) ?>
                                    <span style="font-size: 12px; color: #00bbb4;">원 / 총<?= $item['view']['sum']['qty']; ?>개</span>
                                </div>
                                <input type="hidden" name="it_id[<?= $oi; ?>]" value="<?= $item['it_id']; ?>">
                                <input type="hidden" name="it_name[<?= $oi; ?>]" value="<?= get_text($item['it_name']); ?>">
                                <input type="hidden" name="it_price[<?= $oi; ?>]" value="<?= $item['view']['sell_price']; ?>">
                                <input type="hidden" name="cp_id[<?= $oi; ?>]" value="">
                                <input type="hidden" name="cp_price[<?= $oi; ?>]" value="0">
                            </td>
                        </tr>
                    <?php endforeach ?>
                    <tr>
                        <td style="border-top: 1px solid #000000; padding-top: 12px; height: 36px;">총 상품 금액</td>
                        <td id="confirm-od-price" style="font-size: 16px; font-weight: 600; text-align: right; border-top: 1px solid #000000; padding-top: 12px; padding-bottom: 12px;">
                            <?= number_format($tot_sell_price); ?>원
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 36px;">총 배송비</td>
                        <td id="confirm-sc-price" style="font-size: 16px; font-weight: 600; text-align: right; height: 36px;">
                            <?= $send_cost; ?>원
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 36px;">쿠폰할인</td>
                        <td id="confirm-coupon" style="font-size: 16px; font-weight: 600; text-align: right; height: 36px;">(-)0원</td>
                    </tr>
                    <tr>
                        <td style="height: 36px;">포인트 사용</td>
                        <td id="confirm-point" style="font-size: 16px; font-weight: 600; text-align: right; height: 36px;">(-)0원</td>
                    </tr>
                    <tr>
                        <th>총 결제 금액</th>
                        <th id="confirm-total" style="font-size: 20px; font-weight: bold; color: #00bbb4; text-align: right;">
                            <?= number_format($tot_price); ?> 원
                        </th>
                    </tr>
                    <tr>
                        <td colspan=2>
                            <div class="custom-checkbox check C1KOGRL" style="margin-left: 3px;">
                                <input type="checkbox" class="custom-control-input order_confirm" name="order_confirm" value="1" id="order_confirm">
                                <label class="custom-control-label" for="order_confirm" style="color: #000000;">전자상거래법 제8조 2항에 근거하여 주문할 상품의 상품명, 가격, 배송정보 등 판매조건을 확인하였으며, 구매진행에 동의합니다. (필수)</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2>
                            <div class="custom-checkbox check C1KOGRL" style="margin-left: 3px;">
                                <input type="checkbox" class="custom-control-input chk_user_privacy" name="chk_user_privacy" value="1" id="chk_user_privacy">
                                <label class="custom-control-label" for="chk_user_privacy" style="color: #000000;">개인정보 수집 • 이용 동의 (필수)</label>
                            </div>
                            <span class="on-big" style="margin-left: 24px; color: #2fc3bd; cursor: pointer;" id="btn_user_privacy" onclick="modal_privacy('modal_privacy_on-big')">상세보기 ></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2>
                            <button type="button" class="btn btn-big btn-mint" style="font-size: 18px;" onclick=forderform_check(this.form)>결제하기</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>



        <!-- NEW -->

        <div class="cart-list-wrapper">
            <div class="cart-order-info order-info-left">
                <table>
                    <colgroup class="on-big">
                        <col style="width: 140px">
                    </colgroup>
                    <colgroup class="on-small">
                        <col style="width: 80px">
                    </colgroup>
                    <tr class="on-big">
                        <th colspan=2>주문자 정보</th>
                    </tr>
                    <tr class="on-small">
                        <th colspan=2 style="border-top: unset; font-size :12px;">주문자 정보</th>
                    </tr>
                    <tr>
                        <td>주문하시는 분</td>
                        <td><?= get_text($member['mb_name']); ?> 님</td>
                    </tr>
                    <tr>
                        <td>이메일</td>
                        <td><?= $member['mb_email']; ?></td>
                    </tr>
                    <tr>
                        <td>연락처</td>
                        <td><?= get_text($member['mb_hp']); ?></td>
                    </tr>

                    <tr>
                        <th>배송 정보</th>
                        <th>
                            <a href="<?= G5_SHOP_URL ?>/orderaddress.php" id="order_address"><button type="button" class="btn btn-cart">기존 배송지</button></a>
                        </th>
                    </tr>
                    <tr>
                        <td>배송지명</td>
                        <td>
                            <input type="text" class="no-border-bottom" name="ad_subject" id="ad_subject" value="<?= get_text($row['ad_subject']) ?>" placeholder="배송지명">
                        </td>
                    </tr>
                    <tr>
                        <td>수령인</td>
                        <td>
                            <input type="text" class="no-border-bottom" name="od_b_name" id="od_b_name" value="<?= $od_b_name ?>" placeholder="수령인">
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; padding-top: 15px;">배송지</td>
                        <td>
                            <div class="input-group" id="order-addr-zip">
                                <input type="text" class="form-control form-input no-border-bottom" id="od_b_zip" name="od_b_zip" title="우편번호" onclick="win_zip('forderform','od_b_zip' , 'od_b_addr1', 'od_b_addr2', 'od_b_addr3','od_b_addr_jibeon');" readonly aria-describedby="btn-mb-zip" value="<?= $od_b_zip ?>">
                                <div class="input-group-append" id="btn-mb-zip">
                                    <button class="btn btn-outline-secondary btn-black-2" type="button" style="margin-top: 0" onclick="win_zip('forderform','od_b_zip' , 'od_b_addr1', 'od_b_addr2', 'od_b_addr3','od_b_addr_jibeon');">우편번호 검색</button>
                                </div>
                            </div>
                            <input type="text" class="no-border-bottom" name="od_b_addr1" id="od_b_addr1" value="<?= $od_b_addr1 ?>" placeholder="주소">
                            <input type="text" class="no-border-bottom" name="od_b_addr2" id="od_b_addr2" value="<?= $od_b_addr2 ?>" placeholder="상세주소">
                            <input type="hidden" name="od_b_addr3" id="od_b_addr3" value="<?= $od_b_addr3 ?>">
                            <input type="hidden" name="od_b_addr_jibeon" id="od_b_addr_jibeon" value="<?= $od_b_addr_jibeon ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>연락처1</td>
                        <td><input type="text" class="no-border-bottom" name="od_b_hp" id="od_b_hp" value="<?= $od_b_hp ?>" placeholder="연락처를 입력하세요"></td>
                    </tr>
                    <tr>
                        <td>연락처2</td>
                        <td><input type="text" name="od_b_tel" id="od_b_tel" value="<?= $od_b_tel ?>" placeholder="추가 연락처"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div class="custom-checkbox check C1KOGRL" style="margin-left: 3px;">
                                <input type="checkbox" class="custom-control-input" name="ad_default" value="1" id="ad_default">
                                <label class="custom-control-label" for="ad_default">기본배송지로 설정</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; padding-top: 15px;">배송요청사항</td>
                        <td>
                            <input type="text" placeholder="한글 20자 이내 입력" name="od_memo" id="od_memo" title="배송요청 내용" value="" maxlength="20">
                        </td>
                    </tr>
                    <tr class="on-big">
                        <td></td>
                        <td>
                            <div style="color: #7f7f7f; margin-top: 14px; margin-bottom: 24px;">
                                - 도서산간 지역은 추가 배송비가 발생할 수 있습니다.<br>
                                - 해외배송은 불가합니다.<br>
                                - 잘못된 배송정보 기입으로 인한 왕복 택배비는 구매자에게 부담될 수 있습니다.
                            </div>
                        </td>
                    </tr>
                    <tr class="on-small">
                        <td colspan=2>
                            <div style="color: #7f7f7f; margin-top: 14px; margin-bottom: 24px;">
                                - 도서산간 지역은 추가 배송비가 발생할 수 있습니다.<br>
                                - 해외배송은 불가합니다.<br>
                                - 잘못된 배송정보 기입으로 인한 왕복 택배비는 구매자에게 부담될 수 있습니다.
                            </div>
                        </td>
                    </tr>
                    <tr class="on-small">
                        <th colspan=2>
                            상품정보(총 <?= count($order_items) ?>개)
                        </th>
                    </tr>
                    <tr class="on-small">
                        <td colspan=2 style="padding-top: 8px;">
                            <?php foreach ($order_items as $oi => $item) : ?>
                                <div style="padding-bottom: 16px;">
                                    <div style="display: inline-block; width: 90px;"><?= $item['view']['image']; ?></div>
                                    <div style="display: inline-block; width: calc(100vw - 150px); padding-left: 14px; vertical-align: top;">
                                        <div style="font-size: 12px;"><?= $item['brand']; ?></div>
                                        <div style="font-size: 12px;"><?= $item['view']['it_name']; ?></div>
                                        <div style="font-size: 10px;"><?= $item['view']['it_options']; ?></div>
                                        <div style="color: #00bbb4;font-size: 16px;font-weight: 600;"><?= number_format($item['view']['sell_price']) ?>
                                            <span style="font-size: 12px; color: #00bbb4;">원 / 총<?= $item['view']['sum']['qty']; ?>개</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </td>
                    </tr>
                    <tr>
                        <th colspan=2>추가 할인</th>
                    </tr>
                    <? if (count($coupons[0]) > 0) : ?>
                    <tr class="on-big">
                        <td>상품 쿠폰</td>
                        <td style="line-height: 40px;">
                            <select id="order-item-coupon" class="order-coupon-cart">
                                <option>사용 가능 쿠폰 <?= count($coupons[0]) ?>장</option>
                                <? foreach ($coupons[0] as $cp) : ?>
                                <option value="<?= $cp['cp_id'] ?>" data-cp_id="<?= $cp['cp_id'] ?>" data-cp_dc="<?= $cp['dc'] ?>" data-cp_dis="<?= $cp['discount_price'] ?>" data-cp_subj="<?= $cp['cp_subject'] ?>" data-it_id="<?= $cp['item'] ?>"><?= $cp['cp_subject'] ?>(<?= number_format($cp['cp_minimum']) ?>원 이상 주문시) - <?= number_format($cp['dc']) ?>원 할인</option>
                                <? endforeach ?>
                            </select>
                        </td>
                    </tr>
                    <? endif ?>
                    <? if (count($coupons[1]) > 0) : ?>
                    <tr class="on-big">
                        <td>장바구니 쿠폰</td>
                        <td style="line-height: 40px;">
                            <select id="order-cart-coupon" class="order-coupon-cart">
                                <option>사용 가능 쿠폰 <?= count($coupons[1]) ?>장</option>
                                <? foreach ($coupons[1] as $cp) : ?>
                                <option value="<?= $cp['cp_id'] ?>" data-cp_id="<?= $cp['cp_id'] ?>" data-cp_dc="<?= $cp['dc'] ?>" data-cp_dis="<?= $cp['discount_price'] ?>" data-cp_subj="<?= $cp['cp_subject'] ?>" data-it_id="<?= $cp['item'] ?>"><?= $cp['cp_subject'] ?>(<?= number_format($cp['cp_minimum']) ?>원 이상 주문시) - <?= number_format($cp['dc']) ?>원 할인</option>
                                <? endforeach ?>
                            </select>
                        </td>
                    </tr>
                    <? endif ?>
                    <tr class="on-big">
                        <td>포인트</td>
                        <td style="font-size: 0; line-height: 40px;">
                            <input type="text" id="order-point-use" class="order-point-use" name="use_temp_point" value=0>
                            <button type="button" class="btn btn-point-use" id="btn-point-use">모두 사용</button>
                        </td>
                    </tr>
                    <tr class="on-big">
                        <td></td>
                        <td style="font-size: 0; line-height: 40px;">
                            <div style="font-size: 14px; color: #00bbb4;">보유 포인트 <?= number_format($member['mb_point']) ?>P</div>
                            <div style="font-size: 14px; color: #7f7f7f; line-height: normal; padding-bottom: 26px;">
                                <!-- - 장바구니 쿠폰과 브랜드 쿠폰은 중복 사용이 불가능합니다.<br> -->
                                - 쿠폰 유형에 따라 일부 상품의 쿠폰 적용이 제한될 수 있습니다.<br>
                                - 쿠폰 유형에 따라 쿠폰 적용 가능 금액 및 총 할인 금액이 제한될 수 있습니다.
                            </div>
                        </td>
                    </tr>
                    <tr class="on-small">
                        <td colspan=2>쿠폰</td>
                    </tr>
                    <? if (count($coupons[0]) > 0) : ?>
                    <tr class="on-small">
                        <td colspan=2 style="line-height: 40px;">
                            <select id="order-coupon-cart-mobile" class="order-coupon-cart">
                                <option>상품 쿠폰 <?= count($coupons[0]) ?>장</option>
                                <? foreach ($coupons[0] as $cp) : ?>
                                <option value="<?= $cp['cp_id'] ?>" data-cp_id="<?= $cp['cp_id'] ?>" data-cp_dc="<?= $cp['dc'] ?>" data-cp_dis="<?= $cp['discount_price'] ?>" data-cp_subj="<?= $cp['cp_subject'] ?>" data-it_id="<?= $cp['item'] ?>"><?= $cp['cp_subject'] ?>(<?= number_format($cp['cp_minimum']) ?>원 이상 주문시) - <?= number_format($cp['dc']) ?>원 할인</option>
                                <? endforeach ?>
                            </select>
                        </td>
                    </tr>
                    <? endif ?>
                    <? if (count($coupons[1]) > 0) : ?>
                    <tr class="on-small">
                        <td colspan=2 style="line-height: 40px;">
                            <select id="order-coupon-cart-mobile" class="order-coupon-cart">
                                <option>장바구니 쿠폰 <?= count($coupons[1]) ?>장</option>
                                <? foreach ($coupons[1] as $cp) : ?>
                                <option value="<?= $cp['cp_id'] ?>" data-cp_id="<?= $cp['cp_id'] ?>" data-cp_dc="<?= $cp['dc'] ?>" data-cp_dis="<?= $cp['discount_price'] ?>" data-cp_subj="<?= $cp['cp_subject'] ?>" data-it_id="<?= $cp['item'] ?>"><?= $cp['cp_subject'] ?>(<?= number_format($cp['cp_minimum']) ?>원 이상 주문시) - <?= number_format($cp['dc']) ?>원 할인</option>
                                <? endforeach ?>
                            </select>
                        </td>
                    </tr>
                    <? endif ?>
                    <tr class="on-small">
                        <td colspan=2>포인트</td>
                    </tr>
                    <tr class="on-small">
                        <td colspan=2 style="font-size: 0;">
                            <input type="text" id="order-point-use-mobile" class="order-point-use" value=0>
                            <button type="button" class="btn btn-point-use" id="btn-point-use-mobile">모두 사용</button>
                        </td>
                    </tr>
                    <tr class="on-small">
                        <td colspan=2 style="font-size: 0; line-height: 40px;">
                            <div style="font-size: 12px; color: #00bbb4;">보유 포인트 <?= number_format($member['mb_point']) ?>P</div>
                            <div style="font-size: 12px; color: #7f7f7f; line-height: normal; padding-bottom: 26px;">
                                <!-- - 장바구니 쿠폰과 브랜드 쿠폰은 중복 사용이 불가능합니다.<br> -->
                                - 쿠폰 유형에 따라 일부 상품의 쿠폰 적용이 제한될 수 있습니다.<br>
                                - 쿠폰 유형에 따라 쿠폰 적용 가능 금액 및 총 할인 금액이 제한될 수 있습니다.
                            </div>
                        </td>
                    </tr>
                    <tr class="on-small">
                        <th colspan=2>총 결제 금액(<?= count($order_items) ?>종) <span class="coupon-recommend">쿠폰을 적용하고 추가 할인을 받아보세요.</span></th>
                    </tr>
                    <tr class="on-small">
                        <td style="border-top: 1px solid #000000; padding-top: 12px; height: 28px;">총 상품 금액</td>
                        <td id="confirm-od-price" style="font-size: 12px; font-weight: 600; text-align: right; border-top: 1px solid #000000; padding-top: 12px; padding-bottom: 12px;">
                            <?= number_format($tot_sell_price); ?>원
                        </td>
                    </tr>
                    <tr class="on-small">
                        <td style="height: 28px;">총 배송비</td>
                        <td id="confirm-sc-price" style="font-size: 12px; font-weight: 600; text-align: right; height: 28px;">
                            <?= $send_cost; ?>원
                        </td>
                    </tr>
                    <tr class="on-small">
                        <td style="height: 28px;">쿠폰할인</td>
                        <td id="confirm-coupon-mobile" style="font-size: 12px; font-weight: 600; text-align: right; height: 28px;">(-)0원</td>
                    </tr>
                    <tr class="on-small">
                        <td style="height: 28px;">포인트 사용</td>
                        <td id="confirm-point-mobile" style="font-size: 12px; font-weight: 600; text-align: right; height: 28px;">(-)0원</td>
                    </tr>
                    <tr class="on-small">
                        <th colspan=2 style="font-size: 16px; background-color: #f8fafb; border-top: unset;">
                            총 결제 금액
                            <span id="confirm-total-mobile" style="font-size: 16px; font-weight: bold; color: #00bbb4; float: right; background-color: #f8fafb;">
                                <?= number_format($tot_price); ?> 원
                            </span>
                        </th>
                    </tr>
                    <tr class="on-small">
                        <th colspan=2 style="font-size: 12px;">결제수단 선택</th>
                    </tr>
                    <tr class="on-big">
                        <th colspan=2>결제수단 선택
                            <span style="float: right; text-decoration:underline; font-size: 14px; line-height: 1.71; letter-spacing: -0.5px; color: #000000;">무이자 할부 혜택 안내</span>
                        </th>
                    </tr>
                    <tr>
                        <td colspan=2 style="padding: 16px 0;">
                            <input type="radio" id="od_settle_card" name="od_settle_case" hidden value="신용카드">
                            <input type="radio" id="od_settle_iche" name="od_settle_case" hidden value="계좌이체">
                            <button type="button" class="btn btn-big btn-payment-selector" data-payment='od_settle_card' onclick="selectPayment(this)">신용/체크카드</button>
                            <button type="button" class="btn btn-big btn-payment-selector" data-payment='od_settle_iche' onclick="selectPayment(this)" style="float: right;">실시간계좌이체</button>
                        </td>
                    </tr>
                </table>
            </div>


        </div>
    </div>
    <div class="on-small" style="background-color: #f2f2f2; padding-top: 8px;">
        <div class="custom-checkbox check C1KOGRL" style="margin-left: 3px; padding: 8px 20px;">
            <input type="checkbox" class="custom-control-input order_confirm" name="order_confirm_mobile" value="1" id="order_confirm_mobile">
            <label class="custom-control-label" for="order_confirm_mobile" style="color: #000000;">전자상거래법 제8조 2항에 근거하여 주문할 상품의 상품명, 가격, 배송정보 등 판매조건을 확인하였으며, 구매진행에 동의합니다. (필수)</label>
        </div>
        <div class="custom-checkbox check C1KOGRL" style="margin-left: 3px; padding: 8px 20px;">
            <input type="checkbox" class="custom-control-input chk_user_privacy" name="chk_user_privacy_mobile" value="1" id="chk_user_privacy_mobile">
            <label class="custom-control-label" for="chk_user_privacy_mobile" style="color: #000000;">개인정보 수집 • 이용 동의 (필수)</label>
        </div>
        <span class="on-small" style="font-size: 12px; margin-left: 24px; color: #2fc3bd; cursor: pointer;" id="btn_user_privacy" onclick="modal_privacy('modal_privacy_on-small')">상세보기 ></span>
        <button type="button" class="btn btn-big btn-mint" style="font-size: 18px; margin-top: 16px;" onclick=forderform_check(this.form)>결제하기</button>
    </div>

    <input type="hidden" name="od_name" value="<?= get_text($member['mb_name']); ?>">
    <input type="hidden" name="od_price" value="<?= $tot_sell_price; ?>">
    <input type="hidden" name="org_od_price" value="<?= $tot_sell_price; ?>">
    <input type="hidden" name="org_before_price" value="<?= $tot_sell_price; ?>">
    <input type="hidden" name="od_send_cost" value="<?= $send_cost; ?>">
    <input type="hidden" name="od_send_cost2" value="0">
    <input type="hidden" name="item_coupon" value="0">
    <input type="hidden" name="od_coupon" value="0">
    <input type="hidden" name="od_send_coupon" value="0">
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
<script>
    var zipcode = "";
    var form_action_url = "<?= $order_action_url; ?>";

    function selectPayment(btn) {
        $(".btn-payment-selector").removeClass("active");
        $(btn).addClass("active");
        $("#" + $(btn).data("payment")).click();
    }

    function modal_privacy(modal) {
        $("#" + modal).modal("show");
    }

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

        /*

        $(".cp_btn").click(function() {
            $cp_btn_el = $(this);
            $cp_row_el = $(this).closest("tr");
            $("#cp_frm").remove();
            var it_id = $cp_btn_el.closest("tr").find("input[name^=it_id]").val();

            $.post(
                "./orderitemcoupon.php", {
                    it_id: it_id,
                    sw_direct: "<?= $sw_direct; ?>"
                },
                function(data) {
                    $cp_btn_el.after(data);
                }
            );
        });

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

        */
        $(document).on("click", "#cp_close", function() {
            $("#cp_frm").remove();
            $cp_btn_el.focus();
        });

        $(document).on("click", ".cp_cancel", function() {
            coupon_cancel($(this).closest("tr"));
            calculate_total_price();
            $("#cp_frm").remove();
            $(this).closest("tr").find(".cp_btn").text("적용").focus();
            $(this).remove();
        });

        $("#od_coupon_btn").click(function() {
            $("#od_coupon_frm").remove();
            var $this = $(this);
            var $forderform = $(this).closest("form");

            var price = parseInt($("input[name=org_od_price]").val()) - parseInt($("input[name=item_coupon]").val());
            var before_price = parseInt($("input[name=org_before_price]").val()) - parseInt($("input[name=item_coupon]").val());

            if (price <= 0) {
                alert('상품금액이 0원이므로 쿠폰을 사용할 수 없습니다.');
                return false;
            }
            $.post(
                "./ordercoupon.php", {
                    price: price,
                    before_price: before_price
                },
                function(data) {
                    //$this.after(data);
                    $("#container").before(data);
                    //$forderform.before(data);
                }
            );
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
        /*
                $(document).on("click", ".od_cp_apply", function() {

                    if ($("input[name='chk_cp']:checked").length == 0) {

                        $("#od_coupon_frm").remove();
                        $("#od_coupon_btn").focus();
                        return;
                    }

                    var $el = $("input[name='chk_cp']:checked").closest("li");

                    var cp_id = $el.find("input[name='o_cp_id[]']").val();
                    var price = parseInt($el.find("input[name='o_cp_prc[]']").val());
                    var subj = $el.find("input[name='o_cp_subj[]']").val();
                    var send_cost = $("input[name=od_send_cost]").val();
                    var item_coupon = parseInt($("input[name=item_coupon]").val());
                    var od_price = parseInt($("input[name=org_od_price]").val()) - item_coupon;

                    if (price == 0) {
                        if (!confirm(subj + "쿠폰의 할인 금액은 " + price + "원입니다.\n쿠폰을 적용하시겠습니까?")) {
                            return false;
                        }
                    }

                    if (od_price - price <= 0) {
                        alert("쿠폰할인금액이 주문금액보다 크므로 쿠폰을 적용할 수 없습니다.");
                        return false;
                    }

                    $("input[name=sc_cp_id]").val("");
                    $("#sc_coupon_btn").text("조회");
                    $("#sc_coupon_cancel").remove();

                    $("input[name=od_price]").val(od_price - price);
                    $("input[name=od_cp_id]").val(cp_id);
                    $("input[name=od_coupon]").val(price);
                    $("input[name=od_send_coupon]").val(0);
                    $("#od_cp_price").text(number_format(String(price)));
                    $("#sc_cp_price").text(0);
                    calculate_order_price();
                    $("#od_coupon_frm").remove();
                    $("#od_coupon_btn").text("변경").focus();
                    if (!$("#od_coupon_cancel").size())
                        $("#od_coupon_btn").after("<button type=\"button\" id=\"od_coupon_cancel\" class=\"btn small gray round cp_cancel\">취소</button>");
                });
        */
        $(document).on("click", "#od_coupon_close", function() {
            $("#od_coupon_frm").remove();
            $("#od_coupon_btn").focus();
        });

        $(document).on("click", "#od_coupon_cancel", function() {
            var org_price = $("input[name=org_od_price]").val();
            var item_coupon = parseInt($("input[name=item_coupon]").val());
            $("input[name=od_price]").val(org_price - item_coupon);
            $("input[name=sc_cp_id]").val("");
            $("input[name=od_coupon]").val(0);
            $("input[name=od_send_coupon]").val(0);
            $("#od_cp_price").text(0);
            $("#sc_cp_price").text(0);
            calculate_order_price();
            $("#od_coupon_frm").remove();
            $("#od_coupon_btn").text("조회").focus();
            $(this).remove();
            $("#sc_coupon_btn").text("조회");
            $("#sc_coupon_cancel").remove();
        });

        $("#sc_coupon_btn").click(function() {
            $("#sc_coupon_frm").remove();
            var $this = $(this);
            var price = parseInt($("input[name=od_price]").val());
            var send_cost = parseInt($("input[name=od_send_cost]").val());
            $.post(
                "./ordersendcostcoupon.php", {
                    price: price,
                    send_cost: send_cost
                },
                function(data) {
                    $this.after(data);
                }
            );
        });

        $(document).on("click", ".sc_cp_apply", function() {
            var $el = $(this).closest("tr");
            var cp_id = $el.find("input[name='s_cp_id[]']").val();
            var price = parseInt($el.find("input[name='s_cp_prc[]']").val());
            var subj = $el.find("input[name='s_cp_subj[]']").val();
            var send_cost = parseInt($("input[name=od_send_cost]").val());

            if (parseInt(price) == 0) {
                if (!confirm(subj + "쿠폰의 할인 금액은 " + price + "원입니다.\n쿠폰을 적용하시겠습니까?")) {
                    return false;
                }
            }

            $("input[name=sc_cp_id]").val(cp_id);
            $("input[name=od_send_coupon]").val(price);
            $("#sc_cp_price").text(number_format(String(price)));
            calculate_order_price();
            $("#sc_coupon_frm").remove();
            $("#sc_coupon_btn").text("변경").focus();
            if (!$("#sc_coupon_cancel").size())
                $("#sc_coupon_btn").after("<button type=\"button\" id=\"sc_coupon_cancel\" class=\"btn small gray round cp_cancel\">취소</button>");
        });

        $(document).on("click", "#sc_coupon_close", function() {
            $("#sc_coupon_frm").remove();
            $("#sc_coupon_btn").focus();
        });

        $(document).on("click", "#sc_coupon_cancel", function() {
            $("input[name=od_send_coupon]").val(0);
            $("#sc_cp_price").text(0);
            calculate_order_price();
            $("#sc_coupon_frm").remove();
            $("#sc_coupon_btn").text("쿠폰적용").focus();
            $(this).remove();
        });

        $("#od_b_addr2").focus(function() {
            var zip = $("#od_b_zip").val().replace(/[^0-9]/g, "");
            if (zip == "")
                return false;

            var code = String(zip);

            if (zipcode == code)
                return false;

            zipcode = code;
            //calculate_sendcost(code);
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
            const max_point = parseInt(f.max_temp_point.value);
            $(this).prev("input").val(number_format(String(max_point)));

            f.od_temp_point.value = max_point;

            payment_check(f);
            calculate_temp_point();
        });

        $(".order-point-use").on("blur", function() {
            var f = document.forderform;
            var od_price = parseInt(f.od_price.value);
            var max_point = parseInt(f.max_temp_point.value);
            const target = $(this);

            if ($(this).val()) {
                var point_unit = parseInt(<?= $default['de_settle_point_unit']; ?>);
                var temp_point = parseInt($(this).val());

                if (temp_point < 0) {
                    alert("적립금를 0 이상 입력하세요.");
                    target.val(0);
                    return false;
                }

                if (temp_point > od_price) {
                    alert("상품 주문금액(배송비 제외) 보다 많이 적립금결제할 수 없습니다.");
                    target.val(0);
                    return false;
                }

                if (temp_point > <?= (int) $member['mb_point']; ?>) {
                    alert("회원님의 적립금보다 많이 결제할 수 없습니다.");
                    target.val(0);
                    return false;
                }

                if (temp_point > max_point) {
                    alert(max_point + "원 이상 결제할 수 없습니다.");
                    target.val(0);
                    return false;
                }

                if (parseInt(parseInt(temp_point / point_unit) * point_unit) != temp_point) {
                    alert("적립금를 " + String(point_unit) + "원 단위로 입력하세요.");
                    target.val(0);
                    return false;
                }
            }
            f.od_temp_point.value = $(this).val();
            target.val(number_format(String($(this).val())));

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
        $("input[name=od_send_coupon]").val(0); <
        ?
        if ($oc_cnt > 0) {
            ? >
            $("input[name=od_cp_id]").val("");
            $("#od_cp_price").text(0);
            if ($("#od_coupon_cancel").size()) {
                $("#od_coupon_btn").text("쿠폰적용");
                $("#od_coupon_cancel").remove();
            } <
            ?
        } ? >
        <
        ?
        if ($sc_cnt > 0) {
            ? >
            $("input[name=sc_cp_id]").val("");
            $("#sc_cp_price").text(0);
            if ($("#sc_coupon_cancel").size()) {
                $("#sc_coupon_btn").text("쿠폰적용");
                $("#sc_coupon_cancel").remove();
            } <
            ?
        } ? >
        $("input[name=od_temp_point]").val(0); <
        ?
        if ($temp_point > 0 && $is_member) {
            ? >
            calculate_temp_point(); <
            ?
        } ? >
        calculate_order_price();
    }

    function calculate_order_price(withPoint) {
        var sell_price = parseInt($("input[name=od_price]").val());
        var send_cost = parseInt($("input[name=od_send_cost]").val());
        var send_cost2 = parseInt($("input[name=od_send_cost2]").val());
        var send_coupon = parseInt($("input[name=od_send_coupon]").val());
        var tot_price = sell_price + send_cost + send_cost2 - send_coupon - temp_point;

        $("input[name=good_mny]").val(tot_price);
        // $("#od_tot_price .print_price").text(number_format(String(tot_price)));
        <
        ?
        if ($temp_point > 0 && $is_member) {
            ? >
            if (withPoint == true) calculate_temp_point(); <
            ?
        } ? >

        // $("#od_coupon_cost").text(number_format(String(parseInt($("input[name=od_coupon]").val()))));
        // $("#od_point_cost").text(number_format(String(temp_point)));
        // $("#od_tot_price").text(number_format(String(tot_price)));
        $("input[name='submitChecked']").val(number_format(String(tot_price)) + "원 결제");

        const coupon_price = number_format(String(parseInt($("input[name=od_coupon]").val())));

        // 쿠폰할인 텍스트
        $("#confirm-coupon").text("(-)" + number_format(String(parseInt($("input[name=od_coupon]").val()))) + "원");
        $("#confirm-coupon-mobile").text("(-)" + number_format(String(parseInt($("input[name=od_coupon]").val()))) + "원");
        // 적립금 텍스트
        $("#confirm-point").text("(-)" + number_format(String(temp_point)) + "원");
        $("#confirm-point-mobile").text("(-)" + number_format(String(temp_point)) + "원");
        // 총 결제금액 변경
        $("#confirm-total").text(number_format(tot_price) + "원");
        $("#confirm-total-mobile").text(number_format(tot_price) + "원");
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

        // $("#use_max_point").text(number_format(String(temp_point)) + "원");
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
        // if (typeof(f.od_pwd) != 'undefined')
        // {
        //     clear_field(f.od_pwd);
        //     if( (f.od_pwd.value.length<3) || (f.od_pwd.value.search(/([^A-Za-z0-9]+)/)!=-1) )
        //         error_field(f.od_pwd, "회원이 아니신 경우 주문서 조회시 필요한 비밀번호를 3자리 이상 입력해 주십시오.");
        // }
        check_field(f.od_hp, "주문하시는 분 휴대전화 번호를 입력하십시오.");
        //check_field(f.od_tel, "주문하시는 분 전화번호를 입력하십시오.");
        //check_field(f.od_addr1, "주소검색을 이용하여 주문하시는 분 주소를 입력하십시오.");
        //check_field(f.od_addr2, " 주문하시는 분의 상세주소를 입력하십시오.");
        //check_field(f.od_zip, "");

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

                if (temp_point > od_price) {
                    alert("상품 주문금액(배송비 제외) 보다 많이 적립금결제할 수 없습니다.");
                    f.od_temp_point.select();
                    return false;
                }

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
                    f.good_mny.value = od_price + send_cost + send_cost2 - send_coupon - temp_point;
                }
            }
        }

        var tot_price = od_price + send_cost + send_cost2 - send_coupon - temp_point;

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

        <
        ?
        if ($default['de_tax_flag_use']) {
            ? >
            calculate_tax(); <
            ?
        } ? >

        <
        ?
        if ($default['de_pg_service'] == 'inicis') {
            ? >
            if (f.action != form_action_url) {
                f.action = form_action_url;
                f.removeAttribute("target");
                f.removeAttribute("accept-charset");
            } <
            ?
        } ? >

        // 카카오페이 지불
        if (settle_method == "KAKAOPAY") {
            <
            ?
            if ($default['de_tax_flag_use']) {
                ? >
                f.SupplyAmt.value = parseInt(f.comm_tax_mny.value) + parseInt(f.comm_free_mny.value);
                f.GoodsVat.value = parseInt(f.comm_vat_mny.value); <
                ?
            } ? >
            getTxnId(f);
            return false;
        }

        var form_order_method = '';

        if (settle_method == "lpay") { //이니시스 L.pay 이면 ( 이니시스의 삼성페이는 모바일에서만 단독실행 가능함 )
            form_order_method = 'samsungpay';
        }

        if (jQuery(f).triggerHandler("form_sumbit_order_" + form_order_method) !== false) {

            // pay_method 설정
            <
            ?
            if ($default['de_pg_service'] == 'kcp') {
                ? >
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
                        <
                        ?
                        if ($default['de_card_test']) {
                            ? >
                            f.site_cd.value = "S6729"; <
                            ?
                        } ? >
                        f.pay_method.value = "100000000000";
                        f.payco_direct.value = "Y";
                        break;
                    default:
                        f.pay_method.value = "무통장";
                        break;
                } <
                ?
            } else if ($default['de_pg_service'] == 'lg') {
                ? >
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
                } <
                ?
            } else if ($default['de_pg_service'] == 'inicis') {
                ? >
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
                } <
                ?
            } ? >

            // 결제정보설정
            <
            ?
            if ($default['de_pg_service'] == 'kcp') {
                ? >
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
                } <
                ?
            } ? >
            <
            ?
            if ($default['de_pg_service'] == 'lg') {
                ? >
                f.LGD_BUYER.value = f.od_name.value;
                f.LGD_BUYEREMAIL.value = f.od_email.value;
                f.LGD_BUYERPHONE.value = f.od_hp.value;
                f.LGD_AMOUNT.value = f.good_mny.value;
                f.LGD_RECEIVER.value = f.od_b_name.value;
                f.LGD_RECEIVERPHONE.value = f.od_b_hp.value; <
                ?
                if ($default['de_escrow_use']) {
                    ? >
                    f.LGD_ESCROW_ZIPCODE.value = f.od_b_zip.value;
                    f.LGD_ESCROW_ADDRESS1.value = f.od_b_addr1.value;
                    f.LGD_ESCROW_ADDRESS2.value = f.od_b_addr2.value;
                    f.LGD_ESCROW_BUYERPHONE.value = f.od_hp.value; <
                    ?
                } ? >
                <
                ?
                if ($default['de_tax_flag_use']) {
                    ? >
                    f.LGD_TAXFREEAMOUNT.value = f.comm_free_mny.value; <
                    ?
                } ? >

                if (f.LGD_CUSTOM_FIRSTPAY.value != "무통장") {
                    launchCrossPlatform(f);
                } else {
                    f.submit();
                } <
                ?
            } ? >
            <
            ?
            if ($default['de_pg_service'] == 'inicis') {
                ? >
                f.price.value = f.good_mny.value; <
                ?
                if ($default['de_tax_flag_use']) {
                    ? >
                    f.tax.value = f.comm_vat_mny.value;
                    f.taxfree.value = f.comm_free_mny.value; <
                    ?
                } ? >
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
                } <
                ?
            } ? >
        }

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

                if (temp_point > od_price) {
                    alert("상품 주문금액(배송비 제외) 보다 많이 적립금결제할 수 없습니다.");
                    f.od_temp_point.select();
                    return false;
                }

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

        <
        ?
        if ($default['de_tax_flag_use']) {
            ? >
            calculate_tax(); <
            ?
        } ? >

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

    <
    ?
    if ($default['de_hope_date_use']) {
        ? >
        $(function() {
            $("#od_hope_date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                showButtonPanel: true,
                yearRange: "c-99:c+99",
                minDate: "+<?= (int) $default['de_hope_date_after']; ?>d;",
                maxDate: "+<?= (int) $default['de_hope_date_after'] + 6; ?>d;"
            });
        }); <
        ?
    } ? >
</script>
<?php
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>