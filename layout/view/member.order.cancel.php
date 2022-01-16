<?php
ob_start();
$g5_title = "주문취소";
include_once G5_LAYOUT_PATH . "/nav.member.php";
?>
<style>
    .custom-checkbox {
        margin-left: 14px;
    }

    @media (max-width: 1366px) {
        #member-content-wrapper {
            padding: 20px;
        }

        #order-list-wrapper {
            padding: 20px;
        }

        #order-notice-wrapper {
            padding: 20px;
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
    }
</style>
<div id="member-content-wrapper">
    <div class="member-content-title">
        주문 취소
    </div>
    <div class="on-big" style="margin-top: 14px; margin-bottom: 80px; font-size: 16px;">
        카드주문 승인취소 : 접수일로부터 5영업일 이내 카드사 홈페이지에서 취소 내역 확인 가능<br>
        실시간 이체 취소 : 거래한 은행계좌로 5영업일 이내 입금
    </div>
    <div class="on-small" style="margin-top: 14px; margin-bottom: 80px; font-size: 14px;">
        카드주문 승인취소 : 접수일로부터 5영업일 이내 카드사 홈페이지에서 취소 내역 확인 가능<br>
        실시간 이체 취소 : 거래한 은행계좌로 5영업일 이내 입금
    </div>
    <div class="member-content-title">
        주문 취소 상품 선택
    </div>
    <form action="/member/order.cancel.php" method="POST" id="form-cancel">
        <input type="hidden" name="step" value="2">
        <input type="hidden" name="od_id" value="<?= $od['od_id'] ?>">
        <table class="member-order-detail-list">
            <thead>
                <tr class="on-big">
                    <th style="line-height: inherit; width: 34px;">
                        <span class="custom-checkbox"><input type="checkbox" id="cancel-item-all" class="custom-control-input cbg-cancel-<?= $od['od_id'] ?>" data-checkgroup="cbg-cancel-<?= $od['od_id'] ?>" data-checkall="cbg-cancel-<?= $od['od_id'] ?>">
                            <label for="cancel-item-all" class="custom-control-label"></label>
                        </span>
                    </th>
                    <th colspan=2>상품 정보</th>
                    <th style="width:15%">배송비</th>
                    <th style="width:15%">주문상태</th>
                </tr>
            </thead>
            <tbody>
                <?
                $sql = " select ct.it_id, ct.it_name, cp_price, ct_send_cost, ct.it_sc_type, ct_id, ct_option, ct_qty, ct_price, ct_point, ct_status, io_type, io_price, ct_rental_price, ct_item_rental_month, ct_keep_month, ct_receipt_price, it.it_brand, it.it_price AS org_price
                                        , if(ct_status IN ( '결제완료', '상품준비중', '배송중', '배송완료', '구매완료' ), 0, 1) as ct_status_order
                                        from {$g5['g5_shop_cart_table']} AS ct JOIN {$g5['g5_shop_item_table']} AS it ON ct.it_id=it.it_id
                                        where od_id = '{$od['od_id']}' AND ct_status = '결제완료'
                                        order by it_id, it_sc_type, ct_status_order";

                $result = sql_query($sql);
                $tot_rows = sql_num_rows($result);
                $rowspan = 0;
                $rowspanCnt = 0;
                $total_send_cost = (int) $od['od_send_cost'];
                if ($tot_rows > 0) {
                    while ($row = sql_fetch_array($result)) {
                        if (isset($od_status_set_count[$row['ct_status']])) $od_status_set_count[$row['ct_status']]++;
                        $image = get_it_image($row['it_id'], 90, 90, '', '', $row['it_name']);
                        $ct_send_cost_str = "-";

                        if ($od['od_type'] == "O") {
                            $sql_sc = " select  SUM((b.its_final_price + a.io_price) * a.ct_qty) as price,
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

                            if ($ct_send_cost == 0) $ct_send_cost_str = "무료배송";
                            else $ct_send_cost_str = number_format($ct_send_cost) . " 원";
                        }
                        $tot_rows--;
                ?>
                        <tr class="on-big">
                            <td>
                                <span class="custom-checkbox">
                                    <input type="checkbox" id="cancel-item-<?= $row['ct_id'] ?>" class="custom-control-input cbg-cancel-<?= $od['od_id'] ?>" data-checkgroup="cbg-cancel-<?= $od['od_id'] ?>" name="ct_id[]" value="<?= $row['ct_id'] ?>">
                                    <label for="cancel-item-<?= $row['ct_id'] ?>" class="custom-control-label"></label>
                                </span>
                            </td>
                            <td style="width: 90px;"><?= $image; ?></td>
                            <td style="text-align: left; padding-left: 16px;">
                                <div class="list-brand"><?= empty($row['it_brand']) ? "LIFELIKE" : stripslashes($row['it_brand']); ?></div>
                                <div class="list-name"><a href="/shop/item.php?it_id=<?= $row['it_id']; ?>"><?= stripslashes($row['it_name']); ?></a></div>
                                <? if ($row['ct_option']) : ?>
                                    <div class="list-option"><?= get_text($row['ct_option']); ?></div>
                                <? endif ?>
                                <div class="list-price">
                                    <?= number_format(($row['ct_price'] + $row['io_price']) * $row['ct_qty']) ?><span style="font-size: 12px; font-weight: normal;">원<? if ($row['ct_qty'] > 1) : ?>/ 총<?= number_format($row['ct_qty']); ?>개<? endif ?></span>
                                </div>
                            </td>
                            <td <?
                                if ($rowspan == $tot_rows + 1) echo "style='border-bottom-color: #000000;'";
                                //묶음 배송비 처리
                                if ($rowspanCnt > 0) {
                                    echo "hidden";
                                    $rowspanCnt--;
                                }
                                if ($rowspan > 0) {
                                    echo " rowspan={$rowspan} ";
                                    $rowspanCnt = $rowspan - 1;
                                    $rowspan = 0;
                                }
                                ?>>
                                <?= $ct_send_cost_str; ?>
                            </td>
                            <td>
                                <?= $row['ct_status']; ?>
                                <? if (in_array($row['ct_status'], $display_delivery_set)) : ?>
                                    <?
                                    if ($od['od_invoice'] && $od['od_delivery_company']) {
                                        $dlcomp = explode(")", str_replace("(", "", G5_DELIVERY_COMPANY));
                                        foreach ($dlcomp as $dlcompany) {
                                            if (strstr($dlcompany, $od['od_delivery_company'])) {
                                                list($com, $url, $tel) = explode("^", $dlcompany);
                                                break;
                                            }
                                        }
                                        if ($com && $url) {
                                            echo '<div class="btn-invoice-company">' . $com . ' <a href="' . $url . $od['od_invoice'] . '" target="_blank" class="btn-invoice">' . $od['od_invoice'] . '</a></div>';
                                        }
                                    } ?>
                                <? endif ?>
                            </td>
                        </tr>
                        <tr class="on-small">
                            <td style="width: 90px; font-size: 12px;"><span class="custom-checkbox">
                                    <input type="checkbox" id="cancel-item-<?= $row['ct_id'] ?>" class="custom-control-input cbg-cancel-<?= $od['od_id'] ?>" data-checkgroup="cbg-cancel-<?= $od['od_id'] ?>" name="ct_id[]" value="<?= $row['ct_id'] ?>">
                                    <label for="cancel-item-<?= $row['ct_id'] ?>" class="custom-control-label"><?= $row['ct_status']; ?></label>
                                </span></td>
                            <td style="text-align: right; font-size: 12px;"><?= $ct_send_cost_str; ?></td>
                        </tr>
                        <tr class="on-small">
                            <td><?= $image; ?></td>
                            <td style="text-align: left; padding-left: 20px;">
                                <div style="font-size: 12px; color: #000000;"><?= stripslashes($row['it_name']); ?></div>
                                <div style="font-size: 10px; color: #000000;"><?= get_text($row['ct_option']); ?></div>
                                <div style="font-size: 12px; color: #7f7f7f;"><?= number_format(($row['ct_price'] + $row['io_price']) * $row['ct_qty']) ?><span style="font-size: 12px; font-weight: normal;">원<? if ($row['ct_qty'] > 1) : ?>/ 총<?= number_format($row['ct_qty']); ?>개<? endif ?></span></div>
                            </td>
                        </tr>
                    <? }
                } else { ?>
                    <tr>
                        <td colspan=5>취소 가능한 상품이 없습니다.</td>
                    </tr>
                <? } ?>
            </tbody>
        </table>

        <div style="height: 32px; padding: 20px 0;" class="on-small">
            <button type="button" class="btn btn-black" style="height: 32px; width: 100px; float:right; margin-top: 0; font-size: 12px; font-weight: 500;" onclick="cancelAll()">전체 주문 취소</button>
            <button type="submit" class="btn btn-black" style="height: 32px; width: 100px; float:right; margin-top: 0; font-size: 12px; font-weight: 500; background-color: #e0e0e0; color: #000000; margin-right: 8px;">선택 주문 취소</button>
        </div>
        <div style="margin-top: 16px;" class="on-big">
            <span style="height: 40px; font-size: 14px; line-height: 22px; color: #7f7f7f; display: inline-block;">
                - 주문 취소는 결제 대기, 결제 완료 상태에서만 가능합니다.<br>
                - 주문 취소 금액에 따라 취소 외 제품에 적용된 할인 쿠폰 일부가 취소 될 수 있습니다.<br>
                - 주문 취소와 함께 사용이 취소된 쿠폰은 재발급됩니다.<br>
                - 주문 취소 금액에 따라 무료 배송 일부가 취소 될 수 있습니다.
            </span>
            <button type="button" class="btn btn-black" style="height: 50px; width: 100px; float:right; margin-top: 0; font-size: 12px; font-weight: 500;" onclick="cancelAll()">전체 주문 취소</button>
            <button type="submit" class="btn btn-black" style="height: 50px; width: 100px; float:right; margin-top: 0; font-size: 12px; font-weight: 500; background-color: #e0e0e0; color: #000000; margin-right: 8px;">선택 주문 취소</button>
        </div>
        <div style="margin-top: 32px; padding: 16px 20px; background-color: #f2f2f2; font-size: 12px; font-weight: 500; color: #8a8a8a;" class="on-small">
            - 주문 취소는 결제 대기, 결제 완료 상태에서만 가능합니다.<br>
            - 주문 취소 금액에 따라 취소 외 제품에 적용된 할인 쿠폰 일부가 취소 될 수 있습니다.<br>
            - 주문 취소와 함께 사용이 취소된 쿠폰은 재발급됩니다.<br>
            - 주문 취소 금액에 따라 무료 배송 일부가 취소 될 수 있습니다.
        </div>
    </form>
</div>
<script>
    function cancelAll() {
        const form = $("#form-cancel");

        const checkElem = $(".member-order-detail-list").find("input[type='checkbox']");
        checkElem.each(function(ei, elem) {
            $(elem).attr("checked", "checked");
        });

        form.submit();
    }
</script>
<?
$tmp_ods = array();
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>