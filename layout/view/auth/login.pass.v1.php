<?php
ob_start();
?>
<form action="" method="POST">
    <input type="hidden" id="form-mb-certify" class="form-input" name="mb_certify" value="PASS">
    <input type="hidden" id="form-mb-dupinfo" class="form-input" name="mb_dupinfo" value="">
    <input type="hidden" id="form-mb-sex-plain" name="mb_sex">
    <input type="hidden" name="mb_id" value="<?= $id; ?>">
    <input type="hidden" name="token" value="<?= $token; ?>">
    <input type="hidden" name="url" value="<?= $url; ?>">

    <span class="form-validation-warn"><img src="/img/re/or.png" srcset="/img/re/or@2x.png 2x,/img/re/or@3x.png 3x"><span class="validation-warn-text"></span></span>
    <div class="layout-offset">
        <div id="join-wrapper">
            <div style="text-align: center; margin-bottom: 35px;" class="on-big">
                <img src="/img/re/logo.png" srcset="/img/re/logo@2x.png 2x,/img/re/logo@3x.png 3x" style="width: 298px;">
            </div>
            <div>
                <button type="button" class="btn btn-pass" onclick="passDoAuth();">PASS 본인인증</button>
            </div>

            <div id="form-mb-info" style="display: none;">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text btn btn-black-2" style="text-align: center;">이름</span>
                    </div>
                    <input type="text" id="form-mb-name" class="form-control form-input" name="mb_name" placeholder="이름" readonly>
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text btn btn-black-2" style="text-align: center;">휴대폰번호</span>
                    </div>
                    <input type="text" id="form-mb-hp" class="form-control form-input no-border-top" name="mb_hp" placeholder="휴대폰번호" readonly>
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text btn btn-black-2" style="text-align: center;">생년월일</span>
                    </div>
                    <input type="text" id="form-mb-birth" class="form-control form-input no-border-top" name="mb_birth" placeholder="생년월일" readonly>
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text btn btn-black-2" style="text-align: center;">성별</span>
                    </div>
                    <input type="text" id="form-mb-sex" class="form-control form-input no-border-top" placeholder="성별" readonly>
                </div>
            </div>

            <div class="Rectangle" style="padding: 14px 20px; margin-bottom: unset;">
                <div>
                    <div class="custom-checkbox C1KOGRL" style="padding-left: 22px;">
                        <input type="checkbox" class="custom-control-input cbg-join-agree-all" id="join-agree-all" data-checkall="cbg-join-agree-all" data-checkgroup="cbg-join-agree-all">
                        <label class="custom-control-label" for="join-agree-all">전체동의</label>
                    </div>
                </div>
                <div>
                    <div class="custom-checkbox C2KOGRL" style="padding-top: 16px; padding-left: 22px;">
                        <input type="checkbox" class="custom-control-input cbg-join-agree-all cbg-join-agree" value="agree" id="join-agree" data-checkall="cbg-join-agree" data-checkgroup="cbg-join-agree-all cbg-join-agree" required>
                        <label class="custom-control-label" for="join-agree">
                            (필수) 이용약관 및 개인정보 수집/이용동의
                        </label>
                    </div>
                    <div style="padding-left: 24px;">
                        <div class="custom-checkbox C2KOGRL" style="padding-top: 8px; padding-left: 22px;">
                            <input type="checkbox" class="custom-control-input cbg-join-agree-all cbg-join-agree" value="agree" id="join-agree-1" data-checkgroup="cbg-join-agree cbg-join-agree-all" required>
                            <label class="custom-control-label" for="join-agree-1">
                                (필수) 이용약관
                            </label>
                            <span class="C2KOMIL float-right" style="cursor: pointer;" onclick="window.open('/shop/licensing.php', '_blank', 'width=600,height=800')">전문보기 &gt;</span>
                        </div>
                        <div class="custom-checkbox C2KOGRL" style="padding-top: 8px; padding-left: 22px;">
                            <input type="checkbox" class="custom-control-input cbg-join-agree-all cbg-join-agree" value="agree" id="join-agree-2" data-checkgroup="cbg-join-agree cbg-join-agree-all" required>
                            <label class="custom-control-label" for="join-agree-2">
                                (필수) 개인정보 수집/이용동의
                            </label>
                            <span class="C2KOMIL float-right" style="cursor: pointer;" onclick="window.open('/shop/privacy.php', '_blank', 'width=600,height=800')">전문보기 &gt;</span>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="custom-checkbox C2KOGRL" style="padding-top: 16px; padding-left: 22px;">
                        <input type="checkbox" class="custom-control-input cbg-join-agree-all cbg-join-agree-mkt" value="agree_mkt" id="join-agree-mkt" data-checkall="cbg-join-agree-mkt" data-checkgroup="cbg-join-agree-all cbg-join-agree-mkt">
                        <label class="custom-control-label" for="join-agree-mkt">
                            (선택) 광고성 정보 수신 동의
                        </label>
                    </div>
                    <div style="padding-left: 24px;">
                        <div class="custom-checkbox C2KOGRL" style="padding-top: 8px; padding-left: 22px;">
                            <input type="checkbox" name="mb_sms" class="custom-control-input cbg-join-agree-all cbg-join-agree-mkt" value=1 id="join-agree-mkt-1" data-checkgroup="cbg-join-agree-mkt cbg-join-agree-all">
                            <label class="custom-control-label" for="join-agree-mkt-1">
                                문자메시지
                            </label>
                        </div>
                        <div class="custom-checkbox C2KOGRL" style="padding-top: 8px; padding-left: 22px;">
                            <input type="checkbox" name="mb_mailling" class="custom-control-input cbg-join-agree-all cbg-join-agree-mkt" value=1 id="join-agree-mkt-2" data-checkgroup="cbg-join-agree-mkt cbg-join-agree-all">
                            <label class="custom-control-label" for="join-agree-mkt-2">
                                이메일
                            </label>
                        </div>
                    </div>
                </div>
                <div style="padding: 16px 0;">
                    <div class="C2KOBLL">개인정보 유효기간</div>
                    <div class="LAKOBLL">개인정보 유효기간 동안 로그인 등 이용이 없는 경우 휴면 계정으로
                        전환됩니다. 설정하지 않으시면 1년으로 유지됩니다.</div>
                    <div class="LAKOBLL">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="exp-privacy-1" name="mb_10" value=1 required checked>
                            <label class="custom-control-label" for="exp-privacy-1" style="line-height: 30px; padding-left: 4px;">
                                1년
                            </label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="exp-privacy-3" name="mb_10" value=3 required>
                            <label class="custom-control-label" for="exp-privacy-3" style="line-height: 30px; padding-left: 4px;">
                                3년
                            </label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="exp-privacy-5" name="mb_10" value=5 required>
                            <label class="custom-control-label" for="exp-privacy-5" style="line-height: 30px; padding-left: 4px;">
                                5년
                            </label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="exp-privacy-0" name="mb_10" value=0 required>
                            <label class="custom-control-label" for="exp-privacy-0" style="line-height: 30px; padding-left: 4px;">
                                탈퇴 시까지
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="btn btn-join btn-mint" style="margin-top: 8px;">회원정보변경</button>
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
    function initPlaceholder() {
        $(".form-placeholder").each(function(pi, pl) {
            let target = $("#" + $(pl).data("target"));
            $(pl).width(target.width()).height(target.css("height")).val(target.prop("placeholder"));
        });
    }

    function showFormValidationWarn(targetElem) {
        const vTargetElem = $(targetElem);

        $(".form-validation-warn > span").text(vTargetElem.data("validation"));
        $(".form-validation-warn").css({
            "top": vTargetElem.offset().top + vTargetElem.height(),
            "left": vTargetElem.offset().left
        }).addClass("active");
    }

    function passDoAuth() {
        const urlPass = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
        const winPass = window.open('', 'popupPass', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');

        $("#form-pass").prop("action", urlPass).prop("target", "popupPass").submit();
    }

    function passResponse(response) {
        if (response.error.length > 0) {
            alert(response.error);
        } else {
            $("#form-mb-name").val(response.data.UTF8_NAME);
            $("#form-mb-hp").val(response.data.MOBILE_NO);
            $("#form-mb-birth").val(response.data.BIRTHDATE);
            $("#form-mb-sex").val(response.data.GENDER);
            $("#form-mb-sex-plain").val(response.data.GENDER_PLAIN);
            $("#form-mb-dupinfo").val(response.data.CI);
            $("#form-mb-info").show();

            if (response.data.OVER14 == false) {
                alert("14세 미만은 가입할 수 없습니다.");
            }
        }

        hasScrollBar();
    }

    $(document).on("load", initPlaceholder()).on("resize", initPlaceholder());
    $(document).ready(function() {
        // showFormValidationWarn("#form-mb-email");
    })
</script>
<?php
$contents = ob_get_contents();
ob_end_clean();

return $contents;
?>;