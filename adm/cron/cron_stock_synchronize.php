<? #!/usr/local/php53/bin/php
$chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
$root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));

include_once($root_path . '/../../common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

$outputs = array();
$outputs[] = date('Y-m-d H:i:s', time()) . " : cron_stock_synchronize 시작  ";
$toDate = date("YmdH");


$banSql = "SELECT it_id FROM lt_shop_item_stock_ben";
$banResult = sql_query($banSql);
$banId = '';
for ($bri = 0; $br = sql_fetch_array($banResult); $bri++) { 
  if ($bri == 0) $banId.= "'".$br['it_id']."'";
  else $banId.= ",'".$br['it_id']."'";
}

if ($banId && $banId != '') {
  $selesctSql = "SELECT lt_shop_item_option.* FROM lt_shop_item_option LEFT JOIN lt_shop_item ON lt_shop_item_option.it_id = lt_shop_item.it_id WHERE lt_shop_item_option.it_id NOT IN ($banId) ";
} else {
  $selesctSql = "SELECT lt_shop_item_option.* FROM lt_shop_item_option LEFT JOIN lt_shop_item ON lt_shop_item_option.it_id = lt_shop_item.it_id ";
}

$result = sql_query($selesctSql);
for ($ioi = 0; $io = sql_fetch_array($result); $ioi++) {  
  $outputs[] = date('Y-m-d H:i:s', time()) . " : start io_no : ".$io['io_no'] ;
  $io_stock_qty = 0;
  $stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(1,0,$io['io_order_no'],$io['io_color_name'],$io['io_hoching']);
  if (count($stockSamjin) == 0) {
    $outputs[] = date('Y-m-d H:i:s', time()) . " : fail OR sold_out io_no : ".$io['io_no'] ;
  } else {
    for ($j =0; $j < count($stockSamjin); $j++) {
      if ($stockSamjin[$j]['C_NO'] == 2 || $stockSamjin[$j]['C_NO'] == 3 || $stockSamjin[$j]['C_NO'] == 4 || $stockSamjin[$j]['C_NO'] == 8 || $stockWith[$j]['C_NO'] == 17 || $stockWith[$j]['C_NO'] == 45) {
        $io_stock_qty += $stockSamjin[$j]['STOCK2'];
      } 
    }
    $outputs[] = date('Y-m-d H:i:s', time()) . " : success io_no : ".$io['io_no']. " 재고수량 : ". $io_stock_qty;
  }
  if ($io_stock_qty < 3) $io_stock_qty =0;
  // $updateSql = "UPDATE lt_shop_item_option SET io_stock_qty = $io_stock_qty, io_noti_qty = $io_stock_qty WHERE io_no = {$io['io_no']}";
  $updateSql = "UPDATE lt_shop_item_option SET io_stock_qty = $io_stock_qty WHERE io_no = {$io['io_no']}";
  sql_query($updateSql);
  $updateSql2 = "UPDATE lt_shop_item SET it_stock_qty = $io_stock_qty WHERE it_id = {$io['it_id']}";
  sql_query($updateSql2);
}

print_raw($outputs);
return;
