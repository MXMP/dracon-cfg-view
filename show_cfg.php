<link rel="stylesheet" href="css/bootstrap.min.css">
<div class="container">
<?php
require_once 'AppSettings.php';

// получаем настройки приложения
$AppSettings = AppSettings::getInstance();
$dbHost           = $AppSettings->get('dbHost');
$dbName           = $AppSettings->get('dbName');
$dbCollectionName = $AppSettings->get('dbCollectionName');

$input_hash = $_GET["hash"];

$server = new MongoClient("mongodb://{$dbHost}");
$db = $server->$dbName;
$collection = $db->$dbCollectionName;
$fields = array('hash' => true, 'config' => true);
$query = array('hash' => $input_hash);
$cursor = $collection->find($query, $fields)->limit(1);

foreach ($cursor as $document) {
    echo "<pre>".$document["config"]->bin."</pre>";;
} 
?>
</div>