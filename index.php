<?php
require_once "vendor/autoload.php";
const DATA_PATH = "/Users/illia/Downloads/php-chunked-xhtml/";
use Zend\I18n\Translator\Translator;

$config = require_once("config/global.php");
//$sectionFileName = sprintf("%s%s", DATA_PATH, $argv[1]);
$sectionFileName = sprintf("%s%s", DATA_PATH, "ref.filesystem.html");
$translator = Translator::factory($config['translator']);
//path can be https://www.php.net/manual/ru/
$app = new \Permiakov\PHPDocScraper\Application(DATA_PATH, $translator);
$app->execute($sectionFileName);