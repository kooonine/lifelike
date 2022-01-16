<?php
ob_start();
$g5_title = 'MY PICK';
include_once G5_LAYOUT_PATH . '/nav.member.php';
?>
<link rel="stylesheet" href="/re/css/event.css">
<style>
    #member-brand-list-wrapper {
        display: grid;
        grid-template-columns: repeat(2, calc((100% - 20px) / 2));
        grid-template-rows: 280px;
        grid-auto-columns: calc((100% - 20px) / 2);
        grid-auto-rows: 280px;
        grid-column-gap: 20px;
        grid-row-gap: 20px;
        grid-auto-flow: row;
    }

    #member-brand-list-wrapper>.brand-list-cell {
        width: 580px;
        height: 280px;
        float: left;
        cursor: pointer;
        padding: 10px;
        text-align: right;
        margin: 0 20px 20px 0;
    }


    #member-product-list-wrapper {
        display: grid;
        grid-template-columns: repeat(4, calc((100% - 60px) / 4));
        grid-template-rows: 420px;
        grid-auto-columns: calc((100% - 60px) / 4);
        grid-auto-rows: 420px;
        grid-column-gap: 20px;
        grid-row-gap: 20px;
        grid-auto-flow: row;
    }

    #member-product-list-wrapper>.product-list-cell {
        cursor: pointer;
        width: 300px;
        height: 420px;
        padding-right: 20px;
        float: left;
    }

    #member-product-list-wrapper>.product-list-cell>a>.product-list-item-thumb {
        width: 100%;
        height: 0;
        padding-top: 100%;
    }

    #pick-tab-wrapper {
        width: 100%;
        margin-bottom: 20px;
    }

    #pick-tab-wrapper>a {
        width: 50%;
        font-size: 16px;
        font-weight: 500;
        color: #a3a3a3;
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
        border-bottom: 1px solid #333333;
        display: inline-block;
        text-align: center;
        line-height: 48px;
    }

    #pick-tab-wrapper>a.active {
        color: #333333;
        border-color: #333333;
        border-bottom-width: 0;
    }

    @media (max-width: 1366px) {
        #offset-nav-top {
            height: 44px;
        }

        .mo_hard_line,
        .nav-samll-member-top {
            display: none;
        }

        .product-list {
            padding-left: unset !important;
        }

        .banner-brand-info {
            width: 100%;
            padding: unset;
        }

        .product-list {
            margin-left: unset;
        }

        .product-list li.product-list-item-wrapper:nth-child(odd) {
            margin-right: 14px;
        }

        .product-list .product-list-item-wrapper {
            width: calc((100% - 14px) / 2);
        }

        .product-list .product-list-item-thumb {
            width: 100%;
            height: calc(50vw - 28px);
        }

        #member-brand-list-wrapper {
            padding: 0;
            grid-template-columns: repeat(1, 100%);
            grid-template-rows: 280px;
            grid-auto-columns: 100%;
            grid-auto-rows: 280px;
            grid-column-gap: 20px;
            grid-row-gap: 20px;
            grid-auto-flow: row;
        }

        #member-product-list-wrapper {
            padding: 0 14px;
            grid-template-columns: repeat(2, calc((100% - 14px) / 2));
            grid-template-rows: 75vw;
            grid-auto-columns: calc((100% - 14px) / 2);
            grid-auto-rows: 75vw;
            grid-column-gap: 14px;
            grid-row-gap: 14px;
            grid-auto-flow: row;
        }

        #member-product-list-wrapper>.product-list-cell,
        #member-brand-list-wrapper>.brand-list-cell {
            width: 100%;
            padding-right: unset;
            float: inherit;
        }

        #member-brand-list-wrapper>.brand-list-cell {
            padding: 10px;
        }

        .product-list-item-brand {
            font-size: 10px;
        }

        .product-list-item-name {
            font-size: 12px;
        }

        .product-list-item-saleprice {
            font-size: 12px;
            line-height: 14px;
        }

        .product-list-item-saleprice>.price-tag,
        .product-list-item-saleprice>.price-dis {
            font-size: 10px;
        }
    }
</style>
<div id="member-content-wrapper">
    <div class="member-content-title on-big">위시리스트</div>
    <div id="pick-tab-wrapper" style="font-size: 0;">
        <a class="<?= $type == 'item' ? 'active' : '' ?>" href="/member/pick.php?type=item">상품</a>
        <a class="<?= $type == 'brand' ? 'active' : '' ?>" href="/member/pick.php?type=brand">브랜드</a>
    </div>

    <? if ($type == 'brand') : ?>
        <?php if ($db_picked->num_rows > 0) : ?>
            <div id="member-brand-list-wrapper">
                <? for ($i = 0; $pick = sql_fetch_array($db_picked); $i++) : ?>
                    <?
                    $sql_row = "SELECT * FROM lt_brand WHERE br_use=1 AND br_id='{$pick['it_id']}'";
                    $brand = sql_fetch($sql_row);
                    ?>
                    <div class="brand-list-cell" style="background-image: url(/data/brand/<?= $brand['br_main_image'] ?>); <?= ($i + 1) % 2 == 0 ? "margin-right: 0;" : "" ?>" onclick="location.href='/shop/brand.php?br_id=<?= $brand['br_id'] ?>'">
                        <span class="btn-pick-heart picked" style="display: inline-block; position: relative; float: unset; top: unset; right: unset;" data-type="brand" data-pick=<?= $brand['br_id'] ?>></span>
                        <?php if (G5_IS_IE) : ?>
                            <div class="brand-list-logo-wrapper" style="background-color: transparent;">
                                <img src="/data/brand/<?= $brand['br_logo'] ?>">
                            </div>
                        <? else : ?>
                            <div class="brand-list-logo-wrapper" style="margin-top: 70px; -webkit-mask-image: url(/data/brand/<?= $brand['br_logo'] ?>);"></div>
                        <? endif ?>
                    </div>
                <? endfor; ?>
            </div>
        <? else : ?>
            <div class="notice-more-pick">
                <div>위시리스트에 담긴 브랜드가 없습니다.</div>
            </div>
        <? endif ?>
    <? else : ?>
        <?php if ($db_picked->num_rows > 0) : ?>
            <div id="member-product-list-wrapper">
                <? for ($i = 0; $pick = sql_fetch_array($db_picked); $i++) : ?>
                    <?
                    $sql_row = "SELECT * FROM lt_shop_item WHERE it_use=1 AND it_id='{$pick['it_id']}'";
                    $row = sql_fetch($sql_row);
                    $badge = new badge($row);
                    $badgeHtml = $badge->makeHtml();
                    $thumb = get_it_image_path($row['it_id'], 500, 500, '', '', stripslashes($row['it_name']));
                    ?>
                    <div class="product-list-cell" style="<?= ($i + 1) % 4 == 0 ? "width: 280px; padding-right: 0;" : "" ?>">
                        <a href="/shop/item.php?it_id=<?= $row['it_id']; ?>">
                            <div class="product-list-item-thumb" data-image="<?= $thumb ?>" style="background-image: url(<?= $thumb ?>);">
                                <span class="btn-pick-heart picked" data-type="item" data-pick=<?= $row['it_id'] ?>></span>
                            </div>
                            <div class="product-list-item-info" style="margin-left: 0;">
                                <div class=" product-list-item-brand">
                                    <?php echo empty($row['it_brand']) ? "LIFELIKE" : $row['it_brand'] ?>
                                </div>
                                <div class="product-list-item-name">
                                    <?= stripslashes($row['it_name']) ?>
                                </div>
                                <div class="product-list-item-saleprice">
                                    <?= display_price(get_price($row), $row['it_tel_inq']) ?><span>원</span>
                                    <?php
                                    if (!empty($row['it_discount_price'])) {
                                        $it_price = $row['it_price'];
                                        $it_sale_price = $row['it_discount_price'];
                                        $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                                    ?>
                                        <br class="on-small">
                                        <span class="price-tag"><del><?= number_format($it_price + $it_sale_price) ?></del><span>원</span></span>
                                        <span class="price-dis">(<?= number_format($discount_ratio) ?>%)</span>
                                    <?
                                    }
                                    ?>
                                </div>
                                <div class="product-list-item-label"><?= $badgeHtml->html ?></div>
                            </div>
                        </a>
                    </div>
                <? endfor; ?>
            </div>
        <? else : ?>
            <div class="notice-more-pick">
                <div>위시리스트에 담긴 상품이 없습니다.</div>
            </div>
        <? endif; ?>
    <? endif; ?>
    <? if ($paging) : ?>
    <div style="margin-bottom: 170px;"><?= $paging ?></div>
    <? endif; ?>
</div>


<?php
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>