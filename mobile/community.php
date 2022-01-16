
<?php
//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once('../common.php');
ini_set('display_errors', 1);




include_once(G5_MOBILE_PATH.'/_head.php');
?>

<!-- container -->
<div id="container">
	<div class="content gate-page">
		<!-- 컨텐츠 시작 -->
		<div class="visual_area">
			<a href="#"><img src="../../img/mb/content/community_visual.jpg" alt="라이프라이크와 함께 소통해요" /></a>
		</div>
		<div class="gate-wrap community">
			<div class="gate1">
				<div class="gate-box">
					<dl>
                        <dt><span class="mint">공지사항</span>Notice</dt>
                        <dd>이벤트, 체험단 등<br>
                           라이프라이크의 새로운 소식을<br>
                            한눈에 확인하세세요!</dd>
                    </dl>
					<div class="gate-btn">
						<a href="<?php echo G5_BBS_URL?>/board.php?bo_table=notice">보러 가기</a>
					</div>
				</div>
			</div>
			<div class="gate2">
				<div class="gate-box">
					<dl>
                                <dt><span class="mint">이벤트</span>Event</dt>
                                <dd>
									라이프라이크만의 특별한<br>
									이벤트를 함께하고,<br>
									풍성한 혜택도 받아가세요!<br>
                                 </dd>
                            </dl>
					<div class="gate-btn">
						<a href="<?php echo G5_BBS_URL?>/board.php?bo_table=event">보러 가기</a>
					</div>
				</div>
			</div>
			<div class="gate3">
				<div class="gate-box">
					<dl>
                                <dt><span class="mint">체험단 모집</span>Try it</dt>
                                <dd>
								라이프라이크 제품이 궁금하다면?<br>
								체험단을 통해 제품을 경험해보세요!
                                    </dd>
                            </dl>
					<div class="gate-btn">
						<a href="<?php echo G5_BBS_URL?>/board.php?bo_table=experience">보러 가기</a>
					</div>
				</div>
			</div>
			<div class="gate4">
				<div class="gate-box">
					<dl>
                                <dt><span class="mint">생생한 후기</span>review</dt>
                                <dd>
								라이프라이크를 사용하고 느낀<br>
								생생한 후기가 궁금하시다면?
								</dd>
                            </dl>
					<div class="gate-btn">
						<a href="<?php echo G5_BBS_URL?>/board.php?bo_table=review">보러 가기</a>
					</div>
				</div>
			</div>
			<div class="gate5">
				<div class="gate-box">
					<dl>
                                <dt><span class="mint">온라인 집들이</span>#집스타그램 #랜선 집들이 #자랑해<span></span></dt>
                                <dd>
								라이프라이크와 함께하는<br>
								다양한 일상이 궁금하시다면?<br>
								</dd>
                            </dl>
					<div class="gate-btn">
						<a href="<?php echo G5_BBS_URL?>/board.php?bo_table=online">보러 가기</a>
					</div>
				</div>
			</div>
		</div>        
		<!-- 컨텐츠 종료 -->
	</div>
</div>
<!-- //container -->


<?php
include_once(G5_MOBILE_PATH.'/_tail.php');
?>