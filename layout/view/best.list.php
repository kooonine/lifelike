<?php
ob_start();

?>
<link rel="stylesheet" href="/re/css/event.css">

<div class="on-big" style="float:left; margin-top: 60px;"></div>

<?php  
       $best_id =   $_GET["bs_ca"];

?>

<div class="best_title on-big" style = "margin-left: -7%;">주간베스트</div>
<ul class="best_category best_list on-big" style="width: 35%; margin-left: 34%;">
    <li class="best_category_li best_list"><a class="<?= ($best_id == '00') ? 'active':'' ?>" href="/best/list.php?bs_ca=00">전체</a></li>
<?php $mi = 0; foreach ($g5_menu as $di => $dm) : ?>
    <?php $gubun =  ($mi+1).'0'; ?>
    <?php if ($dm['me_code'] == 10 || $dm['me_code'] == 20 || $dm['me_code'] == 30 || $dm['me_code'] == 40) : ?>
        <li class="best_category_li best_list"><a class="best_category_name  <?= ($gubun == $best_id) ? 'active':'' ?>" href="/best/list.php?bs_ca=<?=$mi + 1?>0"><?=$dm['me_name']?></a>
            
        </li>
    <?php endif; $mi++; ?>
<?php endforeach; ?>
</ul>

<div class="best_title on-small">주간베스트</div>
<ul class="best_category best_list on-small">
    <li class="best_category_li best_list"><a class="<?= ($best_id == '00') ? 'active':'' ?>" href="/best/list.php?bs_ca=00">전체</a></li>
<?php $mi = 0; foreach ($g5_menu as $di => $dm) : ?>
    <?php $gubun =  ($mi+1).'0'; ?>
    <?php if ($dm['me_code'] == 10 || $dm['me_code'] == 20 || $dm['me_code'] == 30 || $dm['me_code'] == 40) : ?>
        <li class="best_category_li best_list"><a class="best_category_name  <?= ($gubun == $best_id) ? 'active':'' ?>" href="/best/list.php?bs_ca=<?=$mi + 1?>0"><?=$dm['me_name']?></a>
            
        </li>
    <?php endif; $mi++; ?>
<?php endforeach; ?>
</ul>

<div id="event-wrapper">
    <? /* ?>
    <div style="margin: 40px 0 16px 0;">
        <a href="/event/list.php?pick=true&page=1"><button type="button" class="btn btn-black" style="font-size: 12px; margin-top: unset;">YOU PICK</button></a>
    </div>
    <?*/ ?>
    <div id="best-list-wrapper">
        <? for ($ci = 0; $citem = sql_fetch_array($db_event); $ci++) : ?>
            
            <div class="best_item_list  <?= $ci < 6 ? 'item_list_3tab' :  'item_list_4tab' ?>" id = "best_item_list_id">
            <a href="/shop/item.php?it_id=<?= $citem['it_id'] ?>">
                <?php
                // $badge = new badge($row);
                // $badgeHtml = $badge->makeHtml();
                // $thumb = get_it_image_path($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name']));
                $discount_ratio = 0;
                if ($citem['it_discount_price'] != '' && $citem['it_discount_price'] != '0') {
                    $it_price = $citem['it_price'];
                    $it_sale_price = $citem['it_discount_price'];
                    $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                }
                $thumb = get_it_thumbnail_path($citem['it_img1'], 600, 600);

                $totalBest001 = "SELECT io_order_no FROM {$g5['g5_shop_item_option_table']} WHERE it_id = '{$citem['it_id']}' LIMIT 1";
                $totalBest001_1= sql_fetch($totalBest001); 
                $totalBest002 = "SELECT * FROM {$g5['g5_shop_item_table']} a LEFT JOIN {$g5['g5_shop_item_option_table']} b ON (a.it_id = b.it_id) WHERE b.io_order_no = '{$totalBest001_1['io_order_no']}' AND it_use = 1  ORDER BY it_price ASC, it_size_info DESC";
                $totalBest002_2 = sql_query($totalBest002); 

                
                ?>
                <div class="best_item_img" data-id=<?= $citem['it_id'] ?> style="background-image: url(<?= $thumb ?>); background-size: contain;"></div>
                <span class="rank <?= $ci > 9 ? 'rank_10by' :  '' ?>"> <?if($ci < 3):?><img class="crown on-big" style="position: absolute; top: 7px; left: 22px;" src="/img/re/crown.png"  srcset="/img/re/crown@2x.png 2x,/img/re/crown@3x.png 3x"><?endif?> <?= $ci + 1?></span>
                <span class="btn-pick-heart <?= in_array($citem['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $citem['it_id'] ?>></span>
                
                <div class="swiper_item_detail">
                    <div class="swiper_item_brand"><?= $citem['it_brand'] ?> 
                <? for ($tbs = 0; $tbR = sql_fetch_array($totalBest002_2); $tbs++) : 
                    if ($tbR['it_soldout'] == 1 || $tbR['io_stock_qty'] < 1 ) { ?>
                        <span class ='hocOutName<?= $tbR['io_hoching'] ?>'></span>
                    <? } else {?>
                        <span class ='hocName<?= $tbR['io_hoching'] ?>'></span>
                    <? }
                    ?>
                <? endfor; 
                    $oneSize = '원 ~';
                    if ($tbs == 1) $oneSize = '원'
                ?>
                    </div>
                    <div class="swiper_item_name"><?= $citem['it_name'] ?></div>
                    <div class="swiper_item_price_area">
                        <span><?= display_price(get_price($citem), $citem['it_tel_inq']); ?><span style="font-size: 12px;"><?= $oneSize; ?></span>
                        <? if ($discount_ratio > 0) : ?>
                            <span class="price-del"><del><?= number_format($it_price + $it_sale_price) ?></del>원<span>
                            <span class="price-dis" style="color: #e65026;"><?= number_format($discount_ratio) ?>%</span>
                        <? endif ?>
                    </div>
                    <?php
                    $it_view_list_items = ','.$citem['it_view_list_items'].',';
                    $view_sale = (preg_match("/,할인블릿,/i", $it_view_list_items));
                    ?>
                    <div class="swiper_item_sale">
                        <img src="/img/re/sale.png" style="opacity : <?= $view_sale ?>" srcset="/img/re/sale@2x.png 2x,/img/re/sale@3x.png 3x">
                    </div>
                </div>
            </a>
            </div>
        <? endfor ?>
    </div>
    <div id="add_list"></div>
    <!-- <div class="on-small add_item_btn"><a onclick="addList(<?=$total_page?>)">더보기</a></div> -->
    <? if ($paging) : ?>
        <div class="on-big" style="margin-bottom: 170px;"><?= $paging ?></div>
    <? endif ?>
</div>

<script>
    let add_page = 2;
    function addList(totalPage){
        
        $.ajax({
            url:'/event/ajax.list.php',
            type:'post',
            data:{page : add_page},
            
            success:function(response){
                $('#add_list').append(response);
                add_page++;
            }
        });
    
    }
    $(document).ready(function() {
        if ($("#main-banner-wrapper").length > 0) {
            const sliderMain = tns({
                container: '#main-banner-wrapper',
                controls: false,
                nav: false,
                autoplayButtonOutput: false,
                autoplay: true,
                speed: 400,
                items: 1
            });
        }
    });
</script>
<?php
$contents = ob_get_contents();
ob_end_clean();

return $contents;
?>