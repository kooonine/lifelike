<?php
$sub_menu = '400300';
include_once('./_common.php');

//auth_check($auth[substr($sub_menu,0,2)], "w");

$ca_id = trim($ca_id);
$it_name = trim(strip_tags($it_name));

if(!$ca_id && !$it_name)
    die('<p>상품의 분류를 선택하시거나 상품명을 입력하신 후 검색하여 주십시오.</p>');

$sql = " select a.ca_id, a.it_id, a.it_name, a.it_price,a.it_use,b.ca_name as ca_name1, c.ca_name as ca_name2, d.ca_name as ca_name3, it_item_type
           from {$g5['g5_shop_item_table']} a,
                 {$g5['g5_shop_category_table']} b,
                 {$g5['g5_shop_category_table']} c,
                 {$g5['g5_shop_category_table']} d
          where b.ca_id = left(a.ca_id,2)
                and c.ca_id = left(a.ca_id,4)
                and d.ca_id = left(a.ca_id,6)
                and a.it_id <> '$it_id' ";
if($ca_id)
    $sql .= " and ( a.ca_id like '$ca_id%' or a.ca_id2 like '$ca_id%' or a.ca_id3 like '$ca_id%' ) ";
/*
if ($is_admin == 'brand'){
    $sql_common .= " and a.ca_id3 != '' and a.ca_id3 = (select company_code from lt_member_company where mb_id = '". $member['mb_id']."' ) ";
}
*/

if($it_name)
    $sql .= " and a.it_name like '%$it_name%' ";

$sql .= " order by a.ca_id, a.it_name ";
$result = sql_query($sql);

$list = '';

for($i=0;$row=sql_fetch_array($result);$i++) {
    $sql2 = " select count(*) as cnt from {$g5['g5_shop_item_relation_table']} where it_id = '$it_id' and it_id2 = '{$row['it_id']}' ";
    $row2 = sql_fetch($sql2);
    if ($row2['cnt'])
        continue;

    $it_name = get_it_image($row['it_id'], 50, 50).' '.$row['it_name'];

    $list .= '<tr>';
    $list .= '<td>'.$row['it_id'].'</td>';
    $list .= '<td>'.($row['it_item_type'] == '0' ? '제품' : '리스').'</td>';
    $list .= '<td>'.$row['ca_name1'].($row['ca_name2'] ? ' > '.$row['ca_name2'] : '').($row['ca_name3'] ? ' > '.$row['ca_name3'] : '').'</td>';
    $list .= '<td style="text-align:left;">'.$it_name;
    $list .= '<td>'.number_format($row['it_price']).'</td>';
    $list .= '<input type="hidden" name="re_it_id[]" value="'.$row['it_id'].'">'.'</td>';
    $list .= '<td>'.($row['it_use'] ? '진열' : '진열안함').'</td>';
    $list .= '<td><button type="button" class="add_item btn_frmline">추가</button></td>';
    $list .= '</tr>'.PHP_EOL;
}

if($list){
?>
<div class="tbl_head01 tbl_wrap">
    <table>
    	<thead>
    		<tr>
                <th scope="col">상품코드</a></th>
                <th scope="col">분류</a></th>
                <th scope="col">카테고리</a></th>
                <th scope="col">상품명</a></th>
                <th scope="col">최종판매가<br/>(최종월리스료)</a></th>
                <th scope="col">진열<br/>상태</a></th>
                <th scope="col">추가</th>
    		</tr>
    	</thead>
    	<tbody>
			<?php echo $list;?>
    	</tbody>
	</table>
</div>
<?php } else {
    echo '<p>등록된 상품이 없습니다.';
}
?>