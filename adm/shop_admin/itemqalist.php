<?php
$sub_menu = '400660';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '상품문의';
include_once (G5_ADMIN_PATH.'/admin.head.php');

// $where = " where ";
$sql_search = "";

// TLqkf whsks Wkwmds
// if ($stx != "") {
//     if ($sfl != "") {
//         $sql_search .= " $where $sfl like '%$stx%' ";
//         $where = " and ";
//     }
//     if ($save_stx != $stx)
//         $page = 1;
// }

if ($sca != "") {
    $sql_search .= " and ca_id like '$sca%' ";
}

if ($sfl != "" && $stx !="") {
    $sql_search .= " AND $sfl like '%$stx%' ";
    
}
if ($save_stx != $stx)
    $page = 1;

if ($sfl == "")  $sfl = "it_name";

if (!$sst) {
    $sst = "iq_id";
    $sod = "desc";
}


if ($savereYn != $reYn) {
    $page = 1;
    $savereYn = $reYn;
}
if ($reYn =='Y') {
    $sql_search .= " and (NULLIF(a.iq_answer,'') IS NOT NULL) ";
} else if ($reYn =='N') {
    $sql_search .= " and (NULLIF(a.iq_answer,'') IS NULL) ";
}

$sql_common = "  from {$g5['g5_shop_item_qa_table']} a
                 left join {$g5['g5_shop_item_table']} b on (a.it_id = b.it_id)
                 left join {$g5['member_table']} c on (a.mb_id = c.mb_id) ";
$sql_common .= 'WHERE 1=1'.$sql_search;

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql  = " select *
          $sql_common
          order by $sst $sod, iq_id desc
          limit $from_record, $rows ";
$result = sql_query($sql);

//$qstr = 'page='.$page.'&amp;sst='.$sst.'&amp;sod='.$sod.'&amp;stx='.$stx;
$qstr .= ($qstr ? '&amp;' : '').'sca='.$sca.'&amp;save_stx='.$stx.'&amp;savereYn='.$savereYn.'&amp;reYn='.$reYn;

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$headers = array('no','문의유형','상품명','문의제목','문의내용','답변','이름','답변여부','번호','이메일','문의일','처리일');
$bodys = array('iq_category','it_name','iq_subject','iq_question','iq_answer','iq_name','iq_yn','iq_hp','iq_email','iq_time','reply_time');

$enc = new str_encrypt();

$headers = $enc->encrypt(json_encode_raw($headers));
$bodys = $enc->encrypt(json_encode_raw($bodys));
?>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	
<div class="local_ov01 local_ov">
    <?php echo $listall; ?>
    <span class="btn_ov01"><span class="ov_txt"> 전체 문의내역</span><span class="ov_num"> <?php echo $total_count; ?>건</span></span>
    &nbsp;
    <input type="button" value="엑셀다운로드" class="btn btn_02" onclick="qaExcel(0)">
    <input type="button" value="전체 엑셀다운로드" class="btn btn_02" onclick="qaExcel(1)">
</div>

<form name="flist" class="local_sch01 local_sch" id="flist">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="save_stx" value="<?php echo $stx; ?>">
<input type="hidden" name="savereYn" value="<?php echo $reYn; ?>">

<label for="sca" class="sound_only">분류선택</label>
<select name="sca" id="sca">
    <option value="">전체분류</option>
    <?php
    $sql1 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} order by ca_order, ca_id ";
    $result1 = sql_query($sql1);
    for ($i=0; $row1=sql_fetch_array($result1); $i++) {
        $len = strlen($row1['ca_id']) / 2 - 1;
        $nbsp = "";
        for ($i=0; $i<$len; $i++) $nbsp .= "&nbsp;&nbsp;&nbsp;";
        $selected = ($row1['ca_id'] == $sca) ? ' selected="selected"' : '';
        echo '<option value="'.$row1['ca_id'].'"'.$selected.'>'.$nbsp.$row1['ca_name'].'</option>'.PHP_EOL;
    }
    ?>
</select>

<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="it_name" <?php echo get_selected($sfl, 'it_name'); ?>>상품명</option>
    <option value="a.it_id" <?php echo get_selected($sfl, 'a.it_id'); ?>>상품코드</option>
</select>

<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" class="frm_input required" onkeydown="enterSearch();">
<input type="submit" value="검색" class="btn_submit">
<br> 답변 : 
<label><input type="radio" value="" id="reYn" name="reYn" <?php echo ($reYn == '')?'checked':''; ?>>전체</label>&nbsp;&nbsp;&nbsp;
<label><input type="radio" value="Y" id="reYn0" name="reYn" <?php echo ($reYn == 'Y')?'checked':''; ?>>Y</label>&nbsp;&nbsp;&nbsp;
<label><input type="radio" value="N" id="reYn1" name="reYn" <?php echo ($reYn == 'N')?'checked':''; ?>>N</label>&nbsp;&nbsp;&nbsp;

</form>

<form name="fitemqalist" method="post" action="./itemqalistupdate.php" onsubmit="return fitemqalist_submit(this);" autocomplete="off">
<input type="hidden" name="sca" value="<?php echo $sca; ?>">
<input type="hidden" name="sst" value="<?php echo $sst; ?>">
<input type="hidden" name="sod" value="<?php echo $sod; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="reYn" value="<?php echo $reYn; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="tbl_head01 tbl_wrap" id="itemqalist">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" style="width: 2%">
            <label for="chkall" class="sound_only">상품문의 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col" style="width: 4%">문의유형</th>
        <th scope="col" style="width: 23%"><?php echo subject_sort_link('it_name'); ?>상품명</a></th>
        <th scope="col" style="width: 29%"><?php echo subject_sort_link('iq_subject'); ?>질문</a></th>
        <th scope="col" style="width: 6%"><?php echo subject_sort_link('mb_name'); ?>이름</a></th>
        <th scope="col" style="width: 4%"><?php echo subject_sort_link('iq_answer'); ?>답변</a></th>
        <th scope="col" style="width: 11%">번호</th>
        <th scope="col" style="width: 11%">이메일</th>
        <th scope="col" style="width: 5%">문의일</th>
        <th scope="col" style="width: 5%">처리일</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $row['iq_subject'] = cut_str($row['iq_subject'], 30, "...");
        $href = G5_SHOP_URL.'/item.php?it_id='.$row['it_id'];
        $name = get_sideview($row['mb_id'], get_text($row['iq_name']), $row['mb_email'], $row['mb_homepage']);
        $answer = $row['iq_answer'] ? 'Y' : 'N';
        $iq_question = get_view_thumbnail(conv_content($row['iq_question'], 1), 300);
        $iq_answer = $row['iq_answer'] ? get_view_thumbnail(conv_content($row['iq_answer'], 1), 300) : NULL;

        $bg = 'bg'.($i%2);
     ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['iq_subject']) ?> 상품문의</label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i; ?>">
            <input type="hidden" id="iq_id_<?= $i?>" name="iq_id[<?php echo $i; ?>]" value="<?php echo $row['iq_id']; ?>">
            <input type="hidden" id="iq_question_<?= $i?>" name="iq_question[<?php echo $i; ?>]" value="<?php echo $row['iq_question']; ?>">
            <input type="hidden" id="iq_subject_<?= $i?>" name="iq_subject[<?php echo $i; ?>]" value="<?php echo $row['iq_subject']; ?>">
        </td>
        <td><?php echo $row['iq_category']; ?></td>
        <td class="td_left"><a href="<?php echo $href; ?>"><?php echo get_it_image($row['it_id'], 50, 50); ?> <?php echo cut_str($row['it_name'],27); ?></a></td>
        <td class="td_left">
            <a href="#" class="qa_href" onclick="return false;" target="<?php echo $i; ?>"><?php echo get_text($row['iq_subject']); ?> <span class="tit_op">열기</span></a>
            <div id="qa_div<?php echo $i; ?>" class="qa_div" style="display:none;">
                <div class="qa_q">
                    <strong>문의내용</strong>
                    <?php echo $iq_question; ?>
                    
                </div>
                <div class="qa_a">
                <strong>답변</strong>
                    <textarea name="iq_answer_<?= $i?>" id="iq_answer_<?= $i?>" style ="width:95%; height:50px;" placeholder="답변이 등록되지 않았습니다."><?php if($iq_answer) echo $iq_answer; else  echo '안녕하세요 고객님 

감사합니다.' ; ?></textarea>
                    <input type="button" onclick="ansAjax(<?= $i?>)" value ="등록">
                <!-- <input type="text" value ='<?php echo $iq_answer; ?>'> -->
                <!-- <?php echo $iq_answer; ?> -->
                </div>
            </div>
        </td>
        <td><?php echo $name; ?></td>
        <td class="td_boolean"><?php echo $answer; ?></td>
        <td><?php echo $row['iq_hp']; ?></td>
        <td><?php echo cut_str($row['iq_email'],22); ?></td>
        <td><?php echo $row['iq_time']; ?></td>
        <td><?php if ($row['iq_reply_time'] =='0000-00-00 00:00:00') echo '-'; else echo $row['iq_reply_time']; ?></td>
        <!-- <td class="td_mng td_mng_s"> -->
            <!-- <a href="./itemqaform.php?w=u&amp;iq_id=<?php echo $row['iq_id']; ?>&amp;<?php echo $qstr; ?>" class="btn btn_03"><span class="sound_only"><?php echo get_text($row['iq_subject']); ?> </span>수정</a> -->
        <!-- </td> -->
    </tr>
    <?php
    }
    if ($i == 0) {
        echo '<tr><td colspan="6" class="empty_table"><span>자료가 없습니다.</span></td></tr>';
    }
    ?>
    </tbody>
    </table>
</div>

<div class="">
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02">
</div>
</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

</div></div></div>

<script>
function ansAjax(e) {

    let iq_answer = $('#iq_answer_'+e).val();
    let iq_id = $('#iq_id_'+e).val();
    let iq_question = $('#iq_question_'+e).val();
    let iq_subject = $('#iq_subject_'+e).val();

    $.ajax({
        url: "./itemqaformupdate.php",
        method: "POST",
        data: {
            'iq_answer' : iq_answer,
            'iq_subject' : iq_subject,
            'iq_question' :iq_question,
            'iq_id':iq_id,
            'w' :'u',
            'qaAjax' : 1 
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function(result) {
            alert('등록되었습니다.');
            location.reload();
        }
    });
}
function fitemqalist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed  == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}

$(function(){
    $(".qa_href").click(function(){
        var $content = $("#qa_div"+$(this).attr("target"));
        $(".qa_div").each(function(index, value){
            if ($(this).get(0) == $content.get(0)) { // 객체의 비교시 .get(0) 를 사용한다.
                $(this).is(":hidden") ? $(this).show() : $(this).hide();
            } else {
                $(this).hide();
            }
        });
    });
});
function enterSearch() {
    if (window.event.keyCode == 13) {
    	document.getElementById('flist').submit();
    }
}
function qaExcel(e) {
    if (!e) {
        if (!is_checked("chk[]")) {
		    alert("하나 이상 선택하세요.");
		    return false;
        }
    }
    var $form = $('<form></form>');     
    $form.attr('action', '<?=G5_ADMIN_URL?>/ajax.excel_download.itemqalist.php');
    $form.attr('method', 'post');
    $form.appendTo('body')
    var excel_obj = '';
    var iq_id = null;
    var excel_sql = "SELECT *, IF(NULLIF(a.iq_answer,'') IS NOT NULL,'Y','N') AS iq_yn, IF(iq_reply_time='0000-00-00 00:00:00','-',iq_reply_time) AS reply_time FROM lt_shop_item_qa a LEFT JOIN lt_shop_item b ON (a.it_id = b.it_id) order by <?= $sst ?> <?= $sod ?>, iq_id desc";
    if (!e) { 
        $("input[name='chk[]']:checked").each(function (index) {
            iq_id = $(`#iq_id_${$(this).val()}`).val();
            if (index != 0) {
                excel_obj += ',';
            }   
            excel_obj += iq_id;            
        });
        excel_sql = "SELECT *, IF(NULLIF(a.iq_answer,'') IS NOT NULL,'Y','N') AS iq_yn, IF(iq_reply_time='0000-00-00 00:00:00','-',iq_reply_time) AS reply_time FROM lt_shop_item_qa a LEFT JOIN lt_shop_item b ON (a.it_id = b.it_id) WHERE iq_id IN ("+excel_obj+") order by <?= $sst ?> <?= $sod ?>, iq_id desc";
    }

    var exceldata = $('<input type="hidden" value="'+excel_sql+'" name="exceldata">');
    var headerdata = $('<input type="hidden" value="<?=$headers?>" name="headerdata">');
    var bodydata = $('<input type="hidden" value="<?=$bodys?>" name="bodydata">');
    var excelnamedata = $('<input type="hidden" value="상품문의" name="excelnamedata">');
    $form.append(exceldata).append(headerdata).append(bodydata).append(excelnamedata);
    $form.submit();

}
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
