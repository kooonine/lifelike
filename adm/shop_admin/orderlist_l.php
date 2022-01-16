<?php
$where = array();

$doc = strip_tags($doc);
$sort1 = in_array($sort1, array('od_id', 'od_cart_price', 'od_receipt_price', 'od_cancel_price', 'od_misu', 'od_cash')) ? $sort1 : '';
$sort2 = in_array($sort2, array('desc', 'asc')) ? $sort2 : 'desc';
$sel_field = get_search_string($sel_field);
if (!in_array($sel_field, array('od_id', 'mb_id', 'od_name', 'od_tel', 'od_hp', 'od_b_name', 'od_b_tel', 'od_b_hp', 'od_deposit_name', 'od_invoice'))) {   //검색할 필드 대상이 아니면 값을 제거
	$sel_field = '';
}
$od_cart_price = get_search_string($od_cart_price);
$od_status = get_search_string($od_status);
$search = get_search_string($search);

$sql_search = "";
if ($search != "") {
	if ($sel_field != "") {
		$where[] = " a.$sel_field like '%$search%' ";
	}

	if ($save_search != $search) {
		$page = 1;
	}
}

if ($sc_od_time != "") {
	$sc_od_times = explode("~", $sc_od_time);
	$fr_date = trim($sc_od_times[0]);
	$to_date = trim($sc_od_times[1]);
}

if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$where[] = " a.od_type = '$od_type' ";

if ($od_cart_price) {
	if ($od_cart_price == '무료세탁') $where[] = " od_cart_price = '0' ";
	if ($od_cart_price == '유료세탁') $where[] = " od_cart_price != '0' ";
}



if ($od_status) {
	$where[] = " a.od_status = '$od_status' ";

	switch ($od_status) {
		case '세탁신청':   // 결제완료
			$sort1 = "od_receipt_time";
			$sort2 = "desc";
			break;
		case '수거중':   // 배송중
			$sort1 = "od_pickup_invoice_time";
			$sort2 = "desc";
		case '배송중':   // 배송중
			$sort1 = "od_invoice_time";
			$sort2 = "desc";
			break;
	}
}

if ($sel_date == "") $sel_date = "od_time";
if ($fr_date && $to_date) {
	$where[] = " $sel_date between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
}

if ($where) {
	$sql_search = ' where ' . implode(' and ', $where);
}

if ($sel_field == "")  $sel_field = "od_id";
if ($sort1 == "") $sort1 = "od_id";
if ($sort2 == "") $sort2 = "desc";

$sql_common = " from {$g5['g5_shop_order_table']} a $sql_search ";

$sql = " select count(od_id) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) {
	$page = 1;
} // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql  = " select *,
(od_cart_coupon + od_coupon + od_send_coupon) as couponprice
$sql_common
order by $sort1 $sort2
limit $from_record, $rows ";
$result = sql_query($sql);



$excel_sql = "select a.*, b.it_id, b.it_name, b.ct_option, b.ct_qty, (b.ct_price + b.io_price)  as opt_price, b.ct_status, b.ct_status_claim, b.rf_serial
                     ,concat(a.od_type,'-',left(a.od_id,8),'-',right(a.od_id,6)) as disp_od_id
                    ,concat(' ',od_boxsend_invoice) as str_od_boxsend_invoice
                    ,concat(' ',od_pickup_invoice) as str_od_pickup_invoice
                    ,concat(' ',od_invoice) as str_od_invoice
                    ,if(b.ct_free_laundry_use = '1','무료','유료') ct_free_laundry
                from lt_shop_order as a
                     left join lt_shop_cart as b
                        on a.od_id = b.od_id and b.io_type = '0'
                $sql_search
                order by a.$sort1 $sort2, b.io_type asc, b.ct_id asc";

if (substr_count($sql, "limit")) {
	$sqls = explode('limit', $excel_sql);
	$excel_sql = $sqls[0];
}

$headers = array('NO', '주문일시', '주문번호', '주문상태', '세탁구분', '클레임상태', '주문자아이디', '주문자명', '제품코드', '제품명', '옵션항목', 'RFID', '결제수단', '결제금액', '박스운송장번호', '수거운송장번호', '고객운송장번호', '메모');
$bodys = array('NO', 'od_time', 'disp_od_id', 'od_status', 'ct_free_laundry', 'ct_status_claim', 'mb_id', 'od_name', 'it_id', 'it_name', 'ct_option', 'rf_serial', 'od_settle_case', 'od_receipt_price', 'str_od_boxsend_invoice', 'str_od_pickup_invoice', 'str_od_invoice', 'od_memo');

$enc = new str_encrypt();

$excel_sql = $enc->encrypt($excel_sql);
$headers = $enc->encrypt(json_encode_raw($headers));
$bodys = $enc->encrypt(json_encode_raw($bodys));

$qstr1 = "od_type=" . urlencode($od_type) . "&amp;od_status=" . urlencode($od_status) . "&amp;od_settle_case=" . urlencode($od_settle_case) . "&amp;od_misu=$od_misu&amp;od_cancel_price=$od_cancel_price&amp;od_refund_price=$od_refund_price&amp;od_receipt_point=$od_receipt_point&amp;od_coupon=$od_coupon&amp;fr_date=$fr_date&amp;to_date=$to_date&amp;sel_field=$sel_field&amp;search=$search&amp;save_search=$search";
$qstr = "$qstr1&amp;sort1=$sort1&amp;sort2=$sort2&amp;page=$page";

$token = get_admin_token();
?>

<form name="frmorderlist" class="local_sch01 local_sch" onsubmit="$('#page').val('1');" method="get" id ='frmorderlist'>
	<input type="hidden" name="doc" value="<?= $doc; ?>">
	<input type="hidden" name="sort1" value="<?= $sort1; ?>">
	<input type="hidden" name="sort2" value="<?= $sort2; ?>">
	<input type="hidden" name="page" value="<?= $page; ?>">
	<input type="hidden" name="save_search" value="<?= $search; ?>">
	<input type="hidden" name="od_type" value="<?= $od_type; ?>">
	<div class="tbl_frm01 tbl_wrap">
		<table>
			<colgroup>
				<col class="grid_4">
				<col>
				<col class="grid_3">
			</colgroup>

			<tr>
				<th scope="row">기간일자</th>
				<td colspan="2">
					<div class="col-lg-1 col-md-3 col-sm-12 col-xs-12">
						<select name="sel_date" id="sel_date">
							<option value="od_time" <?= get_selected($sel_date, 'od_time'); ?>>&nbsp;&nbsp;주문일&nbsp;&nbsp;</option>
							<option value="od_receipt_time" <?= get_selected($sel_date, 'od_receipt_time'); ?>>&nbsp;&nbsp;결제일&nbsp;&nbsp;</option>
						</select>
					</div>
					<div class="col-lg-5 col-md-3 col-sm-12 col-xs-12">
						<input type='text' class="form-control" id="sc_od_time" name="sc_od_time" value="" />
						<i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 10px;right: 24px;top: auto;cursor: pointer;"></i>
					</div>
					<div class="btn-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<button type="button" class="btn btn_02" name="dateBtn" data="all">전체</button>
						<button type="button" class="btn btn_02" name="dateBtn" data="today">오늘</button>
						<button type="button" class="btn btn_02" name="dateBtn" data="3d">3일</button>
						<button type="button" class="btn btn_02" name="dateBtn" data="1w">1주</button>
						<button type="button" class="btn btn_02" name="dateBtn" data="1m">1개월</button>
						<button type="button" class="btn btn_02" name="dateBtn" data="3m">3개월</button>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row">제품명</th>
				<td colspan="2">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<input type="text" name="sc_it_name" value="<?= $sc_it_name; ?>" id="sc_it_name" class="frm_input" size="60" autocomplete="off">
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row">검색항목</th>
				<td colspan="2">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<select name="sel_field" id="sel_field">
							<option value="od_id" <?= get_selected($sel_field, 'od_id'); ?>>주문번호</option>
							<option value="mb_id" <?= get_selected($sel_field, 'mb_id'); ?>>회원 ID</option>
							<option value="od_name" <?= get_selected($sel_field, 'od_name'); ?>>주문자</option>
							<option value="od_tel" <?= get_selected($sel_field, 'od_tel'); ?>>주문자전화</option>
							<option value="od_hp" <?= get_selected($sel_field, 'od_hp'); ?>>주문자핸드폰</option>
							<option value="od_b_name" <?= get_selected($sel_field, 'od_b_name'); ?>>받는분</option>
							<option value="od_b_tel" <?= get_selected($sel_field, 'od_b_tel'); ?>>받는분전화</option>
							<option value="od_b_hp" <?= get_selected($sel_field, 'od_b_hp'); ?>>받는분핸드폰</option>
							<option value="od_deposit_name" <?= get_selected($sel_field, 'od_deposit_name'); ?>>입금자</option>
							<option value="od_invoice" <?= get_selected($sel_field, 'od_invoice'); ?>>운송장번호</option>
						</select>

						<label for="search" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
						<input type="text" name="search" value="<?= $search; ?>" id="search" class="frm_input" autocomplete="off">
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row">세탁구분</th>
				<td colspan="2">
					<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
						<input type="radio" name="od_cart_price" value="" id="od_misu01" <?= get_checked($od_cart_price, ''); ?>>
						<label for="od_misu01">전체</label>
						<input type="radio" name="od_cart_price" value="무료세탁" id="od_misu02" <?= get_checked($od_cart_price, '무료세탁'); ?>>
						<label for="od_misu02">무료세탁</label>
						<input type="radio" name="od_cart_price" value="유료세탁" id="od_misu03" <?= get_checked($od_cart_price, '유료세탁'); ?>>
						<label for="od_misu03">유료세탁</label>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row">주문상태</th>
				<td colspan="2">
					<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
						<input type="radio" name="od_status" value="" id="od_status_all" <?= get_checked($od_status, '');  ?>>
						<label for="od_status_all">전체</label>

						<input type="radio" name="od_status" value="세탁신청" id="od_status_odr" <?= get_checked($od_status, '세탁신청'); ?>>
						<label for="od_status_odr">세탁신청</label>
						<input type="radio" name="od_status" value="수거박스배송" id="od_status_income" <?= get_checked($od_status, '수거박스배송'); ?>>
						<label for="od_status_income">박스배송</label>
						<input type="radio" name="od_status" value="박스배송완료" id="od_status_income2" <?= get_checked($od_status, '박스배송완료'); ?>>
						<label for="od_status_income2">박스배송완료</label>
						<input type="radio" name="od_status" value="수거중" id="od_status_rdy" <?= get_checked($od_status, '수거중'); ?>>
						<label for="od_status_rdy">수거중</label>
						<input type="radio" name="od_status" value="수거완료" id="od_status_rdy2" <?= get_checked($od_status, '수거완료'); ?>>
						<label for="od_status_rdy2">수거완료</label>
						<input type="radio" name="od_status" value="세탁중" id="od_status_l" <?= get_checked($od_status, '세탁중'); ?>>
						<label for="od_status_l">세탁중</label>
						<input type="radio" name="od_status" value="세탁반려" id="od_status_l2" <?= get_checked($od_status, '세탁반려'); ?>>
						<label for="od_status_l2">세탁반려</label>
						<input type="radio" name="od_status" value="배송중" id="od_status_dvr" <?= get_checked($od_status, '배송중'); ?>>
						<label for="od_status_dvr">배송중</label>
						<input type="radio" name="od_status" value="세탁완료" id="od_status_done" <?= get_checked($od_status, '세탁완료'); ?>>
						<label for="od_status_done">세탁완료</label>
					</div>
				</td>
			</tr>
		</table>
	</div>

	<div class="form-group">
		<div class="col-md-12 col-sm-12 col-xs-12 text-center">
			<button class="btn btn_02" type="reset" id="btn_clear">초기화</button>
			<button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i>검색</button>
		</div>
	</div>
</form>


<div class="local_ov01 local_ov">
	<?= $listall; ?>
	<span class="btn_ov01">[ 검색결과 <?= number_format($total_count); ?>건 ]</span>
</div>

<form name="forderlist" id="forderlist" method="post" autocomplete="off">
	<input type="hidden" name="search_od_status" value="<?= $od_status; ?>">
	<input type="hidden" name="od_status" id="post_od_status">
	<input type="hidden" name="token" value="<?= $token ?>">

	<div class="local_cmd01 local_cmd">
		<div style="float: left">
			<input type="button" value="세탁취소(CS)" class="btn btn_02" onclick="forderlist_submit('세탁취소');">
			<input type="button" value="세탁처리(CS)" class="btn btn_02" onclick="forderlist_submit('세탁처리');">
		</div>
		<div style="float: right">
			<input type="text" name="od_delivery_company" id="delivery_company" value="CJ대한통운">
			<input type="text" name="od_invoice" id="invoice" value="346046214716">
			<input type="text" name="od_invoice_time" id="invoice_time" value="<?= G5_TIME_YMDHIS ?>">
			<input type="button" value="T:박스배송완료" class="btn btn_01" onclick="forderlist_submit('박스배송완료');">
			<input type="button" value="T:수거요청" class="btn btn_01" onclick="forderlist_submit('수거중');">
			<input type="button" value="T:수거완료" class="btn btn_01" onclick="forderlist_submit('수거완료');">

			<input type="button" value="T:배송중" class="btn btn_01" onclick="forderlist_submit('배송중');">
			<input type="button" value="T:배송완료" class="btn btn_01" onclick="forderlist_submit('배송완료');">

			<input type="button" value="세탁주문확인" class="btn btn_02" onclick="forderlist_submit('주문확인');">
			<input type="button" value="엑셀다운로드" class="btn btn_02" id="excel_download1">
		</div>
	</div>

	<div class="tbl_head01 tbl_wrap">
		<table id="sodr_list">
			<caption>주문 내역 목록</caption>
			<thead>
				<tr>
					<th scope="col">
						<label for="chkall" class="sound_only">주문 전체</label>
						<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
					</th>
					<th scope="col">주문일시<br /><a href="<?= title_sort("od_id", 1) . "&amp;$qstr1"; ?>">주문번호</a></th>
					<th scope="col">주문상태<br />(세탁구분/클레임상태)</th>

					<th scope="col">제품 이미지</th>
					<th scope="col">제품정보</th>

					<th scope="col">주문자명(ID)</th>

					<th scope="col">결제수단</th>
					<th scope="col">결제금액</th>
					<th scope="col">운송장정보</th>
					<th scope="col">관리</th>
				</tr>
			</thead>
			<tbody>
				<?php
				for ($i = 0; $row = sql_fetch_array($result); $i++) {
					// 결제 수단
					$s_receipt_way = $s_br = "";
					if ($row['od_settle_case']) {
						$s_receipt_way = $row['od_settle_case'];
						$s_br = '<br />';

						// 간편결제
						if ($row['od_settle_case'] == '간편결제') {
							switch ($row['od_pg']) {
								case 'lg':
									$s_receipt_way = 'PAYNOW';
									break;
								case 'inicis':
									$s_receipt_way = 'KPAY';
									break;
								case 'kcp':
									$s_receipt_way = 'PAYCO';
									break;
								default:
									$s_receipt_way = $row['od_settle_case'];
									break;
							}
						}
					} else {
						$s_receipt_way = '결제수단없음';
						$s_br = '<br />';
					}

					$mb_nick = get_sideview($row['mb_id'], get_text($row['od_name']), $row['od_email'], '');

					// 주문 번호에 device 표시
					$od_mobile = '';
					if ($row['od_mobile'])
						$od_mobile = '(M)';


					$disp_od_id = $row['od_type'] . '-' . substr($row['od_id'], 0, 8) . '-' . substr($row['od_id'], 8, 6);

					// 주문 번호에 에스크로 표시
					$od_paytype = '';
					if ($row['od_test'])
						$od_paytype .= '<span class="list_test">테스트</span>';

					if ($default['de_escrow_use'] && $row['od_escrow'])
						$od_paytype .= '<span class="list_escrow">에스크로</span>';

					$uid = md5($row['od_id'] . $row['od_time'] . $row['od_ip']);

					$invoice_time = is_null_time($row['od_invoice_time']) ? G5_TIME_YMDHIS : $row['od_invoice_time'];
					$delivery_company = $row['od_delivery_company'] ? $row['od_delivery_company'] : $default['de_delivery_company'];

					$bg = 'bg' . ($i % 2);
					$td_color = 0;
					if ($row['od_cancel_price'] > 0) {
						$bg .= 'cancel';
						$td_color = 1;
					}

					$odsql = " select *
					from {$g5['g5_shop_cart_table']}
					where od_id = '" . $row['od_id'] . "'
					and io_type = '0'
					order by io_type asc, ct_id asc
					";

					$opt = sql_fetch($odsql);

					// 제품이미지
					$image = get_it_image($opt['it_id'], 50, 50);
					$rowspan = 1;
				?>
					<tr>
						<td class="td_chk" rowspan="<?= $rowspan ?>">
							<input type="hidden" name="od_id[<?= $i ?>]" value="<?= $row['od_id'] ?>" id="od_id_<?= $i ?>">
							<input type="checkbox" name="chk[]" value="<?= $i ?>" id="chk_<?= $i ?>">
						</td>
						<td rowspan="<?= $rowspan ?>">
							<label class="sound_only">주문일시</label>
							<?= $row['od_time'] ?><br />
							<label class="sound_only">주문번호</label>
							<a href="./orderform.php?od_id=<?= $row['od_id']; ?>&amp;<?= $qstr; ?>"><?= $disp_od_id; ?></a>

							<label class="sound_only">주문통합상태</label>
							<input type="hidden" name="current_status[<?= $i ?>]" value="<?= $row['od_status'] ?>">
						</td>

						<td headers="odrstat">
							<label class="sound_only">주문상태</label>
							<?= $opt['ct_status']; ?><br />
							<label class="sound_only">세탁구분</label>
							(<?= ($opt['ct_free_laundry_use']) ? "무료" : "유료"; ?><?= ($opt['ct_status_claim'] != '') ? '/' . $opt['ct_status_claim'] : '' ?>)
						</td>
						<td><?= $image; ?></td>
						<td headers="td_name" class="td_itopt_tl">
							<label>제품코드 : </label>
							<?= $opt['it_id'] ?><br />
							<label>제품명 : </label>
							<a href="./itemform.php?w=u&amp;it_id=<?= $opt['it_id']; ?>"><?= stripslashes($opt['it_name']); ?></a>
							<?= ($opt['its_order_no']) ? "<br/>옵션 : " . $opt['its_order_no'] : "" ?>
							<?= ($opt['rf_serial']) ? "<br/>RFID : " . $opt['rf_serial'] : "" ?>
							<br />

						</td>


						<td headers="th_odrer" rowspan="<?= $rowspan ?>">
							<label class="sound_only">주문자명</label>
							<?= $mb_nick; ?>(<?= $row['mb_id']; ?>)
							<br /><?= get_text($row['od_tel']); ?>
						</td>

						<td headers="odrpay" rowspan="<?= $rowspan ?>">
							<label class="sound_only">결재수단</label>
							<input type="hidden" name="current_settle_case[<?= $i ?>]" value="<?= $row['od_settle_case'] ?>">
							<?= $s_receipt_way; ?>
						</td>

						<td rowspan="<?= $rowspan ?>">
							<label class="sound_only">결제금액</label>
							<?= number_format($row['od_receipt_price']); ?> 원
						</td>

						<td class="td_id td_itopt_tl">
							<label class="sound_only">박스 운송장번호</label>
							<? if ($row['od_boxsend_invoice']) { ?>
								<a href='<?php echo G5_URL ?>/common/tracking.php?invc_co=롯데택배&invc_no=<?= $row['od_boxsend_invoice'] ?>&view_popup=1' target='_blank' class="od_invoice">
									박스 : <?= $row['od_boxsend_invoice'] ? $row['od_boxsend_invoice'] : '-' ?><br />
								</a>
							<? } ?>
							<label class="sound_only">수거 운송장번호</label>
							<? if ($row['od_pickup_invoice']) { ?>
								<a href='<?php echo G5_URL ?>/common/tracking.php?invc_co=<?= $row['od_pickup_delivery_company'] ?>&invc_no=<?= $row['od_pickup_invoice'] ?>&view_popup=1' target='_blank' class="od_invoice">
									수거 : <?= $row['od_pickup_invoice'] ? $row['od_pickup_invoice'] : '-' ?><br />
								</a>
							<? } ?>
							<label class="sound_only">고객 운송장번호</label>
							<? if ($row['od_invoice']) { ?>
								<a href='<?php echo G5_URL ?>/common/tracking.php?invc_co=<?= $row['od_delivery_company'] ?>&invc_no=<?= $row['od_invoice'] ?>&view_popup=1' target='_blank' class="od_invoice">
									고객 : <?= $row['od_invoice'] ? $row['od_invoice'] : '-' ?><br />
								</a>
							<? } ?>
						</td>

						<td rowspan="<?= $rowspan ?>">
							<label class="sound_only">메모</label>
							<?php if ($row['od_memo'] != '') { ?>
								<input type="button" value="USER" class="btn btn_01 od_memo" od_memo='<?= $row['od_memo'] ?>'>
							<?php } else { ?>
								<input type="button" value="USER" class="btn">
							<?php } ?>

							<input type="button" value="상세" class="btn btn_01 orderitem" od_id='<?= $row['od_id'] ?>'>
						</td>
					</tr>
				<?php
				}
				sql_free_result($result);
				if ($i == 0)
					echo '<tr><td colspan="20" class="empty_table">자료가 없습니다.</td></tr>';
				?>
			</tbody>
		</table>
	</div>

	<div class="local_cmd01 local_cmd">
		<div style="float: left">
			<input type="button" value="세탁취소(CS)" class="btn btn_02" onclick="forderlist_submit('세탁취소');">
			<input type="button" value="세탁처리(CS)" class="btn btn_02" onclick="forderlist_submit('세탁처리');">
		</div>
		<div style="float: right">
			<a href="<?php echo G5_ADMIN_URL ?>/cron/cron_samjin_ordercheck.php" target="_blank"><input type="button" value="삼진동기화" class="btn btn_01"></a>
			<a href="<?php echo G5_ADMIN_URL ?>/cron/cron_invoice.php" target="_blank"><input type="button" value="택배동기화" class="btn btn_01"></a>
			<input type="button" value="세탁주문확인" class="btn btn_02" onclick="forderlist_submit('주문확인');">
			<input type="button" value="엑셀다운로드" class="btn btn_02" id="excel_download2">
		</div>
	</div>


</form>

<?= get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

<div id="modal_od_memo" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">구매자 메모</h4>
			</div>
			<div class="modal-body">
				<div class="tbl_frm01 tbl_wrap">
					<table>
						<tr>
							<th scope="row"><label id="od_memo"></label></th>
						</tr>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(function() {
		//$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });

		$("#excel_download1, #excel_download2").click(function() {
			var $form = $('<form></form>');
			$form.attr('action', '<?= G5_ADMIN_URL ?>/ajax.excel_download.php');
			$form.attr('method', 'post');
			$form.appendTo('body');

			var exceldata = $('<input type="hidden" value="<?= $excel_sql ?>" name="exceldata">');
			var headerdata = $('<input type="hidden" value="<?= $headers ?>" name="headerdata">');
			var bodydata = $('<input type="hidden" value="<?= $bodys ?>" name="bodydata">');
			$form.append(exceldata).append(headerdata).append(bodydata);
			$form.submit();
		});

		$('#sc_od_time').daterangepicker({
			"autoApply": true,
			"opens": "right",
			locale: {
				"format": "YYYY-MM-DD",
				"separator": " ~ ",
				"applyLabel": "선택",
				"cancelLabel": "취소",
				"fromLabel": "시작일자",
				"toLabel": "종료일자",
				"customRangeLabel": "직접선택",
				"weekLabel": "W",
				"daysOfWeek": ["일", "월", "화", "수", "목", "금", "토"],
				"monthNames": ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
				"firstDay": 1
			}
			/*,ranges: {
				   '오늘': [moment(), moment()],
				   '3일': [moment().subtract(2, 'days'), moment()],
				   '1주': [moment().subtract(6, 'days'), moment()],
				   '1개월': [moment().subtract(1, 'month'), moment()],
				   '3개월': [moment().subtract(3, 'month'), moment()],
				   '이번달': [moment().startOf('month'), moment().endOf('month')],
				   '마지막달': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				}*/
		});
		//alert($("button[name='dateBtn'].btn_03").attr("data"));
		<?php
		if ($fr_date != '') echo "$('#sc_od_time').val('" . $fr_date . " ~ " . $to_date . "');";
		else if ($sc_od_time != '') echo "$('#sc_od_time').val('" . $sc_od_time . "');";
		else echo "$('#sc_od_time').val('');";
		?>

		//날짜 버튼
		$("button[name='dateBtn']").click(function() {

			var d = $(this).attr("data");
			if (d == "all") {
				$('#sc_od_time').val("");
			} else {
				var startD = moment();
				var endD = moment();

				if (d == "3d") {
					startD = moment().subtract(2, 'days');
					endD = moment();

				} else if (d == "1w") {
					startD = moment().subtract(6, 'days');
					endD = moment();

				} else if (d == "1m") {
					startD = moment().subtract(1, 'month');
					endD = moment();

				} else if (d == "3m") {
					startD = moment().subtract(3, 'month');
					endD = moment();
				} else if (d == "today") {
					startD = moment();
					endD = moment();
				}

				$('#sc_od_time').data('daterangepicker').setStartDate(startD);
				$('#sc_od_time').data('daterangepicker').setEndDate(endD);
			}

		});

		// 제품처리보기
		$(".orderitem").on("click", function() {
			var od_id = $(this).attr("od_id");

			var url = "./orderproc.l.php?w=v&od_id=" + od_id;
			window.open(url, "orderform", "left=230,top=100,width=1000,height=650,scrollbars=yes,resizable=yes");

			return false;
		});

		// 제품리스트 닫기
		$(".orderitemlist-x").on("click", function() {
			$("#orderitemlist").remove();
		});

		$("body").on("click", function() {
			$("#orderitemlist").remove();
		});

		$(".od_memo").on("click", function() {
			var $this = $(this);
			var od_memo = $this.attr("od_memo");

			$("#od_memo").text(od_memo);
			$("#modal_od_memo").modal('show');

			return false;
		});

		$(".od_invoice").on("click", function() {
			var $this = $(this);
			var url = $this.attr("href");
			window.open(url, "invoice_view", "left=100,top=100,width=600,height=600,scrollbars=1");
			return false;
		});
		window.addEventListener("keydown", (e) => {
            if (e.keyCode == 13) {
                document.getElementById('frmorderlist').submit();
            }
        })

	});

	function forderlist_submit(change_status) {
		if (!is_checked("chk[]")) {
			alert(change_status + " 하실 항목을 하나 이상 선택하세요.");
			return false;
		}
		var $f = $("#forderlist");
		var $chk = $("input[name='chk[]']:checked");

		$("#post_od_status").val("");

		switch (change_status) {
			case "세탁취소":
				if ($chk.size() != 1) {
					alert("CS처리는 다중 선택할 수 없습니다.");
					return false;
				}
				var k = $($chk[0]).val();
				var current_status = $("input[name='current_status[" + k + "]']").val();

				if (current_status != "세탁신청") {
					alert("'세탁신청' 상태의 주문만 '세탁취소'가 가능합니다.");
					return false;
				}

				var url = "./orderpartcancel.php?od_id=" + $("input[name='od_id[" + k + "]']").val();
				window.open(url, "orderpartcancel", "left=230,top=100,width=1000,height=650,scrollbars=yes,resizable=yes");

				break;
			case "세탁처리":
				if ($chk.size() != 1) {
					alert("CS처리는 다중 선택할 수 없습니다.");
					return false;
				}
				var k = $($chk[0]).val();
				var current_status = $("input[name='current_status[" + k + "]']").val();

				if (current_status != "세탁중" && current_status != "세탁완료") {
					alert("'세탁중, 세탁완료' 상태의 주문만 '세탁처리'가 가능합니다.");
					return false;
				}

				var url = "./orderproc.l.php?od_id=" + $("input[name='od_id[" + k + "]']").val();
				window.open(url, "orderform", "left=230,top=100,width=1000,height=650,scrollbars=yes,resizable=yes");

				break;
			case "주문확인":

				for (var i = 0; i < $chk.size(); i++) {
					var k = $($chk[i]).val();
					var current_status = $("input[name='current_status[" + k + "]']").val();

					if (current_status != "세탁신청") {
						alert("'세탁신청' 상태의 주문만 '세탁주문확인'이 가능합니다.");
						return false;
					}
				}

				$("#post_od_status").val("수거박스배송");
				$("#forderlist").attr("action", "./orderlistupdate.php").submit();
				//f.action = "./orderlistupdate.php";

				return false;
				break;
			case "박스배송완료":

				for (var i = 0; i < $chk.size(); i++) {
					var k = $($chk[i]).val();
					var current_status = $("input[name='current_status[" + k + "]']").val();

					if (current_status != "수거박스배송") {
						alert("'수거박스배송' 상태의 주문만 '박스배송완료'이 가능합니다.");
						return false;
					}
				}

				$("#post_od_status").val("박스배송완료");
				$("#forderlist").attr("action", "./orderlistupdate.php").submit();
				return false;
				break;
			case "수거중":

				for (var i = 0; i < $chk.size(); i++) {
					var k = $($chk[i]).val();
					var current_status = $("input[name='current_status[" + k + "]']").val();

					if (current_status != "수거박스배송" && current_status != "박스배송완료") {
						alert("'수거박스배송','박스배송완료' 상태의 주문만 '수거중'으로 변경 가능합니다.");
						return false;
					}
				}

				$("#post_od_status").val("수거중");
				$("#forderlist").attr("action", "./orderlistupdate.php").submit();
				//f.action = "./orderlistupdate.php";

				return false;
				break;
			case "수거완료":

				for (var i = 0; i < $chk.size(); i++) {
					var k = $($chk[i]).val();
					var current_status = $("input[name='current_status[" + k + "]']").val();

					if (current_status != "수거중") {
						alert("'수거중' 상태의 주문만 '수거완료'가 가능합니다.");
						return false;
					}
				}

				$("#post_od_status").val("수거완료");
				$("#forderlist").attr("action", "./orderlistupdate.php").submit();
				//f.action = "./orderlistupdate.php";

				return false;
				break;
			case "배송중":

				for (var i = 0; i < $chk.size(); i++) {
					var k = $($chk[i]).val();
					var current_status = $("input[name='current_status[" + k + "]']").val();

					if (current_status != "세탁중") {
						alert("'세탁중' 상태의 주문만 '배송중'으로 변경 가능합니다.");
						return false;
					}
				}

				$("#post_od_status").val("배송중");
				$("#forderlist").attr("action", "./orderlistupdate.php").submit();
				//f.action = "./orderlistupdate.php";

				return false;
				break;
			case "배송완료":

				for (var i = 0; i < $chk.size(); i++) {
					var k = $($chk[i]).val();
					var current_status = $("input[name='current_status[" + k + "]']").val();

					if (current_status != "배송중") {
						alert("'배송중' 상태의 주문만 '배송완료'가 가능합니다.");
						return false;
					}
				}

				$("#post_od_status").val("배송완료");
				$("#forderlist").attr("action", "./orderlistupdate.php").submit();
				//f.action = "./orderlistupdate.php";

				return false;
				break;
		}
	}
</script>