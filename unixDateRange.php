<?php
/**
 * @author Kirill Belyaev <bandoftoys@gmail.com>
 * 
 * Функция принимает одну или две даты в формате dd.mm.yyyy (валидация пока не 
 * предусмотрена.
 * Если передана одна дата, то вычисляем unixtimestamp от начала дня до его
 * конца.
 * Если передано 2 даты, ты вычисляем unixtimestamp от начала дня первой
 * даты до конца дня второй даты
 * 
 * @param string $startDate Дата в формате dd.mm.yyyy
 * @param string $endDate Дата в формате dd.mm.yyyy
 * @return array Массив из двух целых чисел в формате unixtimestamp
 */
function getUnixDateRange($startDate, $endDate = NULL){   
    // Разбиваем начальную дату по разделителю ".",
    $startDateArray = explode(".", $startDate);
    // и получаем из нее unixtimestamp на начало дня.
    $unixStartDate = mktime(0,0,0,$startDateArray[1],$startDateArray[0],$startDateArray[2]);
    
    // Если вторая дата не передана, то считаем unixtimestamp до конца этого же
    // дня.
    if (is_null($endDate)) {
        $unixEndDate = mktime(23,59,59,$startDateArray[1],$startDateArray[0],$startDateArray[2]);
    } else {
        // В противном случае (передано две даты), разбиваем и вторую дату по 
        // разделителю ".",
        $endDateArray = explode(".", $endDate);
        // и получаем из нее unixtimestamp на конец дня.
        $unixEndDate = mktime(23,59,59,$endDateArray[1],$endDateArray[0],$endDateArray[2]);
    }
    
    return array($unixStartDate,$unixEndDate);
}
