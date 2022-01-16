<style>
    #modal-order-coupon .modal-content,
    #modal-order-coupon-mobile .modal-content {
        width: 100%;
        height: 100%;
        margin-left: auto;
        padding: unset !important;
        box-shadow: unset;
        border-radius: 2px;
    }

    #modal-order-coupon-content {
        padding: 20px;
    }

    #modal-order-coupon-content select {
        width: 100%;
    }

    .selector-coupon-plus {
        margin-top: 20px;
    }

    .coupon-row td {
        font-size: 18px;
        padding: 20px 0;
        border-bottom: 1px solid #333333;
    }

    .order-coupon-content-title {
        font-size: 20px;
        font-weight: 600;
        color: #333333;
        margin-top: 50px;
        padding-bottom: 16px;
        border-bottom: 3px solid #333333;
    }

    .order-coupon-preview {
        border: 0px solid #333333;
        border-width: 1px 0 3px 0;
    }

    .order-coupon-preview-set,
    .order-coupon-preview-icon {
        display: inline-block;
        width: calc((100% - 145px) / 4);
        height: 140px;
        text-align: center;
        font-size: 0;
        padding-top: 40px;
    }

    .order-coupon-preview-label {
        font-size: 18px;
        font-weight: 500;
        color: #7c7c7b;
    }

    .order-coupon-preview-content {
        font-size: 24px;
        font-weight: 600;
        text-align: center;
        color: #333333;
    }

    .order-coupon-preview-icon {
        width: 35px;
        padding-top: 52px;
    }

    .order-coupon-content-desc {
        font-size: 12px;
        font-weight: normal;
        line-height: 1.5;
        color: #767676;
        margin: 16px 0 56px 0;
    }

    #modal-order-coupon-button {
        text-align: center;
        padding: 20px 0 80px 0;
    }

    #modal-order-coupon-button>button {
        width: 340px;
        height: 52px;
        font-size: 18px;
        font-weight: 500;
        border-radius: 2px;
        border: 1px solid #333333;
        color: #333333;
        background-color: #ffffff;
    }

    #order-address-list {
        width: 100%;
        min-height: 192px;
    }

    #order-address-list>tr>td {
        height: 64px;
        border-bottom: 1px solid #e0e0e0;
    }

    #order-address-list .custom-control.custom-radio {
        padding-left: unset;
    }

    #modal-order-address-button {
        padding: 20px 0;
        text-align: center;
    }

    #modal-order-address-button>button {
        width: 295px;
        margin-left: 8px;
    }

    #modal-order-address-button>button:first-child {
        margin-left: unset;
    }

    #modal-order-address-button.no-result>button.only-result {
        display: none;
    }

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
    }

    #modal-order-coupon-mobile>.modal-dialog {
        margin-top: 50px;
        padding-bottom: env(safe-area-inset-bottom);
    }

    #modal-order-address-form-mobile>.modal-dialog {
        position: fixed;
        bottom: calc(114px + env(safe-area-inset-bottom));
    }

    #modal-order-coupon-mobile>.modal-dialog>.modal-content,
    #modal-order-address-mobile>.modal-dialog>.modal-content,
    #modal-order-address-form-mobile>.modal-dialog>.modal-content {
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
        background-color: #ffffff;
        margin: unset;
        width: 100%;
        height: 100%;
        padding: unset !important;
        margin-left: unset;

    }

    #modal-order-address-mobile>.modal-dialog>.modal-content {
        margin-top: 200px;
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
        height: calc(85px + env(safe-area-inset-bottom));
        bottom: 50px;
        background-color: #ffffff;
        border-top: 1px solid #e0e0e0;
        font-size: 0;
        padding: 20px 14px;
        z-index: 1000;
    }

    #modal-order-address-content-mobile {
        padding-bottom: calc(154px + env(safe-area-inset-bottom));
    }

    #modal-order-address-form-content-mobile th,
    #modal-order-address-form-content-mobile td {
        padding: 0 14px;
    }

    #modal-order-address-form-content-mobile th {
        font-size: 12px;
        padding-top: 16px;
    }

    @media (max-width: 1366px) {
        #modal-order-coupon-content-mobile {
            padding-bottom: 50px;
        }

        #modal-order-coupon-content-mobile table,
        #modal-order-coupon-content-mobile select {
            width: 100%;
        }

        .order-coupon-content-title {
            font-size: 16px;
            font-weight: 500;
            color: #333333;
            padding: 0 14px;
            border: unset;
            margin-top: 20px;
        }

        .coupon-row-mobile td {
            font-size: 14px;
            padding: 20px 14px;
            border-bottom: 1px solid #e0e0e0;
        }

        .coupon-row-mobile tr>td:last-child {
            border-bottom: unset;
        }
    }
</style>

<div class="modal fade" id="modal-order-coupon" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="min-width: 940px; width: 940px;">
        <div class="modal-content">
            <div style="background-color: #ffffff; text-align: center; height: 73px; border-bottom: 1px solid #e0e0e0; font-size: 26px; font-weight: bold; color: #0d0d0d; line-height: 73px;">
                쿠폰조회
                <button type="button" data-dismiss="modal" aria-label="close" style="width: 24px; height: 24px; background: url(/img/re/cancle@3x.png) center center no-repeat; background-size: 18px; position: absolute; right: 17px; top: 25px; border: unset;"></button>
            </div>
            <div id="modal-order-coupon-content">
                <div class="custom-checkbox" style="padding-left: 16px;">
                    <input class="custom-control-input" type="checkbox" id="order-coupon-best">
                    <label class="custom-control-label" for="order-coupon-best" style="font-size: 18px; font-weight: 500; margin-left: 6px;">최대 혜택가 적용하기</label>
                </div>
                <div class="order-coupon-content-title" style="margin-top: 24px;">상품쿠폰</div>
                <table>
                    <? foreach ($order_items as $item) : ?>
                        <tr class="coupon-row" data-ct=<?= $item['ct_id'] ?> data-price=<?= $item['view']['sell_price'] ?>>
                            <td style="width: 340px;">
                                <div>[<?= $item['it_brand'] ?>]<?= $item['it_name'] ?></div>
                                <div style="font-weight: 500;"><?= $item['ct_qty'] ?>개 / <?= number_format($item['view']['sell_price']) ?>원</div>
                            </td>
                            <td style="width: 340px;">
                                <? if (!empty($item['view']['coupons'])) : ?>
                                    <? if (!empty($item['view']['coupons'][0]) || !empty($item['view']['coupons'][4])) : ?>
                                       
                                        <!-- <p><?= json_encode($item['view']['coupons'][0][0]) ?></p> -->
                                        <!-- <select class="selector-coupon-0" id ="scId_<?= $ci['cp_id'] ?>"> -->
                                        <select class="selector-coupon-0">
                                        <? if (!$item['view']['coupons'][0][0]['cp_promotion'] || $item['view']['coupons'][0][0]['cp_promotion'] == 0 ) : ?>
                                            <option>쿠폰 선택2</option>
                                        <? endif ?>
                                            <? foreach ($item['view']['coupons'][0] as $ci) : ?>
                                                <option value="<?= $ci['cp_id'] ?>" data-min=<?= $ci['cp_minimum'] ?> data-max=<?= $ci['cp_maximum'] ?> data-no=<?= $ci['cp_no'] ?> data-type=<?= $ci['cp_type'] ?> data-discount=<?= $ci['discount_price'] ?> data-price=<?= $ci['cp_price'] ?> data-trunc="<?= $ci['cp_trunc'] ?>" <? if ( $item['view']['sell_price'] < $ci['cp_minimum']) echo "disabled" ?>><?= $ci['cp_subject'] ?> - <?= number_format($ci['discount_price']) ?>원 할인</option>
                                            <? endforeach ?>
                                            <? foreach ($item['view']['coupons'][4] as $ci) : ?>
                                                <option value="<?= $ci['cp_id'] ?>" data-min=<?= $ci['cp_minimum'] ?> data-max=<?= $ci['cp_maximum'] ?> data-no=<?= $ci['cp_no'] ?> data-type=<?= $ci['cp_type'] ?> data-discount=<?= $ci['discount_price'] ?> data-price=<?= $ci['cp_price'] ?> data-trunc="<?= $ci['cp_trunc'] ?>" <? if ( $item['view']['sell_price'] < $ci['cp_minimum']) echo "disabled" ?>><?= $ci['cp_subject'] ?> - <?= number_format($ci['discount_price']) ?>원 할인</option>
                                            <? endforeach ?>
                                        </select>
                                        <? if (!empty($item['view']['coupons'][11])) : ?>
                                            <select class="selector-coupon-plus">
                                                <option>[플러스] 쿠폰 선택</option>
                                                <? foreach ($item['view']['coupons'][11] as $cip) : ?>
                                                    <option value="<?= $cip['cp_id'] ?>" data-plus=true data-min=<?= $cip['cp_minimum'] ?> data-max=<?= $cip['cp_maximum'] ?> data-no=<?= $cip['cp_no'] ?> data-type=<?= $cip['cp_type'] ?> data-discount=<?= $cip['discount_price'] ?> data-price=<?= $cip['cp_price'] ?> data-subject="<?= $cip['cp_subject'] ?>" data-minimum="<?= $cip['cp_minimum'] ?>" data-maximum="<?= $cip['cp_maximum'] ?>" data-trunc="<?= $cip['cp_trunc'] ?>"><?= $cip['cp_subject'] ?> - <?= number_format($cip['discount_price']) ?>원 할인</option>
                                                <? endforeach ?>
                                            </select>
                                        <? endif ?>
                                    <? else : ?>
                                        <select class="selector-coupon-0">
                                            <option>사용 가능한 쿠폰이 없습니다</option>
                                        </select>
                                    <? endif ?>
                                <? else : ?>
                                    <select>
                                        <option>사용 가능한 쿠폰이 없습니다</option>
                                    </select>
                                <? endif ?>
                            </td>
                            <td style="width: 220px; padding-left: 20px; font-size: 18px; text-align: center; color: #f93f00;"><span class="coupon-row-discount">0</span>원</td>
                        </tr>
                    <? endforeach ?>
                </table>
                <div class="order-coupon-content-title">장바구니쿠폰</div>
                <table>
                    <tr class="coupon-row" data-ct='cart'>
                        <td style="width: 340px; font-weight: 500;">
                            주문금액 : <span id="order-coupon-preview-item-total"></span>원
                        </td>
                        <td style="width: 340px;">
                            <select id="">
                                <? if (!empty($member_coupon[2])) : ?>
                                    <option>쿠폰 선택</option>
                                    <? foreach ($member_coupon[2] as $cp) : ?>
                                        <option value="<?= $cp['cp_id'] ?>" data-min=<?= $cp['cp_minimum'] ?> data-max=<?= $cp['cp_maximum'] ?> data-no=<?= $cp['cp_no'] ?> data-type=<?= $cp['cp_type'] ?> data-discount=<?= $cp['cp_price'] ?> data-subject="<?= $cp['cp_subject'] ?>" data-minimum=<?= $cp['cp_minimum'] ?> data-maximum=<?= $cp['cp_maximum'] ?> data-trunc=<?= $cp['cp_trunc'] ?>></option>
                                    <? endforeach ?>
                                <? else : ?>
                                    <option>사용 가능한 쿠폰이 없습니다</option>
                                <? endif ?>
                            </select>
                        </td>
                        <td style="width: 220px; padding-left: 20px; font-size: 18px; text-align: center; color: #f93f00;"><span class="coupon-row-discount">0</span>원</td>
                    </tr>
                </table>
                <div class="order-coupon-content-title">배송비쿠폰</div>
                <table>
                    <tr class="coupon-row" data-ct='send'>
                        <td style="width: 340px; font-weight: 500;">
                            배송비 : <?= number_format($it_send_cost) ?>원
                        </td>
                        <td style="width: 340px;">
                            <select>
                                <? if ($it_send_cost > 0 && !empty($member_coupon[3])) : ?>
                                    <option>쿠폰 선택</option>
                                    <? foreach ($member_coupon[3] as $cp) : ?>
                                        <? $coupon_price = $cp['cp_type'] == 1 ? ($default['de_send_cost_list'] / 100) * $cp['cp_price'] : $cp['cp_price'];
                                        if ($coupon_price > $cp['cp_maximum']) {
                                            $coupon_price = $cp['cp_maximum'];
                                        }
                                        ?>
                                        <option value="<?= $cp['cp_id'] ?>" data-min=<?= $cp['cp_minimum'] ?> data-max=<?= $cp['cp_maximum'] ?> data-no=<?= $cp['cp_no'] ?> data-type=<?= $cp['cp_type'] ?> data-discount=<?= $coupon_price ?> data-trunc="<?= $cp['cp_trunc'] ?>"><?= $cp['cp_subject'] ?> - <?= number_format($coupon_price) ?>원 할인</option>
                                    <? endforeach ?>
                                <? else : ?>
                                    <option>사용 가능한 쿠폰이 없습니다</option>
                                <? endif ?>
                            </select>
                        </td>
                        <td style="width: 220px; padding-left: 20px; font-size: 18px; text-align: center; color: #f93f00;"><span class="coupon-row-discount">0</span>원</td>
                    </tr>
                </table>

                <div class="order-coupon-content-desc">
                    <div><span style="display: inline-block; width: 3px; height: 3px; border-radius: 3px; border: 1px solid #acacac; margin-right: 4px; vertical-align: middle;"></span>쿠폰 유형에 따라 쿠폰 적용 가능 상품, 금액 및 총 할인 금액이 제한될 수 있습니다.</div>
                    <div><span style="display: inline-block; width: 3px; height: 3px; border-radius: 3px; border: 1px solid #acacac; margin-right: 4px; vertical-align: middle;"></span>쿠폰리스트에서는 보유 중인 쿠폰만 확인 할 수 있습니다.</div>
                    <div><span style="display: inline-block; width: 3px; height: 3px; border-radius: 3px; border: 1px solid #acacac; margin-right: 4px; vertical-align: middle;"></span>보유 중인 쿠폰 목록은 마이페이지 > 쿠폰조회에서 확인하시기 바랍니다.</div>
                </div>
                <div class="order-coupon-preview">
                    <span class="order-coupon-preview-set">
                        <div class="order-coupon-preview-label">상품할인</div>
                        <div class="order-coupon-preview-content" id="order-coupon-preview-item"></div>
                    </span>
                    <span class="order-coupon-preview-icon"><span class="icon-cart-calc calc-plus"></span></span>
                    <span class="order-coupon-preview-set">
                        <div class="order-coupon-preview-label">장바구니할인</div>
                        <div class="order-coupon-preview-content" id="order-coupon-preview-cart"></div>
                    </span>
                    <span class="order-coupon-preview-icon"><span class="icon-cart-calc calc-plus"></span></span>
                    <span class="order-coupon-preview-set">
                        <div class="order-coupon-preview-label">배송비할인</div>
                        <div class="order-coupon-preview-content" id="order-coupon-preview-send"></div>
                    </span>
                    <span class="order-coupon-preview-icon"><span class="icon-cart-calc calc-eq"></span></span>
                    <span class="order-coupon-preview-set">
                        <div class="order-coupon-preview-label">총 할인금액</div>
                        <div class="order-coupon-preview-content" id="order-coupon-preview-total"></div>
                    </span>
                </div>
            </div>
            <div id="modal-order-coupon-button">
                <button type="button" data-dismiss="modal">취소</button>
                <button type="button" id="order-coupon-apply" style="background-color: #333333; color: #ffffff; margin-left: 20px; font-weight: normal;">쿠폰적용</button>
            </div>
        </div>
    </div>
</div>

<!-- 쿠폰조회 모바일 -->
<div class="modal fade bottom" id="modal-order-coupon-mobile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div style="text-align: center; height: 50px; border-bottom: 1px solid #e0e0e0; font-size: 18px; font-weight: 500; color: #090909; line-height: 50px;">
                쿠폰조회
                <button type="button" data-dismiss="modal" aria-label="close" style="width: 18px; height: 18px; background: url(/img/re/cancle@3x.png) center center no-repeat; background-size: 18px; position: absolute; right: 12px; top: 15px; border: unset;"></button>
            </div>
            <div style="padding: 14px; border-bottom: 1px solid #e0e0e0;">
                <div class="custom-checkbox" style="padding-left: 22px;">
                    <input class="custom-control-input" type="checkbox" id="order-coupon-best-mobile" checked>
                    <label class="custom-control-label" for="order-coupon-best-mobile" style="font-size: 16px; font-weight: normal; color: #606060;">최대 혜택가 적용하기</label>
                </div>
            </div>
            <div id="modal-order-coupon-content-mobile">
                <div class="order-coupon-content-title">상품쿠폰</div>
                <table>
                    <? foreach ($order_items as $item) : ?>
                        <tr class="coupon-row-mobile" data-ct=<?= $item['ct_id'] ?> data-price=<?= $item['view']['sell_price'] ?>>
                            <td style="width: 100%;">
                                <div>[<?= $item['it_brand'] ?>]<?= $item['it_name'] ?></div>
                                <div style="font-weight: 500;"><?= $item['ct_qty'] ?>개 / <?= number_format($item['view']['sell_price']) ?>원<span class="coupon-row-mobile-discount"></span></div>
                                <? if (!empty($item['view']['coupons'])) : ?>
                                    <? if (!empty($item['view']['coupons'][0])) : ?>
                                        <select class="selector-coupon-0">
                                            <? if (!$item['view']['coupons'][0][0]['cp_promotion'] || $item['view']['coupons'][0][0]['cp_promotion'] == 0 ) : ?>
                                                <option>쿠폰 선택 모바일?</option>
                                            <? endif ?>
                                            <? foreach ($item['view']['coupons'][0] as $ci) : ?>
                                                <option value="<?= $ci['cp_id'] ?>" data-min=<?= $ci['cp_minimum'] ?> data-max=<?= $ci['cp_maximum'] ?> data-no=<?= $ci['cp_no'] ?> data-type=<?= $ci['cp_type'] ?> data-discount=<?= $ci['discount_price'] ?> data-price=<?= $ci['cp_price'] ?> data-trunc="<?= $ci['cp_trunc'] ?>" <? if ( $item['view']['sell_price'] < $ci['cp_minimum']) echo "disabled" ?>><?= $ci['cp_subject'] ?> - <?= number_format($ci['discount_price']) ?>원 할인</option>
                                            <? endforeach ?>
                                        </select>
                                        <? if (!empty($item['view']['coupons'][11])) : ?>
                                            <select class="selector-coupon-plus">
                                                <option>[플러스] 쿠폰 선택</option>
                                                <? foreach ($item['view']['coupons'][11] as $cip) : ?>
                                                    <option value="<?= $cip['cp_id'] ?>" data-plus=true data-min=<?= $cip['cp_minimum'] ?> data-max=<?= $cip['cp_maximum'] ?> data-no=<?= $cip['cp_no'] ?> data-type=<?= $cip['cp_type'] ?> data-discount=<?= $cip['discount_price'] ?> data-price=<?= $cip['cp_price'] ?> data-subject="<?= $cip['cp_subject'] ?>" data-minimum="<?= $cip['cp_minimum'] ?>" data-maximum="<?= $cip['cp_maximum'] ?>" data-trunc="<?= $cip['cp_trunc'] ?>"><?= $cip['cp_subject'] ?> - <?= number_format($cip['discount_price']) ?>원 할인</option>
                                                <? endforeach ?>
                                            </select>
                                        <? endif ?>
                                    <? else : ?>
                                        <select class="selector-coupon-0">
                                            <option>사용 가능한 쿠폰이 없습니다</option>
                                        </select>
                                    <? endif ?>
                                <? else : ?>
                                    <select>
                                        <option>사용 가능한 쿠폰이 없습니다</option>
                                    </select>
                                <? endif ?>
                            </td>
                        </tr>
                    <? endforeach ?>
                </table>
                <div class="order-info-separator"></div>
                <div class="order-coupon-content-title">장바구니쿠폰</div>
                <table>
                    <tr class="coupon-row-mobile" data-ct='cart'>
                        <td>
                            <div>
                                주문금액 : <span id="order-coupon-preview-item-total-mobile"></span>원<span class="coupon-row-mobile-discount"></span>
                            </div>
                            <select>
                                <? if (!empty($member_coupon[2])) : ?>
                                    <option>쿠폰 선택</option>
                                    <? foreach ($member_coupon[2] as $cp) : ?>
                                        <option value="<?= $cp['cp_id'] ?>" data-min=<?= $cp['cp_minimum'] ?> data-max=<?= $cp['cp_maximum'] ?> data-no=<?= $cp['cp_no'] ?> data-type=<?= $cp['cp_type'] ?> data-discount=<?= $cp['cp_price'] ?> data-subject="<?= $cp['cp_subject'] ?>" data-trunc="<?= $cp['cp_trunc'] ?>"></option>
                                    <? endforeach ?>
                                <? else : ?>
                                    <option>사용 가능한 쿠폰이 없습니다</option>
                                <? endif ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <div class="order-info-separator"></div>
                <div class="order-coupon-content-title">배송비쿠폰</div>
                <table>
                    <tr class="coupon-row-mobile" data-ct='send'>
                        <td>
                            <div>
                                배송비 : <?= number_format($send_cost) ?>원<span class="coupon-row-mobile-discount"></span>
                            </div>
                            <select>
                                <? if ($it_send_cost > 0 && !empty($member_coupon[3])) : ?>
                                    <option>쿠폰 선택</option>
                                    <? foreach ($member_coupon[3] as $cp) : ?>
                                        <option value="<?= $cp['cp_id'] ?>" data-min=<?= $cp['cp_minimum'] ?> data-max=<?= $cp['cp_maximum'] ?> data-no=<?= $cp['cp_no'] ?> data-type=<?= $cp['cp_type'] ?> data-discount=<?= $cp['cp_price'] ?> data-trunc="<?= $cp['cp_trunc'] ?>"><?= $cp['cp_subject'] ?> - <?= number_format($cp['cp_price']) ?>원 할인</option>
                                    <? endforeach ?>
                                <? else : ?>
                                    <option>사용 가능한 쿠폰이 없습니다</option>
                                <? endif ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <div class="order-info-separator"></div>
                <div style="font-size: 10px; padding: 16px 14px; padding-left: 25px; color: #424242; line-height: 1.8;">
                    <div style="text-indent: -9px;">
                        <span class="dot-desc"></span>쿠폰 유형에 따라 쿠폰 적용 가능 상품, 금액 및 총 할인 금액이 제한될 수 있습니다.
                    </div>
                    <div style="text-indent: -9px;">
                        <span class="dot-desc"></span>쿠폰리스트에서는 보유 중인 쿠폰만 확인 할 수 있습니다.
                    </div>
                    <div style="text-indent: -9px;">
                        <span class="dot-desc"></span>보유 중인 쿠폰 목록은 마이페이지 > 쿠폰조회에서 확인하시기 바랍니다.
                    </div>
                </div>
                <div class="order-info-separator"></div>
                <div style="font-size: 12px; padding: 16px 14px; color: #333333; line-height: 1.8;">
                    <div style="display: flex; justify-content: space-between;">
                        <span>상품할인</span>
                        <span id="order-coupon-preview-mobile-item" style="font-size: 14px;"></span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>장바구니할인</span>
                        <span id="order-coupon-preview-mobile-cart" style="font-size: 14px;"></span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>배송비할인</span>
                        <span id="order-coupon-preview-mobile-send" style="font-size: 14px;"></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 16px; border-top: 1px solid #e0e0e0; margin-top: 4px; padding-top: 8px;">
                        <span>총 결제금액</span>
                        <span id="order-coupon-preview-mobile-total" style="font-size: 18px;"></span>
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 20px 14px;">
                    <button type="button" class="btn btn-cart-action white" data-dismiss="modal">취소</button>
                    <button type="button" class="btn btn-cart-action" style="margin-left: 14px;" onclick=applyCoupon(true)>적용</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 배송지목록 -->
<div class="modal fade" id="modal-order-address" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="min-width: 940px; width: 940px;">
        <div class="modal-content" style="width: 100%; height: auto; margin-left: unset; padding: unset !important;">
            <div style="text-align: center; padding: 17px 0; border-bottom: 1px solid #e0e0e0; font-size: 26px; font-weight: 500; color: #090909;">
                배송지목록
                <button type="button" data-dismiss="modal" aria-label="close" style="width: 18px; height: 18px; background: url(/img/re/cancle@3x.png) center center no-repeat; background-size: 18px; position: absolute; right: 12px; top: 15px; border: unset;"></button>
            </div>
            <div id="modal-order-address-content">
                <table id="order-address-list"></table>
            </div>
            <div id="modal-order-address-button">
                <button type="button" class="btn btn-cart-action only-result white" onclick="setDefaultAddress()">기본배송지로 저장</button>
                <button type="button" class="btn btn-cart-action white" onclick="openUpdateAddress(false)">배송지 추가</button>
                <button type="button" class="btn btn-cart-action only-result" onclick="selectAddress()">선택 완료</button>
            </div>
        </div>
    </div>
</div>

<!-- 배송지 목록 모바일 -->
<div class="modal bottom" id="modal-order-address-mobile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div style="text-align: center; height: 50px; border-bottom: 1px solid #e0e0e0; font-size: 18px; font-weight: 500; color: #090909; line-height: 50px;">
                배송지목록
                <button type="button" data-dismiss="modal" aria-label="close" style="width: 18px; height: 18px; background: url(/img/re/cancle@3x.png) center center no-repeat; background-size: 18px; position: absolute; right: 12px; top: 15px; border: unset;"></button>
            </div>
            <div style="padding: 14px; padding-bottom: unset;">
                <button type="button" class="btn btn-cart-action white" onclick="openUpdateAddress(true)">배송지 추가</button>
            </div>
            <div id="modal-order-address-content-mobile">
                <table id="order-address-list-mobile"></table>
            </div>
            <div id="modal-order-address-button-mobile">
                <button type="button" class="btn btn-cart-action only-result white" onclick="setDefaultAddress()">기본배송지로 저장</button>
                <button type="button" class="btn btn-cart-action only-result" onclick="selectAddress(true)">선택 완료</button>
            </div>
        </div>
    </div>
</div>

<!-- 배송지추가/수정 -->
<div class="modal" id="modal-order-address-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="min-width: 500px; width: 500px;">
        <div class="modal-content" style="width: 100%; height: auto; margin-left: unset; padding: unset !important;">
            <div style="text-align: center; padding: 17px 0; border-bottom: 1px solid #e0e0e0; font-size: 26px; font-weight: 500; color: #090909;">
                <span class="order-address-update-title"></span>
                <button type="button" data-dismiss="modal" aria-label="close" style="width: 18px; height: 18px; background: url(/img/re/cancle@3x.png) center center no-repeat; background-size: 18px; position: absolute; right: 12px; top: 15px; border: unset;"></button>
            </div>
            <form name="formOrderAddressUpdate">
                <div id="modal-order-address-form-content">
                    <input type="hidden" name="ad_id">
                    <input type="hidden" name="ad_addr3">
                    <input type="hidden" name="ad_addr_jibeon">
                    <table>
                        <tr>
                            <th style="width: 140px;">수령인<span class="point-require">*</span></th>
                            <td>
                                <input type="text" placeholder="수령인 입력" name="ad_name" required>
                            </td>
                        </tr>
                        <tr>
                            <th>휴대폰번호<span class="point-require">*</span></th>
                            <td style="font-size: 0;">
                                <select style="width: 100px; margin-right: 20px;" name="ad_hp_1">
                                    <option value="010">010</option>
                                    <option value="011">011</option>
                                    <option value="016">016</option>
                                    <option value="017">017</option>
                                    <option value="018">018</option>
                                    <option value="019">019</option>
                                </select>
                                <input type="number" placeholder="휴대폰번호 입력" name="ad_hp_2" style="width: 220px;" required>
                            </td>
                        </tr>
                        <tr>
                            <th>배송지<span class="point-require">*</span></th>
                            <td>
                                <input type="text" name="ad_zip" placeholder="우편번호" onclick="win_zip('formOrderAddressUpdate','ad_zip' , 'ad_addr1', 'ad_addr2', 'ad_addr3','ad_addr_jibeon');" style="width: 210px;" readonly required>
                                <button class="btn btn-cart-action btn-black-2" type="button" style="margin-top: 0; vertical-align: top; margin-left: 14px;" onclick="win_zip('formOrderAddressUpdate','ad_zip' , 'ad_addr1', 'ad_addr2', 'ad_addr3','ad_addr_jibeon');">우편번호</button>
                            </td>
                        </tr>
                        <tr>
                            <th></th>
                            <td>
                                <input type="text" name="ad_addr1" placeholder="기본주소" style="width: 340px;" onclick="win_zip('formOrderAddressUpdate','ad_zip' , 'ad_addr1', 'ad_addr2', 'ad_addr3','ad_addr_jibeon');" readonly required>
                            </td>
                        </tr>
                        <tr>
                            <th></th>
                            <td>
                                <input type="text" name="ad_addr2" placeholder="상세주소" style="width: 340px;" required>
                            </td>
                        </tr>
                        <tr>
                            <th></th>
                            <td>
                                <div class="custom-checkbox" style="height: 28px;">
                                    <input type="checkbox" id="check-order-address-default" class="custom-control-input" name="ad_default">
                                    <label class="custom-control-label" for="check-order-address-default" style="line-height: normal; font-size: 14px;">기본배송지로 저장</label>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="modal-order-address-form-button">
                    <button type="button" class="btn btn-cart-action white" onclick="$('#modal-order-address-form').modal('hide')">취소</button>
                    <button type="submit" class="btn btn-cart-action">저장</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- 배송지추가/수정 모바일 -->
<div class="modal bottom" id="modal-order-address-form-mobile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 100%; height: auto; margin-left: unset; padding: unset !important;">
            <div style="text-align: center; height: 50px; border-bottom: 1px solid #e0e0e0; font-size: 18px; font-weight: 500; color: #090909; line-height: 50px;">
                <span class="order-address-update-title"></span>
                <button type="button" data-dismiss="modal" aria-label="close" style="width: 18px; height: 18px; background: url(/img/re/cancle@3x.png) center center no-repeat; background-size: 18px; position: absolute; right: 12px; top: 15px; border: unset;"></button>
            </div>
            <form name="formOrderAddressUpdateMobile">
                <div id="modal-order-address-form-content-mobile">
                    <input type="hidden" name="ad_id">
                    <input type="hidden" name="ad_addr3">
                    <input type="hidden" name="ad_addr_jibeon">
                    <table>
                        <tr>
                            <th style="width: 140px;">수령인<span class="point-require">*</span></th>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" placeholder="수령인 입력" name="ad_name" required style="width: 100%;">
                            </td>
                        </tr>
                        <tr>
                            <th>휴대폰번호<span class="point-require">*</span></th>
                        </tr>
                        <tr>
                            <td style="font-size: 0;">
                                <select style="width: 100px; margin-right: 14px;" name="ad_hp_1">
                                    <option value="010">010</option>
                                    <option value="011">011</option>
                                    <option value="016">016</option>
                                    <option value="017">017</option>
                                    <option value="018">018</option>
                                    <option value="019">019</option>
                                </select>
                                <input type="number" placeholder="휴대폰번호 입력" name="ad_hp_2" style="width: calc(100% - 114px)" required>
                            </td>
                        </tr>
                        <tr>
                            <th>배송지<span class="point-require">*</span></th>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 10px; font-size: 0;">
                                <input type="text" name="ad_zip" placeholder="우편번호" onclick="win_zip('formOrderAddressUpdateMobile','ad_zip' , 'ad_addr1', 'ad_addr2', 'ad_addr3','ad_addr_jibeon');" style="width: calc(100vw - 118px);" readonly required>
                                <button class="btn btn-cart-action btn-black-2" type="button" style="margin-top: 0; vertical-align: top; margin-left: 10px; width: 80px !important;" onclick="win_zip('formOrderAddressUpdateMobile','ad_zip' , 'ad_addr1', 'ad_addr2', 'ad_addr3','ad_addr_jibeon');">우편번호</button>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 10px;">
                                <input type="text" name="ad_addr1" placeholder="기본주소" style="width: 100%;" onclick="win_zip('formOrderAddressUpdateMobile','ad_zip' , 'ad_addr1', 'ad_addr2', 'ad_addr3','ad_addr_jibeon');" readonly required>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 10px;">
                                <input type="text" name="ad_addr2" placeholder="상세주소" style="width: 100%;" required>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 26px;">
                                <div class="custom-checkbox" style="height: 28px; padding-left: 22px;">
                                    <input type="checkbox" id="check-order-address-default-mobile" class="custom-control-input" name="ad_default">
                                    <label class="custom-control-label" for="check-order-address-default-mobile" style="line-height: normal; font-size: 14px;">기본배송지로 저장</label>
                                </div>
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


<script>
    function updateCouponPreview(mobile=false,e=false) {
        const orderPrice = $("#order-org-price").val() * 1;
        let discountItem = 0;
        let discountCart = 0;
        let discountSend = 0;

        const targetRow = mobile == true ? ".coupon-row-mobile" : ".coupon-row";

        $(targetRow).each(function(ri, re) {
            const ctid = $(re).data("ct");
            const $coupons = $(re).find("option");
            let discountRow = 0;
            let couponNo = [];

            switch (ctid) {
                case 'cart':
                    let cartPrice = orderPrice - discountItem;
                    couponNo = [];

                    $coupons.each(function(oi, oe) {
                        if ($(oe).data("discount")) {
                            if (!Math.trunc) { 
                                Math.trunc = function (v) {
                                return v < 0 ? Math.ceil(v) : Math.floor(v);
                                };
                            }
                            let dc = $(oe).data("type") == 1 ? Math.trunc((cartPrice / 100 * $(oe).data("discount")) / ($(oe).data("trunc") * 10)) * ($(oe).data("trunc") * 10) : $(oe).data("discount");
                            if (dc > $(oe).data("max")) dc = $(oe).data("max");
                            const subject = $(oe).data("subject") + " - " + number_format(dc) + "원 할인";
                            $(oe).text(subject);
                            $(oe).prop("disabled", false);

                            if ( $(oe).data("min") > cartPrice ) {
                                $(oe).prop("disabled", true).prop("selected", false);
                            }
                            else if ($(oe).prop("selected")) {
                                discountRow += dc;
                                couponNo.push($(oe).val());
                            }
                        }
                    });
                    discountCart += discountRow;
                    $(re).data("used", couponNo.join(','));

                    break;
                case 'send':
                    couponNo = [];

                    $coupons.each(function(oi, oe) {
                        if ($(oe).data("discount") && $(oe).prop("selected")) {
                            discountRow += $(oe).data("discount");
                            couponNo.push($(oe).val());
                        }
                    });
                    discountSend += discountRow;
                    $(re).data("used", couponNo.join(','));

                    break;
                default:
                    couponNo = [];
                    $coupons.each(function(oi, oe) {
                        if ($(oe).data("discount") && $(oe).prop("selected")) {
                            if ($(oe).data("plus")) {
                                discountRow += $(oe).data("discount");
                                couponNo.push($(oe).val());
                            } else {
                                const plusCouponSelector = $(oe).parent().next(".selector-coupon-plus");

                                discountRow += $(oe).data("discount");
                                couponNo.push($(oe).val());

                                if (plusCouponSelector.length > 0) {
                                    const newCartPrice = plusCouponSelector.closest("tr").data("price") * 1 - discountRow;
                                    plusCouponSelector.find("option").each(function(pi, pe) {
                                        if ($(pe).data("discount")) {
                                            const dc = $(pe).data("type") == "1" ? Math.trunc((newCartPrice / 100 * $(pe).data("price")) / ($(pe).data("trunc") * 10)) * ($(pe).data("trunc") * 10) : $(pe).data("price");
                                            $(pe).data("discount", dc);
                                            $(pe).text($(pe).data("subject") + " - " + number_format(dc) + "원 할인");
                                        }
                                    });
                                }
                            }
                        }
                    });

                    discountItem += discountRow;
                    $(re).data("used", couponNo.join(','));

                    break;
            }

            $(re).find(".coupon-row-discount").text(number_format(discountRow));
            $(re).find(".coupon-row-mobile-discount").text("");
            if (discountRow > 0) {
                $(re).find(".coupon-row-mobile-discount").text("(-" + number_format(discountRow) + "원)");
            }
        });

        $("#order-coupon-preview-item-total").text(number_format(orderPrice - discountItem)).data("value", orderPrice - discountItem);
        $("#order-coupon-preview-item-total-mobile").text(number_format(orderPrice - discountItem)).data("value", orderPrice - discountItem);
        $("#order-coupon-preview-item").text(number_format(discountItem) + "원").data("value", discountItem);
        $("#order-coupon-preview-cart").text(number_format(discountCart) + "원").data("value", discountCart);
        $("#order-coupon-preview-send").text(number_format(discountSend) + "원").data("value", discountSend);
        $("#order-coupon-preview-total").text(number_format(discountItem + discountCart + discountSend) + "원").data("value", discountItem + discountCart + discountSend);
        $("#order-coupon-preview-mobile-item").text(number_format(discountItem) + "원").data("value", discountItem);
        $("#order-coupon-preview-mobile-cart").text(number_format(discountCart) + "원").data("value", discountCart);
        $("#order-coupon-preview-mobile-send").text(number_format(discountSend) + "원").data("value", discountSend);
        $("#order-coupon-preview-mobile-total").text(number_format(orderPrice - discountItem - discountCart - discountSend) + "원").data("value", orderPrice - discountItem - discountCart - discountSend);
    }

    function calcMaxDiscount() {
        let usedCPNO = new Array;
        let usedPCPNO = new Array;
        let options = new Array;
        let tmp_price = 0;

        $("#modal-order-coupon-content").find("option").each(function(oi, oe) {
            if ($(oe).data("no")) {
                let CTID = $(oe).closest("tr").data("ct");
                let price = $(oe).closest("tr").data("price");

                if (CTID == 'cart') {
                    price = $("#order-coupon-preview-item-total").data("value");
                }
                    tmp_price = price;

                    if ( ! $(oe).prop("disabled") )
                    {
                        options.push({
                            elem: oe,
                            ct: CTID,
                            no: $(oe).data("no"),
                            type: $(oe).data("type"),
                            discount: $(oe).data("discount"),
                            min: $(oe).data("min"),
                            max: $(oe).data("max"),
                            price: price,
                            plus: $(oe).data("plus")
                        });

                    }
            }
        });

        options.sort(function(a, b) {
            let discount_a = 0;
            let discount_b = 0;

            if (a.type == "1") {
                discount_a = (a.price * 1 / 100 ) * a.discount;
            } else {
                discount_a = a.discount;
            }
            if (b.type == "1") {
                discount_b = (b.price * 1 / 100 ) * b.discount;
            } else {
                discount_b = b.discount;
            }

            discount_a = discount_a > a.max ? a.max : discount_a;
            discount_b = discount_b > b.max ? b.max : discount_b;
            
            if (discount_a > discount_b) {
                return -1;
            }
            if (discount_a < discount_b) {
                return 1;
            }
            return 0;
        });

        $(options).each(function(oi, oe) {
            if ( parseInt(oe.ct).toString() == 'NaN') { // 장바구니, 배송비 쿠폰
                if ( oe.min > 0 && tmp_price < oe.min) {
                    $(oe.elem).prop('disabled', true);
                }
                else if (usedCPNO[oe.ct] === undefined && usedCPNO.indexOf(oe.no) < 0 ) {
                    $(oe.elem).prop('disabled', false);
                    usedCPNO[oe.ct] = oe.no;
                }
            }
            else {
                if (oe.plus != true && usedCPNO[oe.ct] === undefined && usedCPNO.indexOf(oe.no) < 0) {
                    usedCPNO[oe.ct] = oe.no;
                    tmp_price -= oe.discount;
                }
                if (oe.plus == true && usedPCPNO[oe.ct] === undefined && usedPCPNO.indexOf(oe.no) < 0) {
                    usedPCPNO[oe.ct] = oe.no;
                    tmp_price -= oe.discount;
                }
            }
        });


        $(".coupon-row").each(function(ti, te) {
            const ct = $(te).data("ct");
            const no = usedCPNO[$(te).data("ct")];
            $(te).find("option").each(function(oi, oe) {
                if ($(oe).data("no") == no || $(oe).data("no") == usedPCPNO[ct] && !$(oe).prop("disabled")) $(oe).prop("selected", true);
            });
        });

        $(".coupon-row-mobile").each(function(ti, te) {
            const ct = $(te).data("ct");
            const no = usedCPNO[$(te).data("ct")];
            $(te).find("option").each(function(oi, oe) {
                if ($(oe).data("no") == no || $(oe).data("no") == usedPCPNO[ct] && !$(oe).prop("disabled")) $(oe).prop("selected", true);
            });
        });

        $(usedCPNO).each(function(ct, no) {
            if (no !== undefined) {

            }
        });

        return updateCouponPreview();
    }

    function unsetCoupons() {
        $("#modal-order-coupon-content").find("select").each(function(si, se) {
            $(se).find("option").eq(0).prop("selected", true);
        });
        $("#modal-order-coupon-content-mobile").find("select").each(function(si, se) {
            $(se).find("option").eq(0).prop("selected", true);
        });

        updateCouponPreview();
        updateCouponPreview(true);

        return true;
    }

    function applyCoupon(mobile) {
        const formOrder = document.getElementById("forderform");
        const targetRow = mobile == true ? ".coupon-row-mobile" : ".coupon-row";

        $(formOrder).find("input[name^='cp_id']").val("");
        $(formOrder).find("input[name='od_cp_id']").val("");
        $(formOrder).find("input[name='od_send_cp_id']").val("");

        formOrder.item_coupon.value = $("#order-coupon-preview-item").data("value");
        formOrder.od_coupon.value = $("#order-coupon-preview-cart").data("value");
        formOrder.od_send_coupon.value = $("#order-coupon-preview-send").data("value");

        let cpId = '';
        let cpOverlapCheck = false;
        $(targetRow).each(function(ri, re) {
            if ($(re).data("used")) {
                const CTID = $(re).data("ct");
                if (cpId.indexOf($(re).data("used")) != -1) { 
                    cpOverlapCheck = true;
                } else {
                    cpId = cpId +','+$(re).data("used");
                }
                if ($(re).data("used"))
                if (true);
                switch (CTID) {
                    case 'cart':
                        formOrder.od_cp_id.value = $(re).data("used");
                        break;
                    case 'send':
                        formOrder.od_send_cp_id.value = $(re).data("used");
                        break;
                    default:
                        $("#order-coupon-id-" + CTID).val($(re).data("used"));
                        break;
                }
            }
        });
        if (cpOverlapCheck) {
            return  alert('동일한 쿠폰을 중복으로 사용할 수 없습니다.');
        }

        $("#order-discount-coupon").val(number_format($("#order-coupon-preview-total").data("value")));
        $("#order-discount-coupon-mobile").val(number_format($("#order-coupon-preview-total").data("value")));
        if (typeof calculate_order_price == "function") {
            calculate_order_price();
        }

        if (mobile) {
            return $("#modal-order-coupon-mobile").modal("hide");
        }
        return $("#modal-order-coupon").modal("hide");
    }

    function requestAddress(action, data) {
        try {
            $.post("/shop/ajax.address.php?action=" + action, data, function(response) {
                if (response.result) {
                    switch (action) {
                        case "r":
                            let htmlAddress = "";
                            let htmlAddressMobile = "";

                            $("#order-address-list").html("");
                            $("#order-address-list-mobile").html("");

                            if (response.data.length > 0) {
                                for (di in response.data) {
                                    htmlAddress = "<tr>" +
                                        "<td style='width: 55px; padding-left: 20px;'><div class='custom-control custom-radio'>" +
                                        "<input type='radio' class='custom-control-input' id='order-address-" + response.data[di].ad_id + "' name='ad' value='" + response.data[di].ad_id + "' checked=" + (di == 0) + " data-json='" + JSON.stringify(response.data[di]) + "'>" +
                                        "<label class='custom-control-label' for='order-address-" + response.data[di].ad_id + "'></label></div></td>" +
                                        "<td style='width: 65x;'>" + response.data[di].ad_name + "</td>" +
                                        "<td style='width: 170px; padding-left: 20px;'>" + response.data[di].ad_hp + "</td>" +
                                        "<td style='width: 400px; word-break: keep-all;'>(" + response.data[di].ad_zip1 + response.data[di].ad_zip2 + ")" + response.data[di].ad_addr1 + " " + response.data[di].ad_addr2 + " </td>" +
                                        "<td style='width: 250px; padding-right: 20px; text-align: right;'>" +
                                        "<button type='button' class='btn btn-cart-action white' onclick='openUpdateAddress(false," + response.data[di].ad_id + ")' style='border-radius: 22px;'>수정</button><button type='button' class='btn btn-cart-action white' onclick='deleteAddress(" + response.data[di].ad_id + ")' style='border-radius: 22px; margin-left: 8px;'>삭제</button>" +
                                        "</td></tr>";
                                    $("#order-address-list").prepend(htmlAddress);

                                    htmlAddressMobile = "<tr><td style='width: 42px; padding-left: 14px;'><div class='custom-control custom-radio'>" +
                                        "<input type='radio' class='custom-control-input' id='order-address-mobile-" + response.data[di].ad_id + "' name='ad' value='" + response.data[di].ad_id + "' checked=" + (di == 0) + " data-json='" + JSON.stringify(response.data[di]) + "'>" +
                                        "<label class='custom-control-label' for='order-address-mobile-" + response.data[di].ad_id + "'></label></div></td>" +
                                        "<td style='font-size: 12px; padding: 16px 0; line-height: 24px; min-height: 128px;'>" +
                                        "<div>" + response.data[di].ad_name + "</div>" +
                                        "<div>" + response.data[di].ad_hp + "</div>" +
                                        "<div>(" + response.data[di].ad_zip1 + response.data[di].ad_zip2 + ")" + response.data[di].ad_addr1 + " " + response.data[di].ad_addr2 + "</div></td>" +
                                        "<td style='width: 50px; padding-right: 14px; text-align: right; line-height: 48px;'>" +
                                        "<button type='button' class='btn btn-cart-action white' onclick='openUpdateAddress(true," + response.data[di].ad_id + ")' style='border-radius: 22px; width: 64px !important; height: 36px !important;'>수정</button><button type='button' class='btn btn-cart-action white' onclick='deleteAddress(" + response.data[di].ad_id + ")' style='width: 64px !important; height: 36px !important;border-radius: 22px; margin-left: 8px;'>삭제</button></td></tr>";
                                    $("#order-address-list-mobile").prepend(htmlAddressMobile);
                                }
                                $("#modal-order-address-button").removeClass("no-result");
                            } else {
                                $("<tr><td class='order-address-no-result'>등록된 주소지가 없습니다.<br><strong>배송지 추가</strong>버튼 선택 후 배송지를 등록해주세요.</td></tr>").appendTo($("#order-address-list"));
                                $("#modal-order-address-button").addClass("no-result");
                                $("#modal-order-address-button-mobile").addClass("no-result");
                            }
                            break;
                        case "u":
                            const actionType = data['ad_id'].length > 0 ? "수정" : "추가"
                            alert("배송지가 " + actionType + "되었습니다.");
                            $("#modal-order-address-form").modal("hide");
                            $("#modal-order-address-form-mobile").modal("hide");
                            break;
                        case "d":
                            alert("배송지가 삭제되었습니다.");
                            return requestAddress('r');
                            break;
                        case "default":
                            alert("기본배송지 저장이 완료되었습니다.");
                            break;
                    }
                } else {
                    return alert(response.msg);
                }
            }, 'JSON');
        } catch (e) {
            console.log(e);
            return false;
        }
        return false;
    }

    $("#modal-order-address").on("show.bs.modal", function() {
        requestAddress("r");
    });
    $("#modal-order-address-form").on("hide.bs.modal", function() {
        $("#modal-order-address").modal("show");
    });
    $("#modal-order-address-mobile").on("show.bs.modal", function() {
        requestAddress("r");
    });
    $("#modal-order-address-form-mobile").on("hide.bs.modal", function() {
        $("#modal-order-address-mobile").modal("show");
    });
    // $("#modal-order-coupon-mobile").on("hide.bs.modal", function() {
    //     applyCoupon(true);
    // });

    function setDefaultAddress() {
        const selectedAddrElem = $("#order-address-list").find("input[type='radio']:checked");
        requestAddress('default', {
            ad_id: selectedAddrElem.val()
        });
    };

    function selectAddress(mobile) {
        const formOrder = document.forderform;
        const selectedAddr = mobile == true ? $("#order-address-list-mobile").find("input[type='radio']:checked").data("json") : $("#order-address-list").find("input[type='radio']:checked").data("json");
        const splitHP = selectedAddr.ad_hp.split('-');

        formOrder.od_b_name.value = selectedAddr.ad_name;
        formOrder.od_b_zip.value = selectedAddr.ad_zip1 + selectedAddr.ad_zip2;
        formOrder.od_b_addr1.value = selectedAddr.ad_addr1;
        formOrder.od_b_addr2.value = selectedAddr.ad_addr2;
        formOrder.od_b_addr3.value = selectedAddr.ad_addr3;
        formOrder.od_b_addr_jibeon.value = selectedAddr.ad_jibeon;
        formOrder.od_b_hp_1.value = splitHP[0];
        formOrder.od_b_hp_2.value = splitHP[1] + splitHP[2];

        alert("배송지가 변경되었습니다.");
        if (mobile) {
            formOrder.od_b_name_mobile.value = selectedAddr.ad_name;
            formOrder.od_b_zip_mobile.value = selectedAddr.ad_zip1 + selectedAddr.ad_zip2;
            formOrder.od_b_addr1_mobile.value = selectedAddr.ad_addr1;
            formOrder.od_b_addr2_mobile.value = selectedAddr.ad_addr2;
            formOrder.od_b_hp_1_mobile.value = splitHP[0];
            formOrder.od_b_hp_2_mobile.value = splitHP[1] + splitHP[2];

            $("#delivery-preview-name").text(selectedAddr.ad_name);
            $("#delivery-preview-hp").text(selectedAddr.ad_hp);
            $("#delivery-preview-address").text("(" + selectedAddr.ad_zip1 + selectedAddr.ad_zip2 + ")" + selectedAddr.ad_addr1 + " " + selectedAddr.ad_addr2);
            $("#modal-order-address-mobile").modal("hide");
        } else {
            $("#modal-order-address").modal("hide");
        }
    };

    function openUpdateAddress(mobile, ADID) {
        const formAddress = mobile == true ? document.formOrderAddressUpdateMobile : document.formOrderAddressUpdate;
        if (ADID) {
            const selectedAddr = $("#order-address-" + ADID).data("json");
            const splitHP = selectedAddr.ad_hp.split('-');

            formAddress.ad_id.value = selectedAddr.ad_id;
            formAddress.ad_name.value = selectedAddr.ad_name;
            formAddress.ad_hp_1.value = splitHP[0];
            formAddress.ad_hp_2.value = splitHP[1] + splitHP[2];
            formAddress.ad_zip.value = selectedAddr.ad_zip1 + selectedAddr.ad_zip2;
            formAddress.ad_addr1.value = selectedAddr.ad_addr1;
            formAddress.ad_addr2.value = selectedAddr.ad_addr2;
            formAddress.ad_addr3.value = selectedAddr.ad_addr3;
            formAddress.ad_addr_jibeon.value = selectedAddr.ad_jibeon;
            $(".order-address-update-title").text("배송지 수정");
        } else {
            formAddress.ad_id.value = "";
            formAddress.ad_name.value = "";
            formAddress.ad_hp_1.value = "010";
            formAddress.ad_hp_2.value = "";
            formAddress.ad_zip.value = "";
            formAddress.ad_addr1.value = "";
            formAddress.ad_addr2.value = "";
            formAddress.ad_addr3.value = "";
            formAddress.ad_addr_jibeon.value = "";
            formAddress.ad_default.checked = false;
            $(".order-address-update-title").text("배송지 추가");
        }

        if (mobile) {
            $("#modal-order-address-mobile").modal("hide");
            $("#modal-order-address-form-mobile").modal("show");
        } else {
            $("#modal-order-address").modal("hide");
            $("#modal-order-address-form").modal("show");
        }
    }

    $(document.formOrderAddressUpdate).on("submit", function() {
        return updateAddress();
    });
    $(document.formOrderAddressUpdateMobile).on("submit", function() {
        return updateAddress(true);
    });

    function updateAddress(mobile) {
        const formAddress = mobile == true ? document.formOrderAddressUpdateMobile : document.formOrderAddressUpdate;

        if (formAddress.ad_zip.value.length * formAddress.ad_addr1.value.length * formAddress.ad_addr2.value.length == 0) {
            alert("배송지를 정확하게 입력해주세요.");
            return false;
        }

        let data = {};
        data["ad_hp_1"] = formAddress.ad_hp_1.value;

        $(formAddress).find("input").each(function(ii, ie) {
            if (ie.name == 'ad_default') {
                data[ie.name] = ie.checked;
            } else {
                data[ie.name] = ie.value;
            }
        });

        requestAddress('u', data);

        return false;
    }

    function deleteAddress(ADID) {
        const data = {
            "ad_id": ADID
        };

        if (confirm("배송지를 삭제하시겠습니까?")) {
            return requestAddress('d', data);
        }
        return false;
    }

    $("#order-coupon-apply").on("click", function() {
        return applyCoupon();
    });

    $("#order-coupon-best-mobile").on("change", function() {
        unsetCoupons();
        if ($(this).prop("checked")) {
            return calcMaxDiscount();
        }
        return false;
    });

    $("#order-coupon-best").on("change", function() {
        $("#order-coupon-best-mobile").prop("checked", $(this).prop("checked"));
        unsetCoupons();
        if ($(this).prop("checked")) {
            return calcMaxDiscount();
        }
        return false;
    });

 

    $("#order-coupon-best").click();
    $("#modal-order-coupon-content select").on("change", updateCouponPreview);
    $("#modal-order-coupon-content-mobile select").on("change", function() {
        return updateCouponPreview(true);
    });
    applyCoupon();
</script>