<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
require("/home/test/IT490/RabbitMQClientSample.php")
$username = $_POST['username'];
$password = $_POST['password'];

    if ($username != "" && $password != ""){
        $rabbitResponse = registerMessage($username, $password);

        if($rabbitResponse==false){
            echo "account already created";

        }else{

            echo "Account is created";
            header("Location: login.html");

        }
    }else{
        echo "Nothing entered";
    }

?>
