<?php

namespace App\Controller\Api;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/v1")
 */
class SecurityController extends AbstractController
{


    /**
     * @Route("/login", name="login", methods={"POST","GET"})
     */
    public function login(UserPasswordEncoderInterface $encoder, Request $request): Response
    {
        $message = "error";
        $statut = 200;
        //$email = "";
       // $token = $request->headers->get('Authorization');
        /*  if ($token){

              try {

                  $data = $JWTEncoder->decode($token);
                  $email = $data['email'];

              } catch (\Exception $e) {
                  $message = $e->getMessage();
                  $data = array("code" => 3, "data" => $message);
                  $statut = 401;
                  //  throw new \Symfony\Component\Security\Core\Exception\BadCredentialsException($e->getMessage(), 0, $e);
              }*/
        $email = $request->get("email", "");
        $password = $request->get("password", "");
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->findUserByEmail($email);

        if ($user) {
            if (($user->getPassword()==$password) and ($user->hasRole('ROLE_APPRENANT'))) {
                // $token = $JWTManager->decode()
                $statut = 200;
                $message = "success";
                $data = array(
                    "id" => $user->getId().'',
                    "tel" => $user->getTelephone(),
                    "nom" => $user->getNom(),
                    "prenon" => $user->getPrenom(),
                    "adresse" => $user->getAdresse(),
                    "code" => "3",
                );
            }
            else {

                $message = "error";
                $data =  array("code" => "1", "message" => "password is incorrect");
                $statut = 200;
            }
        }

        else {

            $message = "error";
            $data = array("code" => "2", "data" => "username is incorrect");
            $statut = 200;
        }
        /*  }
        else{
             $message = "Invalid Token";
             $data = array("code" => 1, "data" => $message);
             $statut = 404;
         }*/


        return new JsonResponse(array(
            'data' => $data,
        ), $statut);


    }


}
