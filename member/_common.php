<?php
include_once('../common.php');

if (isset($_REQUEST['sort']) && !preg_match("/(--|#|\/\*|\*\/)/", $_REQUEST['sort']))  {
    $sort = trim($_REQUEST['sort']);
    $sort = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\s]/", "", $sort);
} else {
    $sort = '';
}

if (isset($_REQUEST['sortodr']))  {
    $sortodr = preg_match("/^(asc|desc)$/i", $sortodr) ? $sortodr : '';
} else {
    $sortodr = '';
}

if(strpos($_SERVER['REQUEST_URI'] , 'member/member.center.php')){

}else{
    if (!$is_member) 
    {
        if (strpos($_SERVER['REQUEST_URI'] , 'member/point.php')) {
            goto_url('/auth/login.php?url=' . urlencode('/member/point.php'));
        } else {
            goto_url("/auth/login.php");
        }
        
    } 
}
