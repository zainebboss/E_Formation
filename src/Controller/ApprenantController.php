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
 * @Route("/apprenant")
 */
class ApprenantController extends AbstractController
{
    /**
     * @Route("/", name="apprenant_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $search = $request->get('search', '');
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findUsersByRole($search, 'ROLE_APPRENANT');

        return $this->render('back/apprenant/index.html.twig', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    /**
     * @Route("/table", name="apprenant_table", methods={"GET","POST"})
     */
    public function table(Request $request, EngineInterface $engine)
    {
        $search = $request->get('search', '');
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findUsersByRole($search, 'ROLE_APPRENANT');
        $response = new JsonResponse();
        $html = $engine->render('back/apprenant/table.html.twig',
            [
                'users' => $users,
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
     * @Route("/new", name="apprenant_new", methods={"GET","POST"})
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
            $user->setRole('ROLE_APPRENANT');
            $user->setRoles(array('ROLE_APPRENANT'));
            $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_login');
        }

        return $this->render('front/apprenant/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'errors' => $this->getErrors($form, $form->getName()),
            'new' => true
        ]);
    }


    /**
     * @Route("/{id}/edit", name="apprenant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
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
                $user->setPassword($user->getPlainPassword());
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
     * @Route("/{id}", name="apprenant_delete", methods={"GET"})
     */
    public function delete(Request $request, User $user): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('apprenant_index');
    }

    /**
     * @Route("/{id}/enable", name="apprenant_enable", methods={"GET"})
     */
    public function enable(Request $request, User $user): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user->setEnable(true);
        $entityManager->flush();

        return $this->redirectToRoute('apprenant_index');
    }

    /**
     * @Route("/disable/{id}", name="apprenant_disable", methods={"GET"})
     */
    public function disable(Request $request, User $user): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user->setEnable(false);
        $entityManager->flush();

        return $this->redirectToRoute('apprenant_index');
    }
}
