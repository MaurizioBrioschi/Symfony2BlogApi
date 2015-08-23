<?php

namespace ridesoft\TopicsApiBundle\Entity;

use ridesoft\TopicsApiBundle\Entity\TopicInterface;
use ridesoft\TopicsApiBundle\Entity\AbstractEntity;


/**
 * Topic
 */
class Topic extends AbstractEntity implements EntityInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var array
     */
    private $articles = array();


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Topic
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set articles
     *
     * @param array $articles
     * @return Topic
     */
    public function setArticles($articles)
    {
        $this->articles = $articles;

        return $this;
    }

    /**
     * Get articles
     *
     * @return array 
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Get all  topics
     * @return array
     * @throws DBALException
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public function getAll()
    {
        try{
            $query = "SELECT * FROM topics";

            $statement = $this->connection->prepare($query);
            $statement->execute();
            return $statement->fetchAll();

        }catch (DBALException $e)   {
            throw $e;
        }

    }

    /**
     * get the topic with $id
     * @param $id
     * @return array
     * @throws DBALException
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public function get($id)
    {
        try{
            $query = "SELECT * from topics WHERE id=:id";

            $statement = $this->connection->prepare($query);
            $statement->bindValue("id",$id);
            $statement->execute();

            return $statement->fetchAll();
        }catch (DBALException $e){
            throw $e;
        }
    }

    /**
     * Get the topic with $id and its related articles
     * @param $id
     * @return array
     * @throws DBALException
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public function getTopicWithArticles($id)   {
        try {
            $query = "SELECT t.id as TopicId, t.title as TopicTitle, a.* FROM topics t "
                ."JOIN topics_article ta ON t.id=ta.topic_id "
                ."JOIN articles a ON ta.article_id=a.id "
                ."WHERE t.id=:id";

            $statement = $this->connection->prepare($query);
            $statement->bindValue("id",$id);
            $statement->execute();
            $result =$statement->fetchAll();
            $response = [];
            $response["articles"] = [];
            foreach($result as $row){
                $response["id"] = $row["TopicId"];
                $response["title"] = $row["TopicTitle"];
                array_push($response["articles"],[
                    "id" => $row["id"],
                    "title" => $row["title"],
                    "author" => $row["author"],
                    "text" => $row["text"],
                ]);
            }
            return $response;

        }catch (DBALException $e)   {
            throw $e;
        }
    }

    /**
     * Delete  the topic with $id
     * @param $id
     * @return bool
     * @throws DBALException
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public function delete($id)
    {
        try{
            $this->connection->beginTransaction();
            $query = "DELETE FROM topics_article WHERE topic_id=:id";
            $statement = $this->connection->prepare($query);
            $statement->bindValue("id",$id);
            $statement->execute();

            $query = "DELETE from topics WHERE id=:id";
            $statement = $this->connection->prepare($query);
            $statement->bindValue("id",$id);
            $statement->execute();

            $this->connection->commit();
            return true;
        }catch (DBALException $e)   {
            $this->connection->rollBack();
            throw $e;
        }
    }

    /**
     * Insert the current  topic
     * @return $this
     * @throws DBALException
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public function insert()
    {
        try{
            $query = "INSERT INTO topics(title) VALUES(:title)";

            $statement = $this->connection->prepare($query);
            $statement->bindValue("title",$this->getTitle());
            $statement->execute();
            $this->id = $this->connection->lastInsertId();

            /* is just an idea... not implemented yet
             * if(count($this->articles)>0) {
                $queryJoin = "INSERT INTO topics_articles(topic_id,article_id) VALUES (:topic_id,:article_id);";
                $statement->bindValue("topic_id", $this->getId());
                foreach ($this->articles as $article) {
                    $articleId = $article->insert();
                    $statement->bindValue("article_id", $articleId->getId());
                    $statement->execute();
                }
            }*/
            return $this;
        }catch (DBALException $e)   {
            throw $e;
        }
    }
}
