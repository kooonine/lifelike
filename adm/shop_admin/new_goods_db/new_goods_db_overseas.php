<?php
//$sub_menu = '930110';
$sub_menu = '93';
include_once('./_common.php');


auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '상품DB전산화(해외)';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

if($od_type == "") $od_type = "L";

$sql_search = " where (1)  ";

$txt1 = $_POST['stx'];

if($sfl){
    if($stx){
        switch($sfl){
            // case 'IDX':
            //     preg_match_all("/[^() || \-\ \/\,]+/", $txt1,$IDX_list);
            //     $IDX_in_list = empty($IDX_list[0])?'NULL':"'".join("','", $IDX_list[0])."'";
            //     $sql_search.= " and sabang_goods_cd IN({$IDX_in_list})";
            // break;
            // case 'sb_conpany_cd':
            //     preg_match_all("/[^() || \-\ \/\,]+/", $txt1,$sb_conpay_cd_list);
            //     $sb_conpay_cd_in_list = empty($sb_conpay_cd_list[0])?'NULL':"'".join("','", $sb_conpay_cd_list[0])."'";
            //     $sql_search.= " and sabang_goods_cd IN({$sb_conpay_cd_in_list})";
            //     break;
            // case 'sap_cd':
            //     preg_match_all("/[^() || \-\ \/\,]+/", $txt1,$model_no_list);
            //     $model_no_in_list = empty($model_no_list[0])?'NULL':"'".join("','", $model_no_list[0])."'";
            //     $sql_search.= "and model_no IN({$model_no_in_list})";
            //     break;
            case 'sam_cd':
                preg_match_all("/[^() || \-\ \/\,]+/", $txt1,$samjin_code);
                $samjin_code_in_list = empty($samjin_code[0])?'NULL':"'".join("','", $samjin_code[0])."'";
                $sql_search.= "and samjin_code IN({$samjin_code_in_list})";
                break;
            case 'it_name':
                $sql_search .= " and item_name like '%{$txt1}%' ";
                break;
        }
    }else{

    }
}

$all_brand = false;
if ($brands) {
    $brand_item = implode("','", explode(',', $brands));
    $sql_search .= " and brand in ('{$brand_item}') ";
}else{
    $all_brand=true;
}


if(!$ings){
    // $sql_search .= " and ps_ipgo_status = 'N' ";
}else{
    if ($ings == 'RUN') {
        $sql_search .= " and run_out = 'RUN' ";
    }else{
        $ings_item = implode("','", explode(',', $ings));
        $sql_search .= " and run_out in ('{$ings_item}') ";
    }
}


if(!$channals){
    // $sql_search .= " and ps_channal_status = 'N' ";
}else{
    if ($channals == '오프라인') {
        $sql_search .= " and channal = '오프라인' ";
    }else{
        $channals_item = implode("','", explode(',', $channals));
        $sql_search .= " and channal in ('{$channals_item}') ";
    }
}



// 테이블의 전체 레코드수만 얻음
$cnt_sql = "SELECT COUNT(*) AS cnt FROM  new_goods_db_overseas {$sql_search}";
$cnt_row = sql_fetch($cnt_sql);

$total_count = $cnt_row['cnt'];

if($limit_list) $rows = $limit_list;
// else $rows = $config['cf_page_rows'];
else $rows = 50;
// $rows=4;

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산

if ($total_page < 2 || empty($page)) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$new_goods_db_overseas = "select * from new_goods_db_overseas  {$sql_search} order by no desc limit $from_record, $rows ";
$ovs_sql = sql_query($new_goods_db_overseas);
$ovs_db_data = sql_fetch($new_goods_db_overseas);


$headers = array('진행여부','브랜드','채널','아이템1','아이템2','삼진코드','아이템','색상','특이사항','MOQ','최근확인일자','추가확정일자','총재고',' ',' ','창고재고',' ',' ','매장재고',' ',' ','최근3개월(-)',' ',' ','전년3개월(+)',' ',' ','최근6개월(-)',' ',' ','전년6개월(+)',' ',' ','1년(작년)',' ',' ','1년(제작년)',' ',' ','발주수량',' ',' ','제품정보',' ','비고');
$headers2 = array(' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ','S/SS/L','Q','K','S/SS/L','Q','K','S/SS/L','Q','K','S/SS/L','Q','K','S/SS/L','Q','K','S/SS/L','Q','K','S/SS/L','Q','K','S/SS/L','Q','K','S/SS/L','Q','K','S/SS/L','Q','K','제조국','제조업체명',' ');

// $headers = array('진행여부','브랜드','채널','아이템1','아이템1','삼진코드','아이템','색상','특이사항','MOQ','최근확인일자','추가확정일자','총재고',' ',' ','창고재고',' ',' ','매장재고',' ',' ','판매량(최근3개월)',' ',' ','판매량(전년동월)',' ',' ','판매량(최근6개월)',' ',' ','판매량(전년동월)',' ',' ','매장재고',' ',' ','판매량(작년1년)',' ',' ','판매량(제낙년1년)',' ',' ','발주수량',' ',' ','제품정보',' ','비고');
// $headers = array('진행여부','브랜드','채널','아이템1','아이템1','삼진코드','아이템','색상','특이사항','MOQ','최근확인일자','추가확정일자','S/SS/L','Q','K','S/SS/L','Q','K','S/SS/L','Q','K','S/SS/L','Q','K','S/SS/L','Q','K','S/SS/L','Q','K','S/SS/L','Q','K','S/SS/L','Q','K','S/SS/L','Q','K','S/SS/L','Q','K','S/SS/L','Q','K','제조국','제조업체명','비고');

$bodys = array('run_out','brand','channal','item_1','item_2','samjin_code','item_name','color_nm','memo','moq','lately_date','add_date','total_stock_s','total_stock_k','total_stock_q','dpart_stock_s','dpart_stock_q','dpart_stock_k','rep_stock_s','rep_stock_q','rep_stock_k','rep_sale_sum3m_s','rep_sale_sum3m_q','rep_sale_sum3m_k','rep_sale_sum3p_s','rep_sale_sum3p_q','rep_sale_sum3p_k','rep_sale_sum6m_s','rep_sale_sum6m_q','rep_sale_sum6m_k','rep_sale_sum6p_s','rep_sale_sum6p_q','rep_sale_sum6p_k','rep_sale_sum1y_s','rep_sale_sum1y_q','rep_sale_sum1y_k','rep_sale_sum2y_s','rep_sale_sum2y_q','rep_sale_sum2y_k','balju_qty_s','balju_qty_q','balju_qty_k','maker_ct','maker_cp','etc_meg');

$enc = new str_encrypt();

$headers = $enc->encrypt(json_encode_raw($headers));
$headers2 = $enc->encrypt(json_encode_raw($headers2));
$bodys = $enc->encrypt(json_encode_raw($bodys));
function prod_gb_ch($text){
    if(preg_match("/[a-zA-Z]/",$text)){
        switch($text){
            case 'MA' : $prod_gb = "임가공"; break;
            case 'MW' : $prod_gb = "완사입"; break;
            case 'MD' : $prod_gb = "임가공"; break;
            case 'MS' : $prod_gb = "완사입"; break;
            case 'MX' : $prod_gb = "임가공"; break;
        }
    }else{
        $prod_gb = $text;
    }
    return $prod_gb;
}   

?>
<script src="../total_order/jquery.table2excel.js"></script>
<div style="background-color : #fff;">

<form name="new_goods_form" id="new_goods_form" onsubmit="" method="post">
    <input type="hidden" name = "brands" value='<?=$brands?>' id="brands">
    <input type="hidden" name = "ings" value='<?=$ings?>' id="ings">
    <input type="hidden" name = "channals" value='<?=$channals?>' id="channals">
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
                    <option value="sb_conpany_cd" <?php echo get_selected($sfl, 'sb_conpany_cd'); ?>>사방넷자체상품코드</option>
                    <option value="sap_cd" <?php echo get_selected($sfl, 'sap_cd'); ?>>SAP코드</option> -->
                    <option value="it_name" <?php echo get_selected($sfl, 'it_name'); ?>>아이템명</option>
                    <option value="sam_cd" <?php echo get_selected($sfl, 'sam_cd'); ?>>삼진코드</option>
                    <!-- <option value="invoice" <?php echo get_selected($sfl, 'invoice'); ?>>송장번호</option>
                    <option value="order_cel" <?php echo get_selected($sfl, 'order_cel'); ?>>전화번호</option>
                    <option value="receive_name" <?php echo get_selected($sfl, 'receive_name'); ?>>수취인명</option> -->
                </select>
                <label for="stx" class="sound_only">검색어</label>
                <input type="text" name="stx" value="<?php echo $txt1; ?>" id="stx" class="frm_input" autocomplete="off" onkeydown="enterSearch();">
        </td>
        </tr>
        <tr>
            <th scope="row">브랜드</th>
            <td colspan="2">
            <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                <label><input type="checkbox" onkeydown="enterSearch();" value="" id="brand_0"  <?php if(!$brands) echo "checked"; ?>>전체</label>&nbsp;&nbsp;
                <label><input type="checkbox" onkeydown="enterSearch();" value="소프라움" id="brand_1" class="brand" <?php if((substr_count($brands, '소프라움') >= 1) || $all_brand) echo "checked"; ?>  >소프라움</label>&nbsp;&nbsp;
                <label><input type="checkbox" onkeydown="enterSearch();" value="쉐르단" id="brand_2" class="brand" <?php if((substr_count($brands, '쉐르단') >= 1) || $all_brand) echo "checked"; ?> >쉐르단</label>&nbsp;&nbsp;
                <label><input type="checkbox" onkeydown="enterSearch();" value="랄프로렌홈" id="brand_3" class="brand" <?php if((substr_count($brands, '랄프로렌홈') >= 1)|| $all_brand) echo "checked"; ?> >랄프로렌홈</label>&nbsp;&nbsp;
                <label><input type="checkbox" onkeydown="enterSearch();" value="베온트레" id="brand_4" class="brand" <?php if((substr_count($brands, '베온트레') >= 1) || $all_brand) echo "checked"; ?> >베온트레</label>&nbsp;&nbsp;
                <label><input type="checkbox" onkeydown="enterSearch();" value="링스티드던" id="brand_5" class="brand" <?php if((substr_count($brands, '링스티드던') >= 1) || $all_brand) echo "checked"; ?> >링스티드던</label>&nbsp;&nbsp;
                <label><input type="checkbox" onkeydown="enterSearch();" value="로자리아" id="brand_6" class="brand" <?php if((substr_count($brands, '로자리아') >= 1) || $all_brand) echo "checked"; ?> >로자리아</label>&nbsp;&nbsp;
                <label><input type="checkbox" onkeydown="enterSearch();" value="그라치아노" id="brand_7" class="brand" <?php if((substr_count($brands, '그라치아노') >= 1) || $all_brand) echo "checked"; ?> >그라치아노</label>&nbsp;&nbsp;
                <label><input type="checkbox" onkeydown="enterSearch();" value="시뇨리아" id="brand_8" class="brand" <?php if((substr_count($brands, '시뇨리아') >= 1) || $all_brand) echo "checked"; ?> >시뇨리아</label>&nbsp;&nbsp;
                <label><input type="checkbox" onkeydown="enterSearch();" value="플랫폼일반" id="brand_9" class="brand" <?php if((substr_count($brands, '플랫폼일반') >= 1) || $all_brand) echo "checked"; ?> >플랫폼일반</label>&nbsp;&nbsp;
                <label><input type="checkbox" onkeydown="enterSearch();" value="플랫폼렌탈" id="brand_10" class="brand" <?php if((substr_count($brands, '플랫폼렌탈') >= 1) || $all_brand) echo "checked"; ?> >플랫폼렌탈</label>&nbsp;&nbsp;
                <label><input type="checkbox" onkeydown="enterSearch();" value="온라인" id="brand_11" class="brand" <?php if((substr_count($brands, '온라인') >= 1) || $all_brand) echo "checked"; ?> >온라인</label>&nbsp;&nbsp;
                <label><input type="checkbox" onkeydown="enterSearch();" value="템퍼" id="brand_12" class="brand" <?php if((substr_count($brands, '템퍼') >= 1) || $all_brand) echo "checked"; ?> >템퍼</label>&nbsp;&nbsp;
            </div>
            </td>
        </tr>
        <tr>
            <th scope="row">진행여부</th>
            <td colspan="2">
            <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                <label><input type="checkbox" onkeydown="enterSearch();" value="" id="ing_0"  <?php if(!$ings) echo "checked"; ?>>전체</label>&nbsp;&nbsp;
                <label><input type="checkbox" onkeydown="enterSearch();" value="RUN" id="ing_1" class="ing" <?php if((substr_count($ings, 'RUN') >= 1) || $all_ing) echo "checked"; ?>  >RUN</label>&nbsp;&nbsp;
                <label><input type="checkbox" onkeydown="enterSearch();" value="OUT" id="ing_2" class="ing" <?php if((substr_count($ings, 'OUT') >= 1) || $all_ing) echo "checked"; ?> >OUT</label>&nbsp;&nbsp;
            </div>
            </td>
        </tr>
        <tr>
            <th scope="row">채널</th>
            <td colspan="2">
            <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                <label><input type="checkbox" onkeydown="enterSearch();" value="" id="channal_0"  <?php if(!$channals) echo "checked"; ?>>전체</label>&nbsp;&nbsp;
                <label><input type="checkbox" onkeydown="enterSearch();" value="오프라인" id="channal_1" class="channal" <?php if((substr_count($channals, '오프라인') >= 1) || $all_channal) echo "checked"; ?>  >오프라인</label>&nbsp;&nbsp;
                <label><input type="checkbox" onkeydown="enterSearch();" value="온라인" id="channal_2" class="channal" <?php if((substr_count($channals, '온라인') >= 1) || $all_channal) echo "checked"; ?> >온라인</label>&nbsp;&nbsp;
                <label><input type="checkbox" onkeydown="enterSearch();" value="공통" id="channal_3" class="channal" <?php if((substr_count($channals, '공통') >= 1)|| $all_channal) echo "checked"; ?> >공통</label>&nbsp;&nbsp;
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
            <button class="btn btn_02 search-reset" type="button" id="btn_clear">초기화</button>
            <button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i>검색</button>
        </div>
    </div>
</form>

<style>
    table {font-size :13px;}
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
    .hidden_ {display : none;}
    .st_btn , .sl_btn {position : absolute; right : -8px;}
    .dataTables_sizing .st_btn , .dataTables_sizing .sl_btn {display : none;}
    .st_btn.right , .sl_btn.right { background: url("/img/re/paging_next@2x.png"); width :12px; height:16px;}
    .st_btn.left , .sl_btn.left { background: url("/img/re/paging_prev@2x.png");width :12px; height:16px;}

</style>

<div class="local_ov01 local_ov">
    <span class="btn_ov01">[ 총 건수 : <?= number_format($total_count); ?>건 ]</span>
</div>
</div>
<form name="fwrite" id="fwrite" action="./update_new_goods_db_overseas.php" method="post" enctype="multipart/form-data" autocomplete="off">
    <div>
        <div class="btn btn_02" style="height: 30px;" onclick="down_excel();">
            <input type ="hidden" >엑셀다운로드
        </div>
        <div class="btn btn_02" style="height: 30px;" onclick="all_down_excel();">
            <input type ="hidden" >전체엑셀다운로드
        </div>
        <!-- <div class="btn btn_02" style="height: 30px;" onclick="sabang_send();">
            <input type ="hidden" name ="sabang_goods_cds" id="sabang_goods_cds" >최근확인일자
        </div>
        <div class="btn btn_02" style="height: 30px;" onclick="sabang_send();">
            <input type ="hidden" name ="sabang_goods_cds" id="sabang_goods_cds" >추가확정일자
        </div> -->
        <div class="btn btn_02 btn-success" style="height: 30px;" onclick="formsubmit()">
            <input type ="hidden" class="btn btn-success" >저장
        </div>
    </div>
    

    <table id="reportTb" name="reportTb" class="display" style="width:100%">
        <div class="tbl_head01 tbl_wrap" id="topscroll"  style="width:100%; margin:0px; overflow-x:scroll;">
            <div class="div1" style="width:1950px; height:20px;"></div>
        </div>
        <thead>
            <tr>
                <th class="noExport no-sort" rowspan = "2">
                    <label for="chkall" class="sound_only">선택 전체</label>
                    <!-- <input type="checkbox" name="chkall"  id="chkall" class="chk_all" onclick="all_chk(this.form)"   /> -->
                    <input onclick='allCheck2("chk_")' type="checkbox" id ='allchk'>
                </th>
                <th rowspan = "2">진행여부</th>
                <th rowspan = "2">브랜드</th>
                <th rowspan = "2">채널</th>
                <th rowspan = "2">아이템1</th>
                <th rowspan = "2">아이템2</th>
                <th rowspan = "2">생산구분</th>
                <th rowspan = "2">시즌</th>
                <th rowspan = "2">삼진코드</th>
                <th rowspan = "2">아이템</th>
                <th rowspan = "2">색상</th>
                <th rowspan = "2">특이사항</th>
                <th rowspan = "2">MOQ</th>
                <th rowspan = "2">최근확인일자</th>
                <th rowspan = "2">추가확정일자</th>
                <th colspan = "3" style="position:relative;" onclick="all_stock(event)">총재고 <span class="st_btn right"><span></th>
                <th colspan = "3" class="hidden_st">창고재고</th>
                <th colspan = "3" class="hidden_st">매장재고</th>
                <th colspan = "3">최근3개월(-)</th>
                <th colspan = "3" style="position:relative;" onclick="all_sale(event)">전년3개월(+) <span class="sl_btn right"><span></th>
                <th colspan = "3" class="hidden_sl">최근6개월(-)</th>
                <th colspan = "3" class="hidden_sl">전년6개월(+)</th>
                <th colspan = "3" class="hidden_sl">1년(작년)</th>
                <th colspan = "3" class="hidden_sl">1년(제작년)</th>
                <th colspan = "3">발주수량</th>
                <th colspan = "2">제품정보</th>
                <th rowspan = "2">비고</th>
                
            </tr>
            <tr>
                <!-- 재고 -->
                <th>S/SS/L</th>
                <th>Q</th>
                <th>K</th>
                <th class="hidden_st">S/SS/L</th>
                <th class="hidden_st">Q</th>
                <th class="hidden_st">K</th>
                <th class="hidden_st">S/SS/L</th>
                <th class="hidden_st">Q</th>
                <th class="hidden_st">K</th>
                <!-- 판매량 -->
                <th>S/SS/L</th>
                <th>Q</th>
                <th>K</th>
                <th>S/SS/L</th>
                <th>Q</th>
                <th>K</th>
                <th class="hidden_sl">S/SS/L</th>
                <th class="hidden_sl">Q</th>
                <th class="hidden_sl">K</th>
                <th class="hidden_sl">S/SS/L</th>
                <th class="hidden_sl">Q</th>
                <th class="hidden_sl">K</th>
                <th class="hidden_sl">S/SS/L</th>
                <th class="hidden_sl">Q</th>
                <th class="hidden_sl">K</th>
                <th class="hidden_sl">S/SS/L</th>
                <th class="hidden_sl">Q</th>
                <th class="hidden_sl">K</th>
                <th>S/SS/L</th>
                <th>Q</th>
                <th>K</th>
                <th>제조국</th>
                <th>제조업체명</th>
            </tr>
        </thead>
        <tbody id="revenue-status">
            <?if(!empty($ovs_sql)){
                for ($i = 0; $ovs_row = sql_fetch_array($ovs_sql); $i++) {
                    
                    $balju_qty = array();
                    if (!empty($ovs_row['balju_qty'])) {
                        $balju_qty_set = json_decode($ovs_row['balju_qty'], true);
                    }

            ?>
            <tr class="data_row row_<?=$i?> noExport" data-item-idx=<?=$i?>>
                <td class="noExport no_dis">
                    <input type="checkbox" name="chk[]" class="ab chk_<?=$i?>" value="<?=$i?>">
                    <input type="hidden" class="chk_ro chk_ro_<?=$i?>" name="chk_ro[<?=$i?>]" value="false" id="chk_ro_<?=$i?>">
                    <input type="hidden" name="no[<?=$i?>]" value="<?=$ovs_row['no']?>" id="no_<?=$i?>">
                </td>
                <td>
                    <select class="noborder_re  jo_select ro_select_<?=$i?>" data-slc-idx=<?=$i?>>
                        <option value="RUN" <?= $ovs_row['run_out'] == 'RUN' ? "selected" : "" ?> >RUN</option>
                        <option value="OUT" <?= $ovs_row['run_out'] == 'OUT' ? "selected" : "" ?> >OUT</option>
                    </select>


                    <input type="hidden" class="inp_run_out_<?=$i?>" name="run_out[<?=$i?>]" id="run_out_<?=$i?>"  value="<?= $ovs_row['run_out']?>" >
                </td>
                <td><?=$ovs_row['brand']?></td>
                <td><?=$ovs_row['channal']?></td>
                <td><?=$ovs_row['item_1']?></td>
                <td><?=$ovs_row['item_2']?></td>
                <td><?=prod_gb_ch($ovs_row['prod_gubun'])?></td>
                <td><?=$ovs_row['season']?></td>
                <td><?=$ovs_row['samjin_code']?></td>
                <td><?=$ovs_row['item_name']?></td>
                <td>
                    <input type="hidden" name="color_nm[<?=$i?>]" id="color_select_<?=$i?>"  value="<?= $ovs_row['color_nm']?>" >
                    <select class="color_select   color_select_<?=$i?>"  data-cslc-idx=<?=$i?>>
                        <option value="AA(기타)" <?= $ovs_row['color_nm'] == 'AA(기타)' ? "selected" : "" ?>>AA(기타)</option>
                        <option value="BE(베이지)" <?= $ovs_row['color_nm'] == 'BE(베이지)' ? "selected" : "" ?>>BE(베이지)</option>
                        <option value="BK(블랙)" <?= $ovs_row['color_nm'] == 'BK(블랙)' ? "selected" : "" ?>>BK(블랙)</option>
                        <option value="BL(블루)" <?= $ovs_row['color_nm'] == 'BL(블루)' ? "selected" : "" ?>>BL(블루)</option>
                        <option value="BR(브라운)" <?= $ovs_row['color_nm'] == 'BR(브라운)' ? "selected" : "" ?>>BR(브라운)</option>
                        <option value="CR(크림)" <?= $ovs_row['color_nm'] == 'CR(크림)' ? "selected" : "" ?>>CR(크림)</option>
                        <option value="DB(진블루)" <?= $ovs_row['color_nm'] == 'DB(진블루)' ? "selected" : "" ?>>DB(진블루)</option>
                        <option value="DP(진핑크)" <?= $ovs_row['color_nm'] == 'DP(진핑크)' ? "selected" : "" ?>>DP(진핑크)</option>
                        <option value="FC(푸시아)" <?= $ovs_row['color_nm'] == 'FC(푸시아)' ? "selected" : "" ?>>FC(푸시아)</option>
                        <option value="GD(골드)" <?= $ovs_row['color_nm'] == 'GD(골드)' ? "selected" : "" ?>>GD(골드)</option>
                        <option value="GN(그린)" <?= $ovs_row['color_nm'] == 'GN(그린)' ? "selected" : "" ?>>GN(그린)</option>
                        <option value="GR(그레이)" <?= $ovs_row['color_nm'] == 'GR(그레이)' ? "selected" : "" ?>>GR(그레이)</option>
                        <option value="IV(아이보리)" <?= $ovs_row['color_nm'] == 'IV(아이보리)' ? "selected" : "" ?>>IV(아이보리)</option>
                        <option value="KA(카키)" <?= $ovs_row['color_nm'] == 'KA(카키)' ? "selected" : "" ?>>KA(카키)</option>
                        <option value="LB(연블루)" <?= $ovs_row['color_nm'] == 'LB(연블루)' ? "selected" : "" ?>>LB(연블루)</option>
                        <option value="LG(연그레이)" <?= $ovs_row['color_nm'] == 'LG(연그레이)' ? "selected" : "" ?>>LG(연그레이)</option>
                        <option value="LP(연핑크)" <?= $ovs_row['color_nm'] == 'LP(연핑크)' ? "selected" : "" ?>>LP(연핑크)</option>
                        <option value="LV(라벤다)" <?= $ovs_row['color_nm'] == 'LV(라벤다)' ? "selected" : "" ?>>LV(라벤다)</option>
                        <option value="MT(민트)" <?= $ovs_row['color_nm'] == 'MT(민트)' ? "selected" : "" ?>>MT(민트)</option>
                        <option value="MU(멀티)" <?= $ovs_row['color_nm'] == 'MU(멀티)' ? "selected" : "" ?>>MU(멀티)</option>
                        <option value="MV(모브)" <?= $ovs_row['color_nm'] == 'MV(모브)' ? "selected" : "" ?>>MV(모브)</option>
                        <option value="MX(혼합)" <?= $ovs_row['color_nm'] == 'MX(혼합)' ? "selected" : "" ?>>MX(혼합)</option>
                        <option value="NC(내츄럴)" <?= $ovs_row['color_nm'] == 'NC(내츄럴)' ? "selected" : "" ?>>NC(내츄럴)</option>
                        <option value="NV(네이비)" <?= $ovs_row['color_nm'] == 'NV(네이비)' ? "selected" : "" ?>>NV(네이비)</option>
                        <option value="OR(오렌지)" <?= $ovs_row['color_nm'] == 'OR(오렌지)' ? "selected" : "" ?>>OR(오렌지)</option>
                        <option value="PC(청록)" <?= $ovs_row['color_nm'] == 'PC(청록)' ? "selected" : "" ?>>PC(청록)</option>
                        <option value="PK(핑크)" <?= $ovs_row['color_nm'] == 'PK(핑크)' ? "selected" : "" ?>>PK(핑크)</option>
                        <option value="PU(퍼플)" <?= $ovs_row['color_nm'] == 'PU(퍼플)' ? "selected" : "" ?>>PU(퍼플)</option>
                        <option value="RD(레드)" <?= $ovs_row['color_nm'] == 'RD(레드)' ? "selected" : "" ?>>RD(레드)</option>
                        <option value="WH(화이트)" <?= $ovs_row['color_nm'] == 'WH(화이트)' ? "selected" : "" ?>>WH(화이트)</option>
                        <option value="YE(노랑)" <?= $ovs_row['color_nm'] == 'YE(노랑)' ? "selected" : "" ?>>YE(노랑)</option>
                        <option value="DG(딥그레이)" <?= $ovs_row['color_nm'] == 'DG(딥그레이)' ? "selected" : "" ?>>DG(딥그레이)</option>
                        <option value="CO(코랄)" <?= $ovs_row['color_nm'] == 'CO(코랄)' ? "selected" : "" ?>>CO(코랄)</option>
                    </select>

                    
                    
                    
                    
                </td>
                <td><input class="noborder_sm txt_center" name="memo[<?=$i?>]" id="memo_<?=$i?>" type="text" value = "<?=$ovs_row['memo']?>"></td>
                <td><input class="noborder_sm txt_center" name="moq[<?=$i?>]" id="moq_<?=$i?>" type="text" value = "<?=$ovs_row['moq']?>"></td>
                <td onclick="latelyDatePicker(<?=$i?>)">
                    <input type="text" name="lately_date[<?=$i?>]" value="<?= strtotime($ovs_row['lately_date']) > 0 ? $ovs_row['lately_date'] : ''; ?>" name = "lately_date"  id="latelyDatePicker_<?=$i?>" class="noborder_sm txt_center" >
                </td>
                <td onclick="addDatePicker(<?=$i?>)">
                    <input type="text" name="add_date[<?=$i?>]" value="<?php echo strtotime($ovs_row['add_date']) > 0 ? $ovs_row['add_date'] : ''; ?>" name = "add_date"  id="addDatePicker_<?=$i?>" class="noborder_sm txt_center" >
                </td>
                <td><?=$ovs_row['total_stock_s']?></td>
                <td><?=$ovs_row['total_stock_q']?></td>
                <td><?=$ovs_row['total_stock_k']?></td>
                <td class="hidden_st"><?=$ovs_row['dpart_stock_s']?></td>
                <td class="hidden_st"><?=$ovs_row['dpart_stock_q']?></td>
                <td class="hidden_st"><?=$ovs_row['dpart_stock_k']?></td>
                <td class="hidden_st"><?=$ovs_row['rep_stock_s']?></td>
                <td class="hidden_st"><?=$ovs_row['rep_stock_q']?></td>
                <td class="hidden_st"><?=$ovs_row['rep_stock_k']?></td>
                <td><?=$ovs_row['rep_sale_sum3m_s']?></td>
                <td><?=$ovs_row['rep_sale_sum3m_q']?></td>
                <td><?=$ovs_row['rep_sale_sum3m_k']?></td>
                <td><?=$ovs_row['rep_sale_sum3p_s']?></td>
                <td><?=$ovs_row['rep_sale_sum3p_q']?></td>
                <td><?=$ovs_row['rep_sale_sum3p_k']?></td>
                <td class="hidden_sl"><?=$ovs_row['rep_sale_sum6m_s']?></td>
                <td class="hidden_sl"><?=$ovs_row['rep_sale_sum6m_q']?></td>
                <td class="hidden_sl"><?=$ovs_row['rep_sale_sum6m_k']?></td>
                <td class="hidden_sl"><?=$ovs_row['rep_sale_sum6p_s']?></td>
                <td class="hidden_sl"><?=$ovs_row['rep_sale_sum6p_q']?></td>
                <td class="hidden_sl"><?=$ovs_row['rep_sale_sum6p_k']?></td>
                <td class="hidden_sl"><?=$ovs_row['rep_sale_sum1y_s']?></td>
                <td class="hidden_sl"><?=$ovs_row['rep_sale_sum1y_q']?></td>
                <td class="hidden_sl"><?=$ovs_row['rep_sale_sum1y_k']?></td>
                <td class="hidden_sl"><?=$ovs_row['rep_sale_sum2y_s']?></td>
                <td class="hidden_sl"><?=$ovs_row['rep_sale_sum2y_q']?></td>
                <td class="hidden_sl"><?=$ovs_row['rep_sale_sum2y_k']?></td>
                <td><input class="noborder_sm_w5 txt_center" name="balju_qty_s[<?=$i?>]" id="balju_qty_s_<?=$i?>" type="text" value = "<?=$ovs_row['balju_qty_s']?>"></td>
                <td><input class="noborder_sm_w5 txt_center" name="balju_qty_q[<?=$i?>]" id="balju_qty_q_<?=$i?>" type="text" value = "<?=$ovs_row['balju_qty_q']?>"></td>
                <td><input class="noborder_sm_w5 txt_center" name="balju_qty_k[<?=$i?>]" id="balju_qty_k_<?=$i?>" type="text" value = "<?=$ovs_row['balju_qty_k']?>"></td>
                <td><?=$ovs_row['maker_ct']?></td>
                <td><?=$ovs_row['maker_cp']?></td>
                <td><input class="noborder_etc txt_center" name="etc_meg[<?=$i?>]" id="etc_meg_<?=$i?>" type="text" value = "<?=$ovs_row['etc_meg']?>"></td>
            </tr>
            <?
                }
            }
            ?>
            
            
        </tbody>
    </table>

</form>
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?&amp;page="); ?>
</div>

<link rel="stylesheet" href="../total_order/fixed_table.css">
<script src="../total_order/fixed_table.js"></script>
<script>
    var scroll_left;

    $(document).ready(function () {
        $(".hidden_st").addClass("hidden_");
        $(".hidden_sl").addClass("hidden_");
        
        table_setting();

        $('.jo_select').change(function () {
            // alert($('.DTFC_LeftBodyLiner select.jo_select').val());
            var slc_idx = $(this).data("slc-idx");
            // alert(slc_idx);
            // alert($('.DTFC_LeftBodyLiner select.ro_select_'+slc_idx).val());
            $('.inp_run_out_'+slc_idx).val($('.DTFC_LeftBodyLiner select.ro_select_'+slc_idx).val());


        });

        $('.color_select').change(function () {
            var cslc_idx = $(this).data("cslc-idx");
            // alert(cslc_idx);
            $('#color_select_'+cslc_idx).val($('select.color_select_'+cslc_idx).val());

            // alert($('select.color_select_'+cslc_idx).val());
        });

        

        $(".no-sort").removeClass('sorting_asc');
        
    });
    $('.search-reset').on("click",function() {
        $('.stx').val('');
        $('.sc_it_time').val('');
        
        $(".brand").attr('checked',false);
        $("#brand_0").attr('checked',true);
        $("#brands").val('');
        $(".ing").attr('checked',false);
        $("#ing_0").attr('checked',true);
        $("#ings").val('');
        $(".channal").attr('checked',false);
        $("#channal_0").attr('checked',true);
        $("#channals").val('');
        
        
       

        
    });

    $(".brand").change(function(){
        var brands = "";
        $("#brand_0").attr('checked',false);
        $("input.brand:checked").each(function(){
            //alert($(this).val());
            if(brands != "") brands += ",";
            brands += $(this).val();

        });
        $("#brands").val(brands);
    });
    $("#brand_0").change(function(){
        if($("#brand_0").is(":checked")){
            $(".brand").prop('checked',true);
            $("#brands").val('');
        }else{
            $(".brand").prop('checked',false);
        }
    });
    $(".ing").change(function(){
        var ings = "";
        $("#ing_0").attr('checked',false);
        $("input.ing:checked").each(function(){
            //alert($(this).val());
            if(ings != "") ings += ",";
            ings += $(this).val();

        });
        $("#ings").val(ings);
    });
    $("#ing_0").change(function(){
        if($("#ing_0").is(":checked")){
            $(".ing").prop('checked',true);
            $("#ings").val('');
        }else{
            $(".ing").prop('checked',false);
        }
    });
    $(".channal").change(function(){
        var channals = "";
        $("#channal_0").attr('checked',false);
        $("input.channal:checked").each(function(){
            //alert($(this).val());
            if(channals != "") channals += ",";
            channals += $(this).val();

        });
        $("#channals").val(channals);
    });
    $("#channal_0").change(function(){
        if($("#channal_0").is(":checked")){
            $(".channal").prop('checked',true);
            $("#channals").val('');
        }else{
            $(".channal").prop('checked',false);
        }
    });

    function table_setting(){
        $("#reportTb").DataTable().destroy();
        
        var table = $('#reportTb').DataTable({
            scrollY: "650px",
            scrollX: true,
            scrollCollapse: true,
            ordering: false,
            // columnDefs: [{
            //     orderable: false,
            //     targets: "no-sort"
            // }],
            // columns : {"name" : "chk[]" , "orderable":"false"},
            info: false,
            paging: false,
            searching: false,
            createdRow: function (row, data, dataIndex) {

                //ROWSPAN
                if (dataIndex == 0) {
                    //$('td:eq(0)', row).attr('rowspan', 9);
                }
                else {
                    if (data[1] === '대한항공') {
                        //$('td:eq(0)', row).attr('rowspan', 9);
                    }
                    else {
                        //$('td:eq(0)', row).css('display', 'none');
                    }
                }
                //COLSPAN
                if (data[1] === '합계') {
                    $('td:eq(1)', row).attr('colspan', 2);
                }

                //CSS셋팅
                // $('th:eq(0)', row).css('text-align', 'center');
                $('td:not(:eq(0))', row).css('text-align', 'center');
                $('td:eq(0)', row).css('text-align', 'center');
                
                // $('td:eq(10)', row).css('text-align', 'left');
                // $('td:eq(12)', row).css('text-align', 'left');
                
            },
            fixedColumns: {
                leftColumns: 8
            }

        });

        $("#topscroll .div1").css('width',$("#reportTb").innerWidth() +'px');
        $("#topscroll").scroll(function(){
            $(".dataTables_scrollBody").scrollLeft($("#topscroll").scrollLeft());
            scroll_left = $("#topscroll").scrollLeft();
        });
        $(".dataTables_scrollBody").scroll(function(){
            $("#topscroll").scrollLeft($(".dataTables_scrollBody").scrollLeft());
            scroll_left = $(".dataTables_scrollBody").scrollLeft();
            
        });

        $(".DTFC_LeftBodyLiner select").addClass("noExport");

        closeLoadingWithMask();
    }

    // 작성일
    function latelyDatePicker(elem){
        var date = new Date();
        var year = date.getFullYear();
        var month = ("0" + (1 + date.getMonth())).slice(-2);
        var day = ("0" + date.getDate()).slice(-2);

        $('#latelyDatePicker_'+elem).val(year +"-"+ month +"-"+ day);
    }
    function addDatePicker(elem){
        var date = new Date();
        var year = date.getFullYear();
        var month = ("0" + (1 + date.getMonth())).slice(-2);
        var day = ("0" + date.getDate()).slice(-2);

        $('#addDatePicker_'+elem).val(year +"-"+ month +"-"+ day);
    }

    // $('#latelyDatePicker').datetimepicker({
    //     ignoreReadonly: true,
    //     allowInputToggle: true,
    //     format: 'YYYY-MM-DD',
    //     locale: 'ko'
    // });

    // $('#enddatepicker').datetimepicker({
    //     ignoreReadonly: true,
    //     allowInputToggle: true,
    //     format: 'YYYY-MM-DD HH:mm',
    //     locale: 'ko'
    // });

    function enterSearch() {
        if (window.event.keyCode == 13) {
        	document.getElementById('new_goods_form').submit();
    	}
    }

    function all_chk(f){
        // $(".DTFC_LeftBodyLiner input[name='chk[]']").prop("checked" , true);
        if($(".DTFC_LeftHeadWrapper .chk_all").hasClass("allchks")){
            $(".DTFC_LeftHeadWrapper .chk_all").removeClass("allchks");
            $("input[name='chk[]']").prop("checked" , false);
            $(".data_row").addClass("noExport");
            $(".chk_ro").val(false);
        }else{
            $(".DTFC_LeftHeadWrapper .chk_all").addClass("allchks");
            $("input[name='chk[]']").prop("checked" , true);
            $(".data_row").removeClass("noExport");
            $(".chk_ro").val(true);
        }
    }
    function allCheck2(e=false) { 
        if ($("input:checkbox[id='allchk']").is(':checked')) {
            // $("input[name='chk[]']").prop("checked", true);
            $("input[name='chk[]']").prop("checked" , true);
            $(".data_row").removeClass("noExport");
            $(".chk_ro").val(true);
        } else {
            // $("input[name='chk[]']").prop("checked", false);
            $("input[name='chk[]']").prop("checked" , false);
            $(".data_row").addClass("noExport");
            $(".chk_ro").val(false);
        }

        // testchk
    }

    function all_stock(event){
        event.preventDefault();
        LoadingWithMask();
        if($(".hidden_st").hasClass('hidden_')){
            $(".hidden_st").removeClass("hidden_");
            $(".st_btn").removeClass("right");
            $(".st_btn").addClass("left");
        }else{
            $(".hidden_st").addClass("hidden_");
            $(".st_btn").removeClass("left");
            $(".st_btn").addClass("right");
        }
        table_setting();
        $("#topscroll").scrollLeft(scroll_left);
        $(".dataTables_scrollBody").scrollLeft(scroll_left);
        
    }
    function all_sale(){
        LoadingWithMask();
        if($(".hidden_sl").hasClass('hidden_')){
            $(".hidden_sl").removeClass("hidden_");
            $(".sl_btn").removeClass("right");
            $(".sl_btn").addClass("left");
        }else{
            $(".hidden_sl").addClass("hidden_");
            $(".sl_btn").removeClass("left");
            $(".sl_btn").addClass("right");
        }
        table_setting();
        $("#topscroll").scrollLeft(scroll_left);
        $(".dataTables_scrollBody").scrollLeft(scroll_left);
        
    }
    $("input[name='chk[]']").change(function() {
        if(is_checked("chk[]")){
            $(".row_"+this.value).removeClass("noExport");
            $(".chk_ro_"+this.value).val(true);
        }else{
            $(".row_"+this.value).addClass("noExport");    
            $(".chk_ro_"+this.value).val(false);
        }
    });

    function down_excel(){
        if (!is_checked("chk[]")) {
            alert("상품을 선택해주세요.");
            return false;
        }
        $("input[name='chk[]']:checked").each(function() {
            var no = $(".DTFC_LeftBodyLiner input[name='no["+this.value+"]']").val();
            $(".row_"+this.value).removeClass("noExport");
            // $("select.ro_select_"+this.value).addClass("noExport");
            // $("select.color_select_"+this.value).addClass("noExport");
        });

        $("#reportTb").table2excel({
            name: "Excel table",
            filename: "상품DB전산화(해외)" + new Date().toISOString().replace(/[\-\:\.]/g, ""),
            fileext: ".xls",
            exclude : ".noExport",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true,
            exclude_selects : true
            
        });
    }

    function all_down_excel(){
        var excel_sql = "";
        var excel_type = "";

        var headerdata = "";
        var bodydata = "";

        
        excel_sql = "select * from new_goods_db_overseas order by no desc";
        headerdata = $('<input type="hidden" value="<?=$headers?>" name="headerdata">');
        headerdata2 = $('<input type="hidden" value="<?=$headers2?>" name="headerdata2">');
        bodydata = $('<input type="hidden" value="<?=$bodys?>" name="bodydata">');
        excel_type = '상품DB전산화(해외)';
        

        var $form = $('<form></form>');     
        $form.attr('action', '<?=G5_ADMIN_URL?>/ajax.excel_download.new_goods_db_overseas.php');
        $form.attr('method', 'post');
        $form.appendTo('body');
        
        var exceldata = $('<input type="hidden" value="'+excel_sql+'" name="exceldata">');
        
        var excelnamedata = $('<input type="hidden" value="'+excel_type+'" name="excelnamedata">');
        //$form.append(exceldata).append(headerdata).append(bodydata).append(excelnamedata);
        $form.append(exceldata).append(headerdata).append(headerdata2).append(bodydata).append(excelnamedata);
        $form.submit();
    }

    function formsubmit(){
        if (!is_checked("chk[]")) {
            alert("상품을 선택해주세요.");
            return false;
        }
        var f = document.fwrite;
        var $f = jQuery(f);
        var $b = jQuery(this);
        var $t, t;
        var result = true;
        if (confirm("저장하시겠습니까?")) {
            $("input[name='chk[]']:checked").each(function() {
                // var no = $(".DTFC_LeftBodyLiner input[name='no["+this.value+"]']").val();
                $(".row_"+this.value).removeClass("noExport");
            });
            // $f.find("input[name='chk[]']:checked").each(function(j) {
            //     console.log(this.value);
            //     $(".row_"+this.value).removeClass("noExport");
            //     $f.find(".noExport input, .noExport select, .noExport textarea").each(function() {
            //         $t = jQuery(this);    
            //         $t.removeAttr("name");
            //     });
            // });
            $f.find(".DTFC_LeftBodyLiner input[name='chk[]']").each(function(i) {
                $t = jQuery(this);    
                $t.removeAttr("name");
               
            });
            //$f.find("input[name='chkall']").removeAttr("name");

            // alert($("input[name='run_out["+this.value+"]']").val());
            // console.log($f.serializeArray());


            f.submit();
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

</script>

<?php 
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
