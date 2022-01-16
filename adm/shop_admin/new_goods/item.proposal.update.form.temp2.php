<?

$sub_menu = "930200";
include_once('./_common.php');
include_once('../../admin.head.php');
include_once(G5_LAYOUT_PATH . "/modal.php");


auth_check($auth[substr($sub_menu,0,2)], 'w');

$ps_id = $_GET['ps_id'];
$proposal_name = $_GET['it_name'];
$ip_id = $_GET['ip_id'];

if (!($w == '' || $w == 'u' || $w == 'r')) {
    alert('w 값이 제대로 넘어오지 않았습니다.');
}

if ($w == '') {
    $title_msg = '작성';
    if ($cp_id) {
        alert('글쓰기에는 \$cp_id 값을 사용하지 않습니다.');
    }
} else if ($w == 'u') {
    $title_msg = '수정';
    $sql = " select * from lt_item_proposal where ip_id = '$ip_id' ";
    $ip = sql_fetch($sql);
    if (!$ip['ip_id']) alert("등록된 자료가 없습니다.");

    // $cp_banner_checked = array();
    // foreach (explode(',', $cp['cp_banner']) as $cb) {
    //     $cp_banner_checked[$cb] = "checked";
    // }
}

$ps_sql = " select * from lt_prod_schedule where ps_id = '$ps_id' ";
$ps = sql_fetch($ps_sql);


$fisrt_jo_sql = " select * from lt_job_order where jo_it_name = '$proposal_name' ORDER BY jo_id LIMIT 1";
$fisrt_jo = sql_fetch($fisrt_jo_sql);

$jo_item_sql = " select jo_prod_name from lt_job_order where jo_it_name = '$proposal_name' GROUP BY jo_prod_name  ORDER BY jo_id ASC ";
$jo_items = sql_query($jo_item_sql);


// 그룹접근 가능
if (!empty($group['gr_use_access'])) {
    if ($is_guest) {
        alert("접근 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.", 'login.php?' . $qstr . '&amp;url=' . urlencode($_SERVER['SCRIPT_NAME'] . '?bo_table=' . $bo_table));
    }

    if ($is_admin == 'super' || $group['gr_admin'] === $member['mb_id'] || $board['bo_admin'] === $member['mb_id']) {; // 통과
    } else {
        // 그룹접근
        $sql = " select gr_id from {$g5['group_member_table']} where gr_id = '{$board['gr_id']}' and mb_id = '{$member['mb_id']}' ";
        $row = sql_fetch($sql);
        if (!$row['gr_id'])
            alert('접근 권한이 없으므로 글쓰기가 불가합니다.\\n\\n궁금하신 사항은 관리자에게 문의 바랍니다.');
    }
}

$ip_proposal_memo = array();
if (!empty($ip['ip_proposal_memo'])) {
    $ip_proposal_memo = json_decode($ip['ip_proposal_memo'], true);
}
$ip_job_orders = array();
if (!empty($ip['ip_job_orders'])) {
    $ip_job_orders = json_decode($ip['ip_job_orders'], true);
}


$jo_pumjil = array();
if (!empty($fisrt_jo['jo_pumjil'])) {
    $jo_pumjil = json_decode($fisrt_jo['jo_pumjil'], true);
}
$jo_mater_name = array();
if (!empty($fisrt_jo['jo_mater_name'])) {
    $jo_mater_name = json_decode($fisrt_jo['jo_mater_name'], true);
}

$jo_main_img = array();
if (!empty($fisrt_jo['jo_main_img'])) {
    $jo_main_img = json_decode($fisrt_jo['jo_main_img'], true);
}
$jo_codi_img = array();
if (!empty($fisrt_jo['jo_codi_img'])) {
    $jo_codi_img = json_decode($fisrt_jo['jo_codi_img'], true);
}
$jo_sub_img = array();
if (!empty($fisrt_jo['jo_sub_img'])) {
    $jo_sub_img = json_decode($fisrt_jo['jo_sub_img'], true);
}


$cp_item_set = array();
if (!empty($cp['cp_item_set'])) {
    $cp_item_set = json_decode($cp['cp_item_set'], true);
}



$ip_mater_purchace = array();
if (!empty($ip['ip_mater_purchace'])) {
    $ip_mater_purchace = json_decode($ip['ip_mater_purchace'], true);
}

$ip_processing = array();
if (!empty($ip['ip_processing'])) {
    $ip_processing = json_decode($ip['ip_processing'], true);
}
$ip_finished = array();
if (!empty($ip['ip_finished'])) {
    $ip_finished = json_decode($ip['ip_finished'], true);
}

$g5['title'] = $title_msg;

$is_html = true;
$is_category = false;
$is_link = true;
$is_file = true;
$is_file_content = true;

$html_checked   = "";
$html_value     = "";
$subject = "";

if (isset($cp['cp_subject'])) {
    $subject = str_replace("\"", "&#034;", get_text(cut_str($cp['cp_subject'], 255), 0));
}

$g5['title'] = "제품기획서 " . $title_msg;


$action_url = https_url('adm') . "/shop_admin/new_goods/item.proposal.update.php";
$brands = array(
    '소프라움' => '소프라움',
    '쉐르단' => '쉐르단',
    '랄프로렌홈' => '랄프로렌홈',
    '베온트레' => '베온트레',
    '링스티드던' => '링스티드던',
    '로자리아' => '로자리아',
    '그라치아노' => '그라치아노',
    '시뇨리아' => '시뇨리아',
    '플랫폼일반' => '플랫폼일반',
    '플랫폼렌탈' => '플랫폼렌탈',
    '온라인' => '온라인',
    '템퍼' => '템퍼'
);
$pumjongs = array(
    '커버' => '커버',
    '기타' => '기타',
    '소품액세서리' => '소품액세서리',
    '속통류' => '속통류'
);
$banners = array('MAIN', 'LIST', 'GNB', 'LNB', 'HISTORY');

$img_size_pc = array(
    'GNB_TOP' => '1920*90px',
    'GNB_IN' => '',
    'MAIN' => '1920*580px',
    'BADING' => '1420*200px',
    'GOOS' => '460*460px',
    'THEME' => 'THEME',
    'MD' => '460*460px',
    'BRAND' => '1420*450px',
    'BEST' => 'BEST',
    'NEW' => 'NEW',
    'HOT' => 'HOT',
    'SEASON' => 'SEASON',
    'EVENT' => 'EVENT',
);

$img_size_mo = array(
    'GNB_TOP' => '900*270px',
    'GNB_IN' => 'GNB 내부',
    'MAIN' => '1080*1200px',
    'BADING' => '1080*1080px',
    'GOOS' => '1080*1080px',
    'THEME' => 'THEME',
    'MD' => '1080*1080px',
    'BRAND' => '1080*840px',
    'BEST' => 'BEST',
    'NEW' => 'NEW',
    'HOT' => 'HOT',
    'SEASON' => 'SEASON',
    'EVENT' => 'EVENT',
);

function item_color_name($col){
    switch ( $col ) {
        case 'AA' : return  'AA(기타)'; break;
        case 'BE' : return 'BE(베이지)'; break;
        case 'BK' : return 'BK(블랙)'; break;
        case 'BL' : return 'BL(블루)'; break;
        case 'BR' : return 'BR(브라운)'; break;
        case 'CR' : return 'CR(크림)'; break;
        case 'DB' : return 'DB(진블루)'; break;
        case 'DP' : return 'DP(진핑크)'; break;
        case 'FC' : return 'FC(푸시아)'; break;
        case 'GD' : return 'GD(골드)'; break;
        case 'GN' : return 'GN(그린)'; break;
        case 'GR' : return 'GR(그레이)'; break;
        case 'IV' : return 'IV(아이보리)'; break;
        case 'KA' : return 'KA(카키)'; break;
        case 'LB' : return 'LB(연블루)'; break;
        case 'LG' : return 'LG(연그레이)'; break;
        case 'LP' : return 'LP(연핑크)'; break;
        case 'LV' : return 'LV(라벤다)'; break;
        case 'MT' : return 'MT(민트)'; break;
        case 'MU' : return 'MU(멀티)'; break;
        case 'MV' : return 'MV(모브)'; break;
        case 'MX' : return 'MX(혼합)'; break;
        case 'NC' : return 'NC(내츄럴)'; break;
        case 'NV' : return 'NV(네이비)'; break;
        case 'OR' : return 'OR(오렌지)'; break;
        case 'PC' : return 'PC(청록)'; break;
        case 'PK' : return 'PK(핑크)'; break;
        case 'PU' : return 'PU(퍼플)'; break;
        case 'RD' : return 'RD(레드)'; break;
        case 'WH' : return 'WH(화이트)'; break;
        case 'YE' : return 'YE(노랑)'; break; 
        case 'DG' : return 'DG(딥그레이)'; break; 
        case 'CO' : return 'CO(코랄)'; break; 
      }

}


?>

<!-- @START@ 내용부분 시작 -->

<style>
    button.btn-add {
        visibility: hidden;
    }

    button.btn-add.first {
        visibility: visible;
    }
    .table-title {font-size: 17px;font-weight: bold;margin-top: 38px;margin-bottom: 5px;}
    .table-title-first {font-size: 17px;font-weight: bold;margin-bottom: 5px;margin-top: 19px;}
</style>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <form name="fwrite" id="fwrite" action="<?= $action_url ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="uid" value="<?= get_uniqid(); ?>">
                <input type="hidden" name="w" value="<?= $w ?>">
                <input type="hidden" name="ps_id" value="<?= $ps['ps_id'] ?>">
                <input type="hidden" name="ip_id" value="<?= $ip['ip_id'] ?>">
                <input type="hidden" name="ip_temp" value="2">
                <input type="hidden" name="token" value="<?= get_admin_token() ?>">

                <div class="x_title">
                    <h4><span class="fa fa-check-square"></span>제품기획서<small></small></h4>                        

                    <label class="nav navbar-right"></label>
                    <div class="clearfix"></div>
                </div>

                <div><button class="btn btn_02" onclick="printPage();" type="button btn-success">출력</button></div>
                <div class="a4" id = "print">

                    <div id ="job_order_title">제품기획서</div>
                        <!-- <label><input type="checkbox" value="" name="function" onclick="func_checked()" id="function">수식</label> -->
                        <table id="new_goods_table" style="width : auto;" >
                            <colgroup>
                                <col width="97px"/>
                                <col width="178px"/>
                                <col width="107px"/>
                                <col width="85px"/>
                                <col width="75px"/>
                                <col width="75px"/>
                                <col width="75px"/>
                                <col width="115px"/>
                                <col width="95px"/>
                                <col width="100px"/>
                                <col width="80px"/>
                                <col width="107px"/>
                                <col width="60px"/>
                                <col width="100px"/>
                            </colgroup>
                        
                            <tr>
                                <th>제품명</th>
                                <td colspan = "8" class="txt_left">
                                    <input type = "hidden" name="ip_it_name" value="<?=$proposal_name?>">
                                    <?=$proposal_name?>
                                </td>
                                <th>작성일</th>
                                <td class="txt_left">
                                    <input type = "hidden" name="ip_reg_date" value="<?=$fisrt_jo['jo_reg_date']?>">
                                    <?=$fisrt_jo['jo_reg_date']?>
                                </td>
                                <th>목표납기</th>
                                <td class="txt_left">
                                    <select class="noborder jo_select" name="ip_nabgi_m" id="ip_nabgi_m" required>
                                        <option value="" <?= $ip['ip_nabgi_m'] == '' ? "selected" : "" ?>>선택</option>
                                        <?for($yyi = 1; $yyi < 13; $yyi++){?>
                                        <option value="<?=($yyi)?>" <?= $ip['ip_nabgi_m'] == ($yyi) ? "selected" : "" ?>><?=($yyi)?>월</option>
                                        <?}?>
                                    </select>
                                </td>
                                <td class="txt_left">
                                    <select class="noborder jo_select" name="ip_nabgi_limit" id="ip_nabgi_limit" required>
                                        <option value="" <?= $ip['ip_nabgi_limit'] == '' ? "selected" : "" ?>>선택</option>
                                        
                                        <option value="초" <?= $ip['ip_nabgi_limit'] == '초' ? "selected" : "" ?>>초</option>
                                        <option value="중" <?= $ip['ip_nabgi_limit'] == '중' ? "selected" : "" ?>>중</option>
                                        <option value="말" <?= $ip['ip_nabgi_limit'] == '말' ? "selected" : "" ?>>말</option>
                                        
                                    </select>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                <th rowspan="2">아이템</th>
                                <td rowspan="2" colspan = "5" class="txt_left">
                                    <?
                                        for($jis=0; $row=sql_fetch_array($jo_items); $jis++) {
                                            echo $row['jo_prod_name'] . '<br>';
                                        }
                                    ?>
                                
                                </td>
                                <th rowspan="2">브랜드</th>
                                <td rowspan="2" colspan = "2" class="txt_left">
                                    <input type = "hidden" name="ip_brand" value="<?=$fisrt_jo['jo_brand']?>">
                                    <?=$fisrt_jo['jo_brand']?>
                                </td>
                                <th>예상 입고 일정</th>
                                <td colspan="2">
                                    <span style="position: relative;">
                                        <input type="text" name="ip_ipgo_date" value="<?php echo $ip['ip_ipgo_date'] ? $ip['ip_ipgo_date'] : ''; ?>"  id="ipipgodatepicker" required class="noborder txt_left" size="21" maxlength="19">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"  style="position: absolute;bottom: 2px;right: 10px;top: auto;cursor: pointer;"></i>
                                    </span>
                                </td>
                                <td rowspan="3" colspan="2">
                                        <textarea id="text-area" class="noborder txt_left" name="ip_maker_etc"></textarea>
                                </td>
                            </tr>

                            <tr>
                               
                                <th>제조국</th>
                                <td colspan="2"><input class="noborder txt_left" type = "text" name="ip_maker_country" value="<?=$ip['ip_maker_country']?>"></td>
                                
                            </tr>
                            


                            <tr>
                                <th>소재</th>
                                <td colspan = "5" class="txt_left">
                                    <?php foreach ($jo_pumjil as $jop => $jo_pumjils) : ?>
                                        <?if(!empty($jo_pumjils['contents'])) : ?>
                                            <?=$jo_pumjils['contents']?><br>
                                        <?endif?>
                                    <?endforeach?>
                                </td>
                                <th>담당자</th>
                                <td colspan = "2" class="txt_left">
                                    <?=$fisrt_jo['jo_user']?>
                                </td>
                                <th>시즌</th>
                                <td colspan="2">
                                    <input class="noborder txt_left" type = "text" name="ip_season" value="<?=$fisrt_jo['jo_prod_year'] ?>년 <?=$fisrt_jo['jo_season'] ?>">
                                </td>
                            </tr>
                            <tr>
                                <th>기획의도</th>
                                <td colspan = "5">
                                    <table id="noline_new_goods_table" style="width : 100%;">
                                        <tr><td><input class="noborder txt_left" name="ip_proposal_memo[1]" value="<?=$ip_proposal_memo[1]['contents']?>"></td></tr>
                                        <tr><td><input class="noborder txt_left" name="ip_proposal_memo[2]" value="<?=$ip_proposal_memo[2]['contents']?>"></td></tr>
                                        <tr><td><input class="noborder txt_left" name="ip_proposal_memo[3]" value="<?=$ip_proposal_memo[3]['contents']?>"></td></tr>
                                        <tr><td><input class="noborder txt_left" name="ip_proposal_memo[4]" value="<?=$ip_proposal_memo[4]['contents']?>"></td></tr>
                                        <tr><td><input class="noborder txt_left" name="ip_proposal_memo[5]" value="<?=$ip_proposal_memo[5]['contents']?>"></td></tr>
                                    </table>
                                </td>
                                <th>원자재 및<br>부자재<br>공급업체</th>
                                <td colspan = "5">
                                    <table id="noline_new_goods_table" style="width : 100%;">
                                        <tr class="hei24"><td><span style="font-size : 9px;"><?=$jo_mater_name[1]['mater'] ? '원자재명(1)':''?></span></td><td> <?=$jo_mater_name[1]['mater']?> / <?=$jo_mater_name[1]['tel']?></td></tr>
                                        <tr class="hei24"><td><span style="font-size : 9px;"><?=$jo_mater_name[2]['mater'] ? '원자재명(2)':''?></span></td><td><?=$jo_mater_name[2]['mater']?> / <?=$jo_mater_name[2]['tel']?></td></tr>
                                        <tr class="hei24"><td><span style="font-size : 9px;"><?=$jo_mater_name[3]['mater'] ? '원자재명(3)':''?></span></td><td><?=$jo_mater_name[3]['mater']?> / <?=$jo_mater_name[3]['tel']?></td></tr>
                                        <tr class="hei24"><td><span style="font-size : 9px;"><?=$jo_mater_name[4]['mater'] ? '원자재명(4)':''?></span></td><td><?=$jo_mater_name[4]['mater']?> / <?=$jo_mater_name[4]['tel']?></td></tr>
                                        <tr class="hei24"><td><span style="font-size : 9px;"><?=$jo_mater_name[5]['mater'] ? '원자재명(5)':''?></span></td><td><?=$jo_mater_name[5]['mater']?> / <?=$jo_mater_name[5]['tel']?></td></tr>
                                    </table>
                                </td>
                                <th>원자재 <br> 매입가<br>(VAT - )</th>
                                <td>
                                    <table id="noline_new_goods_table" style="width : 100%;">
                                        <tr class="hei24"><td><?=$jo_mater_name[1]['danga'] ? number_format($jo_mater_name[1]['danga']) : ''?></td></tr>
                                        <tr class="hei24"><td><?=$jo_mater_name[2]['danga'] ? number_format($jo_mater_name[2]['danga']) : ''?></td></tr>
                                        <tr class="hei24"><td><?=$jo_mater_name[3]['danga'] ? number_format($jo_mater_name[3]['danga']) : ''?></td></tr>
                                        <tr class="hei24"><td><?=$jo_mater_name[4]['danga'] ? number_format($jo_mater_name[4]['danga']) : ''?></td></tr>
                                        <tr class="hei24"><td><?=$jo_mater_name[5]['danga'] ? number_format($jo_mater_name[5]['danga']) : ''?></td></tr>
                                    </table>
                                </td>
                            </tr>

                            <tr></tr>

                            
<!--                             
                        </table>

                        

                        <table id="new_goods_table" style="width : auto;" >
                            <colgroup>
                                <col width="178px"/>
                                <col width="107px"/>
                                <col width="85px"/>
                                <col width="75px"/>
                                <col width="75px"/>
                                <col width="75px"/>
                                <col width="115px"/>
                                <col width="95px"/>
                                <col width="100px"/>
                                <col width="80px"/>
                                <col width="107px"/>
                                <col width="60px"/>
                                <col width="100px"/>
                            </colgroup> -->
                            <tr>
                                <th rowspan = "2">예상원가<br>및<br>판매가</th>
                                <th>ITEM</th>
                                <th>원가</th>
                                <th>택가</th>
                                <th>수수료 %</th>
                                <th>세일가</th>
                                <th>택배비</th>
                                <th>공헌이익율 (택가기준)</th>
                                <th>예상생산 수량</th>
                                <th colspan="2">총 원가 예상금액</th>
                                
                                <th colspan="3">비고</th>
                            </tr>
                            <tr>
                                <td colspan="10">
                                    <table id="noline_new_goods_table" style="min-width : 965px; width : 100%;">
                                        <colgroup>
                                            <col width="178px"/>
                                            <col width="107px"/>
                                            <col width="85px"/>
                                            <col width="75px"/>
                                            <col width="75px"/>
                                            <col width="75px"/>
                                            <col width="115px"/>
                                            <col width="95px"/>
                                            <col width="100px"/>
                                            <col width="80px"/>
                                        </colgroup>
                                        <?
                                            $jo_job_order_sql = "select * from lt_job_order where jo_it_name = '{$fisrt_jo['jo_it_name']}' AND NULLIF(jo_prod_name,'') IS NOT NULL order by jo_id ASC";
                                            $jo_job_order_items = sql_query($jo_job_order_sql);
                                        ?>
                                        <?for ($vi = 0 ; $row_items = sql_fetch_array($jo_job_order_items); $vi++) {
                                            $all_false = false;
                                            foreach($ip_job_orders as $ijo => $job_items){
                                                
                                                if($row_items['jo_id'] == $job_items['jo_id']){

                                                    ?>
                                                        <tr>
                                                            <input type="hidden" name="ip_job_orders[<?=$vi?>]" value = "<?=$vi?>" >
                                                            <input type="hidden" name="ip_job_orders_jo_id[<?=$vi?>]" id = "ip_job_orders_jo_id_<?=$vi?>" data-orders-idx = "<?=$vi?>" value = "<?=$row_items['jo_id']?>" >
                                                            <td><input type="hidden" name="ip_job_orders_item[<?=$vi?>]"  value = "<?=$row_items['jo_prod_name']?><?=$row_items['jo_size_code']?> <?=item_color_name($row_items['jo_color'])?>" ><span id = "ip_job_orders_item_<?=$vi?>" data-orders-idx = "<?=$vi?>"><?=$row_items['jo_prod_name']?><?=$row_items['jo_size_code']?> <?=item_color_name($row_items['jo_color'])?></span></td>
                                                            <td><input type="hidden" name="ip_job_orders_price[<?=$vi?>]" id = "ip_job_orders_price_<?=$vi?>" data-orders-idx = "<?=$vi?>" value = "<?=$row_items['jo_total_origin_price']?>" ><span id = "ip_job_orders_price_view_<?=$vi?>" data-orders-idx = "<?=$vi?>"><?=number_format($row_items['jo_total_origin_price'])?></span></td>
                                                            <td><input class="noborder" name="ip_job_orders_tag[<?=$vi?>]" id = "ip_job_orders_tag_<?=$vi?>" data-orders-idx = "<?=$vi?>" onblur="comma_input(this)" value="<?=number_format($job_items['tag'])?>"></td>
                                                            <td><input class="noborder80 txt_right" name="ip_job_orders_sale_rate[<?=$vi?>]" id = "ip_job_orders_sale_rate_<?=$vi?>" data-orders-idx = "<?=$vi?>" value="<?=$job_items['sale_rate']?>">%</td>
                                                            <td><input class="noborder" type="text" name="ip_job_orders_sale[<?=$vi?>]" id = "ip_job_orders_sale_<?=$vi?>" data-orders-idx = "<?=$vi?>"  onblur="comma_input(this)" value = "<?=number_format($job_items['sale'])?>" ></td>
                                                            <td><input class="noborder" name="ip_job_orders_delivery[<?=$vi?>]" id = "ip_job_orders_delivery_<?=$vi?>" data-orders-idx = "<?=$vi?>" onblur="math_majin_temp2(this)" value="<?=number_format($job_items['delivery'])?>"></td>
                                                            <td><input type="hidden" name="ip_job_orders_majin[<?=$vi?>]" id = "ip_job_orders_majin_<?=$vi?>" data-orders-idx = "<?=$vi?>" value = "<?=$job_items['majin']?>" ><span id = "ip_job_orders_majin_view_<?=$vi?>" data-orders-idx = "<?=$vi?>"><?=$job_items['majin']?>%</span></td>
                                                            <td><input class="noborder" name="ip_job_orders_prod_qty[<?=$vi?>]" id = "ip_job_orders_prod_qty_<?=$vi?>" data-orders-idx = "<?=$vi?>"  onblur="about_total_price(this)" value="<?=$job_items['qty']?>"></td>
                                                            <td colspan="2"><input class="ip_total_items_price" type="hidden" name="ip_job_orders_total_price[<?=$vi?>]" id = "ip_job_orders_total_price_<?=$vi?>" data-orders-idx = "<?=$vi?>"  value = "<?=$job_items['total_price']?>" ><span id = "ip_job_orders_total_price_view_<?=$vi?>" data-orders-idx = "<?=$vi?>"><?=number_format($job_items['total_price'])?></span></td>
                                                        </tr>                                                   
                                                    <?
                                                    $all_false = true; 
                                                }else{
                                                    ?>
                                                        
                                                    
                                                    <?

                                                }
                                            }
                                            ?>

<?
                                            if(!$all_false){
                                                ?>
                                                    <tr>
                                                        <input type="hidden" name="ip_job_orders[<?=$vi?>]" value = "<?=$vi?>" >
                                                        <input type="hidden" name="ip_job_orders_jo_id[<?=$vi?>]" id = "ip_job_orders_jo_id_<?=$vi?>" data-orders-idx = "<?=$vi?>" value = "<?=$row_items['jo_id']?>" >
                                                        <td><input type="hidden" name="ip_job_orders_item[<?=$vi?>]"  value = "<?=$row_items['jo_prod_name']?><?=$row_items['jo_size_code']?> <?=item_color_name($row_items['jo_color'])?>" ><span id = "ip_job_orders_item_<?=$vi?>" data-orders-idx = "<?=$vi?>"><?=$row_items['jo_prod_name']?><?=$row_items['jo_size_code']?> <?=item_color_name($row_items['jo_color'])?></span></td>
                                                        <td><input type="hidden" name="ip_job_orders_price[<?=$vi?>]" id = "ip_job_orders_price_<?=$vi?>" data-orders-idx = "<?=$vi?>" value = "<?=$row_items['jo_total_origin_price']?>" ><span id = "ip_job_orders_price_view_<?=$vi?>" data-orders-idx = "<?=$vi?>"><?=number_format($row_items['jo_total_origin_price'])?></span></td>
                                                        <td><input class="noborder" name="ip_job_orders_tag[<?=$vi?>]" id = "ip_job_orders_tag_<?=$vi?>" data-orders-idx = "<?=$vi?>" onblur="comma_input(this)" value=""></td>
                                                        <td><input class="noborder80 txt_right" name="ip_job_orders_sale_rate[<?=$vi?>]" id = "ip_job_orders_sale_rate_<?=$vi?>" data-orders-idx = "<?=$vi?>" value="">%</td>
                                                        <td><input class="noborder" type="text" name="ip_job_orders_sale[<?=$vi?>]" id = "ip_job_orders_sale_<?=$vi?>" data-orders-idx = "<?=$vi?>"  onblur="comma_input(this)" value = "" ></td>
                                                        <td><input class="noborder" name="ip_job_orders_delivery[<?=$vi?>]" id = "ip_job_orders_delivery_<?=$vi?>" data-orders-idx = "<?=$vi?>" onblur="math_majin_temp2(this)" value=""></td>
                                                        <td><input type="hidden" name="ip_job_orders_majin[<?=$vi?>]" id = "ip_job_orders_majin_<?=$vi?>" data-orders-idx = "<?=$vi?>" value = "" ><span id = "ip_job_orders_majin_view_<?=$vi?>" data-orders-idx = "<?=$vi?>"></span></td>
                                                        <td><input class="noborder" name="ip_job_orders_prod_qty[<?=$vi?>]" id = "ip_job_orders_prod_qty_<?=$vi?>" data-orders-idx = "<?=$vi?>"  onblur="about_total_price(this)" value=""></td>
                                                        <td colspan="2"><input class="ip_total_items_price" type="hidden" name="ip_job_orders_total_price[<?=$vi?>]" id = "ip_job_orders_total_price_<?=$vi?>" data-orders-idx = "<?=$vi?>"  value = "" ><span id = "ip_job_orders_total_price_view_<?=$vi?>" data-orders-idx = "<?=$vi?>"></span></td>
                                                    </tr>
                                                
                                                <?
                                            }
                                            
                                        
                                        
                                        
                                            }?>                                       
                                        <?if($vi < 7):?>
                                        <?
                                            if($vi < 1) { 
                                                $cnt_items = 1;
                                            }else{
                                                $cnt_items = $vi+1;
                                            }
                                            ?>

                                            <?for($giidx = $cnt_items; $giidx < 8 ; $giidx++) : ?>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td colspan="2"></td>
                                                </tr>
                                            <?endfor?>
                                        <?endif?>

                                        <tr>
                                            <th colspan="8">합계</th>
                                            <th colspan="2"><span id="all_ip_total_items_price"></span></th>
                                        </tr>

                                    </table>
                                </td>
                                <td colspan="3" id="hold_td_text_area2">
                                    <div id="print_text_area">
                                        <textarea id="text-area2" class="noborder txt_left hei_92_per" name="ip_etc" placeholder=""><?=$ip['ip_etc']?></textarea>
                                    </div>
                                </td>
                            </tr>

                            <tr></tr>
                            <tr>
                                <th rowspan="2">소요량</th>
                                <td rowspan="2" colspan="5">
                                    <input type='file' id="ydImg" name ="ip_yd_img" />
                                    <div style="margin : 0 auto;">
                                        <?
                                        $img_type = explode('.' , $ip['ip_yd_img']);
                                        ?>
                                        <a class="down_img" <?if(!empty($ip['ip_yd_img'])):?> href="<?=G5_URL?>/data/new_goods/<?=$ip['ip_yd_img']?>" download="<?=$ip['ip_it_name']?>_소요량.<?=$img_type[1]?>" <?endif?>>
                                            <img id="blah" style="margin: 0 auto; margin-top: 10px; display: block; max-height:350px;" src="<?=G5_URL?>/data/new_goods/<?=$ip['ip_yd_img']?>" alt="your image" />
                                        </a>
                                    </div>
                                </td>
                                <td rowspan="2"></td>
                                <th colspan="7">IMAGE</th>
                            </tr>
                            <tr style="height:auto !important;">
                                <td colspan="7">
                                    <div class="job_images" style="display : flex;">
                                        <div id="main_pf_foto">
                                            <div><?=$jo_main_img[1]['title']?></div>
                                            <?if($jo_main_img[1]['img']):?>
                                            <input type="hidden" name="jo_main_img_img[1]"  value="<?=$jo_main_img[1]['img']?>"> 
                                            <?endif?>
                                            <img style="margin: 0 5px; display: block;" id="main_pf_foto_img" <?if($jo_main_img[1]['img']):?> src="<?=G5_URL?>/data/new_goods/<?=$jo_main_img[1]['img']?>" onclick = "click_img_down('<?=G5_URL?>/data/new_goods/<?=$jo_main_img[1]['img']?>','메인');" <?endif?>>
                                            <input class="noborder txt_center txt_op2 emptyImg" name="jo_main_img_text[1]"  value="<?=$jo_main_img[1]['text']?>" readonly>
                                        </div>
                                        <div id="codi_pf_foto">
                                            <div><?=$jo_codi_img[1]['title']?></div>
                                            <?if($jo_codi_img[1]['img']):?>
                                            <input type="hidden" name="jo_codi_img_img[1]"  value="<?=$jo_codi_img[1]['img']?>"> 
                                            <?endif?>
                                            <img style="margin: 0 5px; display: block;" id="codi_pf_foto_img" <?if($jo_codi_img[1]['img']):?>  src="<?=G5_URL?>/data/new_goods/<?=$jo_codi_img[1]['img']?>" onclick = "click_img_down('<?=G5_URL?>/data/new_goods/<?=$jo_codi_img[1]['img']?>','코디');" <?endif?> >
                                            <input class="noborder txt_center txt_op2 emptyImg" name="jo_codi_img_text[1]" value="<?=$jo_codi_img[1]['text']?>" readonly>
                                        </div>
                                        <div id="sub_pf_foto">
                                            <div><?=$jo_sub_img[1]['title']?></div>
                                            <?if($jo_sub_img[1]['img']):?>
                                            <input type="hidden" name="jo_sub_img_img[1]"  value="<?=$jo_sub_img[1]['img']?>"> 
                                            <?endif?>
                                            <img style="margin: 0 5px; display: block;" id="sub_pf_foto_img" <?if($jo_sub_img[1]['img']):?> src="<?=G5_URL?>/data/new_goods/<?=$jo_sub_img[1]['img']?>" onclick = "click_img_down('<?=G5_URL?>/data/new_goods/<?=$jo_sub_img[1]['img']?>','코디1');" <?endif?>>
                                            <input class="noborder txt_center txt_op2 emptyImg" name="jo_sub_img_text[1]" value="<?=$jo_sub_img[1]['text']?>" readonly>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        </table>
                    </div>
                </div>


                <div class="x_content">
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                            <button class="btn btn_02" type="button" id="btn_cancel">취소</button>
                            <button class="btn btn_02" type="button btn-success" id="btn_submit">임시저장</button>
                            <button type="submit" class="btn btn-success" onclick="formsubmit()"  value="저장">저장</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="../../vendors/bootstrap-tagsinput-latest/src/bootstrap-tagsinput.js"></script>
<script>
    <? if ($write_min || $write_max) { ?>
        // 글자수 제한
        var char_min = parseInt(<?= $write_min; ?>); // 최소
        var char_max = parseInt(<?= $write_max; ?>); // 최대
        check_byte("cp_content", "char_count");

        $(function() {
            $("#cp_content").on("keyup", function() {
                check_byte("cp_content", "char_count");
            });
        });

    <? } ?>
    
    function html_auto_br(obj) {
        if (obj.checked) {
            result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
            if (result)
                obj.value = "html2";
            else
                obj.value = "html1";
        } else
            obj.value = "";
    }
    $('#regdatepicker').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true,
        format: 'YYYY-MM-DD',
        locale: 'ko'
    });

    $('#ipipgodatepicker').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true,
        format: 'YYYY-MM-DD',
        locale: 'ko'
    });

    $('#clhadatepicker').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true,
        format: 'YYYY-MM-DD',
        locale: 'ko'
    });

    $("#startdatepicker").on("dp.change", function(e) {
        $('#enddatepicker').data("DateTimePicker").minDate(e.date);
    });

    $("#enddatepicker").on("dp.change", function(e) {
        $('#startdatepicker').data("DateTimePicker").maxDate(e.date);
    });

    $("#btn_cancel").click(function() {
        if (confirm("목록으로 이동 시 입력된 값은 삭제됩니다. 이동하시겠습니까?")) {
            window.history.back();
        }
    });

    $("#btn_submit").click(function() {
        fwrite_submit($("#fwrite"));
    });

    function formsubmit(){
        
        var f = document.fwrite;
        var $f = jQuery(f);
        var $b = jQuery(this);
        var $t, t;
        var result = true;
        if (confirm("저장하시겠습니까?")) {
            $f.find("input, select, textarea").each(function(i) {
                $t = jQuery(this);

                if($t.prop("required")) {
                    if(!jQuery.trim($t.val())) {
                        //t = jQuery("label[for='"+$t.attr("id")+"']").text();
                        result = false;
                        $t.focus();
                        //alert(t+" 필수 입력입니다.");
                        return false;
                    }
                }
            });
            if(!result){
                return false;
            }
            f.submit();
        }
    }

    var addItemCnt = 0;

    function fwrite_submit(f) {
        // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   
        

        //기간 설정
        if ($('#startdate').val() == "") {
            alert('게시일을 입력하세요.');
            return false;
        }

        if ($('#enddate').val() == "") {
            alert('종료일을 입력하세요.');
            return false;
        }

        if (confirm("저장하시겠습니까?")) {
            document.getElementById("btn_submit").disabled = "disabled";
            f.submit();
        } else {
            return false;
        }
    }
</script>


<script>
    //출력
    function printPage(){
        var initBody;
        window.onbeforeprint = function(){
            initBody = document.body.innerHTML;
            document.body.innerHTML =  document.getElementById('print').innerHTML;
        };
        window.onafterprint = function(){
            document.body.innerHTML = initBody;
            location.reload();
        };
        window.print();
        location.reload();
        return false;
    }
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
            $('#blah').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }
    

    $("#ydImg").change(function() {
        readURL(this);
    });


    function math_sale(elem){
        comma_input(elem);
        let id = $(elem).data("orders-idx");

        let tag = $('#ip_job_orders_tag_'+id).val().replace(/,/gi,'');
        let sale_rate = $('#ip_job_orders_sale_rate_'+id).val().replace(/,/gi,'');

        let price = tag - (tag * (sale_rate/100));

        price = Math.ceil(price * 10) / 10;

        $('#ip_job_orders_sale_'+id).val(comma(price+""));
        $('#ip_job_orders_sale_view_'+id).empty().html(comma(price+""));
    }

    function math_majin(elem){
        comma_input(elem);
        let id = $(elem).data("orders-idx");

        let sale = $('#ip_job_orders_sale_'+id).val().replace(/,/gi,'');
        let majin_rate = $('#ip_job_orders_majin_rate_'+id).val().replace(/,/gi,'');
        let origin_price = $('#ip_job_orders_price_'+id).val().replace(/,/gi,'');

        let price = ((sale - (sale * (majin_rate/100))) - origin_price) / sale ;

        price = Math.ceil(price * 10) / 10;

        
        
        $('#ip_job_orders_majin_'+id).val(comma(price+""));
        $('#ip_job_orders_majin_view_'+id).empty().html(comma(price+""));
    }

    function math_majin_temp2(elem){
        comma_input(elem);
        let id = $(elem).data("orders-idx");

        let tag = $('#ip_job_orders_tag_'+id).val().replace(/,/gi,'');
        let sale_rate = $('#ip_job_orders_sale_rate_'+id).val().replace(/,/gi,'');
        let delivery = $('#ip_job_orders_delivery_'+id).val().replace(/,/gi,'');
        let origin_price = $('#ip_job_orders_price_'+id).val().replace(/,/gi,'');


        let price = (tag - origin_price - (tag*(sale_rate / 100)) - delivery) / tag ;

        price = Math.floor(price * 100);

        
        
        $('#ip_job_orders_majin_'+id).val(comma(price+""));
        $('#ip_job_orders_majin_view_'+id).empty().html(comma(price+"")+"%");
    }

    function about_total_price(elem){
        comma_input(elem);
        let id = $(elem).data("orders-idx");
        let qty = $('#ip_job_orders_prod_qty_'+id).val().replace(/,/gi,'');
        let origin_price = $('#ip_job_orders_price_'+id).val().replace(/,/gi,'');

        let price = (origin_price*1) * (qty*1) ;

        price = Math.ceil(price * 10) / 10;
        
        $('#ip_job_orders_total_price_'+id).val(comma(price+""));
        $('#ip_job_orders_total_price_view_'+id).empty().html(comma(price+""));
        all_total_ip_items_price();
    }

    

    function comma_input(elem){
        var val = $(elem).val();
        $(elem).val(comma(val));
    }


    function comma(obj){
        
        var regx = new RegExp(/(-?\d+)(\d{3})/);
        var bExists = obj.indexOf(".", 0);//0번째부터 .을 찾는다.
        var strArr = obj.split('.');
        while (regx.test(strArr[0])) {//문자열에 정규식 특수문자가 포함되어 있는지 체크
            //정수 부분에만 콤마 달기 
            strArr[0] = strArr[0].replace(regx, "$1,$2");//콤마추가하기
        }
        if (bExists > -1) {
            //. 소수점 문자열이 발견되지 않을 경우 -1 반환
            obj = strArr[0] + "." + strArr[1];
        } else { //정수만 있을경우 //소수점 문자열 존재하면 양수 반환 
            obj = strArr[0];
        }
        return obj;//문자열 반환     
    }

    function preview_Img(imgPath){
        $("#imgPath").attr('src' , imgPath);
        $("#imgStr").html(imgPath);

        $("#modal_preview_img").modal('show');
    }


    //원자재 매입처
    $(".btn-add-purchace").on("click", function() {
        let nextIdx = $("button.btn-add-purchace").last().data("item-idx") * 1 + 1;
        let setHtml = '';
        setHtml += '<tr class="purchace_' + nextIdx + '">';
        setHtml += '<th><input type="text" name="ip_mater_purchace[' + nextIdx + ']" style="width:100px;">* <button type="button" class="btn-add-purchace" onclick="del_purchace(' + nextIdx + ')" data-item-idx=' + nextIdx + '>삭제</button></th>';
        setHtml += '<td><table><colgroup><col width="30%" class=""><col width="70%"></colgroup>';
        setHtml += '<tr><th>이미지<br>(원단스와치)</th><td><input name="ip_mater_purchace_img[' + nextIdx + ']" class="ip_mater_purchace_img" type="file"></td></tr>';
        setHtml += '<tr><th>매입처*</th><td><input name="ip_mater_purchace_maip[' + nextIdx + ']" class="ip_mater_purchace_maip"></td></tr>';
        setHtml += '<tr><th>자재단가/yd*</th><td><input name="ip_mater_purchace_danga[' + nextIdx + ']" onblur="comma_input(this)" class="ip_mater_purchace_danga" id="ip_mater_purchace_danga_' + nextIdx + '"></td></tr>';
        setHtml += '<tr><th>소재*</th><td><input name="ip_mater_purchace_soje[' + nextIdx + ']" class="ip_mater_purchace_soje"></td></tr>';
        setHtml += '</table></td></tr>';
        
        $("#ip_mater_purchace_area").append(setHtml);

    });

    function del_purchace(idx) {
        $(".purchace_"+idx).remove();
    }

    //임가공(수입)
    $(".btn-add-processing").on("click", function() {
        let nextIdx = $("button.btn-add-processing").last().data("item-idx") * 1 + 1;
        let setHtml = '';
        setHtml += '<tr class="processing_' + nextIdx + '">';
        setHtml += '<th>임가공(수입)<button type="button" class="btn-add-processing" onclick="del_processing(' + nextIdx + ')" data-item-idx=' + nextIdx + '>삭제</button></th>';
        setHtml += '<td><table><colgroup><col width="20%" class=" "><col width=""></colgroup>';
        setHtml += '<tr><th>아이템명1</th><td><input name="ip_processing_item[' + nextIdx + ']" class="ip_processing_item"></td></tr>';
        setHtml += '<tr><th>가공처</th><td><input name="ip_processing_gakong[' + nextIdx + ']" class="ip_processing_gakong"></td></tr>';
        setHtml += '<tr><th>가공임</th><td><input name="ip_processing_gakongp[' + nextIdx + ']" class="ip_processing_gakongp" onblur="comma_input(this)"></td></tr>';
        setHtml += '</table></td></tr>';
        
        $("#ip_processing_area").append(setHtml);
    });

    function del_processing(idx) {
        $(".processing_"+idx).remove();
    }

    //완제품 아이템
    $(".btn-add-finished").on("click", function() {
        let nextIdx = $("button.btn-add-finished").last().data("item-idx") * 1 + 1;
        let setHtml = '';
        setHtml += '<tr class="finished_' + nextIdx + '">';
        setHtml += '<th>완제품아이템*<button type="button" class="btn-add-finished" onclick="del_finished(' + nextIdx + ')" data-item-idx=' + nextIdx + '>삭제</button></th>';
        setHtml += '<td><table><colgroup><col width="20%" class=" "><col width=""></colgroup>';
        setHtml += '<tr><th>아이템명</th><td><input name="ip_finished_item[' + nextIdx + ']" class="ip_finished_item" value=""></td></tr>';
        setHtml += '<tr><th>사이즈cm</th><td><input name="ip_finished_size[' + nextIdx + ']" class="ip_finished_item" value=""></td></tr>';
        setHtml += '<tr><th>매입가V-</th><td><input name="ip_finished_meip[' + nextIdx + ']" onblur="comma_input(this)" class="ip_finished_meip" value="" id="ip_finished_meip_' + nextIdx + '" data-finished-idx="' + nextIdx + '"></td></tr>';
        setHtml += '<tr><th>예상원가V+*</th><td><input name="ip_finished_onega[' + nextIdx + ']" onblur="comma_input(this)" class="ip_finished_onega" value="" id="ip_finished_onega_' + nextIdx + '" data-finished-idx="' + nextIdx + '" onkeyup="prod_total_balju(this)"></td></tr>';
        setHtml += '<tr><th>예상소비자가V+*</th><td><input name="ip_finished_comsum[' + nextIdx + ']" onblur="comma_input(this)" class="ip_finished_comsum" value="" id="ip_finished_comsum_' + nextIdx + '" data-finished-idx="' + nextIdx + '" onkeyup="prod_sale_price(this)"></td></tr>';
        setHtml += '<tr><th>할인적용률%</th><td><input name="ip_finished_sale_rate[' + nextIdx + ']" class="ip_finished_sale_rate" value="" id="ip_finished_sale_rate_' + nextIdx + '" data-finished-idx="' + nextIdx + '" onkeyup="prod_sale_price(this)"></td></tr>';
        setHtml += '<tr><th>예상세일가V+</th><td><input name="ip_finished_sale_price[' + nextIdx + ']" onblur="comma_input(this)" class="ip_finished_sale_price" value="" id="ip_finished_sale_price_' + nextIdx + '" readonly></td></tr>';
        setHtml += '<tr><th>공헌이익율%</th><td><input name="ip_finished_kh_rate[' + nextIdx + ']" class="ip_finished_kh_rate" value=""></td></tr>';
        setHtml += '<tr><th>예상생산수량pc</th><td><input name="ip_finished_prod_qty[' + nextIdx + ']" onblur="comma_input(this)" class="ip_finished_prod_qty" value="" id="ip_finished_prod_qty_' + nextIdx + '" data-finished-idx="' + nextIdx + '" onkeyup="prod_total_balju(this)"></td></tr>';
        setHtml += '<tr><th>총예상발주금액V+</th><td><input name="ip_finished_total_price[' + nextIdx + ']" onblur="comma_input(this)" class="ip_finished_total_price" value="" id="ip_finished_total_price_' + nextIdx + '" readonly></td></tr>';
        setHtml += '</table></td></tr>';

        $("#ip_finished_area").append(setHtml);
    });

    function del_finished(idx) {
        $(".finished_"+idx).remove();
    }


    function prod_total_balju(elem){
        if (!is_checked("function")) {
            return false;
        }
        let id = $(elem).data("finished-idx");
        let onega = $('#ip_finished_onega_'+id).val().replace(/[^0-9]/g,'');
        let qty = $('#ip_finished_prod_qty_'+id).val().replace(/[^0-9]/g,'');

        let price = onega * qty;
        
        price = parseInt(price * 100) / 100;

        $('#ip_finished_total_price_'+id).val(comma(price+""));

    }

    function prod_sale_price(elem){
        if (!is_checked("function")) {
            return false;
        }

        let id = $(elem).data("finished-idx");
        let comsum = $('#ip_finished_comsum_'+id).val().replace(/[^0-9]/g,'');
        let rate = $('#ip_finished_sale_rate_'+id).val().replace(/[^0-9]/g,'');

        let price = (comsum*1) - ((comsum * rate ) / 100 ) ;

        price = parseInt(price * 100) / 100;

        $('#ip_finished_sale_price_'+id).val(comma(price+""));

    }

    function func_checked(){
        if (!is_checked("function")) {
            $('.ip_finished_sale_price').attr('readonly',false);
            $('.ip_finished_total_price').attr('readonly',false);
            
        }else{
            $('.ip_finished_sale_price').attr('readonly',true);
            $('.ip_finished_total_price').attr('readonly',true);
        }
    }

    function all_total_ip_items_price(){
        var fileValue = $(".ip_total_items_price").length;
        var fileData = new Array(fileValue);
        let total_items_price = 0;
        for(var i=0; i<fileValue; i++){                          
            fileData[i] = $(".ip_total_items_price")[i].value.replace(/,/gi,'');
            total_items_price += (fileData[i]*1);
        }
        total_items_price = parseInt(total_items_price * 100) / 100;
        // $('#all_ip_total_items_price').val(comma(total_items_price+""));
        $('#all_ip_total_items_price').empty().html(comma(total_items_price+""));
    }

    function click_img_down(url,filename){
        var link = document.createElement('a');
        var arr = url.split('.');
        link.href = url;
        link.download = filename+"."+arr[arr.length-1];
        link.click();
    }


</script>

<script>
    $(function() {
        func_checked();

        $('#coupon_btn_category_add').click(function() {

            var ca_id = $('#coupon_sel_product_main').val();
            if (ca_id != "") {
                let ca_name = $('#coupon_sel_product_main :selected').text();
                let ca_ids = [];

                let stop = false;
                $('#coupon_ul_category li').each(function() {
                    if ($(this).attr("data") == ca_id) {
                        alert("등록된 상품분류입니다.");
                        stop = true;
                        return;
                    }
                    ca_ids.push($(this).attr("data"));
                });
                if (stop) return;

                let li_script = '<li data="' + ca_id + '">' + ca_name +
                    '<div class="pull-right"><button type="button" class="btn btn-sm btn-default" name="coupon_btn_category_delete">X</button></div>' +
                    '</li>';

                $('#coupon_ul_category').append(li_script);
                $("button[name='coupon_btn_category_delete']").parent().css("height", "22px");
                $("button[name='coupon_btn_category_delete']").css("height", "100%");

                ca_ids.push(ca_id);
                $("#cp_item_set_category_" + CpItemIndex).val(ca_ids.join(','))
            }
        });

        $("button[name='coupon_btn_category_delete']").parent().css("height", "22px");
        $("button[name='coupon_btn_category_delete']").css("height", "100%");


        $("#btnSearch").click(function(event) {
            var $table = $("#tblProduct");
            $.post(
                "<?= G5_ADMIN_URL ?>/design/design_component_itemsearch.php", {
                    ca_id: $("#ca_id").val(),
                    stx: $("#stx").val(),
                    not_it_id_list: $("#cp_item_set_item_" + CpItemIndex).val()
                },
                function(data) {
                    if(!data){
                        alert("해당 상품이 없거나, 상품을 검색 할 수 없습니다.");
                    }
                    $table.empty().html(data);
                }
            );
        });

        $("#btnProductDel").click(function(event) {
            if (!is_checked("chk2[]")) {
                alert("삭제 하실 항목을 하나 이상 선택하세요.");
                return false;
            }

            if (confirm("삭제하시겠습니까?")) {

                var $chk = $("input[name='chk2[]']");
                var $it_id = new Array();

                for (var i = 0; i < $chk.size(); i++) {
                    if (!$($chk[i]).is(':checked')) {
                        var k = $($chk[i]).val();
                        $it_id.push($("input[name='it_id2[" + k + "]']").val());
                    }
                }

                $("#cp_item_set_item_" + CpItemIndex).val($it_id.join(","));
                tblProductFormBind();
            }
        });


        $("#btnProductSubmit").click(function(event) {
            if (!is_checked("chk[]")) {
                alert("등록 하실 항목을 하나 이상 선택하세요.");
                return false;
            }

            var $chk = $("input[name='chk[]']:checked");
            var $it_id = new Array();

            for (var i = 0; i < $chk.size(); i++) {
                var k = $($chk[i]).val();
                $it_id.push($("input[name='it_id[" + k + "]']").val());
            }

            var it_ids = $it_id.join(",");
            if ($("#cp_item_set_item_" + CpItemIndex).val() != "") it_ids += "," + $("#cp_item_set_item_" + CpItemIndex).val();
            $("#cp_item_set_item_" + CpItemIndex).val(it_ids);

            tblProductFormBind();
            $("#btnSearch").click();
        });

        $("#btnProductSearch").click(function(event) {
            $("#stx").val("");
            var $table = $("#tblProduct");
            $table.empty();
            $("#modal_product").modal('show');
        });
        all_total_ip_items_price();
        $('input').keydown(function() {
            if (event.keyCode === 13) {
                event.preventDefault();
            }
        });  
    });
</script>

<?
include_once('../../admin.tail.php');
?>