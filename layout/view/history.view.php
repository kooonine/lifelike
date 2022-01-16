<?php
ob_start();
?>
<link rel="stylesheet" href="/re/css/event.css">
<style>
    @media (max-width: 1366px) {
        .product-list {
            width: calc(100vw - 20px);
            margin-left: unset;
            min-width: unset !important;
        }
        .history_items{
            height : 90px;
            position : relative;
            clear: both;
            margin: 20px 14px;
            border-bottom : 5px solid #e0e0e0;
        }
        .line-gubun {
            background-color : #e0e0e0;
            height : 5px;
        }

        .history_thumbnail_img{
            width: 75px;
            height: 75px;
            float: left;
            margin-right : 5px;
        }
        .history_thumbnail_imgbox{
            width: 75px;
            height: 75px;
            display: block;
            background-size: cover;
        }

        .history-content-remove {display : block !important;right: 4px;}
        .empty_item{
            height : 200px;
            text-align : center;
            line-height : 200px;
        }
        .empty_btn{
            width : calc(100vw - 24px);
            height : 44px;
            margin-left: 14px;
            color : #ffffff;
            background-color : #333333;
            line-height : 44px;
            text-align : center;
        }
    }

    .search-no-result {
        font-size: 14px;
        color: #606060;
        font-weight: 500;
        text-align: center;
        margin-top: 80px;
        margin-bottom: 200px;
        /* margin-right: -30px; */
    }

    .search-no-result>a {
        color: #f14e00;
        font-weight: 500;
        line-height: 32px;
    }
</style>

<div class="">
    <div class="">
        <?php if ($g5_user_history_mo['COUNT'] > 0) : ?>
            <?php foreach ($g5_user_history_mo['ITEMS'] as $mhis) : ?>
                <?php if ($mhis['hi_type'] == 'item') : ?>
                    <?php
                    $mhi_thumb = get_it_thumbnail_path($mhis['item']['it_img1'], 300, 300);
                    $it_price = $mhis['item']['it_price'];
                    $it_sale_price = $mhis['item']['it_discount_price'];
                    $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                    ?>
                    <a href="/shop/item.php?it_id=<?= $mhis['item']['it_id'] ?>">
                        <div class="history_items">
                            <div class="history_thumbnail_img">
                                <span class="history_thumbnail_imgbox" style="background-image: url(<?= $mhi_thumb ?>);"></span>
                            </div>
                            <div>
                                <div class="tab2_mo_item_brand"><?= $mhis['item']['it_brand'] ?> <img src="/img/re/size_lable/<?= replace_hoching($mhis['item']['io_hoching']) ?>.png" srcset="/img/re/size_lable/<?= replace_hoching($mhis['item']['io_hoching']) ?>@2x.png 2x,/img/re/size_lable/<?= replace_hoching($mhis['item']['io_hoching']) ?>@3x.png 3x"></div>
                                <div class="tab2_mo_item_name"><?= $mhis['item']['it_name'] ?></div>
                                <div class="tab2_mo_item_price_area">
                                    <span><?= display_price(get_price($mhis['item']), $mhis['item']['it_tel_inq']); ?><span style="font-size: 12px;">원</span>
                                </div>
                                <div class="tab2_mo_item_sale_price_area">
                                    <? if ($discount_ratio > 0) : ?>
                                        <span class="price-del"><del><?= number_format($it_price + $it_sale_price) ?></del>원<span>
                                        <span class="price-dis" style="color: #e65026;"><?= number_format($discount_ratio) ?>%</span>
                                    <? endif ?>
                                </div>
                            </div>
                            <span class="history-content-remove"  data-type="<?= $mhis['hi_type'] ?>" data-id="<?= $mhis['it_id'] ?>"><img src="/img/re/x.png" srcset="/img/re/x@2x.png 2x,/img/re/x@3x.png 3x"></span>
                        </div>
                        
                    </a>
                <?php endif ?>
            <?php endforeach ?>
        <?php else : ?>
            <div class="empty_item">
                최근 본 상품 내역이 없습니다.
            </div>
            <div class="empty_btn">
                계속 쇼핑하기
            </div>
        <?php endif ?>


    </div>
</div>


<script>
   
</script>
<?php
$contents = ob_get_contents();
ob_end_clean();

return $contents;
?>;