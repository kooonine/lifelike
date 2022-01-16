<?php
include_once('./_common.php');

$sql  = "SELECT * FROM b2b_order WHERE order_no = '{$order_no}' limit 1";
$ord = sql_query($sql);
?>
<table>
  <colgroup>
  	<col width="150px">
  	<col width="150px">
  	<col width="150px">
  	<col >
    <col width="150px">
  </colgroup>

  <thead>
    <tr>
      <th scope="col">주문자명</th>
      <th scope="col">주문자<br>전화번호</th>
      <th scope="col">수취인명</th>
      <th scope="col">수취인주소</th>
      <th scope="col">수취인<br>전화번호</th>
    </tr>
  </thead>
  <tbody>
    <? for ($i = 0; $row = sql_fetch_array($ord); $i++) { ?>
      <tr>
      <td>
        <?php echo $row['st_name']; ?>
      </td>
      <td>
        <?php echo $row['st_tel']; ?>
      </td>
      <td>
        <?php echo $row['receive_name']; ?>
      </td>
      <td>
        <?php echo '['.$row['receive_zip'].'] '; echo $row['receive_addr1'] . ' ';  echo $row['receive_addr2']; ?>
      </td>
      <td>
        <?php echo $row['receive_tel']; ?>
      </td>
    </tr>
    <? }?>
  </tbody>


 </table>