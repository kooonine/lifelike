<?php
include_once('./_common.php');

//dbconfig파일에 $g5['faq_table'] , $g5['faq_master_table'] 배열변수가 있는지 체크
if (!isset($g5['faq_table']) || !isset($g5['faq_master_table'])) {
	die('<meta charset="utf-8">관리자 모드에서 게시판관리->FAQ관리를 먼저 확인해 주세요.');
}

// FAQ MASTER
$fm_id = 1;
$sql = " select * from {$g5['faq_master_table']} where fm_id = '$fm_id' ";
$fm = sql_fetch($sql);
$fa_category1_arr = array();
$fa_category1_arr = explode("|", $fm['fm_subject']);
$qstr = !empty($fa_category1) ? "&amp;fa_category1=" . urlencode($fa_category1) : "";


$g5['title'] = $fm['fm_subject'];
$skin_file = $faq_skin_path . '/list.skin.php';
include_once('./_head.php');
$todapth = "고객센터";
$title = "FAQ";
?>
<? require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/navigation.php" ?>
<?
if (is_file($skin_file)) {
	$admin_href = '';
	$himg_src = '';
	$timg_src = '';
	if ($is_admin) {
		$admin_href = G5_ADMIN_URL . '/community/faqlist.php';
	}
	if (!G5_IS_MOBILE) {
		$himg = G5_DATA_PATH . '/faq/' . $fm_id . '_h';
		if (is_file($himg)) {
			$himg_src = G5_DATA_URL . '/faq/' . $fm_id . '_h';
		}

		$timg = G5_DATA_PATH . '/faq/' . $fm_id . '_t';
		if (is_file($timg)) {
			$timg_src = G5_DATA_URL . '/faq/' . $fm_id . '_t';
		}
	}

	$category_href = G5_BBS_URL . '/faq.php';
	$category_stx = '';
	$faq_list = array();

	$stx = trim($stx);
	$sql_search = '';

	if ($stx) {
		$sql_search = " and ( INSTR(fa_subject, '$stx') > 0 or INSTR(fa_content, '$stx') > 0 ) ";
	}
	if ($fa_category1) {
		$sql_search .= " and fa_category1 = '$fa_category1' ";
	}

	if ($page < 1) {
		$page = 1;
	} // 페이지가 없으면 첫 페이지 (1 페이지)

	$page_rows = G5_IS_MOBILE ? $config['cf_mobile_page_rows'] : $config['cf_page_rows'];

	$sql = " select count(*) as cnt from {$g5['faq_table']} where fm_id = '$fm_id' $sql_search ";
	$total = sql_fetch($sql);
	$total_count = $total['cnt'];

	$total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
	$from_record = ($page - 1) * $page_rows; // 시작 열을 구함

	$sql = " select * from {$g5['faq_table']} where fm_id = '$fm_id' $sql_search order by fa_category2, fa_order , fa_id limit $from_record, $page_rows ";
	$result = sql_query($sql);
	for ($i = 0; $row = sql_fetch_array($result); $i++) {
		$faq_list[] = $row;
		if ($stx) {
			$faq_list[$i]['fa_subject'] = search_font($stx, conv_content($faq_list[$i]['fa_subject'], 1));
			$faq_list[$i]['fa_content'] = search_font($stx, conv_content($faq_list[$i]['fa_content'], 1));
		}
	}
	$list_pages = preg_replace('/(\.php)(&amp;|&)/i', '$1?', get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, './faq.php' . $qstr . '&amp;page='));

	include_once($skin_file);
} else {
	echo '<p>' . str_replace(G5_PATH . '/', '', $skin_file) . '이 존재하지 않습니다.</p>';
}

include_once('./_tail.php');

?>
