<?php
ob_start();
?>
<input type="hidden" name="sp" value=3>
<div id="login-account-password-wrapper" class="login-account-wrapper <?= $btnActivePw ?>">
    <div style="margin: 24px 0; font-size: 14px; font-weight: normal; color: #424242; text-align: center;">
        비밀번호 변경이 완료되었습니다.
    </div>
    <div>
        <a href="/auth/login.php">
            <button type="button" class="btn btn-black" style="margin-top: 8px;">로그인</button>
        </a>
    </div>
</div>
<?php
$sub_contents = ob_get_contents();
ob_end_clean();
return $sub_contents;
?>;