<?php
$sub_menu = '90';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");


$board_table = $g5['write_prefix'] . $bo_table;

$sql = " select wr_content, wr_datetime from {$board_table} where wr_parent = {$wr_id} and wr_is_comment = 1 ";
$result = sql_query($sql);

if(!@sql_num_rows($result))
    alert_close('목록이 없습니다.');

function column_char($i) { return chr( 65 + $i ); }

include_once(G5_LIB_PATH.'/PHPExcel.php');

$headers = array('NO', '일자', '이름', '이메일', '휴대전화번호', '생년월일', '성별', '주소', '추가정보');
$widths  = array(10, 20, 15, 15, 30, 25, 15, 15);
$header_bgcolor = 'FFABCDEF';
$last_char = column_char(count($headers) - 1);
$rows = array();

for($i=1; $row=sql_fetch_array($result); $i++) {
    
    $wr_content =  trim(stripslashes($row['wr_content']));
    $wr_content = json_decode($wr_content, true);
    $address = "";
    if($wr_content['address']['zip']) $address .= $wr_content['address']['zip']." ";
    if($wr_content['address']['adrr1']) $address .= $wr_content['address']['adrr1']." ";
    if($wr_content['address']['adrr2']) $address .= $wr_content['address']['adrr2']." ";
    if($wr_content['address']['adrr3']) $address .= $wr_content['address']['adrr3']." ";
    
    $sex = "";
    if($wr_content['sex'] == "M") $sex = "남성";
    else if($wr_content['sex'] == "F") $sex = "여성";
    
    $rows[] = 
        array(' '.$i,
              $row['wr_datetime'], 
                $wr_content['name'],
                $wr_content['email'],
                $wr_content['phone'],
                $wr_content['age'],
                $sex,
                $address,
                stripslashes(str_replace("{","",str_replace("}","",json_encode_raw($wr_content['addItem'])))),
              ' ');
}

$data = array_merge(array($headers), $rows);

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0)->getStyle( "A1:${last_char}1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
$excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
$excel->getActiveSheet()->fromArray($data,NULL,'A1');

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".$bo_table."-".date("ymd", time()).".xls\"");
header("Cache-Control: max-age=0");

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
$writer->save('php://output');

?>