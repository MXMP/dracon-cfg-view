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
// Дергаем из настроек имя коллекции в зависимости от установленной галки
// поиска по загруженным конфигам
if(!array_key_exists("from_up", $_POST)) {
    $fromUpFlag = "no";
    $dbCollectionName = $AppSettings->get('dbCollectionName');
} else {
    $fromUpFlag = "yes";
    $dbCollectionName = $AppSettings->get('dbUpCollectionName');
}

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
    $new_search->doSearch();
    $new_search->getResultsTable($fromUpFlag);    
} catch (AppBaseException $ex) {
    $ex->getHtmlPanel();
}

require_once 'view/generalFooter.php';