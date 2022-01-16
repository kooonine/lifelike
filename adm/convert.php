<?php
include_once("./_common.php");
include_once(G5_LIB_PATH . '/Excel/reader.php');

function column_char($i)
{
    return chr(65 + $i);
}

if (!function_exists('iconv_utf8')) {
    function iconv_utf8(string $str)
    {
        return iconv('EUC-KR', 'UTF-8', $str);
    }
}

$file = $_FILES['samjin_excel']['tmp_name'];
if (!empty($file)) {
    $data = new Spreadsheet_Excel_Reader();

    // Set output Encoding.
    $data->setOutputEncoding('UTF-8');
    $data->read($file);
    $orders = array();
    $orderItem = array();
    $lastOrderId = null;

    for ($i = 5; $i <= $data->sheets[0]['numRows']; $i++) {
        $orderId = preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][2]);
        if (empty($orderId)) $orderId = $lastOrderId;

        $item = iconv_utf8(sprintf("%s(%s/%s)", $data->sheets[0]['cells'][$i][6], $data->sheets[0]['cells'][$i][7], $data->sheets[0]['cells'][$i][9]));
        $itemCode = $data->sheets[0]['cells'][$i][5];

        if ($orderId != $lastOrderId) {
            $orders[$orderId] = array(
                'od_id' => $orderId,
                'disp_od_id' => $data->sheets[0]['cells'][$i][2],
                'item' => $item,
                'count' => 0
            );
        }
        if (!isset($orderItem[$orderId])) $orderItem[$orderId] = array();
        if (!isset($orderItem[$orderId][$itemCode])) {
            $orderItem[$orderId][$itemCode] = array(
                'item' => $item,
                'count' => 1
            );
        } else {
            $orderItem[$orderId][$itemCode]['count']++;
        }

        $lastOrderId = $orderId;
    }

    if (!empty($orders)) {
        $excelData = array();

        foreach ($orders as $orderSet) {
            $sql = "SELECT * FROM {$g5['g5_shop_order_table']} WHERE od_id={$orderSet['od_id']}";
            $db_order = sql_query($sql);
            $order = sql_fetch_array($db_order);
            $item = array();
            foreach ($orderItem[$orderSet['od_id']] as $itemCode => $oi) $item[] = sprintf("%s - %s EA", $oi['item'], $oi['count']);

            $addr = sprintf("%s %s %s", $order['od_b_addr1'], $order['od_b_addr2'], $order['od_b_addr3']);
            $zipno = sprintf("%s%s", $order['od_b_zip1'], $order['od_b_zip2']);
            if ($order) {
                $data = array(1, date('Ymd'), $order['od_b_name'], $order['od_b_hp'], $order['od_b_tel'], $zipno, $addr, null, $orderSet['disp_od_id'], implode("/\n", $item), 1, 3, null, $order['od_memo'], null);
                $excelData[] = $data;
            }
        }

        include_once(G5_LIB_PATH . '/PHPExcel.php');

        $widths  = array(5, 12, 12, 15, 15, 12, 60, 12, 30, 60, 12, 12, 12, 12, 12, 12);
        $styleArray = array(
            'font'  => array(
                'color' => array('rgb' => '000000'),
                'size'  => 10,
                'name'  => 'Consolas'
            )
        );
        $excel = new PHPExcel();
        $excel->setActiveSheetIndex(0)->getStyle("A:O")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
        $excel->setActiveSheetIndex(0)->getStyle("A:O")->applyFromArray($styleArray);
        $excel->getActiveSheet()->fromArray($excelData, NULL, 'A1');
        foreach ($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension(column_char($i))->setWidth($w);

        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"CNPLUS-" . date("ymd") . ".xls\"");
        header("Cache-Control: max-age=0");

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $writer->save('php://output');
    }
}

// 택배 접수 필드 상세
/*
예약구분    선택    일반 1, 반품 2, 공란시 프로그램 선택값
집하예정일	선택    YYYYMMDD, 공란시 자동적용
받는분성명	필수
받는분전화번호	필수
받는분기타연락처	선택
받는분우편번호	선택    XXX-XXX, XXXXXX
받는분주소(전체, 분할)	필수
운송장번호	선택    선출력인경우, 기타 공란
고객주문번호	선택    주문번호
품목명	선택    MAX 150Bytes
박스수량	선택    1~999, 공란이면 1
박스타입    선택    극소 1, 소 2, 중 3, 대 4, 특대 5, 공란이면 프로그램 선택값
기본운임	선택    운임합계
배송메세지1	선택
배송메세지2	선택
*/

$g5['title'] = "CNPlus 변환기";
include_once('./admin.head.php');
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <form method="POST" enctype="multipart/form-data">
                <div class="tbl_frm01 tbl_wrap">
                    <table>
                        <colgroup>
                            <col class="grid_4">
                            <col>
                            <col class="grid_3">
                        </colgroup>
                        <tr>
                            <th scope="row" style="width:15%;">사용방법</th>
                            <td colspan="2">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <p>삼진전산 > 상품입출고 > 리탠다드 쇼핑몰 관련자료 > 집계시작 > 출고예정 내역 집계 > Excel</p>
                                    <p>출고 내역 Excel 내용을 확인하고 파일 선택 후 변환하시면 CNPlus 기업고객 일괄등록용 파일을 생성합니다</p>
                                    <p>* 삼진전산에서 Excel로 출력한 내용은 "C:\FSMANDIR\EXCEL_DATAS"에 자동저장 됩니다.</p>
                                    <p>* Excel 내용 수정후에는 반드시 "Excel 97 - 2003 통합 문서" 형식으로 저장해주세요</p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">출고예정 Excel</th>
                            <td colspan="2">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="file" name="samjin_excel" />
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <button type="submit">변환</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?
include_once('./admin.tail.php');
