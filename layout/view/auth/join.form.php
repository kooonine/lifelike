<?php
ob_start();

if ($appleCheck=='apple') {
    $provider = 'apple';
    $is_social_join =true;
    $mb_email = $appleId;
    $appID = explode("@",$appleId);

    $mb_id =  'apple_'.$appID[0];
    // 'http://' . $_SERVER['HTTP_HOST'] . '/bbs/register_form_update.php';
    $join_form_action = 'https://' . $_SERVER['HTTP_HOST'] . '/plugin/social/register_member_update.php';
} 
?>

<style>
    #join-content-wrapper {
        font-size: 26px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: center;
        color: #111111;
    }

    .join-step-title {
        font-size: 26px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: 25px;
        letter-spacing: normal;
        text-align: center;
        color: #0f0f0f;
    }

    .icon-join-step {
        display: inline-block;
        width: 47px;
        height: 47px;
        background-color: #999999;
        font-size: 26px;
        font-weight: 300;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: center;
        color: #ffffff;
        border-radius: 47px;
        line-height: 47px;
    }

    .icon-join-step.active {
        background-color: #333333;
    }

    .label-join-step {
        font-size: 14px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: center;
        color: #999999;
    }

    .label-join-step.active {
        color: #333333;
    }

    button.btn.btn-pass {
        width: 332px;
        height: 80px;
        border-radius: 2px;
        border: solid 1px #333333;
        background-color: #ffffff;
        font-size: 18px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 27px;
        letter-spacing: normal;
        text-align: center;
        color: #424242;
        margin-top: 32px;
        background: url(/img/re/next@3x.png) 302px center no-repeat;
        background-size: 14px 14px;
    }

    #list-join-notice {
        font-size: 12px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.5;
        letter-spacing: normal;
        color: #a2a2a2;

        width: 490px;
        padding-inline-start: unset;
        margin-left: calc(50% - (490px / 2));
    }

    .join-form-wrapper {
        width: 700px;
        border-radius: 2px;
        border: solid 1px #e0e0e0;
        padding: 40px 60px;
    }

    .join-form-wrapper>table {
        width: 100%;
        font-size: 14px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #424242;
    }

    button.btn.btn-join-submit,
    button.btn.btn-join-form {
        width: 110px;
        height: 44px;
        border: unset;
        border-radius: 2px;
        background-color: #333333;
        font-size: 16px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #ffffff;
    }

    button.btn.btn-join-form {
        margin-left: 10px;
    }

    button.btn.btn-join-submit {
        width: 340px;
    }

    #table-join-info>tbody>tr>td {
        padding-bottom: 16px;
    }

    .form-validation-row {
        font-size: 12px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #333333;
        display: none;
    }

    .form-validation-row .passed {
        color: #094283;
    }

    .form-validation-row .not-passed {
        color: #d42e01;
    }

    .input-title {
        height: 18px;
        font-size: 12px;
        font-weight: 400;
        line-height: normal;
        color: #424242;
    }

    .input-join-form {
        float: left;
        width: 335px !important;
    }

    .input-title>td {
        padding-bottom: 4px !important;
    }

    .join-social-request>tbody>.off-social-request {
        display: none;
    }

    @media (max-width: 1366px) {
        .join-form-wrapper {
            width: 100%;
            padding: unset;
            border: unset;
        }

        #join-content-wrapper {
            font-size: 18px;
        }

        .input-join-form {
            float: left;
            width: 100% !important;
            padding: 0 10px !important;
        }


        button.btn.btn-join-submit,
        button.btn.btn-join-form {
            width: 80px;
            font-size: 14px;
        }

        button.btn.btn-join-submit {
            width: 100%;
        }

        input.input-join-form-with-button {
            width: calc(100% - 90px) !important;
        }
    }
</style>

<form id="formPassInfo" name="formPassInfo" action="<?= $join_form_action ?>" method="POST">
    <input type="hidden" name="mb_certify" value="PASS">
    <input type="hidden" name="mb_dupinfo" value="<?= $_POST['mb_dupinfo'] ?>">
    <input type="hidden" name="mb_hp" value="<?= $display_info['hp'] ?>">
    <input type="hidden" name="mb_birth" value="<?= $_POST['mb_birth'] ?>">
    <input type="hidden" name="mb_sex" value="<?= $_POST['mb_sex'] ?>">
    <input type="hidden" name="w" value="">
    <input type="hidden" name="provider" value="<?= $provider ?>">

    <div class="layout-offset" style="padding-bottom: 0;">
        <div id="join-wrapper">
            <div class="on-big" style="margin-bottom: 40px;">
                <div class="join-step-title">회원가입</div>
                <div style="text-align: center; font-size: 0; margin-top: 32px; margin-bottom: 8px;">
                    <span class="icon-join-step">1</span>
                    <span class="icon-join-step active" style="margin: 0 94px;">2</span>
                    <span class="icon-join-step">3</span>
                </div>
                <div style="text-align: center; font-size: 0;">
                    <span class="label-join-step">본인인증</span>
                    <span class="label-join-step active" style="margin: 0 73px;">회원정보입력</span>
                    <span class="label-join-step">가입완료</span>
                </div>
            </div>
            <div id="join-content-wrapper" class="on-small">
                <div style="margin: 28px 0;">회원정보입력</div>
            </div>
            <div class="join-form-wrapper">
                <table id="table-join-info" class="<?= $is_social_join ? "join-social-request" : "" ?>">
                    <tr class="input-title on-small off-social-request">
                        <td>아이디<span class="point-require">*</span></td>
                    </tr>
                    <tr class="off-social-request">
                        <td class="on-big" style="width: 120px;">아이디<span class="point-require">*</span></td>
                        <td>
                            <input class="form-input form-control input-join-form input-join-form-with-button" type="text" name="mb_id" value="<?= $mb_id ?>" placeholder="아이디 입력" data-validation="id" require>
                            <button type="button" class="btn btn-join-form" data-type="id">중복확인</button>
                        </td>
                    </tr>
                    <tr class="form-validation-row validation-id off-social-request" data-target="id">
                        <td class="on-big"></td>
                        <td>
                            <div data-validation="아이디는 영문 또는 영문+숫자 3자리 이상 입력해주세요.">영문 또는 영문+숫자 3자리 이상</div>
                            <div data-validation="아이디 중복확인을 해주세요.">중복확인</div>
                        </td>
                    </tr>
                    <tr class="input-title on-small off-social-request">
                        <td>비밀번호<span class="point-require">*</span></td>
                    </tr>
                    <tr class="off-social-request">
                        <td class="on-big" style="width: 120px;">비밀번호<span class="point-require">*</span></td>
                        <td><input class="form-input form-control input-join-form" type="password" name="mb_password" value="" placeholder="비밀번호 입력" data-validation="password" require></td>
                    </tr>
                    <tr class="form-validation-row validation-password off-social-request" data-target="password">
                        <td class="on-big"></td>
                        <td>
                            <div data-validation="비밀번호는 영문+숫자+특수문자 조합 8자리 이상 입력해주세요.">영문+숫자+특수문자 조합 8자리 이상</div>
                        </td>
                    </tr>
                    <tr class="input-title on-small off-social-request">
                        <td>비밀번호확인<span class="point-require">*</span></td>
                    </tr>
                    <tr class="off-social-request">
                        <td class="on-big" style="width: 120px;">비밀번호확인<span class="point-require">*</span></td>
                        <td><input class="form-input form-control input-join-form" type="password" name="mb_password_re" value="" placeholder="비밀번호 확인" data-validation="password-re" require></td>
                    </tr>
                    <tr class="form-validation-row validation-password-re off-social-request" data-target="password-re">
                        <td class="on-big"></td>
                        <td>
                            <div data-validation="동일한 비밀번호를 입력해주세요">동일한 비밀번호를 입력해주세요</div>
                        </td>
                    </tr>

                    <? if ($is_social_join) : ?>
                        <tr class="input-title on-small">
                            <td>이메일</td>
                        </tr>
                        <tr>
                            <td class="on-big" style="width: 120px;">이메일</td>
                            <td>
                                <input class="form-input form-control input-join-form" type="email" name="mb_email" value="<?= $mb_email ?>" placeholder="이메일 입력" data-validation="email" require readonly>
                            </td>
                        </tr>
                    <? else : ?>
                        <tr class="input-title on-small">
                            <td>이메일<span class="point-require">*</span></td>
                        </tr>
                        <tr>
                            <td class="on-big" style="width: 120px;">이메일<span class="point-require">*</span></td>
                            <td>
                                <input class="form-input form-control input-join-form input-join-form-with-button" type="email" name="mb_email" value="" placeholder="이메일 입력" data-validation="email" require>
                                <button type="button" class="btn btn-join-form" data-type="email">중복확인</button>
                            </td>
                        </tr>
                        <tr class="form-validation-row validation-email off-social-request" data-target="email">
                            <td class="on-big"></td>
                            <td>
                                <div data-validation="이메일 주소를 확인해주세요.">이메일 주소를 입력해주세요</div>
                                <div data-validation="이메일 중복확인을 해주세요.">중복확인</div>
                            </td>
                        </tr>
                    <? endif ?>

                    <tr class="input-title on-small">
                        <td>이름</td>
                    </tr>
                    <tr>
                        <td class="on-big" style="width: 120px;">이름</td>
                        <td><input class="form-input form-control input-join-form" type="text" name="mb_name" value="<?= $_POST['mb_name'] ?>" readonly></td>
                    </tr>
                    <tr class="input-title on-small">
                        <td>휴대폰번호</td>
                    </tr>
                    <tr>
                        <td class="on-big" style="width: 120px;">휴대폰번호</td>
                        <td><input class="form-input form-control input-join-form" type="text" value="<?= $display_info['hp'] ?>" readonly></td>
                    </tr>
                    <tr class="input-title on-small">
                        <td>생년월일</td>
                    </tr>
                    <tr>
                        <td class="on-big" style="width: 120px;">생년월일</td>
                        <td><input class="form-input form-control input-join-form" type="text" value="<?= $display_info['birth'] ?>" readonly></td>
                    </tr>
                    <tr class="input-title on-small">
                        <td>성별</td>
                    </tr>
                    <tr>
                        <td class="on-big" style="width: 120px;">성별</td>
                        <td><input class="form-input form-control input-join-form" type="text" value="<?= $display_info['sex'] ?>" readonly></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="on-small" style="height: 10px; background-color: #f2f2f2; margin-bottom: 20px;"></div>
    <div class="layout-offset" style="padding-top: 0;">
        <div id="join-wrapper">
            <div class="join-form-wrapper">
                <div>
                    <div class="custom-checkbox C1KOGRL" style="padding-left: 22px; color: #333333;">
                        <input type="checkbox" class="custom-control-input cbg-join-agree-all" id="join-agree-all" data-checkall="cbg-join-agree-all" data-checkgroup="cbg-join-agree-all">
                        <label class="custom-control-label" for="join-agree-all">전체동의</label>
                    </div>
                </div>
                <div>
                    <div class="custom-checkbox C2KOGRL" style="padding-top: 16px; padding-left: 22px; color: #333333;">
                        <input type="checkbox" class="custom-control-input cbg-join-agree-all cbg-join-agree" value="agree" id="join-agree" data-checkall="cbg-join-agree" data-checkgroup="cbg-join-agree-all cbg-join-agree" required>
                        <label class="custom-control-label" for="join-agree">
                            (필수) 이용약관 및 개인정보 수집/이용동의
                        </label>
                    </div>
                    <div style="padding-left: 24px;">
                        <div class="custom-checkbox C2KOGRL" style="padding-top: 8px; padding-left: 22px; color: #333333;">
                            <input type="checkbox" class="custom-control-input cbg-join-agree-all cbg-join-agree" value="agree" id="join-agree-1" data-checkgroup="cbg-join-agree cbg-join-agree-all" required>
                            <label class="custom-control-label" for="join-agree-1">
                                (필수) 이용약관
                            </label>
                            <span class="C2KOMIL float-right on-big" style="cursor: pointer;" onclick="modal_privacy('modal_stipulation_on-big')">전문보기 &gt;</span>
                            <span class="C2KOMIL float-right on-small" style="cursor: pointer;" onclick="modal_privacy('modal_stipulation_on-small')">전문보기 &gt;</span>
                        </div>
                        <div class="custom-checkbox C2KOGRL" style="padding-top: 8px; padding-left: 22px; color: #333333;">
                            <input type="checkbox" class="custom-control-input cbg-join-agree-all cbg-join-agree" value="agree" id="join-agree-2" data-checkgroup="cbg-join-agree cbg-join-agree-all" required>
                            <label class="custom-control-label" for="join-agree-2">
                                (필수) 개인정보 수집/이용동의
                            </label>
                            <span class="C2KOMIL float-right on-big" style="cursor: pointer;" onclick="modal_privacy('modal_privacy_on-big')">전문보기 &gt;</span>
                            <span class="C2KOMIL float-right on-small" style="cursor: pointer;" onclick="modal_privacy('modal_privacy_on-small')">전문보기 &gt;</span>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="custom-checkbox C2KOGRL" style="padding-top: 16px; padding-left: 22px; color: #333333;">
                        <input type="checkbox" class="custom-control-input cbg-join-agree-all cbg-join-agree-mkt" value="agree_mkt" id="join-agree-mkt" data-checkall="cbg-join-agree-mkt" data-checkgroup="cbg-join-agree-all cbg-join-agree-mkt">
                        <label class="custom-control-label" for="join-agree-mkt">
                            (선택) 광고성 정보 수신 동의
                        </label>
                    </div>
                    <div style="padding-left: 24px; padding-top: 8px;">
                        <span class="custom-checkbox C2KOGRL" style="padding-left: 22px; color: #333333;">
                            <input type="checkbox" name="mb_sms" class="custom-control-input cbg-join-agree-all cbg-join-agree-mkt" value=1 id="join-agree-mkt-1" data-checkgroup="cbg-join-agree-mkt cbg-join-agree-all">
                            <label class="custom-control-label" for="join-agree-mkt-1">문자메시지</label>
                        </span>
                        <span class="custom-checkbox C2KOGRL" style="margin-left: 72px; padding-left: 22px; color: #333333;">
                            <input type="checkbox" name="mb_mailling" class="custom-control-input cbg-join-agree-all cbg-join-agree-mkt" value=1 id="join-agree-mkt-2" data-checkgroup="cbg-join-agree-mkt cbg-join-agree-all">
                            <label class="custom-control-label" for="join-agree-mkt-2">이메일</label>
                        </span>
                    </div>
                </div>
            </div>
            <div style="text-align: center; margin-top: 32px;">
                <button type="submit" class="btn btn-join-submit">회원가입</button>
            </div>
        </div>
    </div>
</form>


<!-- PASS 본인인증 FORM -->
<form id="form-pass" method="post">
    <input type="hidden" name="m" value="checkplusService">
    <input type="hidden" name="EncodeData" value="<?php echo $enc_data ?>">
</form>

<script type="text/javascript">
    $("#table-join-info input").on("focus", function() {
        if ($(this).data("validation")) {
            const parentRow = $(this).closest("tr");

            $(parentRow).children("td").css("paddingBottom", "unset");
            $(parentRow).next("tr.form-validation-row").show();
        }
    });

    function doValidation(validationElem) {
        const target = $(validationElem).data("validation");
        const $targetElem = $(".form-validation-row.validation-" + target).find("div").first();
        const $checkElem = $(".form-validation-row.validation-" + target).find("div").eq(1);
        const testValue = $(validationElem).val();
        let vRule = new RegExp;
        let vResult = false;

        if ($checkElem.length > 0) {
            $checkElem.addClass("not-passed").removeClass("passed");
        }

        switch (target) {
            case "id":
                vRule = /[^0-9_a-z]+/i;
                vResult = !vRule.test(testValue);
                if (testValue.length < 3) vResult = false;
                break;
            case "password":
                const vRule1 = /[!*@#$%^&+=_]+/;
                const vRule2 = /[0-9]+/;
                const vRule3 = /[a-z]+/i;
                vResult = vRule1.test(testValue) == vRule2.test(testValue) == vRule3.test(testValue) == true;
                if (testValue.length < 8) vResult = false;
                break;
            case "password-re":
                const password = formPassInfo.mb_password.value;
                vResult = password == testValue;
                break;
            case "email":
                vRule = /([0-9a-zA-Z_-]+)@([0-9a-zA-Z_-]+)\.([0-9a-zA-Z_-]+)/;
                vResult = vRule.test(testValue);
                break;
        }

        if (vResult) {
            $targetElem.addClass("passed").removeClass("not-passed");
        } else {
            $targetElem.removeClass("passed").addClass("not-passed");
        }
    }

    function passDoAuth() {
        const urlPass = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
        const winPass = window.open('', 'popupPass', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');

        $("#form-pass").prop("action", urlPass).prop("target", "popupPass").submit();
    }

    $(document).ready(function() {
        $("#table-join-info input").each(function(ii, ie) {
            if ($(ie).data("validation")) {
                $(ie).on("keyup", function() {
                    doValidation(ie);
                }).on("blur", function() {
                    doValidation(ie);
                });
            }
        });

        $(".btn-join-form").on("click", function() {
            const button = $(this);
            button.attr("disabled", true);

            const url = "/auth/ajax.auth.php";
            const type = $(this).data("type");
            const id = formPassInfo.mb_id.value;
            const email = formPassInfo.mb_email.value;
            const data = {
                "action": "check_" + type,
                "id": id,
                "email": email
            };

            if (type == "id" && id.length == 0) {
                alert("아이디를 입력해주세요");
                formPassInfo.mb_id.focus();
                return button.removeAttr("disabled");
            }
            if (type == "email" && email.length == 0) {
                alert("메일주소를 입력해주세요");
                formPassInfo.mb_id.focus();
                return button.removeAttr("disabled");
            }

            $.get(url, data, function(response) {
                let alertMsg = "";

                if (response.result === true) {
                    switch (type) {
                        case "id":
                            alertMsg = "해당 아이디는 사용이 가능합니다";
                            break;
                        case "email":
                            alertMsg = "해당 메일 주소는 사용이 가능합니다";
                            break;
                    }

                    const $checkElem = $(".form-validation-row.validation-" + type).find("div").eq(1);
                    $checkElem.removeClass("not-passed").addClass("passed");
                } else {
                    switch (type) {
                        case "id":
                            alertMsg = "이미 사용중인 아이디입니다";
                            break;
                        case "email":
                            alertMsg = "해당 메일 주소로 가입내역이 있습니다";
                            break;
                    }
                }

                alert(alertMsg);
                button.removeAttr("disabled");

            }, "JSON");
        });


        $("#formPassInfo").on("submit", function() {
            const validationRow = $(".form-validation-row");
            const isSocial = $("#table-join-info").hasClass("join-social-request");

            let validationReturn = isSocial;

            if (validationReturn == false) {
                validationRow.each(function(vi, ve) {
                    const countValidation = $(ve).find("div").length;
                    const countPassed = $(ve).find("div.passed").length;
                    const countNotPassed = $(ve).find("div.not-passed").length;

                    if (countPassed + countNotPassed == 0) {
                        const target = $(".input-join-form[data-validation='" + $(ve).data("target") + "']");
                        const st = target.offset().top - 300;
                        $("html, body").animate({
                            scrollTop: st
                        }, 500);
                        target.focus();

                        return false;

                    } else if (countValidation != countPassed) {
                        $(ve).find("div").each(function(di, de) {
                            if ($(de).hasClass("not-passed")) {
                                const target = $(".input-join-form[data-validation='" + $(ve).data("target") + "']");
                                const message = $(de).data("validation");
                                const st = target.offset().top - 300;

                                alert(message);

                                $("html, body").animate({
                                    scrollTop: st
                                }, 500);
                                target.focus();

                            }
                        });

                        return false;
                    } else {
                        validationReturn = true;
                    }
                });
            }

            return validationReturn;
        });
    });
</script>
<?php
$contents = ob_get_contents();
ob_end_clean();

return $contents;
?>;