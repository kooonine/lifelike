<?php
include_once('./_common.php');
//리스 해지

// 세션에 저장된 토큰과 폼으로 넘어온 토큰을 비교하여 틀리면 에러
if ($token && get_session("ss_token") == $token) {
    // 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
    set_session("ss_token", "");
} else {
    set_session("ss_token", "");
    alert("토큰 에러", G5_SHOP_URL);
}

$od = sql_fetch(" select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' and mb_id = '{$member['mb_id']}' ");

if (!$od['od_id']) {
    alert("존재하는 주문이 아닙니다.");
}

$uid = md5($od['od_id'].$od['od_time'].$od['od_ip']);

$od_contractout      = clean_xss_tags($od_contractout);
    
sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '해지요청', ct_status_claim = '해지' where od_id = '$od_id' and ct_status = '리스중' ");

$sql = " insert into lt_shop_order_history
                    (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim, cancel_select)
                 values
                    ('$od_id', 1, '[해지요청] ".$od_contractout."', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','해지','$od_contractout'); ";
sql_query($sql);
/*
$od_b_name        = clean_xss_tags($od_b_name);
$od_b_tel         = clean_xss_tags($od_b_tel);
$od_b_hp          = clean_xss_tags($od_b_hp);
$od_b_zip1  = substr($od_b_zip, 0, 3);
$od_b_zip2  = substr($od_b_zip, 3);
$od_b_addr1       = clean_xss_tags($od_b_addr1);
$od_b_addr2       = clean_xss_tags($od_b_addr2);
$od_b_addr3       = clean_xss_tags($od_b_addr3);
$od_b_addr_jibeon = preg_match("/^(N|R)$/", $od_b_addr_jibeon) ? $od_b_addr_jibeon : '';


//서명파일 저장
$cust_file_data = $_POST['cust_file'];
$cust_file_data = str_replace('data:image/png;base64,', '', $cust_file_data);
$cust_file_data = str_replace(' ', '+', $cust_file_data);
$fileData = base64_decode($cust_file_data);

// 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
@mkdir(G5_DATA_PATH.'/file/order', G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH.'/file/order', G5_DIR_PERMISSION);
@mkdir(G5_DATA_PATH.'/file/order/'.$od_id, G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH.'/file/order/'.$od_id, G5_DIR_PERMISSION);

$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

$upload = array();

shuffle($chars_array);
$shuffle = implode('', $chars_array);
$fileName = 'signature.png';

$upload[0]['file'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);
$dest_file = G5_DATA_PATH.'/file/order/'.$od_id.'/'.$upload[0]['file'];
file_put_contents($dest_file, $fileData);

// 올라간 파일의 퍼미션을 변경합니다.
chmod($dest_file, G5_FILE_PERMISSION);
$upload[0]['filesize'] = filesize($dest_file);

$od_contractout_cust_file = "";
if(count($upload) > 0) {
    $od_contractout_cust_file = json_encode_raw($upload, JSON_UNESCAPED_UNICODE);
}

// 해지요청
$sql = " update {$g5['g5_shop_order_table']}
            set od_status_claim   = '해지',
                od_status_claim_date = '".G5_TIME_YMDHIS."',
                od_status         = '해지요청',
                od_hope_date      = '$od_hope_date',
                od_b_name         = '$od_b_name',
                od_b_tel          = '$od_b_tel',
                od_b_hp           = '$od_b_hp',
                od_b_zip1         = '$od_b_zip1',
                od_b_zip2         = '$od_b_zip2',
                od_b_addr1        = '$od_b_addr1',
                od_b_addr2        = '$od_b_addr2',
                od_b_addr3        = '$od_b_addr3',
                od_b_addr_jibeon  = '$od_b_addr_jibeon',
                od_contractout    = '$od_contractout',
                od_contractout_cust_file        = '$od_contractout_cust_file',
                od_penalty        = '$od_penalty',
                od_shop_memo = concat(od_shop_memo,\"\\n주문자 본인 해지 요청 - ".G5_TIME_YMDHIS." (해지이유 : {$od_contractout})\")
            where od_id = '$od_id' ";
sql_query($sql);
*/
// 해지요청

$od_penalty = rental_contractout_calc($od, $od_contractout);

$sql = " update {$g5['g5_shop_order_table']}
            set od_status_claim   = '해지',
                od_status_claim_date = '".G5_TIME_YMDHIS."',
                od_status         = '해지요청',
                od_contractout    = '$od_contractout',
                od_penalty        = '$od_penalty',
                od_shop_memo = concat(od_shop_memo,\"\\n주문자 본인 해지 요청 - ".G5_TIME_YMDHIS." (해지이유 : {$od_contractout})\")
            where od_id = '$od_id' ";
sql_query($sql);

include(G5_SHOP_PATH.'/ordermail1.inc.php');
$arr_change_data = array();
$arr_change_data['고객명'] = $od['od_name'];
$arr_change_data['이름'] = $od['od_name'];
$arr_change_data['보낸분'] = $od['od_name'];
$arr_change_data['받는분'] = $od['od_b_name'];;
$arr_change_data['주문번호'] = $od_id;
$arr_change_data['주문금액'] = number_format($ttotal_price + $od_send_cost + $od_send_cost2);
$arr_change_data['결제금액'] = number_format($od_receipt_price);
$arr_change_data['회원아이디'] = $od['mb_id'];
$arr_change_data['회사명'] = $default['de_admin_company_name'];
$arr_change_data["아이디"] = $od['mb_id'];
$arr_change_data["고객명(아이디)"] = $od['od_name']."(".$od['mb_id'].")";
$arr_change_data["od_list"] = $list;
$arr_change_data['od_type'] = $od['od_type'];
$arr_change_data['od_id'] = $od_id;

msg_autosend('리스', '해지 신청', $od['mb_id'], $arr_change_data);

goto_url(G5_SHOP_URL."/orderinquiryview.php?od_id=$od_id&amp;uid=$uid");
?>