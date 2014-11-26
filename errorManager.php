<?php
class ErrorManager {
    private $errorFlag;
    private $errors = ["emptyRequest" => "Пустой запрос, задайте параметры для поиска. Скорее всего вы не выбрали метод поиска (шелкните на кружок возле необходимого поля).", 
        "wrongFormat" => "Введены неправильные данные или не соблюден формат ввода.",
        "emptyString" => "Вы не ввели данные для поиска.",
        "unknownError" => "Неизвестная ошибка."];
    
    function __construct($errorFlag) {
        $this->setErrorFlag($errorFlag);
    }
    
    private function setErrorFlag($errorFlag) {
        if (array_key_exists($errorFlag, $this->errors)) {
            $this->errorFlag = $errorFlag;
        } else {
            $this->errorFlag = "unknownError";
        }
    }
    
    public function getErrorMessage() {
        echo '<div class="alert alert-danger" role="alert"><strong>Ошибка! '
            . '</strong>'.$this->errors[$this->errorFlag].' <a href="index.html" '
            . 'class="alert-link">На главную.</a></div>';
    }
}