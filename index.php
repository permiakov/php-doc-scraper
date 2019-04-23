<?php
require_once "vendor/autoload.php";
//must contain unpacked php.net docs in html files format
const DATA_PATH = "";
//where to output csv file
const OUTPUT_PATH = "";
use Zend\I18n\Translator\Translator;

if (empty(DATA_PATH) || empty(OUTPUT_PATH)) {
    die("Please set DATA_PATH and OUTPUT_PATH constants");
}
$config = include_once "config/global.php";
if (!isset($argv[1])) {
    die("Section or function name argument was not passed!");
}
$translator = Translator::factory($config['translator']);
$app = new \Permiakov\PHPDocScraper\Application(DATA_PATH, OUTPUT_PATH, $translator);
$command = explode('=', $argv[1]);
if ($command[0] === "s") {
    $app->processSection($command[1]);
} else {
    $app->processFunction($command[1]);
}
