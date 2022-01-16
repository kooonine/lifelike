<?
$where = array();

$doc = strip_tags($doc);


$od_type = $_GET['od_type'];
$yearsServer = date('Y',  G5_SERVER_TIME);
if($od_type == "") $od_type = "Y";

$txt1 = $_POST['stx'];

$sql_search = " where (1)  ";


if($sfl){
    if($stx){
        switch($sfl){
            
            case 'sap_code':
                preg_match_all("/[^() || \-\ \/\,]+/", $txt1,$sap_cd_list);
                $sap_cd_in_list = empty($sap_cd_list[0])?'NULL':"'".join("','", $sap_cd_list[0])."'";
                $sql_search.= " and prod_code IN({$sap_cd_in_list})";

                break;
            
            case 'it_name':
                $sql_search .= " and item_name like '%{$txt1}%' ";
                break;
        }
    }else{

    }
}


$all_brand = false;
if ($brands) {
    $brand_item = implode("','", explode(',', $brands));
    $sql_search .= " and brand in ('{$brand_item}') ";
}else{
    $all_brand=true;
}

if(!$db_cover_year){
    // $sql_search .= " and ps_ipgo_status = 'N' ";
}else{
    if(strpos($db_cover_year , '>') === false){
        $year = substr($db_cover_year,-2);
        $sql_search .= " and ps_year = '{$year}' ";
    }else{
        
        $year .= substr($db_cover_season,-2);
        $sql_search .= " and ps_year >= {$year}";
    }
    
    
}

if(!$db_cover_season){
    // $sql_search .= " and ps_ipgo_status = 'N' ";
}else{
    $season = $db_cover_season;
    $sql_search .= " and ps_season = '{$season}' ";
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
	$cnt_sql = "SELECT COUNT(*) AS cnt FROM (SELECT item_name  FROM lt_prod_schedule_balju_print {$sql_search}  GROUP BY item_name) pin";
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
	$abs_sql = "select MIN(PS.ps_id) AS sort , PS.* from lt_prod_schedule_balju_print AS PS {$sql_search} GROUP BY item_name ORDER BY {$chain_orderby} sort DESC limit $from_record, $rows ";
	$abs_result = sql_query($abs_sql);



	$abs_result1 = sql_query($abs_sql);

	$sub_table_data ;
	for ($tdi = 0; $row_sub_table_data = sql_fetch_array($abs_result1); $tdi++) {
		if(!empty($sub_table_data)){
			$sub_table_data .=  ',';
		}
		$sub_table_data .= "'". $row_sub_table_data['item_name'] ."'";
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
						<option value="SS" <?= get_selected($db_cover_season, 'SS'); ?> >SS</option>
						<option value="HS" <?= get_selected($db_cover_season, 'HS'); ?> >HS</option>
						<option value="FW" <?= get_selected($db_cover_season, 'FW'); ?> >FW</option>
						<option value="AA" <?= get_selected($db_cover_season, 'AA'); ?> >AA</option>
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
			<input type="button" value="저장" class="btn btn_02" onclick="save();">
		</div>
	</div>

	<div class="tbl_head01 tbl_wrap">
		
		<table id="dbcover_list" class="table table-dark dbcover_list_p" style="width : 100%;">
			<caption>상품DB전산화(커버)</caption>
			<colgroup>
				<col width="35px;" />
				<col width="45px;" />
				<col width="132px;" />
				<col width="174px;" />
				<col width="71px;" />
				<col width="71px;" />
				<col width="71px;" />
				<col width="71px;" />
				<col width="71px;" />
				<col width="71px;" />
				<col width="71px;" />
				<col width="71px;" />
				<col width="71px;" />
				<col width="91px;" />
				<col width="88px;" />
				<col width="98px;" />
				<col width="66px;" />
				<col width="66px;" />
				<col width="69px;" />
				<col width="108px;" />
				<col width="108px;" />
				<col width="128px;" />
			</colgroup>
	

			<thead>
				<!-- <tr >
					<th colspan="2" class="b_w"><?=$row['ps_it_name']?></th>
					
					<th class="b_w bor2"><a class="noExport" style="color: black;" onclick="balju_print('<?=$row['ps_id']?>')" >발주서출력</a></th>
					<th colspan = "3" class="b_y bor2">메인</th>
					<th colspan = "3" class="b_y bor2">코디</th>
					<th colspan = "3" class="b_y bor2">코디1</th>
				</tr> -->
				<tr>
					<th class="b_g">구분</th>
					<th class="b_g">개발자</th>
					<th class="b_g">상품명</th>
					<th class="b_g bor2">원자재명</th>
					<th class="b_g bor2">색상</th>
					<th class="b_g bor2">규격</th>
					<th class="b_g">항균</th>
					<th class="b_g">항균<br>약제비</th>
					<th class="b_g">원단가</th>
					<th class="b_g">원단<br>+항균제</th>
					<th class="b_g">발주량</th>
					<th class="b_g bor2">금액</th>
					<th class="b_g bor2">염직</th>
					<th class="b_g bor2">업체</th>
					<th class="b_g bor2">비율</th>
					<th class="b_g">발주일</th>
					<th class="b_g">소요량</th>
					<th class="b_g">맞춤</th>
					<th class="b_g bor2">예상재고</th>
					<th class="b_g bor2">입고예정일</th>
					<th class="b_g bor2">입고확정일자</th>
					<th class="b_g bor2">렌징택<br>부탁여부</th>
				</tr>
			</thead>
			<tbody>
				<?for ($bl = 0; $balju_row = sql_fetch_array($abs_result) ; $bl++) {

					$mater_info = array();
					$mater_info_dump = array();
					if (!empty($balju_row['mater_info'])) {
						$mater_info_dump = json_decode($balju_row['mater_info'], true);

						for($mma = 1 ; $mma < 4 ; $mma ++){
							if($mater_info_dump[$mma]['main'] == '메인'){
								$mater_info[1] =  $mater_info_dump[$mma];
							}
							if($mater_info_dump[$mma]['main'] == '코디'){
								$mater_info[2] =  $mater_info_dump[$mma];
							}
							if($mater_info_dump[$mma]['main'] == '코디1'){
								$mater_info[3] =  $mater_info_dump[$mma];
							}
							
							$total_balju_cnt += $mater_info_dump[$mma]['yd'];
							$total_balju_price += $mater_info_dump[$mma]['price'];
						}

					}

					

					$mater_info_list = array();
					if (!empty($balju_row['mater_info_list'])) {
						$mater_info_list = json_decode($balju_row['mater_info_list'], true);
					}
				?>
					<input type="hidden" name="pno[<?=$bl?>]" value="<?=$balju_row['pno']?>">

					<tr>
						<input type="hidden" name="mater_info[1]">
						<input type="hidden" name="mater_info_soje[1]" value = "<?=$mater_info[1]['soje']?>">
						<input type="hidden" name="mater_info_color[1]" value="<?=$mater_info[1]['color']?>">
						<input type="hidden" name="mater_info_main[1]" value="<?=$mater_info[1]['main']?>">
						<input type="hidden" name="mater_info_main[1]" value="<?=$mater_info[1]['size']?>">
						<input type="hidden" name="mater_info_main[1]" value="<?=$mater_info[1]['mater_name']?>">
						<input type="hidden" name="mater_info_main[1]" value="<?=$mater_info[1]['yd']?>">
						
						<input type="hidden" name="mater_info_list_<?=$balju_row['pno']?>[1]">
						<td class="b_w vtc" rowspan = "3"><?=$balju_row['ps_year']?><br><?=$balju_row['ps_season']?><br><?=$balju_row['ps_job_gubun']?></td>
						<td class="b_w vtc" rowspan = "3"><?=$balju_row['prod_user']?></td>
						<td class="b_d vtc txt_center" rowspan = "3"><?=$balju_row['item_name']?></td>
						<td class="b_g txt_left bor2"><?=$mater_info[1]['soje']?></td>
						<td class="b_w txt_center bor2"><?=$mater_info[1]['color']?></td>
						<td class="b_w txt_center bor2"><?=$mater_info[1]['size']?></td>
						<td class="b_w txt_center"><input name="mater_info_list_<?=$balju_row['pno']?>_hk[1]" class="font11 txt_center noborder" value="<?=$mater_info_list[1]['hk']?>"></td>
						<td class="b_w txt_right"><input name="mater_info_list_<?=$balju_row['pno']?>_hk_p[1]" class="font11 txt_center noborder" data-idx="1" data-pno ="<?=$balju_row['pno']?>" onblur="hk_price(this)" value="<?=$mater_info_list[1]['hk_p'] >0 ? number_format(($mater_info_list[1]['hk_p'] ),1) : ''?>"></td>
						<td class="b_w txt_right"><input id = "mater_info_danga_<?=$balju_row['pno']?>_1" class="font11 txt_right noborder" readonly value="<?=$mater_info[1]['danga'] > 0 ? number_format($mater_info[1]['danga']) : '' ?>"></td>
						<td class="b_w txt_right"><input name="mater_info_list_<?=$balju_row['pno']?>_total_p[1]" class="font11 txt_right noborder" value="<?=$mater_info_list[1]['total_p'] ? number_format($mater_info_list[1]['total_p']) : ($mater_info[1]['danga'] + $mater_info_list[1]['hk_p']) >0 ? number_format($mater_info[1]['danga'] + $mater_info_list[1]['hk_p'] ) : '' ?>"></td>
						<td class="b_w txt_right"><?=$mater_info[1]['yd'] > 0 ? number_format(($mater_info[1]['yd'])) : ''?></td>
						<td class="b_w txt_right bor2"><?=$mater_info[1]['price'] > 0 ? number_format($mater_info[1]['price']) : ''?></td>
						<td class="b_w txt_right bor2"><input name="mater_info_list_<?=$balju_row['pno']?>_yj[1]" class="font11 txt_center noborder" value="<?=$mater_info_list[1]['yj']?>"></td>
						<td class="b_w txt_center bor2"><input name="mater_info_list_<?=$balju_row['pno']?>_mater_name[1]" class="font11 txt_center noborder" value="<?=$mater_info_list[1]['mater_name'] ? $mater_info_list[1]['mater_name'] : $mater_info[1]['mater_name'] ?>"></td>
						<td class="b_w txt_right bor2"> 1 : <?=number_format(($mater_info[2]['yd'] / $mater_info[1]['yd']),1)?></td>
						<td class="b_w txt_center"><?=$mater_info[1]['soje'] ?  date('m월 d일', strtotime($balju_row['balju_date'])) : ''?></td>
						<td class="b_w txt_right"><?=$mater_info[1]['origin_yd'] > 0 ? number_format(($mater_info[1]['origin_yd'])) : ''?></td>
						<td class="b_w txt_right"><input name="mater_info_list_<?=$balju_row['pno']?>_mc[1]" class="font11 txt_right noborder" data-idx="1" data-pno ="<?=$balju_row['pno']?>" onblur="fore_stock(this , '<?=$mater_info[1]['origin_yd']?>','<?=$mater_info[1]['yd']?>')" value="<?=$mater_info_list[1]['mc'] > 0 ? number_format($mater_info_list[1]['mc']) : ''?>"></td>
						<td class="b_w txt_right bor2"><input name="mater_info_list_<?=$balju_row['pno']?>_fore_stock[1]" class="font11 txt_right noborder" value="<?=$mater_info_list[1]['fore_stock'] ? number_format($mater_info_list[1]['fore_stock']) : $mater_info[1]['soje'] ? number_format($mater_info[1]['yd'] - $mater_info[1]['origin_yd'] -  $mater_info_list[1]['mc'] ) : '' ?>" readonly></td>
						<td class="b_w txt_center bor2"><?=$mater_info[1]['soje'] ? date('m월 d일', strtotime($balju_row['balju_limit_date'])) : ''?></td>
						<td class="b_w txt_right bor2"><input name="mater_info_list_<?=$balju_row['pno']?>_pick_date[1]" class="font11 txt_center noborder" onblur = "pick_date(this)" value="<?=$mater_info_list[1]['pick_date']?>"></td>
						<td class="b_w txt_right bor2"><?=$mater_info[1]['etc']?></td>
					</tr>
					
					<tr>
						<input type="hidden" name="mater_info[2]">
						<input type="hidden" name="mater_info_soje[2]" value = "<?=$mater_info[2]['soje']?>">
						<input type="hidden" name="mater_info_color[2]" value="<?=$mater_info[2]['color']?>">
						<input type="hidden" name="mater_info_main[2]" value="<?=$mater_info[2]['main']?>">
						<input type="hidden" name="mater_info_main[2]" value="<?=$mater_info[2]['size']?>">
						<input type="hidden" name="mater_info_main[2]" value="<?=$mater_info[2]['mater_name']?>">
						<input type="hidden" name="mater_info_main[2]" value="<?=$mater_info[2]['yd']?>">
						<input type="hidden" name="mater_info_list_<?=$balju_row['pno']?>[2]">

						<td class="b_g txt_left bor2"><?=$mater_info[2]['soje']?></td>
						<td class="b_w txt_center bor2"><?=$mater_info[2]['color']?></td>
						<td class="b_w txt_center bor2"><?=$mater_info[2]['size']?></td>
						<td class="b_w txt_center"><input name="mater_info_list_<?=$balju_row['pno']?>_hk[2]" class="font11 txt_center noborder" value="<?=$mater_info_list[2]['hk']?>"></td>
						<td class="b_w txt_right"><input name="mater_info_list_<?=$balju_row['pno']?>_hk_p[2]" class="font11 txt_center noborder" data-idx="2" data-pno ="<?=$balju_row['pno']?>" onblur="hk_price(this)" value="<?=$mater_info_list[2]['hk_p'] >0 ? number_format(($mater_info_list[2]['hk_p'] ),1) : ''?>"></td>
						<td class="b_w txt_right"><input id = "mater_info_danga_<?=$balju_row['pno']?>_2" class="font11 txt_right noborder" readonly value="<?=$mater_info[2]['danga'] > 0 ? number_format($mater_info[2]['danga']) : '' ?>"></td>
						<td class="b_w txt_right"><input name="mater_info_list_<?=$balju_row['pno']?>_total_p[2]" class="font11 txt_right noborder" value="<?=$mater_info_list[2]['total_p'] ? number_format($mater_info_list[2]['total_p']) : ($mater_info[2]['danga'] + $mater_info_list[2]['hk_p']) >0 ? number_format($mater_info[2]['danga'] + $mater_info_list[2]['hk_p'] ) : ''  ?>"></td>
						<td class="b_w txt_right"><?=$mater_info[2]['yd'] > 0 ? number_format(($mater_info[2]['yd'])) : ''?></td>
						<td class="b_w txt_right bor2"><?=$mater_info[2]['price'] > 0 ? number_format($mater_info[2]['price']) : ''?></td>
						<td class="b_w txt_right bor2"><input name="mater_info_list_<?=$balju_row['pno']?>_yj[2]" class="font11 txt_center noborder" value="<?=$mater_info_list[2]['yj']?>"></td>
						<td class="b_w txt_center bor2"><input name="mater_info_list_<?=$balju_row['pno']?>_mater_name[2]" class="font11 txt_center noborder" value="<?=$mater_info_list[2]['mater_name'] ? $mater_info_list[2]['mater_name'] : $mater_info[2]['mater_name'] ?>"></td>
						<td class="b_w txt_right bor2"></td>
						<td class="b_w txt_center"><?=$mater_info[2]['soje'] ?  date('m월 d일', strtotime($balju_row['balju_date'])) : ''?></td>
						<td class="b_w txt_right"><?=$mater_info[2]['origin_yd'] > 0 ? number_format(($mater_info[2]['origin_yd'])) : ''?></td>
						<td class="b_w txt_right"><input name="mater_info_list_<?=$balju_row['pno']?>_mc[2]" class="font11 txt_right noborder" data-idx="2" data-pno ="<?=$balju_row['pno']?>" onblur="fore_stock(this , '<?=$mater_info[2]['origin_yd']?>','<?=$mater_info[2]['yd']?>')" value="<?=$mater_info_list[2]['mc'] > 0 ? number_format($mater_info_list[2]['mc']) : ''?>"></td>
						<td class="b_w txt_right bor2"><input name="mater_info_list_<?=$balju_row['pno']?>_fore_stock[2]" class="font11 txt_right noborder" value="<?=$mater_info_list[2]['fore_stock'] ? number_format($mater_info_list[2]['fore_stock']) : $mater_info[2]['soje'] ? number_format($mater_info[2]['yd'] - $mater_info[2]['origin_yd'] -  $mater_info_list[2]['mc'] ) : '' ?>" readonly></td>
						<td class="b_w txt_center bor2"><?=$mater_info[2]['soje'] ? date('m월 d일', strtotime($balju_row['balju_limit_date'])) : ''?></td>
						<td class="b_w txt_right bor2"><input name="mater_info_list_<?=$balju_row['pno']?>_pick_date[2]" class="font11 txt_center noborder" onblur = "pick_date(this)"  value="<?=$mater_info_list[2]['pick_date']?>"></td>
						<td class="b_w txt_right bor2"><?=$mater_info[2]['etc']?></td>
					</tr>
					<tr>
						<input type="hidden" name="mater_info[3]">
						<input type="hidden" name="mater_info_soje[3]" value = "<?=$mater_info[3]['soje']?>">
						<input type="hidden" name="mater_info_color[3]" value="<?=$mater_info[3]['color']?>">
						<input type="hidden" name="mater_info_main[3]" value="<?=$mater_info[3]['main']?>">
						<input type="hidden" name="mater_info_main[3]" value="<?=$mater_info[3]['size']?>">
						<input type="hidden" name="mater_info_main[3]" value="<?=$mater_info[3]['mater_name']?>">
						<input type="hidden" name="mater_info_main[3]" value="<?=$mater_info[3]['yd']?>">
						<input type="hidden" name="mater_info_list_<?=$balju_row['pno']?>[3]">

						<td class="b_g txt_left bor2"><?=$mater_info[3]['soje']?></td>
						<td class="b_w txt_center bor2"><?=$mater_info[3]['color']?></td>
						<td class="b_w txt_center bor2"><?=$mater_info[3]['size']?></td>
						<td class="b_w txt_center"><input name="mater_info_list_<?=$balju_row['pno']?>_hk[3]" class="font11 txt_center noborder" value="<?=$mater_info_list[3]['hk']?>"></td>
						<td class="b_w txt_right"><input name="mater_info_list_<?=$balju_row['pno']?>_hk_p[3]" class="font11 txt_center noborder" data-idx="3" data-pno ="<?=$balju_row['pno']?>" onblur="hk_price(this)" value="<?=$mater_info_list[3]['hk_p'] >0 ? number_format(($mater_info_list[3]['hk_p'] ),1) : ''?>"></td>
						<td class="b_w txt_right"><input id = "mater_info_danga_<?=$balju_row['pno']?>_3" class="font11 txt_right noborder" readonly value="<?=$mater_info[3]['danga'] > 0 ? number_format($mater_info[3]['danga']) : '' ?>"></td>
						<td class="b_w txt_right"><input name="mater_info_list_<?=$balju_row['pno']?>_total_p[3]" class="font11 txt_right noborder" value="<?=$mater_info_list[3]['total_p'] ? number_format($mater_info_list[3]['total_p']) : ($mater_info[3]['danga'] + $mater_info_list[3]['hk_p']) >0 ? number_format($mater_info[3]['danga'] + $mater_info_list[3]['hk_p'] ) : ''  ?>"></td>
						<td class="b_w txt_right"><?=$mater_info[3]['yd'] > 0 ? number_format(($mater_info[3]['yd'])) : ''?></td>
						<td class="b_w txt_right bor2"><?=$mater_info[3]['price'] > 0 ? number_format($mater_info[3]['price']) : ''?></td>
						<td class="b_w txt_right bor2"><input name="mater_info_list_<?=$balju_row['pno']?>_yj[3]" class="font11 txt_center noborder" value="<?=$mater_info_list[3]['yj']?>"></td>
						<td class="b_w txt_center bor2"><input name="mater_info_list_<?=$balju_row['pno']?>_mater_name[3]" class="font11 txt_center noborder" value="<?=$mater_info_list[3]['mater_name'] ? $mater_info_list[3]['mater_name'] : $mater_info[3]['mater_name']?>"></td>
						<td class="b_w txt_right bor2"></td>
						<td class="b_w txt_center"><?=$mater_info[3]['soje'] ?  date('m월 d일', strtotime($balju_row['balju_date'])) : ''?></td>
						<td class="b_w txt_right"><?=$mater_info[3]['origin_yd'] > 0 ? number_format(($mater_info[3]['origin_yd'])) : ''?></td>
						<td class="b_w txt_right"><input name="mater_info_list_<?=$balju_row['pno']?>_mc[3]" class="font11 txt_right noborder" data-idx="3" data-pno ="<?=$balju_row['pno']?>" onblur="fore_stock(this , '<?=$mater_info[3]['origin_yd']?>','<?=$mater_info[3]['yd']?>')" value="<?=$mater_info_list[3]['mc'] > 0 ? number_format($mater_info_list[3]['mc']) : ''?>"></td>
						<td class="b_w txt_right bor2"><input name="mater_info_list_<?=$balju_row['pno']?>_fore_stock[3]" class="font11 txt_right noborder" value="<?=$mater_info_list[3]['fore_stock'] ? number_format($mater_info_list[3]['fore_stock']) : $mater_info[3]['soje'] ? number_format($mater_info[3]['yd'] - $mater_info[3]['origin_yd'] -  $mater_info_list[3]['mc'] ) : '' ?>" readonly></td>
						<td class="b_w txt_center bor2"><?= $mater_info[3]['soje'] ? date('m월 d일', strtotime($balju_row['balju_limit_date'])) : ''?></td>
						<td class="b_w txt_right bor2"><input name="mater_info_list_<?=$balju_row['pno']?>_pick_date[3]" class="font11 txt_center noborder" onblur = "pick_date(this)"  value="<?=$mater_info_list[3]['pick_date']?>"></td>
						<td class="b_w txt_right bor2"><?=$mater_info[3]['etc']?></td>
					</tr>
				<? } ?>
						
			</tbody>
			<tfooter>
			<tr>
				<td colspan = "4" class="b_g txt_center bor2">합계</td>
				<td colspan = "6" class=""></td>
				<td class="b_w txt_right bor2"><?=number_format($total_balju_cnt)?></td>
				<td class="b_w txt_right bor2"><?=number_format($total_balju_price)?></td>
				<td colspan = "10"></td>
			</tr>
			</tfooter>
			

		<!-- 테이블 -->
	
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
	function save(){
        // alert("asdf");
        
        var formData = $("#forderlist").serialize();

        $.ajax({
            cache : false,
            url : "./update_balju_list_cover_p.php", // 요기에
            type : 'POST', 
            data : formData, 
            success : function(data) {
                // var jsonObj = JSON.parse(data);
				console.log(data);
				location.reload();
            }, // success 

            error : function(xhr, status) {
                alert(xhr + " : " + status);
            }
        }); 
        

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

	function hk_price(elem){
		var pno = $(elem).data('pno');
		var idx = $(elem).data('idx');
		
		var hk_p = $("input[name='mater_info_list_"+pno+"_hk_p["+idx+"]']").val().replace(/,/gi,'');
		var danga = $("#mater_info_danga_"+pno+"_"+idx).val().replace(/,/gi,'');

		var total_p = (hk_p*1) + (danga*1);


		$("input[name='mater_info_list_"+pno+"_total_p["+idx+"]']").val(comma(total_p+""));	
	}

	function fore_stock(elem,origin,yd){
		var pno = $(elem).data('pno');
		var idx = $(elem).data('idx');

		
		var balju = yd.replace(/,/gi,'');;
		var origin_yd = origin.replace(/,/gi,'');;
		var mc = $(elem).val().replace(/,/gi,'');
	
		var fore_stock = (yd*1) - (origin_yd*1) - (mc*1);

		$("input[name='mater_info_list_"+pno+"_fore_stock["+idx+"]']").val(comma(fore_stock+""));	
	}
	
	function pick_date(elem){

		var data = elem.value;
		var redate = "";
		if(data.indexOf('-')  > -1  ){
			var dateArr = data.split('-');
			redate = dateArr[0] + "월 " + dateArr[1] + "일"
		}
		if(data.indexOf('/') > -1  ){
			var dateArr = data.split('/');
			redate = dateArr[0] + "월 " + dateArr[1] + "일"
		}
		
		$(elem).val(redate);	
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
	
	


	
</script>