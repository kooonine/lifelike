<?php
include_once './common.php';
require_once(G5_LIB_PATH . '/badge.lib.php');
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$g5_title = "최근 본 상품";


$history_mb_id_mo = $is_member ? $member['mb_id'] : session_id();
$g5_user_history_mo = user_history("list", array("mb_id" => $history_mb_id_mo));


    

// $total_page  = ceil($snum[$type] / $perpage);

// $qstr .= 'type=' . $type;
// $qstr .= '&amp;skeyword=' . $skeyword;
// $paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');


$contents = include_once(G5_VIEW_PATH . "/history.view.php");

include_once G5_LAYOUT_PATH . "/layout.php";
