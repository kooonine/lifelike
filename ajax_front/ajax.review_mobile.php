<?php
include_once('./../common.php');
$sql_common = " from `{$g5['g5_shop_item_qa_table']}`";
$page = $_POST['page'];
$sql_cnt_review_mobile = "SELECT COUNT(*) AS CNT FROM lt_shop_item_use WHERE mb_id='{$member['mb_id']}' ";
$cnt_review_mobile = sql_fetch($sql_cnt_review_mobile);

// if ($repage > 1) $fr = ($repage - 1) * $perpage . ",";
$perpage = 5;
if ($page > 1) $fr = ($page - 1) * $perpage . ",";


$total_count = $cnt_review_mobile['CNT'];


$sql_review_mobile = "SELECT iu.it_id,iu.ct_id,iu.is_time,it.it_brand,it.it_discount_price, iu.is_score, iu.is_content FROM lt_shop_item_use AS iu JOIN {$g5['g5_shop_item_table']} AS it ON iu.it_id=it.it_id WHERE mb_id='{$member['mb_id']}' ORDER BY is_time DESC LIMIT {$fr}{$perpage}";
$db_review_mobile = sql_query($sql_review_mobile);

$rows = 5;
$total_page  = ceil($total_count / $rows);  
if ($page < 1) { $page = 1; } 

?>


<? for ($oi = 0; $review = sql_fetch_array($db_review_mobile); $oi++) : ?>
                        <?php
                        $sql_cart_item = "SELECT it_name, ct_option, it_id, ct_keep_month, ct_id, ct_time, od_id, ct_price, ct_qty, ct_option, io_hoching FROM {$g5['g5_shop_cart_table']} WHERE ct_id='{$review['ct_id']}' ORDER BY io_type, ct_id LIMIT 1 ";
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

                        $it_discount_price = $review['it_discount_price'];
                        $it_sale_price = ($cart_item['ct_price'] + $review['it_discount_price']);
                        if (!empty($it_sale_price)) {
                            $discount_ratio = $review['it_discount_price'] / ($cart_item['ct_price'] + $review['it_discount_price']) * 100;
                        }
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
                                    <!-- <img src="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png" srcset="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@2x.png 2x,/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png 3x"> -->
                                    <span class ='hocName<?= $cart_item['io_hoching'] ?>'></span>
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

                        <tr class="review_tr on-big" height= "236px;">
                            <td>
                                <div style="font-size: 14px; font-weight: 500; color: #424242; text-decoration:underline;"><?= $od_id ?></div>
                                <div style="font-size: 14px; font-weight: 500; color: #9f9f9f;">(<?= $ct_time ?>)<div>
                            </td>
                            <td style="font-size: 18px; text-align: left; cursor: pointer;" onclick=location.href="/shop/item.php?it_id=<?= $cart_item['it_id'] ?>"><?= $od_image ?></td>
                            
                            <!-- 상품정보 넣어야함 ㅋㅋㅋ -->
                            <td onclick="openReview(this)" class="table_item_contents" style="text-align: left; padding: 58px 0;">
                                <div style="font-size: 16px; color: #4c4c4c;"><?= $review['it_brand'] ?>
                                    <!-- <img src="/img/re/size_lable/<?= replace_hoching($cart_item['io_id']) ?>@3x.png" srcset="/img/re/size_lable/<?= replace_hoching($cart_item['io_id']) ?>@2x.png 2x,/img/re/size_lable/<?= replace_hoching($cart_item['io_id']) ?>@3x.png 3x"> -->
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
                            <td></td>
                            <td colspan=4; style= "text-align: left;"> 
                            <div style="margin-bottom :20px; font-size: 14px; font-weight: 500; color: #606060;"> 옵션 <span style="margin-left :120px; font-size: 14px; color: #424242;"><?= $ct_option ?></span></div>
                            <div style="margin-bottom :20px; font-size: 14px; font-weight: 500; color: #606060;"> 만족도 <span style="margin-left :105px; font-size: 14px; color: #424242;"><?php echo str_repeat('★', $review['is_score']) ?></span></div>
                            <div style="margin-bottom :20px; font-size: 14px; font-weight: 500; color: #606060;"> 내용 <span style="margin-left :120px; font-size: 14px; color: #424242;"><?= $review['is_content'] ?></span></div>
                            <div><span style="margin-left :150px;">image</span></div>
                            </td>
                        </tr>
                    <? endfor ?>

<script>

$(".btn-write-review").on("click", function() {
        $("#is_id").val("");
        const it_id = $(this).data("it");
        const ct_id = $(this).data("ct");
        const review_type = $(this).data("type");
        let popupData = {};

        if (review_type) {
            if (review_type == "update") {
                $.get('/shop/ajax.review.php?it_id=' + it_id + '&ct_id=' + ct_id, function(response) {
                    if (response.result == true) {

                        updateUserRating(response.data['is_score'] * 14);
                        $("#is_id").val(response.data['is_id']);
                        $("#ct_id").val(response.data['ct_id']);
                        var option_text = response.data['is_subject'].split('/');
                        $("#review-content-subject").html(option_text[0]);
                        $("#review-content-option").html(option_text[1]);
                        $("#review-content-subject-mobile").html(option_text[0]);
                        $("#review-content-option-mobile").html(option_text[1]);
                        $("#review-content").html(response.data['is_content']);
                        $("#review-content_mobile").html(response.data['is_content']);
                        if (response.file.length > 0) {
                            $(response.file).each(function(fidx) {
                                const ffidx = 3 - (fidx * 1);
                                $("#imgimgFile" + ffidx).attr("src", "/data/file/itemuse/" + response.file[fidx]['file']).css("display", "inline-block");
                                $("#imgimgFile"+ffidx+"-mobile").attr("src", "/data/file/itemuse/" + response.file[fidx]['file']).css("display", "inline-block");
                            });

                        }
                        return writeReview(response.data['is_subject']);
                    } else {
                        if (response.msg == 'NOT_FOUND_MEMBER') {
                            return openLogin();
                        }
                        if (response.msg == 'NOT_FOUND_REVIEW') {
                            popupData.content = "주문 기록이 없습니다.";
                            return openPopup(popupData);
                        }
                    }
                }, "JSON");
            } else if (review_type == "delete") {
                $.get('/shop/ajax.review.php?it_id=' + it_id + '&ct_id=' + ct_id, function(response) {
                    if (response.result == true) {
                        $("#is_id").val(response.data['is_id']);
                    } else {
                        if (response.msg == 'NOT_FOUND_MEMBER') {
                            return openLogin();
                        }
                        if (response.msg == 'NOT_FOUND_REVIEW') {
                            popupData.content = "주문 기록이 없습니다.";
                            return openPopup(popupData);
                        }
                    }
                }, "JSON");
                let popupData = {
                    content: "삭제된 리뷰는 복구되지 않습니다.<br>그래도 삭제하시겠습니까?",
                    confirm: {
                        text: "삭제",
                        action: "deleteReview()"
                    }
                };

                openPopup(popupData, 'confirm');
            }
            return false;
        }

        if (it_id.length > 0) {
            $("#it_id").val(it_id);

            $.get('/shop/ajax.review.php?it_id=' + it_id + '&type=orderlist', function(response) {
                let popupData = {
                    content: ""
                };
                if (response.result == true) {
                    if (response.data.length > 1) {
                        if (ct_id > 0) {
                            $(response.data).each(function(idx) {
                                if (response.data[idx]['ct_id'] == ct_id) {
                                    $("#ct_id").val(response.data[idx]['ct_id']);

                                    return writeReview(response.data[idx]['subject'], true);
                                }
                            });
                        } else {
                            let selectContent = [];
                            $(response.data).each(function(idx) {
                                selectContent.push('<div class="custom-control custom-radio custom-control-inline"><input type="radio" class="custom-control-input review-select" id="review-select-' + idx + '" data-ct_id="' + response.data[idx]['ct_id'] + '" name="review-select" value="' + response.data[idx]['subject'] + '"><label class="custom-control-label" for="review-select-' + idx + '" style="line-height: 30px; padding-left: 4px;">' + response.data[idx]['subject'] + '</label></div>')
                            });
                            $("#modal-select-review-content").html(selectContent.join(""));
                            $("#modal-select-review-wrapper").modal("show");
                        }
                    } else {
                        $("#ct_id").val(response.data[0]['ct_id']);

                        return writeReview(response.data[0]['subject'], true);
                    }
                } else {
                    if (response.msg == 'NOT_FOUND_MEMBER') {
                        return openLogin();
                    }
                    if (response.msg == 'NOT_FOUND_ORDER') {
                        popupData.content = "주문 기록이 없습니다.";
                        return openPopup(popupData);
                    }
                }
            }, "JSON");
        }
    });
</script>