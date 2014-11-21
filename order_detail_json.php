<?php
/* After the user scanning the QRcode, the app will get the URL encoded 
 * in the QRcode. 
 * The URL contains the order_detail_json.php?order_id = ^^^.
 * So 1. through the order_id, we get the order_information form database,
 		 and encode them in json format.
 * 	  2. through the order_detail_json.php file, we can get
 *       the json format information of the specific order_id.
 * After these, we can display the json format information on the phone,
 * and this part will be done on the mobile app development.  
 */
$order_id = $_GET['order_id'];

include 'db.php';

try {
	$results = $db->query("SELECT order_time, total_price, user_id
		FROM orders WHERE id ='$order_id' ");
	$results->setFetchMode(PDO::FETCH_ASSOC);
	} catch (Exception $e) {
		var_dump($e);
		exit;
}

$r_of_orders = $results->fetch();

$order['date'] = $r_of_orders['order_time'];
$order['total'] = $r_of_orders['total_price'];
$order['user_id'] = $r_of_orders['user_id'];

try {
	$results = $db->query("SELECT name, price, quantity
		FROM order_items WHERE order_id = '$order_id' ");
	$results->setFetchMode(PDO::FETCH_ASSOC);
	} catch (Exception $e) {
		var_dump($e);
		exit;
	}

$SN = 0;
while ($r_of_order_id = $results->fetch() ): 
	$items[$SN]['name'] = $r_of_order_id['name'];
	$items[$SN]['price'] = $r_of_order_id['price'];
	$items[$SN]['quantity'] = $r_of_order_id['quantity'];
	$SN = $SN + 1;
endwhile;

$json = array(
	'order' => $order,
	'items' => $items,
);

$json_str = json_encode($json);

header('Content-Length: '. strlen($json_str));
echo $json_str;
?>