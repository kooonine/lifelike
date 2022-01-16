<?php
// if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once ('_common.php');
include_once (G5_PATH . '/_head.php');

?>

<!-- container -->
<div id="container">
	<div id="lnb" class="header_bar">
		<h1 class="title">
			<span>회원 유형 선택</span>
		</h1>
	</div>
	<div class="content comm sub">
		<!-- 컨텐츠 시작 -->
		<div class="grid cont">
			<div class="title_bar none">
				<h2 class="g_title_02">
					가입 하시려는 회원 유형을 선택 해 주세요.<br>회원가입을 하시면 다양한 이벤트 혜택을 받으실 수 있습니다.
				</h2>
			</div>
		</div>
		<div class="grid join_container">
			<ul class="join_type list">
				<li><span class="tit">일반 회원가입</span><a
					href="<?php echo G5_BBS_URL?>/register.php?type=normal"><span>바로가기</span></a></li>
				<li><span class="tit">사업자 회원가입</span><a
					href="<?php echo G5_BBS_URL?>/register.php?type=company"><span>바로가기</span></a></li>
			</ul>
			<ul class="join_text clearfix">
				<li>- 만 14세 이상 일반 회원일 경우</li>
				<li>- 사업자등록번호가 있는 법인 회원일 경우</li>
			</ul>
		</div>
		<!-- 컨텐츠 종료 -->
	</div>
</div>
<!-- //container -->

<?php include_once(G5_PATH.'/_tail.php');?>
