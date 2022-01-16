<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (defined('G5_THEME_PATH')) {
	require_once(G5_THEME_PATH . '/tail.php');
	return;
}

if (G5_IS_MOBILE) {
	include_once(G5_MOBILE_PATH . '/tail.php');
	return;
}

/* "환경설정 에서 내용 불러오기
 - 회원소개 : 기타이용안내설정 > 회원가입안내
 - 이용약관 : 이용약관설정 > 쇼핑몰 이양약관
 - 개인정보처리방침 : 이용약관 설정 > 개인정보 처리방침
 - 법적 고지 : 기타이용안내설정 > 주문안내
 - 이용안내 : 기타이용안내 > 이용안내 > 결제안내"*/
?>
<!-- 하단 시작 { -->
<div id="footer">
	<!-- terms_group -->
	<h3 class="blind">정책 및 약관</h3>
	<div class="terms_group fix_wrap">
		<ul>
			<li><a href="<?php echo G5_URL ?>/company.php">회사소개</a></li>
			<li><a href="<?php echo G5_URL ?>/company.php?type=stipulation">이용약관</a></li>
			<li><a href="<?php echo G5_URL ?>/company.php?type=privacy">개인정보처리방침</a></li>
			<li><a href="<?php echo G5_URL ?>/bbs/faq.php">고객센터</a></li>
			<!-- <li><a href="<?php echo G5_URL ?>/common/terms_agreement.php?type2=order_info&agree=no&title=법적고지" target="_blank">법적고지</a></li>
			<li><a href="<?php echo G5_URL ?>/common/terms_agreement.php?type2=pay_info&agree=no&title=이용안내" target="_blank">이용안내</a></li> -->
		</ul>
	</div>
	<div class="inner">
		<!-- address_group -->
		<div class="address_group fix_wrap">
			<?php echo $default['de_admin_company_name']; ?> <span class="bar">|</span>
			<?php echo $default['de_admin_company_addr']; ?> <span class="bar">|</span>
			대표이사 : <?php echo $default['de_admin_company_owner']; ?> <span class="bar"></span>
			<!-- 개인정보책임자 : <?php echo $default['de_admin_info_name']; ?>
			호스팅서비스 : 카페24(주) -->
			<br>

			사업자등록번호 : <?php echo $default['de_admin_company_saupja_no']; ?> <span class="bar">|</span>
			통신판매업신고 : <?php echo $default['de_admin_tongsin_no']; ?> <span class="bar"></span>
			<br>

			<span class="point">고객센터 : <?php echo $default['de_admin_call_tel']; ?></span> <span class="bar">|</span>
			<?php echo $default['de_admin_call_time']; ?>
			<br>

			E-MAIL : <?php echo $default['de_admin_call_email']; ?> <span class="bar">|</span>
			FAX : <?php echo $default['de_admin_company_fax']; ?>

		</div>
	</div>
</div>

</div>
</body>

</html>


<?php
if (G5_DEVICE_BUTTON_DISPLAY && !G5_IS_MOBILE) { ?>
<?php
}

if ($config['cf_analytics']) {
	echo $config['cf_analytics'];
}
?>

<!-- } 하단 끝 -->

<script>
	$(function() {
		// 폰트 리사이즈 쿠키있으면 실행
		font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
	});
</script>

<!-- 네이버 분석스크립트 시작 -->
<!-- 공통 적용 스크립트 , 모든 페이지에 노출되도록 설치. 단 전환페이지 설정값보다 항상 하단에 위치해야함 -->
<script type="text/javascript" src="https://wcs.naver.net/wcslog.js"> </script>
<script type="text/javascript">
	if (!wcs_add) var wcs_add = {};
	wcs_add["wa"] = "s_3a59b688a58f";
	if (!_nasa) var _nasa = {};
	wcs.inflow();
	wcs_do(_nasa);
</script>
<!-- 네이버 분석스크립트 끝 -->


<?php
include_once(G5_PATH . "/tail.sub.php");
?>