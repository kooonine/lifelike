<?php
//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once('../../common.php');

?>
<html lang="ko">
<head>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi" />
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">

<!-- 스타일 -->
  <link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/js/swiper/swiper.min.css">
 <link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/css/m_common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/css/m_ui.css" />

<!-- 스크립트 -->
<script src="<?php echo G5_MOBILE_URL;?>/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="<?php echo G5_MOBILE_URL;?>/js/swiper/swiper.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo G5_MOBILE_URL;?>/js/m_ui.js" type="text/javascript"></script>
    

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
			<!-- lnb -->
			<div id="lnb" class="header_bar">
				<h1 class="title"><span>회원가입</span></h1>
				<a href="<?php echo G5_BBS_URL; ?>/login.php" class="btn_back"><span class="blind">뒤로가기</span></a>
				<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>
			</div>
			<!-- //lnb -->

			<div class="content comm sub">
				<!-- 컨텐츠 시작 -->
				<div class="grid cont">
					<div class="title_bar none">
						<h2 class="g_title_01">회원 유형을 선택해 주세요.</h2>
                        <p class="g_title_02">회원가입을 하시면 다양한 이벤트 혜택을 받으실 수 있습니다.</p>
                    </div>
				</div>
				<div class="grid join_container">
                    <div class="join_type list">
                        <div>
                            <span class="tit">일반 회원가입</span><a href="<?php echo G5_BBS_URL?>/register.php?type=normal"><span>바로가기</span></a>
                        </div>
                        <span class="info_box">
                            <span class="cmt">만 14세 이상 일반 회원일 경우</span>
                        </span>
                        <div>
                            <span class="tit">사업자 회원가입</span><a href="<?php echo G5_BBS_URL?>/register.php?type=company"><span>바로가기</span></a>
                        </div>
                        <span class="info_box">
                            <span class="cmt">사업자등록번호가 있는 법인 회원일 경우</span>
                        </span>
                    </div>
				</div>
				<!-- 컨텐츠 종료 -->
			</div>
		</div>
</div>

		
</body>
</html>
