<?
include_once('./_common.php');

//if($is_guest)
//    alert('회원이시라면 로그인 후 이용해 보십시오.', './login.php?url='.urlencode(G5_BBS_URL.'/qalist.php'));

$qaconfig = get_qa_config();

$g5['title'] = $qaconfig['qa_title'];
include_once('./qahead.php');
$todapth = "고객센터";
$title="1:1문의하기";
?>
<? require_once $_SERVER['DOCUMENT_ROOT']."/lib/navigation.php" ?>
<?
$sca=urldecode($sca);
$skin_file = $qa_skin_path.'/list.skin.php';

$category_option = '';
if ($qaconfig['qa_category']) {
	$category_href = G5_BBS_URL.'/qalist.php';


	if ($sca==''){
		$category_option .= '<li class="on"><a href="'.$category_href.'"';
	} else {
		$category_option .= '<li><a href="'.$category_href.'"';
	}
	$category_option .= '>전체</a></li>';

	$categories = explode('|', $qaconfig['qa_category']); // 구분자가 | 로 되어 있음
	for ($i=0; $i<count($categories); $i++) {
		$category = trim($categories[$i]);
		if ($category=='') continue;

		if($is_guest && $category != "기타") continue;

		$category_msg = '';

		if ($category==$sca) { // 현재 선택된 카테고리라면
			$category_option .= '<li class="on"><a href="'.($category_href."?sca=".urlencode($category)).'"';

		}else {
			$category_option .= '<li><a href="'.($category_href."?sca=".urlencode($category)).'"';
		}
		$category_option .= '>'.$category_msg.$category.'</a></li>';
	}
}

if(is_file($skin_file)) {
	$sql_common = " from {$g5['qa_content_table']} ";
	$sql_search = " where qa_type = '0' ";

	if(!$is_admin && !$is_guest){
		$sql_search .= " and (mb_id = '{$member['mb_id']}' or INSTR(qa_category, '기타') > 0) ";
	}

	if($is_guest){
		$sql_search .= " and INSTR(qa_category, '기타') > 0 ";
	}

	if($sca) {
		if (preg_match("/[a-zA-Z]/", $sca))
			$sql_search .= " and INSTR(LOWER(qa_category), LOWER('$sca')) > 0 ";
		else
			$sql_search .= " and INSTR(qa_category, '$sca') > 0 ";
	}

	$stx = trim($stx);
	if($stx) {
		if (preg_match("/[a-zA-Z]/", $stx))
			$sql_search .= " and ( INSTR(LOWER(qa_subject), LOWER('$stx')) > 0 or INSTR(LOWER(qa_content), LOWER('$stx')) > 0 )";
		else
			$sql_search .= " and ( INSTR(qa_subject, '$stx') > 0 or INSTR(qa_content, '$stx') > 0 ) ";
	}

	$sql_order = " order by qa_num ";

	$sql = " select count(*) as cnt
	$sql_common
	$sql_search ";
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$page_rows = G5_IS_MOBILE ? $qaconfig['qa_mobile_page_rows'] : $qaconfig['qa_page_rows'];
	$total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
	if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $page_rows; // 시작 열을 구함

	$sql = " select *
	$sql_common
	$sql_search
	$sql_order
	limit $from_record, $page_rows ";
	$result = sql_query($sql);
	//echo $sql;

	$list = array();
	$num = $total_count - ($page - 1) * $page_rows;
	$subject_len = G5_IS_MOBILE ? $qaconfig['qa_mobile_subject_len'] : $qaconfig['qa_subject_len'];
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$list[$i] = $row;

		$list[$i]['category'] = get_text($row['qa_category']);
		$list[$i]['subject'] = conv_subject($row['qa_subject'], $subject_len, '…');
		if ($stx) {
			$list[$i]['subject'] = search_font($stx, $list[$i]['subject']);
		}

		$list[$i]['view_href'] = G5_BBS_URL.'/qaview.php?qa_id='.$row['qa_id'].$qstr;

		$list[$i]['icon_file'] = '';
		if(trim($row['qa_file1']) || trim($row['qa_file2']))
			$list[$i]['icon_file'] = '<img src="'.$qa_skin_url.'/img/icon_file.gif">';

		$list[$i]['name'] = get_text($row['qa_name']);
		// 사이드뷰 적용시
		//$list[$i]['name'] = get_sideview($row['mb_id'], $row['qa_name']);
		$list[$i]['date'] = substr($row['qa_datetime'], 2, 8);

		$list[$i]['num'] = $num - $i;
	}

	$is_checkbox = false;
	$admin_href = '';
	if($is_admin) {
		$is_checkbox = true;
		$admin_href = G5_ADMIN_URL.'/qa_config.php';
	}

	$list_href = G5_BBS_URL.'/qalist.php';
	if(!$is_guest) {
		$write_href = G5_BBS_URL.'/qawrite.php';
	}

	$list_pages = preg_replace('/(\.php)(&amp;|&)/i', '$1?', get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, './qalist.php'.$qstr.'&amp;page='));

	$stx = get_text(stripslashes($stx));
	include_once($skin_file);
} else {
	echo '<div>'.str_replace(G5_PATH.'/', '', $skin_file).'이 존재하지 않습니다.</div>';
}

include_once('./qatail.php');
?>
