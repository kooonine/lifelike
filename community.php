<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/community.php');
    return;
}

include_once(G5_PATH.'/head.php');
?>

        <!-- container -->
        <div id="container">
            <div class="content gate-page">
                <!-- 컨텐츠 시작 -->
                <!-- <div class="visual_area">
                    <a href="#"><img src="../img/pc/content/community_visual.jpg" alt="리페리케와 함께 소통해요" /></a>
                </div> -->
                <div class="gate-wrap community">

					<div class="gate0">
						<div class="gate-box bgnone">
							<dl>
								<dt>
									<span class="mint">LIFELIKE</span>
									<span>Talk with</span>
								</dt>
								<dd  class="fz20">
									라이프라이크 이용 고객님들의 생생한 리뷰와<br>
									새로운 소식, 다양한 이벤트까지!<br>
									라이프라이크를 통해<br>
									당신의 이야기를 들려주세요.
									</dd>
							</dl>
						</div>
					</div>

                    <div class="gate1">
                        <div class="gate-box">
                            <dl>
                                <dt><span class="mint">공지사항</span><span>Notice</span></dt>
                                <dd class="fz20">
                                    이벤트, 체험단 등<br>라이프라이크의 새로운 소식을<br>한눈에 확인하세세요!
                                </dd>
                            </dl>
                            <div class="gate-btn">
                                <a href="<?php echo G5_BBS_URL?>/board.php?bo_table=notice">보러 가기</a>
                            </div>
                        </div>
                    </div>
                    <div class="gate2">
                        <div class="gate-box">
                            <dl>
                                <dt><span class="mint">이벤트</span><span>Event</span></dt>
                                <dd class="fz20">
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
                                <dt class="mb20"><span class="mint">체험단 모집</span><span>Try it</span></dt>
                                <dd class="fz20">
                                    라이프라이크 제품이 궁금하다면?<br>
                                    체험단을 통해 제품을 경험해보세요!<br>
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
                                <dt class="mb20"><span class="mint">생생한 후기</span><span>review</span></dt>
                                <dd class="fz20">
                                    라이프라이크를 사용하고 느낀<br>
                                   생생한 후기가 궁금하시다면?<br>
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
                                <dt class="mb20"><span class="mint">온라인집들이</span><span>#집스타그램 #랜선집들이 #자랑해</span></dt>
                                <dd class="fz20">
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
include_once(G5_PATH.'/tail.php');
?>