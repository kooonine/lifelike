<?
$sub_menu = "200180";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

if ($is_admin != 'super' && $is_admin != 'admin')
	alert('최고관리자만 접근 가능합니다.');

$sql_common = " from lt_shop_coupon_mng a";

$sql_search = " where (1) ";
if ($stx) {
	$sql_search .= " and ( ";
	switch ($sfl) {
		default :
		$sql_search .= " ({$sfl} like '%{$stx}%') ";
		break;
	}
	$sql_search .= " ) ";
}

if ($cm_type != '') {
	$sql_search .= " and cm_type = '{$cm_type}' ";
}
if ($cm_target_type != '') {
	$sql_search .= " and cm_target_type = '{$cm_target_type}' ";
}
if ($cm_method != '') {
	$sql_search .= " and cm_method = '{$cm_method}' ";
}
if ($cm_status != '') {
	$sql_search .= " and cm_status = '{$cm_status}' ";
}

if (!$sst) {
	$sst  = "cm_no";
	$sod = "desc";
}
$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

if($page_rows) $rows = $page_rows;
else $rows = $config['cf_page_rows'];

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select a.*
                , if(cm_type = '0',concat('할인금액 : ',cm_price,'원'),concat('할인율 : ',cm_price,'%')) as str_cm_type
                , if(cm_end_time = '0','기간 제한 없음',concat('발급일로부터 ',cm_end_time,'일 이내')) as str_cm_end_time
                , (select count(*) as cnt from {$g5['g5_shop_coupon_table']} where cm_no = a.cm_no) used_count
                , concat(if(cm_target_type = '0','대상자지정','조건부 자동'), if(cm_target_type2 != '',concat('(',cm_target_type2,')'),'')) as str_cm_target_type
                , if(cm_method = '0','상품쿠폰','주문서쿠폰') as str_cm_method
                , concat(cm_status, if(cm_status = '발급중지' 
                                        ,concat('(',if(cm_status_sdate!='0000-00-00 00:00:00',cm_status_sdate,''),'~',if(cm_status_edate!='0000-00-00 00:00:00',cm_status_edate,''),')')
                                        ,if(cm_status_sdate!='0000-00-00 00:00:00',concat('(중지예정일시:',cm_status_sdate,'~)'),'')
                    ) ) as str_cm_status
{$sql_common}
{$sql_search}
{$sql_order}
limit {$from_record}, {$rows} ";
$result = sql_query($sql);



$excel_sql = $sql;
if(substr_count($sql, "limit")){
    $sqls = explode('limit', $sql);
    $excel_sql = $sqls[0];
}
$headers = array('NO', '쿠폰명', '혜택','사용기간', '발급수', '발급구분', '적용범위', '발급상태');
$bodys = array('NO', 'cm_subject', 'str_cm_type','str_cm_end_time', 'used_count', 'str_cm_target_type', 'str_cm_method', 'str_cm_status');

$enc = new str_encrypt();

$excel_sql = $enc->encrypt($excel_sql);
$headers = $enc->encrypt(json_encode_raw($headers));
$bodys = $enc->encrypt(json_encode_raw($bodys));


$g5['title'] = '쿠폰내역 조회';
include_once ('../admin.head.php');


?>


<!-- @START@ 내용부분 시작 -->

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">

			<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
				<div class="x_title">
					<h4><span class="fa fa-check-square"></span>쿠폰내역 조회<small></small></h4>
					<label class="nav navbar-right"></label>
					<div class="clearfix"></div>
				</div>

				<div class="tbl_frm01 tbl_wrap">
					<table>
						<colgroup>
							<col class="grid_4">
							<col>
							<col>
							<col>
						</colgroup>
						<tr>
							<th scope="col">검색조건</th>
							<td>

								<label for="sfl" class="sound_only">검색대상</label>
								<select name="sfl" id="sfl">
									<option value="cm_subject" <?=get_selected($sfl, 'cm_subject'); ?>>쿠폰명</option>
									<option value="cm_summary" <?=get_selected($sfl, 'cm_summary'); ?>>쿠폰설명</option>
								</select>
								<label for="stx" class="sound_only">검색어</label>
								<input type="text" name="stx" value="<?=$stx; ?>" id="stx" class="frm_input">
							</td>
							<th scope="col">혜택구분</th>
							<td>
								<select name="cm_type" id="cm_type" >
									<option value="" >전체</option>
									<option value="0" <?=get_selected($cm_type, '0'); ?>>할인금액</option>
									<option value="1" <?=get_selected($cm_type, '1'); ?>>할인율</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="col">발급구분</th>
							<td >
								<select name="cm_target_type" id="cm_target_type" >
									<option value="" >전체</option>
									<option value="0" <?=get_selected($cm_target_type, '0'); ?>>대상자 지정발급</option>
									<option value="1" <?=get_selected($cm_target_type, '1'); ?>>조건부 자동발급</option>
								</select>
							</td>
							<th scope="col">적용범위</th>
							<td >
								<select name="cm_method" id="cm_method" >
									<option value="" >전체</option>
									<option value="2" <?=get_selected($cm_method, '2'); ?>>주문서쿠폰</option>
									<option value="0" <?=get_selected($cm_method, '0'); ?>>상품쿠폰</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="col">상태</th>
							<td colspan="3">
								<select name="cm_status" id="cm_status" >
									<option value="" >전체</option>
									<option value="발급중" <?=get_selected($cm_status, '발급중'); ?>>발급중</option>
									<option value="발급중지" <?=get_selected($cm_status, '발급중지'); ?>>발급중지</option>
									<option value="삭제" <?=get_selected($cm_status, '삭제'); ?>>삭제</option>
								</select>
							</td>
						</tr>

						<tr>
							<td class="col-md-12 col-sm-12 col-xs-12 text-right" colspan="4" style="text-align:right;">
								<input type="submit" class="btn btn-primary" value="검색"></input>
							</td>
						</tr>
					</table>
				</div>
			</form>

		</div>

		<div class="x_panel">
			<div class="x_title">
				<h4><span class="fa fa-check-square"></span> 쿠폰목록<small></small></h4>

				<div class="clearfix"></div>
			</div>

			<form name="fcouponlist" id="fcouponlist" method="post" action="./configform_coupon_list_delete.php" onsubmit="return fcouponlist_submit(this);">
				<input type="hidden" name="sst" value="<?=$sst; ?>">
				<input type="hidden" name="sod" value="<?=$sod; ?>">
				<input type="hidden" name="sfl" value="<?=$sfl; ?>">
				<input type="hidden" name="stx" value="<?=$stx; ?>">
				<input type="hidden" name="page" value="<?=$page; ?>">
				<input type="hidden" name="token" value="">

				<div class="tbl_head01 tbl_wrap">
					<div class="pull-right">
						<select name="page_rows" onchange="location.href='<?=$_SERVER['SCRIPT_NAME'].'?'.$qstr ?>&page_rows='+$(this).val();">
							<option value="10" <?=get_selected($page_rows, '10') ; ?> >10개씩 보기</option>
							<option value="20" <?=get_selected($page_rows, '20') ; ?> >20개씩 보기</option>
							<option value="30" <?=get_selected($page_rows, '30') ; ?> >30개씩 보기</option>
						</select>
					</div>
					<table id="test">
						<caption>목록</caption>
						<thead>
							<tr>
								<th colspan="11">
									<div class="pull-right">
										<input type="button" class="btn btn_02" id="excel_download1" value="엑셀다운로드"></input>
										<input type="submit" name="act_button" value="삭제" onclick="document.pressed=this.value" class="btn btn_02">
									</div>
								</th>
							</tr>
							<tr>
								<th scope="col">
									<label for="chkall" class="sound_only">쿠폰 전체</label>
									<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
								</th>
								<th scope="col" class="col-md-2">쿠폰명</th>
								<th scope="col" class="">혜택</th>
								<th scope="col" class="">사용기간</th>
								<th scope="col" class="">발급수</th>
								<th scope="col" class="">발급구분</th>
								<th scope="col" class="">적용범위</th>
								<th scope="col" class="">발급상태</th>
								<th scope="col" class="">발급</th>
								<th scope="col" class="">조회</th>
								<th scope="col" class="">복사</th>
							</tr>
						</thead>
						<tbody>
							<?
							$colspan = 11;
							for ($i=0; $row=sql_fetch_array($result); $i++)
							{
				// 쿠폰사용회수
								$sql = " select count(*) as cnt from {$g5['g5_shop_coupon_table']} where cm_no = '{$row['cm_no']}' ";
								$tmp = sql_fetch($sql);
								$used_count = $tmp['cnt'];

								$bg = 'bg'.($i%2);
								?>
								<tr class="<?=$bg; ?>">
									<td class="td_chk">
										<label class="sound_only">쿠폰 전체</label>
										<input type="hidden" id="cm_no_<?=$i; ?>" name="cm_no[<?=$i; ?>]" value="<?=$row['cm_no']; ?>">
										<input type="checkbox" id="chk_<?=$i; ?>" name="chk[]" value="<?=$i; ?>" title="내역선택">
									</td>
									<td class="td_left">
										<label class="sound_only">쿠폰명</label>
										<a href="./configform_coupon_detail.php?w=u&amp;cm_no=<?=$row['cm_no']; ?>&amp;<?=$qstr; ?>">
											<?=$row['cm_subject']; ?>
										</a>
									</td>
									<td class="td_left">
										<label class="sound_only">혜택</label>
										<?
										switch($row['cm_type']) {
											case '0':
											echo '할인금액 : '.number_format($row['cm_price']).'원';
											break;
											case '1':
											echo '할인율 : '.$row['cm_price'].'%';
											break;
										}
										?></td>
										<td>
											<label class="sound_only">사용기간</label>
											<?
											if ($row['cm_end_time'] == 0) echo "기간 제한 없음";
											else echo "발급일로부터 ".$row['cm_end_time']."일 이내";
											?></td>
											<td style="width: 100px;">
												<label class="sound_only">발급수</label>
												<?=$used_count ?>
											</td>
											<td class="td_left" style="width: 150px;">
												<label class="sound_only">발급구분</label>
												<?=($row['cm_target_type'] == '0')?"대상자지정":"조건부 자동" ?>
												<?=($row['cm_target_type2'] != '')?"(".$row['cm_target_type2'].")":"" ?>
											</td>
											<td class="td_name sv_use">
												<label class="sound_only">적용범위</label>
												<div><?=($row['cm_method'] =='0')?"상품쿠폰":"주문서쿠폰"; ?></div>
											</td>
											<td class="td_name sv_use">
												<label class="sound_only">발급상태</label>
												<div>
													<? if ($row['cm_status'] == '발급중지') {
														echo $row['cm_status'];
														echo "<br/>(";
														if ($row['cm_status_sdate'] != '0000-00-00 00:00:00') echo $row['cm_status_sdate'];
														echo " ~";
														if ($row['cm_status_edate'] != '0000-00-00 00:00:00') echo '<br/>'.$row['cm_status_edate'];
														echo ")";
													} else {
														echo $row['cm_status'];
														if ($row['cm_status_sdate'] != '0000-00-00 00:00:00') {
															echo "<br/>(중지예정일시:".$row['cm_status_sdate']." ~)";
														}
													}
													?>
												</div>
											</td>
											<td class="td_mng td_mng_s">
												<? if($row['cm_target_type'] == '0' && $row['cm_status'] != '삭제') { ?>
													<a href="./configform_coupon_issuance.php?cm_no=<?=$row['cm_no']; ?>&amp;<?=$qstr; ?>" class="btn btn_02"><span class="sound_only"><?=$row['cm_no']; ?> </span>발급</a>
												<? } else if($row['cm_status'] == '발급중') {?>
													<input type="button" class="btn btn_01" value="발급중지" name="coupon_issuance_stop" cmno="<?=$row['cm_no']; ?>" cmsubject="<?=$row['cm_subject']; ?>"  />
												<? } else if($row['cm_status'] == '발급중지') {?>
													<input type="button" class="btn btn_03" value="발급재개" name="coupon_issuance_restart" cmno="<?=$row['cm_no']; ?>" cmsubject="<?=$row['cm_subject']; ?>" />
												<? }?>
											</td>
											<td class="td_mng td_mng_s">
												<a href="./configform_coupon_issuance_history.php?cm_no=<?=$row['cm_no']; ?>&amp;<?=$qstr; ?>" class="btn btn_03"><span class="sound_only"><?=$row['cm_no']; ?> </span>조회</a>
											</td>
											<td class="td_mng td_mng_s">
												<input type="button" class="btn btn_03" value="복사" name="coupon_copy" cmno="<?=$row['cm_no']; ?>" cmsubject="<?=$row['cm_subject']; ?>" />
											</td>
										</tr>
										<?
									}

									if ($i == 0)
										echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
									?>
								</tbody>
								<thead>
									<tr>
										<th colspan="11">
											<div class="pull-right">
												<input type="button" class="btn btn_02" id="excel_download2" value="엑셀다운로드"></input>
												<input type="submit" name="act_button" value="삭제" onclick="document.pressed=this.value" class="btn btn_02">
											</div>
										</th>
									</tr>
								</thead>
							</table>

						</div>
						<?=get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
					</div>


				</div>


			</div>

			<script>
				$(document).ready(function() {


					$("#excel_download1, #excel_download2").click(function(){
						var $form = $('<form></form>');     
						$form.attr('action', '<?=G5_ADMIN_URL?>/ajax.excel_download.php');
					    $form.attr('method', 'post');
					    $form.appendTo('body');
					     
					    var exceldata = $('<input type="hidden" value="<?=$excel_sql?>" name="exceldata">');
					    var headerdata = $('<input type="hidden" value="<?=$headers?>" name="headerdata">');
					    var bodydata = $('<input type="hidden" value="<?=$bodys?>" name="bodydata">');
					    $form.append(exceldata).append(headerdata).append(bodydata);
					    $form.submit();
					});
					
//발급재개
$('input[name="coupon_issuance_restart"]').click(function(){
	var cmsubject = $(this).attr("cmsubject");
	var cmno = $(this).attr("cmno");

	$('#restart_cm_no').val(cmno);

	$('#coupon_issuance_restart_reservation1').hide();
	$('#coupon_issuance_restart_reservation2').hide();
	$('#coupon_issuance_restart_label').html(cmsubject);
	$('#coupon_issuance_restart_modal').modal('show');
});

//발급중지
$('input[name="coupon_issuance_stop"]').click(function(){
	var cmsubject = $(this).attr("cmsubject");
	var cmno = $(this).attr("cmno");

	$('#stop_cm_no').val(cmno);

	$('#coupon_issuance_stop_stop1').hide();
	$('#coupon_issuance_stop_stop2').hide();
	$('#coupon_issuance_stop_btn_label').html(cmsubject);
	$('#coupon_issuance_stop_btn_modal').modal('show');

	$('#coupon_issuance_stop_type').hide();
});

//복사
$('input[name="coupon_copy"]').click(function(){
	var cmsubject = $(this).attr("cmsubject");
	var cmno = $(this).attr("cmno");

	if(confirm("'"+cmsubject+"'쿠폰을 복사하시겠습니까?"))
	{
		$.post(
			"ajax.configform_coupon_update.php",
			{	cmd : "copy"
			, cm_no:  cmno
		},
		function(data) {
			var responseJSON = JSON.parse(data);
			if(responseJSON.result == "S"){
				alert("'"+cmsubject+"'쿠폰을 복사 완료하였습니다.");
				$("#fsearch").submit();

			}else {
				alert("오류가 발생했습니다. 다시 시도해주시기 바랍니다.");
				return false;
			}
		}
		);

	}

});

$('input[name="coupon_issuance_restart_type"]').click(function(){
	if($(this).val() == 'now'){
		$('#cm_status_edate').val('');

		$('#coupon_issuance_restart_reservation1').hide();
		$('#coupon_issuance_restart_reservation2').hide();
		$(this).parents('tr').attr('rowspan','2');
	}else{
		$(this).parents('tr').attr('rowspan','4');
		$('#coupon_issuance_restart_reservation1').show();
		$('#coupon_issuance_restart_reservation2').show();
	}
});

$('input[name="coupon_issuance_stop_type"]').click(function(){
	if($(this).val() == 'now'){
		$('#cm_status_sdate').val('');

		$('#coupon_issuance_stop_stop1').hide();
		$('#coupon_issuance_stopt_stop2').hide();
		$(this).parents('tr').attr('rowspan','2');
	}else{
		$(this).parents('tr').attr('rowspan','4');
		$('#coupon_issuance_stop_stop1').show();
		$('#coupon_issuance_stop_stop2').show();
	}
});

$('#cm_status_sdate_picker, #cm_status_edate').datetimepicker({
	ignoreReadonly: true,
	allowInputToggle: true,
	format: 'YYYY-MM-DD HH:mm',
	locale : 'ko'
});

$("#coupon_issuance_stop_btn").click(function () {
	var cm_no = $('#stop_cm_no').val();

	if($('input[name="coupon_issuance_stop_type"]:checked').val() == 'stop'){
		if($("#cm_status_sdate").val() == ""){
			alert("발급중지 시작일을 설정하십시오.");
			return false;
		}
	}

	$.post(
		"ajax.configform_coupon_update.php",
		{	cmd : "stop"
		, cm_no: $('#stop_cm_no').val()
		, stop_type: $('input[name="coupon_issuance_stop_type"]:checked').val()
		, cm_status_sdate: $('#cm_status_sdate').val()
	},
	function(data) {
		//alert(data);
		var responseJSON = JSON.parse(data);

		if(responseJSON.result == "S"){

			if($('input[name="coupon_issuance_stop_type"]:checked').val() == 'now'){
				alert('쿠폰 발급이 중지되었습니다.\n쿠폰명:'+$('#coupon_issuance_stop_btn_label').text());
			}else {
				alert('쿠폰 발급 중지가 설정되었습니다.\n쿠폰명:'+$('#coupon_issuance_stop_btn_label').text()+'\n'
					+'발급중지 일시 : '+$("#cm_status_sdate").val());
			}

			$("#fsearch").submit();

		}else {
			alert("오류가 발생했습니다. 다시 시도해주시기 바랍니다..");
			return false;
		}
	}
	);


});

$("#coupon_issuance_restart_btn").click(function () {
	var cm_no = $('#restart_cm_no').val();

	if($('input[name="coupon_issuance_restart_type"]:checked').val() == 'reservation'){
		if($("#cm_status_edate").val() == ""){
			alert("발급중지 해제일을 설정하십시오.");
			return false;
		}
	}

	$.post(
		"ajax.configform_coupon_update.php",
		{	cmd : "restart"
		, cm_no: $('#restart_cm_no').val()
		, restart_type: $('input[name="coupon_issuance_restart_type"]:checked').val()
		, cm_status_edate: $('#cm_status_edate').val()
	},
	function(data) {
		//alert(data);
		var responseJSON = JSON.parse(data);

		if(responseJSON.result == "S"){

			if($('input[name="coupon_issuance_restart_type"]:checked').val() == 'now'){
				alert('쿠폰 발급이 재개되었습니다.\n쿠폰명:'+$('#coupon_issuance_restart_label').text());
			}else {
				alert('쿠폰 발급 재개가 설정되었습니다.\n쿠폰명:'+$('#coupon_issuance_restart_label').text()+'\n'
					+'발급재개 일시 : '+$("#cm_status_edate").val());
			}

			$("#fsearch").submit();

		}else {
			alert("오류가 발생했습니다. 다시 시도해주시기 바랍니다..");
			return false;
		}
	}
	);
});
	window.addEventListener("keydown", (e) => {
    	if (e.keyCode == 13) {
        	document.getElementById('fsearch').submit();
    	}
  	})

});

function fcouponlist_submit(f){
	if (!is_checked("chk[]")) {
		alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
		return false;
	}

	if(document.pressed == "삭제") {
		if(!confirm("선택한 쿠폰을 정말 삭제하시겠습니까?")) {
			return false;
		}
	}

	return true;
}
</script>


<div class="modal fade" id="coupon_issuance_stop_btn_modal" tabindex="-1" role="dialog" aria-labelledby="coupon_issuance_stop_modal">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">쿠폰 발급중지</h4>

			</div>
			<div class="modal-body" >
				<div class="tbl_frm01 tbl_wrap ">

					<table>
						<tbody>
							<tr>
								<th>쿠폰명
								</th>
								<td>
									<label id="coupon_issuance_stop_btn_label"></label>
								</td>
							</tr>
							<tr>
								<th rowspan="4">
									중지시점
								</th>
								<td>
									<label><input type="radio" name="coupon_issuance_stop_type" value = "now" checked="checked"/> 즉시발급중지 </label>
								</td>
							</tr>
							<tr>
								<td>
									<label><input type="radio" name="coupon_issuance_stop_type" value = "stop"/> 발급중지 시작일 변경 </label>
								</td>
							</tr>

							<tr id='coupon_issuance_stop_stop1'>
								<td>
									<div style="position:relative">
										<div class='input-append date' >
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class='col-md-2 col-sm-2 col-xs-2'>
													<label>시작:  </label>
												</div>
												<div class='col-md-5 col-sm-5 col-xs-5'>
													<div class='input-group date' id='cm_status_sdate_picker'>
														<input type='text' class="form-control" id="cm_status_sdate" name="cm_status_sdate" />
														<span class="input-group-addon">
															<span class="glyphicon glyphicon-calendar"></span>
														</span>
													</div>
												</div>

											</div>
										</div>
									</div>
								</td>
							</tr>
							<tr id='coupon_issuance_stop_stop2'>
								<td>
									<label>-설정한 시작일 이후에 쿠폰 발급이 중지됩니다.</label>
								</td>
							</tr>
						</tbody>
					</table>
				</div>


			</div>
			<div class="modal-footer">
				<br><br><br>
				<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
				<button type="button" class="btn btn-default" id="coupon_issuance_stop_btn">확인</button>
				<input type="hidden" name="stop_cm_no" id="stop_cm_no" value="">

			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="coupon_issuance_restart_modal" tabindex="-1" role="dialog" aria-labelledby="coupon_issuance_restart_modal">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">쿠폰 발급 재개</h4>

			</div>
			<div class="modal-body" >
				<div class="tbl_frm01 tbl_wrap ">

					<table>
						<tbody>
							<tr>
								<th>쿠폰명
								</th>
								<td>
									<label id="coupon_issuance_restart_label"></label>
								</td>
							</tr>
							<tr>
								<th rowspan="4">
									재개시점
								</th>
								<td>
									<label><input type="radio" name="coupon_issuance_restart_type" value = "now" checked="checked"/> 즉시발급재개(발급중지 해제일 현재로 변경) </label>
								</td>
							</tr>
							<tr>
								<td>
									<label><input type="radio" name="coupon_issuance_restart_type" value = "reservation"/> 발급중지 해제일 변경 </label>
								</td>
							</tr>

							<tr id='coupon_issuance_restart_reservation1'>
								<td>
									<div style="position:relative">
										<div class='input-append date' >
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class='col-md-2 col-sm-2 col-xs-2'>
													<label>해제:  </label>
												</div>
												<div class='col-md-5 col-sm-5 col-xs-5'>
													<div class='input-group date' id='cm_status_edate_picker'>
														<input type='text' class="form-control" id="cm_status_edate" name="cm_status_edate" />
														<span class="input-group-addon">
															<span class="glyphicon glyphicon-calendar"></span>
														</span>
													</div>

												</div>
											</div>
										</div>
									</div>
								</td>
							</tr>
							<tr id='coupon_issuance_restart_reservation2'>
								<td>
									<label>-설정한 해제일 이후에 쿠폰 발급이 재개됩니다.</label>
								</td>
							</tr>
						</tbody>
					</table>
				</div>


			</div>
			<div class="modal-footer">
				<br><br><br>
				<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
				<button type="button" class="btn btn-default" id="coupon_issuance_restart_btn">확인</button>
				<input type="hidden" name="restart_cm_no" id="restart_cm_no" value="">
			</div>
		</div>
	</div>
</div>



<!-- @END@ 내용부분 끝 -->



<?
include_once ('../admin.tail.php');
?>
