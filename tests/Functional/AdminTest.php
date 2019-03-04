<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Functional;

use Sonata\AdminBundle\Admin\AdminInterface;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class AdminTest extends WebTestCase
{
    protected $pool;

    protected $router;

    protected $verifiablePatterns = [
        '/app/demoseocontent/list',
        '/app/demoseocontent/create',
        '/app/demoseocontent/{id}/edit',
        '/app/demoseocontent/{id}/delete',
        '/cmf/block/simpleblock/list',
        '/cmf/block/simpleblock/create',
        '/cmf/block/simpleblock/{id}/edit',
        '/cmf/block/simpleblock/{id}/delete',
        '/cmf/block/containerblock/list',
        '/cmf/block/containerblock/create',
        '/cmf/block/containerblock/{id}/edit',
        '/cmf/block/containerblock/{id}/delete',
        '/cmf/block/referenceblock/list',
        '/cmf/block/referenceblock/create',
        '/cmf/block/referenceblock/{id}/edit',
        '/cmf/block/referenceblock/{id}/delete',
        '/cmf/block/actionblock/list',
        '/cmf/block/actionblock/create',
        '/cmf/block/actionblock/{id}/edit',
        '/cmf/block/actionblock/{id}/delete',
        '/cmf/routing/route/list',
        '/cmf/routing/route/create',
        '/cmf/routing/route/{id}/edit',
        '/cmf/routing/route/{id}/delete',
        '/cmf/routing/redirectroute/list',
        '/cmf/routing/redirectroute/create',
        '/cmf/routing/redirectroute/{id}/edit',
        '/cmf/routing/redirectroute/{id}/delete',
        '/cmf/menu/menu/list',
        '/cmf/menu/menu/create',
        '/cmf/menu/menu/{id}/edit',
        '/cmf/menu/menu/{id}/delete',
        '/cmf/menu/menunode/list',
        '/cmf/menu/menunode/create',
        '/cmf/menu/menunode/{id}/edit',
        '/cmf/menu/menunode/{id}/delete',
    ];

    protected static $testedPatterns = [];

    public function setUp()
    {
        parent::setUp();
        $this->pool = $this->getContainer()->get('sonata.admin.pool');
        $this->router = $this->getContainer()->get('router');
        $this->client = $this->createClientAuthenticated();
        $this->dm = $this->getContainer()->get('doctrine_phpcr.odm.default_document_manager');
    }

    public function getAdmin()
    {
        $pool = $this->getContainer()->get('sonata.admin.pool');
        $adminGroups = $pool->getAdminGroups();
        $admins = function () use ($adminGroups, $pool) {
            foreach (array_keys($adminGroups) as $adminName) {
                yield $pool->getAdminsByGroup($adminName);
            }
        };

        return $admins();
    }

    /**
     * @dataProvider getAdmin
     */
    public function testAdmin(AdminInterface $admin)
    {
        $route = $this->doTestReachableAdminRoutes($admin);

        $this->assertTrue(\in_array($route, $this->verifiablePatterns, true));

        $diffCountBefore = \count(array_diff($this->verifiablePatterns, self::$testedPatterns));
        self::$testedPatterns[] = $route;
        $diffCountAfter = \count(array_diff($this->verifiablePatterns, self::$testedPatterns));

        // verify that at the end is nothing in diff
        $this->assertSame($diffCountBefore - 1, $diffCountAfter, 'Each admin should be verified.');
    }

    protected function doTestReachableAdminRoutes(AdminInterface $admin)
    {
        $routeCollection = $admin->getRoutes();
        $routeParams = ['_locale' => 'en'];

        foreach ($routeCollection->getElements() as $route) {
            $requirements = $route->getRequirements();

            // fix this one later
            if (strpos($route->getPath(), 'export')) {
                continue;
            }

            // these don't all work atm
            if (strpos($route->getPath(), 'show')) {
                continue;
            }

            // batch routes from new admin integration would need POST request
            if (strpos($route->getPath(), 'batch')) {
                continue;
            }

            // do not test POST routes
            if (isset($requirements['_method'])) {
                if ('GET' !== $requirements['_method']) {
                    continue;
                }
            }

            // if an ID is required, try and find a document to test
            if (isset($requirements['id'])) {
                $document = $admin->createQuery('list')->execute()->first();
                if ($document) {
                    $node = $this->dm->getNodeForDocument($document);
                    $routeParams['id'] = $node->getPath();
                }
                // we should throw an exception here maybe and fix the missing fixtures
            }

            try {
                $url = $this->router->generate($route->getDefault('_sonata_name'), $routeParams);
            } catch (MissingMandatoryParametersException $e) {
                // do not try and load pages with parameters, e.g. edit, show, etc.
                continue;
            }

            $this->client->request('GET', $url);
            $res = $this->client->getResponse();

            $this->assertResponseSuccess($res, $url);

            return $route->getPath();
        }
    }
}
