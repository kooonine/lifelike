<?php
include_once("./_common.php");
include_once('../lib/Excel/reader.php');

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
    for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {

        $test001 = trim($data->sheets[0]['cells'][$i][1]);
        $test002 = trim($data->sheets[0]['cells'][$i][2]);
        // $test003 = preg_replace("/\s+/", "", $test001);
        // $test004 = str_replace('�','',$test001);
        preg_match('/^([\x00-\x7e]|.{2})*/', $test001, $temp_str);



        sql_query("UPDATE lt_shop_item SET it_name='{$test002}' WHERE it_id = '{$temp_str[0]}'");
        echo 'it_name: '.$test002 .'/ it_id :'.$temp_str[0].'<br>';
        // return;
        // for ($i = 3; $i <= 8; $i++) {
        // $it_id = trim($data->sheets[0]['cells'][$i][3]);
        // $ca_id = trim($data->sheets[0]['cells'][$i][1]);
        // $testtest = sql_fetch("SELECT it_total_size FROM lt_shop_item WHERE test001 = '$it_id' LIMIT 1");
        // if($testtest['it_total_size']==null) {
        //     sql_query("UPDATE lt_shop_item SET it_total_size='$ca_id' WHERE test001 = '$it_id'");
        //     echo 'it:id '.$it_id .'/ it_total_size :'.$ca_id.'<br>'; 
        // }
        // sql_query("UPDATE lt_shop_item SET it_total_size='$ca_id' WHERE test001 = '$it_id'");
        // echo 'it:id '.$it_id .'/ it_total_size :'.$ca_id.'<br>';
        
        continue;

    }


    return;
    // // 카테고리 매칭정보
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
        '홈데코,쿠션/쿠션커버' => 105010,
        '홈데코,리빙' => 105020,
        '메모리폼베개,베개' => 106010,
        '메모리폼베개,커버' => 106020
        
    );
    return;
    // 카테고리 매칭정보
    $categories = array(
        '이불,이불솜' => 101010,
        '이불,차렵이불' => 101020,
        '이불,차렵이불SET' => 101030,
        '이불,누비이불' => 101040,
        '이불,스프레드' => 101050,
        '이불,홑이불' => 101060,

        '베개/토퍼,베개솜,일반' => 10201010,
        '베개/토퍼,베개솜,메모리폼 베개' => 10201020,
        '베개/토퍼,토퍼' => 102020,

        '커버/패드,호텔베딩,이불커버' => 10301010,
        '커버/패드,호텔베딩,베개커버' => 10301020,
        '커버/패드,호텔베딩,커버SET' => 10301030,
        '커버/패드,호텔베딩,매트리스커버' => 10301040,
        '커버/패드,호텔베딩,패드' => 10301050,
        '커버/패드,모던,이불커버' => 10302010,
        '커버/패드,모던,베개커버' => 10302020,
        '커버/패드,모던,커버SET' => 10302030,
        '커버/패드,모던,매트리스커버' => 10302040,
        '커버/패드,모던,패드' => 10302050,
        '커버/패드,베이직,이불커버' => 10303010,
        '커버/패드,베이직,베개커버' => 10303020,
        '커버/패드,베이직,커버SET' => 10303030,
        '커버/패드,베이직,매트리스커버' => 10303040,
        '커버/패드,베이직,패드' => 10303050,
        '커버/패드,내추럴,이불커버' => 10304010,
        '커버/패드,내추럴,베개커버' => 10304020,
        '커버/패드,내추럴,커버SET' => 10304030,
        '커버/패드,내추럴,매트리스커버' => 10304040,
        '커버/패드,내추럴,패드' => 10304050,
        '커버/패드,클래식,이불커버' => 10305010,
        '커버/패드,클래식,베개커버' => 10305020,
        '커버/패드,클래식,커버SET' => 10305030,
        '커버/패드,클래식,매트리스커버' => 10305040,
        '커버/패드,클래식,패드' => 10305050,

        '홈데코,쿠션/쿠션커버' => 104010,
        '홈데코,리빙' => 104020,

        '키즈,차렵이불' => 104110,
        '키즈,베개커버' => 104120,
        '키즈,패드' => 104130,
        '키즈,홑이불' => 104140,
        '키즈,스프레드' => 104150,
        '키즈,리빙' => 104160,

        '수입침구,SHERIDAN,이불커버' => 10421010,
        '수입침구,SHERIDAN,베개커버' => 10421020,
        '수입침구,SHERIDAN,매트리스커버' => 10421030,
        '수입침구,SHERIDAN,커버SET' => 10421040,
        '수입침구,RALPH LAUREN HOME,이불커버' => 10422010,
        '수입침구,RALPH LAUREN HOME,베개커버' => 10422020,
        '수입침구,RALPH LAUREN HOME,패드' => 10422030,
        '수입침구,RALPH LAUREN HOME,홈데코' => 10422040,
        '수입침구,GRAZIANO,커버SET' => 10423010,
        '수입침구,RINGSTED DUN,이불솜' => 10424010,
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
            $it_id = $item['etc']['it_id'];
            echo "success : ";
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
                    "UPDATE lt_shop_item_sub SET
                    its_sap_code='%s',
                    its_order_no='%s',
                    its_item='%s',
                    its_price=%s,
                    its_final_price=%s,
                    its_discount_type=%d,
                    its_discount=%d,
                    its_update_time=NOW()
                    WHERE it_id = '$it_id'",
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
                // $sql_set[] = sprintf("INSERT INTO lt_shop_item_option SET io_id='%s',io_type=0,it_id='%s',io_price=%s,io_stock_qty=0,io_noti_qty=0,io_use=1,its_no='%s',io_sapcode_color_gz='%s',io_order_no='%s',io_color_name='%s',io_hoching='%s',io_sap_price=%s", $io_id, $it_id, $io_price, $its_no, $sap_code, $option['code'], $option['color'], $option['size'], $option['tag_price']);
                $sql_set[] = sprintf("UPDATE lt_shop_item_option SET io_id='%s',io_price=%s,io_sapcode_color_gz='%s',io_order_no='%s',io_color_name='%s',io_hoching='%s',io_sap_price=%s WHERE it_id = '$it_id'", $io_id, $io_price, $sap_code, $option['code'], $option['color'], $option['size'], $option['tag_price']);
            }


            // 이미지 복사
            for ($ii = 1; $ii < 6; $ii++) {
                $tmp_img_key = "it_img" . $ii;
                $tmp_img_url = str_replace("'", "", $item['item'][$tmp_img_key]);

                if (!empty($tmp_img_url) && $tmp_img_url !=1) {
                    $pathinfo = pathinfo($tmp_img_url);
                    $filepath = str_replace('https://lifelikecdn.co.kr', G5_PATH, $tmp_img_url);
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
            $commonImg = '';
            if($item['item']["it_img1"] != 1) {
                $commonImg = $commonImg . "it_img1 = '{$item['item']["it_img1"]}',";
            } 
            if($item['item']["it_img2"] != 1) {
                $commonImg = $commonImg . "it_img2 = '{$item['item']["it_img2"]}',";
            } 
            if($item['item']["it_img3"] != 1) {
                $commonImg = $commonImg . "it_img3 = '{$item['item']["it_img3"]}',";
            } 
            if($item['item']["it_img4"] != 1) {
                $commonImg = $commonImg . "it_img4 = '{$item['item']["it_img4"]}',";
            } 
            if($item['item']["it_img5"] != 1) {
                $commonImg = $commonImg . "it_img5 = '{$item['item']["it_img5"]}',";
            } 
            // item 등록
            $sql_common = "
                it_id               = '{$it_id}',
                ca_id               = '" . substr($category, 0, 8) . "',

                it_name             = '" . $item['item']['it_name'] . "',
                it_maker            = '" . $item['item']['it_maker'] . "',
                it_origin           = '" . $item['item']['it_origin'] . "',
                it_brand            = '" . $brands[$item['item']['it_brand']] . "',

                it_explan           = '" . addslashes(trim($item['item']['it_explan'])) . "',
                it_explan2          = '" . addslashes(strip_tags(trim($item['item']['it_explan2']))) . "',
                it_mobile_explan    = '" . addslashes(trim($item['item']['it_explan'])) . "',

                it_price            = $min_price,

                it_discount_price   = $discount,

                it_ip               = '{$_SERVER['REMOTE_ADDR']}',

                it_info_value       = '{$item["info"]}',
                $commonImg

                it_update_time      = NOW(),

                it_search_word = '{$item['item']["it_search_word"]}',

                it_return_zip ='{$default['de_return_zip']}',
                it_return_address1 ='{$default['de_return_address1']}',
                it_return_address2 ='{$default['de_return_address2']}',
                it_info_finditem = '$it_info_finditem'
                WHERE it_id = '$it_id'
                ";


            $sql_set[] = "UPDATE lt_shop_item SET " . $sql_common;
            foreach ($sql_set as $si => $sql) {
                // echo $sql . "<br>";
                sql_query($sql);
            }
            ob_flush();
        }
    }

    ob_end_flush();
    ob_clean();

    goto_url("/adm/update.sabang.php");
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
