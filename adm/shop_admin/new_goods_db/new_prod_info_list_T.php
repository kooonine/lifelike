<?php
//$sub_menu = '930110';
$sub_menu = '93';
include_once('./_common.php');


auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '상품집 디지털화';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

$yearsServer = date('Y',  G5_SERVER_TIME);

$year_2 = substr($yearsServer , 2,4);
if($od_type == "") $od_type = "L";

// $left_sql = "SELECT lps.ps_code_year , lps.ps_code_season ,
// COUNT(CASE WHEN  lps.ps_job_gubun = '정상' THEN 1 END ) AS normal ,
// COUNT(CASE WHEN  lps.ps_job_gubun = '기획' THEN 1 END ) AS project
// FROM (SELECT * FROM lt_prod_schedule WHERE ps_display = 'Y' AND ps_code_year >= 20 and ps_code_item_type = 'C' GROUP BY ps_item_nm) AS lps
// WHERE lps.ps_code_year IS NOT NULL AND lps.ps_code_season <> '' AND lps.ps_display = 'Y'
// GROUP BY lps.ps_code_year , lps.ps_code_season 
// ORDER BY lps.ps_code_year DESC";


$subsql_on = "";
for($x= 21 ; $x <= $year_2 ; $x++){
    if($x == ($year_2)){
        $subsql_on .= "SELECT '온라인' AS on_off , '$x' AS ps_code_year, 'A' AS ps_code_season FROM DUAL  UNION ALL 
        SELECT '온라인' AS on_off , '$x' AS ps_code_year, 'S' AS ps_code_season FROM DUAL  UNION ALL 
        SELECT '온라인' AS on_off , '$x' AS ps_code_year, 'H' AS ps_code_season FROM DUAL  UNION ALL 
        SELECT '온라인' AS on_off , '$x' AS ps_code_year, 'F' AS ps_code_season FROM DUAL  
        ";
    }else{
        $subsql_on .= "SELECT '온라인' AS on_off , '$x' AS ps_code_year, 'A' AS ps_code_season FROM DUAL  UNION ALL 
        SELECT '온라인' AS on_off , '$x' AS ps_code_year, 'S' AS ps_code_season FROM DUAL  UNION ALL 
        SELECT '온라인' AS on_off , '$x' AS ps_code_year, 'H' AS ps_code_season FROM DUAL  UNION ALL
        SELECT '온라인' AS on_off , '$x' AS ps_code_year, 'F' AS ps_code_season FROM DUAL  UNION ALL 
        ";
    } 
}

// $left_on_sql = "SELECT  lps.on_off,lps.ps_code_year , lps.ps_code_season ,
// COUNT(CASE WHEN  lps.ps_job_gubun = '정상' THEN 1 END ) AS normal ,
// COUNT(CASE WHEN  lps.ps_job_gubun = '기획' THEN 1 END ) AS project
// FROM 
// (SELECT (CASE WHEN  ps_code_gubun IN ('MA','MW') THEN '오프라인'  WHEN ps_code_gubun IN ('MO','MD','MS') THEN '온라인'  END ) AS on_off,
//  aa.* FROM lt_prod_schedule AS aa WHERE ps_display = 'Y' AND ps_code_year >= 21 AND ps_code_item_type = 'C'  AND ps_shooting_yn ='Y' AND ps_ipgo_status = 'Y' GROUP BY ps_item_nm ) AS lps
// WHERE lps.ps_code_year IS NOT NULL AND lps.ps_code_season <> '' AND lps.ps_display = 'Y' AND lps.on_off = '온라인' AND lps.ps_code_brand = 'S'
// GROUP BY lps.ps_code_year , lps.ps_code_season , lps.on_off 
// ORDER BY lps.on_off DESC , lps.ps_code_year DESC , lps.ps_code_season ASC";

$left_on_sql = "SELECT tc.* , infos.normal , infos.project FROM (
    SELECT  lps.on_off,lps.ps_code_year , lps.ps_code_season ,
    COUNT(CASE WHEN  lps.ps_job_gubun = '정상' THEN 1 END ) AS normal ,
    COUNT(CASE WHEN  lps.ps_job_gubun = '기획' THEN 1 END ) AS project
    FROM 
    (SELECT (CASE WHEN  ps_code_gubun IN ('MA','MW') THEN '오프라인'  WHEN ps_code_gubun IN ('MO','MD','MS','MX') THEN '온라인'  END ) AS on_off,
     aa.* FROM lt_prod_schedule AS aa WHERE ps_display = 'Y' AND ps_code_year >= 21   AND ps_shooting_yn ='Y'  GROUP BY ps_item_nm ) AS lps
    
    WHERE lps.ps_code_year IS NOT NULL AND lps.ps_code_season <> '' AND lps.ps_display = 'Y' AND lps.on_off = '온라인' AND lps.ps_code_brand = 'H'
    GROUP BY lps.ps_code_year , lps.ps_code_season , lps.on_off 
    ORDER BY lps.on_off DESC , lps.ps_code_year DESC , lps.ps_code_season ASC
    ) AS infos
    RIGHT JOIN (
        $subsql_on
    ) AS tc
    ON tc.ps_code_season = infos.ps_code_season AND tc.on_off = infos.on_off AND tc.ps_code_year = infos.ps_code_year";


$subsql_off = "";
for($y= 21 ; $y <= $year_2 ; $y++){
    if($y == ($year_2)){
        $subsql_off .= "SELECT '오프라인' AS on_off , '$y' AS ps_code_year, 'A' AS ps_code_season FROM DUAL  UNION ALL 
        SELECT '오프라인' AS on_off , '$y' AS ps_code_year, 'S' AS ps_code_season FROM DUAL  UNION ALL 
        SELECT '오프라인' AS on_off , '$y' AS ps_code_year, 'H' AS ps_code_season FROM DUAL  UNION ALL
        SELECT '오프라인' AS on_off , '$y' AS ps_code_year, 'F' AS ps_code_season FROM DUAL   
        ";
    }else{
        $subsql_off .= "SELECT '오프라인' AS on_off , '$y' AS ps_code_year, 'A' AS ps_code_season FROM DUAL  UNION ALL 
        SELECT '오프라인' AS on_off , '$y' AS ps_code_year, 'S' AS ps_code_season FROM DUAL  UNION ALL 
        SELECT '오프라인' AS on_off , '$y' AS ps_code_year, 'H' AS ps_code_season FROM DUAL  UNION ALL
        SELECT '오프라인' AS on_off , '$y' AS ps_code_year, 'F' AS ps_code_season FROM DUAL  UNION ALL 
        ";
    } 
}



$left_off_sql = "SELECT tc.* , infos.normal , infos.project FROM (
    SELECT  lps.on_off,lps.ps_code_year , lps.ps_code_season ,
    COUNT(CASE WHEN  lps.ps_job_gubun = '정상' THEN 1 END ) AS normal ,
    COUNT(CASE WHEN  lps.ps_job_gubun = '기획' THEN 1 END ) AS project
    FROM 
    (SELECT (CASE WHEN  ps_code_gubun IN ('MA','MW') THEN '오프라인'  WHEN ps_code_gubun IN ('MO','MD','MS','MX') THEN '온라인'  END ) AS on_off,
     aa.* FROM lt_prod_schedule AS aa WHERE ps_display = 'Y' AND ps_code_year >= 21 AND ps_shooting_yn ='Y'  GROUP BY ps_item_nm ) AS lps
    
    WHERE lps.ps_code_year IS NOT NULL AND lps.ps_code_season <> '' AND lps.ps_display = 'Y' AND lps.on_off = '오프라인' AND lps.ps_code_brand = 'H'
    GROUP BY lps.ps_code_year , lps.ps_code_season , lps.on_off 
    ORDER BY lps.on_off DESC , lps.ps_code_year DESC , lps.ps_code_season ASC
    ) AS infos
    RIGHT JOIN (
        $subsql_off
    ) AS tc
    ON tc.ps_code_season = infos.ps_code_season AND tc.on_off = infos.on_off AND tc.ps_code_year = infos.ps_code_year";

$left_on_res = sql_query($left_on_sql);
$left_off_res = sql_query($left_off_sql);


$now_year =  date('Y',  G5_SERVER_TIME);


$year = $_GET['year'];
$season = $_GET['season'];
$item_nm = $_POST['item_nm'];
$on_off = $_GET['onoff'];
$it_name = $_POST['it_name'];

$db_code_year = $_POST['db_code_year'];
$db_code_season = $_POST['db_code_season'];


if(!empty($db_code_year)){
    $year = substr($db_code_year, 2,4);
}
if(!empty($db_code_season)){
    $season = $db_code_season;
}
if(!empty($item_nm) || (!empty($year) && !empty($season))){
    $sql_where = "WHERE (1)  AND ps_code_brand ='H' AND ps_shooting_yn ='Y'  ";

    if(!empty($item_nm)){
        preg_match_all("/[^()||\-\/\,]+/", $item_nm,$it_list);
        $it_list_in_list = empty($it_list[0])?'NULL':"'".join("','", $it_list[0])."'";
        $sql_where .= " AND ps_item_nm IN ({$it_list_in_list})  ";   
    }
    if(!empty($year) && !empty($season)){
        $sql_where .= " AND ps_code_year = '{$year}' AND ps_code_season = '{$season}' ";
    }

    if(!empty($on_off)){
        if($on_off == 'ON'){
            $sql_where .= " AND ps_code_gubun IN ('MO','MD','MS','MX')";
        }else{
            $sql_where .= " AND ps_code_gubun IN ('MA','MW')";
        }
    }
    
    $item_sql = "SELECT GROUP_CONCAT(ps_id)  AS ps_ids, ps.* FROM lt_prod_schedule AS ps $sql_where GROUP BY ps_item_nm ORDER BY ps_item_nm ASC" ;
  
    $item_res = sql_query($item_sql);
    $item_list = sql_query($item_sql);
    
    
}



function season_nm($text){
    if(preg_match("/[a-zA-Z]/",$text)){
        switch($text){
            case 'A' : $season_nm = "AA"; break;
            case 'S' : $season_nm = "SS"; break;
            case 'H' : $season_nm = "HS"; break;
            case 'F' : $season_nm = "FW"; break;
        }
    }else{
        $season_nm = $text;
    }
    return $season_nm;
}

function color_table($text){
    if(preg_match("/[a-zA-Z]/",$text)){
        switch($text){
            case 'AA' : $color_nm = "AA(기타)"; break;
            case 'BE' : $color_nm = "BE(베이지)"; break;
            case 'BK' : $color_nm = "BK(블랙)"; break;
            case 'BL' : $color_nm = "BL(블루)"; break;
            case 'BR' : $color_nm = "BR(브라운)"; break;
            case 'CR' : $color_nm = "CR(크림)"; break;
            case 'DB' : $color_nm = "DB(진블루)"; break;
            case 'DP' : $color_nm = "DP(진핑크)"; break;
            case 'FC' : $color_nm = "FC(푸시아)"; break;
            case 'GD' : $color_nm = "GD(골드)"; break;
            case 'GN' : $color_nm = "GN(그린)"; break;
            case 'GR' : $color_nm = "GR(그레이)"; break;
            case 'IV' : $color_nm = "IV(아이보리)"; break;
            case 'KA' : $color_nm = "KA(카키)"; break;
            case 'LB' : $color_nm = "LB(연블루)"; break;
            case 'LG' : $color_nm = "LG(연그레이)"; break;
            case 'LP' : $color_nm = "LP(연핑크)"; break;
            case 'LV' : $color_nm = "LV(라벤다)"; break;
            case 'MT' : $color_nm = "MT(민트)"; break;
            case 'MU' : $color_nm = "MU(멀티)"; break;
            case 'MV' : $color_nm = "MV(모브)"; break;
            case 'MX' : $color_nm = "MX(혼합)"; break;
            case 'NC' : $color_nm = "NC(내츄럴)"; break;
            case 'NV' : $color_nm = "NV(네이비)"; break;
            case 'OR' : $color_nm = "OR(오렌지)"; break;
            case 'PC' : $color_nm = "PC(청록)"; break;
            case 'PK' : $color_nm = "PK(핑크)"; break;
            case 'PU' : $color_nm = "PU(퍼플)"; break;
            case 'RD' : $color_nm = "RD(레드)"; break;
            case 'WH' : $color_nm = "WH(화이트)"; break;
            case 'YE' : $color_nm = "YE(노랑)"; break;
            case 'DG' : $color_nm = "DG(딥그레이)"; break;
            case 'CO' : $color_nm = "CO(코랄)"; break;
        }
    }else{
        $color_nm = $text;
    }
    return $color_nm;
}

?>
<link href="../../css/good_db.css" rel="stylesheet">
<div id="left_cate" >

    <form id="new_prod_info"  method="post" action="./new_prod_info_list.php?od_type=T" >
        <div style="float:left; height:25px;">
        시즌
            <select name="db_code_year" id="db_code_year" style=" height:25px;">
                <option value="" <?= get_selected($db_code_year, ''); ?>>선택</option>
                <? for($i = (int)$yearsServer+1; 2020 < $i; $i--) {  ?>
                    <option value=<?= $i?> <?= get_selected($db_code_year, $i); ?>><?= $i?>년</option>
                <? }?>
            </select>
        </div>
        <div>
            <select  name="db_code_season" id="db_code_season"  style=" height:25px;">
                <option value="" <?= $db_code_season == '' ? "selected" : "" ?>>선택</option>
                <option value="S" <?= $db_code_season == 'S' ? "selected" : "" ?>>SS</option>
                <option value="H" <?= $db_code_season == 'H' ? "selected" : "" ?>>HS</option>
                <option value="F" <?= $db_code_season == 'F' ? "selected" : "" ?>>FW</option>
                <option value="A" <?= $db_code_season == 'A' ? "selected" : "" ?>>AA</option>
            </select>
        </div>
        품명
        <input type = "text" onkeydown="enterSearch();" name="item_nm" value = "<?=$item_nm?>">
    </form>
    
    <div class="online" id="on-toggle"  onclick="openCloseON()">온라인</div>
    <div id="on-content" style="<?if($onoff == 'ON'):?>display:block; <?endif?>">
        <?for ($oli = 0; $left_on_row = sql_fetch_array($left_on_res); $oli++) {
        ?>
            <?if($left_on_row['on_off'] == '온라인'):?>
                <?if($left_on_row['ps_code_season'] == 'A'):?>
                <div>20<?=$left_on_row['ps_code_year']?></div>
                <div><a href="./new_prod_info_list.php?od_type=T&year=<?=$left_on_row['ps_code_year']?>&season=A&onoff=ON">AA 정상 <?=$left_on_row['normal'] ? $left_on_row['normal'] : '0'?>건 기획 <?=$left_on_row['project']? $left_on_row['project'] : '0'?>건</a></div>
                <?endif?>
                <?if($left_on_row['ps_code_season'] == 'S'):?><div><a href="./new_prod_info_list.php?od_type=T&year=<?=$left_on_row['ps_code_year']?>&season=S&onoff=ON">SS 정상 <?=$left_on_row['normal'] ? $left_on_row['normal'] : '0'?>건 기획 <?=$left_on_row['project']? $left_on_row['project'] : '0'?>건</a></div><?endif?>
                <?if($left_on_row['ps_code_season'] == 'H'):?><div><a href="./new_prod_info_list.php?od_type=T&year=<?=$left_on_row['ps_code_year']?>&season=H&onoff=ON">HS 정상 <?=$left_on_row['normal'] ? $left_on_row['normal'] : '0'?>건 기획 <?=$left_on_row['project']? $left_on_row['project'] : '0'?>건</a></div><?endif?>
                <?if($left_on_row['ps_code_season'] == 'F'):?><div><a href="./new_prod_info_list.php?od_type=T&year=<?=$left_on_row['ps_code_year']?>&season=F&onoff=ON">FW 정상 <?=$left_on_row['normal'] ? $left_on_row['normal'] : '0'?>건 기획 <?=$left_on_row['project']? $left_on_row['project'] : '0'?>건</a></div><?endif?>
            <?endif?>

        <?}?>
    </div>
    <div class="offline" id="off-toggle" onclick="openCloseOFF()">오프라인</div>
    <div id="off-content" style="<?if($onoff == 'OFF'):?>display:block; <?endif?>">
        <?for ($fli = 0; $left_off_row = sql_fetch_array($left_off_res); $fli++) {
        ?>
            <?if($left_off_row['on_off'] == '오프라인'):?>
                <?if($left_off_row['ps_code_season'] == 'A'):?>
                <div>20<?=$left_off_row['ps_code_year']?></div>
                <div><a href="./new_prod_info_list.php?od_type=T&year=<?=$left_off_row['ps_code_year']?>&season=A&onoff=OFF">AA 정상 <?=$left_off_row['normal']? $left_off_row['normal'] : '0' ?>건 기획 <?=$left_off_row['project'] ? $left_off_row['project'] : '0'?>건</a></div>
                <?endif?>
                <?if($left_off_row['ps_code_season'] == 'S'):?><div><a href="./new_prod_info_list.php?od_type=T&year=<?=$left_off_row['ps_code_year']?>&season=S&onoff=OFF">SS 정상 <?=$left_off_row['normal']? $left_off_row['normal'] : '0' ?>건 기획 <?=$left_off_row['project'] ? $left_off_row['project'] : '0'?>건</a></div><?endif?>
                <?if($left_off_row['ps_code_season'] == 'H'):?><div><a href="./new_prod_info_list.php?od_type=T&year=<?=$left_off_row['ps_code_year']?>&season=H&onoff=OFF">HS 정상 <?=$left_off_row['normal']? $left_off_row['normal'] : '0' ?>건 기획 <?=$left_off_row['project'] ? $left_off_row['project'] : '0'?>건</a></div><?endif?>
                <?if($left_off_row['ps_code_season'] == 'F'):?><div><a href="./new_prod_info_list.php?od_type=T&year=<?=$left_off_row['ps_code_year']?>&season=F&onoff=OFF">FW 정상 <?=$left_off_row['normal']? $left_off_row['normal'] : '0' ?>건 기획 <?=$left_off_row['project'] ? $left_off_row['project'] : '0'?>건</a></div><?endif?>
            <?endif?>
        <?}?>
    </div>
</div>
<div id="rigth_list">
    <div style="margin-left: 15px;">
        <?$prod_info_year_season = '20'.$year.' '.season_nm($season)?>
        <h2><?=$year ? '20'.$year : ''?> <?=$season ? season_nm($season) : ''?>  <button style="float:right;" class="noprint btn btn_02 print_hidden" onclick="all_print();" type="button btn-success">전체출력</button> <button style="float:right;" class="noprint btn btn_02 print_hidden" onclick="select_print();" type="button btn-success">선택출력</button></h2>
        <?for ($itc = 0; $it_list = sql_fetch_array($item_res); $itc++ ){
            if($itc == 0){
                $item_list_search = $it_list['ps_item_nm'];
            }else{
                $item_list_search .= ', '.$it_list['ps_item_nm'];
            }

        }?>
        <div>
            <?=$item_list_search?>
        </div>
    </div>
    <div id="wrap_table">
        <?for ($i = 0; $lists = sql_fetch_array($item_list); $i++ ) : ?>
            <?
                $item_jo = "select * from lt_job_order where ps_id in ({$lists['ps_ids']}) ORDER BY jo_total_origin_price+0 DESC limit 1";
                $item_jo_res = sql_fetch($item_jo);
                $item_pi ="select * from lt_prod_info where jo_id = '{$item_jo_res['jo_id']}' order by jo_id asc limit 1 ";
                $item_pi_res = sql_fetch($item_pi);

                $item_ps_list = "select * from lt_prod_schedule  $sql_where and ps_item_nm = '{$lists['ps_item_nm']}' GROUP BY ps_it_name ";
                $item_ps_list_res = sql_query($item_ps_list);

                for ($si = 0; $color_row = sql_fetch_array($item_ps_list_res); $si++) {
                    $color_sql = "select * from lt_job_order where ps_id = '{$color_row['ps_id']}' limit 1 ";
                    $color_res = sql_fetch($color_sql);

                    if($si  == 0 ){
                        $first_item = $color_row['ps_it_name'];
                        $info_colors =  color_table($color_res['jo_color']);
                    }else{
                        $info_colors .=  ",".color_table($color_res['jo_color']);
                    }
                }

                preg_match_all("/[^ \-\ \/\,]+/", $info_colors,$info_colors_list);
                $color_lists = array_unique($info_colors_list[0]);
                
                $info_colors_list_in_list = empty($color_lists)?'NULL':join(",", $color_lists);

                $main_i = "select * from lt_prod_schedule where ps_id = '{$item_jo_res['ps_id']}'";
                $main_i_res = sql_fetch($main_i);

                $ps_prod_main_imgs_set = array();
                if (!empty($lists['ps_prod_main_imgs'])) {
                    $ps_prod_main_imgs_set = json_decode($main_i_res['ps_prod_main_imgs'], true);
                }

                
                $jo_main_img = array();
                if (!empty($item_jo_res['jo_main_img'])) {
                    $jo_main_img = json_decode($item_jo_res['jo_main_img'], true);
                }
                $jo_codi_img = array();
                if (!empty($item_jo_res['jo_codi_img'])) {
                    $jo_codi_img = json_decode($item_jo_res['jo_codi_img'], true);
                }
                $jo_sub_img = array();
                if (!empty($item_jo_res['jo_sub_img'])) {
                    $jo_sub_img = json_decode($item_jo_res['jo_sub_img'], true);
                }
                
            ?>
        <div class="item_info_t noprint" id="pring_item_<?=$i?>">
            <!-- <div class="pring_a4"> -->
            <div class="display_hidden">
                <?if(!empty($year)):?><?=$prod_info_year_season?><?endif?>
                <br>
                <?=$item_list_search?>
            </div>
            <table class="print_table">
                <colgroup>
                <col width = "20%">
                <col width = "20%">
                <col width = "20%">
                <col width = "20%">
                <col width = "20%">
                </colgroup>
                <tr>
                    <th colspan="5" class="txt_center line_h30 chk_box">
                        <input class="noprint chk_" type="checkbox" name="chk[]" value = "<?=$i?>" > 
                        <span> <?=($i+1)?></span> 
                        <button class="noprint one_print btn btn_02 print_hidden" onclick="printPage_item(<?=$i?>);" type="button btn-success">출력</button>
                    </th>
                </tr>
                <!-- <tr><td colspan = "5">
                    <div style="height : 100px; -webkit-print-color-adjust:exact !important; width : 100%; background-image : url('http://www.lifelike.co.kr/data/new_goods/<?=$jo_main_img[1]['img']?>')"></div>
                </td></tr> -->
                
                <tr class="borderL">
                    <td height="300" colspan="2" rowspan="3" class="imgs">
                    
                    <!-- <img <?if($ps_prod_main_imgs_set[0]['img']):?> src="<?=G5_URL?>/data/new_goods/<?=$ps_prod_main_imgs_set[0]['img']?>" <?endif?>>                 -->
                    <img class="main_imgs" <?if($ps_prod_main_imgs_set[0]['img']):?> src="<?=G5_URL?>/data/new_goods/<?=$ps_prod_main_imgs_set[0]['img']?>" <?endif?>>
                        
                    </td>
                    <td height="100" colspan="3" class="imgs">
                        <div class="main_codi_txt"><?=stripslashes($jo_main_img[1]['title'])?></div>
                        <img class="main_codi" <?if($jo_main_img[1]['img']):?>  src="<?=G5_URL?>/data/new_goods/<?=$jo_main_img[1]['img']?>"  <?endif?> > 
                    </td>
                </tr>

                <tr class="borderR">
                    <td height="100" colspan="3" class="imgs">
                        <div class="main_codi_txt"><?=stripslashes($jo_codi_img[1]['title'])?></div>
                        <img class="main_codi" <?if($jo_codi_img[1]['img']):?>  src="<?=G5_URL?>/data/new_goods/<?=$jo_codi_img[1]['img']?>"  <?endif?> >
                    </td>
                </tr>

                <tr class="borderR">
                    <td height="100" colspan="3" class="imgs">
                        <div class="main_codi_txt"><?=stripslashes($jo_sub_img[1]['title'])?></div>
                        <img class="main_codi" <?if($jo_sub_img[1]['img']):?>  src="<?=G5_URL?>/data/new_goods/<?=$jo_sub_img[1]['img']?>"  <?endif?> >
                    </td>
                </tr>


                <tr><th class="txt_center">구분</th><td class="txt_center" colspan="4"><?=$lists['ps_job_gubun'] ?></td></tr>
                <tr><th class="txt_center">컨셉</th><td class="txt_center" colspan="4"><?=$item_pi_res['pi_design_style']?></td></tr>
                <tr><th class="txt_center">상품명</th><td class="txt_center" colspan="4"><?=$lists['ps_item_nm'] ?></td></tr>
                <tr><th class="txt_center">컬러</th><td class="txt_center" colspan="4"><?=$info_colors_list_in_list ?></td></tr>
                <tr><th class="txt_center">소재</th><td class="txt_center" colspan="4"><?=$item_pi_res['pi_item_soje'] ?> <br/><?=$item_pi_res['pi_item_soje_detail'] ?></td></tr>
                <tr><th class="txt_center">항균</th><td class="txt_center" colspan="4"><?=$item_pi_res['pi_hangkun_info'] ?></td></tr>
                <!-- <tr><th class="txt_center">출시예정일</th><td class="txt_center" colspan="4"><?=$lists['ps_job_gubun'] ?></td></tr> -->
                <tr><th class="txt_center ">상품설명</th><td class="rm_p txt_center" colspan="4"><?=strip_tags($item_pi_res['pi_detail_info'] )  ?></td></tr>
                <tr><th class="txt_center ">셀링포인트1</th><td class="rm_p txt_center" colspan="4"><?=strip_tags($item_pi_res['pi_selling1'] )?></td></tr>
                <tr><th class="txt_center ">셀링포인트2</th><td class="rm_p txt_center" colspan="4"><?=strip_tags($item_pi_res['pi_selling2'] )?></td></tr>
                <tr><th class="txt_center ">셀링포인트3</th><td class="rm_p txt_center" colspan="4"><?=strip_tags($item_pi_res['pi_selling3'] )?></td></tr>
                <tr>
                    <th class="txt_center">아이템</th>
                    <th class="txt_center">사이즈</th>
                    <th class="txt_center">TAG가</th>
                    <th class="txt_center">판매가</th>
                    <th class="txt_center">할인율</th>
                    
                </tr>

                <?
                    $item_size = "SELECT * FROM lt_prod_schedule $sql_where and ps_it_name = '$first_item'  ";
                    $item_size_res = sql_query($item_size);


                for ($is = 0; $ch_row = sql_fetch_array($item_size_res); $is++) {
                    $jo_sql = "select * from lt_prod_info where ps_id = '{$ch_row['ps_id']}' order by pi_size desc, jo_id ASC  ";
					$jo_cnt_sql = "select count(*) as cnt from lt_prod_info where ps_id = '{$ch_row['ps_id']}'  ";
					$jo_result = sql_query($jo_sql);
                    $jo_cnt_result = sql_fetch($jo_cnt_sql);    
                    
                    for ($ii = 0; $jo_row = sql_fetch_array($jo_result) ; $ii++) {
                        if(strpos($ch_row['ps_prod_name'] , "베개커버") === false ){
                            $s_size = $jo_row['pi_size'];
                        }else{
                            $s_size =  $jo_row['pi_cisu'];
                        }

                        $sale_rate =  (1 -  ($jo_row['pi_sale_price'] / $jo_row['pi_tag_price']) ) * 100;
                    
                ?>

                <tr>
                <?if ($ii == 0) : ?>
                <td class="vtc txt_center" rowspan = <?=$jo_cnt_result['cnt']?>><?=$ch_row['ps_prod_name']?></td>
                <?endif?>
                <td class="txt_center"><?=$s_size?></td>
                <td class="txt_right bor2"><?=number_format($jo_row['pi_tag_price'])?></td>
                <td class="txt_right bor2"><?=number_format($jo_row['pi_sale_price'])?></td>
                <td class="txt_right bor2"><?=round($sale_rate/10)*10?>%</td>
                </tr>

                <?}
                }?>

            </table>
            <!-- </div> -->
        </div>
        <div class="endline endline_<?=$i?> noprint"></div><br style="height:0; line-height:0">
        <?endfor?>
        
    </div>
</div>


<script src="../total_order/jquery.table2excel.js"></script>

<script>

    function enterSearch() {
        if (window.event.keyCode == 13) {
        	document.getElementById('new_prod_info').submit();
    	}
    }

    function printPage_item(elem){
        // var el = document.querySelectorAll(".mater_select");
        // el.forEach(function(select) {
        //     if(select.value){

        //     }else{
        //         select.style.display = "none";
        //     }
        // });
        id = elem;
        item_a = "pring_item_"+id;
        var initBody;
        window.onbeforeprint = function(){
            initBody = document.body.innerHTML;
            document.body.innerHTML =  document.getElementById(item_a).innerHTML;
        };
        window.onafterprint = function(){
            document.body.innerHTML = initBody;
            // location.reload();
        };
        window.print();
        location.reload();
        return false;
    }
    function all_print(){
        // var el = document.querySelectorAll(".mater_select");
        // el.forEach(function(select) {
        //     if(select.value){

        //     }else{
        //         select.style.display = "none";
        //     }
        // });
        $(".item_info_t").removeClass("noprint");
        $(".endline").removeClass("noprint");
        var last = $("input[name='chk[]']").last().val();
        $(".endline_"+last).addClass("noprint");

        var initBody;
        window.onbeforeprint = function(){
            initBody = document.body.innerHTML;
            document.body.innerHTML =  document.getElementById('wrap_table').innerHTML;
        };
        window.onafterprint = function(){
            document.body.innerHTML = initBody;
            // location.reload();
            $(".item_info_t").addClass("noprint");
            $(".endline").addClass("noprint");
        };
        window.print();
        location.reload();
        return false;
    }

    function select_print(){
        if (!is_checked("chk[]")) {
	        alert("출력하실 상품정보집 선택해주세요.");
	        return false;
	    }


        $("input[name='chk[]']:checked").each(function() {
            var chk = this.value;
            $("#pring_item_"+chk).removeClass("noprint");
            $(".endline_"+chk).removeClass("noprint");
        });
        var last = $("input[name='chk[]']:checked").last().val();
        $(".endline_"+last).addClass("noprint");
        var initBody;
        window.onbeforeprint = function(){
            initBody = document.body.innerHTML;
            document.body.innerHTML =  document.getElementById('wrap_table').innerHTML;
        };
        window.onafterprint = function(){
            document.body.innerHTML = initBody;
            // location.reload();
            $(".item_info_t").addClass("noprint");
            $(".endline").addClass("noprint");
        };
        window.print();
        location.reload();
        return false;

    }

    function openCloseON() {
        if(document.getElementById('on-content').style.display === 'block') {
            document.getElementById('on-content').style.display = 'none';
        } else {
            document.getElementById('on-content').style.display = 'block';
        }
    }
    function openCloseOFF() {
        if(document.getElementById('off-content').style.display === 'block') {
            document.getElementById('off-content').style.display = 'none';
        } else {
            document.getElementById('off-content').style.display = 'block';
        }
    }
    

</script>

<?php 
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
