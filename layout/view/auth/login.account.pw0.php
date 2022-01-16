<?php
ob_start();
?>
<input type="hidden" name="sp" value=1>
<div id="login-account-password-wrapper" class="login-account-wrapper <?= $btnActivePw ?>">
    <div style="margin: 24px 0; font-size: 14px; font-weight: normal; color: #424242; text-align: center;">
        아이디 확인 및 본인인증을 통해<br />
        비밀번호를 변경하실 수 있습니다.
    </div>
    <div style="text-align: center;margin-bottom: 24px;">
        <input type="text" class="form-input" style="width: 332px;" id="form-mb-id" name="mb_id" placeholder="아이디" data-validation="인증정보가 일치하지 않습니다">
        <span class=" form-validation-warn"><img src="/img/re/or.png" srcset="/img/re/or@2x.png 2x,/img/re/or@3x.png 3x"><span class="validation-warn-text"></span></span>
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