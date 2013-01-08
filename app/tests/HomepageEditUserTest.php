<?php

namespace Sandbox;

use Sauce\Sausage\WebDriverTestCase;

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
        $titleXPath = '/html/body/div/div[3]/div/div/div[2]/div/div/div/h1';

        //page loaded correctly?
        $this->assertContains($originalTitle, $this->title());

        //enter the edit mode
        $editLink = $this->byId('midgardcreate-edit');
        $editLink->click();

        //cancel should now be in the button content
        $cancelLink = $this->byId('midgardcreate-edit');
        $this->assertContains("Cancel", $cancelLink->text());

        //update the page title
        $titleToEdit = $this->byXPath($titleXPath);
        $titleToEdit->click();
        $titleToEdit->value($toAddToTitle);

        //save the changes
        $this->byId('midgardcreate-save')->click();

        //check the result
        $this->assertContains($toAddToTitle . $originalTitle,
            $this->byXPath($titleXPath)->text());

        //leave the edit mode
        $this->byId('midgardcreate-edit')->click();
        $editLink = $this->byId('midgardcreate-edit');
        $this->assertContains("Edit", $editLink->text());

        //reload the page to ensure changes have been persisted
        $this->setBrowserUrl($this->homeUrl);

        //updated title needs to be present in the page title and page content
        $this->assertContains($toAddToTitle . $originalTitle, $this->title());
        $this->assertContains($toAddToTitle . $originalTitle,
            $this->byXPath($titleXPath)->text());
    }
}
