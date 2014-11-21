<?php 

/* This file is used when the "checkout tab" is clicked.
 * Then this file is redireced to order_items.php file.
 * It is expecially used to generate the order's id.
 * After the order's id is generated, is quickly redirect to the order_items.php file.
 */

/* connect to the database */
include 'db.php';

/* When a user goes into checkout page, it means he wants to make 
a deal, and the deal will be identified by the time he enter the 
checkout page, it means the system will generate an order */

date_default_timezone_set("Asia/Singapore");
$date = date('Y-m-d H:i:s');

// generate an order id in 'orders' table in database
try {
$results = $db->exec("INSERT INTO orders (order_time) VALUES ('$date')");
} catch (Exception $e) {
	var_dump($e);
	exit;
}

/* We want to redirect this page to order_items.php to deal with 
all the concrete operations, so we need to pass the id in 'orders' table 
in database to the order_items.php page. 
We need to get the id */

try {
$results = $db->query("SELECT id FROM orders WHERE order_time ='$date' LIMIT 1");
$results->setFetchMode(PDO::FETCH_ASSOC);
} catch (Exception $e) {
	var_dump($e);
	exit;
}

$r = $results->fetch();
$id = $r['id'];

//redirect the page to order_items.php
header("Location:order_items.php?id=$id");
exit();

?> 