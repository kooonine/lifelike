<?php
ob_start();
$g5_title = "기획전";
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
<?php
// function MobileCheck() {
//     global $HTTP_USER_AGENT;
//     $MobileArray  = array("iphone","lgtelecom","skt","mobile","samsung","nokia","blackberry","android","android","sony","phone");

//     $checkCount = 0;
//     for($i=0; $i<sizeof($MobileArray); $i++){
//         if(preg_match("/$MobileArray[$i]/", strtolower($HTTP_USER_AGENT))){ $checkCount++; break; }
//     }
//     return ($checkCount >= 1) ? "Mobile" : "Computer";
// }
?>
<style>
    /* <? if ($db_event['cp_subject']!='지구를 생각하는 착한 침구를 골라보세요' && $db_event['cp_subject']!='여름밤을 시원하게 바꿔줄 침구를 소개해요') : ?> */
    /* #event-wrapper #event-content-wrapper-big p > img:first-child { */
    /* width:1024px !important; */
    /* } */
    /* <? endif ?> */
    
    .campaignMobile iframe {
        width :100% !important;
        height: 242px !important;
    }
</style>

<div class="event_off_area on-big"></div>
<div class="event_detail_title">
    <div class="on-small"><?= $db_event['cp_subject']?></div>
    <div class="date on-small"><?= substr($db_event['cp_start_date'] , 0,11)   ?> ~ <?= substr($db_event['cp_end_date'] ,0,11) ?></div>
    
    <!-- 하드코딩 -->
    <!-- <? if ($db_event['cp_subject']=='지구를 생각하는 착한 침구를 골라보세요' || $db_event['cp_subject']=='여름밤을 시원하게 바꿔줄 침구를 소개해요') : ?>
        <span class="on-big"><?= $db_event['cp_subject']?></span>
    <? else : ?>
        <span class="on-big" style="margin-left: 197px;"><?= $db_event['cp_subject']?></span>
    <? endif ?> -->
    <!-- end -->

    <span class="on-big"><?= $db_event['cp_subject']?></span>


    <span class="date on-big"><?= substr($db_event['cp_start_date'] , 0,11)   ?> ~ <?= substr($db_event['cp_end_date'] ,0,11) ?></span>

    <!-- 하드코딩 -->
    <!-- <? if ($db_event['cp_subject']=='지구를 생각하는 착한 침구를 골라보세요' || $db_event['cp_subject']=='여름밤을 시원하게 바꿔줄 침구를 소개해요') : ?>
        <span class="gotolist btns on-big"><a href="/campaign/list.php"> <img src="/img/re/nav/menu.png" srcset="/img/re/nav/menu@2x.png 2x,/img/re/nav/menu@3x.png 3x"> 목록보기</a></span>
        <span onclick="openSnsPopup('item')" class="share btns on-big"><img src="/img/re/share.png" srcset="/img/re/share@2x.png 2x,/img/re/share@3x.png 3x"> 공유하기</span>
    <? else : ?>
        <span class="gotolist btns on-big" style="margin-right: 197px;"><a href="/campaign/list.php"> <img src="/img/re/nav/menu.png" srcset="/img/re/nav/menu@2x.png 2x,/img/re/nav/menu@3x.png 3x"> 목록보기</a></span>
        <span onclick="openSnsPopup('item')" class="share btns on-big" style="margin-right: 6px;"><img src="/img/re/share.png" srcset="/img/re/share@2x.png 2x,/img/re/share@3x.png 3x"> 공유하기</span>
    <? endif ?> -->

    <span class="gotolist btns on-big"><a href="/campaign/list.php"> <img src="/img/re/nav/menu.png" srcset="/img/re/nav/menu@2x.png 2x,/img/re/nav/menu@3x.png 3x"> 목록보기</a></span>
    <span onclick="openSnsPopup('item')" class="share btns on-big" style="margin-right: 6px;"><img src="/img/re/share.png" srcset="/img/re/share@2x.png 2x,/img/re/share@3x.png 3x"> 공유하기</span>

    <!-- <span class="gotolist btns on-big"><a href="/campaign/list.php"> <img src="/img/re/nav/menu.png" srcset="/img/re/nav/menu@2x.png 2x,/img/re/nav/menu@3x.png 3x"> 목록보기</a></span> -->
    
    <!-- <span onclick="openSnsPopup('item')" class="share btns on-big"><img src="/img/re/share.png" srcset="/img/re/share@2x.png 2x,/img/re/share@3x.png 3x"> 공유하기</span> -->
    <!-- end -->
    <!-- <span class="gotolist btns on-big" style="margin-right: 197px;"><a href="/campaign/list.php"> <img src="/img/re/nav/menu.png" srcset="/img/re/nav/menu@2x.png 2x,/img/re/nav/menu@3x.png 3x"> 목록보기</a></span> -->
    
    <!-- <span onclick="openSnsPopup('item')" class="share btns on-big" style="margin-right: 6px;"><img src="/img/re/share.png" srcset="/img/re/share@2x.png 2x,/img/re/share@3x.png 3x"> 공유하기</span> -->
</div>
<?php 
    function MobileCheckCampaignView() {
        global $HTTP_USER_AGENT;
        $MobileArray  = array("iphone","lgtelecom","skt","mobile","samsung","nokia","blackberry","android","android","sony","phone");

        $checkCount = 0;
        for($i=0; $i<sizeof($MobileArray); $i++){
            if(preg_match("/$MobileArray[$i]/", strtolower($HTTP_USER_AGENT))){ $checkCount++; break; }
        }
        return ($checkCount >= 1) ? "Mobile" : "Computer";
    }
?>

<div id="event-wrapper" style="text-align: center;">
    <div id="event-content-wrapper-big" class="on-big" style="margin-top: 10px;">
     <? if(MobileCheckCampaignView() == "Computer") { ?>
         <?= $event->print_content(false) ?>
     <? } ?>
    </div>
    <div id="event-content-wrapper" class="campaignMobile on-small">
    <? if(MobileCheckCampaignView() == "Mobile") { ?>
        <?= $event->print_content(true) ?>   
    <? } ?>
    </div>
    <div class="on-small footer_btn_group">
        <div class="btns" onclick="openSnsPopup('item')"><img src="/img/re/share.png" srcset="/img/re/share@2x.png 2x,/img/re/share@3x.png 3x"> 공유하기</div>
        <div class="btns"><a href="/campaign/list.php"><img src="/img/re/nav/menu.png" srcset="/img/re/nav/menu@2x.png 2x,/img/re/nav/menu@3x.png 3x">목록보기</a></div>
    </div>
</div>


<script>

    // $('.event_detail_title .share').on('click', function(){
        
    //     if ($(".sct_sns").hasClass("active") == true) {
    //         $(".sct_sns").removeClass("active");
    //     } else {
    //         $(".sct_sns").addClass("active");
    //     }
    // });

    $(document).ready(function() {

        var imgSize = document.querySelector("#event-wrapper #event-content-wrapper-big p > img:first-child");
        var imgWidth = imgSize.clientWidth;    
        $('.event_detail_title').css('width',imgWidth);

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