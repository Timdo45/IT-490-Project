<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
echo "thing";
require('/home/test/IT490/RabbitMQClientSample.php');
$username = $_POST['username'];
$password = $_POST['password'];
$rabbitResponse = loginMessage($username, $password);
if(!$rabbitResponse){
	 echo "login has failed, please try again";
                //redirect back to login page to try again
}
else{
                echo "You are logged in!";
                $userSes = json_decode($rabbitResponse, true);
                $_SESSION['logged'] = true;
                $_SESSION['user'] = $userSes;
                echo var_export($_SESSION['user']['name']);
               // header("location: dashboard.php");
		}

?>
