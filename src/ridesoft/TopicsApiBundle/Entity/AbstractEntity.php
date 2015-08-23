<?php
/**
 * Created by PhpStorm.
 * User: mauri
 * Date: 8/23/15
 * Time: 11:10 AM
 */

namespace ridesoft\TopicsApiBundle\Entity;

use Doctrine\DBAL\Connection;
/**
 * AbstractEntity form manage the connection and the response for entities
 * @package ridesoft\TopicsApiBundle\Entity
 */
abstract class AbstractEntity
{
    protected $connection;

    public function __construct(Connection $connection){
        $this->connection = $connection;
    }

}