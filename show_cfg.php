<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<div class="container">
<?php
$input_hash = $_GET["hash"];
$dbhost = '10.200.201.180';
$dbname = 'mcvt';
$collection_name = "mcvt_down";

$server = new MongoClient("mongodb://{$dbhost}");
$db = $server->$dbname;
$collection = $db->$collection_name;
$fields = array('hash' => true, 'config' => true);
$query = array('hash' => $input_hash);
$cursor = $collection->find($query, $fields)->limit(1);

foreach ($cursor as $document) {
    echo "<pre>".$document["config"]->bin."</pre>";;
} 
?>
</div>