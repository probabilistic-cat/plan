<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Controller\MainController;
use App\Tests\Application\ApplicationTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainControllerTest extends ApplicationTestCase
{
    public function testIndex(): void {
        $this->client->request(Request::METHOD_GET, $this->router->generate(MainController::ROUTE_INDEX));
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
