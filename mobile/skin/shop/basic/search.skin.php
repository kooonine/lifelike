<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

?>

		<section class="popup_container layer search_container">
            <div class="inner_layer">
            	<div id="lnb" class="header_bar">
					<h1 class="title"><span>검색</span></h1>
					<a href="#" class="btn_closed" onclick="history.back();"><span class="blind">닫기</span></a>
				</div>
				<div class="content inner">
                	<?php include_once(G5_MSHOP_SKIN_PATH.'/search_box.php');?>
                	<?php include_once(G5_MSHOP_SKIN_PATH.'/popular_box.php');?>
                	<?php include_once(G5_MSHOP_SKIN_PATH.'/search_complete_box.php');?>
            	</div>
            </div>
        </section>
	</div>
</div>
</body>

</html>