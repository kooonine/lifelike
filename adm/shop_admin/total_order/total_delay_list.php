<?php
$sub_menu = '41';
include_once('./_common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');


auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '출고지연 리스트';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

// echo '주문서';
// echo '<br> ov_search_type1  : '.$ov_search_type;
$orderWhere = "";


// echo '$orderWhere : '.$orderWhere;
$totalSql = "SELECT count(*) AS CNT FROM sabang_lt_order_form WHERE invoice_check IN (1,2)";


// $sql = " select count(od_id) as cnt " . $sql_common;
$countRow = sql_fetch($totalSql);
$total_count = $countRow['CNT'];
// echo '<br>총건수 : '.$total_count.'<br>';
if ($outputCount < 1 || !$outputCount) {
	$outputCount = 200;
}
$rows = $outputCount; 
// $total_page  = ceil($total_count / $outputCount);
// if ($page < 1 || !$page) {
// 	$page = 1;
// }
// $from_record = ($page - 1) * $outputCount;


$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산

if ($total_page < 2 || empty($page)) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


// $listSql = "SELECT * FROM sabang_lt_order_view WHERE sub_slov_id = 0 $orderWhere ORDER BY ov_mall_id DESC, receive_date DESC, ov_order_id DESC, ov_ct_id ASC, slov_id DESC LIMIT $from_record, $outputCount";
$listSql = "SELECT * FROM sabang_lt_order_form WHERE invoice_check IN (1,2) ORDER BY sno desc";
// $listSql = "SELECT * FROM sabang_lt_order_form WHERE invoice_check IN (0) LIMIT 11";
$listQuery = sql_query($listSql);

?>

<!-- 검색 따로만들래 !! -->
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">

            <div class="local_ov01 local_ov">
	            <span class="btn_ov01">[ 검색결과 <?= number_format($total_count); ?>건 ]</span>
            </div>
            <div class="local_cmd01 local_cmd">
            </div>
    <!-- </div> -->
<!-- </div> -->




<style>
    th, td {
        white-space: nowrap;
        text-align: center !important;
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
    #total_order_body{font-size : 13px;}
    /*table.dataTable thead > tr > td {
        width: 80px;
    }*/
    #upload_excel{display:none;}
</style>
<link rel="stylesheet" href="./fixed_table.css">
<body id="total_order_body">
    <table id="reportTb" class="display" style="width:100%; text-align: center;">
        <div class="tbl_head01 tbl_wrap" id="topscroll"  style="width:100%; margin:0px; overflow-x:scroll;">
            <div class="div1" style="width:1950px; height:20px;"></div>
        </div>
        <colgroup>
            <col width ='10%'>
            <col width ='20%'>
            <col width ='20%'>
            <col width ='20%'>
            <col width ='30%'>
        </colgroup>    
        <thead>
            <tr>
                <th>제휴사<br>거래처</th>
                <th>쇼핑몰주문번호</th>
                <th>사방넷주문번호</th>
                <th>송장번호</th>
                <th>상품명</th>
            </tr>
        </thead>
        <tbody id="revenue-status">
            
        <?
            for ($k = 0; $row = sql_fetch_array($listQuery); $k++) {
                $invcCo = '';
                if ($row['tak_code'] == '003') {
                    $invcCo = 'CJ대한통운';
                } else if ($row['tak_code'] == '002') {
                    $invcCo = '롯데택배';
                } else if ($row['tak_code'] == '001') { 
                    $invcCo = '대한통운';
                } else if ($row['tak_code'] == '004') { 
                    $invcCo = '한진택배';
                } else if ($row['tak_code'] == '005') { 
                    $invcCo = 'KGB택배';
                } else if ($row['tak_code'] == '006') { 
                    $invcCo = '동부택배';
                } else if ($row['tak_code'] == '007') { 
                    $invcCo = '로젠택배';
                } else if ($row['tak_code'] == '008') { 
                    $invcCo = '옐로우캡택배';
                } else if ($row['tak_code'] == '009') { 
                    $invcCo = '우체국택배';
                } else if ($row['tak_code'] == '010') { 
                    $invcCo = '하나로택배';
                } else if ($row['tak_code'] == '013') { 
                    $invcCo = '경동택배';
                } else if ($row['tak_code'] == '014') { 
                    $invcCo = '일양로직스';
                } else if ($row['tak_code'] == '016') { 
                    $invcCo = '천일택배';
                } else if ($row['tak_code'] == '017') { 
                    $invcCo = '동부익스프레스';
                }
        ?>
            <tr>  
                <td><? echo $row['mall_name'] ?><br>
                    <? echo $row['mall_id'] ?>
                </td>
                <td>
                    <a href="/adm/shop_admin/total_order/total_order_form.php?sfl=mall_order_no&amp;keyword=<? echo $row['mall_order_no'] ?>&amp;sc_it_time=%20" target='_blank'>
                        <? echo $row['mall_order_no'] ?>
                    </a>
                </td>
                <td>
                    <a href="/adm/shop_admin/total_order/total_order_form.php?sfl=sabang_ord_no&amp;keyword=<? echo $row['sabang_ord_no'] ?>&amp;sc_it_time=%20" target='_blank'>
                        <? echo $row['sabang_ord_no'] ?>
                    </a>
                </td>
                <td> 
                    <a href='<?= G5_URL ?>/common/tracking.php?invc_no=<?= $row['order_invoice'] ?>&invc_co=<?= $invcCo ?>&view_popup=1' target='_blank' onClick="window.open(this.href, '', 'width=550, height=800'); return false;" class="form_invoice">
                        <?= $row['order_invoice'] ?>
                    </a>
                </td>
                <td><? echo $row['samjin_name'] ?></td>
            </tr>
        <? 
            }
        ?>
        </tbody>
    </table>
    <?= get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?&amp;page="); ?>

    </body>
</div>
</div>
</div>



<script src="./fixed_table.js"></script>
<script>
    

    $(document).ready(function () {
        var table = $('#reportTb').DataTable({
            scrollY: "650px",
            scrollX: true,
            scrollCollapse: false,
            ordering: false,
            info: false,
            paging: false,
            searching: false,
            createdRow: function (row, data, dataIndex) {
                //ROWSPAN
                if (dataIndex == 0) {
                    // $('td:eq(0)', row).attr('rowspan', 9);
                }
                else {
                    // if (data[1] === '대한항공') {
                    //     $('td:eq(0)', row).attr('rowspan', 9);
                    // }
                    // else {
                    //     $('td:eq(0)', row).css('display', 'none');
                    // }
                }
                //COLSPAN
                if (data[1] === '합계') {
                    // $('td:eq(1)', row).attr('colspan', 2);
                }
                //CSS셋팅
                // $('th').attr('style','text-align : center !important');
                // $('th.sorting_disabled').attr('style','text-align : center !important');
                // $('td:not(:eq(0))', row).css('text-align', 'center');
                // $('td:eq()', row).css('text-align', 'center');
                // $('td:eq(1)', row).css('text-align', 'center');
                // $('td:eq(9)', row).css('width', '300px;');
                // $('th:eq(9)', row).css('width', '300px;');
            },
            // fixedColumns: {
            //     leftColumns: 0
            // }
        });
        // $('th.sorting_disabled').attr('style','text-align : center !important');
        $("#topscroll .div1").css('width',$("#reportTb").innerWidth() +'px');

        // $("#topscroll").scroll(function(){
        //     $(".dataTables_scrollBody").scrollLeft($("#topscroll").scrollLeft());
        // });
        // $(".dataTables_scrollBody").scroll(function(){
        //     $("#topscroll").scrollLeft($(".dataTables_scrollBody").scrollLeft());
        // });


        

        // window.addEventListener("keydown", (e) => {
        //     if (e.keyCode == 13) {
        //         document.getElementById('orderMainTable').submit();
        //     }
        // })
    });

    
</script>



<?php 
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
