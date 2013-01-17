<?php

namespace Sandbox\Saucelabs;

use Sauce\Sausage\WebDriverTestCase;
use Sandbox\FixturesLoader;

/**
 * Base class for all CMF Sandbox frontend tests using Saucelabs
 */
abstract class SaucelabsWebTestCase extends WebDriverTestCase
{
    protected $homeUrl = 'http://cmf.lo/app_test.php';
    protected $newsUrl = 'http://cmf.lo/app_test.php/en/news';

    public static $browsers = array(
        // run FF17 on Linux on Sauce
        array(
            'browserName' => 'firefox',
            'desiredCapabilities' => array(
                'version' => '17',
                'platform' => 'Linux'
            ),
        )
    );

    public function setUp()
    {
        parent::setUp();

        //loads the fixtures through an instance of WebTestCase
        $webTestCase = new FixturesLoader();
        $webTestCase->setUp();
    }
}
