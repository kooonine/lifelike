<?php
ob_start();
?>
<style>
    button.btn.btn-pass {
        width: 332px;
        height: 80px;
        border-radius: 2px;
        border: solid 1px #333333;
        background-color: #ffffff;
        font-size: 14px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 27px;
        letter-spacing: normal;
        text-align: center;
        color: #424242;
        /* margin-top: 32px; */
        background: url(/img/re/next@3x.png) 302px center no-repeat;
        background-size: 14px 14px;
    }
</style>
<input type="hidden" name="si" value=1>
<div id="login-account-id-wrapper" class="login-account-wrapper <?= $btnActiveId ?>">
    <div class="C1KOBLM" style="margin: 40px 0;">
        휴대폰 인증으로 아이디 또는<br />
        가입된 소셜 로그인 서비스를 찾을 수 있습니다.
    </div>
    <div style="text-align: center;">
        <button type="button" class="btn btn-pass" onclick="passDoAuth();">
            <img src="/img/re/icon_mobile@3x.png" style="width: 28px; height: 45px; margin-right: 4px;">
            휴대폰 인증
        </button>
    </div>
</div>
<?php
$sub_contents = ob_get_contents();
ob_end_clean();

return $sub_contents;
?>;