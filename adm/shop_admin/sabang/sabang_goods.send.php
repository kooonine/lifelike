<?php
//$sub_menu = '930100';
$sub_menu = '93';
include_once('./_common.php');


auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '사방넷 상품재고전송';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

if($od_type == "") $od_type = "L";
?>


<form name="create_order_xml" action = "./create_order_xml.php" method = "POST" enctype = "multipart/form-data">
         <input type = "submit"/>
</form>


<form  name = "create_xml_goods" action = "./create_goods_xml.php" method = "POST" enctype = "multipart/form-data">

    상품코드<input type="text" name="compayny_goods_cd" value="" > <button class="make_xml">사방넷 전송 파일 생성</button>

</form>


<form name="excel_file_upload" action = "./update_sabang_goods.php" method = "POST" enctype = "multipart/form-data">
         <input type = "file" name = "excel"  />
         <input type = "submit"/>
</form>


<?php 
$sabang_origin_goods = "select * from sabang_goods_origin limit 50";
$sb_sql = sql_query($sabang_origin_goods);
$sb_db_data = sql_fetch($sabang_origin_goods);

?>
<style>
    .sabang_goods_table{
        white-space : nowrap;
    }

</style>

<table class="sabang_goods_table">
    <th>no</th>
    <th>사방넷상품코드</th>
    <th>자체상품코드</th>
    <th>SAP코드</th>
    <th>상품명</th>
    <th>상품상태</th>
    <th>원가</th>
    <th>판매가</th>
    <th>TAG가</th>
    <th>옵션명1</th>
    <th>옵션상세1</th>
    <th>옵션명2</th>
    <th>옵션상세2</th>
<?php
    for ($i = 0; $row = sql_fetch_array($sb_sql); $i++) {
        //$str_confirm = sprintf("'%s','%s','%s',%d,'%s'", $row['ORDER_NO'], $row['SAP_CODE'], $row['ITEM'], $row['PRICE'], $_POST['subID']);
        ?>
    <tr>
        <td><?=$i+1?></td>
        <td><?=$row['sabang_goods_cd']?></td>
        <td><?=$row['compayny_goods_cd']?></td>
        <td><?=$row['model_no']?></td>
        <td><?=$row['goods_nm']?></td>
        <td><?=$row['status']?></td>
        <td><?=$row['goods_cost']?></td>
        <td><?=$row['goods_price']?></td>
        <td><?=$row['goods_consumer_price']?></td>
        <td><?=$row['char_1_nm']?></td>
        <td><?=$row['char_1_val']?></td>
        <td><?=$row['char_2_nm']?></td>
        <td><?=$row['char_2_val']?></td>
    </tr>
<?php
} ?>

</table>

<script>
    // $(".make_xml").click(function(){
        
    //     var compayny_goods_cd =  $("input[name='it_id']").val();

    //     $.ajax({
    //         url:'./create_goods_xml.php',
    //         type:'post',
    //         data:{compayny_goods_cd : compayny_goods_cd},
            
    //         success:function(response){
                
    //         }
    //     });

    // });

</script>

<?php 
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
