









<!-- <pre>
<script src="http://localhost/plugin/editor/ckeditor4/ckeditor.js"></script>
<script>var g5_editor_url = "http://localhost/plugin/editor/ckeditor4";</script>
<script src="http://localhost/plugin/editor/ckeditor4/config.js"></script>

<textarea id="sss" name="sss" class="ckeditor" maxlength="65536">abddc</textarea>
</pre> -->



<? #!/usr/local/php53/bin/php
// $chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
// $root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));
// $outputs = array();
// $outputs[] = date('Y-m-d H:i:s', time()) . " : 크론시작  ";
// include_once($root_path . '/../../common.php');
// include_once($root_path.'/_common.php');
// include_once(G5_LIB_PATH . '/samjin.lib.php');

include_once('./_common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');
// gdd

// 
function ftp_putAll($conn_id, $src_dir, $dst_dir) {
  echo 'testset : '.$src_dir.'<br>';
  $d = dir($src_dir);
  echo '$d : '.$d.'<br>';
  while($file = $d->read()) { // do this for each file in the directory
      if ($file != "." && $file != "..") { // to prevent an infinite loop
          if (is_dir($src_dir."/".$file)) { // do the following if it is a directory
              if (!@ftp_chdir($conn_id, $dst_dir."/".$file)) {
                  ftp_mkdir($conn_id, $dst_dir."/".$file); // create directories that do not yet exist
              }
              ftp_putAll($conn_id, $src_dir."/".$file, $dst_dir."/".$file); // recursive part
          } else {
              $upload = ftp_put($conn_id, $dst_dir."/".$file, $src_dir."/".$file, FTP_BINARY); // put the files  <== 45번째 줄
          }
      }
  }
  $d->close();
}






$ftp_server = "litandard-org.daouidc.com"; 
$ftp_port = 2021; 
$ftp_user_name = "litandard"; 
$ftp_user_pass = "flxosekem_ftp!@34"; 
$conn_id = ftp_connect($ftp_server,$ftp_port) or die("Couldn't connect to $ftp_server");
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<Br> Couldn't connect to $ftp_server"); 
ftp_pasv($conn_id, true);

// 이거 2개만 바꾸면
// $filepath = $_FILES['ba_image']['tmp_name'];
// $path = md5(microtime()) . '.' . $_FILES['ba_image']['name'];

echo 'login_result: '.$login_result.'<br>';
$filepath = '/test001';
// ftp_putAll;
// dlRJ QlTlqkf ??? ??? 
$upload = ftp_putAll($conn_id, $filepath, '/data/item/00700');
echo 'filepath: '.$filepath.'<br>';
echo 'test<br>';
echo $upload.'<br>';
// $path = 'https://lifelikecdn.co.kr/newbanner/'.$path;
if ($upload) {
    echo $upload.'<br>';
    $tpType = 1;
    
    // $tpNum = sql_fetch(" SELECT tp_num+1 AS tn FROM lt_temper WHERE tp_type = $tpType ORDER BY tp_num DESC LIMIT 1 ");
    // $sql = " INSERT INTO lt_temper (tp_img,tp_use,tp_num,tp_type) VALUES ('$path','$tp_use', '{$tpNum['tn']}',$tpType)";
    // sql_query($sql);
} else {
   
}



return;
// 
$a = 52;
if (true) {
  if ($a==5) {
    echo 'test001<Br>';
  }else {
    echo 'test002<Br>';
    return;
  }
  echo 'test003<Br>';
}
echo 'test004<Br>';


return;
$db_filters = sql_query($sql_filters22);





dd($db_filters);

return;
for ($j = 0; $j < 10; $j++) { 
$itemInfo = sql_fetch(" SELECT B.io_hoching , A.* FROM {$g5['g5_shop_item_table']} AS A LEFT JOIN lt_shop_item_option AS B ON (A.it_id = B.it_id) WHERE  A.it_use=1 AND B.io_use= 1 AND B.io_stock_qty > 0 LIMIT 1 ");
  if ($itemInfo) {
    echo 'j 값  : '.$j. '<br>';
    echo 'iteminfo 값  <br>';
    continue;
  }
  echo 'if문통과<br>';
}

dd('end');

$mergeCell = array();

$meger_st = "WQQQ". 1 . ":WQQ". 3;
array_push($mergeCell, $meger_st);
$meger_st = "YA". 2 . ":YA". 3;
array_push($mergeCell, $meger_st);       
// dd($mergeCell);
foreach ($mergeCell as $mi => $mc) {
  echo 'mi : ',$mi.'<Br>';
  echo 'mc : ',$mc.'<Br>';
  preg_match("/[^\:\.]+/", $mc,$cv);

  echo 'cv:'.$cv[0].'<br>';
  // echo 'test :'.substr($mc,0,2).'<br>';
}



return;
// $content = '최초리뷰작성(시베리아80 사계절 거위이불솜 +모드니 블루 이불베개커버SET 싱글사이즈(S))';
// w
// sftp://lifelike2@lifelike.co.kr/home/hosting_users/lifelike2/www/data/file/itemuse

// if (!is_mobile() || defined('G5_IS_MOBILE_DHTML_USE') && G5_IS_MOBILE_DHTML_USE) {
//   echo '모바일아님 <br>';
// } else {
//   echo '모바일임 <br>';
// }
sftp://lifelike2@lifelike.co.kr/home/hosting_users/lifelike2/www/data/file/itemuse/3739970033_BwQpvVOi_2e36034bd87b95022d9a3cebcd9339f83d8d3895.jpg
dd('ttt');

$dest_file = G5_DATA_PATH . '/file/itemuse/thumb-2887057409_a958VMIm_92dbbf1f7b82b0ceaed1e183be94d9d8fdb9fca8_500x500.png';
// dd($dest_file);
$ftp_server = "litandard-org.daouidc.com"; 
$ftp_port = 2021; 
$ftp_user_name = "litandard"; 
$ftp_user_pass = "flxosekem_ftp!@34"; 
$conn_id = ftp_connect($ftp_server,$ftp_port) or die("Couldn't connect to $ftp_server");
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<Br> Couldn't connect to $ftp_server"); 
ftp_pasv($conn_id, true);

$cdnFile = '/data/file/itemuse/thumb-2887057409_a958VMIm_92dbbf1f7b82b0ceaed1e183be94d9d8fdb9fca8_500x500.png';
$upload = ftp_put($conn_id, $cdnFile, $dest_file, FTP_BINARY);


echo 'testsetest';
return;


$filepath = G5_DATA_PATH . '/file/itemuse';
$file = '2887057409_z1yJHptN_378fc3663abace81478dda029fe721a22fabf14e.jpg';
// $test = thumbnail($file, $filepath, $filepath, 171, 173, false, false, 'center', false, $um_value = '80/0.5/3');
// dd($test);
// wh
// dk 

dd($filepath.$file);
// alclsrjdksldi 
// $dest_file = G5_DATA_PATH .'/file/itemuse/';
// $dest_file = 'https://lifelike.co.kr/data/file/itemuse/662173320_sqFrwoWm_f175fc2084a9616cd4fb5d0df2c90e79c330076f.jpg';

// $dest_file2 = G5_DATA_PATH .'/file/itemuse/lowsize/';
$thumb_file = '/var/www/html/data/file/itemuse/thumb-2887057409_HtvmlpXs_27199414b46562f624456a8f7738fb8d7bfd444a_172x172.jpg';
$fileGil = $thumb_file;
$dest_file2 = str_replace('/var/www/html','',$fileGil);
// dd($dest_file2);





// $filename = compress2($dest_file, $dest_file2, 20); 이건 안해도될듯
// 
$ftp_server = "litandard-org.daouidc.com"; 
$ftp_port = 2021; 
$ftp_user_name = "litandard"; 
$ftp_user_pass = "flxosekem_ftp!@34"; 
$conn_id = ftp_connect($ftp_server,$ftp_port) or die("Couldn't connect to $ftp_server");
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<Br> Couldn't connect to $ftp_server"); 
ftp_pasv($conn_id, true);

// $tpImg = sql_fetch(" SELECT tp_img FROM lt_temper WHERE tp_id = '$tp_id' ");
// // $tpImg['tp_img'] = str_replace('https://lifelikecdn.co.kr/','',$tpImg['tp_img']);
// // $ftpDel = ftp_delete($conn_id, $tpImg['tp_img']);
// // $filepath = $_FILES['ba_image']['tmp_name'];
// $filepath = $dest_file2;
// // $path = md5(microtime()) . '.' . $_FILES['ba_image']['name'];
$upload = ftp_put($conn_id, $dest_file2, $fileGil, FTP_BINARY);
// dd('dd');
dd($dest_file2);

$filepath = G5_DATA_PATH . '/file/itemuse';
$file = '2887057409_z1yJHptN_378fc3663abace81478dda029fe721a22fabf14e.jpg';
$test = thumbnail($file, $filepath, $filepath, 171, 173, false, false, 'center', false, $um_value = '80/0.5/3');
dd($test);




$dest_file = G5_DATA_PATH .'/file/itemuse/';
// $dest_file = 'https://lifelike.co.kr/data/file/itemuse/662173320_sqFrwoWm_f175fc2084a9616cd4fb5d0df2c90e79c330076f.jpg';

$dest_file2 = G5_DATA_PATH .'/file/itemuse/lowsize/' . $upload[$i]['file'];
// $dest_file2 = 'https://lifelike.co.kr/data/file/itemuse/lowsize/662173320_sqFrwoWm_f175fc2084a9616cd4fb5d0df2c90e79c330076f.jpg';


$filename = compress2($dest_file, $dest_file2, 20);
// 
$ftp_server = "litandard-org.daouidc.com"; 
$ftp_port = 2021; 
$ftp_user_name = "litandard"; 
$ftp_user_pass = "flxosekem_ftp!@34"; 
$conn_id = ftp_connect($ftp_server,$ftp_port) or die("Couldn't connect to $ftp_server");
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<Br> Couldn't connect to $ftp_server"); 
ftp_pasv($conn_id, true);

// $tpImg = sql_fetch(" SELECT tp_img FROM lt_temper WHERE tp_id = '$tp_id' ");
// // $tpImg['tp_img'] = str_replace('https://lifelikecdn.co.kr/','',$tpImg['tp_img']);
// // $ftpDel = ftp_delete($conn_id, $tpImg['tp_img']);
// // $filepath = $_FILES['ba_image']['tmp_name'];
$filepath = $dest_file2;
// // $path = md5(microtime()) . '.' . $_FILES['ba_image']['name'];
$upload = ftp_put($conn_id, '/data/file/itemuse/review/'.$upload[$i]['file'], $filepath, FTP_BINARY);

// 3
return;
// 1110 end



$point  = '15576';
$content = '주문번호 20210124000080 적립';
$relel ='20211008000115';
// vkxdl xmz fmfjq 
if(strpos($content, '주문번호') == 0) {
  echo '확인1<br>';
};
if(strpos($content, '적립') != false && strpos($content, '주문번호') == 0) { 
  echo '확인2<br>';
}


dd('end');
// $ckck = insert_point('kootest', 500, '주문번호 ' . $relel . ' 적립', '@order', $relel, G5_TIME_YMD);

dd($ckck);

echo strpos($content, '주문번호');
if(strpos($content, '적립') !== false && strpos($content, '주문번호') !== false) {
  echo '???Das';
  $odDate = sql_fetch(" SELECT od_receipt_time FROM lt_shop_order WHERE od_id ='20211008000115' LIMIT 1 ");
  if($odDate['od_receipt_time'] > '2021-10-01' && $odDate['od_receipt_time'] < '2021-11-01') {
    echo 'testset22: '.$odDate['od_receipt_time'].'<br>';
    if ($mb['mb_tier'] =='SIGNATURE') {
      echo 'tset1111!!!<br>';
      $point = floor($point * 2);
    } else {
      echo 'tset2222!!!<br>';
      $point = floor($point / 3) * 10;
    }
  }
}



// $odDate = sql_fetch(" SELECT od_receipt_time FROM lt_shop_order WHERE od_id ='20211008000115' LIMIT 1 ");
// echo $odDate['od_receipt_time'].'<br>';
// if($odDate['od_receipt_time'] > '2021-10-01' && $odDate['od_receipt_time'] < '2021-11-01') {
//   echo 'testset22: '.$odDate['od_receipt_time'].'<br>';
//   if ($mb['mb_tier'] =='SIGNATURE') {
//     $point = $point * 2;
//     echo 'tset111!!!<br>';
//   } else {
//     $point = ($point / 3) * 10;
//     echo 'tset2222!!!<br>';
//   }
// }

dd($point);


// 1022 start ------------------------------------------------------------------------------------------------------
if(strpos($content, '리뷰작성') !== false) {
  echo 'test1';
} else {
  
  echo 'test2';
}

return;
// 

$sql = " SELECT * FROM lt_shop_order ORDER BY od_id DESC LIMIT 10 ";
// $resultSql = sql_query($sql);
// echo 'dd : '.$resultSql;
// 
// tkfkddms ekfzhagkrp v
// $sql = " SELECT * FROM lt_point WHERE po_point > 0 AND po_point != po_use_point AND po_expire_date > '$toDate' AND po_expire_date < '$afterDate' AND po_sms_check = 0 ";
// d
echo ' rmaksgkfo<br> ';
echo ' test 1022<br> ';
echo ' ddddddddd<br> ';
echo ' epik ';

return;
// 1022 end ------------------------------------------------------------------------------------------------------
$toDate = date("Y-m-d");
$afterDate= date("Y-m-d" , strtotime($toDate." 7 day"));
$category = 10101020;

// zkss
dd(substr($category, 0, 8)); 


// rmfjslRlk sis bal 

echo '날짜 체크1  : '.$toDate.'<br>';
echo '날짜 체크2  : '.$afterDate;
//ㅇ
$sql = " SELECT * FROM lt_point WHERE po_point > 0 AND po_point != po_use_point AND po_expire_date > '$toDate' AND po_expire_date < '$afterDate' AND po_sms_check = 0 ";

return;


$content = '주문번호 20210723000017 적립';
preg_match_all("/[^() || \-\ \/\,\:\.]+/", $content,$pmaC);

$orderNum =	$pmaC[0][1];
$arr_change_data = array();
$orderNum = '2021101219432521';
// $cartSql = sql_query(" SELECT it_name FROM lt_shop_cart WHERE od_id = '$orderNum' AND ct_status = '구매확정' LIMIT 1 ");
$cartSql = sql_fetch(" SELECT it_name, COUNT(it_name) AS CNT FROM lt_shop_cart WHERE od_id = '$orderNum' LIMIT 1 ");


// ▶취소상품: 폴란드80 사계절 거위이불솜 +베스 이불베개커버SET 싱글사이즈(S)외 8건 
$goodsCnt = (int)$cartSql['CNT'] - 1;

if ($cartSql['CNT'] == 1) {
  $orderGoods = $cartSql['it_name'];
} else {
  $orderGoods = $cartSql['it_name'].'외 '. $goodsCnt.'건';
}

$arr_change_data['주문내역'] = $orderGoods;
$arr_change_data['주문번호'] = $orderNum;
$arr_change_data['적립포인트'] = $point;
$arr_change_data['button'] = array(
  "type" => "웹링크",
  "txt" => "포인트 조회",
  "link" => "https://lifelike.co.kr/member/point.php"
);

// // $kkoHp = str_replace('-','',$mb['mb_hp']);
$kkoHp = '01094476190';

sms_autosend('주문', '구매 확정', '', '', $kkoHp, $arr_change_data);
// ghekek ghekekr
echo 'test';
echo 'test111';
echo 'cj';

return;




$kkoHp = '01094476190';
$orderNum =	$pmaC[0][1];
$arr_change_data = array();
$arr_change_data['고객명'] = 'test001';
$arr_change_data['소멸예정포인트'] = 'test';
$arr_change_data['소멸예정일'] = 'test';
$arr_change_data['button'] = array(
  "type" => "웹링크",
  "txt" => "포인트 확인하기",
  "link" => "https://lifelike.co.kr/member/point.php"
);


  // $arr_change_data["브랜드"] = $ss['it_brand'];
  // $arr_change_data["상품명"] = $ss['it_name'];
sms_autosend('회원', '포인트소멸안내', '', '', $kkoHp, $arr_change_data);

echo 'test';

return;
// include_once(G5_EDITOR_LIB);

// dd(API_STORE_KEY);


// 카톡 test start ----------

// $template_code = 'B011';
// $param = array('template_code' => $template_code, 'status' => null);
// $response = Unirest::post(
//   "http://api.apistore.co.kr/kko/1/template/list/" . API_STORE_ID,
//   array(
//     "x-waple-authorization" => API_STORE_KEY
//   ),
//   $param
// );
// $body = get_object_vars($response->body);

// // dd($body);
// $template = $body['templateList'][0];
// $status = $template->status;
// $sf_title = $template->template_name;
// $msg = $template->templaㅣkte_msg;
// $msg = $template->template_msg;


// dd($msg);
// 카톡 test end ----------
// 할튼번호 0234947625


// dd('test!!!!');


$template_code = 'B011';

// $arr_change_data['button'] = array(
//   "type" => "웹링크",
//   "txt" => "상품 바로가기",
//   "link" => "https://lifelike.co.kr/shop/item.php?it_id=" . $it_id
// );


$msg_body = '[라이프라이크] 구매확정안내
고객님께서 주문하신 상품의 구매확정이 완료되었습니다.
* 더블적립 이벤트로 결제금액의 10%를 적립해드리고 있습니다.

▶주문상품: #{test상품}
▶주문번호: #{test번호}
▶적립포인트: #{test100}';

$param = array('phone' => '01094476190','CALLBACK' => '16613353','MSG ' => $msg_body,'template_code' => $template_code, 'FAILED_TYPE' => 'N', 'BTN_TYPES' => '웹링크', 'BTN_TXTS' => '포인트 조회', 'BTN_URLS1' => 'https://lifelike.co.kr/member/point.php');

$response = Unirest::post(
  "http://api.apistore.co.kr/kko/1/msg/" . API_STORE_ID,
  array(
    "x-waple-authorization" => API_STORE_KEY
  ),
  $param
);


$body = get_object_vars($response->body);

// cmid : 2021100716364534224701
// 

dd($body);

// end!!

// return;
// dd('tes');
$response = Unirest::post(
  "http://api.apistore.co.kr/kko/1/sendnumber/list/" . API_STORE_ID,
  array(
    "x-waple-authorization" => API_STORE_KEY
  )
  
);
$body = get_object_vars($response->body);
dd($body);
return;

$po_expire_date = date('Y-m-d', strtotime("30 days", G5_SERVER_TIME));
dd($po_expire_date);



$cp_start = G5_SERVER_TIME;
$cp_end = G5_SERVER_TIME;
$cp_end = date('t', strtotime(G5_TIME_YMD));
$cp_end = date('Y-m').'-'.$cp_end;

echo 'test:'.G5_TIME_YMD;
$cp_start = date('Y-m-d', G5_SERVER_TIME);
echo 'cp_start1차 : '.$cp_start;
// echo 'cp_start : '.G5_SERVER_TIME;
// $cp_start = date('Y-m-d', strtotime("1 day", G5_SERVER_TIME));
// $cp_end = date('Y-m-d', strtotime("-1 day", G5_SERVER_TIME));
// echo 'cp_start2 : '.$cp_start2;
echo '<br>';
echo 'cp_start : '.$cp_start;
echo '<br>';
echo 'cp_end : '.$cp_end;
echo '<br>';
return;
$sqlSoupon = sql_fetch(" SELECT * FROM {$g5['g5_shop_coupon_table']} WHERE cp_start='{$cp_start}' AND cp_end='{$cp_start}' AND (mb_id = NULL OR mb_id = '') ORDER BY cp_no ASC LIMIT 1 ");
// return;

if (!$sqlSoupon) {
    die(json_encode(array('error' => '쿠폰 발급이 끝났습니다')));
} else {
    sql_query(" UPDATE {$g5['g5_shop_coupon_table']} SET mb_id ='' WHERE cp_no = '{$sqlSoupon['cp_no']}' ");
    die(json_encode(array('success' => '쿠폰이 발급되었습니다.')));
}

return;



dd($sqlSoupon);



// ㅇ
return;
// $test = 102020;
// dd(strlen($test));
$db_it_id['it_id'] = "000001";
// '0000001';
$category = '102010';
$category2 = '10201010';
$category3 = '10102010';
$tmp_it_id = sprintf("%03d%03d%03d%s", substr($category, 0, 2), substr($category, 2, 2), substr($category, 4, 2), $db_it_id['it_id']);
$tmp_it_id2 = sprintf("%03d%03d%03d%s", substr($category2, 0, 2), substr($category2, 2, 2), substr($category2, 4, 2), $db_it_id['it_id']);
$tmp_it_id3 = sprintf("%03d%03d%03d%s", substr($category3, 2, 2), substr($category3, 4, 2), substr($category3, 6, 2), $db_it_id['it_id']);


// 
// dk xhgkfrjrkxek ssi bal sjanwhffudy ! ! 

// $tmp_it_id2 = sprintf("%02d%02d%02d%s", substr($category, 0, 2), substr($category, 2, 2), substr($category, 4, 2), $db_it_id['it_id']);

echo '수정값1 : '.$tmp_it_id.'<br>';
echo '수정값2 : '.$tmp_it_id2.'<br>';
echo '수정값3 : '.$tmp_it_id3.'<br>';
echo '수정값? : '.$tmp_it_id2.'<br>';


// 
dd($tmp_it_id);
// ㅇ
// d

return;

$content = file_get_contents('http://www.melon.com/genre/song_list.htm?gnrCode=GN0200'); 
if ($content !== false) { 
  // content 사용 
  // echo 'test : '.$content;
  // whyhy a
  preg_match_all('/input type="checkbox"(.*?)곡 선택"/is', $content, $html);
} else { 
  // error 발생 
}
$i = 1;
foreach($html[0] as $a) { 
  $a = str_replace('input type="checkbox" title="','',$a);
  $a = str_replace(' 곡 선택"','',$a);
  if($i != 1) echo ($i-1).'위 '.$a.'<br>';
  $i++;
}


// preg_match('/<div id="my2">(.*?)<\/div>/is', $contents, $html);

// dd($html);
// if ($fp = fopen('http://www.melon.com/', 'r')) { 
//   $content = ''; // 전부 읽을때까지 계속 읽음
//    while ($line = fread($fp, 1024)) { 
//      echo 'test : '.$line;
//      $content .= $line; 
//    }
// } else { 

// }

// echo ($content);

return;
$content = '주문번호 20210901000034 적립';
$rel_id = '20210901000034';
$point = '5050505050';
if(strpos($content, '적립') !== false && strpos($content, '주문번호') !== false) {
  $odDate = sql_fetch(" SELECT od_receipt_time FROM lt_shop_order WHERE od_id ='$rel_id' LIMIT 1 ");
  if($odDate['od_receipt_time'] < '2021-09-19' && $odDate['od_receipt_time'] > '2021-05-06') {
    $point = $point * 3;
  }
}

// $point = $point * 2;
dd($point);




// $filepath = "http://nsimg.sabangnet.co.kr/product_image2/mw67645/103/103128_1.jpg";
$ftp_server = "litandard-org.daouidc.com"; 
// $ftp_server = "192.168.60.129"; 
$ftp_port = 2021; 
$ftp_user_name = "litandard"; 
$ftp_user_pass = "flxosekem_ftp!@34"; 
// $ftp_user_name = "ftpkoo"; 
// $ftp_user_pass = "1596"; 
$conn_id = ftp_connect($ftp_server,$ftp_port) or die("Couldn't connect to $ftp_server");

// // die();

$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<Br> Couldn't connect to $ftp_server"); 
ftp_pasv($conn_id, true);
// echo '<br>';
// echo ($login_result);
// echo '<br>';


// // dd($login_result);
// // dd($login_result);
// $ftp_dir = ftp_pwd($conn_id);
// echo '<br> ftp_dir :';
// echo ($ftp_dir);
// echo '<br>';
// $a = ftp_mkdir($conn_id, "0512");
// $b = ftp_chmod($conn_id, 0777, '0512');
// echo '<br> a : ';
// echo ($a);
// echo '<br>';
// echo '<br> b : ';
// echo ($b);
// echo '<br>';
// 


$file = fopen("0512/test2.jpg","w");
$filepath = "http://nsimg.sabangnet.co.kr/product_image2/mw67645/103/103128_1.jpg";
$upload = ftp_put($conn_id, '/0512/ttt6.jpg', $filepath, FTP_BINARY);
echo ($upload);
// ㅋㅋㅋㅋㅋㅋㅋ
return;



dd('tt');
// $row = sql_fetch(" select password('kootest1') as pass ");
// dd($row);
// return $row['pass'];

// for( $i = 0; $i < 999; $i ++ ) {

//   $row = sql_fetch(" select password('$i') as pass ");

//   if( '*3D58518D1719ED9C13046789E18B889D709E14E9' == $row['pass'] ) {
//          echo " find ok : $i ";
//          break;
//   }
// }

// insert_point('kang6226', 3000, '품절보상포인트지급', '@soldOutPoint', 'kang6226','20210622000041',100);    

// dd('ttt');
// dd(get_encrypt_string('koo12345!'));
// dd(check_password('koo12345!', '*E86512B92C863687F171222C5BE73AF5B7039343'));

// MDS21SS08D12WHK

$stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(1,0,'MDS21SS08D12','WH','K');
dd($stockSamjin);

return;
$option = str_replace(" ","","구성 - 4. 프로즌 삼각쿠션 냉감커버(-29,000원) / 색상 - 그레이(-29,000원) / 사이즈 - 커버단품(-29,000원)");

set_mapping_item('19940' , $option , '소프라움 프로즌 냉감 패드세트 (냉감패드+냉감베개커버)' , '23665' ,'100018164' ,'100018164' );

function set_mapping_item( $mall , $opt , $name , $ov_id, $sabang_goods_cd, $mall_goods_cd){
    if($mall == '15001'){
      $map_sql = "SELECT GROUP_CONCAT(company_goods_cd SEPARATOR ' \n') AS set_sku_value
          FROM sabang_set_code_mapping 
        WHERE REPLACE(sku_value,' ','') LIKE '%{$opt}%'
        AND set_name = '{$name}'
        AND mall_code = '$mall'
        AND set_code = (SELECT  MAX(set_code) 
          FROM sabang_set_code_mapping 
        
          WHERE  set_name = '{$name}'
          AND REPLACE(sku_value,' ','') LIKE '%{$opt}%'
          AND mall_code = '$mall') LIMIT 1 ";
      $map_result = sql_fetch($map_sql); 
      if(!empty($map_result['set_sku_value'])){
        $new_opt = $opt.' \n'.$map_result['set_sku_value'];
      
        $up_set_opt = "UPDATE sabang_lt_order_view set ov_options = '{$new_opt}' , samjin_link_check = 0   where slov_id = '$ov_id'";
        sql_query($up_set_opt);    
      }
    }else if($mall == '19952'){
      $map_sql = "SELECT GROUP_CONCAT(company_goods_cd SEPARATOR ' \n') AS set_sku_value
                  FROM sabang_set_code_mapping 
                  WHERE set_name LIKE '%{$name}%'
                  AND (sabang_goods_cd = '{$sabang_goods_cd}'  OR mall_goods_cd = '{$mall_goods_cd}' )
                  AND mall_code = '$mall'
                  AND set_code = (SELECT  MAX(set_code) 
                  FROM sabang_set_code_mapping 
  
                  WHERE set_name LIKE '%{$name}%'
                  AND (sabang_goods_cd = '{$sabang_goods_cd}'  OR mall_goods_cd = '{$mall_goods_cd}' )
                  AND mall_code = '$mall' ORDER BY   CASE  WHEN set_code LIKE '%SET%' THEN CAST(SUBSTR(set_code , 4) AS UNSIGNED) WHEN set_code LIKE '%SINGLE%' THEN CAST(SUBSTR(set_code , 7) AS UNSIGNED) END DESC ) LIMIT 1 ";
      $map_result = sql_fetch($map_sql); 
      if(!empty($map_result['set_sku_value'])){
        $new_opt = $opt.' \n'.$map_result['set_sku_value'];
      
        $up_set_opt = "UPDATE sabang_lt_order_view set ov_options = '{$new_opt}' , samjin_link_check = 0   where slov_id = '$ov_id'";
        sql_query($up_set_opt);    
      }
    }else{
      // anrnrryahdla ?? ? 
      $map_sql = "SELECT GROUP_CONCAT(company_goods_cd SEPARATOR ' \n') AS set_sku_value
      FROM sabang_set_code_mapping 
      WHERE REPLACE(sku_value,' ','') LIKE '%{$opt}%'
      AND (sabang_goods_cd LIKE '%{$mall_goods_cd}%'  OR mall_goods_cd LIKE '%{$mall_goods_cd}%' )
      AND mall_code = '$mall'
      AND set_code = (
        SELECT set_code  FROM (
        SELECT  *
        FROM sabang_set_code_mapping 
      
        WHERE REPLACE(sku_value,' ','') LIKE '%{$opt}%'
        AND (sabang_goods_cd LIKE '%{$mall_goods_cd}%'  OR mall_goods_cd LIKE '%{$mall_goods_cd}%' )
        AND mall_code = '$mall' 
        UNION 
        SELECT  *
        FROM sabang_set_code_mapping 
      
        WHERE REPLACE(sku_value,' ','') LIKE '%{$opt}%'
        AND sabang_goods_cd = '{$sabang_goods_cd}'
        AND mall_code = '$mall' 
        ) AS map_u
         ORDER BY   CASE  WHEN map_u.set_code LIKE '%SET%' THEN CAST(SUBSTR(map_u.set_code , 4) AS UNSIGNED)
         WHEN map_u.set_code LIKE '%SINGLE%' THEN CAST(SUBSTR(map_u.set_code , 7) AS UNSIGNED) END DESC LIMIT 1
      )";
      $map_result = sql_fetch($map_sql); 
      dd($map_sql);
      if(!empty($map_result['set_sku_value'])){
        $new_opt = $opt.' \n'.$map_result['set_sku_value'];
      
        $up_set_opt = "UPDATE sabang_lt_order_view set ov_options = '{$new_opt}' , samjin_link_check = 0   where slov_id = '$ov_id'";
        sql_query($up_set_opt);    
      }
    }
  }

  
  return;










$sql = "SELECT * FROM sabang_lt_order_view WHERE samjin_link_check=0 AND ov_mall_name ='카카오메이커스'";
$result = sql_query($sql);
$mappingSuccessKey = '';
for ($i = 0; $row = sql_fetch_array($result); $i++) { 
  $outputs[] = date('Y-m-d H:i:s', time()) . " : 삼진 링크 시작 ".$row['slov_id'] .",".$row['ov_options'];
  if ($row['order_from'] == 1) { 
    if ($row['ov_mall_id'] == '19968' && $row['ov_mall_code'] =='MOS20AS30D11WHQ') {
        $row['ov_options'] = 'MOS20AS30D11WHQ';
    }
    preg_match_all("/[^() || \-\ \/\,\:\.]+/", preg_replace("/[^a-zA-Z0-9 (),\/\*.+-]/", "", preg_replace('/\n/','',$row['ov_options'])) ,$c);
    
    // preg_match_all("/[^() || \-\ \/\,]+/", preg_replace('/\n/','',$row['ov_options']),$c);
    // preg_match_all("/[^() || \-\ \/\,]+/", $row['ov_options'],$c);
    $optPlus = 0;
    $box_ex = 0;

    $optionsArr = array();
    foreach($c[0] as $opc) {
      if (strlen($opc) > 14) {
        if(substr($opc, 0, 1)=='M' || substr($opc, 0, 1)=='Z'){
          if (strpos($opc,'+')!== false) {
              preg_match_all("/[^ \+\,]+/", $opc, $opcPlus);
              for($i=0; $i<$opcPlus[0][1]; $i++) {
                array_push($optionsArr,  $opcPlus[0][0]);
              }
          } else {
            array_push($optionsArr,  $opc);
          }
        }
      }
    }

    foreach($optionsArr as $a) {
      if (strlen($a) > 14) {
        if(substr($a, 0, 1)=='M' || substr($a, 0, 1)=='Z'){
          $box_ex += 1;
          $sapCode12 = substr($a, 0, 12);
          $color = substr($a, 12, 2);
          $size = substr($a, 14);
          $strSize = array("x","X");  
          $size = str_replace($strSize,'*', $size);

          $newSlov_id = $row['slov_id'];

          $connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
          $g5['connect_samjindb'] = $connect_db;
          $sqlSamjin = "SELECT ORDER_NO, SAP_CODE, ITEM FROM S_MALL_ORDERS WHERE SAP_CODE = '{$sapCode12}' AND COLOR = '$color' AND HOCHING = '$size'";
          $rsSamjin = mssql_sql_query($sqlSamjin);
        
        
          $ov_samjin_name = '';
          $ov_samjin_code = '';
          $ov_sap_code = '';


          for ($k = 0; $samrow = mssql_sql_fetch_array($rsSamjin); $k++) {  
            $ov_samjin_name = $samrow['ITEM'];
            $ov_samjin_code = $samrow['ORDER_NO'];
            $ov_sap_code = $samrow['SAP_CODE'];
          }
          $ov_options_modify = $sapCode12.$color.$size;
          if ($ov_samjin_code=='') {
            $updateSql = "UPDATE sabang_lt_order_view SET ov_options_modify = '', samjin_link_check = 2
            WHERE slov_id = {$row['slov_id']} OR sub_slov_id = {$row['slov_id']}";
            sql_query($updateSql); 
            $outputs[] = date('Y-m-d H:i:s', time()) . " : 삼진에 없는 상품입니다 ".$row['slov_id'] .",".$row['ov_options'];
          } else {
            $optPlus += 1;
            if ($box_ex > 1) {
              $insertSql = "INSERT INTO sabang_lt_order_view(sub_slov_id,slod_id,samjin_link_check,order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_mall_id,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,ov_sap_code,ov_mall_code,ov_sabang_code,ov_it_name,ov_brand,ov_options,ov_distribution_status,ov_qty,ov_total_cost,ov_pay_cost,ov_delv_cost,ov_order_msg,ov_invoice_no,ov_delivery_company,ov_delivery_company_code,ov_sms_check,ov_ct_id,ov_sum_sno,ov_register_datetime,ov_collection_degress,ov_MALL_PRODUCT_ID) SELECT slov_id,slod_id,samjin_link_check,order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_mall_id,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,ov_sap_code,ov_mall_code,ov_sabang_code,ov_it_name,ov_brand,ov_options,ov_distribution_status,ov_qty,ov_total_cost,ov_pay_cost,ov_delv_cost,ov_order_msg,ov_invoice_no,ov_delivery_company,ov_delivery_company_code,ov_sms_check,ov_ct_id,ov_sum_sno,ov_register_datetime,ov_collection_degress,ov_MALL_PRODUCT_ID FROM sabang_lt_order_view WHERE slov_id = {$row['slov_id']}";
              $res = sql_query($insertSql);
              if ($res) $newSlov_id = sql_insert_id();
            }
            $ov_stock1 = 0;
            $ov_stock2 = 0;
            $stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(1,0,$sapCode12,$color,$size);
            if (count($stockSamjin) == 0) {
            } else {
              for ($j =0; $j < count($stockSamjin); $j++) {
                if ($stockSamjin[$j]['C_NO'] == 30 || $stockSamjin[$j]['C_NO'] == 31 || $stockSamjin[$j]['C_NO'] == 33 || $stockSamjin[$j]['C_NO'] == 34) {
                  $ov_stock1 += $stockSamjin[$j]['STOCK2'];
                } else if ($stockSamjin[$j]['C_NO'] == 2 || $stockSamjin[$j]['C_NO'] == 3 || $stockSamjin[$j]['C_NO'] == 4) {
                  $ov_stock2 += $stockSamjin[$j]['STOCK2'];
                }
              }
            }
            $fromQty = 1;
            $sapCodeBrand = substr($ov_options_modify, 2, 1);
            if ($sapCodeBrand =='D' || $sapCodeBrand =='d') {
              $sapCodeCheck = substr($ov_options_modify, 9, 3);
                if ($sapCodeCheck == '201' || $sapCodeCheck == '202' || $sapCodeCheck == '203') {
                    $fromQty = 2;
                }
            }
            preg_match_all("/[^() || , -]+/", $row['ov_order_id'],$orderPreg);
            $row['ov_order_id'] = $orderPreg[0][0];
            $ov_distribution_status = null;
            if ($ov_stock1 != 0 && $ov_stock1 >= (int)$row['ov_qty'] * $fromQty) {
              $ov_dpartner = '경민실업';
              $ov_delivery_company = 'CJ대한통운';
              $ov_delivery_company_code = '003';
            } else if ($ov_stock2 != 0 && $ov_stock2 >= (int)$row['ov_qty'] * $fromQty) {
              $ov_dpartner = '어시스트';
              $ov_delivery_company = '롯데택배';
              $ov_delivery_company_code = '002';
            } else {
              $ov_distribution_status = '품절';
              $updateSql = "UPDATE sabang_lt_order_view SET ov_distribution_status = '$ov_distribution_status'  WHERE (slov_id = '{$row['slov_id']}' OR sub_slov_id = '{$row['slov_id']}' OR ov_order_id LIKE ('{$row['ov_order_id']}%')) AND ov_mall_id ='{$row['ov_mall_id']}' AND ov_order_name ='{$row['ov_order_name']}'";
              sql_query($updateSql); 
              $ov_dpartner = null;
              $ov_delivery_company = null;
              $ov_delivery_company_code = null;
            }
            $selectSql = "SELECT count(*) AS CNT FROM sabang_lt_order_view WHERE (slov_id = '{$row['slov_id']}' OR sub_slov_id = '{$row['slov_id']}' OR ov_order_id LIKE ('{$row['ov_order_id']}%')) AND ov_mall_id ='{$row['ov_mall_id']}' AND ov_order_name ='{$row['ov_order_name']}' AND ov_distribution_status = '품절'";
            $soldOut = sql_fetch($selectSql);
            $soldOutCnt = $soldOut['CNT'];
            if ($soldOutCnt > 0) {
              $ov_distribution_status = '품절';
            } 

            $updateSql = "UPDATE sabang_lt_order_view SET samjin_link_check = 1, ov_samjin_name = '$ov_samjin_name', ov_samjin_code = '$ov_samjin_code', ov_dpartner = '$ov_dpartner', 
                          ov_stock1 = '$ov_stock1', ov_stock2 = '$ov_stock2', ov_color = '$color', ov_size = '$size', ov_sap_code = '$ov_sap_code', ov_delivery_company = '$ov_delivery_company', ov_delivery_company_code = '$ov_delivery_company_code',
                          ov_options_modify = '$ov_options_modify', ov_distribution_status = '$ov_distribution_status', ov_qty_form = ov_qty * $fromQty
                          WHERE slov_id = '$newSlov_id'";
            sql_query($updateSql); 
            
            $selectSql = "SELECT COUNT(*) AS cnt FROM sabang_lt_order_view WHERE sub_slov_id = '{$row['slov_id']}'";
            $set = sql_fetch($selectSql);
            if ($set['cnt'] >0) {
              $setCheck = '002';
              $updateSql = "UPDATE sabang_lt_order_view SET ov_set_check = '002'  WHERE (slov_id = '{$row['slov_id']}' OR sub_slov_id = '{$row['slov_id']}')";
              sql_query($updateSql);
            }

            $selectSql = "SELECT IFNULL(MAX(ov_sum_sno+1), 1) AS ov_sum_sno FROM sabang_lt_order_view WHERE ov_order_id LIKE ('{$row['ov_order_id']}%') AND ov_mall_id ='{$row['ov_mall_id']}' AND ov_order_name ='{$row['ov_order_name']}' AND ov_dpartner = '$ov_dpartner' ORDER BY ov_sum_sno DESC LIMIT 1";
            $sum_sno = sql_fetch($selectSql);
            $ov_sum_sno = $sum_sno['ov_sum_sno'];
            $updateSql = "UPDATE sabang_lt_order_view SET ov_sum_sno = $ov_sum_sno  WHERE ov_order_id LIKE ('{$row['ov_order_id']}%') AND ov_mall_id ='{$row['ov_mall_id']}' AND ov_order_name ='{$row['ov_order_name']}' AND ov_dpartner = '$ov_dpartner'";
            sql_query($updateSql); 
            $outputs[] = date('Y-m-d H:i:s', time()) . " : 삼진 링크 성공 ".$row['slov_id'] .",".$row['ov_options'];
          }
        }
      }
    }
    if($optPlus == 1){
      $outputs[] = date('Y-m-d H:i:s', time()) . " : 프리시저 시작 ".$row['slov_id'] .",".$row['ov_options'];
      if($mappingSuccessKey == '') $mappingSuccessKey .= $row['slov_id'];
      else $mappingSuccessKey .= ','.$row['slov_id'];
      //프로시져
      $resultSql = "SELECT ov_samjin_code , ov_mall_code , ov_color, ov_size , ov_sabang_code  FROM sabang_lt_order_view WHERE slov_id = '{$row['slov_id']}' LIMIT 1 "; 
      $result_stock = sql_fetch($resultSql);
      
      $BARCODE = $result_stock['ov_mall_code'];
      $ORDER_NO = $result_stock['ov_samjin_code'];
      $COLOR = $result_stock['ov_color'];
      $HOCHING = $result_stock['ov_size'];
      $SABANGCODE =  $result_stock['ov_sabang_code'];
      //삼진바코드
      $barcode_add = NM_ADD_BARCODE($BARCODE,$ORDER_NO,$COLOR,$HOCHING);
      $add_result_no = $barcode_add[0]['V1'];
      $add_result_meg = $barcode_add[0]['RSLT'];
      
      $barcodeSql = "UPDATE sabang_send_goods_list SET samjin_barcode_no = '{$add_result_no}', samjin_barcode_meg = '{$add_result_meg}'
                      WHERE sabang_goods_cd = '{$SABANGCODE}'
                      ";
      sql_query($barcodeSql);
    }

  } 
}
// $outputs[] = date('Y-m-d H:i:s', time()) . " : 프리시저 key ".$mappingSuccessKey;
// if($mappingSuccessKey != '') $sabang_send_stock_set =  sql_query('CALL sabang_send_stock_set("'.$mappingSuccessKey.'")');
print_raw($outputs);
return;



















// 구성-1.프로즌냉감패드+베개커버세트(60,000원)/색상-그레이(60,000원)/사이즈-K(180x210)(60,000원)
// $option = str_replace(" ","",'구성 - 1. 프로즌 냉감 패드+베개커버세트(60,000원) / 색상 - 그레이(60,000원) / 사이즈 - K (180x210)(60,000원)');
// dd($option);
// dd('test');
//  echo (editor_html('sss', 'abc'));

// MWS21HC03111GRQ

// MSS21SC28401BEQ
// MSS21SC21201BL50*70
// MSS21SC10111PKS

// MAS21HC16101GRS,MAS21HC16201GRS
// dd ('<textarea id="test" name="test" style=\"width:100%;\" maxlength=\"65536\">zzzz</textarea>');

// NM_GET_STOCK_WITH_SAP_CODE($M1, $C_NO, $SAP_CODE , $COLOR, $HOCHING);
// MAS21HC16101GRS
// $stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(1,0,'MAS21HC16101','GR','');

// preg_match_all("/[^() || \-\ \/\,\:\.]+/", preg_replace("/[^a-zA-Z0-9 (),\/\*.+-]/", "", preg_replace('/\n/','','MAS21HC16101GRS,MAS21HC16201GRS')) ,$c);
// dd($c);
// MDS21SC16204GR50X70

// MDS21SC16401GRQ
// MDS21SC16204GR50X70+2
// MDS21SC16401GRQ 
// MDS21SC16204GR50X70+2
// MOS20AS57T15GRSS
$qw = "구성-1.프로즌냉감패드+베개커버세트(50,000원)/색상-그레이(50,000원)/사이즈-Q(155x210)(50,000원) 
MDS21SC16401GRQ 
MDS21SC16204GR50X70+2";

// 된거
"구성-1.프로즌냉감패드+베개커버세트(50,000원)/색상-화이트(50,000원)/사이즈-Q(155x210)(50,000원) 
MDS21SC15401WHQ 
MDS21SC15204WH50X70+2";

// 안된거
"구성-1.프로즌냉감패드+베개커버세트/색상-화이트/사이즈-SS(115x210) 
MDS21SC15401WHSS 
MDS21SC15204WH50X70";

// $connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
// $g5['connect_samjindb'] = $connect_db;
// $sqlSamjin = "SELECT ORDER_NO, SAP_CODE, ITEM FROM S_MALL_ORDERS WHERE SAP_CODE = 'MDS21SC15204' AND COLOR = 'WH' AND HOCHING = '50*70'";
// $rsSamjin = mssql_sql_query($sqlSamjin);

// $ov_samjin_name = '';
// $ov_samjin_code = '';
// $ov_sap_code = '';


// for ($k = 0; $samrow = mssql_sql_fetch_array($rsSamjin); $k++) {  
//     dd($samrow);
//   $ov_samjin_name = $samrow['ITEM'];
//   $ov_samjin_code = $samrow['ORDER_NO'];
//   $ov_sap_code = $samrow['SAP_CODE'];
// }

// 4363062 안된거
// return;

$content = '주문번호 20210723000017 적립';
preg_match_all("/ /", $content,$pmaC);
dd($pmaC);




$qq = "SELECT * FROM sabang_lt_order_view WHERE ov_IDX ='4363080'";
$qqq = sql_fetch($qq);
// dd($qqq['ov_options']);

preg_match_all("/[^() || \-\ \/\,\:\.]+/", preg_replace("/[^a-zA-Z0-9 (),\/\*.+-]/", "", preg_replace('/\n/','',$qqq['ov_options'])) ,$d);
preg_match_all("/[^() || \-\ \/\,]+/", $qqq['ov_options'],$c);

// 구성-1.구스토퍼싱글(110x200) 
// MOS20AS57T15GRSS
// echo $c;
dd($c);

$qq = "SELECT * FROM sabang_lt_order_view WHERE ov_IDX ='4363080'";
$qqq = sql_fetch($qq);
dd($qqq['ov_options']);

if ($buttonType == 'mapping') {
    $mappingCheck = 0;
    $mappingFailId = NULL;
    $mappingSuccessId = NULL;
    $mappingSuccessKey = '';

    foreach($mappingOpt as $key=>$value) {
        preg_match_all("/[^() || \-\ \/\,]+/", $value,$c);
        $optPlus = 0;
        $box_ex = 0;

        $optionsArr = array();
        foreach($c[0] as $opc) {
            if (strlen($opc) > 14) {
                if(substr($opc, 0, 1)=='M' || substr($opc, 0, 1)=='Z'){
                    if (strpos($opc,'+')!== false) {
                        preg_match_all("/[^ \+\,]+/", $opc, $opcPlus);
                        for($i=0; $i<$opcPlus[0][1]; $i++) {
                            array_push($optionsArr,  $opcPlus[0][0]);
                        }
                    } else {
                        array_push($optionsArr,  $opc);
                    }
                }
            }
        }


        foreach($optionsArr as $a) {
            if (strlen($a) > 14) {
                if(substr($a, 0, 1)=='M' || substr($a, 0, 1)=='Z'){
                    $mappingCheck = 1;
                    $box_ex += 1;
                    $sapCode12 = substr($a, 0, 12);
                    $color = substr($a, 12, 2);
                    $size = substr($a, 14);
                    $strSize = array("x","X");  
                    $size = str_replace($strSize,'*', $size);
                    $newSlov_id = $key;
                    $connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
                    $g5['connect_samjindb'] = $connect_db;
                    $sqlSamjin = "SELECT ORDER_NO, SAP_CODE, ITEM FROM S_MALL_ORDERS WHERE SAP_CODE = '{$sapCode12}' AND COLOR = '$color' AND HOCHING = '$size'";
                    $rsSamjin = mssql_sql_query($sqlSamjin);
                    $num_rows = mssql_sql_num_rows($rsSamjin);

                    if (!$num_rows || $num_rows == null || $num_rows ==0) {
                        $updateSql = "UPDATE sabang_lt_order_view SET ov_options_modify = NULL ,samjin_link_check = 2, ov_samjin_name = NULL, ov_samjin_code = NULL , ov_dpartner = NULL, ov_stock1 = NULL, ov_stock2 = NULL, ov_color = NULL, ov_size = NULL, ov_sap_code = NULL, ov_delivery_company = NULL, ov_delivery_company_code = NULL, ov_qty_form = NULL
                        WHERE slov_id = '$key' OR sub_slov_id = '$key'";
                        sql_query($updateSql);
                        // $result = '매핑에 실패하였습니다 옵션을 확인해주세요.';
                        // echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                        // return false;
                    }
                    $ov_samjin_name = '';
                    $ov_samjin_code = '';
                    $ov_sap_code = '';

                    for ($k = 0; $samrow = mssql_sql_fetch_array($rsSamjin); $k++) {  
                      $ov_samjin_name = $samrow['ITEM'];
                      $ov_samjin_code = $samrow['ORDER_NO'];
                      $ov_sap_code = $samrow['SAP_CODE'];
                    }

                    if ($ov_samjin_code=='') {
                        $updateSql = "UPDATE sabang_lt_order_view SET ov_options_modify = '' , samjin_link_check = 2
                        WHERE slov_id = '$key' OR sub_slov_id = '$key'";
                        sql_query($updateSql); 
                        // $result = '매핑에 실패하였습니다 옵션을 확인해주세요.';
                        // echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                        // return false;
                    } else {
                        $optPlus += 1;
                        if ($optPlus == 1) {
                            $deleteSql = "DELETE FROM sabang_lt_order_view WHERE sub_slov_id = '$key'";
                            sql_query($deleteSql);
                            $updateSql = "UPDATE sabang_lt_order_view SET ov_sum_sno = 0  WHERE slov_id = '$key'";
                            sql_query($updateSql);
                        }
                        if ($box_ex > 1) {
                            $insertSql = "INSERT INTO sabang_lt_order_view(sub_slov_id,slod_id,samjin_link_check,order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_mall_id,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,ov_sap_code,ov_mall_code,ov_sabang_code,ov_it_name,ov_brand,ov_options,ov_distribution_status,ov_qty,ov_total_cost,ov_pay_cost,ov_delv_cost,ov_order_msg,ov_invoice_no,ov_delivery_company,ov_delivery_company_code,ov_sms_check,ov_ct_id,ov_sum_sno,ov_register_datetime,ov_collection_degress,ov_MALL_PRODUCT_ID) SELECT slov_id,slod_id,samjin_link_check,order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_mall_id,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,ov_sap_code,ov_mall_code,ov_sabang_code,ov_it_name,ov_brand,ov_options,ov_distribution_status,ov_qty,ov_total_cost,ov_pay_cost,ov_delv_cost,ov_order_msg,ov_invoice_no,ov_delivery_company,ov_delivery_company_code,ov_sms_check,ov_ct_id,ov_sum_sno,ov_register_datetime,ov_collection_degress,ov_MALL_PRODUCT_ID FROM sabang_lt_order_view WHERE slov_id = {$key}";
                        //   $insertSql = "INSERT INTO sabang_lt_order_view(sub_slov_id,slod_id,samjin_link_check,order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_mall_id,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,ov_sap_code,ov_mall_code,ov_sabang_code,ov_it_name,ov_brand,ov_samjin_name,ov_samjin_code,ov_options,ov_options_modify,ov_dpartner,ov_stock1,ov_stock2,ov_distribution_status,ov_qty,ov_color,ov_size,ov_total_cost,ov_pay_cost,ov_delv_cost,ov_order_msg,ov_invoice_no,ov_delivery_company,ov_delivery_company_code,ov_sms_check,ov_ct_id,ov_sum_sno,ov_register_datetime) SELECT slov_id,slod_id,samjin_link_check,order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_mall_id,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,ov_sap_code,ov_mall_code,ov_sabang_code,ov_it_name,ov_brand,ov_samjin_name,ov_samjin_code,ov_options,ov_options_modify,ov_dpartner,ov_stock1,ov_stock2,ov_distribution_status,ov_qty,ov_color,ov_size,ov_total_cost,ov_pay_cost,ov_delv_cost,ov_order_msg,ov_invoice_no,ov_delivery_company,ov_delivery_company_code,ov_sms_check,ov_ct_id,ov_sum_sno,ov_register_datetime FROM sabang_lt_order_view WHERE slov_id = {$key}";
                          $res = sql_query($insertSql);
                          if ($res) $newSlov_id = sql_insert_id();
                        }

                        $selectSql = "SELECT ov_order_id,ov_mall_id,ov_order_name, ov_qty, ov_it_name FROM sabang_lt_order_view WHERE (slov_id = '{$key}' OR sub_slov_id = '{$key}') LIMIT 1";
                        $mall = sql_fetch($selectSql);
                        $ov_order_id = $mall['ov_order_id'];
                        $ov_mall_id = $mall['ov_mall_id'];
                        $ov_order_name = $mall['ov_order_name'];
                        $ov_qty = $mall['ov_qty'];
                        $ov_it_name = $mall['ov_it_name'];

                        $ov_stock1 = 0;
                        $ov_stock2 = 0;
                        $stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(1,0,$sapCode12,$color,$size);
                        $ov_distribution_status = '1';
                        if (count($stockSamjin) == 0) {
                        } else {
                            if (strpos($ov_it_name,'옥의티')!== false) {
                                // $ov_distribution_status = '리퍼';
                            } 
                            for ($j =0; $j < count($stockSamjin); $j++) {
                                if (strpos($ov_it_name,'옥의티')!== false) {
                                    if ($stockSamjin[$j]['C_NO'] == 45) {
                                        $ov_stock1 += (int)$ov_qty;
                                      } 
                                } else if ($stockSamjin[$j]['C_NO'] == 30 || $stockSamjin[$j]['C_NO'] == 31 || $stockSamjin[$j]['C_NO'] == 33 || $stockSamjin[$j]['C_NO'] == 34) {
                                  $ov_stock1 += $stockSamjin[$j]['STOCK2'];
                                } else if ($stockSamjin[$j]['C_NO'] == 2 || $stockSamjin[$j]['C_NO'] == 3 || $stockSamjin[$j]['C_NO'] == 4) {
                                  $ov_stock2 += $stockSamjin[$j]['STOCK2'];
                                }
                            } 
                        }
                        
                        $fromQty = 1;
                        $sapCodeBrand = substr($sapCode12, 2, 1);
                        if ($sapCodeBrand =='D' || $sapCodeBrand =='d') {
                          $sapCodeCheck = substr($sapCode12, 9, 3);
                            if ($sapCodeCheck == '201' || $sapCodeCheck == '202' || $sapCodeCheck == '203') {
                                $fromQty = 2;
                            }
                        }
                        if ($ov_stock1 != 0 && $ov_stock1 >= (int)$ov_qty * $fromQty) {
                          $ov_dpartner = '경민실업';
                          $ov_delivery_company = 'CJ대한통운';
                          $ov_delivery_company_code = '003';
                          $ov_distribution_status = NULL;
                        } else if ($ov_stock2 != 0 && $ov_stock2 >= (int)$ov_qty * $fromQty) {
                          $ov_dpartner = '어시스트';
                          $ov_delivery_company = '롯데택배';
                          $ov_delivery_company_code = '002';
                          $ov_distribution_status = NULL;
                        } else {
                            $ov_distribution_status = '품절';
                            $updateSql = "UPDATE sabang_lt_order_view SET ov_distribution_status = '$ov_distribution_status'  WHERE slov_id = '{$key}' OR sub_slov_id = '{$key}'";
                            sql_query($updateSql); 
                            $ov_dpartner = null;
                            $ov_delivery_company = null;
                            $ov_delivery_company_code = null;
                        }
                        if (strpos($row['ov_it_name'],'옥의티')!== false) {
                            $ov_dpartner = '경민실업';
                            $ov_delivery_company = 'CJ대한통운';
                            $ov_delivery_company_code = '003';
                        }
                        $whereCheck = '';
                        if ($ov_mall_id == '15001') {
                            $whereCheck = " ov_order_id = '$ov_order_id' AND ";
                        } else {
                            $whereCheck = " (slov_id = '$key' OR sub_slov_id = '$key' OR ov_order_id LIKE ('{$ov_order_id}%') AND ov_mall_id ='$ov_mall_id' AND ov_order_name ='$ov_order_name') AND ";
                            $selectSql = "SELECT COUNT(*) AS cnt FROM sabang_lt_order_view WHERE sub_slov_id = '{$key}'";
                            $set = sql_fetch($selectSql);
                            if ($set['cnt'] >0) {
                              $setCheck = '002';
                              $updateSql = "UPDATE sabang_lt_order_view SET ov_set_check = '002'  WHERE (slov_id = '{$key}' OR sub_slov_id = '{$key}')";
                              sql_query($updateSql);
                            }
                        }
                        $selectSql = "SELECT count(*) AS CNT FROM sabang_lt_order_view WHERE $whereCheck ov_distribution_status = '품절' AND slov_id != '$newSlov_id'";
                        $soldOut = sql_fetch($selectSql);
                        $soldOutCnt = $soldOut['CNT'];
                        if ($soldOutCnt > 0) {
                          $ov_distribution_status = '품절';
                        } 

                        $ov_options_modify = $sapCode12.$color.$size;
                        $updateSql = "UPDATE sabang_lt_order_view SET samjin_link_check = 1, ov_samjin_name = '$ov_samjin_name', ov_samjin_code = '$ov_samjin_code', ov_dpartner = '$ov_dpartner', 
                                      ov_stock1 = '$ov_stock1', ov_stock2 = '$ov_stock2', ov_color = '$color', ov_size = '$size', ov_sap_code = '$ov_sap_code', ov_delivery_company = '$ov_delivery_company', ov_delivery_company_code = '$ov_delivery_company_code',
                                      ov_options_modify = '$ov_options_modify', ov_distribution_status = IF('$ov_distribution_status'='1', ov_distribution_status,'$ov_distribution_status'), ov_qty_form = ov_qty * $fromQty
                                      WHERE slov_id = '$newSlov_id'";
                        sql_query($updateSql); 

                        $selectSql = "SELECT COUNT(*) AS cnt FROM sabang_lt_order_view WHERE sub_slov_id = '{$key}'";
                        $set = sql_fetch($selectSql);
                        if ($set['cnt'] >0) {
                          $setCheck = '002';
                          $updateSql = "UPDATE sabang_lt_order_view SET ov_set_check = '002'  WHERE (slov_id = '{$key}' OR sub_slov_id = '{$key}')";
                          sql_query($updateSql);
                        }
                        //  check
                        $selectSql = "SELECT IFNULL(MAX(ov_sum_sno+1), 1) AS ov_sum_sno FROM sabang_lt_order_view WHERE $whereCheck ov_dpartner = '$ov_dpartner' ORDER BY ov_sum_sno DESC LIMIT 1";
                        $sum_sno = sql_fetch($selectSql);
                        $ov_sum_sno = $sum_sno['ov_sum_sno'];
                        $updateSql = "UPDATE sabang_lt_order_view SET ov_sum_sno = $ov_sum_sno  WHERE $whereCheck ov_dpartner = '$ov_dpartner'";
                        sql_query($updateSql); 
                    }
                }
            }
        }
        $selectSql = "SELECT ov_IDX FROM sabang_lt_order_view WHERE slov_id = '{$key}' LIMIT 1"; 
        $selIDX = sql_fetch($selectSql);
        $selIDX['ov_IDX']; 
        if ($optPlus > 0) {
            if($optPlus == 1){
                if($mappingSuccessKey == '') $mappingSuccessKey .= $key;
                else $mappingSuccessKey .= ','.$key;
                // 프로시져
                $resultSql = "SELECT ov_samjin_code , ov_mall_code , ov_color, ov_size , ov_sabang_code  FROM sabang_lt_order_view WHERE slov_id = '{$key}' LIMIT 1 "; 
                $result_stock = sql_fetch($resultSql);
                
                $BARCODE = $result_stock['ov_mall_code'];
                $ORDER_NO = $result_stock['ov_samjin_code'];
                $COLOR = $result_stock['ov_color'];
                $HOCHING = $result_stock['ov_size'];
                $SABANGCODE =  $result_stock['ov_sabang_code'];

                //삼진바코드
                $barcode_add = NM_ADD_BARCODE($BARCODE,$ORDER_NO,$COLOR,$HOCHING);
                $add_result_no = $barcode_add[0]['V1'];
                $add_result_meg = $barcode_add[0]['RSLT'];
                
                $barcodeSql = "UPDATE sabang_send_goods_list SET samjin_barcode_no = '{$add_result_no}', samjin_barcode_meg = '{$add_result_meg}'
                                WHERE sabang_goods_cd = '{$SABANGCODE}'
                                ";
                sql_query($barcodeSql);
            }
            // 매핑 성공
            $mappingSuccessId .= $selIDX['ov_IDX'].' ';
        } else {
            // 매핑 실패
            $mappingFailId .= $selIDX['ov_IDX'].' ';
            $updateSql = "UPDATE sabang_lt_order_view SET ov_options_modify = NULL ,samjin_link_check = 2, ov_samjin_name = NULL, ov_samjin_code = NULL , ov_dpartner = NULL, ov_stock1 = NULL, ov_stock2 = NULL, ov_color = NULL, ov_size = NULL, ov_sap_code = NULL, ov_delivery_company = NULL, ov_delivery_company_code = NULL, ov_qty_form = NULL
            WHERE (slov_id = '{$key}' OR sub_slov_id = '{$key}')";
            sql_query($updateSql);
        }
    }
    if ($mappingFailId == NULL) {
        $result = "매핑 완료 되었습니다.";    
    } else {
        $result .= "성공 매핑 : ".$mappingSuccessId;
        $result .= " , 실패 매핑 : ".$mappingFailId;     
    }
    if($mappingSuccessKey != '') $sabang_send_stock_set =  sql_query('CALL sabang_send_stock_set("'.$mappingSuccessKey.'")');
    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    return;
}













return;


print_r ('<Br> ㅋ : ');


return;
dd('ttttaaa');


return;
// dd('sabang_order_collection zzzzzzzzzzzzzzzzzzzzzzddddddddzezezzzzzzezeze');
// todrkr sksl cjdmadnfl

if ($config['cf_extra_point_1'] && $config['cf_extra_point_1'] != 0) insert_point('kootest', $config['cf_extra_point_1'], '생일 축하', '@birthday', 'kootest', '생일');
// aldksgo gkwlak !!!
// ska
// insert_point('kootest', $config['cf_extra_point_1'], '생일 축하', '@birthday', 'kootest', '생일');
// sorktmadms nqwksl
dd('tttt');
// 

// rhekslgns

// $sql = "select * from lt_member where DATE_FORMAT(mb_birth, '%m-%d') = DATE_FORMAT(now(), '%m-%d') ";
// $cp_id = get_coupon_id();
// $



// dd($cp_id);

echo G5_PATH; 
echo '<br>';
echo G5_URL;

// return;
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

// 방법1, PHP < 5.3.0
echo gethostbyname(php_uname('n')); // 127.0.0.1
echo '<Br>';
// 방법2, PHP >= 5.3.0
echo gethostbyname(gethostname()); // 127.0.0.1
echo '<Br>';
// 방법3
echo $_SERVER['SERVER_ADDR'];
echo '<Br>';



$ip = $_SERVER['REMOTE_ADDR'];
echo '<br> ip : '.$ip.'<br>';
echo "SERVER 함수 사용자 아이피 : ".$_SERVER['REMOTE_ADDR'];
echo "<br>";
echo "getenv 사용자 아이피 : ".get_client_ip();
// return;

// 
// MOS20AS28D11WHQSSF
// MOS20AS28D11WHQ
// $stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(1,0,'MOS20AS28D11','WH','Q');
$filepath = "http://nsimg.sabangnet.co.kr/product_image2/mw67645/103/103128_1.jpg";
// $fp = fopen($filepath, 'r');

// $fp = fopen($filepath, 'r+');
// $current .= "John Smith\n";
// echo '<Br> fp : '.$fp.'<Br>';
// fputs($fp, $current);
// rewind($fp);


// echo '<Br> fp : '.$fp.'<Br>';

// dd($fp);
$tmp_img_bin = file_get_contents($filepath);


// $content = file_get_contents('http://www.google.com/');


// file_put_contents($destpath, $tmp_img_bin);
// dd($tmp_img_bin);

// 디비에 저장될 파일 이름 
//  $filename = $_FILES['userfile']['name'];
// B 호스트에 저장될 실제 파일
//  $tmpfile = md5("habony_" . $_FILES['userfile']['tmp_name']); 
//  $fp = fopen($tmp_img_bin, 'r'); 
//  dd($fp);
// return;
// B 호스트 정보
// $ftp_port = 2021;
// ftp_connect ('',11,90);

// gnagna

// gksk Qnsl gkskQns

$filepath = "http://nsimg.sabangnet.co.kr/product_image2/mw67645/103/103128_1.jpg";
$ftp_server = "litandard-org.daouidc.com"; 
// $ftp_server = "192.168.60.129"; 
$ftp_port = 2021; 
$ftp_user_name = "litandard"; 
$ftp_user_pass = "flxosekem_ftp!@34"; 
// $ftp_user_name = "ftpkoo"; 
// $ftp_user_pass = "1596"; 
$conn_id = ftp_connect($ftp_server,$ftp_port) or die("Couldn't connect to $ftp_server");
echo '<br> conn : ';
var_dump($conn_id);
echo ($conn_id);
echo '<br>';
print("test");
// die();

$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<Br> Couldn't connect to $ftp_server"); 
echo '<br>';
echo ($login_result);
echo '<br>';
ftp_pasv($conn_id, true);

// dd($login_result);
// dd($login_result);
$ftp_dir = ftp_pwd($conn_id);
echo '<br> ftp_dir :';
echo ($ftp_dir);
echo '<br>';
// $a = ftp_mkdir($conn_id, "0512");
// $b = ftp_chmod($conn_id, 0777, '0512');
// echo '<br> a : ';
// echo ($a);
// echo '<br>';
// echo '<br> b : ';
// echo ($b);
// echo '<br>';0


// 


$file = fopen("0512/test2.jpg","w");
$filepath = "http://nsimg.sabangnet.co.kr/product_image2/mw67645/103/103128_1.jpg";
$upload = ftp_put($conn_id, '/0512/ttt2.jpg', $filepath, FTP_BINARY);
// echo ($upload);
// ㅋㅋㅋㅋㅋㅋㅋ
return;


if (ftp_fput($conn_id, $tmpfile, $fp, FTP_BINARY)) { 
    echo "Successfully uploaded $filename\n";
} else { 
    echo "There was a problem while uploading $file\n"; 
} 

ftp_close($conn_id); 
fclose($fp); 


dd('dd');
return;

// $litandard = 'litandard';
// $pass = '리탠다드_ftp!@34';
$tt = "ftp://{$litandard}:{$pass}@litandard-org.daouidc.com/test/koo";
$file = "http://nsimg.sabangnet.co.kr/product_image2/mw67645/103/103128_1.jpg";



$dest = fopen("ftp://{$litandard}:{$pass}@litandard-org.daouidc.com:2021/test/koo" . $file, "wb");


echo '<Br> tt : '.$tt.'<br>';
dd('안녕');
dd($dest);
$src = file_get_contents($file);
fwrite($dest, $src, strlen($src));
fclose($dest);
// !@!@!2 
// ㅇ
dd('dd');
return;
// 


// is_url_exists('http://stackoverflow.com')
if (getimagesize('http://localhost/data/item/010010030000066/103128_1.jpg') ) {
    echo '있 ~ ~';
} else {
    echo '없 ~ ~';
}

dd(file_exists('http://localhost/data/item/010030040000166/101054_1.jpg'));


// 
$expire_limit = date("Y-m-d", strtotime("+ 30 day"));
$sql_point_expire = "SELECT (SUM(po_point)-SUM(po_use_point)) AS point_expire FROM lt_point WHERE mb_id='{$member['mb_id']}' AND po_expired=0 AND po_expire_date <= '{$expire_limit}'";

echo '<Br> ex : '.$expire_limit.'<br>'; 
echo '<Br> ex : '.$sql_point_expire.'<br>'; 
return;

$po_expire_date = date('Y-m-d', strtotime('+'.($po_expire_date + 14).' day', G5_SERVER_TIME));
// dd($po_expire_date);


// -------------------------------안쓰는 페이지 --------------------------------------------------------
// $arr2 = array('aa','bb','cc'); 
// $arr = '';
// foreach ($arr2 as $mbId) { 
//     echo '<br> test : '.$mbId.'<br>';
// }
// for ($i=0; $row=$arr2; $i++) { 
//     echo '<br> ttt'.$row.'<br>';
//     echo '<br> iiii'.$i.'<br>';
// }

// dd($arr2);

// return;
// $po_expire_date = date('Y-m-d', strtotime('+'.($po_expire_date + 14).' day', G5_SERVER_TIME));
// $selectMemberSql = "SELECT mb_id FROM lt_member WHERE mb_leave_date = ''";
// $resultMember = sql_query($selectMemberSql);
// for ($i = 0; $rm = sql_fetch_array($resultMember); $i++) {  
//     $mbId = $rm['mb_id'];
//     $mb_point = get_point_sum($mbId);
//     $po_mb_point = (int)$mb_point+5000;
//     $po_expired = 100;
//     $sql = " insert into {$g5['point_table']}
//             set mb_id = '$mbId',
//             po_datetime = '".G5_TIME_YMDHIS."',
//             po_content = '오해피위크',
//             po_point = 5000,
//             po_use_point = '0',
//             po_mb_point = '$po_mb_point',
//             po_expired = '$po_expired',
//             po_expire_date = '$po_expire_date',
//             po_rel_table = '오해피위크',
//             po_rel_id = '',
//             po_rel_action = '오해피위크',
//             po_request_id = 'jeongwseong' ";

//     sql_query($sql);              
//     $sql = " update {$g5['member_table']} set mb_point = $po_mb_point where mb_id = '$mbId' ";
//     sql_query($sql);
// }
dd('dd');
return;
// $select = "SELECT mb_id FROM lt_member WHERE ";
// $result2 = sql_query($select);
// for ($i=0; $row=sql_fetch_array($result2); $i++) { 

// }

$mb_point = get_point_sum($mbId);
$po_mb_point = (int)$mb_point+5000;
$po_expire_date = date('Y-m-d', strtotime('+'.($po_expire_date + 1).' years', G5_SERVER_TIME));
$po_expired = 100;
$sql = " insert into {$g5['point_table']}
        set mb_id = '$mbId',
        po_datetime = '".G5_TIME_YMDHIS."',
        po_content = '오해피위크',
        po_point = 5000,
        po_use_point = '0',
        po_mb_point = '$po_mb_point',
        po_expired = '$po_expired',
        po_expire_date = '$po_expire_date',
        po_rel_table = '오해피위크',
        po_rel_id = '',
        po_rel_action = '오해피위크',
        po_request_id = 'jeongwseong' ";

sql_query($sql);              
$sql = " update {$g5['member_table']} set mb_point = $po_mb_point where mb_id = '$mbId' ";
sql_query($sql);



dd('dd');






// MAS20HC68706_GR_Q
// MAS20HC67706_GR_S
$stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(1,0,'MAS20HC68706','GR','Q');

dd($stockSamjin);

// dd('gggggg');
// s
// preg_match_all("/[^() || \:\ \-\ \/\,]+/", '상품명:001_비앙코 구스페더 베개솜 1000g,색
// 상/사이즈:화이트/50x70,품번(필수선택):MWO19FS01P11WH50*70-1개',$c);
// dd($c);

// foreach($c[0] as $opc) {
//     // ㅇㅇㅇ
//     // dddd
//     // dd
//     // 11123456789
// }





// $a  = NM_GET_STOCK_WITH_SAP_CODE(0,0,0,0,0);
// $ssTest =  NM_GET_STOCK_WITH_SAP_CODE(1,1,1,1,1);
$stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(1,0,'MDS21SS03D12','WH','Q');
dd($stockSamjin);

return;
// $select = "SELECT order_invoice,sabang_ord_no,order_it_color,order_it_size,samjin_code FROM sabang_lt_order_form";
// $result2 = sql_query($select);

for ($i=0; $row=sql_fetch_array($result2); $i++) { 


$upd = "UPDATE sabang_lt_order_view SET ov_invoice_no = '{$row['order_invoice']}' WHERE ov_IDX = '{$row['sabang_ord_no']}' AND ov_color = '{$row['order_it_color']}' AND ov_size = '{$row['order_it_size']}' AND ov_samjin_code = '{$row['samjin_code']}' ";
// dd($upd);
sql_query($upd);
// dd($upd);
}

dd('ttt');
return;



// dd('http://lifelike.co.kr/?NaPm=ct%3Dhf2bview%7Cci%3D0Gy1001FZzHfpCO600-M%7Ctr%3Dsa%7Cet%3Dhf3rbd2w%7Cba%3D1%2E0%7Caa%3D1%2E0%7Chk%3Db03c9555b13a8cdb2e0c58e6759281a4efcd510d');
// ddd
// mappingOpt[$(this).val()] = $(`#optionsModi_${$(this).val()}`).val();
// if (mappingOpt[$(this).val()] == '' || mappingOpt[$(this).val()] == null) {
//     alert("옵션명을 확인해주세요.");
//     return false;
// }
$result = '';

// $mappingOpt = {};
// dd('tt');
$buttonType = 'mapping';
 
$select = "SELECT slov_id, TRIM(ov_options) AS ov_options FROM sabang_lt_order_view WHERE ov_mall_name ='카카오메이커스' AND ov_order_status ='신규주문' AND sub_slov_id = 0  ORDER BY slov_id ASC LIMIT 50, 0";
$result2 = sql_query($select);

// return;
if ($buttonType == 'mapping') {
  $mappingCheck = 0;
  $mappingFailId = NULL;
  $mappingSuccessId = NULL;
  $mappingSuccessKey = '';

  for ($i=0; $row=sql_fetch_array($result2); $i++) {
      $key = $row['slov_id'];
      $values = $row['ov_options'];
      preg_match_all("/[^() || \-\ \/\,]+/", $values,$c);
      $optPlus = 0;
      $box_ex = 0;
    // dd($c);
      $optionsArr = array();
      foreach($c[0] as $opc) {
          if (strlen($opc) > 14) {
              if(substr($opc, 0, 1)=='M' || substr($opc, 0, 1)=='Z'){
                  if (strpos($opc,'+')!== false) {
                      preg_match_all("/[^ \+\,]+/", $opc, $opcPlus);
                      for($i=0; $i<$opcPlus[0][1]; $i++) {
                          array_push($optionsArr,  $opcPlus[0][0]);
                      }
                  } else {
                      array_push($optionsArr,  $opc);
                  }
              }
          }
      }
      // dd($optionsArr);

      foreach($optionsArr as $a) {

          if (strlen($a) > 14) {
              if(substr($a, 0, 1)=='M' || substr($a, 0, 1)=='Z'){
                  $mappingCheck = 1;
                  $box_ex += 1;
                  $sapCode12 = substr($a, 0, 12);
                  $color = substr($a, 12, 2);
                  $size = substr($a, 14);
                  $strSize = array("x","X");  
                  $size = str_replace($strSize,'*', $size);
                  $newSlov_id = $key;
                  $connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
                  $g5['connect_samjindb'] = $connect_db;
                  $sqlSamjin = "SELECT ORDER_NO, SAP_CODE, ITEM FROM S_MALL_ORDERS WHERE SAP_CODE = '{$sapCode12}' AND COLOR = '$color' AND HOCHING = '$size'";
                  $rsSamjin = mssql_sql_query($sqlSamjin);
                  $num_rows = mssql_sql_num_rows($rsSamjin);

                  if (!$num_rows || $num_rows == null || $num_rows ==0) {
                      $updateSql = "UPDATE sabang_lt_order_view SET ov_options_modify = NULL ,samjin_link_check = 2, ov_samjin_name = NULL, ov_samjin_code = NULL , ov_dpartner = NULL, ov_stock1 = NULL, ov_stock2 = NULL, ov_color = NULL, ov_size = NULL, ov_sap_code = NULL, ov_delivery_company = NULL, ov_delivery_company_code = NULL, ov_qty_form = NULL
                      WHERE slov_id = '$key' OR sub_slov_id = '$key'";
                      sql_query($updateSql);
                      // $result = '매핑에 실패하였습니다 옵션을 확인해주세요.';
                      // echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                      // return false;
                  }
                  $ov_samjin_name = '';
                  $ov_samjin_code = '';
                  $ov_sap_code = '';

                  for ($k = 0; $samrow = mssql_sql_fetch_array($rsSamjin); $k++) {  
                    $ov_samjin_name = $samrow['ITEM'];
                    $ov_samjin_code = $samrow['ORDER_NO'];
                    $ov_sap_code = $samrow['SAP_CODE'];
                  }

                  if ($ov_samjin_code=='') {
                      $updateSql = "UPDATE sabang_lt_order_view SET ov_options_modify = '' , samjin_link_check = 2
                      WHERE slov_id = '$key' OR sub_slov_id = '$key'";
                      sql_query($updateSql); 
                      // $result = '매핑에 실패하였습니다 옵션을 확인해주세요.';
                      // echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                      // return false;
                  } else {
                      $optPlus += 1;
                      if ($optPlus == 1) {
                          $deleteSql = "DELETE FROM sabang_lt_order_view WHERE sub_slov_id = '$key'";
                          sql_query($deleteSql);
                          $updateSql = "UPDATE sabang_lt_order_view SET ov_sum_sno = 0  WHERE slov_id = '$key'";
                          sql_query($updateSql);
                      }
                      if ($box_ex > 1) {
                          $insertSql = "INSERT INTO sabang_lt_order_view(sub_slov_id,slod_id,samjin_link_check,order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_mall_id,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,ov_sap_code,ov_mall_code,ov_sabang_code,ov_it_name,ov_brand,ov_options,ov_distribution_status,ov_qty,ov_total_cost,ov_pay_cost,ov_delv_cost,ov_order_msg,ov_invoice_no,ov_delivery_company,ov_delivery_company_code,ov_sms_check,ov_ct_id,ov_sum_sno,ov_register_datetime,ov_collection_degress,ov_MALL_PRODUCT_ID) SELECT slov_id,slod_id,samjin_link_check,order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_mall_id,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,ov_sap_code,ov_mall_code,ov_sabang_code,ov_it_name,ov_brand,ov_options,ov_distribution_status,ov_qty,ov_total_cost,ov_pay_cost,ov_delv_cost,ov_order_msg,ov_invoice_no,ov_delivery_company,ov_delivery_company_code,ov_sms_check,ov_ct_id,ov_sum_sno,ov_register_datetime,ov_collection_degress,ov_MALL_PRODUCT_ID FROM sabang_lt_order_view WHERE slov_id = {$key}";
                      //   $insertSql = "INSERT INTO sabang_lt_order_view(sub_slov_id,slod_id,samjin_link_check,order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_mall_id,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,ov_sap_code,ov_mall_code,ov_sabang_code,ov_it_name,ov_brand,ov_samjin_name,ov_samjin_code,ov_options,ov_options_modify,ov_dpartner,ov_stock1,ov_stock2,ov_distribution_status,ov_qty,ov_color,ov_size,ov_total_cost,ov_pay_cost,ov_delv_cost,ov_order_msg,ov_invoice_no,ov_delivery_company,ov_delivery_company_code,ov_sms_check,ov_ct_id,ov_sum_sno,ov_register_datetime) SELECT slov_id,slod_id,samjin_link_check,order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_mall_id,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,ov_sap_code,ov_mall_code,ov_sabang_code,ov_it_name,ov_brand,ov_samjin_name,ov_samjin_code,ov_options,ov_options_modify,ov_dpartner,ov_stock1,ov_stock2,ov_distribution_status,ov_qty,ov_color,ov_size,ov_total_cost,ov_pay_cost,ov_delv_cost,ov_order_msg,ov_invoice_no,ov_delivery_company,ov_delivery_company_code,ov_sms_check,ov_ct_id,ov_sum_sno,ov_register_datetime FROM sabang_lt_order_view WHERE slov_id = {$key}";
                        $res = sql_query($insertSql);
                        if ($res) $newSlov_id = sql_insert_id();
                      }

                      $selectSql = "SELECT ov_order_id,ov_mall_id,ov_order_name, ov_qty, ov_it_name FROM sabang_lt_order_view WHERE (slov_id = '{$key}' OR sub_slov_id = '{$key}') LIMIT 1";
                      $mall = sql_fetch($selectSql);
                      $ov_order_id = $mall['ov_order_id'];
                      $ov_mall_id = $mall['ov_mall_id'];
                      $ov_order_name = $mall['ov_order_name'];
                      $ov_qty = $mall['ov_qty'];
                      $ov_it_name = $mall['ov_it_name'];

                      $ov_stock1 = 0;
                      $ov_stock2 = 0;
                      $stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(1,0,$sapCode12,$color,$size);
                      $ov_distribution_status = '1';
                      if (count($stockSamjin) == 0) {
                      } else {
                          if (strpos($ov_it_name,'옥의티')!== false) {
                              // $ov_distribution_status = '리퍼';
                          } 
                          for ($j =0; $j < count($stockSamjin); $j++) {
                              if (strpos($ov_it_name,'옥의티')!== false) {
                                  if ($stockSamjin[$j]['C_NO'] == 31) {
                                      $ov_stock1 += (int)$ov_qty;
                                    } 
                              } else if ($stockSamjin[$j]['C_NO'] == 30 || $stockSamjin[$j]['C_NO'] == 31) {
                                $ov_stock1 += $stockSamjin[$j]['STOCK2'];
                              } else if ($stockSamjin[$j]['C_NO'] == 2 || $stockSamjin[$j]['C_NO'] == 3 || $stockSamjin[$j]['C_NO'] == 4) {
                                $ov_stock2 += $stockSamjin[$j]['STOCK2'];
                              }
                          } 
                      }
                      
                      $fromQty = 1;
                      $sapCodeBrand = substr($sapCode12, 2, 1);
                      if ($sapCodeBrand =='D' || $sapCodeBrand =='d') {
                        $sapCodeCheck = substr($sapCode12, 9, 3);
                          if ($sapCodeCheck == '201' || $sapCodeCheck == '202' || $sapCodeCheck == '203') {
                              $fromQty = 2;
                          }
                      }
                      if ($ov_stock1 != 0 && $ov_stock1 >= (int)$ov_qty * $fromQty) {
                        $ov_dpartner = '경민실업';
                        $ov_delivery_company = 'CJ대한통운';
                        $ov_delivery_company_code = '003';
                        $ov_distribution_status = NULL;
                      } else if ($ov_stock2 != 0 && $ov_stock2 >= (int)$ov_qty * $fromQty) {
                        $ov_dpartner = '어시스트';
                        $ov_delivery_company = '롯데택배';
                        $ov_delivery_company_code = '002';
                        $ov_distribution_status = NULL;
                      } else {
                          $ov_distribution_status = '품절';
                          $updateSql = "UPDATE sabang_lt_order_view SET ov_distribution_status = '$ov_distribution_status'  WHERE slov_id = '{$key}' OR sub_slov_id = '{$key}'";
                          sql_query($updateSql); 
                          $ov_dpartner = null;
                          $ov_delivery_company = null;
                          $ov_delivery_company_code = null;
                      }
                      if (strpos($row['ov_it_name'],'옥의티')!== false) {
                          $ov_dpartner = '경민실업';
                          $ov_delivery_company = 'CJ대한통운';
                          $ov_delivery_company_code = '003';
                      }
                      $whereCheck = '';
                      if ($ov_mall_id == '15001') {
                          $whereCheck = " ov_order_id = '$ov_order_id' AND ";
                      } else {
                          $whereCheck = " (slov_id = '$key' OR sub_slov_id = '$key' OR ov_order_id LIKE ('{$ov_order_id}%') AND ov_mall_id ='$ov_mall_id' AND ov_order_name ='$ov_order_name') AND ";
                          $selectSql = "SELECT COUNT(*) AS cnt FROM sabang_lt_order_view WHERE sub_slov_id = '{$key}'";
                          $set = sql_fetch($selectSql);
                          if ($set['cnt'] >0) {
                            $setCheck = '002';
                            $updateSql = "UPDATE sabang_lt_order_view SET ov_set_check = '002'  WHERE (slov_id = '{$key}' OR sub_slov_id = '{$key}')";
                            sql_query($updateSql);
                          }
                      }
                      $selectSql = "SELECT count(*) AS CNT FROM sabang_lt_order_view WHERE $whereCheck ov_distribution_status = '품절' AND slov_id != '$newSlov_id'";
                      $soldOut = sql_fetch($selectSql);
                      $soldOutCnt = $soldOut['CNT'];
                      if ($soldOutCnt > 0) {
                        $ov_distribution_status = '품절';
                      } 

                      $ov_options_modify = $sapCode12.$color.$size;
                      $updateSql = "UPDATE sabang_lt_order_view SET samjin_link_check = 1, ov_samjin_name = '$ov_samjin_name', ov_samjin_code = '$ov_samjin_code', ov_dpartner = '$ov_dpartner', 
                                    ov_stock1 = '$ov_stock1', ov_stock2 = '$ov_stock2', ov_color = '$color', ov_size = '$size', ov_sap_code = '$ov_sap_code', ov_delivery_company = '$ov_delivery_company', ov_delivery_company_code = '$ov_delivery_company_code',
                                    ov_options_modify = '$ov_options_modify', ov_distribution_status = IF('$ov_distribution_status'='1', ov_distribution_status,'$ov_distribution_status'), ov_qty_form = ov_qty * $fromQty
                                    WHERE slov_id = '$newSlov_id'";
                      sql_query($updateSql); 

                      $selectSql = "SELECT COUNT(*) AS cnt FROM sabang_lt_order_view WHERE sub_slov_id = '{$key}'";
                      $set = sql_fetch($selectSql);
                      if ($set['cnt'] >0) {
                        $setCheck = '002';
                        $updateSql = "UPDATE sabang_lt_order_view SET ov_set_check = '002'  WHERE (slov_id = '{$key}' OR sub_slov_id = '{$key}')";
                        sql_query($updateSql);
                      }
                      //  check
                      $selectSql = "SELECT IFNULL(MAX(ov_sum_sno+1), 1) AS ov_sum_sno FROM sabang_lt_order_view WHERE $whereCheck ov_dpartner = '$ov_dpartner' ORDER BY ov_sum_sno DESC LIMIT 1";
                      $sum_sno = sql_fetch($selectSql);
                      $ov_sum_sno = $sum_sno['ov_sum_sno'];
                      $updateSql = "UPDATE sabang_lt_order_view SET ov_sum_sno = $ov_sum_sno  WHERE $whereCheck ov_dpartner = '$ov_dpartner'";
                      sql_query($updateSql); 
                  }
              }
          }
      }
      $selectSql = "SELECT ov_IDX FROM sabang_lt_order_view WHERE slov_id = '{$key}' LIMIT 1"; 
      $selIDX = sql_fetch($selectSql);
      $selIDX['ov_IDX']; 
      if ($optPlus > 0) {
          if($optPlus == 1){
              if($mappingSuccessKey == '') $mappingSuccessKey .= $key;
              else $mappingSuccessKey .= ','.$key;
              // 프로시져
              $resultSql = "SELECT ov_samjin_code , ov_mall_code , ov_color, ov_size , ov_sabang_code  FROM sabang_lt_order_view WHERE slov_id = '{$key}' LIMIT 1 "; 
              $result_stock = sql_fetch($resultSql);
              
              $BARCODE = $result_stock['ov_mall_code'];
              $ORDER_NO = $result_stock['ov_samjin_code'];
              $COLOR = $result_stock['ov_color'];
              $HOCHING = $result_stock['ov_size'];
              $SABANGCODE =  $result_stock['ov_sabang_code'];

              //삼진바코드
              $barcode_add = NM_ADD_BARCODE($BARCODE,$ORDER_NO,$COLOR,$HOCHING);
              $add_result_no = $barcode_add[0]['V1'];
              $add_result_meg = $barcode_add[0]['RSLT'];
              
              $barcodeSql = "UPDATE sabang_send_goods_list SET samjin_barcode_no = '{$add_result_no}', samjin_barcode_meg = '{$add_result_meg}'
                              WHERE sabang_goods_cd = '{$SABANGCODE}'
                              ";
              sql_query($barcodeSql);
          }
          // 매핑 성공
          $mappingSuccessId .= $selIDX['ov_IDX'].' ';
      } else {
          // 매핑 실패
          // dd('fff');
          $mappingFailId .= $selIDX['ov_IDX'].' ';
          $updateSql = "UPDATE sabang_lt_order_view SET ov_options_modify = NULL ,samjin_link_check = 2, ov_samjin_name = NULL, ov_samjin_code = NULL , ov_dpartner = NULL, ov_stock1 = NULL, ov_stock2 = NULL, ov_color = NULL, ov_size = NULL, ov_sap_code = NULL, ov_delivery_company = NULL, ov_delivery_company_code = NULL, ov_qty_form = NULL
          WHERE (slov_id = '{$key}' OR sub_slov_id = '{$key}')";
          // dd('fff');
          sql_query($updateSql);
      }
  }
  if ($mappingFailId == NULL) {
      $result = "매핑 완료 되었습니다.";    
  } else {
      $result .= "성공 매핑 : ".$mappingSuccessId;
      $result .= " , 실패 매핑 : ".$mappingFailId;     
  }
  echo $result;
  // dd('d');
  if($mappingSuccessKey != '') $sabang_send_stock_set =  sql_query('CALL sabang_send_stock_set("'.$mappingSuccessKey.'")');
  echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
  return;
}

dd('tt');
return;

?>

<script>
  $(function() { 
    alert('testsetset')

  })
  function makeCouponForce() {
        const cz_id = fcouponform.cz_id.value;
        const make_count = $("#cz_make_coupon").val();

        const tmp_coupons = [{
            id: cz_id,
            count: make_count,
            force: true
        }];

        const coupons = encodeURIComponent(JSON.stringify(tmp_coupons));

        $.ajax({
            type: 'GET',
            data: {
                coupons
            },
            url: '/shop/ajax.coupondownload.php',
            cache: false,
            async: true,
            dataType: 'json',
            success(data) {
                if (data.error != '') {
                    alert(data.error);
                    return false;
                }

                alert('쿠폰이 발행됐습니다.');
            },
        });
    }  
</script>


