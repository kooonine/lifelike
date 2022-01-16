<?php
//$sub_menu = '930110';
$sub_menu = '93';
include_once('./_common.php');


// auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '사방넷 요약수정';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

if($od_type == "") $od_type = "L";

$sql_search = " where (1)  ";

$txt1 = $_POST['stx'];
$page = $_GET['page'];

if($sfl){
    if($stx){
        switch($sfl){
            case 'IDX':
                preg_match_all("/[^() || \-\ \/\,]+/", $txt1,$IDX_list);
                $IDX_in_list = empty($IDX_list[0])?'NULL':"'".join("','", $IDX_list[0])."'";
                $sql_search.= " and sabang_goods_cd IN({$IDX_in_list})";
            break;
            case 'sb_conpany_cd':
                preg_match_all("/[^() || \-\ \/\,]+/", $txt1,$sb_conpay_cd_list);
                $sb_conpay_cd_in_list = empty($sb_conpay_cd_list[0])?'NULL':"'".join("','", $sb_conpay_cd_list[0])."'";
                $sql_search.= " and compayny_goods_cd IN({$sb_conpay_cd_in_list})";

                break;
            case 'sap_cd':
                preg_match_all("/[^() || \-\ \/\,]+/", $txt1,$model_no_list);
                $model_no_in_list = empty($model_no_list[0])?'NULL':"'".join("','", $model_no_list[0])."'";
                $sql_search.= "and model_no IN({$model_no_in_list})";
                break;
            case 'sam_cd':
                preg_match_all("/[^() || \-\ \/\,]+/", $txt1,$model_nm_list);
                $model_nm_in_list = empty($model_nm_list[0])?'NULL':"'".join("','", $model_nm_list[0])."'";
                $sql_search.= "and model_nm IN({$model_nm_in_list})";
                break;
            case 'it_name':
                $sql_search .= " and goods_nm like '%{$txt1}%' ";
                break;
        }
    }else{

    }
}



// 테이블의 전체 레코드수만 얻음
$cnt_sql = "SELECT COUNT(*) AS cnt FROM  sabang_goods_origin {$sql_search}";
$cnt_row = sql_fetch($cnt_sql);
$total_count = $cnt_row['cnt'];

if($limit_list) $rows = $limit_list;
// else $rows = $config['cf_page_rows'];
else $rows = 50;
// $rows=4;

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산

if ($total_page < 2 || empty($page)) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$sabang_origin_goods = "select * from sabang_goods_origin  {$sql_search} limit $from_record, $rows ";

$sb_sql = sql_query($sabang_origin_goods);
$sb_db_data = sql_fetch($sabang_origin_goods);



?>
<div style="background-color : #fff;">

<form name="new_goods_form" id="new_goods_form" onsubmit="" method="post">
    <div class="tbl_frm01 tbl_wrap">
        <table class="new_goods_list">
        <colgroup>
        <col >
        <col>
        <col >
        </colgroup>
        
        <tr>
            <th scope="row">검색분류</th>
            <td colspan="2">
                <label for="sfl" class="sound_only">검색대상</label>
                <select name="sfl" id="sfl">
                    <!-- <option value="it_name" <?php echo get_selected($sfl, 'it_name'); ?>>상품명</option> -->
                    <option value="IDX" <?php echo get_selected($sfl, 'IDX'); ?>>사방넷품번코드</option>
                    <option value="sb_conpany_cd" <?php echo get_selected($sfl, 'sb_conpany_cd'); ?>>사방넷자체상품코드</option>
                    <option value="sap_cd" <?php echo get_selected($sfl, 'sap_cd'); ?>>SAP코드</option>
                    <option value="sam_cd" <?php echo get_selected($sfl, 'sam_cd'); ?>>삼진코드</option>
                    <option value="it_name" <?php echo get_selected($sfl, 'it_name'); ?>>상품명</option>
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

<form>
    <div>
        <select name = "sabang_status" id = "sabang_status" >
            <option value = "">상품상태</option>
            <option value = "1">대기중</option>
            <option value = "2">공급중</option>
            <option value = "3">일시중지</option>
            <option value = "4">완전품절</option>
            <option value = "5">미사용</option>
            <option value = "6">삭제</option>
            <option value = "7">정보없음</option>
        </select>
        <div class="btn btn_02" style="height: 30px;" onclick="sabang_send();">
        선택 상품상태변경
        <input type ="hidden" name ="sabang_goods_cds" id="sabang_goods_cds" >
        </div>

        <select name = "stock_send" id = "stock_send" >
            <option value = "">상태</option>
            <option value = "Y">연동</option>
            <option value = "N">중지</option>
        </select>
            <div class="btn btn_02" style="height: 30px;" onclick="stock_zero_send();">
            재고연동상태변경
        <input type ="hidden" name ="stock_send_cds" id="stock_send_cds" >
        </div>
    </div>
    

    <table id="reportTb" class="display" style="width:100%">
        <div class="tbl_head01 tbl_wrap" id="topscroll"  style="width:100%; margin:0px; overflow-x:scroll;">
            <div class="div1" style="width:1950px; height:20px;"></div>
        </div>
        <thead>
            <tr>
                <th>
                    <label for="chkall" class="sound_only">선택 전체</label>
                    <input type="checkbox" name="chkall"  id="chkall" class="chk_all" onclick="all_chk(this.form)"   />
                </th>
                <th>사방넷상품코드</th>
                <th>자체상품코드</th>
                <th>SAP코드</th>
                <th>상품명</th>
                <th>상품상태</th>
                <th>재고연동</th>
                <th>원가</th>
                <th>판매가</th>
                <th>TAG가</th>
                <th>옵션명1</th>
                <th class="txt_left">옵션상세1</th>
                <th>옵션명2</th>
                <th class="txt_left">옵션상세2</th>
                
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
                <!-- <td><?=$i+1?></td> -->
                <td>
                    <input type="checkbox" name="chk[]" class="ab chk_<?=$i?>" value="<?=$i?>">
                    <input type="hidden" name="sb_cd[<?=$i?>]" value="<?=$row['sabang_goods_cd']?>" id="sb_cd_<?=$i?>">
                </td>
                <td><?=$row['sabang_goods_cd']?></td>
                <td><?=$row['compayny_goods_cd']?></td>
                <td><?=$row['model_no']?></td>
                <td><?=$row['goods_nm']?></td>
                <td><?=$status?></td>
                <td><?=$row['stock_send'] == 'Y' ? $row['stock_send'] :'중지'?></td>
                <td><?=number_format($row['goods_cost'])?></td>
                <td><?=number_format($row['goods_price'])?></td>
                <td><?=number_format($row['goods_consumer_price'])?></td>
                <td><?=$row['char_1_nm']?></td>
                <td><?=$row['char_1_val']?></td>
                <td><?=$row['char_2_nm']?></td>
                <td><?=$row['char_2_val']?></td>
            </tr>
        <?php
        } ?>
        </tbody>
    </table>

</form>
<div>
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?&amp;page="); ?>
</div>
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
                $('td:eq(4)', row).css('text-align', 'left');
                $('td:eq(10)', row).css('text-align', 'left');
                $('td:eq(12)', row).css('text-align', 'left');
                
            },
            fixedColumns: {
                leftColumns: 6
            }

        });

        $("#topscroll .div1").css('width',$("#reportTb").innerWidth() +'px');

        $("#topscroll").scroll(function(){
            $(".dataTables_scrollBody").scrollLeft($("#topscroll").scrollLeft());
        });
        $(".dataTables_scrollBody").scroll(function(){
            $("#topscroll").scrollLeft($(".dataTables_scrollBody").scrollLeft());
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

    function sabang_send(){
        if (!is_checked("chk[]")) {
            alert("변경할 상품을 선택해 주세요.");
            return false;
        }
        var chk_stat = $("#sabang_status").val();
        if(chk_stat == ''){
            alert("변경할 상품 상태값을 선택해 주세요.");
            return false;
        }
        var $select = new Array();
        $("#sabang_goods_cds").val('');

        $(".DTFC_LeftBodyLiner input[name='chk[]']:checked").each(function() {
            var sb_cd = $(".DTFC_LeftBodyLiner input[name='sb_cd["+this.value+"]']").val();
            $select.push(sb_cd);

        });

        var selects = $select.join(",");
        if ($("#sabang_goods_cds").val() != "") selects += "," + $("#sabang_goods_cds").val();
        $("#sabang_goods_cds").val(selects);

        var sabang_goods_cd = $("#sabang_goods_cds").val();
        var type = "status";
        

        var result = confirm("사방넷 상품 송신 하시겠습니까?");
        if(result){
            $.ajax({
                url: "./sabang_goods_stat_send.php",
                method: "POST",
                data: {
                    "sabang_goods_cds": selects,
                    "status" : chk_stat
                    
                },
                dataType: "json",
                async : false,
                cache: false,
                success: function(result) {
                    // console.log(result);
                    if (result.indexOf('200') !== -1){
                        // window.open("../sabang/send_sabang_new_goods_form2.php");
                        alert("사방넷 전송 성공!");
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

    function stock_zero_send(){
        if (!is_checked("chk[]")) {
            alert("재고연동 / 중지 상품을 선택해 주세요.");
            return false;
        }
        var chk_stat = $("#stock_send").val();
        if(chk_stat == ''){
            alert("해당상품 재고 연동 상태을 선택해 주세요.");
            return false;
        }
        var $select = new Array();
        $("#stock_send_cds").val('');

        $(".DTFC_LeftBodyLiner input[name='chk[]']:checked").each(function() {
            var sb_cd = $(".DTFC_LeftBodyLiner input[name='sb_cd["+this.value+"]']").val();
            $select.push(sb_cd);

        });

        var selects = $select.join(",");
        if ($("#stock_send_cds").val() != "") selects += "," + $("#stock_send_cds").val();
        $("#stock_send_cds").val(selects);

        var stock_send_cd = $("#stock_send_cds").val();

        var result = confirm("해당 상품 재고연동 상태 변경 하시겠습니까?");
        if(result){
            $.ajax({
                url: "./send_sabang_goods_stock_zero.php",
                method: "POST",
                data: {
                    "stock_send_cds": selects,
                    "stock_stat" : chk_stat
                    
                },
                dataType: "json",
                async : false,
                cache: false,
                success: function(result) {
                    // console.log(result);
                    if (result.indexOf('200') !== -1){
                        // window.open("../sabang/send_sabang_new_goods_form2.php");
                        alert("재고연동 상태 변경 완료");
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

</script>

<?php 
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
