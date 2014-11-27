<?php
require_once 'unixDateRange.php';

class Search {
    private $cursor;
    private $fields = ['ip'=>true, 'date'=>true, 'hash'=>true];
    private $collectionName;
    private $limitResults;
    private $query;
    private $errors = [];
    private $searchMethod = "ip";
    private $searchMethods = ["ip", "hash", "oneDay", "dateRange"];
    private $dbHost;
    private $dbName;
    private $collection;
    private $searchStr;
    private $searchStr2;

    private function setSearchMethod($searchMethod) {
        // Сверяем переданный метод поиска с массивом эталонных значений.
        // Если есть в массиве
        if (in_array($searchMethod, $this->searchMethods)) {
            $this->searchMethod = $searchMethod; // то устанавливаем поле класса
        } else {
            $this->errors[] = "Передан неверный метод поиска."; // Иначе пишем ошибку в массив с ошибками
        }
    }    

    public function getSearchMethod() {
        // Просто возвращаем метод поиска.
        // Если поле не заполненно, то выводится стандартное значение "ip"
        return $this->searchMethod;
    }
    
    private function setDBHost($dbHost) {
        /**
         * @todo Сделать валидацию вводных данных, а то некрасиво как-то
         */
        // Просто устанавливаем это поле.
        // Не производим валидацию принятых данных (надеемся на
        // честность и аккуратность пользователя)
        $this->dbHost = $dbHost;
    }
    
    public function getDBHost() {
        // Просто возвращаем хост базы данных.
        // Данное поле всегда будет содержать какие-то данные, т.к. без хоста
        // класс не будет создан (не отработает конструктор).
        return $this->dbHost;
    }
            
    private function setDBName($dbName) {
        /**
         * @todo Сделать валидацию вводных данных, а то некрасиво как-то
         */
        // Просто устанавливаем это поле.
        // Не производим валидацию принятых данных (надеемся на
        // честность и аккуратность пользователя)
        $this->dbName = $dbName;
    }
    
    public function getDBName() {
        // Просто возвращаем имя базы данных.
        // Данное поле всегда будет содержать какие-то данные, т.к. без имени
        // класс не будет создан (не отработает конструктор).
        return $this->dbName;
    }    
    
    private function setCollectionName($collectionName) {
        /**
         * @todo Сделать валидацию вводных данных, а то некрасиво как-то
         */
        // Просто устанавливаем это поле.
        // Не производим валидацию принятых данных (надеемся на
        // честность и аккуратность пользователя)
        $this->collectionName = $collectionName;        
    }
    
    public function getCollectionName() {
        // Просто возвращаем имя коллекции.
        // Данное поле всегда будет содержать какие-то данные, т.к. без имени
        // класс не будет создан (не отработает конструктор).
        return $this->collectionName;        
    }
    
    private function setSearchStr($searchStr) {
        $this->searchStr = $searchStr;
    }
    
    private function setSearchStr2($searchStr2) {
        $this->searchStr2 = $searchStr2;
    }
    
    function __construct($dbHost, $dbName, $collectionName, $searchMethod, $searchStr, $searchStr2 = null, $limit = 10) {
        $this->setDBHost($dbHost); // Заполняем хост БД
        $this->setDBName($dbName); // Заполняем имя БД
        $this->setCollectionName($collectionName); // Заполняем имя коллекции в БД
        $this->setSearchMethod($searchMethod); // Заполняем метод поиска
        $this->setSearchStr($searchStr);
        
        $this->setSearchStr2($searchStr2);
        $this->getQuery();        
        $this->limitResults = $limit;
        
        try {
            $server = new MongoClient("mongodb://{$this->dbHost}");
            $this->collection = $server->$dbName->$collectionName;                
            $this->doSearch();
        } catch (MongoConnectionException $ex) {
            $this->errors[] = 'Ошибка подключения к базе:' . $ex->getMessage();
        }
    }
    
    private function getQuery() {
        switch ($this->searchMethod) {
            case "ip":
                $this->query = array('ip' => ip2long($this->searchStr));
                break;
            case "hash":
                $this->query = array('hash' => $this->searchStr);
                break;
            case "oneDay":
                $dateRangeForQuery = getUnixDateRange($this->searchStr);
                $this->query = array('date' => array('$gte' => $dateRangeForQuery[0], '$lte' => $dateRangeForQuery[1]));
                break;
            case "dateRange":
                $dateRangeForQuery = getUnixDateRange($this->searchStr, $this->searchStr2);
                $this->query = array('date' => array('$gte' => $dateRangeForQuery[0], '$lte' => $dateRangeForQuery[1]));
                break;
        }
    }
    
    private function doSearch() {
        $this->cursor = $this->collection->find($this->query, $this->fields)->limit($this->limitResults)->sort(array('date' => -1));
    }
    
    public function getResultsTable($formAction) {
        if (!is_null($formAction) || $formAction != "") {
            if ($this->cursor->count() != 0) {
                echo '<form action="'.$formAction.'" method="post"><div 
                    class="panel panel-default"><div class="panel-heading">
                    Результаты поиска (последние '.  $this->limitResults.')</div><table 
                    class="table table-hover"><tr><th>ip-адрес</th><th>Дата загрузки</th>
                    <th>Хэш</th><th>Сравнение*</th></tr><tr>';
                foreach($this->cursor as $document) {
                    $ip = long2ip($document["ip"]);
                    echo "<td><a href=telnet://".$ip.">".$ip."</td>";
                    echo "<td>".date('Y-m-d H:i:s', $document["date"])."</td>";
                    echo "<td><a href=show_cfg.php?hash=".$document["hash"].">".$document["hash"]."</a></td>";
                    echo '<td align="center"><input type=checkbox name= hash[] value='.$document["hash"].' /></td></tr>';
                }            
                echo '<tr><td colspan="4" align="right"><input type="submit" 
                    class="btn btn-default" value="Сравнить" name="make_diff" 
                    disabled="true" /></td></tr></table></div></form>';
            } else {
                echo '<div class="alert alert-danger" role="alert">По вашему 
                    запросу ничего не найдено. <a href="index.html" 
                    class="alert-link">Повторите поиск.</a></div>';
            }
        } else {
            $this->errors[] = "Не передано обязательное действие.";
        }
    }
}
