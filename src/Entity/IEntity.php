<?php
namespace PHPComplexParser\Entity;

interface IEntity
{
    public function validate();
    public function getJson($_null);
    public function setJson(string $json);
}