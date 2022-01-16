<?php

$modalSql = "SELECT * FROM lt_shop_item WHERE lt_order_no = '{$it['lt_order_no']}' AND it_use = 1  ORDER BY it_price ASC, it_size_info DESC ";
$totalRes = sql_query($modalSql);
$item_priceA = array();
$item_sale_priceA = array();
$item_cost_priceA = array();
$item_discountPreA = array();
$prod_coupon_ableA = array();
$brnad_coupon_ableA = array();
$plus_coupon_ableA = array();
$it_idA = array(); 

for($itr=0; $tr=sql_fetch_array($totalRes); $itr++) { 
    // dd($itr);
    $item_priceA[$itr] = $tr['it_price'];

    $item_sale_priceA[$itr] = $tr['it_discount_price'];
    $item_cost_priceA[$itr] = ($tr['it_price'] + $tr['it_discount_price']);
    $item_discountPreA[$itr] = ($item_sale_priceA[$itr] / $item_cost_priceA[$itr]) * 100;

    $prod_coupon_ableA[$itr] = 0;
    $brnad_coupon_ableA[$itr] = 0;
    $plus_coupon_ableA[$itr] = 0;

    $it_idA[$itr] = $tr['it_id'];
    // echo $item_cost_price[$itr].'<br>';
    // dd(  $it_id[$itr]);
}
// pc 17 mobile 11 margin-left
?>


<div class="modal fade modal-product-detail" id="modal-max-sale0" tabindex="-1" role="dialog" aria-labelledby="btn-modal-max-sale" aria-hidden="true" style="max-width: unset; min-width: unset;">
    <div class="modal-dialog" role="document">
        <div id="modal-max-sale-content" class="modal-product-detail-content modal-content" style="height: auto;">
            <div class="modal_header">최대혜택가
                <img src="/img/re/cancle.png" srcset="/img/re/cancle@2x.png 2x,/img/re/cancle@3x.png 3x" onclick="openOrder()" data-dismiss="modal">
            </div>
            <div style="margin-top: 17px; margin-left: 11px">
                <?php
                $check = 0; 
                for($is2=0; $sd2=sql_fetch_array($sizeData2); $is2++) { 
                    if ($sd2['it_soldout'] == 1 || $sd2['io_stock_qty'] < 1 ) { ?>
                         <input type="radio" id ="<?= $sd2['io_hoching']?>" disabled><label for="<?= $sd2['io_hoching']?>" style="background: #eee; color: #757575; border-color: #ccc; text-decoration: line-through;" disabled><?= $sd2['io_hoching'] ?></label>

                   <?php } else {
                            $check += 1;
                            if (is_numeric(substr($sd2['io_hoching'],0,1)))  $raId = 'n'.str_replace('*','',$sd2['io_hoching']); else $raId = $sd2['io_hoching'];
                          ?>
                        <input type="radio" id ="max<?= $raId?>" class="salePrice" name="sizePickOpt" data-num="<?= $is2 ?>" <?php if($check==1) echo 'checked' ?> ><label for="max<?= $raId?>"><?= $sd2['io_hoching'] ?></label>
                <?php } }
                ?>
            </div>
            <div class="modal_body" id="modal-max-sale-body0">
                <?php
                $item_price = $item_priceA[0];
                $item_sale_price = $item_sale_priceA[0];
                $item_cost_price = $item_cost_priceA[0];
                $item_discountPre = $item_discountPreA[0];

                $prod_coupon_able = 0;
                $brnad_coupon_able = 0;
                $plus_coupon_able = 0;

                $libCoupon->setItem($it_idA[0]);
                $coupons = $libCoupon->getCouponList($member['mb_id']);

                ?>

                <div class="heard_text_big"><span class="heard_text big">정상가<?php echo $itId ?></span><span class="contents_text big"><?= number_format($item_cost_price) ?>원</span></div>

                <div><span class="heard_text">즉시할인 <?= number_format($item_discountPre) ?>%</span><span class="contents_text">-<?= number_format($item_sale_price) ?>원</span></div>
                <?php $total_sale_price = $item_cost_price - $item_sale_price; ?>
                <?php foreach ($coupons as $cpi => $cpiv) : ?>
                    <? if ($cpiv['cp_method'] == 0 && $prod_coupon_able == 0) : ?>
                        <? if ($total_sale_price  >  $cpiv['cp_minimum']) :
                            $coupon_sale_prod = $libCoupon->calcDiscountPrice($total_sale_price, $cpiv['cp_price'], $cpiv['cp_type'], $cpiv['cp_trunc'], $cpiv['cp_maximum']);
                            $total_sale_price = $total_sale_price - $coupon_sale_prod;
                            $prod_coupon_able = 1;
                        ?>
                            <?
                                $bol = '%';
                                if ($cpiv['cp_type'] ==0) :
                                    $bol = '원';
                            ?>
                            <? endif ?>
                            <div><span class="heard_text">상품쿠폰 <?= $cpiv['cp_price'] ?><?= $bol ?></span><span class="contents_text">-<?= number_format($coupon_sale_prod) ?>원</span></div>
                        <? endif ?>
                    <? endif ?>
                    <? if ($cpiv['cp_method'] == 4 && $brnad_coupon_able == 0) : ?>
                        <? if ($total_sale_price  >  $cpiv['cp_minimum']) :
                            $coupon_sale_brand = $libCoupon->calcDiscountPrice($total_sale_price, $cpiv['cp_price'], $cpiv['cp_type'], $cpiv['cp_trunc'], $cpiv['cp_maximum']);
                            $total_sale_price  = $total_sale_price - $coupon_sale_brand;
                            $brnad_coupon_able = 1;
                        ?>
                            <div><span class="heard_text">브랜드쿠폰 <?= $cpiv['cp_price'] ?><?= $bol ?></span><span class="contents_text">-<?= number_format($coupon_sale_brand) ?>원</span></div>
                        <? endif ?>
                    <? endif ?>
                    <? if ($cpiv['cp_method'] == 11 && $plus_coupon_able == 0) : ?>
                        <? if ($total_sale_price  >  $cpiv['cp_minimum']) :
                            $coupon_sale_plus = $libCoupon->calcDiscountPrice($total_sale_price, $cpiv['cp_price'], $cpiv['cp_type'], $cpiv['cp_trunc'], $cpiv['cp_maximum']);
                            $total_sale_price  = $total_sale_price - $coupon_sale_plus;
                            $plus_coupon_able = 1;
                        ?>
                            <div><span class="heard_text">[플러스]상품쿠폰 <?= $cpiv['cp_price'] ?><?= $bol ?></span><span class="contents_text">-<?= number_format($coupon_sale_plus) ?>원</span></div>
                        <? endif ?>
                    <? endif ?>
                <? endforeach ?>

                <?php
                if (!empty($member['mb_id'])) {

                    $cp_end_ymd = G5_TIME_YMD;
                    $sql_jangba_coupon = "SELECT * FROM lt_shop_coupon WHERE mb_id = '{$member['mb_id']}' AND cp_method = '2' AND od_id = 0 AND cp_start <= NOW() AND cp_end >= '$cp_end_ymd'  ORDER BY cp_price DESC, cp_maximum DESC  ";
                    $db_jangba_coupon = sql_query($sql_jangba_coupon);
                    $db_jangba_coupon_date = sql_fetch($sql_jangba_coupon);
                }
                ?>
                <?php if (count($db_jangba_coupon_date) > 0) : ?>
                    <?php while (($jangbagu = sql_fetch_array($db_jangba_coupon)) != false) {
                        if ($total_sale_price > $jangbagu['cp_minimum']) {
                            $jangbagu_pre = $jangbagu['cp_price'];
                            $coupon_sale_jangba = $libCoupon->calcDiscountPrice($total_sale_price, $jangbagu['cp_price'], $jangbagu['cp_type'], $jangbagu['cp_trunc'], $jangbagu['cp_maximum']);
                            $total_sale_price  = $total_sale_price - $coupon_sale_jangba;
                            $bol2 = '%';
                            if ($jangbagu['cp_type'] ==0) {
                                $bol2 = '원';
                            }
                            break;
                        }
                    }
                    ?>
                    <? if($coupon_sale_jangba > 0 ) : ?>
                    <div><span class="heard_text">장바구니쿠폰 <?= $jangbagu_pre ?><?= $bol2 ?></span><span class="contents_text">-<?= number_format($coupon_sale_jangba) ?>원</span></div>
                    <? endif?>
                <? endif ?>

                <?php
                $sale_my_point = 0;
                if ($member['mb_point'] >= 1000) {
                    $sale_my_point = $member['mb_point'];
                    if ($total_sale_price < $member['mb_point']) {
                        $sale_my_point = $total_sale_price;
                    }
                }

                $total_sale_price = $total_sale_price - $sale_my_point;
                ?>

                <div><span class="heard_text">포인트(<?= number_format($member['mb_point']) ?>P)</span><span class="contents_text">-<?= number_format($sale_my_point) ?>원</span></div>
                <div class="etc">1,000P 이상 보유 시, 사용가능</div>
                <div class="heard_text_red"><span class="heard_text red">최대혜택가</span><span class="contents_text red"><?= number_format($total_sale_price) ?> 원</span></div>
                <input type="hidden" value="<?= $total_sale_price ?>" id="total_max_sale_price">
            </div>

            <div class="modal_body" id="modal-max-sale-body1" style="display: none;">
                <?php
                $item_price = $item_priceA[1];
                $item_sale_price = $item_sale_priceA[1];
                $item_cost_price = $item_cost_priceA[1];
                $item_discountPre = $item_discountPreA[1];

                $prod_coupon_able = 0;
                $brnad_coupon_able = 0;
                $plus_coupon_able = 0;

                $libCoupon->setItem($it_idA[1]);
                $coupons = $libCoupon->getCouponList($member['mb_id']);

                ?>
                <div class="heard_text_big"><span class="heard_text big">정상가</span><span class="contents_text big"><?= number_format($item_cost_price) ?>원</span></div>

                <div><span class="heard_text">즉시할인 <?= number_format($item_discountPre) ?>%</span><span class="contents_text">-<?= number_format($item_sale_price) ?>원</span></div>
                <?php $total_sale_price = $item_cost_price - $item_sale_price; ?>
                <?php foreach ($coupons as $cpi => $cpiv) : ?>
                    <? if ($cpiv['cp_method'] == 0 && $prod_coupon_able == 0) : ?>
                        <? if ($total_sale_price  >  $cpiv['cp_minimum']) :
                            $coupon_sale_prod = $libCoupon->calcDiscountPrice($total_sale_price, $cpiv['cp_price'], $cpiv['cp_type'], $cpiv['cp_trunc'], $cpiv['cp_maximum']);
                            $total_sale_price = $total_sale_price - $coupon_sale_prod;
                            $prod_coupon_able = 1;
                        ?>
                            <?
                                $bol = '%';
                                if ($cpiv['cp_type'] ==0) :
                                    $bol = '원';
                            ?>
                            <? endif ?>
                            <div><span class="heard_text">상품쿠폰 <?= $cpiv['cp_price'] ?><?= $bol ?></span><span class="contents_text">-<?= number_format($coupon_sale_prod) ?>원</span></div>
                        <? endif ?>
                    <? endif ?>
                    <? if ($cpiv['cp_method'] == 4 && $brnad_coupon_able == 0) : ?>
                        <? if ($total_sale_price  >  $cpiv['cp_minimum']) :
                            $coupon_sale_brand = $libCoupon->calcDiscountPrice($total_sale_price, $cpiv['cp_price'], $cpiv['cp_type'], $cpiv['cp_trunc'], $cpiv['cp_maximum']);
                            $total_sale_price  = $total_sale_price - $coupon_sale_brand;
                            $brnad_coupon_able = 1;
                        ?>
                            <div><span class="heard_text">브랜드쿠폰 <?= $cpiv['cp_price'] ?><?= $bol ?></span><span class="contents_text">-<?= number_format($coupon_sale_brand) ?>원</span></div>
                        <? endif ?>
                    <? endif ?>
                    <? if ($cpiv['cp_method'] == 11 && $plus_coupon_able == 0) : ?>
                        <? if ($total_sale_price  >  $cpiv['cp_minimum']) :
                            $coupon_sale_plus = $libCoupon->calcDiscountPrice($total_sale_price, $cpiv['cp_price'], $cpiv['cp_type'], $cpiv['cp_trunc'], $cpiv['cp_maximum']);
                            $total_sale_price  = $total_sale_price - $coupon_sale_plus;
                            $plus_coupon_able = 1;
                        ?>
                            <div><span class="heard_text">[플러스]상품쿠폰 <?= $cpiv['cp_price'] ?><?= $bol ?></span><span class="contents_text">-<?= number_format($coupon_sale_plus) ?>원</span></div>
                        <? endif ?>
                    <? endif ?>
                <? endforeach ?>

                <?php
                if (!empty($member['mb_id'])) {

                    $cp_end_ymd = G5_TIME_YMD;
                    $sql_jangba_coupon = "SELECT * FROM lt_shop_coupon WHERE mb_id = '{$member['mb_id']}' AND cp_method = '2' AND od_id = 0 AND cp_start <= NOW() AND cp_end >= '$cp_end_ymd'  ORDER BY cp_price DESC, cp_maximum DESC  ";
                    $db_jangba_coupon = sql_query($sql_jangba_coupon);
                    $db_jangba_coupon_date = sql_fetch($sql_jangba_coupon);
                }
                ?>
                <?php if (count($db_jangba_coupon_date) > 0) : ?>
                    <?php while (($jangbagu = sql_fetch_array($db_jangba_coupon)) != false) {
                        if ($total_sale_price > $jangbagu['cp_minimum']) {
                            $jangbagu_pre = $jangbagu['cp_price'];
                            $coupon_sale_jangba = $libCoupon->calcDiscountPrice($total_sale_price, $jangbagu['cp_price'], $jangbagu['cp_type'], $jangbagu['cp_trunc'], $jangbagu['cp_maximum']);
                            $total_sale_price  = $total_sale_price - $coupon_sale_jangba;
                            $bol2 = '%';
                            if ($jangbagu['cp_type'] ==0) {
                                $bol2 = '원';
                            }
                            break;
                        }
                    }
                    ?>
                    <? if($coupon_sale_jangba > 0 ) : ?>
                    <div><span class="heard_text">장바구니쿠폰 <?= $jangbagu_pre ?><?= $bol2 ?></span><span class="contents_text">-<?= number_format($coupon_sale_jangba) ?>원</span></div>
                    <? endif?>
                <? endif ?>

                <?php
                $sale_my_point = 0;
                if ($member['mb_point'] >= 1000) {
                    $sale_my_point = $member['mb_point'];
                    if ($total_sale_price < $member['mb_point']) {
                        $sale_my_point = $total_sale_price;
                    }
                }

                $total_sale_price = $total_sale_price - $sale_my_point;
                ?>

                <div><span class="heard_text">포인트(<?= number_format($member['mb_point']) ?>P)</span><span class="contents_text">-<?= number_format($sale_my_point) ?>원</span></div>
                <div class="etc">1,000P 이상 보유 시, 사용가능</div>
                <div class="heard_text_red"><span class="heard_text red">최대혜택가</span><span class="contents_text red"><?= number_format($total_sale_price) ?> 원</span></div>
                <input type="hidden" value="<?= $total_sale_price ?>" id="total_max_sale_price">
            </div>

            <div class="modal_body" id="modal-max-sale-body2" style="display: none;">
                <?php
                $item_price = $item_priceA[2];
                $item_sale_price = $item_sale_priceA[2];
                $item_cost_price = $item_cost_priceA[2];
                $item_discountPre = $item_discountPreA[2];

                $prod_coupon_able = 0;
                $brnad_coupon_able = 0;
                $plus_coupon_able = 0;

                $libCoupon->setItem($it_idA[2]);
                $coupons = $libCoupon->getCouponList($member['mb_id']);

                ?>
                <div class="heard_text_big"><span class="heard_text big">정상가</span><span class="contents_text big"><?= number_format($item_cost_price) ?>원</span></div>

                <div><span class="heard_text">즉시할인 <?= number_format($item_discountPre) ?>%</span><span class="contents_text">-<?= number_format($item_sale_price) ?>원</span></div>
                <?php $total_sale_price = $item_cost_price - $item_sale_price; ?>
                <?php foreach ($coupons as $cpi => $cpiv) : ?>
                    <? if ($cpiv['cp_method'] == 0 && $prod_coupon_able == 0) : ?>
                        <? if ($total_sale_price  >  $cpiv['cp_minimum']) :
                            $coupon_sale_prod = $libCoupon->calcDiscountPrice($total_sale_price, $cpiv['cp_price'], $cpiv['cp_type'], $cpiv['cp_trunc'], $cpiv['cp_maximum']);
                            $total_sale_price = $total_sale_price - $coupon_sale_prod;
                            $prod_coupon_able = 1;
                        ?>
                            <?
                                $bol = '%';
                                if ($cpiv['cp_type'] ==0) :
                                    $bol = '원';
                            ?>
                            <? endif ?>
                            <div><span class="heard_text">상품쿠폰 <?= $cpiv['cp_price'] ?><?= $bol ?></span><span class="contents_text">-<?= number_format($coupon_sale_prod) ?>원</span></div>
                        <? endif ?>
                    <? endif ?>
                    <? if ($cpiv['cp_method'] == 4 && $brnad_coupon_able == 0) : ?>
                        <? if ($total_sale_price  >  $cpiv['cp_minimum']) :
                            $coupon_sale_brand = $libCoupon->calcDiscountPrice($total_sale_price, $cpiv['cp_price'], $cpiv['cp_type'], $cpiv['cp_trunc'], $cpiv['cp_maximum']);
                            $total_sale_price  = $total_sale_price - $coupon_sale_brand;
                            $brnad_coupon_able = 1;
                        ?>
                            <div><span class="heard_text">브랜드쿠폰 <?= $cpiv['cp_price'] ?><?= $bol ?></span><span class="contents_text">-<?= number_format($coupon_sale_brand) ?>원</span></div>
                        <? endif ?>
                    <? endif ?>
                    <? if ($cpiv['cp_method'] == 11 && $plus_coupon_able == 0) : ?>
                        <? if ($total_sale_price  >  $cpiv['cp_minimum']) :
                            $coupon_sale_plus = $libCoupon->calcDiscountPrice($total_sale_price, $cpiv['cp_price'], $cpiv['cp_type'], $cpiv['cp_trunc'], $cpiv['cp_maximum']);
                            $total_sale_price  = $total_sale_price - $coupon_sale_plus;
                            $plus_coupon_able = 1;
                        ?>
                            <div><span class="heard_text">[플러스]상품쿠폰 <?= $cpiv['cp_price'] ?><?= $bol ?></span><span class="contents_text">-<?= number_format($coupon_sale_plus) ?>원</span></div>
                        <? endif ?>
                    <? endif ?>
                <? endforeach ?>

                <?php
                if (!empty($member['mb_id'])) {

                    $cp_end_ymd = G5_TIME_YMD;
                    $sql_jangba_coupon = "SELECT * FROM lt_shop_coupon WHERE mb_id = '{$member['mb_id']}' AND cp_method = '2' AND od_id = 0 AND cp_start <= NOW() AND cp_end >= '$cp_end_ymd'  ORDER BY cp_price DESC, cp_maximum DESC  ";
                    $db_jangba_coupon = sql_query($sql_jangba_coupon);
                    $db_jangba_coupon_date = sql_fetch($sql_jangba_coupon);
                }
                ?>
                <?php if (count($db_jangba_coupon_date) > 0) : ?>
                    <?php while (($jangbagu = sql_fetch_array($db_jangba_coupon)) != false) {
                        if ($total_sale_price > $jangbagu['cp_minimum']) {
                            $jangbagu_pre = $jangbagu['cp_price'];
                            $coupon_sale_jangba = $libCoupon->calcDiscountPrice($total_sale_price, $jangbagu['cp_price'], $jangbagu['cp_type'], $jangbagu['cp_trunc'], $jangbagu['cp_maximum']);
                            $total_sale_price  = $total_sale_price - $coupon_sale_jangba;
                            $bol2 = '%';
                            if ($jangbagu['cp_type'] ==0) {
                                $bol2 = '원';
                            }
                            break;
                        }
                    }
                    ?>
                    <? if($coupon_sale_jangba > 0 ) : ?>
                    <div><span class="heard_text">장바구니쿠폰 <?= $jangbagu_pre ?><?= $bol2 ?></span><span class="contents_text">-<?= number_format($coupon_sale_jangba) ?>원</span></div>
                    <? endif?>
                <? endif ?>

                <?php
                $sale_my_point = 0;
                if ($member['mb_point'] >= 1000) {
                    $sale_my_point = $member['mb_point'];
                    if ($total_sale_price < $member['mb_point']) {
                        $sale_my_point = $total_sale_price;
                    }
                }

                $total_sale_price = $total_sale_price - $sale_my_point;
                ?>

                <div><span class="heard_text">포인트(<?= number_format($member['mb_point']) ?>P)</span><span class="contents_text">-<?= number_format($sale_my_point) ?>원</span></div>
                <div class="etc">1,000P 이상 보유 시, 사용가능</div>
                <div class="heard_text_red"><span class="heard_text red">최대혜택가</span><span class="contents_text red"><?= number_format($total_sale_price) ?> 원</span></div>
                <input type="hidden" value="<?= $total_sale_price ?>" id="total_max_sale_price">
            </div>


            <div class="bold_line"></div>

            <div class="modal_footer">
                <div>- 고객님이 보유한 쿠폰 및 포인트 적용한 최대 할인 가격입니다.</div>
                <div>- 상품 1개 기준으로 적용된 가격입니다.</div>
                <div>- 구매상품 목록에 따라 최대혜택가는 변경될 수 있습니다.</div>
            </div>

        </div>
    </div>
</div>

