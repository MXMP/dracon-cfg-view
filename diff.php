<?php require_once 'header.php'; ?>

<link rel="stylesheet" type="text/css" href="css/diff.css">

<?php
require_once 'finediff.php';
require_once 'AppSettings.php';

// получаем настройки приложения
$AppSettings = AppSettings::getInstance();
$dbHost           = $AppSettings->get('dbHost');
$dbName           = $AppSettings->get('dbName');
$dbCollectionName = $AppSettings->get('dbCollectionName');

if (empty($_POST["hash"])) {
    echo "Скрипту не передано никаких параметров!";
    exit();
} else {
    if (count($_POST["hash"]) > 2) {
        echo "Передано неверное количество параметров!";
        exit();
    } else {
        $hash1 = $_POST["hash"][0];
        $hash2 = $_POST["hash"][1];
    }
}

$server = new MongoClient("mongodb://{$dbHost}");
$db = $server->$dbName;
$collection = $db->$dbCollectionName;
$fields = array('hash' => true, 'config' => true);
$query = array('hash' => $hash1);
$cursor = $collection->find($query, $fields)->limit(1);

foreach ($cursor as $document) {
    $from_text = $document["config"]->bin;
} 

$query = array('hash' => $hash2);
$cursor = $collection->find($query, $fields)->limit(1);

foreach ($cursor as $document) {
    $to_text = $document["config"]->bin;
} 

$opcodes = FineDiff::getDiffOpcodes($from_text, $to_text, FineDiff::$paragraphGranularity);
$to_text = FineDiff::renderDiffToHTMLFromOpcodes($from_text, $opcodes);

echo "<pre>";
echo $to_text;
echo "</pre>";

require_once 'footer.php';