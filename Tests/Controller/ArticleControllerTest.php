<?php

namespace ridesoft\TopicsApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleControllerTest extends WebTestCase
{
    protected $client;

    public function __construct(){
        $this->client   = static::createClient();
    }

    public function testPostArticle(){
        $crawler  = $this->client->request('POST', '/api/articles.json',array(
            "title"=>"Unit test",
            "author"=>"Maurizio Brioschi",
            "text" => "Rock and roll"
        ));
        $response = $this->client->getResponse();
        $this->assertEquals(201,$response->getStatusCode());
    }

    public function testGetAll()
    {
        $crawler  = $this->client->request('GET', '/api/articles.json');
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded = json_decode($response->getContent(),true);
        $this->assertArrayHasKey("articles",$decoded);
        foreach($decoded["articles"] as $article){
            $this->assertArrayHasKey("id",$article);
            $this->assertArrayHasKey("title",$article);
            $this->assertArrayHasKey("author",$article);
            $this->assertArrayHasKey("text",$article);
        }

        return $decoded["articles"][count($decoded["articles"])-1];

    }

    /**
     * @depends testGetAll
     */
    public function testGetId($article) {

        $crawler  = $this->client->request('GET', '/api/articles/'.$article["id"].'.json');
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded = json_decode($response->getContent(),true);
        $this->assertEquals(1,count($decoded));
        $this->assertArrayHasKey("article",$decoded);
        $this->assertArrayHasKey("id",$decoded["article"][0]);
        $this->assertArrayHasKey("title",$decoded["article"][0]);
        $this->assertArrayHasKey("author",$decoded["article"][0]);
        $this->assertArrayHasKey("text",$decoded["article"][0]);

    }
    /**
     * @depends testGetAll
     */
    public function testDeleteArticle($article) {
        $crawler  = $this->client->request('DELETE', '/api/articles/'.$article["id"].'.json');
        $response = $this->client->getResponse();

        $this->assertEquals(200,$response->getStatusCode());
    }
    protected function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }
}
