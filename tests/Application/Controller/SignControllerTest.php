<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Controller\SignController;
use App\Tests\Application\ApplicationTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SignControllerTest extends ApplicationTestCase
{
    public function testLogin(): void {
        $this->client->request(Request::METHOD_GET, $this->router->generate(SignController::ROUTE_LOGIN));
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testSignup(): void {
        $this->client->request(Request::METHOD_GET, $this->router->generate(SignController::ROUTE_SIGNUP));
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testLogout(): void {
        $this->client->request(Request::METHOD_GET, $this->router->generate(SignController::ROUTE_LOGOUT));
        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
    }
}
