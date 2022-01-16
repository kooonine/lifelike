<?php
include_once('../../common.php');

if ($test) {
    printf("%s,%s\r\n", '테스트0', 'nemesisler+test0@gmail.com');
    printf("%s,%s\r\n", '테스트1', 'nemesisler+test1@gmail.com');
    printf("%s,%s\r\n", '테스트2', 'nemesisler+test2@gmail.com');
    printf("%s,%s\r\n", '테스트3', 'nemesisler+test3@gmail.com');
    printf("%s,%s\r\n", '테스트4', 'nemesisler+test4@gmail.com');
    printf("%s,%s\r\n", '테스트5', 'nemesisler+test5@gmail.com');
    printf("%s,%s\r\n", '테스트6', 'nemesisler+test6@gmail.com');
    printf("%s,%s\r\n", '테스트7', 'nemesisler+test7@gmail.com');
    printf("%s,%s\r\n", '테스트8', 'nemesisler+test8@gmail.com');
    printf("%s,%s\r\n", '테스트9', 'nemesisler+test9@gmail.com');
} else {
    $type = !empty($_GET['type']) ? $_GET['type'] : 'all';
    $reject_type = !empty($_GET['reject']) ? $_GET['reject'] : 2;

    $sql_list = "SELECT mb_name,mb_email FROM lt_member WHERE mb_email != ''";
    if ($reject_type == 2) $sql_list .= " AND mb_mailling=1";

    $sql_list .= " GROUP BY mb_email";

    $db_list = sql_query($sql_list);
    while (($list = sql_fetch_array($db_list))) {
        printf("%s,%s\r\n", $list['mb_name'], $list['mb_email']);
    }
}
