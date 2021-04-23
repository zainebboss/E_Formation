<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Templating\EngineInterface;
/**
 * @Route("admin/formateurs")
 */
class FormateursController extends AbstractController
{

    // page index back
    /**
     * @Route("/", name="formateurs_index", methods={"GET"})
     */
    public function index(Request  $request): Response
    {
        $search=$request->get('search','');
      /*  $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findUsersByRole($search,'ROLE_FORMATEUR');
      */

        return $this->render('back/formateurs/index.html.twig', [
          //  'users' => $users,
            'search'=>$search,
        ]);
    }
    //affichage table ajax back
    /**
     * @Route("/table", name="formateurs_table", methods={"GET","POST"})
     */
    public function table(Request $request, EngineInterface $engine)
    {
        $search=$request->get('search','');
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findUsersByRole($search,'ROLE_FORMATEUR');
        $response = new JsonResponse();
        $html = $engine->render('back/formateurs/table.html.twig', [
            'users' => $users,
            'search'=>$search,
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
             'new'=>true
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
            if($user->getPlainPassword()){
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
}
