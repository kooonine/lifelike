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

if($order_status){
	$sql_search .= "AND order_status = '{$order_status}' ";
}else{
	$order_status = '';
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
        $sql_search .= " AND reg_date is not null ";
    }else{
        if ($fr_sc_it_time && $to_sc_it_time) {
            $sql_search .= " AND reg_date between   FROM_UNIXTIME({$fr_sc_it_time}) AND  FROM_UNIXTIME({$to_sc_it_time}) ";
        }
    }
    
}else{
    $toDate = date("Y-m-d");
	$stDate = date("Y-m-d", strtotime($toDate.'- 7 days'));

    $timestamp1 = strptime($toDate, '%Y-%m-%d');

    $fr_sc_it_time = mktime(0, 0, 0, $timestamp1['tm_mon']+1, $timestamp1['tm_mday']-7, $timestamp1['tm_year']+1900);
    $to_sc_it_time = mktime(0, 0, 0, $timestamp1['tm_mon']+1, $timestamp1['tm_mday']+1, $timestamp1['tm_year']+1900);

    $sql_search .= " AND reg_date between   FROM_UNIXTIME({$fr_sc_it_time}) AND  FROM_UNIXTIME({$to_sc_it_time}) ";
    $sc_it_time = $stDate.' ~ '.$toDate;
}

if (!$order_statuss) {
    //$sql_search .= " and dparner_id in ('경민실업','어시스트','본사') ";
}else{
    
	$order_statuss_item = implode("','", explode(',', $order_statuss));
	$sql_search .= " and order_status in ('{$order_statuss_item}') ";
    
}



if ($search != "") {
	if ($sel_field != "") {
		$sql_search .= " AND  $sel_field like '%$search%' ";
	}

	if ($save_search != $search) {
		$page = 1;
	}
}


$sqlcnt = " SELECT count(*) as cnt FROM b2b_order  WHERE cp_code = '{$cp_code}' " . $sql_search ;
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

$st_sql = "SELECT * FROM b2b_order WHERE cp_code = '{$cp_code}'  " . $sql_search  . "order by reg_date desc limit $from_record, $rows" ;
$st_res = sql_query($st_sql);

$qstr= "cp_code=".$cp_code."&amp;now_cp_code=".$cp_code."&amp;order_statuss".$order_statuss."&amp;sel_field=".$sel_field."&amp;search=".$search."&amp;sc_it_time=".$sc_it_time."&amp;page_rows=".$page;

$oform_headers = array('고객주문번호','주문상태','주문일자','주문수량','매장명','수취인명','상품명','사이즈','SAP_CODE','정상가','공급가','출고유형','운임유형','메모');
$oform_bodys = array('order_no','order_status','reg_date','order_qty','st_name','receive_name','it_name','size','sap_code','normal_price','supply_price','dpart_type','deliver_type','order_memo');

//쿠팡
$oform_headers_c = array('고객주문번호','주문상태','주문일자','주문수량','매장명','수취인명','상품명','사이즈','SAP_CODE','매입가','출고유형','운임유형','메모');
$oform_bodys_c = array('order_no','order_status','reg_date','order_qty','st_name','receive_name','it_name','size','sap_code','order_price','dpart_type','deliver_type','order_memo');

$enc = new str_encrypt();

$oform_headers = $enc->encrypt(json_encode_raw($oform_headers));
$oform_bodys = $enc->encrypt(json_encode_raw($oform_bodys));

$oform_headers_c = $enc->encrypt(json_encode_raw($oform_headers_c));
$oform_bodys_c = $enc->encrypt(json_encode_raw($oform_bodys_c));

?>
<form id= "storeMainTable" class="local_sch01 local_sch">
	<input type="hidden" id='cp_code' name ='cp_code' value="<?=$cp_code?>" >
	<input type="hidden" id='now_cp_code' name ='now_cp_code' value="<?=$cp_code?>" >
	<input type="hidden" id='order_statuss' name ='order_statuss' value="<?=$order_statuss?>" >
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
							<option value="it_name" <?= get_selected($sel_field, 'it_name'); ?>>상품명</option>
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
						<input type="checkbox" class="order_status" value="주문접수" id="order_status02" <?php if(!$order_statuss || (substr_count($order_statuss, '주문접수') >= 1) ) echo "checked"; ?> >
						<label for="order_status02">주문접수</label>
						<input type="checkbox" class="order_status" value="출고요청" id="order_status03" <?php if(!$order_statuss || (substr_count($order_statuss, '출고요청') >= 1) ) echo "checked"; ?> >
						<label for="order_status03">출고요청</label>
						<input type="checkbox" class="order_status" value="출고지시" id="order_status04" <?php if(!$order_statuss || (substr_count($order_statuss, '출고지시') >= 1) ) echo "checked"; ?> >
						<label for="order_status04">출고지시</label>
						<input type="checkbox" class="order_status" value="주문취소" id="order_status05" <?php if(!$order_statuss || (substr_count($order_statuss, '주문취소') >= 1) ) echo "checked"; ?> >
						<label for="order_status05">주문취소</label>
						

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
	<input type="hidden" name="cp_code" value="<?= $cmp_res['cp_code'] ?>" id="cp_code">
	<input type="hidden" name="cp_name" value="<?= $cmp_res['cp_name'] ?>"  >
	<input type="hidden" name="cp_number" value="<?= $cmp_res['cp_number'] ?>">

	<input type="hidden" name="fnc_type" id="fnc_type" value="">
	<input type="hidden" name="selects_ord" id="selects_ord" value="">

	<input type ="hidden" name ="excel_bono" id="excel_bono" >

	<div class="local_cmd01 local_cmd" style="margin-top : 20px;">
		<?if($cmp_res['cp_code'] != '19941'){?>
			<div class="btn btn_02" style="height: 30px;" id="clgo_request" onclick ="b2b_order_func('clgo_request')">출고요청</div>
		<?}?>
        <div class="btn btn_02" style="height: 30px;" id="clgo_instruction" onclick ="b2b_order_func('clgo_instruction')">출고지시</div>
        <div class="btn btn_02" style="height: 30px;" id="order_cancel"  onclick ="b2b_order_func('order_cancel')">주문취소</div>
        <div class="btn btn_02" style="height: 30px;" id="all_save"  onclick ="b2b_order_func('all_save')">일괄저장</div>
        
		<div class="b2b_btn_wrap_right">
			<div class="btn btn_02" style="height: 30px;" onclick ="down_excel()">엑셀다운로드</div>
			<?if($cmp_res['cp_code'] == '19941'){?>
				<a  class="btn btn_02" href="./excel_sample_coupang_b2b_20211206.xls">수기주문양식</a>
			<?}else {?>
				<a  class="btn btn_02" href="./excel_sample_b2b_20211203.xls">수기주문양식</a>
			<?}?>
			<input type='file' name ="upload_excel" id='upload_excel' />
			<div class="btn btn_02" style="height: 30px;" id="upload_excel_btn">엑셀업로드</div>
		</div>
    </div>
	
	<div class="tbl_head01 tbl_wrap"  id = "b2b_tbl_wrap">
		<table id="sodr_list">
			<caption>주문 내역 목록</caption>
			<thead>
				<?if( $cmp_res['cp_code'] == '19941') :?>
				<tr>
					<th scope="col">
						<label for="chkall" class="sound_only">주문 전체</label>
						<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all_o(this.form)">
					</th>
					<th scope="col">고객주문번호</th>
					<th scope="col">주문상태</th>
					<th scope="col">주문일자</th>
					<th scope="col">주문수량</th>
					<th scope="col">매장명</th>
					<th scope="col">수취인명</th>
					<th scope="col">상품명</th>
					<th scope="col">사이즈</th>
					<th scope="col">SAP코드</th>
					<th scope="col">매입가</th>
					<th scope="col">출고유형</th>
					<th scope="col">운임유형</th>
					<th scope="col">메모</th>
				</tr>
				<?else : ?>
				<tr>
					<th scope="col">
						<label for="chkall" class="sound_only">주문 전체</label>
						<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all_o(this.form)">
					</th>
					<th scope="col">고객주문번호</th>
					<th scope="col">주문상태</th>
					<th scope="col">주문일자</th>
					<th scope="col">주문수량</th>
					<th scope="col">매장명</th>
					<th scope="col">수취인명</th>
					<th scope="col">상품명</th>
					<th scope="col">사이즈</th>
					<th scope="col">SAP코드</th>
					<th scope="col">정상가</th>
					<th scope="col">공급가</th>
					<th scope="col">출고유형</th>
					<th scope="col">운임유형</th>
					<th scope="col">메모</th>
				</tr>	
				<?endif?>
			</thead>
			<tbody>
			
			<?for($s = 0 ; $st_Row = sql_fetch_array($st_res); $s++) {?>
				<tr style ="background-color : <?= $st_Row['receive_addr1'] ? '':'yellow' ?> ">
					<input type="hidden" name="bo_no[<?= $s ?>]" value="<?= $st_Row['bo_no'] ?>" id="bo_no_<?= $s ?>">
					<input type="hidden" name="order_no[<?= $s ?>]" value="<?= $st_Row['order_no'] ?>" id="order_no_<?= $s ?>">
					<input type="hidden" name="samjin_it_name[<?= $s ?>]" value="<?= $st_Row['samjin_it_name'] ?>" id="samjin_it_name_<?= $s ?>">
					<td>
						<input type="checkbox" name="chk[]" value="<?= $st_Row['bo_no'] ?>" order_status ="<?= $st_Row['order_status'] ?>" id="chk_<?= $st_Row['bo_no'] ?>" boId= '<?= $st_Row['bo_no'] ?>'>
					</td>
					<td scope="col"><?=$st_Row['order_no']?></td>
					<td scope="col"><?=$st_Row['order_status']?></td>
					<td scope="col"><?=$st_Row['reg_date']?></td>
					<td scope="col">
						<?if($st_Row['order_status'] == '출고지시' || $st_Row['order_status'] == '주문취소') : ?>
							<?=$st_Row['order_qty']?>
						<?else :?>
							<input type="" style="text-align:center; width : 60px;" name ="order_qty[]" id='order_qty_<?= $s ?>' value="<?=$st_Row['order_qty']?>">
						<?endif?>
					</td>
					<td scope="col" onclick="showDetailInfo('<?=$st_Row['order_no']?>')"><?=$st_Row['st_name']?></td>
					<td scope="col"><?=$st_Row['receive_name']?></td>
					<td scope="col"><?=$st_Row['it_name']?></td>
					<td scope="col"><?=$st_Row['size']?></td>
					<td scope="col"><?=$st_Row['sap_code']?></td>
					<?if( $cmp_res['cp_code'] == '19941') :?>
						<td scope="col"><?=number_format($st_Row['order_price'])?></td>
					<?else : ?>
						<td scope="col"><?=number_format($st_Row['normal_price'])?></td>
						<td scope="col"><?=number_format($st_Row['supply_price'])?></td>
					<?endif?>
					
					<td scope="col">
						<?if($st_Row['order_status'] == '출고지시' || $st_Row['order_status'] == '주문취소') : ?>
							<?=$st_Row['dpart_type']?>
						<?else :?>
							<?if($cmp_res['cp_code'] == '19941') :?>
							<select name="dpart_type[]" id="dpart_type_<?= $s ?>" >
								<option value="택배" <?= get_selected($st_Row['dpart_type'], '택배'); ?>>택배</option>	
								<option value="밀크런" <?= get_selected($st_Row['dpart_type'], '밀크런'); ?>>밀크런</option>
							</select>
							<?else : ?>
							<select name="dpart_type[]" id="dpart_type_<?= $s ?>" >
								<option value="택배" <?= get_selected($st_Row['dpart_type'], '택배'); ?>>택배</option>
								<option value="용달" <?= get_selected($st_Row['dpart_type'], '용달'); ?>>용달</option>
							</select>
							<?endif?>
						<?endif?>

					</td>
					<td scope="col">
						<?if($st_Row['order_status'] == '출고지시' || $st_Row['order_status'] == '주문취소') : ?>
							<?=$st_Row['deliver_type']?>
						<?else :?>
							<select name="deliver_type[]" id="deliver_type_<?= $s ?>" >
								<option value="신용" <?= get_selected($st_Row['deliver_type'], '신용'); ?>>신용</option>	
								<option value="착불" <?= get_selected($st_Row['deliver_type'], '착불'); ?>>착불</option>
							</select>
						<?endif?>
					</td>
					<td title="<?=$st_Row['order_memo']?>">
						<?if(substr($st_Row['order_no'],-2) == '_1') : ?>
							<input type="hidden" id="order_memo_<?=$st_Row['order_no']?>" value = "<?=$st_Row['order_memo']?>">
							<a onclick="showMemo('<?=$st_Row['order_no']?>')" title="<?=$st_Row['order_memo']?>" style="cursor:pointer;"><?=$st_Row['order_memo'] ? 'ⓘ' : ''?>메모</a>
						<?endif?>
					</td>


					
				</tr>
			<?}
			if($s == 0 ){
				echo '<tr><td colspan="15" class="empty_table">등록된 주문이 없습니다.</td></tr>';
			}
			?>
			</tbody>
		</table>	
	</div>
</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

<!-- 상세보기 !!!!!!!!!!! -->
<div class="modal fade" id="detail_modal" tabindex="-1" role="dialog" aria-labelledby="detail_modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content"  style ="width: 1450px; margin-left: -120px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">주문자정보</h4>
      </div>
      <div class="modal-body">
        <div class="tbl_frm01 tbl_wrap" id="dvDetail">
        </div>
        <!-- 몸체를 만들자 -->
        
      </div>
      <div class="modal-footer">
        <br><br><br>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
    </div>
  </div>
</div>

<!-- 메모 -->
<div class="modal fade" id="memo_modal" tabindex="-1" role="dialog" aria-labelledby="memo_modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"  style ="width: 750px; margin-left: 200px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">메모</h4>
            </div>
            <div class="modal-body" style="text-align: center;">
                <div class="tbl_frm01 tbl_wrap" id="memoDetail">
                    
                </div>

            </div>
            <div class="modal-footer" style="text-align: center;">
                <input type="button" value="취소" class="btn btn_02" class="close" data-dismiss="modal" aria-label="Close">
                <input type="button" value="저장" class="btn btn_03" onclick="saveMemo()">
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

		$(".order_status").change(function(){
            var Ord_Stat = "";
            $("input.order_status:checked").each(function(){
                //alert($(this).val());
                if(Ord_Stat != "") Ord_Stat += ",";
                Ord_Stat += $(this).val();

            });
            $("#order_statuss").val(Ord_Stat);
        });
		
	});
	function upload_excel(){
		var $excelfile = $("#upload_excel");

		var $cp_code= $("#now_cp_code").val();;

		var cp_code = $('<input type="hidden" value="'+$cp_code+'" name="cp_code">');

		var $form = $('<form></form>');     
		$form.attr('action', './upload_b2b_order.php');
		$form.attr('method', 'post');
		$form.attr('enctype', 'multipart/form-data');
		$form.appendTo('body');
		$form.append($excelfile);
		$form.append(cp_code);
		$form.submit();
		
	}

	function showDetailInfo(e) {
        $.post(
            "b2b_order_info.php", {
            order_no: e
          },
          function(data) {
            $("#dvDetail").empty().html(data);
          }
        );

        $('#detail_modal').modal('show');
    }

	function check_all_o(f){
		var chk = document.getElementsByName("chk[]");
		
    	for (i=0; i<chk.length; i++) {
        if(!chk[i].disabled) chk[i].checked = f.chkall.checked;
		}
		
	}

	function showMemo(idx) {
        if(idx){
            var memo = $("#order_memo_"+idx).val();
            $("#memoDetail").empty().html("<input type='hidden' id ='idx' value='"+idx+"'><textarea id = 'order_memo' style='width: 100%; border: 1px solid;' type='text'>"+memo+"</textarea>");
        }else{
            $("#memoDetail").empty().html("<input type='hidden' id ='idx' value='"+idx+"'><textarea id = 'order_memo' style='width: 100%; border: 1px solid;' type='text' ></textarea>");
        }
        $('#memo_modal').modal('show');
    }

	function saveMemo(){
        var write_memo = $('#order_memo').val();
        var order_no = $('#idx').val();

        if(write_memo && order_no){
            $.ajax({
                url : './b2b_order_memo.php',
                type: 'post',
                async : false,
                data : {order_memo : write_memo , order_no : order_no},

                error:function(error){
                    complete = false;  
                },
                success:function(response){
                    // console.log(response);  
                    complete = true; 
                }
            });
            if(complete == true){
                alert("수정 완료 되었습니다.");
                location.reload();
            }
        }
    }
	

	function b2b_order_func(fnc_type){
		$("#fnc_type").val(fnc_type);

		let falseCheck = true;
		let fnc_chk = "";
		let mssg = '';

		if(fnc_type == 'all_save'){
			fnc_chk = confirm("일괄저장 하시겠습니까?");

		}else {
			if (!is_checked("chk[]")) {
                alert("하나 이상 선택하세요.");
                return false;
            }
			$("#selects_ord").val("");

			var select = new Array();

			
		
			$("input[name='chk[]']:checked").each(function() {
				let order_status = $(this).attr("order_status");
				if(fnc_type == 'clgo_request'){
					if ((order_status !='주문접수') || order_status =='출고지시' || order_status =='주문취소' ) {
                        alert("주문접수 상태인지 확인해주세요.");
                        falseCheck = false;
						return false;
                    }
					mssg = "출고요청 하시겠습니까?";

				}else if(fnc_type == 'clgo_instruction'){
					if ((order_status !='출고요청') || order_status =='주문접수' || order_status =='출고지시' || order_status =='주문취소' ) {
                        alert("출고요청 상태인지 확인해주세요.");
                        falseCheck = false;
						return false;
                    }
					mssg = "출고지시 하시겠습니까?";

				}else if(fnc_type == 'order_cancel'){
					if ((order_status !='주문접수' && order_status !='출고요청') || order_status =='출고지시' || order_status =='주문취소' ) {
                        alert("특판 발주서로 넘어간 주문건은 취소하실수 없습니다.");
                        falseCheck = false;
						return false;
                    }
					mssg = "주문취소 하시겠습니까?";

				}

				var bo_no = this.value;
				select.push(bo_no);
			});

			
			var selects = select.join(",");
			
			$("#selects_ord").val(selects);
			fnc_chk = confirm(mssg);
		}
		if(!falseCheck) {
			return false;
		}
		
		if(fnc_chk) {
			var formData = $("#forderlist").serialize();
	
			$.ajax({
				cache : false,
				url : "./ajax_b2b_order_func.php", // 요기에
				type : 'POST', 
				data : formData, 
				dataType : 'json',
				success : function(data) {
					console.log(data);
					if(data.code == '100'){alert("출고요청 처리되었습니다."); location.reload();}
					else if(data.code == '200') {alert("출고지시 처리되었습니다."); location.reload();}
					else if(data.code == '299') {alert(data.samjin_it_name+" 상품이 주문량이 재고수량 초과하였습니다. \n주문량 : " + data.total_qty +  "개 \n재고수량 : "+ data.stock + "개");}
					else if(data.code == '300') {alert("선택 주문취소 처리되었습니다."); location.reload();}
					else if(data.code == '400') {alert("수정 하신 내용 저장되었습니다."); location.reload();}
					
				}, // success 
	
				error : function(xhr, status) {
					// alert(xhr + " : " + status);
				}
			}); 
		}
	}

	function down_excel(){

		if (!is_checked("chk[]")) {
			alert("엑셀 다운로드 할 주문을 선택해주세요.");
			return false;
		}

		var $select = new Array();
		$("#excel_bono").val('');

		$("input[name='chk[]']:checked").each(function() {
			var bo_no = this.value;
			$select.push(bo_no);

		});

		var selects = $select.join(",");
		if ($("#excel_bono").val() != "") selects += "," + $("#excel_bono").val();
		$("#excel_bono").val(selects);
		excel_cp_code = $("#cp_code").val();

		excel_sql = "select * from b2b_order where bo_no in ( "+selects +" ) order by reg_date ASC ";
		
		if(excel_cp_code == '19941'){
			headerdata = $('<input type="hidden" value="<?=$oform_headers_c?>" name="headerdata">');
			bodydata = $('<input type="hidden" value="<?=$oform_bodys_c?>" name="bodydata">');
		}else{

			headerdata = $('<input type="hidden" value="<?=$oform_headers?>" name="headerdata">');
			bodydata = $('<input type="hidden" value="<?=$oform_bodys?>" name="bodydata">');
		}
		
		excel_type = '특판주문서';

		var $form = $('<form></form>');     
		$form.attr('action', './ajax.excel_download.b2b_order_form.php');
		$form.attr('method', 'post');
		$form.appendTo('body');

		var exceldata = $('<input type="hidden" value="'+excel_sql+'" name="exceldata">');

		var excelcpcode = $('<input type="hidden" value="'+excel_cp_code+'" name="excelcpcode">');

		var excelnamedata = $('<input type="hidden" value="'+excel_type+'" name="excelnamedata">');
		$form.append(exceldata).append(headerdata).append(bodydata).append(excelnamedata).append(excelcpcode);
		$form.submit();

		}



</script>



