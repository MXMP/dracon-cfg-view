<?php
require_once 'view/generalHeader.php';
require 'vendor/autoload.php';
?>

    <link rel="stylesheet" type="text/css" href="/css/diff.css">

<?php
require_once 'core/AppSettings.php';

// получаем настройки приложения
$AppSettings = AppSettings::getInstance();
$dbHost = $AppSettings->get('dbHost');
$dbName = $AppSettings->get('dbName');
$dbUser = $AppSettings->get('dbUser');
$dbPassword = $AppSettings->get('dbPassword');
if (!array_key_exists("from_up", $_POST)) {
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

$client = new MongoDB\Client(
    "mongodb://{$dbHost}",
    [
        "db" => $dbName,
        "username" => $dbUser,
        "password" => $dbPassword
    ]
);
$db = $client->$dbName;
$collection = $db->$dbCollectionName;
$fields = array('hash' => true, 'config' => true);
$query = array('hash' => $hash1);
$cursor = $collection->find(
    $query,
    [
        'projection' => $fields,
        'limit' => 1
    ]
);

foreach ($cursor as $document) {
    $from_text = explode("\n", $document["config"]);
}

$query = array('hash' => $hash2);
$cursor = $collection->find(
    $query,
    [
        'projection' => $fields,
        'limit' => 1
    ]
);

foreach ($cursor as $document) {
    $to_text = explode("\n", $document["config"]);
}

// Options for generating the diff
$options = array(
    //'ignoreWhitespace' => true,
    //'ignoreCase' => true,
);

// Initialize the diff class
$diff = new Diff($from_text, $to_text, $options);

$renderer = new Diff_Renderer_Html_Inline;

echo $diff->render($renderer);

require_once 'view/generalFooter.php';