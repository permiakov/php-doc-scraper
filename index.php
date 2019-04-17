<?php
require_once "vendor/autoload.php";
//must contain unpacked php.net docs in html files format
const DATA_PATH = "data/";
use Zend\I18n\Translator\Translator;

$config = include_once "config/global.php";
if (!isset($argv[1])) {
    die("Section name argument was not passed!");
}
//may be download an archive and unzip to data folder?
// we need to know current locale or pass it via second argument
$sectionFileName = sprintf("%s%s", DATA_PATH, "ref.$argv[1].html");
$translator = Translator::factory($config['translator']);
$app = new \Permiakov\PHPDocScraper\Application(DATA_PATH, $translator);
$app->execute($sectionFileName);