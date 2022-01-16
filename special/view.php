<?php
include_once('./../common.php');
require_once(G5_LIB_PATH . '/badge.lib.php');
require_once(G5_LIB_PATH . '/event.lib.php');

$cp_id = 1;

$sql_common = "SELECT * FROM lt_special WHERE cp_use = 1 AND (cp_end_date != '0000-00-00 00:00:00' AND cp_end_date > NOW()) ORDER BY cp_create_date DESC LIMIT 1";
$db_special = sql_fetch($sql_common);

// if (empty($cp_id) || empty($db_event['cp_id'])) {
//     alert("정상적인 방법으로 이용해주세요");
// }

$special = new event($db_special);

$qstr .= 'pick=' . $pick;
$qstr .= '&amp;filter=' . $filter;
$paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');

$contents = include_once(G5_VIEW_PATH . "/special.view.php");
include_once G5_LAYOUT_PATH . "/layout.php";
