<?php
$sub_menu = '400400';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '주문내역 현황';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

if($od_type == "") $od_type = "L";
?>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	
	<div class="" role="tabpanel" data-example-id="togglable-tabs">
	  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
		<li role="presentation" class="<?php echo ($od_type=="L"?"active":"") ?>"><a href="./penguin_orderlist.php?od_type=L" >세탁</a></li>
		<li role="presentation" class="<?php echo ($od_type=="K"?"active":"") ?>"><a href="./penguin_orderlist.php?od_type=K" >보관</a></li>
	  </ul>
	  <div class="clearfix"></div>
	</div>

<?php 
if($od_type=="L"){
    include_once ('./orderlist_l.php');
} elseif($od_type=="K"){
    include_once ('./orderlist_k.php');
}
?>
	</div>
	</div>
</div>

<?php 
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
