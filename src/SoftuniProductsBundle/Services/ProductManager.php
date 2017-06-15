<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 15.6.2017 г.
 * Time: 15:12
 */

namespace SoftuniProductsBundle\Services;


use Doctrine\ORM\EntityManager;
use SoftuniProductsBundle\Entity\Product;

class ProductManager
{
    protected $em,$class,$container,$repository ;

    public  function __construct(EntityManager $em,$class,$container)
    {
        $this->em = $em;
        $this->class = $class;
        $this->container = $container;
        $this->repository = $em->getRepository($class);
    }

    public function createProduct()
    {
        $class = $this->getClass();
        return new $class;
    }


    public function deleteProduct(Product $product)
    {
        $this->em->remove($product);
        $this->em->flush();
    }

    public function deleteProducts($products)
    {
        foreach ($products as $product){
            $this->deleteProduct($product);
        }
        return true;
    }

    public function getProducts()
    {
        $posts= $this->repository->findAll();
        return $posts;
    }

    public function getProductsBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

}