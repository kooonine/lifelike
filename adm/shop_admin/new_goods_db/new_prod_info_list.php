<?php
//$sub_menu = '930110';
$sub_menu = '93';
include_once('./_common.php');


auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '상품집 디지털화';
include_once (G5_ADMIN_PATH.'/admin.head.php');




if ($od_type == "") $od_type = "S";
?>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="" role="tabpanel" data-example-id="togglable-tabs">
				<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
					<li role="presentation" class="<?= ($od_type == "S" ? "active" : "") ?>"><a href="./new_prod_info_list.php?od_type=S">소프라움</a></li>
					<li role="presentation" class="<?= ($od_type == "T" ? "active" : "") ?>"><a href="./new_prod_info_list.php?od_type=T">템퍼</a></li>
					<li role="presentation" class="<?= ($od_type == "C" ? "active" : "") ?>"><a href="./new_prod_info_list.php?od_type=C">쉐르단</a></li>
					<li role="presentation" class="<?= ($od_type == "R" ? "active" : "") ?>"><a href="./new_prod_info_list.php?od_type=R">랄프로렌홈</a></li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<?
			if ($od_type == "S") {
				include_once('./new_prod_info_list_S.php');
			} elseif ($od_type == "T") {
				include_once('./new_prod_info_list_T.php');
			} elseif ($od_type == "R") {
				include_once('./new_prod_info_list_R.php');
			} 
			?>
		</div>
	</div>
</div>
<?php 
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
