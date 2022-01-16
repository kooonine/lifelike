<?php
$sub_menu = '92';
include_once('./_common.php');
include_once(G5_ADMIN_PATH.'/shop_admin/admin.shop.lib.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

define("_ORDERMAIL_", true);

$sms_count = 0;
$sms_messages = array();

if($_FILES['excelfile']['tmp_name']) {
    $file = $_FILES['excelfile']['tmp_name'];

    include_once(G5_LIB_PATH.'/Excel/reader.php');

    $data = new Spreadsheet_Excel_Reader();

    // Set output Encoding.
    $data->setOutputEncoding('UTF-8');

    /***
    * if you want you can change 'iconv' to mb_convert_encoding:
    * $data->setUTFEncoder('mb');
    *
    **/

    /***
    * By default rows & cols indeces start with 1
    * For change initial index use:
    * $data->setRowColOffset(0);
    *
    **/



    /***
    *  Some function for formatting output.
    * $data->setDefaultFormat('%.2f');
    * setDefaultFormat - set format for columns with unknown formatting
    *
    * $data->setColumnFormat(4, '%.3f');
    * setColumnFormat - set format for column (apply only to number fields)
    *
    **/

    $data->read($file);

    /*


     $data->sheets[0]['numRows'] - count rows
     $data->sheets[0]['numCols'] - count columns
     $data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

     $data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell

        $data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
            if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
        $data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format
        $data->sheets[0]['cellsInfo'][$i][$j]['colspan']
        $data->sheets[0]['cellsInfo'][$i][$j]['rowspan']
    */

    error_reporting(E_ALL ^ E_NOTICE);

    $fail_it_id = array();
    $total_count = 0;
    $fail_count = 0;
    $succ_count = 0;

    // $i 사용시 ordermail.inc.php의 $i 때문에 무한루프에 빠짐
    for ($k = 2; $k <= $data->sheets[0]['numRows']; $k++) {
        
        $it_id               = addslashes(trim($data->sheets[0]['cells'][$k][6]));
        $it_commission       = addslashes(trim($data->sheets[0]['cells'][$k][8]));
        
        if($it_id) {
            $total_count++;
        
            if(!$it_id || $it_commission == '') {
                $fail_count++;
                $fail_it_id[] = $it_id;
                continue;
            }
    
            // 상품정보
            $it = sql_fetch(" select * from lt_shop_item where it_id = '$it_id' ");
            if (!$it) {
                $fail_count++;
                $fail_it_id[] = $it_id;
                continue;
            }
            $result = sql_query("update lt_shop_item set it_commission = '$it_commission' where it_id = '$it_id' ");
            if (!$result) {
                $fail_count++;
                $fail_it_id[] = $it_id;
                continue;
            }
    
            $succ_count++;
        }
    }
}

$msg = "엑셀 일괄변경 처리를 완료했습니다.";

$msg .= "\\n 총건수:".number_format($total_count);
$msg .= "\\n 완료건수:".number_format($succ_count);
$msg .= "\\n 실패건수:".number_format($fail_count);
if($fail_count > 0) {
    $msg .= "\\n 실패상품코드:".implode(', ', $fail_it_id);
}

alert($msg);

?>