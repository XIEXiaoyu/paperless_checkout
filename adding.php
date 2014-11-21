<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$name = $_POST["name"];
	$code = $_POST["code"];
	$cost = $_POST["cost"]; 
	$price = $_POST["price"];
	$stock = $_POST["stock"]; 

	if (empty($name) or empty($code) or empty($cost) or empty($price) or empty($stock)){
		$err_msg = "Caution! Each field must not be blank.";
	}
	else{
		include 'db.php';

		try {
		$results = $db->exec("INSERT into list(name, code, cost, price, 
					stock) values ('$name','$code','$cost',
					'$price','$stock')");
		} catch (Exception $e) {
			var_dump($e);
			exit;
		}
	}	
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">	
	<title>adding</title>
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
				<li><a href="adding.php" class="selected">adding</a></li>
				<li><a href="orders.php">checkout</a></li>
			</ul>
		</nav>
	</header>

	<?php 
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($err_msg)) { ?>
		<p class="response">You have added the item successfully!</p>
	<?php 
	} else { ?>

	<div class="clearfix">
		<form action="adding.php" method="post">			
			<table  id= "adding_table" class="table ">
				<thead>
					<tr>
						<th id="name_a">name</th>
						<th id="code_a">code</th>
						<th id="cost_a">cost</th>
						<th id="price_a">price</th>
						<th id="stock_a">stock</th>	
					</tr>			
				</thead>	
				<tbody>
					<tr>
						<td><input class="input" id="input_name_a" type="text" name="name"></td>
						<td><input class="input" type="text" name="code"></td>
						<td><input class="input" type="text" name="cost"></td>
						<td><input class="input" type="text" name="price"></td>
						<td><input class="input" type="text" name="stock"></td>
					</tr>
				<tbody>	
			</table>
				<input type="submit" class="button" id="submit_a" value="adding">
				<p id="alert"> <?php if(isset($err_msg)){ echo $err_msg; } ?> </p>
		</form>		
	</div> <!-- end table -->
	<?php 
	} ?>
</div> <!-- end container -->
</html> 