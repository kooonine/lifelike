<?php
include_once('./_common.php');

// 로그인중인 경우 회원가입 할 수 없습니다.
if ($is_member) {
    goto_url(G5_URL);
}

// 세션을 지웁니다.
set_session("ss_mb_reg", "");
 
$register_type = $_GET['type'];

$g5['title'] = '회원가입';
include_once('./_head.php');


$cf_1 = json_decode($config['cf_1'], true);
$cf_2 = json_decode($config['cf_2'], true);


$register_action_url = G5_BBS_URL.'/register_form_update.php';
$register_auth_url = G5_BBS_URL.'/ajax.register_auth.php';
$register_certify_url = G5_BBS_URL.'/ajax.register_authkey_certify.php';
if($register_type == 'normal'){
    include_once($member_skin_path.'/register.normal.php');
}else if($register_type == 'company'){
    include_once($member_skin_path.'/register.company.php');
}else{
    goto_url(G5_URL."/common/register_select.php");
}

?>
