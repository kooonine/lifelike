<?php
include_once('./_common.php');
require_once(G5_LIB_PATH . '/badge.lib.php');

$br_id = strtoupper($br_id);
if (empty($br_id) || $br_id == 'PICK') {

    // 순서 수정전
    // $sql_brand = "SELECT br.*, (SELECT COUNT(*) FROM lt_shop_wish WHERE wi_type='brand' AND it_id=br.br_id ) AS PICKED FROM lt_brand AS br WHERE br_use=1 ORDER BY br_id";
    // 순서 수정후
    $sql_brand = "SELECT br.*, (SELECT COUNT(*) FROM lt_shop_wish WHERE wi_type='brand' AND it_id=br.br_id ) AS PICKED FROM lt_brand AS br WHERE br_use=1 ORDER BY br_num";

    $db_brand = sql_query($sql_brand);
    $brands = array();

    while (false != ($brand = sql_fetch_array($db_brand))) {
        if ($br_id == 'PICK') {
            if ((int) $brand['PICKED'] > 0) {
                $brands[] = $brand;
            }
        } else {
            $brands[] = $brand;
        }
    }

    $g5['title'] = '브랜드 리스트';
    $contents = include_once(G5_VIEW_PATH . "/brand.list.php");
} else {
    $sql = " select * from lt_brand where br_id = '$br_id' and br_use = '1' ";
    $brand = sql_fetch($sql);
    if (!$brand['br_id']) alert('등록된 브랜드가 없습니다.');

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
    // $items = $ca['ca_list_mod'] * $ca['ca_list_row'];
    $items = 20;
    // 페이지가 없으면 첫 페이지 (1 페이지)
    $brand_list_mod = 4;
    $brand_list_row = 5;
    switch ($numberView) {
        case 20:
            $items = 20;
            $brand_list_mod = 4;
            $brand_list_row = 5;
            break;
        case 50:
            $items = 50;
            $brand_list_mod = 4;
            $brand_list_row = 12.5;
            break;
        case 100:
            $items = 100;
            $brand_list_mod = 4;
            $brand_list_row = 25;
            break;
    }

    if (empty($page)) {
        $page = 1;
        $create = true;
    }
    // 시작 레코드 구함
    $from_record = ($page - 1) * $items;

    $skin_file = G5_VIEW_PATH . "/product.list.item.php";

    // $list = new item_list($skin_file, $ca['ca_list_mod'], $ca['ca_list_row'], $ca['ca_img_width'], $ca['ca_img_height']);
    $list = new item_list($skin_file, $brand_list_mod, $brand_list_row, 290, 290);
    // $list->set_category($ca_id, 1);
    // $list->set_category($ca_id, 2);
    // if ($company_code) $list->set_category($company_code, 3);
    $list->set_brand($brand);
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

    if ($is_member) {
        $sql_wish = "SELECT GROUP_CONCAT(it_id) AS wishlist FROM {$g5['g5_shop_wish_table']} WHERE wi_type='item' AND mb_id='{$member['mb_id']}' GROUP BY mb_id";
        $db_wishlist = sql_fetch($sql_wish);
        $list->set_wish($db_wishlist['wishlist']);
    }

    $list_view = $list->out_brand();

    // where 된 전체 상품수
    $total_count = $list->total_count;
    // 전체 페이지 계산
    $total_page  = ceil($total_count / $items);

    $qstr .= 'br_id=' . $br_id;
    if ($company_code) $qstr .= '$amp;company_code=' . $company_code;
    $qstr .= '&amp;sort=' . $sort . '&amp;sortodr=' . $sortodr . '&amp;filter=' . $filter . '&amp;numberView=' . $numberView;
    $paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');
    // 기존소스 끝

    // 필터 리스트
    $sql_filters = "SELECT * FROM `lt_shop_finditem` WHERE fi_status='B'";
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
    $db_item_filters = sql_query("SELECT * FROM " . $list->get_view_id());
    while (($fi = sql_fetch_array($db_item_filters)) != false) {
        if (!empty($fi['filter'])) {
            foreach (explode(',', $fi['filter']) as $fc) {
                $filters[$fc]['COUNT']++;
                if (isset($filters[$fc]['SUBJECT'])) $filters[$filters[$fc]['SUBJECT']]['COUNT']++;
            }
        }
    }
    // 필터 결과 카운트 끝

    $g5['title'] = $brand['br_name'] . ' 상품리스트';

    $filter_view = include_once(G5_VIEW_PATH . "/brand.filter.php");
    $contents = include_once(G5_VIEW_PATH . "/brand.list.php");
}

include_once G5_LAYOUT_PATH . "/layout.php";
