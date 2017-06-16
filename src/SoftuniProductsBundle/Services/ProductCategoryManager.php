<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 15.6.2017 г.
 * Time: 15:30
 */

namespace SoftuniProductsBundle\Services;


use Doctrine\ORM\EntityManager;
use SoftuniProductsBundle\Entity\ProductCategory;

class ProductCategoryManager
{
    protected $em,$class,$container,$repository ;

    public  function __construct(EntityManager $em,$class,$container)
    {
        $this->em = $em;
        $this->class = $class;
        $this->container = $container;
        $this->repository = $em->getRepository($class);
    }

    public function createProductCategory()
    {
        $class = $this->getClass();
        return new $class;
    }


    public function deleteProductCategory(ProductCategory $productCategory)
    {
        $this->em->remove($productCategory);
        $this->em->flush();
    }

    public function deleteProductCategories($productCategories)
    {
        foreach ($productCategories as $productCategory){
            $this->deleteProductCategory($productCategory);
        }
        return true;
    }

    public function getProductCategories()
    {
        $posts= $this->repository->findAll();
        return $posts;
    }

    public function getProductCategoriesBy(array $criteria)
    {
        return $this->repository->findBy([],$criteria);
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

}