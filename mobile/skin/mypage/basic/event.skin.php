<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once('../common.php');
$sql_list = "select * from lt_write_event where wr_id IN (select distinct wr_parent from lt_write_event where mb_id = '{$member['mb_id']}' and wr_is_comment = 1)";
$sql_board = "select * from lt_board where bo_table='{$type}'";
$sql_count = "select count(*) as cnt from lt_write_event where wr_id IN (select distinct wr_parent from lt_write_event where mb_id = '{$member['mb_id']}' and wr_is_comment = 1)";
$board = sql_fetch($sql_board);
$count = sql_fetch($sql_count);
$result = sql_query($sql_list);

?>

<p class="txt_total">총<strong><?php echo $count['cnt'];?></strong>건</p>

					<!-- 컨텐츠 게시판 : 웹진형 가로타입 -->
                    <div class="list webzine-list">
                        <ul class="type2">
                            <?php 
                            
                            while ($list=sql_fetch_array($result)) {
                                $nDate = date("Y-m-d",time()); // 오늘 날짜를 출력하겠지요?
                                $bo_newYN = "N";
                                $bo_new = $board['bo_new'];
                                $bo_newYN = intval(strtotime($list['wr_datetime'].' +'.$bo_new.' hours')-strtotime($nDate)) > 0 ? 'Y' : 'N';
            
                             ?>
                			<li>
                                <a href="<?php echo G5_BBS_URL.'/board.php?bo_table=event&wr_id='.$list['wr_id']; ?>">
                                    <div class="cont">
                                        <?php if($list['wr_file'] == 1){
                    					    $sql2 = " select bf_file from lt_board_file where bo_table='event' and wr_id= {$list['wr_id']} ";
                    					    $row2 = sql_fetch($sql2);
                    					    $sum_img_url = G5_DATA_URL.'/file/event/'.$row2['bf_file'];
                    					?>
                        					<div class="photo">
                        						<img src="<?php echo $sum_img_url;?>" alt="" />
                        					</div>
                    					<?php }?>  
                    					<div class="star-point">
                                            <span class="num"><?php if($list['wr_8'] != ''){echo $list['wr_8']/2; } else {echo '0';}?></span>
                                            <div class="star">
                                                <!-- width = 평점 2배 -->
                                                <div class="star-bar"><span class="bar" style="width:<?php if($list['wr_8'] != ''){echo $list['wr_8']*10; } else {echo '0';}?>%;"></span></div>
                                            </div>
                                        </div>                          
                                        <p class="title bold"><?php echo $list['wr_subject']?>
                                        <?php if($bo_newYN == 'Y'){
                                        ?>
                                        <span class="new">N</span>
                                        <?php }?></p>
                                        <p><?php echo $list['wr_3']?></p>
                                        <span class="date"><?php echo $list[$i]['wr_datetime']?></span>
                                    </div>
                                </a>
                                <div class="user-area">
                                    <div class="user-info">
                                    	<?php 
                					    $wr_id = $list['wr_id'];
                					    
                					    $mb_dir = substr($list['mb_id'],0,2);
                					    $icon_file = G5_DATA_PATH.'/member/'.$mb_dir.'/'.$list['mb_id'].'.gif';
                					    $icon_url = "";
                					    if (file_exists($icon_file)) {
                					        $icon_url = G5_DATA_URL.'/member/'.$mb_dir.'/'.$list['mb_id'].'.gif';
                					    }
                    					    
                    					?>
                                        <span class="user-photo"><img src="<?php echo $icon_url;?>" alt=""></span>
                                        <span class="user-name"><?php echo $list['wr_name']?></span>
                                    </div>
                                    <div class="user-like">
                                        <!-- 찜 눌르면 class="on" 추가 -->
                                        <?php 
            							$sql = " select bg_flag from {$g5['board_good_table']}
                                                where bo_table = '{$bo_table}'
                                                and wr_id = '{$list['wr_id']}'
                                                and mb_id = '{$member['mb_id']}'
                                                and bg_flag in ('good', 'nogood') ";
            							$pickYN = sql_fetch($sql);
            							?>
                                        <button type="button" class="pick ico <?php if ($pickYN['bg_flag']) echo 'on';?>" ><span
                                                class="blind">찜</span><?php echo $list['wr_good']?></button>
                                        <button type="button" class="review ico"><span
                                                class="blind">댓글</span><?php echo $list['wr_comment']?></button>
                                    </div>
                                </div>
                            </li>
                            <?php } ?>
                        </ul>
                        <? if(!$list=sql_fetch_array($result)) echo '<p class="sit_empty tcenter bd_top">등록된 글이 없습니다.</p>';	?>
					</div>
					