<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once('../common.php');
?>
<!-- container -->
<div id="container">

	<div class="content magazine">
    <!-- 컨텐츠 시작 -->
<?php
    $bannersql = " select * from lt_board where bo_use = 1 and bo_table='".$board['bo_table']."'";
    $result = sql_query($bannersql);
    
    while ($row=sql_fetch_array($result)) {
        
        $main_view_data = json_decode(str_replace('\\','',$row['bo_banner']), true);
?>
    <div class="visual_area">
    	<div class="swiper-container">
    		<div class="swiper-wrapper">
    			<?php for($i=0; $i<$main_view_data['bannerCount']; $i++) {
				    $img_data = $main_view_data['imgFile'][$i];
				    $link_url = $img_data['linkURL'];
				    $muse = $img_data['muse'];
				    if($muse == 1){
				        $img_file = G5_DATA_PATH.'/banner/review/'.$img_data['mimgFile'];
				    }else {
				        $img_file = G5_DATA_PATH.'/banner/review/'.$img_data['imgFile'];
				    }
				    
				    if ($img_data['imgFile'] && file_exists($img_file)) {
				        if($muse == 1){
				            $img_url = G5_DATA_URL.'/banner/review/'.$img_data['mimgFile'];
				        } else {
				            $img_url = G5_DATA_URL.'/banner/review/'.$img_data['imgFile'];
				        }
				        
			    ?>
			    		<div class="swiper-slide"><a href="<?php echo $link_url?>"><img src="<?php echo $img_url?>" alt="" /></a></div>
				<?php } else { ?>
				        <div class="swiper-slide"><a href="<?php echo $link_url?>"><img src="<?php echo G5_MOBILE_URL; ?>/img/theme_img.jpg" alt="" /></a></div>
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
   	<?php }?>
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
   	<div class="grid">
   		<div class="btn_group write">
            <a class="btn green btn-write" href="<?php echo G5_BBS_URL?>/write.php?bo_table=review">작성하기</a>
        </div>
    	<div class="title_bar">
<!-- 						<h3 class="g_title_01"><?php echo $g5['board_title']?></h3>
<a href="#javascript:" class="btn text"><span>전체보기</span></a> -->
		</div>
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
        					    $sql2 = " select bf_file from lt_board_file where bo_table='review' and wr_id= {$list[$i]['wr_id']} ";
        					    $row2 = sql_fetch($sql2);
        					    $sum_img_url = G5_DATA_URL.'/file/review/'.$row2['bf_file'];
        					?>
        					<div class="photo">
        						<img src="<?php echo $sum_img_url;?>" alt="" />
        					</div>
        					<?php } else { ?>
    						<div class="photo">
    							<span>
    								<?php echo $list[$i]['wr_subject']?>
    							</span>
    						</div>
        					<?php } ?>  
                        
        					<div class="star-point">
                                <span class="num"><?php if($list[$i]['wr_8'] != ''){echo $list[$i]['wr_8']/2; } else {echo '0';}?></span>
                                <div class="star">
                                	<div class="star-bar"><span class="bar" style="width:<?php if($list[$i]['wr_8'] != ''){echo $list[$i]['wr_8']*10; } else {echo '0';}?>%;"></span></div>
                                </div>
                            </div>
                    		<span class="category round">체험단리뷰</span>
    
                            <p class="title bold"><?php echo $list[$i]['wr_subject']?>
                            <?php if($bo_newYN == 'Y'){
                            ?>
                            <span class="new">N</span>
                            <?php }?></p>
                            <p><?php echo $list[$i]['wr_3']?></p>
                        	<span class="date"><?php echo $list[$i]['wr_datetime']?></span>
                        </div>
					</a>                        
                    <div class="user-area">
                    	<?php 
					    $wr_id = $list[$i]['wr_id'];
					    
					    $mb_dir = substr($list[$i]['mb_id'],0,2);
					    $icon_file = G5_DATA_PATH.'/member/'.$mb_dir.'/'.$list[$i]['mb_id'].'.gif';
					    $icon_url = "";
					    if (file_exists($icon_file)) {
					        $icon_url = G5_DATA_URL.'/member/'.$mb_dir.'/'.$list[$i]['mb_id'].'.gif';
					    }
    					    
    					?>
    					<div class="user-info">
                            <span class="user-phot"><img src="<?php echo $icon_url;?>" alt=""></span>
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
                                <button type="button" class="pick ico <?php if ($pickYN['bg_flag']) echo 'on';?>">
                                	<span class="blind">찜</span><?php echo $list[$i]['wr_good']?></button>
                                <button type="button" class="review ico">
                                	<span class="blind">댓글</span><?php echo $list[$i]['wr_comment']?></button>
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
    });

	$.bo_selected_option_create();
});


</script> 