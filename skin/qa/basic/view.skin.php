<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/style.css">', 0);
?>
<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>
<!-- container -->
<div id="container">
	<!-- lnb -->
	<div id="lnb" class="header_bar">
		<h1 class="title"><span>1:1 문의</span></h1>
	</div>
	<!-- //lnb -->
	<div class="content mypage sub">
		<!-- 컨텐츠 시작 -->
		<div class="grid">
			<div class="title_bar none right_cont">
				<h2 class="g_title_01"><?php echo $view['subject']; // 글제목 출력 ?></h2>
				<div class="user_bar">
					<span class="date"><?php echo $view['datetime']; ?></span>
					<?php echo ($view['qa_status'] ? '<span class="category round_green">답변 완료</span>' : '<span class="category round_black">미답변</span>'); ?>
				</div>
			</div>
			<div  class="divide_two box stick">
				<div class="box">
					<div class="order_title reverse">
						<span class="item">문의 정보</span>
					</div>
					<div class=" order_list">
						<ul>
							<li>
								<span class="item">분류/유형</span>
								<strong class="result">
									<?php echo $view['category'] ?>
								</strong>
							</li>
							<?php if($view['od_id']) {?>
							<li>
								<span class="item">주문번호</span>
								<strong class="result">
									<?php echo $view['od_id'] ?>
								</strong>
							</li>
							<?php } else if($view['it_id'] ) {?>
							<li>
								<span class="item">상품번호</span>
								<strong class="result">
									<a href="<?php echo G5_SHOP_URL?>/item.php?it_id=<?php echo $view['it_id']?>"><?php echo $view['it_id'] ?></a>
								</strong>
							</li>
							<?php } else {?>
							<li></li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<div class="box">
					<div class="order_title reverse">
						<span class="item">작성자 정보</span>
					</div>
					<div class=" order_list">
						<ul>
							<li>
								<span class="item">작성자</span>
								<strong class="result">
                                     <?php echo $view['mb_id'];?>
								</strong>
							</li>
							<li>
								<span class="item">이메일</span>
								<strong class="result">
									<?php echo $view['email']; ?>
								</strong>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<div class="grid bg_none">
			<div class="detail_wrap border_box">
				<?php echo get_view_thumbnail($view['content'], $qaconfig['qa_image_width']); ?>
				<div class="photo">
				<?php
                // 파일 출력
                if($view['img_count']) {
                    echo "<ul class=\"list\">\n";
                    for ($i=0; $i<$view['img_count']; $i++) {
                        //echo $view['img_file'][$i];
                        echo get_view_thumbnail($view['img_file'][$i], $qaconfig['qa_image_width']);
                    }
                    echo "</ul>\n";
                }
                 ?>
				</div>
			</div>
		</div>
		<?php
        // 질문글에서 답변이 있으면 답변 출력, 답변이 없고 관리자이면 답변등록폼 출력
        if(!$view['qa_type']) {
            if($view['qa_status'] && $answer['qa_id'])
                include_once($qa_skin_path.'/view.answer.skin.php');
            else
                include_once($qa_skin_path.'/view.answerform.skin.php');
        }
        ?>
		<div class="grid foot">
			<div class="btn_group two">
				<?php if ($update_href) { ?><a href="<?php echo $update_href ?>"><button type="button" class="btn big border"><span>수정</span></button></a><?php } ?>
                <?php if ($delete_href) { ?><a href="<?php echo $delete_href ?>" onclick="del(this.href); return false;"><button type="button" class="btn big border"><span>삭제</span></button></a><?php } ?>
                <a href="<?php echo $list_href ?>"><button type="button" class="btn big border"><span>목록</span></button></a>
                
				<?php if($view['qa_status'] && $rewrite_href) { ?>
				<a href="<?php echo $rewrite_href; ?>"><button type="button" class="btn big green"><span>재문의</span></button></a>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php if($view['rel_count'] && false) { ?>
<section id="bo_v_rel">
    <h2>연관질문</h2>

    <div class="tbl_head01 tbl_wrap">
        <table>
        <thead>
        <tr>
            <th scope="col">제목</th>
            <th scope="col">등록일</th>
            <th scope="col">상태</th>
        </tr>
        </thead>
        <tbody>
        <?php
        for($i=0; $i<$view['rel_count']; $i++) {
        ?>
        <tr>
            <td>
                <span class="bo_cate_link"><?php echo get_text($rel_list[$i]['category']); ?></span>

                <a href="<?php echo $rel_list[$i]['view_href']; ?>" class="bo_tit">
                    <?php echo $rel_list[$i]['subject']; ?>
                </a>
            </td>
            <td class="td_date"><?php echo $rel_list[$i]['date']; ?></td>
            <td class="td_stat"><span class="<?php echo ($list[$i]['qa_status'] ? 'txt_done' : 'txt_rdy'); ?>"><?php echo ($rel_list[$i]['qa_status'] ? '<i class="fa fa-check-circle" aria-hidden="true"></i> 답변완료' : '<i class="fa fa-times-circle" aria-hidden="true"></i> 답변대기'); ?></span></td>
        </tr>
        <?php
        }
        ?>
        </tbody>
        </table>
    </div>
</section>
<?php } ?>


<!-- } 게시판 읽기 끝 -->

<script>
$(function() {
    $("a.view_image").click(function() {
        window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
        return false;
    });

    // 이미지 리사이즈
    $("#bo_v_atc").viewimageresize();
});
</script>