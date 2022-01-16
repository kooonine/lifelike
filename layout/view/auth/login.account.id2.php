<?php
ob_start();
?>
<input type="hidden" name="si" value=2>
<div id="login-account-id-wrapper" class="login-account-wrapper <?= $btnActiveId ?>">
    <div class="C1KOBLM" style="margin: 40px 0;">
        회원님의 정보와 일치하는<br />
        아이디가 없습니다.
    </div>
    <div>
        <a href="/auth/join.php">
            <button type="button" class="btn btn-black btn-mint" style="margin-top: 8px;">회원가입</button>
        </a>
    </div>
</div>
<?php
$sub_contents = ob_get_contents();
ob_end_clean();

return $sub_contents;
?>;