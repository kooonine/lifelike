<?php
include_once('./_common.php');

$sql  = "SELECT * FROM sabang_lt_order_view WHERE slov_id = $slov_id";
$slov = sql_query($sql);
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
      <th scope="col">주문자명</th>
      <th scope="col">주문자<br>전화번호1</th>
      <th scope="col">주문자<br>전화번호2</th>
      <th scope="col">수취인명</th>
      <th scope="col">수취인주소</th>
      <th scope="col">배송메세지</th>
      <th scope="col">수취인<br>우편번호</th>
      <th scope="col">수취인<br>전화번호1</th>
      <th scope="col">수취인<br>전화번호2</th>
    </tr>
  </thead>
  <tbody>
    <? for ($i = 0; $row = sql_fetch_array($slov); $i++) { ?>
      <tr>
      <td>
        <?php echo $row['ov_order_name']; ?>
      </td>
      <td>
        <?php echo $row['ov_order_tel']; ?>
      </td>
      <td>
        <?php echo $row['ov_order_hp']; ?>
      </th>
      <td>
        <?php echo $row['ov_receive_name']; ?>
      </td>
      <td>
        <?php echo '['.$row['ov_receive_zip'].'] '; echo $row['ov_receive_addr']; ?>
      </td>
      <td>
        <?php echo $row['ov_order_msg']; ?>
      </td>
      <td>
        <?php echo $row['ov_receive_zip']; ?>
      </td>
      <td>
        <?php echo $row['ov_receive_tel']; ?>
      </td>
      <td>
        <?php echo $row['ov_receive_hp']; ?>
      </td>
    </tr>
    <? }?>
  </tbody>


 </table>