<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category", name="admin_category_")
 * @package App\Controller
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CategoryRepository $catRepo): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'categories' => $catRepo->findAll()
        ]);
    }
    /**
     * @Route("/ajout", name="ajout")
     */
    public function addCategory(Request $request)
    {
        $categorie = new Category;
        $form = $this->createForm(CategoryType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('admin_category_home');
        }
        return $this->render('admin/category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="modifier")
     */
    public function editCategory(Category $categorie, Request $request)
    {
        $form = $this->createForm(CategoryType::class, $categorie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('admin_category_home');
        }
        return $this->render('admin/category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}