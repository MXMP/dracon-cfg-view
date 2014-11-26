<?php
require_once 'search.php';
require_once 'errorManager.php';
require_once 'validationManager.php';

// Объявления вспомогательных переменных
$dbHost = "10.200.201.180";
$dbName = "mcvt";
$collectionName = "mcvt_down";
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>SwitchConfig - Результаты поиска</title>
        <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <script type="text/javascript" src="only_two2.js"></script>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <?php                                 
            // Валидация данных
            $validator = new validationManager();
            $validator->validate();
            
            // Проверка на наличие ошибок
            $error_flag = $validator->getErrorFlag();
            if ($error_flag != "") {
                $errorManager = new ErrorManager($error_flag);
                $errorManager->getErrorMessage();
                exit(); //остановка выполнения
            }
            
            // Попытка выполнения ранее подготовленного запроса
            $flag = $validator->getSearchFlag();
            if ($flag == "dateRange") {                
                $new_search = new Search($dbHost, $dbName, $collectionName, $flag, $validator->getSearchStr(), $validator->getSearchStr2());
            } else {
                $new_search = new Search($dbHost, $dbName, $collectionName, $flag, $validator->getSearchStr());
            }
            $new_search->getResultsTable("diff.php");
            
            // Подсчет посетителей
            $visitor_ip = $_SERVER['REMOTE_ADDR'];
            file_put_contents("visitors.txt", $visitor_ip." :: ".date("d.m.Y H:i:s")."\r", FILE_APPEND);
            ?>
            
            <div class="alert alert-warning" role="alert">
                * Для сравнения можно отметить только две версии конфигурации. Это
                жесткое правило которое не стоит нарушать.
            </div>
            <!--<div class="alert alert-info">-->
                <?php
                /*$json = $new_search->getJSONString();
                echo '<pre>';
                var_dump($json);
                echo '</pre>';
                
                $json_str = json_encode($json);
                echo '<pre>';
                var_dump($json_str);
                echo '</pre>';*/
                ?>
            <!--</div>-->
        </div>
        
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    </body>
</html>