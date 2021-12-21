<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function registerMessage($Username, $Password){
	$client = new RabbitMQClient('databaseRabbitMQ.ini', 'sampleServer');
	if(isset($argv[1])){
	}
	else{
		$msg = array("message"=>"Register", "type"=>"register", "username" => $Username, "password" => $Password );

	}



	$response = $client->send_request($msg);

	//echo "client received response: " . PHP_EOL;
	return($response);
	//echo "\n\n";

	if(isset($argv[0]))
		echo $argv[0] . " END".PHP_EOL;
}

function loginMessage($Username, $Password){

	$client = new RabbitMQClient('databaseRabbitMQ.ini', 'sampleServer');
	if(isset($argv[1])){
		$msg = $argv[1];
	}
	else{
		$msg = array("message"=>"Login", "type"=>"login", "username" => $Username, "password" => $Password);
		//server listens for "login" in processor function then points to login function
	}

	$response = $client->send_request($msg);

	//echo "client received response: " . PHP_EOL;
	return($response);
	//echo "\n\n";

	if(isset($argv[0]))
		echo $argv[0] . " END".PHP_EOL;
}
?>
