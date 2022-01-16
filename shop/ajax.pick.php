<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/json.lib.php');

if (!isset($_POST['id']) || !isset($_POST['action']) || !isset($_POST['type']))
    die(json_encode(array('error' => 'EMPTY_PARAMS')));

if (empty($member['mb_id']))
    die(json_encode(array('error' => 'NOT_FOUND_MEMBER')));

$result = array('error' => '', 'result' => false);
$type = strtolower($_POST['type']);
$sql_data = "mb_id = '{$member['mb_id']}', wi_type = '{$type}', it_id = '{$_POST['id']}'";

switch ($_POST['action']) {
    case 'pick':
        $sql = sprintf(
            "INSERT INTO %s SET %s, wi_time='%s', wi_ip='%s'",
            $g5['g5_shop_wish_table'],
            $sql_data,
            G5_TIME_YMDHIS,
            $REMOTE_ADDR
        );

        $result['result'] = sql_query($sql);
        break;
    case 'unpick':
        $sql = sprintf("DELETE FROM %s WHERE %s", $g5['g5_shop_wish_table'], str_replace(',', ' AND ', $sql_data));
        $result['result'] = sql_query($sql);
        break;
}

die(json_encode($result));
