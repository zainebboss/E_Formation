<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SessionRepository;
use App\Entity\Formation;
use App\Entity\Inscription;
use App\Entity\User;
use App\Entity\Session;

use App\Form\FormationType;
use App\Repository\FormationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ShowController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        return $this->render('front_template/index.html.twig');
    }


    /**
     * @Route("/user/listeF", name="liste_formation_front")
     */
    public function liste_session(SessionRepository $SessionRepository):Response
    {
    	//dd($SessionRepository->findOneBySomeField());
    	 return $this->render('front_template/formation.html.twig', [
            'sessions' => $SessionRepository->findOneBySomeField(),
        ]);

    }

      /**
     * @Route("/asearch",name="ajax_search",methods={"GET"},requirements={"q":"\value+"})
     * return Mixed
     */
    public function searchAction(Request $request ,FormationRepository  $repository)
    {
       // dd('1');
        $em = $this->getDoctrine()->getManager();
        $requestString =$request->query->get('q');
    //  dd($requestString);
        $posts =  $em->getRepository(Formation::class)->findByNom($requestString);
      //  dd($posts);
        if(!$posts) {
            $result['posts']['error'] = "Aucune Formation avec cette nom :( ";
        } else {
            $result['posts'] = $this->getRealEntities($posts);
        }
        return new Response(json_encode($result));
    }
    public function getRealEntities($posts)
    {
       //dd($posts);

        foreach ($posts as $key => $value){
            //dd($value['cv']);
            $realEntities[$value['id']] = $value['titre'];
        }

        return $realEntities;
    }


    /**
     * @Route("/Formation/register/{id}", name="inscrire")
     */
    public function inscrireF($id)
    {
      //dd('1');
        $Inscription = new Inscription();
        $em = $this->getDoctrine()->getManager();

        //$auth_id = $this->getUser()->getId();
        $user =  $em->getRepository(User::class)->find(1);

        $Inscription->setUtilisateur($user);

        $session =  $em->getRepository(Session::class)->find($id);
        $formation_id=$session->getFormation()->getId();
        $date=$session->getDate();
        $formation = $em->getRepository(Formation::class)->find($formation_id);

            $Inscription->setFormation($formation);
            $Inscription->setDateInscrit($date);
            $em->persist($Inscription);
            $em->flush();

        $basic  = new \Nexmo\Client\Credentials\Basic('fcb45a8f', 'wxETkucp0f7kHnqj');
        $client = new \Nexmo\Client($basic);

        $message = $client->message()->send([
            'to' => '21624167170',
            'from' => 'eFormation',
            'text' => 'Bonjour Madame/Monsieur, Votre inscription à été bien effectué'
        ]);

           return $this->redirectToRoute('index');


    }

}
