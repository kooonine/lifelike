<?
if (!defined("_GNUBOARD_")) exit;		// 개별 페이지 접근 불가
add_javascript(G5_POSTCODE_JS, 0);		//다음 주소 js

if (!defined("_ORDERINQUIRY_")) exit;	// 개별 페이지 접근 불가
?>
<?
$sql = " select *, (od_cart_coupon + od_coupon + od_send_coupon) as couponprice from {$g5['g5_shop_order_table']} where mb_id = '{$member['mb_id']}'";
if (isset($is_claim) && $is_claim != "") $sql .= " and od_status_claim in ('주문취소','교환','반품','철회','해지') ";
if (isset($is_care) && $is_care != "") $sql .= " and ((od_type = 'R' and od_status = '리스중') or od_type in ('L','K','S')) ";
if (isset($od_status_claim) && $od_status_claim != "") $sql .= " and od_status_claim = '{$od_status_claim}' ";
if (isset($od_type) && $od_type != "") $sql .= " and od_type = '{$od_type}' ";
if (isset($od_stime) && $od_stime != "") $sql .= " and od_time >= '{$od_stime}' ";
if (isset($od_etime) && $od_etime != "") $sql .= " and od_time <= '{$od_etime} 23:59:59' ";
$sql .=  " order by od_time desc ";
$result = sql_query($sql);
while ($od = sql_fetch_array($result)) {
	$sqlit = "
	select
	it_name,
	ct_option,
	it_id,
	ct_keep_month,
	ct_id,
	ct_id,
	ct_price,
	io_price,
	ct_qty,
	it_sc_type,
	it_sc_price,
	it_sc_minimum,
	SUM((ct_price+io_price)*ct_qty) AS pay_price,
	SUM(ct_laundry_price) AS pay_ct_laundry_price,
	SUM(ct_laundrykeep_lprice) AS pay_ct_laundrykeep_lprice,
	SUM(ct_laundrykeep_kprice) AS pay_ct_laundrykeep_kprice,
	SUM(ct_repair_price) AS pay_ct_repair_price
	from
	{$g5['g5_shop_cart_table']}
	where
	od_id = '{$od['od_id']}'
	group by
	it_name
	order by
	io_type,
	ct_id
	";
	$resultit = sql_query($sqlit);
	$pay_price = 0;
	$pay_delivery = 0;
	$pay_delivery_all = 0;
	$pay_price_item_sc0 = 0;
	$pay_price_item_sc2 = 0;
	?>
	<div style="font-size:13px; padding:10px 0;">
		주문번호 : <a href="./orderinquiryview.php?od_id=<?= $od['od_id']; ?>&amp;uid=<?= $uid; ?>"><?= $od['od_id']; ?></a>
		<a href="<?= G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?= $od['od_id']; ?>&amp;uid=<?= $uid; ?>" class="arrow_r_gray floatR">상세보기</a>
	</div>
	<table class="TBasic3">
		<colgroup>
			<col width="13%" />
			<col width="40%" />
			<col width="14%" />
			<col width="20%" />
			<col width="13%" />
		</colgroup>
		<thead>
			<tr>
				<th>사진</th>
				<th>제품정보</th>
				<th>제품금액</th>
				<th>배송비/판매자/진행상태</th>
				<th>비고</th>
			</tr>
		</thead>
		<tbody>
			<?
				while ($ct = sql_fetch_array($resultit)) {
					//네이밍
					$ct_name = get_text($ct['it_name']) . ' ';
					//제품이미지
					$image = get_it_image($ct['it_id'], 80, 80, '', '', $ct['it_name']);

					//제품 조건별 옵션
					if ($od['od_type'] == 'O') {
						$orderType = "제품";
						$pay_price_item = $ct['pay_price'];
					} else if ($od['od_type'] == 'R') {
						$orderType = "리스";
						$pay_price_item = $ct['pay_price'];
					} else if ($od['od_type'] == 'L') {
						$orderType = "세탁";
						$pay_price_item = $ct['pay_ct_laundrykeep_lprice'];
					} else if ($od['od_type'] == 'K') {
						$orderType = "세탁/보관";
						$pay_price_item = $ct['pay_ct_laundrykeep_kprice'];
					} else if ($od['od_type'] == 'S') {
						$orderType = "수선";
						$pay_price_item = $ct['pay_ct_repair_price'];
					}

					//제품(옵션) 합계
					$pay_price = $pay_price + $pay_price_item;

					//배송비 정책구분
					if ($ct['it_sc_type'] == 2) {
						//상품개별배송정책
						$pay_delivery_minimum = $ct['it_sc_minimum'];
						$pay_delivery_price = $ct['it_sc_price'];
						if ($pay_price_item < $ct['it_sc_minimum']) {
							$pay_delivery = $pay_delivery + $ct['it_sc_price'];
							$pay_delivery_all = $pay_delivery_all + $ct['it_sc_price'];
							$pay_price_item_sc2 = $pay_price_item_sc2 + $pay_price_item;
						} else {
							$pay_delivery = 0;
							$pay_delivery_all = 0;
						}
					} else {
						//기본배송정책
						$pay_delivery_minimum = $default['de_send_cost_limit'];
						$pay_delivery_price = $default['de_send_cost_list'];
						if ($pay_price_item < $default['de_send_cost_limit']) {
							$pay_delivery = $pay_delivery + $ct['de_send_cost_list'];
							$pay_delivery_all = $pay_delivery_all + $default['de_send_cost_list'];
							$pay_price_item_sc0 = $pay_price_item_sc0 + $pay_price_item;
						} else {
							$pay_delivery = 0;
							$pay_delivery_all = 0;
						}
					}
					?>
				<tr>
					<td class="tcenter"><?= $image ?></td>
					<td class="">
						<p class="bold" style="margin-bottom:5px;"><a href="./item.php?it_id=<?= $ct['it_id']; ?>">[<?= $orderType ?>] <?= $ct_name ?></a></p>
						<? if ($od['od_type'] == 'O' || $od['od_type'] == 'R') { ?>
							<ul class="disc">
								<?
											$sqlot = " select it_name, io_id, ct_option, its_no, it_id, ct_keep_month, ct_id, ct_price, io_price, ct_qty from {$g5['g5_shop_cart_table']} where od_id = '{$od['od_id']}' AND it_id = '{$ct['it_id']}' order by io_type, ct_id";
											$resultot = sql_query($sqlot);
											while ($ot = sql_fetch_array($resultot)) {
												$sqlits = " select * from lt_shop_item_sub where its_no = '{$ot['its_no']}'";
												$resultits = sql_query($sqlits);
												$its = sql_fetch_array($resultits);
												?>
									<li>
										<?= $its['its_item'] ?> : <?= $ot['io_id'] ?>
										(수량 : <?= $ot['ct_qty'] ?>개)
										<span style="float:right;"><?= number_format(($ot['ct_price'] + $ot['io_price']) * $ot['ct_qty']) ?> 원</span>
									</li>
								<? } ?>
							</ul>
						<? } ?>

					</td>
					<td class="tcenter">
						<?= number_format($pay_price_item) ?>원
					</td>
					<td class="tcenter">
						<? if ($od['od_type'] == 'O') { ?>
							<i class="axi axi-info-outline" style="vertical-align:top;" title="<?= number_format($pay_delivery_minimum) ?>원 이상 구매시 배송비 무료 이하구매시 배송비 <?= number_format($pay_delivery_price) ?>원 이 부과됩니다."></i><br />

							<?= $pay_delivery_all == 0 ? '배송비 무료' : number_format($pay_delivery_all) . '원' ?><br />
							(<?= $ct['it_sc_type'] == 2 ? '개별배송' : '묶음배송' ?>)<br />

							<?= $od['od_status'] ?>
						<? } else { ?>
							해당없음
						<? } ?>
					</td>
					<td class="tcenter"></td>
				</tr>
			<? } ?>
			<tr>
				<td class="" colspan="20" style="border-bottom:2px solid #666;">
					<span class=""></span>
					<span class="floatR">
						제품 합계 <?= number_format($pay_price) ?>원
						(기본 <?= number_format($pay_price_item_sc0) ?>원 / 개별 <?= number_format($pay_price_item_sc2) ?>원)
						+ 배송비 합계 <?= number_format($pay_delivery_all) ?>원
						= 총 결제 금액 <span class="fred bold"><?= number_format($pay_price) ?></span>원
					</span>
				</td>
			</tr>
		</tbody>
	</table>
	<br />
<? } ?>
<!--
<div class="order_cont">
	<div class="head">
		<span class="category round_green"><?= $od_type_name; ?></span>

		<span class="order_number">주문번호 : <strong><a href="<?= G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?= $od['od_id']; ?>&amp;uid=<?= $uid; ?>"><?= $od['od_id']; ?></a></strong></span>
		<a href="<?= G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?= $od['od_id']; ?>&amp;uid=<?= $uid; ?>" class="arrow_r_gray floatR">상세보기</a>
	</div>
	<div class="body">
		<ul class="order_step <?= $order_step ?>">
			<?= $od_status_step ?>
		</ul>

		<? if ($od['od_type'] == "O") { ?>
			<div class="cont right_cont">
				<div class="photo"><a href="./item.php?it_id=<?= $ct['it_id']; ?>" ><?= $image; ?></a></div>
				<div class="info">
					<strong><a href="./item.php?it_id=<?= $ct['it_id']; ?>"><?= stripslashes($ct_name); ?></a></strong>
					<p><span class="txt">옵션</span>
						<span class="point_black"><strong class="bold"><?= get_text($ct['ct_option']); ?></strong></span>
					</p>
					<p><span class="txt">주문일</span>
						<span class="point_black"><strong class="bold"><?= substr($od['od_time'], 0, 10); ?></strong></span>
					</p>
				</div>

				<? if ($btn_act != '') { ?>
					<div class="button_item" id="orderinquiry_btn">
						<?= $btn_act; ?>
					</div>
				<? } ?>
				<div class="pay_item">
					결제 금액<span class="amount"><strong><?= display_price($od['od_receipt_price']); ?> 원</strong></span>
				</div>
			</div>
		<? } else if ($od['od_type'] == "R") { ?>
			<div class="cont right_cont">
				<div class="photo"><a href="./item.php?it_id=<?= $ct['it_id']; ?>" ><?= $image; ?></a></div>
				<div class="info">
					<strong><a href="./item.php?it_id=<?= $ct['it_id']; ?>"><?= stripslashes($ct_name); ?></a></strong>
					<p><span class="txt">옵션</span>
						<span class="point_black"><strong class="bold"><?= get_text($ct['ct_option']); ?></strong>
							/ 계약기간<strong class="bold"><?= number_format($od['rt_month']); ?>개월</strong>
						</span>
					</p>
					<p><span class="txt">계약일</span>
						<span class="point_black"><strong class="bold"><?= substr($od['od_time'], 0, 10); ?></strong></span>
					</p>
				</div>

				<? if ($btn_act != '') { ?>
					<div class="button_item" id="orderinquiry_btn">
						<?= $btn_act; ?>
					</div>
				<? } ?>
				<div class="pay_item">
					리스 금액<span class="amount"><strong><?= display_price($od['rt_rental_price']); ?> 원</strong></span>
				</div>
			</div>
		<? } else if ($od['od_type'] == "L") { ?>
			<div class="cont right_cont">
				<div class="photo"><a href="./item.php?it_id=<?= $ct['it_id']; ?>" ><?= $image; ?></a></div>
				<div class="info">
					<strong><a href="./item.php?it_id=<?= $ct['it_id']; ?>"><?= stripslashes($ct_name); ?></a></strong>
					<p><span class="txt">옵션</span>
						<span class="point_black"><strong class="bold"><?= get_text($ct['ct_option']); ?></strong></span>
					</p>
					<p><span class="txt">신청일</span>
						<span class="point_black"><strong class="bold"><?= substr($od['od_time'], 0, 10); ?></strong></span>
					</p>
				</div>

				<? if ($btn_act != '') { ?>
					<div class="button_item" id="orderinquiry_btn">
						<?= $btn_act; ?>
					</div>
				<? } ?>
				<div class="pay_item">
					결제 금액<span class="amount"><strong><?= display_price($od['od_receipt_price']); ?> 원</strong></span>
				</div>
			</div>
		<? } else if ($od['od_type'] == "K") { ?>
			<div class="cont right_cont">
				<div class="photo"><a href="./item.php?it_id=<?= $ct['it_id']; ?>" ><?= $image; ?></a></div>
				<div class="info">
					<strong><a href="./item.php?it_id=<?= $ct['it_id']; ?>"><?= stripslashes($ct_name); ?></a></strong>
					<p><span class="txt">옵션</span>
						<span class="point_black"><strong class="bold"><?= get_text($ct['ct_option']); ?></strong></span>
					</p>
					<p><span class="txt">신청일</span>
						<span class="point_black"><strong class="bold"><?= substr($od['od_time'], 0, 10); ?></strong></span>
					</p>
					<p><span class="txt">보관 기간</span>
						<span class="point_black"><strong class="bold"><?= $ct['ct_keep_month']; ?>개월</strong></span>
					</p>
				</div>

				<? if ($btn_act != '') { ?>
					<div class="button_item" id="orderinquiry_btn">
						<?= $btn_act; ?>
					</div>
				<? } ?>
				<div class="pay_item">
					결제 금액<span class="amount"><strong><?= display_price($od['od_receipt_price']); ?> 원</strong></span>
				</div>
			</div>
		<? } else if ($od['od_type'] == "S") { ?>
			<?
				$tot_price = (int) $od['od_cart_price'] + (int) $od['od_send_cost'] + (int) $od['od_send_cost2'];
				?>
			<div class="cont right_cont">
				<div class="photo"><a href="./item.php?it_id=<?= $ct['it_id']; ?>" ><?= $image; ?></a></div>
				<div class="info">
					<strong><a href="./item.php?it_id=<?= $ct['it_id']; ?>"><?= stripslashes($ct_name); ?></a></strong>
					<p><span class="txt">옵션</span>
						<span class="point_black"><strong class="bold"><?= get_text($ct['ct_option']); ?></strong></span>
					</p>
					<p><span class="txt">주문일</span>
						<span class="point_black"><strong class="bold"><?= substr($od['od_time'], 0, 10); ?></strong></span>
					</p>
				</div>

				<? if ($btn_act != '') { ?>
					<div class="button_item" id="orderinquiry_btn">
						<?= $btn_act; ?>
					</div>
				<? } ?>
				<div class="pay_item">
					결제 금액<span class="amount"><strong><?= ($od['od_cart_price']) ? display_price($tot_price) . " 원" : "후불"; ?></strong></span>
				</div>
			</div>
		<? } ?>
	</div>
</div>
-->
<?
if ($i == 0) {
	echo '<div class="none-item">주문 내역이 없습니다.</div>';
}
?>
<section class="popup_container layer" id="od_review_select" hidden it_id="" ct_id="">
	<div class="inner_layer" style="top:10%">
		<!-- lnb -->
		<div id="lnb" class="header_bar">
			<h1 class="title"><span>리뷰 작성 유형 선택</span></h1>
		</div>
		<!-- //lnb -->
		<div class="content sub">
			<div class="grid cont">
				<div class="list">
					<ul class="type1 pad">
						<li><a href="#" onclick="location.href='<?= G5_SHOP_URL ?>/itemuseform.php?mode=txt&it_id='+$('#od_review_select').attr('it_id')+'&ct_id='+$('#od_review_select').attr('ct_id');"><span class="bold point_black">일반 리뷰</span></a></li>
						<li><a href="#" onclick="location.href='<?= G5_SHOP_URL ?>/itemuseform.php?mode=img&it_id='+$('#od_review_select').attr('it_id')+'&ct_id='+$('#od_review_select').attr('ct_id');"><span class="bold point_black">프리미엄 리뷰</span></a></li>
					</ul>
				</div>
			</div>
		</div>
		<a href="#" class="btn_closed btn_close" onclick="$('#od_review_select').prop('hidden', true);"><span class="blind">닫기</span></a>
	</div>
</section>

<form method="post" action="./orderinquirychange.php" id="orderinquirychange_form" name="orderinquirychange_form">
	<input type="hidden" name="act" value="">
	<input type="hidden" name="od_id" value="">
	<input type="hidden" name="token" value="">
	<input type="hidden" name="uid" value="">
</form>
<script>
	$(function() {
		if ($.fn.orderinquiry_btn_click == null) {
			$.fn.orderinquiry_btn_click = function() {
				var mode = $(this).text();
				var od_id = $(this).attr("od_id");
				switch (mode) {
					case "주문취소":
						var uid = $(this).attr("uid");
						location.href = "<?= G5_SHOP_URL; ?>/orderinquirycancelform.php?od_id=" + od_id + "&uid=" + uid;
						break;
					case "수거지변경":
					case "배송지변경":
						$.post(
							"./orderinquiry.deliverychangeform.php", {
								od_id: od_id,
								uid: uid
							},
							function(data) {
								$("#dvOrderinquiryPopup").html(data);
							}
						);
						break;
					case "배송조회":
						var href = $(this).closest("a").attr("href");
						if (href.indexOf("<?= G5_URL ?>") >= 0) {
							$.post(href, {
									od_id: od_id
								},
								function(data) {
									$("#dvOrderinquiryPopup").html(data);
								}
							);
							return false;
						}
						break;
					case "교환요청":
						if (confirm("교환을 요청 하시겠습니까? 교환 시 사유에 따라 배송료가 발생 될 수 있습니다.")) {
							var uid = $(this).attr("uid");
							location.href = "<?= G5_SHOP_URL; ?>/orderinquirychangeform.php?od_id=" + od_id + "&act=change&uid=" + uid;
						}
						break;
					case "교환철회":
						if (confirm("교환을 철회 하시겠습니까?")) {
							var uid = $(this).attr("uid");

							$("form[name=orderinquirychange_form] input[name=act]").val("교환철회");
							$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
							$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
							$("form[name=orderinquirychange_form]").submit();
						}
						break;
					case "반품요청":
						if (confirm("반품을 요청 하시겠습니까? 반품 시 사유에 따라 배송료가 발생 될 수 있습니다.")) {
							var uid = $(this).attr("uid");
							location.href = "<?= G5_SHOP_URL; ?>/orderinquiryreturnform.php?od_id=" + od_id + "&act=return&uid=" + uid;
						}
						break;
					case "반품철회":
						if (confirm("반품을 철회 하시겠습니까?")) {
							var uid = $(this).attr("uid");

							$("form[name=orderinquirychange_form] input[name=act]").val("반품철회");
							$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
							$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
							$("form[name=orderinquirychange_form]").submit();
						}
						break;
					case "철회요청":
						if (confirm("철회를 요청 하시겠습니까? 철회 시 사유에 따라 배송료가 발생 될 수 있습니다.")) {
							var uid = $(this).attr("uid");
							location.href = "<?= G5_SHOP_URL; ?>/orderinquiryreturnform.php?od_id=" + od_id + "&act=return&uid=" + uid;
						}
						break;
					case "철회취소":
						if (confirm("철회요청을 취소 하시겠습니까?")) {
							var uid = $(this).attr("uid");

							$("form[name=orderinquirychange_form] input[name=act]").val("철회취소");
							$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
							$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
							$("form[name=orderinquirychange_form]").submit();
						}
						break;
					case "해지신청":
						$.post(
							"./orderinquiry.contractout.php", {
								od_id: od_id,
								uid: uid
							},
							function(data) {
								$("#dvOrderinquiryPopup").html(data);
							}
						);
						break;
					case "해지취소":
						if (confirm("해지신청을 취소 하시겠습니까?")) {
							var uid = $(this).attr("uid");

							$("form[name=orderinquirychange_form] input[name=act]").val("해지취소");
							$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
							$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
							$("form[name=orderinquirychange_form]").submit();
						}
						break;
					case "위약금납부":
						var uid = $(this).attr("uid");
						location.href = "<?= G5_SHOP_URL; ?>/orderform.out.php?od_id=" + od_id + "&uid=" + uid;
						break;
					case "위약금영수증":
						var uid = $(this).attr("uid");
						location.href = "<?= G5_SHOP_URL; ?>/orderform.out2.php?od_id=" + od_id + "&uid=" + uid;
						break;
					case "구매확정":
						if (confirm("구매확정 시 반품 및 교환이 불가합니다. 확정 하시겠습니까?")) {
							var uid = $(this).attr("uid");

							$("form[name=orderinquirychange_form] input[name=act]").val("구매확정");
							$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
							$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
							$("form[name=orderinquirychange_form]").submit();
						}
						break;
					case "리뷰작성":
						var it_id = $(this).attr("it_id");
						var ct_id = $(this).attr("ct_id");
						$('#od_review_select').attr("it_id", it_id);
						$('#od_review_select').attr("ct_id", ct_id);
						$('#od_review_select').prop('hidden', false);

						//location.href="<?= G5_SHOP_URL; ?>/itemuseform.php?it_id="+it_id;
						break;
					case "리뷰보기":
						var it_id = $(this).attr("it_id");
						location.href = "<?= G5_SHOP_URL; ?>/item.php?it_id=" + it_id + "#review";
						break;
					case "계약취소":
						var uid = $(this).attr("uid");
						location.href = "<?= G5_SHOP_URL; ?>/orderinquirycancelform.php?od_id=" + od_id + "&uid=" + uid;
						break;
					case "리스시작하기":
						if (confirm("리스를 시작 하시겠습니까?")) {
							var uid = $(this).attr("uid");

							$("form[name=orderinquirychange_form] input[name=act]").val("리스시작하기");
							$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
							$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
							$("form[name=orderinquirychange_form]").submit();
						}
						break;
					case "수선비용결제":
						var uid = $(this).attr("uid");
						location.href = "<?= G5_SHOP_URL; ?>/orderform2.php?od_id=" + od_id;
						break;
					case "계약서다운로드":
						url = "<?= G5_SHOP_URL; ?>/orderinquiryview.rental.php?od_id=" + od_id;
						window.open(url, "rentalpdf", "left=100,top=100,width=800,height=600,scrollbars=0");
						break;
				}
			}

			$(document).on("click", "#orderinquiry_btn button", $.fn.orderinquiry_btn_click);
		};
	});
</script>
<div id="dvOrderinquiryPopup"></div>