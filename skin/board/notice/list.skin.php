<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

?>
<!-- container -->
<div id="container">
	<div class="content magazine">
				
    <!-- 컨텐츠 시작 -->
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
		
	</form>
	
	
	    <div class="grid wrap">
        <h2 class="blind">공지사항 목록</h2>
        <div class="list">
            <ul class="type1 none">
            	<?php 
                for ($i=0; $i<count($list); $i++) {
                    
                 ?>
				<li>
					<a href="<?php echo $list[$i]['href'] ?>">
							<p class="title bold"><strong class="title"><?php echo $list[$i]['wr_subject']?><span class="new">N</span></strong></p>
							<span class="text ellipsis"><?php echo $list[$i]['wr_3']?></span>
							<span class="date"><?php echo $list[$i]['wr_datetime']?></span>
					</a>
				</li>
				<?php } ?>
                
            </ul>
            <div class="btn_group"><button type="button" class="btn big border" onclick="$.list_more()"><span>더보기</span></button></div>
        </div>

    </div>
    <!-- 컨텐츠 종료 -->
	
	
	

    
	</div>
</div>
<!-- //container -->

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
