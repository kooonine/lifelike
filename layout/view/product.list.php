<?php
ob_start();
$tmp_menu = explode(',', $m_path);
$current_menu = ($g5_menu[$tmp_menu[0]]);
?>
<style>
    @media (max-width: 1366px) {
        #offset-nav-top {
            height: 128px;
            margin-bottom: 0;
        }
    }
</style>

<div id="list-wrapper" class="">
    <? foreach ($g5_banner_new['ETC'] as $listba) : ?>
        <? if ($listba['ca_id'] == $ca_id && $listba['ba_position'] == 'LIST_TOP') : ?>
            <div onclick="location.href='<?= $listba['cp_link'] ?>'" class="product-list-banner-main on-big" style="background-image: url(/data/banner/<?= $listba['cp_image_1'] ?>);"></div>
            <div onclick="location.href='<?= $listba['cp_link'] ?>'" class="product-list-banner-main on-small" style="background-image: url(/data/banner/<?= $listba['cp_image_2'] ?>);"></div>
        <? endif ?>
    <? endforeach ?>

    <?php $select_ca_id = $_GET["ca_id"]; ?>
    <?php if ($select_ca_id != '1091' && $select_ca_id != '1092' && $select_ca_id != '1093' && $select_ca_id != '1094') { ?>

    <div class="product-list-menu-title on-big"><?= $current_menu['me_name'] ?></div>
    <div class="product-list-menu-item on-big">
        <?php $all = (string) $ca_id;
        if (strlen($select_ca_id) < 5) {
            echo  '<div><a class="active" href="/shop/list.php?ca_id=' .  substr($all, 0, 4) . '">전체</a></div> ';
        } else {
            echo  '<div><a href="/shop/list.php?ca_id=' .  substr($all, 0, 4) . '">전체</a></div> ';
        }
        ?>
        <?php
        foreach ($current_menu['SUB'] as $sm) {
            if ($sm['me_name'] == "PRODUCT" || $sm['me_name'] == "WE PICK") $sm['me_name'] = "전체보기";
            if (strlen($select_ca_id) > 6) {
                $select_ca_id = substr($select_ca_id,0,6);
            }
            if (strpos($sm['me_link'], $select_ca_id) && strlen($select_ca_id) > 4) {
                echo '<div><a class="active" href="' . $sm['me_link'] . '">' . $sm['me_name'] . '</a></div>';
            } else {
                echo '<div><a href="' . $sm['me_link'] . '">' . $sm['me_name'] . '</a></div>';
            }
        }
        ?>
    </div>
    <?php
        } 
    ?>
    <?php echo $filter_view; ?>

    <?php echo $list_view; ?>
    <div id="add_items" class="on-small"></div>
    <div class="on-small add_item_btn" style=" clear: both; <?if( $total_page < 2) : ?> display: none ; <? endif ?>" ><a onclick="addItem(<?= $ca_id ?> , <?= $total_page ?>)">더보기</a></div>

    <div class="on-big">
        <? if (!empty($paging)) echo $paging ?>
    </div>

</div>



<script>
    var itemlist_ca_id = "<? echo $ca_id; ?>";
</script>

<script>
    let add_page = 2;

    function addItem(id, total_page) {
        if (total_page < add_page) {
            alert("등록된 상품이 없습니다.")
        } else {
            $.ajax({
                url: '/shop/ajax.list.php',
                type: 'post',
                data: {
                    ca_id: id,
                    add_page: add_page
                },

                success: function(response) {
                    $('#add_items').append(response);
                    add_page++;
                }
            });
            if (add_page >= total_page) {
                $('.add_item_btn').css('display', 'none');
            }
        }
    }


    $(document).ready(function() {
        let thumbLazy = $("div.thumb-lazy");
        let active = false;


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