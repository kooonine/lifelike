<?php
include_once('./_common.php');

// 테마에 cart.php 있으면 include
if(defined('G5_THEME_MSHOP_PATH')) {
	$theme_cart_file = G5_THEME_MSHOP_PATH.'/cart.php';
	if(is_file($theme_cart_file)) {
		include_once($theme_cart_file);
		return;
		unset($theme_cart_file);
	}
}

$g5['title'] = '장바구니';
include_once(G5_MSHOP_PATH.'/_head.php');

if(!$od_type) $od_type = "O";

// $s_cart_id 로 현재 장바구니 자료 쿼리
$sql = " select a.ct_id,
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
				b.ca_id,
				b.it_item_type,
				a.ct_option,
				a.io_price
		   from {$g5['g5_shop_cart_table']} a left join {$g5['g5_shop_item_table']} b on ( a.it_id = b.it_id )
		  where a.od_id = '$s_cart_id' and a.od_type = '$od_type' ";

//$sql .= " group by a.it_id ";
$sql .= " order by a.ct_id ";
$result = sql_query($sql);

$cart_count = sql_num_rows($result);
?>

<script>
var header = '<div id="lnb" class="header_bar">';
header += '<h1 class="title"><span>장바구니</span></h1>';
header += '<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
header += '</div>';
$('#header').html(header);
</script>

<script src="<?php echo G5_JS_URL; ?>/shop.js"></script>
<script src="<?php echo G5_JS_URL; ?>/shop.override.js"></script>
<!-- //lnb -->

<form name="frmcartlist" id="sod_bsk_list" class="2017_renewal_itemform" method="post" action="<?php echo $cart_action_url; ?>">
<input type="hidden" name="od_type" value="<?php echo $od_type?>" />

<div class="content mypage sub">
	<!-- 컨텐츠 시작 -->
	<div class="grid" id="sit_sel_option">
		<!--
		<div class="tab fix">
			<ul class="type3 onoff col2">
				<li <?php echo ($od_type=="O")?'class="on"':'' ?> onclick="location.href='<?php echo G5_SHOP_URL ?>/cart.php?od_type=O';"><a href="#"><span>제품</span></a></li>
				<li <?php echo ($od_type=="R")?'class="on"':'' ?> onclick="location.href='<?php echo G5_SHOP_URL ?>/cart.php?od_type=R';"><a href="#"><span>리스</span></a></li>
			</ul>
		</div>
		-->

	<?php if($cart_count) { ?>
	<div class="title_bar none" id="sod_chk">
		<span class="chk check">
			<input type="checkbox" name="ct_all" value="1" id="ct_all" checked>
			<label for="ct_all">전체 선택</label>
		</span>
	</div>
	<?php } ?>

	<?php
	$tot_point = 0;
	$tot_sell_price = 0;
	$tot_sell_rental_price = 0;
	$tot_sell_rental_price_all = 0;
	$it_send_cost = 0;

	for ($i=0; $row=sql_fetch_array($result); $i++)
	{
		// 합계금액 계산
		$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((ct_price + io_price) * ct_qty))) as price,
						SUM(IF(io_type = 1, (ct_rental_price * ct_qty), ((ct_rental_price + io_price) * ct_qty))) as rental_price,
						SUM(ct_point * ct_qty) as point,
						SUM(ct_qty) as qty
					from {$g5['g5_shop_cart_table']}
					where ct_id = '{$row['ct_id']}'
					  and od_id = '$s_cart_id' ";
		$sum = sql_fetch($sql);


		if ($i==0) { // 계속쇼핑
			$continue_ca_id = $row['ca_id'];
		}

		$a1 = '<a href="./item.php?it_id='.$row['it_id'].'"><strong>';
		$a2 = '</strong></a>';
		$image_width = 80;
		$image_height = 80;
		$image = get_it_image($row['it_id'], $image_width, $image_height);

		$it_name = $a1 . stripslashes($row['it_name']) . $a2;
		$it_options = print_item_options($row['it_id'], $s_cart_id);

		$price_plus = '';
		if($row['io_price'] >= 0) $price_plus = '+';
		$it_options = get_text($row['ct_option']).' / '.$row['ct_qty'].'개 ('.$price_plus.display_price($row['io_price']).')'.PHP_EOL;

		/*// 배송비
		switch($row['ct_send_cost'])
		{
			case 1:
				$ct_send_cost = '착불';
				break;
			case 2:
				$ct_send_cost = '무료';
				break;
			default:
				$ct_send_cost = '선불';
				break;
		}

		// 조건부무료
		if($row['it_sc_type'] == 2) {
			$sendcost = get_item_sendcost($row['it_id'], $sum['price'], $sum['qty'], $s_cart_id);

			if($sendcost == 0)
				$ct_send_cost = '무료';
		}*/

		$point      = $sum['point'];
		$sell_price = $sum['price'];
		$sell_rental_price = $sum['rental_price'];

		$it_price = $row['ct_price'];
		if($row['it_item_type'] == "1") $it_price = $row['ct_rental_price'];
	?>
	<div class="order_cont chk_order">
		<input type="hidden" name="ct_id[<?php echo $i; ?>]"    value="<?php echo $row['ct_id']; ?>">
		<input type="hidden" name="it_id[<?php echo $i; ?>]"    value="<?php echo $row['it_id']; ?>">
		<input type="hidden" name="it_name[<?php echo $i; ?>]"  value="<?php echo get_text($row['it_name']); ?>">

		<div class="body">
			<div class="cont">
				<span class="chk check">
					<input type="checkbox" class="ct_chk" name="ct_chk[<?php echo $i; ?>]" value="1" id="ct_chk_<?php echo $i; ?>" checked>
				</span>
				<div class="photo">
					<?php echo $image; ?>
				</div>
				<div class="info">
					<li>
					<strong><?php echo $it_name; ?></strong>
					<p>옵션 : <?php echo $it_options; ?> </p>
					<!-- p>적립포인트 : <?php echo number_format($sum['point']); ?></p -->

					<p>수량 : &nbsp;
						<span class="count_control">
							<em class="num"><input type="text" name="ct_qty[<?php echo $row['ct_id']?>]" value="<?php echo $row['ct_qty'] ?>" size="5" style="height:18px;"></em>
							<button type="button" class="count_minus"><span class="blind">감소</span></button>
							<button type="button" class="count_plus"><span class="blind">증가</span></button>
						</span>
					</p>

					<?php if($od_type == "O") { ?>
					<p class="price"><span> 주문 금액 : </span><?php echo number_format($sell_price); ?> 원</p>
					<?php } else if($od_type == "R") { ?>
					<p class="price"><span> 월리스료 : </span> <?php echo number_format($sell_rental_price); ?> 원</p>
					<p class="price"><span> (<?php echo $row['ct_item_rental_month']?>개월) 총 완납금액 : </span><?php echo number_format($sell_rental_price * (int)$row['ct_item_rental_month']); ?> 원</p>
					<?php } ?>
					</li>
				</div>
			</div>
			<div class="btn_comm count3">
				<button type="button" class="btn gray_line small count_mod" ct_id="<?php echo $row['ct_id']?>"><span>적용</span></button>
				<div class="btn_cart">
					<button type="button" class="ico_01 count_delete" ct_id="<?php echo $row['ct_id']?>"><span class="blind">상품삭제</span></button>
					<?php
					$sqlwish = " select count(*) as cnt, ifnull(sum(case when mb_id = '".$member['mb_id']."' then 1 else 0 end),0) as wishis from lt_shop_wish where it_id ='".$row['it_id']."' ";
					$rowwish = sql_fetch($sqlwish);
					echo "<a href=\"javascript:item_wish(document.fitem, '".$row['it_id']."');\" >";
					echo "<button type=\"button\" class=\"pick ico_02 ".(($rowwish['wishis'] != '0')?'on':'')."\" it_id=\"".$row['it_id']."\"><span class=\"blind\">좋아요</span></button>";
					echo "</a>";
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
		$tot_point      += $point;
		$tot_sell_price += $sell_price;
		$tot_sell_rental_price += $sell_rental_price;
		$tot_sell_rental_price_all += $sell_rental_price * (int)$row['ct_item_rental_month'];
	} // for 끝

	if ($i == 0) {
		echo '<div class="order_cont chk_order"><div class="body"><div class="cont"><div class="info"><strong>장바구니에 담긴 상품이 없습니다.</strong></div></div></div></div>';
	} else {
		// 배송비 계산
		$send_cost = get_sendcost($s_cart_id, 0);
	}
	?>
	</div>
</div>

<!-- floating_wrap -->
<div class="floating_wrap">
	<div class="ft_inner">
		<div class="fix_view">
		<?php
		if ($i != 0) {
		?>
		<?php if($od_type == "O") {
			$tot_price = $tot_sell_price + $send_cost; // 총계 = 주문상품금액합계 + 배송비
			if ($tot_price > 0 || $send_cost > 0) { ?>
				<?php if ($tot_point > 0 && $config['cf_use_point']) { ?>
				<div class="cont">
					<p class="txt">적립 예정 금액</p>
					<strong class="price"><?php echo number_format($tot_point); ?> 원</strong>
				</div>
				<?php } ?>

				<?php if ($send_cost > 0) { // 배송비가 0 보다 크다면 (있다면) ?>
				<div class="cont">
					<p class="txt">배송비</p>
					<strong class="price"><?php echo number_format($send_cost); ?> 원</strong>
				</div>
				<?php } ?>

				<?php if ($tot_price > 0) { ?>
				<div class="cont">
					<p class="txt">총 주문 금액<span class="point">(<?php echo $i ?>개)</span></p>
					<strong class="price"><?php echo number_format($tot_price); ?> 원</strong>
				</div>
				<?php } ?>
		<?php }
		} else if($od_type == "R") {
			$tot_price = $tot_sell_rental_price; // 총계 = 주문상품금액합계 + 배송비

			if ($tot_price > 0) { ?>

					<?php if ($tot_sell_rental_price > 0) { ?>
					<div class="cont">
						<p class="txt">총 월리스료</p>
						<strong class="price"><?php echo number_format($tot_sell_rental_price); ?> 원</strong>
					</div>
					<?php } ?>

					<?php if ($tot_point > 0 && $config['cf_use_point']) { ?>
					<div class="cont">
						<p class="txt">적립 예정 금액</p>
						<strong class="price"><?php echo number_format($tot_point); ?> 원</strong>
					</div>
					<?php } ?>

					<?php if ($send_cost > 0) { // 배송비가 0 보다 크다면 (있다면) ?>
					<div class="cont">
						<p class="txt">배송비</p>
						<strong class="price"><?php echo number_format($send_cost); ?> 원</strong>
					</div>
					<?php } ?>
					<?php if ($tot_price > 0) { ?>
					<div class="cont">
						<p class="txt">총 완납 금액<span class="point">(<?php echo $i ?>개)</span></p>
						<strong class="price"><?php echo number_format($tot_sell_rental_price_all + $send_cost); ?> 원</strong>
					</div>
					<?php } ?>

		<?php }
			}
		}
		?>
			<?php if ($i == 0) { ?>
			<div class="btn_group">
				<a href="<?php echo G5_SHOP_URL; ?>/" class="btn big green">쇼핑 계속하기</a>
			</div>
			<?php } else { ?>
			<div class="btn_group two">
				<button type="button" onclick="return form_check('seldelete');" class="btn big black">선택삭제</button>
				<!-- button type="button" onclick="return form_check('alldelete');" class="btn big black">비우기</button -->

				<input type="hidden" name="url" value="<?php echo G5_SHOP_URL; ?>/orderform.php">
				<input type="hidden" name="act" value="">
				<input type="hidden" name="records" value="<?php echo $i; ?>">
				<button type="button" onclick="return form_check('buy');" class="btn big green">바로 주문</button>
			</div>
			<?php } ?>

			<?php if ($naverpay_button_js) { ?>
			<div class="naverpay-cart"><?php echo $naverpay_request_js.$naverpay_button_js; ?></div>
			<?php } ?>
		</div>
	</div>
</div>
<!-- //floating_wrap -->
</form>

<form name="fitem" method="post" >
<input type="hidden" name="it_id" value="">
<input type="hidden" name="od_type" value="<?php $od_type ?>">
<input type="hidden" name="sw_direct">
<input type="hidden" name="url">
</form>
<script>
$(function() {
	var close_btn_idx;

	$(".ct_chk").click(function() {
		$(this).attr("checked",$(this).is(":checked"));

		if(!$(this).is(":checked")) $("input[name=ct_all]").attr("checked", false);
	});

	// 선택사항수정
	$(".mod_options").click(function() {
		var it_id = $(this).attr("id").replace("mod_opt_", "");
		var $this = $(this);
		close_btn_idx = $(".mod_options").index($(this));

		$.post(
			"/mobile/shop/cartoption.php",
			{ it_id: it_id },
			function(data) {
				$("#mod_option_frm").remove();
				$this.after("<div id=\"mod_option_frm\"></div>");
				$("#mod_option_frm").html(data);
				price_calculate();
			}
		);
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
					location.href="<?php echo G5_SHOP_URL.'/cart.php?od_type='.$od_type ?>";
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
					location.href="<?php echo G5_SHOP_URL.'/cart.php?od_type='.$od_type ?>";

					//alert("수정되었습니다.");
					//price_calculate();
				}
			});

		}
	});


});

function fsubmit_check(f) {
	if($("input[name^=ct_chk]:checked").length < 1) {
		alert("구매하실 제품을 하나이상 선택해 주십시오.");
		return false;
	}

	return true;
}

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
			alert("삭제하실 제품을 하나이상 선택해 주십시오.");
			return false;
		}

		if(confirm("선택하신 제품을 장바구니에서 삭제하시겠습니다?")) {
			f.act.value = act;
			f.submit();
		}
	}

	return true;
}

function item_wish(f, it_id)
{
	if($(".pick[it_id='"+it_id+"']").attr("class").indexOf("on") < 0) {
		$.post(
			"<?php echo G5_SHOP_URL; ?>/wishupdate2.php",
			{   it_id : it_id },
			function(data) {
				var responseJSON = JSON.parse(data);
				if(responseJSON.result == "S"){

					if(confirm("관심상품에 저장되었습니다. 보러가시겠습니까?")) location.href='<?php echo G5_SHOP_URL; ?>/wishlist.php';

					$(".pick[it_id='"+it_id+"']").addClass("on");
				}else {
					alert(responseJSON.alert);
					return false;
				}
			}
		);
	} else {
		$.post(
				"<?php echo G5_SHOP_URL; ?>/wishupdate2.php",
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

</script>

<?php
include_once(G5_MOBILE_PATH."/tail.sub.php");
?>
