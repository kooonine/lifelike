<?php
//$sub_menu = '100310';
$sub_menu = '93';
include_once('./_common.php');

// var_dump($_FILES);
// dd($_POST);

$origin_ps_id = $_POST['origin_ps_id'];
$new_name = $_POST['new_name'];
$chain_item = $_POST['chain_item'];

function trans_preg($txt){
    $text_trans = preg_replace("/[\'\"]/", "", $txt);

    return $text_trans;
}


$copy_prod_schedule_sql = "select * from lt_prod_schedule where ps_id = '{$origin_ps_id}' ORDER BY ps_id ASC";

$copy_prod_schedule_result = sql_query($copy_prod_schedule_sql);

for($psc = 0 ; $ps_row = sql_fetch_array($copy_prod_schedule_result); $psc++){
    $ps_common = "ps_statue = '{$ps_row['ps_statue']}'
            ,ps_re_order = '{$ps_row['ps_re_order']}'
            ,ps_ipgo_status = '{$ps_row['ps_ipgo_status']}'
            ,ps_gubun = '{$ps_row['ps_gubun']}'
            ,ps_limit_date = '{$ps_row['ps_limit_date']}'
            ,ps_os = '{$ps_row['ps_os']}'
            ,ps_brand = '{$ps_row['ps_brand']}'
            ,ps_prod_gubun = '{$ps_row['ps_prod_gubun']}'
            ,ps_it_name = '{$new_name}'
            ,ps_prod_name = '{$ps_row['ps_prod_name']}'
            ,ps_size = '{$ps_row['ps_size']}'
            ,ps_code = '{$ps_row['ps_code']}'
            ,ps_company_name = '{$ps_row['ps_company_name']}'
            ,ps_approval_date = '{$ps_row['ps_approval_date']}'
            ,ps_prod_company = '{$ps_row['ps_prod_company']}'
            ,ps_balju = '{$ps_row['ps_balju']}'
            ,ps_expected_limit_date = '{$ps_row['ps_expected_limit_date']}'
            ,ps_gumpum = '{$ps_row['ps_gumpum']}'
            ,ps_prod_balju = '{$ps_row['ps_prod_balju']}'
            ,ps_sample_date = '{$ps_row['ps_sample_date']}'
            ,ps_ipgo_date = '{$ps_row['ps_ipgo_date']}'
            ,ps_prod_proprosal_date = '{$ps_row['ps_prod_proprosal_date']}'
            ,ps_real_ipgo_date = '{$ps_row['ps_real_ipgo_date']}'
            ,ps_reg_date = 'now()'
            ,ps_origin_ps_id = ''
            ,ps_reorder_id = '{$ps_row['ps_reorder_id']}'
            ,ps_shooting_yn = '{$ps_row['ps_shooting_yn']}'
            
            ,ps_code_gubun = '{$ps_row['ps_code_gubun']}'
            ,ps_code_brand = '{$ps_row['ps_code_brand']}'
            ,ps_code_year = '{$ps_row['ps_code_year']}'
            ,ps_code_season = '{$ps_row['ps_code_season']}'
            ,ps_code_item_type = '{$ps_row['ps_code_item_type']}'
            ,ps_code_item_name = '{$ps_row['ps_code_item_name']}'
            ,ps_code_samjin = '{$ps_row['ps_code_samjin']}'
            ,ps_display = '{$ps_row['ps_display']}' 
    
    ";

    $code_index = "";
    
    $ps_code_index = "SELECT ps_code_index FROM lt_prod_schedule WHERE ps_it_name = '{$new_name}' AND ps_code_gubun= '{$ps_row['ps_code_gubun']}' 
    AND ps_code_brand='{$ps_row['ps_code_brand']}' AND ps_code_year ='{$ps_row['ps_code_year']}' 
    AND ps_code_season='{$ps_row['ps_code_season']}' AND ps_code_item_type='{$ps_row['ps_code_item_type']}' AND ps_code_index IS NOT NULL LIMIT 1";
    $ps_code_index_result = sql_fetch($ps_code_index);
    if($ps_code_index_result['ps_code_index']){
        $code_index = $ps_code_index_result['ps_code_index'];
    }else{
        $ps_code_index2 = "SELECT ps_code_index FROM lt_prod_schedule WHERE ps_code_gubun= '{$ps_row['ps_code_gubun']}' 
        AND ps_code_brand='{$ps_row['ps_code_brand']}' AND ps_code_year ='{$ps_row['ps_code_year']}' 
        AND ps_code_season='{$ps_row['ps_code_season']}' AND ps_code_item_type='{$ps_row['ps_code_item_type']}' AND ps_code_index IS NOT NULL LIMIT 1";
        $ps_code_index_result2 = sql_fetch($ps_code_index2);
        if(empty($ps_code_index_result2['ps_code_index'])){
            $code_index = '01';
        }else{
            $ps_code_index3 = "SELECT MAX(temp.max_i) AS MAXM , COUNT(temp.cnt_i) AS CNT FROM (SELECT MAX(ps_code_index) AS max_i , COUNT(ps_code_index) AS cnt_i , ps_it_name FROM lt_prod_schedule 
            WHERE ps_code_gubun= '{$ps_row['ps_code_gubun']}' 
            AND ps_code_brand='{$ps_row['ps_code_brand']}' AND ps_code_year ='{$ps_row['ps_code_year']}' 
            AND ps_code_season='{$ps_row['ps_code_season']}' AND ps_code_item_type='{$ps_row['ps_code_item_type']}'
            GROUP BY ps_it_name) AS temp";
            $ps_code_index_result3 = sql_fetch($ps_code_index3);

            $code_index = str_pad(($ps_code_index_result3['MAXM']+1), 2, "0", STR_PAD_LEFT);

            if($code_index > 99){
                $code_index  = str_pad(($ps_code_index_result3['CNT']+1), 2, "0", STR_PAD_LEFT);
            }
        }
    }
    $ps_common .= ",ps_code_index = '{$code_index}'";
    
    if($chain_item == 'true'){
        $ps_chain_gb = 'Y';
        $ps_chain_code = $ps_row['ps_code_gubun'].$ps_row['ps_code_brand'].$ps_row['ps_code_year'].$ps_row['ps_code_season'].$ps_row['ps_code_item_type'].$ps_row['ps_code_index'].$ps_row['ps_code_item_name'];
        $ps_common .= ",ps_chain_gb = '{$ps_chain_gb}'";
        $ps_common .= ",ps_chain_code = '{$ps_chain_code}'";
        $chain_ps = "update lt_prod_schedule set ps_chain_gb = 'Y' where ps_id = '{$ps_row['ps_id']}'";
        sql_query($chain_ps);
    }


    $sql_ps = "insert lt_prod_schedule set $ps_common";
    sql_query($sql_ps);
    $ps_id = sql_insert_id();

    //var 1.0
    // $code_index = 0;
    // $ps_code_index = "SELECT ps_code_index FROM lt_prod_schedule WHERE ps_it_name = '{$new_name}' AND ps_code_index IS NOT NULL LIMIT 1";
    // $ps_code_index_result = sql_fetch($ps_code_index);
    // if($ps_code_index_result['ps_code_index']){
    //     $code_index = $ps_code_index_result['ps_code_index'];
    // }else{
    //     $ps_code_index2 = "SELECT COUNT(aa.code_index) AS CNT FROM (SELECT ps_it_name AS code_index FROM lt_prod_schedule  GROUP BY ps_it_name) AS aa ";
    //     $ps_code_index_result2 = sql_fetch($ps_code_index2);
    //     $code_index = str_pad($ps_code_index_result2['CNT'], 2, "0", STR_PAD_LEFT);
    // }


    $ps_u_sql = " update lt_prod_schedule set ps_origin_ps_id = '{$ps_row['ps_id']}'  where ps_id = '$ps_id' ";
    sql_query($ps_u_sql);

    //text 데이터 복사
    $ps_text_sql = "update lt_prod_schedule set ps_size = (SELECT * FROM  (select ps_size from lt_prod_schedule where ps_id = '{$ps_row['ps_id']}') AS temp ) where ps_id = '{$ps_id}'";
    sql_query($ps_text_sql);
}

$copy_job_order_sql = "select * from lt_job_order where ps_id = '{$origin_ps_id}' ORDER BY jo_id ASC";

$copy_job_order_result = sql_query($copy_job_order_sql);

for($joc = 0 ; $jo_row = sql_fetch_array($copy_job_order_result); $joc++){
    $jo_common = "
                jo_temp = '{$jo_row['jo_temp']}',
                jo_gubun = '{$jo_row['jo_gubun']}',
                jo_prod_gubun = '{$jo_row['jo_prod_gubun']}',
                jo_id_code = '',
                jo_brand = '{$jo_row['jo_brand']}',
                jo_it_name = '{$new_name}',
                jo_reg_date = 'now()',
                jo_prod_type = '{$jo_row['jo_prod_type']}',
                jo_prod_name = '{$jo_row['jo_prod_name']}',
                jo_prod_year = '{$jo_row['jo_prod_year']}',
                jo_season = '{$jo_row['jo_season']}',
                jo_size_code = '{$jo_row['jo_size_code']}',
                jo_size = '{$jo_row['jo_size']}',
                jo_size_wid = '{$jo_row['jo_size_wid']}',
                jo_size_verti = '{$jo_row['jo_size_verti']}',
                jo_size_hei = '{$jo_row['jo_size_hei']}',
                jo_color = '{$jo_row['jo_color']}',
                jo_user = '{$member['mb_name']}',
                jo_soje = '{$jo_row['jo_soje']}',
                jo_main_img = '{$jo_row['jo_main_img']}',
                jo_codi_img = '{$jo_row['jo_codi_img']}',
                jo_sub_img = '{$jo_row['jo_sub_img']}',
                jo_design_img = '{$jo_row['jo_design_img']}',
                
                jo_memo_img = '{$jo_row['jo_memo_img']}',
                jo_mater_info = '{$jo_row['jo_mater_info']}',
                jo_sub_mater = '{$jo_row['jo_sub_mater']}',
                jo_maip_price = '{$jo_row['jo_maip_price']}',
                jo_gakong_item = '{$jo_row['jo_gakong_item']}',
                jo_bongje = '{$jo_row['jo_bongje']}',
                jo_juip_price = '{$jo_row['jo_juip_price']}',
                jo_pack_price = '{$jo_row['jo_pack_price']}',
                jo_un_im = '{$jo_row['jo_un_im']}',
                jo_customs = '{$jo_row['jo_customs']}',
                jo_prod_origin_price = '{$jo_row['jo_prod_origin_price']}',
                jo_prod_control_price = '{$jo_row['jo_prod_control_price']}',
                jo_total_origin_price = '{$jo_row['jo_total_origin_price']}',
                jo_mater_name = '{$jo_row['jo_mater_name']}',
                jo_etc_company = '{$jo_row['jo_etc_company']}',
                jo_etc_company_tel = '{$jo_row['jo_etc_company_tel']}',
                jo_pumjil = '{$jo_row['jo_pumjil']}'
    
    
    ";

    $jo_common .= ",jo_memo = '" . addslashes($jo_row['jo_memo']) . "'";

    $sql_jo = "insert lt_job_order set $jo_common";
    sql_query($sql_jo);

    $jo_id = sql_insert_id();

    $jo_ps_id_update_sql = "select *  from lt_job_order where jo_id = '{$jo_id}'";
    $jo_ps_id_update_result = sql_fetch($jo_ps_id_update_sql);

    $ps_data_set_sql = "select * from lt_prod_schedule where ps_it_name = '{$new_name}' and ps_prod_name ='{$jo_ps_id_update_result['jo_prod_name']}' ";
    $ps_data_set_result = sql_fetch($ps_data_set_sql);

    $jo_u_common = "ps_id = '{$ps_data_set_result['ps_id']}'";

    if($ps_data_set_result['ps_code_gubun'] && $ps_data_set_result['ps_code_brand'] && $ps_data_set_result['ps_code_year'] && $ps_data_set_result['ps_code_season'] && $ps_data_set_result['ps_code_item_type'] && $ps_data_set_result['ps_code_index'] && $ps_data_set_result['ps_code_item_name']){
        $jo_id_code = $ps_data_set_result['ps_code_gubun']."".$ps_data_set_result['ps_code_brand']."".$ps_data_set_result['ps_code_year']."".$ps_data_set_result['ps_code_season']."".$ps_data_set_result['ps_code_item_type']."".$ps_data_set_result['ps_code_index']."".$ps_data_set_result['ps_code_item_name'];
        
        //중복체크
        $chk_sql = "SELECT COUNT(*) AS CNT FROM lt_job_order WHERE jo_id_code = '{$jo_id_code}' AND jo_it_name <> '{$new_name}'  ";
        $chk_sql_result = sql_fetch($chk_sql);
        if($chk_sql_result['CNT'] == 0 ) {
            $jo_u_common .= ",jo_id_code = '{$jo_id_code}'";
        }else{
            $jo_u_common .= ",jo_id_code = '삼진코드중복'";
        }
    }

    $jo_u_sql = " update lt_job_order set $jo_u_common  where jo_id = '{$jo_id}' ";
    sql_query($jo_u_sql);

    $jo_text_sql = "
    UPDATE lt_job_order AS a , (SELECT jo_soje,jo_main_img,jo_codi_img,jo_sub_img,jo_mater_info,jo_sub_mater,jo_maip_price,jo_gakong_item,jo_mater_name,jo_pumjil 
    FROM lt_job_order WHERE jo_id = '{$jo_row['jo_id']}') AS temp
    SET a.jo_main_img = temp.jo_main_img,
     a.jo_codi_img=temp.jo_codi_img,
     a.jo_sub_img = temp.jo_sub_img,
     a.jo_mater_info = temp.jo_mater_info,
     a.jo_sub_mater = temp.jo_sub_mater,
     a.jo_maip_price = temp.jo_maip_price,
     a.jo_gakong_item = temp.jo_gakong_item,
     a.jo_mater_name= temp.jo_mater_name,
     a.jo_pumjil = temp.jo_pumjil
    WHERE a.jo_id = '{$jo_id}'
    ";
    sql_query($jo_text_sql);


    //상품정보집
    $copy_item_info_sql = "select * from lt_prod_info where jo_id = '{$jo_row['jo_id']}'";

    $pi_row_copy_item = sql_fetch($copy_item_info_sql);
    
    $sql_pi_common = "";
    $sql_pi_common .= "ps_id = '{$ps_data_set_result['ps_id']}'";
    $sql_pi_common .= ",pi_size_name = '{$jo_ps_id_update_result['jo_size_code']}'";
    $sql_pi_common .= ",tem_save = 'N'";
    $sql_pi_common .= ",jo_id = '{$jo_id}'";
    $sql_pi_common .= ",pi_model_name = '{$jo_id_code}'";
    $sql_pi_common .= ",pi_model_no = '{$jo_id_code}'";

    $t_pi_detail_info = trans_preg($pi_row_copy_item['pi_detail_info']);
    $t_pi_selling1 = trans_preg($pi_row_copy_item['pi_selling1']);
    $t_pi_selling2 = trans_preg($pi_row_copy_item['pi_selling2']);
    $t_pi_selling3 = trans_preg($pi_row_copy_item['pi_selling3']);
    $t_pi_prod_info1 = trans_preg($pi_row_copy_item['pi_prod_info1']);
    $t_pi_prod_info2 = trans_preg($pi_row_copy_item['pi_prod_info2']);
    $t_pi_prod_info3 = trans_preg($pi_row_copy_item['pi_prod_info3']);
    $t_pi_prod_info4 = trans_preg($pi_row_copy_item['pi_prod_info4']);
    $t_pi_prod_info5 = trans_preg($pi_row_copy_item['pi_prod_info5']);
    $t_pi_prod_info6 = trans_preg($pi_row_copy_item['pi_prod_info6']);
    $t_pi_prod_info7 = trans_preg($pi_row_copy_item['pi_prod_info7']);
    $t_pi_prod_info8 = trans_preg($pi_row_copy_item['pi_prod_info8']);
    $t_pi_prod_info9 = trans_preg($pi_row_copy_item['pi_prod_info9']);
    $t_pi_prod_info10 = trans_preg($pi_row_copy_item['pi_prod_info10']);

    if(!empty($pi_row_copy_item)){
        $sql_pi_common .= " ,pi_sub_category= '{$pi_row_copy_item['pi_sub_category']}',
                    pi_design_style= '{$pi_row_copy_item['pi_design_style']}',
                    pi_design_style_sub= '{$pi_row_copy_item['pi_design_style_sub']}',
                    pi_season= '{$pi_row_copy_item['pi_season']}',
                    pi_it_name= '',
                    pi_it_sub_name= '{$pi_row_copy_item['pi_it_sub_name']}',
                    
                    pi_company_it_id= '{$pi_row_copy_item['pi_company_it_id']}',
                    pi_brand= '{$pi_row_copy_item['pi_brand']}',
                    pi_category= '{$pi_row_copy_item['pi_category']}',
                    pi_mater= '{$pi_row_copy_item['pi_mater']}',
                    pi_prod_date= '{$pi_row_copy_item['pi_prod_date']}',
                    pi_age_gubun= '{$pi_row_copy_item['pi_age_gubun']}',
                    pi_delivery_price= '{$pi_row_copy_item['pi_delivery_price']}',
                    
                    
                    
                    pi_item_soje= '{$pi_row_copy_item['pi_item_soje']}',
                    pi_item_soje_detail= '{$pi_row_copy_item['pi_item_soje_detail']}',
                    pi_color= '{$pi_row_copy_item['pi_color']}',
                    
                    
                    pi_maker= '{$pi_row_copy_item['pi_maker']}',
                    pi_laundry= '{$pi_row_copy_item['pi_laundry']}',
                    pi_kc_safe_yn= '{$pi_row_copy_item['pi_kc_safe_yn']}',
                    pi_soip_yn= '{$pi_row_copy_item['pi_soip_yn']}',
                    
                    
                    pi_charge= '{$pi_row_copy_item['pi_charge']}',
                    
                    pi_charge_mater= '{$pi_row_copy_item['pi_charge_mater']}',
                    pi_charge_mater_etc= '{$pi_row_copy_item['pi_charge_mater_etc']}',
                    pi_charge_brand= '{$pi_row_copy_item['pi_charge_brand']}',
                    pi_charge_brand_etc = '{$pi_row_copy_item['pi_charge_brand_etc']}',
                    pi_charge_weight= '{$pi_row_copy_item['pi_charge_weight']}',
                    pi_charge_weight_etc= '{$pi_row_copy_item['pi_charge_weight_etc']}',

                    pi_ll_style= '{$pi_row_copy_item['pi_ll_style']}',
                    pi_prauden_umu_yn= '{$pi_row_copy_item['pi_prauden_umu_yn']}',
                    pi_hangkun_info= '{$pi_row_copy_item['pi_hangkun_info']}',
                    pi_hangkun_info_txt= '{$pi_row_copy_item['pi_hangkun_info_txt']}',
                    pi_pilpower= '{$pi_row_copy_item['pi_pilpower']}',
                    pi_pilpower_safe_yn= '{$pi_row_copy_item['pi_pilpower_safe_yn']}',
                    pi_info1= '{$pi_row_copy_item['pi_info1']}',
                    pi_info2= '{$pi_row_copy_item['pi_info2']}',
                    pi_info2_1= '{$pi_row_copy_item['pi_info2_1']}',
                    pi_info3= '{$pi_row_copy_item['pi_info3']}',
                    pi_manager= '{$pi_row_copy_item['pi_manager']}',
                    pi_img = '',
                    pi_img_total = '{$pi_row_copy_item['pi_img_total']}',
                    pi_video1= '{$pi_row_copy_item['pi_video1']}',
                    pi_video2= '{$pi_row_copy_item['pi_video2']}',
                    pi_video3= '{$pi_row_copy_item['pi_video3']}',
                    pi_video4= '{$pi_row_copy_item['pi_video4']}',
                    pi_origin_image= '{$pi_row_copy_item['pi_origin_image']}',
                    pi_detail_info= '{$t_pi_detail_info}',
                    pi_selling1= '{$t_pi_selling1}',
                    pi_selling2= '{$t_pi_selling2}',
                    pi_selling3= '{$t_pi_selling3}',
                    pi_prod_info1= '{$t_pi_prod_info1}',
                    pi_prod_info2= '{$t_pi_prod_info2}',
                    pi_prod_info3= '{$t_pi_prod_info3}',
                    pi_prod_info4= '{$t_pi_prod_info4}',
                    pi_prod_info5= '{$t_pi_prod_info5}',
                    pi_prod_info6= '{$t_pi_prod_info6}',
                    pi_prod_info7= '{$t_pi_prod_info7}',
                    pi_prod_info8= '{$t_pi_prod_info8}',
                    pi_prod_info9= '{$t_pi_prod_info9}',
                    pi_prod_info10= '{$t_pi_prod_info10}',
                    etc= '{$pi_row_copy_item['etc']}'
        ";

    }

    $sql_pi = "insert lt_prod_info set $sql_pi_common";
    sql_query($sql_pi);

    $ps_text_sql = "
    UPDATE lt_prod_info AS a , (SELECT pi_laundry,pi_img
    FROM lt_prod_info WHERE jo_id = '{$jo_row['jo_id']}') AS temp
    SET a.pi_laundry = temp.pi_laundry,
     a.pi_img=temp.pi_img
    WHERE a.jo_id = '{$jo_id}'
    ";
    
    sql_query($ps_text_sql);
    
}
//제품기획서는
// $copy_item_proposal_sql = "select * from lt_item_proposal where ip_it_name = '{$origin_name}'";

// $copy_item_proposal_result = sql_fetch($copy_item_proposal_sql);

// if(!empty($copy_item_proposal_result)){
//     $sql_ip_common = "
//         ip_temp = '{$copy_item_proposal_result['ip_temp']}',
//         ip_brand = '{$copy_item_proposal_result['ip_brand']}',
//         ip_it_name = '{$new_name}',
//         ip_reg_date = '{$copy_item_proposal_result['ip_reg_date']}',
//         ip_ipgo_date = '{$copy_item_proposal_result['ip_ipgo_date']}',
//         ip_maker_etc = '{$copy_item_proposal_result['ip_maker_etc']}',
//         ip_maker_country = '{$copy_item_proposal_result['ip_maker_country']}',
//         ip_prod_name = '{$copy_item_proposal_result['ip_prod_name']}',
//         ip_gubun = '{$copy_item_proposal_result['ip_gubun']}',
//         ip_year = '{$copy_item_proposal_result['ip_year']}',
//         ip_season = '{$copy_item_proposal_result['ip_season']}',
//         ip_prod_gubun = '{$copy_item_proposal_result['ip_prod_gubun']}',
//         ip_color = '{$copy_item_proposal_result['ip_color']}',
//         ip_clha_date = '{$copy_item_proposal_result['ip_clha_date']}',
//         ip_item_ipgoer = '{$copy_item_proposal_result['ip_item_ipgoer']}',
//         ip_mater= '{$copy_item_proposal_result['ip_mater']}',
//         ip_maker= '{$copy_item_proposal_result['ip_maker']}',
//         ip_importer= '{$copy_item_proposal_result['ip_importer']}',
//         ip_seller= '{$copy_item_proposal_result['ip_seller']}',
        
        
//         ip_etc= '{$copy_item_proposal_result['ip_etc']}',
        
        
//         ip_performance= '{$copy_item_proposal_result['ip_performance']}',
//         ip_yd_img= '{$copy_item_proposal_result['ip_yd_img']}'
        
//     ";


//     $sql_ip = "insert lt_item_proposal set $sql_ip_common";
//     sql_query($sql_ip);



//     $ip_text_sql = "
//     UPDATE lt_item_proposal AS a , (SELECT ip_job_orders,ip_proposal_memo,ip_mater_purchace,ip_processing,ip_finished,ip_images
//     FROM lt_item_proposal WHERE ip_it_name = '{$origin_name}') AS temp
//     SET 
//     a.ip_proposal_memo = temp.ip_proposal_memo,
//     a.ip_mater_purchace = temp.ip_mater_purchace,
//     a.ip_processing = temp.ip_processing,
//     a.ip_finished = temp.ip_finished,
//     a.ip_images = temp.ip_images
//     WHERE a.ip_it_name = '{$new_name}'
//     ";
    
//     sql_query($ip_text_sql);

// }







// sql_query($sql_ps);
// $ps_id = sql_insert_id();



// $sql = "INSERT INTO lt_item_proposal
// SELECT '', ps_id,ip_temp,ip_brand,ip_it_name,ip_reg_date,ip_ipgo_date,ip_maker_etc,ip_maker_country,ip_prod_name,ip_gubun,ip_year,ip_season,ip_prod_gubun,ip_color,ip_clha_date,ip_item_ipgoer,ip_mater,ip_maker,ip_importer,ip_seller,ip_job_orders,ip_proposal_memo,ip_etc,ip_mater_purchace,ip_processing,ip_finished,ip_performance,ip_yd_img,ip_images
// FROM lt_item_proposal WHERE ip_id = 10";
// sql_query($sql);




 goto_url("./new_goods_process.php?tabs=list&brands=&ipgos=&dpart_ipgos=&shootings=&reorders=&sfl=it_name&stx=&sc_it_time=&limit_list=10");
