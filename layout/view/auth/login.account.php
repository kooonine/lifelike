<?php
ob_start();
$g5_title = "아이디/비밀번호 찾기";
?>

<form action="" method="POST" id="form-account" name="form_account">
    <input type="hidden" id="form-mb-dupinfo" class="form-input" name="mb_dupinfo" value="">
    <div class="on-small">
        <a href="?active=i"><button type="button" class="btn btn-white btn-half btn-account-tab <?= $btnActiveId; ?>" data-active="i" data-target="login-account-id-wrapper">아이디</button></a><a href="?active=p"><button type="button" class="btn btn-white btn-half btn-account-tab <?= $btnActivePw; ?>" data-active="p" data-target="login-account-password-wrapper">비밀번호</button></a>
    </div>
    <div class="layout-offset">
        <div id="login-wrapper">
            <div style="height: 25px; font-size: 26px; font-weight: bold; line-height: normal; text-align: center; color: #0f0f0f; margin-bottom: 40px;" class="on-big">아이디/비밀번호 찾기</div>
            <div class="on-big">
                <a href="?active=i"><button type="button" class="btn btn-white btn-half btn-account-tab <?= $btnActiveId; ?>" data-active="i" data-target="login-account-id-wrapper">아이디</button></a><a href="?active=p"><button type="button" class="btn btn-white btn-half btn-account-tab <?= $btnActivePw; ?>" data-active="p" data-target="login-account-password-wrapper">비밀번호</button></a>
            </div>
            <?php echo $sub_id ?>
            <?php echo $sub_ps ?>
        </div>
    </div>
</form>

<!-- PASS 본인인증 FORM -->
<form id="form-pass" method="post">
    <input type="hidden" name="m" value="checkplusService">
    <input type="hidden" name="EncodeData" value="<?php echo $enc_data ?>">
</form>

<script>
    const fv = JSON.parse('<?= get_session("fv") ?>');
    $(document).ready(function() {
        if (fv.length > 0) {
            $(fv).each(function(fi, fe) {
                showFormValidationWarn("#" + fe);
            });
        }
    });

    function passDoAuth() {
        const urlPass = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
        const winPass = window.open('', 'popupPass', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');

        $("#form-pass").prop("action", urlPass).prop("target", "popupPass").submit();
    }

    function passResponse(response) {
        if (response.error.length > 0) {
            alert(response.error);
        } else {
            $("#form-mb-dupinfo").val(response.data.CI);
            $("#form-account").submit();
        }
    }
</script>
<?php
$contents = ob_get_contents();
ob_end_clean();

return $contents;
?>;