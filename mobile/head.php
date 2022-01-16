<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$begin_time = get_microtime();

if (!isset($g5['title'])) {
	$g5['title'] = $config['cf_title'];
	$g5_head_title = $g5['title'];
} else {
	$g5_head_title = $g5['title']; // 상태바에 표시될 제목
	$g5_head_title .= " | " . $config['cf_title'];
}

// 현재 접속자
// 게시판 제목에 ' 포함되면 오류 발생
$g5['lo_location'] = addslashes($g5['title']);
if (!$g5['lo_location']) {
	$g5['lo_location'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
}
$g5['lo_url'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
if (strstr($g5['lo_url'], '/' . G5_ADMIN_DIR . '/') || $is_admin == 'super') {
	$g5['lo_url'] = '';
}
/*
// 만료된 페이지로 사용하시는 경우
header("Cache-Control: no-cache"); // HTTP/1.1
header("Expires: 0"); // rfc2616 - Section 14.21
header("Pragma: no-cache"); // HTTP/1.0
*/
?>
<!doctype html>
<html lang="ko">

<head>
	<meta charset="utf-8">
	<?
	if (G5_IS_MOBILE) {
		echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10">' . PHP_EOL;
		echo '<meta name="HandheldFriendly" content="true">' . PHP_EOL;
		echo '<meta name="format-detection" content="telephone=no">' . PHP_EOL;
	} else {
		echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10">' . PHP_EOL;
		echo '<meta http-equiv="imagetoolbar" content="no">' . PHP_EOL;
		echo '<meta http-equiv="X-UA-Compatible" content="IE=Edge">' . PHP_EOL;
	}

	if ($config['cf_add_meta']) {
		echo $config['cf_add_meta'] . PHP_EOL;
	}
	if ($config['cf_add_seo_use'] == "1") {
		if (strstr(addslashes(clean_xss_tags($_SERVER['REQUEST_URI'])), '/' . G5_BBS_DIR . '/')) {
			if ($config['cf_add_meta_bbs_title']) echo '<meta name="title" content="' . $config['cf_add_meta_bbs_title'] . '">' . PHP_EOL;
			if ($config['cf_add_meta_bbs_author']) echo '<meta name="author" content="' . $config['cf_add_meta_bbs_author'] . '">' . PHP_EOL;
			if ($config['cf_add_meta_bbs_description']) echo '<meta name="description" content="' . $config['cf_add_meta_bbs_description'] . '">' . PHP_EOL;
			if ($config['cf_add_meta_bbs_keywords']) echo '<meta name="keywords" content="' . $config['cf_add_meta_bbs_keywords'] . '">' . PHP_EOL;
		} else {
			if ($config['cf_add_meta_common_title']) echo '<meta name="title" content="' . $config['cf_add_meta_common_title'] . '">' . PHP_EOL;
			if ($config['cf_add_meta_common_author']) echo '<meta name="author" content="' . $config['cf_add_meta_common_author'] . '">' . PHP_EOL;
			if ($config['cf_add_meta_common_description']) echo '<meta name="description" content="' . $config['cf_add_meta_common_description'] . '">' . PHP_EOL;
			if ($config['cf_add_meta_common_keywords']) echo '<meta name="keywords" content="' . $config['cf_add_meta_common_keywords'] . '">' . PHP_EOL;
		}
	}
	?>
	<title><?= $g5_head_title; ?></title>
	<link rel="shortcut icon" href="/favicon.png" />
	<!--[if lte IE 8]>
	<script src="<?= G5_JS_URL ?>/html5.js"></script>
	<![endif]-->
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-153591131-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());
		gtag('config', 'UA-153591131-1');
	</script>
	<script>
		// 자바스크립트에서 사용하는 전역변수 선언
		var g5_url = "<?= G5_URL ?>";
		var g5_bbs_url = "<?= G5_BBS_URL ?>";
		var g5_is_member = "<?= isset($is_member) ? $is_member : ''; ?>";
		var g5_is_admin = "<?= isset($is_admin) ? $is_admin : ''; ?>";
		var g5_is_mobile = "<?= G5_IS_MOBILE ?>";
		var g5_bo_table = "<?= isset($bo_table) ? $bo_table : ''; ?>";
		var g5_sca = "<?= isset($sca) ? $sca : ''; ?>";
		var g5_editor = "<?= ($config['cf_editor'] && $board['bo_use_dhtml_editor']) ? $config['cf_editor'] : ''; ?>";
		var g5_cookie_domain = "<?= G5_COOKIE_DOMAIN ?>";
		<? if (defined('G5_IS_ADMIN')) { ?>
			var g5_admin_url = "<?= G5_ADMIN_URL; ?>";
		<? } ?>
	</script>
	<script src="<?= G5_JS_URL ?>/jquery-1.8.3.min.js"></script>
	<?
	if (defined('_SHOP_')) {
		if (!G5_IS_MOBILE) {
	?>
			<script src="<?= G5_JS_URL ?>/jquery.shop.menu.js?ver=<?= G5_JS_VER; ?>"></script>
		<?
		}
	} else {
		?>
		<script src="<?= G5_JS_URL ?>/jquery.menu.js?ver=<?= G5_JS_VER; ?>"></script>
	<? } ?>
	<script src="<?= G5_JS_URL ?>/common.js?ver=<?= G5_JS_VER; ?>"></script>
	<script src="<?= G5_JS_URL ?>/wrest.js?ver=<?= G5_JS_VER; ?>"></script>
	<script src="<?= G5_JS_URL ?>/placeholders.min.js"></script>
	<link rel="stylesheet" href="<?= G5_JS_URL ?>/font-awesome/css/font-awesome.min.css">
	<?
	if (G5_IS_MOBILE) {
		echo '<script src="' . G5_JS_URL . '/modernizr.custom.70111.js"></script>' . PHP_EOL; // overflow scroll 감지
	}
	if (!defined('G5_IS_ADMIN')) {
		echo $config['cf_add_script'];
	}
	echo $config['cf_add_html_head_mobile'];
	include_once(G5_LIB_PATH . '/popular.lib.php');
	?>
</head>
<body<?= isset($g5['body_script']) ? $g5['body_script'] : ''; ?>>
	<?= $config['cf_add_html_body_mobile']; ?>
	<!-- 스타일 -->
	<link rel="stylesheet" type="text/css" href="<?= G5_MOBILE_URL; ?>/js/swiper/swiper.min.css?v=<?= date('Ymdhis') ?>">
	<link rel="stylesheet" type="text/css" href="<?= G5_MOBILE_URL; ?>/css/m_common.css?v=<?= date('Ymdhis') ?>" />
	<link rel="stylesheet" type="text/css" href="<?= G5_MOBILE_URL; ?>/css/m_ui.css?v=<?= date('Ymdhis') ?>" />
	<link rel="stylesheet" type="text/css" href="<?= G5_MOBILE_URL; ?>/css/m_custom.css?v=<?= date('Ymdhis') ?>" />
	<link rel="stylesheet" type="text/css" href="<?= G5_URL ?>/css/axicon/axicon.min.css?v=<?= date('Ymdhis') ?>" />
	<!-- 스크립트 -->
	<script src="<?= G5_MOBILE_URL; ?>/js/swiper/swiper.min.js?v=<?= date('Ymdhis') ?>" type="text/javascript" charset="utf-8"></script>
	<script src="<?= G5_MOBILE_URL; ?>/js/m_ui.js?v=<?= date('Ymdhis') ?>" type="text/javascript"></script>
	<script src="<?= G5_MOBILE_URL; ?>/js/jquery.cookie.js?v=<?= date('Ymdhis') ?>" type="text/javascript"></script>
	<?php echo G5_POSTCODE_JS ?>

	<div id="wrap_all">
		<p class="skipNavi"></p>
		<div id="header" class="common">
			<!-- top_group -->
			<? if ($indexok) { ?>
				<div class="top_group" style="display: none;">
					<div class="title" id="gnbTitle">

					</div>
					<a href="#" class="top_close"><span class="blind">닫기</span></a>
				</div>
			<? } ?>
			<!-- logo_group -->
			<div class="logo_group">
				<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>
				<h1 class="logo"><a href="/index.php"><span class="blind">LIFELIKE</span>
						<? if ($test) : ?>
							<span style="margin-left: 80px; font-size: 24px; position: absolute; line-height: 16px; color: rgb(1, 186, 180, .9);">DEV</span>
						<? endif ?>
					</a></h1>
				<button type="button" class="btn_search" onclick="location.href='<?= G5_SHOP_URL ?>/search.php'"><span class="blind">검색</span></button>
				<a href="/shop/cart.php"><button type="button" class="btn_cart"><span class="blind">장바구니</span></button></a>
			</div>
			<!-- nav_group -->
			<div class="nav_group">
				<h2 class="blind">메뉴</h2>
				<div class="menu">
					<ul>
						<?
						$sql = " select *
					from {$g5['menu_table']}
					where me_mobile_use = '1'
					and length(me_code) = '2'
					order by me_order, me_id ";
						$result = sql_query($sql, false);

						for ($i = 0; $row = sql_fetch_array($result); $i++) {
						?>
							<li>
								<?
								$sql2 = " select *
							from {$g5['menu_table']}
							where me_mobile_use = '1'
							and length(me_code) = '4'
							and substring(me_code, 1, 2) = '{$row['me_code']}'
							order by me_order, me_id ";
								$result2 = sql_query($sql2);
								if (sql_num_rows($result2) > 0) {
									echo '<a href="#" target="_' . $row['me_target'] . '" class="dep1a1">' . $row['me_name'] . '</a>';
								} else {
									echo '<a href="' . $row['me_link'] . '" target="_' . $row['me_target'] . '" class="dep1a1">' . $row['me_name'] . '</a>';
								}
								$result2Cnt = -1;
								for ($k = 0; $row2 = sql_fetch_array($result2); $k++) {
									$result2Cnt = $k;
									if ($k == 0) {
								?>
										<div class="dep2">
											<ul>
											<? } ?>
											<li>
												<?
												$sql3 = " select *
											from {$g5['menu_table']}
											where me_mobile_use = '1'
											and length(me_code) = '6'
											and substring(me_code, 1, 4) = '{$row2['me_code']}'
											order by me_order, me_id ";
												$result3 = sql_query($sql3);

												if (sql_num_rows($result3) > 0) {
													echo '<a href="#" target="_' . $row['me_target'] . '" >' . $row2['me_name'] . '</a>';
												} else {
													echo '<a href="' . $row2['me_link'] . '" target="_' . $row2['me_target'] . '" class="dep1a1">' . $row2['me_name'] . '</a>';
												}

												$result3Cnt = -1;
												for ($l = 0; $row3 = sql_fetch_array($result3); $l++) {
													$result3Cnt = $l;
													if ($l == 0) {
												?>
														<div class="dep3 swiper-container<?= $k + 1 ?>">
															<ul class="swiper-wrapper">
															<? } ?>
															<li class="swiper-slide"><a href="<?= $row3['me_link']; ?>" target="_<?= $row3['me_target']; ?>">&nbsp;<?= $row3['me_name'] ?>&nbsp;</a></li>
														<? }
													if ($result3Cnt > -1) {
														?>
															</ul>
														</div>
													<? } ?>
											</li>
										<? }
									if ($result2Cnt > -1) {
										?>
											</ul>
										</div>
										<!--
										<div class="menu-gate menu-gate<?= $i + 1 ?>">
											<p><?= $row['me_name'] ?> 한눈에 보기</p>
											<span><a href="<?= $row['me_link']; ?>" target="_<?= $row['me_target']; ?>">바로가기</a></span>
										</div>
										-->
									<? } ?>
							</li>
						<? } ?>
					</ul>
				</div>
			</div>

		</div>

		<? include_once(G5_MOBILE_PATH . "/aside.php"); ?>

		<?
		if (defined('_INDEX_')) { // index에서만 실행
			include G5_MOBILE_PATH . '/newwin.inc.php'; // 팝업레이어
		} ?>


		<div id="container">