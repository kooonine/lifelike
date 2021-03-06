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

    if (!empty($it_brand)) {
        $sql_search .= " $where a.it_brand='$it_brand' ";
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
        $sql_order = "ORDER BY FIELD(a.it_id, $sql_search_it_id)";
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
    $sql_use = "AND a.it_use = 1";
    $sql_notuse = "AND a.it_use != 1";

    if (!$sst) {
        $sst  = "it_time";
        $sod = "desc";
    }
    // $sql_order = "order by $sst $sod";

    $sql  = " select b.ca_name as ca_name1, c.ca_name as ca_name2, d.ca_name as ca_name3, a.*
            $sql_common
            $sql_use
            $sql_order ";

    $result = sql_query($sql);

    $sql  = " select b.ca_name as ca_name1, c.ca_name as ca_name2, d.ca_name as ca_name3, a.*
    $sql_common
    $sql_notuse
    $sql_order ";
    $result2 = sql_query($sql);
}
?>
<h4> ????????? </h4>
<table>
    <thead>
        <tr>
            <th scope="col">
                <label for="chkall" class="sound_only">??????</label>
                <input type="checkbox" name="chkall" value="1" id="chkall" onclick="Dcheck_all<?php echo $chkname; ?>(this.form)">
            </th>
            <th scope="col">??????</th>
            <th scope="col">??????</th>
            <th scope="col">????????????</th>
            <th scope="col">????????????</th>
            <th scope="col">????????????</th>
            <th scope="col">????????????</th>
            <th scope="col">???????????????</th>
            <th scope="col">????????????</th>
        </tr>
    </thead>
    <tbody id="tbodyProduct<?php echo  $search; ?>">
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
                    <label for="it_item_type_<?php echo $i; ?>" class="sound_only">??????</label>
                    <?php echo ($row['it_item_type'] == '0' ? '??????' : '??????'); ?>
                </td>
                <td class="th_qty grid_6">
                    <?php echo $row['ca_name1'] ?>
                    <?php echo ($row['ca_name2'] ? ' > ' . $row['ca_name2'] : ''); ?>
                    <?php echo ($row['ca_name3'] ? ' > ' . $row['ca_name3'] : ''); ?>
                </td>
                <td headers="th_pc_title" class="td_input " style="text-align: left;cursor: pointer;">
                    <label for="name_<?php echo $i; ?>" class="sound_only">?????????</label>
                    <?php echo get_it_image($row['it_id'], 50, 50); ?>
                    [<?= $row['it_brand'] ?>] <?php echo htmlspecialchars2(cut_str($row['it_name'], 250, "")); ?>
                </td>
                <td headers="th_amt" class="td_numbig td_input grid_4">
                    <label for="price_<?php echo $i; ?>" class="sound_only">???????????????</label>
                    <?php echo number_format($row['it_price']); ?>
                </td>
                <td class="td_input grid_1">
                    <label for="use_<?php echo $i; ?>" class="sound_only">????????????</label>
                    <?php echo ($row['it_use'] ? '??????' : '????????????'); ?>
                </td>
            </tr>
        <?php
        }

        if ($i == 0) {
        ?>
            <tr>
                <td colspan="8">???????????? ????????? ????????????.</td>
            </tr>
        <?php } ?>

    </tbody>
</table>
<br><br><br>
<h4> ???????????? </h4>
<table>
    <thead>
        <tr>
            <th scope="col">
                <label for="chkall" class="sound_only">??????</label>
                <input type="checkbox" name="chkall" value="1" id="chkall" onclick="Ncheck_all<?php echo $chkname; ?>(this.form)">
            </th>
            <th scope="col">??????</th>
            <th scope="col">??????</th>
            <th scope="col">????????????</th>
            <th scope="col">????????????</th>
            <th scope="col">????????????</th>
            <th scope="col">????????????</th>
            <th scope="col">???????????????</th>
            <th scope="col">????????????</th>
        </tr>
    </thead>
    <tbody id="tbodyProduct<?php echo  $search; ?>">
        <?php
        for ($i = 0; $row = sql_fetch_array($result2); $i++) {
            $bg = 'bg' . ($i % 2);
        ?>
            <tr>
                <td class="td_chk">
                    <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['it_name']); ?></label>
                    <input type="checkbox" name="n_chk<?php echo $chkname; ?>[]" value="<?php echo $i ?>" id="chk<?php echo $chkname; ?>_<?php echo $i; ?>">

                    <input type="hidden" name="n_it_id<?php echo $chkname; ?>[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">
                </td>
                <td class="td_chk">
                    <?php echo ($i + 1); ?>
                </td>
                <td class="td_chk n_it_id_sort" data-value="<?php echo $row['it_id'] ?>">
                    <span class="glyphicon glyphicon-chevron-up" onclick="changeSort2(this, 'up')"></span>
                    <span class="glyphicon glyphicon-chevron-down" onclick="changeSort2(this, 'down')"></span>
                </td>
                <td class="td_num grid_2">
                    <?php echo $row['it_id']; ?>
                </td>
                <td class="td_sort grid_1">
                    <label for="it_item_type_<?php echo $i; ?>" class="sound_only">??????</label>
                    <?php echo ($row['it_item_type'] == '0' ? '??????' : '??????'); ?>
                </td>
                <td class="th_qty grid_6">
                    <?php echo $row['ca_name1'] ?>
                    <?php echo ($row['ca_name2'] ? ' > ' . $row['ca_name2'] : ''); ?>
                    <?php echo ($row['ca_name3'] ? ' > ' . $row['ca_name3'] : ''); ?>
                </td>
                <td headers="th_pc_title" class="td_input " style="text-align: left;cursor: pointer;">
                    <label for="name_<?php echo $i; ?>" class="sound_only">?????????</label>
                    <?php echo get_it_image($row['it_id'], 50, 50); ?>
                    [<?= $row['it_brand'] ?>] <?php echo htmlspecialchars2(cut_str($row['it_name'], 250, "")); ?>
                </td>
                <td headers="th_amt" class="td_numbig td_input grid_4">
                    <label for="price_<?php echo $i; ?>" class="sound_only">???????????????</label>
                    <?php echo number_format($row['it_price']); ?>
                </td>
                <td class="td_input grid_1">
                    <label for="use_<?php echo $i; ?>" class="sound_only">????????????</label>
                    <?php echo ($row['it_use'] ? '??????' : '????????????'); ?>
                </td>
            </tr>
        <?php
        }

        if ($i == 0) {
        ?>
            <tr>
                <td colspan="8">???????????? ????????? ????????????.</td>
            </tr>
        <?php } ?>

    </tbody>
</table>
<script>
    function changeSort2(elem, action) { 
        const value2 = $(elem).parent().data("value");
		const $rows2 = $("#tbodyProduct>tr>td.n_it_id_sort");
        let $current2, targetIdx2;
        $rows2.each(function(idx, elem) {
			if ($(elem).data("value") == value2) {
				targetIdx2 = action == 'up' ? idx - 1 : idx + 1;
				$current2 = $(elem).parent();
			}
        });
        if (targetIdx2 >= 0 && targetIdx2 < $rows2.length) {
			$rows2.each(function(idx, elem) {
				if (idx == targetIdx2) {
					if (action == 'up') {
						$(elem).parent().before($current2);
					} else {
						$(elem).parent().after($current2)
					}
				}
			});

			let values = [];
            $("#tbodyProduct>tr>td.n_it_id_sort").each(function(idx, elem) {
				values.push($(elem).data("value"));
            });
            $("#n.it_id_list").val(values.join(','));


            values = [];
            $("#tbodyProduct>tr>td.n_it_id_sort").each(function(idx, elem) {
				values.push($(elem).data("value"));
            });
            $("#tbodyProduct>tr>td.it_id_sort").each(function(idx, elem) {
				values.push($(elem).data("value"));
            });
            $("#cp_item_set_item_" + CpItemIndex).val(values.join(','));
		}
    }
    function changeSort(elem, action) {
		const value = $(elem).parent().data("value");
		const $rows = $("#tbodyProduct>tr>td.it_id_sort");
		let $current, targetIdx;
        $rows.each(function(idx, elem) {
			if ($(elem).data("value") == value) {
				targetIdx = action == 'up' ? idx - 1 : idx + 1;
				$current = $(elem).parent();
			}
		});
        if (targetIdx >= 0 && targetIdx < $rows.length) {
			$rows.each(function(idx, elem) {
				if (idx == targetIdx) {
					if (action == 'up') {
						$(elem).parent().before($current);
					} else {
						$(elem).parent().after($current)
					}
				}
			});

			let values = [];
			$("#tbodyProduct>tr>td.it_id_sort").each(function(idx, elem) {
				values.push($(elem).data("value"));
            });
            $("#it_id_list").val(values.join(','));
            $("#tbodyProduct>tr>td.n_it_id_sort").each(function(idx, elem) {
				values.push($(elem).data("value"));
            });
            $("#cp_item_set_item_" + CpItemIndex).val(values.join(','));
		}
	}
    function Dcheck_all(e=false) { 
        if ($("input:checkbox[id='chkall']").is(':checked')) {
            $("input[name='chk[]']").prop("checked" , true);
        } else {
            $("input[name='chk[]']").prop("checked" , false);
        }
    }
    function Dcheck_all2(e=false) { 
        if ($("input:checkbox[id='chkall']").is(':checked')) {
            $("input[name='chk2[]']").prop("checked" , true);
        } else {
            $("input[name='chk2[]']").prop("checked" , false);
        }
    }
    function Ncheck_all(e=false) { 
        if ($("input:checkbox[id='chkall']").is(':checked')) {
            $("input[name='n_chk[]']").prop("checked" , true);
        } else {
            $("input[name='n_chk[]']").prop("checked" , false);
        }
    }
    function Ncheck_all2(e=false) { 
        if ($("input:checkbox[id='chkall']").is(':checked')) {
            $("input[name='n_chk2[]']").prop("checked" , true);
        } else {
            $("input[name='n_chk2[]']").prop("checked" , false);
        }
    }
</script>