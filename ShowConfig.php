<?php
require_once 'view/generalHeader.php';
require 'vendor/autoload.php';
require_once 'core/AppSettings.php';

// получаем настройки приложения
$AppSettings = AppSettings::getInstance();
$dbHost = $AppSettings->get('dbHost');
$dbName = $AppSettings->get('dbName');
$dbUser = $AppSettings->get('dbUser');
$dbPassword = $AppSettings->get('dbPassword');

if (!array_key_exists("from_up", $_GET)) {
    $dbCollectionName = $AppSettings->get('dbCollectionName');
} else {
    $dbCollectionName = $AppSettings->get('dbUpCollectionName');
}

$input_hash = $_GET["hash"];

try {
    $server = new MongoDB\Client(
        "mongodb://{$dbHost}",
        [
            "db" => $dbName,
            "username" => $dbUser,
            "password" => $dbPassword
        ]
    );
} catch (MongoDB\Driver\Exception\RuntimeException $ex) {
    echo $ex->getMessage();
}

$db = $server->$dbName;
$collection = $db->$dbCollectionName;
$fields = array('hash' => true, 'config' => true);
$query = array('hash' => $input_hash);
$cursor = $collection->find(
    $query,
    [
        'projection' => $fields,
        'limit' => 1
    ]
);

foreach ($cursor as $document) {
    echo "<pre>" . $document["config"] . "</pre>";;
}

require_once 'view/generalFooter.php';