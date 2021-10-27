<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\RegisterActivity;
use App\Form\RegisterActivityType;
use App\Repository\ActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/activity", name="activity_")
*/

class ActivityController extends AbstractController
{
    /**
     * @Route("/details/{slug}", name="details")
     */
    public function details($slug, ActivityRepository $activityRepository, Request $request): Response
    { 
        $activity = $activityRepository->findOneBy(['slug'=>$slug]);

        $registeractivity = new RegisterActivity;
        $form = $this->createForm(RegisterActivityType::class, $registeractivity);
        $form->handleRequest($request);

        if(!$activity){
            throw new NotFoundHttpException('Pas d\'activité trouvées');
        }

        $participantsMax = $activity->getParticipantsMax();
        $participantsNumber = $activity->getParticipantsNumber();

        if($form->isSubmitted() && $form->isValid() && $participantsMax > $participantsNumber )  
        {
            $registeractivity->setUser($this->getUser());
            $registeractivity->setActivity($activity);
            $activity->setParticipantsNumber(1); // ma valeur s'incrémente seulement la première fois elle semble écrasée Peut faudrait il compter le nombre de lignes de l'activité et dire qu'il est = à $participantsNumber
            $em = $this->getDoctrine()->getManager();
            $em->persist($registeractivity);
            $em->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('activity/details.html.twig',[
            'activity' => $activity,
            'form' => $form->createView()
        ]); 
    }

    /**
     * @Route("/favorite/add/{id}", name="add_favorite")
     */
    public function addFavorite(Activity $activity)
    {   
        if(!$activity){
            throw new NotFoundHttpException('Pas d\'activité trouvées');
        }
        $activity->addFavorite($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($activity);
        $em->flush();
        return $this->redirectToRoute('app_home'); 
    }
    
    /**
     * @Route("/favorite/delete/{id}", name="delete_favorite")
     */
    public function deleteFavorite(Activity $activity)
    {   
        if(!$activity){
            throw new NotFoundHttpException('Pas d\'activité trouvées');
        }
        $activity->removeFavorite($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($activity);
        $em->flush();
        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/register/{id}", name="register_activity")
     * Ajout d'une activité, faire un lien dans mon twig qui renvoi à cette route redirect et appflashmessage
     */
    public function registerActivity(Request $request): Response
    {
        $registeractivity = new RegisterActivity;
        $form = $this->createForm(RegisterActivityType::class, $registeractivity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $registeractivity->setUser($this->getUser());
            $registeractivity->getActivity();
            $em = $this->getDoctrine()->getManager();
            $em->persist($registeractivity);
            $em->flush();

            return $this->redirectToRoute('user');
        }
        return $this->render('user/activity/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

 

    
}
