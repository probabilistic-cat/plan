<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SignController extends AbstractController
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
        ]);
    }

    #[Route('/signup/', name: self::ROUTE_SIGNUP)]
    public function signup(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
    ): Response {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 3) Encode the password (you could also do this via Doctrine listener)
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getUserPlainPassword());
            $user->setUserPassword($hashedPassword);

            // 4) save the User!
            $em->persist($user);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute(MainController::ROUTE_INDEX);
        }

        return $this->render('Sign/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/logout', name: self::ROUTE_LOGOUT)]
    public function logout(): never {
        throw new \BadMethodCallException('Actual logout in security.yaml');
    }
}
