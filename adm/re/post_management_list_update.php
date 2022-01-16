<?php
include_once('./_common.php');

if (isset($_POST['mb_id']))  {
    $post_mb_id = clean_xss_tags(trim($_POST['mb_id']));
    if ($post_mb_id) {
        $post_mb_id = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", "", $post_mb_id);
        $qstr .= '&amp;mb_id=' . urlencode($post_mb_id);
    }
}
if (isset($_POST['mode']))  {
    $post_mode = clean_xss_tags(trim($_POST['mode']));
    if ($post_mode) {
        $post_mode = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", "", $post_mode);
        $qstr .= '&amp;mode=' . urlencode($post_mode);
    }
}

if(!isset($rtnURL) || $rtnURL == "") $rtnURL = G5_ADMIN_URL."/community/post_management.php";

check_admin_token();

if($_POST['btn_submit'] == '선택삭제' || $_POST['btn_submit'] == '댓글선택삭제') {
    
    $count = count($_POST['chk']);
    
    if(!$count) {
        alert($_POST['btn_submit'].' 하실 항목을 하나 이상 선택하세요.');
    }
    
    include G5_ADMIN_PATH.'/community/post_move_delete.php';
    //
} elseif($_POST['btn_submit'] == '선택복원' || $_POST['btn_submit'] == '댓글선택복원') {
    
    $count = count($_POST['chk2']);
    
    if(!$count) {
        alert($_POST['btn_submit'].' 하실 항목을 하나 이상 선택하세요.');
    }
    include G5_ADMIN_PATH.'/community/post_recovery.php';
    
} elseif($_POST['btn_submit'] == '완전삭제' || $_POST['btn_submit'] == '댓글완전삭제') {
    
    $count = count($_POST['chk2']);
    
    if(!$count) {
        alert($_POST['btn_submit'].' 하실 항목을 하나 이상 선택하세요.');
    }
    include G5_ADMIN_PATH.'/community/post_delete_all.php';
} elseif($_POST['btn_submit'] == '적립금적용') {
    
    $count = count($_POST['chk']);
    
    if(!$count) {
        alert($_POST['btn_submit'].' 하실 항목을 하나 이상 선택하세요.');
    }
    
    include G5_ADMIN_PATH.'/community/post_point_save.php';
    //
} elseif(substr($_POST['btn_submit'], 0, 4) == 'spam') {
    
    $count = count($_POST['chk']);
    
    if(!$count) {
        alert($_POST['btn_submit'].' 하실 항목을 하나 이상 선택하세요.');
    }
    
    include G5_ADMIN_PATH.'/community/post_spam.php';
    //
} elseif($_POST['btn_submit'] == '스팸해제') {
    
    $count = count($_POST['chk']);
    
    if(!$count) {
        alert($_POST['btn_submit'].' 하실 항목을 하나 이상 선택하세요.');
    }
    
    include G5_ADMIN_PATH.'/community/post_spam_clear.php';
    //
} else{
    alert('올바른 방법으로 이용해 주세요.');
}
?>