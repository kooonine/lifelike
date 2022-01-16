<?php
include_once('./_common.php');

$od_id = isset($od_id) ? preg_replace('/[^A-Za-z0-9\-_]/', '', strip_tags($od_id)) : 0;

if (!$is_member) {
    if (get_session('ss_orderview_uid') != $_GET['uid'])
        alert("직접 링크로는 주문서 조회가 불가합니다.\\n\\n주문조회 화면을 통하여 조회하시기 바랍니다.", G5_SHOP_URL);
}

// $sql = "select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
$sql = "select * from {$g5['g5_shop_rental_table']} where rt_id = '$od_id' ";
if ($is_member && !$is_admin)
    $sql .= " and mb_id = '{$member['mb_id']}' ";
$od = sql_fetch($sql);

if (!$od['rt_id'] || (!$is_member && md5($od['rt_id'] . $od['rt_time'] . $od['rt_ip']) != get_session('ss_orderview_uid'))) {
    alert("조회하실 주문서가 없습니다.", G5_SHOP_URL);
}

require_once(G5_PLUGIN_PATH . '/tcpdf-master/config/tcpdf_config_alt.php');
require_once(G5_PLUGIN_PATH . '/tcpdf-master/tcpdf.php');


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('LIFELIKE.CO.KR');
$pdf->SetTitle('리스계약서-' . $od['rt_id']);
$pdf->SetSubject('리스계약서');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
//require_once(G5_PLUGIN_PATH.'/tcpdf-master/lang/kor.php');

$l = array();
// PAGE META DESCRIPTORS --------------------------------------
$l['a_meta_charset'] = 'UTF-8';
$l['a_meta_dir'] = 'ltr';
$l['a_meta_language'] = 'ko';
// TRANSLATIONS --------------------------------------
$l['w_page'] = 'page';

$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('nanumbarungothicyethangul', '', 10);

// add a page
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// Set some content to print
$html = $config['cf_contract_cancel'];
$html = str_replace("{이름}", $od['rt_name'], $html);
$html = str_replace("{계약일자}", substr($od['rt_time'], 0, 10), $html);

if (isset($od['cust_file']) && $od['cust_file'] != "") {

    $cust_file = json_decode($od['cust_file'], true);
    for ($i = 0; $i < count($cust_file); $i++) {
        $imgL = '../data/file/order/' . $od['rt_id'] . '/' . $cust_file[$i]['file'];
        $img = '<img src="' . $imgL . '" style="border:1px solid #ccc;" width="300px">';

        $html = str_replace("{서명}", $img, $html);

        //$img2 = file_get_contents($imgL);
        //$pdf->Image('@' . $img2);
    }
}


// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('LIFELIKE_' . $od['rt_id'] . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
