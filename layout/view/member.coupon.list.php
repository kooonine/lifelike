<?php
ob_start();
$g5_title = "쿠폰 조회";
include_once G5_LAYOUT_PATH . "/nav.member.php";
?>
<style>
    #table-member-coupon-list td {
        font-size: 14px;
        color: #000000;
    }

    #coupon-id {
        width: 340px;
        height: 44px;
        border-radius: 2px;
        border: solid 1px #e0e0e0;
        text-align: center;
    }

    #btn-check-coupon {
        width: 340px;
        margin-bottom: 40px;
    }

    #modal-publish-coupon>.modal-dialog,
    #modal-target-coupon>.modal-dialog {
        min-width: 500px;
        width: 500px;
    }

    #modal-target-coupon-content {
        padding: 20px;
    }

    #modal-target-coupon-content>div {
        padding: 6px 0;
    }

    #modal-target-coupon-content>div>a {
        font-size: 16px;
        font-weight: 500;
        color: #828282;
        background: url(/img/re/right_gr@3x.png) center center no-repeat;
        background-size: 7px;
        background-position-x: calc(100%);
        padding-right: 12px;
    }

    #modal-target-coupon-desc {
        border-top: 1px solid #e0e0e0;
    }

    @media (max-width: 1366px) {
        #contents {
            margin-bottom: unset;
        }

        #member-content-wrapper {
            min-height: calc(100vh - 190px);
            background-color: #f2f2f2;
        }

        .member-content-desc {
            font-size: 14px;
            font-weight: normal;
            color: #000000;
        }

        #coupon-id {
            width: calc(100% - 90px);
        }

        #btn-check-coupon {
            margin-left: unset;
        }

        .member-content-title {
            margin-top: 36px !important;
            font-size: 16px !important;
        }

        #table-member-coupon-list th,
        #table-member-coupon-list td {
            font-size: 12px;
            color: #000000;
            text-align: left;
            height: auto;
            padding: 16px 0;
        }

        #table-member-coupon-list th {
            font-size: 14px;
        }

        div.member-content-section {
            margin-bottom: 32px;
        }

        div#member-content-wrapper {
            padding: unset;
        }

        #modal-publish-coupon>.modal-dialog,
        #modal-target-coupon>.modal-dialog {
            min-width: unset;
            width: unset;
        }
    }
</style>
<div id="member-content-wrapper">
    <div class="member-content-section" style="margin-bottom: 16px;">
        <div class="member-content-title on-big">
            쿠폰조회
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
                <button type="button" class="btn-member btn-toggle-coupon-publish" style="margin-left: 14px;">쿠폰등록</button>
            </div>

            <!-- 기간필터 모바일 -->
            <div class="on-small" style="padding: 28px 14px; padding-top: 18px; background-color: #f2f2f2; display: none; justify-content: space-between;">
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

            <div style="text-align: center; margin: 40px 0;" class="on-small">
                <div><button type="button" class="btn-member btn-toggle-coupon-publish" style="font-size: 14px; font-weight: 500; width: 140px;">쿠폰등록</button></div>
            </div>
        </form>
        <? if ($db_coupon->num_rows > 0) : ?>
            <div>
                <div class="on-big" style="margin-top: 30px; border-top: 3px solid #333333;"></div>
                <table>
                    <tr class="on-big">
                        <th>쿠폰종류</th>
                        <th>쿠폰명/사용조건</th>
                        <th>할인혜택</th>
                        <th>적용대상</th>
                        <th>유효기간</th>
                    </tr>
                    <? for ($oi = 0; $coupon = sql_fetch_array($db_coupon); $oi++) : ?>
                        <tr class="on-big">
                            <td style="height: 135px; min-height: 135px; font-weight: normal; font-size: 18px; color: #424242;">
                                <?
                                switch ($coupon['cp_method']) {
                                    case 0:
                                        echo "상품 쿠폰";
                                        break;
                                    case 2:
                                        echo "장바구니 쿠폰";
                                        break;
                                    case 3:
                                        echo "배송비 쿠폰";
                                        break;
                                    case 4:
                                        echo "브랜드 쿠폰";
                                        break;
                                    case 11:
                                        echo "플러스 쿠폰";
                                        break;
                                }
                                ?>
                            </td>
                            <td style="height: unset;">
                                <div style="font-weight: normal; text-align: left; font-size: 18px; color: #424242;"><?= $coupon['cp_subject'] ?>
                                    
                                    (<?= $coupon['CNT_COUPON'] - $coupon['CNT_USED'] ?>/<?= $coupon['CNT_COUPON'] ?>)
                                    
                                </div>
                                <div style="font-weight: normal; text-align: left; font-size: 14px; color: #9f9f9f;"><?= $coupon['cp_desc'] ?></div>
                            </td>
                            <td style="height: 135px; font-weight: 500; font-size: 20px; font-weight: 500; color: #f93f00;"><?= number_format($coupon['cp_price']) ?><?= $coupon['cp_type'] == 1 ? "%" : "원" ?></td>
                            <td style="height: 135px; font-weight: normal; font-size: 16px; color: #333333;">
                                <div>
                                    <?
                                    switch ($coupon['cp_method']) {
                                        case 0:
                                            echo "특정상품";
                                            break;
                                        case 2:
                                        case 3:
                                            echo "전체상품";
                                            break;
                                        case 4:
                                            echo "특정브랜드";
                                            break;
                                        case 11:
                                            echo "특정상품";
                                            break;
                                    }
                                    if (in_array($coupon['cp_method'], array(0, 4, 11))) {
                                    ?>
                                </div>
                                <div>
                                    <button type="button" class="btn-member btn-gray btn-sm btn-toggle-coupon-target" data-target="<?= $coupon['cp_target'] ?>" data-method="<?= $coupon['cp_method'] ?>">상세보기</button>
                                <? } ?>
                                </div>
                            </td>
                            <td style="height: 135px; font-weight: normal; font-size: 14px; color: #606060;">
                                <?= date("Y.m.d", strtotime($coupon['cp_start'])) ?> ~<br><?= date("Y.m.d", strtotime($coupon['cp_end'])) ?>
                            </td>
                        </tr>
                        <tr class="on-small">
                            <td style="border-bottom: unset;">
                                <div style="margin-left: 14px; padding: 20px; width: calc(100% - 28px); background-color: #ffffff; font-size: 12px; font-weight: 500; color: #6b6b6b; line-height: 1.67; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                    <div style="display: flex; justify-content: space-between;">
                                        <span style="font-size: 32px; color: #f14e00;">
                                            <?= number_format($coupon['cp_price']) ?><?= $coupon['cp_type'] == 1 ? "%" : "원" ?>
                                        </span>
                                        <?
                                        if (in_array($coupon['cp_method'], array(0, 4, 11))) {
                                        ?>
                                            <span style="font-size: 12px; color: #9f9f9f;" class="btn-toggle-coupon-target" data-target="<?= $coupon['cp_target'] ?>" data-method="<?= $coupon['cp_method'] ?>">적용대상</span>
                                        <? } ?>

                                    </div>
                                    <div style="font-size: 16px; color: #424242;">
                                        <?= $coupon['cp_subject'] ?> 
                                        
                                        (<?= $coupon['CNT_COUPON'] - $coupon['CNT_USED'] ?>/<?= $coupon['CNT_COUPON'] ?>)
                                        
                                    </div>
                                    <div>
                                        <?= $coupon['cp_desc'] ?>
                                    </div>
                                    <div>
                                        <?= date("Y.m.d", strtotime($coupon['cp_start'])) ?> ~ <?= date("Y.m.d", strtotime($coupon['cp_end'])) ?>
                                    </div>
                                </div>
                                <div style="width: calc(100% - 28px); margin-left: 14px; margin-bottom: 20px; line-height: 35px; text-align: center; color: #ffffff; font-size: 14px; background-color: #959595; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; ">
                                    <?
                                    switch ($coupon['cp_method']) {
                                        case 0:
                                            echo "상품 쿠폰";
                                            break;
                                        case 2:
                                            echo "장바구니 쿠폰";
                                            break;
                                        case 3:
                                            echo "배송비 쿠폰";
                                            break;
                                        case 4:
                                            echo "브랜드 쿠폰";
                                            break;
                                        case 11:
                                            echo "플러스 쿠폰";
                                            break;
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                    <? endfor ?>
                </table>
            </div>
        <? else : ?>
            <div class="member-no-content">
                보유한 쿠폰이 없습니다
            </div>
        <? endif ?>
    </div>
    <div class="on-big" style="font-size: 12px; color: #a1a1a1; margin-bottom: 100px;">
        <span class="dot-desc"></span>사용기간이 만료된 쿠폰은 보유 목록에서 자동으로 삭제됩니다.<br>
        <span class="dot-desc"></span>일부 상품 및 카테고리는 쿠폰 적용이 불가할 수 있으며, 유효기간 및 적용 대상이 변경될 수 있습니다.<br>
        <span class="dot-desc"></span>쿠폰 사용 시, 포인트는 최종 결제금액을 기준으로 적립됩니다.<br>
        <span class="dot-desc"></span>주문결제 화면에서 쿠폰을 적용하면 할인된 금액으로 상품을 구입하실 수 있습니다.<br>
        <span class="dot-desc"></span>주문취소 시 사용한 쿠폰은 유효기간이 만료된 경우 재발급되지 않습니다.
    </div>

    <? if ($paging) : ?>
        <div class="on-big" style="margin-bottom: 170px;"><?= $paging ?></div>
    <? endif ?>
</div>
<div style="margin-bottom: 120px; display: inline-block;"></div>


<!-- 쿠폰등록 -->

<div class="modal fade" id="modal-publish-coupon" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 100%; height: auto; margin-left: unset; padding: unset !important;">
            <div style="text-align: center; padding: 17px 0; border-bottom: 1px solid #e0e0e0; font-size: 26px; font-weight: 500; color: #090909;">
                쿠폰등록
                <button type="button" data-dismiss="modal" aria-label="close" style="width: 18px; height: 18px; background: url(/img/re/cancle@3x.png) center center no-repeat; background-size: contain; position: absolute; right: 12px; top: 15px; border: 0;"></button>
            </div>
            <div id="modal-publish-coupon-content">
                <div style="height: 20px; font-size: 14px; font-weight: 500; font-style: normal; line-height: normal; color: #565656; text-align: center; margin-top: 27px;">발급받으신 쿠폰번호를 입력해주세요</div>
                <div style="text-align: center; margin-bottom: 80px; padding-top: 4px;">
                    <input type="text" id="coupon-id">
                </div>
            </div>
            <div id="modal-publish-coupon-button" style="text-align: center;">
                <button type="button" id="btn-check-coupon" class="btn-member btn-lg" onclick="checkCouponId()">확인</button>
            </div>
        </div>
    </div>
</div>

<!-- 쿠폰등록 모바일 -->



<!-- 쿠폰연결 -->

<div class="modal fade" id="modal-target-coupon" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 100%; height: auto; margin-left: unset; padding: unset !important;">
            <div style="text-align: center; padding: 17px 0; border-bottom: 1px solid #e0e0e0; font-size: 26px; font-weight: 500; color: #090909;">
                <span id="title-target-coupon">특정상품</span>
                <button type="button" data-dismiss="modal" aria-label="close" style="width: 18px; height: 18px; background: url(/img/re/cancle@3x.png) center center no-repeat; background-size: cover; position: absolute; right: 12px; top: 15px; border: unset;"></button>
            </div>
            <div id="modal-target-coupon-content">
            </div>
            <div id="modal-target-coupon-desc" style="padding: 10px 20px 40px 20px; font-size: 12px; font-weight: normal; line-height: 1.5; color: #9f9f9f;">
                <span class="dot-desc"></span>상품 혹은 카테고리를 클릭하시면 해당 페이지로 이동됩니다.<br>
                <span class="dot-desc"></span>일부 상품의 경우 쿠폰 할인 적용대상에서 제외됩니다.
            </div>
        </div>
    </div>
</div>



<script>
    $(".btn-toggle-coupon-publish").on("click", function() {
        $("#modal-publish-coupon").modal("show");
    });
    $(".btn-toggle-coupon-target").on("click", function() {
        const $targetWrapper = $("#modal-target-coupon-content");
        const target = $(this).data("target");
        const listData = {
            it: target,
            method: $(this).data("method")
        };
        let title = "";

        switch ($(this).data("method")) {
            case 0:
                title = "특정상품";
                break;
            case 2:
            case 3:
                title = "전체상품";
                break;
            case 4:
                title = "특정브랜드";
                break;
            case 11:
                title = "특정상품";
                break;
        }

        $targetWrapper.html("");


        $.post('/shop/ajax.coupon.item.php', listData, function(response) {
            if (response.error) {
                alert(response.error);
                return false;
            } else {
                for (ti in response.result) {
                    $targetWrapper.append("<div><a href='" + response.result[ti].link + "'>" + response.result[ti].subject + "</a></div>");
                }
                $("#modal-target-coupon").modal("show");
            }

        }, "JSON");

        $("#title-target-coupon").text(title);
        return true;
    });
    $("#coupon-id").on("keypress", function(key) {
        if ((key.keyCode >= 65 && key.keyCode <= 90) || (key.keyCode >= 97 && key.keyCode <= 122) || (key.keyCode >= 48 && key.keyCode <= 57)) {
            // if ($("#coupon-id").val().length == 4 || $("#coupon-id").val().length == 9 || $("#coupon-id").val().length == 14) {
            //     $("#coupon-id").val($("#coupon-id").val() + '-');
            // } else if ($("#coupon-id").val().length >= 19) {
            //     return false;
            // }
        } else {
            return false;
        }

    });

    function checkCouponId() {
        const couponId = $("#coupon-id").val();

        $("#btn-check-coupon").attr("disabled", true);

        $.ajax({
            type: 'GET',
            data: {
                "cp_id": couponId
            },
            url: '/shop/ajax.couponcheck.php',
            cache: false,
            async: true,
            dataType: 'json',
            success: function(data) {
                $("#btn-check-coupon").removeClass('disabled').attr('disabled', false);
                if (data.error != '' && data.error != null) {
                    alert(data.error);
                    return false;
                }
                if (data.success == 'pointcoupon') {
                    alert('포인트가 지급되었습니다.');
                    location.href = "/member/point.php";
                    return false;
                }

                alert('쿠폰이 발급됐습니다.');
                location.reload();
            },
        });
    }

    $(document).ready(function() {
        const formFilter = document.formMemberFilter;
        $("#filter-member-startdate").val(formFilter.startdate.value).datepicker("setDate", formFilter.startdate.value);
        // $("#filter-member-startdate-mobile").val(formFilter.startdate.value).datepicker("setDate", formFilter.startdate.value);
        $("#filter-member-enddate").val(formFilter.enddate.value).datepicker("setDate", formFilter.enddate.value);
        // $("#filter-member-enddate-mobile").val(formFilter.enddate.value).datepicker("setDate", formFilter.enddate.value);

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