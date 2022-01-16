<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$sct_sort_href = $_SERVER['SCRIPT_NAME'].'?';
if($ca_id){
	$sct_sort_href .= 'ca_id='.$ca_id;
} else if($ev_id) {
	$sct_sort_href .= 'ev_id='.$ev_id;
}

if($skin){
	$sct_sort_href .= '&amp;skin='.$skin;
}
$sct_sort_href .= '&amp;sort=';

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);
?>

<!-- 상품 정렬 선택 시작 { -->
<div class="tab">
	<ul class="type2">
		<li <?=($sort == 'it_sum_qty')?'class="on"':'' ?> ><a href="<?=$sct_sort_href; ?>it_sum_qty&amp;sortodr=desc"><span>인기순</span></a></li>
		<li <?=($sort == 'it_update_time')?'class="on"':'' ?>><a href="<?=$sct_sort_href; ?>it_update_time&amp;sortodr=desc"><span>최신순</span></a></li>
		<li <?=($sort == 'it_price' && $sortodr=='asc')?'class="on"':'' ?>><a href="<?=$sct_sort_href; ?>it_price&amp;sortodr=asc"><span>낮은가격순</span></a></li>
		<li <?=($sort == 'it_price' && $sortodr=='desc')?'class="on"':'' ?>><a href="<?=$sct_sort_href; ?>it_price&amp;sortodr=desc"><span>높은가격순</span></a></li>
	</ul>
</div>
