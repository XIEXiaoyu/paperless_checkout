<?php 

/*
 * This is filed is directed by the orders.php file.
 * It is expecially used to display the table on the left side, 
   which is used to select good one by one.
 * It is also used to display the selected items in another table on the right side. 
 */

// connect to the database
include 'db.php';

/*
Each item(good) in 'list' table have a field named stock. 
We need to update the stock if the item is bought and we
also need to calculate the total price of all the items of a specific
deal(order).
When the checkout button is clicked, we do the update, that is we will 
update the stock of the bought items and we will update the total price
of a specific deal(order). We also need to update the "paid" field in
the "orders" table in the items_list database.
To update, we first need to select price and quantity from
'order_items' table to calculate the total price. Second, 
we select code from 'order_items' table to updae the stock.
*/

// check if the chekcout button is clicked
if(isset($_GET['order_ID'])) { 
	$order_id = $_GET['order_ID'];
	$user_id = $_GET['user_id'];

	//get code, price and quantity from "order_items" table
	try {
		$results = $db->query("SELECT code, price, quantity 
			FROM order_items WHERE order_id ='$order_id' ");
		$results->setFetchMode(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			var_dump($e);
			exit;
	}

	//calculate the total price
	$total = 0;

	while ($r = $results->fetch()): 
		$total = $total + ( $r['price'] * $r['quantity'] );
		$code = $r['code'];

		//get the stock
		try {
			$results_for_stock = $db->query("SELECT stock
				FROM list WHERE code = '$code' ");
			$results_for_stock->setFetchMode(PDO::FETCH_ASSOC);
			} catch (Exception $e) {
				var_dump($e);
				exit;
		}

		//calculate the balance stock
		$r_for_stock = $results_for_stock->fetch();
		$stock = $r_for_stock['stock'];
		$balance_stock = $stock - $r['quantity'];

		//update the balance stock into "list" table
	    try {
		$results_for_balance_stock = $db->exec("UPDATE list
			SET stock = '$balance_stock'
			WHERE code = '$code' ");
		$results->setFetchMode(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			var_dump($e);
			exit;
		}   
	endwhile;

	//insert the total price to the "orders" table 
	//and insert the user_id to the "orders" table
	$paid = 0;
	if($total == 0) {
		$paid = 0;
	}
	else {
		$paid =1;
	}
	try {
		$results = $db->exec("UPDATE orders SET total_price = '$total', paid = '$paid', user_id = '$user_id' where id = '$order_id' ");
		} catch (Exception $e) {
			var_dump($e);
			exit;
	}

	//display the order_detail and QR code
	header("Location:order_detail.php?order_id=$order_id");
	exit();
}

/* Not for the situation that the checkout button is not clicked, but 
   for either selecting an item or just the first time this php file 
   is opened by redirction from the orders.php file. 
*/
else{
	//get the id from orders.php file the first time this page is 
	//opended
	$order_id = isset($_GET['id']) ? $_GET['id'] : 0;

	// after press the "buy" button, get the name and price of the 
	// item been bought
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){

		$order_id = $_POST['order_id'];

		$code = $_POST['code'];
		$quantity = $_POST['quantity'];

	//get id, name, price and stock from "list" talbe
		try {
		$results = $db->query("SELECT id, name, price, stock 
			FROM list WHERE code='$code' LIMIT 1");
		$results->setFetchMode(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			var_dump($e);
			exit;
		}

		$r = $results->fetch();

		$id = $r['id'];
		$name = $r['name'];
		$price = $r['price'];
		$stock = $r['stock'];

		//insert the bought item to "order_items" table
		try {
		$results = $db->exec("INSERT into order_items (order_id,code,name,price,quantity) 
			values ('$order_id','$code','$name','$price','$quantity') ");
		} catch (Exception $e) {
			var_dump($e);
			exit;
		}

		/* 
		Every time an item is selected, the table on the right would 
		display all the items been selected, so we need to select all 
		the bought items of this specific order from 'order_items' table
		in database.
		*/
		try {
			$results = $db->query("SELECT code,name,price,quantity FROM order_items 
				WHERE order_id='$order_id' ");
			$results->setFetchMode(PDO::FETCH_ASSOC);
			} catch (Exception $e) {
				var_dump($e);
				exit;
		}
	}
	// If the buy button is not pressed, it means that inside this 
	// "else" situation, it is the first time open this file, nothing
	// else is done.   
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">	
	<title>checkout(order_items)</title>
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

	<div class="classfix">
		<!-- display the selection box -->
		<div id="table1"> 
			<form action="order_items.php" method="post">
				<input type="hidden" name="order_id" value="<?php echo $order_id ?>" />
				<div id="p1_and_p2">					
					<p id="p1"><input type="text" class="input_c" name="code" placeholder="good's code"></p>
					<p id="p2"><input type="text" class="input_c" name="quantity" placeholder="quantity"></p>
				</div>
				<p id="p3"><input type="submit" name="buy" id="table1_button" value="select"></p>
			</form>								
		</div> <!-- end table1 -->

		<!-- diplay all the selected items of a specific order -->
		<div id="table2">
			<form id="form2" action="order_items.php" method="get">
				<input type="hidden" name="order_ID" 
				value="<?php echo $order_id; ?>" />
				<table style="width:100%">
					<thead>
						<tr>
							<th id="SN_s">SN</th>
							<th id="code_s">code</th>
							<th id="name_s">name</th>
							<th id="quantity_s">quantity</th>
							<th id="price_s">price</th>
						</tr>			
					</thead>
					<tbody>	
					<?php 
					//display all the bought items of this specific order
					if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
						$SN = 0;
						while ($r = $results->fetch()): 
							$SN = $SN + 1; ?>		
							<tr>
					            <td><?php echo $SN; ?></td>
					            <td><?php echo htmlspecialchars($r['code']); ?></td>
					            <td><?php echo htmlspecialchars($r['name']); ?></td>
					            <td><?php echo htmlspecialchars($r['quantity']); ?></td>
					            <td><?php echo htmlspecialchars($r['price']); ?></td>
							</tr> <?php
						endwhile;
					}
						
					//display a blank line of table if it is the first time to open the page
					else { ?>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>  <?php
					} ?>
					</tbody> 
				</table>
				<p>
					<input id="user_id" name="user_id" placeholder="enter user_id here">
					<input type="submit" class="button" id="submit_bottom" value="checkout">
				</p>
			</form>
		</div> <!-- end table2 -->
	</div> <!-- end table_wrapper -->

</div> <!-- end container -->

</html> 