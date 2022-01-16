<?

$sub_menu = "930200";
include_once('./_common.php');
include_once('../../admin.head.php');
include_once(G5_LAYOUT_PATH . "/modal.php");


auth_check($auth[substr($sub_menu,0,2)], 'w');

$ps_id = $_GET['ps_id'];

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
    $sql = " select * from lt_item_proposal where ps_id = '$ps_id' ";
    $ip = sql_fetch($sql);
    if (!$ip['ip_id']) alert("등록된 자료가 없습니다.");

    // $cp_banner_checked = array();
    // foreach (explode(',', $cp['cp_banner']) as $cb) {
    //     $cp_banner_checked[$cb] = "checked";
    // }
}

$ps_sql = " select * from lt_prod_schedule where ps_id = '$ps_id' ";
$ps = sql_fetch($ps_sql);


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
                <input type="hidden" name="token" value="<?= get_admin_token() ?>">

                <div class="x_title">
                    <h4><span class="fa fa-check-square"></span>제품기획서<small></small></h4>                        

                    <label class="nav navbar-right"></label>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <div class="tbl_frm01 tbl_wrap">
                        <div class="table-title-first">■ 기본정보</div>
                        <label><input type="checkbox" value="" name="function" onclick="func_checked()" id="function">수식</label>
                        <table id="compaign-content-wrapper" class="ng_table">
                            <colgroup>
                                <col width="10%" class="">
                                <col width="40%">
                                <col width="10%" class="">
                                <col width="40%" >
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>브랜드명*</th>
                                    <td>
                                        <select name="ip_brand" id="ip_brand" required >
                                        
                                            <? foreach ($brands as $ck => $brand) : ?>
                                                <option value="<?= $ck ?>" <?= $ps['ps_brand'] == $ck ? "selected" : "" ?>><?= $brand ?></option>
                                            <?php endforeach ?>
                                            
                                        </select>
                                    </td>
                                    <th>상품명*</th>
                                    <td>
                                        <input name="ip_it_name" id="ip_it_name" value="<?=$ps['ps_it_name'] ?  $ps['ps_it_name'] : '' ?>" required >
                                    </td>
                                </tr>
                                <tr>
                                    <th>작성일*</th>
                                    <td>
                                        <span style="position: relative;">
                                            <input type="text" name="ip_reg_date" value="<?php echo $ip['ip_reg_date'] ? $ip['ip_reg_date'] : date("Y-m-d") ; ?>" onclick="regDatePicker()" id="regdatepicker" required class="frm_input required" size="21" maxlength="19">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar" onclick="regDatePicker()" style="position: absolute;bottom: 2px;right: 10px;top: auto;cursor: pointer;"></i>
                                        </span>
                                    </td>
                                    <th>품목(아이템)*</th>
                                    <td>
                                        <input type="text" name="ip_prod_name" id="ip_prod_name" required  value="<?=$ps['ps_prod_name']?>" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <th>구분*</th>
                                    <td>
                                        <select name="ip_gubun" id="ip_gubun" required >
                                            <option value="" <?= $ip['ip_gubun'] == '' ? "selected" : "" ?>>선택</option>
                                            <option value="정상" <?= $ip['ip_gubun'] == '정상' ? "selected" : "" ?>>정상</option>
                                            <option value="기획" <?= $ip['ip_gubun'] == '기획' ? "selected" : "" ?>>기획</option>
                                        </select>
                                    </td>
                                    <th>연도*</th>
                                    <td>
                                        <select name="ip_year" id="ip_year" required >
                                            <option value="" <?= $ip['ip_year'] == '' ? "selected" : "" ?>>선택</option>
                                            <?for($yyi = 0; $yyi < 51; $yyi++){?>
                                            <option value="<?=($yyi+2000)?>" <?= $ip['ip_year'] == ($yyi+2000) ? "selected" : "" ?>><?=($yyi+2000)?>년</option>
                                            <?}?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>시즌*</th>
                                    <td >
                                        <select name="ip_season" id="ip_season" required >
                                            <option value="" <?= $ip['ip_season'] == '' ? "selected" : "" ?>>선택</option>
                                            <option value="SS" <?= $ip['ip_season'] == 'SS' ? "selected" : "" ?>>SS</option>
                                            <option value="FW" <?= $ip['ip_season'] == 'FW' ? "selected" : "" ?>>FW</option>
                                            <option value="AA" <?= $ip['ip_season'] == 'AA' ? "selected" : "" ?>>AA</option>
                                        </select>
                                    </td>
                                    <th>생산구분*</th>
                                    <td >
                                        <select  name="ip_prod_gubun" id="ip_prod_gubun" required >
                                            <option value="" <?= $ip['ip_prod_gubun'] == '' ? "selected" : "" ?>>선택</option>
                                            <option value="임가공" <?= $ip['ip_prod_gubun'] == '임가공' ? "selected" : "" ?>>임가공</option>
                                            <option value="완사입" <?= $ip['ip_prod_gubun'] == '완사입' ? "selected" : "" ?>>완사입</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>색상* </th>
                                    <td colspan="3">
                                        <input type="text" name="ip_color" id="ip_color" value="<?= $ip['ip_color']?>" required >
                                    </td>
                                </tr>
                                <tr>
                                    <th>출하시기*</th>
                                    <td>
                                        <span style="position: relative;">
                                            <input type="text" name="ip_clha_date" value="<?php echo $ip['ip_clha_date']; ?>" onclick="clhaDatePicker()" id="clhadatepicker" required class="frm_input required" size="21" maxlength="19">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar" onclick="clhaDatePicker()" style="position: absolute;bottom: 2px;right: 10px;top: auto;cursor: pointer;"></i>
                                        </span>
                                    </td>
                                    <th>제품입고처*</th>
                                    <td>
                                        <input name="ip_item_ipgoer" id="ip_item_ipgoer" required  value="<?= $ip['ip_ipgo_center'] ?  $ip['ip_ipgo_center'] : '리탠다드물류센터' ?>"> 
                                    </td>
                                </tr>
                                <tr>
                                    <th>원산지*</th>
                                    <td>
                                        <input type="text" name="ip_mater" id="ip_mater" required  value="<?= $ip['ip_mater']?>" > 
                                    </td>
                                    <th>제조사*</th>
                                    <td>
                                        <input name="ip_importer" id="ip_importer"  required  value="<?= $ip['ip_make_center'] ? $ip['ip_make_center'] : '리탠다드' ?>"> 
                                    </td>
                                </tr>
                                <tr>
                                    <th>수입자*</th>
                                    <td>
                                        <input  name="ip_importer" id="ip_importer" required value="<?= $ip['ip_importer'] ? $ip['ip_importer'] : '리탠다드' ?>">
                                    </td>
                                    <th>판매자*</th>
                                    <td>
                                        <input  name="ip_seller" id="ip_seller" required  value="<?= $ip['ip_seller'] ? $ip['ip_seller'] : '리탠다드' ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>기획의도</th>
                                    <td colspan="3">
                                        <textarea style="width:44%;" name="ip_importer" id="ip_importer" ></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="table-title">■ 원자재 매입처</div>

                        <table id="compaign-content-wrapper">
                            <colgroup>
                                <col width="10%" class="">
                                <col width="40%">
                                <col width="10%" class="">
                                <col width="40%" >
                            </colgroup>
                                <tbody id="ip_mater_purchace_area">
                                <?if (!empty($ip_mater_purchace)) :?>
                                <?php foreach ($ip_mater_purchace as $imp => $mater_purchace) : ?>
                                <tr class="purchace_<?= $imp ?>">
                                    <th><input  type="text" name="ip_mater_purchace[<?=$imp?>]" value="<?=$mater_purchace['purchace']?>" style="width:100px;">*
                                    <?if($imp == 1) : ?>
                                    <button type="button" class="btn-add-purchace <?= $first_item !== false ? "first" : "" ?>" data-item-idx=1>추가</button>
                                    <?else:?>
                                    <button type="button" class="btn-add-purchace" onclick="del_purchace(<?= $imp ?>)" data-item-idx=<?= $imp ?>>삭제</button>
                                    <?endif?>
                                    </th>
                                    <td>
                                        <table>
                                            <colgroup>
                                                <col width="30%" class="">
                                                <col width="70%">
                                            </colgroup>
                                            <tr>
                                                <th>이미지<br>(원단스와치)</th>
                                                <td>
                                                    <input name="ip_mater_purchace_img_<?= $imp ?>" class="ip_mater_purchace_img" type="file" accept="image/*">
                                                    <?php if ($w == 'u' ) : ?>
                                                        <span class="file_del">
                                                            <label for="ip_mater_purchace_img_<?= $imp ?>"><?=$mater_purchace['img']?></label>
                                                        </span>
                                                        <button class="btn btn_01" type="button" id="btn_preview_img" onclick=preview_Img("<?= G5_DATA_URL . '/new_goods/' . $mater_purchace['img'] ?>")>미리보기</button>
                                                    <?php endif ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>매입처*</th>
                                                <td><input name="ip_mater_purchace_maip[<?= $imp ?>]" required  value="<?=$mater_purchace['maip']?>" class="ip_mater_purchace_maip"></td>
                                            </tr>
                                            <tr>
                                                <th>자재단가/yd*</th>
                                                <td><input name="ip_mater_purchace_danga[<?= $imp ?>]" required  onblur="comma_input(this)" id="ip_mater_purchace_danga_<?= $imp ?>" value="<?=number_format($mater_purchace['danga'])?>" class="ip_mater_purchace_danga"></td>
                                            </tr>
                                            <tr>
                                                <th>소재*</th>
                                                <td><input name="ip_mater_purchace_soje[<?= $imp ?>]" required  value="<?=$mater_purchace['soje']?>" class="ip_mater_purchace_soje"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            
                                <?php endforeach ?>
                                <?endif?>
                                <?php if (empty($ip_mater_purchace)) : ?>
                                <tr class="purchace_1">
                                    <th><input type="text" name="ip_mater_purchace[1]"  style="width:100px;">*<button type="button" class="btn-add-purchace <?= $first_item !== false ? "first" : "" ?>" data-item-idx=1>추가</button></th>
                                    <td>
                                        <table>
                                            <colgroup>
                                                <col width="30%" class="">
                                                <col width="70%">
                                            </colgroup>
                                            <tr>
                                                <th>이미지<br>(원단스와치)</th>
                                                <td><input name="ip_mater_purchace_img[1]" class="ip_mater_purchace_img" type="file"></td>
                                            </tr>
                                            <tr>
                                                <th>매입처*</th>
                                                <td><input name="ip_mater_purchace_maip[1]" value="" class="ip_mater_purchace_maip"></td>
                                            </tr>
                                            <tr>
                                                <th>자재단가/yd*</th>
                                                <td><input name="ip_mater_purchace_danga[1]" id = "ip_mater_purchace_danga_1" onblur="comma_input(this)" class="ip_mater_purchace_danga"></td>
                                            </tr>
                                            <tr>
                                                <th>소재*</th>
                                                <td><input name="ip_mater_purchace_soje[1]" class="ip_mater_purchace_soje"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                
                                <?php endif ?>
                                </tbody>
                            </table>
                            <div class="table-title">■ 기타</div>
                            <table>
                                <tbody id="ip_processing_area">
                                <?if (!empty($ip_processing)) :?>
                                <?php foreach ($ip_processing as $ipp => $processing) : ?>
                                <tr class="processing_<?=$ipp?>">
                                    <th>임가공(수입)
                                    <?if($ipp == 1) : ?>
                                    <button type="button" class="btn-add-processing <?= $first_item !== false ? "first" : "" ?>" data-item-idx=1>추가</button>
                                    <?else:?>
                                    <button type="button" class="btn-add-processing" onclick="del_processing(<?= $ipp ?>)" data-item-idx=<?= $ipp ?>>삭제</button>
                                    <?endif?>
                                    </th>
                                    <td>
                                        <table>
                                            <colgroup>
                                                <col width="20%" class=" ">
                                                <col width="">
                                            </colgroup>
                                            <tr>
                                                <th>아이템명1</th>
                                                <td><input name="ip_processing_item[<?= $ipp ?>]"  value="<?=$processing['item']?>" class="ip_processing_item"></td>
                                            </tr>
                                            <tr>
                                                <th>가공처</th>
                                                <td><input name="ip_processing_gakong[<?= $ipp ?>]"  value="<?=$processing['gakong']?>" class="ip_processing_gakong"></td>
                                            </tr>
                                            <tr>
                                                <th>가공임</th>
                                                <td><input name="ip_processing_gakongp[<?= $ipp ?>]" id="ip_processing_gakongp_<?= $ipp ?>" value="<?=$processing['gakongp']?>" onblur="comma_input(this)" class="ip_processing_gakongp"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <?php endforeach ?>
                                <?endif?>
                                <?php if (empty($ip_processing)) : ?>
                                <tr class="processing_1">
                                    <th>임가공(수입)
                                        <button type="button" class="btn-add-processing <?= $first_item !== false ? "first" : "" ?>" data-item-idx=1>추가</button>
                                    </th>
                                    <td>
                                        <table>
                                            <colgroup>
                                                <col width="20%" class=" ">
                                                <col width="">
                                            </colgroup>
                                            <tr>
                                                <th>아이템명1</th>
                                                <td><input name="ip_processing_item[1]" class="ip_processing_item"></td>
                                            </tr>
                                            <tr>
                                                <th>가공처</th>
                                                <td><input name="ip_processing_gakong[1]" class="ip_processing_gakong"></td>
                                            </tr>
                                            <tr>
                                                <th>가공임</th>
                                                <td><input name="ip_processing_gakongp[1]" id="ip_processing_gakongp_1" class="ip_processing_gakongp" onblur="comma_input(this)"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                
                                <?php endif ?>
                                </tbody>
                                <tbody id="ip_finished_area">
                                <?if (!empty($ip_finished)) :?>
                                <?php foreach ($ip_finished as $if => $finished) : ?>
                                <tr class="finished_<?= $if ?>">
                                    <th>완제품아이템*
                                        <?if($if == 1) : ?>
                                        <button type="button" class="btn-add-finished <?= $first_item !== false ? "first" : "" ?>" data-item-idx=1>추가</button>
                                        <?else:?>
                                        <button type="button" class="btn-add-finished" onclick="del_finished(<?= $if ?>)" data-item-idx=<?= $if ?>>삭제</button>
                                        <?endif?>
                                    </th>
                                    <td>
                                        <table>
                                            <colgroup>
                                                <col width="20%" class=" ">
                                                <col width="">
                                            </colgroup>
                                            <tr>
                                                <th>아이템명</th>
                                                <td><input name="ip_finished_item[<?= $if ?>]" class="ip_finished_item" value="<?=$finished['item']?>"></td>
                                            </tr>
                                            <tr>
                                                <th>사이즈cm</th>
                                                <td><input name="ip_finished_size[<?= $if ?>]" class="ip_finished_size" value="<?=$finished['size']?>"></td>
                                            </tr>
                                            <tr>
                                                <th>매입가V-</th>
                                                <td><input name="ip_finished_meip[<?= $if ?>]" class="ip_finished_meip" onblur="comma_input(this)" id="ip_finished_meip_<?= $if ?>" value="<?=number_format($finished['meip'])?>" data-finished-idx="<?=$if?>"></td>
                                            </tr>
                                            <tr>
                                                <th>예상원가V+*</th>
                                                <td><input name="ip_finished_onega[<?= $if ?>]" class="ip_finished_onega" onblur="comma_input(this)" id="ip_finished_onega_<?= $if ?>" value="<?=number_format($finished['onega'])?>" data-finished-idx="<?=$if?>" onkeyup="prod_total_balju(this)"></td>
                                            </tr>
                                            <tr>
                                                <th>예상소비자가V+*</th>
                                                <td><input name="ip_finished_comsum[<?= $if ?>]" class="ip_finished_comsum" onblur="comma_input(this)" value="<?=number_format($finished['comsum'])?>" id="ip_finished_comsum_<?=$if?>" data-finished-idx="<?=$if?>" onkeyup="prod_sale_price(this)"></td>
                                            </tr>
                                            <tr>
                                                <th>할인적용률%</th>
                                                <td><input name="ip_finished_sale_rate[<?= $if ?>]" class="ip_finished_sale_rate" value="<?=$finished['srate']?>" id="ip_finished_sale_rate_<?=$if?>" data-finished-idx="<?=$if?>" onkeyup="prod_sale_price(this)"></td>
                                            </tr>
                                            <tr>
                                                <th>예상세일가V+</th>
                                                <td><input name="ip_finished_sale_price[<?= $if ?>]" class="ip_finished_sale_price" onblur="comma_input(this)" id = "ip_finished_sale_price_<?= $if ?>" value="<?=number_format($finished['sprice'])?>" readonly></td>
                                            </tr>
                                            <tr>
                                                <th>공헌이익율%</th>
                                                <td><input name="ip_finished_kh_rate[<?= $if ?>]" class="ip_finished_kh_rate" value="<?=$finished['khrate']?>"></td>
                                            </tr>
                                            <tr>
                                                <th>예상생산수량pc</th>
                                                <td><input name="ip_finished_prod_qty[<?= $if ?>]" class="ip_finished_prod_qty" onblur="comma_input(this)" id="ip_finished_prod_qty_<?= $if ?>" value="<?=number_format($finished['prodqty'])?>" data-finished-idx="<?=$if?>" onkeyup="prod_total_balju(this)"></td>
                                            </tr>
                                            <tr>
                                                <th>총예상발주금액V+</th>
                                                <td><input name="ip_finished_total_price[<?= $if ?>]" class="ip_finished_total_price" onblur="comma_input(this)" id="ip_finished_total_price_<?= $if ?>" value="<?=number_format($finished['totalp'])?>" readonly></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                               
                                <?php endforeach ?>
                                <?endif?>
                                <?php if (empty($ip_finished)) : ?>
                                <tr class="finished_1">
                                    <th>완제품아이템*
                                        <button type="button" class="btn-add-finished <?= $first_item !== false ? "first" : "" ?>" data-item-idx=1>추가</button>
                                    </th>
                                    <td>
                                        <table>
                                            <colgroup>
                                                <col width="20%" class=" ">
                                                <col width="">
                                            </colgroup>
                                            <tr>
                                                <th>아이템명</th>
                                                <td><input name="ip_finished_item[1]" class="ip_finished_item" value=""></td>
                                            </tr>
                                            <tr>
                                                <th>사이즈cm</th>
                                                <td><input name="ip_finished_size[1]" class="ip_finished_size" value=""></td>
                                            </tr>
                                            <tr>
                                                <th>매입가V-</th>
                                                <td><input name="ip_finished_meip[1]" class="ip_finished_meip" value="" onblur="comma_input(this)"></td>
                                            </tr>
                                            <tr>
                                                <th>예상원가V+*</th>
                                                <td><input name="ip_finished_onega[1]" class="ip_finished_onega" id="ip_finished_onega_1" onblur="comma_input(this)" value="" data-finished-idx="1" onkeyup="prod_total_balju(this)"></td>
                                            </tr>
                                            <tr>
                                                <th>예상소비자가V+*</th>
                                                <td><input name="ip_finished_comsum[1]" class="ip_finished_comsum" id="ip_finished_comsum_1" onblur="comma_input(this)" value=""  data-finished-idx="1" onkeyup="prod_sale_price(this)"></td>
                                            </tr>
                                            <tr>
                                                <th>할인적용률%</th>
                                                <td><input name="ip_finished_sale_rate[1]" class="ip_finished_sale_rate" id="ip_finished_sale_rate_1" value=""  data-finished-idx="1" onkeyup="prod_sale_price(this)"></td>
                                            </tr>
                                            <tr>
                                                <th>예상세일가V+</th>
                                                <td><input name="ip_finished_sale_price[1]" class="ip_finished_sale_price" id="ip_finished_sale_price_1" onblur="comma_input(this)" value="" readonly></td>
                                            </tr>
                                            <tr>
                                                <th>공헌이익율%</th>
                                                <td><input name="ip_finished_kh_rate[1]" class="ip_finished_kh_rate" value=""></td>
                                            </tr>
                                            <tr>
                                                <th>예상생산수량pc</th>
                                                <td><input name="ip_finished_prod_qty[1]" class="ip_finished_prod_qty" id="ip_finished_prod_qty_1" onblur="comma_input(this)" value="" data-finished-idx="1" onkeyup="prod_total_balju(this)"></td>
                                            </tr>
                                            <tr>
                                                <th>총예상발주금액V+</th>
                                                <td><input name="ip_finished_total_price[1]" class="ip_finished_total_price" id="ip_finished_total_price_1" onblur="comma_input(this)" value="" readonly></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                               
                                <?php endif ?>
                                </tbody>
                                <tr>
                                    <th>실적참고데이터</th>
                                    <td colspan="3">
                                        <input type="file">
                                    </td>
                                   
                                </tr>
                            </tbody>
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
    });
</script>

<?
include_once('../../admin.tail.php');
?>