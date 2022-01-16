<?
//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
require_once($_SERVER['DOCUMENT_ROOT'] . "/common.php");
require_once(G5_LIB_PATH . '/badge.lib.php');
ini_set('display_errors', 1);

define("_INDEX_", TRUE);

//$g5['title'] = "당신의 삶을 더 편한 공간으로, 리페리케";
$indexok = true;

include_once(G5_MOBILE_PATH . '/_head.php');

$badgeObj = new badge();
$sql_common = " from lt_design_main_mobile ";
$sql_where = " where (1) ";

if ($_POST['previewonofflist']) {
	$previewonofflist = json_decode(str_replace('\\', '', $previewonofflist), true);
}
?>

<?
$sql = " select main_id, main_name, main_fixed, main_order, mobile_onoff, main_type1, main_type2, main_view_data, main_datetime $sql_common $sql_where ";
if ($_POST['preview_main_id']) {
	$sql .= " and main_id = '" . $_POST['preview_main_id'] . "'";
}
$sql .= " order by main_order ";

$result = sql_query($sql);
$i = 0;

while ($row = sql_fetch_array($result)) {
	$main_id = $row['main_id'];

	if ($previewonofflist[$main_id] != '' && $previewonofflist[$main_id] == "N") continue;
	if ($row['mobile_onoff'] == "N") continue;

	$main_view_data = json_decode(str_replace('\\', '', $row['main_view_data']), true);

	$fixView = false;
	switch ($row['main_id']) {
		case 1: {
?>
				<script>
					$('#gnbTitle').html('<?= $main_view_data['title_name'] ?>');
				</script>
			<?
											$fixView = true;
										}
										break;
									case 2: {
			?>
				<div class="main_visual">
					<div class="swiper-container">
						<div class="swiper-wrapper">
							<? for ($i = 0; $i < $row['main_type2']; $i++) {
												$img_data = $main_view_data['imgFile'][$i];
												$link_url = $img_data['linkURL'];
												$img_file = G5_DATA_PATH . '/design/' . $main_id . '/' . $img_data['imgFile'];
												if ($img_data['imgFile'] && file_exists($img_file)) {
													$img_url = G5_DATA_URL . '/design/' . $main_id . '/' . $img_data['imgFile'];
							?>
									<div class="swiper-slide"><a href="<?= $link_url ?>"><img src="<?= $img_url ?>" alt="" /></a></div>
								<? } else { ?>
									<div class="swiper-slide"><a href="<?= $link_url ?>"><img src="<?= G5_MOBILE_URL; ?>/img/theme_img.jpg" alt="" /></a></div>
							<? }
																							} ?>
						</div>
						<div class="swiper-pagination"></div>
					</div>
				</div>
				<script>
					var swiperMain_visual = new Swiper('.main_visual .swiper-container', {
						slidesPerView: 'auto',
						spaceBetween: 0,
						loop: true,
						autoplay: {
							delay: 2500,
							disableOnInteraction: false,
						},
						pagination: {
							el: '.swiper-pagination',
							clickable: true,
						},
					});
				</script>

			<?
																							$fixView = true;
																						}
																						break;
																					case 3: {
			?>
				<div class="content">
					<!-- column_group -->
					<div class="column_group" style="display: none;">
						<!-- h3 class="main_title">Living Standard</h3 -->
						<?
																							$movieimg_file = G5_DATA_PATH . '/design/' . $main_id . '/' . $main_view_data['movieimg'];
																							if (file_exists($movieimg_file)) {
																								$movieimg_url = G5_DATA_URL . '/design/' . $main_id . '/' . $main_view_data['movieimg'];

																								if ($main_view_data['moviefile'] != "") {
																									$moviefile_url = G5_DATA_URL . '/design/' . $main_id . '/' . $main_view_data['moviefile'];
																								} else {
																									$moviefile_url = $main_view_data['linkURL'];
																								}

						?>
							<div class="column_one <?= ($main_view_data['moviefile'] != "") ? "ico_video" : "" ?>">
								<a href="<?= $moviefile_url ?>"><img src="<?= $movieimg_url ?>" alt="" /></a>
							</div>
						<? } else { ?>
							<div class="column_one">
								<a href="<?= $moviefile_url ?>"><img src="<?= G5_MOBILE_URL; ?>/img/theme_img.jpg" alt="" /></a>
							</div>
						<? } ?>
					<?
																							$fixView = true;
																						}
																						break;
																					case 4: {
					?>
						<div class="column_one">
							<div class="column_swiper">
								<div class="swiper-wrapper">
									<? for ($i = 0; $i < $row['main_type2']; $i++) {
																								$img_data = $main_view_data['imgFile'][$i];
																								$link_url = $img_data['linkURL'];
																								$img_file = G5_DATA_PATH . '/design/' . $main_id . '/' . $img_data['imgFile'];
																								if ($img_data['imgFile'] && file_exists($img_file)) {
																									$img_url = G5_DATA_URL . '/design/' . $main_id . '/' . $img_data['imgFile'];
									?>
											<div class="swiper-slide"><a href="<?= $link_url ?>"><img src="<?= $img_url ?>" alt="" /></a></div>
										<? } else { ?>
											<div class="swiper-slide"><a href="<?= $link_url ?>"><img src="<?= G5_MOBILE_URL; ?>/img/theme_img.jpg" alt="" /></a></div>
									<? }
																									} ?>
								</div>
								<div class="swiper-pagination"></div>
							</div>
							<script>
								var swiperMain_visual = new Swiper('.column_swiper', {
									slidesPerView: 'auto',
									spaceBetween: 0,
									loop: true,
									/*
									autoplay: {
										delay: 4000,
										disableOnInteraction: false,
									},
									*/
									pagination: {
										el: '.swiper-pagination',
										clickable: true,
									},
								});
							</script>
						</div>
					<?
																									$fixView = true;
																								}
																								break;
																							case 5: {
					?>
						<div class="column_three">
							<h3 class="main_title"><?= $main_view_data['title_name'] ?></h3>
							<div class="grid">
								<div class="item_row_list rolling_wrap2 swiper-container">
									<ul class="swiper-wrapper">
										<?
																									for ($i = 0; $i < $row['main_type2']; $i++) {
																										$sql2 = "select it_name,it_price, it_img1, it_id, it_rental_price, it_item_type from lt_shop_item where it_id = '{$main_view_data['it_id'][$i]}' and it_use = 1";
																										$row2 = sql_fetch($sql2);
																										if ($row2) {
										?>
												<li class="swiper-slide">
													<?php
																											$link_url = G5_URL . '/shop/item.php?it_id=' . $row2['it_id'];
																											$img_data = $row2['it_img1'];
																											$img_file = G5_DATA_PATH . '/item/' . $img_data;

																											echo "<div class='photo'><a href='{$link_url}'>";
																											if ($img_data && file_exists($img_file)) {
																												$img_url = G5_DATA_URL . "/item/" . $img_data;
																												echo "<img src='" . $img_url . "' />";
																											} else {
																												echo "<img src='" . G5_MOBILE_URL . "/img/theme_img.jpg' />";
																											}
																											echo "</a></div>";
													?>
													<div class="cont">
														<div class="inner">
															<a href="<?= $link_url ?>"><strong class="title bold"><?= $row2['it_name'] ?></strong></a>
															<a href="<?= $link_url ?>"><span class="price"><?= ($row2['it_item_type']) ? number_format($row2['it_rental_price']) : number_format($row2['it_price']) ?> 원</span></a>
														</div>
													</div>
												</li>
										<?
																												}
																											}
										?>
									</ul>
								</div>
							</div>
						</div>
					<?
																											$fixView = true;
																										}
																										break;
																									case 6: {
					?>
						<div class="column_two">
							<h3 class="main_title"><?= $main_view_data['title_name'] ?></h3>
							<ul>
								<? for ($i = 0; $i < $row['main_type2']; $i++) {
																												$img_data = $main_view_data['imgFile'][$i];
																												$link_url = $img_data['linkURL'];
																												$img_file = G5_DATA_PATH . '/design/' . $main_id . '/' . $img_data['imgFile'];
																												if ($img_data['imgFile'] && file_exists($img_file)) {
																													$img_url = G5_DATA_URL . '/design/' . $main_id . '/' . $img_data['imgFile'];
								?>
										<li><a href="<?= $link_url ?>"><img src="<?= $img_url ?>" alt="" /></a></li>
									<? } else { ?>
										<li><a href="<?= $link_url ?>"><img src="<?= G5_MOBILE_URL; ?>/img/theme_img.jpg" alt="" /></a></li>
								<? }
																											} ?>
							</ul>
						</div>
					</div>
				<?
																											$fixView = true;
																										}
																										break;
																								}
																								if ($fixView) continue;


																								switch ($row['main_type1']) {
																									case "gnb": {
				?>
					<script>
						$('#gnbTitle').html('<?= $main_view_data['title_name'] ?>');
					</script>
				<?
																										}
																										break;
																									case "rolling": {

				?>
					<div class="section_content brand">
						<h3 class="main_title"><?= $main_view_data['title_name'] ?></h3>
						<div class="swiper-container">
							<ul class="swiper-wrapper">
								<? for ($i = 0; $i < $row['main_type2']; $i++) {
																												$img_data = $main_view_data['imgFile'][$i];
																												$link_url = $img_data['linkURL'];
																												$img_file = G5_DATA_PATH . '/design/' . $main_id . '/' . $img_data['imgFile'];
																												if ($img_data['imgFile'] && file_exists($img_file)) {
																													$img_url = G5_DATA_URL . '/design/' . $main_id . '/' . $img_data['imgFile'];
								?>
										<li class="swiper-slide"><a href="<?= $link_url ?>">
												<div class="photo"><img src="<?= $img_url ?>" alt="" /></div>
											</a></li>
									<? } else { ?>
										<li class="swiper-slide"><a href="<?= $link_url ?>">
												<div class="photo"><img src="<?= G5_MOBILE_URL; ?>/img/theme_img.jpg" alt="" /></div>
											</a></li>
								<? }
																											} ?>
							</ul>
							<div class="swiper-pagination green"></div>
						</div>
					</div>

				<?
																										}
																										break;
																									case "image": {
				?>
					<div class="column_two">
						<h3 class="main_title"><?= $main_view_data['title_name'] ?></h3>
						<ul>
							<? for ($i = 0; $i < $row['main_type2']; $i++) {
																												$img_data = $main_view_data['imgFile'][$i];
																												$link_url = $img_data['linkURL'];
																												$img_file = G5_DATA_PATH . '/design/' . $main_id . '/' . $img_data['imgFile'];
																												if ($img_data['imgFile'] && file_exists($img_file)) {
																													$img_url = G5_DATA_URL . '/design/' . $main_id . '/' . $img_data['imgFile'];
							?>
									<li><a href="<?= $link_url ?>"><img src="<?= $img_url ?>" alt="" /></a></li>
								<? } else { ?>
									<li><a href="<?= $link_url ?>"><img src="<?= G5_MOBILE_URL; ?>/img/theme_img.jpg" alt="" /></a></li>
							<? }
																											} ?>
						</ul>
					</div>
				<?
																										}
																										break;
																									case "imagetext": {
				?>
					<div class="section_content magazine">
						<div class="fix_wrap">
							<h3 class="main_title"><?= $main_view_data['title_name'] ?></h3>
							<ul class="thumb_list col<?= $row['main_type2'] ?>">
								<? for ($i = 0; $i < $row['main_type2']; $i++) {
																												$img_data = $main_view_data['imgFile'][$i];
																												$link_url = $img_data['linkURL'];
																												$img_file = G5_DATA_PATH . '/design/' . $main_id . '/' . $img_data['imgFile'];
																												if ($img_data['imgFile'] && file_exists($img_file)) {
																													$img_url = G5_DATA_URL . '/design/' . $main_id . '/' . $img_data['imgFile'];

								?>
										<li>
											<a href="<?= $link_url ?>">
												<div class="photo"><img src="<?= $img_url ?>" alt="" /></div>
												<div class="cont">
													<span class="title"><?= $img_data['mainText'] ?><br><?= $img_data['subText'] ?></span>
												</div>
											</a>
										</li>
								<? }
																											} ?>
							</ul>
							<a href="#" class="btn_more"><span class="blind">더보기</span></a>
						</div>
					</div>
				<?
																										}
																										break;
																									case "banner": {
				?>
					<div class="banner_bar">
						<h3 class="main_title"><?= $main_view_data['title_name'] ?></h3>
						<div class="banner_bar_swiper">
							<div class="swiper-wrapper">
								<? for ($i = 0; $i < $row['main_type2']; $i++) {
																												$img_data = $main_view_data['imgFile'][$i];
																												$link_url = $img_data['linkURL'];
																												$img_file = G5_DATA_PATH . '/design/' . $main_id . '/' . $img_data['imgFile'];
																												if ($img_data['imgFile'] && file_exists($img_file)) {
																													$img_url = G5_DATA_URL . '/design/' . $main_id . '/' . $img_data['imgFile'];
								?>
										<div class="swiper-slide"><a href="<?= $link_url ?>"><img src="<?= $img_url ?>" alt="" /></a></div>
									<? } else { ?>
										<div class="swiper-slide"><a href="<?= $link_url ?>"><img src="<?= G5_MOBILE_URL; ?>/img/theme_img.jpg" alt="" /></a></div>
								<? }
																											} ?>
							</div>
							<div class="swiper-pagination"></div>
						</div>
						<script>
							var swiperNew = new Swiper('.banner_bar_swiper', {
								slidesPerView: 'auto',
								spaceBetween: 0,
								//loop: true,

								pagination: {
									el: '.swiper-pagination',
									clickable: true,
								},
							});
						</script>
					</div>
				<?
																										}
																										break;
																									case "motion": {
				?>

				<?
																										}
																										break;
																									case "product": {
				?>
					<div class="column_three">
						<h3 class="main_title"><?= $main_view_data['title_name'] ?></h3>
						<div class="grid">
							<div class="item_row_list rolling_wrap2 swiper-container">
								<ul class="swiper-wrapper">
									<?
																											for ($i = 0; $i < $row['main_type2']; $i++) {
																												$sql2 = "select it_name,it_price, it_img1, it_id, it_rental_price, it_item_type from lt_shop_item where it_id = '{$main_view_data['it_id'][$i]}' and it_use = 1";
																												$result2 = sql_query($sql2);
																												for ($k = 0; $row2 = sql_fetch_array($result2); $k++) {

									?>
											<li class="swiper-slide">
												<div class="photo">
													<?
																													$img_data = $row2['it_img1'];
																													$img_file = G5_DATA_PATH . '/item/' . $img_data;
																													$link_url = G5_URL . '/shop/item.php?it_id=' . $row2['it_id'];
																													if ($img_data && file_exists($img_file)) {
																														$img_url = G5_DATA_URL . '/item/' . $img_data;

													?>
														<a href="<?= $link_url ?>"><img src="<?= $img_url ?>" alt="" /></a>
													<? } else { ?>
														<a href="<?= $link_url ?>"><img src="<?= G5_MOBILE_URL; ?>/img/theme_img.jpg" alt="" /></a>
													<? }	?>
												</div>
												<div class="cont">
													<div class="inner">
														<strong class="title bold"><?= $row2['it_name'] ?></strong>
														<span class="price"><?= ($row2['it_item_type']) ? number_format($row2['it_rental_price']) : number_format($row2['it_price']) ?> 원</span>
													</div>
												</div>
											</li>
									<?
																												}
																											}
									?>
								</ul>
							</div>
						</div>

					</div>
				<?
																										}
																										break;
																									case "subproduct": {
				?>
					<div class="section_content best border_top">
						<div class="grid">
							<h3 class="main_title"><?= $main_view_data['title_name'] ?></h3>
							<div class="item_row_list">
								<ul class="count2">
									<?
																											for ($i = 0; $i < $row['main_type2']; $i++) {
																												$sql2 = "select it_name, it_price, it_img1, it_id, it_basic, it_rental_price, it_item_type, it_view_list_items, ca_id, it_time from lt_shop_item where it_id = '{$main_view_data['it_id'][$i]}' and it_use = 1;";
																												//echo $sql2;
																												$result2 = sql_query($sql2);
																												for ($k = 0; $row2 = sql_fetch_array($result2); $k++) {
																													$link_url = G5_URL . '/shop/item.php?it_id=' . $row2['it_id'];
									?>
											<li>
												<a href="<?= $link_url ?>">
													<?php
																													$badgeObj->item = $row2;
																													$badgeObj->makeHtml();
																													$badgeHtml = $badgeObj->photoHtml;
																													$img_data = $row2['it_img1'];
																													$img_file = G5_DATA_PATH . '/item/' . $img_data;

																													if ($img_data && file_exists($img_file)) {
																														$img_url = G5_DATA_URL . '/item/' . $img_data;
																														$badgeObj->innerHtml = "<img src='" . $img_url . "' />";
																													} else {
																														$badgeObj->innerHtml = "<img src='" . G5_MOBILE_URL . "/img/theme_img.jpg' />";
																													}

																													$badgeObj->makeHtml();
																													echo $badgeObj->photoHtml;
													?>

													<div class="cont">
														<div class="inner">
															<strong class="title bold ellipsis"><?= $row2['it_name'] ?></strong>
															<span class="text ellipsis"><?= $row2['it_basic'] ?></span>
															<span class="price"><?= ($row2['it_item_type']) ? number_format($row2['it_rental_price']) : number_format($row2['it_price']) ?> 원</span>
														</div>
													</div>
												</a>
											</li>
									<?
																												}
																											}
									?>

								</ul>
							</div>
							<a href="<?= G5_MOBILE_URL; ?>/.shop/index.php?device=mobile" class="btn_more"><span class="blind">더보기</span></a>
						</div>
					</div>
				<?
																										}
																										break;
																									case "movie": {
				?>
					<?
																											$movieimg_file = G5_DATA_PATH . '/design/' . $main_id . '/' . $main_view_data['movieimg'];
																											if (file_exists($movieimg_file)) {
																												$movieimg_url = G5_DATA_URL . '/design/' . $main_id . '/' . $main_view_data['movieimg'];

																												if ($main_view_data['moviefile'] != "") {
																													$moviefile_url = G5_DATA_URL . '/design/' . $main_id . '/' . $main_view_data['moviefile'];
																												} else {
																													$moviefile_url = $main_view_data['linkURL'];
																												}

					?>
						<div class="video_container <?= ($main_view_data['moviefile'] != "") ? "ico_video" : "" ?>">
							<a href="<?= $moviefile_url ?>"><img src="<?= $movieimg_url ?>" alt="" /></a>
						</div>
					<? } else { ?>
						<div class="video_container ">
							<a href="<?= $moviefile_url ?>"><img src="<?= G5_MOBILE_URL; ?>/img/theme_img.jpg" alt="" /></a>
						</div>
					<? } ?>
				<?
																										}
																										break;
																									case "sns": {
				?>
					<div class="section_content instagram">
						<div class="fix_wrap">
							<h3 class="main_title"><?= $main_view_data['title_name'] ?></h3>
							<ul class="thumb_list">
								<li><a href="#"><img src="../img/mb/main/sample_instagram.jpg" alt="instagram photo" /></a></li>
								<li><a href="#"><img src="../img/mb/main/sample_instagram.jpg" alt="instagram photo" /></a></li>
								<li><a href="#"><img src="../img/mb/main/sample_instagram.jpg" alt="instagram photo" /></a></li>
							</ul>
						</div>
					</div>
	<?
																										}
																										break;
																									default: {
																										}
																										break;
																								}
																							}
	?>
	<script>
		var swiperColumn_product = new Swiper('.column_three .swiper-container', {
			slidesPerView: 'auto',
			spaceBetween: 10,
			//loop: true,
		});

		var swiperBrand = new Swiper('.section_content.brand .swiper-container', {
			slidesPerView: 'auto',
			spaceBetween: 10,
			pagination: {
				el: '.swiper-pagination',
				clickable: true,
			},
		});
	</script>
	<? include_once(G5_MOBILE_PATH . '/_tail.php'); ?>