<?php
include_once('./_common.php');

if (!empty($_POST)) {
    $connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
    $g5['connect_samjindb'] = $connect_db;

    $sap_code = $_POST['sap_code'];

    $sql = " SELECT	ORDER_NO,SAP_CODE,ITEM,MIN(PRICE) as PRICE ";
    $sql .= " FROM	S_MALL_ORDERS";
    $sql .= " WHERE SAP_CODE LIKE N'{$sap_code}%' ";
    $sql .= " GROUP BY ORDER_NO,SAP_CODE,ITEM ";

    $result = mssql_sql_query($sql);
}

?>
<div class="sit_option_frm_wrapper">
    <div class="tbl_frm01 tbl_wrap">
        <table>
            <colgroup>
                <col width="30%">
                <col width="70%">
            </colgroup>
            <tbody>
                <?php
                for ($i = 0; $row = mssql_sql_fetch_array($result); $i++) {
                    $str_confirm = sprintf("'%s','%s','%s',%d,'%s'", $row['ORDER_NO'], $row['SAP_CODE'], $row['ITEM'], $row['PRICE'], $_POST['subID']);
                    ?>
                    <tr onclick="sapconfirm(<?= $str_confirm ?>);" style="cursor: pointer;">
                        <th><?php echo $row['SAP_CODE'] ?></th>
                        <td><?php echo $row['ITEM'] ?></td>
                    </tr>
                <?php
                }

                if ($i == 0) {
                    ?>
                    <tr>
                        <td>검색되는 코드값이 없습니다.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>