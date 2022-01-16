<?php
$sub_menu = '400200';
include_once('./_common.php');

if ($file = $_POST['ca_include_head']) {
    $file_ext = pathinfo($file, PATHINFO_EXTENSION);

    if (!$file_ext || !in_array($file_ext, array('php', 'htm', 'html')) || !preg_match("/\.(php|htm[l]?)$/i", $file)) {
        alert("상단 파일 경로가 php, html 파일이 아닙니다.");
    }
}

if ($file = $_POST['ca_include_tail']) {
    $file_ext = pathinfo($file, PATHINFO_EXTENSION);

    if (!$file_ext || !in_array($file_ext, array('php', 'htm', 'html')) || !preg_match("/\.(php|htm[l]?)$/i", $file)) {
        alert("하단 파일 경로가 php, html 파일이 아닙니다.");
    }
}

if (isset($_POST['ca_id'])) {
    $ca_id = preg_replace('/[^0-9a-z]/i', '', $ca_id);
    $sql = " select * from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' ";
    $ca = sql_fetch($sql);

    if (($ca['ca_include_head'] !== $_POST['ca_include_head'] || $ca['ca_include_tail'] !== $_POST['ca_include_tail']) && function_exists('get_admin_captcha_by') && get_admin_captcha_by()) {
        include_once(G5_CAPTCHA_PATH . '/captcha.lib.php');

        if (!chk_captcha()) {
            alert('자동등록방지 숫자가 틀렸습니다.');
        }
    }
}

if (!is_include_path_check($_POST['ca_include_head'], 1)) {
    alert('상단 파일 경로에 포함시킬수 없는 문자열이 있습니다.');
}

if (!is_include_path_check($_POST['ca_include_tail'], 1)) {
    alert('하단 파일 경로에 포함시킬수 없는 문자열이 있습니다.');
}

if ($w == "u" || $w == "d")
    check_demo();

auth_check($auth[substr($sub_menu,0,2)], "d");

check_admin_token();

@mkdir(G5_DATA_PATH . "/banner/category", G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH . "/banner/category", G5_DIR_PERMISSION);

if ($w == 'd' && $is_admin != 'super')
    alert("최고관리자만 분류를 삭제할 수 있습니다.");

if ($w == "" || $w == "u") {
    if ($ca_mb_id) {
        $sql = " select mb_id from {$g5['member_table']} where mb_id = '$ca_mb_id' ";
        $row = sql_fetch($sql);
        if (!$row['mb_id'])
            alert("\'$ca_mb_id\' 은(는) 존재하는 회원아이디가 아닙니다.");
    }
}

if ($ca_skin && !is_include_path_check($ca_skin)) {
    alert('오류 : 데이터폴더가 포함된 path 를 포함할수 없습니다.');
}

$sql_common = " ca_order                = '$ca_order',
                ca_skin_dir             = '$ca_skin_dir',
                ca_mobile_skin_dir      = '$ca_mobile_skin_dir',
                ca_skin                 = '$ca_skin',
                ca_mobile_skin          = '$ca_mobile_skin',
                ca_img_width            = '$ca_img_width',
                ca_img_height           = '$ca_img_height',
				ca_list_mod             = '$ca_list_mod',
				ca_list_row             = '$ca_list_row',
                ca_mobile_img_width     = '$ca_mobile_img_width',
                ca_mobile_img_height    = '$ca_mobile_img_height',
				ca_mobile_list_mod      = '$ca_mobile_list_mod',
                ca_mobile_list_row      = '$ca_mobile_list_row',
                ca_sell_email           = '$ca_sell_email',
                ca_use                  = '$ca_use',
                ca_stock_qty            = '$ca_stock_qty',
                ca_explan_html          = '$ca_explan_html',
                ca_head_html            = '$ca_head_html',
                ca_tail_html            = '$ca_tail_html',
                ca_mobile_head_html     = '$ca_mobile_head_html',
                ca_mobile_tail_html     = '$ca_mobile_tail_html',
                ca_include_head         = '$ca_include_head',
                ca_include_tail         = '$ca_include_tail',
                ca_mb_id                = '$ca_mb_id',
                ca_cert_use             = '$ca_cert_use',
                ca_adult_use            = '$ca_adult_use',
                ca_nocoupon             = '$ca_nocoupon',
                ca_1_subj               = '$ca_1_subj',
                ca_2_subj               = '$ca_2_subj',
                ca_3_subj               = '$ca_3_subj',
                ca_4_subj               = '$ca_4_subj',
                ca_5_subj               = '$ca_5_subj',
                ca_6_subj               = '$ca_6_subj',
                ca_7_subj               = '$ca_7_subj',
                ca_8_subj               = '$ca_8_subj',
                ca_9_subj               = '$ca_9_subj',
                ca_10_subj              = '$ca_10_subj'";

/*
                ca_1                    = '$ca_1',
                ca_2                    = '$ca_2',
                ca_3                    = '$ca_3',
                ca_4                    = '$ca_4',
                ca_5                    = '$ca_5',
                ca_6                    = '$ca_6',
                ca_7                    = '$ca_7',
                ca_8                    = '$ca_8',
                ca_9                    = '$ca_9',
                ca_10                   = '$ca_10' 
                */


/* 상단배너 업로드 - balance@panpacific.co.kr */

// dd($_FILES);
function create_banner_path($prefix = "", $ext = "")
{
    $banner_dir = G5_DATA_PATH . '/banner/category/';
    $tmppath =  $banner_dir . $prefix . '.' . $ext;
    if (!file_exists($tmppath)) @unlink($tmppath);
    return $tmppath;
}

if (!empty($_FILES['ca_banner_file']['name'])) {
    $cname = $_FILES['ca_banner_file']['name'];
    $pi = pathinfo($cname);
    if (!preg_match('/\.(gif|jpe?g|bmp|png)$/i', $cname)) {
        alert("이미지 파일만 업로드 할수 있습니다.");
    }

    $banner_sql = sprintf("INSERT INTO lt_shop_category_banner SET bn_alt='%s',bn_url='%s',bn_device='PC',ca_id='%s'", clean_xss_attributes($ca_banner_alt), clean_xss_attributes($ca_banner_url), $ca_id);
    $sql_return = sql_query($banner_sql);
    if ($sql_return) {
        $bn = sql_fetch("SELECT MAX(bn_id) AS bn_id FROM lt_shop_category_banner");
        $banner_path = create_banner_path($bn['bn_id'], strtolower($pi['extension']));
        $banner_file = basename($banner_path);
        upload_file($_FILES['ca_banner_file']['tmp_name'], $banner_file, G5_DATA_PATH . "/banner/category");
        sql_query("UPDATE lt_shop_category_banner SET bn_file='" . $banner_file . "' WHERE bn_id=" . $bn['bn_id']);
    }
}

if (!empty($_FILES['ca_banner_m_file']['name'])) {
    $cname = $_FILES['ca_banner_m_file']['name'];
    $pi = pathinfo($cname);
    if (!preg_match('/\.(gif|jpe?g|bmp|png)$/i', $cname)) {
        alert("이미지 파일만 업로드 할수 있습니다.");
    }

    $banner_sql = sprintf("INSERT INTO lt_shop_category_banner SET bn_alt='%s',bn_url='%s',bn_device='M',ca_id='%s'", clean_xss_attributes($ca_banner_m_alt), clean_xss_attributes($ca_banner_m_url), $ca_id);
    $sql_return = sql_query($banner_sql);
    if ($sql_return) {
        $bn = sql_fetch("SELECT MAX(bn_id) AS bn_id FROM lt_shop_category_banner");
        $banner_path = create_banner_path($bn['bn_id'], strtolower($pi['extension']));
        $banner_file = basename($banner_path);
        upload_file($_FILES['ca_banner_m_file']['tmp_name'], $banner_file, G5_DATA_PATH . "/banner/category");
        sql_query("UPDATE lt_shop_category_banner SET bn_file='" . $banner_file . "' WHERE bn_id=" . $bn['bn_id']);
    }
}

if (!empty($bn_delete)) {
    $sql_banners = "SELECT bn_id,bn_file FROM lt_shop_category_banner WHERE bn_id IN (" . implode(',', $bn_delete) . ")";
    $db_banners = sql_query($sql_banners);
    for ($di = 0; $banner = sql_fetch_array($db_banners); $di++) {
        $banner_path = G5_DATA_PATH . '/banner/category/' . $banner['bn_file'];
        @unlink($banner_path);
        sql_query("DELETE FROM lt_shop_category_banner WHERE bn_id=" . $banner['bn_id']);
    }
}
/* 상단배너 업로드 끝 */

if ($w == "") {
    if (!trim($ca_id))
        alert("분류 코드가 없으므로 분류를 추가하실 수 없습니다.");

    // 소문자로 변환
    $ca_id = strtolower($ca_id);

    $sql = " insert {$g5['g5_shop_category_table']}
                set ca_id   = '$ca_id',
                    ca_name = '$ca_name',
                    $sql_common ";
    sql_query($sql);
} else if ($w == "u") {
    $sql = " update {$g5['g5_shop_category_table']}
                set ca_name = '$ca_name',
                    $sql_common
              where ca_id = '$ca_id' ";
    sql_query($sql);

    // 하위분류를 똑같은 설정으로 반영
    if ($sub_category) {
        $len = strlen($ca_id);
        $sql = " update {$g5['g5_shop_category_table']}
                    set $sql_common
                  where SUBSTRING(ca_id,1,$len) = '$ca_id' ";
        if ($is_admin != 'super')
            $sql .= " and ca_mb_id = '{$member['mb_id']}' ";
        sql_query($sql);
    }
} else if ($w == "d") {
    // 분류의 길이
    $len = strlen($ca_id);

    $sql = " select COUNT(*) as cnt from {$g5['g5_shop_category_table']}
              where SUBSTRING(ca_id,1,$len) = '$ca_id'
                and ca_id <> '$ca_id' ";
    $row = sql_fetch($sql);
    if ($row['cnt'] > 0)
        alert("이 분류에 속한 하위 분류가 있으므로 삭제 할 수 없습니다.\\n\\n하위분류를 우선 삭제하여 주십시오.");

    $str = $comma = "";
    $sql = " select it_id from {$g5['g5_shop_item_table']} where ca_id = '$ca_id' ";
    $result = sql_query($sql);
    $i = 0;
    while ($row = sql_fetch_array($result)) {
        $i++;
        if ($i % 10 == 0) $str .= "\\n";
        $str .= "$comma{$row['it_id']}";
        $comma = " , ";
    }

    if ($str)
        alert("이 분류와 관련된 상품이 총 {$i} 건 존재하므로 상품을 삭제한 후 분류를 삭제하여 주십시오.\\n\\n$str");

    // 분류 삭제
    $sql = " delete from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' ";
    sql_query($sql);
}

if (function_exists('get_admin_captcha_by'))
    get_admin_captcha_by('remove');

if ($w == "" || $w == "u") {
    goto_url("./categoryform.php?w=u&amp;ca_id=$ca_id&amp;$qstr");
} else {
    goto_url("./categorylist.php?$qstr");
}
