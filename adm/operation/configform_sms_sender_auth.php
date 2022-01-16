<?php
include_once('./_common.php');
require_once(G5_LIB_PATH.'/ppurioSMS.lib.php');

if(!empty($_POST) && $_POST['authType']) {
    $authType = $_POST['authType'];
    
    if($authType == "s" || $authType == "a")
    {
        $sendnumber = $_POST['sendnumber'];
        $comment = $_POST['comment'];
        
        $pincode = "";
        if($_POST['pincode']) $pincode = $_POST['pincode'];
    
        $body = save_sendnumber($sendnumber, $comment, "SMS", $pincode);
        
        echo json_encode_raw($body,JSON_UNESCAPED_UNICODE);
    }
}

if($authType == "l")
{
    $de_sms_hp = str_replace('-', '', trim($default['de_sms_hp']));
?>
<select name="send_phone" id="send_phone" >
  <option value="" >발신번호 선택</option>
  <?php
  
            
  $body = get_sendnumber_list();
  if($body['result_code'] == '200')
  {
      for ($i = 0; $i < count($body['numberList']); $i++) {
          $numberData = get_object_vars($body['numberList'][$i]);
          
          echo "<option value='".$numberData['sendnumber']."' ".get_selected($numberData['sendnumber'], $de_sms_hp).">".$numberData['comment'].'('.$numberData['sendnumber'].")</option>";
      }
      
  }
  
  ?>
</select>
<?php 
}
?>