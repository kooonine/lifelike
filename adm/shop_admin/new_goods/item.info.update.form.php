<?

$sub_menu = "930200";
include_once('./_common.php');
include_once('../../admin.head.php');
include_once(G5_LAYOUT_PATH . "/modal.php");

include_once(G5_EDITOR_PATH."/ckeditor4/editor.lib.php");



auth_check($auth[substr($sub_menu,0,2)], 'w');

$referer = $_SERVER["REQUEST_URI"];
$cut_url = explode("qstr=" , $referer);
$qstr=$cut_url[1];

if (!($w == '' || $w == 'u' || $w == 'r')) {
    alert('w 값이 제대로 넘어오지 않았습니다.');
}

$ps_id = $_GET['ps_id'];

$pi_id = $_GET['pi_id'];

$jo_id = $_GET['jo_id'];

$jo_sql = " select * from lt_job_order where jo_id = '$jo_id' ";
$jo = sql_fetch($jo_sql);

if ($w == '') {
    $title_msg = '작성';
    if ($cp_id) {
        alert('글쓰기에는 \$cp_id 값을 사용하지 않습니다.');
    }
} else if ($w == 'u') {
    $title_msg = '수정';
    $sql = " select * from lt_prod_info where ps_id = '$ps_id' AND pi_id ='$pi_id' ";
    $pi = sql_fetch($sql);
    if (!$pi['pi_id']) alert("등록된 자료가 없습니다.");

    $cp_banner_checked = array();
    foreach (explode(',', $cp['cp_banner']) as $cb) {
        $cp_banner_checked[$cb] = "checked";
    }
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

$allow_admin_sql = "select * from lt_admin where mb_id = '{$member['mb_id']}'";
$aas = sql_fetch($allow_admin_sql);


$ps_sql = " select * from lt_prod_schedule where ps_id = '$ps_id' ";
$ps = sql_fetch($ps_sql);


$cp_item_set = array();
if (!empty($cp['cp_item_set'])) {
    $cp_item_set = json_decode($cp['cp_item_set'], true);
}

$pi_images = array();
if (!empty($pi['pi_img'])) {
    $pi_images = json_decode($pi['pi_img'], true);
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


$g5['title'] = "상품정보집 " . $title_msg;


$action_url = https_url('adm') . "/shop_admin/new_goods/item.info.update.php";
$mid_cate = array(
    '차렵이불' => '차렵이불',
    '스프레드' => '스프레드',
    '겹이불' => '겹이불',
    '홑이불' => '홑이불',
    '이불커버' => '이불커버',
    '베개커버' => '베개커버',
    '매트커버' => '매트커버',
    '패드' => '패드',
    '요커버' => '요커버',
    '소품(쿠션/방석)커버' => '소품(쿠션/방석)커버',
    '세트류' => '세트류',
    '의류' => '의류',
    '기타' => '기타',
    '소품(쿠션/방석)속통' => '소품(쿠션/방석)속통',
    '블랭킷' => '블랭킷',
    '요솜' => '요솜',
    '이불솜' => '이불솜',
    '베개솜' => '베개솜',
    '토퍼(페더)베드' => '토퍼(페더)베드',
    '메모리폼 베개' => '메모리폼 베개'
);
$design_style = array(
    '베이직' => '베이직',
    '호텔베딩' => '호텔베딩',
    '모던' => '모던',
    '클래식' => '클래식',
    '에스닉' => '에스닉',
    '내추럴' => '내추럴',
    '로맨틱' => '로맨틱',
    '키즈' => '키즈',
    '기타' => '기타'
);
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
$list_banners = array(
    'ETC' => 'LIST 베너'
    
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

function color_table($text){
    if(preg_match("/[a-zA-Z]/",$text)){
        switch($text){
            case 'AA' : $color_nm = "기타"; break;
            case 'BE' : $color_nm = "베이지"; break;
            case 'BK' : $color_nm = "블랙"; break;
            case 'BL' : $color_nm = "블루"; break;
            case 'BR' : $color_nm = "브라운"; break;
            case 'CR' : $color_nm = "크림"; break;
            case 'DB' : $color_nm = "진블루"; break;
            case 'DP' : $color_nm = "진핑크"; break;
            case 'FC' : $color_nm = "푸시아"; break;
            case 'GD' : $color_nm = "골드"; break;
            case 'GN' : $color_nm = "그린"; break;
            case 'GR' : $color_nm = "그레이"; break;
            case 'IV' : $color_nm = "아이보리"; break;
            case 'KA' : $color_nm = "카키"; break;
            case 'LB' : $color_nm = "연블루"; break;
            case 'LG' : $color_nm = "연그레이"; break;
            case 'LP' : $color_nm = "연핑크"; break;
            case 'LV' : $color_nm = "라벤다"; break;
            case 'MT' : $color_nm = "민트"; break;
            case 'MU' : $color_nm = "멀티"; break;
            case 'MV' : $color_nm = "모브"; break;
            case 'MX' : $color_nm = "혼합"; break;
            case 'NC' : $color_nm = "내츄럴"; break;
            case 'NV' : $color_nm = "네이비"; break;
            case 'OR' : $color_nm = "오렌지"; break;
            case 'PC' : $color_nm = "청록"; break;
            case 'PK' : $color_nm = "핑크"; break;
            case 'PU' : $color_nm = "퍼플"; break;
            case 'RD' : $color_nm = "레드"; break;
            case 'WH' : $color_nm = "화이트"; break;
            case 'YE' : $color_nm = "노랑"; break;
            case 'DG' : $color_nm = "딥그레이"; break;
            case 'CO' : $color_nm = "코랄"; break;
        }
    }else{
        $color_nm = $text;
    }
    return $color_nm;
}


echo '<script src="https://lifelike.co.kr/plugin/editor/ckeditor4_old/ckeditor.js"></script>';
echo '<script>var g5_editor_url = "https://lifelike.co.kr/plugin/editor/ckeditor4_old";</script>';
echo '<script src="https://lifelike.co.kr/plugin/editor/ckeditor4_old/config.js"></script>';

$is_dhtml_editor = true;
$is_dhtml_editor_use = false;
$content1 = '';

// $content1 = get_text($pi['pi_detail_info'], 0);
// $content2 = get_text($pi['pi_selling1'], 0);
// $content3 = get_text($pi['pi_selling2'], 0);
// $content4 = get_text($pi['pi_selling3'], 0);
// $content5 = get_text($pi['pi_prod_info1'], 0);
// $content6 = get_text($pi['pi_prod_info2'], 0);
// $content7 = get_text($pi['pi_prod_info3'], 0);
// $content8 = get_text($pi['pi_prod_info4'], 0);
// $content9 = get_text($pi['pi_prod_info5'], 0);
// $content10 = get_text($pi['pi_prod_info6'], 0);
// $content11 = get_text($pi['pi_prod_info7'], 0);
// $content12 = get_text($pi['pi_prod_info8'], 0);
// $content13 = get_text($pi['pi_prod_info9'], 0);
// $content14 = get_text($pi['pi_prod_info10'], 0);

$edit_order_c = get_text($pi['edit_order_content'], 0);

// $editor_html = editor_html('pi_detail_info', $content1, $is_dhtml_editor);
// $editor_htmls1 = editor_html('pi_selling1', $content2, $is_dhtml_editor);
// $editor_htmls2 = editor_html('pi_selling2', $content3, $is_dhtml_editor);
// $editor_htmls3 = editor_html('pi_selling3', $content4, $is_dhtml_editor);
// $editor_htmli1 = editor_html('pi_prod_info1', $content5, $is_dhtml_editor);
// $editor_htmli2 = editor_html('pi_prod_info2', $content6, $is_dhtml_editor);
// $editor_htmli3 = editor_html('pi_prod_info3', $content7, $is_dhtml_editor);
// $editor_htmli4 = editor_html('pi_prod_info4', $content8, $is_dhtml_editor);
// $editor_htmli5 = editor_html('pi_prod_info5', $content9, $is_dhtml_editor);
// $editor_htmli6 = editor_html('pi_prod_info6', $content10, $is_dhtml_editor);
// $editor_htmli7 = editor_html('pi_prod_info7', $content11, $is_dhtml_editor);
// $editor_htmli8 = editor_html('pi_prod_info8', $content12, $is_dhtml_editor);
// $editor_htmli9 = editor_html('pi_prod_info9', $content13, $is_dhtml_editor);
// $editor_htmli10 = editor_html('pi_prod_info10', $content14, $is_dhtml_editor);

$edit_order_html = editor_html('edit_order_content', $edit_order_c, $is_dhtml_editor);

?>

<!-- @START@ 내용부분 시작 -->

<style>
    button.btn-add {
        visibility: hidden;
    }

    button.btn-add.first {
        visibility: visible;
    }


    .sub_stylei1,.sub_stylei2, .sub_stylei3, .sub_stylei4, .sub_stylei5,.sub_stylei6, .sub_stylei7, .sub_stylei8 ,.sub_stylei9 {display:none;}

    .sub_style1 > .sub_stylei1,.sub_style2 > .sub_stylei2, .sub_style3 > .sub_stylei3, .sub_style4 > .sub_stylei4,.sub_style5 > .sub_stylei5,.sub_style6 > .sub_stylei6, .sub_style7 > .sub_stylei7, .sub_style8 > .sub_stylei8, .sub_style9 > .sub_stylei9{display:block;}
    .top_btn{
        position: fixed;
        right: 1%;
        bottom: 7%;
        z-index: 1000;
        text-align : center;
        cursor:pointer;
    }
    
    
</style>
<script src="./dist/clipboard.min.js"></script>
<script src="./dist/clipboard.js"></script>
<a class="top_btn" href="#">▲ <br> TOP</a>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <form name="fwrite" id="fwrite" action="<?= $action_url ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="uid" value="<?= get_uniqid(); ?>">
                <input type="hidden" name="w" value="<?= $w ?>">
                <input type="hidden" name="ps_id" value="<?= $ps['ps_id'] ?>">
                <input type="hidden" name="pi_id" value="<?= $pi['pi_id'] ?>">
                <input type="hidden" name="pi_gumsu" value="<?= $pi['pi_gumsu'] ?>">
                <input type="hidden" name="pi_gumsu_sub" value="<?= $pi['pi_gumsu_sub'] ?>">
                <input type="hidden" name="token" value="<?= get_admin_token() ?>">

                <div class="x_title">
                    <h4><span class="fa fa-check-square"></span> 상품정보집<small></small></h4>
                        
                    <label class="nav navbar-right"></label>
                    <div class="clearfix"></div>
                </div>

                <div>
                <? 
                $pi_sql ="select * from lt_prod_info where ps_id ={$ps['ps_id']} and pi_size_name is not null ORDER BY jo_id ASC";
                $pi_result= sql_query($pi_sql);
                for ($api = 0; $pi_row = sql_fetch_array($pi_result); $api++) {
                    if($api  == 0 ){
                        $first_insert = $pi_row['jo_id'];
                    }
                ?>
                <button style="color:#000;" type="button" onclick="location.href='./item.info.update.form.php?w=u&amp;ps_id=<?php echo $ps['ps_id']; ?>&amp;pi_id=<?= $pi_row['pi_id'] ?>&amp;jo_id=<?= $pi_row['jo_id'] ?>&amp;qstr=<?= $qstr?>'"><?=$pi_row['pi_size_name']?></button>
                <?}?>

                </div>

                <div class="x_content">
                    <div class="tbl_frm01 tbl_wrap">
                        <table id="compaign-content-wrapper" class="ng_table">
                            <caption>기본정보
                            </caption>
                            <colgroup>
                                <col width="15%" class="">
                                <col width="35%">
                                <col width="15%" class="">
                                <col width="35%" >
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th class="design">중분류*</th>
                                    <td>
                                        <select name="pi_sub_category" id = "pi_sub_category"  onfocus="valiD(this)" required>
                                            <option value="">선택</option>
                                            <? foreach ($mid_cate as $mc => $m_category) : ?>
                                                <option value="<?= $mc ?>" <?= $pi['pi_sub_category'] == $mc ? "selected" : "" ?>><?= $m_category ?></option>
                                            <?php endforeach ?>
                                            
                                        </select>
                                    </td>
                                    <th class="design">스타일</th>
                                    <td colspan="3">
                                        <select name="pi_design_style" id = "pi_design_style">
                                            <option value="">선택</option>
                                            <? foreach ($design_style as $ds => $d_style) : ?>
                                                <option value="<?= $ds ?>" <?= $pi['pi_design_style'] == $ds ? "selected" : "" ?>><?= $d_style ?></option>
                                            <?php endforeach ?>
                                        </select>

                                        <select name="pi_design_style_sub" id = "pi_design_style_sub"  class="
                                        <? if ($pi['pi_design_style'] == '베이직'):?>
                                            sub_style1
                                        <? elseif ($pi['pi_design_style'] == '호텔베딩'):?>
                                            sub_style2
                                        <? elseif ($pi['pi_design_style'] == '모던'):?>
                                            sub_style3
                                        <? elseif ($pi['pi_design_style'] == '클래식'):?>
                                            sub_style4
                                        <? elseif ($pi['pi_design_style'] == '에스닉'):?>
                                            sub_style5
                                        <? elseif ($pi['pi_design_style'] == '내추럴'):?>
                                            sub_style6
                                        <? elseif ($pi['pi_design_style'] == '로맨틱'):?>
                                            sub_style7
                                        <? elseif ($pi['pi_design_style'] == '키즈'):?>
                                            sub_style8
                                        <? elseif ($pi['pi_design_style'] == '키타'):?>
                                            sub_style9
                                        <? endif ?>
                                        ">


                                        <option class="sub_stylei1" id="sub_stylei1_f" value="" <?= $pi['pi_design_style_sub'] == '' ? "selected" : "" ?>>선택</option>
                                        <option class="sub_stylei1" value="베이직" <?= $pi['pi_design_style_sub'] == '베이직' ? "selected" : "" ?>>베이직</option>
                                        <option class="sub_stylei1" value="소재물" <?= $pi['pi_design_style_sub'] == '소재물' ? "selected" : "" ?>>소재물</option>
                                        <option class="sub_stylei1" value="여름용" <?= $pi['pi_design_style_sub'] == '여름용' ? "selected" : "" ?>>여름용</option>

                                        <option class="sub_stylei2" value="호텔베딩" <?= $pi['pi_design_style_sub'] == '호텔베딩' ? "selected" : "" ?>>호텔베딩</option>

                                        <option class="sub_stylei3" value="" <?= $pi['pi_design_style_sub'] == '' ? "selected" : "" ?>>선택</option>
                                        <option class="sub_stylei3" value="모던_STRIPE" <?= $pi['pi_design_style_sub'] == '모던_STRIPE' ? "selected" : "" ?>>모던_STRIPE</option>
                                        <option class="sub_stylei3" value="모던_TEXTURE" <?= $pi['pi_design_style_sub'] == '모던_TEXTURE' ? "selected" : "" ?>>모던_TEXTURE</option>
                                        <option class="sub_stylei3" value="모던_PLAID" <?= $pi['pi_design_style_sub'] == '모던_PLAID' ? "selected" : "" ?>>모던_PLAID</option>
                                        <option class="sub_stylei3" value="모던_GEOMETRIC" <?= $pi['pi_design_style_sub'] == '모던_GEOMETRIC' ? "selected" : "" ?>>모던_GEOMETRIC</option>
                                        
                                        <option class="sub_stylei4" value="" <?= $pi['pi_design_style_sub'] == '' ? "selected" : "" ?>>선택</option>
                                        <option class="sub_stylei4" value="다마스크" <?= $pi['pi_design_style_sub'] == '다마스크' ? "selected" : "" ?>>다마스크</option>
                                        <option class="sub_stylei4" value="페이즐리" <?= $pi['pi_design_style_sub'] == '페이즐리' ? "selected" : "" ?>>페이즐리</option>
                                        <option class="sub_stylei4" value="사라사" <?= $pi['pi_design_style_sub'] == '사라사' ? "selected" : "" ?>>사라사</option>

                                        <option class="sub_stylei5" value="" <?= $pi['pi_design_style_sub'] == '' ? "selected" : "" ?>>선택</option>
                                        <option class="sub_stylei5" value="페이즐리" <?= $pi['pi_design_style_sub'] == '페이즐리' ? "selected" : "" ?>>페이즐리</option>
                                        <option class="sub_stylei5" value="사라사" <?= $pi['pi_design_style_sub'] == '사라사' ? "selected" : "" ?>>사라사</option>

                                        <option class="sub_stylei6" value="내추럴" <?= $pi['pi_design_style_sub'] == '내추럴' ? "selected" : "" ?>>내추럴</option>

                                        <option class="sub_stylei7" value="" <?= $pi['pi_design_style_sub'] == '' ? "selected" : "" ?>>선택</option>
                                        <option class="sub_stylei7" value="미니플로럴" <?= $pi['pi_design_style_sub'] == '미니플로럴' ? "selected" : "" ?>>미니플로럴</option>
                                        <option class="sub_stylei7" value="맥스플로럴" <?= $pi['pi_design_style_sub'] == '맥스플로럴' ? "selected" : "" ?>>맥스플로럴</option>

                                        <option class="sub_stylei8" value="키즈" <?= $pi['pi_design_style_sub'] == '키즈' ? "selected" : "" ?>>키즈</option>

                                        <option class="sub_stylei9" value="기타" <?= $pi['pi_design_style_sub'] == '기타' ? "selected" : "" ?>>기타</option>
                                        
                                        </select>

                                       
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">재고연령</th>
                                    <?
                                    $psc_sql ="SELECT * FROM lt_prod_schedule WHERE ps_origin_ps_id = {$ps['ps_id']} ORDER BY ps_reorder_id DESC LIMIT 1";

                                    //$psc_result= sql_query($psc_sql);
                                    $psc_data = sql_fetch($psc_sql);

                                    $runingTime = date("Y-m-d" , strtotime($psc_data['ps_ipgo_date']."+1 year") );

                                    $r = strtotime($runingTime) - strtotime($psc_data['ps_ipgo_date']) ;
                                    $r = ceil($r / (60*60 *24   )) ;
                                    ?>
                                    <td>
                                        <input class="input_wid_100" style="background: #e5e5e5;border: 0px;" name="pi_jego_age" id = "pi_jego_age" value="<?=$psc_data['ps_ipgo_date'] ? $psc_data['ps_ipgo_date'] : '생산일정에 입력바랍니다.' ?>" readonly >
                                    </td>
                                    <th class="design">러닝/아웃*</th>
                                    <td>
                                        <input class="input_wid_100" style="background: #e5e5e5;border: 0px;" type="text" name="pi_running_out" id = "pi_running_out" value="<?= $r >= 0 ? ( $psc_data['ps_ipgo_date'] ? '러닝' : '아웃') : '아웃'?>" readonly required>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">시즌*</th>
                                    <td>
                                        <select name="pi_season" id="pi_season" onfocus="valiD(this)" required>
                                            <option value="" <?= $pi['pi_season'] == '' ? "selected" : "" ?>>선택</option>
                                            <option value="SS" <?= $pi['pi_season'] == 'SS' ? "selected" : "" ?>>SS</option>
                                            <option value="HS" <?= $pi['pi_season'] == 'HS' ? "selected" : "" ?>>HS</option>
                                            <option value="FW" <?= $pi['pi_season'] == 'FW' ? "selected" : "" ?>>FW</option>
                                            <option value="AA" <?= $pi['pi_season'] == 'AA' ? "selected" : "" ?>>AA</option>
                                        </select>
                                    </td>
                                    <th class="pmd">상품명(사방넷상품명)*</th>
                                    <td>
                                        <?
                                            if(!empty($pi['pi_it_name'])){
                                                $c_pi_it_name = $pi['pi_it_name'];

                                            }else{
                                                if(!empty($pi['pi_brand']) && !empty($jo['jo_it_name']) && !empty($jo['jo_prod_name']) && (!empty($pi['pi_cisu']) || !empty($pi['pi_size']) ) && !empty($pi['pi_color']) ){
                                                    $c_pi_it_name = "[".$pi['pi_brand']."]";
                                                    $name_array = explode('(', $jo['jo_it_name']);
                                                    $c_pi_it_name .= " ".$name_array[0];
                                                    $c_pi_it_name .= " ".$jo['jo_prod_name'];
                                                    if(strpos($ps['ps_prod_name'] , "베개커버") === false ){
                                                        switch($pi['pi_size']){
                                                            case 'S':
                                                                $size_cisu = "싱글사이즈";
                                                                break;
                                                            case 'Q':
                                                                $size_cisu = "퀸사이즈";
                                                                break;
                                                            case 'K':
                                                                $size_cisu = "킹사이즈";
                                                                break;
                                                            case 'SS':
                                                                $size_cisu = "슈퍼싱글사이즈";
                                                                break;
                                                            default : 
                                                                $size_cisu = "";
                                                                break;
                                                        }
                                                    }else{
                                                        $size_cisu = str_replace('*','X' , $pi['pi_cisu']);
                                                    }
                                                    $c_pi_it_name .= " ".$size_cisu;
                                                    $c_pi_it_name .= "(".color_table($pi['pi_color']).")";
                                                }
                                            }
                                        ?>
                                        <input class="input_wid_100" name="pi_it_name" id="pi_it_name" value="<?=$c_pi_it_name?>" onfocus="valiDMD(this)" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pmd">상품약어(삼진상품명)*</th>
                                    <td >
                                        <input class="input_wid_100" name="pi_it_sub_name" id="pi_it_sub_name" value="<?=$jo['jo_it_name'].$jo['jo_prod_name'] ?>" onfocus="valiPMD(this)" required>
                                    </td>
                                    <th class="pmd">모델명(삼진코드)*</th>
                                    <td >
                                        <input class="input_wid_100" name="pi_model_name" id="pi_model_name" value="<?=$pi['pi_model_name']?>" onfocus="valiPMD(this)" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pmd">모델No(SAP코드)* </th>
                                    <td>
                                        <input class="input_wid_100" name="pi_model_no" id="pi_model_no" value="<?=$pi['pi_model_no']?>" onfocus="valiPMD(this)" required>
                                    </td>
                                    <th class="pmd">자체상품코드(SAP색상사이즈)* </th>
                                    <td>
                                        <?
                                        if(!empty($pi['pi_company_it_id'])){
                                            $c_pi_company_it_id = $pi['pi_company_it_id'];
                                        }else{
                                            if(!empty($pi['pi_model_no']) && !empty($jo['jo_it_name']) && (!empty($pi['pi_cisu']) || !empty($pi['pi_size']))){
                                                $c_pi_company_it_id = $pi['pi_model_no'];
                                                $name_array = explode('(', $jo['jo_it_name']);
                                                $c_pi_company_it_id .= iconv_substr($name_array[1] ,0, 2);
                                                if(strpos($ps['ps_prod_name'] , "베개커버") === false ){
                                                    $size_cisu_c = $pi['pi_size'];
                                                }else{
                                                    $size_cisu_c = str_replace('*','X' , $pi['pi_cisu']);
                                                }
                                                $c_pi_company_it_id .= $size_cisu_c;
                                            }
                                        }
                                        ?>
                                        <input class="input_wid_100" name="pi_company_it_id" id="pi_company_it_id" value="<?=$c_pi_company_it_id?>" onfocus="valiPMD(this)" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">브랜드명*</th>
                                    <td>
                                        <select name="pi_brand" id="pi_brand" onfocus="valiD(this)" required>
                                            <option value="">선택</option>
                                            <? foreach ($brands as $ck => $brand) : ?>
                                                <option value="<?= $ck ?>" <?= $pi['pi_brand'] == $ck ? "selected" : "" ?>><?= $brand ?></option>
                                            <?php endforeach ?>
                                            
                                        </select>
                                    </td>
                                    <th class="design">카테고리</th>
                                    <td>
                                        <input class="input_wid_100"  name="pi_category" id="pi_category" value="침구"> 
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">충전재*</th>
                                    <td>
                                        <select name="pi_charge" id="pi_charge" onfocus="valiD(this)"  required>
                                            <option value="" <?= $pi['pi_charge'] ? "selected" : "" ?>>선택</option>
                                            <option value="구스" <?= $pi['pi_charge'] == '구스' ? "selected" : "" ?>>구스</option>
                                            <option value="폴리" <?= $pi['pi_charge'] == '폴리' ? "selected" : "" ?>>폴리</option>
                                            <option value="덕다운" <?= $pi['pi_charge'] == '덕다운' ? "selected" : "" ?>>덕다운</option>
                                            <option value="메모리폼" <?= $pi['pi_charge'] == '메모리폼' ? "selected" : "" ?>>메모리폼</option>
                                            <option value="양모" <?= $pi['pi_charge'] == '양모' ? "selected" : "" ?>>양모</option>
                                            <option value="없음" <?= $pi['pi_charge'] == '없음' ? "selected" : "" ?>>없음</option>
                                        </select>
                                    </td>
                                    <th class="charge_chk design">충전재(원산지)*</th>
                                    <td class="charge_chk">
                                        <!-- <input class="input_wid_100" name="pi_mater" id="pi_mater" value="<?=$pi['pi_mater']?>"  onfocus="valiD(this)"> -->
                                        <select name="pi_charge_mater" id="pi_charge_mater" onfocus="valiD(this)"  >
                                            <option value="" <?= $pi['pi_charge_mater'] == '' ? "selected" : "" ?>>선택</option>
                                            <option value="폴란드" <?= $pi['pi_charge_mater'] == '폴란드' ? "selected" : "" ?>>폴란드</option>
                                            <option value="시베리아" <?= $pi['pi_charge_mater'] == '시베리아' ? "selected" : "" ?>>시베리아</option>
                                            <option value="헝가리" <?= $pi['pi_charge_mater'] == '헝가리' ? "selected" : "" ?>>헝가리</option>
                                            <option value="중국" <?= $pi['pi_charge_mater'] == '중국' ? "selected" : "" ?>>중국</option>
                                            <option value="중국RDS" <?= $pi['pi_charge_mater'] == '중국RDS' ? "selected" : "" ?>>중국RDS</option>
                                            <option value="캐나다" <?= $pi['pi_charge_mater'] == '캐나다' ? "selected" : "" ?>>캐나다</option>
                                            <option value="프랑스" <?= $pi['pi_charge_mater'] == '프랑스' ? "selected" : "" ?>>프랑스</option>
                                            <option value="대만" <?= $pi['pi_charge_mater'] == '대만' ? "selected" : "" ?>>대만</option>
                                            <option value="유러피안" <?= $pi['pi_charge_mater'] == '유러피안' ? "selected" : "" ?>>유러피안</option>
                                            <option value="direct" <?= $pi['pi_charge_mater'] == 'direct' ? "selected" : "" ?>>기타</option>
                                        </select>

                                        <input class="input_wid_100" type="text" id="pi_charge_mater_etc" name="pi_charge_mater_etc" value="<?= $pi['pi_charge_mater_etc']?>"/>
                                    </td>
                                    <!-- <th class="design">생산연도*</th>
                                    <td>
                                        <select name="pi_prod_date" id="pi_prod_date" onfocus="valiD(this)"  required>
                                            <option value="" <?= $pi['pi_year'] == '' ? "selected" : "" ?>>선택</option>
                                            <?for($yyi = 0; $yyi < 51; $yyi++){?>
                                            <option value="<?=($yyi+2000)?>" <?= $pi['pi_prod_date'] == ($yyi+2000) ? "selected" : "" ?>><?=($yyi+2000)?>년</option>
                                            <?}?>
                                        </select>
                                    </td> -->
                                </tr>
                                <tr class="charge_chk">
                                    <th class="design">충전재(브랜드)*</th>
                                    <td>
                                        <select name="pi_charge_brand" id="pi_charge_brand" onfocus="valiD(this)"  >
                                            <option value="" <?= $pi['pi_charge_brand'] =='' ? "selected" : "" ?>>선택</option>
                                            <option value="프라우덴" <?= $pi['pi_charge_brand'] =='프라우덴' ? "selected" : "" ?>>프라우덴</option>
                                            <option value="direct" <?= $pi['pi_charge_brand'] == 'direct' ? "selected" : "" ?>>기타</option>
                                        </select>

                                        <input class="input_wid_100" type="text" id="pi_charge_brand_etc" name="pi_charge_brand_etc" value="<?= $pi['pi_charge_brand_etc']?>"/>
                                    </td>
                                    <th class="design">충전재(함량)*</th>
                                    <td>
                                        <select name="pi_charge_weight" id="pi_charge_weight"  onfocus="valiD(this)"  >
                                            <option value="" <?= $pi['pi_charge_weight'] =='' ? "selected" : "" ?>>선택</option>
                                            <option value="95" <?= $pi['pi_charge_weight'] =='95' ? "selected" : "" ?>>95%</option>
                                            <option value="90" <?= $pi['pi_charge_weight'] == '90' ? "selected" : "" ?>>90%</option>
                                            <option value="85" <?= $pi['pi_charge_weight'] == '85' ? "selected" : "" ?>>85%</option>
                                            <option value="80" <?= $pi['pi_charge_weight'] == '80' ? "selected" : "" ?>>80%</option>
                                            <option value="75" <?= $pi['pi_charge_weight'] == '75' ? "selected" : "" ?>>75%</option>
                                            <option value="70" <?= $pi['pi_charge_weight'] == '70' ? "selected" : "" ?>>70%</option>
                                            <option value="65" <?= $pi['pi_charge_weight'] == '65' ? "selected" : "" ?>>65%</option>
                                            <option value="60" <?= $pi['pi_charge_weight'] == '60' ? "selected" : "" ?>>60%</option>
                                            <option value="55" <?= $pi['pi_charge_weight'] == '55' ? "selected" : "" ?>>55%</option>
                                            <option value="direct" <?= $pi['pi_charge_weight'] == 'direct' ? "selected" : "" ?>>기타</option>
                                        </select>

                                        <input class="input_wid_100" type="text" id="pi_charge_weight_etc" name="pi_charge_weight_etc" value="<?= $pi['pi_charge_weight_etc']?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="charge_chk design">필파워</th>
                                    <td class="charge_chk">
                                        <!-- <input class="input_wid_100" name="pi_pilpower" id="pi_pilpower" value="<?=$pi['pi_pilpower']?>" onfocus="valiD(this)"> -->
                                        <select name="pi_pilpower" id="pi_pilpower" onfocus="valiD(this)" >
                                            <option value="" <?= $pi['pi_pilpower'] =='' ? "selected" : "" ?>>선택</option>
                                            <option value="900" <?= $pi['pi_pilpower'] =='900' ? "selected" : "" ?>>900이상</option>
                                            <option value="900850" <?= $pi['pi_pilpower'] == '900850' ? "selected" : "" ?>>900~850</option>
                                            <option value="850800" <?= $pi['pi_pilpower'] == '850800' ? "selected" : "" ?>>850~800</option>
                                            <option value="800750" <?= $pi['pi_pilpower'] == '800750' ? "selected" : "" ?>>800~750</option>
                                            <option value="750700" <?= $pi['pi_pilpower'] == '750700' ? "selected" : "" ?>>750~700</option>
                                            <option value="700650" <?= $pi['pi_pilpower'] == '700650' ? "selected" : "" ?>>700~650</option>
                                            <option value="650600" <?= $pi['pi_pilpower'] == '650600' ? "selected" : "" ?>>650~600</option>
                                            <option value="600550" <?= $pi['pi_pilpower'] == '600550' ? "selected" : "" ?>>600~550</option>
                                            <option value="550500" <?= $pi['pi_pilpower'] == '550500' ? "selected" : "" ?>>550~500</option>
                                            <option value="550500" <?= $pi['pi_pilpower'] == '550500' ? "selected" : "" ?>>550~500</option>
                                            <option value="none" <?= $pi['pi_pilpower'] == 'none' ? "selected" : "" ?>>해당없음</option>
                                        </select>
                                    </td>
                                    <th class="design">필파워 인증서 유/무</th>
                                    <td>
                                        <select name="pi_pilpower_safe_yn" id="pi_pilpower_safe_yn" onfocus="valiD(this)" >
                                            <option value="" <?= $pi['pi_pilpower_safe_yn'] ? "selected" : "" ?>>선택</option>
                                            <option value="무" <?= $pi['pi_pilpower_safe_yn'] == '무' ? "selected" : "" ?>>무</option>
                                            <option value="유" <?= $pi['pi_pilpower_safe_yn'] == '유' ? "selected" : "" ?>>유</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">남녀구분*</th>
                                    <td>
                                        <select name="pi_age_gubun" id="pi_age_gubun" onfocus="valiD(this)" required>
                                            <option value="공용" <?= $pi['pi_age_gubun'] == '공용' ? "selected" : "" ?>>공용</option>
                                            <option value="여자" <?= $pi['pi_age_gubun'] == '여자' ? "selected" : "" ?>>여자</option>
                                            <option value="남자" <?= $pi['pi_age_gubun'] == '남자' ? "selected" : "" ?>>남자</option>
                                            <option value="키즈" <?= $pi['pi_age_gubun'] == '키즈' ? "selected" : "" ?>>키즈</option>
                                        </select>
                                    </td>
                                    <th class="pmd">배송비</th>
                                    <td>
                                        <select name="pi_delivery_price" id="pi_delivery_price" onfocus="valiPMD(this)">
                                            <option value="2300" <?= $pi['pi_delivery_price'] == '2300' ? "selected" : "" ?>>2,300</option>
                                            <option value="3500" <?= $pi['pi_delivery_price'] == '3500' ? "selected" : "" ?>>3,500</option>
                                            <option value="8000" <?= $pi['pi_delivery_price'] == '8000' ? "selected" : "" ?>>8,000</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pmd">1차판매가*</th>
                                    <td>
                                        <input class="input_wid_100" name="pi_sale_price" id="pi_sale_price" value="<?=$pi['pi_sale_price'] ? $pi['pi_sale_price'] : floor($pi['pi_tag_price'] / 2)?>" onfocus="valiPMD(this)" required>
                                    </td>
                                    <th class="design">원가*</th>
                                    <td>
                                        <input class="input_wid_100" style="background: #e5e5e5;border: 0px;" name="pi_origin_price" id="pi_origin_price" value="<?=$jo['jo_total_origin_price']?>"  readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pmd">2차판매가*</th>
                                    <td>
                                        <input class="input_wid_100" name="pi_sale_price2" id="pi_sale_price2" value="<?=$pi['pi_sale_price2']?>" onfocus="valiPMD(this)" required>
                                    </td>
                                    <th class="design">TAG가*</th>
                                    <td>
                                        <input class="input_wid_100" name="pi_tag_price" id="pi_tag_price" value="<?=$pi['pi_tag_price']?>" onfocus="valiD(this)" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">제품소재* <span style="" onclick="tooltib_soje()"><img style="width: 20px;height: 20px;" src="/img/re/icon_tooltip.png"></span></th>
                                    <td>
                                        <input class="input_wid_100" name="pi_item_soje" id="pi_item_soje" value="<?=$pi['pi_item_soje']?>" onfocus="valiD(this)" required>
                                    </td>
                                    <th class="design">색상* <br><p style="font-size : 9px;">한글로 작성</p></th>
                                    <td>
                                        <input class="input_wid_100" name="pi_color" id="pi_color" value="<?=color_table($pi['pi_color'])?>" onfocus="valiD(this)" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">충전재세부</th>
                                    <td>
                                        <input class="input_wid_100" name="pi_item_soje_detail" id="pi_item_soje_detail" value="<?=$pi['pi_item_soje_detail']?>" >
                                    </td>
                                    
                                </tr>
                                <tr>
                                    <th class="design">사이즈(코드)*</th>
                                    <td>
                                        <input class="input_wid_100" style="background: #e5e5e5;border: 0px;" name="pi_size" id="pi_size" value="<?=$jo['jo_size_code']?>" readonly>
                                    </td>
                                    <th class="design">치수*</th>
                                    <td>
                                        <input class="input_wid_100" name="pi_cisu" id="pi_cisu" value="<?=$pi['pi_cisu']?>" onfocus="valiD(this)" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">제조국*</th>
                                    <td>
                                        <input class="input_wid_100" name="pi_maker" id="pi_maker" value="<?=$pi['pi_maker']?>" onfocus="valiD(this)" required>
                                    </td>
                                    <th class="design">세탁방법*</th>
                                    <td>
                                        <textarea name="pi_laundry" id="pi_laundry" value="<?=$pi['pi_laundry']?>" onfocus="valiD(this)"  required><?=$pi['pi_laundry'] ? $pi['pi_laundry'] : '본 제품에 부착되어 있는 제품특성 및 사용방법&#13;&#10;(세탁방법)을 반드시 확인해 주시기 바랍니다.' ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">KC안전인증 대상유/무</th>
                                    <td>
                                        <select name="pi_kc_safe_yn" id="pi_kc_safe_yn" onfocus="valiD(this)">
                                            <option value="유" <?= $pi['pi_kc_safe_yn'] == '유' ? "selected" : "" ?>>유</option>
                                            <option value="무" <?= $pi['pi_kc_safe_yn'] == '무' ? "selected" : "" ?>>무</option>
                                        </select>
                                    </td>
                                    <th class="design">수입여부</th>
                                    <td>
                                        <select name="pi_soip_yn" id="pi_soip_yn"  onfocus="valiD(this)">
                                            <option value="N" <?= $pi['pi_soip_yn'] == 'N' ? "selected" : "" ?>>N</option>
                                            <option value="Y" <?= $pi['pi_soip_yn'] == 'Y' ? "selected" : "" ?>>Y</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">상품무게(충전물중량)<span style="" onclick="tooltib_prod_weight()"><img style="width: 20px;height: 20px;" src="/img/re/icon_tooltip.png"></span></th>
                                    <td>
                                        <input type="text" class="input_wid_100 no_text" onkeyup="notext(this)" name="pi_prod_weight" id="pi_prod_weight" value="<?=$pi['pi_prod_weight']?>" >
                                    </td>
                                    <!-- <th class="design">상품 가로_세로_높이*</th>
                                    <td>
                                        <input class="input_wid_100" name="pi_xyz" id="pi_xyz" value="<?=$pi['pi_xyz']?>" onfocus="valiD(this)" required>
                                    </td> -->
                                </tr>
                                <tr>
                                    <!-- <th class="design">충전재*</th>
                                    <td>
                                        <select name="pi_charge" id="pi_charge"  onfocus="valiD(this)" required>
                                            <option value="" <?= $pi['pi_charge'] ? "selected" : "" ?>>선택</option>
                                            <option value="구스" <?= $pi['pi_charge'] == '구스' ? "selected" : "" ?>>구스</option>
                                            <option value="폴리" <?= $pi['pi_charge'] == '폴리' ? "selected" : "" ?>>폴리</option>
                                            <option value="덕다운" <?= $pi['pi_charge'] == '덕다운' ? "selected" : "" ?>>덕다운</option>
                                            <option value="메모리폼" <?= $pi['pi_charge'] == '메모리폼' ? "selected" : "" ?>>메모리폼</option>
                                            <option value="없음" <?= $pi['pi_charge'] == '없음' ? "selected" : "" ?>>없음</option>
                                        </select>
                                    </td> -->
                                    <th class="design">자사몰스타일</th>
                                    <td>
                                        <select name="pi_ll_style" id="pi_ll_style" onfocus="valiD(this)">
                                            <option value="" <?= $pi['pi_ll_style'] ? "selected" : "" ?>>선택</option>
                                            <option value="플레인" <?= $pi['pi_ll_style'] == '플레인' ? "selected" : "" ?>>플레인</option>
                                            <option value="패턴" <?= $pi['pi_ll_style'] == '패턴' ? "selected" : "" ?>>패턴</option>
                                            <option value="아트" <?= $pi['pi_ll_style'] == '아트' ? "selected" : "" ?>>아트</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">프라우덴 우모사용 유/무</th>
                                    <td>
                                        <select name="pi_prauden_umu_yn" id="pi_prauden_umu_yn" onfocus="valiD(this)">
                                            <option value="" <?= $pi['pi_prauden_umu_yn'] ? "selected" : "" ?>>선택</option>
                                            <option value="무" <?= $pi['pi_prauden_umu_yn'] == '무' ? "selected" : "" ?>>무</option>
                                            <option value="유" <?= $pi['pi_prauden_umu_yn'] == '유' ? "selected" : "" ?>>유</option>
                                        </select>
                                    </td>
                                    <th class="design">항균가공 정보</th>
                                    <td>
                                        <select name="pi_hangkun_info" id="pi_hangkun_info" onfocus="valiD(this)">
                                            <option value="알러지세이퍼(항균/알러지)" <?= $pi['pi_hangkun_info'] =='알러지세이퍼(항균/알러지)' ? "selected" : "" ?>>알러지세이퍼(항균/알러지)</option>
                                            <option value="새니타이즈" <?= $pi['pi_hangkun_info'] == '새니타이즈' ? "selected" : "" ?>>새니타이즈</option>
                                            <option value="일반항균" <?= $pi['pi_hangkun_info'] == '일반항균' ? "selected" : "" ?>>일반항균</option>
                                            <option value="해당사항없음" <?= $pi['pi_hangkun_info'] == '해당사항없음' ? "selected" : "" ?>>해당사항없음</option>
                                            <option value="direct" <?= $pi['pi_hangkun_info'] == 'direct' ? "selected" : "" ?>>직접입력</option>
                                        </select>
                                        
                                        <input class="input_wid_100" type="text" id="selboxDirect" name="pi_hangkun_info_txt" value="<?= $pi['pi_hangkun_info_txt']?>"/>
                                        
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th class="design">원단상세정보 1-기업정보</th>
                                    <td>
                                        <input class="input_wid_100" name="pi_info1" id="pi_info1" value="<?=$pi['pi_info1']?>" onfocus="valiD(this)" >
                                    </td>
                                    <th class="design">원단상세정보 2<br>-시험성적서 유/무*</th>
                                    <td>
                                        <select name="pi_info2" id="pi_info2" onfocus="valiD(this)">
                                            <option value="" <?= $pi['pi_info2'] ? "selected" : "" ?>>선택</option>
                                            <option value="무" <?= $pi['pi_info2'] == '무' ? "selected" : "" ?>>무</option>
                                            <option value="유" <?= $pi['pi_info2'] == '유' ? "selected" : "" ?>>유</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">원단상세정보 2-1*</th>
                                    <td>
                                        <input class="input_wid_100" name="pi_info2_1" id="pi_info2_1" value="<?=$pi['pi_info2_1'] ? $pi['pi_info2_1'] : '폼알데하이드 / AP2아릴아민 / PH 산성' ?>" onfocus="valiD(this)" required>
                                    </td>
                                    <th class="design">원단상세정보 3<br>-OEKO-TEX 인증 유/무*</th>
                                    <td>
                                        <select name="pi_info3" id="pi_info3" onfocus="valiD(this)">
                                            <option value="유" <?= $pi['pi_info3'] == '유' ? "selected" : "" ?>>유</option>
                                            <option value="무" <?= $pi['pi_info3'] == '무' ? "selected" : "" ?>>무</option>
                                        </select>
                                    </td>
                                    <!-- <th class="design">담당자</th>
                                    <td>
                                        <input class="input_wid_100" name="pi_manager" id="pi_manager" value="<?=$pi['pi_manager']?>" onfocus="valiD(this)">
                                    </td> -->
                                </tr>
                                <!-- <tr class="pi_img_total">
                                    <th class="pd">검수요청용 상세기술서 경로*</th>
                                    <td colspan="3">
                                        <input class="input_wid_50" type="text" name="pi_img_total" id="pi_img_total" value="<?=$pi['pi_img_total']?>" onfocus="valiPD(this)">
                                    </td>
                                </tr> -->

                                <tbody id="pi_img_area">
                                <?if (!empty($pi_images)) :?>
                                <?$last = count($pi_images)?>
                                <tr>
                                    <th class="pma">대표이미지</th>
                                    <td>
                                            <?
                                                $ps_prod_main_imgs_set = array();
                                                if (!empty($ps['ps_prod_main_imgs'])) {
                                                    $ps_prod_main_imgs_set = json_decode($ps['ps_prod_main_imgs'], true);
                                                }
                                            ?>
                                            <div class="prod_main_img_area" onclick="main_preview_Imgs('<?=$ps['ps_id']?>')" id="prod_main_img_area_<?=$ps['ps_id']?>" data-prod-img= "<?=$ps['ps_id']?>" >
                                                
                                                <?if($ps_prod_main_imgs_set[0]['img']):?>
                                                    <?php foreach ($ps_prod_main_imgs_set as $psmi => $main_imgs) : ?>
                                                        <input type="hidden" class="group_prod_main_img_<?=$ps['ps_id']?>" data-imgs-idx ="<?=$psmi?>"  id="arr_prod_main_img_<?=$psmi?>"  value="<?=$main_imgs['img']?>"> 
                                                    <?php endforeach ?>

                                                <?endif?>
                                                <img style="margin: 0 auto; display: block;" class="prod_main_pf_foto_img prod_main_pf_foto_img_<?=$ps['ps_id']?>" <?if($ps_prod_main_imgs_set[0]['img']):?> src="<?=G5_URL?>/data/new_goods/<?=$ps_prod_main_imgs_set[0]['img']?>" <?endif?>>
                                            </div>  
                                    
                                    </td>
                                </tr>

                                <tr>
                                    <th class="pma">상품썸네일 이미지(대표이미지)</th>
                                    <td>
                                        <?
                                        if($pi['pi_brand'] == '템퍼'){
                                            $brand_img_path = 'tempur';
                                        }else if ($pi['pi_brand'] == '쉐르단'){
                                            $brand_img_path = 'sheridan';
                                        }else{
                                            $brand_img_path = 'sofraum';
                                        }

                                        if($pi['pi_brand'] == '템퍼'){
                                            if(file("https://lifelikecdn.co.kr/sabang/".$brand_img_path."/".$pi['pi_model_no']."_THUM_1.jpg")){
                                                $THUM1 .= "https://lifelikecdn.co.kr/sabang/".$brand_img_path."/".$pi['pi_model_no']."_THUM_1.jpg";
                                            }
                                        }else if ($pi['pi_brand'] == '쉐르단'){
                                            if(file("https://lifelikecdn.co.kr/sabang/".$brand_img_path."/".$pi['pi_model_no']."_THUM_1.jpg")){
                                                $THUM1 .= "https://lifelikecdn.co.kr/sabang/".$brand_img_path."/".$pi['pi_model_no']."_THUM_1.jpg";
                                            }
                                        }else{
                                            if(file("https://lifelikecdn.co.kr/sabang/".$brand_img_path."/".$pi['pi_model_no']."_THUM_1.jpg")){
                                                $THUM1 .= "https://lifelikecdn.co.kr/sabang/".$brand_img_path."/".$pi['pi_model_no']."_THUM_1.jpg";
                                            }
                                        }
                                        ?>
                                        <input class="input_wid_100" name="thum_img1" id="thum_img1" value="<?=$THUM1?>" >
                                    </td>
                                </tr>
                                
                                <?php foreach ($pi_images as $pii => $pi_image) : ?>
                                    <tr class="pi_img_<?=$pii?>">
                                        <th class="pd">상품기술서경로<?=$pii?>*
                                        <?if($pii == 1) : ?>
                                        <button type="button" class="btn-add-images <?= $first_item !== false ? "first" : "" ?>" data-item-idx=<?=$last?>>추가</button>
                                        <button class="btn btn_01" type="button" id="btn_preview_img" onclick="preview_Imgs()">미리보기</button>
                                        <button class="btn btn_01" type="button" id="copy_full_imgs" data-clipboard-text="" onclick="Imgs_copy_html()">소스복사</button>
                                        <?endif?>
                                        </th>
                                        <td colspan="3">
                                            <input class="input_wid_100" type="text" name="pi_img[<?=$pii?>]" id="pi_img_<?=$pii?>"   value="<?=$pi_image['img']?>">
                                        </td>
                                    </tr>

                                <?php endforeach ?>
                                <?endif?>
                                <?php if (empty($pi_images)) : ?>
                                    <tr class="pi_img_1">
                                        <th class="pd">상품기술서경로1*
                                        <button type="button" class="btn-add-images <?= $first_item !== false ? "first" : "" ?>" data-item-idx=4>추가</button>
                                        <button class="btn btn_01" type="button" id="btn_preview_img" onclick="preview_Imgs()">미리보기</button>
                                        </th>
                                        <td colspan="3">
                                            <input class="input_wid_50" type="text" name="pi_img[1]" id="pi_img_1" value="<?=$pi['pi_img_1']?>" >
                                        </td>
                                    </tr>
                                    <tr class="pi_img_2">
                                        <th class="pd">상품기술서경로2*</th>
                                        <td colspan="3">
                                            <input class="input_wid_50" type="text" name="pi_img[2]" id="pi_img_2" value="<?=$pi['pi_img_2']?>" onfocus="valiPD(this)">
                                        </td>
                                    </tr>
                                    <tr class="pi_img_3">
                                        <th class="pd">상품기술서경로3*</th>
                                        <td colspan="3">
                                            <input class="input_wid_50" type="text" name="pi_img[3]" id="pi_img_3" value="<?=$pi['pi_img_3']?>" onfocus="valiPD(this)">
                                        </td>
                                    </tr>
                                    <tr class="pi_img_4">
                                        <th class="pd">상품기술서경로4*</th>
                                        <td colspan="3">
                                            <input class="input_wid_50" type="text" name="pi_img[4]" id="pi_img_4" value="<?=$pi['pi_img_4']?>" onfocus="valiPD(this)">
                                        </td>
                                    </tr>
                                    
                                <?php endif ?>
                                
                                </tbody>
                                
                                <tr>
                                    <th class="pma">동영상경로1 (유투브URL)</th>
                                    <td>
                                        <input class="input_wid_100" name="pi_video1" id="pi_video1" value="<?=$pi['pi_video1']?>" onfocus="valiPM(this)">
                                    </td>
                                    <th class="pma">동영상경로2 (유투브URL)</th>
                                    <td>
                                        <input class="input_wid_100" name="pi_video2" id="pi_video2" value="<?=$pi['pi_video2']?>" onfocus="valiPM(this)">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pma">동영상경로3 (유투브URL)</th>
                                    <td>
                                        <input class="input_wid_100" name="pi_video3" id="pi_video3" value="<?=$pi['pi_video3']?>" onfocus="valiPM(this)">
                                    </td>
                                    <th class="pma">동영상경로4 (유투브URL)</th>
                                    <td>
                                        <input class="input_wid_100" name="pi_video4" id="pi_video4" value="<?=$pi['pi_video4']?>" onfocus="valiPM(this)">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">제품원본이미지경로<span style="" onclick="tooltib_imgroot()"><img style="width: 20px;height: 20px;" src="/img/re/icon_tooltip.png"></span></th>
                                    <td colspan="3">
                                        <input class="input_wid_50" name="pi_origin_image" id="pi_origin_image" value="<?=$pi['pi_origin_image']?>" onfocus="valiD(this)">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">상품설명* <span  style="" onclick="tooltib_prodInfo()"><img style="width: 20px;height: 20px;" src="/img/re/icon_tooltip.png"></span></th>
                                    <td >
                                        <!-- <div class="pi_detail_info <?= $is_dhtml_editor ? $config['cf_editor'] : ''; ?>">
                                            <? if ($write_min || $write_max) { ?>
                                                
                                                <p id="char_count_desc">이 게시판은 최소 <strong><?= $write_min; ?></strong>글자 이상, 최대 <strong><?= $write_max; ?></strong>글자 이하까지 글을 쓰실 수 있습니다.</p>
                                            <? } ?>
                                            <?= $editor_html; 
                                            ?>
                                            <? if ($write_min || $write_max) { ?>
                                                
                                                <div id="char_count_wrap"><span id="char_count"></span>글자</div>
                                            <? } ?>
                                        </div> -->
                                        <textarea style='width:100%; height : 200px;' id ='pi_detail_info' name = 'pi_detail_info'><?=strip_tags($pi['pi_detail_info'])?></textarea>
                                    </td>
                                    <th class="design">셀링포인트1* <span style="" onclick="tooltib_selling()"><img style="width: 20px;height: 20px;" src="/img/re/icon_tooltip.png"></span></th>
                                    <td>
                                        <div class="pi_selling1 <?= $is_dhtml_editor ? $config['cf_editor'] : ''; ?>">
                                            <? if ($write_min || $write_max) { ?>
                                                <!-- 최소/최대 글자 수 사용 시 -->
                                                <p id="char_count_desc">이 게시판은 최소 <strong><?= $write_min; ?></strong>글자 이상, 최대 <strong><?= $write_max; ?></strong>글자 이하까지 글을 쓰실 수 있습니다.</p>
                                            <? } ?>
                                            <?= $editor_htmls1; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 
                                            ?>
                                            <? if ($write_min || $write_max) { ?>
                                                <!-- 최소/최대 글자 수 사용 시 -->
                                                <div id="char_count_wrap"><span id="char_count"></span>글자</div>
                                            <? } ?>
                                        </div>
                                        <textarea style='width:100%; height : 200px;' id ='pi_selling1' name = 'pi_selling1'><?=strip_tags($pi['pi_selling1'])?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">셀링포인트2* <span style="" onclick="tooltib_selling()"><img style="width: 20px;height: 20px;" src="/img/re/icon_tooltip.png"></span></th>
                                    <td>
                                        
                                        <textarea style='width:100%; height : 200px;' id ='pi_selling2' name = 'pi_selling2'><?=strip_tags($pi['pi_selling2'])?></textarea>
                                    </td>
                                    <th class="design">셀링포인트3* <span style="" onclick="tooltib_selling()"><img style="width: 20px;height: 20px;" src="/img/re/icon_tooltip.png"></span></th>
                                    <td>
                                        <textarea style='width:100%; height : 200px;' id ='pi_selling3' name = 'pi_selling3'><?=strip_tags($pi['pi_selling3'])?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">제품정보1* <span style="" onclick="tooltib_itemInfo1()"><img style="width: 20px;height: 20px;" src="/img/re/icon_tooltip.png"></span></th>
                                    <td>
                                       <textarea style='width:100%; height : 200px;' id ='pi_prod_info1' name = 'pi_prod_info1'><?=strip_tags($pi['pi_prod_info1'])?></textarea>
                                    </td>
                                    <th class="design">제품정보2* <span style="" onclick="tooltib_itemInfo2()"><img style="width: 20px;height: 20px;" src="/img/re/icon_tooltip.png"></span></th>
                                    <td>
                                        <textarea style='width:100%; height : 200px;' id ='pi_prod_info2' name = 'pi_prod_info2'><?=strip_tags($pi['pi_prod_info2'])?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">제품정보3* <span style="" onclick="tooltib_itemInfo3()"><img style="width: 20px;height: 20px;" src="/img/re/icon_tooltip.png"></span></th>
                                    <td>
                                        <textarea style='width:100%; height : 200px;' id ='pi_prod_info3' name = 'pi_prod_info3'><?=strip_tags($pi['pi_prod_info3'])?></textarea>
                                    </td>
                                    <th class="design">제품정보4 <span style="" onclick="tooltib_itemInfo4()"><img style="width: 20px;height: 20px;" src="/img/re/icon_tooltip.png"></span></th>
                                    <td>
                                        <textarea style='width:100%; height : 200px;' id ='pi_prod_info4' name = 'pi_prod_info4'><?=strip_tags($pi['pi_prod_info4'])?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">제품정보5 <span style="" onclick="tooltib_itemInfo5()"><img style="width: 20px;height: 20px;" src="/img/re/icon_tooltip.png"></span></th>
                                    <td>
                                        <textarea style='width:100%; height : 200px;' id ='pi_prod_info5' name = 'pi_prod_info5'><?=strip_tags($pi['pi_prod_info5'])?></textarea>
                                    </td>
                                    <th class="design">제품정보6 <span style="" onclick="tooltib_itemInfo6()"><img style="width: 20px;height: 20px;" src="/img/re/icon_tooltip.png"></span></th>
                                    <td>
                                        <textarea style='width:100%; height : 200px;' id ='pi_prod_info6' name = 'pi_prod_info6'><?=strip_tags($pi['pi_prod_info6'])?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">제품정보7 <span style="" onclick="tooltib_itemInfo7()"><img style="width: 20px;height: 20px;" src="/img/re/icon_tooltip.png"></span></th>
                                    <td>
                                        <textarea style='width:100%; height : 200px;' id ='pi_prod_info7' name = 'pi_prod_info7'><?=strip_tags($pi['pi_prod_info7'])?></textarea>
                                    </td>
                                    <th class="design">제품정보8 <span style="" onclick="tooltib_itemInfo8()"><img style="width: 20px;height: 20px;" src="/img/re/icon_tooltip.png"></span></th>
                                    <td>
                                        <textarea style='width:100%; height : 200px;' id ='pi_prod_info8' name = 'pi_prod_info8'><?=strip_tags($pi['pi_prod_info8'])?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">제품정보9 <span style="" onclick="tooltib_itemInfo9()"><img style="width: 20px;height: 20px;" src="/img/re/icon_tooltip.png"></span></th>
                                    <td>
                                        <textarea style='width:100%; height : 200px;' id ='pi_prod_info9' name = 'pi_prod_info9'><?=strip_tags($pi['pi_prod_info9'])?></textarea>
                                    </td>
                                    <th class="design">제품정보10 <span style="" onclick="tooltib_itemInfo10()"><img style="width: 20px;height: 20px;" src="/img/re/icon_tooltip.png"></span></th>
                                    <td>
                                        <textarea style='width:100%; height : 200px;' id ='pi_prod_info10' name = 'pi_prod_info10'><?=strip_tags($pi['pi_prod_info10'])?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="design">비고</th>
                                    <td colspan="3">
                                        <input class="input_wid_50" name="etc" id="etc" value="<?=$pi['etc']?>"  onfocus="valiD(this)">
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
                            <button class="btn btn_02" type="button" id="btn_submit">임시저장</button>
                            <button type="submit" class="btn btn-success" >저장</button>
                            <?if($first_insert == $jo_id) : ?>
                                <button type="button" class="btn btn-warning" onclick="gumsu()" >검수완료</button>

                                <button type="button" class="btn btn_02" onclick="edit_order_pop()" >수정요청</button>
                                <button type="button" class="btn btn-info" onclick="edit_complete()" >수정완료</button>
                            <?endif?>
                            <button type="button" class="btn btn-danger" onclick="sabang_send()" >사방넷송신</button>
                            <?if($member['mb_id'] == 'sbs608' || $aas['mb_dept'] == '플랫폼팀(MD)') : ?>
                            <button type="button" class="btn btn-danger" onclick="opt_sabang_send('S')" >사방넷송신(묶음코드_사이즈)</button>
                            <button type="button" class="btn btn-danger" onclick="opt_sabang_send('C')" >사방넷송신(묶음코드_컬러)</button>
                            <button type="button" class="btn btn-danger" onclick="opt_sabang_send('SC')" >사방넷송신(묶음코드_사이즈&컬러)</button>
                            <?endif?>
                            <input type="hidden" name="tem_save" id="tem_save" value="N">
                        </div>
                    </div>
                </div>
                
<!-- tabindex="-1" -->
<div class="modal fade" id="edit_order_pop"  role="dialog" aria-labelledby="edit_order_pop">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">수정요청</h4>
            </div>
            <div class="modal-body">
                <div class="edit_order_content <?= $is_dhtml_editor ? $config['cf_editor'] : ''; ?>">
                    <? if ($write_min || $write_max) { ?>
                        <!-- 최소/최대 글자 수 사용 시 -->
                        <p id="char_count_desc">이 게시판은 최소 <strong><?= $write_min; ?></strong>글자 이상, 최대 <strong><?= $write_max; ?></strong>글자 이하까지 글을 쓰실 수 있습니다.</p>
                    <? } ?>
                    <?= $edit_order_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 
                    ?>
                    <? if ($write_min || $write_max) { ?>
                        <!-- 최소/최대 글자 수 사용 시 -->
                        <div id="char_count_wrap"><span id="char_count"></span>글자</div>
                    <? } ?>
                </div>

            </div>
            <div class="modal-footer">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                    <button class="btn btn_02" type="button" data-dismiss="modal"  id="btn_cancel">취소</button>
                    <button class="btn btn-success" type="button" id="btn_submit2">저장</button>
                </div>
            </div>
        </div>
    </div>
</div>

            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_preview_img" tabindex="-1" role="dialog" aria-labelledby="modal_preview_img">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">미리보기</h4>
            </div>
            <div class="modal-body">
                <div style="width: 100%; max-height: 80%;">
                    <img id="imgPath" src="#" style="width: 50%; max-height: 80%;">
                </div>
                <div id="imgStr">
                </div>
            </div>
            <div class="modal-footer">
                <br><br><br>
                <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="preview_imgs" tabindex="-1" role="dialog" aria-labelledby="preview_imgs">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">상품이미지</h4>
            </div>
            <div class="modal-body">
            <div id="imgs">
            </div>
            </div>
        </div>
    </div>
</div>

<!-- //툴팁 -->
<div class="modal fade" id="prod_weight" tabindex="-1" role="dialog" aria-labelledby="prod_weight">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">상품무게(충전물 중량) <span style="float: right;margin-right: 50px; color:red;"><span></h4>
            </div>
            <div class="modal-body">
                <pre>
                
                g단위만 입력해주세요(kg/온스 절대 불가)
                </pre>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="img_root" tabindex="-1" role="dialog" aria-labelledby="img_root">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">제품원본이미지 경로 <span style="float: right;margin-right: 50px; color:red;"><span></h4>
            </div>
            <div class="modal-body">
                <pre>
                품명별로 별도 이미지경로 폴더랑 제품명 맞춰야 합니다.
                (플랫폼 디자인팀)
                </pre>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="prod_soje" tabindex="-1" role="dialog" aria-labelledby="prod_soje">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">제품소재 <span style="float: right;margin-right: 50px; color:red;">* 제품별 상이<span></h4>
            </div>
            <div class="modal-body">
                <pre>
                원단과 충전재 모두 작성해주세요. 

                <font style='color:blue'>1. 원단</font>

                <strong>| 양식 : 수 + 소재 + 조직 (상세사양)</strong>

                예) 
                * 앞뒤 같은 경우 : 40수 MOC 트윌(모달 70% + 면 30%)
                * 앞뒤 다를 경우 : 앞) 60수 모달 리플(모달 100%) / 뒤) 60수 모달 평직(모달 100%)

                <font style='color:purple'>2. 충전재</font>

                <strong>| 양식 : 원산지 + 우모 + 함량 </strong>

                예) 
                * 1제품 1 충전재의 경우 : 헝가리산 구스다운 30% + 구스 페더 70%
                * 1제품 2 충전재 이상의 경우 : 상단) 구스다운 75% + 구스페더 25% / 하단) 구스페더100%

                </pre>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="prod_info" tabindex="-1" role="dialog" aria-labelledby="prod_info">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">상품설명 <span style="float: right;margin-right: 50px; color:red;">* 중요한 내용은 볼드 처리 해주세요.<span></h4>
            </div>
            <div class="modal-body">
                <pre>
                <font style="color : red">1. 속통 </font>

                <strong>| 내용 : 상품설명서에 작성했던 제품에 대한 전반적인 내용</strong>
                <strong>| 양식 : 소제목 X , 상세 내용 O </strong>

                예) 
                최상급 필파워를 자랑하는 우수한 품질의 폴란드산 구스다운을 
                간절기/여름에 사용하기 좋은 중량감(300g)으로 제작하였습니다.
                수면 중 적정 체온을 유지시켜 쾌적하고 편안한 수면 활동을 돕는 녹턴을 소개합니다. 

                <font style="color : blue">2. 커버 </font>

                <strong>| 내용 : 상품설명서에 작성했던 제품에 대한 전반적인 내용</strong>
                <strong>| 양식 : 소제목 X , 상세 내용 O </strong>

                예) 
                봄의 따스한 햇볕에 비친 화사한 꽃들의 싱그러움을 간직한 플라워 패턴 베딩 제품입니다. 
                출시 1년간 매일 10개 이상씩 판매되며 6차 물량, 전량 완판된 베스트 혼수 아이템입니다. 
                부드러운 면 모달과 면을 혼방하여피부가 민감한 분들도 부담 없이 사용할 수 있습니다. 

                </pre>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="selling" tabindex="-1" role="dialog" aria-labelledby="selling">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">셀링포인드 1,2,3 <span style="float: right;margin-right: 50px; color:red;">* 중요한 내용은 볼드 처리 해주세요.<span></h4>
            </div>
            <div class="modal-body">
                <pre>
                <font style="color : red">1. 속통 </font>

                <strong>| 내용 : 제품의 장점 중 3가지 소개</strong>
                <strong>| 양식 : 소제목 O , 상세 내용 O </strong>

                예) 
                셀링포인트 1 : 충전재
                셀링포인트 2 : 원단
                셀링포인트 3 :  사용자에게 주는 장점 

                <font style="color : blue">2. 커버 </font>

                <strong>| 내용 : 제품의 장점 중 3가지 소개</strong>
                <strong>| 양식 : 소제목 O , 상세 내용 O</strong>

                예) 
                셀링포인트 1 : 패턴/컬러 장점
                셀링포인트 2 : 원단
                셀링포인트 3 : 사용상 장점 
                </pre>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="itemInfo1" tabindex="-1" role="dialog" aria-labelledby="itemInfo1">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">제품정보1 <span style="float: right;margin-right: 50px; color:red;">* 중요한 내용은 볼드 처리 해주세요.<span></h4>
            </div>
            <div class="modal-body">
                <pre>
                <font style="color : red">1. 속통 </font>

                <strong>| 내용 : 추천인 </strong>
                            – 누구에게 /어떤 상황에 있는 사람에게 추천하고 싶은가?

                <strong>| 양식 : 소제목 X , 상세 내용 O </strong>


                <font style="color : blue">2. 커버 </font>

                <strong>| 내용 : 컬러 </strong>
                        – 어떤 컬러인가? 어디에서 유행하고,영감을 얻었나?

                <strong>| 양식 : 소제목 X , 상세 내용 O</strong>
                </pre>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="itemInfo2" tabindex="-1" role="dialog" aria-labelledby="itemInfo2">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">제품정보2 <span style="float: right;margin-right: 50px; color:red;">* 중요한 내용은 볼드 처리 해주세요.<span></h4>
            </div>
            <div class="modal-body">
                <pre>
                <font style="color : red">1. 속통 </font>

                <strong>| 내용 : 충전재 </strong>
                            –원산지 / 함유량 / 필파워 / 중량이 왜 좋은가? 
                            - 어떤 사람에게/어떤 계절에/어떤 상황에 좋은가? 
                            - 구스 인증 마크(RDS, 피톤치드 등등)?

                <strong>| 양식 : 소제목 X , 상세 내용 O </strong>


                <font style="color : blue">2. 커버 </font>

                <strong>| 내용 : 사용상 장점  中 1</strong>
                            - 양면으로 사용할 수 있거나, 다용도로 사용할수 있거나.. 등등
                            - 먼지날림, 정전기 등 사용상 불편함 해소 
                            - (제품/소재만의) 세탁의 용이성 

                <strong>| 양식 : 소제목 X , 상세 내용 O</strong>

                </pre>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="itemInfo3" tabindex="-1" role="dialog" aria-labelledby="itemInfo3">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">제품정보3 <span style="float: right;margin-right: 50px; color:red;">* 중요한 내용은 볼드 처리 해주세요.<span></h4>
            </div>
            <div class="modal-body">
                <pre>
                <font style="color : red">1. 속통 </font>

                <strong>| 내용 : 소재 </strong>
                            –소재의 종류, 장점 (촉감 특징)
                            - 특별 가공처리(항균, 다운프루프)장점
                <strong>| 양식 : 소제목 X , 상세 내용 O </strong>


                <font style="color : blue">2. 커버 </font>

                <strong>| 내용 : 원단/소재</strong>
                            - 소재의 종류, 장점 (촉감의 특징)
                            - 특별 가공처리(항균, 워싱, 리플 등) 장점 

                <strong>| 양식 : 소제목 X , 상세 내용 O</strong>

                </pre>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="itemInfo4" tabindex="-1" role="dialog" aria-labelledby="itemInfo4">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">제품정보4 <span style="float: right;margin-right: 50px; color:red;">* 중요한 내용은 볼드 처리 해주세요.<span></h4>
            </div>
            <div class="modal-body">
                <pre>
                <font style="color : red">1. 속통 </font>

                <strong>| 내용 : 생산/가공법 </strong>
                            - 3D 입체 봉제 방식, 방의 수 
                            - 이중 봉제 마감, 파이핑 마감
                            - 이불고리(이불끈) 
                            * 해당 사항 전부 작성           

                <strong>| 양식 : 소제목 X , 상세 내용 O </strong>


                <font style="color : blue">2. 커버 </font>

                <strong>| 내용 : 패턴</strong>
                            - 어디에서 영감을 얻었나?
                            - 어떤 기법으로 표현되었나?
                            - 어디에서 유행하는가?
                <strong>| 양식 : 소제목 X , 상세 내용 O</strong>

                </pre>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="itemInfo5" tabindex="-1" role="dialog" aria-labelledby="itemInfo5">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">제품정보5 <span style="float: right;margin-right: 50px; color:red;">* 중요한 내용은 볼드 처리 해주세요.<span></h4>
            </div>
            <div class="modal-body">
                <pre>
                <font style="color : red">1. 속통 </font>

                <strong>| 내용 : 마감 특징</strong>
                            - 박음질(땀수) 
                            - 패밀리 브랜드 라벨 
                            - 코너 라벨
                            - 16개 이불 고리 
                            - 더블스티치와 파이핑마감
                            * 해당 사항 전부 작성           

                <strong>| 양식 : 소제목 X , 상세 내용 O </strong>


                <font style="color : blue">2. 커버 </font>

                <strong>| 내용 : 인테리어팁</strong>
                            - 어떤 재질/컬러의 인테리어와 잘 어울리는지
                            - 어떤 컬러/소재/아이템으로 코디하면 좋은지 

                <strong>| 양식 : 소제목 X , 상세 내용 O</strong>

                </pre>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="itemInfo6" tabindex="-1" role="dialog" aria-labelledby="itemInfo6">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">제품정보6 <span style="float: right;margin-right: 50px; color:red;">* 중요한 내용은 볼드 처리 해주세요.<span></h4>
            </div>
            <div class="modal-body">
                <pre>
                <font style="color : red">1. 속통 </font>

                <strong>| 내용 : 사용상 장점</strong>
                        - 구스의 장점 : 보온성(겨울철 절세o), 흡습·방습성, 경량성, 바디커버링
                        - 사용상 장점 : 호텔의 바삭하고 폭신한 느낌 
                            * 해당 사항 전부 작성           

                <strong>| 양식 : 소제목 X , 상세 내용 O </strong>


                <font style="color : blue">2. 커버 </font>

                <strong>| 내용 : 마감 특징 中 1</strong>
                            - 박음질
                            - 제품 마감 양식(콘솔지퍼/일반지퍼,단추,자루 등)  
                            - 이불고리(이불끈/스냅)
                            - 난지, 연폭 등  
                            - 미끄럼 방지 처리 등 추가 가공 처리

                <strong>| 양식 : 소제목 X , 상세 내용 O</strong>

                </pre>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="itemInfo7" tabindex="-1" role="dialog" aria-labelledby="itemInfo7">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">제품정보7 <span style="float: right;margin-right: 50px; color:red;">* 중요한 내용은 볼드 처리 해주세요.<span></h4>
            </div>
            <div class="modal-body">
                <pre>
                <font style="color : red">1. 속통 </font>

                <strong>| 내용 : 추천 구성</strong>
                        - 같이 사용하기 좋은 연관 제품 추천(소개) 

                <strong>| 양식 : 소제목 X , 상세 내용 O </strong>


                <font style="color : blue">2. 커버 </font>

                <strong>| 내용 : 아이템 특징</strong>
                            - 이불커버 
                            - 베개커버
                            - 매트리스커버
                            - 패드 
                            - 그 외 (스프레드, 홑이불, 점프차렵 등) 

                <strong>| 양식 : 소제목 X , 상세 내용 O</strong>

                </pre>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="itemInfo8" tabindex="-1" role="dialog" aria-labelledby="itemInfo8">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">제품정보8 <span style="float: right;margin-right: 50px; color:red;">* 중요한 내용은 볼드 처리 해주세요.<span></h4>
            </div>
            <div class="modal-body">
                <pre>
                <font style="color : red">1. 속통 </font>

                <strong>| 내용 : 기타 특징</strong>
                            - 자체 특허받은 디자인 (ex. 윙필로우)
                            - 독립적인 생산 기술 / 국내 생산 기술 등
                            - 프라우덴
                            - 주문 제작 상품
                            - 4년연속 전량 매진 기록 (ex.쇼팽) 

                <strong>| 양식 : 소제목 X , 상세 내용 O </strong>


                <font style="color : blue">2. 커버 </font>

                <strong>| 내용 : 마감 특징 中 1</strong>
                            - 박음질
                            - 제품 마감 양식(콘솔지퍼/일반지퍼,단추,자루 등)  
                            - 이불고리(이불끈/스냅)
                            - 난지, 연폭 등  
                            - 미끄럼 방지 처리 등 추가 가공 처리

                <strong>| 양식 : 소제목 X , 상세 내용 O</strong>

                </pre>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="itemInfo9" tabindex="-1" role="dialog" aria-labelledby="itemInfo9">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">제품정보9 <span style="float: right;margin-right: 50px; color:red;">* 중요한 내용은 볼드 처리 해주세요.<span></h4>
            </div>
            <div class="modal-body">
                <pre>
                <font style="color : red">1. 속통 </font>

                <strong>| 내용 : (구스차렵 기재) 패턴, 컬러 </strong>
                            – 어떤 컬러인가? 어디에서 유행하고,영감을 얻었나?
                            - 어떤 기법으로 표현되었나?

                <strong>| 양식 : 소제목 X , 상세 내용 O </strong>


                <font style="color : blue">2. 커버 </font>

                <strong>| 내용 : 사용상 장점  中 1</strong>
                            - 양면으로 사용할 수 있거나, 다용도로 사용할수 있거나.. 등등
                            - 먼지날림, 정전기 등 사용상 불편함 해소 
                            - (제품/소재만의) 세탁의 용이성 

                <strong>| 양식 : 소제목 X , 상세 내용 O</strong>

                </pre>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="itemInfo10" tabindex="-1" role="dialog" aria-labelledby="itemInfo10">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">제품정보1 <span style="float: right;margin-right: 50px; color:red;">* 중요한 내용은 볼드 처리 해주세요.<span></h4>
            </div>
            <div class="modal-body">
                <pre>
                <font style="color : red">1. 속통 </font>

                <strong>| 내용 : (구스차렵 기재) 인테리어 팁 </strong>
                            - 어떤 재질/컬러의 인테리어와 잘 어울리는지
                            - 어떤 컬러/소재/아이템으로 코디하면 좋은지 

                <strong>| 양식 : 소제목 X , 상세 내용 O </strong>


                <font style="color : blue">2. 커버 </font>

                <strong>| 내용 : 마감 특징 中 1</strong>
                            - 박음질
                            - 제품 마감 양식(콘솔지퍼/일반지퍼,단추,자루 등)  
                            - 이불고리(이불끈/스냅)
                            - 난지, 연폭 등  
                            - 미끄럼 방지 처리 등 추가 가공 처리

                <strong>| 양식 : 소제목 X , 상세 내용 O</strong>

                </pre>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="main_preview_imgs" tabindex="-1" role="dialog" aria-labelledby="main_preview_imgs">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">대표이미지</h4>
            </div>

            <div class="modal-body">
                <div id="main_imgs">
                </div>
            </div>
        </div>
    </div>
</div>


<script src="../../vendors/bootstrap-tagsinput-latest/src/bootstrap-tagsinput.js"></script>
<script>
    $( '.top_btn' ).click( function() {
        $( 'html, body' ).animate( { scrollTop : 0 }, 400 );
        return false;
    } );
    $(document).ready(function(){
        history.pushState(null, document.title, location.href); 
        window.addEventListener('popstate', function(event) { 
            history.pushState(null, document.title, location.href);
            var param = '<?=$qstr?>';
            window.location.href = 'new_goods_process.php?'+param;
        });   
    });
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


    $("#pi_design_style").on("change",function() {
        let type = $("#pi_design_style").val();
        $("#pi_design_style_sub").removeClass();        

        // var item1 = <?php echo json_encode($pumitem1)?>;
        // var item2 = <?php echo json_encode($pumitem2)?>;
        // // var item3 = <?php echo json_encode($pumitem3)?>;
        // var item4 = <?php echo json_encode($pumitem4)?>;
        
        switch(type) {
            case '베이직' :
                $("#pi_design_style_sub").addClass('sub_style1');
                $("#pi_design_style_sub").val('');
                break;
            case '호텔베딩' :
                $("#pi_design_style_sub").addClass('sub_style2');
                $("#pi_design_style_sub").val('호텔베딩');
                break;
            case '모던' :
                $("#pi_design_style_sub").addClass('sub_style3');
                $("#pi_design_style_sub").val('');
                break;
            case '클래식' :
                $("#pi_design_style_sub").addClass('sub_style4');
                $("#pi_design_style_sub").val('');
                break;
            case '에스닉' :
                $("#pi_design_style_sub").addClass('sub_style5');
                $("#pi_design_style_sub").val('');
                break;
            case '내추럴' :
                $("#pi_design_style_sub").addClass('sub_style6');
                $("#pi_design_style_sub").val('내추럴');
                break;
            case '로맨틱' :
                $("#pi_design_style_sub").addClass('sub_style7');
                $("#pi_design_style_sub").val('');
                break;
            case '키즈' :
                $("#pi_design_style_sub").addClass('sub_style8');
                $("#pi_design_style_sub").val('키즈');
                break;
            case '기타' :
                $("#pi_design_style_sub").addClass('sub_style9');
                $("#pi_design_style_sub").val('기타');
                break;
        }
    });

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
    $('#startdatepicker').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true,
        format: 'YYYY-MM-DD HH:mm',
        locale: 'ko'
    });

    $('#enddatepicker').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true,
        format: 'YYYY-MM-DD HH:mm',
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
        $("#tem_save").val('Y');

        fwrite_submit($("#fwrite"));
    });
    $("#btn_submit2").click(function() {
        fwrite_submit($("#fwrite"));
    });

    $("#fwrite").submit(function (){
        var result = confirm("저장하시겠습니까?");
        if(result){
            $.ajax({
                url: $('#fwrite').attr('action'),
                type: 'POST',
                data : $('#fwrite').serialize(),
                success: function(){
                    alert('저장이 완료되었습니다.');
                    location.reload();
                }
            });
        }else{
            return;
        }
        
        

    });

    var addItemCnt = 0;

    function notext(text){
        // console.log(text.value.match(/[^0-9]/g));
        if(text.value.match(/[^0-9]/g)){
            $("#pi_prod_weight").val("");
            text.blur();
        }else{

        }

    }

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

        document.getElementById("btn_submit").disabled = "disabled";
        f.submit();
        // if (confirm("저장하시겠습니까?")) {
        //     document.getElementById("btn_submit").disabled = "disabled";
        //     f.submit();
        // } else {
        //     return false;
        // }
    }
    //디자인팀
    function valiD(elem) {
        let super_id = "<? echo $aas['mb_id']?>";
        let dept = "<? echo $aas['mb_dept']?>";
        if(dept == '디자인팀' || dept == '상품MD팀' || super_id == 'sbs608' || super_id == 'jeongwseong' || dept == '플랫폼팀(MD)'){
            $(elem).prop('disabled', false);
            return;
        }else{
            alert('디자인팀 만 작성할 수 있습니다.');
            $(elem).prop('disabled', true);
            $(elem).blur();
        }
    }
    //플랫폼팀(MD) & 디자인팀
    function valiDMD(elem) {
        let super_id = "<? echo $aas['mb_id']?>";
        let dept = "<? echo $aas['mb_dept']?>";
        if(dept == '플랫폼팀(MD)' || dept == '디자인팀' || dept == '상품MD팀' || super_id == 'sbs608' || super_id == 'jeongwseong'){
            $(elem).prop('disabled', false);
            return;
        }else{
            alert('플랫폼팀(MD) 만 작성할 수 있습니다.');
            $(elem).prop('disabled', true);
            $(elem).blur();
        }
    }
    //플랫폼팀(MD)
    function valiPMD(elem) {
        let super_id = "<? echo $aas['mb_id']?>";
        let dept = "<? echo $aas['mb_dept']?>";
        if(dept == '플랫폼팀(MD)' || dept == '상품MD팀' || super_id == 'sbs608' || super_id == 'jeongwseong'){
            $(elem).prop('disabled', false);
            return;
        }else{
            alert('플랫폼팀(MD) 만 작성할 수 있습니다.');
            $(elem).prop('disabled', true);
            $(elem).blur();
        }
    }
    //플랫폼팀(디자인)
    function valiPD(elem) {
        let super_id = "<? echo $aas['mb_id']?>";
        let dept = "<? echo $aas['mb_dept']?>";
        if(dept == '플랫폼팀(디자인)' || dept == '상품MD팀' || super_id == 'sbs608' || super_id == 'jeongwseong' || dept == '플랫폼팀(MD)'){
            $(elem).prop('disabled', false);
            return;
        }else{
            alert('플랫폼팀(디자인) 만 작성할 수 있습니다.');
            $(elem).prop('disabled', true);
            $(elem).blur();
        }
    }
    //플랫폼팀(마케팅)
    function valiPM(elem) {
        let super_id = "<? echo $aas['mb_id']?>";
        let dept = "<? echo $aas['mb_dept']?>";
        if(dept == '플랫폼팀(마케팅)' || dept == '상품MD팀' || super_id == 'sbs608' || super_id == 'jeongwseong' || dept == '플랫폼팀(MD)'){
            $(elem).prop('disabled', false);
            return;
        }else{
            alert('플랫폼팀(마케팅)팀 만 작성할 수 있습니다.');
            $(elem).prop('disabled', true);
            $(elem).blur();
        }
    }
    //이미지
    $(".btn-add-images").on("click", function() {
        let nextIdx = $("button.btn-add-images").last().data("item-idx") * 1 + 1;
        let setHtml = '';
        setHtml += '<tr class="pi_img_' + nextIdx + '">';
        setHtml += '<th class="pd">상품기술서경로' + nextIdx + '*<button type="button" class="btn-add-images"  onclick="del_images(' + nextIdx + ')" data-item-idx=' + nextIdx + '>삭제</button></th>';
        setHtml += '<td colspan="3"><input class="input_wid_50" type="text" name="pi_img[' + nextIdx + ']" id="pi_img_' + nextIdx + '" value=""></td></tr>';
            
        
        $("#pi_img_area").append(setHtml);
    });

    function del_images(idx) {
        $(".pi_img_"+idx).remove();
    }

    function preview_Imgs(){
        //$('input[name="pi_img"]').val();
        let index = $("button.btn-add-images").last().data("item-idx");
        let imgshtml = '';
        for(var i = 1 ; i<=index; i++){
            $("#pi_img_"+i).val();
            imgshtml+= '<div><img src="'+$("#pi_img_"+i).val()+'"></div>';
        }
        $("#imgs").empty().append(imgshtml);

        $("#preview_imgs").modal('show');
    }

    function tooltib_prod_weight(){
        $("#prod_weight").modal('show');
    }
    function tooltib_imgroot(){
        $("#img_root").modal('show');
    }

    function tooltib_soje(){
        $("#prod_soje").modal('show');
    }
    function tooltib_prodInfo(){
        $("#prod_info").modal('show');
    }
    function tooltib_selling(){
        $("#selling").modal('show');
    }
    function tooltib_itemInfo1(){        $("#itemInfo1").modal('show');    }
    function tooltib_itemInfo2(){        $("#itemInfo2").modal('show');    }
    function tooltib_itemInfo3(){        $("#itemInfo3").modal('show');    }
    function tooltib_itemInfo4(){        $("#itemInfo4").modal('show');    }
    function tooltib_itemInfo5(){        $("#itemInfo5").modal('show');    }
    function tooltib_itemInfo6(){        $("#itemInfo6").modal('show');    }
    function tooltib_itemInfo7(){        $("#itemInfo7").modal('show');    }
    function tooltib_itemInfo8(){        $("#itemInfo8").modal('show');    }
    function tooltib_itemInfo9(){        $("#itemInfo9").modal('show');    }
    function tooltib_itemInfo10(){       $("#itemInfo10").modal('show');    }
</script>
<!-- @END@ 내용부분 끝 -->




<script>
    function preview_Img(imgPath){
        $("#imgPath").attr('src' , imgPath);
        $("#imgStr").html(imgPath);

        $("#modal_preview_img").modal('show');
    }

    function Imgs_copy_html(){
        
        let img_idx = $("button.btn-add-images").last().data("item-idx");
        let img_html = '';
        for(var i = 1 ; i<=img_idx; i++){
            $("#pi_img_"+i).val();
            img_html+= '<div><img src="'+$("#pi_img_"+i).val()+'"></div> \n';
        }

        $("#copy_full_imgs").attr('data-clipboard-text',img_html);

        var clipboard = new ClipboardJS('#copy_full_imgs');
        clipboard.on('success', function(e) {
            alert("복사 성공: \n" + e.text);
        });
        clipboard.on('error', function(e) {
            alert("복사 실패");
        });
    }

</script>

<script>
    $(function() {
        $("#pi_origin_price").autoNumeric('init', {
                //소수점 표기 안됨
            mDec: '0'
        });
        $("#pi_sale_price").autoNumeric('init', {
            mDec: '0'
        });
        $("#pi_sale_price2").autoNumeric('init', {
            mDec: '0'
        });
        $("#pi_tag_price").autoNumeric('init', {
            mDec: '0'
        });

        // var temSave = '<? echo $pi['tem_save'] ?>';
        // if(temSave == 'Y'){
        //     alert("임시 저장상태입니다.");
        // }

        var hangkun_txt = '<? echo $pi['pi_hangkun_info'] ?>';
        if(hangkun_txt == 'direct'){
            $("#selboxDirect").show();
        }else{
            $("#selboxDirect").hide();
        }

        $("#pi_hangkun_info").change(function() {
            //직접입력을 누를 때 나타남            
            if($("#pi_hangkun_info").val() == "direct") {
                $("#selboxDirect").show();
            }  else {
                $("#selboxDirect").hide();
            }
        }); 

        //충전재에 따른 패이지

        var charge_txt = '<? echo $pi['pi_charge'] ?>';
        if((charge_txt == '구스') || (charge_txt == '덕다운')){
            $(".charge_chk").show();
        }else{
            $(".charge_chk").hide();
        }
        $("#pi_charge").change(function() {
            //직접입력을 누를 때 나타남            
            if(($("#pi_charge").val() == "구스") || ($("#pi_charge").val() == "덕다운")) {
                $(".charge_chk").show();
                $(".charge_chk").prop("disabled",false);
            }  else {
                $(".charge_chk").hide();
                $(".charge_chk").prop("disabled",true);
            }
        }); 

        //기타 항목 충전재
        var charge_mater_txt = '<? echo $pi['pi_charge_mater'] ?>';
        if(charge_mater_txt == 'direct'){
            $("#pi_charge_mater_etc").show();
        }else{
            $("#pi_charge_mater_etc").hide();
        }
        var charge_brand_txt = '<? echo $pi['pi_charge_brand'] ?>';
        if(charge_brand_txt == 'direct'){
            $("#pi_charge_brand_etc").show();
        }else{
            $("#pi_charge_brand_etc").hide();
        }
        var charge_weight_txt = '<? echo $pi['pi_charge_weight'] ?>';
        if(charge_weight_txt == 'direct'){
            $("#pi_charge_weight_etc").show();
        }else{
            $("#pi_charge_weight_etc").hide();
        }
        $("#pi_charge_mater").change(function() {
            //직접입력을 누를 때 나타남            
            if($("#pi_charge_mater").val() == "direct") {
                $("#pi_charge_mater_etc").show();
            }  else {
                $("#pi_charge_mater_etc").hide();
            }
        }); 
        $("#pi_charge_brand").change(function() {
            //직접입력을 누를 때 나타남            
            if($("#pi_charge_brand").val() == "direct") {
                $("#pi_charge_brand_etc").show();
            }  else {
                $("#pi_charge_brand_etc").hide();
            }
        }); 
        $("#pi_charge_weight").change(function() {
            //직접입력을 누를 때 나타남            
            if($("#pi_charge_weight").val() == "direct") {
                $("#pi_charge_weight_etc").show();
            }  else {
                $("#pi_charge_weight_etc").hide();
            }
        }); 
        

    });

    function sabang_send(){
        
        var pi_id = "<?=$pi_id?>";

        var result = confirm("사방넷 상품 송신 하시겠습니까?");
        if(result){
            $.ajax({
                url: "../sabang/sabang_new_goods_send.php",
                method: "POST",
                data: {
                    "pi_id": pi_id
                    
                },
                dataType: "json",
                async : false,
                cache: false,
                success: function(result) {
                    // console.log(result);
                    // if (result.indexOf('200') !== -1){
                    //     // window.open("../sabang/send_sabang_new_goods_form2.php");
                    //     alert("사방넷 전송 성공!");
                    // }
                    // location.reload();
                    if (result.indexOf('300') !== -1) {
                        alert("사방넷 전송 실패! \n 대표이미지 누락 \n 서버(파일질라) 내 대표이미지 경로 확인 바랍니다.");
                        location.reload();
                    }else if (result.indexOf('301') !== -1){
                        alert("사방넷 전송 실패! \n 상품명(사방넷 상품명) 누락 \n 상품정보집 저장 후 진행해주세요.");
                        location.reload();
                    }else if (result.indexOf('302') !== -1){
                        alert("사방넷 전송 실패! \n 모델명(SAP 코드) 누락 \n 상품정보집 저장 후 진행해주세요.");
                        location.reload();
                    }else if (result.indexOf('303') !== -1){
                        alert("사방넷 전송 실패! \n 모델no(삼진코드) 누락 \n 상품정보집 저장 후 진행해주세요.");
                        location.reload();
                    }else if (result.indexOf('304') !== -1){
                        alert("사방넷 전송 실패! \n 자체상품코드(사방넷자체상품코드) 누락 \n 상품정보집 저장 후 진행해주세요.");
                        location.reload();
                    }else if (result.indexOf('305') !== -1){
                        alert("사방넷 전송 실패! \n 원가 누락 \n 상품정보집 저장 후 진행해주세요.");
                        location.reload();
                    }else if (result.indexOf('306') !== -1){
                        alert("사방넷 전송 실패! \n 1차판매가 누락 \n 상품정보집 저장 후 진행해주세요.");
                        location.reload();
                    }else if (result.indexOf('307') !== -1){
                        alert("사방넷 전송 실패! \n tag가 누락 \n 상품정보집 저장 후 진행해주세요.");
                        location.reload();
                    }else if (result.indexOf('308') !== -1){
                        alert("사방넷 전송 실패! \n 상품기술서 경로가 잘못되었습니다.");
                        location.reload();
                    }
                    else if (result.indexOf('400') !== -1){
                        alert("사방넷 전송 실패! \n 삼진전산에 해당 코드를 찾을 수 없습니다.");
                        location.reload();
                    }
                    else if (result.indexOf('200') !== -1){
                        alert("사방넷 전송 성공!");
                        location.reload();
                    }
                }
            });
        }
    }
    //묶음
    function opt_sabang_send(type){
        
        
        var ps_id = "<?=$ps_id?>";
        var pi_it_name = "<?=$pi['pi_it_name']?>";
        var pi_sub_category = "<?=$pi['pi_sub_category']?>";
        var size = "<?=$pi['pi_size']?>";

        

        var result = confirm("사방넷 묶음코드 생성 하시겠습니까?");
        if(result){
            $.ajax({
                url: "../sabang/sabang_opt_goods_send.php",
                method: "POST",
                data: {
                    "ps_id": ps_id,
                    "pi_it_name": pi_it_name,
                    "pi_sub_category": pi_sub_category,
                    "pi_size": size,
                    "type": type
                    
                },
                dataType: "json",
                async : false,
                cache: false,
                success: function(result) {
                    // console.log(result);
                    if (result.indexOf('200') !== -1){
                        // window.open("../sabang/send_sabang_new_goods_form2.php");
                        alert("사방넷 전송 성공!");
                    }
                    location.reload();
                    if (result.indexOf('300') !== -1) {
                        alert("사방넷 전송 실패! \n 대표이미지 누락 \n 서버(파일질라) 내 대표이미지 경로 확인 바랍니다.");
                        location.reload();
                    }else if (result.indexOf('301') !== -1){
                        alert("사방넷 전송 실패! \n 상품명(사방넷 상품명) 누락 \n 상품정보집 저장 후 진행해주세요.");
                        location.reload();
                    }else if (result.indexOf('302') !== -1){
                        alert("사방넷 전송 실패! \n 모델명(SAP 코드) 누락 \n 상품정보집 저장 후 진행해주세요.");
                        location.reload();
                    }else if (result.indexOf('303') !== -1){
                        alert("사방넷 전송 실패! \n 모델no(삼진코드) 누락 \n 상품정보집 저장 후 진행해주세요.");
                        location.reload();
                    }else if (result.indexOf('304') !== -1){
                        alert("사방넷 전송 실패! \n 자체상품코드(사방넷자체상품코드) 누락 \n 상품정보집 저장 후 진행해주세요.");
                        location.reload();
                    }else if (result.indexOf('305') !== -1){
                        alert("사방넷 전송 실패! \n 원가 누락 \n 상품정보집 저장 후 진행해주세요.");
                        location.reload();
                    }else if (result.indexOf('306') !== -1){
                        alert("사방넷 전송 실패! \n 1차판매가 누락 \n 상품정보집 저장 후 진행해주세요.");
                        location.reload();
                    }else if (result.indexOf('307') !== -1){
                        alert("사방넷 전송 실패! \n tag가 누락 \n 상품정보집 저장 후 진행해주세요.");
                        location.reload();
                    }else if (result.indexOf('308') !== -1){
                        alert("사방넷 전송 실패! \n 상품기술서 경로가 잘못되었습니다.");
                        location.reload();
                    }
                    else if (result.indexOf('400') !== -1){
                        alert("사방넷 전송 실패! \n 삼진전산에 해당 코드를 찾을 수 없습니다.");
                        location.reload();
                    }
                    else if (result.indexOf('200') !== -1){
                        alert("사방넷 전송 성공!");
                        location.reload();
                    }
                }
            });
        }
    }

    function gumsu(){
        var id = <?=$pi['pi_id']?>;
        var ps_id = <?=$pi['ps_id']?>;
        var type = 'gumsu';

        var chk  = confirm("해당 상품정보집 검수완료 하시겠습니까?");
        if(chk){
            $.ajax({
                url: "./job_order_ing_status.php",
                method: "POST",
                data: {
                    "id": id,
                    "ps_id": ps_id,
                    "type" : type
                },
                dataType: "json",
                async: false,
                cache: false,
                success: function(result) {
                    //console.log(result);
                    if (result.indexOf('200') !== -1) {
                        alert("해당 상품정보집 검수 완료 되었습니다.");
                        location.reload();
                    }
                }
            });
        }
    }

    function edit_order_pop(){
        $("#edit_order_pop").modal('show');    
    }

    
    function edit_complete(){
        // alert("2월 오픈 예정");
        var id = <?=$pi['pi_id']?>;
        var ps_id = <?=$pi['ps_id']?>;
        var type = 'edit_complete';

        var chk  = confirm("해당 상품정보집 수정완료 처리 하시겠습니까?");
        if(chk){
            $.ajax({
                url: "./job_order_ing_status.php",
                method: "POST",
                data: {
                    "id": id,
                    "ps_id": ps_id,
                    "type" : type
                },
                dataType: "json",
                async: false,
                cache: false,
                success: function(result) {
                    //console.log(result);
                    if (result.indexOf('200') !== -1) {
                        alert("해당 수정요청 처리가 완료 되었습니다.");
                        location.reload();
                    }
                }
            });
        }
    }

    //로딩
    function LoadingWithMask() {
        //화면의 높이와 너비를 구합니다.
        var maskHeight = $(document).height();
        var maskWidth  = window.document.body.clientWidth;
        
        //화면에 출력할 마스크를 설정해줍니다.
        var mask       ="<div id='mask' style='position:absolute; z-index:9000; background-color:#000000; display:none; left:0; top:0;'></div>";
        var loadingImg ='';
        
        loadingImg +=" <img src='/img/re/Spinner.gif' style='position: relative; top:300px; display: block; margin: 20% auto;'/>";
    
        //화면에 레이어 추가
        $('body')
            .append(mask)
    
        //마스크의 높이와 너비를 화면 것으로 만들어 전체 화면을 채웁니다.
        $('#mask').css({
                'width' : maskWidth,
                'height': maskHeight,
                'opacity' :'0.3'
        });
    
        //마스크 표시
        $('#mask').show();
    
        //로딩중 이미지 표시
        $('#mask').append(loadingImg);
        $('#loadingImg').show();
    }

    function closeLoadingWithMask() {
        $('#mask, #loadingImg').hide();
        $('#mask, #loadingImg').remove(); 
    }

    function main_preview_Imgs(target){
        
        let mindex = $(".group_prod_main_img_"+target).last().data("imgs-idx");
        
        let mimgshtml = '';
        
        mimgshtml+= '<div class="top_imgs_area">';
        
        mimgshtml+= '<div class="top_imgs_group"><img  class="top_imgs" id="top_img_item" src="/data/new_goods/'+$(".group_prod_main_img_"+target+"#arr_prod_main_img_0").val()+'">';
        mimgshtml+= '<input type="hidden" id = "frist_main_img" value = "/data/new_goods/'+$(".group_prod_main_img_"+target+"#arr_prod_main_img_0").val()+'"';
        mimgshtml+= '</div>';
        mimgshtml+= '</div>';
        mimgshtml+= '<div class="thumbs_imgs_group">';
        for(var mi = 0 ; mi<=mindex; mi++){   
        mimgshtml+= '    <div  class="thumbs_imgs_area"><img onmouseover="bigImg(this)" onmouseout="normalImg(this)" class="thumbs_imgs" src="/data/new_goods/'+$(".group_prod_main_img_"+target+"#arr_prod_main_img_"+mi).val()+'"></div>';
        }                
        mimgshtml+= '</div>';
        
        
        
        $("#main_imgs").empty().append(mimgshtml);

        $("#main_preview_imgs").modal('show');
    }

    function bigImg(x) {
        $("#top_img_item").attr('src', x.src);
    }

    function normalImg(x) {
        $("#top_img_item").attr('src', $("#frist_main_img").val());
        
    }

</script>

<?
include_once('../../admin.tail.php');
?>