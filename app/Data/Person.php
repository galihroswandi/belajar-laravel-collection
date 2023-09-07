<?php

namespace App\Data;


class Person
{

    var string $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}