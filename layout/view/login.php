<?php
ob_start();
?>
<style>
    .login-desc {
        font-size: 14px;
        font-weight: 400;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #828282;
        text-align: left;
        margin-bottom: 10px;
    }

    .login-desc>span {
        width: 195px;
        display: inline-block;
    }

    .login-desc>a>button {
        height: 24px;
        border-radius: 2px;
        border: solid 1px #333333;
        background-color: #ffffff;
        font-size: 12px;
        font-weight: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: center;
        color: #3a3a3a;
    }

    .input-title {
        height: 18px;
        font-size: 12px;
        font-weight: 400;
        line-height: normal;
        color: #424242;
        margin-bottom: 4px;
    }

    .btn-find-account {
        height: 20px;
        font-size: 12px;
        font-weight: 400;
        line-height: 1.67;
        text-align: center;
        color: #565656;
    }

    @media (max-width: 1366px) {
        .layout-offset {
            padding-top: 8px;
        }
    }
</style>

<form name="flogin" action="<?= $login_action_url ?>" method="POST">
    <input type="hidden" name="url" value="<?= $login_url ?>">
    <input type="hidden" id="deviceKind" name="deviceKind" value="">
    <input type="hidden" id="devicetoken" name="devicetoken" value="">
    <input type="hidden" id="mobileAppLogin" name="mobileAppLogin" value="">
    <input type="hidden" id="reCheck" name="reCheck" value="<?= $r ?>">
    <input type="hidden" id="rePage" name="rePage" value="<?= $_SERVER['HTTP_REFERER'] ?>">
    <div class="layout-offset">
        <div id="login-wrapper">
            <div style="height: 25px; font-size: 26px; font-weight: bold; line-height: normal; text-align: center; color: #0f0f0f; margin-bottom: 40px;" class="on-big">로그인</div>
            <div class="input-title on-small">아이디</div>
            <div style="margin-bottom: 16px;">
                <input type="text" class="form-input" name="mb_id" placeholder="아이디 입력" value="<?= $def['mb_id'] ?>">
            </div>
            <div class="input-title on-small">비밀번호</div>
            <div>
                <input type="password" class="form-input" name="mb_password" placeholder="비밀번호 입력" value="">
            </div>
            <div id="autoLogin">
                <div class="custom-control custom-checkbox" style="margin-top: 16px; line-height: 23px; vertical-align: middle;">
                    <input type="checkbox" class="form-input custom-control-input" id="save_id" name="save_id" <?= $def['save_id'] ?>>
                    <label for="save_id" style="color: #3a3a3a;" class="C1KOGRL custom-control-label">아이디 저장</label>
                </div>
                <div class="custom-control custom-checkbox" style="margin-top: 10px; line-height: 23px; vertical-align: middle;">
                    <input type="checkbox" class="form-input custom-control-input" id="save_me" name="save_me">
                    <label for="save_me" style="color: #3a3a3a;" class="C1KOGRL custom-control-label">자동로그인</label>
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-login" onclick="appPushCheck()">로그인</button>
            </div>
            <div style="text-align: center; margin: 12px 0 28px 0;" class="on-big">
                <div class="login-desc">
                    <span>아직도 회원이 아니신가요?</span>
                    <a href="/auth/join.choice.php"><button type="button" class="btn-find-account btn-find-password">회원가입</button></a>
                </div>
                <div class="login-desc">
                    <span>아이디/비밀번호를 잊으셨나요?</span>
                    <a href="/auth/login.account.php"><button type="button" class="btn-find-account btn-find-id">아이디/비밀번호 찾기</button></a>
                </div>
            </div>
            <div style="text-align: center; margin: 16px 0 40px 0;" class="on-small">
                <a href="/auth/login.account.php"><span class="btn-find-account btn-find-id">아이디 찾기</span></a>
                <span class="Line-2"></span>
                <a href="/auth/login.account.php?active=p"><span class="btn-find-account btn-find-password">비밀번호 찾기</span></a>
                <span class="Line-2"></span>
                <a href="/auth/join.choice.php"><span class="btn-find-account btn-find-password">회원가입</span></a>
            </div>
            <div style="border-top: 1px solid var(--very-light-pink-three);">
                <? if (social_service_check('naver')) : ?>
                    <a href="<?= $self_url; ?>?provider=naver&amp;url=<?= $urlencode; ?>" target="_blank" rel="opener"><button type="button" class="form-input btn btn-sns-login btn-sns-naver" onclick="appPushCheckSns()">네이버 아이디로 로그인</button></a>
                <? endif ?>
                <? if (social_service_check('kakao')) : ?>
                    <a href="<?= $self_url; ?>?provider=kakao&amp;url=<?= $urlencode; ?>" target="_blank" rel="opener"><button type="button" class="form-input btn btn-sns-login btn-sns-kakao" onclick="appPushCheckSns()">카카오 아이디로 로그인</button></a>
                <? endif ?>
                <? if (social_service_check('facebook')) : ?>
                    <a href="<?= $self_url; ?>?provider=facebook&amp;url=<?= $urlencode; ?>" target="_blank" rel="opener"><button type="button" class="form-input btn btn-sns-login btn-sns-fb">페이스북 아이디로 로그인</button></a>
                <? endif ?>
                <? if (social_service_check('twitter')) : ?>
                    <a href="<?= $self_url; ?>?provider=twitter&amp;url=<?= $urlencode; ?>" target="_blank" rel="opener"><button type="button" class="form-input btn btn-sns-login btn-sns-twitter">트위터 아이디로 로그인</button></a>
                <? endif ?>
                <? if (social_service_check('payco')) : ?>
                    <a href="<?= $self_url; ?>?provider=payco&amp;url=<?= $urlencode; ?>" target="_blank" rel="opener"><button type="button" class="form-input btn btn-sns-login btn-sns-payco">페이코 아이디로 로그인</button></a>
                <? endif ?>

                <? if (social_service_check('apple')) : ?>
                    <a class="on-small" href="<?= $self_url; ?>?provider=apple&amp;url=<?= $urlencode; ?>" target="_blank" rel="opener"><button type="button" class="form-input btn btn-sns-login btn-sns-apple" style="background-color: black; color: white;">Apple로 로그인</button></a>
                   <!-- <button type="button" id="sign-in-with-Apple-button" class="form-input btn btn-sns-apple">애플 아이디로 로그인</button> -->
                    <!-- <script type="text/javascript" src="https://appleid.cdn-apple.com/appleauth/static/jsapi/appleid/1/en_US/appleid.auth.js"></script>
                    <div id="appleid-signin" data-color="black" data-border="true" data-type="sign in"></div>
                    <script type="text/javascript">
                        AppleID.auth.init({
                            clientId : 'com.litandard.lifelike.singin',
                            scope : 'name email',
                            redirectURI: 'https://lifelike.co.kr/auth/login.php',
                            state : 'DE',
                        });
                    </script> -->


                <? endif ?>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript" src="https://appleid.cdn-apple.com/appleauth/static/jsapi/appleid/1/en_US/appleid.auth.js"></script>
<script type="text/javascript">
    $(document).ready(function() { 
        let broswerInfo = navigator.userAgent;
        if (broswerInfo.indexOf("APP_ANDROID")>-1 || broswerInfo.indexOf("APP_IOS")>-1 ) {
            $("#autoLogin").hide();
            $("#mobileAppLogin").val("1");
            document.getElementById('save_id').checked = true;
            document.getElementById('save_me').checked = true;
        } else {
            $("#autoLogin").show();
            $("#mobileAppLogin").val("");
            if(broswerInfo.indexOf("Mobile")>-1) {
                let h = document.getElementById('appAd').clientHeight;
                document.getElementsByClassName('offsetNavTop')[0].style.height= h+84+'px';
            }
        }
    });

    AppleID.auth.init({
        clientId : 'com.litandard.lifelike.singin',
        scope : 'name email',
        redirectURI: 'https://lifelike.co.kr/auth/apple_login.php',
        state : 'DE',
        response_type: 'code id_token',
        // client_secret : 'PAZ4ABM7GD'
    });
    function appleSignIn() { 
        AppleID.auth.signIn();
    }
    function getFcmToken(data) {
        $("#deviceKind").val('APP_IOS');
        $("#devicetoken").val(data);
    }
    function appPushCheckSns() {
        let broswerInfo = navigator.userAgent;
        if (broswerInfo.indexOf("APP_ANDROID")>-1) {
            let token = window.lifelike_android.getFcmToken();
            set_cookie('life_t', token ,1000*100);
            set_cookie('life_k', "APP_ANDROID" ,1000*100);
        }else if (broswerInfo.indexOf("APP_IOS")>-1) {  
            set_cookie('life_t', '' ,-1);
            set_cookie('life_k', '' ,-1);
        } else {
            set_cookie('life_t', '' ,-1);
            set_cookie('life_k', '' ,-1);
            $("#deviceKind").val('');
            $("#devicetoken").val('');
        }
    }
    function appPushCheck() {
        let broswerInfo = navigator.userAgent;
        if (broswerInfo.indexOf("APP_ANDROID")>-1) {
            let token = window.lifelike_android.getFcmToken();
            $("#deviceKind").val('APP_ANDROID');
            $("#devicetoken").val(token);
        } else if (broswerInfo.indexOf("APP_IOS")>-1) { 
            $("#deviceKind").val('APP_IOS');
        } else {
            set_cookie('life_t', '' ,-1);
            set_cookie('life_k', '' ,-1);
            $("#deviceKind").val('');
            $("#devicetoken").val('');
        }
    }
    const buttonElement = document.getElementById('sign-in-with-Apple-button');
    buttonElement.addEventListener('click', () => {
        AppleID.auth.signIn()
    });
</script>
<style>
    .btn.btn-join {
        background-color: #2dbbb4;
    }
</style>
<?php
$contents = ob_get_contents();
ob_end_clean();

return $contents;
?>