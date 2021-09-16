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

use App\Kernel;
use Doctrine\Common\DataFixtures\Purger\PHPCRPurger;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class WebTestCase extends BaseTestCase
{
    protected static $fixturesLoaded = false;

    public function setUp()
    {
        if (self::$fixturesLoaded) {
            return;
        }

        (new PHPCRPurger($this->getDbManager('PHPCR')->getOm()))->purge();
        $this->db('PHPCR')->loadFixtures([
                'App\DataFixtures\PHPCR\LoadStaticPageData',
                'App\DataFixtures\PHPCR\LoadMenuData',
                'App\DataFixtures\PHPCR\LoadRoutingData',
        ]);

        self::$fixturesLoaded = true;
        parent::setUp();
    }

    /**
     * @return string
     */
    public static function getKernelClass(): string
    {
        return Kernel::class;
    }

    /**
     * @param array $options
     * @param array $server
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createClientAuthenticated(array $options = [], array $server = [])
    {
        $server = array_merge(
            $server,
            [
                'PHP_AUTH_USER' => 'username',
                'PHP_AUTH_PW' => 'pa$$word',
            ]
        );

        return self::createClient($options, $server);
    }

    /**
     * Method to assert a 200 response code.
     *
     * This code is taken from symfony-cmf/Testing.
     *
     * @param Response $response
     * @param string   $url
     */
    protected function assertResponseSuccess(Response $response, $url = '')
    {
        libxml_use_internal_errors(true);

        $dom = new \DomDocument();
        $dom->loadHTML($response->getContent());

        $xpath = new \DOMXpath($dom);
        $result = $xpath->query('//div[contains(@class,"text-exception")]/h1');
        $exception = null;
        if ($result->length) {
            $exception = $result->item(0)->nodeValue;
        }

        $this->assertEquals(
            200,
            $response->getStatusCode(),
            $exception ? 'Exception: "'.trim($exception).'" on url: '.$url : null
        );
    }
}
