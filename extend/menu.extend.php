<?php
// MENU

$check_set = array(
    'ca_id' => $ca_id,
    'br_id' => $br_id,
    'cp_id' => $cp_id
);

if ($it_id) {
    $sql_get_ca_id = "SELECT ca_id FROM lt_shop_item WHERE it_id='{$it_id}'";
    $get_ca = sql_fetch($sql_get_ca_id);
    if (!empty($get_ca['ca_id'])) $check_set['ca_id'] = $get_ca['ca_id'];
}

foreach ($check_set as $ctype => $cid) {
    if (!empty($check_set[$ctype])) {
        $sql_active_menu = "SELECT me_id FROM lt_menu WHERE me_link LIKE '%{$ctype}={$cid}'";
        $active_menu = sql_fetch($sql_active_menu);
        break;
    }
}

if (empty($active_menu)) {
    $sql_active_menu = "SELECT me_id FROM lt_menu WHERE me_link LIKE '%" . ($_SERVER['SCRIPT_NAME']) . "'";
    $active_menu = sql_fetch($sql_active_menu);
}

$m_path = get_session('m_path');
$sql_menu = "SELECT * FROM {$g5['menu_table']}
            WHERE LENGTH(me_code) = 2 AND (me_use + me_mobile_use) > 0
            ORDER BY me_order, me_id";
$res_menu = sql_query($sql_menu);

$g5_menu = array();
$g5_menu_path = "";
$g5_active_menu = $active_menu;

while ($mr = sql_fetch_array($res_menu)) {
    $tm = $mr;
    $active = $tm['me_id'] == $active_menu['me_id'];
    $tm['ACTIVE'] = $active;
    if ($active) {
        $m_path = $tm['me_code'];
        $g5_active_menu = $tm;
    }
    $tm['SUB'] = array();
    $sql_menu_sub = "SELECT * FROM {$g5['menu_table']}
                    WHERE LENGTH(me_code) = 4 AND (me_use + me_mobile_use) > 0 AND SUBSTRING(me_code, 1, 2) = '{$mr['me_code']}'
                    ORDER BY me_order,me_code, me_id";

    $res_menu_sub = sql_query($sql_menu_sub);
    while ($smr = sql_fetch_array($res_menu_sub)) {
        $active = $smr['me_id'] == $active_menu['me_id'];
        $smr['ACTIVE'] = $active;
        if ($active) {
            $m_path = sprintf("%s,%s", $tm['me_code'], $smr['me_code']);
            $g5_active_menu = $smr;
        }
        $tm['SUB'][$smr['me_code']] = $smr;
        
        $tm['SUB'][$smr['me_code']]['SUB2'] = array();
        $sql_menu_sub2 = "SELECT * FROM {$g5['menu_table']}
        WHERE LENGTH(me_code) = 6 AND (me_use + me_mobile_use) > 0 AND SUBSTRING(me_code, 1, 4) = '{$smr['me_code']}'
        ORDER BY me_order,me_code, me_id";
        $res_menu_sub2 = sql_query($sql_menu_sub2);
        while ($smr2 = sql_fetch_array($res_menu_sub2)) { 
            
            $active2 = $smr2['me_id'] == $active_menu['me_id'];
            $smr2['ACTIVE'] = $active2;
            if ($active2) {
                $m_path = sprintf("%s,%s,%s", $tm['me_code'],$smr['me_code'], $smr2['me_code']);
                $g5_active_menu = $smr2;
            }
            $tm['SUB'][$smr['me_code']]['SUB2'][$smr2['me_code']] = $smr2;
        }

    }
    $g5_menu[$tm['me_code']] = $tm;
}

set_session('m_path', $m_path);

$m_code = explode(',', $m_path);
if (!empty($m_code)) {
    $g5_menu_path = "<a href='/'>LIFELIKE</a>";
    $g5_menu_path .= " > <a href='{$g5_menu[$m_code[0]]['me_link']}'>{$g5_menu[$m_code[0]]['me_name']}</a>";
    if (isset($m_code[1])) {
        $g5_menu_path .= " > <a href='{$g5_menu[$m_code[0]]['SUB'][$m_code[1]]['me_link']}'>{$g5_menu[$m_code[0]]['SUB'][$m_code[1]]['me_name']}</a>";
    }
}



// 배너 리스트
$g5_banner = array();
$sql_banner = "SELECT * FROM lt_banner WHERE ba_use=1 AND ba_start_date <= NOW() AND ba_end_date >=NOW() ORDER BY RAND()";
$db_banner = sql_query($sql_banner);
for ($bi = 0; $br = sql_fetch_array($db_banner); $bi++) {
    if (!isset($g5_banner[$br['ba_type']])) $g5_banner[$br['ba_type']] = array();
    $g5_banner[$br['ba_type']][] = $br;
}

// 신규 배너 리스트
$g5_banner_new = array();
//$sql_banner_new = "SELECT * FROM lt_banner_new WHERE cp_start_date <= NOW() AND cp_end_date >=NOW() ORDER BY RAND()";
$sql_banner_new = "SELECT * FROM lt_banner_new where cp_use = 0 ORDER BY sort DESC, ba_sequence ASC";
$db_banner_new = sql_query($sql_banner_new);
for ($bi = 0; $br = sql_fetch_array($db_banner_new); $bi++) {
    if (!isset($g5_banner_new[$br['cp_category']])) $g5_banner_new[$br['cp_category']] = array();
    $g5_banner_new[$br['cp_category']][] = $br;
}

//메인 베스트 리스트

$g5_best_list = array(array(),array(),array(),array(),array());
$bs_ca = '0';

for ($bl = 0; $bl < 7; $bl++){
    $bs_ca = $bl+"0";
    if ($bl ==5) $bs_ca ='41';
    if ($bl ==6) $bs_ca ='42';
    $sql_common = "FROM lt_best_item  a ,
        lt_shop_item b
        WHERE (a.it_id = b.it_id
        AND a.bs_category = '".$bs_ca."'
        AND b.it_use = 1
        AND b.it_soldout = 0
        )";

        $rownum_sql = "set @rownum:=0";
        $re_rownum_sql = sql_fetch($rownum_sql);

        $sql_count_total = "SELECT COUNT(*) AS CNT " . $sql_common;
        $count_total = sql_fetch($sql_count_total);
        $total_count = $count_total['CNT'];

        $qu_limit = 20 - $total_count;

        if($qu_limit < 0){
            $qu_limit = 0 ;
        }

        
        switch ($bs_ca){
            case '0' :
                $target_category = "10";
                break;
            case '1' :
                $target_category = "1010";
                break;
            case '2' :
                $target_category = "1020";
                break;
            case '3' :
                $target_category = "1030";
                break;
            case '4' :
                $target_category = "1040";
                break;
            case '5' :
                $target_category = "1041";
                break;
            case '6' :
                $target_category = "1042";
                break;
            default :
                $target_category = "10";
        }

        // $sql_best = "SELECT bb.io_hoching , aa.* 
        // FROM ((SELECT a.sort AS 1sort, b.* 
        // FROM lt_best_item  a ,
        // lt_shop_item b
        // WHERE (a.it_id = b.it_id
        // AND a.bs_category = '".$bs_ca."0'
        // AND b.it_use = 1
        // AND b.it_soldout = 0
        // ))
        // UNION
        // (SELECT @ROWNUM:=@ROWNUM+1 AS 1sort, c.* FROM lt_shop_item c 
        // WHERE (@ROWNUM:=".$total_count.")=".$total_count." AND it_use = 1 AND it_soldout = 0 AND it_id NOT IN (SELECT it_id FROM lt_best_item WHERE bs_category = '".$bs_ca."0') AND ca_id LIKE '".$target_category."%' ORDER BY it_order DESC LIMIT " .$qu_limit.")
        // ORDER BY 1sort ASC) aa
        // LEFT JOIN lt_shop_item_option bb ON (aa.it_id=bb.it_id)
        // WHERE aa.it_use=1  AND bb.io_use= 1
        // ";

        $sql_best = "SELECT bb.io_hoching , aa.* 
        FROM ((SELECT a.sort AS 1sort, b.* 
        FROM lt_best_item  a ,
        lt_shop_item b
        WHERE (a.it_id = b.it_id
        AND a.bs_category = '".$bs_ca."0'
        AND b.it_use = 1
        AND b.it_soldout = 0
        ))
        UNION
        (SELECT @ROWNUM:=@ROWNUM+1 AS 1sort, c.* FROM lt_shop_item c 
        WHERE (@ROWNUM:=".$total_count.")=".$total_count." AND it_use = 1 AND it_soldout = 0 AND NOT EXISTS (SELECT lt_best_item.it_id FROM lt_best_item WHERE bs_category = '".$bs_ca."0' AND c.it_id = lt_best_item.it_id ) AND ca_id LIKE '".$target_category."%' ORDER BY it_order DESC LIMIT " .$qu_limit.")
        ORDER BY 1sort ASC) aa
        LEFT JOIN lt_shop_item_option bb ON (aa.it_id=bb.it_id)
        WHERE aa.it_use=1  AND bb.io_use= 1
        ";

        $db_best_item = sql_query($sql_best);

        while (($best_items_list = sql_fetch_array($db_best_item)) != false) {
            $g5_best_list[$bl][] = $best_items_list;
        }


}

// 기획전 리스트
$sql_campaign = "SELECT cp_id,cp_category,cp_banner,cp_subject,cp_desc,cp_start_date,cp_end_date,cp_image_1,cp_image_2,cp_image_3,cp_image_4,cp_image_5,cp_image_6 FROM lt_campaign WHERE cp_use=1 AND cp_start_date <= NOW() AND cp_end_date >= NOW() ORDER BY RAND()";
$db_campaign = sql_query($sql_campaign);
$g5_campaign = array(
    'MAIN' => array(),
    'LIST' => array(),
    'GNB' => array(),
    'LNB' => array(),
    'HISTORY' => array()
);
while (false != ($cp = sql_fetch_array($db_campaign))) {
    foreach (array_keys($g5_campaign) as $bk) {
        if (strpos($cp['cp_banner'], $bk) !== false) $g5_campaign[$bk][] = $cp;
    }
}

// PICK 리스트
$g5_picked = array(
    'ITEM' => array(),
    'BRAND' => array(),
    'EVENT' => array()
);
if ($is_member) {
    $sql_picked = "SELECT * FROM lt_shop_wish WHERE mb_id='{$member['mb_id']}'";
    $db_picked = sql_query($sql_picked);
    while (false != ($tp = sql_fetch_array($db_picked))) {
        $pk = strtoupper($tp['wi_type']);
        $g5_picked[$pk][] = $tp['it_id'];
    }
}
