<?php
//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once('_common.php');

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
	<link rel="stylesheet" type="text/css" href="<?php echo G5_URL; ?>/js/swiper/swiper.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo G5_URL; ?>/css/pc_common.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo G5_URL; ?>/css/pc_ui.css" />

	<!-- 스크립트 -->
	<script src="<?php echo G5_URL; ?>/js/jquery-1.8.3.min.js" type="text/javascript"></script>
	<script src="<?php echo G5_URL; ?>/js/swiper/swiper.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo G5_URL; ?>/js/pc_ui.js" type="text/javascript"></script>


	</head>

	<body>

		<!-- popup -->
		<section class="popup_container layer">
			<div class="inner_layer">
				<div class="content login">
					<!-- 컨텐츠 시작 -->
					<div class="grid cont">
						<div class="title_bar">
							<h1 class="g_title_01">비회원 주문/배송조회</h1>
						</div>
						<div class="title_bar none">
							<h2 class="g_title_03">비회원으로 제품을 구매하신 경우에만 주문/배송조회가 가능합니다.</h2>
						</div>
					</div>
					<div class="grid">

						<form name="forderinquiry" method="post" action="<?php echo G5_SHOP_URL . '/orderinquiry.php'; ?>" autocomplete="off">
							<div class="inp_wrap">
								<div class="title count3"><label for="join1">이름</label></div>
								<div class="inp_ele count6">
									<div class="input"><input type="text" placeholder="이름 입력" name="od_name" id="od_name" required="required"></div>
								</div>
							</div>
							<div class="inp_wrap">
								<div class="title count3"><label for="join2">휴대전화 번호</label></div>
								<div class="inp_ele count6">
									<div class="input"><input type="tel" placeholder="휴대전화 번호 입력" name="od_tel" id="od_tel" required="required"></div>
								</div>
							</div>
							<div class="inp_wrap">
								<div class="title count3"><label for="join3">주문번호</label></div>
								<div class="inp_ele count6">
									<div class="input"><input type="text" placeholder="주문번호 입력" name="od_id" id="od_id" required="required"></div>
								</div>
							</div>

							<!-- 간격 여백 -->
							<hr class="full_line">

							<div class="info_box">
								<p class="ico_import red point_red">주의해주세요.</p>
								<div class="list">
									<ul class="hyphen">
										<li>주문자명/휴대전화 번호/주문번호를 모두 입력하셔야 정상 조회가 가능합니다.</li>
										<li>주문번호가 기억나지 않은 경우 고객센터(<?= $default['de_admin_call_tel'] ?>)를 통해 문의해 주세요.</li>
									</ul>
								</div>
							</div>

							<div class="btn_group bottom none"><button type="submit" class="btn big green"><span>주문/배송조회</span></button></div>
						</form>
					</div>
					<!-- 컨텐츠 종료 -->
				</div>
				<a href="<?php echo G5_BBS_URL ?>/login.php" class="btn_closed" "><span class=" blind">닫기</span></a>
			</div>
		</section>
		<!-- //popup -->


	</body>

</html>