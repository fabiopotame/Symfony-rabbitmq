<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Entity\Product;
use App\Repository\ProductRepository;
class ProductController {

    public function index()
    {
        $response = ['id' => 1, 'status' => true];
        return new JsonResponse($response, 500);
    }

    public function publish()
    {
        $connection = new AMQPStreamConnection('container_rabbit', 5672, 'guest', 'guest');

        $channel = $connection->channel();
        $channel->queue_declare('products', false, false, false, false);

        $body = [
            'name' => 'Calça Jeans',
        ];

        for ($i = 0; $i <= 10; $i++) {
            $body['name'] = array_rand(array_flip(
                ['Calça Jeans', 'Camisa', 'Shorts', 'Camiseta', 'Casaco']
            ), 1);
            $body['sku'] = rand(10000000, 99999999);
            $body['size'] = rand(32, 50);
            $body['color'] = array_rand(array_flip(['azul', 'preta', 'branca', 'vinho']), 1);
            $msg = new AMQPMessage(json_encode($body));
            $channel->basic_publish($msg, '','products');
        }

        $channel->close();
        $connection->close();

        $response = ['status' => true, 'message' => 'Success!'];
        return new JsonResponse($response, 200);
    }

    public function save()
    {
        $product = new Product();
        $product->setName('Calça jeans');
        $product->setSku('64581234');
        $product->setColor('Azul');
        $product->setSize(38);

        $productRepository = (new ProductRepository())->save($product);
        return new JsonResponse($productRepository,200);
    }
}
