<?php

/* 
 * This file is invoded by the "list" tab page and it is used to update or delete 
   the information of one item in the list. 
 */

include 'db.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
	$id = $_GET["id"];
	if($_GET['action'] == 'update') {
		try {			
			$results = $db->query("SELECT id, name, code, cost, price, 
							stock FROM list WHERE id = '$id'");
			} catch (Exception $e) {
				var_dump($e);
				exit;
			}
			$r = $results->fetch();
		} 
	else if($_GET['action'] == 'delete') {
		try {
		$results = $db->exec("DELETE FROM list WHERE id = '$id'");
		} catch (Exception $e) {
			var_dump($e);
			exit;
		}
	}
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$id = $_POST["id"];
	$name= $_POST["name"];
	$code= $_POST["code"];
	$cost= $_POST["cost"];
	$price= $_POST["price"];
	$stock= $_POST["stock"];
	
	try {
	$sql = $db->query("UPDATE list SET name='$name', code='$code',cost='$cost', price='$price', stock='$stock' WHERE id='$id'");  
	} catch (Exception $e) {
		var_dump($e);
		exit;
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">	
	<title>updating</title>
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
				<li><a href="orders.php">checkout</a></li>
			</ul>
		</nav>
	</header>

	<?php 
	if($_SERVER['REQUEST_METHOD'] == 'POST') { ?>
		<p class="response">You have updated the item successfully!</p>
		<?php }
	else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		if($_GET['action'] == 'delete'){ ?>
			<p class="response">You have deleted the item successfully!</p>
			<?php }
		else if($_GET['action'] == 'update'){ ?>
			<div class="clearfix">
				<form action="update_delete.php" method="post">
					<table  class="list_adding_table">
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
								<input type="hidden" name="id" value="<?php echo $r['id']; ?>" >
								<td><input class="input_a" id="input_name_a" type="text" name="name" value="<?php echo htmlspecialchars($r['name']); ?>" ></td>
								<td><input class="input_a" type="text" name="code" value="<?php echo htmlspecialchars($r['code']); ?>" ></td>
								<td><input class="input_a" type="text" name="cost" value="<?php echo htmlspecialchars($r['cost']); ?>" ></td>
								<td><input class="input_a" type="text" name="price" value="<?php echo htmlspecialchars($r['price']); ?>" ></td>
								<td><input class="input_a" type="text" name="stock" value="<?php echo htmlspecialchars($r['stock']); ?>" ></td>
							</tr>
						<tbody>	
					</table>
						<input type="submit" class="button" id="submit" value="update">
				</form>		
			</div> <!-- end table -->	<?php 
		} 
	} ?>
</div> <!-- end container -->
</html> 