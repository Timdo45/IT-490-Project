<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$username = $_POST['username'];
$password = $_POST['password'];


$connection = new AMQPStreamConnection('10.244.0.6', 5672, 'admin', 'admin');
$channel = $connection->channel();

$channel->queue_declare('username', false, false, false, false);
$channel->queue_declare('password', false, false, false, false);


$msg = new AMQPMessage($username);
$msg2 = new AMQPMessage($password);

$channel->basic_publish($msg, '', 'username');
$channel->basic_publish($msg2, '', 'password');

echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();


?>
