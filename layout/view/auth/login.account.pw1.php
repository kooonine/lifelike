<?php
ob_start();
?>
<style>
    .input-title {
        height: 18px;
        font-size: 14px;
        font-weight: 500;
        line-height: normal;
        color: #424242;
        margin-bottom: 4px;
    }

    .point-require {
        color: #f14e00;
        margin-left: 1px;
    }
</style>
<input type="hidden" name="sp" value=2>
<input type="hidden" name="dupinfo" value=<?= $mb_dupinfo ?>>
<div id="login-account-password-wrapper" class="login-account-wrapper <?= $btnActivePw ?>">
    <div style="margin: 24px 0; font-size: 14px; font-weight: normal; text-align: center; color: #4c4c4c;">
        새로운 비밀번호를 입력해주세요.
    </div>
    <div class="input-title">비밀번호<span class="point-require">*</span></div>
    <div>
        <input type="password" class="form-input" id="form-mb-password" name="mb_password" placeholder="신규 비밀번호(영문+숫자+특수문자 조합 8자리 이상)" data-validation="영문+숫자+특수문자 조합 8자리 이상 입력해주세요.">
        <span class="form-validation-warn"><img src="/img/re/or.png" srcset="/img/re/or@2x.png 2x,/img/re/or@3x.png 3x"><span class="validation-warn-text"></span></span>
    </div>
    <div class="input-title" style="margin-top: 16px;">비밀번호 확인<span class="point-require">*</span></div>
    <div>
        <input type="password" class="form-input" id="form-mb-password-re" name="mb_password_re" placeholder="신규 비밀번호 확인" data-validation="비밀번호가 일치하지 않습니다.">
        <span class="form-validation-warn"><img src="/img/re/or.png" srcset="/img/re/or@2x.png 2x,/img/re/or@3x.png 3x"><span class="validation-warn-text"></span></span>
    </div>
    <div>
        <button type="submit" class="btn btn-black" style="margin-top: 16px;">변경하기</button>
    </div>
</div>
<?php
$sub_contents = ob_get_contents();
ob_end_clean();
return $sub_contents;
?>;