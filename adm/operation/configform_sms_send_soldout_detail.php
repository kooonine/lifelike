<?php
include_once('./_common.php');

$sql  = "SELECT * FROM lt_sms_soldout WHERE ss_op_id = $sh_no ORDER BY ss_id";
$ss = sql_query($sql);
?>
<table>
  <colgroup>
  	<col class="grid_4">
  	<col>
  	<col class="grid_4">
  	<col>
  </colgroup>

  <thead>
    <tr>
      <th scope="col">전송일시</th>
      <th scope="col">쇼핑몰명</th>
      <th scope="col">주문번호</th>
      <th scope="col">상품명1</th>
      <th scope="col">상품명2</th>
      <th scope="col">고객명</th>
      <th scope="col">휴대폰번호</th>
    </tr>
  </thead>
  <tbody>
    <? for ($i = 0; $row = sql_fetch_array($ss); $i++) { ?>
      <tr>
      <td>
        <?php echo $row['ss_regdatetime']; ?>
      </th>
      <td>
        <?php echo $row['ss_mallname']; ?>
      </th>
      <td>
        <?php echo $row['ss_od_id']; ?>
      </th>
      <td>
        <?php echo $row['ss_products1']; ?>
      </th>
      <td>
        <?php echo $row['ss_products2']; ?>
      </th>
      <td>
        <?php echo $row['ss_mb_name']; ?>
      </th>
      <td>
        <?php echo $row['ss_phone_number']; ?>
      </th>
    </tr>
    <? }?>
  </tbody>


 </table>