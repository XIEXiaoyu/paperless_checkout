<?php
/* In the mobile app, after the user press the history button, the app will get the URL  
 * The URL contains the user_id.php?user_id = ^^^.
 * So 1. through the user_id from "orders" table in database, we get the all the orders of 
 		 this specific id.
 * 	  2. through the user_id.php file, we can get
 *       the json format information of the specific user_id.
 * After these, we can display the json format information on the phone,
 * and this part will be done on the mobile app development.  
 */
$user_id = $_GET['user_id'];
$dateFrom = $_GET['dateFrom'];
$dateTo = $_GET['dateTo'];

include 'db.php';

try {
	$results = $db->query("SELECT id, order_time, total_price
		FROM orders WHERE (user_id = '$user_id') AND (paid = 1) AND (order_time BETWEEN '$dateFrom' and '$dateTo') ORDER BY id DESC");
	$results->setFetchMode(PDO::FETCH_ASSOC);
	} catch (Exception $e) {
		var_dump($e);
		exit;
	}

$SN_Uid = 0;
while ($r_of_Uid = $results->fetch() ):
	$Uid[$SN_Uid]['id'] = $r_of_Uid['id'];
	$Uid[$SN_Uid]['date'] = $r_of_Uid['order_time'];
	$Uid[$SN_Uid]['total'] = $r_of_Uid['total_price'];
	$SN_Uid = $SN_Uid + 1;
endwhile;

$userID['id'] = $user_id; 

$json = array(
	'Uid' => $Uid,
	'UserID' => $userID,
);

$json_str = json_encode($json);

header('Content-Length: '. strlen($json_str));
echo $json_str;
?>