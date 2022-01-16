<?
$where = array();

$doc = strip_tags($doc);
$sort1 = in_array($sort1, array('od_id', 'od_cart_price', 'od_receipt_price', 'od_cancel_price', 'od_misu', 'od_cash')) ? $sort1 : '';
$sort2 = in_array($sort2, array('desc', 'asc')) ? $sort2 : 'desc';
$sel_field = get_search_string($sel_field);
if (!in_array($sel_field, array('od_id', 'mb_id', 'od_name', 'od_tel', 'od_hp', 'od_b_name', 'od_b_tel', 'od_b_hp', 'od_deposit_name', 'od_invoice'))) {   //검색할 필드 대상이 아니면 값을 제거
	$sel_field = '';
}
$od_settle_case = get_search_string($od_settle_case);
$od_status = get_search_string($od_status);
$search = get_search_string($search);

$numberOrder = 10;

if ($page_rows > 10 || $page_rows== "0") {
	$numberOrder = $page_rows;
}
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
$where[] = " (a.company_code is null or a.company_code = '') ";

if ($od_status) {
	$where[] = " (a.od_status = '$od_status' OR a.od_id in (select od_id from {$g5['g5_shop_cart_table']} where ct_status = '$od_status')) ";

	switch ($od_status) {
		case '주문':
			$sort1 = "od_time";
			$sort2 = "desc";
			break;
		case '결제완료':   // 결제완료
			$sort1 = "od_receipt_time";
			$sort2 = "desc";
			break;
		case '배송중':   // 배송중
			$sort1 = "od_invoice_time";
			$sort2 = "desc";
			break;
	}
}

if ($od_settle_case) {
	$where[] = " od_settle_case = '$od_settle_case' ";
}

if (isset($is_mb) && $is_mb != '') {
	if ($is_mb == '1') $where[] = " (a.mb_id is not null and a.mb_id != '') ";
	else if ($is_mb == '0') $where[] = " (a.mb_id is null or a.mb_id = '') ";
} else {
	$is_mb = '';
}

if ($sc_it_name) {
	$where[] = " a.od_id in (select od_id from {$g5['g5_shop_cart_table']} where it_name like '%$sc_it_name%') ";
}
if ($od_escrow) {
	$where[] = " od_escrow = 1 ";
}

if ($sel_date == "") $sel_date = "od_time";

if ($fr_date && $to_date) {
	$where[] = " $sel_date between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
}

if ($where) {
	$sql_search = ' where ' . implode(' and ', $where);
}

if ($sel_field == "")  $sel_field = "od_id";
if ($sort1 == "") $sort1 = "od_time";
if ($sort2 == "") $sort2 = "desc";

$sql_common = " from {$g5['g5_shop_order_table']} as a $sql_search ";

$sql = " select count(od_id) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];
if ($numberOrder =='0') {
	$numberOrder = $total_count;
}
$rows = $numberOrder;
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


$excel_sql = "SELECT a.*,b.ct_delivery_company,b.ct_invoice,b.ct_id, b.it_id, b.it_name, b.ct_option, b.ct_qty, (b.ct_price + b.io_price)  as opt_price, b.ct_status, b.ct_status_claim, concat( '(',a.od_b_zip1,a.od_b_zip2,')','  ',a.od_b_addr1,'  ', a.od_b_addr2,'  ',a.od_b_addr3) AS addrTotal
, concat(a.od_type,'-',left(a.od_id,8),'-',right(a.od_id,6)) as disp_od_id
, concat(od_delivery_company,' ',od_invoice) as str_od_invoice
, concat(ct_delivery_company,' ',ct_invoice) as str_ct_invoice
, (a.od_send_cost) AS send_cost
, (SELECT IF(SUM(c.cp_price) > 0, SUM(c.cp_price), 0) FROM lt_shop_coupon_log AS c WHERE c.od_id=a.od_id AND c.ct_id=b.ct_id) AS tot_it_cp_price
, c.io_order_no AS sap_code, REPLACE(c.io_sapcode_color_gz,'_','') AS io_sapcodecolorgz
, (od_coupon_cancel+od_cart_coupon_cancel) AS total_coupon_all, (od_coupon_ori+od_cart_coupon_ori) AS total_coupon_final
, od_receipt_price_ori,od_receipt_refund_price_ori, (od_receipt_price_ori-od_receipt_refund_price_ori) AS cancel_price_ori
, CASE 
	WHEN (ct_status ='주문취소') THEN 0
	WHEN (ct_status ='반품완료') THEN 0
ELSE ct_cart_coupon_price
END AS ct_cart_coupon_price
, CASE 
	WHEN (ct_status ='주문취소') THEN 0
	WHEN (ct_status ='반품완료') THEN 0
ELSE cp_price
END AS cp_price
, CASE 
	WHEN (ct_status ='주문취소') THEN 0
	WHEN (ct_status ='반품완료') THEN 0
ELSE ct_cart_price_ori
END AS ct_cart_price_ori

FROM lt_shop_order as a
LEFT JOIN lt_shop_cart as b
ON a.od_id = b.od_id and b.io_type = '0'
LEFT JOIN lt_shop_item_option as c ON b.it_id = c.it_id 
$sql_search
ORDER BY a.$sort1 $sort2, b.io_type asc, b.ct_id asc";

if (substr_count($sql, "limit")) {
	$sqls = explode('limit', $excel_sql);
	$excel_sql = $sqls[0];
}

$headers = array('NO', '주문일시', '주문번호', '카트번호', '주문상품번호', '주문통합상태', '주문자아이디', '주문자명', '주문자연락처', '수령인', '휴대폰번호', '주소', '제품코드','SAP코드', '제품명', '옵션항목', '옵션코드', '결제수단', '수량', '제품금액', '배송비','총쿠폰금액','최종쿠폰금액','장바구니쿠폰할인','상품쿠폰할인', '적립금사용','상품결제금액','총결제금액','최종결제금액','취소금액', '운송장번호', '주문상태', '클레임상태', '메모','디바이스');
$bodys = array('NO', 'od_time', 'od_id', 'ct_id', 'it_id', 'ct_status', 'mb_id', 'od_name', 'od_hp', 'od_b_name', 'od_b_hp', 'addrTotal','it_id','sap_code', 'it_name', 'ct_option','io_sapcodecolorgz', 'od_settle_case', 'ct_qty', 'opt_price', 'send_cost', 'total_coupon_all','total_coupon_final','ct_cart_coupon_price','cp_price', 'od_receipt_point','ct_cart_price_ori','od_receipt_price_ori','od_receipt_refund_price_ori','cancel_price_ori', 'str_ct_invoice', 'ct_status', 'ct_status_claim', 'od_memo','od_pcmobile');
$summaries = array('ct_qty', 'opt_price', 'send_cost');

$enc = new str_encrypt();

$excel_sql = $enc->encrypt($excel_sql);
$headers = $enc->encrypt(json_encode_raw($headers));
$bodys = $enc->encrypt(json_encode_raw($bodys));
$summaries = $enc->encrypt(json_encode_raw($summaries));


$qstr1 = "od_type=" . urlencode($od_type) . "&amp;od_status=" . urlencode($od_status) . "&amp;od_settle_case=" . urlencode($od_settle_case) . "&amp;od_misu=$od_misu&amp;od_cancel_price=$od_cancel_price&amp;od_refund_price=$od_refund_price&amp;od_receipt_point=$od_receipt_point&amp;od_coupon=$od_coupon&amp;fr_date=$fr_date&amp;to_date=$to_date&amp;sel_field=$sel_field&amp;search=$search&amp;save_search=$search&amp;page_rows=$page_rows";
if ($default['de_escrow_use'])
	$qstr1 .= "&amp;od_escrow=$od_escrow";
$qstr = "$qstr1&amp;sort1=$sort1&amp;sort2=$sort2&amp;page=$page";

$token = get_admin_token();
?>

<form id="orderMainTable" name="frmorderlist" class="local_sch01 local_sch" onsubmit="$('#page').val('1');" method="get">
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
							<option value="od_time" <?= get_selected($sel_date, 'od_time'); ?>>주문일</option>
							<option value="od_receipt_time" <?= get_selected($sel_date, 'od_receipt_time'); ?>>결제일</option>
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
				<th scope="row">결제수단</th>
				<td colspan="2">
					<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
						<input type="radio" name="od_settle_case" value="" id="od_settle_case01" <?= get_checked($od_settle_case, ''); ?>>
						<label for="od_settle_case01">전체</label>
						<input type="radio" name="od_settle_case" value="가상계좌" id="od_settle_case03" <?= get_checked($od_settle_case, '가상계좌'); ?>>
						<label for="od_settle_case03">가상계좌</label>
						<input type="radio" name="od_settle_case" value="계좌이체" id="od_settle_case04" <?= get_checked($od_settle_case, '계좌이체'); ?>>
						<label for="od_settle_case04">계좌이체</label>
						<input type="radio" name="od_settle_case" value="휴대전화" id="od_settle_case05" <?= get_checked($od_settle_case, '휴대전화');  ?>>
						<label for="od_settle_case05">휴대전화</label>
						<input type="radio" name="od_settle_case" value="신용카드" id="od_settle_case06" <?= get_checked($od_settle_case, '신용카드'); ?>>
						<label for="od_settle_case06">신용카드</label>
						<input type="radio" name="od_settle_case" value="간편결제" id="od_settle_case07" <?= get_checked($od_settle_case, '간편결제'); ?>>
						<label for="od_settle_case07">간편결제</label>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row">회원구분</th>
				<td colspan="2">
					<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
						<input type="radio" name="is_mb" value="" id="is_mb" <?= get_checked($is_mb, ''); ?>>
						<label for="is_mb">전체</label>
						<input type="radio" name="is_mb" value="1" id="is_mb1" <?= get_checked($is_mb, '1'); ?>>
						<label for="is_mb1">회원</label>
						<input type="radio" name="is_mb" value="0" id="is_mb0" <?= get_checked($is_mb, '0'); ?>>
						<label for="is_mb0">비회원</label>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row">주문상태</th>
				<td colspan="2">
					<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
						<input type="radio" name="od_status" value="" id="od_status_all" <?= get_checked($od_status, '');   ?>>
						<label for="od_status_all">전체</label>
						<input type="radio" name="od_status" value="결제완료" id="od_status_income" <?= get_checked($od_status, '결제완료'); ?>>
						<label for="od_status_income">결제완료</label>
						<input type="radio" name="od_status" value="상품준비중" id="od_status_rdy" <?= get_checked($od_status, '상품준비중'); ?>>
						<label for="od_status_rdy">상품준비중</label>
						<input type="radio" name="od_status" value="배송중" id="od_status_dvr" <?= get_checked($od_status, '배송중'); ?>>
						<label for="od_status_dvr">배송중</label>
						<input type="radio" name="od_status" value="배송완료" id="od_status_done" <?= get_checked($od_status, '배송완료'); ?>>
						<label for="od_status_done">배송완료</label>
						<input type="radio" name="od_status" value="구매완료" id="od_status_done2" <?= get_checked($od_status, '구매완료'); ?>>
						<label for="od_status_done2">구매완료</label>
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
	<div style="float: right">
		<select name="page_rows" onchange="$('#orderMainTable').submit();">
    	        <option value="10" <?= get_selected($page_rows, '10'); ?> >10개씩 보기</option>
    	        <option value="100" <?= get_selected($page_rows, '100'); ?> >100개씩 보기</option>
				<option value="500" <?= get_selected($page_rows, '500'); ?> >500개씩 보기</option>
				<option value="0" <?= get_selected($page_rows, '0'); ?> >전체 보기</option>

		</select>
	</div>
</form>

<div class="local_ov01 local_ov">
	<?= $listall; ?>
	<span class="btn_ov01">[ 검색결과 <?= number_format($total_count); ?>건 ]</span>
</div>

<!-- <form name="fitem" method="post" action="./orderExcel.php" enctype="multipart/form-data">
			
			<p>파일업로드</p>
			<p><input type="file" name="excelfile" id="excelfile" required="required" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"></p>
			<input type="submit" name="act_button" value="엑셀등록"  class="btn btn-success">
		</form> -->
<form name="fitem" method="post" action="./orderExcel.php" enctype="multipart/form-data">
	<p><input type="file" name="excelfile" id="excelfile" required="required" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"></p>
	<input type="submit" name="act_button" value="송장일괄업로드"  class="btn btn_02">
</form>

<form name="forderlist" id="forderlist" method="post" autocomplete="off">
	<input type="hidden" name="search_od_status" value="<?= $od_status; ?>">
	<input type="hidden" name="od_status" id="post_od_status">
	<input type="hidden" name="token" value="<?= $token ?>">

	<div class="local_cmd01 local_cmd">
		<div style="float: left">
			<input type="button" value="주문취소(CS)" class="btn btn_02" onclick="forderlist_submit('주문취소');">
			<input type="button" value="교환요청(CS)" class="btn btn_02" onclick="forderlist_submit('교환요청');" style="display: none;">
			<input type="button" value="반품요청(CS)" class="btn btn_02" onclick="forderlist_submit('반품요청');">
			
			
			<!-- 업로드 나중에 살려야함 ㅋㅋ -->
			<!-- <form name="fitem" method="post" action="./orderExcel.php" enctype="multipart/form-data">
				<p><input type="file" name="excelfile" id="excelfile" required="required" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"></p>
				<input type="submit" name="act_button" value="송장일괄업로드"  class="btn btn_02">
			</form> -->


			<!-- <input type="button" value="송장일괄업로드" class="btn btn_02" onclick=""> -->
		</div>
		<div style="float: right">
			<!-- <select name="od_delivery_company" id="delivery_company"> -->
				<!-- <option value="롯데택배">롯데택배</option> -->
				<!-- <option value="CJ대한통운">CJ대한통운</option> -->
			<!-- </select> -->
			<!-- <input type="text" name="od_invoice" id="invoice" value="" placeholder="운송장번호"> -->
			<!-- <input type="text" name="od_invoice_time" id="invoice_time" value="<?= G5_TIME_YMDHIS ?>"> -->
			<input type="button" value="배송중" class="btn btn_01" onclick="forderlist_submit('배송중');">
			<input type="button" value="배송완료" class="btn btn_01" onclick="forderlist_submit('배송완료');">

			<input type="button" value="주문확인" class="btn btn_02" onclick="forderlist_submit('주문확인');">
			<input type="button" value="EXCEL" class="btn btn_02 excel_download">
			<!-- <input type="button" value="EXCEL 정산용" class="btn btn_02 excel_download excel_invoice"> -->
			<input type="button" value="결제완료" class="btn btn_02" onclick="forderlist_submit('결제완료');">
		</div>
	</div>

	<div class="tbl_head01 tbl_wrap">
		<table id="sodr_list">
			<caption>주문 내역 목록</caption>
			<thead>
				<tr>
					<th scope="col">
						<label for="chkall" class="sound_only">주문 전체</label>
						<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all_o(this.form)">
					</th>
					<th scope="col">주문일시<br /><a href="<?= title_sort("od_id", 1) . "&amp;$qstr1"; ?>">주문번호</a></th>
					<th scope="col">상품번호</th>
					<th scope="col">주문자명(ID)</th>
					<th scope="col">제품이미지</th>
					<th scope="col">제품정보</th>
					<!--th scope="col">옵션항목</th-->
					<th scope="col">결제수단</th>
					<th scope="col">수량</th>
					<th scope="col">제품금액</th>
					<th scope="col">배송비</th>
					<th scope="col" style="width: 90px;">쿠폰할인</th>
					<th scope="col">장바구니쿠폰</th>
					<th scope="col">상품쿠폰</th>
					<th scope="col">적립금사용</th>
					<th scope="col">상품결제금액</th>
					<th scope="col" style="width: 90px;">총결제금액</th>
					<th scope="col">운송장정보</th>
					<th scope="col">주문상태<br />(클레임상태)</th>
					<th scope="col">메모</th>
					<th scope="col">디바이스</th>
				</tr>
			</thead>
			<tbody>
				<?
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

					if ($row['od_receipt_point'] > 0) {
						$s_receipt_way .= $s_br . "포인트";
					}

					$mb_nick = get_sideview($row['mb_id'], get_text($row['od_name']), $row['od_email'], '');

					$od_cnt = 0;
					if ($row['mb_id']) {
						$sql2 = " select count(*) as cnt from {$g5['g5_shop_order_table']} where mb_id = '{$row['mb_id']}' ";
						$row2 = sql_fetch($sql2);
						$od_cnt = $row2['cnt'];
					}

					// 주문 번호에 device 표시
					$od_mobile = '';
					if ($row['od_mobile']) {
						$od_mobile = '(M)';
					}

					// 주문번호에 - 추가
					/*
						switch(strlen($row['od_id'])) {
							case 16:
								$disp_od_id = $row['od_type'].'-'.substr($row['od_id'],0,8).'<br/>-'.substr($row['od_id'],8,6);
								break;
							default:
								$disp_od_id = $row['od_type'].'-'.substr($row['od_id'],0,6).'<br/>-'.substr($row['od_id'],6);
								break;
						}
					*/
					$disp_od_id = $row['od_type'] . '-' . substr($row['od_id'], 0, 8) . '-' . substr($row['od_id'], 8, 6);

					// 주문 번호에 에스크로 표시
					$od_paytype = '';
					if ($row['od_test'])
						$od_paytype .= '<span class="list_test">테스트</span>';

					if ($default['de_escrow_use'] && $row['od_escrow'])
						$od_paytype .= '<span class="list_escrow">에스크로</span>';

					$uid = md5($row['od_id'] . $row['od_time'] . $row['od_ip']);

					$bg = 'bg' . ($i % 2);
					$td_color = 0;
					if ($row['od_cancel_price'] > 0) {
						$bg .= 'cancel';
						$td_color = 1;
					}

					$odsql = "
						select
						it_id, it_name, its_sap_code, its_order_no, ct_id, it_id, ct_price, ct_qty, ct_option, ct_status, cp_price, ct_send_cost, io_type, io_price, rf_serial,ct_status_claim, (select group_concat(od_sub_id) from lt_shop_order_item where lt_shop_order_item.ct_id = lt_shop_cart.ct_id ) as od_sub_id
						,ct_delivery_company,ct_invoice,ct_invoice_time,ct_cart_price_ori,ct_cart_coupon_price,cp_price_ori
						from
						lt_shop_cart
						where
						od_id = '" . $row['od_id'] . "'
						and io_type = '0'
						order by
						io_type asc, ct_id asc
						";

					$od = sql_query($odsql);
					$rowspan = sql_num_rows($od);
					for ($k = 0; $opt = sql_fetch_array($od); $k++) {
						// 제품이미지
						$image = get_it_image($opt['it_id'], 50, 50);
						if ($opt['io_type']) {
							$opt_price = $opt['io_price'];
						} else {
							$opt_price = $opt['ct_price'] + $opt['io_price'];
						}
				?>
						<tr>
							<td>
								<!-- <input type="hidden" name="od_id[<?= $i ?>]" value="<?= $row['od_id'] ?>" id="od_id_<?= $i ?>">
								<input type="hidden" name="ct_id[<?= $i ?>]" value="<?= $opt['ct_id'] ?>" id="ct_id<?= $i ?>">
								<input type="checkbox" name="chk[]" value="<?= $i ?>" id="chk_<?= $i ?>"> -->

								<!-- k 변경 -->
								<input type="hidden" name="od_id[<?= $i ?>]" value="<?= $row['od_id'] ?>" id="od_id_<?= $i ?>">
								<input type="hidden" name="ct_id[<?= $i ?>][<?= $k ?>]" value="<?= $opt['ct_id'] ?>" id="ct_id_<?= $i ?>_<?= $k ?>">
								<input type="checkbox" name="chk[]" value="<?= $i ?>" id="chk_<?= $i ?>" ctId= '<?= $opt['ct_id'] ?>' onclick="checkInvoice(<?= $i ?>,<?= $k ?>)">
								<input type="checkbox" name="chkInvoice[]" value="<?= $k ?>" id="chkInvoice_<?= $i ?>_<?= $k ?>" ctId= '<?= $opt['ct_id'] ?>' odId = '<?= $row['od_id'] ?>' style="display: none;">
								
								<!-- <input type="checkbox" name="modi1" value="<?= $row['od_id'] ?>" id="modiId1_<?= $i ?>_<?= $k ?>" style="display: none;"> -->
								<!-- <input type="checkbox" name="modi2" value="<?= $opt['ct_id'] ?>" id="modiId2_<?= $i ?>_<?= $k ?>" style="display: none;"> -->
							</td>
							<td>
								<label class="sound_only">주문일시</label>
								<?= $row['od_time'] ?>
								<br />
								<label class="sound_only">주문번호</label>
								<!-- <a href="#" onclick="location.href='./orderform.php?od_id=<?= $row['od_id']; ?>&amp;<?= $qstr; ?>'"><?= $disp_od_id; ?></a> -->
								<a href="#" onclick="location.href='./orderform.php?od_id=<?= $row['od_id']; ?>&amp;<?= $qstr; ?>'"><?= $row['od_id'] ?>_<?= $k+1 ?></a>
								<label class="sound_only">주문통합상태</label>
								<!-- <input type="hidden" name="current_status[<?= $i ?>]" value="tests!!!!"> -->
								<input type="hidden" name="current_status[<?= $i ?>][<?= $k ?>]" value="<?= $opt['ct_status'] ?>">
							</td>
							<td>
								<?= $opt['ct_id'] ?>
							</td>
							<? if ($k == 0) { ?>
								<td rowspan="<?= $rowspan ?>">
									<label class="sound_only">주문자명</label>
									<?= $mb_nick; ?>
									<?= $row['mb_id'] ? '<br/>(' . $row['mb_id'] . ')' : '(비회원)'; ?>
									<?= $row['od_tel'] ? '<br/>' . $row['od_tel'] : ''; ?>
								</td>
							<? } ?>
							<td headers="td_odrnum2">
								<?= $image; ?>
							</td>
							<td class="td_itopt_tl">
								<label>제품코드 : </label>
								<?= $opt['it_id'] ?>
								<?= ($opt['its_order_no']) ? "<br/>(" . $opt['its_order_no'] . ")" : "" ?>
								<br />
								<label class="sound_only">제품명 : </label>
								<a href="./itemform.php?w=u&amp;it_id=<?= $opt['it_id']; ?>"><?= stripslashes($opt['it_name']); ?></a><br />
								<label>옵션 : </label>
								<?= $opt['ct_option']; ?>
							</td>
							<!--td class="td_itopt_tl">
						옵션내용
					</td-->

							<? if ($k == 0) { ?>
								<td headers="odrpay" rowspan="<?= $rowspan ?>">
									<label class="sound_only">결재수단</label>
									<input type="hidden" name="current_settle_case[<?= $i ?>]" value="<?= $row['od_settle_case'] ?>">
									<?= $s_receipt_way; ?>
								</td>
							<? } ?>

							<td headers="odrstat">
								<label class="sound_only">수량</label>
								<?= $opt['ct_qty']; ?>
							</td>

							<td>
								<label class="sound_only">제품금액</label>
								<?= number_format($opt_price); ?>원
							</td>

							<td>
								<label class="sound_only">배송비</label>
								<?= number_format($row['od_send_cost']); ?>원
							</td>


							<? if ($k == 0) { ?>
								<td rowspan="<?= $rowspan ?>" style="text-align:right;">
									<label class="sound_only">쿠폰할인</label>
									총쿠폰금액 <br><?= number_format($row['od_coupon_cancel'] + $row['od_cart_coupon_cancel']) ?>원<br>
									최종쿠폰금액 <br><?= number_format($row['od_coupon_ori'] + $row['od_cart_coupon_ori']) ?> 원

								</td>
							<? } ?>
							<td <?php if ($opt['ct_status'] =='주문취소' || $opt['ct_status'] =='반품완료') echo ' style="text-decoration:line-through" '; ?>>
								<label class="sound_only">장바구니쿠폰</label>
								<?= number_format($opt['ct_cart_coupon_price']); ?>원
							</td>
							<td <?php if ($opt['ct_status'] =='주문취소' || $opt['ct_status'] =='반품완료') echo ' style="text-decoration:line-through" '; ?>>
								<label class="sound_only">상품쿠폰</label>
								<?= number_format($opt['cp_price']); ?>원
							</td>

							<? if ($k == 0) { ?>
								<td rowspan="<?= $rowspan ?>" style="text-align:right;"><label class="sound_only">적립금사용</label><?= number_format($row['od_receipt_point']) ?></td>
							<? } ?>
							<td <?php if ($opt['ct_status'] =='주문취소' || $opt['ct_status'] =='반품완료') echo ' style="text-decoration:line-through" '; ?>>
								<label class="sound_only">상품결제금액</label>
								<?= number_format($opt['ct_cart_price_ori']); ?>원
							</td>	

							<? if ($k == 0) { ?>
								<td rowspan="<?= $rowspan ?>" style="text-align:right;">
									<label class="sound_only">총결제금액</label>
									총결제금액 <br> <?= number_format($row['od_receipt_price_ori']); ?>원<br />
									<?= ((int) $row['od_refund_price'] > 0) ? '취소금액 : <strong class="red">' . number_format((-1) * (int) $row['od_refund_price']) . '원</strong><br/>' : ''; ?>
									최종결제금액 <br> <?= number_format($row['od_receipt_refund_price_ori']); ?>원
								</td>
							<? } ?>

							<td headers="delino">
								<label class="sound_only">운송장번호</label>
								<? if ($row['od_invoice']) { ?> 
									<a href='<?= G5_URL ?>/common/tracking.php?invc_no=<?= $row['od_invoice'] ?>&invc_co=<?= $row['od_delivery_company'] ?>&view_popup=1' target='_blank' class="od_invoice">
										<?= $row['od_delivery_company'] ? $row['od_delivery_company'] : '-' ?><br />
										<?= $row['od_invoice'] ? $row['od_invoice'] : '-' ?><br />
									</a>

								<? } else if (($opt['ct_status'] =="상품준비중" || $opt['ct_status'] =="배송중") && $opt['ct_invoice']) { ?>
									<div id="invoiceBasic<?= $i ?><?= $k ?>">
										<a href='<?= G5_URL ?>/common/tracking.php?invc_no=<?= $opt['ct_invoice'] ?>&invc_co=<?= $opt['ct_delivery_company'] ?>&view_popup=1' target='_blank' class="od_invoice">
											<?= $opt['ct_delivery_company'] ? $opt['ct_delivery_company'] : '-' ?><br />
											<?= $opt['ct_invoice'] ? $opt['ct_invoice'] : '-' ?><br />
										</a>
									</div>
									<input id="invoiceModifyButton<?= $i ?><?= $k ?>" type="button" value="수정하기" class="btn btn_01" onclick="modifyInvoice(<?= $i ?>,<?= $k ?>)">
									<div id="invoiceModify<?= $i ?><?= $k ?>" style="display: none;">
										<select name="od_delivery_company[<?= $i ?>][<?= $k ?>]" id="delivery_company[<?= $i ?>][<?= $k ?>]" style="width: 175px">
											<option value="롯데택배">롯데택배</option>
											<option value="CJ대한통운">CJ대한통운</option>
											<option value="일양로지스">일양로지스</option>
											<option value="로젠택배">로젠택배</option>
										</select><br>
										<input type="text" name="od_invoice[<?= $i ?>][<?= $k ?>]" id="invoice[<?= $i ?>][<?= $k ?>]" value="" placeholder="운송장번호"><br>
										<input type="text" name="od_invoice_time[<?= $i ?>][<?= $k ?>]" id="invoice_time[<?= $i ?>][<?= $k ?>]" value="<?= G5_TIME_YMDHIS ?>"><br>
										<input type="button" value="뒤로가기" class="btn btn_01" onclick="invoiceBeforeButton(<?= $i ?>,<?= $k ?>)">
										<!-- <input id="invoiceChangeButton" type="button" value="송장변경" class="btn btn_01" onclick="modifyInvoiceButton(<?= $i ?>,<?= $k ?>)"> -->
									</div>
								<? } else if ($opt['ct_status'] =="상품준비중" || $opt['ct_status'] =="배송중") { ?>
									<select name="od_delivery_company[<?= $i ?>][<?= $k ?>]" id="delivery_company[<?= $i ?>][<?= $k ?>]" style="width: 175px">
										<option value="롯데택배">롯데택배</option>
										<option value="CJ대한통운">CJ대한통운</option>
										<option value="일양로지스">일양로지스</option>
										<option value="로젠택배">로젠택배</option>
									</select><br>
									<input type="text" name="od_invoice[<?= $i ?>][<?= $k ?>]" id="invoice[<?= $i ?>][<?= $k ?>]" value="" placeholder="운송장번호"><br>
									<input type="text" name="od_invoice_time[<?= $i ?>][<?= $k ?>]" id="invoice_time[<?= $i ?>][<?= $k ?>]" value="<?= G5_TIME_YMDHIS ?>">
									<!-- <input type="button" value="배송중" class="btn btn_01" onclick="forderlist_submit('배송중');"> -->
								
								<? } else { ?>
									<a href='<?= G5_URL ?>/common/tracking.php?invc_no=<?= $opt['ct_invoice'] ?>&invc_co=<?= $opt['ct_delivery_company'] ?>&view_popup=1' target='_blank' class="od_invoice">
											<?= $opt['ct_delivery_company'] ? $opt['ct_delivery_company'] : '-' ?><br />
											<?= $opt['ct_invoice'] ? $opt['ct_invoice'] : '-' ?><br />
									</a>
								<? } ?>
							</td>
							<td>
								<? if ($row['od_invoice'] == '상품준비중' || $row['od_invoice'] == '배송중') { ?> 
									<label class="sound_only">주문상태</label><?= $opt['ct_status']; ?><br />
									<label class="sound_only">클레임상태</label><?= ($opt['ct_status_claim'] != '') ? "(" . $opt['ct_status_claim'] . ")" : '' ?>
								
								<? } else { ?>
									<label class="sound_only">주문상태</label><?= $opt['ct_status']; ?><br />
									<label class="sound_only">클레임상태</label><?= ($row['od_status_claim'] != '') ? "(" . $opt['od_status_claim'] . ")" : '' ?>
								<? }  ?>
							</td>

							<? if ($k == 0) { ?>
								<td rowspan="<?= $rowspan ?>">
									<label class="sound_only">메모</label>
									<? if ($row['od_memo'] != '') { ?>
										<input type="button" value="USER" class="btn btn_01 od_memo" od_memo='<?= $row['od_memo'] ?>'>
									<? } else { ?>
										<input type="button" value="USER" class="btn">
									<? } ?>
								</td>
							<? } ?>
							<td>
							  	<?= $row['od_pcmobile'] ?>
							</td>
						</tr>
				<?
					}
				}
				sql_free_result($result);
				if ($i == 0)
					echo '<tr><td colspan="19" class="empty_table">자료가 없습니다.</td></tr>';
				?>
			</tbody>
		</table>
	</div>

	<div class="local_cmd01 local_cmd">
		<div style="float: left">
			<input type="button" value="주문취소(CS)" class="btn btn_02" onclick="forderlist_submit('주문취소');">
			<input type="button" value="교환요청(CS)" class="btn btn_02" onclick="forderlist_submit('교환요청');" style="display: none;">
			<input type="button" value="반품요청(CS)" class="btn btn_02" onclick="forderlist_submit('반품요청');">
		</div>
		<div style="float: right">
			<a href="<?= G5_ADMIN_URL ?>/cron/cron_samjin_ordercheck.php" target="_blank" class="btn btn_01">삼진동기화</a>
			<a href="<?= G5_ADMIN_URL ?>/cron/cron_invoice.php" target="_blank"><input type="button" value="택배동기화" class="btn btn_01"></a>
			<!-- <a href="<?= G5_ADMIN_URL ?>/cron/cron_order_complete.php" target="_blank"><input type="button" value="새로운 동기화 !!!" class="btn btn_01"></a> -->
			<input type="button" value="주문확인" class="btn btn_02" onclick="forderlist_submit('주문확인');">
			<input type="button" value="EXCEL" class="btn btn_02 excel_download">
			<input type="button" value="EXCEL 정산용" class="btn btn_02 excel_download excel_invoice">
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

		$(".excel_download").click(function() {

			var $chk = $("input[name='chk[]']:checked");
			var $chkInvo = $("input[name='chkInvoice[]']:checked");

			var $searchCt = '';
			var $searchOd;
			var $countOd = 1;
			var $odIdG;
			var odJson = {};
			for (var i = 0; i < $chkInvo.length; i++) {
				var $getId = $($chkInvo[i]).attr("id");
				var $ctId = $( `#${$getId}` ).attr("ctId");
				
				// --- 개수 계산 ㅋㅋㅋ
				var $odId = $( `#${$getId}` ).attr("odId");
				if ($odIdG == $odId) {
					$countOd += 1;
					odJson[$odId] = $countOd;
				} else {
					$countOd = 1;
					odJson[$odId] = $countOd;
				}
				$odIdG = $odId;
				if (i==0) $searchCt = $searchCt.concat($ctId);
				else $searchCt = $searchCt.concat(`,${$ctId}`);
			}

			odJson  = JSON.stringify(odJson);

			var selectCheck = $(`<input type="hidden" value="${$searchCt}" name="selectCheck">`);
			var odJsonCheck = $(`<input type="hidden" value=${odJson} name="odJsonCheck">`);

			var $form = $('<form></form>');
			$form.attr('action', '<?= G5_ADMIN_URL ?>/ajax.excel_download.php');
			$form.attr('method', 'post');
			$form.appendTo('body');

			var exceldata = $('<input type="hidden" value="<?= $excel_sql ?>" name="exceldata">');
			var headerdata = $('<input type="hidden" value="<?= $headers ?>" name="headerdata">');
			var bodydata = $('<input type="hidden" value="<?= $bodys ?>" name="bodydata">');
			var summarydata = $('<input type="hidden" value="<?= $summaries ?>" name="summarydata">');

			$form.append(exceldata).append(headerdata).append(bodydata).append(selectCheck).append(odJsonCheck);

			if ($(this).hasClass('excel_invoice')) $form.append(summarydata);

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
                document.getElementById('orderMainTable').submit();
            }
        })

	});
	function check_all_o(f)
	{
		var chk = document.getElementsByName("chk[]");
		var chkInvo = document.getElementsByName("chkInvoice[]");

    	for (i=0; i<chk.length; i++) {
        if(!chk[i].disabled) chk[i].checked = f.chkall.checked;
		}
		for (j=0; j<chkInvo.length; j++) {
        if(!chkInvo[j].disabled) chkInvo[j].checked = f.chkall.checked;
    	}
	}

	function checkInvoice(e1,e2) {
		if ($("#chkInvoice_"+e1+"_"+e2).prop("checked")) {
			$("#chkInvoice_"+e1+"_"+e2).prop("checked", false);
		} else {
			$("#chkInvoice_"+e1+"_"+e2).prop("checked", true);
		}
	}

	function modifyInvoice(e1,e2) {
		$("#invoiceBasic"+e1+e2).css("display", "none");
		$("#invoiceModifyButton"+e1+e2).css("display", "none");
		$("#invoiceModify"+e1+e2).css("display", "block");
	}

	function invoiceBeforeButton(e1,e2) { 
		$("#invoiceBasic"+e1+e2).css("display", "block");
		$("#invoiceModifyButton"+e1+e2).css("display", "inline-block");
		$("#invoiceModify"+e1+e2).css("display", "none");
	}
	
	// function modifyInvoiceButton(e1,e2) { 
	// 	// $od_id =$("#od_id"+e1+e2)
	// 	// $ct_id = $_POST['ct_id'][$k][$k1];
	// 	// $ct_id_modi = $("#ct_id_"+e1+"_"+e2).val();
	// 	// $od_id_modi = $("#od_id_"+e1).val();
	// 	// console.log('ct_id_modi',$ct_id_modi);
	// 	// console.log('$od_id_modi ',$od_id_modi );
	// 	$("#modiId1_"+e1+"_"+e2).prop("checked", true);
	// 	$("#modiId2_"+e1+"_"+e2).prop("checked", true);

	// 	// $("#modiId").prop("checked", true);
	// 	// console.log('----------------e-------------------',e);
	// 	// $("#post_od_status").val("배송중");
	// 	$("#forderlist").attr("action", "./orderlistupdate.php").submit();
	// }
	// 	console.log('e : ',e);

	// 	$('#chk'+e).prop('checked', true);
	// 	//
	// 	return;
	// 	var $chk = $("input[name='chk[]']:checked");
	// 	for (var i = 0; i < $chk.size(); i++) {
	// 		var k = $($chk[i]).val();
	// 		var current_status = $("input[name='current_status[" + k + "]']").val();
	// 		// if (current_status != "상품준비중") {
	// 		// 	alert("'상품준비중' 상태의 주문만 '배송중'으로 변경 가능합니다.");
	// 		// 	return false;
	// 		// }
	// 	}
		
	// 	// $("#post_od_status").val("송장변경");
	// 	$("#forderlist").attr("action", "./orderlistupdate.php").submit();
	// 			//f.action = "./orderlistupdate.php";

	// 	return false;
	// 	break;

	// }
	function forderlist_submit(change_status) {
		if (!is_checked("chk[]")) {
			alert(change_status + " 하실 항목을 하나 이상 선택하세요.");
			return false;
		}
		var $f = $("#forderlist");
		var $chk = $("input[name='chk[]']:checked");
		var $chkInvoice = $("input[name='chkInvoice[]']:checked");

		$("#post_od_status").val("");

		switch (change_status) {
			case "결제완료":
				for (var i = 0; i < $chk.size(); i++) {
					var k = $($chk[i]).val();
					var chkIn = $($chkInvoice[i]).val();
					var current_status = $("input[name='current_status[" + k + "][" + chkIn + "]']").val();

					if (current_status != "상품준비중") {
						alert("'상품준비중' 상태의 주문만 '결제완료'이 가능합니다.");
						return false;
					}
				}
				if (confirm("결제완료로 변경하시겠습니까?")) {
					$("#post_od_status").val("결제완료");
					$("#forderlist").attr("action", "./orderlistupdate.php").submit();                             
                }
				//f.action = "./orderlistupdate.php";
				// return false;
				break;
			case "주문취소":
				if ($chk.size() != 1) {
					alert("CS처리는 다중 선택할 수 없습니다.");
					return false;
				}
				var k = $($chk[0]).val();
				var chkIn = $($chkInvoice[0]).val();
				var current_status = $("input[name='current_status[" + k + "][" + chkIn + "]']").val();

				/*if (current_status != "결제완료" && current_status != "상품준비중") {
					alert("'결제완료','상품준비중' 상태의 주문만 '주문취소'가 가능합니다.");
					return false;
				}*/
				if (current_status != "결제완료" && current_status != "상품준비중") {
					alert("'결제완료' , '상품준비중' 상태의 주문만 '주문취소'가 가능합니다.");
					return false;
				}

				var url = "./orderpartcancel.php?od_type=<?= $od_type; ?>&od_id=" + $("input[name='od_id[" + k + "]']").val();
				window.open(url, "orderpartcancel", "left=230,top=100,width=1000,height=650,scrollbars=yes,resizable=yes");

				break;
			case "교환요청":
				if ($chk.size() != 1) {
					alert("CS처리는 다중 선택할 수 없습니다.");
					return false;
				}
				var k = $($chk[0]).val();
				var chkIn = $($chkInvoice[0]).val();
				var current_status = $("input[name='current_status[" + k + "][" + chkIn + "]']").val();

				if (current_status != "배송완료") {
					alert("'배송완료' 상태의 주문만 '교환요청'이 가능합니다.");
					return false;
				}

				var url = "./orderpartchange.php?od_type=<?= $od_type; ?>&od_id=" + $("input[name='od_id[" + k + "]']").val();
				window.open(url, "orderpartchange", "left=230,top=100,width=1000,height=650,scrollbars=yes,resizable=yes");

				break;
			case "반품요청":
				if ($chk.size() != 1) {
					alert("CS처리는 다중 선택할 수 없습니다.");
					return false;
				}
				var k = $($chk[0]).val();
				var chkIn = $($chkInvoice[0]).val();
				var current_status = $("input[name='current_status[" + k + "][" + chkIn + "]']").val();
				if (current_status != "배송완료") {
					alert("'배송완료' 상태의 주문만 '반품요청'이 가능합니다.");
					return false;
				}

				var url = "./orderpartreturn.php?od_type=<?= $od_type; ?>&od_id=" + $("input[name='od_id[" + k + "]']").val();
				window.open(url, "orderpartreturn", "left=230,top=100,width=1000,height=650,scrollbars=yes,resizable=yes");

				break;
			case "주문확인":

				for (var i = 0; i < $chk.size(); i++) {
					var k = $($chk[i]).val();
					var chkIn = $($chkInvoice[i]).val();
					var current_status = $("input[name='current_status[" + k + "][" + chkIn + "]']").val();

					if (current_status != "결제완료") {
						alert("'결제완료' 상태의 주문만 '주문확인'이 가능합니다.");
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
					var chkIn = $($chkInvoice[i]).val();
					var k = $($chk[i]).val();
					var current_status = $("input[name='current_status[" + k + "][" + chkIn + "]']").val();
					if (current_status != "상품준비중" && current_status != "배송중") {
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
					var chkIn = $($chkInvoice[i]).val();
					var k = $($chk[i]).val();
					var current_status = $("input[name='current_status[" + k + "][" + chkIn + "]']").val();

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