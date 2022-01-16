<?php
include_once './_common.php';
$result = array(
    "result" => false,
    "data" => array(),
    "msg" => null
);

$type = strtolower($_REQUEST['type']);
$check = strtolower($_REQUEST['check']);
if ($is_member) {
    switch ($type) {
        case "orderlist":
            $sql_orderlist = "SELECT c.it_name,c.ct_option,c.ct_id,(SELECT u.is_id FROM {$g5['g5_shop_item_use_table']} AS u WHERE u.mb_id=c.mb_id AND u.it_id=c.it_id AND u.ct_id=c.ct_id) AS is_exists FROM lt_shop_cart AS c WHERE mb_id='{$member['mb_id']}' AND it_id='{$it_id}' AND ct_status IN ('구매완료','배송완료','구매확정') GROUP BY ct_id HAVING is_exists IS NULL";
            $db_orderlist = sql_query($sql_orderlist);

            if ($db_orderlist->num_rows > 0) {
                while (false != ($row = sql_fetch_array($db_orderlist))) {
                    $tmp_data = array(
                        // 'subject' => sprintf("%s / %s", $row['it_name'], $row['ct_option']),
                        // 'subject' => sprintf("%s", $row['it_name']),
                        'subject' => $row['it_name'],
                        'ct_id' => $row['ct_id']
                    );
                    $result['data'][] = $tmp_data;
                }
                $result['result'] = true;
            } else {
                $result['msg'] = "NOT_FOUND_ORDER";
            }
            break;
        case "write":
        case "update":
            $it_id       = trim($_REQUEST['it_id']);
            $is_subject  = trim($_POST['is_subject']);
            $is_content  = trim($_POST['is_content']);
            $is_content  = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $is_content);
            $is_score    = (int) $_POST['is_score'] > 5 ? 1 : (int) $_POST['is_score'];
            $is_id       = (int) trim($_REQUEST['is_id']);
            $ct_id       = (int) trim($_REQUEST['ct_id']);
            $is_name     = $member['mb_name'];
            $is_age      = $member['mb_age'];
            $is_password = $member['mb_password'];
            $is_content_mobile = trim($_POST['is_content_mobile']);
            $is_content_mobile  = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $is_content_mobile);

            $imgCheck[0]  = trim($_POST['img1']);
            $imgCheck[1]  = trim($_POST['img2']);
            $imgCheck[2]  = trim($_POST['img3']);
            check_itemuse_write($it_id, $member['mb_id']);

            if ($check=='mobile') { 
                $_FILES['review_image'] = null;
                $is_content = $is_content_mobile;
                $_FILES['review_image'] = $_FILES['review_image-mobile'];
            }
            if (!$is_subject) {
                $result['msg'] = "제목을 입력하여 주십시오.";
                die(json_encode($result, JSON_UNESCAPED_UNICODE));
            }
            if (!$is_content) {
                $result['msg'] = "내용을 입력하여 주십시오.";
                die(json_encode($result, JSON_UNESCAPED_UNICODE));
            }

            $upload_max_filesize = ini_get('upload_max_filesize');

            if (empty($_POST)) {
                $result['msg'] = "파일 또는 글내용의 크기가 서버에서 설정한 값을 넘어 오류가 발생하였습니다.\\npost_max_size=" . ini_get('post_max_size') . " , upload_max_filesize=" . $upload_max_filesize . "\\n관리자에게 문의 바랍니다.";
                die(json_encode($result, JSON_UNESCAPED_UNICODE));
            }
            $pointYn = 0;
            if (empty($is_id)) {
                $reviewCount = sql_fetch(" SELECT COUNT(*) AS CNT FROM {$g5['g5_shop_item_use_table']} WHERE mb_id = '{$member['mb_id']}' AND ct_id = '$ct_id' ");
                if ($reviewCount['CNT'] > 0) {
                    $result['msg'] = "이미 등록된 리뷰입니다.";
                    die(json_encode($result, JSON_UNESCAPED_UNICODE));  
                }
                $insertSql = "SELECT lt_order_no,it_size_info FROM lt_shop_item WHERE it_id = '$it_id' LIMIT 1 ";
                $itInfo = sql_fetch($insertSql);
                $sql = "INSERT INTO {$g5['g5_shop_item_use_table']}
                               SET it_id = '$it_id',
                                   ct_id = '$ct_id',
                                   mb_id = '{$member['mb_id']}',
                                   is_score = '$is_score',
                                   is_age = '$is_age',
                                   is_name = '$is_name',
                                   is_password = '$is_password',
                                   is_subject = '$is_subject',
                                   is_content = '$is_content',
                                   is_time = '" . G5_TIME_YMDHIS . "',
                                   is_type = 0,
                                   is_ip = '{$_SERVER['REMOTE_ADDR']}',
                                   io_order_no = '{$itInfo['lt_order_no']}',
                                   it_size ='{$itInfo['it_size_info']}' ";
                if (!$default['de_item_use_use']) $sql .= ", is_confirm = '1' ";

                sql_query($sql);
                $is_id = sql_insert_id();

                $result['msg'] = "리뷰가 등록되었습니다.";
                if ($default['de_item_use_use']) {
                    $result['msg'] .= "";
                }
            } else {
                $pointYn = 1;
                $sql = "UPDATE {$g5['g5_shop_item_use_table']}
                        SET is_subject = '$is_subject',
                            is_content = '$is_content',
                            is_score = '$is_score',
                            is_age = '$is_age'
                        WHERE is_id = '$is_id' ";
                sql_query($sql);

                $result['msg'] = "리뷰가 수정되었습니다.";
            }

            $result['result'] = true;
            // 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
            @mkdir(G5_DATA_PATH . '/file/itemuse', G5_DIR_PERMISSION);
            @chmod(G5_DATA_PATH . '/file/itemuse', G5_DIR_PERMISSION);

            $chars_array = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));

            // 가변 파일 업로드
            $file_upload_msg = '';
            $upload = array();
            for ($i = 0; $i < count($_FILES['review_image']['name']); $i++) {
                $upload[$i]['file']     = '';
                $upload[$i]['source']   = '';
                $upload[$i]['filesize'] = 0;
                $upload[$i]['image']    = array();
                $upload[$i]['image'][0] = '';
                $upload[$i]['image'][1] = '';
                $upload[$i]['image'][2] = '';

                $tmp_file  = $_FILES['review_image']['tmp_name'][$i];
                $filesize  = $_FILES['review_image']['size'][$i];
                $filename  = $_FILES['review_image']['name'][$i];
                $filename  = get_safe_filename($filename);

                // 서버에 설정된 값보다 큰파일을 업로드 한다면
                if ($filename) {
                    if ($_FILES['review_image']['error'][$i] == 1) {
                        $file_upload_msg .= '\"' . $filename . '\" 파일의 용량이 서버에 설정(' . $upload_max_filesize . ')된 값보다 크므로 업로드 할 수 없습니다.\\n';
                        continue;
                    } else if ($_FILES['review_image']['error'][$i] != 0) {
                        $file_upload_msg .= '\"' . $filename . '\" 파일이 정상적으로 업로드 되지 않았습니다.\\n';
                        continue;
                    }
                }

                if (is_uploaded_file($tmp_file)) {
                    //=================================================================\
                    // 090714
                    // 이미지나 플래시 파일에 악성코드를 심어 업로드 하는 경우를 방지
                    // 에러메세지는 출력하지 않는다.
                    //-----------------------------------------------------------------
                    $timg = @getimagesize($tmp_file);
                    // image type
                    if (
                        preg_match("/\.({$config['cf_image_extension']})$/i", $filename) ||
                        preg_match("/\.({$config['cf_flash_extension']})$/i", $filename)
                    ) {
                        if ($timg['2'] < 1 || $timg['2'] > 16)
                            continue;
                    }
                    //=================================================================

                    $upload[$i]['image'] = $timg;

                    // 4.00.11 - 글답변에서 파일 업로드시 원글의 파일이 삭제되는 오류를 수정
                    if ($w == 'u') {
                        // 존재하는 파일이 있다면 삭제합니다.
                        $row = sql_fetch(" select review_image from lt_shop_item_use_file where is_id = '$is_id' and bf_no = '$i' ");
                        @unlink(G5_DATA_PATH . '/file/itemuse/' . $row['review_image']);
                        // 이미지파일이면 썸네일삭제
                        if (preg_match("/\.({$config['cf_image_extension']})$/i", $row['review_image'])) {
                            delete_board_thumbnail($bo_table, $row['review_image']);
                        }
                    }

                    // 프로그램 원래 파일명
                    $upload[$i]['source'] = $filename;
                    $upload[$i]['filesize'] = $filesize;

                    // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
                    $filename = preg_replace("/\.(php|pht|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

                    shuffle($chars_array);
                    $shuffle = implode('', $chars_array);

                    // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
                    $upload[$i]['file'] = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($filename);

                    $dest_file = G5_DATA_PATH . '/file/itemuse/' . $upload[$i]['file'];

                    // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
                    $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['review_image']['error'][$i]);

                    // $ftp_server = "litandard-org.daouidc.com"; 
                    // $ftp_port = 2021; 
                    // $ftp_user_name = "litandard"; 
                    // $ftp_user_pass = "flxosekem_ftp!@34"; 
                    // $conn_id = ftp_connect($ftp_server,$ftp_port) or die("Couldn't connect to $ftp_server");
                    // $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<Br> Couldn't connect to $ftp_server"); 
                    // ftp_pasv($conn_id, true);

                    // $cdnFile = '/data/file/itemuse/' . $upload[$i]['file'];
                    // $upload = ftp_put($conn_id, $cdnFile, $dest_file, FTP_BINARY);

                    // 올라간 파일의 퍼미션을 변경합니다.
                    chmod($dest_file, G5_FILE_PERMISSION);
                }
            }
            // 나중에 테이블에 저장하는 이유는 $is_id 값을 저장해야 하기 때문입니다.
            for ($i = 0; $i < count($upload); $i++) {
                if (!get_magic_quotes_gpc()) {
                    $upload[$i]['source'] = addslashes($upload[$i]['source']);
                }

                // $delsql = "DELETE from lt_shop_item_use_file where is_id = '{$is_id}' AND  bf_no = '{$i}'";
                // sql_query($delsql);
                // $result['msg'] = $filename;
                // die(json_encode($result, JSON_UNESCAPED_UNICODE));

                // $sql = "INSERT into lt_shop_item_use_file
                // SET is_id = '{$is_id}',
                //     bf_no = '{$i}',
                //     bf_source = '{$upload[$i]['source']}',
                //     bf_file = '{$upload[$i]['file']}',
                //     bf_download = 0,
                //     bf_filesize = '{$upload[$i]['filesize']}',
                //     bf_width = '{$upload[$i]['image']['0']}',
                //     bf_height = '{$upload[$i]['image']['1']}',
                //     bf_type = '{$upload[$i]['image']['2']}',
                //     bf_datetime = '" . G5_TIME_YMDHIS . "' ";
                // sql_query($sql);

                $row = sql_fetch(" select count(*) as cnt from lt_shop_item_use_file where is_id = '{$is_id}' and bf_no = '{$i}' ");
                if ($row['cnt']) {
                    // 삭제에 체크가 있거나 파일이 있다면 업데이트를 합니다.
                    // 그렇지 않다면 내용만 업데이트 합니다.
                    if ($imgCheck[$i]  == '' || !$imgCheck[$i] ){
                        // $result['msg'] = $filename;
                        // die(json_encode($result, JSON_UNESCAPED_UNICODE));

                        $delsql = "DELETE from lt_shop_item_use_file where is_id = '{$is_id}' AND  bf_no = '{$i}'";
                        sql_query($delsql);
                    } else {
                        // $result['msg'] = 'dkkssksk';
                        // die(json_encode($result, JSON_UNESCAPED_UNICODE));
                        if ($upload[$i]['del_check'] || $upload[$i]['file']) {
                            $sql = "UPDATE lt_shop_item_use_file
                                    SET bf_source = '{$upload[$i]['source']}',
                                        bf_file = '{$upload[$i]['file']}',
                                        bf_filesize = '{$upload[$i]['filesize']}',
                                        bf_width = '{$upload[$i]['image']['0']}',
                                        bf_height = '{$upload[$i]['image']['1']}',
                                        bf_type = '{$upload[$i]['image']['2']}',
                                        bf_datetime = '" . G5_TIME_YMDHIS . "'
                                    WHERE is_id = '{$is_id}' AND bf_no = '{$i}' ";
                            sql_query($sql);
                        }
                    }
                } else {
                    // $result['msg'] = 'ㅁ지막';
                    // die(json_encode($result, JSON_UNESCAPED_UNICODE));
                    if ($imgCheck[$i] && $imgCheck[$i]  != ''){ 
                        $sql = "INSERT into lt_shop_item_use_file
                        SET is_id = '{$is_id}',
                            bf_no = '{$i}',
                            bf_source = '{$upload[$i]['source']}',
                            bf_file = '{$upload[$i]['file']}',
                            bf_download = 0,
                            bf_filesize = '{$upload[$i]['filesize']}',
                            bf_width = '{$upload[$i]['image']['0']}',
                            bf_height = '{$upload[$i]['image']['1']}',
                            bf_type = '{$upload[$i]['image']['2']}',
                            bf_datetime = '" . G5_TIME_YMDHIS . "' ";
                            sql_query($sql);
                    }
 
                }
                // cdn 이동
                $ftp_server = "litandard-org.daouidc.com"; 
                $ftp_port = 2021; 
                $ftp_user_name = "litandard"; 
                $ftp_user_pass = "flxosekem_ftp!@34"; 
                $conn_id = ftp_connect($ftp_server,$ftp_port) or die("Couldn't connect to $ftp_server");
                $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<Br> Couldn't connect to $ftp_server"); 
                ftp_pasv($conn_id, true);
                $cdnFile = '/data/file/itemuse/'.$upload[$i]['file'];
                $upload = ftp_put($conn_id, $cdnFile, $dest_file, FTP_BINARY);
            }

            // 업로드된 파일 내용에서 가장 큰 번호를 얻어 거꾸로 확인해 가면서
            // 파일 정보가 없다면 테이블의 내용을 삭제합니다.
            $row = sql_fetch(" select max(bf_no) as max_bf_no from lt_shop_item_use_file where is_id = '{$is_id}' ");
            for ($i = (int) $row['max_bf_no']; $i >= 0; $i--) {
                $row2 = sql_fetch(" select bf_file from lt_shop_item_use_file where  is_id = '{$is_id}' and bf_no = '{$i}' ");

                // 정보가 있다면 빠집니다.
                if ($row2['bf_file']) break;

                // 그렇지 않다면 정보를 삭제합니다.
                sql_query(" delete from lt_shop_item_use_file where is_id = '{$is_id}' and bf_no = '{$i}' ");
            }

            // 파일의 개수를 게시물에 업데이트 한다.
            $row = sql_fetch(" select count(*) as cnt from lt_shop_item_use_file where is_id = '{$is_id}' ");
            $new_is_type = $row['cnt'] > 0 ? 1 : 0;
            sql_query(" update {$g5['g5_shop_item_use_table']} set is_file='{$row['cnt']}',is_type={$new_is_type} where is_id = '{$is_id}' ");

            //쇼핑몰 설정에서 사용후기가 즉시 출력일 경우
            if (!$default['de_item_use_use']) {
                update_use_cnt($it_id);
                update_use_avg($it_id);
            }
            // 리뷰 작성 후 포인트 적립
            if ($pointYn == 0) {
                $is_point = 0;
                $sql_points =  "SELECT
                cf_review_write_point AS review_write,
                cf_review_photo_point AS review_photo,
                cf_review_first_point AS review_first
                FROM {$g5['config_table']}";
                $cf_points_re = sql_fetch($sql_points);
                $is_re = sql_fetch("SELECT * FROM lt_shop_item_use WHERE is_id = '$is_id'");
                $is_first = sql_fetch("SELECT is_id FROM lt_shop_item_use WHERE it_id = '{$it_id}' ORDER BY is_time ASC LIMIT 1");
                if ($is_first['is_id'] == $is_id) {
                    $is_point += $cf_points_re['review_first'];
                    insert_point($member['mb_id'], $cf_points_re['review_first'], "최초리뷰작성({$is_re['is_subject']})", 'item_use', $is_id, "최초리뷰작성");
                } else if ($_FILES['review_image'] > 0) {
                    $is_point += $cf_points_re['review_photo'];
                    insert_point($member['mb_id'], $cf_points_re['review_photo'], "포토리뷰작성({$is_re['is_subject']})", 'item_use', $is_id, "포토리뷰작성");
                } else {
                    $is_point += $cf_points_re['review_write'];
                    insert_point($member['mb_id'], $cf_points_re['review_write'], "리뷰작성({$is_re['is_subject']})", 'item_use', $is_id, "리뷰작성");
                }
    
                $sql = "UPDATE lt_shop_item_use
                SET is_confirm = '1'
                ,is_point = '{$is_point}'
                WHERE is_id = '{$is_id}'";
                sql_query($sql);
            }
            // 리뷰 작성 완료 후 구매확정시 포인트 적립 
            $selectSql = "SELECT COUNT(*) AS CNT FROM lt_shop_cart WHERE ct_status='구매확정' AND ct_id = '$ct_id'";
            $cartCnt = sql_fetch($selectSql);
            $statusCheck = $cartCnt['CNT'];
            if ($statusCheck < 1) {
                $selectSql = "SELECT * FROM lt_shop_cart WHERE ct_id = '$ct_id' LIMIT 1";
                $cartOd = sql_fetch($selectSql);
                $cartOdId = $cartOd['od_id'];
                $selectSql = "SELECT * FROM lt_shop_order WHERE od_id = '$cartOdId' LIMIT 1";
                $odCheck = sql_fetch($selectSql);

                if ($odCheck['od_point_save'] == 1) {
                    $selSql = "SELECT SUM(ct_point_save) AS poSum FROM lt_shop_cart WHERE od_id = '$cartOdId'";
                    $order_point = $selSql['poSum'];
                    // $ctSql = "UPDATE lt_shop_cart SET ct_point_save = NULL ct_id = ''$ct_id''";
                    // sql_query($ctSql);
                }else {
                    $order_point = ($odCheck['od_receipt_price'] - $odCheck['od_cancel_price'] - $odCheck['od_refund_price']) / 100 * $default['de_point_percent'];
                }
                insert_point($member['mb_id'], $order_point, '주문번호 ' . $odCheck['od_id'] . ' 적립', '@order', $odCheck['od_id'], G5_TIME_YMD);
            }

            $cartSql = "UPDATE lt_shop_cart SET ct_status='구매확정' WHERE ct_id = '$ct_id'"; 
            sql_query($cartSql);
            break;
        case "delete":
            $is_id = (int) trim($_REQUEST['is_id']);
            if (!empty($is_id)) {
                $result['result'] = sql_query("DELETE FROM lt_shop_item_use WHERE mb_id='{$member['mb_id']}' AND is_id='{$is_id}'");
            } else {
                $result['msg'] = "NOT_FOUND_REVIEW";
            }

            break;
        default:
            $sql_review = "SELECT * FROM lt_shop_item_use WHERE mb_id='{$member['mb_id']}' AND it_id='{$it_id}' AND ct_id='{$ct_id}'";
            $db_review = sql_fetch($sql_review);

            if ($db_review['is_id']) {
                $sql_files = "SELECT * FROM lt_shop_item_use_file WHERE is_id='{$db_review['is_id']}' ORDER BY bf_no";
                $db_files = sql_query($sql_files);
                $tmp_files = array();

                while (false != ($frow = sql_fetch_array($db_files))) {
                    $tmp_file = array(
                        'no' => $frow['bf_no'],
                        'file' => $frow['bf_file']
                    );

                    $tmp_files[] = $tmp_file;
                }
                $result['file'] = $tmp_files;
                $result['data'] = $db_review;
                $result['result'] = true;
            } else {
                $result['msg'] = "NOT_FOUND_REVIEW";
            }
            break;
    }
} else {
    $result['msg'] = "NOT_FOUND_MEMBER";
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);
