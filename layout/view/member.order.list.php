<?php
ob_start();
$g5_title = "주문조회";
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
            주문/배송조회
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
                        <input type="text" id="filter-member-startdate-mobile" autocomplete="off" class="bs-datepicker" style="width: 100px; height: 28px; font-size: 12px; border: 1px solid #333333; border: 1px solid #333333; background: #ffffff; padding: 0 14px; line-height : 28px;">
                        <span style="font-size: 12px; margin: 0 8px; padding-top: 4px;">~</span>
                        <input type="text" id="filter-member-enddate-mobile" autocomplete="off" class="bs-datepicker" style="width: 100px; height: 28px; font-size: 12px; border: 1px solid #333333; border: 1px solid #333333; background: #ffffff; padding: 0 14px; line-height : 28px;">
                    </div>
                </span>
                <span style="width: 100px;">
                    <button type="submit" class="btn-member btn-white" style="width: 100px; height: 100%;">조회</button>
                </span>
            </div>
        </form>
        <? if ($db_order->num_rows > 0) : ?>
            <div class="on-big" style="margin-top: 30px; border-top: 3px solid #333333;"></div>
            <table id="add_orderMobile">
                <tr class="on-big">
                    <th>주문번호(주문일)</th>
                    <th colspan=2>상품정보</th>
                    <th>결제금액(수량)</th>
                    <th>주문상태</th>
                </tr>
                <? include_once "member.order.list.item.php" ?>
                <tbody></tbody>
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
        <div class="member-content-title on-big">주문 안내</div>
        <div style="height: 285px; border-radius: 2px; border: solid 1px #e0e0e0; padding: 50px 150px;" class="on-big">
            <table style="width:100%;">
                <tr>
                    <td colspan=4>
                        <span class="icon-order-step" style="margin-left: 41px;">1</span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step">2</span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step">3</span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step-dot"></span>
                        <span class="icon-order-step">4</span>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; width: 130px; font-size: 14px; color: #333333; font-weight: 500; vertical-align: top; padding-top: 8px;">
                        <div>
                            결제완료
                        </div>
                        <div style="color: #999999; font-weight: normal;">
                            주문접수 및 결제 완료
                        </div>
                    </td>
                    <td style="text-align: center; width: 279px; font-size: 14px; color: #333333; font-weight: 500; vertical-align: top; padding-left: 70px; padding-top: 8px;">
                        <div>
                            배송준비중
                        </div>
                        <div style="color: #999999; font-weight: normal;">
                            주문 확인 후 배송준비중<br>(배송 지연 및 품절 발생 가능)
                        </div>
                    </td>
                    <td style="text-align: center; width: 279px; font-size: 14px; color: #333333; font-weight: 500; vertical-align: top; padding-right: 10px; padding-top: 8px;">
                        <div>
                            배송중
                        </div>
                        <div style="color: #999999; font-weight: normal;">
                            택배사로 상품 전달 후<br>배송 시작
                        </div>
                    </td>
                    </td>
                    <td style="text-align: center; width: 190px; font-size: 14px; color: #333333; font-weight: 500; vertical-align: top; padding-top: 8px;">
                        <div>
                            배송완료
                        </div>
                        <div style="color: #999999; font-weight: normal; letter-spacing: -0.4px; padding-bottom: 30px;">
                            고객님께 상품 배송 완료<br>(상품리뷰 작성 가능, 포인트 지급)
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 130px; height: 15px; border-left: 1px solid #eb6000; padding: 7px 0;">
                        <div style="border-bottom: 1px solid #eb6000;"></div>
                    </td>
                    <td colspan=2 style="height: 15px; border-left: 1px solid #eb6000; padding: 7px 0;">
                        <div style="border-bottom: 1px solid #eb6000;"></div>
                    </td>
                    <td style="width: 190px; height: 15px; border-left: 1px solid #eb6000; border-right: 1px solid #eb6000; padding: 7px 0;">
                        <div style="border-bottom: 1px solid #eb6000;"></div>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; letter-spacing: -0.4px; width: 130px; font-size: 12px; color: #eb6000;">주문취소/배송지 변경 가능</td>
                    <td colspan=2 style="text-align: center; font-size: 12px; color: #eb6000;">교환/반품 신청 가능</td>
                    <td style="text-align: center; width: 190px; font-size: 12px; color: #eb6000;">구매확정 가능</td>
                </tr>
            </table>
        </div>
        <div class="on-small add_item_btn" style=" clear: both; <?if( $total_page < 2) : ?> display: none ; <? endif ?>" ><a onclick="addItem('<?= $startdate ?>', '<?= $enddate ?>', <?= $total_page ?>)">더보기</a></div>
        <br><br><br><br><br>
        <!-- 주문안내 모바일 -->
        <div class="member-content-title on-small" style="background-color: #f2f2f2; display: flex; padding: 14px; margin-top: 20px">
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

    function writeQuestion(odid) {
        if (!odid) odid = 0;
        return window.location.href = '/member/customer.php?od_id=' + odid;
    }

    function writeReview(ctid) {
        if (!ctid) ctid = 0;
        return window.location.href = '/member/review.php?ct_id=' + ctid;
    }
    let add_page = 2;
    function addItem(startdate, enddate, total_page) {
        if (total_page < add_page) {
            alert("등록된 상품이 없습니다.")
        } else {
            $.ajax({
                url: '/shop/ajax.order.list.new.php',
                type: 'post',
                dataType: "html",
                async: true,
                data: {
                    startdate: startdate,
                    enddate: enddate,
                    add_page: add_page
                },

                success: function(response) {
                    $('#add_orderMobile > tbody:last').append(response);
                    add_page++;
                }
            });
            if (add_page >= total_page) {
                $('.add_item_btn').css('display', 'none');
            }
        }
    }
</script>
<?php
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>