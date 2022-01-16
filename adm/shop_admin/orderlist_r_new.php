<?
$where = array();

$doc = strip_tags($doc);
$sort1 = in_array($sort1, array('od_id', 'od_cart_price', 'od_receipt_price', 'od_cancel_price', 'od_misu', 'od_cash')) ? $sort1 : '';
$sort2 = in_array($sort2, array('desc', 'asc')) ? $sort2 : 'desc';
$sel_field = get_search_string($sel_field);
if (!in_array($sel_field, array('od_id', 'mb_id', 'od_name', 'od_tel', 'od_hp', 'od_b_name', 'od_b_tel', 'od_b_hp', 'od_deposit_name', 'od_invoice'))) {   //검색할 필드 대상이 아니면 값을 제거
	$sel_field = '';
}
$od_status = get_search_string($od_status);
$search = get_search_string($search);

$sql_search = "";
if ($search != "") {
	if ($sel_field != "") {
		$where[] = " r.$sel_field like '%$search%' ";
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

if ($od_status) {
	$where[] = " r.rt_status = '$od_status' ";

	switch ($od_status) {
		case '주문':
			$sort1 = "rt_id";
			$sort2 = "desc";
			break;
		case '계약등록':   // 계약등록
			$sort1 = "rt_receipt_time";
			$sort2 = "desc";
			break;
		case '배송중':   // 배송중
			$sort1 = "rt_invoice_time";
			$sort2 = "desc";
			break;
	}
}

/*
if ($sc_it_name) {
	$where[] = " a.od_id in (select od_id from {$g5['g5_shop_cart_table']} where it_name like '%$sc_it_name%') ";
}
*/

// dd($sel_date);
if ($sel_date == "" | $sel_date == 'od_time') $sel_date = "rt_time";
if ($fr_date && $to_date) {
	$where[] = " $sel_date BETWEEN '$fr_date 00:00:00' AND '$to_date 23:59:59' ";
}

if ($where) {
	$sql_search = ' WHERE ' . implode(' AND ', $where);
}

if ($sel_field == "")  $sel_field = "rt_id";
if ($sort1 == "") $sort1 = "rt_time";
if ($sort2 == "") $sort2 = "desc";

$sql_cnt_rental = "SELECT COUNT(*) as cnt FROM {$g5['g5_shop_rental_table']} AS r {$sql_search}";
$row = sql_fetch($sql_cnt_rental);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) {
	$page = 1;
} // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$excel_sql = "SELECT o.*, b.it_id, b.it_name, b.ct_option, b.ct_qty, (b.ct_rental_price + b.io_price)  as opt_price, b.ct_status, b.ct_status_claim, b.ct_item_rental_month
			,CONCAT(o.od_type,'-',LEFT(o.od_id,8),'-',RIGHT(o.od_id,6)) as disp_od_id
            ,CONCAT('T-',LEFT(r.rt_id,8),'-',RIGHT(r.rt_id,6)) as disp_rt_id
            ,CONCAT(od_delivery_company,' ',od_invoice) as str_od_invoice
			FROM {$g5['g5_shop_rental_table']} AS r
			JOIN {$g5['g5_shop_rental_order_table']} AS ro ON r.rt_id = ro.rt_id
			JOIN {$g5['g5_shop_order_table']} AS o ON ro.od_id = o.od_id
			LEFT JOIN {$g5['g5_shop_cart_table']} AS b ON o.od_id = b.od_id AND b.io_type = '0'
			$sql_search
            ORDER BY r.$sort1 $sort2, b.io_type ASC, b.ct_id ASC";

if (substr_count($sql, "limit")) {
	$sqls = explode('limit', $excel_sql);
	$excel_sql = $sqls[0];
}

// dd($excel_sql);

$headers = array('NO', '주문일시', '계약번호', '개별주문번호', '주문통합상태', '주문자아이디', '주문자명', '제품코드', '제품명', '옵션항목', '결제수단', '수량', '월이용료', '개월', '총계약금액', '운송장번호', '주문상태', '클레임상태', '메모');
$bodys = array('NO', 'od_time', 'disp_rt_id', 'disp_od_id', 'od_status', 'mb_id', 'od_name', 'it_id', 'it_name', 'ct_option', 'od_settle_case', 'ct_qty', 'opt_price', 'ct_item_rental_month', 'od_cart_price', 'str_od_invoice', 'ct_status', 'ct_status_claim', 'od_memo');

$enc = new str_encrypt();

$excel_sql = $enc->encrypt($excel_sql);
$headers = $enc->encrypt(json_encode_raw($headers));
$bodys = $enc->encrypt(json_encode_raw($bodys));

$qstr1 = "od_type=" . urlencode($od_type) . "&amp;od_status=" . urlencode($od_status) . "&amp;od_settle_case=" . urlencode($od_settle_case) . "&amp;od_misu=$od_misu&amp;od_cancel_price=$od_cancel_price&amp;od_refund_price=$od_refund_price&amp;od_receipt_point=$od_receipt_point&amp;od_coupon=$od_coupon&amp;fr_date=$fr_date&amp;to_date=$to_date&amp;sel_field=$sel_field&amp;search=$search&amp;save_search=$search";
if ($default['de_escrow_use'])
	$qstr1 .= "&amp;od_escrow=$od_escrow";
$qstr = "$qstr1&amp;sort1=$sort1&amp;sort2=$sort2&amp;page=$page";

$token = get_admin_token();
?>

<form name="frmorderlist" class="local_sch01 local_sch" onsubmit="$('#page').val('1');" method="get">
	<input type="hidden" name="doc" value="<?= $doc; ?>">
	<input type="hidden" name="sort1" value="<?= $sort1; ?>">
	<input type="hidden" name="sort2" value="<?= $sort2; ?>">
	<input type="hidden" name="page" id="page" value="<?= $page; ?>">
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
							<!-- <option value="od_receipt_time" <?= get_selected($sel_date, 'od_receipt_time'); ?>>&nbsp;&nbsp;결제일&nbsp;&nbsp;</option> -->
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
				<th scope="row">주문상태</th>
				<td colspan="2">
					<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
						<input type="radio" name="od_status" value="" id="od_status_all" <?= get_checked($od_status, '');     ?>>
						<label for="od_status_all">전체</label>

						<input type="radio" name="od_status" value="계약등록" id="od_status_odr" <?= get_checked($od_status, '계약등록'); ?>>
						<label for="od_status_odr">계약등록</label>
						<input type="radio" name="od_status" value="상품준비중" id="od_status_rdy" <?= get_checked($od_status, '상품준비중'); ?>>
						<label for="od_status_rdy">상품준비중</label>
						<input type="radio" name="od_status" value="배송중" id="od_status_dvr" <?= get_checked($od_status, '배송중'); ?>>
						<label for="od_status_dvr">배송중</label>
						<input type="radio" name="od_status" value="배송완료" id="od_status_done" <?= get_checked($od_status, '배송완료'); ?>>
						<label for="od_status_done">배송완료</label>
						<input type="radio" name="od_status" value="리스중" id="od_status_done2" <?= get_checked($od_status, '리스중'); ?>>
						<label for="od_status_done2">리스중</label>
						<input type="radio" name="od_status" value="리스완료" id="od_status_done3" <?= get_checked($od_status, '리스완료'); ?>>
						<label for="od_status_done3">리스완료</label>
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
			<input type="button" value="교환요청(CS)" class="btn btn_02" onclick="openClaimPopup(0);" style="display: none;">
			<input type="button" value="계약취소(CS)" class="btn btn_02" onclick="openClaimPopup(4);">
			<input type="button" value="철회요청(CS)" class="btn btn_02" onclick="openClaimPopup(5);">
			<input type="button" value="해지요청(CS)" class="btn btn_02" onclick="openClaimPopup(6);">
		</div>
		<div style="float: right">
			<input type="hidden" name="od_delivery_company" id="delivery_company" value="CJ대한통운">
			<input type="hidden" name="od_invoice_time" id="invoice_time" value="<?= G5_TIME_YMDHIS ?>">
			<!-- <input type="text" name="od_invoice" id="invoice" value=""> -->
			<input type="button" value="계약등록확인" class="btn btn_02" onclick="openClaimPopup(1);">
			<input type="button" value="배송중" class="btn btn_01" onclick="openClaimPopup(2);">
			<input type="button" value="배송완료" class="btn btn_01" onclick="openClaimPopup(3);">
			<input type="button" value="EXCEL" class="btn btn_02" id="excel_download1">
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
					<th scope="col">주문자명(ID)</th>
					<th scope="col">계약일시<br /><a href="<?= title_sort("od_id", 1) . "&amp;$qstr1"; ?>">계약번호</a></th>
					<th scope="col">주문번호</th>
					<th scope="col" colspan=2>주문 제품정보</th>
					<th scope="col">수량</th>
					<th scope="col">상품금액</th>
					<th scope="col">월이용료<br />(개월)</th>
					<th scope="col">총계약금액<br />(총월이용료)</th>
					<th scope="col">주문상태<br />(클레임상태)</th>
					<th scope="col">운송장정보</th>
					<th scope="col">메모</th>
				</tr>
			</thead>
			<tbody>
				<?
				$sql_rental_order_count = "SELECT ro.rt_id, COUNT(*) AS cnt FROM {$g5['g5_shop_rental_order_table']} AS ro JOIN {$g5['g5_shop_rental_table']} AS r ON ro.rt_id=r.rt_id {$sql_search} GROUP BY ro.rt_id";
				$db_rental_order_count = sql_query($sql_rental_order_count);
				$rental_count = array();
				while ($row = sql_fetch_array($db_rental_order_count)) $rental_count[$row['rt_id']] = array('count' => (int) $row['cnt'], 'current' => 1, 'rowspan' => 0);

				$sql_rental_order =
					"SELECT r.*, o.*
						FROM {$g5['g5_shop_rental_table']} AS r
						JOIN {$g5['g5_shop_rental_order_table']} AS ro ON r.rt_id = ro.rt_id
						JOIN {$g5['g5_shop_order_table']} AS o ON ro.od_id = o.od_id
						{$sql_search}
						ORDER BY r.rt_id DESC
						";

				$rental_order = sql_query($sql_rental_order);

				for ($i = 0; $row = sql_fetch_array($rental_order); $i++) {
					$mb_nick = get_sideview($row['mb_id'], get_text($row['od_name']), $row['od_email'], '');
					$disp_rt_id = 'T-' . substr($row['rt_id'], 0, 8) . '-' . substr($row['rt_id'], 8, 6);
					$disp_od_id = $row['od_type'] . '-' . substr($row['od_id'], 0, 8) . '-' . substr($row['od_id'], 8, 6);
					$uid = md5($row['od_id'] . $row['od_time'] . $row['od_ip']);

					$invoice_time = is_null_time($row['od_invoice_time']) ? G5_TIME_YMDHIS : $row['od_invoice_time'];
					$delivery_company = $row['od_delivery_company'] ? $row['od_delivery_company'] : $default['de_delivery_company'];

					$bg = 'bg' . ($i % 2);
					$td_color = 0;
					if ($row['od_cancel_price'] > 0) {
						$bg .= 'cancel';
						$td_color = 1;
					}

					$odcols = array(
						'it_id',
						'it_name',
						'its_sap_code',
						'its_order_no',
						'ct_id',
						'it_id',
						'ct_price',
						'ct_qty',
						'ct_option',
						'ct_status',
						'cp_price',
						'ct_send_cost',
						'io_type',
						'io_price',
						'io_sapcode_color_gz',
						'rf_serial',
						'ct_status_claim',
						'ct_rental_price',
						'ct_item_rental_month'
					);
					$odsql = "SELECT " . implode(',', $odcols) . ",(SELECT GROUP_CONCAT(od_sub_id) FROM {$g5['g5_shop_order_item_table']} WHERE lt_shop_order_item.ct_id = lt_shop_cart.ct_id ) AS od_sub_id
										FROM {$g5['g5_shop_cart_table']}
										WHERE od_id = '{$row['od_id']}'
										AND io_type = '0'
										ORDER BY io_type ASC, ct_id ASC";

					$od = sql_query($odsql);
					$rowspan = sql_num_rows($od);
					$order_html = array();
					$row_html = array();

					for ($k = 0; $opt = sql_fetch_array($od); $k++) {
						$rental_count[$row['rt_id']]['rowspan']++;
						$item_options = get_item_option($opt['it_id']);

						// 제품이미지
						$image = get_it_image($opt['it_id'], 50, 50);
						$opt_price = $opt['ct_rental_price'] + $opt['io_price'];

						if ($k === 0) {
							$row_html[] = "<td rowspan='{$rowspan}'>
										<label class='sound_only'>주문번호</label>
										<a href='./rentalform.php?rt_id={$row['rt_id']}'>{$disp_od_id}</a>
										<label class='sound_only'>주문통합상태</label>
										<input type='hidden' name='current_status[{$i}]' value='{$row['od_status']}'>
									</td>";
						}
						$row_html[] = "<td>{$image}</td>";
						$row_html[] = "<td class='td_itopt_tl'>
									<label>제품코드 : </label>
									{$opt['it_id']}<br />
									<label>제품명 : </label>
									<a href='./itemform.php?w=u&amp;it_id={$opt['it_id']}'>" . stripslashes($opt['it_name']) . "</a><br />
									{$item_options[$opt['io_sapcode_color_gz']]['item']} - {$opt['ct_option']}
								</td>";
						$row_html[] = "<td headers='odrstat'>
									<label class='sound_only'>수량</label>
									{$opt['ct_qty']}
								</td>";
						$row_html[] = "<td>
									<label class='sound_only'>상품 금액</label>
									" . number_format($opt['ct_rental_price'] * $opt['ct_item_rental_month']) . "원
								</td>";
						$row_html[] = "<td>
									<label class='sound_only'>월이용료</label>
									" . number_format($opt['ct_rental_price']) . "원<br />({$opt['ct_item_rental_month']}개월)
								</td>";

						if ($rental_count[$row['rt_id']]['current'] === 1) {
							$row_html[] = "<td rowspan='{$rental_count[$row['rt_id']]['count']}'>
										<label class='sound_only'>총계약금액</label>
										" . number_format($row['od_cart_price']) . "원<br />(" . number_format($row['od_cart_price'] / $opt['ct_item_rental_month']) . "원)
									</td>";
						}

						$row_html[] = "<td headers='odrstat' class='odrstat'>
									<label class='sound_only'>주문상태</label>{$opt['ct_status']}
									<label class='sound_only'>클레임상태</label>";
						$row_html[] = !empty($opt['ct_status_claim']) ? "<br />({$opt['ct_status_claim']})" : "";
						$row_html[] = "</td>";

						if (!empty($row['od_invoice'])) {
							$row_html[] = "<td headers='delino' class='delino'>
									<label class='sound_only'>운송장번호</label>
										<a href='" . G5_URL . "/common/tracking.php?invc_co={$row['od_delivery_company']}&invc_no={$row['od_invoice']}&view_popup=1' target='_blank' class='od_invoice'>";
							$row_html[] = empty($row['od_delivery_company']) ? "-" : $row['od_delivery_company'];
							$row_html[] = "<br />{$row['od_invoice']}</a></td>";
						} else {
							$row_html[] = "<td headers='delino' class='delino'>-</td>";
						}
						if ($rental_count[$row['rt_id']]['current'] === 1) {
							$row_html[] = "<td rowspan='{$rental_count[$row['rt_id']]['count']}'>
										<label class='sound_only'>메모</label>";
							$row_html[] = !empty($row['od_memo']) ? "<input type='button' value='USER' class='btn btn_01 od_memo' od_memo='{$row['od_memo']}'>" : "<input type='button' value='USER' class='btn'>";
							$row_html[] = "</td>";
						}

						$order_html[] = implode('', $row_html);
					}

					foreach ($order_html as $oh) {
						echo "<tr>";
						if ($rental_count[$row['rt_id']]['current'] === 1) {
							echo "<td rowspan={$rental_count[$row['rt_id']]['count']}>
								<input type='hidden' name='rt_id[{$i}]' value='{$row['rt_id']}' id='rt_id_{$i}'>
									<input type='checkbox' name='chk[]' value='{$i}' id='chk_{$i}'></td>
									<td rowspan={$rental_count[$row['rt_id']]['count']}>{$mb_nick}<br />({$row['mb_id']})</td><td rowspan={$rental_count[$row['rt_id']]['count']}>{$row['rt_time']}<br /><a href='./rentalform.php?rt_id={$row['rt_id']}'>{$disp_rt_id}</a></td>";
							$rental_count[$row['rt_id']]['current']++;
						}
						echo $oh;
						echo "</tr>";
					}
				}

				sql_free_result($rental_order);
				if ($i == 0)
					echo "<tr><td colspan='19' class='empty_table'>자료가 없습니다.</td></tr>";
				?>
			</tbody>
		</table>
	</div>
	<div class="local_cmd01 local_cmd">
		<div style="float: left">
			<input type="button" value="교환요청(CS)" class="btn btn_02" onclick="openClaimPopup(0);" style="display: none;">
			<input type="button" value="계약취소(CS)" class="btn btn_02" onclick="openClaimPopup(4);">
			<input type="button" value="철회요청(CS)" class="btn btn_02" onclick="openClaimPopup(5);">
			<input type="button" value="해지요청(CS)" class="btn btn_02" onclick="openClaimPopup(6);">
		</div>
		<div style="float: right">
			<!-- <input type="button" value="TEST:리스완료" class="btn btn_01" onclick="openClaimPopup('리스완료');"> -->
			<input type="button" value="계약등록확인" class="btn btn_02" onclick="openClaimPopup(1);">
			<input type="button" value="배송중" class="btn btn_01" onclick="openClaimPopup(2);">
			<input type="button" value="배송완료" class="btn btn_01" onclick="openClaimPopup(3);">
			<a href="<?php echo G5_ADMIN_URL ?>/cron/cron_samjin_ordercheck.php" target="_blank"><input type="button" value="삼진동기화" class="btn btn_01"></a>
			<a href="<?php echo G5_ADMIN_URL ?>/cron/cron_invoice.php" target="_blank"><input type="button" value="택배동기화" class="btn btn_01"></a>

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

<div id="modal-claim" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
	<div class="modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<form name='frm-claim' method='POST' autocomplete="off" action="orderlistupdate_new.php">
				<input type="hidden" name="search_od_status" value="<?= $od_status; ?>">
				<input type="hidden" name="od_status" id="post_od_status">
				<input type="hidden" name="token" value="<?= $token ?>">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"></h4>
					<h4 class="modal-desc"></h4>
				</div>
				<div class="modal-body tbl_head01 tbl_wrap">
					<div class="tbl_frm01 tbl_wrap">
					</div>
				</div>
				<div class="modal-footer">
				</div>
			</form>
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
		<?
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
				}

				$('#sc_od_time').data('daterangepicker').setStartDate(startD);
				$('#sc_od_time').data('daterangepicker').setEndDate(endD);
			}

		});

		// 주문제품보기
		$(".orderitem").on("click", function() {
			var $this = $(this);
			var od_id = $this.text().replace(/[^0-9]/g, "");

			if ($this.next("#orderitemlist").size())
				return false;

			$("#orderitemlist").remove();

			$.post(
				"./ajax.orderitem.php", {
					od_id: od_id
				},
				function(data) {
					$this.after("<div id=\"orderitemlist\"><div class=\"itemlist\"></div></div>");
					$("#orderitemlist .itemlist")
						.html(data)
						.append("<div id=\"orderitemlist_close\"><button type=\"button\" id=\"orderitemlist-x\" class=\"btn_frmline\">닫기</button></div>");
				}
			);

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
                document.getElementById('forderlist').submit();
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
			case "계약취소":
				if ($chk.size() != 1) {
					alert("CS처리는 다중 선택할 수 없습니다.");
					return false;
				}
				var k = $($chk[0]).val();
				var current_status = $("input[name='current_status[" + k + "]']").val();

				/*if (current_status != "계약등록" && current_status != "상품준비중") {
					alert("'계약등록','상품준비중' 상태의 주문만 '계약취소'가 가능합니다.");
					return false;
				}*/
				if (current_status != "계약등록") {
					alert("'계약등록' 상태의 주문만 '계약취소'가 가능합니다.");
					return false;
				}

				var url = "./orderpartcancel.php?od_id=" + $("input[name='od_id[" + k + "]']").val();
				window.open(url, "orderpartcancel", "left=230,top=100,width=1000,height=650,scrollbars=yes,resizable=yes");

				break;
			case "교환요청":
				if ($chk.size() != 1) {
					alert("CS처리는 다중 선택할 수 없습니다.");
					return false;
				}
				var k = $($chk[0]).val();
				var current_status = $("input[name='current_status[" + k + "]']").val();

				if (current_status != "계약등록" && current_status != "배송완료") {
					alert("'계약등록, 배송완료' 상태의 주문만 '교환요청'이 가능합니다.");
					return false;
				}

				var url = "./orderpartchange.php?od_id=" + $("input[name='od_id[" + k + "]']").val();
				window.open(url, "orderpartchange", "left=230,top=100,width=1000,height=650,scrollbars=yes,resizable=yes");

				break;
			case "철회요청":
				if ($chk.size() != 1) {
					alert("CS처리는 다중 선택할 수 없습니다.");
					return false;
				}
				var k = $($chk[0]).val();
				var current_status = $("input[name='current_status[" + k + "]']").val();

				if (current_status != "배송완료") {
					alert("'배송완료' 상태의 주문만 '철회요청'이 가능합니다.");
					return false;
				}

				var url = "./orderpartreturn_r.php?od_id=" + $("input[name='od_id[" + k + "]']").val();
				window.open(url, "orderpartreturn", "left=230,top=100,width=1000,height=650,scrollbars=yes,resizable=yes");

				break;
			case "계약확인":

				for (var i = 0; i < $chk.size(); i++) {
					var k = $($chk[i]).val();
					var current_status = $("input[name='current_status[" + k + "]']").val();

					if (current_status != "계약등록") {
						alert("'계약등록' 상태의 주문만 '계약확인'이 가능합니다.");
						return false;
					}
				}

				$("#post_od_status").val("상품준비중");
				$("#forderlist").attr("action", "./orderlistupdate.php").submit();
				//f.action = "./orderlistupdate.php";

				return false;
				break;
			case "배송중":

				for (var i = 0; i < $chk.size(); i++) {
					var k = $($chk[i]).val();
					var current_status = $("input[name='current_status[" + k + "]']").val();

					if (current_status != "상품준비중") {
						alert("'상품준비중' 상태의 주문만 '배송중'으로 변경 가능합니다.");
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
			case "리스완료":

				for (var i = 0; i < $chk.size(); i++) {
					var k = $($chk[i]).val();
					var current_status = $("input[name='current_status[" + k + "]']").val();

					if (current_status != "리스중") {
						alert("'리스중' 상태의 주문만 '리스완료'가 가능합니다.");
						return false;
					}
				}

				$("#post_od_status").val("리스완료");
				$("#forderlist").attr("action", "./orderlistupdate.php").submit();
				//f.action = "./orderlistupdate.php";

				return false;
				break;
		}
	}

	function openClaimPopup(type) {
		var types = {
			1: {
				current: '계약등록',
				next: '계약확인'
			},
			2: {
				current: '상품준비중',
				next: '배송중'
			},
			3: {
				current: '배송중',
				next: '배송완료'
			},
			4: {
				current: '계약등록',
				next: '계약취소'
			},
			5: {
				current: '계약확인',
				next: '주문철회'
			},
			6: {
				current: '리스중',
				next: '해지요청'
			}
		};

		var $f = $("#forderlist");
		var $chk = $("input[name='chk[]']:checked");
		var k = $($chk[0]).val();
		var change_status = types[type].next;
		var current_status = $("input[name='current_status[" + k + "]']").val();

		if ($chk.size() != 1) {
			alert(change_status + " 하실 항목을 선택하세요(다중 선택 불가).");
			return false;
		}

		const rt_id = $("input[name='rt_id[" + k + "]']").val();

		$.get('ajax.claim.php?rt_id=' + rt_id + '&type=' + type, function(data) {
			const $modal = $("#modal-claim");
			const $data = JSON.parse(data);

			if ($data.error) {
				alert($data.error);
				return false;
			}

			$($modal).find(".modal-title").html($data.title);
			$($modal).find(".modal-desc").html($data.desc);
			$($modal).find(".modal-body").html($data.body);
			$($modal).find(".modal-footer").html($data.footer);

			$modal.modal('show');
		});
	}

	function claim_check_all() {
		// console.log($(".cb-claim").attr("checked"));
		$(".cb-claim").prop("checked", $(".cb-claim:first-child").prop("checked"));
	}


	function change_reason(elem) {
		const elemSelected = $(elem).find("option:checked");

		const claimPrice = number_format($("#claim-price").data('price'));

		$("#claim-reason").text(elemSelected.text());
		$("#claim-reason-text").val("");
		$("#claim-price").val(claimPrice);

		if (elemSelected.val() == 2) {
			$("#input-claim-reason").show();
			$("#claim-price").prop('disabled', false);
		} else {
			$("#input-claim-reason").hide();
			$("#claim-price").prop('disabled', true);
		}
	}
</script>