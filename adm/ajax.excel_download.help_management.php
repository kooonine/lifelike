<?
include_once('./_common.php');
$enc = new str_encrypt();

$excel_sql = $exceldata;
$headers = json_decode($enc->decrypt($headerdata), true);
$bodys = json_decode($enc->decrypt($bodydata), true);
$summaries = json_decode($enc->decrypt($summarydata), true);
$excelname = $excelnamedata;

$excel_sql = stripslashes($excel_sql);

$widths = array();
// for ($i = 0; $i < count($headers); $i++) {
array_push($widths, 5);
array_push($widths, 15);
array_push($widths, 20);
array_push($widths, 20);
array_push($widths, 40);
array_push($widths, 50);
array_push($widths, 40);
array_push($widths, 50);
array_push($widths, 15);
array_push($widths, 15);
array_push($widths, 20);
array_push($widths, 15);
array_push($widths, 20);


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

$mcheck = '';
$idxCheck = '';
$merNum = 0;
for ($i = 0; $ex_data_row = sql_fetch_array($excel_result); $i++) {
	if (!empty($ex_row)) {
		array_push($rows, $ex_row);
	}

	// $row_name_count = 0;
	$ex_row = array();
	// $tmp_od_id = $ex_data_row['od_id'];
	$tmp_row_id++;

	array_push($ex_row, ' ' . $tmp_row_id);	// A열 순번 추가
	if ($ex_data_row['qa_status']) {
		$ex_data_row['qa_status'] = '답변완료';
	} else {
		$ex_data_row['qa_status'] = '답변대기';
	}
	if ($ex_data_row['qa_status']) {
		$ex_data_row['qa_status'] = '답변완료';
	} else {
		$ex_data_row['qa_status'] = '답변대기';
	}
	if ($ex_data_row['qa_category'] == '배송' || $ex_data_row['qa_category'] == '취소' || $ex_data_row['qa_category'] == '반품' || $ex_data_row['qa_category'] == '환불' || $ex_data_row['qa_category'] == '사은품' || $ex_data_row['qa_category'] == '증빙서류' ) {
		$ex_data_row['qa_category1'] = '구매상품';
	} else if ($ex_data_row['qa_category'] == '회원정보' || $ex_data_row['qa_category'] == '쿠폰/포인트' || $ex_data_row['qa_category'] == '이벤트 당첨') { 
		$ex_data_row['qa_category1'] = '개인정보';
	} else if ($ex_data_row['qa_category'] == '세탁서비스') { 
		$ex_data_row['qa_category1'] = '리스';
	} else if ($ex_data_row['qa_category'] == '시스템오류' || $ex_data_row['qa_category'] == '기타') { 
		$ex_data_row['qa_category1'] = '기타';
	}
	// 다시만들어야지 ㅋㅋ
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
