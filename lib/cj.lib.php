<?php
if (!defined('_GNUBOARD_')) exit;
include_once(G5_LIB_PATH.'/json.lib.php');

/*************************************************************************
 **
 **  CJ택배 연동 함수 모음
 **
 *************************************************************************/

function cj_oracle_connect() {
    
    $conn = @oci_connect(CJ_ORACLE_USER,CJ_ORACLE_PASSWORD,CJ_ORACLE_SID,'AL32UTF8');
    return $conn;
}

function cj_oracle_close($conn) {
    
    // 오라클에서 로그아웃
    oci_close($conn);
}

function cj_oracle_insert_one($od_id, $act) {
    $conn = cj_oracle_connect();
    $result = cj_oracle_insert($conn, $od_id, $act);
    cj_oracle_close($conn);
    
    return $result;
}

/*
 *정산구분코드(CAL_DV_CD) : '01'(계약운임)
 *운임구분코드(FRT_DV_CD) : '03'(신용)
 *박스타입코드(BOX_TYPE_CD) : '01'(극소), '02'(소), '03'(중), '04'(대), '05'(이형), '06'(취급제한)
 *박스수량(BOX_QTY) : '1' (반드시 1 기재)
 *택배구분(DLV_DV)    : '01' (반드시 01 기재)
 */
function cj_oracle_insert($conn, $od_id, $act) {
    
    global $g5, $default, $config;
    
    $od = sql_fetch(" select * from lt_shop_order where od_id = '".$od_id."' ");
    $cust_use_no = $od['od_id'];
    //$cust_use_no = $cust_use_no."90";
    
    $rcpt_dv = "01"; //접수구분	RCPT_DV	VARCHAR2(2)	01 : 일반,  02 : 반품
    if($act == "반품") {
        $rcpt_dv = "02";
        $cust_use_no = $cust_use_no."02";
    }
    else if($act == "수거")
    {
        $cust_use_no = $cust_use_no."03";
    }
    else if($act == "배송")
    {
        $rcpt_dv = "01";
        $cust_use_no = $cust_use_no."01";
    } else {
        $cust_use_no = $cust_use_no."04";
    }
    
    $od_b_tel = preg_replace('/[^0-9]/', '', $od['od_b_tel']);
    $od_b_hp = preg_replace('/[^0-9]/', '', $od['od_b_hp']);
    
    $od_b_tel1 = $od_b_tel2 = $od_b_tel3 = "";
    $od_b_hp1 = $od_b_hp2 = $od_b_hp3 = "";
    
    if($od_b_tel != ""){
        $od_b_tel1 = substr($od_b_tel, 0, 4);
        $od_b_tel2 = substr($od_b_tel, 4, 4);
        $od_b_tel3 = substr($od_b_tel, 8, 4);
    }
    if($od_b_hp != ""){
        $od_b_hp1 = substr($od_b_hp, 0, 3);
        $od_b_hp2 = substr($od_b_hp, 3, 4);
        $od_b_hp3 = substr($od_b_hp, 7, 4);
        
        if($od_b_tel == ""){
            $od_b_tel1 = substr($od_b_hp, 0, 3);
            $od_b_tel2 = substr($od_b_hp, 3, 4);
            $od_b_tel3 = substr($od_b_hp, 7, 4);
        }
    }
    
    $odsql = " select it_name from lt_shop_cart where od_id = '".$od['od_id']."' and io_type = '0' order by io_type asc, ct_id asc limit 1";
    $opt = sql_fetch($odsql);
    $it_name = $opt['it_name'];
    
    $query = "INSERT INTO V_RCPT_LITANDARD010 (CUST_ID,RCPT_YMD,CUST_USE_NO,RCPT_DV,WORK_DV_CD,REQ_DV_CD,MPCK_KEY,MPCK_SEQ,CAL_DV_CD,FRT_DV_CD,CNTR_ITEM_CD,BOX_TYPE_CD,BOX_QTY,FRT,CUST_MGMT_DLCM_CD
			,SENDR_NM,SENDR_TEL_NO1,SENDR_TEL_NO2,SENDR_TEL_NO3,SENDR_CELL_NO1,SENDR_CELL_NO2,SENDR_CELL_NO3,SENDR_ZIP_NO,SENDR_ADDR,SENDR_DETAIL_ADDR
			,RCVR_NM,RCVR_TEL_NO1,RCVR_TEL_NO2,RCVR_TEL_NO3,RCVR_CELL_NO1,RCVR_CELL_NO2,RCVR_CELL_NO3,RCVR_ZIP_NO,RCVR_ADDR,RCVR_DETAIL_ADDR
			,INVC_NO,ORI_INVC_NO,ORI_ORD_NO,PRT_ST,REMARK_1,REMARK_2,REMARK_3,GDS_NM,DLV_DV,RCPT_ERR_YN,EAI_PRGS_ST,REG_EMP_ID,REG_DTIME,MODI_EMP_ID,MODI_DTIME)
		VALUES
			('".CJ_ORACLE_CUST_ID."','".date('Ymd', G5_SERVER_TIME)."',:cust_use_no,:rcpt_dv,:work_dv_cd,:req_dv_cd,:mpck_key,'1','01','03','01','02','1','0','".CJ_ORACLE_CUST_ID."'
			,:sendr_nm,:sendr_tel_no1,:sendr_tel_no2,:sendr_tel_no3,:sendr_cell_no1,:sendr_cell_no2,:sendr_cell_no3,:sendr_zip_no,:sendr_addr,:sendr_detail_addr
			,:rcvr_nm,:rcvr_tel_no1,:rcvr_tel_no2,:rcvr_tel_no3,:rcvr_cell_no1,:rcvr_cell_no2,:rcvr_cell_no3,:rcvr_zip_no,:rcvr_addr,:rcvr_detail_addr
			,:invc_no,:ori_invc_no,:ori_ord_no,:prt_st,:remark_1,:remark_2,:remark_3,:gds_nm,'01','N','01','".strtoupper(CJ_ORACLE_USER)."',SYSDATE,'".strtoupper(CJ_ORACLE_USER)."',SYSDATE)";
    
    $stmt = oci_parse($conn,$query);
    //:cust_use_no,:rcpt_dv,:work_dv_cd,:req_dv_cd,:mpck_key
    oci_bind_by_name($stmt, ':cust_use_no', $cust_use_no);
    oci_bind_by_name($stmt, ':rcpt_dv', $rcpt_dv);
    
    $work_dv_cd = "01";
    $req_dv_cd= "01";
    oci_bind_by_name($stmt, ':work_dv_cd', $work_dv_cd);
    oci_bind_by_name($stmt, ':req_dv_cd', $req_dv_cd);
    
    $mpck_key = preg_replace("/[^0-9]/", "", G5_TIME_YMD)."_".CJ_ORACLE_CUST_ID."_".$cust_use_no;
    oci_bind_by_name($stmt, ':mpck_key', $mpck_key);
    
    //:sendr_nm,:sendr_tel_no1,:sendr_tel_no2,:sendr_tel_no3,:sendr_zip_no,:sendr_addr,:sendr_detail_addr
    //:rcvr_nm,:rcvr_tel_no1,:rcvr_tel_no2,:rcvr_tel_no3,:rcvr_cell_no1,:rcvr_cell_no2,:rcvr_cell_no3,:rcvr_zip_no,:rcvr_addr,:rcvr_detail_addr
    $empty_string = "";
    $remark_1 = "";
    $remark_2 = "";
    $remark_3 = "";
    
    if($act == "반품" || $act == "수거")
    {
        //송화인 - 고객
        oci_bind_by_name($stmt, ':sendr_nm', $od['od_b_name']);
        oci_bind_by_name($stmt, ':sendr_tel_no1', $od_b_tel1);
        oci_bind_by_name($stmt, ':sendr_tel_no2', $od_b_tel2);
        oci_bind_by_name($stmt, ':sendr_tel_no3', $od_b_tel3);
        oci_bind_by_name($stmt, ':sendr_cell_no1', $od_b_hp1);
        oci_bind_by_name($stmt, ':sendr_cell_no2', $od_b_hp2);
        oci_bind_by_name($stmt, ':sendr_cell_no3', $od_b_hp3);
        
        $sendr_zip_no = $od['od_b_zip1'].$od['od_b_zip2'];
        oci_bind_by_name($stmt, ':sendr_zip_no', $sendr_zip_no);
        oci_bind_by_name($stmt, ':sendr_addr', $od['od_b_addr1']);
        oci_bind_by_name($stmt, ':sendr_detail_addr', $od['od_b_addr2']);
        
        $remark_2 = $od['od_memo'];
        
        //수화인
        if($od['od_type'] == "O" || $od['od_type'] == "R" || $od['od_type'] == "S" ){
            //리탠다드
            $rcvr_tel_no1 = substr($default['de_admin_call_tel'],0,2);
            $rcvr_tel_no2 = substr($default['de_admin_call_tel'],3,4);
            $rcvr_tel_no3 = substr($default['de_admin_call_tel'],8,4);
            
            oci_bind_by_name($stmt, ':rcvr_nm', $default['de_admin_company_name']);
            oci_bind_by_name($stmt, ':rcvr_tel_no1', $rcvr_tel_no1);
            oci_bind_by_name($stmt, ':rcvr_tel_no2', $rcvr_tel_no2);
            oci_bind_by_name($stmt, ':rcvr_tel_no3', $rcvr_tel_no3);
            oci_bind_by_name($stmt, ':rcvr_cell_no1', $empty_string);
            oci_bind_by_name($stmt, ':rcvr_cell_no2', $empty_string);
            oci_bind_by_name($stmt, ':rcvr_cell_no3', $empty_string);
            oci_bind_by_name($stmt, ':rcvr_zip_no', $default['de_return_zip']);
            oci_bind_by_name($stmt, ':rcvr_addr', $default['de_return_address1']);
            oci_bind_by_name($stmt, ':rcvr_detail_addr', $default['de_return_address2']);
            
        }elseif($od['od_type'] == "L" || $od['od_type'] == "K" ){
            //펭귄
            $de_penguin_call_tel = preg_replace('/[^0-9]/', '', $default['de_penguin_call_tel']);
            
            $rcvr_tel_no1 = substr($de_penguin_call_tel,0,3);
            $rcvr_tel_no2 = substr($de_penguin_call_tel,3,4);
            $rcvr_tel_no3 = substr($de_penguin_call_tel,7,4);
            
            oci_bind_by_name($stmt, ':rcvr_nm', $default['de_penguin_company_name']);
            oci_bind_by_name($stmt, ':rcvr_tel_no1', $rcvr_tel_no1);
            oci_bind_by_name($stmt, ':rcvr_tel_no2', $rcvr_tel_no2);
            oci_bind_by_name($stmt, ':rcvr_tel_no3', $rcvr_tel_no3);
            oci_bind_by_name($stmt, ':rcvr_cell_no1', $empty_string);
            oci_bind_by_name($stmt, ':rcvr_cell_no2', $empty_string);
            oci_bind_by_name($stmt, ':rcvr_cell_no3', $empty_string);
            oci_bind_by_name($stmt, ':rcvr_zip_no', $default['de_penguin_zip']);
            oci_bind_by_name($stmt, ':rcvr_addr', $default['de_penguin_address1']);
            oci_bind_by_name($stmt, ':rcvr_detail_addr', $default['de_penguin_address2']);
        }
    } else {
        //송화인 - 리탠다드
        $sendr_tel_no1 = substr($default['de_admin_call_tel'],0,2);
        $sendr_tel_no2 = substr($default['de_admin_call_tel'],3,4);
        $sendr_tel_no3 = substr($default['de_admin_call_tel'],8,4);
        
        oci_bind_by_name($stmt, ':sendr_nm', $default['de_admin_company_name']);
        oci_bind_by_name($stmt, ':sendr_tel_no1', $sendr_tel_no1);
        oci_bind_by_name($stmt, ':sendr_tel_no2', $sendr_tel_no2);
        oci_bind_by_name($stmt, ':sendr_tel_no3', $sendr_tel_no3);
        oci_bind_by_name($stmt, ':sendr_cell_no1', $empty_string);
        oci_bind_by_name($stmt, ':sendr_cell_no2', $empty_string);
        oci_bind_by_name($stmt, ':sendr_cell_no3', $empty_string);
        oci_bind_by_name($stmt, ':sendr_zip_no', $default['de_return_zip']);
        oci_bind_by_name($stmt, ':sendr_addr', $default['de_return_address1']);
        oci_bind_by_name($stmt, ':sendr_detail_addr', $default['de_return_address2']);
        
        //수화인 - 고객
        oci_bind_by_name($stmt, ':rcvr_nm', $od['od_b_name']);
        oci_bind_by_name($stmt, ':rcvr_tel_no1', $od_b_tel1);
        oci_bind_by_name($stmt, ':rcvr_tel_no2', $od_b_tel2);
        oci_bind_by_name($stmt, ':rcvr_tel_no3', $od_b_tel3);
        oci_bind_by_name($stmt, ':rcvr_cell_no1', $od_b_hp1);
        oci_bind_by_name($stmt, ':rcvr_cell_no2', $od_b_hp2);
        oci_bind_by_name($stmt, ':rcvr_cell_no3', $od_b_hp3);
        $rcvr_zip_no = $od['od_b_zip1'].$od['od_b_zip2'];
        oci_bind_by_name($stmt, ':rcvr_zip_no', $rcvr_zip_no);
        oci_bind_by_name($stmt, ':rcvr_addr', $od['od_b_addr1']);
        oci_bind_by_name($stmt, ':rcvr_detail_addr', $od['od_b_addr2']);
        
        $remark_3 = $od['od_memo'];
    }
    
    //:invc_no,:ori_invc_no,:ori_ord_no,:prt_st,:remark_1,:remark_2,:remark_3,:gds_nm
    
    
    $prt_st = "";
    if($act == "반품") {
        //3) 반품 접수 건의 경우, 운송장번호는 CJ대한통운이 채번합니다. INVC_NO 빈 값으로 접수 요청드립니다.
        oci_bind_by_name($stmt, ':invc_no', $empty_string);
        
        oci_bind_by_name($stmt, ':remark_1', $remark_1);
        oci_bind_by_name($stmt, ':remark_2', $remark_2);
        oci_bind_by_name($stmt, ':remark_3', $remark_3);
        
        oci_bind_by_name($stmt, ':ori_invc_no', $od['od_invoice']);
        oci_bind_by_name($stmt, ':ori_ord_no', $od['od_id']);
        $prt_st = "01";
    }
    else {
        $invc = sql_fetch("select concat((max(left(invoice_no,11)) + 1), right(max(left(invoice_no,11)) + 1, 9)%7) as invoice_no from lt_invoice");
        oci_bind_by_name($stmt, ':invc_no', $invc['invoice_no']);
        
        oci_bind_by_name($stmt, ':remark_1', $remark_1);
        oci_bind_by_name($stmt, ':remark_2', $remark_2);
        oci_bind_by_name($stmt, ':remark_3', $remark_3);
        
        oci_bind_by_name($stmt, ':ori_invc_no', $empty_string);
        oci_bind_by_name($stmt, ':ori_ord_no', $empty_string);
        $prt_st = "01";
    }
    
    oci_bind_by_name($stmt, ':prt_st', $prt_st);
    oci_bind_by_name($stmt, ':gds_nm', $it_name);
    
    $result = false;
    $r = @oci_execute($stmt);
    if($r) {
        if($invc['invoice_no'] != ""){
            $sql = " insert into lt_invoice set invoice_no = '".$invc['invoice_no']."', od_id = '{$cust_use_no}', iv_type = '{$act}' ";
            sql_query($sql, true);
        }
        
        oci_free_statement($stmt);
        
        $result = $invc['invoice_no'];
    }
    
    return $result;
}


?>