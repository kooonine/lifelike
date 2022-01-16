<?

$sub_menu = "930200";
include_once('./_common.php');
include_once('../../admin.head.php');
include_once(G5_LAYOUT_PATH . "/modal.php");


auth_check($auth[substr($sub_menu,0,2)], 'w');

$ps_id = $_GET['ps_id'];
$size = $_REQUEST['size'];
$get_size = iconv('euc-kr', 'utf-8', $size);

if (!($w == '' || $w == 'u' || $w == 'r' || $w == 'copy')) {
    alert('w 값이 제대로 넘어오지 않았습니다.');
}

if ($w == '') {
    $title_msg = '작성';
    if ($cp_id) {
        alert('글쓰기에는 \$cp_id 값을 사용하지 않습니다.');
    }
} else if ($w == 'u') {
    $title_msg = '수정';
    $sql = " select * from lt_job_order where ps_id = '$ps_id' and jo_size = '$size' ";
    $jo = sql_fetch($sql);
    if (!$jo['jo_id']) alert("등록된 자료가 없습니다.");

} else if($w == 'copy'){
    $title_msg = '복사';
    $sql = " select * from lt_job_order where ps_id = '$ps_id' order by jo_id  limit 1 ";
    $jo = sql_fetch($sql);
    if (!$jo['jo_id']) alert("등록된 자료가 없습니다.");
}

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

$ps_sql = " select * from lt_prod_schedule where ps_id = '$ps_id' ";
$ps = sql_fetch($ps_sql);

$jo_soje_set = array();
if (!empty($jo['jo_soje'])) {
    $jo_soje_set = json_decode($jo['jo_soje'], true);
}

$jo_mater_info = array();
if (!empty($jo['jo_mater_info'])) {
    $jo_mater_info = json_decode($jo['jo_mater_info'], true);
}
$jo_sub_mater = array();
if (!empty($jo['jo_sub_mater'])) {
    $jo_sub_mater = json_decode($jo['jo_sub_mater'], true);
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

$content = '';
if ($w == 'r') {
    if (!strstr($cp['cp_option'], 'html')) {
        $content = "\n\n\n &gt; "
            . "\n &gt; "
            . "\n &gt; " . str_replace("\n", "\n> ", get_text($cp['cp_content'], 0))
            . "\n &gt; "
            . "\n &gt; ";
    }
} else {
    $content = get_text($cp['cp_content'], 0);
}

$content_mobile = '';
if ($w == 'r') {
    if (!strstr($cp['cp_option'], 'html')) {
        $content_mobile = "\n\n\n &gt; "
            . "\n &gt; "
            . "\n &gt; " . str_replace("\n", "\n> ", get_text($cp['cp_content_mobile'], 0))
            . "\n &gt; "
            . "\n &gt; ";
    }
} else {
    $content_mobile = get_text($cp['cp_content_mobile'], 0);
}


$g5['title'] = "작업지시서 " . $title_msg;


$action_url = https_url('adm') . "/shop_admin/new_goods/job.order.update.php";
$brands = array(
    '' => '선택',
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
    '' => '선택',
    '커버' => '커버',
    '기타' => '기타',
    // '소품액세서리' => '소품액세서리',
    '속통류' => '속통류'
);

$pumitem1 = array(
    '' => '선택',
    '홑겹이불커버' => '홑겹이불커버','누비이불커버' => '누비이불커버',    '차렵이불' => '차렵이불','누비이불' => '누비이불',    '홑이불' => '홑이불','겹이불' => '겹이불',    '홑보더이불' => '홑보더이불','베개커버' => '베개커버',    '자루베개커버' => '자루베개커버','누비베개커버' => '누비베개커버',    '매트커버' => '매트커버','누비매트커버' => '누비매트커버',    '프로텍터매트커버' => '프로텍터매트커버','프로텍터커버(토펴용)' => '프로텍터커버(토펴용)',    '플랫시트' => '플랫시트','패드' => '패드',    '침대커버' => '침대커버','스프레드' => '스프레드',    '카페트' => '카페트','요커버' => '요커버',    '누비요커버' => '누비요커버','쿠션커버' => '쿠션커버',    '방석커버' => '방석커버','이불베개set' => '이불베개set',    '이불매트베개set' => '이불매트베개set','이불플랫베개set' => '이불플랫베개set',    '이불베개패드set' => '이불베개패드set','홑보더베개set' => '홑보더베개set',    '차렵베개set' => '차렵베개set','차렵베개패드set' => '차렵베개패드set',    '누비이불베개set' => '누비이불베개set','스프레드베개set' => '스프레드베개set',    '인견이불패드베개set' => '인견이불패드베개set'
);
$pumitem2 = array(
    '' => '선택',
    '셔츠' => '셔츠','팬츠' => '팬츠','가디건' => '가디건','가운' => '가운','바스가운' => '바스가운','잠옷' => '잠옷',
    '타올' => '타올','핸드타올' => '핸드타올','바스타올' => '바스타올','굿스카프' => '굿스카프','예단보자기' => '예단보자기','에코백' => '에코백','기타' => '기타','굿숄' => '굿숄'
);
// $pumitem3 = array(
//     '' => '선택',
//     '타올' => '타올','핸드타올' => '핸드타올','바스타올' => '바스타올','굿스카프' => '굿스카프','예단보자기' => '예단보자기','에코백' => '에코백','기타' => '기타','굿숄' => '굿숄'
// );

$pumitem4 = array(
    '' => '선택',
    '담요' => '담요','기타쿠션' => '기타쿠션',    '기타방석' => '기타방석','거위털쿠션솜' => '거위털쿠션솜','거위털방석솜' => '거위털방석솜','독서쿠션(드라마)' => '독서쿠션(드라마)','오리털쿠션솜' => '오리털쿠션솜','오리털방석솜' => '오리털방석솜',    '거위털블랭킷' => '거위털블랭킷','오리털블랭킷' => '오리털블랭킷',    '폴리블랭킷' => '폴리블랭킷','요솜' => '요솜',    '기타이불솜' => '기타이불솜','거위털이불솜(사계절)' => '거위털이불솜(사계절)',    '거위털이불솜(간절기)' => '거위털이불솜(간절기)','거위털이불솜(한계울)' => '거위털이불솜(한계울)','거위털차렵이불(사계절)' => '거위털차렵이불(사계절)',    '거위털차렵이불(간절기)' => '거위털차렵이불(간절기)',    '오리털이불솜(사계절)' => '오리털이불솜(사계절)','오리털이불솜(간절기)' => '오리털이불솜(간절기)',        '오리털이불솜(한겨울)' => '오리털이불솜(한겨울)','오리털차렵이불(사계절)' => '오리털차렵이불(사계절)',    '오리털차렵이불(간절기)' => '오리털차렵이불(간절기)',        '폴리이불솜' => '폴리이불솜',    '거위차렵베개set' => '거위차렵베개set','오리차렵베개set' => '오리차렵베개set',    '기타베개솜' => '기타베개솜',        '거위털베개솜' => '거위털베개솜',    '거위털베개솜(Firm)' => '거위털베개솜(Firm)','거위털베개솜(Slim)' => '거위털베개솜(Slim)',            '바디필로우' => '바디필로우','경추베개솜' => '경추베개솜',    '오리털베개솜' => '오리털베개솜',           '오리털베개솜(Firm)' => '오리털베개솜(Firm)','오리털베개솜(Slim)' => '오리털베개솜(Slim)',    '폴리베개솜' => '폴리베개솜',           '거위털페더베드(고급형)' => '거위털페더베드(고급형)','거위털페더베드(일반형)' => '거위털페더베드(일반형)',    '거위털페더베드' => '거위털페더베드',           '거위털패드' => '거위털패드','구스토퍼' => '구스토퍼',    '폴리토퍼' => '폴리토퍼'

);

$prod_it_name = array(

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
    button.btn-add-mater {
        visibility: visible;
    }

    button.btn-add-mater.first {
        visibility: visible;
    }

    .pumi1,.pumi2, .pumi3, .pumi4{display:none;}

    .pum1 > .pumi1,.pum2 > .pumi2, .pum3 > .pumi3, .pum4 > .pumi4{display:block;}

    .table-title {font-size: 17px;font-weight: bold;margin-top: 38px;margin-bottom: 5px;}
    .table-title-first {font-size: 17px;font-weight: bold;margin-bottom: 5px;margin-top: 19px;}
</style>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <form name="fwrite" id="fwrite" action="<?= $action_url ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="uid" value="<?= get_uniqid(); ?>">
                <input type="hidden" name="w" value="<?= $w ?>">
                <input type="hidden" name="ps_id" value="<?= $ps_id ?>">
                <input type="hidden" name="jo_id" value="<?= $jo['jo_id'] ?>">
                <input type="hidden" name="token" value="<?= get_admin_token() ?>">

                <div class="x_title">
                    <h4><span class="fa fa-check-square"></span>작업지시서<small></small></h4>

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
                                        <select name="jo_brand" id="jo_brand" required >
                                        
                                            <? foreach ($brands as $ck => $brand) : ?>
                                                <option value="<?= $ck ?>" <?= $ps['ps_brand'] == $ck ? "selected" : "" ?>><?= $brand ?></option>
                                            <?php endforeach ?>
                                            
                                        </select>
                                    </td>
                                    <th>상품명*</th>
                                    <td>
                                        <input name="jo_it_name" id="jo_it_name" value="<?=$ps['ps_it_name'] ?  $ps['ps_it_name'] : '' ?>" required >
                                    </td>
                                </tr>
                                <tr>
                                    <th>작성일*</th>
                                    <td>
                                        <span style="position: relative;" required >
                                            <input type="text" name="jo_reg_date" value="<?php echo $jo['jo_reg_date']; ?>" onclick="regDatePicker()" id="regdatepicker" required class="frm_input required" size="21" maxlength="19">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar" onclick="regDatePicker()" style="position: absolute;bottom: 2px;right: 10px;top: auto;cursor: pointer;"></i>
                                        </span>
                                    </td>
                                    <th>품종*</th>
                                    <td>
                                        <select name="jo_prod_type" id ="jo_prod_type" required >
                                        <? foreach ($pumjongs as $pj => $pumjong) : ?>
                                            <option value="<?= $pj ?>" <?= $jo['jo_prod_type'] == $pj ? "selected" : "" ?>><?= $pumjong ?></option>
                                        <?php endforeach ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>품목(아이템)*</th>
                                    <td>
                                        <select name="jo_prod_name" id ="jo_prod_name" class="
                                        <? if ($jo['jo_prod_type'] == '커버'):?>
                                        pum1
                                        <? elseif ($jo['jo_prod_type'] == '기타'):?>
                                        pum2
                                        
                                        <? elseif ($jo['jo_prod_type'] == '속통류'):?>
                                        pum4
                                        <? endif ?>
                                        " required >
                                            
                                                <? foreach ($pumitem1 as $pi1 => $item1) : ?>
                                                    <option class="pumi1" value="<?= $pi1 ?>" <?= $jo['jo_prod_name'] == $pi1 ? "selected" : "" ?>><?= $item1 ?></option>
                                                <?php endforeach ?>

                                            
                                                <? foreach ($pumitem2 as $pi2 => $item2) : ?>
                                                    <option class="pumi2" value="<?= $pi2 ?>" <?= $jo['jo_prod_name'] == $pi2 ? "selected" : "" ?>><?= $item2 ?></option>
                                                <?php endforeach ?>


                                            
                                                <? foreach ($pumitem4 as $pi4 => $item4) : ?>
                                                    <option class="pumi4" value="<?= $pi4 ?>" <?= $jo['jo_prod_name'] == $pi4 ? "selected" : "" ?>><?= $item4 ?></option>
                                                <?php endforeach ?>                                      
                                        </select>
                                    </td>
                                    <th></th>
                                    <td>
                                    </td>
                                </tr>
                                <tr>
                                    <th>사이즈CM*</th>
                                    <td colspan="3">
                                        <input name="jo_size" id ="jo_size" value="<?=$w == 'copy' ? '' : ($jo['jo_size'] ?  $jo['jo_size'] : '') ?>" required >
                                    </td>
                                </tr>
                                <tbody>
                                    <?if (!empty($jo_soje_set)) :?>
                                    <tr>
                                        <th>소재(품질표시)*</th>
                                        <td>
                                            <table>
                                                <colgroup>
                                                <col width="30%" class="">
                                                <col width="70%">
                                            </colgroup>
                                            <tbody id="jo_soje_area">    
                                                <?php foreach ($jo_soje_set as $js => $soje) : ?>
                                                <tr class="soje_<?=$js?>">
                                                    <th colspan="2">
                                                        <input type="hidden" name="jo_soje_set">
                                                        <input type="text" name="jo_soje_subject[<?= $js ?>]" id="jo_soje_subject<?= $js ?>" value="<?= $soje['subject'] ?>" required>
                                                        <?if($js == 1) : ?>
                                                        <button type="button" class="btn-add-soje first" data-item-idx=1>추가</button>
                                                        <?else:?>
                                                            <button type="button" class="btn-add-soje" onclick="del_soje(<?=$js?>)" data-item-idx=<?=$js?>>삭제</button>
                                                        <?endif?>
                                                    </th>
                                                    <!-- <td>
                                                        <input type="text" name="jo_soje_item[<?= $js ?>]" id="cp_item_set_item<?= $js ?>" value="<?= $soje['item'] ?>">
                                                    </td> -->
                                                </tr>
                                                <?php endforeach ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <? endif?>
                                    <?php $first_item = false ?>

                                    <?php if (empty($jo_soje_set)) : ?>
                                    <tr>
                                        <th scope="row">소재(품질표시)*</th>
                                        <td>
                                            <table>
                                                <colgroup>
                                                    <col width="30%" class="">
                                                    <col width="70%">
                                                </colgroup>
                                                <tbody id="jo_soje_area">
                                                    <tr class="soje_1">
                                                        <th colspan="2">
                                                            <input type="hidden" name="jo_soje_set">
                                                            <input type="text" name="jo_soje_subject[1]" id="jo_soje_subject_1" value="" required> <button type="button" class="btn-add-soje first" data-item-idx=1>추가</button>
                                                        </th>
                                                        <!-- <td>
                                                            <input type="text" name="jo_soje_item[1]" id="cp_item_set_item_1" value="">
                                                        </td> -->
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                <?php endif ?>

                                </tbody>
                                    
                                <tr>
                                    <th>디자인이미지*</th>
                                    <td colspan="3">
                                        <input type="file" name ="jo_design_img" > 
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="table-title">■ 원자재 정보</div>
                        
                        <table id="compaign-content-wrapper">
                            <colgroup>
                                <col width="10%" class="">
                                <col width="40%">
                                <col width="10%" class="">
                                <col width="40%" >
                            </colgroup>
                                <tbody id="jo_mater_info_area">
                                <?if (!empty($jo_mater_info)) :?>
                                <?php $total_origin_mater_price =0; $frist_ = 0;?>
                                <?php foreach ($jo_mater_info as $sjm => $smater_info) {
                                    $total_origin_mater_price += $smater_info['price'];
                                } ?>                                
                                <?php foreach ($jo_mater_info as $jm => $mater_info) : ?>
                                <tr class="mater_<?=$jm?>">
                                    <th>
                                        <input type="text" name="jo_mater_info[<?=$jm?>]" value="<?= $mater_info['info'] ?>" style="width:100px;" required>* 
                                        <?if($jm == 1) : ?>
                                        <button type="button" class="btn-add-mater <?= $first_item !== false ? "first" : "" ?>" data-item-idx=1>추가</button>
                                        <?else:?>
                                        <button type="button" class="btn-add-mater" onclick="del_mater(<?= $jm ?>)" data-item-idx=<?= $jm ?>>삭제</button>
                                        <?endif?>
                                    </th>
                                    <td>
                                    <?if($jm == 1) : ?>
                                        원자재금액V- 총합 : <span id ="total_origin_mater_price_view"> <?=number_format($total_origin_mater_price)?> </span>원
                                        <input type="hidden" id ="total_origin_mater_price" value ="<?=$total_origin_mater_price?>" > 
                                    <?endif?>
                                        <table>
                                            <colgroup>
                                                <col width="30%" class="">
                                                <col width="70%">
                                            </colgroup>
                                            <tr>
                                                <th>폭</th>
                                                <td><input name="jo_mater_info_wid[<?=$jm?>]" id="jo_mater_info_wid_<?=$jm?>" required data-mater-idx="<?=$jm?>" value="<?=number_format( $mater_info['wid']) ?>"  onblur="yochek_cal(this)" class="jo_mater_info_wid"></td>
                                            </tr>
                                            <tr>
                                                <th>길이</th>
                                                <td><input name="jo_mater_info_length[<?=$jm?>]" id="jo_mater_info_length_<?=$jm?>" required data-mater-idx="<?=$jm?>" value="<?=number_format($mater_info['length']) ?>"  onblur="yochek_cal(this)" class="jo_mater_info_length"></td>
                                            </tr>
                                            <tr>
                                                <th>요척*</th>
                                                <td><input name="jo_mater_info_yochek[<?=$jm?>]" id="jo_mater_info_yochek_<?=$jm?>" class="yochek_input"  required value="<?=  number_format($mater_info['yochek'],1) ?>" data-mater-idx="<?=$jm?>" onkeyup="press(this)"></td>
                                            </tr>
                                            <tr>
                                                <th>자재단가 /yd, V-*</th>
                                                <td><input name="jo_mater_info_mater_danga[<?=$jm?>]" id="jo_mater_info_mater_danga_<?=$jm?>" class="jo_mater_info_mater_danga" required onblur="comma_input(this)" value="<?= number_format($mater_info['danga']) ?>" data-mater-idx="<?=$jm?>" onkeyup="mater_math(this)"></td>
                                            </tr>
                                            <tr>
                                                <th>원자재 금액 V-*</th>
                                                <td><input name="jo_mater_info_mater_price[<?=$jm?>]" class="jo_mater_info_mater_price" id="jo_mater_info_mater_price_<?=$jm?>" required value="<?= number_format($mater_info['price'],2) ?>" onblur="comma_input(this)" readonly ></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <?php $first_item = false ?>
                                <?php endforeach ?>
                                <?endif?>
                                <?php if (empty($jo_mater_info)) : ?>
                                    <tr class="mater_1">
                                        <th><input type="text" name="jo_mater_info[1]" style="width:100px;" required>* <button type="button" class="btn-add-mater <?= $first_item !== false ? "first" : "" ?>" data-item-idx=1>추가</button></th>
                                        <td>
                                            원자재금액V- 총합 : <span id ="total_origin_mater_price_view">  </span>원
                                            <input type="hidden" id ="total_origin_mater_price" value ="<?=$total_origin_mater_price?>" > 
                                            <table>
                                                <colgroup>
                                                    <col width="30%" class="">
                                                    <col width="70%">
                                                </colgroup>
                                                <tr>
                                                    <th>폭</th>
                                                    <td><input name="jo_mater_info_wid[1]" id="jo_mater_info_wid_1" required  data-mater-idx="1" class="jo_mater_info_wid"   onblur="yochek_cal(this)"></td>
                                                </tr>
                                                <tr>
                                                    <th>길이</th>
                                                    <td><input name="jo_mater_info_length[1]" id="jo_mater_info_length_1" required data-mater-idx="1" class="jo_mater_info_length"   onblur="yochek_cal(this)"></td>
                                                </tr>
                                                <tr>
                                                    <th>요척*</th>
                                                    <td><input name="jo_mater_info_yochek[1]" id="jo_mater_info_yochek_1" required data-mater-idx="1" onblur="comma_input(this)"  onkeyup="press(this)"></td>
                                                </tr>
                                                <tr>
                                                    <th>자재단가 /yd, V-*</th>
                                                    <td><input name="jo_mater_info_mater_danga[1]" class="jo_mater_info_mater_danga" required id="jo_mater_info_mater_danga_1" data-mater-idx="1" onblur="comma_input(this)" onkeyup="mater_math(this)"></td>
                                                </tr>
                                                <tr>
                                                    <th>원자재 금액 V-*</th>
                                                    <td><input name="jo_mater_info_mater_price[1]" class='jo_mater_info_mater_price' required id="jo_mater_info_mater_price_1" onblur="comma_input(this)" readonly></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                <?php endif ?>
                                </tbody>

                                <tr>
                                    <th>부자재*</th>
                                    <td colspan="3">
                                        <table>
                                            <colgroup>
                                                <col width="10%" class="grid_4">
                                                <col width="">
                                            </colgroup>
                                            <tr>
                                                <input type="hidden" name="jo_sub_mater">
                                                <th>부자재내역</th>
                                                <td><input type="text" name="jo_sub_mater_history[1]" required value="<?= $jo_sub_mater[1]['history'] ?>"></td>
                                            </tr>
                                            <tr>
                                                <th>단가</th>
                                                <td><input type="text" name="jo_sub_mater_price[1]" required id="jo_sub_mater_price" onkeyup="prod_origin_price()" onblur="comma_input(this)" value="<?=$jo_sub_mater[1]['price'] ?  number_format($jo_sub_mater[1]['price']) : '' ?>"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <th>봉제공임*</th>
                                    <td>
                                        <input type="text" name="jo_bongje" id="jo_bongje" value="<?=$jo['jo_bongje'] ? number_format($jo['jo_bongje']) : ''?>" required onblur="comma_input(this)"  onkeyup="prod_origin_price()">
                                    </td>
                                    <th>주입비용</th>
                                    <td>
                                        <input type="text" name="jo_juip_price" id="jo_juip_price" onblur="comma_input(this)" value="<?=$jo['jo_juip_price'] ? number_format($jo['jo_juip_price']):''?>"  onkeyup="prod_origin_price()">
                                    </td>
                                </tr>
                                <tr>
                                    <th>포장비*</th>
                                    <td>
                                        <input type="text" name="jo_pack_price" id="jo_pack_price" onblur="comma_input(this)" value="<?=$jo['jo_pack_price'] ? number_format($jo['jo_pack_price']) : ''?>" required  onkeyup="prod_origin_price()">
                                    </td>
                                    <th>생산원가 V-*</th>
                                    <td>
                                        <input type="text" name="jo_prod_origin_price" id="jo_prod_origin_price" required  value="<?=number_format($jo['jo_prod_origin_price'])?>" onblur="comma_input(this)" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <th>생산관리비* </th>
                                    <td>
                                        <input type="text" name="jo_prod_control_price" id="jo_prod_control_price" required  onkeyup="prod_total_price()" value="<?=$jo['jo_prod_control_price']?>"> % <span id="jo_prod_control_price_view"></span>
                                    </td>
                                    <th>총 원가 V+*</th>
                                    <td>
                                        <input type="text" name="jo_total_origin_price" id="jo_total_origin_price" required  value="<?=number_format($jo['jo_total_origin_price'])?>" onblur="comma_input(this)" readonly>
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
                            <button type="submit" class="btn btn-success"  value="저장">저장</button>
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
    // 작성일
    function regDatePicker(){
        $('#regdatepicker').datetimepicker({
            ignoreReadonly: true,
            allowInputToggle: true,
            format: 'YYYY-MM-DD',
            locale: 'ko'
        });
    }

    // $('#regdatepicker').datetimepicker({
    //     ignoreReadonly: true,
    //     allowInputToggle: true,
    //     format: 'YYYY-MM-DD',
    //     locale: 'ko'
    // });

    $('#enddatepicker').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true,
        format: 'YYYY-MM-DD HH:mm',
        locale: 'ko'
    });

    $("#regdatepicker").on("dp.change", function(e) {
        $('#regdatepicker').data("DateTimePicker").minDate(e.date);
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
    function preview_Img(imgPath){
        $("#imgPath").attr('src' , imgPath);
        $("#imgStr").html(imgPath);

        $("#modal_preview_img").modal('show');
    }

    function func_checked(){
        if (!is_checked("function")) {
            $('.jo_mater_info_mater_price').attr('readonly',false);
            $("#jo_prod_origin_price").attr('readonly',false);
            $("#jo_total_origin_price").attr('readonly',false);
        }else{
            $('.jo_mater_info_mater_price').attr('readonly',true);
            $("#jo_prod_origin_price").attr('readonly',true);
            $("#jo_total_origin_price").attr('readonly',true);
        }
    }

    $("#jo_prod_type").on("change",function() {
        let type = $("#jo_prod_type").val();
        $("#jo_prod_name").removeClass();        

        var item1 = <?php echo json_encode($pumitem1)?>;
        var item2 = <?php echo json_encode($pumitem2)?>;
        // var item3 = <?php echo json_encode($pumitem3)?>;
        var item4 = <?php echo json_encode($pumitem4)?>;
        
      
        switch(type) {
            case '커버' :
                $("#jo_prod_name").addClass('pum1');
                break;
            case '기타' :
                $("#jo_prod_name").addClass('pum2');
                break;
            // case '소품액세서리' :
            //     $("#jo_prod_name").addClass('pum3');
            //     break;
            case '속통류' :
                $("#jo_prod_name").addClass('pum4');
                break;
        }
    });
    
    //소재
    $(".btn-add-soje").on("click", function() {
        let nextIdx = $("button.btn-add-soje").last().data("item-idx") * 1 + 1;
        let setHtml = '';
        
        setHtml += '<tr class="soje_' + nextIdx + '">';
        setHtml += '<th colspan="2"><input type="hidden" name="jo_soje_set"><input type="text" name="jo_soje_subject[' + nextIdx + ']" id="jo_soje_subject_' + nextIdx + '" value=""> <button type="button" class="btn-add-soje" onclick="del_soje(' + nextIdx + ')" data-item-idx=' + nextIdx + '>삭제</button></th>';
        //setHtml += '<td><input type="text" name="jo_soje_item[' + nextIdx + ']" id="jo_soje_set_item_' + nextIdx + '" value=""></td>';
        setHtml += '</tr>';


        $("#jo_soje_area").append(setHtml);
    });

    function del_soje(idx) {
        
        $(".soje_"+idx).remove();
    }

    //원자재정보
    $(".btn-add-mater").on("click", function() {
        let nextIdx = $("button.btn-add-mater").last().data("item-idx") * 1 + 1;
        let setHtml = '';
        setHtml += '<tr class="mater_' + nextIdx + '">';
        setHtml += '<th><input type="text" name="jo_mater_info[' + nextIdx + ']" style="width:100px;">* <button type="button" class="btn-add-mater" onclick="del_mater(' + nextIdx + ')" data-item-idx=' + nextIdx + '>삭제</button></th>';
        setHtml += '<td><table><colgroup><col width="30%" class=""><col width="70%"></colgroup>';
        setHtml += '<tr><th>폭</th><td><input name="jo_mater_info_wid[' + nextIdx + ']" id="jo_mater_info_wid_' + nextIdx + '" required data-mater-idx="' + nextIdx + '"   onblur="yochek_cal(this)" class="jo_mater_info_wid"></td></tr>';
        setHtml += '<tr><th>길이</th><td><input name="jo_mater_info_length[' + nextIdx + ']" id="jo_mater_info_length_' + nextIdx + '" required data-mater-idx="' + nextIdx + '"   onblur="yochek_cal(this)" class="jo_mater_info_length"></td></tr>';
        setHtml += '<tr><th>요척</th><td><input name="jo_mater_info_yochek[' + nextIdx + ']" id="jo_mater_info_yochek_' + nextIdx + '" required onblur="comma_input(this)" value="" data-mater-idx="' + nextIdx + '" onkeyup="press(this)"></td></tr>';
        setHtml += '<tr><th>자재단가 /yd, V-*</th><td><input name="jo_mater_info_mater_danga[' + nextIdx + ']" class="jo_mater_info_mater_danga" required id="jo_mater_info_mater_danga_' + nextIdx + '" onblur="comma_input(this)" value="" data-mater-idx="' + nextIdx + '" onkeyup="mater_math(this)"></td></tr>';
        setHtml += '<tr><th>원자재 금액 V-*</th><td><input name="jo_mater_info_mater_price[' + nextIdx + ']" class="jo_mater_info_mater_price" required id="jo_mater_info_mater_price_' + nextIdx + '" onblur="comma_input(this)" value="" readonly></td></tr>';
        setHtml += '</table></td></tr>';
        
        $("#jo_mater_info_area").append(setHtml);
    });

    function del_mater(idx) {
        $(".mater_"+idx).remove();
    }

    function prod_origin_price(){
        if (!is_checked("function")) {
            return false;
        }
        
        //원재재금액
        let a = $('#total_origin_mater_price').val().replace(/,/gi,'');
        //부자재
        let b = $('#jo_sub_mater_price').val().replace(/,/gi,'');
        //봉제공임
        let c = $('#jo_bongje').val().replace(/,/gi,'');
        //주입비용
        let d = $('#jo_juip_price').val().replace(/,/gi,'');
        //포장비
        let e = $('#jo_pack_price').val().replace(/,/gi,'');
        
        let origin_price = (a*1) + (b*1) + (c*1) + (d*1) + (e*1);

        origin_price = parseInt(origin_price * 100) / 100;
        $('#jo_prod_origin_price').val(comma(origin_price+""));

        prod_total_price();

    }

    function prod_total_price(){
        if (!is_checked("function")) {
            return false;
            
        }
        let oprice = $('#jo_prod_origin_price').val().replace(/,/gi,'');
        let rate = $('#jo_prod_control_price').val();

        let control_price = oprice * rate / 100 ;

        $('#jo_prod_control_price_view').empty().html(comma(control_price+"") + '원');

        let totalprice = ((oprice*1) + (control_price*1) ) * 1.1 ;
        totalprice = parseInt(totalprice * 100) / 100;

        $('#jo_total_origin_price').val(comma(totalprice+""));

    }
    
    function mater_math(elem){
        if (!is_checked("function")) {
            return false;
            
        }
        let id = $(elem).data("mater-idx");
        let yo = $('#jo_mater_info_yochek_'+id).val().replace(/,/gi,'');
        let danga = $('#jo_mater_info_mater_danga_'+id).val().replace(/,/gi,'');

        let price = yo * danga;
        price = parseInt(price * 100) / 100;
        $('#jo_mater_info_mater_price_'+id).val(comma(price+""));

        // $('input[name=jo_mater_info_mater_price]').val();
        // console.log($('input[name=jo_mater_info_mater_price]'));
        var fileValue = $(".jo_mater_info_mater_price").length;
        var fileData = new Array(fileValue);
        let total_mater_price = 0;
        for(var i=0; i<fileValue; i++){                          
            fileData[i] = $(".jo_mater_info_mater_price")[i].value.replace(/,/gi,'');
            total_mater_price += (fileData[i]*1);
        }
        total_mater_price = parseInt(total_mater_price * 100) / 100;
        $('#total_origin_mater_price').val(comma(total_mater_price+""));
        $('#total_origin_mater_price_view').empty().html(comma(total_mater_price+""));
        prod_origin_price();
    }

    function press(elem){
        if (!is_checked("function")) {
            return false;
        }
        var val = elem.value;
        var yochek = 0;
        var cck = 0;
        var _pat = /^\d*[.]\d{2}$/;
            
        if(val.indexOf(".") > -1){
            if(_pat.test(val)){
                yochek = Math.ceil(val * 10) / 10;
                
                $(elem).val(yochek);
            }
        }
        
        mater_math(elem);
    }

    
    function yochek_cal(elem){
        if (!is_checked("function")) {
            comma_input(elem);
            return false;
        }
        let id = $(elem).data("mater-idx");

        let wid = $('#jo_mater_info_wid_'+id).val().replace(/,/gi,'');
        let length = $('#jo_mater_info_length_'+id).val().replace(/,/gi,'');
        
        $('#jo_mater_info_wid_'+id).val(comma(wid+""));
        $('#jo_mater_info_length_'+id).val(comma(length+""));

        
        let price =(((wid*1) * (length*1)) / 91.44) * 1.02 ;
        price = parseInt(price * 100) / 100;
        price = Math.ceil(price * 10) / 10;

        $('#jo_mater_info_yochek_'+id).val(comma(price+""));

        mater_math(elem);

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

    $(function(){
        func_checked();
        prod_origin_price();
        prod_total_price();
    });



</script>

<script>
    $(function() {
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