<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$onoff = $_GET['onoff'];
if($onoff == ""){
	$onoff = "on";
}
?>

<div class="content community type1">
	<!-- 컨텐츠 시작 -->
	<?
	$bannersql = " select * from lt_board where bo_use = 1 and bo_table='experience'";
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
							$img_file = G5_DATA_PATH.'/banner/experience/'.$img_data['mimgFile'];
						}else {
							$img_file = G5_DATA_PATH.'/banner/experience/'.$img_data['imgFile'];
						}

						if ($img_data['imgFile'] && file_exists($img_file)) {
							if($muse == 1){
								$img_url = G5_DATA_URL.'/banner/experience/'.$img_data['mimgFile'];
							} else {
								$img_url = G5_DATA_URL.'/banner/experience/'.$img_data['imgFile'];
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

	<div class="grid ">
		<div class="step-nav">
			<ul>
				<li name="onOffBar" data='on'><a href="#">진행 중</a></li>
				<li name="onOffBar" data='off'> <a href="#">종료</a></li>
			</ul>
		</div>
		<div class="type-wrap">
			<!-- 분류 선택시 f_200.30.001_m.html 팝업 -->
			<div class="type-list">
				<ul>
					<? for ($i=1; $i<10; $i++) {
						if($board['bo_'.$i.'_subj'] != ''){
							?>
							<li><button class="bo_option" name="<?='btn_bo_subj'?>" targetID="bo_option_<?=$i?>" ><?=$board['bo_'.$i.'_subj'];?></button></li>
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
			<div id="optionModal" class="modal" style="display: none;z-index: 999;">
				<!-- Modal content -->
				<div class="">
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

		<div class="list webzine-list">
			<ul class="type2">
				<?
				for ($i=0; $i<count($list); $i++) {
					$nDate = date("Y-m-d",time()); // 오늘 날짜를 출력하겠지요?

					$valDate = Trim($list[$i]['wr_7']); // 폼에서 POST로 넘어온 value 값('yyyy-mm-dd' 형식)

					$bo_new = $board['bo_new'];



					$leftDate = intval((strtotime($nDate)-strtotime($valDate))); // 나머지 날짜값이 나옵니다.
					if($leftDate > 0){
						$deadLine = "off";
					}else {
						$deadLine = "on";
					}

					$leftDate = intval((strtotime($nDate)-strtotime($valDate)) / 86400); // 나머지 날짜값이 나옵니다.
					if($leftDate == 0){
						$leftDate = '마감 D-day';
					}else {
						if($valDate != ''){
							$leftDate = '마감 D'.$leftDate;
						}else {
							$leftDate = '상시 모집';
							$deadLine = "on";
						}
					}

					$postingDate = explode(',', $list[$i]['wr_1']);
					$postYn = "N";
					$bo_newYN = "N";
					if($list[$i]['wr_1'] == '0') $postYn = "N";
					else if($list[$i]['wr_1'] == '1') $postYn = "Y";
					else {
						$postingDate = explode(',', $list[$i]['wr_1']);
						if(count($postingDate) == 2){
							$postingStDt = Trim($postingDate[0]);
							$postingEnDt = Trim($postingDate[1]);
							$bo_newYN = intval(strtotime($postingStDt.' +'.$bo_new.' hours')-strtotime($nDate)) > 0 ? 'Y' : 'N';
							if(intval(strtotime($nDate)-strtotime($postingStDt)) >= 0 && intval(strtotime($postingEnDt)-strtotime($nDate)) >= 0 ) {
								$postYn = "Y";
							}
						} else {
							$postingStDt = Trim($postingDate[0]);
							$bo_newYN = intval(strtotime($postingStDt.' +'.$bo_new.' hours')-strtotime($nDate)) > 0 ? 'Y' : 'N';
							if(intval(strtotime($nDate)-strtotime($postingStDt)) >= 0) {
								$postYn = "Y";
							}
						}
					}

					if($postYn == "Y"){
						?>
						<li class="<?=$deadLine;?>">
							<a href="<?=$list[$i]['href'] ?>"">
								<? if($list[$i]['wr_file'] == 1){
									$wr_id = $list[$i]['wr_id'];
									$sql2 = " select bf_file from lt_board_file where bo_table='experience' and wr_id= {$list[$i]['wr_id']} ";
									$row2 = sql_fetch($sql2);
									$sum_img_url = G5_DATA_URL.'/file/experience/'.$row2['bf_file'];
									?>
									<div class="photo">
										<img src="<?=$sum_img_url?>" alt="">
									</div>
								<? }?>
								<div class="cont">
									<span class="category round"><?=$leftDate;?></span>
									<p class="title bold"><?=$list[$i]['wr_subject'];

									if($bo_newYN == 'Y'){
										?>
										<span class="new">N</span>
										<? }?></p>
										<p><?=$list[$i]['wr_3']?></p>
										<span class="date"><?=$list[$i]['wr_datetime']?></span>
									</div>
								</a>
							</li>
							<?
						}
					}
					?>
				</ul>
				<? if($i == 0) echo "<p class=\"sct_noitem\">등록된 글이 없습니다.</p>\n"; ?>
			</div>
		</div>

		<!-- 컨텐츠 종료 -->
	</div>


	<script>

		function bo_option_select(optionval){

			var wr_6 = $('#wr_6').val();
			var new_wr_6 = '';
			var wr_6Split = wr_6.split(',');
			if(wr_6 != ''){
				for ( var i in wr_6Split ) {
					if(wr_6Split[i] != optionval){
						if(new_wr_6 == '') {
							new_wr_6 = wr_6Split[i];
						}else {
							new_wr_6 += ','+wr_6Split[i];
						}
					}
				}
			}
			if(new_wr_6 != ''){

				new_wr_6 += ','+optionval;
			}else {
				new_wr_6 = optionval;
			}
			$('#wr_6').val(new_wr_6);

			$(".modal").css("display","none");

			$('#fboardlist').submit();
		};


		function bo_selected_option_delete(optionval){
			var wr_6 = $('#wr_6').val();
			var new_wr_6 = '';
			if(wr_6 != ''){
				var wr_6Split = wr_6.split(',');

				for ( var i in wr_6Split ) {
					if(wr_6Split[i] != optionval){
						if(new_wr_6 == '') {
							new_wr_6 = wr_6Split[i];
						}else {
							new_wr_6 += ','+wr_6Split[i];
						}
					}
				}
			}
			$('#wr_6').val(new_wr_6);
			$('#fboardlist').submit();
		};

		$(document).ready(function(){
			$(".bo_option").click(function() {
				var target_id = $(this).attr("targetID");

				var optionName = $(this).text();
				$('#optionModalTitle').html(optionName);

				var optionList = "";
				var $option = $('#'+target_id+' option');

				$option.each(function() {
					bo_value = $(this).val();


					if(bo_value != ""){
						optionList += "<li>";
						optionList += "<a onclick='bo_option_select(\""+bo_value+"\");' >";
						optionList += "<span class=\"bold\">"+bo_value+"</span>";
						optionList += "</a></li>";

					}
				});

				$('#optionModalList').html(optionList);

				$("#optionModal").css("display","block");
			});

			$('li[name="onOffBar"]').click(function(){
				$.changeList($(this).attr('data'));
			});



			$.changeList = function(type){
				if(type == 'on'){
					$('.type2 .on').css('display','block');
					$('.type2 .off').css('display','none');
					$('li[name="onOffBar"][data="off"]').removeClass('on');
					$('li[name="onOffBar"][data="on"]').removeClass('on').addClass('on');
				} else {
					$('.type2 .off').css('display','block');
					$('.type2 .on').css('display','none');
					$('li[name="onOffBar"][data="on"]').removeClass('on');
					$('li[name="onOffBar"][data="off"]').removeClass('on').addClass('on');
				}

			};

			$.bo_selected_option_create = function(){
				$('#bo_selected_option').html('');
				var wr_6 = $('#wr_6').val();
				var html = '';

				if(wr_6 != ''){
					var wr_6Split = wr_6.split(',');
					for ( var i in wr_6Split ) {
						html += '<li><span>'+wr_6Split[i]+'</span><a onclick="bo_selected_option_delete(\''+wr_6Split[i]+'\');" >닫기</a></li>';
					}
					$('#bo_selected_option').html(html);
				}
			};

			$(".close").click(function() {
				$(".modal").css("display","none");
			});;
			$.changeList('<?=$onoff?>');

			$.bo_selected_option_create();
		});


	</script>
