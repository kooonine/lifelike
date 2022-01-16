<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$sql_notice = "SELECT * FROM lt_write_notice WHERE wr_1='1' ORDER BY wr_datetime DESC LIMIT 6";
$db_notice = sql_query($sql_notice);

include_once G5_LAYOUT_PATH . "/view/tail.php";
