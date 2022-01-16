<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
    return;
}


// 사용자 화면 우측과 하단을 담당하는 페이지입니다.
// 우측, 하단 화면을 꾸미려면 이 파일을 수정합니다.
?>

</div><!-- container End -->

<div id="footer">
	<!-- terms_group -->
	<h2 class="blind">정책 및 약관</h2>
	<div class="menu_group">
		<ul>
			<li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=company">회사소개</a></li>
			<li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=provision">이용약관</a></li>
			<li><a href="#">문의하기</a></li>
			<li><a href="#">고객센터</a></li>
		</ul>
	</div>
	<div class="inner">
		<!-- address_group -->
		<div class="address_group">
			<?php echo $default['de_admin_company_addr']; ?><br> 
			<?php echo $default['de_admin_company_name']; ?> 대표자 : <?php echo $default['de_admin_company_owner']; ?> <br>
			개인정보책임자 : <?php echo $default['de_admin_info_name']; ?><br>
			고객센터 : <?php echo $default['de_admin_company_tel']; ?><br>
			평일 10:00 ~ 17:00<br>
			OFF-time 12:00 ~ 14:00 (토/일/공휴일 휴무)<br>
		</div>
		<p class="copyright">Copyright ⓒ LIFELIKE INC. all rights reserved.</p>
		<ul class="terms_link">
			<li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=provision">이용안내</a></li>
			<li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=privacy">개인정보처리방침</a></li>
			<li><a href="#">법적고지</a></li>
		</ul>
	</div>
</div>

