
<? #!/usr/local/php53/bin/php
$chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
$root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));

include_once($root_path . '/../../common.php');

$outputs = array();
$outputs[] = date('Y-m-d H:i:s', time()) . " : appPush 시작";
$sql = "SELECT * from lt_app_push_reservation WHERE (pr_status = 0 OR pr_status IS NULL) AND DATE_FORMAT(pr_push_date, '%Y-%m-%d %H:%i') = DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i')";
$result = sql_query($sql);
for ($i = 0; $pr = sql_fetch_array($result); $i++) {
    $outputs[] = date('Y-m-d H:i:s', time()) . " : appPush ID : ".$pr["pr_id"]; 
    $fields[] = array(
        'pushType' => 0,
        'msg_title' => $pr["pr_title"],
        'msg_body' => $pr["pr_body"],
        'msg_url' => $pr["pr_url"],
        'fcmId' => $pr["pr_mb_id"],
        'prType' => $pr["pr_type"],
        'prId' => $pr["pr_id"],
    );

};
if (count($fields) >0) {
    $dateCurl = json_encode($fields);
    $url = 'http://localhost/ajax_front/ajax.configform_app_push_send.php';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        $prStatus = 2;
        $outputs[] = date('Y-m-d H:i:s', time()) . " : 실패 ".$result;
    } else {
        $prStatus = 1;
        $outputs[] = date('Y-m-d H:i:s', time()) . " : 성공".$result;  
    } 
    curl_close($ch);    
}
print_raw($outputs);
?>

