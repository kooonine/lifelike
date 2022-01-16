<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가


// 사용자 화면 우측과 하단을 담당하는 페이지입니다.
// 우측, 하단 화면을 꾸미려면 이 파일을 수정합니다.

/* "환경설정 에서 내용 불러오기
    - 회원소개 : 기타이용안내설정 > 회원가입안내
    - 이용약관 : 이용약관설정 > 쇼핑몰 이양약관
    - 개인정보처리방침 : 이용약관 설정 > 개인정보 처리방침
    - 법적 고지 : 기타이용안내설정 > 주문안내
    - 이용안내 : 기타이용안내 > 이용안내 > 결제안내"*/

?>

<!-- container End -->
<div id="footer">
	<!-- terms_group -->
	<h2 class="blind">정책 및 약관</h2>
	<div class="menu_group">
		<ul>
			<li><a href="/company.php">회사소개</a></li>
			<li><a href="<?php echo G5_MOBILE_URL ?>/common/terms_agreement.php?type=stipulation&agree=no&title=이용약관" target="_blank">이용약관</a></li>
			<li><a href="<?php echo G5_MOBILE_URL ?>/common/terms_agreement.php?type=privacy&agree=no&title=개인정보처리방침" target="_blank">개인정보처리방침</a></li>
			<li><a href="<?php echo G5_BBS_URL ?>/faq.php">고객센터</a></li>
		</ul>
	</div>
	<div class="inner">
		<!-- address_group -->
		<div class="address_group">
			<?php echo $default['de_admin_company_name']; ?><br>
			<?php echo $default['de_admin_company_addr']; ?><br>
			대표자 : <?php echo $default['de_admin_company_owner']; ?><br>
			사업자등록번호 : <?php echo $default['de_admin_company_saupja_no']; ?><br>
			통신판매업신고 : <?php echo $default['de_admin_tongsin_no']; ?><br>
			고객센터 : <?php echo $default['de_admin_call_tel']; ?><br>
			<?php echo $default['de_admin_call_time']; ?><br>
			E-MAIL : <?php echo $default['de_admin_call_email']; ?><br>
			FAX : <?php echo $default['de_admin_company_fax']; ?>
		</div>
		<p class="copyright">Copyright ⓒ LIFELIKE INC. all rights reserved.</p>
		<!-- ul class="terms_link">
			<li><a href="<?php echo G5_MOBILE_URL ?>/common/terms_agreement.php?type2=pay_info&agree=no&title=이용안내" target="_blank">이용안내</a></li>
			<li><a href="<?php echo G5_MOBILE_URL ?>/common/terms_agreement.php?type=privacy&agree=no&title=개인정보처리방침" target="_blank">개인정보처리방침</a></li>
			<li><a href="<?php echo G5_MOBILE_URL ?>/common/terms_agreement.php?type2=order_info&agree=no&title=법적고지" target="_blank">법적고지</a></li>
		</ul -->
	</div>
</div>
<!-- <script type="text/javascript">
	var IS_IOS = /iPad|iPhone|iPod/.test(navigator.platform);
	if (IS_IOS) {
		$(".column_one > a").attr("href", "https://apps.apple.com/kr/app/lifelike/id1473080254");
	}
</script> -->
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
include_once(G5_MOBILE_PATH . "/tail.sub.php");
?>