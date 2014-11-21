<?php 

/*  
 * This file is used in the main page of system. It displays the list of all the items. 
 */ 

// connect to database 
include 'db.php';

// filter function 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$code = $_POST["filter_input"];
	if ( empty($code) ) {
		header("Location: index.php ");
	}
	try {
	$results = $db->query("SELECT id, name, code, cost, price, 
						stock FROM list WHERE code='$code' LIMIT 1");
	$results->setFetchMode(PDO::FETCH_ASSOC);
	} catch (Exception $e) {
		var_dump($e);
		exit;
	}
}

// display ten items of the list on one page 
else{
	$perpage = 10;
	try {
		$rownum = $db->query("SELECT count(id) FROM list");
		$rownum = $rownum->fetch();
		$rownum = $rownum[0];
		$max = ceil($rownum/$perpage);
	} catch (Exception $e) {
		var_dump($e);
		exit;
	}

	if(!isset($_GET['page']) or $_GET['page'] <= 0){
		$page = 1;
	}
	else {
		$page = $_GET['page'];	
	}
	$offset = ($page - 1) * $perpage;

	try {
		$results = $db->query("SELECT id, name, code, cost, price, 
					stock FROM list ORDER BY code ASC 
					limit $perpage offset $offset");
		$results->setFetchMode(PDO::FETCH_ASSOC);
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
	<title>list</title>
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
				<li><a href="index.php" class="selected">list</a></li>
				<li><a href="adding.php">adding</a></li>
				<li><a href="orders.php">checkout</a></li>
			</ul>
		</nav>
	</header>

	<!-- the search function -->
	<div class="filter clearfix">
		<form class="form_for_filter" method="post" action="index.php">
			<label>Filter</label> 
			<input type="text" id="filter_input" name="filter_input" placeholder="enter good code here"> 
			<input type="submit" value="search" class="button">
		</form>
	</div>
	
	<!-- displaying the talbe of ten items -->
	<div class="table_wrapper clearfix">
		<table class="table">
			<thead>
				<tr>
					<th id="name_l">name</th>
					<th id="code_l">code</th>
					<th id="cost_l">cost</th>
					<th id="price_l">price</th>
					<th id="stock_l">stock</th>
					<th colspan="2" id="operations">operations</th>						
				</tr>
			</thead>
			<tbody>
				<!-- display the filter reslut -->
				<?php 
				if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					$r = $results->fetch(); ?>
					<tr>
			            <td><?php echo htmlspecialchars($r['name']); ?></td>
			            <td><?php echo htmlspecialchars($r['code']); ?></td>
			            <td><?php echo htmlspecialchars($r['cost']); ?></td>
			            <td><?php echo htmlspecialchars($r['price']); ?></td>
			            <td><?php echo htmlspecialchars($r['stock']); ?></td>
					<td>
						<form action="update_delete.php" method="get">
							<input type="hidden" name="id" value="<?php echo $r['id']; ?>" >
							<input type="hidden" name="action" value="update" />
							<input type="submit" value="Update" />
						</form>
					</td>
					<td>
						<form action="update_delete.php" method="get">
							<input type="hidden" name="id" value="<?php echo $r['id'] ?>" >
							<input type="hidden" name="action" value="delete" />
							<input type="submit" value="Delete">
						</form>
					</td>
		        	</tr>
				<?php
				}
				// display ten items on a page
				else{ 
					while ($r = $results->fetch()): ?>
		        <tr>
		            <td><?php echo htmlspecialchars($r['name']); ?></td>
		            <td><?php echo htmlspecialchars($r['code']); ?></td>
		            <td><?php echo htmlspecialchars($r['cost']); ?></td>
		            <td><?php echo htmlspecialchars($r['price']); ?></td>
		            <td><?php echo htmlspecialchars($r['stock']); ?></td>
					<td>
						<form action="update_delete.php" method="get">
							<input type="hidden" name="id" value="<?php echo $r['id']; ?>" >
							<input type="hidden" name="action" value="update" />
							<input type="submit" value="Update" />
						</form>
					</td>
					<td>
						<form action="update_delete.php" method="get">
							<input type="hidden" name="id" value="<?php echo $r['id'] ?>" >
							<input type="hidden" name="action" value="delete" />
							<input type="submit" value="Delete">
						</form>
					</td>
		        </tr>
        		<?php endwhile; 
        		}?>	
			</tbody>
		</table>
	</div> <!-- end table-->
	
	<div class="footer clearfix">
		<footer>
			<a href="index.php?page=<?php 
				if ($page = 1) {
					echo $page;
				}
				else {
					echo $page-1;  
				}
				?>" id="previous" class="page">Previous</a>
			<a href="index.php?page=<?php 
				if ($page < $max) { 
					echo $page+1;
				} 
				else {
					echo $max;
				} 
				?>" id="next" class="page">Next</a>
		</footer>
	</div> <!-- end footer-->
</div> <!-- end container -->

</html> 