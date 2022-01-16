<?
// 업체 코드
include_once('./_common.php');

if (empty($cp_code)) $cp_code = $_GET['now_cp_code'];


$cmp = "SELECT * FROM b2b_company WHERE cp_code = '{$cp_code}' AND cp_gubun = 'Y' AND use_yn = 'Y' limit 1 ";
$cmp_res = sql_fetch($cmp);


// $st_comforms  = '' ;
// $st_shutdowns = '';

$numberOrder = 10;

if ($page_rows > 10 || $page_rows== "0") {
	$numberOrder = $page_rows;
}

if($dpart_types){
	$sql_search .= "AND dpart_type = '{$dpart_types}' ";
}else{
	$dpart_types = '';
}


if ($sc_it_time != "") {
    $sc_it_times = explode("~", $sc_it_time);
    $fr_sc_it_time = trim($sc_it_times[0]);
    $to_sc_it_time = trim($sc_it_times[1]);

    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_sc_it_time) ) $fr_sc_it_time = '';
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_sc_it_time) ) $to_sc_it_time = '';

    $timestamp1 = strptime($fr_sc_it_time, '%Y-%m-%d');
    $timestamp2 = strptime($to_sc_it_time, '%Y-%m-%d');
    

    $fr_sc_it_time = mktime(0, 0, 0, $timestamp1['tm_mon']+1, $timestamp1['tm_mday'], $timestamp1['tm_year']+1900);
    $to_sc_it_time = mktime(0, 0, 0, $timestamp2['tm_mon']+1, $timestamp2['tm_mday']+1, $timestamp2['tm_year']+1900);

    if($sc_it_time == " ") {
        $sql_search .= " AND order_date is not null ";
    }else{
        if ($fr_sc_it_time && $to_sc_it_time) {
            $sql_search .= " AND order_date between   FROM_UNIXTIME({$fr_sc_it_time}) AND  FROM_UNIXTIME({$to_sc_it_time}) ";
        }
    }
    
}else{
    $toDate = date("Y-m-d");
	$stDate = date("Y-m-d", strtotime($toDate.'- 7 days'));

    $timestamp1 = strptime($toDate, '%Y-%m-%d');

    $fr_sc_it_time = mktime(0, 0, 0, $timestamp1['tm_mon']+1, $timestamp1['tm_mday']-7, $timestamp1['tm_year']+1900);
    $to_sc_it_time = mktime(0, 0, 0, $timestamp1['tm_mon']+1, $timestamp1['tm_mday']+1, $timestamp1['tm_year']+1900);

    $sql_search .= " AND order_date between   FROM_UNIXTIME({$fr_sc_it_time}) AND  FROM_UNIXTIME({$to_sc_it_time}) ";
    $sc_it_time = $stDate.' ~ '.$toDate;
}

if ($search != "") {
	if ($sel_field != "") {
		$sql_search .= " AND  $sel_field like '%$search%' ";
	}

	if ($save_search != $search) {
		$page = 1;
	}
}


$sqlcnt = " SELECT count(*) as cnt FROM b2b_order_form  WHERE cp_code = '{$cp_code}' " . $sql_search ;
$cntrow = sql_fetch($sqlcnt);
$total_count = $cntrow['cnt'];
if ($numberOrder =='0') {
	$numberOrder = $total_count;
}
$rows = $numberOrder;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) {
	$page = 1;
} // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$bf_sql = "SELECT * FROM b2b_order_form WHERE cp_code = '{$cp_code}'  " . $sql_search . 'ORDER BY order_no desc , samjin_modi_it_code DESC , box_num ASC ' ;
$bf_res = sql_query($bf_sql);


$oform_headers = array('삼진출고처','제휴사','고객주문번호','받는분','연락처','우편번호','주소','바코드','품목명','SAP_CODE','색상','사이즈','주문수량','박스수량','송장번호');
$oform_bodys = array('mall_code','supply_cp','order_no','receive_name','receive_tel','receive_zip','receive_addr','samjin_modi_it_code','samjin_it_name','sap_code','color','size','order_qty','box_qty');

//쿠팡용
$oform_headers_c = array('받는분','연락처','','우편번호','주소','품목명','색상','사이즈','주문수량','확정수량','타입','운임','배송요청사항','보내는분','보내는분연락처','주소','고객주문번호','품목코드','운송장번호','창고번호','제휴사','결재번호','SMS발송여부','SAMJIN_CODE','박스번호');
$oform_bodys_c = array('receive_name','receive_tel','','receive_zip','receive_addr','samjin_it_name','color','size','order_qty','clgo_qty','','','','sender','sender_tel','sender_addr','order_no','sap_code','invoice_no','','mall_code','','','samjin_code','box_num');


$enc = new str_encrypt();

$oform_headers = $enc->encrypt(json_encode_raw($oform_headers));
$oform_bodys = $enc->encrypt(json_encode_raw($oform_bodys));

$oform_headers_c = $enc->encrypt(json_encode_raw($oform_headers_c));
$oform_bodys_c = $enc->encrypt(json_encode_raw($oform_bodys_c));


$qstr= "cp_code=".$cp_code."&amp;now_cp_code=".$cp_code."&amp;dpart_type".$dpart_type."&amp;sel_field=".$sel_field."&amp;search=".$search."&amp;sc_it_time=".$sc_it_time."&amp;page_rows=".$page;

?>
<form id= "storeMainTable" class="local_sch01 local_sch">
	<input type="hidden" id='cp_code' name ='cp_code' value="<?=$cp_code?>" >
	<input type="hidden" id='now_cp_code' name ='now_cp_code' value="<?=$cp_code?>" >
	<div class="tbl_frm01 tbl_wrap">
		<table>
			<colgroup>
				<col class="grid_4">
				<col>
				<col class="grid_3">
			</colgroup>
			<tr>
				<th scope="row">검색분류</th>
				<td colspan="2">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<select name="sel_field" id="sel_field">
							<option value="samjin_it_name" <?= get_selected($sel_field, 'samjin_it_name'); ?>>상품명</option>
							<option value="st_name" <?= get_selected($sel_field, 'st_name'); ?>>매장명</option>
							<option value="sap_code" <?= get_selected($sel_field, 'sap_code'); ?>>SAP코드</option>
							
						</select>

						<label for="search" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
						<input type="text" name="search" value="<?= $search; ?>" id="search" class="frm_input" autocomplete="off">
					</div>
				</td>
			</tr>
			<tr>
                <th scope="row">주문일자</th>
                <td colspan="2">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <input type='text' class="form-control" id="it_time" name="sc_it_time" value="" autocomplete="off"/>
                        <i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 10px;right: 24px;top: auto;cursor: pointer;"></i>
                    </div>
                    <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                        <div class="btn-group" >
                            <button type="button" class="btn btn_02" name="dateBtn" data="all">전체</button>
                            <button type="button" class="btn btn_02" name="dateBtn" data="today">오늘</button>
                            <button type="button" class="btn btn_02" name="dateBtn" data="3d">3일</button>
                            <button type="button" class="btn btn_02" name="dateBtn" data="1w">1주</button>
                            <button type="button" class="btn btn_02" name="dateBtn" data="1m">1개월</button>
                            <button type="button" class="btn btn_02" name="dateBtn" data="3m">3개월</button>
                        </div>
                    </div>
                </td>
            </tr>
			<tr>
				<th scope="row">주문상태</th>
				<td colspan="2">
					<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
						<input type="radio" name="dpart_types" value="" id="dpart_types01" <?= get_checked($dpart_types, ''); ?>>
						<label for="dpart_types01">전체</label>
						<input type="radio" name="dpart_types" value="택배" id="dpart_types02" <?= get_checked($dpart_types, '택배'); ?>>
						<label for="dpart_types02">택배</label>
						<input type="radio" name="dpart_types" value="용달" id="dpart_types03" <?= get_checked($dpart_types, '용달'); ?>>
						<label for="dpart_types03">용달</label>
						

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
		<select name="page_rows" onchange="$('#storeMainTable').submit();">
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




<form name="forderlist" id="forderlist" method="post" autocomplete="off">
	<input type="hidden" name="cp_code" value="<?= $cmp_res['cp_code'] ?>">
	<input type="hidden" name="cp_name" value="<?= $cmp_res['cp_name'] ?>" id="cp_code" >
	<input type="hidden" name="cp_number" value="<?= $cmp_res['cp_number'] ?>">

	<input type="hidden" name="fnc_type" id="fnc_type" value="">
	<input type="hidden" name="selects_ord" id="selects_ord" value="">
	<input type="hidden" value="" name="box_num" id="box_num">

	<input type ="hidden" name ="excel_bfno" id="excel_bfno" >

	<div class="local_cmd01 local_cmd" style="margin-top : 20px;">
		<div class="btn btn_02" style="height: 30px;" onclick ="down_excel()">엑셀다운로드</div>
		<input type='file' name ="upload_excel" id='upload_excel' />
		<div class="btn btn_02" style="height: 30px;" id="upload_excel_btn">엑셀업로드</div>
		<?if($cmp_res['cp_code'] == '19941'){?>
		<div class="btn btn_02" style="height: 30px;" id="all_save_form"  onclick ="all_save_form()">일괄저장</div>
		<div class="btn btn_02" style="height: 30px;" id="order_form_conform"  onclick ="order_form_conform()">출고확정</div>
		
		<?}?>
	
		<div class="b2b_btn_wrap_right">
			
			<?if($cmp_res['cp_code'] == '19941'){?>
				<a class="btn btn_02" onclick="box_separation()" >박스분할</a>
				<a class="btn btn_02" onclick="box_delete()" >박스삭제</a>
			<?}?>
		</div>
	</div>


	<div class="tbl_head01 tbl_wrap" id = "b2b_tbl_wrap">
		<table id="sodr_list">
			<caption>발주 내역 목록</caption>
			<thead>
				<?if($cmp_res['cp_code'] == '19941') : ?>
				<tr>
					<th style="background: #fff;    border: 0px;" colspan="16"></th>
					<th>총 주문금액</th>
					<th style="background-color: #fff; color : #000;" colspan="2" id="total_order_price"></th>
					<th>총 확정금액</th>
					<th style="background-color: #fff; color : #000;" colspan="2"  id="total_clgo_price"></th>

				</tr>
				<tr>
					<th scope="col">
						<label for="chkall" class="sound_only">주문 전체</label>
						<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all_o(this.form)">
					</th>
					<th scope="col">주문일자</th>
					<th scope="col">삼진출고처</th>
					<th scope="col">제휴사</th>
					<th scope="col">고객주문번호 <a class="sort_btn_a" onclick="sortTD ( 4 )">▲</a><a class="sort_btn_a" onclick="reverseTD ( 4 )">▼</a></th>
					<th scope="col">매장명  <a class="sort_btn_a" onclick="sortTD ( 5 )">▲</a><a class="sort_btn_a" onclick="reverseTD ( 5 )">▼</a></th>
					<th scope="col">수취인명</th>
					<th scope="col">연락처</th>
					<th scope="col">우편번호</th>
					<th scope="col">주소</th>
					<th scope="col">삼진품목명 <a class="sort_btn_a" onclick="sortTD ( 10 )">▲</a><a class="sort_btn_a" onclick="reverseTD ( 10 )">▼</a></th>
					<th scope="col">삼진상품명 <a class="sort_btn_a" onclick="sortTD ( 11 )">▲</a><a class="sort_btn_a" onclick="reverseTD ( 11 )">▼</a></th>
					<th scope="col">삼진코드</th>
					<th scope="col">색상</th>
					<th scope="col">사이즈</th>
					<th scope="col">주문수량</th>
					<th scope="col">박스번호</th>
					<th scope="col">확정수량</th>
					<th scope="col">매입가</th>
					<th scope="col">출고유형</th>
					<th scope="col">송장번호</th>
					<th scope="col">출고상태</th>
				</tr>
				<?else : ?>
				<tr>
					<th scope="col">
						<label for="chkall" class="sound_only">주문 전체</label>
						<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all_o(this.form)">
					</th>
					<th scope="col">주문일자</th>
					<th scope="col">삼진출고처</th>
					<th scope="col">제휴사</th>
					<th scope="col">고객주문번호</th>
					<th scope="col">매장명</th>
					<th scope="col">수취인명</th>
					<th scope="col">연락처</th>
					<th scope="col">우편번호</th>
					<th scope="col">주소</th>
					<th scope="col">삼진품목명</th>
					<th scope="col">삼진상품명</th>
					<th scope="col">삼진코드</th>
					<th scope="col">색상</th>
					<th scope="col">사이즈</th>
					<th scope="col">주문수량</th>
					<th scope="col">박스수량</th>
					<th scope="col">출고유형</th>
					<th scope="col">송장번호</th>
					<th scope="col">출고상태</th>
				</tr>
				<?endif?>
			</thead>
			<tbody>
			
			<?for($bf = 0 ; $bf_Row = sql_fetch_array($bf_res); $bf++) {?>
				<tr>
					<input type="hidden" name="bf_no[<?= $bf ?>]" value="<?= $bf_Row['bf_no'] ?>" id="bf_no_<?= $bf ?>">
					<input type="hidden" name="order_no[<?= $bf ?>]" value="<?= $bf_Row['order_no'] ?>" id="order_no_<?= $bf ?>">
					<input type="hidden" name="bf_idx[]" value="<?=$bf ?>" box_num = "<?=$bf_Row['box_num'] ?>"; id="bf_idx_<?= $bf ?>">
					
					<input type="hidden" name="order_qty[<?= $bf ?>]" value="<?=$bf_Row['order_qty'] ?>" id="order_qty_<?= $bf ?>">
					<input type="hidden" name="c_clgo_qty[<?= $bf ?>]" value="<?=$bf_Row['clgo_qty'] ?>" id="c_clgo_qty_<?= $bf ?>">
					<input type="hidden" name="order_price[<?= $bf ?>]" value="<?=$bf_Row['order_price'] ?>" id="order_price_<?= $bf ?>">
					<td>
						<input type="checkbox" name="chk[]" value="<?= $bf_Row['bf_no'] ?>" id="chk_<?= $bf_Row['bf_no'] ?>" box_num = "<?=$bf_Row['box_num']?>" boId= '<?= $bf_Row['bf_no'] ?>'>
					</td>
					<td scope="col"><?=substr($bf_Row['order_date'], 0 , 10)?></td>
					<td scope="col"><?=$bf_Row['mall_code']?></td>
					<td scope="col"><?=$bf_Row['supply_cp']?></td>
					<td scope="col"><?=$bf_Row['order_no']?></td>
					<td scope="col"><?=$bf_Row['st_name']?></td>
					<td scope="col"><?=$bf_Row['receive_name']?></td>
					<td scope="col"><?=$bf_Row['receive_tel']?></td>
					<td scope="col"><?=$bf_Row['receive_zip']?></td>
					<td scope="col"><?=$bf_Row['receive_addr']?></td>
					<td scope="col"><?=$bf_Row['samjin_modi_it_code']?></td>
					<td scope="col"><?=$bf_Row['samjin_it_name']?></td>
					<td scope="col"><?=$bf_Row['samjin_code']?></td>
					<td scope="col"><?=$bf_Row['color']?></td>
					<td scope="col"><?=$bf_Row['size']?></td>
					<td scope="col"><?=$bf_Row['order_qty']?></td>
					<?if($cmp_res['cp_code'] == '19941') :?>
						<td scope="col"><?=$bf_Row['box_num']?></td>
						<td scope="col" style="width : 50px; "><input class="noborder" style="background-color : yellow; text-align : center;" name = "clgo_qty[]" value="<?=$bf_Row['clgo_qty'] ?>"></td>
						<td scope="col"><?=number_format($bf_Row['order_price'])?></td>
						<td scope="col">
							<select class="noborder" name="dpart_type[]" id="dpart_type_<?= $s ?>" >
								<option value="택배" <?= get_selected($bf_Row['dpart_type'], '택배'); ?>>택배</option>	
								<option value="밀크런" <?= get_selected($bf_Row['dpart_type'], '밀크런'); ?>>밀크런</option>
							</select>
						</td>
						<td scope="col"><input class="noborder" style="width : 100%; text-align : center;" name = "invoice_no[]" value="<?=$bf_Row['invoice_no'] ?>"> </td>
					<?else :?>
						<td scope="col"><?=$bf_Row['box_qty']?></td>
						<td scope="col"><?=$bf_Row['dpart_type']?></td>
						<td scope="col"><a onclick="invoice_modal('invoice_<?=$bf_Row['bf_no']?>')"><?=$bf_Row['invoice_no'] ? '완료' : '' ?></a></td>
						<input type="hidden" id = "invoice_<?=$bf_Row['bf_no']?>" value ="<?=$bf_Row['invoice_no']?>"> 
					<?endif?>
					<td scope="col"><?=$bf_Row['order_form_status']?></td>
					
				</tr>
			<?}
			if($bf == 0 ){
				if($cmp_res['cp_code'] == '19941'){
					echo '<tr><td colspan="22" class="empty_table">등록된 발주 건 없습니다.</td></tr>';
				}else{
					echo '<tr><td colspan="20" class="empty_table">등록된 발주 건 없습니다.</td></tr>';
				}
			}
			?>
			</tbody>
		</table>	
	</div>
</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>


<!-- 송장 -->
<div class="modal fade" id="invoice_modal" tabindex="-1" role="dialog" aria-labelledby="invoice_modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"  style ="width: 450px; margin: 0 auto;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">송장번호</h4>
            </div>
            <div class="modal-body" style="text-align: center;">
                <div class="tbl_frm01 tbl_wrap" id="invoiceDetail">
                    
                </div>

            </div>
            <div class="modal-footer" style="text-align: center;">
                <input type="button" value="확인" class="btn btn_02" class="close" data-dismiss="modal" aria-label="Close">                
            </div>
        </div>
    </div>
</div>

<script>
	$('#it_time').daterangepicker({
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
			"daysOfWeek": ["일","월","화","수","목","금","토"],
			"monthNames": ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],
			"firstDay": 1
		}
	});
	
	$('#it_time').val("<?php echo $sc_it_time ?>");
	$( "#invoice_up_dt" ).datetimepicker({
		locale: 'ko',
		format: 'YYYY-MM-DD',
	});
	
	//날짜 버튼
	
	$("button[name='dateBtn']").click(function(){
		
		var d = $(this).attr("data");
		if(d == "all") {
			$('#it_time').val(" ");
		} else {
			var startD = moment();
			var endD = moment();
			
			if(d == "3d") {
				startD = moment().subtract(2, 'days');
				endD = moment();
				
			} else if(d == "1w") {
				startD = moment().subtract(6, 'days');
				endD = moment();
				
			} else if(d == "1m") {
				startD = moment().subtract(1, 'month');
				endD = moment();
				
			} else if(d == "3m") {
				startD = moment().subtract(3, 'month');
				endD = moment();
			}
	
			$('#it_time').data('daterangepicker').setStartDate(startD);
			$('#it_time').data('daterangepicker').setEndDate(endD);
		}
	
	});
	$(document).ready(function () {
		$('#upload_excel').hide();
		$('#upload_excel_btn').on('click', function () {$('#upload_excel').click();});

		$('#upload_excel').change(function () {
			if (confirm("정말 등록하시겠습니까?") == true){  
				var file = this.files[0];
				var reader = new FileReader();
				reader.onloadend = function () {
				}
				if (file) {
					reader.readAsDataURL(file);
				} else {
				}
				upload_excel();

			} else {
				$("#upload_excel").val("");
			}
			
		});

		let total_order_price =0; 
		let total_clgo_price = 0;
		let total_order_qty =0; 
		let total_clgo_qty = 0;
		$("input[name='bf_idx[]']").each(function() {
			var idx = this.value;
			let order_qty = $("input[name='order_qty["+idx+"]']").val().replace(/,/gi,''); 
			let clgo_qty = $("input[name='c_clgo_qty["+idx+"]']").val().replace(/,/gi,''); 
			let order_price = $("input[name='order_price["+idx+"]']").val().replace(/,/gi,''); 
			
			let box_num = $(this).attr("box_num");
			
			if (box_num =='0001') {
				total_order_price += (order_qty * order_price);
				total_order_qty += (order_qty * 1);
			}
			total_clgo_price += (clgo_qty * order_price);
			total_clgo_qty += (clgo_qty * 1);
			
		});
		$("#total_order_price").empty().html(comma(total_order_price+"") + "원 / " + total_order_qty + "개");
		$("#total_clgo_price").empty().html(comma(total_clgo_price+"") + "원 / " + total_clgo_qty + "개");

		
	});

	function check_all_o(f){
		var chk = document.getElementsByName("chk[]");
		
    	for (i=0; i<chk.length; i++) {
        if(!chk[i].disabled) chk[i].checked = f.chkall.checked;
		}
		
	}

	function upload_excel(){
		var $excelfile = $("#upload_excel");
		var $cp_code= $("#now_cp_code").val();;
		var cp_code = $('<input type="hidden" value="'+$cp_code+'" name="cp_code">');
		var $form = $('<form></form>');     
		$form.attr('action', './upload_b2b_order_invoice.php');
		$form.attr('method', 'post');
		$form.attr('enctype', 'multipart/form-data');
		$form.appendTo('body');
		$form.append($excelfile);
		$form.append(cp_code);
		$form.submit();
	}

	function box_separation(){
		let fnc_chk = "";
		$("#fnc_type").val();
		if (!is_checked("chk[]")) {
			alert("분리 하실 주문 1건 선택해주세요.");
			return false;
		}

		let bf_no = '';
		let mssg = '';

		$("input[name='chk[]']:checked").is(function() {
			bf_no = this.value;
		});

		$("#fnc_type").val('box_separation');

		var inputString = prompt('몇 박스로 분리하시겠습니까?'); 

		$("#selects_ord").val(bf_no);
		$("#box_num").val(inputString);

		mssg = '박스를 ' + inputString + '개로 분활하시겠습니까?';

		fnc_chk = confirm(mssg);

		
		if(fnc_chk) {
			var formData = $("#forderlist").serialize();
	
			$.ajax({
				cache : false,
				url : "./ajax_b2b_order_func.php", // 요기에
				type : 'POST', 
				data : formData, 
				dataType : 'json',
				success : function(data) {
					// console.log(data);
					if(data.code == 500){alert("박스 분활 되었습니다.");}
					
					location.reload();
				}, // success 
	
				error : function(xhr, status) {
					alert(xhr + " : " + status);
				}
			}); 
		}
	}

	function box_delete (){
		let fnc_chk = "";
		let mssg = '';
		let falseCheck = true;
		$("#fnc_type").val();

		if (!is_checked("chk[]")) {
			alert("분활된 주문건 선택해주세요.");
			return false;
		}

		$("#fnc_type").val('box_delete');

		$("#selects_ord").val("");

		var select = new Array();

		$("input[name='chk[]']:checked").each(function() {
			let box_num = $(this).attr("box_num");
			
			if (box_num =='0001') {
				alert("원본 주문 삭제 불가능합니다.(박스번호 0001)");
				falseCheck = false;
				return false;
			}
			var bf_no = this.value;
			select.push(bf_no);
		});
		mssg = "분활된 발주건 삭제하시겠습니까?";

		var selects = select.join(",");
			
		$("#selects_ord").val(selects);

		if(!falseCheck) {
			return false;
		}
		fnc_chk = confirm(mssg);
		if(fnc_chk) {
			var formData = $("#forderlist").serialize();
	
			$.ajax({
				cache : false,
				url : "./ajax_b2b_order_func.php", // 요기에
				type : 'POST', 
				data : formData, 
				dataType : 'json',
				success : function(data) {
					// console.log(data);
					if(data.code == 700){alert("분활된 주문 삭제되었습니다.");}
					location.reload();
				}, // success 
	
				error : function(xhr, status) {
					// alert(xhr + " : " + status);
				}
			}); 

		}
	}

	function order_form_conform(){

		let fnc_chk = "";
		let mssg = '';
		let falseCheck = true;
		$("#fnc_type").val();

		if (!is_checked("chk[]")) {
			alert("주문건 선택해주세요.");
			return false;
		}

		$("#fnc_type").val('order_form_conform');

		$("#selects_ord").val("");

		var select = new Array();

		$("input[name='chk[]']:checked").each(function() {
			var bf_no = this.value;
			select.push(bf_no);
		});
		mssg = "출고확정 처리하시겠습니까?";

		var selects = select.join(",");
			
		$("#selects_ord").val(selects);

		if(!falseCheck) {
			return false;
		}
		fnc_chk = confirm(mssg);
		if(fnc_chk) {
			var formData = $("#forderlist").serialize();
	
			$.ajax({
				cache : false,
				url : "./ajax_b2b_order_func.php", // 요기에
				type : 'POST', 
				data : formData, 
				dataType : 'json',
				success : function(data) {
					// console.log(data);
					if(data.code == 800){alert("출고화정 처리되었습니다.");}
					location.reload();
				}, // success 
	
				error : function(xhr, status) {
					// alert(xhr + " : " + status);
				}
			}); 

		}

	}

	function all_save_form (){
		let fnc_chk = "";
		$("#fnc_type").val();

		$("#fnc_type").val('all_save_form');


		var formData = $("#forderlist").serialize();
	
		$.ajax({
			cache : false,
			url : "./ajax_b2b_order_func.php", // 요기에
			type : 'POST', 
			data : formData, 
			dataType : 'json',
			success : function(data) {
				// console.log(data);
				if(data.code == 600){alert("수정 하신 내용 저장되었습니다.");}
				location.reload();
			}, // success 

			error : function(xhr, status) {
				// alert(xhr + " : " + status);
			}
		}); 
	}


	function down_excel(){

		if (!is_checked("chk[]")) {
			alert("엑셀 다운로드 할 주문을 선택해주세요.");
			return false;
		}

		var $select = new Array();
		$("#excel_bfno").val('');

		$("input[name='chk[]']:checked").each(function() {
			var bf_no = this.value;
			$select.push(bf_no);

		});

		var selects = $select.join(",");
		if ($("#excel_bfno").val() != "") selects += "," + $("#excel_bfno").val();
		$("#excel_bfno").val(selects);
		excel_cp_code = $("#cp_code").val();

		excel_sql = "select * from b2b_order_form where bf_no in ( "+selects +" ) order by reg_date ASC ";
		
		if(excel_cp_code == '19941'){
			headerdata = $('<input type="hidden" value="<?=$oform_headers_c?>" name="headerdata">');
			bodydata = $('<input type="hidden" value="<?=$oform_bodys_c?>" name="bodydata">');
		}else{

			headerdata = $('<input type="hidden" value="<?=$oform_headers?>" name="headerdata">');
			bodydata = $('<input type="hidden" value="<?=$oform_bodys?>" name="bodydata">');
		}
		excel_type = '특판발주서';


		var $form = $('<form></form>');     
		$form.attr('action', './ajax.excel_download.b2b_order_form.php');
		$form.attr('method', 'post');
		$form.appendTo('body');

		var exceldata = $('<input type="hidden" value="'+excel_sql+'" name="exceldata">');

		var excelnamedata = $('<input type="hidden" value="'+excel_type+'" name="excelnamedata">');

		var excelcpcode = $('<input type="hidden" value="'+excel_cp_code+'" name="excelcpcode">');


		$form.append(exceldata).append(headerdata).append(bodydata).append(excelnamedata).append(excelcpcode);
		$form.submit();

	}

	function invoice_modal(invoice){
		let inv =  $("#"+invoice).val();

		let inv_txt = inv.replace(/\n/gi , '<br> ');

        $("#invoiceDetail").empty().html(inv_txt);
        
        $('#invoice_modal').modal('show');

	}

	function comma(obj){
        
        var regx = new RegExp(/(-?\d+)(\d{3})/);
        var bExists = obj.indexOf(".", 0);//0번째부터 .을 찾는다.
        var strArr = obj.split('.');
        while (regx.test(strArr[0])) {//문자열에 정규식 특수문자가 포함되어 있는지 체크
            //정수 부분에만 콤마 달기 
            strArr[0] = strArr[0].replace(regx, "$1,$2");//콤마추가하기
        }
        if (bExists > -1) {
            //. 소수점 문자열이 발견되지 않을 경우 -1 반환
            obj = strArr[0] + "." + strArr[1];
        } else { //정수만 있을경우 //소수점 문자열 존재하면 양수 반환 
            obj = strArr[0];
        }
        return obj;//문자열 반환     
    }

	var myTable = document.getElementById( "sodr_list" ); 
	var replace = replacement( myTable );
	function sortTD( index ){   replace.ascending( index );    } 
	function reverseTD( index ){   replace.descending( index );    } 


	
	function sortingNumber( a , b ){  

	if ( typeof a == "number" && typeof b == "number" ) return a - b; 

	// 천단위 쉼표와 공백문자만 삭제하기.  
	var a = ( a + "" ).replace( /[,\s\xA0]+/g , "" ); 
	var b = ( b + "" ).replace( /[,\s\xA0]+/g , "" ); 

	var numA = parseFloat( a ) + ""; 
	var numB = parseFloat( b ) + ""; 

	if ( numA == "NaN" || numB == "NaN" || a != numA || b != numB ) return false; 

	return parseFloat( a ) - parseFloat( b ); 
	} 


	/* changeForSorting() : 문자열 바꾸기. */ 

	function changeForSorting( first , second ){  

		// 문자열의 복사본 만들기. 
		var a = first.toString().replace( /[\s\xA0]+/g , " " ); 
		var b = second.toString().replace( /[\s\xA0]+/g , " " ); 
		
		var change = { first : a, second : b }; 

		if ( a.search( /\d/ ) < 0 || b.search( /\d/ ) < 0 || a.length == 0 || b.length == 0 ) return change; 

		var regExp = /(\d),(\d)/g; // 천단위 쉼표를 찾기 위한 정규식. 

		a = a.replace( regExp , "$1" + "$2" ); 
		b = b.replace( regExp , "$1" + "$2" ); 

		var unit = 0; 
		var aNb = a + " " + b; 
		var numbers = aNb.match( /\d+/g ); // 문자열에 들어있는 숫자 찾기 

		for ( var x = 0; x < numbers.length; x++ ){ 

				var length = numbers[ x ].length; 
				if ( unit < length ) unit = length; 
		} 

		var addZero = function( string ){ // 숫자들의 단위 맞추기 

			var match = string.match( /^0+/ ); 

			if ( string.length == unit ) return ( match == null ) ? string : match + string; 

			var zero = "0"; 

			for ( var x = string.length; x < unit; x++ ) string = zero + string;

			return ( match == null ) ? string : match + string; 
		}; 

		change.first = a.replace( /\d+/g, addZero ); 
		change.second = b.replace( /\d+/g, addZero ); 
		
		return change; 
	} 


	/* byLocale() */ 

	function byLocale(){ 

		var compare = function( a , b ){ 

			var sorting = sortingNumber( a , b ); 

			if ( typeof sorting == "number" ) return sorting; 

			var change = changeForSorting( a , b ); 

			var a = change.first; 
			var b = change.second; 

			return a.localeCompare( b ); 
		}; 

		var ascendingOrder = function( a , b ){  return compare( a , b );  }; 
		var descendingOrder = function( a , b ){  return compare( b , a );  }; 

		return { ascending : ascendingOrder, descending : descendingOrder }; 
	} 


	/* replacement() */ 

	function replacement( parent ){  
		var tagName = parent.tagName.toLowerCase(); 
		if ( tagName == "table" ) parent = parent.tBodies[ 0 ]; 
		tagName = parent.tagName.toLowerCase(); 
		if ( tagName == "tbody" ) var children = parent.rows; 
		else var children = parent.getElementsByTagName( "li" ); 
		
		var replace = { 
			order : byLocale(), 
			index : false, 
			array : function(){ 
					var array = [ ]; 
					for ( var x = 0; x < children.length; x++ ) array[ x ] = children[ x ]; 
					return array; 
			}(), 
			checkIndex : function( index ){ 
					if ( index ) this.index = parseInt( index, 10 ); 
					var tagName = parent.tagName.toLowerCase(); 
					if ( tagName == "tbody" && ! index ) this.index = 0; 
			}, 
			getText : function( child ){ 
					if ( this.index ) child = child.cells[ this.index ]; 
					return getTextByClone( child ); 
			}, 
			setChildren : function(){ 
					var array = this.array; 
					while ( parent.hasChildNodes() ) parent.removeChild( parent.firstChild ); 
					for ( var x = 0; x < array.length; x++ ) parent.appendChild( array[ x ] ); 
			}, 
			ascending : function( index ){ // 오름차순 
					this.checkIndex( index ); 
					var _self = this; 
					var order = this.order; 
					var ascending = function( a, b ){ 
							var a = _self.getText( a ); 
							var b = _self.getText( b ); 
							return order.ascending( a, b ); 
					}; 
					this.array.sort( ascending ); 
					this.setChildren(); 
			}, 
			descending : function( index ){ // 내림차순
					this.checkIndex( index ); 
					var _self = this; 
					var order = this.order; 
					var descending = function( a, b ){ 
							var a = _self.getText( a ); 
							var b = _self.getText( b ); 
							return order.descending( a, b ); 
					}; 
					this.array.sort( descending ); 
					this.setChildren(); 
			} 
		}; 
		return replace; 
	} 

	function getTextByClone( tag ){  
	var clone = tag.cloneNode( true ); // 태그의 복사본 만들기. 
	var br = clone.getElementsByTagName( "br" ); 
	while ( br[0] ){ 
			var blank = document.createTextNode( " " ); 
			clone.insertBefore( blank , br[0] ); 
			clone.removeChild( br[0] ); 
	} 
	var isBlock = function( tag ){ 
			var display = ""; 
			if ( window.getComputedStyle ) display = window.getComputedStyle ( tag, "" )[ "display" ]; 
			else display = tag.currentStyle[ "display" ]; 
			return ( display == "block" ) ? true : false; 
	}; 
	var children = clone.getElementsByTagName( "*" ); 
	for ( var x = 0; x < children.length; x++){ 
			var child = children[ x ]; 
			if ( ! ("value" in child) && isBlock(child) ) child.innerHTML = child.innerHTML + " "; 
	} 
	var textContent = ( "textContent" in clone ) ? clone.textContent : clone.innerText; 
	return textContent; 
	} 



</script>



