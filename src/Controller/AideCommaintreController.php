<?php

namespace App\Controller;

use App\Entity\Aidecom;
use App\Form\Aidecom2Type;
use App\Form\AideComFormType;
use App\Form\AideFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/aide/commaintre")
 */
class AideCommaintreController extends AbstractController
{


    /**
     * @Route("/new", name="aide_commaintre_index", methods={"GET"})
     */
    public function index(): Response
    {
        $aidecoms = $this->getDoctrine()
            ->getRepository(Aidecom::class)
            ->findAll();

        return $this->render('aide_commaintre/index.html.twig', [
            'aidecoms' => $aidecoms,
        ]);
    }


    /**
     * @Route("/", name="aide_commaintre_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $aidecom = new Aidecom();
        $form = $this->createForm(AideComFormType::class, $aidecom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($aidecom);
            $entityManager->flush();

            return $this->redirectToRoute('aide_commaintre_index');
        }

        return $this->render('aide_commaintre/new.html.twig', [
            'aidecom' => $aidecom,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="aide_commaintre_show", methods={"GET"})
     */
    public function show(Aidecom $aidecom): Response
    {
        return $this->render('aide_commaintre/show.html.twig', [
            'aidecom' => $aidecom,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="aide_commaintre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Aidecom $aidecom): Response
    {
        $form = $this->createForm(AideComFormType::class, $aidecom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('aide_commaintre_index');
        }

        return $this->render('aide_commaintre/edit.html.twig', [
            'aidecom' => $aidecom,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="aide_commaintre_delete", methods={"POST"})
     */
    public function delete(Request $request, Aidecom $aidecom): Response
    {
        if ($this->isCsrfTokenValid('delete'.$aidecom->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($aidecom);
            $entityManager->flush();
        }

        return $this->redirectToRoute('aide_commaintre_index');
    }
}
