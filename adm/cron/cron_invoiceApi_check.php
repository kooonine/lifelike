<? #!/usr/local/php53/bin/php
$chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
$root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));

include_once($root_path . '/../../common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');


$outputs = array();
$outputs[] = date('Y-m-d H:i:s', time()) . " : cron_invoiceApi 시작  ";


$sql = " SELECT sno,tak_code, order_invoice FROM sabang_lt_order_form WHERE order_invoice IS NOT NULL AND invoice_up_dt >= date_add(now(), interval -4 day) AND invoice_check != 3 ";

// $sql = " SELECT sno,tak_code, order_invoice FROM sabang_lt_order_form WHERE (order_invoice IS NOT NULL AND order_invoice != '') AND invoice_check != 3 ";

// gglglglaglaemf

$resultSql = sql_query($sql);

for($i=0; $row=sql_fetch_array($resultSql); $i++) { 
  $invcCo = '';
  if ($row['tak_code'] == '003') {
      $invcCo = '04';
  } else if ($row['tak_code'] == '002') {
      $invcCo = '08';
  } else if ($row['tak_code'] == '001') { 
      $invcCo = '04';
  } else if ($row['tak_code'] == '004') { 
      $invcCo = '05';
  } else if ($row['tak_code'] == '005') { 
      $invcCo = '56';
  } else if ($row['tak_code'] == '006') { 
      $invcCo = '동부택배';
  } else if ($row['tak_code'] == '007') { 
      $invcCo = '06';
  } else if ($row['tak_code'] == '008') { 
      $invcCo = '옐로우캡택배';
  } else if ($row['tak_code'] == '009') { 
      $invcCo = '01';
  } else if ($row['tak_code'] == '010') { 
      $invcCo = '하나로택배';
  } else if ($row['tak_code'] == '013') { 
      $invcCo = '23';
  } else if ($row['tak_code'] == '014') { 
      $invcCo = '11';
  } else if ($row['tak_code'] == '016') { 
      $invcCo = '17';
  } else if ($row['tak_code'] == '017') { 
      $invcCo = '동부익스프레스';
  }

  $url = sprintf("%s?t_key=%s&t_code=%s&t_invoice=%s", $default['de_tracking_api'], $default['de_tracking_api_key'], $invcCo, $row['order_invoice']);
  // $url = sprintf("%s?t_key=%s&t_code=%s&t_invoice=%s", $default['de_tracking_api'], $default['de_tracking_api_key'], '04', '554457844274');

  $options = array(
      'http' => array(
          'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
          'method'  => 'GET',
          'content' => http_build_query($data)
      )
  );
  $context  = stream_context_create($options); // 데이터 가공
  $result = file_get_contents($url, false, $context); // 전송 ~ 결과값 반환
  
  $data = json_decode($result, true); 

  if (!$data['level'] && $data['level'] <'3') {
    sql_query("UPDATE sabang_lt_order_form SET invoice_check = '{$data['level']}' WHERE sno = {$row['sno']}");
    $outputs[] = date('Y-m-d H:i:s', time()) . " : level Down  :  ".$row['sno']." dataLevel  :". $data['level'];
  } else if (!$data['level'] && $data['level'] >'3') {
    sql_query("UPDATE sabang_lt_order_form SET invoice_check = 3 WHERE sno = {$row['sno']}");
    $outputs[] = date('Y-m-d H:i:s', time()) . " : level High  :  ".$row['sno']." dataLevel  :". $data['level'];
  } else {
    $outputs[] = date('Y-m-d H:i:s', time()) . " : level Pending  :  ".$row['sno']." dataLevel  :". $data['level'];
  }
}

$outputs[] = date('Y-m-d H:i:s', time()) . " : cron_invoiceApi 끝  ";
print_raw($outputs);
return;




