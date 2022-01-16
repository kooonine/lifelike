<?php
ob_start();
$g5_title = "주문조회";
include_once G5_LAYOUT_PATH . "/nav.member.php";
?>
<link rel="stylesheet" href="/re/css/shop.css">
<style>
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
        text-align: center;
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
        .btn-member {
            font-size: 14px;
        }

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

        .member-content-title {
            font-size: 16px !important;
        }

        .member-content-desc {
            font-size: 12px !important;
        }

        #order-detail-return-wrapper,
        #order-detail-circle-wrapper {
            display: none;
            margin-top: unset !important;
        }

        #btn-toggle-order-detail-circle,
        #btn-toggle-order-detail-return {
            margin-top: 20px;
            width: 100%;
            height: 50px;
            border: solid 1px #cecece;
            background-color: #ffffff;
            font-size: 18px;
            font-weight: 500;
            text-align: center;
            color: #8a8a8a;
        }

        div.member-content-section {
            margin-bottom: 32px;
        }

        div#member-content-wrapper {
            padding: unset;
        }
    }
</style>
<div id="member-content-wrapper">
    <div class="member-content-title on-big">주문/배송 상세</div>
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
    <div class="on-small" style="padding: 18px 14px; padding-top: 8px; background-color: #f2f2f2;">
        <table style="width: 100%;">
            <tr>
                <td style="font-size: 18px; font-weight: 500; color: #333333;"><?= $od['od_status'] ?></td>
                <td style="font-size: 14px; font-weight: normal; color: #333333; width: 90px;">주문일자</td>
                <td style="font-size: 14px; font-weight: normal; color: #333333; width: 72px;"><?= date("Y.m.d", strtotime($od['od_time'])) ?></td>
            </tr>
            <tr>
                <td style="font-size: 18px; font-weight: 500; color: #333333;"><?= $od['od_id'] ?></td>
                <td style="font-size: 14px; font-weight: normal; color: #333333; width: 90px;"></td>
                <td style="font-size: 14px; font-weight: normal; color: #333333; width: 72px;"></td>
            </tr>
        </table>
    </div>

    <!-- 상품정보 -->
    <div class="member-content-section">
        <div class="on-big" style="margin-top: 30px; border-top: 3px solid #333333;"></div>
        <table>
            <tr class="on-big">
                <th colspan=2>상품정보</th>
                <th>결제금액(수량)</th>
                <th>주문상태</th>
            </tr>
            <? include_once "member.order.view.item.php" ?>
        </table>
    </div>

    <!-- 결제정보 -->
    <div class="member-content-title on-big">결제정보</div>
    <div class="member-content-section on-big">
        <div style="border-top: 3px solid #333333;"></div>
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
                    <div style="font-size: 18px; font-weight: 500; color: #656565;">총 할인금액 </div>
                    <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($total_order_price + $od['od_coupon_cancel'] + $od['od_cart_coupon_cancel'] + $od['od_send_coupon_cancel'] + $od['od_receipt_point_cancel']) ?></div>
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
                    <div style="font-size: 18px; font-weight: 500; color: #656565;">총 주문금액</div>
                    <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($total_item_price - $total_order_price - $od['od_coupon_cancel'] - $od['od_cart_coupon_cancel'] - $od['od_send_coupon_cancel'] - $od['od_receipt_point_cancel'] + $od['od_send_cost'] ) ?></div>
                    <!-- <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($od['od_receipt_price']) ?></div> -->
                </td>
            </tr>
            <tr>
                <td class="member-order-view-table-wide">
                </td>
                <td class="member-order-view-table-narrow"><span style="display: inline-block; border-left: 1px solid #333333;; height: 100%; margin-top: 5px;"></span></td>
                <td class="member-order-view-table-wide" style="vertical-align: top; padding-top: 20px !important;">
                    <? if ($total_item_price > $total_order_price) : ?>
                    <div style="display: flex; justify-content: space-between; padding-bottom: 20px;">
                        <span style="padding-top: 2px;">즉시할인</span>
                        <span style="font-size: 18px; font-weight: normal;"><?= number_format($total_order_price) ?>원</span>
                    </div>
                    <? endif ?>
                    <? if (($od['od_coupon_cancel'] + $od['od_cart_coupon_cancel']) > 0) : ?>
                    <div style="display: flex; justify-content: space-between; padding-bottom: 20px;">
                        <span style="padding-top: 2px;">쿠폰할인</span>
                        <span style="font-size: 18px; font-weight: normal;"><?= number_format($od['od_coupon_cancel'] + $od['od_cart_coupon_cancel']) ?>원</span>
                    </div>
                    <? endif ?>
                    <? if ($od['od_receipt_point_cancel'] > 0) : ?>
                    <div style="display: flex; justify-content: space-between; padding-bottom: 20px;">
                        <span style="padding-top: 2px;">포인트사용</span>
                        <span style="font-size: 18px; font-weight: normal;"><?= number_format($od['od_receipt_point_cancel']) ?>P</span>
                    </div>
                    <? endif ?>
                    <? if ($od['od_send_coupon_cancel'] > 0) : ?>
                    <div style="display: flex; justify-content: space-between; padding-bottom: 20px;">
                        <span style="padding-top: 2px;">배송비쿠폰</span>
                        <span style="font-size: 18px; font-weight: normal;"><?= number_format($od['od_send_coupon_cancel']) ?>원</span>
                    </div>
                    <? endif ?>
                </td>
                <td class="member-order-view-table-narrow"><span style="display: inline-block; border-left: 1px solid #333333;; height: 100%; margin-top: 5px;"></span></td>
                <td class="member-order-view-table-wide" style="vertical-align: top; padding-top: 20px !important;">
                    <!-- <? if ($od['od_send_coupon_cancel'] > 0) : ?>
                    <div style="display: flex; justify-content: space-between; padding-bottom: 20px;">
                        <span style="padding-top: 2px;">배송비쿠폰2</span>
                        <span style="font-size: 18px; font-weight: normal;"><?= number_format($od['od_send_coupon_cancel']) ?>원</span>
                    </div>
                    <? endif ?> -->
                </td>
                <td class="member-order-view-table-narrow"><span style="display: inline-block; border-left: 1px solid #333333;; height: 100%; margin-top: 5px;"></span></td>
                <td class="member-order-view-table-wide" style="vertical-align: top; padding-top: 20px !important; padding-right: 20px !important;">
                    <div style="display: flex; justify-content: space-between; font-size: 14px; color: #f54600;">
                        <span style="padding-top: 2px;">적립예정포인트</span>
                        <span style="font-size: 18px; font-weight: normal;"><?= number_format(($od['od_cart_price'] - $od['od_coupon'] - $od['od_cart_coupon'] - $od['od_receipt_point']) / 100 * $default['de_point_percent']) ?>P</span>
                    </div>
                    <div style="color: #a3a3a3; text-align: left; font-weight: normal; font-size: 14px;">(구매확정 후 즉시지급)</div>
                </td>
            </tr>
            <tr>
                <td colspan=7 style="background-color: #f2f2f2; border-bottom: 1px solid #f2f2f2; font-size: 18px; font-weight: 500; text-align: left; padding: 0 28px; height: 60px; border-bottom: 1px solid #333333;">
                    <span style="width: 210px; display: inline-block;">결제수단</span>
                    <span><?= ($easy_pay_name ? $easy_pay_name . '(' . $od['od_settle_case'] . ')' : check_pay_name_replace($od['od_settle_case'])) . "(" . $od['od_bank_account'] . ")"; ?></span>
                    <span style="font-size: 16px; margin-left: 8px;">(<?= date("Y.m.d H:i", strtotime($od['od_receipt_time'])) ?>)</span>
                    <span>
                        <? if ($disp_receipt) { ?>
                        <?
                            if ($od['od_settle_case'] == '휴대전화') {
                                $LGD_TID      = $od['od_tno'];
                                //$LGD_MERTKEY  = $config['cf_lg_mert_key'];
                                $LGD_HASHDATA = md5($LGD_MID . $LGD_TID . $LGD_MERTKEY);
                                $hp_receipt_script = 'showReceiptByTID(\'' . $LGD_MID . '\', \'' . $LGD_TID . '\', \'' . $LGD_HASHDATA . '\');';
                            ?>
                        <button class="btn gray_line small btn-member btn-white" type="button" style="font-size:16px; margin-left: 8px;" onclick="<?= $hp_receipt_script; ?>">영수증 발급</button>
                        <? } ?>

                        <?
                            if ($od['od_settle_case'] == '신용카드' || is_inicis_order_pay($od['od_settle_case'])) {
                                $LGD_TID      = $od['od_tno'];
                                $LGD_HASHDATA = md5($LGD_MID . $LGD_TID . $LGD_MERTKEY);
                                // $card_receipt_script = "showReceiptByTID(\'' . $LGD_MID . '\', \'' . $LGD_TID . '\', \'' . $LGD_HASHDATA . '\');";
                                $card_receipt_script = "showReceiptByTID('{$LGD_MID}','{$LGD_TID}','{$LGD_HASHDATA}');";
                            ?>
                        <button class="btn gray_line small btn-member btn-white" type="button" style="font-size:16px; margin-left: 8px;" onclick="<?= $card_receipt_script; ?>">영수증 발급</button>
                        <? } ?>

                        <?
                            if ($od['od_settle_case'] == 'KAKAOPAY') {
                                $card_receipt_script = 'window.open(\'https://mms.cnspay.co.kr/trans/retrieveIssueLoader.do?TID=' . $od['od_tno'] . '&type=0\', \'popupIssue\', \'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=420,height=540\');';
                            ?>
                        <button class="btn gray_line small btn-member btn-white" type="button" style="font-size:16px; margin-left: 8px;" onclick="<?= $card_receipt_script; ?>">영수증 발급</button>
                        <? } ?>
                        <? } ?>
                    </span>
                </td>
            </tr>
        </table>
    </div>


    <!-- 환불정보 -->
    <? if ($od['od_refund_price'] > 0 || $od['od_cancel_price'] > 0) : ?>
    <div class="member-content-title on-big">환불정보</div>
        <?
            $cancel_sql = "SELECT oi.*,si.* FROM lt_shop_order_item AS oi LEFT JOIN lt_shop_item AS si ON oi.it_id = si.it_id  WHERE oi.od_id = '".$od['od_id']."'  AND oi.ct_status='주문취소'";
            $cancel_result = sql_query($cancel_sql);

            while (false != ($cancel_item = sql_fetch_array($cancel_result))) {
                $cancel_item_price +=  $cancel_item['ct_price'];
                $io_sap_price += $cancel_item['io_sap_price'];
                $it_discount_price += $cancel_item['it_discount_price'];
                
            }

        ?>
    <div class="member-content-section">
        <div class="on-big" style="border-top: 3px solid #333333;"></div>
        <table class="on-big">
            <tr>
            <td class="member-order-view-table-wide">
                <!-- <div style="font-size: 18px; font-weight: 500; color: #656565;">총 상품금액</div>
                <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;" ><?= number_format($total_item_price) ?></div> -->
                <div style="font-size: 18px; font-weight: 500; color: #656565;">총 상품금액</div>
                <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;" ><?= number_format($total_item_price) ?></div>
                <!-- <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;" ><?= number_format($io_sap_price) ?></div> -->
            </td>
            <td class="member-order-view-table-narrow">
                <span class="icon-cart-calc calc-minus"></span>
            </td>
            <td class="member-order-view-table-wide">
                <!-- <div style="font-size: 18px; font-weight: 500; color: #656565;">총 할인금액</div>
                <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($total_order_price + $od['od_coupon'] + $od['od_cart_coupon'] + $od['od_send_coupon'] + $od['od_receipt_point']) ?></div> -->
                <div style="font-size: 18px; font-weight: 500; color: #656565;">총 할인금액</div>
                <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($total_order_price + $od['od_coupon_cancel'] + $od['od_cart_coupon_cancel'] + $od['od_send_coupon_cancel'] + $od['od_receipt_point_cancel']) ?></div>
                <!-- <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($it_discount_price + $od['od_coupon'] + $od['od_cart_coupon'] + $od['od_send_coupon'] + $od['od_receipt_point']) ?></div> -->
            </td>
            <td class="member-order-view-table-narrow">
                <span class="icon-cart-calc calc-plus"></span>
            </td>
            <td class="member-order-view-table-wide">
                <div style="font-size: 18px; font-weight: 500; color: #656565;">총 배송비</div>
                <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($od['od_send_cost']) ?></div>
            </td>
            <td class="member-order-view-table-narrow">
                <span class="icon-cart-calc calc-minus"></span>
            </td>
            <td class="member-order-view-table-wide">
                <div style="font-size: 18px; font-weight: 500; color: #656565;">환불 차감 금액</div>
                <? if ($od['od_status'] != '반품요청' ) : ?>
                    <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;">0</div>
                <? elseif ($od['od_cancel_price'] > 0) : ?>
                    <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format(($total_item_price - $total_order_price - $od['od_coupon_cancel'] - $od['od_cart_coupon_cancel'] - $od['od_send_coupon_cancel'] - $od['od_receipt_point_cancel'] + $od['od_send_cost']) - $od['od_cancel_price']) ?></div>
                <? else: ?>
                    <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format(($total_item_price - $total_order_price - $od['od_coupon'] - $od['od_cart_coupon'] - $od['od_send_coupon'] - $od['od_receipt_point'] + $od['od_send_cost']) - $od['od_refund_price']) ?></div>
                <? endif ?>
                <!-- <? if ($od['od_cancel_price'] > 0) : ?>
                    <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format(($io_sap_price - $it_discount_price - $od['od_coupon'] - $od['od_cart_coupon'] - $od['od_send_coupon'] - $od['od_receipt_point'] + $od['od_send_cost']) - $cancel_item_price) ?></div>
                <? else: ?>
                    <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format(($io_sap_price - $it_discount_price - $od['od_coupon'] - $od['od_cart_coupon'] - $od['od_send_coupon'] - $od['od_receipt_point'] + $od['od_send_cost']) - $od['od_refund_price']) ?></div>
                <? endif ?> -->
            </td>
            <td class="member-order-view-table-narrow">
                <span class="icon-cart-calc calc-eq"></span>
            </td>
            <td class="member-order-view-table-wide">
                <? if ($od['od_cancel_price'] > 0) : ?>
                    <div style="font-size: 18px; font-weight: 500; color: #656565;">결제 취소 금액</div>
                    <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($od['od_cancel_price']) ?></div>
                <? else: ?>
                    <div style="font-size: 18px; font-weight: 500; color: #656565;">환불 금액</div>
                    <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($od['od_refund_price']) ?></div>
                <? endif ?>
            </td>

                <!-- <td class="member-order-view-table-wide">
                    <div style="font-size: 18px; font-weight: 500; color: #656565;">총 상품금액</div>
                    <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($total_item_price) ?></div>
                </td>
                <td class="member-order-view-table-narrow">
                    <span class="icon-cart-calc calc-minus"></span>
                </td>
                <td class="member-order-view-table-wide">
                    <div style="font-size: 18px; font-weight: 500; color: #656565;">할인 취소 금액</div>
                    <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($total_order_price + $od['od_coupon'] + $od['od_cart_coupon'] + $od['od_send_coupon'] + $od['od_receipt_point']) ?></div>
                </td>
                <td class="member-order-view-table-narrow">
                    <span class="icon-cart-calc calc-plus"></span>
                </td>
                <td class="member-order-view-table-wide">
                    <div style="font-size: 18px; font-weight: 500; color: #656565;">환불 차감 금액</div>
                    <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($od['od_send_cost']) ?></div>
                </td>
                <td class="member-order-view-table-narrow">
                    <span class="icon-cart-calc calc-eq"></span>
                </td>
                <td class="member-order-view-table-wide">
                    <? if ($od['od_cancel_price'] > 0) : ?>
                    <div style="font-size: 18px; font-weight: 500; color: #656565;">결제 취소 금액</div>
                    <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($od['od_cancel_price']) ?></div>
                    <? else: ?>
                    <div style="font-size: 18px; font-weight: 500; color: #656565;">환불 금액</div>
                    <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($od['od_refund_price']) ?></div>
                    <? endif ?>
                </td> -->
            </tr>
                    

            <tr>
                <td class="member-order-view-table-wide">
                </td>
                <td class="member-order-view-table-narrow"><span style="display: inline-block; border-left: 1px solid #333333;; height: 100%; margin-top: 5px;"></span></td>
                <td class="member-order-view-table-wide" style="vertical-align: top; padding-top: 20px !important;">
                    <? if ($total_item_price > $total_order_price) : ?>
                    <div style="display: flex; justify-content: space-between; padding-bottom: 20px;">
                        <span style="padding-top: 2px;">즉시할인</span>
                        <!-- <span style="font-size: 18px; font-weight: normal;"><?= number_format($total_order_price) ?>원</span> -->
                        <span style="font-size: 18px; font-weight: normal;"><?= number_format($it_discount_price) ?>원</span>
                    </div>
                    <? endif ?>
                    <? if (($od['od_coupon_cancel'] + $od['od_cart_coupon_cancel']) > 0) : ?>
                    <div style="display: flex; justify-content: space-between; padding-bottom: 20px;">
                        <span style="padding-top: 2px;">쿠폰할인</span>
                        <span style="font-size: 18px; font-weight: normal;"><?= number_format($od['od_coupon_cancel'] + $od['od_cart_coupon_cancel']) ?>원</span>
                    </div>
                    <? endif ?>
                    <? if ($od['od_receipt_point_cancel'] > 0) : ?>
                    <div style="display: flex; justify-content: space-between; padding-bottom: 20px;">
                        <span style="padding-top: 2px;">포인트사용</span>
                        <span style="font-size: 18px; font-weight: normal;"><?= number_format($od['od_receipt_point_cancel']) ?>P</span>
                    </div>
                    <? endif ?>
                    <? if ($od['od_send_coupon_cancel'] > 0) : ?>
                    <div style="display: flex; justify-content: space-between; padding-bottom: 20px;">
                        <span style="padding-top: 2px;">배송비쿠폰</span>
                        <span style="font-size: 18px; font-weight: normal;"><?= number_format($od['od_send_coupon_cancel']) ?>원</span>
                    </div>
                    <? endif ?>
                </td>
                <td class="member-order-view-table-narrow"><span style="display: inline-block; border-left: 1px solid #333333;; height: 100%; margin-top: 5px;"></span></td>
                <td class="member-order-view-table-wide" style="vertical-align: top; padding-top: 20px !important;">

                </td>
                <td class="member-order-view-table-narrow"><span style="display: inline-block; border-left: 1px solid #333333;; height: 100%; margin-top: 5px;"></span></td>
                <td class="member-order-view-table-wide" style="vertical-align: top; padding-top: 20px !important; padding-right: 20px !important;">

                </td>
                <td class="member-order-view-table-narrow"><span style="display: inline-block; border-left: 1px solid #333333;; height: 100%; margin-top: 5px;"></span></td>
                <td class="member-order-view-table-wide" style="vertical-align: top; padding-top: 20px !important; padding-right: 20px !important;">

                </td>
            </tr>
            <tr>
                <td colspan=9 style="background-color: #f2f2f2; border-bottom: 1px solid #f2f2f2; font-size: 18px; font-weight: 500; text-align: left; padding: 0 28px; height: 60px; border-bottom: 1px solid #333333;">
                    <span style="width: 210px; display: inline-block;">환불결제수단</span>
                    <span><?= ($easy_pay_name ? $easy_pay_name . '(' . $od['od_settle_case'] . ')' : check_pay_name_replace($od['od_settle_case'])) . "(" . $od['od_bank_account'] . ")"; ?></span>
                    <!-- <span style="font-size: 16px; margin-left: 8px;">(<?= date("Y.m.d H:i", strtotime($od['od_status_claim_date'])) ?>)</span> -->
                </td>
            </tr>
        </table>
    </div>
    <? endif ?>

    <!-- 배송정보 -->
    <div class="member-content-title on-big" style="padding-bottom: 8px;">
        <div class="order-info-title" style="display: flex; justify-content: space-between;">
            <span style="padding-top: 8px;">배송정보</span>
            <? if (in_array($od['od_status'], array("주문", "결제완료"))) : ?>
            <span>
                <button type="button" class="btn-member btn-white change-address">배송지변경</button>
            </span>
            <? endif ?>
        </div>
    </div>
    <div class="on-big" style="border-top: 3px solid #333333;"></div>
    <div class="member-content-section on-big" id="order-delivery-info">
        <table id="table-order-delivery-info">
            <tr>
                <td style="vertical-align: top; height: auto; padding: 10px 0; padding-top: 20px; text-align: left; border-bottom: unset; width: 120px; font-size: 14px; font-weight: 500;">수령인</td>
                <td style="vertical-align: top; height: auto; padding: 10px 0; padding-top: 20px; text-align: left; border-bottom: unset; font-size: 16px; font-weight: normal;"><?= $od['od_b_name'] ?></td>
            </tr>
            <tr>
                <td style="vertical-align: top; height: auto; padding: 10px 0; text-align: left; border-bottom: unset; width: 120px; font-size: 14px; font-weight: 500;">연락처</td>
                <td style="vertical-align: top; height: auto; padding: 10px 0; text-align: left; border-bottom: unset; font-size: 16px; font-weight: normal;"><?= $od['od_b_hp'] ?></td>
            </tr>
            <tr>
                <td style="vertical-align: top; height: auto; padding: 10px 0; text-align: left; border-bottom: unset; width: 120px; font-size: 14px; font-weight: 500;">배송지</td>
                <td style="vertical-align: top; height: auto; padding: 10px 0; text-align: left; border-bottom: unset; font-size: 16px; font-weight: normal;">
                    (<?= $od['od_b_zip1'] . $od['od_b_zip2']  ?>)<br>
                    <?= $od['od_b_addr1'] . " " . $od['od_b_addr2']  ?>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top; height: auto; padding: 10px 0; text-align: left; border-bottom: unset; width: 120px; font-size: 14px; font-weight: 500;">배송 메세지</td>
                <td style="vertical-align: top; height: auto; padding: 10px 0; text-align: left; border-bottom: unset; font-size: 16px; font-weight: normal;"><?= $od['od_memo'] ?></td>
            </tr>
        </table>
        <form name="formOrderDelivery" method="POST">
            <input type="hidden" name="od_b_addr3" value="true">
            <input type="hidden" name="od_b_addr_jibeon" value="true">
            <table id="table-change-delivery-info" style="width: 100%;">
                <tr>
                    <td style="width: 120px;">수령인<span class="point-require">*</span></td>
                    <td style="width: 1060px">
                        <input type="text" name="od_b_name" value="<?= $od['od_b_name'] ?>" placeholder="수령인" style="width: 340px;">
                    </td>
                </tr>
                <tr>
                    <td>휴대폰번호<span class="point-require">*</span></td>
                    <td style="font-size: 0;">
                        <select name="od_b_hp_1" id="od_b_hp_1" style="width: 100px; margin-right: 20px;">
                            <option value="010" <?= get_selected(substr($od['od_b_hp'], 0, 3), "010") ?>>010</option>
                            <option value="011" <?= get_selected(substr($od['od_b_hp'], 0, 3), "011") ?>>011</option>
                            <option value="016" <?= get_selected(substr($od['od_b_hp'], 0, 3), "016") ?>>016</option>
                            <option value="017" <?= get_selected(substr($od['od_b_hp'], 0, 3), "017") ?>>017</option>
                            <option value="018" <?= get_selected(substr($od['od_b_hp'], 0, 3), "018") ?>>018</option>
                            <option value="019" <?= get_selected(substr($od['od_b_hp'], 0, 3), "019") ?>>019</option>
                        </select>
                        <input type="number" name="od_b_hp_2" value="<?= substr($od['od_b_hp'], 3) ?>" placeholder="휴대폰번호 입력" style="width: 220px;">
                    </td>
                </tr>
                <tr>
                    <td>배송지<span class="point-require">*</span></td>
                    <td>
                        <input type="text" name="od_b_zip" value="<?= $od['od_b_zip1'] . $od['od_b_zip2'] ?>" placeholder="우편번호" onclick="win_zip('formOrderDelivery','od_b_zip' , 'od_b_addr1', 'od_b_addr2', 'od_b_addr3','od_b_addr_jibeon');" readonly aria-describedby="btn-mb-zip" value="<?= $od['od_b_zip'] ?>" style="width: 340px;">
                        <button class="btn-member btn-black-2" type="button" style="margin-top: 0; vertical-align: top; margin-left: 14px;" onclick="win_zip('formOrderDelivery','od_b_zip' , 'od_b_addr1', 'od_b_addr2', 'od_b_addr3','od_b_addr_jibeon');">우편번호</button>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="text" name="od_b_addr1" id="od_b_addr1" value="<?= $od['od_b_addr1'] ?>" placeholder="기본주소" style="width: 100%;" onclick="win_zip('formOrderDelivery','od_b_zip' , 'od_b_addr1', 'od_b_addr2', 'od_b_addr3','od_b_addr_jibeon');" readonly>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="text" name="od_b_addr2" id="od_b_addr2" value="<?= $od['od_b_addr2'] ?>" placeholder="상세주소" style="width: 100%;">
                    </td>
                </tr>
                <tr>
                    <td>배송 메세지</td>
                    <td>
                        <select class="select-order-memo" name="od_memo" placeholder="배송 메세지를 선택해주세요" style="width: 100%;">
                            <option>배송 메세지를 선택해주세요</option>
                            <option value="배송 전 전화 혹은 문자 남겨주세요">배송 전 전화 혹은 문자 남겨주세요</option>
                            <option value="부재 시 전화 혹은 문자 남겨주세요">부재 시 전화 혹은 문자 남겨주세요</option>
                            <option value="부재 시 경비실에 맡겨주세요">부재 시 경비실에 맡겨주세요</option>
                            <option value="부재 시 무인택배함에 넣어주세요">부재 시 무인택배함에 넣어주세요</option>
                            <option value="부재 시 문앞에 놔주세요">부재 시 문앞에 놔주세요</option>
                            <option value="user">직접입력</option>
                        </select>
                        <input style="width: 100%; margin-top: 8px;" class="select-order-memo-user" type="text" name="od_memo_user" placeholder="50자 이내로 입력해주세요." value="<?= $od['od_memo'] ?>">
                    </td>
                </tr>
                <tr>
                    <td colspan=2 style="text-align: right;">
                        <button type="button" class="btn-member btn-white change-address">취소</button>
                        <button type="submit" class="btn-member">저장</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <!-- 배송정보 끝-->

    <!-- 모바일 -->
    <div class="member-content-section on-small">
        <div class="member-content-title" style="border-top: 10px solid #f2f2f2; border-bottom: unset !important; padding: 17px 14px 20px 14px;">결제정보</div>
        <table>
            <tr>
                <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">총 상품금액</td>
                <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;"><?= number_format($total_item_price) ?>원</td>
            </tr>
            <? if ($total_item_price > $total_order_price) : ?>
            <tr>
                <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">즉시할인</td>
                <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;"><?= number_format($total_order_price) ?>원</td>
            </tr>
            <? endif ?>
            <? if (($od['od_coupon_cancel'] + $od['od_cart_coupon_cancel']) > 0) : ?>
            <tr>
                <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">쿠폰 할인</td>
                <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;"><?= number_format($od['od_coupon_cancel'] + $od['od_cart_coupon_cancel']) ?>원</td>
            </tr>
            <? endif ?>
            <tr>
                <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">총 배송비</td>
                <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;"><?= number_format($od['od_send_cost']) ?>원</td>
            </tr>
            <? if ($od['od_receipt_point'] > 0) : ?>
            <tr>
                <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">포인트 사용</td>
                <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;"><?= number_format($od['od_receipt_point']) ?>P</td>
            </tr>
            <? endif ?>
            <tr>
                <td style="border-bottom: unset; border-top: 1px solid #e0e0e0; height: auto; padding-left: 14px; font-size: 16px; font-weight: 500; color: #3a3a3a;">총 결제금액</td>
                <td style="border-bottom: unset; border-top: 1px solid #e0e0e0; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;"><?= number_format($total_item_price - $total_order_price - $od['od_coupon_cancel'] - $od['od_cart_coupon_cancel'] - $od['od_send_coupon_cancel'] - $od['od_receipt_point_cancel'] + $od['od_send_cost']) ?>원</td>
                <!-- <div style='font-size: 15px; font-weight: 500; margin-top:67px'><?= number_format($cart_item['ct_price'] * $cart_item['ct_qty'] - $order['od_coupon'] - $order['od_cart_coupon'] - $order['od_send_coupon'] - $order['od_receipt_point'] + $order['od_send_cost']) ?><span>원</span></div> -->
            </tr>
            <tr>
                <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #f93f00;">적립예정포인트</td>
                <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #f93f00; font-weight: 500; text-align: right;"><?= number_format(($od['od_cart_price'] - $od['od_coupon'] - $od['od_cart_coupon'] - $od['od_receipt_point']) / 100 * $default['de_point_percent']) ?>P</td>
            </tr>
        </table>

        <!-- <div class="member-content-title" style="border-top: 10px solid #f2f2f2; border-bottom: unset !important; padding: 17px 14px 20px 14px; display: flex; justify-content: space-between;">
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
                <button class="btn-member btn-white btn-xs" type="button" onclick="<?= $hp_receipt_script; ?>">영수증 발급</button>
                <? } ?>

                <?
                    if ($od['od_settle_case'] == '신용카드' || is_inicis_order_pay($od['od_settle_case'])) {
                        $LGD_TID      = $od['od_tno'];
                        $LGD_HASHDATA = md5($LGD_MID . $LGD_TID . $LGD_MERTKEY);
                        // $card_receipt_script = "showReceiptByTID(\'' . $LGD_MID . '\', \'' . $LGD_TID . '\', \'' . $LGD_HASHDATA . '\');";
                        $card_receipt_script = "showReceiptByTID('{$LGD_MID}','{$LGD_TID}','{$LGD_HASHDATA}');";
                    ?>
                <button class="btn-member btn-white btn-xs" type="button" onclick="<?= $card_receipt_script; ?>">영수증 발급</button>
                <? } ?>

                <?
                    if ($od['od_settle_case'] == 'KAKAOPAY') {
                        $card_receipt_script = 'window.open(\'https://mms.cnspay.co.kr/trans/retrieveIssueLoader.do?TID=' . $od['od_tno'] . '&type=0\', \'popupIssue\', \'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=420,height=540\');';
                    ?>
                <button class="btn-member btn-white btn-xs" type="button" onclick="<?= $card_receipt_script; ?>">영수증 발급</button>
                <? } ?>
                <? } ?>
            </span>
        </div> -->
        <!-- <table>
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
        <? if ($od['od_refund_price'] > 0 || $od['od_cancel_price'] > 0) : ?>
        <div class="member-content-title" style="border-top: 10px solid #f2f2f2; border-bottom: unset !important; padding: 17px 14px 20px 14px;">환불정보</div>
        
            <table>
                <tr>
                    <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">총 상품금액</td>
                    <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;"><?= number_format($total_item_price) ?>원</td>
                </tr>
                <tr>
                    <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">총 할인금액</td>
                    <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;"><?= number_format($total_order_price +$od['od_coupon_cancel'] + $od['od_cart_coupon_cancel'] + $od['od_receipt_point_cancel']) ?>원</td>
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
                <tr>
                    <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 8px; color: #3a3a3a;">배송비쿠폰</td>
                    <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 10px; color: #333333; font-weight: 500; text-align: right;"><?= number_format($od['od_send_coupon']) ?>원</td>
                </tr>
                <? endif ?>

                <!-- <tr>
                    <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">쿠폰 할인</td>
                    <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;" id="preview-discount-coupon-mobile">0원</td>
                </tr> -->
                <tr>
                    <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">총 배송비</td>
                    <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;"><?= number_format($od['od_send_cost']) ?>원</td>
                </tr>
                <!-- <tr>
                    <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">포인트 사용</td>
                    <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;" id="preview-point-mobile">0P</td>
                </tr> -->
                <tr>
                    <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #3a3a3a;">환불 차감 금액</td>
                    <? if ($od['od_status'] != '반품요청' ) : ?>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;">0원</td>
                    <? elseif ($od['od_cancel_price'] > 0) : ?>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;"><?= number_format(($total_item_price - $total_order_price - $od['od_coupon_cancel'] - $od['od_cart_coupon_cancel'] - $od['od_send_coupon_cancel'] - $od['od_receipt_point_cancel'] + $od['od_send_cost']) - $od['od_cancel_price']) ?>원</td>
                    <? else: ?>
                        <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;"><?= number_format(($total_item_price - $total_order_price - $od['od_coupon'] - $od['od_cart_coupon'] - $od['od_send_coupon'] - $od['od_receipt_point'] + $od['od_send_cost']) - $od['od_refund_price']) ?>원</td>
                    <? endif ?>
                    <!-- <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;" id="preview-cancel-cost-mobile">0원</td> -->
                </tr>
                
                <!-- <tr id="preview-cancelCheck" style="display: none;">
                    <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 8px; color: #3a3a3a;">반품배송비</td>
                    <td id="preview-cancel-cost-mobile2" style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 10px; color: #333333; font-weight: 500; text-align: right">0원</td>
                </tr> -->
        
            
                <tr>

                    <!-- <td style="border-bottom: unset; border-top: 1px solid #e0e0e0; height: auto; padding-left: 14px; font-size: 16px; font-weight: 500; color: #3a3a3a;">환불 예정 금액</td> -->

                    <? if ($od['od_cancel_price'] > 0) : ?>
                        <td style="border-bottom: unset; border-top: 1px solid #e0e0e0; height: auto; padding-left: 14px; font-size: 16px; font-weight: 500; color: #3a3a3a;">결제 취소 금액</td>
                        <td style="border-bottom: unset; border-top: 1px solid #e0e0e0; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;"><?= number_format($od['od_cancel_price']) ?>원</td>
                        <!-- <div style="font-size: 18px; font-weight: 500; color: #656565;">결제 취소 금액</div> -->
                        <!-- <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($od['od_cancel_price']) ?></div> -->
                    <? else: ?>
                        <td style="border-bottom: unset; border-top: 1px solid #e0e0e0; height: auto; padding-left: 14px; font-size: 16px; font-weight: 500; color: #3a3a3a;">환불 금액</td>
                        <td style="border-bottom: unset; border-top: 1px solid #e0e0e0; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;"><?= number_format($od['od_refund_price']) ?>원</td>
                        <!-- <div style="font-size: 18px; font-weight: 500; color: #656565;">환불 예정 금액</div> -->
                        <!-- <div style="font-size: 24px; font-weight: 600; color: #333333; padding-top: 16px;"><?= number_format($od['od_refund_price']) ?></div> -->
                    <? endif ?>




                    <!-- <td style="border-bottom: unset; border-top: 1px solid #e0e0e0; height: auto; padding-right: 14px; font-size: 14px; color: #333333; font-weight: 500; text-align: right;" id="preview-cancel-refund-mobile">0원</td> -->
                </tr>
                <!-- <tr>
                    <td style="border-bottom: unset; height: auto; padding-left: 14px; font-size: 12px; color: #f93f00;">환불예정포인트</td>
                    <td style="border-bottom: unset; height: auto; padding-right: 14px; font-size: 14px; color: #f93f00; font-weight: 500; text-align: right;" id="preview-refund-point-mobile">0P</td>
                </tr> -->
            </table>
        
        <? endif ?>

        <div class="member-content-title" style="border-top: 10px solid #f2f2f2; border-bottom: unset !important; padding: 17px 14px 20px 14px; display: flex; justify-content: space-between;">
            <span>배송정보</span>
            <? if (in_array($od['od_status'], array("주문", "결제완료"))) : ?>
            <span>
                <button id="change-invoice-mobile" class="btn-member btn-white btn-xs" type="button">배송지변경</button>
            </span>
            <? endif ?>
        </div>
        <div style="margin-bottom: 10px; padding-left: 14px; font-size: 12px; color: #3a3a3a;"><?= $od['od_b_name'] ?></div>
        <div style="margin-bottom: 10px; padding-left: 14px; font-size: 12px; color: #3a3a3a;"><?= $od['od_b_hp'] ?></div>
        <div style="margin-bottom: 10px; padding-left: 14px; font-size: 12px; color: #3a3a3a;">(<?= $od['od_b_zip1'] . $od['od_b_zip2']  ?>) <?= $od['od_b_addr1'] . " " . $od['od_b_addr2']  ?></div>
        <div style="margin-bottom: 10px; padding-left: 14px; font-size: 12px; color: #3a3a3a;"><?= $od['od_memo'] ?></div>

        <div class="member-content-title" style="border-top: 10px solid #f2f2f2; border-bottom: unset !important; padding: 17px 14px 20px 14px; text-align: center;">
            <button class="btn-member btn-white" onclick="history.back();">목록</button>
        </div>
    </div>



    <!-- OLD

    <div class="member-order-sub-info-wapper">
        <div class="member-order-sub-info">
            <div class="member-content-title">
                주문자 정보
            </div>
            <div>
                <table>
                    <colgroup>
                        <col style="width: 120px">
                        <col>
                    </colgroup>
                    <tr>
                        <td>주문하신분</td>
                        <td><?= get_text($od['od_name']); ?> 님</td>
                    </tr>
                    <tr>
                        <td>이메일 주소</td>
                        <td><?= get_text($od['od_email']); ?></td>
                    </tr>
                    <tr>
                        <td>연락처</td>
                        <td><?= get_text($od['od_hp']); ?></td>
                    </tr>
                </table>
            </div>


            <div class="member-content-title on-small">배송 정보</div>
            <div class="member-content-title on-big" style="margin-top: 64px;">배송 정보</div>
            <div>
                <table>
                    <colgroup>
                        <col style="width: 120px">
                        <col>
                    </colgroup>
                    <tr>
                        <td>수령인</td>
                        <td><?= get_text($od['od_b_name']); ?> 님</td>
                    </tr>
                    <tr>
                        <td>연락처</td>
                        <td><?= get_text($od['od_b_hp']); ?></td>
                    </tr>
                    <tr>
                        <td>주소</td>
                        <td><?= get_text(sprintf("(%s%s)", $od['od_b_zip1'], $od['od_b_zip2']) . ' ' . print_address($od['od_b_addr1'], $od['od_b_addr2'], $od['od_b_addr3'], $od['od_b_addr_jibeon'])); ?></td>
                    </tr>
                    <tr>
                        <td>배송요청사항</td>
                        <td><?= conv_content($od['od_memo'], 0); ?></td>
                    </tr>
                </table>
                <div class="order-description">
                    - 상품이 품절되는 경우 주문이 자동으로 취소되며 주문자의 SMS와 이메일로 안내가 진행됩니다.<br>
                    - 침구류 특성 상 주문 후 제작이 시작되는 경우 배송일이 7-14일 소요될 수 있습니다.<br>
                    &nbsp;&nbsp;관련 문의는 상품 Q&A 또는 고객센터를 이용하시길 바랍니다.
                </div>
            </div>
        </div>
        <div class="member-order-sub-info member-order-sub-payment-info">
            <div class="member-content-title">
                결제 정보
            </div>
            <div>
                <table>
                    <colgroup>
                        <col style="width: 120px">
                        <col>
                    </colgroup>

                    <tr>
                        <td>결제 방법</th>
                        <td>
                            <?= ($easy_pay_name ? $easy_pay_name . '(' . $od['od_settle_case'] . ')' : check_pay_name_replace($od['od_settle_case'])); ?>
                            <? if ($disp_receipt) { ?>
                                <?
                                if ($od['od_settle_case'] == '휴대전화') {
                                    $LGD_TID      = $od['od_tno'];
                                    //$LGD_MERTKEY  = $config['cf_lg_mert_key'];
                                    $LGD_HASHDATA = md5($LGD_MID . $LGD_TID . $LGD_MERTKEY);
                                    $hp_receipt_script = 'showReceiptByTID(\'' . $LGD_MID . '\', \'' . $LGD_TID . '\', \'' . $LGD_HASHDATA . '\');';
                                ?>
                                    <button class="btn gray_line small btn_invoice2" type="button" style="font-size:12px;vertical-align:middle;" onclick="<?= $hp_receipt_script; ?>">영수증 출력</button>
                                <? } ?>

                                <?
                                if ($od['od_settle_case'] == '신용카드' || is_inicis_order_pay($od['od_settle_case'])) {
                                    $LGD_TID      = $od['od_tno'];
                                    $LGD_HASHDATA = md5($LGD_MID . $LGD_TID . $LGD_MERTKEY);
                                    $card_receipt_script = "showReceiptByTID(\'' . $LGD_MID . '\', \'' . $LGD_TID . '\', \'' . $LGD_HASHDATA . '\');";
                                    $card_receipt_script = "showReceiptByTID('{$LGD_MID}','{$LGD_TID}','{$LGD_HASHDATA}');";
                                ?>
                                    <button class="btn gray_line small btn_invoice2" type="button" style="font-size:12px;vertical-align:middle;" onclick="<?= $card_receipt_script; ?>">영수증 출력</button>
                                <? } ?>

                                <?
                                if ($od['od_settle_case'] == 'KAKAOPAY') {
                                    $card_receipt_script = 'window.open(\'https://mms.cnspay.co.kr/trans/retrieveIssueLoader.do?TID=' . $od['od_tno'] . '&type=0\', \'popupIssue\', \'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=420,height=540\');';
                                ?>
                                    <button class="btn gray_line small btn_invoice2" type="button" style="font-size:12px;vertical-align:middle;" onclick="<?= $card_receipt_script; ?>">영수증 출력</button>
                                <? } ?>
                            <? } ?>
                        </td>
                    </tr>

                    <? if ($disp_bank) { ?>
                        <tr>
                            <td>입금자명</th>
                            <td><?= get_text($od['od_deposit_name']); ?></td>
                        </tr>
                        <tr>
                            <td>입금계좌</th>
                            <td><?= get_text($od['od_bank_account']); ?></td>
                        </tr>
                    <? } ?>

                    <? if ($default['de_taxsave_use']) { ?>
                        <? if ($misu_price == 0 && $od['od_receipt_price'] && ($od['od_settle_case'] == '무통장' || $od['od_settle_case'] == '계좌이체' || $od['od_settle_case'] == '가상계좌')) { ?>
                            <tr>
                                <td>현금영수증</th>
                                <td>
                                    <?
                                    if ($od['od_cash']) {
                                        switch ($od['od_settle_case']) {
                                            case '계좌이체':
                                                $trade_type = 'BANK';
                                                break;
                                            case '가상계좌':
                                                $trade_type = 'CAS';
                                                break;
                                            default:
                                                $trade_type = 'CR';
                                                break;
                                        }
                                        $cash_receipt_script = 'javascript:showCashReceipts(\'' . $LGD_MID . '\',\'' . $od['od_id'] . '\',\'' . $od['od_casseqno'] . '\',\'' . $trade_type . '\',\'' . $CST_PLATFORM . '\');';
                                    ?>
                                        <a href="javascript:;" onclick="<?= $cash_receipt_script; ?>" class="btn gray_trne small">현금영수증 확인하기</a>
                                    <? } else { ?>
                                        <a href="javascript:;" onclick="window.open('<?= G5_SHOP_URL; ?>/taxsave.php?od_id=<?= $od_id; ?>', 'taxsave', 'width=550,height=400,scrollbars=1,menus=0');" class="btn gray_trne small">현금영수증을 발급</a>
                                    <? } ?>
                                </td>
                            </tr>
                        <? } ?>
                    <? } ?>
                    <tr>
                        <td>총 결제 금액</th>
                        <td><?= number_format(($od['od_cart_price'] + $od['od_send_cost']) - ($od['od_receipt_point'] + $od['od_cart_coupon'] + $od['od_coupon'])); ?> 원</td>
                    </tr>
                    <? if ($od['od_coupon'] > 0) { ?>
                        <tr>
                            <td>쿠폰 사용 금액</td>
                            <td><?= number_format($od['od_coupon']); ?>원</td>
                        </tr>
                    <? } ?>
                    <? if ($od['od_receipt_point'] > 0) { ?>
                        <tr>
                            <td>포인트 사용 금액</td>
                            <td><?= display_point($od['od_receipt_point']) ?></td>
                        </tr>
                    <? } ?>
                    <? if ($od['od_cancel_price'] > 0) { ?>
                        <tr>
                            <td>취소 금액</td>
                            <td><?= number_format($od['od_cancel_price']); ?>원</td>
                        </tr>
                    <? } ?>
                    <? if ($od['od_refund_price'] > 0) { ?>
                        <tr>
                            <td>환불 금액</td>
                            <td><?= number_format($od['od_refund_price']); ?>원</td>
                        </tr>
                    <? } ?>
                    <? if ($od['od_send_cost2'] > 0) { ?>
                        <tr>
                            <td>반품 배송비</td>
                            <td><?= number_format($od['od_send_cost2']); ?>원</td>
                        </tr>
                    <? } ?>
                    <tr>
                        <td>주문접수일시</td>
                        <td><?= $od['od_time']; ?></td>
                    </tr>
                    <tr>
                        <td>결제완료일시</td>
                        <td><?= $od['od_receipt_time']; ?></td>
                    </tr>
                </table>
                <div class="order-description">
                    - 카드사별 이벤트, 포인트 사용 등에 따라 실제 결제금액과 표기된 총 결제금액에 차이가 있을 수 있습니다.
                </div>
            </div>
        </div>
    </div>
    -->
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

?>
<script type="text/javascript">
    $(".change-address").on("click", function() {
        $("#order-delivery-info").toggleClass("change");
    });

    $(".select-order-memo").on("change", function() {
        if ($(this).find("option:selected").val() == "user") {
            $(".select-order-memo-user").addClass("active");
        } else {
            $(".select-order-memo-user").removeClass("active");
        }
    });

    $("#change-invoice-mobile").on("click", function() {
        return $("#modal-order-address-form-mobile").modal("show");
    });

    function writeQuestion(odid) {
        if (!odid) odid = 0;
        return window.location.href = '/member/customer.php?od_id=' + odid;
    }

    function writeReview(ctid) {
        if (!ctid) ctid = 0;
        return window.location.href = '/member/review.php?ct_id=' + ctid;
    }
</script>
<?
include_once G5_LAYOUT_PATH . "/modal.review.php";
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>