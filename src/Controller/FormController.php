<?php

namespace App\Controller;

use App\Entity\Aide;
use App\Entity\Aidecom;
use App\Form\AideType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AideRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/form")
 */
class FormController extends AbstractController
{
    /**
     * @Route("/", name="form_index", methods={"GET"})
     */
    public function index(): Response
    {
        $aides = $this->getDoctrine()
            ->getRepository(Aide::class)
            ->findAll();

        $aidecom = $this->getDoctrine()
            ->getRepository(Aidecom::class)
            ->findAll();


        return $this->render('form/index.html.twig', [
            'aides' => $aides,
            'aidecom'=>$aidecom,
        ]);
    }


    /**
     * @Route("/new", name="form_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $aide = new Aide();
        $form = $this->createForm(AideType::class, $aide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($aide);
            $entityManager->flush();

            return $this->redirectToRoute('form_index');
        }

        return $this->render('form/new.html.twig', [
            'aide' => $aide,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="form_show", methods={"GET"})
     */
    public function show(Aide $aide): Response
    {
        return $this->render('form/show.html.twig', [
            'aide' => $aide,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="form_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Aide $aide): Response
    {
        $form = $this->createForm(AideType::class, $aide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('form_index');
        }

        return $this->render('form/edit.html.twig', [
            'aide' => $aide,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="form_delete", methods={"POST"})
     */
    public function delete(Request $request, Aide $aide): Response
    {
        if ($this->isCsrfTokenValid('delete'.$aide->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($aide);
            $entityManager->flush();
        }

        return $this->redirectToRoute('form_index');
    }

    /**
     * @Route ("form/recherche",name="recherche")
     */
    public function recherche  (AideRepository $repository,Request $request){

        $data=$request->get('search');

        $aide=$repository->findBy(['sujet'=>$data]);
        $aides =$aide;
        $aidecom = $this->getDoctrine()
            ->getRepository(Aidecom::class)
            ->findAll();
        return $this->render('form/index.html.twig', [
            'aides' => $aides,
            'aidecom'=>$aidecom,
        ]);
    }

}
