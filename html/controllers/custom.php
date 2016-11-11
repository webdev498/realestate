<?php
$list_numb = $_GET['list_numb']?$_GET['list_numb']:'-';
$code = $_GET['code']?$_GET['code']:'-';
echo "<div id='somerandom' data-list-numb='$list_numb' data-code='$code'>"
."</div>";
?>