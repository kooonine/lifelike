<? #!/usr/local/php53/bin/php
$chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
$root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));

include_once($root_path . '/../../common.php');
$rtnData = array();


$target_date = date("Ymd", strtotime("-1day"));

$sql_view_list = "SELECT TABLE_NAME FROM information_schema.VIEWS WHERE TABLE_NAME LIKE 'VIEW_LIST_{$target_date}_%'";
$view_list = sql_query($sql_view_list);

if ($view_list->num_rows > 0) {
    while (false != ($view = sql_fetch_array($view_list))) {
        sql_query("DROP VIEW " . $view['TABLE_NAME']);
    }
}

printf("%d VIEWS DROPPED\n", $view_list->num_rows);
