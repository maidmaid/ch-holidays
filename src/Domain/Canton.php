<?php

namespace App\Domain;

class Canton
{
    public $id;
    public $canton;
    public $text;
    public $language;

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->canton, $this->text);
    }
}
