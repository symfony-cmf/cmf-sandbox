<?php

namespace Sandbox\AdminBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TreeTest extends WebTestCase
{
    public function testTreeRenderedInDOM()
    {
        $client = $this->createClient();
        
        $crawler = $client->request('GET', '/admin/sandbox/main/editablestaticcontent/list');
        
        $this->assertEquals(1, $crawler->filter('#tree')->count(), 'The tree is in the DOM');
    }
}