
<?php
$sub_menu = '930100';
include_once('./_common.php');


$send_mxl_sql = "select xml_name from sabang_xml_history where status = 0 order by no desc limit 1";

$send_xml_name = sql_fetch($send_mxl_sql);


?>
<input type="hidden" id="sabang_api_info2" name ="sabang_api_info2" value = "http://r.sabangnet.co.kr/RTL_API/xml_goods_info2.html?xml_url=">
<input type="hidden" id="sever_url" name ="sever_url" value = "<?=G5_URL?>/adm/cron/xml_sabang/" >
<input type="hidden" id="xml_name" name ="xml_name" value = "<?=$send_xml_name['xml_name']?>">
<div>송신<?=$send_xml_name['xml_name']?></div>
<script  type="text/javascript">
    var sabangApiInfo2 = document.getElementById("sabang_api_info2").value;
    var serverUrl = document.getElementById("sever_url").value;
    var xmlName = document.getElementById("xml_name").value;

    var sendUrl = sabangApiInfo2+serverUrl + xmlName + ".xml";
    console.log(sendUrl);

    window.location.href = sendUrl;
    

</script>