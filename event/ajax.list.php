<?php
include_once('./../common.php');

$sql_common = " FROM lt_event WHERE cp_use=1";
// if ($pick == "true") {
//     $sql_event_picked = "SELECT GROUP_CONCAT(it_id) AS picked FROM lt_shop_wish WHERE mb_id='{$member['mb_id']}' AND wi_type='event' GROUP BY mb_id";
//     $picked = sql_fetch($sql_event_picked);
//     $sql_common .= " AND cp_id IN ({$picked['picked']})";
// }
$page = $_POST['page'];

$perpage = 9;
if ($page > 1) $fr = ($page - 1) * $perpage . ",";
$sql_event = $sql_common . " ORDER BY cp_sort, cp_create_date DESC LIMIT {$fr}{$perpage}";
$add_event = sql_query("SELECT *" . $sql_event);

?>

<? for ($ci = 0; $citem = sql_fetch_array($add_event); $ci++) : ?>
    <a href="<?= empty($citem['cp_link']) ? "/event/view.php?cp_id=" . $citem['cp_id'] : $citem['cp_link'] ?>">
        <div class="event-list-item-wrapper">
            <div class="event-list-item-image" style="background-image: url(/data/banner/<?= $citem['cp_image_1'] ?>)">
                <div style="height: 67px;">
                <!-- <span class="btn-pick <?= in_array($citem['cp_id'], $g5_picked['EVENT']) ? "picked" : "" ?>" data-type="event" data-pick=<?= $citem['cp_id'] ?>></span> -->
                </div>
                <div style="height: calc(100% - 67px - 32px);"></div>
                <div style="font-size: 0; text-align: right;">
                    <? if ((substr($citem['cp_end_date'], 0, 1) != "0") && strtotime($citem['cp_end_date']) < strtotime("+14 day")) : ?>
                        <span class="bagde-dday" data-enddate="<?= $citem['cp_end_date'] ?>">D - <?= floor((strtotime("+14 day") - strtotime($citem['cp_end_date'])) / 60 / 60 / 24) ?></span>
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
