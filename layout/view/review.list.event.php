<?php
ob_start();
$reviewRank = " SELECT a.*, b.bf_file FROM lt_shop_item_use AS a LEFT JOIN lt_shop_item_use_file AS b ON (a.is_id = b.is_id)  WHERE is_confirm = 1 AND  b.bf_no = '0' AND is_time > '2021-11-01 00:00:00'  GROUP BY is_subject,mb_id ORDER BY ISNULL(is_rank) ASC, is_time DESC ";
$reviewResult = sql_query($reviewRank);

?>
<style>
.reviewEventDiv {
    width:100.5%; 
    height: 440px; 
    border: 1px solid #e5e5e5;
}
@media screen and (max-width: 1366px) {
  .reviewEventDiv {
    width:100.5%; 
    height: calc((100vw + 175px) / 2);
    border: 1px solid #e5e5e5;
  }
}    
</style>
<div class="event-item-set-wrapper">
    <? if (!empty($is_subject)) : ?>
        <div class="event-item-set-subject"><?= $is_subject ?></div>
    <? endif ?>
    <div class="product-list">
    <?php for ($i=0; $row=sql_fetch_array($reviewResult); $i++) {
        $itImg = sql_fetch(" SELECT it_img1 FROM lt_shop_item WHERE it_id = '{$row['it_id']}' LIMIT 1 ");
        $itImgUrl =  "/data/item/".$itImg['it_img1'];
        if (is_mobile()) {
            $filepath = G5_DATA_PATH . '/file/itemuse';
            $file = $row['bf_file'];
            $thumb = thumbnail($file, $filepath, $filepath, 172, 172, false, false, 'center', false, $um_value = '80/0.5/3');
            $thumb = "/data/file/itemuse/".$thumb; 
          } else {
            // $thumb = "/data/file/itemuse/".$row['bf_file']; 
            $thumb = "https://lifelikecdn.co.kr/data/file/itemuse/".$row['bf_file']; 
          }

        ?>
        <div class="product-list-item-wrapper event">
            <div class="reviewEventDiv">
                <a href="/shop/item.php?it_id=<?= $row['it_id']; ?>&is_id=<?= $row['is_id']; ?>">
                    <div class="review-list-item-thumb thumb-lazy" data-image="<?= $thumb ?>" style='background-image: url("<?= $thumb ?>");'>
        
                    </div>
                    <div style="height: 45px; border-bottom: 1px solid #e5e5e5; padding: 0px 2px 15px 2px; margin: -10px 10px; position: relative;">
                        <div style="font-size: 15px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?= $row['is_content']; ?></div>
                        <div style="font-size: 13px; color: #767676;"><?= mb_substr($row['is_name'],0,1,'utf-8');echo'**' ?></div>
                        <div style="font-size: 13px; color: #767676; position: absolute; text-align: right; top:22px; right: 0;"><?= substr($row['is_time'],0,10); ?></div>
                    </div>
                </a>
                    <div OnClick="location.href ='/shop/item.php?it_id=<?= $row['it_id']; ?>'" style="height: 45px; padding: 16px 2px 15px 2px; margin: -10px 10px; cursor: pointer;">
                        <div style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                        <img src='<?= $itImgUrl ?>' width="33" height="33">
                            <span style="font-size: 15px; margin-left: 5px;"> <?= $row['is_subject']; ?> </span>
                        </div>
                    </div>
            </div>
        </div>



    <?php } ?>



    </div>
</div>
<?php
$products = ob_get_contents();
ob_end_clean();
return $products;
?>