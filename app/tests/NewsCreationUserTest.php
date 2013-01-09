<?php

namespace Sandbox;

/**
 * Use Saucelabs to test the creation of content with Create.js
 */
class NewsCreationUserTest extends SaucelabsWebTestCase
{
    private $pageTitle = 'News';

    public function setUp()
    {
        parent::setUp();
        $this->setBrowserUrl($this->newsUrl);
    }

    public function testCreateNewsAndRoutes()
    {
        //page loaded correctly?
        $this->assertContains($this->pageTitle, $this->title());

        //TODO: test the news creation once it is possible to reach the news url...
    }
}
