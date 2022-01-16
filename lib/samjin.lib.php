<?php
if (!defined('_GNUBOARD_')) exit;
include_once(G5_LIB_PATH . '/json.lib.php');

/*************************************************************************
 **
 **  삼진 연동 함수 모음
 **
 *************************************************************************/
function mssql_sql_call($sp_name, $params)
{
    global $g5;

    if (OSWINDOWS) {
        $connectionInfo = array("Database" => SAMJIN_MSSQL_DB, "UID" => SAMJIN_MSSQL_USER, "PWD" => SAMJIN_MSSQL_PASSWORD, "CharacterSet" => "UTF-8");
        $rCon = @sqlsrv_connect(SAMJIN_MSSQL_HOST, $connectionInfo);

        $newparamstr = "";
        $comma = "";
        $newparams = array();
        if (is_array($params)) {
            foreach ($params as $p) {
                $newparams[] = array($p['var'], SQLSRV_PARAM_IN);
                $newparamstr .= $comma . '?';
                $comma = ",";
            }
        }
        // 호출할 프로시져를 초기화 한다.
        $result =  sqlsrv_query($rCon, "{CALL " . $sp_name . "(" . $newparamstr . ")}", $newparams);
        $row = mssql_sql_fetch_array($result);

        $json_params = json_encode($params);
        $json_result = json_encode($row);

        sql_query("INSERT INTO lt_samjin_history (sp_name, params, result, regDate) VALUES ('$sp_name', '$json_params', '$json_result', '" . G5_TIME_YMDHIS . "')", true);

        return $row;
    } else {

        $rCon = @mssql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD);
        mssql_sql_select_db(SAMJIN_MSSQL_DB, $rCon);

        mssql_query('SET ANSI_NULLS ON');
        mssql_query('SET ANSI_WARNINGS ON');

        // 호출할 프로시져를 초기화 한다.
        $stmt =  mssql_init($sp_name, $rCon);
        if (is_array($params)) {
            foreach ($params as $p) {
                mssql_bind($stmt, $p['param_name'], iconv("UTF-8", "EUC-KR", $p['var']), $p['type']);
            }
        }

        // 프로시져를 실행한다.
        $result = mssql_execute($stmt);
        $row = @mssql_fetch_assoc($result);
        $row = array_iconv($row);

        // Statement 메모리를 해제 한다.
        mssql_free_statement($stmt);

        // 접속을 끊는다.
        mssql_close($rCon);

        $json_params = json_encode($params);
        $json_result = json_encode_raw($row);

        sql_query("INSERT INTO lt_samjin_history (sp_name, params, result, regDate) VALUES ('$sp_name', '$json_params', '$json_result', '" . G5_TIME_YMDHIS . "')", true);

        return $row;
    }
}

// row list 리턴용
function mssql_sql_call_assoc($sp_name, $params)
{
    global $g5;

    if (OSWINDOWS) {
        $connectionInfo = array("Database" => SAMJIN_MSSQL_DB, "UID" => SAMJIN_MSSQL_USER, "PWD" => SAMJIN_MSSQL_PASSWORD, "CharacterSet" => "UTF-8");
        $rCon = @sqlsrv_connect(SAMJIN_MSSQL_HOST, $connectionInfo);

        $newparamstr = "";
        $comma = "";
        $newparams = array();
        if (is_array($params)) {
            foreach ($params as $p) {
                $newparams[] = array($p['var'], SQLSRV_PARAM_IN);
                $newparamstr .= $comma . '?';
                $comma = ",";
            }
        }
        // 호출할 프로시져를 초기화 한다.
        $result =  sqlsrv_query($rCon, "{CALL " . $sp_name . "(" . $newparamstr . ")}", $newparams);
        $row = mssql_sql_fetch_array($result);

        $json_params = json_encode($params);
        $json_result = json_encode($row);

        sql_query("INSERT INTO lt_samjin_history (sp_name, params, result, regDate) VALUES ('$sp_name', '$json_params', '$json_result', '" . G5_TIME_YMDHIS . "')", true);

        return $row;
    } else {

        $rCon = @mssql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD);
        mssql_sql_select_db(SAMJIN_MSSQL_DB, $rCon);

        mssql_query('SET ANSI_NULLS ON');
        mssql_query('SET ANSI_WARNINGS ON');

        // 호출할 프로시져를 초기화 한다.
        $stmt =  mssql_init($sp_name, $rCon);
        if (is_array($params)) {
            foreach ($params as $p) {
                mssql_bind($stmt, $p['param_name'], iconv("UTF-8", "EUC-KR", $p['var']), $p['type']);
            }
        }

        // 프로시져를 실행한다.
        $tmp_result = array();
        $result = mssql_execute($stmt);
        while(false != ($row = @mssql_fetch_assoc($result))) {
            $arr_row = array_iconv($row);
            $tmp_result[] = $arr_row;
        }

        // $row = @mssql_fetch_assoc($result);
        // $row = array_iconv($row);

        // Statement 메모리를 해제 한다.
        mssql_free_statement($stmt);

        // 접속을 끊는다.
        mssql_close($rCon);

        $json_params = json_encode($params);
        $json_result = json_encode_raw($tmp_result);

        sql_query("INSERT INTO lt_samjin_history (sp_name, params, result, regDate) VALUES ('$sp_name', '$json_params', '$json_result', '" . G5_TIME_YMDHIS . "')", true);

        return $tmp_result;
    }
}

/**
 *    1. ERP 에서 상품 자료 요청

     프로시저명: SM_TRAN_ORDER_DATA

     파라미터  : 없음

     리턴값    : RSLT            INTEGER        0 - 실행완료            ( 예외 상황은 없습니다 )
                 RSLT_ITEM       VARCHAR(80)    자료가 처리되었습니다.  ( 예외 상황은 없습니다 )

     결과      : 프로시저가 실행되면 S_MALL_ORDERS 에 상품자료가 저장됩니다.
 */
function SM_TRAN_ORDER_DATA()
{
    return mssql_sql_call("SM_TRAN_ORDER_DATA", null);
}
/**
 *    2. ERP 에서 특정상품 재고 요청

     프로시저명: SM_GET_STOCK

     파라미터  : ORDER_NO        CHAR(12)       요청할 품명 - NULL 이면 모든 품명입니다.

     리턴값    : RSLT            INTEGER        0 - 실행완료            ( 예외 상황은 없습니다 )
                 RSLT_ITEM       VARCHAR(80)    자료가 처리되었습니다.  ( 예외 상황은 없습니다 )

     결과      : ORDER_NO 를 NULL 로 호출하면 모든 상품의 재고가 UPDATE 됩니다.
                 특정한 품명을 넣고 호출하면 해당 품명의 모든 색상/사이즈의 재고만 UPDATE 됩니다.

 ** 리팩상품 재고는 제외됩니다. 리팩상품 재고는 S_MALL_REPACK_JAEGO 를 확인하면 됩니다.
 ** EXEC SM_GET_STOCK NULL 로 호출해도 아주 짧은 시간만 소요되므로 무방합니다.
 * @param ORDER_NO        CHAR(12)       요청할 품명 - NULL 이면 모든 품명입니다.
 */
function SM_GET_STOCK($ORDER_NO)
{
    $params = array(
        array("param_name" => "@ORDER_NO", "var" => $ORDER_NO, "type" => SQLVARCHAR)
    );
    return mssql_sql_call("SM_GET_STOCK", $params);
}
// function SM_GET_STOCK3($ORDER_NO)
// {
//     $params = array(
//         array("param_name" => "@ORDER_NO", "var" => $ORDER_NO, "type" => SQLVARCHAR),
//         array("param_name" => "@COLOR", "var" => 'BL', "type" => SQLVARCHAR),
//         array("param_name" => "@HOCHING", "var" => 'Q', "type" => SQLVARCHAR)
//     );
//     // dd($params);
//     return mssql_sql_call_assoc("SM_GET_STOCK_3", $params);
// }


/**
 *    2-1. 삼진에서 에서 특정상품 재고 요청

     프로시저명: NM_GET_STOCK

     파라미터  : 파라미터:   C_NO       SMALLINT         창고번호 - 0 이면 MALL 로 지정된 모든 창고
                                                           번호를 지정하면 해당 창고 ( MALL 로 지정되지 않은 창고는 검색되지 않음  )
                    @CODE      VARCHAR(16)      쇼핑몰 코드 ( 별도 바코드 5 번 )
                    @M1        INTEGER          1: 해당 바코드의 전품목자료  2:해당 바코드의 동일 품목/색상  3:해당 바코드 1 개 자료


     리턴값    : 리턴되는 레코드 ( 색상과 호칭별로 여러개의 레코드가 검색됨 )
                    C_NO       SMALLINT         창고번호
                    CODE       VARCHAR(16)      쇼핑몰 코드
                    ORDER_NO   CHAR(12)         태평양 품명
                    COLOR      CHAR(4)          태평양 색상
                    SZ         SMALLINT         태평양 사이즈 순서
                    HOCHING    CHAR(8)          태평양 호칭
                    STOCK      INTEGER          가용재고량

     결과      : ORDER_NO 를 NULL 로 호출하면 모든 상품의 재고가 UPDATE 됩니다.
                 특정한 품명을 넣고 호출하면 해당 품명의 모든 색상/사이즈의 재고만 UPDATE 됩니다.

 ** 리팩상품 재고는 제외됩니다. 리팩상품 재고는 S_MALL_REPACK_JAEGO 를 확인하면 됩니다.
 ** EXEC SM_GET_STOCK NULL 로 호출해도 아주 짧은 시간만 소요되므로 무방합니다.
 * @param   M1         INTEGER          1-총재고나 가용재고가 1 이상인 것만 집계, 2-입고,출고가 있는 것은 모두 집계
                      M2         INTEGER          1-해당 바코드의 전품목자료  2-해당 바코드의 동일 품목/색상  3-해당 바코드 1 개 자료
                      C_NO       SMALLINT         창고번호 - 0 이면 MALL 로 지정된 모든 창고
                                                             번호를 지정하면 해당 창고 ( MALL 로 지정되지 않은 창고는 검색되지 않음  )
                      @CODE      VARCHAR(16)      쇼핑몰 코드 ( 별도 바코드 5 번 )
 */
function NM_GET_STOCK($M1,$M2,$C_NO,$CODE)
{
    $params = array(
        array("param_name" => "@M1", "var" => $M1, "type" => SQLVARCHAR),array("param_name" => "@M2", "var" => $M2, "type" => SQLVARCHAR),array("param_name" => "@C_NO", "var" => $C_NO, "type" => SQLVARCHAR),array("param_name" => "@CODE", "var" => $CODE, "type" => SQLVARCHAR)
    );
    return mssql_sql_call_assoc("NM_GET_STOCK", $params);
}


// ver 2.0 쇼핑몰 관리 전산화
// -쇼핑몰코드 바코드 등록/ 삭제 NM_ADD_BARCODE / NM_DEL_BARCODE
// -쇼핑몰코드 재고 조회  NM_GET_STOCK / NM_GET_STOCK_2
// -삼진품명 색상 호칭 재고조회 NM_GET_STOCK_WITH_ORDER_NO
// -SAP 코드 색상 호칭 재고 조회 NM_GET_STOCK_WITH_SAP_CODE
function NM_GET_STOCK_2($M1,$C_NO,$CODE01,$CODE02,$CODE03,$CODE04,$CODE05,$CODE06,$CODE07,$CODE08,$CODE09,$CODE10){
    $params = array(
        array("param_name" => "@M1", "var" => $M1, "type" => SQLVARCHAR)
        ,array("param_name" => "@C_NO", "var" => $C_NO, "type" => SQLVARCHAR)
        ,array("param_name" => "@CODE01", "var" => $CODE01, "type" => SQLVARCHAR),array("param_name" => "@CODE02", "var" => $CODE02, "type" => SQLVARCHAR)
        ,array("param_name" => "@CODE03", "var" => $CODE03, "type" => SQLVARCHAR),array("param_name" => "@CODE04", "var" => $CODE04, "type" => SQLVARCHAR)
        ,array("param_name" => "@CODE05", "var" => $CODE05, "type" => SQLVARCHAR),array("param_name" => "@CODE06", "var" => $CODE06, "type" => SQLVARCHAR)
        ,array("param_name" => "@CODE07", "var" => $CODE07, "type" => SQLVARCHAR),array("param_name" => "@CODE08", "var" => $CODE08, "type" => SQLVARCHAR)
        ,array("param_name" => "@CODE09", "var" => $CODE09, "type" => SQLVARCHAR),array("param_name" => "@CODE10", "var" => $CODE10, "type" => SQLVARCHAR)
    );
    return mssql_sql_call_assoc("NM_GET_STOCK_2", $params);
}

function NM_GET_STOCK_WITH_ORDER_NO($M1,$C_NO, $ORDER_NO , $COLOR, $HOCHING){
    // 조건에 따라서 색상/사이즈 별로 태평양 품명/색상/사이즈 자료와 재고가 집계됨
    // C_NO       SMALLINT         창고번호 - 0 이면 MALL 로 지정된 모든 창고
    // 번호를 지정하면 해당 창고 ( MALL 로 지정되지 않은 창고는 검색되지 않음  )
    // @ORDER_NO  CHAR(12)         품 명 - 유효한 품명이 필수로 입력되어야 함
    // @COLOR     CHAR(4)          색 상 - NULL 이 입력되면 해당 품목의 모든 색상이 검색됨
    // @HOCHING   CHAR(8)          호 칭 - NULL 이 입력되면 해당 품목의 모든 호칭이 검색됨
    $params = array(
        array("param_name" => "@M1", "var" => $M1, "type" => SQLVARCHAR)
        ,array("param_name" => "@C_NO", "var" => $C_NO, "type" => SQLVARCHAR)
        ,array("param_name" => "@ORDER_NO", "var" => $ORDER_NO, "type" => SQLVARCHAR)
        ,array("param_name" => "@COLOR", "var" => $COLOR, "type" => SQLVARCHAR)
        ,array("param_name" => "@HOCHING", "var" => $HOCHING, "type" => SQLVARCHAR)
    );
    return mssql_sql_call_assoc("NM_GET_STOCK_WITH_ORDER_NO", $params);
}
function NM_GET_STOCK_WITH_SAP_CODE($M1, $C_NO, $SAP_CODE , $COLOR, $HOCHING){
    // 조건에 따라서 색상/사이즈 별로 태평양 품명/색상/사이즈 자료와 재고가 집계됨
    // C_NO       SMALLINT         창고번호 - 0 이면 MALL 로 지정된 모든 창고
    //번호를 지정하면 해당 창고 ( MALL 로 지정되지 않은 창고는 검색되지 않음  )
    //@SAP_CODE  CHAR(12)         SAP CODE - 유효한 SAP CODE 가 필수로 입력되어야 함
    //@COLOR     CHAR(4)          색 상 - NULL 이 입력되면 해당 품목의 모든 색상이 검색됨
    //@HOCHING   CHAR(8)          호 칭 - NULL 이 입력되면 해당 품목의 모든 호칭이 검색됨
    $params = array(
        array("param_name" => "@M1", "var" => $M1, "type" => SQLVARCHAR)
        ,array("param_name" => "@C_NO", "var" => $C_NO, "type" => SQLVARCHAR)
        ,array("param_name" => "@SAP_CODE", "var" => $SAP_CODE, "type" => SQLVARCHAR)
        ,array("param_name" => "@COLOR", "var" => $COLOR, "type" => SQLVARCHAR)
        ,array("param_name" => "@HOCHING", "var" => $HOCHING, "type" => SQLVARCHAR)
    );
    return mssql_sql_call_assoc("NM_GET_STOCK_WITH_SAP_CODE", $params);
}
function NM_ADD_BARCODE($BARCODE,$ORDER_NO,$COLOR, $HOCHING){
    // 쇼핑몰에서 사용할 코드를 입력함
    // 입력되는 @BARCODE (쇼핑몰 코드) 가 기존에 등록되어 있는 것이면 등록되지 않음 ( 에러코드 리턴 )
    // 입력되는 품명/색상/호칭이 기존에 등록되어 있는 것이면 새로운 쇼핑몰 코드로 변경됨
    // 품명/색상/호칭은 작업지시서에 등록되어 있는 자료와 일치해야 함.
    // @BARCODE   VARCHAR(23)      쇼핑몰 코드
    // @ORDER_NO  CHAR(12)         품 명        - 작업지시서에 등록되어 있는 자료와 일치해야 함.
    // @COLOR     CHAR(4)          색 상        - 작업지시서에 등록되어 있는 자료와 일치해야 함.
    // @HOCHING   CHAR(8)          호 칭        - 작업지시서에 등록되어 있는 자료와 일치해야 함.
    $params = array(
        array("param_name" => "@BARCODE", "var" => $BARCODE, "type" => SQLVARCHAR)
        ,array("param_name" => "@ORDER_NO", "var" => $ORDER_NO, "type" => SQLVARCHAR)
        ,array("param_name" => "@COLOR", "var" => $COLOR, "type" => SQLVARCHAR)
        ,array("param_name" => "@HOCHING", "var" => $HOCHING, "type" => SQLVARCHAR)
    );
    return mssql_sql_call_assoc("NM_ADD_BARCODE", $params);
}
function NM_DEL_BARCODE($BARCODE){
    // 쇼핑몰 코드로 등록된 자료를 삭제함
    //5 번에 등록된 바코드만 삭제됨

    //파라미터:   @BARCODE   VARCHAR(23)      쇼핑몰 코드

    $params = array(
        array("param_name" => "@BARCODE", "var" => $BARCODE, "type" => SQLVARCHAR)  
    );
    return mssql_sql_call_assoc("NM_DEL_BARCODE", $params);
}

//2021-03 삼진 추가 개발 / 매장 재고/ 판매데이터
function NM_REP_SALE_SUM($D1,$D2,$H1,$G11,$G12,$G21,$G22,$G31,$G32,$G41,$G42,$G51,$G52,$G61,$G62,$G71,$G72,$G81,$G82){
    /*
    지정하는 기간의 전매장 판매 집계

    파라미터:   D1 CHAR(10)      집계 시작일자 - YYYY-MM-DD 의 형식으로 호출함
                D2 CHAR(10)      집계 종료일자 - YYYY-MM-DD 의 형식으로 호출함
                H1 INTEGER       상품 지정방법 - 아래에 별도로 설명함
                G11 VARCHAR(30)
                G12 VARCHAR(30)
                G21 VARCHAR(30)
                G22 VARCHAR(30)
                G31 VARCHAR(30)
                G32 VARCHAR(30)
                G41 VARCHAR(30)
                G42 VARCHAR(30)
                G51 VARCHAR(30)
                G52 VARCHAR(30)
                G61 VARCHAR(30)
                G62 VARCHAR(30)
                G71 VARCHAR(30)
                G72 VARCHAR(30)
                G81 VARCHAR(30)
                G82 VARCHAR(30)

    리턴되는 레코드
            ORDER_NO     CHAR(12)       품명
            SAP_CODE     CHAR(12)       SAP 코드
            REM          VARCHAR(56)    상품내역
            COLOR        CHAR(4)        색상
            COLOR_NAME   VARCHAR(12)    색상명
            SIZE         SMALLINT       사이즈 순서
            HOCHING      CHAR(8)        호칭
            SALE_RATE    SMALLINT       할인율
            MARGIN       DECIMAL(10,3)  마진                    
            QTY          INTEGER        판매량
            PRICE2       INTEGER        실판매 단가
            G_AMOUNT     DECIMAL(18)    매가금액
            N_AMOUNT     DECIMAL(18)    원가금액
            W_AMOUNT     DECIMAL(18)    생산원가 금액
            DISCOUNT     DECIMAL(18)    에누리
            O_PRICE      INTEGER        판매정상가
            O_AMOUNT     DECIMAL(18)    정상가 금액
    */
    $params = array(
        array("param_name" => "@D1", "var" => $D1, "type" => SQLVARCHAR),array("param_name" => "@D2", "var" => $D2, "type" => SQLVARCHAR)
        ,array("param_name" => "@H1", "var" => $H1, "type" => SQLVARCHAR)
        ,array("param_name" => "@G11", "var" => $G11, "type" => SQLVARCHAR),array("param_name" => "@G12", "var" => $G12, "type" => SQLVARCHAR)
        ,array("param_name" => "@G21", "var" => $G21, "type" => SQLVARCHAR),array("param_name" => "@G22", "var" => $G22, "type" => SQLVARCHAR)
        ,array("param_name" => "@G31", "var" => $G31, "type" => SQLVARCHAR),array("param_name" => "@G32", "var" => $G32, "type" => SQLVARCHAR)
        ,array("param_name" => "@G41", "var" => $G41, "type" => SQLVARCHAR),array("param_name" => "@G42", "var" => $G42, "type" => SQLVARCHAR)
        ,array("param_name" => "@G51", "var" => $G51, "type" => SQLVARCHAR),array("param_name" => "@G52", "var" => $G52, "type" => SQLVARCHAR)
        ,array("param_name" => "@G61", "var" => $G61, "type" => SQLVARCHAR),array("param_name" => "@G62", "var" => $G62, "type" => SQLVARCHAR)
        ,array("param_name" => "@G71", "var" => $G71, "type" => SQLVARCHAR),array("param_name" => "@G72", "var" => $G72, "type" => SQLVARCHAR)
        ,array("param_name" => "@G81", "var" => $G81, "type" => SQLVARCHAR),array("param_name" => "@G82", "var" => $G82, "type" => SQLVARCHAR)
    );
    return mssql_sql_call_assoc("NM_REP_SALE_SUM", $params);
}
function NM_REP_STOCK($M1,$H1,$G11,$G12,$G21,$G22,$G31,$G32,$G41,$G42,$G51,$G52,$G61,$G62,$G71,$G72,$G81,$G82){
    /*
    전매장 재고 집계

    파라미터:   M1 INTEGER          1-총재고나 가용재고가 1 이상인 것만 집계, 2-입고,출고가 있는 것은 모두 집계
                H1 INTEGER          상품 지정방법 - 아래에 별도로 설명함
                G11 VARCHAR(30)
                G12 VARCHAR(30)
                G21 VARCHAR(30)
                G22 VARCHAR(30)
                G31 VARCHAR(30)
                G32 VARCHAR(30)
                G41 VARCHAR(30)
                G42 VARCHAR(30)
                G51 VARCHAR(30)
                G52 VARCHAR(30)
                G61 VARCHAR(30)
                G62 VARCHAR(30)
                G71 VARCHAR(30)
                G72 VARCHAR(30)
                G81 VARCHAR(30)
                G82 VARCHAR(30)

    리턴되는 레코드
            ORDER_NO     CHAR(12)       품명
            SAP_CODE     CHAR(12)       SAP 코드
            REM          VARCHAR(56)    상품내역
            COLOR        CHAR(4)        색상
            COLOR_NAME   VARCHAR(12)    색상명
            Q1         INTEGER          입고량
            Q2         INTEGER          출고량
            Q3         INTEGER          반품량
            Q4         INTEGER          조정량
            Q5         INTEGER          예약량
            Q6         INTEGER          미착량
            STOCK1     INTEGER          총재고량
            STOCK2     INTEGER          가용재고량
    */
    $params = array(
        array("param_name" => "@M1", "var" => $M1, "type" => SQLVARCHAR)
        ,array("param_name" => "@H1", "var" => $H1, "type" => SQLVARCHAR)
        ,array("param_name" => "@G11", "var" => $G11, "type" => SQLVARCHAR),array("param_name" => "@G12", "var" => $G12, "type" => SQLVARCHAR)
        ,array("param_name" => "@G21", "var" => $G21, "type" => SQLVARCHAR),array("param_name" => "@G22", "var" => $G22, "type" => SQLVARCHAR)
        ,array("param_name" => "@G31", "var" => $G31, "type" => SQLVARCHAR),array("param_name" => "@G32", "var" => $G32, "type" => SQLVARCHAR)
        ,array("param_name" => "@G41", "var" => $G41, "type" => SQLVARCHAR),array("param_name" => "@G42", "var" => $G42, "type" => SQLVARCHAR)
        ,array("param_name" => "@G51", "var" => $G51, "type" => SQLVARCHAR),array("param_name" => "@G52", "var" => $G52, "type" => SQLVARCHAR)
        ,array("param_name" => "@G61", "var" => $G61, "type" => SQLVARCHAR),array("param_name" => "@G62", "var" => $G62, "type" => SQLVARCHAR)
        ,array("param_name" => "@G71", "var" => $G71, "type" => SQLVARCHAR),array("param_name" => "@G72", "var" => $G72, "type" => SQLVARCHAR)
        ,array("param_name" => "@G81", "var" => $G81, "type" => SQLVARCHAR),array("param_name" => "@G82", "var" => $G82, "type" => SQLVARCHAR)
    );
    return mssql_sql_call_assoc("NM_REP_STOCK", $params);
}


/**
 * 상품 지정방법

         H1 - 1 : 전상품

                  G11,G12,G21,G22,G31,G31,G41,G42,G51,G52,G61,G62,G71,G72,G81,G82 는
                  전부 NULL 로 지정합니다.

                  EX: 1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL

              2 : 상품 범위로 지정

                  G11-G12, G21-G22, G31-G32, G41-G42, G51-G52, G61-G62, G71-G72, G81-G92 의
                  범위로 지정된 상품을 집계합니다. 범위로 지정하지 않는 파라미터는 NULL 로 합니다.

                  EX: 2,'A','C','K','L',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL
                      ** 'A' 에서 'C' 사이의 상품과 'K' 에서 'L' 사이의 상품을 집계합니다.

              3. 와일드 문자로 검색

                 와일드 문자 종류:  '%'       - 문자가 0 개 이상인 문자열
                                    '_'       - 단일 문자
                                    [A-D]     - 지정된 범위 A 에서 D 사이의 단일 문자
                                    [ABCDEF]  - A,B,C,D,E,F 에 속하는 단일 문자
                                    [A-CF]    - A,B,C,F 에 속하는 단일문자
                                    [^A-D]    - 지정된 범위 A 에서 D 사이에 속하지 않는 단일 문자
                                    [^ABCDEF] - A,B,C,D,E,F 에 속하지 않는 단일 문자
                                    와일드문자로 사용하는 '%','_' 을 실제 문자로 사용하려면 [ ] 로 감싸 줍니다.
                                    5[%]%     - 5% 로 시작하는 모든 문자열
                                    5[_]%     - 5_ 로 시작하는 모든 문자열

                 EX: 3,'M1%','_K%','M[3-5]%',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL

                     ** M1 으로 시작하는 모든 상품 +
                        두번째 자리가 K 인 모든 상품 +
                        M 으로 시작하고 두번째 자리가 3,4,5 인 모든 상품을 집계합니다.
 * 
 * 
 */

/**
 * 3. ERP 로 고객 자료 전송

     프로시서명: SM_SEND_CUST_DATA

     파라미터  : CUST_ID         VARCHAR(20)    쇼핑몰에서 사용하는 고객 ID - 중복이 허용되지 않은 고유 KEY  ** 필수항목
                 NAME            VARCHAR(40)    고객성명                                                     ** 필수항목
                 H_PHONE         VARCHAR(25)    핸드폰    XXX-XXXX-XXXX 의 형태로 전송함                     ** 필수항목
                 PHONE           VARCHAR(25)    전화번호  XXX-XXXX-XXXX 의 형태로 전송함
                 ZIP             VARCHAR(7)     우편번호  구 우편번호 XXX-XXX 또는 5 자리 우편번호 XXXXX
                 ADR1            VARCHAR(120)   주소 1                                                       ** 필수항목
                 ADR2            VARCHAR(120)   주소 2

     리턴값    : RSLT_CODE       INTEGER        결과코드
                 RSLT_ITEM       VARCHAR(80)    위 코드에 대한 내역

     결과      : 전송되어 ERP 에 저장된 고객자료는 S_MALL_CUST 에 저장됩니다.
                 이후 ERP 와 매출자료 전송시 고객은 쇼핑몰의 ID 를 통해서 연동하게 됩니다.
 * @param mb_id
 */
function SM_SEND_CUST_DATA($mb)
{
    $params = array(
        array("param_name" => "@ID", "var" => $mb['mb_id'], "type" => SQLVARCHAR), array("param_name" => "@NAME", "var" => $mb['mb_name'], "type" => SQLVARCHAR), array("param_name" => "@H_PHONE", "var" => $mb['mb_hp'], "type" => SQLVARCHAR), array("param_name" => "@PHONE", "var" => $mb['mb_tel'], "type" => SQLVARCHAR), array("param_name" => "@ZIP", "var" => $mb['mb_zip1'] . $mb['mb_zip2'], "type" => SQLVARCHAR), array("param_name" => "@ADR1", "var" => $mb['mb_addr1'], "type" => SQLVARCHAR), array("param_name" => "@ADR2", "var" => $mb['mb_addr2'], "type" => SQLVARCHAR)
    );
    return mssql_sql_call("SM_SEND_CUST_DATA", $params);
}

/*
 * 배송지 정보로 삼진 회원정보 변경
 */
function SM_SEND_CUST_DATA_OD($od)
{
    $params = array(
        array("param_name" => "@ID", "var" => $od['mb_id'], "type" => SQLVARCHAR), array("param_name" => "@NAME", "var" => $od['od_b_name'], "type" => SQLVARCHAR), array("param_name" => "@H_PHONE", "var" => $od['od_b_hp'], "type" => SQLVARCHAR), array("param_name" => "@PHONE", "var" => $od['od_b_tel'], "type" => SQLVARCHAR), array("param_name" => "@ZIP", "var" => $od['od_b_zip1'] . $od['od_b_zip2'], "type" => SQLVARCHAR), array("param_name" => "@ADR1", "var" => $od['od_b_addr1'], "type" => SQLVARCHAR), array("param_name" => "@ADR2", "var" => $od['od_b_addr2'], "type" => SQLVARCHAR)
    );
    return mssql_sql_call("SM_SEND_CUST_DATA", $params);
}

function SM_FINISH_RENTAL_DATA($od_id, $current_status)
{
    global $g5, $default;
    $r = array();
    $od = sql_fetch(" select * from lt_shop_order where od_id = '$od_id' ");
    $od_type = $od['od_type'];
    //$od_type = "R"; - 삼진연동 R은 주문번호 에러발생
    SM_SEND_CUST_DATA_OD($od); //배송정보 전달을 위한 배송지정보로 회원정보 갱신.


    $ctsql = " select  a.od_type, a.od_id, a.od_sub_id, a.rf_serial, a.io_sapcode_color_gz, a.ct_price, a.io_price,a.ct_rental_price
                                ,a.io_order_no, a.io_color_name, a.io_hoching
                                ,a.ct_laundry_price, a.ct_free_laundry, a.ct_zbox_price, a.ct_free_laundry_delivery_price
                        from    lt_shop_order_item as a
                                inner join lt_shop_cart as c
                                  on a.ct_id = c.ct_id
                        where a.od_id = '{$od_id}'
                         and  c.ct_status = '{$current_status}'
                         and  a.io_sapcode_color_gz != ''
                        order by od_sub_id asc
                        ";
    $ctr = sql_query($ctsql);

    //$DELIVERY_CHARGE = $default['de_send_cost_list']; //기본배송비
    $DELIVERY_CHARGE = get_samjin_sendcost($od_id, $current_status);
    $WASHING_CHARGE = 0;
    $BOX_CHARGE = 0;
    $CLEANING_FREE_COUNT = 0;
    for ($k = 0; $ct = sql_fetch_array($ctr); $k++) {

        $SM_SERIAL = $od_type . '-' . substr($ct['od_id'], 0, 8) . '-' . substr($ct['od_id'], 8, 6) . '-' . $ct['od_sub_id'];
        $AMOUNT = (int) $ct['ct_rental_price'] + (int) $ct['io_price'];
        //$DELIVERY_CHARGE = $DELIVERY_CHARGE + (int)$ct['ct_free_laundry_delivery_price']; //배송비
        $WASHING_CHARGE = $WASHING_CHARGE + (int) $ct['ct_laundry_price']; //유료세탁비
        $BOX_CHARGE = $BOX_CHARGE + (int) $ct['ct_zbox_price']; //박스비
        $CLEANING_FREE_COUNT = (int) $ct['ct_free_laundry']; //무료세탁횟수

        $params = array(
            array("param_name" => "@SM_SERIAL", "var" => $SM_SERIAL, "type" => SQLVARCHAR), array("param_name" => "@ORDER_NO", "var" => $ct['io_order_no'], "type" => SQLVARCHAR), array("param_name" => "@COLOR", "var" => $ct['io_color_name'], "type" => SQLVARCHAR), array("param_name" => "@HOCHING", "var" => $ct['io_hoching'], "type" => SQLVARCHAR), array("param_name" => "@RENT_PER_MON", "var" => $AMOUNT, "type" => SQLINT4)
        );

        $stmt =  mssql_sql_call("SM_ADD_RENTAL_DATA", $params);
        /*

        프로시저명: SM_ADD_RENTAL_DATA
        파라미터  : SM_SERIAL       VARCHAR(22)    쇼핑몰 주문번호 - 일련번호 포함 22 자리 전송함
        ORDER_NO        CHAR(12)       품명
        COLOR           CHAR(4)        색상
        HOCHING         CHAR(8)        호칭
        RENT_PER_MON    INTEGER        월리스료
        리턴값    : RSLT_CODE       INTEGER        결과코드
        RSLT_ITEM       VARCHAR(80)    위 코드에 대한 내역
        */
    }

    $SM_SERIAL = $od_type . '-' . substr($od['od_id'], 0, 8) . '-' . substr($od['od_id'], 8, 6);
    $ISSUE_DATE = substr($od['od_time'], 0, 10);
    $COUPON_AMOUNT =  (int) $od['od_cart_coupon'];
    $POINT =  (int) $od['od_receipt_point'];
    $PG_CHARGE = pg_charge_calc($od['od_settle_case'], (int) $od['rt_rental_price']); //수수료 계산 1회
    $I_DAY = $od['rt_billday'];
    $CUST_ID = $od['mb_id'];
    $DEF_MON = $od['rt_month'];

    $params = array(
        array("param_name" => "@SM_SERIAL", "var" => $SM_SERIAL, "type" => SQLVARCHAR), array("param_name" => "@ISSUE_DATE", "var" => $ISSUE_DATE, "type" => SQLVARCHAR), array("param_name" => "@COUPON_AMOUNT", "var" => $COUPON_AMOUNT, "type" => SQLINT4), array("param_name" => "@POINT", "var" => $POINT, "type" => SQLINT4), array("param_name" => "@ETC_AMOUNT", "var" => 0, "type" => SQLINT4), array("param_name" => "@DELIVERY_CHARGE", "var" => $DELIVERY_CHARGE, "type" => SQLINT4), array("param_name" => "@WASHING_CHARGE", "var" => $WASHING_CHARGE, "type" => SQLINT4), array("param_name" => "@BOX_CHARGE", "var" => $BOX_CHARGE, "type" => SQLINT4), array("param_name" => "@PG_CHARGE", "var" => $PG_CHARGE, "type" => SQLINT4), array("param_name" => "@I_DAY", "var" => $I_DAY, "type" => SQLINT2), array("param_name" => "@CUST_ID", "var" => $CUST_ID, "type" => SQLVARCHAR), array("param_name" => "@DEF_MON", "var" => $DEF_MON, "type" => SQLINT2), array("param_name" => "@CLEANING_FREE_COUNT", "var" => $CLEANING_FREE_COUNT, "type" => SQLINT2)
    );

    $stmt = mssql_sql_call("SM_FINISH_RENTAL_DATA", $params);

    $od = sql_query("update lt_shop_order set od_samjin_chk = '{$stmt['RSLT_CODE']}' where od_id = '$od_id' ");

    return $stmt;
    //return $r;
    /*
    2. 개별 리스자료를 전송한뒤 마스터자료 전송

     프로시저명: SM_FINISH_RENTAL_DATA

     파라미터  : SM_SERIAL       VARCHAR(17)    쇼핑몰 주문번호 - 마스터 17 자리만 전송함
                 ISSUE_DATE      VARCHAR(10)    발생일자 YYYY-MM-DD 의 형식으로 전송함
                 COUPON_AMOUNT   INTEGER        쿠폰 할인금액
                 POINT           INTEGER        포인트 사용금액
                 ETC_AMOUNT      INTEGER        기타 프로모션 금액
                 DELIVERY_CHARGE INTEGER        1 회 배송비
                 WASHING_CHARGE  INTEGER        1 회 세탁비
                 BOX_CHARGE      INTEGER        1 회 박스비
                 PG_CHARGE       INTEGER        1 회 PG 수수료
                 I_DAY           SMALLINT       매월 입금일자
                 CUST_ID         VARCHAR(20)    고객코드
                 DEF_MON         SMALLINT       리스시 약정 개월수 ( 현재에는 보내지 않아도 자동처리, 차후 리탠다드 60개월 등등의 변수 대비해 별도 처리를 위해 추가됨 )
                 CLEANING_FREE_COUNT  SMALLINT  리스시 무상 세탁횟수 ( 현재에는 보내지 않아도 자동처리, 차후 리탠다드 변수 대비해 별도 처리를 위해 추가됨 )



                 DELIVERY_CHARGE INTEGER        총배송비 80000원 (1회당 배송비 8000원: 최초배송 1회 + 세탁 3회 * 3번 )
                                                ** 무료세탁시는 1회당 박스배송,고객발송,완료후배송의 3회가 필요하므로 1회당 3 번의 배송비:24000원이 소요됨
                 WASHING_CHARGE  INTEGER        총세탁비 106500원 (35500원 3회)
                 BOX_CHARGE      INTEGER        BOX 비   13800 원 (4600원  3회)
                 PG_CHARGE       INTEGER        총 PG 수수료 (36회분)
                 I_DAY           SMALLINT       매월 입금일자
                 CUST_ID         VARCHAR(20)    고객코드

     리턴값    : RSLT_CODE       INTEGER        결과코드
                 RSLT_ITEM       VARCHAR(80)    위 코드에 대한 내역

     결과      : 위의 SM_ADD_RENTAL_DATA 로 전송된 자료들이 유효한 자료로 전송됩니다.

     *** 3 개의 상품이 한 번에 리스되었을때는 상품별로 SM_ADD_RENTAL_DATA 를 세번 호출한다음
         SM_FINISH_RENTAL_DATA 를 호출해야 합니다.
     */
}

function SM_ADD_RENTAL_IBKEUM($od_id)
{
    global $g5;

    $od = sql_fetch(" select * from lt_shop_order where od_id = '$od_id' ");
    //$od_type = "R";
    $od_type = $od['od_type'];
    $ISSUE_DATE = G5_TIME_YMD;
    $SM_SERIAL = $od_type . '-' . substr($od['od_id'], 0, 8) . '-' . substr($od['od_id'], 8, 6);
    $AMOUNT = (int) $od['rt_rental_price'];
    $PG_CHARGE = pg_charge_calc($od['od_settle_case'], $AMOUNT); //수수료 계산
    $MON = (int) $od['rt_payment_count'];

    $params = array(
        array("param_name" => "@ISSUE_DATE", "var" => $ISSUE_DATE, "type" => SQLVARCHAR), array("param_name" => "@SM_SERIAL", "var" => $SM_SERIAL, "type" => SQLVARCHAR), array("param_name" => "@MON", "var" => $MON, "type" => SQLINT2), array("param_name" => "@PG_CHARGE", "var" => $PG_CHARGE, "type" => SQLINT4)
    );
    $result =  mssql_sql_call("SM_ADD_RENTAL_IBKEUM", $params);

    return $result;
    /* (입금처리 완료 후 전달)
     *    3. 리스료 입금 자료 전송

     프로시저명: SM_ADD_RENTAL_IBKEUM

     파라미터  : ISSUE_DATE      VARCHAR(10)    발생일자 YYYY-MM-DD 의 형식으로 전송함
                 SM_SERIAL       VARCHAR(17)    쇼핑몰 주문번호 - 마스터 17 자리만 전송함
                 MON             SMALLINT       불입개월수
                 PG_CHARGE       INTEGER        발생한 PG 수수료

     리턴값    : RSLT_CODE       INTEGER        결과코드
                 RSLT_ITEM       VARCHAR(80)    위 코드에 대한 내역

     결과      : 월리스료 입금이 처리됨.
     */
}

function SM_ADD_RENTAL_CANCEL($od_id, $RSP)
{
    global $g5;

    $od = sql_fetch(" select * from lt_shop_order where od_id = '$od_id' ");
    //$od_type = "R";
    $od_type = $od['od_type'];

    $ISSUE_DATE = G5_TIME_YMD;
    $SM_SERIAL = $od_type . '-' . substr($od['od_id'], 0, 8) . '-' . substr($od['od_id'], 8, 6);

    $params = array(
        array("param_name" => "@ISSUE_DATE", "var" => $ISSUE_DATE, "type" => SQLVARCHAR), array("param_name" => "@SM_SERIAL", "var" => $SM_SERIAL, "type" => SQLVARCHAR), array("param_name" => "@RSP", "var" => $RSP, "type" => SQLINT2)
    );
    $result =  mssql_sql_call("SM_ADD_RENTAL_CANCEL", $params);
    $od = sql_query("update lt_shop_order set od_samjin_chk = '{$stmt['RSLT_CODE']}' where od_id = '$od_id' ");

    return $result;
    /*
     *    4. 리스 취소 - 고객이 취소했을 때

     프로시저명: SM_ADD_RENTAL_CANCEL

     파라미터  : ISSUE_DATE      VARCHAR(10)    발생일자 YYYY-MM-DD 의 형식으로 전송함
                 SM_SERIAL       VARCHAR(17)    쇼핑몰 주문번호 - 마스터 17 자리만 전송함
                 RSP             SMALLINT       취소시 귀책구분  1-리탠다드 귀책  2-고객귀책

     리턴값    : RSLT_CODE       INTEGER        결과코드
                 RSLT_ITEM       VARCHAR(80)    위 코드에 대한 내역

     결과      : 리스계약이 취소됩니다.
                 S_MALL_SALE_MAIN_MASTER 를 확인해서 SALE_DATE 가 기록되었으면 실물 회수후 호출해야 합니다.
     */
}

function SM_ADD_RENTAL_RETURN($od_id)
{
    global $g5;
    //$od_type = "R";
    $od = sql_fetch(" select * from lt_shop_order where od_id = '$od_id' ");
    $od_type = $od['od_type'];

    SM_SEND_CUST_DATA_OD($od); //배송정보 전달을 위한 배송지정보로 회원정보 갱신.

    $ISSUE_DATE = G5_TIME_YMD;
    $SM_SERIAL = $od_type . '-' . substr($od['od_id'], 0, 8) . '-' . substr($od['od_id'], 8, 6);
    $MON = (int) $od['rt_payment_count'];
    $PENALTY = (int) $od['od_penalty'];
    $PG_CHARGE = pg_charge_calc($od['od_settle_case'], $PENALTY); //수수료 계산

    $params = array(
        array("param_name" => "@ISSUE_DATE", "var" => $ISSUE_DATE, "type" => SQLVARCHAR), array("param_name" => "@SM_SERIAL", "var" => $SM_SERIAL, "type" => SQLVARCHAR), array("param_name" => "@MON", "var" => $MON, "type" => SQLINT2), array("param_name" => "@PENALTY", "var" => $PENALTY, "type" => SQLINT4), array("param_name" => "@PG_CHARGE", "var" => $PG_CHARGE, "type" => SQLINT4)
    );
    $result =  mssql_sql_call("SM_ADD_RENTAL_RETURN", $params);
    $od = sql_query("update lt_shop_order set od_samjin_chk = '{$stmt['RSLT_CODE']}' where od_id = '$od_id' ");

    return $result;
    /*
     *    5. 리스상품의 회수

     프로시저명: SM_ADD_RENTAL_RETURN

     파라미터  : ISSUE_DATE      VARCHAR(10)    발생일자 YYYY-MM-DD 의 형식으로 전송함
                 SM_SERIAL       VARCHAR(17)    쇼핑몰 주문번호 - 마스터 17 자리만 전송함
                 MON             SMALLINT       최종불입 개월수
                 PENALTY         INTEGER        위약금
                 PG_CHARGE       INTEGER        PG 수수료

     리턴값    : RSLT_CODE       INTEGER        결과코드
                 RSLT_ITEM       VARCHAR(80)    위 코드에 대한 내역

     결과      : 리스상품중 1회 이상 입금된 상품의 회수(반납) 처리가 됩니다.
     */
}

function SM_ADD_RENTAL_LOSS($od_id, $KUBUN)
{
    global $g5;
    //$od_type = "R";
    $od = sql_fetch(" select * from lt_shop_order where od_id = '$od_id' ");
    $od_type = $od['od_type'];

    SM_SEND_CUST_DATA_OD($od); //배송정보 전달을 위한 배송지정보로 회원정보 갱신.

    $ISSUE_DATE = G5_TIME_YMD;
    $SM_SERIAL = $od_type . '-' . substr($od['od_id'], 0, 8) . '-' . substr($od['od_id'], 8, 6);
    $MON = (int) $od['rt_payment_count'];
    $PENALTY = (int) $od['od_penalty'];
    $PG_CHARGE = pg_charge_calc($od['od_settle_case'], $PENALTY); //수수료 계산

    $params = array(
        array("param_name" => "@ISSUE_DATE", "var" => $ISSUE_DATE, "type" => SQLVARCHAR), array("param_name" => "@SM_SERIAL", "var" => $SM_SERIAL, "type" => SQLVARCHAR), array("param_name" => "@MON", "var" => $MON, "type" => SQLINT2), array("param_name" => "@KUBUN", "var" => $KUBUN, "type" => SQLINT2), array("param_name" => "@PENALTY", "var" => $PENALTY, "type" => SQLINT4), array("param_name" => "@PG_CHARGE", "var" => $PG_CHARGE, "type" => SQLINT4)
    );
    $result =  mssql_sql_call("SM_ADD_RENTAL_LOSS", $params);
    $od = sql_query("update lt_shop_order set od_samjin_chk = '{$stmt['RSLT_CODE']}' where od_id = '$od_id' ");

    return $result;
    /*
     *    6. 리스상품의 반납/파손/분실

     프로시저명: SM_ADD_RENTAL_LOSS

     파라미터  : ISSUE_DATE      VARCHAR(10)    발생일자 YYYY-MM-DD 의 형식으로 전송함
                 SM_SERIAL       VARCHAR(17)    쇼핑몰 주문번호 - 마스터 17 자리만 전송함
                 MON             SMALLINT       최종불입 개월수
                 KUBUN           SMALLINT       1-파손  2-분실
                 PENALTY         INTEGER        파손/분실료
                 PG_CHARGE       INTEGER        PG 수수료

     리턴값    : RSLT_CODE       INTEGER        결과코드
                 RSLT_ITEM       VARCHAR(80)    위 코드에 대한 내역

     결과      : 리스상품중 파손/분실 처리된 상품의 처리가 됩니다.
     */
}

function SM_FINISH_SALE_DATA($od_id, $current_status)
{
    global $g5, $default;

    $od = sql_fetch(" select * from lt_shop_order where od_id = '$od_id' ");

    $str_mb_id = trim($od['mb_id']);
    if (empty($str_mb_id)) {
        $od['mb_id'] = "user" . $od['od_id'];
    };
    SM_SEND_CUST_DATA_OD($od); //배송정보 전달을 위한 배송지정보로 회원정보 갱신.

    $ctsql = " select  a.od_type, a.od_id, a.od_sub_id, a.rf_serial, a.io_sapcode_color_gz, a.ct_price, a.io_price,a.ct_rental_price
                                , a.io_order_no, a.io_color_name, a.io_hoching
                                ,a.ct_laundry_price, a.ct_free_laundry, a.ct_zbox_price
                        from    lt_shop_order_item as a
                                inner join lt_shop_cart as c
                                  on a.ct_id = c.ct_id
                        where a.od_id = '{$od_id}'
                         and  c.ct_status = '{$current_status}'
                         and  a.io_sapcode_color_gz != ''
                        order by od_sub_id asc
                        ";
    $ctr = sql_query($ctsql);

    //$DELIVERY_CHARGE = $default['de_send_cost_list']; //기본배송비
    //$DELIVERY_CHARGE = (int)$od['od_send_cost']+(int)$od['od_send_cost2'];
    $DELIVERY_CHARGE = get_samjin_sendcost($od_id, $current_status);
    $DLV_KUBUN = ((int) $od['od_send_cost'] > 0) ? 1 : 0;
    $COUPON_AMOUNT = $COUPON_REMAIN = (int) $od['od_cart_coupon'] + (int) $od['od_coupon'] + (int) $od['od_send_coupon'];
    $WASHING_CHARGE = 0;
    $BOX_CHARGE = 0;
    for ($k = 0; $ct = sql_fetch_array($ctr); $k++) {

        //$WASHING_CHARGE = $WASHING_CHARGE + ((int)$ct['ct_laundry_price'] * (int)$ct['ct_free_laundry'] * ((int)$od['rt_month']/12)); //유료세탁비 * 무료제공횟수 * 3년(리스개월수/12)
        $SM_SERIAL = $ct['od_type'] . '-' . substr($ct['od_id'], 0, 8) . '-' . substr($ct['od_id'], 8, 6) . '-' . $ct['od_sub_id'];
        $RF_SERIAL = $ct['rf_serial'];
        $BOX_CHARGE = $BOX_CHARGE + (int) $ct['ct_zbox_price'];
        $AMOUNT = (int) $ct['ct_price'] + (int) $ct['io_price'];

        // 삼진전산 정산관련 수정사항 - 200226 balance@panpacific.co.kr
        // 1. 배송비 1번 상품 판매가에 포함
        // 2. 쿠폰 할인금액을 상품 판매가에서 차감
        if ($k == 0 && $DLV_KUBUN > 0) $AMOUNT = $AMOUNT + $DELIVERY_CHARGE;
        if ($COUPON_REMAIN > 0) {
            $AMOUNT = $AMOUNT - $COUPON_REMAIN;
            $COUPON_REMAIN = $AMOUNT * -1;
            if ($COUPON_REMAIN > 0 || $AMOUNT == 0) {
                $AMOUNT = 1;
                $COUPON_REMAIN = $COUPON_REMAIN + 1;
            }
        }

        $params = array(
            array("param_name" => "@SM_SERIAL", "var" => $SM_SERIAL, "type" => SQLVARCHAR), array("param_name" => "@RF_SERIAL", "var" => $RF_SERIAL, "type" => SQLVARCHAR), array("param_name" => "@ORDER_NO", "var" => $ct['io_order_no'], "type" => SQLVARCHAR), array("param_name" => "@COLOR", "var" => $ct['io_color_name'], "type" => SQLVARCHAR), array("param_name" => "@HOCHING", "var" => $ct['io_hoching'], "type" => SQLVARCHAR), array("param_name" => "@AMOUNT", "var" => $AMOUNT, "type" => SQLINT4)
        );

        $stmt =  mssql_sql_call("SM_ADD_SALE_DATA", $params);
        /*
        1. ERP 로 개별 판매자료 전송 - 품명/색상/호칭/월리스료
         프로시저명: SM_ADD_SALE_DATA

         파라미터  : SM_SERIAL       VARCHAR(22)    쇼핑몰 주문번호 - 일련번호 포함 22 자리 전송함
                     RF_SERIAL       VARCHAR(16)    RF 고유번호 - 판매상품이 리팩상품 일때만 RF_SERIAL 을 기록하고 일반판매는 NULL 로 전송함
                     ORDER_NO        CHAR(12)       품명
                     COLOR           CHAR(4)        색상
                     HOCHING         CHAR(8)        호칭
                     AMOUNT          INTEGER        실판매가

         리턴값    : RSLT_CODE       INTEGER        결과코드
                     RSLT_ITEM       VARCHAR(80)    위 코드에 대한 내역

         결과      : 전송된 자료들이 임시 테이블에 저장됩니다.
                     아래 SM_FINISH_SALE_DATA 를 호출해야 유효한 자료로 저장됩니다.
        */
    }
    $KUBUN = 1; //판매유형  1-신규판매 2-리팩판매
    $SM_SERIAL = $od['od_type'] . '-' . substr($od['od_id'], 0, 8) . '-' . substr($od['od_id'], 8, 6);
    $ISSUE_DATE = substr($od['od_time'], 0, 10);
    $POINT =  (int) $od['od_receipt_point'];

    $PG_CHARGE = pg_charge_calc($od['od_settle_case'], (int) $od['od_receipt_price']); //수수료 계산
    $CUST_ID = $od['mb_id'];

    $params = array(
        array("param_name" => "@KUBUN", "var" => $KUBUN, "type" => SQLINT2), array("param_name" => "@SM_SERIAL", "var" => $SM_SERIAL, "type" => SQLVARCHAR), array("param_name" => "@ISSUE_DATE", "var" => $ISSUE_DATE, "type" => SQLVARCHAR), array("param_name" => "@COUPON_AMOUNT", "var" => $COUPON_AMOUNT, "type" => SQLINT4), array("param_name" => "@POINT", "var" => $POINT, "type" => SQLINT4), array("param_name" => "@ETC_AMOUNT", "var" => 0, "type" => SQLINT4), array("param_name" => "@DLV_KUBUN", "var" => $DLV_KUBUN, "type" => SQLINT2), array("param_name" => "@DELIVERY_CHARGE", "var" => $DELIVERY_CHARGE, "type" => SQLINT4), array("param_name" => "@PG_CHARGE", "var" => $PG_CHARGE, "type" => SQLINT4), array("param_name" => "@CUST_ID", "var" => $CUST_ID, "type" => SQLVARCHAR)
    );

    $stmt =  mssql_sql_call("SM_FINISH_SALE_DATA", $params);
    $od = sql_query("update lt_shop_order set od_samjin_chk = '{$stmt['RSLT_CODE']}' where od_id = '$od_id' ");

    return $stmt;
    /*
      2. 개별 판매자료를 전송한뒤 마스터자료 전송

     프로시저명: SM_FINISH_SALE_DATA

     파라미터  : KUBUN           SMALLINT       판매유형  1-신규판매 2-리팩판매
                 SM_SERIAL       VARCHAR(17)    쇼핑몰 주문번호 - 마스터 17 자리만 전송함
                 ISSUE_DATE      VARCHAR(10)    발생일자 YYYY-MM-DD 의 형식으로 전송함
                 COUPON_AMOUNT   INTEGER        쿠폰 할인금액
                 POINT           INTEGER        포인트 사용금액
                 ETC_AMOUNT      INTEGER        기타 프로모션 금액
                 DLV_KUBUN       SMALLINT       배송비 구분 0-무상 1-유상  ** 리팩판매는 무조건 무상으로 처리됩니다.
                 DELIVERY_CHARGE INTEGER        배송비
                 PG_CHARGE       INTEGER        발생한 PG수수료
                 CUST_ID         VARCHAR(20)    고객코드

     리턴값    : RSLT_CODE       INTEGER        결과코드
                 RSLT_ITEM       VARCHAR(80)    위 코드에 대한 내역

     결과      : 위의 SM_ADD_SALE_DATA 로 전송된 자료들이 유효한 자료로 전송됩니다.

     *** 3 개의 상품이 한 번에 판매되었을때는 상품별로 SM_ADD_SALE_DATA 를 세번 호출한다음
         SM_FINISH_SALE_DATA 를 호출해야 합니다.
     */
}

/**
 *   3. 판매 취소
 * @param $od_id
 * @param  $RSP - 취소시 귀책구분  1-리탠다드 귀책  2-고객귀책
 * @return
 */
function SM_ADD_SALE_CANCEL($od_id)
{
    global $g5;
    $od = sql_fetch(" select * from lt_shop_order where od_id = '$od_id' ");

    SM_SEND_CUST_DATA_OD($od); //배송정보 전달을 위한 배송지정보로 회원정보 갱신.

    $RSP = "1";
    $od_sh = sql_fetch("select cancel_select from lt_shop_order_history where od_id = '$od_id' and ct_status_claim = '반품' order by sh_id desc limit 1");

    //고객 귀책
    if ($od_sh['cancel_select'] == "색상및사이즈변경" || $od_sh['cancel_select'] == "다른상품잘못주문") {
        $RSP = "2";
    }

    $ISSUE_DATE = G5_TIME_YMD;
    $SM_SERIAL = $od['od_type'] . '-' . substr($od['od_id'], 0, 8) . '-' . substr($od['od_id'], 8, 6);

    $params = array(
        array("param_name" => "@ISSUE_DATE", "var" => $ISSUE_DATE, "type" => SQLVARCHAR), array("param_name" => "@SM_SERIAL", "var" => $SM_SERIAL, "type" => SQLVARCHAR), array("param_name" => "@RSP", "var" => $RSP, "type" => SQLINT2)
    );
    $result =  mssql_sql_call("SM_ADD_SALE_CANCEL", $params);
    $od = sql_query("update lt_shop_order set od_samjin_chk = '{$stmt['RSLT_CODE']}' where od_id = '$od_id' ");

    return $result;
    /*
     *    3. 판매 취소

     프로시저명: SM_ADD_SALE_CANCEL

     파라미터  : ISSUE_DATE      VARCHAR(10)    발생일자 YYYY-MM-DD 의 형식으로 전송함
                 SM_SERIAL       VARCHAR(17)    쇼핑몰 주문번호 - 마스터 17 자리만 전송함
                 RSP             SMALLINT       취소시 귀책구분  1-리탠다드 귀책  2-고객귀책

     리턴값    : RSLT_CODE       INTEGER        결과코드
                 RSLT_ITEM       VARCHAR(80)    위 코드에 대한 내역

     결과      : 판매가 취소됩니다.
                 S_MALL_SALE_MAIN_MASTER 를 확인해서 SALE_DATE 가 기록되었으면 실물 회수후 호출해야 합니다.
     */
}


function SM_ADD_PARTIAL_SALE_CANCEL($od_id)
{
    global $g5;
    $od = sql_fetch(" select od_type, od_id, od_settle_case, od_refund_price from lt_shop_order where od_id = '$od_id' ");

    SM_SEND_CUST_DATA_OD($od); //배송정보 전달을 위한 배송지정보로 회원정보 갱신.

    $ISSUE_DATE = G5_TIME_YMD;
    $SM_SERIAL = $od['od_type'] . '-' . substr($od['od_id'], 0, 8) . '-' . substr($od['od_id'], 8, 6);
    $PG_CHARGE = pg_charge_calc($od['od_settle_case'], (int) $od['od_refund_price']); //취소 수수료 계산

    $RSP = 1;
    $od_sh = sql_fetch("select cancel_select from lt_shop_order_history where od_id = '$od_id' and ct_status_claim = '반품' order by sh_id desc limit 1");

    //고객 귀책
    if ($od_sh['cancel_select'] == "색상및사이즈변경" || $od_sh['cancel_select'] == "다른상품잘못주문") {
        $RSP = 2;
    }

    $params = array(
        array("param_name" => "@ISSUE_DATE", "var" => $ISSUE_DATE, "type" => SQLVARCHAR), array("param_name" => "@SM_SERIAL", "var" => $SM_SERIAL, "type" => SQLVARCHAR), array("param_name" => "@RSP", "var" => $RSP, "type" => SQLINT2), array("param_name" => "@PG_CHARGE", "var" => $PG_CHARGE, "type" => SQLINT4)
    );

    $ct_result = sql_query(" select a.od_sub_id from lt_shop_order_item as a inner join lt_shop_cart as c on a.ct_id = c.ct_id where c.od_id = '$od_id' and c.ct_status in ('주문취소', '반품완료') ");
    for ($i = 1; $row = sql_fetch_array($ct_result); $i++) {
        array_push($params, array("param_name" => "@S" . $i, "var" => $row['od_sub_id'], "type" => SQLVARCHAR));
    }

    for ($j = $i; $j <= 9; $j++) {
        $nulldata = null;
        array_push($params, array("param_name" => "@S" . $j, "var" => $nulldata, "type" => SQLVARCHAR));
    }
    $nulldata = null;
    array_push($params, array("param_name" => "@SA", "var" => $nulldata, "type" => SQLVARCHAR));

    $result =  mssql_sql_call("SM_ADD_PARTIAL_SALE_CANCEL", $params);

    return $result;
    /*
     *          4. 판매 부분취소

     프로시저명: SM_ADD_PARTIAL_SALE_CANCEL

     파라미터  : ISSUE_DATE      VARCHAR(10)    발생일자 YYYY-MM-DD 의 형식으로 전송함
                 SM_SERIAL       VARCHAR(17)    쇼핑몰 주문번호 - 마스터 17 자리만 전송함
                 RSP             SMALLINT       취소시 귀책구분  1-리탠다드 귀책  2-고객귀책
                 PG_CHARGE       INTEGER        취소된 상품들의 PG 수수료 ( 양수로 전송함 )
                 S1              VARCHAR(4)     취소된 상품의 주문번호 하위 4 자리
                 S2              VARCHAR(4)     취소된 상품의 주문번호 하위 4 자리
                 S3              VARCHAR(4)     취소된 상품의 주문번호 하위 4 자리
                 S4              VARCHAR(4)     취소된 상품의 주문번호 하위 4 자리
                 S5              VARCHAR(4)     취소된 상품의 주문번호 하위 4 자리
                 S6              VARCHAR(4)     취소된 상품의 주문번호 하위 4 자리
                 S7              VARCHAR(4)     취소된 상품의 주문번호 하위 4 자리
                 S8              VARCHAR(4)     취소된 상품의 주문번호 하위 4 자리
                 S9              VARCHAR(4)     취소된 상품의 주문번호 하위 4 자리
                 SA              VARCHAR(4)     취소된 상품의 주문번호 하위 4 자리

     리턴값    : RSLT_CODE       INTEGER        결과코드
                 RSLT_ITEM       VARCHAR(80)    위 코드에 대한 내역

     결과      : 지정한 하위번호의 판매가 취소됩니다.
                 S_MALL_SALE_MAIN_MASTER 를 확인해서 SALE_DATE 가 기록되었으면 실물 회수후 호출해야 합니다.

                 ** 부분취소는 10개 까지 가능합니다.
                    '0001','0003','0006' 세개의 하위번호가 부분취소 되었다면
                    S1 에서 SA 까지 '0001','0003','0006',NULL,NULL,NULL,NULL,NULL,NULL,NULL 과 같이 호출합니다.
     */
}



function SM_ADD_CLEANING_REQUEST($od_id)
{
    global $g5;
    $od = sql_fetch(" select * from lt_shop_order where od_id = '$od_id' ");

    SM_SEND_CUST_DATA_OD($od); //배송정보 전달을 위한 배송지정보로 회원정보 갱신.

    $ct = sql_fetch(" select * from lt_shop_cart where od_id = '$od_id' ");

    $buy_item = sql_fetch(" select * from lt_shop_order_item where ct_id = '{$ct['buy_ct_id']}' and  od_sub_id = '{$ct['buy_od_sub_id']}' ");

    $SM_SERIAL = $od['od_type'] . '-' . substr($od['od_id'], 0, 8) . '-' . substr($od['od_id'], 8, 6);
    //$SM_SERIAL = 'O-'.substr($od['od_id'],0,8).'-'.substr($od['od_id'],8,6);
    $ISSUE_DATE = substr($od['od_time'], 0, 10);

    $ORG_SM_SERIAL = $buy_item['od_type'] . '-' . substr($buy_item['od_id'], 0, 8) . '-' . substr($buy_item['od_id'], 8, 6);


    //$DELIVERY_CHARGE = (int)$od['od_send_cost']+(int)$od['od_send_cost2'];
    $PG_CHARGE = pg_charge_calc($od['od_settle_case'], (int) $od['od_receipt_price']); //수수료 계산

    if ($od['od_type'] == "L") {
        $DELIVERY_CHARGE = (int) $buy_item['ct_laundry_delivery_price'];
        $WASHING_CHARGE = (int) $ct['ct_laundry_price'] - (int) $buy_item['ct_zbox_price'] - $DELIVERY_CHARGE; //세탁비 : 세탁비-박스비-배송비
        $STORAGE_CHARGE = 0;

        $KUBUN = ($ct['ct_free_laundry_use'] == '1') ? 1 : 3; //세탁유무상 구분 1-무상세탁 3-유상세탁  ** 보관은 무조건 유상임

    } else if ($od['od_type'] == "K") {
        $DELIVERY_CHARGE = (int) $buy_item['ct_laundrykeep_delivery_price'];
        $WASHING_CHARGE = (int) $ct['ct_laundry_price'] - (int) $buy_item['ct_zbox_price'] - $DELIVERY_CHARGE; //세탁비 : 세탁비-박스비-배송비
        $STORAGE_CHARGE = (int) $ct['ct_laundrykeep_kprice'] * (int) $ct['ct_keep_month'];

        $KUBUN = ($ct['ct_free_laundry_use'] == '1') ? 2 : 4; //세탁유무상 구분 2-무상세탁+유상보관 4-유상세탁+유상보관  ** 보관은 무조건 유상임
    }

    $params = array(
        array("param_name" => "@ISSUE_DATE", "var" => $ISSUE_DATE, "type" => SQLVARCHAR), array("param_name" => "@SM_SERIAL", "var" => $SM_SERIAL, "type" => SQLVARCHAR), array("param_name" => "@ORG_SM_SERIAL", "var" => $ORG_SM_SERIAL, "type" => SQLVARCHAR), array("param_name" => "@KUBUN", "var" => $KUBUN, "type" => SQLINT2), array("param_name" => "@SZ", "var" => $buy_item['ct_zbox_name'], "type" => SQLINT4), array("param_name" => "@CUST_SHARE", "var" => $od['od_receipt_price'], "type" => SQLINT4), array("param_name" => "@WASHING_CHARGE", "var" => $WASHING_CHARGE, "type" => SQLINT4), array("param_name" => "@STORAGE_CHARGE", "var" => $STORAGE_CHARGE, "type" => SQLINT4), array("param_name" => "@DELIVERY_CHARGE", "var" => $DELIVERY_CHARGE, "type" => SQLINT4), array("param_name" => "@B_DELIVERY_CHARGE", "var" => $DELIVERY_CHARGE, "type" => SQLINT4), array("param_name" => "@BOX_CHARGE", "var" => $buy_item['ct_zbox_price'], "type" => SQLINT4), array("param_name" => "@PG_CHARGE", "var" => $PG_CHARGE, "type" => SQLINT4)
    );

    $stmt =  mssql_sql_call("SM_ADD_CLEANING_REQUEST", $params);
    $od = sql_query("update lt_shop_order set od_samjin_chk = '{$stmt['RSLT_CODE']}' where od_id = '$od_id' ");

    /*
     1. 세탁/보관자료 전송

     프로시저명: SM_ADD_CLEANING_REQUEST

     파라미터  : ISSUE_DATE      VARCHAR(10)    발생일자 YYYY-MM-DD 의 형식으로 전송함
                 SM_SERIAL       VARCHAR(17)    쇼핑몰 세탁/보관 주문번호 - 마스터 17 자리만 전송함
                 ORG_SM_SERIAL   VARCHAR(17)    원래 판매시의 주문번호
                 X => KUBUN           SMALLINT       세탁유무상 구분 0-무상 1-유상  ** 보관은 무조건 유상임
                 KUBUN           SMALLINT       1-무상세탁  2-무상세탁+유상보관  3-유상세탁 4-유상세탁+유상보관
                 SZ              SMALLINT       박스 규격 1-15 까지
                 CUST_SHARE      INTEGER        고객부담금
                 WASHING_CHARGE  INTEGER        세탁비
                 STORAGE_CHARGE  INTEGER        보관비
                 DELIVERY_CHARGE INTEGER        배송비
                 B_DELIVERY_CHARGE INTEGER        공박스 배송비
                 BOX_CHARGE      INTEGER        박스비
                 PG_CHARGE       INTEGER        PG 수수료

     리턴값    : RSLT_CODE       INTEGER        결과코드
                 RSLT_ITEM       VARCHAR(80)    위 코드에 대한 내역

     결과      : 세탁,보관 요청을 처리함
     */
    return $stmt;
}

function SM_ADD_CLEANING_FINISH($od_id)
{
    global $g5;
    $od = sql_fetch(" select * from lt_shop_order where od_id = '$od_id' ");

    //$SM_SERIAL = $od['od_type'].'-'.substr($od['od_id'],0,8).'-'.substr($od['od_id'],8,6);
    $SM_SERIAL = $od['od_type'] . '-' . substr($od['od_id'], 0, 8) . '-' . substr($od['od_id'], 8, 6);
    $ISSUE_DATE = G5_TIME_YMD;

    $params = array(
        array("param_name" => "@ISSUE_DATE", "var" => $ISSUE_DATE, "type" => SQLVARCHAR), array("param_name" => "@SM_SERIAL", "var" => $SM_SERIAL, "type" => SQLVARCHAR)
    );

    $stmt =  mssql_sql_call("SM_ADD_CLEANING_FINISH", $params);
    $od = sql_query("update lt_shop_order set od_samjin_chk = '{$stmt['RSLT_CODE']}' where od_id = '$od_id' ");

    /*
     2. 세탁/보관 처리후 고객에게 배송완료

     프로시저명: SM_ADD_CLEANING_FINISH

     파라미터  : ISSUE_DATE      VARCHAR(10)    발생일자 YYYY-MM-DD 의 형식으로 전송함
                 SM_SERIAL       VARCHAR(17)    쇼핑몰 세탁/보관 주문번호 - 마스터 17 자리만 전송함

     리턴값    : RSLT_CODE       INTEGER        결과코드
                 RSLT_ITEM       VARCHAR(80)    위 코드에 대한 내역

     결과      : 세탁,보관후 고객에게 배송처리 완료
     */
    return $stmt;
}


function SM_ADD_REPAIR_REQUEST($od_id)
{
    global $g5;
    $od = sql_fetch(" select * from lt_shop_order where od_id = '$od_id' ");
    $ct = sql_fetch(" select * from lt_shop_cart where od_id = '$od_id' ");
    $buy_item = sql_fetch(" select * from lt_shop_order_item where ct_id = '{$ct['buy_ct_id']}' and  od_sub_id = '{$ct['buy_od_sub_id']}' ");

    $SM_SERIAL = $od['od_type'] . '-' . substr($od['od_id'], 0, 8) . '-' . substr($od['od_id'], 8, 6);
    $ISSUE_DATE = substr($od['od_time'], 0, 10);
    $ORG_SM_SERIAL = $buy_item['od_type'] . '-' . substr($buy_item['od_id'], 0, 8) . '-' . substr($buy_item['od_id'], 8, 6);

    $CUST_SHARE = (int) $od['od_receipt_price'];
    $DELIVERY_CHARGE = (int) $buy_item['ct_repair_delivery_price']; //등록된 수선의 배송비로 보냄.
    $REPAIR_CHARGE = $CUST_SHARE - $DELIVERY_CHARGE;
    $PG_CHARGE = pg_charge_calc($od['od_settle_case'], (int) $od['od_receipt_price']); //수수료 계산


    $params = array(
        array("param_name" => "@ISSUE_DATE", "var" => $ISSUE_DATE, "type" => SQLVARCHAR), array("param_name" => "@SM_SERIAL", "var" => $SM_SERIAL, "type" => SQLVARCHAR), array("param_name" => "@ORG_SM_SERIAL", "var" => $ORG_SM_SERIAL, "type" => SQLVARCHAR), array("param_name" => "@CUST_SHARE", "var" => $CUST_SHARE, "type" => SQLINT4), array("param_name" => "@REPAIR_CHARGE", "var" => $REPAIR_CHARGE, "type" => SQLINT4), array("param_name" => "@DELIVERY_CHARGE", "var" => $DELIVERY_CHARGE, "type" => SQLINT4), array("param_name" => "@PG_CHARGE", "var" => $PG_CHARGE, "type" => SQLINT4)
    );

    $stmt =  mssql_sql_call("SM_ADD_REPAIR_REQUEST", $params);
    $od = sql_query("update lt_shop_order set od_samjin_chk = '{$stmt['RSLT_CODE']}' where od_id = '$od_id' ");

    /*
     1. 수선요청 접수 ( 수선접수시 판정후 수선이 가능할 때 )

     프로시저명: SM_ADD_REPAIR_REQUEST

     파라미터  : ISSUE_DATE      VARCHAR(10)    접수일자
                 SM_SERIAL       VARCHAR(17)    수선 주문번호
                 ORG_SM_SERIAL   VARCHAR(17)    최초 리스/판매시의 주문번호
                 CUST_SHARE      INTEGER        고객부담금
                 REPAIR_CHARGE   INTEGER        소요될 수선비
                 DELIVERY_CHARGE INTEGER        소요될 배송비
                 PG_CHARGE       INTEGER        PG 수수료

     리턴값    : RSLT_CODE       INTEGER        결과코드
                 RSLT_ITEM       VARCHAR(80)    위 코드에 대한 내역

     결과      : 수선(유료) 이 접수됨
     */
    return $stmt;
}

function SM_ADD_REPAIR_REJECT($od_id)
{
    global $g5;
    $od = sql_fetch(" select * from lt_shop_order where od_id = '$od_id' ");
    $ct = sql_fetch(" select * from lt_shop_cart where od_id = '$od_id' ");
    $buy_item = sql_fetch(" select * from lt_shop_order_item where ct_id = '{$ct['buy_ct_id']}' and  od_sub_id = '{$ct['buy_od_sub_id']}' ");

    $SM_SERIAL = $od['od_type'] . '-' . substr($od['od_id'], 0, 8) . '-' . substr($od['od_id'], 8, 6);
    $ISSUE_DATE = G5_TIME_YMD;
    $ORG_SM_SERIAL = $buy_item['od_type'] . '-' . substr($buy_item['od_id'], 0, 8) . '-' . substr($buy_item['od_id'], 8, 6);

    $DELIVERY_CHARGE = (int) $buy_item['ct_repair_delivery_price']; //등록된 수선의 배송비로 보냄.
    $PG_CHARGE = pg_charge_calc($od['od_settle_case'], (int) $od['od_receipt_price']); //수수료 계산

    $params = array(
        array("param_name" => "@ISSUE_DATE", "var" => $ISSUE_DATE, "type" => SQLVARCHAR), array("param_name" => "@SM_SERIAL", "var" => $SM_SERIAL, "type" => SQLVARCHAR), array("param_name" => "@ORG_SM_SERIAL", "var" => $ORG_SM_SERIAL, "type" => SQLVARCHAR), array("param_name" => "@CUST_SHARE", "var" => $od['od_receipt_price'], "type" => SQLINT4), array("param_name" => "@DELIVERY_CHARGE", "var" => $DELIVERY_CHARGE, "type" => SQLINT4), array("param_name" => "@PG_CHARGE", "var" => $PG_CHARGE, "type" => SQLINT4)
    );

    $stmt =  mssql_sql_call("SM_ADD_REPAIR_REJECT", $params);
    $od = sql_query("update lt_shop_order set od_samjin_chk = '{$stmt['RSLT_CODE']}' where od_id = '$od_id' ");

    /*
     2. 수선이 접수되었으나 수선불가로 반송처리됨

      프로시저명: SM_ADD_REPAIR_REJECT

      파라미터  : ISSUE_DATE      VARCHAR(10)            처리일자
                  SM_SERIAL       VARCHAR(17)            원래의 수선 주문번호
                  ORG_SM_SERIAL   VARCHAR(17),           최초 리스/판매시의 주문번호
                  CUST_SHARE      INTEGER                고객부담금
                  DELIVERY_CHARGE INTEGER                반송배송비
                  PG_CHARGE       INTEGER                반송배송비의 PG 수수료

      리턴값    : RSLT            INTEGER                0 - 실행완료
                  RSLT_ITEM       VARCHAR(80)            자료가 처리되었습니다.

      결과      : 수선이 접수되었으나 수선불가로 반송처리됨
     */
}

function SM_ADD_CLEANING_CANCEL($od_id)
{
    global $g5;
    $od = sql_fetch(" select * from lt_shop_order where od_id = '$od_id' ");

    $SM_SERIAL = $od['od_type'] . '-' . substr($od['od_id'], 0, 8) . '-' . substr($od['od_id'], 8, 6);
    $ISSUE_DATE = G5_TIME_YMD;

    $PG_CHARGE = pg_charge_calc($od['od_settle_case'], (int) $od['od_receipt_price']); //수수료 계산

    $params = array(
        array("param_name" => "@ISSUE_DATE", "var" => $ISSUE_DATE, "type" => SQLVARCHAR), array("param_name" => "@SM_SERIAL", "var" => $SM_SERIAL, "type" => SQLVARCHAR), array("param_name" => "@NEW_CUST_SHARE", "var" => $od['od_receipt_price'], "type" => SQLINT4), array("param_name" => "@NEW_PG_CHARGE", "var" => $PG_CHARGE, "type" => SQLINT4)
    );

    $stmt =  mssql_sql_call("SM_ADD_CLEANING_CANCEL", $params);
    $od = sql_query("update lt_shop_order set od_samjin_chk = '{$stmt['RSLT_CODE']}' where od_id = '$od_id' ");
    /*
     3. 수선 접수후 취소됨

      프로시저명: SM_ADD_CLEANING_CANCEL

      파라미터  : ISSUE_DATE      VARCHAR(10)            처리일자
                  SM_SERIAL       VARCHAR(17)            원래의 수선 주문번호
                  NEW_CUST_SHARE  INTEGER                취소후 신규로 발생한 고객부담금  ** 수선접수시 발생했던 고객부담금은 모두 반환처리됨
                  NEW_PG_CHARGE   INTEGER                취소후 신규로 발생한 PG 수수료   ** 수선접수시 발생했던 PG 수수료는  모두 반환처리됨

      리턴값    : RSLT            INTEGER                0 - 실행완료
                  RSLT_ITEM       VARCHAR(80)            자료가 처리되었습니다.

      결과      : 현재 상태에서 세탁/보관이 취소됨
                  1.접수상태 2.박스출고상태 3.완료후 배송상태의 세가지 상태에서 모두 발생할 수 있음
     */
}

function SM_ADD_REPAIR_FINISH($od_id)
{
    global $g5;
    $od = sql_fetch(" select * from lt_shop_order where od_id = '$od_id' ");

    $SM_SERIAL = $od['od_type'] . '-' . substr($od['od_id'], 0, 8) . '-' . substr($od['od_id'], 8, 6);
    $ISSUE_DATE = G5_TIME_YMD;
    $DELIVERY_CHARGE = (int) $od['od_send_cost2'];

    $params = array(
        array("param_name" => "@ISSUE_DATE", "var" => $ISSUE_DATE, "type" => SQLVARCHAR), array("param_name" => "@SM_SERIAL", "var" => $SM_SERIAL, "type" => SQLVARCHAR), array("param_name" => "@DELIVERY_CHARGE", "var" => $DELIVERY_CHARGE, "type" => SQLINT4)
    );

    $stmt =  mssql_sql_call("SM_ADD_REPAIR_FINISH", $params);
    $od = sql_query("update lt_shop_order set od_samjin_chk = '{$stmt['RSLT_CODE']}' where od_id = '$od_id' ");
    /*
     4. 수선 완료완료후 고객에게 배송됨

      프로시저명: SM_ADD_REPAIR_FINISH

      파라미터  : ISSUE_DATE      VARCHAR(10)            처리일자
                  SM_SERIAL       VARCHAR(17)            원래의 수선 주문번호
                  DELIVERY_CHARGE INTEGER                배송비

      리턴값    : RSLT            INTEGER                0 - 실행완료
                  RSLT_ITEM       VARCHAR(80)            자료가 처리되었습니다.

      결과      : 처리됨
      */
    return $stmt;
}

function SM_ADD_CUST_REPAIR_REJECT($od_id)
{
    global $g5;
    $od = sql_fetch(" select * from lt_shop_order where od_id = '$od_id' ");

    $SM_SERIAL = $od['od_type'] . '-' . substr($od['od_id'], 0, 8) . '-' . substr($od['od_id'], 8, 6);
    $ISSUE_DATE = G5_TIME_YMD;

    $params = array(
        array("param_name" => "@ISSUE_DATE", "var" => $ISSUE_DATE, "type" => SQLVARCHAR), array("param_name" => "@SM_SERIAL", "var" => $SM_SERIAL, "type" => SQLVARCHAR)
    );

    $stmt =  mssql_sql_call("SM_ADD_CUST_REPAIR_REJECT", $params);
    $od = sql_query("update lt_shop_order set od_samjin_chk = '{$stmt['RSLT_CODE']}' where od_id = '$od_id' ");
    /*
        5. 수선접수후 완료되었으나 고객 클레임으로 취소 처리됨.

      프로시저명: SM_ADD_CUST_REPAIR_REJECT

      파라미터  : ISSUE_DATE      VARCHAR(10)            처리일자
                  SM_SERIAL       VARCHAR(17)            원래의 수선 주문번호

      리턴값    : RSLT            INTEGER                0 - 실행완료
                  RSLT_ITEM       VARCHAR(80)            자료가 처리되었습니다.

      결과      : 수선접수후 완료되었으나 고객 클레임으로 취소 처리됨.

     */
}
