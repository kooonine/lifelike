<?php
$result = false;
$ios_devices = array(
    '/iPhone/',
    '/iPad/',
    '/iPod/'                                                                                                                                                                                                                                                                   );                                                                                                                                                                                                                                                                             
foreach ($ios_devices as $id) {
    $result = preg_match($id, $_SERVER['HTTP_USER_AGENT']);
    if (preg_match($id, $_SERVER['HTTP_USER_AGENT']) == true) {
        $result = true;
        break;
    }
}

if ($result) {
    header('Location: itms-apps://itunes.apple.com/kr/app/apple-store/id1473080254');
} else {
    header('Location: market://details?id=com.litandard.lifelike');
}