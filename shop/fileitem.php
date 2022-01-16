<?php
include_once('./_common.php');

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_SHOP_PATH.'/finditem.php');
    return;
}

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/finditem.php');
    return;
}

include_once(G5_SHOP_PATH.'/shop.head.php');
?>

		<!-- container -->
		<div id="container">
			<div class="content shop">
				<!-- 컨텐츠 시작 -->
				<div class="grid">
					<div class="title_bar bg_title">
						<h2 class="title">내게 맞는 제품 찾기</h2>
						<p class="txt">나에게 맞는 제품을 검색 해 보세요<br>원하는 카테고리를 선택 하시면, 딱 맞는 제품을 추천 해 드려요. </p>
					</div>
				</div>

				<div class="grid bg_none">
					<div class="white_box curation_wrap">
						<strong class="title">구매와 리스, 어떤 걸 선호 하세요?</strong>
						<ul class="category_list onoff"><!-- 선택하면 on 추가 -->
							<li><button type="button" class="category round_black on">구매</button></li>
							<li><button type="button" class="category round_black">리스</button></li>
						</ul>
						<strong class="title">어떤 색이 좋아요?</strong>
						<ul class="category_list"><!-- 선택하면 on 추가 -->
							<li><button type="button" class="category round_black on">화이트</button></li>
							<li><button type="button" class="category round_black">블루</button></li>
							<li><button type="button" class="category round_black on">골드</button></li>
							<li><button type="button" class="category round_black">그레이</button></li>
							<li><button type="button" class="category round_black">핑크</button></li>
						</ul>
						<strong class="title">혼자 살아요? 아니면...</strong>
						<ul class="category_list"><!-- 선택하면 on 추가 -->
							<li><button type="button" class="category round_black on">실글</button></li>
							<li><button type="button" class="category round_black">신혼</button></li>
							<li><button type="button" class="category round_black">친구와</button></li>
							<li><button type="button" class="category round_black">부모님과</button></li>
							<li><button type="button" class="category round_black">아이와</button></li>
						</ul>
						<strong class="title">누가 사용 하는 건가요?</strong>
						<ul class="category_list"><!-- 선택하면 on 추가 -->
							<li><button type="button" class="category round_black">영 유아/어린이</button></li>
							<li><button type="button" class="category round_black">청소년</button></li>
							<li><button type="button" class="category round_black">20대</button></li>
							<li><button type="button" class="category round_black">30대</button></li>
							<li><button type="button" class="category round_black on">40대</button></li>
							<li><button type="button" class="category round_black on">50대 이상</button></li>
						</ul>
						<strong class="title">어떤 소재가 좋으세요?</strong>
						<ul class="category_list"><!-- 선택하면 on 추가 -->
							<li><button type="button" class="category round_black">다운프루프</button></li>
							<li><button type="button" class="category round_black">헝가리구스</button></li>
							<li><button type="button" class="category round_black">시베리아구스</button></li>
							<li><button type="button" class="category round_black">텐셀</button></li>
							<li><button type="button" class="category round_black">누비</button></li>
						</ul>
						<strong class="title">원하는 제품의 느낌은...?</strong>
						<ul class="category_list"><!-- 선택하면 on 추가 -->
							<li><button type="button" class="category round_black">호텔같은</button></li>
							<li><button type="button" class="category round_black on">포근한</button></li>
							<li><button type="button" class="category round_black">시원한</button></li>
							<li><button type="button" class="category round_black">따뜻한</button></li>
						</ul>
						<strong class="title">다양한 브랜드에서도 찾아보세요!</strong>
						<ul class="category_list"><!-- 선택하면 on 추가 -->
							<li><button type="button" class="category round_black">삼분의일</button></li>
							<li><button type="button" class="category round_black">하나조</button></li>
						</ul>
						<strong class="title">원하는 가격대가 얼마인가요?</strong>
						<ul class="category_list"><!-- 선택하면 on 추가 -->
							<li><button type="button" class="category round_black">5만원 미만</button></li>
							<li><button type="button" class="category round_black">10만원</button></li>
							<li><button type="button" class="category round_black">20만원</button></li>
							<li><button type="button" class="category round_black">30만원</button></li>
							<li><button type="button" class="category round_black on">50만원</button></li>
							<li><button type="button" class="category round_black">70만원</button></li>
							<li><button type="button" class="category round_black">100만원</button></li>
						</ul>
					</div>
				
					<div class="curation_choice">
						<button type="button" class="btn_reset txt">초기화</button>
						<ul class="list">
							<li><span>구매<a href="#" class="btn_del"><span class="blind">삭제</span></a></span></li>
							<li><span>싱글<a href="#" class="btn_del"><span class="blind">삭제</span></a></span></li>
							<li><span>30대<a href="#" class="btn_del"><span class="blind">삭제</span></a></span></li>
							<li><span>따뜻한<a href="#" class="btn_del"><span class="blind">삭제</span></a></span></li>
							<li><span>30만원<a href="#" class="btn_del"><span class="blind">삭제</span></a></span></li>
						</ul>
					</div>
				</div>

				<div class="page_title">
					<span class="point">#30대 #싱글</span>인기많은 제품이에요!
				</div>

				<div class="grid tab_cont_wrap">
					<div class="tab">
						<ul class="type2 onoff tab_btn">
							<li class="on"><a href="#"><span>인기순</span></a></li>
							<li><a href="#"><span>최신순</span></a></li>
							<li><a href="#"><span>낮은가격순</span></a></li>
							<li><a href="#"><span>높은가격순</span></a></li>
						</ul>
					</div>
					<div class="tab_cont">
						<!-- tab1 -->
						<div class="tab_inner">
							<div class="item_row_list">
								<ul class="count4">
									<li>
										<a href="#">
											<div class="photo"><img src="../img/mb/sample/sample_11.jpg" alt=""></div>
											<div class="cont">
												<strong class="title bold">시베리안구스 이불솜</strong>
												<span class="text ellipsis">한겨울을 거뜬하게 거뜬하게 거뜬하게 거뜬하게 거뜬하게 거뜬하게</span>
												<span class="price">399,000 원</span>
											</div>
										</a>
										<div class="btn_comm big bottom"><!-- 찜 눌르면 class="on" 추가 -->
											<button type="button" class="pick ico on"><span class="blind">찜</span></button>
										</div>
									</li>
									<li>
										<a href="#">
											<div class="photo"><img src="../img/mb/sample/sample_12.jpg" alt=""></div>
											<div class="cont">
												<strong class="title bold">시베리안구스 이불솜시베리안구스 이불솜</strong>
												<span class="text ellipsis">한겨울을 거뜬하게</span>
												<span class="price">399,000 원</span>
											</div>
										</a>
										<div class="btn_comm big bottom"><!-- 찜 눌르면 class="on" 추가 -->
											<button type="button" class="pick ico"><span class="blind">찜</span></button>
										</div>
									</li>
									<li>
										<a href="#">
											<div class="photo"><img src="../img/mb/sample/sample_12.jpg" alt=""></div>
											<div class="cont">
												<strong class="title bold">시베리안구스 이불솜시베리안구스 이불솜</strong>
												<span class="text ellipsis">한겨울을 거뜬하게</span>
												<span class="price">399,000 원</span>
											</div>
										</a>
										<div class="btn_comm big bottom"><!-- 찜 눌르면 class="on" 추가 -->
											<button type="button" class="pick ico"><span class="blind">찜</span></button>
										</div>
									</li>
									<li>
										<a href="#">
											<div class="photo"><img src="../img/mb/sample/sample_12.jpg" alt=""></div>
											<div class="cont">
												<strong class="title bold">시베리안구스 이불솜시베리안구스 이불솜</strong>
												<span class="text ellipsis">한겨울을 거뜬하게</span>
												<span class="price">399,000 원</span>
											</div>
										</a>
										<div class="btn_comm big bottom"><!-- 찜 눌르면 class="on" 추가 -->
											<button type="button" class="pick ico"><span class="blind">찜</span></button>
										</div>
									</li>
									<li>
										<a href="#">
											<div class="photo"><img src="../img/mb/sample/sample_12.jpg" alt=""></div>
											<div class="cont">
												<strong class="title bold">시베리안구스 이불솜시베리안구스 이불솜</strong>
												<span class="text ellipsis">한겨울을 거뜬하게</span>
												<span class="price">399,000 원</span>
											</div>
										</a>
										<div class="btn_comm big bottom"><!-- 찜 눌르면 class="on" 추가 -->
											<button type="button" class="pick ico"><span class="blind">찜</span></button>
										</div>
									</li>
									<li>
										<a href="#">
											<div class="photo"><img src="../img/mb/sample/sample_12.jpg" alt=""></div>
											<div class="cont">
												<strong class="title bold">시베리안구스 이불솜시베리안구스 이불솜</strong>
												<span class="text ellipsis">한겨울을 거뜬하게</span>
												<span class="price">399,000 원</span>
											</div>
										</a>
										<div class="btn_comm big bottom"><!-- 찜 눌르면 class="on" 추가 -->
											<button type="button" class="pick ico"><span class="blind">찜</span></button>
										</div>
									</li>
									<li>
										<a href="#">
											<div class="photo"><img src="../img/mb/sample/sample_12.jpg" alt=""></div>
											<div class="cont">
												<strong class="title bold">시베리안구스 이불솜시베리안구스 이불솜</strong>
												<span class="text ellipsis">한겨울을 거뜬하게</span>
												<span class="price">399,000 원</span>
											</div>
										</a>
										<div class="btn_comm big bottom"><!-- 찜 눌르면 class="on" 추가 -->
											<button type="button" class="pick ico"><span class="blind">찜</span></button>
										</div>
									</li>
									<li>
										<a href="#">
											<div class="photo"><img src="../img/mb/sample/sample_12.jpg" alt=""></div>
											<div class="cont">
												<strong class="title bold">시베리안구스 이불솜시베리안구스 이불솜</strong>
												<span class="text ellipsis">한겨울을 거뜬하게</span>
												<span class="price">399,000 원</span>
											</div>
										</a>
										<div class="btn_comm big bottom"><!-- 찜 눌르면 class="on" 추가 -->
											<button type="button" class="pick ico"><span class="blind">찜</span></button>
										</div>
									</li>
								</ul>
							</div>
						</div>
						<!-- tab2 -->
						<div class="tab_inner">
						</div>
						<!-- tab3 -->
						<div class="tab_inner">
						</div>
						<!-- tab4 -->
						<div class="tab_inner">
						</div>
					</div>
					
				</div>
				<!-- 컨텐츠 종료 -->
			</div>
		</div>
		<!-- //container -->

<?php
include_once(G5_SHOP_PATH.'/shop.tail.php');
?>