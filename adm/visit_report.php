<?php
include_once('./_common.php');
if (!defined('_GNUBOARD_')) exit;

$g5['title'] = '기간별 현황';
$sub_menu = "200800";
auth_check($auth[substr($sub_menu,0,2)], 'r');

include_once(G5_LIB_PATH . '/visit.lib.php');
include_once('./admin.head.php');
include_once(G5_PLUGIN_PATH . '/jquery-ui/datepicker.php');

if (empty($fr_date) || !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = date("Y-m-d", strtotime(G5_TIME_YMD) - 3600 * 24 * 6);
if (empty($to_date) || !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = G5_TIME_YMD;
$rtypes = array('date', 'week', 'weekday', 'month', 'year');

if (empty($rtype) || !in_array($rtype, $rtypes)) {
    $rtype = "week";
}

// $qstr = "fr_date=" . $fr_date . "&amp;to_date=" . $to_date;
$qstr = sprintf("fr_date=%s&amp;to_date=%s&amp;percent=%s", $fr_date, $to_date, $percent);
$query_string = $qstr ? '?' . $qstr : '';

$colspan = 4;

$max = 0;
$sum_count = 0;

$sql_prefix = "SELECT SUM(IF(LEFT(vi_device,6)='Mobile', 1, 0)) AS count_mobile, SUM(IF(vi_stay / 60000 <= 1, 1, 0)) AS count_stay_1, SUM(IF(vi_stay / 60000 >= 10, 1, 0)) AS count_stay_10, vi_date AS date, MIN(vi_date) AS startdate, MAX(vi_date) AS enddate, YEAR(vi_date) AS year,LEFT(vi_date, 7) AS month, CONCAT(YEAR(vi_date), '-', WEEK(vi_date, 1)) AS week, WEEKDAY(vi_date) AS weekday, COUNT(*) AS count_total FROM lt_visit_page";
$sql_postfix = " ORDER BY vi_date DESC";
$sql_where = " WHERE vi_date BETWEEN '{$fr_date}' AND '{$to_date}'";

$sql_report = $sql_prefix . $sql_where . " GROUP BY {$rtype}" . $sql_postfix;

$result = array();
$report = sql_query($sql_report);
for ($i = 0; $row = sql_fetch_array($report); $i++) {
    $sql_inner_date = " BETWEEN '{$row['startdate']} 00:00:00' AND '{$row['enddate']} 23:59:59'";
    $sql_user = "SELECT COUNT(*) AS count_user, SUM(IF(vi_new=1,1,0)) AS count_new FROM lt_visit WHERE vi_date" . $sql_inner_date;
    $sql_order = "SELECT SUM(IF(od_type='O',1,0)) AS count_o,SUM(IF(od_type='R',1,0)) / 2 AS count_r,SUM(IF(od_type='O',od_receipt_price,0)) AS sum_o FROM lt_shop_order WHERE od_status != '주문취소' AND od_time" . $sql_inner_date;
    $sql_join = "SELECT COUNT(*) AS count_join FROM lt_member WHERE mb_datetime" . $sql_inner_date;

    $db_user = sql_fetch($sql_user);
    $db_order = sql_fetch($sql_order);
    $db_join = sql_fetch($sql_join);
    $tmp_row = $row;

    $tmp_row['count_join'] = $db_join['count_join'];
    $tmp_row['count_user'] = $db_user['count_user'];
    $tmp_row['count_new'] = $db_user['count_new'];
    $tmp_row['count_o'] = $db_order['count_o'];
    $tmp_row['count_r'] = $db_order['count_r'];
    $tmp_row['sum_o'] = $db_order['sum_o'];

    $result[] = $tmp_row;
    unset($tmp_row);
}

// dd($result);
?>

<form name="fvisit" id="fvisit" class="local_sch03 local_sch" method="get">
    <div class="sch_last">
        <strong>기간별검색</strong>
        <input type="text" name="fr_date" value="<?php echo $fr_date ?>" id="fr_date" class="frm_input" size="11" maxlength="10">
        <label for="fr_date" class="sound_only">시작일</label>
        ~
        <input type="text" name="to_date" value="<?php echo $to_date ?>" id="to_date" class="frm_input" size="11" maxlength="10">
        <label for="to_date" class="sound_only">종료일</label>
        <input type="submit" value="검색" class="btn_submit">
        <span><input type="checkbox" id="percent" name="percent" style="width: 20px; height: 20px; vertical-align: text-bottom; margin: 0 4px;" <?= !empty($percent) ? "checked" : "" ?>><label for="percent">비율 표시</label></span>
    </div>
    <input type="hidden" name="rtype" value="<?= $rtype ?>">
</form>
<ul class="anchor">
    <li><a href="./visit_report.php<?= empty($query_string) ? "?rtype=week" : $query_string . "&amp;rtype=week" ?>">주간</a></li>
    <li><a href="./visit_report.php<?= empty($query_string) ? "?rtype=date" : $query_string . "&amp;rtype=date" ?>">일</a></li>
    <li><a href="./visit_report.php<?= empty($query_string) ? "?rtype=month" : $query_string . "&amp;rtype=month" ?>">월</a></li>
    <li><a href="./visit_report.php<?= empty($query_string) ? "?rtype=year" : $query_string . "&amp;rtype=year" ?>">년</a></li>
</ul>
<div class="tbl_head01 tbl_wrap">
    <table>
        <caption><?php echo $g5['title']; ?> 목록</caption>
        <thead>
            <tr>
                <th>기간</th>
                <th>페이지뷰</th>
                <th>모바일<?= !empty($percent) ? "(%)" : "" ?></th>
                <th>PC<?= !empty($percent) ? "(%)" : "" ?></th>
                <th>방문자</th>
                <th>첫방문<?= !empty($percent) ? "(%)" : "" ?></th>
                <th>재방문<?= !empty($percent) ? "(%)" : "" ?></th>
                <th>가입자</th>
                <th>1분미만<?= !empty($percent) ? "(%)" : "" ?></th>
                <th>10분이상<?= !empty($percent) ? "(%)" : "" ?></th>
                <th>판매-리스</th>
                <th>판매-일반</th>
                <th>매출-일반</th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($result as $idx => $rep) : ?>
                <tr class="bg<?= ($idx % 2) ?>">
                    <td>
                        <?= $rtype == 'week' ? $rep['startdate'] . ' ~ ' . $rep['enddate'] : $rep[$rtype] ?>
                    </td>
                    <td><?= number_format($rep['count_total']) ?></td>
                    <td><?= number_format($rep['count_mobile']) ?><?= !empty($percent) ?  "(" . round($rep['count_mobile'] / $rep['count_total'] * 100, 1) . ")" : "" ?></td>
                    <td><?= number_format($rep['count_total'] - $rep['count_mobile']) ?><?= !empty($percent) ?  "(" . round(($rep['count_total'] - $rep['count_mobile']) / $rep['count_total'] * 100, 1) . ")" : "" ?></td>
                    <td><?= number_format($rep['count_user']) ?></td>
                    <td><?= number_format($rep['count_new']) ?><?= !empty($percent) ?  "(" . round($rep['count_new'] / $rep['count_user'] * 100, 1) . ")" : "" ?></td>
                    <td><?= number_format($rep['count_user'] - $rep['count_new']) ?><?= !empty($percent) ?  "(" . round(($rep['count_user'] - $rep['count_new']) / $rep['count_user'] * 100, 1) . ")" : "" ?></td>
                    <td><?= !empty($rep['count_join']) ? number_format((int) $rep['count_join']) : 0 ?></td>
                    <td><?= number_format($rep['count_stay_1']) ?><?= !empty($percent) ?  "(" . round($rep['count_stay_1'] / $rep['count_total'] * 100, 1) . ")" : "" ?></td>
                    <td><?= number_format($rep['count_stay_10']) ?><?= !empty($percent) ?  "(" . round($rep['count_stay_10'] / $rep['count_total'] * 100, 1) . ")" : "" ?></td>
                    <td><?= !empty($rep['count_r']) ? number_format((int) $rep['count_r']) : 0 ?></td>
                    <td><?= !empty($rep['count_o']) ? number_format((int) $rep['count_o']) : 0 ?></td>
                    <td><?= !empty($rep['sum_o']) ? number_format((int) $rep['sum_o']) : 0 ?></td>
                </tr>
            <? endforeach ?>
        </tbody>
    </table>
</div>

<script>
    $(function() {
        $("#fr_date, #to_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            showButtonPanel: true,
            yearRange: "c-99:c+99",
            maxDate: "+0d"
        });
    });

    function fvisit_submit(act) {
        var f = document.fvisit;
        f.action = act;
        f.submit();
    }
</script>

<?php
if (isset($domain))
    $qstr .= "&amp;domain=$domain";
$qstr .= "&amp;page=";
$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
echo $pagelist;
include_once('./admin.tail.php');
?>