<?php
//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$mb_name = $mb['mb_name'];
?>
<!-- <html lang="ko"> -->
<!-- <head> -->

<!-- <meta charset="utf-8" /> -->
<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi" /> -->
<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"> -->


<!-- </head> -->
<!-- <body> -->
<!-- <div class="wrap_all"> -->
<!-- 		<p id="skipNavi"><a href="#container">본문 바로가기</a></p> -->

		<!-- container -->
		<div id="container">
			<!-- lnb -->
			<div id="lnb" class="header_bar">
				<h1 class="title"><span>회원가입 완료</span></h1>
			</div>
			<!-- //lnb -->
			<div class="content comm sub">
				<!-- 컨텐츠 시작 -->
				<div class="grid">
					<div class="guide_box ico ico_chk">
						<p>안녕하세요, <span class="point"><?php echo $mb_name?></span> 님<br>라이프라이크 회원이 되어주셔서 감사합니다. <br>일상을 더 선명하게 즐겁게 할 라이프라이크의 특별한 서비스, 지금 바로 만나보세요.</p>
					</div>
					<div class="btn_group"><a href="<?php echo G5_URL?>/index.php" class="btn big green"><span>메인으로</span></a></div>
				</div>
				
				<!-- 컨텐츠 종료 -->
			</div>
		</div>
		<!-- //container -->
		
		<!-- footer -->

		<!-- //footer -->
	</div>
<?php
include_once(G5_PATH.'/tail.php');
?>
		
<!-- </body> -->
<!-- </html> -->
