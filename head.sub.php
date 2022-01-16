<?
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
	include_once(G5_MOBILE_PATH . '/head.php');
	return;
}

// 테마 head.sub.php 파일
if (!defined('G5_IS_ADMIN') && defined('G5_THEME_PATH') && is_file(G5_THEME_PATH . '/head.sub.php')) {
	require_once(G5_THEME_PATH . '/head.sub.php');
	return;
}

$begin_time = get_microtime();

if (!isset($g5['title'])) {
	$g5['title'] = $config['cf_title'];
	$g5_head_title = $g5['title'];
} else {
	$g5_head_title = $g5['title']; // 상태바에 표시될 제목
	$g5_head_title .= " | " . $config['cf_title'];
}

$g5['title'] = strip_tags(get_text($g5['title']));
$g5_head_title = strip_tags(get_text($g5_head_title));

// 현재 접속자
// 게시판 제목에 ' 포함되면 오류 발생
$g5['lo_location'] = addslashes($g5['title']);
if (!$g5['lo_location'])
	$g5['lo_location'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
$g5['lo_url'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
if (strstr($g5['lo_url'], '/' . G5_ADMIN_DIR . '/') || $is_admin == 'super') $g5['lo_url'] = '';

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
		echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10; target-densitydpi=medium-dpi;">' . PHP_EOL;
		echo '<meta name="HandheldFriendly" content="true">' . PHP_EOL;
		echo '<meta name="format-detection" content="telephone=no">' . PHP_EOL;
	} else {
		echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">' . PHP_EOL;
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
	<?
	if (defined('G5_IS_ADMIN')) {
		if (!defined('_THEME_PREVIEW_'))
			echo '<link rel="stylesheet" href="' . G5_ADMIN_URL . '/css/admin.css">' . PHP_EOL;
	} else {
		//$shop_css = '';
		//if (defined('_SHOP_')) $shop_css = '_shop';
		//echo '<link rel="stylesheet" href="'.G5_CSS_URL.'/'.(G5_IS_MOBILE?'mobile':'default').$shop_css.'.css?ver='.G5_CSS_VER.'">'.PHP_EOL;
		if (G5_IS_MOBILE) {
			echo '<link rel="stylesheet" type="text/css" href="' . G5_MOBILE_URL . '/js/swiper/swiper.min.css" />' . PHP_EOL;
			echo '<link rel="stylesheet" type="text/css" href="' . G5_MOBILE_URL . '/css/m_common.css" />' . PHP_EOL;
			echo '<link rel="stylesheet" type="text/css" href="' . G5_MOBILE_URL . '/css/m_ui.css?v=' . date(Ymdhis) . '" />' . PHP_EOL;
		} else {
			echo '<link rel="stylesheet" href="' . G5_JS_URL . '/swiper/swiper.min.css?ver=' . G5_CSS_VER . '" />' . PHP_EOL;
			echo '<link rel="stylesheet" href="' . G5_CSS_URL . '/pc_common.css?ver=' . date(Ymdhis) . '" />' . PHP_EOL;
			echo '<link rel="stylesheet" href="' . G5_CSS_URL . '/pc_ui.css?v=' . date(Ymdhis) . '" />' . PHP_EOL;
			echo '<link rel="stylesheet" href="' . G5_CSS_URL . '/pc_custom.css?ver=' . date(Ymdhis) . '" />' . PHP_EOL;
		}
	}
	?>
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
	if (!defined('G5_IS_ADMIN'))
		echo $config['cf_add_script'];

	echo $config['cf_add_html_head_pc'];
	?>
</head>
<body<?= isset($g5['body_script']) ? $g5['body_script'] : ''; ?>>
	<?
	echo $config['cf_add_html_body_pc'];
	if ($is_member) { // 회원이라면 로그인 중이라는 메세지를 출력해준다.
		$sr_admin_msg = '';
		if ($is_admin == 'super') $sr_admin_msg = "최고관리자 ";
		else if ($is_admin == 'group') $sr_admin_msg = "그룹관리자 ";
		else if ($is_admin == 'board') $sr_admin_msg = "게시판관리자 ";

		//echo '<div id="hd_login_msg">'.$sr_admin_msg.get_text($member['mb_nick']).'님 로그인 중 ';
		//echo '<a href="'.G5_BBS_URL.'/logout.php">로그아웃</a></div>';
	}
	?>