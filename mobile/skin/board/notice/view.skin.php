<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

?>
<script>
var header = '<div id="lnb" class="header_bar">';
header += '<h1 class="title"><span>공지사항</span></h1>';
header += '<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
header += '</div>';
$('#header').html(header);
</script>

<!-- //lnb -->
<div class="content sub community type4">
	<!-- 컨텐츠 시작 -->
		<div class="grid head">
            <div class="title_bar none">
                <h2 class="g_title_01"><?php echo cut_str(get_text($view['wr_subject']), 70); ?></h2>
                <p class="g_title_02"><?php echo cut_str(get_text($view['wr_3']), 70); ?></p>
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
            </div>
            <div class="user_bar">
                <span class="date"><?php echo cut_str(get_text($view['wr_datetime']), 70); ?></span>
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
    		<div class="detail_tag">
                <?php $view['wr_2']?>
            </div>
        </div>
        <!-- 댓글 시작 --> 
        <?php include_once(G5_BBS_PATH.'/view_comment.php'); ?>                
        <!-- //댓글 -->
		
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