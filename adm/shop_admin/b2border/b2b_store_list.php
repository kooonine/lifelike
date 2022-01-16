<?
// 업체 코드
include_once('./_common.php');

if (empty($cp_code)) $cp_code = $_GET['now_cp_code'];


$cmp = "SELECT * FROM b2b_company WHERE cp_code = '{$cp_code}' AND cp_gubun = 'Y' AND use_yn = 'Y' limit 1 ";
$cmp_res = sql_fetch($cmp);


$st_comforms  = $_GET['st_comforms']; ;
$st_shutdowns = $_GET['st_shutdowns'];;

$numberOrder = 10;

if ($page_rows > 10 || $page_rows== "0") {
	$numberOrder = $page_rows;
}

if(!empty($st_comforms)){
	$sql_search .= "AND st_comform = '{$st_comforms}' ";
}else{
	$st_comforms = '';
}

if($st_shutdowns == 'all'){
	$st_shutdowns = 'all';
}else{
	if($st_shutdowns){
		$sql_search .= "AND st_shutdown = '{$st_shutdowns}' ";
	}else{
		$st_shutdowns = 'N';
		$sql_search .= "AND st_shutdown = 'N' ";
	}
}



if ($search != "") {
	if ($sel_field != "") {
		$sql_search .= " AND  $sel_field like '%$search%' ";
	}

	if ($save_search != $search) {
		$page = 1;
	}
}


$sqlcnt = " SELECT count(*) as cnt FROM b2b_store_list  WHERE cp_code = '{$cp_code}' " . $sql_search ;
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

$st_sql = "SELECT * FROM b2b_store_list WHERE cp_code = '{$cp_code}'  " . $sql_search  ;
$st_res = sql_query($st_sql);




?>

<button onclick="company_add()">업체등록</button>

<div>업체명: <?=$cmp_res['cp_name']?></div>
<div>업체코드: <?=$cmp_res['cp_code']?></div>
<div>사업자 번호: <?=$cmp_res['cp_number']?></div>


<form id= "storeMainTable" class="local_sch01 local_sch" methed = "get">
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
							<option value="st_name" <?= get_selected($sel_field, 'st_name'); ?>>매장명</option>
							<option value="st_owner" <?= get_selected($sel_field, 'st_owner'); ?>>점주명</option>
							
						</select>

						<label for="search" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
						<input type="text" name="search" value="<?= $search; ?>" id="search" class="frm_input" autocomplete="off">
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row">승인여부</th>
				<td colspan="2">
					<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
						<input type="radio" name="st_comforms" value="" id="st_comforms01" <?= get_checked($st_comforms, '');   ?>>
						<label for="st_comforms01">전체</label>
						<input type="radio" name="st_comforms" value="Y" id="st_comforms03" <?= get_checked($st_comforms, 'Y');  ?> >
						<label for="st_comforms03">승인완료</label>
						<input type="radio" name="st_comforms" value="N" id="st_comforms04" <?= get_checked($st_comforms, 'N');  ?>>
						<label for="st_comforms04">승인대기</label>
					</div>
				</td>
			</tr>

			<tr>
				<th scope="row">폐점여부</th>
				<td colspan="2">
					<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">

						<input type="radio" name="st_shutdowns" value="all" id="st_shutdowns01" <?=get_checked($st_shutdowns, 'all'); ?>>
						<label for="st_shutdowns01">전체</label>
						<input type="radio" name="st_shutdowns" value="N" id="st_shutdowns03" <?=get_checked($st_shutdowns, 'N'); ?>>
						<label for="st_shutdowns03">정상</label>
						<input type="radio" name="st_shutdowns" value="Y" id="st_shutdowns04" <?=get_checked($st_shutdowns, 'Y'); ?>>
						<label for="st_shutdowns04">폐점</label>
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

	<div class="tbl_head01 tbl_wrap">
		<table id="sodr_list">
			<caption>매장 내역 목록</caption>
			<thead>
				<tr>
					<th scope="col">매장코드</th>
					<th scope="col">매장명</th>
					<th scope="col">점주명</th>
					<th scope="col">사업자번호</th>
					<th scope="col">주소</th>
					<th scope="col">승인여부</th>
					<th scope="col">폐점</th>
				</tr>
			</thead>
			<tbody>
			
			<?for($s = 0 ; $st_Row = sql_fetch_array($st_res); $s++) {?>
				<tr>
					<td scope="col"><?=$st_Row['st_code']?></td>
					<td scope="col"><?=$st_Row['st_name']?></td>
					<td scope="col"><?=$st_Row['st_owner']?></td>
					<td scope="col"><?=$st_Row['st_number']?></td>
					<td scope="col">[<?=$st_Row['st_zip']?>] <?=$st_Row['st_addr1']?>  <?=$st_Row['st_addr2']?></td>
					<td scope="col">
						<?if($st_Row['st_comform'] == 'Y') :?>
							승인완료
						<?else:?>
							승인대기<button class='btn-group btn' onclick = "store_control('<?=$st_Row['st_no']?>','comform')">승인</button>
						<?endif?>
					</td>
					<td scope="col">
						<?if($st_Row['st_shutdown'] == 'Y') :?>
							폐점
						<?else:?>
							<button class='btn-group btn' onclick = "store_control('<?=$st_Row['st_no']?>','shutdown')">폐점</button>
						<?endif?>
					</td>
				</tr>
			<?}
			if($s == 0 ){
				echo '<tr><td colspan="7" class="empty_table">등록된 매장이 없습니다.</td></tr>';
			}
			?>
			</tbody>
		</table>	
	</div>
</form>

<div class="modal fade" id="modal_company_add" tabindex="-1" role="dialog" aria-labelledby="modal_company_add">
    <div class="modal-dialog  modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header on-big">
                <div style="font-size: 26px; color: #070707; font-weight: bold; float: left;">업체등록</div>
                <button type="button"  style="margin-top: 5px;" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            
            <div class="modal-body">
                <form id="newCpForm" method="post">
					<div class="tbl_frm01 tbl_wrap">
						<table>
							<tr>
								<th style="width:15%" scope="row">업체명</th>
								<td>
									<input type="text" style="width:100%" id='add_cp_name' name ='add_cp_name' >
								</td>
							</tr>
							<tr>
								<th style="width:15%" scope="row">업체코드</th>
								<td>
									<input type="text" style="width:100%" id='add_cp_code' name ='add_cp_code' >
								</td>
							</tr>
							<tr>
								<th style="width:15%" scope="row">사업자번호</th>
								<td>
									<input type="text" style="width:100%" id='add_cp_number' name ='add_cp_number' >
								</td>
							</tr>
						</table>
					</div>
				</form>
            </div>

			<div class="modal-footer">
				<div class="text-center">
					<button type="button" class="btn btn-info" data-dismiss="modal">취소</button>
					<button type="submit" class="btn btn-info" data-dismiss="modal" id ="btn_submit_cp">확인</button>
				</div>
            </div>
        </div>
    </div>
</div>

<script>
	$(function() {
		
	});

	function company_add(){
		$('#add_cp_name').val('');
		$('#add_cp_code').val('');
		$('#add_cp_number').val('');

        $("#modal_company_add").modal('show');    
    }

	function store_control(st_no,type){
		let msg = '';
		if(type == 'comform' ){
			msg = "해당 업체에 대해 승인 완료 하시겠습니까?";
		}else if (type == 'shutdown'){
			msg = "해당 업체에 대해 폐점 처리 하시겠습니까?";
		}
		var check = confirm(msg);
		if(check){
			var formData = new FormData();
                
			formData.append("st_no", st_no);
			formData.append("type", type);

			$.ajax({
				url:'./ajax_b2b_company_add.php',
				type:'post',
				processData: false,
				contentType:false,
				async: false,
				data: formData,
				
				success:function(data){
					if (data.indexOf('300') !== -1) {
						alert("승인 완료되었습니다.");
						
					}else if (data.indexOf('400') !== -1){
						alert("폐점처리 되었습니다.");
						
					}
					location.reload();         
				}
				
			});
			
		}
		
	}

	

	$("#btn_submit_cp").click(function() {
		if(!$('#add_cp_name').val()){			alert("명");		}
		if(!$('#add_cp_code').val()){			alert("코드");		}
		if(!$('#add_cp_number').val()){			alert("사업자");	}

		var complete = false;

		if($('#add_cp_name').val() && $('#add_cp_code').val() && $('#add_cp_number').val()){
			var check = confirm('업체명:' + $('#add_cp_name').val() + '\n' +'업체코드:' + $('#add_cp_code').val() + '\n' +'사업자번호:' + $('#add_cp_number').val() + '\n' + '위 신규 업체 등록 하시겠습니까?');
			if(check){

				var formData = new FormData();
                
                formData.append("add_cp_name", $('#add_cp_name').val());
                formData.append("add_cp_code", $('#add_cp_code').val());
				formData.append("add_cp_number", $('#add_cp_number').val());
				formData.append("type", "add");


				$.ajax({
					url:'./ajax_b2b_company_add.php',
					type:'post',
					processData: false,
					contentType:false,
					async: false,
					data: formData,
					
					success:function(data){
						if (data.indexOf('200') !== -1) {
							alert("중복된 업체가 존재합니다.");
							
						}else{
							alert("업체 등록 성공!");
							location.reload();
						}            
					}
					
				});
			}
		}
	});



</script>



