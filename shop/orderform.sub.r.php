<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

require_once(G5_SHOP_PATH . '/settle_lg2.inc.php');

if (!$is_member) { // 회원이 아닌 경우 로그인화면 이동
	alert("로그인이 필요한 서비스입니다.", G5_BBS_URL . '/login.php?url=' . urlencode(G5_SHOP_URL . '/orderform.php?od_type=' . $od_type), false);
	return;
}

// 결제대행사별 코드 include (스크립트 등)
require_once(G5_SHOP_PATH . '/lg2/orderform.1.php');

?>
<!-- container -->
<div id="container">
	<!-- lnb -->
	<div id="lnb" class="header_bar type2">
		<h1 class="title"><span>리스 계약서 작성</span></h1>
	</div>
	<!-- //lnb -->
	<div class="content mypage sub">
		<form name="forderform" id="forderform" method="post" action="<?= $order_action_url; ?>" autocomplete="off">
			<!-- 주문하시는 분 입력 시작 { -->
			<div class="grid bg_none type2">
				<div class="title_bar none padNone bold">
					계약자 정보
				</div>
				<table class="TBasic2">
					<colgroup>
						<col width="15%" />
						<col width="75%" />
					</colgroup>
					<tr>
						<th class="tleft">생년월일</th>
						<td><?= get_text($member['mb_birth']); ?></td>
					</tr>
					<tr>
						<th class="tleft">이름</th>
						<td><?= get_text($member['mb_name']); ?></td>
					</tr>
					<tr>
						<th class="tleft">연락처</th>
						<td><?= get_text($member['mb_tel']); ?></td>
					</tr>
					<tr>
						<th class="tleft">휴대전화 번호</th>
						<td><?= get_text($member['mb_hp']); ?></td>
					</tr>
					<tr>
						<th class="tleft">주소</th>
						<td>
							(<?= $member['mb_zip1'] . $member['mb_zip2']; ?>)
							<?= get_text($member['mb_addr1']); ?>
							<?= get_text($member['mb_addr2']); ?>
						</td>
					</tr>
				</table>
				<input type="hidden" value="<?php echo get_text($member['od_email']); ?>" id="mb_email" name="mb_email">
				<input type="hidden" name="od_tel" value="<?= get_text($member['mb_tel']); ?>" id="od_tel">
				<input type="hidden" name="od_zip" value="<?= $member['mb_zip1'] . $member['mb_zip2']; ?>" id="od_zip">
				<input type="hidden" name="od_addr1" value="<?= get_text($member['mb_addr1']) ?>" id="od_addr1">
				<input type="hidden" name="od_addr2" value="<?= get_text($member['mb_addr2']) ?>" id="od_addr2">
				<input type="hidden" name="od_addr3" value="<?= get_text($member['mb_addr3']) ?>" id="od_addr3">
				<input type="hidden" name="od_addr_jibeon" value="<?= get_text($member['mb_addr_jibeon']); ?>">
			</div>
			<!-- } 주문하시는 분 입력 끝 -->

			<!-- 받으시는 분 입력 시작 { -->
			<div class="grid bg_none  type2">
				<div class="title_bar none padNone bold" style="float:left;">
					사용자 정보
				</div>
				<span class="chk radio" style="float:right; font-size:12px">
					<input type="checkbox" name="ad_sel_addr" value="same" id="ad_sel_addr_same">
					<label for="ad_sel_addr_same">계약자 정보 동일</label>
				</span>
				<div class="clear"></div>
				<table class="TBasic2">
					<colgroup>
						<col width="15%" />
						<col width="75%" />
					</colgroup>
					<tr>
						<th class="tleft">이름</th>
						<td><input type="text" name="od_b_name" id="od_b_name" class="InputBasic" maxlength="20" required /></td>
					</tr>
					<tr>
						<th class="tleft">연락처</th>
						<td><input type="text" name="od_b_tel" id="od_b_tel" class="InputBasic" maxlength="20" required /></td>
					</tr>
					<tr>
						<th class="tleft">휴대전화 번호</th>
						<td><input type="text" name="od_b_hp" id="od_b_hp" required class="InputBasic" maxlength="20"></td>
					</tr>
					<tr>
						<th class="tleft">E-mail</th>
						<td><input type="text" name="od_email" id="od_email" required class="InputBasic" size="35" maxlength="100" /></td>
					</tr>
					<tr>
						<th class="tleft">주소</th>
						<td>
							<input type="text" name="od_b_zip" id="od_b_zip" required class="InputBasic readonly" size="5" maxlength="6" readonly="" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');" required readonly />
							<button type="button" class="btn small green" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');" style="font-size:12px;">주소검색</button><br />
							<input type="text" name="od_b_addr1" id="od_b_addr1" required class="InputBasicW readonly" readonly="readonly"><br />
							<input type="text" name="od_b_addr2" id="od_b_addr2" class="InputBasicW">
							<input type="hidden" name="od_b_addr3" id="od_b_addr3">
							<input type="hidden" name="od_b_addr_jibeon" value="">
						</td>
					</tr>
					<tr>
						<th class="tleft">배송 메시지</th>
						<td>
							<textarea class="InputBasicA" name="od_memo" id="od_memo" placeholder="배송요청 내용을 한글 20자 이내 입력하세요."></textarea>
						</td>
					</tr>
				</table>
			</div>
			<!-- } 받으시는 분 입력 끝 -->

			<?
			// 결제대행사별 코드 include (결제대행사 정보 필드)
			require_once(G5_SHOP_PATH . '/lg2/orderform.2.php');
			?>

			<!-- 컨텐츠 시작 -->

			<!-- 주문상품 확인 시작 { -->
			<div class="grid bg_none type2">
				<div class="title_bar none padNone bold" style="float:left;">
					계약 내용
				</div>
				<?
				$tot_sell_price = 0;
				$tot_sell_rental_price = 0;
				$od_type = "R";

				$goods = "";
				$goods_count = -1;

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
				a.ct_option,
				b.ca_id,
				b.ca_id2,
				b.ca_id3,
				b.it_notax,
				c.its_free_laundry,
				((a.ct_price + a.io_price) * a.ct_qty) as price,
				((a.ct_rental_price + a.io_price) * a.ct_qty) as rental_price
				from     {$g5['g5_shop_cart_table']} a
				left join {$g5['g5_shop_item_table']} b on ( a.it_id = b.it_id )
				left join lt_shop_item_sub c on (a.its_no = c.its_no)
				where a.od_id = '$s_cart_id'
				and a.od_type = '$od_type'
				and a.ct_select = '1' ";
				//$sql .= " group by a.it_id ";
				$sql .= " order by a.ct_id ";
				$result = sql_query($sql);

				$good_info = '';
				$it_send_cost = 0;
				$it_cp_count = 0;

				$comm_tax_mny = 0; // 과세금액
				$comm_vat_mny = 0; // 부가세
				$comm_free_mny = 0; // 면세금액
				$tot_tax_mny = 0;

				for ($i = 0; $row = sql_fetch_array($result); $i++) {
					if (!$goods) {
						//$goods = addslashes($row[it_name]);
						//$goods = get_text($row[it_name]);
						$goods = preg_replace("/\'|\"|\||\,|\&|\;/", "", $row['it_name']);
					}
					$goods_count++;

					$image = get_it_image($row['it_id'], 150, 150);

					$it_name = '<strong>' . stripslashes($row['it_name']) . '</strong>';

					$price_plus = '';
					if ($row['io_price'] >= 0) $price_plus = '+';

					$it_options = get_text($row['ct_option']) . ' (' . $price_plus . display_price($row['io_price']) . ')' . PHP_EOL;
					//$it_options = print_item_options($row['ct_option'], $s_cart_id);

					$sell_price = $row['price'];
					$sell_rental_price = $row['rental_price'];

					?>
					<br />
					<table class="TBasic2">
						<colgroup>
							<col width="15%" />
							<col width="75%" />
						</colgroup>
						<tr>
							<th class="tleft">계약 제품</th>
							<td><?= $it_name; ?></td>
						</tr>
						<tr>
							<th class="tleft">옵션</th>
							<td><?= $it_options; ?></td>
						</tr>
						<tr>
							<th class="tleft">수량</th>
							<td><?= $row['ct_qty']; ?>개</td>
						</tr>
						<tr>
							<th class="tleft">월 이용료</th>
							<td><?= number_format($sell_rental_price); ?> 원</td>
						</tr>
						<tr>
							<th class="tleft">무료세탁권</th>
							<td>연간 1회</td>
							<!-- <td>년 <?= $row['its_free_laundry'] ?>회</td> -->
						</tr>
						<tr>
							<th class="tleft">총 계약기간</th>
							<td><?= $row['ct_item_rental_month'] ?> 개월</td>
						</tr>
						<tr>
							<th class="tleft">자동이체 수단</th>
							<td>신용카드</td>
						</tr>
						<tr>
							<th class="tleft">판매 일자</th>
							<td>
								<?
									$makedate = date_create(G5_TIME_YMDHIS);
									$makedate = date_format($makedate, "Y년 m월 d일");
									echo $makedate;
									?>
							</td>
						</tr>
						<tr>
							<th class="tleft">판매자</th>
							<td><?= $default['de_admin_company_name'] ?> (<?= $default['de_admin_call_tel'] ?>)</td>
						</tr>
					</table>
					<input type="hidden" name="it_id[<?= $i; ?>]" value="<?= $row['it_id']; ?>">
					<input type="hidden" name="it_name[<?= $i; ?>]" value="<?= get_text($row['it_name']); ?>">
					<input type="hidden" name="it_price[<?= $i; ?>]" value="<?= $sell_rental_price; ?>">
					<? if ($default['de_tax_flag_use']) { ?>
						<input type="hidden" name="it_notax[<?= $i; ?>]" value="<?= $row['it_notax']; ?>">
					<? } ?>
					<input type="hidden" name="cp_id[<?= $i; ?>]" value="">
					<input type="hidden" name="cp_price[<?= $i; ?>]" value="0">
				<?
					$tot_sell_price += $sell_price;
					$tot_sell_rental_price += $sell_rental_price;
				} // for 끝

				if ($i == 0) {
					//echo '<tr><td colspan="7" class="empty_table">장바구니에 담긴 상품이 없습니다.</td></tr>';
					alert('장바구니가 비어 있습니다.', G5_SHOP_URL . '/cart.php');
				} else {
					// 배송비 계산
					$send_cost = get_sendcost($s_cart_id);
				}

				// 복합과세처리
				if ($default['de_tax_flag_use']) {
					$comm_tax_mny = round(($tot_tax_mny + $send_cost) / 1.1);
					$comm_vat_mny = ($tot_tax_mny + $send_cost) - $comm_tax_mny;
				}
				?>
			</div>

			<? if ($goods_count) $goods .= ' 외 ' . $goods_count . '건'; ?>
			<? $tot_price = $tot_sell_rental_price; // 총계 = 주문상품금액합계 + 배송비 
			?>
			<!-- } 주문상품 확인 끝 -->

			<?
			$oc_cnt = $sc_cnt = 0;
			?>
			<? $tot_price = $tot_sell_price + $send_cost; // 총계 = 주문상품금액합계 + 배송비 
			?>
			<input type="hidden" id="od_settle_card" name="od_settle_case" value="신용카드" checked />
			<input type="hidden" name="od_price" value="<?= $tot_sell_rental_price; ?>">
			<input type="hidden" name="org_od_price" value="<?= $tot_sell_rental_price; ?>">
			<input type="hidden" name="od_send_cost" value="<?= $send_cost; ?>">
			<input type="hidden" name="od_send_cost2" value="0">
			<input type="hidden" name="item_coupon" value="0">
			<input type="hidden" name="od_coupon" value="0">
			<input type="hidden" name="od_send_coupon" value="0">
			<input type="hidden" name="od_goods_name" value="<?= $goods; ?>">
			<input type="hidden" name="od_type" value="<?= $od_type ?>" />

			<div class="grid bg_none type2">
				<div class="title_bar none padNone bold">
					고객 확인사항
				</div>
				<div class="gray_box">
					<ul class="number" style="font-size:12px;">
						<li>제품 정보(제품명/수량/월이용료) 및 기능, 색상, 디자인에 대해 이상 없이 확인합니다.</li>
						<li>총 계약기간은 36개월(3년)이고, 의무사용기간은 36개월(3년) 입니다.</li>
						<li>월요금은 인도일 익월부터 정기 출금/승인일에 청구 됩니다.</li>
						<li>계약은 제품 인도/설치 후 성립되며, 계약 후 고객의 임의 해지시 해약금(위약금+운송비+사은품비용+미납/연체비용 등)이 부과됩니다.</li>
						<li>의무사용기간 내 고객요청에 의한 임의 해지 시 위약금이 부과됩니다. 위약금 산정 시, 월요금은 할인되기 전의 기본요금을 기준으로 합니다.
							<ul class="disc">
								<li>1년 이내 해지시: (월 리스료÷30일) X (의무사용일수-실사용일수) X 30%</li>
								<li>1년 이상~2년 이내 해지시: (월 이용료÷30일) X (의무사용일수-실사용일수) X 20%</li>
								<li>2년 이상~3년 이내 해지시: (월 이용료÷30일) X (의무사용일수-실사용일수) X 10%</li>
								<li>운송비: 총 계약기간(3년) 내 고객 요청에 의한 임의 해지시 운송비가 부과됩니다.</li>
								<li>미납/연체비용: 해지 접수 시점에 미납/연체비용이 있을 경우 해약금에 함께 청구 됩니다.</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>

			<div class="grid bg_none  type2">
				<div class="title_bar none padNone bold">
					약관 안내/동의
				</div>
				<div class="list">
					<ul class="type1 terms">
						<li>
							<p class="chk_title">
								<span class="fix">계약서 확인 및 동의(필수)</span>
								<button type="button" class="btn small border" id="btn_auto_billing">전문보기</button>
							</p>
							<div class="floatR">
								<span class="chk radio">
									<input type="radio" id="chk_auto_billing" name="rdo_auto_billing" value="1">
									<label for="chk_auto_billing">동의</label>
								</span>
								<span class="chk radio">
									<input type="radio" id="chk_auto_billing2" name="rdo_auto_billing" value="0">
									<label for="chk_auto_billing2">미동의</label>
								</span>
							</div>
						</li>
						<li>
							<p class="chk_title">
								<span class="fix">마케팅 활용 동의(선택)</span>
								<button type="button" class="btn small border" id="btn_user_thirdparty_privacy">전문보기</button>
							</p>
							<div class="floatR">
								<span class="chk radio">
									<input type="radio" id="chk_user_thirdparty_privacy" name="rdo_user_thirdparty_privacy" value="1">
									<label for="chk_user_thirdparty_privacy">동의</label>
								</span>
								<span class="chk radio">
									<input type="radio" id="chk_user_thirdparty_privacy2" name="rdo_user_thirdparty_privacy" value="0">
									<label for="chk_user_thirdparty_privacy2">미동의</label>
								</span>
							</div>
						</li>
					</ul>
				</div>
			</div>

			<div class="grid bg_none type2">
				<div class="title_bar none padNone bold">
					고지 안내사항
				</div>
				<div class="gray_box" style="font-size:12px;">
					회사는 본 제품의 계약 이행 및 원활한 서비스 제공을 위해 개인정보 취급을 위탁하고 있으며, 위탁업체 및 업무내용에 관한 상세내용은 뒷면의 이용약관 또는 리탠다드 홈페이지(http://www.lifelike.co.kr) 內 개인정보취급방츰을 참조하여 주시기 바랍니다.<br />
					※고객님의 정보조회/수정 또는 동의 철회를 위해서는 본 제품 계약 매장 또는 개인정보관리책임자에게 연락하여 주십시오.
				</div>
			</div>

			<div class="grid bg_none type2">
				<div class="title_bar none padNone bold">
					계약 서명
				</div>
				<table class="TBasic2">
					<colgroup>
						<col width="15%" />
						<col width="75%" />
					</colgroup>
					<tr>
						<th class="tleft">이름</th>
						<td><?= get_text($member['mb_name']); ?><input type="hidden" name="od_name" class="InputBasic" value="<?= get_text($member['mb_name']); ?>" id="od_name" readonly="readonly"></td>
					</tr>
					<tr>
						<th class="tleft">휴대전화 번호</th>
						<td><?= get_text($member['mb_hp']); ?><input type="hidden" name="od_hp" class="InputBasic" value="<?= get_text($member['mb_hp']); ?>" id="od_hp" readonly="readonly"></td>
					</tr>
					<tr>
						<th class="tleft">본인인증</th>
						<td>
							<button type="button" class="btn green small" style="font-size:12px;" id="btn_send_auth_key">인증하기</button>
							<span class="fred"><i class="axi axi-info-outline"></i> 본인인증 후 전자서명이 가능합니다.</span>
						</td>
					</tr>
					<tr id="div_auth" style="display:none;">
						<th class="tleft">전자서명</th>
						<td>
							<div class="inp_wrap">
								<div class="title count9"><label for="join7">휴대전화 번호로 전송된 숫자를 입력해 주세요.</label></div>
								<div class="inp_ele count9 r_btn">
									<div class="input r_txt bg">
										<input type="tel" placeholder="인증번호 입력" id="auth_key" name="auth_key" maxlength="6">
										<span class="time" id="timer">02:59</span>
									</div>
									<button type="button" class="btn small green" id="btn_auth">인증</button>
									<input type="hidden" id="auth_yn">
								</div>
								<div id="div_alert" style="display: none"></div>
							</div>
							<div class="signature_box" id="canvasSimpleDiv"><span class="signature_txt">전자서명란</span></div>
							<div class="title count9">
								<input type="hidden" id="cust_file" name="cust_file">
								<input type="hidden" id="orgCanvasSimple">
								<button type="button" id="clearCanvasSimple" class="btn floatR gray_line small auto" style="font-size:12px;">다시작성</button>
							</div>
						</td>
					</tr>
					<tr>
						<th class="tleft">문의전화</th>
						<td><?= $default['de_admin_call_tel'] ?> (제품/계약내용 및 유지관리 서비스 문의)</td>
					</tr>
				</table>
				<div class="fred" style="font-size:12px;">
					※ 계약자는 제품내용, 고객확인사항 및 이용약관에 대하여 충분한 설명을 듣고 이를 확인하였으며, 계약은 설치 완료 후 성립됨에 동의하는 의미로 아래와 같이 전자서명을 합니다.
				</div>

			</div>
			<?
			// 결제대행사별 코드 include (주문버튼)
			require_once(G5_SHOP_PATH . '/lg2/orderform.3.php');
			?>
		</form>
	</div>
</div>

<!-- popup -->
<section class="popup_container layer" id="popup_container" style="display: none">
	<div class="inner_layer" style="top:10%">
		<div class="content comm sub">
			<!-- 컨텐츠 시작 -->
			<div class="grid cont">
				<div class="title_bar">
					<h1 class="g_title_01" id='popuptitle'><?= $title; ?></h1>
				</div>
			</div>
			<div class="grid terms_wrap">
				<div class="terms_box" id='popupbody1'><?= $config['cf_contract_cancel'] ?></div>
				<div class="terms_box" id='popupbody2' style="display: none;"><?= $config['cf_collection_privacy'] ?></div>
			</div>

			<div class="btn_group bottom none"><button type="button" class="btn big green" id="agree"><span>동의합니다</span></button></div>
			<!-- 컨텐츠 종료 -->
		</div>
		<a class="btn_closed" onclick="$('#popup_container').css('display','none')"><span class="blind">닫기</span></a>
	</div>
</section>
<!-- //popup -->

<script>
	var zipcode = "";
	var canvasDiv;
	var context;
	var canvasWidth = 556;
	var canvasHeight = 159;

	var clickX_simple = new Array();
	var clickY_simple = new Array();
	var clickDrag_simple = new Array();
	var paint_simple;
	var canvas_simple;
	var context_simple;
	var form_action_url = "<?= $order_action_url; ?>";

	$(function() {
		var $cp_btn_el;
		var $cp_row_el;

		$(".cp_btn").click(function() {
			$cp_btn_el = $(this);
			$cp_row_el = $(this).closest("tr");
			$("#cp_frm").remove();
			var it_id = $cp_btn_el.closest("tr").find("input[name^=it_id]").val();

			$.post(
				"./orderitemcoupon.php", {
					it_id: it_id,
					sw_direct: "<?= $sw_direct; ?>"
				},
				function(data) {
					$cp_btn_el.after(data);
				}
			);
		});

		$(document).on("click", ".cp_apply", function() {
			var $el = $(this).closest("tr");
			var cp_id = $el.find("input[name='f_cp_id[]']").val();
			var price = $el.find("input[name='f_cp_prc[]']").val();
			var subj = $el.find("input[name='f_cp_subj[]']").val();
			var sell_price;

			if (parseInt(price) == 0) {
				if (!confirm(subj + "쿠폰의 할인 금액은 " + price + "원입니다.\n쿠폰을 적용하시겠습니까?")) {
					return false;
				}
			}

			// 이미 사용한 쿠폰이 있는지
			var cp_dup = false;
			var cp_dup_idx;
			var $cp_dup_el;
			$("input[name^=cp_id]").each(function(index) {
				var id = $(this).val();

				if (id == cp_id) {
					cp_dup_idx = index;
					cp_dup = true;
					$cp_dup_el = $(this).closest("tr");;

					return false;
				}
			});

			if (cp_dup) {
				var it_name = $("input[name='it_name[" + cp_dup_idx + "]']").val();
				if (!confirm(subj + "쿠폰은 " + it_name + "에 사용되었습니다.\n" + it_name + "의 쿠폰을 취소한 후 적용하시겠습니까?")) {
					return false;
				} else {
					coupon_cancel($cp_dup_el);
					$("#cp_frm").remove();
					$cp_dup_el.find(".cp_btn").text("적용").focus();
					$cp_dup_el.find(".cp_cancel").remove();
				}
			}

			var $s_el = $cp_row_el.find(".total_price");;
			sell_price = parseInt($cp_row_el.find("input[name^=it_price]").val());
			sell_price = sell_price - parseInt(price);
			if (sell_price < 0) {
				alert("쿠폰할인금액이 상품 주문금액보다 크므로 쿠폰을 적용할 수 없습니다.");
				return false;
			}
			$s_el.text(number_format(String(sell_price)));
			$cp_row_el.find("input[name^=cp_id]").val(cp_id);
			$cp_row_el.find("input[name^=cp_price]").val(price);

			calculate_total_price();
			$("#cp_frm").remove();
			$cp_btn_el.text("변경").focus();
			if (!$cp_row_el.find(".cp_cancel").size())
				$cp_btn_el.after("<button type=\"button\" class=\"cp_cancel\">취소</button>");
		});

		$(document).on("click", "#cp_close", function() {
			$("#cp_frm").remove();
			$cp_btn_el.focus();
		});

		$(document).on("click", ".cp_cancel", function() {
			coupon_cancel($(this).closest("tr"));
			calculate_total_price();
			$("#cp_frm").remove();
			$(this).closest("tr").find(".cp_btn").text("적용").focus();
			$(this).remove();
		});

		$("#od_coupon_btn").click(function() {
			$("#od_coupon_frm").remove();
			var $this = $(this);
			var price = parseInt($("input[name=org_od_price]").val()) - parseInt($("input[name=item_coupon]").val());
			if (price <= 0) {
				alert('상품금액이 0원이므로 쿠폰을 사용할 수 없습니다.');
				return false;
			}
			$.post(
				"./ordercoupon.php", {
					price: price
				},
				function(data) {
					$this.after(data);
				}
			);
		});

		$(document).on("click", ".od_cp_apply", function() {
			var $el = $(this).closest("tr");
			var cp_id = $el.find("input[name='o_cp_id[]']").val();
			var price = parseInt($el.find("input[name='o_cp_prc[]']").val());
			var subj = $el.find("input[name='o_cp_subj[]']").val();
			var send_cost = $("input[name=od_send_cost]").val();
			var item_coupon = parseInt($("input[name=item_coupon]").val());
			var od_price = parseInt($("input[name=org_od_price]").val()) - item_coupon;

			if (price == 0) {
				if (!confirm(subj + "쿠폰의 할인 금액은 " + price + "원입니다.\n쿠폰을 적용하시겠습니까?")) {
					return false;
				}
			}

			if (od_price - price <= 0) {
				alert("쿠폰할인금액이 주문금액보다 크므로 쿠폰을 적용할 수 없습니다.");
				return false;
			}

			$("input[name=sc_cp_id]").val("");
			$("#sc_coupon_btn").text("쿠폰적용");
			$("#sc_coupon_cancel").remove();

			$("input[name=od_price]").val(od_price - price);
			$("input[name=od_cp_id]").val(cp_id);
			$("input[name=od_coupon]").val(price);
			$("input[name=od_send_coupon]").val(0);
			$("#od_cp_price").text(number_format(String(price)));
			$("#sc_cp_price").text(0);
			calculate_order_price();
			$("#od_coupon_frm").remove();
			$("#od_coupon_btn").text("변경").focus();
			if (!$("#od_coupon_cancel").size())
				$("#od_coupon_btn").after("<button type=\"button\" id=\"od_coupon_cancel\" class=\"cp_cancel\">취소</button>");
		});

		$(document).on("click", "#od_coupon_close", function() {
			$("#od_coupon_frm").remove();
			$("#od_coupon_btn").focus();
		});

		$(document).on("click", "#od_coupon_cancel", function() {
			var org_price = $("input[name=org_od_price]").val();
			var item_coupon = parseInt($("input[name=item_coupon]").val());
			$("input[name=od_price]").val(org_price - item_coupon);
			$("input[name=sc_cp_id]").val("");
			$("input[name=od_coupon]").val(0);
			$("input[name=od_send_coupon]").val(0);
			$("#od_cp_price").text(0);
			$("#sc_cp_price").text(0);
			calculate_order_price();
			$("#od_coupon_frm").remove();
			$("#od_coupon_btn").text("쿠폰적용").focus();
			$(this).remove();
			$("#sc_coupon_btn").text("쿠폰적용");
			$("#sc_coupon_cancel").remove();
		});

		$("#sc_coupon_btn").click(function() {
			$("#sc_coupon_frm").remove();
			var $this = $(this);
			var price = parseInt($("input[name=od_price]").val());
			var send_cost = parseInt($("input[name=od_send_cost]").val());
			$.post(
				"./ordersendcostcoupon.php", {
					price: price,
					send_cost: send_cost
				},
				function(data) {
					$this.after(data);
				}
			);
		});

		$(document).on("click", ".sc_cp_apply", function() {
			var $el = $(this).closest("tr");
			var cp_id = $el.find("input[name='s_cp_id[]']").val();
			var price = parseInt($el.find("input[name='s_cp_prc[]']").val());
			var subj = $el.find("input[name='s_cp_subj[]']").val();
			var send_cost = parseInt($("input[name=od_send_cost]").val());

			if (parseInt(price) == 0) {
				if (!confirm(subj + "쿠폰의 할인 금액은 " + price + "원입니다.\n쿠폰을 적용하시겠습니까?")) {
					return false;
				}
			}

			$("input[name=sc_cp_id]").val(cp_id);
			$("input[name=od_send_coupon]").val(price);
			$("#sc_cp_price").text(number_format(String(price)));
			calculate_order_price();
			$("#sc_coupon_frm").remove();
			$("#sc_coupon_btn").text("변경").focus();
			if (!$("#sc_coupon_cancel").size())
				$("#sc_coupon_btn").after("<button type=\"button\" id=\"sc_coupon_cancel\" class=\"cp_cancel\">취소</button>");
		});

		$(document).on("click", "#sc_coupon_close", function() {
			$("#sc_coupon_frm").remove();
			$("#sc_coupon_btn").focus();
		});

		$(document).on("click", "#sc_coupon_cancel", function() {
			$("input[name=od_send_coupon]").val(0);
			$("#sc_cp_price").text(0);
			calculate_order_price();
			$("#sc_coupon_frm").remove();
			$("#sc_coupon_btn").text("쿠폰적용").focus();
			$(this).remove();
		});

		$("#od_b_addr2").focus(function() {
			var zip = $("#od_b_zip").val().replace(/[^0-9]/g, "");
			if (zip == "")
				return false;

			var code = String(zip);

			if (zipcode == code)
				return false;

			zipcode = code;
			calculate_sendcost(code);
		});

		$("#od_settle_bank").on("click", function() {
			$("[name=od_deposit_name]").val($("[name=od_name]").val());
			$("#settle_bank").show();
		});

		$("#od_settle_iche,#od_settle_card,#od_settle_vbank,#od_settle_hp,#od_settle_easy_pay,#od_settle_kakaopay").bind("click", function() {
			$("#settle_bank").hide();
		});

		// 배송지선택
		$("input[name=ad_sel_addr]").on("click", function() {
			var addr = $(this).val().split(String.fromCharCode(30));

			if (addr[0] == "same") {
				gumae2baesong();
			} else {
				if (addr[0] == "new") {
					for (i = 0; i < 10; i++) {
						addr[i] = "";
					}
				}

				var f = document.forderform;
				f.od_b_name.value = addr[0];
				f.od_b_tel.value = addr[1];
				f.od_b_hp.value = addr[2];
				f.od_b_zip.value = addr[3] + addr[4];
				f.od_b_addr1.value = addr[5];
				f.od_b_addr2.value = addr[6];
				f.od_b_addr3.value = addr[7];
				f.od_b_addr_jibeon.value = addr[8];
				f.ad_subject.value = addr[9];

				var zip1 = addr[3].replace(/[^0-9]/g, "");
				var zip2 = addr[4].replace(/[^0-9]/g, "");

				var code = String(zip1) + String(zip2);

				if (zipcode != code) {
					calculate_sendcost(code);
				}
			}
		});

		// 배송지목록
		$("#order_address").on("click", function() {
			var url = this.href;
			window.open(url, "win_address", "left=100,top=100,width=800,height=600,scrollbars=1");
			return false;
		});

		//전체 동의
		$("#chk_all_stipulation").on("click", function() {
			var chk = $("#chk_all_stipulation").is(":checked");
			if (chk) {
				$("#chk_auto_billing").prop("checked", chk);
				$("#chk_user_thirdparty_privacy").prop("checked", chk);
			}
		});

		var agreeBtnID = "";

		$('#btn_auto_billing').click(function() {
			$("#popuptitle").text("계약서 확인 및 동의(필수)");
			$("#popupbody1").css("display", "");
			$("#popupbody2").css("display", "none");

			agreeBtnID = "chk_auto_billing";

			$("#popup_container").css("display", "");
		});

		$('#btn_user_thirdparty_privacy').click(function() {
			$("#popuptitle").text("마케팅 활용 동의");
			$("#popupbody1").css("display", "none");
			$("#popupbody2").css("display", "");

			agreeBtnID = "chk_user_thirdparty_privacy";

			$("#popup_container").css("display", "");
		});

		$('#agree').click(function() {
			$('#' + agreeBtnID).prop("checked", true);
			agreeBtnID = "";
			$("#popup_container").css("display", "none");
		});




		var timer = 180;
		$("#btn_send_auth_key").on("click", function() {

			if ($('#od_hp').val() == '') {
				alert("등록된 휴대전화번호가 없습니다. 회원정보에서 휴대전화 번호를 입력바랍니다.");
				return false;
			}

			<? if ($default['de_card_test'] && true) {   // 테스트 결제시 
				?>

				if (!confirm("결제 테스트 모드 : 인증 테스트하시겠습니까?\n취소시 인증완료로 처리됩니다.")) {
					$("#auth_yn").val('Y');
					$('#auth_key').prop("disabled", true);
					$('#btn_auth').prop("disabled", true);
					$('#btn_send_auth_key').prop("disabled", true);
					$('#btn_send_auth_key').text("인증완료");
					$('#div_auth').show();

					return false;
				}

			<? } ?>

			var dest_phone = $('#od_hp').val().replace(/[\-]/g, "");

			$.post(
				"./orderform.sub.rental.sms_sender_auth.php", {
					name: encodeURIComponent($('#od_name').val()),
					auth_phoneNumber: dest_phone
				},
				function(data) {
					if (data.result == 'S') {
						$("#div_alert").html(data.view_text);
						$('#div_auth').show();
						timer = 180;
						var interval = setInterval(function() {
							minutes = parseInt(timer / 60, 10);
							seconds = parseInt(timer % 60, 10);

							minutes = minutes < 10 ? "0" + minutes : minutes;
							seconds = seconds < 10 ? "0" + seconds : seconds;


							$('#timer').text(minutes + ':' + seconds);

							if (--timer < 0) {
								timer = 0;
								clearInterval(interval);
								if ($('#auth_yn').val() != 'Y') {
									alert('인증시간이 만료되었습니다.\n다시 인증해주세요');
								}
							}
						}, 1000);
					} else {
						$("#div_alert").html(data.view_text);
					}
				}
			);
		});

		$('#btn_auth').click(function() {
			if (timer == 0) {
				alert('인증시간이 만료되었습니다.\n다시 인증해주세요');
				return;
			}

			var dest_phone = $('#od_hp').val().replace(/[\-]/g, "");
			$.post(
				"<?= G5_BBS_URL . '/ajax.register_authkey_certify.php'; ?>", {
					auth_key: $('#auth_key').val(),
					auth_phoneNumber: dest_phone
				},
				function(data) {

					if (data.result == 'S') {
						$("#div_alert").html(data.view_text);
						$("#auth_yn").val('Y');
						timer = 0;

						$('#auth_key').prop("disabled", true);
						$('#btn_auth').prop("disabled", true);
						$('#btn_send_auth_key').prop("disabled", true);
						$('#btn_send_auth_key').text("인증완료");

					} else {
						$("#div_alert").html(data.view_text);
					}
				}
			);
		});

		function prepareSimpleCanvas() {
			//alert('test');
			// Create the canvas (Neccessary for IE because it doesn't know what a canvas element is)
			var canvasDiv = document.getElementById('canvasSimpleDiv');
			var canvasTxt = document.querySelector('.signature_txt');
			canvas_simple = document.createElement('canvas');
			canvas_simple.setAttribute('width', canvasWidth);
			canvas_simple.setAttribute('height', canvasHeight);
			canvas_simple.setAttribute('id', 'canvasSimple');
			canvasDiv.appendChild(canvas_simple);
			if (typeof G_vmlCanvasManager != 'undefined') {
				canvas_simple = G_vmlCanvasManager.initElement(canvas_simple);
			}
			context_simple = canvas_simple.getContext("2d");

			document.getElementById('orgCanvasSimple').value = document.getElementById("canvasSimple").toDataURL();

			// Add mouse events
			// ----------------
			$('#canvasSimple').mousedown(function(e) {
				canvasDiv.style.position = 'inherit';
				canvasTxt.style.display = 'none';
				// Mouse down location
				var mouseX = e.pageX - this.offsetLeft;
				var mouseY = e.pageY - this.offsetTop;

				paint_simple = true;
				addClickSimple(mouseX, mouseY, false);
				redrawSimple();
			});

			$('#canvasSimple').mousemove(function(e) {
				if (paint_simple) {
					addClickSimple(e.pageX - this.offsetLeft, e.pageY - this.offsetTop, true);
					redrawSimple();
				}
			});

			$('#canvasSimple').mouseup(function(e) {
				paint_simple = false;
				redrawSimple();
			});

			$('#canvasSimple').mouseleave(function(e) {
				paint_simple = false;
			});

			$('#clearCanvasSimple').mousedown(function(e) {
				clickX_simple = new Array();
				clickY_simple = new Array();
				clickDrag_simple = new Array();
				clearCanvas_simple();
				canvasDiv.style.position = 'relative';
				canvasTxt.style.display = 'block';
			});

			// Add touch event listeners to canvas element
			canvas_simple.addEventListener("touchstart", function(e) {
				canvasDiv.style.position = 'inherit';
				canvasTxt.style.display = 'none';
				// Mouse down location
				var mouseX = (e.changedTouches ? e.changedTouches[0].pageX : e.pageX) - this.offsetLeft,
					mouseY = (e.changedTouches ? e.changedTouches[0].pageY : e.pageY) - this.offsetTop;

				paint_simple = true;
				addClickSimple(mouseX, mouseY, false);
				redrawSimple();
			}, false);
			canvas_simple.addEventListener("touchmove", function(e) {
				canvasDiv.style.position = 'inherit';
				canvasTxt.style.display = 'none';
				var mouseX = (e.changedTouches ? e.changedTouches[0].pageX : e.pageX) - this.offsetLeft,
					mouseY = (e.changedTouches ? e.changedTouches[0].pageY : e.pageY) - this.offsetTop;

				if (paint_simple) {
					addClickSimple(mouseX, mouseY, true);
					redrawSimple();
				}
				e.preventDefault()
			}, false);
			canvas_simple.addEventListener("touchend", function(e) {
				paint_simple = false;
				redrawSimple();
			}, false);
			canvas_simple.addEventListener("touchcancel", function(e) {
				paint_simple = false;
			}, false);
		}

		function addClickSimple(x, y, dragging) {
			clickX_simple.push(x);
			clickY_simple.push(y);
			clickDrag_simple.push(dragging);
		}

		function clearCanvas_simple() {
			context_simple.clearRect(0, 0, canvasWidth, canvasHeight);

		}

		function redrawSimple() {
			clearCanvas_simple();

			var radius = 3;
			context_simple.strokeStyle = "#000000";
			context_simple.lineJoin = "round";
			context_simple.lineWidth = radius;

			for (var i = 0; i < clickX_simple.length; i++) {
				context_simple.beginPath();
				if (clickDrag_simple[i] && i) {
					context_simple.moveTo(clickX_simple[i - 1], clickY_simple[i - 1]);
				} else {
					context_simple.moveTo(clickX_simple[i] - 1, clickY_simple[i]);
				}
				context_simple.lineTo(clickX_simple[i], clickY_simple[i]);
				context_simple.closePath();
				context_simple.stroke();
			}
		}

		prepareSimpleCanvas();
	});

	function coupon_cancel($el) {
		var $dup_sell_el = $el.find(".total_price");
		var $dup_price_el = $el.find("input[name^=cp_price]");
		var org_sell_price = $el.find("input[name^=it_price]").val();

		$dup_sell_el.text(number_format(String(org_sell_price)));
		$dup_price_el.val(0);
		$el.find("input[name^=cp_id]").val("");
	}

	function calculate_total_price() {
		var $it_prc = $("input[name^=it_price]");
		var $cp_prc = $("input[name^=cp_price]");
		var tot_sell_price = sell_price = tot_cp_price = 0;
		var it_price, cp_price, it_notax;
		var tot_mny = comm_tax_mny = comm_vat_mny = comm_free_mny = tax_mny = vat_mny = 0;
		var send_cost = parseInt($("input[name=od_send_cost]").val());

		$it_prc.each(function(index) {
			it_price = parseInt($(this).val());
			cp_price = parseInt($cp_prc.eq(index).val());
			sell_price += it_price;
			tot_cp_price += cp_price;
		});

		tot_sell_price = sell_price - tot_cp_price + send_cost;

		$("#ct_tot_coupon").text(number_format(String(tot_cp_price)));
		$("#ct_tot_price").text(number_format(String(tot_sell_price)));

		$("input[name=good_mny]").val(tot_sell_price);
		$("input[name=od_price]").val(sell_price - tot_cp_price);
		$("input[name=item_coupon]").val(tot_cp_price);
		$("input[name=od_coupon]").val(0);
		$("input[name=od_send_coupon]").val(0);
		<? if ($oc_cnt > 0) { ?>
			$("input[name=od_cp_id]").val("");
			$("#od_cp_price").text(0);
			if ($("#od_coupon_cancel").size()) {
				$("#od_coupon_btn").text("쿠폰적용");
				$("#od_coupon_cancel").remove();
			}
		<? } ?>
		<? if ($sc_cnt > 0) { ?>
			$("input[name=sc_cp_id]").val("");
			$("#sc_cp_price").text(0);
			if ($("#sc_coupon_cancel").size()) {
				$("#sc_coupon_btn").text("쿠폰적용");
				$("#sc_coupon_cancel").remove();
			}
		<? } ?>
		$("input[name=od_temp_point]").val(0);
		<? if ($temp_point > 0 && $is_member) { ?>
			calculate_temp_point();
		<? } ?>
		calculate_order_price();
	}

	function calculate_order_price() {
		var sell_price = parseInt($("input[name=od_price]").val());
		var send_cost = parseInt($("input[name=od_send_cost]").val());
		var send_cost2 = parseInt($("input[name=od_send_cost2]").val());
		var send_coupon = parseInt($("input[name=od_send_coupon]").val());
		var tot_price = sell_price + send_cost + send_cost2 - send_coupon;

		$("input[name=good_mny]").val(tot_price);
		$("#od_tot_price .print_price").text(number_format(String(tot_price)));
		<? if ($temp_point > 0 && $is_member) { ?>
			calculate_temp_point();
		<? } ?>
	}

	function calculate_temp_point() {
		var sell_price = parseInt($("input[name=od_price]").val());
		var mb_point = parseInt(<?= $member['mb_point']; ?>);
		var max_point = parseInt(<?= $default['de_settle_max_point']; ?>);
		var point_unit = parseInt(<?= $default['de_settle_point_unit']; ?>);
		var temp_point = max_point;

		if (temp_point > sell_price)
			temp_point = sell_price;

		if (temp_point > mb_point)
			temp_point = mb_point;

		temp_point = parseInt(temp_point / point_unit) * point_unit;

		$("#use_max_point").text(number_format(String(temp_point)) + "점");
		$("input[name=max_temp_point]").val(temp_point);
	}

	function calculate_sendcost(code) {
		$.post(
			"./ordersendcost.php", {
				zipcode: code
			},
			function(data) {
				$("input[name=od_send_cost2]").val(data);
				$("#od_send_cost2").text(number_format(String(data)));

				zipcode = code;

				calculate_order_price();
			}
		);
	}

	function calculate_tax() {
		var $it_prc = $("input[name^=it_price]");
		var $cp_prc = $("input[name^=cp_price]");
		var sell_price = tot_cp_price = 0;
		var it_price, cp_price, it_notax;
		var tot_mny = comm_free_mny = tax_mny = vat_mny = 0;
		var send_cost = parseInt($("input[name=od_send_cost]").val());
		var send_cost2 = parseInt($("input[name=od_send_cost2]").val());
		var od_coupon = parseInt($("input[name=od_coupon]").val());
		var send_coupon = parseInt($("input[name=od_send_coupon]").val());
		var temp_point = 0;

		$it_prc.each(function(index) {
			it_price = parseInt($(this).val());
			cp_price = parseInt($cp_prc.eq(index).val());
			sell_price += it_price;
			tot_cp_price += cp_price;
			it_notax = $("input[name^=it_notax]").eq(index).val();
			if (it_notax == "1") {
				comm_free_mny += (it_price - cp_price);
			} else {
				tot_mny += (it_price - cp_price);
			}
		});

		if ($("input[name=od_temp_point]").size())
			temp_point = parseInt($("input[name=od_temp_point]").val());

		tot_mny += (send_cost + send_cost2 - od_coupon - send_coupon - temp_point);
		if (tot_mny < 0) {
			comm_free_mny = comm_free_mny + tot_mny;
			tot_mny = 0;
		}

		tax_mny = Math.round(tot_mny / 1.1);
		vat_mny = tot_mny - tax_mny;
		$("input[name=comm_tax_mny]").val(tax_mny);
		$("input[name=comm_vat_mny]").val(vat_mny);
		$("input[name=comm_free_mny]").val(comm_free_mny);
	}

	function forderform_check(f) {
		// 재고체크
		var stock_msg = order_stock_check();
		if (stock_msg != "") {
			alert(stock_msg);
			return false;
		}

		//alert(f.good_mny.value);

		errmsg = "";
		errfld = "";
		var deffld = "";

		check_field(f.od_name, "주문하시는 분 이름을 입력하십시오.");
		check_field(f.od_hp, "주문하시는 분 휴대전화 번호를 입력하십시오.");
		//check_field(f.od_tel, "주문하시는 분 전화번호를 입력하십시오.");
		//check_field(f.od_addr1, "주소검색을 이용하여 주문하시는 분 주소를 입력하십시오.");
		//check_field(f.od_addr2, " 주문하시는 분의 상세주소를 입력하십시오.");
		//check_field(f.od_zip, "");

		clear_field(f.od_email);
		if (f.od_email.value == '' || f.od_email.value.search(/(\S+)@(\S+)\.(\S+)/) == -1)
			error_field(f.od_email, "E-mail을 바르게 입력해 주십시오.");
		/*
		if (typeof(f.od_hope_date) != "undefined")
		{
			clear_field(f.od_hope_date);
			if (!f.od_hope_date.value)
				error_field(f.od_hope_date, "희망배송일을 선택하여 주십시오.");
		}
		*/

		check_field(f.od_b_name, "받으시는 분 이름을 입력하십시오.");
		check_field(f.od_b_hp, "받으시는 분 휴대전화 번호를 입력하십시오.");
		check_field(f.od_b_addr1, "주소검색을 이용하여 받으시는 분 주소를 입력하십시오.");
		//check_field(f.od_b_addr2, "받으시는 분의 상세주소를 입력하십시오.");
		check_field(f.od_b_zip, "");

		// 배송비를 받지 않거나 더 받는 경우 아래식에 + 또는 - 로 대입
		f.od_send_cost.value = parseInt(f.od_send_cost.value);

		if (errmsg) {
			alert(errmsg);
			errfld.focus();
			return false;
		}

		if ($("input[name='rdo_auto_billing']:checked").val() != "1") {
			alert("계약서 확인 및 동의에 동의해 주십시오. 동의하셔야 계약 진행이 가능합니다.");
			f.rdo_auto_billing.focus();
			return false;
		}

		if ($("#auth_yn").val() != "Y") {
			alert("본인 인증이 완료되어야 계약이 가능합니다.");
			f.od_hp.focus();
			return false;
		}

		var canvasSimpleData = document.getElementById("canvasSimple").toDataURL();
		f.cust_file.value = canvasSimpleData;

		if ($("#orgCanvasSimple").val() == $("#cust_file").val()) {
			alert("계약서 서명란에 사인을 입력하셔야 계약이 가능합니다.");
			return false;
		}

		var settle_case = document.getElementsByName("od_settle_case");
		var settle_check = true;
		var settle_method = $("#od_settle_card").val();
		/*for (i = 0; i < settle_case.length; i++) {
			if (settle_case[i].checked) {
				settle_check = true;
				settle_method = settle_case[i].value;
				break;
			}
		}
		if (!settle_check) {
			//alert("결제방식을 선택하십시오.");
			//return false;
		}*/

		var od_price = parseInt(f.od_price.value);
		var send_cost = parseInt(f.od_send_cost.value);
		var send_cost2 = parseInt(f.od_send_cost2.value);
		var send_coupon = parseInt(f.od_send_coupon.value);

		var max_point = 0;
		if (typeof(f.max_temp_point) != "undefined")
			max_point = parseInt(f.max_temp_point.value);

		var temp_point = 0;
		if (typeof(f.od_temp_point) != "undefined") {
			if (f.od_temp_point.value) {
				var point_unit = parseInt(<?= $default['de_settle_point_unit']; ?>);
				temp_point = parseInt(f.od_temp_point.value);

				if (temp_point < 0) {
					alert("포인트를 0 이상 입력하세요.");
					f.od_temp_point.select();
					return false;
				}

				if (temp_point > od_price) {
					alert("상품 주문금액(배송비 제외) 보다 많이 포인트결제할 수 없습니다.");
					f.od_temp_point.select();
					return false;
				}

				if (temp_point > <?= (int) $member['mb_point']; ?>) {
					alert("회원님의 포인트보다 많이 결제할 수 없습니다.");
					f.od_temp_point.select();
					return false;
				}

				if (temp_point > max_point) {
					alert(max_point + "점 이상 결제할 수 없습니다.");
					f.od_temp_point.select();
					return false;
				}

				if (parseInt(parseInt(temp_point / point_unit) * point_unit) != temp_point) {
					alert("포인트를 " + String(point_unit) + "점 단위로 입력하세요.");
					f.od_temp_point.select();
					return false;
				}

				// pg 결제 금액에서 포인트 금액 차감
				if (settle_method != "무통장") {
					f.good_mny.value = od_price + send_cost + send_cost2 - send_coupon - temp_point;
				}
			}
		}

		var tot_price = od_price + send_cost + send_cost2 - send_coupon - temp_point;

		if (document.getElementById("od_settle_iche")) {
			if (document.getElementById("od_settle_iche").checked) {
				if (tot_price < 150) {
					alert("계좌이체는 150원 이상 결제가 가능합니다.");
					return false;
				}
			}
		}

		if (document.getElementById("od_settle_card")) {
			if (document.getElementById("od_settle_card").checked) {
				if (tot_price < 1000) {
					alert("신용카드는 1000원 이상 결제가 가능합니다.");
					return false;
				}
			}
		}

		if (document.getElementById("od_settle_hp")) {
			if (document.getElementById("od_settle_hp").checked) {
				if (tot_price < 350) {
					alert("휴대전화은 350원 이상 결제가 가능합니다.");
					return false;
				}
			}
		}

		<? if ($default['de_tax_flag_use']) { ?>
			calculate_tax();
		<? } ?>

		<? if ($default['de_pg_service'] == 'inicis') { ?>
			if (f.action != form_action_url) {
				f.action = form_action_url;
				f.removeAttribute("target");
				f.removeAttribute("accept-charset");
			}
		<? } ?>

		// 카카오페이 지불
		if (settle_method == "KAKAOPAY") {
			<? if ($default['de_tax_flag_use']) { ?>
				f.SupplyAmt.value = parseInt(f.comm_tax_mny.value) + parseInt(f.comm_free_mny.value);
				f.GoodsVat.value = parseInt(f.comm_vat_mny.value);
			<? } ?>
			getTxnId(f);
			return false;
		}

		var form_order_method = '';

		if (settle_method == "lpay") { //이니시스 L.pay 이면 ( 이니시스의 삼성페이는 모바일에서만 단독실행 가능함 )
			form_order_method = 'samsungpay';
		}

		if (jQuery(f).triggerHandler("form_sumbit_order_" + form_order_method) !== false) {

			// pay_method 설정
			<? if ($default['de_pg_service'] == 'kcp') { ?>
				f.site_cd.value = f.def_site_cd.value;
				f.payco_direct.value = "";
				switch (settle_method) {
					case "계좌이체":
						f.pay_method.value = "010000000000";
						break;
					case "가상계좌":
						f.pay_method.value = "001000000000";
						break;
					case "휴대전화":
						f.pay_method.value = "000010000000";
						break;
					case "신용카드":
						f.pay_method.value = "100000000000";
						break;
					case "간편결제":
						<? if ($default['de_card_test']) { ?>
							f.site_cd.value = "S6729";
						<? } ?>
						f.pay_method.value = "100000000000";
						f.payco_direct.value = "Y";
						break;
					default:
						f.pay_method.value = "무통장";
						break;
				}
			<? } else if ($default['de_pg_service'] == 'lg') { ?>
				f.LGD_EASYPAY_ONLY.value = "";
				if (typeof f.LGD_CUSTOM_USABLEPAY === "undefined") {
					var input = document.createElement("input");
					input.setAttribute("type", "hidden");
					input.setAttribute("name", "LGD_CUSTOM_USABLEPAY");
					input.setAttribute("value", "");
					f.LGD_EASYPAY_ONLY.parentNode.insertBefore(input, f.LGD_EASYPAY_ONLY);
				}

				switch (settle_method) {
					case "계좌이체":
						f.LGD_CUSTOM_FIRSTPAY.value = "SC0030";
						f.LGD_CUSTOM_USABLEPAY.value = "SC0030";
						break;
					case "가상계좌":
						f.LGD_CUSTOM_FIRSTPAY.value = "SC0040";
						f.LGD_CUSTOM_USABLEPAY.value = "SC0040";
						break;
					case "휴대전화":
						f.LGD_CUSTOM_FIRSTPAY.value = "SC0060";
						f.LGD_CUSTOM_USABLEPAY.value = "SC0060";
						break;
					case "신용카드":
						f.LGD_CUSTOM_FIRSTPAY.value = "SC0010";
						f.LGD_CUSTOM_USABLEPAY.value = "SC0010";
						break;
					case "간편결제":
						var elm = f.LGD_CUSTOM_USABLEPAY;
						if (elm.parentNode)
							elm.parentNode.removeChild(elm);
						f.LGD_EASYPAY_ONLY.value = "PAYNOW";
						break;
					default:
						f.LGD_CUSTOM_FIRSTPAY.value = "무통장";
						break;
				}
			<? } else if ($default['de_pg_service'] == 'inicis') { ?>
				switch (settle_method) {
					case "계좌이체":
						f.gopaymethod.value = "DirectBank";
						break;
					case "가상계좌":
						f.gopaymethod.value = "VBank";
						break;
					case "휴대전화":
						f.gopaymethod.value = "HPP";
						break;
					case "신용카드":
						f.gopaymethod.value = "Card";
						f.acceptmethod.value = f.acceptmethod.value.replace(":useescrow", "");
						break;
					case "간편결제":
						f.gopaymethod.value = "Kpay";
						break;
					case "lpay":
						f.gopaymethod.value = "onlylpay";
						f.acceptmethod.value = f.acceptmethod.value + ":cardonly";
						break;
					default:
						f.gopaymethod.value = "무통장";
						break;
				}
			<? } ?>

			// 결제정보설정
			<? if ($default['de_pg_service'] == 'kcp') { ?>
				f.buyr_name.value = f.od_name.value;
				f.buyr_mail.value = f.od_email.value;
				f.buyr_tel1.value = f.od_tel.value;
				f.buyr_tel2.value = f.od_hp.value;
				f.rcvr_name.value = f.od_b_name.value;
				f.rcvr_tel1.value = f.od_b_tel.value;
				f.rcvr_tel2.value = f.od_b_hp.value;
				f.rcvr_mail.value = f.od_email.value;
				f.rcvr_zipx.value = f.od_b_zip.value;
				f.rcvr_add1.value = f.od_b_addr1.value;
				f.rcvr_add2.value = f.od_b_addr2.value;

				if (f.pay_method.value != "무통장") {
					jsf__pay(f);
				} else {
					f.submit();
				}
			<? } ?>
			<? if ($default['de_pg_service'] == 'lg') { ?>
				f.LGD_BUYER.value = f.od_name.value;
				f.LGD_BUYEREMAIL.value = f.od_email.value;
				f.LGD_BUYERPHONE.value = f.od_hp.value;
				f.LGD_AMOUNT.value = f.good_mny.value;
				f.LGD_RECEIVER.value = f.od_b_name.value;
				f.LGD_RECEIVERPHONE.value = f.od_b_hp.value;
				<? if ($default['de_escrow_use']) { ?>
					f.LGD_ESCROW_ZIPCODE.value = f.od_b_zip.value;
					f.LGD_ESCROW_ADDRESS1.value = f.od_b_addr1.value;
					f.LGD_ESCROW_ADDRESS2.value = f.od_b_addr2.value;
					f.LGD_ESCROW_BUYERPHONE.value = f.od_hp.value;
				<? } ?>
				<? if ($default['de_tax_flag_use']) { ?>
					f.LGD_TAXFREEAMOUNT.value = f.comm_free_mny.value;
				<? } ?>

				if (f.LGD_CUSTOM_FIRSTPAY.value != "무통장") {
					launchCrossPlatform(f);
				} else {
					f.submit();
				}
			<? } ?>
			<? if ($default['de_pg_service'] == 'inicis') { ?>
				f.price.value = f.good_mny.value;
				<? if ($default['de_tax_flag_use']) { ?>
					f.tax.value = f.comm_vat_mny.value;
					f.taxfree.value = f.comm_free_mny.value;
				<? } ?>
				f.buyername.value = f.od_name.value;
				f.buyeremail.value = f.od_email.value;
				f.buyertel.value = f.od_hp.value ? f.od_hp.value : f.od_tel.value;
				f.recvname.value = f.od_b_name.value;
				f.recvtel.value = f.od_b_hp.value ? f.od_b_hp.value : f.od_b_tel.value;
				f.recvpostnum.value = f.od_b_zip.value;
				f.recvaddr.value = f.od_b_addr1.value + " " + f.od_b_addr2.value;

				if (f.gopaymethod.value != "무통장") {
					// 주문정보 임시저장
					var order_data = $(f).serialize();
					var save_result = "";
					$.ajax({
						type: "POST",
						data: order_data,
						url: g5_url + "/shop/ajax.orderdatasave.php",
						cache: false,
						async: false,
						success: function(data) {
							save_result = data;
						}
					});

					if (save_result) {
						alert(save_result);
						return false;
					}

					if (!make_signature(f))
						return false;

					paybtn(f);
				} else {
					f.submit();
				}
			<? } ?>
		}

	}

	// 구매자 정보와 동일합니다.
	function gumae2baesong() {
		var f = document.forderform;

		f.od_b_name.value = f.od_name.value;
		f.od_email.value = f.mb_email.value;
		f.od_b_tel.value = f.od_tel.value;
		f.od_b_hp.value = f.od_hp.value;
		f.od_b_zip.value = f.od_zip.value;
		f.od_b_addr1.value = f.od_addr1.value;
		f.od_b_addr2.value = f.od_addr2.value;
		f.od_b_addr3.value = f.od_addr3.value;
		f.od_b_addr_jibeon.value = f.od_addr_jibeon.value;

		calculate_sendcost(String(f.od_b_zip.value));
	}

	<? if ($default['de_hope_date_use']) { ?>
		$(function() {
			$("#od_hope_date").datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: "yy-mm-dd",
				showButtonPanel: true,
				yearRange: "c-99:c+99",
				minDate: "+<?= (int) $default['de_hope_date_after']; ?>d;",
				maxDate: "+<?= (int) $default['de_hope_date_after'] + 6; ?>d;"
			});
		});
	<? } ?>
</script>