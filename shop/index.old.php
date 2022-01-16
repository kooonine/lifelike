<?
include_once('./_common.php');

if (defined('G5_THEME_PATH')) {
    require_once(G5_THEME_SHOP_PATH . '/index.php');
    return;
}

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH . '/index.php');
    return;
}

include_once(G5_SHOP_PATH . '/shop.head.php');
?>
<!-- container -->
<div id="container">
    <div class="content gate-page">
        <!-- 컨텐츠 시작 -->
        <div class="gate-wrap shop">
            <div class="gate1">
                <div class="gate-box">
                    <dl>
                        <dt><span class="mint">LIFELIKE</span><span>Like your own Life</span></dt>
                        <!-- <dt class="gate-logo mb30">Like your own Life
							<span>LIFELIKE</span>
						</dt> -->
                        <dd class="fz20">
                            삶을 편안하게, 더욱 다채롭게, 보다 즐겁게!<br>
                            라이프라이크의 즐거운 시도,<br>
                            지금 만나보세요.
                        </dd>
                    </dl>
                    <div class="gate-btn">
                        <a href="<?php echo G5_URL ?>/about.php">About Lifelike</a>
                    </div>
                </div>
            </div>
            <div class="gate2">
                <div class="gate-box">
                    <dl>
                        <dt><span class="mint">리스 서비스</span><span>합리적 소비의 시작</span></dt>
                        <dd class="fz20">구스 침구의 장점은 더하고<br>
                            관리의 불편함은 덜고,<br>
                            라이프라이크의 리스서비스!
                        </dd>
                    </dl>
                    <div class="gate-btn">
                        <a href="<?php echo G5_URL ?>/listener.php">서비스 안내</a>
                    </div>
                </div>
            </div>
            <div class="gate3">
                <div class="gate-box">
                    <dl>
                        <dt><span class="mint">라이프라이크 베딩</span><span>당신의 침실을 더욱 감각적으로!</span></dt>
                        <dd class="fz20">국내 최고의 기술력과 디자인력이 만난<br> 라이프라이크만의 트랜디한 베딩<br>당신만의 감각으로 공간을 연출해보세요.</dd>
                    </dl>
                    <div class="gate-btn">
                        <a href="<?php echo G5_SHOP_URL ?>/list.php?ca_id=1010">보러 가기</a>
                    </div>
                </div>
            </div>
            <div class="gate4">
                <div class="gate-box">
                    <dl>
                        <dt><span class="mint">세탁 서비스</span><span>숙련된 전문가의 손길</span></dt>
                        <dd class="fz20">매일 사용하는 침구, 잘 관리하고 계신가요?<br>매번 번거로웠던 세탁, 잘못된 방법으로<br>손상된 침구, 이제는 가장 믿을 수 있는<br>전문 세탁
                            서비스를 통해 해결하세요.</dd>
                    </dl>
                    <div class="gate-btn">
                        <a href="<?php echo G5_SHOP_URL ?>/care1.php">서비스 안내</a>
                        <a href="<?php echo G5_SHOP_URL ?>/care.php">서비스 신청</a>
                    </div>
                </div>
            </div>
            <!--
			<div class="gate5">
				<div class="gate-box">
					<dl>
						<dt><span class="mint">세탁보관 서비스</span><span>경험해보지 못했던 여유로움</span></dt>
						<dd class="fz20">좁아지는 공간에 대한 고민을 해결하고자<br>
							시작한 라이프라이크의 보관 서비스!<br>
							지금까지 경험해보지 못한 공간 활용의<br>
							즐거움을 느껴보세요.</dd>
					</dl>
					<div class="gate-btn">
						<a href="<?php echo G5_SHOP_URL ?>/care2.php">서비스 안내</a>
						<a href="<?php echo G5_SHOP_URL ?>/care.php">서비스 신청</a>
					</div>
				</div>
			</div>
			<div class="gate6">
				<div class="gate-box">
					<dl>
						<dt><span class="mint">수선 서비스</span><span>전문가의 1:1 밀착 관리</span>
						</dt>
						<dd class="fz20">아직도 고장 난 지퍼를<br>
							사용하고 있으신가요?<br>
							믿고 맡길 수 있는 곳.<br>
							라이프라이크에서 해결하세요.</dd>
					</dl>
					<div class="gate-btn">
						<a href="<?php echo G5_SHOP_URL ?>/care3.php">서비스 안내</a>
						<a href="<?php echo G5_SHOP_URL ?>/care.php">서비스 신청</a>
					</div>
				</div>
			</div>
-->
        </div>
        <!-- 컨텐츠 종료 -->
    </div>
</div>
<!-- //container -->
<? include_once(G5_SHOP_PATH . '/shop.tail.php'); ?>