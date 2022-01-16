<?
include_once('./_common.php');

if ($is_guest)
	exit;

$price = (int) preg_replace('#[^0-9]#', '', $_POST['price']);
$before_price = (int) preg_replace('#[^0-9]#', '', $_POST['before_price']);

if ($price <= 0)
	die('상품금액이 0원이므로 쿠폰을 사용할 수 없습니다.');

// 쿠폰정보
$sql = " select  a.*, b.cm_use_price_type
from lt_shop_coupon as a, lt_shop_coupon_mng as b
where a.cm_no = b.cm_no
and a.mb_id IN ( '{$member['mb_id']}', '전체회원' )
and a.cp_method in ('0', '2')
and a.cp_start <= '" . G5_TIME_YMD . "'
and a.cp_end >= '" . G5_TIME_YMD . "'
and a.cp_minimum <= '$price' ";
$result = sql_query($sql);
$count = sql_num_rows($result);
?>

<!-- 쿠폰 선택 시작 { -->
<? if (G5_IS_MOBILE) { ?>
	<section class="popup_container" id="od_coupon_frm">
		<div class="grid">
		<? } else { ?>
			<div class="popup_container layer" id="od_coupon_frm">
				<div class="inner_layer" style="top:10%">
					<div class="content ">
					<? } ?>

					<div class="title_bar">
						<h2 class="g_title_01">보유 쿠폰 목록</h2>
					</div>
					<?
					$j = 0;
					for ($i = 0; $row = sql_fetch_array($result); $i++) {
						// 사용한 쿠폰인지 체크
						if (is_used_coupon($member['mb_id'], $row['cp_id'])) {
							continue;
						}
						$dc = 0;
						if ($row['cp_type']) {
							if ($row['cm_use_price_type'] == 1) {
								$dc = floor(($price * ($row['cp_price'] / 100)) / $row['cp_trunc']) * $row['cp_trunc'];
							} else {
								$dc = floor(($before_price * ($row['cp_price'] / 100)) / $row['cp_trunc']) * $row['cp_trunc'];
							}
							$discount_price = $row['cp_price'] . '%';
						} else {
							$dc = $row['cp_price'];
							$discount_price = number_format($row['cp_price']) . '원';
						}

						if ($row['cp_maximum'] && $dc > $row['cp_maximum']) {
							$dc = $row['cp_maximum'];
							$discount_price = number_format($row['cp_maximum']) . '원';
						}
						?>
						<? if ($j == 0) { ?>
							<div class="coupon_list2">
								<ul>
								<? } ?>
								<li>
									<span class="chk check" style="position:absolute; width:100%; height:100%; z-index:9999;">
										<input type="radio" name="chk_cp" value="<?= $i; ?>" id="chk_<?= $i; ?>" style="position:absolute; z-index:-999999; ">
										<label for="chk_<?= $i; ?>" style="position:absolute; width:100%; height:100%; z-index:9999;"></label>
									</span>
									<div class="couponBox">
										<div class="couponPay"><?= $discount_price; ?></div>
										<div class="couponInfo">
											<div class="couponInfoBox">
												<? if ($row['cm_item_type'] == "1" || $row['cm_category_type'] == "1") { ?>
													<button type="button" class="btn floatR arrow_r" id="btn_coupon_item1" cm_no="<?= $row['cm_no'] ?>" cm_type="1" style="font-size:12px; padding-right:10px;"><span>적용 제품보기</span></button>
												<? }

													if ($row['cm_item_type'] == "2" || $row['cm_category_type'] == "2") { ?>
													<button type="button" class="btn floatR arrow_r" id="btn_coupon_item2" cm_no="<?= $row['cm_no'] ?>" cm_type="2" style="font-size:12px; padding-right:10px;"><span>적용 제외 제품보기</span></button>
												<? } ?>
												<? if ($row['cp_end'] != 0) { ?>
													<span class="category">D-<?= ceil((strtotime($row['cp_end']) - strtotime(G5_TIME_YMDHIS)) / (60 * 60 * 24)) ?></span>
												<? } ?>
												<input type="hidden" name="o_cp_id[]" value="<?= $row['cp_id']; ?>">
												<input type="hidden" name="o_cp_prc[]" value="<?= $dc; ?>">
												<input type="hidden" name="o_cp_subj[]" value="<?= $row['cp_subject']; ?>">
												<!-- button type="button" class="od_cp_apply btn_frmline">적용</button -->
												<p class="subject"><?= get_text($row['cp_subject']); ?></p>
												<ul class="disc">
													<li>
														<? if ($row['cp_end'] != '0000-00-00') { ?>
															<span class="date"><?= substr($row['cp_start'], 0, 10); ?> ~ <?= substr($row['cp_end'], 0, 10); ?></span>
														<? } else { ?>
															<span class="date">기간 제한 없음</span>
														<? } ?>
													</li>
													<?
														if ($row['cm_summary']) {
															echo '<li>' . $row['cm_summary'] . '</li>';
														}
														if ($row['cp_minimum']) {
															echo '<li>결제 시 ' . number_format($row['cp_minimum']) . '원 이상 구매 시 사용</li>';
														}
														if ($row['cp_maximum']) {
															echo '<li>최대 할인 금액 ' . number_format($row['cp_maximum']) . ' 원</li>';
														}
														?>
												</ul>

											</div>
											<div class="clear"></div>
										</div>
										<div class="clear"></div>
									</div>
									<div class="clear"></div>
								</li>
							<?
								$j++;
							}
							?>
							<? if ($j > 0) { ?>
								</ul>
								<div class="clear"></div>
							</div>

							<div class="btn_group">
								<button type="button" class="btn big border od_cp_apply" id="od_cp_apply_btn"><span>선택완료</span></button>
							</div>
						<? } else { ?>
							<div class="guide_box">
								<p>보유하신<br>쿠폰이 없습니다.</p>
							</div>

							<div class="btn_group">
								<button type="button" class="btn big border" id="od_coupon_close"><span>닫기</span></button>
							</div>

						<? } ?>
						<? if (G5_IS_MOBILE) { ?>
							<a href="#" class="btn_closed btn_close" id="od_coupon_close"><span class="blind">닫기</span></a>
					</div>
	</section>
<? } else { ?>
	</div>
	<a href="#" class="btn_closed btn_close" id="od_coupon_close"><span class="blind">닫기</span></a>
	</div>
	</div>
<? } ?>

<!-- } 쿠폰 선택 끝 -->