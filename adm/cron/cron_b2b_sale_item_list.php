<? #!/usr/local/php53/bin/php
$chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
$root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));

include_once($root_path . '/../../common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');


$sql = "SELECT * FROM b2b_sale_item_list" ; 
$result = sql_query($sql);

$toDate = date('Y-m-d H:i:s');

for($i=0 ; $row = sql_fetch_array($result); $i++){
    $si_no = $row['si_no'];
    $sapCode12 = $row['samjin_code'];
    $color = $row['color'];
    $size = $row['size'];
    //재고
    $dpart_stock = NM_GET_STOCK_WITH_SAP_CODE(1,0,$sapCode12,$color,$size);
    $stock = 0; 
    $ORDER_NO = '';
    if(count($dpart_stock) > 0){
        for($t=0 ;  $t< count($dpart_stock); $t++){
            $ORDER_NO = $dpart_stock[0]['ORDER_NO'];
            
                
            if ($dpart_stock[$t]['C_NO'] == 2 || $dpart_stock[$t]['C_NO'] == 4 ) {
                //90%
                $stock +=floor($dpart_stock[$t]['STOCK2'] * 0.9);
            }
        
            
        }
    }else{
    }


    $common = "
    stock = '{$stock}'
    ,up_date = '{$toDate}'
    
    
    ";

    $upsql = "UPDATE b2b_sale_item_list SET $common WHERE si_no = '{$si_no}' ";
    sql_query($upsql);
}
