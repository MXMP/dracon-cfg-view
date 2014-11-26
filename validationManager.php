<?php

class validationManager {
    private $error_flag;
    private $search_str;
    private $search_str2;
    private $search_flag;
    
    public function validate() {
        if (!filter_has_var(INPUT_POST, 'searchMethod')) {
            $this->error_flag = "emptyRequest";
        } else if (filter_input(INPUT_POST, 'searchMethod') == 'ipHash') {
            $this->validateIpHash();
        } else if (filter_input(INPUT_POST, 'searchMethod') == 'oneDay') {
            $this->validateOneDay();
        } else if (filter_input(INPUT_POST, 'searchMethod') == 'dateRange') {
            $this->validateDateRange();
        } else {
            $this->error_flag = "wrongFormat";
        }
    }
    
    public function getErrorFlag() {
        return $this->error_flag;
    }
    
    public function getSearchFlag() {
        return $this->search_flag;
    }

    public function getSearchStr() {
        return $this->search_str;
    }

    public function getSearchStr2() {
        return $this->search_str2;
    }

    private function validateIpHash() {
        // Поиск по ip-адресу или хэшу

        // Получаем входную строку и тут же очищаем ее от всяких примесей,
        $clear_str = filter_input(INPUT_POST, "search_input", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // анализируем строку и пытаемся выяснить по чему искать
        // проверка на пустую строку
        if ($clear_str == '') {
            $this->error_flag = "emptyString";
        } else if (filter_var($clear_str, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE)) {
            $this->search_flag = "ip"; // строка соответствует ip-адресу
        } else if (preg_match("/^[0-9a-f]{32}$/", $clear_str)) {
            $this->search_flag = "hash"; // строка соответсвует хэшу
        } else {
            // ошибка формата ввода, вывод ошибки и остановка выполнения скрипта
            $this->error_flag = "wrongFormat";
        }
    }
    
    private function validateOneDay() {
        // Поиск за один день.
        $this->search_flag = 'oneDay';
        // Проверка на пустой ввод.
        if (filter_input(INPUT_POST, 'oneDayDate') == '') {
            $this->error_flag = "emptyString";
        } else {
            $this->search_str = filter_input(INPUT_POST, 'oneDayDate');
        }
    }
    
    private function validateDateRange() {
        // Поиск по диапазону дат.
        $this->search_flag = 'dateRange';
        // Проверка на пустой ввод.
        if (filter_input(INPUT_POST, 'dateRangeStart') == '' || filter_input(INPUT_POST, 'dateRangeEnd') == '') {
            $this->error_flag = "emptyString";
        } else {
            $this->search_str = filter_input(INPUT_POST, 'dateRangeStart');
            $this->search_str2 = filter_input(INPUT_POST, 'dateRangeEnd');
        }
    }
}

/*// Проверка метода поиска
// Проверка на передачу пустого запроса. Или не отмеченный вариант поиска.
if (!array_key_exists('searchMethod', $_POST)) {
    $error_flag = "emptyRequest";
} else if ($_POST['searchMethod'] == 'ipHash') {
    // Поиск по ip-адресу или хэшу

    // Получаем входную строку и тут же очищаем ее от всяких примесей,
    $search_str = filter_input(INPUT_POST, "search_input", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // анализируем строку и пытаемся выяснить по чему искать
    // проверка на пустую строку
    if ($search_str == '') {
        $error_flag = "emptyString";
    } else if (filter_var($search_str, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE)) {
        $flag = "ip"; // строка соответствует ip-адресу
    } else if (preg_match("/^[0-9a-f]{32}$/", $search_str)) {
        $flag = "hash"; // строка соответсвует хэшу
    } else {
        // ошибка формата ввода, вывод ошибки и остановка выполнения скрипта
        $error_flag = "wrongFormat";
    }                
} else if ($_POST['searchMethod'] == 'oneDay') {
    // Поиск за один день.
    $flag = 'oneDay';
    // Проверка на пустой ввод.
    if ($_POST['oneDayDate'] == '') {
        $error_flag = "emptyString";
    } else {
        $search_str = $_POST['oneDayDate'];
    }
} else if ($_POST['searchMethod'] == 'dateRange') {
    // Поиск по диапазону дат.
    $flag = 'dateRange';
    // Проверка на пустой ввод.
    if ($_POST['dateRangeStart'] == '' || $_POST['dateRangeEnd'] == '') {
        $error_flag = "emptyString";
    } else {
        $search_str = $_POST['dateRangeStart'];
        $search_str2 = $_POST['dateRangeEnd'];
    }
} else {
    // пустой поиск, или некорректный запрос
    $error_flag = "wrongFormat";
}*/
