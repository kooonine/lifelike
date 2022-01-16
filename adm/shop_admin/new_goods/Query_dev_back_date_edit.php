<?php
//$sub_menu = '100310';
$sub_menu = '93';
include_once('./_common.php');

// var_dump($_FILES);
// dd($_POST);



// $sql = " update lt_prod_schedule set $sql_common where ps_id = '$ps_id' ";
// sql_query($sql);

//삼진코드 채번 로직
//이전 상품 체벌 돌리기

// $ps_sql = "SELECT * FROM lt_prod_schedule ";
// $ps_result = sql_query($ps_sql);

// for($psi = 0 ; $ps_row = sql_fetch_array($ps_result); $psi++ ){
//     $code_index = "";
//     $sql_ps_common = "";
    
//     $ps_code_index = "SELECT ps_code_index FROM lt_prod_schedule WHERE ps_it_name = '{$ps_row['ps_it_name']}' AND ps_code_gubun= '{$ps_row['ps_code_gubun']}' 
//     AND ps_code_brand='{$ps_row['ps_code_brand']}' AND ps_code_year ='{$ps_row['ps_code_year']}' 
//     AND ps_code_season='{$ps_row['ps_code_season']}' AND ps_code_item_type='{$ps_row['ps_code_item_type']}' AND ps_code_index IS NOT NULL LIMIT 1";
//     $ps_code_index_result = sql_fetch($ps_code_index);
//     if($ps_code_index_result['ps_code_index']){
//         $code_index = $ps_code_index_result['ps_code_index'];
//     }else{
//         $ps_code_index2 = "SELECT ps_code_index FROM lt_prod_schedule WHERE ps_code_gubun= '{$ps_row['ps_code_gubun']}' 
//         AND ps_code_brand='{$ps_row['ps_code_brand']}' AND ps_code_year ='{$ps_row['ps_code_year']}' 
//         AND ps_code_season='{$ps_row['ps_code_season']}' AND ps_code_item_type='{$ps_row['ps_code_item_type']}' AND ps_code_index IS NOT NULL LIMIT 1";
//         $ps_code_index_result2 = sql_fetch($ps_code_index2);
//         if(empty($ps_code_index_result2['ps_code_index'])){
//             $code_index = '01';
//         }else{
//             $ps_code_index3 = "SELECT MAX(ps_code_index) AS id_max FROM lt_prod_schedule 
//             WHERE ps_code_gubun= '{$ps_row['ps_code_gubun']}' 
//             AND ps_code_brand='{$ps_row['ps_code_brand']}' AND ps_code_year ='{$ps_row['ps_code_year']}' 
//             AND ps_code_season='{$ps_row['ps_code_season']}' AND ps_code_item_type='{$ps_row['ps_code_item_type']}'
//             ";
//             $ps_code_index_result3 = sql_fetch($ps_code_index3);

//             $code_index = str_pad(( (int)$ps_code_index_result3['id_max']+1 ), 2, "0", STR_PAD_LEFT);
//         }
//     }

//     $sql_ps_common .= "ps_code_index = '{$code_index}'";

//     $u_ps_sql = "update  lt_prod_schedule set $sql_ps_common  where ps_id = '{$ps_row['ps_id']}' ";
    
//     sql_query($u_ps_sql);

// }

$jo_id_code_sql = "SELECT * FROM lt_job_order";
$jo_id_code_result = sql_query($jo_id_code_sql);
for($joi = 0 ; $jo_row = sql_fetch_array($jo_id_code_result); $joi++ ){

    $ps_sql = "SELECT * FROM lt_prod_schedule WHERE ps_id = '{$jo_row['ps_id']}'";
    $ps_code_result = sql_fetch($ps_sql);

    $jo_id_code = $ps_code_result['ps_code_gubun']."".$ps_code_result['ps_code_brand']."".$ps_code_result['ps_code_year']."".$ps_code_result['ps_code_season']."".$ps_code_result['ps_code_item_type']."".$ps_code_result['ps_code_index']."".$ps_code_result['ps_code_item_name'];

    $jo_id_code_update = "update lt_job_order set jo_id_code = '{$jo_id_code}' where jo_id = '{$jo_row['jo_id']}'";
    sql_query($jo_id_code_update);
}

$pi_id_code_sql = "SELECT * FROM lt_job_order";
$pi_id_code_result = sql_query($pi_id_code_sql);
for($pii = 0 ; $pi_row = sql_fetch_array($pi_id_code_result); $pii++ ){
    $jo_id_code = $pi_row['jo_id_code'];

    $pi_id_code_update = "update lt_prod_info set pi_model_name = '{$jo_id_code}' ,  pi_model_no = '{$jo_id_code}' where jo_id = '{$pi_row['jo_id']}'";
    sql_query($pi_id_code_update);

}



// $copy_job_order_sql = "select * from lt_job_order where jo_it_name = '{$origin_name}' ORDER BY jo_id ASC";

// $copy_job_order_result = sql_query($copy_job_order_sql);

// for($joc = 0 ; $jo_row = sql_fetch_array($copy_job_order_result); $joc++){
//     $jo_common = "
//                 jo_temp = '{$jo_row['jo_temp']}',
//                 jo_gubun = '{$jo_row['jo_gubun']}',
//                 jo_prod_gubun = '{$jo_row['jo_prod_gubun']}',
//                 jo_id_code = '',
//                 jo_brand = '{$jo_row['jo_brand']}',
//                 jo_it_name = '{$new_name}',
//                 jo_reg_date = '{$jo_row['jo_reg_date']}',
//                 jo_prod_type = '{$jo_row['jo_prod_type']}',
//                 jo_prod_name = '{$jo_row['jo_prod_name']}',
//                 jo_prod_year = '{$jo_row['jo_prod_year']}',
//                 jo_season = '{$jo_row['jo_season']}',
//                 jo_size_code = '{$jo_row['jo_size_code']}',
//                 jo_size = '{$jo_row['jo_size']}',
//                 jo_size_wid = '{$jo_row['jo_size_wid']}',
//                 jo_size_verti = '{$jo_row['jo_size_verti']}',
//                 jo_size_hei = '{$jo_row['jo_size_hei']}',
//                 jo_color = '{$jo_row['jo_color']}',
//                 jo_user = '{$jo_row['jo_user']}',
//                 jo_soje = '{$jo_row['jo_soje']}',
//                 jo_main_img = '{$jo_row['jo_main_img']}',
//                 jo_codi_img = '{$jo_row['jo_codi_img']}',
//                 jo_sub_img = '{$jo_row['jo_sub_img']}',
//                 jo_design_img = '{$jo_row['jo_design_img']}',
//                 jo_memo = '{$jo_row['jo_memo']}',
//                 jo_memo_img = '{$jo_row['jo_memo_img']}',
//                 jo_mater_info = '{$jo_row['jo_mater_info']}',
//                 jo_sub_mater = '{$jo_row['jo_sub_mater']}',
//                 jo_maip_price = '{$jo_row['jo_maip_price']}',
//                 jo_gakong_item = '{$jo_row['jo_gakong_item']}',
//                 jo_bongje = '{$jo_row['jo_bongje']}',
//                 jo_juip_price = '{$jo_row['jo_juip_price']}',
//                 jo_pack_price = '{$jo_row['jo_pack_price']}',
//                 jo_un_im = '{$jo_row['jo_un_im']}',
//                 jo_customs = '{$jo_row['jo_customs']}',
//                 jo_prod_origin_price = '{$jo_row['jo_prod_origin_price']}',
//                 jo_prod_control_price = '{$jo_row['jo_prod_control_price']}',
//                 jo_total_origin_price = '{$jo_row['jo_total_origin_price']}',
//                 jo_mater_name = '{$jo_row['jo_mater_name']}',
//                 jo_etc_company = '{$jo_row['jo_etc_company']}',
//                 jo_etc_company_tel = '{$jo_row['jo_etc_company_tel']}',
//                 jo_pumjil = '{$jo_row['jo_pumjil']}'
    
    
//     ";

//     $sql_jo = "insert lt_job_order set $jo_common";
//     sql_query($sql_jo);

//     $jo_id = sql_insert_id();

//     $jo_ps_id_update_sql = "select *  from lt_job_order where jo_id = '{$jo_id}'";
//     $jo_ps_id_update_result = sql_fetch($jo_ps_id_update_sql);

//     $ps_data_set_sql = "select * from lt_prod_schedule where ps_it_name = '{$new_name}' and ps_prod_name ='{$jo_ps_id_update_result['jo_prod_name']}' ";
//     $ps_data_set_result = sql_fetch($ps_data_set_sql);

//     $jo_u_common = "ps_id = '{$ps_data_set_result['ps_id']}'";

//     if($ps_data_set_result['ps_code_gubun'] && $ps_data_set_result['ps_code_brand'] && $ps_data_set_result['ps_code_year'] && $ps_data_set_result['ps_code_season'] && $ps_data_set_result['ps_code_item_type'] && $ps_data_set_result['ps_code_index'] && $ps_data_set_result['ps_code_item_name']){
//         $jo_id_code = $ps_data_set_result['ps_code_gubun']."".$ps_data_set_result['ps_code_brand']."".$ps_data_set_result['ps_code_year']."".$ps_data_set_result['ps_code_season']."".$ps_data_set_result['ps_code_item_type']."".$ps_data_set_result['ps_code_index']."".$ps_data_set_result['ps_code_item_name'];
        
//         //중복체크
//         $chk_sql = "SELECT COUNT(*) AS CNT FROM lt_job_order WHERE jo_id_code = '{$jo_id_code}' AND jo_it_name <> '{$new_name}'  ";
//         $chk_sql_result = sql_fetch($chk_sql);
//         if($chk_sql_result['CNT'] == 0 ) {
//             $jo_u_common .= ",jo_id_code = '{$jo_id_code}'";
//         }else{
//             $jo_u_common .= ",jo_id_code = '삼진코드중복'";
//         }
//     }

//     $jo_u_sql = " update lt_job_order set $jo_u_common  where jo_id = '{$jo_id}' ";
//     sql_query($jo_u_sql);

//     $jo_text_sql = "
//     UPDATE lt_job_order AS a , (SELECT jo_soje,jo_main_img,jo_codi_img,jo_sub_img,jo_mater_info,jo_sub_mater,jo_maip_price,jo_gakong_item,jo_mater_name,jo_pumjil 
//     FROM lt_job_order WHERE jo_id = '{$jo_row['jo_id']}') AS temp
//     SET a.jo_main_img = temp.jo_main_img,
//      a.jo_codi_img=temp.jo_codi_img,
//      a.jo_sub_img = temp.jo_sub_img,
//      a.jo_mater_info = temp.jo_mater_info,
//      a.jo_sub_mater = temp.jo_sub_mater,
//      a.jo_maip_price = temp.jo_maip_price,
//      a.jo_gakong_item = temp.jo_gakong_item,
//      a.jo_mater_name= temp.jo_mater_name,
//      a.jo_pumjil = temp.jo_pumjil
//     WHERE a.jo_id = '{$jo_id}'
//     ";
//     sql_query($jo_text_sql);


//     //상품정보집
//     $copy_item_info_sql = "select * from lt_prod_info where jo_id = '{$jo_row['jo_id']}'";

//     $pi_row_copy_item = sql_fetch($copy_item_info_sql);
    
//     $sql_pi_common = "";
//     $sql_pi_common .= "ps_id = '{$ps_data_set_result['ps_id']}'";
//     $sql_pi_common .= ",pi_size_name = '{$jo_ps_id_update_result['jo_size_code']}'";
//     $sql_pi_common .= ",tem_save = 'N'";
//     $sql_pi_common .= ",jo_id = '{$jo_id}'";
//     $sql_pi_common .= ",pi_model_name = '{$jo_id_code}'";
//     $sql_pi_common .= ",pi_model_no = '{$jo_id_code}'";

//     if(!empty($pi_row_copy_item)){
//         $sql_pi_common .= " ,pi_sub_category= '{$pi_row_copy_item['pi_sub_category']}',
//                     pi_design_style= '{$pi_row_copy_item['pi_design_style']}',
//                     pi_design_style_sub= '{$pi_row_copy_item['pi_design_style_sub']}',
//                     pi_season= '{$pi_row_copy_item['pi_season']}',
//                     pi_it_name= '{$pi_row_copy_item['pi_it_name']}',
//                     pi_it_sub_name= '{$pi_row_copy_item['pi_it_sub_name']}',
                    
//                     pi_company_it_id= '{$pi_row_copy_item['pi_company_it_id']}',
//                     pi_brand= '{$pi_row_copy_item['pi_brand']}',
//                     pi_category= '{$pi_row_copy_item['pi_category']}',
//                     pi_mater= '{$pi_row_copy_item['pi_mater']}',
//                     pi_prod_date= '{$pi_row_copy_item['pi_prod_date']}',
//                     pi_age_gubun= '{$pi_row_copy_item['pi_age_gubun']}',
//                     pi_delivery_price= '{$pi_row_copy_item['pi_delivery_price']}',
                    
                    
                    
//                     pi_item_soje= '{$pi_row_copy_item['pi_item_soje']}',
//                     pi_color= '{$pi_row_copy_item['pi_color']}',
                    
                    
//                     pi_maker= '{$pi_row_copy_item['pi_maker']}',
//                     pi_laundry= '{$pi_row_copy_item['pi_laundry']}',
//                     pi_kc_safe_yn= '{$pi_row_copy_item['pi_kc_safe_yn']}',
//                     pi_soip_yn= '{$pi_row_copy_item['pi_soip_yn']}',
                    
                    
//                     pi_charge= '{$pi_row_copy_item['pi_charge']}',
                    
//                     pi_charge_mater= '{$pi_row_copy_item['pi_charge_mater']}',
//                     pi_charge_mater_etc= '{$pi_row_copy_item['pi_charge_mater_etc']}',
//                     pi_charge_brand= '{$pi_row_copy_item['pi_charge_brand']}',
//                     pi_charge_brand_etc = '{$pi_row_copy_item['pi_charge_brand_etc']}',
//                     pi_charge_weight= '{$pi_row_copy_item['pi_charge_weight']}',
//                     pi_charge_weight_etc= '{$pi_row_copy_item['pi_charge_weight_etc']}',

//                     pi_ll_style= '{$pi_row_copy_item['pi_ll_style']}',
//                     pi_prauden_umu_yn= '{$pi_row_copy_item['pi_prauden_umu_yn']}',
//                     pi_hangkun_info= '{$pi_row_copy_item['pi_hangkun_info']}',
//                     pi_hangkun_info_txt= '{$pi_row_copy_item['pi_hangkun_info_txt']}',
//                     pi_pilpower= '{$pi_row_copy_item['pi_pilpower']}',
//                     pi_pilpower_safe_yn= '{$pi_row_copy_item['pi_pilpower_safe_yn']}',
//                     pi_info1= '{$pi_row_copy_item['pi_info1']}',
//                     pi_info2= '{$pi_row_copy_item['pi_info2']}',
//                     pi_info2_1= '{$pi_row_copy_item['pi_info2_1']}',
//                     pi_info3= '{$pi_row_copy_item['pi_info3']}',
//                     pi_manager= '{$pi_row_copy_item['pi_manager']}',
//                     pi_img = '',
//                     pi_img_total = '{$pi_row_copy_item['pi_img_total']}',
//                     pi_video1= '{$pi_row_copy_item['pi_video1']}',
//                     pi_video2= '{$pi_row_copy_item['pi_video2']}',
//                     pi_video3= '{$pi_row_copy_item['pi_video3']}',
//                     pi_video4= '{$pi_row_copy_item['pi_video4']}',
//                     pi_origin_image= '{$pi_row_copy_item['pi_origin_image']}',
//                     pi_detail_info= '{$pi_row_copy_item['pi_detail_info']}',
//                     pi_selling1= '{$pi_row_copy_item['pi_selling1']}',
//                     pi_selling2= '{$pi_row_copy_item['pi_selling2']}',
//                     pi_selling3= '{$pi_row_copy_item['pi_selling3']}',
//                     pi_prod_info1= '{$pi_row_copy_item['pi_prod_info1']}',
//                     pi_prod_info2= '{$pi_row_copy_item['pi_prod_info2']}',
//                     pi_prod_info3= '{$pi_row_copy_item['pi_prod_info3']}',
//                     pi_prod_info4= '{$pi_row_copy_item['pi_prod_info4']}',
//                     pi_prod_info5= '{$pi_row_copy_item['pi_prod_info5']}',
//                     pi_prod_info6= '{$pi_row_copy_item['pi_prod_info6']}',
//                     pi_prod_info7= '{$pi_row_copy_item['pi_prod_info7']}',
//                     pi_prod_info8= '{$pi_row_copy_item['pi_prod_info8']}',
//                     pi_prod_info9= '{$pi_row_copy_item['pi_prod_info9']}',
//                     pi_prod_info10= '{$pi_row_copy_item['pi_prod_info10']}',
//                     etc= '{$pi_row_copy_item['etc']}'
//         ";

//     }

//     $sql_pi = "insert lt_prod_info set $sql_pi_common";
//     sql_query($sql_pi);

//     $ps_text_sql = "
//     UPDATE lt_prod_info AS a , (SELECT pi_laundry,pi_img
//     FROM lt_prod_info WHERE jo_id = '{$jo_row['jo_id']}') AS temp
//     SET a.pi_laundry = temp.pi_laundry,
//      a.pi_img=temp.pi_img
//     WHERE a.jo_id = '{$jo_id}'
//     ";
    
//     sql_query($ps_text_sql);
    
// }

// $s_sql = "SELECT COUNT(*), ps_code_gubun ,ps_code_brand , ps_code_year ,ps_code_season ,ps_code_item_type   
// FROM lt_prod_schedule 
// WHERE ps_display= 'Y' 
// GROUP BY ps_code_gubun ,ps_code_brand ,ps_code_year ,ps_code_season ,ps_code_item_type ";

// $s_result = sql_query($s_sql);

// for($si = 0 ; $s_row = sql_fetch_array($s_result); $si++ ){
    
//     $sql = "
//     SELECT * FROM lt_prod_schedule
//     WHERE ps_display= 'Y' 
//     AND ps_code_gubun ='MA'
//     AND ps_code_brand='S'
//     AND ps_code_year='00'
//     AND ps_code_season= 'F'
//     AND ps_code_item_type ='C'
//     ORDER BY ps_id ASC
//     ";
//     $index_reset = sql_query($sql);
//     $index_common = "";
    
//     for($i = 0 ; $idx_row = sql_fetch_array($index_reset); $i++ ){
//         $code_index = str_pad(($i+1), 2, "0", STR_PAD_LEFT);
//         $index_common = "";
//         $index_common .= "ps_code_index = '{$code_index}'";
//         $up_sql = "update  lt_prod_schedule set $index_common  where ps_id = '{$idx_row['ps_id']}' ";
//         //sql_query($up_sql);
//     }


//     // $code_index = "";
    
//     // $ps_code_index = "SELECT ps_code_index FROM lt_prod_schedule WHERE ps_it_name = '{$new_name}' AND ps_code_gubun= '{$ps_row['ps_code_gubun']}' 
//     // AND ps_code_brand='{$ps_row['ps_code_brand']}' AND ps_code_year ='{$ps_row['ps_code_year']}' 
//     // AND ps_code_season='{$ps_row['ps_code_season']}' AND ps_code_item_type='{$ps_row['ps_code_item_type']}' AND ps_code_index IS NOT NULL LIMIT 1";
//     // $ps_code_index_result = sql_fetch($ps_code_index);
//     // if($ps_code_index_result['ps_code_index']){
//     //     $code_index = $ps_code_index_result['ps_code_index'];
//     // }else{
//     //     $ps_code_index2 = "SELECT ps_code_index FROM lt_prod_schedule WHERE ps_code_gubun= '{$ps_row['ps_code_gubun']}' 
//     //     AND ps_code_brand='{$ps_row['ps_code_brand']}' AND ps_code_year ='{$ps_row['ps_code_year']}' 
//     //     AND ps_code_season='{$ps_row['ps_code_season']}' AND ps_code_item_type='{$ps_row['ps_code_item_type']}' AND ps_code_index IS NOT NULL LIMIT 1";
//     //     $ps_code_index_result2 = sql_fetch($ps_code_index2);
//     //     if(empty($ps_code_index_result2['ps_code_index'])){
//     //         $code_index = '01';
//     //     }else{
//     //         $ps_code_index3 = "SELECT COUNT(*) AS CNT FROM (SELECT ps_it_name FROM lt_prod_schedule 
//     //         WHERE ps_code_gubun= '{$ps_row['ps_code_gubun']}' 
//     //         AND ps_code_brand='{$ps_row['ps_code_brand']}' AND ps_code_year ='{$ps_row['ps_code_year']}' 
//     //         AND ps_code_season='{$ps_row['ps_code_season']}' AND ps_code_item_type='{$ps_row['ps_code_item_type']}'
//     //         GROUP BY ps_it_name) AS temp";
//     //         $ps_code_index_result3 = sql_fetch($ps_code_index3);

//     //         $code_index = str_pad(($ps_code_index_result3['CNT']+1), 2, "0", STR_PAD_LEFT);
//     //     }
//     // }

// }




goto_url("./new_goods_process.php?tabs=list&brands=&ipgos=&dpart_ipgos=&shootings=&reorders=&sfl=it_name&stx=&sc_it_time=&limit_list=10");
