
<?php
//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once('../common.php');

include_once(G5_MOBILE_PATH.'/_head.php');
?>
<!-- container -->
<div id="container">
	<div class="content gate-page">
		<!-- 컨텐츠 시작 -->
		
		<div class="gate-wrap magazine">
			<div class="gate1">
				<div class="gate-box bgnone">
					<dl>
						<dt><span class="mint">LIFELIKE</span>ABOUT</dt>
                        <!-- <dt class="gate-logo">ABOUT
                            <span>LIFELIKE</span>
                        </dt> -->
                        <dd  class="fz20">
							살림의 달인들이 들려주는 생활의 꿀팁과<br>
							삶을 가치 있게 만드는 캠페인까지!<br>
							당신의 일상을 더 다채롭게 할<br>
							다양한 이야기들을 만나보세요.
						</dd>
					</dl>
				</div>
			</div>
			<div class="gate2">
				<div class="gate-box">
					<dl>
						<dt><span class="mint">Tips for Living</span>리빙노하우</dt>
						<dd class="fz20">나만의 얼룩 제거 팁,<br>
							공간 활용을 위한 옷장 정리 비법 등<br>
							소소한 살림 노하우를<br>
							라이프라이크와 함께 나눠보세요.
						</dd>
					</dl>
					<div class="gate-btn">
						<a href="<?php echo G5_BBS_URL?>/board.php?bo_table=living">보러 가기</a>
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