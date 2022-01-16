<?php
//$sub_menu = '930110';
$sub_menu = '93';
include_once('./_common.php');


auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '상품DB전산화(커버)';
include_once (G5_ADMIN_PATH.'/admin.head.php');




if ($od_type == "") $od_type = "C";
?>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="" role="tabpanel" data-example-id="togglable-tabs">
				<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
					<li role="presentation" class="<?= ($od_type == "C" ? "active" : "") ?>"><a href="./new_goods_db_cover_main.php?od_type=C">일정</a></li>
					<li role="presentation" class="<?= ($od_type == "Y" ? "active" : "") ?>"><a href="./new_goods_db_cover_main.php?od_type=Y">소요량</a></li>
					<li role="presentation" class="<?= ($od_type == "B" ? "active" : "") ?>"><a href="./new_goods_db_cover_main.php?od_type=B">배정표</a></li>
					<li role="presentation" class="<?= ($od_type == "P" ? "active" : "") ?>"><a href="./new_goods_db_cover_main.php?od_type=P">원자재 입고계획</a></li>
					<li role="presentation" class="<?= ($od_type == "R" ? "active" : "") ?>"><a href="./new_goods_db_cover_main.php?od_type=R">리오더</a></li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<?
			if ($od_type == "C") {
				include_once('./new_goods_db_cover_c.php');
			} elseif ($od_type == "Y") {
				include_once('./new_goods_db_cover_y.php');
			} elseif ($od_type == "B") {
				include_once('./new_goods_db_cover_b.php');
			} elseif ($od_type == "P") {
				include_once('./new_goods_db_cover_p.php');
			} elseif ($od_type == "R") {
				include_once('./new_goods_db_cover_r.php');
			}
			?>
		</div>
	</div>
</div>
<?php 
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
