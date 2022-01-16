<?php
$sub_menu = '40';
include_once('./_common.php');
include_once('./admin.shop.lib.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');
include_once(G5_LIB_PATH.'/samjin.lib.php');

$auth_sub_menu = $auth[substr($sub_menu,0,2)];
if($is_admin == "brand") $auth_sub_menu = $auth['92'];
auth_check($auth_sub_menu, 'w');

check_admin_token();

define("_ORDERMAIL_", true);

for ($i=0; $i<count($_POST['chk']); $i++)
{
    // 실제 번호를 넘김
    $k     = $_POST['chk'][$i];
    $od_id = $_POST['od_id'][$k];


    $od = sql_fetch(" select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ");
    if (!$od) continue;

    //change_order_status($od['od_status'], $_POST['od_status'], $od);
    //echo $od_id . "<br>";

    $current_status = $od['od_status'];
    $change_status  = $_POST['od_status'];

    switch ($current_status)
    {
        case '반품수거중' :
            if ($change_status != '수거완료') continue;
            $ct = "SELECT * FROM {$g5['g5_shop_cart_table']} WHERE od_id = '$od_id' AND ct_status = '$current_status'";;
            $ctRes = sql_query($ct);
            while ($row = sql_fetch_array($ctRes)) { 
                $ctId = $row['ct_id'];
                $itID = $row['it_id'];
				$ctQty = $row['ct_qty'];
				$ioId = $row['io_id'];
                $ioType = $row['io_type'];
                
                if ($ioId) {
					$sql = " update {$g5['g5_shop_item_option_table']}
								set io_stock_qty = io_stock_qty + '{$ctQty}'
								where it_id = '{$itID}'
								  and io_id = '{$ioId}'
								  and io_type = '{$ioType}' ";
				} else {
					$sql = " update {$g5['g5_shop_item_table']}
								set it_stock_qty = it_stock_qty + '{$ctQty}'
								where it_id = '{$itID}' ";
				}
				sql_query($sql);
				$stock_use = 2;
				$stockSql = " update {$g5['g5_shop_cart_table']} set ct_stock_use  = '$stock_use' where ct_id = '{$ctId}' ";
                sql_query($stockSql);
                change_status($od_id, '반품수거중', '수거완료',$row['ct_id']);
            }
            // change_status($od_id, '반품수거중', '수거완료','');
            break;
        case '철회수거중' :
            if ($change_status != '수거완료') continue;
            change_status($od_id, '철회수거중', '수거완료');
            break;
        case '해지요청' :
            if ($change_status != '해지취소') continue;
            change_status($od_id, '해지요청', '해지취소');
            break;
        case '해지수거중' :
            if ($change_status != '수거완료') continue;
            change_status($od_id, '해지수거중', '수거완료');
            break;
        case '수거완료' :
            if ($change_status != '해지완료') continue;
            change_status($od_id, '수거완료', '해지완료');
            
            $od_contractout = $od['od_contractout'];
            if($od_contractout == "일반해지"){
                
                SM_ADD_RENTAL_RETURN($od_id);
            }else if($od_contractout == "분실해지"){
                
                SM_ADD_RENTAL_LOSS($od_id, "1");
            }else if($od_contractout == "파손해지"){
                
                SM_ADD_RENTAL_LOSS($od_id, "2");
            }else if($od_contractout == "분실/파손해지"){
                
                SM_ADD_RENTAL_LOSS($od_id, "1");
            }
            
            include(G5_SHOP_PATH.'/ordermail1.inc.php');
            $arr_change_data = array();
            $arr_change_data['고객명'] = $od['od_name'];
            $arr_change_data['이름'] = $od['od_name'];
            $arr_change_data['보낸분'] = $od['od_name'];
            $arr_change_data['받는분'] = $od['od_b_name'];;
            $arr_change_data['주문번호'] = $od_id;
            $arr_change_data['주문금액'] = number_format($ttotal_price + $od_send_cost + $od_send_cost2);
            $arr_change_data['결제금액'] = number_format($od_receipt_price);
            $arr_change_data['회원아이디'] = $od['mb_id'];
            $arr_change_data['회사명'] = $default['de_admin_company_name'];
            $arr_change_data["아이디"] = $od['mb_id'];
            $arr_change_data["고객명(아이디)"] = $od['od_name']."(".$od['mb_id'].")";
            $arr_change_data["od_list"] = $list;
            $arr_change_data['od_type'] = $od['od_type'];
            $arr_change_data['od_id'] = $od_id;
            
            msg_autosend('리스', '해지 완료', $od['mb_id'], $arr_change_data);
            break;

    } // switch end
}

$qstr  = "claimtype=".$_POST['claimtype'];

goto_url("./claimlist".(($is_admin == "brand")?".brand":"").".php?$qstr");
?>