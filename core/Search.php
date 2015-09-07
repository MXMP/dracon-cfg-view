<?php
require_once 'core/unixDateRange.php';

class Search {
    private $cursor;
    private $fields = ['ip'=>true, 'date'=>true, 'hash'=>true, 'target'=>true, 
        'switch' => true];
    private $collectionName;
    private $limitResults = 10;
    private $query;
    private $errors = [];
    private $searchMethod = "ip";
    private $searchMethods = ["ip", "hash", "oneDay", "dateRange"];
    private $dbHost;
    private $dbName;
    private $collection;
    private $searchStr;
    private $searchStr2;
    private $count;

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
        // честность и аккуратность пользователя);
        $this->dbName = $dbName;
    }

    public function getDBName() {
        // Просто возвращаем имя базы данных.
        // Данное поле всегда будет содержать какие-то данные, т.к. без имени
        // класс не будет создан (не отработает конструктор).
        return $this->dbName;
    }    

    private function setCollectionName($collectionName) {
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
    
    public function getCount() {
        return $this->count;
    }
    
    private function setLimitResults($limit) {
        $limit = (integer) $limit;
        if (gettype($limit) !== "integer") {
            throw new AppBaseException("Wrong value of limit parameter");
        } else {
            $this->limitResults = $limit;
        }
    }
    
    public function getLimitResults() {
        return $this->limitResults;
    }
    
    function __construct(
            $dbHost, 
            $dbName, 
            $dbUser, 
            $dbPassword, 
            $collectionName, 
            $searchMethod, 
            $searchStr, 
            $searchStr2 = null) {
        $this->setDBHost($dbHost); // Заполняем хост БД
        $this->setDBName($dbName); // Заполняем имя БД
        $this->setCollectionName($collectionName); // Заполняем имя коллекции в БД
        $this->setSearchMethod($searchMethod); // Заполняем метод поиска
        $this->setSearchStr($searchStr);        
        $this->setSearchStr2($searchStr2);
        $this->getQuery();        
        
        try {
            $server = new MongoClient("mongodb://{$this->dbHost}", array("db" => $dbName, "username" => $dbUser, "password" => $dbPassword));
            $this->collection = $server->$dbName->$collectionName;                            
        } catch (MongoConnectionException $ex) {
	    echo $ex->getMessage();
            $this->errors[] = 'Ошибка подключения к базе:' . $ex->getMessage();
        }
    }

    private function getQuery() {
        switch ($this->searchMethod) {
            case "ip":
                $longip = (float) sprintf("%u", ip2long($this->searchStr));
                $this->query = array('$or' => array(
                    array('ip' => $longip),
                    array('target' => $longip)
                ));
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
    
    public function doSearch($limit = NULL, $skip = 0) {
        if ($limit !== NULL) {
            $this->setLimitResults($limit);
        }
        $this->cursor = $this->collection->find($this->query, $this->fields)->limit($this->limitResults)->skip($skip)->sort(array('date' => -1));
        $this->count = $this->cursor->count();
    }
    
    public function getResultsTable($fromUp = "no", $formAction = "Diff.php") {
        if ($this->cursor->count() != 0) {            
            echo '<form action="'.$formAction.'" method="post"><div 
                class="panel panel-default"><div class="panel-heading">
                Результаты поиска ';
            if ($this->count > $this->limitResults) {
                echo '(отображено '.  $this->limitResults.' из '.$this->count.')';
            } else {
                echo "({$this->count})";
            }
            echo '</div><table 
                class="table table-hover"><tr><th>ip клиента</th><th>ip устройства</th>
                <th>Модель</th><th>Дата загрузки</th><th>Хэш</th><th>Сравнение*</th></tr><tr>';
            foreach($this->cursor as $document) {
                $ip = long2ip($document["ip"]);
                echo "<td><a href=telnet://".$ip.">".$ip."</td>";
                $target = long2ip($document["target"]);
                echo "<td><a href=telnet://".$target.">".$target."</td>";
                echo "<td>" . $document["switch"] . "</td>";
                echo "<td>".date('Y-m-d H:i:s', $document["date"])."</td>";
                if ($fromUp == "yes") {
                    echo "<td><a href=ShowConfig.php?from_up=yes&hash=".$document["hash"].">".$document["hash"]."</a></td>";
                } else {
                    echo "<td><a href=ShowConfig.php?hash=".$document["hash"].">".$document["hash"]."</a></td>";
                }
                echo '<td align="center"><input type=checkbox name= hash[] value='.$document["hash"].' /></td></tr>';
            }
            if ($fromUp == "yes") {
                echo '<input type="hidden" name="from_up" value="yes" />';
            }
            echo '<tr><td colspan="6" align="right"><input type="submit" 
                class="btn btn-primary" value="Сравнить" name="make_diff" 
                disabled="true" /></td></tr></table></div></form><script 
                type="text/javascript" src="js/only_two2.js"></script><div 
                class="alert alert-info" role="alert">* Для сравнения можно 
                отметить только две версии конфигурации. Это жесткое правило 
                которое не стоит нарушать.</div>';            
        } else {
            echo '<div class="alert alert-warning" role="alert">По вашему 
                запросу ничего не найдено. <a href="index.php" 
                class="alert-link">Повторите поиск.</a></div>';
        }
    }
    
    public function getResults($fromUp = "no", $format = "json") {
        if ($this->cursor->count() != 0) {
            if ($format == "htmlTable") {
                echo '<table><tr><th>ip клиента</th><th>ip устройства</th><th>Модель'
                . '</th><th>Дата загрузки</th><th>Хэш</th></tr><tr>';
                foreach($this->cursor as $document) {
                    $ip = long2ip($document["ip"]);
                    echo "<td><a href=telnet://".$ip.">".$ip."</td>";
                    $target = long2ip($document["target"]);
                    echo "<td><a href=telnet://".$target.">".$target."</td>";
                    echo "<td>" . $document["switch"] . "</td>";
                    echo "<td>".date('Y-m-d H:i:s', $document["date"])."</td>";
                    if ($fromUp == "yes") {
                        echo "<td><a href=http://switch.powernet/switch-config/ShowConfig.php?from_up=yes&hash=".$document["hash"].">".$document["hash"]."</a></td>";
                    } else {
                        echo "<td><a href=http://switch.powernet/switch-config/ShowConfig.php?hash=".$document["hash"].">".$document["hash"]."</a></td>";
                    }
                    echo '</tr>';
                }
            } else if ($format == "json") {
                $i = 1;
                foreach ($this->cursor as $document) {
                    $result_name = "result" . $i;
                    $arr["ip"] = long2ip($document["ip"]);
                    $arr["target"] = long2ip($document["target"]);
                    $arr["switch"] = $document["switch"];
                    $arr["date"] = date('Y-m-d H:i:s', $document["date"]);
                    $arr["hash"] = $document["hash"];
                    $output_array[$result_name] = $arr;
                    $i++;
                }
                echo json_encode($output_array);
            } else {
                throw new AppBaseException("Invalid results format");
            }
        } else {
            echo 'No results';
        }            
    }
    
}