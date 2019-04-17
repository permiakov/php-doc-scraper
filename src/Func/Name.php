<?php

namespace Permiakov\PHPDocScraper\Func;

class Name
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $title;

    public function __construct($name, $title)
    {
        $this->name = $name;
        $this->title = $title;
    }

    public function asText()
    {
        return sprintf("{{c1::%s}} - %s", $this->name, $this->title);
    }
}