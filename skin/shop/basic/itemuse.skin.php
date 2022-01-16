<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_SKIN_URL.'/style.css">', 0);
?>
<h3 class="blind">REVIEW</h3>
<div class="item_row_list">
	<ul class="count4">
		<?
		$bestsql = "select * $sql_common and is_best = '1' order by is_id desc limit 0, 10 ";
		$bestresult = sql_query($bestsql);
		for ($i=0; $row=sql_fetch_array($bestresult); $i++){
			$is_star    = get_star($row['is_score']);
			$is_name    = get_text($row['is_name']);
			$is_subject = conv_subject($row['is_subject'],50,"…");
			//$is_content = ($row['wr_content']);
			$is_content = get_view_thumbnail(conv_content($row['is_content'], 1), $thumbnail_width);

			$file_count = 0;
			$src = '';
			if($row['is_file']){
				$fi_sql = " select * from lt_shop_item_use_file where is_id = '".$row['is_id']."' order by bf_no ";
				$fi_result = sql_query($fi_sql);
				while ($fi_row = sql_fetch_array($fi_result)){
					$filepath = G5_DATA_PATH.'/file/itemuse';
					$no = $fi_row['bf_no'];
					if($fi_row['bf_type'] != '0'){
						$file[$no]['path'] = G5_DATA_URL.'/file/itemuse';
						$file[$no]['size'] = get_filesize($fi_row['bf_filesize']);
						$file[$no]['datetime'] = $fi_row['bf_datetime'];
						$file[$no]['source'] = addslashes($fi_row['bf_source']);
						$file[$no]['file'] = $fi_row['bf_file'];
						$file[$no]['image_width'] = $fi_row['bf_width'] ? $fi_row['bf_width'] : 640;
						$file[$no]['image_height'] = $fi_row['bf_height'] ? $fi_row['bf_height'] : 480;
						$file[$no]['image_type'] = $fi_row['bf_type'];

						$thumb = thumbnail($file[$no]['file'], $filepath, $filepath, 228, 228, false, false, 'center', false, $um_value='80/0.5/3');
						$file[$no]['thumb'] = $thumb;

						$src = G5_DATA_URL.'/file/itemuse/'.$fi_row['bf_file'];
						break;
					}
				}
			}
			?>
			<li>
				<a onclick="best_review_popup('<?=$row['is_id']; ?>');" style="cursor: pointer;">
					<div class="photo"><img src="<?=$src ?>" alt=""></div>
					<div class="cont">

						<div class="star_rating">
							<span class="num"><?=$is_star; ?></span>
							<div class="star">
								<!-- width = 평점 2배 -->
								<div class="star_bar"><span class="bar" style="width:<?=$is_star*20; ?>%;"></span></div>
							</div>
						</div>
						<span class="text"><?=$is_content; ?></span>
					</div>
				</a>
			</li>
		<? } ?>
	</ul>
</div>
<!-- best end -->

<div class="grid border_box review_info">
	<div class="review_star">
		<p class="title bold">구매 고객 총 평점</p>
		<p class="text">총<span class="point"><?=number_format($total_count); ?></span>건 REVIEW 기준</p>
		<div class="star big">
			<!-- width = 평점 2배 -->
			<span class="star_num"><strong><?=$star_score ?></strong>/5</span>
			<div class="star_bar"><span class="bar" style="width:<?=$star_score*20 ?>%;"></span></div>
		</div>
	</div>
	<div class="graph_wrap">
		<div class="graph_box">
			<ul>
				<li>
					<div class="graph_bar">
						<span class="bar">
							<!-- 높이값 평점 2배 -->
							<span class="in_txt <?=($age20best)?"best":"" ?>" style="height:<?=($age20score==0)?20:$age20score*20 ?>%;"><?=$age20score ?></span>
						</span>
					</div>
					<span class="txt">20대</span>
				</li>
				<li>
					<div class="graph_bar">
						<span class="bar">
							<span class="in_txt <?=($age30best)?"best":"" ?>" style="height:<?=($age30score==0)?20:$age30score*20 ?>%;"><?=$age30score?></span>
						</span>
					</div>
					<span class="txt">30대</span>
				</li>
				<li>
					<div class="graph_bar">
						<span class="bar">
							<span class="in_txt <?=($age40best)?"best":"" ?>" style="height:<?=($age40score==0)?20:$age40score*20 ?>%;"><?=$age40score?></span>
						</span>
					</div>
					<span class="txt">40대</span>
				</li>
				<li>
					<div class="graph_bar">
						<span class="bar">
							<span class="in_txt <?=($age50best)?"best":"" ?>" style="height:<?=($age50score==0)?20:$age50score*20 ?>%;"><?=$age50score?></span>
						</span>
					</div>
					<span class="txt">50대</span>
				</li>
			</ul>
		</div>
	</div>
	<div class="right_cmt">
		<?
		if($age20best) echo '이제품은<br><span class="point">20대</span> 에<br>가장 인기가 많아요!';
		else if($age30best) echo '이제품은<br><span class="point">30대</span> 에<br>가장 인기가 많아요!';
		else if($age40best) echo '이제품은<br><span class="point">40대</span> 에<br>가장 인기가 많아요!';
		else if($age50best) echo '이제품은<br><span class="point">50대</span> 에<br>가장 인기가 많아요!';
		?>
	</div>
	<!--
	<div class="btn_group">
		<a href="<?=$itemuse_form; ?>" class="qa_wr itemuse_form " onclick="return false;"><button type="button" class="btn big green"><span>REVIEW 작성</span></button></a>
	</div>
-->
</div>

<!-- 상품 사용후기 시작 { -->
<div class="grid tab_cont_wrap">
	<div class="tab none">
		<ul class="type2 alignL onoff tab_btn">
			<li class="on"><a href="#" name="review"><span>전체리뷰</span></a></li>
			<li class=""><a href="#"><span>일반리뷰</span></a></li>
			<li class=""><a href="#"><span>프리미엄리뷰</span></a></li>
		</ul>
		<div class="none_sel">
			<span class="select">
				<select name="" title="목록">
					<option value="" selected="">최신등록순</option>
				</select>
			</span>
		</div>
	</div>
	<div class="tab_cont">
		<!-- tab1 -->
		<div class="tab_inner">
			<div class="tbl_list">
				<?
				$thumbnail_width = 500;
				for ($i=0; $row=sql_fetch_array($result); $i++)
				{
					$is_star    = get_star($row['is_score']);
					$is_name    = get_text($row['is_name']);
					$is_subject = conv_subject($row['is_subject'],50,"…");
			//$is_content = ($row['wr_content']);
					$is_content = get_view_thumbnail(conv_content($row['is_content'], 1), $thumbnail_width);

					$hash = md5($row['is_id'].$row['is_time'].$row['is_ip']);
					$file_count = 0;
					$movie_count = 0;
					if($row['is_file']){
						$file['count'] = 0;
						$movie['count'] = 0;
						$fi_sql = " select * from lt_shop_item_use_file where is_id = '".$row['is_id']."' order by bf_no ";
						$fi_result = sql_query($fi_sql);
						while ($fi_row = sql_fetch_array($fi_result))
						{
							$filepath = G5_DATA_PATH.'/file/itemuse';
							$no = $fi_row['bf_no'];

							if($fi_row['bf_type'] == '0'){
						//movie
								$movie[$no]['path'] = G5_DATA_URL.'/file/itemuse';
								$movie[$no]['size'] = get_filesize($fi_row['bf_filesize']);
								$movie[$no]['datetime'] = $fi_row['bf_datetime'];
								$movie[$no]['source'] = addslashes($fi_row['bf_source']);
								$movie[$no]['file'] = $fi_row['bf_file'];
								$movie['count']++;
							} else {
								$file[$no]['path'] = G5_DATA_URL.'/file/itemuse';
								$file[$no]['size'] = get_filesize($fi_row['bf_filesize']);
								$file[$no]['datetime'] = $fi_row['bf_datetime'];
								$file[$no]['source'] = addslashes($fi_row['bf_source']);
								$file[$no]['file'] = $fi_row['bf_file'];
								$file[$no]['image_width'] = $fi_row['bf_width'] ? $fi_row['bf_width'] : 640;
								$file[$no]['image_height'] = $fi_row['bf_height'] ? $fi_row['bf_height'] : 480;
								$file[$no]['image_type'] = $fi_row['bf_type'];

								$thumb = thumbnail($file[$no]['file'], $filepath, $filepath, 228, 228, false, false, 'center', false, $um_value='80/0.5/3');
								$file[$no]['thumb'] = $thumb;
								$file['count']++;
							}
						}
						$file_count = $file['count'];
						$movie_count = $movie['count'];
					}

					?>
					<div class="order_cont" is_id="<?=$row['is_id']?>" is_type="<?=$row['is_type']?>">
						<div class="head">
							<div class="user_bar">
								<?
								$mb_dir = substr($row['mb_id'],0,2);
								$icon_file = G5_DATA_PATH.'/member_image/'.$mb_dir.'/'.$row['mb_id'].'.gif';
								if (file_exists($icon_file)) {
									$icon_url = G5_DATA_URL.'/member_image/'.$mb_dir.'/'.$row['mb_id'].'.gif';
									?>
									<span class="photo"><img src="<?=$icon_url;?>" alt=""/></span>
								<? }else {?>
									<span class="photo"><img src="/img/default.jpg" alt="default photo" /></span>
								<? } ?>
								<span class="name"><?=$row['mb_id']; ?></span>

								<? if ($row['mb_id'] == $member['mb_id']) { ?>
									<div class="floatR">
										<a href="<?=$itemuse_form."&amp;is_id={$row['is_id']}&amp;w=u"; ?>" class="itemuse_form btn gray_line small " >수정</a>
										<a href="<?=$itemuse_formupdate."&amp;is_id={$row['is_id']}&amp;w=d&amp;hash={$hash}"; ?>" class="itemuse_delete btn gray_line small ">삭제</a>
									</div>
								<? } ?>
							</div>
						</div>
						<div class="body">
							<div class="cont">
								<div class="info">
									<!-- span class="category black">Best</span -->
									<?=($row['is_best'])?'<span class="category black">Best</span>':''; ?>
									<p><?=$is_subject; ?></p>
									<div class="review_star">
										<div class="star small">
											<!-- width = 평점 2배 -->
											<span class="star_num">별점 : <?=$is_star; ?></span>
											<div class="star_bar"><span class="bar" style="width:<?=$is_star*20; ?>%;"></span></div>
										</div>
									</div>
								</div>
							</div>
							<div class="clear"></div>
							<div class="text">
								<?=$is_content; // 사용후기 내용 ?>
							</div>
							<? if($file_count) { ?>
								<div class="view">
									<div class="scroll_wrap">
										<div class="inner_scroll">
											<ul>
												<? for ($j = 0; $j < $file_count; $j++) {
													$src = G5_DATA_URL.'/file/itemuse/'.$file[$j]['thumb'];
													echo '<li><img src="'.$src.'"></li>';
												}?>
											</ul>
										</div>
									</div>
								</div>
							<? } ?>
							<? if($movie_count) { ?>
								<div class="view ico_video">
									<? for ($j = 0; $j < $movie_count; $j++) {
										$src = G5_DATA_URL.'/file/itemuse/'.$movie[$no]['file'];
										echo '<video controls width="350px"><source src="'.$src.'" type="video/mp4" width="350px">Your browser does not support the video tag.</video>';
									}?>
								</div>
							<? } ?>
							<div class="clear"></div>
							<div class="btn_comm">
								<?
								$sql = " select  count(*) cnt
								,ifnull(sum(if(mb_id = '{$member['mb_id']}',1,0)),0) mb_cnt
								from    lt_shop_item_use_good
								where   is_id = '{$row['is_id']}' ";
								$pickYN = sql_fetch($sql);

								$good_href = './itemgood.php?is_id='.$row['is_id'].'&amp;good=good';
								?>
								<!-- 찜 눌르면 class="on" 추가 -->
								<button type="button" class="pick ico itemusegood <? if ($pickYN['mb_cnt']) echo 'on';?>" href="<?=$good_href.'&amp;'.$qstr ?>"><span class="blind">찜</span><?=$pickYN['cnt']?></button>
								<span class="date line floatR"><?php echo $row['is_time'] ?></span>
							</div>
						</div>
					</div>

				<? }

				if (!$i) echo '<p class="sit_empty"></p>';
				?>
			</div>
		</div>
		<!-- tab2 -->
		<div class="tab_inner">
			<div class="tbl_list" id="review1">
			</div>
		</div>
		<!-- tab3 -->
		<div class="tab_inner">
			<div class="tbl_list" id="review2">
			</div>
		</div>
	</div>
	<div id="best_review"></div>

	<?
	echo itemuse_page($config['cf_mobile_pages'], $page, $total_page, "./itemuse.php?it_id=$it_id&amp;page=", "");
	?>
</div>

<script>
	$(function(){
		$(document).on("click", ".itemusegood", function() {
			var href = $(this).attr('href');
			$pick = $(this);

			$pick.prop("disabled", true);
			
			$.post(
				href,
				{ js: "on" },
				function(data) {
					$pick.prop("disabled", false);
					if(data.error) {
						alert(data.error);
						return false;
					}

					if(data.flag) {
						if(data.flag == 'ON'){
							$pick.removeClass('on').addClass('on');
						} else {
							$pick.removeClass('on');
						}
					}
					if(data.count) {
						$pick.text('');
						$pick.append('<span class="blind">찜</span>'+data.count);
					}
				}, "json"
				);
		});



		/*$(".itemuse_form").click(function(){
			window.open(this.href, "itemuse_form", "width=810,height=680,scrollbars=1");
			return false;
		});*/

		$(".itemuse_delete").click(function(){
			if (confirm("정말 삭제 하시겠습니까?\n\n삭제후에는 되돌릴수 없습니다.")) {
				return true;
			} else {
				return false;
			}
		});

		$(".pg_page").click(function(){
			$("#itemuse").load($(this).attr("href"));
			return false;
		});

		var review1 = "";
		var review2 = "";

		$(".order_cont").each(function(){
			var is_type = $(this).attr("is_type");

			if(is_type == "1") {
				review2 += '<div class="order_cont">'+$(this).html()+'</div>';
			} else {
				review1 += '<div class="order_cont">'+$(this).html()+'</div>';
			}
		});

		$("#review1").html(review1);
		$("#review2").html(review2);
	});

	function best_review_popup(is_id)
	{
	//alert(is_id);
	var $table = $("#best_review");
	$.post(
		"<?=G5_SHOP_URL?>/itemuse_best_view.php",
		{ is_id:is_id },
		function(data) {
			$table.empty().html(data);
		}
		);
}


</script>
<!-- } 상품 사용후기 끝 -->
