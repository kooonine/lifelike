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
	$sf_type = 'push';
	$pushType = $_POST['pushType'];
	$pushDate = $_POST['pushDateTimeIn'];
	$prIdCron = $_POST['prIdCron'];
	$pr_status_change = $_POST['pr_status_change'];
	if (!$pushType || $pushType==0)	{
		$tokens = array();

		$sql = 'SELECT token, mb_id FROM lt_app_users where mb_id IS NOT NULL AND mb_id !="" AND push_check = 1';

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

		if($body['success'] > 0 ) $result_code = "200";
		else $result_code = "100";


		if($msg_url) {
			$msg_body .= ", URL:".$msg_url;
		}

		$sql = " insert into lt_sms_sendhistory
					 set sh_sendtype = '전체발송',
						sf_type = '".$sf_type."',
						msg_type = '전체',
						dest_phone = '전체',
						msg_title = '{$msg_title}',
						msg_body = '{$msg_body}',
						result_code = '{$result_code}',
						result_msg = '".json_encode($body)."',
						sh_datetime = '".G5_TIME_YMDHIS."',
						send_time = ''
					";
		sql_query($sql);
		if($pr_status_change==2) {
			$updatesql = "UPDATE lt_app_push_reservation SET pr_status = 2 WHERE pr_id = '$prIdCron'";
			sql_query($updatesql);
		} 
		$lpi = sql_insert_id();
		foreach ($tokens as $key => $value) {
			$sqlin = "INSERT INTO `lt_push_count_history` (`token`, `mid`, `pid`, `regdate`) VALUES ('".$value."', '".$mb_id[$key]."', '".$lpi."', now());";
			sql_query($sqlin);
		}

		if($body['success'] > 0 )
		{
			alert('PUSH 메시지 발송을 완료했습니다.', './configform_app_push_send.php', false);
		}
		else
		{
			alert('PUSH 메시지 발송에 실패했습니다.');
		}
	} else {
		$min10 = $_POST['min10'];
		$sql = "INSERT INTO lt_app_push_reservation SET pr_type = 1, pr_mb_id ='전체', pr_push_date ='{$pushDate}', pr_title='{$msg_title}', pr_body='{$msg_body}', pr_url='{$msg_url}', pr_status='{$min10}'";
		sql_query($sql);
		// if ($min10==1) {
		// }
		alert('PUSH 메시지 예약을 완료했습니다.', './configform_app_push_send.php', false);
	}
} else {
	alert('잘못된 접근입니다.');
}

?>
