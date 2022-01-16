<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>


    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            /* background-color: rgba(0, 0, 0, .5); */
        }

        .btn-ani {
            border: none;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, .5);
            color: rgba(0, 0, 0, .5);
            cursor: pointer;
            transition: all .4s ease-in-out;
            font-weight: bold;
            outline: none;
        }

        .btn-ani:hover {
            background-color: rgba(255, 255, 255, 1);
            color: rgba(0, 0, 0, 1);
        }

        .btn-ani:focus {
            background-color: rgba(255, 255, 255, .8);
            color: rgba(0, 0, 0, 1);
        }

        .btn-ani:active {
            background-color: rgba(255, 255, 255, 1);
            color: rgba(0, 0, 0, 1);
        }

        .img-test {
            display: inline-block;
            width: 300px;
            height: 300px;
            /* background-color: rgba(0, 0, 0, .5); */
            background: url(/data/item/0000040000000001/thumb-7F82043_220_01_120x120.jpg) center center no-repeat;
            background-size: 100%;
            transition: all .5s ease-out;
            opacity: .9;
        }

        .img-test.lazy {
            background-size: 105%;
        }

        .img-test:hover {
            background-size: 105%;
            transition: background-size .2s ease-out;
            opacity: 1;
        }

        .nav-top {
            display: inline-block;
            width: 100%;
            padding: 24px;
            background-color: rgba(100, 100, 255);
            color: #ffffff;
            z-index: 2000;
        }

        #nav-top-scroll.scroll {
            visibility: hidden;
            display: inline-block;
            position: fixed;
            top: 0;
            left: 0;
            background-color: rgba(255, 100, 100);
            color: #ffffff;
            width: 100%;
            overflow: none;
            opacity: 0;
            transition: all .3s ease-out;
            z-index: 1900;
        }

        #nav-top-scroll.active {
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>

<body>
    <div id="nav-top" class="nav-top"><button id="btn-test" class="btn-ani">버튼</button><button id="btn-test" class="btn-ani">버튼</button><button id="btn-test" class="btn-ani">버튼</button></div>
    <div id="nav-top-scroll" class="nav-top scroll active"><button id="btn-test" class="btn-ani">버튼</button><button id="btn-test" class="btn-ani">버튼</button><button id="btn-test" class="btn-ani">버튼</button></div>
    <section class="test-section">
        <span class="img-test lazy" data-src="/data/item/0000040000000001/thumb-7F82043_220_01_150x150.jpg" placeholder="불러오는중"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/thumb-7F82043_220_M1_GJH_1_120x120.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/thumb-7F82043_220_M2_GJH_1_80x80.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/thumb-7F82043_220_01_480x480.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/thumb-7F82043_220_M1_GJH_1_150x150.jpg"></span>
    </section>
    <section class="test-section">
        <span class="img-test lazy" data-src="/data/item/0000040000000001/thumb-7F82043_220_05_150x150.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/thumb-7F82043_220_M2_GJH_1_480x480.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/thumb-7F82043_220_01_120x120.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/thumb-7F82043_220_05_480x480.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/thumb-7F82043_220_M2_GJH_1_50x50.jpg"></span>
    </section>
    <section class="test-section">
        <span class="img-test lazy" data-src="/data/item/0000040000000001/thumb-7F82043_220_M2_GJH_1_120x120.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/7F82043_220_M1_GJH_1.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/thumb-7F82043_220_05_120x120.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/thumb-7F82043_220_M2_GJH_1_150x150.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/7F82043_220_M2_GJH_1.jpg"></span>
    </section>
    <section class="test-section">
        <span class="img-test lazy" data-src="/data/item/0000040000000001/7F82043_220_02.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/thumb-7F82043_220_02_150x150.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/thumb-7F82043_220_M2_GJH_1_100x100.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/7F82043_220_05.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/thumb-7F82043_220_02_480x480.jpg"></span>
    </section>
    <section class="test-section">
        <span class="img-test lazy" data-src="/data/item/0000040000000001/7F82043_220_01.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/thumb-7F82043_220_02_120x120.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/thumb-7F82043_220_M1_GJH_1_480x480.jpg"></span>
    </section>
    <section class="test-section">
        <span class="img-test lazy" data-src="/data/item/0000040000000001/7F82043_220_M1_GJH_1.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/7F82043_220_M2_GJH_1.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/7F82043_220_02.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/7F82043_220_05.jpg"></span>
        <span class="img-test lazy" data-src="/data/item/0000040000000001/7F82043_220_01.jpg"></span>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let lazyImages = [].slice.call(document.querySelectorAll("span.img-test"));
            let active = false;

            if ("IntersectionObserver" in window) {
                console.log("IntersectionObserver ON");
                let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            let lazyImage = entry.target;
                            lazyImage.style.backgroundImage = "url(" + lazyImage.dataset.src + ")";
                            lazyImage.classList.remove("lazy");
                            lazyImageObserver.unobserve(lazyImage);
                        }
                    });
                });

                lazyImages.forEach(function(lazyImage) {
                    lazyImageObserver.observe(lazyImage);
                });

                let navTop = [].slice.call(document.querySelectorAll("div#nav-top"));
                let navTopScroll = document.querySelector("div#nav-top-scroll");
                let navTopObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            console.log("SEE");
                            navTopScroll.classList.remove("active");
                        } else {
                            console.log("CANT SEE");
                            navTopScroll.classList.add("active");
                        }
                    });
                });

                navTop.forEach(function(navTopElem) {
                    navTopObserver.observe(navTopElem);
                });
            } else {
                console.log("IntersectionObserver OfF");
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
    </script>
</body>

</html>