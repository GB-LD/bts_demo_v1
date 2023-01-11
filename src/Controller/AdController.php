<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AdType;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    #[Route('/annonces', name: 'index_annonces')]
    public function searchProducts(ProductRepository $productRepository, Request $request) : Response
    {
        $products = $productRepository->findAll();

        return $this->render('ad/searchAds.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/annonce/{slug}', name: 'show_annonce', priority: -1)]
    public function showProduct($slug, Product $product): Response
    {
        return $this->render('ad/showProduct.html.twig', [
            'product' => $product
        ]);
    }

    #[Route('/annonce/creation', name: 'create_annonce')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(AdType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $title = $form->getData()["title"];
            $description = $form->getData()["description"];
            $category = $form->getData()["category"];
            $subject = $form->getData()["subject"];

            $product->setTitle($title);
            $product->setDescription($description);
            $product->setCategory($category);
            $product->setSubject($subject);
            $product->setSlug(strtolower($product->getTitle()));

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('show_annonce', [
                'slug' => $product->getSlug()
            ]);
        }

        return $this->render('ad/adForm.html.twig', [
            "formView" => $form->createView()
        ]);
    }
}
