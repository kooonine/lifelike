<?php
include_once('./_common.php');

$sql  = "SELECT * FROM sabang_return_memo WHERE sro_id = $sro_id ORDER BY srm_id ASC";
$sro = sql_query($sql);
?>
<input type="text" id="memoText" name="memoText"  class="frm_input" size="110">
<br><br>
<input type="button" value="취소" class="btn btn_02" class="close" data-dismiss="modal" aria-label="Close">
<input type="button" value="저장" class="btn btn_03" onclick="saveMemo(<? echo $sro_id ?>)">
<? for ($i = 0; $row = sql_fetch_array($sro); $i++) { ?> 
  <br>
  <br>
  <div style="border-top: 1px solid #D5D5D5; width: 688px; display: inline-block; text-align: left;">
    <br>
    <p><?php echo $row['mb_id']; ?></p>
    <p style="margin-top: -10px;"><?php echo $row['reg_datetime']; ?></p>
    <p style="font-size: 17px;"><?php echo $row['srm_memo']; ?></p>

  </div>
<? }?>