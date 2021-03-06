<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// 제품

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);

$subsql = " select * from lt_shop_item_sub where it_id = '{$it['it_id']}' ";
$subresult = sql_query($subsql);

$subits = array();
$subits_count = 0;
for ($i = 0; $its = sql_fetch_array($subresult); $i++) {
    $subits[] = $its;
    $subits_count++;
}

require_once(G5_LIB_PATH . '/badge.lib.php');
$badgeObj = new badge();
?>
<script>
    var header = '<div id="lnb" class="header_bar">';
    header += '<h1 class="title"><span><?= $it["ca_name"] ?></span></h1>';
    header += '<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
    header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
    header += '</div>';
    $('#header').html(header);
</script>

<?php if ($config['cf_kakao_js_apikey']) { ?>
    <script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
    <script src="<?php echo G5_JS_URL; ?>/kakaolink.js"></script>
    <script>
        // 사용할 앱의 Javascript 키를 설정해 주세요.
        Kakao.init("<?php echo $config['cf_kakao_js_apikey']; ?>");
    </script>
<?php } ?>

<form name="fitem" action="<?php echo $action_url; ?>" method="post" onsubmit="return fitem_submit(this);">
    <input type="hidden" name="it_id[]" value="<?php echo $it['it_id']; ?>">
    <input type="hidden" name="od_type" value="O">
    <input type="hidden" name="sw_direct">
    <input type="hidden" name="url">

    <!-- container -->
    <div id="container">

        <div class="content sub shop floating">
            <!-- 컨텐츠 시작 -->

            <div class="grid none img">
                <div class="photo gallery_view">
                    <div class="swiper-container gallery-top">
                        <?
                        $badgeObj->item = $it;
                        $badgeObj->makeHtml();
                        ?>
                        <div style="width: 100%; position: absolute; z-index: 1000;"><?= $badgeObj->badgeHtml; ?></div>
                        <div style="width: 100%; position: absolute; z-index: 1000; bottom: 0;"><?= $badgeObj->leaseHtml; ?></div>
                        <div class="swiper-wrapper">
                            <?php
                            $big_img_count = 0;
                            $thumbnails = array();
                            $mainimg = "";
                            for ($i = 1; $i <= 10; $i++) {
                                if (!$it['it_img' . $i])
                                    continue;

                                $img = get_it_thumbnail_path($it['it_img' . $i], 480, 480);

                                if ($img) {
                                    // 썸네일
                                    $thumb = get_it_thumbnail_path($it['it_img' . $i], 120, 120);
                                    $thumbnails[] = $thumb;
                                    if ($big_img_count == 0) $mainimg = $img;

                                    $big_img_count++;
                                    echo '<div class="swiper-slide" ><img src="' . $img . '"></div>';
                                }
                            }

                            if ($big_img_count == 0) {
                                echo '<div class="swiper-slide" ><img src="' . G5_SHOP_URL . '/img/no_image.gif"></div>';
                            }
                            ?>

                        </div>
                    </div>
                    <div class="swiper-container gallery-thumbs">
                        <div class="swiper-wrapper">
                            <?php
                            // 썸네일
                            $total_count = count($thumbnails);
                            if ($total_count > 0) {

                                foreach ($thumbnails as $val) {
                                    echo '<div class="swiper-slide" ><img src="' . $val . '"></div>';
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
            </div>

            <div class="grid">
                <div class="title_bar none">
                    <?php
                    if ($view_detail_items['view_it_sale']) echo '<span class="category round_none">SALE</span>';
                    if ($view_detail_items['view_it_event']) echo '<span class="category round_none">EVENT</span>';
                    if ($view_detail_items['view_it_hit']) echo '<span class="category round_none">HIT</span>';

                    if ($view_detail_items['view_it_name']) echo '<h2 class="g_title_01">' . stripslashes($it['it_name']) . '</h2>';
                    if ($view_detail_items['view_it_basic']) echo '<p class="sm_title ellipsis">' . stripslashes($it['it_basic']) . '</p>';

                    echo '<div class="btn_comm big">';
                    if ($view_detail_items['view_wish']) {
                        $sqlwish = " select count(*) as cnt, ifnull(sum(case when mb_id = '" . $member['mb_id'] . "' then 1 else 0 end),0) as wishis from lt_shop_wish where it_id ='" . $it['it_id'] . "' ";
                        $rowwish = sql_fetch($sqlwish);
                        echo "<a href=\"javascript:item_wish(document.fitem, '" . $it_id . "');\" >";
                        echo "<button type=\"button\" class=\"pick ico " . (($rowwish['wishis'] != '0') ? 'on' : '') . "\" it_id=\"" . $it_id . "\"><span class=\"blind\">찜</span></button>";
                        echo "</a>";
                    }

                    if ($view_detail_items['view_it_share']) {
                        //echo '<a href="'.G5_MOBILE_URL.'/common/social_share.php?share_url='.urlencode(G5_SHOP_URL.'/item.php?it_id='.$it_id).'&title='.urlencode(stripslashes($it['it_name'])).'&imgUrl='.urlencode($mainimg).'" target="_blank"><button type="button" class="shared"><span class="blind">공유</span></button></a>';
                        echo '<button type="button" class="shared" onclick="$(\'#sendsns_popup\').css(\'display\',\'\');"><span class="blind">공유</span></button>';
                    }
                    echo '</div>';
                    ?>

                </div>
                <div class="benefit_price">
                    <?php
                    if ($view_detail_items['view_it_sale_bprice'] && $it['it_discount_price'] != '' && $it['it_discount_price'] != '0') {
                        echo '<del class="price">' . number_format($it['it_discount_price'] + $it['it_price']) . '</del>';
                    }

                    if ($view_detail_items['view_it_price']) {
                        echo '<span class="price">' . display_price(get_price($it)) . '</span>';
                    }
                    ?>
                    <input type="hidden" id="it_price" value="<?php echo get_price($it); ?>">
                </div>
            </div>

            <div class="grid bg_none">
                <ul class="basic_info none">
                    <?php
                    if ($view_detail_items['view_it_sale_bprice'] && $it['it_discount_price'] != '' && $it['it_discount_price'] != '0') {
                        echo '<li><span>제품 할인</span><strong>-' . display_price($it['it_discount_price']) . '</strong></li>';
                    }
                    ?>

                    <? if ($subits[0]['its_free_laundry'] || $subits[0]['its_laundry_use'] || $subits[0]['its_laundrykeep_use'] || $subits[0]['its_repair_use']) { ?>
                        <li>
                            <span class="full <?php if ($subits_count > 1) echo "info_add" ?>">케어 서비스 정보</span>
                            <div class="full info_service">
                                <p class="info_service_title"><?php echo $subits[0]['its_item'] ?></p>
                                <ul>
                                    <?
                                    if ($subits[0]['its_free_laundry']) echo '<li class="cell">- 무료세탁권 (년 ' . $subits[0]['its_free_laundry'] . '회)</li>';
                                    //if($subits[0]['its_laundry_use']) echo '<li class="cell">- 추가 세탁시 '.number_format($subits[0]['its_laundry_price']).' 원</li>';
                                    if ($subits[0]['its_laundrykeep_use']) echo '<li class="cell">- 세탁보관 ' . number_format($subits[0]['its_laundrykeep_lprice'] + $subits[0]['its_laundrykeep_kprice']) . ' 원</li>';
                                    if ($subits[0]['its_repair_use']) {
                                        if ($subits[0]['its_repair_price']) echo '<li class="cell">- 수선 ' . number_format($subits[0]['its_repair_price']) . '</li>';
                                        else echo '<li class="cell">- 수선 후불</li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                            <?php if ($subits_count > 1) { ?>
                                <!-- 패키지 상품일때 : 케어서비스 -->
                                <div class="full info_service">
                                    <?php for ($i = 1; $i < count($subits); $i++) {
                                        $its = $subits[$i];
                                    ?>
                                        <p class="info_service_title"><?php echo $its['its_item'] ?></p>
                                        <ul>
                                            <?
                                            // TODO: 무료세탁권 정책결정 반영 - 190819 balance@panpacific.co.kr
                                            // if ($its['its_free_laundry']) echo '<li class="cell">- 무료세탁권 (연 ' . $its['its_free_laundry'] . '회)</li>';
                                            if ($its['its_free_laundry']) echo '<li class="cell">- 무료세탁권 (연간 1회)</li>';
                                            //if($its['its_laundry_use']) echo '<li class="cell">- 추가 세탁시 '.number_format($its['its_laundry_price']).' 원</li>';
                                            if ($its['its_laundrykeep_use']) echo '<li class="cell">- 세탁보관 ' . number_format($its['its_laundrykeep_lprice'] + $its['its_laundrykeep_kprice']) . ' 원</li>';
                                            if ($its['its_repair_use']) {
                                                if ($its['its_repair_price']) echo '<li class="cell">- 수선 ' . number_format($its['its_repair_price']) . '</li>';
                                                else echo '<li class="cell">- 수선 후불</li>';
                                            }
                                            ?>
                                        </ul>
                                    <?php } ?>
                                </div>
                                <!-- //패키지 상품일때 : 케어서비스 -->
                            <?php } ?>
                        </li>
                    <?php } ?>

                    <?php for ($i = 0; $i < count($subits); $i++) {
                        $its = $subits[$i];
                    ?>
                        <li>
                            <p><?php echo $its['its_item'] ?> <span class="point"><?php echo display_price($its['its_final_price']) ?></span></p>
                            <input type="hidden" name="its_final_price[]" value="<?php echo $its['its_final_price']; ?>" its_no="<?php echo $its['its_no'] ?>">
                            <ul class="info_option">
                                <li>
                                    <span id="spn_it_option_<?php echo $i ?>"><?php echo $its['its_option_subject'] ?></span>
                                    <strong>
                                        <?php
                                        echo '<button type="button" id="btn_it_option_' . $i . '" class="it_option btn_select" targetID="it_option_' . $i . '" SEQ="' . $i . '" >선택</button>';

                                        $io_sql = " select * from {$g5['g5_shop_item_option_table']} where io_type = '0' and it_id = '{$its['it_id']}' and its_no = '{$its['its_no']}' and io_use = '1' order by io_no asc ";
                                        $io_result = sql_query($io_sql);

                                        $io_select = '<select id="it_option_' . $i . '" name="sel_it_option[]" hidden its_no="' . $its['its_no'] . '" >' . PHP_EOL;
                                        $io_select .= '<option value="">선택</option>' . PHP_EOL;
                                        for ($j = 0; $io_row = sql_fetch_array($io_result); $j++) {
                                            $io_select .= '<option value="' . $io_row['io_id'] . ',' . $io_row['io_price'] . ',' . $io_row['io_stock_qty'] . '" io_price="' . $io_row['io_price'] . '" io_stock_qty="' . $io_row['io_stock_qty'] . '">' . $io_row['io_id'] . '</option>' . PHP_EOL;
                                        }
                                        $io_select .= '</select>' . PHP_EOL;

                                        echo $io_select . PHP_EOL;

                                        ?>
                                    </strong>
                                </li>
                                <?php

                                if ($its['its_supply_subject']) {
                                    $it_supply_subjects = explode(',', $its['its_supply_subject']);
                                    $supply_count = count($it_supply_subjects);

                                    for ($j = 0; $j < $supply_count; $j++) {
                                        echo '<li><span id="spn_it_supply_' . $j . '">' . $it_supply_subjects[$j] . '</span><strong>
                                                <button type="button" id="btn_it_supply_' . $j . '" class="it_supply btn_select" targetID="it_supply_' . $j . '" SEQ="' . $j . '" >선택</button>
                                            </strong>
                                            </li>';

                                        $io_sql = " select * from {$g5['g5_shop_item_option_table']} where io_type = '1' and it_id = '{$its['it_id']}' and its_no = '{$its['its_no']}' and io_use = '1' and io_id like '{$it_supply_subjects[$j]}%' order by io_no asc ";
                                        $io_result = sql_query($io_sql);

                                        $io_select = '<select id="it_supply_' . $j . '" hidden name="sel_it_supply[]" its_no="' . $its['its_no'] . '">' . PHP_EOL;
                                        $io_select .= '<option value="">선택</option>' . PHP_EOL;
                                        for ($k = 0; $io_row = sql_fetch_array($io_result); $k++) {
                                            //$io_id = str_replace($it_supply_subjects[$j], "", $io_row['io_id']);
                                            $opt_id = explode(chr(30), $io_row['io_id']);

                                            $io_select .= '<option value="' . $io_row['io_id'] . ',' . $io_row['io_price'] . ',' . $io_row['io_stock_qty'] . '" io_price="' . $io_row['io_price'] . '" io_stock_qty="' . $io_row['io_stock_qty'] . '">' . $opt_id[1] . '</option>' . PHP_EOL;
                                        }
                                        $io_select .= '</select>' . PHP_EOL;

                                        echo $io_select . PHP_EOL;
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <!-- 상품정보
				<?php
                if ($it['it_id'] && $it['it_info_value'] != '' && $it['it_info_value'] != '[]') {
                    echo '<div class="grid bg_none"><ul class="basic_info none">';

                    $article = json_decode($it['it_info_value'], true);
                    foreach ($article as $key => $value) {
                        $list = '<li>';
                        $list .= '    <span >' . $value['name'] . '</수선>';
                        $list .= '    	<strong >' . $value['value'] . '</strong>';
                        $list .= '</li>';

                        echo $list;
                    }

                    echo '</ul></div>';
                }
                ?>
				-->
            <!-- 관련제품 -->
            <?php if ($item_relation_count) { ?>
                <div class="grid">
                    <div class="title_bar">
                        <h3 class="g_title_01">관련제품</h3>
                    </div>
                    <div class="item_row_list pd_roll rolling_wrap2">
                        <div class="swiper-container">
                            <ul class="swiper-wrapper">
                                <?php
                                $sql = " select b.ca_id, b.it_id, b.it_name, b.it_basic, b.it_price, b.it_rental_price, b.it_item_type
                                   from {$g5['g5_shop_item_relation_table']} a
                                   left join {$g5['g5_shop_item_table']} b on (a.it_id2=b.it_id)
                                  where a.it_id = '$it_id'
                                  order by ir_no asc ";
                                $result = sql_query($sql);
                                for ($g = 0; $row = sql_fetch_array($result); $g++) {
                                    $it_relation_img = get_it_image($row['it_id'], 120, 120);

                                    if (is_soldout($row['it_id'])) echo '<li class="swiper-slide soldout">';
                                    else  echo '<li class="swiper-slide">';

                                    echo "<a href=\"" . G5_SHOP_URL . "/item.php?it_id={$row['it_id']}\">\n";
                                ?>
                                    <div class="photo"><?php echo $it_relation_img ?></div>
                                    <div class="cont">
                                        <strong class="title bold"><?php echo stripslashes($row['it_name']); ?></strong>
                                        <p class="price divide"><?= ($row['it_item_type']) ? number_format($row['it_rental_price']) : number_format($row['it_price']) ?> 원</p>
                                    </div>
                                    </a>
                                <?php
                                }
                                ?>
                            </ul>
                        </div>
                        <script>
                            var swiperNew = new Swiper('.pd_roll .swiper-container', {
                                slidesPerView: 'auto',
                                spaceBetween: 10,
                                //loop: true,
                            });
                        </script>
                    </div>
                </div>
            <?php } ?>

            <!-- 제품설명 -->
            <div class="grid none tab_cont_wrap">
                <div class="tab sticky_tab">
                    <ul class="type3 onoff tab_btn">
                        <li class="on"><a href="#"><span>제품설명</span></a></li>
                        <li class=""><a href="#"><span>상세정보</span></a></li>
                        <li class=""><a href="#"><span>REVIEW(<?php echo number_format($item_use_count); ?>)</span></a></li>
                        <li class=""><a href="#"><span>제품문의</span></a></li>
                    </ul>
                </div>
                <div class="tab_cont">
                    <!-- tab1 -->
                    <div class="tab_inner">
                        <div class="grid">
                            <h3 class="blind">제품설명</h3>
                            <div class="detail_wrap" style="overflow: hidden;">
                                <?php echo ($it['it_mobile_explan_use'] ? conv_content($it['it_mobile_explan'], 1) : conv_content($it['it_explan'], 1)); ?>
                            </div>
                        </div>
                    </div>

                    <!-- tab2-->
                    <div class="tab_inner">
                        <div class="grid bb0">
                            <h3 class="g_title_05">제품 상세 정보</h3>
                            <?php
                            if ($it['it_id'] && $it['it_info_value'] != '' && $it['it_info_value'] != '[]') {
                                echo '<ul class="basic_info">';

                                $article = json_decode($it['it_info_value'], true);
                                foreach ($article as $key => $value) {
                                    $list = '<li>';
                                    $list .= '    <span class="item">' . $value['name'] . '</span>';
                                    $list .= '    	<strong class="result">' . $value['value'] . '</strong>';
                                    $list .= '</li>';

                                    echo $list;
                                }

                                echo '</ul>';
                            }
                            ?>
                        </div>
                        <div class="grid bt0">
                            <h3 class="g_title_05">배송 및 교환/반품 안내</h3>
                            <ul class="basic_info">
                                <li>
                                    <span>배송방법</span>
                                    <strong><?php echo $it['it_send_type'] ?></strong>
                                </li>
                                <li>
                                    <span>배송기간</span>
                                    <strong><?php echo $it['it_send_term_start'] ?> ~ <?php echo $it['it_send_term_end'] ?>일 정도 소요됩니다.</strong>
                                </li>
                                <li>
                                    <span>기본 배송비</span>
                                    <strong><?php echo number_format($it['it_sc_minimum']) ?> 원 미만일때 배송비 <?php echo number_format($it['it_sc_price']) ?> 원 부과됩니다.</strong>
                                </li>
                                <li>
                                    <span>반품<br>택배사</span>
                                    <strong><?php echo $it['it_delivery_company'] ?></strong>
                                </li>
                                <li>
                                    <span>반품<br>비용</span>
                                    <strong>반품 : <?php echo number_format($it['it_return_costs']) ?> 원</strong>
                                </li>
                                <li>
                                    <span>교환/반품<br>주소지</span>
                                    <strong><?php echo $it['it_return_zip'] . ' ' . $it['it_return_address1'] . ' ' . $it['it_return_address2'] ?></strong>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- tab3-->
                    <div class="tab_inner" id="itemuse">
                        <?php include_once(G5_SHOP_PATH . '/itemuse.php'); ?>
                    </div>

                    <!-- tab4-->
                    <div class="tab_inner" id="itemqa">
                        <?php include_once(G5_SHOP_PATH . '/itemqa.php'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($is_orderable) { ?>
        <!-- floating_wrap -->
        <div class="floating_wrap">
            <div class="control_btn">
                <a href="#none" class="btn-open">옵션 보기/닫기</a>
            </div>
            <div class="ft_inner">
                <div class="fix_view">
                    <div id="sit_sel_option">
                        <?php
                        if (!$option_item) {
                            if (!$it['it_buy_min_qty'])
                                $it['it_buy_min_qty'] = 1;
                        ?>
                            <ul id="sit_opt_added">
                                <li class="sit_opt_list">
                                    <input type="hidden" name="io_type[<?php echo $it_id; ?>][]" value="0">
                                    <input type="hidden" name="io_id[<?php echo $it_id; ?>][]" value="">
                                    <input type="hidden" name="io_value[<?php echo $it_id; ?>][]" value="<?php echo $it['it_name']; ?>">
                                    <input type="hidden" class="io_price" value="0">
                                    <input type="hidden" class="io_stock" value="<?php echo $it['it_stock_qty']; ?>">

                                    <div class="cont">
                                        <p class="txt"><span><?php echo $it['it_name']; ?></span></p>
                                    </div>
                                    <div class="cont alignR">
                                        <div class="count_control">
                                            <em class="num">
                                                <span class="blind">현재수량</span>
                                                <span><input type="text" name="ct_qty[<?php echo $it_id; ?>][]" value="<?php echo $it['it_buy_min_qty']; ?>" id="ct_qty_<?php echo $i; ?>" class="num_input" size="5" style="height:18px;"></span>
                                            </em>
                                            <button type="button" class="count_minus"><span class="blind">감소</span></button>
                                            <button type="button" class="count_plus"><span class="blind">증가</span></button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <script>
                                $(function() {
                                    price_calculate();
                                });
                            </script>
                        <?php } ?>
                    </div>
                    <div class="cont">
                        <p class="txt">총 주문 금액<span class="point" id="sit_tot_count"></span></p>
                        <div><strong class="price" id="sit_tot_price">0원</strong></div>
                    </div>
                    <div class="btn_group two">
                        <?php if ($is_orderable) { ?>
                            <button type="submit" class="btn big black" onclick="document.pressed=this.value;" value="장바구니"><span>장바구니</span></button>
                            <button type="submit" class="btn big green" onclick="document.pressed=this.value;" value="바로구매"><span>바로주문</span></button>
                        <?php } ?>

                        <?php if (!$is_orderable && $it['it_soldout'] && $it['it_stock_sms'] && false) { ?>
                            <a href="javascript:popup_stocksms('<?php echo $it['it_id']; ?>');" id="sit_btn_alm"><i class="fa fa-bell-o" aria-hidden="true"></i> 재입고알림</a>
                        <?php } ?>

                        <?php if ($naverpay_button_js) { ?>
                            <div class="itemform-naverpay"><?php echo $naverpay_request_js . $naverpay_button_js; ?></div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <!-- //floating_wrap -->

    <!-- The Modal -->
    <div id="optionModal" class="modal" style="display: none;">
        <!-- Modal content -->
        <div class="content sub">
            <div style="float: right;">
                <a href="#" class="close"><span class="blind">닫기</span></a>
            </div>
            <div class="grid cont" style="border-top-width: 0px;">
                <div class="title_bar" style="overflow:visible;">
                    <h3 class="g_title_01" id="optionModalTitle">선택한 :<span class="none"></span></h3>
                </div>
                <div class="list">
                    <ul class="type1 pad" id="optionModalList">
                    </ul>
                </div>

            </div>
        </div>
    </div>
    <!--End Modal-->

</form>

<? include_once(G5_MOBILE_PATH . '/common/social_share1.php'); ?>

<script>
    // 상품보관
    function item_wish(f, it_id) {
        if ($(".pick[it_id='" + it_id + "']").attr("class").indexOf("on") < 0) {
            $.post(
                "<?php echo G5_SHOP_URL; ?>/wishupdate2.php", {
                    it_id: it_id
                },
                function(data) {
                    var responseJSON = JSON.parse(data);
                    if (responseJSON.result == "S") {

                        if (confirm("관심상품에 저장되었습니다. 보러가시겠습니까?")) location.href = '<?php echo G5_SHOP_URL; ?>/wishlist.php';

                        $(".pick[it_id='" + it_id + "']").addClass("on");
                    } else {
                        alert(responseJSON.alert);
                        return false;
                    }
                }
            );
        } else {
            $.post(
                "<?php echo G5_SHOP_URL; ?>/wishupdate2.php", {
                    it_id: it_id,
                    w: 'r'
                },
                function(data) {
                    var responseJSON = JSON.parse(data);
                    if (responseJSON.result == "S") {
                        $(".pick[it_id='" + it_id + "']").removeClass("on");
                    } else {
                        alert(responseJSON.alert);
                        return false;
                    }
                }
            );
        }
    }

    // 추천메일
    function popup_item_recommend(it_id) {
        if (!g5_is_member) {
            if (confirm("회원만 추천하실 수 있습니다."))
                document.location.href = "<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo urlencode(G5_SHOP_URL . "/item.php?it_id=$it_id"); ?>";
        } else {
            url = "./itemrecommend.php?it_id=" + it_id;
            opt = "scrollbars=yes,width=616,height=420,top=10,left=10";
            popup_window(url, "itemrecommend", opt);
        }
    }

    function fsubmit_check(f) {
        // 판매가격이 0 보다 작다면
        if (document.getElementById("it_price").value < 0) {
            alert("전화로 문의해 주시면 감사하겠습니다.");
            return false;
        }

        if ($(".sit_opt_list").size() < 1) {
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

            if (val.length < 1) {
                alert("수량을 입력해 주십시오.");
                result = false;
                return false;
            }

            if (val.replace(/[0-9]/g, "").length > 0) {
                alert("수량은 숫자로 입력해 주십시오.");
                result = false;
                return false;
            }

            if (parseInt(val.replace(/[^0-9]/g, "")) < 1) {
                alert("수량은 1이상 입력해 주십시오.");
                result = false;
                return false;
            }

            io_type = $el_type.eq(index).val();
            if (io_type == "0")
                sum_qty += parseInt(val);
        });

        if (!result) {
            return false;
        }

        if (min_qty > 0 && sum_qty < min_qty) {
            alert("선택옵션 개수 총합 " + number_format(String(min_qty)) + "개 이상 주문해 주십시오.");
            return false;
        }

        if (max_qty > 0 && sum_qty > max_qty) {
            alert("선택옵션 개수 총합 " + number_format(String(max_qty)) + "개 이하로 주문해 주십시오.");
            return false;
        }

        return true;
    }

    // 바로구매, 장바구니 폼 전송
    function fitem_submit(f) {
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

        if ($(".sit_opt_list").size() < 1) {
            alert("상품의 선택옵션을 선택해 주십시오.");
            return false;
        }

        if ($("select[name='sel_it_option[]']").size() > 1) {
            //세트상품의 경우 모든 상품을 선택해야함.
            if ($("select[name='sel_it_option[]']").size() != $(".sit_opt_list").size()) {
                alert("상품의 선택옵션을 선택해 주십시오.");
                return false;
            }
        }

        var val, io_type, result = true;
        var sum_qty = 0;
        var min_qty = parseInt(<?php echo $it['it_buy_min_qty']; ?>);
        var max_qty = parseInt(<?php echo $it['it_buy_max_qty']; ?>);
        var $el_type = $("input[name^=io_type]");

        $("input[name^=ct_qty]").each(function(index) {
            val = $(this).val();

            if (val.length < 1) {
                alert("수량을 입력해 주십시오.");
                result = false;
                return false;
            }

            if (val.replace(/[0-9]/g, "").length > 0) {
                alert("수량은 숫자로 입력해 주십시오.");
                result = false;
                return false;
            }

            if (parseInt(val.replace(/[^0-9]/g, "")) < 1) {
                alert("수량은 1이상 입력해 주십시오.");
                result = false;
                return false;
            }

            io_type = $el_type.eq(index).val();
            if (io_type == "0")
                sum_qty += parseInt(val);
        });

        if (!result) {
            return false;
        }

        if (min_qty > 0 && sum_qty < min_qty) {
            alert("선택옵션 개수 총합 " + number_format(String(min_qty)) + "개 이상 주문해 주십시오.");
            return false;
        }

        if (max_qty > 0 && sum_qty > max_qty) {
            alert("선택옵션 개수 총합 " + number_format(String(max_qty)) + "개 이하로 주문해 주십시오.");
            return false;
        }

        return true;
    }

    function io_option_select(optionid, optionval) {
        //alert(optionid+","+optionval);

        $('#' + optionid).val(optionval);

        $('#btn_' + optionid).text($('#' + optionid + " option:selected").text());
        $(".modal").css("display", "none");

        var its_no = $('#' + optionid).attr("its_no");

        var $sel_it_supply = $("select[name='sel_it_supply[]'][its_no='" + its_no + "']");
        if ($sel_it_supply.size() > 0) {
            $sel_it_supply.each(function() {
                $(this).val("");
                optionid = $(this).attr("id");
                $('#btn_' + optionid).text("선택");
            });
        }

        //alert($('#'+optionid).attr("its_no"));

        add_sel_option_mobile_chk(its_no);
    }

    function io_supply_select(optionid, optionval) {
        //alert(optionid+","+optionval);

        $('#' + optionid).val(optionval);

        $('#btn_' + optionid).text($('#' + optionid + " option:selected").text());
        $(".modal").css("display", "none");

        add_sel_option_mobile_chk($('#' + optionid).attr("its_no"));
    }

    function add_sel_option_mobile_chk(its_no) {
        var add_exec = true;

        var $sel_it_option = $("select[name='sel_it_option[]'][its_no='" + its_no + "']");
        if ($sel_it_option.val() == "") add_exec = false;

        var $sel_it_supply = $("select[name='sel_it_supply[]'][its_no='" + its_no + "']");
        if ($sel_it_supply.size() > 0) {
            $sel_it_supply.each(function() {
                if ($(this).val() == "") add_exec = false;
            });
        }

        //add_option
        if (add_exec) {
            var id = "";
            var value, info, sel_opt, item, price, stock, run_error = false;
            var option = sep = "";

            var it_price = parseInt($("input[name='its_final_price[]'][its_no='" + its_no + "']").val());
            //var it_price = parseInt($("input#it_price").val());
            var item = $sel_it_option.closest("li").find("span[id^=spn_it_option]").text();

            value = $sel_it_option.val();
            info = value.split(",");
            sel_opt = info[0];
            id = sel_opt;
            option += sep + item + ":" + sel_opt;

            price = info[1];
            stock = info[2];

            supply_ids = '';
            supply_seq = '';

            $sel_it_supply.each(function() {
                //if($(this).val() == "") add_exec = false;
                value = $(this).val();
                info = value.split(",");
                sel_opt = info[0].split(chr(30))[1];

                //id += chr(30)+sel_opt;
                sep = " , ";
                option += sep + sel_opt;
                price = parseInt(price) + parseInt(info[1]);

                supply_ids += supply_seq + value;
                supply_seq = ",";
            });

            //alert(option);

            //if(same_option_check(option))
            //   return;

            add_sel_option_mobile(0, id, option, price, stock, it_price, its_no, supply_ids);
        }
    }

    function add_sel_option_mobile(type, id, option, price, stock, it_price, its_no, supply_ids) {
        var item_code = $("input[name='it_id[]']").val();
        var opt = "";
        var li_class = "sit_opt_list";
        if (type)
            li_class = "sit_spl_list";

        var opt_prc;
        if (parseInt(price) >= 0)
            opt_prc = number_format(it_price) + "원 (+" + number_format(String(price)) + "원)";
        else
            opt_prc = number_format(it_price) + "원 (" + number_format(String(price)) + "원)";

        opt += "<li class=\"" + li_class + "\" its_no=\"" + its_no + "\">";
        opt += "<input type=\"hidden\" name=\"io_type[" + item_code + "][]\" value=\"" + type + "\">";
        opt += "<input type=\"hidden\" name=\"io_id[" + item_code + "][]\" value=\"" + id + "\">";
        opt += "<input type=\"hidden\" name=\"io_value[" + item_code + "][]\" value=\"" + option + "\">";
        opt += "<input type=\"hidden\" name=\"io_supply[" + item_code + "][]\" value=\"" + supply_ids + "\">";
        opt += "<input type=\"hidden\" name=\"its_no[" + item_code + "][]\" value=\"" + its_no + "\">";

        opt += "<input type=\"hidden\" class=\"it_price\" value=\"" + it_price + "\">";
        opt += "<input type=\"hidden\" class=\"io_price\" value=\"" + price + "\">";
        opt += "<input type=\"hidden\" class=\"io_stock\" value=\"" + stock + "\">";
        opt += "<div class=\"cont\"><p class=\"txt\"><span>" + option + "</span></p>";
        opt += "<span style=\"\">" + opt_prc + "</span></div>";

        opt += "<div class=\"cont alignR\"><div class=\"count_control\">";
        opt += "<em class=\"num\"><input type=\"text\" name=\"ct_qty[" + item_code + "][]\" value=\"1\" class=\"frm_input\" size=\"5\" style=\"height:18px;\"></em>";

        opt += "<button type=\"button\" class=\"count_minus\"><span class=\"blind\">감소</span></button>";
        opt += "<button type=\"button\" class=\"count_plus\"><span class=\"blind\">증가</span></button>";

        opt += "</div>";

        opt += "</div></li>";

        if ($(".sit_opt_list[its_no='" + its_no + "']").size() >= 1) {
            $(".sit_opt_list[its_no='" + its_no + "']").remove();
        }

        if ($("#sit_sel_option > ul").size() < 1) {
            $("#sit_sel_option").html("<ul id=\"sit_opt_added\"></ul>");
            $("#sit_sel_option > ul").html(opt);
        } else {
            if (type) {
                if ($("#sit_sel_option .sit_spl_list").size() > 0) {
                    $("#sit_sel_option .sit_spl_list:last").after(opt);
                } else {
                    if ($("#sit_sel_option .sit_opt_list").size() > 0) {
                        $("#sit_sel_option .sit_opt_list:last").after(opt);
                    } else {
                        $("#sit_sel_option > ul").html(opt);
                    }
                }
            } else {
                if ($("#sit_sel_option .sit_opt_list").size() > 0) {
                    $("#sit_sel_option .sit_opt_list:last").after(opt);
                } else {
                    if ($("#sit_sel_option .sit_spl_list").size() > 0) {
                        $("#sit_sel_option .sit_spl_list:first").before(opt);
                    } else {
                        $("#sit_sel_option > ul").html(opt);
                    }
                }
            }
        }

        price_calculate();
    }


    $(function() {

        $(".it_option").click(function() {
            var seq = $(this).attr("SEQ");

            var optionName = $(this).text();
            var title = $('#spn_it_option_' + seq).text();
            $('#optionModalTitle').html("선택한 " + title + ":<span class=\"none\">" + optionName + "</span></h3>");

            var optionList = "";
            var $option = $('#it_option_' + seq + ' option');

            $option.each(function() {
                io_id = $(this).text();
                io_price = $(this).attr("io_price");
                io_price_mark = (parseInt(io_price) > 0) ? "+" : "";

                io_stock_qty = $(this).attr("io_stock_qty");

                io_value = $(this).val();


                if (io_value != "") {
                    if (io_stock_qty < 1) {
                        //품절
                        optionList += "<li class=\"soldout\">";
                        optionList += "<a >";
                        optionList += "<span class=\"bold point_black\">" + io_id + "</span>";
                        optionList += "<span class=\"r_box point\">" + io_price_mark + number_format(io_price) + "</span>";
                        optionList += "</a></li>";
                    } else {
                        optionList += "<li>";
                        optionList += "<a onclick='io_option_select(\"it_option_" + seq + "\", \"" + io_value + "\");' >";
                        optionList += "<span class=\"bold\">" + io_id + "</span>";
                        optionList += "<span class=\"r_box point\">" + io_price_mark + number_format(io_price) + "</span>";
                        optionList += "</a></li>";
                    }
                }
            });

            $('#optionModalList').html(optionList);

            $('#it_option_' + seq).val("");
            $("#optionModal").css("display", "block");
        });

        $(".it_supply").click(function() {
            var seq = $(this).attr("SEQ");

            var optionName = $(this).text();
            var title = $('#spn_it_supply_' + seq).text();
            $('#optionModalTitle').html("선택한 " + title + ":<span class=\"none\">" + optionName + "</span></h3>");

            var optionList = "";
            var $option = $('#it_supply_' + seq + ' option');

            $option.each(function() {
                io_id = $(this).text();
                io_price = $(this).attr("io_price");
                io_stock_qty = $(this).attr("io_stock_qty");

                io_value = $(this).val();

                if (io_value != "") {
                    if (io_stock_qty < 1) {
                        //품절
                        optionList += "<li class=\"soldout\">";
                        optionList += "<a >";
                        optionList += "<span class=\"bold point_black\">" + io_id + "</span>";
                        optionList += "<span class=\"r_box point\">" + number_format(io_price) + "</span>";
                        optionList += "</a></li>";
                    } else {
                        optionList += "<li>";
                        optionList += "<a onclick='io_supply_select(\"it_supply_" + seq + "\", \"" + io_value + "\");' >";
                        optionList += "<span class=\"bold\">" + io_id + "</span>";
                        optionList += "<span class=\"r_box point\">" + number_format(io_price) + "</span>";
                        optionList += "</a></li>";
                    }
                }
            });

            $('#optionModalList').html(optionList);

            $('#it_supply_' + seq).val("");
            $("#optionModal").css("display", "block");
        });

        $("a[name='modalClose']").click(function() {
            $(".modal").css("display", "none");
        });

        $(".close").click(function() {
            $(".modal").css("display", "none");
        });;

    });
</script>


<?php /* 2017 리뉴얼한 테마 적용 스크립트입니다. 기존 스크립트를 오버라이드 합니다. */ ?>
<script src="<?php echo G5_JS_URL; ?>/shop.override.js"></script>