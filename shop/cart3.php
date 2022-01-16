<?
include_once('./_common.php');
include_once(G5_SHOP_PATH.'/settle_naverpay.inc.php');

// 보관기간이 지난 상품 삭제
cart_item_clean();

// cart id 설정
set_cart_id($sw_direct);
set_session("ss_direct", $sw_direct);

$s_cart_id = get_session('ss_cart_id');
// 선택필드 초기화
$sql = " update {$g5['g5_shop_cart_table']} set ct_select = '0' where od_id = '$s_cart_id' ";
sql_query($sql);

$cart_action_url = G5_SHOP_URL.'/cartupdate.php';

if (G5_IS_MOBILE) {
	include_once(G5_MSHOP_PATH.'/cart.php');
	return;
}

// 테마에 cart.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
	$theme_cart_file = G5_THEME_SHOP_PATH.'/cart.php';
	if(is_file($theme_cart_file)) {
		include_once($theme_cart_file);
		return;
		unset($theme_cart_file);
	}
}

$g5['title'] = '장바구니';
include_once('./_head.php');
if(!$od_type) $od_type = "O";
?>

<!-- 장바구니 시작 { -->
<script src="<?=G5_JS_URL; ?>/shop.js"></script>
<script src="<?=G5_JS_URL; ?>/shop.override.js"></script>

<!-- container -->
<div id="container">
	<!-- lnb -->
	<div id="lnb" class="header_bar">
		<h1 class="title"><span>장바구니</span></h1>
	</div>
	<!-- //lnb -->
	<div class="content mypage sub">
		<!-- 컨텐츠 시작 -->
		<div class="grid" id="sit_sel_option">
			<div class="tab fix">
				<ul class="type4 black center onoff">
					<li <?=($od_type=="O")?'class="on"':'' ?> onclick="location.href='<?=G5_SHOP_URL ?>/cart3.php?od_type=O';"><a href="#"><span>제품</span></a></li>
					<li <?=($od_type=="R")?'class="on"':'' ?> onclick="location.href='<?=G5_SHOP_URL ?>/cart3.php?od_type=R';"><a href="#"><span>리스</span></a></li>
				</ul>
			</div>

			<form name="frmcartlist" id="sod_bsk_list" class="2017_renewal_itemform" method="post" action="<?=$cart_action_url; ?>">
				<input type="hidden" name="od_type" value="<?=$od_type?>" />

				<? if($cart_count) { ?>
					<div class="title_bar none" id="sod_chk">
						<span class="chk check"><input type="checkbox" name="ct_all" value="1" id="ct_all" checked /> <label for="ct_all">전체선택</label></span>
						<span class="floatR">총 <?=$cart_count?>건</span>
					</div>
				<? } ?>

				<table class="TBasic">
					<? if($od_type == "O") { ?>
						<colgroup>
							<col width="5%" />
							<col width="10%" />
							<col width="35%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
						</colgroup>
						<thead>
							<tr>
								<th>선택</th>
								<th>이미지</th>
								<th>상품정보</th>
								<th>적립금</th>
								<th>상품금액</th>
								<th>수량</th>
								<th>배송비</th>
								<th>주문금액</th>
							</tr>
						</thead>
					<? } else if($od_type == "R") { ?>
						<colgroup>
							<col width="5%" />
							<col width="10%" />
							<col width="25%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
						</colgroup>
						<thead>
							<tr>
								<th>선택</th>
								<th>이미지</th>
								<th>상품정보</th>
								<th>적립금</th>
								<th>상품금액</th>
								<th>약정기간</th>
								<th>수량</th>
								<th>배송비</th>
								<th>주문금액</th>
							</tr>
						</thead>
					<? } ?>
					<tbody>
						<?
						$i = 0;
						$sql = "
							SELECT
								lsc.ct_id,
								lsc.it_id,
								lsc.it_name,
								lsc.ct_price,
								lsc.ct_point,
								lsc.ct_qty,
								lsc.ct_status,
								lsc.ct_send_cost,
								lsc.it_sc_type,
								lsc.ct_rental_price,
								lsc.ct_item_rental_month,
								lsc.ct_option,
								lsc.io_price,
								lsi.ca_id,
								lsi.ca_id3,
								lsi.it_sc_minimum,
								lsi.it_sc_price,
								lsi.it_item_type,
								lsi.it_stock_qty,
								lsi.it_point_type,
								lsi.it_point,
								lmc.company_name
							FROM
								`lt_shop_cart` lsc
								left join lt_shop_item lsi on lsc.it_id = lsi.it_id
								left join lt_member_company lmc on lsi.ca_id3 = lmc.company_code
							WHERE
								lsc.`od_id` = '".$s_cart_id."'
								AND lsc.od_type = '".$od_type."'
							order by
								lsi.ca_id3 asc,
								lsc.it_sc_type desc,
								lsc.it_id asc
						";
						$result = sql_query($sql);
						while ($row=sql_fetch_array($result)) {
							if ($i==0) {
								$continue_ca_id = $row['ca_id'];
							}
							$image = get_it_image($row['it_id'], 80, 80);
							$it_options = print_item_options($row['it_id'], $s_cart_id);

							//상품가격표시부분
							if($od_type == "O"){
								$item_price = $row['ct_price'];
							} else {
								$item_price = $row['ct_rental_price'];
							}

							//배송비 정책부분
							if($row['it_sc_type'] == 2){
								//상품별 배송정책;
								$row['it_sc_minimum']; //최소구매금액
								$row['it_sc_price']; //배송비
							} else if($row['it_sc_type'] == 0){
								//기본배송정책
								$default['de_send_cost_case']; //최소구매금액
								$default['de_send_cost_list']; //배송비
							} else {
								//이도저도아닌경우
							}
							?>
							<tr>
								<td class="tcenter"><span class="chk check"><input type="checkbox" class="ct_chk" name="ct_chk[<?=$i?>]" value="1" id="ct_chk_<?=$i?>" checked /></span></td>
								<td class="tcenter"><a href="/shop/item.php?it_id=<?=$row['it_id']?>"><?=$image?></a></td>
								<td>
									[<?=($row['company_name'])?$row['company_name']:$default['de_admin_company_name'] ?>]<br/>
									<a href="/shop/item.php?it_id=<?=$row['it_id']?>"><?=$row['it_name']?></a><br/>
									<span class="txt">옵션 </span> <span class="point_black"><?=$it_options; ?> </span>
								</td>
								<td class="tright">
									<?=number_format($row['ct_point'])?>원
								</td>
								<td class="tright">
									<?=$od_type == "R"?'월':''?>	<span id="item_price_<?=$i?>"><?=number_format($item_price); ?></span>원
								</td>
								<? if($od_type == "R") { ?>
									<td class="tright"><?=$row['ct_item_rental_month']?> 개월</td>
								<? } ?>
								<td class="tcenter">
									<span class="point_black">
										<span class="count_control">
											<em class="num"><input type="text" name="ct_qty[<?=$row['ct_id']?>]" id="qty_<?=$i?>" value="<?=$row['ct_qty'] ?>" class="tcenter" size="2" style="height:26px; width:50px;"></em>
											<button type="button" class="count_minus" onclick="priceSet(<?=$i?>);"><span class="blind">감소</span></button>
											<button type="button" class="count_plus" onclick="priceSet(<?=$i?>);"><span class="blind">증가</span></button>

											<input type="hidden" name="ct_id[<?=$i?>]"    value="<?=$row['ct_id']; ?>">
											<input type="hidden" name="it_id[<?=$i?>]"    value="<?=$row['it_id']; ?>">
											<input type="hidden" name="it_name[<?=$i?>]"  value="<?=get_text($row['it_name']); ?>">
											<input type="hidden" class="io_stock"  value="<?=$row['it_stock_qty']; ?>">
										</span>
									</span>
									<input type="hidden" name="it_sc_minimum_<?=$i?>" value="<?=$row['it_sc_minimum']?>" id="it_sc_minimum_<?=$i?>" />
								</td>
								<td class="tcenter tdrow1">
									<span id="delivery_<?=$i?>">	</span>
									<input type="hidden" id="delivery_num_<?=$i?>" name="" value="<?=$ct_send_cost?>" />
								</td>
								<td class="tright">
									<span id="item_sum_price_<?=$i?>"><?=$od_type == "O"?number_format($sell_price + $send_cost):number_format(($sell_rental_price * (int)$row['ct_item_rental_month']) * $row['ct_qty'])?></span>원
								</td>
							</tr>
						<? $i++; } ?>

						<? if ($i == 0){?>
							<tr>
								<td colspan="20" class="tcenter">장바구니에 담긴 상품이 없습니다.</td>
							</tr>
						<? } ?>
					</tbody>
					<? if($i != 0){ ?>
						<tfoot>
							<? if($od_type == "O") { ?>
								<tr>
									<td colspan="6" class="tcenter">
										<span class="txt">상품금액 (옵션포함)</span> : <span class="point_black" id="total_item_price"><?=number_format($tot_sell_price)?></span>원
										<?
										$tot_price = $tot_sell_price + $send_cost;
											// 총계 = 주문상품금액합계 + 배송비
										if ($tot_price > 0 || $send_cost > 0) {
											?>
											(<span class="txt">적립금</span> : <span class="point_black" id="total_point"><?=number_format($tot_point); ?></span>원)

											+ <span class="txt">배송비</span> : <span class="point_black" id="total_delivery"><?=number_format($send_cost); ?></span>원
										<? } ?>
									</td>
									<td colspan="2" class="tright bold">
										<? if ($tot_price > 0) { ?>
											결제금액 : <span class="amount fred" id="total_price"><?=number_format($tot_price)?></span>원
										<? } ?>
									</td>
								</tr>
								<?} else if($od_type == "R") {?>
									<tr>
										<td colspan="6" class="tcenter">
											<?
											$tot_price = $tot_sell_rental_price;
											if ($tot_price > 0) {
												?>

												<? if ($tot_point > 0 && $config['cf_use_point']) { ?>
													<div class="info">
														<p><span class="txt">적립금</span><span class="point_black"><?=number_format($tot_point); ?> point</span></p>
													</div>
												<? } ?>

												<? if ($send_cost > 0) { ?>
													<div class="info">
														<p><span class="txt">배송비</span><span class="point_black"><?=number_format($send_cost); ?> 원</span></p>
													</div>
												<? } ?>

												<div class="pay_item">
													<? if ($tot_sell_rental_price > 0) { ?>
														월 이용료 합계
														<span class="amount"><?=number_format($tot_sell_rental_price); ?> 원</span>
													<? } ?>
												</div>
											<? } ?>
										</td>
										<td colspan="3" class="tright bold">
											<? if ($tot_price > 0) { ?>
												<span class="amount">총 리스료 합계</span>
												<span class="amount fred"><?=number_format($tot_sell_rental_price_all + $send_cost); ?></span>원
											<? } ?>
										</td>
									</tr>
								<? } ?>
							<? } ?>
						</tfoot>
					</table>

					<? if ($i != 0) { ?>
						<?
						if($od_type == "O") {
							$tot_price = $tot_sell_price + $send_cost;
							if ($tot_price > 0 || $send_cost > 0) {
								?>
								<div class="order_cont chk_order">
									<div class="body">
										<div class="tcenter">

											상품금액 합계
											<?=number_format($tot_sell_price); ?>원

											<i class="axi axi-add-circle"></i>

											배송비 합계
											<?=number_format($send_cost); ?>원

											<i class="axi axi-pause-circle-fill"></i>

											결제금액 합계
											<?=number_format($tot_price); ?>원

										</div>
									</div>
								</div>
							<? } ?>
						<? } ?>
					<? } ?>

					<div class="btn_group count3">
						<button type="button" onclick="return form_check('alldelete');" class="btn big border"><span>전체 제품 삭제</span></button>
						<button type="button" onclick="return form_check('seldelete');" class="btn big border"><span>선택 제품 삭제</span></button>
						<input type="hidden" name="url" value="<?=G5_SHOP_URL; ?>/orderform.php">
						<input type="hidden" name="act" value="">
						<input type="hidden" name="records" value="<?=$i?>">
						<button type="button" onclick="return form_check('buy');" class="btn big green">바로 주문</button>
					</div>

					<? if ($naverpay_button_js) { ?>
						<div class="naverpay-cart"><?=$naverpay_request_js.$naverpay_button_js; ?></div>
					<? } ?>
				</form>
			</div>
		</div>
	</div>

	<form name="fitem" method="post" >
		<input type="hidden" name="it_id" value="">
		<input type="hidden" name="od_type" value="<? $od_type ?>">
		<input type="hidden" name="sw_direct">
		<input type="hidden" name="url">
	</form>

	<!-- //container -->
	<script>
		$(function() {
			var close_btn_idx;

			$(".ct_chk").click(function() {
				$(this).attr("checked",$(this).is(":checked"));
			});

			$(".count_plus").click(function() {
				var this_qty, max_qty = 9999, min_qty = 1;
				var $el_qty = $(this).closest("td").find("input[name^=ct_qty]");
				var stock = parseInt($(this).closest("td").find("input.io_stock").val());

				this_qty = parseInt($el_qty.val().replace(/[^0-9]/, "")) + 1;
				if(this_qty > stock) {
					alert("재고수량 보다 많은 수량을 구매할 수 없습니다.");
					this_qty = stock;
				}

				if(this_qty > max_qty) {
					this_qty = max_qty;
					alert("최대 구매수량은 "+number_format(String(max_qty))+" 입니다.");
				}

				$el_qty.val(this_qty);
				price_calculate();
			});

			// 모두선택
			$("input[name=ct_all]").click(function() {
				if($(this).is(":checked"))
					$("input[name^=ct_chk]").attr("checked", true);
				else
					$("input[name^=ct_chk]").attr("checked", false);
			});

			$(".count_delete").click(function() {
				var $this = $(this);
				var ct_id = $this.attr("ct_id");

				if(confirm("선택하신 옵션항목을 삭제하시겠습니까?")) {

					$this.addClass("disabled").attr("disabled", true);
					$.ajax({
						url: g5_url+"/shop/ajax.cartupdate.php",
						type: "POST",
						data: {
							"act": "del"
							,"ct_id" : ct_id
						},
						dataType: "json",
						async: false,
						cache: false,
						success: function(data) {
							if(data.error != "") {
								$this.removeClass("disabled").attr("disabled", false);
								alert(data.error);
								return false;
							}

							$this.attr("disabled", false);
					//alert("삭제되었습니다.");
					location.href="<?=G5_SHOP_URL.'/cart.php?od_type='.$od_type ?>";
					/*
					var $el = $this.closest("li");
					$el.closest("li").remove();
					price_calculate();
					*/
				}
			});

				}
			});

			$(".count_mod").click(function() {
				var $this = $(this);
				var ct_id = $this.attr("ct_id");
				var ct_qty = $("input[name='ct_qty["+ct_id+"]']").val();

				if(confirm("선택하신 옵션항목 수량을 적용하시겠습니까?")) {

					$this.addClass("disabled").attr("disabled", true);
					$.ajax({
						url: g5_url+"/shop/ajax.cartupdate.php",
						type: "POST",
						data: {
							"act": "mod"
							,"ct_id" : ct_id
							,"ct_qty" : ct_qty
						},
						dataType: "json",
						async: false,
						cache: false,
						success: function(data) {
							if(data.error != "") {
								$this.removeClass("disabled").attr("disabled", false);
								alert(data.error);
								return false;
							}

							$this.attr("disabled", false);
							location.href="<?=G5_SHOP_URL.'/cart.php?od_type='.$od_type ?>";

					//alert("수정되었습니다.");
					//price_calculate();
				}
			});

				}
			});
		});


		function form_check(act) {
			var f = document.frmcartlist;
			var cnt = f.records.value;

			if (act == "buy")
			{
				f.act.value = act;
				f.submit();
			}
			else if (act == "alldelete")
			{
				if(confirm("선택하신 제품을 장바구니에서 삭제하시겠습니다?")) {
					f.act.value = act;
					f.submit();
				}
			}
			else if (act == "seldelete")
			{
				if($("input[name^=ct_chk]:checked").length < 1) {
					alert("삭제하실 상품을 하나이상 선택해 주십시오.");
					return false;
				}

				if(confirm("선택하신 제품을 장바구니에서 삭제하시겠습니다?")) {
					f.act.value = act;
					f.submit();
				}
			}

			return true;
		}

		function item_wish(f, it_id){
			if($(".pick[it_id='"+it_id+"']").attr("class").indexOf("on") < 0) {
				$.post(
					"<?=G5_SHOP_URL; ?>/wishupdate2.php",
					{   it_id : it_id },
					function(data) {
						var responseJSON = JSON.parse(data);
						if(responseJSON.result == "S"){

							if(confirm("관심상품에 저장되었습니다. 보러가시겠습니까?")) location.href='<?=G5_SHOP_URL; ?>/wishlist.php';

							$(".pick[it_id='"+it_id+"']").addClass("on");
						}else {
							alert(responseJSON.alert);
							return false;
						}
					}
					);
			} else {
				$.post(
					"<?=G5_SHOP_URL; ?>/wishupdate2.php",
					{   it_id : it_id, w : 'r' },
					function(data) {
						var responseJSON = JSON.parse(data);
						if(responseJSON.result == "S"){
							$(".pick[it_id='"+it_id+"']").removeClass("on");
						}else {
							alert(responseJSON.alert);
							return false;
						}
					}
					);
			}
		}

		/* 배송비 테이블 셀병합 */
		$(document).ready(function(e){
			genRowspan("tdrow");
		});
		function genRowspan(className){
			$("."+className).each(function() {
				var rows = $("." + className + ":contains('" + $(this).text() + "')");
				if (rows.length > 1) {
					rows.eq(0).attr("rowspan", rows.length);
					rows.not(":eq(0)").remove();
				}
			});
		}

		/* 주문금액 실시간변경 계산수식 */
		function priceSet(i){
			var item_price = parseFloat($('#item_price_'+i).text().replace(",",""));
			var item_num = parseFloat($('#qty_'+i).val());


			if(parseFloat($('#delivery_num_'+i).text().replace(",","")) == 0){
				var item_delivery = 0;
			} else {
				var item_delivery = parseFloat($('#delivery_num_'+i).text().replace(",",""));
			}

			var item_sc_minimum = $('#it_sc_minimum_'+i).val();
			var item_sum_price = item_price * item_num;

			/**/

			/* 배송비 무료처리 */
			if(item_sc_minimum < item_sum_price){
				var price_sum = (item_price * item_num) + item_delivery;
			} else {
				var price_sum = (item_price * item_num);
				$('#delivery_'+i).html('무료배송');
				$('#delivery_num_'+i).val(0);
			}

			/* 배송비 합계금액 */
			var total_delivery = 0;
			$("span[id^='delivery_num_']").each(function(idx){
				total_delivery +=parseFloat($(this).text().replace(",",""));
				//totalsum + = $(this).text();
			})
			if(total_delivery > 0){
				$("#total_delivery").html(number_format(total_delivery));
			} else {
				$("#total_delivery").html(number_format(0));
			}


			/* 상품별 합계금액 구하기 */
			$('#item_sum_price_'+i).html(number_format(price_sum));

			/* 전체 합계금액 구하기 */
			var total_price = 0;
			$("span[id^='item_sum_price_']").each(function(idx){
				total_price +=parseFloat($(this).text().replace(",",""));
				//totalsum + = $(this).text();
			})
			$("#total_price").html(number_format(total_price));

		}

		priceSet();
	</script>
	<? include_once('./_tail.php'); ?>
