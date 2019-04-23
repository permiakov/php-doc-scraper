<?php

namespace Permiakov\PHPDocScraper;

use Permiakov\PHPDocScraper\Func\Description;
use Permiakov\PHPDocScraper\Func\Name;
use Zend\I18n\Translator\Translator;

class Application
{
    /**
     * @var string
     */
    private $dataPath;
    /**
     * @var string
     */
    private $outputPath;
    /**
     * @var Translator
     */
    private $translator;

    const LIST_QUERY = "//ul[@class='chunklist chunklist_reference']/li/a/@href";
    const NAME_QUERY = "//span[@class='refname']";
    const TITLE_QUERY = "//span[@class='dc-title']";
    const SYNOPSIS_QUERY = "//div[@class='methodsynopsis dc-description']";

    /**
     * @param string $dataPath
     * @param string $outputPath
     * @param Translator $translator
     */
    public function __construct($dataPath, $outputPath, $translator)
    {
        $this->dataPath = $dataPath;
        $this->outputPath = $outputPath;
        $this->translator = $translator;
    }

    /**
     * @param string $sectionName
     */
    public function processSection($sectionName)
    {
        $sectionFilePath = sprintf("%s%s", $this->dataPath, "ref.{$sectionName}.html");
        $sectionDoc = new \DOMDocument();
        if (!$sectionDoc->loadHTMLFile($sectionFilePath)) {
            die("Section file wasn't loaded!");
        }
        $sectionXpath = new \DOMXPath($sectionDoc);
        $list = $sectionXpath->query(self::LIST_QUERY);
        foreach ($list as $item) {
            $this->processFunction($item->nodeValue);
        }
    }

    /**
     * @param $functionName
     * @return bool
     */
    public function processFunction($functionName)
    {
        if (strpos($functionName, '_') > 0) {
            $functionName = str_replace('_', '-', $functionName);
        }

        if (strpos($functionName, '.html') === false) {
            $functionName = sprintf('function.%s.html', $functionName);
        }
        echo $functionName . PHP_EOL;
        $functionFileName = sprintf("%s%s", DATA_PATH, $functionName);
        echo $functionFileName . PHP_EOL;

        $functionDoc = new \DOMDocument();
        $functionDoc->preserveWhiteSpace = true;
        $functionDoc->formatOutput = true;
        if (!@$functionDoc->loadHTMLFile($functionFileName)) {
            die("Function description file is absent!");
        }
        $functionXpath = new \DOMXPath($functionDoc);
        $functionName = $functionXpath->query(self::NAME_QUERY)->item(0)->nodeValue;
        $functionTitle = $functionXpath->query(self::TITLE_QUERY)->item(0)->nodeValue;
        $functionTitle = str_replace("\n", '', $functionTitle);
        $synopsis = $functionXpath->query(self::SYNOPSIS_QUERY);

        if (!$synopsis->item(0)) {
            return false;
        }

        $name = new Name($functionName, $functionTitle);
        $desc = new Description($this->getHtml($synopsis->item(0)));
        $text = sprintf('%s %s', $this->translator->translate('Function'), $name->asText());
        $link = sprintf(
            "<a href='https://www.php.net/manual/%s/function.%s.php'>%s</a>",
            $this->translator->getLocale(),
            $name->getNameForUrl(),
            $this->translator->translate('Details')
        );
        //important to wrap multiline lines into double quotes for ANKI
        //other double quotes better to replace with single ones
        $extra = sprintf("\"%s\"<br><br>%s", $desc->asHtml(), $link);
        $card = sprintf("%s\t%s\t\n", $text, $extra);
        file_put_contents($this->outputPath . 'output.csv', $card, FILE_APPEND);
        return true;
    }

    /**
     * @param \DOMNode $element
     * @return string
     */
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
