<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_LIB_PATH . '/popular.lib.php');

if (defined('G5_THEME_PATH')) {
	require_once(G5_THEME_PATH . '/head.php');
	return;
}

if (G5_IS_MOBILE) {
	include_once(G5_MOBILE_PATH . '/head.php');
	return;
}

include_once(G5_PATH . '/head.sub.php');
?>

<!-- 스크립트 -->
<script src="<?= G5_JS_URL ?>/swiper/swiper.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="<?= G5_JS_URL ?>/pc_ui.js"></script>
<link rel="shortcut icon" href="/favicon.png" />
<link rel="stylesheet" type="text/css" href="/css/axicon/axicon.min.css" />

<!-- top_group -->
<? if ($indexok) { ?>
	<div class="top_group" style="display: none;">
		<div class="title" id="gnbTitle"></div>
		<a href="#" class="btn_closed"><span class="blind">닫기</span></a>
	</div>
	<script>
		$(function() {
			$(".top_group .btn_closed").click(function() {
				$(".top_group").css("display", "none");
			});
		});
	</script>
<? } ?>
<div class="wrap_all">
	<p id="skipNavi"><a href="#container">본문 바로가기</a></p>
	<!-- header -->
	<div id="header">
		<!-- logo_group -->
		<div class="logo_group">
			<a href="#" class="btn_menu"><span class="blind">메뉴</span></a>
			<h1 class="logo"><a href="/index.php"><span class="blind">LIFELIKE</span>
					<? if ($test) : ?>
						<span style="margin-left: 190px; font-size: 32px; position: absolute; line-height: 24px; color: rgb(1, 186, 180, .9);">DEV</span>
					<? endif ?>
				</a></h1>
			<div class="user_group">
				<ul>
					<? if ($is_member) {  ?>
						<li class=""><a href="<?= G5_SHOP_URL; ?>/mypage.php">마이페이지</a></li>
					<? } else {  ?>
						<li class=""><a href="<?= G5_BBS_URL ?>/login.php">로그인</a></li>
						<li class=""><a href="<?= G5_BBS_URL ?>/register.php">회원가입</a></li>
					<? }  ?>

					<li class=""><a href="<?= G5_SHOP_URL; ?>/cart.php">장바구니</a></li>

					<li class=""><a href="<?= G5_BBS_URL ?>/faq.php">고객센터</a></li>
					<? if ($is_member) {  ?>
						<li class=""><a href="<?= G5_BBS_URL; ?>/logout.php">로그아웃</a></li>
					<? }  ?>
					<? if ($is_admin) {  ?>
						<li class=""><a href="<?= G5_ADMIN_URL ?>">관리자</a></li>
					<? }  ?>
				</ul>
			</div>
			<div class="link">
				<ul>
					<li><a href="<?= G5_BBS_URL ?>/board.php?bo_table=notice">공지사항</a></li>
					<!-- li><a href="#">베스트</a></li -->
					<li><a href="<?= G5_BBS_URL ?>/board.php?bo_table=event">이벤트</a></li>
					<li><a href="<?= G5_BBS_URL ?>/board.php?bo_table=review">리뷰</a></li>
				</ul>
				<a href="<?= G5_SHOP_URL ?>/search.php" style="margin-left: -20px;"><button type="button" class="btn_search"><span class="blind">검색</span></button></a>
			</div>
		</div>
		<? include_once(G5_PATH . "/aside.php"); ?>
		<!-- nav_group -->
		<nav class="nav_group">
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
							<a href="<?= $row['me_link']; ?>" target="_<?= $row['me_target']; ?>"><?= $row['me_name'] ?></a>
							<?
							$sql2 = " select *
							from {$g5['menu_table']}
							where me_mobile_use = '1'
							and length(me_code) = '4'
							and substring(me_code, 1, 2) = '{$row['me_code']}'
							order by me_order, me_id ";
							$result2 = sql_query($sql2);
							$result2Cnt = -1;
							for ($k = 0; $row2 = sql_fetch_array($result2); $k++) {
								$result2Cnt = $k;
								if ($k == 0) {
							?>
									<div class="depth2">
										<ul>
										<? } ?>
										<li><a href="<?= $row2['me_link']; ?>" target="_<?= $row2['me_target']; ?>"><?= $row2['me_name'] ?></a>
											<?
											$sql3 = " select *
											from {$g5['menu_table']}
											where me_mobile_use = '1'
											and length(me_code) = '6'
											and substring(me_code, 1, 4) = '{$row2['me_code']}'
											order by me_order, me_id ";
											$result3 = sql_query($sql3);
											$result3Cnt = -1;
											for ($l = 0; $row3 = sql_fetch_array($result3); $l++) {
												$result3Cnt = $l;
												if ($l == 0) {
											?>
													<div class="depth3">
														<ul>
														<? } ?>
														<li><a href="<?= $row3['me_link']; ?>" target="_<?= $row3['me_target']; ?>"><?= $row3['me_name'] ?></a></li>
													<? }
												if ($result3Cnt > -1) {
													?>
														</ul>
													</div>
												<? } ?>
										</li>
									<? } ?>
									<? if ($result2Cnt > -1) { ?>
										</ul>
									</div>
								<? } ?>
						</li>
					<? } ?>
				</ul>
			</div>
		</nav>
	</div>
	<!-- //header -->
	<?
	if (defined('_INDEX_')) {
		// index에서만 실행
		include G5_BBS_PATH . '/newwin.inc.php'; // 팝업레이어
	}
	?>