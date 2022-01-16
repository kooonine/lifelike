<?
include_once('./_common.php');

$result = array(
    "result" => false,
    "data" => array(),
    "msg" => ""
);
if (!$is_member) {
    $result["msg"] = 'NOT_FOUND_MEMBER';
    return_json($result);
}

if (!empty($action)) {
    switch ($action) {
        case "i":
        case "u":
            $ad_hp = hyphen_hp_number($ad_hp_1 . $ad_hp_2);
            $ad_zip1 = substr($ad_zip, 0, 3);
            $ad_zip2 = substr($ad_zip, 3, 2);
            $ad_default = $ad_default == "true" ? 1 : 0;
            $d = "
            ad_subject='" . clean_xss_attributes($ad_name) . "',
            ad_name='"    . clean_xss_attributes($ad_name) . "',
            ad_hp='"      . clean_xss_attributes($ad_hp) . "',
            ad_zip1='"    . clean_xss_attributes($ad_zip1) . "',
            ad_zip2='"    . clean_xss_attributes($ad_zip2) . "',
            ad_addr1='"   . clean_xss_attributes($ad_addr1) . "',
            ad_addr2='"   . clean_xss_attributes($ad_addr2) . "',
            ad_addr3='"   . clean_xss_attributes($ad_addr3) . "',
            ad_jibeon='"  . clean_xss_attributes($ad_addr_jibeon) . "',
            ad_default='" . clean_xss_attributes($ad_default) . "'";
            if (!empty($ad_id)) {
                $sql = sprintf("UPDATE %s SET %s WHERE mb_id='%s' AND ad_id='%s'", $g5['g5_shop_order_address_table'], $d, $member['mb_id'], $ad_id);
            } else {
                $sql = sprintf("INSERT INTO %s SET %s, mb_id='%s'", $g5['g5_shop_order_address_table'], $d, $member['mb_id']);
            }

            if ($ad_default == 1) {
                $sql_clear_default = "UPDATE {$g5['g5_shop_order_address_table']} SET ad_default=0 WHERE mb_id='{$member['mb_id']}'";
                sql_query($sql_clear_default);
            }
            $result['result'] = sql_query($sql);
            break;
        case "r":
            if (!empty($ad_id)) {
                $sql = "SELECT * FROM {$g5['g5_shop_order_address_table']} WHERE mb_id='{$member['mb_id']}' AND ad_id='{$ad_id}'";
            } else {
                $sql = "SELECT * FROM {$g5['g5_shop_order_address_table']} WHERE mb_id='{$member['mb_id']}' ORDER BY ad_id DESC";
            }
            $db_sql = sql_query($sql);
            $tmp_data = array();

            while (false != ($addr = sql_fetch_array($db_sql))) {
                $tmp_data[] = $addr;
            }

            $result['result'] = true;
            $result['data'] = $tmp_data;

            break;
        case "d":
            if (!empty($ad_id)) {
                $sql = "DELETE FROM {$g5['g5_shop_order_address_table']} WHERE ad_id='{$ad_id}' AND mb_id='{$member['mb_id']}'";
                $result['result'] = sql_query($sql);
            } else {
                $result["msg"] = 'NOT_FOUND_ID';
            }
            break;
        case "default":
            if (!empty($ad_id)) {
                $sql_clear_default = "UPDATE {$g5['g5_shop_order_address_table']} SET ad_default=0 WHERE mb_id='{$member['mb_id']}'";
                sql_query($sql_clear_default);
                $sql = "UPDATE {$g5['g5_shop_order_address_table']} SET ad_default=1 WHERE ad_id='{$ad_id}' AND mb_id='{$member['mb_id']}'";
                $result['result'] = sql_query($sql);
            } else {
                $result["msg"] = 'NOT_FOUND_ID';
            }
            break;
    }
} else {
    $result["msg"] = 'NOT_FOUND_ACTION';
}

return_json($result);
