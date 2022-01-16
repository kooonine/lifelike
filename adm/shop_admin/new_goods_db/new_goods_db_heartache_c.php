<?
$yearsServer = date('Y',  G5_SERVER_TIME);
$orderWhere = "";


if ($cm_search_type) {
 	$orderWhere .= " AND $cm_search_type LIKE '%$cm_search_keyword%'"; 
}

if( (isset($cm_brand) && in_array('전체', $cm_brand)) || !$cm_brand) {

} else {
    $checkNum = 0;
    if (in_array('소프라움', $cm_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (cm_brand_jo = '소프라움'";
        else $orderWhere .= " OR cm_brand_jo = '소프라움'";
        $checkNum = 1;
    } 
    if (in_array('쉐르단', $cm_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (cm_brand_jo = '쉐르단'";
        else $orderWhere .= " OR cm_brand_jo = '쉐르단'";
        $checkNum = 1;
	}
	if (in_array('랄프로렌홈', $cm_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (cm_brand_jo = '랄프로렌홈'";
        else $orderWhere .= " OR cm_brand_jo = '랄프로렌홈'";
        $checkNum = 1;
    } 
    if (in_array('라이프라이크', $cm_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (cm_brand_jo = '라이프라이크'";
        else $orderWhere .= " OR cm_brand_jo = '라이프라이크'";
        $checkNum = 1;
	}
	if (in_array('베온트레', $cm_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (cm_brand_jo = '베온트레'";
        else $orderWhere .= " OR cm_brand_jo = '베온트레'";
        $checkNum = 1;
    } 
    if (in_array('링스티드던', $cm_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (cm_brand_jo = '링스티드던'";
        else $orderWhere .= " OR cm_brand_jo = '링스티드던'";
        $checkNum = 1;
	}
	if (in_array('로자리아', $cm_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (cm_brand_jo = '로자리아'";
        else $orderWhere .= " OR cm_brand_jo = '로자리아'";
        $checkNum = 1;
    } 
    if (in_array('그라치아노', $cm_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (cm_brand_jo = '그라치아노'";
        else $orderWhere .= " OR cm_brand_jo = '그라치아노'";
        $checkNum = 1;
	}
	if (in_array('LBL', $cm_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (cm_brand_jo = 'LBL'";
        else $orderWhere .= " OR cm_brand_jo = 'LBL'";
        $checkNum = 1;
	}
	if (in_array('시뇨리아', $cm_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (cm_brand_jo = '시뇨리아'";
        else $orderWhere .= " OR cm_brand_jo = '시뇨리아'";
        $checkNum = 1;
	}
	if (in_array('플랫폼일반', $cm_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (cm_brand_jo = '플랫폼일반'";
        else $orderWhere .= " OR cm_brand_jo = '플랫폼일반'";
        $checkNum = 1;
	}
	if (in_array('플랫폼렌탈', $cm_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (cm_brand_jo = '플랫폼렌탈'";
        else $orderWhere .= " OR cm_brand_jo = '플랫폼렌탈'";
        $checkNum = 1;
	}
	if (in_array('온라인', $cm_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (cm_brand_jo = '온라인'";
        else $orderWhere .= " OR cm_brand_jo = '온라인'";
        $checkNum = 1;
	}
	if (in_array('템퍼', $cm_brand)) {
        if($checkNum ==0) $orderWhere .= " AND (cm_brand_jo = '템퍼'";
        else $orderWhere .= " OR cm_brand_jo = '템퍼'";
        $checkNum = 1;
    }
    if($checkNum ==1) $orderWhere .= ")";
} 

if ($cm_prod_year_jo) {
	$orderWhere .= " AND cm_prod_year_jo LIKE '%$cm_prod_year_jo%'"; 
}
if ($cm_season_jo) {
	$orderWhere .= " AND cm_season_jo LIKE '%$cm_season_jo%'"; 
}
if ($cm_gubun_jo) {
	$orderWhere .= " AND cm_gubun_jo LIKE '%$cm_gubun_jo%'"; 
}
if (!$orderWhere ||  $orderWhere =='') $orderWhere = 'LIMIT 0';
$totalSql = "SELECT count(*) AS CNT FROM lt_cover_merge  WHERE cm_id IS NOT NULL $orderWhere";

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


$listSql = "SELECT *,  CONCAT(right(cm_prod_year_jo,2),cm_season_jo,' ',cm_gubun_jo) AS merge_season, CONCAT(cm_nabgi_m_ip,'월',cm_nabgi_limit_ip) AS merge_nabgi FROM lt_cover_merge WHERE cm_id IS NOT NULL $orderWhere ORDER BY reg_datetime DESC LIMIT $from_record, $outputCount";
if ($orderWhere == 'LIMIT 0') {
	$listSql = "SELECT *,  CONCAT(right(cm_prod_year_jo,2),cm_season_jo,' ',cm_gubun_jo) AS merge_season, CONCAT(cm_nabgi_m_ip,'월',cm_nabgi_limit_ip) AS merge_nabgi FROM lt_cover_merge WHERE cm_id IS NOT NULL LIMIT 0";
}
$listQuery = sql_query($listSql);

// $headers = array('제품유형','시즌','향균','목표납기','개발자','SO','브랜드','제조구분','분류','상품명','기획서 완료일','원단/완사입업체','원단발주일','원단예정일','예상납기일','비고');
// $bodys = array('cm_prod_type_jo','merge_season','cm_scent','merge_nabgi','cm_user_jo','cm_so','cm_brand_jo','cm_manufacture_gubun','cm_prod_gubun_jo','cm_it_name_jo','cm_approval_date_ps','cm_mater_name_jo','cm_balju_ps','cm_expected_limit_date_ps','cm_ipgo_date_ps','cm_etc');

// $enc = new str_encrypt();

// $excel_sql = $enc->encrypt($excel_sql);
// $headers = $enc->encrypt(json_encode_raw($headers));
// $bodys = $enc->encrypt(json_encode_raw($bodys));
// $summaries = $enc->encrypt(json_encode_raw($summaries));

$qstr= "cm_search_type=".$cm_search_type."&amp;cm_search_keyword=".$cm_search_keyword."&amp;cm_brand=".$cm_brand."&amp;cm_season_jo=".$cm_season_jo."&amp;cm_gubun_jo=".$cm_gubun_jo."&amp;outputCount=".$outputCount."&amp;page=".$page;
if ($allPut) $outputCount = '-1';

?>
<script src="../total_order/jquery.table2excel.js"></script>
<form id="orderMainTable" name="frmorderlist" class="local_sch01 local_sch" onsubmit="$('#page').val('1');" method="get">
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
						<select name="cm_search_type" id="cm_search_type">
							<option value="cm_it_name_jo" <?= get_selected($cm_search_type, 'cm_it_name_jo'); ?>>아이템명</option>
						</select>
						<label for="search" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
						<input type="text" name="cm_search_keyword" value="<?= $cm_search_keyword; ?>" id="cm_search_keyword" class="frm_input" autocomplete="off">
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row">브랜드</th>
				<td colspan="2">
				<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
					<label><input onclick='allCheck("cm_brand")' type="checkbox" name="cm_brand[]" value="전체" id="cm_brand01" <?php if(in_array('전체', $cm_brand) || !$cm_brand) echo "checked"; ?>>전체</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="cm_brand[]" value="소프라움" id="cm_brand02" <?php if(in_array('전체', $cm_brand) || in_array('소프라움', $cm_brand) || !$cm_brand) echo "checked"; ?>>소프라움</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="cm_brand[]" value="쉐르단" id="cm_brand03" <?php if(in_array('전체', $cm_brand) || in_array('쉐르단', $cm_brand) || !$cm_brand) echo "checked"; ?>>쉐르단</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="cm_brand[]" value="랄프로렌홈" id="cm_brand04" <?php if(in_array('전체', $cm_brand) || in_array('랄프로렌홈', $cm_brand) || !$cm_brand) echo "checked"; ?>>랄프로렌홈</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="cm_brand[]" value="베온트레" id="cm_brand05" <?php if(in_array('전체', $cm_brand) || in_array('베온트레', $cm_brand) || !$cm_brand) echo "checked"; ?>>베온트레</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="cm_brand[]" value="링스티드던" id="cm_brand06" <?php if(in_array('전체', $cm_brand) || in_array('링스티드던', $cm_brand) || !$cm_brand) echo "checked"; ?>>링스티드던</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="cm_brand[]" value="로자리아" id="cm_brand07" <?php if(in_array('전체', $cm_brand) || in_array('로자리아', $cm_brand) || !$cm_brand) echo "checked"; ?>>로자리아</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="cm_brand[]" value="그라치아노" id="cm_brand08" <?php if(in_array('전체', $cm_brand) || in_array('그라치아노', $cm_brand) || !$cm_brand) echo "checked"; ?>>그라치아노</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="cm_brand[]" value="시뇨리아" id="cm_brand09" <?php if(in_array('전체', $cm_brand) || in_array('시뇨리아', $cm_brand) || !$cm_brand) echo "checked"; ?>>시뇨리아</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="cm_brand[]" value="플랫폼일반" id="cm_brand10" <?php if(in_array('전체', $cm_brand) || in_array('플랫폼일반', $cm_brand) || !$cm_brand) echo "checked"; ?>>플랫폼일반</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="cm_brand[]" value="플랫폼렌탈" id="cm_brand11" <?php if(in_array('전체', $cm_brand) || in_array('플랫폼렌탈', $cm_brand) || !$cm_brand) echo "checked"; ?>>플랫폼렌탈</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="cm_brand[]" value="온라인" id="cm_brand12" <?php if(in_array('전체', $cm_brand) || in_array('온라인', $cm_brand) || !$cm_brand) echo "checked"; ?>>온라인</label>&nbsp;&nbsp;
					<label><input type="checkbox" name="cm_brand[]" value="템퍼" id="cm_brand12" <?php if(in_array('전체', $cm_brand) || in_array('템퍼', $cm_brand) || !$cm_brand) echo "checked"; ?>>템퍼</label>&nbsp;&nbsp;

				</div>
				</td>
			</tr>
			<tr>
				<th scope="row">시즌</th>
				<td colspan="2">
				<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
					<select name="cm_prod_year_jo" id="cm_prod_year_jo" required oninvalid="this.setCustomValidity('년도를 선택해주세요')" oninput="setCustomValidity('')">
						<option value="" <?= get_selected($cm_prod_year_jo, ''); ?>>선택</option>
						<? for($i = (int)$yearsServer+1; 2009 < $i; $i--) {  ?>
							<option value=<?= $i?> <?= get_selected($cm_prod_year_jo, $i); ?>><?= $i?>년</option>
						<? }?>
					</select>
					<select name="cm_season_jo" id="cm_season_jo" required oninvalid="this.setCustomValidity('시즌를 선택해주세요')" oninput="setCustomValidity('')">
						<option value="" <?= get_selected($cm_season_jo, ''); ?>>선택</option>
						<option value="SS" <?= get_selected($cm_season_jo, 'SS'); ?>>SS</option>
						<option value="HS" <?= get_selected($cm_season_jo, 'HS'); ?>>HS</option>
						<option value="FW" <?= get_selected($cm_season_jo, 'FW'); ?>>FW</option>
						<option value="AA" <?= get_selected($cm_season_jo, 'AA'); ?>>FW</option>
					</select>
					<select name="cm_gubun_jo" id="cm_gubun_jo" required oninvalid="this.setCustomValidity('상품구분를 선택해주세요')" oninput="setCustomValidity('')">
						<option value="" <?= get_selected($cm_gubun_jo, ''); ?>>선택</option>
						<option value="정상" <?= get_selected($cm_gubun_jo, '정상'); ?>>정상</option>
						<option value="기획" <?= get_selected($cm_gubun_jo, '기획'); ?>>기획</option>
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
			<input type="button" value="초기화" class="btn btn_02" onclick="coverCReset()">
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
			<input type="button" value="엑셀다운로드" class="btn btn_02" onclick="down_excel_c()">
			<input type="button" value="저장" class="btn btn_02" onclick="saveCoverDate()">
		</div>
	</div>

	<div class="tbl_head01 tbl_wrap">
		<table id="sodr_list">
			<caption>상품DB전산화(커버) 일정</caption>
			<thead>
				<tr>
					<th scope="col" class="noExport">
						<label for="chkall" class="sound_only">주문 전체</label>
						<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all_o(this.form)">
					</th>
					<th scope="col">제품유형</th>
					<th scope="col">시즌</th>
					<th scope="col">항균</th>
					<th scope="col">목표납기</th>
					<th scope="col">개발자</th>
					<th scope="col">SO</th>
					<th scope="col">브랜드</th>
					<th scope="col">제조구분</th>
					<th scope="col">분류</th>
					<th scope="col">상품명</th>
					<th scope="col">기획서 완료일</th>
					<th scope="col">원단/완사입업체</th>
					<th scope="col">원단발주일</th>
					<th scope="col">원단예정일</th>
					<th scope="col">예상납기일</th>
					<th scope="col">비고</th>
				</tr>
			</thead>
			<tbody>
				<!-- gnagnagn gkfndp gksekh dksvkfflrjrkxdmsp wlfkfhwa gkwl azzz -->
				<?
				for ($i = 0; $row = sql_fetch_array($listQuery); $i++) {
				?>
				<tr class="data_row row_<?=$row['cm_id']?> noExport">
					<td class="noExport">
						<input type="checkbox" name="chk[]" value="<?= $row['cm_id'] ?>" id="chk_<?= $i ?>">
					</td>
					<td>
						<?= $row['cm_prod_type_jo']?>
					</td>
					<td>
						<?= $row['merge_season']?>
					</td>
					<td>
						<select name='cm_scent[]' id="cm_scent_<?= $row['cm_id'] ?>">
							<option value="" <?= get_selected($row['cm_scent'], ''); ?>>선택</option>
							<option value="향균" <?= get_selected($row['cm_scent'], '향균'); ?>>향균</option>
							<option value="비향균" <?= get_selected($row['cm_scent'], '비향균'); ?>>비향균</option>
						</select>
						
					</td>
					<td>
						<?= $row['merge_nabgi']?>
					</td>
					<td>
						<?= $row['cm_user_jo']?>
					</td>
					<td>
						<input name='cmSo[]' id="cmSo_<?= $row['cm_id'] ?>" type="date" value ="<?= $row['cm_so']?>" />
					</td>
					<td>
						<?= $row['cm_brand_jo']?>
					</td>
					<td>
						<select name='cm_manufacture_gubun[]' id="cm_manufacture_gubun_<?= $row['cm_id'] ?>">
							<option value="">선택</option>
							<option value="국/임" <?= get_selected($row['cm_manufacture_gubun'], '국/임'); ?>>국/임</option>
							<option value="국/완" <?= get_selected($row['cm_manufacture_gubun'], '국/완'); ?>>국/완</option>
							<option value="해/임" <?= get_selected($row['cm_manufacture_gubun'], '해/임'); ?>>해/임</option>
							<option value="해/완" <?= get_selected($row['cm_manufacture_gubun'], '해/완'); ?>>해/완</option>
						</select>
					</td>
					<td>
						<?= $row['cm_prod_gubun_jo']?>
					</td>
					<td>
						<?= $row['cm_it_name_jo']?>
					</td>
					<td>
						<input name='cmApprovalDatePs[]' id="cmApprovalDatePs_<?= $row['cm_id'] ?>" type="date" value ="<?= $row['cm_approval_date_ps']?>" />
					</td>
					<td>
					<?
						$cm_mater_name_jo = array();
						if (!empty($row['cm_mater_name_jo'])) {
							$cm_mater_name_jo = json_decode($row['cm_mater_name_jo'], true);
						}
						if (!empty($cm_mater_name_jo)) {
							foreach ($cm_mater_name_jo as $cmn => $cm_mater_name) {?>
								<input name='cmMaterPurchaceIp[]' id="cmMaterPurchaceIp_<?= $row['cm_id'] ?><?= $cmn ?>" type="text" value ="<?= $cm_mater_name['mater']?>" />
							<?}
						} else { ?>
							<input name='cmMaterPurchaceIp[]' id="cmMaterPurchaceIp_<?= $row['cm_id'] ?>" type="text" value ="" />
						<?} 
					?>
						<!-- <input name='cmMaterPurchaceIp[]' id="cmMaterPurchaceIp_<?= $row['cm_id'] ?>" type="text" value ="<?= $row['cm_mater_name_jo']?>" /> -->
					</td>
					<td>
						<input name='cmBaljuPs[]' id="cmBaljuPs_<?= $row['cm_id'] ?>" type="date" value ="<?= $row['cm_balju_ps']?>" />
					</td>
					<td>
						<input name='cmExpectedLimitDatePs[]' id="cmExpectedLimitDatePs_<?= $row['cm_id'] ?>" type="date" value ="<?= $row['cm_expected_limit_date_ps']?>" />
					</td>
					<td>
						<input name='cmIpgoDatePs[]' id="cmIpgoDatePs_<?= $row['cm_id'] ?>" type="date" value ="<?= $row['cm_ipgo_date_ps']?>" />
					</td>
					<td>
						<input name='cm_etc[]' id="cm_etc_<?= $row['cm_id'] ?>" type="text" size ="10px" style="size:10px" value ="<?= $row['cm_etc']?>">
					</td>
				</tr>
				<?
				}
				if ($i == 0)
					echo '<tr><td colspan="19" class="empty_table">자료가 없습니다.</td></tr>';
				?>
			</tbody>
		</table>
	</div>

	<div class="local_cmd01 local_cmd">
		<div style="float: left">
			<input type="button" value="엑셀다운로드" class="btn btn_02" onclick="down_excel_c()">
			<input type="button" value="저장" class="btn btn_02" onclick="saveCoverDate()">
		</div>
	</div>

</form>

<?= get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>


<script>
	// function enterSearch() {
    //     if (window.event.keyCode == 13) {
    //     	document.getElementById('new_goods_form').submit();
    // 	}
	// }
	function saveCoverDate() {
		var check = confirm("저장 하시겠습니까?"); 
        if(check){
            if (!is_checked("chk[]")) {
                alert("하나 이상 선택하세요.");
                return false;
			}
			let materSplit = '';
			var select_obj = '';
			var mappingCov = {};
			var materName = [];
            var saveCom = {};
			$("input[name='chk[]']:checked").each(function (index) { 
				materName = [];
				mappingCov[$(this).val()] = {};
				Object.assign(mappingCov[$(this).val()], {'cm_scent':$(`#cm_scent_${$(this).val()}`).val()});
				Object.assign(mappingCov[$(this).val()], {'cm_manufacture_gubun':$(`#cm_manufacture_gubun_${$(this).val()}`).val()});
				Object.assign(mappingCov[$(this).val()], {'cmSo':$(`#cmSo_${$(this).val()}`).val()});
				Object.assign(mappingCov[$(this).val()], {'cmApprovalDatePs':$(`#cmApprovalDatePs_${$(this).val()}`).val()});
				Object.assign(mappingCov[$(this).val()], {'cmBaljuPs':$(`#cmBaljuPs_${$(this).val()}`).val()});
				Object.assign(mappingCov[$(this).val()], {'cmExpectedLimitDatePs':$(`#cmExpectedLimitDatePs_${$(this).val()}`).val()});
				Object.assign(mappingCov[$(this).val()], {'cmIpgoDatePs':$(`#cmIpgoDatePs_${$(this).val()}`).val()});
				Object.assign(mappingCov[$(this).val()], {'cm_etc':$(`#cm_etc_${$(this).val()}`).val()});

				$("input[id*='cmMaterPurchaceIp_"+$(this).val()+"']").each(function (index) { 
					if ($(this).val() && $(this).val() != '' && $(this).val() != null) {
						materSplit = $(this).val().split(',');
						for ( let mi in materSplit ) {
							materName.push({mater:materSplit[mi]});
      					}
					}
				});
				Object.assign(mappingCov[$(this).val()], {'cmMaterPurchaceIp':materName});

			});
			$.ajax({
                url: "./ajax.new_goods_db_cover_c.php",
                method: "POST",
                data: {
                    'mappingCov' : mappingCov
                },
                dataType: "json",
                async: false,
                cache: false,
                success: function(result) {
					// console.log('result',result);
                    location.reload();
                }
            });
			return;
		}
	}
	function down_excel_c(){
		if (!is_checked("chk[]")) {
            alert("하나 이상 선택하세요.");
            return false;
		}
		let excelMerge = {};
		let seasonNameChk = null;
		$(".data_row").addClass("noExport");

		$("input[name='chk[]']:checked").each(function (index) { 
			$(".row_"+this.value).removeClass("noExport");
		});

        $("#sodr_list").table2excel({
            name: "Excel table",
            filename: "상품DB전산화(커버) 일정" + new Date().toISOString().replace(/[\-\:\.]/g, ""),
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
	// function excel_download_c() {
	// 	if (!is_checked("chk[]")) {
    //         alert("하나 이상 선택하세요.");
    //         return false;
	// 	}
    //     var $form = $('<form></form>');     

    //     $form.attr('action', '<?=G5_ADMIN_URL?>/ajax.excel_download.new_goods_db_cover_c.php');
    //     $form.attr('method', 'post');
    //     $form.appendTo('body');
    //     var excel_obj = '';
    //     $("input[name='chk[]']:checked").each(function (index) {
    //         if (index != 0) {
    //             excel_obj += ',';
    //         }
    //         excel_obj += $(this).val();            
	// 	});

    //     var excel_sql = `SELECT *, CONCAT(right(cm_prod_year_jo,2),cm_season_jo,'  ',cm_gubun_jo) AS merge_season, CONCAT(cm_nabgi_m_ip,'월',cm_nabgi_limit_ip) AS merge_nabgi FROM lt_cover_merge WHERE cm_id IN (${excel_obj}) ORDER BY reg_datetime DESC`;

	// 	var exceldata = $('<input type="hidden" value="'+excel_sql+'" name="exceldata">');
    //     var headerdata = $('<input type="hidden" value="<?=$headers?>" name="headerdata">');
    //     var bodydata = $('<input type="hidden" value="<?=$bodys?>" name="bodydata">');
    //     var excelnamedata = $('<input type="hidden" value="상품DB전산화(커버)/일정" name="excelnamedata">');
    //     $form.append(exceldata).append(headerdata).append(bodydata).append(excelnamedata);
    //     $form.submit();
	// }
	function coverCReset() {
        $("input:checkbox[id*='cm_brand']").prop("checked",true);
        $("#cm_search_type").val('cm_it_name_jo');
		$("#cm_search_keyword").val('');
		$("#cm_prod_year_jo").val('');
		$("#cm_season_jo").val('');
		$("#cm_gubun_jo").val('');
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
	
</script>