<?php
include_once('./../common.php');


$page_noti = $_POST['page_faq'];
$filter_notice = $_POST['filter_faq'];


$sql_notice = "SELECT * FROM lt_write_notice WHERE wr_1='1'";
$sql_where_notice = array();
if (!empty($filter_notice)) $sql_where_notice[] = "ca_name='" . addslashes($filter_notice) . "'";
if (!empty($sql_where_notice)) $sql_notice .= " AND " . implode(' AND ', $sql_where_notice);

$db_count_noti = sql_query($sql_notice);

$perpage_noti = 10;
if ($page_noti > 1) $fr_noti = ($page_noti - 1) * $perpage_noti . ",";
$total_count_noti = $db_count_noti->num_rows;
$total_page_noti  = ceil($total_count_noti / $perpage_noti);

$qstr_noti = "filter_notice={$filter_notice}";
$paging_noti = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page_noti, $total_page_noti, $_SERVER['SCRIPT_NAME'] . '?' . $qstr_noti . '&amp;page=');

$sql_notice .= " ORDER BY wr_datetime DESC LIMIT {$fr_noti}{$perpage_noti}";
$db_notice = sql_query($sql_notice);


?>

<? for ($oi = 0; $notice_add = sql_fetch_array($db_notice); $oi++) : ?>
    <tr>
        <td class="on-big" style="cursor: pointer; text-align : center; padding-left: 0px;" onclick="openAnswer_noti(this)"><?= strip_tags($notice_add['ca_name']) ?></td>
        <td class="noti_title" onclick="openAnswer_noti(this)">
            <p class="wr_subject"><?= $notice_add['wr_subject'] ?></p>
            <p class="on-small"><?= date("Y.m.d", strtotime($notice_add['wr_datetime'])) ?></p>
        </td>
        <td class="on-big" style="font-size: 16px; font-weight: 600; color: #7f7f7f;"><?= date("Y.m.d", strtotime($notice_add['wr_datetime'])) ?></td>
    </tr>
    <tr class="noti-content">
        <td class="on-big"></td>
        <td colspan=2 class="on-big">
            <?= $notice_add['wr_content'] ?>
        </td>
        <td colspan=2 class="on-small">
            <?= $notice_add['wr_content_mobile'] ?>
        </td>
    </tr>
<? endfor ?>