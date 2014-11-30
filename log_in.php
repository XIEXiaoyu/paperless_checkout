<?php 
include 'db.php';

$email = $_GET['email'];
$password = $_GET['password'];

$count = 0;

$encrypted_password = md5($password);

//compare password
try {
	$results = $db->query("SELECT password
		FROM Uid_pw WHERE user_id ='$email' ");

	//var_dump($results);

	$results->setFetchMode(PDO::FETCH_ASSOC);
	
} catch (Exception $e) {
	var_dump($e);
	exit;
}

$r_of_orders = $results->fetch();
//var_dump($r_of_orders);

if($r_of_orders === false){ // the datetype and value both are equal
	$count = 0;
}

else {
	$password_in_database = $r_of_orders['password'];

	if($password_in_database == $encrypted_password) {
		$count = 1;
	}
	else {
		$count = 0;
	}
}

if ($count == 1) {
	$verificationNumber['veri_num'] = 1;
}
else {
	$verificationNumber['veri_num'] = 0;
}

$json = array(
	'Veri' => $verificationNumber
);

$json_str = json_encode($json);

header('Content-Length: '. strlen($json_str));
header('Connection: close');
echo $json_str;

?>

