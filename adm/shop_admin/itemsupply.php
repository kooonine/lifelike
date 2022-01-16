<?php
include_once('./_common.php');

if($it['it_id'] || $_POST['it_id']) {
    
    $it['it_id'] = $_POST['it_id'];
    $it['its_no'] = $_POST['its_no'];
    
    $sql = " select * from {$g5['g5_shop_item_option_table']} where io_type = '1' and its_no = '{$it['its_no']}' and it_id = '{$it['it_id']}' order by io_no asc ";
    $result = sql_query($sql);
    
    $subID = $_POST['subID'];
    
} else if(!empty($_POST)) {
    $subject_count = count($_POST['subject']);
    $supply_count = count($_POST['supply']);
    
    $subID = $_POST['subID'];

    if(!$subject_count || !$supply_count) {
        echo '추가옵션명과 추가옵션항목을 입력해 주십시오.';
        exit;
    }
}

?>
<div class="sit_option_frm_wrapper">
    <table>
    <caption>추가옵션 목록</caption>
    <thead>
    <tr>
        <th scope="col">
            <label for="spl_chk_all<?php echo $s ?>" class="sound_only">전체 추가옵션</label>
            <input type="checkbox" name="spl_chk_all<?php echo $s ?>" value="1">
        </th>
        <th scope="col">옵션명</th>
        <th scope="col">옵션항목</th>
        <th scope="col">옵션가</th>
        <th scope="col">사용여부</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if($it['it_id']) {
        for($i=0; $row=sql_fetch_array($result); $i++) {
            $spl_id = $row['io_id'];
            $spl_val = explode(chr(30), $spl_id);
            $spl_subject = $spl_val[0];
            $spl = $spl_val[1];
            $spl_price = $row['io_price'];
            $spl_stock_qty = $row['io_stock_qty'];
            $spl_noti_qty = $row['io_noti_qty'];
            $spl_use = $row['io_use'];
    ?>
    <tr>
        <td class="td_chk">
            <input type="hidden" name="spl_id[]" value="<?php echo $spl_id; ?>">
            <input type="hidden" name="spl_stock_qty[]" value="<?php echo $spl_stock_qty; ?>">
            <input type="hidden" name="spl_noti_qty[]" value="<?php echo $spl_noti_qty; ?>">
            
            <label for="spl_chk_<?php echo $i; ?>" class="sound_only"><?php echo $spl_subject.' '.$spl; ?></label>
            <input type="checkbox" name="spl_chk[]" id="spl_chk_<?php echo $i; ?>" value="1">
            
            <input type="hidden" name="spl_subid[]" value="<?php echo $subID; ?>">
        </td>
        <td class="spl-subject-cell"><?php echo $spl_subject; ?></td>
        <td class="spl-cell"><?php echo $spl; ?></td>
        <td class="td_numsmall">
            <label for="spl_price_<?php echo $i; ?>" class="sound_only">상품금액</label>
            <input type="text" name="spl_price[]" value="<?php echo $spl_price; ?>" id="spl_price_<?php echo $i; ?>" class="frm_input" size="5">
        </td>
        <td class="td_mng">
            <label for="spl_use_<?php echo $i; ?>" class="sound_only">사용여부</label>
            <select name="spl_use[]" id="spl_use_<?php echo $i; ?>">
                <option value="1" <?php echo get_selected('1', $spl_use); ?>>사용함</option>
                <option value="0" <?php echo get_selected('0', $spl_use); ?>>사용안함</option>
            </select>
        </td>
    </tr>
    <?php
        } // for
    } else {
        for($i=0; $i<$subject_count; $i++) {
            $spl_subject = preg_replace(G5_OPTION_ID_FILTER, '', trim(stripslashes($_POST['subject'][$i])));
            $spl_val = explode(',', preg_replace(G5_OPTION_ID_FILTER, '', trim(stripslashes($_POST['supply'][$i]))));
            $spl_count = count($spl_val);

            for($j=0; $j<$spl_count; $j++) {
                $spl = strip_tags(trim($spl_val[$j]));
                if($spl_subject && strlen($spl)) {
                    $spl_id = $spl_subject.chr(30).$spl;
                    $spl_price = 0;
                    $spl_stock_qty = 9999;
                    $spl_noti_qty = 100;
                    $spl_use = 1;

                    // 기존에 설정된 값이 있는지 체크
                    if($_POST['w'] == 'u') {
                        $sql = " select io_price, io_stock_qty, io_noti_qty, io_use
                                    from {$g5['g5_shop_item_option_table']}
                                    where it_id = '{$_POST['it_id']}'
                                      and io_id = '$spl_id'
                                      and io_type = '1' ";
                        $row = sql_fetch($sql);

                        if($row) {
                            $spl_price = (int)$row['io_price'];
                            $spl_stock_qty = (int)$row['io_stock_qty'];
                            $spl_noti_qty = (int)$row['io_noti_qty'];
                            $spl_use = (int)$row['io_use'];
                        }
                    }
    ?>
    <tr>
        <td class="td_chk">
            <input type="hidden" name="spl_id[]" value="<?php echo $spl_id; ?>">
            <input type="hidden" name="spl_stock_qty[]" value="<?php echo $spl_stock_qty; ?>">
            <input type="hidden" name="spl_noti_qty[]" value="<?php echo $spl_noti_qty; ?>">
            
            <label for="spl_chk_<?php echo $i; ?>" class="sound_only"><?php echo $spl_subject.' '.$spl; ?></label>
            <input type="checkbox" name="spl_chk[]" id="spl_chk_<?php echo $i; ?>" value="1">
            
            <input type="hidden" name="spl_subid[]" value="<?php echo $subID; ?>">
        </td>
        <td class="spl-subject-cell"><?php echo $spl_subject; ?></td>
        <td class="spl-cell"><?php echo $spl; ?></td>
        <td class="td_numsmall">
            <label for="spl_price_<?php echo $i; ?>" class="sound_only">상품금액</label>
            <input type="text" name="spl_price[]" value="<?php echo $spl_price; ?>" id="spl_price_<?php echo $i; ?>" class="frm_input" size="9">
        </td>
        <td class="td_mng">
            <label for="spl_use_<?php echo $i; ?>" class="sound_only">사용여부</label>
            <select name="spl_use[]" id="spl_use_<?php echo $i; ?>">
                <option value="1" <?php echo get_selected('1', $spl_use); ?>>사용함</option>
                <option value="0" <?php echo get_selected('0', $spl_use); ?>>사용안함</option>
            </select>
        </td>
    </tr>
    <?php
                } // if
            } // for
        } // for
    }
    ?>
    </tbody>
    </table>
</div>

<div class="btn_list01 btn_list">
    <button type="button" id="sel_supply_delete" class="btn btn_02">선택삭제</button>
</div>
