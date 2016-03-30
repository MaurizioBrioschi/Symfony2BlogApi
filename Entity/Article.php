<?php

namespace ridesoft\TopicsApiBundle\Entity;

use Doctrine\DBAL\DBALException;
use ridesoft\TopicsApiBundle\Entity\EntityInterface;
use ridesoft\TopicsApiBundle\Entity\AbstractEntity;
/**
 * Article
 */
class Article extends AbstractEntity implements EntityInterface
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
     * @var string
     */
    private $author;

    /**
     * @var string
     */
    private $text;
    /**
     * @var integer
     */
    private $topic_id;

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
     * @return Article
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
     * Set author
     *
     * @param string $author
     * @return Article
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Article
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set Topic Id
     * @param $topic_id
     * @return $this
     */
    public function setTopicId($topic_id)
    {
        $this->topic_id = $topic_id;

        return $this;
    }

    /**
     * Get Topic id
     * @return int
     */
    public function getTopicId(){
        return $this->topic_id;
    }

    /**
     * Get all articles
     * @return array
     * @throws DBALException
     * @throws \Exception
     */
    public function getAll()
    {
        try{
            $query = "SELECT * from articles";
            $statement = $this->connection->prepare($query);
            $statement->execute();

            return $statement->fetchAll();
        }catch (DBALException $e){
            throw $e;
        }


    }

    /**
     * Get the articles with that id
     * @param $id
     * @return array
     * @throws DBALException
     * @throws \Exception
     */
    public function get($id)
    {
        try{
            $query = "SELECT * from articles WHERE id=:id";

            $statement = $this->connection->prepare($query);
            $statement->bindValue("id",$id);
            $statement->execute();

            return $statement->fetchAll();
        }catch (DBALException $e){
            throw $e;
        }

    }

    /**
     * delete the article with $id
     * @param $id
     * @return bool
     * @throws DBALException
     * @throws \Exception
     */
    public function delete($id)
    {
        try{
            $this->connection->beginTransaction();
            $query = "DELETE from topics_article WHERE article_id=:id";
            $statement = $this->connection->prepare($query);
            $statement->bindValue("id",$id);
            $statement->execute();

            $query = "DELETE from articles WHERE id=:id";
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
     * insert the article
     * @return $this
     * @throws DBALException
     * @throws \Exception
     */
    public function insert()
    {
        try{
            $this->connection->beginTransaction();

            $query = "INSERT INTO articles(title,author,text) VALUES(:title,:author,:text)";

            $statement = $this->connection->prepare($query);
            $statement->bindValue("title",$this->getTitle());
            $statement->bindValue("author",$this->getAuthor());
            $statement->bindValue("text",$this->getText());
            $statement->execute();
            $this->id = $this->connection->lastInsertId();

            if(isset($this->topic_id)) {
                $query = "INSERT INTO topics_article VALUES(:topic_id,:article_id)";
                $statement = $this->connection->prepare($query);
                $statement->bindValue("topic_id", $this->getTopicId());
                $statement->bindValue("article_id", $this->getId());
                $statement->execute();
            }
            $this->connection->commit();
            return $this;
        }catch (DBALException $e)   {
            $this->connection->rollBack();
            throw $e;
        }

    }
}
