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

function login($username,$pass){
	//TODO Make login function
	global $db;
	$stmt = $db->prepare('SELECT * FROM user WHERE username = :username');
	$stmt->bindParam(':username', $username);
	$stmt->execute();
	$results = $stmt->fetch(PDO::FETCH_ASSOC);
	if($results){
		$userpass = $results['password'];
		if($pass == $userpass){ //comparing passwords
			$stmt->bindParam(':username', $username);
			$stmt->execute();
			if($results && count($results) > 0){
				$userSes = array("username"=> $results['username']);
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

$server = new rabbitMQServer("databaseRabbitMQ.ini", "sampleServer");

echo "Rabbit MQ Server Start" . PHP_EOL;
$server->process_requests('request_processor');
echo "Rabbit MQ Server Stop" . PHP_EOL;
exit();
?>

