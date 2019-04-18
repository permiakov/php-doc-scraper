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

    /**
     * @param string $name
     * @param string $title
     */
    public function __construct($name, $title)
    {
        $this->name = $name;
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function asText()
    {
        return sprintf("{{c1::%s}} - %s", $this->name, $this->title);
    }

    /**
     * @return mixed
     */
    public function getNameForUrl()
    {
        return str_replace('_', '-', $this->name);
    }
}
