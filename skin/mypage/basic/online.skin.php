<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once('../common.php');
if(!isset($month)) $month = 3;
$month = isset($month) ? preg_replace('/[^0-9]/i', '', $month) : '3';

$sql_list = "select * from lt_write_online where mb_id = '{$member['mb_id']}' and wr_is_comment = 0 and wr_datetime >= DATE_ADD(now(), INTERVAL -".$month." MONTH) ";
$sql_board = "select * from lt_board where bo_table='{$type}'";
$sql_count = "select count(*) as cnt from lt_write_online where mb_id = '{$member['mb_id']}' and wr_is_comment = 0 and wr_datetime >= DATE_ADD(now(), INTERVAL -".$month." MONTH) ";
$board = sql_fetch($sql_board);
$count = sql_fetch($sql_count);
$result = sql_query($sql_list);

?>
<div class="title_bar none">
	<div class="none_sel floatR">
		<span class="select">
			<select name="month" onchange="location.href='<?=$_SERVER['SCRIPT_NAME']?>?month='+$(this).val();">
				<option value="3" <?=get_selected($month, '3') ; ?>>3개월</option>
				<option value="6" <?=get_selected($month, '6') ; ?>>6개월</option>
				<option value="12" <?=get_selected($month, '12') ; ?>>1년</option>
			</select>
		</span>
	</div>
</div>
<p class="txt_total">총<strong><?=$count['cnt'];?></strong>건</p>

<!-- 컨텐츠 게시판 : 웹진형 가로타입 -->
<div class="div_wrap">
	<ul class="reviewList2">
		<?

		while ($list=sql_fetch_array($result)) {
			$nDate = date("Y-m-d",time());
			$bo_newYN = "N";
			$bo_new = $board['bo_new'];
			$bo_newYN = intval(strtotime($list['wr_datetime'].' +'.$bo_new.' hours')-strtotime($nDate)) > 0 ? 'Y' : 'N';

			?>
			<li>
				<div class="ListBox">
					<div class="cont">
						<? if($list['wr_file'] == 1){
							$sql2 = " select bf_file from lt_board_file where bo_table='online' and wr_id= {$list['wr_id']} ";
							$row2 = sql_fetch($sql2);
							$sum_img_url = G5_DATA_URL.'/file/online/'.$row2['bf_file'];
							?>
							<div class="photo">
								<a href="<?=G5_BBS_URL.'/board.php?bo_table='.$board['bo_table'].'&wr_id='.$list['wr_id']; ?>"><img src="<?=$sum_img_url;?>" alt="" /></a>
							</div>
						<? }?>
						<p class="title bold"><?=$list['wr_subject']?><? if($bo_newYN == 'Y'){ ?> <span class="new">N</span><? } ?></p>
						<p><?=$list['wr_3']?></p>
						<span class="date"><?=$list[$i]['wr_datetime']?></span>
					</div>
					<div class="user-area">
						<div class="user-info">
							<?
							$wr_id = $list['wr_id'];

							$mb_dir = substr($list['mb_id'],0,2);
							$icon_file = G5_DATA_PATH.'/member/'.$mb_dir.'/'.$list['mb_id'].'.gif';
							$icon_url = "";
							if (file_exists($icon_file)) {
								$icon_url = G5_DATA_URL.'/member/'.$mb_dir.'/'.$list['mb_id'].'.gif';
							}

							?>
							<?if($icon_url){?><span class="user-photo"><img src="<?=$icon_url;?>" alt=""></span><? } ?>
							<span class="user-name"><?=$list['wr_name']?></span>
						</div>
						<div class="user-like">
							<!-- 찜 눌르면 class="on" 추가 -->
							<?
							$sql = " select bg_flag from {$g5['board_good_table']}
							where bo_table = '{$bo_table}'
							and wr_id = '{$list['wr_id']}'
							and mb_id = '{$member['mb_id']}'
							and bg_flag in ('good', 'nogood') ";
							$pickYN = sql_fetch($sql);
							?>
							<button type="button" class="pick ico <? if ($pickYN['bg_flag']) echo 'on';?>" ><span class="blind">찜</span><?=$list['wr_good']?></button>
							<button type="button" class="review ico"><span class="blind">댓글</span><?=$list['wr_comment']?></button>
						</div>
					</div>
				</div>
			</li>
		<? } ?>
	</ul>
	<div class="clear"></div>
	<? if($count['cnt'] == 0) echo '<p class="sit_empty tcenter">등록된 글이 없습니다.</p>';	?>
</div>

