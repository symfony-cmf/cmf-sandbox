<?php

namespace Sandbox;

class NewsTest extends WebTestCase
{
    public function testAddNews()
    {
        $client = $this->createClient();

        $title = 'news title from testAddNews';
        $content = 'some new content from testAddNews';
        $request = $this->generateCreateArticleRequest($title, $content);

        $client->request('POST', '/en/symfony-cmf/create/document/_:bnode89', $request);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //TODO: simulate here the route creation

        //get the created page and check if everything is contained in the page
        $crawler = $client->request('GET', '(/en/news/news-title-from-testAddNews');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertCount(1, $crawler->filter(sprintf('h2:contains("%s")', $title)));
        $this->assertCount(1, $crawler->filter(sprintf('p:contains("%s")', $content)));
        $this->assertCount(1, $crawler->filter(sprintf('div.subtitle:contains("%s")', 'Date: ' . date('Y-m-d'))));

        //try to add a news with the same title, a collision on the node name should happen
        $client->request('POST', '/en/symfony-cmf/create/document/_:bnode89', $request);
        $this->assertEquals(500, $client->getResponse()->getStatusCode());
        $this->assertEquals('The document could not be created', $client->getResponse()->getContent());
    }

    public function testUpdateNews()
    {
        self::$fixturesLoaded = false; // we only load fixtures once, but after this write test we want to refresh them
        $client = $this->createClient();

        //prepare the PUT request
        $titleKey = '<http://schema.org/CreativeWork/headline>';
        $title = 'updated title from testUpdateNews';

        $contentKey = '<http://schema.org/Article/articleBody>';
        $content = 'some updated content from testUpdateNews';

        $subjectKey = '@subject';
        $subject = '</cms/content/news/news-on-the-sandbox>';

        $typeKey = '@type';
        $type = '<<http://schema.org/NewsArticle>';

        $crawler = $client->request('PUT', '/en/symfony-cmf/create/document/cms/content/news/news-on-the-sandbox',
            array(
                $titleKey => $title,
                $contentKey => $content,
                $subjectKey => $subject,
                $typeKey => $type
            )
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //get the updated page and check if data has been updated
        $crawler = $client->request('GET', '/en/news/news-on-the-sandbox');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertCount(1, $crawler->filter(sprintf('h2:contains("%s")', $title)));
        $this->assertCount(1, $crawler->filter(sprintf('p:contains("%s")', $content)));
    }

    private function generateCreateArticleRequest($title, $content)
    {
        //prepare the POST request
        $partOfKey = '<http://purl.org/dc/terms/partOf>';
        $partOf = '</cms/content/news>';

        $titleKey = '<http://schema.org/CreativeWork/headline>';
        $titleValue = $title;

        $contentKey = '<http://schema.org/Article/articleBody>';
        $contentValue = $content;

        $subjectKey = '@subject';
        $subject = '_:bnode89';

        $typeKey = '@type';
        $type = '<http://schema.org/NewsArticle>';

        $request = array(
            $partOfKey => array($partOf),
            $titleKey => $titleValue,
            $contentKey => $contentValue,
            $subjectKey => $subject,
            $typeKey => $type
        );

        return $request;
    }
}
