<?php
ob_start();
$g5_title = "상품리뷰";
include_once G5_LAYOUT_PATH . "/nav.member.php";
?>
<style>
    .order-detail-circle-label {
        margin-top: 64px;
    }

    @media (max-width: 1366px) {
        div#member-content-wrapper {
            /* padding: 0 20px; */
        }
    }
    #review-tab-wrapper {
        width: 100%;
        margin-bottom: 20px;
    }

    #review-tab-wrapper>a {
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
        background-color: #f2f2f2;
    }

    #review-tab-wrapper>a.active {
        background-color: #ffffff;
        color: #333333;
        border-color: #333333;
        border-bottom-width: 0;
    }
    .review-content-img {
        display: none;
    }
    .review-content-img.active {
        display: table-row;
    }
    .review-content {
        display: none;
    }
    .review-content.active {
        display: table-row;
    }
    .review-content>td {
        /* font-size: 16px !important;
        font-weight: bold !important;
        color: #000000 !important;
        text-align: left !important; */
        border-top:none !important;
        background-color: #f2f2f2;
        border: hidden;
        /* padding: 16px 0 16px 20px; */

    }
    .review_tr.active {
        border-bottom: hidden;
    }

    .mo_review_modify_btn {
        width: 64px;
        height: 36px;
        border-radius: 20px;
        line-height: 36px;
        border: 1px solid #333333;
        text-align: center;
        font-size: 14px;
        color: #3a3a3a;
        background-color: #ffffff;
    }

    .mo_review_delete_btn {
        width: 64px;
        height: 36px;
        border-radius: 20px;
        line-height: 36px;
        border: 1px solid #333333;
        text-align: center;
        font-size: 14px;
        color: #3a3a3a;
        background-color: #ffffff;
    }

    .mo_review_delete_btn {
        width: 64px;
        height: 36px;
        border-radius: 20px;
        line-height: 36px;
        border: 1px solid #333333;
        text-align: center;
        font-size: 14px;
        color: #3a3a3a;
    }
    .add_review_btn {
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

    .add_review_pos_btn {
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



ul.containter_tabs {
    margin: 0px;
    padding: 0px;
    list-style: none;
}
ul.containter_tabs li {
    background: none;
    color: #adadad;
    display: inline-block;
    padding: 10px 15px;
    cursor: pointer;
    width: calc(50% - 2px);
    text-align: center;
    border: 1px solid #e5e5e5;
    border-bottom: 1px solid #333333;
    margin-right: -3px;
}
ul.containter_tabs li.current {
    border: 1px solid #333333;
    border-bottom: none;
    color: #222;
    background-color: #ffffff;
}
@media (max-width: 1366px) { 
    ul.containter_tabs li {
        background-color: #f2f2f2;
        display: inline-block;
        padding: 10px 15px;
        cursor: pointer;
        width: calc(50% - 2px);
        font-size: 14px;
        height: 50px;
        text-align: center;
        border-bottom: 1px solid #333333;
        margin-right: -4px;
        color: #9f9f9f;
    }

    ul.containter_tabs li.current {
        border: 1px solid #333333;
        background-color: #ffffff;
        border-bottom: none;
        color: #222;
    }
}


</style>
<div id="member-content-wrapper">
    <div class="member-content-title on-big" style="padding: 0 20px;">
        상품리뷰
    </div>
    <div id="review-tab-wrapper" class="on-big" style="font-size: 0px; padding: 0 20px;">
        <a class="<?= $type != 'done' ? 'active' : '' ?>" href="/member/review.php?type=possible">작성가능 상품리뷰</a>
        <a class="<?= $type == 'done' ? 'active' : '' ?>" href="/member/review.php?type=done">작성한 상품리뷰</a>
    </div>
    <div id="review-tab-wrapper" class="on-small" style="font-size: 0px;">
        <a class="<?= $type != 'done' ? 'active' : '' ?>" href="/member/review.php?type=possible">작성가능 상품리뷰</a>
        <a class="<?= $type == 'done' ? 'active' : '' ?>" href="/member/review.php?type=done">작성한 상품리뷰</a>
    </div>
<div style="padding: 0 20px;">  
    <? if ($type == 'done') : ?>
        <div class="member-content-section">
            <? if ($db_review->num_rows > 0) : ?>
                <table id="review-list-table-done" style="margin-top: 25px;">
                    <colgroup class="on-big">
                        <col style="width: 220px">
                        <col style="width: 160px">
                        <col style="width: 440px">
                        <col style="width: 180px">
                        <col style="width: 180px">
                    </colgroup>

                    <tr class="on-big" height= "56px;" style="border-top: 2px solid #333333; color: #424242; font-size: 16px; font-weight: 500;">
                        <td>일자/주문번호</td>
                        <td colspan="2">상품정보</td>
                        <td>작성일</td>
                        <td>리뷰작성</td>
                    </tr>
                    <? for ($oi = 0; $review = sql_fetch_array($db_review); $oi++) : ?>
                        <?php
                        $sql_cart_item = "SELECT cart.it_name, cart.ct_option, cart.it_id, cart.ct_keep_month, cart.ct_id, cart.ct_time, cart.od_id, cart.ct_price, cart.ct_qty, cart.ct_option, options.io_hoching FROM {$g5['g5_shop_cart_table']} as cart LEFT JOIN lt_shop_item_option as options  on cart.it_id = options.it_id WHERE cart.ct_id='{$review['ct_id']}' ORDER BY cart.io_type, cart.ct_id LIMIT 1 ";
                        $cart_item = sql_fetch($sql_cart_item);
                        $it_name = get_text($cart_item['it_name']);
                        // koo
                        $od_image =  get_it_image($cart_item['it_id'], 120, 120);
                        $od_image_mo =  get_it_image($cart_item['it_id'], 75, 75);
                        $od_id = ($cart_item['od_id']);
                        $ct_price = number_format($cart_item['ct_price']);
                        $ct_qty = number_format($cart_item['ct_qty']);
                        $ct_time = date("Y.m.d", strtotime($cart_item['ct_time']));
                        $ct_option = $cart_item['ct_option'];

                        // // 가격 ---------------------------------------------
                        $it_discount_price = $review['it_discount_price'];
                        $it_sale_price = ($cart_item['ct_price'] + $review['it_discount_price']);
                        if (!empty($it_sale_price)) {
                            $discount_ratio = $review['it_discount_price'] / ($cart_item['ct_price'] + $review['it_discount_price']) * 100;
                            $discount_ratio = round($discount_ratio);
                        }

                        $sql_files_img = "SELECT bf_file, bf_no FROM lt_shop_item_use_file WHERE is_id='{$review['is_id']}' ORDER BY bf_no";
                        $db_files_img = sql_query($sql_files_img);
                        $result_img=null;
                        $tmp_files_img=null;
                        while (false != ($frow_img = sql_fetch_array($db_files_img))) {
                            $tmp_file_img = array(
                                'no' => $frow_img['bf_no'],
                                'file' => $frow_img['bf_file']
                            );
                    
                            $tmp_files_img[] = $tmp_file_img;
                        }
                        $result_img= $tmp_files_img;
                        // $("#imgimgFile"+ffidx+"-mobile").attr("src", "/data/file/itemuse/" + response.file[fidx]['file']).css("display", "inline-block");

                        // if ($result_img['file'].length > 0) {
                        //     $(response.file).each(function(fidx) {
                        //         const ffidx = 3 - (fidx * 1);
                        //         $("#imgimgFile" + ffidx).attr("src", "/data/file/itemuse/" + response.file[fidx]['file']).css("display", "inline-block");
                        //         $("#imgimgFile"+ffidx+"-mobile").attr("src", "/data/file/itemuse/" + response.file[fidx]['file']).css("display", "inline-block");
                        //         $("#imgimgDel" + ffidx).css("display", "inline-block");
                        //     });

                        // }


                        /// ------------------------------------가격--------------------------
                        // 이미지 가져오기
                        // $sql_files_img = "SELECT * FROM lt_shop_item_use_file WHERE is_id='{$db_review['is_id']}' ORDER BY bf_no";
                        // $db_files_img = sql_query($sql_files_img);
                        // $tmp_files_img = array();

                        // while (false != ($frow = sql_fetch_array($db_files_img))) {
                        //     $tmp_file_img = array(
                        //         'no' => $frow['bf_no'],
                        //         'file' => $frow['bf_file']
                        //     );
                    
                            // $tmp_files_img[] = $tmp_file_img;
                        // }
                        // $result_img['file'] = $tmp_files_img;


                        ?>
                        <tr class="on-small" style="height: 17px;">
                            <td colspan="3" style="height: 17px; border: 0px">
                                <span style="font-size: 18px; font-weight: 500; color: #333333; position: relative; top: 10px;"><?= $od_id ?></span>
                                <span style="font-size: 12px; color: #959595; margin-left: 10px; position: relative; top: 10px;"><?= $ct_time ?><span>
                            </td>
                        </tr>
                        <tr class="on-small" height="100px" style="border-top: 0px; border-bottom: 1px solid #e0e0e0;">
                            <td style="border-bottom: 1px solid #e0e0e0">
                                <div><?= $od_image_mo ?></div>
                            </td>
                            <td style="border-bottom: 1px solid #e0e0e0" onclick="openReviewMobile(this)">
                                <div style="position: relative; top: -10px;">
                                    <div style="font-size: 10px; color: #3a3a3a;"><?= $review['it_brand'] ?>
                                        <span class ='hocName<?= $cart_item['io_hoching'] ?>'></span>
                                        <!-- <img src="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png" srcset="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@2x.png 2x,/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png 3x"> -->
                                    </div>
                                    <div style="font-size: 12px; color: #3a3a3a; width:195px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?= $it_name ?></div>
                                    <div style="font-size: 12px; color: #3a3a3a;"><?= $ct_price ?>원</div>
                                </div>
                            </td>
                            <td style="border-bottom: 1px solid #e0e0e0"> 
                                <div style="margin-top: -30px;">
                                <button type="button" class="btn btn-black btn-write-review" style="width: 64px; height: 36px; border-radius: 20px; line-height: 36px; border: 1px solid #333333; text-align: center; font-size: 14px; color: #3a3a3a; background-color: #ffffff; margin-top: 0; padding:0;" data-it="<?= $cart_item['it_id'] ?>" data-ct="<?= $cart_item['ct_id'] ?>" data-type="update">수정</button>
                                </div>
                                <div style="margin-top: 10px;">
                                <button type="button" class="btn btn-black btn-write-review" style="width: 64px; height: 36px; border-radius: 20px; line-height: 36px; border: 1px solid #333333; text-align: center; font-size: 14px; color: #3a3a3a; background-color: #ffffff; margin-top: 0; padding:0;" data-it="<?= $cart_item['it_id'] ?>" data-ct="<?= $cart_item['ct_id'] ?>" data-type="delete">삭제</button>    
                                </div>
                            </td>
                        </tr>
                        <tr class="review-content on-small" style="height: 73px;">
                            <td style="border-bottom: 1px solid #9f9f9f;"><span>옵션</span>
                                <div>
                                    만족도
                                </div>
                            </td>
                            <td style="border-bottom: 1px solid #9f9f9f;"><span><?= $ct_option ?></span>
                                <div>
                                    <?php echo str_repeat('★', $review['is_score']) ?>
                                </div>    
                            </td>
                            <td style="border-bottom: 1px solid #9f9f9f;"><span><?= $review['is_name'] ?></span>
                                <div>
                                    <?= date("Y.m.d", strtotime($review['is_time'])) ?>
                                </div>
                            </td>
                        </tr> 
                        <tr class="review-content-img on-small" style="background-color: #f2f2f2;" >
                            
                            <td colspan="3" style="border-bottom : none"><span style="color:#424242; font-size:14px; font-weight: normal;"><?= $review['is_content'] ?></span>
                                <? if (!empty($result_img[0]['file'])) : ?>
                                    <div style="text-align :center; margin-top: 10px;"><span><img src="/data/file/itemuse/<?= $result_img[0]['file'] ?>" style="width: 332px; height: 250px;"></span></div>
                                    <br>
                                <? endif ?>
                                <? if (!empty($result_img[1]['file'])) : ?>
                                    <div style="text-align :center;"><span><img src="/data/file/itemuse/<?= $result_img[1]['file'] ?>" style="width: 332px; height: 250px;"></span></div>
                                    <br>
                                <? endif ?>
                                <? if (!empty($result_img[2]['file'])) : ?>
                                    <div style="text-align :center;"><span><img src="/data/file/itemuse/<?= $result_img[2]['file'] ?>" style="width: 332px; height: 250px;"></span></div>
                                    <br>
                                <? endif ?>
                            </td>

                        </tr> 
                        <!-- <tr class="review-content on-small">
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                        </tr>  -->

                            <!-- <td colspan=3; style= "text-align: left;"> 
                            <div style="margin-bottom :20px; font-size: 14px; font-weight: 500; color: #606060; margin-left :17px;"> 옵션 <span style="margin-left :120px; font-size: 14px; color: #424242;"><?= $ct_option ?></span></div>
                            <div style="margin-bottom :20px; font-size: 14px; font-weight: 500; color: #606060; margin-left :17px;"> 만족도 <span style="margin-left :105px; font-size: 14px; color: #424242;"><?php echo str_repeat('★', $review['is_score']) ?></span></div>
                            <div style="margin-bottom :20px; font-size: 14px; font-weight: 500; color: #606060; margin-left :17px;"> 내용 <span style="margin-left :120px; font-size: 14px; color: #424242;"><?= $review['is_content'] ?></span></div>
                            
                            <? if (!empty($result_img[0]['file'])) : ?>
                            <div style="text-align :center;"><span><img src="/data/file/itemuse/<?= $result_img[0]['file'] ?>" style="width: 50px; height: 50px;"></span></div>
                            <br>
                            <? endif ?>
                            <? if (!empty($result_img[1]['file'])) : ?>
                            <div style="text-align :center;"><span><img src="/data/file/itemuse/<?= $result_img[1]['file'] ?>" style="width: 50px; height: 50px;"></span></div>
                            <br>
                            <? endif ?>
                            <? if (!empty($result_img[2]['file'])) : ?>
                            <div style="text-align :center;"><span><img src="/data/file/itemuse/<?= $result_img[2]['file'] ?>" style="width: 50px; height: 50px;"></span></div>
                            <br>
                            <? endif ?>
                            </td>
                        </tr> -->
                        








                        <tr class="review_tr on-big" height= "236px;">
                            <td>
                                <div style="font-size: 14px; font-weight: 500; color: #424242; text-decoration:underline;"><?= $od_id ?></div>
                                <div style="font-size: 14px; font-weight: 500; color: #9f9f9f;">(<?= $ct_time ?>)<div>
                            </td>
                            <td style="font-size: 18px; text-align: left; cursor: pointer;" onclick=location.href="/shop/item.php?it_id=<?= $cart_item['it_id'] ?>"><?= $od_image ?></td>
                            
                            <!-- 상품정보 넣어야함 ㅋㅋㅋ -->
                            <td onclick="openReview(this)" class="table_item_contents" style="text-align: left; padding: 58px 0;">
                                <div style="font-size: 16px; color: #4c4c4c;"><?= $review['it_brand'] ?>
                                    <span class ='hocName<?= $cart_item['io_hoching'] ?>'></span>
                                <!-- <img src="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png" srcset="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@2x.png 2x,/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png 3x"> -->
                                </div>
                                <div style="font-size: 18px; color: #3a3a3a;"><?= $it_name ?></div>
                                <div style="font-size: 18px; color: #3a3a3a;">
                                    <span style="font-size: 16px; color: #3a3a3a"><?= $ct_price ?>원</span>
                                    <? if (!empty($it_discount_price) || $it_discount_price != 0 ) : ?>
                                    <del style="font-size: 14px; color: #9f9f9f;"><?= number_format($it_sale_price) ?>원</del>
                                    <span style="font-size: 14px; color: #f93f00"><?= $discount_ratio ?>%</span>
                                    <? endif ?>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 14px; font-weight: 500; color: #424242;"><?= date("Y.m.d", strtotime($review['is_time'])) ?><div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-black btn-write-review" style="width: 111px; height: 44px; border-radius: 2px; background-color: #333333; margin-top: -1px; color:#ffffff; font-size: 16px;" data-it="<?= $cart_item['it_id'] ?>" data-ct="<?= $cart_item['ct_id'] ?>" data-type="update">수정</button>
                                <button type="button" class="btn btn-black btn-write-review" style="width: 111px; height: 44px; border-radius: 2px; background-color: #ffffff; margin-top: 15px; color:#333333; font-size: 16px; border: 1px solid #333333;" data-it="<?= $cart_item['it_id'] ?>" data-ct="<?= $cart_item['ct_id'] ?>" data-type="delete">삭제</button>
                            </td>
                        </tr>

                        <tr class="review-content on-big" height= "479px;">

                            <td colspan=5; style= "text-align: left;"> 
                            <div style="margin-bottom :20px; font-size: 14px; font-weight: 500; color: #606060; margin-left :220px;"> 옵션 <span style="margin-left :120px; font-size: 14px; color: #424242;"><?= $ct_option ?></span></div>
                            <div style="margin-bottom :20px; font-size: 14px; font-weight: 500; color: #606060; margin-left :220px;"> 만족도 <span style="margin-left :105px; font-size: 14px; color: #424242;"><?php echo str_repeat('★', $review['is_score']) ?></span></div>
                            <div style="margin-bottom :20px; font-size: 14px; font-weight: 500; color: #606060; margin-left :220px;"> 내용 <span style="margin-left :120px; font-size: 14px; color: #424242;"><?= $review['is_content'] ?></span></div>
                            
                            <? if (!empty($tmp_files_img[0]['file'])) : ?>
                            <div style="margin-left :220px;"><span><img src="/data/file/itemuse/<?= $tmp_files_img[0]['file'] ?>" style="width: 340px; height: 340px;"></span></div>
                            <br>
                            <? endif ?>
                            <? if (!empty($tmp_files_img[1]['file'])) : ?>
                            <div style="margin-left :220px;"><span><img src="/data/file/itemuse/<?= $tmp_files_img[1]['file'] ?>" style="width: 340px; height: 340px;"></span></div>
                            <br>
                            <? endif ?>
                            <? if (!empty($tmp_files_img[2]['file'])) : ?>
                            <div style="margin-left :220px;"><span><img src="/data/file/itemuse/<?= $tmp_files_img[2]['file'] ?>" style="width: 340px; height: 340px;"></span></div>
                            <br>
                            <? endif ?>
                            </td>

                        </tr>
                    <? endfor ?>
                </table>
            <? else : ?>
                <div class="member-no-content on-big">
                    작성한 리뷰가 없습니다
                </div>
                <div class="member-no-content on-small" style="color:#ee5600;">
                    작성한 리뷰가 없습니다
                </div>
            <? endif ?>
        </div>
        <? if ($repaging) : ?>
            <div class="on-big" style="margin-bottom: 170px;"><?= str_replace('page=', 'repage=', $repaging) ?></div>
        <? endif ?>

        <?php if ($total_count > 5) : ?>
            <div class="add_review_btn on-small"><span onclick="addReviewList(<?= $total_page ?>)">더보기</span></div>
        <? endif ?>
    <? else : ?>
        <div class="member-content-section">
            <? if ($db_order->num_rows > 0) : ?>
                <table id = "review-list-table-pos" style="margin-top: 25px;">

                    <colgroup class="on-big">
                        <col style="width: 220px">
                        <col style="width: 160px">
                        <col style="width: 440px">
                        <col style="width: 180px">
                        <col style="width: 180px">
                    </colgroup>

                    <!-- <colgroup class="on-small">
                        <col style="width: 20%">
                        <col>
                        <col style="width: 20%">
                    </colgroup> -->


                    <tr class="on-big" height= "56px;" style="border-top: 2px solid #333333; color: #424242; font-size: 16px; font-weight: 500;">
                        <td>일자/주문번호</td>
                        <td colspan="2">상품정보</td>
                        <td>결제금액(수량)</td>
                        <td>리뷰작성</td>
                    </tr>
                    <? for ($oi = 0; $order = sql_fetch_array($db_order); $oi++) : ?>
                        <?php
                        // $sql_cart_item = "SELECT it_name, ct_option, it_id, ct_keep_month, ct_id, ct_time FROM {$g5['g5_shop_cart_table']} WHERE ct_id='{$order['ct_id']}' ORDER BY io_type, ct_id LIMIT 1 ";
                        // $cart_item = sql_fetch($sql_cart_item);

                        // $od_image =  get_it_image($cart_item['it_id'], 90, 90);
                        // $it_name = get_text($cart_item['it_name']);
                        ?>

                        <?php
                        // $sql_cart_item = "SELECT it_name, ct_option, it_id, ct_keep_month, ct_id, ct_time, od_id, ct_price, ct_qty, ct_option,io_hoching FROM {$g5['g5_shop_cart_table']} WHERE ct_id='{$order['ct_id']}' ORDER BY io_type, ct_id LIMIT 1 ";
                        $sql_cart_item = "SELECT cart.it_name, cart.ct_option, cart.it_id, cart.ct_keep_month, cart.ct_id, cart.ct_time, cart.od_id, cart.ct_price, cart.ct_qty, cart.ct_option, options.io_hoching FROM {$g5['g5_shop_cart_table']} as cart LEFT JOIN lt_shop_item_option as options  on cart.it_id = options.it_id  WHERE cart.ct_id='{$order['ct_id']}' ORDER BY cart.io_type, cart.ct_id LIMIT 1 ";
                        $cart_item = sql_fetch($sql_cart_item);
                        $it_name = get_text($cart_item['it_name']);
                        // koo
                        $od_image =  get_it_image($cart_item['it_id'], 120, 120);
                        $od_image_mo =  get_it_image($cart_item['it_id'], 75, 75);
                        $od_id = ($cart_item['od_id']);
                        $ct_price = number_format($cart_item['ct_price']);
                        $ct_qty = number_format($cart_item['ct_qty']);
                        $ct_time = date("Y.m.d", strtotime($cart_item['ct_time']));
                        $ct_option = $cart_item['ct_option'];

                        $it_discount_price = $order['discount_price'];
                
                        $it_sale_price = ($cart_item['ct_price'] + $order['discount_price']);
                        if (!empty($it_sale_price)) {
                            $discount_ratio = $order['discount_price'] / ($cart_item['ct_price'] + $order['discount_price']) * 100;
                        }
                        ?>

                        <!--  -->
                        <tr class="on-big" height= "236px;">
                            <td>
                                <div style="font-size: 14px; font-weight: 500; color: #424242; text-decoration:underline;"><?= $od_id ?></div>
                                <div style="font-size: 14px; font-weight: 500; color: #9f9f9f;">(<?= $ct_time ?>)<div>
                            </td>
                            <td style="font-size: 18px; text-align: left; cursor: pointer;"><?= $od_image ?></td>
                            <td class="table_item_contents" style="text-align: left; padding: 58px 0;">
                                <div style="font-size: 16px; color: #4c4c4c;"><?= $order['it_brand'] ?>
                                    <span class ='hocName<?= $cart_item['io_hoching'] ?>'></span>
                                    <!-- <img src="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png" srcset="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@2x.png 2x,/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png 3x"> -->
                                </div>
                                <div style="font-size: 18px; color: #3a3a3a;"><?= $it_name ?></div>
                                <div style="font-size: 18px; color: #3a3a3a;">
                                    <span style="font-size: 16px; color: #3a3a3a"><?= $ct_price ?>원</span>
                                    <? if (!empty($it_discount_price) || $it_discount_price != 0 ) : ?>
                                    <del style="font-size: 14px; color: #9f9f9f;"><?= number_format($it_sale_price) ?>원</del>
                                    <span style="font-size: 14px; color: #f93f00"><?= number_format($discount_ratio) ?>%</span>
                                    <? endif ?>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 18px; font-weight: 500; color: #3a3a3a;"><?= $ct_price ?>원<div>
                                <span style="font-size: 14px; font-weight: 500; color: #a9a9a9;">(<?= number_format($cart_item['ct_qty']) ?>개)</span>
                                <!-- <div>()</div> 개수는 어딨냐 ㅋㅋㅋㅋㅋ --> 
                            </td>
                            <td>
                                <button type="button" class="btn btn-black btn-write-review" style="width: 81px; height: 32px; border-radius: 2px; background-color: #ffffff; color:#f93f00; margin-top: 0px; font-size: 14px; font-weight: 500; border: 1px solid #f93f00;" data-it="<?= $cart_item['it_id'] ?>" data-ct="<?= $cart_item['ct_id'] ?>">리뷰작성</button>
                            </td>
                        </tr>
                        <!-- <tr class="on-small" height="30px">

                            <td colspan="3" style="border: 0px">
                                <span style="font-size: 18px; font-weight: 500; color: #333333;"><?= $od_id ?></span>
                                <span style="font-size: 1px; color: #959595; margin-left: 10px;"><?= $ct_time ?><span>
                            </td>
                        </tr> -->
                        <tr class="on-small" style="height: 17px;">
                            <td colspan="3" style="height: 17px; border: 0px">
                                <span style="font-size: 18px; font-weight: 500; color: #333333; position: relative; top: 10px;"><?= $od_id ?></span>
                                <span style="font-size: 12px; color: #959595; margin-left: 10px; position: relative; top: 10px;"><?= $ct_time ?><span>
                            </td>
                        </tr>
                        <tr class="on-small" height="100px" style="border-top: 0px; border-bottom: 1px solid #e0e0e0;">
                            <td style="border-bottom: 1px solid #e0e0e0">
                                <div><?= $od_image_mo ?></div>
                            </td>
                            <td style="border-bottom: 1px solid #e0e0e0">
                                <div style="position: relative; top: -10px;">
                                    <div style="font-size: 10px; color: #3a3a3a;"><?= $order['it_brand'] ?>
                                        <span class ='hocName<?= $cart_item['io_hoching'] ?>'></span>
                                        <!-- <img src="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png" srcset="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@2x.png 2x,/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png 3x"> -->
                                    </div>
                                    <div style="font-size: 12px; color: #3a3a3a; width:195px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?= $it_name ?></div>
                                    <div style="font-size: 12px; color: #3a3a3a;"><?= $ct_price ?>원</div>
                                </div>
                            </td>
                            <td style="border-bottom: 1px solid #e0e0e0"> 
                                <span style="position: relative; top: -33px;">
                                <button type="button" class="btn btn-black btn-write-review" style="width: 64px; height: 24px; font-size:10px; background-color: #ffffff; border: 1px solid #424242; color:#424242; line-height: 0;" data-it="<?= $cart_item['it_id'] ?>" data-ct="<?= $cart_item['ct_id'] ?>">리뷰작성</button>
                                </span>
                            </td>
                        </tr>


                    <? endfor ?>
                </table>
            <? else : ?>
                <div class="member-no-content on-big">
                    작성가능한 상품리뷰가 없습니다.
                </div>
                <div class="member-no-content on-small" style="color:#ee5600;">
                    작성가능한 상품리뷰가 없습니다.
                </div>
            <? endif ?>
        </div>
        <? if ($paging) : ?>
            <div class="on-big" style="margin-bottom: 170px;"><?= $paging ?></div>
        <? endif ?>
        <?php if ($total_count > 5) : ?>
            <div class="add_review_pos_btn on-small"><span onclick="addReviewPosList(<?= $total_page ?>)">더보기</span></div>
        <? endif ?>


    <? endif ?>

    <!-- <h1>작성 가능한 리뷰(<?= $cnt_order['CNT'] ? $cnt_order['CNT'] : 0 ?>)</h1> -->

    <div style="margin-bottom: 120px; display: inline-block;"></div>
    </div> 
</div>
<script>
    function openReview(elem) {
        if ($(elem).parent().next(".review-content.on-big").hasClass("active") === true) {
            $(elem).parent().next(".review-content.on-big").removeClass("active");
            $(elem).parent(".review_tr").removeClass("active");
        } else {
            $(".review-content.on-big").removeClass("active");
            $(elem).parent().next(".review-content.on-big").addClass("active");
            $(elem).parent(".review_tr").addClass("active");
        }
    }
    function openReviewMobile(elem) {
        if ($(elem).parent().next(".review-content.on-small").hasClass("active") === true) {
            $(elem).parent().next(".review-content.on-small").removeClass("active");
            $(elem).parent().next().next(".review-content-img.on-small").removeClass("active");
            // $(elem).parent(".review_tr").removeClass("active");
        } else {
            $(".review-content.on-small").removeClass("active");
            $(".review-content-img.on-small").removeClass("active");
            $(elem).parent().next(".review-content.on-small").addClass("active");
            $(elem).parent().next().next(".review-content-img.on-small").addClass("active");
            $(elem).parent(".review_tr").addClass("active");
        }
    }

    var add_review_page = 2;
    function addReviewList(totalPage) { 
        var it_id = $('#review_it_id_hi').val();
        $.ajax({
            url: '/ajax_front/ajax.review_mobile.php',
            type: 'post',
            data: {
                page: add_review_page,
                it_id: it_id
            },

            success: function(response) {
                $('#review-list-table-done tbody').append(response);
                add_review_page++;
            }
        });

        if (add_review_page  >= totalPage) {
            $('.add_review_btn').css('display', 'none');
        }
    }

    var add_review_pos_page = 2;
    function addReviewPosList(totalPage) { 
        var it_id = $('#review_it_id_hi').val();
        $.ajax({
            url: '/ajax_front/ajax.review_mobile_pos.php',
            type: 'post',
            data: {
                page: add_review_pos_page,
                it_id: it_id
            },

            success: function(response) {
                $('#review-list-table-pos tbody').append(response);
                add_review_pos_page++;
            }
        });

        if (add_review_pos_page  >= totalPage) {
            $('.add_review_pos_btn').css('display', 'none');
        }
    }
    $('ul.containter_tabs li').click(function() {
        var tab_id = $(this).attr('data-tab');

        $('ul.containter_tabs li').removeClass('current');
        $('.tab-content').removeClass('current');

        $(this).addClass('current');
        $("#" + tab_id).addClass('current');
    })
    // function addList(totalPage) {
    //     alert('addList');
    //     var it_id = $('#review_it_id_hi').val();
    //     $.ajax({
    //         url: '/ajax_front/ajax.review.php',
    //         type: 'post',
    //         data: {
    //             page: add_review_page,
    //             it_id: it_id
    //         },

    //         success: function(response) {
    //             $('#review-list-table tbody').append(response);
    //             add_review_page++;
    //         }
    //     });

    //     if ((add_review_page * 5) >= totalPage) {
    //         $('.add_review_btn').css('display', 'none');
    //     }
    // }

    
</script>
<?php
include_once G5_LAYOUT_PATH . "/modal.review.php";
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>