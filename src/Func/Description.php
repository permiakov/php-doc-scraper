<?php

namespace Permiakov\PHPDocScraper\Func;

class Description
{
    /**
     * @var string
     */
    private $synopsisHtml;

    /**
     * Description constructor.
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
        return preg_replace('/\"/', "'", $this->synopsisHtml);
    }
}