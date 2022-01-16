<?php
ob_start();
$g5_title = "취소/반품 조회";
include_once G5_LAYOUT_PATH . "/nav.member.php";
?>
<style>
    div.member-content-section tr>td {
        height: 56px;
        padding-left: 20px;
    }

    .claim-content {
        display: none;
    }

    .claim-content.active {
        display: table-row;
    }

    .claim-content>td {
        color: #000000 !important;
        text-align: left !important;
        background-color: #e0e0e0;
        padding: 16px 0 16px 20px;
        border-bottom: 1px solid #ffffff !important;
    }

    .qna-answer {
        font-size: 16px !important;
        font-weight: normal;
        color: #000000 !important;
        margin-top: 14px !important;
    }

    td>p {
        font-size: 14px;
        font-weight: normal;
        margin-bottom: unset;
    }

    .btn-list,
    .btn-list-black {
        font-size: 12px !important;
        font-weight: normal !important;
        color: #7f7f7f !important;
        height: 32px !important;
        border: solid 1px #cecece !important;
        margin-top: unset !important;
        background-color: #ffffff !important;
    }

    .btn-list-black {
        color: #ffffff !important;
        background-color: var(--black-two) !important;
        border-color: var(--black-two) !important;
        vertical-align: top;
        float: right;
    }

    #member-qna-write th,
    #member-qna-write td {
        text-align: left;
        border-bottom: unset;
        height: auto;
        padding: 8px 0;
    }

    #member-qna-write td {
        font-weight: normal;
        font-size: 14px;
        line-height: 28px;
    }


    #qa-check-email {
        margin-left: 8px;
    }

    #qa-check-email>.custom-control-label::before {
        margin-left: 4px;
        margin-top: 2px;
    }

    #qa-check-email>.custom-control-label::after {
        margin-left: 4px;
        margin-top: 2px;
    }

    .popup-order-item-wrapper {
        font-size: 12px;
        padding: 8px 20px;
        line-height: 20px;

    }

    .popup-order-item-wrapper>div {
        padding: 0 4px;
    }

    .member-claim-filter {
        font-size: 14px;
        font-weight: normal;
        line-height: normal;
        color: #7f7f7f;
        cursor: pointer;
    }

    .member-claim-filter:after {
        content: '';
        margin-left: 8px;
        border-left: 1px solid #000000;
        height: 13px;
        display: inline-block;
        vertical-align: middle;
    }

    .member-claim-filter:last-child:after {
        display: none;
    }

    .member-claim-filter.active {
        color: #000000;
    }

    @media (max-width: 1366px) {
        #member-content-wrapper {
            padding: 20px;
        }

        div.member-content-section tr>td {
            padding-left: unset;
        }
    }
</style>
<div id="member-content-wrapper">
    <form method="GET" id="form-claim">
        <input type="hidden" id="form-claim-filter" name="filter" value="<?= $filter ?>">
        <div class="member-content-title on-big">취소/반품 조회
            <div style="text-align: right; float: right;">
                <span class="member-claim-filter <?= empty($filter) ? "active" : "" ?>" onclick="applyFilter('')">전체보기</span>
                <span class="member-claim-filter <?= $filter == "주문취소" ? "active" : "" ?>" onclick="applyFilter('주문취소')">주문취소</span>
                <!-- <span class="member-claim-filter <?= $filter == "교환" ? "active" : "" ?>" onclick="applyFilter('교환')">교환</span> -->
                <span class="member-claim-filter <?= $filter == "반품" ? "active" : "" ?>" onclick="applyFilter('반품')">반품</span>
            </div>
        </div>
        <div class="member-content-title on-small">
            <div>
                <span class="member-claim-filter <?= empty($filter) ? "active" : "" ?>" onclick="applyFilter('')">전체보기</span>
                <span class="member-claim-filter <?= $filter == "주문취소" ? "active" : "" ?>" onclick="applyFilter('주문취소')">주문취소</span>
                <!-- <span class="member-claim-filter <?= $filter == "교환" ? "active" : "" ?>" onclick="applyFilter('교환')">교환</span> -->
                <span class="member-claim-filter <?= $filter == "반품" ? "active" : "" ?>" onclick="applyFilter('반품')">반품</span>
            </div>
        </div>

        <div class="member-content-section" style="margin-bottom: 40px;">
            <? if ($db_order_claim->num_rows > 0) : ?>
                <table>
                    <tr class="on-big">
                        <th style="width: 140px">요청</th>
                        <th>주문번호</th>
                        <th style="width: 140px">접수일자</th>
                        <th style="width: 140px">진행상태</th>
                        <th style="width: 140px">완료일자</th>
                    </tr>
                    <tr class="on-small">
                        <th style="font-size: 14px; width: 120px; text-align: left;">주문번호/접수일</th>
                        <th style="font-size: 14px; ">진행상태</th>
                    </tr>
                    <? for ($oi = 0; $claim = sql_fetch_array($db_order_claim); $oi++) : ?>
                        <tr class="on-big">
                            <td><?= $claim['ct_status_claim'] ?></td>
                            <td style="text-align: left; cursor: pointer; font-size: 14px; font-weight: normal; color: #000000;" onclick="openAnswer(this)" data-oi=<?= $oi ?>><?= $claim['od_id'] ?></td>
                            <td><?= date("Y.m.d", strtotime($claim['first_claim_datetime'])) ?></td>
                            <td><?= $claim['ct_status_claim'] ?></td>
                            <td><?= date("Y.m.d", strtotime($claim['od_status_claim_date'])) ?></td>
                        </tr>
                        <tr class="on-small">
                            <td style="text-align: left; cursor: pointer; font-size: 14px; font-weight: normal; color: #000000;" onclick="openAnswer(this)" data-oi=<?= $oi ?>>
                                <div style="font-size: 12px;"><?= $claim['od_id'] ?></div>
                                <div style="font-size: 10px;"><?= date("Y.m.d", strtotime($claim['first_claim_datetime'])) ?></div>
                            </td>
                            <td>
                                <div style="font-size: 12px;"><?= $claim['ct_status_claim'] ?></div>
                                <div style="font-size: 10px;"><?= date("Y.m.d", strtotime($claim['od_status_claim_date'])) ?></div>
                            </td>
                        </tr>
                        <?php
                        if ($claim['ct_id'] == "0") {
                            $sql_ct_parted = "SELECT GROUP_CONCAT(sh.ct_id) AS ct_id_parted FROM lt_shop_order_history AS sh WHERE sh.od_id='{$claim['od_id']}' AND sh.ct_id != 0";
                            $db_ct_parted = sql_fetch($sql_ct_parted);

                            $sql_order_cart = "SELECT ct.*,it.*,od.od_pickup_delivery_company,od.od_pickup_invoice,od_cancel_price,od_refund_price FROM lt_shop_cart AS ct
                                            LEFT JOIN lt_shop_item AS it ON ct.it_id=it.it_id
                                            LEFT JOIN lt_shop_order AS od ON ct.od_id=od.od_id
                                            WHERE ct.od_id='{$claim['od_id']}'";
                            if (!empty($db_ct_parted['ct_id_parted'])) {
                                $sql_order_cart .= " AND ct.ct_id NOT IN ({$db_ct_parted['ct_id_parted']})";
                            }
                            $sql_order_cart .= " GROUP BY ct.it_id";
                        } else {
                            // $sql_order_cart = "SELECT it.*,ct.*,(SELECT it_brand FROM lt_shop_item AS si WHERE si.it_id=ct.it_id) FROM lt_shop_cart AS ct LEFT JOIN lt_shop_order_item AS it ON ct.it_id=it.it_id WHERE ct.od_id='{$claim['od_id']}' AND ct.ct_id='{$claim['ct_id']}' GROUP BY ct.it_id";
                            $sql_order_cart = "SELECT ct.*,it.*,od.od_pickup_delivery_company,od.od_pickup_invoice,od_cancel_price,od_refund_price FROM lt_shop_cart AS ct
                                            LEFT JOIN lt_shop_item AS it ON ct.it_id=it.it_id
                                            LEFT JOIN lt_shop_order AS od ON ct.od_id=od.od_id
                                            WHERE ct.od_id='{$claim['od_id']}' AND ct.ct_id='{$claim['ct_id']}' GROUP BY ct.it_id";
                        }
                        $db_order_cart = sql_query($sql_order_cart);
                        ?>
                        <tr class="claim-content claim-content-<?= $oi ?> on-big">
                            <td style="font-size: 16px; font-weight: normal;">접수사유</td>
                            <td style="font-size: 18px;" colspan=4>
                                <?= $claim['sh_memo'] ?>
                            </td>
                        </tr>
                        <tr class="claim-content claim-content-<?= $oi ?> on-small">
                            <td style="font-size: 10px; font-weight: normal;">접수사유</td>
                            <td style="font-size: 10px;">
                                <?= $claim['sh_memo'] ?>
                            </td>
                        </tr>
                        <? for ($ci = 0; $oc = sql_fetch_array($db_order_cart); $ci++) : ?>
                            <tr class="claim-content claim-content-<?= $oi ?> on-big">
                                <? if ($ci == 0) : ?>
                                    <td style="font-size: 16px; font-weight: normal; vertical-align: top;" rowspan=<?= $db_order_cart->num_rows ?>>접수 상품</td>
                                <? endif ?>
                                <td colspan=4 style="text-align: left;">
                                    <div style="font-size: 16px; font-weight: 600; color: #7f7f7f;"><?= $oc['it_brand'] ?></div>
                                    <div style="font-size: 18px; font-weight: 500; color: #000000;"><?= $oc['it_name'] ?></div>
                                    <div style="font-size: 12px; font-weight: 500; color: #000000;"><?= $oc['it_id'] ?></div>
                                    <div style="font-size: 12px; font-weight: normal; color: #000000;"><?= $oc['ct_option'] ?></div>
                                    <div style="font-size: 16px; font-weight: 600; color: #2fc3bd;"><?= number_format($oc['ct_receipt_price']) ?><span style="font-size: 12px;">원 / 총<?= $oc['ct_qty'] ?>개</span>
                                    </div>

                                </td>
                            </tr>
                            <tr class="claim-content claim-content-<?= $oi ?> on-small">
                                <? if ($ci == 0) : ?>
                                    <td style="font-size: 10px; font-weight: normal; vertical-align: top;" rowspan=<?= $db_order_cart->num_rows ?>>접수 상품</td>
                                <? endif ?>
                                <td style="text-align: left;">
                                    <div style="font-size: 12px; font-weight: 600; color: #7f7f7f;"><?= $oc['it_brand'] ?></div>
                                    <div style="font-size: 12px; font-weight: 500; color: #000000;"><?= $oc['it_name'] ?></div>
                                    <div style="font-size: 10px; font-weight: 500; color: #000000;"><?= $oc['it_id'] ?></div>
                                    <div style="font-size: 10px; font-weight: normal; color: #000000;"><?= $oc['ct_option'] ?></div>
                                    <div style="font-size: 12px; font-weight: 600; color: #2fc3bd;"><?= number_format($oc['ct_receipt_price']) ?><span style="font-size: 12px;">원 / 총<?= $oc['ct_qty'] ?>개</span>
                                    </div>

                                </td>
                            </tr>
                        <? endfor ?>
                        <? if (!empty($oc['od_pickup_delivery_company'])) : ?>
                            <tr class="claim-content claim-content-<?= $oi ?> on-big">
                                <td style="font-size: 16px; font-weight: normal;">택배사 정보</td>
                                <td colspan=4><?= $oc['od_pickup_delivery_company'] ?><?= $oc['od_pickup_invoice'] ?></td>
                            </tr>
                            <tr class="claim-content claim-content-<?= $oi ?> on-small">
                                <td style="font-size: 10px; font-weight: normal;">택배사 정보</td>
                                <td><?= $oc['od_pickup_delivery_company'] ?><?= $oc['od_pickup_invoice'] ?></td>
                            </tr>
                        <? endif ?>
                        <? if ($oc['ct_status'] == "주문취소" && $oc['od_cancel_price'] > 0) : ?>
                            <tr class="claim-content claim-content-<?= $oi ?> on-big">
                                <td style="color: #fa3f00; font-size: 14px;">총 취소 금액</td>
                                <td style="color: #fa3f00; font-size: 14px;" colspan=4><?= number_format($oc['od_cancel_price']) ?>원</td>
                            </tr>
                            <tr class="claim-content claim-content-<?= $oi ?> on-small">
                                <td style="color: #fa3f00; font-size: 10px;">총 취소 금액</td>
                                <td style="color: #fa3f00; font-size: 10px;"><?= number_format($oc['od_cancel_price']) ?>원</td>
                            </tr>
                        <? endif ?>
                        <? if ($oc['ct_status'] == "반품" && $oc['od_refund_price'] > 0) : ?>
                            <tr class="claim-content claim-content-<?= $oi ?> on-big">
                                <td style="color: #fa3f00; font-size: 14px;">총 환불 금액</td>
                                <td style="color: #fa3f00; font-size: 14px;" colspan=4><?= number_format($oc['od_refund_price']) ?>원</td>
                            </tr>
                            <tr class="claim-content claim-content-<?= $oi ?> on-small">
                                <td style="color: #fa3f00; font-size: 10px;">총 환불 금액</td>
                                <td style="color: #fa3f00; font-size: 10px;"><?= number_format($oc['od_refund_price']) ?>원</td>
                            </tr>
                        <? endif ?>
                        <?php
                        if ($oi == 0) {
                            $sql_sh_memo = "SELECT sh_memo FROM lt_shop_order_history WHERE od_id='{$claim['od_id']}' AND is_important=0";
                            $sh_memo = sql_query($sql_sh_memo);

                            if ($sh_memo->num_rows > 0) {
                        ?>
                                <tr class="claim-content claim-content-<?= $oi ?> on-big">
                                    <td style="color: #fa3f00; font-size: 14px;">기타</td>
                                    <td style="color: #fa3f00; font-size: 14px;" colspan=4>
                                        <?
                                        while (false != ($sm = sql_fetch_array($sh_memo))) {
                                        ?>
                                            <div><?= $sm['sh_memo'] ?></div>
                                        <?
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr class="claim-content claim-content-<?= $oi ?> on-small">
                                    <td style="color: #fa3f00; font-size: 10px;">기타</td>
                                    <td style="color: #fa3f00; font-size: 10px;">
                                        <?
                                        while (false != ($sm = sql_fetch_array($sh_memo))) {
                                        ?>
                                            <div><?= $sm['sh_memo'] ?></div>
                                        <?
                                        }
                                        ?>
                                    </td>
                                </tr>
                        <?
                            }
                        }
                        ?>
                    <? endfor ?>
                </table>
            <? else : ?>
                <div class="member-no-content">
                    검색결과가 없습니다.
                </div>
            <? endif ?>
        </div>
        <? if ($paging) : ?>
            <div style="margin-bottom: 170px;"><?= $paging ?></div>
        <? endif ?>
    </form>
</div>

<script>
    $(".modal-custom-scrollbar").scrollbar({
        height: 300,
        disableBodyScroll: true
    });

    function writeQna() {
        $(".member-qna-list").hide();
        $(".member-qna-write").show();
    }

    function openAnswer(elem) {
        const oi = $(elem).data("oi");
        $(".claim-content").removeClass("active");
        $(".claim-content-" + oi).addClass("active");
    }

    function setOrderId(id) {
        $("#od_id").val(id);
        $("#modal-order-list-wrapper").modal("hide");
    }

    function applyFilter(filter) {
        $("#form-claim-filter").val(filter);
        $("#form-claim").submit();
    }
</script>
<?php
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>