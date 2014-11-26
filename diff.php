<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/diff.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
	<title>
	    Diff-Config Test
	</title>
    </head>
    <body>
        <div class="container">
	<?php
	    include 'finediff.php';
            
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
            
            $dbhost = '10.200.201.180';
            $dbname = 'mcvt';
            $collection_name = "mcvt_down";

            $server = new MongoClient("mongodb://{$dbhost}");
            $db = $server->$dbname;
            $collection = $db->$collection_name;
            $fields = array('hash' => true, 'config' => true);
            $query = array('hash' => $hash1);
            $cursor = $collection->find($query, $fields)->limit(1);

            foreach ($cursor as $document) {
                //echo "<pre>".$document["config"]->bin."</pre>";
                $from_text = $document["config"]->bin;
            } 
            
            //echo "<h1>----------------------------------------------------------------------------------</h1>";
            
            $query = array('hash' => $hash2);
            $cursor = $collection->find($query, $fields)->limit(1);

            foreach ($cursor as $document) {
                //echo "<pre>".$document["config"]->bin."</pre>";
                $to_text = $document["config"]->bin;
            } 

            //echo "<h1>+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++</h1>";
            
            /*
            $from_text = file_get_contents('diff_from.txt');
	    $to_text = file_get_contents('diff_to.txt');
            */
            
	    $opcodes = FineDiff::getDiffOpcodes($from_text, $to_text, FineDiff::$paragraphGranularity);
	    $to_text = FineDiff::renderDiffToHTMLFromOpcodes($from_text, $opcodes);

	    echo "<pre>";
	    echo $to_text;
	    echo "</pre>";
        ?>
        </div>
    </body>
</html>
