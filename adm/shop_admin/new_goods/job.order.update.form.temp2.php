<?

$sub_menu = "930200";
include_once('./_common.php');
include_once('../../admin.head.php');
include_once(G5_LAYOUT_PATH . "/modal.php");


auth_check($auth[substr($sub_menu,0,2)], 'w');

$yearsServer = date('Y',  G5_SERVER_TIME);
$ps_id = $_GET['ps_id'];

$before_jo_id = $_GET['be_jo_id'];
$create_type = $_GET['type'];

$size = $_REQUEST['size'];
$jo_id_temp = $_REQUEST['jo_id'];
$get_size = iconv('euc-kr', 'utf-8', $size);

$referer = $_SERVER["REQUEST_URI"];
$cut_url = explode("qstr=" , $referer);
$qstr=$cut_url[1];

if (!($w == '' || $w == 'u' || $w == 'r' || $w == 'copy')) {
    alert('w 값이 제대로 넘어오지 않았습니다.');
}

if ($w == '') {
    $title_msg = '작성';
    $be_sql = " select * from lt_job_order where jo_id = '$before_jo_id' ";
    $be_jo = sql_fetch($be_sql);
    if($create_type == 'copy'){
        $sql = " select * from lt_job_order where jo_id = '$before_jo_id' ";
        $jo = sql_fetch($sql);
    }
    if ($cp_id) {
        alert('글쓰기에는 \$cp_id 값을 사용하지 않습니다.');
    }
} else if ($w == 'u') {
    $title_msg = '수정';
    $sql = " select * from lt_job_order where ps_id = '$ps_id' and jo_id = '$jo_id_temp' ";
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

$allow_admin_sql = "select * from lt_admin where mb_id = '{$member['mb_id']}'";
$aas = sql_fetch($allow_admin_sql);

$ps_sql = " select * from lt_prod_schedule where ps_id = '$ps_id' ";
$ps = sql_fetch($ps_sql);

$jo_soje_set = array();
if (!empty($jo['jo_soje'])) {
    $jo_soje_set = json_decode($jo['jo_soje'], true);
}

$jo_main_img = array();
if (!empty($jo['jo_main_img'])) {
    $jo_main_img = json_decode($jo['jo_main_img'], true);
}
$jo_codi_img = array();
if (!empty($jo['jo_codi_img'])) {
    $jo_codi_img = json_decode($jo['jo_codi_img'], true);
}
$jo_sub_img = array();
if (!empty($jo['jo_sub_img'])) {
    $jo_sub_img = json_decode($jo['jo_sub_img'], true);
}

$jo_mater_info = array();
if (!empty($jo['jo_mater_info'])) {
    $jo_mater_info = json_decode($jo['jo_mater_info'], true);
}
$jo_sub_mater_ = array();
if (!empty($jo['jo_sub_mater'])) {
    $jo_sub_mater = json_decode($jo['jo_sub_mater'], true);
}
$jo_maip_price = array();
if (!empty($jo['jo_maip_price'])) {
    $jo_maip_price = json_decode($jo['jo_maip_price'], true);
}
$jo_gakong_item = array();
if (!empty($jo['jo_gakong_item'])) {
    $jo_gakong_item = json_decode($jo['jo_gakong_item'], true);
}
$jo_mater_name = array();
if (!empty($jo['jo_mater_name'])) {
    $jo_mater_name = json_decode($jo['jo_mater_name'], true);
}
$jo_pumjil = array();
if (!empty($jo['jo_pumjil'])) {
    $jo_pumjil = json_decode($jo['jo_pumjil'], true);
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
    '속통' => '속통'
);

$itemSql1 = "select * from lt_job_order_code where prod_type='커버' ORDER BY NO ASC";
$lt_job_order_item1 = sql_query($itemSql1);
$itemSql2 = "select * from lt_job_order_code where prod_type='기타' ORDER BY NO ASC";
$lt_job_order_item2 = sql_query($itemSql2);
$itemSql3 = "select * from lt_job_order_code where prod_type='속통' ORDER BY NO ASC";
$lt_job_order_item3 = sql_query($itemSql3);

$pumitem1 = array(
    '' => '선택'
);
if(!empty($lt_job_order_item1)){
    for($ii = 0; $rowItem1 = sql_fetch_array($lt_job_order_item1); $ii++){
        if(!empty($rowItem1['prod_name'])){
            array_push( $pumitem1, $rowItem1['prod_name']);    
        } 
    }
}
$pumitem2 = array(
    '' => '선택'
);
if(!empty($lt_job_order_item2)){
    for($iii = 0; $rowItem2 = sql_fetch_array($lt_job_order_item2); $iii++){
        if(!empty($rowItem2['prod_name'])){
            array_push($pumitem2  , $rowItem2['prod_name']);    
        }
    }   
}
$pumitem4 = array(
    '' => '선택'
);
if(!empty($lt_job_order_item3)){
    for($iiii = 0; $rowItem3 = sql_fetch_array($lt_job_order_item3); $iiii++){
        if(!empty($rowItem3['prod_name'])){
            array_push($pumitem4  , $rowItem3['prod_name']);    
        }
    }   
}
// $pumitem1 = array(
//     '' => '선택',
//     '홑겹이불커버' => '홑겹이불커버','누비이불커버' => '누비이불커버',    '차렵이불' => '차렵이불','누비이불' => '누비이불',    '홑이불' => '홑이불','겹이불' => '겹이불',    '홑보더이불' => '홑보더이불','베개커버' => '베개커버',    '자루베개커버' => '자루베개커버','누비베개커버' => '누비베개커버',    '매트커버' => '매트커버','누비매트커버' => '누비매트커버',    '프로텍터매트커버' => '프로텍터매트커버','프로텍터커버(토펴용)' => '프로텍터커버(토펴용)',    '플랫시트' => '플랫시트','패드' => '패드',    '침대커버' => '침대커버','스프레드' => '스프레드',    '카페트' => '카페트','요커버' => '요커버',    '누비요커버' => '누비요커버','쿠션커버' => '쿠션커버',    '방석커버' => '방석커버','이불베개set' => '이불베개set',    '이불매트베개set' => '이불매트베개set','이불플랫베개set' => '이불플랫베개set',    '이불베개패드set' => '이불베개패드set','홑보더베개set' => '홑보더베개set',    '차렵베개set' => '차렵베개set','차렵베개패드set' => '차렵베개패드set',    '누비이불베개set' => '누비이불베개set','스프레드베개set' => '스프레드베개set',    '인견이불패드베개set' => '인견이불패드베개set'
// );
// $pumitem2 = array(
//     '' => '선택',
//     '셔츠' => '셔츠','팬츠' => '팬츠','가디건' => '가디건','가운' => '가운','바스가운' => '바스가운','잠옷' => '잠옷',
//     '타올' => '타올','핸드타올' => '핸드타올','바스타올' => '바스타올','굿스카프' => '굿스카프','예단보자기' => '예단보자기','에코백' => '에코백','기타' => '기타','굿숄' => '굿숄'
// );
// // $pumitem3 = array(
// //     '' => '선택',
// //     '타올' => '타올','핸드타올' => '핸드타올','바스타올' => '바스타올','굿스카프' => '굿스카프','예단보자기' => '예단보자기','에코백' => '에코백','기타' => '기타','굿숄' => '굿숄'
// // );

// $pumitem4 = array(
//     '' => '선택',
//     '담요' => '담요','기타쿠션' => '기타쿠션',    '기타방석' => '기타방석','거위털쿠션솜' => '거위털쿠션솜','거위털방석솜' => '거위털방석솜','독서쿠션(드라마)' => '독서쿠션(드라마)','오리털쿠션솜' => '오리털쿠션솜','오리털방석솜' => '오리털방석솜',    '거위털블랭킷' => '거위털블랭킷','오리털블랭킷' => '오리털블랭킷',    '폴리블랭킷' => '폴리블랭킷','요솜' => '요솜',    '기타이불솜' => '기타이불솜','거위털이불솜(사계절)' => '거위털이불솜(사계절)',    '거위털이불솜(간절기)' => '거위털이불솜(간절기)','거위털이불솜(한계울)' => '거위털이불솜(한계울)','거위털차렵이불(사계절)' => '거위털차렵이불(사계절)',    '거위털차렵이불(간절기)' => '거위털차렵이불(간절기)',    '오리털이불솜(사계절)' => '오리털이불솜(사계절)','오리털이불솜(간절기)' => '오리털이불솜(간절기)',        '오리털이불솜(한겨울)' => '오리털이불솜(한겨울)','오리털차렵이불(사계절)' => '오리털차렵이불(사계절)',    '오리털차렵이불(간절기)' => '오리털차렵이불(간절기)',        '폴리이불솜' => '폴리이불솜',    '거위차렵베개set' => '거위차렵베개set','오리차렵베개set' => '오리차렵베개set',    '기타베개솜' => '기타베개솜',        '거위털베개솜' => '거위털베개솜',    '거위털베개솜(Firm)' => '거위털베개솜(Firm)','거위털베개솜(Slim)' => '거위털베개솜(Slim)',            '바디필로우' => '바디필로우','경추베개솜' => '경추베개솜',    '오리털베개솜' => '오리털베개솜',           '오리털베개솜(Firm)' => '오리털베개솜(Firm)','오리털베개솜(Slim)' => '오리털베개솜(Slim)',    '폴리베개솜' => '폴리베개솜',           '거위털페더베드(고급형)' => '거위털페더베드(고급형)','거위털페더베드(일반형)' => '거위털페더베드(일반형)',    '거위털페더베드' => '거위털페더베드',           '거위털패드' => '거위털패드','구스토퍼' => '구스토퍼',    '폴리토퍼' => '폴리토퍼'

// );

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

    .a4 {
        /* height:297mm;
        width:210mm; */
        /* font-size : 10px; */
    }
    @media print { 

        .a4 { page-break-after: always; }

        body { border:0; margin:0; padding:0;  }

        th {font: 9pt/1/5 sans-serif; background: white; color: black; background : red; color : red;}

        #tete {background : transprent; font-size : 12px;}

    }

    #blah {
        margin: 0 auto;
        display: block;
    }
    #blah_main {
        margin: 0 auto;
        display: block;
    }

</style>
<div class="row">
    
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <form name="fwrite" id="fwrite" action="<?= $action_url ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="uid" value="<?= get_uniqid(); ?>">
                <input type="hidden" name="w" value="<?= $w ?>">
                <input type="hidden" name="ps_id" value="<?= $ps_id ?>">
                <input type="hidden" name="jo_id" value="<?= $jo['jo_id'] ?>">
                <input type="hidden" name="jo_temp" value="2">
                <input type="hidden" name="token" value="<?= get_admin_token() ?>">

                <div class="x_title">
                    <h4><span class="fa fa-check-square"></span>작업지시서<small></small></h4>

                    <label class="nav navbar-right"></label>
                    <div class="clearfix"></div>
                </div>

                <div><button class="btn btn_02" onclick="printPage(event);" type="button btn-success">출력</button><button class="btn btn_02" onclick="printPage_2(event);" type="button btn-success">출력(항목숨김)</button></div>
                <label><input type="checkbox" name="function" onclick="func_checked()" <?= $jo['jo_function_yn'] == 'N' ? '' : 'checked'; ?>  id="function">수식</label>
                <input type="hidden"  name = "jo_function_yn" id = "jo_function_yn" value ="<?=$jo['jo_function_yn']?>" >
                <div>
                <?
                if(!empty($jo['ps_id'])){
                    $jo_size_sql ="select * from lt_job_order where ps_id ={$jo['ps_id']} ORDER BY jo_id ASC";

                    $jo_size_result= sql_query($jo_size_sql);
                    $jo_size = 0;
                    $jo_frist_temp = 0;
                    for ($jis = 0; $jo_row = sql_fetch_array($jo_size_result); $jis++) {
                        if($jis == 0){
                            $jo_size = $jo_row['jo_id'];
                            $jo_frist_temp = $jo_row['jo_temp'];
                        }
                    ?>
                        <button style="color:#000;" type="button" onclick="location.href='./job.order.update.form.temp<?= $jo_row['jo_temp']?>.php?w=u&amp;ps_id=<?php echo $jo['ps_id']; ?>&amp;jo_id=<?= $jo_row['jo_id'] ?>&amp;qstr=<?= $qstr?>'"><?=$jo_row['jo_size_code'] ? $jo_row['jo_size_code'] : '임시' ?></button>
                <?}}?>
                </div>
                <div class="a4" id = "print2">
                    <div class="a4" id = "print">
                    
                    <div id ="job_order_title">생산 작업지시서</div>

                    <table id="new_goods_table" style="width : auto;" >
                        <colgroup>
                            <col width="55px"/>
                            <col width="55px"/>
                            <col width="78px"/>
                            <col width="68px"/>
                            <col width="55px"/>
                            <col width="55px"/>
                            <col width="55px"/>
                            <col width="55px"/>
                            <col width="55px"/>
                            <col width="55px"/>
                            <col width="0px"/>
                            <col width="31px"/>
                            <col width="55px"/>
                            <col width="31px"/>
                            <col width="71px"/>
                            <col width="31px"/>
                            <col width="45px"/>
                            <col width="82px"/>
                            <col width="20px"/>
                            <col width="55px"/>
                            <col width="81px"/>
                            <col width="72px"/>
                            <col width="50px"/>
                            <col width="78px"/>
                        </colgroup>
                        <tr class="nohei">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th id="color1" colspan="2">제품명/구분/생산구분</th>
                            <td colspan="2">
                                <!-- <input onfocus="Vail_fixed(this)" class="noborder txt_center" type="text" name="jo_it_name" value="소프라운 국민구스 프라우덴 30배게"> -->
                                <?
                                if(!empty($create_type) && $create_type != 'copy'){
                                    $it_name = $be_jo['jo_it_name'];
                                    if($create_type == 'size'){
                                        $it_prod_type= $be_jo['jo_prod_type'];
                                        $it_prod_name= $be_jo['jo_prod_name'];
                                        $jo_id_code = $be_jo['jo_id_code'];
                                    }
                                    $it_name = $be_jo['jo_it_name'];
                                    $it_gubun = $be_jo['jo_gubun'];
                                    $it_prod_gubun = $be_jo['jo_prod_gubun'];
                                    //$it_reg_date = $be_jo['jo_reg_date'];
                                    
                                    $it_brand= $be_jo['jo_brand'];
                                    $it_prod_year= $be_jo['jo_prod_year'];
                                    $it_season= $be_jo['jo_season'];
                                    if (!empty($be_jo['jo_main_img'])) {
                                        $jo_main_img = json_decode($be_jo['jo_main_img'], true);
                                    }
                                    
                                    if (!empty($be_jo['jo_codi_img'])) {
                                        $jo_codi_img = json_decode($be_jo['jo_codi_img'], true);
                                    }
                                    
                                    if (!empty($be_jo['jo_sub_img'])) {
                                        $jo_sub_img = json_decode($be_jo['jo_sub_img'], true);
                                    }
                                    if (!empty($be_jo['jo_mater_name'])) {
                                        $jo_mater_name = json_decode($be_jo['jo_mater_name'], true);
                                    }

                                }else{
                                    $it_name = $jo['jo_it_name'];
                                    $it_gubun = $jo['jo_gubun'];
                                    $it_prod_gubun = $jo['jo_prod_gubun'];
                                    $it_reg_date = $jo['jo_reg_date'];
                                    $jo_id_code = $jo['jo_id_code'];
                                    $it_prod_type= $jo['jo_prod_type'];
                                    $it_prod_name= $jo['jo_prod_name'];
                                    $it_brand= $jo['jo_brand'];
                                    $it_prod_year= $jo['jo_prod_year'];
                                    $it_season= $jo['jo_season'];
                                    
                                    if (!empty($jo['jo_main_img'])) {
                                        $jo_main_img = json_decode($jo['jo_main_img'], true);
                                    }
                                    
                                    if (!empty($jo['jo_codi_img'])) {
                                        $jo_codi_img = json_decode($jo['jo_codi_img'], true);
                                    }
                                    
                                    if (!empty($jo['jo_sub_img'])) {
                                        $jo_sub_img = json_decode($jo['jo_sub_img'], true);
                                    }
                                    if (!empty($jo['jo_mater_name'])) {
                                        $jo_mater_name = json_decode($jo['jo_mater_name'], true);
                                    }
                                    

                                }

                                $len = mb_strlen($it_name, "UTF-8");
                                $row_ = 1;
                                if( $len > 13){
                                    $one = implode(' ',array_slice(explode(' ',$it_name),0,2));
                                    $two = implode(' ',array_slice(explode(' ',$it_name),2));

                                    
                                    $row_ = 2;
                                    

                                }else{
                                    $one = $it_name;
                                }
                              
                                ?>
                                <textarea id="text-area" onfocus="Vail_fixed(this)" class="noborder " rows="<?=$row_?>" name="jo_it_name"><?=$one?><?if($row_ > 1):?>&#13;<?endif?><?=$two?></textarea>
                                
                            </td>
                            <td>
                                <select onfocus="Vail_fixed(this)" class="noborder jo_select" name="jo_gubun" id="jo_gubun" required>
                                    <option value="기획" <?= $it_gubun == '기획' ? "selected" : "" ?>>기획</option>
                                    <option value="정상" <?= $it_gubun == '정상' ? "selected" : "" ?>>정상</option>
                                </select>
                            </td>
                            <td>
                                <?if($it_prod_gubun=='MO') :?>
                                    <input name="jo_prod_gubun" class=" noborder txt_center" value="<?=$it_prod_gubun?>" readonly>
                                <?else :?>
                                <select onfocus="Vail_fixed(this)" class="noborder jo_select" name="jo_prod_gubun" id="jo_prod_gubun" required>
                                    <option value="MA" <?= $it_prod_gubun == 'MA' ? "selected" : "" ?>>MA</option>
                                    <option value="MW" <?= $it_prod_gubun == 'MW' ? "selected" : "" ?>>MW</option>
                                    <option value="MD" <?= $it_prod_gubun == 'MD' ? "selected" : "" ?>>MD</option>
                                    <option value="MS" <?= $it_prod_gubun == 'MS' ? "selected" : "" ?>>MS</option>
                                    <option value="MX" <?= $it_prod_gubun == 'MX' ? "selected" : "" ?>>MX</option>
                                </select>
                                <?endif?>
                            </td>
                            <th colspan="2">제품코드</th>
                            <td colspan="2"><input onfocus="Vail_fixed(this)" class="noborder txt_center" name="jo_id_code" value="<?=$jo_id_code?>" <?if(($member['mb_id'] != 'ryun1002') && ($member['mb_id'] != 'sbs608')) :?> readonly <?endif?> placeholder="자동생성 및 입력"></td>
                            <td></td>
                            <th colspan="2" style="border-left : 1px double #333333;">작성일</th>
                            <td colspan="4" onclick="regDatePicker()">
                                <span style="position: relative;">
                                <input type="text" name="jo_reg_date" value="<?php echo $it_reg_date ? $it_reg_date : date("Y-m-d"); ; ?>"  id="regdatepicker" required onfocus="Vail_fixed(this)" class="noborder txt_center" size="21" maxlength="19">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"  style="position: absolute;bottom: 2px;right: 10px;top: auto;cursor: pointer;"></i>
                                </span>
                            </td>
                            <th colspan="7">원단 스와치</th>
                            
                        </tr>
                        <tr>
                            <th id="color2" rowspan="2" colspan="2">아이템</th>
                            <td rowspan="2">
                                <select onfocus="Vail_fixed(this)" class="noborder jo_select" name="jo_prod_type" id ="jo_prod_type" required >
                                <? foreach ($pumjongs as $pj => $pumjong) : ?>
                                    <option value="<?= $pj ?>" <?= $it_prod_type == $pj ? "selected" : "" ?>><?= $pumjong ?></option>
                                <?php endforeach ?>
                                </select>
                            </td>
                            <td rowspan="2" colspan="3">
                                <select  name="jo_prod_name" id ="jo_prod_name" class="jo_select noborder 
                                <? if ($it_prod_type == '커버'):?>
                                     pum1
                                <? elseif ($it_prod_type == '기타'):?>
                                     pum2
                                
                                <? elseif ($it_prod_type == '속통'):?>
                                     pum4
                                <? endif ?>
                                " required >
                                    
                                    <?if(!empty($pumitem1)) : ?>
                                        <? foreach ($pumitem1 as $pi1 => $item1) : ?>
                                            <option class="pumi1" value="<?= $item1 ?>" <?= $it_prod_name == $item1 ? "selected" : "" ?>><?= $item1 ?></option>
                                        <?php endforeach ?>
                                    <?endif?>
                                    <?if(!empty($pumitem2)) : ?>
                                        <? foreach ($pumitem2 as $pi2 => $item2) : ?>
                                            <option class="pumi2" value="<?= $item2 ?>" <?= $it_prod_name == $item2 ? "selected" : "" ?>><?= $item2 ?></option>
                                        <?php endforeach ?>
                                    <?endif?>
                                    <?if(!empty($pumitem4)) : ?>
                                        <? foreach ($pumitem4 as $pi4 => $item4) : ?>
                                            <option class="pumi4" value="<?= $item4 ?>" <?= $it_prod_name == $item4 ? "selected" : "" ?>><?= $item4 ?></option>
                                        <?php endforeach ?>
                                    <?endif?> 
                                    
                                </select>
                            </td>
                            <th rowspan="2" colspan="2">브랜드</th>
                            <td rowspan="2" colspan="2">
                                <select onfocus="Vail_fixed(this)" class="noborder jo_select" name="jo_brand" id="jo_brand" required >
                                    <? foreach ($brands as $ck => $brand) : ?>
                                        <option value="<?= $ck ?>" <?= $it_brand == $ck ? "selected" : "" ?>><?= $brand ?></option>
                                    <?php endforeach ?>
                                </select>
                            </td>
                            <td rowspan="2"></td>
                            <th rowspan="2" colspan="2">시즌</th>
                            <td rowspan="2" colspan="2">
                                <select onfocus="Vail_fixed(this)" class="noborder jo_select" name="jo_prod_year" id="jo_prod_year" required>
                                    <option value="" <?= $it_prod_year == '' ? "selected" : "" ?>>선택</option>
                                    <? for($i = (int)$yearsServer+1; 2009 < $i; $i--) {?>
                                        <option value=<?= $i?> <?= get_selected($it_prod_year, $i); ?>><?= $i?>년</option>
                                    <?}?>
                                </select>
                            </td>
                            <td rowspan="2" colspan="2">
                                <select onfocus="Vail_fixed(this)" class="noborder jo_select" name="jo_season" id="jo_season" required>
                                    <option value="" <?= $it_season == '' ? "selected" : "" ?>>선택</option>
                                    <option value="SS" <?= $it_season == 'SS' ? "selected" : "" ?>>SS</option>
                                    <option value="HS" <?= $it_season == 'HS' ? "selected" : "" ?>>HS</option>
                                    <option value="FW" <?= $it_season == 'FW' ? "selected" : "" ?>>FW</option>
                                    <option value="AA" <?= $it_season == 'AA' ? "selected" : "" ?>>AA</option>
                                </select>
                            </td>
                            <td colspan="3" class="txt_center">
                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_main_img[1]">
                                <input onfocus="Vail_fixed(this)" class="noborder txt_center" name="jo_main_img_title[1]" value="<?=$jo_main_img[1]['title'] ? stripslashes($jo_main_img[1]['title']) : '메인'?>">
                            </td>
                            <td colspan="2" class="txt_center">
                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_codi_img[1]">
                                <input onfocus="Vail_fixed(this)" class="noborder txt_center" name="jo_codi_img_title[1]" value="<?=$jo_codi_img[1]['title'] ? stripslashes($jo_codi_img[1]['title']) : '코디'?>">
                            </td>
                            <td colspan="2" class="txt_center">
                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_sub_img[1]">
                                <input onfocus="Vail_fixed(this)" class="noborder txt_center" name="jo_sub_img_title[1]" value="<?=stripslashes($jo_sub_img[1]['title'])?>">
                            </td>
                            
                        </tr>
                        <tr class="onedan_switch">
                            <td rowspan="2" colspan="3" class="txt_center">
                                <input type='file' name ="jo_main_img_img" id='verborgen_file_main' />
                                <div id="main_pf_foto">
                                    <?if($jo_main_img[1]['img']):?>
                                    <input type="hidden" name="jo_main_img_img[1]"  value="<?=$jo_main_img[1]['img']?>"> 
                                    <?endif?>
                                    <img style="margin: 0 auto; display: block;" id="main_pf_foto_img" <?if($jo_main_img[1]['img']):?> src="<?=G5_URL?>/data/new_goods/<?=$jo_main_img[1]['img']?>" <?endif?>>
                                    <input onfocus="Vail_fixed(this)" class="noborder txt_center txt_op emptyImg" name="jo_main_img_text[1]"  value="<?=$jo_main_img[1]['text']?>">
                                </div>    
                            </td>
                            <td rowspan="2" colspan="2" class="txt_center">
                                <input type='file' name ="jo_codi_img_img" id='verborgen_file_codi' />    
                                <div id="codi_pf_foto">
                                    <?if($jo_codi_img[1]['img']):?>
                                    <input type="hidden" name="jo_codi_img_img[1]"  value="<?=$jo_codi_img[1]['img']?>"> 
                                    <?endif?>
                                    <img style="margin: 0 auto; display: block;" id="codi_pf_foto_img" <?if($jo_codi_img[1]['img']):?>  src="<?=G5_URL?>/data/new_goods/<?=$jo_codi_img[1]['img']?>"  <?endif?> >
                                    <input onfocus="Vail_fixed(this)" class="noborder txt_center txt_op emptyImg" name="jo_codi_img_text[1]" value="<?=$jo_codi_img[1]['text']?>">
                                </div>
                            </td>
                            <td rowspan="2" colspan="2" class="txt_center">
                            <input type='file' name ="jo_sub_img_img" id='verborgen_file_sub' />
                                <div id="sub_pf_foto">
                                    <?if($jo_sub_img[1]['img']):?>
                                    <input type="hidden" name="jo_sub_img_img[1]"  value="<?=$jo_sub_img[1]['img']?>"> 
                                    <?endif?>
                                    <img style="margin: 0 auto; display: block;" id="sub_pf_foto_img" <?if($jo_sub_img[1]['img']):?> src="<?=G5_URL?>/data/new_goods/<?=$jo_sub_img[1]['img']?>" <?endif?>>
                                    <input onfocus="Vail_fixed(this)" class="noborder txt_center txt_op emptyImg" name="jo_sub_img_text[1]" value="<?=$jo_sub_img[1]['text']?>">
                                </div>
                            </td>
                        </tr>
                        <tr class="cahei">
                            <th colspan="2">기호/규격</th>
                            <td>
                                <select onfocus="Vail_fixed(this)" class="noborder jo_select" name="jo_size_code" id="jo_size_code" required>
                                    <option value="" <?= $create_type != 'copy' ?   ($jo['jo_size_code'] == '' ? "selected" : "") : '' ?>>선택</option>
                                    <option value="S" <?= $create_type != 'copy' ?  $jo['jo_size_code'] == 'S' ? "selected" : "" : '' ?>>S</option>
                                    <option value="SS" <?=$create_type != 'copy' ?  $jo['jo_size_code'] == 'SS' ? "selected" : "": ''  ?>>SS</option>
                                    <option value="D" <?=$create_type != 'copy' ?  $jo['jo_size_code'] == 'D' ? "selected" : "" : '' ?>>D</option>
                                    <option value="Q" <?=$create_type != 'copy' ?  $jo['jo_size_code'] == 'Q' ? "selected" : "" : '' ?>>Q</option>
                                    <option value="K" <?=$create_type != 'copy' ?  $jo['jo_size_code'] == 'K' ? "selected" : "" : ''  ?>>K</option>
                                    <option value="L" <?=$create_type != 'copy' ?  $jo['jo_size_code'] == 'L' ? "selected" : "" : '' ?>>L</option>
                                    <option value="MS" <?=$create_type != 'copy' ?  $jo['jo_size_code'] == 'MS' ? "selected" : "" : '' ?>>MS</option>
                                    <option value="MSS" <?=$create_type != 'copy' ?  $jo['jo_size_code'] == 'MSS' ? "selected" : "" : '' ?>>MSS</option>
                                    <option value="MD" <?=$create_type != 'copy' ?  $jo['jo_size_code'] == 'MD' ? "selected" : "" : '' ?>>MD</option>
                                    <option value="SK" <?=$create_type != 'copy' ?  $jo['jo_size_code'] == 'SK' ? "selected" : "" : '' ?>>SK</option>
                                    <option value="MSK" <?=$create_type != 'copy' ?  $jo['jo_size_code'] == 'MSK' ? "selected" : "" : '' ?>>MSK</option>
                                </select>
                            </td>
                            <td><div class="txt_center">가로<span id="noprint">*</span></div><input onfocus="Vail_fixed(this)" class="noborder txt_center" name="jo_size_wid" value="<?=$jo['jo_size_wid']?>" required> </td>
                            <td><div class="txt_center">세로<span id="noprint">*</span></div><input onfocus="Vail_fixed(this)" class="noborder txt_center" name="jo_size_verti" value="<?=$jo['jo_size_verti']?>" required></td>
                            <td><div class="txt_center">높이</div><input onfocus="Vail_fixed(this)" class="noborder txt_center" name="jo_size_hei" value="<?=$jo['jo_size_hei']?>"> </td>
                            <th colspan="2">컬러</th>
                            <td colspan="2">
                                <select onfocus="Vail_fixed(this)" class="noborder jo_select" name="jo_color" id="jo_color" required>
                                    <option value="AA" <?= $jo['jo_color'] == 'AA' ? "selected" : "" ?>>AA(기타)</option>
                                    <option value="BE" <?= $jo['jo_color'] == 'BE' ? "selected" : "" ?>>BE(베이지)</option>
                                    <option value="BK" <?= $jo['jo_color'] == 'BK' ? "selected" : "" ?>>BK(블랙)</option>
                                    <option value="BL" <?= $jo['jo_color'] == 'BL' ? "selected" : "" ?>>BL(블루)</option>
                                    <option value="BR" <?= $jo['jo_color'] == 'BR' ? "selected" : "" ?>>BR(브라운)</option>
                                    <option value="CR" <?= $jo['jo_color'] == 'CR' ? "selected" : "" ?>>CR(크림)</option>
                                    <option value="DB" <?= $jo['jo_color'] == 'DB' ? "selected" : "" ?>>DB(진블루)</option>
                                    <option value="DP" <?= $jo['jo_color'] == 'DP' ? "selected" : "" ?>>DP(진핑크)</option>
                                    <option value="FC" <?= $jo['jo_color'] == 'FC' ? "selected" : "" ?>>FC(푸시아)</option>
                                    <option value="GD" <?= $jo['jo_color'] == 'GD' ? "selected" : "" ?>>GD(골드)</option>
                                    <option value="GN" <?= $jo['jo_color'] == 'GN' ? "selected" : "" ?>>GN(그린)</option>
                                    <option value="GR" <?= $jo['jo_color'] == 'GR' ? "selected" : "" ?>>GR(그레이)</option>
                                    <option value="IV" <?= $jo['jo_color'] == 'IV' ? "selected" : "" ?>>IV(아이보리)</option>
                                    <option value="KA" <?= $jo['jo_color'] == 'KA' ? "selected" : "" ?>>KA(카키)</option>
                                    <option value="LB" <?= $jo['jo_color'] == 'LB' ? "selected" : "" ?>>LB(연블루)</option>
                                    <option value="LG" <?= $jo['jo_color'] == 'LG' ? "selected" : "" ?>>LG(연그레이)</option>
                                    <option value="LP" <?= $jo['jo_color'] == 'LP' ? "selected" : "" ?>>LP(연핑크)</option>
                                    <option value="LV" <?= $jo['jo_color'] == 'LV' ? "selected" : "" ?>>LV(라벤다)</option>
                                    <option value="MT" <?= $jo['jo_color'] == 'MT' ? "selected" : "" ?>>MT(민트)</option>
                                    <option value="MU" <?= $jo['jo_color'] == 'MU' ? "selected" : "" ?>>MU(멀티)</option>
                                    <option value="MV" <?= $jo['jo_color'] == 'MV' ? "selected" : "" ?>>MV(모브)</option>
                                    <option value="MX" <?= $jo['jo_color'] == 'MX' ? "selected" : "" ?>>MX(혼합)</option>
                                    <option value="NC" <?= $jo['jo_color'] == 'NC' ? "selected" : "" ?>>NC(내츄럴)</option>
                                    <option value="NV" <?= $jo['jo_color'] == 'NV' ? "selected" : "" ?>>NV(네이비)</option>
                                    <option value="OR" <?= $jo['jo_color'] == 'OR' ? "selected" : "" ?>>OR(오렌지)</option>
                                    <option value="PC" <?= $jo['jo_color'] == 'PC' ? "selected" : "" ?>>PC(청록)</option>
                                    <option value="PK" <?= $jo['jo_color'] == 'PK' ? "selected" : "" ?>>PK(핑크)</option>
                                    <option value="PU" <?= $jo['jo_color'] == 'PU' ? "selected" : "" ?>>PU(퍼플)</option>
                                    <option value="RD" <?= $jo['jo_color'] == 'RD' ? "selected" : "" ?>>RD(레드)</option>
                                    <option value="WH" <?= $jo['jo_color'] == 'WH' ? "selected" : "" ?>>WH(화이트)</option>
                                    <option value="YE" <?= $jo['jo_color'] == 'YE' ? "selected" : "" ?>>YE(노랑)</option>
                                    <option value="DG" <?= $jo['jo_color'] == 'DG' ? "selected" : "" ?>>DG(딥그레이)</option>
                                    <option value="CO" <?= $jo['jo_color'] == 'CO' ? "selected" : "" ?>>CO(코랄)</option>
                                </select>
                            </td>
                            <td></td>
                            <th colspan="2">담당자</th>
                            <td colspan="4"><input onfocus="Vail_fixed(this)" class="noborder txt_center" name="jo_user" value="<?=$jo['jo_user'] ? $jo['jo_user'] : $member['mb_name']?>"></td>
                            
                        </tr>

                        <tr>
                            <td colspan="10"></td>
                            <td></td>
                            <td colspan="13">■ 원자재 정보</td>
                        </tr>
                        
                        
                        <tr>
                            <td rowspan="1"  colspan = "10" >
                                <input type='file' id="imgInp" name ="jo_design_img" />
                                <div style="margin : 0 auto; max-width: 590px;">
                                    <?
                                    $img_type = explode('.' , $jo['jo_design_img']);
                                    ?>
                                    <a class="down_img" href="<?=G5_URL?>/data/new_goods/<?=$jo['jo_design_img']?>" download="<?=$jo['jo_it_name']?>_원자재정보.<?=$img_type[1]?>">
                                        <img id="blah" style="margin: 0 auto; display: block;" src="<?=G5_URL?>/data/new_goods/<?=$jo['jo_design_img']?>" alt="your image" />
                                    </a>
                                </div>
                            </td>
                            <td id="noline_td" colspan = "14">
                                <table id="noline_new_goods_table" style="width : 100%;">
                                    <colgroup>
                                        <col width="0px"/>
                                        <col width="31px"/>
                                        <col width="55px"/>
                                        <col width="31px"/>
                                        <col width="71px"/>
                                        <col width="31px"/>
                                        <col width="45px"/>
                                        <col width="82px"/>
                                        <col width="20px"/>
                                        <col width="55px"/>
                                        <col width="81px"/>
                                        <col width="72px"/>
                                        <col width="50px"/>
                                        <col width="78px"/>
                                    </colgroup>
                                    <tr>
                                        <td class="noline_left noline_top"></td>
                                        <th colspan = "4">항목</th>
                                        <th colspan = "2">규격(")</th>
                                        <th colspan="3">재단SIZE (폭+폭)x길이cm</th>
                                        <th>소요량(yd)</th>
                                        <th colspan = "2">단가(별도)</th>
                                        <th>원가</th>
                                    </tr>
                                    <tbody id="jo_mater_info_area">
                                    <?if (!empty($jo_mater_info)) :?>
                                    <?php foreach ($jo_mater_info as $jm => $mater_info) : ?>
                                        <tr class="btn-add-mater mater_<?=$jm?>" data-item-idx=<?=$jm?> >
                                            <td class="noline_left noline_top">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_mater_info[<?=$jm?>]">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_mater_info_no[<?=$jm?>]" value = "<?=$jm?>">
                                            </td>
                                            <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_mater_info_title[<?=$jm?>]" value="<?=$mater_info['title']?>"></td>
                                            <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_size[<?=$jm?>]"  value="<?=stripslashes($mater_info['size'])?>"></td>
                                            <?php if ($jm == 3 || $jm == 4) : ?>
                                            <td>
                                                <!-- <input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_hei[<?=$jm?>]" id="jo_mater_info_hei_<?=$jm?>" data-mater-idx="<?=$jm?>"  value="<?=$mater_info['hei']?>"> -->
                                            </td>
                                            <td class="txt_center">x</td>
                                            <td>
                                                <!-- <input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_length[<?=$jm?>]" id="jo_mater_info_length_<?=$jm?>" data-mater-idx="<?=$jm?>"  value="<?=$mater_info['length']?>"> -->
                                            </td>
                                            <?else:?>
                                            <td>
                                                <!-- <input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_hei[<?=$jm?>]" id="jo_mater_info_hei_<?=$jm?>" data-mater-idx="<?=$jm?>" onblur="yochek_cal1(this)" value="<?=$mater_info['hei']?>"> -->
                                            </td>
                                            <td class="txt_center">x</td>
                                            <td>
                                                <!-- <input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_length[<?=$jm?>]" id="jo_mater_info_length_<?=$jm?>" data-mater-idx="<?=$jm?>" onblur="yochek_cal1(this)" value="<?=$mater_info['length']?>"> -->
                                            </td>
                                            <?endif?>
                                            <?php if ($jm == 1) : ?>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_yd[<?=$jm?>]" id="jo_mater_info_yd_<?=$jm?>" data-mater-idx="<?=$jm?>" onblur="ex_mater_math1(this)" value="<?=$mater_info['yd']?>" ></td>
                                                <td colspan = "2">
                                                    <select onfocus="Vail_fixed(this)" class="noborder jo_select exchange_select" name="jo_mater_info_danga_exchange[1]"  id ="exchange_select_1" data-mater-idx="1">
                                                        <option value="KRW" <?= $mater_info['exchange'] == 'KRW' ? "selected" : "" ?>>KRW</option>
                                                        <option value="USD" <?= $mater_info['exchange'] == 'USD' ? "selected" : "" ?>>USD</option>
                                                        <option value="CNY" <?= $mater_info['exchange'] == 'CNY' ? "selected" : "" ?>>CNY</option>
                                                        <option value="JPY" <?= $mater_info['exchange'] == 'JPY' ? "selected" : "" ?>>JPY</option>
                                                        <option value="EUR" <?= $mater_info['exchange'] == 'EUR' ? "selected" : "" ?>>EUR</option>
                                                    </select>
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_right exchange_input print_hidden" name="jo_mater_info_danga[<?=$jm?>]" id="jo_mater_info_danga_<?=$jm?>" data-mater-idx="<?=$jm?>" onblur="ex_mater_math1(this)" value="<?=$mater_info['danga']?>">
                                                </td>
                                            <? elseif ($jm == 2) :?>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_yd[<?=$jm?>]" id="jo_mater_info_yd_<?=$jm?>" data-mater-idx="<?=$jm?>" onblur="mater_math1(this)" value="<?=$mater_info['yd']?>" ></td>
                                                <td colspan = "2">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_right print_hidden" name="jo_mater_info_danga[<?=$jm?>]" id="jo_mater_info_danga_<?=$jm?>" data-mater-idx="<?=$jm?>" onblur="mater_math1(this)" value="<?=$mater_info['danga'] ? number_format($mater_info['danga']): ''?>">
                                                </td>
                                            <?elseif ($jm == 3) :?>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder noborder80" name="jo_mater_info_yd[3]" id="jo_mater_info_yd_3" data-mater-idx="3" onblur="unim_math(this)" value="<?=$mater_info['yd']?>" >%</td>
                                                <td colspan = "2">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_right print_hidden" name="jo_mater_info_danga[<?=$jm?>]" id="jo_mater_info_danga_<?=$jm?>" data-mater-idx="<?=$jm?>"  value="">
                                                </td>
                                            <?elseif ($jm == 4) :?>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder noborder80" name="jo_mater_info_yd[4]" id="jo_mater_info_yd_4" data-mater-idx="4" onblur="kanse_math(this)" value="<?=$mater_info['yd']?>" >%</td>
                                                <td colspan = "2">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_right print_hidden" name="jo_mater_info_danga[<?=$jm?>]" id="jo_mater_info_danga_<?=$jm?>" data-mater-idx="<?=$jm?>"  value="">
                                                </td>
                                            <?else:?>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_yd[<?=$jm?>]" id="jo_mater_info_yd_<?=$jm?>" data-mater-idx="<?=$jm?>" onblur="mater_math1(this)" value="<?=$mater_info['yd']?>" ></td>
                                                <td colspan = "2">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_right print_hidden" name="jo_mater_info_danga[<?=$jm?>]" id="jo_mater_info_danga_<?=$jm?>" data-mater-idx="<?=$jm?>" onblur="mater_math1(this)" value="<?=$mater_info['danga'] ? number_format($mater_info['danga']): ''?>">
                                                </td>
                                            <?endif?>
                                            
                                            <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_mater_info_price print_hidden" name="jo_mater_info_price[<?=$jm?>]" id="jo_mater_info_price_<?=$jm?>" data-mater-idx="<?=$jm?>" value="<?=$mater_info['price'] ? number_format($mater_info['price']) : ''?>"></td>
                                        </tr>
                                    <?php endforeach ?>
                                    <?else:
                                    $jm = 4;
                                    ?>
                                        <tr class="btn-add-mater mater_1" data-item-idx=1 >
                                            <td class="noline_left noline_top">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_mater_info[1]">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_mater_info_no[1]" value = "1">
                                            </td>
                                            <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_mater_info_title[1]" value="반제피/80수 반제피(항균)"></td>
                                            <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_size[1]"  value=""></td>
                                            <td>
                                                <!-- <input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_hei[1]" id="jo_mater_info_hei_1" data-mater-idx="1" onblur="yochek_cal1(this)" value=""> -->
                                            </td>
                                            <td class="txt_center"></td>
                                            <td>
                                                <!-- <input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_length[1]" id="jo_mater_info_length_1" data-mater-idx="1" onblur="yochek_cal1(this)" value=""> -->
                                            </td>
                                            <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_yd[1]" id="jo_mater_info_yd_1" data-mater-idx="1" onblur="ex_mater_math1(this)" value="1.0" ></td>
                                            <td colspan = "2">
                                                <select onfocus="Vail_fixed(this)" class="noborder jo_select exchange_select" name="jo_mater_info_danga_exchange[1]" id ="exchange_select_1" data-mater-idx="1">
                                                    <option value="KRW">KRW</option>
                                                    <option value="USD">USD</option>
                                                    <option value="CNY">CNY</option>
                                                    <option value="JPY">JPY</option>
                                                    <option value="EUR">EUR</option>
                                                </select>
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_right exchange_input print_hidden" name="jo_mater_info_danga[1]" id="jo_mater_info_danga_1" data-mater-idx="1" onblur="ex_mater_math1(this)" value="">
                                            </td>
                                            <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_mater_info_price print_hidden" name="jo_mater_info_price[1]" id="jo_mater_info_price_1" data-mater-idx="1"  onblur="ex_mater_math1(this)" value=""></td>
                                        </tr>
                                        <tr class="btn-add-mater mater_2" data-item-idx=2 >
                                            <td class="noline_left noline_top">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_mater_info[2]">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_mater_info_no[2]" value = "2">
                                            </td>
                                            <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_mater_info_title[2]" value="코너라벨"></td>
                                            <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_size[2]"  value=""></td>
                                            <td>
                                                <!-- <input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_hei[2]" id="jo_mater_info_hei_2" data-mater-idx="2" onblur="yochek_cal1(this)" value=""> -->
                                            </td>
                                            <td class="txt_center"></td>
                                            <td>
                                                <!-- <input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_length[2]" id="jo_mater_info_length_2" data-mater-idx="2" onblur="yochek_cal1(this)" value=""> -->
                                            </td>
                                            <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_yd[2]" id="jo_mater_info_yd_2" data-mater-idx="2" onblur="mater_math1(this)" value="1.0" ></td>
                                            <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder txt_right print_hidden" name="jo_mater_info_danga[2]" id="jo_mater_info_danga_2" data-mater-idx="2" onblur="mater_math1(this)" value=""></td>
                                            <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_mater_info_price print_hidden" name="jo_mater_info_price[2]" id="jo_mater_info_price_2" data-mater-idx="2" onblur="mater_math1(this)" value=""></td>
                                        </tr>
                                        <tr class="btn-add-mater mater_3" data-item-idx=3 >
                                            <td class="noline_left noline_top">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_mater_info[3]">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_mater_info_no[3]" value = "3">
                                            </td>
                                            <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_mater_info_title[3]" value="운임"></td>
                                            <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_size[3]"  value=""></td>
                                            <td>
                                                <!-- <input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_hei[3]" id="jo_mater_info_hei_3" data-mater-idx="3" value=""> -->
                                            </td>
                                            <td class="txt_center"></td>
                                            <td>
                                                <!-- <input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_length[3]" id="jo_mater_info_length_3" data-mater-idx="3" value=""> -->
                                            </td>
                                            <td><input onfocus="Vail_fixed(this)" class="noborder noborder80" name="jo_mater_info_yd[3]" id="jo_mater_info_yd_3" data-mater-idx="3" onblur="unim_math(this)" value="" >%</td>
                                            <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder txt_right print_hidden" name="jo_mater_info_danga[3]" id="jo_mater_info_danga_3" data-mater-idx="3"  value=""></td>
                                            <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_mater_info_price print_hidden" name="jo_mater_info_price[3]" id="jo_mater_info_price_3" data-mater-idx="3" value=""></td>
                                        </tr>
                                        <tr class="btn-add-mater mater_4" data-item-idx=4 >
                                            <td class="noline_left noline_top">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_mater_info[4]">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_mater_info_no[4]" value = "4">
                                            </td>
                                            <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_mater_info_title[4]" value="관세"></td>
                                            <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_size[4]"  value=""></td>
                                            <td>
                                                <!-- <input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_hei[4]" id="jo_mater_info_hei_4" data-mater-idx="4"  value=""> -->
                                            </td>
                                            <td class="txt_center"></td>
                                            <td>
                                                <!-- <input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_length[4]" id="jo_mater_info_length_4" data-mater-idx="4"  value=""> -->
                                            </td>
                                            <td><input onfocus="Vail_fixed(this)" class="noborder noborder80" name="jo_mater_info_yd[4]" id="jo_mater_info_yd_4" data-mater-idx="4" onblur="kanse_math(this)" value="" >%</td>
                                            <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder txt_right print_hidden" name="jo_mater_info_danga[4]" id="jo_mater_info_danga_4" data-mater-idx="4"  value=""></td>
                                            <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_mater_info_price print_hidden" name="jo_mater_info_price[4]" id="jo_mater_info_price_4" data-mater-idx="4" value=""></td>
                                        </tr>
                                        
                                    <?endif?>
                                    <?if($jm < 5):?>
                                        <?
                                        if($jm < 1) { 
                                            $cnt_mater = 1;
                                        }else{
                                            $cnt_mater = $jm+1;
                                        }
                                        ?>

                                        <?for($maidx = $cnt_mater; $maidx < 7 ; $maidx++) : ?>
                                            <tr class="btn-add-mater mater_<?=$maidx?>" data-item-idx=<?=$maidx?> >
                                                <td class="noline_left noline_top">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_mater_info[<?=$maidx?>]">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_mater_info_no[<?=$maidx?>]" value = "<?=$maidx?>">
                                                </td>
                                                <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_mater_info_title[<?=$maidx?>]" value=""></td>
                                                <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_size[<?=$maidx?>]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_hei[<?=$maidx?>]" id="jo_mater_info_hei_<?=$maidx?>" data-mater-idx="<?=$maidx?>" onblur="yochek_cal1(this)" value=""></td>
                                                <td class="txt_center"></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_length[<?=$maidx?>]" id="jo_mater_info_length_<?=$maidx?>" data-mater-idx="<?=$maidx?>" onblur="yochek_cal1(this)" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_yd[<?=$maidx?>]" id="jo_mater_info_yd_<?=$maidx?>" data-mater-idx="<?=$maidx?>" onblur="mater_math1(this)" value=""></td>
                                                <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder txt_right print_hidden" name="jo_mater_info_danga[<?=$maidx?>]" id="jo_mater_info_danga_<?=$maidx?>" data-mater-idx="<?=$maidx?>" onblur="mater_math1(this)" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_mater_info_price print_hidden" name="jo_mater_info_price[<?=$maidx?>]" id="jo_mater_info_price_<?=$maidx?>" onblur="mater_math1(this)" value=""></td>
                                            </tr>
                                        <?endfor?>
                                    <?endif?>
                                    </tbody>
                                    <tbody id="jo_maip_price_area">
                                    <tr>
                                        <td class="noline_left noline_top dotted"></td>
                                        <th colspan = "4" class="dotted">반제피 원가 적용</th>
                                        <!-- <th colspan = "8"></th> -->
                                        <th colspan = "2" class="dotted"></th>
                                        <th class="dotted"></th>
                                        <th class="dotted"></th>
                                        <th class="dotted"></th>
                                        <th class="dotted"></th>
                                        <th colspan = "2" class="dotted"></th>

                                        <th class="txt_right dotted">
                                            
                                            <?php foreach ($jo_mater_info as $sjm => $smater_info) {
                                                $total_mater_price += $smater_info['price'];
                                            } ?> 
                                            <span class="func_th_view print_hidden" id ="total_mater_price_view"><?=number_format($total_mater_price)?></span>
                                            <input type="hidden" class="noborder_th txt_right func_th print_hidden" id ="total_mater_price" name ="total_mater_price" onblur="comma_input(this)" value ="<?=number_format($total_mater_price)?>" > 
                                        </th>
                                    </tr>
                                    <tr class="btn-add-mater-price mater_price_1" data-item-idx=1 >
                                        <td class="noline_left noline_top">
                                            <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_maip_price[1]">
                                            <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_maip_price_no[1]" value = "1">
                                        </td>
                                        <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_maip_price_title[1]" value="<?=$jo_maip_price[1]['title']?>"></td>
                                        <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_maip_price_size[1]"  value="<?=stripslashes($jo_maip_price[1]['size'])?>"></td>
                                        <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_maip_price_hei[1]" id="jo_maip_price_hei_1" data-price-idx="1" onblur="yochek_cal1(this)" value="<?=$jo_maip_price[1]['hei']?>"></td>
                                        <td class="txt_center"></td>
                                        <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_maip_price_length[1]" id="jo_maip_price_length_1" data-price-idx="1" onblur="yochek_cal1(this)" value="<?=$jo_maip_price[1]['length']?>"></td>
                                        <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_maip_price_yd[1]" id="jo_maip_price_yd_1" data-price-idx="1" onblur="mater_price_math(this)" value="<?=$jo_maip_price[1]['yd']?>" ></td>
                                        <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder txt_right print_hidden" name="jo_maip_price_danga[1]" id="jo_maip_price_danga_1" data-price-idx="1" onblur="mater_price_math(this)" value="<?=$jo_maip_price[1]['danga'] ? number_format($jo_maip_price[1]['danga']) : ''?>"></td>
                                        <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_maip_price_price print_hidden" name="jo_maip_price_price[1]" id="jo_maip_price_price_1" data-price-idx="1" onblur="mater_price_math(this)" value="<?=$jo_maip_price[1]['price'] ? number_format($jo_maip_price[1]['price']) : ''?>"></td>
                                    </tr>
                                    <tr class="btn-add-mater-price mater_price_2" data-item-idx=2 >
                                        <td class="noline_left noline_top">
                                            <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_maip_price[2]">
                                            <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_maip_price_no[2]" value = "2">
                                        </td>
                                        <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_maip_price_title[2]" value="<?=$jo_maip_price[2]['title']?>"></td>
                                        <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_maip_price_size[2]"  value="<?=stripslashes($jo_maip_price[2]['size'])?>"></td>
                                        <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_maip_price_hei[2]" id="jo_maip_price_hei_2" data-price-idx="2" onblur="yochek_cal1(this)" value="<?=$jo_maip_price[2]['hei']?>"></td>
                                        <td class="txt_center"></td>
                                        <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_maip_price_length[2]" id="jo_maip_price_length_2" data-price-idx="2" onblur="yochek_cal1(this)" value="<?=$jo_maip_price[2]['length']?>"></td>
                                        <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_maip_price_yd[2]" id="jo_maip_price_yd_2" data-price-idx="2" onblur="mater_price_math(this)" value="<?=$jo_maip_price[2]['yd']?>" ></td>
                                        <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder txt_right print_hidden" name="jo_maip_price_danga[2]" id="jo_maip_price_danga_2" data-price-idx="2" onblur="mater_price_math(this)" value="<?=$jo_maip_price[2]['danga'] ? number_format($jo_maip_price[2]['danga']) : ''?>"></td>
                                        <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_maip_price_price print_hidden" name="jo_maip_price_price[2]" id="jo_maip_price_price_2" data-price-idx="2" onblur="mater_price_math(this)" value="<?=$jo_maip_price[2]['price'] ? number_format($jo_maip_price[2]['price']) : ''?>"></td>
                                    </tr>

                                    </tbody>
                                    <tr>
                                        <td class="noline_left noline_top"></td>
                                        <th colspan = "4">원자재 계</th>
                                        <th colspan = "8"></th>
                                        <th class="txt_right">
                                            
                                            <?php
                                            // foreach ($jo_mater_info as $sjm => $smater_info) {
                                            //     $total_mater_price += $smater_info['price'];
                                            // } 
                                            $total_mater_price_fin = $total_mater_price + ( $jo_maip_price[1]['price'] + $jo_maip_price[2]['price'] );
                                            ?> 
                                            <span class="func_th_view print_hidden" id ="total_mater_price_view_fin"><?=number_format($total_mater_price_fin)?></span>
                                            <input type="hidden" class="noborder_th txt_right func_th print_hidden" id ="total_mater_price_fin" onblur="comma_input(this)" value ="<?=number_format($total_mater_price_fin)?>" > 
                                        </th>
                                    </tr>

                                    

                                    <tbody id="jo_sub_mater_info_area">
                                        <?if (!empty($jo_sub_mater)) :?>
                                        <?php foreach ($jo_sub_mater as $jsm => $sub_mater) : ?>
                                            <tr class="btn-add-sub-mater sub-mater_<?=$jsm?>" data-item-idx=<?=$jsm?>>
                                                <td class="noline_left noline_top">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_sub_mater_info[<?=$jsm?>]">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_sub_mater_info_no[<?=$jsm?>]" value = "<?=$jsm?>">
                                                </td>
                                                <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_sub_mater_info_title[<?=$jsm?>]" value="<?=$sub_mater['title']?>"></td>
                                                <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_size[<?=$jsm?>]" value="<?=stripslashes($sub_mater['size'])?>"></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_hei[<?=$jsm?>]" value="<?=$sub_mater['hei']?>"></td>
                                                <td></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_length[<?=$jsm?>]" value="<?=$sub_mater['length']?>"></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_yd[<?=$jsm?>]" id="jo_sub_mater_info_yd_<?=$jsm?>" data-sub-mater="<?=$jsm?>" onblur="sub_mater_math(this)" value="<?=$sub_mater['yd']?>"></td>
                                                <td colspan = "2">
                                                    <select onfocus="Vail_fixed(this)" class="noborder jo_select per_select" name="jo_sub_mater_info_danga_per[<?=$jsm?>]" id ="per_select_<?=$jsm?>" data-sub-mater="<?=$jsm?>">
                                                        <option value="" <?= $sub_mater['danga_per'] == "" ? "selected" : "" ?>>기본</option>
                                                        <option value="1.2" <?= $sub_mater['danga_per'] == 1.2 ? "selected" : "" ?>>20%</option>
                                                    </select>
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_right exchange_input print_hidden" name="jo_sub_mater_info_danga[<?=$jsm?>]" id="jo_sub_mater_info_danga_<?=$jsm?>" data-sub-mater="<?=$jsm?>" onblur="sub_mater_math(this)" value="<?=$sub_mater['danga'] ? number_format((int)$sub_mater['danga']) : '-'?>">
                                                </td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_sub_mater_info_price print_hidden" name="jo_sub_mater_info_price[<?=$jsm?>]" id="jo_sub_mater_info_price_<?=$jsm?>" data-sub-mater="<?=$jsm?>" onblur="sub_mater_math(this)" value="<?=$sub_mater['price'] ? number_format($sub_mater['price']) : '0'?>"></td>
                                            </tr>
                                        <?php endforeach ?>
                                        <?else:?>
                                            <tr class="btn-add-sub-mater sub-mater_1" data-item-idx=1>
                                                <td class="noline_left noline_top">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_sub_mater_info[1]">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_sub_mater_info_no[1]" value = "1">
                                                </td>
                                                <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_sub_mater_info_title[1]" value="소프라움 전사라벨"></td>
                                                <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_size[1]" value="소프라움직조"></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_hei[1]" value=""></td>
                                                <td></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_length[1]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_yd[1]" id="jo_sub_mater_info_yd_1" data-sub-mater="1" onblur="sub_mater_math(this)" value="1.0"></td>
                                                <td colspan = "2">
                                                    <select onfocus="Vail_fixed(this)" class="noborder jo_select per_select" name="jo_sub_mater_info_danga_per[1]" id ="per_select_1" data-sub-mater="1">
                                                        <option value="">기본</option>
                                                        <option value="1.2">20%</option>
                                                    </select>
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_right exchange_input print_hidden" id="jo_sub_mater_info_danga_1" data-sub-mater="1" onblur="sub_mater_math(this)" name="jo_sub_mater_info_danga[1]" value="330">
                                                </td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_sub_mater_info_price print_hidden" name="jo_sub_mater_info_price[1]" id="jo_sub_mater_info_price_1" data-sub-mater="1"  value="330"></td>
                                            </tr>
                                            <tr class="btn-add-sub-mater sub-mater_2" data-item-idx=2>
                                                <td class="noline_left noline_top">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_sub_mater_info[2]">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_sub_mater_info_no[2]" value = "2">
                                                </td>
                                                <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_sub_mater_info_title[2]" value="소프라움 사이드구스라벨"></td>
                                                <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_size[2]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_hei[2]" value=""></td>
                                                <td></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_length[2]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_yd[2]" id="jo_sub_mater_info_yd_2" data-sub-mater="2" onblur="sub_mater_math(this)" value="1.0"></td>
                                                <td colspan = "2">
                                                    <select onfocus="Vail_fixed(this)" class="noborder jo_select per_select" name="jo_sub_mater_info_danga_per[2]" id ="per_select_2" data-sub-mater="2">
                                                        <option value="">기본</option>
                                                        <option value="1.2">20%</option>
                                                    </select>
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_right exchange_input print_hidden" name="jo_sub_mater_info_danga[2]" id="jo_sub_mater_info_danga_2" data-sub-mater="2" onblur="sub_mater_math(this)" value="380">
                                                </td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_sub_mater_info_price print_hidden" name="jo_sub_mater_info_price[2]" id="jo_sub_mater_info_price_2" data-sub-mater="2" onblur="sub_mater_math(this)" value="380"></td>
                                            </tr>
                                            <tr class="btn-add-sub-mater sub-mater_3" data-item-idx=3>
                                                <td class="noline_left noline_top">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_sub_mater_info[3]">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_sub_mater_info_no[3]" value = "3">
                                                </td>
                                                <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_sub_mater_info_title[3]" value="프라우덴라벨"></td>
                                                <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_size[3]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_hei[3]" value=""></td>
                                                <td></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_length[3]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_yd[3]" id="jo_sub_mater_info_yd_3" data-sub-mater="3" onblur="sub_mater_math(this)" value="1.0"></td>
                                                <td colspan = "2">
                                                    <select onfocus="Vail_fixed(this)" class="noborder jo_select per_select" name="jo_sub_mater_info_danga_per[3]" id ="per_select_3" data-sub-mater="3">
                                                        <option value="">기본</option>
                                                        <option value="1.2">20%</option>
                                                    </select>
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_right exchange_input print_hidden" name="jo_sub_mater_info_danga[3]" id="jo_sub_mater_info_danga_3" data-sub-mater="3" onblur="sub_mater_math(this)" value="-">
                                                </td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_sub_mater_info_price print_hidden" name="jo_sub_mater_info_price[3]" id="jo_sub_mater_info_price_3" data-sub-mater="3" onblur="sub_mater_math(this)" value="0"></td>
                                            </tr>
                                            <tr class="btn-add-sub-mater sub-mater_4" data-item-idx=4>
                                                <td class="noline_left noline_top">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_sub_mater_info[4]">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_sub_mater_info_no[4]" value = "4">
                                                </td>
                                                <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_sub_mater_info_title[4]" value="품질표시라벨"></td>
                                                <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_size[4]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_hei[4]" value=""></td>
                                                <td></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_length[4]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_yd[4]" id="jo_sub_mater_info_yd_4" data-sub-mater="4" onblur="sub_mater_math(this)" value="1.0"></td>
                                                <td colspan = "2">
                                                    <select onfocus="Vail_fixed(this)" class="noborder jo_select per_select" name="jo_sub_mater_info_danga_per[4]" id ="per_select_4" data-sub-mater="4">
                                                        <option value="">기본</option>
                                                        <option value="1.2">20%</option>
                                                    </select>    
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_right exchange_input print_hidden" name="jo_sub_mater_info_danga[4]" id="jo_sub_mater_info_danga_4" data-sub-mater="4" onblur="sub_mater_math(this)" value="80">
                                                </td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_sub_mater_info_price print_hidden" name="jo_sub_mater_info_price[4]" id="jo_sub_mater_info_price_4" data-sub-mater="4" onblur="sub_mater_math(this)" value="80"></td>
                                            </tr>
                                            <tr class="btn-add-sub-mater sub-mater_5" data-item-idx=5>
                                                <td class="noline_left noline_top">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_sub_mater_info[5]">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_sub_mater_info_no[5]" value = "5">
                                                </td>
                                                <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_sub_mater_info_title[5]" value="RDS 라벨"></td>
                                                <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_size[5]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_hei[5]" value=""></td>
                                                <td></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_length[5]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_yd[5]" id="jo_sub_mater_info_yd_5" data-sub-mater="5" onblur="sub_mater_math(this)" value="1.0"></td>
                                                <td colspan = "2">
                                                    <select onfocus="Vail_fixed(this)" class="noborder jo_select per_select" name="jo_sub_mater_info_danga_per[5]" id ="per_select_5" data-sub-mater="5">
                                                        <option value="">기본</option>
                                                        <option value="1.2">20%</option>
                                                    </select>
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_right exchange_input print_hidden" name="jo_sub_mater_info_danga[5]" id="jo_sub_mater_info_danga_5" data-sub-mater="5" onblur="sub_mater_math(this)" value="42">
                                                </td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_sub_mater_info_price print_hidden" name="jo_sub_mater_info_price[5]" id="jo_sub_mater_info_price_5" data-sub-mater="5" onblur="sub_mater_math(this)" value="42"></td>
                                            </tr>
                                            <tr class="btn-add-sub-mater sub-mater_6" data-item-idx=6>
                                                <td class="noline_left noline_top">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_sub_mater_info[6]">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_sub_mater_info_no[6]" value = "6">
                                                </td>
                                                <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_sub_mater_info_title[6]" value="웨코텍스 라벨"></td>
                                                <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_size[6]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_hei[6]" value=""></td>
                                                <td></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_length[6]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_yd[6]" id="jo_sub_mater_info_yd_6" data-sub-mater="6" onblur="sub_mater_math(this)" value="1.0"></td>
                                                <td colspan = "2">
                                                    <select onfocus="Vail_fixed(this)" class="noborder jo_select per_select" name="jo_sub_mater_info_danga_per[6]" id ="per_select_6" data-sub-mater="6">
                                                        <option value="">기본</option>
                                                        <option value="1.2">20%</option>
                                                    </select>    
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_right exchange_input print_hidden" name="jo_sub_mater_info_danga[6]" id="jo_sub_mater_info_danga_6" data-sub-mater="6" onblur="sub_mater_math(this)" value="-">
                                                </td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_sub_mater_info_price print_hidden" name="jo_sub_mater_info_price[6]" id="jo_sub_mater_info_price_6" data-sub-mater="6" onblur="sub_mater_math(this)" value="0"></td>
                                            </tr>
                                            <tr class="btn-add-sub-mater sub-mater_7" data-item-idx=7>
                                                <td class="noline_left noline_top">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_sub_mater_info[7]">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_sub_mater_info_no[7]" value = "7">
                                                </td>
                                                <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_sub_mater_info_title[7]" value="가격택"></td>
                                                <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_size[7]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_hei[7]" value=""></td>
                                                <td></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_length[7]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_yd[7]" id="jo_sub_mater_info_yd_7" data-sub-mater="7" onblur="sub_mater_math(this)" value="1.0"></td>
                                                <td colspan = "2">
                                                    <select onfocus="Vail_fixed(this)" class="noborder jo_select per_select" name="jo_sub_mater_info_danga_per[7]" id ="per_select_7" data-sub-mater="7">
                                                        <option value="">기본</option>
                                                        <option value="1.2">20%</option>
                                                    </select>    
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_right exchange_input print_hidden" name="jo_sub_mater_info_danga[7]" id="jo_sub_mater_info_danga_7" data-sub-mater="7" onblur="sub_mater_math(this)" value="40">
                                                </td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_sub_mater_info_price print_hidden" name="jo_sub_mater_info_price[7]" id="jo_sub_mater_info_price_7" data-sub-mater="7" onblur="sub_mater_math(this)" value="40"></td>
                                            </tr>
                                            <tr class="btn-add-sub-mater sub-mater_8" data-item-idx=8>
                                                <td class="noline_left noline_top">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_sub_mater_info[8]">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_sub_mater_info_no[8]" value = "8">
                                                </td>
                                                <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_sub_mater_info_title[8]" value="품질보증택"></td>
                                                <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_size[8]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_hei[8]" value=""></td>
                                                <td></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_length[8]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_yd[8]" id="jo_sub_mater_info_yd_8" data-sub-mater="8" onblur="sub_mater_math(this)" value="1.0"></td>
                                                <td colspan = "2">
                                                    <select onfocus="Vail_fixed(this)" class="noborder jo_select per_select" name="jo_sub_mater_info_danga_per[8]" id ="per_select_8" data-sub-mater="8">
                                                        <option value="">기본</option>
                                                        <option value="1.2">20%</option>
                                                    </select>    
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_right exchange_input print_hidden" name="jo_sub_mater_info_danga[8]" id="jo_sub_mater_info_danga_8" data-sub-mater="8" onblur="sub_mater_math(this)" value="40">
                                                </td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_sub_mater_info_price print_hidden" name="jo_sub_mater_info_price[8]" id="jo_sub_mater_info_price_8" data-sub-mater="8" onblur="sub_mater_math(this)" value="40"></td>
                                            </tr>
                                            <tr class="btn-add-sub-mater sub-mater_9" data-item-idx=9>
                                                <td class="noline_left noline_top">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_sub_mater_info[9]">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_sub_mater_info_no[9]" value = "9">
                                                </td>
                                                <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_sub_mater_info_title[9]" value="프라우덴 우모택"></td>
                                                <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_size[9]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_hei[9]" value=""></td>
                                                <td></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_length[9]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_yd[9]" id="jo_sub_mater_info_yd_9" data-sub-mater="9" onblur="sub_mater_math(this)" value="1.0"></td>
                                                <td colspan = "2">
                                                    <select onfocus="Vail_fixed(this)" class="noborder jo_select per_select" name="jo_sub_mater_info_danga_per[9]" id ="per_select_9" data-sub-mater="9">
                                                        <option value="">기본</option>
                                                        <option value="1.2">20%</option>
                                                    </select>        
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_right exchange_input print_hidden" name="jo_sub_mater_info_danga[9]" id="jo_sub_mater_info_danga_9" data-sub-mater="9" onblur="sub_mater_math(this)" value="-">
                                                </td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_sub_mater_info_price print_hidden" name="jo_sub_mater_info_price[9]" id="jo_sub_mater_info_price_9" data-sub-mater="9" onblur="sub_mater_math(this)" value="0"></td>
                                            </tr>
                                            <tr class="btn-add-sub-mater sub-mater_10" data-item-idx=10>
                                                <td class="noline_left noline_top">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_sub_mater_info[10]">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_sub_mater_info_no[10]" value = "10">
                                                </td>
                                                <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_sub_mater_info_title[10]" value="프라우덴 RDS 택"></td>
                                                <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_size[10]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_hei[10]" value=""></td>
                                                <td></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_length[10]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_yd[10]" id="jo_sub_mater_info_yd_10" data-sub-mater="10" onblur="sub_mater_math(this)" value="1.0"></td>
                                                <td colspan = "2">
                                                    <select onfocus="Vail_fixed(this)" class="noborder jo_select per_select" name="jo_sub_mater_info_danga_per[10]" id ="per_select_10" data-sub-mater="10">
                                                        <option value="">기본</option>
                                                        <option value="1.2">20%</option>
                                                    </select>        
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_right exchange_input print_hidden" name="jo_sub_mater_info_danga[10]" id="jo_sub_mater_info_danga_10" data-sub-mater="10" onblur="sub_mater_math(this)" value="-">
                                                </td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_sub_mater_info_price print_hidden" name="jo_sub_mater_info_price[10]" id="jo_sub_mater_info_price_10" data-sub-mater="10" onblur="sub_mater_math(this)" value="0"></td>
                                            </tr>
                                            <tr class="btn-add-sub-mater sub-mater_11" data-item-idx=11>
                                                <td class="noline_left noline_top">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_sub_mater_info[11]">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_sub_mater_info_no[11]" value = "11">
                                                </td>
                                                <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_sub_mater_info_title[11]" value="알러지 방지택"></td>
                                                <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_size[11]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_hei[11]" value=""></td>
                                                <td></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_length[11]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_yd[11]" id="jo_sub_mater_info_yd_11" data-sub-mater="11" onblur="sub_mater_math(this)" value="1.0"></td>
                                                <td colspan = "2">
                                                    <select onfocus="Vail_fixed(this)" class="noborder jo_select per_select" name="jo_sub_mater_info_danga_per[11]" id ="per_select_11" data-sub-mater="11">
                                                        <option value="">기본</option>
                                                        <option value="1.2">20%</option>
                                                    </select>    
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_right exchange_input print_hidden" name="jo_sub_mater_info_danga[11]" id="jo_sub_mater_info_danga_11" data-sub-mater="11" onblur="sub_mater_math(this)" value="30">
                                                </td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_sub_mater_info_price print_hidden" name="jo_sub_mater_info_price[11]" id="jo_sub_mater_info_price_11" data-sub-mater="11" onblur="sub_mater_math(this)" value="30"></td>
                                            </tr>
                                            <tr class="btn-add-sub-mater sub-mater_12" data-item-idx=12>
                                                <td class="noline_left noline_top">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_sub_mater_info[12]">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_sub_mater_info_no[12]" value = "12">
                                                </td>
                                                <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_sub_mater_info_title[12]" value="이블가방"></td>
                                                <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_size[12]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_hei[12]" value=""></td>
                                                <td></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_length[12]" value=""></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_yd[12]" id="jo_sub_mater_info_yd_12" data-sub-mater="12" onblur="sub_mater_math(this)" value="1.0"></td>
                                                <td colspan = "2">
                                                    <select onfocus="Vail_fixed(this)" class="noborder jo_select per_select" name="jo_sub_mater_info_danga_per[12]" id ="per_select_12" data-sub-mater="12">
                                                        <option value="">기본</option>
                                                        <option value="1.2">20%</option>
                                                    </select>
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_right exchange_input print_hidden" name="jo_sub_mater_info_danga[12]" id="jo_sub_mater_info_danga_12" data-sub-mater="12" onblur="sub_mater_math(this)" value="2,100">
                                                </td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_sub_mater_info_price print_hidden" name="jo_sub_mater_info_price[12]" id="jo_sub_mater_info_price_12" data-sub-mater="12" onblur="sub_mater_math(this)" value="2,100"></td>
                                            </tr>
                                        <?endif?>

                                    </tbody>
                                        
                                    <tr>
                                        <td class="noline_left noline_top"></td>
                                        <th colspan = "4">부자재 계</th>
                                        <th colspan = "8"></td>
                                        <th class="txt_right">
                                            <?if (!empty($jo_sub_mater)) :?>
                                            <?php foreach ($jo_sub_mater as $smi => $sub_mater_info) {
                                                $total_sub_mater_price += $sub_mater_info['price'];
                                            } ?> 
                                            <?endif?>
                                            <span class="func_th_view print_hidden" id ="total_sub_mater_price_view"><?=number_format($total_sub_mater_price)?></span>
                                            <input type="hidden" class="noborder_th txt_right func_th print_hidden" id ="total_sub_mater_price" name ="total_sub_mater_price" onblur="comma_input(this)" value ="<?=number_format($total_sub_mater_price)?>" > 
                                        </th>
                                    </tr>
                                    <tbody id="jo_gakong_item_area">
                                        
                                        <?php foreach ($jo_gakong_item as $jgi => $gakong_item) : ?>
                                            <tr class="btn-add-gakong-item gakong-item_<?=$jgi?>" data-item-idx=<?=$jgi?>>
                                                <td class="noline_left noline_top">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_gakong_item[<?=$jgi?>]">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_gakong_item_no[<?=$jgi?>]" value = "<?=$jgi?>">
                                                </td>
                                                <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_gakong_item_title[<?=$jgi?>]" value="<?=$gakong_item['title']?>"></td>
                                                <td colspan = "2"></td>
                                                <td></td>
                                                <td class="txt_center"></td>
                                                <td></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_gakong_item_yd[<?=$jgi?>]" id="jo_gakong_item_yd_<?=$jgi?>" data-gakong-idx="<?=$jgi?>" onblur="gakong_math(this)" value="<?=$gakong_item['yd']?>"></td>
                                                <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder txt_right print_hidden" name="jo_gakong_item_danga[<?=$jgi?>]" id="jo_gakong_item_danga_<?=$jgi?>" data-gakong-idx="<?=$jgi?>" onblur="gakong_math(this)" value="<?=$gakong_item['danga'] ? number_format($gakong_item['danga']) : '-'?>"></td>
                                                <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_gakong_item_price print_hidden" name="jo_gakong_item_price[<?=$jgi?>]" id="jo_gakong_item_price_<?=$jgi?>" data-gakong-idx="<?=$jgi?>"  onblur="gakong_math(this)" value="<?=$gakong_item['price'] ? number_format($gakong_item['price']) : '0'?>"></td>
                                            </tr>
                                            
                                        <?php endforeach ?>
                                        <?if($jgi < 4):?>
                                        <?
                                            if($jgi < 1) { 
                                                $cnt_gakong = 1;
                                            }else{
                                                $cnt_gakong = $jgi+1;
                                            }
                                            ?>

                                            <?for($giidx = $cnt_gakong; $giidx < 5 ; $giidx++) : ?>
                                                <tr class="btn-add-gakong-item gakong-item_<?=$giidx?>" data-item-idx=<?=$giidx?>>
                                                    <td class="noline_left noline_top">
                                                        <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_gakong_item[<?=$giidx?>]">
                                                        <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_gakong_item_no[<?=$giidx?>]" value = "<?=$giidx?>">
                                                    </td>
                                                    <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_gakong_item_title[<?=$giidx?>]" value=""></td>
                                                    <td colspan = "2"></td>
                                                    <td></td>
                                                    <td class="txt_center"></td>
                                                    <td></td>
                                                    <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_gakong_item_yd[<?=$giidx?>]" id="jo_gakong_item_yd_<?=$giidx?>" data-gakong-idx="<?=$giidx?>" onblur="gakong_math(this)" value=""></td>
                                                    <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder txt_right print_hidden" name="jo_gakong_item_danga[<?=$giidx?>]" id="jo_gakong_item_danga_<?=$giidx?>" data-gakong-idx="<?=$giidx?>" onblur="gakong_math(this)" value=""></td>
                                                    <td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_gakong_item_price print_hidden" name="jo_gakong_item_price[<?=$giidx?>]" id="jo_gakong_item_price_<?=$giidx?>" data-gakong-idx="<?=$giidx?>" onblur="gakong_math(this)" value=""></td>
                                                </tr>
                                            <?endfor?>
                                        <?endif?>
                                        
                                        <!-- <tr class="btn-add-gakong-item gakong-item_1" data-item-idx=1>
                                            <td class="noline_left noline_top">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_gakong_item[1]">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_gakong_item_no[1]" value = "1">
                                            </td>
                                            <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_gakong_item_title[1]" value="봉제공임"></td>
                                            <td colspan = "2"></td>
                                            <td></td>
                                            <td class="txt_center"></td>
                                            <td></td>
                                            <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_gakong_item_yd[1]" value=""></td>
                                            <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder txt_right" name="jo_gakong_item_danga[1]" value=""></td>
                                            <td><input onfocus="Vail_fixed(this)" class="noborder txt_right" name="jo_gakong_item_price[1]" value=""></td>
                                        </tr>
                                        <tr class="btn-add-gakong-item gakong-item_2" data-item-idx=2>
                                            <td class="noline_left noline_top">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_gakong_item[2]">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_gakong_item_no[2]" value = "2">
                                            </td>
                                            <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_gakong_item_title[2]" value="주입공임"></td>
                                            <td colspan = "2"></td>
                                            <td></td>
                                            <td class="txt_center"></td>
                                            <td></td>
                                            <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_gakong_item_yd[2]" value=""></td>
                                            <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder txt_right" name="jo_gakong_item_danga[2]" value=""></td>
                                            <td><input onfocus="Vail_fixed(this)" class="noborder txt_right" name="jo_gakong_item_price[2]" value=""></td>
                                        </tr>
                                        <tr class="btn-add-gakong-item gakong-item_3" data-item-idx=3>
                                            <td class="noline_left noline_top">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_gakong_item[3]">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_gakong_item_no[3]" value = "3">
                                            </td>
                                            <td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_gakong_item_title[3]" value="포장비"></td>
                                            <td colspan = "2"></td>
                                            <td></td>
                                            <td class="txt_center"></td>
                                            <td></td>
                                            <td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_gakong_item_yd[3]" value=""></td>
                                            <td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder txt_right" name="jo_gakong_item_danga[3]" value=""></td>
                                            <td><input onfocus="Vail_fixed(this)" class="noborder txt_right" name="jo_gakong_item_price[3]" value=""></td>
                                        </tr> -->
                                        

                                    </tbody>
                                        
                                    <tr>
                                        <td class="noline_left noline_top"></td>
                                        <th colspan = "4">가공임 계</th>
                                        <th colspan = "8"></td>
                                        <th class="txt_right">
                                            <?php foreach ($jo_gakong_item as $gi => $gakong_item) {
                                                $total_gakong_price += $gakong_item['price'];
                                            } ?> 
                                            <span class="func_th_view print_hidden" id ="total_gakong_price_view"><?=number_format($total_gakong_price)?></span>
                                            <input type="hidden" class="noborder_th txt_right func_th print_hidden" id ="total_gakong_price" name ="total_gakong_price" value ="<?=$total_gakong_price?>" >
                                        </th>
                                    </tr>
                                </table>
                                
                            </td>
                        </tr>

                        <tr class="memo_info_aera">
                            <td rowspan="1"  colspan = "10" id="hold_td_text_area" >
                                <div id="msg_area">
                                    <div id="print_text_area">
                                        <div><작업시 주의사항></div>
                                        <textarea id="text-area2" onfocus="Vail_fixed(this)" class="noborder txt_left hei_92_per"name="jo_memo" placeholder=""><?=$jo['jo_memo']?></textarea>
                                    </div>
                                    <div id="print_img_area" style='display:flex; flex-wrap:wrap; justify-content:center; align-items:center;'>
                                        <input type='file' name ="jo_memo_img" id='verborgen_file_jo_memo_img' />
                                        <div id="memo_img_pf_foto" style='display:flex; flex-wrap:wrap; justify-content:center; align-items:center; height: 100%;'>
                                            <?if($jo['jo_memo_img']):?>
                                            <input type="hidden" name="jo_memo_img_img"  value="<?=$jo['jo_memo_img']?>"> 
                                            <?endif?>
                                            <img style="margin: 0 auto; display: block; <?if(!$jo['jo_memo_img']):?> width: 95%; height: 100%  <?endif?>" id="memo_img_pf_foto_img" <?if($jo['jo_memo_img']):?> src="<?=G5_URL?>/data/new_goods/<?=$jo['jo_memo_img']?>" <?endif?>>
                                        </div>  
                                    </div>
                                </div>
                            </td>
                            <td id="noline_td" colspan = "17">
                                <table id="noline_new_goods_table" style="width : 100%;">
                                    <colgroup>
                                        <col width="0px"/>
                                        <col width="31px"/>
                                        <col width="55px"/>
                                        <col width="31px"/>
                                        <col width="71px"/>
                                        <col width="31px"/>
                                        <col width="45px"/>
                                        <col width="82px"/>
                                        <col width="20px"/>
                                        <col width="55px"/>
                                        <col width="81px"/>
                                        <col width="72px"/>
                                        <col width="50px"/>
                                        <col width="78px"/>
                                    </colgroup>
                                    
                                    <tr>
                                        <td></td>
                                        <th colspan = "12" class="txt_left"> 생산원가(원자재계+부자재계+가공임계) </th>
                                        <th class="txt_right">
                                            <!-- <input onfocus="Vail_fixed(this)" class="noborder txt_right txt_op1" name="jo_prod_origin_price" value="28,235"> -->
                                            <span class="func_th_view print_hidden" id ="jo_prod_origin_price_view"><?=number_format($jo['jo_prod_origin_price'])?></span>
                                            <input type="hidden" class="noborder_th txt_right func_th print_hidden" id ="jo_prod_origin_price" name ="jo_prod_origin_price" onblur="comma_input(this)" value ="<?=number_format($jo['jo_prod_origin_price'])?>" >
                                        </th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <th colspan = "10" class="txt_left"> 일반관리비 </th>
                                        <td colspan = "2" class="txt_right">
                                            <input onfocus="Vail_fixed(this)" class="noborder90 txt_right print_hidden" name="jo_prod_control_price" id="jo_prod_control_price" onblur="prod_total_price()" value="<?=$jo['jo_prod_control_price'] <= 0 ? $jo['jo_prod_control_price'] : 13?>">%
                                        </td>
                                        <th class="txt_right">
                                            <span class="print_hidden" id ="jo_prod_control_price_view"></span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <th colspan = "12" class="txt_left"> 총원가 (VAT 포함) </th>
                                        <th class="txt_right">
                                            <!-- <input onfocus="Vail_fixed(this)" class="noborder txt_right" name="jo_total_origin_price" value="28,235"> -->
                                            <span class="func_th_view print_hidden" id ="jo_total_origin_price_view"><?=number_format($jo['jo_total_origin_price'])?></span>
                                            <input type="hidden" class="noborder_th txt_right func_th print_hidden" id ="jo_total_origin_price" name ="jo_total_origin_price" onblur="comma_input(this)" value ="<?=number_format($jo['jo_total_origin_price'])?>" >
                                        </th>
                                    </tr>
                                    <?
                                    $jo_mater_name_item = array();
                                    ?>
                                    <?php foreach ($jo_mater_name as $jmn => $mater_name) {
                                        if($jmn == 1){
                                            $first_mater_name = $mater_name;
                                        }else{
                                            $item_set = array(  
                                                "no" => $mater_name['no'], 
                                                "title" => $mater_name['title'],  
                                                "mater" => $mater_name['mater'], 
                                                "danga" => $mater_name['danga'], 
                                                "tel" => $mater_name['tel'], 
                                            );
                                            $jo_mater_name_item[$jmn-2] = $item_set;
                                        }
                                    } ?>
                                    <tr>
                                        <td></td>
                                        <th colspan="4">원자재명(1)</th>
                                        <td colspan="5" class="txt_left">
                                            <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_mater_name[1]">
                                            <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_mater_name_no[1]" value = "1">
                                            <input onfocus="Vail_fixed(this)" class="noborder txt_left print_hidden" name="jo_mater_name_title[1]" value="<?=$first_mater_name['title']?>">    
                                        </td>
                                        <th>기타업체</th>
                                        <td colspan="3">
                                            <input onfocus="Vail_fixed(this)" class="noborder txt_left print_hidden" name="jo_etc_company" value="<?=$jo['jo_etc_company']?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <th colspan="4">구입처</th>
                                        <td colspan="5" class="txt_left">
                                            <input onfocus="Vail_fixed(this)" class="noborder txt_left print_hidden" name="jo_mater_name_mater[1]" value="<?=$first_mater_name['mater']?>">
                                        </td>
                                        <th>전화번호</th>
                                        <td colspan="3">
                                            <input onfocus="Vail_fixed(this)" class="noborder txt_left print_hidden" name="jo_etc_company_tel" value="<?=$jo['jo_etc_company_tel']?>">
                                        </td>
                                        
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <th colspan="4">매입 단가(vat-)</th>
                                        <td colspan="5" class="txt_left">
                                            <input onfocus="Vail_fixed(this)" class="noborder txt_left print_hidden" name="jo_mater_name_danga[1]" value="<?=number_format($first_mater_name['danga'])?>">
                                        </td>
                                        <td colspan="4"></td>
                                        
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <th colspan="4">연락처</th>
                                        <td colspan="5" class="txt_left">
                                        <input onfocus="Vail_fixed(this)" class="noborder txt_left print_hidden" name="jo_mater_name_tel[1]" value="<?=$first_mater_name['tel']?>">
                                        </td>
                                        <th colspan="4">품질표시</th>
                                        
                                    </tr>
                                    <tbody id="jo_mater_name_area">
                                        <?if(!empty($jo_mater_name_item)) : ?>
                                            <?php foreach ($jo_mater_name_item as $jmni => $mater_name_item) : ?>
                                            <tr class="btn-add-name-mater mater-name_<?=$jmni+2?>"  data-item-idx=<?=$jmni+2?>>
                                                <td></td>
                                                <th colspan="4">원자재명(<?=$jmni+2?>)</th>
                                                <td colspan="5">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_mater_name[<?=$jmni+2?>]">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_mater_name_no[<?=$jmni+2?>]" value = "<?=$mater_name_item['no']?>">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left print_hidden" name="jo_mater_name_title[<?=$jmni+2?>]" value="<?=$mater_name_item['title']?>">   
                                                </td>
                                                <td colspan="4">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_pumjil[<?=($jmni*4)+1?>]" value="<?=$jo_pumjil[($jmni*4)+1]['contents']?>">
                                                </td>
                                                
                                            </tr>
                                            <tr class="btn-add-name-mater mater-name_<?=$jmni+2?>"  data-item-idx=<?=$jmni+2?>>
                                                <td></td>
                                                <th colspan="4">구입처</th>
                                                <td colspan="5">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left print_hidden" name="jo_mater_name_mater[<?=$jmni+2?>]" value="<?=$mater_name_item['mater']?>">
                                                </td>
                                                <td colspan="4">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_pumjil[<?=($jmni*4)+2?>]" value="<?=$jo_pumjil[($jmni*4)+2]['contents']?>">
                                                </td>
                                                
                                            </tr>
                                            <tr class="btn-add-name-mater mater-name_<?=$jmni+2?>"  data-item-idx=<?=$jmni+2?>>
                                                <td></td>
                                                <th colspan="4">매입 단가(vat-)</th>
                                                <td colspan="5">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left print_hidden" name="jo_mater_name_danga[<?=$jmni+2?>]" value="<?=$mater_name_item['danga']?>">
                                                </td>
                                                <td colspan="4">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_pumjil[<?=($jmni*4)+3?>]" value="<?=$jo_pumjil[($jmni*4)+3]['contents']?>">
                                                </td>
                                                
                                            </tr>
                                            <tr class="btn-add-name-mater mater-name_<?=$jmni+2?>"  data-item-idx=<?=$jmni+2?>>
                                                <td></td>
                                                <th colspan="4">연락처</th>
                                                <td colspan="5">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left print_hidden" name="jo_mater_name_tel[<?=$jmni+2?>]" value="<?=$mater_name_item['tel']?>">
                                                </td>
                                                <td colspan="4">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_pumjil[<?=($jmni*4)+4?>]" value="<?=$jo_pumjil[($jmni*4)+4]['contents']?>">
                                                </td>
                                                
                                            </tr>
                                            <?endforeach?>
                                        <?else:?>
                                            <tr class="btn-add-name-mater mater-name_2"  data-item-idx=2>
                                                <td></td>
                                                <th colspan="4">원자재명(2)</th>
                                                <td colspan="5">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_mater_name[2]">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_mater_name_no[2]" value = "2">
                                                <input onfocus="Vail_fixed(this)" class="noborder txt_left print_hidden" name="jo_mater_name_title[2]" value="">   
                                                </td>
                                                <td colspan="4">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_pumjil[1]" value="겉감1 : ">
                                                </td>
                                                
                                            </tr>
                                            <tr class="btn-add-name-mater"  data-item-idx=2>
                                                <td></td>
                                                <th colspan="4">구입처</th>
                                                <td colspan="5">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left print_hidden" name="jo_mater_name_mater[2]" value="">
                                                </td>
                                                <td colspan="4">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_pumjil[2]" value="겉감2 : ">
                                                </td>
                                                
                                            </tr>
                                            <tr class="btn-add-name-mater"  data-item-idx=2>
                                                <td></td>
                                                <th colspan="4">매입 단가(vat-)</th>
                                                <td colspan="5">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left print_hidden" name="jo_mater_name_danga[2]" value="">
                                                </td>
                                                <td colspan="4">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_pumjil[3]" value="충전재 : ">
                                                </td>
                                                
                                            </tr>
                                            <tr class="btn-add-name-mater"  data-item-idx=2>
                                                <td></td>
                                                <th colspan="4">연락처</th>
                                                <td colspan="5">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left print_hidden" name="jo_mater_name_tel[2]" value="">
                                                </td>
                                                <td colspan="4">
                                                    <input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_pumjil[4]" value="">
                                                </td>
                                                
                                            </tr>

                                        <?endif?>
                                        
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                    
                    <!-- <div style="width : 100%;  display: flow-root;">

                        <div style="width : 50%; float:left; ">
                            <input type='file' id="imgInp" />
                            <div style="margin : 0 auto;">
                                <img id="blah" src="#" alt="your image" />
                            </div>
                        </div>
                        <table id="new_goods_table" style="display: inline-table; width : 50%;">
                            <tr>
                                <th>원.부자재</th>
                                <th>구격(")</th>
                                <th colspan="3">재단SIZE (폭+폭)x길이cm</th>
                                <th>소요량(yd)</th>
                                <th>단가(별도)</th>
                                <th>원가</th>
                            </tr>
                            <tr>
                                <td>앞판/메인솔리드</td>
                                <td>63</td>
                                <td>1/3</td>
                                <td>x</td>
                                <td>73</td>
                                <td>0.3</td>
                                <td>3,630</td>
                                <td>1,016</td>
                            </tr>
                        </table>
                    </div>
                    <div style="width : 100%; display: flow-root;">

                        <div style="clear: both;  width : 50%;float:left;">
                            코멘트
                        </div>
                        <table id="new_goods_table" style="display: inline-table; width : 50%;">
                            <tr>
                                <th>원.부자재</th>
                                <th>구격(")</th>
                                <th colspan="3">재단SIZE (폭+폭)x길이cm</th>
                                <th>소요량(yd)</th>
                                <th>단가(별도)</th>
                                <th>원가</th>
                            </tr>
                            <tr>
                                <td>앞판/메인솔리드</td>
                                <td>63</td>
                                <td>1/3</td>
                                <td>x</td>
                                <td>73</td>
                                <td>0.3</td>
                                <td>3,630</td>
                                <td>1,016</td>
                            </tr>
                        </table>
                    </div> -->

                </div></div>


                <div class="x_content">
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                            <button class="btn btn_02" type="button" id="btn_cancel">취소</button>
                            <button class="btn btn_02" type="button btn-success" <?if(($jo['jo_price_fixed'] == '100') && ($aas['mb_dept'] != '상품MD팀')):?>disabled<?endif?> id="btn_submit">임시저장</button>
                            <button type="submit" class="btn btn-success" onclick="formsubmit()" <?if(($jo['jo_price_fixed'] == '100') && ($aas['mb_dept'] != '상품MD팀' && $member['mb_id'] != 'sbs608')):?>disabled<?endif?> value="저장">저장</button>
                            <?if ($jo['jo_price_fixed'] == '100') : ?>
                                <button type="button" class="btn btn-info" disabled>확정완료</button>
                            <?else :?>
                                <button type="button" class="btn btn-primary" <?if( ($aas['mb_dept'] != '상품MD팀') && ($member['mb_id'] != 'sbs608')) :?>disabled <?endif?> onclick="price_fixed()" >확정</button>
                            <?endif?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<ul class="contextmenu">
  <li><a href="#" onclick="add_mater()">원자재 추가</a></li>
  <li><a href="#" onclick="removeRow()">행삭제</a></li>
</ul>
<ul class="contextmenu1">
  <li><a href="#" onclick="add_sub_mater()">부자재 추가</a></li>
  <li><a href="#" onclick="removeRow()">행삭제</a></li>
</ul>
<ul class="contextmenu2">
  <li><a href="#" onclick="add_gakong()">가공임 추가</a></li>
  <li><a href="#" onclick="removeRow()">행삭제</a></li>
</ul>
<ul class="contextmenu3">
  <li><a href="#" onclick="add_mater_name()">원자재명 추가</a></li>
  <li><a href="#" onclick="removeRow()">행삭제</a></li>
</ul>
<ul class="contextmenu4">
  <li><a href="#" onclick="removeImg_main()">메인 프린트 삭제</a></li>
  <li><a href="#" onclick="removeImg_codi()">코디 프린트 삭제</a></li>
  <li><a href="#" onclick="removeImg_sub()">서브 프린트 삭제</a></li>
</ul>
<ul class="contextmenu5">
  <li><a href="#" onclick="removeImg_memo_img()">이미지삭제</a></li>
</ul>

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

    //
    var select_row = "";
    var select_data = "";
    $(document).ready(function(){
        history.pushState(null, document.title, location.href); 
        window.addEventListener('popstate', function(event) { 
            history.pushState(null, document.title, location.href);
            var param = '<?=$qstr?>';
            window.location.href = 'new_goods_process.php?'+param;
        });

        //Show contextmenu:
        $(document).contextmenu(function(e){
            $(".contextmenu").hide();
            $(".contextmenu1").hide();
            $(".contextmenu2").hide();
            $(".contextmenu3").hide();
            $(".contextmenu4").hide();
            $(".contextmenu5").hide();
            select_data = e.target;
            select_row = e.target.closest('tr');
            //Get window size:
            var winWidth = $(document).width();
            var winHeight = $(document).height();
            //Get pointer position:
            var posX = e.pageX;
            var posY = e.pageY;
            //Get contextmenu size:
            var menuWidth = $(".contextmenu").width();
            var menuHeight = $(".contextmenu").height();
            if(select_row.className.indexOf('btn-add-mater') > -1){
                menuWidth = $(".contextmenu").width();
                menuHeight = $(".contextmenu").height();
            }else if(select_row.className.indexOf('btn-add-sub-mater') > -1){
                menuWidth = $(".contextmenu1").width();
                menuHeight = $(".contextmenu1").height();
            }else if (select_row.className.indexOf('btn-add-gakong-item') > -1){
                menuWidth = $(".contextmenu2").width();
                menuHeight = $(".contextmenu2").height();
            }else if(select_row.className.indexOf('btn-add-name-mater') > -1){
                menuWidth = $(".contextmenu3").width();
                menuHeight = $(".contextmenu3").height();
            }else if(select_row.className.indexOf('onedan_switch') > -1){
                menuWidth = $(".contextmenu4").width();
                menuHeight = $(".contextmenu4").height();
            }else if(select_row.className.indexOf('memo_info_aera') > -1){
                menuWidth = $(".contextmenu5").width();
                menuHeight = $(".contextmenu5").height();
            }
            
            //Security margin:
            var secMargin = 10;
            //Prevent page overflow:
            if(posX + menuWidth + secMargin >= winWidth
            && posY + menuHeight + secMargin >= winHeight){
            //Case 1: right-bottom overflow:
            posLeft = posX - menuWidth - secMargin + "px";
            posTop = posY - menuHeight - secMargin + "px";
            }
            else if(posX + menuWidth + secMargin >= winWidth){
            //Case 2: right overflow:
            posLeft = posX - menuWidth - secMargin + "px";
            posTop = posY + secMargin + "px";
            }
            else if(posY + menuHeight + secMargin >= winHeight){
            //Case 3: bottom overflow:
            posLeft = posX + secMargin + "px";
            posTop = posY - menuHeight - secMargin + "px";
            }
            else {
            //Case 4: default values:
            posLeft = posX + secMargin + "px";
            posTop = posY + secMargin + "px";
            };
            //Display contextmenu:

            if(select_row.className.indexOf('btn-add-mater') > -1){
                $(".contextmenu").css({
                "left": posLeft,
                "top": posTop
                }).show();
            }else if(select_row.className.indexOf('btn-add-sub-mater') > -1){
                $(".contextmenu1").css({
                "left": posLeft,
                "top": posTop
                }).show();
            }else if (select_row.className.indexOf('btn-add-gakong-item') > -1){
                $(".contextmenu2").css({
                "left": posLeft,
                "top": posTop
                }).show();
            }else if(select_row.className.indexOf('btn-add-name-mater') > -1){
                $(".contextmenu3").css({
                "left": posLeft,
                "top": posTop
                }).show();
            }else if(select_row.className.indexOf('btn-add-name-mater') > -1){
                $(".contextmenu3").css({
                "left": posLeft,
                "top": posTop
                }).show();
            }else if(select_row.className.indexOf('onedan_switch') > -1){
                $(".contextmenu4").css({
                "left": posLeft,
                "top": posTop
                }).show();
            }else if(select_row.className.indexOf('memo_info_aera') > -1){
                $(".contextmenu5").css({
                "left": posLeft,
                "top": posTop
                }).show();
            }
            // $(".contextmenu").css({
            // "left": posLeft,
            // "top": posTop
            // }).show();
            //Prevent browser default contextmenu.
            return false;
        });
        //Hide contextmenu:
        $(document).click(function(){
            $(".contextmenu").hide();
        });
        $(document).click(function(){
            $(".contextmenu1").hide();
        });
        $(document).click(function(){
            $(".contextmenu2").hide();
        });
        $(document).click(function(){
            $(".contextmenu3").hide();
        });
        $(document).click(function(){
            $(".contextmenu4").hide();
        });
        $(document).click(function(){
            $(".contextmenu5").hide();
        });


        $('#verborgen_file_main').hide();
        $('#verborgen_file_codi').hide();
        $('#verborgen_file_sub').hide();
        $('#verborgen_file_jo_memo_img').hide();
        $('#main_pf_foto').on('click', function () {if($("#main_pf_foto_img").attr("src")){ click_img_down($("#main_pf_foto_img").attr("src") , '메인');}else { $('#verborgen_file_main').click();}});
        $('#codi_pf_foto').on('click', function () {if($("#codi_pf_foto_img").attr("src")){ click_img_down($("#codi_pf_foto_img").attr("src"), '코디');}else { $('#verborgen_file_codi').click();}});
        $('#sub_pf_foto').on('click', function () {if($("#sub_pf_foto_img").attr("src")){ click_img_down($("#sub_pf_foto_img").attr("src"), '코디1');}else { $('#verborgen_file_sub').click();}});
        $('#memo_img_pf_foto').on('click', function () {if($("#memo_img_pf_foto_img").attr("src")){ click_img_down($("#memo_img_pf_foto_img").attr("src"), '추가설명이미지');}else { $('#verborgen_file_jo_memo_img').click();}});


        // $('#main_pf_foto').on('click', function () {$('#verborgen_file_main').click();});
        // $('#codi_pf_foto').on('click', function () {$('#verborgen_file_codi').click();});
        // $('#sub_pf_foto').on('click', function () {$('#verborgen_file_sub').click();});
        // $('#memo_img_pf_foto').on('click', function () {$('#verborgen_file_jo_memo_img').click();});

        $('#verborgen_file_main').change(function () {
            var file = this.files[0];
            var reader = new FileReader();
            reader.onloadend = function () {
               $('#main_pf_foto_img').attr('src', reader.result);
            }
            if (file) {
                reader.readAsDataURL(file);
            } else {
            }
        });
        $('#verborgen_file_codi').change(function () {
            var file = this.files[0];
            var reader = new FileReader();
            reader.onloadend = function () {
                $('#codi_pf_foto_img').attr('src', reader.result);
            }
            if (file) {
                reader.readAsDataURL(file);
            } else {
            }
        });
        $('#verborgen_file_sub').change(function () {
            var file = this.files[0];
            var reader = new FileReader();
            reader.onloadend = function () {
                $('#sub_pf_foto_img').attr('src', reader.result);
            }
            if (file) {
                reader.readAsDataURL(file);
            } else {
            }
        });
        $('#verborgen_file_jo_memo_img').change(function () {
            $("#memo_img_pf_foto_img").css('width', '');
            $("#memo_img_pf_foto_img").css('height', '');
            var file = this.files[0];
            var reader = new FileReader();
            reader.onloadend = function () {
                $('#memo_img_pf_foto_img').attr('src', reader.result);
            }
            if (file) {
                reader.readAsDataURL(file);
            } else {
            }
        });

        if(is_checked("function")){
            mater_math_re_price();
            sub_mater_re_price();
            gakong_math_re_price();
        }
        // var fileValue = $(".jo_sub_mater_info_price").length;
        // var fileData = new Array(fileValue);
        // let total_sub_mater_price = 0;
        // for(var i=0; i<fileValue; i++){                          
        //     fileData[i] = $(".jo_sub_mater_info_price")[i].value.replace(/,/gi,'');
        //     total_sub_mater_price += (fileData[i]*1);
        // }
        // total_sub_mater_price = parseInt(total_sub_mater_price * 100) / 100;
        // $('#total_sub_mater_price').val(comma(total_sub_mater_price+""));
        // $('#total_sub_mater_price_view').empty().html(comma(total_sub_mater_price+""));

        func_checked();
        prod_origin_price();
        prod_total_price();
    });

    function sub_mater_re_price(){
        var fileValue = $(".jo_sub_mater_info_price").length;
        var fileData = new Array(fileValue);
        let total_sub_mater_price = 0;
        for(var i=0; i<fileValue; i++){                          
            fileData[i] = $(".jo_sub_mater_info_price")[i].value.replace(/,/gi,'');
            total_sub_mater_price += (fileData[i]*1);
        }
        total_sub_mater_price = parseInt(total_sub_mater_price * 100) / 100;
        $('#total_sub_mater_price').val(comma(total_sub_mater_price+""));
        $('#total_sub_mater_price_view').empty().html(comma(total_sub_mater_price+""));
        prod_origin_price();
    }

    function addRow(e){
        alert("add"+e);
    }

    function emptyImg(e){
        e.stopPropagation();
    }

    $( ".emptyImg" ).click(function( event ) {
        event.stopPropagation();
    // Do something
    });

    $('input').keydown(function() {
        if (event.keyCode === 13) {
            event.preventDefault();
        }
    });

    function removeImg_main(){
        $('#verborgen_file_main').val('');
        $("input[name ='jo_main_img_img[1]']").val('');
        $('#main_pf_foto_img').removeAttr('src');
    }
    function removeImg_codi(){
        $('#verborgen_file_codi').val('');
        $("input[name ='jo_codi_img_img[1]']").val('');
        $('#codi_pf_foto_img').removeAttr('src');
    }
    function removeImg_sub(){
        $('#verborgen_file_sub').val('');
        $("input[name ='jo_sub_img_img[1]']").val('');
        $('#sub_pf_foto_img').removeAttr('src');
    }
    function removeImg_memo_img(){
        $('#verborgen_file_memo_img').val('');
        $("input[name ='jo_memo_img']").val('');
        $("input[name ='jo_memo_img_img']").val('');
        $('#memo_img_pf_foto_img').removeAttr('src');
    }

    function removeRow(){
        if((select_row.className.indexOf('btn-add-mater') > -1) || (select_row.className.indexOf('btn-add-sub-mater') > -1) || (select_row.className.indexOf('btn-add-gakong-item') > -1) ){
            select_row.remove();
            if(select_row.className.indexOf('btn-add-mater') > -1){
                mater_math_re_price();
            }else if(select_row.className.indexOf('btn-add-sub-mater') > -1){
                sub_mater_re_price();
            }else if (select_row.className.indexOf('btn-add-gakong-item') > -1){
                gakong_math_re_price();
            }
        }else if(select_row.className.indexOf('btn-add-name-mater') > -1){
            let nextIdx = $("tr.btn-add-name-mater").last().data("item-idx") * 1;
            if(nextIdx <= 2){
                alert("삭제할수 없는 행입니다.");
            }else{
                $('.mater-name_'+nextIdx).remove();
            }
            
        }else {
            alert("삭제할수 없는 행입니다.");
        }
    }
    


    //
    // document.addEventListener('mousedown', function() {
    //     if ((event.button == 2) || (event.which == 3)) {
    //         // Code here
    //         alert("asdfasdf");
    //         console.log(event);
    //     }
    // });

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
    function printPage_2(event){
        var initBody;
        window.onbeforeprint = function(){
            initBody = document.body.innerHTML;
            document.body.innerHTML =  document.getElementById('print2').innerHTML;
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
    function readURL_main(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
            $('#blah_main').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    $("#imgInp").change(function() {
        readURL(this);
    });
    $("#main_imgInp").change(function() {
        readURL_main(this);
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
    // 작성일
    function regDatePicker(){
        $('#regdatepicker').datetimepicker({
            ignoreReadonly: true,
            allowInputToggle: true,
            format: 'YYYY-MM-DD',
            locale: 'ko'
        });
    }

    $('#regdatepicker').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true,
        format: 'YYYY-MM-DD',
        locale: 'ko'
    });

    $('#enddatepicker').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true,
        format: 'YYYY-MM-DD HH:mm',
        locale: 'ko'
    });

    // $("#regdatepicker").on("dp.change", function(e) {
    //     $('#regdatepicker').data("DateTimePicker").minDate(e.date);
    // });

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
    function preview_Img(imgPath){
        $("#imgPath").attr('src' , imgPath);
        $("#imgStr").html(imgPath);

        $("#modal_preview_img").modal('show');
    }

    function func_checked(){
        if (!is_checked("function")) {
            $('.func_th').attr('readonly',false);
            $(".func_th").attr("type","text");
            $(".func_th_view").css("display","none");
            $("#jo_prod_origin_price").attr('readonly',false);
            $("#jo_total_origin_price").attr('readonly',false);
        }else{
            $('.func_th').attr('readonly',true);
            $(".func_th").attr("type","hidden");
            $(".func_th_view").css("display","block");
            $("#jo_prod_origin_price").attr('readonly',true);
            $("#jo_total_origin_price").attr('readonly',true);
        }
    }

    $("#function").change(function (){
        let $chk_func = ("input[name='function']");

        if(!$($chk_func).is(':checked')){
            $("#jo_function_yn").val("N");
        }else{
            $("#jo_function_yn").val("Y");
        }
    });

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
                $("#jo_prod_name").addClass('noborder');
                break;
            case '기타' :
                $("#jo_prod_name").addClass('pum2');
                $("#jo_prod_name").addClass('noborder');
                break;
            // case '소품액세서리' :
            //     $("#jo_prod_name").addClass('pum3');
            //     break;
            case '속통' :
                $("#jo_prod_name").addClass('pum4');
                $("#jo_prod_name").addClass('noborder');
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

    //원자재정보 ver 1
  

    //원자재 추가
    function add_mater(){
        let nextIdx = $("tr.btn-add-mater").last().data("item-idx") * 1 + 1;
        let setHtml = '';
        setHtml += '<tr class="btn-add-mater mater_' + nextIdx + '" data-item-idx=' + nextIdx + '>';
        setHtml += '<td class="noline_left noline_top">';
        setHtml += '<input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_mater_info[' + nextIdx + ']">';
        setHtml += '<input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_mater_info_no[' + nextIdx + ']" value = "' + nextIdx + '">';
        setHtml += '</td>';
        setHtml += '<td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_mater_info_title[' + nextIdx + ']" value=""></td>';
        setHtml += '<td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_size[' + nextIdx + ']" value=""></td>';
        setHtml += '<td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_hei[' + nextIdx + ']" id="jo_mater_info_hei_' + nextIdx + '" data-mater-idx="' + nextIdx + '" onblur="yochek_cal1(this)" value=""></td>';
        setHtml += '<td>x</td>';
        setHtml += '<td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_length[' + nextIdx + ']" id="jo_mater_info_length_' + nextIdx + '" data-mater-idx="' + nextIdx + '" onblur="yochek_cal1(this)" value=""></td>';
        setHtml += '<td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_mater_info_yd[' + nextIdx + ']" id="jo_mater_info_yd_' + nextIdx + '" data-mater-idx="' + nextIdx + '" onblur="mater_math1(this)" value=""></td>';
        setHtml += '<td colspan="2"><input onfocus="Vail_fixed(this)" class="noborder txt_right" name="jo_mater_info_danga[' + nextIdx + ']" id="jo_mater_info_danga_' + nextIdx + '" data-mater-idx="' + nextIdx + '" onblur="mater_math1(this)" value=""></td>';
        setHtml += '<td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_mater_info_price" name="jo_mater_info_price[' + nextIdx + ']" id="jo_mater_info_price_' + nextIdx + '" data-mater-idx="' + nextIdx + '" onblur="mater_math1(this)" value=""></td>';
        setHtml += '</tr>';
        
        $("#jo_mater_info_area").append(setHtml);

    }
    //부자재 추가
    function add_sub_mater(){
        let nextIdx = $("tr.btn-add-sub-mater").last().data("item-idx") * 1 + 1;
        let setHtml = '';
        setHtml += '<tr class="btn-add-sub-mater sub-mater_' + nextIdx + '" data-item-idx=' + nextIdx + '>';
        setHtml += '<td class="noline_left noline_top">';
        setHtml += '<input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_sub_mater_info[' + nextIdx + ']">';
        setHtml += '<input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_sub_mater_info_no[' + nextIdx + ']" value = "' + nextIdx + '">';
        setHtml += '</td>';
        setHtml += '<td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_sub_mater_info_title[' + nextIdx + ']" value=""></td>';
        setHtml += '<td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_size[' + nextIdx + ']" value=""></td>';
        setHtml += '<td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_hei[' + nextIdx + ']" value=""></td>';
        setHtml += '<td></td>';
        setHtml += '<td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_sub_mater_info_length[' + nextIdx + ']" value=""></td>';
        setHtml += '<td><input onfocus="Vail_fixed(this)" class="noborder" id="jo_sub_mater_info_yd_' + nextIdx + '" name="jo_sub_mater_info_yd[' + nextIdx + ']" value=""></td>';
        setHtml += '<td colspan = "2">';
        setHtml += '<select onfocus="Vail_fixed(this)" class="noborder jo_select per_select" name="jo_sub_mater_info_danga_per[' + nextIdx + ']" id ="per_select_' + nextIdx + '" data-sub-mater="' + nextIdx + '">';
        setHtml += '<option value="">기본</option><option value="1.2">20%</option></select>';
        setHtml += '<input onfocus="Vail_fixed(this)" class="noborder txt_right exchange_input" name="jo_sub_mater_info_danga[' + nextIdx + ']" id="jo_sub_mater_info_danga_' + nextIdx + '" data-sub-mater="' + nextIdx + '" onblur="sub_mater_math(this)" value="0"></td>';
        setHtml += '<td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_sub_mater_info_price" name="jo_sub_mater_info_price[' + nextIdx + ']" id="jo_sub_mater_info_price_' + nextIdx + '" onblur="sub_mater_math(this)" value=""></td>';
        setHtml += '</tr>';

        $("#jo_sub_mater_info_area").append(setHtml);
        
    }
    function add_gakong(){
        let nextIdx = $("tr.btn-add-gakong-item").last().data("item-idx") * 1 + 1;
        let setHtml = '';
        setHtml += '<tr class="btn-add-gakong-item gakong-item_' + nextIdx + '" data-item-idx=' + nextIdx + '>';
        setHtml += '<td class="noline_left noline_top">';
        setHtml += '<input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_gakong_item[' + nextIdx + ']">';
        setHtml += '<input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_gakong_item_no[' + nextIdx + ']" value = "' + nextIdx + '">';
        setHtml += '</td>';
        setHtml += '<td colspan = "4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_gakong_item_title[' + nextIdx + ']" value=""></td>';
        setHtml += '<td colspan = "2"></td>';
        setHtml += '<td></td>';
        setHtml += '<td class="txt_center"></td>';
        setHtml += '<td></td>';
        setHtml += '<td><input onfocus="Vail_fixed(this)" class="noborder" name="jo_gakong_item_yd[' + nextIdx + ']" id="jo_gakong_item_yd_' + nextIdx + '" data-gakong-idx="' + nextIdx + '" onblur="gakong_math(this)" value=""></td>';
        setHtml += '<td colspan = "2"><input onfocus="Vail_fixed(this)" class="noborder txt_right" name="jo_gakong_item_danga[' + nextIdx + ']" id="jo_gakong_item_danga_' + nextIdx + '" data-gakong-idx="' + nextIdx + '" onblur="gakong_math(this)" value=""></td>';
        setHtml += '<td><input onfocus="Vail_fixed(this)" class="noborder txt_right jo_gakong_item_price" name="jo_gakong_item_price[' + nextIdx + ']" id="jo_gakong_item_price_' + nextIdx + '" data-gakong-idx="' + nextIdx + '" onblur="gakong_math(this)" value=""></td>';
        setHtml += '</tr>';

        $("#jo_gakong_item_area").append(setHtml);
        
    }
    //가공임 추가

    //원자재명
    function add_mater_name(){
        let nextIdx = $("tr.btn-add-name-mater").last().data("item-idx") * 1 + 1;
        let pumjilIdx = 4;
        if(nextIdx == 4){
            pumjilIdx = 8;
        }else if(nextIdx > 4){
            alert("더 이상 추가 불가 합니다.");
            return;
        }
        let setHtml = '';
        setHtml += '<tr class="btn-add-name-mater mater-name_' + nextIdx + '" data-item-idx=' + nextIdx + '><td></td><th colspan="4">원자재명(' + nextIdx + ')</th><td colspan="5"><input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden" name="jo_mater_name[' + nextIdx + ']"><input onfocus="Vail_fixed(this)" class="noborder txt_left" type="hidden"name="jo_mater_name_no[' + nextIdx + ']" value = ""><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_mater_name_title[' + nextIdx + ']" value=""></td><td colspan="4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_pumjil['+ (pumjilIdx +1) +']" value=""></td></tr>';
        setHtml += '<tr class="btn-add-name-mater mater-name_' + nextIdx + '" data-item-idx=' + nextIdx + '><td></td><th colspan="4">구입처</th><td colspan="5"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_mater_name_mater[' + nextIdx + ']" value=""></td><td colspan="4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_pumjil['+ (pumjilIdx +2) +']" value=""></td></tr>';
        setHtml += '<tr class="btn-add-name-mater mater-name_' + nextIdx + '" data-item-idx=' + nextIdx + '><td></td><th colspan="4">매입 단가(vat-)</th><td colspan="5"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_mater_name_danga[' + nextIdx + ']" value=""></td><td colspan="4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_pumjil['+ (pumjilIdx +3) +']" value=""></td></tr>';
        setHtml += '<tr class="btn-add-name-mater mater-name_' + nextIdx + '" data-item-idx=' + nextIdx + '><td></td><th colspan="4">연락처</th><td colspan="5"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_mater_name_tel[' + nextIdx + ']" value=""></td><td colspan="4"><input onfocus="Vail_fixed(this)" class="noborder txt_left" name="jo_pumjil['+ (pumjilIdx +4) +']" value=""></td></tr>';
        
        $("#jo_mater_name_area").append(setHtml);
    }


    function del_mater(idx) {
        $(".mater_"+idx).remove();
    }

    function prod_origin_price(){
        if (!is_checked("function")) {
            return false;
        }
        
        //원재재금액
        let a = $('#total_mater_price_fin').val().replace(/,/gi,'');
        //부자재
        let b = $('#total_sub_mater_price').val().replace(/,/gi,'');        
        //가공임계
        let c = $('#total_gakong_price').val().replace(/,/gi,'');
        

        let origin_price = (a*1) + (b*1) + (c*1) ;

        origin_price = parseInt(origin_price * 100) / 100;
        origin_price = Math.floor(origin_price * 10 / 10);
        $('#jo_prod_origin_price').val(comma(origin_price+""));
        $('#jo_prod_origin_price_view').empty().html(comma(origin_price+"") );

        prod_total_price();

    }

    function prod_total_price(){
        let oprice = $('#jo_prod_origin_price').val().replace(/,/gi,'');
        let rate = $('#jo_prod_control_price').val();

        let control_price = oprice * rate / 100 ;
                

        $('#jo_prod_control_price_view').empty().html(comma( Math.floor(control_price)+"") );

        if (!is_checked("function")) {
            return false;
            
        }

        let totalprice = ((oprice*1) + (control_price*1) ) * 1.1 ;
        totalprice = parseInt(totalprice * 100) / 100;
        totalprice = Math.floor(totalprice * 10 / 10);

        $('#jo_total_origin_price').val(comma(totalprice+""));
        $('#jo_total_origin_price_view').empty().html(comma(totalprice+"") );

    }

    function gakong_math(elem){
        comma_input(elem);
        let id = $(elem).data("gakong-idx");
        let yo = $('#jo_gakong_item_yd_'+id).val().replace(/,/gi,'');
        let danga = $('#jo_gakong_item_danga_'+id).val().replace(/,/gi,'');

        let price = yo * danga;
        price = parseInt(price * 100) / 100;
        $('#jo_gakong_item_price_'+id).val(comma(price+""));

        // $('input[name=jo_mater_info_mater_price]').val();
        // console.log($('input[name=jo_mater_info_mater_price]'));
        var fileValue = $(".jo_gakong_item_price").length;
        var fileData = new Array(fileValue);
        let total_gakong_price = 0;
        for(var i=0; i<fileValue; i++){                          
            fileData[i] = $(".jo_gakong_item_price")[i].value.replace(/,/gi,'');
            total_gakong_price += (fileData[i]*1);
        }
        total_gakong_price = parseInt(total_gakong_price * 100) / 100;
        $('#total_gakong_price').val(comma(total_gakong_price+""));
        $('#total_gakong_price_view').empty().html(comma(total_gakong_price+""));
        prod_origin_price();
    
    }

    function mater_math_re_price(){
        var fileValue = $(".jo_mater_info_price").length;
        var fileData = new Array(fileValue);
        let total_mater_price = 0;
        for(var i=0; i<fileValue; i++){                          
            fileData[i] = $(".jo_mater_info_price")[i].value.replace(/,/gi,'');
            total_mater_price += (fileData[i]*1);
        }
        total_mater_price = parseInt(total_mater_price * 100) / 100;
        $('#total_mater_price').val(comma(total_mater_price+""));
        $('#total_mater_price_view').empty().html(comma(total_mater_price+""));
        prod_origin_price();
    }


    function gakong_math_re_price(){
        var fileValue = $(".jo_gakong_item_price").length;
        var fileData = new Array(fileValue);
        let total_gakong_price = 0;
        for(var i=0; i<fileValue; i++){                          
            fileData[i] = $(".jo_gakong_item_price")[i].value.replace(/,/gi,'');
            total_gakong_price += (fileData[i]*1);
        }
        total_gakong_price = parseInt(total_gakong_price * 100) / 100;
        $('#total_gakong_price').val(comma(total_gakong_price+""));
        $('#total_gakong_price_view').empty().html(comma(total_gakong_price+""));
        prod_origin_price();
    }

    function sub_mater_math(elem){
        comma_input(elem);
        if (!is_checked("function")) {
            return false;   
        }
        let id = $(elem).data("sub-mater");
        let per = $('#per_select_'+id).val().replace(/,/gi,'');
        let yo = $('#jo_sub_mater_info_yd_'+id).val().replace(/,/gi,'');
        let danga_be = $('#jo_sub_mater_info_danga_'+id).val().replace(/,/gi,'');
        let plus_per = 0;
        if(danga_be == '-'){
            danga_be = 0;
        }
        if(per != ''){
            plus_per = per * danga_be;

            plus_per = Math.floor(plus_per);
            // $('#jo_sub_mater_info_danga_'+id).val(comma(plus_per+""));
        }else{
            plus_per = 1 * danga_be;
        }
        

        // let danga = $('#jo_sub_mater_info_danga_'+id).val().replace(/,/gi,'');

        let danga = plus_per;


        let price = yo * danga;
        price = parseInt(price * 100) / 100;
        $('#jo_sub_mater_info_price_'+id).val(comma(price+""));

        // $('input[name=jo_mater_info_mater_price]').val();
        // console.log($('input[name=jo_mater_info_mater_price]'));
        var fileValue = $(".jo_sub_mater_info_price").length;
        var fileData = new Array(fileValue);
        let total_sub_mater_price = 0;
        for(var i=0; i<fileValue; i++){                          
            fileData[i] = $(".jo_sub_mater_info_price")[i].value.replace(/,/gi,'');
            total_sub_mater_price += (fileData[i]*1);
        }
        total_sub_mater_price = parseInt(total_sub_mater_price * 100) / 100;
        $('#total_sub_mater_price').val(comma(total_sub_mater_price+""));
        $('#total_sub_mater_price_view').empty().html(comma(total_sub_mater_price+""));
        prod_origin_price();
    
    }

    function ex_mater_math1(elem){
        comma_input(elem);
        if (!is_checked("function")) {
            return false;   
        }
        let id = $(elem).data("mater-idx");
        let ex = $('#exchange_select_'+id).val();
        let yo = $('#jo_mater_info_yd_'+id).val().replace(/,/gi,'');
        let danga = $('#jo_mater_info_danga_'+id).val().replace(/,/gi,'');
        var num = 0;

        
        // $.getJSON('http://api.exchangeratesapi.io/v1/latest?access_key=a69cdab73788b1842980079e5cb80509')
        // .done(function(data){
            if( ex == undefined ) return;

            // //20210414
            // num = Math.round(danga.match(/\d+/)/data.rates[ex]);

            // num = num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
            
            // if(ex == 'USD'){num = (danga*1200);}
            // else if(ex == 'EUR'){num = (danga*1350);}
            // else if(ex == 'CNY'){num = (danga*175);}
            // else if(ex == 'JPY'){num = (danga*10);}
            // 2021-11-01

            if(ex == 'USD'){num = (danga*1100);}
            else if(ex == 'EUR'){num = (danga*1320);}
            else if(ex == 'CNY'){num = (danga*169.23);}
            else if(ex == 'JPY'){num = (danga*10);}
            else if(ex == 'KRW'){num = (danga*1);}
            

            if(yo == ''){
                yo = 1;
            }
            let price = yo * num;
            price = parseInt(price * 100) / 100;
            price = Math.floor(price * 10 / 10);
            $('#jo_mater_info_price_'+id).val(comma(price+""));

            // $('input[name=jo_mater_info_mater_price]').val();
            // console.log($('input[name=jo_mater_info_mater_price]'));
            var fileValue = $(".jo_mater_info_price").length;
            var fileData = new Array(fileValue);
            let total_mater_price = 0;
            for(var i=0; i<fileValue; i++){                          
                fileData[i] = $(".jo_mater_info_price")[i].value.replace(/,/gi,'');
                total_mater_price += (fileData[i]*1);
            }
            total_mater_price = parseInt(total_mater_price * 100) / 100;
            $('#total_mater_price').val(comma(total_mater_price+""));
            $('#total_mater_price_view').empty().html(comma(total_mater_price+""));
            //prod_origin_price();
            unim_math();
            kanse_math();
        // });

    
    }
    function unim_math(elem){
        // comma_input(elem);
        //let id = $(elem).data("mater-idx");
        if (!is_checked("function")) {
            return false;   
        }
        let id = 3;
        let yo = $('#jo_mater_info_yd_'+id).val().replace(/,/gi,'');

        let origin = $('#jo_mater_info_price_1').val().replace(/,/gi,'');        
        let lavel = 0;
        if($('#jo_mater_info_price_2').val()){
            lavel = $('#jo_mater_info_price_2').val().replace(/,/gi,'');
        }
        let price = ((origin*1) +(lavel*1)) *  yo / 100 ;
        price = Math.floor(price);
        $('#jo_mater_info_price_'+id).val(comma(price+""));

        // $('input[name=jo_mater_info_mater_price]').val();
        // console.log($('input[name=jo_mater_info_mater_price]'));
        var fileValue = $(".jo_mater_info_price").length;
        var fileData = new Array(fileValue);
        let total_mater_price = 0;
        for(var i=0; i<fileValue; i++){                          
            fileData[i] = $(".jo_mater_info_price")[i].value.replace(/,/gi,'');
            total_mater_price += (fileData[i]*1);
        }
        total_mater_price = parseInt(total_mater_price * 100) / 100;
        $('#total_mater_price').val(comma(total_mater_price+""));
        $('#total_mater_price_view').empty().html(comma(total_mater_price+""));
        prod_origin_price();
    }
    function kanse_math(elem){
        if (!is_checked("function")) {
            return false;   
        }
        // comma_input(elem);
        // let id = $(elem).data("mater-idx");
        let id = 4;
        let yo = $('#jo_mater_info_yd_'+id).val().replace(/,/gi,'');
        
        let origin = $('#jo_mater_info_price_1').val().replace(/,/gi,'');
        let lavel = 0;
        if($('#jo_mater_info_price_2').val()){
            lavel = $('#jo_mater_info_price_2').val().replace(/,/gi,'');
        }
        let unim = $('#jo_mater_info_price_3').val().replace(/,/gi,'');

        let price = ((origin*1) +(lavel*1) +(unim*1)) *  yo / 100 ;
        
        price = Math.floor(price * 10 / 10);
        $('#jo_mater_info_price_'+id).val(comma(price+""));

        // $('input[name=jo_mater_info_mater_price]').val();
        // console.log($('input[name=jo_mater_info_mater_price]'));
        var fileValue = $(".jo_mater_info_price").length;
        var fileData = new Array(fileValue);
        let total_mater_price = 0;
        for(var i=0; i<fileValue; i++){                          
            fileData[i] = $(".jo_mater_info_price")[i].value.replace(/,/gi,'');
            total_mater_price += (fileData[i]*1);
        }
        total_mater_price = parseInt(total_mater_price * 100) / 100;
        $('#total_mater_price').val(comma(total_mater_price+""));
        $('#total_mater_price_view').empty().html(comma(total_mater_price+""));
        prod_origin_price();
    }

    function mater_math1(elem){
        comma_input(elem);
        if (!is_checked("function")) {
            return false;   
        }
        let id = $(elem).data("mater-idx");
        let yo = $('#jo_mater_info_yd_'+id).val().replace(/,/gi,'');
        let danga = $('#jo_mater_info_danga_'+id).val().replace(/,/gi,'');

        let price = yo * danga;
        price = parseInt(price * 100) / 100;
        price = Math.floor(price * 10 / 10);
        $('#jo_mater_info_price_'+id).val(comma(price+""));

        // $('input[name=jo_mater_info_mater_price]').val();
        // console.log($('input[name=jo_mater_info_mater_price]'));
        var fileValue = $(".jo_mater_info_price").length;
        var fileData = new Array(fileValue);
        let total_mater_price = 0;
        for(var i=0; i<fileValue; i++){                          
            fileData[i] = $(".jo_mater_info_price")[i].value.replace(/,/gi,'');
            total_mater_price += (fileData[i]*1);
        }
        total_mater_price = parseInt(total_mater_price * 100) / 100;
        $('#total_mater_price').val(comma(total_mater_price+""));
        $('#total_mater_price_view').empty().html(comma(total_mater_price+""));
        // prod_origin_price();
        unim_math();
        kanse_math();
        mater_price_math($('#jo_maip_price_yd_1'));
    }
    function mater_price_math(elem){
        comma_input(elem);
        if (!is_checked("function")) {
            return false;   
        }
        let id = $(elem).data("price-idx");
        let yo = $('#jo_maip_price_yd_'+id).val().replace(/,/gi,'');
        let danga = $('#jo_maip_price_danga_'+id).val().replace(/,/gi,'');

        let price = yo * danga;
        price = parseInt(price * 100) / 100;
        price = Math.floor(price * 10 / 10);
        $('#jo_maip_price_price_'+id).val(comma(price+""));

        // $('input[name=jo_mater_info_mater_price]').val();
        // console.log($('input[name=jo_mater_info_mater_price]'));
        var fileValue = $(".jo_mater_info_price").length;
        var fileData = new Array(fileValue);
        let total_mater_price = 0;
        for(var i=0; i<fileValue; i++){                          
            fileData[i] = $(".jo_mater_info_price")[i].value.replace(/,/gi,'');
            total_mater_price += (fileData[i]*1);
        }
        total_mater_price = parseInt(total_mater_price * 100) / 100;
        $('#total_mater_price').val(comma(total_mater_price+""));
        $('#total_mater_price_view').empty().html(comma(total_mater_price+""));
        var fileValue_fin = $(".jo_maip_price_price").length;
        var fileData_fin = new Array(fileValue_fin);
        let total_mater_price_fin = 0;
        for(var j=0; j<fileValue_fin; j++){                          
            fileData_fin[j] = $(".jo_maip_price_price")[j].value.replace(/,/gi,'');
            total_mater_price_fin += (fileData_fin[j]*1);
        }
        total_mater_price_fin = (total_mater_price_fin + total_mater_price);
        total_mater_price_fin = parseInt(total_mater_price_fin * 100) / 100;
        $('#total_mater_price_fin').val(comma(total_mater_price_fin+""));
        $('#total_mater_price_view_fin').empty().html(comma(total_mater_price_fin+""));
        prod_origin_price();
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

    function yochek_cal1(elem){
        if (!is_checked("function")) {
            return false;
        }
        let id = $(elem).data("mater-idx");

        let wid = $('#jo_mater_info_hei_'+id).val();

        let strArray = wid.split('/');
        let a = strArray[0];
        let b = strArray[1];
        if(b == '' || b == null){
            b = 1;
        }


        let length = $('#jo_mater_info_length_'+id).val().replace(/,/gi,'');
        let price =((a/b*length)/91.44) * 1.02 ;
        price = parseInt(price * 100) / 100;
        price = Math.ceil(price * 10) / 10;
        if(price < 0.1){
            price = 0.1;
        }

        $('#jo_mater_info_yd_'+id).val(comma(price+""));
        
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

    function Vail_fixed(elem){
        if(fixed == '100'){
            var fixed = "<?=$jo['jo_price_fixed']?>";
            var dept = "<?=$aas['mb_dept']?>";
            if(dept == '상품MD팀'){
            // if(dept == '플랫폼사업팀'){
                $(elem).prop('disabled', false);
                return;
            }else{
                alert("원가확정이 된 작업지시서는 수정이 불가 합니다. \n 상품MD팀의 문의 바랍니다.");
                $(elem).prop('disabled', true);
                $(elem).blur();
            }
        }
    }
    function price_fixed(){
    
        var id = "<?=$jo['jo_id']?>";
        var ps_id = "<?=$jo['ps_id']?>";
        var type = 'fixed';

        if(id == '' || ps_id == ''){
            alert("작업지시서 저장 이후 확정처리 해주시기 바랍니다.");
            return false;
        }

        var chk  = confirm("해당 작업지시서 원가확정 하시겠습니까? \n 확정 처리 이후 수정이 불가합니다.");
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
                        alert("해당 작업지시서 원가 확정 되었습니다.");
                        location.reload();
                    }
                }
            });
        }
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