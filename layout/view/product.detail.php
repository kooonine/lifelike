<?php
ob_start();
$g5_title = " ";
$g5_product_detail = "Y";
require_once(G5_LIB_PATH . '/coupon.lib.php');
$libCoupon = new coupon;

$libCoupon->setItem($it_id);
$coupons = $libCoupon->getCouponList($member['mb_id']);
// dd($libCoupon->maxDiscountPrice());
// dd($coupons);
$total_sale_price = $it_price;
$ciCheck = 0;
foreach ($coupons as $ci => $citier) {
    if ($citier['cz_period'] != 31) {
        $ciCheck = 1;
    } 
}
include_once G5_VIEW_PATH . "/product.detail.modal.php";
?>
<link rel="stylesheet" href="/re/css/event.css">
<link rel="stylesheet" href="/re/css/coupon.css?ver=20051901">
<?php if ($config['cf_kakao_js_apikey']) { ?>
    <script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
    <script src="<?php echo G5_JS_URL; ?>/kakaolink.js"></script>
    <script>
        // 사용할 앱의 Javascript 키를 설정해 주세요.
        Kakao.init("<?php echo $config['cf_kakao_js_apikey']; ?>");
    </script>
<?php } ?>

<style>
    @import "/re/css/product.detail.css";

    .on-small.topSubmenu {
        display: none;
    }

    html.scroll-down #nav-top-small {
        top: 0;
    }

    .detailMobile iframe {
        width :100% !important;
        height: 242px !important;
    }
/* form | radio */
input[type=radio] {display:none; margin:10px;}

input[type=radio] +label {
    display: inline-block;
    height: 40px;
    line-height: 40px;
    padding: 0 11px;
    border-radius: 4px;
    background: #fff;
    border: 1px solid #eee;
    text-align: center;
    cursor: pointer;
    min-width: 47px;
}
/* .salePrice input[type="radio"]:checked + label {
    background: #f93f00;
    color: #f93f00;
 } */
 input[class="salePrice"]:checked + label {
    color: #f93f00;
 }
 input[class="sizeReview"] + label {
    margin-top: 2px;
 }
 
 input[class="sizeReviewBodyMo"] + label {
    height: 22px;
    min-width: 17px;
    line-height: normal;
    margin-bottom: 1px;
 }

</style>
<script type="text/javascript" src="/js/swiper/swiper.min.js"></script>

<div class="on-big" style="height:80px;"></div>
<div id="product-detail-wrapper">
    <!-- <div class="product-list-path on-big"><?php echo $g5_menu_path ?></div>
    <? if ($brand) : ?>
        <a href="/shop/brand.php?br_id=<?= $brand['br_id'] ?>" class="on-big">
            <div id="info-brand" style="font-size: 0; margin-bottom: 16px;">
                <span id="product-detail-brand-thumb" style="background-image: url(/data/brand/<?= $brand['br_lookbook'] ?>);"></span>
                <span id="product-detail-brand-info">
                    <div style="font-size: 20px; font-weight: bold;"> <?= $brand['br_name_en'] ?></div>
                    <div><?= $brand['br_slogan'] ?></div>
                    <div></div>
                </span>
            </div>
        </a>
    <? endif ?> -->
    <div id="product-info-wrapper" class="section-observed">
        <div class="product-info-gallery gallery-view">

            <div class="swiper-container gallery-top">
                <div class="swiper-wrapper">
                    <?
                    $big_img_count = 0;
                    $thumbnails = array();
                    for ($i = 1; $i <= 10; $i++) {
                        if (!$it['it_img' . $i]) {
                            continue;
                        }
                        $img = get_it_thumbnail_path($it['it_img' . $i], 700, 700);
                        if ($img) {
                            // 썸네일
                            $thumb = get_it_thumbnail_path($it['it_img' . $i], 120, 120);
                            $thumbnails[] = $thumb;
                            $big_img_count++;
                            echo '<div class="swiper-slide" style="background-image:url(' . $img . ')"></div>';
                        }
                    }
                    // if ($big_img_count == 0) {
                    //     echo '<div class="swiper-slide" style="background-image:url(' . G5_SHOP_URL . '/img/no_image.gif)"></div>';
                    // }
                    ?>
                </div>
                <div class="on-small swiper-pagination swiper-pagination-black"></div>
            </div>
            <div class="swiper-container gallery-thumbs on-big">
                <div class="swiper-wrapper" style = "margin-left:13px;">
                    <?
                    $total_count = count($thumbnails);
                    if ($total_count > 0) {
                        foreach ($thumbnails as $val) {
                            echo '<div class="swiper-slide" style="background-image:url(' . $val . ')"></div>';
                        }
                    }
                    ?>
                </div>
                <!-- Add Arrows -->
                <!-- <div class="swiper-button-next swiper-button-white"></div> -->
                <!-- <div class="swiper-button-prev swiper-button-white"></div> -->
            </div>
        </div>
        <div class="product-info">
            <form id="formCartUpdate" name="formCartUpdate" method="POST" action="<?= $action_url; ?>">
                <!-- <input type="hidden" name="it_id[]" value="<?= $it_id; ?>" /> -->
                <input type="hidden" id = "ori_it_id" name="ori_it_id" value="<?= $it_id; ?>" />
                <input type="hidden" name="od_type" value="O" />
                <input type="hidden" name="sw_direct" />
                <input type="hidden" name="url" />



                <?php
                $now_host = $_SERVER['HTTP_HOST'];
                $now_uri = $_SERVER['REQUEST_URI'];

                $sns_url  = G5_URL . $now_uri;
                $sns_title = $it['it_name'];
                $sns_image = G5_URL . '/data/item/' . $it['it_img1'];
                ?>
                <table class="product_info_goods_info">
                    <tbody>
                        <colgroup>
                            <col id="product-info-col-1">
                        </colgroup>
                        <tr>
                            <td colspan=2 class="product_info_brand"><span style="cursor: pointer;" onclick="location.href='/shop/brand.php?br_id=<?= $brand['br_id'] ?>'"><?php echo $brand['br_name'] ?></span>
                                <span class="on-small product_info_pick_brand btn-pick-heart <?= in_array($brand['br_id'], $g5_picked['BRAND']) ? "picked" : "" ?>" data-type="brand" data-pick=<?= $brand['br_id'] ?>></span>
                                <span class="on-big" style="cursor: pointer;" onclick="location.href='/shop/brand.php?br_id=<?= $brand['br_id'] ?>'"><img class="bl_right" src="/img/re/bl_right.png" srcset="/img/re/bl_right@2x.png 2x,/img/re/bl_right@3x.png 3x"></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=2 class="product_info_name"><?php echo $it['it_name'] ?></td>
                        </tr>
                        <tr>
                            <td colspan=2 class="product_info_basic" style="text-align: left;"><?php echo $it['it_basic'] ?></td>
                        </tr>
                        <tr>
                            <td colspan=2><?php echo $badgeObj->html ?></td>
                        </tr>

                        <tr>
                            <td colspan=2>
                                <div class="product-info-seperator on-big"></div>
                            </td>
                        </tr>

                        <?php
                        $user_price = get_price($it);
                        /*
                        if (!empty($maxDiscountCouponInfo) && $user_price > $maxDiscountCouponInfo['DISCOUNT_PRICE']) {
                        ?>
                            <tr>
                                <th>최대 혜택가</th>
                                <td class="H5ENMIL">
                                    <?= number_format($maxDiscountCouponInfo['DISCOUNT_PRICE']) ?><span class="P1KOMIL">원</span><button type="button" style="font-size: 12px; margin-left: 12px;">쿠폰 다운로드</button>
                                </td>
                            </tr>
                        <?
                        }
                        */

                        if ($view_detail_items['view_it_sale_bprice'] && !empty($it['it_discount_price'])) {
                            $it_price = $it['it_price'];
                            $it_sale_price = $it['it_discount_price'];
                            $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                            $sns_sales = $it_price + $it_sale_price;
                        ?>
                            <tr>
                                <th>판매가</th>
                                <td class="price_group">
                                    <?php
                                    if ($view_detail_items['view_it_price']) {
                                        echo number_format($user_price) . '<span class="price">원 </span>';
                                        echo '<del class="price-del">' . number_format($it_price + $it_sale_price) . '원</del><span class="price-dis"> (' . number_format($discount_ratio) . "%)</span>";
                                    }
                                    ?>
                                </td>
                            </tr>

                            <!-- // 쿠폰 혜텍가 계산 상품 쿠폰, 플러스 맥심으로 -->
                            <?php
                            $sql_coupon_sale_price_0 = "SELECT * FROM lt_shop_coupon_zone WHERE cp_method = '0' AND cz_start <= NOW() AND cz_end >= NOW() AND cp_target LIKE '%{$it['it_id']}%' ORDER BY cp_price DESC, cp_maximum DESC  ";
                            $db_coupon_sale_price_0 = sql_query($sql_coupon_sale_price_0);
                            $db_coupon_sale_price_data_0 = sql_fetch($sql_coupon_sale_price_0);

                            $sql_coupon_sale_price_11 = "SELECT * FROM lt_shop_coupon_zone WHERE cp_method = '11' AND cz_start <= NOW() AND cz_end >= NOW() AND cp_target LIKE '%{$it['it_id']}%' ORDER BY cp_price DESC, cp_maximum DESC  ";
                            $db_coupon_sale_price_11 = sql_query($sql_coupon_sale_price_11);
                            $db_coupon_sale_price_data_11 = sql_fetch($sql_coupon_sale_price_11);
                            $coupon_sale_price_total = $user_price;
                            ?>

                            <?php if (count($db_coupon_sale_price_data_0) > 0) : ?>
                                <?php while (($csp0 = sql_fetch_array($db_coupon_sale_price_0)) != false) {
                                    if ($coupon_sale_price_total > $csp0['cp_minimum']) {
                                        $csp0_pre = $csp0['cp_price'];
                                        $coupon_sale_price_0 = $libCoupon->calcDiscountPrice($coupon_sale_price_total, $csp0['cp_price'], $csp0['cp_type'], $csp0['cp_trunc'], $csp0['cp_maximum']);
                                        $coupon_sale_price_total  = $coupon_sale_price_total - $coupon_sale_price_0;
                                        break;
                                    }
                                }
                                ?>
                                <?php if (count($db_coupon_sale_price_data_11) > 0) : ?>
                                    <?php while (($csp11 = sql_fetch_array($db_coupon_sale_price_11)) != false) {
                                        if ($coupon_sale_price_total > $csp11['cp_minimum']) {
                                            $csp11_pre = $csp11['cp_price'];
                                            $coupon_sale_price_11 = $libCoupon->calcDiscountPrice($coupon_sale_price_total, $csp11['cp_price'], $csp11['cp_type'], $csp11['cp_trunc'], $csp11['cp_maximum']);
                                            $coupon_sale_price_total  = $coupon_sale_price_total - $coupon_sale_price_11;
                                            break;
                                        }
                                    }
                                    ?>
                                <? endif ?>
                            <? endif ?>
                            <? if (count($coupons) > 0) : ?>
                                <tr>
                                    <th>쿠폰 혜택가</th>
                                    <td class="price_group">
                                        <?php
                                        if  ($ciCheck >0) {
                                            echo  number_format($coupon_sale_price_total) . '<span class="price">원 </span><span class="mouse-point C1ENORL detail_coupon_btn" onclick=coupon_modal("' . $member['mb_id'] . '")><span>쿠폰받기</span><span></span></span>';
                                        } else {
                                            echo  number_format($coupon_sale_price_total) . '<span class="price">원 </span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <? endif ?>
                        <?php
                        } else {
                        ?>
                            <th>판매가</th>
                            <td class="price_group">
                                <?php
                                if ($view_detail_items['view_it_price']) {
                                    echo number_format($user_price) . '<span class="price">원 </span>';
                                    // echo '<del class="price-del">' . number_format($it_price + $it_sale_price) . '원 </del><span class="price-dis">(' . number_format($discount_ratio) . "%)</span>";
                                }
                                ?>
                            </td>
                            </tr>
                            <!-- // 쿠폰 혜텍가 계산 상품 쿠폰, 플러스 맥심으로 -->
                            <?php
                            $sql_coupon_sale_price_0 = "SELECT * FROM lt_shop_coupon_zone WHERE cp_method = '0' AND cz_start <= NOW() AND cz_end >= NOW() AND cp_target LIKE '%{$it['it_id']}%' ORDER BY cp_price DESC, cp_maximum DESC  ";
                            // $sql_coupon_sale_price_0 = "SELECT cz.* FROM lt_shop_coupon_zone AS cz LEFT JOIN lt_shop_coupon AS cp ON cz.cz_subject = cp.cp_subject WHERE cz.cp_method = '0' AND cz.cz_start <= NOW() AND cz.cz_end >= NOW() AND cz.cp_target LIKE '%{$it['it_id']}%' AND cp.cp_subject IS NOT NULL ORDER BY cp_price DESC, cp_maximum DESC  ";

                            $db_coupon_sale_price_0 = sql_query($sql_coupon_sale_price_0);
                            $db_coupon_sale_price_data_0 = sql_fetch($sql_coupon_sale_price_0);

                            $sql_coupon_sale_price_11 = "SELECT * FROM lt_shop_coupon_zone WHERE cp_method = '11' AND cz_start <= NOW() AND cz_end >= NOW() AND cp_target LIKE '%{$it['it_id']}%' ORDER BY cp_price DESC, cp_maximum DESC  ";
                            $db_coupon_sale_price_11 = sql_query($sql_coupon_sale_price_11);
                            $db_coupon_sale_price_data_11 = sql_fetch($sql_coupon_sale_price_11);
                            $coupon_sale_price_total = $user_price;
                            ?>

                            <?php if (count($db_coupon_sale_price_data_0) > 0) : ?>
                                <?php while (($csp0 = sql_fetch_array($db_coupon_sale_price_0)) != false) {
                                    if ($user_price > $csp0['cp_minimum']) {
                                        $csp0_pre = $csp0['cp_price'];
                                        $coupon_sale_price_0 = $libCoupon->calcDiscountPrice($coupon_sale_price_total, $csp0['cp_price'], $csp0['cp_type'], $csp0['cp_trunc'], $csp0['cp_maximum']);
                                        $coupon_sale_price_total  = $coupon_sale_price_total - $coupon_sale_price_0;
                                        break;
                                    }
                                }
                                ?>
                                <?php if (count($db_coupon_sale_price_data_11) > 0) : ?>
                                    <?php while (($csp11 = sql_fetch_array($db_coupon_sale_price_11)) != false) {
                                        if ($coupon_sale_price_total > $csp11['cp_minimum']) {
                                            $csp11_pre = $csp11['cp_price'];
                                            $coupon_sale_price_11 = $libCoupon->calcDiscountPrice($coupon_sale_price_total, $csp11['cp_price'], $csp11['cp_type'], $csp11['cp_trunc'], $csp11['cp_maximum']);
                                            $coupon_sale_price_total  = $coupon_sale_price_total - $coupon_sale_price_11;
                                            break;
                                        }
                                    }
                                    ?>
                                <? endif ?>
                            <? endif ?>
                            <? if (count($coupons) > 0) : ?>
                                <tr>
                                    <th>쿠폰 혜택가</th>
                                    <td class="price_group">
                                        <?php
                                        if  ($ciCheck >0) { 
                                            echo  number_format($coupon_sale_price_total) . '<span class="price">원 </span><span class="mouse-point C1ENORL detail_coupon_btn" onclick=coupon_modal("' . $member['mb_id'] . '")><span>쿠폰받기</span><span></span></span>';
                                        } else {
                                            echo  number_format($coupon_sale_price_total) . '<span class="price">원 </span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <? endif ?>
                        <?php
                        }
                        ?>
                        <tr>
                            <td colspan=2 class="emptytr"></td>
                        </tr>

                        <tr>
                            <?php if ($member['mb_id'] != null || $member['mb_id'] != '') : ?>
                                <td class="hetack on-small" colspan=2 onclick="max_sale_modal()"><?= $member['mb_name'] ?>님의 최대혜택가 <span class="redfont view_max_sale_price"></span> <img class="ns_down" src="/img/re/ns_down.png" srcset="/img/re/ns_down@2x.png 2x,/img/re/ns_down@3x.png 3x"> </td>
                                <td class="mouse-point on-big" colspan=2 onclick="max_sale_modal()">
                                    <div class="hetack on-big"><?= $member['mb_name'] ?>님의 최대혜택가 <span class="redfont view_max_sale_price"></span> <img class="ns_down" src="/img/re/ns_down.png" srcset="/img/re/ns_down@2x.png 2x,/img/re/ns_down@3x.png 3x"> </div>
                                </td>
                            <?php else : ?>
                                <td class="hetack on-small" colspan=2 onclick="location.href='/auth/login.php'">로그인하고 <span class="redfont">최대혜택가</span> 확인하기</td>
                                <td class="mouse-point on-big" colspan=2 onclick="location.href='/auth/login.php'">
                                    <div class="hetack on-big">로그인하고 <span class="redfont">최대혜택가</span> 확인하기</div>
                                </td>
                            <?php endif ?>

                        </tr>

                        <tr>
                            <td colspan=2>
                                <div class="product-info-seperator on-small"></div>
                            </td>
                        </tr>

                        <tr class="on-small">
                            <td colspan=2>
                                <div class="product_info_btn_group">

                                    <div class="product_info_btns"><span class="product_info_pick_item btn-pick-heart <?= in_array($it['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $it['it_id'] ?>></span> 위시리스트</div>
                                    <div class="product_info_btns" onclick="openSnsPopup('item')"><img src="/img/re/share.png" srcset="/img/re/share@2x.png 2x,/img/re/share@3x.png 3x"> 공유하기</div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td colspan=2>
                                <div class="product-info-seperator on-small"></div>
                            </td>
                        </tr>


                        <tr>
                            <th>리뷰</th>
                            <td>
                                <span class="product-info-review-stars"><span style="width: <?= $star_score * 20 ?>%">&nbsp;</span></span>
                                <a class="" href="javascript:goProductDesc('product-desc-review-wrapper')" style="text-decoration: underline !important; margin-left: 8px">
                                    <span class="prod_info_review">
                                        (<?php echo number_format($item_use_count) ?>개)<img class="bl_right" src="/img/re/bl_right.png" srcset="/img/re/bl_right@2x.png 2x,/img/re/bl_right@3x.png 3x">
                                    </span></a>
                            </td>
                        </tr>
                        <tr>
                            <th>배송비</th>
                            <td class="">
                                <?
                                $sc_price = (int) $it['it_sc_price'];
                                $sc_min_price = (int) $it['it_sc_minimum'];
                                // if ($sc_price <= 0) {
                                //     $sc_price = "무료배송";
                                // } else {
                                //     $sc_price = number_format($sc_price) . "원";
                                // }

                                // if (!empty($sc_min_price)) {
                                //     $sc_price = $sc_price . "(" . number_format($sc_min_price) . "원 이상 구매 시 무료배송)";
                                // }
                                echo '전 상품 무료배송';
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>적립포인트</th>
                            <td class="reword_point">
                                <? if ($it['it_point_type'] == '2') { ?>
                                    <span style="color: #f93f00;">결제금액 기준 <?= $it['it_point'] ?>%</span>
                                <?} else if ($it['it_point_type'] == '0') {?>
                                    <?= number_format($it['it_point']) ?>P
                                <?} else if ($it['it_point_type'] == '9') {?>
                                    -
                                <?} else {?>
                                    <span style="color: #f93f00;">결제금액 기준 <?= $default['de_point_percent'] ?>%</span>
                                <?}?>
                                <!-- 결제금액 기준 5% -->
                            </td>
                        </tr>
                        <tr>
                            <th>카드혜택</th>
                            <td class="reword_card on-big" style="cursor: pointer;" id="btn-toggle-benefit">
                                무이자혜택 <img class="bl_right" src="/img/re/bl_right.png" srcset="/img/re/bl_right@2x.png 2x,/img/re/bl_right@3x.png 3x">
                            </td>
                            <td class="reword_card on-small" id="btn-toggle-benefit-mobile">
                                무이자혜택 <img class="bl_right" src="/img/re/bl_right.png" srcset="/img/re/bl_right@2x.png 2x,/img/re/bl_right@3x.png 3x">
                            </td>
                        </tr>

                        <tr>
                            <td colspan=2>
                            <? if ($is_orderable) : ?>
                                <div class="product-info-seperator" style="margin-bottom: 0px;"></div>
                            <? endif ?>
                            </td>
                        </tr>

                        <?php if ($sub_its_count > 0) : ?>
                            <?php foreach ($sub_its as $sub_group => $sub_group_its) : ?>
                                <tr class="on-big" style="display: none">
                                    <th><?= $sub_group ?></th>
                                    <td style="padding: 8px 0">
                                        <?php foreach ($sub_group_its as $sub_group_it) : ?>
                                            <select class="product-detail-item-option pc_select_order_option" <?php printf("data-group='%s' data-no='%s' data-its-no='%s' data-item='%s' data-price='%s' data-rental_price='%s'", $sub_group, $sub_group_it['its_no'], $sub_group_it['its_no'], $sub_group_it['its_item'], $sub_group_it['its_final_price'], $sub_group_it['its_final_rental_price']); ?>>
                                                <!-- <option value=""><?= $sub_group_it['its_item'] ?></option> -->
                                                <option value="">선택</option>
                                                <?php foreach ($sub_group_it['OPTIONS'] as $io) : ?>
                                                    <option value="<?= $io['io_price'] ?>" <?php printf("data-no='%s' data-id='%s' data-stock='%s' data-option-price='%s'", $io['io_no'], $io['io_id'], $io['io_stock_qty'], $io['io_price']); ?> <?= $io['io_noti_qty'] <= 0 ? "disabled" : "" ?>><?= $io['io_id'] ?><?= $io['io_price'] > 0 ? "(+" . number_format($io['io_price']) . "원)" : "" ?><?= $io['io_noti_qty'] <= 0 ? " - 품절" : "" ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        <?php endforeach ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                        <?php if ($sup_its_count > 0) : ?>
                            <tr class="on-big" style="display: none">
                                <th>추가구매</th>
                                <td style="padding: 8px 0">
                                    <?php foreach ($sup_its as $sup_group => $sup_group_its) : ?>
                                        <select class="product-detail-item-option" data-no="supply" data-price=0 data-supply=true data-its-no=<?= $io['its_no'] ?>>
                                            <option value=""><?= $sup_group ?></option>
                                            <?php foreach ($sup_group_its as $io) : ?>
                                                <option value="<?= $io['io_price'] ?>" <?php printf("data-no='%s' data-id='%s' data-stock='%s' data-option-price='%s'", $io['io_no'], $io['name'], $io['io_stock_qty'], $io['io_price']); ?> <?= $io['io_noti_qty'] <= 0 ? "disabled" : "" ?>><?= $io['name'] ?><?= $io['io_price'] > 0 ? "(+" . number_format($io['io_price']) . "원)" : "" ?><?= $io['io_noti_qty'] <= 0 ? " - 품절" : "" ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    <?php endforeach ?>
                                </td>
                            </tr>
                        <?php endif ?>
                        <? if ($is_orderable || $is_totalsoldout) : ?>
                            <!-- 총 구매액 -->
                            <tr class="on-big">
                                <th colspan="2">
                                        <?php 
                                        for($is=0; $sd=sql_fetch_array($sizeData); $is++) { 
                                            if ($sd['it_soldout'] == 1 || $sd['io_stock_qty'] < 1 ) { ?>
                                                 <input type="radio" id ="<?= $sd['io_hoching']?>" disabled><label for="<?= $sd['io_hoching']?>" style="background: #eee; color: #757575; border-color: #ccc; text-decoration: line-through;" disabled><div><?= $sd['io_hoching'] ?></div></label>
                                                
                                           <?php } else {
                                                if (is_numeric(substr($sd['io_hoching'],0,1)))  $raId = 'n'.str_replace('*','',$sd['io_hoching']); else $raId = $sd['io_hoching'];
                                                ?>
                                            <input type="radio" id ="<?= $raId?>" name="sizePick" data-price="<?= $sd['it_price'] ?>" data-itsno="<?= $sd['its_no'] ?>" data-iono="<?= $sd['io_no'] ?>" data-ioid="<?= $sd['io_id'] ?>" data-itid="<?= $sd['it_id'] ?>" data-ioprice="<?= $sd['io_price'] ?>" data-sizeidview="<?= $sd['io_hoching'] ?>" data-totalimg="<?= $sns_image ?>" data-itname ="<?= $it['it_name'] ?>"><label for="<?= $raId?>"><div><?= $sd['io_hoching'] ?></div></label>
                                        <?php } }
                                        ?>
                                </th>
                            </tr>

                            <tr>
                                <td colspan=2>
                                    <div class="product-info-seperator3 on-big" id ="totalLine" style="display: none;"></div>
                                </td>
                            </tr>

                            <tr id="product-item-order" class="on-big renew_order" style="<?= ($sub_its_count == 1) ? 'display : table-row;' : ' display:none;' ?>">
                                <td colspan="2">
                                    <div class='optionDiv' style="display: block;">
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan=2>
                                    <div class="product-info-seperator3 on-big"></div>
                                </td>
                            </tr>

                            <tr id="product-item-order-price" class="on-big">
                                <th>합계</th>
                                <td class="pc-product-item-order-price-total">
                                    총 <span id="product-item-order-price-total"></span>원
                                </td>
                            </tr>                       
                            <tr class="on-big">
                                <td colspan=2 style="padding-top: 40px;">
                                    <input type="hidden" value="<?= $member['mb_id'] ?>" id="cart_update_member_chk">
                                    <button type="button" class="btn btn-order btn-half btn-cart" onclick="cartUpdate(0,'<?= $member['mb_id'] ?>')"> 장바구니</button>
                                    <button type="button" class="btn btn-order btn-half btn-buynow" onclick="cartUpdate(1,'<?= $member['mb_id'] ?>')">바로구매</button>
                                    <button type="button" class="btn-heart btn-quarter"><span class="product_info_pick_item btn-pick-heart quarter-img <?= in_array($it['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $it['it_id'] ?>></span></button>
                                    <button type="button" class="btn-quarter" onclick="openSnsPopup('item')"><img class="btn-community" src="/img/re/share@2x.png" srcset="/img/re/share.png 0.5x , /img/re/share@2x.png 1.5x , /img/re/share@3x.png 2x"></button>
                                </td>
                            </tr>
                        <? else : ?>
                            <tr class="on-big">
                            <td colspan=2>

                                    <button type="button" id="product-detail-instock" class="btn btn-cart-action btn-toggle-instock" data-mb_id="<?= $member['mb_id'] ?>" data-brand="<?= $it['it_brand'] ?>" data-item="<?= $it['it_id'] ?>" data-name="<?= $it['it_name'] ?>" style="border-radius: 2px; width:calc(100% - 116px); ">재입고알림</button>
                                    <button type="button" class="btn-heart btn-quarter"><span class="product_info_pick_item btn-pick-heart quarter-img <?= in_array($it['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $it['it_id'] ?>></span></button>
                                    <button type="button" class="btn-quarter" onclick="openSnsPopup('item')"><img class="btn-community" srcset="/img/re/share.png 0.5x , /img/re/share@2x.png 1.5x , /img/re/share@3x.png 2x"></button>
                                </td>
                            </tr>
                        <? endif ?>

                        <tr class="on-big">
                            <td colspan=2>
                                <div style="height : 20px;"></div>
                            </td>
                        </tr>
                        <?php $prod_banners = 0; ?>
                        <? foreach ($g5_banner_new['ETC'] as $listidx => $listpd) : ?>
                            <? if ($listpd['ba_position'] == 'PRODUCT') : ?>

                                <tr class="on-big">
                                    <td colspan=2>
                                        <div onclick="location.href='<?= $listpd['cp_link'] ?>'" class="product-detail-banner-img on-big" style="background-image: url(/data/banner/<?= $listpd['cp_image_1'] ?>); cursor:pointer;"></div>
                                    </td>
                                </tr>

                            <? endif ?>
                        <? endforeach ?>
                        <tr class="on-big">
                            <td colspan=2>
                                <div style="height : 80px;"></div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </form>
            <!-- 브랜드정보 -->
            <!-- <? if ($brand) : ?>
                <a href="/shop/brand.php?br_id=<?= $brand['br_id'] ?>" class="on-small">
                    <div id="info-brand" style="font-size: 0; margin: 16px 0; padding-top: 16px; border-top: 1px solid var(--very-light-pink-two);">
                        <span id="product-detail-brand-thumb" style="background-image: url(/data/brand/<?= $brand['br_lookbook'] ?>);"></span>
                        <span id="product-detail-brand-info" style="width: calc(100vw - 124px); word-break: keep-all;">
                            <div style="font-size: 16px; font-weight: bold;"> <?= $brand['br_name_en'] ?><?= $brand['br_name'] ?></div>
                            <div style="font-size: 12px;"><?= $brand['br_slogan'] ?></div>
                            <div></div>
                        </span>
                    </div>
                </a>
            <? endif ?> -->
        </div>
    </div>
    <? foreach ($g5_banner_new['ETC'] as $listidx => $listpd) : ?>
        <? if ($listpd['ba_position'] == 'PRODUCT') : ?>
            <? if (count($listidx) < 3) : ?>
                <div onclick="location.href='<?= $listpd['cp_link'] ?>'" class="product-detail-banner-img on-small" style="background-image: url(/data/banner/<?= $listpd['cp_image_2'] ?>);"></div>

                <div class="on-small product-info-seperator" style="margin: 0px;">
                </div>
            <? endif ?>
        <? endif ?>
    <? endforeach ?>

    <div id="product-desc-tab-wrapper">
        <a id="selector-desc-detail" class="product-desc-tab" data-target="product-desc-detail-wrapper" data-name="desc-detail"><span class="mo_style">DETAIL</span>
        </a><a id="selector-desc-review" class="product-desc-tab" data-target="product-desc-review-wrapper" data-name="desc-review"><span class="mo_style">REVIEW(<?php echo number_format($item_use_count) ?>)</span>
        </a><a id="selector-desc-info" class="product-desc-tab" data-target="product-desc-info-wrapper" data-name="desc-info"><span class="mo_style">INFO</span>
        </a><a id="selector-desc-qna" class="product-desc-tab" data-target="product-desc-qna-wrapper" data-name="desc-qna"><span class="mo_style">Q&A</span>
        </a></div>
    <div id="product-desc-tab-wrapper-dummy"></div>
    <section id="product-desc-detail-wrapper" class="section-observed" data-observe="desc-detail" data-observe-prev="desc-detail">
        <!-- <table>
            <tr>
                <th>상품코드</th>
                <td><?= $it_id ?></td>
            </tr>
        </table> -->
        <div class="grid">
            <h3 class="blind">제품설명</h3>
            <div class="detail_wrap">
                <div class ='on-big'>
                <img src="https://lifelikecdn.co.kr/sabang/notice.jpg">
                <?= conv_content($it['it_explan'], 1); ?></div>
                <div class ='detailMobile on-small'> 
                <img src="https://lifelikecdn.co.kr/sabang/notice.jpg">
                <?= conv_content($it['it_explan'], 1); ?></div>
            </div>
        </div>
    </section>

    <section id="product-desc-review-wrapper" class="section-observed" data-observe="desc-review" data-observe-prev="desc-detail">
        <? include_once(G5_SHOP_PATH . '/itemuse.php'); ?>
    </section>
    <section id="product-desc-info-wrapper" class="section-observed" data-observe="desc-info" data-observe-prev="desc-review">
        <div class="product-detail-subtitle-wrapper">
            <!-- koo 0727 -->
            <div class="product-detail-subtitle on-big" style="padding-bottom: 20px;">
                제품상세정보
            </div>
            <div class="product-detail-subtitle on-small" style="padding-bottom: 10px;">
                제품상세정보
            </div>
        </div>
        <div class="product-detail-info-pc on-big">
            <table>
                <?php if (!empty($it['it_info_value'])) : ?>
                    <?php $article = json_decode($it['it_info_value'], true);
                    foreach ($article as $key => $value) {
                    ?>
                        <tr>
                            <th style=''><?= $value['name'] ?></th>
                            <? if ($value['name'] == '세탁방법 및 주의사항') {
                                $pattern = "/([0-9]{1})([.] {1})/";
                                $split = preg_split( $pattern, $value['value']);
                                $num = 1;
                            ?> 
                            <td>
                            <?
                                foreach ($split as $sp) {
                                    if ($sp && $sp !='') { 
                                        $numCheck = substr($sp, -1);
                                        if (is_numeric($numCheck)) $sp = substr($sp, 0, -1);
                                        $numCheck = substr($sp, -1);
                                        if (is_numeric($numCheck)) $sp = substr($sp, 0, -1);
                                        ?>
                                        <?= $num.'. '. $sp ?> <br />
                                    <? $num +=1;
                                    } 
                                }
                            ?>
                                </td>
                            <?} else { ?>
                            <td><?= $value['value'] ?></td>
                            <? } ?>
                        </tr>
                    <?php } ?>
                <?php endif ?>
            </table>
        </div>
        <div class="product-detail-info-mobile on-small">
            <table>
                <?php if (!empty($it['it_info_value'])) : ?>
                    <?php $article = json_decode($it['it_info_value'], true);
                    foreach ($article as $key => $value) {
                    ?>
                        <tr>
                        <th style=''><?= $value['name'] ?></th>
                            <? if ($value['name'] == '세탁방법 및 주의사항') {
                                $pattern = "/([0-9]{1})([.] {1})/";
                                $split = preg_split( $pattern, $value['value']);
                                $num = 1;
                            ?> 
                            <td>
                            <?
                                foreach ($split as $sp) {
                                    if ($sp && $sp !='') { 
                                        $numCheck = substr($sp, -1);
                                        if (is_numeric($numCheck)) $sp = substr($sp, 0, -1);
                                        $numCheck = substr($sp, -1);
                                        if (is_numeric($numCheck)) $sp = substr($sp, 0, -1);
                                        ?>
                                        <?= $num.'. '. $sp ?> <br />
                                    <? $num +=1;
                                    } 
                                }
                            ?>
                                </td>
                            <?} else { ?>
                            <td><?= $value['value'] ?></td>
                            <? } ?>
                        </tr>
                    <?php } ?>
                <?php endif ?>
            </table>
        </div>
        <div class="product-detail-subtitle-wrapper">
            <div class="product-detail-subtitle on-big" style="margin-top: 80px; padding-bottom: 20px;">
                배송 및 교환/반품 안내
            </div>
            <div class="product-detail-subtitle on-small" style="margin-top: 40px; padding-bottom: 10px;">
                배송 및 교환/반품 안내
            </div>
        </div>
        <div class="product-detail-info-pc on-big">
            <table>
                <tr>
                    <th class="tleft">배송방법</th>
                    <td><?= $it['it_send_type'] ?></td>
                </tr>
                <tr>
                    <th class="tleft">배송기간</th>
                    <td><?= $it['it_send_term_start'] ?> ~ <?= $it['it_send_term_end'] ?>일 정도 소요됩니다.</td>
                </tr>
                <tr>
                    <th class="tleft">기본 배송비</th>
                    <td><?= number_format($it['it_sc_minimum']) ?> 원 미만일때 배송비 <?= number_format($it['it_sc_price']) ?> 원 부과됩니다.</td>
                </tr>
                <tr>
                    <th class="tleft">반품 택배사</th>
                    <td><?= $it['it_delivery_company'] ?></td>
                </tr>
                <tr>
                    <th class="tleft">반품 비용</th>
                    <!-- <td>교환 : <?= number_format($it['it_return_costs']) ?> 원 | 반품 : <?= number_format($it['it_roundtrip_costs']) ?> 원</td> -->
                    <td>
                        <!-- 반품 : <?= number_format($it['it_return_costs']) ?> 원 -->
                        총 <?= number_format($it['it_return_costs']*2) ?>원 (최초배송비<?= number_format($it['it_return_costs']) ?>원 + 반품배송비<?= number_format($it['it_return_costs']) ?>원)
                        <!-- 최초 배송비 : <?= number_format($it['it_return_costs']) ?> 원 + 반품 배송비 : <?= number_format($it['it_return_costs']) ?> 원 -->
                        <br>반품 사유에 따라 배송비가 부과됩니다.
                        <br>- 단순 변심: 고객 부담
                        <br>- 상품 불량 또는 오배송: 자사 부담
                        <br>부분 반품시 남은 결제 금액이 무료 배송 기준 이하일 경우, 최초 배송비를 포함한 왕복 배송비가 부과됩니다.
                    </td>
                </tr>
                <tr>
                    <th class="tleft">반품 주소지</th>
                    <td><?= $it['it_return_zip'] . ' ' . $it['it_return_address1'] . ' ' . $it['it_return_address2'] ?></td>
                </tr>
            </table>
        </div>
        <div class="product-detail-info-mobile on-small">
            <table>
                <tr>
                    <th class="tleft">배송방법</th>
                    <td><?= $it['it_send_type'] ?></td>
                </tr>
                <tr>
                    <th class="tleft">배송기간</th>
                    <td><?= $it['it_send_term_start'] ?> ~ <?= $it['it_send_term_end'] ?>일 정도 소요됩니다.</td>
                </tr>
                <tr>
                    <th class="tleft">기본 배송비</th>
                    <td><?= number_format($it['it_sc_minimum']) ?> 원 미만일때 배송비 <?= number_format($it['it_sc_price']) ?> 원 부과됩니다.</td>
                </tr>
                <tr>
                    <th class="tleft">반품 택배사</th>
                    <td><?= $it['it_delivery_company'] ?></td>
                </tr>
                <tr>
                    <th class="tleft">반품 비용</th>
                    <!-- <td>교환 : <?= number_format($it['it_return_costs']) ?> 원 | 반품 : <?= number_format($it['it_roundtrip_costs']) ?> 원</td> -->
                    <td>
                        <!-- 반품 : <?= number_format($it['it_return_costs']) ?> 원 -->
                        총 <?= number_format($it['it_return_costs']*2) ?>원 (최초배송비<?= number_format($it['it_return_costs']) ?>원 + 반품배송비<?= number_format($it['it_return_costs']) ?>원)
                        <!-- 최초 배송비 : <?= number_format($it['it_return_costs']) ?> 원 + 반품 배송비 : <?= number_format($it['it_return_costs']) ?> 원 -->
                        <br>반품 사유에 따라 배송비가 부과됩니다.
                        <br>- 단순 변심: 고객 부담
                        <br>- 상품 불량 또는 오배송: 자사 부담
                        <br>부분 반품시 남은 결제 금액이 무료 배송 기준 이하일 경우, 최초 배송비를 포함한 왕복 배송비가 부과됩니다.
                    </td>
                </tr>
                <tr>
                    <th class="tleft">반품 주소지</th>
                    <td><?= $it['it_return_zip'] . ' ' . $it['it_return_address1'] . ' ' . $it['it_return_address2'] ?></td>
                </tr>
            </table>
        </div>
    </section>
    <section id="product-desc-qna-wrapper" class="section-observed" data-observe="desc-qna" data-observe-prev="desc-info">
        <? include_once(G5_SHOP_PATH . '/itemqalist.php'); ?>
    </section>


    <div class="product_detail_footers_items">같은 카테고리 베스트 아이템</div>

    <div class="main_front_item_area swiper-container" id="category_best_swiper">
        <ul class="swiper-wrapper">
            <?php

            $sql_category_best = "SELECT B.io_hoching , A.* FROM {$g5['g5_shop_item_table']} AS A LEFT JOIN lt_shop_item_option AS B ON (A.it_id = B.it_id) WHERE A.it_use=1 AND B.io_use= 1 AND A.it_total_size = 1 AND A.it_brand = '{$it['it_brand']}' ";

            $sql_category_best_item = $sql_category_best . " LIMIT 20";
            $db_category_best_item = sql_query($sql_category_best_item);


            while (($cbitem = sql_fetch_array($db_category_best_item)) != false) {
                $cbthumb = get_it_thumbnail_path($cbitem['it_img1'], 340, 340);
                if ($cbitem['it_discount_price'] != '' && $cbitem['it_discount_price'] != '0') {
                    $it_price = $cbitem['it_price'];
                    $it_sale_price = $cbitem['it_discount_price'];
                    $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                }
                $totalCate001 = "SELECT io_order_no FROM {$g5['g5_shop_item_option_table']} WHERE it_id = '{$cbitem['it_id']}' LIMIT 1";
                $totalCate001_1= sql_fetch($totalCate001); 
                $totalCate002 = "SELECT * FROM {$g5['g5_shop_item_table']} a LEFT JOIN {$g5['g5_shop_item_option_table']} b ON (a.it_id = b.it_id) WHERE b.io_order_no = '{$totalCate001_1['io_order_no']}' AND it_use = 1  ORDER BY it_price ASC, it_size_info DESC";
                $totalCate002_2 = sql_query($totalCate002); 
            ?>
                <li class="swiper-slide">
                    <a href="/shop/item.php?it_id=<?= $cbitem['it_id'] ?>">
                        <div class="swiper_item_img" data-id=<?= $cbitem['it_id'] ?> style="background-image: url(<?= $cbthumb ?>);"></div>
                        <span class="btn-pick-heart <?= in_array($cbitem['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $cbitem['it_id'] ?>></span>
                        <div class="swiper_item_detail">
                            <div class="swiper_item_brand"><?= $cbitem['it_brand'] ?> 
                            <? for ($tcs = 0; $tcR = sql_fetch_array($totalCate002_2); $tcs++) : 
                                if ($tcR['it_soldout'] == 1 || $tcR['io_stock_qty'] < 1 ) { ?>
                                    <span class ='hocOutName<?= $tcR['io_hoching'] ?>'></span>
                                <? } else {?>
                                    <span class ='hocName<?= $tcR['io_hoching'] ?>'></span>
                                <? }
                                ?>
                            <? endfor; 
                                $oneSize = '원 ~';
                                if ($tcs == 1) $oneSize = '원'
                            ?>
                            </div>
                            <div class="swiper_item_name"><?= $cbitem['it_name'] ?></div>
                            <div class="swiper_item_price_area">
                                <span><?= display_price(get_price($cbitem), $cbitem['it_tel_inq']); ?><span style="font-size: 12px;"><?= $oneSize ?></span>
                                    <? if ($discount_ratio > 0) : ?>
                                        <span class="price-del on-big"><del><?= number_format($it_price + $it_sale_price) ?></del>원<span>
                                                <span class="price-dis on-big" style="color: #e65026;"><?= number_format($discount_ratio) ?>%</span>
                                        <p class="price-del on-small"><del><?= number_format($it_price + $it_sale_price) ?></del>원
                                            <span class="price-dis" style="color: #e65026;"><?= number_format($discount_ratio) ?>%</span></p>
                                            <? endif ?>
                            </div>
                            <?php
                            $it_view_list_items = ',' . $cbitem['it_view_list_items'] . ',';
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
        <!-- <div class="on-big category_best_pagination swiper-pagination swiper-pagination-black"></div> -->
        <div class="on-big swiper-button-next swiper-button-black"></div>
        <div class="on-big swiper-button-prev swiper-button-black"></div>
    </div>
    <script>
        var deviceChk = 4;
        var mobileKeyWords = new Array('iPhone', 'iPod', 'BlackBerry', 'Android', 'Windows CE', 'LG', 'MOT', 'SAMSUNG', 'SonyEricsson');

        if ((navigator.userAgent.indexOf('Mac OS') > 0 && navigator.userAgent.indexOf('Safari') <= 0) || ((navigator.userAgent.indexOf('Android') > 0 && navigator.userAgent.indexOf('wv)') > 0))) {
            // 모바일 앱 웹뷰 
            deviceChk = 2.5;
        } else {
            // 모바일웹 
            deviceChk = 2.5;
            for (var word in mobileKeyWords) {
                if (navigator.userAgent.match(mobileKeyWords[word]) != null) {
                    deviceChk = 2.5;
                    break;
                } else {
                    if ($(window).width() < 700) {
                        deviceChk = 2.5;
                    } else {
                        deviceChk = 4;
                    }
                }
            }
        }
        var swiper_sd = new Swiper('#category_best_swiper', {
            slidesPerView: deviceChk,
            slidesPerGroup: 1,
            centeredSlides: false,
            spaceBetween: 20,
            grabCursor: false,
            // pagination: {
            //     el: '.category_best_pagination',
            //     clickable: true,
            // },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            cssMode: false,
            loop: false,
            keyboard: false,
        });
    </script>

    <? if ($item_relation_count) : ?>
        <div class="product_detail_footers_items">MD 추천, 함께 구매하면 좋은 아이템</div>
        <div class="main_front_item_area swiper-container" id="md_best_swiper">
            <ul class="swiper-wrapper">
                <?php

                $sql_category_md_best = " select c.io_hoching , b.*
            from {$g5['g5_shop_item_relation_table']} a
            left join {$g5['g5_shop_item_table']} b on (a.it_id2=b.it_id)
            LEFT JOIN lt_shop_item_option AS c ON (b.it_id = c.it_id) 
            where a.it_id = '$it_id'
            order by ir_no asc ";
                // $result = sql_query($sql);

                $sql_category_md_best_item = $sql_category_md_best . " LIMIT 20 ";
                $db_category_md_best_item = sql_query($sql_category_md_best_item);


                while (($cbitem = sql_fetch_array($db_category_md_best_item)) != false) {
                    $cbthumb = get_it_thumbnail_path($cbitem['it_img1'], 600, 600);
                    if ($cbitem['it_discount_price'] != '' && $cbitem['it_discount_price'] != '0') {
                        $it_price = $cbitem['it_price'];
                        $it_sale_price = $cbitem['it_discount_price'];
                        $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                    }
                ?>
                    <li class="swiper-slide">
                        <a href="/shop/item.php?it_id=<?= $cbitem['it_id'] ?>">
                            <div class="swiper_item_img" data-id=<?= $cbitem['it_id'] ?> style="background-image: url(<?= $cbthumb ?>);"></div>
                            <span class="btn-pick-heart <?= in_array($cbitem['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $cbitem['it_id'] ?>></span>
                            <div class="swiper_item_detail">
                                <div class="swiper_item_brand"><?= $cbitem['it_brand'] ?> <span class ='hocName<?= $cbitem['io_hoching'] ?>'></span></div>
                                <div class="swiper_item_name"><?= $cbitem['it_name'] ?></div>
                                <div class="swiper_item_price_area">
                                    <span><?= display_price(get_price($cbitem), $cbitem['it_tel_inq']); ?><span style="font-size: 12px;">원</span>
                                        <? if ($discount_ratio > 0) : ?>
                                            <span class="price-del"><del><?= number_format($it_price + $it_sale_price) ?></del>원<span>
                                                    <span class="price-dis" style="color: #e65026;"><?= number_format($discount_ratio) ?>%</span>
                                                <? endif ?>
                                </div>
                                <?php
                                $it_view_list_items = ',' . $cbitem['it_view_list_items'] . ',';
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
            <!-- <div class="on-big md_best_pagination swiper-pagination swiper-pagination-black"></div> -->
            <div class="on-big swiper-button-next swiper-button-black"></div>
            <div class="on-big swiper-button-prev swiper-button-black"></div>
        </div>
        <script>
            var deviceChk = 4;
            var mobileKeyWords = new Array('iPhone', 'iPod', 'BlackBerry', 'Android', 'Windows CE', 'LG', 'MOT', 'SAMSUNG', 'SonyEricsson');

            if ((navigator.userAgent.indexOf('Mac OS') > 0 && navigator.userAgent.indexOf('Safari') <= 0) || ((navigator.userAgent.indexOf('Android') > 0 && navigator.userAgent.indexOf('wv)') > 0))) {
                // 모바일 앱 웹뷰 
                deviceChk = 2.5;
            } else {
                // 모바일웹 
                deviceChk = 2.5;
                for (var word in mobileKeyWords) {
                    if (navigator.userAgent.match(mobileKeyWords[word]) != null) {
                        deviceChk = 2.5;
                        break;
                    } else {
                        if ($(window).width() < 700) {
                            deviceChk = 2.5;
                        } else {
                            deviceChk = 4;
                        }
                    }
                }
            }
            var swiper_sd = new Swiper('#md_best_swiper', {
                slidesPerView: deviceChk,
                slidesPerGroup: 1,
                centeredSlides: false,
                spaceBetween: 20,
                grabCursor: false,
                // pagination: {
                //     el: '.md_best_pagination',
                //     clickable: true,
                // },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                cssMode: false,
                loop: false,
                keyboard: false,
            });
        </script>
    <? endif ?>



    <div class="product_detail_footers_items">지금 장바구니에 가장 많이 담긴 아이템</div>
    <div class="main_front_item_area swiper-container" id="cart_best_swiper">
        <ul class="swiper-wrapper">
            <?php
            $sql_cart_best = "SELECT io.io_hoching ,  cb.* 
                            FROM 
                            (SELECT  b.* 
                            FROM 
                            (SELECT COUNT(a.it_id) cart_cnt , a.* FROM lt_shop_cart a GROUP BY a.it_id ORDER BY  cart_cnt DESC LIMIT 20) AS SC
                            LEFT JOIN lt_shop_item b ON (SC.it_id=b.it_id)) AS cb
                            LEFT JOIN lt_shop_item_option io ON (cb.it_id=io.it_id)
                            WHERE cb.it_use=1  AND io.io_use= 1 AND it_total_size = 1
                            ";
            $db_cart_best_item = sql_query($sql_cart_best);


            while (($cabitem = sql_fetch_array($db_cart_best_item)) != false) {
                $cabthumb = get_it_thumbnail_path($cabitem['it_img1'], 600, 600);
                if ($cabitem['it_discount_price'] != '' && $cabitem['it_discount_price'] != '0') {
                    $it_price = $cabitem['it_price'];
                    $it_sale_price = $cabitem['it_discount_price'];
                    $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                }

                $totalCart001 = "SELECT io_order_no FROM {$g5['g5_shop_item_option_table']} WHERE it_id = '{$cabitem['it_id']}' LIMIT 1";
                $totalCart001_1= sql_fetch($totalCart001); 
                $totalCart002 = "SELECT * FROM {$g5['g5_shop_item_table']} a LEFT JOIN {$g5['g5_shop_item_option_table']} b ON (a.it_id = b.it_id) WHERE b.io_order_no = '{$totalCart001_1['io_order_no']}' AND it_use = 1  ORDER BY it_price ASC, it_size_info DESC";
                $totalCart002_2 = sql_query($totalCart002); 


            ?>
                <li class="swiper-slide">
                    <a href="/shop/item.php?it_id=<?= $cabitem['it_id'] ?>">
                        <div class="swiper_item_img" data-id=<?= $cabitem['it_id'] ?> style="background-image: url(<?= $cabthumb ?>); background-size:cover;"></div>
                        <span class="btn-pick-heart <?= in_array($cabitem['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $cabitem['it_id'] ?>></span>
                        <div class="swiper_item_detail">
                            <div class="swiper_item_brand"><?= $cabitem['it_brand'] ?> 
                            <? for ($tcts = 0; $tctR = sql_fetch_array($totalCart002_2); $tcts++) : 
                                if ($tctR['it_soldout'] == 1 || $tctR['io_stock_qty'] < 1 ) { ?>
                                    <span class ='hocOutName<?= $tctR['io_hoching'] ?>'></span>
                                <? } else {?>
                                    <span class ='hocName<?= $tctR['io_hoching'] ?>'></span>
                                <? }
                                ?>
                            <? endfor; 
                                $oneSize = '원 ~';
                                if ($tcts == 1) $oneSize = '원'
                            ?>
                            </div>
                            <div class="swiper_item_name"><?= $cabitem['it_name'] ?></div>
                            <div class="swiper_item_price_area">
                                <span><?= display_price(get_price($cabitem), $cabitem['it_tel_inq']); ?><span style="font-size: 12px;"><?= $oneSize ?></span>
                                    <? if ($discount_ratio > 0) : ?>
                                        <span class="price-del on-big"><del><?= number_format($it_price + $it_sale_price) ?></del>원</span>
                                                <span class="price-dis on-big" style="color: #e65026;"><?= number_format($discount_ratio) ?>%</span>
                                        <p class="price-del on-small"><del><?= number_format($it_price + $it_sale_price) ?></del>원
                                            <span class="price-dis" style="color: #e65026;"><?= number_format($discount_ratio) ?>%</span></p>
                                            <? endif ?>
                            </div>
                            <?php
                            $it_view_list_items = ',' . $cabitem['it_view_list_items'] . ',';
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
        <!-- <div class="on-big cart_best_pagination swiper-pagination swiper-pagination-black"></div> -->
        <div class="on-big swiper-button-next swiper-button-black"></div>
        <div class="on-big swiper-button-prev swiper-button-black"></div>
    </div>
    <script>
        var deviceChk = 4;
        var mobileKeyWords = new Array('iPhone', 'iPod', 'BlackBerry', 'Android', 'Windows CE', 'LG', 'MOT', 'SAMSUNG', 'SonyEricsson');

        if ((navigator.userAgent.indexOf('Mac OS') > 0 && navigator.userAgent.indexOf('Safari') <= 0) || ((navigator.userAgent.indexOf('Android') > 0 && navigator.userAgent.indexOf('wv)') > 0))) {
            // 모바일 앱 웹뷰 
            deviceChk = 2.5;
        } else {
            // 모바일웹 
            deviceChk = 2.5;
            for (var word in mobileKeyWords) {
                if (navigator.userAgent.match(mobileKeyWords[word]) != null) {
                    deviceChk = 2.5;
                    break;
                } else {
                    if ($(window).width() < 700) {
                        deviceChk = 2.5;
                    } else {
                        deviceChk = 4;
                    }
                }
            }
        }
        var swiper_sd = new Swiper('#cart_best_swiper', {
            slidesPerView: deviceChk,
            slidesPerGroup: 1,
            centeredSlides: false,
            spaceBetween: 20,
            grabCursor: false,
            // pagination: {
            //     el: '.cart_best_pagination',
            //     clickable: true,
            // },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            cssMode: false,
            loop: false,
            keyboard: false,
        });
    </script>
    <?php
    $history_mem_id = $is_member ? $member['mb_id'] : session_id();

    $sql_user_history = "SELECT c.io_hoching , b.* 
                            FROM lt_history AS a
                            LEFT JOIN lt_shop_item b ON (a.it_id=b.it_id) 
                            LEFT JOIN lt_shop_item_option c ON (a.it_id=c.it_id)
                            WHERE b.it_use=1  AND c.io_use= 1
                            AND a.mb_id = '{$history_mem_id}'
                            LIMIT 20
                            ";
    $db_sql_user_history_item = sql_query($sql_user_history);

    //$g5_user_history_member = user_history("list", array("mb_id" => $history_mem_id));

    ?>
    <?php if (count($db_sql_user_history_item) > 0) : ?>
        <div class="on-small product_detail_footers_items">최근 본 상품</div>

        <div class="main_front_item_area swiper-container on-small" id="history_best_swiper">
            <ul class="swiper-wrapper">
                <?php if (count($db_sql_user_history_item) > 0) : ?>
                    <?php while (($hsitem = sql_fetch_array($db_sql_user_history_item)) != false) {

                        $unthumb = get_it_thumbnail_path($hsitem['it_img1'], 250, 250);
                        if ($hsitem['it_discount_price'] != '' && $hsitem['it_discount_price'] != '0') {
                            $it_price = $hsitem['it_price'];
                            $it_sale_price = $hsitem['it_discount_price'];
                            $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                        }

                        $totalLook001 = "SELECT io_order_no FROM {$g5['g5_shop_item_option_table']} WHERE it_id = '{$hsitem['it_id']}' LIMIT 1";
                        $totalLook001_1= sql_fetch($totalLook001); 
                        $totalLook002 = "SELECT * FROM {$g5['g5_shop_item_table']} a LEFT JOIN {$g5['g5_shop_item_option_table']} b ON (a.it_id = b.it_id) WHERE b.io_order_no = '{$totalLook001_1['io_order_no']}' AND it_use = 1  ORDER BY it_price ASC, it_size_info DESC";
                        $totalLook002_2 = sql_query($totalLook002); 
                    ?>
                        <li class="swiper-slide">
                            <a href="/shop/item.php?it_id=<?= $hsitem['it_id'] ?>">
                                <div class="swiper_item_img" data-id=<?= $hsitem['it_id'] ?> style="background-image: url(<?= $unthumb ?>);"></div>
                                <span class="btn-pick-heart <?= in_array($hsitem['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $hsitem['it_id'] ?>></span>
                                <div class="swiper_item_detail">
                                    <div class="swiper_item_brand"><?= $hsitem['it_brand'] ?> 
                                    <? for ($tls = 0; $tlR = sql_fetch_array($totalLook002_2); $tls++) : 
                                        if ($tlR['it_soldout'] == 1 || $tlR['io_stock_qty'] < 1 ) { ?>
                                            <span class ='hocOutName<?= $tlR['io_hoching'] ?>'></span>
                                        <? } else {?>
                                            <span class ='hocName<?= $tlR['io_hoching'] ?>'></span>
                                        <? }
                                        ?>
                                    <? endfor; 
                                        $oneSize = '원 ~';
                                        if ($tls == 1) $oneSize = '원'
                                    ?>
                                    </div>
                                    <div class="swiper_item_name"><?= $hsitem['it_name'] ?></div>
                                    <div class="swiper_item_price_area">
                                        <span><?= display_price(get_price($hsitem), $hsitem['it_tel_inq']); ?><span style="font-size: 12px;"><?= $oneSize ?></span>
                                            <? if ($discount_ratio > 0) : ?>
                                                <span class="price-del on-big"><del><?= number_format($it_price + $it_sale_price) ?></del>원</span>
                                                <span class="price-dis on-big" style="color: #e65026;"><?= number_format($discount_ratio) ?>%</span>
                                                <p class="price-del on-small"><del><?= number_format($it_price + $it_sale_price) ?></del>원
                                                        <span class="price-dis" style="color: #e65026;"><?= number_format($discount_ratio) ?>%</span></p>
                                                    <? endif ?>
                                    </div>
                                    <?php
                                    $it_view_list_items = ',' . $hsitem['it_view_list_items'] . ',';
                                    $view_sale = (preg_match("/,할인블릿,/i", $it_view_list_items));
                                    ?>
                                    <div class="swiper_item_sale">
                                        <img src="/img/re/sale.png" style="opacity : <?= $view_sale ?>" srcset="/img/re/sale@2x.png 2x,/img/re/sale@3x.png 3x">
                                    </div>
                                </div>
                            </a>
                        </li>

                    <? } ?>
                <? endif ?>
            </ul>
            <!-- <div class="on-big history_best_pagination swiper-pagination swiper-pagination-black"></div> -->
            <div class="on-big swiper-button-next swiper-button-black"></div>
            <div class="on-big swiper-button-prev swiper-button-black"></div>
        </div>
        <script>
            var swiper_sd = new Swiper('#history_best_swiper', {
                slidesPerView: 2.5,
                slidesPerGroup: 4,
                centeredSlides: false,
                spaceBetween: 20,
                grabCursor: false,
                // pagination: {
                //     el: '.history_best_pagination',
                //     clickable: true,
                // },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                cssMode: false,
                loop: false,
                keyboard: false,
            });
        </script>
    <? endif ?>
</div>
<!-- 
<div id="mobile-options-wrapper">
    <div id="btn-toggle-mobile-options" style="display : none"></div>
    <div id="btn-toggle-mobile-options-block"></div>
    <?php if ($sup_its_count > 0) : ?>
        <tr>
            <td style="padding: 8px 0">
                <?php foreach ($sup_its as $sup_group => $sup_group_its) : ?>
                    <select class="product-detail-item-option" data-no="supply" data-price=0 data-supply=true data-its-no=<?= $io['its_no'] ?>>
                        <option value=""><?= $sup_group ?></option>
                        <?php foreach ($sup_group_its as $io) : ?>
                            <option value="<?= $io['io_price'] ?>" <?php printf("data-no='%s' data-id='%s' data-stock='%s' data-option-price='%s'", $io['io_no'], $io['name'], $io['io_stock_qty'], $io['io_price']); ?> <?= $io['io_noti_qty'] <= 0 ? "disabled" : "" ?>><?= $io['name'] ?><?= $io['io_price'] > 0 ? "(+" . number_format($io['io_price']) . "원)" : "" ?><?= $io['io_noti_qty'] <= 0 ? " - 품절" : "" ?></option>
                        <?php endforeach ?>
                    </select>
                <?php endforeach ?>
            </td>
        </tr>
    <?php endif ?>
    <?php if ($sub_its_count > 0) : ?>
        <tr><td><?= $it['it_name'] ?></td></tr>
        <?php foreach ($sub_its as $sub_group => $sub_group_its) : ?>
            <tr>
                <td style="padding: 8px 0">
                    <?php foreach ($sub_group_its as $sub_group_it) : ?>
                        <select style="display:none;" class="product-detail-item-option mo-order-select" <?php printf("data-group='%s' data-no='%s' data-its-no='%s' data-item='%s' data-price='%s' data-rental_price='%s'", $sub_group, $sub_group_it['its_no'], $sub_group_it['its_no'], $sub_group_it['its_item'], $sub_group_it['its_final_price'], $sub_group_it['its_final_rental_price']); ?>>
                            <option value=""><?= $sub_group ?> 선택</option>
                            <?php foreach ($sub_group_it['OPTIONS'] as $io) : ?>
                                <option value="<?= $io['io_price'] ?>" selected <?php printf("data-no='%s' data-id='%s' data-stock='%s' data-option-price='%s'", $io['io_no'], $io['io_id'], $io['io_stock_qty'], $io['io_price']); ?> <?= $io['io_noti_qty'] <= 0 ? "disabled" : "" ?>><?= $io['io_id'] ?><?= $io['io_price'] > 0 ? "(+" . number_format($io['io_price']) . "원)" : "" ?><?= $io['io_noti_qty'] <= 0 ? " - 품절" : "" ?></option>
                            <?php endforeach ?>
                        </select>
                    <?php endforeach ?>
                </td>
            </tr>
        <?php endforeach ?>
    <?php endif ?>
    <ul id="mobile-options"></ul>
    <div id="mobile-options-total">
        총 <span id="product-item-order-price-total-mobile">0</span>원
    </div>
</div> -->
<? if ($is_orderable || $is_totalsoldout) : ?>
    <div class="on-small btn-order-mobile-group" id="btn-order-mobile" style="position: fixed; bottom: 0; width: 100%;z-index: 1051;">
        <button class="btn btn-order-mobile" onclick="cartUpdate(0,'<?= $member['mb_id'] ?>')">장바구니</button><button class="btn btn-order-mobile baro" onclick="cartUpdate(1,'<?= $member['mb_id'] ?>')">바로구매</button>
    </div>
<? else : ?>
    <div class="on-small btn-order-mobile-group-instock" id="btn-order-mobile" style="position: fixed; bottom: 0; width: 100%;z-index: 1000;">
        <button type="button" id="product-detail-instock" class="btn btn-cart-action btn-toggle-instock" data-screen="mobile" data-mb_id="<?= $member['mb_id'] ?>" data-brand="<?= $it['it_brand'] ?>" data-item="<?= $it['it_id'] ?>" data-name="<?= $it['it_name'] ?>" style="margin-top: 16px; border-radius: 2px;">재입고알림</button>
    </div>
<? endif ?>


<!-- 모달 start -->
<!-- 필터 모달 -->

<div class="modal fade modal-product-detail" id="modal-mobile-order-select" tabindex="-1" role="dialog" aria-labelledby="btn-modal-mobile-order-select" aria-hidden="true" style="max-width: unset; min-width: unset;">
    <div class="modal-dialog" role="document" style="margin:0;">
        <div id="modal-mobile-order-select-content" class="modal-product-detail-content modal-content" style="height: auto;">
            <div class="modal_header">상품선택
                <img onclick="modaldel()" src="/img/re/cancle.png" srcset="/img/re/cancle@2x.png 2x,/img/re/cancle@3x.png 3x" data-dismiss="modal">
            </div>
            <div class="modal_body">
                <div style="margin : 10px">
                    <div id="btn-toggle-mobile-options" style="display : none"></div>
                    <?php for($sdi=0; $sdm=sql_fetch_array($sizeDataMo); $sdi++) { 
                            if ($sdm['it_soldout'] == 1 || $sdm['io_stock_qty'] < 1 ) { ?>
                                 <input type="radio" id ="modal_<?= $sdm['io_hoching']?>" disabled><label for="modal_<?= $sdm['io_hoching']?>" style="background: #eee; color: #757575; border-color: #ccc; text-decoration: line-through;" disabled><div><?= $sdm['io_hoching'] ?></div></label>
                            <?php } else {
                                if (is_numeric(substr($sdm['io_hoching'],0,1))) $raIdMo = 'n'.str_replace('*','',$sdm['io_hoching']); else $raIdMo = $sdm['io_hoching'];
                                ?>
                                <input type="radio" id ="modal_<?= $raIdMo; ?>" name="sizePick" data-price="<?= $sdm['it_price'] ?>" data-itsno="<?= $sdm['its_no'] ?>" data-iono="<?= $sdm['io_no'] ?>" data-ioid="<?= $sdm['io_id'] ?>" data-itid="<?= $sdm['it_id'] ?>" data-ioprice="<?= $sdm['io_price'] ?>" data-sizeidview="<?= $sdm['io_hoching'] ?>" data-totalimg="<?= $sns_image ?>" data-itname ="<?= $it['it_name'] ?>"><label for="modal_<?= $raIdMo ?>"><div><?= $sdm['io_hoching'] ?></div></label>
                            
                                <?php } 
                                } ?>
                        <br>
                    <?php if ($sup_its_count > 0) : ?>
                        <tr>
                            <td style="padding: 8px 0">
                                <?php foreach ($sup_its as $sup_group => $sup_group_its) : ?>
                                    <select class="product-detail-item-option" data-no="supply" data-price=0 data-supply=true data-its-no=<?= $io['its_no'] ?>>
                                        <option value=""><?= $sup_group ?></option>
                                        <?php foreach ($sup_group_its as $io) : ?>
                                            <option value="<?= $io['io_price'] ?>" <?php printf("data-no='%s' data-id='%s' data-stock='%s' data-option-price='%s'", $io['io_no'], $io['name'], $io['io_stock_qty'], $io['io_price']); ?> <?= $io['io_noti_qty'] <= 0 ? "disabled" : "" ?>><?= $io['name'] ?><?= $io['io_price'] > 0 ? "(+" . number_format($io['io_price']) . "원)" : "" ?><?= $io['io_noti_qty'] <= 0 ? " - 품절" : "" ?></option>
                                        <?php endforeach ?>
                                    </select>
                                <?php endforeach ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <div class="product-info-seperator3 on-small" id ="totalLineMo1" style="margin-top: 5px; display: none;"></div>
                    <div class='optionDivMo'>                  
                    </div>
                    <br>
                    <div class="product-info-seperator3 on-small" id ="totalLineMo2" style="display: none; margin-top: -11px;"></div>
                    <div id="mobile-options-total" style="display: none;">
                        총 <span id="product-item-order-price-total-mobile">0</span>원
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<input type="hidden" name="abled_coupon_list" value="<?= count($coupons) ?>" id="abled_coupon_list">
<div class="modal fade modal-product-detail" id="modal-coupon-down" tabindex="-1" role="dialog" aria-labelledby="btn-modal-coupon-down" aria-hidden="true" style="max-width: unset; min-width: unset;">
    <div class="modal-dialog" role="document">
        <div id="modal-coupon-down-content" class="modal-product-detail-content modal-content" style="height: auto;">
            <div class="modal_header">쿠폰받기
                <img src="/img/re/cancle.png" srcset="/img/re/cancle@2x.png 2x,/img/re/cancle@3x.png 3x" data-dismiss="modal">
            </div>
            <div class="modal_body">

                <?php foreach ($coupons as $ci => $cival) : ?>
                    <?php
                    if ($cival['cz_period'] != 31) {
                        $All_coupon[$ci]["id"] = $cival['cz_id'];
                        $All_coupon[$ci]["count"] = $cival['cz_download_user_limit'];
                    }
                    ?>

                    <? if ($cival['cp_method'] == 0 && $cival['cz_period'] != 31) : $itType = 1; ?>
                        <? if ($itType == 1) : ?>
                            <div class="coupon_type_title">상품쿠폰</div>
                        <? $itType++;
                        endif ?>
                        <? if ($cival['cp_maximum'] ) :
                            // r rdk sk

                        endif ?>
                        <div class="coupon_down_zone">
                            <div class="discount"><?= $cival['cp_price'] ?><?= $cival['cp_type'] == 1 ? '%' : '원' ?></div>
                            <div class="cz_name"><?= $cival['cz_subject'] ?> (<font class="coupon_allow_cnt"><?= is_coupon_downloaded($member['mb_id'], $cival['cz_id']) == 1 ? '0' : $cival['cz_download_user_limit'] ?> </font> / <?= $cival['cz_download_user_limit'] ?>)</div>
                            <div class="down_load_btn <?= is_coupon_downloaded($member['mb_id'], $cival['cz_id']) == 1 ? 'disabled' : '' ?>" data-id="<?= $cival['cz_id'] ?>" data-user-limit="<?= $cival['cz_download_user_limit'] ?>"><span class="down_able">다운로드</span><span class="down_disable">발급완료</span></div>
                        </div>
                    <? endif ?>
                    <? if ($cival['cp_method'] == 4 && $cival['cz_period'] != 31) : $bType = 1; ?>
                        <? if ($bType == 1) : ?>
                            <div class="coupon_type_title">브랜드쿠폰</div>
                        <? $bType++;
                        endif ?>
                        <div class="coupon_down_zone">
                            <div class="discount"><?= $cival['cp_price'] ?><?= $cival['cp_type'] == 1 ? '%' : '원' ?></div>
                            <div class="cz_name"><?= $cival['cz_subject'] ?> (<font class="coupon_allow_cnt"><?= is_coupon_downloaded($member['mb_id'], $cival['cz_id']) == 1 ? '0' : $cival['cz_download_user_limit'] ?></font> / <?= $cival['cz_download_user_limit'] ?>)</div>
                            <div class="down_load_btn <?= is_coupon_downloaded($member['mb_id'], $cival['cz_id']) == 1 ? 'disabled' : '' ?>" data-id="<?= $cival['cz_id'] ?>" data-user-limit="<?= $cival['cz_download_user_limit'] ?>"><span class="down_able">다운로드</span><span class="down_disable">발급완료</span></div>
                        </div>
                    <? endif ?>

                    <? if ($cival['cp_method'] == 11 && $cival['cz_period'] != 31) : $fType = 1; ?>
                        <? if ($fType == 1) : ?>
                            <div class="coupon_type_title">플러스쿠폰</div>
                        <? $fType++;
                        endif ?>
                        <div class="coupon_down_zone">
                            <div class="discount"><?= $cival['cp_price'] ?><?= $cival['cp_type'] == 1 ? '%' : '원' ?></div>
                            <div class="cz_name"><?= $cival['cz_subject'] ?> (<font class="coupon_allow_cnt"><?= is_coupon_downloaded($member['mb_id'], $cival['cz_id']) == 1 ? '0' : $cival['cz_download_user_limit'] ?></font> / <?= $cival['cz_download_user_limit'] ?>)</div>
                            <div class="down_load_btn <?= is_coupon_downloaded($member['mb_id'], $cival['cz_id']) == 1 ? 'disabled' : '' ?>" data-id="<?= $cival['cz_id'] ?>" data-user-limit="<?= $cival['cz_download_user_limit'] ?>"><span class="down_able">다운로드</span><span class="down_disable">발급완료</span></div>
                        </div>
                    <? endif ?>
                    <? if ($cival['cp_method'] == 2 && $cival['cz_period'] != 31) : $jType = 1; ?>
                        <? if ($jType == 1) : ?>
                            <div class="coupon_type_title">장바구니쿠폰</div>
                        <? $jType++;
                        endif ?>
                        <div class="coupon_down_zone">
                            <div class="discount"><?= $cival['cp_price'] ?><?= $cival['cp_type'] == 1 ? '%' : '원' ?></div>
                            <div class="cz_name"><?= $cival['cz_subject'] ?> (<font class="coupon_allow_cnt"><?= is_coupon_downloaded($member['mb_id'], $cival['cz_id']) == 1 ? '0' : $cival['cz_download_user_limit'] ?></font> / <?= $cival['cz_download_user_limit'] ?>)</div>
                            <div class="down_load_btn <?= is_coupon_downloaded($member['mb_id'], $cival['cz_id']) == 1 ? 'disabled' : '' ?>" data-id="<?= $cival['cz_id'] ?>" data-user-limit="<?= $cival['cz_download_user_limit'] ?>"><span class="down_able">다운로드</span><span class="down_disable">발급완료</span></div>
                        </div>
                    <? endif ?>


                <? endforeach  ?>
            </div>

            <div class="coupon-down-btn-group product-detaill-btn-group">
                <button type="button" class="ALL_coupon_down_load" style="color: #ffffff; background-color: #333333;" data-coupons=<?= json_encode($All_coupon, JSON_FORCE_OBJECT) ?>>전체쿠폰다운로드<img style="transform: translate(5px, -3px);" srcset="/img/re/coupon-download.png 1x, /img/re/coupon-download@2x.png 2.5x/img/re/coupon-download@3x.png 4x"></button>
            </div>

        </div>
    </div>
</div>




<style>
    #product-detail-instock {
        width: 100%;
        height: 52px;
        background-color: #333333;
        font-size: 16px;
        font-weight: 500;
        line-height: 52px;
        text-align: center;
        color: #ffffff;
        margin: 0;
        padding: 0;
    }

    .modal-product-detail {
        height: auto;
    }

    .modal-product-detail .modal-dialog {
        width: 100%;
        margin-top: 300px;
        margin-left: 50%;
        padding: 0;
    }

    .modal-product-detail-content {
        width: 500px;
        border-radius: 2px;
        margin: 0;
        padding: 0 !important;
        bottom: 0px;
    }

    .modal-product-detail-content .modal_header {
        height: 73px;
        line-height: 73px;
        text-align: center;
        font-size: 26px;
        font-weight: bold;
        color: #090909;
        position: relative;
        border-bottom: 2px solid #e0e0e0;
    }

    .modal-product-detail-content .modal_header img {
        position: absolute;
        top: 50%;
        right: 7px;
        transform: translate(-50%, -50%);
    }

    .coupon-down-btn-group.product-detaill-btn-group button {
        width: 340px;
        height: 52px;
        border-radius: 2px;
        border: solid 1px #333333;
        background-color: #333333;
        font-size: 18px;
        font-weight: 500;
        line-height: 52px;
        text-align: center;
        color: #ffffff;
        margin: 52px 80px;
    }

    #modal-alert-wrapper {
        margin-top: 350px;
    }

    @media (max-width: 1366px) {
        #modal-instock-noti-mobile .modal-dialog {
            bottom: 0px !important;
        }

        .modal-product-detail {
            height: 100vh;
        }

        .modal-product-detail .modal-dialog {
            width: 100%;
            margin: 0 !important;
            padding: 0;
        }

        .modal-product-detail-content {
            width: 100%;
            border-radius: 20px 20px 0 0;
            margin: 0;
            padding: 0 !important;
            position: fixed;
            bottom: 0px;
        }

        #modal-mobile-order-select-content {
            bottom: 50px;
        }

        #modal-mobile-order-select-content .modal_body {
            font-size: 14px;
            line-height: normal;
            letter-spacing: normal;
            color: #424242;
        }

        #modal-mobile-order-select-content #mobile-options li {
            background-color: #ffffff;
        }

        #modal-mobile-order-select-content #mobile-options li {
            background-color: #ffffff;
        }

        #modal-mobile-order-select-content #mobile-options li .order-item-price {
            font-size: 14px;
            font-weight: 500;
            line-height: normal;
            text-align: right;
            color: #000000;
        }

        .modal-product-detail-content .modal_header {
            height: 50px;
            line-height: 50px;
            text-align: center;
            font-size: 18px;
            font-weight: 500;
            color: #090909;
            position: relative;
            border-bottom: 1px solid #e0e0e0;
        }

        .modal-product-detail-content .modal_header img {
            position: absolute;
            top: 50%;
            right: 7px;
            transform: translate(-50%, -50%);
        }

        .modal-product-detail-content .modal_body {
            display: grid;
            max-height: calc(100vh - 180px);
            overflow-x: scroll;
        }

        .modal-product-detail-content .product-detaill-row {
            border-top: 1px solid #e0e0e0;
        }

        .modal-product-detail-content .product-detaill-row .custom-title {
            margin: 20px 0 0 14px;
        }

        .modal-product-detail-content .product-detaill-row.product-detaill-row-sub {
            margin-left: 2px;
            margin-top: 8px;
            border: none;
            padding-bottom: 6px;
        }

        .modal-product-detail-content .product-detaill-btn-group {
            margin: 0 14px;
            display: flex;
            justify-content: space-around;
            margin-bottom: 40px;
        }

        .modal-product-detail-content .product-detaill-btn-group button {
            width: calc((100vw - 42px) / 2);
            height: 44px;
            text-align: center;
            font-size: 14px;
            font-weight: 500;
            border-radius: 2px;
            border: solid 1px #333333;
            line-height: 40px;
            letter-spacing: normal;
            margin: 0;
        }

        .modal-product-detail-content .product-detaill-row.product-detaill-row-sub .custom-checkbox {
            width: 50%;
            float: left;
            height: 20px;
            font-size: 14px;
            font-weight: normal;
            font-style: normal;
            line-height: 20px;
            color: #777777;
            margin-bottom: 18px;
        }

        .modal-product-detail-content .product-detaill-row .custom-title {
            height: 20px;
            font-size: 14px;
            font-weight: 500;
            font-stretch: normal;
            line-height: 20px;
            color: #333333;
        }

        #modal-alert-wrapper {
            margin-top: 50%;
        }
    }
</style>
<!-- 모달 end -->

<script>
    function coupon_modal(member) {
        var coupon_count = $('#abled_coupon_list').val();
        if (member) {
            if (coupon_count > 0) {
                $('#mobile-options-wrapper').css('display', 'none');
                $('.btn-order-mobile-group').css('display', 'none');
                $("#modal-coupon-down").modal('show');
            } else {
                alert("다운로드 가능한 쿠폰이 없습니다.");
                return;
            }
        } else {
            alert("로그인 후 다운로드 할 수 있습니다.");
            window.location = '/auth/login.php';
        }
    }
    $("input:radio[name=sizePickOpt]").click(function(){
        let maxId = this.id;
        let maxSet = document.querySelector('#'+maxId).dataset;
        let maxNum = maxSet.num;

        $("div[id^='modal-max-sale-body']").css('display', 'none');
        $("#modal-max-sale-body"+maxNum).css('display', 'block');


    })

    $("input:radio[name=sizePick]").click(function(){ 
        let sizeId = this.id;
        let sizeOri = sizeId.substring(6,);
        let mobileYn = 0;
        if (sizeId.indexOf('modal') != -1) mobileYn = 1;
      
        let dataSet = document.querySelector('#'+sizeId).dataset;
        let totalPrice = dataSet.price;
        let totalIoPrice = dataSet.ioprice;
        let totalIono = dataSet.iono;
        let totalItsno = dataSet.itsno;
        let totalIoid = dataSet.ioid;
        let totalItid = dataSet.itid;
        let sizeIdView = dataSet.sizeidview;
        let totalImg = dataSet.totalimg;
        let itName = dataSet.itname;
        let sizePrice = this.value;

        if (mobileYn) {
            let duplicateMo = $('#option_'+sizeId).length;
            if (duplicateMo > 0) {
                alert('이미 선택한 상품입니다. 추가구매가 필요한 경우 수량을 조절해주세요.');
                return false;
            }

            $("#mobile-options-total").css('display', '');
            if (!$("#product-item-order-price").hasClass("active")) $("#product-item-order-price").addClass("active");
            let newDivMo = document.createElement('div');
            newDivMo.setAttribute("id", "optionMo_"+sizeId);
            newDivMo.setAttribute("style","height: 96px; margin-top:7px")

            let newImgMo = document.createElement('div');
            newImgMo.setAttribute("class", "product-list-item-thumb-total-mo");
            newImgMo.setAttribute("style", "background-image: url("+totalImg+"); float:left; margin-right: 15px");
            // newImgMo.setAttribute("style", "background-image: url(https://lifelike.co.kr/data/item/030030020500324/thumb-105073_1_600x600.jpg); float:left; margin-right: 15px");
            newDivMo.appendChild(newImgMo);




            let newTextMo = document.createElement('div');
            newTextMo.setAttribute("style","float:left; width:210px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:inline-block; ")
            let newNameMo = document.createTextNode(itName);
            newTextMo.appendChild(newNameMo);
            newDivMo.appendChild(newTextMo);
            
            let newDelMo = document.createElement('span');
            newDelMo.setAttribute("style","text-align: right;")
            let newDelImgMo = document.createElement('img');
            newDelImgMo.setAttribute("src","/img/re/x.png");
            newDelImgMo.setAttribute("onclick","optionDelete(optionMo_"+sizeId+","+sizeOri+")");
            newDelImgMo.setAttribute("align","right");
            newDelMo.appendChild(newDelImgMo);

            newDivMo.appendChild(newDelMo);

            let newBrMo = document.createElement('br');
            newDivMo.appendChild(newBrMo);
            let newSizeMo = document.createTextNode(sizeIdView+'⠀⠀');
            newDivMo.appendChild(newSizeMo);
            let newBrMo2 = document.createElement('br');
            newDivMo.appendChild(newBrMo2);

            let newUlMo = document.createElement('ul');
            newUlMo.setAttribute("id", "product-item-order-options-mo-"+sizeId);
            newUlMo.setAttribute("class", "product-item-order-options");      
            newUlMo.setAttribute("style","float:left;");
            newDivMo.appendChild(newUlMo);      

            let newPriceDivMo = document.createElement('div');
            newPriceDivMo.setAttribute("style","float:left; margin-left:10px; margin-top:15px")
            newPriceDivMo.setAttribute("class","new-price-mo-"+sizeId)
            let newPriceMo = document.createTextNode(number_format(totalPrice)+'원');
            newPriceDivMo.appendChild(newPriceMo);
            newDivMo.appendChild(newPriceDivMo);

            let duplicateNumMo = $("div[id^='optionMo_']").length;
            if (duplicateNumMo > 0) {
                let tdLineMo = document.createElement('div');
                tdLineMo.setAttribute("class", "mobileLine");
                tdLineMo.setAttribute("style", "border-top: 1px solid #D8D8D8;");
                $(".optionDivMo").prepend(tdLineMo);
            }

            $(".optionDivMo").prepend(newDivMo);

            totalLineMo1.style.display = 'block';
            totalLineMo2.style.display = 'block';
        } else {
            let duplicate = $('#option_'+sizeId).length;
            if (duplicate > 0) {
                alert('이미 선택한 상품입니다. 추가구매가 필요한 경우 수량을 조절해주세요.');
                return false;
            }

        };
        let newDiv = document.createElement('div');
        let totalLine = document.getElementById("totalLine");
        if(totalLine.style.display=='none') totalLine.style.display = 'block';
        if (!$("#product-item-order-price").hasClass("active")) $("#product-item-order-price").addClass("active");
        newDiv.setAttribute("id", "option_"+sizeId);
        

        let newImg = document.createElement('div');
        newImg.setAttribute("class", "product-list-item-thumb-total");
        newImg.setAttribute("style", "background-image: url("+totalImg+"); background-size : cover; float:left; margin-right: 15px");
        // newImg.setAttribute("style", "background-image: url(https://lifelike.co.kr/data/item/030030020500324/thumb-105073_1_600x600.jpg); background-size : cover; float:left; margin-right: 15px");
        newDiv.appendChild(newImg);


        let newText = document.createElement('div');
        newText.setAttribute("style","float:left; width:470px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:inline-block;")
        newDiv.appendChild(newText);
        let newName = document.createTextNode(itName);
        newText.appendChild(newName);
        let newDel = document.createElement('span');
        newDel.setAttribute("style","text-align: right;")
        let newDelImg = document.createElement('img');
        newDelImg.setAttribute("src","/img/re/x.png");
        newDelImg.setAttribute("onclick","optionDelete(option_"+sizeId+")");
        newDelImg.setAttribute("style","margin-right:6px; margin-top:5px;");
        newDelImg.setAttribute("align","right");
        newDel.appendChild(newDelImg);
        newDiv.appendChild(newText);
        newDiv.appendChild(newDel);
        let newBr = document.createElement('br');
        newDiv.appendChild(newBr);
        let newSize = document.createTextNode(sizeIdView);
        newDiv.appendChild(newSize);
        let newBr2 = document.createElement('br');
        newDiv.appendChild(newBr2);
        let newUl = document.createElement('ul');
        newUl.setAttribute("id", "product-item-order-options-"+sizeId);
        newUl.setAttribute("class", "product-item-order-options");
        newUl.setAttribute("style","float:left;");
        newDiv.appendChild(newUl);
        let newPriceDiv = document.createElement('div');
        newPriceDiv.setAttribute("style","float:left; margin-left:10px; margin-top:1px;")
        newPriceDiv.setAttribute("class","new-price-"+sizeId)
        let newPrice = document.createTextNode(number_format(totalPrice)+'원');
        newPriceDiv.appendChild(newPrice);
        
        newDiv.appendChild(newPriceDiv);
        let duplicateNum = $("div[id^='option_']").length;
        if (duplicateNum > 0) {
            let newTd = document.createElement('td');
            newTd.setAttribute("colspan", "2");
            let tdLine = document.createElement('div');
            tdLine.setAttribute("style", "border-top: 1px solid #D8D8D8; width:671px;");
            newTd.appendChild(tdLine);
            newDiv.appendChild(newTd);
        }
        $(".optionDiv").prepend(newDiv)


 
        let optionWrapper = $("#product-item-order-options-"+sizeId);
        let optionWrapperMo = $("#product-item-order-options-mo-"+sizeId);
        // let $optionWrapperMo = $("#product-item-order-options-mo");
        let optionWrapperMobile = $("#mobile-options-"+sizeId);

        // let optionWrapperMobile = $("#product-item-order-options-mo-"+sizeId);
        let orderItemRemoveSet = "<span class='btn-remove-order-option' onclick=optionControl(this,'remove')></span>";
        let orderItemControllSet = "<span class='order-item-control'><button type='button' onclick=optionControl(this,'minus','"+sizeId+"')>-</button>" +
        "<input type='text' onblur=optionControl() value=1>" +
        "<button type='button' onclick=optionControl(this,'plus','"+sizeId+"')>+</button></span>";


        // <input type="radio" id ="<?= $sd['io_hoching'] ?>" name="sizePick" data-price="<?= $sd['st_price'] ?>" data-ioid="<?= $sd['io_id'] ?>" data-iono="<?= $sd['io_no'] ?>"><label><div class="size_<?= $sd['io_hoching'] ?>"><?= $sd['io_hoching'] ?></div></label>
        
        let io_id = "io-" + totalItsno + "-" + totalIono;
        let optionValue = totalIoid;
        let ioPrice = 1 * Number(totalPrice) + Number(totalIoPrice);

        let optionSet = $("<li id='" + io_id + "' data-price='" + ioPrice + "' data-no='" + totalItsno + "' data-its-no='" + totalItsno + "' data-id='" + optionValue + "' data-size='" + sizeId + "' class='option-its-no-" + totalItsno + "' style='margin-top: -10px;'></li>");
        // let optionSet = $("<li id='total_id_" + totalIono + "' data-price='" + totalPrice + "' data-no='" + totalIono + "'data-id='" + totalIoid + "' data-size='" + sizeId + "' class=''></li>");
        
        let optionSetMobile = $("<li id='" + io_id + "-mobile' data-id='" + io_id + "' data-price='" + ioPrice + "' data-no='" + totalItsno + "' data-its-no='" + totalItsno + "' data-id='" + optionValue + "' data-size='" + sizeId + "' class='option-its-no-" + totalItsno + "'></li>");
        


        // optionValue = $select.data("group") + " : " + optionValue;
        optionSet.append("<input type='hidden' name='ct_qty[" + totalItid + "][]' value=0 class='option-ct-qty'>");
        optionSet.append("<input type='hidden' name='io_type[" + totalItid + "][]' value='0'>");
        optionSet.append("<input type='hidden' name='io_id[" + totalItid + "][]' value='" + totalIoid + "'>");
        optionSet.append("<input type='hidden' name='io_value[" + totalItid + "][]' value='" + optionValue + "'>");
        optionSet.append("<input type='hidden' name='io_supply[" + totalItid + "][]' class='option-io-supply'>");
        optionSet.append("<input type='hidden' name='its_no[" + totalItid + "][]' value='" + totalItsno + "'>");

        optionSet.append("<input type='hidden' name='it_id[]' value='" + totalItid + "'>");


        // optionSetMobile.append("<span class='order-item-name'>" + optionValue + "</span>");
        optionSetMobile.append(orderItemControllSet);
        // optionSetMobile.append("<span class='order-item-price'>" + number_format(ioPrice) + "원</span>");
        optionSetMobile.append(orderItemRemoveSet);


        // optionSetQ.append("<input type='hidden' name='io_type_q[" + it_id + "][]' value='0'>");
        // optionSetQ.append("<input type='hidden' name='io_id_q[" + it_id + "][]' value='" + $selected.data("id") + "'>");
        // optionSetQ.append("<input type='hidden' name='io_value_q[" + it_id + "][]' value='" + optionValue + "'>");
        // optionSetQ.append("<input type='hidden' name='io_supply_q[" + it_id + "][]' class='option-io-supply'>");
        // optionSetQ.append("<input type='hidden' name='its_no_q[" + it_id + "][]' value='" + $select.data("no") + "'>");
        if (!mobileYn) {
            optionValue = '색상/사이즈' + " : " + optionValue;
            optionSet.append("<span class='order-item-name'>" + optionValue + "</span>");
        }
        optionSet.append(orderItemControllSet);
        optionSet.append("<span class='order-item-price'>" + number_format(totalPrice) + "원</span>");
        optionSet.append(orderItemRemoveSet);
        optionWrapperMo.append(optionSetMobile);
        optionWrapper.append(optionSet);
        // ?? 모바일 왱 ㅏㄴ되 씨발
        // optionWrapperMo.append(optionSet);
        // optionWrapperMobile.append(optionSet);
        
        return optionPriceUpdate();
    });

    $("#modal-coupon-down").on('hide.bs.modal', function(e) {
        $('#mobile-options-wrapper').css('display', 'block');
        $('.btn-order-mobile-group').css('display', 'block');
    });

    function max_sale_modal() {
        $('#mobile-options-wrapper').css('display', 'none');
        $('.btn-order-mobile-group').css('display', 'none');
        $("#modal-max-sale0").modal('show');
        $("#modal-max-sale0").modal('show');
    }
    $("#modal-max-sale").on('hide.bs.modal', function(e) {
        $('#mobile-options-wrapper').css('display', 'block');
        $('.btn-order-mobile-group').css('display', 'block');
    });

    $("#modal-mobile-order-select").on('hide.bs.modal', function(e) {
        $("#product-item-order-options").empty();
        $("#mobile-options").empty();
        $('.mo-order-select').val("").trigger('change');
    });

    var it_id = "<? echo $it_id; ?>";
    var itemlist_ca_id = "<? echo $ca_id; ?>";
    // var galleryThumbs = new Swiper('.gallery-view .gallery-thumbs', {
    //     // spaceBetween: 50,
    //     width: '140px',
    //     slidesPerView: 4,
    //     freeMode: true,
    //     watchSlidesVisibility: true,
    //     watchSlidesProgress: true,
    //     slideToClickedSlide: true,
    //     navigation: {
    //         nextEl: '.swiper-button-next',
    //         prevEl: '.swiper-button-prev',
    //     },


    // });
    var galleryTop = new Swiper('.gallery-view .gallery-top', {
        effect: 'fade',
        spaceBetween: 0,

        // loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination'
        }
        // thumbs: {
        //     swiper: galleryThumbs
        // }

    });
    $('.gallery-thumbs .swiper-slide').mouseover(function() {
        galleryTop.slideTo($(this).index());
    });
</script>
<script src="<? echo G5_JS_URL; ?>/shop.list.js"></script>
<? if ($is_orderable) : ?>
    <script src="<?= G5_JS_URL; ?>/shop.js"></script>
<? endif ?>
<!-- START NEXDI 0222 -->
<script type="text/javascript" charset="UTF-8" src="//t1.daumcdn.net/adfit/static/kp.js"></script>
<!-- END NEXDI 0222 -->
<script>
    $('.ALL_coupon_down_load').on('click', function() {
        if (g5_is_member != '1') {
            alert('로그인 후 이용해 주십시오.');
            return openLogin();
        }
        const $this = $(this);
        //const coupons = $this.data('coupon');
        const coupons = $this.data('coupons');
        $.ajax({
            type: 'GET',
            data: {
                "coupons": coupons,
                "type": 1
            },
            url: '/shop/ajax.coupondownload.product.detail.php',
            cache: false,
            async: true,
            dataType: 'json',
            success: function(data) {
                if (data.error != '') {
                    // $this.removeClass('disabled').attr('disabled', false);
                    alert(data.error);
                    $('.coupon_allow_cnt').html('0');
                    $('.coupon_down_zone .down_load_btn').addClass('disabled');
                    return false;
                }
                // $this.attr('disabled', false);

                //$('.coupon_down_zone .down_load_btn').addClass('disabled');
            }
        });
    });

    $('.down_load_btn').on('click', function() {
        if (g5_is_member != '1') {
            alert('로그인 후 이용해 주십시오.');
            return openLogin();
        }

        var coupon_allow_cnt = $(this).prev().children('.coupon_allow_cnt').text();

        const $this = $(this);
        //const coupons = $this.data('coupon');
        const id = $this.data('id');
        const user_limit = $this.data('user-limit');

        if ($this.hasClass('disabled')) {
            alert('이미 다운로드하신 쿠폰입니다.');
            return false;
        }
        $(this).prev().children('.coupon_allow_cnt').html('0');
        $.ajax({
            type: 'GET',
            data: {
                "coupons": [{
                    id: id,
                    count: user_limit
                }]
            },
            url: '/shop/ajax.coupondownload.product.detail.php',
            cache: false,
            async: true,
            dataType: 'json',
            success: function(data) {
                if (data.error != '') {
                    // $this.removeClass('disabled').attr('disabled', false);
                    alert(data.error);
                    $this.addClass('disabled').attr('disabled', true);
                    return false;
                }
                // $this.attr('disabled', false);
                // alert('쿠폰이 발급됐습니다.');
            }
        });
    });

    function fsubmit_check(f) {
        // 판매가격이 0 보다 작다면
        if (document.getElementById("it_price").value < 0) {
            alert("전화로 문의해 주시면 감사하겠습니다.");
            return false;
        }
        if ($(".sit_opt_list").length < 1) {
            alert("상품의 선택옵션을 선택해 주십시오.");
            return false;
        }
        var val, io_type, result = true;
        var sum_qty = 0;
        var min_qty = parseInt(<?= $it['it_buy_min_qty']; ?>);
        var max_qty = parseInt(<?= $it['it_buy_max_qty']; ?>);
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
            if (io_type == "0") {
                sum_qty += parseInt(val);
            }
        });
        if (!result) {
            return false;
        }
        if (min_qty > 0 && sum_qty < min_qty) {
            alert("선택옵션 개수 총합 " + number_format(String(min_qty)) + " 개 이상 주문해 주십시오.");
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
        //f.action = "<?= $action_url; ?>";
        //f.target = "";

        if (document.pressed == "장바구니") {
            $("input[name='sw_direct']").val("0");
            //f.sw_direct.value = 0;
        } else { // 바로구매
            $("input[name='sw_direct']").val("1");
            //f.sw_direct.value = 1;
        }

        // 판매가격이 0 보다 작다면
        if (document.getElementById("it_price").value < 0) {
            alert("전화로 문의해 주시면 감사하겠습니다.");
            return false;
        }
        if ($(".sit_opt_list").length < 1) {
            alert("상품의 선택옵션을 선택해 주십시오.");
            return false;
        }
        if ($("select[name='sel_it_option[]' ]").length > 1) {
            //세트상품의 경우 모든 상품을 선택해야함.
            if ($("select[name='sel_it_option[]']").length != $(".sit_opt_list").length) {
                alert("상품의 선택옵션을 선택해 주십시오.");
                return false;
            }
        }

        var val, io_type, result = true;
        var sum_qty = 0;
        var min_qty = parseInt(<?= $it['it_buy_min_qty']; ?>);
        var max_qty = parseInt(<?= $it['it_buy_max_qty']; ?>);
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
            if (io_type == "0") sum_qty += parseInt(val);
        });
        if (!result) {
            return false;
        }
        if (min_qty > 0 && sum_qty < min_qty) {
            alert("선택옵션 개수 총합 " + number_format(String(min_qty)) + " 개 이상 주문해 주십시오.");
            return false;
        }
        if (max_qty > 0 && sum_qty > max_qty) {
            alert("선택옵션 개수 총합 " + number_format(String(max_qty)) + "개 이하로 주문해 주십시오.");
            return false;
        }

        var form = $("form[name='fitem']")[0];
        var formData = new FormData(form);

        $.ajax({
            url: '<?= $action_url; ?>',
            processData: false,
            contentType: false,
            data: formData,
            type: 'POST',
            success: function(result) {
                if ($("input[name='sw_direct']").val() == "1") {
                    location.href = '<?= G5_SHOP_URL ?>/orderform.php?sw_direct=1&od_type=O';
                } else {
                    $("#popup").html(result);
                }
            }
        });
        /*
        $.post("<?= $action_url; ?>", formData,function(data){
        $("#popup").html(data);
        });
        */
        return false;
        //return true;
    }

    function add_single_option() {
        const optionSelect = $("select[name='sel_it_option[]']");

        if (optionSelect.length === 1 && $(optionSelect).children().length <= 2) {
            $(optionSelect).each(function(idx, select) {
                const its_no = $(select).attr("its_no");
                $(select).children().each(function(cidx, option) {
                    if (option.value) {
                        $(option).attr("selected", "selected");
                    }
                });
                $("div#list-item-options> ul").hide();
                add_sel_option_mobile_chk(its_no);

            });
        }
    }

    add_single_option();

    let popupData = {
        content: ""
    };
    let $optionWrapper = $("#product-item-order-options");
    let $optionWrapperMo = $("#product-item-order-options-mo");
    const $optionWrapperMobile = $("#mobile-options");
    const orderItemRemoveSet = "<span class='btn-remove-order-option' onclick=optionControl(this,'remove')></span>";
    const orderItemControllSet = "<span class='order-item-control'><button type='button' onclick=optionControl(this,'minus')>-</button>" +
        "<input type='text' onblur=optionControl() value=1>" +
        "<button type='button' onclick=optionControl(this,'plus')>+</button></span>";

    $(".product-detail-item-option").on("change", function() {
        const $select = $(this);
        const $selected = $select.find("option:selected");

        if ($selected.val().length > 0) {
            const io_id = "io-" + $select.data("no") + "-" + $selected.data("no");
            if ($("#" + io_id).length > 0) {
                const quantity = $("#" + io_id).find("input[type='text']").val() * 1 + 1;
                if ($selected.data("stock") < quantity) {
                    popupData.content = $selected.data("stock") + "개 이상 주문하실 수 없습니다";
                    return openPopup(popupData);
                }
                $("#" + io_id).find("input[type='text']").val(quantity);
                $("#" + io_id + "-mobile").find("input[type='text']").val(quantity);
            } else {
                const ioPrice = 1 * $select.data("price") + $selected.data("option-price");
                let optionValue = $selected.data("id");
                // let optionSet = $("<li id='" + io_id + "' data-price='" + ioPrice + "' data-no='" + $select.data("no") + "' data-its-no='" + $select.data("its-no") + "' data-id='" + optionValue + "' class='option-its-no-" + $select.data("no") + "'></li>");
                // let optionSetMobile = $("<li id='" + io_id + "-mobile' data-id='" + io_id + "' data-price='" + ioPrice + "' data-no='" + $select.data("no") + "' data-its-no='" + $select.data("its-no") + "' data-id='" + optionValue + "' class='option-its-no-" + $select.data("no") + "'></li>");

                // Form Data
                if ($select.data("no") == "supply") {
                    if ($(".option-its-no-" + $select.data("its-no")).length < 1) {
                        let its;
                        $(".product-detail-item-option").each(function(iit, iie) {
                            if ($(iie).data("no") == $select.data("its-no")) its = $(iie);
                        });

                        popupData.content = "기본품목(" + its.data("item") + ")을 먼저 선택해주세요";
                        return openPopup(popupData);
                    }

                    optionValue = "추가구매 : " + optionValue;
                } else {
                    // optionValue = $select.data("group") + " : " + optionValue;
                    // optionSet.append("<input type='hidden' name='ct_qty[" + it_id + "][]' value=0 class='option-ct-qty'>");
                    // optionSet.append("<input type='hidden' name='io_type[" + it_id + "][]' value='0'>");
                    // optionSet.append("<input type='hidden' name='io_id[" + it_id + "][]' value='" + $selected.data("id") + "'>");
                    // optionSet.append("<input type='hidden' name='io_value[" + it_id + "][]' value='" + optionValue + "'>");
                    // optionSet.append("<input type='hidden' name='io_supply[" + it_id + "][]' class='option-io-supply'>");
                    // optionSet.append("<input type='hidden' name='its_no[" + it_id + "][]' value='" + $select.data("no") + "'>");
                }

                // optionSet.append("<span class='order-item-name'>" + optionValue + "</span>");
                // optionSet.append(orderItemControllSet);
                // optionSet.append("<span class='order-item-price'>" + number_format(ioPrice) + "원</span>");
                // optionSet.append(orderItemRemoveSet);

                // optionSetMobile.append("<span class='order-item-name'>" + optionValue + "</span>");
                // optionSetMobile.append(orderItemControllSet);
                // optionSetMobile.append("<span class='order-item-price'>" + number_format(ioPrice) + "원</span>");
                // optionSetMobile.append(orderItemRemoveSet);

                // $optionWrapper.append(optionSet);
                // $optionWrapperMobile.append(optionSetMobile);

                if (!$("#product-item-order").hasClass("active")) $("#product-item-order").addClass("active");
            }

            optionPriceUpdate();
        }
        // console.log($select.children().eq(0).attr("selected", "selected"));
    });
    function modaldel() {
        $('.product-item-order-options').remove();
        $("div[id^='optionMo_']").remove();
        $("div[id^='option_']").remove();
        $("#mobile-options-total").css('display', 'none');
        $('.mobileLine').remove();
        $("input[name='sizePick']").prop('checked',false);
        totalLineMo1.style.display ='none'
        totalLineMo2.style.display ='none'
    }
    function optionDelete(e1=false,e2=false) {
        $(e1).remove();
        if(e2){
            let e2Id = '#option_modal_'+e2.id;
            $(e2Id).remove();
        }  
        let optionNum = $("div[id^='option_']").length;
        if (optionNum < 1 || !optionNum) {
            $("input[name='sizePick']").prop('checked',false);
            let optionLine = document.getElementById("totalLine");
            optionLine.style.display ='none'
        }

        let optionNumMo = $("div[id^='optionMo_']").length;

        if (optionNumMo < 1 || !optionNumMo) {
            totalLineMo1.style.display = 'none';
            totalLineMo2.style.display = 'none';
            $('.mobileLine').remove();
            if (e2) $("input[name='sizePick']").prop('checked',false);
        }
        return optionPriceUpdate();
    }

    function optionControl(elem, action,priceId=false) {
        const $optionWrapper = $(elem).closest("li");
        let target = $optionWrapper.find(".order-item-control>input[type='text']");

        switch (action) {
            case 'plus':
                target.val(target.val() * 1 + 1);
                break;
            case 'minus':
                if (target.val() > 1) target.val(target.val() - 1);
                break;
            case 'remove':
                $optionWrapper.remove();
                break;
            default:
                break;
        }

        if ($optionWrapper.data("id")) {
            let targetReal = $("#" + $optionWrapper.data("id")).find(".order-item-control>input[type='text']");

            switch (action) {
                case 'plus':
                    targetReal.val(targetReal.val() * 1 + 1);
                    break;
                case 'minus':
                    if (targetReal.val() > 1) targetReal.val(targetReal.val() - 1);
                    break;
                case 'remove':
                    $optionWrapper.remove();
                    break;
                default:
                    break;
            }
        }

        return optionPriceUpdate(priceId);
    }

    function optionPriceUpdate(priceId=false) {

        var $optionWrapper = $(".product-item-order-options");
        var sumPrice = 0;

        if ($optionWrapper.length < 1) {
            $optionWrapper = $("#product-item-order-options");
        } else {
            var sumOption = '';
            $optionWrapper.find("li").each(function(oi, oe) {
                sumOption = $(oe).data("price") * $(oe).find(".order-item-control>input[type='text']").val();
                sumPrice += sumOption;
                $(oe).find(".order-item-price").text(number_format(sumOption) + "원");
                if (priceId && priceId == oe.dataset.size) {
                    $('.new-price-'+priceId).text(number_format(sumOption) + "원");
                    $('.new-price-mo-'+priceId).text(number_format(sumOption) + "원");
                }$

            });
        }
        $("#product-item-order-price-total").text(number_format(sumPrice));
        $("#product-item-order-price-total-mobile").text(number_format(sumPrice/2));
        if ($optionWrapper.find("li").length > 0) {
            $("#product-item-order").addClass("active");
            $("#product-item-order-price").addClass("active");
        } else {
            $("#product-item-order").removeClass("active");
            $("#product-item-order-price").removeClass("active");
        }
        return true;
    }


    function cartUpdate(direct, member) {
        if (member == '' || member == null) {
            alert("로그인 후 장바구니 이용 가능합니다.");
            window.location = "/auth/login.php?r=1";
            return;
        }

        const f = document.getElementById("formCartUpdate");
        f.sw_direct.value = direct;
        let orderItemElems = $(".product-item-order-options > li");
        if (orderItemElems.length == 0) {
            if ($(window).width() > 1024) {
                popupData.content = "주문하실 제품을 선택해주세요";
                return openPopup(popupData);
            } else {
                $('.mo-order-select').val('0').trigger('change');
                return $('#modal-mobile-order-select').modal('show');
                // return $("#mobile-options-wrapper").addClass("active");
            }
        }

        // $('#modal-mobile-order-select').modal('hide');

        $((".option-io-supply")).val("");
        orderItemElems.each(function(oi, oe) {
            let targetElem;
            let targetSupply;
            let glue = "";
            if ($(oe).hasClass("option-its-no-supply")) {
                targetElem = $("#product-item-order-options").find(".option-its-no-" + $(oe).data("its-no")).first();
                targetSupply = targetElem.find(".option-io-supply");
                for (i = 1; i < $(oe).find("input[type='text']").val() * 1; i++) {
                    glue = targetSupply.val().length > 0 ? "," : "";
                    targetSupply.val(targetSupply.val() + glue + $(this).data("id"));
                }
            } else {
                $(oe).find(".option-ct-qty").val($(oe).find("input[type='text']").val() * 1);
            }
        });

        var formData = new FormData(f);

        $.ajax({
            url: '<?= $action_url; ?>',
            processData: false,
            contentType: false,
            cache: false,
            data: formData,
            type: 'POST',
            success: function(response) {
                nexdiCart();
                if ($("input[name='sw_direct']").val() == "1") {
                    location.href = '<?= G5_SHOP_URL ?>/orderform.php?sw_direct=1&od_type=O';
                } else {
                    popupData.content = "장바구니에 상품이 추가되었습니다. <br> 장바구니로 이동하시겠습니까?";
                    popupData.confirm = {
                        text: "장바구니 확인",
                        action: "location.href='<?= G5_SHOP_URL ?>/cart.php'"
                    }
                    popupData.close = {
                        text: "계속 쇼핑",
                    }
                    return openPopup(popupData, "confirm");
                }
            }
        });
    }
    // NEXDICART ,모비온
    function nexdiCart() { 
        let nexdiId = $("#ori_it_id").val();
        let nexdiPrice = $("#product-item-order-price-total").text();
        nexdiPrice = nexdiPrice.replace(",","");
        kakaoPixel('2967409213611789029').addToCart({
            id: nexdiId,
            tag: nexdiPrice
        });
        // 모비온 



    }
    function goProductDesc(target) {
        const target_Elem = $("#" + target);
        $("html,body").animate({
            "scrollTop": target_Elem.offset().top - 300
        }, 500);
    }

    $(document).ready(function() {
        var t_max_sale_price = $('#total_max_sale_price').val();
        var max_sale_price_text = number_format(t_max_sale_price) + '원';
        $('.view_max_sale_price').text(max_sale_price_text);
        if ($(window).width() > 1024) {
            $('.pc_select_order_option').val('0').trigger('change');
        }

        if ("IntersectionObserver" in window) {
            let observerFlags = [];
            let observerHandle = $("html");
            let observeFlag = "scroll-over";
            let domObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    observeFlag = $(entry.target).data("observe") ? $(entry.target).data("observe") : "scroll-over";

                    if (observeFlag == "scroll-over") {
                        if (entry.isIntersecting) {
                            observerHandle.removeClass("scroll-over");
                        } else {
                            observerHandle.addClass("scroll-over");
                        }
                    } else {
                        if (entry.isIntersecting) {
                            if (observerFlags.indexOf(observeFlag) < 0) observerFlags.push(observeFlag);
                        } else {
                            observerFlags.splice(observerFlags.indexOf(observeFlag), 1);
                        }
                        observerHandle.removeClass("desc-detail desc-review desc-info desc-qna");
                        if (observerFlags.length == 0) observerFlags.push("desc-detail");
                        observerHandle.addClass(observerFlags[observerFlags.length - 1]);
                    }
                });
            });

            $(".section-observed").each(function(ei, el) {
                domObserver.observe(el);
            });
        }
        // let broswerInfo = navigator.userAgent;

        // if (broswerInfo.indexOf("Mobile")>-1) {
        //     if (broswerInfo.indexOf("APP_ANDROID") < 0 && broswerInfo.indexOf("APP_IOS") < 0 ) { 
        //         $(".scroll-over.scroll-up #product-desc-tab-wrapper").css();
        //         let c = $(".scroll-over.scroll-up #product-desc-tab-wrapper").css();
        //         console.log('c : ',c);
        //         let h = document.getElementById('appAd').clientHeight;
        //         // document.getElementsByClassName('nav_main')[0].style.top= h+'px';
        //         document.getElementsByClassName('html.scroll-over.scroll-up topSubmenu')[0].style.top= h+44+'px';
        //         // html.scroll-over.scroll-up #product-desc-tab-wrapper
        //         // html.scroll-down #nav-top-small {top: -150px!important;}
        //         // html.scroll-down .topSubmenu {top: 0px!important;}
        //     }

        // }
        if ($(window).width() > 1024) {
            let fnum =0;
            if (<?= $sdNum?> == 1 && fnum ==0) {
                fnum = 1; 
                $("#<?= $sdHoc?>").trigger("click");
            }
        }

    });
    $(".product-desc-tab").on("click", function() {
        $("html").removeClass("desc-detail desc-review desc-info desc-qna");
        $("html").addClass($(this).data("name"));
        goProductDesc($(this).data("target"));
    });

    $("#btn-toggle-mobile-options").on("click", function() {
        $("#mobile-options-wrapper").toggleClass("active");
    });
    function openOrder() {
        $('#mobile-options-wrapper').css('display', 'block');
        $('.btn-order-mobile-group').css('display', 'block');
        $("div[id^='modal-max-sale-body']").css('display', 'none');
        $("#modal-max-sale-body0").css('display', 'block');
        $("input[name='sizePickOpt']").prop('checked',false);
        $("input[name='sizePickOpt']").eq(0).prop('checked',true);
    }
</script>
<script src="<?= G5_JS_URL; ?>/shop.override.js"></script>

<!-- Enliple Tracker Start -->
<script type="text/javascript">
let codeDetail ='<? echo $it_id?>';
let nameDetail ='<? echo $sns_title?>';
let imageDetail ='<? echo $sns_image?>';
let salesDatail = '<? echo ($sns_sales) ?>';
let dissalesDatail = '<? echo $user_price ?>';
let caidDetail = '<? echo $it["ca_id"] ?>';
let caidDetail1 = caidDetail.substr(0,2);
let caidDetail2 = caidDetail.substr(2,2);
let caidDetail3 = caidDetail.substr(4,2);
let soldoutDetail = '<? echo $is_orderable ?>';
let soldoutresDetail = 'Y'
if (soldoutDetail =='1') soldoutresDetail = 'N';

let broswerTrackerPD = navigator.userAgent;
let deviceTrackerPD = "W";
let wishSelector = '.quarter-img';
let cartSelector = '.btn-cart';
if (broswerTrackerPD.indexOf("Mobile")>-1) { 
    wishSelector = '.btn-pick-heart';
    cartSelector = '.btn-order-mobile';
    deviceTrackerPD = "M";
}

var ENP_VAR = {
collect: {},
conversion: { product: [] }
};
ENP_VAR.collect.productCode = codeDetail;
ENP_VAR.collect.productName = nameDetail;
ENP_VAR.collect.price = salesDatail;
ENP_VAR.collect.dcPrice = dissalesDatail;
ENP_VAR.collect.soldOut = soldoutresDetail;
ENP_VAR.collect.imageUrl = imageDetail;
ENP_VAR.collect.topCategory = '상품이 속한 카테고리의 최상위 분류';
ENP_VAR.collect.firstSubCategory = caidDetail1;
ENP_VAR.collect.secondSubCategory = caidDetail2;
ENP_VAR.collect.thirdSubCategory = caidDetail3;

	/* 간편 결제 시스템을 통한 전환. (이용하지 않는 경우 삭제) */
// ENP_VAR.conversion.product.push({
// 		productCode : '제품 코드',
// 		productName : '제품명',
// 		price : '제품가격',
// 		dcPrice : '제품 할인가격',
// 		qty : '제품 수량',
// 		soldOut : '품절 여부',
// 		imageUrl : '상품 이미지 URL',
// 		topCategory : '상품이 속한 카테고리의 최상위 분류',
// 		firstSubCategory : '대분류',
// 		secondSubCategory : '중분류',
// 		thirdSubCategory : '소분류'
// 	});

(function(a,g,e,n,t){a.enp=a.enp||function(){(a.enp.q=a.enp.q||[]).push(arguments)};n=g.createElement(e);n.async=!0;n.defer=!0;n.src="https://cdn.megadata.co.kr/dist/prod/enp_tracker_self_hosted.min.js";t=g.getElementsByTagName(e)[0];t.parentNode.insertBefore(n,t)})(window,document,"script");
	/* 상품수집 */
enp('create', 'collect', 'litandard', { device: deviceTrackerPD });
	/* 장바구니 버튼 타겟팅 (이용하지 않는 경우 삭제) */
enp('create', 'cart', 'litandard', { device: deviceTrackerPD, btnSelector: cartSelector });
	/* 찜 버튼 타겟팅 (이용하지 않는 경우 삭제) */
enp('create', 'wish', 'litandard', { device: deviceTrackerPD, btnSelector: wishSelector });
	/* 네이버페이 전환. (이용하지 않는 경우 삭제) */
	// enp('create', 'conversion', 'litandard', { device: 'B', paySys: 'naverPay' });
</script>
<!-- Enliple Tracker End -->

<?php
$contents = ob_get_contents();
ob_end_clean();

return $contents;
?>