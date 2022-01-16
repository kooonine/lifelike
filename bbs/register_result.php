<?php
include_once('./_common.php');

if (isset($_SESSION['ss_mb_reg'])) {
    $mb = get_member($_SESSION['ss_mb_reg']);

    // 세션을 지웁니다.
    set_session("ss_mb_reg", "");
}
// 회원정보가 없다면 초기 페이지로 이동
if (!$mb['mb_id']) {
    goto_url(G5_URL);
}

$g5['title'] = '회원가입 완료';
include_once('./_head.php');
?>
<!-- 네이버 분석스크립트 시작 -->
<!-- 전환페이지 설정 -->
<script type="text/javascript" src="https://wcs.naver.net/wcslog.js"></script>
<script type="text/javascript">
    var _nasa = {};
    _nasa["cnv"] = wcs.cnv("2", "10"); // 전환유형, 전환가치 설정해야함. 설치매뉴얼 참고
</script>
<!-- 네이버 분석스크립트 끝 -->
<?php
include_once($member_skin_path . '/register_result.skin.php');
if (G5_IS_MOBILE) include_once(G5_PATH . '/tail.wcslog.php');
// include_once('./_tail.php');
