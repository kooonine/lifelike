<? #!/usr/local/php53/bin/php
$chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
$root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));

include_once($root_path . '/../../common.php');


$outputs = array();
$outputs[] = date('Y-m-d H:i:s', time()) . " : cron_point_disappear 시작  ";
$toDate = date("Y-m-d");
$afterDate= date("Y-m-d" , strtotime($toDate." 8 day"));

$pointSql = " SELECT * FROM lt_point WHERE po_point > 0 AND po_point != po_use_point AND po_expire_date > '$toDate' AND po_expire_date < '$afterDate' AND po_sms_check = 0 ";
$pointResult = sql_query($pointSql);
for ($pri = 0; $pr = sql_fetch_array($pointResult); $pri++) {
  
  $arr_change_data = array();
  $memberSql = sql_fetch(" SELECT mb_hp,mb_name ,COUNT(mb_name) AS CNT  FROM lt_member WHERE mb_id = '{$pr['mb_id']}' AND mb_leave_date = '' LIMIT 1 ");
  if ($memberSql['CNT'] > 0) {
    
    $kkoHp = str_replace('-','',$memberSql['mb_hp']);
    $disaPoint = $pr['po_point'] - $pr['po_use_point']; 
    $disaPoint = $pr['po_point'] - $pr['po_use_point']; 
    $disaPoint = $pr['po_point'] - $pr['po_use_point']; 
    $arr_change_data['고객명'] = $memberSql['mb_name'];
    $arr_change_data['소멸예정포인트'] = $disaPoint;
    $arr_change_data['소멸예정일'] = $pr['po_expire_date'];
    $arr_change_data['button'] = array(
      "type" => "웹링크",
      "txt" => "포인트 확인하기",
      "link" => "https://lifelike.co.kr/member/point.php"
    );
  
    sms_autosend('회원', '포인트소멸안내2', '', '', $kkoHp, $arr_change_data);
    sql_query(" UPDATE lt_point SET po_sms_check = 1 WHERE po_id = '{$pr['po_id']}' ");
    $outputs[] = date('Y-m-d H:i:s', time()) . " : po_id : ".$pr['po_id'] ;
  }
}



print_raw($outputs);
return;
