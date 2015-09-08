<?php
session_start();

foreach ($_POST as $key => $val) {
    $_SESSION[$key] = $val;
}

require_once 'view/generalHeader.php';
require_once 'core/AppExceptions.php';
require_once 'core/Search.php';
require_once 'core/validationManager.php';
require_once 'core/AppSettings.php';
require_once 'core/Paginator.php';

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
    $validator = new validationManager("session");
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
    
    if (isset($_GET['currentPage'])) {
        $_SESSION['currentPage'] = $_GET['currentPage'];
    } else {
        $_SESSION['currentPage'] = 1;
    }
    
    $paginator = new Paginator($new_search->getCount(), $new_search->getLimitResults());
    $numbers = $paginator->getNumbers($_SESSION['currentPage']);
    $new_search->doSearch(null, $numbers['startNum']);
    
    $new_search->getResultsTable($fromUpFlag);        
    
    echo '<center>';
    if (array_key_exists('currentPage', $_SESSION)) {
        $paginator->getUI($_SESSION['currentPage'], "mongo.php", 10);        
    } else {
        $paginator->getUI(1, "mongo.php", 10);       
    }
    echo '</center>';
        
} catch (AppBaseException $ex) {
    $ex->getHtmlPanel();
}

require_once 'view/generalFooter.php';