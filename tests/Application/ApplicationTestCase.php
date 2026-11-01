<?php

declare(strict_types=1);

namespace App\Tests\Application;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApplicationTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected Router $router;

    protected function setUp(): void {
        parent::setUp();

        $this->client = static::createClient();
        $this->router = static::getContainer()->get('router');
    }

    public static function setUpBeforeClass(): void {
        self::setBrowserKitAssertionsAsVerbose(false);
    }
}
