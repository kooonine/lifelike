<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// 리스

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);

$subsql = " select * from lt_shop_item_sub where it_id = '{$it['it_id']}' ";
$subresult = sql_query($subsql);

$subits = array();
$subits_count = 0;
for ($i = 0; $its = sql_fetch_array($subresult); $i++) {
	$subits[] = $its;
	$subits_count++;
}

require_once(G5_LIB_PATH . '/badge.lib.php');
$badgeObj = new badge();
?>

<style>
	#select-size-selector {
		color: #9b9b9b;
		border: 1px solid #cfcfcf;
	}

	#select-size-selector>option {
		color: #000000
	}

	#selected-option {
		margin-bottom: 20px;
		color: #888888;
	}

	div#selected-option>div {
		background-color: #f5f5f5;
		border: 1px solid #cfcfcf;
		border-width: 1px 0;
		padding: 6px 10px;
	}

	.icon-pack {
		width: 25px;
		height: 20px;
		display: inline-block;
		background: url(<?= G5_IMG_URL ?>/mb/ico/ico_pack.png) 0 0 no-repeat;
		vertical-align: middle;
	}

	.icon-laundry {
		width: 20px;
		margin-right: 17px;
		background-position-x: -3px;
	}

	.icon-delevery {
		margin-right: 12px;
		background-position-x: -25px;
	}

	.cont-foot em {
		font-size: 24px;
		font-family: NSBold;
	}

	button.btn-sit-opt-remove {
		display: inline-block;
		width: 19px;
		height: 19px;
		background-color: #ffffff;
		border: 1px solid #707070;
		position: absolute;
		margin-top: 2px;
		right: 8px;
		font-size: 20px;
		color: #707070;
	}

	button.btn-sit-opt-remove>span {
		margin-top: -18px;
		left: 2px;
		position: absolute;
	}
</style>

<form name="fitem" method="post" action="<?= $action_url; ?>" onsubmit="return fitem_submit(this);">
	<input type="hidden" name="it_id[]" value="<?= $it_id; ?>">
	<input type="hidden" name="od_type" value="1">
	<input type="hidden" name="sw_direct">
	<input type="hidden" name="url">

	<!-- container -->
	<div id="container" class="no_title">
		<!-- lnb -->
		<div id="lnb" class="header_bar blind">
			<h1 class="title"><span>상세</span></h1>
		</div>
		<!-- // lnb -->
		<div class="content shop floating">
			<!-- 컨텐츠 시작 -->
			<div class="grid none frt_info_wrap">
				<div class="photo gallery_view">
					<div class="swiper-container gallery-top">
						<?
						$badgeObj->item = $it;
						$badgeObj->makeHtml();
						?>
						<div style="width: 100%; position: absolute; z-index: 1000;"><?= $badgeObj->badgeHtml; ?></div>
						<div style="width: 100%; position: absolute; z-index: 1000; bottom: 0;"><?= $badgeObj->leaseHtml; ?></div>
						<div class="swiper-wrapper">
							<?
							$big_img_count = 0;
							$thumbnails = array();
							for ($i = 1; $i <= 10; $i++) {
								if (!$it['it_img' . $i])
									continue;

								$img = get_it_thumbnail_path($it['it_img' . $i], 480, 480);

								if ($img) {
									// 썸네일
									$thumb = get_it_thumbnail_path($it['it_img' . $i], 120, 120);
									$thumbnails[] = $thumb;
									$big_img_count++;

									echo '<div class="swiper-slide" style="background-image:url(' . $img . ')"></div>';
								}
							}

							if ($big_img_count == 0) {
								echo '<div class="swiper-slide" style="background-image:url(' . G5_SHOP_URL . '/img/no_image.gif)"></div>';
							}
							?>
						</div>
					</div>
					<div class="swiper-container gallery-thumbs">
						<div class="swiper-wrapper">
							<?
							// 썸네일
							$total_count = count($thumbnails);
							if ($total_count > 0) {

								foreach ($thumbnails as $val) {
									echo '<div class="swiper-slide" style="background-image:url(' . $val . ')"></div>';
								}
							}
							?>
						</div>
						<!-- Add Arrows -->
						<div class="swiper-button-next swiper-button-white"></div>
						<div class="swiper-button-prev swiper-button-white"></div>
					</div>
					<script>
						var galleryThumbs = new Swiper('.gallery_view .gallery-thumbs', {
							spaceBetween: 12,
							slidesPerView: 4,
							freeMode: true,
							watchSlidesVisibility: true,
							watchSlidesProgress: true,
							slideToClickedSlide: true,
							navigation: {
								nextEl: '.swiper-button-next',
								prevEl: '.swiper-button-prev',
							},
						});
						var galleryTop = new Swiper('.gallery_view .gallery-top', {
							effect: 'fade',
							spaceBetween: 0,
							thumbs: {
								swiper: galleryThumbs,
							},

						});
					</script>
				</div>


				<div class="frt_descript">
					<div class="head">
						<div class="comm">
							<?
							echo '<div class="btn_comm">';
							if ($view_detail_items['view_wish']) {
								$sqlwish = " select count(*) as cnt, ifnull(sum(case when mb_id = '" . $member['mb_id'] . "' then 1 else 0 end),0) as wishis from lt_shop_wish where it_id ='" . $it['it_id'] . "' ";
								$rowwish = sql_fetch($sqlwish);
								echo "<button type=\"button\" class=\"pick ico " . (($rowwish['wishis'] != '0') ? 'on' : '') . "\" onclick=\"item_wish(document.fitem, '" . $it_id . "');\" it_id=\"" . $it_id . "\"><span class=\"blind\">찜</span></button>";
							}

							if ($view_detail_items['view_it_share']) {
								echo '<button type="button" class="shared" onclick="$(\'#sendsns_popup\').css(\'display\',\'\');"><span class="blind">공유</span></button>';
							}
							echo '</div>';
							?>
						</div>
						<p class="title"><?= stripslashes($it['it_name']); ?></p>
						<? if ($view_detail_items['view_it_basic']) echo '<p class="sm_title ellipsis">' . stripslashes($it['it_basic']) . '</p>'; ?>
						<div id="item-price" style="margin-top: 12px;">
							<?php

							// $view_detail_items['view_it_sale_bprice'] = false;
							if ($view_detail_items['view_it_sale_bprice'] && $it['it_discount_price'] != '' && $it['it_discount_price'] != '0') {
								$rt_price = $it['it_rental_price'];
								$rt_sale_price = $it['it_discount_price'] / $it['it_item_rental_month'];
								$discount_ratio = $rt_sale_price / ($rt_price + $rt_sale_price) * 100;
								echo '<span style="font-size: 46px; float: left; display: inline-block; padding: 8px 8px 0 0; color: #1fc0ba;"><span class="discount-ratio">' . number_format($discount_ratio) . '<span style="font-size: 32px; font-weight: bold;">%</span></span></span><span style="line-height: 30px;"><del class="price" style="color: #888888">' . number_format($rt_price + $rt_sale_price) . '원</del><br />';
							}

							if ($view_detail_items['view_it_price']) {
								echo '<span><span class="price num" style="font-size: 34px; font-weight: bold;">' . display_price($it['it_rental_price']) . '원</span>';
							}
							?>
							</span>
						</div>
					</div>
					<div class="order_list">
						<ul style="line-height: 28px;">

							<input type="hidden" id="it_price" value="<?= $it['it_rental_price']; ?>">
							<input type="hidden" name="it_item_rental_month" value="<?= $it['it_item_rental_month']; ?>">
							<li>
								<div class="info_service">
									<ul class="info_list">
										<li class="cell"><span class="icon-pack icon-laundry"></span>세탁 무제한</li>
										<li class="cell"><span class="icon-pack icon-delevery"></span>무료배송</li>
									</ul>
								</div>
							</li>

							<? if (count($subits) > 1) : ?>
								<li>
									<select id="select-size-selector" onchange="add_sel_option_by_select(this)">
										<option value="">필수 옵션을 선택해주세요.</option>
										<?
											// 옵션 항목 조회
											$size_options = array();
											foreach ($subits as $sits) {
												$sql_io = " select * from {$g5['g5_shop_item_option_table']} where io_type = '0' and it_id = '{$sits['it_id']}' and its_no = '{$sits['its_no']}' and io_use = '1' order by io_no asc ";
												$db_io = sql_query($sql_io);
												for ($ii = 0; $io = sql_fetch_array($db_io); $ii++) {
													if (!isset($size_options[$io['io_id']])) $size_options[$io['io_id']] = $io['io_id'];
												}
											}

											if (!empty($size_options)) {
												foreach ($size_options as $so) echo "<option value='{$so}'>" . str_replace('_', ' ', $so) . "</option>";
											}
											?>
									</select>
								</li>
							<? endif; ?>


							<? for ($i = 0; $i < count($subits); $i++) {
								$its = $subits[$i];
								?>
								<li style="display: none;">
									<p class="info_title"><?= $its['its_item'] ?> <span class="price"><em class="point"><?= display_price($its['its_final_rental_price']) ?> 원</em></span></p>
									<input type="hidden" name="its_final_price[]" value="<?= $its['its_final_rental_price']; ?>" its_no="<?= $its['its_no'] ?>">

									<ul class="info_option">
										<li>
											<span id="spn_it_option_<?= $i ?>" class="item"><?= $its['its_option_subject'] ?></span>
											<strong class="result">
												<?
													$io_sql = " select * from {$g5['g5_shop_item_option_table']} where io_type = '0' and it_id = '{$its['it_id']}' and its_no = '{$its['its_no']}' and io_use = '1' order by io_no asc ";
													$io_result = sql_query($io_sql);

													$io_select = '<select id="it_option_' . $i . '" name="sel_it_option[]" class="cart_it_option btn_select" its_no="' . $its['its_no'] . '" >' . PHP_EOL;
													$io_select .= '<option value="">선택</option>' . PHP_EOL;
													for ($j = 0; $io_row = sql_fetch_array($io_result); $j++) {

														if ($io_row['io_price'] >= 0) {
															$price = '&nbsp;&nbsp;+ ' . number_format($io_row['io_price']) . '원';
														} else {
															$price = '&nbsp;&nbsp; ' . number_format($io_row['io_price']) . '원';
														}

														if ($io_row['io_stock_qty'] < 1) {
															$soldout = '&nbsp;&nbsp;[품절]';
														} else {
															$soldout = '';
														}

														$io_select .= '<option value="' . $io_row['io_id'] . ',' . $io_row['io_price'] . ',' . $io_row['io_stock_qty'] . '"io_id="' . $io_row['io_id'] . '" io_price="' . $io_row['io_price'] . '" io_stock_qty="' . $io_row['io_stock_qty'] . '">' . $io_row['io_id'] . $price . $soldout . '</option>' . PHP_EOL;
													}
													$io_select .= '</select>' . PHP_EOL;

													echo $io_select . PHP_EOL;

													?>
											</strong>
										</li>
										<?

											if ($its['its_supply_subject']) {
												$it_supply_subjects = explode(',', $its['its_supply_subject']);
												$supply_count = count($it_supply_subjects);

												for ($j = 0; $j < $supply_count; $j++) {
													echo '<li><span class="item" id="spn_it_supply_' . $j . '">' . $it_supply_subjects[$j] . '</span><strong class="result">' . PHP_EOL;

													$io_sql = " select * from {$g5['g5_shop_item_option_table']} where io_type = '1' and it_id = '{$its['it_id']}' and its_no = '{$its['its_no']}' and io_use = '1' and io_id like '{$it_supply_subjects[$j]}%' order by io_no asc ";
													$io_result = sql_query($io_sql);

													$io_select = '<select id="it_supply_' . $j . '" class="cart_it_supply btn_select" name="sel_it_supply[]" its_no="' . $its['its_no'] . '">' . PHP_EOL;
													$io_select .= '<option value="">선택</option>' . PHP_EOL;
													for ($k = 0; $io_row = sql_fetch_array($io_result); $k++) {

														if ($io_row['io_price'] >= 0) {
															$price = '&nbsp;&nbsp;+ ' . number_format($io_row['io_price']) . '원';
														} else {
															$price = '&nbsp;&nbsp; ' . number_format($io_row['io_price']) . '원';
														}

														//$io_id = str_replace($it_supply_subjects[$j], "", $io_row['io_id']);
														$opt_id = explode(chr(30), $io_row['io_id']);

														$io_select .= '<option value="' . $io_row['io_id'] . ',' . $io_row['io_price'] . ',' . $io_row['io_stock_qty'] . '" io_price="' . $io_row['io_price'] . '" io_stock_qty="' . $io_row['io_stock_qty'] . '">' . $opt_id[1] . $price . '</option>' . PHP_EOL;
													}
													$io_select .= '</select>' . PHP_EOL;

													echo $io_select . '</strong></li>' . PHP_EOL;
												}
											}
											?>
									</ul>
								</li>
							<? } ?>
						</ul>
					</div>

					<? if ($is_orderable) { ?>
						<!-- 총 구매액 -->
						<div class="order_list">
							<?
								if (!$it['it_buy_min_qty'])
									$it['it_buy_min_qty'] = 1;
								?>
							<div id="sit_sel_option" style="display: none;">
								<ul id="sit_opt_added">
								</ul>
							</div>
							<?  ?>
						</div>

						<div id="selected-option"></div>

						<div class="cont_foot">
							<div class="order_total count4">
								<p class="txt">월 이용료<span class="point" id="sit_tot_count"></span></p>
								<strong id="sit_tot_price" class="price"><em>0 원</em></strong>
							</div>
							<div class="order_total">
								<p class="txt">총 개월수</p>
								<strong class="price"><em><?= $it['it_item_rental_month'] ?> 개월</em></strong>
							</div>
							<div class="order_total">
								<p class="txt">총 완납 금액</p>
								<strong id="sit_tot_price2" month="<?= $it['it_item_rental_month'] ?>" class="price"><?= display_price(((int) $it['it_rental_price'] * (int) $it['it_item_rental_month']), $it['it_tel_inq']) ?><em> 원</em></strong>
							</div>
						</div>
					<? } ?>

					<div class="btn_group two">
						<? if ($is_orderable) { ?>
							<!--
						<button type="button" class="btn big white" onclick="document.pressed=this.value;fitem_submit();" value="장바구니"><span>장바구니 담기</span></button>
						-->
							<button type="button" class="btn big green" onclick="document.pressed=this.value;fitem_submit();" value="계약 하기" style="width: 100%;"><span>계약 하기</span></button>
						<? } ?>

						<? if (!$is_orderable && $it['it_soldout'] && $it['it_stock_sms'] && false) { ?>
							<a href="javascript:popup_stocksms('<?= $it['it_id']; ?>');" id="sit_btn_alm"><i class="fa fa-bell-o" aria-hidden="true"></i> 재입고알림</a>
						<? } ?>

						<? if ($naverpay_button_js) { ?>
							<div class="itemform-naverpay"><?= $naverpay_request_js . $naverpay_button_js; ?></div>
						<? } ?>
					</div>
				</div>
			</div>

			<!-- 상품정보
				<div class="grid">
					<div class="title_bar">
						<h3 class="g_title_01">상품 정보</h3>
					</div>
					<div class="order_list">
						<ul>
						<?
						if ($it['it_id'] && $it['it_info_value'] != '') {
							$article = json_decode($it['it_info_value'], true);
							foreach ($article as $key => $value) {
								$list = '<li>';
								$list .= '    <span class="item">' . $value['name'] . '</span>';
								$list .= '    	<strong class="result">' . $value['value'] . '</strong>';
								$list .= '</li>';

								echo $list;
							}
						}
						?>
						</ul>
					</div>
				</div>
			-->
			<? if ($item_relation_count) { ?>
				<!-- 관련제품 -->
				<div class="grid">
					<div class="title_bar">
						<h3 class="g_title_01">관련제품</h3>
					</div>
					<div class="item_row_list swiper-container related_list">
						<ul class="swiper-wrapper">
							<?
								$sql = " select b.ca_id, b.it_id, b.it_name, b.it_basic, b.it_price, b.it_rental_price, b.it_item_type
							from {$g5['g5_shop_item_relation_table']} a
							left join {$g5['g5_shop_item_table']} b on (a.it_id2=b.it_id)
							where a.it_id = '$it_id'
							order by ir_no asc ";
								$result = sql_query($sql);
								for ($g = 0; $row = sql_fetch_array($result); $g++) {
									$it_relation_img = get_it_image($row['it_id'], 228, 228);
									if (is_soldout($row['it_id'])) {
										echo "<li class=\"soldout\">\n";
									} else {
										echo "<li class=\"swiper-slide\">\n";
									}
									echo "<a href=\"" . G5_SHOP_URL . "/item.php?it_id={$row['it_id']}\">\n";
									?>
								<?
										$badgeObj->item = $it;
										$badgeObj->innerHtml = $it_relation_img;
										$badgeObj->makeHtml();
										?>
								<!-- <div class="photo"><?= $it_relation_img ?></div> -->
								<?= $badgeObj->photoHtml ?>
								<div class="cont">
									<strong class="title bold"><?= stripslashes($row['it_name']); ?></strong>
									<span class="text ellipsis"><?= stripslashes($row['it_basic']); ?></span>
									<span class="price"><?= ($row['it_item_type']) ? number_format($row['it_rental_price']) : number_format($row['it_price']) ?> 원</span>
								</div>
								</a>
							<?
									$sqlwish = " select count(*) as cnt, ifnull(sum(case when mb_id = '" . $member['mb_id'] . "' then 1 else 0 end),0) as wishis from lt_shop_wish where it_id ='" . $row['it_id'] . "' ";
									$rowwish = sql_fetch($sqlwish);
									echo "<div class=\"btn_comm big bottom\"><!-- 찜 눌르면 class=\"on\" 추가 --> ";
									echo "<a href=\"javascript:item_wish(document.fitem, '" . $row['it_id'] . "');\" >";
									echo "<button type=\"button\" class=\"pick ico " . (($rowwish['wishis'] != '0') ? 'on' : '') . "\" it_id=\"" . $row['it_id'] . "\"><span class=\"blind\">찜</span></button>";
									echo "</a></div>";
									echo "</li>\n";
								}
								?>
						</ul>
					</div>
					<script>
						var swiper = new Swiper('.related_list.swiper-container', {
							slidesPerView: 4,
							loop: true,
							spaceBetween: 30
						});
					</script>
				</div>
			<? } ?>

			<div class="grid none tab_cont_wrap">
				<div class="tab">
					<ul class="type3 onoff tab_btn">
						<li class="on"><a href="#"><span>제품설명</span></a></li>
						<li class=""><a href="#"><span>상세정보</span></a></li>
						<li class=""><a href="#"><span>REVIEW(<?= number_format($item_use_count); ?>)</span></a></li>
						<li class=""><a href="#"><span>제품문의</span></a></li>
					</ul>
				</div>
				<div class="tab_cont">
					<!-- tab1 -->
					<div class="tab_inner">
						<div class="grid">
							<h3 class="blind">제품설명</h3>
							<div class="detail_wrap">
								<?= conv_content($it['it_explan'], 1); ?>
							</div>
						</div>
					</div>
					<!-- tab2-->
					<div class="tab_inner">
						<div class="grid">
							<div class="title_bar none padNone bold">
								제품 상세 정보
							</div>
							<table class="TBasic2">
								<colgroup>
									<col width="17%" />
									<col width="83%" />
								</colgroup>
								<?
								if ($it['it_id'] && $it['it_info_value'] != '' && $it['it_info_value'] != '[]') {
									$article = json_decode($it['it_info_value'], true);
									foreach ($article as $key => $value) {
										?>
										<tr>
											<th class="tleft"><?= $value['name'] ?></th>
											<td><?= $value['value'] ?></td>
										</tr>
									<? } ?>
								<? } ?>
							</table>
						</div>

						<div class="grid">
							<div class="title_bar none padNone bold">
								배송 및 교환/반품 안내
							</div>
							<table class="TBasic2">
								<colgroup>
									<col width="17%" />
									<col width="83%" />
								</colgroup>
								<tr>
									<th class="tleft">배송방법</th>
									<td><?= $it['it_send_type'] ?></td>
								</tr>
								<tr>
									<th class="tleft">배송기간</th>
									<td><?= $it['it_send_term_start'] ?> ~ <?= $it['it_send_term_end'] ?>일 정도 소요됩니다.</td>
								</tr>
								<tr>
									<th class="tleft">기본 배송비</th>
									<td><?= number_format($it['it_sc_minimum']) ?> 원 미만일때 배송비 <?= number_format($it['it_sc_price']) ?> 원 부과됩니다.</td>
								</tr>
								<tr>
									<th class="tleft">반품 택배사</th>
									<td><?= $it['it_delivery_company'] ?></td>
								</tr>
								<tr>
									<th class="tleft">반품 비용</th>
									<!-- <td>교환 : <?= number_format($it['it_return_costs']) ?> 원 | 반품 : <?= number_format($it['it_roundtrip_costs']) ?> 원</td> -->
									<td>반품 : <?= number_format($it['it_return_costs']) ?> 원</td>
								</tr>
								<tr>
									<th class="tleft">반품 주소지</th>
									<td><?= $it['it_return_zip'] . ' ' . $it['it_return_address1'] . ' ' . $it['it_return_address2'] ?></td>
								</tr>
							</table>
						</div>
					</div>
					<!-- tab3-->
					<div class="tab_inner" id="itemuse">
						<? include_once(G5_SHOP_PATH . '/itemuse.php'); ?>
					</div>

					<!-- tab4-->
					<div class="tab_inner" id="itemqa">
						<? include_once(G5_SHOP_PATH . '/itemqa.php'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>


<!-- popup -->
<section class="popup_container layer" id="sendsns_popup" style="display: none;">
	<div class="inner_layer" style="top:100px;">
		<div class="grid">
			<div class="title_bar">
				<h2 class="g_title_01">공유</h2>
			</div>
			<div class="border_box alignC none">
				<p class="sm tb_cell">공유 할 채널을 선택 해 주세요.</p>
			</div>
			<ul class="sns_link">
				<li><a href="#" class="sns naver" onclick="sendSns('naver','<?= G5_SHOP_URL . '/item.php?it_id=' . $it_id ?>','<?= urlencode(stripslashes($it['it_name'])) ?>')"><span>네이버로 공유하기</span></a></li>
				<li><a href="#" class="sns talk" onclick="sendSns('kakao','<?= G5_SHOP_URL . '/item.php?it_id=' . $it_id ?>','<?= urlencode(stripslashes($it['it_name'])) ?>')"><span>카카오로 공유하기</span></a></li>
				<li><a href="#" class="sns facebook" onclick="sendSns('facebook','<?= G5_SHOP_URL . '/item.php?it_id=' . $it_id ?>','<?= urlencode(stripslashes($it['it_name'])) ?>')"><span>페이스북으로 공유하기</span></a></li>
			</ul>
			<a href="#" class="btn_closed" onclick="$('#sendsns_popup').css('display','none');"><span class="blind">닫기</span></a>
		</div>
	</div>
</section>
<!-- //popup -->

<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
<div id="popup"></div>

<script>
	// 상품보관
	function item_wish(f, it_id) {
		if ($(".pick[it_id='" + it_id + "']").attr("class").indexOf("on") < 0) {
			$.post(
				"<?= G5_SHOP_URL; ?>/wishupdate2.php", {
					it_id: it_id
				},
				function(data) {
					var responseJSON = JSON.parse(data);
					if (responseJSON.result == "S") {

						if (confirm("관심상품에 저장되었습니다. 보러가시겠습니까?")) location.href = '<?= G5_SHOP_URL; ?>/wishlist.php';

						$(".pick[it_id='" + it_id + "']").addClass("on");
					} else {
						alert(responseJSON.alert);
						return false;
					}
				}
			);
		} else {
			$.post(
				"<?= G5_SHOP_URL; ?>/wishupdate2.php", {
					it_id: it_id,
					w: 'r'
				},
				function(data) {
					var responseJSON = JSON.parse(data);
					if (responseJSON.result == "S") {
						$(".pick[it_id='" + it_id + "']").removeClass("on");
					} else {
						alert(responseJSON.alert);
						return false;
					}
				}
			);
		}
	}

	// 추천메일
	function popup_item_recommend(it_id) {
		if (!g5_is_member) {
			if (confirm("회원만 추천하실 수 있습니다."))
				document.location.href = "<?= G5_BBS_URL; ?>/login.php?url=<?= urlencode(G5_SHOP_URL . "/item.php?it_id=$it_id"); ?>";
		} else {
			url = "./itemrecommend.php?it_id=" + it_id;
			opt = "scrollbars=yes,width=616,height=420,top=10,left=10";
			popup_window(url, "itemrecommend", opt);
		}
	}

	function fsubmit_check(f) {
		// 판매가격이 0 보다 작다면
		if (document.getElementById("it_price").value < 0) {
			alert("전화로 문의해 주시면 감사하겠습니다.");
			return false;
		}

		if ($(".sit_opt_list").size() < 1) {
			alert("상품의 선택옵션을 선택해 주십시오.");
			return false;
		}

		var val, io_type, result = true;
		var sum_qty = 0;
		var min_qty = parseInt(<?= $it['it_buy_min_qty']; ?>);
		var max_qty = parseInt(<?= $it['it_buy_max_qty']; ?>);
		var $el_type = $("input[name^=io_type]");

		$("input[name^=ct_qty]").each(function(index) {
			val = $(this).val();

			if (val.length < 1) {
				alert("수량을 입력해 주십시오.");
				result = false;
				return false;
			}

			if (val.replace(/[0-9]/g, "").length > 0) {
				alert("수량은 숫자로 입력해 주십시오.");
				result = false;
				return false;
			}

			if (parseInt(val.replace(/[^0-9]/g, "")) < 1) {
				alert("수량은 1이상 입력해 주십시오.");
				result = false;
				return false;
			}

			io_type = $el_type.eq(index).val();
			if (io_type == "0")
				sum_qty += parseInt(val);
		});

		if (!result) {
			return false;
		}

		if (min_qty > 0 && sum_qty < min_qty) {
			alert("선택옵션 개수 총합 " + number_format(String(min_qty)) + "개 이상 주문해 주십시오.");
			return false;
		}

		if (max_qty > 0 && sum_qty > max_qty) {
			alert("선택옵션 개수 총합 " + number_format(String(max_qty)) + "개 이하로 주문해 주십시오.");
			return false;
		}

		return true;
	}

	// 계약 하기, 장바구니 폼 전송
	function fitem_submit(f) {
		//f.action = "<?= $action_url; ?>";
		//f.target = "";

		if (document.pressed == "장바구니") {
			$("input[name='sw_direct']").val("0");
		} else { // 계약 하기
			$("input[name='sw_direct']").val("1");
		}

		// 판매가격이 0 보다 작다면
		if (document.getElementById("it_price").value < 0) {
			alert("전화로 문의해 주시면 감사하겠습니다.");
			return false;
		}

		if ($(".sit_opt_list").size() < 1) {
			alert("상품의 선택옵션을 선택해 주십시오.");
			return false;
		}

		if ($("select[name='sel_it_option[]']").size() > 1) {
			//세트상품의 경우 모든 상품을 선택해야함.
			if ($("select[name='sel_it_option[]']").size() != $(".sit_opt_list").size()) {
				alert("상품의 선택옵션을 선택해 주십시오.");
				return false;
			}
		}

		var val, io_type, result = true;
		var sum_qty = 0;
		var min_qty = parseInt(<?= $it['it_buy_min_qty']; ?>);
		var max_qty = parseInt(<?= $it['it_buy_max_qty']; ?>);
		var $el_type = $("input[name^=io_type]");

		$("input[name^=ct_qty]").each(function(index) {
			val = $(this).val();

			if (val.length < 1) {
				alert("수량을 입력해 주십시오.");
				result = false;
				return false;
			}

			if (val.replace(/[0-9]/g, "").length > 0) {
				alert("수량은 숫자로 입력해 주십시오.");
				result = false;
				return false;
			}

			if (parseInt(val.replace(/[^0-9]/g, "")) < 1) {
				alert("수량은 1이상 입력해 주십시오.");
				result = false;
				return false;
			}

			io_type = $el_type.eq(index).val();
			if (io_type == "0")
				sum_qty += parseInt(val);
		});

		if (!result) {
			return false;
		}

		if (min_qty > 0 && sum_qty < min_qty) {
			alert("선택옵션 개수 총합 " + number_format(String(min_qty)) + "개 이상 주문해 주십시오.");
			return false;
		}

		if (max_qty > 0 && sum_qty > max_qty) {
			alert("선택옵션 개수 총합 " + number_format(String(max_qty)) + "개 이하로 주문해 주십시오.");
			return false;
		}

		var form = $("form[name='fitem']")[0];
		var formData = new FormData(form);

		$.ajax({
			url: '<?= $action_url; ?>',
			processData: false,
			contentType: false,
			data: formData,
			type: 'POST',
			success: function(result) {
				if ($("input[name='sw_direct']").val() == "1") {
					location.href = '<?= G5_SHOP_URL ?>/orderform.php?sw_direct=1&od_type=R';
				} else {
					$("#popup").html(result);
				}
			}
		});
		return false;
	}

	function add_sel_option_mobile_chk(its_no) {
		var add_exec = true;

		var $sel_it_option = $("select[name='sel_it_option[]'][its_no='" + its_no + "']");
		if ($sel_it_option.val() == "") add_exec = false;

		var $sel_it_supply = $("select[name='sel_it_supply[]'][its_no='" + its_no + "']");
		if ($sel_it_supply.size() > 0) {
			$sel_it_supply.each(function() {
				if ($(this).val() == "") add_exec = false;
			});
		}

		//add_option
		if (add_exec) {
			var id = "";
			var value, info, sel_opt, item, price, stock, run_error = false;
			var option = sep = "";

			var it_price = parseInt($("input[name='its_final_price[]'][its_no='" + its_no + "']").val());
			//var it_price = parseInt($("input#it_price").val());
			var item = $sel_it_option.closest("li").find("span[id^=spn_it_option]").text();

			value = $sel_it_option.val();
			info = value.split(",");
			sel_opt = info[0];
			id = sel_opt;
			option += sep + item + ":" + sel_opt;

			price = info[1];
			stock = info[2];

			supply_ids = '';
			supply_seq = '';

			$sel_it_supply.each(function() {
				//if($(this).val() == "") add_exec = false;
				value = $(this).val();
				info = value.split(",");
				sel_opt = info[0].split(chr(30))[1];

				//id += chr(30)+sel_opt;
				sep = " , ";
				option += sep + sel_opt;
				price = parseInt(price) + parseInt(info[1]);

				supply_ids += supply_seq + value;
				supply_seq = ",";
			});

			//alert(option);

			//if(same_option_check(option))
			//	return;

			add_sel_option_mobile(0, id, option, price, stock, it_price, its_no, supply_ids);
		}
	}

	function add_sel_option_mobile(type, id, option, price, stock, it_price, its_no, supply_ids) {
		var item_code = $("input[name='it_id[]']").val();
		var opt = "";
		var li_class = "sit_opt_list";

		var opt_prc;
		if (parseInt(price) >= 0)
			opt_prc = number_format(it_price) + "원 (+" + number_format(String(price)) + "원)";
		else
			opt_prc = number_format(it_price) + "원 (" + number_format(String(price)) + "원)";

		opt += "<li class=\"" + li_class + "\" its_no=\"" + its_no + "\">";
		opt += "<input type=\"hidden\" name=\"io_type[" + item_code + "][]\" value=\"" + type + "\">";
		opt += "<input type=\"hidden\" name=\"io_id[" + item_code + "][]\" value=\"" + id + "\">";
		opt += "<input type=\"hidden\" name=\"io_value[" + item_code + "][]\" value=\"" + option + "\">";
		opt += "<input type=\"hidden\" name=\"io_supply[" + item_code + "][]\" value=\"" + supply_ids + "\">";
		opt += "<input type=\"hidden\" name=\"its_no[" + item_code + "][]\" value=\"" + its_no + "\">";

		opt += "<input type=\"hidden\" class=\"it_price\" value=\"" + it_price + "\">";
		opt += "<input type=\"hidden\" class=\"io_price\" value=\"" + price + "\">";
		opt += "<input type=\"hidden\" class=\"io_stock\" value=\"" + stock + "\">";
		opt += "<span class=\"item\" style=\"width: auto;\">" + option + " " + opt_prc + "</span>";

		opt += "<strong class=\"result\"><div class=\"count_control\" style=\"display: none;\">";
		opt += "<em class=\"num\"><input type=\"text\" name=\"ct_qty[" + item_code + "][]\" value=\"1\" class=\"frm_input\" size=\"5\"></em>";

		opt += "<button type=\"button\" class=\"count_minus\"><span class=\"blind\">감소</span></button>";
		opt += "<button type=\"button\" class=\"count_plus\"><span class=\"blind\">증가</span></button>";
		opt += "</div>";
		opt += "<button type=\"button\" class=\"count_del\"><span class=\"blind\">삭제</span></button>";

		opt += "</strong></li>";

		if ($(".sit_opt_list[its_no='" + its_no + "']").size() >= 1) {
			$(".sit_opt_list[its_no='" + its_no + "']").remove();
		}

		if ($("#sit_sel_option > ul").size() < 1) {
			$("#sit_sel_option").html("<ul id=\"sit_opt_added\"></ul>");
			$("#sit_sel_option > ul").html(opt);
		} else {
			if (type) {
				if ($("#sit_sel_option .sit_spl_list").size() > 0) {
					$("#sit_sel_option .sit_spl_list:last").after(opt);
				} else {
					if ($("#sit_sel_option .sit_opt_list").size() > 0) {
						$("#sit_sel_option .sit_opt_list:last").after(opt);
					} else {
						$("#sit_sel_option > ul").html(opt);
					}
				}
			} else {
				if ($("#sit_sel_option .sit_opt_list").size() > 0) {
					$("#sit_sel_option .sit_opt_list:last").after(opt);
				} else {
					if ($("#sit_sel_option .sit_spl_list").size() > 0) {
						$("#sit_sel_option .sit_spl_list:first").before(opt);
					} else {
						$("#sit_sel_option > ul").html(opt);
					}
				}
			}
		}

		price_calculate();
	}


	$(function() {


		$(document).on("change", "select.cart_it_option", function() {
			var val = $(this).val();

			var info = val.split(",");
			// 재고체크
			if (parseInt(info[2]) < 1) {
				alert("선택하신 선택옵션상품은 재고가 부족하여 구매할 수 없습니다.");
				$(this).val("");
				return false;
			}
			var its_no = $(this).attr("its_no");

			var $sel_it_supply = $("select[name='sel_it_supply[]'][its_no='" + its_no + "']");
			if ($sel_it_supply.size() > 0) {
				$sel_it_supply.each(function() {
					$(this).val("");
				});
			}

			add_sel_option_mobile_chk(its_no);
		});

		$(document).on("change", "select.cart_it_supply", function() {
			var its_no = $(this).attr("its_no");
			add_sel_option_mobile_chk(its_no);
		});

	});

	Kakao.init('<?= $config['cf_kakao_js_apikey'] ?>');

	function sendSns(sns, url, txt) {
		var o;
		var _url = encodeURIComponent(url);
		var _txt = encodeURIComponent(txt);
		var _br = encodeURIComponent('\r\n');

		switch (sns) {
			case 'facebook':
				o = {
					method: 'popup',
					url: 'http://www.facebook.com/sharer/sharer.php?u=' + _url
				};
				break;

			case 'twitter':
				o = {
					method: 'popup',
					url: 'http://twitter.com/intent/tweet?text=' + _txt + '&url=' + _url
				};
				break;

			case 'me2day':
				o = {
					method: 'popup',
					url: 'http://me2day.net/posts/new?new_post[body]=' + _txt + _br + _url + '&new_post[tags]=epiloum'
				};
				break;
			case 'naver':
				o = {
					method: 'popup',
					url: 'https://share.naver.com/web/shareView.nhn?url=' + _url + '&title=' + _txt
				};
				break;
			case 'kakao':
				Kakao.Link.sendDefault({
					objectType: 'feed',
					content: {
						title: '게시글 공유하기',
						description: txt,
						imageUrl: '<?= ($total_count) ? $thumbnails[0] : ""; ?>',
						link: {
							mobileWebUrl: url,
							webUrl: url
						}
					},
					social: {
						likeCount: 0,
						commentCount: 0,
						sharedCount: 0
					},
					buttons: [{
							title: '웹으로 보기',
							link: {
								mobileWebUrl: url,
								webUrl: url
							}
						},
						{
							title: '앱으로 보기',
							link: {
								mobileWebUrl: url,
								webUrl: url
							}
						}
					]
				});
				return false;
				break;
			case 'kakaotalk':
				o = {
					method: 'web2app',
					param: 'sendurl?msg=' + _txt + '&url=' + _url + '&type=link&apiver=2.0.1&appver=2.0&appid=&appname=' + encodeURIComponent(''),
					a_store: 'itms-apps://itunes.apple.com/app/id362057947?mt=8',
					g_store: 'market://details?id=com.kakao.talk',
					a_proto: 'kakaolink://',
					g_proto: 'scheme=kakaolink;package=com.kakao.talk'
				};
				break;

			case 'kakaostory':
				o = {
					method: 'web2app',
					param: 'posting?post=' + _txt + _br + _url + '&apiver=1.0&appver=2.0&appid=&appname=' + encodeURIComponent(''),
					a_store: 'itms-apps://itunes.apple.com/app/id486244601?mt=8',
					g_store: 'market://details?id=com.kakao.story',
					a_proto: 'storylink://',
					g_proto: 'scheme=kakaolink;package=com.kakao.story'
				};
				break;

			case 'band':
				o = {
					method: 'web2app',
					param: 'create/post?text=' + _txt + _br + _url,
					a_store: 'itms-apps://itunes.apple.com/app/id542613198?mt=8',
					g_store: 'market://details?id=com.nhn.android.band',
					a_proto: 'bandapp://',
					g_proto: 'scheme=bandapp;package=com.nhn.android.band'
				};
				break;


			default:
				alert('지원하지 않는 SNS입니다.');
				return false;
		}

		switch (o.method) {
			case 'popup':
				window.open(o.url);
				break;

			case 'web2app':
				if (navigator.userAgent.match(/android/i)) {
					// Android
					setTimeout(function() {
						location.href = 'intent://' + o.param + '#Intent;' + o.g_proto + ';end'
					}, 100);
				} else if (navigator.userAgent.match(/(iphone)|(ipod)|(ipad)/i)) {
					// Apple
					setTimeout(function() {
						location.href = o.a_store;
					}, 200);
					setTimeout(function() {
						location.href = o.a_proto + o.param
					}, 100);
				} else {
					alert('이 기능은 모바일에서만 사용할 수 있습니다.');
				}
				break;
		}
	}

	function add_sel_option_by_select(elem) {
		const optionSelect = $("select[name='sel_it_option[]']");
		const size = $(elem).val();

		let its_nos = [];
		let its_price = 0;
		let its_id = "";

		$(".sit_opt_list").remove();
		$(optionSelect).each(function(idx, select) {
			const its_no = $(select).attr("its_no");
			const is_pillow = $(select).data("pillow");

			$(select).children().each(function(cidx, option) {
				$(option).removeAttr("selected");
				if (option.value && option.value.indexOf(size) >= 0) {
					$(option).attr("selected", "selected");

					its_nos.push(its_no);
					its_id = $(option).attr("io_id");
					its_price = its_price + $(option).attr("io_price") * 1;
				}
			});

			if (is_pillow) {
				$(select).children().each(function(cidx, option) {
					if (option.value) $(option).attr("selected", "selected");
				});
			}

			add_sel_option_mobile_chk(its_no);

			if (is_pillow && (size.indexOf('Q') >= 0 || size.indexOf('K') >= 0)) {
				const btnCountUp = $("li.sit_opt_list[its_no='" + its_no + "']").find("button.count_plus");
				btnCountUp.click();
			}
		});

		let selectedOption = $("div#selected-option");
		selectedOption.html("");
		selectedOption.append("<div>" + its_id + " + " + number_format(its_price) + " 원<button type='button' class='btn-sit-opt-remove' data-its_no=" + its_nos.join(",") + " onclick='removeSelectedOptions(this)'><span>×</span></button></div>");
		selectedOption.show();
	}

	function add_single_option() {
		const optionSelect = $("select[name='sel_it_option[]']");

		if (optionSelect.length === 1 && $(optionSelect).children().length <= 2) {
			$(optionSelect).each(function(idx, select) {
				const its_no = $(select).attr("its_no");

				$(select).children().each(function(cidx, option) {
					if (option.value) {
						$(option).attr("selected", "selected");
					}
				});

				$("div#list-item-options").hide();
				add_sel_option_mobile_chk(its_no);

			});
		}
	}

	add_single_option();

	function removeSelectedOptions(elem) {
		const its_nos = $(elem).data("its_no").toString().split(',');
		for (idx in its_nos) {
			$("li.sit_opt_list[its_no='" + its_nos[idx] + "']").remove();
		}

		$(elem).parent().remove();

		price_calculate();
	}
</script>


<? /* 2017 리뉴얼한 테마 적용 스크립트입니다. 기존 스크립트를 오버라이드 합니다. */ ?>
<script src="<?= G5_JS_URL; ?>/shop.override.js"></script>