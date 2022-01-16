<?php
ob_start();
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
        word-break: keep-all;
    }

    @media (max-width: 1366px) {
        button.btn.btn-pass {
            font-size: 14px;
            margin-top: unset;
        }

        #join-content-wrapper {
            font-size: 18px;
        }

        #list-join-notice {
            width: calc(100% - 14px);
            font-size: 10px;
            font-weight: 200;
            font-stretch: normal;
            font-style: normal;
            line-height: 1.8;
            letter-spacing: normal;
            color: #a2a2a2;
            margin-left: 14px;
        }
    }
</style>

<form id="formPassInfo" method="POST">
    <input type="hidden" id="form-mb-certify" name="mb_certify" value="PASS">
    <input type="hidden" id="form-mb-dupinfo" name="mb_dupinfo">
    <input type="hidden" id="form-mb-sex-plain" name="mb_sex">
    <input type="hidden" id="form-mb-name" name="mb_name">
    <input type="hidden" id="form-mb-hp" name="mb_hp">
    <input type="hidden" id="form-mb-birth" name="mb_birth">
    <input type="hidden" name="mb_id" value="<?= $id; ?>">
    <input type="hidden" name="token" value="<?= $token; ?>">
    <input type="hidden" name="url" value="<?= $url; ?>">

    <div class="layout-offset">
        <div id="join-wrapper">
            <div class="on-big">
                <div class="join-step-title">회원가입</div>
                <div style="text-align: center; font-size: 0; margin-top: 32px; margin-bottom: 8px;">
                    <span class="icon-join-step active">1</span>
                    <span class="icon-join-step" style="margin: 0 94px;">2</span>
                    <span class="icon-join-step">3</span>
                </div>
                <div style="text-align: center; font-size: 0;">
                    <span class="label-join-step active">본인인증</span>
                    <span class="label-join-step" style="margin: 0 73px;">회원정보입력</span>
                    <span class="label-join-step">가입완료</span>
                </div>
            </div>
            <div id="join-content-wrapper" class="on-small">
                <div style="margin: 28px 0;">본인인증</div>
            </div>
            <div style="text-align: center; margin-bottom: 24px;">
                <button type="button" class="btn btn-pass" onclick="passDoAuth();">
                    <img src="/img/re/icon_mobile@3x.png" style="width: 28px; height: 45px; margin-right: 4px;">
                    휴대폰 인증
                </button>
            </div>
            <div>
                <ul id="list-join-notice">
                    <li>인증완료 시 이름/휴대폰번호는 인증된 정보로 갱신되며, 생일 및 통신사 정보가 추가로 수집됩니다.</li>
                    <li>정보통신망법(2012.08.18 시행) 제 23조 2(주민번호 사용제한) 규정에 따라 온라인 상 주민번호의 수집/이용을 제한합니다.</li>
                    <li>만 14세 미만은 회원가입을 제한합니다.</li>
                </ul>
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
            $("#form-mb-sex-plain").val(response.data.GENDER_PLAIN);
            $("#form-mb-dupinfo").val(response.data.CI);

            if (response.data.OVER14 == false) {
                alert("14세 미만은 가입할 수 없습니다.");
            }

            return $("#formPassInfo").submit();
        }
    }
</script>
<?php
$contents = ob_get_contents();
ob_end_clean();

return $contents;
?>;