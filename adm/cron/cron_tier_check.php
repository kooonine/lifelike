<? #!/usr/local/php53/bin/php
$chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
$root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));

include_once($root_path . '/../../common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

$outputs = array();
$outputs[] = date('Y-m-d H:i:s', time()) . " : cron_tier_check 시작  ";

$oneYears = date('Y-m', strtotime('-12 months', G5_SERVER_TIME));
$eleMonths = date('Y-m', strtotime('-11 months', G5_SERVER_TIME));


// $selectMemberSql = "SELECT mb_id FROM lt_member WHERE mb_leave_date = '' AND mb_tier='COMFORT' ORDER BY mb_id DESC ";
$selectMemberSql = "SELECT mb_id,mb_tier_next FROM lt_member WHERE mb_leave_date = '' ";
// $selectMemberSql = "SELECT mb_id,mb_tier_next FROM lt_member WHERE mb_leave_date = '' AND mb_id = 'kootest' ";

$resultMember = sql_query($selectMemberSql);
for ($i = 0; $rm = sql_fetch_array($resultMember); $i++) {  
  $sumSql = "SELECT SUM(od_receipt_refund_price_ori) AS checkPrice  FROM lt_shop_order WHERE mb_id = '{$rm['mb_id']}' AND od_status ='구매확정' AND od_receipt_time > '$eleMonths' ";
  $sumRes = sql_fetch($sumSql);
  $checkPrice = $sumRes['checkPrice'];
  if (!$checkPrice) $checkPrice = 0;

  // $sumSql2 = "SELECT SUM(od_receipt_price-od_cancel_price-od_refund_price) AS checkPrice  FROM lt_shop_order WHERE mb_id = '{$rm['mb_id']}' AND od_status ='구매확정' AND od_receipt_time > '$eleMonths' ";
  // $sumRes2 = sql_fetch($sumSql2);
  // $checkPrice2 = $sumRes2['checkPrice'];
  // if (!$checkPrice2) $checkPrice2 = 0;

  // $cntSql = "SELECT count(*) AS checkCNT FROM lt_shop_order WHERE mb_id = '{$rm['mb_id']}' AND od_status ='구매확정' AND od_receipt_price-od_cancel_price-od_refund_price > 0 AND od_receipt_time > '$oneYears' ";
  // $cntRes = sql_fetch($cntSql);
  // $checkCNT = $cntRes['checkCNT'];

  $tierSql = "SELECT * FROM lt_member_rating ORDER BY mr_start_amount ASC";
  $tierRes = sql_query($tierSql);

  
  $tierSql2 = "SELECT * FROM lt_member_rating WHERE mr_rating = '{$rm['mb_tier_next']}' LIMIT 1 ";
  $tr2 = sql_fetch($tierSql2);

  $mr_rating ='';
  $couponProduct ='';
  $couponPlus ='';
  $couponCart ='';

  // $mr_rating = $rm['mb_tier_next'];
  $couponProduct = $tr2['mr_couponProductName'];
  $couponPlus = $tr2['mr_couponPlusName'];
  $couponCart = $tr2['mr_couponCartName'];

  for ($j = 0; $tr = sql_fetch_array($tierRes); $j++) {  
    // if ($tr['mr_start_count'] == 0 || $tr['mr_start_amount'] == 0) { 
    if ($tr['mr_start_amount'] == 0) { 
      $mr_rating = $tr['mr_rating'];
      // $couponProduct = $tr['mr_couponProductName'];
      // $couponPlus = $tr['mr_couponPlusName'];
      // $couponCart = $tr['mr_couponCartName'];
      continue;
    }
    // if ($tr['mr_start_count'] < $checkCNT && $tr['mr_start_amount'] < $checkPrice) {  
    if ($tr['mr_start_amount'] < $checkPrice) {  
      $mr_rating = $tr['mr_rating'];
      // $couponProduct = $tr['mr_couponProductName'];
      // $couponPlus = $tr['mr_couponPlusName'];
      // $couponCart = $tr['mr_couponCartName'];
      continue;
    }
  } 
  // $outputs[] = date('Y-m-d H:i:s', time()) . " : checkCNT  :  ".$checkCNT." checkPrice : ".$checkPrice." mr_rating : ".$mr_rating;
  $outputs[] = date('Y-m-d H:i:s', time()) . " mb_id : ".$rm['mb_id']." checkPrice : ".$checkPrice." mr_rating : ".$mr_rating;
  
  // 여기서 해당 쿠폰발행
  $updateSql = "UPDATE lt_member SET mb_tier = mb_tier_next, mb_tier_next = '$mr_rating', mb_tier_account = '$checkPrice' WHERE mb_id = '{$rm['mb_id']}' ";
  sql_query($updateSql);

  
  // $cpArr[0] = $couponProduct;
  // $cpArr[1] = $couponPlus;
  // $cpArr[2] = $couponCart;


  $couponProductArr = explode(',', $couponProduct);
  $cpArr = array();
 
  foreach($couponProductArr as $productName) {
    $cpArr[] = $productName;
  }
  $couponPlusArr = explode(',', $couponPlus);
  foreach($couponPlusArr as $plusName) { 
    $cpArr[] = $plusName;
  }
  $couponCartArr = explode(',', $couponCart);
  foreach($couponCartArr as $cartName) { 
    $cpArr[] = $cartName;
  }
  foreach ($cpArr as $coupon) {  
    if ($coupon && $coupon !='') {
      $productSql = "SELECT * FROM lt_shop_coupon_zone WHERE cz_subject ='$coupon'";
      $cp = sql_fetch($productSql);
  
      if (!$cp) continue;
  
      for ($ci = 1; $ci <= $cp['cz_download_user_limit']; $ci++) { 
        $j = 0;
        do {
            $cp_id = get_coupon_id();
    
            $sql3 = " select count(*) as cnt from {$g5['g5_shop_coupon_table']} where cp_id = '$cp_id' ";
            $row3 = sql_fetch($sql3);
    
            if (!$row3['cnt'])
                break;
            else {
                if ($j > 20)
                    die(json_encode(array('error' => 'Coupon ID Error')));
            }
            $j++;
        } while (1);
        $cp_start = G5_TIME_YMD;
        $period = $cp['cz_period'] - 1;
        if ($period < 0)
            $period = 0;
  
        $cp_end = date('t', strtotime(G5_TIME_YMD));
        $cp_end = date('Y-m').'-'.$cp_end;
        // $cp_end = date('Y-m-d', strtotime("+{$period} days", G5_SERVER_TIME));
        // comfort 쿠폰 제거 
        $cp_comfort_end = date('Y-m-d', strtotime("-1 days", G5_SERVER_TIME));
        sql_query(" UPDATE {$g5['g5_shop_coupon_table']} SET cp_end =  '$cp_comfort_end' WHERE cp_subject LIKE ('%COMFORT%') AND mb_id = '{$rm['mb_id']}' AND cp_start  < '$cp_start' " );

        $sql = " INSERT INTO {$g5['g5_shop_coupon_table']}
        ( cp_id, cp_subject, cp_desc, cp_method, cp_target, cz_id, cp_start, cp_end, cp_type, cp_price, cp_trunc, cp_minimum, cp_maximum, cp_datetime, cp_weekday, cp_week, mb_id )
        VALUES
        ( '$cp_id', '{$cp['cz_subject']}', '{$cp['cz_desc']}', '{$cp['cp_method']}', '{$cp['cp_target']}', '{$cp['cz_id']}', '$cp_start', '$cp_end', '{$cp['cp_type']}', '{$cp['cp_price']}', '{$cp['cp_trunc']}', '{$cp['cp_minimum']}', '{$cp['cp_maximum']}', '" . G5_TIME_YMDHIS . "', '{$cp['cz_weekday']}', '{$cp['cz_week']}', '{$rm['mb_id']}' ) ";
        
        $result = sql_query($sql);
        sql_query(" update {$g5['g5_shop_coupon_zone_table']} set cz_download = cz_download + 1 where cz_id = '{$cp['cz_id']}' ");
      }
    } 
  }
}

print_raw($outputs);
return;


