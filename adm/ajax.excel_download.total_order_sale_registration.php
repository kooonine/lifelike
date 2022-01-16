<?
include_once('./_common.php');
$enc = new str_encrypt();

$excel_sql = $exceldata;
$headers = json_decode($enc->decrypt($headerdata), true);
$bodys = json_decode($enc->decrypt($bodydata), true);
$summaries = json_decode($enc->decrypt($summarydata), true);
$excelname = $excelnamedata;

$widths = array();
// for ($i = 0; $i < count($headers); $i++) {
array_push($widths, 7);
array_push($widths, 15);
array_push($widths, 15);
array_push($widths, 20);
array_push($widths, 50);
array_push($widths, 7);
array_push($widths, 7);
array_push($widths, 7);
array_push($widths, 7);
array_push($widths, 7);
array_push($widths, 15);
array_push($widths, 15);
array_push($widths, 15);
array_push($widths, 30);
array_push($widths, 15);
array_push($widths, 30);

// }

$mergeCell = array();
// array_push($mergeCell, 'J2:J3');
// array_push($mergeCell, 'J4:J6');
// array_push($mergeCell, 'J9:J10');

$header_bgcolor = 'FFABCDEF';
$last_char = column_char(count($headers) - 1);

$rows = array();
$excel_result = sql_query($excel_sql);
$row_name_cell = null;
$row_name_count = 0;
$tmp_od_id = null;
$tmp_row_id = 0;

$tmp_addr = "";
$tmp_box_sum = 0;
$box_st = 0;
$meger_st = "";

for ($i = 0; $ex_data_row = sql_fetch_array($excel_result); $i++) {
	if (!empty($ex_row)) {
		array_push($rows, $ex_row);
	}

	$row_name_count = 0;
	$ex_row = array();
	$tmp_od_id = $ex_data_row['od_id'];
	$tmp_row_id++;
	if ($excelname == '반품등록') $ex_data_row['order_cnt'] = '-'.$ex_data_row['order_cnt'];
	//array_push($ex_row, ' ' . $tmp_row_id);	// A열 순번 추가
	
	// if($i == 0 ){
	// 	$tmp_addr = $ex_data_row['receive_addr'];
	// 	$tmp_box_sum = $ex_data_row['order_sum_sno'];
	// }else{
	
	// 	if($ex_data_row['order_sum_sno'] > 1){
	// 		if($tmp_addr == $ex_data_row['receive_addr'] && $tmp_box_sum == $ex_data_row['order_sum_sno']){
	// 			$box_st++;
	// 			if($box_st == 1){
	// 				$meger_st = "J". ($i+1) . ":J".($i+$tmp_box_sum) ;
	// 				array_push($mergeCell, $meger_st);
	// 			}
				

	// 		}else {
	// 			$tmp_addr = $ex_data_row['receive_addr'];
	// 			$tmp_box_sum = $ex_data_row['order_sum_sno'];
				
	// 			$box_st = 0;
				
				
	// 		}
	// 	}
			
		
	// }

	for ($j = 0; $j < count($bodys); $j++) {
		$cell_key = $bodys[$j];
		$cell_data = stripslashes($ex_data_row[$cell_key]);
		
		if($cell_key == 'order_gb'){
			if($ex_data_row['order_gb'] == '001'){
				$cell_data = "단품";
			}else{
				$cell_data = "세트";
			}
		}
		// if ($cell_key == 'mall_order_no') {	
		// 	if( is_numeric($cell_data) && strlen($cell_data) > 15 ) {
		// 		$cell_data = $cell_data."_";
		// 	}		
		// }

		if ($ex_data_row['order_gb'] == '002' && $cell_key == 'order_division_price') {	
				$cell_data = $ex_data_row['set_division_price'];
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

function merge_cell($str){
	$cell = '';
	$cell = substr($str,0,2);

	return $cell;
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

foreach ($mergeCell as $mi => $mc) $excel->setActiveSheetIndex(0)->mergeCells($mc)->setCellValue(merge_cell($mc),'1');


$excel->getActiveSheet()->fromArray($data, NULL, 'A1');
foreach ($rows as $n => $inv){
	$excel->getActiveSheet()->setCellValueExplicit("N".($n+2), $inv[13], PHPExcel_Cell_DataType::TYPE_STRING);
}

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=" . date("ymd", time()) . "_" . $script_pathinfo['filename'] . ".xls");
header("Cache-Control: max-age=0");


$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
$writer->save('php://output');
