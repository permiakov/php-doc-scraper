<?php

namespace Permiakov\PHPDocScraper\Func;

class Description
{
    /**
     * @var string
     */
    private $synopsisHtml;

    /**
     * @param $synopsisHtml
     */
    public function __construct($synopsisHtml)
    {
        $this->synopsisHtml = trim($synopsisHtml);
    }

    /**
     * @return string|string[]|null
     */
    public function asHtml()
    {
        //@todo replace <a href=""> contain with adding base URL https://www.php.net/manual/ru/
        //for links inside html like parameter types

        return preg_replace('/\"/', "'", $this->synopsisHtml);
    }
}
