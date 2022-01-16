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
    }

    .join-form-wrapper {
        width: 700px;
        border-radius: 2px;
        border: solid 1px #e0e0e0;
        padding: 40px 60px;
    }

    .join-form-wrapper>table {
        width: 100%;
        font-size: 14px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #424242;
    }

    button.btn.btn-join-submit,
    button.btn.btn-join-form {
        width: 110px;
        height: 44px;
        border: unset;
        border-radius: 2px;
        background-color: #333333;
        font-size: 16px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #ffffff;
    }

    button.btn.btn-join-submit {
        width: 340px;
    }

    .point-require {
        color: #f14e00;
        margin-left: 1px;
    }

    input[type="checkbox"].custom-control-input {
        left: unset;
        margin-left: -22px;
    }

    .custom-checkbox .custom-control-label::before {
        width: 18px;
        height: 18px;
        top: 2px;
    }

    .custom-checkbox .custom-control-input:checked~.custom-control-label::after {
        width: 18px;
        height: 18px;
        top: 2px;
    }

    #table-join-info>tbody>tr>td {
        padding-bottom: 16px;
    }

    .form-validation-row {
        font-size: 12px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #333333;
        display: none;
    }

    .form-validation-row .passed {
        color: #094283;
    }

    .form-validation-row .not-passed {
        color: #d42e01;
    }

    @media (max-width: 1366px) {
        #join-wrapper {
            padding: unset !important;
        }

        button.btn.btn-join-submit {
            width: 100%;
            font-size: 14px;
        }
    }
</style>
<div class="layout-offset">
    <div id="join-wrapper" class="offset-mobile-middle">
        <div class="on-big">
            <div class="join-step-title">회원가입</div>
            <div style="text-align: center; font-size: 0; margin-top: 32px; margin-bottom: 8px;">
                <span class="icon-join-step">1</span>
                <span class="icon-join-step" style="margin: 0 94px;">2</span>
                <span class="icon-join-step active">3</span>
            </div>
            <div style="text-align: center; font-size: 0;">
                <span class="label-join-step">본인인증</span>
                <span class="label-join-step" style="margin: 0 73px;">회원정보입력</span>
                <span class="label-join-step active">가입완료</span>
            </div>
        </div>
        <div id="join-content-wrapper" class="on-small">
            <div style="margin: 28px 0;">가입완료</div>
        </div>
        <div style="font-size: 18px; font-weight: 500; text-align: center; color: #4c4c4c; margin: 80px 0;">
            가입이 완료 되었습니다.
        </div>
        <div style="text-align: center;">
            <button type="button" class="btn btn-join-submit" onclick="location.href='/auth/login.php'">로그인</button>
        </div>
    </div>
</div>
<!-- START NEXDI 0222 -->
<script type="text/javascript" charset="UTF-8" src="//t1.daumcdn.net/adfit/static/kp.js"></script>
<script type="text/javascript">
     kakaoPixel('2967409213611789029').pageView();
     kakaoPixel('2967409213611789029').completeRegistration();
</script>
<!-- END NEXDI 0222 -->
<!-- START NEXDI 0326 -->
<script type="text/javascript" src="//wcs.naver.net/wcslog.js"></script>
<script type="text/javascript">
var _nasa={};
if(window.wcs) _nasa["cnv"] = wcs.cnv("2","1"); // 전환유형, 전환가치 설정해야함. 설치매뉴얼 참고
</script>
<!-- END NEXDI 0222 -->
<!-- START NEXDI 0720 -->
<script>
  gtag('event', 'conversion', {'send_to': 'AW-336156343/asrVCIOa6tYCELetpaAB'});
</script>
<!-- END NEXDI 0720 -->
<!-- Facebook Pixel Code 0720 -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '1185455058605170');
  fbq('track', 'PageView');

	fbq('track', 'CompleteRegistration');

</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=772782013392105&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code 0720 -->
<!-- Facebook Pixel Code 1110 -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '427587602051149');
  fbq('track', 'PageView');

	fbq('track', 'CompleteRegistration');

</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=772782013392105&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code 1110 -->
<!-- adpopcorn start -->
<!-- <script src="//webapi.adpopcorn.com/offerwall/postback/js/adpopcorn-cpa.1.0.js"></script> -->
<script> 
	$(function() {
        AdPopcorn.cpaCompleted();
	});
</script>
<!-- adpopcorn end -->
<?php
$contents = ob_get_contents();
ob_end_clean();

return $contents;
?>;