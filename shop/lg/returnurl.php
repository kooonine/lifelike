<?php
include_once('./_common.php');

/*
  payreq_crossplatform 에서 세션에 저장했던 파라미터 값이 유효한지 체크
  세션 유지 시간(로그인 유지시간)을 적당히 유지 하거나 세션을 사용하지 않는 경우 DB처리 하시기 바랍니다.
*/
if (!isset($_SESSION['PAYREQ_MAP'])) {
  echo '세션이 만료 되었거나 유효하지 않은 요청 입니다.';
  return;
}

$payReqMap = $_SESSION['PAYREQ_MAP']; //결제 요청시, Session에 저장했던 파라미터 MAP
?>
<html>

<head>
  <script type="text/javascript">
    function setLGDResult() {

      var form = document.getElementsByTagName("input");
      var data = [];

      for (idx in form) {
        data[form[idx].id] = form[idx].value;
      }
      if(data['LGD_WINDOW_TYPE'] == 'iframe'){
        parent.payment_return(data);
        try {} catch (e) {
          alert(e.message);
        }
      }else if (data['LGD_WINDOW_TYPE'] == 'submit'){
        if (data['LGD_RESPCODE'] == "0000") {
          document.getElementById("LGD_RETURNINFO").submit();
        }
        
      }else{
        opener.payment_return(data);
        try {} catch (e) {
          alert(e.message);
        }
        window.close("_self");
      }

      // var varUA = navigator.userAgent.toLowerCase();
      // if ( varUA.indexOf("iphone") > -1||varUA.indexOf("ipad") > -1||varUA.indexOf("ipod") > -1 ){
      //   var form = document.getElementsByTagName("input");
      //   var data = [];
  
      //   for (idx in form) {
      //     data[form[idx].id] = form[idx].value;
      //   }
      //   parent.payment_return(data);
      //   try {} catch (e) {
      //     alert(e.message);
      //   }
			// } else {
				
      //   var form = document.getElementsByTagName("input");
      //   var data = [];
  
      //   for (idx in form) {
      //     data[form[idx].id] = form[idx].value;
      //   }
      //   opener.payment_return(data);
      //   try {} catch (e) {
      //     alert(e.message);
      //   }
  
      //   window.close("_self");
			// }
    }
  </script>
</head>

<body onload="setLGDResult()">
  <?php
  $LGD_RESPCODE = $_POST['LGD_RESPCODE'];
  $LGD_RESPMSG  = $_POST['LGD_RESPMSG'];
  $LGD_PAYKEY   = '';

  $payReqMap['LGD_RESPCODE'] = $LGD_RESPCODE;
  $payReqMap['LGD_RESPMSG']   =  $LGD_RESPMSG;

  if ($LGD_RESPCODE == "0000") {
    $LGD_PAYKEY = $_POST['LGD_PAYKEY'];
    $payReqMap['LGD_PAYKEY'] = $LGD_PAYKEY;
  } else {
    echo "LGD_RESPCODE:" + $LGD_RESPCODE + " ,LGD_RESPMSG:" + $LGD_RESPMSG; //인증 실패에 대한 처리 로직 추가
  }
  ?>
  
  <form method="post" name="LGD_RETURNINFO" id="LGD_RETURNINFO" action="../orderformupdate.xpay.php" >
    <?php
    foreach ($payReqMap as $key => $value) {
      echo "<input type='hidden' name='$key' id='$key' value='$value'>";
    }
    ?>
  </form>
  <div style="display: block; font-size : 15px; text-align : center;">주문완료 중입니다. 잠시만 기다려 주십시오.</div>
</body>

</html>