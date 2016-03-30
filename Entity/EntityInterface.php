<?php

namespace ridesoft\TopicsApiBundle\Entity;

/**
 * Interface fot entities in the Api
 * @package ridesoft\TopicsApiBundle\Entity
 */
interface EntityInterface
{
    public function getAll();
    public function get($id);
    public function delete($id);
    public function insert();
}