<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

?>
<!-- container -->
<div id="container">
	<div class="content event">
		<!-- 컨텐츠 시작 -->
		<div class="grid ">
			<div class="tab">
				<ul class="type1 onoff">
					<li class="on"><a href="#"><span>진행중</span></a></li>
					<li><a href="#"><span>종료</span></a></li>
				</ul>
			</div>

			<div class="list event_container">
				<ul class="type2 divide_two">
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
						<li class="<? echo $deadLine;?>">
							<a href="<? echo $list[$i]['href'] ?>"">
								<? if($list[$i]['wr_file'] == 1){
									$wr_id = $list[$i]['wr_id'];
									$sql2 = " select bf_file from lt_board_file where bo_table='event' and wr_id= {$wr_id} ";
									$row2 = sql_fetch($sql2);
									$sum_img_url = G5_DATA_URL.'/file/event/'.$row2['bf_file'];
									?>
									<div class="photo">
										<img src="<? echo $sum_img_url?>" alt="">
									</div>
								<? }?>
						<!-- div class="cont">
							<span class="category round"><? echo $leftDate;?></span>
							<p class="title bold"><? echo $list[$i]['wr_subject'];

							if($bo_newYN == 'Y'){
							?>
							<span class="new">N</span></p>
							<? }?>
							<p><? echo $list[$i]['wr_3']?></p>
							<span class="date"><? echo $list[$i]['wr_datetime']?></span>
						</div -->
					</a>
				</li>
				<?
			}
		}
		?>
	</ul>

</div>
</div>

<!-- 컨텐츠 종료 -->
</div>
</div>
<script>
	$(document).ready(function(){

	});


</script>
