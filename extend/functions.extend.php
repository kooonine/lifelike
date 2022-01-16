<?php
if (!function_exists('dd')) {
    function dd($resource)
    {
        echo "<pre>";
        var_dump($resource);
        echo "</pre>";
        exit();
    }
}
if (!function_exists('print_raw')) {
    function print_raw($resource = array())
    {
        if (is_array($resource)) {
            $is_cli = php_sapi_name() == "cli";
            if ($is_cli) {
                foreach ($resource as $row) echo $row . "\n";
            } else {
                foreach ($resource as $row) echo "<div>" . $row . "</div>";
            }

            return true;
        }

        return false;
    }
}
if (!function_exists('return_json')) {
    function return_json($data)
    {
        echo json_encode($data);
        exit();
        return;
    }
}
if (!function_exists('get_item_option')) {
    function get_item_option($it_id)
    {
        $ret = array();
        $sql = sprintf("SELECT s.its_sap_code, s.its_order_no, s.its_item, s.its_option_subject, o.io_id, o.io_sapcode_color_gz FROM lt_shop_item_sub s LEFT JOIN lt_shop_item_option o ON o.io_order_no=s.its_order_no WHERE s.it_id=%d GROUP BY o.io_sapcode_color_gz", $it_id);
        $res_options = sql_query($sql);

        if ($res_options) {

            while ($opt = sql_fetch_array($res_options)) {
                $ret[$opt['io_sapcode_color_gz']] = array(
                    'item' => $opt['its_item'],
                    'id' => $opt['io_id'],
                    'subject' => $opt['its_option_subject'],
                    'order_no' => $opt['its_order_no'],
                    'sapcode' => $opt['its_sap_code'],
                    'sapcode_color_gz' => $opt['io_sapcode_color_gz']
                );
            }
        }

        return $ret;
    }
}

if (!function_exists('get_disp_id')) {
    function get_disp_id($id, $type = 'R')
    {
        return strtoupper($type) . '-' . substr($id, 0, 8) . '-' . substr($id, 8, 6);
    }
}

if (!function_exists('is_valid_password')) {
    function is_valid_password($reg_mb_password)
    {
        $special = preg_match('/[!@#$%^&+=]/', $reg_mb_password);
        $lowercase = preg_match('/[a-z]/', $reg_mb_password);
        $number    = preg_match('/[0-9]/', $reg_mb_password);

        if (!$special || !$lowercase || !$number || strlen($reg_mb_password) < 8) {
            return false;
        }
        return true;
    }
}

if (!function_exists('get_star_string')) {
    function get_star_string($str)
    {
        $rstr = mb_substr($str, 0, 1, "UTF-8");
        $d = mb_strlen($str, "UTF-8");  
        if ($d > 2) {
            for ($i = $d; $i >= $d ; $i--) $rstr .= "*";
            $rstr .= mb_substr($str, -1, 1, "UTF-8");
        } else {
            $rstr .= "*";
        }
        return $rstr;
    }
}

if (!function_exists('covered_string')) {
    function covered_string($str, $cover = '*', $start = 0, $end = 0)
    {
        $str_result = $str;
        $str_len = mb_strlen($str, "UTF-8");
        $total_count = $start + $end;

        if ($total_count >= $str_len) return sprintf("%" . $str_len . "s", $cover);

        if ($start > 0) {
            $tmp_str = mb_substr($str_result, $start - 1, $str_len - $start, "UTF-8");
            $tmp_cover = "";
            for ($i = 0; $i < $start; $i++) $tmp_cover .= $cover;
            $str_result = sprintf("%s%s", $tmp_cover, $tmp_str);
        }
        if ($end > 0) {
            $tmp_str = mb_substr($str_result, 0, $str_len - $end, "UTF-8");
            $tmp_cover = "";
            for ($i = 0; $i < $end; $i++) $tmp_cover .= $cover;
            $str_result = sprintf("%s%s", $tmp_str, $tmp_cover);
        }

        return $str_result;
    }
}

if (!function_exists('user_history')) {
    function user_history($action = "LIST", $data = array())
    {
        if (!empty($data)) {
            $hi_table = "lt_history";
            $action = strtoupper($action);
            $sqlset = array();

            switch ($action) {
                case "UPDATE":
                    $sqlset[] = "DELETE FROM {$hi_table} WHERE mb_id='{$data['mb_id']}' AND it_id='{$data['it_id']}' AND hi_type='{$data['type']}'";
                case "CREATE":
                    $sqlset[] = "INSERT INTO {$hi_table} SET mb_id='{$data['mb_id']}',it_id='{$data['it_id']}',hi_type='{$data['type']}'";
                    break;
                case "DELETE":
                    $sqlset[] = "DELETE FROM {$hi_table} WHERE mb_id='{$data['mb_id']}' AND it_id='{$data['it_id']}' AND hi_type='{$data['type']}'";
                    break;
                default:
                    $result = array('COUNT' => 0, 'ITEMS' => array());
                    $tmpSql = "SELECT * FROM {$hi_table} WHERE mb_id='{$data['mb_id']}'";
                    if (isset($data['type'])) $tmpSql .= " AND hi_type='{$data['type']}'";
                    $tmpSql .= " ORDER BY hi_id DESC";
                    $db_list = sql_query($tmpSql);
                    $result['COUNT'] = $db_list->num_rows;
                    if ($db_list->num_rows > 0) {
                        for ($hi = 0; $history = (sql_fetch_array($db_list)); $hi++) {
                            if ($history['hi_type'] == 'item') {
                                $sql_item = "SELECT b.io_hoching, a.* FROM lt_shop_item AS a LEFT JOIN lt_shop_item_option AS  b ON(a.it_id = b.it_id) WHERE b.io_use = 1 AND a.it_id='{$history['it_id']}'";


                                $history['item'] = sql_fetch($sql_item);
                            }
                            $result['ITEMS'][] = $history;
                        }
                    }
                    return $result;
                    break;
            }

            if (!empty($sqlset)) {
                $result = false;
                foreach ($sqlset as $sql) {
                    $result = sql_query($sql);
                    if ($result == false) break;
                }

                return $result;
            }
        }

        return false;
    }
}

if (!function_exists('replace_hoching')) {
    function replace_hoching($io_hoching = "")
    {
        if (is_numeric(substr($io_hoching,0,1))) $io_hoching = 'S';
        return str_replace('*', 'X', strtoupper($io_hoching));
    }
}
