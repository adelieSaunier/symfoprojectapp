<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 * @package App\Controller
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    
    /**
     * @Route("/categorie/ajout", name="categorie_ajout")
     */
    public function addCategory(Request $request): Response
    {
        //ajout d'une nouvelle catégorie pour l'admin 
        $categorie = new Category;
        $form = $this->createForm(CategoryType::class, $categorie);
        $form->handleRequest($request);
        //si le form est soumis et est valide
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            //EN BDD
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('admin_home');
        }
        //RENDU
        return $this->render('admin/category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
