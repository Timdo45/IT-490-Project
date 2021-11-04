<?php

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function login ($email, $pass){
	

}
function register ($email, $pass){
	$clientB = new RabbitMQClient('backendRabbitMQ.ini', 'it490Server');
	$clientD = new RabbitMQClient('databaseRabbitMQ.ini', 'it490Server');
	if(isset($argv[1])){
		$msg = $argv[1];
	}
	else{
		$msg = array("message"=>"Login", "type"=>"login", "username" => $email, "password" => $pass);
		//server listens for "login" in processor function then points to login function
	}

	$responseB = $clientB->send_request($msg);

	if($responseB){
		$responseD = $clientD->send_request($msg);
	}
	else{
		echo "Backend could not verify";
		return false;
	}

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

$server = new rabbitMQServer("frontendRabbitMQ.ini", "sampleServer");

echo "Rabbit MQ Server Start" . PHP_EOL;
$server->process_requests('request_processor');
echo "Rabbit MQ Server Stop" . PHP_EOL;
exit();
?>
