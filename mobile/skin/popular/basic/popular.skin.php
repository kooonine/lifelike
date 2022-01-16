<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

?>

    <?php
    if( isset($list) && is_array($list) ){
        for ($i=0; $i<count($list); $i++) {
    ?>
    	<li>
        <a href="#" onclick="$.search('search','<?php echo urlencode($list[$i]['pp_word']) ?>')">
        <span class="ellipsis"><?php echo get_text($list[$i]['pp_word']); ?></span>
        </a>
    	</li>
    <?php
        }   //end for
    }   //end if
    ?>

					