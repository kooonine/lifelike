<?php
$sub_menu = "200210";
include_once('./_common.php');
auth_check($auth[substr($sub_menu,0,2)], "r");

if(!empty($_POST)) {
    $sql_common = " from {$g5['member_table']} ";

    $sql_search = " where (1) ";
    if ($stx) {
        $sql_search .= " and ( ";
        switch ($sfl) {
            case 'mb_tel' :
            case 'mb_hp' :
                $sql_search .= " ({$sfl} like '%{$stx}') ";
                break;
            default :
                $sql_search .= " ({$sfl} like '{$stx}%') ";
                break;
        }
        $sql_search .= " ) ";
    }

    //$rows = $config['cf_page_rows'];
    $rows = 99999;

    if ($mb_id_list) {
        $mb_id_arr = explode(",", $mb_id_list);
        $sql_search .= " and mb_id in ('' ";

        for ($i = 0; $i < count($mb_id_arr); $i++) {
            $sql_search .= ", '".$mb_id_arr[$i]."'";
        }

        $sql_search .= ")";

        $rows = 99999;
    }

    $sql_search .= " and mb_leave_date = '' and mb_intercept_date = '' ";

    if (!$sst) {
        $sst = "mb_datetime";
        $sod = "desc";
    }

    $sql_order = " order by {$sst} {$sod} ";


    $sql = " select count(*) as cnt
        {$sql_common}
        {$sql_search}
        {$sql_order} ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];

    $total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
    if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
    $from_record = ($page - 1) * $rows; // 시작 열을 구함

    $sql = " select *
        {$sql_common}
        {$sql_search}
        {$sql_order}
        limit {$from_record}, {$rows} ";

    $result = sql_query($sql);
?>
	<?php if (!$mb_id_list) { ?>
	<!-- 회원검색 -->
	<table>
      <thead>
        <tr>
        <th scope="col">
            <label for="chkall" class="sound_only">전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
          <th scope="col">아이디</th>
          <th scope="col">고객명</th>
          <th scope="col">연락처</th>
          <th scope="col">휴대전화번호</th>
        </tr>
        </thead>
        <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
    ?>
          <tr>
            <td class="td_chk">
            	<label class="sound_only">전체</label>
                <input type="hidden" id="mb_id_<?php echo $i; ?>" name="mb_id[<?php echo $i; ?>]" value="<?php echo $row['mb_id']; ?>">
                <input type="checkbox" id="chk_<?php echo $i; ?>" name="chk[]" value="<?php echo $i; ?>" title="내역선택">
            </td>
            <td><?php echo $row['mb_id'] ?></td>
            <td><?php echo $row['mb_name'] ?></td>
            <td><?php echo $row['mb_tel'] ?></td>
            <td><?php echo $row['mb_hp'] ?></td>
          </tr>
	<?php }

	if($i == 0) echo "<tr><td colspan='5'>검색된 내용이 없습니다.</td></tr>";
	?>
		</tbody>
	</table>

	<?php //echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

	<?php } else {?>
	<!-- 선택 회원 목록 -->
	<table>
      <thead>
        <tr>
          <th scope="col">아이디</th>
          <th scope="col">고객명</th>
          <th scope="col">연락처</th>
          <th scope="col">휴대전화번호</th>
        </tr>
        </thead>
        <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
    ?>
          <tr>
            <td><?php echo $row['mb_id'] ?></td>
            <td><?php echo $row['mb_name'] ?></td>
            <td><?php echo $row['mb_tel'] ?></td>
            <td><?php echo $row['mb_hp'] ?></td>
          </tr>
	<?php }
	?>
		</tbody>
	</table>
	<?php }?>

<?php } else { ?>
	<table>
      <thead>
        <tr>
          <th scope="col"></th>
          <th scope="col">아이디</th>
          <th scope="col">고객명</th>
          <th scope="col">연락처</th>
          <th scope="col">휴대전화번호</th>
        </tr>
        </thead>
        <tbody>
        <tr>
        	<td colspan="5">검색된 내용이 없습니다.</td>
        </tr>
		</tbody>
	</table>

<?php } ?>
