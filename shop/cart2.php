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

$cart_action_url = G5_SHOP_URL.'/cartupdate2.php';

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

// $s_cart_id 로 현재 장바구니 자료 쿼리
$sql = "
select
a.ct_id,
a.it_id,
a.it_name,
a.ct_price,
a.ct_point,
a.ct_qty,
a.ct_status,
a.ct_send_cost,
a.it_sc_type,
a.ct_rental_price,
a.ct_item_rental_month,
a.ct_option,
a.io_price,
b.ca_id,
b.ca_id3,
b.it_sc_minimum,
b.it_item_type,
b.it_stock_qty,
b.it_point_type,
b.it_point,
c.company_name
from
lt_shop_cart a
left join lt_shop_item b on ( a.it_id = b.it_id )
left join lt_member_company c on (b.ca_id3 = c.company_code)
where
a.od_id = '".$s_cart_id."'
and a.od_type = '".$od_type."'
order by
b.ca_id3 asc,
a.it_sc_type desc,
a.it_id asc
";
$result = sql_query($sql);
$cart_count = sql_num_rows($result);
if($cart_count){
	$send_cost = get_sendcost($s_cart_id, 0);
}
?>

<!-- 장바구니 시작 { -->
<script src="<?=G5_JS_URL; ?>/shop.js"></script>
<script src="<?=G5_JS_URL; ?>/shop.override.js"></script>

<?
//$todapth = "마이페이지";
$title = "장바구니";
?>
<? require_once $_SERVER['DOCUMENT_ROOT']."/lib/navigation.php" ?>
<!-- container -->
<div id="container">
	<div class="content mypage sub">
		<!-- 컨텐츠 시작 -->
		<div class="grid" id="sit_sel_option">
			<form name="frmcartlist" id="sod_bsk_list" class="2017_renewal_itemform" method="post" action="<?=$cart_action_url; ?>">
				<input type="hidden" name="od_type" value="<?=$od_type?>" />
				<? if($cart_count) { ?>
					<div class="title_bar none" id="sod_chk">
						<span class="chk check"><input type="checkbox" name="ct_all" value="1" id="ct_all" checked /> <label for="ct_all">전체선택</label></span>
						<span class="floatR">총 <?=$cart_count?>건</span>
					</div>
				<? } ?>

				<table class="TBasic3">
					<? if($od_type == "O") { ?>
						<colgroup>
							<col width="7%" />
							<col width="10%" />
							<col width="53%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
						</colgroup>
						<thead>
							<tr>
								<th>선택</th>
								<th>이미지</th>
								<th>상품정보</th>
								<th>상품금액</th>
								<th>배송비<br/>(판매처)</th>
								<th>주문금액</th>
							</tr>
						</thead>
					<? } else if($od_type == "R") { ?>
						<colgroup>
							<col width="7%" />
							<col width="10%" />
							<col width="53%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
						</colgroup>
						<thead>
							<tr>
								<th>선택</th>
								<th>이미지</th>
								<th>상품정보</th>
								<th>상품금액<br/>(약정기간)</th>
								<th>배송비<br/>(판매처)</th>
								<th>총납입금액</th>
							</tr>
						</thead>
					<? } ?>
					<tbody>
						<?
						$i = 0;
						$tot_point = 0;
						$tot_sell_price = 0;
						$tot_sell_rental_price = 0;
						$tot_sell_rental_price_all = 0;
						$it_send_cost = 0;
						$ct_item_rental_month = 0;
						$send_cost_rowspan = 0;
						$sql = "
						select
						a.ct_id,
						a.it_id,
						a.it_name,
						a.ct_price,
						a.ct_point,
						a.ct_qty,
						a.ct_status,
						a.ct_send_cost,
						a.it_sc_type,
						a.ct_rental_price,
						a.ct_item_rental_month,
						a.ct_option,
						a.io_price,
						b.ca_id,
						b.ca_id3,
						b.it_sc_minimum,
						b.it_item_type,
						b.it_stock_qty,
						b.it_point_type,
						b.it_point,
						c.company_name,
						c.mb_id,
						SUM((a.ct_price + a.io_price) * a.ct_qty) as price,
						SUM((d.its_price + a.io_price) * a.ct_qty) as before_price,
						SUM((a.ct_rental_price + a.io_price) * a.ct_qty) as rental_price,
						SUM(a.ct_point * a.ct_qty) as point,
						SUM(a.ct_qty) as qty
						from
						lt_shop_cart a
						left join lt_shop_item b on ( a.it_id = b.it_id )
						left join lt_member_company c on (b.ca_id3 = c.company_code)
						inner join lt_shop_item_sub as d on a.it_id = d.it_id and a.its_no = d.its_no
						where
						a.od_id = '".$s_cart_id."'
						and a.od_type = '".$od_type."'
						group by
						a.it_id
						order by
						b.ca_id3 asc,
						a.it_sc_type desc,
						a.it_id asc
						";
						$result = sql_query($sql);
						while($row=sql_fetch_array($result)){
							$image = get_it_image($row['it_id'], 80, 80);
							$it_name = '<a href="./item.php?it_id='.$row['it_id'].'">'.stripslashes($row['it_name']).'</a>';
							$it_options = print_item_options($row['it_id'], $s_cart_id);
							?>
							<tr>
								<td class="tcenter"><span class="chk check"><input type="checkbox" class="ct_chk" name="ct_chk[<?=$i?>]" value="1" id="ct_chk_<?=$i?>" checked /></span></td>
								<td class="tcenter"><?=$image; ?></td>
								<td>
									<p class="bold" style="margin:5px 0;"><?=$it_name; ?></p>
									<table class="TBasic0">
										<colgroup>
											<col width="32%"/>
											<col width="15%"/>
											<col width="15%"/>
											<col width="20%"/>
											<col width="18%"/>
										</colgroup>
										<thead>
											<tr>
												<th>옵션명</th>
												<th>단가</th>
												<th>추가금액</th>
												<th>수량</th>
												<th>합계</th>
											</tr>
										</thead>
										<tbody>
											<?
											$sqlopt = "
											select
											a.ct_id,
											a.it_id,
											a.it_name,
											a.ct_price,
											a.io_price,
											a.ct_qty,
											a.ct_option,
											a.ct_rental_price
											from
											lt_shop_cart a
											where
											a.od_id = '".$s_cart_id."'
											and a.od_type = '".$od_type."'
											and a.it_id = '".$row['it_id']."'
											order by
											a.it_sc_type desc,
											a.it_id asc
											";
											$resultopt = sql_query($sqlopt);
											$n = 0;
											while($rowopt=sql_fetch_array($resultopt)){
												?>
												<input type="hidden" name="ct_id[<?=$n; ?>]" value="<?=$rowopt['ct_id']; ?>">
												<input type="hidden" name="it_id[<?=$n; ?>]" value="<?=$rowopt['it_id']; ?>">
												<input type="hidden" name="it_name[<?=$n; ?>]" value="<?=get_text($rowopt['it_name']); ?>">
												<tr>
													<td><?=$rowopt['ct_option']?></td>
													<td class="tright"><span id="ct_price_<?=$i.$n?>"><?=$od_type=="O"?number_format($rowopt['ct_price']):number_format($rowopt['ct_rental_price'])?></span> 원</td>
													<td class="tright"><span id="io_price_<?=$i.$n?>"><?=number_format($rowopt['io_price'])?></span> 원</td>
													<td class="tcenter">
														<button type="button" class="count_minus2" onclick="odrnumchk($('#qty_<?=$i.$n?>').val(), '<?=$i?>', '<?=$n?>', 'minus');"><i class="axi axi-square-minus"></i></button>
														<input type="text" name="ct_qty[<?=$row['ct_id']?>]" id="qty_<?=$i.$n?>" value="<?=$rowopt['ct_qty'] ?>" class="tcenter" size="2" style="height:26px; width:30px; font-size:12px; text-align:center; padding:0px;" onchange="odrnumchk($(this).val(), '<?=$i?>', '<?=$n?>', '');">
														<button type="button" class="count_plus2" onclick="odrnumchk($('#qty_<?=$i.$n?>').val(), '<?=$i?>', '<?=$n?>', 'plus');"><i class="axi axi-square-plus"></i></button>
													</td>
													<td class="tright"><span id="all_price_<?=$i.$n?>">
														<? if($od_type == "O") { ?>
															<?=number_format(($rowopt['ct_price']+$rowopt['io_price'])*$rowopt['ct_qty'])?></span>원
														<? } else if($od_type == "R") { ?>
															<?=number_format(($rowopt['ct_rental_price']+$rowopt['io_price'])*$rowopt['ct_qty'])?></span>원
														<? } ?>
													</td>
												</tr>
												<?
												$n++;
											}
											?>
										</tbody>
									</table>
								</td>
								<td class="tcenter">
									<? if($od_type == "O") { ?>
										<span id="item_price_<?=$i?>"><?=number_format($row['price'])?></span>원
									<? } else if($od_type == "R") { ?>
										월 <span id="item_price_<?=$i?>"><?=number_format($row['rental_price']); ?></span>원
										<br/>(<span id="item_month"><?=$row['ct_item_rental_month']?></span> 개월)
									<? } ?>
									<? if($sum['point'] && $config['cf_use_point']){ ?>
										<br/>(적립금 : <?=number_format($sum['point'])?>원)
									<? } ?>
								</td>
								<td class="tcenter">
									<input type="hidden" name="it_sc_type_[]" id="it_sc_type_<?=$i?>" value="<?=$row['it_sc_type']==0?'N':'Y'?>" />
									<input type="hidden" name="ct_send_cost[]" id="ct_send_cost_<?=$i?>" value="<?=$row['ct_send_cost']==0?'N':'Y'?>" />
									<input type="hidden" name="ca_id3_[]" value="<?=$row['ca_id3']?>" />
									<?
									if($row['it_sc_type']==0){
										$delivery_price = $default['de_send_cost_list'];
										echo "<input type='hidden' id='send_cost_price_".$i."' value='".$delivery_price."'/>";
										echo "<input type='hidden' id='send_cost_limit_".$i."' value='".$default['de_send_cost_limit']."'/>";
									} else {
										$delivery_price = $row['it_sc_price'];
										echo "<input type='hidden' id='send_cost_price_".$i."' value='".$delivery_price."'/>";
										echo "<input type='hidden' id='send_cost_limit_".$i."' value='".$row['it_sc_minimum']."'/>";
									}
									?>

									<? if($delivery_price == 0){ ?>
										<span id="delivery_<?=$i?>">무료배송</span>
									<? } else { ?>
										<span id="delivery_<?=$i?>"><?=number_format($delivery_price)?> 원</span>
									<? } ?>

									<br/><?=$row['it_sc_type']==0?'(묶음배송)':'(개별배송)'?>

									<input type="hidden" id="delivery_num_<?=$i?>" name="" value="<?=$delivery_price?>" />
									<br/>(<span id="company_name_<?=$i?>"><?=($row['company_name'])?$row['company_name']:$default['de_admin_company_name'] ?></span>)
								</td>
								<td class="tcenter">
									<span id="item_sum_price_<?=$i?>"><?=$od_type == "O"?number_format($row['price'] + $delivery_price):number_format(($row['rental_price'] * (int)$row['ct_item_rental_month']) * $row['ct_qty'])?></span>원
								</td>
							</tr>
							<?
							$tot_point      += $point;
							$tot_sell_price += $row['price'];
							$tot_delivery_price += $delivery_price;
							$tot_sell_rental_price += $row['rental_price'];
							$ct_item_rental_month = (int)$row['ct_item_rental_month'];
							$tot_sell_rental_price_all += $row['rental_price'] * (int)$row['ct_item_rental_month'];
							$i++ ;
						}
						?>
						<? if ($i == 0){?>
							<tr>
								<td colspan="20" class="tcenter">장바구니에 담긴 상품이 없습니다.</td>
							</tr>
						<? } ?>
					</tbody>
				</table>

				<input type="hidden" name="default_delivery_limit" value="<?=$default['de_send_cost_limit']?>" />
				<input type="hidden" name="default_delivery_pay" value="<?=$default['de_send_cost_list']?>" />

				<? if ($i != 0) { ?>
					<?
					if($od_type == "O") {
						$tot_price = $tot_sell_price + $delivery_price;
						if ($tot_price > 0 || $delivery_price > 0) {
							?>
							<div class="order_cont chk_order">
								<div class="body">
									<div class="tcenter">

										상품금액 합계
										<span id="item_all_sum_price"><?=number_format($tot_sell_price); ?></span> 원
										<i class="axi axi-add-circle"></i>
										배송비 합계
										<span id="all_delivery_sum_price"><?=number_format($tot_delivery_price); ?></span> 원
										<i class="axi axi-pause-circle-fill"></i>
										결제금액 합계
										<span id="all_sum_price"><?=number_format($tot_price); ?></span> 원
									</div>
								</div>
							</div>
						<? } ?>
					<? } else if($od_type == "R") { ?>
						<div class="order_cont chk_order">
							<div class="body">
								<div class="tcenter">
									월 리스 금액
									<span id="item_all_sum_price"><?=number_format($tot_sell_rental_price); ?></span> 원
									<i class="axi axi-times-circle"></i>

									<span id="all_month"><?=number_format($ct_item_rental_month); ?></span> 개월
									<i class="axi axi-pause-circle-fill"></i>
									총 리스 금액 합계
									<span id="all_sum_price"><?=number_format($tot_sell_rental_price_all); ?></span> 원
								</div>
							</div>
						</div>
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
	<input type="hidden" name="od_type" value="<?=$od_type ?>">
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

		$(".count_plus2").click(function() {
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

		$(".count_minus2").click(function() {
			var this_qty, max_qty = 9999, min_qty = 1;
			var $el_qty = $(this).closest("td").find("input[name^=ct_qty]");
			var stock = parseInt($(this).closest("td").find("input.io_stock").val());

			this_qty = parseInt($el_qty.val().replace(/[^0-9]/, "")) - 1;
			if(this_qty <= 0) {
				alert("1개 이상 선택하세요.");
				this_qty = 1;
			}

			$el_qty.val(this_qty);
			price_calculate();
		});

		// 모두선택
		$("input[name=ct_all]").click(function() {
			if($(this).is(":checked")){
				$("input[name^=ct_chk]").attr("checked", true);
			} else {
				$("input[name^=ct_chk]").attr("checked", false);
			}
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
					} else {
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

	function odrnumchk(number, num, opt, pm){
		var ct_price = parseInt($("span[id='ct_price_"+num+opt+"']").html().replace(/,/gi, ""));
		var io_price = parseInt($("span[id='io_price_"+num+opt+"']").html().replace(/,/gi, ""));
		var ct_qty = parseInt($("input[id='qty_"+num+opt+"']").val());
		var all_price = $("span[id='all_price_"+num+opt+"']");
		var item_all_price = 0;
		var delivery_price = 0;
		var item_all_sum_price = 0;
		var all_delivery_sum_price = 0;
		<? if($od_type == "R") {?>
			var all_month = parseInt($("span[id='all_month']").html().replace(/,/gi, ""));
		<? } ?>

		if(pm == 'plus'){
			var qty = ct_qty + 1;
		} else if(pm == 'minus'){
			var qty = ct_qty - 1;
		} else {
			var qty = number;
		}

		if(qty > 0){
			Calculator = (ct_price + io_price) * qty;
		} else {
			Calculator = (ct_price + io_price) * 1;
		}
		parseInt(all_price.html(number_format(Calculator)));

		//상품옵션 금액 합계
		$("span[id^='all_price_"+num+"']").each(function(){
			item_all_price += parseInt($(this).html().replace(/,/gi, ""));
		});

		//상품금액 합계
		$('#item_price_'+num).html(number_format(item_all_price));

		//배송비 x금액 이상일때 0원처리
		var deli_price =  parseInt($('#send_cost_price_'+num).val().replace(/,/gi, ""));
		var deli_limit =  parseInt($('#send_cost_limit_'+num).val().replace(/,/gi, ""));

		if(item_all_price >= deli_limit){
			$('#delivery_'+num).html('무료배송');
		} else {
			$('#delivery_'+num).html(number_format(deli_price)+'원');
		}

		//주문금액 합계
		if($('#delivery_'+num).html() == '무료배송'){
			delivery_price = 0;
		} else {
			delivery_price = parseInt($('#delivery_'+num).html().replace(/,/gi, ""));
		}
		$('#item_sum_price_'+num).html(number_format(item_all_price + delivery_price));

		//전체상품 합계
		$("span[id^='item_price_']").each(function(){
			item_all_sum_price += parseInt($(this).html().replace(/,/gi, ""));
		});
		$('#item_all_sum_price').html(number_format(item_all_sum_price));

		//전체배송비 합계
		$("span[id^='delivery_']").each(function(){
			if($(this).html() == '무료배송'){
				delivery_price = 0;
			} else {
				delivery_price = parseInt($(this).html().replace(/,/gi, ""));
			}
			all_delivery_sum_price += delivery_price;
		});
		$('#all_delivery_sum_price').html(number_format(all_delivery_sum_price));

		<? if($od_type == "R") {?>
			$('#all_sum_price').html(number_format(item_all_sum_price*all_month));
		<? } else {?>
			$('#all_sum_price').html(number_format(item_all_sum_price+all_delivery_sum_price));
		<? } ?>

		deliverychk();
	}

	function submitcart(){
		jQuery.ajax({
			type:"GET",
			url:"/test",
			dataType:"JSON",
			success : function(data) {
			},
			complete : function(data) {
			},
			error : function(xhr, status, error) {
				alert("에러발생");
			}
		});
	}

	function deliverychk(){
		var deliverylimitpay = parseInt($("input[name='default_delivery_limit']").val().replace(/,/gi, ""));
		var delivery_pay = parseInt($("input[name='default_delivery_pay']").html().replace(/,/gi, ""));
		var deliverychk = [];
		var paysum = 0;

		for (var i = 0; i < $("input[id^='it_sc_type_']").length; i++) {
			var delivery_type = $("input[id='it_sc_type_"+i+"']").val();
			var item_pay = parseInt($("span[id='item_price_"+i+"']").html().replace(/,/gi, ""));
			deliverychk.push({
				"type":delivery_type,
				"pay":item_pay
			});

			if(delivery_type == 'N'){
				paysum += item_pay;
			}
		}

		if(paysum > deliverylimitpay){
			$("span[id^='delivery_']").each(function(){
				//console.log();
				if($(this).siblings($("input[id^=it_sc_type_]")).val() == 'N'){
					$(this).html("무료배송");
					//$(this).siblings($("input[id^=send_cost_price]").val("0"));
					$(this).siblings($("input[id^=delivery_num]").val("0"));
				} else {
					$(this).siblings($("input[id^=item_price_]").val("0"));
				}
			});
		}

		//console.log(paysum);
		//상품 합계금액 체크
		//console.log(deliverychk);
	}

	//deliverychk();
</script>
<?
include_once('./_tail.php');
?>
