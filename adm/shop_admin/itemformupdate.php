<?php
$sub_menu = '400300';
include_once('./_common.php');

$auth_sub_menu = $auth[substr($sub_menu,0,2)];
if ($is_admin == "brand") $auth_sub_menu = $auth['92'];

if ($w == '' || $w == 'u')
    auth_check($auth_sub_menu, "w");
else if ($w == 'd')
    auth_check($auth_sub_menu, "d");

check_admin_token();

@mkdir(G5_DATA_PATH . "/item", G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH . "/item", G5_DIR_PERMISSION);

// input vars 체크
check_input_vars();

$ca_id = isset($ca_id) ? preg_replace('/[^0-9a-z]/i', '', $ca_id) : '';
$ca_id2 = isset($ca_id2) ? preg_replace('/[^0-9a-z]/i', '', $ca_id2) : '';
$ca_id3 = isset($ca_id3) ? preg_replace('/[^0-9a-z]/i', '', $ca_id3) : '';

// 파일정보
if ($w == "u") {
    $sql = " select it_img1, it_img2, it_img3, it_img4, it_img5, it_img6, it_img7, it_img8, it_img9, it_img10
                from {$g5['g5_shop_item_table']}
                where it_id = '$it_id' ";
    $file = sql_fetch($sql);

    $it_img1    = $file['it_img1'];
    $it_img2    = $file['it_img2'];
    $it_img3    = $file['it_img3'];
    $it_img4    = $file['it_img4'];
    $it_img5    = $file['it_img5'];
    $it_img6    = $file['it_img6'];
    $it_img7    = $file['it_img7'];
    $it_img8    = $file['it_img8'];
    $it_img9    = $file['it_img9'];
    $it_img10   = $file['it_img10'];
}

$it_img_dir = G5_DATA_PATH . '/item';

// 파일삭제
if ($w == 'u' && $it_img1 && $orgit_img1 == '') {
    $file_img1 = $it_img_dir . '/' . $it_img1;
    @unlink($file_img1);
    delete_item_thumbnail(dirname($file_img1), basename($file_img1));
    $it_img1 = '';
}

if ($w == 'u' && $it_img2 && $orgit_img2 == '') {
    $file_img2 = $it_img_dir . '/' . $it_img2;
    @unlink($file_img2);
    delete_item_thumbnail(dirname($file_img2), basename($file_img2));
    $it_img2 = '';
}
if ($w == 'u' && $it_img3 && $orgit_img3 == '') {
    $file_img3 = $it_img_dir . '/' . $it_img3;
    @unlink($file_img3);
    delete_item_thumbnail(dirname($file_img3), basename($file_img3));
    $it_img3 = '';
}
if ($w == 'u' && $it_img4 && $orgit_img4 == '') {
    $file_img4 = $it_img_dir . '/' . $it_img4;
    @unlink($file_img4);
    delete_item_thumbnail(dirname($file_img4), basename($file_img4));
    $it_img4 = '';
}
if ($w == 'u' && $it_img5 && $orgit_img5 == '') {
    $file_img5 = $it_img_dir . '/' . $it_img5;
    @unlink($file_img5);
    delete_item_thumbnail(dirname($file_img5), basename($file_img5));
    $it_img5 = '';
}

if ($w == 'u' && $it_img6 && $orgit_img6 == '') {
    $file_img6 = $it_img_dir . '/' . $it_img6;
    @unlink($file_img6);
    delete_item_thumbnail(dirname($file_img6), basename($file_img6));
    $it_img6 = '';
}
if ($w == 'u' && $it_img7 && $orgit_img7 == '') {
    $file_img7 = $it_img_dir . '/' . $it_img7;
    @unlink($file_img7);
    delete_item_thumbnail(dirname($file_img7), basename($file_img7));
    $it_img7 = '';
}
if ($w == 'u' && $it_img8 && $orgit_img8 == '') {
    $file_img8 = $it_img_dir . '/' . $it_img8;
    @unlink($file_img8);
    delete_item_thumbnail(dirname($file_img8), basename($file_img8));
    $it_img8 = '';
}
if ($w == 'u' && $it_img9 && $orgit_img9 == '') {
    $file_img9 = $it_img_dir . '/' . $it_img9;
    @unlink($file_img9);
    delete_item_thumbnail(dirname($file_img9), basename($file_img9));
    $it_img9 = '';
}
if ($w == 'u' && $it_img10 && $orgit_img10 == '') {
    $file_img10 = $it_img_dir . '/' . $it_img10;
    @unlink($file_img10);
    delete_item_thumbnail(dirname($file_img10), basename($file_img10));
    $it_img10 = '';
}

// 이미지업로드
if ($_FILES['it_img1']['name']) {
    if ($w == 'u' && $it_img1) {
        $file_img1 = $it_img_dir . '/' . $it_img1;
        @unlink($file_img1);
        delete_item_thumbnail(dirname($file_img1), basename($file_img1));
    }
    $it_img1 = it_img_upload($_FILES['it_img1']['tmp_name'], $_FILES['it_img1']['name'], $it_img_dir . '/' . $it_id);
}
if ($_FILES['it_img2']['name']) {
    if ($w == 'u' && $it_img2) {
        $file_img2 = $it_img_dir . '/' . $it_img2;
        @unlink($file_img2);
        delete_item_thumbnail(dirname($file_img2), basename($file_img2));
    }
    $it_img2 = it_img_upload($_FILES['it_img2']['tmp_name'], $_FILES['it_img2']['name'], $it_img_dir . '/' . $it_id);
}
if ($_FILES['it_img3']['name']) {
    if ($w == 'u' && $it_img3) {
        $file_img3 = $it_img_dir . '/' . $it_img3;
        @unlink($file_img3);
        delete_item_thumbnail(dirname($file_img3), basename($file_img3));
    }
    $it_img3 = it_img_upload($_FILES['it_img3']['tmp_name'], $_FILES['it_img3']['name'], $it_img_dir . '/' . $it_id);
}
if ($_FILES['it_img4']['name']) {
    if ($w == 'u' && $it_img4) {
        $file_img4 = $it_img_dir . '/' . $it_img4;
        @unlink($file_img4);
        delete_item_thumbnail(dirname($file_img4), basename($file_img4));
    }
    $it_img4 = it_img_upload($_FILES['it_img4']['tmp_name'], $_FILES['it_img4']['name'], $it_img_dir . '/' . $it_id);
}
if ($_FILES['it_img5']['name']) {
    if ($w == 'u' && $it_img5) {
        $file_img5 = $it_img_dir . '/' . $it_img5;
        @unlink($file_img5);
        delete_item_thumbnail(dirname($file_img5), basename($file_img5));
    }
    $it_img5 = it_img_upload($_FILES['it_img5']['tmp_name'], $_FILES['it_img5']['name'], $it_img_dir . '/' . $it_id);
}
if ($_FILES['it_img6']['name']) {
    if ($w == 'u' && $it_img6) {
        $file_img6 = $it_img_dir . '/' . $it_img6;
        @unlink($file_img6);
        delete_item_thumbnail(dirname($file_img6), basename($file_img6));
    }
    $it_img6 = it_img_upload($_FILES['it_img6']['tmp_name'], $_FILES['it_img6']['name'], $it_img_dir . '/' . $it_id);
}
if ($_FILES['it_img7']['name']) {
    if ($w == 'u' && $it_img7) {
        $file_img7 = $it_img_dir . '/' . $it_img7;
        @unlink($file_img7);
        delete_item_thumbnail(dirname($file_img7), basename($file_img7));
    }
    $it_img7 = it_img_upload($_FILES['it_img7']['tmp_name'], $_FILES['it_img7']['name'], $it_img_dir . '/' . $it_id);
}
if ($_FILES['it_img8']['name']) {
    if ($w == 'u' && $it_img8) {
        $file_img8 = $it_img_dir . '/' . $it_img8;
        @unlink($file_img8);
        delete_item_thumbnail(dirname($file_img8), basename($file_img8));
    }
    $it_img8 = it_img_upload($_FILES['it_img8']['tmp_name'], $_FILES['it_img8']['name'], $it_img_dir . '/' . $it_id);
}
if ($_FILES['it_img9']['name']) {
    if ($w == 'u' && $it_img9) {
        $file_img9 = $it_img_dir . '/' . $it_img9;
        @unlink($file_img9);
        delete_item_thumbnail(dirname($file_img9), basename($file_img9));
    }
    $it_img9 = it_img_upload($_FILES['it_img9']['tmp_name'], $_FILES['it_img9']['name'], $it_img_dir . '/' . $it_id);
}
if ($_FILES['it_img10']['name']) {
    if ($w == 'u' && $it_img10) {
        $file_img10 = $it_img_dir . '/' . $it_img10;
        @unlink($file_img10);
        delete_item_thumbnail(dirname($file_img10), basename($file_img10));
    }
    $it_img10 = it_img_upload($_FILES['it_img10']['tmp_name'], $_FILES['it_img10']['name'], $it_img_dir . '/' . $it_id);
}

if ($w == "" || $w == "u") {
    // 다음 입력을 위해서 옵션값을 쿠키로 한달동안 저장함
    //@setcookie("ck_ca_id",  $ca_id,  time() + 86400*31, $default[de_cookie_dir], $default[de_cookie_domain]);
    //@setcookie("ck_maker",  stripslashes($it_maker),  time() + 86400*31, $default[de_cookie_dir], $default[de_cookie_domain]);
    //@setcookie("ck_origin", stripslashes($it_origin), time() + 86400*31, $default[de_cookie_dir], $default[de_cookie_domain]);
    @set_cookie("ck_ca_id", $ca_id, time() + 86400 * 31);
    //@set_cookie("ck_ca_id2", $ca_id2, time() + 86400*31);
    //@set_cookie("ck_ca_id3", $ca_id3, time() + 86400*31);
    @set_cookie("ck_maker", stripslashes($it_maker), time() + 86400 * 31);
    @set_cookie("ck_origin", stripslashes($it_origin), time() + 86400 * 31);
}

// 관련상품을 우선 삭제함
sql_query(" delete from {$g5['g5_shop_item_relation_table']} where it_id = '$it_id' ");

// 관련상품의 반대도 삭제
sql_query(" delete from {$g5['g5_shop_item_relation_table']} where it_id2 = '$it_id' ");


if (!$it_stock_qty) $it_stock_qty = 0;

$option_count = (isset($_POST['opt_id']) && is_array($_POST['opt_id'])) ? count($_POST['opt_id']) : array();
if ($option_count) {
    // 옵션명
    $opt1_cnt = 0;
    $io_no_array = array();

    for ($i = 0; $i < $option_count; $i++) {
        $_POST['opt_id'][$i] = preg_replace(G5_OPTION_ID_FILTER, '', $_POST['opt_id'][$i]);
        $opt1_cnt++;

        $it_stock_qty += $_POST['opt_stock_qty'][$i];

        if ($_POST['io_no'][$i] && $_POST['io_no'][$i] != '') {
            $io_no_array[] = $_POST['io_no'][$i];
        }
    }

    $io_no_list = implode("','", $io_no_array);

    // 선택옵션
    sql_query(" delete from {$g5['g5_shop_item_option_table']} where io_type = '0' and it_id = '$it_id' and io_no not in ('$io_no_list') "); // 기존선택옵션삭제
} else {
    // 선택옵션
    sql_query(" delete from {$g5['g5_shop_item_option_table']} where io_type = '0' and it_id = '$it_id' "); // 기존선택옵션삭제
}

// 추가옵션
sql_query(" delete from {$g5['g5_shop_item_option_table']} where io_type = '1' and it_id = '$it_id' "); // 기존추가옵션삭제

$supply_count = (isset($_POST['spl_id']) && is_array($_POST['spl_id'])) ? count($_POST['spl_id']) : array();
if ($supply_count) {
    // 추가옵션명
    $arr_spl = array();
    for ($i = 0; $i < $supply_count; $i++) {
        $_POST['spl_id'][$i] = preg_replace(G5_OPTION_ID_FILTER, '', $_POST['spl_id'][$i]);

        $spl_val = explode(chr(30), $_POST['spl_id'][$i]);
        if (!in_array($spl_val[0], $arr_spl))
            $arr_spl[] = $spl_val[0];
    }

    $it_supply_subject = implode(',', $arr_spl);
}

// 상품요약정보
$value_array = array();
$it_info_value = "";
$ii_article_count = (isset($_POST['ii_article']) && is_array($_POST['ii_article'])) ? count($_POST['ii_article']) : 0;
if ($ii_article_count) {
    for ($i = 0; $i < $ii_article_count; $i++) {

        $key = $_POST['ii_article'][$i];
        $val = $_POST['ii_value'][$i];

        $article = array("name" => $key, "value" => $val);
        $value_array[] = $article;
    }
    $it_info_value = json_encode_raw($value_array, JSON_UNESCAPED_UNICODE);
}

// 내게맞는 상품 찾기
sql_query(" delete from lt_shop_item_finditem where it_id = '$it_id' "); // 기존 내게맞는 상품 찾기 삭제

$fi_id_count = (isset($_POST['fi_id']) && is_array($_POST['fi_id'])) ? count($_POST['fi_id']) : array();
if ($fi_id_count) {
    $it_info_finditem_array = array();
    for ($i = 0; $i < $fi_id_count; $i++) {
        $fi_id = $_POST['fi_id'][$i];
        $fi_subject = $_POST['fi_subject'][$i];
        $fi_contents = array();

        if (isset($_POST['fi_contents_' . $fi_id]) && is_array($_POST['fi_contents_' . $fi_id])) {
            $fi_contents = implode(',', $_POST['fi_contents_' . $fi_id]);
        }
        $it_info_finditem_array[] = array("fi_id" => $_POST['fi_id'][$i], "fi_subject" => $fi_subject, "fi_contents" => $fi_contents);
    }
    $it_info_finditem = json_encode_raw($it_info_finditem_array, JSON_UNESCAPED_UNICODE);
}

// 포인트 비율 값 체크
$it_point = 0;
if ($it_point_type == '0') {
    $it_point = (int) $it_point0;
} else if ($it_point_type == '2') {
    if ($it_point2 > 99) {
        alert("포인트 비율을 0과 99 사이의 값으로 입력해 주십시오.");
    }
    $it_point = (int) $it_point2;
} else if ($it_point_type == '3') {
    $it_point = $default['de_point_percent'];
}


$it_name = strip_tags(trim($_POST['it_name']));


// KVE-2019-0708
$check_sanitize_keys = array(
    'it_order',             // 출력순서
    'it_maker',             // 제조사
    'it_origin',            // 원산지
    'it_brand',             // 브랜드
    'it_model',             // 모델
    'it_tel_inq',           // 전화문의
    'it_use',               // 판매가능
    'it_nocoupon',          // 쿠폰적용안함
    'ec_mall_pid',          // 네이버쇼핑 상품ID
    'it_sell_email',        // 판매자 e-mail
    'it_price',             // 판매가격
    'it_cust_price',        // 시중가격
    'it_point_type',        // 포인트 유형
    'it_supply_point',      // 추가옵션상품 포인트
    'it_soldout',           // 상품품절
    'it_stock_sms',         // 재입고SMS 알림
    'it_stock_qty',         // 재고수량
    'it_noti_qty',          // 재고 통보수량
    'it_buy_min_qty',       // 최소구매수량
    'it_notax',             // 상품과세 유형
    'it_sc_type',           // 배송비 유형
    'it_sc_method',         // 배송비 결제
    'it_sc_price',          // 기본배송비
    'it_sc_minimum',        // 배송비 상세조건
    'it_period'             // 판매기간
);
foreach ($check_sanitize_keys as $key) {
    $$key = isset($_POST[$key]) ? strip_tags($_POST[$key]) : '';
}

if ($it_name == "")
    alert("상품명을 입력해 주십시오.");

$it_price = preg_replace('/[^0-9]/', '', $it_price);
$it_rental_price = preg_replace('/[^0-9]/', '', $it_rental_price);
$it_discount_price = preg_replace('/[^0-9]/', '', $it_discount_price);

if ($rdo_it_view_list_items == "0") {
    $it_view_list_items = '';
} else {
    $it_view_list_items = (isset($_POST['it_view_list_items']) && is_array($_POST['it_view_list_items'])) ? implode(',', $it_view_list_items) : '';
}
if ($rdo_it_view_detail_items == "0") {
    $it_view_detail_items = '';
} else {
    $it_view_detail_items = (isset($_POST['it_view_detail_items']) && is_array($_POST['it_view_detail_items'])) ? implode(',', $it_view_detail_items) : '';
}
$all_it_option_subject = (isset($_POST['it_option_subject']) && is_array($_POST['it_option_subject'])) ? implode(',', $it_option_subject) : '';

if ($it_sc_type == "0") {
    //기본설정
    if ($is_admin == "brand") {
        //브랜드는 브랜드설정으로 처리
        $cp = sql_fetch("select * from lt_member_company where mb_id = '{$member['mb_id']}' ");

        $it_send_type  = $cp['cp_send_type'];
        $it_send_term_start = $cp['cp_send_term_start'];
        $it_send_term_end = $cp['cp_send_term_end'];

        $it_sc_minimum = $cp['cp_send_cost_limit'];
        $it_sc_price = $cp['cp_send_cost_list'];

        $it_send_condition = $cp['cp_send_condition'];
        $it_sc_method = $cp['cp_send_prepayment'];

        $it_individual_costs_use = '0';

        $it_delivery_company = $cp['cp_delivery_company'];
        $it_return_costs = $cp['cp_return_costs'];
        $it_roundtrip_costs = $cp['cp_roundtrip_costs'];

        $it_return_zip = $cp['cp_return_zip'];
        $it_return_address1 = $cp['cp_return_address1'];
        $it_return_address2 = $cp['cp_return_address2'];
    } else {
        $it_send_type  = $default['de_send_type'];
        $it_send_term_start = $default['de_send_term_start'];
        $it_send_term_end = $default['de_send_term_end'];

        $it_sc_minimum = $default['de_send_cost_limit'];
        $it_sc_price = $default['de_send_cost_list'];

        $it_send_condition = $default['de_send_condition'];
        $it_sc_method = $default['de_send_prepayment'];

        $it_individual_costs_use = '0';

        $it_delivery_company = $default['de_delivery_company'];
        $it_return_costs = $default['de_return_costs'];
        $it_roundtrip_costs = $default['de_roundtrip_costs'];

        $it_return_zip = $default['de_return_zip'];
        $it_return_address1 = $default['de_return_address1'];
        $it_return_address2 = $default['de_return_address2'];
    }
}
$sql_common = " ca_id               = '$ca_id',
                ca_id2              = '$ca_id2',
                ca_id3              = '$ca_id3',
                it_skin             = '$it_skin',
                it_mobile_skin      = '$it_mobile_skin',
                it_name             = '$it_name',
                it_maker            = '$it_maker',
                it_origin           = '$it_origin',
                it_brand            = '$it_brand',
                it_model            = '$it_model',
                it_option_subject   = '$all_it_option_subject',
                it_supply_subject   = '$it_supply_subject',
                it_type1            = '$it_type1',
                it_type2            = '$it_type2',
                it_type3            = '$it_type3',
                it_type4            = '$it_type4',
                it_type5            = '$it_type5',
                it_basic            = '$it_basic',
                it_explan           = '$it_explan',
                it_explan2          = '" . strip_tags(trim($_POST['it_explan'])) . "',
                it_mobile_explan    = '$it_mobile_explan',
                it_mobile_explan_use = '$it_mobile_explan_use',
                it_cust_price       = '$it_cust_price',
                it_price            = '$it_price',
                it_rental_price     = '$it_rental_price',
                it_discount_price   = '$it_discount_price',
                it_point            = '$it_point',
                it_point_type       = '$it_point_type',
                it_supply_point     = '0',
                it_notax            = '0',
                it_sell_email       = '',
                it_use              = '$it_use',
                it_nocoupon         = '$it_nocoupon',
                it_soldout          = '0',
                it_stock_qty        = '$it_stock_qty',
                it_stock_sms        = '0',
                it_noti_qty         = '0',
                it_sc_type          = '$it_sc_type',
                it_sc_method        = '$it_sc_method',
                it_sc_price         = '$it_sc_price',
                it_sc_minimum       = '$it_sc_minimum',
                it_sc_qty           = '0',
                it_buy_min_qty      = '$it_buy_min_qty',
                it_buy_max_qty      = '$it_buy_max_qty',
                it_head_html        = '',
                it_tail_html        = '',
                it_mobile_head_html = '',
                it_mobile_tail_html = '',
                it_ip               = '{$_SERVER['REMOTE_ADDR']}',
                it_order            = '$it_order',
                it_tel_inq          = '0',
                it_info_gubun       = '$it_info_gubun',
                it_info_value       = '$it_info_value',
                it_shop_memo        = '$it_shop_memo',
                ec_mall_pid         = '',
                it_img1             = '$it_img1',
                it_img2             = '$it_img2',
                it_img3             = '$it_img3',
                it_img4             = '$it_img4',
                it_img5             = '$it_img5',
                it_img6             = '$it_img6',
                it_img7             = '$it_img7',
                it_img8             = '$it_img8',
                it_img9             = '$it_img9',
                it_img10            = '$it_img10',

                it_item_type        = '$it_item_type',
                it_item_rental_month = '$it_item_rental_month',
                it_search_word = '$it_search_word',
                it_level_sell = '$it_level_sell',
                it_point_only = '$it_point_only',
                it_point_use = '$it_point_use',
                it_use_use = '$it_use_use',
                it_review_use = '$it_review_use',

                it_view_list_items = '$it_view_list_items',
                it_view_detail_items = '$it_view_detail_items',

                it_send_type ='$it_send_type',
                it_send_term_start ='$it_send_term_start',
                it_send_term_end ='$it_send_term_end',
                it_send_condition ='$it_send_condition',
                it_individual_costs_use ='$it_individual_costs_use',
                
                it_delivery_company ='$it_delivery_company',
                it_return_costs ='$it_return_costs',
                it_roundtrip_costs ='$it_roundtrip_costs',
                it_return_zip ='$it_return_zip',
                it_return_address1 ='$it_return_address1',
                it_return_address2 ='$it_return_address2',
                it_info_finditem = '$it_info_finditem',
                it_status = '$it_status',
                it_period = '$it_period'

                ";

$current_soldout = 0;

if ($w == "") {
    $it_id = $_POST['it_id'];

    if (!trim($it_id)) {
        //alert('상품 코드가 없으므로 상품을 추가하실 수 없습니다.');
        //상품코드 자동생성
    }

    $t_it_id = preg_replace("/[A-Za-z0-9\-_]/", "", $it_id);
    if ($t_it_id)
        alert('상품 코드는 영문자, 숫자, -, _ 만 사용할 수 있습니다.');

    $sql_common .= " , it_time = '" . G5_TIME_YMDHIS . "' ";
    $sql_common .= " , it_update_time = '" . G5_TIME_YMDHIS . "' ";
    $sql = " insert {$g5['g5_shop_item_table']}
                set it_id = '$it_id',
					$sql_common	";
    sql_query($sql);
} else if ($w == "u") {
    $current_soldout = sql_fetch("SELECT it_id,it_name,it_soldout FROM " . $g5['g5_shop_item_table'] . " WHERE it_id='" . $it_id . "'");

    $sql_common .= " , it_update_time = '" . G5_TIME_YMDHIS . "' ";
    $sql_common .= " , it_modify_date = '" . G5_TIME_YMDHIS . "' ";
    $sql = " update {$g5['g5_shop_item_table']}
                set $sql_common
              where it_id = '$it_id' ";
    sql_query($sql);
}
/*
else if ($w == "d")
{
    if ($is_admin != 'super')
    {
        $sql = " select it_id from {$g5['g5_shop_item_table']} a, {$g5['g5_shop_category_table']} b
                  where a.it_id = '$it_id'
                    and a.ca_id = b.ca_id
                    and b.ca_mb_id = '{$member['mb_id']}' ";
        $row = sql_fetch($sql);
        if (!$row['it_id'])
            alert("\'{$member['mb_id']}\' 님께서 삭제 할 권한이 없는 상품입니다.");
    }

    itemdelete($it_id);
}
*/

if ($w == "" || $w == "u") {
    // 관련상품 등록
    $it_id2 = explode(",", $it_list);
    for ($i = 0; $i < count($it_id2); $i++) {
        if (trim($it_id2[$i])) {
            $sql = " insert into {$g5['g5_shop_item_relation_table']}
                        set it_id  = '$it_id',
                            it_id2 = '$it_id2[$i]',
                            ir_no = '$i' ";
            sql_query($sql, false);

            // 관련상품의 반대로도 등록
            $sql = " insert into {$g5['g5_shop_item_relation_table']}
                        set it_id  = '$it_id2[$i]',
                            it_id2 = '$it_id',
                            ir_no = '$i' ";
            sql_query($sql, false);
        }
    }
}

$it_stock_qty = 0;
$its_sap_code_count = (isset($_POST['its_sap_code']) && is_array($_POST['its_sap_code'])) ? count($_POST['its_sap_code']) : array();

for ($s = 0; $s < $its_sap_code_count; $s++) {

    $its_price[$s] = preg_replace('/[^0-9]/', '', $its_price[$s]);
    $its_final_price[$s] = preg_replace('/[^0-9]/', '', $its_final_price[$s]);
    $its_rental_price[$s] = preg_replace('/[^0-9]/', '', $its_rental_price[$s]);
    $its_final_rental_price[$s] = preg_replace('/[^0-9]/', '', $its_final_rental_price[$s]);

    if ($its_discount1[$s]) $its_discount[$s] = preg_replace('/[^0-9]/', '', $its_discount1[$s]);
    else if ($its_discount2[$s]) $its_discount[$s] = preg_replace('/[^0-9]/', '', $its_discount2[$s]);

    $its_free_laundry_delivery_price[$s] = preg_replace('/[^0-9]/', '', $its_free_laundry_delivery_price[$s]);

    $its_laundry_price[$s] = preg_replace('/[^0-9]/', '', $its_laundry_price[$s]);
    $its_laundry_delivery_price[$s] = preg_replace('/[^0-9]/', '', $its_laundry_delivery_price[$s]);

    $its_laundrykeep_lprice[$s] = preg_replace('/[^0-9]/', '', $its_laundrykeep_lprice[$s]);
    $its_laundrykeep_kprice[$s] = preg_replace('/[^0-9]/', '', $its_laundrykeep_kprice[$s]);
    $its_laundrykeep_delivery_price[$s] = preg_replace('/[^0-9]/', '', $its_laundrykeep_delivery_price[$s]);

    $its_repair_price[$s] = preg_replace('/[^0-9]/', '', $its_repair_price[$s]);
    $its_repair_delivery_price[$s] = preg_replace('/[^0-9]/', '', $its_repair_delivery_price[$s]);

    $its_zbox[$s] = $its_zbox[$s];

    $its_zbox_data = explode(",", $its_zbox[$s]);
    $its_zbox_name[$s] = ($its_zbox[$s]) ? $its_zbox_data[0] : "";
    $its_zbox_price[$s] = preg_replace('/[^0-9]/', '', $its_zbox_price[$s]);

    //SUBID
    $subID = $_POST['itscnt'][$s];
    $its_discount_type[$s] = $_POST['its_discount_type' . $subID];
    $its_laundrykeep_use[$s] = $_POST['its_laundrykeep_use' . $subID];
    $its_laundry_use[$s] = $_POST['its_laundry_use' . $subID];
    $its_repair_use[$s] = $_POST['its_repair_use' . $subID];

    if ($supply_count) {
        // 추가옵션명
        $arr_spl = array();
        for ($i = 0; $i < $supply_count; $i++) {
            if ($subID == $_POST['spl_subid'][$i]) {
                $_POST['spl_id'][$i] = preg_replace(G5_OPTION_ID_FILTER, '', $_POST['spl_id'][$i]);

                $spl_val = explode(chr(30), $_POST['spl_id'][$i]);
                if (!in_array($spl_val[0], $arr_spl))
                    $arr_spl[] = $spl_val[0];
            }
        }

        $it_supply_subject = implode(',', $arr_spl);
    }


    $sql_common = " it_id = '$it_id',
                    its_sap_code = '$its_sap_code[$s]',
                    its_order_no = '$its_order_no[$s]',
                    its_item = '$its_item[$s]',
                    its_rental_price = '$its_rental_price[$s]',
                    its_price = '$its_price[$s]',
                    its_option_subject = '$it_option_subject[$s]',
                    its_supply_subject = '$it_supply_subject',
                    its_final_price = '$its_final_price[$s]',
                    its_final_rental_price = '$its_final_rental_price[$s]',
                    its_discount_type = '$its_discount_type[$s]',
                    its_discount = '$its_discount[$s]',
                    its_free_laundry = '$its_free_laundry[$s]',
                    its_free_laundry_delivery_price = '$its_free_laundry_delivery_price[$s]',
                    its_laundry_use = '$its_laundry_use[$s]',
                    its_laundry_price = '$its_laundry_price[$s]',
                    its_laundry_delivery_price = '$its_laundry_delivery_price[$s]',
                    its_laundrykeep_use = '$its_laundrykeep_use[$s]',
                    its_laundrykeep_lprice = '$its_laundrykeep_lprice[$s]',
                    its_laundrykeep_kprice = '$its_laundrykeep_kprice[$s]',
                    its_laundrykeep_delivery_price = '$its_laundrykeep_delivery_price[$s]',
                    its_repair_use = '$its_repair_use[$s]',
                    its_repair_price = '$its_repair_price[$s]',
                    its_repair_delivery_price = '$its_repair_delivery_price[$s]',
                    its_zbox_name = '$its_zbox_name[$s]',
                    its_zbox_price = '$its_zbox_price[$s]'
                    ";

    if ($w == "" || $its_no[$s] == "") {

        $sql_common .= " , its_time = '" . G5_TIME_YMDHIS . "' ";
        $sql_common .= " , its_update_time = '" . G5_TIME_YMDHIS . "' ";

        $sql = " insert lt_shop_item_sub
                    set $sql_common	";
        sql_query($sql);

        $its_no[$s] = sql_insert_id();
    } else if ($w == "u" && $its_no[$s] != "") {
        $sql_common .= " , its_update_time = '" . G5_TIME_YMDHIS . "' ";

        $sql = " update lt_shop_item_sub
                set $sql_common
              where its_no = '$its_no[$s]' ";
        sql_query($sql);
    }

    //고정옵션등록
    for ($i = 0; $i < $option_count; $i++) {

        //패키지상품 처리를 위해 동일 Subid 일때 만 처리
        if ($subID == $_POST['io_subid'][$i]) {
            if ($_POST['io_no'][$i] && $_POST['io_no'][$i] != '') {
                $sql = " update {$g5['g5_shop_item_option_table']}
                            set   io_id = '{$_POST['opt_id'][$i]}'
                                , io_price = '{$_POST['opt_price'][$i]}'
                                , io_stock_qty = '{$_POST['opt_stock_qty'][$i]}'
                                , io_noti_qty = '{$_POST['opt_noti_qty'][$i]}'
                                , io_use = '{$_POST['opt_use'][$i]}'
                            where io_no = '{$_POST['io_no'][$i]}'
                            ";

                sql_query($sql);

                $sqlItem = " update {$g5['g5_shop_item_table']}
                set   it_soldout = '{$_POST['opt_use2'][$i]}'
                where it_id = '{$it_id}'
                ";
                sql_query($sqlItem);
            } else {
                $sql = " INSERT INTO {$g5['g5_shop_item_option_table']}
                    (io_id, io_type, it_id ,io_price, io_stock_qty, io_noti_qty, io_use
                    , its_no, io_sapcode_color_gz, io_order_no, io_color_name, io_hoching, io_sap_price )
                    VALUES ";
                $sql .= " ( '{$_POST['opt_id'][$i]}', '0', '$it_id', '{$_POST['opt_price'][$i]}', '{$_POST['opt_stock_qty'][$i]}', '{$_POST['opt_noti_qty'][$i]}', '{$_POST['opt_use'][$i]}' ,
                            '$its_no[$s]', '{$_POST['io_sapcode_color_gz'][$i]}', '{$_POST['io_order_no'][$i]}', '{$_POST['io_color_name'][$i]}', '{$_POST['io_hoching'][$i]}', '{$_POST['io_sap_price'][$i]}' )";
                sql_query($sql);
            }

            if ($_POST['opt_use'][$i] == '1') {
                $it_stock_qty += $_POST['opt_stock_qty'][$i];
            }
        }
    }

    /*$option_update_count = (isset($_POST['io_no']) && is_array($_POST['io_no'])) ? count($_POST['io_no']) : array();
    // 선택옵션등록
    if($option_count) {
        
        if($option_update_count)
        {
            for($i=0; $i<$option_update_count; $i++) {
                
                
            }
        } else {
            $comma = '';
            $sql = " INSERT INTO {$g5['g5_shop_item_option_table']}
                    (io_id, io_type, it_id ,io_price, io_stock_qty, io_noti_qty, io_use
                    , its_no, io_sapcode_color_gz, io_order_no, io_color_name, io_hoching, io_sap_price )
                    VALUES ";
            
            for($i=0; $i<$option_count; $i++) {
                $sql .= $comma . " ( '{$_POST['opt_id'][$i]}', '0', '$it_id', '{$_POST['opt_price'][$i]}', '{$_POST['opt_stock_qty'][$i]}', '{$_POST['opt_noti_qty'][$i]}', '{$_POST['opt_use'][$i]}' ,
                                      '$its_no', '{$_POST['io_sapcode_color_gz'][$i]}', '{$_POST['io_order_no'][$i]}', '{$_POST['io_color_name'][$i]}', '{$_POST['io_hoching'][$i]}', '{$_POST['io_sap_price'][$i]}' )";
                $comma = ' , ';
            }
            sql_query($sql);
        }
    }
    */

    // 추가옵션등록
    if ($supply_count) {
        // 추가옵션명
        $comma = '';
        $sql = " INSERT INTO {$g5['g5_shop_item_option_table']}
                        ( `io_id`, `io_type`, `it_id`, `its_no`, `io_price`, `io_stock_qty`, `io_noti_qty`, `io_use` )
                    VALUES ";
        $s_cnt = 0;
        for ($i = 0; $i < $supply_count; $i++) {
            if ($subID == $_POST['spl_subid'][$i]) {
                $sql .= $comma . " ( '{$_POST['spl_id'][$i]}', '1', '$it_id', '$its_no[$s]', '{$_POST['spl_price'][$i]}', '{$_POST['spl_stock_qty'][$i]}', '{$_POST['spl_noti_qty'][$i]}', '{$_POST['spl_use'][$i]}' )";
                $comma = ' , ';
                $s_cnt++;
            }
        }

        if ($s_cnt) sql_query($sql);
    }
    /*
    if($supply_count) {
        $comma = '';
        $sql = " INSERT INTO {$g5['g5_shop_item_option_table']}
                        ( `io_id`, `io_type`, `it_id`, `its_no`, `io_price`, `io_stock_qty`, `io_noti_qty`, `io_use` )
                    VALUES ";
        for($i=0; $i<$supply_count; $i++) {
            $sql .= $comma . " ( '{$_POST['spl_id'][$i]}', '1', '$it_id', '$its_no', '{$_POST['spl_price'][$i]}', '{$_POST['spl_stock_qty'][$i]}', '{$_POST['spl_noti_qty'][$i]}', '{$_POST['spl_use'][$i]}' )";
            $comma = ' , ';
        }
    
        sql_query($sql);
    }
    */
}



$it_soldout = ($it_stock_qty == 0) ? 1 : 0;


$sql = " update {$g5['g5_shop_item_table']}
                set it_soldout          = '$it_soldout',
                    it_stock_qty        = '$it_stock_qty'
              where it_id = '$it_id' ";
sql_query($sql);

//내게 맞는 상품찾기
if ($fi_id_count) {

    $comma = '';
    $sql = " INSERT INTO lt_shop_item_finditem
                        ( `it_id`, `fi_id`, `fi_contents` )
                    VALUES ";
    $fi_cnt = 0;
    for ($i = 0; $i < $fi_id_count; $i++) {
        $fi_id = $_POST['fi_id'][$i];

        $fi_contents_count = (isset($_POST['fi_contents_' . $fi_id]) && is_array($_POST['fi_contents_' . $fi_id])) ? count($_POST['fi_contents_' . $fi_id]) : array();
        if ($fi_contents_count) {
            for ($j = 0; $j < $fi_contents_count; $j++) {

                $sql .= $comma . " ( '$it_id', '$fi_id', '{$_POST['fi_contents_' .$fi_id][$j]}')";
                $comma = ' , ';
                $fi_cnt++;
            }
        }
    }

    if ($fi_cnt) sql_query($sql);
}

// 삭제된 서브제거
if (count($its_no)) {
    sql_query(" delete from lt_shop_item_sub where it_id = '$it_id' and its_no not in ('" . implode("','", $its_no) . "') "); // 기존선택옵션삭제
}

// 재입고알림
if ($it_soldout == 0 && $current_soldout['it_soldout'] == 1) {

    $sql_stock_sms = "SELECT ss.*, it.it_name, it.it_brand FROM lt_shop_item_stocksms AS ss JOIN lt_shop_item AS it ON ss.it_id=it.it_id WHERE ss.it_id='{$it_id}' AND ss_send=0";
    $db_stock_sms = sql_query($sql_stock_sms);

    $arr_change_data = array();
    $arr_change_data['button'] = array(
        "type" => "웹링크",
        "txt" => "상품 바로가기",
        "link" => "https://lifelike.co.kr/shop/item.php?it_id=" . $it_id
    );

    while (false != ($ss = sql_fetch_array($db_stock_sms))) {
        $arr_change_data["브랜드"] = $ss['it_brand'];
        $arr_change_data["상품명"] = $ss['it_name'];
        sms_autosend('게시판', '재입고알림', '', '재입고알림', $ss['ss_hp'], $arr_change_data);
        $sql_update_ss = "UPDATE lt_shop_item_stocksms SET ss_send=1, ss_time='" . G5_TIME_YMD . "' WHERE ss_id='{$ss['ss_id']}'";
        sql_query($sql_update_ss);
    }
}


$qstr = "$qstr&amp;sca=$sca&amp;page=$page";
if ($is_admin == "brand") {
    if ($w == "u") {
        goto_url("./itemform.brand.php?w=u&amp;it_id=$it_id&amp;$qstr");
    } else if ($w == "d") {
        $qstr = "ca_id=$ca_id&amp;sfl=$sfl&amp;sca=$sca&amp;page=$page&amp;stx=" . urlencode($stx) . "&amp;save_stx=" . urlencode($save_stx);
        goto_url("./itemlist.brand.php?$qstr");
    }

    alert("제품이 등록되었습니다.", "./itemlist.brand.php?" . str_replace('&amp;', '&', $qstr), false);
} else {
    if ($w == "u") {
        goto_url("./itemform.php?w=u&amp;it_id=$it_id&amp;$qstr");
    } else if ($w == "d") {
        $qstr = "ca_id=$ca_id&amp;sfl=$sfl&amp;sca=$sca&amp;page=$page&amp;stx=" . urlencode($stx) . "&amp;save_stx=" . urlencode($save_stx);
        goto_url("./itemlist.php?$qstr");
    }

    alert("제품이 등록되었습니다.", "./itemlist.php?" . str_replace('&amp;', '&', $qstr), false);
}
