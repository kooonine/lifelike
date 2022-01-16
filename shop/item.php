<?
include_once('./_common.php');
include_once(G5_LIB_PATH . '/iteminfo.lib.php');
include_once(G5_LIB_PATH . '/coupon.lib.php');

$it_id = get_search_string(trim($_GET['it_id']));


$sqlCheck = " SELECT lt_order_no, it_total_size FROM {$g5['g5_shop_item_table']} WHERE it_id = '{$it_id}' LIMIT 1 ";
$sqlRes = sql_fetch($sqlCheck);
if ($sqlRes['it_total_size'] != 1)  {
	$selCheck2 = " SELECT it_id FROM {$g5['g5_shop_item_table']} WHERE lt_order_no = '{$sqlRes['lt_order_no']}' AND it_total_size = 1  LIMIT 1 ";
	$sqlRes2 = sql_fetch($selCheck2);
	if (!$sqlRes2['it_id']) {
		
	} else {
		echo "<script>location.href='item.php?it_id={$sqlRes2['it_id']}'</script>";
	} 
}



$coupon = new coupon;
$coupon->setItem($it_id);

// 최대 혜택가(쿠폰사용 최대 할인금액)
$maxDiscountCouponInfo = $coupon->maxDiscountPrice();

// 분류사용, 상품사용하는 상품의 정보를 얻음
$sql = " select a.*, b.ca_name, b.ca_use from " . $g5['g5_shop_item_table'] . " a, " . $g5['g5_shop_category_table'] . " b where a.it_id = '" . $it_id . "' and a.ca_id = b.ca_id ";
$it = sql_fetch($sql);
if (!$it['it_id']) {
	alert('자료가 없습니다.');
}
if (!($it['ca_use'] && $it['it_use'])) {
	if (!$is_admin) {
		alert('현재 판매가능한 상품이 아닙니다.');
	}
}

// 분류 테이블에서 분류 상단, 하단 코드를 얻음
$sql = " select ca_skin_dir, ca_include_head, ca_include_tail, ca_cert_use, ca_adult_use from " . $g5['g5_shop_category_table'] . " where ca_id = '" . $it['ca_id'] . "' ";
$ca = sql_fetch($sql);
$g5['title'] = $it['it_name'] . ' &gt; ' . $it['ca_name'];
$ca_id = $it['ca_id'];

// History 저장 - 200413 balance@panpacific.co.kr
$mb_id =  $is_member ? $member['mb_id'] : session_id();
user_history("update", array("type" => "item", "mb_id" => $mb_id, "it_id" => $it_id));
// History 저장 끝

// 조회수 증가
if (get_cookie('ck_it_id') != $it_id) {
	sql_query(" update " . $g5['g5_shop_item_table'] . " set it_hit = it_hit + 1 where it_id = '" . $it_id . "' "); // 1증가
	set_cookie("ck_it_id", $it_id, 3600); // 1시간동안 저장
}

// 상품 상세보기 시작
// 고객선호도 별점수
$star_score = get_star_image($it['it_id']);

// 관리자가 확인한 사용후기의 개수를 얻음
// $sql = " select count(*) as cnt from `{$g5['g5_shop_item_use_table']}` where it_id = '{$it_id}' and is_confirm = '1' ";
// $row = sql_fetch($sql);
// $item_use_count = $row['cnt'];

$sql = " select count(*) as cnt from `{$g5['g5_shop_item_use_table']}` where io_order_no = '{$it['lt_order_no']}' and is_confirm = '1' ";
$row = sql_fetch($sql);
$item_use_count = $row['cnt'];

// 상품문의의 개수를 얻음
$sql = " select count(*) as cnt from `{$g5['qa_content_table']}` where it_id = '{$it_id}' and qa_type = '0' ";
$row = sql_fetch($sql);
$item_qa_count = $row['cnt'];

// 관련상품의 개수를 얻음
if ($default['de_rel_list_use']) {
	$sql = " select count(*) as cnt from {$g5['g5_shop_item_relation_table']} a left join " . $g5['g5_shop_item_table'] . " b on (a.it_id2=b.it_id) where a.it_id = '{$it['it_id']}' and  b.it_use='1' ";
	$row = sql_fetch($sql);
	$item_relation_count = $row['cnt'];
}

// 소셜 관련
$sns_title = get_text($it['it_name']) . ' | ' . get_text($config['cf_title']);
$sns_url  = G5_SHOP_URL . '/item.php?it_id=' . $it['it_id'];
$sns_share_links .= get_sns_share_link('facebook', $sns_url, $sns_title, G5_SHOP_SKIN_URL . '/img/facebook.png') . ' ';
$sns_share_links .= get_sns_share_link('twitter', $sns_url, $sns_title, G5_SHOP_SKIN_URL . '/img/twitter.png') . ' ';
$sns_share_links .= get_sns_share_link('googleplus', $sns_url, $sns_title, G5_SHOP_SKIN_URL . '/img/gplus.png');

// 상품품절체크
if (G5_SOLDOUT_CHECK)
	$is_soldout = is_soldout($it['it_id']);

// 주문가능체크
$is_orderable = true;
if (!$it['it_use'] || $it['it_tel_inq'] || $is_soldout)
	$is_orderable = false;

// if ($is_orderable) {
// 	if (defined('G5_THEME_USE_OPTIONS_TRTD') && G5_THEME_USE_OPTIONS_TRTD) {
// 		$option_item = get_item_options($it['it_id'], $it['it_option_subject'], '');
// 		$supply_item = get_item_supply($it['it_id'], $it['it_supply_subject'], '');
// 	} else {
// 		// 선택 옵션 ( 기존의 tr td 태그로 가져오려면 'div' 를 '' 로 바꾸거나 또는 지워주세요 )
// 		$option_item = get_item_options($it['it_id'], $it['it_option_subject'], 'div');

// 		// 추가 옵션 ( 기존의 tr td 태그로 가져오려면 'div' 를 '' 로 바꾸거나 또는 지워주세요 )
// 		$supply_item = get_item_supply($it['it_id'], $it['it_supply_subject'], 'div');
// 	}

// 	// 상품 선택옵션 수
// 	$option_count = 0;
// 	if ($it['it_option_subject']) {
// 		$temp = explode(',', $it['it_option_subject']);
// 		$option_count = count($temp);
// 	}

// 	// 상품 추가옵션 수
// 	$supply_count = 0;
// 	if ($it['it_supply_subject']) {
// 		$temp = explode(',', $it['it_supply_subject']);
// 		$supply_count = count($temp);
// 	}
// }

// TODO: 네이버페이?
// include_once(G5_SHOP_PATH.'/settle_naverpay.inc.php');

$it_item_type = '0';
if ($it['it_item_type'] != "") $it_item_type = $it['it_item_type'];
if (!$it['it_buy_min_qty']) $it['it_buy_min_qty'] = 1;

$view_detail_items = array();

if ($it['it_view_detail_items'] != "") {
	//detail view 설정
	$it_view_list_items = ',' . $it['it_view_detail_items'] . ',';

	$view_detail_items['view_it_name']  = (preg_match("/,상품명,/i", $it_view_list_items));
	$view_detail_items['view_it_basic']  = (preg_match("/,한줄설명,/i", $it_view_list_items));
	$view_detail_items['view_new']  = (preg_match("/,신상품,/i", $it_view_list_items));
	$view_detail_items['view_wish']  = preg_match("/,좋아요,/i", $it_view_list_items);

	$view_detail_items['view_it_sale']  = (preg_match("/,할인블릿,/i", $it_view_list_items));
	$view_detail_items['view_it_event']  = (preg_match("/,이벤트블릿,/i", $it_view_list_items));
	$view_detail_items['view_it_hit']  = (preg_match("/,인기블릿,/i", $it_view_list_items));

	$view_detail_items['view_it_share']  = (preg_match("/,공유하기,/i", $it_view_list_items));
	$view_detail_items['view_it_reviewcnt']  = (preg_match("/,리뷰수,/i", $it_view_list_items));

	$view_detail_items['view_it_sale_bprice']  = (preg_match("/,할인전금액,/i", $it_view_list_items));
	$view_detail_items['view_it_sale_price']  = (preg_match("/,할인가,할인율,/i", $it_view_list_items));

	$view_detail_items['view_it_price']  = (preg_match("/,최종 판매가,/i", $it_view_list_items));
	//인기블릿,쿠폰,판매가
} else {

	$view_detail_items['view_it_name'] = true;
	$view_detail_items['view_it_basic'] = true;
	$view_detail_items['view_wish'] = true;

	$view_detail_items['view_it_sale'] = true;
	$view_detail_items['view_it_event'] = true;
	$view_detail_items['view_it_hit'] = true;

	$view_detail_items['view_it_share'] = true;
	$view_detail_items['view_it_reviewcnt'] = true;

	$view_detail_items['view_it_sale_bprice'] = true;
	$view_detail_items['view_it_sale_price'] = true;

	$view_detail_items['view_it_price'] = true;

	$reg_time = gap_time(strtotime($it['it_time']), G5_SERVER_TIME);
	$view_detail_items['view_new'] = ($reg_time['days'] <= 7);
}

// 상품 구입폼 - OLD
// include_once($skin_dir . '/item.form.' . $it_item_type . '.skin.php');

// 상품옵션
$sub_sql = "SELECT * FROM lt_shop_item_sub WHERE it_id = '{$it['it_id']}'";
$sub_result = sql_query($sub_sql);
$sub_its = $sup_its = array();
$sub_its_count = $sup_its_count = 0;

for ($i = 0; $its = sql_fetch_array($sub_result); $i++) {
	if (!isset($sub_its[$its['its_option_subject']])) $sub_its[$its['its_option_subject']] = array();

	// 옵션 데이터
	$sql_io = "SELECT * FROM {$g5['g5_shop_item_option_table']}
				WHERE io_type = '0' AND it_id = '{$its['it_id']}' AND its_no = '{$its['its_no']}' AND io_use = '1' ORDER BY io_no ASC";
	$db_io = sql_query($sql_io);
	$tmp_its_io = array();
	for ($ii = 0; $io = sql_fetch_array($db_io); $ii++) {
		if (!isset($tmp_its_io[$io['io_id']])) $tmp_its_io[$io['io_id']] = $io;
	}
	$its['OPTIONS'] = $tmp_its_io;
	$sub_its[$its['its_option_subject']][] = $its;
	$sub_its_count++;

	// 추가구성 데이터
	if (!empty($its['its_supply_subject'])) {
		$tmp_sup_subjects = explode(',', $its['its_supply_subject']);
		foreach ($tmp_sup_subjects as $tmp_sup_subject) {
			$tmp_sup_sql = "SELECT * FROM {$g5['g5_shop_item_option_table']}
							WHERE io_type = '1' AND it_id = '{$its['it_id']}' AND its_no = '{$its['its_no']}' AND io_use = '1' AND io_id LIKE '{$tmp_sup_subject}%' ORDER BY io_no ASC";
			$db_tmp_sup = sql_query($tmp_sup_sql);

			if (!isset($sup_its[$tmp_sup_subject])) $sup_its[$tmp_sup_subject] = array();
			for ($j = 0; $tmp_sup = sql_fetch_array($db_tmp_sup); $j++) {
				$tmp_id = explode(chr(30), $tmp_sup['io_id']);
				$tmp_sup['name'] = $tmp_id[1];
				$sup_its[$tmp_sup_subject][] = $tmp_sup;
				$sup_its_count++;
			}
		}
	}
}
// 사이즈 통합 데이터
$sizeSql = "SELECT * FROM {$g5['g5_shop_item_option_table']} WHERE it_id = '{$it_id}' LIMIT 1";
$sizeOp = sql_fetch($sizeSql);
// $sizeOp['io_order_no']
$sizeSql = "SELECT a.*, b.io_hoching,b.io_id,b.io_no,b.its_no,b.io_price,it_soldout,io_stock_qty,it_stock_qty FROM {$g5['g5_shop_item_table']} a LEFT JOIN {$g5['g5_shop_item_option_table']} b ON (a.it_id = b.it_id) WHERE b.io_order_no = '{$sizeOp['io_order_no']}' AND it_use = 1 ORDER BY it_price ASC, it_size_info DESC ";
$sizeData = sql_query($sizeSql);
$sizeData2 = sql_query($sizeSql);
$sizeDataMo = sql_query($sizeSql);
$soldDate = sql_query($sizeSql);

$is_totalsoldout = 0;
$sdNum = 0;
$sdHoc = 0;
for($sd=0; $soid=sql_fetch_array($soldDate); $sd++) {
	$sdNum = $sdNum + 1; 
	if ($sdNum == 1 ) {
		if (is_numeric(substr($soid['io_hoching'],0,1)))  $sdHoc = 'n'.str_replace('*','',$soid['io_hoching']); else $sdHoc = $soid['io_hoching'];
	} 
	if (!$soid['it_soldout']  && $soid['io_stock_qty'] > 0 && $soid['it_stock_qty'] > 0) {
		$is_totalsoldout = 1;
	}
}
// $item_qa_count = $row['cnt']; 

if ($is_member) {
	$sql_pick = "SELECT it_id FROM {$g5['g5_shop_wish_table']} WHERE wi_type='item' AND mb_id='{$member['mb_id']}' AND it_id='{$it_id}' GROUP BY mb_id";
	$db_pick = sql_fetch($sql_pick);
}
$action_url =  G5_SHOP_URL.'/cartupdate.php?' . time();

require_once(G5_LIB_PATH . '/badge.lib.php');
$badgeObj = new badge();
$badgeObj->item = $it;
$badgeObj->makeHtml();

// 브랜드 정보
if (!empty($it['it_brand'])) {
	$sql_brand = "SELECT * FROM lt_brand WHERE br_name_en='{$it['it_brand']}'";
	$brand = sql_fetch($sql_brand);
}

$contents = include_once(G5_VIEW_PATH . "/product.detail.php");
include_once G5_LAYOUT_PATH . "/layout.php";
