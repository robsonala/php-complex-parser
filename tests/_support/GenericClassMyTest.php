<?php

class GenericClassMyTest
{
    public $LoremIpsum;
    public $DolorSit;

    public function __construct()
    {
        $this->LoremIpsum = uniqid();
        $this->DolorSit = uniqid();
    }
}