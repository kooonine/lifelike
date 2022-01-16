<?php

include_once('./../common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

    // $order_no = $_POST['order_no'];

    // ERP 에서 특정상품 재고 요청
    $order_no = 'MWR20HC52403';
    // SM_GET_STOCK($order_no);

    $connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
    $g5['connect_samjindb'] = $connect_db;

    $sap_code = $_POST['sap_code'];
    $min_price = $_POST['min_price'];

    $sql = "SELECT ORDER_NO,SAP_CODE,ITEM,CAT_NO,CAT_ITEM,STATUS,COLOR,COLOR_NAME,SZ,HOCHING,PRICE,STOCK
            FROM S_MALL_ORDERS
            WHERE SAP_CODE='{$order_no}'
            ORDER BY COLOR, SZ";
    $result = mssql_sql_query($sql);
    $data = mssql_sql_fetch($result);
?>

<?php
                for ($i = 0; $row = mssql_sql_fetch_array($result); $i++) {
                    $str_confirm = sprintf("'%s','%s','%s',%d,'%s'", $row['ORDER_NO'], $row['SAP_CODE'], $row['ITEM'], $row['PRICE'], $_POST['subID']);
                    ?>
                    <div onclick="sapconfirm(<?= $str_confirm ?>);" style="cursor: pointer;">
                        <span><?php echo $row['SAP_CODE'] ?></span>
                        <span><?php echo $row['ITEM'] ?></span>
                        <span><?php echo $row['COLOR'] ?></span>
                        <span><?php echo $row['ORDER_NO'] ?></span>
                        <span><?php echo $row['PRICE'] ?></span>
                        <span><?php echo $row['STOCK'] ?></span>
                        <span><?php echo $row['STATUS'] ?></span>
                    </div>
<?php
} ?>