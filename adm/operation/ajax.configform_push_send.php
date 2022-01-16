<?php
$sub_menu = "20";
include_once('./_common.php');
require_once(G5_LIB_PATH.'/Unirest.php');
require_once(G5_LIB_PATH.'/ppurioSMS.lib.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

if(!empty($_POST)) {
	$msg_title = $_POST['msg_title'];
	$msg_body = $_POST['msg_body'];
	$msg_url = $_POST['msg_url'];

	$sendType = $_POST['sendType'];

	$tokens = array();
	$mb_id = array();
	$sql = "SELECT token, mb_id FROM lt_app_users where (1)";

	if(is_checked('sendType')) {
		$sql = $sql." and device = '".$sendType."' ";
	}

	$result = sql_query($sql);
	while ($row = sql_fetch_array($result)) {
		$tokens[] = $row['token'];
		$mb_id[] = $row['mb_id'];
	}

	$dest_phone_tokens = "";
	if(count($tokens) > 0) $dest_phone_tokens = implode(",", $tokens);

	$message = array(
		"title"     => $msg_title,
		"message"   => $msg_body,
		"url"   => $msg_url
	);
	$body = send_notification($tokens, $msg_title, $msg_body, $message);
	$body = json_decode($body, true);

	//echo print_r2($body);
	if($body['success'] > 0 ) $result_code = "200";
	else $result_code = "100";


	if($msg_url) {
		$msg_body .= ", URL:".$msg_url;
	}

	$sql = " insert into lt_sms_sendhistory
				 set sh_sendtype = '개별발송',
					sf_type = 'push',
					msg_type = '".$sendType."',
					dest_phone = '".$dest_phone_tokens."',
					msg_title = '{$msg_title}',
					msg_body = '{$msg_body}',
					result_code = '{$result_code}',
					result_msg = '".json_encode($body)."',
					sh_datetime = '".G5_TIME_YMDHIS."',
					send_time = ''
				";
	sql_query($sql);
	$lpi = sql_insert_id();
	foreach ($tokens as $key => $value) {
		$sqlin = "INSERT INTO `lt_push_count_history` (`token`, `mid`, `pid`, `regdate`) VALUES ('".$value."', '".$mb_id[$key]."', '".$lpi."', now());";
		sql_query($sqlin);
	}

	if($body['success'] > 0 )
	{
		alert('PUSH 메시지 발송을 완료했습니다.', './configform_app_push.php', false);
	}
	else
	{
		alert('PUSH 메시지 발송에 실패했습니다.');
	}
} else {
	alert('잘못된 접근입니다.');
}

?>
