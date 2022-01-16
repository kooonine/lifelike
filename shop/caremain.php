<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/caremain.php');
    return;
}

$g5['title'] = '케어';
include_once('./_head.php');

?>

<!-- container -->
        <div id="container">
            <div class="content gate-page">
                <!-- 컨텐츠 시작 -->
                <div class="gate-wrap shop">
                    <div class="gate4">
                        <div class="gate-box">
                            <dl>
                                <dt><span class="mint">세탁 서비스</span>숙련된 전문가의 손길</dt>
                                <dd class="fz20">매일 사용하는 침구, 잘 관리하고 계신가요?<br>매번 번거로웠던 세탁, 잘못된 방법으로<br>손상된 침구, 이제는 가장 믿을 수 있는<br>전문 세탁
                                    서비스를 통해 해결하세요.</dd>
                            </dl>
                            <div class="gate-btn">
                                <a href="./care1.php">서비스 안내</a>
                                <a href="./care.php">서비스 신청</a>
                            </div>
                        </div>
                    </div>
                    <div class="gate5">
                        <div class="gate-box">
                            <dl>
                                <dt><span class="mint">세탁보관 서비스</span>경험해보지 못했던 여유로움</dt>
                                <dd class="fz20">좁아지는 공간에 대한 고민을 해결하고자<br>
                                    시작한 라이프라이크의 보관 서비스!<br>
                                    지금까지 경험해보지 못한 공간 활용의<br>
                                    즐거움을 느껴보세요.</dd>
                            </dl>
                            <div class="gate-btn">
                                <a href="./care2.php">서비스 안내</a>
                                <a href="./care.php">서비스 신청</a>
                            </div>
                        </div>
                    </div>
                    <div class="gate6">
                        <div class="gate-box">
                            <dl>
                                <dt><span class="mint">수선 서비스</span>전문가의 1:1 밀착 관리
                                    </dt>
                                <dd class="fz20">아직도 고장 난 지퍼를<br>
                                    사용하고 있으신가요?<br>
                                    믿고 맡길 수 있는 곳.<br>
                                    라이프라이크에서 해결하세요.</dd>
                            </dl>
                            <div class="gate-btn">
                                <a href="./care3.php">서비스 안내</a>
                                <a href="./care.php">서비스 신청</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 컨텐츠 종료 -->
            </div>
        </div>
        <!-- //container -->


</div>
<?php
include_once('./_tail.php');
?>