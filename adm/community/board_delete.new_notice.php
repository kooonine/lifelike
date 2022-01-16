<?php
// board_delete.php , boardgroup_delete.php 에서 include 하는 파일
include_once('./_common.php');
//if (!defined('_GNUBOARD_')) exit;
//if (!defined('_BOARD_DELETE_')) exit; // 개별 페이지 접근 불가

$wr_id = $_GET['wr_id'];

$sql = " delete from lt_write_new_notice where wr_id = '{$wr_id}' ";
sql_query($sql);

$sql_noti_cnt = "SELECT COUNT(*) AS cnt FROM lt_write_new_notice";
$row = sql_fetch($sql_noti_cnt);
$total_count = $row['cnt'];

$sql_board_cnt = "update lt_board set bo_count_write = '{$total_count}' where bo_table = 'new_notice' ";

sql_query($sql_board_cnt);

