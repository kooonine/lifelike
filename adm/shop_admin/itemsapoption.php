<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

$po_run = false;

if ($it['it_id'] || $_POST['it_id']) {

    $it['it_id'] = $_POST['it_id'];
    $it['its_no'] = $_POST['its_no'];

    $sql = " select * from {$g5['g5_shop_item_option_table']} where io_type = '0' and its_no = '{$it['its_no']}' and it_id = '{$it['it_id']}' order by io_no asc ";
    $itSo = sql_fetch(" select it_soldout from lt_shop_item where it_id = '{$it['it_id']}' LIMIT 1 ");

    $result = sql_query($sql);
    if (sql_num_rows($result))
        $po_run = true;
} else if (!empty($_POST)) {
    $order_no = $_POST['order_no'];

    // ERP 에서 특정상품 재고 요청
    SM_GET_STOCK($order_no);

    $connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
    $g5['connect_samjindb'] = $connect_db;

    $sap_code = $_POST['sap_code'];
    $min_price = $_POST['min_price'];

    $sql = "SELECT ORDER_NO,SAP_CODE,ITEM,CAT_NO,CAT_ITEM,STATUS,COLOR,COLOR_NAME,SZ,HOCHING,PRICE,STOCK
            FROM S_MALL_ORDERS
            WHERE SAP_CODE='{$sap_code}'
            ORDER BY COLOR, SZ";
    $result = mssql_sql_query($sql);
    $po_run = true;
}

if ($po_run) {
    ?>
    <div class="sit_option_frm_wrapper">
        <table>
            <caption>옵션 목록</caption>
            <thead>
                <tr>
                    <th scope="col">옵션명</th>
                    <td class="td_numsmall" colspan="7">
                        <input type="text" name="it_option_subject[]" value="<?php echo $_POST['its_option_subject']; ?>" id="it_option_subject" class="frm_input required col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    </td>
                </tr>
                <tr>
                    <th scope="col">컬러</th>
                    <th scope="col">호칭(사이즈)</th>
                    <th scope="col">단가</th>

                    <th scope="col">옵션명</th>
                    <th scope="col">추가금액</th>
                    <th scope="col">재고수량</th>
                    <th scope="col">판매수량</th>
                    <th scope="col">사용여부</th>
                    <th scope="col">품절여부</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($it['it_id']) {
                        for ($i = 0; $row = sql_fetch_array($result); $i++) {
                            ?>
                        <tr>
                            <td class="opt1-cell"><?php echo ($row['io_color_name']) ?></td>
                            <td class="opt1-cell"><?php echo ($row['io_hoching']) ?></td>
                            <td class="opt1-cell"><?php echo number_format($row['io_sap_price']) ?></td>
                            <td class="td_numsmall">
                                <input type="text" name="opt_id[]" value="<?php echo $row['io_id']; ?>" id="opt_id_<?php echo $i; ?>" class="frm_input" size="50">
                                <input type="hidden" name="io_sapcode_color_gz[]" value="<?php echo $row['io_sapcode_color_gz']; ?>">
                                <input type="hidden" name="io_order_no[]" value="<?php echo ($row['io_order_no']); ?>">
                                <input type="hidden" name="io_color_name[]" value="<?php echo ($row['io_color_name']); ?>">
                                <input type="hidden" name="io_hoching[]" value="<?php echo ($row['io_hoching']); ?>">
                                <input type="hidden" name="io_sap_price[]" value="<?php echo ($row['io_sap_price']); ?>">

                                <input type="hidden" name="io_no[]" value="<?php echo $row['io_no']; ?>">
                                <input type="hidden" name="io_subid[]" value="<?php echo $subID; ?>">
                            </td>
                            <td class="td_numsmall">
                                <label for="opt_price_<?php echo $i; ?>" class="sound_only"></label>
                                <input type="text" name="opt_price[]" value="<?php echo $row['io_price']; ?>" id="opt_price_<?php echo $i; ?>" class="frm_input" size="9">
                            </td>
                            <td class="td_num">
                                <label for="opt_stock_qty_<?php echo $i; ?>" class="sound_only"></label>
                                <!-- <input type="text" name="opt_stock_qty[]" value="<?php echo $row['io_stock_qty']; ?>" id="opt_stock_qty_<?php echo $i; ?>" class="frm_input readonly" size="5" readonly="readonly"> -->
                                <input type="text" name="opt_stock_qty[]" value="<?php echo $row['io_stock_qty']; ?>" id="opt_stock_qty_<?php echo $i; ?>" class="frm_input" size="5">
                            </td>
                            <td class="td_num">
                                <label for="opt_noti_qty_<?php echo $i; ?>" class="sound_only"></label>
                                <input type="text" name="opt_noti_qty[]" value="<?php echo $row['io_noti_qty']; ?>" id="opt_noti_qty_<?php echo $i; ?>" class="frm_input" size="5">
                            </td>
                            <td class="td_mng">
                                <label for="opt_use_<?php echo $i; ?>" class="sound_only"></label>
                                <select name="opt_use[]" id="opt_use_<?php echo $i; ?>">
                                    <option value="1" <?php echo get_selected('1', $row['io_use']); ?>>사용함</option>
                                    <option value="0" <?php echo get_selected('0', $row['io_use']); ?>>사용안함</option>
                                </select>
                            </td>
                            <td class="td_mng">
                                <label for="opt_use2_<?php echo $i; ?>" class="sound_only"></label>
                                <select name="opt_use2[]" id="opt_use2_<?php echo $i; ?>">
                                    <option value="1" <?php echo get_selected('1', $itSo['it_soldout']); ?>>사용함</option>
                                    <option value="0" <?php echo get_selected('0', $itSo['it_soldout']); ?>>사용안함</option>
                                </select>
                            </td>
                        </tr>
                    <?php
                            } // for
                        } else {
                            for ($i = 0; $row = mssql_sql_fetch_array($result); $i++) {

                                $opt_price = $row['PRICE'] - $min_price;
                                $opt_stock_qty = $row['STOCK'];
                                $opt_noti_qty = $row['STOCK'];
                                $opt_use = 1;
                                ?>
                        <tr>
                            <td class="opt1-cell"><?php echo trim(stripslashes($row['COLOR'])) ?></td>
                            <td class="opt1-cell"><?php echo trim(stripslashes($row['HOCHING'])) ?></td>
                            <td class="opt1-cell"><?php echo number_format($row['PRICE']) ?></td>
                            <td class="td_numsmall">
                                <input type="text" name="opt_id[]" value="<?php echo trim(stripslashes($row['COLOR_NAME'])) . '_' . trim(stripslashes($row['HOCHING'])); ?>" id="opt_id_<?php echo $i; ?>" class="frm_input" size="50">
                                <input type="hidden" name="io_sapcode_color_gz[]" value="<?php echo trim($row['SAP_CODE']) . '_' . trim($row['COLOR']) . '_' . trim($row['SZ']); ?>">
                                <input type="hidden" name="io_order_no[]" value="<?php echo trim($row['ORDER_NO']); ?>">
                                <input type="hidden" name="io_color_name[]" value="<?php echo trim($row['COLOR']); ?>">
                                <input type="hidden" name="io_hoching[]" value="<?php echo trim($row['HOCHING']); ?>">
                                <input type="hidden" name="io_sap_price[]" value="<?php echo trim($row['PRICE']); ?>">

                                <input type="hidden" name="io_no[]" value="">
                                <input type="hidden" name="io_subid[]" value="<?php echo $subID; ?>">
                            </td>
                            <td class="td_numsmall">
                                <label for="opt_price_<?php echo $i; ?>" class="sound_only"></label>
                                <input type="text" name="opt_price[]" value="<?php echo $opt_price; ?>" id="opt_price_<?php echo $i; ?>" class="frm_input" size="9">
                            </td>
                            <td class="td_num">
                                <label for="opt_stock_qty_<?php echo $i; ?>" class="sound_only"></label>
                                <input type="text" name="opt_stock_qty[]" value="<?php echo $opt_stock_qty; ?>" id="opt_stock_qty_<?php echo $i; ?>" class="frm_input disabled" size="5" readonly="readonly">
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
                        }
                    }
                    ?>
            </tbody>
        </table>
    </div>

<?php
}
?>