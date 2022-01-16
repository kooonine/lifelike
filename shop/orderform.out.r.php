<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

require_once(G5_SHOP_PATH.'/settle_lg.inc.php');
require_once(G5_SHOP_PATH.'/settle_kakaopay.inc.php');

if( $default['de_inicis_lpay_use'] ){   //이니시스 Lpay 사용시
	require_once(G5_SHOP_PATH.'/inicis/lpay_common.php');
}

// 결제대행사별 코드 include (스크립트 등)
require_once(G5_SHOP_PATH.'/lg/orderform.1.php');

if( $default['de_inicis_lpay_use'] ){   //이니시스 L.pay 사용시
	require_once(G5_SHOP_PATH.'/inicis/lpay_form.1.php');
}

if($is_kakaopay_use) {
	require_once(G5_SHOP_PATH.'/kakaopay/orderform.1.php');
}

$sql = "select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
if($is_member && !$is_admin)
	$sql .= " and mb_id = '{$member['mb_id']}' ";
$od = sql_fetch($sql);
if (!$od['od_id'] || (!$is_member && md5($od['od_id'].$od['od_time'].$od['od_ip']) != get_session('ss_orderview_uid'))) {
	alert("조회하실 주문서가 없습니다.", G5_SHOP_URL);
}

set_session('ss_order_id', $od_id);

$rt_month = $od['rt_month'];
$rt_rental_enddate = date_create($od['rt_rental_startdate']);
date_add($rt_rental_enddate, date_interval_create_from_date_string($rt_month.' months'));
$rt_rental_enddate = date_format($rt_rental_enddate,"Y-m-d");

$goods = "리스위약금";
$tot_price = $od['od_penalty'];
?>
<!-- container -->
<div id="container">
	<!-- lnb -->
	<div id="lnb" class="header_bar">
		<h1 class="title"><span>위약금 납부</span></h1>
	</div>
	<!-- //lnb -->
	<div class="content comm sub">
		<form name="forderform" id="forderform" method="post" action="<?=$order_action_url; ?>" autocomplete="off">
			<input type="hidden" name="od_price"    value="<?=$od['od_penalty']; ?>">
			<input type="hidden" name="od_penalty"    value="<?=$od['od_penalty']; ?>">
			<input type="hidden" name="od_send_cost2" value="<?=$od['od_send_cost2']; ?>">
			<input type="hidden" name="od_goods_name" value="리스위약금">

			<input type="hidden" name="od_name" value="<?=get_text($member['mb_name']); ?>" id="od_name" >
			<input type="hidden" name="od_tel" value="<?=get_text($member['mb_tel']); ?>" id="od_tel" >
			<input type="hidden" name="od_hp" value="<?=get_text($member['mb_hp']); ?>" id="od_hp" >
			<input type="hidden" name="od_email" value="<?=get_text($member['mb_email']); ?>" id="od_email" >
			<input type="hidden" name="od_zip" value="<?=$member['mb_zip1'].$member['mb_zip2']; ?>" id="od_zip" >
			<input type="hidden" name="od_addr1" value="<?=get_text($member['mb_addr1']) ?>" id="od_addr1" >
			<input type="hidden" name="od_addr2" value="<?=get_text($member['mb_addr2']) ?>" id="od_addr2" >
			<input type="hidden" name="od_addr3" value="<?=get_text($member['mb_addr3']) ?>" id="od_addr3" >
			<input type="hidden" name="od_addr_jibeon" value="<?=get_text($member['mb_addr_jibeon']); ?>">

			<input type="hidden" name="od_b_name" id="od_b_name" value="<?=$od['od_b_name']?>">
			<input type="hidden" name="od_b_hp" id="od_b_hp" value="<?=$od['od_b_hp']?>">
			<input type="hidden" name="od_b_tel" id="od_b_tel" value="<?=$od['od_b_tel']?>">
			<input type="hidden" name="od_b_zip" id="od_b_zip" value="<?=$od['od_b_zip']?>">
			<input type="hidden" name="od_b_addr1" id="od_b_addr1" value="<?=$od['od_b_addr1']?>">
			<input type="hidden" name="od_b_addr2" id="od_b_addr2" value="<?=$od['od_b_addr2']?>">
			<input type="hidden" name="od_b_addr3" id="od_b_addr3" value="<?=$od['od_b_addr3']?>">
			<input type="hidden" name="od_b_addr_jibeon" id="od_b_addr_jibeon" value="<?=$od['od_b_addr_jibeon']?>">
			<?
			$org_od_id = $od_id;
			$od_id = $od_id."9999";
	// 결제대행사별 코드 include (결제대행사 정보 필드)
			require_once(G5_SHOP_PATH.'/lg/orderform.2.php');

			if($is_kakaopay_use) {
				require_once(G5_SHOP_PATH.'/kakaopay/orderform.2.php');
			}
			?>

			<!-- 컨텐츠 시작 -->
			<div class="grid cont">

				<div class="orderwrap">
					<div class="order_cont">
						<div class="head">
							<span class="category round_green">리스</span>
							<span class="order_number">주문번호 : <strong><?=$org_od_id; ?></strong></span>
						</div>

						<div class="body">
							<?
							$sql = " select it_id, it_name, cp_price, ct_send_cost, it_sc_type
							,ct_id, ct_option, ct_qty, ct_price, ct_point, ct_status, io_type, io_price, ct_rental_price, ct_item_rental_month, ct_keep_month, ct_receipt_price
							from {$g5['g5_shop_cart_table']}
							where od_id = '$org_od_id'
							order by ct_id ";
							$result = sql_query($sql);

							for($i=0; $row=sql_fetch_array($result); $i++) {
								$image = get_it_image($row['it_id'], 150, 150, '', '', $row['it_name']);

								$opt_rental_price = $row['ct_rental_price'] + $row['io_price'];
								$sell_rental_price = $opt_rental_price * $row['ct_qty'];
								?>
								<div class="cont right_cont">
									<div class="photo"><a href="./item.php?it_id=<?=$row['it_id']; ?>" ><?=$image; ?></a></div>
									<div class="info">
										<strong><a href="./item.php?it_id=<?=$row['it_id']; ?>"><?=stripslashes($row['it_name']); ?></a></strong>
										<p><span class="txt">옵션</span>
											<span class="point_black"><strong class="bold"><?=get_text($row['ct_option']); ?></strong>
												/ 수량<strong class="bold"><?=number_format($row['ct_qty']);?>개</strong>
												/ 계약기간<strong class="bold"><?=number_format($row['ct_item_rental_month']);?>개월</strong>
											</span>
										</p>
									</div>
									<div class="pay_item">
										리스 금액<span class="amount"><strong><?=number_format($sell_rental_price); ?> 원</strong></span>
									</div>
								</div>
							<? } ?>

							<div class="order_list bottom_cont">
								<ul>
									<li>
										<span class="item">계약일</span>
										<strong class="result"><?=substr($od['od_time'],0,10) ?></strong>
									</li>
									<li>
										<span class="item">리스료</span>
										<strong class="result">월 <?=number_format($od['rt_rental_price']); ?> 원</strong>
									</li>
									<li>
										<span class="item">횟수정보</span>
										<strong class="result"><span class="point"><?=number_format($od['rt_payment_count']); ?></span>회 / <?=number_format($od['rt_month']); ?>회 (현재 횟수/전체 횟수)</strong>
									</li>
									<li>
										<span class="item">해지사유</span>
										<strong class="result"><?=$od['od_contractout']; ?></strong>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>

				<div class="grid">
					<div class="divide_two box">
						<table class="">
							<colgroup>
								<col width="50%" />
								<col width="50%" />
							</colgroup>
							<tr>
								<td class="bold" style="padding-right:5px; height:35px;">위약금 정보</td>
								<td class="bold" style="padding-left:5px; height:35px;">해지 요청 수거지 정보</td>
							</tr>
							<tr>
								<td valign="top" style="padding-right:5px;">
									<table class="TBasic4">
										<colgroup>
											<col width="25%" />
											<col width="75%" />
										</colgroup>
										<tbody>
											<tr>
												<th class="tleft">리스료</th>
												<td><?=number_format($od['rt_rental_price']); ?> 원</td>
											</tr>
											<tr>
												<th class="tleft">수납 방법</th>
												<td>카드자동이체</td>
											</tr>
											<tr>
												<th class="tleft">카드사</th>
												<td><?=$od['od_bank_account']; ?></td>
											</tr>
											<tr>
												<th class="tleft">수납일</th>
												<td><?=$od['rt_billday']; ?> 일</td>
											</tr>
											<tr>
												<th class="tleft">수납 횟수</th>
												<td><?=$od['rt_payment_count']; ?> 회</td>
											</tr>
											<tr>
												<th class="tleft">수납일 시작일</th>
												<td><?=$od['rt_rental_startdate']; ?></td>
											</tr>
											<tr>
												<th class="tleft">수납일 종료일</th>
												<td><?=$rt_rental_enddate; ?></td>
											</tr>
											<tr>
												<th class="tleft">예상 위약금 금액</th>
												<td><?=number_format($od['od_penalty']) ?> 원</td>
											</tr>
										</tbody>
									</table>
								</td>
								<td valign="top" style="padding-right:5px;">
									<table class="TBasic4">
										<colgroup>
											<col width="25%" />
											<col width="75%" />
										</colgroup>
										<!--
										<tr>
											<th class="tleft">수거요청일</th>
											<td><?=substr($od['od_hope_date'],0,10).' ('.get_yoil($od['od_hope_date']).')' ;?></td>
										</tr>
										-->
										<tr>
											<th class="tleft">이름</th>
											<td><?=get_text($od['od_b_name']); ?></td>
										</tr>
										<tr>
											<th class="tleft">주소</th>
											<td><?=get_text(sprintf("(%s%s)", $od['od_b_zip1'], $od['od_b_zip2']).' '.print_address($od['od_b_addr1'], $od['od_b_addr2'], $od['od_b_addr3'], $od['od_b_addr_jibeon'])); ?></td>
										</tr>
										<tr>
											<th class="tleft">이메일 주소</th>
											<td><?=get_text($od['od_email']); ?></td>
										</tr>
										<tr>
											<th class="tleft">연락처</th>
											<td><?=get_text($od['od_b_tel']); ?></td>
										</tr>
										<tr>
											<th class="tleft">휴대전화 번호</th>
											<td><?=get_text($od['od_b_hp']); ?></td>
										</tr>
										<tr>
											<th class="tleft">요청사항</th>
											<td><?=conv_content($od['od_memo'], 0); ?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</div>

				<div class="grid">
					<div class="title_bar none">
						<h2 class="g_title_01">결제수단 선택</h2>
					</div>
					<div class="border_box">
						<?
						if (!$default['de_card_point'])
							echo '<p id="sod_frm_pt_alert"><strong>무통장입금</strong> 이외의 결제 수단으로 결제하시는 경우 적립금를 적립해드리지 않습니다.</p>';

						$multi_settle = 0;
						$checked = '';

						$escrow_title = "";
						if ($default['de_escrow_use']) {
							$escrow_title = "에스크로 ";
						}

						if ($is_kakaopay_use || $default['de_bank_use'] || $default['de_vbank_use'] || $default['de_iche_use'] || $default['de_card_use'] || $default['de_hp_use'] || $default['de_easy_pay_use'] || is_inicis_simple_pay()) {
							echo '<div class="order_list button_choice inline black"><ul class="onoff">';
						}

		// 신용카드 사용
						if ($default['de_card_use']) {
							$multi_settle++;
							echo '<li><a onclick=\'$("#od_settle_card").click();\'>신용카드</a></li><input type="radio" id="od_settle_card" name="od_settle_case" hidden value="신용카드" '.$checked.'>'.PHP_EOL;
							$checked = '';
						}

		// 카카오페이
						if($is_kakaopay_use) {
							$multi_settle++;
							echo '<li><a onclick=\'$("#od_settle_kakaopay").click();\'>카카오페이</a></li><input type="radio" id="od_settle_kakaopay" name="od_settle_case" hidden value="KAKAOPAY" '.$checked.'>'.PHP_EOL;
							$checked = '';
						}

		// 무통장입금 사용
						if ($default['de_bank_use']) {
							$multi_settle++;
							echo '<li><input type="radio" id="od_settle_bank" name="od_settle_case" value="무통장" '.$checked.'> <label for="od_settle_bank" class="lb_icon  bank_icon">무통장입금</label></li>'.PHP_EOL;
							$checked = '';
						}

		// 가상계좌 사용
						if ($default['de_vbank_use']) {
							$multi_settle++;
							echo '<li><a onclick=\'$("#od_settle_vbank").click();\'>'.$escrow_title.'가상계좌</a></li><input type="radio" id="od_settle_vbank" name="od_settle_case" hidden value="가상계좌" '.$checked.'>'.PHP_EOL;
							$checked = '';
						}

		// 계좌이체 사용
						if ($default['de_iche_use']) {
							$multi_settle++;
							echo '<li><a onclick=\'$("#od_settle_iche").click();\'>'.$escrow_title.'계좌이체</a></li><input type="radio" id="od_settle_iche" name="od_settle_case" hidden value="계좌이체" '.$checked.'>'.PHP_EOL;
							$checked = '';
						}

		// 휴대전화 사용
						if ($default['de_hp_use']) {
							$multi_settle++;
							echo '<li><a onclick=\'$("#od_settle_hp").click();\'>'.$escrow_title.'휴대전화</a></li><input type="radio" id="od_settle_hp" name="od_settle_case" hidden value="휴대전화" '.$checked.'>'.PHP_EOL;
							$checked = '';
						}

		// PG 간편결제
						if($default['de_easy_pay_use']) {
							switch($default['de_pg_service']) {
								case 'lg':
								$pg_easy_pay_name = 'PAYNOW';
								break;
								case 'inicis':
								$pg_easy_pay_name = 'KPAY';
								break;
								default:
								$pg_easy_pay_name = 'PAYCO';
								break;
							}

							$multi_settle++;
							echo '<li><input type="radio" id="od_settle_easy_pay" name="od_settle_case" value="간편결제" '.$checked.'> <label for="od_settle_easy_pay" class="'.$pg_easy_pay_name.' lb_icon">'.$pg_easy_pay_name.'</label></li>'.PHP_EOL;
							$checked = '';
						}

		//이니시스 삼성페이
						if($default['de_samsung_pay_use']) {
							echo '<li><input type="radio" id="od_settle_samsungpay" data-case="samsungpay" name="od_settle_case" value="삼성페이" '.$checked.'> <label for="od_settle_samsungpay" class="samsung_pay lb_icon">삼성페이</label></li>'.PHP_EOL;
							$checked = '';
						}

		//이니시스 Lpay
						if($default['de_inicis_lpay_use']) {
							echo '<li><input type="radio" id="od_settle_inicislpay" data-case="lpay" name="od_settle_case" value="lpay" '.$checked.'> <label for="od_settle_inicislpay" class="inicis_lpay">L.pay</label></li>'.PHP_EOL;
							$checked = '';
						}

						echo '</ul>';

						$temp_point = 0;

						if ($default['de_bank_use']) {
			// 은행계좌를 배열로 만든후
							$str = explode("\n", trim($default['de_bank_account']));
							if (count($str) <= 1)
							{
								$bank_account = '<input type="hidden" name="od_bank_account" value="'.$str[0].'">'.$str[0].PHP_EOL;
							}
							else
							{
								$bank_account = '<select name="od_bank_account" id="od_bank_account">'.PHP_EOL;
								$bank_account .= '<option value="">선택하십시오.</option>';
								for ($i=0; $i<count($str); $i++)
								{
					//$str[$i] = str_replace("\r", "", $str[$i]);
									$str[$i] = trim($str[$i]);
									$bank_account .= '<option value="'.$str[$i].'">'.$str[$i].'</option>'.PHP_EOL;
								}
								$bank_account .= '</select>'.PHP_EOL;
							}
							echo '<div id="settle_bank" style="display:none">';
							echo '<label for="od_bank_account" class="sound_only">입금할 계좌</label>';
							echo $bank_account;
							echo '<br><label for="od_deposit_name">입금자명</label> ';
							echo '<input type="text" name="od_deposit_name" id="od_deposit_name" size="10" maxlength="20">';
							echo '</div>';
						}

						if ($default['de_bank_use'] || $default['de_vbank_use'] || $default['de_iche_use'] || $default['de_card_use'] || $default['de_hp_use'] || $default['de_easy_pay_use'] || is_inicis_simple_pay() ) {
							echo '</div>';
						}

						if ($multi_settle == 0)
							echo '<p>결제할 방법이 없습니다.<br>운영자에게 알려주시면 감사하겠습니다.</p>';
						?>
					</div>

					<div class="inp_wrap">
						<span class="chk check">
							<input type="checkbox" id="chk_user_privacy" name="chk_user_privacy" required="required" value="1">
							<label for="chk_user_privacy">개인정보 수집 • 이용 동의<span>(필수)</span></label>
						</span>
						<a href="<?=G5_URL?>/common/terms_agreement.php?id=chk_user_privacy&type=user_privacy&title=<?=urlencode("개인정보 수집 • 이용 동의")?>" class="btn floatR arrow_r_green" target="_blank">전문보기</a>
					</div>

					<hr class="full_line">

					<div class="page_title">
						<p class="g_title_03">위 주문 내용을 확인하였으며, 결제에 동의 합니다.</p>
					</div>

					<div class="btn_group two" id="display_pay_button" style="display:none;">
						<input type="button" onClick="forderform_check(this.form);" value="결제 후 해지 요청" class="btn big green">
					</div>
					<div id="display_pay_process" style="display:none">
						<img src="<?=G5_URL; ?>/shop/img/loading.gif" alt="">
						<span>주문완료 중입니다. 잠시만 기다려 주십시오.</span>
					</div>

					<script>
						document.getElementById("display_pay_button").style.display = "" ;
					</script>

					<?
		// 결제대행사별 코드 include (주문버튼)
					require_once(G5_SHOP_PATH.'/lg/orderform.3.php');

					if($is_kakaopay_use) {
						require_once(G5_SHOP_PATH.'/kakaopay/orderform.3.php');
					}
					?>

					<?
					if ($default['de_escrow_use']) {
			// 결제대행사별 코드 include (에스크로 안내)
						require_once(G5_SHOP_PATH.'/lg/orderform.4.php');
					}
					?>
				</div>
			</div>
		</form>
	</div>
</div>

<?
if( $default['de_inicis_lpay_use'] ){   //이니시스 L.pay 사용시
	require_once(G5_SHOP_PATH.'/inicis/lpay_order.script.php');
}
?>
<script>
	var zipcode = "";
	var form_action_url = "<?=$order_action_url; ?>";
	$(function() {

	});

	var temp_point = 0;
	function forderform_check(f)
	{
		var settle_case = document.getElementsByName("od_settle_case");
		var settle_check = false;
		var settle_method = "";
		for (i=0; i<settle_case.length; i++)
		{
			if (settle_case[i].checked)
			{
				settle_check = true;
				settle_method = settle_case[i].value;
				break;
			}
		}
		if (!settle_check)
		{
			alert("결제방식을 선택하십시오.");
			return false;
		}

		if(!$("#chk_user_privacy").is(":checked"))
		{
			alert("개인정보 수집 • 이용에 동의 해주십시오.");
			return false;
		}


		var form_order_method = '';

		if( jQuery(f).triggerHandler("form_sumbit_order_"+form_order_method) !== false ) {

		// pay_method 설정
		<? if($default['de_pg_service'] == 'kcp') { ?>
			f.site_cd.value = f.def_site_cd.value;
			f.payco_direct.value = "";
			switch(settle_method)
			{
				case "계좌이체":
				f.pay_method.value   = "010000000000";
				break;
				case "가상계좌":
				f.pay_method.value   = "001000000000";
				break;
				case "휴대전화":
				f.pay_method.value   = "000010000000";
				break;
				case "신용카드":
				f.pay_method.value   = "100000000000";
				break;
				case "간편결제":
				<? if($default['de_card_test']) { ?>
					f.site_cd.value      = "S6729";
				<? } ?>
				f.pay_method.value   = "100000000000";
				f.payco_direct.value = "Y";
				break;
				default:
				f.pay_method.value   = "무통장";
				break;
			}
		<? } else if($default['de_pg_service'] == 'lg') { ?>
			f.LGD_EASYPAY_ONLY.value = "";
			if(typeof f.LGD_CUSTOM_USABLEPAY === "undefined") {
				var input = document.createElement("input");
				input.setAttribute("type", "hidden");
				input.setAttribute("name", "LGD_CUSTOM_USABLEPAY");
				input.setAttribute("value", "");
				f.LGD_EASYPAY_ONLY.parentNode.insertBefore(input, f.LGD_EASYPAY_ONLY);
			}

			switch(settle_method)
			{
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
				if(elm.parentNode)
					elm.parentNode.removeChild(elm);
				f.LGD_EASYPAY_ONLY.value = "PAYNOW";
				break;
				default:
				f.LGD_CUSTOM_FIRSTPAY.value = "무통장";
				break;
			}
		<? }  else if($default['de_pg_service'] == 'inicis') { ?>
			switch(settle_method)
			{
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
				f.acceptmethod.value = f.acceptmethod.value+":cardonly";
				break;
				default:
				f.gopaymethod.value = "무통장";
				break;
			}
		<? } ?>

		// 결제정보설정
		<? if($default['de_pg_service'] == 'kcp') { ?>
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

			if(f.pay_method.value != "무통장") {
				jsf__pay( f );
			} else {
				f.submit();
			}
		<? } ?>
		<? if($default['de_pg_service'] == 'lg') { ?>
			f.LGD_BUYER.value = f.od_name.value;
			f.LGD_BUYEREMAIL.value = f.od_email.value;
			f.LGD_BUYERPHONE.value = f.od_hp.value;
			f.LGD_AMOUNT.value = f.good_mny.value;
			f.LGD_RECEIVER.value = f.od_b_name.value;
			f.LGD_RECEIVERPHONE.value = f.od_b_hp.value;
			<? if($default['de_escrow_use']) { ?>
				f.LGD_ESCROW_ZIPCODE.value = f.od_b_zip.value;
				f.LGD_ESCROW_ADDRESS1.value = f.od_b_addr1.value;
				f.LGD_ESCROW_ADDRESS2.value = f.od_b_addr2.value;
				f.LGD_ESCROW_BUYERPHONE.value = f.od_hp.value;
			<? } ?>
			<? if($default['de_tax_flag_use']) { ?>
				f.LGD_TAXFREEAMOUNT.value = f.comm_free_mny.value;
			<? } ?>

			if(f.LGD_CUSTOM_FIRSTPAY.value != "무통장") {
				launchCrossPlatform(f);
			} else {
				f.submit();
			}
		<? } ?>
		<? if($default['de_pg_service'] == 'inicis') { ?>
			f.price.value       = f.good_mny.value;
			<? if($default['de_tax_flag_use']) { ?>
				f.tax.value         = f.comm_vat_mny.value;
				f.taxfree.value     = f.comm_free_mny.value;
			<? } ?>
			f.buyername.value   = f.od_name.value;
			f.buyeremail.value  = f.od_email.value;
			f.buyertel.value    = f.od_hp.value ? f.od_hp.value : f.od_tel.value;
			f.recvname.value    = f.od_b_name.value;
			f.recvtel.value     = f.od_b_hp.value ? f.od_b_hp.value : f.od_b_tel.value;
			f.recvpostnum.value = f.od_b_zip.value;
			f.recvaddr.value    = f.od_b_addr1.value + " " +f.od_b_addr2.value;

			if(f.gopaymethod.value != "무통장") {
			// 주문정보 임시저장
			var order_data = $(f).serialize();
			var save_result = "";
			$.ajax({
				type: "POST",
				data: order_data,
				url: g5_url+"/shop/ajax.orderdatasave.php",
				cache: false,
				async: false,
				success: function(data) {
					save_result = data;
				}
			});

			if(save_result) {
				alert(save_result);
				return false;
			}

			if(!make_signature(f))
				return false;

			paybtn(f);
		} else {
			f.submit();
		}
	<? } ?>
}
}
</script>
