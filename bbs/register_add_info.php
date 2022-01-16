<?php
include_once('./_common.php');


if (isset($_SESSION['ss_mb_reg'])){
    $mb = get_member($_SESSION['ss_mb_reg']);    
    $type = $_GET['type'];
    $mb_id = $mb['mb_id'];
}
if (!isset($_SESSION['ss_mb_reg']) || !$mb['mb_id']){
    alert('회원아이디 값이 없습니다. 올바른 방법으로 이용해 주십시오.',G5_URL);
}

$g5['title'] = '추가정보입력';
include_once('./_head.php');

$register_action_url = G5_BBS_URL.'/register_add_update.php';

include_once($member_skin_path.'/register.add.skin.php');


?>