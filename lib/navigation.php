	<?php
	if ($it['ca_id']) {
		$ca_id = $it['ca_id'];
	} else {
		$ca_id = $_REQUEST['ca_id'];
	}

	$device = G5_IS_MOBILE ? 'M' : 'PC';
	$sql_navi = "SELECT ca_name, ca_1 FROM lt_shop_category WHERE ca_id = '" . $ca_id . "' ";
	$result_navi = sql_query($sql_navi);
	$row_navi = sql_fetch_array($result_navi);

	if (strpos($_SERVER['SCRIPT_NAME'], "list.php") !== false) {
		$db_banners = sql_query("SELECT bn_id, bn_alt, bn_url, bn_file FROM lt_shop_category_banner WHERE bn_device='" . $device . "' AND ca_id='" . $ca_id . "'");

		if ($db_banners->num_rows > 0) : ?>
			<div id="navigation-banner-wrapper" style="height: 0; overflow: hidden; transition: height .5s ease-out;">
				<div class="swiper-wrapper">
					<? for ($bi = 0; $banner = sql_fetch_array($db_banners); $bi++) : ?>
						<div class="swiper-slide">
							<a href="<?= $banner['bn_url'] ?>"><img class="navigation-banner" src="/data/banner/category/<?= $banner['bn_file'] ?>" alt="<?= $banner['bn_alt'] ?>" style="width: 100%;"></a>
						</div>
					<? endfor ?>
				</div>
			</div>
		<? endif; ?>
	<? } ?>
	<? if ($device == 'PC') : ?>
		<div class="naviagtionArea">
			<div class="navi">
				<div class="naviTitle">
					<?= $title ?>
					<?= $ca_id ? $row_navi['ca_name'] : $board['bo_subject']; ?>
				</div>
				<ul>
					<li>HOME</li>
					<? if ($_REQUEST['bo_table'] == 'notice' || $_REQUEST['bo_table'] == 'event' || $_REQUEST['bo_table'] == 'experience' || $_REQUEST['bo_table'] == 'review' || $_REQUEST['bo_table'] == 'online') { ?>
						<li>커뮤니티</li>
					<? } else if ($_REQUEST['bo_table'] == 'living' || $_REQUEST['bo_table'] == 'campaign') { ?>
						<li>매거진</li>
					<? } else if ($ca_id) { ?>
						<li>라이프라이크</li>
					<? } else if ($od['od_type']) { ?>
						<li>마이페이지</li>
					<? } else if ($todapth) { ?>
						<li><?= $todapth ?></li>
					<? } ?>
					<li>
						<?= $title ?>
						<?= $ca_id ? $row_navi['ca_name'] : $board['bo_subject']; ?>
					</li>
				</ul>
				<div class="clear"></div>
			</div>
		</div>
	<? endif ?>
	<script type="text/javascript">
		var swiper;

		$(function() {
			function updateNavigationWrapperSize() {
				const h = ($(swiper.el).width() / 1920) * 320;
				$("#navigation-banner-wrapper").height(h);
			}

			$(document).ready(function() {
				const slideCount = $("#navigation-banner-wrapper img").length;
				swiper = new Swiper("#navigation-banner-wrapper", {
					autoplay: {
						delay: 4000,
						stopOnLastSlide: true
					},
					loop: slideCount < 2 ? false : true,
					effect: 'fade'
				});

				swiper.on("resize", updateNavigationWrapperSize);
				const t = setTimeout(updateNavigationWrapperSize, 500);
			});

			$(window).on("resize", function() {
				swiper.updateSize();
			});
		});
	</script>