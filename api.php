<?php
// подключаем класс с эксепшенами
require_once 'core/AppExceptions.php';
// подключаем класс валидатора
require_once 'core/validationManager.php';
// подключаем поисковый класс
require_once 'core/Search.php';
// подключаем класс для работы с настройками
require_once 'core/AppSettings.php';

// получаем настройки приложения
$AppSettings = AppSettings::getInstance();
$dbHost           = $AppSettings->get('dbHost');
$dbName           = $AppSettings->get('dbName');
$dbUser		  = $AppSettings->get('dbUser');
$dbPassword	  = $AppSettings->get('dbPassword');

// если в GET пустой массив, возвращаем ошибку #100
if (empty($_GET)) {
    echo 'Error: Empty request';
} else {
    // Дергаем из настроек имя коллекции в зависимости от параметра "from_up"
    if(!array_key_exists("from_up", $_GET)) {
       $fromUpFlag = "no";
        $dbCollectionName = $AppSettings->get('dbCollectionName');
    } else {
        $fromUpFlag = "yes";
        $dbCollectionName = $AppSettings->get('dbUpCollectionName');
    }
    //var_dump($_GET);
    // создаем объект валидатора, указав, что будем
    // проверять GET-параметры (по-умолчанию POST)
    $apiValidator = new validationManager("get");
    try {
        // запускаем процесс валидации входных данных
        $apiValidator->validate();
        
        // Получаем флаг для поиска
        $flag = $apiValidator->getSearchFlag();
        // В зависимости от флага поиска создаем объект класса Search
        if ($flag == "dateRange") {                
            $new_search = new Search($dbHost, $dbName, $dbUser, $dbPassword, $dbCollectionName, $flag, 
                    $apiValidator->getSearchStr(), $apiValidator->getSearchStr2());
        } else {
            $new_search = new Search($dbHost, $dbName, $dbUser, $dbPassword, $dbCollectionName, $flag, 
                    $apiValidator->getSearchStr());
        }
        
        // Если прилетел параметр "limitResults", устанавливаем его и выполняем
        // поиск, иначе действуем со стандартными параметрами
        if (array_key_exists("limitResults", $_GET)) {
            $new_search->doSearch($_GET["limitResults"]);
        } else {
            $new_search->doSearch();
        }
    } catch (AppBaseException $e) {
        echo 'Error: ' . $e->getMessage();
    }
    // возвращаем результат
    if (array_key_exists("format", $_GET)) {
        $new_search->getResults($fromUpFlag, $_GET["format"]);
    } else {
        $new_search->getResults($fromUpFlag);
    }
}