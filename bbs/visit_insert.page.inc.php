<?php
include_once("./_common.php");

// 컴퓨터의 아이피와 쿠키에 저장된 아이피가 다르다면 테이블에 반영함
//if (get_cookie('ck_visit_ip') != $_SERVER['REMOTE_ADDR'])
{
    //set_cookie('ck_visit_ip', $_SERVER['REMOTE_ADDR'], 86400); // 하루동안 저장

    // $_SERVER 배열변수 값의 변조를 이용한 SQL Injection 공격을 막는 코드입니다. 110810
    $remote_addr = escape_trim($_SERVER['REMOTE_ADDR']);
    $referer = "";
    if (isset($_POST['full_page']))
        $referer = escape_trim(clean_xss_tags($_POST['full_page']));
    
    $user_agent  = escape_trim(clean_xss_tags($_SERVER['HTTP_USER_AGENT']));
    $vi_browser = '';
    $vi_os = '';
    $vi_device = '';
    if(version_compare(phpversion(), '5.3.0', '>=') && defined('G5_BROWSCAP_USE') && G5_BROWSCAP_USE) {
        include_once(G5_BBS_PATH.'/visit_browscap.inc.php');
    }
    
    $vi_page = escape_trim(clean_xss_tags($_POST['vi_page']));
    $vi_stay = escape_trim(clean_xss_tags($_POST['vi_stay']));
    
    $sql = " insert lt_visit_page ( vi_page, vi_ip, vi_date, vi_time, vi_stay, vi_referer, vi_agent, vi_browser, vi_os, vi_device ) 
            values ( '{$vi_page}', '{$remote_addr}', '".G5_TIME_YMD."', '".G5_TIME_HIS."', '{$vi_stay}', '{$referer}', '{$user_agent}', '{$vi_browser}', '{$vi_os}', '{$vi_device}' ) ";

    $result = sql_query($sql, FALSE);
}
?>
