<?php
include_once('./_common.php');

if (!empty($_POST)) {
    $where = " and ";
    $sql_search = "";
    if ($stx != "") {
        $sql_search .= " $where (it_name like '%$stx%' or a.it_id like '%$stx%' or e.its_sap_code like '%$stx%')";
        $where = " and ";
    }

    if ($ca_id != "") {
        $sql_search .= " $where a.ca_id like '$ca_id%' ";
        $where = " and ";
    }


    if ($psc_it_use != "") {
        $sql_search .= " $where a.it_use like '$psc_it_use%' ";
        $where = " and ";
    }
    if ($psc_it_soldout != "") {
        $sql_search .= " $where a.it_soldout like '$psc_it_soldout%' ";
        $where = " and ";
    }

    if (is_checked('not_it_id_list')) {
        $sql_search_it_id = "";
        $it_id_lists = explode(",", $not_it_id_list);

        for ($i = 0; $i < count($it_id_lists); $i++) {
            if ($i != 0) $sql_search_it_id .= ",";
            $sql_search_it_id .= "'" . $it_id_lists[$i] . "'";
        }
        $sql_search .= " $where a.it_id not in ($sql_search_it_id)";
    }

    $chkname = "";

    if ($w == "u" || is_checked('it_id_list')) {
        $sql_search_it_id = "";
        $it_id_lists = explode(",", $it_id_list);
        for ($i = 0; $i < count($it_id_lists); $i++) {
            if ($i != 0) $sql_search_it_id .= ",";
            $sql_search_it_id .= "'" . $it_id_lists[$i] . "'";
        }
        $sql_search .= " $where a.it_id in ($sql_search_it_id)";

        $chkname = "2";
    }

    $sql_common = " from {$g5['g5_shop_item_table']} a ,
                     {$g5['g5_shop_category_table']} b,
                     {$g5['g5_shop_category_table']} c,
                     {$g5['g5_shop_category_table']} d,
                     lt_shop_item_sub e
               where (b.ca_id = left(a.ca_id,2)
                    and   c.ca_id = left(a.ca_id,4)
                    and   d.ca_id = left(a.ca_id,6)
                    and   a.it_id = e.it_id
                    ";

    $sql_common .= ") ";
    $sql_common .= $sql_search;

    if (!$sst) {
        $sst  = "it_time";
        $sod = "desc";
    }
    $sql_order = "order by $sst $sod";

    $sql  = " select b.ca_name as ca_name1, c.ca_name as ca_name2, d.ca_name as ca_name3, a.*
            $sql_common
            $sql_order ";

    $result = sql_query($sql);
}
?>
<table>
    <thead>
        <tr>
            <th scope="col">
                <label for="chkall" class="sound_only">전체</label>
                <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all<?php echo $chkname; ?>(this.form)">
            </th>
            <th scope="col">번호</th>
            <th scope="col">정렬</th>
            <th scope="col">상품번호</th>
            <th scope="col">상품유형</th>
            <th scope="col">카테고리</th>
            <th scope="col">상품정보</th>
            <th scope="col">최종판매가</th>
            <th scope="col">전열상태</th>
            <th scope="col">품절</th>
        </tr>
    </thead>
    <tbody id="tbodyProduct">
        <?php
        for ($i = 0; $row = sql_fetch_array($result); $i++) {
            $bg = 'bg' . ($i % 2);
        ?>
            <tr>
                <td class="td_chk">
                    <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['it_name']); ?></label>
                    <input type="checkbox" name="chk<?php echo $chkname; ?>[]" value="<?php echo $i ?>" id="chk<?php echo $chkname; ?>_<?php echo $i; ?>">

                    <input type="hidden" name="it_id<?php echo $chkname; ?>[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">
                </td>
                <td class="td_chk">
                    <?php echo ($i + 1); ?>
                </td>
                <td class="td_chk it_id_sort" data-value="<?php echo $row['it_id'] ?>">
                    <span class="glyphicon glyphicon-chevron-up" onclick="changeSort(this, 'up')"></span>
                    <span class="glyphicon glyphicon-chevron-down" onclick="changeSort(this, 'down')"></span>
                </td>
                <td class="td_num grid_2">
                    <?php echo $row['it_id']; ?>
                </td>
                <td class="td_sort grid_1">
                    <label for="it_item_type_<?php echo $i; ?>" class="sound_only">분류</label>
                    <?php echo ($row['it_item_type'] == '0' ? '제품' : '리스'); ?>
                </td>
                <td class="th_qty grid_6">
                    <?php echo $row['ca_name1'] ?>
                    <?php echo ($row['ca_name2'] ? ' > ' . $row['ca_name2'] : ''); ?>
                    <?php echo ($row['ca_name3'] ? ' > ' . $row['ca_name3'] : ''); ?>
                </td>
                <td headers="th_pc_title" class="td_input " style="text-align: left;cursor: pointer;">
                    <label for="name_<?php echo $i; ?>" class="sound_only">상품명</label>
                    <?php echo get_it_image($row['it_id'], 50, 50); ?>
                    [<?= $row['it_brand'] ?>] <?php echo htmlspecialchars2(cut_str($row['it_name'], 250, "")); ?>
                </td>
                <td headers="th_amt" class="td_numbig td_input grid_4">
                    <label for="price_<?php echo $i; ?>" class="sound_only">최종판매가</label>
                    <?php echo number_format($row['it_price']); ?>
                </td>
                <td class="td_input grid_1">
                    <label for="use_<?php echo $i; ?>" class="sound_only">진열상태</label>
                    <?php echo ($row['it_use'] ? '진열' : '진열안함'); ?>
                </td>
                <td class="td_input grid_1">
                    <label for="use_<?php echo $i; ?>" class="sound_only">품절</label>
                    <?php echo ($row['it_soldout'] ? 'Y' : 'N'); ?>
                </td>
            </tr>
        <?php
        }
        
        if ($i == 0) {
        ?>
            <tr>
                <td colspan="8">검색되는 상품이 없습니다.</td>
            </tr>
        <?php } ?>
        <script>
            closeLoadingWithMask();
        </script>
    </tbody>
</table>