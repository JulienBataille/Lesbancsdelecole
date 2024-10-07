<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods:['GET'])]
    public function index(ProductRepository $productRepository): Response
    {


        return $this->render('home/index.html.twig', [
            'products' => $productRepository->findby([],['id'=>'DESC']),
        ]);
    }

    #[Route('/home/product/{id}/show', name: 'app_home_product_show', methods:['GET'])]
    public function show(Product $product,ProductRepository $productRepository):Response
    {
        $latestProducts = $productRepository->findBy([],['id'=>'DESC'], limit:3);
        return $this->render('home/show.html.twig', [
            'product' => $product,
            'products'=>$latestProducts
        ]);
    }

}
