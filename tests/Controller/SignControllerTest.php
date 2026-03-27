<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SignControllerTest extends WebTestCase
{
    public function testLogin(): void {
        $client = static::createClient();
        $client->request('GET', '/login/');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testSignup(): void {
        $client = static::createClient();
        $client->request('GET', '/signup/');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testLogout(): void {
        $client = static::createClient();
        $client->request('GET', '/logout/');
        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }
}
