<?php
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

if (!$is_member) {
    alert_close("사용후기는 회원만 작성 가능합니다.");
}

$w     = preg_replace('/[^0-9a-z]/i', '', trim($_REQUEST['w']));
$it_id = get_search_string(trim($_REQUEST['it_id']));
$is_id = preg_replace('/[^0-9]/', '', trim($_REQUEST['is_id']));
$ct_id = preg_replace('/[^0-9]/', '', trim($_REQUEST['ct_id']));

// 상품정보체크
$sql = " select * from {$g5['g5_shop_item_table']} where it_id = '$it_id' ";
$row = sql_fetch($sql);
if(!$row['it_id'])
    alert_close('상품정보가 존재하지 않습니다.');

if ($w == "") {
    $is_score = 5;

    // 사용후기 작성 설정에 따른 체크
    check_itemuse_write($it_id, $member['mb_id']);
    
} else if ($w == "u") {
    $use = sql_fetch(" select * from {$g5['g5_shop_item_use_table']} where is_id = '$is_id' ");
    if (!$use) {
        alert_close("사용후기 정보가 없습니다.");
    }
    
    $ct_id    = $use['ct_id'];
    $it_id    = $use['it_id'];
    $is_score = $use['is_score'];
    $is_age = $use['is_age'];

    if (!$is_admin && $use['mb_id'] != $member['mb_id']) {
        alert_close("자신의 사용후기만 수정이 가능합니다.");
    }
    
    $file_count = (int)$use['is_file'];
    
    $file['count'] = 0;
    $sql = " select * from lt_shop_item_use_file where is_id = '$is_id' order by bf_no ";
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result))
    {
        $filepath = G5_DATA_PATH.'/file/itemuse';
        
        $no = $row['bf_no'];
        $file[$no]['path'] = G5_DATA_URL.'/file/itemuse';
        $file[$no]['size'] = get_filesize($row['bf_filesize']);
        $file[$no]['datetime'] = $row['bf_datetime'];
        $file[$no]['source'] = addslashes($row['bf_source']);
        $file[$no]['file'] = $row['bf_file'];
        $file[$no]['image_width'] = $row['bf_width'] ? $row['bf_width'] : 640;
        $file[$no]['image_height'] = $row['bf_height'] ? $row['bf_height'] : 480;
        $file[$no]['image_type'] = $row['bf_type'];
        
        $thumb = thumbnail($file[$no]['file'], $filepath, $filepath, 80, 80, false, false, 'center', false, $um_value='80/0.5/3');
        $file[$no]['thumb'] = $thumb;
        $file['count']++;
    }
    
    if($file_count < $file['count'])
    {
        $file_count = $file['count'];
    }
}

$ct_sql = " select * from {$g5['g5_shop_cart_table']} where it_id = '$it_id' and ct_id = '$ct_id' ";
$ct = sql_fetch($ct_sql);

include_once(G5_PATH.'/head.sub.php');

$is_dhtml_editor = false;
// 모바일에서는 DHTML 에디터 사용불가
/*if ($config['cf_editor'] && (!is_mobile() || defined('G5_IS_MOBILE_DHTML_USE') && G5_IS_MOBILE_DHTML_USE)) {
    $is_dhtml_editor = true;
}*/
$editor_html = editor_html('is_content', get_text($use['is_content'], 0), $is_dhtml_editor);
$editor_js = '';
$editor_js .= get_editor_js('is_content', $is_dhtml_editor);
$editor_js .= chk_editor_js('is_content', $is_dhtml_editor);

$itemuseform_skin = G5_MSHOP_SKIN_PATH.'/itemuseform.skin.php';

if(!file_exists($itemuseform_skin)) {
    echo str_replace(G5_PATH.'/', '', $itemuseform_skin).' 스킨 파일이 존재하지 않습니다.';
} else {
    include_once($itemuseform_skin);
}

include_once(G5_PATH.'/tail.sub.php');
?>