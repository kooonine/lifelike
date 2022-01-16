<?php
ob_start();
$g5_title = '포인트 조회';
include_once G5_LAYOUT_PATH . '/nav.member.php';
?>
<style>
    #table-member-coupon-list td {
        font-size: 14px;
        color: #000000;
    }

    div.member-content-section tr>td {
        height: 76px;
    }

    .member-point-notice-title {
        font-size: 16px;
        font-weight: bold;
        color: #000000;
        margin-top: 20px;
    }

    .member-point-notice {
        font-size: 14px;
        font-weight: normal;
        color: #000000;
        margin-bottom: 24px;
    }

    #member-point-notice-desc {
        display: inline-block;
        font-size: 14px;
        color: #000000;
        background-color: #f7fafb;

        padding: 28px 18px;
    }

    @media (max-width: 1366px) {
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

        #member-content-wrapper {
            padding: 0;
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

        #member-point-notice {
            background-color: #f2f2f2;
            padding: 24px 20px;
        }

        #member-point-notice-desc {
            background-color: #ffffff;
            font-size: 12px;
            padding: 16px;
        }

        .member-point-notice-title {
            font-size: 12px;
            font-weight: normal;
        }

        .member-point-notice {
            font-size: 12px;
            color: #7f7f7f;
        }

        .mo_hard_line,
        .nav-samll-member-top {
            display: none;
        }
    }
</style>
<div id="member-content-wrapper">
    <div class="member-content-title on-big" style="border: unset;">
        포인트 조회
    </div>
    <div class="member-content-desc on-big" style="padding-top: unset;">
        <table style="background-color: #f2f2f2;">
            <tr>
                <td colspan=3 style="height: 28px;"></td>
            </tr>
            <tr>
            <td style="text-align: center; font-size: 18px; font-weight: 500; color: #4c4c4c; border-right: 1px solid #333333; width: 410px; padding-left: 50px;">적립 포인트</td>
                <td style="text-align: center; font-size: 18px; font-weight: 500; color: #4c4c4c; border-right: 1px solid #333333; width: 360px;">적립 예정 포인트</td>
                <td style="text-align: center; font-size: 18px; font-weight: 500; color: #4c4c4c; width: 410px; padding-right: 50px;">소멸예정(30일 이내)포인트</td>
                <!-- <td style="text-align: center; font-size: 18px; font-weight: 500; color: #4c4c4c; border-right: 1px solid #333333; width: 410px; padding-left: 50px;">적립 포인트<img src="/img/re/next@3x.png" style="width: 18px; height: 18px; margin-left: 4px;"></td>
                <td style="text-align: center; font-size: 18px; font-weight: 500; color: #4c4c4c; border-right: 1px solid #333333; width: 360px;">적립 예정 포인트<img src="/img/re/next@3x.png" style="width: 18px; height: 18px; margin-left: 4px;"></td>
                <td style="text-align: center; font-size: 18px; font-weight: 500; color: #4c4c4c; width: 410px; padding-right: 50px;">소멸예정(30일 이내)포인트<img src="/img/re/next@3x.png" style="width: 18px; height: 18px; margin-left: 4px;"></td> -->
            </tr>
            <tr>
                <td style="text-align: center; font-size: 28px; font-weight: 500; color: #4c4c4c; border-right: 1px solid #333333; width: 410px; padding-left: 50px;"><?= number_format($member['mb_point']) ?>P</td>
                <td style="text-align: center; font-size: 28px; font-weight: 500; color: #4c4c4c; border-right: 1px solid #333333; width: 360px;"><?= number_format($point_income) ?>P</td>
                <td style="text-align: center; font-size: 28px; font-weight: 500; color: #4c4c4c; width: 410px; padding-right: 50px;"><?= number_format($point_expire) ?>P</td>
            </tr>
            <tr>
                <td colspan=3 style="height: 28px;"></td>
            </tr>
        </table>
    </div>
    <!-- 기간필터 -->

    <form action="" name="formMemberFilter" id="form-member-filter">
        <input type="hidden" name="startdate" value="<?= $startdate ?>">
        <input type="hidden" name="enddate" value="<?= $enddate ?>">

        <div class="on-big" style="font-weight: 500; padding: 20px 28px; margin-top: 16px; padding-top: 14px; border: solid 1px #f2f2f2; background-color: #f2f2f2;">
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

        <div class="member-content-desc on-small" style="padding-top: unset;">
            <table style="width: 100%;">
                <tr>
                    <td style="height: auto; font-size: 14px; font-weight: 500; color: #333333; border-bottom: 1px solid #e0e0e0; padding: 10px 14px; padding-top: 16px; ">적립 포인트</td>
                    <td style="height: auto; font-size: 16px; font-weight: 500; color: #333333; border-bottom: 1px solid #e0e0e0; padding: 10px 14px; padding-top: 16px; text-align: right; width: 120px;"><?= number_format(
                                                                                                                                                                                                                $member['mb_point']
                                                                                                                                                                                                            ) ?>P</td>
                </tr>
                <tr>
                    <td style="height: auto; font-size: 14px; font-weight: 500; color: #333333; border-bottom: 1px solid #e0e0e0; padding: 10px 14px; ">적립 예정 포인트</td>
                    <td style="height: auto; font-size: 16px; font-weight: 500; color: #333333; border-bottom: 1px solid #e0e0e0; padding: 10px 14px; text-align: right; width: 120px;"><?= number_format(
                                                                                                                                                                                            $point_income
                                                                                                                                                                                        ) ?>P</td>
                </tr>
                <tr>
                    <td style="height: auto; font-size: 14px; font-weight: 500; color: #333333; padding: 10px 14px; padding-bottom: 16px; ">소멸예정(30일 이내) 포인트</td>
                    <td style="height: auto; font-size: 16px; font-weight: 500; color: #333333; padding: 10px 14px; padding-bottom: 16px; text-align: right; width: 120px;"><?= number_format(
                                                                                                                                                                                $point_expire
                                                                                                                                                                            ) ?>P</td>
                </tr>
            </table>
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
    <div class="member-content-section" style="margin-bottom: 20px;">
        <div style="margin-top: 30px; border-top: 3px solid #333333;" class="on-big"></div>
        <? if ($db_point->num_rows > 0) : ?>
            <table id="table-member-point-list">
                <tr class="on-big">
                    <th style="color: #606060; width: 220px">발생일자</th>
                    <th style="color: #606060;">내역</th>
                    <th style="color: #606060; width: 240px">주문번호</th>
                    <th style="color: #606060; width: 220px">적립/사용</th>
                </tr>
                <?= include_once G5_VIEW_PATH . '/member.point.list.item.php' ?>
            </table>
        <? else : ?>
            <div class="member-no-content">
                조회된 내역이 없습니다
            </div>
        <? endif ?>
    </div>
    <div class="member-content-section on-big" style="opacity: 0.79; font-size: 12px; font-weight: normal; line-height: 1.5; color: #b4b4b4;">
        <span class="dot-desc"></span>포인트는 상품 구매나 상품리뷰 작성 등을 통해 적립이 가능합니다.<br>
        <span class="dot-desc"></span>포인트는 1P는 현금 1원과 같으며, 현금 및 다른 혜택으로의 전환은 불가합니다.<br>
        <span class="dot-desc"></span>주문취소 시, 포인트 사용금액도 취소됩니다.<br>
        <span class="dot-desc"></span>유효기간은 각 포인트에 따라 다르며, 유효기간이 지나면 자동 소멸됩니다.
    </div>
    <? if ($paging) : ?>
        <div style="margin-bottom: 170px;"><?= $paging ?></div>
    <? endif ?>
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