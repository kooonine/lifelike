<?php
$g5_title = "브랜드";
ob_start();
$tmp_menu = explode(',', $m_path);
$current_menu = $g5_menu[$tmp_menu[0]];
?>
<style>
    .banner-brand {
        background: url() center center no-repeat;
        background-size: cover;
    }

    .banner-brand-info {
        width: 80%;
        display: inline-block;
        padding: 32px 0 32px 60px;
        font-size: 0;
        color: #ffffff;
    }

    .product-list-menu-item>div>a {
        vertical-align: sub;
    }

    @media (max-width: 1366px) {
        #offset-nav-top {
            height: 128px;
            margin-bottom: 0;
        }
    }
</style>

<? $uri = $_SERVER['REQUEST_URI']; ?>

<div id="list-wrapper">
    <div class="nav-product-list on-small">

    </div>
    <div class="product-list-menu on-big">
        <div class="product-list-menu-title"><?= $current_menu['me_name'] ?></div>
        <div class="product-list-menu-item">
            <?= $current_menu['me_name'] == '브랜드' ? '<div><a class="' . (strpos($uri, 'br_id=') ? '' : 'active') . '" href="' . $current_menu['me_link'] . '">전체</a></div>' : '' ?>
            <?php
            $menu_idx = 1;
            foreach ($current_menu['SUB'] as $sm) {
                echo '<div><a class="' . (strpos($uri, 'br_id=' . $menu_idx . '') ? 'active' : '') . '" href="' . $sm['me_link'] . '">' . $sm['me_name'] . '</a></div>';
                $menu_idx++;
            }
            // foreach ($brands as $brands_ti) {
            //     echo '<div><a class="' . (strpos($uri, 'br_id=' . $menu_idx . '') ? 'active' : '') . '" href="/shop/brand.php?br_id=' . $brands_ti['br_id'] . '">' . $brands_ti['br_name'] . '</a></div>';
            //     $menu_idx++;
            // }
            ?>
        </div>
        <!-- <? foreach ($g5_banner_new['ETC'] as $listba) : ?>
            <? if ($br_id == '' && $listba['ca_id'] == 999999) : ?>
                <a href="<?= !empty($listba['cp_link']) ? $listba['cp_link'] : "/event/view.php?cp_id=" . $listba['cp_id'] ?>">
                    <div onclick="location.href='<?= $$listba['cp_link'] ?>'" class="product-list-menu-banner" style="background-image: url(/data/banner/<?= $listba['cp_image_1'] ?>);"></div>
                </a>
            <? else : ?>
                <? if ($listba['ca_id'] == $br_id && $listba['ba_position'] == 'LIST_LEFT') : ?>
                    <a href="<?= !empty($listba['cp_link']) ? $listba['cp_link'] : "/event/view.php?cp_id=" . $listba['cp_id'] ?>">
                        <div onclick="location.href='<?= $$listba['cp_link'] ?>'" class="product-list-menu-banner" style="background-image: url(/data/banner/<?= $listba['cp_image_1'] ?>);"></div>
                    </a>
                <? endif ?>
            <? endif ?>
        <? endforeach ?> -->
    </div>

    <div class="brand-list-wrapper">
        <!-- <div class="brand-list-path on-big"><?php echo $g5_menu_path ?></div> -->
        <?php if (!isset($br_id)) : ?>
            <div class="brand-list-banner-main"></div>
        <?php endif ?>
        <?php if (isset($brands)) : ?>
            <?php if (empty($brands)) : ?>
                <div style="font-size: 18px;font-weight: 500;text-align: center;color: #000000;margin-top: 130px;margin-bottom: unset;">아직 설정한 Brand Pick이 없습니다.</div>
                <div style="font-size: 26px;font-weight: 800;text-align: center;color: #000000;">LET’S FIND YOUR PICK!</div>
            <? else : ?>
                <div class="brand-list on-big">
                    <!-- <div id="list-action-wrapper">
                        <a href="/shop/brand.php?br_id=pick">
                            <button type="button" class="btn btn-black" style="font-size: 12px; margin-top: unset; width: 96px; height: 32px;">YOU PICK</button>
                        </a>
                    </div> -->
                    <?php $idx = 0;
                    foreach ($brands as $brand) : ?>
                        <?php if ($idx < 4) : ?>
                            <a href="/shop/brand.php?br_id=<?= $brand['br_id'] ?>"><?= $idx ?>
                                <div class="brand-list-brand" style="background-image: url(/data/brand/<?= $brand['br_main_image'] ?>);">
                                    <div class="brand-list-logo-shadow"></div>
                                    <span class="btn-pick-heart on-big <?= in_array($brand['br_id'], $g5_picked['BRAND']) ? "picked" : "" ?>" data-type="brand" data-pick=<?= $brand['br_id'] ?> style="margin-right: unset; margin-left: -66px; position: absolute;"></span>
                                    <?php if (G5_IS_IE) : ?>
                                        <div class="brand-list-logo-wrapper" style="background-color: transparent;">
                                            <img style="width: 60%; display: block; margin-left: auto; margin-right: auto; margin-top: 130px; " src="/data/brand/<?= $brand['br_logo'] ?>">
                                        </div>
                                    <? else : ?>
                                        <div class="brand-list-logo-wrapper" style="-webkit-mask-image: url(/data/brand/<?= $brand['br_logo'] ?>); width: 60%; display: block; margin-left: auto; margin-right: auto;"></div>
                                    <? endif ?>
                                </div>
                            </a>
                        <?php elseif ($idx == 4) : ?>
                            <div class="swiper-container" id="brand-swiper">
                                <div class="swiper-wrapper">
                                    <a class="swiper-slide" href="/shop/brand.php?br_id=<?= $brand['br_id'] ?>"><?= $idx ?>
                                        <div class="brand-list-brand" style="background-image: url(/data/brand/<?= $brand['br_main_image'] ?>);">
                                            <div class="brand-list-logo-shadow"></div>
                                            <span class="btn-pick-heart on-big <?= in_array($brand['br_id'], $g5_picked['BRAND']) ? "picked" : "" ?>" data-type="brand" data-pick=<?= $brand['br_id'] ?> style="margin-right: unset; margin-left: -66px; position: absolute;"></span>
                                            <?php if (G5_IS_IE) : ?>
                                                <div class="brand-list-logo-wrapper" style="background-color: transparent;">
                                                    <img style="width: 60%; display: block; margin-left: auto; margin-right: auto; margin-top: 130px;" src="/data/brand/<?= $brand['br_logo'] ?>">
                                                </div>
                                            <? else : ?>
                                                <div class="brand-list-logo-wrapper" style="-webkit-mask-image: url(/data/brand/<?= $brand['br_logo'] ?>); width: 60%; display: block; margin-left: auto; margin-right: auto;"></div>
                                            <? endif ?>
                                        </div>
                                    </a>
                                <?php else : ?>
                                    <a class="swiper-slide" href="/shop/brand.php?br_id=<?= $brand['br_id'] ?>"><?= $idx ?>
                                        <div class="brand-list-brand" style="background-image: url(/data/brand/<?= $brand['br_main_image'] ?>);">
                                            <div class="brand-list-logo-shadow"></div>
                                            <span class="btn-pick-heart on-big <?= in_array($brand['br_id'], $g5_picked['BRAND']) ? "picked" : "" ?>" data-type="brand" data-pick=<?= $brand['br_id'] ?> style="margin-right: unset; margin-left: -66px; position: absolute;"></span>
                                            <?php if (G5_IS_IE) : ?>
                                                <div class="brand-list-logo-wrapper" style="background-color: transparent;">
                                                <img style="width: 60%; display: block; margin-left: auto; margin-right: auto; margin-top: 130px;" src="/data/brand/<?= $brand['br_logo'] ?>">
                                                </div>
                                            <? else : ?>
                                                <div class="brand-list-logo-wrapper" style="-webkit-mask-image: url(/data/brand/<?= $brand['br_logo'] ?>); width: 60%; display: block; margin-left: auto; margin-right: auto;"></div>
                                            <? endif ?>
                                        </div>
                                    </a>

                                <?php endif ?>
                            <?php $idx++;
                        endforeach ?>
                                </div>
                                <!-- <div class="swiper-button-next swiper-button-black"></div>
                                <div class="swiper-button-prev swiper-button-black"></div> -->
                            </div>
                            <script>
                                var swiper1 = new Swiper('#brand-swiper', {
                                    slidesPerView: 4,
                                    centeredSlides: false,
                                    spaceBetween: 20,
                                    grabCursor: false,
                                    // navigation: {
                                    //     nextEl: '.swiper-button-next',
                                    //     prevEl: '.swiper-button-prev',
                                    // },
                                    cssMode: false,
                                    loop: false,
                                    keyboard: false,
                                });
                            </script>

                </div>

                <div class="brand-list on-small">
                    <?php foreach ($brands as $brand) : ?>
                        <a href="/shop/brand.php?br_id=<?= $brand['br_id'] ?>"><?= $idx ?>
                            <div class="brand-list-brand" style="background-image: url(/data/brand/<?= $brand['br_main_image'] ?>);">
                                <div class="brand-list-logo-shadow"></div>
                                <span class="btn-pick-heart <?= in_array($brand['br_id'], $g5_picked['BRAND']) ? "picked" : "" ?>" data-type="brand" data-pick=<?= $brand['br_id'] ?> style="margin-right: unset; margin-left: -66px; position: absolute;"></span>
                                <?php if (G5_IS_IE) : ?>
                                    <div class="brand-list-logo-wrapper" style="background-color: transparent;">
                                        <img src="/data/brand/<?= $brand['br_logo'] ?>">
                                    </div>
                                <? else : ?>
                                    <div class="brand-list-logo-wrapper" style="-webkit-mask-image: url(/data/brand/<?= $brand['br_logo'] ?>); width: 72%; display: block; margin-left: auto; margin-right: auto;"></div>
                                <? endif ?>
                            </div>
                        </a>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
        <?php else : ?>
            <div class="brand-list-banner-main banner-brand on-big" style="cursor: auto; background-image: url(/data/brand/<?= $brand['br_main_image'] ?>); position: relative;">
                <span class="banner-brand-info">
                    <div style="margin-bottom: 80px;">
                        <span style="font-size: 48px; font-weight: 800;"><?= $brand['br_name_en'] ?>
                            <span style="font-size: 26px; font-weight: bold;"><?= $brand['br_name'] ?></span>
                        </span>
                    </div>
                    <div><span style="font-size: 16px; font-weight: bold;"><?= $brand['br_slogan'] ?></span></div>
                    <div>
                        <div style="margin: 8px 0; border-top: 1px solid #ffffff; width: 50%;"></div>
                        <span style="font-size: 14px;"><?= $brand['br_desc'] ?></span>
                    </div>
                </span>
                <span class="btn-pick-heart on-big <?= in_array($brand['br_id'], $g5_picked['BRAND']) ? "picked" : "" ?>" data-type="brand" data-pick=<?= $brand['br_id'] ?>></span></div>
            <div class="brand-list-banner-main banner-brand on-big" style="height: 550px; margin-top: 72px; cursor: auto; background-image: url(/data/brand/<?= $brand['br_lookbook'] ?>);"></div>

            <div class="on-small" style= "margin: 40px 0 16px 0;">
                <div class="on-small" style="text-align:center;font-size: 16px;  font-weight: 500;  line-height: normal;  color: #4c4c4c;"><?= $brand['br_name_en'] ?></div>
                <div class="on-small" style="text-align:center;font-size: 16px;  font-weight: 500;  line-height: normal;  color: #4c4c4c;"><?= $brand['br_name'] ?></div>
            </div>
            
            <div class="brand-list-banner-main banner-brand on-small" style="width:100vw; height : 100vw; cursor: auto; background-image: url(/data/brand/<?= $brand['br_main_image_mobile'] ?>); position: relative;">
                <span class="btn-pick-heart <?= in_array($brand['br_id'], $g5_picked['BRAND']) ? "picked" : "" ?>" data-type="brand" data-pick=<?= $brand['br_id'] ?>></span>
            </div>
            <div class="on-small" style="font-size: 14px; padding: 0 20px; margin-top: 14px;"><?= $brand['br_slogan'] ?></div>
            <div class="on-small" style="font-size: 10px; padding: 0 20px; margin-top: 6px;"><?= $brand['br_desc'] ?></div>
            <div class="brand-list-banner-main banner-brand on-small" style="width:100vw; height : 100vw; cursor: auto; background-image: url(/data/brand/<?= $brand['br_lookbook_mobile'] ?>);">
            </div>
            <?
            if (isset($filter_view)) echo $filter_view;
            if (isset($list_view)) echo $list_view;
            ?>
            <div id="add_brand_items" class="on-small"></div>
            <?if (!empty($paging)) :?>
                <div class="on-big"><?= $paging ?></div>
                <div class="on-small add_brand_btn"><a onclick="addList_brand(<?= $br_id ?>,<?= $total_page?>)">더보기</a></div>
            <?endif?>
            
        <?php endif ?>
    </div>
</div>

<script>
    var itemlist_ca_id = "<? echo $ca_id; ?>";

    var add_brand_page = 2;

    function addList_brand(id,totalPage) {

        //var filter_faq = $('#form-faq-filter').val();
        
        
        $.ajax({
            url: '/ajax_front/ajax.brand.list.php',
            type: 'post',
            data: {
                br_id: id,
                add_page: add_brand_page
                
            },

            success: function(response) {
                $('#add_brand_items').append(response);
                add_brand_page++;
            }
        });
        if (add_brand_page >= totalPage) {
            $('.add_brand_btn').css('display', 'none');
        }
    }

</script>
<script src="<? echo G5_JS_URL; ?>/shop.list.js"></script>
<script>
    $(document).ready(function() {
        let thumbLazy = $("div.thumb-lazy");
        let active = false;
        let urlCheck =window.location.href
        if(urlCheck.indexOf('br_id=4') != -1 || urlCheck.indexOf('br_id=5') != -1 || urlCheck.indexOf('br_id=6') != -1 || urlCheck.indexOf('br_id=7') != -1) {
            $('.nav-product-list-category-item').scrollLeft((320));
        } else if (urlCheck.indexOf('br_id=8') != -1) {
            $('.nav-product-list-category-item').scrollLeft((450));
        } 

        if ("IntersectionObserver" in window) {
            let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        let lazyImage = entry.target;
                        lazyImage.style.backgroundImage = "url(" + lazyImage.dataset.image + ")";
                        lazyImage.classList.remove("thumb-lazy");
                        lazyImageObserver.unobserve(lazyImage);
                    }
                });
            });

            thumbLazy.each(function(li, le) {
                lazyImageObserver.observe(le);
            });
        } else {
            const lazyLoad = function() {
                if (active === false) {
                    active = true;

                    setTimeout(function() {
                        thumbLazy.each(function(lazyIndex, lazyImage) {
                            if ((lazyImage.getBoundingClientRect().top <= window.innerHeight && lazyImage.getBoundingClientRect().bottom >= 0) && getComputedStyle(lazyImage).display !== "none") {
                                lazyImage.style.backgroundImage = "url(" + lazyImage.dataset.image + ")";
                                lazyImage.classList.remove("thumb-lazy");

                                thumbLazy = thumbLazy.filter(function(image) {
                                    return image !== lazyImage;
                                });

                                if (thumbLazy.length === 0) {
                                    document.removeEventListener("scroll", lazyLoad);
                                    window.removeEventListener("resize", lazyLoad);
                                    window.removeEventListener("orientationchange", lazyLoad);
                                }
                            }
                        });

                        active = false;
                    }, 200);
                }
            };

            document.addEventListener("scroll", lazyLoad);
            window.addEventListener("resize", lazyLoad);
            window.addEventListener("orientationchange", lazyLoad);
        }

        $("#btn-toggle-filter").on("click", function() {
            $("#filter-wrapper").toggleClass("active");
            if ($("#filter-wrapper").hasClass('active')) {
                $("#btn-toggle-filter").addClass('active');
                $("#btn-toggle-filter img").attr('src', '/img/re/select-filter.png');
                $("#btn-toggle-filter img").attr('srcset', '/img/re/select-filter@2x.png 2x,/img/re/select-filter@3x.png 3x');
            } else {
                $("#btn-toggle-filter").removeClass('active');
                $("#btn-toggle-filter img").attr('src', '/img/re/filter.png');
                $("#btn-toggle-filter img").attr('srcset', '/img/re/filter@2x.png 2x,/img/re/filter@3x.png 3x');
            }
        });
    });
</script>
<?php
$contents = ob_get_contents();
ob_end_clean();

return $contents;
?>