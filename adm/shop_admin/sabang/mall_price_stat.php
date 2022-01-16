<?php
//$sub_menu = '930110';
$sub_menu = '93';
include_once('./_common.php');

include_once(G5_LIB_PATH . '/PHPExcel.php');
include_once(G5_LIB_PATH . '/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '제휴몰 가격 관리';
include_once(G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$etc_mall_id = $_GET['etc_mall_id'];

if($od_type == "") $od_type = "L";

$sql_search = " where (1)  ";

$txt1 = $_POST['stx'];

if(!empty($etc_mall_id)){
    $sql_search.= " and mall_id ='{$etc_mall_id}'";
}else{
    goto_url("./mall_price_stat.php?etc_mall_id=19963");
}

if($sfl){
    if($stx){
        switch($sfl){
            // case 'IDX':
            //     preg_match_all("/[^() || \-\ \/\,]+/", $txt1,$IDX_list);
            //     $IDX_in_list = empty($IDX_list[0])?'NULL':"'".join("','", $IDX_list[0])."'";
            //     $sql_search.= " and sabang_goods_cd IN({$IDX_in_list})";
            // break;
            case 'sb_conpany_cd':
                preg_match_all("/[^() || \-\ \/\,]+/", $txt1,$sb_conpay_cd_list);
                $sb_conpay_cd_in_list = empty($sb_conpay_cd_list[0])?'NULL':"'".join("','", $sb_conpay_cd_list[0])."'";
                $sql_search.= " and prod_code IN({$sb_conpay_cd_in_list})";

                break;
            // case 'sap_cd':
            //     preg_match_all("/[^() || \-\ \/\,]+/", $txt1,$model_no_list);
            //     $model_no_in_list = empty($model_no_list[0])?'NULL':"'".join("','", $model_no_list[0])."'";
            //     $sql_search.= "and model_no IN({$model_no_in_list})";
            //     break;
            // case 'sam_cd':
            //     preg_match_all("/[^() || \-\ \/\,]+/", $txt1,$model_nm_list);
            //     $model_nm_in_list = empty($model_nm_list[0])?'NULL':"'".join("','", $model_nm_list[0])."'";
            //     $sql_search.= "and model_nm IN({$model_nm_in_list})";
            //     break;
            case 'it_name':
                $sql_search .= " and goods_name like '%{$txt1}%' ";
                break;
        }
    }else{

    }
}



// 테이블의 전체 레코드수만 얻음
$cnt_sql = "SELECT COUNT(*) AS cnt FROM  samjin_sale_reg_mall_goods_list {$sql_search}";
$cnt_row = sql_fetch($cnt_sql);

$total_count = $cnt_row['cnt'];

if($limit_list) $rows = $limit_list;
// else $rows = $config['cf_page_rows'];
else $rows = 50;
// $rows=4;

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산

if ($total_page < 2 || empty($page)) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함



$sabang_origin_goods = "select * from samjin_sale_reg_mall_goods_list  {$sql_search} limit $from_record, $rows ";
$sb_sql = sql_query($sabang_origin_goods);
$sb_db_data = sql_fetch($sabang_origin_goods);

$qstr= "etc_mall_id=".$etc_mall_id."&amp;limit_list=".$limit_list."&amp;page=".$page;


?>
<div style="background-color : #fff;">

<form name="new_goods_form" id="new_goods_form" onsubmit="" method="post">
    <div>
        <button class="btn <?=$etc_mall_id == '19963' ? "btn-info" : '' ?>" type="button" onclick="location.href='./mall_price_stat.php?etc_mall_id=19963'">까사미아</button>
        <button class="btn <?=$etc_mall_id == '19950' ? "btn-info" : '' ?>" type="button" onclick="location.href='./mall_price_stat.php?etc_mall_id=19950'">SSF</button>
        <button class="btn <?=$etc_mall_id == '19944' ? "btn-info" : '' ?>" type="button" onclick="location.href='./mall_price_stat.php?etc_mall_id=19944'">EQL</button>
        <button class="btn <?=$etc_mall_id == '19979' ? "btn-info" : '' ?>" type="button" onclick="location.href='./mall_price_stat.php?etc_mall_id=19979'">네이버</button>
    </div>
    <div class="tbl_frm01 tbl_wrap">
        <table class="new_goods_list">
        <colgroup>
        <col class="grid_4">
        <col>
        <col class="grid_3">
        </colgroup>
        
        <tr>
            <th scope="row" style="width : 15%;">검색분류</th>
            <td colspan="2">
                <label for="sfl" class="sound_only">검색대상</label>
                <select name="sfl" id="sfl">
                    <!-- <option value="it_name" <?php echo get_selected($sfl, 'it_name'); ?>>상품명</option> -->
                    <!-- <option value="IDX" <?php echo get_selected($sfl, 'IDX'); ?>>사방넷품번코드</option>
                    <option value="sap_cd" <?php echo get_selected($sfl, 'sap_cd'); ?>>SAP코드</option>
                    <option value="sam_cd" <?php echo get_selected($sfl, 'sam_cd'); ?>>삼진코드</option> -->
                    <option value="it_name" <?php echo get_selected($sfl, 'it_name'); ?>>상품명</option>
                    <option value="sb_conpany_cd" <?php echo get_selected($sfl, 'sb_conpany_cd'); ?>>자체상품코드</option>
                    <!-- <option value="invoice" <?php echo get_selected($sfl, 'invoice'); ?>>송장번호</option>
                    <option value="order_cel" <?php echo get_selected($sfl, 'order_cel'); ?>>전화번호</option>
                    <option value="receive_name" <?php echo get_selected($sfl, 'receive_name'); ?>>수취인명</option> -->
                </select>
                <label for="stx" class="sound_only">검색어</label>
                <input type="text" name="stx" value="<?php echo $txt1; ?>" id="stx" class="frm_input" autocomplete="off" onkeydown="enterSearch();">
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
<form>
    <input type = "hidden" name="etc_mall_id" value ="<?=$etc_mall_id?>" >
    <div>
        <input type='file' name ="upload_excel" id='upload_excel' />
        <div class="btn btn_02" style="height: 30px;" id="upload_excel_btn">
            엑셀 업로드
        </div>
    </div>
    

    <table id="reportTb" class="display" style="width:100%">
        <div class="tbl_head01 tbl_wrap" id="topscroll"  style="width:100%; margin:0px; overflow-x:scroll;">
            <div class="div1" style="width:1950px; height:20px;"></div>
        </div>
        <thead>
            <tr>
                <th>순번</th>
                <th>판매상태</th>
                <th>상품명</th>
                <th>판매가</th>
                <th>할인가</th>
                <th>품목명</th>
                <th>브랜드ID</th>
                <th>브랜드명</th>
                <th>카테고리</th>
                <th>수수료</th>
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
                <th>균일가여부</th>
                
            </tr>
        </thead>
        <tbody id="revenue-status">
        <?php
            for ($i = 0; $row = sql_fetch_array($sb_sql); $i++) {
                //$str_confirm = sprintf("'%s','%s','%s',%d,'%s'", $row['ORDER_NO'], $row['SAP_CODE'], $row['ITEM'], $row['PRICE'], $_POST['subID']);
                $sb_status = $row['status'];
                switch($sb_status){
                    case '1' : 
                        $status = '대기중';
                        break;
                    case '2' : 
                        $status = '공급중';
                        break;
                    case '3' : 
                        $status = '일시중지';
                        break;
                    case '4' : 
                        $status = '완전품절';
                        break;
                    case '5' : 
                        $status = '미사용';
                        break;
                    case '6' : 
                        $status = '삭제';
                        break;
                    case '7' : 
                        $status = '자료없음';
                        break;
                }

                ?>
            <tr>
                <td><?=$i+1?></td>
                <td><?=$row['status']?></td>
                <td><?=$row['goods_name']?></td>
                <td><?=number_format($row['order_price'])?></td>
                <td><?=number_format($row['sale_price'])?></td>
                <td><?=$row['prod_name']?></td>
                <td><?=$row['brand_id']?></td>
                <td><?=$row['brand_name']?></td>
                <td><?=$row['category']?></td>
                <td><?=$row['fee']?></td>
                <td><?=$row['goods_id']?></td>
                <td><?=$row['option_id']?></td>
                <td><?=number_format($row['option_id_price'])?></td>
                <td><?=number_format($row['option_id_sale_price'])?></td>
                <td><?=$row['sap_code']?></td>
                <td><?=$row['sale_rate']?></td>
                <td><?=number_format($row['sale_price'])?></td>
                <td><?=number_format($row['prod_price'])?></td>
                <td><?=$row['saler']?></td>
                <td><?=$row['qty']?></td>
                <td><?=$row['stock']?></td>
                <td><?=$row['uniform_yn']?></td>
            </tr>
        <?php
        } ?>
        </tbody>
    </table>

</form>
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
</div>

<link rel="stylesheet" href="../total_order/fixed_table.css">
<script src="../total_order/fixed_table.js"></script>
<script>
    $(document).ready(function () {
        var table = $('#reportTb').DataTable({
            scrollY: "650px",
            scrollX: true,
            scrollCollapse: true,
            ordering: true,
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
                $('th:eq(0)', row).css('text-align', 'center');
                $('td:not(:eq(0))', row).css('text-align', 'center');
                $('td:eq(0)', row).css('text-align', 'center');
                $('td:eq(2)', row).css('text-align', 'left');
                $('td:eq(5)', row).css('text-align', 'left');
                // $('td:eq(12)', row).css('text-align', 'left');
                
            },
            fixedColumns: {
                leftColumns: 3
            }

        });

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
        
    });

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

    function upload_excel(){
        var $excelfile = $("#upload_excel");

        var etc_mall_id = $('<input type="hidden" value="<?=$etc_mall_id?>" name="etc_mall_id">');
        
        var $form = $('<form></form>');     
        $form.attr('action', './upload_mall_price_stat.php');
        $form.attr('method', 'post');
        $form.attr('enctype', 'multipart/form-data');
        $form.appendTo('body');
        $form.append(etc_mall_id).append($excelfile);

    
        $form.submit();
    }

    

</script>

<?php 
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
