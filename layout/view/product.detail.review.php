<?php
$sql_review_points = "SELECT cf_review_write_point,cf_review_photo_point,cf_review_first_point FROM lt_config";
$review_points = sql_fetch($sql_review_points);

$reviewCh = "SELECT io_order_no FROM lt_shop_item_option WHERE it_id ='{$it_id}' LIMIT 1";
$orderNo = sql_fetch($reviewCh);
$reviewCh2 = "SELECT Distinct(it_size) AS size FROM lt_shop_item_use WHERE io_order_no ='{$orderNo['io_order_no']}' ORDER BY size DESC";
$reviewCh2_1 = sql_query($reviewCh2);
$reviewCh2_2 = sql_query($reviewCh2);
?>

<div class="product-detail-subtitle-wrapper" style="">
    <span class="product-detail-subtitle product-detail-subtitle-review">
        REVIEW(<?= number_format($total_count) ?>) <span class="product-info-review-stars on-big" style="width: 130px; background-size: 130px; margin-left: 8px;"><span style="width: <?= $star_score * 20 ?>%; background-size: 130px;">&nbsp;</span></span>
        <div class ="on-big">
        <?php if ($total_count > 0) : ?>
            <input type="radio" id ="reAll" name="sizePickrev" class ="sizeReview"  value ="" checked="checked"><label for="reAll"><div>전체</div></label>
            <? for ($rd = 0; $rrow = sql_fetch_array($reviewCh2_1); $rd++) :?>
                <input type="radio" id ="re<?= $rrow['size']?>" name="sizePickrev" class ="sizeReview" value ="<?= $rrow['size']?>" ><label for="re<?= $rrow['size']?>"><div><?= $rrow['size'] ?></div></label>
            <? endfor ?>
        <? endif ?>
        </div>
    </span>
    <span class="product-detail-subtitle-action on-big" style="float: right;">
        <button type="button" class="btn btn-black btn-write-review" style="margin-top: 0; font-size: 14px; display:none;" data-it="<?= $it_id ?>">리뷰쓰기</button>
    </span>
    <div class="product-info-review-stars on-small" style="width: 70px; background-size: 70px; display: block;"><span style="width: <?= $star_score * 20 ?>%; background-size: 70px;">&nbsp;</span></div>
    <div class ="on-small">
        <?php if ($total_count > 0) : ?>
        <input type="radio" id ="reMoAll" name="sizePickrev" class ="sizeReviewMo"  value ="" checked="checked"><label for="reMoAll"><div>전체</div></label>
            <? for ($rd2 = 0; $rrow2 = sql_fetch_array($reviewCh2_2); $rd2++) :?>
                <input type="radio" id ="reMo<?= $rrow2['size']?>" name="sizePickrev" class ="sizeReviewMo" value ="<?= $rrow2['size']?>" ><label for="reMo<?= $rrow2['size']?>"><div><?= $rrow2['size'] ?></div></label>
            <? endfor ?>
        <? endif ?>
    </div>
</div>
<?php if ($total_count > 0) : ?>
    <div class="product-detail-review on-big">
        <?php if ($count_best > 0) : ?>
            <div class="swiper-container swiper_items_area" id="pc_review_photo">
                <div id="product-review-best-wrapper" class="swiper-wrapper">

                    <? for ($bi = 0; $brow = sql_fetch_array($db_review_photo); $bi++) : ?>
                        <?php
                        $is_star    = get_star($brow['is_score']);
                        $is_name    = get_text($brow['is_name']);
                        $is_subject = conv_subject($brow['is_subject'], 50, "…");
                        $is_content = get_view_thumbnail(conv_content($brow['is_content'], 1), $thumbnail_width);

                        $file_count = 0;
                        $src = '';
                        if ($brow['is_file']) {
                            $fi_sql = " select * from lt_shop_item_use_file where is_id = '" . $brow['is_id'] . "' order by bf_no ";
                            $fi_result = sql_query($fi_sql);
                            while ($fi_row = sql_fetch_array($fi_result)) {
                                $filepath = G5_DATA_PATH . '/file/itemuse';
                                $no = $fi_row['bf_no'];
                                if ($fi_row['bf_type'] != '0') {
                                    $file[$no]['path'] = G5_DATA_URL . '/file/itemuse';
                                    $file[$no]['size'] = get_filesize($fi_row['bf_filesize']);
                                    $file[$no]['datetime'] = $fi_row['bf_datetime'];
                                    $file[$no]['source'] = addslashes($fi_row['bf_source']);
                                    $file[$no]['file'] = $fi_row['bf_file'];
                                    $file[$no]['image_width'] = $fi_row['bf_width'] ? $fi_row['bf_width'] : 640;
                                    $file[$no]['image_height'] = $fi_row['bf_height'] ? $fi_row['bf_height'] : 480;
                                    $file[$no]['image_type'] = $fi_row['bf_type'];

                                    $thumb = thumbnail($file[$no]['file'], $filepath, $filepath, $thumbnail_width, $thumbnail_width, false, false, 'center', false, $um_value = '80/0.5/3');
                                    $file[$no]['thumb'] = $thumb;

                                    $src = G5_DATA_URL . '/file/itemuse/' . $fi_row['bf_file'];
                                    break;
                                }
                            }
                        }
                        ?>
                        <!-- <span class="swiper-slide product-review-best-thumb" onclick=mo_photo_review() style="background-image: url(<?= $src ?>);" data-id="<?= $brow['is_id'] ?>"></span> -->
                    <? endfor ?>
                </div>
                <?php if ($count_best > 6) : ?>
                <div class="on-big swiper-button-next swiper-button-black"></div>
                <div class="on-big swiper-button-prev swiper-button-black"></div>
                <? endif ?>
            </div>
            <script>
                var swiper_review_photo = new Swiper('#pc_review_photo', {
                    slidesPerView: 6,
                    spaceBetween: 10,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                });
            </script>
        <?php endif ?>
        <div id="product-review-list-wrapper">
            <table>
                <?php for ($ri = 0; $rrow = sql_fetch_array($db_review); $ri++) : ?>
                    <?php
                    $is_star    = get_star($rrow['is_score']);
                    $is_name    = get_text($rrow['is_name']);
                    $is_subject = conv_subject($rrow['is_subject'], 50, "…");
                    //$is_content = ($rrow['wr_content']);
                    $is_content = get_view_thumbnail(conv_content($rrow['is_content'], 1), $thumbnail_width);
                    $tmp_options = explode("/", $rrow['is_subject']);
                    $is_option = $tmp_options[1];

                    $hash = md5($rrow['is_id'] . $rrow['is_time'] . $rrow['is_ip']);
                    $rfile = $rmovie = array();
                    $rfile_count = 0;
                    $rmovie_count = 0;
                    if ($rrow['is_file']) {
                        $fi_sql = " select * from lt_shop_item_use_file where is_id = '" . $rrow['is_id'] . "' order by bf_no ";
                        $fi_result = sql_query($fi_sql);
                        while ($fi_row = sql_fetch_array($fi_result)) {
                            $filepath = G5_DATA_PATH . '/file/itemuse';
                            $no = $fi_row['bf_no'];

                            if ($fi_row['bf_type'] == '0') {
                                //movie
                                $rmovie[$no]['path'] = G5_DATA_URL . '/file/itemuse';
                                $rmovie[$no]['size'] = get_filesize($fi_row['bf_filesize']);
                                $rmovie[$no]['datetime'] = $fi_row['bf_datetime'];
                                $rmovie[$no]['source'] = addslashes($fi_row['bf_source']);
                                $rmovie[$no]['file'] = $fi_row['bf_file'];
                                $rmovie_count++;
                            } else {
                                $rfile[$no]['path'] = G5_DATA_URL . '/file/itemuse';
                                $rfile[$no]['size'] = get_filesize($fi_row['bf_filesize']);
                                $rfile[$no]['datetime'] = $fi_row['bf_datetime'];
                                $rfile[$no]['source'] = addslashes($fi_row['bf_source']);
                                $rfile[$no]['file'] = $fi_row['bf_file'];
                                $rfile[$no]['image_width'] = $fi_row['bf_width'] ? $fi_row['bf_width'] : 640;
                                $rfile[$no]['image_height'] = $fi_row['bf_height'] ? $fi_row['bf_height'] : 480;
                                $rfile[$no]['image_type'] = $fi_row['bf_type'];
                                $rfile[$no]['thumb'] = thumbnail($rfile[$no]['file'], $filepath, $filepath, $thumbnail_width, $thumbnail_width, false, false, 'center', false, $um_value = '80/0.5/3');
                                $rfile[$no]['src'] = G5_DATA_URL . '/file/itemuse/' . $rfile[$no]['file'];
                                $file_count++;
                            }
                        }
                        ?>
                        <tr class="product-review-list-title on-big totalRe_<?= $rrow['it_size']?>" id ="reviewOpen_<?= $rrow['is_id'] ?>">
                        <?
                    } else {
                        ?>
                        <tr class="product-review-list-title2 on-big totalRe_<?= $rrow['it_size']?>" id ="reviewOpen_<?= $rrow['is_id'] ?>" style="cursor: default;">
                        <?
                    }
                    ?>
                        <td class="list-cell-1">
                            <div><span class="product-info-review-stars"><span style="width: <?= $is_star * 20 ?>%">&nbsp;</span></span></div>
                            <div><?= date("Y.m.d", strtotime($rrow['is_time'])) ?></div>
                            <div style="font-size: 15px; font-weight: 500;">사이즈: <?= $rrow['it_size']?></div>
                        </td>
                        <td class="list-cell-2">
                            <div><?= $is_option ?></div>
                            <div><?= $rrow['is_content'] ?></div>
                            <div class="product-review-list-item">
                                <?php foreach ($rfile as $rfno => $rf) : ?>
                                    <img style="max-height: 700px;" src="<?= $rf['src'] ?>" alt="">
                                    <!-- <span class="product-review-list-thumb-big" style="background-image: url(<?= $rf['src'] ?>);"></span> -->
                                    <!-- <span class="product-review-list-thumb-big" style="background-image: url(<?= $rf['src'] ?>);"></span> -->
                                <?php endforeach ?>
                            </div>
                        </td>
                        <td class="list-cell-3" style="padding: 2px 0px 2px">
                            <?php foreach ($rfile as $rfno => $rf) : ?>
                                <span class="product-review-list-thumb" style="background-image: url(<?= $rf['src'] ?>); background-size: 200px;"></span>
                            <?php endforeach ?>
                        </td>
                    </tr>
                <? endfor ?>
            </table>
            <?php if ($total_count > 5) : ?>
                <? if ($paging) : ?>
                    <div class="page-margin-bottom review-page on-big"><?= $paging ?></div>
                <? endif ?>
            <?php endif ?>
        </div>
    </div>
    <!-- 모바일 -->
    <div class="product-detail-review on-small">
        <?php if ($count_best > 0) : ?>
            <div class="swiper-container mo_review_photo">
                <!-- <div class="swiper-wrapper" style="overflow:unset; height : 80px;">
                    <? for ($bi = 0; $brow = sql_fetch_array($db_m_review_photo); $bi++) : ?>
                        <?php
                        $is_star    = get_star($brow['is_score']);
                        $is_name    = get_text($brow['is_name']);
                        $is_subject = conv_subject($brow['is_subject'], 50, "…");
                        $is_content = get_view_thumbnail(conv_content($brow['is_content'], 1), $thumbnail_width);

                        $file_count = 0;
                        $src = '';
                        if ($brow['is_file']) {
                            $m_fi_sql = " select * from lt_shop_item_use_file where is_id = '" . $brow['is_id'] . "' order by bf_no ";
                            $m_fi_result = sql_query($m_fi_sql);
                            while ($fi_row = sql_fetch_array($m_fi_result)) {
                                $filepath = G5_DATA_PATH . '/file/itemuse';
                                $no = $fi_row['bf_no'];
                                if ($fi_row['bf_type'] != '0') {
                                    $file[$no]['path'] = G5_DATA_URL . '/file/itemuse';
                                    $file[$no]['size'] = get_filesize($fi_row['bf_filesize']);
                                    $file[$no]['datetime'] = $fi_row['bf_datetime'];
                                    $file[$no]['source'] = addslashes($fi_row['bf_source']);
                                    $file[$no]['file'] = $fi_row['bf_file'];
                                    $file[$no]['image_width'] = $fi_row['bf_width'] ? $fi_row['bf_width'] : 80;
                                    $file[$no]['image_height'] = $fi_row['bf_height'] ? $fi_row['bf_height'] : 80;
                                    $file[$no]['image_type'] = $fi_row['bf_type'];

                                    $thumb = thumbnail($file[$no]['file'], $filepath, $filepath, $thumbnail_width, $thumbnail_width, false, false, 'center', false, $um_value = '80/0.5/3');
                                    $file[$no]['thumb'] = $thumb;

                                    $src = G5_DATA_URL . '/file/itemuse/' . $fi_row['bf_file'];
                                    break;
                                }
                            }
                        }
                        ?>
                        <div class="swiper-slide product-review-best-thumb" onclick=mo_photo_review() style="background-image: url(<?= $src ?>); width : 80px; height:80px;" data-id="<?= $brow['is_id'] ?>"></div>
                    <? endfor ?>
                </div> -->
            </div>
            <script>
                var swiper_review_photo = new Swiper('.mo_review_photo', {
                    slidesPerView: 3.5,
                    spaceBetween: 10,

                });
            </script>
        <?php endif ?>
        <div id="product-review-list-wrapper" style="">
            <table id="review-list-table" style="border-collapse: separate;    border-spacing: 0px 10px;">
                <?php for ($ri = 0; $rrow = sql_fetch_array($db_m_review); $ri++) : ?>
                    <?php
                    $is_star    = get_star($rrow['is_score']);
                    $is_name    = get_text($rrow['is_name']);
                    $is_subject = conv_subject($rrow['is_subject'], 50, "…");
                    //$is_content = ($rrow['wr_content']);
                    $is_content = get_view_thumbnail(conv_content($rrow['is_content'], 1), $thumbnail_width);
                    $tmp_options = explode("/", $rrow['is_subject']);
                    $is_option = $tmp_options[1];

                    $hash = md5($rrow['is_id'] . $rrow['is_time'] . $rrow['is_ip']);
                    $rfile = $rmovie = array();
                    $rfile_count = 0;
                    $rmovie_count = 0;
                    if ($rrow['is_file']) {
                        $fi_sql = " select * from lt_shop_item_use_file where is_id = '" . $rrow['is_id'] . "' order by bf_no ";
                        $fi_result = sql_query($fi_sql);
                        while ($fi_row = sql_fetch_array($fi_result)) {
                            $filepath = G5_DATA_PATH . '/file/itemuse';
                            $no = $fi_row['bf_no'];

                            if ($fi_row['bf_type'] == '0') {
                                //movie
                                $rmovie[$no]['path'] = G5_DATA_URL . '/file/itemuse';
                                $rmovie[$no]['size'] = get_filesize($fi_row['bf_filesize']);
                                $rmovie[$no]['datetime'] = $fi_row['bf_datetime'];
                                $rmovie[$no]['source'] = addslashes($fi_row['bf_source']);
                                $rmovie[$no]['file'] = $fi_row['bf_file'];
                                $rmovie_count++;
                            } else {
                                $rfile[$no]['path'] = G5_DATA_URL . '/file/itemuse';
                                $rfile[$no]['size'] = get_filesize($fi_row['bf_filesize']);
                                $rfile[$no]['datetime'] = $fi_row['bf_datetime'];
                                $rfile[$no]['source'] = addslashes($fi_row['bf_source']);
                                $rfile[$no]['file'] = $fi_row['bf_file'];
                                $rfile[$no]['image_width'] = $fi_row['bf_width'] ? $fi_row['bf_width'] : 90;
                                $rfile[$no]['image_height'] = $fi_row['bf_height'] ? $fi_row['bf_height'] : 90;
                                $rfile[$no]['image_type'] = $fi_row['bf_type'];
                                $rfile[$no]['thumb'] = thumbnail($rfile[$no]['file'], $filepath, $filepath, $thumbnail_width, $thumbnail_width, false, false, 'center', false, $um_value = '80/0.5/3');
                                $rfile[$no]['src'] = G5_DATA_URL . '/file/itemuse/' . $rfile[$no]['thumb'];
                                $file_count++;
                            }
                        }
                    }
                     ?>
                    <tr class="product-review-list-title on-small totalRe_<?= $rrow['it_size']?>" id ="reviewOpen2_<?= $rrow['is_id'] ?>">
                        <td colspan=3 style="font-size: 0; padding: 10px; border: 1px solid #e0e0e0">
                        <input type="radio" id ="mo_<?= $rrow['it_size']?>"  class ="sizeReviewBodyMo" disabled><label style="font-size: 13px;" for="mo_<?= $rrow['it_size']?>" disabled><?= $rrow['it_size']?></label>
                            <div style="margin-top: -21px; margin-left: 41px;"><span class="product-info-review-stars"><span style="width: <?= $is_star * 20 ?>%; height: 18px;">&nbsp;</span></span></div>
                            <div style="text-align: right; font-size: 12px; margin-top: -18px;"><?= get_star_string($rrow['is_name']) ?> <?= date("Y.m.d", strtotime($rrow['is_time'])) ?></div>
                            <span style="width:100%; display: inline-block; vertical-align: top;">
                                <div style="font-size: 12px;"><?= $is_option ?></div>
                                <div style="font-size: 12px; margin-top: 5px"><?= $rrow['is_content'] ?></div>
                            </span>
                            <!-- <span class="img-spans" style="width: 45px; display: inline-block; vertical-align: top;"> -->
                                <!-- <?php foreach ($rfile as $rfno => $rf) : ?> -->
                                    <!-- <span class="product-review-list-thumb  product-review-list-thumb-img" style="background-image: url(<?= $rf['src'] ?>); background-size: auto; width: 60px; height: 60px;"></span> -->
                                    <!-- <? break; ?> -->
                                <!-- <?php endforeach ?> -->
                            <!-- </span> -->
                            <div class="product-detail-review-photo on-small">
                                <?php foreach ($rfile as $rfno => $rf) : ?>
                                    <span class="product-review-list-thumb" style="background-image: url(<?= $rf['src'] ?>); background-size: cover; width: 100%; height: 250px;"></span>
                                <?php endforeach ?>
                            </div>
                        </td>
                    </tr>
                <? endfor ?>
            </table>
            <?php if ($total_count > 5) : ?>
                <div class="on-small add_item_btn"><a onclick="addList(<?= $total_count ?>)">더보기</a></div>
            <?php endif ?>
        </div>
    </div>
<?php else : ?>
    <div class="product-detail-review no-content">
        등록된 리뷰가 없습니다.<br>
        최초 상품리뷰를 작성하고 최대 <span style="color : #f93f00"> <?= number_format($review_points['cf_review_first_point']) ?> P</span> 를 적립하세요.
    </div>
<?php endif ?>
<div class="modal fade" id="mo_photo_review_view" tabindex="-1" role="dialog" aria-labelledby="mo_photo_review_view">
    <div class="modal-dialog  modal-xl" role="document">
        <div class="modal-content">
            <div class="modal_header">포토리뷰
                <img src="/img/re/cancle.png" srcset="/img/re/cancle@2x.png 2x,/img/re/cancle@3x.png 3x" data-dismiss="modal">
            </div>
            <div class="modal-body">

                <div class=" swiper-container" id="photo_review_modal" style="width:100%">
                    <div class="swiper-wrapper" style="overflow:unset">
                        <? for ($bi = 0; $rrow = sql_fetch_array($db_modal_review_photo); $bi++) : ?>
                            <?php
                            $is_star    = get_star($rrow['is_score']);
                            $is_name    = get_text($rrow['is_name']);
                            $is_subject = conv_subject($rrow['is_subject'], 50, "…");
                            //$is_content = ($rrow['wr_content']);
                            $is_content = get_view_thumbnail(conv_content($rrow['is_content'], 1), $thumbnail_width);
                            $tmp_options = explode("/", $rrow['is_subject']);
                            $is_option = $tmp_options[1];

                            $hash = md5($rrow['is_id'] . $rrow['is_time'] . $rrow['is_ip']);
                            $rfile = $rmovie = array();
                            $rfile_count = 0;
                            $rmovie_count = 0;
                            if ($rrow['is_file']) {
                                $fi_sql = " select * from lt_shop_item_use_file where is_id = '" . $rrow['is_id'] . "' order by bf_no ";
                                $fi_result = sql_query($fi_sql);
                                while ($fi_row = sql_fetch_array($fi_result)) {
                                    $filepath = G5_DATA_PATH . '/file/itemuse';
                                    $no = $fi_row['bf_no'];

                                    if ($fi_row['bf_type'] == '0') {
                                        //movie
                                        $rmovie[$no]['path'] = G5_DATA_URL . '/file/itemuse';
                                        $rmovie[$no]['size'] = get_filesize($fi_row['bf_filesize']);
                                        $rmovie[$no]['datetime'] = $fi_row['bf_datetime'];
                                        $rmovie[$no]['source'] = addslashes($fi_row['bf_source']);
                                        $rmovie[$no]['file'] = $fi_row['bf_file'];
                                        $rmovie_count++;
                                    } else {
                                        $rfile[$no]['path'] = G5_DATA_URL . '/file/itemuse';
                                        $rfile[$no]['size'] = get_filesize($fi_row['bf_filesize']);
                                        $rfile[$no]['datetime'] = $fi_row['bf_datetime'];
                                        $rfile[$no]['source'] = addslashes($fi_row['bf_source']);
                                        $rfile[$no]['file'] = $fi_row['bf_file'];
                                        $rfile[$no]['image_width'] = $fi_row['bf_width'] ? $fi_row['bf_width'] : 500;
                                        $rfile[$no]['image_height'] = $fi_row['bf_height'] ? $fi_row['bf_height'] : 500;
                                        $rfile[$no]['image_type'] = $fi_row['bf_type'];
                                        $rfile[$no]['thumb'] = thumbnail($rfile[$no]['file'], $filepath, $filepath, 500, 500, false, false, 'center', false, $um_value = '800/0.50/3');
                                        $rfile[$no]['src'] = G5_DATA_URL . '/file/itemuse/thumb-2887057409_uvGTQH7t_b4b2cb2047b7f0d1596b9ea388a6ccfa50c6037b';
                                        $file_count++;
                                    }
                                }
                            }
                            ?>
                            <div class="swiper-slide">
                                <div style="background-image: url(<?= $src ?>);"><img style="width:100%; height : 200px;" src="<?= $src ?>"></div>

                                <div style="margin-top: 34px; border: solid 1px #e0e0e0; padding : 8px;">
                                    <div><span class="product-info-review-stars"><span style="width: <?= $is_star * 20 ?>%;">&nbsp;</span></span><span style="font-size: 12px;  font-weight: normal;  line-height: normal;  color: #959595; float:right;"><?= get_star_string($rrow['is_name']) ?> <?= date("Y.m.d", strtotime($rrow['is_time'])) ?></span></div>
                                    <div style="font-size: 12px; margin-top:16px; font-weight: normal;  line-height: normal;  color: #959595;"><?= $is_option ?></div>
                                    <div style="font-size: 12px;  font-weight: 500;  line-height: normal;  color: #333333;"><?= $rrow['is_content'] ?></div>
                                </div>
                            </div>
                        <? endfor ?>
                    </div>

                    <div class="swiper-pagination swiper-photo-view-paging swiper-pagination-black" style="bottom : 130px;"></div>
                </div>
            </div>
            <script>

            </script>
        </div>
    </div>
</div>

<input type="hidden" id="review_it_id_hi" value="<?= $it_id ?>">

<style>
    #mo_photo_review_view .modal-content {
        width: 940px;
        height: 530px;
        margin: 0;
        padding: 0;
    }

    #mo_photo_review_view .modal-dialog {
        padding-left: 0px;
        bottom: 0;
        margin: 0 auto;
        padding: 0;
        width: 940px;
        ;
        margin-top: 200px;
    }

    #mo_photo_review_view .modal-content {
        border-radius: 2px;
    }

    #mo_photo_review_view .modal_header {
        height: 50px;
        line-height: 50px;
        text-align: center;
        font-size: 18px;
        font-weight: 500;
        color: #090909;
        position: relative;
        border-bottom: 1px solid #e0e0e0;
    }

    #mo_photo_review_view .modal_header img {
        position: absolute;
        top: 50%;
        right: 7px;
        transform: translate(-50%, -50%);
    }

    #mo_photo_review_view .swiper-wrapper {
        width: 900px;
        height: 610px;
    }

    .product-detail-review-photo {
        display: none;
        font-size: 20px;
    }

    .product-detail-review-photo.active {
        display: block;
    }

    .product-review-list-title.on-small.active .product-review-list-thumb-img {
        display: none;
    }

    @media (max-width: 1366px) {
        #mo_photo_review_view .modal-content {
            width: 100%;
            height: 530px;
            margin: 0;
            padding: 0;
        }

        #mo_photo_review_view .modal-dialog {
            padding-left: 0px;
            position: fixed;
            bottom: 0;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        #mo_photo_review_view .modal-content {
            border-radius: 24px 24px 0 0;
        }

        #mo_photo_review_view .modal_header {
            height: 50px;
            line-height: 50px;
            text-align: center;
            font-size: 18px;
            font-weight: 500;
            color: #090909;
            position: relative;
            border-bottom: 1px solid #e0e0e0;
        }

        #mo_photo_review_view .modal_header img {
            position: absolute;
            top: 50%;
            right: 7px;
            transform: translate(-50%, -50%);
        }

        .product-detail-review-photo {
            display: block;
            font-size: 20px;
        }

        .product-detail-review-photo.active {
            display: block;
        }

        .product-review-list-title.on-small.active .product-review-list-thumb-img {
            display: none;
        }
    }
</style>

<script>
    $(document).ready(function() {
        setTimeout(function(){
            let reUrl = window.location.search;
            if (reUrl.indexOf('is_id') != -1) {
                let urlArray = reUrl.split('is_id='); 
                let offset = $("#reviewOpen_"+urlArray[1]).offset();
                if (offset.top == 0 || !offset.top) {
                    offset = $("#reviewOpen2_"+urlArray[1]).offset();
                    $('html, body').scrollTop(offset.top-80);
                }  else {
                    $("#reviewOpen_"+urlArray[1]).addClass("active");
                    $('html, body').scrollTop(offset.top-250);   
                }

            }
        }, 800);
    })

    function mo_photo_review() {
        $('#btn-order-mobile').css('display', 'none');
        $('#mo_photo_review_view').modal('show');
        setTimeout(function() {
            var swiper_p_review = new Swiper('#photo_review_modal', {
                pagination: {
                    el: '.swiper-photo-view-paging',
                }
            });
        }, 500);

    }
    $("#mo_photo_review_view").on('hide.bs.modal', function(e) {
        $('#btn-order-mobile').css('display', 'block');
    });

    $(".product-review-list-title").on("click", function() {
        let has = $(this).hasClass("active");
        if (!has) {
            $(".product-review-list-title").removeClass("active");
            $(this).addClass("active");
        } else {
            $(".product-review-list-title").addClass("active");
            $(this).removeClass("active");
        }

    });

    function mopenPhoto(elem) {
        if ($(elem).children().children(".product-detail-review-photo.on-small").hasClass("active") === true) {
            $(elem).children().children(".product-detail-review-photo.on-small").removeClass("active");
            $(elem).children().children(".img-spans").children().css('display', 'inline-block');
        } else {
            $(".product-detail-review-photo.on-small").removeClass("active");
            $(elem).children().children(".img-spans").children().css('display', 'none');
            $(elem).children().children(".product-detail-review-photo.on-small").addClass("active");
        }
    }

    let add_review_page = 2;

    function addList(totalPage) {
        var it_id = $('#review_it_id_hi').val();
        $.ajax({
            url: '/ajax_front/ajax.review.php',
            type: 'post',
            data: {
                page: add_review_page,
                it_id: it_id
            },

            success: function(response) {
                $('#review-list-table tbody').append(response);
                add_review_page++;
            }
        });

        if ((add_review_page * 5) >= totalPage) {
            $('.add_item_btn').css('display', 'none');
        }
    }
    $("input:radio[name=sizePickrev]").click(function(){ 
        let reVal = this.value;
        if (reVal =='') {
            $("tr[class*='totalRe_']").attr('style', "display:'';");
        } else {
            $("tr[class*='totalRe_']").attr('style', "display:none;");
            $('.totalRe_'+reVal).attr('style', "display:'';");  
        }
    });

</script>
<?php
include_once G5_LAYOUT_PATH . "/modal.review.php";
?>