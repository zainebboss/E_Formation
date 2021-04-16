<?php

namespace App\Controller;

use App\Entity\Aide;
use App\Form\Aide1Type;
use App\Form\AideFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

        return $this->render('aide_con/index.html.twig', [
            'aides' => $aides,
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
}
