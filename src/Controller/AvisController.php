<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\User;
use App\Form\AvisType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/avis")
 */
class AvisController extends AbstractController
{
    /**
     * @Route("/", name="avis_index", methods={"GET"})
     */
    public function index(): Response
    {
        $avis = $this->getDoctrine()
            ->getRepository(Avis::class)
            ->findAvis();

        return $this->render('back/avis/index.html.twig', [
            'avis' => $avis,
            'search' => '',
        ]);
    }
    //affichage formateurs  front
    /**
     * @Route("/formateurs", name="list_formateurs", methods={"GET"})
     */
    public function listFormateurs(): Response
    {
        $formateurs = $this->getDoctrine()
            ->getRepository(User::class)
            ->findUsersByRole('','ROLE_FORMATEUR');

        return $this->render('front/formateur/index.html.twig', [
            'formateurs' => $formateurs,
        ]);
    }


    /**
     * @Route("/new/{formateurId}", name="avis_new", methods={"GET","POST"})
     */
    public function new($formateurId,Request $request): Response
    {

       $formateur = $this->getDoctrine()
           ->getRepository(User::class)
           ->find($formateurId);
       if($formateur){
           if ($request->isMethod('post')) {
               $note=$request->get('note','');
               $commentaire=$request->get('commentaire','');
               if($note==1 ||$note==2 || $note==3 ){
                   $avis=new Avis();
                   $avis->setApprenant($this->getUser());
                   $avis->setFormateur($formateur);
                   $avis->setNote($note);
                   $avis->setCommentaire($commentaire);
                   $this->getDoctrine()->getManager()->persist($avis);
                   $this->getDoctrine()->getManager()->flush();
                   return $this->redirectToRoute('avis_show');
               }
               else{
                   return $this->render('front/avis/new.html.twig', [
                       'formateur' => $formateur,
                   ]);
               }
           }
           return $this->render('front/avis/new.html.twig', [
               'formateur' => $formateur,
           ]);
       }
       else{
           $this->redirectToRoute('list_formateurs');
       }

    }

    /**
     * @Route("/show", name="avis_show", methods={"GET"})
     */
    public function show(): Response
    {
        $avis = $this->getDoctrine()
            ->getRepository(Avis::class)
            ->findAvis('',$this->getUser()->getId());
        return $this->render('front/avis/show.html.twig', [
            'avis' => $avis,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="avis_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Avis $avi): Response
    {
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('avis_index');
        }

        return $this->render('avis/edit.html.twig', [
            'avi' => $avi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="avis_delete", methods={"POST"})
     */
    public function delete(Request $request, Avis $avi): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avi->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($avi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('avis_index');
    }
}
