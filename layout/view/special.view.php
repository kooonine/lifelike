<?php
ob_start();
$g5_title = "특가";
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

<div class="special_off_area on-big"></div>

<input type="hidden" name ="cp_end_date" id = "special_end" value = "<?=$db_special['cp_end_date']?>" >

<div id="event-wrapper" style="text-align: center;">
    <div id="event-content-wrapper" class="on-big">
        <?= $special->special_print_content() ?>
    </div>
    <div id="event-content-wrapper" class="on-small">
        <?= $special->special_print_content(true) ?>
    </div>
</div>


<script>
    $('.event_detail_title .share').on('click', function(){
        
        if ($(".sct_sns").hasClass("active") == true) {
            $(".sct_sns").removeClass("active");
        } else {
            $(".sct_sns").addClass("active");
        }
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
        if (distance && distance != NaN) {
            var string = "D-" + days +"    " + '<span class="count_down_img"></span>  ' + pad(hours,2) + ":" + pad(minutes,2) + ":" + pad(seconds,2) + " ";
        }
        // var string = "D-" + days +"    " + '<span class="count_down_img"></span>  ' + pad(hours,2) + ":" + pad(minutes,2) + ":" + pad(seconds,2) + " ";
        //var string = String(aaa);

        // Display the result in the element with id="demo"
        // document.getElementById("count_down_area").innerHTML = "D-" + days +"    " + '<span class="count_down_img"></span>  ' + pad(hours,2) + ":"
        // + pad(minutes,2) + ":" + pad(seconds,2) + "";

            $('.count_down_area').empty().html(string);
        // document.getElementById("count_down_area_mo").innerHTML = "D-" + days +"    " + '<span class="count_down_img"></span>  ' + pad(hours,2) + ":"
        // + pad(minutes,2) + ":" + pad(seconds,2) + "";

        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            // document.getElementById("count_down_area").innerHTML = "THE END";
            // document.getElementById("count_down_area_mo").innerHTML = "THE END";
            $('.count_down_area').empty().html('THE END');
        }
        }, 1000);
    }

    function pad(n, width) {
        n = n + '';
        return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n;
    }

    $(document).ready(function() {
        count_down_start();
        

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
</script>
<?php
$contents = ob_get_contents();
ob_end_clean();

return $contents;
?>