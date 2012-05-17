<?php

use Liip\FunctionalTestBundle\Test\WebTestCase;

class HomepageTest extends WebTestCase
{
	public function testRedirectToDefaultLanguage()
	{
		$client = $this->createClient();

		$client->request('GET', '/');

		$this->assertEquals(301, $client->getResponse()->getStatusCode());

		$client->followRedirect();

		$this->assertEquals('http://localhost/en', $client->getRequest()->getUri());
	}
}