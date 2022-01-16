<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>
<div class="grid bg_none">
    <div class="order_title reverse">
        <span class="item"><?php echo get_text($answer['qa_subject']); ?></span>
        <strong class="result">
            <?php echo $answer['qa_datetime']; ?>
        </strong>
    </div>
    <div class="border_box">
        <?php echo get_view_thumbnail(conv_content($answer['qa_content'], $answer['qa_html']), $qaconfig['qa_image_width']); ?>
    </div>
	<div class="btn_group two">
        <?php if($answer_update_href) { ?>
        <a href="<?php echo $answer_update_href; ?>"><button type="button" class="btn big border"><span>답변수정</span></button></a>
        <?php } ?>
        <?php if($answer_delete_href) { ?>
        <a href="<?php echo $answer_delete_href; ?>" onclick="del(this.href); return false;"><button type="button" class="btn big border"><span>답변삭제</span></button></a>
        <?php } ?>
	</div>
</div>
