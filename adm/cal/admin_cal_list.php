<?php
$sub_menu = "50";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');
$g5['title'] = '매출 관리';
include_once ('../admin.head.php');

if($cal_type == "") $cal_type = "1";
?>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	
	<div class="" role="tabpanel" data-example-id="togglable-tabs">
	  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
		<li role="presentation" class="<?php echo ($cal_type=="1"?"active":"") ?>"><a href="?cal_type=1" >PG사 정산내역업로드</a></li>
		<li role="presentation" class="<?php echo ($cal_type=="2"?"active":"") ?>"><a href="?cal_type=2" >매출 내역조회</a></li>
	  </ul>
	  <div class="clearfix"></div>
	</div>

<?php 
if($cal_type=="1"){
    include_once ('./admin_cal_list.pg.php');
} elseif($cal_type=="2"){
    include_once ('./admin_cal_list.sales.php');
}
?>
	</div>
	</div>
</div>

<?php
include_once ('../admin.tail.php');
?>