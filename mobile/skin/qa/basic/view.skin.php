<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.G5_MSHOP_SKIN_URL.'/style.css">', 0);
?>
<script>
var header = '<div id="lnb" class="header_bar">';
header += '<h1 class="title"><span>1:1문의 상세보기</span></h1>';
header += '<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
header += '</div>';
$('#header').html(header);
</script>

<div class="content mypage sub">
                <!-- 컨텐츠 시작 -->
                <div class="grid cont">
                    <div class="title_bar none">
                        <h2 class="g_title_01"><?php echo $view['subject'];?></h2>
                    </div>
                    <div class="user_bar">
                        <span class="date"><?php echo $view['datetime'];?></span>
                        <?php echo ($view['qa_status'] ? '<span class="state on floatR">답변완료</span>' : '<span class="state off floatR">답변대기</span>'); ?>
                        <!-- <span class="state on floatR">답변 완료</span> -->
                    </div>
                </div>

                <div class="grid">
                    <div class="order_title reverse">
                        <span class="item">문의 정보</span>
                    </div>
                    <div class="border_box order_list">
                        <ul>
                            <li>
                                <span class="item">문의 유형</span>
                                <strong class="result">
                                    <?php echo $view['category'];?>
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
                    <div class="order_title reverse">
                        <span class="item">작성자 정보</span>
                    </div>
                    <div class="border_box order_list">
                        <ul>
                        	<li>
                                <span class="item">작성자(ID)</span>
                                <strong class="result">
                                     <?php echo $view['mb_id'];?>
                                </strong>
                            </li>
                            <li>
                                <span class="item">작성자(email)</span>
                                <strong class="result">
                                    <?php echo $view['email'];?>
                                </strong>
                            </li>
                        </ul>
                    </div>
                </div>
				
                <div class="grid bg_none">
                	<div class="order_title reverse">
                        <span class="item">문의 내용</span>
                    </div>
                    <div class="border_box">
                        <?php echo get_view_thumbnail($view['content'], $qaconfig['qa_image_width']); ?>
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
                    <div class="btn_group <?php if(!$view['qa_status']){ echo 'three';} else if($view['qa_status'] && !$is_admin) { echo 'two';} else { echo 'one';}?>">
                        <?php if ($update_href) { ?><button type="button" class="btn big green" onclick="location.href='<?php echo $update_href; ?>';"><span>수정</span></button><?php }?>
                        <?php if ($delete_href) { ?><button type="button" class="btn big gray" onclick="location.href='<?php echo $delete_href; ?>';"><span>삭제</span></button><?php } ?>
						<button type="button" class="btn big border gray" onclick="location.href='<?php echo G5_BBS_URL?>/qalist.php';"><span>목록</span></button>
						
                        <?php 
                        if($view['qa_status'] && $rewrite_href) { ?>
                        <button type="button" class="btn big green" onclick="location.href='<?php echo $rewrite_href; ?>';"><span>재문의</span></button>
                        <?php }?>
                    </div>
                </div>
                <!-- 컨텐츠 끝 -->
            </div>
        </div>
        <!-- //container -->

        <!-- footer -->

        <!-- //footer -->
    </div>
</body>

</html>