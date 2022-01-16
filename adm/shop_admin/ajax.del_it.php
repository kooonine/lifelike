<?php
include_once('./_common.php');

$it_id = trim($_POST['it_id']);


sql_query("INSERT lt_shop_item_del SELECT * FROM lt_shop_item WHERE it_id = $it_id");
sql_query("INSERT lt_shop_item_sub_del SELECT * FROM lt_shop_item_sub WHERE it_id = $it_id");
sql_query("INSERT lt_shop_item_finditem_del SELECT * FROM lt_shop_item_finditem WHERE it_id = $it_id");
sql_query("INSERT lt_shop_item_option_del SELECT * FROM lt_shop_item_option WHERE it_id = $it_id");

sql_query("DELETE FROM lt_shop_item WHERE it_id = $it_id");
sql_query("DELETE FROM lt_shop_item_sub WHERE it_id = $it_id");
sql_query("DELETE FROM lt_shop_item_finditem WHERE it_id = $it_id");
sql_query("DELETE FROM lt_shop_item_option WHERE it_id = $it_id");
sql_query("DELETE FROM lt_shop_cart WHERE it_id = $it_id AND ct_status ='쇼핑'");

return 'success';

?>