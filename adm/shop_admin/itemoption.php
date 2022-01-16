<?php
include_once('./_common.php');

$po_run = false;

if($it['it_id']) {
    $opt_subject = explode(',', $it['it_option_subject']);
    $opt1_subject = $opt_subject[0];
    $opt2_subject = $opt_subject[1];
    $opt3_subject = $opt_subject[2];

    $sql = " select * from {$g5['g5_shop_item_option_table']} where io_type = '0' and it_id = '{$it['it_id']}' order by io_no asc ";
    $result = sql_query($sql);
    if(sql_num_rows($result))
        $po_run = true;
} else if(!empty($_POST)) {
    $opt1_subject = preg_replace(G5_OPTION_ID_FILTER, '', trim(stripslashes($_POST['opt1_subject'])));
    $opt2_subject = preg_replace(G5_OPTION_ID_FILTER, '', trim(stripslashes($_POST['opt2_subject'])));
    $opt3_subject = preg_replace(G5_OPTION_ID_FILTER, '', trim(stripslashes($_POST['opt3_subject'])));

    $opt1_val = preg_replace(G5_OPTION_ID_FILTER, '', trim(stripslashes($_POST['opt1'])));
    $opt2_val = preg_replace(G5_OPTION_ID_FILTER, '', trim(stripslashes($_POST['opt2'])));
    $opt3_val = preg_replace(G5_OPTION_ID_FILTER, '', trim(stripslashes($_POST['opt3'])));

    if(!$opt1_subject || !$opt1_val) {
        echo '옵션1과 옵션1 항목을 입력해 주십시오.';
        exit;
    }

    $po_run = true;

    $opt1_count = $opt2_count = $opt3_count = 0;

    if($opt1_val) {
        $opt1 = explode(',', $opt1_val);
        $opt1_count = count($opt1);
    }

    if($opt2_val) {
        $opt2 = explode(',', $opt2_val);
        $opt2_count = count($opt2);
    }

    if($opt3_val) {
        $opt3 = explode(',', $opt3_val);
        $opt3_count = count($opt3);
    }
}

if($po_run) {
?>

<div class="sit_option_frm_wrapper">
    <table>
    <caption>옵션 목록</caption>
    <thead>
    <tr>
        <th scope="col">
            <label for="opt_chk_all" class="sound_only">전체 옵션</label>
            <input type="checkbox" name="opt_chk_all" value="1" id="opt_chk_all">
        </th>
        <th scope="col">옵션명</th>
        <th scope="col">추가금액</th>
        <th scope="col">재고수량</th>
        <th scope="col">판매수량</th>
        <th scope="col">사용여부</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if($it['it_id']) {
        for($i=0; $row=sql_fetch_array($result); $i++) {
            $opt_id = $row['io_id'];
            $opt_val = explode(chr(30), $opt_id);
            $opt_1 = $opt_val[0];
            $opt_2 = $opt_val[1];
            $opt_3 = $opt_val[2];
            $opt_2_len = strlen($opt_2);
            $opt_3_len = strlen($opt_3);
            $opt_price = $row['io_price'];
            $opt_stock_qty = $row['io_stock_qty'];
            $opt_noti_qty = $row['io_noti_qty'];
            $opt_use = $row['io_use'];
    ?>
    <tr>
        <td class="td_chk">
            <input type="hidden" name="opt_id[]" value="<?php echo $opt_id; ?>">
            <input type="hidden" name="io_no[]" value="<?php echo $row['io_no']; ?>">
            <input type="hidden" name="io_subid[]" value="0">
            <label for="opt_chk_<?php echo $i; ?>" class="sound_only"></label>
            <input type="checkbox" name="opt_chk[]" id="opt_chk_<?php echo $i; ?>" value="1">
        </td>
        <td class="opt-cell"><?php echo $opt_1; if ($opt_2_len) echo ' <small>&gt;</small> '.$opt_2; if ($opt_3_len) echo ' <small>&gt;</small> '.$opt_3; ?></td>
        <td class="td_numsmall">
            <label for="opt_price_<?php echo $i; ?>" class="sound_only"></label>
            <input type="text" name="opt_price[]" value="<?php echo $opt_price; ?>" id="opt_price_<?php echo $i; ?>" class="frm_input" size="9">
        </td>
        <td class="td_num">
            <label for="opt_stock_qty_<?php echo $i; ?>" class="sound_only"></label>
            <input type="text" name="opt_stock_qty[]" value="<?php echo $opt_stock_qty; ?>" id="op_stock_qty_<?php echo $i; ?>" class="frm_input" size="5">
        </td>
        <td class="td_num">
            <label for="opt_noti_qty_<?php echo $i; ?>" class="sound_only"></label>
            <input type="text" name="opt_noti_qty[]" value="<?php echo $opt_noti_qty; ?>" id="opt_noti_qty_<?php echo $i; ?>" class="frm_input" size="5">
        </td>
        <td class="td_mng">
            <label for="opt_use_<?php echo $i; ?>" class="sound_only"></label>
            <select name="opt_use[]" id="opt_use_<?php echo $i; ?>">
                <option value="1" <?php echo get_selected('1', $opt_use); ?>>사용함</option>
                <option value="0" <?php echo get_selected('0', $opt_use); ?>>사용안함</option>
            </select>
        </td>
    </tr>
    <?php
        } // for
    } else {
        for($i=0; $i<$opt1_count; $i++) {
            $j = 0;
            do {
                $k = 0;
                do {
                    $opt_1 = strip_tags(trim($opt1[$i]));
                    $opt_2 = strip_tags(trim($opt2[$j]));
                    $opt_3 = strip_tags(trim($opt3[$k]));

                    $opt_2_len = strlen($opt_2);
                    $opt_3_len = strlen($opt_3);

                    $opt_id = $opt_1;
                    if($opt_2_len)
                        $opt_id .= chr(30).$opt_2;
                    if($opt_3_len)
                        $opt_id .= chr(30).$opt_3;
                    $opt_price = 0;
                    $opt_stock_qty = 9999;
                    $opt_noti_qty = 100;
                    $opt_use = 1;

                    // 기존에 설정된 값이 있는지 체크
                    if($_POST['w'] == 'u') {
                        $sql = " select io_price, io_stock_qty, io_noti_qty, io_use
                                    from {$g5['g5_shop_item_option_table']}
                                    where it_id = '{$_POST['it_id']}'
                                      and io_id = '$opt_id'
                                      and io_type = '0' ";
                        $row = sql_fetch($sql);

                        if($row) {
                            $opt_price = (int)$row['io_price'];
                            $opt_stock_qty = (int)$row['io_stock_qty'];
                            $opt_noti_qty = (int)$row['io_noti_qty'];
                            $opt_use = (int)$row['io_use'];
                        }
                    }
    ?>
    <tr>
        <td class="td_chk">
            <input type="hidden" name="opt_id[]" value="<?php echo $opt_id; ?>">
            <label for="opt_chk_<?php echo $i; ?>" class="sound_only"></label>
            <input type="checkbox" name="opt_chk[]" id="opt_chk_<?php echo $i; ?>" value="1">
            <input type="hidden" name="io_subid[]" value="0">            
        </td>
        <td class="opt1-cell"><?php echo $opt_1; if ($opt_2_len) echo ' <small>&gt;</small> '.$opt_2; if ($opt_3_len) echo ' <small>&gt;</small> '.$opt_3; ?></td>
        <td class="td_numsmall">
            <label for="opt_price_<?php echo $i; ?>" class="sound_only"></label>
            <input type="text" name="opt_price[]" value="<?php echo $opt_price; ?>" id="opt_price_<?php echo $i; ?>" class="frm_input" size="9">
        </td>
        <td class="td_num">
            <label for="opt_stock_qty_<?php echo $i; ?>" class="sound_only"></label>
            <input type="text" name="opt_stock_qty[]" value="<?php echo $opt_stock_qty; ?>" id="opt_stock_qty_<?php echo $i; ?>" class="frm_input" size="5">
        </td>
        <td class="td_num">
            <label for="opt_noti_qty_<?php echo $i; ?>" class="sound_only"></label>
            <input type="text" name="opt_noti_qty[]" value="<?php echo $opt_noti_qty; ?>" id="opt_noti_qty_<?php echo $i; ?>" class="frm_input" size="5">
        </td>
        <td class="td_mng">
            <label for="opt_use_<?php echo $i; ?>" class="sound_only"></label>
            <select name="opt_use[]" id="opt_use_<?php echo $i; ?>">
                <option value="1" <?php echo get_selected('1', $opt_use); ?>>사용함</option>
                <option value="0" <?php echo get_selected('0', $opt_use); ?>>사용안함</option>
            </select>
        </td>
    </tr>
    <?php
                    $k++;
                } while($k < $opt3_count);

                $j++;
            } while($j < $opt2_count);
        } // for
    }
    ?>
    </tbody>
    </table>
</div>

<div class="btn_list01 btn_list">
    <input type="button" value="선택삭제" id="sel_option_delete" class="btn btn_02">
</div>
<?php
}
?>