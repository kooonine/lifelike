<?php
ob_start();

?>
<link rel="stylesheet" href="/re/css/event.css">
<?php if (!empty($g5_banner['MAIN'])) : ?>
    <!--
    <div id="main-banner-wrapper">
        <? foreach ($g5_banner['MAIN'] as $bmain) : ?>
            <a href="<?= $bmain['ba_link'] ?>">
                <div class="main-banner" style="background-image: url(/data/banner/<?= $bmain['ba_image'] ?>);">
                    <div class="main-banner-subject" style="display: none;"><?= $bmain['ba_subject'] ?></div>
                    <div class="main-banner-content" style="display: none;"><?= $bmain['ba_content'] ?></div>
                </div>
            </a>
        <? endforeach ?>
    </div>
        -->
<?php endif ?>
<div class="on-big" style="float:left; margin-top: 60px;"></div>
<div id="event-wrapper">
    <? /* ?>
    <div style="margin: 40px 0 16px 0;">
        <a href="/event/list.php?pick=true&page=1"><button type="button" class="btn btn-black" style="font-size: 12px; margin-top: unset;">YOU PICK</button></a>
    </div>
    <?*/ ?>
    <div id="event-list-wrapper">
        <? for ($ci = 0; $citem = sql_fetch_array($db_event); $ci++) : ?>
            <a href="<?= empty($citem['cp_link']) ? "/campaign/view.php?cp_id=" . $citem['cp_id'] : $citem['cp_link'] ?>">
                <div class="event-list-item-wrapper">
                    <div class="event-list-item-image on-big" style="background-image: url(/data/banner/<?= $citem['cp_image_1'] ?>)">
                        <div style="height: 67px;">
                        <!-- <span class="btn-pick <?= in_array($citem['cp_id'], $g5_picked['EVENT']) ? "picked" : "" ?>" data-type="event" data-pick=<?= $citem['cp_id'] ?>></span> -->
                        </div>
                        <div style="height: calc(100% - 67px - 32px);"></div>
                        <div style="font-size: 0; text-align: right;">
                            <? if ((substr($citem['cp_end_date'], 0, 1) != "0") && strtotime($citem['cp_end_date']) > strtotime("Now")) : ?>
                                <span class="bagde-dday" data-enddate="<?= $citem['cp_end_date'] ?>">D - <?= abs(floor((strtotime("Now") - strtotime($citem['cp_end_date'])) / 60 / 60 / 24)) ?></span>
                            <? else : ?>
                                <span class="bagde-dday" data-enddate="<?= $citem['cp_end_date'] ?>">종료</span>
                            <? endif ?>
                        </div>
                    </div>
                    <div class="event-list-item-image on-small" style="background-image: url(/data/banner/<?= $citem['cp_image_2'] ?>)">
                        <div style="height: 67px;">
                        <!-- <span class="btn-pick <?= in_array($citem['cp_id'], $g5_picked['EVENT']) ? "picked" : "" ?>" data-type="event" data-pick=<?= $citem['cp_id'] ?>></span> -->
                        </div>
                        <div style="height: calc(100% - 67px - 32px);"></div>
                        <div style="font-size: 0; text-align: right;">
                            <? if ((substr($citem['cp_end_date'], 0, 1) != "0") && strtotime($citem['cp_end_date']) < strtotime("Now")) : ?>
                                <span class="bagde-dday" data-enddate="<?= $citem['cp_end_date'] ?>">D - <?= abs(floor((strtotime("Now") - strtotime($citem['cp_end_date'])) / 60 / 60 / 24)) ?></span>
                            <? endif ?>
                        </div>
                    </div>
                    <div class="event-list-item-subject">
                        <?= $citem['cp_subject'] ?>
                    </div>
                    <div class="event-list-item-desc">
                        <?= $citem['cp_desc'] ?>
                    </div>
                    <div class="event-list-item-date">
                        <?= substr($citem['cp_start_date'] , 0,11)   ?> ~ <?= substr($citem['cp_end_date'] ,0,11) ?>
                    </div>
                </div>
            </a>
        <? endfor ?>
    </div>
    <div id="add_list"></div>
    <?php if ($total_count > 9) : ?>
        <div class="on-small add_item_btn"><a onclick="addList_campaign(<?=$total_page?>)">더보기</a></div>
    <?endif?>
    <? if ($paging) : ?>
        <div class="on-big" style="margin-bottom: 170px;"><?= $paging ?></div>
    <? endif ?>
</div>

<script>
    let add_page = 2;
    function addList_campaign(totalPage){
        
        $.ajax({
            url:'/campaign/ajax.list.php',
            type:'post',
            data:{page : add_page},
            
            success:function(response){
                $('#add_list').append(response);
                add_page++;
            }
        });
        if (add_page >= totalPage) {
            $('.add_item_btn').css('display', 'none');
        }
    
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