<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once('../common.php');
?>

<div class="content magazine">
	<!-- 컨텐츠 시작 -->
	<?
	$bannersql = " select * from lt_board where bo_use = 1 and bo_table='living'";
	$result = sql_query($bannersql);

	while ($row=sql_fetch_array($result)) {

		$main_view_data = json_decode(str_replace('\\','',$row['bo_banner']), true);
		?>
		<div class="visual_area">
			<div class="swiper-container">
				<div class="swiper-wrapper">
					<? for($i=0; $i<$main_view_data['bannerCount']; $i++) {
						$img_data = $main_view_data['imgFile'][$i];
						$link_url = $img_data['linkURL'];
						$muse = $img_data['muse'];
						if($muse == 1){
							$img_file = G5_DATA_PATH.'/banner/living/'.$img_data['mimgFile'];
						}else {
							$img_file = G5_DATA_PATH.'/banner/living/'.$img_data['imgFile'];
						}

						if ($img_data['imgFile'] && file_exists($img_file)) {
							if($muse == 1){
								$img_url = G5_DATA_URL.'/banner/living/'.$img_data['mimgFile'];
							} else {
								$img_url = G5_DATA_URL.'/banner/living/'.$img_data['imgFile'];
							}

							?>
							<div class="swiper-slide"><a href="<?=$link_url?>"><img src="<?=$img_url?>" alt="" /></a></div>
						<? } else { ?>
							<div class="swiper-slide"><a href="<?=$link_url?>"><img src="<?=G5_MOBILE_URL; ?>/img/theme_img.jpg" alt="" /></a></div>
						<? }
					}?>
				</div>
				<div class="swiper-pagination"></div>
			</div>
			<script>
				var swiperMain_visual = new Swiper('.visual_area .swiper-container', {
					slidesPerView: 'auto',
					spaceBetween: 0,
					loop: true,
					autoplay: {
						delay: 2500,
						disableOnInteraction: false,
					},
					pagination: {
						el: '.swiper-pagination',
						clickable: true,
					},
				});
			</script>
		</div>
	<? }?>
	<div class="grid">
		<h2 class="blind">온라인집들이</h2>
		<div class="type-wrap">
			<!-- 분류 선택시 f_200.30.001_m.html 팝업 -->
			<div class="type-list">
				<ul>
					<? for ($i=1; $i<10; $i++) {
						if($board['bo_'.$i.'_subj'] != ''){
							?>
							<li><button class="bo_option" name="<?='btn_bo_subj'?>" targetID="bo_option_<?=$i?>" SEQ="<?=$i-1;?>"><?=$board['bo_'.$i.'_subj'];?></button></li>
							<?

							if($board['bo_'.$i] != '') {
								$bo_option_list = explode(',', $board['bo_'.$i]);
								$option_count = count($bo_option_list);
								$bo_select = '<select id="bo_option_'.$i.'" name="sel_bo_option[]" hidden >'.PHP_EOL;
								$bo_select .= '<option value="">선택</option>'.PHP_EOL;
								for($j=0; $j<$option_count; $j++) {
									$bo_select .= '<option value="'.$bo_option_list[$j].'">'.$bo_option_list[$j].'</option>'.PHP_EOL;
								}
								$bo_select .= '</select>'.PHP_EOL;

								echo $bo_select.PHP_EOL;
							}

						}
					}?>
				</ul>
			</div>
			<!-- The Modal -->
			<div id="optionModal" class="modal" style="display: none;">
				<!-- Modal content -->
				<div class="content sub">
					<div style="float: right;">
						<a href="#" class="close"><span class="blind">닫기</span></a>
					</div>
					<div class="grid cont" style="border-top-width: 0px;">
						<div class="title_bar" style="overflow:visible;">
							<h3 class="g_title_01" id="optionModalTitle">선택한 :<span class="none"></span></h3>
						</div>
						<div class="list">
							<ul class="type1 pad"  id="optionModalList">
							</ul>
						</div>

					</div>
				</div>
			</div>
			<!--End Modal-->
			<!-- 컨텐츠 분류 : 태그 -->
			<div class="type-tag">
				<ul id="bo_selected_option">


				</ul>
			</div>
		</div>
		<form name="fboardlist" id="fboardlist" action="./board.php" method="post">
			<input type="hidden" name="bo_table" value="<?=$bo_table ?>">
			<input type="hidden" name="sfl" value="<?=$sfl ?>">
			<input type="hidden" name="stx" value="<?=$stx ?>">
			<input type="hidden" name="spt" value="<?=$spt ?>">
			<input type="hidden" name="sst" value="<?=$sst ?>">
			<input type="hidden" name="sod" value="<?=$sod ?>">
			<input type="hidden" name="page" value="<?=$page ?>">
			<input type="hidden" name="sw" value="">
			<input type="hidden" name="wr_6" id="wr_6" value="<?=$wr_6 ?>">


		</form>
		<div class="card-list">
			<ul>
				<?
				for ($i=0; $i<count($list); $i++) {
					$nDate = date("Y-m-d",time()); // 오늘 날짜를 출력하겠지요?
					$bo_newYN = "N";
					$bo_new = $board['bo_new'];
					$bo_newYN = intval(strtotime($list[$i]['wr_datetime'].' +'.$bo_new.' hours')-strtotime($nDate)) > 0 ? 'Y' : 'N';

					?>
					<li>
						<a href="<?=$list[$i]['href'] ?>">
							<div class="cont">
								<? if($list[$i]['wr_file'] == 1){
									$sql2 = " select bf_file from lt_board_file where bo_table='living' and wr_id= {list[$i]['wr_id']} ";
									$row2 = sql_fetch($sql2);
									$sum_img_url = G5_DATA_URL.'/file/living/'.$row2['bf_file'];
									?>
									<div class="photo">
										<img src="<?=$sum_img_url;?>" alt="" />
									</div>
								<? }?>
								<p class="title bold"><?=$list[$i]['wr_subject']?>
								<? if($bo_newYN == 'Y'){
									?>
									<span class="new">N</span>
									<? }?></p>
									<p><?=$list[$i]['wr_3']?></p>
									<span class="date">2019-01-19</span>
								</div>
							</a>
							<div class="user-area">
								<div class="user-info">
									<?
									$wr_id = $list[$i]['wr_id'];

									$mb_dir = substr($list[$i]['mb_id'],0,2);
									$icon_file = G5_DATA_PATH.'/member/'.$mb_dir.'/'.$list[$i]['mb_id'].'.gif';
									$icon_url = "";
									if (file_exists($icon_file)) {
										$icon_url = G5_DATA_URL.'/member/'.$mb_dir.'/'.$list[$i]['mb_id'].'.gif';
									}

									?>
									<span class="user-photo"><img src="<?=$icon_url;?>" alt=""></span>
									<span class="user-name"><?=$list[$i]['wr_name']?></span>
								</div>
								<div class="user-like">
									<?
									$sql = " select bg_flag from {$g5['board_good_table']}
									where bo_table = '{$bo_table}'
									and wr_id = '{$list[$i]['wr_id']}'
									and mb_id = '{$member['mb_id']}'
									and bg_flag in ('good', 'nogood') ";
									$pickYN = sql_fetch($sql);
									?>
									<button type="button" class="pick ico <? if ($pickYN['bg_flag']) echo 'on';?>" ><span
										class="blind">찜</span><?=$list[$i]['wr_good']?></button>
										<button type="button" class="review ico"><span
											class="blind">댓글</span><?=$list[$i]['wr_comment']?></button>
										</div>
									</div>
								</li>
							<? } ?>




						</ul>
					</div>

				</div>
				<!-- 컨텐츠 종료 -->
			</div>
			<!-- 게시판 목록 끝 -->

			<script>
				function bo_option_select(optionval,seq){

					var wr_6 = $('#wr_6').val();
					var new_wr_6 = '';
					var wr_6Split = '';
					if(wr_6 == ''){
						wr_6 = ',,,,,,,,';
					}
					wr_6Split = wr_6.split(',');
					for ( var i in wr_6Split ) {
						if(i == seq){
							wr_6Split[i] = optionval;
						}
					}
					for ( var i in wr_6Split ) {
						if(i == 0){
							new_wr_6 += wr_6Split[i];
						}else {
							new_wr_6 += ','+wr_6Split[i];
						}
					}
					$('#wr_6').val(new_wr_6);

					$(".modal").css("display","none");

					$('#fboardlist').submit();
				};


				function bo_selected_option_delete(seq){
					var wr_6 = $('#wr_6').val();
					var new_wr_6 = '';
					if(wr_6 != ''){
						var wr_6Split = wr_6.split(',');

						for ( var i in wr_6Split ) {
							if(i == seq) {
								wr_6Split[i] = '';
							}
						}
					}
					for ( var i in wr_6Split ) {
						if(i == 0){
							new_wr_6 += wr_6Split[i];
						}else {
							new_wr_6 += ','+wr_6Split[i];
						}
					}
					$('#wr_6').val(new_wr_6);
					$('#fboardlist').submit();
				};
				$(document).ready(function(){

					$(".bo_option").click(function() {
						var target_id = $(this).attr("targetID");
						var seq = $(this).attr("SEQ");
						var optionName = $(this).text();
						$('#optionModalTitle').html(optionName);

						var optionList = "";
						var $option = $('#'+target_id+' option');

						$option.each(function() {
							bo_value = $(this).val();


							if(bo_value != ""){
								optionList += "<li>";
								optionList += "<a onclick='bo_option_select(\""+bo_value+"\", \""+seq+"\");'>";
								optionList += "<span class=\"bold\">"+bo_value+"</span>";
								optionList += "</a></li>";

							}
						});

						$('#optionModalList').html(optionList);

						$("#optionModal").css("display","block");
					});

					$.bo_selected_option_create = function(){
						$('#bo_selected_option').html('');
						var wr_6 = $('#wr_6').val();
						var html = '';

						if(wr_6 != ''){
							var wr_6Split = wr_6.split(',');
							for ( var i in wr_6Split ) {
								if(wr_6Split[i] != '')
									html += '<li><span>'+wr_6Split[i]+'</span><a onclick="bo_selected_option_delete(\''+i+'\');" >닫기</a></li>';
							}
							$('#bo_selected_option').html(html);
						}
					};

					$(".close").click(function() {
						$(".modal").css("display","none");
					});

					$.bo_selected_option_create();
				});


			</script>
