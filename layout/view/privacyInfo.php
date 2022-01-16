<?php
ob_start();
?>
<div id="list-wrapper">

<?
 echo '<div>'.$config['cf_privacy'].'</div>';
?>
</div>
<?php
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>