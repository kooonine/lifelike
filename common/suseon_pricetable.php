<?php
//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once('_common.php');
?>
<html lang="ko">
<head>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi" />
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">

<!-- 스타일 -->
  <link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/js/swiper/swiper.min.css">
 <link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/css/m_common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/css/m_ui.css" />

<!-- 스크립트 -->
<script src="<?php echo G5_MOBILE_URL;?>/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="<?php echo G5_MOBILE_URL;?>/js/swiper/swiper.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo G5_MOBILE_URL;?>/js/m_ui.js" type="text/javascript"></script>
    

</head>
<body>
<section class="popup_container layer" id="pricetable">
	<div class="inner_layer" style="top:0px">
	
	<!-- lnb -->
	<div id="lnb" class="header_bar">
		<h1 class="title"><span>수선 단가표</span></h1>
		<a href="#" class="btn_closed" onclick="self.close();"><span class="blind">닫기</span></a>
	</div>
	<!-- // lnb -->

	<div class="content shop sub">
		<!-- 컨텐츠 시작 -->
		<div class="grid">
                <div class="gray_box pad15">
                    <p>*아래 요금표는 안내를 위한 것으로, 전문 상담사와 통화 후 정확한 요금 안내가 이루어집니다.</p>
                    <p>*수선 서비스는 라이프라이크 제품에 한하여 이용이 가능합니다.</p>
                </div>

                <div class="order_title reverse type2">
                    <span class="item">구스다운 충전 (100g당)</span>
                    <strong class="result"><em class="point">72,000원~</em></strong>
                </div>
                <div class="border_box order_list ">
                    <ul>
                        <li>
                            <span class="item">폴란드산 구스다운 90%</span>
                            <strong class="result">119,000원</strong>
                        </li>
                        <li>
                            <span class="item">헝가리산 구스다운 90%</span>
                            <strong class="result">72,000원</strong>
                        </li>
                    </ul>
                </div>

                <div class="order_title reverse type2">
                    <span class="item">사이즈 수선(줄임)</span>
                    <strong class="result"><em class="point">7,000원~</em></strong>
                </div>
                <div class="border_box order_list reverse ">
                    <ul>
                        <li>
                            <span class="item">이불 커버</span>
                            <strong class="result">15,000원</strong>
                        </li>
                        <li>
                            <span class="item">누비 이불 커버</span>
                            <strong class="result">20,000원</strong>
                        </li>
                        <li>
                            <span class="item">매트리스 커버</span>
                            <strong class="result">10,000원</strong>
                        </li>
                        <li>
                            <span class="item">패드</span>
                            <strong class="result">10,000원</strong>
                        </li>
                        <li>
                            <span class="item">차렵 이불</span>
                            <strong class="result">15,000원</strong>
                        </li>
                    </ul>
                </div>

                <div class="order_title reverse type2">
                    <span class="item">솜 샤시(누비)</span>
                    <strong class="result"><em class="point">10,000원~</em></strong>
                </div>
                <div class="border_box order_list reverse ">
                    <ul>
                        <li>
                            <span class="item">홑겹 이불 커버 → 누비 이불 커버</span>
                            <strong class="result">S → 40,000원</strong>
                        </li>
                        <li>
                            <span class="item">홑겹 매트리스 커버 → 누비 매트리스 커버</span>
                            <strong class="result">Q → 50,000원</strong>
                        </li>
                        <li>
                            <span class="item">홑겹 베개 커버 → 누비 베개 커버</span>
                            <strong class="result">50,000원</strong>
                        </li>
                    </ul>
                </div>

                <div class="order_title reverse type2">
                    <span class="item">밴드 교체</span>
                    <strong class="result"><em class="point">8,000원~</em></strong>
                </div>
                <div class="border_box order_list reverse ">
                    <ul>
                        <li>
                            <span class="item">패드(모서리 4면)</span>
                            <strong class="result">8,000원</strong>
                        </li>
                        <li>
                            <span class="item">매트리스 커버(양 쪽 2개)</span>
                            <strong class="result">12,000원</strong>
                        </li>
                    </ul>
                </div>

                <div class="order_title reverse type2">
                    <span class="item">지퍼 수선</span>
                    <strong class="result"><em class="point">5,000원~</em></strong>
                </div>
                <div class="border_box order_list reverse ">
                    <ul>
                        <li>
                            <span class="item">대품(이불, 이불커버)</span>
                            <strong class="result">8,000원</strong>
                        </li>
                        <li>
                            <span class="item">베개</span>
                            <strong class="result">5,000원</strong>
                        </li>
                    </ul>
                </div>

                <div class="order_title reverse type2">
                    <span class="item">매트리스 커버 수선</span>
                    <strong class="result"><em class="point">27,000원~</em></strong>
                </div>
                <div class="border_box order_list reverse ">
                    <ul>
                        <li>
                            <span class="item">프릴단 전체 교체 ~10cm, 20cm 길이 수선</span>
                            <strong class="result">27,000원 ~ 32,000원</strong>
                        </li>
                    </ul>
                </div>

            </div>
            
            <div class="grid bg_none">
                <div class="toggle inquiry_toggle">
                    <div class="toggle_group type2 opened">
                        <div class="title">
                            <h3 class="tit ellipsis">참조사항</h3>
                        </div>
                        <div class="cont">
                            <div class="white_box info_box">
                                <p class="tit">* 베이직라인 이불속통 (중량추가주입)</p>
                                <ul class="hyphen">
                                    <li>원산지별 100g 당 가격</li>
                                    <li>속통류는 베이직라인만 가능</li>
                                    <li>원단 덧댐 수선은 별도문의</li>
                                </ul>
                                <p class="tit">* 사이즈 수선 (줄임)</p>
                                <ul class="hyphen">
                                    <li>지퍼수선이 필요할경우 추가</li>
                                </ul>
                                <p class="tit">* 사이즈 수선 (늘임)</p>
                                <ul class="hyphen">
                                    <li>정상 런제품 가능하며,<br>원자재 보유제품이어야 함</li>
                                </ul>
                                <p class="tit">* 매커프릴수선 교체 / 높이연폭</p>
                                <ul class="hyphen">
                                    <li>프릴단 교체(완성 35cm)</li>
                                    <li>기존끝에서 추가(35+20)</li>
                                    <li>기존끝에서 추가(35+10)</li>
                                </ul>
                                <p class="tit">* 랍바수선 (면)</p>
                                <ul class="hyphen">
                                    <li>정상제품 가능하며, 원자재 보유제품이어야 함</li>
                                </ul>
                                <p class="tit">* 앞, 뒤 판갈이</p>
                                <ul class="hyphen">
                                    <li>패드 판갈이 불가능(짜집기, 덧뎀)</li>
                                </ul>
                                <p class="tit">* 손샤시</p>
                                <ul class="hyphen">
                                    <li>기존 사용솜 이외의 신청은 불가</li>
                                </ul>
                                <p class="tit">* 밴드교체</p>
                                <ul class="hyphen">
                                    <li>해당없음</li>
                                </ul>
                                <p class="tit">* 지퍼수선</p>
                                <ul class="hyphen">
                                    <li>옥매트 ㄷ자형 : 메모리폼 커버</li>
                                </ul>
                                <p class="tit">* 삥줄(3면)</p>
                                <ul class="hyphen">
                                    <li>해당없음</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		<!-- 컨텐츠 종료 -->
	</div>
</section>

		<!-- //popup -->
		<script>
			$(document).ready(function(){
				var url = "<?php echo G5_URL?>"
				$('button[name="btn"]').click(function() {
					if($(this).attr('data') == 'stop'){
						if(opener.closed) {   //부모창이 닫혔는지 여부 확인

						      // 부모창이 닫혔으니 새창으로 열기

						      window.open(url, "openWin");

						   } else {

						      opener.location.href = url;

						      opener.focus();

						   }
					}
					self.close();
				});
			});
		</script>
		
</body>
</html>
