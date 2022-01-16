<?php
include_once('./_common.php');

header('Content-Type: application/json');

// 오류시 공히 Error 라고 처리하는 것은 회원정보가 있는지? 비밀번호가 틀린지? 를 알아보려는 해킹에 대비한것

// QUERY 문에 공통적으로 들어가는 내용
// 상품명에 검색어가 포한된것과 상품판매가능인것만
$sql_common = " from {$g5['g5_shop_item_table']} a, {$g5['g5_shop_category_table']} b ";

$where = array();
$where[] = " (a.ca_id = b.ca_id and a.it_use=1 and b.ca_use=1 and LEFT(a.ca_id, 4) != 1020 and a.it_total_size =1 ) ";

$search_text = urldecode($_POST['search_text']);
$q       = utf8_strcut(get_search_string(trim($search_text)), 30, "");
$type = $_POST['type'];

if ($q) {
    $search_all = true;

    $arr = explode(" ", $q);
    $detail_where = array();
    for ($i = 0; $i < count($arr); $i++) {
        $word = trim($arr[$i]);
        if (!$word) continue;

        $concat = array();
        $concat[] = "a.it_name";
        $concat[] = "a.it_explan2";
        $concat[] = "a.it_search_word";
        $concat[] = "a.it_basic";

        $concat_fields = "concat(" . implode(",' ',", $concat) . ")";
        $detail_where[] = $concat_fields . " like '%$word%' ";

        // 인기검색어
        if ($type == 'search') {
            insert_popular($concat, $word);
        }
    }

    $where[] = "(" . implode(" and ", $detail_where) . ")";
}
$sql_where = " where " . implode(" and ", $where);

$sql = "select distinct a.it_id as it_id, a.it_name as it_name, a.it_img1 as it_img1, a.it_basic as it_basic, a.it_price as it_price $sql_common $sql_where order by it_order asc , it_name asc limit 5";
$result  = sql_query($sql);


$view_text = '';
if ($type == 'recommend') {

    $view_text .= '<ul>';
    while ($row = sql_fetch_array($result)) {
        $view_text .= '<li><a href="' . G5_SHOP_URL . '/item.php?it_id=' . $row['it_id'] . '">' . $row['it_name'] . '</a></li>';
    }
    $view_text .= '</ul>';
} else if ($type == 'search') {

    while ($row = sql_fetch_array($result)) {


        $link_url = G5_URL . '/shop/item.php?it_id=' . $row['it_id'];
        $img_data = $row['it_img1'];
        $img_file = G5_DATA_PATH . '/item/' . $img_data;

        if ($img_data && file_exists($img_file)) {
            $img_url = G5_DATA_URL . '/item/' . $img_data;
        } else {
            $img_url = G5_MOBILE_URL . '/img/theme_img.jpg';
        }
        $view_text .= '<li>';
        $view_text .= '   <a href="' . $link_url . '">';
        $view_text .= '     <div class="photo">';
        $view_text .= '         <img src="' . $img_url . '" alt="">';
        $view_text .= '     </div>';
        $view_text .= '         <div class="cont">';
        $view_text .= '             <div class="inner">';
        $view_text .= '                 <strong class="title bold ellipsis">' . $row['it_name'] . '</strong>';
        $view_text .= '                 <span class="text ellipsis">' . $row['it_basic'] . '</span>';
        $view_text .= '                 <span class="price">' . number_format($row['it_price']) . ' 원</span>';
        $view_text .= '             </div>';
        $view_text .= '         </div>';
        $view_text .= '     </a>';
        $view_text .= ' </li>';
    }
}

$result2 = array("view_text" => $view_text);
$output =  json_encode($result2);
// 출력
echo  urldecode($output);
