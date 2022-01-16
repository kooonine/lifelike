<?
include_once('./_common.php');

$error = $flag = $count = "";

function print_result($error, $flag, $count){
	echo '{ "error": "' . $error . '", "flag": "' . $flag . '", "count": "' . $count . '" }';
	if($error){
		exit;
	}
}

if (!$is_member){
	$error = '회원만 가능합니다.';
	print_result($error, $flag, $count);
}

if (!($is_id)){
	$error = '값이 제대로 넘어오지 않았습니다.';
	print_result($error, $flag, $count);
}

$row = sql_fetch(" select count(*) as cnt from lt_shop_item_use where is_id = '{$is_id} ", FALSE);
if ($row['cnt'] == '0') {
	$error = '존재하는 리뷰가 아닙니다.';
	print_result($error, $flag, $count);
}

if ($good == 'good' || $good == 'nogood'){

	$sql = " select bg_flag from lt_shop_item_use_good where is_id = '{$is_id}' and mb_id = '{$member['mb_id']}' and bg_flag in ('good', 'nogood') ";
	$row = sql_fetch($sql);
	if ($row['bg_flag']){
		// 내역 생성
		sql_query(" delete from lt_shop_item_use_good where is_id = '{$is_id}' and mb_id = '{$member['mb_id']}' and bg_flag = '{$good}' ");

		$sql = " select count(*) as count from lt_shop_item_use_good where is_id = '$is_id' ";
		$row = sql_fetch($sql);
		$count = $row['count'];
		$flag = "OFF";
		print_result($error, $flag, $count);
	} else {
		// 내역 생성
		sql_query(" insert lt_shop_item_use_good 
                        set is_id = '{$is_id}'
                            , mb_id = '{$member['mb_id']}'
                            , bg_flag = '{$good}'
                            , ig_time = '".G5_TIME_YMDHIS."'
                            , ig_ip = '$REMOTE_ADDR' ");
		
		$sql = " select count(*) as count from lt_shop_item_use_good where is_id = '$is_id' ";
		$row = sql_fetch($sql);
		$count = $row['count'];
		$flag = "ON";
		print_result($error, $flag, $count);
	}
}
?>
