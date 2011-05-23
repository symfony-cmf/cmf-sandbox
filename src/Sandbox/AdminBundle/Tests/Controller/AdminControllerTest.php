<?php
namespace Sandbox\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/admin/');

        $this->assertTrue($crawler->filter('html:contains("Welcome to the Admin!")')->count() > 0);
    }
}