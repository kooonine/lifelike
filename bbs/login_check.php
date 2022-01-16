<?
include_once('./_common.php');

$g5['title'] = "로그인 검사";

$mb_id       = trim($_POST['mb_id']);
$mb_password = trim($_POST['mb_password']);
$apptoken = trim($_POST['apptoken']);
$devicetoken = trim($_POST['devicetoken']);
$deviceKind = trim($_POST['deviceKind']);
$appopt = trim($_GET['appopt']);

$devicetokenSns = $_COOKIE["life_t"];
$deviceKindSns = $_COOKIE["life_k"];

setcookie("life_t", "" ,0,"/");
setcookie("life_k", "" ,0,"/");

if($appopt) {
	$mb_id  = $appopt;
	$mb = get_member($mb_id);

	// 차단된 아이디인가?
	if ($mb['mb_intercept_date'] && $mb['mb_intercept_date'] <= date("Ymd", G5_SERVER_TIME)) {
		$date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $mb['mb_intercept_date']);
		alert('회원님의 아이디는 접근이 금지되어 있습니다.\n처리일 : ' . $date);
	}
	
	// 탈퇴한 아이디인가?
	if ($mb['mb_leave_date'] && $mb['mb_leave_date'] <= date("Ymd", G5_SERVER_TIME)) {
		$date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $mb['mb_leave_date']);
		alert('탈퇴한 아이디이므로 접근하실 수 없습니다.\n탈퇴일 : ' . $date);
	}
	
	// 메일인증 설정이 되어 있다면
	if (is_use_email_certify() && !preg_match("/[1-9]/", $mb['mb_email_certify'])) {
		$ckey = md5($mb['mb_ip'] . $mb['mb_datetime']);
		confirm("{$mb['mb_email']} 메일로 메일인증을 받으셔야 로그인 가능합니다. 다른 메일주소로 변경하여 인증하시려면 취소를 클릭하시기 바랍니다.", G5_URL, G5_BBS_URL . '/register_email.php?mb_id=' . $mb_id . '&ckey=' . $ckey);
	}
	
	// 본인인증 미확인 아이디인 경우 본인인증
	if ( is_admin($mb_id) == "" && empty($mb['mb_dupinfo']) ) {
		$mkey = md5($mb['mb_id'] . $mb['mb_ip'] . $mb['mb_datetime']);
		alert("고객정보 보호를 위해 최초 1회 본인인증이 필요합니다.","/auth/login.pass.php?url=" . $url . "&id=" . $mb_id . "&token=" . $mkey);
	}
	// 소셜 체크 
	$is_social_login = true;
	$is_social_password_check = true;

	@include_once($member_skin_path . '/login_check.skin.php');

	// 회원아이디 세션 생성
	set_session('ss_mb_id', $mb['mb_id']);
	// FLASH XSS 공격에 대응하기 위하여 회원의 고유키를 생성해 놓는다. 관리자에서 검사함 - 110106
	set_session('ss_mb_key', md5($mb['mb_datetime'] . get_real_client_ip() . $_SERVER['HTTP_USER_AGENT']));

	// return;

} else {

if (!$mb_id || !$mb_password) {
	alert('회원아이디나 비밀번호가 공백이면 안됩니다.');
}

$mb = get_member($mb_id);

//소셜 로그인추가 체크

$is_social_login = false;
$is_social_password_check = false;

// 소셜 로그인이 맞는지 체크하고 해당 값이 맞는지 체크합니다.
if (function_exists('social_is_login_check')) {
	$is_social_login = social_is_login_check();
	//패스워드를 체크할건지 결정합니다.
	//소셜로그인일때는 체크하지 않고, 계정을 연결할때는 체크합니다.
	$is_social_password_check = social_is_login_password_check($mb_id);
}

//소셜 로그인이 맞다면 패스워드를 체크하지 않습니다.
// 가입된 회원이 아니다. 비밀번호가 틀리다. 라는 메세지를 따로 보여주지 않는 이유는
// 회원아이디를 입력해 보고 맞으면 또 비밀번호를 입력해보는 경우를 방지하기 위해서입니다.
// 불법사용자의 경우 회원아이디가 틀린지, 비밀번호가 틀린지를 알기까지는 많은 시간이 소요되기 때문입니다.
if (!$is_social_password_check && (!$mb['mb_id'] || !check_password($mb_password, $mb['mb_password']))) {
	alert('가입된 회원아이디가 아니거나 비밀번호가 틀립니다.\\n비밀번호는 대소문자를 구분합니다.');
}

// 차단된 아이디인가?
if ($mb['mb_intercept_date'] && $mb['mb_intercept_date'] <= date("Ymd", G5_SERVER_TIME)) {
	$date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $mb['mb_intercept_date']);
	alert('회원님의 아이디는 접근이 금지되어 있습니다.\n처리일 : ' . $date);
}

// 탈퇴한 아이디인가?
if ($mb['mb_leave_date'] && $mb['mb_leave_date'] <= date("Ymd", G5_SERVER_TIME)) {
	$date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $mb['mb_leave_date']);
	alert('탈퇴한 아이디이므로 접근하실 수 없습니다.\n탈퇴일 : ' . $date);
}

// 메일인증 설정이 되어 있다면
if (is_use_email_certify() && !preg_match("/[1-9]/", $mb['mb_email_certify'])) {
	$ckey = md5($mb['mb_ip'] . $mb['mb_datetime']);
	confirm("{$mb['mb_email']} 메일로 메일인증을 받으셔야 로그인 가능합니다. 다른 메일주소로 변경하여 인증하시려면 취소를 클릭하시기 바랍니다.", G5_URL, G5_BBS_URL . '/register_email.php?mb_id=' . $mb_id . '&ckey=' . $ckey);
}

// 본인인증 미확인 아이디인 경우 본인인증
if ( is_admin($mb_id) == "" && empty($mb['mb_dupinfo']) ) {
	$mkey = md5($mb['mb_id'] . $mb['mb_ip'] . $mb['mb_datetime']);
	alert("고객정보 보호를 위해 최초 1회 본인인증이 필요합니다.","/auth/login.pass.php?url=" . $url . "&id=" . $mb_id . "&token=" . $mkey);
}

@include_once($member_skin_path . '/login_check.skin.php');

// 회원아이디 세션 생성
set_session('ss_mb_id', $mb['mb_id']);
// FLASH XSS 공격에 대응하기 위하여 회원의 고유키를 생성해 놓는다. 관리자에서 검사함 - 110106
set_session('ss_mb_key', md5($mb['mb_datetime'] . get_real_client_ip() . $_SERVER['HTTP_USER_AGENT']));
}
//로그인 이력
$remote_addr = get_real_client_ip();
$referer = "";
if (isset($_SERVER['HTTP_REFERER'])) {
	$referer = escape_trim(clean_xss_tags($_SERVER['HTTP_REFERER']));
}
$user_agent  = escape_trim(clean_xss_tags($_SERVER['HTTP_USER_AGENT']));
$vi_browser = '';
$vi_os = '';
$vi_device = '';
if (version_compare(phpversion(), '5.3.0', '>=') && defined('G5_BROWSCAP_USE') && G5_BROWSCAP_USE) {
	include_once(G5_BBS_PATH . '/visit_browscap.inc.php');
}

$lh_sql = " insert lt_login_history ( lh_ip, mb_id, lh_date_time, lh_referer, lh_agent, lh_browser, lh_os, lh_device )
values ( '{$remote_addr}', '{$mb['mb_id']}', '" . G5_TIME_YMDHIS . "', '{$referer}', '{$user_agent}', '{$vi_browser}', '{$vi_os}', '{$vi_device}' ) ";

sql_query($lh_sql);

$sqlsu = "update lt_app_users set mb_id = '" . $mb['mb_id'] . "' where token = '" . $apptoken . "'";
sql_query($sqlsu);
if ($deviceKind =='APP_ANDROID' || $deviceKind =='APP_IOS') {
	$appUserSql = "update lt_app_users set mb_id = '" . $mb['mb_id'] . "' where token = '{$devicetoken}'";
	sql_query($appUserSql);
	insert_point($mb['mb_id'], $config['cf_install_point'], 'APP 설치 적립', '@appinstall', $mb['mb_id'],'App설치',30);
}

if ($deviceKindSns =='APP_ANDROID') {
	$appUserSnsSql = "update lt_app_users set mb_id = '" . $mb['mb_id'] . "' where token = '{$devicetokenSns}'";
	sql_query($appUserSnsSql);
	insert_point($mb['mb_id'], $config['cf_install_point'], 'APP 설치 적립', '@appinstall', $mb['mb_id'],'App설치',30);
}

// 포인트 체크
if ($config['cf_use_point']) {
	$sum_point = get_point_sum($mb['mb_id']);

	$sql = " update {$g5['member_table']} set mb_point = '$sum_point' where mb_id = '{$mb['mb_id']}' ";
	sql_query($sql);
}

// 자동로그인 관련 내용 수정 - balance@panpacific.co.kr
$arr_login_cookie = array();
if (is_checked("save_id")) {	// 자동로그인 : 쿠키 31일 저장
	$arr_login_cookie["ck_mb_id"] = $mb['mb_id'];
}
if (is_checked("save_me")) {	// 자동로그인 : 쿠키 31일 저장
	$arr_login_cookie["ck_mb_id"] = $mb['mb_id'];
	$arr_login_cookie["ck_auto"] = md5($_SERVER['SERVER_ADDR'] . $_SERVER['SERVER_SOFTWARE'] . $_SERVER['HTTP_USER_AGENT'] . $mb['mb_password']);
}

set_cookie('ck_mb_id', '', 0);
set_cookie('ck_auto', '', 0);
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

if ($url) {
	// url 체크
	check_url_host($url, '', G5_URL, true);

	$link = urldecode($url);
	// 2003-06-14 추가 (다른 변수들을 넘겨주기 위함)
	if (preg_match("/\?/", $link))
		$split = "&amp;";
	else
		$split = "?";

	// $_POST 배열변수에서 아래의 이름을 가지지 않은 것만 넘김
	$post_check_keys = array('mb_id', 'mb_password', 'x', 'y', 'url');

	//소셜 로그인 추가
	if ($is_social_login) {
		$post_check_keys[] = 'provider';
	}

	foreach ($_POST as $key => $value) {
		if ($key && !in_array($key, $post_check_keys)) {
			$link .= "$split$key=$value";
			$split = "&amp;";
		}
	}
} else {
	$link = G5_URL;
}

//소셜 로그인 추가
if (function_exists('social_login_success_after')) {
	// 로그인 성공시 소셜 데이터를 기존의 데이터와 비교하여 바뀐 부분이 있으면 업데이트 합니다.
	$link = social_login_success_after($mb, $link);
	social_login_session_clear(1);
}

//영카트 회원 장바구니 처리
if (function_exists('set_cart_id')) {
	$member = $mb;

	// 보관기간이 지난 상품 삭제
	cart_item_clean();
	set_cart_id('');
	$s_cart_id = get_session('ss_cart_id');
	// 선택필드 초기화
	$sql = " update {$g5['g5_shop_cart_table']} set ct_select = '0' where od_id = '$s_cart_id' ";
	sql_query($sql);
}
$reCheck = trim($_POST['reCheck']);
$rePage = trim($_POST['rePage']);
if ($reCheck==1) {
	goto_url($rePage);
} else {
	goto_url($link);	
}
