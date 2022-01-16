<footer id="footer-big" class="on-big">
    <div class="footer_line menu">
        <span><a href="https://litandard.com" target="_blank">회사소개</a></span>
        <span style="cursor: pointer;"><a onclick="modal_privacy('modal_stipulation_on-big')">이용약관</a></span>
        <span style="cursor: pointer;"><strong><a onclick="modal_privacy('modal_privacy_on-big')">개인정보처리방침</a></strong></span>
        <span><a href="/member/member.center.php">고객센터</a></span>
    </div>

    <div class="footer_line info">
        <div class="info_line1">
            <span>리탠다드(주)</span>
            <span>서울 구로구 디지털로32길 86 리탠다드</span>
            <span>대표자 : 임석원</span>
            <span>사업자등록번호 : 207-81-28350</span>
            <span>통신판매업신고 : 2018-서울구로-1031</span>
        </div>
        <div class="info_line2">
            <span>개인정보보호책임자 : 김성훈</span>
            <span>고객센터 : 02-3494-7602(10:00~17:00, 11:30 ~ 13:00 점심시간)</span>
            <span><a href="https://www.ftc.go.kr/bizCommPop.do?wrkr_no=2078128350" target="_blank"><span class="btn" style="border-radius: 15px; width: 100px;height: 16px; font-size: 12px;padding:0px; font-weight: normal;line-height: 16px; ">사업자정보확인</span></a></span>
        </div>
        <div class="footer-copyright">
            COPYRIGHT (C) <?= date("Y") ?> 리탠다드(주) ALL RIGHT RESERVED
        </div>
    </div>
    <div id="console-display" style="display: none; font-size: 14px; color: #000000; position:fixed; top: 200px; left: 20px; z-index: 2000;"></div>
</footer>



<footer id="footer-small" class="on-small">
    <div id="btn-toggle-footer-info-all" class="footer-small-div C1KOBLL">라이프라이크 사업자 정보 <span id="btn-toggle-footer-info"></span></div>
    <div id="footer-small-info" class="footer-small-div">
        <span class="C2KOBLL">상호명 : 리탠다드(주)
            </br>주소 : 서울 구로구 디지털로32길 86 리탠다드
            </br>대표이사 : 임석원
            </br>사업자등록번호 : 207-81-28350
            </br>통신판매업신고 : 2018-서울구로-1031 <button style="height : 18px; line-height : 15px"><a href="http://www.ftc.go.kr/bizCommPop.do?wrkr_no=2078128350" target="_blank">사업자정보확인</a></button>
            </br>개인정보보호책임자 : 김성훈
            </br>고객센터 : LIFELIKE@LIFELIKE.CO.KR
            </br>02-3494-7602 | 10:00 - 17:00(토/일 /공휴일 휴무)
            </br>11:30 - 13:00(점심시간)
        </span>
    </div>
    <div id="footer-small-footer" class="footer-small-div">
        <!-- <div class="C2KOBLL"><a href="https://litandard.com" target="_blank">회사소개</a></div> -->
        <div class="C2KOBLL"><a id='companyInfo' name = 'companyInfo' href="https://litandard.com">회사소개</a></div>
        <div class="C2KOBLL" onclick="modal_privacy('modal_stipulation_on-small')">이용약관</div>
        <div class="C2KOBLL" onclick="modal_privacy('modal_privacy_on-small')">개인정보처리방침</div>
        <div class="C2KOBLL" onclick="location.href='/member/member.center.php'">고객센터</div>
    </div>

    <div class="footer-copyright LAKOBLL">
        COPYRIGHT (C) <?= date("Y") ?> 리탠다드(주) ALL RIGHT RESERVED
    </div>
</footer>





<script type="text/javascript">
    $(function(){ 
        let broswerInfo = navigator.userAgent;
        if (broswerInfo.indexOf("APP_ANDROID")>-1) { 
        } else {
            $('#companyInfo').attr('target','_blank');
        }
    })
    function modal_privacy(modal) {
        $("#" + modal).modal("show");
    }
    let scrollTop = 0;
    let scrollBottom = document.body.offsetHeight - window.innerHeight - 100;

    if (scrollBottom < 0) scrollBottom = 0;

    function hasScrollBar() {
        const hasScrollBar = window.innerHeight < $(document).height();
        if (hasScrollBar) {
            $("html").removeClass("no-scrollbar");
        } else {
            $("html").addClass("no-scrollbar");
        }

        return hasScrollBar;
    };

    function controllCheckboxGroup(checkElem, type) {
        if (!type) type = "check";
        if ($(checkElem).data("checkgroup")) {
            let checkGroup = $(checkElem).data("checkgroup").split(" ");
            if ($(checkElem).data("checkall") && type == "check") { // 하위 레벨 전체선택
                $("input[type=checkbox]." + $(checkElem).data("checkall")).prop("checked", $(checkElem).prop("checked"));
                for (gi in checkGroup) {
                    if (checkGroup[gi] != $(checkElem).data("checkall")) {
                        $("input[type=checkbox]." + checkGroup[gi]).each(function(ngi, ng) {
                            if ($(ng).data("checkall") == checkGroup[gi]) controllCheckboxGroup(ng, "test");
                        });
                    }
                }
            } else {
                for (gi in checkGroup) {
                    const $groups = $("input[type=checkbox]." + checkGroup[gi]);
                    let gChecked = true;
                    let checkAllElem;

                    $groups.each(function(cgi, cElem) {
                        if ($(cElem).data("checkall") && $(cElem).data("checkall") == checkGroup[gi]) {
                            checkAllElem = cElem;
                        } else {
                            if (!cElem.checked) gChecked = cElem.checked;
                        }
                    });
                    checkAllElem.checked = gChecked;
                }
            }
        }
    }

    function showFormValidationWarn(targetElem) {
        const vTargetElem = $(targetElem);
        const vElem = vTargetElem.parent().find(".form-validation-warn");

        $(vElem).addClass("active-inline").find("span").text(vTargetElem.data("validation"));
    }

    function closeFormValidationWarn() {
        $(".form-validation-warn").removeClass("active-inline");
    }

    var today = new Date();

    window.onbeforeunload = function(event) {
        var edate = new Date();
        var stayTime = edate.getTime() - today.getTime();
        $.post("<?php echo G5_BBS_URL ?>/visit_insert.page.inc.php", {
            vi_stay: stayTime,
            vi_page: "<?php echo $_SERVER['PHP_SELF'] ?>",
            full_page: "<?php echo $_SERVER['PHP_SELF'] . (($_SERVER['QUERY_STRING']) ? "?" . $_SERVER['QUERY_STRING'] : "") ?>"
        }, function(data) {
            //console.log( data );
        });

        window.onbeforeunload = null;
        try {
            (e || window.event).returnValue = null;
        } catch (e) {};

        return;
    }
    $(function() {
        $(".btn").click(function() {
            window.onbeforeunload = null;
        });
    });

    $("#btn-toggle-footer-info-all").on("click", function() {
        $("#btn-toggle-footer-info, #footer-small-info").toggleClass("active");
    });


    document.addEventListener("DOMContentLoaded", function() {
        let lazyImages = [].slice.call(document.querySelectorAll("span.img-test"));
        let active = false;

        if ("IntersectionObserver" in window) {
            let navTop = [].slice.call(document.querySelectorAll("div#offset-nav-top"));
            let navTopScroll = document.querySelector("html");
            let navTopObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        navTopScroll.classList.remove("scrolled");
                    } else {
                        navTopScroll.classList.add("scrolled");
                    }
                });
            });

            navTop.forEach(function(navTopElem) {
                navTopObserver.observe(navTopElem);
            });
        } else {
            const lazyLoad = function() {
                if (active === false) {
                    active = true;

                    setTimeout(function() {
                        lazyImages.forEach(function(lazyImage) {
                            if ((lazyImage.getBoundingClientRect().top <= window.innerHeight && lazyImage.getBoundingClientRect().bottom >= 0) && getComputedStyle(lazyImage).display !== "none") {
                                lazyImage.style.backgroundImage = "url(" + lazyImage.dataset.src + ")";
                                lazyImage.classList.remove("lazy");

                                lazyImages = lazyImages.filter(function(image) {
                                    return image !== lazyImage;
                                });

                                if (lazyImages.length === 0) {
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
    });

    $(document).ready(function() {
        hasScrollBar();

        $("input[type=checkbox]").on("change", function() {
            controllCheckboxGroup(this);
        });

        $(".btn-pick").on("click", function(e) {
            e.stopPropagation();
            const $btnPick = $(this);
            const action = $btnPick.hasClass("picked") ? "unpick" : "pick";
            const type = $btnPick.data("type");
            const pickid = $btnPick.data("pick");

            $.post("/shop/ajax.pick.php", {
                action: action,
                type: type,
                id: pickid
            }, function(response) {
                if (response.result == true) {
                    $btnPick.toggleClass("picked");
                } else {
                    switch (response.error) {
                        case "NOT_FOUND_MEMBER":
                            return openLogin();
                            break;
                        default:
                            return openPopup({
                                content: response.error
                            });
                            break;
                    }
                }
            }, "json");
            e.preventDefault();
        });

        //리뉴얼 픽
        $(".btn-pick-heart").on("click", function(e) {
            e.stopPropagation();
            const $btnPick = $(this);
            const action = $btnPick.hasClass("picked") ? "unpick" : "pick";
            const type = $btnPick.data("type");
            const pickid = $btnPick.data("pick");

            $.post("/shop/ajax.pick.php", {
                action: action,
                type: type,
                id: pickid
            }, function(response) {
                if (response.result == true) {
                    $btnPick.toggleClass("picked");
                } else {
                    switch (response.error) {
                        case "NOT_FOUND_MEMBER":
                            return openLogin();
                            break;
                        default:
                            return openPopup({
                                content: response.error
                            });
                            break;
                    }
                }
            }, "json");
            e.preventDefault();
        });

        $(".history-content-remove").on("click", function(e) {
            const $btn = $(this);
            const hi_id = $btn.data("id");
            const hi_type = $btn.data("type");

            $.post("/shop/ajax.history.php", {
                id: hi_id,
                type: hi_type
            }, function(response) {
                if (response.result == true) {
                    $btn.parent().remove();
                    $(".btn-nav-scroll-history").text($(".btn-nav-scroll-history").text() * 1 - 1);
                }
            }, "json");
            e.preventDefault();
        });

        $(function() {
            $('[data-toggle="popover"]').popover()
        })

        $(".custom-scrollbar").scrollbar({
            disableBodyScroll: true
        });

        $(".btn-order-action").on("click", function() {
            const action = $(this).data("action");
            const odid = $(this).data("odid");
            const ctid = $(this).data("ctid");

            switch (action) {
                case "invoice":
                    const invoice = $(this).data("invoice");
                    const invoiceCo = $(this).data("invoice-co");
                    window.open("/common/tracking.php?invc_no=" + invoice + "&invc_co=" + invoiceCo + "&view_popup=1", "winInvoice", "width=500,height=800px");
                    break;
                case "return":
                case "change":
                case "cancel":
                    location.href = "/member/order.cancel.php?od_id=" + odid + "&action=" + action;
                    break;
                case "confirm":
                    const answer = confirm("구매를 확정하시겠습니까?");

                    if (answer) {
                        const data = {
                            act: action,
                            od_id: odid,
                            ct_id: ctid
                        };

                        $.get("/shop/ajax.order.action.php", data, function(response) {
                            if (response.result) {
                                alert("구매확정이 완료되었습니다.")
                                return window.location.reload();
                            } else {
                                alert(response.error);

                                return response.result;
                            }
                        }, "JSON");
                    }
                    console.log(answer);
                    break;
            }
        });

        $(".btn-filter-range").on("click", function() {
            const today = "<?= date("Y-m-d") ?>";
            const $sDateElem = $("#filter-member-startdate");
            const $eDateElem = $("#filter-member-enddate");
            const $sDateElemMobile = $("#filter-member-startdate-mobile");
            const $eDateElemMobile = $("#filter-member-enddate-mobile");
            const range = $(this).data("range");
            let before = [];
            before[1] = "<?= date("Y-m-d", strtotime("-1 month")) ?>";
            before[3] = "<?= date("Y-m-d", strtotime("-3 month")) ?>";
            before[6] = "<?= date("Y-m-d", strtotime("-6 month")) ?>";

            $eDateElem.val(today).datepicker("setDate", today);
            $eDateElemMobile.val(today).datepicker("setDate", today);
            $sDateElem.val(before[range]).datepicker("setDate", before[range]);
            $sDateElemMobile.val(before[range]).datepicker("setDate", before[range]);

            return false;
        });
        scrollTop = $(window).scrollTop();
    });

    function openLogin() {
        const currentUrl = encodeURIComponent(location.href);
        location.href = "/auth/login.php?url=" + currentUrl;

        return false;
    }

    function memberLogout() {
        return location.href = "/bbs/logout.php";
    }

    $(window).on("resize", _.debounce(function() {
        hasScrollBar();
    }, 0)).scroll(_.throttle(function(e) {
        const scTop = $(window).scrollTop();
        const toUp = (scTop <= scrollTop) && (scTop <= scrollBottom);

        scrollTop = scTop > 0 ? scTop : 0;

        if (toUp) {
            $("html").removeClass("scroll-down").addClass("scroll-up");
        } else {
            $("html").removeClass("scroll-up").addClass("scroll-down");
        }

        // $("#console-display").text(scrollBottom + "/" + scTop + " / " + scrollTop);
    }, 500));

    $(".btn-join-sns").on("click", function() {
        const sns = $(this).data("sns");
        const url = "<?= $sns_login_uri ?>?provider=" + sns + "&amp;url=<?= $_SERVER['PHP_SELF'] ?>"
        const winSnsLogin = window.open(url, '_blank', 'width=800px,height=800px');
    });
</script>
<!-- Enliple Tracker Start -->
<script type="text/javascript">
let broswerTrackerTa = navigator.userAgent;
let deviceTrackerTa = "W";
if (broswerTrackerTa.indexOf("Mobile")>-1) { 
    deviceTrackerTa = "M";
}
(function(a,g,e,n,t){a.enp=a.enp||function(){(a.enp.q=a.enp.q||[]).push(arguments)};n=g.createElement(e);n.async=!0;n.defer=!0;n.src="https://cdn.megadata.co.kr/dist/prod/enp_tracker_self_hosted.min.js";t=g.getElementsByTagName(e)[0];t.parentNode.insertBefore(n,t)})(window,document,"script");
enp('create', 'common', 'litandard', { device: deviceTrackerTa });  // W:웹, M: 모바일, B: 반응형
enp('send', 'common', 'litandard');
</script>
<!-- Enliple Tracker End -->
<!-- START NEXDI 0222 -->
<script type="text/javascript" charset="UTF-8" src="//t1.daumcdn.net/adfit/static/kp.js"></script>
<script type="text/javascript">
     kakaoPixel('2967409213611789029').pageView();
</script>

<script type="text/javascript">
     kakaoPixel('2967409213611789029').pageView();
     kakaoPixel('2967409213611789029').participation();
</script>
<!-- END NEXDI 0222 -->
<?php if (G5_IS_IE) : ?>
    <!-- Latest v2.x.x -->
    <script src="/js/tiny-slider/tiny-slider.helper.ie8.js"></script>
    <script src="/re/js/css-vars-ponyfill-2.js"></script>
    <script>
        cssVars();
    </script>
<? endif ?>

<!-- start NEXDI 0326 -->
<script type="text/javascript" src="//wcs.naver.net/wcslog.js"> </script>
<script type="text/javascript">
if (!wcs_add) var wcs_add={};
wcs_add["wa"] = "s_3a59b688a58f";
if (!_nasa) var _nasa={};
if(window.wcs){
wcs.inflow();
wcs_do(_nasa);
}
</script>
<!-- end NEXDI 0326 -->