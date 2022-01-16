<?php
ob_start();
?>
<div id="popup-join">
    <div>
        <img src="/img/re/logo.png" srcset="/img/re/logo@2x.png 2x,/img/re/logo@3x.png 3x" class="logo">
    </div>
    <div>
        <button type="submit" class="btn btn-join-email">로그인</button>
    </div>
    <div>SNS로 간편가입</div>
    <div>
        <img src="/img/re/naver.png" srcset="/img/re/naver@2x.png 2x,/img/re/naver@3x.png 3x" class="logo">
        <img src="/img/re/kakao.png" srcset="/img/re/kakao@2x.png 2x,/img/re/kakao@3x.png 3x" class="logo">
        <img src="/img/re/facebook.png" srcset="/img/re/facebook@2x.png 2x,/img/re/facebook@3x.png 3x" class="logo">
    </div>
</div>
<?php
$contents = ob_get_contents();
ob_end_clean();

return $contents;
?>