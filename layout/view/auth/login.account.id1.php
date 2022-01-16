<?php
ob_start();
?>
<input type="hidden" name="si" value=2>
<div id="login-account-id-wrapper" class="login-account-wrapper <?= $btnActiveId ?>">
    <div class="C1KOBLM" style="margin: 40px 0;">
        회원님의 정보와 일치하는<br />
        아이디와 소셜 로그인 서비스입니다.
    </div>
    <div style="height: 40px; font-size: 14px; font-weight: 400; line-height: 1.43; text-align: center; color: #777777; margin-bottom: 40px;">
        <div>
            아이디 :
            <? if ($is_social_login) : ?>
                <img style="width: 24px; height: 24px;" src="/img/re/<?= $member_social['provider'] ?>@3x.png"> <?= $mb['mb_email'] ?>
            <? else : ?>
                <?= covered_string($mb['mb_id'], '*', 0, 2) ?>
            <? endif ?>
        </div>
        <div>
            가입일 : <?= date("Y.m.d", strtotime($mb['mb_datetime'])) ?>
        </div>
    </div>
    <div>
        <a href="/auth/login.php">
            <button type="button" class="btn btn-black" style="margin-top: 8px;" onclick=openLogin()>로그인</button>
        </a>
    </div>
</div>
<?php
$sub_contents = ob_get_contents();
ob_end_clean();

return $sub_contents;
?>;