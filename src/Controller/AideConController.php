<?php

namespace App\Controller;

use App\Entity\Aide;
use App\Entity\Aidecom;
use App\Form\AideFormType;
use App\Repository\AideRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            $this->addFlash("succes","la ajoute a éte effectuée");
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
    public function show(Aide $aide,Aidecom $aidecom): Response
    {

        return $this->render('aide_con/show.html.twig', [
            'aide' => $aide,
            'aidecom' => $aidecom,
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
            $this->addFlash("succes","la modification a éte effectuée");
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
            $this->addFlash("succes","la suppression a éte effectuée");
        }

        return $this->redirectToRoute('aide_con_index');
    }
    public function createAction(Request $request)
    {

        $form = $this->createFormBuilder(new Article());
    }




    /**
     * @Route("/asearch",name="ajax_search",methods={"GET"},requirements={"q":"\value+"})
     * return Mixed
     */
    public function searchAction(Request $request ,AideRepository  $repository)
    {
        // dd('1');
        $em = $this->getDoctrine()->getManager();
        $requestString =$request->query->get('q');

//  dd($requestString);
        $posts =  $em->getRepository(Aide::class)->findByNom($requestString);
        //  dd($posts);
        if(!$posts) {
                $result['posts']['error'] = "Aucune Aide avec cette nom :( ";
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
            $realEntities[$value['id']] = $value['sujet'];
        }

        return $realEntities;
    }

    /**
     * @Route("/imprimer/{id}", name="impp", methods={"GET"})
     */
    public function pdf(Aide $aide,Aidecom $aidecom)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('aide_con/showF.html.twig', [
            'aides' => $aide,
            'aidescom'=>$aidecom,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }
}
