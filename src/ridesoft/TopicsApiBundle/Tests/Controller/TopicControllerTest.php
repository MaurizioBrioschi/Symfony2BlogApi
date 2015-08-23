<?php

namespace ridesoft\TopicsApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TopicControllerTest extends WebTestCase
{
    protected $client;

    public function __construct(){
        $this->client   = static::createClient();
    }

    public function testPostTopic(){
        $crawler  = $this->client->request('POST', '/api/topics.json',array("title"=>"Unit test Topic"));
        $response = $this->client->getResponse();
        $this->assertEquals(201,$response->getStatusCode());
    }

    public function testGetAll()
    {
        $crawler  = $this->client->request('GET', '/api/topics.json');
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded = json_decode($response->getContent(),true);
        $this->assertArrayHasKey("topics",$decoded);
        $last_topic = null;
        foreach($decoded["topics"] as $topic){
            $this->assertArrayHasKey("id",$topic);
            $this->assertArrayHasKey("title",$topic);
            $last_topic = $topic;
        }

        return $last_topic["id"];

    }



    /**
     * @depends testGetAll
     */
    public function testPostArticleWithTopic($topic_id){
        $crawler  = $this->client->request('POST', '/api/articles.json',array(
            "title"=>"Unit test con Topic",
            "author"=>"Maurizio Brioschi",
            "text" => "Rock and roll",
            "topic_id" => $topic_id
        ));
        $response = $this->client->getResponse();
        $this->assertEquals(201,$response->getStatusCode());

        return $topic_id;
    }

    /**
     * @depends testPostArticleWithTopic
     */
    public function testGetTopicWithArticles($topic_id)  {

        $crawler  = $this->client->request('GET', '/api/topics/'.$topic_id.'/articles.json');
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded = json_decode($response->getContent(),true);

        $this->assertEquals(1,count($decoded));
        $this->assertArrayHasKey("topic",$decoded);
        $this->assertArrayHasKey("id",$decoded["topic"]);
        $this->assertArrayHasKey("title",$decoded["topic"]);
        $this->assertArrayHasKey("articles",$decoded["topic"]);
    }
    /**
     * @depends testGetAll
     */
    public function testGetId($topic_id) {

        $crawler  = $this->client->request('GET', '/api/topics/'.$topic_id.'.json');
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded = json_decode($response->getContent(),true);
        $this->assertEquals(1,count($decoded));
        $this->assertArrayHasKey("topic",$decoded);
        $this->assertArrayHasKey("id",$decoded["topic"][0]);

    }
    /**
     * @depends testGetAll
     */
    public function testDeleteTopic($topic_id) {
        $crawler  = $this->client->request('DELETE', '/api/topics/'.$topic_id.'.json');
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
