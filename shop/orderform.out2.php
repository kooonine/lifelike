<?
include_once('./_common.php');

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

// 주문상품 재고체크 js 파일
add_javascript('<script src="'.G5_JS_URL.'/shop.order.js"></script>', 0);

if(!$od_type) $od_type = "R";

// 모바일 주문인지
$is_mobile_order = is_mobile();

$g5['title'] = '위약금 영수증';

if(G5_IS_MOBILE)
	include_once(G5_MSHOP_PATH.'/_head.php');
else
	include_once(G5_SHOP_PATH.'/_head.php');

$sql = "select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
if($is_member && !$is_admin)
	$sql .= " and mb_id = '{$member['mb_id']}' ";
$od = sql_fetch($sql);
if (!$od['od_id'] || (!$is_member && md5($od['od_id'].$od['od_time'].$od['od_ip']) != get_session('ss_orderview_uid'))) {
	alert("조회하실 주문서가 없습니다.", G5_SHOP_URL);
}

$rt_month = $od['rt_month'];
$rt_rental_enddate = date_create($od['rt_rental_startdate']);
date_add($rt_rental_enddate, date_interval_create_from_date_string($rt_month.' months'));
$rt_rental_enddate = date_format($rt_rental_enddate,"Y-m-d");

$tot_price = $od['od_penalty'];

// 기기별 주문폼 include
//if($is_mobile_order) {
//    require_once(G5_MSHOP_PATH.'/orderform.out2.php');
//} else {
?>

<!-- container -->
<div id="container">
	<!-- lnb -->
	<div id="lnb" class="header_bar">
		<h1 class="title"><span>위약금 영수증</span></h1>
	</div>
	<!-- //lnb -->
	<div class="content comm sub">

		<!-- 컨텐츠 시작 -->
		<div class="grid cont">

			<div class="orderwrap">
				<div class="order_cont">
					<div class="head">
						<span class="category round_green">리스</span>
						<span class="order_number">주문번호 : <strong><?=$od_id; ?></strong></span>
					</div>

					<div class="body">
						<?
						$sql = " select it_id, it_name, cp_price, ct_send_cost, it_sc_type
						,ct_id, ct_option, ct_qty, ct_price, ct_point, ct_status, io_type, io_price, ct_rental_price, ct_item_rental_month, ct_keep_month, ct_receipt_price
						from {$g5['g5_shop_cart_table']}
						where od_id = '$od_id'
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
					<div style="">
						<div class="title_bar">
							<h2 class="g_title_01">위약금 정보</h2>
						</div>
						<table class="TBasic4">
							<colgroup>
								<col width="35%" />
								<col width="65%" />
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
									<th class="tleft" style="letter-spacing:-0.5px;">예상 위약금 금액</th>
									<td><?=number_format($od['od_penalty']) ?> 원</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div style="">
						<div class="title_bar">
							<h2 class="g_title_01">해지 요청 수거지 정보</h2>
						</div>
						<table class="TBasic4">
							<colgroup>
								<col width="35%" />
								<col width="65%" />
							</colgroup>
							<tbody>
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
							</tbody>
						</table>
					</div>
					<div class="clear"></div>
				</div>
			</div>

			<div class="grid">
				<div class="box">
					<div class="gray_box">
						<p class="ico_import red point_red" style="margin-top: 0px;">본 영수증은 제품내역 확인 용도로만 사용하실 수 있으며, 법적인 효력은 없습니다.</p>
					</div>
				</div>

				<div class="btn_group">
					<a href="<?=G5_SHOP_URL; ?>/orderinquiry.php"><button type="button" class="btn big border"><span>목록 이동</span></button></a>
				</div>
			</div>



			<?
			$pt = sql_fetch("select * from lt_shop_order_add_receipt where od_id = '$od_id' and od_receipt_type='penalty' ");
			?>
	<!-- div class="grid">
		<div class="title_bar none">
			<h2 class="g_title_01">결제 정보</h2>
		</div>
		<div class="divide_two box">
			<div class="box">
				<div class="order_list border_box">
					<table class="tbl_basic line">
						<colgroup>
								<col style="width:30%;">
								<col style="width:50%;">
								<col style="width:20%;">
							</colgroup>
						<thead>
							<tr>
								<th class="alignL">승인 일자</th>
								<th class="alignL">결제 수단</th>
								<th class="alignR">결제 금액</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="alignL"><?=$pt['od_receipt_time'] ?></td>
								<td class="alignL"><?=$pt['od_bank_account'] ?></td>
								<td class="alignR"><?=$pt['od_receipt_price'] ?> 원</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="box">
				<div class="gray_box">
					<p class="ico_import red point_red">본 영수증은 제품내역 확인 용도로만 사용하실 수 있으며, 법적인 효력은 없습니다.</p>
				</div>
			</div>
		</div>
	</div>
-->

</div>
</div>


<?
//}
if(G5_IS_MOBILE)
	include_once(G5_MSHOP_PATH.'/_tail.php');
else
	include_once(G5_SHOP_PATH.'/_tail.php');
?>
