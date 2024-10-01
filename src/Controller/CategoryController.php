<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/admin/category', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {

        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig',[
            'categories'=>$categories
        ]);
    }

    #[Route('/admin/category/new', name: 'app_category_new')]
    public function addCategory(EntityManagerInterface $em, Request $request): Response
    {
        
        $category = new Category();

        $formCategory = $this->createForm(CategoryFormType::class, $category);
        $formCategory->handleRequest($request);

        if($formCategory->isSubmitted() && $formCategory->isValid()){
            

            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'La catégorie a été créée');
        
            return $this->redirectToRoute('app_category');
        
        }

        return $this->render('category/new.html.twig',[
            'formCategory'=>$formCategory->createView()
        ]);
    }

    #[Route('/admin/category/{id}/update', name: 'app_category_update')]
    public function update(Category $category, EntityManagerInterface $em, Request $request):Response
    {

        $formCategory = $this->createForm(CategoryFormType::class, $category);
        $formCategory->handleRequest($request);

        if($formCategory->isSubmitted() && $formCategory->isValid()){

            $em->flush();
            $this->addFlash('success', 'La catégorie a été modifiée');

            return $this->redirectToRoute('app_category');

        }

        return $this->render('category/update.html.twig',[
            'formCategory'=>$formCategory->createView()
        ]);
    }

    #[Route('/admin/category/{id}/delete', name: 'app_category_delete')]
    public function delete(Category $category, EntityManagerInterface $em, Request $request):Response
    {

        $em->remove($category);
        $em->flush();
        $this->addFlash('danger', 'La catégorie a été supprimée');

        return $this->redirectToRoute('app_category');

        

    }


}
