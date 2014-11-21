<?php 
include('phpqrcode/qrlib.php');

$order_id = $_GET['order_id'];

$url = "http://localhost/dissertation/cash_register_system/order_detail.php?order_id=$order_id";

QRcode::png($url, false ,QR_ECLEVEL_L, 6);
?>