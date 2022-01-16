<?php 

// header('Content-Type: text/tab-separated-values');


$tsv = '';
$tsv.= 'id title price_pc price_mobile'.PHP_EOL; // 탭으로 구분. 마지막은 줄바꿈.
$tsv.= 'xd2 ladaso 15000 25000'.PHP_EOL;
$tsv.= 'xd3 ladaso2 25000 35000'.PHP_EOL;
$tsv.= 'xd4 ladaso3 35000 45000'.PHP_EOL;
$tsv.= 'xd5 ladaso3 45000 55000'.PHP_EOL;
file_put_contents('./output.tsv', $tsv);

$fp = fopen('./output2.tsv', 'w');
fputcsv($fp, array('id', 'title', 'price_pc', 'price_mobile'), "\t"); // PHP 5.4 미만은 [] → array()
fputcsv($fp, array('1xd2', 'ladaso', '15000', '25000'), "\t");
fputcsv($fp, array('1xd3', 'ladaso2', '25000', '35000'), "\t");
fputcsv($fp, array('1xd4', 'ladaso3', '35000', '45000'), "\t");
fputcsv($fp, array('1xd5', 'ladaso3', '45000', '55000'), "\t");
fclose($fp);

// $fp = fopen('./output.tsv', 'w');
// fputcsv($fp, array('id', 'title', 'price_pc', 'price_mobile'), "\t");
// {
// // DB에서 자료 읽어와 반복적으로 처리하는 부분에.
// fputcsv($fp, [$row['필드명'], $row['필드명'], $row['필드명'], $row['필드명']], "\t");
// }
// fclose($fp);

$fp = fopen('./0114test.txt', 'w');
fwrite($fp, "<<begin>>>\n");
fwrite($fp, "<<<mapid>>>estse\n");
fwrite($fp, "<<<pname>>>이름이 뭐에요 !!\n");
fwrite($fp, "<<<price>>>3190000\n");
fwrite($fp, "<<<cate1>>>해외여행\n");

fclose($fp);



?>

<html>
    <!-- <<<begin>>>
    <<<mapid>>>29D07
    <<<pname>>>[유럽단체배낭여행]2016 파리 로마 15박17일 버스동행 삽자루투어
    <<<price>>>3190000 -->
    <h1>확인</h1>
    <h2>저장</h2>
</html>



