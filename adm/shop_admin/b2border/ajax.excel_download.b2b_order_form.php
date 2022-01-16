<?
include_once('./_common.php');
$enc = new str_encrypt();

$excel_sql = $exceldata;
$headers = json_decode($enc->decrypt($headerdata), true);
$bodys = json_decode($enc->decrypt($bodydata), true);
$summaries = json_decode($enc->decrypt($summarydata), true);
$excelname = $excelnamedata;
$excelcpcode = $excelcpcode;
$excel_sql = stripslashes($excel_sql);
$widths = array();
// for ($i = 0; $i < count($headers); $i++) {

	if($excelname =='특판주문서'){
		array_push($widths, 18);
		array_push($widths, 10);
		array_push($widths, 20);
		array_push($widths, 10);
		array_push($widths, 20);
		array_push($widths, 10);
		array_push($widths, 45);
		array_push($widths, 6);
		array_push($widths, 18);
		array_push($widths, 10);
		array_push($widths, 10);
		array_push($widths, 8);
		array_push($widths, 8);
		array_push($widths, 50);
		

	}else{
		if($excelcpcode == '19941'){
			array_push($widths, 23);
			array_push($widths, 16);
			array_push($widths, 20);
			array_push($widths, 10);
			array_push($widths, 115);
			array_push($widths, 40);
			array_push($widths, 7);
			array_push($widths, 8);
			array_push($widths, 10);
			array_push($widths, 10);
			array_push($widths, 6);
			array_push($widths, 6);
			array_push($widths, 45);
			array_push($widths, 15);
			array_push($widths, 18);
			array_push($widths, 55);
			array_push($widths, 15);
			array_push($widths, 20);
			array_push($widths, 15);
			array_push($widths, 20);
			array_push($widths, 20);
			array_push($widths, 20);
			array_push($widths, 20);
			array_push($widths, 20);
			array_push($widths, 30);
		}else{
			array_push($widths, 11);
			array_push($widths, 10);
			array_push($widths, 18);
			array_push($widths, 10);
			array_push($widths, 15);
			array_push($widths, 11);
			array_push($widths, 80);
			array_push($widths, 21);
			array_push($widths, 45);
			array_push($widths, 18);
			array_push($widths, 7);
			array_push($widths, 10);
			array_push($widths, 8);
			array_push($widths, 8);
			array_push($widths, 8);
		}
	}

	

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
	
	

	$row_name_count = 0;
	$ex_row = array();
	$tmp_od_id = $ex_data_row['od_id'];
	$tmp_row_id++;

	$form_row = $ex_data_row['order_qty'];

	if($excelcpcode == '19941'){
		if($excelname =='특판발주서'){
			$ex_data_row['sender'] = '리탠다드';
			$ex_data_row['sender_tel'] = '031-329-7922';
			$ex_data_row['sender_addr'] = '경기도 용인시 처인구 양지면 양지로 271';
		}
	}else{
		if($excelname =='특판발주서'){
			$ex_data_row['order_qty'] = 1;
			$ex_data_row['box_qty'] = 1;
		}
	}

	for ($j = 0; $j < count($bodys); $j++) {
		$cell_key = $bodys[$j];
		$cell_data = stripslashes($ex_data_row[$cell_key]);

		array_push($ex_row, $cell_data);
	}

	
	if($excelname =='특판주문서'){
		if (!empty($ex_row)) {
			array_push($rows, $ex_row);
		}

	}else{
		if($excelcpcode == '19941'){
			if (!empty($ex_row)) {
				array_push($rows, $ex_row);
			}
		}else {
			for($f = 0; $f < $form_row ; $f++ ){
				if (!empty($ex_row)) {
					array_push($rows, $ex_row);
				}
			}
		}
	}

	

	
	

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
	// if(empty($ex_data_row['receive_cel'])){
	// 	$ex_data_row['receive_cel'] = $ex_data_row['receive_tel'];
	// }
	// if($ex_data_row['mall_id'] != '19940'){
	// 	$ex_data_row['sub_order_id'] = '';
	// }

	// if(empty($ex_data_row['samjin_barcode_size'])){
	// 	$ex_data_row['order_it_size'] = $ex_data_row['order_it_size'];
	// }else{
	// 	$ex_data_row['order_it_size'] = $ex_data_row['samjin_barcode_size'];
	// }

	

	
}

// if (!empty($ex_row)) array_push($rows, $ex_row);

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
if($excelcpcode == '19941'){
	foreach ($rows as $n => $inv){
		$excel->getActiveSheet()->setCellValueExplicit("S".($n+2), $inv[18], PHPExcel_Cell_DataType::TYPE_STRING);
	}
}
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=" . date("ymd", time()) . "_" . $script_pathinfo['filename'] . ".xls");
header("Cache-Control: max-age=0");


$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
$writer->save('php://output');
