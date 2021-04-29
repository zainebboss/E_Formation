<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\BranchRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
/**
 * @Route("admin/formateurs")
 */
class FormateursController extends AbstractController
{

    // page index back
    /**
     * @Route("/", name="formateurs_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $search = $request->get('search', '');
        /*  $users = $this->getDoctrine()
              ->getRepository(User::class)
              ->findUsersByRole($search,'ROLE_FORMATEUR');
        */

        return $this->render('back/formateurs/index.html.twig', [
            //  'users' => $users,
            'search' => $search,
        ]);
    }
    //affichage table ajax back

    /**
     * @Route("/table", name="formateurs_table", methods={"GET","POST"})
     */
    public function table(Request $request, EngineInterface $engine)
    {
        $search = $request->get('search', '');
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findUsersByRole($search, 'ROLE_FORMATEUR');
        $response = new JsonResponse();
        $html = $engine->render('back/formateurs/table.html.twig', [
            'users' => $users,
            'search' => $search,
        ]);
        return $response->setData(
            array(
                'html' => $html,
            ));
    }


    private function getErrors($baseForm, $baseFormName)
    {
        $errors = array();
        if ($baseForm instanceof \Symfony\Component\Form\Form) {


            foreach ($baseForm->getErrors() as $key => $error) {

                $errors[] = $error->getMessage();
            }
            foreach ($baseForm->all() as $key => $child) {


                if (($child instanceof \Symfony\Component\Form\Form)) {
                    $cErrors = $this->getErrors($child, $baseFormName . "_" . $child->getName());
                    $errors = array_merge($errors, $cErrors);
                }

            }


        }
        return $errors;
    }

    /**
     * @Route("/new", name="formateurs_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->add('plainPassword', RepeatedType::class, [
            'required' => true,
            'type' => PasswordType::class,
            'error_bubbling' => true,
            'invalid_message' => 'Verifier votre mot de passe',

        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setRole('ROLE_FORMATEUR');
            $user->setRoles(array('ROLE_FORMATEUR'));
            $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('formateurs_index');
        }

        return $this->render('back/formateurs/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'errors' => $this->getErrors($form, $form->getName()),
            'new' => true
        ]);
    }


    /**
     * @Route("/{id}/edit", name="formateurs_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $encoder): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->add('plainPassword', RepeatedType::class, [
            'required' => false,
            'type' => PasswordType::class,
            'error_bubbling' => true,
            'invalid_message' => 'Verifier votre mot de passe',

        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getPlainPassword()) {
                $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($encoded);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('formateurs_index');
        }

        return $this->render('back/formateurs/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'errors' => $this->getErrors($form, $form->getName()),

        ]);
    }

    /**
     * @Route("/{id}", name="formateurs_delete", methods={"GET"})
     */
    public function delete(Request $request, User $user): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('formateurs_index');
    }

    /**
     * @Route("/{id}/enable", name="formateurs_enable", methods={"GET"})
     */
    public function enable(Request $request, User $user): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user->setEnable(true);
        $entityManager->flush();

        return $this->redirectToRoute('formateurs_index');
    }

    /**
     * @Route("/disable/{id}", name="formateurs_disable", methods={"GET"})
     */
    public function disable(Request $request, User $user): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user->setEnable(false);
        $entityManager->flush();

        return $this->redirectToRoute('formateurs_index');
    }

    /**
     * @Route("/exporter/formateurs_export", name="formateurs_export", methods={"GET"})
     */
    public function exportexcel(Request $request): Response
    {
        $search = $request->get('search', '');
        $items = $this->getDoctrine()
            ->getRepository(User::class)
            ->findUsersByRole($search, 'ROLE_FORMATEUR');
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Formateurs");
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

        $sheet->setCellValue('A1', "Nom & Prénom");
        $sheet->setCellValue('B1', "Email");
        $sheet->setCellValue('C1', "Tél");
        $sheet->setCellValue('D1', "Adresse");

        $i = 2;
        foreach ($items as $item) {
            $sheet->setCellValue('A' . $i, $item->getNom().' '.$item->getPrenom());
            $sheet->setCellValue('B' . $i, $item->getEmail());
            $sheet->setCellValue('C' . $i, $item->getTelephone());
            $sheet->setCellValue('D' . $i, $item->getAdresse());

            $i++;
        }

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'Formateurs.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

    }
    /**
     * @Route("/exporter/pdf", name="formateurs_export_pdf", methods={"GET"})
     */
    public function exportpdf( Request $request )
    {

        $search = $request->get('search', '');
        $items = $this->getDoctrine()
            ->getRepository(User::class)
            ->findUsersByRole($search, 'ROLE_FORMATEUR');
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('back/formateurs/pdf.html.twig', [
            'items' => $items,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream('Formateurs.pdf', [
            "Attachment" => true
        ]);


        $dompdf->output();
        die;

    }

}
