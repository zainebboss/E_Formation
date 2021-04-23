<?php

namespace App\Controller;

use App\Entity\Aide;
use App\Entity\Aidecom;
use App\Form\AideFormType;
use App\Repository\AideRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\Doctrine;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/aide/con")
 */
class AideConController extends AbstractController
{
    /**
     * @Route("/index", name="aide_con_index", methods={"GET"})
     */
    public function index(): Response
    {


        $aides = $this->getDoctrine()
            ->getRepository(Aide::class)
            ->findAll();

        $aidecom = $this->getDoctrine()
            ->getRepository(Aidecom::class)
            ->findAll();
        return $this->render('aide_con/index.html.twig', [
            'aides' => $aides,
            'aidecom'=>$aidecom,

        ]);
    }



    /**
     * @Route("/", name="aide_con_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $aide = new Aide();
        $form = $this->createForm(AideFormType::class, $aide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($aide);
            $entityManager->flush();

            return $this->redirectToRoute('aide_con_index');
        }

        return $this->render('aide_con/new.html.twig', [
            'aide' => $aide,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="aide_con_show", methods={"GET"})
     */
    public function show(Aide $aide): Response
    {
        return $this->render('aide_con/show.html.twig', [
            'aide' => $aide,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="aide_con_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Aide $aide): Response
    {
        $form = $this->createForm(AideFormType::class, $aide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('aide_con_index');
        }

        return $this->render('aide_con/edit.html.twig', [
            'aide' => $aide,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="aide_con_delete", methods={"POST"})
     */
    public function delete(Request $request, Aide $aide): Response
    {
        if ($this->isCsrfTokenValid('delete'.$aide->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($aide);
            $entityManager->flush();
        }

        return $this->redirectToRoute('aide_con_index');
    }
    public function createAction(Request $request)
    {

        $form = $this->createFormBuilder(new Article());
    }


    /**
     * @param Request $request
     * @return Response
     * @Route ("/produitajaxxx",name="searchrdvzz")
     */
    public function searchrdvvv(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Aide::class);
        $requestString=$request->get('searchValue');
        $rdv = $repository->findrdvByname($requestString);
        return $this->render('aide_con/showSujet.html.twig' ,[
            "listsujet"=>$rdv,
        ]);
    }

    /**
     * @Route ("aide_con/recherche",name="recherche")
     */
    public function recherche  (AideRepository $repository,Request $request){

        $data=$request->get('search');

     $aide=$repository->findBy(['sujet'=>$data]);
        $aides =$aide;
        $aidecom = $this->getDoctrine()
            ->getRepository(Aidecom::class)
            ->findAll();
        return $this->render('aide_con/index.html.twig', [
         'aides' => $aides,
            'aidecom'=>$aidecom,
     ]);
    }

}
