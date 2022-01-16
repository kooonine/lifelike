<?php
include_once "./_common.php";
require_once G5_LIB_PATH . "/claim.lib.php";

$cp_id = array(
    1803, 1804
);

$claim = new lt_claim;
$claim->setOrder("20200604000006");
$result = $claim->simulate("cancel", $cp_id);
dd($result);
