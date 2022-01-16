<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

?>
<input type="hidden" name="wr_6" id="wr_6" value="<?php echo $view['wr_6'] ?>">

<div id="container" class="no_title">
			<div id="lnb" class="header_bar">
				<h1 class="title"><span>REVIEW</span></h1>
			</div>
			<div class="content sub community type4">
				<!-- 컨텐츠 시작 review -->
					<div class="grid head new-grid">

					<div class="title_bar none">
					
							
							
	                    <div class="user-info">
                            <span class="user-photo"><?php echo get_member_profile_img($view['mb_id']); ?></span>
                            <span class="user-name"><?php echo $view['mb_id'] ?></span>
                        </div>
								
							
							
            			<div class="btn_comm big">
            				<!-- 찜 클릭시 class="on" -->
        					<?php 
        					$sql = " select bg_flag from {$g5['board_good_table']}
                                    where bo_table = '{$bo_table}'
                                    and wr_id = '{$wr_id}'
                                    and mb_id = '{$member['mb_id']}'
                                    and bg_flag in ('good', 'nogood') ";
        					$pickYN = sql_fetch($sql);
        					?>
        					<button type="button" class="pick ico <?php if ($pickYN['bg_flag']) echo 'on';?>" id="btn_pick" href="<?php echo $good_href.'&amp;'.$qstr ?>"><span class="blind">찜</span><?php echo $view['wr_good']?></button>
            				<button type="button" class="shared"><span class="blind">공유</span></button>
            			</div>
            			<h2 class="g_title_01"><?php echo cut_str(get_text($view['wr_subject']), 70); ?></h2>                        
                        <p class="g_title_02"><?php echo $view['wr_datetime']?></p>
						<p class="g_title_02 desc"><?php echo $list[$i]['wr_3']?></p>
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
					

                    <div class="star-point">
                        <span class="num"><?php if($view['wr_8'] != ''){echo $view['wr_8']/2; } else {echo '0';}?></span>
                        <div class="star">
                            <!-- width = 평점 2배 -->
                            <div class="star-bar"><span class="bar" style="width:<?php if($view['wr_8'] != ''){echo $view['wr_8']*10; } else {echo '0';}?>%;"></span></div>
                        </div>
                    </div>

						<div class="detail_wrap">
							<?php
                            // 파일 출력
                            $v_img_count = count($view['file']);
                            if($v_img_count) {
                                echo "<div class=\"photo\">\n";
                    
                                for ($i=0; $i<=count($view['file']); $i++) {
                                    if ($view['file'][$i]['view']) {
                                        //echo $view['file'][$i]['view'];
                                        echo get_view_thumbnail($view['file'][$i]['view']);
                                    }
                                }
                    
                                echo "</div>\n";
                            }
                             ?>
                    		
                    		<?php  if($view['wr_10'] == '1') {echo $view['wr_content_mobile'];} else {echo $view['wr_content'];} ?>
						</div>
                		<?php if($view['wr_2'] != ''){?>
                            <div class="detail_tag">
                                <?php echo $view['wr_2']?>
                            </div>
                        <?php }?>
						<div class="btn_group">
                        	<a href="<?php echo $list_href ?>"><button type="button" class="btn big border"><span>목록</span></button></a>
                        	<?php if($view['mb_id'] == $member['mb_id']){?>
    						<button type="button" class="btn big border" onclick="del('<?php echo $delete_href ?>'); location.href='<?php echo $delete_href ?>'";><span>삭제</span></button>
    						<button type="button" class="btn big green" onclick="location.href='<?php echo $update_href ?>'";><span>수정</span></button>
							<?php }?>
						</div>

					</div>
					
				<?php include_once(G5_BBS_PATH.'/view_comment.php'); ?>   
				<!-- 컨텐츠 종료 -->
			</div>
		</div>
			