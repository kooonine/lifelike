<?
$yearsServer = date('Y',  G5_SERVER_TIME);
$orderWhere = "";

if ($ca_search_type) {
 	$orderWhere .= " AND $ca_search_type LIKE '%$ca_search_keyword%'"; 
}

if( (isset($ca_brand) && in_array('전체', $ca_brand)) || !$ca_brand) {


} else {
    $checkNum = 0;
    if (in_array('소프라움', $ca_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (ca_brand_jo = '소프라움'";
        else $orderWhere .= " OR ca_brand_jo = '소프라움'";
        $checkNum = 1;
    } 
    if (in_array('쉐르단', $ca_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (ca_brand_jo = '쉐르단'";
        else $orderWhere .= " OR ca_brand_jo = '쉐르단'";
        $checkNum = 1;
	}
	if (in_array('랄프로렌홈', $ca_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (ca_brand_jo = '랄프로렌홈'";
        else $orderWhere .= " OR ca_brand_jo = '랄프로렌홈'";
        $checkNum = 1;
    } 
    if (in_array('라이프라이크', $ca_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (ca_brand_jo = '라이프라이크'";
        else $orderWhere .= " OR ca_brand_jo = '라이프라이크'";
        $checkNum = 1;
	}
	if (in_array('베온트레', $ca_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (ca_brand_jo = '베온트레'";
        else $orderWhere .= " OR ca_brand_jo = '베온트레'";
        $checkNum = 1;
    } 
    if (in_array('링스티드던', $ca_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (ca_brand_jo = '링스티드던'";
        else $orderWhere .= " OR ca_brand_jo = '링스티드던'";
        $checkNum = 1;
	}
	if (in_array('로자리아', $ca_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (ca_brand_jo = '로자리아'";
        else $orderWhere .= " OR ca_brand_jo = '로자리아'";
        $checkNum = 1;
    } 
    if (in_array('그라치아노', $ca_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (ca_brand_jo = '그라치아노'";
        else $orderWhere .= " OR ca_brand_jo = '그라치아노'";
        $checkNum = 1;
	}
	if (in_array('LBL', $ca_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (ca_brand_jo = 'LBL'";
        else $orderWhere .= " OR ca_brand_jo = 'LBL'";
        $checkNum = 1;
	}
	if (in_array('시뇨리아', $ca_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (ca_brand_jo = '시뇨리아'";
        else $orderWhere .= " OR ca_brand_jo = '시뇨리아'";
        $checkNum = 1;
	}
	if (in_array('플랫폼일반', $ca_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (ca_brand_jo = '플랫폼일반'";
        else $orderWhere .= " OR ca_brand_jo = '플랫폼일반'";
        $checkNum = 1;
	}
	if (in_array('플랫폼렌탈', $ca_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (ca_brand_jo = '플랫폼렌탈'";
        else $orderWhere .= " OR ca_brand_jo = '플랫폼렌탈'";
        $checkNum = 1;
	}
	if (in_array('온라인', $ca_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (ca_brand_jo = '온라인'";
        else $orderWhere .= " OR ca_brand_jo = '온라인'";
        $checkNum = 1;
	}
	if (in_array('템퍼', $ca_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (ca_brand_jo = '템퍼'";
        else $orderWhere .= " OR ca_brand_jo = '템퍼'";
        $checkNum = 1;
    }
    if($checkNum ==1) $orderWhere .= ")";
} 

if ($ca_prod_year_jo) {
	$orderWhere .= " AND ca_prod_year_jo LIKE '%$ca_prod_year_jo%'"; 
}
if ($ca_season_jo) {
	$orderWhere .= " AND ca_season_jo LIKE '%$ca_season_jo%'"; 
}
if ($ca_gubun_jo) {
	$orderWhere .= " AND ca_gubun_jo LIKE '%$ca_gubun_jo%'"; 
}
if (!$orderWhere ||  $orderWhere =='') $orderWhere = 'LIMIT 0';
$totalSql = "SELECT count(*) AS CNT FROM lt_cover_allocation  WHERE ca_id IS NOT NULL $orderWhere";

$countRow = sql_fetch($totalSql);
$total_count = $countRow['CNT'];
$allPut = false;
if ($outputCount == -1) {
	$allPut = true;
	$from_record = 0;
	$outputCount = $total_count;
} else {
	if ($outputCount < 1 || !$outputCount) {
		$outputCount = 50;
	}
	
	$total_page  = ceil($total_count / $outputCount);
	if ($page < 1 || !$page) {
		$page = 1;
	}
	
	$from_record = ($page - 1) * $outputCount;
} 

$listSql = "SELECT *, CONCAT(right(ca_prod_year_jo,2),ca_season_jo,' ',ca_gubun_jo) AS ca_merge_season, (SELECT COUNT(*) FROM lt_cover_allocation AS b WHERE a.ca_it_name_jo = b.ca_it_name_jo GROUP BY ca_it_name_jo HAVING COUNT(*) > 0 ) AS ca_count FROM lt_cover_allocation AS a WHERE ca_id IS NOT NULL $orderWhere ORDER BY ca_it_name_jo ASC, reg_datetime DESC LIMIT $from_record, $outputCount";
if ($orderWhere == 'LIMIT 0') {
	$listSql = "SELECT *, CONCAT(right(ca_prod_year_jo,2),ca_season_jo,' ',ca_gubun_jo) AS ca_merge_season, (SELECT COUNT(*) FROM lt_cover_allocation AS b WHERE a.ca_it_name_jo = b.ca_it_name_jo GROUP BY ca_it_name_jo HAVING COUNT(*) > 0 ) AS ca_count FROM lt_cover_allocation AS a WHERE ca_id IS NOT NULL LIMIT 0";
}

$listQuery = sql_query($listSql);

$listCompany = "SELECT * FROM lt_company_manufacturing ORDER BY cm_name ASC";
$companyQuery = sql_query($listCompany);
$companyQuery_th1 = sql_query($listCompany);
$companyQuery_th2 = sql_query($listCompany);
$companyQuery_th3 = sql_query($listCompany);
$companyQuery_th4 = sql_query($listCompany);

$nameCheck = '';
$qstr= "ca_search_type=".$ca_search_type."&amp;ca_search_keyword=".$ca_search_keyword."&amp;ca_brand=".$ca_brand."&amp;ca_prod_year_jo=".$ca_prod_year_jo."&amp;ca_season_jo=".$ca_season_jo."&amp;ca_gubun_jo=".$ca_gubun_jo."&amp;outputCount=".$outputCount."&amp;page=".$page;
if ($allPut) $outputCount = '-1';

$total_bongje_p = 0;

$gakong_bongje_price_arr = array();
$gakong_quilting_price_arr = array();
$gakong_one_price_arr = array();

?>
<script src="../total_order/jquery.table2excel.js"></script>
<form id="orderMainTable" name="frmorderlist" class="local_sch01 local_sch" onsubmit="$('#page').val('1');" method="post">
<!-- <form id="orderMainTable" name="frmorderlist" class="local_sch01 local_sch" onsubmit="$('#page').val('1');" method="get"> -->
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
						<select name="ca_search_type" id="ca_search_type">
							<option value="ca_it_name_jo" <?= get_selected($ca_search_type, 'ca_it_name_jo'); ?>>아이템명</option>
						</select>
						<label for="search" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
						<input type="text" name="ca_search_keyword" value="<?= $ca_search_keyword; ?>" id="ca_search_keyword" class="frm_input" autocomplete="off">
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row">브랜드</th>
				<td colspan="2">
				<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
					<label><input onclick='allCheck("ca_brand")' type="checkbox" name="ca_brand[]" value="전체" id="ca_brand01" <?php if(in_array('전체', $ca_brand) || !$ca_brand) echo "checked"; ?>>전체</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="ca_brand[]" value="소프라움" id="ca_brand02" <?php if(in_array('전체', $ca_brand) || in_array('소프라움', $ca_brand) || !$ca_brand) echo "checked"; ?>>소프라움</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="ca_brand[]" value="쉐르단" id="ca_brand03" <?php if(in_array('전체', $ca_brand) || in_array('쉐르단', $ca_brand) || !$ca_brand) echo "checked"; ?>>쉐르단</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="ca_brand[]" value="랄프로렌홈" id="ca_brand04" <?php if(in_array('전체', $ca_brand) || in_array('랄프로렌홈', $ca_brand) || !$ca_brand) echo "checked"; ?>>랄프로렌홈</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="ca_brand[]" value="베온트레" id="ca_brand05" <?php if(in_array('전체', $ca_brand) || in_array('베온트레', $ca_brand) || !$ca_brand) echo "checked"; ?>>베온트레</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="ca_brand[]" value="링스티드던" id="ca_brand06" <?php if(in_array('전체', $ca_brand) || in_array('링스티드던', $ca_brand) || !$ca_brand) echo "checked"; ?>>링스티드던</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="ca_brand[]" value="로자리아" id="ca_brand07" <?php if(in_array('전체', $ca_brand) || in_array('로자리아', $ca_brand) || !$ca_brand) echo "checked"; ?>>로자리아</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="ca_brand[]" value="그라치아노" id="ca_brand08" <?php if(in_array('전체', $ca_brand) || in_array('그라치아노', $ca_brand) || !$ca_brand) echo "checked"; ?>>그라치아노</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="ca_brand[]" value="시뇨리아" id="ca_brand09" <?php if(in_array('전체', $ca_brand) || in_array('시뇨리아', $ca_brand) || !$ca_brand) echo "checked"; ?>>시뇨리아</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="ca_brand[]" value="플랫폼일반" id="ca_brand10" <?php if(in_array('전체', $ca_brand) || in_array('플랫폼일반', $ca_brand) || !$ca_brand) echo "checked"; ?>>플랫폼일반</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="ca_brand[]" value="플랫폼렌탈" id="ca_brand11" <?php if(in_array('전체', $ca_brand) || in_array('플랫폼렌탈', $ca_brand) || !$ca_brand) echo "checked"; ?>>플랫폼렌탈</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="ca_brand[]" value="온라인" id="ca_brand12" <?php if(in_array('전체', $ca_brand) || in_array('온라인', $ca_brand) || !$ca_brand) echo "checked"; ?>>온라인</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="ca_brand[]" value="템퍼" id="ca_brand13" <?php if(in_array('전체', $ca_brand) || in_array('템퍼', $ca_brand) || !$ca_brand) echo "checked"; ?>>템퍼</label>&nbsp;&nbsp;

				</div>
				</td>
			</tr>
			<tr>
				<th scope="row">시즌</th>
				<td colspan="2">
				<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
					<select name="ca_prod_year_jo" id="ca_prod_year_jo" required oninvalid="this.setCustomValidity('년도를 선택해주세요')" oninput="setCustomValidity('')">
						<option value="" <?= get_selected($ca_prod_year_jo, ''); ?>>선택</option>
						<? for($i = (int)$yearsServer+1; 2009 < $i; $i--) {  ?>
							<option value=<?= $i?> <?= get_selected($ca_prod_year_jo, $i); ?>><?= $i?>년</option>
						<? }?>
					</select>
					<select name="ca_season_jo" id="ca_season_jo" required oninvalid="this.setCustomValidity('시즌를 선택해주세요')" oninput="setCustomValidity('')" >
						<option value="" <?= get_selected($ca_season_jo, ''); ?>>선택</option>
						<option value="SS" <?= get_selected($ca_season_jo, 'SS'); ?>>SS</option>
						<option value="HS" <?= get_selected($ca_season_jo, 'HS'); ?>>HS</option>
						<option value="FW" <?= get_selected($ca_season_jo, 'FW'); ?>>FW</option>
						<option value="AA" <?= get_selected($ca_season_jo, 'AA'); ?>>AA</option>
					</select>
					<select name="ca_gubun_jo" id="ca_gubun_jo" required oninvalid="this.setCustomValidity('상품구분를 선택해주세요')" oninput="setCustomValidity('')">
						<option value="" <?= get_selected($ca_gubun_jo, ''); ?>>선택</option>
						<option value="정상" <?= get_selected($ca_gubun_jo, '정상'); ?>>정상</option>
						<option value="기획" <?= get_selected($ca_gubun_jo, '기획'); ?>>기획</option>
					</select>
				</div>
				</td>
			</tr>

			<tr>
				<th scope="row">보기</th>
				<td colspan="2">
				<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
					<select name="outputCount" id="outputCount" style="width: 80px;">
						<option value="50" <?= get_selected($outputCount, '50'); ?> >50개</option>
						<option value="100" <?= get_selected($outputCount, '100'); ?> >100개</option>
						<option value="200" <?= get_selected($outputCount, '200'); ?> >200개</option>
						<option value="300" <?= get_selected($outputCount, '300'); ?> >300개</option>
						<option value="400" <?= get_selected($outputCount, '400'); ?> >400개</option>
						<option value="500" <?= get_selected($outputCount, '500'); ?> >500개</option>
						<option value="-1" <?= get_selected($outputCount, '-1'); ?> >전체 보기</option>
					</select>
				</div>
				</td>
			</tr>
		</table>
	</div>

	<div class="form-group">
		<div class="col-md-12 col-sm-12 col-xs-12 text-center">
			<!-- <button class="btn btn_02" type="reset" id="btn_clear">초기화</button> -->
			<input type="button" value="초기화" class="btn btn_02" onclick="coverBReset()">
			<button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i>검색</button>
		</div>
	</div>
</form>

<div class="local_ov01 local_ov">
	<?= $listall; ?>
	<span class="btn_ov01">[ 검색결과 <?= number_format($total_count); ?>건 ]</span>
</div>

<form name="forderlist" id="forderlist" method="post" autocomplete="off">
	<div class="local_cmd01 local_cmd">
		<div style="float: left">
			<input type="button" value="엑셀다운로드" class="btn btn_02" onclick="down_excel_b()">
			<input type="button" value="저장" class="btn btn_02" onclick="saveCoverDateB()">
		</div>
	</div>

	<div class="tbl_head01 tbl_wrap">
		<table id="dbcover_list_b">
			<caption>상품DB전산화(커버) 일정</caption>
			<thead>
				<tr>
					<td class="noExport"></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" id = "rate_bongje_price"></td>
					<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" id = "rate_quilting_price"></td>
					<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" id = "rate_one_price"></td>
					<?
					$ta_bong = array();
					$ta_quilting = array();
					$ta_one = array();
					for ($thic = 0; $rowth1 = sql_fetch_array($companyQuery_th4); $thic++) { 
						if ($rowth1['cm_bongje'] == '가공비_봉제') $ta_bong[] = $rowth1['cm_name'];
						if ($rowth1['cm_bongje'] == '가공비_퀼팅') $ta_quilting[] = $rowth1['cm_name'];
						if ($rowth1['cm_bongje'] == '가공비_원헤드') $ta_one[] = $rowth1['cm_name'];
					}
                    
					$ta_bong = array_unique($ta_bong);
					$ta_quilting = array_unique($ta_quilting);
					$ta_one = array_unique($ta_one);
					if (!empty($ta_bong)) {
						foreach ($ta_bong as $tcb => $tcb_bong) {
							
					?>
						<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" class="rate_b_<?=$tcb?>" ></td>
					<?
						}
					}
					if (!empty($ta_quilting)) {
						foreach ($ta_quilting as $tcq => $tcq_quilting) {
					?>
						<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" class="rate_q_<?=$tcq?>"></td>
					<?
						}
					}
					if (!empty($ta_one)) {
						foreach ($ta_one as $tco => $tco_one) {
					?>
						<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" class="rate_o_<?=$tco?>"></td>
					<?
						}
					}
					?>
				</tr>
				<tr>
					<td class="noExport"></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" id = "mius_bongje_price"></td>
					<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" id = "mius_quilting_price"></td>
					<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" id = "mius_one_price"></td>
					<?
					$ta_bong = array();
					$ta_quilting = array();
					$ta_one = array();
					for ($thic = 0; $rowth1 = sql_fetch_array($companyQuery_th3); $thic++) { 
						if ($rowth1['cm_bongje'] == '가공비_봉제') $ta_bong[] = $rowth1['cm_name'];
						if ($rowth1['cm_bongje'] == '가공비_퀼팅') $ta_quilting[] = $rowth1['cm_name'];
						if ($rowth1['cm_bongje'] == '가공비_원헤드') $ta_one[] = $rowth1['cm_name'];
					}
                    
					$ta_bong = array_unique($ta_bong);
					$ta_quilting = array_unique($ta_quilting);
					$ta_one = array_unique($ta_one);
					if (!empty($ta_bong)) {
						foreach ($ta_bong as $tcb => $tcb_bong) {
							
					?>
						<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" class="mius_b_<?=$tcb?>" ></td>
					<?
						}
					}
					if (!empty($ta_quilting)) {
						foreach ($ta_quilting as $tcq => $tcq_quilting) {
					?>
						<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" class="mius_q_<?=$tcq?>"></td>
					<?
						}
					}
					if (!empty($ta_one)) {
						foreach ($ta_one as $tco => $tco_one) {
					?>
						<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" class="mius_o_<?=$tco?>"></td>
					<?
						}
					}
					?>
				</tr>
				<tr>
					<td class="noExport"></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="text-align : right;"><input style="height : 24px; margin : 0px; padding : 0px;" type="button" value="업체가격" class="noExport btn btn_02" onclick="saveCompanyPrice()"></td>
					<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" id = "total_cm_bongje_price"></td>
					<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" id = "total_cm_quilting_price"></td>
					<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" id = "total_cm_one_price"></td>
					<?
					$ta_bong = array();
					$ta_quilting = array();
					$ta_one = array();
					for ($thic = 0; $rowth2 = sql_fetch_array($companyQuery_th2); $thic++) { 
						if ($rowth2['cm_bongje'] == '가공비_봉제') {
							$ta_bong_temp = array(
								"id" => $rowth2['cm_id'],
								"cm" => $rowth2['cm_name'],
								"price" =>$rowth2['cm_append_price']
							 ); 
							$ta_bong[$thic] = $ta_bong_temp;	
						}
						if ($rowth2['cm_bongje'] == '가공비_퀼팅') {
							$ta_quilting_temp = array(
								"id" => $rowth2['cm_id'],
								"cm" => $rowth2['cm_name'],
								"price" =>$rowth2['cm_append_price']
							); 
							$ta_quilting[] = $ta_quilting_temp;
						} 
						if ($rowth2['cm_bongje'] == '가공비_원헤드') {
							$ta_one_temp = array(
								"id" => $rowth2['cm_id'],
								"cm" => $rowth2['cm_name'],
								"price" =>$rowth2['cm_append_price']
							);
							$ta_one[] = $ta_one_temp;
						} 
					}
                    
					if (!empty($ta_bong)) {
						foreach ($ta_bong as $tcb1 => $tcb_bong) {
						$total_cm_b += $tcb_bong['price'];
					?>
						<td scope="col" style="border: 1px solid #000;" >
							<input type="hidden" class="noExport cm_id" value = "<?=$tcb_bong['id']?>">
							<input style="border: 0px; text-align : right; padding-right:5px;" size ="10px" name="cm_price" class="cm_b_<?=$tcb1?> input_b_<?=$tcb_bong['id']?>" value="<?=$tcb_bong['price'] ? number_format($tcb_bong['price']) : 0?>" >
						</td>
					<?
						}
					}
					if (!empty($ta_quilting)) {
						foreach ($ta_quilting as $tcq1 => $tcq_quilting) {
							$total_cm_q += $tcq_quilting['price'];
					?>
						<td scope="col" style="border: 1px solid #000;">
							<input type="hidden" class="noExport cm_id" value = "<?=$tcq_quilting['id']?>">
							<input style="border: 0px; text-align : right; padding-right:5px;" size ="10px" name="" class="cm_q_<?=$tcq1?> input_b_<?=$tcq_quilting['id']?>" value="<?=$tcq_quilting['price'] ? number_format($tcq_quilting['price']) : 0?>">
						</td>
					<?
						}
					}
					if (!empty($ta_one)) {
						foreach ($ta_one as $tco1 => $tco_one) {
							$total_cm_o += $tco_one['price'];
					?>
						<td scope="col" style="border: 1px solid #000;">
							<input type="hidden" class="noExport cm_id" value = "<?=$tco_one['id']?>">
							<input style="border: 0px; text-align : right; padding-right:5px;" size ="10px" name=""  class="cm_o_<?=$tco1?> input_b_<?=$tco_one['id']?>" value="<?=$tco_one['price'] ? number_format($tco_one['price']) : 0?>">
						</td>
					<?
						}
					}
					?>
					<input type="hidden" id="cm_total_b" value="<?=$total_cm_b?>">
					<input type="hidden" id="cm_total_q" value="<?=$total_cm_q?>">
					<input type="hidden" id="cm_total_o" value="<?=$total_cm_o?>">
				</tr>
				<tr>
					<td class="noExport"></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" id = "total_bongje_price"></td>
					<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" id = "total_quilting_price"></td>
					<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" id = "total_one_price"></td>
					<?
					$ta_bong = array();
					$ta_quilting = array();
					$ta_one = array();
					for ($thic = 0; $rowth1 = sql_fetch_array($companyQuery_th1); $thic++) { 
						if ($rowth1['cm_bongje'] == '가공비_봉제') $ta_bong[] = $rowth1['cm_name'];
						if ($rowth1['cm_bongje'] == '가공비_퀼팅') $ta_quilting[] = $rowth1['cm_name'];
						if ($rowth1['cm_bongje'] == '가공비_원헤드') $ta_one[] = $rowth1['cm_name'];
					}
                    
					$ta_bong = array_unique($ta_bong);
					$ta_quilting = array_unique($ta_quilting);
					$ta_one = array_unique($ta_one);
					if (!empty($ta_bong)) {
						foreach ($ta_bong as $tcb => $tcb_bong) {
							
					?>
						<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" class="total_b_<?=$tcb?>" ></td>
					<?
						}
					}
					if (!empty($ta_quilting)) {
						foreach ($ta_quilting as $tcq => $tcq_quilting) {
					?>
						<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" class="total_q_<?=$tcq?>"></td>
					<?
						}
					}
					if (!empty($ta_one)) {
						foreach ($ta_one as $tco => $tco_one) {
					?>
						<td scope="col" style="border: 1px solid #000; text-align : right; padding-right:5px;" class="total_o_<?=$tco?>"></td>
					<?
						}
					}
					?>
				</tr>
				<tr>
					<th scope="col"  class="noExport">
						<label for="chkall" class="sound_only">주문 전체</label>
						<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all_o(this.form)">
					</th>
					<th scope="col">시즌</th>
					<th scope="col">상품명</th>
					<th scope="col">소재</th>
					<th scope="col">색상</th>
					<th scope="col">아이템명</th>
					<th scope="col">사이즈</th>
					<th scope="col">계획</th>
					<th scope="col">투입</th>

					<th scope="col" style="background-color: orange;">가공비_봉제</th>
					<th scope="col" style="background-color: green;">가공비_퀼팅</th>
					<th scope="col" style="background-color: blue;">가공비_원헤드</th>
					<?
					$ca_bong = array();
					$ca_quilting = array();
					$ca_one = array();
					for ($ic = 0; $rowc = sql_fetch_array($companyQuery); $ic++) { 
						if ($rowc['cm_bongje'] == '가공비_봉제') $ca_bong[] = $rowc['cm_name'];
						if ($rowc['cm_bongje'] == '가공비_퀼팅') $ca_quilting[] = $rowc['cm_name'];
						if ($rowc['cm_bongje'] == '가공비_원헤드') $ca_one[] = $rowc['cm_name'];
					}
                    
					$ca_bong = array_unique($ca_bong);
					$ca_quilting = array_unique($ca_quilting);
					$ca_one = array_unique($ca_one);
					if (!empty($ca_bong)) {
						foreach ($ca_bong as $cb => $cb_bong) {?>
						<th scope="col" style="background-color: orange;"><?= $cb_bong?></th>
						<?}
					}
					if (!empty($ca_quilting)) {
						foreach ($ca_quilting as $cq => $cq_quilting) {?>
						<th scope="col" style="background-color: green;"><?= $cq_quilting?></th>
						<?}
					}
					if (!empty($ca_one)) {
						foreach ($ca_one as $co => $co_one) {?>
						<th scope="col" style="background-color: blue;"><?= $co_one?></th>
						<?}
					}
					?>

				</tr>
			</thead>
			<tbody>
				<?
				for ($i = 0; $row = sql_fetch_array($listQuery); $i++) {
					$ca_size_ps_qty = '';
					$ca_size_ps = array();
					if (!empty($row['ca_size_ps'])) {
						$ca_size_ps = json_decode($row['ca_size_ps'], true);
					}
					if (!empty($ca_size_ps)) {
						foreach ($ca_size_ps as $si => $ps_size) {
							if($row['ca_size_code_jo'] == $ps_size['size']) {
								$ca_size_ps_qty = $ps_size['qty'];
							}
						}
					}
					$ca_company_name_bong = '';
					$ca_company_name_quilting = '';
					$ca_company_name_one = '';
					$ca_company_bongje = '';
					$ca_company_name_ps = array();
					if (!empty($row['ca_company_name_ps'])) {
						$ca_company_name_ps = json_decode($row['ca_company_name_ps'], true);
					}
					if (!empty($ca_company_name_ps)) {
						foreach ($ca_company_name_ps as $cn => $ps_company) {
							if ($ps_company['bongje'] == '가공비_봉제') $ca_company_name_bong .= $ps_company['name'].',';
							if ($ps_company['bongje'] == '가공비_퀼팅') $ca_company_name_quilting .= $ps_company['name'].',';
							if ($ps_company['bongje'] == '가공비_원헤드') $ca_company_name_one .= $ps_company['name'].',';

						}
					}
					$ca_gakong_item_jo = array();
					if (!empty($row['ca_gakong_item_jo'])) {
						$ca_gakong_item_jo = json_decode($row['ca_gakong_item_jo'], true);
					}
					$ca_gakong_bong = '';
					$ca_gakong_quilting = '';
					$ca_gakong_one = '';
					if (!empty($ca_gakong_item_jo)) {
						foreach ($ca_gakong_item_jo as $cg => $ca_gakong) {
							if ($ca_gakong['bongje'] == '가공비_봉제') $ca_gakong_bong = $ca_gakong['price'];
							if ($ca_gakong['bongje'] == '가공비_퀼팅') $ca_gakong_quilting = $ca_gakong['price'];
							if ($ca_gakong['bongje'] == '가공비_원헤드') $ca_gakong_one = $ca_gakong['price'];
						}
					}


				?>
				<tr class="data_row row_<?=$row['ca_id']?> noExport">

					<td class="noExport">
						<input type="checkbox" name="chk[]" value="<?= $row['ca_id'] ?>" id="chk_<?= $i ?>" seasonName = "<?= $row['ca_it_name_jo']?><?= $row['ca_merge_season']?>">
					</td>
					<? 
						if ($nameCheck != $row['ca_it_name_jo']) {
							$nameCheck = $row['ca_it_name_jo'];
							?>
							<td rowspan="<?=$row['ca_count'] ?>" id ="season_<?= $row['ca_it_name_jo']?><?= $row['ca_merge_season']?>" class="noExport">
								<?= $row['ca_merge_season']?>
							</td>
							<td rowspan="<?=$row['ca_count'] ?>" id ="name_<?= $row['ca_it_name_jo']?><?= $row['ca_merge_season']?>" class="noExport">
								<?= $row['ca_it_name_jo']?>
							</td>
						<?} 
					?>
					<td rowspan="1" id ="season1_<?= $row['ca_it_name_jo']?><?= $row['ca_merge_season']?><?= $row['ca_id'] ?>" class="excel_row noExport" hidden>
						<?= $row['ca_merge_season']?>
					</td>
					<td rowspan="1" id ="name1_<?= $row['ca_it_name_jo']?><?= $row['ca_merge_season']?><?= $row['ca_id'] ?>" class="excel_row noExport" hidden >
						<?= $row['ca_it_name_jo']?>
					</td>

					<td>
						<?= $row['ca_item_soje_pi']?>
					</td>
					<td>
						<?= $row['ca_color_jo']?>
					</td>
					<td>
						<?= $row['ca_prod_name_ps']?>
					</td>
					<td>
						<?= $row['ca_size_code_jo']?>
					</td>
					<td>
						<?= $ca_size_ps_qty?>
					</td>
					<td>
						<input name='ca_input[]' id="ca_input_<?= $row['ca_id'] ?>" type="text" size ="10px" style="size:10px" value ="<?= $row['ca_input']?>">
					</td>
					<td>
						<?=$ca_gakong_bong ?  number_format($ca_gakong_bong) : ''?>
					</td>
					<td>
						<?=  $ca_gakong_quilting ?  number_format($ca_gakong_quilting) : ''?>
					</td>
					<td>
						<?= $ca_gakong_one ?  number_format($ca_gakong_one) : ''?>
					</td>
					<? 
						if (!empty($ca_bong)) {
							foreach ($ca_bong as $cb => $cb_bong) {
								$bongj_price_temp = 0;
							?>
							<td>
								<? if (strpos($ca_company_name_bong, $cb_bong) !== false) { 
									$bongj_price_temp += $ca_size_ps_qty * $ca_gakong_bong;
								?>
									<?=number_format($ca_size_ps_qty)?>
								<?} ?>
							</td>
							<?
							$gakong_bongj_price_arr[$cb] += $bongj_price_temp;
							}
							$total_bongje_p += $ca_size_ps_qty * $ca_gakong_bong;
						}
						if (!empty($ca_quilting)) {
							foreach ($ca_quilting as $cq => $cq_quilting) {
								$quilting_price_temp = 0;
							?>
							<td>
								<? if (strpos($ca_company_name_quilting, $cq_quilting) !== false) { 
									$quilting_price_temp += $ca_size_ps_qty * $ca_gakong_quilting;
								?>
									<?=number_format($ca_size_ps_qty)?>
								<?} ?>
							</td>
							<?
							$gakong_quilting_price_arr[$cq] += $quilting_price_temp;
							}
							$total_quilting_p += $ca_size_ps_qty * $ca_gakong_quilting;
							
						}
						if (!empty($ca_one)) {
							foreach ($ca_one as $co => $co_one) {
								$one_price_temp = 0;
							?>
							<td>
								<? if (strpos($ca_company_name_one, $co_one) !== false) { 
									$one_price_temp += $ca_size_ps_qty * $ca_gakong_one;
								?>
									<?= number_format($ca_size_ps_qty)?>
								<?} ?>
							</td>
							<?
							$gakong_one_price_arr[$co] += $one_price_temp;
							}
							$total_one_p += $ca_size_ps_qty * $ca_gakong_one;
						}
					?>
				</tr>
				<?
				}

				if ($i == 0)
					echo '<tr><td colspan="25" class="empty_table">자료가 없습니다.</td></tr>';
				?>
				<input type = "hidden" id="bongje_bind" value = "<?=$total_bongje_p?>">
				<input type = "hidden" id="quilting_bind" value = "<?=$total_quilting_p?>">
				<input type = "hidden" id="one_bind" value = "<?=$total_one_p?>">
			</tbody>
		</table>
	</div>

	<div class="local_cmd01 local_cmd">
		<div style="float: left">
			<input type="button" value="엑셀다운로드" class="btn btn_02" onclick="down_excel_b()">
			<input type="button" value="저장" class="btn btn_02" onclick="saveCoverDate()">
		</div>
	</div>

</form>

<?= get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?od_type=B&amp;$qstr&amp;page="); ?>

<script>
	$(function() {
		var t_b = $("#bongje_bind").val().replace(/,/gi,'');
		var t_q = $("#quilting_bind").val().replace(/,/gi,'');
		var t_o = $("#one_bind").val().replace(/,/gi,'');
		$("#total_bongje_price").html(comma(t_b));
		$("#total_quilting_price").html(comma(t_q));
		$("#total_one_price").html(comma(t_o));

		var c_t_b = $("#cm_total_b").val().replace(/,/gi,'');
		var c_t_q = $("#cm_total_q").val().replace(/,/gi,'');
		var c_t_o = $("#cm_total_o").val().replace(/,/gi,'');
		
		$("#total_cm_bongje_price").html(comma(c_t_b+""));
		$("#total_cm_quilting_price").html(comma(c_t_q+""));
		$("#total_cm_one_price").html(comma(c_t_o+""));

		var m_t_b = c_t_b - t_b;
		var m_t_q = c_t_q - t_q;
		var m_t_o = c_t_o - t_o;

		$("#mius_bongje_price").html(comma(m_t_b+""));
		$("#mius_quilting_price").html(comma(m_t_q+""));
		$("#mius_one_price").html(comma(m_t_o+""));

		var r_t_b = parseInt((t_b / c_t_b) * 100) *100 / 100;
		var r_t_q = parseInt((t_q / c_t_q) * 100) *100 / 100;
		var r_t_o = parseInt((t_o / c_t_o) * 100) *100 / 100;

		if((r_t_b % 1) === 0 ){}else{r_t_b = 0 }  
		if((r_t_q % 1) === 0 ){}else{r_t_q = 0 }  
		if((r_t_o % 1) === 0 ){}else{r_t_o = 0 }  

		$("#rate_bongje_price").html(r_t_b+"%");
		$("#rate_quilting_price").html(r_t_q+"%");
		$("#rate_one_price").html(r_t_o+"%");



		
		var b_arr = JSON.parse("<?php echo json_encode($gakong_bongj_price_arr); ?>");
		if(!b_arr == null){
			for(var bi = 0 ; bi< b_arr.length; bi++){
				$(".total_b_"+bi).html(comma(b_arr[bi]+""));
				var cm_bong_p =  $(".cm_b_"+bi).val().replace(/,/gi,'');
				var m_b = (b_arr[bi] - cm_bong_p);
				var r_b = parseInt((b_arr[bi] / cm_bong_p) * 100) *100 / 100;
				$(".mius_b_"+bi).html(comma(m_b+""));
				if((r_b % 1) === 0 ){}else{r_b = 0 }  
				$(".rate_b_"+bi).html(r_b+"%");
			}
		}
		var q_arr = JSON.parse("<?php echo json_encode($gakong_quilting_price_arr); ?>");
		if(!q_arr == null){
			for(var qi = 0 ; qi< q_arr.length; qi++){
				$(".total_q_"+qi).html(comma(q_arr[qi]+""));
				var cm_quilting_p =  $(".cm_q_"+qi).val().replace(/,/gi,'');
				var m_q = (q_arr[qi] - cm_quilting_p);
				var r_q = parseInt((q_arr[qi] / cm_quilting_p)* 100) *100 / 100;
				$(".mius_q_"+qi).html(comma(m_q+""));
				if((r_q % 1) === 0 ){}else{r_q = 0 }  
				$(".rate_q_"+qi).html(r_q+"%");
			}
		}
		var o_arr = JSON.parse("<?php echo json_encode($gakong_one_price_arr); ?>");
		if(!o_arr == null){
			for(var oi = 0 ; oi< o_arr.length; oi++){
				$(".total_o_"+oi).html(comma(o_arr[oi]+""));
				var cm_one_p =  $(".cm_q_"+oi).val().replace(/,/gi,'');
				var m_o = (o_arr[oi] - cm_one_p);
				var r_o = parseInt((o_arr[oi] / cm_one_p)* 100) * 100 / 100;
				$(".mius_o_"+oi).html(comma(m_o+""));
				if((r_o % 1) === 0 ){}else{r_o = 0 }  
				$(".rate_q_"+qi).html(r_o+"%");
			}
		}
		
	});

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
	// function enterSearch() {
    //     if (window.event.keyCode == 13) {
    //     	document.getElementById('new_goods_form').submit();
    // 	}
	// }
	function saveCoverDateB() { 
		var check = confirm("저장 하시겠습니까?"); 
        if(check){
            if (!is_checked("chk[]")) {
                alert("하나 이상 선택하세요.");
                return false;
			}
			
			
			let inputOpt = {};
			$("input[name='chk[]']:checked").each(function (index) { 
				inputOpt[$(this).val()] = $(`#ca_input_${$(this).val()}`).val();
			})
			$.ajax({
                url: "./ajax.new_goods_db_cover_b.php",
                method: "POST",
                data: {
                    'inputOpt' : inputOpt
                },
                dataType: "json",
                async: false,
                cache: false,
                success: function(result) {
                    location.reload();
                }
            });
			return;
		}
	}
	function down_excel_b(){
		if (!is_checked("chk[]")) {
            alert("하나 이상 선택하세요.");
            return false;
		}
		let excelMerge = {};
		let seasonNameChk = null;
		$(".data_row").addClass("noExport");
		$(".excel_row").addClass("noExport");
		$("input[name='chk[]']:checked").each(function (index) { 
			$(".row_"+this.value).removeClass("noExport");

			let seasonName = $(this).attr("seasonName");
			if (seasonName == seasonNameChk) {
				excelMerge[snId] += 1;
			} else {
				seasonNameChk = seasonName;
				snId = seasonName+$(this).val();
				excelMerge[snId] = 1;
			}
		})
		$.each(excelMerge,function(key, value){
			var tData = document.getElementById ('season1_'+key);
			var name = document.getElementById ('name1_'+key);
			tData.classList.remove("noExport");
			name.classList.remove("noExport");
			tData.rowSpan = value;
			name.rowSpan = value;
		});
        $("#dbcover_list_b").table2excel({
            name: "Excel table",
            filename: "상품DB전산화(커버) 배정표" + new Date().toISOString().replace(/[\-\:\.]/g, ""),
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
	function coverBReset() {
        $("input:checkbox[id*='ca_brand']").prop("checked",true);
        $("#ca_search_type").val('ca_it_name_jo');
		$("#ca_search_keyword").val('');
		$("#ca_prod_year_jo").val('');
		$("#ca_season_jo").val('');
		$("#ca_gubun_jo").val('');
        $("#outputCount").val(50);
    }

	function allCheck(e=false) {
        let allResult = false;
        if (e!='chk_') allResult = $('#'+e+'01').prop("checked");
        else allResult = $("input:checkbox[id='allchk']").is(":checked");
        if (allResult) {
            $("input:checkbox[id^='"+e+"']").prop("checked",true);
        } else {
            $("input:checkbox[id^='"+e+"']").prop("checked",false);
        }
    }
	$(function() {
		//$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });

        window.addEventListener("keydown", (e) => {
            if (e.keyCode == 13) {
                document.getElementById('orderMainTable').submit();
            }
        })

	});
	function check_all_o(f)
	{
		var chk = document.getElementsByName("chk[]");
		// var chkInvo = document.getElementsByName("chkInvoice[]");
    	for (i=0; i<chk.length; i++) {
        if(!chk[i].disabled) chk[i].checked = f.chkall.checked;
		}
		// for (j=0; j<chkInvo.length; j++) {
        // if(!chkInvo[j].disabled) chkInvo[j].checked = f.chkall.checked;
    	// }
	}

	function saveCompanyPrice(){
		var complete = false;
		var ckconfirm = confirm("업체별 가격 갱신 하시겠습니까?");
        if(ckconfirm){
			$(".cm_id").each(function() {
				var id = $(this).val();
				var price = $(".input_b_"+id).val();
				// console.log(id , price);
				$.ajax({
					url:'./update_cover_company.php',
					type:'post',
					async: false,
					data:{id : id  , price : price},
					
					error:function(error){
						complete = false;  
					},
					success:function(response){
						complete = true;  
						// location.reload();                
					}
				});
			});	
			if(complete === true){
				alert("처리 되었습니다.");
				location.reload();
			}
		}
		
	}
	
</script>