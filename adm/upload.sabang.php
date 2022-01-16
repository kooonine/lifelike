<?php
include_once("./_common.php");
include_once('../lib/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

// function get_url_fsockopen($url,$destpath) {
//     $URL_parsed = parse_url($url);
    
//     $host = $URL_parsed["host"];
//     $port = $URL_parsed["port"];
//     if ($port==0)
//     $port = 80;
//     $path = $URL_parsed["path"];
//     if ($URL_parsed["query"] != "")
//     $path .= "?".$URL_parsed["query"];
    
//     $out = "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n";
    
//     $fp = fsockopen($host, $port, $errno, $errstr, 30);
//     if (!$fp) {
//         echo "$errstr ($errno)<br>\n";
//     } else {
//         fputs($fp, $out);
//         $body = false;
//         while (!feof($fp)) {
//         $s = fgets($fp, 128);
//         if ( $body )
//         $in .= $s;
//         if ( $s == "\r\n" )
//         $body = true;
//         }
//         fclose($fp);
//         file_put_contents($destpath, $in);
//         }
// }

function column_char($i)
{
    return chr(65 + $i);
}

function raw_json_encode($input)
{
    return preg_replace_callback(
        '/\\\\u([0-9a-zA-Z]{4})/',
        function ($matches) {
            return mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UTF-16');
        },
        json_encode($input)
    );
}

if (!function_exists('iconv_utf8')) {
    function iconv_utf8(string $str)
    {
        return iconv('EUC-KR', 'UTF-8', $str);
    }
}

$file = $_FILES['sabang_excel']['tmp_name'];

if (!empty($file)) {

    if (!empty($db_clear)) {
        // DB 초기화 하고 시작
        sql_query("truncate table lt_shop_item");
        sql_query("truncate table lt_shop_item_finditem");
        sql_query("truncate table lt_shop_item_option");
        sql_query("truncate table lt_shop_item_sub");
    }

    $data = new Spreadsheet_Excel_Reader();

    // Set output Encoding.
    $data->setOutputEncoding('UTF-8');
    $data->read($file);

    $items = array();
    $models = array();

    // 업로드된 EXCEL 파일의 헤더부분을 제외하고 반복문 시작
    for ($i = 5; $i <= $data->sheets[0]['numRows']; $i++) {
        // for ($i = 3; $i <= 8; $i++) {
        $tmp_item = array();
        $tmp_filter = array();

        $tmp_etc['model'] = trim($data->sheets[0]['cells'][$i][10]); //모델명
        $tmp_etc['code'] = trim($data->sheets[0]['cells'][$i][13]); //모델명
        $tmp_etc['category'] = trim($data->sheets[0]['cells'][$i][2]); //카테고리
        $tmp_item['it_name'] = trim($data->sheets[0]['cells'][$i][8]); //상품명 수정
        if($tmp_item['it_name']) {
            $expStr=explode("]", $tmp_item['it_name']);
            if (!$expStr[1]) $tmp_item['it_name'] = $expStr[0];
            else $tmp_item['it_name'] = $expStr[1];
            if ($tmp_item['it_name'][0] == ' ') $tmp_item['it_name'] = substr($tmp_item['it_name'], 1);
        }

        $tmp_size = trim(trim($data->sheets[0]['cells'][$i][37]));

        if (!empty($tmp_size)) {
            $tmp_item['it_name'] .= "(" . strtoupper($tmp_size) . ")";
        }
        $tmp_item['it_brand'] = trim($data->sheets[0]['cells'][$i][12]); //브랜드
        $tmp_item['it_search_word'] = trim($data->sheets[0]['cells'][$i][14]); //검색어
        $tmp_item['it_maker'] = trim($data->sheets[0]['cells'][$i][19]); //제조사
        $tmp_item['it_origin'] = trim($data->sheets[0]['cells'][$i][20]); //원산지

        $tmp_item['it_img1'] = trim($data->sheets[0]['cells'][$i][38]); //이미지1
        $tmp_item['it_img2'] = trim($data->sheets[0]['cells'][$i][40]); //이미지2
        $tmp_item['it_img3'] = trim($data->sheets[0]['cells'][$i][41]); //이미지3
        $tmp_item['it_img4'] = trim($data->sheets[0]['cells'][$i][42]); //이미지4
        $tmp_item['it_img5'] = trim($data->sheets[0]['cells'][$i][43]); //이미지5

        // $tmp_item['it_explan'] = "";
        // $tmp_item['it_mobile_explan'] = "";
        // $tmp_item['it_explan2'] = "";
        $tmp_item['it_explan'] = trim($data->sheets[0]['cells'][$i][49]); //상세설명
        $tmp_item['it_mobile_explan'] = trim($data->sheets[0]['cells'][$i][49]); //상세설명 모바일
        $tmp_item['it_explan2'] = trim($data->sheets[0]['cells'][$i][80]); //제품설명(TXT)

        $tmp_filter[1] = trim($data->sheets[0]['cells'][$i][3]) == "" ? array() : explode('/', trim($data->sheets[0]['cells'][$i][3])); //필터 - 사이즈
        $tmp_filter[2] = trim($data->sheets[0]['cells'][$i][4]) == "" ? array() : explode('/', trim($data->sheets[0]['cells'][$i][4])); //필터 - 시즌
        $tmp_filter[3] = trim($data->sheets[0]['cells'][$i][5]) == "" ? array() : explode('/', trim($data->sheets[0]['cells'][$i][5])); //필터 - 충전재
        $tmp_filter[4] = trim($data->sheets[0]['cells'][$i][6]) == "" ? array() : explode('/', trim($data->sheets[0]['cells'][$i][6])); //필터 - 스타일
        $tmp_filter[5] = trim($data->sheets[0]['cells'][$i][7]) == "" ? array() : explode('/', trim($data->sheets[0]['cells'][$i][7])); //필터 - 패브릭

        $tmp_color = trim($data->sheets[0]['cells'][$i][35]);
        // $tmp_size = substr($tmp_etc['code'], 14, 1);
        // 수정필요
        $sql_check_model = "SELECT COUNT(*) AS CNT FROM lt_shop_item_option WHERE io_order_no='{$tmp_etc['model']}' AND io_color_name = '$tmp_color' AND  io_hoching =  '$tmp_size'";
        $check_model = sql_fetch($sql_check_model);

        $io_stock_qty = 0;
        $stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(1,0,$tmp_etc['model'],$tmp_color,$tmp_size);
        if (count($stockSamjin) == 0) {
            $io_stock_qty = 0;
        } else {
          for ($j =0; $j < count($stockSamjin); $j++) {
            if ($stockSamjin[$j]['C_NO'] == 2 || $stockSamjin[$j]['C_NO'] == 3 || $stockSamjin[$j]['C_NO'] == 4 ||  $stockSamjin[$j]['C_NO'] == 8) {
                $io_stock_qty += $stockSamjin[$j]['STOCK2'];
              } 
          }
        }

        $tmp_item['it_stock_qty'] = $io_stock_qty;

        if ($check_model['CNT'] <= 0) {
            if (!isset($models[$tmp_etc['code']])) $models[$tmp_etc['code']] = array('option' => array());
            $tag_price = !empty($data->sheets[0]['cells'][$i][33]) ? $data->sheets[0]['cells'][$i][33] : 0;
            $sell_price = !empty($data->sheets[0]['cells'][$i][32]) ? $data->sheets[0]['cells'][$i][32] : 0;
            $models[$tmp_etc['code']]['option'][$tmp_etc['code']] = array(
                'code' => $tmp_etc['model'],
                'color' => $tmp_color,
                'size' => $tmp_size,
                'tag_price' => $tag_price,
                'sell_price' => $sell_price,
                'io_stock_qty' => $io_stock_qty
            );
            $models[$tmp_etc['code']]['item'] = $tmp_item;
            $models[$tmp_etc['code']]['filter'] = $tmp_filter;
            $models[$tmp_etc['code']]['etc'] = $tmp_etc;
        } else {
            echo $tmp_etc['code'] . "<br>";
        }

        $value_array = array();
        $it_info_value = array();

        $key = "제품소재 및 충전재";
        $val = trim($data->sheets[0]['cells'][$i][89]);
        $article = array("name" => $key, "value" => $val);
        $value_array[] = $article;

        $key = "색상";
        $val = trim($data->sheets[0]['cells'][$i][90]);
        $article = array("name" => $key, "value" => $val);
        $value_array[] = $article;

        $key = "사이즈";
        $val = trim($data->sheets[0]['cells'][$i][91]);
        $article = array("name" => $key, "value" => $val);
        $value_array[] = $article;

        $key = "제조사";
        $val = trim($data->sheets[0]['cells'][$i][92]);
        $article = array("name" => $key, "value" => $val);
        $value_array[] = $article;

        $key = "제조국";
        $val = trim($data->sheets[0]['cells'][$i][93]);
        $article = array("name" => $key, "value" => $val);
        $value_array[] = $article;

        $key = "세탁방법 및 주의사항";
        $val = trim($data->sheets[0]['cells'][$i][94]);
        $article = array("name" => $key, "value" => $val);
        $value_array[] = $article;

        $key = "제품구성";
        $val = trim($data->sheets[0]['cells'][$i][95]);
        $article = array("name" => $key, "value" => $val);
        $value_array[] = $article;

        $key = "품질보증기준";
        $val = trim($data->sheets[0]['cells'][$i][96]);
        $article = array("name" => $key, "value" => $val);
        $value_array[] = $article;

        $key = "A/S 책임자와 전화번호";
        $val = trim($data->sheets[0]['cells'][$i][97]);
        $article = array("name" => $key, "value" => $val);
        $value_array[] = $article;


        $it_info_value = json_encode_raw($value_array, JSON_UNESCAPED_UNICODE);
        $models[$tmp_etc['code']]['info'] = $it_info_value;
    }

    // 카테고리 매칭정보
    // $categories = array(
    //     '침구세트' => 101010,
    //     '이불솜' => 101020,
    //     '베개솜' => 102010,
    //     '차렵이불' => 101030,
    //     '누비이불' => 101040,
    //     '홑이불' => 101050,
    //     '토퍼' => 102030,
    //     '패드' => 102020,
    //     '스프레드' => 101060,
    //     '커버세트' => 103010,
    //     '이불커버' => 103020,
    //     '매트리스커버' => 103030,
    //     '베개커버' => 103040,
    //     '플랫시트' => 103050,
    //     '프로텍터' => 103060,
    //     '쿠션/쿠션커버' => 104010,
    //     '타월' => 104020,
    //     '담요' => 104030
    // );

    // 카테고리 매칭정보 '베개/토퍼,베개솜,메모리폼 베개' => 10201020,
    $categories = array(
        '구스다운,이불' => 101010,
        '구스다운,베개' => 101020,
        '침구,이불커버' => 102010,
        '침구,베개커버' => 102020,
        '침구,차렵이불/누비이불' => 102030,
        '침구,침구세트' => 102040,
        '침구,패드/매트커버' => 102050,
        '침구,토퍼' => 102060,
        '침구,홑이불/스프레드' => 102070,
        '솜/속통,이불솜' => 103010,
        '솜/속통,베개솜' => 103020,
        '키즈,침구' => 104010,
        '키즈,리빙' => 104020,
        '홈데코,쿠션/쿠션커버' => 104110,
        '홈데코,리빙' => 104120,
        '메모리폼베개,베개' => 104210,
        '메모리폼베개,커버' => 104220
    );

    // 브랜드 매칭정보
    $brands = array(
        '[BY 소프라움]' => 'SOFRAUM',
        '그라치아노' => 'GRAZIANO',
        '소프라움' => 'SOFRAUM',
        '랄프로렌홈' => 'RALPH LAUREN home',
        '로자리아' => 'ROSALIA',
        '링스티드던' => 'RINGSTED DUN',
        '베온트레' => 'BEONTRE',
        '쉐르단' => 'SHERIDAN',
        'LIFELIKE' => 'LIFELIKE',
        'LBL' => 'LBL MAISON',
        '베라왕홈' => 'VERAWANG HOME',
        '코펜하겐던' => 'LIFELIKE',
        '홈바이템퍼' => 'Home By Temper',
    );

    $sql_default = "SELECT * FROM lt_shop_default";
    $default = sql_fetch($sql_default);

    // dd($models);
    foreach ($models as $model => $item) {
        if (empty($item['etc']['model'])) die("EMPTY MODEL CODE : " . $item['item']['it_name']);

        $category = isset($categories[$item['etc']['category']]) ? $categories[$item['etc']['category']] : null;
        if (!empty($category)) {
            $it_id = null;
            for ($i = 1; $it_id == null; $i++) {
                $sql_it_id = "SELECT right(concat('000000',max(substr(it_id,10,6))+{$i}),6) AS it_id FROM lt_shop_item WHERE ca_id = '$category'";
                $db_it_id = sql_fetch($sql_it_id);
                if (empty($db_it_id['it_id'])) $db_it_id['it_id'] = "000001";
                $tmp_it_id = sprintf("%03d%03d%03d%s", substr($category, 0, 2), substr($category, 2, 2), substr($category, 4, 2), $db_it_id['it_id']);
                
                // 추가 카테고리 
                if (strlen($category) > 7) {
                    $tmp_it_id = sprintf("%03d%03d%03d%s", substr($category, 2, 2), substr($category, 4, 2), substr($category, 6, 2), $db_it_id['it_id']);
                    $tmp_it_id = $tmp_it_id + 500000 + $i;
                    $tmp_it_id = '0'.$tmp_it_id;
                }


                $sql_check_id = "SELECT COUNT(*) AS CNT FROM lt_shop_item WHERE it_id='{$tmp_it_id}'";
                $db_check_id = sql_fetch($sql_check_id);

                if ($db_check_id['CNT'] == 0) {
                    $it_id = $tmp_it_id;
                    break;
                }

                if ($i == 20) {
                    die("it_id".$tmp_it_id);
                }
            }

            echo $it_id . "<br>";
            ob_flush();

            $sql_set = array();
            $min_option = array();
            $min_price = 9999999999;
            $discount = 0;

            // option 등록
            // 낮은가격순 정렬
            $tmp_item_options = array();
            foreach ($item['option'] as $code => $option) {
                $tmp_item_options[$option['sell_price']] = $option;
                if ($option['sell_price'] < $min_price) {
                    $min_price = $option['sell_price'];
                    $discount = $option['tag_price'] - $option['sell_price'];
                    $min_option = $option;
                }
            }

            ksort($tmp_item_options);
            $item['option'] = $tmp_item_options;

            if (count($item['option'] > 0)) {
                $discount_type = $discount > 0 ? 2 : 0;
                $sql_its_sub = sprintf(
                    "INSERT INTO lt_shop_item_sub SET
                    it_id='%s',
                    its_sap_code='%s',
                    its_order_no='%s',
                    its_item='%s',
                    its_rental_price=0,
                    its_price=%s,
                    its_option_subject='색상/사이즈',
                    its_supply_subject='',
                    its_final_price=%s,
                    its_final_rental_price=0,
                    its_discount_type=%d,
                    its_discount=%d,
                    its_free_laundry=0,
                    its_free_laundry_delivery_price=0,
                    its_laundry_use=0,
                    its_laundry_price=0,
                    its_laundry_delivery_price=0,
                    its_laundrykeep_use=0,
                    its_laundrykeep_lprice=0,
                    its_laundrykeep_kprice=0,
                    its_laundrykeep_delivery_price=0,
                    its_repair_use=0,
                    its_repair_price=0,
                    its_repair_delivery_price=0,
                    its_time=NOW(),
                    its_update_time=NOW(),
                    its_zbox_name='',
                    its_zbox_price=0",
                    $it_id,
                    $item['etc']['model'],
                    $item['etc']['model'],
                    $item['item']['it_name'],
                    $min_option['tag_price'],
                    $min_option['sell_price'],
                    $discount_type,
                    $discount
                );

                // 상품옵션등록
                sql_query($sql_its_sub);
            }

            $its_no = sql_insert_id();

            foreach ($item['option'] as $code => $option) {
                $io_id = $option['color'] . "_" . $option['size'];
                $io_price = $option['sell_price'] - $min_price;
                $sap_code = $option['code'] . '_' . $io_id;

                // total
                $totalCode = $option['code'];
                $totalSize = $option['size'];

                // $sql_set[] = sprintf("INSERT INTO lt_shop_item_option SET io_id='%s',io_type=0,it_id='%s',io_price=%s,io_stock_qty=0,io_noti_qty=0,io_use=1,its_no='%s',io_sapcode_color_gz='%s',io_order_no='%s',io_color_name='%s',io_hoching='%s',io_sap_price=%s", $io_id, $it_id, $io_price, $its_no, $sap_code, $option['code'], $option['color'], $option['size'], $option['tag_price']);
                $sql_set[] = sprintf("INSERT INTO lt_shop_item_option SET io_id='%s',io_type=0,it_id='%s',io_price=%s,io_stock_qty='%s',io_noti_qty=20,io_use=1,its_no='%s',io_sapcode_color_gz='%s',io_order_no='%s',io_color_name='%s',io_hoching='%s',io_sap_price=%s", $io_id, $it_id, $io_price, $option['io_stock_qty'], $its_no, $sap_code, $option['code'], $option['color'], $option['size'], $option['tag_price']);
            }


            // 이미지 복사
            for ($ii = 1; $ii < 6; $ii++) {
                $tmp_img_key = "it_img" . $ii;
                $tmp_img_url = str_replace("'", "", $item['item'][$tmp_img_key]);

                if (!empty($tmp_img_url)) {
                    $pathinfo = pathinfo($tmp_img_url);
                    $filepath = str_replace('https://lifelike.co.kr', G5_PATH, $tmp_img_url);
                    $destpath = '../data/item/' . $it_id . '/' . $pathinfo['basename'];

                    $path = pathinfo($destpath);
                    $path_it = pathinfo($path['dirname']);
                    if (!is_dir($path_it['dirname'])) mkdir($path_it['dirname']);
                    if (!is_dir($path['dirname'])) mkdir($path['dirname']);

                    if (file_exists($filepath)) {
                        exec("cp " . $filepath . " " . $destpath);
                        $item['item'][$tmp_img_key] = $it_id . '/' . $path['basename'];
                    } else {
                        // 서버에 이미지 없는경우. 다운받아서 저장
                        $tmp_img_bin = file_get_contents($filepath);
                        // $tmp_img_bin = get_url_fsockopen($filepath,$destpath);
                        file_put_contents($destpath, $tmp_img_bin);
                        if (file_exists($destpath)) {
                            $item['item'][$tmp_img_key] = $it_id . '/' . $path['basename'];
                        } else {
                            unset($item['item'][$tmp_img_key]);
                        }
                    }
                }
            }
            // 사진 이동
            // if (isset($_FILES['ba_image']) && is_uploaded_file($_FILES['ba_image']['tmp_name'])) {
    
            //     $ftp_server = "litandard-org.daouidc.com"; 
            //     $ftp_port = 2021; 
            //     $ftp_user_name = "litandard"; 
            //     $ftp_user_pass = "flxosekem_ftp!@34"; 
            //     $conn_id = ftp_connect($ftp_server,$ftp_port) or die("Couldn't connect to $ftp_server");
            //     $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<Br> Couldn't connect to $ftp_server"); 
            //     ftp_pasv($conn_id, true);
            //     $filepath = $_FILES['ba_image']['tmp_name'];
            
            //     $path = md5(microtime()) . '.' . $_FILES['ba_image']['name'];
            
            //     // dlRJ QlTlqkf ??? ??? 
            //     $upload = ftp_put($conn_id, '/newbanner/'.$path, $filepath, FTP_BINARY);
            //     $path = 'https://lifelikecdn.co.kr/newbanner/'.$path;
            //     if ($upload) {
            //         $tpType = 1;
                    
            //         // $tpNum = sql_fetch(" SELECT tp_num+1 AS tn FROM lt_temper WHERE tp_type = $tpType ORDER BY tp_num DESC LIMIT 1 ");
            //         // $sql = " INSERT INTO lt_temper (tp_img,tp_use,tp_num,tp_type) VALUES ('$path','$tp_use', '{$tpNum['tn']}',$tpType)";
            //         // sql_query($sql);
            //     } else {
                   
            //     }
            // }
            // 필터 정보 생성
            $sql_delete_filter = "DELETE FROM lt_shop_item_finditem WHERE it_id='{$it_id}'";
            sql_query($sql_delete_filter);
            foreach ($item['filter'] as $fi_id => $fi_contents) {
                foreach ($fi_contents as $fic) {
                    $sql_insert_finditem = "INSERT INTO lt_shop_item_finditem SET it_id='{$it_id}',fi_id={$fi_id},fi_contents='{$fic}'";
                    sql_query($sql_insert_finditem);
                }
            }

            $arr_it_info_finditem = array(
                array("fi_id" => "1", "fi_subject" => "사이즈", "fi_contents" => implode(',', $item['filter'][1])),
                array("fi_id" => "2", "fi_subject" => "시즌", "fi_contents" => implode(',', $item['filter'][2])),
                array("fi_id" => "3", "fi_subject" => "충전재", "fi_contents" => implode(',', $item['filter'][3])),
                array("fi_id" => "4", "fi_subject" => "스타일", "fi_contents" => implode(',', $item['filter'][4])),
                array("fi_id" => "5", "fi_subject" => "패브릭", "fi_contents" => implode(',', $item['filter'][5])),
            );
            $it_info_finditem = raw_json_encode($arr_it_info_finditem);

            // totalsize 등록여부 
            $totalSql = " SELECT COUNT(*) AS CNT FROM lt_shop_item WHERE lt_order_no = '{$totalCode}' ";
            $totalRes = sql_fetch($totalSql);
            $totalSizeCheck = 1;
            if ($totalRes['CNT'] > 0) $totalSizeCheck = NULL;

            // item 등록
            $sql_common = "
                it_id               = '{$it_id}',
                ca_id               = '" . substr($category, 0, 8) . "',
                ca_id2              = '',
                ca_id3              = '',
                it_skin             = 'basic',
                it_mobile_skin      = 'basic',
                it_name             = '" . $item['item']['it_name'] . "',
                it_maker            = '" . $item['item']['it_maker'] . "',
                it_origin           = '" . $item['item']['it_origin'] . "',
                it_brand            = '" . $brands[$item['item']['it_brand']] . "',
                it_model            = '',
                it_option_subject   = '사이즈',
                it_supply_subject   = '',
                it_type1            = 0,
                it_type2            = 0,
                it_type3            = 0,
                it_type4            = 0,
                it_type5            = 0,
                it_basic            = '',
                it_explan           = '" . addslashes(trim($item['item']['it_explan'])) . "',
                it_explan2          = '" . addslashes(strip_tags(trim($item['item']['it_explan2']))) . "',
                it_mobile_explan    = '" . addslashes(trim($item['item']['it_explan'])) . "',
                it_mobile_explan_use = 0,
                it_cust_price       = 0,
                it_price            = $min_price,
                it_rental_price     = 0,
                it_discount_price   = $discount,
                it_point            = 3,
                it_point_type       = 3,
                it_supply_point     = 0,
                it_notax            = 0,
                it_sell_email       = '',
                it_use              = 1,
                it_nocoupon         = 0,
                it_soldout          = 0,
                it_stock_qty        = '" . $item['item']['it_stock_qty'] . "',
                it_stock_sms        = 0,
                it_noti_qty         = 0,
                it_sc_type          = 0,
                it_sc_method        = 0,
                it_sc_price         = 3000,
                it_sc_minimum       = 30000,
                it_sc_qty           = 0,
                it_buy_min_qty      = 0,
                it_buy_max_qty      = 10,
                it_head_html        = '',
                it_tail_html        = '',
                it_mobile_head_html = '',
                it_mobile_tail_html = '',
                it_ip               = '{$_SERVER['REMOTE_ADDR']}',
                it_order            = 1,
                it_tel_inq          = 0,
                it_info_gubun       = 20,
                it_info_value       = '{$item["info"]}',
                it_shop_memo        = '',
                ec_mall_pid         = '',
                it_img1             = '{$item['item']["it_img1"]}',
                it_img2             = '{$item['item']["it_img2"]}',
                it_img3             = '{$item['item']["it_img3"]}',
                it_img4             = '{$item['item']["it_img4"]}',
                it_img5             = '{$item['item']["it_img5"]}',
                it_img6             = '',
                it_img7             = '',
                it_img8             = '',
                it_img9             = '',
                it_img10            = '',
                it_time             = NOW(),
                it_update_time      = NOW(),

                it_item_type        = 0,
                it_item_rental_month = 36,
                it_search_word = '{$item['item']["it_search_word"]}',
                it_level_sell = 0,
                it_point_only = 1,
                it_point_use = 1,
                it_use_use = 1,
                it_review_use = 0,

                it_view_list_items = '',
                it_view_detail_items = '',

                it_send_type ='택배',
                it_send_term_start =3,
                it_send_term_end =5,
                it_send_condition ='판매',
                it_individual_costs_use =0,
                
                it_delivery_company ='롯데택배',
                it_return_costs =3000,
                it_roundtrip_costs =0,
                it_return_zip ='{$default['de_return_zip']}',
                it_return_address1 ='{$default['de_return_address1']}',
                it_return_address2 ='{$default['de_return_address2']}',
                it_info_finditem = '$it_info_finditem',
                it_status = '',
                it_period = '',

                it_total_size = '{$totalSizeCheck}',
                lt_order_no = '{$totalCode}',
                it_size_info = '{$totalSize}'
                ";


            $sql_set[] = "INSERT INTO lt_shop_item SET " . $sql_common;

            foreach ($sql_set as $si => $sql) {
                // echo $sql . "<br>";
                sql_query($sql);
            }
            ob_flush();
        }
    }

    ob_end_flush();
    ob_clean();

    goto_url("/adm/upload.sabang.php");
}

$g5['title'] = "사방넷 EXCEL 등록";
include_once('./admin.head.php');
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <form method="POST" enctype="multipart/form-data">
                <div class="tbl_frm01 tbl_wrap">
                    <table>
                        <colgroup>
                            <col class="grid_4">
                            <col>
                            <col class="grid_3">
                        </colgroup>
                        <tr>
                            <th scope="row">주의사항</th>
                            <td colspan="2">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <p>* Excel 파일은 반드시 "Excel 97 - 2003 통합 문서" 형식으로 저장해주세요</p>
                                    <p>* 등록된 상품이 삭제될 수 있습니다. 실행전 전산담당자 문의해주십시오.</p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">사방넷 Excel</th>
                            <td colspan="2">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="file" name="sabang_excel" />
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <button type="submit">등록</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?
include_once('./admin.tail.php');
