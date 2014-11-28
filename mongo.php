<?php
require_once 'header.php';

require_once 'AppExceptions.php';
require_once 'search.php';
require_once 'validationManager.php';
require_once 'AppSettings.php';

// получаем настройки приложения
$AppSettings = AppSettings::getInstance();
$dbHost           = $AppSettings->get('dbHost');
$dbName           = $AppSettings->get('dbName');
$dbCollectionName = $AppSettings->get('dbCollectionName');

try {
    // Валидация данных
    $validator = new validationManager();
    $validator->validate();
    
    // Попытка выполнения ранее подготовленного запроса
    $flag = $validator->getSearchFlag();
    if ($flag == "dateRange") {                
        $new_search = new Search($dbHost, $dbName, $dbCollectionName, $flag, 
                $validator->getSearchStr(), $validator->getSearchStr2());
    } else {
        $new_search = new Search($dbHost, $dbName, $dbCollectionName, $flag, 
                $validator->getSearchStr());
    }
    $new_search->getResultsTable();    
} catch (AppBaseException $ex) {
    echo '<div class="panel panel-danger">'
            .'<div class="panel-heading">'
                .'<h3 class="panel-title">'
                    .'<span class="glyphicon glyphicon-warning-sign"></span> Ошибка!'
                .'</h3>'
            .'</div>'
            .'<div class="panel-body">'
                .$ex->getMessage().' <a href="index.php">На главную.</a>'
            .'</div>'
         .'</div>';
}

require_once 'footer.php';