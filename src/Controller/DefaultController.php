<?php


namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Inscription;
use App\Entity\User;
use App\Form\ApprenantType;
use App\Form\InscriptionType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/")
 */
class DefaultController extends AbstractController
{


    /**
     * @Route("/", name="index", methods={"GET","POST"})
     */
    public function index( )
    {
        return $this->render('front/index.html.twig');
    }
    /**
     * @Route("/updatepassword", name="updatepassword", methods={"GET","POST"})
     */
    public function updatePassword(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $encoder): Response
    {

        if ($request->isMethod('post')) {
            $acpassword = $request->get('acpassword','');
            $nvpassword = $request->get('nvpassword','');
            $repeatpassword = $request->get('repeatpassword','');
            $user = $this->getUser();
            if((strlen($acpassword)==0)||(strlen($nvpassword)==0)||(strlen($repeatpassword)==0)){
                $this->addFlash('errors', 'Vous devez inserer toutes les informations');
            }
            elseif ($encoder->isPasswordValid($user, $acpassword)  && ($nvpassword == $repeatpassword)) {
                $encoded = $encoder->encodePassword($user, $nvpassword);
                $user->setPassword($encoded);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Votre mot de passe est modifier avec succès');
            } else {
                if (!$user->getPassword()!= $acpassword)
                    $this->addFlash('errors', "Ancien mot de passe erroné");
                if ($nvpassword != $repeatpassword)
                    $this->addFlash('errors', "Vérifier votre nouveau mot de passe");
            }
            return $this->redirectToRoute('updatepassword');
        }
        return $this->render('front/updatepassword.html.twig');
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
     * @Route("/editProfil", name="editProfil", methods={"GET","POST"})
     */
    public function edit(Request $request): Response
    {
        $user=$this->getUser();
        $form = $this->createForm(ApprenantType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('index');
        }

        return $this->render('front/profil.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'errors' => $this->getErrors($form, $form->getName()),

        ]);
    }
    /**
     * @Route("/admin/dashboard", name="dashboard_admin", methods={"GET","POST"})
     */
    public function dashboardAdmin( )
    {
        $apprenants = $this->getDoctrine()
            ->getRepository(User::class)
            ->findUsersByRole('','ROLE_APPRENANT');
        $formateurs = $this->getDoctrine()
            ->getRepository(User::class)
            ->findUsersByRole('','ROLE_FORMATEUR');
        $formations = $this->getDoctrine()
            ->getRepository(Formation::class)
            ->findAll();
        $inscriptions = $this->getDoctrine()
            ->getRepository(Inscription::class)
            ->findAll();
        $formationsname='[';
        $formationsnb='[';
        foreach($formations as $formation){
            $name=htmlspecialchars('"'.$formation->getTitre().'"');
            $formationsname=$formationsname.'"'.$formation->getTitre().'",';
            $formationsnb=$formationsnb.count($formation->getInscriptions()).',';

        }

        $formationsname=substr($formationsname, 0, -1);
        $formationsnb=substr($formationsnb, 0, -1);
        $formationsname=$formationsname.']';
        $formationsnb=$formationsnb.']';





        $inscriptions2020 = array();
        $inscriptions2020['01'] = 0;
        $inscriptions2020['02'] = 0;
        $inscriptions2020['03'] = 0;
        $inscriptions2020['04'] = 0;
        $inscriptions2020['05'] = 0;
        $inscriptions2020['06'] = 0;
        $inscriptions2020['07'] = 0;
        $inscriptions2020['08'] = 0;
        $inscriptions2020['09'] = 0;
        $inscriptions2020['10'] = 0;
        $inscriptions2020['11'] = 0;
        $inscriptions2020['12'] = 0;
        $inscriptions2021 = array();
        $inscriptions2021['01'] = 0;
        $inscriptions2021['02'] = 0;
        $inscriptions2021['03'] = 0;
        $inscriptions2021['04'] = 0;
        $inscriptions2021['05'] = 0;
        $inscriptions2021['06'] = 0;
        $inscriptions2021['07'] = 0;
        $inscriptions2021['08'] = 0;
        $inscriptions2021['09'] = 0;
        $inscriptions2021['10'] = 0;
        $inscriptions2021['11'] = 0;
        $inscriptions2021['12'] = 0;
        foreach ($inscriptions as $inscription) {
            $year=$inscription->getDateInscrit()->format('Y');
            $month = $inscription->getDateInscrit()->format('m');
            if($year=='2020'){
                $inscriptions2020[$month] = $inscriptions2020[$month] + 1;
            }
            if($year=='2021'){
                $inscriptions2021[$month] = $inscriptions2021[$month] + 1;
            }

        }
        $stringmonths2020 = '[' . $inscriptions2020['01'] . ',' . $inscriptions2020['02'] . ',' . $inscriptions2020['03'] . ',' . $inscriptions2020['04'] . ',' .
            $inscriptions2020['05'] . ',' . $inscriptions2020['06'] . ',' . $inscriptions2020['07'] . ',' . $inscriptions2020['08'] . ',' . $inscriptions2020['09'] . ','
            . $inscriptions2020['10'] . ',' . $inscriptions2020['11'] . ',' . $inscriptions2020['12'] . ']';
        $stringmonths2021 = '[' . $inscriptions2021['01'] . ',' . $inscriptions2021['02'] . ',' . $inscriptions2021['03'] . ',' . $inscriptions2021['04'] . ',' .
            $inscriptions2021['05'] . ',' . $inscriptions2021['06'] . ',' . $inscriptions2021['07'] . ',' . $inscriptions2021['08'] . ',' . $inscriptions2021['09'] . ','
            . $inscriptions2021['10'] . ',' . $inscriptions2021['11'] . ',' . $inscriptions2021['12'] . ']';
        return $this->render('back/dashboardAdmin.html.twig',[
            'apprenants'=>$apprenants,
            'formateurs'=>$formateurs,
            'formations'=>$formations,
            'inscriptions'=>$inscriptions,
            'formationsname'=>$formationsname,
            'formationsnb'=>$formationsnb,
            'stringmonths2020'=>$stringmonths2020,
            'stringmonths2021'=>$stringmonths2021,

        ]);
    }
    /**
     * @Route("/formateur/dashboard", name="dashboard_formateur", methods={"GET","POST"})
     */
    public function dashboardFormateur( )
    {
        return $this->render('back/dashboardFormateur.html.twig');
    }

}