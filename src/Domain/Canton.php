<?php

namespace App\Domain;

class Canton
{
    const WEIGHT_TYPE_COUNT = 'count';
    const WEIGHT_TYPE_POPULATION = 'population';

    public $id;
    public $canton;
    public $text;
    public $language;
    public $weight;

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->canton, $this->text);
    }
}
