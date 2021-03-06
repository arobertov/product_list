<?php

namespace SoftuniProductsBundle\Controller;

use SoftuniProductsBundle\Entity\ProductCategory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * Productcategory controller.
 *
 * @Route("admin/product-category")
 */
class ProductCategoryController extends Controller
{
    /**
     * Lists all productCategory entities.
     *
     * @Route("/", name="admin_product-category_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $criteria = ['rank'=>'DESC'];
        $productCategories = $this->get('softuni_product_category.manager')->getProductCategoriesBy($criteria);

        return $this->render('@SoftuniProducts/productcategory/index.html.twig', array(
            'productCategories' => $productCategories,
        ));
    }

    /**
     * Creates a new productCategory entity.
     *
     * @Route("/new", name="admin_product-category_new")
     * @Method({"GET", "POST"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $productCategory = new ProductCategory();
        $form = $this->createForm('SoftuniProductsBundle\Form\ProductCategoryType', $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $productCategory->setCreatedAt(new \DateTime());
            $productCategory->setUpdatedAt(new \DateTime());
            $em->persist($productCategory);
            $em->flush();

            return $this->redirectToRoute('admin_product-category_show', array('id' => $productCategory->getId()));
        }

        return $this->render('@SoftuniProducts/productcategory/new.html.twig', array(
            'productCategory' => $productCategory,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a productCategory entity.
     *
     * @Route("/{id}", name="admin_product-category_show")
     * @Method("GET")
     */
    public function showAction(ProductCategory $productCategory)
    {
        $deleteForm = $this->createDeleteForm($productCategory);

        return $this->render('@SoftuniProducts/productcategory/show.html.twig', array(
            'productCategory' => $productCategory,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing productCategory entity.
     *
     * @Route("/{id}/edit", name="admin_product-category_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ProductCategory $productCategory)
    {
        $deleteForm = $this->createDeleteForm($productCategory);
        $editForm = $this->createForm('SoftuniProductsBundle\Form\ProductCategoryType', $productCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $productCategory->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_product-category_show', array('id' => $productCategory->getId()));
        }

        return $this->render('@SoftuniProducts/productcategory/edit.html.twig', array(
            'productCategory' => $productCategory,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a productCategory entity.
     *
     * @Route("/{id}", name="admin_product-category_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ProductCategory $productCategory)
    {
        $form = $this->createDeleteForm($productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('softuni_products.uploader')->removeFile($productCategory->getImage());
            $this->get('softuni_product_category.manager')->deleteProductCategory($productCategory);
        }

        return $this->redirectToRoute('admin_product-category_index');
    }

    /**
     * Creates a form to delete a productCategory entity.
     *
     * @param ProductCategory $productCategory The productCategory entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ProductCategory $productCategory)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_product-category_delete', array('id' => $productCategory->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
}
