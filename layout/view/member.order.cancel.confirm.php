<?php
ob_start();
$g5_title = "주문취소";
include_once G5_LAYOUT_PATH . "/nav.member.php";
?>
<style>
    #table-cancel-amount {
        font-size: 16px;
        font-weight: 600;
        color: #7f7f7f;
        width: 100%;
    }

    #table-cancel-amount>tbody>tr:first-child>th,
    #table-cancel-amount>tbody>tr:first-child>td {
        font-size: 16px;
        font-weight: bold;
        color: #000000;
        line-height: 40px;
        border-bottom: 1px solid #e0e0e0;
    }

    #table-cancel-amount>tbody>tr:first-child>td {
        font-size: 20px;
    }

    #table-cancel-amount>tbody>tr>td {
        height: 40px;
        vertical-align: top;
    }

    @media (max-width: 1366px) {
        #member-content-wrapper {
            padding: 20px;
        }

        .member-order-detail-summary {
            height: auto;
        }

        .member-order-detail-summary td {
            font-size: 12px;
            line-height: 36px;
        }

        .member-content-title {
            font-size: 16px !important;
        }

        .member-content-desc {
            font-size: 12px !important;
        }
    }
</style>
<div id="member-content-wrapper">
    <div class="on-big">
        <div class="member-content-title">
            주문 취소
        </div>
        <div style="margin-top: 14px; margin-bottom: 80px; font-size: 16px;">
            카드주문 승인취소 : 접수일로부터 5영업일 이내 카드사 홈페이지에서 취소 내역 확인 가능<br>
            실시간 이체 취소 : 거래한 은행계좌로 5영업일 이내 입금
        </div>
        <div class="member-content-title">
            주문 취소 금액
        </div>
        <table id="table-cancel-amount">
            <colgroup>
                <col style="width: 140px">
                <col style="width: 160px">
                <col style="width: 180px">
                <col style="width: 140px">
                <col style="width: 160px">
                <col style="width: 160px">
                <col style="width: 160px">
                <col style="width: 160px">
            </colgroup>
            <tr>
                <th style="text-align: left;">결제 금액</th>
                <td style="text-align: right;"><?= number_format($od_last_price) ?>원</td>
                <td style="text-align: center;">-</td>
                <th style="text-align: left;">차감 금액</th>
                <td style="text-align: right;"><?= number_format($od_last_price - $tot_sell_price) ?>원</td>
                <td style="text-align: center;">=</td>
                <th style="text-align: left; color: #f04e00;">취소 예정 금액</th>
                <td style="text-align: right; color: #f04e00;"><?= number_format($tot_sell_price) ?>원</td>
            </tr>
            <tr>
                <th style="font-size: 14px; font-weight: normal; color: #7f7f7f">상품 금액</th>
                <td style="text-align: right;"><?= number_format($tot_sell_price) ?>원</td>
                <td colspan=6></td>
            </tr>
            <tr>
                <th style="font-size: 14px; font-weight: normal; color: #7f7f7f">배송비</th>
                <td style="text-align: right;"><?= number_format($tot_send_cost) ?>원</td>
                <td colspan=6></td>
            </tr>

            <? if ($od['od_cart_coupon'] > 0) : ?>
                <tr>
                    <th style="font-size: 14px; font-weight: normal; color: #7f7f7f">쿠폰할인(상품)</th>
                    <td style="text-align: right;">(-)<?= number_format($tot_send_cost) ?>원</td>
                    <td colspan=6></td>
                </tr>
            <? endif ?>
            <? if ($od['od_send_coupon'] > 0) : ?>
                <tr>
                    <th style="font-size: 14px; font-weight: normal; color: #7f7f7f">쿠폰할인(배송비)</th>
                    <td style="text-align: right;">(-)<?= number_format($od['od_send_coupon']) ?>원</td>
                    <td colspan=6></td>
                </tr>
            <? endif ?>
            <? if ($od['od_receipt_point'] > 0) : ?>
                <tr>
                    <th style="font-size: 14px; font-weight: normal; color: #7f7f7f">적립금</th>
                    <td style="text-align: right;">(-)<?= number_format($od['od_receipt_point']) ?>원</td>
                    <td colspan=6></td>
                </tr>
            <? endif ?>
            <tr>
                <td colspan=8 style="border-top: 1px solid #000000; font-size: 14px; font-weight: normal; color: #8a8a8a; padding: 8px 0;">
                    <!-- 카드사별 이벤트, 포인트 사용 등에 따라 실제 결제금액과 표기된 총 결제금액에 차이가 있을 수 있습니다. -->
                </td>
            </tr>
        </table>

        <div class="member-content-title" style="margin-top: 80px;">주문 결제 정보</div>
        <div class="member-order-detail-summary">
            <span>결제 방법</span><span style="margin-left: 8px; margin-right: 160px; font-weight: 600; color: #00bbb4"><?= $od['od_settle_case'] ?></span>
            <span>결제 완료 일시</span><span style="margin-left: 8px; font-weight: 600;"><?= date("Y-m-d H:i", strtotime($od['od_receipt_time'])) ?></span>
        </div>
    </div>
    <div class="on-small">
        <div class="member-content-title">
            주문 취소 금액
        </div>
        <table id="table-cancel-amount">
            <tr>
                <td style="font-size: 12px; line-height: 24px; height: auto;">결제 금액</td>
                <td style="font-size: 12px; line-height: 24px; height: auto; text-align: right;"><?= number_format($od_last_price) ?>원</td>
            </tr>
            <tr>
                <td style="font-size: 12px; line-height: 24px; height: auto;">상품 금액</td>
                <td style="font-size: 12px; line-height: 24px; height: auto; text-align: right;"><?= number_format($tot_sell_price) ?>원</td>
            </tr>
            <tr>
                <td style="font-size: 12px; line-height: 24px; height: auto; color: #7f7f7f;">배송비</td>
                <td style="font-size: 12px; line-height: 24px; height: auto; text-align: right;"><?= number_format($tot_send_cost) ?>원</td>
            </tr>
            <? if ($od['od_cart_coupon'] > 0) : ?>
                <tr>
                    <td style="font-size: 12px; line-height: 24px; height: auto; color: #7f7f7f;">쿠폰할인(상품)</td>
                    <td style="font-size: 12px; line-height: 24px; height: auto; text-align: right;">(-)<?= number_format($tot_send_cost) ?>원</td>
                </tr>
            <? endif ?>
            <? if ($od['od_send_coupon'] > 0) : ?>
                <tr>
                    <td style="font-size: 12px; line-height: 24px; height: auto; color: #7f7f7f;">쿠폰할인(배송비)</td>
                    <td style="font-size: 12px; line-height: 24px; height: auto; text-align: right;">(-)<?= number_format($od['od_send_coupon']) ?>원</td>
                </tr>
            <? endif ?>
            <? if ($od['od_receipt_point'] > 0) : ?>
                <tr>
                    <td style="font-size: 12px; line-height: 24px; height: auto; color: #7f7f7f;">적립금</td>
                    <td style="font-size: 12px; line-height: 24px; height: auto; text-align: right;">(-)<?= number_format($od['od_receipt_point']) ?>원</td>
                </tr>
            <? endif ?>
            <tr>
                <td style="border: 1px solid #e0e0e0; border-width: 1px 0; font-size: 12px; line-height: 40px; height: auto; color: #7f7f7f;">차감 금액</td>
                <td style="border: 1px solid #e0e0e0; border-width: 1px 0; font-size: 12px; line-height: 40px; height: auto; text-align: right;"><?= number_format($od_last_price - $tot_sell_price) ?>원</td>
            </tr>
            <tr>
                <td style="font-size: 16px; line-height: 56px; height: auto; color: #000000;">취소 예정 금액</td>
                <td style="font-size: 16px; line-height: 56px; height: auto; text-align: right; color: #e65026;"><?= number_format($tot_sell_price) ?>원</td>
            </tr>
            <tr>
                <td colspan=2 style="background-color: #f2f2f2; font-size: 12px; font-weight: 500; color: #7f7f7f; padding: 20px;">
                    <!-- 카드사별 이벤트, 포인트 사용 등에 따라 실제 결제금액과 표기된 총 결제금액에 차이가 있을 수 있습니다. -->
                </td>
            </tr>
        </table>

        <div class="member-content-title" style="margin-top: 32px;">주문 결제 정보</div>
        <div class="member-order-detail-summary">
            <table>
                <tr>
                    <td>결제 방법</td>
                    <td style="color: #00bbb4"><?= $od['od_settle_case'] ?></td>
                </tr>
                <tr>
                    <td>결제 완료 일시</td>
                    <td><?= date("Y-m-d H:i", strtotime($od['od_receipt_time'])) ?></td>
                </tr>
            </table>
        </div>
    </div>

    <form method="post" name="forderform" id="forderform" action="<?= G5_SHOP_URL; ?>/orderinquirycancel.php" autocomplete="off">
        <input type="hidden" name="od_id" value="<?= $od['od_id']; ?>">
        <input type="hidden" name="ct_id_arr" value="<?= implode(',', $ct_id) ?>">
        <input type="hidden" name="token" value="<?= $token; ?>">
        <input type="hidden" name="od_send_cost" value="<?= $od['od_send_cost']; ?>">
        <? foreach ($target as $ti => $t) : ?>
            <input type="hidden" name="chk[]" value="<?= $ti ?>">
            <? foreach ($t as $tname => $tvalue) : ?>
                <input type="hidden" name="<?= $tname ?>[<?= $ti ?>]" value="<?= $tvalue ?>">
            <? endforeach ?>
        <? endforeach ?>
        <div style="margin-top: 16px; padding-left: 24px;">
            <span style="height: 40px; font-size: 14px; line-height: 22px; color: #7f7f7f; display: inline-block;">
                <span class="custom-checkbox"><input type="checkbox" id="confirm-cancel" class="custom-control-input">
                    <label for="confirm-cancel" class="custom-control-label">(필수) 주문 및 취소 상품 정보(상품명, 가격, 배송정보, 할인내역)을 확인하였으며 이에 동의합니다.</label>
                </span>
            </span>
            <button type="button" class="btn btn-black" style="width: 90px; height: 50px; float:right; margin-top: 0; font-size: 12px; font-weight: 500;" onclick="confirm_cancel()">주문 취소</button>
        </div>
    </form>
</div>

<script>
    function confirm_cancel() {
        const form = $("#forderform");

        if ($("#confirm-cancel").prop("checked") == false) {
            alert("주문 취소 및 상품정보 확인에 동의해주세요.");
        } else {
            form.submit();
        }
    }
</script>

<?
$tmp_ods = array();
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>