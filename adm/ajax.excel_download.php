<?
include_once('./_common.php');
$enc = new str_encrypt();
$excel_sql = $enc->decrypt($exceldata);
$headers = json_decode($enc->decrypt($headerdata), true);
$headers2 = json_decode($enc->decrypt($headerdata2), true);
$bodys = json_decode($enc->decrypt($bodydata), true);
$summaries = json_decode($enc->decrypt($summarydata), true);

$widths = array();
for ($i = 0; $i < count($headers); $i++) {
	array_push($widths, 15);
}
$header_bgcolor = 'FFABCDEF';
$last_char = column_char(count($headers) - 1);
$rows = array();
// 여기서 쿼리문을 좀만 수정해볼까
if ($selectCheckIt && $selectCheckIt != '') { 
	$excel_sql = " select b.ca_name as ca_name1, c.ca_name as ca_name2, d.ca_name as ca_name3, a.*,e.io_order_no AS sap_code,e.io_sapcode_color_gz AS io_sapcode_color_gz,e.io_stock_qty AS io_stock_qty,e.io_hoching AS io_hoching
		, if(a.it_use = '1','진열','진열안함') as it_use_name
		, if(a.it_soldout = '1','Y','N') as it_soldout_name
		from lt_shop_item a 
			   LEFT JOIN lt_shop_item_option AS e ON a.it_id = e.it_id,
				lt_shop_category b,
				lt_shop_category c,
				lt_shop_category d
		  where (b.ca_id = left(a.ca_id,2)
			   and   c.ca_id = left(a.ca_id,4)
			   and   d.ca_id = left(a.ca_id,6)
			   and   a.ca_id3 = ''
			   ) AND a.it_id IN ($selectCheckIt) 
		group by a.it_id
		order by it_time desc
		";
}
if ($selectCheck && $selectCheck != '') {
	$odJsonCheck =  stripslashes($odJsonCheck);
	$odArray = json_decode($odJsonCheck, true);

	foreach ($odArray as $key => $value) { 
		$countSql = "SELECT count(*) as cnt FROM lt_shop_cart WHERE od_id = $key";
		$count = sql_fetch($countSql);
		$odCount = $count['cnt'];
		if ($value <$odCount) return alert('한 주문건 내 특정상품 다운로드는 불가합니다. 주문건 내 모든 상품을 선택해주세요.'); 
	}


	$excel_sql = 
	"SELECT a.*,b.ct_delivery_company,b.ct_invoice,b.ct_id, b.it_id, b.it_name, b.ct_option, b.ct_qty, (b.ct_price + b.io_price)  as opt_price, b.ct_status, b.ct_status_claim, concat( '(',a.od_b_zip1,a.od_b_zip2,')','  ',a.od_b_addr1,'  ', a.od_b_addr2,'  ',a.od_b_addr3) AS addrTotal
	, concat(a.od_type,'-',left(a.od_id,8),'-',right(a.od_id,6)) as disp_od_id
	, concat(od_delivery_company,' ',od_invoice) as str_od_invoice
	, concat(ct_delivery_company,' ',ct_invoice) as str_ct_invoice
	, (a.od_send_cost + a.od_send_cost2) AS send_cost
	, (SELECT IF(SUM(c.cp_price) > 0, SUM(c.cp_price), 0) FROM lt_shop_coupon_log AS c WHERE c.od_id=a.od_id AND c.ct_id=b.ct_id) AS tot_it_cp_price
	, c.io_order_no AS sap_code, c.io_sapcode_color_gz AS io_sapcode_color_gz, concat('(',REPLACE(c.io_sapcode_color_gz,'_',''),')') AS io_sapcodecolorgz
	, (od_coupon_cancel+od_cart_coupon_cancel) AS total_coupon_all, (od_coupon_ori+od_cart_coupon_ori) AS total_coupon_final
	, od_receipt_price_ori,od_receipt_refund_price_ori, (od_receipt_price_ori-od_receipt_refund_price_ori) AS cancel_price_ori
	, CASE 
		WHEN (ct_status ='주문취소') THEN 0
		WHEN (ct_status ='반품완료') THEN 0
	ELSE ct_cart_coupon_price
	END AS ct_cart_coupon_price
	, CASE 
		WHEN (ct_status ='주문취소') THEN 0
		WHEN (ct_status ='반품완료') THEN 0
	ELSE cp_price
	END AS cp_price
	, CASE 
		WHEN (ct_status ='주문취소') THEN 0
		WHEN (ct_status ='반품완료') THEN 0
	ELSE ct_cart_price_ori
	END AS ct_cart_price_ori

	FROM lt_shop_order as a
	LEFT JOIN lt_shop_cart as b
	ON a.od_id = b.od_id and b.io_type = '0'
	LEFT JOIN lt_shop_item_option as c ON b.it_id = c.it_id 
	 where  a.od_type = 'O'  and  (a.company_code is null or a.company_code = '') AND b.ct_id IN ($selectCheck) 
	ORDER BY a.od_time desc, b.io_type asc, b.ct_id asc";
}

$excel_result = sql_query($excel_sql);
$row_name_cell = null;
$row_name_count = 0;
$tmp_od_id = null;
$tmp_row_id = 0;
$odComparison;

$script_pathinfo = pathinfo($_SERVER['HTTP_REFERER']);
$meger_st = "";
$merNum = 0;
$mergeCell = array();
$sqlcount = sql_num_rows($excel_result);

for ($i = 1; $ex_data_row = sql_fetch_array($excel_result); $i++) {
	if($itemlistCheck==1) {
		$finditemSql = sql_query("SELECT * FROM lt_shop_item_finditem WHERE it_id={$ex_data_row['it_id']}");
		for ($j=0; $firow=sql_fetch_array($finditemSql); $j++) { 
			if ($firow['fi_id'] == 1) {
				if (!$ex_data_row['f_size'] || $ex_data_row['f_size']=='') $ex_data_row['f_size'] = $firow['fi_contents'];
				else $ex_data_row['f_size'] = $ex_data_row['f_size'] .'/'.$firow['fi_contents'];
			}
			if ($firow['fi_id'] == 2) {
				if (!$ex_data_row['f_season'] || $ex_data_row['f_season']=='') $ex_data_row['f_season'] = $firow['fi_contents'];
				else $ex_data_row['f_season'] = $ex_data_row['f_season'] .'/'.$firow['fi_contents'];
			}
			if ($firow['fi_id'] == 3) {
				if (!$ex_data_row['f_filling'] || $ex_data_row['f_filling']=='') $ex_data_row['f_filling'] = $firow['fi_contents'];
				else $ex_data_row['f_filling'] = $ex_data_row['f_filling'] .'/'.$firow['fi_contents'];
			}
			if ($firow['fi_id'] == 4) {
				if (!$ex_data_row['f_style'] || $ex_data_row['f_style']=='') $ex_data_row['f_style'] = $firow['fi_contents'];
				else $ex_data_row['f_style'] = $ex_data_row['f_style'] .'/'.$firow['fi_contents'];
			}
			if ($firow['fi_id'] == 5) {
				if (!$ex_data_row['f_fabric'] || $ex_data_row['f_fabric']=='') $ex_data_row['f_fabric'] = $firow['fi_contents'];
				else $ex_data_row['f_fabric'] = $ex_data_row['f_fabric'] .'/'.$firow['fi_contents'];
			}
		}
		$subSql = sql_fetch("SELECT * FROM lt_shop_item_sub WHERE it_id={$ex_data_row['it_id']} LIMIT 1");
		$ex_data_row['its_final_price'] = $subSql['its_final_price'];
		$ex_data_row['its_price'] = $subSql['its_price'];
		if ($selectCheckIt && $selectCheckIt != ''); 
		else $ex_data_row['it_name'] = substr($ex_data_row['it_name'],0,strpos($ex_data_row['it_name'],'('));
		// $ex_data_row['it_name'] = substr($ex_data_row['it_name'],0,strpos($ex_data_row['it_name'],'('));
		$ex_data_row['io_sapcode_color_gz'] =  str_replace('_','',$ex_data_row['io_sapcode_color_gz']);
		// dd($ex_data_row['it_id']);

		// dd($ex_data_row['it_info_value']);
		$article = json_decode($ex_data_row['it_info_value'], true);
		if (!empty($article)) {
			foreach($article as $at) {
				if ($at['name'] =='제품소재 및 충전재') $ex_data_row['value1'] = $at['value']; 
				if ($at['name'] =='색상') $ex_data_row['value2'] = $at['value']; 
				if ($at['name'] =='사이즈') $ex_data_row['value3'] = $at['value']; 
				if ($at['name'] =='제조사') $ex_data_row['value4'] = $at['value']; 
				if ($at['name'] =='제조국') $ex_data_row['value5'] = $at['value']; 
				if ($at['name'] =='세탁방법 및 주의사항') $ex_data_row['value6'] = $at['value']; 
				if ($at['name'] =='제품구성') $ex_data_row['value7'] = $at['value']; 
				if ($at['name'] =='품질보증기준') $ex_data_row['value8'] = $at['value']; 
				if ($at['name'] =='A/S 책임자와 전화번호') $ex_data_row['value9'] = $at['value']; 
			}
		}
		// if ($ex_data_row['ca_id']==101010) $ex_data_row['ca_id'] ='침구세트';
		// if ($ex_data_row['ca_id']==101020) $ex_data_row['ca_id'] ='이불솜';
		// if ($ex_data_row['ca_id']==102010) $ex_data_row['ca_id'] ='베개솜';
		// if ($ex_data_row['ca_id']==101030) $ex_data_row['ca_id'] ='차렵이불';
		// if ($ex_data_row['ca_id']==101040) $ex_data_row['ca_id'] ='누비이불';
		// if ($ex_data_row['ca_id']==101050) $ex_data_row['ca_id'] ='홑이불';
		// if ($ex_data_row['ca_id']==102030) $ex_data_row['ca_id'] ='토퍼';
		// if ($ex_data_row['ca_id']==102020) $ex_data_row['ca_id'] ='패드';
		// if ($ex_data_row['ca_id']==101060) $ex_data_row['ca_id'] ='스프레드';
		// if ($ex_data_row['ca_id']==103010) $ex_data_row['ca_id'] ='커버세트';
		// if ($ex_data_row['ca_id']==103020) $ex_data_row['ca_id'] ='이불커버';
		// if ($ex_data_row['ca_id']==103030) $ex_data_row['ca_id'] ='매트리스커버';
		// if ($ex_data_row['ca_id']==103040) $ex_data_row['ca_id'] ='베개커버';
		// if ($ex_data_row['ca_id']==103050) $ex_data_row['ca_id'] ='플랫시트';
		// if ($ex_data_row['ca_id']==103060) $ex_data_row['ca_id'] ='프로텍터';
		// if ($ex_data_row['ca_id']==104010) $ex_data_row['ca_id'] ='쿠션/쿠션커버';
		// if ($ex_data_row['ca_id']==104020) $ex_data_row['ca_id'] ='타월';
		// if ($ex_data_row['ca_id']==104030) $ex_data_row['ca_id'] ='담요';

		if ($ex_data_row['ca_id']==101010) $ex_data_row['ca_id'] ='이불,이불솜';
		if ($ex_data_row['ca_id']==101020) $ex_data_row['ca_id'] ='이불,차렵이불';
		if ($ex_data_row['ca_id']==102010) $ex_data_row['ca_id'] ='베개솜';
		if ($ex_data_row['ca_id']==101030) $ex_data_row['ca_id'] ='이불,차렵이불SET';
		if ($ex_data_row['ca_id']==101040) $ex_data_row['ca_id'] ='이불,누비이불';
		if ($ex_data_row['ca_id']==101050) $ex_data_row['ca_id'] ='이불,스프레드';
		if ($ex_data_row['ca_id']==101060) $ex_data_row['ca_id'] ='이불,홑이불';

		if ($ex_data_row['ca_id']==10201010) $ex_data_row['ca_id'] ='베개/토퍼,베개솜,일반';
		if ($ex_data_row['ca_id']==102010) $ex_data_row['ca_id'] ='베개/토퍼,베개솜,메모리폼 베개';
		if ($ex_data_row['ca_id']==102020) $ex_data_row['ca_id'] ='베개/토퍼,토퍼';

		if ($ex_data_row['ca_id']==10301010) $ex_data_row['ca_id'] ='커버/패드,호텔베딩,이불커버';
		if ($ex_data_row['ca_id']==10301020) $ex_data_row['ca_id'] ='커버/패드,호텔베딩,베개커버';
		if ($ex_data_row['ca_id']==10301030) $ex_data_row['ca_id'] ='커버/패드,호텔베딩,커버SET';
		if ($ex_data_row['ca_id']==10301040) $ex_data_row['ca_id'] ='커버/패드,호텔베딩,매트리스커버';
		if ($ex_data_row['ca_id']==10301050) $ex_data_row['ca_id'] ='커버/패드,호텔베딩,패드';
		if ($ex_data_row['ca_id']==10302010) $ex_data_row['ca_id'] ='커버/패드,모던,이불커버';
		if ($ex_data_row['ca_id']==10302020) $ex_data_row['ca_id'] ='커버/패드,모던,베개커버';
		if ($ex_data_row['ca_id']==10302030) $ex_data_row['ca_id'] ='커버/패드,모던,커버SET';
		if ($ex_data_row['ca_id']==10302040) $ex_data_row['ca_id'] ='커버/패드,모던,매트리스커버';
		if ($ex_data_row['ca_id']==10302050) $ex_data_row['ca_id'] ='커버/패드,모던,패드';

		if ($ex_data_row['ca_id']==10303010) $ex_data_row['ca_id'] ='커버/패드,베이직,이불커버';
		if ($ex_data_row['ca_id']==10303020) $ex_data_row['ca_id'] ='커버/패드,베이직,베개커버';
		if ($ex_data_row['ca_id']==10303030) $ex_data_row['ca_id'] ='커버/패드,베이직,커버SET';
		if ($ex_data_row['ca_id']==10303040) $ex_data_row['ca_id'] ='커버/패드,베이직,매트리스커버';
		if ($ex_data_row['ca_id']==10303050) $ex_data_row['ca_id'] ='커버/패드,베이직,패드';

		if ($ex_data_row['ca_id']==10304010) $ex_data_row['ca_id'] ='커버/패드,내추럴,이불커버';
		if ($ex_data_row['ca_id']==10304020) $ex_data_row['ca_id'] ='커버/패드,내추럴,베개커버';
		if ($ex_data_row['ca_id']==10304030) $ex_data_row['ca_id'] ='커버/패드,내추럴,커버SET';
		if ($ex_data_row['ca_id']==10304040) $ex_data_row['ca_id'] ='커버/패드,내추럴,매트리스커버';
		if ($ex_data_row['ca_id']==10304050) $ex_data_row['ca_id'] ='커버/패드,내추럴,패드';

		if ($ex_data_row['ca_id']==10305010) $ex_data_row['ca_id'] ='커버/패드,클래식,이불커버';
		if ($ex_data_row['ca_id']==10305020) $ex_data_row['ca_id'] ='커버/패드,클래식,베개커버';
		if ($ex_data_row['ca_id']==10305030) $ex_data_row['ca_id'] ='커버/패드,클래식,커버SET';
		if ($ex_data_row['ca_id']==10305040) $ex_data_row['ca_id'] ='커버/패드,클래식,매트리스커버';
		if ($ex_data_row['ca_id']==10305050) $ex_data_row['ca_id'] ='커버/패드,클래식,패드';



		if ($ex_data_row['ca_id']==104010) $ex_data_row['ca_id'] ='홈데코,쿠션/쿠션커버';
		if ($ex_data_row['ca_id']==104020) $ex_data_row['ca_id'] ='홈데코,리빙';

		if ($ex_data_row['ca_id']==104110) $ex_data_row['ca_id'] ='키즈,차렵이불';
		if ($ex_data_row['ca_id']==104120) $ex_data_row['ca_id'] ='키즈,베개커버';
		if ($ex_data_row['ca_id']==104130) $ex_data_row['ca_id'] ='키즈,패드';
		if ($ex_data_row['ca_id']==104140) $ex_data_row['ca_id'] ='키즈,홑이불';
		if ($ex_data_row['ca_id']==104150) $ex_data_row['ca_id'] ='키즈,스프레드';
		if ($ex_data_row['ca_id']==104160) $ex_data_row['ca_id'] ='키즈,리빙';

		if ($ex_data_row['ca_id']==10421010) $ex_data_row['ca_id'] ='수입침구,SHERIDAN,이불커버';
		if ($ex_data_row['ca_id']==10421020) $ex_data_row['ca_id'] ='수입침구,SHERIDAN,베개커버';
		if ($ex_data_row['ca_id']==10421030) $ex_data_row['ca_id'] ='수입침구,SHERIDAN,매트리스커버';
		if ($ex_data_row['ca_id']==10421040) $ex_data_row['ca_id'] ='수입침구,SHERIDAN,커버SET';
		if ($ex_data_row['ca_id']==10422010) $ex_data_row['ca_id'] ='수입침구,RALPH LAUREN HOME,이불커버';
		if ($ex_data_row['ca_id']==10422020) $ex_data_row['ca_id'] ='수입침구,RALPH LAUREN HOME,베개커버';
		if ($ex_data_row['ca_id']==10422030) $ex_data_row['ca_id'] ='수입침구,RALPH LAUREN HOME,패드';
		if ($ex_data_row['ca_id']==10422040) $ex_data_row['ca_id'] ='수입침구,RALPH LAUREN HOME,홈데코';
		if ($ex_data_row['ca_id']==10423010) $ex_data_row['ca_id'] ='수입침구,GRAZIANO,커버SET';
		if ($ex_data_row['ca_id']==10424010) $ex_data_row['ca_id'] ='수입침구,RINGSTED DUN,이불솜';




		if ($ex_data_row['it_brand']=='SOFRAUM') $ex_data_row['it_brand'] ='소프라움';
		if ($ex_data_row['it_brand']=='GRAZIANO') $ex_data_row['it_brand'] ='그라치아노';
		if ($ex_data_row['it_brand']=='RALPH LAUREN home') $ex_data_row['it_brand'] ='랄프로렌홈';
		if ($ex_data_row['it_brand']=='ROSALIA') $ex_data_row['it_brand'] ='로자리아';
		if ($ex_data_row['it_brand']=='RINGSTED DUN') $ex_data_row['it_brand'] ='링스티드던';
		if ($ex_data_row['it_brand']=='BEONTRE') $ex_data_row['it_brand'] ='베온트레';
		if ($ex_data_row['it_brand']=='SHERIDAN') $ex_data_row['it_brand'] ='쉐르단';
		if ($ex_data_row['it_brand']=='LIFELIKE') $ex_data_row['it_brand'] ='LIFELIKE';
		if ($ex_data_row['it_brand']=='LBL MAISON') $ex_data_row['it_brand'] ='LBL';
		if ($ex_data_row['it_brand']=='VERAWANG HOME') $ex_data_row['it_brand'] ='베라왕홈';
	}

	if (!empty($ex_row)) {
		array_push($rows, $ex_row);
	}
	// if ($odComparison == $ex_data_row['od_id']) {
	// 	// $ex_data_row['od_id'] = '';
	// 	$ex_data_row['od_coupon'] = '';
	// } else {
	// 	$odComparison = $ex_data_row['od_id'];
	// }
	if ($script_pathinfo['filename'] =='orderlist') {
		if ($odComparison == $ex_data_row['od_id']) {
			$merNum = $merNum + 1;
			// $ex_data_row['od_id'] = '0';
			$ex_data_row['od_coupon'] = '0';
			$ex_data_row['od_receipt_point'] = '0';
			$ex_data_row['od_receipt_price'] = '0';

			$ex_data_row['total_coupon_all'] = '0';
			$ex_data_row['total_coupon_final'] = '0';
			$ex_data_row['od_receipt_price_ori'] = '0';
			$ex_data_row['od_receipt_refund_price_ori'] = '0';
			$ex_data_row['cancel_price_ori'] = '0';

			if ($i==$sqlcount) {
				$merc = count($rows);
				$meger_st = "V". ($merc+2-$merNum) . ":V".($merc+2) ;
				array_push($mergeCell, $meger_st);
				$meger_st = "W". ($merc+2-$merNum) . ":W".($merc+2) ;
				array_push($mergeCell, $meger_st);
				$meger_st = "Z". ($merc+2-$merNum) . ":Z".($merc+2) ;
				array_push($mergeCell, $meger_st);
				$meger_st = "AB". ($merc+2-$merNum) . ":AB".($merc+2) ;
				array_push($mergeCell, $meger_st);
				$meger_st = "AC". ($merc+2-$merNum) . ":AC".($merc+2) ;
				array_push($mergeCell, $meger_st);
				$meger_st = "AD". ($merc+2-$merNum) . ":AD".($merc+2) ;
				array_push($mergeCell, $meger_st);
				// $meger_st = "AB". ($merc+2-$merNum) . ":AB".($merc+2) ;
				// array_push($mergeCell, $meger_st);
			}
		} else {
			if ($merNum !=0) {
				$merc = count($rows);
				$meger_st = "V". ($merc+1-$merNum) . ":V".($merc+1) ;
				array_push($mergeCell, $meger_st);
				$meger_st = "W". ($merc+1-$merNum) . ":W".($merc+1) ;
				array_push($mergeCell, $meger_st);
				$meger_st = "Z". ($merc+1-$merNum) . ":Z".($merc+1) ;
				array_push($mergeCell, $meger_st);
				$meger_st = "AB". ($merc+1-$merNum) . ":AB".($merc+1) ;
				array_push($mergeCell, $meger_st);
				$meger_st = "AC". ($merc+1-$merNum) . ":AC".($merc+1) ;
				array_push($mergeCell, $meger_st);
				$meger_st = "AD". ($merc+1-$merNum) . ":AD".($merc+1) ;
				array_push($mergeCell, $meger_st);
				// $meger_st = "AB". ($merc+1-$merNum) . ":AB".($merc+1) ;
				// array_push($mergeCell, $meger_st);
			}
			$merNum = 0;
		}
		$odComparison = $ex_data_row['od_id'];
	}

	$row_name_count = 0;
	$ex_row = array();
	$tmp_od_id = $ex_data_row['od_id'];
	$tmp_row_id++;

	array_push($ex_row, ' ' . $tmp_row_id);	// A열 순번 추가

	for ($j = 1; $j < count($bodys); $j++) {
		$cell_key = $bodys[$j];
		$cell_data = stripslashes($ex_data_row[$cell_key]);
		if ($cell_key == 'opt_price') $cell_data = $cell_data * stripslashes($ex_data_row['ct_qty']);
		array_push($ex_row, $cell_data);
	}
	if ($tmp_od_id != $ex_data_row['od_id']) {
		// if (!empty($ex_row)) {
		// 	if ($row_name_count > 0) $ex_row[$row_name_cell] = sprintf("%s 외 %d", $ex_row[$row_name_cell], $row_name_count);
		// 	array_push($rows, $ex_row);
		// }

		// $row_name_count = 0;
		// $ex_row = array();
		// $tmp_od_id = $ex_data_row['od_id'];
		// $tmp_row_id++;

		// array_push($ex_row, ' ' . $tmp_row_id);	// A열 순번 추가

		// for ($j = 1; $j < count($bodys); $j++) {
		// 	$cell_key = $bodys[$j];
		// 	$cell_data = stripslashes($ex_data_row[$cell_key]);
		// 	if ($cell_key == 'opt_price') $cell_data = $cell_data * stripslashes($ex_data_row['ct_qty']);
		// 	array_push($ex_row, $cell_data);
		// }
	} else {
		// $row_name_count++;
		// for ($j = 1; $j < count($bodys); $j++) {
		// 	$cell_key = $bodys[$j];
		// 	$cell_data = stripslashes($ex_data_row[$cell_key]);
		// 	if ($cell_key == 'it_name' && empty($row_name_cell)) $row_name_cell = $j;
		// 	if ($cell_key == 'opt_price') $cell_data = $cell_data * stripslashes($ex_data_row['ct_qty']);
		// 	if (in_array($cell_key, $summaries)) {
		// 		$ex_row[$j] = $ex_row[$j] * 1 + $cell_data * 1;
		// 	}
		// }
	}
}
if (!empty($ex_row)) array_push($rows, $ex_row);

function column_char($i)
{
	$prefix = '';
if ( $i >= 15 ) {
	$prefix = 'A';
	$i = $i - 15;
}
	return $prefix . chr(65 + $i);
}

function merge_cell($str){
	$cell = '';
	preg_match("/[^\:\.]+/", $str,$cv);
	$cell = $cv[0];

	return $cell;
}
include_once(G5_LIB_PATH . '/PHPExcel.php');


if (!$headers2) $data = array_merge(array($headers), $rows);
else $data = array_merge(array($headers),array($headers2), $rows);

// $script_pathinfo = pathinfo($_SERVER['HTTP_REFERER']);

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0)->getStyle("A1:${last_char}1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
$excel->setActiveSheetIndex(0)->getStyle("A:$last_char")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
foreach ($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension(column_char($i))->setWidth($w);

foreach ($mergeCell as $mi => $mc) $excel->setActiveSheetIndex(0)->mergeCells($mc)->setCellValue(merge_cell($mc),'1');
$excel->getActiveSheet()->fromArray($data, NULL, 'A1');

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=" . date("ymd", time()) . "_" . $script_pathinfo['filename'] . ".xls");
header("Cache-Control: max-age=0");

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
$writer->save('php://output');
