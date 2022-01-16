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


    .btn.btn-join-email {
        width: 340px;
        height: 98px;
        border-radius: 2px;
        border: solid 1px #333333;
        background-color: #333333;
        font-size: 18px;
        font-weight: 400;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #ffffff;
        margin-top: 0;
    }

    .join-choice-button {
        display: inline-flex;
        height: 98px;
        width: 340px;
        flex-direction: column;
        vertical-align: top;
    }

    @media (max-width: 1366px) {
        .layout-offset {
            padding: 80px 14px;
        }

        .join-choice-button {
            height: auto;
            width: 100%;
        }

        .join-choice-button>a {
            margin-bottom: 10px;
        }

        .btn.btn-join-email {
            width: 100%;
            height: 44px;
            font-size: 14px;
        }

        #join-content-banner {
            padding: 22px 0 50px 0;
        }

        #join-content-banner>img {
            width: 100%;
        }

        #join-content-wrapper {
            font-size: 18px;

        }
    }
</style>
<div class="layout-offset">
    <div id="join-wrapper">
        <div id="join-content-wrapper">
            <div>라이프라이크에 오신것을 환영합니다</div>
            <div class="on-big" style="margin-top: 24px; font-size: 14px; font-weight: normal; line-height: 1.43; text-align: center; color: #4c4c4c;">
                지금 바로 라이프라이크에 가입해보세요.<br>
                오직 라이프라이크 고객만을 위한 혜택이 여러분을 기다리고 있습니다.
            </div>
            <div class="on-small" style="margin-top: 24px; font-size: 12px; font-weight: normal; line-height: 1.43; text-align: center; color: #4c4c4c;">
                지금 바로 라이프라이크에 가입해보세요.<br>
                오직 라이프라이크 고객만을 위한 혜택이<br>
                여러분을 기다리고 있습니다.
            </div>
            <div id="join-content-banner">
                <img src="/img/re/temp_banner_join.png" alt="">
            </div>
            <div style="font-size: 0;">
                <span class="join-choice-button on-big">
                    <!-- <a href="join.pass.php?url=<?= $url ?>&type=email"><button type="button" class="btn btn-join-email" style="height:132px;">일반회원가입</button></a> -->
                    <a href="join.pass.php?url=<?= $url ?>&type=email"><button type="button" class="btn btn-join-email">일반회원가입</button></a>
                </span>
                <span class="join-choice-button on-small">
                    <a href="join.pass.php?url=<?= $url ?>&type=email"><button type="button" class="btn btn-join-email">일반회원가입</button></a>
                </span>
                <span class="join-choice-button on-big" style="width: 20px;"></span>
                <span class="join-choice-button">
                    <? if (social_service_check('naver')) : ?>
                        <button type="button" class="btn btn-sns-login btn-sns-naver btn-join-sns" data-sns="naver">네이버 아이디로 회원가입</button><? endif ?>
                    <? if (social_service_check('kakao')) : ?>
                        <button type="button" class="btn btn-sns-login btn-sns-kakao btn-join-sns on-big" data-sns="kakao" style="margin-bottom: 0px;">카카오 아이디로 회원가입</button>
                        <button type="button" class="btn btn-sns-login btn-sns-kakao btn-join-sns on-small" data-sns="kakao" style="margin-top: 0px;">카카오 아이디로 회원가입</button>
                    <? endif ?>
                    <? if (social_service_check('apple')) : ?>
                        <button type="button" class="btn btn-sns-login btn-sns-apple btn-join-sns on-small" data-sns="apple" style="background-color: black; color: white;">Apple로 회원가입</button>
                    <? endif ?>   
                </span>
            </div>
        </div>
    </div>
</div>
<script>
</script>
<?php
$contents = ob_get_contents();
ob_end_clean();

return $contents;
?>;