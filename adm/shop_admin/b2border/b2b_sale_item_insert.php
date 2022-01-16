<?php
include_once('./_common.php');

if (!empty($_POST)) {
    $connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
    $g5['connect_samjindb'] = $connect_db;

    $keyword = $_POST['keyword'];



    $sql = " SELECT	* ";
    $sql .= " FROM	S_MALL_ORDERS";
    $sql .= " WHERE 1=1 AND ( SAP_CODE LIKE N'{$keyword}%' OR  ORDER_NO LIKE N'{$keyword}%' OR  ITEM LIKE N'{$keyword}%') ";
    
    $result = mssql_sql_query($sql);
}

?>
<div class="sit_option_frm_wrapper">
    <div class="tbl_frm01 tbl_wrap">
        <table>
            <colgroup>
                <col width="20%">
                <col width="20%">
				<col width="55%">
				<col width="5%">
            </colgroup>
            <tbody>
                <?php
                for ($i = 0; $row = mssql_sql_fetch_array($result); $i++) {
					?>
                    <tr onclick="insert_sale_item('<?=$row['ORDER_NO']?>','<?=$row['SAP_CODE']?>','<?=$row['ITEM']?>','<?=$row['HOCHING']?>','<?=$row['PRICE']?>','<?=$row['COLOR']?>');" >
						<th><?php echo $row['ORDER_NO'] ?></th>
                        <th><?php echo $row['SAP_CODE'] ?></th>
                        <td><?php echo $row['ITEM'] . count($stockSamjin) ?></td>
						<td><?php echo $row['HOCHING'] ?></td>
                    </tr>
                <?php
                }

                if ($i == 0) {
                    ?>
                    <tr>
                        <td colspan = "4">검색되는 코드값이 없습니다.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>