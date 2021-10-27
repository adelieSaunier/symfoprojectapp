<?php

namespace App\Controller\Admin;

use App\Entity\Activity;
use App\Form\ActivityType;
use App\Repository\ActivityRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/activity", name="admin_activity_")
 * @package App\Controller
 */
class ActivityController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ActivityRepository $actRepo): Response
    {
        return $this->render('admin/activity/index.html.twig', [
            'activities' => $actRepo->findAll()
        ]);
    }

    /**
     * @Route("/activer/{id}", name="activer")
     */
    public function activer(Activity $activity)
    {
        $activity->setActive(($activity->getActive())?false:true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($activity);
        $em->flush();

        return new Response("true");
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(Activity $activity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($activity);
        $em->flush();

        $this->addFlash('message', 'Annonce supprimée avec succès');
        return $this->redirectToRoute('admin_activity_home');
    }


}