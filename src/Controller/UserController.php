<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Image;
use App\Form\ActivityType;
use App\Form\EditProfilType;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }

    /**
     * @Route("user/details/{id}", name="user_details")
     */
    public function details($id, UserRepository $userRepository)
    {   //findOneBy pour recup un utilisateur
        $user = $userRepository->findOneBy(['id'=>$id]);

        if(!$user){
            throw new NotFoundHttpException('Pas d\'utilisateur trouvé');
        }

        return $this->render('user/viewUser.html.twig', compact('user')); // compact pour passer les infos
    }

    /**
     * @Route("/user/activity/add", name="user_add_activity")
     */

    public function addActivity(Request $request): Response
    {
        $activity = new Activity;  
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $activity->setCreatedAt(new DateTime('now'));
            $activity->setUser($this->getUser());
            $activity->setActive(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($activity);
            $em->flush();

            return $this->redirectToRoute('user');
        }
        return $this->render('user/activity/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/user/activity/modifier/{id}", name="user_activity_modifier")
     */
    public function editActivity(Activity $activity, Request $request): Response
    {
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($activity);
            $em->flush();
            return $this->redirectToRoute('user_activity_home');
        }
        return $this->render('user/activity/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/user/profile/edit", name="user_edit_profile")
     */

    public function editProfile(Request $request)
    {  
        $user = $this->getUser();
        $form = $this->createForm(EditProfilType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //CHANGEMENTS DES IMAGES
            //Récup des images transmisent par l'utilisateur
            $images = $form->get('images')->getData();
            //j'itère chaque image'
            foreach($images as $image){
                //nveau fichier avec 2 fonctions php qui permettent d'avoir une chaine de caractères aléatoire 
                //basée sur un timestamp et j'ajoute l'extension du fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                //copie dans le fichier upload (avant j'ai rajouté un param images_directory dans service.yaml dans mon config dir)
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                //stockage en bdd
                $img = new Image;
                $img->setName($fichier);
                $user->addImage($img);
            }

            //MISE À JOUR EN BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('message','Profil mis à jour');

            return $this->redirectToRoute('user');
        }
        return $this->render('user/editprofil.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/delete/image/{id}", name="user_delete_image", methods={"DELETE"})
     * je ne peux accéder à cette route qu'en utilisant la méthode delete
     */

    public function deleteImage(Image $image, Request $request){
        //je dois sécuriser ma route avec un token 
        //je recup les données en json que je dois décoder
        $data = json_decode($request->getContent(), true);
        //on verrif si le token est valide avec le nom delete
        //on verrif le nom de l'image  
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            //je vais chercher le manager puis on enlève l'image
            $nom = $image->getName();
            unlink($this->getParameter('images_directory').'/'.$nom);
            //SUPP
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();
            //on repond en json
            return new JsonResponse(['success' => 1 ]);
        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }

    }



    /**
     * @Route("/user/password/edit", name="user_edit_password")
     */

    public function editPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    { 
        if($request->isMethod('POST')){
            $entitymanager = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            //là je dois verrif que les deux mot de passe sont identiques
            if($request->request->get('pass') == $request->request->get('pass2') ){
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('pass')));
                $entitymanager->flush();
                $this->addFlash('message','Votre mot de passe mis à jour');
                return $this->redirectToRoute('user');
            }else{
                $this->addFlash('error', 'Les 2 mots de passe ne sont pas identiques');
            }
        } 
        return $this->render('user/editpassword.html.twig');
    }
}
