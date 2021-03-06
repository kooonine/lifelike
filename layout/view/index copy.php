<?php
ob_start();
?>
<link rel="stylesheet" href="/css/renewal2107.css">
<link rel="stylesheet" href="/css/renewal2107_reset.css">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400&display=swap" rel="stylesheet">
<style>
    .btn.btn-more {
        width: 140px;
        margin-top: 26px;
        border-color: var(--black-two);
    }
</style>
<!-- 메인 롤링 베너 -->
<!-- ㅋㅋㅋㅋㅋㅋㅋㅋㅋ -->
<?php if (!empty($g5_banner_new['MAIN'])) : ?>
  <div id="main_swiper-container" class="swiper-container">
      <div id="main-banner-wrapper" class="swiper-wrapper">
        <? foreach ($g5_banner_new['MAIN'] as $bmain) : ?>
            <div class="swiper-slide" onclick="location.href='<?= $bmain['cp_link'] ?>'" style="cursor:pointer;">
                <img src="http://dev.lifelike.co.kr/data/banner/<?= $bmain['cp_image_1'] ?>" alt="">
                <!-- <div class="text-box">
                    <div class="title" data-swiper-parallax="-200"><?= $bmain['ba_subject'] ?> 새학기 맞이<br>심플스타일 침구</div>
                    <div class="text" data-swiper-parallax="-100">MD추천 상품</div>
                </div> -->
            </div>
        <? endforeach ?>
        <!-- <div class="swiper-slide">
            <img src="/img/renewal2107/main/main_visual1.jpg" alt="">
            <div class="text-box">
                <div class="title" data-swiper-parallax="-200">새학기 맞이<br>심플스타일 침구</div>
                <div class="text" data-swiper-parallax="-100">MD추천 상품</div>
            </div>
        </div>
        <div class="swiper-slide">
            <img src="/img/renewal2107/main/main_visual1.jpg" alt="">
            <div class="text-box">
                <div class="title" data-swiper-parallax="-200">새학기 맞이<br>심플스타일 침구</div>
                <div class="text" data-swiper-parallax="-100">MD추천 상품</div>
            </div>
        </div>
        <div class="swiper-slide">
            <img src="/img/renewal2107/main/main_visual1.jpg" alt="">
            <div class="text-box">
                <div class="title" data-swiper-parallax="-200">새학기 맞이<br>심플스타일 침구</div>
                <div class="text" data-swiper-parallax="-100">MD추천 상품</div>
            </div>
        </div>
        <div class="swiper-slide">
            <img src="/img/renewal2107/main/main_visual1.jpg" alt="">
            <div class="text-box">
                <div class="title" data-swiper-parallax="-200">새학기 맞이<br>심플스타일 침구</div>
                <div class="text" data-swiper-parallax="-100">MD추천 상품</div>
            </div>
        </div> -->
      </div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
      <!-- <div class="swiper-pagination"></div> -->
    </div>

    <script>
        var swiper_main = new Swiper('#main_swiper-container', {
            slidesPerView: 3,
            spaceBetween: 20,
            loop: true,
            centeredSlides: true,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints : {
                1366 : {
                    slidesPerView : 1,
                },
            },
        });
    </script>
<?php endif ?>

<!-- 퀵 베너 모바일-->
<div class="on-small cagegory_menu">
    <ul>
        <li>
            <a href=""><img src="/img/renewal2107/mo_main/main_cate1.jpg" alt=""></a>
            <p class="tit">이불</p>
        </li>
        <li>
            <a href=""><img src="/img/renewal2107/mo_main/main_cate2.jpg" alt=""></a>
            <p class="tit">베개</p>
        </li>
        <li>
            <a href=""><img src="/img/renewal2107/mo_main/main_cate3.jpg" alt=""></a>
            <p class="tit">커버/패드</p>
        </li>
        <li>
            <a href=""><img src="/img/renewal2107/mo_main/main_cate4.jpg" alt=""></a>
            <p class="tit">키즈</p>
        </li>
        <li>
            <a href=""><img src="/img/renewal2107/mo_main/main_cate5.jpg" alt=""></a>
            <p class="tit">수입침구</p>
        </li>
        <li>
            <a href=""><img src="/img/renewal2107/mo_main/main_cate6.jpg" alt=""></a>
            <p class="tit">홈데코</p>
        </li>
        <li>
            <a href=""><img src="/img/renewal2107/mo_main/main_cate7.jpg" alt=""></a>
            <p class="tit">구스이불</p>
        </li>
        <li>
            <a href=""><img src="/img/renewal2107/mo_main/main_cate8.jpg" alt=""></a>
            <p class="tit">침구세트</p>
        </li>
        <li>
            <a href=""><img src="/img/renewal2107/mo_main/main_cate9.jpg" alt=""></a>
            <p class="tit">웨딩패키지</p>
        </li>
        <li>
            <a href=""><img src="/img/renewal2107/mo_main/main_cate10.jpg" alt=""></a>
            <p class="tit">토퍼</p>
        </li>
    </ul>
</div>

<!-- 중간 배너 -->
<div class="banner-coupon-wrap">
    <a href=""><img src="/img/renewal2107/mo_main/main_coupon_banner.jpg" alt=""></a>
</div>

<!-- 인기 키워드 koo success -->
<div class="best-keyword-wrap">
    <h1>인기 키워드</h1>
    <ul class="key-words">
        <?php 
            $keyword = " SELECT pk_keyword FROM lt_popularity_keyword LIMIT 1 ";
            $keySql = sql_fetch($keyword);
            
            $keywordArr = explode( ',', $keySql['pk_keyword'] );
        
            for ($i = 0; $i < count($keywordArr); $i++) {
                ?>
                <li><a href="/search.php?skeyword=<?= $keywordArr[$i];?>"><span><?= $keywordArr[$i]; ?></span></a></li>
            <?php } ?>
    </ul>
</div>

<!-- 브랜드 -->
<?php if ($db_brand->num_rows > 0) : ?>
    <div id="campaign-brand-wrapper">
        <div class="title-tab-wrap">
            <h1>브랜드</h1><span class="subtit">다양한 브랜드를 만나보세요</span>
            <ul class="PC_tabs">
                <? foreach ($g5_banner_new['BRAND'] as $bi => $brand) : ?>
                    <li class="tab-link <?if($bi == 0) : ?> current <?endif?>" data-tab="PC_tab-<?= ($bi+1)?>"><span><?=$brand['cp_subject']?></span></li>
                <? endforeach ?>
            </ul>
        </div>
        <? foreach ($g5_banner_new['BRAND'] as $bi => $brand) : ?>
        <div id="PC_tab-<?= $bi+1 ?>" class="tab-content <?if($bi == 0) : ?> current <?endif?>">
            <div class="main_brand_img" style="background-image: url(/data/banner/<?= $brand['cp_image_1'] ?>); center center no-repeat;">
                <a href="<?= !empty($brand['cp_link']) ? $brand['cp_link'] : "/shop/brand.php?br_id=" . $brand['br_id'] ?>">
                </a>
                <span class="hartpick"></span>
            </div>

            <div class="main_front_item_area">
                <ul class="noswiper_li_list">
                <?php
                    $cp_item_set = json_decode($brand['cp_item_set'], true);
                    $cp_item_set_item = array();
                    $cp_item_set_category = array();
                    foreach ($cp_item_set as $cis) {
                        if (!empty($cis['item'])) $cp_item_set_item[] = $cis['item'];
                        if (!empty($cis['category'])) $cp_item_set_category[] = $cis['category'];
                    }
                    $sql_brand_item = "SELECT B.io_hoching , A.* FROM {$g5['g5_shop_item_table']} AS A LEFT JOIN lt_shop_item_option AS B ON (A.it_id = B.it_id) WHERE A.it_use=1 AND B.io_use= 1 AND ";
                    $sql_brand_where = array();
                    if (!empty($cp_item_set_item)) $sql_brand_where[] = " A.it_id IN(" . implode(',', $cp_item_set_item) . ")";
                    if (!empty($cp_item_set_category)) {
                        $cp_item_set_category = implode(',', $cp_item_set_category);
                        foreach (explode(',', $cp_item_set_category) as $cp_ca_id) {
                            $sql_brand_where[] = "ca_id LIKE '{$cp_ca_id}%'";
                        }
                    }

                    if (empty($sql_brand_where)) die("CAMPAIGN ITEM NOT SET");
                    $sql_brand_item = $sql_brand_item . implode(' OR ', $sql_brand_where) . " ORDER BY RAND() LIMIT 4";
                    $db_brand_item = sql_query($sql_brand_item);

                    while (($citem = sql_fetch_array($db_brand_item)) != false) {
                        $brand_thumb = get_it_thumbnail_path($citem['it_img1'], 600, 600);
                        // $bit_thumb = get_it_thumbnail_path($bit['it_img1'], 250, 250);
                        if ($citem['it_discount_price'] != '' && $citem['it_discount_price'] != '0') {
                            $it_price = $citem['it_price'];
                            $it_sale_price = $citem['it_discount_price'];
                            $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                        }
                    ?>
                        <li>
                            <a href = "/shop/item.php?it_id=<?= $citem['it_id'] ?>">
                                <? if ($citem['it_soldout'] == 1) : ?><div class=" soldout_thumb">일시 품절</div><? endif ?>
                                <div class="swiper_item_img" data-id=<?= $citem['it_id'] ?> style="background-image: url(<?= $brand_thumb ?>);background-size: cover;"></div>
                                <span class="btn-pick-heart <?= in_array($citem['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $citem['it_id'] ?>></span>
                                <div class="swiper_item_detail">
                                    <div class="swiper_item_brand"><?= $citem['it_brand'] ?> <img src="/img/re/size_lable/<?= replace_hoching($citem['io_hoching']) ?>.png" srcset="/img/re/size_lable/<?= replace_hoching($citem['io_hoching']) ?>@2x.png 2x,/img/re/size_lable/<?= replace_hoching($citem['io_hoching']) ?>@3x.png 3x"></div>
                                    <div class="swiper_item_name" style="width:340px;"><?= $citem['it_name'] ?></div>
                                    <div class="swiper_item_price_area">
                                    <? if ($discount_ratio > 0) : ?>
                                        <p class="price-del"><del><?= number_format($it_price + $it_sale_price) ?></del>원<p>
                                    <? endif ?>
                                        <span class="price-dis" style="color: #e65026;"><?= number_format($discount_ratio) ?>%</span>
                                        <span><?= display_price(get_price($citem), $citem['it_tel_inq']); ?><span style="font-size: 12px;">원</span>
                                    </div>
                                    <?php
                                    $it_view_list_items = ','.$citem['it_view_list_items'].',';
                                    $view_sale = (preg_match("/,할인블릿,/i", $it_view_list_items));
                                    ?>
                                    <div class="swiper_item_sale">
                                        <img src="/img/re/sale.png" style="opacity : <?= $view_sale ?>" srcset="/img/re/sale@2x.png 2x,/img/re/sale@3x.png 3x">
                                    </div>
                                </div>
                            </a>
                        </li>
                    <? } ?>
                </ul>
            </div>
        </div>
        <? endforeach ?>
    </div>
<?php endif ?>

<!-- SIGNATURE PRODUCT -->
<!-- <div id="signature-prod-wrapper">
    <div class="title-tab-wrap">
        <h1>SIGNATURE PRODUCT</h1><span class="subtit">다양한 시그니처 구스를 만나보세요</span>
    </div>

    <div id="signature_swiper-container" class="swiper-container">
		<div id="main-banner-wrapper" class="swiper-wrapper">
            <div class="swiper-slide">
                <img class="img-pc" src="/img/renewal2107/main/signature_bn.jpg" alt="">
                <img class="img-mo" src="/img/renewal2107/mo_main/sig_bn.jpg" alt="">
                <a href=""><p class="btn-more">+ View Collection 1</p></a>
            </div>
            <div class="swiper-slide">
                <img class="img-pc" src="/img/renewal2107/main/signature_bn.jpg" alt="">
                <img class="img-mo" src="/img/renewal2107/mo_main/sig_bn.jpg" alt="">
                <a href=""><p class="btn-more">+ View Collection 2</p></a>
            </div>
            <div class="swiper-slide">
                <img class="img-pc" src="/img/renewal2107/main/signature_bn.jpg" alt="">
                <img class="img-mo" src="/img/renewal2107/mo_main/sig_bn.jpg" alt="">
                <a href=""><p class="btn-more">+ View Collection 3</p></a>
            </div>
            <div class="swiper-slide">
                <img class="img-pc" src="/img/renewal2107/main/signature_bn.jpg" alt="">
                <img class="img-mo" src="/img/renewal2107/mo_main/sig_bn.jpg" alt="">
                <a href=""><p class="btn-more">+ View Collection 4</p></a>
            </div>
        </div>
        <div class="swiper-pagination"></div>
	</div>
    <script>
        var signature_swiper = new Swiper('#signature_swiper-container', {
            slidesPerView: 1,
            loop: true,
            centeredSlides: true,
            effect: "fade",
            autoHeight: true,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });

        var nameArr = ['SIGNATURE', 'PREMIUM' , 'GOLD', 'COMFORT'];
        for(i= 0; i < 4; i++){
            $('.swiper-container-horizontal>.swiper-pagination-bullets .swiper-pagination-bullet:nth-child(' + (i+1) + ')').html(nameArr[i]);   
        }
    </script>
</div> -->

<!-- 리뷰 -->
<?php 
    $review = " SELECT a.*, b.bf_file FROM lt_shop_item_use AS a LEFT JOIN lt_shop_item_use_file AS b ON (a.is_id = b.is_id)  WHERE a.is_best = '1' AND b.bf_no = '0' ORDER BY rand() LIMIT 16 ";
    $reviewSql = sql_query($review);
?>
<div id="review-wrapper">
    <div class="title-tab-wrap">
        <h1>리뷰</h1><span class="subtit">고객님의 소중한 리뷰를 만나보세요</span>
    </div>
    <div id="review-swiper-container" class="swiper-container">
      <div id="main-banner-wrapper" class="swiper-wrapper">
        <?php for ($i=0; $row=sql_fetch_array($reviewSql); $i++) { ?>
            <div class="swiper-slide"><img style="width: 100%; height: 100%; object-fit:cover;" src="http://localhost/data/file/itemuse/<?= $row['bf_file']?>" alt=""></div>
        <?php } ?>

      </div>
      <div class="swiper-pagination"></div>
    </div>

    <script>
        var review_swiper = new Swiper('#review-swiper-container', {
            slidesPerView: 5,
            slidesPerColumn: 2,
            spaceBetween: 20,
            pagination: {
				el: ".swiper-pagination",
				clickable: true,
            },
			breakpoints : {
                1366 : {
                    slidesPerView: 3,
					slidesPerColumn: 3,
					spaceBetween: 10,
					clickable: true,
					autoHeight: true,
                },
            },
        });      
    </script>
</div>


<!-- home by tempur -->
<?php 
    $itemTemper = " SELECT * FROM lt_temper WHERE tp_type = 0 LIMIT 1 ";
    $imgTemper = " SELECT * FROM lt_temper WHERE tp_type =1 AND tp_use = 1 ORDER BY tp_num ASC";
    $itemTemperSql = sql_fetch($itemTemper);
    $imgTemperSql = sql_query($imgTemper);
?>
<div id="tempur-wrapper">
    <div class="title-tab-wrap"><h1>Home by TEMPUR</h1><span class="subtit">가장 완벽한 숙면을 선물합니다</span></div>
	<div class="tempur-swiper-cont">
		<div id="tempur-swiper-container" class="swiper-container">
			<div id="main-banner-wrapper" class="swiper-wrapper">
                <?php 
                    for ($i=0; $row=sql_fetch_array($imgTemperSql); $i++) { ?>
                        <div class="swiper-slide"><img src="<?= $row['tp_img']?>" alt=""></div>
                <?php } ?>
			</div>
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>
		</div>
		<div thumbsSlider="" id="tempur-swiper-thumb" class="swiper-container">
			<div class="swiper-wrapper">
                <?php 
                    $itemTemperArr = explode( ',', $itemTemperSql['tp_item'] );
                    for ($j = 0; $j < count($itemTemperArr); $j++) { 
                        $itId=$itemTemperArr[$j];
                        $itemInfo = sql_fetch(" SELECT B.io_hoching , A.* FROM {$g5['g5_shop_item_table']} AS A LEFT JOIN lt_shop_item_option AS B ON (A.it_id = B.it_id) WHERE A.it_id ='$itId' AND A.it_use=1 AND B.io_use= 1 AND B.io_stock_qty > 0 LIMIT 1 ");
                       
                        if ($itemInfo['it_discount_price'] != '' && $itemInfo['it_discount_price'] != '0') {
                            $it_price = $itemInfo['it_price'];
                            $it_sale_price = $itemInfo['it_discount_price'];
                            $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                        }
                ?>
                    <div class="swiper-slide">
                        <a href = "/shop/item.php?it_id=<?= $itemInfo['it_id'] ?>">
				    	    <img src="https://lifelike.co.kr/data/item/<?= $itemInfo['it_img1'] ?>" alt="">
				    	    <div class="item-info">
				    	    	<p class="brand"><?= $itemInfo['it_brand'] ?></p>
				    	    	<span class="name<?= $itemInfo['io_hoching']?>" style = "width:100px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:inline-block;"><?= $itemInfo['it_name'] ?>aaaaaaaaafffffff</span>
				    	    	<p class="ori-prc"><del><?= number_format($it_price + $it_sale_price) ?></del></p>
				    	    	<span class="disc"><?= number_format($discount_ratio) ?>%</span>
				    	    	<span class="sale-prc"><?= display_price(get_price($itemInfo), $itemInfo['it_tel_inq']); ?>원</span>
                            </div>
                        </a>
				    </div>
                <?php } ?>
                
			</div>
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>
		</div>
	</div>
</div>
<script>
	var swiper_tmp_thumb = new Swiper("#tempur-swiper-thumb", {
		slidesPerView: 3,
		direction: "vertical",
		navigation: {
			nextEl: ".swiper-button-next",
			prevEl: ".swiper-button-prev",
		},
		breakpoints : {
			1366 : {
				direction: "horizontal",
				slidesPerView: 3,
				spaceBetween: 10,
				clickable: true,
				autoHeight: true,
			},
		},
	});

	var swiper_tmp = new Swiper('#tempur-swiper-container', {
		slidesPerView: 1,
		autoHeight: true,
		navigation: {
			nextEl: ".swiper-button-next",
			prevEl: ".swiper-button-prev",
		},
		thumbs: {
			swiper: swiper_tmp_thumb,
		},
	});      
</script>

<!-- 라이프라이크 TV koo success -->
<?php 
    $tv = " SELECT * FROM lt_lifeliketv WHERE tv_use = 1 ORDER BY tv_num ASC;";
    $tvSql = sql_query($tv);
    $tvSql2 = sql_query($tv);
?>
<div id="lifeliketv-wrapper">
    <div class="title-tab-wrap"><h1>라이프라이크 TV</h1><span class="subtit">라이프라이크의 핫한 영상을 만나보세요</span></div>
	<div class="ecw_yvtg">
		<div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper-container lifeliketv-swiper">
			<div class="swiper-wrapper">
                <?php 
                    for ($i=0; $row=sql_fetch_array($tvSql); $i++) { ?>
                        <div class="swiper-slide">
					        <div class="youtube-wrap">
						        <iframe src="https://youtube.com/embed/<?= $row['tv_url'];?>?enablejsapi=1&version=3&playerapiid=player" fframeborder="0" allowfullscreen="" id="player"></iframe>
				        	</div>
				        </div>
                    <?php }
                ?>
			</div>
		</div>
		<div thumbsSlider="" class="swiper-container thumb-lifeliketv-swiper">
			<div class="swiper-wrapper">
                <?php 
                    for ($j=0; $row2=sql_fetch_array($tvSql2); $j++) { ?>
                        <div class="swiper-slide"><img src="https://img.youtube.com/vi/<?= $row2['tv_url'];?>/0.jpg" style="width: 100%; height: 100%; object-fit:cover;"></div>
                    <?php }
                ?>
                <!-- <div class="swiper-slide"><img src="/img/renewal2107/main/tv_thumb.jpg"></div> -->
			</div>
		</div>
	</div>
	<script>
		function stop() {
			$('iframe').each(function (i) {
				this.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
			});
		}

		// thumb swiper
		var tv_swiper = new Swiper(".thumb-lifeliketv-swiper", {
			slidesPerView: 3,
            slidesPerColumn: 2,
            spaceBetween: 20,
			on: {
				click: function () {
					stop();
				}
			},
            breakpoints : {
			    1366 : {
				    spaceBetween: 10,
			},
		},
		});
		var tv_swiper2 = new Swiper(".lifeliketv-swiper", {
			slidesPerView: "auto",
			effect: "fade",
			loop: true,
			centeredSlides: true,
			thumbs: {
				swiper: tv_swiper
			},
		});

		//
	</script>
</div>

<div id="today-specialprice-wrapper">
	<div class="title-tab-wrap">
		<h1>오늘의 특가</h1><div id="countdown_area" class="countdown_area" ></div>
		<div class="special_more" onclick="location.href='/special/view.php'">전체보기></div>
	</div>
    
    <div class="main_front_item_area swiper-container" id="main_front_sp_swiper">
        <ul class="swiper-wrapper" >
        <?php 
        $sql_common = "SELECT * FROM lt_special WHERE cp_use = 1 ORDER BY cp_create_date DESC LIMIT 1";
        $db_special = sql_fetch($sql_common);

        echo '<input type="hidden" name ="cp_end_date" id = "special_end" value = "'.$db_special['cp_end_date'].'" >';
        
        $sp_item_set = json_decode($db_special['cp_item_set'], true);
        $sp_item_set_item = array();
        $sp_item_set_category = array();
        foreach ($sp_item_set as $spc) {
            if (!empty($spc['item'])) $sp_item_set_item[] = $spc['item'];
            if (!empty($spc['category'])) $sp_item_set_category[] = $spc['category'];
        }
        
        $sql_sp_item = "SELECT B.io_hoching , A.* FROM {$g5['g5_shop_item_table']} AS A LEFT JOIN lt_shop_item_option AS B ON (A.it_id = B.it_id) WHERE A.it_use=1 AND B.io_use= 1 AND B.io_stock_qty > 0 AND ";
        $sql_sp_where = array();
        if (!empty($sp_item_set_item)) $sql_sp_where[] = "A.it_id IN(" . implode(',', $sp_item_set_item) . ")";

        $sql_sp_item = $sql_sp_item . implode(' OR ', $sql_sp_where) . " LIMIT 20";
        $db_sp_item = sql_query($sql_sp_item);

            while (($spitem = sql_fetch_array($db_sp_item)) != false) {
                $thumb = get_it_thumbnail_path($spitem['it_img1'], 600, 600);
                if ($spitem['it_discount_price'] != '' && $spitem['it_discount_price'] != '0') {
                    $it_price = $spitem['it_price'];
                    $it_sale_price = $spitem['it_discount_price'];
                    $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                }
        ?>
            <li class="swiper-slide">
                <a href = "/shop/item.php?it_id=<?= $spitem['it_id'] ?>">
                    <? if ($spitem['it_soldout'] == 1) : ?><div class=" soldout_thumb">일시 품절</div><? endif ?>
                    <div class="swiper_item_img" data-id=<?= $spitem['it_id'] ?> style="background-image: url(<?= $thumb ?>); background-size: cover;"></div>
                    <span class="btn-pick-heart <?= in_array($spitem['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $spitem['it_id'] ?>></span>
                    <div class="swiper_item_detail">
                        <div class="swiper_item_brand"><?= $spitem['it_brand'] ?> <img src="/img/re/size_lable/<?= replace_hoching($spitem['io_hoching']) ?>.png" srcset="/img/re/size_lable/<?= replace_hoching($spitem['io_hoching']) ?>@2x.png 2x,/img/re/size_lable/<?= replace_hoching($spitem['io_hoching']) ?>@3x.png 3x"></div>
                        <div class="swiper_item_name"><?= $spitem['it_name'] ?></div>
                        <div class="swiper_item_price_area">
                            <span><?= display_price(get_price($spitem), $spitem['it_tel_inq']); ?><span style="font-size: 12px;">원</span>
                            <? if ($discount_ratio > 0) : ?>
                                <span class="price-del"><del><?= number_format($it_price + $it_sale_price) ?></del>원<span>
                                <span class="price-dis" style="color: #e65026;"><?= number_format($discount_ratio) ?>%</span>
                            <? endif ?>
                        </div>
                        <?php
                        $it_view_list_items = ','.$spitem['it_view_list_items'].',';
                        $view_sale = (preg_match("/,할인블릿,/i", $it_view_list_items));
                        ?>
                        <div class="swiper_item_sale">
                            <img src="/img/re/sale.png" style="opacity : <?= $view_sale ?>" srcset="/img/re/sale@2x.png 2x,/img/re/sale@3x.png 3x">
                        </div>
                    </div>
                </a>
            </li>     
        <? }?> 
        </ul>
        <div class="sp_swiper-pagination swiper-pagination swiper-pagination-black"></div>
        <div class="swiper-button-next swiper-button-black"></div>
        <div class="swiper-button-prev swiper-button-black"></div>               
    </div>

    <script>
        var swiper_sp = new Swiper('#main_front_sp_swiper', {
            slidesPerView: 4,
            slidesPerColumn: 2,
            spaceBetween: 20,
            pagination: {
                el: '.sp_swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints : {
			    1366 : {
                    slidesPerView: 2,
                    slidesPerColumn: 2,
                    spaceBetween: 10,
			    },
            },
        });
        

        function count_down_start (date){
            // Set the date we're counting down to
            var end_date = $('#special_end').val().replace(/-/g,"/");
            //var countDownDate = new Date($('#special_end').val()).getTime();
            var countDownDate = new Date(end_date).getTime();
            // Update the count down every 1 second
            var x = setInterval(function() {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            var string = "D-" + days +"    " + '<span class="countdown_img"></span>  ' + pad(hours,2) + ":" + pad(minutes,2) + ":" + pad(seconds,2) + " ";
            //var string = String(aaa);

            // Display the result in the element with id="demo"
            // document.getElementById("countdown_area").innerHTML = "D-" + days +"    " + '<span class="count_down_img"></span>  ' + pad(hours,2) + ":"
            // + pad(minutes,2) + ":" + pad(seconds,2) + "";

                $('.countdown_area').empty().html(string);
            // document.getElementById("count_down_area_mo").innerHTML = "D-" + days +"    " + '<span class="count_down_img"></span>  ' + pad(hours,2) + ":"
            // + pad(minutes,2) + ":" + pad(seconds,2) + "";

            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                // document.getElementById("count_down_area").innerHTML = "THE END";
                // document.getElementById("count_down_area_mo").innerHTML = "THE END";
                $('.countdown_area').empty().html('THE END');
            }
            }, 1000);
        }

        function pad(n, width) {
            n = n + '';
            return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n;
        }
    </script>
</div>

<!-- 소량재고 특가 -->
<div id="oos-wrapper">
	<div class="soldout_goods_area">
		<div class="soldout_goods">소량재고 품절임박 상품</div>
		<div class="soldout_gijun"><?=$nowdate = date('H:i')?> 기준</div>
	</div>
	<div class="main_front_item_area swiper-container" id="main_front_sd_swiper">
		<ul class="swiper-wrapper">
			<?php $sql_sd_item = "SELECT DISTINCT  b.io_hoching ,b.io_stock_qty,a.* FROM lt_shop_item a, lt_shop_item_option b WHERE a.it_id = b.it_id AND a.it_use = 1 AND b.io_use =1 AND (b.io_stock_qty < 11 AND b.io_stock_qty > 0 )AND a.it_soldout = 0 ORDER BY RAND()";
			$sql_sd_where = array();
			$sql_sd_item = $sql_sd_item . " LIMIT 20";
			$db_sd_item = sql_query($sql_sd_item);
			while (($sditem = sql_fetch_array($db_sd_item)) != false) {
				$thumb = get_it_thumbnail_path($sditem['it_img1'], 600, 600);
				if ($sditem['it_discount_price'] != '' && $sditem['it_discount_price'] != '0') {
					$it_price = $sditem['it_price'];
					$it_sale_price = $sditem['it_discount_price'];
					$discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
				}
				?>
				<li class="swiper-slide">
					<a href = "/shop/item.php?it_id=<?= $sditem['it_id'] ?>">
					<? if ($sditem['it_soldout'] == 1) : ?><div class=" soldout_thumb">일시 품절</div><? endif ?>
						<div class="swiper_item_img" data-id=<?= $sditem['it_id'] ?> style="background-image: url(<?= $thumb ?>);background-size: cover;"></div>
						<span class="btn-pick-heart <?= in_array($sditem['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $sditem['it_id'] ?>></span>
						<div class="swiper_item_detail">
							<div class="io_stock_qty">남은수량: <?= $sditem['io_stock_qty']?>개</div>
							<div class="swiper_item_brand"><?= $sditem['it_brand'] ?> <img src="/img/re/size_lable/<?= replace_hoching($sditem['io_hoching']) ?>.png" srcset="/img/re/size_lable/<?= replace_hoching($sditem['io_hoching']) ?>@2x.png 2x,/img/re/size_lable/<?= replace_hoching($sditem['io_hoching']) ?>@3x.png 3x"></div>
							<div class="swiper_item_name"><?= $sditem['it_name'] ?></div>
							<div class="swiper_item_price_area">
								<span><?= display_price(get_price($sditem), $sditem['it_tel_inq']); ?><span style="font-size: 12px;">원</span>
								<? if ($discount_ratio > 0 && $sditem['it_discount_price'] > 0) : ?>
									<span class="price-del"><del><?= number_format($it_price + $it_sale_price) ?></del>원<span>
										<span class="price-dis" style="color: #e65026;"><?= number_format($discount_ratio) ?>%</span>
										<? endif ?>
									</div>
									<?php $it_view_list_items = ','.$sditem['it_view_list_items'].',';
									$view_sale = (preg_match("/,할인블릿,/i", $it_view_list_items));
									?>
									<div class="swiper_item_sale">
										<img src="/img/re/sale.png" style="opacity : <?= $view_sale ?>" srcset="/img/re/sale@2x.png 2x,/img/re/sale@3x.png 3x">
									</div>
								</div>
							</a>
						</li>
						<? }?> 
					</ul>
					<div class="sd_swiper-pagination swiper-pagination swiper-pagination-black"></div>
					<div class="swiper-button-next swiper-button-black"></div>
					<div class="swiper-button-prev swiper-button-black"></div>               
				</div>
				<script>
					var swiper_sd = new Swiper('#main_front_sd_swiper', {
						slidesPerView: 4,
						slidesPerColumn: 2,
						spaceBetween: 20,
						pagination: {
							el: '.sd_swiper-pagination',
							clickable: true,
							},
						navigation: {
							nextEl: '.swiper-button-next',
							prevEl: '.swiper-button-prev',
						},
                        breakpoints : {
                            1366 : {
                                slidesPerView: 2,
                                slidesPerColumn: 2,
                                spaceBetween: 10,
                            },
                        },
					});
				</script>
</div>

<!-- 메모리폼  koo -->
<?php 
    $itemMemory = " SELECT * FROM lt_memoryform WHERE mf_type = 0 LIMIT 1 ";
    $imgMemory = " SELECT * FROM lt_memoryform WHERE mf_type =1 AND mf_use = 1 ORDER BY mf_num ASC";
    $itemMemorySql = sql_fetch($itemMemory);
    $imgMemorySql = sql_query($imgMemory);
?>
<div id="memoryfoam-wrapper">
    <div class="title-tab-wrap"><h1>메모리폼</h1><span class="subtit">편안한 수면의 완성, Peaceful-sleep</span></div>
	<div class="memoryfoam-swiper-cont">
		<div id="memoryfoam-swiper-container" class="swiper-container">
			<div id="main-banner-wrapper" class="swiper-wrapper">
                <?php 
                    for ($i=0; $row=sql_fetch_array($imgMemorySql); $i++) { ?>
                        <div class="swiper-slide"><img src="<?= $row['mf_img']?>" alt=""></div>
                <?php } ?>
			</div>
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>
		</div>
		<div thumbsSlider="" id="memoryfoam-swiper-thumb" class="swiper-container">
			<div class="swiper-wrapper">

                <?php 
                    $itemMemoryArr = explode( ',', $itemMemorySql['mf_item'] );
                    for ($j = 0; $j < count($itemMemoryArr); $j++) { 
                        $itId=$itemMemoryArr[$j];
                        $itemInfoMe = sql_fetch(" SELECT B.io_hoching , A.* FROM {$g5['g5_shop_item_table']} AS A LEFT JOIN lt_shop_item_option AS B ON (A.it_id = B.it_id) WHERE A.it_id ='$itId' AND A.it_use=1 AND B.io_use= 1 AND B.io_stock_qty > 0 LIMIT 1 ");
                       
                        if ($itemInfoMe['it_discount_price'] != '' && $itemInfoMe['it_discount_price'] != '0') {
                            $it_price = $itemInfoMe['it_price'];
                            $it_sale_price = $itemInfoMe['it_discount_price'];
                            $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                        }
                ?>
                    <div class="swiper-slide">
                        <a href = "/shop/item.php?it_id=<?= $itemInfoMe['it_id'] ?>">
				    	    <img src="https://lifelike.co.kr/data/item/<?= $itemInfoMe['it_img1'] ?>" alt="">
				    	    <div class="item-info">
				    	    	<p class="brand"><?= $itemInfoMe['it_brand'] ?></p>
				    	    	<span class="name<?= $itemInfoMe['io_hoching']?>"><?= $itemInfoMe['it_name'] ?></span>
				    	    	<p class="ori-prc"><del><?= number_format($it_price + $it_sale_price) ?></del></p>
				    	    	<span class="disc"><?= number_format($discount_ratio) ?>%</span>
				    	    	<span class="sale-prc"><?= display_price(get_price($itemInfoMe), $itemInfoMe['it_tel_inq']); ?>원</span>
                            </div>
                        </a>
				    </div>
                <?php } ?>

			    <div class="swiper-slide">
					<img src="/img/renewal2107/main/memory_thumb.jpg" alt="">
					<div class="item-info">
						<p class="brand">소프라움</p>
						<span class="name">기능성 A 라인업(Hard 기능)</span>
						<p class="ori-prc">610,000</p>
						<span class="disc">59%</span>
						<span class="sale-prc">130,000</span>
					</div>
				</div>
			</div>
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>
		</div>
	</div>
</div>
<script>
	var swiper_tmp_thumb = new Swiper("#memoryfoam-swiper-thumb", {
		slidesPerView: 3,
		direction: "vertical",
		navigation: {
			nextEl: ".swiper-button-next",
			prevEl: ".swiper-button-prev",
		},
		breakpoints : {
			1366 : {
				direction: "horizontal",
				slidesPerView: 3,
				spaceBetween: 10,
				clickable: true,
				autoHeight: true,
			},
		},
	});

	var swiper_tmp = new Swiper('#memoryfoam-swiper-container', {
		slidesPerView: 1,
		navigation: {
			nextEl: ".swiper-button-next",
			prevEl: ".swiper-button-prev",
		},
		thumbs: {
			swiper: swiper_tmp_thumb,
		},
	});      
</script>

<!-- 공지 배너 -->
<div id="notice-banner-wrap">
    <!-- <ul class="banner-items">
        <li class="item i1">
            <p class="tit">등급별 회원 혜택</p>
            <p class="subt">등급별 회원<br>혜택을 만나보세요</p>
            <a href=""><img src="/img/renewal2107/main/member.png"/></a>
        </li>
        <li class="item i2">
        	<p class="tit">KaKaoTalk</p>
			<p class="subt">카톡친구맺고,<br>추가할인받자</p>
			<a href=""><img src="/img/renewal2107/main/kakao.png"/></a>
		</li>
		<li class="item i3">
			<p class="tit">제품 가이드</p>
			<p class="subt">라이프라이크<br>제품가이드를 만나보세요</p>
			<a href=""><img src="/img/renewal2107/main/guide.png"/></a>
		</li>
	</ul> -->

	<div class="notibn-slider-wrapper">
		<div id="notibn-swiper-container" class="swiper-container">
			<div id="main-banner-wrapper" class="banner-items swiper-wrapper">
				<!-- <div class="swiper-slide">
					<div class="item i1">
						<p class="tit">등급별 회원 혜택</p>
						<p class="subt">등급별 회원<br>혜택을 만나보세요</p>
						<a href=""><img src="/img/renewal2107/main/member.png"/></a>
					</div>
				</div> -->
				<div class="swiper-slide">
					<div class="item i2">
						<p class="tit">KaKaoTalk</p>
						<p class="subt">카톡친구맺고,<br>추가할인받자</p>
						<a href=""><img src="/img/renewal2107/main/kakao.png"/></a>
					</div>
				</div>
				<div class="swiper-slide">
					<div class="item i3">
						<p class="tit">제품 가이드</p>
						<p class="subt">라이프라이크<br>제품가이드를 만나보세요</p>
						<a href=""><img src="/img/renewal2107/main/guide.png"/></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
	var swiper_notibn = new Swiper('#notibn-swiper-container', {
		slidesPerView: 3,
		spaceBetween: 15,
		loop: false,
		thumbs: {
			swiper: swiper_tmp_thumb,
		},
		breakpoints : {
			1366 : {
				slidesPerView: 1,
				spaceBetween: 15,
				loop: true,
				thumbs: {
					swiper: swiper_tmp_thumb,
				},
			},
		}
	});   
	</script>
</div>

<!-- 베스트 -->
<?php if (!empty($g5_banner_new['BEST'])) : ?>
    <div id="campaign-best-wrapper" class="offset-campaign-top">
		<div class="title-tab-wrap">
			<h1>베스트</h1><span class="subtit">가장 인기있는 제품을 소개합니다</span>
			<ul class="PC_tabs">
			<?php $mi = 0; foreach ($g5_menu as $di => $dm) : ?>
				<?php $gubun =  ($mi+1).'0'; ?>
				<?php if ($dm['me_code'] == 10 || $dm['me_code'] == 20 || $dm['me_code'] == 30 || $dm['me_code'] == 40) : ?>
					<li class="tab-link" data-tab="best_tab-<?= ($mi+1)?>" data-index="<?=($mi+1)?>"><span><?=$dm['me_name']?></span></li>
				<?php endif; $mi++; ?>
			<?php endforeach; ?>
			</ul>
		</div>
        <!-- <div class="item_more" onclick="location.href='/best/list.php?bs_ca=00'">전체보기></div> -->
        <?php $blid = 0; foreach ($g5_best_list as $blist) :?>
            <div id="best_tab-<?=$blid?>" class="main_front_item_area swiper-container best_tab-content <?if($blid ==0) : ?> current <?endif?>">
                <ul class="swiper-wrapper">
                <?php foreach ($g5_best_list[$blid] as $bl_items) :
                    $thumb = get_it_thumbnail_path($bl_items['it_img1'], 600, 600);
                    if ($bl_items['it_discount_price'] != '' && $bl_items['it_discount_price'] != '0') {
                        $it_price = $bl_items['it_price'];
                        $it_sale_price = $bl_items['it_discount_price'];
                        $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                    }
                    ?>
                    <li class="swiper-slide">
                        <a href = "/shop/item.php?it_id=<?= $bl_items['it_id'] ?>">
                        
                        <? if ($bl_items['it_soldout'] == 1) : ?><div class=" soldout_thumb">일시 품절</div><? endif ?>
                        
                            <div class="swiper_item_img" data-id=<?= $bl_items['it_id'] ?> style="background-image: url(<?= $thumb ?>);background-size: cover;"></div>
                            <span class="btn-pick-heart <?= in_array($bl_items['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $bl_items['it_id'] ?>></span>
                            <div class="swiper_item_detail">
                                <div class="swiper_item_brand"><?= $bl_items['it_brand'] ?> <img src="/img/re/size_lable/<?= replace_hoching($bl_items['io_hoching']) ?>.png" srcset="/img/re/size_lable/<?= replace_hoching($bl_items['io_hoching']) ?>@2x.png 2x,/img/re/size_lable/<?= replace_hoching($bl_items['io_hoching']) ?>@3x.png 3x"></div>
                                <div class="swiper_item_name"><?= $bl_items['it_name'] ?></div>
                                <div class="swiper_item_price_area">
                                    <span><?= display_price(get_price($bl_items), $bl_items['it_tel_inq']); ?><span style="font-size: 12px;">원</span>
                                    <? if ($discount_ratio > 0) : ?>
                                        <span class="price-del"><del><?= number_format($it_price + $it_sale_price) ?></del>원<span>
                                        <span class="price-dis" style="color: #e65026;"><?= number_format($discount_ratio) ?>%</span>
                                    <? endif ?>
                                </div>
                                <?php
                                $it_view_list_items = ','.$bl_items['it_view_list_items'].',';
                                $view_sale = (preg_match("/,할인블릿,/i", $it_view_list_items));
                                ?>
                                <div class="swiper_item_sale">
                                    <img src="/img/re/sale.png" style="opacity : <?= $view_sale ?>" srcset="/img/re/sale@2x.png 2x,/img/re/sale@3x.png 3x">
                                </div>
                            </div>
                        </a>
                    </li>
                    
                <? endforeach ?>
                </ul>
                <div class="best_swiper-pagination swiper-pagination swiper-pagination-black"></div>
                <div class="best_swiper-next swiper-button-next swiper-button-black"></div>
                <div class="best_swiper-prev swiper-button-prev swiper-button-black"></div> 
            </div>
        <? $blid++; endforeach ?>
        <script>
            var swiper_best = new Swiper('.best_tab-content.current', {
                slidesPerView: 4,
				slidesPerColumn: 2,
				spaceBetween: 20,
				pagination: {
					el: '.sp_swiper-pagination',
					clickable: true,
				},
				navigation: {
					nextEl: '.swiper-button-next',
					prevEl: '.swiper-button-prev',
				},
				breakpoints : {
					1366 : {
						slidesPerView: 2,
						slidesPerColumn: 2,
						spaceBetween: 10,
					},
				},
            });
        </script> 
    </div>
<?php endif ?>

<!-- 신상품 -->
<?php if (!empty($g5_banner_new['NEW'])) : ?>
    <div id="campaign-new-wrapper" class="offset-campaign-top">
		<div class="title-tab-wrap">
			<h1>신상품</h1><span class="subtit">가장 먼저 신제품을 만나보세요</span>
		</div>
        <div class="main_front_item_area swiper-container" id="main_front_new_swiper">
            <ul class="swiper-wrapper" style="margin-top: 30px;">
            <?php
                $cp_item_set = json_decode($g5_banner_new['NEW'][0]['cp_item_set'], true);
                $cp_item_set_item = array();
                $cp_item_set_category = array();
                foreach ($cp_item_set as $cis) {
                    if (!empty($cis['item'])) $cp_item_set_item[] = $cis['item'];
                    if (!empty($cis['category'])) $cp_item_set_category[] = $cis['category'];
                }

                $sql_new_item = "SELECT B.io_hoching , A.* FROM {$g5['g5_shop_item_table']} AS A LEFT JOIN lt_shop_item_option AS B ON (A.it_id = B.it_id) WHERE A.it_use=1 AND B.io_use= 1 AND ";
                
                // 신상품 수정
                $sql_new_where = array();
                if (!empty($cp_item_set_item)) $sql_new_where[] = " A.it_id IN(" . implode(',', $cp_item_set_item) . ")";
                if (!empty($cp_item_set_category)) {
                    $cp_item_set_category = implode(',', $cp_item_set_category);
                    foreach (explode(',', $cp_item_set_category) as $cp_ca_id) {
                        $sql_new_where[] = "ca_id LIKE '{$cp_ca_id}%'";
                    }
                }

                if (empty($sql_new_where)) {
                    echo ("<!-- ITEM NOT SET CAMPAIGN[NEW]-->");
                } else {
                    $sql_new_item = $sql_new_item . implode(' OR ', $sql_new_where) . "  ";
                    $db_new_item = sql_query($sql_new_item);
                    while (($citem = sql_fetch_array($db_new_item)) != false) {
                        $thumb = get_it_thumbnail_path($citem['it_img1'], 600, 600);
                        if ($citem['it_discount_price'] != '' && $citem['it_discount_price'] != '0') {
                            $it_price = $citem['it_price'];
                            $it_sale_price = $citem['it_discount_price'];
                            $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                        }
                ?>
                    <li class="swiper-slide">
                        <a href = "/shop/item.php?it_id=<?= $citem['it_id'] ?>">
                            <? if ($citem['it_soldout'] == 1) : ?><div class=" soldout_thumb">일시 품절</div><? endif ?>
                            <div class="swiper_item_img" data-id=<?= $citem['it_id'] ?> style="background-image: url(<?= $thumb ?>);background-size: cover;"></div>
                            <span class="btn-pick-heart <?= in_array($citem['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $citem['it_id'] ?>></span>
                            <div class="swiper_item_detail">
                                <div class="swiper_item_brand"><?= $citem['it_brand'] ?> <img src="/img/re/size_lable/<?= replace_hoching($citem['io_hoching']) ?>.png" srcset="/img/re/size_lable/<?= replace_hoching($citem['io_hoching']) ?>@2x.png 2x,/img/re/size_lable/<?= replace_hoching($citem['io_hoching']) ?>@3x.png 3x"></div>
                                <div class="swiper_item_name"><?= $citem['it_name'] ?></div>
                                <div class="swiper_item_price_area">
                                    <span><?= display_price(get_price($citem), $citem['it_tel_inq']); ?><span style="font-size: 12px;">원</span>
                                    <? if ($discount_ratio > 0) : ?>
                                        <span class="price-del"><del><?= number_format($it_price + $it_sale_price) ?></del>원<span>
                                        <span class="price-dis" style="color: #e65026;"><?= number_format($discount_ratio) ?>%</span>
                                    <? endif ?>
                                </div>
                                <?php
                                $it_view_list_items = ','.$citem['it_view_list_items'].',';
                                $view_sale = (preg_match("/,할인블릿,/i", $it_view_list_items));
                                ?>
                                <div class="swiper_item_sale">
                                    <img src="/img/re/sale.png" style="opacity : <?= $view_sale ?>" srcset="/img/re/sale@2x.png 2x,/img/re/sale@3x.png 3x">
                                </div>
                            </div>
                        </a>
                    </li>
                <? }
                } ?>
                </ul>
                <div class="new_swiper-pagination swiper-pagination swiper-pagination-black"></div>
                <div class="swiper-button-next swiper-button-black"></div>
                <div class="swiper-button-prev swiper-button-black"></div>       

            </div>
            <script>
                var swiper1 = new Swiper('#main_front_new_swiper', {
                    slidesPerView: 4,
                    centeredSlides: false,
                    slidesPerGroup : 4,
                    spaceBetween: 20,
                    pagination: {
                        el: '.new_swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    grabCursor: false,
                    cssMode: false,
                    loop: false,
                    keyboard: false,
					breakpoints : {
						1366 : {
							slidesPerView: 2,
							slidesPerColumn: 2,
							spaceBetween: 10,
						},
					},
                });
            </script>
    </div>
<?php endif ?>

<div class="offset-campaign-top"></div>
<script>
    $(".campaign-image-item").on("click", function(evt) {
        if ($(this).closest(".season-best-item-row").hasClass("ondrag") == false) location.href = "/shop/item.php?it_id=" + $(this).data("id");
    });

    $(document).ready(function() {
        var click_count_best = 1;
        var click_count_new = 1;
        $('.tab2_mo_item_more.best').on('click' , function(){
            $('.best.tab2_mo_item_wapper.'+click_count_best).css('display','block');
            var  item_cnt_best = $(this).data('items-count');
            click_count_best++;
            if(click_count_best >= item_cnt_best){
                $('.tab2_mo_item_more.best').css('display','none');
            }
        });
        $('.tab2_mo_item_more.new').on('click' , function(){
            $('.new.tab2_mo_item_wapper.'+click_count_new).css('display','block');
            var  item_cnt_new = $(this).data('items-count');
            click_count_new++;
            if(click_count_new >= item_cnt_new){
                $('.tab2_mo_item_more.new').css('display','none');
            }
        });

       
        // var swiper_best = new Swiper('.best_tab-content.current', {
        //     slidesPerView: 4,
        //     slidesPerGroup : 4,
        //     centeredSlides: false,
        //     spaceBetween: 20,
        //     grabCursor: false,
        //     pagination: {
        //         el: '.swiper-pagination',
        //         clickable: true,
        //     },
        //     navigation: {
        //         nextEl: '.swiper-button-next',
        //         prevEl: '.swiper-button-prev',
        //     },
        //     cssMode: false,
        //     keyboard: false,
        // });
        count_down_start();
        $('ul.tabs li').click(function(){
            var tab_id = $(this).attr('data-tab');

            $('ul.tabs li').removeClass('current');
            $('.tab-content_mo').removeClass('current');

            $(this).addClass('current');
            $("#"+tab_id).addClass('current');
        });

        $('ul.PC_tabs li').click(function(){
            var tab_id = $(this).attr('data-tab');

            $('ul.PC_tabs li').removeClass('current');
            $('.tab-content').removeClass('current');

            $(this).addClass('current');
            $("#"+tab_id).addClass('current');
        });

        // $('ul.best_tabs li').click(function(){
        //     var tab_id = $(this).attr('data-tab');
        //     var tab_index = $(this).attr('data-index');
        //     $('ul.best_tabs li').removeClass('current');
        //     $('.best_tab-content').removeClass('current');

        //     $(this).addClass('current');
        //     $("#"+tab_id).addClass('current');

        //     var swiper_best = new Swiper("#"+tab_id, {
        //         slidesPerView: 4,
		// 		slidesPerColumn: 2,
		// 		spaceBetween: 20,
        //         pagination: {
        //             el: '.best_swiper-pagination',
        //             clickable: true,
        //         },
        //         navigation: {
        //             nextEl: '.swiper-button-next',
        //             prevEl: '.swiper-button-prev',
        //         },
        //     });
        // });
        $('ul.best_tabs_mo li').click(function(){
            var tab_id = $(this).attr('data-tab');

            $('ul.best_tabs_mo li').removeClass('current');
            $('.best_tab_mo-content').removeClass('current');

            $(this).addClass('current');
            $("#"+tab_id).addClass('current');
            $('.tab2_mo_item_more.best').css('display','block');
            $('.best.tab2_mo_item_wapper:nth-child(n + 5)').css('display','none');
            click_count_best =1;
        });
       
    });
    let broswerInfo = navigator.userAgent;
    if (broswerInfo.indexOf("APP_ANDROID")>-1) {
        let getCoo = get_cookie('life_');
        if (!getCoo) {
            set_cookie('life_', '1' ,1000*60*60*24*1000);
            let tokenAnd = window.lifelike_android.getFcmToken();
            if (confirm("'라이프라이크' 에서 알림을 보내고자 합니다. \n 경고, 사운드 및 아이콘 배지가 알림에 포함될 수 있습니다. \n 설정에서 이를 구성할 수 있습니다.") == true) {
                $.ajax({ 
                    type: "POST",
                    url: "/ajax_front/ajax.app_allow.php",
                    data: { token: tokenAnd, check:1},
                    success: function(res) {
                    }
                })
            } else {
                $.ajax({ 
                    type: "POST",
                    url: "/ajax_front/ajax.app_allow.php",
                    data: { token: tokenAnd, check:0},
                    success: function(res) {
                    }
                })
            }
        }
    }
    if (broswerInfo.indexOf("APP_ANDROID")>-1 || broswerInfo.indexOf("APP_IOS")>-1) { 
        let getCoup = get_cookie('getCoup_');
        if (!getCoup) { 
            set_cookie('getCoup_', '1' ,1000*60*60*24*1000);
            $.ajax({ 
                type: "POST",
                url: "/ajax_front/ajax.app_allow.php",
                data: { check:3},
                success: function(res) {
                }
            })
        }
    }
    function getFcmToken(data) {
        let getCoo = get_cookie('life_');
        if (!getCoo) {
            set_cookie('life_', '1' ,1000*60*60*24*1000);
            $.ajax({ 
                type: "POST",
                url: "/ajax_front/ajax.app_allow.php",
                data: { token: data, check:2},
                success: function(res) {
                }
            })
        }
    }
    function getIosCheckToken(data) {
        return;
    }
</script>
<?php
$contents = ob_get_contents();
ob_end_clean();

return $contents;
?>