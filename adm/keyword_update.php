<?php
$sub_menu = '800800';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

$keyword = ($_POST['keyWord']);


$sql = " update {$g5['config_table']} set cf_keyword = '$keyword'";
sql_query($sql);

goto_url('./keyword.php');
return;
?>


