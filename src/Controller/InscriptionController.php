<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Inscription;
use App\Form\AvisType;
use App\Form\InscriptionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inscription")
 */
class InscriptionController extends AbstractController
{
    /**
     * @Route("/", name="inscription_index", methods={"GET"})
     */
    public function index(): Response
    {
        $inscriptions = $this->getDoctrine()
            ->getRepository(Inscription::class)
            ->findListInscription();

        return $this->render('back/inscription/index.html.twig', [
            'inscriptions' => $inscriptions,
            'search' => '',
        ]);
    }
    /**
     * @Route("/new", name="inscription", methods={"GET","POST"})
     */
    public function inscription(Request $request): Response
    {
        $inscription = new Inscription();
        $form = $this->createForm(InscriptionType::class, $inscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $inscription->setUtilisateur($this->getUser());
            $entityManager->persist($inscription);
            $entityManager->flush();
            $this->addFlash('success', "Votre inscription est enregistrée avec succes");
            return $this->redirectToRoute('index');
        }

        return $this->render('front/inscription.html.twig', [
            'inscription' => $inscription,
            'form' => $form->createView(),
            // 'errors' => $this->getErrors($form, $form->getName()),
        ]);
    }

}
