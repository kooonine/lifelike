<?php
include_once('./../common.php');




$sql_common = " from `{$g5['g5_shop_item_use_table']}` where it_id = '{$it_id}' and is_confirm = '1' ";

$page = $_POST['page'];
$it_id = $_POST['it_id'];

// 테이블의 전체 레코드수만 얻음
$sql = " select COUNT(*) as cnt
        ,sum(IF(is_age=20, is_score, 0)) as age20score
        ,sum(IF(is_age=30, is_score, 0)) as age30score
        ,sum(IF(is_age=40, is_score, 0)) as age40score
        ,sum(IF(is_age=50, is_score, 0)) as age50score
        ,sum(IF(is_age=20, 1, 0)) as age20
        ,sum(IF(is_age=30, 1, 0)) as age30
        ,sum(IF(is_age=40, 1, 0)) as age40
        ,sum(IF(is_age=50, 1, 0)) as age50 " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];
$age20cnt = $row['age20'];
$age30cnt = $row['age30'];
$age40cnt = $row['age40'];
$age50cnt = $row['age50'];
$age20score = ($row['age20score'] != 0) ? get_star($row['age20score'] / $row['age20']) : 0;
$age30score = ($row['age30score'] != 0) ? get_star($row['age30score'] / $row['age30']) : 0;
$age40score = ($row['age40score'] != 0) ? get_star($row['age40score'] / $row['age40']) : 0;
$age50score = ($row['age50score'] != 0) ? get_star($row['age50score'] / $row['age50']) : 0;
$best = array(
    20 => $age20score,
    30 => $age30score,
    40 => $age40score,
    50 => $age50score,
);

$best = arsort($best);
$ageBest = array_pop($best);

$perpage = 5;
if ($page > 1) $fr = ($page - 1) * $perpage . ",";

// $sql_review_photo = "SELECT * {$sql_common} AND is_type=1 ORDER BY is_id DESC";
// $db_review_photo = sql_query($sql_review_photo);
// $db_m_review_photo = sql_query($sql_review_photo);
// $count_best = $db_review_photo->num_rows;

$sql_a_review = "SELECT * {$sql_common} ORDER BY is_id DESC LIMIT {$fr}{$perpage}";
$db_a_review = sql_query($sql_a_review);

$thumbnail_width = 172;


?>


<? for ($ci = 0; $review_add = sql_fetch_array($db_a_review); $ci++) : ?>
    <?php
    $is_star    = get_star($review_add['is_score']);
    $is_name    = get_text($review_add['is_name']);
    $is_subject = conv_subject($review_add['is_subject'], 50, "…");
    //$is_content = ($review_add['wr_content']);
    $is_content = get_view_thumbnail(conv_content($review_add['is_content'], 1), $thumbnail_width);
    $tmp_options = explode("/", $review_add['is_subject']);
    $is_option = $tmp_options[1];

    $hash = md5($review_add['is_id'] . $review_add['is_time'] . $review_add['is_ip']);
    $rfile = $rmovie = array();
    $rfile_count = 0;
    $rmovie_count = 0;
    if ($review_add['is_file']) {
        $fi_sql = " select * from lt_shop_item_use_file where is_id = '" . $review_add['is_id'] . "' order by bf_no ";
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
    <tr class="product-review-list-title on-small"  onclick="mopenPhoto(this)">
        <td colspan=3 style="font-size: 0; padding: 10px; border: 1px solid #e0e0e0">
            <div><span class="product-info-review-stars"><span style="width: <?= $is_star * 20 ?>%; height: 18px;">&nbsp;</span></span></div>
            <div style="text-align: right; font-size: 12px; margin-top: -18px;"><?= get_star_string($review_add['is_name']) ?> <?= date("Y.m.d", strtotime($review_add['is_time'])) ?></div>
            <span style="width: calc(100vw - 114px); display: inline-block; vertical-align: top;">
                <div style="font-size: 12px;"><?= $is_option ?></div>
                <div style="font-size: 12px;"><?= $review_add['is_content'] ?></div>
            </span>
            <span class="img-spans" style="width: 45px; display: inline-block; vertical-align: top;">
                <?php foreach ($rfile as $rfno => $rf) : ?>
                    <span class="product-review-list-thumb  product-review-list-thumb-img" style="background-image: url(<?= $rf['src'] ?>); background-size: auto; width: 60px; height: 60px;"></span>
                    <? break; ?>
                <?php endforeach ?>
            </span>
            <div class="product-detail-review-photo on-small">
                <?php foreach ($rfile as $rfno => $rf) : ?>
                    <span class="product-review-list-thumb" style="background-image: url(<?= $rf['src'] ?>); background-size: cover; width: 100%; height: 250px;"></span>
                    <? break; ?>
                <?php endforeach ?>
            </div>
        </td>
    </tr>
<? endfor ?>
