<?php

namespace Sandbox;

use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class AdminTest extends WebTestCase
{
    protected $pool;
    protected $router;

    public function setUp()
    {
        parent::setUp();
        $this->pool = $this->getContainer()->get('sonata.admin.pool');
        $this->router = $this->getContainer()->get('router');
        $this->client = $this->createClientAuthenticated();
    }

    public function testAdmin()
    {
        $adminGroups = $this->pool->getAdminGroups();
        $admins = array();

        foreach (array_keys($adminGroups) as $adminName) {
            $admins = array_merge($admins, $this->pool->getAdminsByGroup($adminName));
        }

        foreach ($admins as $admin) {
            $this->doTestReachableAdminRoutes($admin);
        }
    }

    protected function doTestReachableAdminRoutes($admin)
    {
        $routeCollection = $admin->getRoutes();

        foreach ($routeCollection->getElements() as $route) {
            try {
                $url = $this->router->generate($route->getDefault('_sonata_name'), array(
                    '_locale' => 'en'
                ));
            } catch (MissingMandatoryParametersException $e) {
                // do not try and load pages with parameters, e.g. edit, show, etc.
                continue;
            }

            $crawler = $this->client->request('GET', $url);
            $res = $this->client->getResponse();
            $statusCode = $res->getStatusCode();

            // hack around apparently mal-defined routes
            if ($statusCode != 200) {
                $exceptionMessage = $crawler->filter('.text-exception h1')->html();

                // we cannot determine if sonata routes need a POST
                if (false !== strpos($exceptionMessage, 'POST expected')) {
                    continue;
                }

                // the export route has no _format requirement
                if (false !== strpos($exceptionMessage, 'Export in format `` is not allowed')) {
                    continue;
                }
            }

            $this->assertEquals(200, $statusCode, sprintf(
                'URL %s returns 200 OK HTTP Code', $url
            ));
        }
    }
}
