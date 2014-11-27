<?php
require_once 'header.php';

require_once 'search.php';
require_once 'errorManager.php';
require_once 'validationManager.php';
require_once 'AppSettings.php';

// получаем настройки приложения
$AppSettings = AppSettings::getInstance();
$dbHost           = $AppSettings->get('dbHost');
$dbName           = $AppSettings->get('dbName');
$dbCollectionName = $AppSettings->get('dbCollectionName');

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
    $new_search = new Search($dbHost, $dbName, $dbCollectionName, $flag, $validator->getSearchStr(), $validator->getSearchStr2());
} else {
    $new_search = new Search($dbHost, $dbName, $dbCollectionName, $flag, $validator->getSearchStr());
}
$new_search->getResultsTable();
?>

<script type="text/javascript" src="only_two2.js"></script>
<div class="alert alert-warning" role="alert">
    * Для сравнения можно отметить только две версии конфигурации. Это
    жесткое правило которое не стоит нарушать.
</div>

<?php require_once 'footer.php'; ?>