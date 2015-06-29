<?php require_once 'view/generalHeader.php'; ?>

<link rel="stylesheet" type="text/css" href="css/diff.css">

<?php
require_once 'core/modules/FineDiff.php';
require_once 'core/AppSettings.php';

// получаем настройки приложения
$AppSettings = AppSettings::getInstance();
$dbHost           = $AppSettings->get('dbHost');
$dbName           = $AppSettings->get('dbName');
$dbUser		  = $AppSettings->get('dbUser');
$dbPassword	  = $AppSettings->get('dbPassword');
if(!array_key_exists("from_up", $_POST)) {
    $dbCollectionName = $AppSettings->get('dbCollectionName');
} else {
    $dbCollectionName = $AppSettings->get('dbUpCollectionName');
}

if (empty($_POST["hash"])) {
    echo "Скрипту не передано никаких параметров!";
    exit();
} else {
    if (count($_POST["hash"]) > 2) {
        echo "Передано неверное количество параметров!";
        exit();
    } else {
        $hash1 = $_POST["hash"][1];
        $hash2 = $_POST["hash"][0];
    }
}

$server = new MongoClient("mongodb://{$dbHost}", array("db" => $dbName, "username" => $dbUser, "password" => $dbPassword));
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

require_once 'view/generalFooter.php';