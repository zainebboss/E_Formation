<?php

namespace App\Controller\Api;

use App\Entity\Avis;
use App\Entity\User;
use App\Form\AvisType;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/v1/avis")
 */
class AvisController extends AbstractController
{
    /**
     * @Route("/getAvisByUser", name="getAvisByUser", methods={"GET"})
     */
    public function getAvisByUser(Request $request)
    {

        $message = "Invalid Token";
        $data = array();
        $statut = 401;


    /*    if ($token){
            try {
                $tokendecode = $JWTEncoder->decode($token);
                $email = $tokendecode['email'];
            }catch (\Exception $e){
                $statut = 401;

                return new JsonResponse(array(
                    'message' => $e->getMessage(),
                    'data' => $data,
                    'statut' => $statut
                ), $statut);
            }
            $statut = 200;


            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findUserByEmail( $email);

            if ($user) {
                if ( $user->hasRole('ROLE_APPRENANT')) {

                    $avis = $this->getDoctrine()
                        ->getRepository(Avis::class)
                        ->findAvis('',$user->getId());
                    $statut = 200;
                    $message = "success";
                    $data = array(
                        'avis' => $this->getAvis($avis)
                    );

                }
                else {

                    $message = "error";
                    $data = array("code" => 1, "data" => "password is incorrect");
                    $statut = 200;
                }

            }
            else {

                $message = "error";
                $data = array("code" => 2, "data" => "username is incorrect");
                $statut = 200;
            }


        }


        return new JsonResponse(array(
            'message' => $message,
            'data' => $data
        ), $statut);
    */
    }
    public function getAvis($avis)
    {
        $array= array();
        foreach ($avis as $a) {
            array_push($array, array(
                'note' => $a->getNote(),
                'commentaire' => $a->getCommentaire(),
                'nom' => $a->getFormateur()->getNom(),
                'prenom' => $a->getFormateur()->getPrenom()
            ));
        }
        return $array;
    }
}
