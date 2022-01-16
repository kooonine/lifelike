<?

$sub_menu = '960100';
include_once('./_common.php');


auth_check($auth[substr($sub_menu,0,2)], "r");


$g5['title'] = '상품등록';


include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');


$cp_sql = "SELECT * FROM b2b_company WHERE cp_gubun = 'Y' AND use_yn = 'Y' ORDER BY sort ASC ";
$cp_res = sql_query($cp_sql);
$cp_defult = sql_fetch($cp_sql);

if ($cp_code == "") $cp_code = $cp_defult['cp_code'];
?>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="" role="tabpanel" data-example-id="togglable-tabs">
				<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <?for ($i = 0 ; $cp_Row = sql_fetch_array($cp_res); $i++) { ?>
                        <li role="presentation" class="<?= ($cp_code == $cp_Row['cp_code'] ? "active" : "") ?>"><a href="./b2b_sale_item_list.php?cp_code=<?=$cp_Row['cp_code']?>"><?=$cp_Row['cp_name']?></a></li>
                    <?}?>
					
				</ul>
				<div class="clearfix"></div>
			</div>
			<?
			include_once('./b2b_sale_item_list_detail.php');
			?>
		</div>
	</div>
</div>






<?php 
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
