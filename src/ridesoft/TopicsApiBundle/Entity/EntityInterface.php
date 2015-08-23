<?php

namespace ridesoft\TopicsApiBundle\Entity;


interface EntityInterface
{
    public function getAll();
    public function get($id);
    public function delete($id);
    public function insert();
}