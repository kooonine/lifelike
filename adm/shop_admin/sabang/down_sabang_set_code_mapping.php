<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/PHPExcel.php');
include_once(G5_LIB_PATH . '/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

ini_set('memory_limit', '256M');


$enc = new str_encrypt();

$excel_sql = $exceldata;
$headers = json_decode($enc->decrypt($headerdata), true);
$bodys = json_decode($enc->decrypt($bodydata), true);
$summaries = json_decode($enc->decrypt($summarydata), true);
$excelname = $excelnamedata;


$widths = array();
array_push($widths, 9);
array_push($widths, 9);
array_push($widths, 120);
array_push($widths, 10);
array_push($widths, 60);
array_push($widths, 25);
array_push($widths, 10);
array_push($widths, 25);
array_push($widths, 25);


$referer = $_SERVER["HTTP_REFERER"];

$cut_url = explode("?" , $referer);


$qstr=$cut_url[1];


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
	// if ($excelname == '반품등록') $ex_data_row['order_cnt'] = '-'.$ex_data_row['order_cnt'];
	

	for ($j = 0; $j < count($bodys); $j++) {
		$cell_key = $bodys[$j];
		$cell_data = stripslashes($ex_data_row[$cell_key]);
		
		if($cell_key == 'status'){
			if($ex_data_row['status'] == '0001'){
				$cell_data = "판매중";
			}else{
				$cell_data = "판매중지";
			}
		}
		// if ($cell_key == 'mall_order_no') {	
		// 	if( is_numeric($cell_data) && strlen($cell_data) > 15 ) {
		// 		$cell_data = $cell_data."_";
		// 	}		
		// }

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

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=" . date("ymd", time()) . "_" . $script_pathinfo['filename'] . ".xls");
header("Cache-Control: max-age=0");


$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
$writer->save('php://output');

?>