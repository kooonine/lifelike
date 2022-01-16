<?php
include_once('./_common.php');

$g5['title'] = "로그인 검사";




$Id       = trim($_POST['Id']);
$Pass = trim($_POST['Pass']);
$type = trim($_POST['type']);

$save_id = trim($_POST['save_id']);
$save_me = trim($_POST['save_me']);

$result = "";


if($type=='login'){
	$b2b_mb = sql_fetch(" select * from b2b_store_list where st_name = TRIM('{$Id}') ");
	
	// 가입된 회원이 아니다. 비밀번호가 틀리다. 라는 메세지를 따로 보여주지 않는 이유는
	// 회원아이디를 입력해 보고 맞으면 또 비밀번호를 입력해보는 경우를 방지하기 위해서입니다.
	// 불법사용자의 경우 회원아이디가 틀린지, 비밀번호가 틀린지를 알기까지는 많은 시간이 소요되기 때문입니다.
	if (!$b2b_mb['st_name'] || !check_password($Pass, $b2b_mb['st_password']) ) {
		// alert('가입된 회원아이디가 아니거나 비밀번호가 틀립니다.\\n비밀번호는 대소문자를 구분합니다.');
		$result  = '300';
		echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
		return false;
	}

	if($b2b_mb['st_comform'] == 'N'){
		$result  = '301';
		echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
		return false;
	}
	
	
	
	// 회원아이디 세션 생성
	set_session('st_mb_name', $b2b_mb['st_name']);
	set_session('st_mb_code', $b2b_mb['st_code']);
	// FLASH XSS 공격에 대응하기 위하여 회원의 고유키를 생성해 놓는다. 관리자에서 검사함 - 110106
	set_session('st_mb_key', md5($b2b_mb['join_date'] . get_real_client_ip() . $_SERVER['HTTP_USER_AGENT']));
	
	
	if($save_id == 'id_on'){
		setcookie('save_id', $save_id , time() + 86400);
		setcookie('save_name', $b2b_mb['st_name'] , time() + 86400);
	}
	if($save_me == 'me_on'){
		setcookie('save_me', $save_me , time() + 86400);
		setcookie('save_name', $b2b_mb['st_name'] , time() + 86400);
		setcookie('save_code', $b2b_mb['st_code'] , time() + 86400);
	}
	
	// 자동로그인 관련 내용 수정 - balance@panpacific.co.kr
	$arr_login_cookie = array();
	if (is_checked("save_id")) {	// 자동로그인 : 쿠키 31일 저장
		$arr_login_cookie["st_mb_id"] = $b2b_mb['st_name'];
	}
	if (is_checked("save_me")) {	// 자동로그인 : 쿠키 31일 저장
		$arr_login_cookie["st_mb_id"] = $b2b_mb['st_name'];
		$arr_login_cookie["st_auto"] = md5($_SERVER['SERVER_ADDR'] . $_SERVER['SERVER_SOFTWARE'] . $_SERVER['HTTP_USER_AGENT'] . $mb['mb_password']);
	}

	set_cookie('st_mb_id', '', 0);
	set_cookie('st_auto', '', 0);
	$mobileAppLogin = trim($_POST['mobileAppLogin']);
	$appPeriod = 31;
	if ($mobileAppLogin=="1") {
		$appPeriod = 1000;
	}
	if (!empty($arr_login_cookie)) {
		foreach ($arr_login_cookie as $alk => $alv) {
			set_cookie($alk, $alv, 86400 * $appPeriod);
		}
	}
	
	// 3.26
	// 아이디 쿠키에 한달간 저장
	if ($arr_login_cookie) {
		// 3.27
		// 자동로그인 ---------------------------
		// 쿠키 한달간 저장
		$key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['SERVER_SOFTWARE'] . $_SERVER['HTTP_USER_AGENT'] . $b2b_mb['st_password']);
		set_cookie('st_mb_name', $b2b_mb['st_name'], 86400 * 31);
		set_cookie('st_mb_code', $b2b_mb['st_code'], 86400 * 31);
		set_cookie('st_auto', $key, 86400 * 31);
		// 자동로그인 end ---------------------------
	} else {
		set_cookie('st_mb_name', '', 0);
		set_cookie('st_auto', '', 0);
	}
	
	//로그인 시간 기록
	$join_sql = " update b2b_store_list set join_date = now() where st_name = TRIM('{$Id}') and st_code = '{$b2b_mb['st_code']}' ";
	sql_query($join_sql);
	
	$result  = '200';
	echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
	return false;
}else if ($type=='logout'){
	// 이호경님 제안 코드
	session_unset(); // 모든 세션변수를 언레지스터 시켜줌
	session_destroy(); // 세션해제함

	// 자동로그인 해제 --------------------------------
	set_cookie('st_mb_name', '', 0);
	set_cookie('st_auto', '', 0);
	// 자동로그인 해제 end --------------------------------


	
	setcookie('save_code', $b2b_mb['st_code'] , time() - 86400);

	$result  = '201';
	echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
	return false;

}else if($type=='PassFind'){
	$st_name = trim($_POST['st_name']);
	$st_number = trim($_POST['st_number']);
	$st_owner = trim($_POST['st_owner'] );
	$st_tel =  trim($_POST['st_tel']);

	$b2b_sql = " select * from b2b_store_list where st_name = TRIM('{$st_name}') and st_number = '{$st_number}' and st_owner = '{$st_owner}' and st_tel = '{$st_tel}' ";
	$b2b_mb = sql_fetch($b2b_sql);



	if (!$b2b_mb['st_name'] ) {
		// alert('가입된 회원아이디가 아니거나 비밀번호가 틀립니다.\\n비밀번호는 대소문자를 구분합니다.');
		$result  = '300';
		echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
		return false;
	}

	if($b2b_mb['st_comform'] == 'N'){
		$result  = '301';
		echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
		return false;
	}

	$result  = '200';
	echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
	return false;
}else if ($type=='newPass'){

	$st_name = trim($_POST['st_name']);
	$cp_code = trim($_POST['cp_code']);
	$st_password  = get_encrypt_string($_POST['st_password']);

	$sql_common = "";

	$sql_common .= "st_password = '{$st_password}'  ";

	
	$up_st_sql = "update b2b_store_list SET ". $sql_common . " where cp_code = '{$cp_code}' and st_name  = '{$st_name}' ";
	sql_query($up_st_sql);


	$result  = '200';
	echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
	return false;

}
