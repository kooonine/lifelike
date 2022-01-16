<?php
include_once('./../common.php');
$sql_common = " from `{$g5['g5_shop_item_qa_table']}`";
$page = $_POST['page'];
// $sql_cnt_review_mobile = "SELECT COUNT(*) AS CNT FROM lt_shop_item_use WHERE mb_id='{$member['mb_id']}' ";
// $cnt_review_mobile = sql_fetch($sql_cnt_review_mobile);


$sql_cnt_order_mobile = "SELECT COUNT(*) AS CNT
              FROM {$g5['g5_shop_cart_table']} AS ct
              JOIN {$g5['g5_shop_item_table']} AS it ON ct.it_id=it.it_id
              LEFT JOIN {$g5['g5_shop_item_use_table']} AS its ON ct.it_id=its.it_id AND ct.ct_id=its.ct_id
              WHERE ct.mb_id='{$member['mb_id']}' AND ct_status IN ('구매완료','배송완료') AND its.is_id IS NULL";
$cnt_order_mobile = sql_fetch($sql_cnt_order_mobile);



// if ($repage > 1) $fr = ($repage - 1) * $perpage . ",";
$perpage = 5;
if ($page > 1) $fr = ($page - 1) * $perpage . ",";


$total_count = $cnt_order_mobile['CNT'];

$sql_order_mobile = "SELECT ct.*, it.it_brand, it.it_price AS org_price, it.it_discount_price AS discount_price
              FROM {$g5['g5_shop_cart_table']} AS ct
              JOIN {$g5['g5_shop_item_table']} AS it ON ct.it_id=it.it_id
              LEFT JOIN {$g5['g5_shop_item_use_table']} AS its ON ct.it_id=its.it_id AND ct.ct_id=its.ct_id
              WHERE ct.mb_id='{$member['mb_id']}' AND ct_status IN ('구매완료','배송완료') AND its.is_id IS NULL
              GROUP BY ct_id ORDER BY ct_time DESC LIMIT {$fr}{$perpage}";

$db_order_mobile = sql_query($sql_order_mobile);



//----------------------------------------


// $sql_review_mobile = "SELECT iu.it_id,iu.ct_id,iu.is_time,it.it_brand,it.it_discount_price, iu.is_score, iu.is_content FROM lt_shop_item_use AS iu JOIN {$g5['g5_shop_item_table']} AS it ON iu.it_id=it.it_id WHERE mb_id='{$member['mb_id']}' ORDER BY is_time DESC LIMIT {$fr}{$perpage}";
// $db_review_mobile = sql_query($sql_review_mobile);

$rows = 5;
$total_page  = ceil($total_count / $rows);  
if ($page < 1) { $page = 1; } 

?>


<? for ($oi = 0; $order = sql_fetch_array($db_order_mobile); $oi++) : ?>
    <?php
    $sql_cart_item = "SELECT it_name, ct_option, it_id, ct_keep_month, ct_id, ct_time, od_id, ct_price, ct_qty, ct_option,io_hoching FROM {$g5['g5_shop_cart_table']} WHERE ct_id='{$order['ct_id']}' ORDER BY io_type, ct_id LIMIT 1 ";
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
    $it_discount_price = $order['discount_price'];
    $it_sale_price = ($cart_item['ct_price'] + $order['discount_price']);
    if (!empty($it_sale_price)) {
        $discount_ratio = $order['discount_price'] / ($cart_item['ct_price'] + $order['discount_price']) * 100;
    }
    /// ------------------------------------가격--------------------------
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
        <td style="border-bottom: 1px solid #e0e0e0">
            <div style="position: relative; top: -10px;">
                <div style="font-size: 10px; color: #3a3a3a;"><?= $order['it_brand'] ?>
                <img src="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png" srcset="/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@2x.png 2x,/img/re/size_lable/<?= replace_hoching($cart_item['io_hoching']) ?>@3x.png 3x">
                </div>
                <div style="font-size: 12px; color: #3a3a3a;"><?= $it_name ?></div>
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