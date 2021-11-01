<?php
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$servername = "serverc.example.com";
$username = "test";
$password = "password";
$dbname = "test";
$db = new PDO("mysql:host=$servername; dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function login2($email, $pass){
	global $db;

	//checking if username exists already
	$usncheck = $db->prepare('SELECT * FROM user WHERE email = :email');
	if (!$usncheck)
		echo "problem";
		return;
	$usncheck->bindParam(':email', $email);
	$usncheck->execute();
	$results = $usncheck->fetch(PDO::FETCH_ASSOC);
	if($results && count($results) > 0){
		echo "Username already exists";
		return false;
	}
	//check passed, inserts user
	$quest = 'INSERT INTO user (email, password) VALUES (:email, :password)';
	$stmt = $db->prepare($quest);
	$stmt->bindParam(':username', $email);
	$stmt->bindParam(':password', $pass);
	$stmt->execute();
}
function login($email,$pass){
	//TODO Make login function
	global $db;
	$stmt = $db->prepare("SELECT email, password FROM user WHERE email=(?)");
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$results = $stmt->fetch_assoc();
	if($results){
		$userpass = $results['password'];
		if($pass == $userpass){ //comparing passwords
			$stmt->bindParam(':email', $email);
			$stmt->execute();
			if($results && count($results) > 0){
				$userSes = array("email"=> $results['email']);
				return json_encode($userSes);
			}
			return true;
			echo "Logged in (Console)";
	}
	else{
		return false;
		echo "invalid password";
	}
	}
}
function register2($usern, $pass){
	global $db;

	//checking if username exists already
	$usncheck = $db->prepare('SELECT * FROM user WHERE (?)');
	$usncheck->bind_param('s', $usern);
	$stmt2 = $usncheck->execute();
	//check passed, inserts user
	$quest = 'INSERT INTO user (username) VALUES (?)';
	$stmt = $db->prepare($quest);
	$stmt->bind_Param('s', $usern);
	$stmt->execute();
	$quest2 = ('INSERT INTO user (password) VALUES (?)');
        $stmt = $db->prepare($quest);
        $stmt->bind_Param('s', $pass);
        $stmt->execute();

}
function register($username, $pass){
	global $db;

	//checking if username exists already
	$usncheck = $db->prepare('SELECT * FROM user WHERE username = :username');
	$usncheck->bindParam(':username', $username);
	$usncheck->execute();
	$results = $usncheck->fetch(PDO::FETCH_ASSOC);
	if($results && count($results) > 0){
		echo "Username already exists";
		return false;
	}
	//check passed, inserts user
	$quest = 'INSERT INTO user (username, password) VALUES (:username, :password)';
	$stmt = $db->prepare($quest);
	$stmt->bindParam(':username', $username);
	$stmt->bindParam(':password', $pass);
	$stmt->execute();
}

function request_processor($req){
	echo "Received Request".PHP_EOL;
	echo "<pre>" . var_dump($req) . "</pre>";
	if(!isset($req['type'])){
		return "Error: unsupported message type";
	}
	//Handle message type
	$type = $req['type'];
	switch($type){
		case "login":
			return login($req['username'], $req['password']);
		case "register":
			return register($req['username'], $req['password']);
		case "validate_session":
			return validate($req['session_id']);
		case "echo":
			return array("return_code"=>'0', "message"=>"Echo: " .$req["message"]);
	}
	return array("return_code" => '0',
		"message" => "Server received request and processed it");
}

$server = new rabbitMQServer("testRabbitMQ.ini", "sampleServer");

echo "Rabbit MQ Server Start" . PHP_EOL;
$server->process_requests('request_processor');
echo "Rabbit MQ Server Stop" . PHP_EOL;
exit();
?>
