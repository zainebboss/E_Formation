<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\UtilisateurType;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/utilisateur")
 */
class UtilisateurController extends AbstractController
{

    /**
     * @Route("/addApprenant", name="addApprenant", methods={"GET","POST"})
     */
    public function new(Request $request, \Swift_Mailer $mailer): Response
    {

        $email = trim($request->get("email", ""));
        $password = $request->get("password", "");
        $nom = $request->get("nom", "");
        $prenom = $request->get("prenom", "");
        $telephone = $request->get("telephone", "");
        $adresse = $request->get("adresse", "");
        $date_naissance = $request->get("date_naissance", "");

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data = array("code" => "4", "message" => "email invalide");
        }
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findUserByEmail($email);
        if ($user) {
            $data = array("code" => "3", "message" => "email deja existe");
        } else {
            $utilisateur = new User();
            $utilisateur->setEmail($email);
            $utilisateur->setPassword($password);
            $utilisateur->setRole("ROLE_APPRENANT");
            $utilisateur->setNom($nom);
            $utilisateur->setPrenom($prenom);
            $utilisateur->setTelephone($telephone);
            $utilisateur->setAdresse($adresse);
            $utilisateur->setDateNaissance(new \ DateTime($date_naissance));
            $utilisateur->setEnable(true);
            $utilisateur->setRoles(array("ROLE_APPRENANT"));

            $em = $this->getDoctrine()->getManager();
            $em->persist($utilisateur);
            $em->flush();
            $message = (new \Swift_Message('Inscription E-Formation'))
               // ->setFrom(array('b2b.total@gmail.com' => "Inscription E-Formation"))
                ->setTo($email)
                ->setBody(
                   "Votre compte est crée avec success"
                );
            $result=$mailer->send($message);
            $data = array("code" => "1", "message" => "success", "result" => $result);

        }

        return new JsonResponse(array(
            'data' => $data,
        ), 200);

    }

    /**
     * @Route("/profile", name="profile", methods={"GET","POST"})
     */
    public function profile(Request $request): Response
    {
        $userId = $request->get('userId');
        $password = $request->get("password", "");
        $nom = $request->get("nom", "");
        $prenom = $request->get("prenom", "");
        $telephone = $request->get("telephone", "");
        $adresse = $request->get("adresse", "");
        $date_naissance = $request->get("date_naissance", "");
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);
        if ($user) {
            // if ($password != '') $user->setPassword($password);
            if ($nom != '') $user->setNom($nom);
            if ($prenom != '') $user->setPrenom($prenom);
            if ($telephone != '') $user->setTelephone($telephone);
            if ($adresse != '') $user->setAdresse($adresse);
            if ($date_naissance != '') $user->setDateNaissance(new \ DateTime($date_naissance));
            try {
                $em = $this->getDoctrine()->getManager()->flush();
                $data = array("code" => "1", "message" => "votre profil est modifie avec success");
            } catch (\Exception $ex) {
                $data = array("code" => "2", "message" => "exception" . $ex->getMessage());

            }

        } else {
            $data = array("code" => "2", "message" => "utilisateur non trouvé");

        }
        return new JsonResponse(array(
            'data' => $data,
        ), 200);

    }


}
