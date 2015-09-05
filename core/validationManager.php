<?php

class validationManager {
    private $search_str;
    private $search_str2;
    private $search_flag;
    private $input_array;
    
    public function __construct($method = "post") {
        switch ($method) {
            case "post":
                $this->input_array = INPUT_POST;
                break;
            case "get":
                $this->input_array = INPUT_GET;
                break;
            case "session":
                $this->input_array = $_SESSION;
                break;
            default :
                throw new AppBaseException("Неправильный метод в запросе!");
        }
    }
    
    public function validate() {
        if (!array_key_exists('searchMethod', $this->input_array)) {
            throw new AppBaseException("Пустой запрос, задайте параметры для поиска. "
                    . "Скорее всего вы не выбрали метод поиска (шелкните на "
                    . "кружок возле необходимого поля).");
        } else if (filter_var($this->input_array['searchMethod']) == 'ipHash') {
            $this->validateIpHash();
        } else if (filter_var($this->input_array['searchMethod']) == 'oneDay') {
            $this->validateOneDay();
        } else if (filter_var($this->input_array['searchMethod']) == 'dateRange') {
            $this->validateDateRange();
        } else {
            throw new AppBaseException("Введены неправильные данные или не соблюден "
                    . "формат ввода.");
        }
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
        $clear_str = filter_var($this->input_array["search_input"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$this->search_str = $clear_str;

        // анализируем строку и пытаемся выяснить по чему искать
        // проверка на пустую строку
        if ($clear_str == '') {
            throw new AppBaseException("Вы не ввели данные для поиска.");
        } else if (filter_var($clear_str, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE)) {
            $this->search_flag = "ip"; // строка соответствует ip-адресу
        } else if (preg_match("/^[0-9a-f]{32}$/", $clear_str)) {
            $this->search_flag = "hash"; // строка соответсвует хэшу
        } else {
            // ошибка формата ввода, вывод ошибки и остановка выполнения скрипта
            throw new AppBaseException("Введены неправильные данные или не соблюден "
                    . "формат ввода.");
        }
    }
    
    private function validateOneDay() {
        // Поиск за один день.
        $this->search_flag = 'oneDay';
        // Проверка на пустой ввод.
        if (filter_var($this->input_array['oneDayDate']) == '') {
            throw new AppBaseException("Вы не ввели данные для поиска.");
        } else {
            $this->search_str = filter_var($this->input_array['oneDayDate']);
        }
    }
    
    private function validateDateRange() {
        // Поиск по диапазону дат.
        $this->search_flag = 'dateRange';
        // Проверка на пустой ввод.
        if (filter_var($this->input_array['dateRangeStart']) == '' || filter_var($this->input_array['dateRangeEnd']) == '') {
            throw new AppBaseException("Вы не ввели данные для поиска.");
        } else {
            $this->search_str = filter_var($this->input_array['dateRangeStart']);
            $this->search_str2 = filter_var($this->input_array['dateRangeEnd']);
        }
    }
}