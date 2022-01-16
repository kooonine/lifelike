<?
include_once('./_common.php');
$enc = new str_encrypt();

$excel_sql = $exceldata;
$headers = json_decode($enc->decrypt($headerdata), true);
$bodys = json_decode($enc->decrypt($bodydata), true);
$summaries = json_decode($enc->decrypt($summarydata), true);
$excelname = $excelnamedata;

$widths = array();
for ($i = 0; $i < count($headers); $i++) {
	array_push($widths, 20);
}
$header_bgcolor = 'FFABCDEF';
$last_char = column_char(count($headers) - 1);

$rows = array();
$excel_result = sql_query($excel_sql);
$row_name_cell = null;
$row_name_count = 0;
$tmp_od_id = null;
$tmp_row_id = 0;

for ($i = 1; $ex_data_row = sql_fetch_array($excel_result); $i++) {
	if (!empty($ex_row)) {
		array_push($rows, $ex_row);
	}

	$row_name_count = 0;
	$ex_row = array();
	$tmp_od_id = $ex_data_row['od_id'];
	$tmp_row_id++;

	array_push($ex_row, ' ' . $tmp_row_id);	// A열 순번 추가

	for ($j = 1; $j < count($bodys); $j++) {
		$cell_key = $bodys[$j];
		$cell_data = stripslashes($ex_data_row[$cell_key]);
		//$cell_data = json_decode($cell_data,JSON_UNESCAPED_UNICODE);
		if ($cell_key == 'ps_id') $cell_data = str_pad($ex_data_row['ps_id'], 5, "0", STR_PAD_LEFT);

		if($cell_key == 'jo_design_img'){
			$cell_data = G5_URL ."/data/new_goods/".$ex_data_row['jo_design_img']; // 이미지 경로
		}

		//작업지시서
		if($cell_key == 'jo_soje'){
			$cell_data = "";
			$jo_soje_set = array();
			if (!empty($ex_data_row['jo_soje'])) {
				$jo_soje_set = json_decode($ex_data_row['jo_soje'], true);
			}
			foreach ($jo_soje_set as $js => $soje){
				if($js == 1){

				}else{
					$cell_data .= ','.PHP_EOL;
				}
				$cell_data .= $soje['subject'];
			}			
		}
		if($cell_key == 'jo_mater_info'){
			$cell_data = "";
			$jo_mater_info = array();
			if (!empty($ex_data_row['jo_mater_info'])) {
				$jo_mater_info = json_decode($ex_data_row['jo_mater_info'], true);
			}
			foreach ($jo_mater_info as $jm => $mater_info){
				if($jm == 1){

				}else{
					$cell_data .= ',';
				}
				$cell_data .= $mater_info['info'];
				$cell_data .= PHP_EOL;
				$cell_data .= '폭:'.$mater_info['wid'].PHP_EOL;
				$cell_data .= '길이:'.$mater_info['length'].PHP_EOL;
				$cell_data .= '요척:'.$mater_info['yochek'].PHP_EOL;
				$cell_data .= '재재단가 /yd, V-*:'.$mater_info['danga'].PHP_EOL;
				$cell_data .= '원자재 금액 V-*:'.$mater_info['price'].PHP_EOL;
			}

		}

		if($cell_key == 'jo_sub_mater'){
			$cell_data = "";
			$jo_sub_mater = array();
			if (!empty($ex_data_row['jo_sub_mater'])) {
				$jo_sub_mater = json_decode($ex_data_row['jo_sub_mater'], true);
			}
			foreach ($jo_sub_mater as $jsm => $sub_mater){
				if($jsm == 1){

				}else{
					$cell_data .= ',';
				}
				$cell_data .= '부자재내역:'.$sub_mater['history'].PHP_EOL;
				$cell_data .= '단가:'.$sub_mater['price'].PHP_EOL;
				
			}
		}

		//제품기획서
		if($cell_key == 'ip_mater_purchace'){
			$cell_data = "";
			$ip_mater_purchace_set = array();
			if (!empty($ex_data_row['ip_mater_purchace'])) {
				$ip_mater_purchace_set = json_decode($ex_data_row['ip_mater_purchace'], true);
			}
			foreach ($ip_mater_purchace_set as $imps => $purchace){
				if($imps == 1){

				}else{
					$cell_data .= ','.PHP_EOL;
				}
				$cell_data .= $purchace['purchace'];
				$cell_data .= PHP_EOL;
				$cell_data .= '이미지:'.$purchace['img'].PHP_EOL;
				$cell_data .= '매입처:'.$purchace['maip'].PHP_EOL;
				$cell_data .= '자재단가:'.$purchace['danga'].PHP_EOL;
				$cell_data .= '소재:'.$purchace['soje'].PHP_EOL;
			}			
		}
		if($cell_key == 'ip_processing'){
			$cell_data = "";
			$ip_processing_info = array();
			if (!empty($ex_data_row['ip_processing'])) {
				$ip_processing_info = json_decode($ex_data_row['ip_processing'], true);
			}
			foreach ($ip_processing_info as $ipi => $processing){
				if($ipi == 1){

				}else{
					$cell_data .= ',';
				}
				$cell_data .= '아이템명:'.$processing['item'].PHP_EOL;
				$cell_data .= '가공처:'.$processing['gakong'].PHP_EOL;
				$cell_data .= '가공임:'.$processing['gakongp'].PHP_EOL;
			}

		}

		if($cell_key == 'ip_finished'){
			$cell_data = "";
			$ip_finished_set = array();
			if (!empty($ex_data_row['ip_finished'])) {
				$ip_finished_set = json_decode($ex_data_row['ip_finished'], true);
			}
			foreach ($ip_finished_set as $ipf => $finished){
				if($ipf == 1){

				}else{
					$cell_data .= ',';
				}
				$cell_data .= '아이템명:'.$finished['item'].PHP_EOL;
				$cell_data .= '사이즈:'.$finished['size'].PHP_EOL;
				$cell_data .= '매입가:'.$finished['meip'].PHP_EOL;
				$cell_data .= '예상원가:'.$finished['onega'].PHP_EOL;
				$cell_data .= '예상소비자가:'.$finished['comsum'].PHP_EOL;
				$cell_data .= '할인적용률:'.$finished['srate'].PHP_EOL;
				$cell_data .= '예상세일가:'.$finished['sprice'].PHP_EOL;
				$cell_data .= '공헌이익율:'.$finished['khrate'].PHP_EOL;
				$cell_data .= '예상생산수량:'.$finished['prodqty'].PHP_EOL;
				$cell_data .= '총예상발주금액:'.$finished['totalp'].PHP_EOL;
				
			}
		}


		

		array_push($ex_row, $cell_data);
	}
	
}
if (!empty($ex_row)) array_push($rows, $ex_row);

function column_char($i)
{
	$prefix = '';
	if ( $i >= 51 ) {
		$prefix = 'B';
		$i = $i - 51;
	}else if ( $i >= 25 ) {
		$prefix = 'A';
		$i = $i - 25;
	}
	return $prefix . chr(65 + $i);
}
include_once(G5_LIB_PATH . '/PHPExcel.php');

$data = array_merge(array($headers), $rows);
if($excelname){
	$script_pathinfo['filename'] = $excelname;
}else{
	$script_pathinfo = pathinfo($_SERVER['HTTP_REFERER']);
}

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0)->getStyle("A1:${last_char}1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
$excel->setActiveSheetIndex(0)->getStyle("A:$last_char")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
foreach ($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension(column_char($i))->setWidth($w);
$excel->getActiveSheet()->fromArray($data, NULL, 'A1');

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=" . date("ymd", time()) . "_" . $script_pathinfo['filename'] . ".xls");
header("Cache-Control: max-age=0");


$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
$writer->save('php://output');
