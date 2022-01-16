<?php
//$sub_menu = '930110';
$sub_menu = '94';
include_once('./_common.php');

include_once(G5_LIB_PATH . '/PHPExcel.php');
include_once(G5_LIB_PATH . '/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '세트상품설정';
include_once(G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$etc_mall_id = $_GET['etc_mall_id'];


if($od_type == "") $od_type = "L";

$sql_search = " where (1)  ";




$txt1 = $_POST['stx'];
if(empty($txt1)){
    $txt1 = $_GET['stx'];
}



$mall_codes = $_POST['mall_codes'];

$pmall_codes = $_GET['mall_codes'];

// if(in_array('전체', $mall_name_case) || !$mall_name_case) {

// } else {
//     for($mi = 0 ; $mi < count($mall_name_case); $mi++){
//         if($mi == 0){
//             $mall_name_case_list .= $mall_name_case[$mi];
//         }else{
//             $mall_name_case_list .= ",".$mall_name_case[$mi];
//         }
//     }
//     $sql_search.= " and mall_code  in ( $mall_name_case_list )";
// }

if(isset($mall_codes)){
    
    if(in_array('00', $mall_codes)){
        $mall_codes= "00";
    }else{
    
        if(!empty($mall_codes)){
    
            $sql_search.= " and mall_code  in ( $mall_codes )";
        }else{
            if(!empty($pmall_codes)){
                $mall_codes = $pmall_codes;
                $sql_search.= " and mall_code  in ( $mall_codes )";
            }else{
                $mall_codes= "";
            }
        
        }
    }
}else{
    if(!empty($pmall_codes)){
        if(in_array('00', $mall_codes)){
            $mall_codes= "";
        }else{
            $mall_codes = $pmall_codes;
            $sql_search.= " and mall_code  in ( $mall_codes )";
        }
    }else{
        $mall_codes= "";
    }
}




$mall_name_case =  explode(',', $mall_codes);


// if(!empty($mall_name_case_list)){
//     $sql_search.= " and mall_code ='{$etc_mall_id}'";
// }else{
//     // goto_url("./sabang_set_code_mapping.php?etc_mall_id=19950");
// }


if($sfl){
    if($stx){
        switch($sfl){
            // case 'IDX':
            //     preg_match_all("/[^() || \-\ \/\,]+/", $txt1,$IDX_list);
            //     $IDX_in_list = empty($IDX_list[0])?'NULL':"'".join("','", $IDX_list[0])."'";
            //     $sql_search.= " and sabang_goods_cd IN({$IDX_in_list})";
            // break;
            case 'sabang_goods_cd':
                preg_match_all("/[^() || \-\ \/\,]+/", $txt1,$sb_conpay_cd_list);
                $sb_conpay_cd_in_list = empty($sb_conpay_cd_list[0])?'NULL':"'".join("','", $sb_conpay_cd_list[0])."'";
                $sql_search.= " and sabang_goods_cd IN({$sb_conpay_cd_in_list})";

                break;
            case 'mall_goods_cd':
                preg_match_all("/[^() || \-\ \/\,]+/", $txt1,$mall_goods_cd_list);
                $mall_goods_cd_in_list = empty($mall_goods_cd_list[0])?'NULL':"'".join("','", $mall_goods_cd_list[0])."'";
                $sql_search.= "and mall_goods_cd IN({$mall_goods_cd_in_list})";
                break;
            case 'company_goods_cd':
                // preg_match_all("/[^() || \-\ \/\,]+/", $txt1,$com_goods_list);
                // $com_goods_in_list = empty($com_goods_list[0])?'NULL':"'".join("','", $com_goods_list[0])."'";
                // $sql_search.= "and company_goods_cd IN({$com_goods_in_list})";
                $sql_search .= " and company_goods_cd like '%{$txt1}%' ";
                break;
            case 'it_name':
                $sql_search .= " and set_name like '%{$txt1}%' ";
                break;
        }
    }else{

    }
}



// 테이블의 전체 레코드수만 얻음
$cnt_sql = "SELECT COUNT(*) AS cnt FROM  sabang_set_code_mapping {$sql_search}";
$cnt_row = sql_fetch($cnt_sql);

$total_count = $cnt_row['cnt'];

if($limit_list) $rows = $limit_list;
// else $rows = $config['cf_page_rows'];
else $rows = 50;
// $rows=4;

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산

if ($total_page < 2 || empty($page)) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함



$sabang_origin_goods = "select * from sabang_set_code_mapping  {$sql_search}  ORDER BY   CAST(SUBSTR(set_code , 4) AS UNSIGNED) DESC limit $from_record, $rows ";
$sb_sql = sql_query($sabang_origin_goods);
$sb_db_data = sql_fetch($sabang_origin_goods);


// $qstr = "mall_codes=".$mall_codes."&amp;limit_list=".$limit_list."&amp;page=".$page;

// if(strpos($mall_codes , '00')!==false){
//     $mall_cds = $mall_codes;
//     $qstr = "mall_codes=&amp;limit_list=".$limit_list."&amp;page=".$page;
// }else {
//     $mall_cds2 = $mall_codes;
//     $qstr = "mall_codes=".$mall_cds2."&amp;limit_list=".$limit_list."&amp;page=".$page;
// }

$mall_cds = $mall_codes;
$qstr = "mall_codes=".$mall_cds."&amp;sfl=".$sfl."&amp;stx=".$stx."&amp;limit_list=".$limit_list."&amp;page=".$page;


$oform_headers = array('판매상태','거래처코드','상품명','세트코드','옵션명(원본)','옵션명(수정)','분할가','사방넷코드','제휴몰코드');
$oform_bodys = array('status','mall_code','set_name','set_code','sku_value','company_goods_cd','set_price','sabang_goods_cd','mall_goods_cd');

$enc = new str_encrypt();

$oform_headers = $enc->encrypt(json_encode_raw($oform_headers));
$oform_bodys = $enc->encrypt(json_encode_raw($oform_bodys));

?>
<div style="background-color : #fff;">

<form name="new_goods_form" id="new_goods_form" onsubmit="" method="post">
    <input type="hidden" name = "mall_codes" id="mall_codes" value ="">
    <!-- <div>
        <button class="btn <?=$etc_mall_id == '19963' ? "btn-info" : '' ?>" type="button" onclick="location.href='./mall_price_stat.php?etc_mall_id=19963'">까사미아</button>
        <button class="btn <?=$etc_mall_id == '19950' ? "btn-info" : '' ?>" type="button" onclick="location.href='./mall_price_stat.php?etc_mall_id=19950'">SSF</button>
        <button class="btn <?=$etc_mall_id == '19979' ? "btn-info" : '' ?>" type="button" onclick="location.href='./mall_price_stat.php?etc_mall_id=19979'">네이버</button>
    </div> -->
    <div class="tbl_frm01 tbl_wrap">
        <table class="new_goods_list">
        <colgroup>
        <col class="grid_4">
        <col>
        <col class="grid_3">
        </colgroup>
        
        <tr>
            <th scope="row">검색분류</th>
            <td colspan="2">
                <label for="sfl" class="sound_only">검색대상</label>
                <select name="sfl" id="sfl">
                    <!-- <option value="it_name" <?php echo get_selected($sfl, 'it_name'); ?>>상품명</option> -->
                    <!-- <option value="IDX" <?php echo get_selected($sfl, 'IDX'); ?>>사방넷품번코드</option>
                    <option value="sap_cd" <?php echo get_selected($sfl, 'sap_cd'); ?>>SAP코드</option>
                    <option value="sam_cd" <?php echo get_selected($sfl, 'sam_cd'); ?>>삼진코드</option> -->
                    <option value="it_name" <?php echo get_selected($sfl, 'it_name'); ?>>상품명</option>
                    <option value="company_goods_cd" <?php echo get_selected($sfl, 'company_goods_cd'); ?>>옵션명(수정)</option>
                    <option value="sabang_goods_cd" <?php echo get_selected($sfl, 'sabang_goods_cd'); ?>>사방넷코드</option>
                    <option value="mall_goods_cd" <?php echo get_selected($sfl, 'mall_goods_cd'); ?>>제휴몰코드</option>
                    <!-- <option value="order_cel" <?php echo get_selected($sfl, 'order_cel'); ?>>전화번호</option>
                    <option value="receive_name" <?php echo get_selected($sfl, 'receive_name'); ?>>수취인명</option> -->
                </select>
                <label for="stx" class="sound_only">검색어</label>
                <input type="text" name="stx" value="<?php echo $txt1; ?>" id="stx" class="frm_input" autocomplete="off" onkeydown="enterSearch();">
        </td>
        </tr>
        <!-- 쇼핑몰명 -->
        <tr>
            <th scope="row">쇼핑몰명</th>
            <td colspan="2">
                <div class="col-lg-1 col-md-6 col-sm-12 col-xs-12">
                    <input onclick='allCheck("mall_name_case")' type="checkbox" name="mall_name_case[]" value="00" id="mall_name_case01" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case01">전체</label>
                    <input type="checkbox" name="mall_name_case[]" value="15001" id="mall_name_case02" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('15001', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case02">자사몰</label>
                    <input type="checkbox" name="mall_name_case[]" value="19953" id="mall_name_case03" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19953', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case03">29CM</label>
                    <input type="checkbox" name="mall_name_case[]" value="19954" id="mall_name_case04" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19954', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case04">패션플러스</label>
                    <input type="checkbox" name="mall_name_case[]" value="19956" id="mall_name_case05" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19956', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case05">이마트(신)</label>
                    <input type="checkbox" name="mall_name_case[]" value="19957" id="mall_name_case06" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19957', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case06">옥션</label>
                    <input type="checkbox" name="mall_name_case[]" value="19958" id="mall_name_case07" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19958', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case07">오늘의집</label>
                    <input type="checkbox" name="mall_name_case[]" value="19961" id="mall_name_case08" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19961', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case08">LG패션</label>
                    <input type="checkbox" name="mall_name_case[]" value="19962" id="mall_name_case09" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19962', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case09">하프클럽(신)</label>
                    <input type="checkbox" name="mall_name_case[]" value="19963" id="mall_name_case10" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19963', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case10">굳닷컴</label>
                    <input type="checkbox" name="mall_name_case[]" value="19964" id="mall_name_case11" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19964', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case11">한샘(신)</label>
                    <input type="checkbox" name="mall_name_case[]" value="19965" id="mall_name_case12" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19965', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case12">현대홈쇼핑(신)</label>
                    <input type="checkbox" name="mall_name_case[]" value="19966" id="mall_name_case13" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19966', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case13">AKmall(신)</label>
                    <input type="checkbox" name="mall_name_case[]" value="19967" id="mall_name_case14" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19967', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case14">GS shop</label>
                    <input type="checkbox" name="mall_name_case[]" value="19968" id="mall_name_case15" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19968', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case15">롯데온</label>
                    <input type="checkbox" name="mall_name_case[]" value="19970" id="mall_name_case16" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19970', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case16">신세계몰(신)</label>
                    <input type="checkbox" name="mall_name_case[]" value="19971" id="mall_name_case17" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19971', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case17">CJOshopping (신)</label>
                    <br>
                    <input type="checkbox" name="mall_name_case[]" value="19972" id="mall_name_case18" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19972', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case18">롯데홈쇼핑(신)</label>
                    <input type="checkbox" name="mall_name_case[]" value="19973" id="mall_name_case19" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19973', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case19">쿠팡</label>
                    <input type="checkbox" name="mall_name_case[]" value="19974" id="mall_name_case20" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19974', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case20">위메프(신)</label>
                    <input type="checkbox" name="mall_name_case[]" value="19975" id="mall_name_case21" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19975', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case21">티몬</label>
                    <input type="checkbox" name="mall_name_case[]" value="19976" id="mall_name_case22" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19976', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case22">11번가</label>
                    <input type="checkbox" name="mall_name_case[]" value="19977" id="mall_name_case23" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19977', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case23">지마켓</label>
                    <input type="checkbox" name="mall_name_case[]" value="19978" id="mall_name_case24" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19978', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case24">카카오</label>
                    <input type="checkbox" name="mall_name_case[]" value="19940" id="mall_name_case25" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19940', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case25">카카오메이커스</label>
                    <input type="checkbox" name="mall_name_case[]" value="19979" id="mall_name_case26" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19979', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case26">스마트스토어</label>
                    <input type="checkbox" name="mall_name_case[]" value="19978" id="mall_name_case27" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19978', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case27">카카오톡스토어</label>
                    <input type="checkbox" name="mall_name_case[]" value="19978" id="mall_name_case28" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19978', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case28">카카오선물하기</label>
                    <input type="checkbox" name="mall_name_case[]" value="19951" id="mall_name_case29" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19951', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case29">집꾸미기(3)</label>
                    <input type="checkbox" name="mall_name_case[]" value="19950" id="mall_name_case30" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19950', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case30">SSF SHOP</label>
                    <input type="checkbox" name="mall_name_case[]" value="19955" id="mall_name_case31" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19955', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case31">텐바이텐</label>
                    <input type="checkbox" name="mall_name_case[]" value="19952" id="mall_name_case32" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19952', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case32">현대리바트(신)</label>
                    <br>
                    <input type="checkbox" name="mall_name_case[]" value="19942" id="mall_name_case33" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19942', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case33">이랜드몰</label>
                    <input type="checkbox" name="mall_name_case[]" value="19943" id="mall_name_case34" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19943', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case34">브랜디</label>
                    <input type="checkbox" name="mall_name_case[]" value="19944" id="mall_name_case35" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19944', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case35">한섬_EQL</label>
                    <input type="checkbox" name="mall_name_case[]" value="19945" id="mall_name_case36" <?php if(in_array('', $mall_name_case) || in_array('00', $mall_name_case) || in_array('19945', $mall_name_case) || !$mall_name_case) echo "checked"; ?>>
                    <label for="mall_name_case35">코오롱FNC</label>
                </div>
            </td>
        </tr>
        <tr>
            <th scope="row">보기</th>
            <td colspan="2">
            <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                <select name="limit_list" id="limit_list">
                    <option value="50" <?php if(!$limit_list ||(substr_count($limit_list, '50') >= 1)) echo "selected"; ?>>50개</option>
                    <option value="100" <?php if(substr_count($limit_list, '100') >= 1) echo "selected"; ?>>100개</option>
                    <option value="200" <?php if(substr_count($limit_list, '200') >= 1) echo "selected"; ?>>200개</option>
                    <option value="300" <?php if(substr_count($limit_list, '300') >= 1) echo "selected"; ?>>300개</option>
                    <option value="400" <?php if(substr_count($limit_list, '400') >= 1) echo "selected"; ?>>400개</option>
                    <option value="500" <?php if(substr_count($limit_list, '500') >= 1) echo "selected"; ?>>500개</option>
                </select>
            </div>
            </td>
        </tr>

        </table>
    </div>
    <div class="form-group">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <!-- <button class="btn btn_02 search-reset" type="button" id="btn_clear">초기화</button> -->
            <input type="button" value="초기화" class="btn btn_02" onclick="outputCountReset()">
            <button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i>검색</button>
        </div>
    </div>
</form>

<style>
    th {text-align : center;}
    th.txt_left {text-align : left;}
    th, td {
        white-space: nowrap;
    }
    .sabang_goods_table{
        white-space : nowrap;
    }
    .frm_input{width : 88%;}

    .sabang_goods_table table {
        width: 100%;
        border: 1px solid #444444;
        border-collapse: collapse;
    }
    .sabang_goods_table th, .sabang_goods_table td {
        border: 1px solid #444444;
        text-align: center;
    }

    #chkall{
        cursor: pointer;
        color: blue;
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
    table.dataTable tr, td{
        border: 1px solid lightgray;
    }
    table.dataTable tr > td{
        border: 1px solid lightgray;
    }
    table.dataTable tr > th {
        border: 1px solid lightgray;
        background:#eeeeee;
    }
    .dataTables_wrapper.no-footer .dataTables_scrollBody{
        border-bottom : 0px !important;
    }
    .dataTables_scrollBody input.ab {display : none;}


</style>

<div class="local_ov01 local_ov">
    <span class="btn_ov01">[ 총 건수 : <?= number_format($total_count); ?>건 ]</span>
</div>
</div>
<form name="set_code_table" id="set_code_table" onsubmit="" method="post">
    <div>
        <input type='file' name ="upload_excel" id='upload_excel' />
        <div class="btn btn_02" style="height: 30px;" id="upload_excel_btn">
            엑셀 업로드
        </div>
        <div class="btn btn_02 btn-success" style="height: 30px;" onclick="save()">
            <input type ="hidden" class="btn btn-success" >저장
        </div>
        <div class="btn btn_02" style="height: 30px; float : right;" onclick="download_execl()">
            <input type ="hidden" class="btn" >전체엑셀 다운로드
        </div>
        <div>
            <select name = "status_ch" id = "status_ch" >
                <option value = "">상품상태</option>
                <option value = "0001">판매중</option>
                <option value = "0002">판매중지</option>
            </select>
            <div class="btn btn_02" style="height: 30px;" onclick="status_ch();">
            선택 상품상태변경
            <input type ="hidden" name ="cno_list" id="cno_list" >
            </div>
        </div>
    </div>
    
    <div class="tbl_head01 tbl_wrap" id="topscroll"  style="width:100%; margin:0px; overflow-x:scroll;">
        <div class="div1" style="height:20px;"></div>
    </div>
    
    <div class="dataTables_scrollBody" style="overflow-x:scroll;">
        <table id="reportTb" class="display" style="width:100%">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col width="10%">
            <col>
            <col>
            <col>
        </colgroup>
        <thead>
            <tr>
                <th id="chkall" class="chk_all" onclick="all_chk(this.form)" >전체</th>
                <th>판매상태</th>
                <th>몰구분<br>(거래처코드)</th>
                <th>상품명</th>
                <th>세트코드(내부용)</th>
                <th>옵션명(원본)</th>
                <th>옵션명(수정)</th>
                <th>삼진상품명</th>
                <th>색상</th>
                <th>사이즈</th>
                <th>분할가</th>
                <th>분할가/원가비</th>
                <th>사방넷코드</th>
                <th>제휴몰코드</th>
                <th>등록일자</th>
                <!-- <th>수수료</th>
                <th>상품ID</th>
                <th>상품옵션ID</th>
                <th>상품옵션ID판매가</th>
                <th>상품옵션ID할인가</th>
                <th>SAP코드</th>
                <th>할인율</th>
                <th>할인금액</th>
                <th>단품금액</th>
                <th>판매자</th>
                <th>구성수량</th>
                <th>재고</th>
                <th>균일가여부</th> -->
                
            </tr>
        </thead>
        <tbody id="revenue-status">
        <?php
            for ($i = 0; $row = sql_fetch_array($sb_sql); $i++) {
                //$str_confirm = sprintf("'%s','%s','%s',%d,'%s'", $row['ORDER_NO'], $row['SAP_CODE'], $row['ITEM'], $row['PRICE'], $_POST['subID']);
                

                ?>
            <tr>
                <td><input type = "checkbox" name = "chk[]" value = "<?=$i?>"></td>
                <td><?=$row['status'] == '0001' ? '판매중' : '판매중지' ?></td>
                <input type = "hidden" name = "cno[]" value = "<?=$i?>">
                <input type = "hidden" name = "item_cno[<?=$i?>]" value = "<?=$row['cno']?>">
                <td><?=$row['mall_code']?></td>
                <td><?=$row['set_name']?></td>
                <td><?=$row['set_code']?></td>
                <td><?=$row['sku_value']?></td>
                <td style="padding:0; "> <div style="width:200px; display:block;"> <input style="width:100%; display:block;"  name = "company_goods_cd[<?=$i?>]" value = "<?=$row['company_goods_cd']?>"></div></td>
                <?
                    $sapCode12 = substr($row['company_goods_cd'] , 0,12);
                    $color = substr($row['company_goods_cd'] , 12,2);
                    if(strpos($row['company_goods_cd'], '+') === false){
                        $size = substr($row['company_goods_cd'] , 14); 
                    }else{
                        $size = substr($row['company_goods_cd'] , 14 , strpos($row['company_goods_cd'], '+')-14 );
                    }
                    
                    
                    $connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
                    $g5['connect_samjindb'] = $connect_db;
                    $sqlSamjin = "SELECT ORDER_NO, SAP_CODE, ITEM , WONGA FROM S_MALL_ORDERS WHERE SAP_CODE = '{$sapCode12}' AND COLOR = '$color' AND HOCHING = '$size'";
                    $rsSamjin = mssql_sql_query($sqlSamjin);

                    $samjin_name = '';
          
                    for ($k = 0; $samrow = mssql_sql_fetch_array($rsSamjin); $k++) {  
                        $samjin_name = $samrow['ITEM'];
                    }                
                ?>
                <td><?=$samjin_name?> </td>
                <td><?=$color?> </td>
                <td><?=$size?> </td>
                <td style="padding:0; "> <div style="width:100px; display:block;"> <input style="width:100%; display:block;"  name = "set_price[<?=$i?>]" value = "<?=number_format($row['set_price'])?>"></div></td>
                <td style="padding:0; ">
                    <div style="display:block; margin:0;">
                        <!-- <input style="width:100%; display:block;"  name = "set_price[<?=$i?>]" value = "<?=number_format($row['set_price'])?>"> -->
                        <select style="width:100%; display:block; text-align-last: center; padding:0; height: 22px;"  name = "set_price_type[<?=$i?>]" >
                            <option value = "Y" <?php if($row['set_price_type'] == 'Y') echo "selected"; ?>>분할가</option>
                            <option value = "N" <?php if($row['set_price_type'] == 'N') echo "selected"; ?>>원가비</option>
                        </select>
                    </div>
                </td>
                <td><?=$row['sabang_goods_cd']?></td>
                <td><?=$row['mall_goods_cd']?></td>
                <td><?=$row['reg_date']?></td>
                
            </tr>
        <?php
        } ?>
        </tbody>
    </table>
    </div>

</form>
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
</div>

<link rel="stylesheet" href="../total_order/fixed_table.css">
<script src="../total_order/fixed_table.js"></script>
<script>
    $(document).ready(function () {
        // var table = $('#reportTb').DataTable({
        //     scrollY: "650px",
        //     scrollX: true,
        //     scrollCollapse: true,
        //     ordering: false,
        //     info: false,
        //     paging: false,
        //     searching: false,
        //     createdRow: function (row, data, dataIndex) {

        //         //ROWSPAN
        //         if (dataIndex == 0) {
        //             //$('td:eq(0)', row).attr('rowspan', 9);
        //         }
        //         else {
        //             if (data[1] === '대한항공') {
        //                 //$('td:eq(0)', row).attr('rowspan', 9);
        //             }
        //             else {
        //                 //$('td:eq(0)', row).css('display', 'none');
        //             }
        //         }
        //         //COLSPAN
        //         if (data[1] === '합계') {
        //             $('td:eq(1)', row).attr('colspan', 2);
        //         }

        //         //CSS셋팅
        //         // $('th:eq(0)', row).css('text-align', 'center');
        //         // $('td:not(:eq(0))', row).css('text-align', 'center');
        //         // $('td:eq(0)', row).css('text-align', 'center');
        //         // $('td:eq(2)', row).css('text-align', 'left');
        //         // $('td:eq(5)', row).css('text-align', 'left');
        //         // $('td:eq(12)', row).css('text-align', 'left');
                
        //     },
        //     fixedColumns: {
        //         // leftColumns: 3
        //     }

        // });

        $("#topscroll .div1").css('width',$("#reportTb").innerWidth() +'px');

        $("#topscroll").scroll(function(){
            $(".dataTables_scrollBody").scrollLeft($("#topscroll").scrollLeft());
        });
        $(".dataTables_scrollBody").scroll(function(){
            $("#topscroll").scrollLeft($(".dataTables_scrollBody").scrollLeft());
        });
        

        $('#upload_excel').hide();
        $('#upload_excel_btn').on('click', function () {$('#upload_excel').click();});

        $('#upload_excel').change(function () {
            var file = this.files[0];
            var reader = new FileReader();
            reader.onloadend = function () {
            //    $('#main_pf_foto_img').attr('src', reader.result);
            }
            if (file) {
                reader.readAsDataURL(file);
            } else {
            }
            upload_excel();
        });

        $("input[name='mall_name_case[]']").change(function () {
            var  mall_codes = "";
            $("input[name='mall_name_case[]']:checked").each(function(){
                
                if(mall_codes != "") mall_codes += ",";
                mall_codes += $(this).val();
            });

            $("#mall_codes").val(mall_codes);
        });
        
    });

    function outputCountReset() {
        $("input:checkbox[id*='_case']").prop("checked",true);
        // $("#ov_search_type").val('ov_it_name');
        $(".frm_input").val('');
        // $("#ov_search_keyword").val('');
        // $("#outputCount").val(200);
    }
    function allCheck(e=false) {
        let allResult = false;
        if (e!='chk_') allResult = $('#'+e+'01').prop("checked");
        else allResult = $("input:checkbox[id='allchk']").is(":checked");
        if (allResult) {
            $("input:checkbox[id^='"+e+"']").prop("checked",true);
        } else {
            $("input:checkbox[id^='"+e+"']").prop("checked",false);
        }
    }

    function enterSearch() {
        if (window.event.keyCode == 13) {
        	document.getElementById('new_goods_form').submit();
    	}
    }

    function all_chk(f){
        // $(".DTFC_LeftBodyLiner input[name='chk[]']").prop("checked" , true);
        if($(".chk_all").hasClass("allchks")){
            $(".chk_all").removeClass("allchks");
            $("input[name='chk[]']").prop("checked" , false);
        }else{
            $(".chk_all").addClass("allchks");
            $("input[name='chk[]']").prop("checked" , true);
        }
    }

    function save(){
        // alert("asdf");
        var formData = $("#set_code_table").serialize();

        $.ajax({
            cache : false,
            url : "./save_sabang_set_code_mapping.php", // 요기에
            type : 'POST', 
            data : formData, 
            success : function(data) {
                // var jsonObj = JSON.parse(data);
				// console.log(data);
				location.reload();
            }, // success 

            error : function(xhr, status) {
                alert(xhr + " : " + status);
            }
        }); 
        

    }

    function upload_excel(){
        var $excelfile = $("#upload_excel");

        // var etc_mall_id = $('<input type="hidden" value="<?=$etc_mall_id?>" name="etc_mall_id">');
        
        var $form = $('<form></form>');     
        $form.attr('action', './upload_sabang_set_code_mapping.php');
        $form.attr('method', 'post');
        $form.attr('enctype', 'multipart/form-data');
        $form.appendTo('body');
        $form.append($excelfile);

    
        $form.submit();
        
    }

    function status_ch(){
        if (!is_checked("chk[]")) {
            alert("변경할 상품을 선택해 주세요.");
            return false;
        }
        var chk_stat = $("#status_ch").val();
        if(chk_stat == ''){
            alert("변경할 상품 상태값을 선택해 주세요.");
            return false;
        }
        var $select = new Array();
        $("#cno_list").val('');

        $("input[name='chk[]']:checked").each(function() {
            var sb_cd = $("input[name='item_cno["+this.value+"]']").val();
            $select.push(sb_cd);

        });

        var selects = $select.join(",");
        if ($("#cno_list").val() != "") selects += "," + $("#cno_list").val();
        $("#cno_list").val(selects);

        var cnos = $("#cno_list").val();
        var type = "status";
        
        var result = confirm("판매상태를 변경 하시겠습니까?");
        if(result){
            $.ajax({
                url: "./sabang_set_code_status_update.php",
                method: "POST",
                data: {
                    "cnos": cnos,
                    "status" : chk_stat
                    
                },
                dataType: "json",
                async : false,
                cache: false,
                success: function(result) {
                    // console.log(result);
                    if (result.indexOf('200') !== -1){
                        // window.open("../sabang/send_sabang_new_goods_form2.php");
                        alert("판매상태 변경완료!");
                        location.reload();
                    }
                    // location.reload();
                    // if (result.indexOf('200') !== -1){
                    //     alert("사방넷 전송 성공!");
                    //     // location.reload();
                    // }
                }
            });
        }

        
    }

    function download_execl(){

        var mall_codes  =  <?=json_encode( $mall_name_case )?>;
        
        if(mall_codes != '' && mall_codes != null){
            excel_sql = "select * from sabang_set_code_mapping where mall_code in ( "+mall_codes +" ) ORDER BY reg_date ASC ";
        }else{
            excel_sql = "select * from sabang_set_code_mapping  ORDER BY reg_date ASC ";
        }

        
        headerdata = $('<input type="hidden" value="<?=$oform_headers?>" name="headerdata">');
        bodydata = $('<input type="hidden" value="<?=$oform_bodys?>" name="bodydata">');
        excel_type = '세트상품설정';

        var $form = $('<form></form>');     
        $form.attr('action', './down_sabang_set_code_mapping.php');
        $form.attr('method', 'post');
        $form.appendTo('body');
        
        var exceldata = $('<input type="hidden" value="'+excel_sql+'" name="exceldata">');
        
        var excelnamedata = $('<input type="hidden" value="'+excel_type+'" name="excelnamedata">');
        $form.append(exceldata).append(headerdata).append(bodydata).append(excelnamedata);
        $form.submit();
        
    }

    

</script>

<?php 
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
