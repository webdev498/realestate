<?php 
session_start();

if(isset($_SESSION['buyerSave'])){ $savedBuyer = $_SESSION['buyerSave']; }
else{ $savedBuyer = ""; }
echo json_encode($savedBuyer);

?>