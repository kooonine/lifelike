<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_SKIN_URL.'/style.css">', 0);
?>
<div id="sit_pvi_nw" class="new_win">
	<h1 id="win_title">상품 이미지 새창 보기</h1>
	<div id="sit_pvi_nwbig">
		<?
		$thumbnails = array();
		for($i=1; $i<=10; $i++) {
			if(!$row['it_img'.$i]){
				continue;
			}
			$file = G5_DATA_PATH.'/item/'.$row['it_img'.$i];
			if(is_file($file)) {
				// 썸네일
				$thumb = get_it_thumbnail($row['it_img'.$i], 60, 60);
				$thumbnails[$i] = $thumb;
				$imageurl = G5_DATA_URL.'/item/'.$row['it_img'.$i];
				?>
				<span>
					<a href="javascript:window.close();">
						<img src="<?=$imageurl; ?>" width="<?=$size[0]; ?>" height="<?=$size[1]; ?>" alt="<?=$row['it_name']; ?>" id="largeimage_<?=$i; ?>">
					</a>
				</span>
			<? } ?>
		<? } ?>
	</div>

	<?
	$total_count = count($thumbnails);
	$thumb_count = 0;
	if($total_count > 0) {
		echo '<ul>';
		foreach($thumbnails as $key=>$val) {
			echo '<li><a href="'.G5_SHOP_URL.'/largeimage.php?it_id='.$it_id.'&amp;no='.$key.'" class="img_thumb">'.$val.'</a></li>';
		}
		echo '</ul>';
	}
	?>

	<div class="win_btn">
		<button type="button" onclick="javascript:window.close();" class="btn_close">창닫기</button>
	</div>
</div>

<script>
// 창 사이즈 조절
$(window).on("load", function() {
	var w = <?=$size[0]; ?> + 50;
	var h = $("#sit_pvi_nw").outerHeight(true) + $("#sit_pvi_nw h1").outerHeight(true);
	window.resizeTo(w, h);
});

$(function(){
	$("#sit_pvi_nwbig span:eq("+<?=($no - 1); ?>+")").addClass("visible");

	// 이미지 미리보기
	$(".img_thumb").bind("mouseover focus", function(){
		var idx = $(".img_thumb").index($(this));
		$("#sit_pvi_nwbig span.visible").removeClass("visible");
		$("#sit_pvi_nwbig span:eq("+idx+")").addClass("visible");
	});
});
</script>
