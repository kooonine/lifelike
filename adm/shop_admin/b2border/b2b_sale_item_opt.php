<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');


$order_no= $_POST['order_no'];
$samjin_it_name=$_POST['item'];
$hoching= $_POST['hoching'];
$sap_code= $_POST['sap_code'];
$normal_price=$_POST['price'];
$color = $_POST['color'];
$stock=$_POST['stock'];

$samjin_code =$_POST['order_no'];



$stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(1,0,$sap_code,$color,$hoching);					
if (count($stockSamjin) == 0) {
    $stock = 0;
} else {
    for ($j =0; $j < count($stockSamjin); $j++) {
    	if ($stockSamjin[$j]['C_NO'] == 2 || $stockSamjin[$j]['C_NO'] == 4) {
    		$stock +=floor($stockSamjin[$j]['STOCK2'] * 0.9);
    	}
    }
}
                    

?>

<tr>
    <input type="hidden" name="si_no[]" value="">
    <input type="hidden" name="samjin_code[]" value="<?=$order_no?>">
    <input type="hidden" name="color[]" value="<?=$color?>">
    <td>
        <input type="checkbox" name="chk[]" value="" id="chk_" boId= '' >
    </td>
    <td scope="col"><input type="hidden" name = "samjin_it_name[]" value = "<?=$samjin_it_name?>"><?=$samjin_it_name?></td>
    <td scope="col"><input type="hidden" name = "size[]" value = "<?=$hoching?>"><?=$hoching?></td>
    <td scope="col"><input type="hidden" name = "sap_code[]" value = "<?=$sap_code?>"><?=$sap_code?></td>
    <td scope="col"><input type="hidden" name = "normal_price[]" value = "<?=$normal_price?>"><?=number_format($normal_price)?></td>
    <td scope="col"><input name ="supply_price[]" value=""></td>
    <td scope="col"><input type="hidden" name = "stock[]" value = "<?=$stock?>"><?=$stock?></td>
    <td scope="col"><input type="hidden" name = "display_yn[]" value = "Y">진열</td>
    <td scope="col"><input name ="minium_order[]" value=""></td>
    
</tr>