<?php

namespace Sandbox\Saucelabs;

/**
 * Use Saucelabs to test the creation of content with Create.js
 *
 * ATTENTION: These tests might be failing due to a raise condition in doctrine.
 * When the 3 routes are created with parallel requests, an invalid and unnecessary
 * content document is created for the locale of the route being created.
 * TODO: paste the URL of the PR containing the unit test in doctrine repository
 */
class NewsCreationUserTest extends SaucelabsWebTestCase
{
    protected $newsUrl = 'http://cmf.lo/app_test.php/en/news';

    public function setUp()
    {
        parent::setUp();
        $this->setBrowserUrl($this->newsUrl);
    }

    /**
     * Test the case where a news is created and another updated in one shot
     */
    public function testNewsCreateAndUpdate()
    {
        //common variables
        $originalNewsPageTitle = 'News';
        $createdNewsTitle = 'News title from testNewsCreateAndUpdate';
        $createdNewsContent = 'News content from testNewsCreateAndUpdate';

        $this->assertEquals($originalNewsPageTitle, $this->title());

        //click on edit
        $this->enterEditMode();

        //click the add button
        $this->clickAddButton();

        //write the news title and content
        $newsTitle = $this->byXPath('//a[contains(text(), "[cw:headline]")]');
        $newsTitle->value($createdNewsTitle);
        $newsTitle = $this->byXPath('//div[contains(text(), "[ar:articleBody]")]');
        $newsTitle->value($createdNewsContent);

        //modify the collection content
        $collectionContent = $this->byCss('div.newsoverview p');
        $collectionContent->click();
        $collectionContent->value('Updated ');

        //click on save
        $this->saveChanges();

        //reload the current page to ensure the changes have been persisted
        $this->url('');
        $driver = $this;
        $newsPageLoaded = function() use ($driver, $originalNewsPageTitle) {
            //give some time to load the page
            return ($driver->title() == $originalNewsPageTitle);
        };
        $this->spinAssert("News page was not loaded", $newsPageLoaded);

        $collectionContent = $this->byCss('div.newsoverview p');
        $this->assertContains('Updated ', $collectionContent->text());
        $allNews = $this->byCss('div.newsoverview');
        $this->assertContains($createdNewsTitle, $allNews->text());
    }

    public function testNewsCreateRoutes()
    {
        //common variables
        $originalNewsPageTitle = 'News';
        $createdNewsTitle = 'News title from testNewsCreateRoutes';
        $createdNewsContent = 'And this is the news content from sauce test as well';
        $today = date("Y-m-d");

        //page loaded correctly?
        $this->assertEquals($originalNewsPageTitle, $this->title());

        //click on edit
        $this->enterEditMode();

        //cancel should now be in the button content
        $cancelLink = $this->byId('midgardcreate-edit');
        $this->assertContains("Cancel", $cancelLink->text());

        //click the add button
        $this->clickAddButton();

        //write the news title and content
        $newsTitle = $this->byXPath('//a[contains(text(), "[cw:headline]")]');
        $newsTitle->value($createdNewsTitle);
        $newsTitle = $this->byXPath('//div[contains(text(), "[ar:articleBody]")]');
        $newsTitle->value($createdNewsContent);

        //click on save
        $this->saveChanges();

        //click on cancel
        $this->leaveEditMode();

        //reload the current page to ensure the changes have been persisted
        $this->url('');
        $driver = $this;
        $newsPageLoaded = function() use ($driver, $originalNewsPageTitle) {
            //give some time to load the page
            return ($driver->title() == $originalNewsPageTitle);
        };
        $this->spinAssert("News page was not loaded", $newsPageLoaded);

        //check the creation date
        $creationDate = $this->byCss('div.newsoverview li:last-child span.newsdate');
        $this->assertEquals($today, $creationDate->text());

        //click the news just created
        $newsTitle = $this->byXPath('//a[contains(text(), "'. $createdNewsTitle .'")]');
        $newsTitle->click();
        $newsPageLoaded = function() use ($driver, $createdNewsTitle) {
            //give some time to load the page
            return ($driver->title() == $createdNewsTitle);
        };
        $this->spinAssert("Created news page was not loaded", $newsPageLoaded);

        //check that the created content, title and date are in the page
        $newsTitle = $this->byCss('h2.my-title');
        $this->assertEquals($createdNewsTitle, $newsTitle->text());
        $newsContent = $this->byCss('div#content-container p');
        $this->assertEquals($createdNewsContent, $newsContent->text());
        $creationDate = $this->byCss('div.subtitle');
        $this->assertEquals('Date: ' . $today, $creationDate->text());
    }
}
