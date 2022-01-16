<?
$sub_menu = '92';
include_once('./_common.php');
auth_check($auth[substr($sub_menu,0,2)], "w");
$g5['title'] = '주문내역 현황';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
$sql = "select * from lt_member_company where mb_id = '".$member['mb_id']."' ";
$cp = sql_fetch($sql);
if($od_type == "") $od_type = "O";
?>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<? include_once ('./orderlist_b.php'); ?>
		</div>
	</div>
</div>
<?
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
