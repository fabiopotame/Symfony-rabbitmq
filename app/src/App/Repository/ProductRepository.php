<?php
namespace App\Repository;

use Doctrine\ORM\Tools\Setup;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Product as ProductEntity;
use Doctrine\ORM\EntityManager;
use Exception;

class ProductRepository {
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct()
    {
        $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."../Entity"), true);
        $conn = ['driver' => 'pdo_mysql'];

//        $conn = [
//            'dbname' => 'rabbitmq',
//            'user' => 'root',
//            'password' => 'root',
//            'host' => '192.168.0.5',
//            'driver' => 'pdo_mysql',
//        ];

        try {
            $this->entityManager = EntityManager::create($conn, $config);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param ProductEntity $product
     * @return Response
     * @throws Exception
     */
    public function save(ProductEntity $product)
    {
        try {
            $this->entityManager->persist($product);
            $this->entityManager->flush();
            return new Response('Saved new product with id '. $product->getId());
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

}
