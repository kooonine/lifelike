<?
// 업체 코드
include_once('./_common.php');

if (empty($cp_code)) $cp_code = $_GET['now_cp_code'];


$cmp = "SELECT * FROM b2b_company WHERE cp_code = '{$cp_code}' AND cp_gubun = 'Y' AND use_yn = 'Y' limit 1 ";
$cmp_res = sql_fetch($cmp);

$numberOrder = 10;

if ($page_rows > 10 || $page_rows== "0") {
	$numberOrder = $page_rows;
}

if($order_status){
	$sql_search .= "AND order_status = '{$order_status}' ";
}else{
	$order_status = '';
}


if ($search != "") {
	if ($sel_field != "") {
		$sql_search .= " AND  $sel_field like '%$search%' ";
	}

	if ($save_search != $search) {
		$page = 1;
	}
}


$sqlcnt = " SELECT count(*) as cnt FROM b2b_sale_item_list  WHERE cp_code = '{$cp_code}' " . $sql_search ;
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

$st_sql = "SELECT * FROM b2b_sale_item_list WHERE cp_code = '{$cp_code}'  " . $sql_search  ;
$st_res = sql_query($st_sql);


?>
<form id= "storeMainTable" class="local_sch01 local_sch">
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
							<option value="it_name" <?= get_selected($sel_field, 'it_name'); ?>>상품명</option>
							<option value="sap_code" <?= get_selected($sel_field, 'sap_code'); ?>>SAP코드</option>
							
						</select>

						<label for="search" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
						<input type="text" name="search" value="<?= $search; ?>" id="search" class="frm_input" autocomplete="off">
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

	<input type="hidden" name="seiects_no"  id="seiects_no" value="">

	<input type="hidden" name="fnc_type" id="fnc_type" value="">
	
	

	<div class="local_cmd01 local_cmd" style="margin-top : 20px;">
        <div class="btn btn_02" style="height: 30px;" id="item_insert">상품등록</div>
        <div class="btn btn_02" style="height: 30px;" id="display_non" onclick ="display_non()" >진열안함</div>
        <div class="btn btn_02" style="height: 30px;" id="all_save"  onclick ="save()">일괄저장</div>
        
    </div>
	
	<div class="tbl_head01 tbl_wrap"  id = "b2b_tbl_wrap">
		<table id="sodr_list">
			<caption>상풍등록 목록</caption>
			<colgroup>
				<col width="20;">
				<col>
				<col width="70;">
				<col>
				<col width="200;">
				<col width="200;">
				<col width="70;">
				<col width="100;">
				<col width="80;">
			</colgroup>
			<thead>
				<tr>
					<th scope="col">
						<label for="chkall" class="sound_only">상품 전체</label>
						<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all_o(this.form)">
					</th>
					<th scope="col">상품명</th>
					<th scope="col">사이즈</th>
					<th scope="col">SAP코드</th>
					<th scope="col">정상가</th>
					<th scope="col">공급가</th>
					<th scope="col">재고</th>
					
					<th scope="col">진열여부</th>
					<th scope="col">최소구매수량</th>
				</tr>
			</thead>
			<tbody id="itemListBody">
			
			<?if (!empty($st_res))  { ?>
				<?for($s = 0 ; $st_Row = sql_fetch_array($st_res); $s++) {?>
					<tr class="itemList_tr">
						<input type="hidden" name="si_no[<?= $s ?>]" value="<?= $st_Row['si_no'] ?>" id="si_no_<?=$st_Row['si_no'] ?>">
						<input type="hidden" name="samjin_code[]" value="<?= $st_Row['samjin_code'] ?>" id="samjin_code<?= $s ?>">
						<input type="hidden" name="color[]" value="<?= $st_Row['color'] ?>" id="color<?= $s ?>">
						<td>
							<input type="checkbox" name="chk[]" value="<?= $st_Row['si_no'] ?>" id="chk_<?= $st_Row['si_no'] ?>" boId= '<?= $st_Row['si_no'] ?>' >
						</td>
						<td scope="col"><input type="hidden" name = "samjin_it_name[]" value = "<?=$st_Row['samjin_it_name']?>"><?=$st_Row['samjin_it_name']?></td>
						<td scope="col"><input type="hidden" name = "size[]" value = "<?=$st_Row['size']?>"><?=$st_Row['size']?></td>
						<td scope="col"><input type="hidden" name = "sap_code[]" value = "<?=$st_Row['sap_code']?>"><?=$st_Row['sap_code']?></td>
						<td scope="col"><input type="hidden" name = "normal_price[]" value = "<?=$st_Row['normal_price']?>"><?=number_format($st_Row['normal_price'])?></td>
						<td scope="col"><input style="margin: 5px 0px;height: 25px; text-align : center;" name ="supply_price[]" value="<?=number_format($st_Row['supply_price'])?>"></td>
						<td scope="col"><input type="hidden" name = "stock[]" value = "<?=$st_Row['stock']?>"><?=$st_Row['stock']?></td>
						<td scope="col"><input type="hidden" name = "display_yn[]" value = "<?=$st_Row['display_yn']?>"><?=$st_Row['display_yn']?></td>
						<td scope="col"><input style="margin: 5px 0px;height: 25px; text-align : center;" name ="minium_order[]" value="<?=number_format($st_Row['minium_order'])?>"></td>
						
					</tr>
				<?}
				if($s == 0 ){
					echo '<tr id= "emptyTr"><td colspan="10" class="empty_table">등록된 상품이 없습니다.</td></tr>';
				}
				?>
			<?}?>
			</tbody>


		</table>	
	</div>
</form>


<div id="modal_sapsearch" class="modal fade" role="dialog" tabindex="-1" aria-labelledby="sapsearchLabel" aria-hidden="true">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" id="sapsearchLabel">상품 검색</h4>
			</div>
			<div class="modal-body">
				<div class="tbl_frm01 tbl_wrap">
					<table>
						<tr>
							<td colspan = "3"><label class="control-label">SAP Code or 삼진코드 or 상품명</label></td>
						</tr>
						<tr>
							<td colspan = "2"><input type="text" class="form-control" id="txtModalSapCode"></td>
							<td><button type="button" class="btn btn-primary" id="btnModalSapSearch">검색</button></td>
						</tr>
					</table>
				</div>

				<div class="clearfix"></div>

				<div id="modal_sap_option_frm">
					<div class="tbl_frm01 tbl_wrap">
						<table>
							<colgroup>
								<col width="30%">
								<col width="70%">
							</colgroup>
							<tbody>
								<tr>
									<td colspan = "3">검색되는 코드값이 없습니다.</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
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

		$("#item_insert").click(function (){
			$("#txtModalSapCode").val("");
			$("#modal_sap_option_frm").empty().html();
			$("#modal_sapsearch").modal('show');

		});

		$("#btnModalSapSearch").click(function() {
			var keyword = $("#txtModalSapCode").val();

			var $option_table = $("#modal_sap_option_frm");
			//alert(subID);

			$.post(
				"./b2b_sale_item_insert.php", {
					keyword: keyword
					
				},
				function(data) {
					$option_table.empty().html(data);
				}
			);

		});
	});

	function check_all_o(f){
		var chk = document.getElementsByName("chk[]");
		
    	for (i=0; i<chk.length; i++) {
        if(!chk[i].disabled) chk[i].checked = f.chkall.checked;
		}
		
	}

	function insert_sale_item(a,b,c,d,e,f){
		var $option_table = $("#itemListBody");

		$.post(
			"./b2b_sale_item_opt.php", {
				
				order_no: a,
				sap_code: b,
				item: c,
				hoching : d,
				price : e,
				color : f
			},
			function(data) {
				$("#emptyTr").css("display","none");
				$option_table.append(data);
			}
		);

		$("#modal_sapsearch").modal('hide');

	}

	function save(){
		let fnc_chk = confirm("일괄저장 하시겠습니까?");
		if(fnc_chk){
			$("#fnc_type").val("save");
			var formData = $("#forderlist").serialize();
	
			$.ajax({
				cache : false,
				url : "./save_b2b_sale_item_list.php", // 요기에
				type : 'POST', 
				data : formData, 
				success : function(data) {
					// var jsonObj = JSON.parse(data);
					// console.log(data);
					alert("저장되었습니다.");
					location.reload();
				}, // success 
	
				error : function(xhr, status) {
					alert(xhr + " : " + status);
				}
			}); 
		}
    }

	function display_non(){
		let fnc_chk = confirm("선택 상품 진열 안하시겠습니까?");
		if(fnc_chk){
			$("#fnc_type").val("display");
			$("#seiects_no").val("");
	
			if (!is_checked("chk[]")) {
				alert("상품을 선택해 주세요.");
				return false;
			}
			var $select = new Array();
			$("input[name='chk[]']:checked").each(function() {
				var si_no = $("#si_no_"+this.value).val();
				$select.push(si_no);
			});
	
			var selects = $select.join(",");
				
			$("#seiects_no").val(selects);
	
			var formData = $("#forderlist").serialize();
	
			$.ajax({
				cache : false,
				url : "./save_b2b_sale_item_list.php", // 요기에
				type : 'POST', 
				data : formData, 
				success : function(data) {
					// var jsonObj = JSON.parse(data);
					// console.log(data);
					alert("진열상태가 변경되었습니다.");
					location.reload();
				}, // success 
	
				error : function(xhr, status) {
					alert(xhr + " : " + status);
				}
			}); 
		}

	}

	



</script>



