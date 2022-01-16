<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
	include_once(G5_MSHOP_PATH . '/orderinquirycare.php');
	return;
}
define("_ORDERINQUIRY_", true);

$od_pwd = get_encrypt_string($od_pwd);

// 회원인 경우
if ($is_member) {
	$sql_common = " from {$g5['g5_shop_order_table']} where mb_id = '{$member['mb_id']}' ";


	if (!isset($od_stime) || $od_stime == "") {
		$od_stime = date_create(G5_TIME_YMD);
		date_add($od_stime, date_interval_create_from_date_string('-7 days'));
		$od_stime = date_format($od_stime, "Y-m-d");
	}
	if (!isset($od_etime) || $od_etime == "") $od_etime = G5_TIME_YMD;
} else if ($od_id && $od_pwd) // 비회원인 경우 주문서번호와 비밀번호가 넘어왔다면
{
	$sql_common = " from {$g5['g5_shop_order_table']} where od_id = '$od_id' and od_pwd = '$od_pwd' ";
} else // 그렇지 않다면 로그인으로 가기
{
	goto_url(G5_BBS_URL . '/login.php?url=' . urlencode(G5_SHOP_URL . '/orderinquirycare.php'));
}


// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$sql .= " and ((od_type = 'R' and od_status = '리스중') or od_type in ('L','K','S')) ";

if (isset($od_type) && $od_type != "") $sql .= " and od_type = '{$od_type}' ";
if (isset($od_stime) && $od_stime != "") $sql .= " and od_time >= '{$od_stime}' ";
if (isset($od_etime) && $od_etime != "") $sql .= " and od_time <= '{$od_etime} 23:59:59' ";

$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) {
	$page = 1;
} // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$qstr = "od_type=" . $od_type . "&amp;od_stime=" . $od_stime . "&amp;od_etime=" . $od_etime . "&amp;dateBtnOn=" . $dateBtnOn;
$qstr2 = "&od_stime=" . $od_stime . "&od_etime=" . $od_etime . "&dateBtnOn=" . $dateBtnOn;

$g5['title'] = '리스/케어 서비스 내역';
include_once('./_head.php');
?>
<!-- container -->
<div id="container">
	<!-- lnb -->
	<div id="lnb" class="header_bar">
		<h1 class="title"><span>리스/케어서비스</span></h1>
	</div>
	<!-- 주문 내역 시작 { -->

	<!-- //lnb -->
	<div class="content mypage sub">
		<!-- 컨텐츠 시작 -->
		<div class="grid">

			<div class="gray_box pad15">
				<p>* 세탁과 보관, 수선서비스 신청을 원하시면 잔여 무료 횟수와 케어 가능한 제품을 확인 해 보세요.</p>
				<p>* 케어 서비스 신청은 라이프라이크몰에서 주문하신 제품에 한해 신청이 가능합니다.</p>
				<p>* 오프라인 매장 제품 및 브랜드제품은 케어서비스를 지원하지 않습니다.</p>
			</div>
		</div>

		<div class="grid">
			<div class="tab_cont_wrap">
				<div class="tab">
					<ul class="type3 onoff tab_btn count6">
						<li <?php echo ($od_type == "") ? 'class="on"' : '' ?> onclick="location.href='<?php echo G5_SHOP_URL ?>/orderinquirycare.php?od_type=<?php echo $qstr2 ?>';"><a href="#"><span>전체</span></a></li>
						<li <?php echo ($od_type == "R") ? 'class="on"' : '' ?> onclick="location.href='<?php echo G5_SHOP_URL ?>/orderinquirycare.php?od_type=R<?php echo $qstr2 ?>';"><a href="#"><span>리스</span></a></li>
						<li <?php echo ($od_type == "L") ? 'class="on"' : '' ?> onclick="location.href='<?php echo G5_SHOP_URL ?>/orderinquirycare.php?od_type=L<?php echo $qstr2 ?>';"><a href="#"><span>세탁</span></a></li>
						<li <?php echo ($od_type == "K") ? 'class="on"' : '' ?> onclick="location.href='<?php echo G5_SHOP_URL ?>/orderinquirycare.php?od_type=K<?php echo $qstr2 ?>';"><a href="#"><span>세탁보관</span></a></li>
						<li <?php echo ($od_type == "S") ? 'class="on"' : '' ?> onclick="location.href='<?php echo G5_SHOP_URL ?>/orderinquirycare.php?od_type=S<?php echo $qstr2 ?>';"><a href="#"><span>수선</span></a></li>
					</ul>
				</div>
				<div class="gray_box form_box">
					<form method="get">
						<input type="hidden" name="od_type" value="<?php echo $od_type ?>">
						<input type="hidden" id="dateBtnOn" name="dateBtnOn" value="<?php echo $dateBtnOn ?>">
						<div class="tab fix">
							<ul class="type4 black onoff">
								<li <?php echo ($dateBtnOn == "" || $dateBtnOn == "1w") ? 'class="on"' : '' ?>><button type="button" name="dateBtn" data="1w">1주일</button></li>
								<li <?php echo ($dateBtnOn == "1m") ? 'class="on"' : '' ?>><button type="button" name="dateBtn" data="1m">1개월</button></li>
								<li <?php echo ($dateBtnOn == "3m") ? 'class="on"' : '' ?>><button type="button" name="dateBtn" data="3m">3개월</button></li>
								<li <?php echo ($dateBtnOn == "6m") ? 'class="on"' : '' ?>><button type="button" name="dateBtn" data="6m">6개월</button></li>
								<li <?php echo ($dateBtnOn == "1y") ? 'class="on"' : '' ?>><button type="button" name="dateBtn" data="1y">1년</button></li>
							</ul>
						</div>
						<div class="inp_wrap">
							<div class="inp_ele count4">
								<div class="input calendar">
									<input type="date" placeholder="" name="od_stime" value="<?php echo $od_stime ?>">
								</div>
							</div>
							<div class="inp_ele count1 alignC">-</div>
							<div class="inp_ele count4">
								<div class="input calendar">
									<input type="date" placeholder="" name="od_etime" value="<?php echo $od_etime ?>">
								</div>
							</div>
						</div>
						<button type="submit" class="btn small green"><span>조회</span></button>
					</form>
				</div>
				<div class="info_box">
					<p class="ico_import red point_red more">
						세탁과 보관, 수선을 맡기고 싶으시다면? 잔여 무료 횟수와 케어 가능한 제품을 확인해 보세요.
						<a href="<?php echo G5_SHOP_URL ?>/care.php" class="btn floatR arrow_r_green">바로가기</a>
					</p>
				</div>
				<div class="tab_cont">
					<!-- tab 1 -->
					<div class="tab_inner">
						<p class="txt_total">총<strong><?php echo $total_count; ?></strong>건</p>
						<div class="orderwrap">
							<!-- 제품일때 -->

							<?php

							$is_care = "1";
							$limit = " limit $from_record, $rows ";
							include "./orderinquiry.sub.php";
							?>

							<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
							<!-- } 주문 내역 끝 -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(function() {

		//날짜 버튼
		$("button[name='dateBtn']").click(function() {

			var d = $(this).attr("data");
			$('#dateBtnOn').val(d);

			var startD = new Date();
			var endD = new Date();

			if (d == "3d") {
				startD.setDate(startD.getDate() - 3);
			} else if (d == "1w") {
				startD.setDate(startD.getDate() - 7);
			} else if (d == "1m") {
				startD.setMonth(startD.getMonth() - 1);
			} else if (d == "3m") {
				startD.setMonth(startD.getMonth() - 3);
			} else if (d == "6m") {
				startD.setMonth(startD.getMonth() - 6);
			} else if (d == "1y") {
				startD.setMonth(startD.getMonth() - 12);
			}

			$("input[name=od_stime]").val(date_format(startD, "yyyy-MM-dd"));
			$("input[name=od_etime]").val(date_format(endD, "yyyy-MM-dd"));
			//$('#sc_od_time').data('daterangepicker').setStartDate(startD);
			//$('#sc_od_time').data('daterangepicker').setEndDate(endD);

		});
	});
</script>
<?php
include_once('./_tail.php');
?>