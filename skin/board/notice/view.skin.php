<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

?>

<!-- container -->
<div id="container" class="no_title">
	<div id="lnb" class="header_bar">
		<h1 class="title"><span>공지사항</span></h1>
		<a href="#" class="btn_back"><span class="blind">뒤로가기</span></a>
	</div>
	<div class="content notice">
	<!-- 컨텐츠 시작 -->
		<div class="grid head">
            <div class="title_bar none">
                <h2 class="g_title_01"><?php echo cut_str(get_text($view['wr_subject']), 70); ?></h2>
                <div class="btn_comm big">
                	<span class="date"><?php echo cut_str(get_text($view['wr_datetime']), 70); ?></span>
					<?php 
					$sql = " select bg_flag from {$g5['board_good_table']}
                            where bo_table = '{$bo_table}'
                            and wr_id = '{$wr_id}'
                            and mb_id = '{$member['mb_id']}'
                            and bg_flag in ('good', 'nogood') ";
					$pickYN = sql_fetch($sql);
					?>
<!--                    <button type="button" class="pick ico <?php if ($pickYN['bg_flag']) echo 'on';?>" id="btn_pick" href="<?php echo $good_href.'&amp;'.$qstr ?>"><span class="blind">찜</span><?php echo $view['wr_good']?></button>
                     <button type="button" class="shared"><span class="blind">공유</span></button> -->
                </div>
            </div>
        </div>
		<div class="grid detail_wrap">
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
		<?php if($view['wr_2'] != ''){?>
            <div class="detail_tag">
                <?php echo $view['wr_2']?>
            </div>
        <?php }?>
        </div>
                        
        <div class="btn_group ">
        	<a href="<?php echo $list_href ?>"><button type="button" class="btn big border"><span>목록</span></button></a>
		</div>
        <!-- 댓글 시작 --> 
        <?php include_once(G5_BBS_PATH.'/view_comment.php'); ?>                
        <!-- //댓글 -->
		
	<!-- 컨텐츠 종료 -->
</div>
