<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/annonces', name: 'index_annonces')]
    public function searchProducts(ProductRepository $productRepository, Request $request) : Response
    {
        $products = $productRepository->findAll();

        return $this->render('search/searchAds.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/annonce/{slug}', name: 'show_annonce')]
    public function showProduct($slug, Product $product): Response
    {
        return $this->render('search/showProduct.html.twig', [
            'product' => $product
        ]);
    }
}
