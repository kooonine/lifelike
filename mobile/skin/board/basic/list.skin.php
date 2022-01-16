<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$onoff = $_GET['onoff'];
if($onoff == ""){
    $onoff = "on";
}
$sst = $_GET['sst'];
if($sst == ''){
    $sst = "wr_datetime";
}
?>
<div class="content magazine">
    <!-- 컨텐츠 상단 비쥬얼 -->
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
				    $muse = $img_data['muse'];
				    if($muse == 1){
				        $img_file = G5_DATA_PATH.'/banner/'.$board['bo_table'].'/'.$img_data['mimgFile'];
				    }else {
				        $img_file = G5_DATA_PATH.'/banner/'.$board['bo_table'].'/'.$img_data['imgFile'];
				    }
				    
				    if ($img_data['imgFile'] && file_exists($img_file)) {
				        if($muse == 1){
				            $img_url = G5_DATA_URL.'/banner/'.$board['bo_table'].'/'.$img_data['mimgFile'];
				        } else {
				            $img_url = G5_DATA_URL.'/banner/'.$board['bo_table'].'/'.$img_data['imgFile'];
				        }
			    ?>
			    		<div class="swiper-slide"><a href="<?php echo $link_url?>"><img src="<?php echo $img_url?>" alt=""/></a></div>
				<?php } else { ?>
				        <div class="swiper-slide"><a href="<?php echo $link_url?>"><img src="<?php echo G5_MOBILE_URL; ?>/img/theme_img.jpg" alt="" /></a></div>
				<?php }	
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
    <?php } ?>

    <form name="fboardlist" id="fboardlist" action="./board.php" method="get">
        <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
        <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
        <input type="hidden" name="stx" value="<?php echo $stx ?>">
        <input type="hidden" name="spt" value="<?php echo $spt ?>">
        <input type="hidden" name="sst" value="<?php echo $sst ?>">
        <input type="hidden" name="sod" value="<?php echo $sod ?>">
        <input type="hidden" name="page" value="<?php echo $page ?>">
        <input type="hidden" name="pageRows" value="<?php echo $page_rows ?>">
        <input type="hidden" name="sw" value="">
		<input type="hidden" name="wr_6" id="wr_6" value="<?php echo $wr_6 ?>">
	</form>
    <div class="grid">
        
        <?php if($board['bo_use_userform'] == "1") {?>  
        <div class="step-nav">
            <ul>
                <li name="onOffBar" data='on'><a href="#">진행 중</a></li>
                <li name="onOffBar" data='off'> <a href="#">종료</a></li>
            </ul>
        </div>
        <?php } ?>
        <!-- 컨텐츠 게시판 정렬 -->
        <div class="title_bar">
            <div class="none_sel floatR">
                <span class="select">
                    <select name="" title="목록" onChange="location.href='<?php echo G5_BBS_URL?>/board.php?bo_table=<?php echo $board['bo_table'] ?>'+$(this).val();">
                        <option value="&sst=wr_datetime&sod=desc" <?php echo get_selected($sst, "wr_datetime")?>>최신 등록 순</option>
                        <option value="&sst=wr_good&sod=desc"<?php echo get_selected($sst, "wr_good")?>>추천순</option>
                    </select>
                </span>
            </div>
        </div>
    
        <div class="list">
            <ul class="type1 none">
            	<?php 
                for ($i=0; $i<count($list); $i++) {
                ?>
				<li>
					<a href="<?php echo $list[$i]['href'] ?>">
						<strong class="title bold"><?php echo $list[$i]['wr_subject']?><span class="new">N</span></strong>
						<span class="text ellipsis"><?php echo ($board['bo_use_view'] == '1' || $board['bo_use_view_summary'] == '1')?$list[$i]['wr_3']:'' ?></span>
						<span class="date"><?php echo ($board['bo_use_view'] == '1' || $board['bo_use_view_datetime'] == '1')?$list[$i]['wr_datetime']:'' ?></span>
					</a>
				</li>
				<?php } ?>
                
            </ul>
            <?php if($total_page > 1) {?>
            <div class="btn_group"><button type="button" class="btn big border" onclick="$.list_more()"><span>더보기</span></button></div>
            <?php } ?>
        </div>
        <? if($board['bo_use_userform'] != "1" && $write_href){ ?>
        <!-- 컨텐츠 게시판리스트 : 글쓰기 버튼 -->
        <div class="btn_fix"><a href="<?php echo G5_BBS_URL?>/write.php?bo_table=<?php echo $board['bo_table']?>"><button type="button" class="btn_fix_write"><span class="blind">글쓰기</span></button></a></div>
        <?php } ?>
    </div>
    <!-- 컨텐츠 종료 -->
</div>
<script>
$(document).ready(function(){
	
	$.list_more = function(){
		pageRows = parseInt($('input[name="pageRows"]').val());
		page_rows = <?php echo $board['bo_mobile_page_rows']?>;
		pageRows += page_rows;
		
		 $('input[name="pageRows"]').val(pageRows);
		 $('input[name="sst"]').val("");
		$('#fboardlist').submit();
	};
	
});


</script>
