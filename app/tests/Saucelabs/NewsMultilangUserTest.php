<?php

namespace Sandbox\Saucelabs;

/**
 * Use Saucelabs to test the multi language in the news system created with Create.js
 *
 * ATTENTION: These tests might be failing due to a raise condition in doctrine.
 * When the 3 routes are created with parallel requests, an invalid and unnecessary
 * content document is created for the locale of the route being created.
 * TODO: see if fixed by https://github.com/doctrine/phpcr-odm/pull/238
 */
class NewsMultilangUserTest extends SaucelabsWebTestCase
{
    protected $newsUrl = 'http://cmf.lo/app_test.php/en/news';
    protected $newsUrlFr = 'http://cmf.lo/app_test.php/fr/news';
    protected $newsUrlDe = 'http://cmf.lo/app_test.php/de/news';

    public function setUp()
    {
        parent::setUp();
        $this->setBrowserUrl($this->newsUrlFr);
    }

    public function testUpdateTranslatedContent()
    {
        //common variables
        $originalNewsPageTitle = 'News';
        $newsFrTitle = 'Nouvelles pour la Sandbox';
        $newsEnTitle = 'News on the Sandbox';
        $newsFrTitleUpdate = 'Mise à jour: ';
        $newsFrTitleUpdated = $newsFrTitleUpdate . $newsFrTitle;

        $driver = $this;
        $newsPageLoaded = function() use ($driver, $originalNewsPageTitle) {
            //give some time to load the page
            return ($driver->title() == $originalNewsPageTitle);
        };
        $this->spinAssert("News page in FR was not loaded", $newsPageLoaded);

        //click on edit
        $this->enterEditMode();

        $newsTitle = $this->byCss('.newsoverview li:first-child a');
        $newsTitle->value($newsFrTitleUpdate);

        //click on save
        $this->saveChanges();

        //click on cancel
        $this->leaveEditMode();

        //reload the current page to ensure the changes have been persisted
        $this->url($this->newsUrlFr);
        $driver = $this;
        $newsPageLoaded = function() use ($driver, $originalNewsPageTitle) {
            //give some time to load the page
            return ($driver->title() == $originalNewsPageTitle);
        };
        $this->spinAssert("News page was not loaded", $newsPageLoaded);

        $newsTitle = $this->byCss('.newsoverview li:first-child a');
        $this->assertEquals($newsFrTitleUpdated, $newsTitle->text());

        //load the EN news page
        $this->url($this->newsUrl);
        $newsPageLoaded = function() use ($driver, $originalNewsPageTitle) {
            //give some time to load the page
            return ($driver->title() == $originalNewsPageTitle);
        };
        $this->spinAssert("News page was not loaded", $newsPageLoaded);

        //the first news should not have changed
        $newsTitle = $this->byCss('.newsoverview li:first-child a');
        $this->assertNotContains($newsFrTitleUpdated, $newsTitle->text());
        $this->assertContains($newsEnTitle, $newsTitle->text());

        //load the DE news page
        $this->url($this->newsUrlDe);
        $newsPageLoaded = function() use ($driver, $originalNewsPageTitle) {
            //give some time to load the page
            return ($driver->title() == $originalNewsPageTitle);
        };
        $this->spinAssert("News page was not loaded", $newsPageLoaded);

        //click edit
        $this->enterEditMode();

        //the first news should be in german
        $newsLocale = $this->byCss('.newsoverview li:first-child .newslocale span');
        $this->assertEquals('de', $newsLocale->text());

        //the second news should be in english
        $newsLocale = $this->byCss('.newsoverview li:nth-child(2) .newslocale span');
        $this->assertEquals('en', $newsLocale->text());
    }

    public function testNewsCreationFrToDe()
    {
        //common variables
        $originalNewsPageTitle = 'News';
        $newsCreationTitle = 'From fr to de';
        $newsCreationContent = 'Content written inside the FR page but in the DE language.';

        //click on edit
        $this->enterEditMode();

        //click the add button
        $this->clickAddButton();

        //write the news title and content
        $newsTitle = $this->byXPath('//a[contains(text(), "[cw:headline]")]');
        $newsTitle->value($newsCreationTitle);
        $newsContent = $this->byXPath('//div[contains(text(), "[ar:articleBody]")]');
        $newsContent->value($newsCreationContent);
        $newsContent = $this->byXPath('//span[contains(text(), "[loc:name]")]');
        $newsContent->value('de');

        //click on save
        $this->saveChanges();

        //load the DE news page
        $this->url($this->newsUrlDe);
        $driver = $this;
        $newsPageLoaded = function() use ($driver, $originalNewsPageTitle) {
            //give some time to load the page
            return ($driver->title() == $originalNewsPageTitle);
        };
        $this->spinAssert("News page was not loaded", $newsPageLoaded);

        //the last news should be in de
        $newsLocale = $this->byCss('.newsoverview li:last-child .newslocale span');
        $this->assertEquals('de', $newsLocale->text());

        //the news content and title should be as written
        $newsTitle = $this->byCss('.newsoverview li:last-child a');
        $this->assertContains($newsCreationTitle, $newsTitle->text());
        $allNews = $this->byCss('div.newsoverview');
        $this->assertContains($newsCreationContent, $allNews->text());
    }
}
