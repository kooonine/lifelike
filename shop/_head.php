<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(G5_IS_MOBILE)
    include_once(G5_MOBILE_PATH.'/head.php');
else
    include_once(G5_PATH.'/head.php');
?>