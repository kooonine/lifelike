<?php
ob_start();
// $g5_title = "취소/반품/교환조회";
$g5_title = "취소/반품조회";
include_once G5_LAYOUT_PATH . "/nav.member.php";
?>
<style>
    .datepicker table tr td.active.active,
    .datepicker table tr td.active.highlighted.active,
    .datepicker table tr td.active.highlighted:active,
    .datepicker table tr td.active:active {
        background-color: #333333;
        border-color: #333333;
    }

    .datepicker table tr td.active.active.focus,
    .datepicker table tr td.active.active:focus,
    .datepicker table tr td.active.active:hover,
    .datepicker table tr td.active.highlighted.active.focus,
    .datepicker table tr td.active.highlighted.active:focus,
    .datepicker table tr td.active.highlighted.active:hover,
    .datepicker table tr td.active.highlighted:active.focus,
    .datepicker table tr td.active.highlighted:active:focus,
    .datepicker table tr td.active.highlighted:active:hover,
    .datepicker table tr td.active:active.focus,
    .datepicker table tr td.active:active:focus,
    .datepicker table tr td.active:active:hover {
        color: #333333;
        background-color: #e0e0e0;
        border-color: #333333;
    }

    .datepicker table tr td span.active.active,
    .datepicker table tr td span.active.disabled.active,
    .datepicker table tr td span.active.disabled:active,
    .datepicker table tr td span.active.disabled:hover.active,
    .datepicker table tr td span.active.disabled:hover:active,
    .datepicker table tr td span.active:active,
    .datepicker table tr td span.active:hover.active,
    .datepicker table tr td span.active:hover:active {
        color: #fff;
        background-color: #333333;
        border-color: #333333;
    }

    @media (max-width: 1366px) {
        #order-list-wrapper {
            /* padding: 20px; */
        }

        #order-notice-wrapper {
            /* padding: 20px; */
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
    <div class="member-content-section" id="order-list-wrapper">
        <div class="member-content-title on-big">
            <?= $g5_title ?>
        </div>
        <form action="" name="formMemberFilter" id="form-member-filter">
            <input type="hidden" name="startdate" value="<?= $startdate ?>">
            <input type="hidden" name="enddate" value="<?= $enddate ?>">

            <!-- 기간필터 -->

            <div class="on-big" style="font-weight: 500; padding: 20px 28px; padding-top: 14px; border: solid 1px #f2f2f2; background-color: #f2f2f2;">
                <span style="font-size: 18px;">기간조회</span>
                <button type="button" class="btn-member btn-white btn-filter-range" data-range=1 style="margin-left: 26px;">1개월</button>
                <button type="button" class="btn-member btn-white btn-filter-range" data-range=3 style="margin-left: 10px;">3개월</button>
                <button type="button" class="btn-member btn-white btn-filter-range" data-range=6 style="margin-left: 10px;">6개월</button>
                <span style="margin: 0 8px 0 42px;">
                    <input type="text" id="filter-member-startdate" autocomplete="off" class="bs-datepicker" style="width: 180px; height: 44px; font-size: 18px; border: 1px solid #333333;">
                    <span style="font-size: 28px; margin: 0 8px;">~</span>
                    <input type="text" id="filter-member-enddate" autocomplete="off" class="bs-datepicker" style="width: 180px; height: 44px; font-size: 18px; border: 1px solid #333333;">
                </span>
                <button type="submit" class="btn-member">조회</button>
            </div>

            <!-- 기간필터 모바일 -->

            <div class="on-small" style="padding: 28px 14px; padding-top: 18px; background-color: #f2f2f2; display: flex; justify-content: space-between;">
                <span>
                    <div style="display: flex; justify-content: space-between;">
                        <button type="button" class="btn-member btn-white btn-xs btn-filter-range" data-range=1>1개월</button>
                        <button type="button" class="btn-member btn-white btn-xs btn-filter-range" data-range=3>3개월</button>
                        <button type="button" class="btn-member btn-white btn-xs btn-filter-range" data-range=6>6개월</button>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 16px;">
                        <input type="text" id="filter-member-startdate-mobile" autocomplete="off" class="bs-datepicker" style="width: 100px; height: 28px; font-size: 12px; border: 1px solid #333333; border: 1px solid #333333; background: #ffffff; padding: 0 14px; line-height: 28px;">
                        <span style="font-size: 12px; margin: 0 8px; padding-top: 4px;">~</span>
                        <input type="text" id="filter-member-enddate-mobile" autocomplete="off" class="bs-datepicker" style="width: 100px; height: 28px; font-size: 12px; border: 1px solid #333333; border: 1px solid #333333; background: #ffffff; padding: 0 14px; line-height: 28px;">
                    </div>
                </span>
                <span style="width: 100px;">
                    <button type="submit" class="btn-member btn-white" style="width: 100px; height: 100%;">조회</button>
                </span>
            </div>
        </form>
        <? if ($db_order_claim->num_rows > 0) : ?>
            <div class="on-big" style="margin-top: 30px; border-top: 3px solid #333333;"></div>
            <table>
                <tr class="on-big">
                    <th>주문번호(주문일)</th>
                    <th colspan=2>상품정보</th>
                    <th>결제금액(수량)</th>
                    <th>주문상태</th>
                </tr>
                <? include_once "member.order.cancel.list.item.php" ?>
            </table>
        <? else : ?>
            <div class="member-no-content">
                주문내역이 없습니다
            </div>
        <? endif ?>
    </div>
    <? if ($paging) : ?>
        <div class="on-big" style="margin-bottom: 170px;"><?= $paging ?></div>
    <? endif ?>

    <div id="order-notice-wrapper">
        <div class="member-content-title on-big">취소안내</div>
        <div style="border-radius: 2px; border: solid 1px #e0e0e0; padding: 20px 16px;" class="on-big">
            <div style="font-size: 14px; font-weight: 500; color: #333333;">결제완료 상태에서 취소시 즉시 취소가능합니다.</div>
            <div style="font-size: 14px; font-weight: normal; color: #a3a3a3;">카드사, 은행사에 따라 환불시점은 상이할 수 있습니다. 문의사항은 해당 카드사, 은행사로 연락주시기 바랍니다.</div>
        </div>

        <div class="member-content-title on-big" style="margin-top: 40px;">반품안내</div>
        <div style="border-radius: 2px; border: solid 1px #e0e0e0; padding: 20px 16px;" class="on-big">
            <table>
                <tr>
                    <td><span class="icon-order-step" style="margin: 25px 15px; margin-right: 20px;">1</span></td>
                    <td>
                        <div style="font-size: 14px; font-weight: 500; color: #333333;">반품신청</div>
                        <div style="font-size: 14px; font-weight: normal; color: #a3a3a3;">
                            오배송 및 불량 : 반품 배송비는 무료입니다. 고객 변심 : 반품 배송비는 고객부담입니다.<br>
                            2개 이상의 상품을 반품 신청하실 경우, 제품하자나 오배송 상품이 포함되어 있다면 당사가 배송비를 부담합니다.
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><span class="icon-order-step" style="margin: 25px 15px; margin-right: 20px;">2</span></td>
                    <td>
                        <div style="font-size: 14px; font-weight: 500; color: #333333;">반품수거중</div>
                        <div style="font-size: 14px; font-weight: normal; color: #a3a3a3;">택배 기사님이 방문하여 상품을 수거해 갑니다.</div>
                    </td>
                </tr>
                <tr>
                    <td><span class="icon-order-step" style="margin: 25px 15px; margin-right: 20px;">3</span></td>
                    <td>
                        <div style="font-size: 14px; font-weight: 500; color: #333333;">반품수거완료</div>
                        <div style="font-size: 14px; font-weight: normal; color: #a3a3a3;">반품하신 상품에 이상이 있을 경우 반품처리가 되지 않을 수도 있습니다.</div>
                    </td>
                </tr>
                <tr>
                    <td><span class="icon-order-step" style="margin: 25px 15px; margin-right: 20px;">4</span></td>
                    <td>
                        <div style="font-size: 14px; font-weight: 500; color: #333333;">반품완료</div>
                        <div style="font-size: 14px; font-weight: normal; color: #a3a3a3;">
                            반품하신 상품에 이상이 없을 경우 반품완료와 함께 취소처리가 됩니다.<br>
                            (카드사, 은행사에 따라 환불시점은 상이할 수 있습니다. 문의사항은 해당 카드사, 은행사로 연락주시기 바랍니다.)
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- <div class="member-content-title on-big" style="margin-top: 40px;">교환안내</div>
        <div style="border-radius: 2px; border: solid 1px #e0e0e0; padding: 20px 16px; margin-bottom: 140px;" class="on-big">
            <table>
                <tr>
                    <td><span class="icon-order-step" style="margin: 25px 15px; margin-right: 20px;">1</span></td>
                    <td>
                        <div style="font-size: 14px; font-weight: 500; color: #333333;">교환신청</div>
                        <div style="font-size: 14px; font-weight: normal; color: #a3a3a3;">
                            오배송 및 불량 : 교환 배송비는 무료입니다. 고객 변심 : 교환 배송비는 고객부담입니다.<br>
                            2개 이상의 상품을 교환 신청하실 경우, 제품하자나 오배송 상품이 포함되어 있다면 당사가 배송비를 부담합니다.
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><span class="icon-order-step" style="margin: 25px 15px; margin-right: 20px;">2</span></td>
                    <td>
                        <div style="font-size: 14px; font-weight: 500; color: #333333;">교환수거중</div>
                        <div style="font-size: 14px; font-weight: normal; color: #a3a3a3;">택배 기사님이 방문하여 상품을 수거해 갑니다.</div>
                    </td>
                </tr>
                <tr>
                    <td><span class="icon-order-step" style="margin: 25px 15px; margin-right: 20px;">3</span></td>
                    <td>
                        <div style="font-size: 14px; font-weight: 500; color: #333333;">교환수거완료</div>
                        <div style="font-size: 14px; font-weight: normal; color: #a3a3a3;">반품하신 상품에 이상이 있을 경우 교환처리가 되지 않을 수도 있습니다.</div>
                    </td>
                </tr>
                <tr>
                    <td><span class="icon-order-step" style="margin: 25px 15px; margin-right: 20px;">4</span></td>
                    <td>
                        <div style="font-size: 14px; font-weight: 500; color: #333333;">교환완료</div>
                        <div style="font-size: 14px; font-weight: normal; color: #a3a3a3;">
                            반품하신 상품에 이상이 없을 경우 반품완료와 함께 동일상품이 발송됩니다.
                        </div>
                    </td>
                </tr>
            </table>
        </div> -->

        <!-- 주문안내 모바일 -->
        <div class="member-content-title on-small" style="background-color: #f2f2f2; display: flex; padding: 14px;">
            <span>주문 안내</span>
            <span></span>
        </div>
        <div class="on-small" style="background-color: #f2f2f2; padding: 40px 14px; padding-top: unset;">
            <table style="width: 100%;">
                <tr>
                    <td style="height: 72px; width: 47px;">
                        <span class="icon-order-step-mobile">1</span>
                    </td>
                    <td style="padding-left: 16px;">
                        <div style="font-size: 16px; font-weight: 500; color: #333333;">
                            배송완료
                        </div>
                        <div style="font-size: 14px; color: #333333;">
                            주문접수 및 결제 완료
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="height: 14px; text-align: center;">
                        <div><span class="icon-order-step-dot-mobile"></span></div>
                        <div><span class="icon-order-step-dot-mobile"></span></div>
                        <div><span class="icon-order-step-dot-mobile"></span></div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 72px;">
                        <span class="icon-order-step-mobile">2</span>
                    </td>
                    <td style="padding-left: 16px;">
                        <div style="font-size: 16px; font-weight: 500; color: #333333;">
                            배송준비중
                        </div>
                        <div style="font-size: 14px; color: #333333;">
                            주문 확인 후 배송준비중<br>(배송 지연 및 품절 발생 가능)
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="height: 14px; text-align: center;">
                        <div><span class="icon-order-step-dot-mobile"></span></div>
                        <div><span class="icon-order-step-dot-mobile"></span></div>
                        <div><span class="icon-order-step-dot-mobile"></span></div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 72px;">
                        <span class="icon-order-step-mobile">3</span>
                    </td>
                    <td style="padding-left: 16px;">
                        <div style="font-size: 16px; font-weight: 500; color: #333333;">
                            배송중
                        </div>
                        <div style="font-size: 14px; color: #333333;">
                            택배사로 상품 전달 후<br>배송 시작
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="height: 14px; text-align: center;">
                        <div><span class="icon-order-step-dot-mobile"></span></div>
                        <div><span class="icon-order-step-dot-mobile"></span></div>
                        <div><span class="icon-order-step-dot-mobile"></span></div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height: 72px;">
                        <span class="icon-order-step-mobile">4</span>
                    </td>
                    <td style="padding-left: 16px;">
                        <div style="font-size: 16px; font-weight: 500; color: #333333;">
                            배송완료
                        </div>
                        <div style="font-size: 14px; color: #333333;">
                            고객님께 상품 배송 완료<br>(상품리뷰 작성 가능, 포인트 지급)
                        </div>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        const formFilter = document.formMemberFilter;
        $("#filter-member-startdate").val(formFilter.startdate.value).datepicker("setDate", formFilter.startdate.value);
        $("#filter-member-startdate-mobile").val(formFilter.startdate.value).datepicker("setDate", formFilter.startdate.value);
        $("#filter-member-enddate").val(formFilter.enddate.value).datepicker("setDate", formFilter.enddate.value);
        $("#filter-member-enddate-mobile").val(formFilter.enddate.value).datepicker("setDate", formFilter.enddate.value);

        $(formFilter).on("submit", function() {
            if ($("#filter-member-startdate").val() != formFilter.startdate.value) {
                formFilter.startdate.value = $("#filter-member-startdate").val();
                $("#filter-member-startdate-mobile").val(formFilter.startdate.value);
            }
            if ($("#filter-member-startdate-mobile").val() != formFilter.startdate.value) {
                formFilter.startdate.value = $("#filter-member-startdate-mobile").val();
                $("#filter-member-startdate").val(formFilter.startdate.value);
            }
            if ($("#filter-member-enddate").val() != formFilter.enddate.value) {
                formFilter.enddate.value = $("#filter-member-enddate").val();
                $("#filter-member-enddate-mobile").val(formFilter.enddate.value);
            }
            if ($("#filter-member-enddate-mobile").val() != formFilter.enddate.value) {
                formFilter.enddate.value = $("#filter-member-enddate-mobile").val();
                $("#filter-member-enddate").val(formFilter.enddate.value);
            }
        });
    });

    $(".bs-datepicker").datepicker({
        format: "yyyy-mm-dd",
        language: "ko",
        todayBtn: true,
        orientation: "bottom left"
    });
</script>
<?php
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>