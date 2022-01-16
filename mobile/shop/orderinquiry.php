<?php
include_once('./_common.php');

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
} else if ($od_id && $od_name && $od_tel) // 비회원인 경우 주문서번호와 비밀번호가 넘어왔다면
{
	$sql_common = " from {$g5['g5_shop_order_table']} where od_id = '$od_id' and od_name = '$od_name' and od_hp = '$od_tel'";
} else // 그렇지 않다면 로그인으로 가기
{
	goto_url(G5_BBS_URL . '/login.php?url=' . urlencode(G5_SHOP_URL . '/orderinquiry.php'));
}


// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;

if (isset($od_type) && $od_type != "") $sql .= " and od_type = '{$od_type}' ";
if (isset($od_stime) && $od_stime != "") $sql .= " and od_time >= '{$od_stime}' ";
if (isset($od_etime) && $od_etime != "") $sql .= " and od_time <= '{$od_etime} 23:59:59' ";

$row = sql_fetch($sql);
$total_count = (int) $row['cnt'];

// 비회원 주문확인시 비회원의 모든 주문이 다 출력되는 오류 수정
// 조건에 맞는 주문서가 없다면
if ($total_count == 0) {
	if (!$is_member) // 회원일 경우는 메인으로 이동
		alert('주문이 존재하지 않습니다.', G5_MOBILE_URL . "/common/nonmemberorder.php");
}

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) {
	$page = 1;
} // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


// 비회원 주문확인의 경우 바로 주문서 상세조회로 이동
if (!$is_member) {
	$od_id = preg_replace("/[^0-9]/", "", $od_id);
	$od_tel = preg_replace("/[^0-9]/", "", $od_tel);

	$sql = " select od_id, od_time, od_ip from {$g5['g5_shop_order_table']} where od_id = '$od_id' and od_name = '$od_name' and od_hp = '$od_tel'";

	$row = sql_fetch($sql);
	if ($row['od_id']) {
		$uid = md5($row['od_id'] . $row['od_time'] . $row['od_ip']);
		set_session('ss_orderview_uid', $uid);
		goto_url(G5_SHOP_URL . '/orderinquiryview.php?od_id=' . $row['od_id'] . '&amp;uid=' . $uid);
	}
}

if (!isset($dateBtnOn)) $dateBtnOn = "1w";
$qstr = "od_type=" . $od_type . "&amp;od_stime=" . $od_stime . "&amp;od_etime=" . $od_etime . "&amp;dateBtnOn=" . $dateBtnOn;

$qstr2 = "&od_stime=" . $od_stime . "&od_etime=" . $od_etime . "&dateBtnOn=" . $dateBtnOn;

$g5['title'] = '전체 주문 내역';
include_once(G5_MSHOP_PATH . '/_head.php');
?>

<script>
	var header = '<div id="lnb" class="header_bar">';
	header += '<h1 class="title"><span>전체 주문 내역</span></h1>';
	header += '<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
	header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
	header += '</div>';
	$('#header').html(header);
</script>

<!-- //lnb -->
<div class="content mypage sub">
	<!-- 컨텐츠 시작 -->
	<div class="grid">
		<div class="tab_cont_wrap">
			<div class="tab">
				<ul class="type3 onoff">
					<li <?php echo ($od_type == "") ? 'class="on"' : '' ?> onclick="location.href='<?php echo G5_SHOP_URL ?>/orderinquiry.php?od_type=<?php echo $qstr2 ?>';"><a href="#"><span>전체</span></a></li>
					<li <?php echo ($od_type == "R") ? 'class="on"' : '' ?> onclick="location.href='<?php echo G5_SHOP_URL ?>/orderinquiry.php?od_type=R<?php echo $qstr2 ?>';"><a href="#"><span>리스</span></a></li>
					<li <?php echo ($od_type == "O") ? 'class="on"' : '' ?> onclick="location.href='<?php echo G5_SHOP_URL ?>/orderinquiry.php?od_type=O<?php echo $qstr2 ?>';"><a href="#"><span>제품</span></a></li>
					<li <?php echo ($od_type == "L") ? 'class="on"' : '' ?> onclick="location.href='<?php echo G5_SHOP_URL ?>/orderinquiry.php?od_type=L<?php echo $qstr2 ?>';"><a href="#"><span>세탁</span></a></li>
					<li <?php echo ($od_type == "K") ? 'class="on"' : '' ?> onclick="location.href='<?php echo G5_SHOP_URL ?>/orderinquiry.php?od_type=K<?php echo $qstr2 ?>';"><a href="#"><span>세탁보관</span></a></li>
					<li <?php echo ($od_type == "S") ? 'class="on"' : '' ?> onclick="location.href='<?php echo G5_SHOP_URL ?>/orderinquiry.php?od_type=S<?php echo $qstr2 ?>';"><a href="#"><span>수선</span></a></li>
				</ul>
			</div>
			<div class="search-box">
				<form method="get">
					<input type="hidden" name="od_type" value="<?php echo $od_type ?>">

					<div class="inp_wrap">
						<span class="sel_box">
							<select name="dateBtnOn" id="dateBtnOn">
								<option value="1w" <?php echo get_selected($dateBtnOn, "1w") ?>>1주일</option>
								<option value="1m" <?php echo get_selected($dateBtnOn, "1m") ?>>1개월</option>
								<option value="3m" <?php echo get_selected($dateBtnOn, "3m") ?>>3개월</option>
								<option value="6m" <?php echo get_selected($dateBtnOn, "6m") ?>>6개월</option>
								<option value="1y" <?php echo get_selected($dateBtnOn, "1y") ?>>1년</option>
							</select>
						</span>
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
					<button type="submit" class="btn big green"><span>조회</span></button>
				</form>
			</div>
			<?php $limit = " limit $from_record, $rows "; ?>
			<div class="tab_cont">
				<!-- tab 1 -->
				<div class="tab_inner">
					<p class="txt_total">총 <strong><?php echo $total_count; ?></strong> 건</p>
					<?php
					include G5_MSHOP_PATH . '/orderinquiry.sub.php';
					?>

					<?php echo get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(function() {

		//날짜 버튼
		$("select[name='dateBtnOn']").change(function() {

			var d = $(this).val();

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
include_once(G5_MSHOP_PATH . '/_tail.php');
?>