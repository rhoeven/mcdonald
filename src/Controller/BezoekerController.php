<?php

namespace App\Controller;


use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BezoekerController extends AbstractController
{
    #[Route('/bezoeker', name: 'app_bezoeker')]
    public function index(): Response
    {
        return $this->render('bezoeker/index.html.twig', [
            'controller_name' => 'BezoekerController',
            'user'=>'Piet'
        ]);
    }

    #[Route('/products', name: 'app_products')]
    public function products(EntityManagerInterface $entityManager): Response
    {
        $products=$entityManager->getRepository(Product::class)->findAll();
        //dd($products);
        return $this->render('bezoeker/products.html.twig', [
            'products' => $products,
        ]);
    }
    #[Route('/new/product', name: 'add_product')]
    public function addProduct(EntityManagerInterface $entityManager, Request $request): Response
    {
        $product=new Product();
        $form=$this->createForm(ProductType::class,$product);

        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $product=$form->getData();
            //dd($product);
            $entityManager->persist($product);
            $entityManager->flush();
            $name=$product->getName();
            $this->addFlash('success',"Product $name is toegevoegd");
            return $this->redirectToRoute('app_products')  ;
        }

        return $this->render('bezoeker/new.html.twig',[
            'form'=>$form,
        ]);
    }

    #[Route('/delete/product/{id}',name: 'delete_product')]
    public function deleteProduct(EntityManagerInterface $entityManager,int $id):Response
    {
        $product=$entityManager->getRepository(Product::class)->find($id);
        $entityManager->remove($product);
        $entityManager->flush();
        return $this->redirectToRoute('app_products');
    }
    #[Route('/update/product/{id}', name: 'update_product')]
    public function updateProduct(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        //$product=new Product();
        $product=$entityManager->getRepository(Product::class)->find($id);
       // dd($product);
        $form=$this->createForm(ProductType::class,$product);

        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $product=$form->getData();
            //dd($product);
            $entityManager->persist($product);
            $entityManager->flush();
            $name=$product->getName();
            $this->addFlash('success',"Product $name is toegevoegd");
            return $this->redirectToRoute('app_products')  ;
        }

        return $this->render('bezoeker/new.html.twig',[
            'form'=>$form,
        ]);
    }
}
