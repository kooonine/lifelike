<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once('../common.php');
if(!isset($month)) $month = 3;
$month = isset($month) ? preg_replace('/[^0-9]/i', '', $month) : '3';

$thumbnail_width = 500;
$sql_common = " from {$g5['g5_shop_item_use_table']} where mb_id = '{$member["mb_id"]}' and is_confirm = '1' and is_time >= DATE_ADD(now(), INTERVAL -".$month." MONTH) ";

// 테이블의 전체 레코드수만 얻음
$sql = " select COUNT(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];
?>
<div class="title_bar none">
	<div class="none_sel floatR">
		<span class="select">
			<select name="month" onchange="location.href='<?php echo $_SERVER['SCRIPT_NAME']?>?month='+$(this).val();">
				<option value="3" <?php echo get_selected($month, '3') ; ?>>3개월</option>
				<option value="6" <?php echo get_selected($month, '6') ; ?>>6개월</option>
				<option value="12" <?php echo get_selected($month, '12') ; ?>>1년</option>
			</select>
		</span>
	</div>
</div>
<p class="txt_total">총<strong><?=$total_count?></strong>건</p>
<!-- 컨텐츠 게시판 : 웹진형 가로타입 -->
<div class="div_wrap">
	<?
	$rows = 5;
	$total_page  = ceil($total_count / $rows);
	if ($page < 1) $page = 1;
	$from_record = ($page - 1) * $rows;

	$sql = "select * $sql_common order by is_id desc limit $from_record, $rows ";
	$result = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($result); $i++){
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
			while ($fi_row = sql_fetch_array($fi_result)){
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
					$thumb = thumbnail($file[$no]['file'], $filepath, $filepath, 200, 200, false, false, 'center', false, $um_value='200/0.5/3');
					$file[$no]['thumb'] = $thumb;
					$file['count']++;
				}
			}
			$file_count = $file['count'];
			$movie_count = $movie['count'];
		}

		if ($i == 0) echo '<ol id="sit_use_ol" class="reviewList">';
		?>
		<li>
			<div class="order_cont" is_id="<?=$row['is_id']?>" is_type="<?=$row['is_type']?>" style="min-height:350px; ">
				<div class="head">
					<div class="user_bar">
						<?
						$mb_dir = substr($row['mb_id'],0,2);
						$icon_file = G5_DATA_PATH.'/member_image/'.$mb_dir.'/'.$row['mb_id'].'.gif';
						if (file_exists($icon_file)) {
							$icon_url = G5_DATA_URL.'/member_image/'.$mb_dir.'/'.$row['mb_id'].'.gif';
							?>
							<span class="photo"><img src="<?=$icon_url;?>" alt=""/></span>
						<? } else { ?>
							<span class="photo"><img src="/img/default.jpg" alt="" /></span>
						<? } ?>
						<span class="name"><?=$row['mb_id']; ?></span>
					</div>
				</div>
				<div class="body" style="margin-bottom:50px;">
					<div class="cont">
						<div style="margin:0px;">
							<!-- span class="category black">Best</span -->
							<?=($row['is_best'])?'<span class="category black">Best</span>':''; ?>
							<a href="/shop/item.php?it_id=<?=$row['it_id']?>" class="btn-prd"><p class="bold"><?=$is_subject; ?></p></a>
							<div class="review_star">
								<div class="star small">
									<!-- width = 평점 2배 -->
									<span class="star_num">별점 : <?=$is_star; ?></span>
									<div class="star_bar"><span class="bar" style="width:<?=$is_star*20; ?>%;"></span></div>
								</div>
							</div>
							<!-- a href="/shop/item.php?it_id=<?=$row['it_id']?>" class="btn-prd">제품보기</a -->
						</div>
					</div>
					<div class="clear"></div>
					<div class="text">
						<?=$is_content;?>
					</div>
					<div class="clear"></div>
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
						<div class="clear"></div>
					<? } ?>
					<? if($movie_count) { ?>
						<div class="view ico_video">
							<? for ($j = 0; $j < $movie_count; $j++) {
								$src = G5_DATA_URL.'/file/itemuse/'.$movie[$no]['file'];
								echo '<video controls width="350px"><source src="'.$src.'" type="video/mp4" width="350px">Your browser does not support the video tag.</video>';
							}?>
						</div>
					<? } ?>
				</div>
				<div class="btn_comm2">
					<!-- 찜 눌르면 class="on" 추가 -->
					<button type="button"><i class="axi axi-thumbs-o-up"></i> 0</button>
					<span class="date line floatR"><?php echo $row['is_time'] ?></span>
				</div>
			</div>
		</li>
	<? } ?>
	<? if(!$i) echo '<ul><li><p class="sit_empty tcenter">등록된 글이 없습니다.</p></li></ul>';	?>
	<div class="clear"></div>
</div>
