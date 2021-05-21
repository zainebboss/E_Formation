<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
            'g-recaptcha-response'=>$request->request->get('g-recaptcha-response'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if ($credentials['g-recaptcha-response']) {
            $g_response = $credentials['g-recaptcha-response'];
            $resp = $this->verifyRecaptcha($g_response);
            if (!$resp) {
                throw new CustomUserMessageAuthenticationException('captcha invalide.');
            }

            $token = new CsrfToken('authenticate', $credentials['csrf_token']);
            if (!$this->csrfTokenManager->isTokenValid($token)) {
                throw new InvalidCsrfTokenException();
            }
            $user = $this->entityManager->getRepository(User::class)->findUserByEmail($credentials['email']);
            if (!$user) {
                // fail authentication with a custom error
                throw new CustomUserMessageAuthenticationException('Email introuvable.');
            }

            return $user;
        }
        else {
            throw new CustomUserMessageAuthenticationException('captcha invalide.');
        }




    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // Check the user's password or other credentials and return true or false
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        $user = $token->getUser();
        if(in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
            $url = 'dashboard_admin';
        }
        elseif(in_array('ROLE_FORMATEUR', $user->getRoles())) {
            $url = 'dashboard_formateur';
        }
        elseif(in_array('ROLE_APPRENANT', $user->getRoles())) {
            $url = 'index';
        }
        else{
            $url = 'index';
        }
        return new RedirectResponse($this->urlGenerator->generate($url));

    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
    private function verifyRecaptcha($recaptchaResponse)
    {

        $data = array(
            "secret"=>"6Lc5D-wUAAAAAPn39VRKsyHUhWt2XxTnlPvcnftv",
            "response"=>$recaptchaResponse
        );

        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);
        $response=json_decode($response,true);
        return !empty($response['success']);



        /*   $url = "https://www.google.com/recaptcha/api/siteverify";
           $ch = curl_init();
           curl_setopt($ch, CURLOPT_URL, $url);
           curl_setopt($ch, CURLOPT_HEADER, 0);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
           curl_setopt($ch, CURLOPT_POST, true);
           curl_setopt($ch, CURLOPT_POSTFIELDS, array(
               "secret"=>"6Lc5D-wUAAAAAPn39VRKsyHUhWt2XxTnlPvcnftv",
               "response"=>$recaptchaResponse
               ));
           $response = curl_exec($ch);
           curl_close($ch);
           // $data = json_decode($response);
           $result = json_decode($response, true);

           return !empty($result['success']);
        */
    }
}
