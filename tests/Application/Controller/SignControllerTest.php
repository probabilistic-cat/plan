<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Controller\SignController;
use App\Entity\User;
use App\Form\UserType;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[CoversClass(SignController::class)]
#[CoversClass(UserType::class)]
final class SignControllerTest extends ApplicationTestCase
{
    private const string USER_MAIL = 'testuser@example.com';
    private const string USER_PASSWORD = 'Pass1234567890';

    public function testLogin(): void {
        UserFactory::createOne(['mail' => self::USER_MAIL, 'password' => self::USER_PASSWORD]);

        $crawler = $this->client->request(Request::METHOD_GET, $this->router->generate(SignController::ROUTE_LOGIN));
        self::assertResponseIsSuccessful();

        $form = $crawler->selectButton('Log in')->form([
            '_username' => self::USER_MAIL,
            '_password' => self::USER_PASSWORD,
        ]);

        $this->client->submit($form);
        self::assertResponseRedirects();
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();

        /** @var TokenStorageInterface $tokenStorage */
        $tokenStorage = self::getContainer()->get('security.token_storage');
        $token = $tokenStorage->getToken();
        $this->assertNotNull($token);
        $tokenUser = $token->getUser();
        $this->assertNotNull($tokenUser);
        $this->assertSame(self::USER_MAIL, $tokenUser->getUserIdentifier());
    }

    public function testLoginInvalid(): void {
        $crawler = $this->client->request(Request::METHOD_GET, $this->router->generate(SignController::ROUTE_LOGIN));

        $form = $crawler->selectButton('Log in')->form([
            '_username' => 'wrong@example.com',
            '_password' => 'wrongpassword',
        ]);

        $this->client->submit($form);
        self::assertResponseRedirects();
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();

        /** @var TokenStorageInterface $tokenStorage */
        $tokenStorage = self::getContainer()->get('security.token_storage');
        $token = $tokenStorage->getToken();
        $this->assertNull($token);
    }

    public function testSignup(): void {
        $crawler = $this->client->request(Request::METHOD_GET, $this->router->generate(SignController::ROUTE_SIGNUP));
        self::assertResponseIsSuccessful();

        $form = $crawler->selectButton('Sign up')->form([
            'user[name]' => 'testuser',
            'user[mail]' => self::USER_MAIL,
            'user[plainPassword][first]' => self::USER_PASSWORD,
            'user[plainPassword][second]' => self::USER_PASSWORD,
        ]);

        $this->client->submit($form);
        self::assertResponseRedirects();
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();

        $em = self::getContainer()->get(EntityManagerInterface::class);
        $user = $em->getRepository(User::class)->findOneBy(['mail' => self::USER_MAIL]);
        $this->assertInstanceOf(User::class, $user);
    }

    public function testSignupInvalid(): void {
        $crawler = $this->client->request(Request::METHOD_GET, $this->router->generate(SignController::ROUTE_SIGNUP));

        $name = 'testuser';
        $mail = self::USER_MAIL;
        $form = $crawler->selectButton('Sign up')->form([
            'user[name]' => $name,
            'user[mail]' => $mail,
            'user[plainPassword][first]' => '123',
            'user[plainPassword][second]' => '12',
        ]);

        $this->client->submit($form);
        self::assertResponseIsSuccessful();

        $em = self::getContainer()->get(EntityManagerInterface::class);
        $user = $em->getRepository(User::class)->findOneBy(['mail' => $mail]);
        $this->assertNull($user);
    }

    public function testLogout(): void {
        $user = UserFactory::createOne(['mail' => self::USER_MAIL, 'password' => self::USER_PASSWORD]);
        $this->client->loginUser($user);

        $this->client->request(Request::METHOD_GET, $this->router->generate(SignController::ROUTE_LOGOUT));
        self::assertResponseRedirects();
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();

        /** @var TokenStorageInterface $tokenStorage */
        $tokenStorage = self::getContainer()->get('security.token_storage');
        $token = $tokenStorage->getToken();
        $this->assertNull($token);
    }
}
