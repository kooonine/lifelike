<?php
$g5_title = "회원 탈퇴";
ob_start();
include_once G5_LAYOUT_PATH . "/nav.member.php";
?>
<style>
    .row-password {
        display: none;
    }

    .row-password.active {
        display: table-row;
    }

    .member-content-sub-title {
        line-height: 54px;
        font-size: 16px;
        font-weight: bold;
        color: #000000;
        border-top: 1px solid #000000;
        border-bottom: 1px solid #e0e0e0;
        margin-top: 64px;
    }

    .member-content-sub-title.first {
        margin-top: unset;
    }

    .member-content-desc {
        padding-top: 0;
        font-weight: 400;
    }

    .member-content-desc table>tbody>tr>td {
        font-size: 14px;
        height: 44px;
    }

    .member-content-desc table>tbody>tr:first-child>td {
        padding-top: 14px;
    }

    .member-content-desc label {
        font-size: 14px;
        color: #7f7f7f;
    }

    .member-content-desc:last-child {
        padding-bottom: 60px;
    }

    .member-content-title {
        margin-top: 80px;
    }

    textarea {
        margin-top: 20px;
    }

    #member-withdraw-policy {
        font-size: 18px;
    }

    #member-withdraw-reason {
        border: 1px solid #e0e0e0;
        padding: 50px 60px;
    }

    @media (max-width: 1366px) {

        .mo_hard_line,
        .nav-samll-member-top {
            display: none;
        }

        .member-content-desc {
            border-top: 10px solid #f0f0f0;
            padding: 20px 14px;
            padding-top: unset;
            font-size: 12px;
            font-weight: 400;
        }

        .member-content-sub-title {
            font-size: 16px;
            font-weight: 400;
            border: unset;
            margin: unset;
        }

        textarea {
            margin-top: unset;
        }

        .custom-control.custom-checkbox.custom-control-inline {
            width: calc(100vw / 2 - 40px);
        }

        #member-withdraw-policy {
            font-size: 12px;
        }

        #member-withdraw-reason {
            border: unset;
            padding: unset;
        }

        #member-reason-check>div {
            margin-bottom: 20px;
            font-size: 12px;
        }

        #member-withdraw-reason label {
            font-size: 12px;
            line-height: 22px;
        }
    }
</style>
<div id="member-content-wrapper">
    <form action="/member/withdraw.php" method="POST" id="form-account" name="form_account">
        <input type="hidden" name="confirm" value="1">
        <div class="member-content-title on-big" style="margin-top: unset;">
            회원 탈퇴
        </div>
        <div class="member-content-desc on-big">
            불편 사항이 있으시다면 고객센터(1661-3353)로 연락주세요.<br>
            서비스 개선을 위해 최선을 다 하겠습니다.
        </div>
        <div class="member-content-desc on-small" style="margin-top: 20px; border-top: unset;">
            불편 사항이 있으시다면 고객센터(1661-3353)로 연락주세요.<br>
            서비스 개선을 위해 최선을 다 하겠습니다.
        </div>
        <div class=" member-content-title on-big" style="border-bottom: unset;">
            소멸 예정 혜택 안내
        </div>
        <div class="member-content-desc" style="padding-bottom: unset;">
            <div class="member-content-sub-title on-small">소멸 예정 혜택 안내</div>
            <div class="on-big">
                <table style="background-color: #f2f2f2;">
                    <tr>
                        <td colspan=3 style="height: 28px;"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-size: 18px; font-weight: 500; color: #4c4c4c; border-right: 1px solid #333333; width: 410px; padding-left: 50px;">포인트</td>
                        <td style="text-align: center; font-size: 18px; font-weight: 500; color: #4c4c4c; border-right: 1px solid #333333; width: 360px;">쿠폰</td>
                        <td style="text-align: center; font-size: 18px; font-weight: 500; color: #4c4c4c; width: 410px; padding-right: 50px;">주문</td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-size: 28px; font-weight: 500; color: #4c4c4c; border-right: 1px solid #333333; width: 410px; padding-left: 50px;"><?= number_format($member['mb_point']) ?>P</td>
                        <td style="text-align: center; font-size: 28px; font-weight: 500; color: #4c4c4c; border-right: 1px solid #333333; width: 360px;"><?= number_format($count['coupon']) ?>장</td>
                        <td style="text-align: center; font-size: 28px; font-weight: 500; color: #4c4c4c; width: 410px; padding-right: 50px;"><?= number_format($count['order']) ?>건</td>
                    </tr>
                    <tr>
                        <td colspan=3 style="height: 28px;"></td>
                    </tr>
                </table>
            </div>
            <table class="on-small" style="width: 100%; margin-bottom: 10px;">
                <tr>
                    <td style="font-size: 14px; font-weight: 500; padding: unset; border-bottom: 1px solid #e0e0e0; color: #333333; text-align: left;">포인트</td>
                    <td style="font-size: 14px; font-weight: 500; padding: unset; border-bottom: 1px solid #e0e0e0; color: #f93f00; text-align: right;"><?= number_format($member['mb_point']) ?>P</td>
                </tr>
                <tr>
                    <td style="font-size: 14px; font-weight: 500; padding: unset; border-bottom: 1px solid #e0e0e0; color: #333333; text-align: left;">쿠폰</td>
                    <td style="font-size: 14px; font-weight: 500; padding: unset; border-bottom: 1px solid #e0e0e0; color: #f93f00; text-align: right;"><?= number_format($count['coupon']) ?> 장</td>
                </tr>
                <tr>
                    <td style="font-size: 14px; font-weight: 500; padding: unset; color: #333333; text-align: left;">주문</td>
                    <td style="font-size: 14px; font-weight: 500; padding: unset; color: #f93f00; text-align: right;"><?= number_format($count['order']) ?> 건</td>
                </tr>
            </table>
        </div>
        <div class="member-content-title on-big">
            회원 탈퇴 규정 안내
        </div>
        <div class="member-content-desc" id="member-withdraw-policy">
            <div class="member-content-sub-title on-small">회원 탈퇴 규정 안내</div>
            <div style="color: #fa3f00;">구매 확정 처리가 되지 않았거나, 반품/교환 중의 상품이 있을 경우 회원 탈퇴가 불가합니다.</div>
            <div style="color: #565656;">
                회원 탈퇴 후 30일간 재가입이 제한됩니다.<br>
                회원 탈퇴 시 보유 중인 쿠폰과 포인트는 자동 소멸됩니다.<br>
                기 참여한 이벤트 당첨자에서 자동 제외됩니다.<br>
                전자상거래등에서의 소비자보호에 관한 법률에 따라 주문 이력이 있는 고객의 정보는 일정기간 보관될 수 있습니다.<br>
                회원 탈퇴 승인 시까지 소요되는 기간에 따라 정보성 광고 메일 및 문자가 전송 될 수 있습니다.
            </div>
        </div>
        <div class="member-content-title on-big">
            회원 탈퇴 신청
        </div>
        <div class="member-content-desc">
            <div class="member-content-sub-title on-small">회원 탈퇴 신청</div>
            <div id="member-withdraw-reason">
                <table style="width: 100%;">
                    <tr class="on-small">
                        <td style="height: auto; padding-top: 0; padding-bottom: 8px; font-size: 12px;">탈퇴 사유 선택(중복 선택 가능)</td>
                    </tr>
                    <tr>
                        <td style="width: 120px; vertical-align: top;" class="on-big">
                            탈퇴 사유 선택<br>
                            (중복 선택 가능)
                        </td>
                        <td>
                            <div id="member-reason-check">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" id="withdraw-1" name="mb_4[]" value="배송">
                                    <label class="custom-control-label" for="withdraw-1">배송</label>
                                </div>
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" id="withdraw-2" name="mb_4[]" value="취소교환반품">
                                    <label class="custom-control-label" for="withdraw-2">취소/교환/반품</label>
                                </div>
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" id="withdraw-3" name="mb_4[]" value="상품다양성부족">
                                    <label class="custom-control-label" for="withdraw-3">상품 다양성 부족</label>
                                </div>
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" id="withdraw-4" name="mb_4[]" value="가격">
                                    <label class="custom-control-label" for="withdraw-4">가격</label>
                                </div>
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" id="withdraw-5" name="mb_4[]" value="혜택부족">
                                    <label class="custom-control-label" for="withdraw-5">혜택 부족</label>
                                </div>
                                <div class="on-big"><br></div>
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" id="withdraw-6" name="mb_4[]" value="사이트이용">
                                    <label class="custom-control-label" for="withdraw-6">사이트 이용</label>
                                </div>
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" id="withdraw-7" name="mb_4[]" value="시스템">
                                    <label class="custom-control-label" for="withdraw-7">시스템</label>
                                </div>
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" id="withdraw-8" name="mb_4[]" value="이용빈도낮음">
                                    <label class="custom-control-label" for="withdraw-8">이용 빈도 낮음</label>
                                </div>
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" id="withdraw-9" name="mb_4[]" value="기타">
                                    <label class="custom-control-label" for="withdraw-9">기타</label>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="on-small">
                        <td style="border-top: 1px solid #e0e0e0; height: auto; padding-top: 20px; font-size: 12px;">고객의견</td>
                    </tr>
                    <tr>
                        <td style="height: auto; vertical-align: baseline; padding-top: 20px;" class="on-big">고객 의견</td>
                        <td>
                            <div>
                                <textarea id="review-content" name="mb_5" style="width: 100%; height: 200px; font-size: 14px; border: 1px solid #e0e0e0; border-radius: 2px; font-weight: normal; box-shadow: unset; padding: 16px;" placeholder="고객님의 의견을 기재해주시면 더 나은 서비스 개발을 위해 노력하겠습니다." onkeyup="$('#reason-length').text($(this).val().length);" onblur="$('#reason-length').text($(this).val().length);"></textarea>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="withdraw-agree-1" value="1">
                                        <label class="custom-control-label" for="withdraw-agree-1">(필수) 회원탈퇴를 위한 안내를 모두 확인하였으며 이에 동의합니다.</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="withdraw-agree-2" value="1">
                                        <label class="custom-control-label" for="withdraw-agree-2">(필수) 보유 쿠폰, 포인트 및 이벤트 참여 혜택 자동 소멸에 동의합니다.</label>
                                    </div>
                                </span>
                                <span class="on-big"><button class="btn-member" type="submit">탈퇴 신청</button></span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>


        </div>
        <div class="on-small" style="display: flex; justify-content: space-between; border-top: 10px solid #e0e0e0; padding: 20px 14px;">
            <button class="btn-member btn-white" style="font-size: 14px; width: calc(50% - 7px);" type=" button">취소</button>
            <button class="btn-member" style="font-size: 14px; width: calc(50% - 7px); margin-left: 14px;" type="submit">탈퇴 신청</button>
        </div>
    </form>
</div>

<script>
    const formAccount = $(" #form-account");
    formAccount.on("submit", function() {
        if ($("#withdraw-agree-1:checked").length <= 0) {
            alert("회원탈퇴 안내 확인에 동의가 필요합니다.");
            $("#withdraw-agree-1").focus();
            return false;
        }
        if ($("#withdraw-agree-2:checked").length <= 0) {
            alert("혜택 자동 소멸에 동의가 필요합니다.");
            $("#withdraw-agree-2").focus();
            return false;
        }
        if (confirm("회원 탈퇴를 진행하시겠습니까?")) return true;
        return false;
    });
</script> <?php
            $contents = ob_get_contents();
            ob_end_clean();

            return $contents;
            ?>;