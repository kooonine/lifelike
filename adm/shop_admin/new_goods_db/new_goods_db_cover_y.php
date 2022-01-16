<?
$where = array();

$doc = strip_tags($doc);


$od_type = $_GET['od_type'];
$yearsServer = date('Y',  G5_SERVER_TIME);
if($od_type == "") $od_type = "Y";

$txt1 = $_POST['stx'];

$sql_search = " where (1) and ps_display = 'Y' and ps_code_item_type = 'C' ";


if($sfl){
    if($stx){
        switch($sfl){
            
            case 'sap_code':
                preg_match_all("/[^() || \-\ \/\,]+/", $txt1,$sap_cd_list);
                $sap_cd_in_list = empty($sap_cd_list[0])?'NULL':"'".join("','", $sap_cd_list[0])."'";
                $sql_search.= " and prod_code IN({$sap_cd_in_list})";

                break;
            
            case 'it_name':
                $sql_search .= " and ps_it_name like '%{$txt1}%' ";
                break;
        }
    }else{

    }
}


$all_brand = false;
if ($brands) {
    $brand_item = implode("','", explode(',', $brands));
    $sql_search .= " and ps_brand in ('{$brand_item}') ";
}else{
    $all_brand=true;
}

if(!$db_cover_year){
    // $sql_search .= " and ps_ipgo_status = 'N' ";
}else{
    if(strpos($db_cover_year , '>') === false){
        $year = substr($db_cover_year,-2);
        $sql_search .= " and ps_code_year = '{$year}' ";
    }else{
        
        $year .= substr($db_cover_season,-2);
        $sql_search .= " and ps_code_year >= {$year}";
    }
    
    
}

if(!$db_cover_season){
    // $sql_search .= " and ps_ipgo_status = 'N' ";
}else{
    $season = $db_cover_season;
    $sql_search .= " and ps_code_season = '{$season}' ";
}


if(!$db_cover_gb){
    // $sql_search .= " and ps_ipgo_status = 'N' ";
}else{
	$code = $db_cover_gb;
	if($code == '정상'){
		$sql_search .= " and ps_job_gubun IN ('정상') ";
	}else if ($code == '기획') {

		$sql_search .= " and ps_job_gubun IN ('기획') ";
	}
}

$total_count = 0;
if(($db_cover_year && $db_cover_season) || $txt1 ){
	
	// 테이블의 전체 레코드수만 얻음
	$cnt_sql = "SELECT COUNT(*) AS cnt FROM (SELECT ps_it_name  FROM lt_prod_schedule {$sql_search}  GROUP BY ps_it_name) pin";
	$cnt_row = sql_fetch($cnt_sql);
	$total_count = $cnt_row['cnt'];

	

	if($limit_list) {$rows = $limit_list;
	// else $rows = $config['cf_page_rows'];
	}else {$rows = 50;}
	// $rows=4;

	// $total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	// if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
	// $from_record = ($page - 1) * $rows; // 시작 열을 구함

	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산

	if ($total_page < 2 || empty($page)) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함


	//행 병ㅎ합 ver 1.1 수정 20201111
	$abs_sql = "select MIN(PS.ps_origin_ps_id) AS sort , PS.* from lt_prod_schedule AS PS {$sql_search} GROUP BY ps_it_name ORDER BY {$chain_orderby} sort DESC limit $from_record, $rows ";
	$abs_result = sql_query($abs_sql);

	$abs_result1 = sql_query($abs_sql);

	$sub_table_data ;
	for ($tdi = 0; $row_sub_table_data = sql_fetch_array($abs_result1); $tdi++) {
		if(!empty($sub_table_data)){
			$sub_table_data .=  ',';
		}
		$sub_table_data .= "'". $row_sub_table_data['ps_it_name'] ."'";
	}

	$qstr= "od_type=".$od_type."&amp;sfl=it_name&amp;stx=".$stx."&amp;sc_it_time=".$sc_it_time."&amp;db_cover_year=".$db_cover_year."&amp;db_cover_season=".$db_cover_season."&amp;db_cover_gb=".$db_cover_gb."&amp;limit_list=".$limit_list."&amp;page=".$page;

	$token = get_admin_token();

}else{

}

?>
<script src="../total_order/jquery.table2excel.js"></script>

<form id="orderMainTable" name="frmorderlist" class="local_sch01 local_sch" onsubmit="$('#page').val('1');" method="post">
	
	<input type="hidden" name="page" id="page" value="<?= $page; ?>">
	<input type="hidden" name = "brands" value='<?=$brands?>' id="brands">
	<input type="hidden" name="od_type" value="<?= $od_type; ?>">
	<div class="tbl_frm01 tbl_wrap">
		<table>
			<colgroup>
				<col class="grid_4">
				<col>
				<col class="grid_3">
			</colgroup>
			<tr>
				<th scope="row" style="width:15%;">검색분류</th>
				<td colspan="2">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<select name="sfl" id="sfl">
							<option value="it_name" <?= get_selected($sfl, 'it_name'); ?>>아이템명</option>
							<option value="sap_code" <?= get_selected($sfl, 'sap_code'); ?>>삼진코드</option>
						</select>

						<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
						<input type="text" name="stx" onkeydown="enterSearch();" value="<?= $stx; ?>" id="stx" class="frm_input" autocomplete="off">
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row">브랜드</th>
				<td colspan="2">
				<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
					<label><input type="checkbox" onkeydown="enterSearch();" value="" id="brand_0"  <?php if(!$brands) echo "checked"; ?>>전체</label>&nbsp;&nbsp;
					<label><input type="checkbox" onkeydown="enterSearch();" value="소프라움" id="brand_1" class="brand" <?php if((substr_count($brands, '소프라움') >= 1) || $all_brand) echo "checked"; ?>  >소프라움</label>&nbsp;&nbsp;
					<label><input type="checkbox" onkeydown="enterSearch();" value="쉐르단" id="brand_2" class="brand" <?php if((substr_count($brands, '쉐르단') >= 1) || $all_brand) echo "checked"; ?> >쉐르단</label>&nbsp;&nbsp;
					<label><input type="checkbox" onkeydown="enterSearch();" value="랄프로렌홈" id="brand_3" class="brand" <?php if((substr_count($brands, '랄프로렌홈') >= 1)|| $all_brand) echo "checked"; ?> >랄프로렌홈</label>&nbsp;&nbsp;
					<label><input type="checkbox" onkeydown="enterSearch();" value="베온트레" id="brand_4" class="brand" <?php if((substr_count($brands, '베온트레') >= 1) || $all_brand) echo "checked"; ?> >베온트레</label>&nbsp;&nbsp;
					<label><input type="checkbox" onkeydown="enterSearch();" value="링스티드던" id="brand_5" class="brand" <?php if((substr_count($brands, '링스티드던') >= 1) || $all_brand) echo "checked"; ?> >링스티드던</label>&nbsp;&nbsp;
					<label><input type="checkbox" onkeydown="enterSearch();" value="로자리아" id="brand_6" class="brand" <?php if((substr_count($brands, '로자리아') >= 1) || $all_brand) echo "checked"; ?> >로자리아</label>&nbsp;&nbsp;
					<label><input type="checkbox" onkeydown="enterSearch();" value="그라치아노" id="brand_7" class="brand" <?php if((substr_count($brands, '그라치아노') >= 1) || $all_brand) echo "checked"; ?> >그라치아노</label>&nbsp;&nbsp;
					<label><input type="checkbox" onkeydown="enterSearch();" value="시뇨리아" id="brand_8" class="brand" <?php if((substr_count($brands, '시뇨리아') >= 1) || $all_brand) echo "checked"; ?> >시뇨리아</label>&nbsp;&nbsp;
					<label><input type="checkbox" onkeydown="enterSearch();" value="플랫폼일반" id="brand_9" class="brand" <?php if((substr_count($brands, '플랫폼일반') >= 1) || $all_brand) echo "checked"; ?> >플랫폼일반</label>&nbsp;&nbsp;
					<label><input type="checkbox" onkeydown="enterSearch();" value="플랫폼렌탈" id="brand_10" class="brand" <?php if((substr_count($brands, '플랫폼렌탈') >= 1) || $all_brand) echo "checked"; ?> >플랫폼렌탈</label>&nbsp;&nbsp;
					<label><input type="checkbox" onkeydown="enterSearch();" value="온라인" id="brand_11" class="brand" <?php if((substr_count($brands, '온라인') >= 1) || $all_brand) echo "checked"; ?> >온라인</label>&nbsp;&nbsp;
					<label><input type="checkbox" onkeydown="enterSearch();" value="템퍼" id="brand_12" class="brand" <?php if((substr_count($brands, '템퍼') >= 1) || $all_brand) echo "checked"; ?> >템퍼</label>&nbsp;&nbsp;
				</div>
				</td>
			</tr>
			<tr>
				<th scope="row">시즌</th>
				<td colspan="2">
				<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
				
					<select name="db_cover_year" id="db_cover_year">
						<option value="" <?= get_selected($db_cover_year, ''); ?>>선택</option>
						<? for($i = (int)$yearsServer+1; 2009 < $i; $i--) {  ?>
							<option value=<?= $i?> <?= get_selected($db_cover_year, $i); ?>><?= $i?>년</option>
						<? }?>
					</select>
					
					<select  name="db_cover_season" id="db_cover_season">
						<option value="" <?= get_selected($db_cover_season, ''); ?> >선택</option>
						<option value="S" <?= get_selected($db_cover_season, 'S'); ?> >SS</option>
						<option value="H" <?= get_selected($db_cover_season, 'H'); ?> >HS</option>
						<option value="F" <?= get_selected($db_cover_season, 'F'); ?> >FW</option>
						<option value="A" <?= get_selected($db_cover_season, 'A'); ?> >AA</option>
					</select>
					
					<select  name="db_cover_gb" id="db_cover_gb" >
						<option value="" <?= get_selected($db_cover_gb, ''); ?> >선택</option>
						<option value="정상" <?= get_selected($db_cover_gb, '정상'); ?>>정상</option>
						<option value="기획" <?= get_selected($db_cover_gb, '기획'); ?>>기획</option>
					</select>
				</div>
				</td>
			</tr>

			<tr>
				<th scope="row">보기</th>
				<td colspan="2">
				<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
					<select name="limit_list" onchange="$('#orderMainTable').submit();">
						<option value="50" <?= get_selected($limit_list, '50'); ?> >50개</option>
						<option value="100" <?= get_selected($limit_list, '100'); ?> >100개</option>
						<option value="200" <?= get_selected($limit_list, '200'); ?> >200개</option>
						<option value="300" <?= get_selected($limit_list, '300'); ?> >300개</option>
						<option value="400" <?= get_selected($limit_list, '400'); ?> >400개</option>
						<option value="0" <?= get_selected($limit_list, '0'); ?> >전체 보기</option>
					</select>
				</div>
				</td>
			</tr>
		</table>
	</div>

	<div class="form-group">
		<div class="col-md-12 col-sm-12 col-xs-12 text-center">
			<button class="btn btn_02" type="reset" id="btn_clear">초기화</button>
			<button type="button" class="btn btn-success" id="fa-search"><i class="fa fa-search" aria-hidden="true"></i>검색</button>
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
			<input type="button" value="엑셀다운로드" class="btn btn_02" onclick="down_excel();">
		</div>
	</div>

	<div class="tbl_head01 tbl_wrap">
		
		<table id="dbcover_list" class="table table-dark" style="width : 1070px;">
			<caption>상품DB전산화(커버) 일정</caption>
			<colgroup>
				<col width="150px;" />
				<col width="60px;" />
				<col width="90px;" />
				<col width="90px;" />
				<col width="90px;" />
				<col width="90px;" />
				<col width="90px;" />
				<col width="90px;" />
				<col width="90px;" />
				<col width="90px;" />
				<col width="90px;" />
				<col width="90px;" />
			</colgroup>
		<?
		for ($i = 0; $row = sql_fetch_array($abs_result); $i++) {
			
			$total_main_c = 0;
			$total_main_o = 0;
			$total_main_b = 0;
			$total_codi_c = 0;
			$total_codi_o = 0;
			$total_codi_b = 0;
			$total_code1_c = 0;
			$total_code1_o = 0;
			$total_code1_b = 0;
			
			$total_prod_qty = 0;
		?>

			<thead>
				<tr >
					<th colspan="2" class="b_w"><?=$row['ps_it_name']?></th>
					
					<th class="b_w bor2"><a class="noExport" style="color: black;" onclick="balju_print('<?=$row['ps_id']?>')" >발주서출력</a></th>
					<th colspan = "3" class="b_y bor2">메인</th>
					<th colspan = "3" class="b_y bor2">코디</th>
					<th colspan = "3" class="b_y bor2">코디1</th>
				</tr>
				<tr>
					<th class="b_g">구분</th>
					<th class="b_g">규격</th>
					<th class="b_g bor2">생산수량</th>
					<th class="b_g">퀼팅</th>
					<th class="b_g">원헤드</th>
					<th class="b_g bor2">봉제</th>
					<th class="b_g">퀼팅</th>
					<th class="b_g">원헤드</th>
					<th class="b_g bor2">봉제</th>
					<th class="b_g">퀼팅</th>
					<th class="b_g">원헤드</th>
					<th class="b_g">봉제</th>
				</tr>
			</thead>
			<tbody>
				<?
				$ch_sql = "select * from lt_prod_schedule {$sql_search} and ps_it_name = '{$row['ps_it_name']}' ORDER BY  ps_origin_ps_id IS NULL ASC , ps_origin_ps_id ASC, ps_id ASC ";
				$ch_result = sql_query($ch_sql);
				
				$total_main_c = 0;
			$total_main_o = 0;
			$total_main_b = 0;
			$total_codi_c = 0;
			$total_codi_o = 0;
			$total_codi_b = 0;
			$total_codi1_c = 0;
			$total_codi1_o = 0;
			$total_codi1_b = 0;
				
				for ($ch_i = 0; $ch_row = sql_fetch_array($ch_result); $ch_i++) {
					$jo_sql = "select * from lt_job_order where ps_id = '{$ch_row['ps_id']}' order by jo_id ASC ";
					$jo_cnt_sql = "select count(*) as cnt from lt_job_order where ps_id = '{$ch_row['ps_id']}'  ";
					$jo_result = sql_query($jo_sql);
					$jo_cnt_result = sql_fetch($jo_cnt_sql);

					$ps_sizes = array();
					if (!empty($ch_row['ps_size'])) {
						$ps_sizes = json_decode($ch_row['ps_size'], true);
					}

				?>
					
						<?for ($ii = 0; $jo_row = sql_fetch_array($jo_result) ; $ii++) {
							$prod_qty = 0;
							
							$mater_type = array();
							if (!empty($jo_row['jo_mater_info'])) {
								$mater_type = json_decode($jo_row['jo_mater_info'], true);
							}
							
							foreach ($ps_sizes as $psi => $psd) {
								if($psd['size'] == $jo_row['jo_size_code']){
									$prod_qty = $psd['qty'];
									$total_prod_qty += $psd['qty'];
								}
							
								
							}
							// if(!empty($jo_row['jo_mater_info']['main'])){

							// 	echo '<script>';
							// 	  echo 'console.log('. json_encode( $mater_type ) .')';
							// 	echo '</script>';
							// }
							$main_c = 0;
							$main_o = 0;
							$main_b = 0;
							$codi_c = 0;
							$codi_o = 0;
							$codi_b = 0;
							$codi1_c = 0;
							$codi1_o = 0;
							$codi1_b = 0;
							foreach ($mater_type as $mt => $mts) {
								if(!empty($mts['main']) && $mts['main'] == '메인' ){
									switch($mts['bongje']){
										case '퀼팅' :
											$main_c += $mts['yd'];
											$total_main_c += (($prod_qty*1) * ($mts['yd'] *1));
										break;
										case '원헤드' :
											$main_o += $mts['yd'];
											$total_main_o += ($prod_qty * $mts['yd']);
										break;
										case '봉제' :
											$main_b += $mts['yd'];
											$total_main_b += ($prod_qty * $mts['yd']);
										break;
									} 
								}
								if(!empty($mts['main']) && $mts['main'] == '코디' ){
									switch($mts['bongje']){
										case '퀼팅' :
											$codi_c += $mts['yd'];
											$total_codi_c += ($prod_qty * $mts['yd']);
										break;
										case '원헤드' :
											$codi_o += $mts['yd'];
											$total_codi_o += ($prod_qty * $mts['yd']);
										break;
										case '봉제' :
											$codi_b += $mts['yd'];
											$total_codi_b += ($prod_qty * $mts['yd']);
										break;
									}   
								}
								if(!empty($mts['main']) && $mts['main'] == '코디1' ){
									switch($mts['bongje']){
										case '퀼팅' :
											$codi1_c += $mts['yd'];
											$total_codi1_c += ($prod_qty * $mts['yd']);
										break;
										case '원헤드' :
											$codi1_o += $mts['yd'];
											$total_codi1_o += ($prod_qty * $mts['yd']);
										break;
										case '봉제' :
											$codi1_b += $mts['yd'];
											$total_codi1_b += ($prod_qty * $mts['yd']);
										break;
										
									}
								}
								
							}
						?>
						<tr>
							<?if ($ii == 0) : ?>
							<td class="b_w vtc" rowspan = <?=$jo_cnt_result['cnt']?>><?=$ch_row['ps_prod_name']?></td>
							<?endif?>
							<td class="b_w"><?=$jo_row['jo_size_code']?></td>
							<td class="b_d txt_right bor2"><?=number_format($prod_qty) > 0 ? number_format($prod_qty) : '-'?></td>
							<td class="b_g txt_right"><?=$main_c?></td>
							<td class="b_w txt_right"><?=$main_o?></td>
							<td class="b_w txt_right bor2"><?=$main_b?></td>
							<td class="b_g txt_right"><?=$codi_c?></td>
							<td class="b_w txt_right"><?=$codi_o?></td>
							<td class="b_w txt_right bor2"><?=$codi_b?></td>
							<td class="b_g txt_right"><?=$codi1_c?></td>
							<td class="b_w txt_right"><?=$codi1_o?></td>
							<td class="b_w txt_right"><?=$codi1_b?></td>
						</tr>
						<? } ?>
					
					
					
				</td>
						
					</tr>
				<?
				}
				
				
				?>
				
				<tr>
					<td class="b_w">합계</td>
					<td class="b_g"></td>
					<td class="b_g bor2 total_prod_qty_<?=$row['ps_id']?>"><?=number_format($total_prod_qty)?></td>
					<td class="b_g txt_right"><?=$total_main_c?></td>
					<td class="b_g txt_right"><?=$total_main_o?></td>
					<td class="b_g txt_right bor2"><?=$total_main_b?></td>
					<td class="b_g txt_right"><?=$total_codi_c?></td>
					<td class="b_g txt_right"><?=$total_codi_o?></td>
					<td class="b_g txt_right bor2"><?=$total_codi_b?></td>
					<td class="b_g txt_right"><?=$total_codi1_c?></td>
					<td class="b_g txt_right"><?=$total_codi1_o?></td>
					<td class="b_g txt_right"><?=$total_codi1_b?></td>
				</tr>
				<tr>
					<td colspan = "3" class="b_w bor2"></td>
					<td colspan = "3" class="b_w txt_center bor2 sum_main_yd_<?=$row['ps_id']?>"><?=$total_main_c + $total_main_o + $total_main_b?></td>
					<td colspan = "3" class="b_w txt_center bor2 sum_codi_yd_<?=$row['ps_id']?>"><?=$total_codi_c + $total_codi_o + $total_codi_b?></td>
					<td colspan = "3" class="b_w txt_center bor2 sum_codi1_yd_<?=$row['ps_id']?>"><?=$total_codi1_c + $total_codi1_o + $total_codi1_b?></td>
				</tr>
						
			</tbody>
			

		<!-- 테이블 -->
		<?
			
		}
		sql_free_result($abs_result);
		if ($i == 0)
			echo '<table id="dbcover_list"><tr><td colspan="19" class="empty_table">자료가 없습니다.</td></tr></table>';
		?>
		</table>

	
	</div>

	<div class="local_cmd01 local_cmd">
		<div style="float: left">
			<input type="button" value="엑셀다운로드" class="btn btn_02" onclick="down_excel();">
			
		</div>
	</div>

</form>

<?= get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?od_type=Y&amp;$qstr&amp;page="); ?>

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
	$(".brand").change(function(){
        var ps_brands = "";
        $("#brand_0").attr('checked',false);
        $("input.brand:checked").each(function(){
            //alert($(this).val());
            if(ps_brands != "") ps_brands += ",";
            ps_brands += $(this).val();

        });
        $("#brands").val(ps_brands);
    });
    $("#brand_0").change(function(){
        if($("#brand_0").is(":checked")){
            $(".brand").prop('checked',true);
            $("#brands").val('');
        }else{
            $(".brand").prop('checked',false);
        }
    });
	function enterSearch() {
        if (window.event.keyCode == 13) {
        	search_submit();
    	}
    }

	$("#fa-search").click(function(){
		search_submit();
	});
	function search_submit(){
		let keyword = $("#stx").val();
		let year =$("#db_cover_year").val();
		let season =$("#db_cover_season").val();
		let prod_gb =$("#db_cover_gb").val();



		if(keyword){
			$('#orderMainTable').submit();
		}else if((year && season)){
			$('#orderMainTable').submit();
		}else{
			alert("아이템명 또는 시즌(3항목 모두) 선택해야 합니다.");
			return false;
		}
	}

	function down_excel(){
        // if (!is_checked("chk[]")) {
        //     alert("상품을 선택해주세요.");
        //     return false;
        // }
        // $("input[name='chk[]']:checked").each(function() {
        //     var no = $(".DTFC_LeftBodyLiner input[name='no["+this.value+"]']").val();
        //     $(".row_"+this.value).removeClass("noExport");
        // });

        $("#dbcover_list").table2excel({
            name: "Excel table",
            filename: "상품DB전산화(커버)" + new Date().toISOString().replace(/[\-\:\.]/g, ""),
            fileext: ".xls",
			exclude : ".noExport",
			preserveColors: true,
			preserveBorder: true,
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true,
            exclude_selects: true
        });
	}

	function all_down_excel(){
        var excel_sql = "";
        var excel_type = "";

        var headerdata = "";
        var bodydata = "";

        
        excel_sql = "select * from new_goods_db_overseas order by no desc";
        headerdata = $('<input type="hidden" value="<?=$headers?>" name="headerdata">');
        headerdata2 = $('<input type="hidden" value="<?=$headers2?>" name="headerdata2">');
        bodydata = $('<input type="hidden" value="<?=$bodys?>" name="bodydata">');
        excel_type = '상품DB전산화(커버)';
        

        var $form = $('<form></form>');     
        $form.attr('action', '<?=G5_ADMIN_URL?>/ajax.excel_download.new_goods_db_overseas.php');
        $form.attr('method', 'post');
        $form.appendTo('body');
        
        var exceldata = $('<input type="hidden" value="'+excel_sql+'" name="exceldata">');
        
        var excelnamedata = $('<input type="hidden" value="'+excel_type+'" name="excelnamedata">');
        //$form.append(exceldata).append(headerdata).append(bodydata).append(excelnamedata);
        $form.append(exceldata).append(headerdata).append(headerdata2).append(bodydata).append(excelnamedata);
        $form.submit();
	}
	
	function balju_print(ps_id){
		
		var main = $(".sum_main_yd_"+ps_id).text().replace(/,/gi,'');
		var codi = $(".sum_codi_yd_"+ps_id).text().replace(/,/gi,'');
		var codi1 = $(".sum_codi1_yd_"+ps_id).text().replace(/,/gi,'');
		var qty = $(".total_prod_qty_"+ps_id).text().replace(/,/gi,'');

		
		
		var url = "./balju_print.php?ps_id="+ps_id+"&qty="+qty+"&main="+main+"&codi="+codi+"&codi1="+codi1;
		
		var win = window.open(url, "_blank", "width=750,height=900");
	}


	
</script>