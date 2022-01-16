<?
include_once('./_common.php');

if (!$is_member){
	$result -> result = "F";
	$result -> alert = '회원 전용 서비스 입니다.';
	$result -> url = G5_BBS_URL.'/login.php?url='.urlencode($url);
	echo json_encode_raw($result,JSON_UNESCAPED_UNICODE);
	exit;
}

if ($w == "d"){
	$wi_id = clean_xss_tags(trim($_GET['wi_id']));

	$sql = " select mb_id from {$g5['g5_shop_wish_table']} where wi_id = '$wi_id' ";
	$row = sql_fetch($sql);

	if($row['mb_id'] != $member['mb_id']){

		$result -> result = "F";
		$result -> alert = '위시리시트 상품을 삭제할 권한이 없습니다.';
		echo json_encode_raw($result,JSON_UNESCAPED_UNICODE);
		exit;
	}

	$sql = " delete from {$g5['g5_shop_wish_table']}
	where wi_id = '$wi_id'
	and mb_id = '{$member['mb_id']}' ";
	sql_query($sql);
}
elseif ($w == "r"){
	if(is_array($it_id))
		$it_id = clean_xss_tags(trim($_POST['it_id'][0]));

	if(!$it_id){
		$result -> result = "F";
		$result -> alert = '상품코드가 올바르지 않습니다.';
		echo json_encode_raw($result,JSON_UNESCAPED_UNICODE);
		exit;
	}

	$sql = " select wi_id from {$g5['g5_shop_wish_table']} where mb_id = '{$member['mb_id']}' and it_id = '$it_id' ";
	$row = sql_fetch($sql);

	$sql = " delete from {$g5['g5_shop_wish_table']}
	where wi_id = '{$row['wi_id']}'
	and mb_id = '{$member['mb_id']}' ";
	sql_query($sql);
}
else
{
	if(is_array($it_id))
		$it_id = clean_xss_tags(trim($_POST['it_id'][0]));

	if(!$it_id){
		$result -> result = "F";
		$result -> alert = '상품코드가 올바르지 않습니다.';
		echo json_encode_raw($result,JSON_UNESCAPED_UNICODE);
		exit;
	}

	// 상품정보 체크
	$sql = " select it_id from {$g5['g5_shop_item_table']} where it_id = '$it_id' ";
	$row = sql_fetch($sql);

	if(!$row['it_id']){
		$result -> result = "F";
		$result -> alert = '상품정보가 존재하지 않습니다.';
		echo json_encode_raw($result,JSON_UNESCAPED_UNICODE);
		exit;
	}

	$sql = " select wi_id from {$g5['g5_shop_wish_table']}
	where mb_id = '{$member['mb_id']}' and it_id = '$it_id' ";
	$row = sql_fetch($sql);

	if (!$row['wi_id']) { // 없다면 등록
		$sql = " insert {$g5['g5_shop_wish_table']}
		set mb_id = '{$member['mb_id']}',
		it_id = '$it_id',
		wi_time = '".G5_TIME_YMDHIS."',
		wi_ip = '$REMOTE_ADDR' ";
		sql_query($sql);
	}
}

$sqlwish = " select count(*) as cnt from lt_shop_wish where it_id ='$it_id' ";
$rowwish = sql_fetch($sqlwish);

$result -> result = "S";
$result -> count = $rowwish['cnt'];
echo json_encode_raw($result,JSON_UNESCAPED_UNICODE);
?>
