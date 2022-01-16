<?
include_once('./_common.php');
$enc = new str_encrypt();

$excel_sql = stripslashes($exceldata);
$sec = $sec;

$excel_sql1 = stripslashes($exceldata_1);
$excel_sql2 = stripslashes($exceldata_2);
$excel_sql3 = stripslashes($exceldata_3);
$excel_sql4 = stripslashes($exceldata_4);

$headers = json_decode($enc->decrypt($headerdata), true);
$bodys = json_decode($enc->decrypt($bodydata), true);
$summaries = json_decode($enc->decrypt($summarydata), true);
$excelname = $excelnamedata;

$widths = array();
// for ($i = 0; $i < count($headers); $i++) {
array_push($widths, 25);
array_push($widths, 15);
array_push($widths, 10);
array_push($widths, 8);
array_push($widths, 10);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);
array_push($widths, 6);

// }

$mergeCell = array();
// array_push($mergeCell, 'J2:J3');
// array_push($mergeCell, 'J4:J6');
// array_push($mergeCell, 'J9:J10');

$header_bgcolor = 'FFABCDEF';
$last_char = column_char(count($headers) - 1);

$rows = array();


preg_match_all("/[^() ||  ,]+/", $excel_sql1,$mallCode);
preg_match_all("/[^() ||  ,]+/", $excel_sql2,$colors);
preg_match_all("/[^() ||  ,]+/", $excel_sql3,$sizes);
preg_match_all("/[^() ||  ,]+/", $excel_sql4,$samjinCodes);

$index = 0;

foreach($mallCode[0] as $mallC) { 
	$color =  $colors[0][$index];
	$size = $sizes[0][$index];
	$samjinCode = $samjinCodes[0][$index];
	$index = $index +1;
	$sql_r = "SELECT sum(snor_cnt) AS sumcnt , snor_id,snor_mall_code, snor_mall_name, snor_samjin_code, snor_samjin_name,snor_samjin_color,snor_samjin_size, snor_price, snor_division_price, snor_mall_order_no, snor_sabang_ord_no 
	FROM samjin_order_delivery_order_return $sec  AND snor_mall_code = '$mallC' AND snor_samjin_color = '$color' AND snor_samjin_size = '$size' AND snor_samjin_code = '$samjinCode'
	GROUP BY snor_mall_code, snor_samjin_code, snor_samjin_color, snor_samjin_size ORDER BY snor_mall_code ASC limit 1";


	$excel_result = sql_query($sql_r);

	for ($i = 0; $ex_data_row = sql_fetch_array($excel_result); $i++) { 
		if (!empty($ex_row)) {
			array_push($rows, $ex_row);
		}
		$ex_row = array();

		for ($j = 0; $j < count($bodys); $j++) {
			$cell_key = $bodys[$j];
			$cell_data = stripslashes($ex_data_row[$cell_key]);
			array_push($ex_row, $cell_data);
		}
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
