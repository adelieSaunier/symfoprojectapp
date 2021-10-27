<?php

namespace App\Controller;

use App\Form\SearchActivityType;
use App\Repository\ActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(ActivityRepository $activityRepository, Request $request)
    {
        $activities = $activityRepository->findBy(['active' => true],['created_at'=>'desc']);
        $form = $this->createForm(SearchActivityType::class);
        $search = $form->handleRequest($request);
        //RECHERCHE DES ANNONCES CORRESPONDANTES AUX MOTS CLÉS
        if($form->isSubmitted() && $form->isValid()){
            //je dois créer dans mon repository une méthode qui va gérer la recherche
            $activities = $activityRepository->search(
                $search->get('mots')->getData(),
                $search->get('category')->getData()
            );

        }


        return $this->render('main/index.html.twig', [
            //méthode findBy() pour aller chercher des données et faire un filtrage et ordonner les données
            //je verrif quelles sont actives et je les ordonne par date de création de la plus recente à la plus ancienne avec desc
            //je peux limiter l'affichage en rajoutant ,5 par exemple après ['created_at'=>'desc']
            'activities' => $activities,
            'form' => $form->createView()
        ]);
    }
}
