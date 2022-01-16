<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

?>
<script>
var header = '<div id="lnb" class="header_bar">';
header += '<h1 class="title"><span>리빙노하우</span></h1>';
header += '<a href="#" onclick-"history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
header += '</div>';
$('#header').html(header);
</script>

<input type="hidden" name="wr_6" id="wr_6" value="<?php echo $view['wr_6'] ?>">
			<!-- //lnb -->
			<div class="content sub community type3">
				<!-- 컨텐츠 시작 -->
				<div class="grid head new-grid">
					<div class="title_bar none">
						<div class="user-info">
							<span class="user-photo"><?php echo get_member_profile_img($view['mb_id']); ?></span>
							<span class="user-name"><?php echo $view['mb_id'] ?></span>
						</div>
						<div class="btn_comm big">
							<?php 
							$sql = " select bg_flag from {$g5['board_good_table']}
                                    where bo_table = '{$bo_table}'
                                    and wr_id = '{$wr_id}'
                                    and mb_id = '{$member['mb_id']}'
                                    and bg_flag in ('good', 'nogood') ";
							$pickYN = sql_fetch($sql);
							?>
                            <button type="button" class="pick ico <?php if ($pickYN['bg_flag']) echo 'on';?>" id="btn_pick" href="<?php echo $good_href.'&amp;'.$qstr ?>"><span class="blind">찜</span><?php echo $view['wr_good']?></button>
                            <button type="button" class="shared" ><span class="blind">공유</span></button>
                        </div>
                        <h2 class="g_title_01"><?php echo cut_str(get_text($view['wr_subject']), 70); ?></h2>
                        <p class="g_title_02"><?php echo $list[$i]['wr_3']?></p>
                        <p class="g_title_02"><?php echo $view['wr_datetime']?></p>
					</div>
					<div class="type-box">
						<ul>
    						<?php for ($i=1; $i<10; $i++) {
                        	    if($board['bo_'.$i.'_subj'] != ''){
                        	        $detail_category = explode(',', $view['wr_6']);
                            	?>
    							<li>
    								<span class="type-box-label"><?php echo $board['bo_'.$i.'_subj']?></span>
    								<span class="type-box-value"><?php if($detail_category[$i-1] != ''){ echo $detail_category[$i-1];}else{echo '선택안함';}?></span>
    							</li>							
    							<?php     
                        	    }
    						}?>
						</ul>
					</div>
					<div class="detail_wrap">
                        <!-- ?php
                        // // 파일 출력
                        // $v_img_count = count($view['file']);
                        // if($v_img_count) {
                        //     echo "<div class=\"photo\">\n";
                
                        //     for ($i=0; $i<=count($view['file']); $i++) {
                        //         if ($view['file'][$i]['view']) {
                        //             //echo $view['file'][$i]['view'];
                        //             echo get_view_thumbnail($view['file'][$i]['view']);
                        //         }
                        //     }
                
                        //     echo "</div>\n";
                        // }
                         ? -->
                		
                		<?php  if($view['wr_10'] == '1') {echo $view['wr_content_mobile'];} else {echo $view['wr_content'];} ?>
                		<?php if($view['wr_2'] != ''){?>
                            <div class="detail_tag">
                                <?php echo $view['wr_2']?>
                            </div>
                        <?php }?>
                        <?php if($view['mb_id'] == $member['mb_id']){?>
                            <div class="btn_group two">
    							<button type="button" class="btn big border" onclick="del('<?php echo $delete_href ?>'); location.href='<?php echo $delete_href ?>'";><span>삭제</span></button>
    							<button type="button" class="btn big green" onclick="location.href='<?php echo $update_href ?>'";><span>수정</span></button>
    						</div>
						<?php }?>
                    </div>
						
				</div>
				

				
				<?php if($view['wr_5'] != ''){?>
                    <div class="grid">
                        <div class="title_bar">
                            <h3 class="g_title_01">관련제품</h3>
                        </div>
                        <div class="pdt_rolling pdt1">
                            <div class="item_row_list swiper-container">
                                <ul class="swiper-wrapper">
                                	<?php 
                                	
                                	   $itemList = explode(',', $view['wr_5']);
                                	   
                                	   for($i=0; $i<count($itemList); $i++){
                                    	$sql2 = " select * from lt_shop_item where it_id = '{$itemList[$i]}' and it_use = 1";
                                    	$row2 = sql_fetch($sql2);
                                    	$link_url = G5_URL.'/shop/item.php?it_id='.$row2['it_id'];
                                    	?>
                                        <li class="swiper-slide">
                                            <a href="<?php echo $link_url?>">
                                                <div class="photo">
                    							<?php 
                    								$img_data = $row2['it_img1'];
                                				    $img_file = G5_DATA_PATH.'/item/'.$img_data;
                                				    
                                				    if ($img_data && file_exists($img_file)) {
                                				        $img_url = G5_DATA_URL.'/item/'.$img_data;
                                				        
                                			    ?>
                                			    		<img src="<?php echo $img_url?>" alt="" />
                                				<?php } else { ?>
                                						<img src="<?php echo G5_MOBILE_URL; ?>/img/theme_img.jpg"  alt="" />
                                				<?php }	?>
                    							</div>
                    							<div class="cont">
                    								<div class="inner">
                    									<strong class="title bold line2"><?php echo $row2['it_name']?></strong>
                    									<span class="price"><?php echo $row2['it_price']?> 원</span>
                    								</div>
                    							</div>
                                            </a>
                                        </li>
                                    <?php }?>
                                	
                                </ul>
                            </div>
                            <script>
                                var swiperColumn_three = new Swiper('.pdt1 .swiper-container', {
                                    slidesPerView: 'auto',
                                    spaceBetween: 10,
                                    //loop: true,
                                });
                            </script>
                        </div>
                    </div>
                    <?php }?>
				<?php include_once(G5_BBS_PATH.'/view_comment.php'); ?>   

				<!-- 컨텐츠 종료 -->
			</div>
			
			
			<script>
     
$(document).ready(function(){
	
	$(".shared").click(function() {
		var imgUrl= $('.view_image img').attr('src');
		var wr_2 = '<?php echo $view['wr_2']?>'.replace('#','-')
		window.open('<?php echo $share_href;?>'+imgUrl+'&wr_2='+wr_2);
	 });	
	
	$("#btn_pick").click(function() {
		var href = $(this).attr('href');
		$.post(
				href,
		        { js: "on" },
		        function(data) {
		            if(data.error) {
		                alert(data.error);
		                return false;
		            }
					if(data.flag) {
						if(data.flag == 'ON'){
							$("#btn_pick").removeClass('on').addClass('on');
						}else {
							$("#btn_pick").removeClass('on');
						}
					}
		            if(data.count) {
		            	$("#btn_pick").text('');
		            	$("#btn_pick").append('<span class="blind">찜</span> '+data.count);
		            }
		        }, "json"
		    );
	 });
});

</script>