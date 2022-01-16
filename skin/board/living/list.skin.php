<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once('../common.php');
?>

<!-- container -->
<div id="container">
	<div class="content bbs type3">
    <!-- 컨텐츠 시작 -->
    <?php
    if($board['bo_banner'] != "") {
    $main_view_data = json_decode(str_replace('\\','',$board['bo_banner']), true);
    ?>
    <div class="visual_area">
    	<div class="swiper-container">
    		<div class="swiper-wrapper">
    			<?php for($i=0; $i<$main_view_data['bannerCount']; $i++) {
				    $img_data = $main_view_data['imgFile'][$i];
				    $link_url = $img_data['linkURL'];
				    $img_file = G5_DATA_PATH.'/banner/living/'.$img_data['imgFile'];
				    if ($img_data['imgFile'] && file_exists($img_file)) {
				        $img_url = G5_DATA_URL.'/banner/living/'.$img_data['imgFile'];
			    ?>
			    		<div class="swiper-slide"><a href="<?php echo $link_url?>"><img src="<?php echo $img_url?>" alt="" /></a></div>
				<?php } else { ?>
				        <div class="swiper-slide"><a href="<?php echo $link_url?>"><img src="<?php echo G5_URL; ?>/img/theme_img.jpg" alt="" /></a></div>
				<?php }	
				}?>
    		</div>
    		<div class="swiper-pagination"></div>
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>
    	</div>
		<script>
			var swiperMain_visual = new Swiper('.visual_area .swiper-container', {
			 autoplay: {
				delay: 4000,
			  },
			  loop: true,
			  pagination: {
				el: '.swiper-pagination',
				clickable: true,
			  },
			  navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			  },
			});
	  </script>
    </div>
    <?php } ?>
    
   	<div class="grid">

		<div class="title_bar">
			<div class="none_sel">
                	<?php 
                	$wr_6s = explode(",", $wr_6);
                	for ($i=1; $i<10; $i++) {
                	    if($board['bo_'.$i.'_subj'] != ''){
                            if($board['bo_'.$i] != '') {
                                $bo_option_list = explode(',', $board['bo_'.$i]);
                                $option_count = count($bo_option_list);
                                $bo_select = '<span class="select"><select id="bo_option_'.$i.'" name="sel_bo_option[]" class="bo_option" onchange="bo_option_select();" >'.PHP_EOL;
                                $bo_select .= '<option value="">선택</option>'.PHP_EOL;
                                for($j=0; $j<$option_count; $j++) {
                                    $selected = "";
                                    if(count($wr_6s) > 0 && $wr_6s[$i-1] && $bo_option_list[$j] == $wr_6s[$i-1]) $selected = "selected";
                                    
                                    $bo_select .= '<option value="'.$bo_option_list[$j].'" '.$selected.'>'.$bo_option_list[$j].'</option>'.PHP_EOL;
                                }
                                $bo_select .= '</select></span>'.PHP_EOL;
                                
                                echo $bo_select.PHP_EOL;
                            }
                	    }
                	}?>
            </div>
        </div>
        <form name="fboardlist" id="fboardlist" action="./board.php" method="get">
            <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
            <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
            <input type="hidden" name="stx" value="<?php echo $stx ?>">
            <input type="hidden" name="spt" value="<?php echo $spt ?>">
            <input type="hidden" name="sst" value="<?php echo $sst ?>">
            <input type="hidden" name="sod" value="<?php echo $sod ?>">
            <input type="hidden" name="page" value="<?php echo $page ?>">
            <input type="hidden" name="sw" value="">
            <input type="hidden" name="wr_6" id="wr_6" value="<?php echo $wr_6 ?>">
    	</form>
    	
    	<div class="card-list">
    		<ul>
    			<?php 
                for ($i=0; $i<count($list); $i++) {
                    $nDate = date("Y-m-d",time()); // 오늘 날짜를 출력하겠지요?
                    $bo_newYN = "N";
                    $bo_new = $board['bo_new'];
                    $bo_newYN = intval(strtotime($list[$i]['wr_datetime'].' +'.$bo_new.' hours')-strtotime($nDate)) > 0 ? 'Y' : 'N';

                 ?>
    			<li>
                    <a href="<?php echo $list[$i]['href'] ?>">
                        <div class="cont">
                            <?php if($list[$i]['wr_file'] == 1){
        					    $sql2 = " select bf_file from lt_board_file where bo_table='{$board["bo_table"]}' and wr_id= {$wr_id} ";
        					    $row2 = sql_fetch($sql2);
        					    $sum_img_url = G5_DATA_URL.'/file/'.$board["bo_table"].'/'.$row2['bf_file'];
        					?>
            					<div class="photo">
            						<img src="<?php echo $sum_img_url;?>" alt="" />
            					</div>
        					<?php }?>                            
                            <p class="title bold"><?php echo $list[$i]['wr_subject']?>
                            <?php if($bo_newYN == 'Y'){
                            ?>
                            <span class="new">N</span>
                            <?php }?></p>
                            <p><?php echo $list[$i]['wr_3']?></p>
                            <span class="date"><?php echo substr($list[$i]['wr_datetime'],0,10)?></span>
                        </div>
                    </a>
                    <div class="user-area">
                        <div class="user-info">
                        	<?php 
    					    $wr_id = $list[$i]['wr_id'];
    					    
    					    $mb_dir = substr($list[$i]['mb_id'],0,2);
    					    $icon_file = G5_DATA_PATH.'/member/'.$mb_dir.'/'.$list[$i]['mb_id'].'.gif';
    					    $icon_url = "";
    					    if (file_exists($icon_file)) {
    					        $icon_url = G5_DATA_URL.'/member/'.$mb_dir.'/'.$list[$i]['mb_id'].'.gif';
    					    }
        					    
        					?>
                            <span class="user-photo"><img src="<?php echo $icon_url;?>" alt=""></span>
                            <span class="user-name"><?php echo $list[$i]['wr_name']?></span>
                        </div>
                        <div class="user-like">
                            <?php 
							$sql = " select bg_flag from {$g5['board_good_table']}
                                    where bo_table = '{$bo_table}'
                                    and wr_id = '{$list[$i]['wr_id']}'
                                    and mb_id = '{$member['mb_id']}'
                                    and bg_flag in ('good', 'nogood') ";
							$pickYN = sql_fetch($sql);
							?>
                            <button type="button" class="pick ico <?php if ($pickYN['bg_flag']) echo 'on';?>"><span
                                    class="blind">찜</span><?php echo $list[$i]['wr_good']?></button>
                            <button type="button" class="review ico"><span
                                    class="blind">댓글</span><?php echo $list[$i]['wr_comment']?></button>
                        </div>
                    </div>
                </li>
                <?php } ?>
                
    			
    			
    			
    		</ul>
    	</div>
    </div>
    <!-- 컨텐츠 종료 -->
</div>
<!-- 게시판 목록 끝 -->

     <script>
     function bo_option_select(){

		var wr_6 = "";
		for (var i = 1; i <= 10; i++) {
			if(i != 1) wr_6 += "," 
			if($("#bo_option_"+i).val() != undefined){
				wr_6 += $("#bo_option_"+i).val(); 			
			}
		}
		$("#wr_6").val(wr_6);
  		
  		$('#fboardlist').submit();
  	};

  	
$(document).ready(function(){
	
	
});


</script>