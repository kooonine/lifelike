<?php
ob_start();
$g5_title = $page_prefix . "신청";
include_once G5_LAYOUT_PATH . "/nav.member.php";
?>
<link rel="stylesheet" href="/re/css/shop.css">
<style>
    .reason-cost {
        display: none;
        font-size: 14px;
        font-weight: 500;
        color: #f93f00;
        margin-left: 14px;
    }

    .reason-cost.active {
        display: inline-block;
    }

    @media (max-width: 1366px) {
        .member-order-sub-info-wapper {
            margin-top: 20px;
        }

        .member-content-title {
            font-size: 16px !important;
            border-bottom: 1px solid #f2f2f2 !important;
        }

        .member-order-detail-list>tbody>tr:last-child>td {
            border-bottom: unset !important;
        }

        .member-order-sub-info {
            margin-bottom: 10px;
        }

        .member-order-sub-info table tr>td {
            font-size: 12px;
            line-height: 32px;
        }

        .member-order-sub-info table tr:last-child>td {
            border-bottom: unset;
        }

        .order-description {
            font-size: 12px;
            font-weight: normal;
            color: #7f7f7f;
            background-color: #f2f2f2;
            padding: 8px 20px;
        }

        div.member-content-section {
            margin-bottom: 32px;
        }

        div#member-content-wrapper {
            padding: unset;
        }

        #btn-cancel-preview {
            width: 100%;
        }

        .reason-cost {
            font-size: 12px;
            font-weight: normal;
        }
    }
</style>
<div id="member-content-wrapper">
    <form name="formOrderCancel" action="" method="POST" id="form-order-cancel">
        <input type="hidden" name="pieceRefund" value="" id="preview-cancel-refund-hidden">
        <input type="hidden" name="pieceHurdleCheck" value="" id="preview-cancel-hurdle-hidden">
        <input type="hidden" name="piecePointCheck" value="" id="preview-cancel-point-hidden">
        <input type="hidden" name="send_cost2" value="" id="preview-cancel-send-cost2-hidden">
        <input type="hidden" name="pieceCartCoupon" value="" id="preview-cancel-pieceCartCoupon-hidden">

        <input type="hidden" name="od_id" value="<?= $od['od_id'] ?>">
        <input type="hidden" name="act" value="<?= $action ?>">
        <input type="hidden" name="token" value="<?= get_session("ss_token"); ?>">
        <!-- <input type="hidden" name="token" value="<?= $token ?>"> -->
        <div class="member-content-title on-big"><?= $g5_title ?></div>
        <!-- 타이틀 -->
        <div class="on-big" style="font-weight: 500; padding: 28px; border: solid 1px #f2f2f2; background-color: #f2f2f2; display: flex; justify-content: space-between;">
            <span style="font-size: 18px;">
                <span>주문번호</span>
                <span style="margin-left: 20px;"><?= $od['od_id'] ?></span>
            </span>
            <span style="font-size: 16px;">
                <span>주문일자</span>
                <span style="margin-left: 20px;"><?= date("Y.m.d", strtotime($od['od_time'])) ?></span>
            </span>
        </div>

        <!-- 타이틀 모바일 -->
        <div class="on-small" style="padding: 8px 14px; background-color: #f2f2f2;">
            <table style="width: 100%;">
                <tr>
                    <td style="font-size: 18px; font-weight: 500; color: #333333;"><?= $od['od_id'] ?></td>
                    <td style="font-size: 14px; font-weight: normal; color: #333333; width: 90px;">주문일자</td>
                    <td style="font-size: 14px; font-weight: normal; color: #333333; width: 72px;"><?= date("Y.m.d", strtotime($od['od_time'])) ?></td>
                </tr>
            </table>
        </div>

        <!-- 상품정보 -->
        <div class="member-content-section" style="margin-bottom: 20px;">
            <div class="on-big" style="margin-top: 30px; border-top: 3px solid #333333;"></div>
            <table>
                <tr class="on-big">
                    <th style="width: 74px;">
                        <div class="custom-checkbox check">
                            <input type="checkbox" class="custom-control-input ct_all" style="padding-left: 6px;" name="ct_all" value="1" id="ct_all">
                            <label class="custom-control-label" for="ct_all" style="font-size: 16px; font-weight: 500; color: #333333; padding-left: 8px;"></label>
                        </div>
                    </th>
                    <th colspan=2>상품정보</th>
                    <th>결제금액</th>
                    <th>신청수량</th>
                </tr>
                <? include_once "member.order.cancel.item.php" ?>
            </table>
            <? if ($action == "cancel") : ?>
                <div class="on-big" id="notiWarmCancel" style="font-size: 12px; line-height: 18px; color: #cccccc; padding: 16px 14px; padding-left: 9px;">
                    <div style="text-indent: -9px;"><span class="dot-desc"></span> 쿠폰을 사용한 상품을 취소하여 쿠폰 사용이 취소된 경우, 해당 쿠폰 정책에 따라 재발급이 제한될 수 있습니다.</div>
                    <div style="text-indent: -9px;"><span class="dot-desc"></span> 사용한 포인트는 취소 완료 시 자동 환원됩니다.</div>
                    <div style="text-indent: -9px;"><span class="dot-desc"></span> 부분취소시 사용하신 장바구니쿠폰은 전액 환원됩니다. (장바구니쿠폰 최소구매금액 충족시키지 못할시)</div>
                </div>
                <div class="on-small" id="notiWarmCancel-mobile" style="font-size: 10px; line-height: 18px; color: #cccccc; padding: 16px 14px; padding-left: 25px;">
                    <div style="text-indent: -9px;"><span class="dot-desc"></span> 쿠폰을 사용한 상품을 취소하여 쿠폰 사용이 취소된 경우, 해당 쿠폰 정책에 따라 재발급이 제한될 수 있습니다.</div>
                    <div style="text-indent: -9px;"><span class="dot-desc"></span> 사용한 포인트는 취소 완료 시 자동 환원됩니다.</div>
                    <div style="text-indent: -9px;"><span class="dot-desc"></span> 부분취소시 사용하신 장바구니쿠폰은 전액 환원됩니다. (장바구니쿠폰 최소구매금액 충족시키지 못할시)</div>
                </div>
            <? endif ?>
        </div>

        <!-- 교환/반품 사유 -->
        <? if ($action != "cancel") : ?>
        <div class="member-content-section on-big" style="margin-bottom: 40px;">
            <table style="width: 100%;background-color: #f2f2f2;">
                <tr>
                    <td style="text-align: left; font-size: 14px; font-weight: 500; width: 120px; border-bottom: unset; padding-top: 16px;">사유 선택</td>
                    <td style="text-align: left; border-bottom: unset; padding-top: 16px;">
                        <select name="cancel_select" id="select-cancel-reason" class="select-cancel-reason" style="background-color: #ffffff; border: 1px solid #e0e0e0;" require>
                            <option><?= $page_prefix ?> 사유를 선택해 주세요</option>
                            <? if ($action == "return") : ?>
                            <option data-free=false value="구매의사가 없어짐​">구매의사가 없어짐​</option>
                            <option data-free=false value="색상/사이즈 불만족​">색상/사이즈 불만족​</option>
                            <option data-free=true value="상품하자 및 파손​">상품하자 및 파손​</option>
                            <option data-free=true value="배송지연​">배송지연​</option>
                            <option data-free=true value="상품오배송​">상품오배송​</option>
                            <option data-free=true value="품절">품절</option>
                            <? elseif ($action == "change") : ?>
                            <option data-free=true value="상품하자 및 파손​">상품하자 및 파손​</option>
                            <option data-free=true value="상품오배송​">상품오배송​</option>
                            <option data-free=true value="기타">기타</option>
                            <? endif ?>
                        </select>
                        <span class="reason-cost">반품비 <?= number_format($default['de_return_costs']) ?>원 고객부담</span>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left; font-size: 14px; font-weight: 500; width: 120px; border-bottom: unset; padding-bottom: 28px;">기타 사유 입력</td>
                    <td style="text-align: left; border-bottom: unset; padding: 20px; padding-bottom: 28px;">
                        <textarea id="select-cancel-memo" name="cancel_memo" rows="5" placeholder="기타 사유를 입력해주세요." style="background-color: #ffffff; border: 1px solid #e0e0e0; width: 100%; font-size: 14px; padding: 12px 16px;"></textarea>
                    </td>
                </tr>
            </table>
            <div id="notiWarm" style="font-size: 12px; line-height: 18px; color: #cccccc; padding: 16px 14px; padding-left: 9px;">
                <div style="text-indent: -9px;"><span class="dot-desc"></span> 단순변심으로 인한 반품비는 결제수단에 따라 상이합니다.<br>
                    신용카드 : 결제금액에서 반품비 <?= number_format($default['de_return_costs']) ?>원 차감 후 환불<br>
                    계좌이체 : 계좌번호로 반품비 <?= number_format($default['de_return_costs']) ?>원 입금 후 환불<br>
                    (기업은행 008-576277-01-016 리탠다드(주))</div>
                <div style="text-indent: -9px;"><span class="dot-desc"></span> 주문자 이름으로 입금해주시기 바라며, 입금되지 않을 경우 반품이 불가합니다.</div>
                <div style="text-indent: -9px;"><span class="dot-desc"></span> 주문하신 주소로 상품을 수거합니다.</div>
                <div style="text-indent: -9px;"><span class="dot-desc"></span> 부분반품시 사용하신 장바구니쿠폰은 전액 환원됩니다. (장바구니쿠폰 최소구매금액 충족시키지 못할시)</div>
            </div>
        </div>
        <div class="member-content-section on-small" style="margin-bottom: 40px;">
            <table style="width: 100%;background-color: #f2f2f2;">
                <tr>
                    <td style="height: unset; border-bottom: unset; padding: 20px 14px; padding-bottom: unset; font-size: 12px; font-weight: 500;">사유 선택</td>
                </tr>
                <tr>
                    <td style="border-bottom: unset; padding: 8px 14px;">
                        <select name="cancel_select" id="select-cancel-reason-mobile" class="select-cancel-reason" style="width: 100%; background-color: #ffffff; border: 1px solid #e0e0e0;" require>
                            <option><?= $page_prefix ?> 사유를 선택해 주세요</option>
                            <? if ($action == "return") : ?>
                            <option data-free=false value="구매의사가 없어짐​">구매의사가 없어짐​</option>
                            <option data-free=false value="색상/사이즈 불만족​">색상/사이즈 불만족​</option>
                            <option data-free=true value="상품하자 및 파손​">상품하자 및 파손​</option>
                            <option data-free=true value="배송지연​">배송지연​</option>
                            <option data-free=true value="상품오배송​">상품오배송​</option>
                            <option data-free=true value="품절">품절</option>
                            <? elseif ($action == "change") : ?>
                            <option data-free=true value="상품하자 및 파손​">상품하자 및 파손​</option>
                            <option data-free=true value="상품오배송​">상품오배송​</option>
                            <option data-free=true value="기타">기타</option>
                            <? endif ?>
                        </select>
                    </td>
                </tr>
                <tr class="reason-cost">
                    <td style="padding: 16px; padding-top: 0; border: none; height: 34px; color: #f93f00;">
                        반품비 <?= number_format($default['de_return_costs']) ?>원 고객부담
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: unset; padding: 30px 14px; padding-top: unset;">
                        <textarea id="select-cancel-memo-mobile" name="cancel_memo" rows="5" placeholder="기타 사유를 입력해주세요." style="background-color: #ffffff; border: 1px solid #e0e0e0; width: 100%; font-size: 14px; padding: 12px 16px;"></textarea>
                    </td>
                </tr>
            </table>
            <div id="notiWarm-mobile" style="font-size: 10px; line-height: 18px; color: #cccccc; padding: 16px 14px; padding-left: 25px;">
                <div style="text-indent: -9px;"><span class="dot-desc"></span> 단순변심으로 인한 반품비는 결제수단에 따라 상이합니다.<br>
                    신용카드 : 결제금액에서 반품비 <?= number_format($default['de_return_costs']) ?>원 차감 후 환불<br>
                    계좌이체 : 계좌번호로 반품비 <?= number_format($default['de_return_costs']) ?>원 입금 후 환불<br>
                    (기업은행 008-576277-01-016 리탠다드(주))</div>
                <div style="text-indent: -9px;"><span class="dot-desc"></span> 주문자 이름으로 입금해주시기 바라며, 입금되지 않을 경우 반품이 불가합니다.</div>
                <div style="text-indent: -9px;"><span class="dot-desc"></span> 주문하신 주소로 상품을 수거합니다.</div>
                <div style="text-indent: -9px;"><span class="dot-desc"></span> 부분반품시 사용하신 장바구니쿠폰은 전액 환원됩니다. (장바구니쿠폰 최소구매금액 충족시키지 못할시)</div>
            </div>
        </div>

        <? endif ?>
        <? if ($action != "change") : ?>
        <div class="on-big" style="text-align: center; margin-bottom: 60px; padding: 0 14px;">
            <button type="button" class="btn-member btn-lg" id="btn-cancel-preview">계산</button>
        </div>
        <div class="on-small" style="text-align: center; margin-bottom: 60px; padding: 0 14px;">
            <button type="button" class="btn-member btn-lg" id="btn-cancel-preview-mobile">계산</button>
        </div>

        <!-- 결제정보 -->
        <div id="order-cancel-preview">
            <div class="on-big">
                <div class="member-content-title">결제정보</div>
                <div class="member-content-section">
                    <div class="on-big" style="border-top: 3px solid #333333;"></div>
                    <table>
                        <tr>
                            <td class="member-order-view-table-wide">
                                <div style="font-size: 18px; font-weight: 500; color: #656565;">총 상품금액</div>
                                <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($total_item_price) ?></div>
                            </td>
                            <td class="member-order-view-table-narrow">
                                <span class="icon-cart-calc calc-minus"></span>
                            </td>
                            <td class="member-order-view-table-wide">
                                <div style="font-size: 18px; font-weight: 500; color: #656565;">총 할인금액</div>
                                <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($total_order_price + $od['od_coupon'] + $od['od_cart_coupon'] + $od['od_send_coupon'] + $od['od_receipt_point']) ?></div>
                            </td>
                            <td class="member-order-view-table-narrow">
                                <span class="icon-cart-calc calc-plus"></span>
                            </td>
                            <td class="member-order-view-table-wide">
                                <div style="font-size: 18px; font-weight: 500; color: #656565;">총 배송비</div>
                                <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($od['od_send_cost']) ?></div>
                            </td>
                            <td class="member-order-view-table-narrow">
                                <span class="icon-cart-calc calc-eq"></span>
                            </td>
                            <td class="member-order-view-table-wide">
                                <div style="font-size: 18px; font-weight: 500; color: #656565;">총 결제금액</div>
                                <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($total_item_price - $total_order_price + $od['od_send_cost'] - $od['od_coupon'] - $od['od_cart_coupon'] - $od['od_send_coupon'] - $od['od_receipt_point']) ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="member-order-view-table-wide">
                            </td>
                            <td class="member-order-view-table-narrow"><span style="display: inline-block; border-left: 1px solid #333333;; height: 100%; margin-top: 5px;"></span></td>
                            <td class="member-order-view-table-wide" style="vertical-align: top; padding-top: 20px !important;">
                                <? if ($total_item_price > $total_order_price && $total_order_price > 0) : ?>
                                <div style="display: flex; justify-content: space-between; padding-bottom: 20px;">
                                    <span style="padding-top: 2px;">즉시할인</span>
                                    <span style="font-size: 18px; font-weight: normal;"><?= number_format($total_order_price) ?>원</span>
                                </div>
                                <? endif ?>
                                <? if (($od['od_coupon'] + $od['od_cart_coupon']) > 0) : ?>
                                <div style="display: flex; justify-content: space-between; padding-bottom: 20px;">
                                    <span style="padding-top: 2px;">쿠폰할인</span>
                                    <span style="font-size: 18px; font-weight: normal;"><?= number_format($od['od_coupon'] + $od['od_cart_coupon']) ?>원</span>
                                </div>
                                <? endif ?>
                                <? if ($od['od_receipt_point'] > 0) : ?>
                                <div style="display: flex; justify-content: space-between; padding-bottom: 20px;">
                                    <span style="padding-top: 2px;">포인트사용</span>
                                    <span style="font-size: 18px; font-weight: normal;"><?= number_format($od['od_receipt_point']) ?>P</span>
                                </div>
                                <? endif ?>
                                <? if ($od['od_send_coupon'] > 0) : ?>
                                <div style="display: flex; justify-content: space-between; padding-bottom: 20px;">
                                    <span style="padding-top: 2px;">배송비쿠폰</span>
                                    <span style="font-size: 18px; font-weight: normal;"><?= number_format($od['od_send_coupon']) ?>원</span>
                                </div>
                                <? endif ?>
                            </td>
                            <td class="member-order-view-table-narrow"><span style="display: inline-block; border-left: 1px solid #333333;; height: 100%; margin-top: 5px;"></span></td>
                            <td class="member-order-view-table-wide" style="vertical-align: top; padding-top: 20px !important;">
                            </td>
                            <td class="member-order-view-table-narrow"><span style="display: inline-block; border-left: 1px solid #333333;; height: 100%; margin-top: 5px;"></span></td>
                            <td class="member-order-view-table-wide" style="vertical-align: top; padding-top: 20px !important; padding-right: 20px !important;">
                                <div style="display: flex; justify-content: space-between; font-size: 14px; color: #f54600;">
                                    <!-- <span style="padding-top: 2px;"><?= $cancel_point_title ?></span> -->
                                    <!-- <span style="font-size: 18px; font-weight: normal;"><?= number_format(($od['od_cart_price'] - $od['od_coupon'] - $od['od_cart_coupon'] - $od['od_receipt_point']) / 100 * $default['de_point_percent']) ?>P</span> -->
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=7 style="background-color: #f2f2f2; border-bottom: 1px solid #f2f2f2; font-size: 18px; font-weight: 500; text-align: left; padding: 0 28px; height: 60px; border-bottom: 1px solid #333333;">
                                <span style="width: 210px; display: inline-block;">결제수단</span>
                                <span><?= ($easy_pay_name ? $easy_pay_name . '(' . $od['od_settle_case'] . ')' : check_pay_name_replace($od['od_settle_case'])) . "(" . $od['od_bank_account'] . ")"; ?></span>
                                <span style="font-size: 16px; margin-left: 8px;">(<?= date("Y.m.d H:i", strtotime($od['od_receipt_time'])) ?>)</span>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="member-content-title">환불정보</div>
                <div class="member-content-section">
                    <div class="on-big" style="border-top: 3px solid #333333;"></div>
                    <table>
                        <tr>
                            <td class="member-order-view-table-wide">
                                <!-- <div style="font-size: 18px; font-weight: 500; color: #656565;">총 상품금액</div>
                                <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;" ><?= number_format($total_item_price) ?></div> -->
                                <div style="font-size: 18px; font-weight: 500; color: #656565;">총 상품금액</div>
                                <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;" id="preview-cancel-tot-price"></div>
                            </td>
                            <td class="member-order-view-table-narrow">
                                <span class="icon-cart-calc calc-minus"></span>
                            </td>
                            <td class="member-order-view-table-wide">
                                <!-- <div style="font-size: 18px; font-weight: 500; color: #656565;">총 할인금액</div>
                                <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($total_order_price + $od['od_coupon'] + $od['od_cart_coupon'] + $od['od_send_coupon'] + $od['od_receipt_point']) ?></div> -->
                                <div style="font-size: 18px; font-weight: 500; color: #656565;">총 할인금액</div>
                                <!-- <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;" id="preview-cancel-tot-discount"></div> -->
                                <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;" id="preview-cancel-discount2"></div>
                            </td>
                            <td class="member-order-view-table-narrow">
                                <span class="icon-cart-calc calc-plus"></span>
                            </td>
                            <td class="member-order-view-table-wide">
                                <div style="font-size: 18px; font-weight: 500; color: #656565;">총 배송비</div>
                                <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;" id="total-send"></div>
                            </td>
                            <td class="member-order-view-table-narrow">
                                <span class="icon-cart-calc calc-minus"></span>
                            </td>
                            <td class="member-order-view-table-wide">
                                <div style="font-size: 18px; font-weight: 500; color: #656565;">환불 차감 금액</div>
                                <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;" id="preview-cancel-cost"></div>
                            </td>

                            <td class="member-order-view-table-narrow">
                                <span class="icon-cart-calc calc-eq"></span>
                            </td>
                            <td class="member-order-view-table-wide">
                                <div style="font-size: 18px; font-weight: 500; color: #656565;">환불 예정 금액</div>
                                <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;" id="preview-cancel-refund"></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="member-order-view-table-wide">
                            </td>
                            <td class="member-order-view-table-narrow"><span style="display: inline-block; border-left: 1px solid #333333;; height: 100%; margin-top: 5px;"></span></td>
                            <td class="member-order-view-table-wide" style="vertical-align: top; padding-top: 20px !important;">
                               <!-- 즉시 할인 넣기 ㅋㅋ -->
                               <? if ($total_item_price > $total_order_price && $total_order_price > 0) : ?>
                                <div style="display: flex; justify-content: space-between; padding-bottom: 20px;">
                                    <span style="padding-top: 2px;">즉시할인</span>
                                    <span style="font-size: 18px; font-weight: normal;" id="preview-cancel-discount"></span>
                                </div>
                                <? endif ?>

                                <div style="display: none; justify-content: space-between; padding-bottom: 20px;" id="preview-discount-coupon-wrapper">
                                    <span style="padding-top: 2px;">쿠폰할인</span>
                                    <span style="font-size: 18px; font-weight: normal;" id="preview-discount-coupon"></span>
                                </div>
                                <div style="display: none; justify-content: space-between; padding-bottom: 20px;" id="preview-point-wrapper">
                                    <span style="padding-top: 2px;">포인트사용</span>
                                    <span style="font-size: 18px; font-weight: normal;" id="preview-point"></span>
                                </div>
                                <? if ($od['od_send_coupon'] > 0) : ?>
                                <div style="display: flex; justify-content: space-between; padding-bottom: 20px;">
                                    <span style="padding-top: 2px;">배송비쿠폰</span>
                                    <span style="font-size: 18px; font-weight: normal;"><?= number_format($od['od_send_coupon']) ?>원</span>
                                </div>
                                <? endif ?>
                            </td>

                            <td class="member-order-view-table-narrow"><span style="display: inline-block; border-left: 1px solid #333333;; height: 100%; margin-top: 5px;"></span></td>


                            <td class="member-order-view-table-wide" style="vertical-align: top; padding-top: 20px !important;">
                            </td>


                            <td class="member-order-view-table-narrow"><span style="display: inline-block; border-left: 1px solid #333333;; height: 100%; margin-top: 5px;"></span></td>
                            <td class="member-order-view-table-wide" style="vertical-align: top; padding-top: 20px !important;">
                                <!-- <div style="display: none; justify-content: space-between; padding-bottom: 20px;" id="preview-sendcost-wrapper">
                                    <span style="padding-top: 2px;">반품배송비</span>
                                    <span style="font-size: 18px; font-weight: normal;" id="preview-sendcost"></span>
                                </div> -->
                            </td>
                            <td class="member-order-view-table-narrow"><span style="display: inline-block; border-left: 1px solid #333333;; height: 100%; margin-top: 5px;"></span>
                            </td>
                            <td class="member-order-view-table-wide" style="vertical-align: top; padding-top: 20px !important; padding-right: 20px !important;">
                                <!-- <div style="display: none; justify-content: space-between; font-size: 14px; color: #f54600;" id="preview-refund-point-wrapper">
                                    <span style="padding-top: 2px;">환불예정포인트</span>
                                    <span style="font-size: 18px; font-weight: normal;" id="preview-refund-point"></span>
                                </div> -->
                            </td>
                        </tr>
                        <tr>
                            <td colspan=9 style=" background-color: #f2f2f2; border-bottom: 1px solid #f2f2f2; font-size: 18px; font-weight: 500; text-align: left; padding: 0 28px; height: 60px; border-bottom: 1px solid #333333;">
                                <span style="width: 210px; display: inline-block;">환불결제수단</span>
                                <span><?= ($easy_pay_name ? $easy_pay_name . '(' . $od['od_settle_case'] . ')' : check_pay_name_replace($od['od_settle_case'])) . "(" . $od['od_bank_account'] . ")"; ?></span>
                            </td>
                        </tr>
                    </table>
                    <? if ($action == "cancel") : ?>
                        <div style="font-size: 12px; line-height: 18px; color: #cccccc; padding: 16px 14px; padding-left: 9px;">
                            <div style="text-indent: -9px;"><span class="dot-desc"></span> 쿠폰을 사용한 상품을 취소하여 쿠폰 사용이 취소된 경우, 해당 쿠폰 정책에 따라 재발급이 제한될 수 있습니다.</div>
                            <div style="text-indent: -9px;"><span class="dot-desc"></span> 사용한 포인트는 취소 완료 시 자동 환원됩니다.</div>
                            <div style="text-indent: -9px;"><span class="dot-desc"></span> 부분취소시 사용하신 장바구니쿠폰은 전액 환원됩니다. (장바구니쿠폰 최소구매금액 충족시키지 못할시)</div>
                        </div>
                    <? endif ?>
                    <div id="notiWarm" style="font-size: 12px; line-height: 18px; color: #cccccc; padding: 16px 14px; padding-left: 9px;">
                        <div style="text-indent: -9px;"><span class="dot-desc"></span> 단순변심으로 인한 반품비는 결제수단에 따라 상이합니다.<br>
                            신용카드 : 결제금액에서 반품비 <?= number_format($default['de_return_costs']) ?>원 차감 후 환불<br>
                            계좌이체 : 계좌번호로 반품비 <?= number_format($default['de_return_costs']) ?>원 입금 후 환불<br>
                            (기업은행 008-576277-01-016 리탠다드(주))</div>
                        <div style="text-indent: -9px;"><span class="dot-desc"></span> 주문자 이름으로 입금해주시기 바라며, 입금되지 않을 경우 반품이 불가합니다.</div>
                        <div style="text-indent: -9px;"><span class="dot-desc"></span> 주문하신 주소로 상품을 수거합니다.</div>
                        <div style="text-indent: -9px;"><span class="dot-desc"></span> 부분반품시 사용하신 장바구니쿠폰은 전액 환원됩니다. (장바구니쿠폰 최소구매금액 충족시키지 못할시)</div>
                    </div>
                </div>
            </div>

            <!-- 모바일 -->
            <div class="member-content-section on-small">
                <div class="member-content-title" style="border-top: 10px solid #f2f2f2; border-bottom: unset !important; padding: 17px 14px 20px 14px;">결제정보</div>
                <table>
                    <tr>
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">총 상품금액</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;"><?= number_format($total_item_price) ?>원</td>
                    </tr>
                    <tr>
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">총 할인금액</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;"><?= number_format($total_order_price +$od['od_coupon'] + $od['od_cart_coupon'] + $od['od_receipt_point']) ?>원</td>
                    </tr>
                    <? if ($total_item_price > $total_order_price && $total_order_price > 0) : ?>
                    <tr>
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 8px; color: #3a3a3a;">즉시할인</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 10px; color: #333333; font-weight: 500; text-align: right;"><?= number_format($total_order_price) ?>원</td>
                    </tr>
                    <? endif ?>
                    <? if (($od['od_coupon'] + $od['od_cart_coupon']) > 0) : ?>
                    <tr>
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 8px; color: #3a3a3a;">쿠폰할인</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 10px; color: #333333; font-weight: 500; text-align: right;"><?= number_format($od['od_coupon'] + $od['od_cart_coupon']) ?>원</td>
                    </tr>
                    <? endif ?>
                    <? if ($od['od_receipt_point'] > 0) : ?>
                    <tr>
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 8px; color: #3a3a3a;">포인트사용</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 10px; color: #333333; font-weight: 500; text-align: right;"><?= number_format($od['od_receipt_point']) ?>P</td>
                    </tr>
                    <? endif ?>
                    <? if ($od['od_send_coupon'] > 0) : ?>
                                <div style="display: flex; justify-content: space-between; padding-bottom: 20px;">
                                    <span style="padding-top: 2px;">배송비쿠폰</span>
                                    <span style="font-size: 18px; font-weight: normal;"><?= number_format($od['od_send_coupon']) ?>원</span>
                                </div>
                    <? endif ?>
                    <tr>
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">총 배송비</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;"><?= number_format($od['od_send_cost']) ?>원</td>
                    </tr>
                    <tr>
                        <td style="border-bottom: unset; border-top: 1px solid #e0e0e0; height: auto; padding-left: 14px; font-size: 16px; font-weight: 500; color: #3a3a3a;">총 결제금액</td>
                        <td style="border-bottom: unset; border-top: 1px solid #e0e0e0; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;"><?= number_format($total_item_price + $od['od_send_cost'] - $total_order_price - $od['od_coupon'] - $od['od_cart_coupon'] - $od['od_send_coupon'] - $od['od_receipt_point']) ?>원</td>
                    </tr>
                    <!-- <tr>
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #f93f00;">적립포인트</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #f93f00; font-weight: 500; text-align: right;"><?= number_format(($od['od_cart_price'] - $od['od_coupon'] - $od['od_cart_coupon'] - $od['od_receipt_point']) / 100 * $default['de_point_percent']) ?>P</td>
                    </tr> -->
                </table>

                <!-- <div class="member-content-title" style="border-top: 10px solid #f2f2f2; border-bottom: unset !important; padding: 17px 14px 20px 14px; display: flex; justify-content: space-between;">
                    <span>결제수단</span>
                </div>
                <table>
                    <tr>
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">결제수단</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;"><?= ($easy_pay_name ? $easy_pay_name . '(' . $od['od_settle_case'] . ')' : check_pay_name_replace($od['od_settle_case'])) . "(" . $od['od_bank_account'] . ")"; ?></td>
                    </tr>
                    <tr>
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">주문접수일시</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;"><?= date("Y.m.d H:i", strtotime($od['od_time'])) ?></td>
                    </tr>
                    <tr>
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">결제완료일시</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;"><?= date("Y.m.d H:i", strtotime($od['od_receipt_time'])) ?></td>
                    </tr>
                </table> -->

                <div class="member-content-title" style="border-top: 10px solid #f2f2f2; border-bottom: unset !important; padding: 17px 14px 20px 14px;">환불정보</div>
                <table>
                    <tr>
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">총 상품금액</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;" id="preview-cancel-tot-price-mobile"></td>
                    </tr>
                    <tr>
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">총 할인금액</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;" id="preview-cancel-discount2-mobile"></td>
                    </tr>
                    <? if ($total_item_price > $total_order_price && $total_order_price > 0) : ?>
                    <tr>
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 8px; color: #3a3a3a;">즉시할인</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 10px; color: #333333; font-weight: 500; text-align: right;" id="preview-cancel-discount-mobile">0원</td>
                    </tr>
                    <? endif ?>
                    <tr style="display: none;" id="preview-discount-coupon-wrapper-mobile">
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 8px; color: #3a3a3a;">쿠폰할인</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 10px; color: #333333; font-weight: 500; text-align: right;" id="preview-discount-coupon-mobile2">0원</td>
                    </tr>
                    <tr style="display: none;" id="preview-refund-point-wrapper-mobile">
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 8px; color: #3a3a3a;">포인트사용</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 10px; color: #333333; font-weight: 500; text-align: right;" id="preview-point-mobile">0P</td>
                    </tr>
                    <? if ($od['od_send_coupon'] > 0) : ?>
                    <tr>
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 8px; color: #3a3a3a;">배송비쿠폰</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 10px; color: #333333; font-weight: 500; text-align: right;" id="preview-point-mobile">0원</td>
                    </tr>
                    <? endif ?>


                    <!-- <tr>
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">쿠폰 할인</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;" id="preview-discount-coupon-mobile">0원</td>
                    </tr> -->
                    <tr>
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">총 배송비</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;" id="preview-order-send-cost-mobile">0원</td>
                    </tr>
                    <!-- <tr>
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">포인트 사용</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;" id="preview-point-mobile">0P</td>
                    </tr> -->
                    <tr>
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">환불차감금액</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;" id="preview-cancel-cost-mobile">0원</td>
                    </tr>

                    
                    <!-- <tr id="preview-cancelCheck" style="display: none;">
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 8px; color: #3a3a3a;">반품배송비</td>
                        <td id="preview-cancel-cost-mobile2" style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 10px; color: #333333; font-weight: 500; text-align: right">0원</td>
                    </tr> -->
           
                
                    <tr>
                        <td style="border-bottom: unset; border-top: 1px solid #e0e0e0; height: auto; padding-left: 14px; font-size: 16px; font-weight: 500; color: #3a3a3a;">환불 예정 금액</td>
                        <td style="border-bottom: unset; border-top: 1px solid #e0e0e0; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;" id="preview-cancel-refund-mobile">0원</td>
                    </tr>
                    <!-- <tr>
                        <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #f93f00;">환불예정포인트</td>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #f93f00; font-weight: 500; text-align: right;" id="preview-refund-point-mobile">0P</td>
                    </tr> -->
                </table>
                <? if ($action == "cancel") : ?>
                    <div style="font-size: 10px; line-height: 18px; color: #cccccc; padding: 16px 14px; padding-left: 25px;">
                        <div style="text-indent: -9px;"><span class="dot-desc"></span> 쿠폰을 사용한 상품을 취소하여 쿠폰 사용이 취소된 경우, 해당 쿠폰 정책에 따라 재발급이 제한될 수 있습니다.</div>
                        <div style="text-indent: -9px;"><span class="dot-desc"></span> 사용한 포인트는 취소 완료 시 자동 환원됩니다.</div>
                        <div style="text-indent: -9px;"><span class="dot-desc"></span> 부분취소시 사용하신 장바구니쿠폰은 전액 환원됩니다. (장바구니쿠폰 최소구매금액 충족시키지 못할시)</div>
                    </div>
                <? endif ?>
                <div id="notiWarm-mobile" style="font-size: 10px; line-height: 18px; color: #cccccc; padding: 16px 14px; padding-left: 25px;">
                    <div style="text-indent: -9px;"><span class="dot-desc"></span> 단순변심으로 인한 반품비는 결제수단에 따라 상이합니다.<br>
                        신용카드 : 결제금액에서 반품비 <?= number_format($default['de_return_costs']) ?>원 차감 후 환불<br>
                        계좌이체 : 계좌번호로 반품비 <?= number_format($default['de_return_costs']) ?>원 입금 후 환불<br>
                        (기업은행 008-576277-01-016 리탠다드(주))</div>
                    <div style="text-indent: -9px;"><span class="dot-desc"></span> 주문자 이름으로 입금해주시기 바라며, 입금되지 않을 경우 반품이 불가합니다.</div>
                    <div style="text-indent: -9px;"><span class="dot-desc"></span> 주문하신 주소로 상품을 수거합니다.</div>
                    <div style="text-indent: -9px;"><span class="dot-desc"></span> 부분반품시 사용하신 장바구니쿠폰은 전액 환원됩니다. (장바구니쿠폰 최소구매금액 충족시키지 못할시)</div>
                </div>
            </div>

            <div style="text-align: center; margin-bottom: 60px;">
                <button type="button" class="on-big btn-member btn-lg btn-white" onclick="history.back();">취소</button>
                <button type="button" class="btn-submit-cancel on-big btn-member btn-lg" style="margin-left: 20px;"><?= $page_prefix ?>신청</button>
                <button type="button" class="on-small btn-member btn-white" style="width: calc((100% - 42px) / 2);" onclick="history.back();">취소</button>
                <button type="button" class="btn-submit-cancel on-small btn-member" style="width: calc((100% - 42px) / 2); margin-left: 14px;"><?= $page_prefix ?>신청</button>
            </div>
        </div>
        <? else : ?>
        <div style="text-align: center; margin-bottom: 60px;">
            <button type="button" class="on-big btn-member btn-lg btn-white" onclick="history.back();">취소</button>
            <button type="button" class="btn-submit-cancel on-big btn-member btn-lg" style="margin-left: 20px;"><?= $page_prefix ?>신청</button>
            <button type="button" class="on-small btn-member btn-white" style="width: calc((100% - 42px) / 2);" onclick="history.back();">취소</button>
            <button type="button" class="btn-submit-cancel on-small btn-member" style="width: calc((100% - 42px) / 2); margin-left: 14px;"><?= $page_prefix ?>신청</button>
        </div>
        <? endif ?>
    </form>
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
?>
<script type="text/javascript">
    $(".change-address").on("click", function() {
        $("#order-delivery-info").toggleClass("change");
    });

    $(".select-order-memo").on("change", function() {
        if ($(this).find("option:checked").val() == "user") {
            $(".select-order-memo-user").addClass("active");
        } else {
            $(".select-order-memo-user").removeClass("active");
        }
    });

    $(".select-cancel-reason").on("change", function() {
        if ($(this).find("option:checked").data("free") == false) {
            $(".reason-cost").addClass("active");
        } else {
            $(".reason-cost").removeClass("active");
        }
    });
    $("input[id^=chk]").on("click", function() { 
        $("#ct_all").prop("checked", false);
    });
    $("button[id^=btn-cancel-preview]").on("click", function() {
        var buttonId = $(this).attr('id');
        let reasonFree = $("#select-cancel-reason > option:checked").data("free");
        const reasonFreeMobile = $("#select-cancel-reason-mobile > option:checked").data("free");
        const action = document.formOrderCancel.act.value;

        if (action == 'return' && reasonFree == undefined && reasonFreeMobile == undefined) {
            alert("반품사유를 선택해주세요");
                return false;
        }

        const mobile = reasonFreeMobile != undefined;

        let ctid = [];
        if (mobile) {
            reasonFree = reasonFreeMobile;
            $(".checkbox-cancel-mobile:checked").each(function(ci, ce) {
                    const tmp_ctid = $(ce).val();
                    let tmp_qty = document.getElementById('refundsNum[' + tmp_ctid + ']').innerHTML;
                    if (tmp_qty < 1) {
                        alert("취소 수량을 선택해주세요");
                        return false;
                    }
                    ctid.push({
                        ct_id: tmp_ctid,
                        qty: tmp_qty
                    });
                });
        } else {
            if (buttonId =='btn-cancel-preview-mobile') {
                $(".checkbox-cancel-mobile:checked").each(function(ci, ce) {
                    const tmp_ctid = $(ce).val();
                    let tmp_qty = document.getElementById('refundsNum[' + tmp_ctid + ']').innerHTML;
                    if (tmp_qty < 1) {
                        alert("취소 수량을 선택해주세요");
                        return false;
                    }
                    ctid.push({
                        ct_id: tmp_ctid,
                        qty: tmp_qty
                    });
                });
            } else {
                $(".checkbox-cancel:checked").each(function(ci, ce) {
                    const tmp_ctid = $(ce).val();
                    // let tmp_qty = 0;
                    let tmp_qty = document.getElementById('refundsNum[' + tmp_ctid + ']').innerHTML;
                    // $("select[name='ct_qty[" + tmp_ctid + "]']").each(function(si, se) {
                    //     if (!$(se).data("mobile")) {
                    //         tmp_qty = $(se).find("option:checked").val();
                    //     }
                    // });

                    if (tmp_qty < 1) {
                        alert("취소 수량을 선택해주세요");
                        return false;
                    }
                    ctid.push({
                        ct_id: tmp_ctid,
                        qty: tmp_qty
                    });
                });
            } 
        }
        if (ctid.length < 1) {
            return false;
        }

        let data = {
            action: action,
            odid: document.formOrderCancel.od_id.value,
            ctid: ctid,
            free: reasonFree
        };
        $("#notiWarm").css("display", "none");
        $("#notiWarm-mobile").css("display", "none");
        $("#notiWarmCancel").css("display", "none");
        $("#notiWarmCancel-mobile").css("display", "none");

        $.get("/shop/ajax.order.cancel.php", data, function(response) {        
            if (response.result) {

                $("#preview-discount-coupon-wrapper").css("display", "none");
                $("#preview-point-wrapper").css("display", "none");
                $("#preview-sendcost-wrapper").css("display", "none");
                $("#preview-refund-point-wrapper").css("display", "none");

                $("#preview-discount-coupon-wrapper-mobile").css("display", "none");
                $("#preview-point-wrapper-mobile").css("display", "none");
                $("#preview-sendcost-wrapper-mobile").css("display", "none");
                $("#preview-refund-point-wrapper-mobile").css("display", "none");
                
                $("#preview-cancel-price").text(number_format(response.data.cancel.price + response.data.order.send));
                //$("#preview-cancel-discount").text(number_format(response.data.cancel.coupon + response.data.cancel.point));
                $("#preview-cancel-cost").text(number_format(response.data.cancel.send));
                $("#preview-cancel-refund").text(number_format(response.data.cancel.pieceRefund));  
                $("#preview-cancel-refund-hidden").val(response.data.cancel.pieceRefund);  
                $("#preview-cancel-hurdle-hidden").val(response.data.cancel.pieceHurdleCheck);  
                $("#preview-cancel-point-hidden").val(response.data.cancel.piecePointCheck); 
                $("#preview-cancel-send-cost2-hidden").val(response.data.cancel.send); 
                // $("#preview-discount-coupon").text(number_format(response.data.cancel.tot_coupon) + "원");
                $("#preview-discount-coupon").text(number_format(response.data.cancel.piecetTotCoupon) + "원");
                $("#preview-discount-coupon-mobile2").text(number_format(response.data.cancel.piecetTotCoupon) + "원");
                // $("#preview-discount-coupon").text(number_format(response.data.cancel.coupon) + "원");
                $("#preview-point").text(number_format(response.data.order.point) + "P");
                $("#preview-sendcost").text(number_format(response.data.cancel.send) + "원");
                $("#preview-refund-point").text(number_format(response.data.cancel.point) + "P");

                $("#preview-discount-coupon-mobile").text(number_format(response.data.cancel.coupon) + "원");
                $("#preview-point-mobile").text(number_format(response.data.order.point) + "P");
                $("#preview-cancel-price-mobile").text(number_format(response.data.cancel.price) + "원");
                $("#preview-cancel-cost-mobile").text(number_format(response.data.cancel.send) + "원");
                $("#preview-order-send-cost-mobile").text(number_format(response.data.order.send) + "원");
                $("#preview-cancel-refund-mobile").text(number_format(response.data.cancel.pieceRefund) + "원");
                $("#preview-refund-point-mobile").text(number_format(response.data.cancel.point) + "P");

                $("#total-send").text(number_format(response.data.order.send));

                $("#preview-cancel-tot-price").text(number_format(response.data.cancel.it_price));
                $("#preview-cancel-tot-price-mobile").text(number_format(response.data.cancel.it_price)+ "원");
                $("#preview-cancel-tot-discount").text(number_format(response.data.cancel.tot_discount));
                $("#preview-cancel-discount").text(number_format(response.data.cancel.discount)+ "원");
                $("#preview-cancel-discount-mobile").text(number_format(response.data.cancel.discount)+ "원");

                $("#preview-cancel-discount2").text(number_format(response.data.cancel.pieceDiscount)+ "원");
                $("#preview-cancel-discount2-mobile").text(number_format(response.data.cancel.pieceDiscount)+ "원");

                $("#preview-cancel-pieceCartCoupon-hidden").val(response.data.cancel.pieceCartCoupon); 
                
                if (response.data.cancel.coupon > 0 || response.data.cancel.pieceCartCoupon > 0) {
                    $("#preview-discount-coupon-wrapper").css("display", "flex");
                    $("#preview-discount-coupon-wrapper-mobile").css("display", "");
                }
                if (response.data.order.point > 0) {
                    $("#preview-point-wrapper").css("display", "flex");
                    $("#preview-point-wrapper-mobile").css("display", "");
                }
                if (response.data.cancel.send > 0) {
                    $("#preview-sendcost-wrapper").css("display", "flex");
                    $("#preview-sendcost-wrapper-mobile").css("display", "");
                }
                if (response.data.cancel.point > 0) {
                    $("#preview-refund-point-wrapper").css("display", "flex");
                    $("#preview-refund-point-wrapper-mobile").css("display", "");
                }

                $("#order-cancel-preview").show();
                $("html, body").animate({
                    scrollTop: $("#btn-cancel-preview").offset().top - 60
                }, 500);
            } else {
                return;
                alert(response.error);
            }
        }, "JSON");

    });
    $(".btn-submit-cancel").on("click", function() {

        const action = document.formOrderCancel.act.value;

        if ($(this).hasClass("on-small")) {
            $("#select-cancel-reason").remove();
            $("#select-cancel-memo").remove();
            $("input[name^='chk[']").each(function(ci, ce) {
                if (!$(ce).data("mobile")) {
                    $(ce).remove();
                }
            });
            $("select[name^='ct_qty[']").each(function(ci, ce) {
                if (!$(ce).data("mobile")) {
                    $(ce).remove();
                }
            });
        } else {
            $("#select-cancel-reason-mobile").remove();
            $("#select-cancel-memo-mobile").remove();
            $("input[name^='chk[']").each(function(ci, ce) {
                if ($(ce).data("mobile")) {
                    $(ce).remove();
                }
            });
            $("select[name^='ct_qty[']").each(function(ci, ce) {
                if ($(ce).data("mobile")) {
                    $(ce).remove();
                }
            });
        }

        let actionUrl = "";
        switch (action) {
            case "cancel":
                actionUrl = "/shop/orderinquirycancel.php";
                break;
            case "return":
                actionUrl = "/shop/orderinquiryreturn.php";
                break;
            case "change":
                actionUrl = "/shop/orderinquirychange.php";
                break;
        }
        $("#form-order-cancel").attr("action", actionUrl);
        $("#form-order-cancel").submit();

        return true;
    });

    $("#ct_all").on("click", function() {
        const chk = $("input[name^='chk[']");
        const checked = $(this).prop("checked");
        $(chk).each(function(chi, che) {
            chk[chi].checked = checked;
        });
    });

    $("input[name^=chk]").on("click", function() { 
        $("#ct_all").prop("checked", false);
    });
    $(document).ready(function() {
        const chk = $("input[name^='chk[']");
        if (chk.length == 2) $("#ct_all").prop("checked", true);
    })
</script>
<?
include_once G5_LAYOUT_PATH . "/modal.review.php";
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>