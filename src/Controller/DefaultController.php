<?php


namespace App\Controller;

use App\Entity\Inscription;
use App\Entity\User;
use App\Form\InscriptionType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
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
     * @Route("/admin/dashboard", name="dashboard_admin", methods={"GET","POST"})
     */
    public function dashboardAdmin( )
    {
        return $this->render('back/dashboardAdmin.html.twig');
    }
    /**
     * @Route("/formateur/dashboard", name="dashboard_formateur", methods={"GET","POST"})
     */
    public function dashboardFormateur( )
    {
        return $this->render('back/dashboardFormateur.html.twig');
    }

}