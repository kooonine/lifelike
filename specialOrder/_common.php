<?php

include_once ('../common.php');



$script .='<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />';



// jQuery, Bootstrap
$script .= '<script type="text/javascript" src="' . G5_URL . '/re/js/jquery-3.4.1.min.js"></script>';
$script .= '<script type="text/javascript" src="' . G5_URL . '/re/js/bootstrap.min.js"></script>';
$script .= '<script type="text/javascript" src="' . G5_URL . '/re/js/bootstrap.bundle.min.js"></script>';
$script .= '<script type="text/javascript" src="' . G5_URL . '/re/js/jquery.scrollbar.min.js"></script>';

$script .='<script src="' . G5_URL . '/js/common.js"></script>';

$script .='<script src="' . G5_URL . '/js/placeholders.min.js"></script>';


// if($_SERVER['REQUEST_SCHEME'] == 'http'){
//     $script .='<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>';
// }else if ($_SERVER['REQUEST_SCHEME'] == 'https'){
//     $script .='<script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>';
// }
$script .='<script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>';


$script .= '<script type="text/javascript" src="' . G5_URL . '/js/swiper/swiper.min.js"></script>';

$script .= '<script> var g5_is_mobile = "' . G5_IS_MOBILE . '" </script>';
$script .= '<style type="text/css">@import url("./specialOrder.css");</style>';

echo $script ;

?>
