<?php

/* 
 * This file is used when the "checkout" button is clicked. 
 * Expecially it is used to show the QR code. 
 */

/* get $order_id from orders.php */
$order_id = $_GET['order_id'];

/* connect to the 'items_list' database */
include 'db.php';

/* The first table display the order time and total price,
so we need to select order_time and total_price from 
'orders' table in database. */

try {
	$results = $db->query("SELECT order_time, total_price, user_id, paid
		FROM orders WHERE id ='$order_id' ");
	$results->setFetchMode(PDO::FETCH_ASSOC);
	} catch (Exception $e) {
		var_dump($e);
		exit;
}

$r_of_orders = $results->fetch();

/* The second table displays all the bought items.
It includes the name, price, quantity information of each 
bought item, so we need to select name, price, quantity from 'order_items' table in databse */

try {
	$results = $db->query("SELECT name, price, quantity
		FROM order_items WHERE order_id = '$order_id' ");
	$results->setFetchMode(PDO::FETCH_ASSOC);
	} catch (Exception $e) {
		var_dump($e);
		exit;
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">	
	<title>checkout(order detail)</title>
	<link rel="stylesheet" href="CSS/reset.css">
	<link rel="stylesheet" href="CSS/text.css">
	<link rel="stylesheet" href="CSS/960_24_col.css">
	<link rel="stylesheet" href="CSS/style.css">
	<link href='http://fonts.googleapis.com/css?family=Noto+Serif' rel='stylesheet' type='text/css'>
</head>
<body>
<div class="container_24">
	<header>
		<h1>Paperless Checkout</h1>
		<nav>
			<ul>
				<li><a href="index.php">list</a></li>
				<li><a href="adding.php">adding</a></li>
				<li><a href="orders.php" class="selected">checkout</a></li>
			</ul>
		</nav>
	</header>
	<?php 
	if ($r_of_orders['paid'] == 0) { 
	?>
		<p class="response">Dear customer, You didn't buy anything.</p>
	<?Php 
	} 
	else {
	?>
		<div class="classfix">
			<!-- display the left table -->
			<table id="table1_d"> 
				<tr>
				    <th class="table1_d_th">user id</th>
				    <td class="table1_d_td">
				    	<?php echo $r_of_orders['user_id']; ?> 
				    </td>
				</tr>
				<tr>
				    <th class="table1_d_th">date</th>
				    <td class="table1_d_td">
				    	<?php echo $r_of_orders['order_time']; ?> 
				    </td>
				</tr>
				<tr>
				    <th class="table1_d_th">total</th>
				    <td class="table1_d_td">
				    	<?php echo $r_of_orders['total_price']; ?>
				    </td>
				</tr>
			</table>
			
			<!-- display the right table  -->
			<table id="table2_d">
				<thead>
					<tr>
						<th id="table2_d_SN">SN</th>
						<th id="table2_d_name">name</th>
						<th id="table2_d_quantity">price</th>
						<th id="table2_d_price">quantity</th>
					</tr>
				</thead>
				<tbody><?php 
					$SN = 0;
					while ($r_of_order_id = $results->fetch() ): 
						$SN = $SN + 1; ?>								
						<tr>
							<td><?php echo $SN ?></td>
							<td><?php echo $r_of_order_id['name']; ?></td>
							<td><?php echo $r_of_order_id['price']; ?></td>
							<td><?php echo $r_of_order_id['quantity']; ?></td>
						</tr><?php
					endwhile; ?>
				</tbody>
			</table>
		</div>
		
		<!-- display the QR code of a specific order_id -->
		<img id="qr_img" src="qr.php?order_id=<?php echo $order_id; ?>" />
	<?php
	} 
	?>
</div> <!-- end container -->

</html> 