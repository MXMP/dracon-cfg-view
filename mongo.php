<?php
require_once 'view/generalHeader.php';

require_once 'core/AppExceptions.php';
require_once 'core/Search.php';
require_once 'core/validationManager.php';
require_once 'core/AppSettings.php';

// получаем настройки приложения
$AppSettings = AppSettings::getInstance();
$dbHost           = $AppSettings->get('dbHost');
$dbName           = $AppSettings->get('dbName');
$dbUser		  = $AppSettings->get('dbUser');
$dbPassword	  = $AppSettings->get('dbPassword');
$dbCollectionName = $AppSettings->get('dbCollectionName');

try {
    // Валидация данных
    $validator = new validationManager();
    $validator->validate();
    
    // Попытка выполнения ранее подготовленного запроса
    $flag = $validator->getSearchFlag();
    if ($flag == "dateRange") {                
        $new_search = new Search($dbHost, $dbName, $dbUser, $dbPassword, $dbCollectionName, $flag, 
                $validator->getSearchStr(), $validator->getSearchStr2());
    } else {
        $new_search = new Search($dbHost, $dbName, $dbUser, $dbPassword, $dbCollectionName, $flag, 
                $validator->getSearchStr());
    }
    $new_search->getResultsTable();    
} catch (AppBaseException $ex) {
    $ex->getHtmlPanel();
}

require_once 'view/generalFooter.php';