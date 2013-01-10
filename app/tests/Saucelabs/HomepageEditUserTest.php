<?php

namespace Sandbox\Saucelabs;

/**
 * Use Saucelabs to test the edition of content with Create.js
 */
class HomepageEditUserTest extends SaucelabsWebTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->setBrowserUrl($this->homeUrl);
    }

    public function testEditHomepageContent()
    {
        //common variables
        $originalTitle = 'Homepage';
        $toAddToTitle = 'Updated title for ';
        $updatedTitle =  $toAddToTitle . $originalTitle;
        $titleCss = 'div.inner h1:first-child';

        //page loaded correctly?
        $this->assertContains($originalTitle, $this->title());

        //enter the edit mode
        $editLink = $this->byId('midgardcreate-edit');
        $editLink->click();

        //cancel should now be in the button text
        $cancelLink = $this->byId('midgardcreate-edit');
        $this->assertContains("Cancel", $cancelLink->text());

        //update the page title
        $titleToEdit = $this->byCss($titleCss);
        $titleToEdit->click();
        $titleToEdit->value($toAddToTitle);

        //save the changes
        $this->byId('midgardcreate-save')->click();

        //check the result
        $this->assertContains($updatedTitle, $this->byCss($titleCss)->text());

        //leave the edit mode
        $this->byId('midgardcreate-edit')->click();
        $editLink = $this->byId('midgardcreate-edit');
        $this->assertContains("Edit", $editLink->text());

        //reload the page to ensure the changes have been persisted
        $this->url('/en');
        $driver = $this;
        $pageLoaded = function() use ($driver, $updatedTitle) {
            //give some time to load the page
            return ($driver->title() == $updatedTitle);
        };
        $this->spinAssert("Homepage was not loaded", $pageLoaded);

        //updated title needs to be present in the page title and page content
        $this->assertContains($updatedTitle, $this->title());
        $this->assertContains($updatedTitle, $this->byCss($titleCss)->text());
    }
}
