<?php
$sub_menu = "900110";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'w');

check_admin_token();

if (!$_POST['gr_id']) {
    alert('그룹 ID는 반드시 선택하세요.');
}
if (!$bo_table) {
    alert('게시판 TABLE명은 반드시 입력하세요.');
}
if (!preg_match("/^([A-Za-z0-9_]{1,20})$/", $bo_table)) {
    alert('게시판 TABLE명은 공백없이 영문자, 숫자, _ 만 사용 가능합니다. (20자 이내)');
}
if (!$_POST['bo_subject']) {
    alert('게시판 제목을 입력하세요.');
}

$bo_table = "de_" . $bo_table;

$board_path = G5_DATA_PATH . '/file/' . $bo_table;

// 게시판 디렉토리 생성
@mkdir($board_path, G5_DIR_PERMISSION);
@chmod($board_path, G5_DIR_PERMISSION);

// 디렉토리에 있는 파일의 목록을 보이지 않게 한다.
$file = $board_path . '/index.php';
$f = @fopen($file, 'w');
@fwrite($f, '');
@fclose($f);
@chmod($file, G5_FILE_PERMISSION);

// 분류에 & 나 = 는 사용이 불가하므로 2바이트로 바꾼다.
$src_char = array('&', '=');
$dst_char = array('＆', '〓');
$bo_category_list = str_replace($src_char, $dst_char, $bo_category_list);
//https://github.com/gnuboard/gnuboard5/commit/f5f4925d4eb28ba1af728e1065fc2bdd9ce1da58 에 따른 조치
$str_bo_category_list = isset($_POST['bo_category_list']) ? preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", "", $_POST['bo_category_list']) : '';

$_POST['bo_subject'] = strip_tags($_POST['bo_subject']);
//$_POST['bo_mobile_subject'] = strip_tags($_POST['bo_subject']);

$_POST['bo_upload_size'] = $_POST['bo_upload_size'] * 1024 * 1024;

$bo_me_code = ($_POST['bo_me_code'] != '') ? $_POST['bo_me_code'] : $_POST['me_code1'];

$sql_common = " gr_id               = '{$_POST['gr_id']}',
                bo_skin             = '{$_POST['bo_skin']}',
                bo_mobile_skin      = '{$_POST['bo_skin']}',
                bo_subject          = '{$_POST['bo_subject']}',
                bo_mobile_subject   = '{$_POST['bo_subject']}',
                bo_read_level       = '{$_POST['bo_read_level']}',
                bo_write_level      = '{$_POST['bo_write_level']}',
                bo_reply_level      = '{$_POST['bo_reply_level']}',
                bo_count_delete     = '{$_POST['bo_count_delete']}',
                bo_write_point      = '{$_POST['bo_write_point']}',
                bo_comment_point    = '{$_POST['bo_comment_point']}',
                bo_use_secret       = '{$_POST['bo_use_secret']}',
                bo_use_name         = '{$_POST['bo_use_name']}',
                bo_page_rows        = '{$_POST['bo_page_rows']}',
                bo_mobile_page_rows = '{$_POST['bo_page_rows']}',
                bo_new              = '{$_POST['bo_new']}',
                bo_upload_size      = '{$_POST['bo_upload_size']}',
                bo_upload_count     = '{$_POST['bo_upload_count']}',
                bo_sort_field       = '{$_POST['bo_sort_field']}',
                bo_me_code          = '{$bo_me_code}',
                bo_use              = '{$_POST['bo_use']}',
                bo_use_comment      = '{$_POST['bo_use_comment']}',
                bo_use_grade        = '{$_POST['bo_use_grade']}',
                bo_use_reply        = '{$_POST['bo_use_reply']}',
                bo_use_secretreply  = '{$_POST['bo_use_secretreply']}',
                bo_use_view         = '{$_POST['bo_use_view']}',
                bo_writeimage_point = '{$_POST['bo_writeimage_point']}',
                bo_reply_rows       = '{$_POST['bo_reply_rows']}',
                bo_reply_sort       = '{$_POST['bo_reply_sort']}',
                bo_filter           = '{$_POST['bo_filter']}',
                bo_banner           = '{$_POST['bo_banner']}',
                bo_view_rows        = '{$_POST['bo_view_rows']}',
                bo_use_view_hit     = '{$_POST['bo_use_view_hit']}',
                bo_use_view_reply   = '{$_POST['bo_use_view_reply']}',
                bo_use_view_good    = '{$_POST['bo_use_view_good']}',
                bo_use_view_subject = '1',
                bo_use_view_summary = '{$_POST['bo_use_view_summary']}',
                bo_use_view_username = '{$_POST['bo_use_view_username']}',
                bo_use_view_datetime = '{$_POST['bo_use_view_datetime']}',
                bo_use_point        = '{$_POST['bo_use_point']}',
                bo_use_userform = '{$_POST['bo_use_userform']}',
                bo_use_shop = '{$_POST['bo_use_shop']}',
                bo_use_good = '{$_POST['bo_use_good']}',
                bo_1_subj = '{$_POST['bo_1_subj']}',
                bo_2_subj = '{$_POST['bo_2_subj']}',
                bo_3_subj = '{$_POST['bo_3_subj']}',
                bo_4_subj = '{$_POST['bo_4_subj']}',
                bo_5_subj = '{$_POST['bo_5_subj']}',
                bo_6_subj = '{$_POST['bo_6_subj']}',
                bo_7_subj = '{$_POST['bo_7_subj']}',
                bo_8_subj = '{$_POST['bo_8_subj']}',
                bo_9_subj = '{$_POST['bo_9_subj']}',
                bo_1 = '{$_POST['bo_1']}',
                bo_2 = '{$_POST['bo_2']}',
                bo_3 = '{$_POST['bo_3']}',
                bo_4 = '{$_POST['bo_4']}',
                bo_5 = '{$_POST['bo_5']}',
                bo_6 = '{$_POST['bo_6']}',
                bo_7 = '{$_POST['bo_7']}',
                bo_8 = '{$_POST['bo_8']}',
                bo_9 = '{$_POST['bo_9']}',
                bo_10 = '{$_POST['bo_10']}'
                ";

if ($w == '') {
    $sql_common .= ",bo_list_level = 1
                    ,bo_comment_level = 2
                    ,bo_upload_level = 9
                    ,bo_download_level = 1
                    ,bo_html_level = 9
                    ,bo_link_level = 9
                    ,bo_count_modify = 1
                    ,bo_read_point = '{$config['cf_read_point']}'
                    ,bo_download_point = '{$config['cf_download_point']}'
                    ,bo_use_category = 0
                    ,bo_category_list = ''
                    ,bo_use_sideview = 0
                    ,bo_use_file_content = 0
                    ,bo_use_dhtml_editor = 0
                    ,bo_use_rss_view = 0
                    ,bo_use_nogood = 0
                    ,bo_use_signature = 0
                    ,bo_use_ip_view = 0
                    ,bo_use_list_view = 0
                    ,bo_use_list_file = 0
                    ,bo_use_list_content = 0
                    ,bo_table_width = 100
                    ,bo_subject_len = 60
                    ,bo_mobile_subject_len = 30
                    ,bo_hot = 100
                    ,bo_image_width = 835
                    ,bo_include_head = '_head.php'
                    ,bo_include_tail = '_tail.php'
                    ,bo_content_head = ''
                    ,bo_mobile_content_head = ''
                    ,bo_content_tail = ''
                    ,bo_mobile_content_tail = ''
                    ,bo_insert_content = ''
                    ,bo_gallery_cols = 4
                    ,bo_gallery_width = 202
                    ,bo_gallery_height = 150
                    ,bo_mobile_gallery_width = 125
                    ,bo_mobile_gallery_height = 100
                    ,bo_reply_order = 1
                    ,bo_use_search = 1
                    ,bo_order = 0
                    ,bo_write_min = 0
                    ,bo_write_max = 0
                    ,bo_comment_min = 0
                    ,bo_comment_max = 0
                    ,bo_notice = ''
                    ,bo_use_email = 0
                    ,bo_use_cert = ''
                    ,bo_use_sns = 1
                    ";


    $row = sql_fetch(" select count(*) as cnt from {$g5['board_design_table']} where bo_table = '{$bo_table}' ");
    if ($row['cnt'])
        alert($bo_table . ' 은(는) 이미 존재하는 TABLE 입니다.');

    $sql = " insert into {$g5['board_design_table']}
                set bo_table = '{$bo_table}',
                    bo_count_write = '0',
                    bo_count_comment = '0',
                    $sql_common ";
    sql_query($sql);

    //echo $sql."<br>";
    // 게시판 테이블 생성
    $file = file('./sql_write.sql');
    $sql = implode($file, "\n");

    $create_table = $g5['write_prefix'] . $bo_table;

    // sql_board.sql 파일의 테이블명을 변환
    $source = array('/__TABLE_NAME__/', '/;/');
    $target = array($create_table, '');
    $sql = preg_replace($source, $target, $sql);
    sql_query($sql);
    //echo $sql;
    //exit;

} else if ($w == 'u') {

    // 게시판의 글 수
    $sql = " select count(*) as cnt from {$g5['write_prefix']}{$bo_table} where wr_is_comment = 0 ";
    $row = sql_fetch($sql);
    $bo_count_write = $row['cnt'];

    // 게시판의 코멘트 수
    $sql = " select count(*) as cnt from {$g5['write_prefix']}{$bo_table} where wr_is_comment = 1 ";
    $row = sql_fetch($sql);
    $bo_count_comment = $row['cnt'];

    // 글수 조정
    /*
        엔피씨님의 팁으로 교체합니다. 130308
        http://sir.kr/g5_tiptech/27207
    */
    if (isset($_POST['proc_count'])) {
        // 원글을 얻습니다.
        //$sql = " select wr_id from {$g5['write_prefix']}{$bo_table} where wr_is_comment = 0 ";
        $sql = " select a.wr_id, (count(b.wr_parent) - 1) as cnt from {$g5['write_prefix']}{$bo_table} a, {$g5['write_prefix']}{$bo_table} b where a.wr_id=b.wr_parent and a.wr_is_comment=0 group by a.wr_id ";
        $result = sql_query($sql);
        for ($i = 0; $row = sql_fetch_array($result); $i++) {
            /*
            // 코멘트수를 얻습니다.
            $sql2 = " select count(*) as cnt from {$g5['write_prefix']}$bo_table where wr_parent = '{$row['wr_id']}' and wr_is_comment = 1 ";
            $row2 = sql_fetch($sql2);
            */

            sql_query(" update {$g5['write_prefix']}{$bo_table} set wr_comment = '{$row['cnt']}' where wr_id = '{$row['wr_id']}' ");
        }
    }

    // 공지사항에는 등록되어 있지만 실제 존재하지 않는 글 아이디는 삭제합니다.
    $bo_notice = "";
    $lf = "";
    if ($board['bo_notice']) {
        $tmp_array = explode(",", $board['bo_notice']);
        for ($i = 0; $i < count($tmp_array); $i++) {
            $tmp_wr_id = trim($tmp_array[$i]);
            $row = sql_fetch(" select count(*) as cnt from {$g5['write_prefix']}{$bo_table} where wr_id = '{$tmp_wr_id}' ");
            if ($row['cnt']) {
                $bo_notice .= $lf . $tmp_wr_id;
                $lf = ",";
            }
        }
    }

    $sql = " update {$g5['board_design_table']}
                set bo_notice = '{$bo_notice}',
                    bo_count_write = '{$bo_count_write}',
                    bo_count_comment = '{$bo_count_comment}',
                    {$sql_common}
              where bo_table = '{$bo_table}' ";
    sql_query($sql);
}

goto_url("./board_management.php?{$qstr}");
