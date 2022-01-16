<?php
$sub_menu = '92';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '취소/반품 현황';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

if($claimtype == "") $claimtype = "1";
?>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	
	<div class="" role="tabpanel" data-example-id="togglable-tabs">
	  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
		<li role="presentation" class="<?php echo ($claimtype=="1"?"active":"") ?>"><a href="./claimlist.brand.php?claimtype=1" >취소</a></li>
		<li role="presentation" class="<?php echo ($claimtype=="3"?"active":"") ?>"><a href="./claimlist.brand.php?claimtype=3" >반품</a></li>
		
	  <div class="clearfix"></div>
	</div>

<?php 
if($claimtype=="1"){
    include_once ('./claimlist_1.php');
} elseif($claimtype=="3"){
    include_once ('./claimlist_3.php');
}
?>


	</div>
	</div>
</div>

<?php 
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
