<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

require_once(G5_MSHOP_PATH . '/settle_lg2.inc.php');

if (!$is_member) { // 회원이 아닌 경우 로그인화면 이동
	alert("로그인이 필요한 서비스입니다.", G5_BBS_URL . '/login.php?url=' . urlencode(G5_SHOP_URL . '/orderform.php?od_type=' . $od_type), false);
	return;
}

$tablet_size = "1.0"; // 화면 사이즈 조정 - 기기화면에 맞게 수정(갤럭시탭,아이패드 - 1.85, 스마트폰 - 1.0)

// 개인결제번호제거
set_session('ss_personalpay_id', '');
set_session('ss_personalpay_hash', '');
?>
<script>
	var header = '<div id="lnb" class="header_bar">';
	header += '<h1 class="title"><span>리스 계약서 작성</span></h1>';
	header += '<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
	header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
	header += '</div>';
	$('#header').html(header);
</script>

<!-- //lnb -->
<div class="content comm sub">
	<!-- 컨텐츠 시작 -->

	<form name="forderform" method="post" action="<?= $order_action_url; ?>" autocomplete="off">

		<div class="grid cont">
			<div class="title_bar none">
				<h2 class="g_title_01">고객 정보</h2>
			</div>
		</div>


		<div class="grid">
			<div class="title_bar">
				<h3 class="g_title_01">계약자 정보</h3>
			</div>
			<!-- 일반 -->
			<div class="border_box order_list">
				<div class="inp_wrap">
					<div class="title count3"><label for="f_51">생년월일</label></div>
					<div class="inp_ele count6">
						<span class="value"><?= get_text($member['mb_birth']); ?></span>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="title count3"><label for="f_52">이름</label></div>
					<div class="inp_ele count6">
						<span class="value"><?= get_text($member['mb_name']); ?></span>
					</div>
					<input type="hidden" value="<?= get_text($member['od_email']); ?>" id="mb_email" name="mb_email">
					<input type="hidden" name="od_tel" value="<?= get_text($member['mb_tel']); ?>" id="od_tel">
					<input type="hidden" name="od_zip" value="<?= $member['mb_zip1'] . $member['mb_zip2']; ?>" id="od_zip">
					<input type="hidden" name="od_addr1" value="<?= get_text($member['mb_addr1']) ?>" id="od_addr1">
					<input type="hidden" name="od_addr2" value="<?= get_text($member['mb_addr2']) ?>" id="od_addr2">
					<input type="hidden" name="od_addr3" value="<?= get_text($member['mb_addr3']) ?>" id="od_addr3">
					<input type="hidden" name="od_addr_jibeon" value="<?= get_text($member['mb_addr_jibeon']); ?>">
				</div>
				<div class="inp_wrap">
					<div class="title count3"><label for="f_53">연락처</label></div>
					<div class="inp_ele count6">
						<span class="value"><?= get_text($member['mb_tel']); ?></span>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="title count3"><label for="f_54">휴대전화 번호</label></div>
					<div class="inp_ele count6">
						<span class="value"><?= get_text($member['mb_hp']); ?></span>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="title count3">
						<label for="join7">주소</label>
					</div>
					<div class="inp_ele count6">
						<span class="value"><?= $member['mb_zip1'] . $member['mb_zip2']; ?></span>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="inp_ele count6 col_r">
						<span class="value"><?= get_text($member['mb_addr1']); ?></span>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="inp_ele count6 col_r">
						<span class="value"><?= get_text($member['mb_addr2']); ?></span>
					</div>
				</div>
			</div>
		</div>

		<div class="grid">
			<div class="title_bar">
				<h3 class="g_title_01">사용자 정보</h3>
				<span class="chk radio floatR">
					<input type="checkbox" name="ad_sel_addr" value="same" id="ad_sel_addr_same">
					<label for="ad_sel_addr_same">계약자와 동일</label>
				</span>
			</div>

			<div class="border_box">
				<div class="inp_wrap">
					<div class="title count3"><label>이름</label></div>
					<div class="inp_ele count6">
						<div class="input"><input type="text" name="od_b_name" id="od_b_name" required class="frm_input required" maxlength="20" placeholder="이름 입력"></div>
					</div>
					<input type="hidden" name="ad_subject" id="ad_subject" value="">
				</div>
				<div class="inp_wrap">
					<div class="title count3"><label>연락처</label></div>
					<div class="inp_ele count6">
						<div class="input"><input type="text" name="od_b_tel" id="od_b_tel" class="frm_input required" maxlength="20" placeholder="연락처 입력"></div>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="title count3"><label>휴대전화 번호</label></div>
					<div class="inp_ele count6">
						<div class="input"><input type="text" name="od_b_hp" id="od_b_hp" required class="frm_input" maxlength="20" placeholder="휴대전화 번호 입력"></div>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="title count3"><label>E-mail</label></div>
					<div class="inp_ele count6">
						<div class="input"><input type="text" name="od_email" id="od_email" required class="frm_input" maxlength="100" placeholder="이메일 입력"></div>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="title count3"><label>주소</label></div>
					<div class="inp_ele count6 r_btn_100">
						<div class="input" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');"><input type="text" name="od_b_zip" id="od_b_zip" required class="frm_input required disabled readonly" size="5" maxlength="6" readonly="readonly"></div>
						<button type="button" class="btn small green" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">주소검색</button>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="inp_ele count12 ">
						<div class="input"><input type="text" name="od_b_addr1" id="od_b_addr1" required class="frm_input frm_address required disabled readonly" readonly="readonly"></div>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="inp_ele count12">
						<div class="input"><input type="text" name="od_b_addr2" id="od_b_addr2" class="frm_input frm_address">
							<input type="hidden" name="od_b_addr3" id="od_b_addr3">
							<input type="hidden" name="od_b_addr_jibeon" value=""></div>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="title count3"><label>배송 메시지</label></div>
					<div class="inp_ele count6">
						<div class="input"><input type="text" placeholder="한글 20자 이내 입력" name="od_memo" id="od_memo" title="배송요청 내용" value="" maxlength="20"></div>
					</div>
				</div>
			</div>
		</div>


		<div class="grid">
			<div class="title_bar">
				<h2 class="g_title_01">계약 내용</h2>
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
					$goods = preg_replace("/\'|\"|\||\,|\&|\;/", "", $row['it_name']);
				}
				$goods_count++;

				$image_width = 80;
				$image_height = 80;
				$image = get_it_image($row['it_id'], $image_width, $image_height);

				$it_name = stripslashes($row['it_name']);

				$price_plus = '';
				if ($row['io_price'] >= 0) $price_plus = '+';

				$it_options = get_text($row['ct_option']) . ' (' . $price_plus . display_price($row['io_price']) . ')' . PHP_EOL;
				//$it_options = print_item_options($row['ct_option'], $s_cart_id);

				$sell_price = $row['price'];
				$sell_rental_price = $row['rental_price'];
				?>

				<div class="border_box order_list">
					<ul>
						<li>
							<span class="item">계약 제품</span>
							<strong class="result">
								<em class="bold"><?= $it_name; ?></em><br /><?= get_text($row['ct_option']); ?>
							</strong>
						</li>
						<li>
							<span class="item">수량</span>
							<strong class="result"><?= $row['ct_qty'] ?>개</strong>
						</li>
						<li>
							<span class="item">월 이용료</span>
							<strong class="result"><?= number_format($sell_rental_price); ?> 원</strong>
						</li>
						<li>
							<span class="item">무료세탁</span>
							<strong class="result">연간 1회</strong>
							<!-- <strong class="result">년 <?= $row['its_free_laundry'] ?>회</strong> -->
						</li>
						<li>
							<span class="item">총 계약기간</span>
							<strong class="result">
								<em class="point_red"><?= $row['ct_item_rental_month'] ?></em>개월
							</strong>
						</li>
					</ul>
					<input type="hidden" name="it_id[<?= $i; ?>]" value="<?= $row['it_id']; ?>">
					<input type="hidden" name="it_name[<?= $i; ?>]" value="<?= get_text($row['it_name']); ?>">
					<input type="hidden" name="it_price[<?= $i; ?>]" value="<?= $sell_rental_price; ?>">
					<? if ($default['de_tax_flag_use']) { ?>
						<input type="hidden" name="it_notax[<?= $i; ?>]" value="<?= $row['it_notax']; ?>">
					<? } ?>
					<input type="hidden" name="cp_id[<?= $i; ?>]" value="">
					<input type="hidden" name="cp_price[<?= $i; ?>]" value="0">

				</div>
			<?
				$tot_sell_price += $sell_price;
				$tot_sell_rental_price += $sell_rental_price;
			} // for 끝

			if ($i == 0) {
				//echo '<li class="empty_li">장바구니에 담긴 상품이 없습니다.</li>';
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
		<? $oc_cnt = $sc_cnt = 0; ?>
		<? $tot_price = $tot_sell_price + $send_cost; // 총계 = 주문상품금액합계 + 배송비 
		?>

		<!-- } 주문상품 합계 끝 -->

		<div class="grid bg_none">
			<div class="title_bar">
				<h3 class="g_title_01">결제 구분</h3>
			</div>
			<div class="border_box type3">
				<div class="inp_wrap">
					<div class="title count3"><label for="ra1">결제 수단</label></div>
					<div class="inp_ele count6">
						<ul class="count2">
							<li><span class="chk radio"><input type="radio" id="od_settle_card" name="od_settle_case" value="신용카드" checked><label for="od_settle_card">카드 이체</label></span></li>
						</ul>

						<input type="hidden" name="od_price" value="<?= $tot_sell_rental_price; ?>">
						<input type="hidden" name="org_od_price" value="<?= $tot_sell_rental_price; ?>">
						<input type="hidden" name="od_send_cost" value="<?= $send_cost; ?>">
						<input type="hidden" name="od_send_cost2" value="0">
						<input type="hidden" name="item_coupon" value="0">
						<input type="hidden" name="od_coupon" value="0">
						<input type="hidden" name="od_send_coupon" value="0">
						<input type="hidden" name="od_goods_name" value="<?= $goods; ?>">
						<input type="hidden" name="od_type" value="<?= $od_type ?>" />

					</div>
				</div>
			</div>
		</div>

		<div class="grid">
			<div class="title_bar">
				<h3 class="g_title_01">판매자 정보</h3>
			</div>
			<div class="border_box order_list">
				<ul>
					<li>
						<span class="item">판매 일시</span>
						<strong class="result">
							<?
							$makedate = date_create(G5_TIME_YMDHIS);
							$makedate = date_format($makedate, "Y년 m월 d일");
							echo $makedate;
							?>
						</strong>
					</li>
					<li>
						<span class="item">판매자 상호</span>
						<strong class="result"><?= $default['de_admin_company_name'] ?></strong>
					</li>
					<li>
						<span class="item">판매자 연락처</span>
						<strong class="result"><?= $default['de_admin_call_tel'] ?></strong>
					</li>
				</ul>
			</div>
		</div>

		<div class="grid">
			<div class="title_bar">
				<h3 class="g_title_01">고객 확인사항</h3>
			</div>
			<div class="info_box border_box">
				<ol class="text_g">
					<li>1. 제품 정보(제품명/수량/월이용료) 및 기능, 색상, 디자인에 대해 이상 없이 확인합니다.</li>
					<li>2. 총 계약기간은 36개월(3년)이고, 의무사용기간은 36개월(3년) 입니다.</li>
					<li>3. 월요금은 인도일 익월부터 정기 출금/승인일에 청구 됩니다.</li>
					<li>4. 계약은 제품 인도/설치 후 성립되며, 계약 후 고객의 임의 해지시 해약금(위약금+운송비+사은품비용+미납/연체비용 등)이 부과됩니다.</li>
					<li>5. 의무사용기간 내 고객요청에 의한 임의 해지 시 위약금이 부과됩니다. 위약금 산정 시, 월요금은 할인되기 전의 기본요금을 기준으로 합니다.
						<ul class="hyphen">
							<li>1년 이내 해지시: (월 리스료÷30일) X (의무사용일수-실사용일수) X 30%</li>
							<li>1년 이상~2년 이내 해지시: (월 이용료÷30일) X (의무사용일수-실사용일수) X 20%</li>
							<li>2년 이상~3년 이내 해지시: (월 이용료÷30일) X (의무사용일수-실사용일수) X 10%</li>
							<li>운송비: 총 계약기간(3년) 내 고객 요청에 의한 임의 해지시 운송비가 부과됩니다.</li>
							<li>미납/연체비용: 해지 접수 시점에 미납/연체비용이 있을 경우 해약금에 함께 청구 됩니다.</li>
						</ul>
					</li>
				</ol>
			</div>
		</div>

		<div class="grid">
			<div class="title_bar">
				<h3 class="g_title_01">약관 안내/동의</h3>
			</div>
			<div class="border_box type2">
				<!--
						<div class="inp_wrap line_full">
							<div class="inp_ele count9 alignR">
								<span class="chk check">
									<input type="checkbox" id="chk_all_stipulation">
									<label for="chk_all_stipulation">전체동의</label>
								</span>
							</div>
						</div>
						-->
				<div class="inp_wrap line_full">
					<div class="title count9 bold">
						<label>계약서 확인 및 동의(필수)</label>
						<a href="<?= G5_MOBILE_URL ?>/common/terms_agreement.php?id=chk_auto_billing&type=contract_cancel&title=<?= urlencode("계약서 확인 및 동의") ?>" class="btn floatR arrow_r_green" target="_blank">전문보기</a>
					</div>
					<div class="count9 alignR agree_list">
						<ul class="count2">
							<li>
								<span class="chk radio">
									<input type="radio" id="chk_auto_billing" name="rdo_auto_billing" value="1">
									<label for="chk_auto_billing">동의</label>
								</span>
							</li>
							<li>
								<span class="chk radio">
									<input type="radio" id="chk_auto_billing2" name="rdo_auto_billing" value="0">
									<label for="chk_auto_billing2">미동의</label>
								</span>
							</li>
						</ul>
					</div>
				</div>
				<div class="inp_wrap line_full">
					<div class="title count9 bold">
						<label>마케팅 활용 동의(선택)</label>
						<a href="<?= G5_MOBILE_URL ?>/common/terms_agreement.php?id=chk_user_thirdparty_privacy&type=collection_privacy&title=<?= urlencode("마케팅 활용 동의") ?>" class="btn floatR arrow_r_green" target="_blank">전문보기</a>
					</div>
					<div class="count9 alignR agree_list">
						<ul class="count2">
							<li>
								<span class="chk radio">
									<input type="radio" id="chk_user_thirdparty_privacy" name="rdo_user_thirdparty_privacy" value="1">
									<label for="chk_user_thirdparty_privacy">동의</label>
								</span>
							</li>
							<li>
								<span class="chk radio">
									<input type="radio" id="chk_user_thirdparty_privacy2" name="rdo_user_thirdparty_privacy" value="0">
									<label for="chk_user_thirdparty_privacy2">미동의</label>
								</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="info_box border_box">
				<strong class="g_title_06">고지 안내사항</strong>
				<p class="text_g">회사는 본 제품의 계약 이행 및 원활한 서비스 제공을 위해 개인정보 취급을 위탁하고 있으며, 위탁업체 및 업무내용에 관한 상세내용은 뒷면의
					이용약관 또는 라이프라이크 홈페이지(http://www.lifelike.co.kr) 內 개인정보취급방츰을 참조하여 주시기 바랍니다.</p>
				<p class="text_g">※고객님의 정보조회/수정 또는 동의 철회를 위해서는 본 제품 계약 매장 또는 개인정보관리책임자에게 연락하여 주십시오.</p>
			</div>
		</div>

		<div class="grid">
			<div class="title_bar">
				<h3 class="g_title_01">계약 서명</h3>
			</div>
			<div class="border_box order_list">
				<p class="ico_import red point_red">계약자는 제품내용, 고객확인사항 및 이용약관에 대하여 충분한 설명을 듣고 이를 확인하였으며, 계약은 설치
					완료 후 성립됨에 동의하는 의미로 아래와 같이 전자서명을 합니다.</p>
				<div class="inp_wrap">
					<div class="title" style="width:50%; padding:0px; float:left;"><label for="f2">판매 일자/계약 일자</label></div>
					<div class="inp_ele floatR" style="width:50%; text-align:right;"><?= $makedate; ?></div>
				</div>
				<div class="inp_wrap">
					<div class="title count3"><label for="f2">이름</label></div>
					<div class="inp_ele count6">
						<div class="input"><input type="text" name="od_name" value="<?= get_text($member['mb_name']); ?>" id="od_name" readonly="readonly"></div>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="title count3"><label for="f2">휴대전화 번호</label></div>
					<div class="inp_ele count6">
						<div class="input"><input type="text" name="od_hp" value="<?= get_text($member['mb_hp']); ?>" id="od_hp" readonly="readonly"></div>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="title count9 bold">
						<label for="btn_send_auth_key">본인인증</label><a href="javascript:" class="btn floatR arrow_r_green" id="btn_send_auth_key">인증하기</a>
					</div>
				</div>

				<div style="display: none;" id="div_auth">
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
						<div class="title count9 bold"><label for="join1">전자 서명</label></div>
					</div>

					<div class="signature_box" id="canvasSimpleDiv"><span class="signature_txt">전자서명란</span></div>

					<div class="inp_wrap">
						<div class="inp_ele count6"></div>
						<div class="inp_ele count3">
							<input type="hidden" id="cust_file" name="cust_file">
							<input type="hidden" id="orgCanvasSimple">
							<input type="button" value="다시작성" id="clearCanvasSimple" class="btn small gray_line">
						</div>
					</div>
				</div>

				<div class="inp_wrap">
					<div class="title count6"><label for="">제품 및 계약 내용, 유지관리 서비스 문의</label></div>
					<div class="inp_ele count3 alignR"><?= $default['de_admin_call_tel'] ?></div>
				</div>
				<div class="inp_wrap" style="position:inherit;">

				</div>
				<p class="ico_import red point_red">본인인증 후 전자서명이 가능합니다.</p>
			</div>
		</div>
		<?
		// 결제대행사별 코드 include (결제대행사 정보 필드 및 주분버튼)
		require_once(G5_MSHOP_PATH . '/lg2/orderform.2.php');
		?>

		<div id="show_progress" style="display:none;">
			<img src="<?= G5_MOBILE_URL; ?>/shop/img/loading.gif" alt="">
			<span>계약완료 중입니다. 잠시만 기다려 주십시오.</span>
		</div>

	</form>
</div>


<?
// 결제대행사별 코드 include (결제등록 필드)
require_once(G5_MSHOP_PATH . '/lg2/orderform.1.php');
?>

<script src="<?= G5_JS_URL ?>/shop.order.js"></script>
<script>
	var zipcode = "";
	var canvasDiv;
	var context;
	var canvasWidth = 311;
	var canvasHeight = 153;

	var clickX_simple = new Array();
	var clickY_simple = new Array();
	var clickDrag_simple = new Array();
	var paint_simple;
	var canvas_simple;
	var context_simple;

	$(function() {
		var $cp_btn_el;
		var $cp_row_el;

		$(".cp_btn").click(function() {
			$cp_btn_el = $(this);
			$cp_row_el = $(this).closest("li");
			$("#cp_frm").remove();
			var it_id = $cp_btn_el.closest("li").find("input[name^=it_id]").val();

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
					$cp_dup_el = $(this).closest("li");;

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
					$cp_dup_el.find(".cp_btn").text("쿠폰적용").removeClass("cp_mod").focus();
					$cp_dup_el.find(".cp_cancel").remove();
				}
			}

			var $s_el = $cp_row_el.find(".total_price strong");;
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
			$cp_btn_el.text("변경").addClass("cp_mod").focus();
			if (!$cp_row_el.find(".cp_cancel").size())
				$cp_btn_el.after("<button type=\"button\" class=\"cp_cancel\">취소</button>");
		});

		$(document).on("click", "#cp_close", function() {
			$("#cp_frm").remove();
			$cp_btn_el.focus();
		});

		$(document).on("click", ".cp_cancel", function() {
			coupon_cancel($(this).closest("li"));
			calculate_total_price();
			$("#cp_frm").remove();
			$(this).closest("li").find(".cp_btn").text("쿠폰적용").removeClass("cp_mod").focus();
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
				$("#od_coupon_btn").after("<button type=\"button\" id=\"od_coupon_cancel\" class=\"cp_cancel1\">취소</button>");
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
				$("#sc_coupon_btn").after("<button type=\"button\" id=\"sc_coupon_cancel\" class=\"cp_cancel1\">취소</button>");
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
			$("#show_req_btn").css("display", "none");
			$("#show_pay_btn").css("display", "block");
		});

		$("#od_settle_iche,#od_settle_card,#od_settle_vbank,#od_settle_hp,#od_settle_easy_pay,#od_settle_kakaopay,#od_settle_samsungpay").bind("click", function() {
			$("#settle_bank").hide();
			$("#show_req_btn").css("display", "block");
			$("#show_pay_btn").css("display", "none");
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
			window.open(url, "win_address", "left=100,top=100,width=650,height=500,scrollbars=1");
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

		var timer = 180;
		$("#btn_send_auth_key").on("click", function() {

			if ($('#od_hp').val() == '') {
				alert("등록된 휴대전화번호가 없습니다. 회원정보에서 휴대전화 번호를 입력바랍니다.");
				return false;
			}

			<? if ($default['de_card_test'] && false) {   // 테스트 결제시 
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
		var $dup_sell_el = $el.find(".total_price strong");
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

		$("#ct_tot_coupon").text(number_format(String(tot_cp_price)) + " 원");
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

		$("form[name=sm_form] input[name=good_mny]").val(tot_price);
		$("#od_tot_price").text(number_format(String(tot_price)));
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

	/* 결제방법에 따른 처리 후 결제등록요청 실행 */
	var settle_method = "";
	var temp_point = 0;

	function pay_approval() {
		// 재고체크
		var stock_msg = order_stock_check();
		if (stock_msg != "") {
			alert(stock_msg);
			return false;
		}

		var f = document.sm_form;
		var pf = document.forderform;

		// 필드체크
		if (!orderfield_check(pf))
			return false;

		// 금액체크
		if (!payment_check(pf))
			return false;

		// pg 결제 금액에서 포인트 금액 차감
		if (settle_method != "무통장") {
			var od_price = parseInt(pf.od_price.value);
			var send_cost = parseInt(pf.od_send_cost.value);
			var send_cost2 = parseInt(pf.od_send_cost2.value);
			var send_coupon = parseInt(pf.od_send_coupon.value);
			f.good_mny.value = od_price + send_cost + send_cost2 - send_coupon - temp_point;
		}

		var form_order_method = '';

		if (settle_method == "삼성페이" || settle_method == "lpay") {
			form_order_method = 'samsungpay';
		}

		if (jQuery(pf).triggerHandler("form_sumbit_order_" + form_order_method) !== false) {

			//lg
			var pay_method = "";
			var easy_pay = "";
			switch (settle_method) {
				case "계좌이체":
					pay_method = "SC0030";
					break;
				case "가상계좌":
					pay_method = "SC0040";
					break;
				case "휴대전화":
					pay_method = "SC0060";
					break;
				case "신용카드":
					pay_method = "SC0010";
					break;
				case "간편결제":
					easy_pay = "PAYNOW";
					break;
			}
			f.LGD_CUSTOM_FIRSTPAY.value = pay_method;
			f.LGD_BUYER.value = pf.od_name.value;
			f.LGD_BUYEREMAIL.value = pf.od_email.value;
			f.LGD_BUYERPHONE.value = pf.od_hp.value;
			f.LGD_AMOUNT.value = f.good_mny.value;
			f.LGD_EASYPAY_ONLY.value = easy_pay;
			<? if ($default['de_tax_flag_use']) { ?>
				f.LGD_TAXFREEAMOUNT.value = pf.comm_free_mny.value;
			<? } ?>

			if (!confirm("입력 된 정보로 계약서를 전송 합니다.\n카드이체 등록이 완료되면 계약서가 전송됩니다.")) {
				return false;
			}

			// 주문 정보 임시저장
			var order_data = $(pf).serialize();
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

			f.submit();
		}

		return false;
	}

	function forderform_check() {
		var f = document.forderform;

		// 필드체크
		if (!orderfield_check(f))
			return false;

		// 금액체크
		if (!payment_check(f))
			return false;

		if (settle_method != "무통장" && f.res_cd.value != "0000") {
			alert("결제등록요청 후 주문해 주십시오.");
			return false;
		}

		document.getElementById("display_pay_button").style.display = "none";
		document.getElementById("show_progress").style.display = "block";

		setTimeout(function() {
			f.submit();
		}, 300);
	}

	// 주문폼 필드체크
	function orderfield_check(f) {
		errmsg = "";
		errfld = "";
		var deffld = "";

		//check_field(f.od_name, "주문하시는 분 이름을 입력하십시오.");
		//check_field(f.od_hp, "주문하시는 분 휴대전화 번호를 입력하십시오.");
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
			f.chk_all_stipulation.focus();
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
		var settle_check = false;
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

		return true;
	}

	// 결제체크
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

		return true;
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