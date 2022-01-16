<?php
$sub_menu = '92';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

// 주문정보
$sql = " select a.it_time, c.company_name, c.company_code, a.it_name, a.it_id, ifnull(a.it_commission,'') it_commission 
                from lt_shop_item a
                  inner join lt_member_company as c
                    on a.ca_id3 = c.company_code
               where a.ca_id3 != '' and c.company_code = '{$company_code}'
            order by it_id desc ";
$result = sql_query($sql);

if(!@sql_num_rows($result))
    alert_close('상품 목록이 없습니다.');

function column_char($i) { return chr( 65 + $i ); }

if (phpversion() >= '5.2.0') {
    include_once(G5_LIB_PATH.'/PHPExcel.php');
    
    $headers = array('NO', '일자', '업체명', '업체코드', '상품명', '상품코드', '수수료 기존', '수수료 변경');
    $widths  = array(10, 20, 15, 15, 30, 25, 15, 15);
    $header_bgcolor = 'FFABCDEF';
    $last_char = column_char(count($headers) - 1);
    $rows = array();
    
    for($i=1; $row=sql_fetch_array($result); $i++) {
        $rows[] = 
            array(' '.$i,
                  $row['it_time'], 
                  $row['company_name'], 
                  $row['company_code'], 
                  $row['it_name'], 
                  $row['it_id'], 
                  ' '.$row['it_commission'], 
                  ' ');
    }

    $data = array_merge(array($headers), $rows);

    $excel = new PHPExcel();
    $excel->setActiveSheetIndex(0)->getStyle( "A1:${last_char}1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
    $excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
    foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
    $excel->getActiveSheet()->fromArray($data,NULL,'A1');

    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"companyitemlist-".$company_code."-".date("ymd", time()).".xls\"");
    header("Cache-Control: max-age=0");

    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
    $writer->save('php://output');
}
?>