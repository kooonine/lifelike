<?php
include_once("../common.php");

function parse_pass_response($plaindata = "")
{
    $rawPlainData = explode(":", $plaindata);

    $date_possible = (int) date("Ymd", strtotime("-14 year"));
    $returnData = array();
    $tmpKey = "";

    $len = $rawPlainData[0];
    for ($i = 1; $i < count($rawPlainData); $i++) {

        $tmpValue = substr($rawPlainData[$i], 0, (int) $len);
        $len = substr($rawPlainData[$i], (int) $len);
        if ($i % 2 === 0) {
            $returnData[$tmpKey] = $tmpKey == 'UTF8_NAME' ? urldecode($tmpValue) : $tmpValue;
        } else {
            $tmpKey = $tmpValue;
        }
    }

    if (isset($returnData['GENDER'])) {
        $returnData['GENDER_PLAIN'] = $returnData['GENDER'] == 0 ? 'F' : 'M';
        $returnData['GENDER'] = $returnData['GENDER'] == 0 ? '여자' : '남자';
    }
    if (isset($returnData['BIRTHDATE'])) {
        $returnData['OVER14'] = (int) $returnData['BIRTHDATE'] <= $date_possible;
    }

    return $returnData;
}

$sitecode = PASS_SITECODE;
$sitepasswd = PASS_SITEPASS;

$error = "";
$data = array();

$enc_data = $_REQUEST["EncodeData"];    // 암호화된 결과 데이타

if (preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {
    $error = "입력 값 확인이 필요합니다 : " . $match[0];
}
if (base64_encode(base64_decode($enc_data)) != $enc_data) {
    $error = "입력 값 확인이 필요합니다";
}

if (!empty($enc_data)) {
    $plaindata = get_decode_data($sitecode, $sitepasswd, $enc_data); // 암호화된 결과 데이터의 복호화

    switch ($plaindata) {
        case -1:
            $error = "암/복호화 시스템 오류";
            break;
        case -4:
            $error = "복호화 처리 오류";
            break;
        case -5:
            $error = "HASH값 불일치 - 복호화 데이터는 리턴됨";
            break;
        case -6:
            $error = "복호화 데이터 오류";
            break;
        case -9:
            $error = "입력값 오류";
            break;
        case -12:
            $error = "사이트 비밀번호 오류";
            break;
        default:
            $data = parse_pass_response($plaindata);

            if (strcmp($_SESSION["PASS_REQ_SEQ"], $data["REQ_SEQ"]) != 0) {
                $error = "Request seq unmatched";
            }
            break;
    }
} else {
    $error = "응답데이터에 오류가 있습니다. : " . $enc_data;
}

$result = array("error" => $error, "data" => $data);
$resJSON = json_encode($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PASS 본인인증</title>
</head>

<body>
    <script>
        window.addEventListener("onclose", window.opener.passResponse(<?php echo $resJSON; ?>));
        window.close("_self");
    </script>
</body>

</html>