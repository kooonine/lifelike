<?php
//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once('_common.php');

$autologinid = get_cookie('ck_mb_id');
$autologinkey = get_cookie('ck_auto');
?>
<!DOCTYPE html>
<html lang="ko">

<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
</head>

<body>
	<!-- 스타일 -->
	<link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/js/swiper/swiper.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/css/m_common.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/css/m_ui.css" />

	<!-- 스크립트 -->
	<script src="<?php echo G5_MOBILE_URL; ?>/js/jquery-1.8.3.min.js" type="text/javascript"></script>
	<script src="<?php echo G5_MOBILE_URL; ?>/js/swiper/swiper.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo G5_MOBILE_URL; ?>/js/m_ui.js" type="text/javascript"></script>


	</head>

	<body>
		<div class="wrap_all">
			<p id="skipNavi"><a href="#container">본문 바로가기</a></p>
			<!-- header -->

			<!-- //header -->

			<!-- aside -->
			<?php include_once('../aside.php'); ?>

			<!-- container -->
			<div id="container">
				<div id="lnb" class="header_bar">
					<h1 class="title"><span>설정</span></h1>
				</div>

				<div class="content comm sub">
					<!-- 컨텐츠 시작 -->
					<div class="grid set">
						<div class="title_bar">
							<h1 class="g_title_01">설정</h1>
						</div>
						<div class="title_bar">
							<h2 class="g_title_01">로그인 설정</h2>
						</div>
						<div class="set_cont">
							<div class="bar">
								<h3 class="tit">자동 로그인</h3>
								<span class="switch_group">

									<?php if ($autologinid != '' && $autologinkey != '') { ?>
										<button type="button" class="switch on" id="auto_login"><span></span></button>
									<?php } else { ?>
										<button type="button" class="switch off" id="auto_login"><span></span></button>
									<?php } ?>
								</span>
							</div>
						</div>
						<div class="title_bar">
							<h2 class="g_title_01">SNS 연동</h2>
						</div>
						<div class="set_cont">
							<div class="bar">
								<h3 class="tit">
									<img src="../../img/mb/ico/ico_sns_naver.png" alt="네이버"><span>네이버</span>
									<!-- <img src="../../img/mb/ico/ico_sns_facebook.png" alt="페이스북"><span>페이스북</span>
								<img src="../../img/mb/ico/ico_sns_talk.png" alt="카카오톡"><span>카카오톡</span> -->
								</h3>
								<span class="sns_right_txt">
									taepyung@naver.com
								</span>
							</div>
						</div>
						<div class="title_bar">
							<h2 class="g_title_01">알람 수신 설정</h2>
						</div>
						<div class="set_cont">
							<div class="bar">
								<h3 class="tit">광고성 정보(PUSH) 수신동의</h3>
								<span class="switch_group">
									<button type="button" class="switch off"><span></span></button>
								</span>
							</div>
							<p class="txt">본 설정은 해당 기기에서만 유효하며, 수신 동의하시면 쿠폰, 할인, 상품 정보 및 주문 입고알림, 등도 받으실 수 있습니다.</p>
						</div>
						<div class="set_cont">
							<div class="bar">
								<h3 class="tit">알림 설정</h3>
								<span class="switch_group">
									<button type="button" class="switch off"><span></span></button>
								</span>
							</div>
							<p class="txt">제품 발송, 리스, 케어 관련 (광고성 메시지 포함) 알림을 받으실 수 있습니다.</p>
						</div>

					</div>
					<div class="grid bg_none">
						<div class="set_cont">
							<div class="bar">
								<h3 class="tit">앱 버전</h3>
								<span class="right_txt">
									<span>1.31</span><button type="button" class="btn small green_line round"><span>앱 업데이트</span></button>
								</span>
							</div>
						</div>
						<?php if ($is_member) { ?>
							<div class="btn_group"><a href="<?php echo G5_BBS_URL; ?>/logout.php"><button type="button" class="btn big border"><span>로그아웃</span></button></a></div>
						<?php } ?>
					</div>
					<!-- 컨텐츠 종료 -->
				</div>

			</div>
		</div>

		<script type="text/javascript">
			$(document).ready(function() {
				$('#auto_login').click(function() {
					if ($(this).hasClass('on') == true) {
						<?php
						$key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['SERVER_SOFTWARE'] . $_SERVER['HTTP_USER_AGENT'] . $mb['mb_password']);
						set_cookie('ck_mb_id', $mb['mb_id'], 86400 * 31);
						set_cookie('ck_auto', $key, 86400 * 31);
						?>
					} else {
						<?php
						set_cookie('ck_mb_id', '', 0);
						set_cookie('ck_auto', '', 0);
						?>
					}
				});
			});
		</script>

	</body>

</html>