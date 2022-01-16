<?php
include_once('./../common.php');

if(!empty($_POST)) {
	foreach ($_POST as $pushRe) {
		$msg_title = $pushRe['msg_title'];
		$msg_body = $pushRe['msg_body'];
		$msg_url = $pushRe['msg_url'];
		$fcmId_ori = $pushRe['fcmId'];
		$sendType = $pushRe['sendType'];
		$fcmId = str_replace(',' , '","', $fcmId_ori);
		$sf_type = 'push';
		$pushType = $pushRe['pushType'];
		$pushDate = $pushRe['pushDateTimeIn'];
		$deviceType = $pushRe['deviceType'];
		$prType = $pushRe['prType'];
		$prId = $pushRe['prId'];

		$tokens = array();
		$prWhere ='AND mb_id IS NOT NULL AND mb_id !=""';
		if($prType!=1) {
			$prWhere = 'AND mb_id IN ("'.$fcmId.'")';
		}
	
		$sql = 'SELECT token, mb_id, device FROM lt_app_users where push_check = 1 '.$prWhere;
	
		$result = sql_query($sql);
		$deviceCheck='';
		while ($row = sql_fetch_array($result)) {
			$tokens[] = $row['token'];
			$mb_id[] = $row['mb_id'];
			if(count($tokens) == 1) {
				$deviceCheck = $row['device'];
			} else {
				if ($deviceCheck !='
				' && $deviceCheck != $row['device']) $deviceCheck = '전체';
			}
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
					 set sh_sendtype = '예약발송',
						sf_type = '".$sf_type."',
						msg_type = '".$deviceCheck."',
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
			$updatesql = "UPDATE lt_app_push_reservation SET pr_status = 1 WHERE pr_id = '$prId'";
			sql_query($updatesql);
		}
		else
		{
			// return false;
		}

	}
} else {
	// return false;
}

?>

