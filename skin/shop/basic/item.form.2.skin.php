<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
//브랜드

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);
?>

<form name="fitem" method="post" action="<?php echo $action_url; ?>" onsubmit="return fitem_submit(this);">
<input type="hidden" name="it_id[]" value="<?php echo $it_id; ?>">
<input type="hidden" name="sw_direct">
<input type="hidden" name="url">

		<!-- container -->
		<div id="container" class="no_title">
			<!-- lnb -->
			<div id="lnb" class="header_bar blind">
				<h1 class="title"><span>상세</span></h1>
			</div>
			<!-- // lnb -->
			<div class="content shop floating">
				<!-- 컨텐츠 시작 -->
				<div class="grid none frt_info_wrap">
					<div class="photo gallery_view">
						<div class="swiper-container gallery-top">
							<div class="swiper-wrapper">
                            <?php
                            $big_img_count = 0;
                            $thumbnails = array();
                            for($i=1; $i<=10; $i++) {
                                if(!$it['it_img'.$i])
                                    continue;
                    
                                $img = get_it_thumbnail_path($it['it_img'.$i], 480, 480);
                    
                                if($img) {
                                    // 썸네일
                                    $thumb = get_it_thumbnail_path($it['it_img'.$i], 120, 120);
                                    $thumbnails[] = $thumb;
                                    $big_img_count++;
                                    
                                    echo '<div class="swiper-slide" style="background-image:url('.$img.')"></div>';
                                }
                            }
                    
                            if($big_img_count == 0) {
                                echo '<div class="swiper-slide" style="background-image:url('.G5_SHOP_URL.'/img/no_image.gif)"></div>';
                            }
                            ?>
							</div>
						</div>
						<div class="swiper-container gallery-thumbs">
							<div class="swiper-wrapper">
                            <?php
                            // 썸네일
                            $total_count = count($thumbnails);
                            if($total_count > 0) {
                                
                                foreach($thumbnails as $val) {
                                    echo '<div class="swiper-slide" style="background-image:url('.$val.')"></div>';
                                }
                                
                            }
                            ?>
                            </div>
							<!-- Add Arrows -->
							<div class="swiper-button-next swiper-button-white"></div>
							<div class="swiper-button-prev swiper-button-white"></div>
						</div>
						 <script>
							var galleryThumbs = new Swiper('.gallery_view .gallery-thumbs', {
								  spaceBetween: 12,
								  slidesPerView: 4,
								  freeMode: true,
								  watchSlidesVisibility: true,
								  watchSlidesProgress: true,
								  slideToClickedSlide: true,
								navigation: {
									nextEl: '.swiper-button-next',
									prevEl: '.swiper-button-prev',
								  },
							});
							var galleryTop = new Swiper('.gallery_view .gallery-top', {
							  effect: 'fade',
							  spaceBetween: 0,
							  thumbs: {
								swiper: galleryThumbs,
							  },
							 
							});
					  </script>
					</div>
					
					
					<div class="frt_descript">
						<div class="head">
							<div class="comm">
								<!-- span class="category round">SALE</span -->
								
								<div class="btn_comm"><!-- 찜 눌르면 class="on" 추가 -->
									<a href="javascript:item_wish(document.fitem, '<?php echo $it['it_id']; ?>');"><button type="button" class="pick ico"><span class="blind">찜</span></button></a>
									<button type="button" class="shared"><span class="blind">공유</span></button>
								</div>
							</div>
							<p class="title"><?php echo stripslashes($it['it_name']); ?></p>
						</div>
						<div class="order_list">
							<ul>
								<li>
									<span class="item">판매가</span>
									<strong class="result">
										<em class="bold"><?php echo display_price(get_price($it)); ?></em>
									</strong>
									<input type="hidden" id="it_price" value="<?php echo get_price($it); ?>">
								</li>
								<li>								
                                    <span class="item info_add">케어 서비스</span>
                                    <div class="info_service">
                                        <p class="info_title">프리미엄 모스코우 시베리안산 거위털 이불솜</p>
                                        <ul class="info_list">
											<li class="cell"><div class="border_box">무료세탁 1회</div></li>
											<li class="cell"><div class="border_box">세탁 30,000</div></li>
											<li class="cell"><div class="border_box">세탁보관 30,000</div></li>
											<li class="cell"><div class="border_box">- 수선 후불</div></li>
										</ul>
                                    </div>
                                 </li>
                                 
                                 <li>
                                    <p class="info_title">프리미엄 모스코우 시베리안산 거위털 이불솜 <span class="price"><em class="point">286,000</em> 원</span></p>
                                    <ul class="info_option">
                                        <li>
                                            <span class="item">옵션 1.</span>
                                            <strong class="result">
                                                <button type="button" class="btn_select">선택</button>
                                            </strong>
                                        </li>
                                        <li>
                                            <span class="item">옵션 2.</span>
                                            <strong class="result">
                                                <button type="button" class="btn_select">선택</button>
                                            </strong>
                                        </li>
                                    </ul>							
                                </li>
                                <li>
									<span class="item">구매 수량</span>
									<strong class="result">
										<div class="count_control">
											<em class="num">
												<span class="blind">현재수량</span>                          
												<span>1</span>               
											</em>                             
											<button type="button" class="count_minus"><span class="blind">수량줄임</span></button>               
											<button type="button" class="count_plus"><span class="blind">수량추가</span></button>        
										</div>
									</strong>
								</li>
							</ul>
						</div>
						<div class="cont_foot">
							<div class="order_total count4">
								<p class="txt">월 이용료 (36개월)<del class="price">398,000</del></p>
								<strong class="price"><em class="per">15%</em><em class="point">338,300</em> 원</strong>
							</div>
							<div class="order_total">
								<p class="txt">총 완납 금액</p>
								<strong class="price"><em>338,300</em> 원</strong>
							</div>
						</div>
						<div class="btn_group two">
							<button type="button" class="btn big white"><span>장바구니 담기</span></button>
							<button type="button" class="btn big green"><span>바로구매</span></button>
						</div>
					</div>
				</div>

				<!-- 상품정보 -->
				<div class="grid">
					<div class="title_bar">
						<h3 class="g_title_01">상품 정보</h3>
					</div>
					<div class="order_list">
						<ul>
							<li>
								<span class="item">모델명</span>
								<strong class="result">
									LIKELIFE530712
								</strong>
							</li>
							<li>
								<span class="item">제품크기</span>
								<strong class="result">
									Model name : 100X120cm/Model name2 : 100X120cm/
								</strong>
							</li>
							<li>
								<span class="item">소재</span>
								<strong class="result">
									80수 다운프루트 면 100%
								</strong>
							</li>
							<li>
								<span class="item">충전재</span>
								<strong class="result">
									헝가리산 구스 다운 80%/스몰 페더 20%
								</strong>
							</li>
							<li>
								<span class="item">중량/필파워</span>
								<strong class="result">
									784g/750FP
								</strong>
							</li>
							<li>
								<span class="item">색상</span>
								<strong class="result">
									네이비/그레이
								</strong>
							</li>
							<li>
								<span class="item">구성품</span>
								<strong class="result">
									이불1/베게2
								</strong>
							</li>
							<li>
								<span class="item">배송방법</span>
								<strong class="result">
									택배배송
								</strong>
							</li>
						</ul>
					</div>
				</div>
				
				<!-- 관련제품 -->
				<div class="grid">
					<div class="title_bar">
						<h3 class="g_title_01">관련제품</h3>
					</div>
					<div class="item_row_list">
						<ul class="count4">
							<li></li>
						</ul>
					</div>
				</div>
				
				<div class="grid none tab_cont_wrap">
					<div class="tab">
						<ul class="type3 onoff tab_btn">
							<li class="on"><a href="#"><span>제품설명</span></a></li>
							<li class=""><a href="#"><span>상세정보</span></a></li>
							<li class=""><a href="#"><span>REVIEW(1,234)</span></a></li>
							<li class=""><a href="#"><span>제품문의</span></a></li>
						</ul>
					</div>
					<div class="tab_cont">
						<!-- tab1 -->
						<div class="tab_inner">
							<div class="grid">
								<h3 class="blind">제품설명</h3>
								<div class="detail_wrap">
									<div class="photo"><img src="../img/mb/sample/sample_02.jpg" alt=""></div>
									<p class="big alignC">최상의 프리미엄 구스 다운, '모스코우'가<br>세상에서 가장 부드럽고 포근한 곳으로<br>당신의 공간을 채웁니다.</p>
								</div>
							</div>
						</div>
						<!-- tab2-->
						<div class="tab_inner">
							<div class="grid">
								<div class="title_bar none alignC padNone">
									<h3 class="g_title_06">제품 상세 정보</h3>
								</div>
								<div class="order_list border_box">
									<ul>
										<li>
											<span class="item">모델명</span>
											<strong class="result">
												LIKELIFE530712
											</strong>
										</li>
										<li>
											<span class="item">소재</span>
											<strong class="result">
												80수 다운프루트 면 100%
											</strong>
										</li>
										<li>
											<span class="item">충전재</span>
											<strong class="result">
												헝가리산 구스 다운 80%/스몰 페더 20%
											</strong>
										</li>
										<li>
											<span class="item">중량</span>
											<strong class="result">
												784g/750FP
											</strong>
										</li>
										<li>
											<span class="item">사이즈</span>
											<strong class="result">
												슈퍼싱글:100X120cm
											</strong>
										</li>
										<li>
											<span class="item">구성</span>
											<strong class="result">
												구성품 노출
											</strong>
										</li>
										<li>
											<span class="item">제조국</span>
											<strong class="result">
												국가명
											</strong>
										</li>
										<li>
											<span class="item">제조사</span>
											<strong class="result">
												태평양물산(주)
											</strong>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<!-- tab3-->
						<div class="tab_inner">
							<h3 class="blind">REVIEW</h3>
							<div class="item_row_list">
								<ul class="count4">
									<li>
										<a href="#">
											<div class="photo"><img src="../img/mb/sample/sample_11.jpg" alt=""></div>
											<div class="cont">
												<div class="star_rating">
													평점 : <span class="num">5.0</span>
													<div class="star"><!-- width = 평점 2배 -->
														<div class="star_bar"><span class="bar" style="width:80%;"></span></div>
													</div>
												</div>
												<strong class="title bold">시베리안구스 이불솜</strong>
												<span class="text ellipsis">한겨울을 거뜬하게 거뜬하게 거뜬하게 거뜬하게 거뜬하게 거뜬하게</span>
											</div>
										</a>
									</li>
								</ul>
							</div>

							<div class="grid border_box review_info">
								<div class="review_star">
									<p class="title">구매 고객 총 평점</p>
									<div class="star big"><!-- width = 평점 2배 -->
										<div class="star_bar"><span class="bar" style="width:<?php echo $star_score*20 ?>%;"></span></div>
										<span class="star_num"><strong><?php echo $star_score ?></strong>/5</span>
									</div>
									<p class="text">총<span class="point"><?php echo number_format($it['it_use_cnt']); ?></span>건 REVIEW 기준</p>
								</div>
								<div class="graph_wrap">
									<div class="graph_box">
										<ul>
											<li>
												<div class="graph_bar">
													<span class="bar"><!-- 높이값 평점 2배 -->
														<span class="in_txt" style="height:20%;">-</span>
													</span>
												</div>
												<span class="txt">20대</span>
											</li>
											<li>
												<div class="graph_bar">
													<span class="bar">
														<span class="in_txt best" style="height:96%;">4.8</span>
													</span>
												</div>
												<span class="txt">30대</span>
											</li>
											<li>
												<div class="graph_bar">
													<span class="bar">
														<span class="in_txt" style="height:82%;">4.1</span>
													</span>
												</div>
												<span class="txt">40대</span>
											</li>
											<li>
												<div class="graph_bar">
													<span class="bar">
														<span class="in_txt" style="height:60%;">3</span>
													</span>
												</div>
												<span class="txt">50대</span>
											</li>
										</ul>
									</div>
								</div>
								<div class="right_cmt">
									이제품은<br><span class="point">30대</span> 에<br>가장 인기가 많아요!
								</div>
							</div>

							<div class="grid tab_cont_wrap">
								<div class="tab none">
									<ul class="type2 alignL onoff tab_btn">
										<li class="on"><a href="#"><span>전체리뷰</span></a></li>
										<li class=""><a href="#"><span>포토리뷰</span></a></li>
										<li class=""><a href="#"><span>동영상 리뷰</span></a></li>
									</ul>
									<div class="none_sel">
										<span class="select">
											<select name="" title="목록">
												<option value="" selected="">최신등록순</option>
												<option value=""></option>
											</select>
										</span>
									</div>
								</div>
								<div class="tab_cont">
									<!-- tab1 -->
									<div class="tab_inner">
										<div class="tbl_list">
											<table>
												<colgroup>
													<col style="width:10%;">
													<col style="width:35%;">
													<col style="width:10%;">
													<col style="width:15%;">
													<col style="width:15%;">
													<col style="width:15%;">
												</colgroup>
												<thead>
													<tr>
														<th>번호</th>
														<th class="alignL">제목</th>
														<th>평점</th>
														<th>작성자</th>
														<th>작성일</th>
														<th>조회수</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>5</td>
														<td class="alignL"><a href="#" class="ellipsis">바스락 거리는 소리마저도 기분 좋아요</a><span class="review_photo"></span></td>
														<td class="point">5.0</td>
														<td>천하태평 김태평</td>
														<td class="date">2019-03-04 17:48</td>
														<td>121</td>
													</tr>
													<tr>
														<td>4</td>
														<td class="alignL"><a href="#" class="ellipsis">바스락 거리는 소리마저도 기분 좋아요</a><span class="review_video"></span></td>
														<td class="point">5.0</td>
														<td>천하태평 김태평</td>
														<td class="date">2019-03-04 17:48</td>
														<td>121</td>
													</tr>
													<tr>
														<td>3</td>
														<td class="alignL"><a href="#" class="ellipsis">바스락 거리는 소리마저도 기분 좋아요바스락 거리는 소리마저도 기분 좋아요바스락 거리는 소리마저도 기분 좋아요</a></td>
														<td class="point">5.0</td>
														<td>천하태평 김태평</td>
														<td class="date">2019-03-04 17:48</td>
														<td>121</td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="paging">
											<a href="#" class="prev"></a>
											<span class="num">
												<a href="#">1</a>
												<a href="#">2</a>
												<a href="#">3</a>
												<a href="#">4</a>
												<a href="#" class="current">5</a>
												<a href="#">6</a>
												<a href="#">7</a>
												<a href="#">8</a>
												<a href="#">9</a>
												<a href="#">10</a>
											</span>
											<a href="#" class="next"></a>
											<button type="button" class="btn big green"><span>REVIEW 작성</span></button>
										</div>
									</div>
									<!-- tab2 -->
									<div class="tab_inner">
										22
									</div>
									<!-- tab3 -->
									<div class="tab_inner">
										33
									</div>
								</div>
							</div>
						</div>
						<!-- tab4-->
						<div class="tab_inner">
							<div class="grid">
								<h3 class="blind">제품문의</h3>
								<div class="gray_box info_top">
									<p class="ico_import red point_red">제품에 관한 문의가 아닌 배송, 결제, 교환/반품에 대한 문의는 고객센터 1:1 상담을 이용해 주세요.</p>
									<a href="#javascript:" class="btn small border"><span>바로가기</span></a>
								</div>
							</div>
							<div class="grid tab_cont_wrap">
								<div class="tab none">
									<ul class="type2 alignL onoff tab_btn">
										<li class="on"><a href="#"><span>전체</span></a></li>
										<li class=""><a href="#"><span>답변 완료</span></a></li>
										<li class=""><a href="#"><span>답변 대기</span></a></li>
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
													<col style="width:35%;">
													<col style="width:15%;">
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
														<th>조회수</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>5</td>
														<td class="state">답변 대기</td>
														<td class="alignL"><a href="#" class="ellipsis qna_btn">바스락 거리는 소리마저도 기분 좋아요</a></td>
														<td>천하태평 김태평</td>
														<td class="date">2019-03-04 17:48</td>
														<td>121</td>
													</tr>
													<tr>
														<td>4</td>
														<td class="state on">답변 완료</td><!-- 완료시 on -->
														<td class="alignL"><a href="#" class="ellipsis qna_btn">바스락 거리는 소리마저도 기분 좋아요</a></td>
														<td>천하태평 김태평</td>
														<td class="date">2019-03-04 17:48</td>
														<td>121</td>
													</tr>
													<!--  답변 -->
													<tr class="qna_reply">
														<td colspan="6">
															<p>안녕하세요 고객님,  담당 제품 MD 입니다..</p>
															<p>현 제품은 알레르기 방지 기능이 있으며</p>
															<p>자세한 사항은 제품설명, 상세정보 확인 부탁드립니다. 감사합니다.</p>
														</td>
													</tr>
													<!-- //답변 -->
													<tr>
														<td>3</td>
														<td class="state on">답변 완료</td><!-- 완료시 on -->
														<td class="alignL"><a href="#" class="ellipsis qna_btn">바스락 거리는 소리마저도 기분 좋아요바스락 거리는 소리마저도 기분 좋아요바스락 거리는 소리마저도 기분 좋아요</a></td>
														<td>천하태평 김태평</td>
														<td class="date">2019-03-04 17:48</td>
														<td>121</td>
													</tr>
													<!--  답변 -->
													<tr class="qna_reply">
														<td colspan="6">
															<p>안녕하세요 고객님,  담당 제품 MD 입니다..</p>
															<p>현 제품은 알레르기 방지 기능이 있으며</p>
															<p>자세한 사항은 제품설명, 상세정보 확인 부탁드립니다. 감사합니다.</p>
														</td>
													</tr>
													<!-- //답변 -->
												</tbody>
											</table>
										</div>
										<div class="paging">
											<a href="#" class="prev"></a>
											<span class="num">
												<a href="#">1</a>
												<a href="#">2</a>
												<a href="#">3</a>
												<a href="#">4</a>
												<a href="#" class="current">5</a>
												<a href="#">6</a>
												<a href="#">7</a>
												<a href="#">8</a>
												<a href="#">9</a>
												<a href="#">10</a>
											</span>
											<a href="#" class="next"></a>
											<button type="button" class="btn big green"><span>문의하기</span></button>
										</div>
									</div>
									<!-- tab2 -->
									<div class="tab_inner">

									</div>
									<!-- tab3 -->
									<div class="tab_inner">

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

					
			</div>
		</div>			
	</form>
	
<script>
// 상품보관
function item_wish(f, it_id)
{
    f.url.value = "<?php echo G5_SHOP_URL; ?>/wishupdate.php?it_id="+it_id;
    f.action = "<?php echo G5_SHOP_URL; ?>/wishupdate.php";
    f.target = "_blank";
    f.submit();
}

function fsubmit_check(f)
{
    // 판매가격이 0 보다 작다면
    if (document.getElementById("it_price").value < 0) {
        alert("전화로 문의해 주시면 감사하겠습니다.");
        return false;
    }

    if($(".sit_opt_list").size() < 1) {
        alert("상품의 선택옵션을 선택해 주십시오.");
        return false;
    }

    var val, io_type, result = true;
    var sum_qty = 0;
    var min_qty = parseInt(<?php echo $it['it_buy_min_qty']; ?>);
    var max_qty = parseInt(<?php echo $it['it_buy_max_qty']; ?>);
    var $el_type = $("input[name^=io_type]");

    $("input[name^=ct_qty]").each(function(index) {
        val = $(this).val();

        if(val.length < 1) {
            alert("수량을 입력해 주십시오.");
            result = false;
            return false;
        }

        if(val.replace(/[0-9]/g, "").length > 0) {
            alert("수량은 숫자로 입력해 주십시오.");
            result = false;
            return false;
        }

        if(parseInt(val.replace(/[^0-9]/g, "")) < 1) {
            alert("수량은 1이상 입력해 주십시오.");
            result = false;
            return false;
        }

        io_type = $el_type.eq(index).val();
        if(io_type == "0")
            sum_qty += parseInt(val);
    });

    if(!result) {
        return false;
    }

    if(min_qty > 0 && sum_qty < min_qty) {
        alert("선택옵션 개수 총합 "+number_format(String(min_qty))+"개 이상 주문해 주십시오.");
        return false;
    }

    if(max_qty > 0 && sum_qty > max_qty) {
        alert("선택옵션 개수 총합 "+number_format(String(max_qty))+"개 이하로 주문해 주십시오.");
        return false;
    }

    return true;
}

// 바로구매, 장바구니 폼 전송
function fitem_submit(f)
{
    f.action = "<?php echo $action_url; ?>";
    f.target = "";

    if (document.pressed == "장바구니") {
        f.sw_direct.value = 0;
    } else { // 바로구매
        f.sw_direct.value = 1;
    }

    // 판매가격이 0 보다 작다면
    if (document.getElementById("it_price").value < 0) {
        alert("전화로 문의해 주시면 감사하겠습니다.");
        return false;
    }

    if($(".sit_opt_list").size() < 1) {
        alert("상품의 선택옵션을 선택해 주십시오.");
        return false;
    }

    var val, io_type, result = true;
    var sum_qty = 0;
    var min_qty = parseInt(<?php echo $it['it_buy_min_qty']; ?>);
    var max_qty = parseInt(<?php echo $it['it_buy_max_qty']; ?>);
    var $el_type = $("input[name^=io_type]");

    $("input[name^=ct_qty]").each(function(index) {
        val = $(this).val();

        if(val.length < 1) {
            alert("수량을 입력해 주십시오.");
            result = false;
            return false;
        }

        if(val.replace(/[0-9]/g, "").length > 0) {
            alert("수량은 숫자로 입력해 주십시오.");
            result = false;
            return false;
        }

        if(parseInt(val.replace(/[^0-9]/g, "")) < 1) {
            alert("수량은 1이상 입력해 주십시오.");
            result = false;
            return false;
        }

        io_type = $el_type.eq(index).val();
        if(io_type == "0")
            sum_qty += parseInt(val);
    });

    if(!result) {
        return false;
    }

    if(min_qty > 0 && sum_qty < min_qty) {
        alert("선택옵션 개수 총합 "+number_format(String(min_qty))+"개 이상 주문해 주십시오.");
        return false;
    }

    if(max_qty > 0 && sum_qty > max_qty) {
        alert("선택옵션 개수 총합 "+number_format(String(max_qty))+"개 이하로 주문해 주십시오.");
        return false;
    }

    return true;
}
</script>

<?php /* 2017 리뉴얼한 테마 적용 스크립트입니다. 기존 스크립트를 오버라이드 합니다. */ ?>
<script src="<?php echo G5_JS_URL; ?>/shop.override.js"></script>