<?php
namespace PHPComplexParser\Entity;

interface IEntity
{
    public function validate();
    public function getJson(bool $runValidate);
    public function setJson(string $json, bool $runValidate);
}