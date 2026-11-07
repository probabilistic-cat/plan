<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use App\Service\PasswordHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class SignController extends AbstractController
{
    public const string ROUTE_LOGIN = 'route_login';
    public const string ROUTE_SIGNUP = 'route_signup';
    public const string ROUTE_LOGOUT = 'route_logout';

    #[Route('/login/', name: self::ROUTE_LOGIN)]
    public function login(AuthenticationUtils $authUtils): Response {
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('Sign/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'active_section' => '',
        ]);
    }

    #[Route('/signup/', name: self::ROUTE_SIGNUP)]
    public function signup(Request $request, EntityManagerInterface $em, PasswordHandler $passwordHandler): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->password = $passwordHandler->getHashedPassword($user, $user->plainPassword);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Your account has been created successfully.');

            return $this->redirectToRoute(MainController::ROUTE_INDEX);
        }

        return $this->render('Sign/signup.html.twig', [
            'form' => $form->createView(),
            'active_section' => '',
        ]);
    }

    #[Route('/logout', name: self::ROUTE_LOGOUT)]
    public function logout(): never {
        throw new \BadMethodCallException('Actual logout in security.yaml');
    }
}
