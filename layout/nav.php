<style>
    html.scroll-down #nav-top-small {top: -150px!important;}
    html.scroll-down .topSubmenu {top: 0px!important;}
    #top-brand-banner-container ul {
	margin: 0;
	padding: 0;
	border: 0;
    font-family: 'Noto Sans KR', sans-serif !important;
	font-size: 100%;
	font: inherit;
    vertical-align: baseline;
    }
    .main_category a {
	margin: 0;
	padding: 0;
	border: 0;
	font-size: 100%;
	font: inherit;
    vertical-align: baseline;
    font-weight: 400;
    }
    #top-brand-banner-container { width: 100%; position: relative; border-bottom: 1px solid #c7c7c7; padding-top: 2px; }
    #top-brand-banner-container ul { width: 1420px; position: relative; margin: 0 auto; box-sizing: border-box; }
    #top-brand-banner-container ul li { display: inline-block; padding: 0 18px 5px 20px; box-sizing: border-box; height: 36px; }
    #top-brand-banner-container ul li:hover { border-bottom: 2px solid #000; }
    #top-brand-banner-container ul li.active { border-bottom: 2px solid #000; }
    /* NAV */
    .sub_drop li a {
        font-weight: 400 !important;
    }
    .sub_drop .dep3 {position: absolute; margin-left: 130px; width: 140px; top:0px; background: rgba(255,255,255,.9); padding: 0px 20px; visibility: hidden; border-left: 1px solid #000; }
    .sub_drop .dep3 li { font-size: 14px; list-style:none; padding-left:0px;}
    .sub_drop .dep3 li a { margin-left: -10px !important; }

</style>
<?php

$cookieStr = html_entity_decode(preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($_COOKIE['sword'])), null, 'UTF-8');
if (!$skeyword) $skeyword = $skeyword2;
if (!$cookieStr) {
    $recentViewArr = null;
} else {
    $recentViewArr=explode("\\\\", $cookieStr);
}

$swSql = "SELECT sw_value FROM lt_search_word ORDER BY sw_seq ASC";
$searchWordRes = sql_query($swSql);


$swSql2 = "SELECT sw_value FROM lt_search_word ORDER BY sw_seq ASC";
$searchWordRes2 = sql_query($swSql2);

?>
<!-- <link rel="stylesheet" href="/css/renewal2107.css">
<link rel="stylesheet" href="/css/renewal2107_reset.css"> -->
<style>
            #nav-bottom-search-small {
            border-radius: 20px 20px 0 0;
            width: 100%;
            height:650px;
            z-index: 1100;
            background: #ffffff !important; 
            text-align: center;
            display: none;
            position: fixed;
            top: 60px;

        }
        input[type=text]:focus {
            outline:none !important;
        }
    /* @media (max-width: 1366px) {
        #nav-bottom-search-small {
            border-radius: 20px 20px 0 0;
            width: 100%;
            height:650px;
            z-index: 1100;
            background: #ffffff !important; 
            text-align: center;
            display: none;
            position: fixed;
            top: 60px;

        }
    } */
    #goApp {
        margin-top: 40%;
        width: 300px;
        height: 65px;
        font-size:22px;
        color: white;
        line-height: 50px;
        text-align: center;
        background: #333333;
        opacity: 1 !important;
        border: 0;
        outline: 0;
        border-radius: 12px;
        z-index: 1103;
    }
</style>

<div name="appLinkDivButton" id="appLinkDivButton"  style="text-align: center; position: fixed; top: 0px; left:0px; display: none; width: 100%; height: 100%;  z-index: 1102;">
    <button name ="goApp" id ="goApp"  onclick="location.href='intent://app/#Intent;package=com.litandard.lifelike;scheme=lifelikeand;end'" target="blank">라이프라이크 App 접속  &nbsp;<img src="/mobile/img/mb/ico/ico_arrow_r_white.png" style="margin-top: -4px;"> </button>
    <br><br>
    <p onclick="viewWeb()" style="color: white; text-decoration: underline;"> 모바일웹 접속 </p>
</div>
<div name="appLinkDivIosButton" id="appLinkDivIosButton"  style="text-align: center; position: fixed; top: 0px; left:0px; display: none; width: 100%; height: 100%;  z-index: 1102;">
    <button name ="goApp" id ="goApp" onclick="iosGoApp()" target="blank">라이프라이크 App 접속  &nbsp;<img src="/mobile/img/mb/ico/ico_arrow_r_white.png" style="margin-top: -4px"> </button> 
    <br><br>
    <p onclick="viewWeb()" style="color: white; text-decoration: underline;"> 모바일웹 접속 </p>
</div>

<div name="appLinkDiv" id="appLinkDiv"  style="text-align: center; position: fixed; top: 0px; left:0px; display: none; width: 100%; height: 100%;  z-index: 1101; background-color: #000;filter: alpha(opacity=50); opacity: .7;">
</div>


<div id="nav-top-big" class="on-big">
    <?php if (!empty($g5_banner_new['GNB_TOP'])) : ?>
        
        <?php  if (!empty($g5_banner_new['GNB_TOP'][0]['cp_image_1'])) :?>
            <div onclick="viewCount('pcbanner')" id="main_swiper-container2" class="swiper-container">
                <div id="main-banner-wrapper" class="swiper-wrapper">
                    <?php 
                        foreach ($g5_banner_new['GNB_TOP'] as $gtv) { ?>
                        <div OnClick="location.href ='<?= $gtv['cp_link'] ?>'" class="swiper-slide" id="nav-top-big-tab" style="background-image: url(/data/banner/<?= $gtv['cp_image_1'] ?>); cursor: pointer;">
                            <span></span>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <? else : ?>
            <a href="<?= $g5_banner_new['GNB_TOP'][0]['cp_link'] ?>" style="color: #<?= $g5_banner_new['GNB_TOP'][0]['ba_color'] ?>">
                <div id="nav-top-big-tab" style="background-color: #<?= $g5_banner_new['GNB_TOP'][0]['ba_bg_color'] ?>;">
                    <span><?= $g5_banner_new['GNB_TOP'][0]['ba_subject'] ?></span>
                </div>
            </a>
        <? endif?>
    <? else : ?>
        <style>
            #nav-top-big-submenu {
                padding-top: 93px;
            }

            #nav-top-big-submenu.active {
                height: 408px;

            }
        </style>
    <?php endif ?>

    <script>
        var swiper_main = new Swiper('#main_swiper-container2', {
            slidesPerView: 1,
            spaceBetween: 0,
            // loop: true,
            centeredSlides: true,
            autoplay : { 
                delay : 8000,
            }
        });
    </script>

    <div id="top-brand-banner-container">
        <ul>
            <li class="<?= strpos($uri , 'brand.php?br_id=1') ? 'active' : '' ?>"><a href="https://lifelike.co.kr/shop/brand.php?br_id=1"><img src="/img/renewal2107/nav/bn1.png" alt=""></a></li>
            <li class="<?= strpos($uri , 'brand.php?br_id=9') ? 'active' : '' ?>"><a href="https://lifelike.co.kr/shop/brand.php?br_id=9"><img src="/img/renewal2107/nav/bn7.png" alt=""></a></li>
            <li class="<?= strpos($uri , 'brand.php?br_id=2') ? 'active' : '' ?>"><a href="https://lifelike.co.kr/shop/brand.php?br_id=2"><img src="/img/renewal2107/nav/bn2.png" alt=""></a></li>
            <!-- <li class="<?= strpos($uri , 'brand.php?br_id=3') ? 'active' : '' ?>"><a href="https://lifelike.co.kr/shop/brand.php?br_id=3"><img src="/img/renewal2107/nav/bn3.png" alt=""></a></li> -->
            <li class="<?= strpos($uri , 'brand.php?br_id=5') ? 'active' : '' ?>"><a href="https://lifelike.co.kr/shop/brand.php?br_id=5"><img src="/img/renewal2107/nav/bn4.png" alt=""></a></li>
            <li class="<?= strpos($uri , 'brand.php?br_id=6') ? 'active' : '' ?>"><a href="https://lifelike.co.kr/shop/brand.php?br_id=6"><img src="/img/renewal2107/nav/bn5.png" alt=""></a></li>
            <li class="<?= strpos($uri , 'brand.php?br_id=4') ? 'active' : '' ?>"><a href="https://lifelike.co.kr/shop/brand.php?br_id=4"><img src="/img/renewal2107/nav/bn6.png" alt=""></a></li>
            <!-- <li><a href="http://localhost/shop/brand.php?br_id=1"><img src="/img/renewal2107/nav/bn7.gif" alt=""></a></li> -->
        </ul>
    </div>   
    <div id="nav-top-big-menu">
        <div class="Topleft">
            <a href="/">
                <img id="nav-top-big-logo" src="/img/re/logo.png" srcset="/img/re/logo@2x.png 2x,/img/re/logo@3x.png 3x" class="logo">
            </a>
        </div>
        <div class="Topright">
            <div class="btn-nav-top-big-warpper">
				<!-- <a href="/shop/cart.php" class="btn-nav-top-big btn-bag" style="margin-right: 280px;"><span class="cart-count"><?= $cart_count ?></span></a> -->
                <!-- <a href="" class="btn-nav-top-big scroll-on">구스베딩리스너</a> -->
                <?php if ($is_member) : ?>
                    <a href="/bbs/logout.php?url=<?= $_SERVER['REQUEST_URI'] ?>" class="btn-nav-top-big btn-logout">로그아웃</a>
                <?php else : ?>
                    <a href="/auth/login.php?url=<?= $_SERVER['REQUEST_URI'] ?>" class="btn-nav-top-big btn-login">로그인</a>
                <?php endif ?>
                <a href="/member/dashboard.php" class="btn-nav-top-big btn-logout">마이페이지</a>
                <a href="/shop/cart.php" class="btn-nav-top-big btn-center">장바구니&nbsp <span class="cart-count"><?= $cart_count ?></span></a>
            </div>
            <div style="margin-left: -200px;">
                <form action="/search.php" method="POST" name="searchForm">
                    <input type='text' autocomplete="off" id ='skeyword' class="skeyword" style="border: 2px solid black; width:395px; height:35px" name="skeyword" value="<?php echo $skeyword ?>" placeholder="검색어를 입력하세요.">
                    <div id="submitSearchDiv" style="float:right; position: absolute; z-index: 200;margin-top: -30px; margin-left: 365px">
                        <input type="submit" onclick="recentSearch()"; value="" style="background:url('/img/re/search.png'); width:25px; height:25px; border: 0px;">
                    </div>
                </form>
                <div id="searchDiv" style="border: 1px solid black; position: absolute; z-index: 201; background-color: #ffffff; width :395px; height: 440px; margin-left: 0; display: none;">
                    <table style="width: 100%;">
                        <colgroup>
                            <col style="width:50%;">
        			        <col style="width:50%;">
                        </colgroup>
                        <tr style="text-align: center; height: 40px;font-size: 12px; font-weight: 500; cursor: pointer;">
                            <td class="searchTd" id='recentTd' name='recentTd' onClick="recentTd()" style="background-color: #ffffff; border-right: 1px solid #979797; color: #333333; line-height: 40px; ">최근검색어</td>
                            <td class="searchTd" id='recommendTd' name='recommendTd' onClick="recommendTd()" style="background-color: #f2f2f2; color: #a9a9a9; border-bottom: 1px solid #979797; line-height: 40px;">추천검색어</td>
                        </tr>
                        <tr style="height: 17px;">

                        </tr>
                        <?foreach ($recentViewArr as $rva) : ?>
                            <tr class='recentValue' style="height: 34px; text-align: left; font-size: 12px; font-weight: 500; color: #3a3a3a; border-bottom: solid 1px #f2f2f2; cursor: pointer;"> 
                                <td class="searchTd" onclick="recentClick('<?= $rva?>')"; colspan="2">&nbsp&nbsp&nbsp&nbsp<?= $rva?></td>
                            </tr>
                        <? endforeach ?>
                        <?for ($i=0; $swr=sql_fetch_array($searchWordRes); $i++) : ?>
                            <tr class='recommendValue' style="height: 34px; text-align: left; font-size: 12px; font-weight: 500; color: #3a3a3a; border-bottom: solid 1px #f2f2f2; cursor: pointer; display: none;"> 
                                <td class="searchTd" onclick="recentClick('<?= $swr['sw_value']?>')" colspan="2">&nbsp&nbsp&nbsp&nbsp<?= $swr['sw_value']?></td>
                            </tr>
                        <? endfor ?>


                    </table>
                    <div style="background-color: #f2f2f2; height: 40px; width :100%; position: absolute; bottom: 1px; text-align: left;">
                    <p class="searchTd" style="margin-top: 10px;">&nbsp&nbsp&nbsp&nbsp<span class="searchTd" style="color: #9f9f9f; font-size: 12px; text-decoration: underline; cursor: pointer;" id="seachDelSpan" onclick="searchDelete()">검색기록삭제</span>
                    <span class="searchTd" style="text-align: right;">
                        <img onclick="imgxGr()" src="/img/re/x_gr.png" align="right" style="margin-right: 10px; margin-top: 4px;">
                    </span>
                    </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $select_ca_id = $_GET["ca_id"]; ?>
    <? $uri= $_SERVER['REQUEST_URI'];?>
    <div class="main_category">
        <div class="main_category_menu">
            <ul class="main_drop">


            <?php $mi = 0; foreach ($g5_menu as $di => $dm) : ?>
                <?php if ($dm['me_code'] == 10 || $dm['me_code'] == 20 || $dm['me_code'] == 30 || $dm['me_code'] == 40 || $dm['me_code'] == 41 || $dm['me_code'] == 42) : ?>
                    <li class="main_drop_li"><a class="main_drop_title  <?= strpos($uri , 'shop/list.php') && strpos($dm['me_link'] , substr($select_ca_id, 0, 4)) ? 'active':'' ?>" href="<?=$dm['me_link']?>"><?=$dm['me_name']?></a>
                        <ul class="sub_drop <?if($dm['me_code'] == 30 || $dm['me_code'] == 40) :?>reposition<?endif?>">
                        <?php
                        $mi2 = 0;
                        foreach ($dm['SUB'] as $dmsi => $dms) {
                            $next = '';
                            if ($dms['SUB2'] && $dms['SUB2'] != '') { 
                                $next = '&nbsp;&nbsp;>';
                            }
                            $me = '<li><a href="' . $dms['me_link'] . '">' . $dms['me_name'].$next . '</a>';    
                            if ($dms['me_name'] == 'PRODUCT' || $dms['me_name'] == 'WE PICK') $me = str_replace('submenu-item', 'submenu-title S1ENBLL', $me);

                            if ($dms['SUB2'] && $dms['SUB2'] != '') {
                                $me .=  '<ul class="dep3" style = "top : '.$mi2.'px;"> ';
                                foreach ($dms['SUB2'] as $dmsi2 => $dms2) {
                                    $me .=  '<li><a href="' . $dms2['me_link'] . '">'. $dms2['me_name'] .'</a></li>';
                                }
                                $me .= '</ul>';
                            }
                            $me .= '</li>';
                            echo $me;
                            $mi2+=26.1;
                        }
                        ?>
                        </ul>
                    </li>
                <?php endif; $mi++; ?>
            <?php endforeach; ?>



                <li class="main_drop_li sub_menu"><a class="sub_menu_hover" style="font-size:10px;">|</a></li>
                <li class="main_drop_li sub_menu <?= strpos($uri , 'shop/brand') ? 'active' : '' ?>" onclick="location.href='/shop/brand.php'"><a class="sub_menu_hover">브랜드</a></li>
                <li class="main_drop_li sub_menu <?= strpos($uri , 'best/') ? 'active' : '' ?>" onclick="location.href='/best/list.php?bs_ca=00'"><a class="sub_menu_hover">베스트</a></li>
                <li class="main_drop_li sub_menu <?= strpos($uri , 'special/') ? 'active' : '' ?>" onclick="location.href='/special/view.php'"><a class="sub_menu_hover">특가</a></li>
                <li class="main_drop_li sub_menu <?= strpos($uri , 'campaign/') ? 'active' : '' ?>" onclick="location.href='/campaign/list.php'"><a class="sub_menu_hover">기획전</a></li>
                <li class="main_drop_li sub_menu <?= strpos($uri , 'event/') ? 'active' : '' ?>" onclick="location.href='/event/list.php'"><a class="sub_menu_hover">이벤트</a></li>
            </ul>
        </div>
    </div>

    <script>
        $('#skeyword').click(function() {
            $('#searchDiv').show();
        });
        $('html').click(function(e) { 
            if(!$(e.target).hasClass("searchTd") && !$(e.target).hasClass("skeyword")) { 
                $('#searchDiv').hide(); 
            } 
        });
        function imgxGr() {
            $('#searchDiv').hide();
            $('#nav-bottom-small').show(); 
            $('#nav-bottom-search-small').modal('hide');
        }
        var seaDelCheck = 0;
        function searchDelete() {
            seaDelCheck = 1;
            set_cookie('sword',"",-1);
            $('.recommendValue').hide();
            $('.recentValue').hide();
        }
        function recentClick(e) {
            $('#skeyword').val(e);
            var form = document.searchForm;
            recentSearch();
            form.submit();
        } 

        function recentSearch() { 
            let searchWord = document.getElementById('skeyword').value;
            if (searchWord =='') return
            let cookieGet = get_cookie('sword');
            let cookieArr;
            let arrNum;
            if (cookieGet) {
                cookieArr = cookieGet.split('\\');
                arrNum = cookieArr.length;
            } 
            if (!arrNum) {
                return set_cookie('sword', searchWord,60*60*24*365);
            }
            let cookieSave = '';
            for (var i = 0; i < arrNum; i++) { 
                if (cookieArr[i]==searchWord)  cookieArr.splice(i,1);
                if (i == arrNum-1) cookieArr.unshift(searchWord) ;
            }
            for (var i = 0; i < cookieArr.length; i++) {
                if (i==0) {

                    cookieSave = cookieArr[i];
                } else {
                    cookieSave = cookieSave + '\\' + cookieArr[i];
                }
                if (i==9) break;
            }
            set_cookie('sword', cookieSave,60*60*24*365); 
        }
        function recentSearchMobile() { 
            let searchWord = document.getElementById('skeyword2').value;
            if (searchWord =='') return
            $('#skeyword').val(searchWord);
            let cookieGet = get_cookie('sword');
            let cookieArr;
            let arrNum;
            if (cookieGet) {
                cookieArr = cookieGet.split('\\');
                arrNum = cookieArr.length;
            } 
            if (!arrNum) {
                return set_cookie('sword', searchWord,60*60*24*365);
            }
            let cookieSave = '';
            for (var i = 0; i < arrNum; i++) { 
                if (cookieArr[i]==searchWord)  cookieArr.splice(i,1);
                if (i == arrNum-1) cookieArr.unshift(searchWord) ;
            }
            for (var i = 0; i < cookieArr.length; i++) {
                if (i==0) {

                    cookieSave = cookieArr[i];
                } else {
                    cookieSave = cookieSave + '\\' + cookieArr[i];
                }
                if (i==9) break;
            }
            set_cookie('sword', cookieSave,60*60*24*365); 
        }
        function recentTd() { 
            $('#recentTd').css({color: "#333333", "background-color":"#ffffff", "border-bottom": "0px"});
            $('#recommendTd').css({color: "#a9a9a9","background-color":"#f2f2f2", "border-bottom": "1px solid #979797"});
            if (seaDelCheck==0)  {
                $('.recentValue').show();
            }
            $('.recommendValue').hide();
            $('#seachDelSpan').show();
        }
        function recommendTd() { 
            $('#recommendTd').css({color: "#333333", "background-color":"#ffffff","border-bottom": "0px solid #979797"});
            $('#recentTd').css({color: "#a9a9a9","background-color":"#f2f2f2","border-bottom": "1px solid #979797"});
            $('.recommendValue').show();
            $('.recentValue').hide();
            $('#seachDelSpan').hide();
        }
        $('.categort_menu_hover').hover(function(){
            var tab_id = $(this).attr('data-tab');

            $('.categort_menu_hover').removeClass('active');
            $('.sub_menu_drowbox').removeClass('active');

            $("."+tab_id).addClass('active');
            $(this).addClass('active');
        }, function(){
            var tab_id = $(this).attr('data-tab');
            if($("."+tab_id).mouseover()){

            }else{
                $(this).removeClass('active');
                $("."+tab_id).removeClass('active');
            }
        });

        $('.sub_menu_drowbox').hover(function(){
            var parents_id = $(this).attr('data-parents');

            $('.categort_menu_hover').removeClass('active');
            $('.sub_menu_drowbox').removeClass('active');
            
            $("#"+parents_id).addClass('active');
            $(this).addClass('active');
        }, function(){
            var parents_id = $(this).attr('data-parents');
            $(this).removeClass('active');
            $("#"+parents_id).removeClass('active');
        });

    </script>


    <div id="nav-top-big-submenu" class="on-big">
        <?php $mi = 0; ?>
        <?php foreach ($g5_menu as $di => $dm) : ?>
            <?php if ($dm['me_code'] != 50 || $dm['me_code'] != 60) : ?>
                <div class="nav-top-big-submenu-subset subset-<?php echo $dm['me_order'] + 1 ?>">
                    <?php if (isset($g5_banner_new['GNB_IN']) && isset($g5_banner_new['GNB_IN'][$mi])) : ?>
                        <a href="<?= !empty($g5_banner_new['GNB_IN'][$mi]['cp_link']) ? $g5_banner_new['GNB_IN'][$mi]['cp_link'] : "/event/view.php?cp_id=" . $g5_banner_new['GNB_IN'][$mi]['cp_id'] ?>">
                            <span class="nav-top-top-big-submenu-subset-banner-wrapper" style="background-image: url(/data/banner/<?= $g5_banner_new['GNB_IN'][$mi]['cp_image_1'] ?>)">
                                <span class="nav-top-top-big-submenu-subset-banner-title S2KOBLL"><?= $g5_banner_new['GNB_IN'][$mi]['ba_subject'] ?></span>
                                <span class="nav-top-top-big-submenu-subset-banner-desc P3KOBLL">
                                    <?= $g5_banner_new['GNB_IN'][$mi]['cp_desc'] ?>
                                </span>
                            </span>
                        </a>
                    <? else : ?>
                        <span class="nav-top-top-big-submenu-subset-banner-wrapper" style="opacity: 0;"></span>
                    <?php endif ?>
                    <span class="nav-top-top-big-submenu-subset-wrapper">
                        <?php foreach ($g5_menu as $di => $dm) : ?>
                            <?php if ($dm['me_code'] == 10 || $dm['me_code'] == 20 || $dm['me_code'] == 30 || $dm['me_code'] == 40) : ?>
                                <span class="P2KOBLL">
                                    <?php
                                    foreach ($dm['SUB'] as $dmsi => $dms) {
                                        $me = '<div class="submenu-item"><a href="' . $dms['me_link'] . '">' . $dms['me_name'] . '</a></div>';
                                        if ($dms['me_name'] == 'PRODUCT' || $dms['me_name'] == 'WE PICK') $me = str_replace('submenu-item', 'submenu-title S1ENBLL', $me);
                                        echo $me;
                                    }
                                    ?>
                                </span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </span>
                </div>
                <?php $mi++ ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
<span id="nav-history-wrapper-contain" style="position:fixed ; bottom : 10%; right: 0;" class="on-big">
    <span id="nav-history">
        <!-- <div class="history-title H5ENBLM">DON'T FORGET</div> -->
        <div class="history-content-wrapper custom-scrollbar scrollbar-inner">
            <div class="history-content-wrapper-title">최근 본 상품<span class="nav-history-close" style="float:right"><img style="width:18px; height:18px;vertical-align: baseline;" src="/img/re/x.png" srcset="/img/re/x@2x.png 2x,/img/re/x@3x.png 3x"></span></div>

            <div class="swiper-container" id = "main_item_history">
                <div class="swiper-wrapper">
                    <?php if ($g5_user_history['COUNT'] > 0) : ?>
                        <?php foreach ($g5_user_history['ITEMS'] as $uh) : ?>
                            <?php if ($uh['hi_type'] == 'item') : ?>
                                <?php
                                $hi_thumb = get_it_thumbnail_path($uh['item']['it_img1'], 60, 60);
                                ?>
                                <div class=" swiper-slide history-content-item">
                                    <a href="/shop/item.php?it_id=<?= $uh['item']['it_id'] ?>">
                                        <span class="history-content-image" style="background-image: url(<?= $hi_thumb ?>);"></span>
                                    </a>
                                    <span class="history-content-remove" data-type="<?= $uh['hi_type'] ?>" data-id="<?= $uh['it_id'] ?>"><img src="/img/re/x.png" srcset="/img/re/x@2x.png 2x,/img/re/x@3x.png 3x"></span>
                                </div>
                            <?php endif ?>
                        <?php endforeach ?>
                    <?php endif ?>
                </div>
                <div class="on-big swiper-btns swiper-button-next swiper-button-black"></div>
                <div class="on-big swiper-btns swiper-button-prev swiper-button-black"></div> 
            </div>
            <script>
                var swiper_history = new Swiper('#main_item_history', {
                    slidesPerView: 3,
                    slidesPerGroup : 3,
                    centeredSlides: false,
                    spaceBetween: 8,
                    grabCursor: false,
                    cssMode: false,
                    loop: false,
                    keyboard: false,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                });
            </script>

            <?php if ($g5_user_history['COUNT'] == 0) : ?>


                <div class="history-content-item">

                    <span class="history-content-image" style="text-align : center;">히스토리가 <br> 없습니다.</span>

                </div>


            <?php endif ?>
        </div>
    </span>
    <span id="nav-scroll-spy">
        <span class="nav-scroll-spy btn-nav-scroll-history C2ENMIL"><?= $g5_user_history['COUNT'] ?></span>
        <span class="nav-scroll-spy btn-nav-scroll-up"></span>
        <span class="nav-scroll-spy btn-nav-scroll-down"></span>
    </span>
</span>


<!-- 모바일 시작 -->
<!-- <div id="nav-top-small" class="nav_app on-small" style="    padding-top: unset; display:none" >
    <img id ="appAd" class ="appAd" name ="appAd" src="https://lifelikecdn.co.kr/imgetc/APP_MC_TOP.png" style="position: absolute; top:0; left: 0; max-width: 100%; height: auto;" onclick="location.href='https://lifelike.co.kr/event/view.php?cp_id=5'">
</div> -->
<div id="nav-top-small" class="nav_app on-small" style="padding-top: unset; display:none; text-align: center; background: #d0d6c7;" >
    <img id ="appAd" class ="appAd" name ="appAd" src="https://lifelikecdn.co.kr/imgetc/APP_MC_TOP.png" style="position: relative; top:0; left: 0; max-width: 100%; height: auto;" onclick="location.href='https://lifelike.co.kr/event/view.php?cp_id=5'">
</div>

<div id="nav-top-small" class="nav_main on-small" name = "nav_main" style="    padding-top: unset;">
    <? if ($g5_title) : ?>
        <span style="background: url(/img/re/left@3x.png) center center no-repeat; float : left; width: 10px; height: 48px; background-size: contain; display: inline-block; vertical-align: middle;" onclick="historyBack('<?=$g5_title?>')"></span>
        <?if ($g5_product_detail) :?>
            <a href="/">
                <img id="nav-top-small-logo" src="/img/re/logo.png" srcset="/img/re/logo@2x.png 2x,/img/re/logo@3x.png 3x" class="logo">
            </a>
        <?endif?>
    <? else : ?>
        <div>
            <img src="/img/re/hamburger.png" srcset="/img/re/hamburger@2x.png 2x,/img/re/hamburger@3x.png 3x" class="tophamburger btn-toggle-nav-burger" style="height: 44px;">    
        </div>
    <? endif ?>
    <div class="toplogo">
    <? if ($g5_title) : ?>
        
        <div style="text-align: center; line-height: 48px; font-size: 20px; font-weight: bold; display: inline-block; vertical-align: middle; height: 48px;">
            <?= $g5_title ?>
        </div>
    <? else : ?>
        <a href="/">
            <img id="nav-top-small-logo" src="/img/re/logo.png" srcset="/img/re/logo@2x.png 2x,/img/re/logo@3x.png 3x" class="logo">
        </a>
    <? endif ?>
    </div>
    <div class="topBtnGroup">
        <!-- <a href="/search.php"><img src="/img/re/group@3x.png" srcset="/img/re/group@2x.png 2x,/img/re/group@3x.png 3x" class="topsearch btn-toggle-nav-search" style="height: 44px; margin-right:10px;"></a> -->
        <a onclick="inputClick()"><img src="/img/renewal2107/mo_nav/btn_search.png" class="topsearch btn-toggle-nav-search" style="width: 22px; height: 44px; margin-right:10px;"></a>
        <a href="/shop/cart.php"><img src="/img/re/bag.png" srcset="/img/re/bag@2x.png 2x,/img/re/bag@3x.png 3x" class="topbag btn-toggle-nav-bag" style="height: 44px;"></a>
    </div>
</div>
<? $uri= $_SERVER['REQUEST_URI'];
?>

<?php if(strpos($uri , 'shop/list.php') ) : ?>
    <style>
        html.scroll-down #nav-bottom-small{display: none;}
    </style>

    <div class="on-small categorySubmenu">
        <ul>
            <li class="<?= $uri == '/' ? 'active' : '' ?>"><span><a class="<?= substr($select_ca_id, 0, 4) == '1010' ? 'active':'' ?>" href="/shop/list.php?ca_id=1010">구스다운</a></span></li>
            <li class="<?= $uri == '/' ? 'active' : '' ?>"><span><a class="<?= substr($select_ca_id, 0, 4) == '1020' ? 'active':'' ?>" href="/shop/list.php?ca_id=1020">침구</a></span></li>
            <li class="<?= $uri == '/' ? 'active' : '' ?>"><span><a class="<?= substr($select_ca_id, 0, 4) == '1030' ? 'active':'' ?>" href="/shop/list.php?ca_id=1030">솜/속통</a></span></li>
            <li class="<?= $uri == '/' ? 'active' : '' ?>"><span><a class="<?= substr($select_ca_id, 0, 4) == '1040' ? 'active':'' ?>" href="/shop/list.php?ca_id=1040">키즈</a></span></li>
            <li class="<?= $uri == '/' ? 'active' : '' ?>"><span><a class="<?= substr($select_ca_id, 0, 4) == '1041' ? 'active':'' ?>" href="/shop/list.php?ca_id=1041">홈데코</a></span></li>
            <li class="<?= $uri == '/' ? 'active' : '' ?>"><span><a class="<?= substr($select_ca_id, 0, 4) == '1042' ? 'active':'' ?>" href="/shop/list.php?ca_id=1042">메모리폼베개</a></span></li>
            <li class="<?= strpos($uri , 'shop/brand') ? 'active' : '' ?>"><span><a href="/shop/brand.php">브랜드</a></span></li>
            <li class="<?= strpos($uri , 'best/') ? 'active' : '' ?>"><span><a href="/best/list.php?bs_ca=00">베스트</a></span></li>
            <li class="<?= strpos($uri , 'special/') ? 'active' : '' ?>"><span><a href="/special/view.php">특가</a></span></li>
            <li class="<?= $uri == '/campaign/list.php' ? 'active' : '' ?>"><span><a href="/campaign/list.php">기획전</a></span></li>
            <li class="<?= $uri == '/event/list.php' ? 'active' : '' ?>"><span><a href="/event/list.php">이벤트</a></span></li>
        </ul>
        <ul class="categort_scoll_area">
            <?php $all = (string) $ca_id;
                if( strlen($select_ca_id) <5){
                    echo  '<li><span><a class="active" href="/shop/list.php?ca_id=' .  substr($all, 0, 4) . '">전체</a></span></li> ';
                }else{
                    echo  '<li><span><a href="/shop/list.php?ca_id=' .  substr($all, 0, 4) . '">전체</a></span></li> ';
                }
            ?>
            <?php
            if ($ca_id != '1093' && $ca_id != '1092' && $ca_id != '1091') {
                foreach ($current_menu['SUB'] as $sm) {
                    if ($sm['me_name'] == "PRODUCT" || $sm['me_name'] == "WE PICK") $sm['me_name'] = "전체보기";
                    if ( strpos($sm['me_link'] , $select_ca_id) && strlen($select_ca_id) >4 ){
                        echo '<li><span><a class="active" href="' . $sm['me_link'] . '">' . $sm['me_name'] . '</a></span></li>';
                    }else{
                        echo '<li><span><a href="' . $sm['me_link'] . '">' . $sm['me_name'] . '</a></span></li>';
                    }
                }
            }
            ?>

        </ul>
    </div>
<? elseif (strpos($uri , 'member/') ||  strpos($uri , 'history_mo.php') ) : ?>
    <style>
        #offset-nav-top {
            height: 40px;
            margin-bottom: 0;
        }
        html.scroll-down #nav-top-small{top : 0;}
    </style>
    
<? else : ?>
    <div class="on-small topSubmenu">
        <ul class="topSubmenu_scroll_area">
            <li><span><a class="<?= $uri == '/' ? 'active' : '' ?>" href="/">홈</a></span></li>
            <li><span><a class="<?= strpos($uri , 'shop/brand') ? 'active' : '' ?>" href="/shop/brand.php">브랜드</a></span></li>
            <li><span><a class="<?= strpos($uri , 'best/') ? 'active' : '' ?>" href="/best/list.php?bs_ca=00">베스트</a></span></li>
            <li><span><a class="<?= strpos($uri , 'special/') ? 'active' : '' ?>" href="/special/view.php">특가</a></span></li>
            <li><span><a class="<?= strpos($uri , 'campaign/') ? 'active' : '' ?>" href="/campaign/list.php">기획전</a></span></li>
            <li><span><a class="<?= strpos($uri , 'event/') ? 'active' : '' ?>" href="/event/list.php">이벤트</a></span></li>
        </ul>
    </div>
<? endif ?>

<div id="nav-bottom-search-small" class="on-small" style ="display:none; overflow:scroll">
<div>
    <form action="/search.php" method="POST" name="searchFormMobile" style="margin-top:20px;">
        <input type='text' autocomplete="off" id ='skeyword2' name="skeyword2"  style="border: 2px solid black; width:307px; height:48px; margin-left: -48px"  value="<?php echo $skeyword ?>" placeholder="검색어를 입력하세요.">
    
        <!-- <div class="input-group-append" id="btn-search-action">
            <button class="btn" type="button" id="btn-search-clear"><img src="/img/re/x.png" srcset="/img/re/x@2x.png 2x,/img/re/x@3x.png 3x"></button>
            <button class="btn" type="submit" onclick="recentSearch()"><img src="/img/re/search.png" srcset="/img/re/search@2x.png 2x,/img/re/search@3x.png 3x"></button>
        </div> -->
        
        <div style="float:right; position: absolute; z-index: 200; margin-top: -46px; margin-left:265px">
            <!-- <button class="btn" type="button" id="btn-search-clear"><img src="/img/re/x.png" srcset="/img/re/x@2x.png 2x,/img/re/x@3x.png 3x"></button> -->
            <!-- <input type="submit" onclick="recentSearch()"; value="" style="background:url('/img/re/search.png'); width:20px; height:20px; border: 0px;"> -->
            <button style ="border: 0px;" class="btn" type="submit" onclick="recentSearchMobile()"><img src="/img/re/search.png" srcset="/img/re/search@2x.png 2x,/img/re/search@3x.png 3x"></button>
        </div>
        <img onclick="imgxGr()"src="/img/re/x.png" srcset="/img/re/x@2x.png 2x,/img/re/x@3x.png 3x" style="margin-top:10px; position: fixed; text-align: right; margin-left: 17px;">
        <!-- <button class="btn" type="button" id="btn-search-clear" style="border: 0;" w><img src="/img/re/x.png" srcset="/img/re/x@2x.png 2x,/img/re/x@3x.png 3x"></button> -->
    </form>
    <div id="searchDivMainMobile" style="background-color: #ffffff; width :100%; height: 481px; margin-top:10px;">
                <table style="width: 100%;">
                    <colgroup>
                        <col style="width:50%;">
        		        <col style="width:50%;">
                    </colgroup>
                    <tr height="30" style="text-align: center; height: 40px;font-size: 12px; font-weight: 500; cursor: pointer;">
                        <td class="searchTd2" id='recentTd2' name='recentTd2' onClick="recentTd2()" style="background-color: #ffffff; border-right: 1px solid #979797; border-left: 1px solid #979797; border-top: 1px solid #979797; color: #333333; line-height: 40px; ">최근검색어</td>
                        <td class="searchTd2" id='recommendTd2' name='recommendTd2' onClick="recommendTd2()" style="background-color: #f2f2f2; color: #a9a9a9; border-bottom: 1px solid #979797; border-top: solid 1px #f2f2f2; line-height: 40px;">추천검색어</td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <?
                    if (!empty($recentViewArr)) {
                    foreach ($recentViewArr as $rva) : ?>
                        <tr class='recentValue' style="height: 35px; text-align: left; font-size: 12px; font-weight: 500; color: #3a3a3a; border-bottom: solid 1px #f2f2f2; cursor: pointer;"> 
                            <td class="searchTd2" onclick="recentClick('<?= $rva?>')"; colspan="2">&nbsp&nbsp&nbsp&nbsp<?= $rva?></td>
                        </tr>
                    <? endforeach ?>
                    <? } ?>
                    <?for ($i=0; $swr=sql_fetch_array($searchWordRes2); $i++) : ?>
                        <tr class='recommendValue' style="height: 35px; text-align: left; font-size: 12px; font-weight: 500; color: #3a3a3a; border-bottom: solid 1px #f2f2f2; cursor: pointer; display:none;"> 
                            <td class="searchTd2" onclick="recentClick('<?= $swr['sw_value']?>')" colspan="2">&nbsp&nbsp&nbsp&nbsp<?= $swr['sw_value']?></td>
                        </tr>
                    <? endfor ?>
                </table>
                <div style="background-color: #f2f2f2; height: 40px; width :100%; text-align: left; position:fixed;">
                    <p class="searchTd2" style="margin-top: 7px; width:345px;">&nbsp&nbsp&nbsp&nbsp<span class="searchTd2" style="color: #9f9f9f; font-size: 13px; text-decoration: underline; cursor: pointer;" id="seachDelSpan2" onclick="searchDelete()">검색기록삭제</span>
                    </p>
                    <!-- <span class="searchTd2" onclick="imgxGr()" style="margin-top: 11px; margin-left: -19px; color: #9f9f9f; font-size: 13px; cursor: pointer;"> -->
                            <!-- 닫기 -->
                            <!-- <img src="/img/re/x_gr.png" align="right" style="margin-top: 7px;" onclick="imgxGr()"> -->
                        <!-- </span> -->
                </div>
                <!-- <div style="height: 20px;">&nbsp</div> -->
    </div>
</div>
</div>

<div id="nav-bottom-small" class="on-small">
    <div class="nav-bottom-wapper"><a onclick="burger()"><div class="nav-bottom-img"><img src="/img/re/nav/menu@3x.png" srcset="/img/re/nav/menu@2x.png 2x,/img/re/nav/menu@3x.png 3x"></div><div>카테고리</div></a></div>
    <div class="nav-bottom-wapper"><a href="/"><div class="nav-bottom-img"><img src="/img/re/nav/home@3x.png" srcset="/img/re/nav/home@2x.png 2x,/img/re/nav/home@3x.png 3x"></div><div>홈</div></a></div>
    <div class="nav-bottom-wapper"><a href="/history_mo.php"><div class="nav-bottom-img"><img src="/img/re/nav/history@3x.png" srcset="/img/re/nav/history@2x.png 2x,/img/re/nav/history@3x.png 3x"></div><div>최근본상품</div></a></div>
    <div class="nav-bottom-wapper"><a href="/member/order.php"><div class="nav-bottom-img"><img src="/img/re/nav/bl-copy@3x.png" srcset="/img/re/nav/bl-copy@2x.png 2x,/img/re/nav/bl-copy@3x.png 3x"></div><div>배송</div></a></div>
    <div class="nav-bottom-wapper"><a href="/member/dashboard.php"><div class="nav-bottom-img"><img src="/img/re/nav/user@3x.png" srcset="/img/re/nav/user@2x.png 2x,/img/re/nav/user@3x.png 3x"></div><div>마이페이지</div></a></div>
</div>

<div id="nav-burger" class="on-small">
    <div id="nav-burger-title">
        <? if ($is_member) : ?>
            <?= $member['mb_name']?> 님
        <? else : ?>
            <a href="/auth/login.php">
                <img id="btn-nav-login" class="btn-toggle-nav-burger" src="/img/re/login.png" srcset="/img/re/login@2x.png 2x,/img/re/login@3x.png 3x"> 로그인
            </a>
        <? endif ?>
        <img id="btn-nav-burger-close" class="btn-toggle-nav-burger" src="/img/re/cancle.png" srcset="/img/re/cancle@2x.png 2x,/img/re/cancle@3x.png 3x">
    </div>
    <?php foreach ($g5_menu as $di => $dm) : ?>
        <? if ($dm['me_name'] != "이벤트" ) : ?>
        <div class="nav-burger-category-wrapper">
            <? if ($dm['me_name'] == "이벤트") : ?>
                <a href="<?= $dm['me_link'] ?>">
                    <div class="nav-burger-category-title btn-toggle-nav-burger-list" style="background-image: unset; color: var(--red-orange); display:none;"><?= $dm['me_name'] ?></div>
                </a>
            <? elseif ($dm['me_name'] == "브랜드") : ?>
                <a href="<?= $dm['me_link'] ?>">
                    <div class="nav-burger-category-title btn-toggle-nav-burger-list" style="background-image: unset;"><?= $dm['me_name'] ?> > </div>
                </a>
                <ul class="brand-ca-list-ul">
  
                <?php if (!empty($dm['SUB'])) :?>
                <?php $brand_idx = 0;
                    foreach ($dm['SUB'] as $bbd) : ?>
                    
                    <?php if($brand_idx > 2) : ?>
                        <?php if($brand_idx == 3) : ?>
                            <li><a href = "<?=$bbd['me_link']?>"><?=$bbd['me_name']?></a></li>
                            <li class="brand-plus" style="color : #959595; margin-bottom : 150px;">더보기</li>        
                        <? else : ?>
                            <li class="hiddenli"><a href = "<?=$bbd['me_link']?>"><?=$bbd['me_name']?></a></li>
                        <? endif?>
                    <? else : ?>
                        <li><a href = "<?=$bbd['me_link']?>"><?=$bbd['me_name']?></a></li>
                    <? endif?>
                    
                <?php $brand_idx++; endforeach ?>
                <? endif?>
                </ul>
                
            <? else : ?>
                <div class="nav-burger-category-title btn-toggle-nav-burger-list"><?= $dm['me_name'] ?></div>
                <div class="burger-list-line"></div>
                <div class="nav-burger-category-list">
                <a href="<?= $dm['me_link'] ?>"><div>전체</div></a>
                    <?php foreach ($dm['SUB'] as $dmsi => $dms) : ?>
                        
                        <!-- 저걸 -->
                        <!-- <a href="<?= $dms['me_link'] ?>"><div><?= $dms['me_name'] ?><span style="background: url(/img/re/sdown@3x.png) 100% center no-repeat; background-size: 18px; float:right;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></div></a> -->
                        <!-- <a href="<?= $dms['me_link'] ?>"><div><?= $dms['me_name'] ?><img src="/img/re/sdown.png" align="right" vertical-align="middle"></div></a> -->
                        <!-- <a href="<?= $dms['me_link'] ?>"><div class="nav-burger-category-title btn-toggle-nav-burger-list"><?= $dms['me_name'] ?></div></a> -->

                        <?php 
                            if ($dms['SUB2']) { ?>
                                <!-- <div class="nav-burger-category-title btn-toggle-nav-burger-list"><?= $dms['me_name'] ?></div> -->
                                <!-- <a><div class="nav-burger-category-title btn-toggle-nav-burger-list"><?= $dms['me_name'] ?></div></a> -->
                                <a class="isSub" onclick="sub2List('<?= $dms['me_name'] ?>')"><div class="depLine"><?= $dms['me_name'] ?><span class="burgerSubDep<?= $dms['me_name'] ?>" style="background: url(/img/re/sdown@3x.png) 100% center no-repeat; background-size: 18px; float:right;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></div></a>
                                <div class="nav-burger-category-wrapper2<?= $dms['me_name'] ?>" style="display: none;" >
                                <a href="<?= $dms['me_link'] ?>" ><div>전체</div></a>
                                <?php foreach ($dms['SUB2'] as $dmsi2 => $dms2) : ?>
                                    <a href="<?= $dms2['me_link'] ?>" ><div><?= $dms2['me_name'] ?></div></a>
                                <? endforeach ?>
                                </div>   
                            <?php } else { ?>
                                <a class="notSub" href="<?= $dms['me_link'] ?>"><div class="depLine"><?= $dms['me_name'] ?></div></a>
                            <?php }
                        ?>
                    <? endforeach ?>
                </div>
            <? endif ?>
        </div>
        <? endif ?>
    <?php endforeach ?>
    <div class="nav-burger-sub-wrapper" style="background-color:#ffffff">
        <div>
            <a href="https://litandard.com" target="_blank" class="btn-nav-burger-sub-bottom">회사소개</a>
            <a href="/member/member.center.php?filter_notice=" class="btn-nav-burger-sub-bottom">공지사항</a>
            <a href="/member/customer.php" class="btn-nav-burger-sub-bottom">1:1문의</a>
            <a href="/member/member.center.php" class="btn-nav-burger-sub-bottom">FAQ</a>
            <? if ($is_member) : ?><a href="/bbs/logout.php" class="btn-nav-burger-sub-bottom">로그아웃</a>
            <? else : ?><a href="/auth/login.php" class="btn-nav-burger-sub-bottom">로그인</a><? endif ?>
        </div>
    </div>
</div>

<script>
    let toggleMenu = false;
    function inputClick() {
        $('#nav-bottom-small').hide(); 
        $('#nav-bottom-search-small').modal('show');
        $("#skeyword2").focus();
        $("#skeyword2").focus(function(){
        $("#skeyword2").css("border","border: 2px solid black");
        });
        $("#nav-bottom-search-small").animate({height:"100%"},1000);
    }
    
    function recentTd2() { 
        $('#recentTd2').css({color: "#333333", "background-color":"#ffffff", "border-bottom": "0px solid", "border-right": "1px solid #979797", "border-left": "1px solid #979797","border-top": "1px solid #979797"});
        $('#recommendTd2').css({color: "#a9a9a9","background-color":"#f2f2f2", "border-bottom": "1px solid #979797", "border-top": "0px solid"});
        if (seaDelCheck==0)  {
            $('.recentValue').show();
        }
        $('.recommendValue').hide();
        $('#seachDelSpan2').show();
    }
    function recommendTd2() { 
        $('#recommendTd2').css({color: "#333333", "background-color":"#ffffff", "border-bottom": "0px solid", "border-right": "1px solid #979797", "border-left": "1px solid #979797","border-top": "1px solid #979797"});
        $('#recentTd2').css({color: "#a9a9a9","background-color":"#f2f2f2","border-bottom": "1px solid #979797", "border-top": "0px solid"});
        $('.recommendValue').show();
        $('.recentValue').hide();
        $('#seachDelSpan2').hide();
    }
    function historyBack(title){
        if(title == 'Q&A'){
            //중간에 수정 페이지도 왔다 가서. 뒤로가기 대시보드로 가지 않는 부분 있어서 수정
            location.href = "/member/dashboard.php";
        }else(
            history.back()
        )
    }

    function resizeSubMenuOffset() {
        let menu_wrapper_offset = $("#nav-top-big-menu").width() - $("#nav-top-big-menu-wrapper").width() - 490;
        if (menu_wrapper_offset < 100) menu_wrapper_offset = 100;
        $(".nav-top-top-big-submenu-subset-wrapper").css("margin-left", menu_wrapper_offset + "px");
    }

    function toggleSubMenu() {
        if (!toggleMenu) return $("#nav-top-big-submenu").removeClass("active");
    }

    $(".btn-nav-top-big-menu").hover(function() {
        const menuIdx = $(this).data("idx") - 1;
        const mt = menuIdx * 315 * -1;

        if (menuIdx == 4 || menuIdx == 5) return false;

        $(".nav-top-big-submenu-subset.subset-1").css("marginTop", mt + "px");
        $("#nav-top-big-submenu").addClass("active");
        toggleMenu = true;
    }, function() {
        toggleMenu = false;
        setTimeout(toggleSubMenu, 500)
    });

    $("#nav-top-big-submenu").hover(function() {
        toggleMenu = true;
        $("#nav-top-big-submenu").addClass("active");
    }, function() {
        toggleMenu = false;
        setTimeout(toggleSubMenu, 500)
    });

    $(".nav-scroll-spy").on("click", function() {
        if ($(this).hasClass("btn-nav-scroll-history")) {
            $("#nav-history").toggleClass("active");
        } else {
            const scrollTo = $(this).hasClass("btn-nav-scroll-up") ? 0 : $(document).height();
            $("html,body").animate({
                "scrollTop": scrollTo
            }, 500);
        }
    });

    $('.nav-history-close').on("click", function() {
        $("#nav-history").removeClass("active");
    });


    function burger(){
        $("#nav-burger").toggleClass("active");
        $(".brand-plus").css("display","block");
        $(".hiddenli").css("display","none");
        
        if($("#nav-burger").hasClass('active') === true){
            $("html").css("overflow","hidden");
        }else{
            $("html").css("overflow","auto");
        }
    }

    $(".btn-toggle-nav-burger").on("click", function() {
        $("#nav-burger").toggleClass("active");
        $(".brand-plus").css("display","block");
        $(".hiddenli").css("display","none");
        $("html").css("overflow","auto");
    });
    $(".btn-toggle-nav-burger-list").on("click", function() {
        $("[class^='burgerSubDep']").css({"background":"url(/img/re/sdown@3x.png) 100% center no-repeat","background-size": "18px", "float":"right"}); 
        $("[class^='nav-burger-category-wrapper2']").hide();
        $(".nav-burger-category-wrapper").removeClass("active");
        $(this).parent().addClass("active");

        if ($(this).parent().hasClass("active") === true) {
            $(this).parent().next(".nav-burger-category-wrapper").removeClass("active");
        } else {
            $(".nav-burger-category-wrapper").removeClass("active");
            $(this).parent().next(".qna-content").addClass("active");
        }
    });
    function sub2List(e) {
        if (!$(".nav-burger-category-wrapper2"+e).is(':visible')) {
            $("[class^='burgerSubDep']").css({"background":"url(/img/re/sdown@3x.png) 100% center no-repeat","background-size": "18px", "float":"right"}); 
            $(".burgerSubDep"+e).css({"background":"url(/img/re/sup@3x.png) 100% center no-repeat","background-size": "18px", "float":"right"}); 	
            $("[class^='nav-burger-category-wrapper2']").hide();
            $(".nav-burger-category-wrapper2"+e).show();
        } else {
            $("[class^='burgerSubDep']").css({"background":"url(/img/re/sdown@3x.png) 100% center no-repeat","background-size": "18px", "float":"right"}); 
            $("[class^='nav-burger-category-wrapper2']").hide();
        }
    };
    $(".brand-plus").on("click", function() {
        $(".brand-plus").css("display","none");
        $(".hiddenli").css("display","block");
    });
    function iosGoApp() {
        var visitedAt = (new Date()).getTime();
        setTimeout(function() {
            if ((new Date()).getTime() - visitedAt < 2000) {
                location.href = "http://apps.apple.com/kr/app/LIFELIKE/id1473080254";
            }
        }, 1000);
        location.href = "lifelike://";
        // setTimeout(function() { 
            // location.href = "lifelike://";
        // }, 0);
        return;
    }
    function viewWeb() {
        $("#appLinkDiv").hide();
        $("#appLinkDivButton").hide();
        $("#appLinkDivIosButton").hide();
        sessionStorage.setItem("_liferem", 1 );
    }
    function viewCount(e) {
        let devi = 'mobile'
        let broswerInfoC = navigator.userAgent;
        if (broswerInfoC.indexOf("Mobile")>-1 || broswerInfoC.indexOf("mobile")>-1) {
        } else {
            if (e!='subcate') devi = 'pc';
        }


        $.ajax({ 
            type: "POST",
            url: "/ajax_front/ajax_view_count.php",
            data: { kind: e,device: devi},
            success: function(res) {
            }
        })
    }
    $(document).ready(function() {
        let broswerInfo = navigator.userAgent;
        if (broswerInfo.indexOf("Mobile")>-1) {
            if (broswerInfo.indexOf("APP_ANDROID") < 0 && broswerInfo.indexOf("APP_IOS") < 0 ) { 
                $(".nav_app").css("display","block");
                let h = document.getElementById('appAd').clientHeight;
                let sMenu = document.getElementsByClassName('categorySubmenu');
                if (sMenu.length > 0) {
                    sMenu[0].style.top= h+44+'px';
                }
                let nMain = document.getElementsByClassName('nav_main');
                if (nMain.length > 0) {
                    nMain[0].style.top= h+'px';
                }
                let tsMeun = document.getElementsByClassName('topSubmenu');
                if (tsMeun.length > 0) {
                    tsMeun[0].style.top= h+44+'px';
                }
                // document.getElementsByClassName('nav_main')[0].style.top= h+'px';
                // document.getElementsByClassName('topSubmenu')[0].style.top= h+44+'px';
            }
            let liferem = sessionStorage.getItem("_liferem");
            if (!liferem) {
                if (broswerInfo.indexOf("Android")>-1 && broswerInfo.indexOf("APP_ANDROID")<0) { 
                    $("#appLinkDiv").show();
                    $("#appLinkDivButton").show();
                } else if (broswerInfo.indexOf("Mac")>-1 && broswerInfo.indexOf("APP_IOS")<0) { 
                    $("#appLinkDiv").show();
                    $("#appLinkDivIosButton").show();
                }
            }
        }

        setTimeout(resizeSubMenuOffset, 500);

        var left = 0;
        var lists = document.querySelectorAll('.topSubmenu_scroll_area a');
        for(var i = 0; i < lists.length; i++){
            if(lists[i-1] && lists[i].className == 'active'){
                left = lists[i-1].offsetLeft;
            }
        }
        $('.topSubmenu_scroll_area').scrollLeft((left-14));

        var left2 = 0;
        var lists2 = document.querySelectorAll('.categort_scoll_area a');
        for(var i = 0; i < lists2.length; i++){
            if(lists2[i-1] && lists2[i].className == 'active'){
                left2 = lists2[i-1].offsetLeft;
            }
        }
        $('.categort_scoll_area').scrollLeft((left2-14));


    });
    $(window).on("resize", resizeSubMenuOffset);
</script>