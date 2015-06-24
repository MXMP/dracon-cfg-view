<?php
require_once 'view/generalHeader.php';
require_once 'core/AppSettings.php';

// получаем настройки приложения
$AppSettings = AppSettings::getInstance();
$dbHost           = $AppSettings->get('dbHost');
$dbName           = $AppSettings->get('dbName');
$dbUser		  = $AppSettings->get('dbUser');
$dbPassword	  = $AppSettings->get('dbPassword');

$dbCollectionName = $AppSettings->get('dbCollectionName');

$input_hash = $_GET["hash"];

try {
    $server = new MongoClient("mongodb://{$dbHost}", array("db" => $dbName, "username" => $dbUser, "password" => $dbPassword));
} catch (MongoConnectionException $ex) {
    echo $ex->getMessage();
}

$db = $server->$dbName;
$collection = $db->$dbCollectionName;
$fields = array('hash' => true, 'config' => true);
$query = array('hash' => $input_hash);
$cursor = $collection->find($query, $fields)->limit(1);

foreach ($cursor as $document) {
    echo "<pre>".$document["config"]->bin."</pre>";;
}

require_once 'view/generalFooter.php';