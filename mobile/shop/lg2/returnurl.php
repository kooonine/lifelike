<?php
include_once('./_common.php');

/*
xpay_approval.php 에서 세션에 저장했던 파라미터 값이 유효한지 체크
세션 유지 시간(로그인 유지시간)을 적당히 유지 하거나 세션을 사용하지 않는 경우 DB처리 하시기 바랍니다.
*/

if(!isset($_SESSION['PAYREQ_MAP'])){
    alert('세션이 만료 되었거나 유효하지 않은 요청 입니다.', G5_SHOP_URL);
}

$payReqMap = $_SESSION['PAYREQ_MAP']; //결제 요청시, Session에 저장했던 파라미터 MAP

$g5['title'] = 'LG 유플러스 eCredit서비스 결제';
$g5['body_script'] = ' onload="setLGDResult();"';
include_once(G5_PATH.'/head.sub.php');

$LGD_RESPCODE = $_REQUEST['LGD_RESPCODE'];
$LGD_RESPMSG  = $_REQUEST['LGD_RESPMSG'];
$LGD_PAYKEY   = '';

$LGD_OID          = $payReqMap['LGD_OID'];

$sql = " select * from {$g5['g5_shop_order_data_table']} where od_id = '$LGD_OID' ";
$row = sql_fetch($sql);

$data = unserialize(base64_decode($row['dt_data']));

if(isset($data['pp_id']) && $data['pp_id']) {
    $order_action_url = G5_HTTPS_MSHOP_URL.'/personalpayformupdate.php';
    $page_return_url  = G5_SHOP_URL.'/personalpayform.php?pp_id='.$data['pp_id'];
} else {
    $order_action_url = G5_HTTPS_SHOP_URL.'/orderformupdate.r.php';
    $page_return_url  = G5_SHOP_URL.'/orderform.php';
    if($_SESSION['ss_direct'])
        $page_return_url .= '?sw_direct=1';
}

if($LGD_RESPCODE == '0000') {
    /*
    $LGD_PAYKEY                = $_REQUEST['LGD_PAYKEY'];
    $payReqMap['LGD_RESPCODE'] = $LGD_RESPCODE;
    $payReqMap['LGD_RESPMSG']  = $LGD_RESPMSG;
    $payReqMap['LGD_PAYKEY']   = $LGD_PAYKEY;
    */
    $LGD_BILLKEY		= $_POST['LGD_BILLKEY'];		//추후 빌링시 카드번호 대신 입력할 값입니다.
    $LGD_PAYTYPE		= $_POST['LGD_PAYTYPE'];		//인증수단
    $LGD_PAYDATE		= $_POST['LGD_PAYDATE'];		//인증일시
    $LGD_FINANCECODE	= $_POST['LGD_FINANCECODE'];	//인증기관코드
    $LGD_FINANCENAME	= $_POST['LGD_FINANCENAME'];	//인증기관이름
    
    
    $payReqMap['LGD_BILLKEY']		= $LGD_BILLKEY;
    $payReqMap['LGD_PAYTYPE']		= $LGD_PAYTYPE;
    $payReqMap['LGD_PAYDATE']		= $LGD_PAYDATE;
    $payReqMap['LGD_FINANCECODE'] = $LGD_FINANCECODE;
    $payReqMap['LGD_FINANCENAME'] = $LGD_FINANCENAME;
} else {
    alert('LGD_RESPCODE:' . $LGD_RESPCODE . ' ,LGD_RESPMSG:' . $LGD_RESPMSG, $page_return_url); //인증 실패에 대한 처리 로직 추가
}
?>

<?php
$exclude = array('LGD_OID', 'res_cd','LGD_PAYKEY','LGD_BILLKEY','LGD_PAYTYPE','LGD_PAYDATE','LGD_FINANCECODE','LGD_FINANCENAME');

echo '<form name="forderform" method="post" action="'.$order_action_url.'" autocomplete="off">'.PHP_EOL;

echo make_order_field($data, $exclude);

foreach ($payReqMap as $key => $value) {
    echo "<input type='hidden' name='$key' id='$key' value='$value'>".PHP_EOL;
}

echo '</form>'.PHP_EOL;
?>
<div class="grid">
	<div class="guide_box ico ico_chk">
		<p>주문완료 중입니다. <br/>잠시만 기다려 주십시오.</p>
	</div>
</div>
<!-- div>
    <div id="show_progress">
        <span style="display:block; text-align:center;margin-top:120px"><img src="<?php echo G5_MOBILE_URL; ?>/shop/img/loading.gif" alt=""></span>
        <span style="display:block; text-align:center;margin-top:10px; font-size:14px">주문완료 중입니다. 잠시만 기다려 주십시오.</span>
    </div>
</div -->

<script type="text/javascript">
function setLGDResult() {
    setTimeout( function() {
        document.forderform.submit();
    }, 300);
}
</script>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>