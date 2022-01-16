<?php
include_once('./_common.php');

$sql  = "select * from lt_mail_sendhistory where sh_no = '{$sh_no}'; ";
$sh = sql_fetch($sql);
?>
<table>
  <colgroup>
  	<col class="grid_4">
  	<col>
  	<col class="grid_4">
  	<col>
  </colgroup>
  <tr>
    <th scope="row">발송타입</th>
    <td><?php echo $sh['sh_type']; ?></td>
    <th scope="row">메시지구분</th>
    <td><?php echo ($sh['sh_type']=="sendmail")?"일반메일발송":"대용량메일"; ?></td>
  </tr>
  <tr>
    <th scope="row">발송시간(등록시간)</th>
    <td><?php echo ($sh['sendDate'] != "0000-00-00 00:00:00")?$sh['sendDate']."(".$sh['sh_datetime'].")":$sh['sh_datetime']; ?></td>
    <th scope="row">발송건수</th>
    <td><?php echo count(preg_split(",", $$sh['receiver'])); ?>건</td>
  </tr>
  <tr>
    <th scope="row">수신메일</th>
    <td colspan="3"><?php echo $sh['receiver']; ?></td>
  </tr>
  <tr>
    <th scope="row">메시지제목</th>
    <td colspan="3"><?php echo $sh['sh_subject']; ?></td>
  </tr>
  <tr>
    <th scope="row">발신내용</th>
    <td colspan="3"><?php echo $sh['sh_content']; ?></td>
  </tr>
  <tr>
    <th scope="row">처리 결과 코드</th>
    <td colspan="3"><?php echo $sh['result_code']?></td>
    <!-- th scope="row">CMID</th>
    <td><?php echo $sh['cmid']; ?></td -->
  </tr>
  <tr>
    <th scope="row">결과내용</th>
    <td colspan="3"><?php echo $sh['result_msg']; ?></td>
  </tr>
 </table>