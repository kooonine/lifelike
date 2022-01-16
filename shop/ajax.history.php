<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/json.lib.php');

if (!isset($_POST['id']) || !isset($_POST['type']))
    die(json_encode(array('error' => 'EMPTY_PARAMS')));

$mb_id = empty($member['mb_id']) ? session_id() : $member['mb_id'];

$result = array('error' => '', 'result' => false);
$sql = sprintf("DELETE FROM lt_history WHERE mb_id='%s' AND hi_type='%s' AND it_id='%s'", $mb_id, $_POST['type'], $_POST['id']);
$result['result'] = sql_query($sql);

die(json_encode($result));
