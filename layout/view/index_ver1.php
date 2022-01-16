<?php
ob_start();
?>
<style>
    .btn.btn-more {
        width: 140px;
        margin-top: 26px;
        border-color: var(--black-two);
    }
</style>
<?php if (!empty($g5_banner_new['MAIN'])) : ?>
    <div class="swiper-container">
        <div id="main-banner-wrapper" class="swiper-wrapper">
            <? foreach ($g5_banner_new['MAIN'] as $bmain) : ?>
                <a class="swiper-slide" href="<?= $bmain['cp_link'] ?>">
                    <div class="main-banner on-big" style="background-image: url(/data/banner/<?= $bmain['cp_image_1'] ?>);">
                        <div class="main-banner-subject" style="display: none;"><?= $bmain['ba_subject'] ?></div>
                        <div class="main-banner-content" style="display: none;"><?= $bmain['cp_desc'] ?></div>
                    </div>
                    <div class="main-banner on-small" style="background-image: url(/data/banner/<?= $bmain['cp_image_2'] ?>);">
                        <div class="main-banner-subject" style="display: none;"><?= $bmain['ba_subject'] ?></div>
                        <div class="main-banner-content" style="display: none;"><?= $bmain['cp_desc'] ?></div>
                    </div>
                </a>
            <? endforeach ?>
        </div>
        <!-- <div id="main-banner-wrapper-dots" class="campaign-hot-selector on-small" style="text-align: center;">
            <? for ($bi = 0; $bi < count($g5_banner_new['MAIN']); $bi++) : ?><span></span><? endfor ?>
        </div> -->
        <!-- Add Arrows -->
        <div class="swiper-button-next on-big swiper-button-white" style="position: absolute;right:53px;width:32px;height: 62px;"></div>
        <div class="swiper-button-prev on-big swiper-button-white" style="position: absolute;left:53px;width:32px;height: 62px;"></div>


        <!-- Add Pagination -->
        <div class="swiper-pagination swiper-pagination-white"></div>
    </div>

    <script>
        var swiper = new Swiper('.swiper-container', {
            cssMode: true,
            loop: true,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination'
            },
            mousewheel: false,
            keyboard: true,
        });
    </script>
<?php endif ?>
<?php if (!empty($g5_banner_new['THEME'])) : ?>
    <div class="offset-campaign-top on-small" style="margin-bottom: 8px; padding-left: 20px;">
        <div class="campaign-title">THEME</div>
    </div>
    <div class="offset-campaign-top on-big"></div>
    <div id="campaign-theme-wrapper">
        <? foreach ($g5_banner_new['THEME'] as $ci => $c_theme) : ?>
            <div class="campaign-theme">
                <div class="campaign-theme-content on-small">
                    <div class="campaign-subject" style="padding-left: 20px; font-size: 42px"><?= $c_theme['cp_subject'] ?></div>
                    <div class="campaign-desc" style="padding-left: 20px; font-size: 12px; min-height: 66px;"><?= $c_theme['cp_desc'] ?></div>
                    <a href="<?= !empty($c_theme['cp_link']) ? $c_theme['cp_link'] : "/event/view.php?cp_id=" . $c_theme['cp_id'] ?>">
                        <div class="campaign-theme-image campaign-image-big" style="background-image: url(/data/banner/<?= $c_theme['cp_image_2'] ?>);"></div>
                    </a>
                </div>
                <div style="text-align: center; overflow: hidden;">
                    <a href="<?= !empty($c_theme['cp_link']) ? $c_theme['cp_link'] : "/event/view.php?cp_id=" . $c_theme['cp_id'] ?>">
                        <div class="campaign-theme-image campaign-image-big on-big" style="background-image: url(/data/banner/<?= $c_theme['cp_image_1'] ?>);"></div>
                    </a>
                    <div class="campaign-theme-content on-big">
                        <div class="campaign-title">THEME</div>
                        <div class="campaign-subject"><?= $c_theme['cp_subject'] ?></div>
                        <div class="campaign-desc" style="min-height: 654px;"><?= $c_theme['cp_desc'] ?></div>
                        <div class="campaign-theme-selector">
                            <?php for ($pi = 1; $pi <= count($g5_banner_new['THEME']); $pi++) : ?><span onclick="sliderTheme.goTo(<?= $pi - 1 ?>)" class="<?= $pi == $ci + 1 ? "active" : "" ?>"><?= $pi ?></span><?php endfor ?>
                        </div>
                    </div>
                </div>
            </div>
        <? endforeach ?>
    </div>
    <div id="campaign-theme-wrapper-dots" class="campaign-hot-selector on-small" style="text-align: center;">
        <? for ($bi = 0; $bi < count($g5_banner_new['THEME']); $bi++) : ?><span></span><? endfor ?>
    </div>
<?php endif ?>
<div>
<?php if (!empty($g5_banner_new['MD'])) : ?>
    <div class="offset-campaign-top on-small" style="margin-bottom: 8px; padding-left: 20px;">
        <div class="campaign-title">MD'S PICK</div>
    </div>
    <div class="offset-campaign-top on-big"></div>
    <div id="campaign-md-wrapper">
        <div class="campaign-md on-big">
            <div class="campaign-md-content on-big" id = "campaign-md-content">
                <div class="campaign-title">MD'S PICK</div>
                <div class="campaign-subject"><?= $g5_banner_new['MD'][0]['cp_subject'] ?></div>
                <div class="campaign-desc"><?= $g5_banner_new['MD'][0]['cp_desc'] ?></div>
                <div class="campaign-md-more"><a href="<?= !empty($campaign['MD'][0]['cp_link']) ? $g5_banner_new['MD'][0]['cp_link'] : "/event/view.php?cp_id=" . $g5_banner_new['MD'][0]['cp_id'] ?>"></a></div>
            </div>
            <div class="campaign-md-image-wapper on-big">
                <a href="<?= !empty($g5_banner_new['MD'][0]['cp_link']) ? $g5_banner_new['MD'][0]['cp_link'] : "/event/view.php?cp_id=" . $g5_banner_new['MD'][0]['cp_id'] ?>">
                    <div class="campaign-md-image campaign-image-big" style="background-image: url(/data/banner/<?= $g5_banner_new['MD'][0]['cp_image_1'] ?>);"></div>
                </a>
                <div class="campaign-md-item-set">
                    <?php
                    $cp_item_set = json_decode($g5_banner_new['MD'][0]['cp_item_set'], true);
                    $cp_item_set_item = array();
                    $cp_item_set_category = array();
                    foreach ($cp_item_set as $cis) {
                        if (!empty($cis['item'])) $cp_item_set_item[] = $cis['item'];
                        if (!empty($cis['category'])) $cp_item_set_category[] = $cis['category'];
                    }

                    $sql_md_item = "SELECT * FROM {$g5['g5_shop_item_table']} WHERE it_use=1 AND ";
                    $sql_md_where = array();
                    if (!empty($cp_item_set_item)) $sql_md_where[] = " it_id IN(" . implode(',', $cp_item_set_item) . ")";
                    if (!empty($cp_item_set_category)) {
                        $cp_item_set_category = implode(',', $cp_item_set_category);
                        foreach (explode(',', $cp_item_set_category) as $cp_ca_id) {
                            $sql_md_where[] = "ca_id LIKE '{$cp_ca_id}%'";
                        }
                    }
                    if (empty($sql_md_where)) {
                        echo ("<!-- ITEM NOT SET CAMPAIGN[MD]-->");
                    } else {
                        $sql_md_item = $sql_md_item . implode(' OR ', $sql_md_where) . " LIMIT 9";
                        $db_md_item = sql_query($sql_md_item);

                        while (($citem = sql_fetch_array($db_md_item)) != false) {
                            $thumb = get_it_thumbnail_path($citem['it_img1'], 290, 290);
                            if ($citem['it_discount_price'] != '' && $citem['it_discount_price'] != '0') {
                                $it_price = $citem['it_price'];
                                $it_sale_price = $citem['it_discount_price'];
                                $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                            }
                    ?>

                    <div class="campaign-image-item" data-id=<?= $citem['it_id'] ?> style="background-image: url(<?= $thumb ?>);">
                        <span class="btn-pick <?= in_array($citem['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $citem['it_id'] ?>></span>
                        <span class="campaign-image-item-hover">
                            <a href = "/shop/item.php?it_id=<?= $citem['it_id'] ?>">
                                <span class="history-content-text-box">
                                    <div class="history-content-text-brand C2ENBLL" style="font-size: 12px !important;"><?= $citem['it_brand'] ?></div>
                                    <div class="history-content-text-item C1ENBLL" style="font-size: 16px !important;"><?= $citem['it_name'] ?></div>
                                    <div class="history-content-text-price C1ENBLL" style="font-size: 20px !important;"><?= display_price(get_price($citem), $citem['it_tel_inq']); ?><span style="font-size: 12px;">원</span></div>
                                <? if ($discount_ratio > 0) : ?>
                                    <div class="history-content-text-saleprice C2ENBLL" style="font-size: 12px !important;">
                                        <div class="price-tag"><del><?= number_format($it_price + $it_sale_price) ?></del><span>원</span>
                                            <span class="price-dis" style="color: #e65026;">(<?= number_format($discount_ratio) ?>%)</span>
                                        </div>
                                    </div>
                                <? endif ?>
                                </span>
                            </a>
                        </span>
                    </div>
                    
                    <? }
                    } ?>
                </div>
            </div>
        </div>
        <div class="campaign-md on-small">
            <div class="campaign-md-content">
                <div class="campaign-subject" style="padding-left: 20px; font-size: 42px"><?= $g5_banner_new['MD'][0]['cp_subject'] ?></div>
                <div class="campaign-desc" style="padding-left: 20px; font-size: 12px; min-height: 66px;"><?= $g5_banner_new['MD'][0]['cp_desc'] ?></div>
            </div>
            <a href="<?= !empty($g5_banner_new['MD'][0]['cp_link']) ? $g5_banner_new['MD'][0]['cp_link'] : "/event/view.php?cp_id=" . $g5_banner_new['MD'][0]['cp_id'] ?>">
                <div class="campaign-md-image campaign-image-big" style="margin-left: 20px; background-image: url(/data/banner/<?= $g5_banner_new['MD'][0]['cp_image_2'] ?>);"></div>
            </a>
            <div class="campaign-md-item-set">
                <?php
                $cp_item_set = json_decode($g5_banner_new['MD'][0]['cp_item_set'], true);
                $cp_item_set_item = array();
                $cp_item_set_category = array();
                foreach ($cp_item_set as $cis) {
                    if (!empty($cis['item'])) $cp_item_set_item[] = $cis['item'];
                    if (!empty($cis['category'])) $cp_item_set_category[] = $cis['category'];
                }

                $sql_md_item = "SELECT * FROM {$g5['g5_shop_item_table']} WHERE it_use=1 AND ";
                $sql_md_where = array();
                if (!empty($cp_item_set_item)) $sql_md_where[] = " it_id IN(" . implode(',', $cp_item_set_item) . ")";
                if (!empty($cp_item_set_category)) {
                    $cp_item_set_category = implode(',', $cp_item_set_category);
                    foreach (explode(',', $cp_item_set_category) as $cp_ca_id) {
                        $sql_md_where[] = "ca_id LIKE '{$cp_ca_id}%'";
                    }
                }
                if (empty($sql_md_where)) {
                    echo ("<!-- ITEM NOT SET CAMPAIGN[MD]-->");
                } else {
                    $sql_md_item = $sql_md_item . implode(' OR ', $sql_md_where) . " LIMIT 12";
                    $db_md_item = sql_query($sql_md_item);

                    while (($citem = sql_fetch_array($db_md_item)) != false) {
                        $thumb = get_it_thumbnail_path($citem['it_img1'], 290, 290);
                        if ($citem['it_discount_price'] != '' && $citem['it_discount_price'] != '0') {
                            $it_price = $citem['it_price'];
                            $it_sale_price = $citem['it_discount_price'];
                            $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                        }
                ?>
                        <div>
                            <a href = "/shop/item.php?it_id=<?= $citem['it_id'] ?>">
                                <span class="campaign-image-item" data-id=<?= $citem['it_id'] ?> style="background-image: url(<?= $thumb ?>);"><span class="btn-pick on-big <?= in_array($citem['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $citem['it_id'] ?>></span>
                                </span><span class="campaign-brand-item-text" style="margin-left: unset;">
                                    <div class="history-content-text-brand C2ENBLL" style="font-size: 12px !important;"><?= $citem['it_brand'] ?></div>
                                    <div class="history-content-text-item C1ENBLL" style="font-size: 16px !important;"><?= $citem['it_name'] ?></div>
                                    <div class="history-content-text-price C1ENBLL" style="font-size: 16px !important;"><?= display_price(get_price($citem), $citem['it_tel_inq']); ?><span style="font-size: 12px;">원</span></div>
                                    <? if ($discount_ratio > 0) : ?>
                                        <div class="history-content-text-saleprice C2ENBLL" style="font-size: 12px !important;">
                                            <span class="price-tag"><del><?= number_format($it_price + $it_sale_price) ?></del><span>원</span></span>
                                            <span class="price-dis" style="color: #e65026;">(<?= number_format($discount_ratio) ?>%)</span>
                                        </div>
                                    <? endif ?>
                                </span>
                            </a>
                        </div>
                <? }
                } ?>
            </div>
        </div>
    </div>
<?php endif ?>
<?php if ($db_brand->num_rows > 0) : ?>
    <div class="offset-campaign-top on-small" style="padding-left: 20px;">
        <div class="campaign-title">BRAND</div>
    </div>
    <div id="campaign-brand-wrapper">
        <div class="offset-campaign-top on-big" style="margin-bottom: 20px;">
            <div class="campaign-title">BRAND</div>
        </div>
        <? foreach ($g5_banner_new['BRAND'] as $bi => $brand) : ?><div class="campaign-brand">
                <div class="campaign-brand-name brand-index-<?= $bi ?>"><?= $brand['cp_subject'] ?></div>
                <a href="<?= !empty($brand['cp_link']) ? $brand['cp_link'] : "/shop/brand.php?br_id=" . $brand['br_id'] ?>">
                    <div class="campaign-brand-image" style="background-image: url(/data/banner/<?= $brand['cp_image_1'] ?>);"></div>
                </a>
                <div class="campaign-brand-item-set">
                    <?php
                    $cp_item_set = json_decode($brand['cp_item_set'], true);
                    $cp_item_set_item = array();
                    $cp_item_set_category = array();
                    foreach ($cp_item_set as $cis) {
                        if (!empty($cis['item'])) $cp_item_set_item[] = $cis['item'];
                        if (!empty($cis['category'])) $cp_item_set_category[] = $cis['category'];
                    }
                    $sql_brand_item = "SELECT * FROM {$g5['g5_shop_item_table']} WHERE it_use=1 AND ";
                    $sql_brand_where = array();
                    if (!empty($cp_item_set_item)) $sql_brand_where[] = " it_id IN(" . implode(',', $cp_item_set_item) . ")";
                    if (!empty($cp_item_set_category)) {
                        $cp_item_set_category = implode(',', $cp_item_set_category);
                        foreach (explode(',', $cp_item_set_category) as $cp_ca_id) {
                            $sql_brand_where[] = "ca_id LIKE '{$cp_ca_id}%'";
                        }
                    }

                    if (empty($sql_brand_where)) die("CAMPAIGN ITEM NOT SET");
                    $sql_brand_item = $sql_brand_item . implode(' OR ', $sql_brand_where) . " ORDER BY RAND() LIMIT 3";
                    $db_brand_item = sql_query($sql_brand_item);
                    while (($citem = sql_fetch_array($db_brand_item)) != false) {
                        $brand_thumb = get_it_thumbnail_path($citem['it_img1'], 250, 250);
                        // $bit_thumb = get_it_thumbnail_path($bit['it_img1'], 250, 250);
                        if ($citem['it_discount_price'] != '' && $citem['it_discount_price'] != '0') {
                            $it_price = $citem['it_price'];
                            $it_sale_price = $citem['it_discount_price'];
                            $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                        }
                    ?>
                        <div class="campaign-brand-item-row">
                            <a href="/shop/item.php?it_id=<?= $citem['it_id'] ?>">
                                <span class="campaign-brand-item-image" onclick="location.href='/shop/item.php?it_id=<?= $citem['it_id'] ?>'" style="background-image: url(<?= $brand_thumb ?>);"><span class="btn-pick on-big <?= in_array($bit['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $citem['it_id'] ?>></span>
                                </span><span class="campaign-brand-item-text">
                                    <div class="history-content-text-brand C2ENBLL"><?= $citem['it_brand'] ?></div>
                                    <div class="history-content-text-item C1ENBLL" style="font-size : 17px !important;"><?= $citem['it_name'] ?></div>
                                    <div class="history-content-text-price C1ENBLL"><?= display_price(get_price($citem), $citem['it_tel_inq']); ?><span style="font-size: 12px;">원</span></div>
                                    <div class="history-content-text-saleprice C2ENBLL">
                                        <span class="price-tag"><del><?= number_format($it_price + $it_sale_price) ?></del><span>원</span></span>
                                        <span class="price-dis" style="color: #e65026;">(<?= number_format($discount_ratio) ?>%)</span>
                                    </div>
                                </span>
                            </a>
                        </div>
                    <? } ?>
                </div>
            </div><? endforeach ?>
    </div>
<?php endif ?>
<?php if (!empty($g5_banner_new['BEST'])) : ?>
    <div id="campaign-best-wrapper" class="offset-campaign-top on-big" style="font-size: 0;width:1344px; height: 786px; margin: 0 auto; margin-top: 160px;">
        <div class="campaign-best-content campaign-image-rect" style="width: 100%; display:block; height : 105px;">
            <div class="campaign-title">BEST</div>
            <div class="campaign-subject"><?= $g5_banner_new['BEST'][0]['cp_subject'] ?></div>
            <div class="campaign-desc"><?= $g5_banner_new['BEST'][0]['cp_desc'] ?></div>
        </div>
        <?php
        $cp_item_set = json_decode($g5_banner_new['BEST'][0]['cp_item_set'], true);
        $cp_item_set_item = array();
        $cp_item_set_category = array();
        foreach ($cp_item_set as $cis) {
            if (!empty($cis['item'])) $cp_item_set_item[] = $cis['item'];
            if (!empty($cis['category'])) $cp_item_set_category[] = $cis['category'];
        }

        $sql_best_item = "SELECT * FROM {$g5['g5_shop_item_table']} WHERE ";
        $sql_best_where = array();
        if (!empty($cp_item_set_item)) $sql_best_where[] = " it_id IN(" . implode(',', $cp_item_set_item) . ")";
        if (!empty($cp_item_set_category)) {
            $cp_item_set_category = implode(',', $cp_item_set_category);
            foreach (explode(',', $cp_item_set_category) as $cp_ca_id) {
                $sql_best_where[] = "ca_id LIKE '{$cp_ca_id}%'";
            }
        }

        if (empty($sql_best_where)) {
            echo ("<!-- ITEM NOT SET CAMPAIGN[BEST]-->");
        } else {
            $sql_best_item = $sql_best_item . implode(' OR ', $sql_best_where) . " LIMIT 2";
            $db_best_item = sql_query($sql_best_item);
            while (($citem = sql_fetch_array($db_best_item)) != false) {
                $thumb = get_it_thumbnail_path($citem['it_img1'], 650, 650);
                if ($citem['it_discount_price'] != '' && $citem['it_discount_price'] != '0') {
                    $it_price = $citem['it_price'];
                    $it_sale_price = $citem['it_discount_price'];
                    $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                }
        ?>
                <div class="campaign-image-rect" style="background-image: url(<?= $thumb ?>);">
                    <a href="/shop/item.php?it_id=<?= $citem['it_id'] ?>">
                        <div class="campaign-item-rect-hover"><span class="btn-pick on-big <?= in_array($citem['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $citem['it_id'] ?>></span>
                            <div class="campaign-item-rect-content">
                                <div class="history-content-text-brand C2ENBLL"><?= $citem['it_brand'] ?></div>
                                <div class="history-content-text-item C1ENBLL"><?= $citem['it_name'] ?></div>
                                <div class="history-content-text-price C1ENBLL"><?= display_price(get_price($citem), $citem['it_tel_inq']); ?><span style="font-size: 12px;">원</span></div>
                                <div class="history-content-text-saleprice C2ENBLL">
                                    <span class="price-tag"><del><?= number_format($it_price + $it_sale_price) ?></del><span>원</span></span>
                                    <span class="price-dis" style="color: #e65026;">(<?= number_format($discount_ratio) ?>%)</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
        <? }
        } ?>
    </div>

    <div class="offset-campaign-top on-small" style="padding: 0 20px;">
        <div class="campaign-title">BEST</div>
        <div class="campaign-subject"><?= $g5_banner_new['BEST'][0]['cp_subject'] ?></div>
        <div class="campaign-desc" style="height: auto;"><?= $g5_banner_new['BEST'][0]['cp_desc'] ?></div>
    </div>
    <div id="campaign-best-wrapper-small" class="on-small" style="font-size: 0; width: 100%;">
        <div class="swiper-container" id ="swiper-containerBEST">
            <div class="swiper-wrapper">
            <?php
            if (empty($sql_best_where)) {
                echo ("<!-- ITEM NOT SET CAMPAIGN[BEST]-->");
            } else {
                $db_best_item = sql_query($sql_best_item);
                while (($citem = sql_fetch_array($db_best_item)) != false) {
                    $thumb = get_it_thumbnail_path($citem['it_img1'], 650, 650);
                    if ($citem['it_discount_price'] != '' && $citem['it_discount_price'] != '0') {
                        $it_price = $citem['it_price'];
                        $it_sale_price = $citem['it_discount_price'];
                        $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                    }
            ?>
                <div class="campaign-image-rect swiper-slide" style="background-image: url(<?= $thumb ?>);">
                    <div class="campaign-item-rect-hover"><span class="btn-pick on-big <?= in_array($citem['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $citem['it_id'] ?>></span>
                        <a href="/shop/item.php?it_id=<?= $citem['it_id'] ?>">
                            <div class="campaign-item-rect-content">
                                <div class="history-content-text-brand C2ENBLL"><?= $citem['it_brand'] ?></div>
                                <div class="history-content-text-item C1ENBLL"><?= $citem['it_name'] ?></div>
                                <div class="history-content-text-price C1ENBLL"><?= display_price(get_price($citem), $citem['it_tel_inq']); ?><span style="font-size: 12px;">원</span></div>
                                <div class="history-content-text-saleprice C2ENBLL">
                                    <span class="price-tag"><del><?= number_format($it_price + $it_sale_price) ?></del><span>원</span></span>
                                    <span class="price-dis" style="color: #e65026;">(<?= number_format($discount_ratio) ?>%)</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <? } ?>
            </div>
            <div class="swiper-pagination swiper-pagination-white"></div>
        </div>
        <? } ?>
        <script>
        var swiper3 = new Swiper('#swiper-containerBEST', {
            cssMode: true,
            loop: true,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            },
            // navigation: {
            //     nextEl: '.swiper-button-next',
            //     prevEl: '.swiper-button-prev',
            // },
            pagination: {
                el: '.swiper-pagination'
            },
            mousewheel: false,
            keyboard: true,
        });
        </script>
    </div>
    <!-- <div id="campaign-best-wrapper-dots" class="campaign-hot-selector on-small" style="text-align: center;">
        <? for ($bi = 0; $bi < $db_best_item->num_rows; $bi++) : ?><span></span><? endfor ?>
    </div> -->
<?php endif ?>
<?php if (!empty($g5_banner_new['NEW'])) : ?>
    <div id="campaign-new-wrapper" class="offset-campaign-top on-big" style="font-size: 0; height:786px; width:1344px; margin:0 auto; margin-top: 160px;">
        <div class="campaign-best-content campaign-image-rect" style="width: 1580px; height : 105px; display : block;">
            <div class="campaign-title">NEW ARRIVAL</div>
            <div class="campaign-subject"><?= $g5_banner_new['NEW'][0]['cp_subject'] ?></div>
            <div class="campaign-desc"><?= $g5_banner_new['NEW'][0]['cp_desc'] ?></div>
            <div class="campaign-best-more"><a href="<?= !empty($g5_banner_new['NEW'][0]['cp_link']) ? $g5_banner_new['NEW'][0]['cp_link'] : "/event/view.php?cp_id=" . $g5_banner_new['NEW'][0]['cp_id'] ?>"></a></div>
        </div><?php
                $cp_item_set = json_decode($g5_banner_new['NEW'][0]['cp_item_set'], true);
                $cp_item_set_item = array();
                $cp_item_set_category = array();
                foreach ($cp_item_set as $cis) {
                    if (!empty($cis['item'])) $cp_item_set_item[] = $cis['item'];
                    if (!empty($cis['category'])) $cp_item_set_category[] = $cis['category'];
                }

                $sql_new_item = "SELECT * FROM {$g5['g5_shop_item_table']} WHERE it_use=1 AND ";
                $sql_new_where = array();
                if (!empty($cp_item_set_item)) $sql_new_where[] = " it_id IN(" . implode(',', $cp_item_set_item) . ")";
                if (!empty($cp_item_set_category)) {
                    $cp_item_set_category = implode(',', $cp_item_set_category);
                    foreach (explode(',', $cp_item_set_category) as $cp_ca_id) {
                        $sql_new_where[] = "ca_id LIKE '{$cp_ca_id}%'";
                    }
                }

                if (empty($sql_new_where)) {
                    echo ("<!-- ITEM NOT SET CAMPAIGN[NEW]-->");
                } else {
                    $sql_new_item = $sql_new_item . implode(' OR ', $sql_new_where) . "  LIMIT 2";
                    $db_new_item = sql_query($sql_new_item);
                    while (($citem = sql_fetch_array($db_new_item)) != false) {
                        $thumb = get_it_thumbnail_path($citem['it_img1'], 650, 650);
                        if ($citem['it_discount_price'] != '' && $citem['it_discount_price'] != '0') {
                            $it_price = $citem['it_price'];
                            $it_sale_price = $citem['it_discount_price'];
                            $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                        }
                ?><div class="campaign-image-rect" style="background-image: url(<?= $thumb ?>);">
                    <a href="/shop/item.php?it_id=<?= $citem['it_id'] ?>">
                        <div class="campaign-item-rect-hover"><span class="btn-pick <?= in_array($citem['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $citem['it_id'] ?>></span>
                            <div class="campaign-item-rect-content">
                                <div class="history-content-text-brand C2ENBLL"><?= $citem['it_brand'] ?></div>
                                <div class="history-content-text-item C1ENBLL"><?= $citem['it_name'] ?></div>
                                <div class="history-content-text-price C1ENBLL"><?= display_price(get_price($citem), $citem['it_tel_inq']); ?><span style="font-size: 12px;">원</span></div>
                                <div class="history-content-text-saleprice C2ENBLL">
                                    <span class="price-tag"><del><?= number_format($it_price + $it_sale_price) ?></del><span>원</span></span>
                                    <span class="price-dis" style="color: #e65026;">(<?= number_format($discount_ratio) ?>%)</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div><? }
                } ?>
    </div>

    <div class="offset-campaign-top on-small" style="padding: 0 20px;">
        <div class="campaign-title">NEW ARRIVAL</div>
        <div class="campaign-subject"><?= $g5_banner_new['NEW'][0]['cp_subject'] ?></div>
        <div class="campaign-desc" style="height: auto;"><?= $g5_banner_new['NEW'][0]['cp_desc'] ?></div>
    </div>
    <div id="campaign-new-wrapper-small" class="offset-campaign-top on-small" style="font-size: 0; width: 100%;">
        <div class="swiper-container" id ="swiper-containerNEW">
            <div class="swiper-wrapper">
        <?php
        if (empty($sql_new_where)) {
            echo ("<!-- ITEM NOT SET CAMPAIGN[NEW]-->");
        } else {
            $db_new_item = sql_query($sql_new_item);
            while (($citem = sql_fetch_array($db_new_item)) != false) {
                $thumb = get_it_thumbnail_path($citem['it_img1'], 650, 650);
                if ($citem['it_discount_price'] != '' && $citem['it_discount_price'] != '0') {
                    $it_price = $citem['it_price'];
                    $it_sale_price = $citem['it_discount_price'];
                    $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                }
        ?>
            
                <div class="campaign-image-rect swiper-slide" style="background-image: url(<?= $thumb ?>);">
                    <div class="campaign-item-rect-hover"><span class="btn-pick <?= in_array($citem['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $citem['it_id'] ?>></span>
                        <a href="/shop/item.php?it_id=<?= $citem['it_id'] ?>">
                            <div class="campaign-item-rect-content">
                                <div class="history-content-text-brand C2ENBLL"><?= $citem['it_brand'] ?></div>
                                <div class="history-content-text-item C1ENBLL"><?= $citem['it_name'] ?></div>
                                <div class="history-content-text-price C1ENBLL"><?= display_price(get_price($citem), $citem['it_tel_inq']); ?><span style="font-size: 12px;">원</span></div>
                                <div class="history-content-text-saleprice C2ENBLL">
                                    <span class="price-tag"><del><?= number_format($it_price + $it_sale_price) ?></del><span>원</span></span>
                                    <span class="price-dis" style="color: #e65026;">(<?= number_format($discount_ratio) ?>%)</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            
        <? } ?>
            </div>
            <div class="swiper-pagination swiper-pagination-white"></div>
        </div>
        
        <?} ?>
        <script>
        var swiper2 = new Swiper('#swiper-containerNEW', {
            cssMode: true,
            loop: true,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            },
            // navigation: {
            //     nextEl: '.swiper-button-next',
            //     prevEl: '.swiper-button-prev',
            // },
            pagination: {
                el: '.swiper-pagination'
            },
            mousewheel: false,
            keyboard: true,
        });
        </script>
    </div>
    <!-- 모바일 스와이프로 변경 -->
    <!-- <div id="campaign-new-wrapper-dots" class="campaign-hot-selector on-small" style="text-align: center;">
        <? for ($bi = 0; $bi < $db_new_item->num_rows; $bi++) : ?><span></span><? endfor ?>
    </div> -->
<?php endif ?>
<?php if (!empty($g5_banner_new['HOT'])) : ?>
    <div id="campaign-hot-wrapper" class="offset-campaign-top on-big">
        <? foreach ($g5_banner_new['HOT'] as $ci => $hot) : ?>
            <a href="<?= !empty($hot['cp_link']) ? $hot['cp_link'] : "/event/view.php?cp_id=" . $hot['cp_id'] ?>">
                <div class="campaign-hot" style="background-image: url(/data/banner/<?= $hot['cp_image_1'] ?>);">
                    <a class="asdf" href="<?= !empty($hot['cp_link']) ? $hot['cp_link'] : "/event/view.php?cp_id=" . $hot['cp_id'] ?>"></a>
                    <div class="campaign-hot-content">
                        <div style="height: 465px;">
                            <div class="campaign-title">HOT DEAL</div>
                            <div class="campaign-subject"><?= $hot['cp_subject'] ?></div>
                            <div class="campaign-desc"><?= $hot['cp_desc'] ?></div>
                            <div class="campaign-md-more"><a href="<?= !empty($hot['cp_link']) ? $hot['cp_link'] : "/event/view.php?cp_id=" . $hot['cp_id'] ?>"></a></div>
                        </div>
                        <div class="campaign-hot-selector">
                            <?php for ($pi = 1; $pi <= count($g5_banner_new['HOT']); $pi++) : ?><span onclick="sliderHot.goTo(<?= $pi - 1 ?>)" class="<?= $pi == $ci + 1 ? "active" : "" ?>"></span><?php endfor ?>
                        </div>
                    </div>
                </div>
            </a>
        <? endforeach ?>
    </div>
    <div id="campaign-hot-wrapper-small" class="offset-campaign-top on-small">
        <? foreach ($g5_banner_new['HOT'] as $ci => $hot) : ?>
            <div style="width: 100%;">
                <a href="<?= !empty($hot['cp_link']) ? $hot['cp_link'] : "/event/view.php?cp_id=" . $hot['cp_id'] ?>">
                    <div class="campaign-hot" style="background-image: url(/data/banner/<?= $hot['cp_image_2'] ?>);"></div>
                </a>
                <div class="campaign-hot-content">
                    <div>
                        <div class="campaign-title">HOT DEAL</div>
                        <div class="campaign-subject"><?= $hot['cp_subject'] ?></div>
                        <div class="campaign-desc"><?= $hot['cp_desc'] ?></div>
                    </div>
                    <div class="campaign-hot-selector" style="text-align: center;">
                        <?php for ($pi = 1; $pi <= count($g5_banner_new['HOT']); $pi++) : ?><span onclick="sliderHot.goTo(<?= $pi - 1 ?>)" class="<?= $pi == $ci + 1 ? "active" : "" ?>"></span><?php endfor ?>
                    </div>
                </div>
            </div>
        <? endforeach ?>
    </div>
<?php endif ?>
<?php if (!empty($g5_banner_new['SEASON'])) : ?>
    <div id="season-best-wrapper" class="offset-campaign-top" style="font-size: 0;">
        <div class="campaign-title" style="margin-bottom: 15px;">SEASON BEST</div>
        <?php for ($si = 0; $si < count($g5_banner_new['SEASON']); $si++) : ?>
            <div class="campaign-season" style="<?= $si == 0 ? "margin-top: unset;" : "" ?>">
                <div class="season-best-text on-big">
                    <div class="season-best-title on-big"><?= $g5_banner_new['SEASON'][$si]['cp_subject'] ?> <span style = "font-family: NotoSansCJKkr;  font-size: 14px;  font-weight: normal;  font-stretch: normal;  font-style: normal;  line-height: normal;"><?= $g5_banner_new['SEASON'][$si]['cp_desc'] ?></span></div>

                </div>
                <div class="season-best-text-small on-small">
                    <div class="season-best-title on-small"><?= $g5_banner_new['SEASON'][$si]['cp_subject'] ?></div>
                    <div class="season-best-desc on-small"><?= $g5_banner_new['SEASON'][$si]['cp_desc'] ?></div>
                </div>
                <div class="swiper-container" id ="swiper-containerSEASON-<?= $si + 1 ?>">
                    
                <div id="season-best-item-row-<?= $si + 1 ?>" class="season-best-item-row swiper-wrapper">
                    
                    <?php
                    $cp_item_set = json_decode($g5_banner_new['SEASON'][$si]['cp_item_set'], true);
                    $cp_item_set_item = array();
                    $cp_item_set_category = array();
                    foreach ($cp_item_set as $cis) {
                        if (!empty($cis['item'])) $cp_item_set_item[] = $cis['item'];
                        if (!empty($cis['category'])) $cp_item_set_category[] = $cis['category'];
                    }
                    $sql_season_item = "SELECT * FROM {$g5['g5_shop_item_table']} WHERE it_use=1 AND ";
                    $sql_season_where = array();
                    if (!empty($cp_item_set_item)) $sql_season_where[] = " it_id IN(" . implode(',', $cp_item_set_item) . ")";
                    if (!empty($cp_item_set_category)) {
                        $cp_item_set_category = implode(',', $cp_item_set_category);
                        foreach (explode(',', $cp_item_set_category) as $cp_ca_id) {
                            $sql_season_where[] = "ca_id LIKE '{$cp_ca_id}%'";
                        }
                    }
                    $sql_season_item = $sql_season_item . implode(' OR ', $sql_season_where) . "   LIMIT 10";
                    $db_season_item = sql_query($sql_season_item);
                    $sitem = sql_fetch_array($db_season_item);

                    do {
                        $sitem_thumb = get_it_thumbnail_path($sitem['it_img1'], 290, 290);
                        if ($sitem['it_discount_price'] != '' && $sitem['it_discount_price'] != '0') {
                            $it_price = $sitem['it_price'];
                            $it_sale_price = $sitem['it_discount_price'];
                            $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                        }
                    ?>
                        <div class="swiper-slide">
                            <span class="season-item-image" data-id=<?= $sitem['it_id'] ?> style="background-image: url(<?= $sitem_thumb ?>);">
                                <span class="btn-pick on-big <?= in_array($sitem['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $sitem['it_id'] ?>></span>
                                <span class="season-item-image-hover" >
                                <a href = "/shop/item.php?it_id=<?= $sitem['it_id'] ?>">
                                    <span class="history-content-text-box on-big">
                                        <div class="history-content-text-brand C2ENBLL" style="font-size: 12px !important;"><?= $sitem['it_brand'] ?></div>
                                        <div class="history-content-text-item C1ENBLL" style="font-size: 16px !important;"><?= $sitem['it_name'] ?></div>
                                        <div class="history-content-text-price C1ENBLL" style="font-size: 20px !important;"><?= display_price(get_price($sitem), $sitem['it_tel_inq']); ?><span style="font-size: 12px;">원</span></div>
                                    <? if ($discount_ratio > 0) : ?>
                                        <div class="history-content-text-saleprice C2ENBLL" style="font-size: 12px !important;">
                                            <div class="price-tag"><del><?= number_format($it_price + $it_sale_price) ?></del><span>원</span>
                                                <span class="price-dis" style="color: #e65026;">(<?= number_format($discount_ratio) ?>%)</span>
                                            </div>
                                        </div>
                                    <? endif ?>
                                    </span>
                                    <span class="history-content-text-box on-small">
                                        <div class="history-content-text-brand C2ENBLL" style="font-size: 10px !important;"><?= $sitem['it_brand'] ?></div>
                                        <div class="history-content-text-item C1ENBLL" style="font-size: 14px !important;"><?= $sitem['it_name'] ?></div>
                                        <div class="history-content-text-price C1ENBLL" style="font-size: 14px !important;"><?= display_price(get_price($sitem), $sitem['it_tel_inq']); ?><span style="font-size: 12px;">원</span></div>
                                    <? if ($discount_ratio > 0) : ?>
                                        <div class="history-content-text-saleprice C2ENBLL" style="font-size: 10px !important;">
                                            <div class="price-tag" style="font-size: 10px !important;"><del><?= number_format($it_price + $it_sale_price) ?></del><span>원</span>
                                                <span class="price-dis" style="color: #e65026;">(<?= number_format($discount_ratio) ?>%)</span>
                                            </div>
                                        </div>
                                    <? endif ?>
                                    </span>
                                </a>
                            </span><br>

                            
                        </div>
                    <? }  while (($sitem = sql_fetch_array($db_season_item)) != false) ?>
                </div>
                </div>
                <script>
                    var deviceChk = 5;
                    var mobileKeyWords = new Array('iPhone', 'iPod', 'BlackBerry', 'Android', 'Windows CE', 'LG', 'MOT', 'SAMSUNG', 'SonyEricsson');
                    
                    if( ( navigator.userAgent.indexOf('Mac OS') > 0 && navigator.userAgent.indexOf('Safari') <= 0 )|| ( ( navigator.userAgent.indexOf('Android') > 0 && navigator.userAgent.indexOf('wv)') > 0 ) ) ){
                        // 모바일 앱 웹뷰 
                        deviceChk = 2;
                    }else{
                        // 모바일웹 
                        deviceChk = 2;
                        for (var word in mobileKeyWords){
                            if (navigator.userAgent.match(mobileKeyWords[word]) != null){
                                deviceChk = 2;
                                break;
                            }else{
                                if($(window).width() < 700){
                                    deviceChk = 2;
                                }else {
                                    deviceChk = 5;
                                }
                            }
                        }
                    }

                    var swiper2 = new Swiper('#swiper-containerSEASON-<?= $si + 1 ?>', {
                        slidesPerView: deviceChk,
                        centeredSlides: false,
                        spaceBetween: 30,
                        grabCursor: true,
                        cssMode: true,
                        loop: false,
                        mousewheel: false,
                        keyboard: true,
                    });
                    </script>
            </div>
        <?php endfor ?>
    </div>
<?php endif ?>
<?php if (!empty($g5_banner_new['EVENT'])) : ?>
    <div id="campaign-event-wrapper" class="offset-campaign-top">
        <div class="on-big" style="margin-bottom: 20px;">
            <div class="campaign-title">EVENT</div>
        </div>
        <div class="campaign-title on-small">EVENT</div><br>
        <? foreach ($g5_banner_new['EVENT'] as $ci => $event) : ?><div class="campaign-event">
                <?php if ($ci >= 3) break; ?>
                <div class="campaign-event-name event-index-<?= $ci ?>"><?= $event['cp_subject'] ?></div>
                <a href="<?= !empty($event['cp_link']) ? $event['cp_link'] : "/event/view.php?cp_id=" . $event['cp_id'] ?>">
                    <div class="campaign-event-image" style="background-image: url(/data/banner/<?= $event['cp_image_1'] ?>);"></div>
                </a>
                <div class="campaign-desc"><?= $event['cp_desc'] ?></div>
            </div><? endforeach ?>
    </div>
<?php endif ?>

<div class="offset-campaign-top"></div>
<script>
    // const sliderMain = tns({
    //     container: '#main-banner-wrapper',
    //     controls: true,
    //     nav: true,
    //     autoplayButtonOutput: false,
    //     autoplay: true,
    //     speed: 400,
    //     items: 1,
    //     navContainer: "#main-banner-wrapper-dots"
    // });
    const sliderTheme = tns({
        container: '#campaign-theme-wrapper',
        controls: false,
        nav: true,
        autoplayButtonOutput: false,
        autoplay: false,
        items: 1,
        speed: 400,
        mode: 'gallery',
        navContainer: "#campaign-theme-wrapper-dots"
    });
    // const sliderMd = tns({
    //     container: ".campaign-md",
    //     autoWidth: true,
    //     mouseDrag: true,
    //     loop: false,
    //     controls: false,
    //     nav: false,
    //     swipeAngle: false,
    //     autoplayButtonOutput: false,
    //     speed: 400
    // });
    // const sliderHot = tns({
    //     container: '#campaign-hot-wrapper',
    //     controls: false,
    //     nav: false,
    //     autoplayButtonOutput: false,
    //     autoplay: true,
    //     items: 1,
    //     speed: 400,
    //     mode: 'gallery'
    // });
    // const sliderSeason1 = tns({
    //     container: "#season-best-item-row-1",
    //     autoWidth: true,
    //     mouseDrag: true,
    //     loop: false,
    //     controls: false,
    //     nav: false,
    //     swipeAngle: false,
    //     autoplayButtonOutput: false,
    //     gutter: 16,
    //     speed: 400,
    //     responsive: {
    //         1024: {
    //             gutter: 24,
    //             edgePadding: 0
    //         }
    //     }
    // });
    // const sliderSeason2 = tns({
    //     container: "#season-best-item-row-2",
    //     autoWidth: true,
    //     mouseDrag: true,
    //     loop: false,
    //     controls: false,
    //     nav: false,
    //     swipeAngle: false,
    //     autoplayButtonOutput: false,
    //     gutter: 16,
    //     speed: 400,
    //     responsive: {
    //         1024: {
    //             gutter: 24,
    //             edgePadding: 0
    //         }
    //     }
    // });
    // const sliderSeason3 = tns({
    //     container: "#season-best-item-row-3",
    //     autoWidth: true,
    //     mouseDrag: true,
    //     loop: false,
    //     controls: false,
    //     nav: false,
    //     swipeAngle: false,
    //     autoplayButtonOutput: false,
    //     gutter: 16,
    //     speed: 400,
    //     responsive: {
    //         1024: {
    //             gutter: 24,
    //             edgePadding: 0
    //         }
    //     }
    // });

    /*

    const sliderSeason1MO = tns({
        container: "#season-best-1-small > .season-item-row",
        autoWidth: true,
        mouseDrag: false,
        loop: false,
        controls: false,
        nav: false,
        swipeAngle: false,
        autoplayButtonOutput: false,
        speed: 400
    });
    const sliderSeason2MO = tns({
        container: "#season-best-2-small > .season-item-row",
        autoWidth: true,
        mouseDrag: false,
        loop: false,
        controls: false,
        nav: false,
        swipeAngle: false,
        autoplayButtonOutput: false,
        speed: 400
    });
    const sliderSeason3MO = tns({
        container: "#season-best-3-small > .season-item-row",
        autoWidth: true,
        mouseDrag: false,
        loop: false,
        controls: false,
        nav: false,
        swipeAngle: false,
        autoplayButtonOutput: false,
        speed: 400
    });
    */
    // const sliderBestMO = tns({
    //     container: "#campaign-best-wrapper-small",
    //     autoWidth: true,
    //     mouseDrag: true,
    //     loop: false,
    //     controls: false,
    //     nav: false,
    //     swipeAngle: false,
    //     autoplayButtonOutput: false,
    //     speed: 400
    // });
    // const sliderNewMO = tns({
    //     container: "#campaign-new-wrapper-small",
    //     autoWidth: true,
    //     mouseDrag: true,
    //     loop: false,
    //     controls: false,
    //     nav: false,
    //     swipeAngle: true,
    //     autoplayButtonOutput: false,
    //     speed: 400
    // });

    // sliderMd.events.on("dragMove", function(container) {
    //     $(container.container).addClass("ondrag");
    // });

    // sliderMd.events.on("transitionEnd", function(container) {
    //     setTimeout(function() {
    //         $(container.container).removeClass("ondrag");
    //     }, 300);
    // });

    // sliderSeason1.events.on("dragMove", function(container) {
    //     $(container.container).addClass("ondrag");
    // });
    // sliderSeason1.events.on("transitionEnd", function(container) {
    //     setTimeout(function() {
    //         $(container.container).removeClass("ondrag");
    //     }, 300);
    // });
    // sliderSeason2.events.on("dragMove", function(container) {
    //     $(container.container).addClass("ondrag");
    // });
    // sliderSeason2.events.on("transitionEnd", function(container) {
    //     setTimeout(function() {
    //         $(container.container).removeClass("ondrag");
    //     }, 300);
    // });
    // sliderSeason3.events.on("dragMove", function(container) {
    //     $(container.container).addClass("ondrag");
    // });
    // sliderSeason3.events.on("transitionEnd", function(container) {
    //     setTimeout(function() {
    //         $(container.container).removeClass("ondrag");
    //     }, 300);
    // });

    // $(".season-item-image .history-content-text-box").on("click", function(evt) {
    //     if ($(this).closest(".season-best-item-row").hasClass("ondrag") == false) location.href = "/shop/item.php?it_id=" + $(this).data("id");
    // });

    
    

    $(".season-item-image-hover").on("click", function(evt) {
        alert("asdf");
    });

    $(".season-item-image-hover").click(function(e){
        e.stopPropagation();
        alert("asdf");
        
    }

    $(".campaign-image-item").on("touchstart", function(evt) {
        alert("asdf");
    }


    $(".campaign-image-item").on("click", function(evt) {
        if ($(this).closest(".season-best-item-row").hasClass("ondrag") == false) location.href = "/shop/item.php?it_id=" + $(this).data("id");
    });

    $(document).ready(function() {
        if ($("#campaign-hot-wrapper-small > div").length > 1) {
            const sliderHotMO = tns({
                container: "#campaign-hot-wrapper-small",
                autoWidth: true,
                mouseDrag: true,
                loop: false,
                controls: false,
                nav: false,
                swipeAngle: false,
                autoplayButtonOutput: false,
                speed: 400
            });
        }
    });
</script>
<?php
$contents = ob_get_contents();
ob_end_clean();

return $contents;
?>