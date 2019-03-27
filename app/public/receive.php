<?php

require_once '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('container_rabbit', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('products', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
};

$channel->basic_consume('products', '', false, true, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}