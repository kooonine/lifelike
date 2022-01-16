<?php
ob_start();
function MobileCheckSearch() {
    global $HTTP_USER_AGENT;
    $MobileArray  = array("iphone","lgtelecom","skt","mobile","samsung","nokia","blackberry","android","android","sony","phone");

    $checkCount = 0;
    for($i=0; $i<sizeof($MobileArray); $i++){
        if(preg_match("/$MobileArray[$i]/", strtolower($HTTP_USER_AGENT))){ $checkCount++; break; }
    }
    return ($checkCount >= 1) ? "Mobile" : "Computer";
}
$cookieStr = html_entity_decode(preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($_COOKIE['sword'])), null, 'UTF-8');

if (!$cookieStr) {
    $recentViewArr = null;
} else {
    $recentViewArr=explode("\\\\", $cookieStr);
}

if ($skeyword2) $skeyword = $skeyword2;
$swSql = "SELECT sw_value FROM lt_search_word ORDER BY sw_seq ASC";
$searchWordRes = sql_query($swSql);

?>
<link rel="stylesheet" href="/re/css/event.css">
<style>
    @media (max-width: 1366px) {
        .product-list {
            width: calc(100vw - 20px);
            margin-left: unset;
            min-width: unset !important;
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
    .add_search_btn {
        margin: 0 14px;
        height: 44px;
        text-align: center;
        line-height: 44px;
        border-radius: 2px;
        border: 1px solid #333333;
        font-size: 14px;
        font-weight: 500;
        margin-top: 24px;
    }
</style>
<form action="/search.php" method="POST">
    <div id="search-wrapper">
        <div class="on-big" style="text-align: center; margin-top: 220px; margin-bottom: 35px;">
            <img src="/img/re/logo.png" srcset="/img/re/logo@2x.png 2x,/img/re/logo@3x.png 3x">
        </div>
        <div id="search-keyword-main" class="input-group" style="text-align: center;">
            <input type="hidden" name="cp_id" value=50>
            <input autocomplete="off" type="text" class="form-control form-input C1KOBLL" id="input-search-keyword" name="skeyword" placeholder="검색어를 입력하세요." aria-describedby="btn-search-action" value="<?php echo $skeyword ?>" data-value="<?php echo MobileCheckSearch() ?>" onclick="inputClick()">
            <div class="input-group-append" id="btn-search-action">
                <button class="btn" type="button" id="btn-search-clear"><img src="/img/re/x.png" srcset="/img/re/x@2x.png 2x,/img/re/x@3x.png 3x"></button>
                <button class="btn" type="submit" onclick="recentSearchMain()"><img src="/img/re/search.png" srcset="/img/re/search@2x.png 2x,/img/re/search@3x.png 3x"></button>
            </div>
        </div>
        <div class="on-small">
            <div id="searchDivMain" style="background-color: #ffffff; width :100%; height: 481px; position: absolute; left:0px; margin-top:10px; display:none;">
                <table style="width: 100%;">
                    <colgroup>
                        <col style="width:50%;">
        		        <col style="width:50%;">
                    </colgroup>
                    <tr style="text-align: center; height: 40px;font-size: 12px; font-weight: 500; cursor: pointer;">
                        <td class="searchTd2" id='recentTd2' name='recentTd2' onClick="recentTd2()" style="background-color: #ffffff; border-right: 1px solid #979797; border-left: 1px solid #979797; border-top: 1px solid #979797; color: #333333">최근검색어</td>
                        <td class="searchTd2" id='recommendTd2' name='recommendTd2' onClick="recommendTd2()" style="background-color: #f2f2f2; color: #a9a9a9; border-bottom: 1px solid #979797; border-top: solid 1px #f2f2f2; ">추천검색어</td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <?foreach ($recentViewArr as $rva) : ?>
                        <tr class='recentValue' style="height: 35px; text-align: left; font-size: 12px; font-weight: 500; color: #3a3a3a; border-bottom: solid 1px #f2f2f2; cursor: pointer;"> 
                            <td class="searchTd2" onclick="recentClick('<?= $rva?>')"; colspan="2">&nbsp&nbsp&nbsp&nbsp<?= $rva?></td>
                        </tr>
                    <? endforeach ?>
                    <?for ($i=0; $swr=sql_fetch_array($searchWordRes); $i++) : ?>
                        <tr class='recommendValue' style="height: 35px; text-align: left; font-size: 12px; font-weight: 500; color: #3a3a3a; border-bottom: solid 1px #f2f2f2; cursor: pointer; display:none;"> 
                            <td class="searchTd2" onclick="recentClick('<?= $swr['sw_value']?>')" colspan="2">&nbsp&nbsp&nbsp&nbsp<?= $swr['sw_value']?></td>
                        </tr>
                    <? endfor ?>
                </table>
                <div style="background-color: #f2f2f2; height: 40px; width :100%; position: fixed; bottom: 60px; text-align: left; display: flex;">
                    <p class="searchTd2" style="margin-top: 7px; width:345px;">&nbsp&nbsp&nbsp&nbsp<span class="searchTd2" style="color: #9f9f9f; font-size: 12px; text-decoration: underline; cursor: pointer;" id="seachDelSpan2" onclick="searchDelete()">검색기록삭제</span>
                        <span class="searchTd2" style="text-align: right;">
                            <img src="/img/re/x_gr.png" align="right" style="margin-top: 7px;">
                        </span>
                    </p>
                </div>
            </div>

            <!-- ---------------------------------------------------------------------------- -->
            <!-- <div class="modal fade modal-product-detail" id="modal-mobile-order-select" tabindex="-1" role="dialog" aria-labelledby="btn-modal-mobile-order-select" aria-hidden="true" style="max-width: unset; min-width: unset;">
                <div class="modal-dialog" role="document" style="margin:0;">
                    <div id="modal-mobile-order-select-content" class="modal-product-detail-content modal-content" style="height: auto;">
                        <div class="modal_header">TTTTTT
                            <img src="/img/re/cancle.png" srcset="/img/re/cancle@2x.png 2x,/img/re/cancle@3x.png 3x" data-dismiss="modal">
                        </div>
                        <div class="modal_body">
                            <div style="margin : 10px">
                            <p>검색창? </p>


                                <div id="btn-toggle-mobile-options" style="display : none"></div>
                                <?php if ($sup_its_count > 0) : ?>
                                    <tr>
                                        <td style="padding: 8px 0">
                                            <?php foreach ($sup_its as $sup_group => $sup_group_its) : ?>
                                                <select class="product-detail-item-option" data-no="supply" data-price=0 data-supply=true data-its-no=<?= $io['its_no'] ?>>
                                                    <option value=""><?= $sup_group ?></option>
                                                    <?php foreach ($sup_group_its as $io) : ?>
                                                        <option value="<?= $io['io_price'] ?>" <?php printf("data-no='%s' data-id='%s' data-stock='%s' data-option-price='%s'", $io['io_no'], $io['name'], $io['io_stock_qty'], $io['io_price']); ?> <?= $io['io_noti_qty'] <= 0 ? "disabled" : "" ?>><?= $io['name'] ?><?= $io['io_price'] > 0 ? "(+" . number_format($io['io_price']) . "원)" : "" ?><?= $io['io_noti_qty'] <= 0 ? " - 품절" : "" ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            <?php endforeach ?>
                                        </td>
                                    </tr>
                                <?php endif ?>
                                <?php if ($sub_its_count > 0) : ?>
                                    <tr>
                                        <td><?= $it['it_name'] ?></td>
                                    </tr>
                                    <?php foreach ($sub_its as $sub_group => $sub_group_its) : ?>
                                        <tr>
                                            <td style="padding: 8px 0">
                                                <?php foreach ($sub_group_its as $sub_group_it) : ?>
                                                    <select style="display:none;" class="product-detail-item-option mo-order-select" <?php printf("data-group='%s' data-no='%s' data-its-no='%s' data-item='%s' data-price='%s' data-rental_price='%s'", $sub_group, $sub_group_it['its_no'], $sub_group_it['its_no'], $sub_group_it['its_item'], $sub_group_it['its_final_price'], $sub_group_it['its_final_rental_price']); ?>>
                                                        <option value=""><?= $sub_group ?> 선택</option>
                                                        <?php foreach ($sub_group_it['OPTIONS'] as $io) : ?>
                                                            <option value="<?= $io['io_price'] ?>" selected <?php printf("data-no='%s' data-id='%s' data-stock='%s' data-option-price='%s'", $io['io_no'], $io['io_id'], $io['io_stock_qty'], $io['io_price']); ?> <?= $io['io_noti_qty'] <= 0 ? "disabled" : "" ?>><?= $io['io_id'] ?><?= $io['io_price'] > 0 ? "(+" . number_format($io['io_price']) . "원)" : "" ?><?= $io['io_noti_qty'] <= 0 ? " - 품절" : "" ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                <?php endforeach ?>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php endif ?>
                                <ul id="mobile-options"></ul>
                                <div id="mobile-options-total">
                                    총 <span id="product-item-order-price-total-mobile">0</span>원
                                </div>
                            </div>
                        </div>
                                                        
                    </div>
                </div>
            </div> -->
        <!-- ---------------------------------------------------------------------------- -->












        </div>
        <div id="searchDef">
        <? if (!empty($skeyword)) : ?>
            <div id="search-result-desc">'<?php echo $skeyword ?>'에 대한 <?php echo $snum['total'] ?>개의 검색결과가 있습니다.</div>
        <? endif ?>
        <div id="search-tab-wrapper" class="on-big" style="font-size: 0; margin-left: -28px;">
            <a href="/search.php?type=item&skeyword=<?= $skeyword ?>"><span class="btn-search-tab btn-search-tab-product <?= $type == "item" ? "active" : "" ?>">상품(<?php echo $snum['item'] ?>)</span></a>
            <a href="/search.php?type=brand&skeyword=<?= $skeyword ?>"><span class="btn-search-tab btn-search-tab-brand <?= $type == "brand" ? "active" : "" ?>">브랜드(<?php echo $snum['brand'] ?>)</span></a>
            <a href="/search.php?type=campaign&skeyword=<?= $skeyword ?>"><span class="btn-search-tab btn-search-tab-campaign <?= $type == "campaign" ? "active" : "" ?>">기획전(<?php echo $snum['campaign'] ?>)</span></a>
        </div>
        <div id="search-tab-wrapper" class="on-small" style="font-size: 0;">
            <a href="/search.php?type=item&skeyword=<?= $skeyword ?>"><span class="btn-search-tab btn-search-tab-product <?= $type == "item" ? "active" : "" ?>">상품(<?php echo $snum['item'] ?>)</span></a>
            <a href="/search.php?type=brand&skeyword=<?= $skeyword ?>"><span class="btn-search-tab btn-search-tab-brand <?= $type == "brand" ? "active" : "" ?>">브랜드(<?php echo $snum['brand'] ?>)</span></a>
            <a href="/search.php?type=campaign&skeyword=<?= $skeyword ?>"><span class="btn-search-tab btn-search-tab-campaign <?= $type == "campaign" ? "active" : "" ?>">기획전(<?php echo $snum['campaign'] ?>)</span></a>
        </div>
        <div id="search-filter-row" style="margin-top: 16px; margin-bottom: 29px;">
            <!--
            <span id="btn-toggle-search-filter" class="C2KOGRL disable-select">FILTER</span>
            <select name="" id="" class="C2KOGRL" style="float: right; width: 96px; height: 32px;">
                <option value="">신상품순</option>
                <option value="">인기순</option>
                <option value="">할인율순</option>
            </select>
            -->
        </div>
        <div class="product-list">
            <? if ($type == 'brand') : ?>
                <?php if ($result->num_rows > 0) : ?>
                    <? for ($i = 0; $row = sql_fetch_array($result); $i++) : ?>
                        <a href="/shop/brand.php?br_id=<?= $row['br_id'] ?>">
                            <div class="brand-list-brand on-big" style="background-image: url(/data/brand/<?= $row['br_main_image'] ?>); margin-left:350px;">
                                <div class="brand-list-logo-shadow"></div>
                                <span class="btn-pick-heart on-big <?= in_array($row['br_id'], $g5_picked['BRAND']) ? "picked" : "" ?>" data-type="brand" data-pick=<?= $row['br_id'] ?> style="margin-right: unset; margin-left: -66px; position: absolute;"></span>
                                <?php if (G5_IS_IE) : ?>
                                    <div class="brand-list-logo-wrapper" style="background-color: transparent;">
                                     <!-- <img src="/data/brand/<?= $row['br_logo'] ?>"> -->
                                    <img style="width: 60%; display: block; margin-left: auto; margin-right: auto; margin-top: 130px; " src="/data/brand/<?= $row['br_logo'] ?>">    
                                    </div>
                                <? else : ?>
                                    <!-- <div class="brand-list-logo-wrapper" style="-webkit-mask-image: url(/data/brand/<?= $row['br_logo'] ?>);"></div> -->
                                    <div class="brand-list-logo-wrapper" style="-webkit-mask-image: url(/data/brand/<?= $row['br_logo'] ?>); width: 60%; display: block; margin-left: auto; margin-right: auto;"></div>
                                <? endif ?>
                            </div>
                            <div class="brand-list-brand on-small" style="background-image: url(/data/brand/<?= $row['br_main_image'] ?>);margin-left: -10px;">                            
                                <div class="brand-list-logo-shadow"></div>
                                <!-- <span class="btn-pick-heart on-big <?= in_array($row['br_id'], $g5_picked['BRAND']) ? "picked" : "" ?>" data-type="brand" data-pick=<?= $row['br_id'] ?> style="margin-right: unset; margin-left: -66px; position: absolute;"></span> -->
                                <?php if (G5_IS_IE) : ?>
                                    <div class="brand-list-logo-wrapper" style="background-color: transparent;">
                                        <img src="/data/brand/<?= $row['br_logo'] ?>">
                                    </div>
                                <? else : ?>
                                    <!-- <div class="brand-list-logo-wrapper" style="-webkit-mask-image: url(/data/brand/<?= $row['br_logo'] ?>);"></div> -->
                                    <div class="brand-list-logo-wrapper" style="-webkit-mask-image: url(/data/brand/<?= $row['br_logo'] ?>); width: 72%; display: block; margin-left: auto; margin-right: auto;"></div>
                                <? endif ?>
                            </div>
                        </a>
                    <? endfor; ?>


                <? else : ?>
                    <div class="search-no-result">
                    입력하신 검색어와 일치하는 브랜드가 없습니다.<br>
                        단어, 띄어쓰기를 변경하여 다시 검색해 보세요.<br>
                        <!-- <a href="/shop/brand.php">
                            나만의 침구 브랜드 만나보기<img src="/img/re/right.svg">
                        </a> -->
                    </div>
                <? endif ?>
            <? elseif ($type == 'campaign') : ?>
                <style>
                    @media (max-width: 1366px) {
                        .product-list {
                            width: 100vw;
                            margin-left: -20px;
                        }
                    }
                </style>
                <?php if ($result->num_rows > 0) : ?>
                    <? for ($i = 0; $row = sql_fetch_array($result); $i++) : ?>
                        <a href="<?= empty($row['cp_link']) ? "/campaign/view.php?cp_id=" . $row['cp_id'] : $row['cp_link'] ?>">
                            <div class="event-list-item-wrapper">

                                <div class="event-list-item-image on-big" style="background-image: url(/data/banner/<?= $row['cp_image_1'] ?>)">
                                    <div style="height: 67px;">
                                    <!-- <span class="btn-pick <?= in_array($citem['cp_id'], $g5_picked['EVENT']) ? "picked" : "" ?>" data-type="event" data-pick=<?= $citem['cp_id'] ?>></span> -->
                                    </div>
                                    <div style="height: calc(100% - 67px - 32px);"></div>
                                    <div style="font-size: 0; text-align: right;">
                                        <? if ((substr($row['cp_end_date'], 0, 1) != "0") && strtotime($row['cp_end_date']) > strtotime("Now")) : ?>
                                            <span class="bagde-dday" data-enddate="<?= $row['cp_end_date'] ?>">D - <?= abs(floor((strtotime("Now") - strtotime($row['cp_end_date'])) / 60 / 60 / 24)) ?></span>
                                        <? else : ?>
                                            <span class="bagde-dday" data-enddate="<?= $crowitem['cp_end_date'] ?>">종료</span>
                                        <? endif ?>
                                    </div>
                                </div>

                                <div class="event-list-item-image on-small" style="background-image: url(/data/banner/<?= $row['cp_image_2'] ?>)">
                                    <div style="height: 67px;">
                                    <!-- <span class="btn-pick <?= in_array($citem['cp_id'], $g5_picked['EVENT']) ? "picked" : "" ?>" data-type="event" data-pick=<?= $citem['cp_id'] ?>></span> -->
                                    </div>
                                    <div style="height: calc(100% - 67px - 32px);"></div>
                                    <div style="font-size: 0; text-align: right;">
                                        <? if ((substr($row['cp_end_date'], 0, 1) != "0") && strtotime($row['cp_end_date']) < strtotime("Now")) : ?>
                                            <span class="bagde-dday" data-enddate="<?= $row['cp_end_date'] ?>">D - <?= abs(floor((strtotime("Now") - strtotime($row['cp_end_date'])) / 60 / 60 / 24)) ?></span>
                                        <? endif ?>
                                    </div>
                                </div>


                                <!--  기존꺼 start ----------------------  -->
                                <!-- <div class="event-list-item-image" style="background-image: url(/data/banner/<?= $row['cp_image_1'] ?>)">
                                    <div style="height: 67px;"><span class="btn-pick-heart <?= in_array($row['cp_id'], $g5_picked['EVENT']) ? "picked" : "" ?>" data-type="event" data-pick=<?= $row['cp_id'] ?>></span></div>
                                    <div style="height: calc(100% - 67px - 32px);"></div>
                                    <div style="font-size: 0; text-align: right;">
                                        <? if (strtotime($row['cp_end_date']) < strtotime("+14 day")) : ?>
                                            <span class="bagde-dday">D - <?= floor((strtotime("+14 day") - strtotime($row['cp_end_date'])) / 60 / 60 / 24) ?></span>
                                        <? endif ?>
                                    </div>
                                </div> -->
                                <div class="event-list-item-subject">
                                    <?= $row['cp_subject'] ?>
                                </div>
                                <div class="event-list-item-desc">
                                    <?= $row['cp_desc'] ?>
                                </div>
                                <!-- 기존꺼 end ----------------------------- -->
                                




                            </div>
                        </a>
                    <? endfor; ?>
                <? else : ?>
                    <div class="search-no-result on-small" style="margin-right: -20px;">
                    입력하신 검색어와 일치하는 기획전이 없습니다.<br>
                        단어, 띄어쓰기를 변경하여 다시 검색해 보세요.<br>
                        <!-- <a href="/campaign/list.php">
                            새로운 기획전 만나보기<img src="/img/re/right.svg">
                        </a> -->
                    </div>
                    <div class="search-no-result on-big">
                    입력하신 검색어와 일치하는 기획전이 없습니다.<br>
                        단어, 띄어쓰기를 변경하여 다시 검색해 보세요.<br>
                        <!-- <a href="/campaign/list.php">
                            새로운 기획전 만나보기<img src="/img/re/right.svg">
                        </a> -->
                    </div>
                <? endif ?>
            <? else : ?>
                <style>
                    @media (max-width: 1366px) {
                        .product-list {
                            width: 100vw;
                        }

                        .product-list .product-list-item-wrapper {
                            margin-right: 16px;
                        }
                    }
                </style>
                <?php if ($result->num_rows > 0) : ?>
                    <?php include_once(G5_VIEW_PATH . '/search.filter.php'); ?>
                    <div id="search_list_table">
                    <? for ($i = 0; $row = sql_fetch_array($result); $i++) : ?>

                        <?php 
                            if(MobileCheckSearch() == "Mobile"){ ?>
                            <div class="product-list-item-wrapper<?= $i == 0 ? " first" : "" ?>" style="margin-right: 22px; margin-left: -18px;">
                        <?php   } else { ?>
                            <div class="product-list-item-wrapper<?= $i == 0 ? " first" : "" ?>">
                        <?php   } ?>
                            <a href="/shop/item.php?it_id=<?= $row['it_id']; ?>">
                                <?php
                                $badge = new badge($row);
                                $badgeHtml = $badge->makeHtml();
                                $thumb = get_it_image_path($row['it_id'], 300, 300, '', '', stripslashes($row['it_name']));

                                $sql_option = "SELECT io_no, io_hoching, io_stock_qty FROM lt_shop_item_option WHERE it_id = '{$row['it_id']}' ORDER BY io_no LIMIT 1 ";
                                $option_item = sql_fetch($sql_option);
                                
                                
                                
                                $totalSearch001 = "SELECT io_order_no FROM {$g5['g5_shop_item_option_table']} WHERE it_id = '{$row['it_id']}' LIMIT 1";
                                $totalSearch001_1= sql_fetch($totalSearch001); 
                                $totalSearch002 = "SELECT * FROM {$g5['g5_shop_item_table']} a LEFT JOIN {$g5['g5_shop_item_option_table']} b ON (a.it_id = b.it_id) WHERE b.io_order_no = '{$totalSearch001_1['io_order_no']}' AND it_use = 1  ORDER BY it_price ASC, it_size_info DESC";
                                $totalSearch002_2 = sql_query($totalSearch002); 
                                $totalSearch002_3 = sql_query($totalSearch002); 
                                $soldOut = 1;
                                for ($ts = 0; $totalSearchRow2 = sql_fetch_array($totalSearch002_3); $ts++) {
                                    if ($totalSearchRow2['it_soldout'] == 1 || $totalSearchRow2['io_stock_qty'] < 1 ) {
                                    } else {
                                        $soldOut = 0;
                                    }
                                }
                                ?>

                                <div class="product-list-item-thumb" data-image="<?= $thumb ?>" style="background-image: url(<?= $thumb ?>)">
                                    <? if ($row['it_soldout'] == 1 || $option_item['io_stock_qty'] < 1) : ?>
                                    <div class="soldout_thumb"><p style="opacity: 1; color: black;">일시 품절</p></div>
                                    <? endif ?>    
                                    <span class="btn-pick-heart <?= in_array($row['it_id'], $g5_picked['ITEM']) ? "picked" : "" ?>" data-type="item" data-pick=<?= $row['it_id'] ?>></span>
                                </div>
                                <div class=" product-list-item-brand">
                                    <?php echo empty($row['it_brand']) ? "LIFELIKE" : $row['it_brand'] ?>
                                    <!-- <span class ='hocName<?= $option_item['io_hoching'] ?>'></span> -->
                                    <? for ($tss = 0; $tsR = sql_fetch_array($totalSearch002_2); $tss++) : 
                                        if ($tsR['it_soldout'] == 1 || $tsR['io_stock_qty'] < 1 ) { ?>
                                            <span class ='hocOutName<?= $tsR['io_hoching'] ?>'></span>
                                       <? } else {?>
                                            <span class ='hocName<?= $tsR['io_hoching'] ?>'></span>
                                        <? }
                                        ?>
                                    <? endfor; 
                                        $oneSize = '원 ~';
                                        if ($tss == 1) $oneSize = '원'
                                    ?>
                                </div>
                                <!-- <div class="product-list-item-label"><?= $badgeHtml->html ?></div> -->
                                <div class="product-list-item-name">
                                    <?= stripslashes($row['it_name']) ?>
                                </div>
                                <div class="product-list-item-saleprice">
                                    <?= display_price(get_price($row), $row['it_tel_inq']) ?><span><?= $oneSize?></span>
                                </div>
                                <?php
                                if (!empty($row['it_discount_price'])) {
                                    $it_price = $row['it_price'];
                                    $it_sale_price = $row['it_discount_price'];
                                    $discount_ratio = $it_sale_price / ($it_price + $it_sale_price) * 100;
                                ?>
                                    <div class="product-list-item-price">
                                        <span class="price-tag"><del><?= number_format($it_price + $it_sale_price) ?></del><span>원</span></span>
                                        <span class="price-dis">(<?= number_format($discount_ratio) ?>%)</span>
                                    </div>
                                <?
                                } else { ?>
                                    <div class="product-list-item-price">
                                        <span class="price-tag">&nbsp</span>
                                        <span class="price-dis">&nbsp</span>
                                    </div>
                                <?php
                                }
                                ?>
                            </a>
                        </div>
                    <? endfor; ?>
        </div>
                <? else : ?>
                    <div class="search-no-result on-big">
                    입력하신 검색어와 일치하는 상품이 없습니다.<br>
                    단어, 띄어쓰기를 변경하여 다시 검색해 보세요.<br>
                        <!-- <a href="/shop/">
                            새로운 상품 만나보기<img src="/img/re/right.svg">
                        </a> -->
                    </div>
                    <div class="search-no-result on-small" style="margin-right: 25px;">
                    입력하신 검색어와 일치하는 상품이 없습니다.<br>
                    단어, 띄어쓰기를 변경하여 다시 검색해 보세요.<br>
                        <!-- <a href="/shop/">
                            새로운 상품 만나보기<img src="/img/re/right.svg">
                        </a> -->
                    </div>
                <? endif ?>
            <? endif ?>
        </div>
        <? if ($paging) : ?>
            <div style="margin-bottom: 170px;" class="on-big"><?= $paging ?></div>
        <? endif ?>
        <?php if ($snum[$type] > 12) : ?>
            <div style="clear:both" class="on-small add_search_btn"><a onclick="addSearchList(<?= $total_page ?>, '<?php echo $skeyword ?>')">더보기</a></div>
        <? endif ?>
        <div style="clear:both"></div>
    </div>
    </div>
</form>
<script>
    $("#btn-toggle-search-filter").on("click", function() {
        $("#search-filter-wrapper").toggleClass("active");
        hasScrollBar();
    });

    $("#btn-search-clear").on("click", function() {
        $("#input-search-keyword").val("");
    });

    var add_search_page = 2;
    function addSearchList(totalPage,key) {
        
        $.ajax({
            url: '/ajax_front/ajax.search_add.php',
            type: 'post',
            data: {
                page: add_search_page,
                searchKey : key
            },
            success: function(response) {
                $('#search_list_table').append(response);
                add_search_page++;
            }
        });
        if (add_search_page >= totalPage) {
            $('.add_search_btn').css('display', 'none');
        }
    }
    $('#input-search-keyword').click(function() {
        var pcCheck = document.querySelector('#input-search-keyword').dataset.value;    
        if (pcCheck=='Mobile') {
            $('.mo-order-select').val('0').trigger('change');
            $('#modal-mobile-order-select').modal('show');
        }    
    });
    $('html').click(function(e) { 
        var pcCheck = document.querySelector('#input-search-keyword').dataset.value;    
        if (pcCheck=='Mobile') { 
            if(!$(e.target).hasClass("searchTd2") && !$(e.target).hasClass("form-control form-input C1KOBLL")) { 
                $('#searchDivMain').hide(); 
                $('#searchDef').show();
            } 
        }
    });
    function inputClick() {
        var pcCheck = document.querySelector('#input-search-keyword').dataset.value;    
        if (pcCheck!='Computer') { 
            $('#nav-bottom-small').hide(); 
            $('#nav-bottom-search-small').modal('show');
            $("#skeyword2").focus();
            $("#skeyword2").focus(function(){
            $("#skeyword2").css("border","border: 2px solid black");
            });
            $("#nav-bottom-search-small").animate({height:"100%"},1000);

        }
    }

    function recentSearchMain() {

        let searchWord = document.getElementById('input-search-keyword').value;
        if (searchWord =='') return 
        let cookieGet = get_cookie('sword');
        let cookieArr;
        let arrNum;
        if (cookieGet) {
            cookieArr = cookieGet.split('\\');
            arrNum = cookieArr.length;
        } 
        if (!arrNum) {
            return set_cookie('sword', searchWord ,60*60*24*365);
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
            if (i==10) break;
        }
        set_cookie('sword', cookieSave, 60*60*24*365); 
    }
    var seaDelCheck = 0;
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
</script>
<?php
$contents = ob_get_contents();
ob_end_clean();

return $contents;
?>;