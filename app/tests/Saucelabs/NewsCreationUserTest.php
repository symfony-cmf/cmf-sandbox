<?php

namespace Sandbox\Saucelabs;

/**
 * Use Saucelabs to test the creation of content with Create.js
 */
class NewsCreationUserTest extends SaucelabsWebTestCase
{
    private $pageTitle = 'News';

    public function setUp()
    {
        parent::setUp();
        $this->setBrowserUrl($this->homeUrl);
    }

    public function testCreateNewsAndRoutes()
    {
        //common variables
        $createdNewsTitle = 'News title from sauce test';
        $createdNewsContent = 'And this is the news content from sauce test as well...';
        $today = date("Y-m-d");

        /*
         * Go first in the home url during setUp and change then to the
         * news URL. This work around is due to the trailing slash problem of
         * selenium:
         * If $this->setBrowserUrl($this->homeUrl); is called, the URL
         * sent to the server is http://cmf.lo/app_test.php/en/news/
         * what gives a 404.
         *
         * TODO: remove this work around
         */
        $this->url('/en/news');
        //page loaded correctly?
        $this->assertContains($this->pageTitle, $this->title());

        //enter the edit mode
        $editLink = $this->byId('midgardcreate-edit');
        $editLink->click();
        //cancel should now be in the button content
        $cancelLink = $this->byId('midgardcreate-edit');
        $this->assertContains("Cancel", $cancelLink->text());

        //click the add button
        $addButton = $this->byCss('.newsoverview button:last-child');
        $addButton->click();

        //write the news title and content
        $newsTitle = $this->byXPath('//a[contains(text(), "[cw:headline]")]');
        $newsTitle->value($createdNewsTitle);
        $newsTitle = $this->byXPath('//div[contains(text(), "[ar:articleBody]")]');
        $newsTitle->value($createdNewsContent);

        //save the changes
        $this->byId('midgardcreate-save')->click();

        //leave the edit mode
        $this->byId('midgardcreate-edit')->click();
        $editLink = $this->byId('midgardcreate-edit');
        $this->assertContains("Edit", $editLink->text());

        //reload the current page to ensure the changes have been persisted
        $this->url('/en/news');

        //check the creation date
        $creationDate = $this->byCss('div.newsoverview li:last-child span.newsdate');
        $this->assertEquals($today, $creationDate->text());

        //click the news just created
        $newsTitle = $this->byXPath('//a[contains(text(), "'. $createdNewsTitle .'")]');
        $newsTitle->click();

        //check that the created content, title and date are in the page
        $this->assertContains($createdNewsTitle, $this->title());
        $newsTitle = $this->byCss('h2.my-title');
        $this->assertEquals($createdNewsTitle, $newsTitle->text());
        $newsContent = $this->byCss('div#content-container p');
        $this->assertEquals($createdNewsContent, $newsContent->text());
        $creationDate = $this->byCss('div.subtitle');
        $this->assertEquals('Date: ' . $today, $creationDate->text());
    }
}
