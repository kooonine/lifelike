<?
include_once('./_common.php');
$enc = new str_encrypt();

$excel_sql = stripslashes($exceldata);
$sec = $sec;



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
array_push($widths, 6);

// }

$mergeCell = array();
// array_push($mergeCell, 'J2:J3');
// array_push($mergeCell, 'J4:J6');
// array_push($mergeCell, 'J9:J10');

$header_bgcolor = 'FFABCDEF';
$last_char = column_char(count($headers) - 1);

$rows = array();

$sql_r = "SELECT samjin_name, samjin_code, samjin_brand, samjin_color, samjin_size, SUM(m15001) AS m15001, SUM(m19940) AS m19940, SUM(m19942) AS m19942, SUM(m19943) AS m19943, SUM(m19944) AS m19944, SUM(m19945) AS m19945, SUM(m19950) AS m19950, SUM(m19951) AS m19951, SUM(m19952) AS m19952, SUM(m19953) AS m19953, SUM(m19954) AS m19954, SUM(m19955) AS m19955, SUM(m19956) AS m19956, SUM(m19957) AS m19957, SUM(m19958) AS m19958, SUM(m19961) AS m19961, SUM(m19962) AS m19962, SUM(m19963) AS m19963, SUM(m19964) AS m19964, SUM(m19965) AS m19965, SUM(m19966) AS m19966, SUM(m19967) AS m19967, SUM(m19968) AS m19968, SUM(m19970) AS m19970, SUM(m19971) AS m19971, SUM(m19972) AS m19972, SUM(m19973) AS m19973, SUM(m19974) AS m19974, SUM(m19975) AS m19975, SUM(m19976) AS m19976, SUM(m19977) AS m19977, SUM(m19978) AS m19978, SUM(m19979) AS m19979 , warehouse_no FROM samjin_order_delivery_order $sec and samjin_name in ( $excel_sql ) GROUP BY dpartner_name, samjin_name,samjin_color, samjin_size, warehouse_no";

$excel_result = sql_query($sql_r);


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

		if ($cell_key == 'mall_order_no') {	
			if( is_numeric($cell_data) && strlen($cell_data) > 15 ) {
				$cell_data = $cell_data."_";
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
