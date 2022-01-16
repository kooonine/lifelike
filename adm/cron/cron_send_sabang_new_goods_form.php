
<?
$chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
$root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));

include_once($root_path . '/../../common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');


$send_mxl_sql = "select xml_name from sabang_new_goods_xml_history where status = 0 order by no desc limit 1";

$send_xml_name = sql_fetch($send_mxl_sql);


$url = 'http://a.sabangnet.co.kr/RTL_API/xml_goods_info.html?xml_url=https://lifelike.co.kr/adm/cron/sabang_new_goods/20210127102353_MWS20FC54701WHQ.xml';

$ch = cURL_init();

cURL_setopt($ch, CURLOPT_URL, $url);
cURL_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = cURL_exec($ch);

// var_dump($response); //결과값 출력
// echo '@<br>';

// print_r(curl_getinfo($ch)); //모든정보 출력
// echo '@<br>';
// echo curl_errno($ch); //에러정보 출력
// echo '@<br>';

// echo curl_error($ch); //에러정보 출력

cURL_close($ch); 


$object = simplexml_load_string($response);

if($object->children()->DATA->RESULT == 'SUCCESS'){
  echo 'asdfasdfasfd';
}else{
  
}

?>
