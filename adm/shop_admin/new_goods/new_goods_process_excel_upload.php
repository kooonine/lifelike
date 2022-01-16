<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/PHPExcel.php');
include_once(G5_LIB_PATH . '/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

ini_set('memory_limit', '256M');


$referer = $_SERVER["HTTP_REFERER"];

$cut_url = explode("?" , $referer);


$qstr=$cut_url[1];


$file = $_FILES['upload_excel']['tmp_name'];

$data = new Spreadsheet_Excel_Reader();

// Set output Encoding.
$data->setOutputEncoding('UTF-8');
$data->read($file);

for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
    
  // 
  $companyId = $data->sheets[0]['cells'][$i][1];
  $priceOne = $data->sheets[0]['cells'][$i][2];
  $priceTwo = $data->sheets[0]['cells'][$i][3];

  $setValue = "";
  if ($priceOne && $priceOne != '') {
      $setValue .= " pi_sale_price = {$priceOne}";
  }

  if ($priceTwo && $priceTwo != '') {
    if ($setValue =="") $setValue .= " pi_sale_price2 = {$priceTwo}";
    else $setValue .= " , pi_sale_price2 = {$priceTwo}";
  }


  if (!$companyId || $companyId =='') { 
  } else {
    $selectSql = "SELECT count(*) AS CNT FROM lt_prod_info WHERE pi_company_it_id = '{$companyId}'";
    $lpi = sql_fetch($selectSql);
    $lpiCnt = $lpi['CNT'];
    if ($lpiCnt == 1) {
      if ($setValue != "") {
        $updateSql = "UPDATE lt_prod_info SET $setValue WHERE pi_company_it_id = '{$companyId}'";
        $res = sql_query($updateSql);
        echo '성공 : '.$companyId.'<br>';
      } else {
        echo '실패(판가확인) : '.$companyId.'<br>';
      }
    } else {
      echo '실패 : '.$companyId.'<br>';
    }
  }

}
?>
<br>
<button onclick="location.href='<?= G5_ADMIN_URL ?>/shop_admin/new_goods/new_goods_process.php?tabs=list&folds=up'">돌아가기</button>




