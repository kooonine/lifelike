<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$sct_sort_href = $_SERVER['SCRIPT_NAME'] . '?';
if ($ca_id)
	$sct_sort_href .= 'ca_id=' . $ca_id;
else if ($ev_id)
	$sct_sort_href .= 'ev_id=' . $ev_id;
if ($skin)
	$sct_sort_href .= '&amp;skin=' . $skin;
$sct_sort_href .= '&amp;sort=';

$sct_cate_href = $_SERVER['SCRIPT_NAME'] . '?';
if ($sort && $sortodr)
	$sct_cate_href .= 'sort=' . $sort . '&amp;sortodr=' . $sortodr;
else if ($ev_id)
	$sct_cate_href .= 'ev_id=' . $ev_id;
$sct_cate_href .= '&amp;ca_id=';

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.G5_MSHOP_SKIN_URL.'/style.css">', 0);

$str = '';
$exists = false;

$len2 = 6;

// $sql = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where ca_id like '".substr($ca_id,0,4)."%' and length(ca_id) = $len2 and ca_use = '1' order by ca_order, ca_id ";
$sql = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where ca_id like '" . substr($ca_id, 0, 4) . "%' and length(ca_id) = $len2 and ca_use = '1' and ca_id NOT IN (102020, 102030) order by ca_order, ca_id ";
$result = sql_query($sql);
while ($row = sql_fetch_array($result)) {

	$row2 = sql_fetch(" select count(*) as cnt from {$g5['g5_shop_item_table']} where (ca_id like '{$row['ca_id']}%' or ca_id2 like '{$row['ca_id']}%' or ca_id3 like '{$row['ca_id']}%') and it_use = '1'  ");

	$str .= '<option value="' . $sct_cate_href . $row['ca_id'] . '" ' . (($ca_id == $row['ca_id']) ? "selected" : "") . '>' . $row['ca_name'] . ' (' . $row2['cnt'] . ')</option>';
	$exists = true;
}
?>
<div class="title_bar">
	<div class="none_sel floatR">
		<?php if ($exists) { ?>
		<span class="select">
			<select id="sct_ct_1" title="카테고리 목록" onchange="location.href=this.value;">
				<option value="<?php echo $sct_cate_href . substr($ca_id, 0, 4); ?>">제품전체</option>
				<?php echo $str ?>
			</select>
		</span>
		<?php } ?>
		<span class="select">
			<select id="sct_sort" title="정렬 목록" onchange="location.href=this.value;">
				<option <?php echo ($sort == 'it_sum_qty') ? 'selected' : '' ?> value="<?php echo $sct_sort_href; ?>it_sum_qty&amp;sortodr=desc">인기순</option>
				<option <?php echo ($sort == 'it_update_time') ? 'selected' : '' ?> value="<?php echo $sct_sort_href; ?>it_update_time&amp;sortodr=desc">최신순</option>
				<option <?php echo ($sort == 'it_price' && $sortodr == 'asc') ? 'selected' : '' ?> value="<?php echo $sct_sort_href; ?>it_price&amp;sortodr=asc">낮은가격순</option>
				<option <?php echo ($sort == 'it_price' && $sortodr == 'desc') ? 'selected' : '' ?> value="<?php echo $sct_sort_href; ?>it_price&amp;sortodr=desc">높은가격순</option>
			</select>
		</span>
	</div>
</div>