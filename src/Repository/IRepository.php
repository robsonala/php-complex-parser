<?php
namespace PHPComplexParser\Repository;

use PHPComplexParser\Entity\BaseEntity;

interface IRepository
{
    public function count() : int;
    public function getAll() : array;
    public function find($searchTerm) : ?BaseEntity;
    public function add(BaseEntity $item) : bool;
    public function exists($searchTerm) : bool;
    public function remove($searchTerm) : bool;
}