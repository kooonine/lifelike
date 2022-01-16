<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

require_once(G5_SHOP_PATH . '/settle_' . $default['de_pg_service'] . '.inc.php');
require_once(G5_SHOP_PATH . '/settle_kakaopay.inc.php');

if ($default['de_inicis_lpay_use']) {   //이니시스 Lpay 사용시
	require_once(G5_SHOP_PATH . '/inicis/lpay_common.php');
}

// 결제대행사별 코드 include (스크립트 등)
require_once(G5_SHOP_PATH . '/' . $default['de_pg_service'] . '/orderform.1.php');

if ($default['de_inicis_lpay_use']) {   //이니시스 L.pay 사용시
	require_once(G5_SHOP_PATH . '/inicis/lpay_form.1.php');
}

if ($is_kakaopay_use) {
	require_once(G5_SHOP_PATH . '/kakaopay/orderform.1.php');
}
$multi_company = false;
?>
<?
ob_start();
?>
<?
$tot_point = 0;
$tot_sell_price = 0;
$tot_before_price = 0;

$goods = $goods_it_id = "";
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
b.ca_id,
b.ca_id2,
b.ca_id3,
b.it_notax
from {$g5['g5_shop_cart_table']} a left join {$g5['g5_shop_item_table']} b on ( a.it_id = b.it_id )
where a.od_id = '$s_cart_id'
and a.od_type = '$od_type'
and a.ct_select = '1' ";
$sql .= " group by a.it_id ";
$sql .= " order by a.ct_id ";
$result = sql_query($sql);

$good_info = '';
$it_send_cost = 0;
$it_cp_count = 0;

$comm_tax_mny = 0; // 과세금액
$comm_vat_mny = 0; // 부가세
$comm_free_mny = 0; // 면세금액
$tot_tax_mny = 0;
$ca_id3 = '';

for ($i = 0; $row = sql_fetch_array($result); $i++) {
	if ($i != 0 && $ca_id3 != $row['ca_id3']) $multi_company = true;
	$ca_id3 = $row['ca_id3'];

	// 합계금액 계산
	$sql = "   select SUM((a.ct_price + a.io_price) * a.ct_qty) as price,
                     SUM((b.its_price + a.io_price) * a.ct_qty) as before_price,
                  	SUM(a.ct_point * a.ct_qty) as point,
                  	SUM(a.ct_qty) as qty
	from  lt_shop_cart as a
          left join lt_shop_item_sub as b on a.it_id = b.it_id and a.its_no = b.its_no
	where a.it_id = '{$row['it_id']}'
	and   a.od_id = '$s_cart_id' ";
	$sum = sql_fetch($sql);

	if (!$goods) {
		$goods = preg_replace("/\'|\"|\||\,|\&|\;/", "", $row['it_name']);
		$goods_it_id = $row['it_id'];
	}
	$goods_count++;

	// 에스크로 상품정보
	if ($default['de_escrow_use']) {
		if ($i > 0)
			$good_info .= chr(30);
		$good_info .= "seq=" . ($i + 1) . chr(31);
		$good_info .= "ordr_numb={$od_id}_" . sprintf("%04d", $i) . chr(31);
		$good_info .= "good_name=" . addslashes($row['it_name']) . chr(31);
		$good_info .= "good_cntx=" . $row['ct_qty'] . chr(31);
		$good_info .= "good_amtx=" . $row['ct_price'] . chr(31);
	}

	$image = get_it_image($row['it_id'], 150, 150);

	$it_name = '<strong>' . stripslashes($row['it_name']) . '</strong>';
	$it_options = print_item_options($row['it_id'], $s_cart_id);

	// 복합과세금액
	if ($default['de_tax_flag_use']) {
		if ($row['it_notax']) {
			$comm_free_mny += $sum['price'];
		} else {
			$tot_tax_mny += $sum['price'];
		}
	}

	$point      = $sum['point'];
	$sell_price = $sum['price'];
	$before_price = $sum['before_price'];
	// 쿠폰
	if ($is_member) {
		$cp_button = '';
		$cp_count = 0;

		$sql = " select cp_id
		from {$g5['g5_shop_coupon_table']}
		where mb_id IN ( '{$member['mb_id']}', '전체회원' )
		and cp_start <= '" . G5_TIME_YMD . "'
		and cp_end >= '" . G5_TIME_YMD . "'
		and cp_minimum <= '$sell_price'
		and (
		( cp_method = '0' and cp_target = '{$row['it_id']}' )
		OR
		( cp_method = '1' and ( cp_target IN ( '{$row['ca_id']}', '{$row['ca_id2']}', '{$row['ca_id3']}' ) ) )
	) ";
		$res = sql_query($sql);

		for ($k = 0; $cp = sql_fetch_array($res); $k++) {
			if (is_used_coupon($member['mb_id'], $cp['cp_id']))
				continue;

			$cp_count++;
		}

		if ($cp_count) {
			$cp_button = '<button type="button" class="cp_btn">쿠폰적용</button>';
			$it_cp_count++;
		}
	}
	/*
	// 배송비
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
	}
	*/
?>
	<div class="order_cont">
		<div class="body">
			<div class="cont right_cont">
				<div class="photo"><?= $image; ?></div>
				<div class="info">
					<input type="hidden" name="it_id[<?= $i; ?>]" value="<?= $row['it_id']; ?>">
					<input type="hidden" name="it_name[<?= $i; ?>]" value="<?= get_text($row['it_name']); ?>">
					<input type="hidden" name="it_price[<?= $i; ?>]" value="<?= $sell_price; ?>">
					<input type="hidden" name="cp_id[<?= $i; ?>]" value="">
					<input type="hidden" name="cp_price[<?= $i; ?>]" value="0">
					<? if ($default['de_tax_flag_use']) { ?>
						<input type="hidden" name="it_notax[<?= $i; ?>]" value="<?= $row['it_notax']; ?>">
					<? } ?>
					<?= $it_name; ?>
					<?= $cp_button; ?>

					<p><span class="txt">옵션</span>
						<span class="point_black">
							<?= $it_options; ?>
							수량<strong class="bold"><?= number_format($sum['qty']); ?>개</strong>
						</span>
					</p>
				</div>
				<div class="pay_item">
					주문 금액<span class="amount"><strong><?= number_format($sell_price); ?></strong> 원</span>
				</div>
			</div>
		</div>
	</div>
<?
	$tot_point      += $point;
	$tot_sell_price += $sell_price;
	$tot_before_price += $before_price;
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

<? if ($goods_count) $goods .= ' 외 ' . $goods_count . '건'; ?>
<? $tot_price = $tot_sell_price + $send_cost; // 총계 = 주문상품금액합계 + 배송비 
?>
<!-- } 주문상품 확인 끝 -->

<?
$content = ob_get_contents();
ob_end_clean();
?>
<!-- container -->
<div id="container">
	<? require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/navigation.php" ?>
	<div class="content comm sub">
		<form name="forderform" id="forderform" method="post" action="<?= $order_action_url; ?>" autocomplete="off">
			<input type="hidden" name="od_price" value="<?= $tot_sell_price; ?>">
			<input type="hidden" name="org_od_price" value="<?= $tot_sell_price; ?>">
			<!-- <input type="hidden" name="org_before_price"    value="<?= $tot_before_price; ?>"> -->
			<input type="hidden" name="org_before_price" value="<?= $tot_sell_price; ?>">
			<input type="hidden" name="od_send_cost" value="<?= $send_cost; ?>">
			<input type="hidden" name="od_send_cost2" value="0">
			<input type="hidden" name="item_coupon" value="0">
			<input type="hidden" name="od_coupon" value="0">
			<input type="hidden" name="od_send_coupon" value="0">
			<input type="hidden" name="od_goods_name" value="<?= $goods; ?>">
			<input type="hidden" name="od_type" value="<?= $od_type ?>" />

			<?
			// 결제대행사별 코드 include (결제대행사 정보 필드)
			require_once(G5_SHOP_PATH . '/' . $default['de_pg_service'] . '/orderform.2.php');
			if ($is_kakaopay_use) {
				require_once(G5_SHOP_PATH . '/kakaopay/orderform.2.php');
			}
			?>
			<!-- 컨텐츠 시작 -->
			<!-- 주문상품 확인 시작 { -->
			<div class="grid">
				<div class="title_bar none">
					<h2 class="g_title_01">주문제품</h2>
				</div>
				<div class="orderwrap">
					<?= $content; ?>
				</div>
			</div>
			<!-- 주문하시는 분 입력 시작 { -->
			<div class="grid">
				<div class="divide_two box grid">
					<div class="box">
						<div class="title_bar none">
							<h2 class="g_title_01">주문자 정보<? if (!$is_member) echo "(비회원)"; ?></h2>
						</div>
						<div class="border_box">
							<div class="inp_wrap">
								<div class="title count3"><label>이름</label></div>
								<div class="inp_ele count6">
									<div class="input"><input type="text" name="od_name" value="<?= get_text($member['mb_name']); ?>" id="od_name" required class="frm_input required" maxlength="20" placeholder="이름 입력"></div>
								</div>
								<input type="hidden" name="od_tel" value="<?= get_text($member['mb_tel']) ?>" id="od_tel">
								<input type="hidden" name="od_zip" value="<?= $member['mb_zip1'] . $member['mb_zip2']; ?>" id="od_zip">
								<input type="hidden" name="od_addr1" value="<?= get_text($member['mb_addr1']) ?>" id="od_addr1">
								<input type="hidden" name="od_addr2" value="<?= get_text($member['mb_addr2']) ?>" id="od_addr2">
								<input type="hidden" name="od_addr3" value="<?= get_text($member['mb_addr3']) ?>" id="od_addr3">
								<input type="hidden" name="od_addr_jibeon" value="<?= get_text($member['mb_addr_jibeon']); ?>">
							</div>
							<? if (!$is_member) { // 비회원이면 
							?>
								<!-- <div class="inp_wrap">
									<div class="title count3"><label>비밀번호</label></div>
									<div class="inp_ele count6">
										<span class="frm_info">영,숫자 3~20자 (주문서 조회시 필요)</span>
										<div class="input"><input type="password" name="od_pwd" id="od_pwd" required class="frm_input required" maxlength="20"></div>
									</div>
								</div> -->
							<? } ?>
							<div class="inp_wrap">
								<div class="title count3"><label for="od_hp">휴대전화 번호</label></div>
								<div class="inp_ele count6">
									<div class="input"><input type="text" name="od_hp" value="<?= get_text($member['mb_hp']); ?>" id="od_hp" class="frm_input" maxlength="20" placeholder="휴대전화 번호 입력"></div>
								</div>
							</div>

							<div class="inp_wrap">
								<div class="title count3"><label for="od_email">E-mail</label></div>
								<div class="inp_ele count6">
									<div class="input"><input type="text" name="od_email" value="<?= $member['mb_email']; ?>" id="od_email" required class="frm_input required" size="35" maxlength="100" placeholder="이메일 입력"></div>
								</div>
							</div>

							<? if ($default['de_hope_date_use']) { ?>
								<div class="inp_wrap">
									<div class="title count3"><label for="od_hope_date">희망배송일</label></div>
									<div class="inp_ele count6">
										<div class="input">
											<!-- <select name="od_hope_date" id="od_hope_date">
												<option value="">선택하십시오.</option>
												<?
												for ($i = 0; $i < 7; $i++) {
													$sdate = date("Y-m-d", time() + 86400 * ($default['de_hope_date_after'] + $i));
													echo '<option value="' . $sdate . '">' . $sdate . ' (' . get_yoil($sdate) . ')</option>' . PHP_EOL;
												}
												?>
											</select> -->
											<input type="text" name="od_hope_date" value="" id="od_hope_date" required class="frm_input required" size="11" maxlength="10" readonly="readonly"> 이후로 배송 바랍니다.
										</div>
									</div>
								</div>
							<? } ?>
						</div>
					</div>
					<!-- } 주문하시는 분 입력 끝 -->

					<!-- 받으시는 분 입력 시작 { -->
					<div class="box">
						<div class="title_bar none">
							<h2 class="g_title_01">배송지 정보</h2>
							<span class="chk radio">
								<input type="checkbox" name="ad_sel_addr" value="same" id="ad_sel_addr_same">
								<label for="ad_sel_addr_same">주문자 정보와 동일</label>
							</span>
						</div>
						<?
						if ($is_member) {
							$addr_list = '';
							$sep = chr(30);
							$sql = " select * from {$g5['g5_shop_order_address_table']} where mb_id = '{$member['mb_id']}' order by ad_default desc limit 1 ";
							$row = sql_fetch($sql);

							if (!$row['ad_id']) {
						?>
								<!-- 배송지 없을경우
								<div class="order_list alignC  border_box pad50">
									<p class="info_cmt">등록된 주소지가 없습니다.<br>아래 "신규 배송지 등록" 버튼을 선택 하신 후 배송지를 등록 해 주세요</p>
									<div class="btn_group mt25">
										<a href="<?= G5_SHOP_URL ?>/orderaddress.php" id="order_address1"><button type="button" class="btn big green"><span>신규 배송지 등록</span></button></a>
									</div>
								</div> -->
							<?
								$od_b_name = get_text($member['mb_name']);
								$od_b_hp = get_text($member['mb_hp']);
								$od_b_tel = get_text($member['mb_tel']);
								$od_b_zip = $member['mb_zip1'] . $member['mb_zip2'];
								$od_b_addr1 = get_text($member['mb_addr1']);
								$od_b_addr2 = get_text($member['mb_addr2']);
								$od_b_addr3 = get_text($member['mb_addr3']);
								$od_b_addr_jibeon = get_text($member['mb_addr_jibeon']);
							} else {
								$od_b_name = get_text($row['ad_name']);
								$od_b_hp = $row['ad_hp'];
								$od_b_tel = $row['ad_tel'];
								$od_b_zip = $row['ad_zip1'] . $row['ad_zip2'];
								$od_b_addr1 = $row['ad_addr1'];
								$od_b_addr2 = $row['ad_addr2'];
								$od_b_addr3 = $row['ad_addr3'];
								$od_b_addr_jibeon = $row['ad_jibeon'];
							}
							?>
							<div class="order_list border_box">
								<ul>
									<li>
										<span class="item">배송지</span>
										<strong class="result">
											<span id="spn_ad_subject"><?= get_text($row['ad_subject']); ?></span>
											<a href="<?= G5_SHOP_URL ?>/orderaddress.php" id="order_address"><button class="btn gray_line small"><span>배송지 변경</span></button></a>
											<span class="addr" id="addr">
												<?= '[' . $row['ad_zip1'] . $row['ad_zip2'] . ']' . $row['ad_addr1'] . ' ' . $row['ad_addr2'] ?>
											</span>
											<input type="hidden" name="ad_subject" id="ad_subject" value="<?= get_text($row['ad_subject']) ?>">
											<input type="hidden" name="od_b_name" id="od_b_name" value="<?= $od_b_name ?>">
											<input type="hidden" name="od_b_hp" id="od_b_hp" value="<?= $od_b_hp ?>">
											<input type="hidden" name="od_b_tel" id="od_b_tel" value="<?= $od_b_tel ?>">
											<input type="hidden" name="od_b_zip" id="od_b_zip" value="<?= $od_b_zip ?>">
											<input type="hidden" name="od_b_addr1" id="od_b_addr1" value="<?= $od_b_addr1 ?>">
											<input type="hidden" name="od_b_addr2" id="od_b_addr2" value="<?= $od_b_addr2 ?>">
											<input type="hidden" name="od_b_addr3" id="od_b_addr3" value="<?= $od_b_addr3 ?>">
											<input type="hidden" name="od_b_addr_jibeon" id="od_b_addr_jibeon" value="<?= $od_b_addr_jibeon ?>">
										</strong>
									</li>
									<li>
										<span class="item">받는분</span>
										<strong class="result" id="spn_od_b_name"><?= $od_b_name; ?></strong>
									</li>
									<li>
										<span class="item">연락처</span>
										<strong class="result" id="spn_od_b_tel"><?= $od_b_tel; ?></strong>
									</li>
									<li>
										<span class="item">휴대전화 번호</span>
										<strong class="result" id="spn_od_b_hp"><?= $od_b_hp; ?></strong>
									</li>
									<li>
										<span class="item">배송 메시지</span>
										<strong class="result">
											<div class="input mt10"><input type="text" placeholder="한글 20자 이내 입력" name="od_memo" id="od_memo" title="배송요청 내용" value="" maxlength="20"></div>
										</strong>
									</li>
								</ul>
							</div>
						<? } else { ?>
							<div class="order_list  border_box">
								<ul>
									<li>
										<span class="item">받는분</span>
										<strong class="result">
											<div class="input">
												<input type="text" name="od_b_name" id="od_b_name" required class="frm_input required" maxlength="20">
											</div>
										</strong>
										<input type="hidden" name="ad_subject" id="ad_subject" value="">
									</li>
									<li>
										<span class="item">휴대전화 번호</span>
										<strong class="result">
											<div class="input">
												<input type="text" name="od_b_hp" id="od_b_hp" class="frm_input" maxlength="20">
											</div>
										</strong>
									</li>
									<li>
										<span class="item">연락처</span>
										<strong class="result">
											<div class="input">
												<input type="text" name="od_b_tel" id="od_b_tel" required class="frm_input required" maxlength="20">
											</div>
										</strong>
									</li>
									<li>
										<div class="inp_wrap">
											<div class="title count3"><span class="item">주소</span></div>
											<div class="inp_ele count3">
												<div class="input">
													<input type="text" name="od_b_zip" id="od_b_zip" required class="frm_input required" size="5" maxlength="6">
												</div>
											</div>
											<div class="count3">
												<button type="button" class="btn small green" style="height:40px;" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">주소검색</button>
											</div>
										</div>
									</li>
									<li>
										<span class="item"></span>
										<strong class="result">
											<div class="input">
												<input type="text" name="od_b_addr1" id="od_b_addr1" required class="frm_input frm_address required">
											</div>
										</strong>
									<li>
										<span class="item"></span>
										<strong class="result">
											<div class="input">
												<input type="text" name="od_b_addr2" id="od_b_addr2" class="frm_input frm_address">
												<input type="hidden" name="od_b_addr3" id="od_b_addr3">
												<input type="hidden" name="od_b_addr_jibeon" value="">
											</div>
										</strong>
									</li>
									<li>
										<span class="item">배송 메시지</span>
										<strong class="result">
											<div class="input mt10"><input type="text" placeholder="한글 20자 이내 입력" name="od_memo" id="od_memo" title="배송요청 내용" value="" maxlength="20"></div>
										</strong>
									</li>
								</ul>
							</div>
						<? } ?>
					</div>
					<!-- } 받으시는 분 입력 끝 -->
				</div>

				<?
				$oc_cnt = $sc_cnt = 0;
				if ($is_member) {
					// 주문쿠폰
					$sql = " select cp_id
						from {$g5['g5_shop_coupon_table']}
						where mb_id IN ( '{$member['mb_id']}', '전체회원' )
						and cp_method in ('0', '2')
						and cp_start <= '" . G5_TIME_YMD . "'
						and cp_end >= '" . G5_TIME_YMD . "'
						and cp_minimum <= '$tot_sell_price' ";
					$res = sql_query($sql);

					for ($k = 0; $cp = sql_fetch_array($res); $k++) {
						if (is_used_coupon($member['mb_id'], $cp['cp_id']))
							continue;

						$oc_cnt++;
					}

					if ($send_cost > 0) {
						// 배송비쿠폰
						$sql = " select cp_id
							from {$g5['g5_shop_coupon_table']}
							where mb_id IN ( '{$member['mb_id']}', '전체회원' )
							and cp_method = '3'
							and cp_start <= '" . G5_TIME_YMD . "'
							and cp_end >= '" . G5_TIME_YMD . "'
							and cp_minimum <= '$tot_sell_price' ";
						$res = sql_query($sql);

						for ($k = 0; $cp = sql_fetch_array($res); $k++) {
							if (is_used_coupon($member['mb_id'], $cp['cp_id']))
								continue;

							$sc_cnt++;
						}
					}
				}
				?>
				<? $tot_price = $tot_sell_price + $send_cost; // 총계 = 주문상품금액합계 + 배송비 
				?>

				<div class="title_bar none">
					<h2 class="g_title_01">결제 상세 정보</h2>
				</div>
				<div class="grid">
					<div class="order_title">
						<span class="item">총 결제 금액</span>
						<strong class="result">
							<em class="big" id="od_tot_price"><?= number_format($tot_price); ?></em><em class="big"> 원</em>
						</strong>
					</div>
					<div class="divide_three box stick">
						<?php if ($is_member && !$multi_company && $oc_cnt > 0) { ?>
							<div class="box">
								<div class="order_title white">
									<span class="item">쿠폰</span>
									<strong class="result">
										<input type="hidden" name="od_cp_id" value="">
										<? if (!$multi_company) { ?><button type="button" id="od_coupon_btn" class="btn small gray round">조회</button><? } ?>
									</strong>
								</div>
								<div class="order_list result_right">
									<ul>
										<li>
											<span class="item">쿠폰 금액</span>
											<strong class="result">
												<em class=""><span id="od_cp_price">0</span> 원</em>
											</strong>
										</li>
									</ul>
								</div>
							</div>
							<?
						}

						$temp_point = 0;
						// 회원이면서 적립금사용이면
						if ($is_member && $config['cf_use_point'] && !$multi_company) {
							// 적립금 결제 사용 적립금보다 회원의 적립금가 크다면
							if ($member['mb_point'] >= $default['de_settle_min_point']) {
								$temp_point = (int) $default['de_settle_max_point'];

								if ($temp_point > (int) $tot_sell_price) {
									$temp_point = (int) $tot_sell_price;
								}

								if ($temp_point > (int) $member['mb_point']) {
									$temp_point = (int) $member['mb_point'];
								}

								$point_unit = (int) $default['de_settle_point_unit'];
								$temp_point = (int) ((int) ($temp_point / $point_unit) * $point_unit);
							?>
								<div class="box">
									<div class="order_title white">
										<span class="item">적립금</span>
										<strong class="result">
											<button type="button" class="btn small gray round" id="btnUsePoint">사용</button>
										</strong>
									</div>
									<div class="order_list result_right">
										<ul>
											<li>
												<span class="item">적립금 금액</span>
												<strong class="result">
													<em class=""><?= display_point($member['mb_point']) ?></em>
												</strong>
											</li>
											<li>
												<span class="item"></span>
												<strong class="result">
													<div class="input alignR "><input type="text" id="use_temp_point" name="use_temp_point" value="0" size="10" min="<?= $point_unit ?>" max="<?= $temp_point ?>"></div>
												</strong>
												<input type="hidden" name="max_temp_point" value="<?= $temp_point ?>">
												<input type="hidden" name="od_temp_point" value="0">
											</li>
										</ul>
									</div>
								</div>
							<? } else { ?>

								<div class="box">
									<div class="order_title white">
										<span class="item">적립금</span>
									</div>
									<div class="order_list result_right">
										<ul>
											<li>
												<span class="item">적립금 금액</span>
												<strong class="result">
													<em class=""><?= display_point($member['mb_point']) ?></em>
												</strong>
											</li>
											<li>
												<span>* <?= number_format($default['de_settle_min_point']) ?>원 이상부터 사용하실 수 있습니다.</span>
												<input type="hidden" name="max_temp_point" value="<?= $temp_point ?>">
												<input type="hidden" name="od_temp_point" value="0">
											</li>
										</ul>
									</div>
								</div>
						<? }
						} ?>
						<div class="box">
							<div class="order_title white">
								<span class="item">결제 내역</span>
								<strong class="result">
								</strong>
							</div>
							<div class="order_list result_right">
								<ul>
									<li class="sod_bsk_sell">
										<span class="item">주문 금액</span>
										<strong class="result bold"><?= number_format($tot_sell_price); ?> 원</strong>
									</li>
									<li class="sod_bsk_dvr">
										<span class="item">배송비</span>
										<strong class="result bold" id="od_send_cost2"><?= number_format($send_cost); ?> 원</strong>
									</li>

									<? if ($is_member) { ?>
										<li class="sod_bsk_coupon">
											<span class="item">쿠폰 사용</span>
											<strong class="result bold">- <span id="od_coupon_cost">0</span> 원</strong>
										</li>
										<? if ($config['cf_use_point']) : ?>
											<li class="sod_bsk_point">
												<span class="item">적립금 사용</span>
												<strong class="result bold">- <span id="od_point_cost">0</span> 원</strong>
											</li>
										<? endif ?>
									<? } ?>

								</ul>
							</div>
						</div>
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
							echo '<li><a onclick=\'$("#od_settle_card").click();\'>신용카드</a></li><input type="radio" id="od_settle_card" name="od_settle_case" hidden value="신용카드" ' . $checked . '>' . PHP_EOL;
							$checked = '';
						}

						// 카카오페이
						if ($is_kakaopay_use) {
							$multi_settle++;
							echo '<li><a onclick=\'$("#od_settle_kakaopay").click();\'>카카오페이</a></li><input type="radio" id="od_settle_kakaopay" name="od_settle_case" hidden value="KAKAOPAY" ' . $checked . '>' . PHP_EOL;
							$checked = '';
						}

						// 무통장입금 사용
						if ($default['de_bank_use']) {
							$multi_settle++;
							echo '<li><input type="radio" id="od_settle_bank" name="od_settle_case" value="무통장" ' . $checked . '> <label for="od_settle_bank" class="lb_icon  bank_icon">무통장입금</label></li>' . PHP_EOL;
							$checked = '';
						}

						// 가상계좌 사용
						if ($default['de_vbank_use']) {
							$multi_settle++;
							echo '<li><a onclick=\'$("#od_settle_vbank").click();\'>' . $escrow_title . '가상계좌</a></li><input type="radio" id="od_settle_vbank" name="od_settle_case" hidden value="가상계좌" ' . $checked . '>' . PHP_EOL;
							$checked = '';
						}

						// 계좌이체 사용
						if ($default['de_iche_use']) {
							$multi_settle++;
							echo '<li><a onclick=\'$("#od_settle_iche").click();\'>' . $escrow_title . '계좌이체</a></li><input type="radio" id="od_settle_iche" name="od_settle_case" hidden value="계좌이체" ' . $checked . '>' . PHP_EOL;
							$checked = '';
						}

						// 휴대전화 사용
						if ($default['de_hp_use']) {
							$multi_settle++;
							echo '<li><a onclick=\'$("#od_settle_hp").click();\'>' . $escrow_title . '휴대전화</a></li><input type="radio" id="od_settle_hp" name="od_settle_case" hidden value="휴대전화" ' . $checked . '>' . PHP_EOL;
							$checked = '';
						}

						// PG 간편결제
						if ($default['de_easy_pay_use']) {
							switch ($default['de_pg_service']) {
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
							echo '<li><input type="radio" id="od_settle_easy_pay" name="od_settle_case" value="간편결제" ' . $checked . '> <label for="od_settle_easy_pay" class="' . $pg_easy_pay_name . ' lb_icon">' . $pg_easy_pay_name . '</label></li>' . PHP_EOL;
							$checked = '';
						}

						//이니시스 삼성페이
						if ($default['de_samsung_pay_use']) {
							echo '<li><input type="radio" id="od_settle_samsungpay" data-case="samsungpay" name="od_settle_case" value="삼성페이" ' . $checked . '> <label for="od_settle_samsungpay" class="samsung_pay lb_icon">삼성페이</label></li>' . PHP_EOL;
							$checked = '';
						}

						//이니시스 Lpay
						if ($default['de_inicis_lpay_use']) {
							echo '<li><input type="radio" id="od_settle_inicislpay" data-case="lpay" name="od_settle_case" value="lpay" ' . $checked . '> <label for="od_settle_inicislpay" class="inicis_lpay">L.pay</label></li>' . PHP_EOL;
							$checked = '';
						}

						echo '</ul>';

						$temp_point = 0;

						if ($default['de_bank_use']) {
							// 은행계좌를 배열로 만든후
							$str = explode("\n", trim($default['de_bank_account']));
							if (count($str) <= 1) {
								$bank_account = '<input type="hidden" name="od_bank_account" value="' . $str[0] . '">' . $str[0] . PHP_EOL;
							} else {
								$bank_account = '<select name="od_bank_account" id="od_bank_account">' . PHP_EOL;
								$bank_account .= '<option value="">선택하십시오.</option>';
								for ($i = 0; $i < count($str); $i++) {
									//$str[$i] = str_replace("\r", "", $str[$i]);
									$str[$i] = trim($str[$i]);
									$bank_account .= '<option value="' . $str[$i] . '">' . $str[$i] . '</option>' . PHP_EOL;
								}
								$bank_account .= '</select>' . PHP_EOL;
							}
							echo '<div id="settle_bank" style="display:none">';
							echo '<label for="od_bank_account" class="sound_only">입금할 계좌</label>';
							echo $bank_account;
							echo '<br><label for="od_deposit_name">입금자명</label> ';
							echo '<input type="text" name="od_deposit_name" id="od_deposit_name" size="10" maxlength="20">';
							echo '</div>';
						}

						if ($default['de_bank_use'] || $default['de_vbank_use'] || $default['de_iche_use'] || $default['de_card_use'] || $default['de_hp_use'] || $default['de_easy_pay_use'] || is_inicis_simple_pay()) {
							echo '</div>';
						}

						if ($multi_settle == 0)
							echo '<p>결제할 방법이 없습니다.<br>운영자에게 알려주시면 감사하겠습니다.</p>';
						?>
					</div>

					<div class="inp_wrap">
						<span class="chk check">
							<input type="checkbox" id="chk_user_privacy" name="chk_user_privacy" required="required">
							<label for="chk_user_privacy">개인정보 수집 • 이용 동의<span>(필수)</span></label>
						</span>
						<button type="button" class="btn floatR arrow_r_green" id="btn_user_privacy">전문보기</button>
					</div>

					<hr class="full_line">

					<div class="page_title">
						<p class="g_title_03">위 주문 내용을 확인하였으며, 결제에 동의 합니다.</p>
					</div>


					<?
					// 결제대행사별 코드 include (주문버튼)
					require_once(G5_SHOP_PATH . '/' . $default['de_pg_service'] . '/orderform.3.php');

					if ($is_kakaopay_use) {
						require_once(G5_SHOP_PATH . '/kakaopay/orderform.3.php');
					}
					?>

					<?
					if ($default['de_escrow_use']) {
						// 결제대행사별 코드 include (에스크로 안내)
						require_once(G5_SHOP_PATH . '/' . $default['de_pg_service'] . '/orderform.4.php');
					}
					?>
				</div>

			</div>

		</form>
	</div>
</div>
<?
if ($default['de_inicis_lpay_use']) {   //이니시스 L.pay 사용시
	require_once(G5_SHOP_PATH . '/inicis/lpay_order.script.php');
}
?>

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
				<div class="terms_box" id='popupbody1'><?= $config['cf_user_privacy'] ?></div>
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
	var form_action_url = "<?= $order_action_url; ?>";

	$(function() {

		$('#btn_user_privacy').click(function() {
			$("#popuptitle").text("개인정보 수집 • 이용 동의");
			$("#popup_container").css("display", "");
		});

		$('#agree').click(function() {
			$('#chk_user_privacy').prop("checked", true);
			$("#popup_container").css("display", "none");
		});

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
			var $forderform = $(this).closest("form");

			var price = parseInt($("input[name=org_od_price]").val()) - parseInt($("input[name=item_coupon]").val());
			var before_price = parseInt($("input[name=org_before_price]").val()) - parseInt($("input[name=item_coupon]").val());

			if (price <= 0) {
				alert('상품금액이 0원이므로 쿠폰을 사용할 수 없습니다.');
				return false;
			}
			$.post(
				"./ordercoupon.php", {
					price: price,
					before_price: before_price
				},
				function(data) {
					// $("#container").before(data);
				}
			);
		});

		$(document).on("click", ".od_cp_apply", function() {

			if ($("input[name='chk_cp']:checked").length == 0) {

				$("#od_coupon_frm").remove();
				$("#od_coupon_btn").focus();
				return;
			}

			var $el = $("input[name='chk_cp']:checked").closest("li");

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
			$("#sc_coupon_btn").text("조회");
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
				$("#od_coupon_btn").after("<button type=\"button\" id=\"od_coupon_cancel\" class=\"btn small gray round cp_cancel\">취소</button>");
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
			$("#od_coupon_btn").text("조회").focus();
			$(this).remove();
			$("#sc_coupon_btn").text("조회");
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
				$("#sc_coupon_btn").after("<button type=\"button\" id=\"sc_coupon_cancel\" class=\"btn small gray round cp_cancel\">취소</button>");
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
			//calculate_sendcost(code);
		});

		$("#od_settle_bank").on("click", function() {
			$("[name=od_deposit_name]").val($("[name=od_name]").val());
			$("#settle_bank").show();
		});

		$("#od_settle_iche,#od_settle_card,#od_settle_vbank,#od_settle_hp,#od_settle_easy_pay,#od_settle_kakaopay").bind("click", function() {
			$("#settle_bank").hide();

			$("input[name='submitChecked']").val($("#od_tot_price").text() + "원 결제");
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
					//calculate_sendcost(code);
				}

				ad_subject_change();
			}
		});

		// 배송지목록
		$("#order_address, #order_address1").on("click", function() {
			var url = this.href;
			window.open(url, "win_address", "left=100,top=100,width=800,height=600,scrollbars=1");
			return false;
		});

		$("#btnUsePointAll").on("click", function() {
			var f = document.forderform;

			var max_point = parseInt(f.max_temp_point.value);
			f.od_temp_point.value = f.use_temp_point.value = max_point;

			payment_check(f);
		});

		$("#btnUsePoint").on("click", function() {

			var f = document.forderform;

			var od_price = parseInt(f.od_price.value);
			var max_point = parseInt(f.max_temp_point.value);

			if (f.use_temp_point.value) {
				var point_unit = parseInt(<?= $default['de_settle_point_unit']; ?>);
				var temp_point = parseInt(f.use_temp_point.value);

				if (temp_point < 0) {
					alert("적립금를 0 이상 입력하세요.");
					f.use_temp_point.select();
					return false;
				}

				if (temp_point > od_price) {
					alert("상품 주문금액(배송비 제외) 보다 많이 적립금결제할 수 없습니다.");
					f.use_temp_point.select();
					return false;
				}

				if (temp_point > <?= (int) $member['mb_point']; ?>) {
					alert("회원님의 적립금보다 많이 결제할 수 없습니다.");
					f.use_temp_point.select();
					return false;
				}

				if (temp_point > max_point) {
					alert(max_point + "원 이상 결제할 수 없습니다.");
					f.use_temp_point.select();
					return false;
				}

				if (parseInt(parseInt(temp_point / point_unit) * point_unit) != temp_point) {
					alert("적립금를 " + String(point_unit) + "원 단위로 입력하세요.");
					f.use_temp_point.select();
					return false;
				}
			}
			f.od_temp_point.value = f.use_temp_point.value;

			payment_check(f);
		});

	});

	function ad_subject_change() {
		$("#addr").text("[" + $("#od_b_zip").val() + "]" + $("#od_b_addr1").val() + " " + $("#od_b_addr2").val());
		$("#spn_od_b_name").text($("#od_b_name").val());
		$("#spn_od_b_tel").text($("#od_b_tel").val());
		$("#spn_od_b_hp").text($("#od_b_hp").val());
		$("#spn_ad_subject").text($("#od_b_name").val());
	}

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
		var tot_price = sell_price + send_cost + send_cost2 - send_coupon - temp_point;

		$("input[name=good_mny]").val(tot_price);
		$("#od_tot_price .print_price").text(number_format(String(tot_price)));
		<? if ($temp_point > 0 && $is_member) { ?>
			calculate_temp_point();
		<? } ?>

		$("#od_coupon_cost").text(number_format(String(parseInt($("input[name=od_coupon]").val()))));
		$("#od_point_cost").text(number_format(String(temp_point)));
		$("#od_tot_price").text(number_format(String(tot_price)));
		$("input[name='submitChecked']").val(number_format(String(tot_price)) + "원 결제");
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

		$("#use_max_point").text(number_format(String(temp_point)) + "원");
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

	var temp_point = 0;

	function forderform_check(f) {
		// 재고체크
		var stock_msg = order_stock_check();
		if (stock_msg != "") {
			alert(stock_msg);
			return false;
		}

		errmsg = "";
		errfld = "";
		var deffld = "";

		check_field(f.od_name, "주문하시는 분 이름을 입력하십시오.");
		// if (typeof(f.od_pwd) != 'undefined')
		// {
		//     clear_field(f.od_pwd);
		//     if( (f.od_pwd.value.length<3) || (f.od_pwd.value.search(/([^A-Za-z0-9]+)/)!=-1) )
		//         error_field(f.od_pwd, "회원이 아니신 경우 주문서 조회시 필요한 비밀번호를 3자리 이상 입력해 주십시오.");
		// }
		check_field(f.od_hp, "주문하시는 분 휴대전화 번호를 입력하십시오.");
		//check_field(f.od_tel, "주문하시는 분 전화번호를 입력하십시오.");
		//check_field(f.od_addr1, "주소검색을 이용하여 주문하시는 분 주소를 입력하십시오.");
		//check_field(f.od_addr2, " 주문하시는 분의 상세주소를 입력하십시오.");
		//check_field(f.od_zip, "");

		clear_field(f.od_email);
		if (f.od_email.value == '' || f.od_email.value.search(/(\S+)@(\S+)\.(\S+)/) == -1)
			error_field(f.od_email, "E-mail을 바르게 입력해 주십시오.");

		if (typeof(f.od_hope_date) != "undefined") {
			clear_field(f.od_hope_date);
			if (!f.od_hope_date.value)
				error_field(f.od_hope_date, "희망배송일을 선택하여 주십시오.");
		}

		check_field(f.od_b_name, "받으시는 분 이름을 입력하십시오.");
		check_field(f.od_b_hp, "받으시는 분 휴대전화 번호를 입력하십시오.");
		check_field(f.od_b_addr1, "주소검색을 이용하여 받으시는 분 주소를 입력하십시오.");
		//check_field(f.od_b_addr2, "받으시는 분의 상세주소를 입력하십시오.");
		check_field(f.od_b_zip, "");

		var od_settle_bank = document.getElementById("od_settle_bank");
		if (od_settle_bank) {
			if (od_settle_bank.checked) {
				check_field(f.od_bank_account, "계좌번호를 선택하세요.");
				check_field(f.od_deposit_name, "입금자명을 입력하세요.");
			}
		}

		// 배송비를 받지 않거나 더 받는 경우 아래식에 + 또는 - 로 대입
		f.od_send_cost.value = parseInt(f.od_send_cost.value);

		if (errmsg) {
			alert(errmsg);
			errfld.focus();
			return false;
		}

		var settle_case = document.getElementsByName("od_settle_case");
		var settle_check = false;
		var settle_method = "";
		for (i = 0; i < settle_case.length; i++) {
			if (settle_case[i].checked) {
				settle_check = true;
				settle_method = settle_case[i].value;
				break;
			}
		}
		if (!settle_check) {
			alert("결제방식을 선택하십시오.");
			return false;
		}

		if (!$("#chk_user_privacy").is(":checked")) {
			alert("개인정보 수집 • 이용에 동의하셔야 구매 가능합니다.");
			return false;
		}

		var od_price = parseInt(f.od_price.value);
		var send_cost = parseInt(f.od_send_cost.value);
		var send_cost2 = parseInt(f.od_send_cost2.value);
		var send_coupon = parseInt(f.od_send_coupon.value);

		var max_point = 0;
		if (typeof(f.max_temp_point) != "undefined")
			max_point = parseInt(f.max_temp_point.value);

		if (typeof(f.od_temp_point) != "undefined") {
			if (f.od_temp_point.value) {
				var point_unit = parseInt(<?= $default['de_settle_point_unit']; ?>);
				temp_point = parseInt(f.od_temp_point.value);

				if (temp_point < 0) {
					alert("적립금를 0 이상 입력하세요.");
					f.od_temp_point.select();
					return false;
				}

				if (temp_point > od_price) {
					alert("상품 주문금액(배송비 제외) 보다 많이 적립금결제할 수 없습니다.");
					f.od_temp_point.select();
					return false;
				}

				if (temp_point > <?= (int) $member['mb_point']; ?>) {
					alert("회원님의 적립금보다 많이 결제할 수 없습니다.");
					f.od_temp_point.select();
					return false;
				}

				if (temp_point > max_point) {
					alert(max_point + "점 이상 결제할 수 없습니다.");
					f.od_temp_point.select();
					return false;
				}

				if (parseInt(parseInt(temp_point / point_unit) * point_unit) != temp_point) {
					alert("적립금를 " + String(point_unit) + "점 단위로 입력하세요.");
					f.od_temp_point.select();
					return false;
				}

				// pg 결제 금액에서 적립금 금액 차감
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


	//결제체크
	function payment_check(f) {
		var max_point = 0;
		var od_price = parseInt(f.od_price.value);
		var send_cost = parseInt(f.od_send_cost.value);
		var send_cost2 = parseInt(f.od_send_cost2.value);
		var send_coupon = parseInt(f.od_send_coupon.value);
		temp_point = 0;

		if (typeof(f.max_temp_point) != "undefined")
			var max_point = parseInt(f.max_temp_point.value);

		if (typeof(f.od_temp_point) != "undefined") {
			if (f.od_temp_point.value) {
				var point_unit = parseInt(<?= $default['de_settle_point_unit']; ?>);
				temp_point = parseInt(f.od_temp_point.value);

				if (temp_point < 0) {
					alert("적립금를 0 이상 입력하세요.");
					f.od_temp_point.select();
					return false;
				}

				if (temp_point > od_price) {
					alert("상품 주문금액(배송비 제외) 보다 많이 적립금결제할 수 없습니다.");
					f.od_temp_point.select();
					return false;
				}

				if (temp_point > <?= (int) $member['mb_point']; ?>) {
					alert("회원님의 적립금보다 많이 결제할 수 없습니다.");
					f.od_temp_point.select();
					return false;
				}

				if (temp_point > max_point) {
					alert(max_point + "원 이상 결제할 수 없습니다.");
					f.od_temp_point.select();
					return false;
				}

				if (parseInt(parseInt(temp_point / point_unit) * point_unit) != temp_point) {
					alert("적립금를 " + String(point_unit) + "원 단위로 입력하세요.");
					f.od_temp_point.select();
					return false;
				}

			}
		}

		var tot_price = od_price + send_cost + send_cost2 - send_coupon - temp_point;

		$("#od_coupon_cost").text(number_format(String(parseInt($("input[name=od_coupon]").val()))));
		$("#od_point_cost").text(number_format(String(temp_point)));

		$("#od_tot_price").text(number_format(String(tot_price)));
		$("input[name='submitChecked']").val(number_format(String(tot_price)) + "원 결제");


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

		return true;
	}

	// 구매자 정보와 동일합니다.
	function gumae2baesong() {
		var f = document.forderform;

		f.od_b_name.value = f.od_name.value;
		f.od_b_tel.value = f.od_tel.value;
		f.od_b_hp.value = f.od_hp.value;
		f.od_b_zip.value = f.od_zip.value;
		f.od_b_addr1.value = f.od_addr1.value;
		f.od_b_addr2.value = f.od_addr2.value;
		f.od_b_addr3.value = f.od_addr3.value;
		f.od_b_addr_jibeon.value = f.od_addr_jibeon.value;

		//calculate_sendcost(String(f.od_b_zip.value));

		ad_subject_change();
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