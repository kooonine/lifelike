<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_SKIN_URL.'/style.css">', 0);
?>
<div class="grid">
    <h3 class="blind">제품문의</h3>
    <div class="title_bar none ">
        <h2 class="g_title_02">제품에 관한 문의가 아닌 배송, 결제, 교환/반품에 대한 문의는 고객센터 1:1 상담을 이용해 주세요.
        </h2>
        <a href="<?php echo $itemqa_list; ?>" class="more ico text point"><span>바로가기</span></a>
    </div>
    <div class="btn_group mt15">
    <?php if ($itemqa_form) {?><a href="<?php echo $itemqa_form ?>"><button type="button" class="btn big green"><span>문의하기</span></button></a><?php } ?>
    </div>
</div>

<div class="grid tab_cont_wrap">
    <div class="tab">
        <ul class="type2 alignL onoff tab_btn">
			<?php if(!isset($qa_status))$qa_status = ""; ?>
			<li class="<?php echo ($qa_status == "")?"on":"" ?>"><a href="./itemqa.php?it_id=<?php echo $it_id?>" class="qa_page"><span>전체</span></a></li>
			<li class="<?php echo ($qa_status == "1")?"on":"" ?>"><a href="./itemqa.php?it_id=<?php echo $it_id?>&qa_status=1" class="qa_page"><span>답변 완료</span></a></li>
			<li class="<?php echo ($qa_status == "0")?"on":"" ?>"><a href="./itemqa.php?it_id=<?php echo $it_id?>&qa_status=0" class="qa_page"><span>답변 대기</span></a></li>
		</ul>
	</div>
	<div class="tab_cont">
		<!-- tab1 -->
		<div class="tab_inner">
            <div class="toggle">
            <?php
                for ($i=0; $i<count($list); $i++) {
                ?>
                <div class="toggle_group">
                    <div class="title">
                    <?php if($list[$i]['qa_status'] && ($member['mb_id'] == $list[$i]['mb_id'] || $is_admin || $list[$i]['category'] == "기타")) { ?>
                    	<a href="javascript:" class="toggle_anchor">
                    <?php } else if($member['mb_id'] == $list[$i]['mb_id']){ ?>
                    	<a href="<?php echo $list[$i]['view_href']?>">
                    <?php } else { ?>
                    	<a>
                    <?php } ?>
                        <!-- 답변완료면 a 에 class="toggle_anchor" -->
                        
                            <h3 class="tit ellipsis">
                            	[<?php echo $list[$i]['category']; ?>] <?php echo $list[$i]['subject']; ?>
                             </h3>
                        </a>
                        <div class="foot">
                            <span class="date"><?php echo substr($list[$i]['qa_datetime'],0,16); ?></span>
                            <span class="name"><?php echo $list[$i]['name']; ?></span>
                            
							<?php echo ($list[$i]['qa_status'] ? ' <span class="answer yes floatR">답변 완료</span>' : '<span class="answer no floatR">답변 대기</span>'); ?>
                        </div>
                    </div>
                    <?php if($list[$i]['qa_status'] && ($member['mb_id'] == $list[$i]['mb_id'] || $is_admin || $list[$i]['category'] == "기타")) { 
					    $sql = " select *
                                from {$g5['qa_content_table']}
                                where qa_type = '1'
                                  and qa_parent = '{$list[$i]['qa_id']}' ";
            			$answer = sql_fetch($sql);
					
					?>
					<!--  답변 -->
					<div class="cont">
							<?php echo get_view_thumbnail(conv_content($answer['qa_content'], $answer['qa_html']), $qaconfig['qa_image_width']); ?>
					</div>
                    <?php } ?>
                </div>
                <?php
                }
                ?>
                <?php if ($i == 0) { echo '<div class="toggle_group"><div class="title">게시물이 없습니다.</div></div>'; } ?> 
                
    			<?php
    			echo itemqa_page_mobile($config['cf_write_pages'], $page, $total_page, "./itemqa.php?it_id=$it_id&amp;page=", "");
                ?>
			</div>
		</div>
	</div>
</div>

<script>
$(function(){
    $(".qa_page").click(function(){
        $("#itemqa").load($(this).attr("href"));
        return false;
    });
});
</script>
<!-- } 상품문의 목록 끝 -->