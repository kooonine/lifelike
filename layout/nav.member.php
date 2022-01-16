<?php
$sql_cnt_pick = "SELECT COUNT(*) AS CNT, SUM(IF(wi_type='item', 1,0)) AS CNT_ITEM, SUM(IF(wi_type='event', 1,0)) AS CNT_EVENT, SUM(IF(wi_type='brand', 1,0)) AS CNT_BRAND FROM {$g5['g5_shop_wish_table']} WHERE mb_id='{$member['mb_id']}' ORDER BY wi_time DESC";
$cnt_pick = sql_fetch($sql_cnt_pick);

// 쿠폰
$sql_count_coupon = "SELECT COUNT(*) AS CNT, (IF(cp.cz_id > 0, CONCAT('Z',cp.cz_id), CONCAT('M',cp.cm_no))) AS CID FROM {$g5['g5_shop_coupon_table']} AS cp LEFT JOIN {$g5['g5_shop_coupon_log_table']} AS cl ON cp.cp_id=cl.cp_id WHERE cl.cl_id IS NULL AND cp.mb_id IN ( '{$member['mb_id']}', '전체회원' ) AND cp_start <= '" . G5_TIME_YMD . "' AND cp_end >= '" . G5_TIME_YMD . "' GROUP BY CID";
$db_count_coupon = sql_query($sql_count_coupon);
$cp_count = $db_count_coupon->num_rows;

$sql_count_review = "SELECT COUNT(*) AS CNT FROM lt_shop_item_use WHERE mb_id='{$member['mb_id']}'";
$count_review = sql_fetch($sql_count_review);

$sql_count_review_all = "SELECT COUNT(*) AS CNT FROM lt_shop_cart AS ct LEFT JOIN {$g5['g5_shop_item_use_table']} AS its ON ct.it_id=its.it_id AND ct.ct_id=its.ct_id WHERE ct.mb_id='{$member['mb_id']}' AND ct.ct_status IN ('구매완료','배송완료','구매확정') AND its.is_id IS NULL";
$count_review_all = sql_fetch($sql_count_review_all);

$review_count = $count_review_all['CNT'];

$memberTierNav = "SELECT mb_tier FROM lt_member WHERE mb_id='{$member['mb_id']}' LIMIT 1";
$mt = sql_fetch($memberTierNav);
?>
<link rel="stylesheet" href="/re/css/member.css">
<link rel="stylesheet" href="/re/css/product.list.css">
<div class="on-big" style="margin-top: 180px;"></div>
<div class="on-big" style="float: left; height:40px"></div>
<div class="nav-member-top-info on-big">
    <div class="title"><a href="/member/dashboard.php">마이페이지</a></div>
    <div class="name" style="line-height: normal; margin-top: 31px; cursor: Default;">
        <?= $member['mb_name'] ?>님 <img style="margin-top: -4px;" src="/img/renewal2107/member/<?= $mt['mb_tier']?>_MINI.jpg" alt=""><br>
        <span style="font-size: 16px; font-weight: 500; cursor: pointer;"><a href="/member/rating.php">회원등급혜택 <img src="/img/re/right_gr.png" srcset="/img/re/right_gr@2x.png 2x,/img/re/right_gr@3x.png 3x"><a></span>
    </div>
    <div class="point" onclick="location.href='/member/point.php'">
        <span><a>포인트 <img src="/img/re/right_gr.png" srcset="/img/re/right_gr@2x.png 2x,/img/re/right_gr@3x.png 3x"></a><br> <?= number_format($member['mb_point']) ?>P </span>
    </div>
    <div class="coupon" onclick="location.href='/member/coupon.php'">
        <span><a>쿠폰 <img src="/img/re/right_gr.png" srcset="/img/re/right_gr@2x.png 2x,/img/re/right_gr@3x.png 3x"></a><br> <?= number_format($cp_count) ?>장</span>
    </div>
</div>

<?php
$uri = $_SERVER['REQUEST_URI'];
?>
<? if (strpos($uri, 'member/dashboard.php')) : ?>
    <div class="nav-samll-member-top on-small">
        <div class="name"><?= $member['mb_name'] ?>님 <img> <a href="/member/info.php">회원정보</a></div>
        <div class="point">
            <span>포인트<a href="/member/point.php"><?= number_format($member['mb_point']) ?>P <img src="/img/re/right_gr.png" srcset="/img/re/right_gr@2x.png 2x,/img/re/right_gr@3x.png 3x"> </a> </span>
        </div>
        <div class="point" style="line-height:50px">
            <span>쿠폰<a href="/member/coupon.php"> <?= number_format($cp_count) ?>장 <img src="/img/re/right_gr.png" srcset="/img/re/right_gr@2x.png 2x,/img/re/right_gr@3x.png 3x"></a></span>
        </div>
        <div class="coupon" style="line-height:50px">
            <span>회원등급<a href="/member/rating.php"> <?= $mt['mb_tier'] ?> <img src="/img/re/right_gr.png" srcset="/img/re/right_gr@2x.png 2x,/img/re/right_gr@3x.png 3x"></a></span>
        </div>
    </div>
<? endif ?>

<div class="mo_hard_line"></div>
<div class=" mypage_nav_contents_wapper">
    <!-- member.dashboard.php end -->
    <div id="nav-member-wrapper" class="on-big">
        <!-- <div id="nav-member-member-info">
            <div style="font-size: 24px; font-weight: 800; margin-bottom: 8px;"><a href="/member/dashboard.php">MY PAGE</a></div>
            <div style="font-size: 26px; font-weight: 900;"><?= $member['mb_name'] ?>님</div>
            <div><a href="/member/pick.php">MY PICK(<?= number_format($cnt_pick['CNT']) ?>)</a></div>
            <div><a href="/member/coupon.php">COUPON(<?= number_format($cp_count) ?>)</a></div>
            <div><a href="/member/point.php">POINT(<?= number_format($member['mb_point']) ?>)</a></div>
        </div> -->
        <div class="nav-member-sub-wrapper">
            <div class="nav-member-sub-title">주문관리</div>
            <div><a href="/member/order.php">주문/배송조회</a></div>
            <!-- <div><a href="/member/order.cancel.php">취소/반품/교환 조회</a></div> -->
            <div><a href="/member/order.cancel.php">취소/반품조회</a></div>
            <!-- <div><a href="/member/review.php">상품리뷰</a></div> -->
        </div>
        <div class="nav-member-sub-wrapper">
            <div class="nav-member-sub-title">나의 혜택</div>
            <div><a href="/member/coupon.php">쿠폰 조회</a></div>
            <div><a href="/member/point.php">포인트 조회</a></div>
            <div><a href="/member/rating.php">회원등급혜택</a></div>
        </div>
        <div class="nav-member-sub-wrapper">
            <div class="nav-member-sub-title">나의 활동</div>
            <div><a href="/member/pick.php">위시리스트</a></div>
            <div><a href="/member/review.php">상품리뷰</a></div>
            <div><a href="/member/customer.php">1:1문의</a></div>
            <div><a href="/member/qna.php">Q&A</a></div>
        </div>
        <div class="nav-member-sub-wrapper">
            <div class="nav-member-sub-title">회원정보</div>
            <div><a href="/member/info.php">회원정보수정</a></div>
            <div><a href="/member/withdraw.php">회원탈퇴</a></div>
            <!-- <div><a href="/member/faq.php">FAQ</a></div>
            <div><a href="/member/customer.php">1:1 문의</a></div>
            <div><a href="/member/qna.php">상품 Q&A 내역</a></div>
            <div><a href="/member/notice.php">공지사항</a></div> -->
        </div>
    </div>
    <?= G5_POSTCODE_JS ?>