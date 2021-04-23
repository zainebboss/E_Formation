<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Form\SeanceType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/seance")
 */
class SeanceController extends AbstractController
{
    /**
     * @Route("/", name="seance_index", methods={"GET"})
     */
    public function index(): Response
    {
        $seances = $this->getDoctrine()
            ->getRepository(Seance::class)
            ->findAll();

        return $this->render('seance/index.html.twig', [
            'seances' => $seances,
        ]);
    }

    /**
     * @Route("/new", name="seance_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $seance = new Seance();
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($seance);
            $entityManager->flush();

            return $this->redirectToRoute('seance_index');
        }

        return $this->render('seance/new.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idSeance}", name="seance_show", methods={"GET"})
     */
    public function show(Seance $seance): Response
    {
        return $this->render('seance/show.html.twig', [
            'seance' => $seance,
        ]);
    }

    /**
     * @Route("/{idSeance}/edit", name="seance_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Seance $seance): Response
    {
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('seance_index');
        }

        return $this->render('seance/edit.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idSeance}", name="seance_delete", methods={"POST"})
     */
    public function delete(Request $request, Seance $seance): Response
    {
        if ($this->isCsrfTokenValid('delete'.$seance->getIdSeance(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($seance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('seance_index');
    }

    /**
     * @Route("/seance/recherche", name="recherche")

     * @param Request $request
     * @return Response
     */
      public function Recherche( Request $request): Response
      {
          $data=$request->get('search');
          $seances =$this->getDoctrine()
              ->getRepository(Seance::class)
              ->findBy(['lien'=>$data]);

          return $this->render('seance/index.html.twig', [
              'seances' => $seances,
          ]);
      }

}
