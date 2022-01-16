<?php
ob_start();
$g5_title = "이벤트";
?>
<link rel="stylesheet" href="/re/css/event.css">
<link rel="stylesheet" href="/re/css/coupon.css?ver=20051901">
<?php if($config['cf_kakao_js_apikey']) { ?>
<script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
<script src="<?php echo G5_JS_URL; ?>/kakaolink.js"></script>
<script>
    // 사용할 앱의 Javascript 키를 설정해 주세요.
    Kakao.init("<?php echo $config['cf_kakao_js_apikey']; ?>");
</script>
<?php } ?>

<?php if (!empty($g5_banner['MAIN'])) : ?>
    <!--
    <div id="main-banner-wrapper">
        <? foreach ($g5_banner['MAIN'] as $bmain) : ?>
            <a href="<?= $bmain['ba_link'] ?>">
                <div class="main-banner" style="background-image: url(/data/banner/<?= $bmain['ba_image'] ?>);">
                    <div class="main-banner-subject" style="display: none;"><?= $bmain['ba_subject'] ?></div>
                    <div class="main-banner-content" style="display: none;"><?= $bmain['ba_content'] ?></div>
                </div>
            </a>
        <? endforeach ?>
    </div>
        -->
<?php endif ?>


<?php 
    $now_host = $_SERVER['HTTP_HOST'] ; 
    $now_uri = $_SERVER['REQUEST_URI'];

    $sns_url  = G5_URL . $now_uri;
    $sns_title = $db_event['cp_subject'];
    $sns_image = G5_URL. '/data/banner/' .$db_event['cp_image_1'];
?>

<div class="event_off_area on-big"></div>
<div class="event_detail_title">
    <div class="on-small"><?= $db_event['cp_subject']?></div>
    <div class="date on-small"><?= substr($db_event['cp_start_date'] , 0,11)   ?> ~ <?= substr($db_event['cp_end_date'] ,0,11) ?></div>
    <span class="on-big"><?= $db_event['cp_subject']?></span>
    <span class="date on-big"><?= substr($db_event['cp_start_date'] , 0,11)   ?> ~ <?= substr($db_event['cp_end_date'] ,0,11) ?></span>
    <span class="gotolist btns on-big"><a href="/event/list.php"> <img src="/img/re/nav/menu.png" srcset="/img/re/nav/menu@2x.png 2x,/img/re/nav/menu@3x.png 3x"> 목록보기</a></span>
    <span class="share btns on-big" onclick="openSnsPopup('item')"><img src="/img/re/share.png" srcset="/img/re/share@2x.png 2x,/img/re/share@3x.png 3x"> 공유하기</span>
</div>

<div id="event-wrapper" style="text-align: center;">
    <div id="event-content-wrapper" class="on-big">
        <?= $event->print_content(false,$db_event['cp_review']) ?>
    </div>
    <div id="event-content-wrapper" class="mobileApp on-small">
    <input type="hidden" id="cpId" name="cpId" value="<?php echo $db_event['cp_id']; ?>">
        <?= $event->print_content(true,$db_event['cp_review']) ?>
    </div>

    <div class="on-small footer_btn_group" style="margin-top:28px;">        
        <div class="btns" onclick="openSnsPopup('item')"><img src="/img/re/share.png" srcset="/img/re/share@2x.png 2x,/img/re/share@3x.png 3x"> 공유하기</div>
        <div class="btns"><a href="/event/list.php"><img src="/img/re/nav/menu.png" srcset="/img/re/nav/menu@2x.png 2x,/img/re/nav/menu@3x.png 3x">목록보기</a></div>
    </div>
</div>


<script>

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
        });
        let evId = $("#cpId").val();
        if (evId ==5) {
            let broswerInfo = navigator.userAgent;
            if (broswerInfo.indexOf("Mobile")>-1) {
                if (broswerInfo.indexOf("Android")>-1 && broswerInfo.indexOf("APP_ANDROID")<0) { 
                    document.getElementById("iosIns").onclick = function() {
                        location.href = "intent://app/#Intent;package=com.litandard.lifelike;scheme=lifelikeand;end";
                        };
                } else if (broswerInfo.indexOf("Mac")>-1 && broswerInfo.indexOf("APP_IOS")<0) { 
                    document.getElementById("iosIns").onclick = function() { 
                        var visitedAt = (new Date()).getTime();
                        setTimeout(function() {
                            if ((new Date()).getTime() - visitedAt < 2000) {
                                location.href = "http://apps.apple.com/kr/app/LIFELIKE/id1473080254";
                            }
                        }, 1000);
                        location.href = "lifelike://";
                        return;
                    };
                }
            }
        }
    });

    $('button.btn-download-coupon').on('click', function() {
        if (g5_is_member != '1') {
            alert('로그인 후 이용해 주십시오.');
            return openLogin();
        }

        const $this = $(this);
        const coupons = $this.data('coupon');

        if ($this.hasClass('disabled')) {
            alert('이미 다운로드하신 쿠폰입니다.');
            return false;
        }

        $this.addClass('disabled').attr('disabled', true);

        $.ajax({
            type: 'GET',
            data: {
                "coupons": coupons
            },
            url: '/shop/ajax.coupondownload.php',
            cache: false,
            async: true,
            dataType: 'json',
            success: function(data) {
                if (data.error != '') {
                    // $this.removeClass('disabled').attr('disabled', false);
                    alert(data.error);
                    return false;
                }

                // $this.attr('disabled', false);
                alert('쿠폰이 발급됐습니다.');
            }
        });
    });
    function eventCoupon() {
        $.ajax({
            type: 'GET',
            data: {
                "cp_id": true
            },
            url: '/shop/ajax.couponEvent.php',
            cache: false,
            async: true,
            dataType: 'json',
            success: function(data) {
                if (data.error != '' && data.error != null) {
                    alert(data.error);
                    return false;
                }
             
                alert('쿠폰이 발급됐습니다.');
                location.reload();
            },
        });
    }
</script>
<?php
$contents = ob_get_contents();
ob_end_clean();

return $contents;
?>