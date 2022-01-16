<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_SKIN_URL.'/style.css">', 0);
?>
<div class="grid">
	<h3 class="blind">제품문의</h3>
	<div class="gray_box info_top">
		<p class="ico_import red point_red">제품에 관한 문의가 아닌 배송, 결제, 교환/반품에 대한 문의는 고객센터 1:1 상담을 이용해 주세요.</p>
		<a href="<?php echo $itemqa_list; ?>" class="btn small border"><span>바로가기</span></a>
	</div>
</div>
<div class="grid tab_cont_wrap">
	<div class="tab none">
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
			<div class="tbl_list">
				<table>
					<colgroup>
						<col style="width:10%;">
						<col style="width:10%;">
						<col style="width:50%;">
						<col style="width:15%;">
						<col style="width:15%;">
					</colgroup>
					<thead>
						<tr>
							<th>번호</th>
							<th>상태</th>
							<th class="alignL">제목</th>
							<th>작성자</th>
							<th>작성일</th>
						</tr>
					</thead>
					<tbody>
					
                    <?php
                    for ($i=0; $i<count($list); $i++) {
                    ?>
					<tr <?php echo "onclick=\"location.href='".$list[$i]['view_href']."';\"" ?>';">
						<td><?php echo $list[$i]['num'] ?></td>
						<?php echo ($list[$i]['qa_status'] ? '<td class="point state">답변완료</td>' : '<td class="state">답변대기</td>'); ?>
						
						<td class="alignL"><a href="javascript:void(0);" class="ellipsis qna_btn1"> [<?php echo $list[$i]['category']; ?>] <?php echo $list[$i]['subject']; ?></a></td>
						
						<td><?php echo $list[$i]['name']; ?></td>
						<td class="date"><?php echo substr($list[$i]['qa_datetime'],0,16); ?></td>
					</tr>
					<?php if($list[$i]['qa_status'] && ($member['mb_id'] == $list[$i]['mb_id'] || $is_admin || $list[$i]['category'] == "기타")) { 
					    $sql = " select *
                                from {$g5['qa_content_table']}
                                where qa_type = '1'
                                  and qa_parent = '{$list[$i]['qa_id']}' ";
            			$answer = sql_fetch($sql);
					
					?>
					<!--  답변 -->
					<tr class="qna_reply">
						<td colspan="5">
							<?php echo get_view_thumbnail(conv_content($answer['qa_content'], $answer['qa_html']), $qaconfig['qa_image_width']); ?>
						</td>
					</tr>
                    <?php
					}
                    }
                    ?>
            
                    <?php if ($i == 0) { echo '<tr><td colspan="5" class="empty_table">게시물이 없습니다.</td></tr>'; } ?>
					</tbody>
				</table>
			</div>
			<div class="paging">
			<?php
            echo itemqa_page($config['cf_write_pages'], $page, $total_page, "./itemqa.php?it_id=$it_id&amp;page=", "");
            if ($itemqa_form) { 
            ?><a href="<?php echo $itemqa_form ?>" class="btn big green"><span>문의하기</span></a><?php 
            } ?>
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

    $("table td .qna_btn1").on("click", function(){
		
		if ($(this).hasClass("on"))
		{
			$(this).removeClass("on");
			$(this).parents("tr").next(".qna_reply").hide();
		} else{
			$(this).addClass("on");
			$(this).parents("tr").next(".qna_reply").show();
		}
	});
});
</script>
<!-- } 상품문의 목록 끝 -->