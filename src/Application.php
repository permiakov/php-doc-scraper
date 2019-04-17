<?php

namespace Permiakov\PHPDocScraper;

use Permiakov\PHPDocScraper\Func\Description;
use Permiakov\PHPDocScraper\Func\Name;

class Application
{
    /**
     * @var string
     */
    private $path;
    /**
     * @var \Zend\Mvc\I18n\Translator
     */
    private $translator;

    const LIST_QUERY = "//ul[@class='chunklist chunklist_reference']/li/a/@href";
    const NAME_QUERY = "//span[@class='refname']";
    const TITLE_QUERY = "//span[@class='dc-title']";
    const SYNOPSIS_QUERY = "//div[@class='methodsynopsis dc-description']";

    /**
     * Application constructor.
     * @param $path
     * @param $translator
     */
    public function __construct($path, $translator)
    {
        $this->path = $path;
        $this->translator = $translator;
    }

    /**
     * @param string $sectionFileName
     */
    public function execute($sectionFileName)
    {
        $sectionDoc = new \DOMDocument();
        @$sectionDoc->loadHTMLFile($sectionFileName);
        $sectionXpath = new \DOMXPath($sectionDoc);
        $list = $sectionXpath->query(self::LIST_QUERY);

        foreach ($list as $item) {
            $functionFileName = sprintf("%s%s", DATA_PATH, $item->nodeValue);
            $functionDoc = new \DOMDocument();
            $functionDoc->preserveWhiteSpace = true;
            $functionDoc->formatOutput = true;
            @$functionDoc->loadHTMLFile($functionFileName);
            $functionXpath = new \DOMXPath($functionDoc);
            $functionName = $functionXpath->query(self::NAME_QUERY)->item(0)->nodeValue;
            $functionTitle = $functionXpath->query(self::TITLE_QUERY)->item(0)->nodeValue;
            $synopsis = $functionXpath->query(self::SYNOPSIS_QUERY);

            if (!$synopsis->item(0)) {
                continue;
            }

            $html = $this->getHtml($synopsis->item(0));
            $name = new Name($functionName, $functionTitle);
            $desc = new Description($html);
            $text = sprintf('%s %s', $this->translator->translate('Function'), $name->asText());
            $link = sprintf("<a href='https://www.php.net/manual/%s/function.%s.php'>%s</a>",
                $this->translator->getLocale(),
                $functionName, $this->translator->translate('Details'));
            //important to wrap multiline lines into double quotes for ANKI
            //other double quotes better to replace with single ones
            $extra = sprintf("\"%s\"<br>%s", $desc->asHtml(), $link);
            $card = sprintf("%s\t%s\t\n", $text, $extra);
            file_put_contents($this->path . '/output.csv', $card, FILE_APPEND);
        }
    }

    protected function getHtml(\DOMNode $element)
    {
        $innerHTML = "";
        $children = $element->childNodes;
        foreach ($children as $child) {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }
        return $innerHTML;
    }
}