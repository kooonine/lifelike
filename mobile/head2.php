<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$begin_time = get_microtime();

if (!isset($g5['title'])) {
	$g5['title'] = $config['cf_title'];
	$g5_head_title = $g5['title'];
} else {
	$g5_head_title = $g5['title']; // 상태바에 표시될 제목
	$g5_head_title .= " | ".$config['cf_title'];
}

// 현재 접속자
// 게시판 제목에 ' 포함되면 오류 발생
$g5['lo_location'] = addslashes($g5['title']);
if (!$g5['lo_location'])
	$g5['lo_location'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
$g5['lo_url'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
if (strstr($g5['lo_url'], '/'.G5_ADMIN_DIR.'/') || $is_admin == 'super') $g5['lo_url'] = '';

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
		echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10">'.PHP_EOL;
		echo '<meta name="HandheldFriendly" content="true">'.PHP_EOL;
		echo '<meta name="format-detection" content="telephone=no">'.PHP_EOL;
	} else {
		echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10">'.PHP_EOL;
		echo '<meta http-equiv="imagetoolbar" content="no">'.PHP_EOL;
		echo '<meta http-equiv="X-UA-Compatible" content="IE=Edge">'.PHP_EOL;
	}

	if($config['cf_add_meta']){
		echo $config['cf_add_meta'].PHP_EOL;
	}
	?>
	<title><?=$g5_head_title; ?></title>

<!--[if lte IE 8]>
<script src="<?=G5_JS_URL ?>/html5.js"></script>
<![endif]-->
<script>
// 자바스크립트에서 사용하는 전역변수 선언
var g5_url 			= "<?=G5_URL ?>";
var g5_bbs_url		= "<?=G5_BBS_URL ?>";
var g5_is_member	= "<?=isset($is_member)?$is_member:''; ?>";
var g5_is_admin 	= "<?=isset($is_admin)?$is_admin:''; ?>";
var g5_is_mobile 	= "<?=G5_IS_MOBILE ?>";
var g5_bo_table 	= "<?=isset($bo_table)?$bo_table:''; ?>";
var g5_sca 			= "<?=isset($sca)?$sca:''; ?>";
var g5_editor 		= "<?=($config['cf_editor'] && $board['bo_use_dhtml_editor'])?$config['cf_editor']:''; ?>";
var g5_cookie_domain = "<?=G5_COOKIE_DOMAIN ?>";
<? if(defined('G5_IS_ADMIN')) { ?>
	var g5_admin_url = "<?=G5_ADMIN_URL; ?>";
<? } ?>
</script>
<script src="<?=G5_JS_URL ?>/jquery-1.8.3.min.js"></script>
<?
if (defined('_SHOP_')) {
	if(!G5_IS_MOBILE) {
		?>
		<script src="<?=G5_JS_URL ?>/jquery.shop.menu.js?ver=<?=G5_JS_VER; ?>"></script>
		<?
	}
} else {
?>
<script src="<?=G5_JS_URL ?>/jquery.menu.js?ver=<?=G5_JS_VER; ?>"></script>
<? } ?>
<script src="<?=G5_JS_URL ?>/common.js?ver=<?=G5_JS_VER; ?>"></script>
<script src="<?=G5_JS_URL ?>/wrest.js?ver=<?=G5_JS_VER; ?>"></script>
<script src="<?=G5_JS_URL ?>/placeholders.min.js"></script>
<link rel="stylesheet" href="<?=G5_JS_URL ?>/font-awesome/css/font-awesome.min.css">
<?
if(G5_IS_MOBILE) {
	echo '<script src="'.G5_JS_URL.'/modernizr.custom.70111.js"></script>'.PHP_EOL; // overflow scroll 감지
}
if(!defined('G5_IS_ADMIN')){
	echo $config['cf_add_script'];
}
?>
</head>
<body>
	<!-- 스타일 -->
	<link rel="stylesheet" type="text/css" href="<?=G5_MOBILE_URL; ?>/js/swiper/swiper.min.css">
	<link rel="stylesheet" type="text/css" href="<?=G5_MOBILE_URL; ?>/css/m_common.css" />
	<link rel="stylesheet" type="text/css" href="<?=G5_MOBILE_URL; ?>/css/m_ui.css" />

	<!-- 스크립트 -->
	<script src="<?=G5_MOBILE_URL;?>/js/jquery-1.8.3.min.js" type="text/javascript"></script>
	<script src="<?=G5_MOBILE_URL;?>/js/swiper/swiper.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?=G5_MOBILE_URL;?>/js/m_ui.js" type="text/javascript"></script>
	<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>

	<div id="wrap_all">
		<p class="skipNavi"></p>
		<div id="header" class="common">
			<!-- top_group -->
			<? if($indexok) { ?>
				<div class="top_group">
					<div class="title" id="gnbTitle">
					</div>
					<a href="#" class="top_close"><span class="blind">닫기</span></a>
				</div>
			<? } ?>
			<!-- logo_group -->
			<div class="logo_group">
				<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>
				<h1 class="logo"><a href="#"><span class="blind">LIFELIKE</span></a></h1>
				<button type="button" class="btn_search"><span class="blind">검색</span></button>
				<button type="button" class="btn_cart"><span class="blind">장바구니</span></button>
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

						for($i=0; $row=sql_fetch_array($result); $i++) {
							?>
							<li>
								<a href="<?=$row['me_link']; ?>" target="_<?=$row['me_target']; ?>"><?=$row['me_name'] ?></a>
								<?
								$sql2 = " select *
								from {$g5['menu_table']}
								where me_mobile_use = '1'
								and length(me_code) = '4'
								and substring(me_code, 1, 2) = '{$row['me_code']}'
								order by me_order, me_id ";
								$result2 = sql_query($sql2);
								$result2Cnt = -1;
								for ($k=0; $row2=sql_fetch_array($result2); $k++) {
									$result2Cnt = $k;
									if($k == 0){
										?>
										<div class="dep2">
											<ul>
											<? } ?>
											<li><a href="<?=$row2['me_link']; ?>" target="_<?=$row2['me_target']; ?>"><?=$row2['me_name'] ?></a>
												<?
												$sql3 = " select *
												from {$g5['menu_table']}
												where me_mobile_use = '1'
												and length(me_code) = '6'
												and substring(me_code, 1, 4) = '{$row2['me_code']}'
												order by me_order, me_id ";
												$result3 = sql_query($sql3);
												$result3Cnt = -1;
												for ($l=0; $row3=sql_fetch_array($result3); $l++) {
													$result3Cnt = $l;
													if($l == 0){
														?>
														<div class="dep3">
															<ul>
															<? } ?>
															<li><a href="<?=$row3['me_link']; ?>" target="_<?=$row3['me_target']; ?>"><?=$row3['me_name'] ?></a></li>
														<? }
														if($result3Cnt > -1) {
															?>
														</ul>
													</div>
												<? }?>
											</li>
										<? }
										if($result2Cnt > -1) {
											?>
										</ul>
									</div>
								<? }?>
							</li>
						<? } ?>
					</ul>
				</div>
			</div>

		</div>

		<? include_once(G5_MOBILE_PATH."/aside.php");?>



		<div id="container">

