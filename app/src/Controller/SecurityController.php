<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Totp\TotpAuthenticatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/auth/login', name: 'app_auth_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('/auth/login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'last_username' => $authenticationUtils->getLastUsername()
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/auth/logout', name: 'app_auth_logout')]
    public function logout()
    {
        throw new \Exception('Logout should neve be reached!');
    }

    #[Route('/auth/2fa/enable', name: 'app_2fa_enable')]
    #[IsGranted('ROLE_USER')]
    public function enable2fa(TotpAuthenticatorInterface $totpAuthenticator, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        if (!$user->isTotpAuthenticationEnabled()) {
            $user->setTotpSecret($totpAuthenticator->generateSecret());

            $entityManager->flush();
        }

        return $this->render('auth/enable2fa.html.twig');
    }

    #[Route('/auth/2fa/qr-code', name: 'app_qr_code' )]
    public function displayGoogleAuthenticatorQrCode(QrCodeGenerator $qrCodeGenerator)
    {
        // $qrCode is provided by the endroid/qr-code library. See the docs how to customize the look of the QR code:
        // https://github.com/endroid/qr-code
        $qrCode = $qrCodeGenerator->getTotpQrCode($this->getUser());

        return new Response($qrCode->writeString(), 200, ['Content-Type' => 'image/png']);
    }
}
