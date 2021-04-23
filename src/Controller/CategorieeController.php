<?php

namespace App\Controller;

use App\Entity\Categoriee;
use App\Form\CategorieeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categoriee")
 */
class CategorieeController extends AbstractController
{
    /**
     * @Route("/", name="categoriee_index", methods={"GET"})
     */
    public function index(): Response
    {
        $categoriees = $this->getDoctrine()
            ->getRepository(Categoriee::class)
            ->findAll();

        return $this->render('categoriee/index.html.twig', [
            'categoriees' => $categoriees,
        ]);
    }

    /**
     * @Route("/new", name="categoriee_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $categoriee = new Categoriee();
        $form = $this->createForm(CategorieeType::class, $categoriee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categoriee);
            $entityManager->flush();

            return $this->redirectToRoute('categoriee_index');
        }

        return $this->render('categoriee/new.html.twig', [
            'categoriee' => $categoriee,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categoriee_show", methods={"GET"})
     */
    public function show(Categoriee $categoriee): Response
    {
        return $this->render('categoriee/show.html.twig', [
            'categoriee' => $categoriee,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="categoriee_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Categoriee $categoriee): Response
    {
        $form = $this->createForm(CategorieeType::class, $categoriee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categoriee_index');
        }

        return $this->render('categoriee/edit.html.twig', [
            'categoriee' => $categoriee,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categoriee_delete", methods={"POST"})
     */
    public function delete(Request $request, Categoriee $categoriee): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoriee->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categoriee);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categoriee_index');
    }
}
