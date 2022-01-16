<?
include_once('./_common.php');

define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH . '/index.php');
    return;
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH . '/index.php');
    return;
}

define("_INDEX_", TRUE);

$indexok = true;

if ($preview_main_id && $preview_main_id != 1) {
    $indexok = false;
}

include_once(G5_PATH . '/head.php');

require_once(G5_LIB_PATH . '/badge.lib.php');
$badgeObj = new badge();
$sql_common = " from lt_design_main ";
$sql_where = " where (1) ";

//$previewonofflist = array();
if ($_POST['previewonofflist']) {
    $previewonofflist = json_decode(str_replace('\\', '', $previewonofflist), true);
}
?>
<!-- container -->
<div id="container">

    <?
    $sql = " select * $sql_common $sql_where and main_id <= 6 ";
    if ($preview_main_id) {
        $sql .= " and main_id = '" . $preview_main_id . "'";
    }
    $sql .= " order by main_order ";

    $result = sql_query($sql);
    $i = 0;

    while ($row = sql_fetch_array($result)) {
        $main_id = $row['main_id'];

        if ($previewonofflist[$main_id] != '' && $previewonofflist[$main_id] == "N") continue;
        if ($row['main_onoff'] == "N") continue;

        $main_view_data = json_decode(str_replace('\\', '', $row['main_view_data']), true);


        switch ($row['main_id']) {
            case 1: {
    ?>
                    <script>
                        $('#gnbTitle').html('<?= $main_view_data['title_name'] ?>');
                    </script>
                <?
                }
                break;
            case 2: {
                ?>
                    <!-- visual -->
                    <div class="main_visual">
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                <? for ($i = 0; $i < $row['main_type2']; $i++) {
                                    $img_data = $main_view_data['imgFile'][$i];
                                    $link_url = $img_data['linkURL'];
                                    $img_url = G5_DATA_URL . '/design/' . $main_id . '/' . $img_data['imgFile'];
                                ?>
                                    <div class="swiper-slide"><a href="<?= $link_url ?>"><img src="<?= $img_url ?>" alt="" style="width: 100%;" /></a></div>
                                <? } ?>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                    <script>
                        var swiperMain_visual = new Swiper('.main_visual .swiper-container', {
                            slidesPerView: 'auto',
                            spaceBetween: 0,
                            loop: true,
                            autoplay: {
                                delay: 4000,
                                disableOnInteraction: false,
                            },
                            pagination: {
                                el: '.swiper-pagination',
                                clickable: true,
                            },
                        });
                    </script>
                <?
                }
                break;
            case 3: {
                ?>
                    <div class="main_content">
                        <?
                        $movieimg_url = G5_DATA_URL . '/design/' . $main_id . '/' . $main_view_data['movieimg'];
                        $moviefile_url = G5_DATA_URL . '/design/' . $main_id . '/' . $main_view_data['moviefile'];

                        $link_url = $main_view_data['linkURL'];
                        ?>
                        <!-- column_group -->
                        <div class="column_group" style="display: none;">
                            <h3 class="blind"><?= $main_view_data['title_name'] ?></h3>
                            <div class="column_one">
                                <ul>
                                    <li>
                                        <?php if ($main_view_data['moviefile'] != "") { ?>
                                            <video controls poster="<?= $movieimg_url ?>" width="711" height="748">
                                                <source src="<?= $moviefile_url ?>" type="video/mp4" width="711" height="748">
                                                Your browser does not support the video tag.
                                            </video>
                                        <?php } else { ?>
                                            <a href="<?= $link_url ?>">
                                                <div class="photo"><img src="<?= $movieimg_url ?>" alt="" width="711" height="748" /></div>
                                            </a>
                                        <?php } ?>
                                    </li>

                                <?
                            }
                            break;
                        case 4: {
                                ?>
                                    <li>
                                        <div class="column_swiper">
                                            <div class="swiper-wrapper">
                                                <? for ($i = 0; $i < $row['main_type2']; $i++) {
                                                    $img_data = $main_view_data['imgFile'][$i];
                                                    $link_url = $img_data['linkURL'];
                                                    $img_url = G5_DATA_URL . '/design/' . $main_id . '/' . $img_data['imgFile'];
                                                ?>
                                                    <div class="swiper-slide"><a href="<?= $link_url ?>"><img src="<?= $img_url ?>" alt="" width="711" /></a></div>
                                                <? } ?>
                                            </div>
                                            <div class="swiper-pagination"></div>
                                        </div>
                                        <script>
                                            var swiperMain_visual = new Swiper('.column_swiper', {
                                                slidesPerView: 'auto',
                                                spaceBetween: 0,
                                                loop: true,
                                                /*
                                                autoplay: {
                                                	delay: 4000,
                                                	disableOnInteraction: false,
                                                },
                                                */
                                                pagination: {
                                                    el: '.swiper-pagination',
                                                    clickable: true,
                                                },
                                            });
                                        </script>
                                    </li>
                                </ul>
                            </div>
                        <?
                            }
                            break;
                        case 5: {
                        ?>
                            <div class="column_two">
                                <h3 class="blind"><?= $main_view_data['title_name'] ?></h3>
                                <ul class="thumb_list col2">
                                    <?
                                    for ($i = 0; $i < $row['main_type2']; $i++) {
                                        $sql2 = "select it_id, it_name,it_basic,it_price, it_img1, it_id, it_rental_price, it_item_type, it_view_list_items, it_time, ca_id from lt_shop_item where it_id = '{$main_view_data['it_id'][$i]}' and it_use = 1";
                                        $row2 = sql_fetch($sql2);
                                        if ($row2) {
                                            $img_data = $row2['it_img1'];
                                            $img_file = G5_DATA_PATH . '/item/' . $img_data;
                                            $link_url = G5_URL . '/shop/item.php?it_id=' . $row2['it_id'];
                                            $img_url = G5_DATA_URL . '/item/' . $img_data;

                                            $sqlwish = " select count(*) as cnt, ifnull(sum(case when mb_id = '" . $member['mb_id'] . "' then 1 else 0 end),0) as wishis from lt_shop_wish where it_id ='" . $row2['it_id'] . "' ";
                                            $rowwish = sql_fetch($sqlwish);

                                            $badgeObj->item = $row2;
                                            $badgeObj->innerHtml = "<img src='{$img_url}' />";
                                            $badgeObj->makeHtml();
                                    ?>
                                            <li>
                                                <a href="<?= $link_url ?>"><?= $badgeObj->photoHtml ?>
                                                    <div class="cont ">
                                                        <span class="title ellipsis"><?= $row2['it_name'] ?></span>
                                                        <span class="txt ellipsis"><?= $row2['it_basic'] ?></span>
                                                        <span class="price"><?= ($row2['it_item_type']) ? number_format($row2['it_rental_price']) : number_format($row2['it_price']) ?> 원</span>
                                                    </div>
                                                </a>
                                                <?
                                                echo "<div class=\"btn_comm big bottom\"><!-- 찜 눌르면 class=\"on\" 추가 --> ";
                                                echo "<button type=\"button\" onclick=\"javascript:item_wish_ajax('" . $row2['it_id'] . "');\" class=\"pick ico " . (($rowwish['wishis'] != '0') ? 'on' : '') . "\" it_id=\"" . $row2['it_id'] . "\"><span class=\"blind\">찜</span>" . $rowwish['cnt'] . "</button>";
                                                echo "</div>";
                                                ?>
                                            </li>
                                    <? }
                                    } ?>
                                </ul>
                                <!-- <a href="<?= G5_URL . '/shop/' ?>" class="btn_more"><span class="blind">더보기</span></a> -->
                                <script>
                                    // 상품보관
                                    function item_wish_ajax(it_id) {
                                        if ($(".pick[it_id='" + it_id + "']").attr("class").indexOf("on") < 0) {
                                            $.post("<?= G5_SHOP_URL; ?>/wishupdate2.php", {
                                                it_id: it_id
                                            }, function(data) {
                                                var responseJSON = JSON.parse(data);
                                                if (responseJSON.result == "S") {
                                                    if (confirm("관심상품에 저장되었습니다. 보러가시겠습니까?")) {
                                                        location.href = '<?= G5_SHOP_URL; ?>/wishlist.php';
                                                    }
                                                    //$(".pick[it_id='" + it_id + "']").removeClass("on").addClass('on');
                                                    $pick = $(".pick[it_id='" + it_id + "']");
                                                    $pick.removeClass('on').addClass('on');

                                                    if (responseJSON.count) {
                                                        $pick.text('');
                                                        $pick.append('<span class="blind">찜</span>' + responseJSON.count);
                                                    }
                                                } else {
                                                    alert(responseJSON.alert);
                                                    return false;
                                                }
                                            });
                                        } else {
                                            $.post("<?= G5_SHOP_URL; ?>/wishupdate2.php", {
                                                it_id: it_id,
                                                w: 'r'
                                            }, function(data) {
                                                var responseJSON = JSON.parse(data);
                                                if (responseJSON.result == "S") {
                                                    //$(".pick[it_id='" + it_id + "']").removeClass("on");

                                                    $pick = $(".pick[it_id='" + it_id + "']");
                                                    $pick.removeClass('on');

                                                    if (responseJSON.count) {
                                                        $pick.text('');
                                                        $pick.append('<span class="blind">찜</span>' + responseJSON.count);
                                                    }
                                                } else {
                                                    alert(responseJSON.alert);
                                                    return false;
                                                }
                                            });
                                        }
                                    }
                                </script>
                            </div>

                        <?
                            }
                            break;
                        case 6: {
                        ?>
                            <div class="column_three">
                                <h3 class="blind">리케리페 서비스</h3>
                                <ul>
                                    <? for ($i = 0; $i < $row['main_type2']; $i++) {
                                        $img_data = $main_view_data['imgFile'][$i];
                                        $link_url = $img_data['linkURL'];
                                        $img_url = G5_DATA_URL . '/design/' . $main_id . '/' . $img_data['imgFile'];
                                    ?>
                                        <li><a href="<?= $link_url ?>"><img src="<?= $img_url ?>" alt="" /></a></li>
                                    <? } ?>
                                </ul>
                                <!-- <a href="#" class="btn_more"><span class="blind">더보기</span></a> -->
                            </div>
                        </div>
        <?
                            }
                            break;
                    }
                }
        ?>

        <?
        $sql = " select * $sql_common $sql_where and main_id > 6 ";
        if ($preview_main_id) {
            $sql .= " and main_id = '" . $preview_main_id . "'";
        }
        $sql .= " order by main_order ";

        $result = sql_query($sql);
        $i = 0;

        while ($row = sql_fetch_array($result)) {
            $main_id = $row['main_id'];
            if ($previewonofflist[$main_id] != '' && $previewonofflist[$main_id] == "N") continue;
            if ($row['main_onoff'] == "N") continue;

            $main_view_data = json_decode(str_replace('\\', '', $row['main_view_data']), true);

            switch ($row['main_type1']) {
                case "rolling": {
        ?>
                        <!-- 브랜드 -->
                        <div class="section_content brand">
                            <h3 class="main_title"><?= $main_view_data['title_name'] ?></h3>
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    <? for ($i = 0; $i < $row['main_type2']; $i++) {
                                        $img_data = $main_view_data['imgFile'][$i];
                                        $link_url = $img_data['linkURL'];
                                        $img_file = G5_DATA_PATH . '/design/' . $main_id . '/' . $img_data['imgFile'];
                                        if ($img_data['imgFile'] && file_exists($img_file)) {
                                            $img_url = G5_DATA_URL . '/design/' . $main_id . '/' . $img_data['imgFile'];
                                    ?>
                                            <div class="swiper-slide"><a href="<?= $link_url ?>">
                                                    <div class="photo"><img src="<?= $img_url ?>" alt="" /></div>
                                                </a></div>
                                        <? } else { ?>
                                            <div class="swiper-slide"><a href="<?= $link_url ?>">
                                                    <div class="photo"><img src="<?= G5_MOBILE_URL; ?>/img/theme_img.jpg" alt="" /></div>
                                                </a></div>
                                    <? }
                                    } ?>

                                </div>

                                <div class="swiper-pagination"></div>

                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
                            <script>
                                var swiper = new Swiper('.section_content.brand .swiper-container', {
                                    slidesPerView: 2,
                                    loop: true,
                                    centeredSlides: true,
                                    spaceBetween: 30,
                                    navigation: {
                                        nextEl: '.section_content.brand .swiper-button-next',
                                        prevEl: '.section_content.brand .swiper-button-prev',
                                        clickable: true,
                                    },
                                    breakpoints: {
                                        1024: {
                                            slidesPerView: 1,
                                            spaceBetween: 0,
                                        },
                                    },
                                });
                            </script>
                        </div>

                    <?
                    }
                    break;
                case "image": {
                    ?>
                        <div class="section_content event">
                            <div class="fix_wrap">
                                <h3 class="main_title"><?= $main_view_data['title_name'] ?></h3>
                                <ul class="thumb_list col<?= $row['main_type2'] ?>">
                                    <? for ($i = 0; $i < $row['main_type2']; $i++) {
                                        $img_data = $main_view_data['imgFile'][$i];
                                        $link_url = $img_data['linkURL'];
                                        $img_file = G5_DATA_PATH . '/design/' . $main_id . '/' . $img_data['imgFile'];
                                        if ($img_data['imgFile'] && file_exists($img_file)) {
                                            $img_url = G5_DATA_URL . '/design/' . $main_id . '/' . $img_data['imgFile'];
                                    ?>
                                            <li>
                                                <a href="<?= $link_url ?>">
                                                    <div class="photo"><img src="<?= $img_url ?>" alt="" /></div>
                                                </a>

                                                <? if ($img_data['mainText'] != null && $img_data['mainText'] != "") { ?>
                                                    <div class="cont">
                                                        <span class="title ellipsis"><?= $img_data['mainText'] ?></span>
                                                        <span class="txt"><?= $img_data['subText'] ?></span>
                                                    </div>
                                                <? } ?>
                                            </li>
                                        <? } else { ?>
                                            <li><a href="<?= $link_url ?>">
                                                    <div class="photo"><img src="<?= G5_MOBILE_URL; ?>/img/theme_img.jpg" alt="" /></div>
                                                </a></li>
                                    <? }
                                    } ?>
                                </ul>
                            </div>
                        </div>
                    <?
                    }
                    break;
                case "imagetext": {
                    ?>
                        <div class="section_content magazine">
                            <div class="inner">
                                <h3 class="main_title"><?= $main_view_data['title_name'] ?></h3>
                                <div class="swiper-container">
                                    <ul class="swiper-wrapper">
                                        <? for ($i = 0; $i < $row['main_type2']; $i++) {
                                            $img_data = $main_view_data['imgFile'][$i];
                                            $link_url = $img_data['linkURL'];
                                            $img_file = G5_DATA_PATH . '/design/' . $main_id . '/' . $img_data['imgFile'];
                                            $img_url = G5_DATA_URL . '/design/' . $main_id . '/' . $img_data['imgFile'];
                                        ?>
                                            <li class="swiper-slide">
                                                <div class="photo"><img src="<?= $img_url ?>" alt="" /></div>
                                                <div class="cont">
                                                    <span class="title"><?= $img_data['mainText'] ?></span>
                                                    <span class="txt"><?= $img_data['subText'] ?></span>
                                                    <a href="<?= $link_url ?>" class="btn_seeMore">See More</a>
                                                </div>
                                            </li>
                                        <? } ?>
                                    </ul>
                                </div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
                            <script>
                                var swiper = new Swiper('.section_content.magazine .swiper-container', {
                                    slidesPerView: 2,
                                    spaceBetween: 15,
                                    loop: true,
                                    navigation: {
                                        nextEl: '.section_content.magazine .swiper-button-next',
                                        prevEl: '.section_content.magazine .swiper-button-prev',
                                    },
                                });
                            </script>
                        </div>
                    <?
                    }
                    break;
                case "banner": {
                    ?>
                        <div class="banner_bar">
                            <div class="rolling_wrap swiper-container">
                                <div class="swiper-wrapper">
                                    <? for ($i = 0; $i < $row['main_type2']; $i++) {
                                        $img_data = $main_view_data['imgFile'][$i];
                                        $link_url = $img_data['linkURL'];
                                        $img_file = G5_DATA_PATH . '/design/' . $main_id . '/' . $img_data['imgFile'];
                                        if ($img_data['imgFile'] && file_exists($img_file)) {
                                            $img_url = G5_DATA_URL . '/design/' . $main_id . '/' . $img_data['imgFile'];
                                    ?>
                                            <div class="swiper-slide"><a href="<?= $link_url ?>"><img src="<?= $img_url ?>" alt="" /></a></div>
                                        <? } else { ?>
                                            <div class="swiper-slide"><a href="<?= $link_url ?>"><img src="<?= G5_MOBILE_URL; ?>/img/theme_img.jpg" alt="" /></a></div>
                                    <? }
                                    } ?>
                                </div>
                            </div>
                            <script>
                                var swiper = new Swiper('.banner_bar .swiper-container', {
                                    autoplay: {
                                        delay: 4000,
                                    },
                                    loop: true,
                                });
                            </script>
                        </div>
                    <?
                    }
                    break;
                case "motion": {
                    ?>
                        <div class="product_all">
                            <div class="banner fix_wrap">
                                <h3 class="blind"><?= $main_view_data['title_name'] ?></h3>

                                <div class="banner_bar_swiper">
                                    <div class="swiper-wrapper">
                                        <? for ($i = 0; $i < $row['main_type2']; $i++) {
                                            $img_data = $main_view_data['imgFile'][$i];
                                            $link_url = $img_data['linkURL'];
                                            $img_file = G5_DATA_PATH . '/design/' . $main_id . '/' . $img_data['imgFile'];
                                            if ($img_data['imgFile'] && file_exists($img_file)) {
                                                $img_url = G5_DATA_URL . '/design/' . $main_id . '/' . $img_data['imgFile'];
                                        ?>
                                                <div class="swiper-slide"><a href="<?= $link_url ?>"><img src="<?= $img_url ?>" alt="" /></a></div>
                                                } else { ?>
                                                <div class="swiper-slide"><a href="<?= $link_url ?>"><img src="<?= G5_MOBILE_URL; ?>/img/theme_img.jpg" alt="" /></a></div>
                                        <? }
                                        } ?>
                                    </div>
                                    <div class="swiper-pagination"></div>
                                </div>

                                <script>
                                    var swiperMain_visual = new Swiper('.banner_bar_swiper', {
                                        slidesPerView: 'auto',
                                        spaceBetween: 0,
                                        loop: true,
                                        autoplay: {
                                            delay: 4000,
                                            disableOnInteraction: false,
                                        },
                                        pagination: {
                                            el: '.swiper-pagination',
                                            clickable: true,
                                        },
                                    });
                                </script>
                            </div>
                        </div>

                    <?
                    }
                    break;
                case "subproduct": {
                    ?>
                        <!-- 상품 -->
                        <div class="product_all">
                            <!-- 신상품 -->
                            <div class="section_content">
                                <div class="fix_wrap">
                                    <h3 class="main_title"><?= $main_view_data['title_name'] ?></h3>
                                    <ul class="thumb_list col<?= ($row['main_type2'] == "4") ? $row['main_type2'] : $row['main_type2'] . "add" ?>">
                                        <?
                                        for ($i = 0; $i < count($main_view_data['it_id']); $i++) {
                                            $sql2 = "select it_name, it_price, it_img1, it_id, it_basic, it_rental_price, it_item_type, it_view_list_items, it_time, ca_id from lt_shop_item where it_id = '{$main_view_data['it_id'][$i]}' and it_use = 1";
                                            $row2 = sql_fetch($sql2);

                                            if ($row2) {
                                                $link_url = G5_URL . '/shop/item.php?it_id=' . $row2['it_id'];
                                                $img_data = $row2['it_img1'];
                                                $img_url = G5_DATA_URL . '/item/' . $img_data;

                                                $badgeObj->item = $row2;
                                                $badgeObj->innerHtml = "<img src='{$img_url}'/>";
                                                $badgeObj->makeHtml();
                                        ?>
                                                <li>
                                                    <a href="<?= $link_url ?>">
                                                        <?= $badgeObj->photoHtml ?>
                                                        <div class="cont">
                                                            <strong class="title bold ellipsis"><?= $row2['it_name'] ?></strong>
                                                            <span class="text ellipsis"><?= $row2['it_basic'] ?></span>
                                                            <span class="price"><?= ($row2['it_item_type']) ? number_format($row2['it_rental_price']) : number_format($row2['it_price']) ?> 원</span>
                                                        </div>
                                                    </a>
                                                </li>
                                        <? }
                                        } ?>
                                    </ul>
                                    <!-- a href="#" class="btn_more"><span class="blind">더보기</span></a -->
                                </div>
                            </div>
                        </div>
                    <?
                    }
                    break;
                case "movie": {

                        $movieimg_url = G5_DATA_URL . '/design/' . $main_id . '/' . $main_view_data['movieimg'];
                        $moviefile_url = G5_DATA_URL . '/design/' . $main_id . '/' . $main_view_data['moviefile'];

                        $link_url = $main_view_data['linkURL'];

                    ?>
                        <div class="section_content make">
                            <div class="fix_wrap">
                                <h3 class="main_title"><?= $main_view_data['title_name'] ?></h3>
                                <div class="video_container">

                                    <?php if ($main_view_data['moviefile'] != "") { ?>
                                        <video controls poster="<?= $movieimg_url ?>" width="1000">
                                            <source src="<?= $moviefile_url ?>" type="video/mp4" width="1000">
                                            Your browser does not support the video tag.
                                        </video>
                                    <?php } else { ?>
                                        <a href="<?= $link_url ?>">
                                            <div class="photo"><img src="<?= $movieimg_url ?>" alt="" /></div>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?
                    }
                    break;
                case "sns": {
                    ?>
                        <div class="section_content instagram">
                            <div class="fix_wrap">
                                <h3 class="main_title"><?= $main_view_data['title_name'] ?></h3>
                                <ul class="thumb_list">
                                    <li><a href="#"><img src="img/pc/main/sample_instagram_1.jpg" alt="instagram photo" /></a>
                                    </li>
                                    <li><a href="#"><img src="img/pc/main/sample_instagram_2.jpg" alt="instagram photo" /></a>
                                    </li>
                                    <li><a href="#"><img src="img/pc/main/sample_instagram_3.jpg" alt="instagram photo" /></a>
                                    </li>
                                    <li><a href="#"><img src="img/pc/main/sample_instagram_4.jpg" alt="instagram photo" /></a>
                                    </li>
                                    <li><a href="#"><img src="img/pc/main/sample_instagram_5.jpg" alt="instagram photo" /></a>
                                    </li>
                                    <li><a href="#"><img src="img/pc/main/sample_instagram_6.jpg" alt="instagram photo" /></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?
                    }
                    break;
                default: {
                    ?>



        <?
                    }
                    break;
            }
        }

        ?>

                    </div>
</div>
<!-- //container -->

<?
include_once(G5_PATH . '/tail.php');

if ($preview_main_id) {
    echo "<script>$('#header').html('');$('#footer').html('');</script>";
}
?>