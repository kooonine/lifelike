<?
include_once('./_common.php');

$sql  = "select * from lt_sms_sendhistory where sh_no = '{$sh_no}'; ";
$sh = sql_fetch($sql);
?>
<table>
	<colgroup>
		<col class="grid_4">
		<col>
		<col class="grid_4">
		<col>
	</colgroup>
	<tr>
		<th scope="row">발송타입</th>
		<td><?=$sh['sh_sendtype']; ?></td>
		<th scope="row">앱(OS)</th>
		<td><?=($row['msg_type']=="")?"전체":$row['msg_type']; ?></td>
	</tr>
	<tr>
		<th scope="row">발송시간(등록시간)</th>
		<td><?=($sh['send_time'] != "0000-00-00 00:00:00")?$sh['send_time']."(".$sh['sh_datetime'].")":$sh['sh_datetime']; ?></td>
		<th scope="row">발송건수</th>
		<td><?=count(explode(",", $sh['dest_phone'])); ?>건</td>
	</tr>
	<tr>
		<th scope="row">메시지제목</th>
		<td colspan="3"><?=$sh['msg_title']; ?></td>
	</tr>
	<tr>
		<th scope="row">발신내용</th>
		<td colspan="3"><?=$sh['msg_body']; ?></td>
	</tr>
	<tr>
		<th scope="row">처리 결과 코드</th>
		<td colspan="3">
			<?
			switch ( $sh['result_code']) {
				case '100':
				echo '사용자 에러';
				break;
				case '200':
				echo '성공';
				break;
				case '300':
				echo '파라미터 에러';
				break;
				case '400':
				echo '기타 에러';
				break;
				case '500':
				echo '미등록 발신번호 차단';
				break;
				default:
				echo '실패';
				break;
			}
			?>
		</td>
	</tr>
</table>
