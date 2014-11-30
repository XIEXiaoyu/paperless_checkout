<?php 
include 'db.php';

$fullname = $_GET['fullName'];
$email = $_GET['email'];
$password = $_GET['password'];

$count = 0;

if(($fullname == NUll) || 
   ($email == NULL) ||
   ($password == NULL)) {
	$count = 0;
}

else {
	$encrypted_password = md5($password);

	//insert the bought item to "order_items" table
	try {
		$results = $db->exec("INSERT into Uid_pw (user_id,password,user_name) 
			values ('$email','$encrypted_password','$fullname') ");
		} catch (Exception $e) {
			var_dump($e);
			exit;
		}
	$count = 1;
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

