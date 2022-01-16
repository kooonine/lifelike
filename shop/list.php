<?php
include_once('./_common.php');
require_once(G5_LIB_PATH . '/badge.lib.php');

$sql = " select * from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' and ca_use = '1'  ";
$ca = sql_fetch($sql);
if (!$ca['ca_id']) alert('등록된 분류가 없습니다.');

$create = false;
// 상품 리스트에서 다른 필드로 정렬을 하려면 아래의 배열 코드에서 해당 필드를 추가하세요.
if (isset($sort) && !in_array($sort, array('it_sum_qty', 'it_price', 'it_use_avg', 'it_use_cnt', 'it_update_time', 'disc', 'best'))) {
	$sort = '';
}
// 상품 출력순서가 있다면
switch ($sort) {
	case "best":
		$sortfield = 'it_sum_qty';
		$sortodr = 'desc';
		break;
	case "disc":
		$sortfield = 'discount';
		$sortodr = 'desc';
		break;
	case "new":
	default:
		$sortfield = 'it_time';
		$sortodr = 'desc';
		break;
}
$order_by = sprintf("%s %s, it_order, it_id desc", $sortfield, $sortodr);

// 총몇개 = 한줄에 몇개 * 몇줄
$items = $ca['ca_list_mod'] * $ca['ca_list_row'];
// 페이지가 없으면 첫 페이지 (1 페이지)

switch ($numberView) {
	case 20:
		$items = 20;
		$ca['ca_list_mod'] = 4;
		$ca['ca_list_row'] = 5;
		break;
	case 50:
		$items = 50;
		$ca['ca_list_mod'] = 4;
		$ca['ca_list_row'] = 12.5;
		break;
	case 100:
		$items = 100;
		$ca['ca_list_mod'] = 4;
		$ca['ca_list_row'] = 25;
		break;
}
if (empty($page)) {
	$page = 1;
	$create = true;
}
// 시작 레코드 구함
$from_record = ($page - 1) * $items;

$skin_file = G5_VIEW_PATH . "/product.list.item.php";

$list = new item_list($skin_file, $ca['ca_list_mod'], $ca['ca_list_row'], $ca['ca_img_width'], $ca['ca_img_height']);
$list_koo = new item_list($skin_file, $ca['ca_list_mod'], $ca['ca_list_row'], $ca['ca_img_width'], $ca['ca_img_height']);

$list->set_category($ca_id, 1);
$list->set_category($ca_id, 2);
if ($company_code) $list->set_category($company_code, 3);

$list_koo->set_category($ca_id, 1);
$list_koo->set_category($ca_id, 2);
if ($company_code) $list_koo->set_category($company_code, 3);

$list->set_is_page(true);
$list->set_order_by($order_by);
$list->set_from_record($from_record);
$list->set_view('it_img', true);
$list->set_view('it_id', false);
$list->set_view('it_name', true);
$list->set_view('it_basic', true);
$list->set_view('it_cust_price', true);
$list->set_view('it_price', true);
$list->set_view('it_icon', true);
$list->set_view('sns', true);
if ($create === true) $list->set_create(true);
if (!empty($mix)) $list->set_mix(true);
if (!empty($filter)) $list->set_filter($filter);

$list_koo->set_is_page(true);
$list_koo->set_order_by($order_by);
$list_koo->set_from_record($from_record);
$list_koo->set_view('it_img', true);
$list_koo->set_view('it_id', false);
$list_koo->set_view('it_name', true);
$list_koo->set_view('it_basic', true);
$list_koo->set_view('it_cust_price', true);
$list_koo->set_view('it_price', true);
$list_koo->set_view('it_icon', true);
$list_koo->set_view('sns', true);
if ($create === true) $list_koo->set_create(true);
if (!empty($mix)) $list_koo->set_mix(true);


if ($is_member) {
	$sql_wish = "SELECT GROUP_CONCAT(it_id) AS wishlist FROM {$g5['g5_shop_wish_table']} WHERE wi_type='item' AND mb_id='{$member['mb_id']}' GROUP BY mb_id";
	$db_wishlist = sql_fetch($sql_wish);
	$list->set_wish($db_wishlist['wishlist']);
	$list_koo->set_wish($db_wishlist['wishlist']);
}

$list_view = $list->out();

// where 된 전체 상품수
$total_count = $list->total_count;
// 전체 페이지 계산
$total_page  = ceil($total_count / $items);

$qstr .= 'ca_id=' . $ca_id;
if ($company_code) $qstr .= '$amp;company_code=' . $company_code;
$qstr .= '&amp;sort=' . $sort . '&amp;sortodr=' . $sortodr . '&amp;filter=' . $filter . '&amp;numberView=' . $numberView;
$paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');
// 기존소스 끝

// 필터 리스트
if (!empty($m_code) && ($m_code[0] == "10" || $m_code[0] == "20" || $m_code[0] == "30" || $m_code[0] == "40") && $ca_id !='104020') {
	$sql_filters = "SELECT * FROM `lt_shop_finditem` WHERE fi_status='Y'";
	if ($m_code[0] == "10" || $m_code[0] == "30") {
		$sql_filters .= " AND fi_id IN (1,2,3)"; // 사이즈 시즌 충전재
	} else if ($m_code[0] == "20") {
		if ($ca_id =='1020' || $ca_id =='102030') {
			$sql_filters .= " AND fi_id IN (1,2,3,4,5)";
		} else {
			$sql_filters .= " AND fi_id IN (1,4,5)";
		}
	} else if ($m_code[0] == "40")  {
		if ($ca_id =='1040' || $ca_id =='104010') {
			$sql_filters .= " AND fi_id IN (1,4,5)";
		}
	}
	

	$db_filters = sql_query($sql_filters);
	$filters = array();
	while (($fr = sql_fetch_array($db_filters)) != false) {
		$filters[$fr['fi_subject']] = array('COUNT' => 0);
		foreach (explode(',', $fr['fi_contents']) as $fc) {
			$filters[$fc] = array(
				'SUBJECT' => $fr['fi_subject'],
				'COUNT' => 0
			);
		}
	}
	// 필터 리스트 끝

	// 전체 결과 필터 카운트
	$list_view_koo = $list_koo->out_koo();
	$db_item_filters = sql_query("SELECT * FROM " . $list->get_view_id());


	while (($fi = sql_fetch_array($db_item_filters)) != false) {
		if (!empty($fi['filter'])) {
			foreach (explode(',', $fi['filter']) as $fc) {
				if (isset($filters[$fc])) {
					$filters[$fc]['COUNT']++;
					if (isset($filters[$fc]['SUBJECT'])) $filters[$filters[$fc]['SUBJECT']]['COUNT']++;
				}
			}
		}
	}
	// 필터 결과 카운트 끝
}

$g5['title'] = $ca['ca_name'] . ' 상품리스트';

$filter_view = include_once(G5_VIEW_PATH . "/product.filter.php");
$contents = include_once(G5_VIEW_PATH . "/product.list.php");

include_once G5_LAYOUT_PATH . "/layout.php";
