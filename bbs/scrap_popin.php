<?php
include_once('./_common.php');


if ($is_guest) {
    $href = './login.php?'.$qstr.'&amp;url='.urlencode('./board.php?bo_table='.$bo_table.'&amp;wr_id='.$wr_id);
    $href2 = str_replace('&amp;', '&', $href);
    echo <<<HEREDOC
    <script>
        alert('회원만 접근 가능합니다.');
        opener.location.href = '$href2';
        window.close();
    </script>
    <noscript>
    <p>회원만 접근 가능합니다.</p>
    <a href="$href">로그인하기</a>
    </noscript>
HEREDOC;
    exit;
}

if ($write['wr_is_comment'])
    alert_close('코멘트는 스크랩 할 수 없습니다.');

$share_url = $_GET['url'].'&wr_id='.$_GET['wr_id'];
include_once($member_skin_path.'/social_share.skin.php');


?>
