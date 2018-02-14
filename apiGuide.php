<?php require_once 'view/generalHeader.php'; ?>

<h1>API Guide</h1>

<p>
    Если Вам вдруг захотелось странного и Вы желаете получать данные из базы
    этого замечательного сервиса каким-либо скриптом минуя посещение
    Web-интерфеса вручную, то эта страничка немного Вам поможет. Have fun!
</p>
<p>
    Итак, что бы запрашивать данные нужно слать GET-запросы на URL: 
    <code>http://configs.localhost/api.php</code>
</p>

<h2>Варианты использования</h2>
<h3>Поиск по ip-адресу или хэшу</h3>
    <p>Нужно установить следующие параметры:</p>
    <ul>
        <li><code>searchMethod=ipHash</code></li>
        <li><code>search_input=&lt;ip-адрес или хэш конфига&gt;</code></li>
    </ul>
    <p>
        <strong>Пример:</strong>
        <code>http://configs.localhost/api.php?searchMethod=ipHash&AMP;search_input=10.99.192.19</code>
    </p>

<h3>Поиск за один день</h3>
    <p>Нужно установить следующие параметры:</p>
    <ul>
        <li><code>searchMethod=oneDay</code></li>
        <li><code>oneDayDate=&ltдата в формате дд.мм.гггг&gt;</code></li>
    </ul>
    <p>
        <strong>Пример:</strong>
        <code>http://configs.localhost/api.php?searchMethod=oneDay&AMP;oneDayDate=01.07.2015</code>
    </p>

<h3>Поиск в диапазоне дат</h3>
    <p>Нужно установить следующие параметры:</p>
    <ul>
        <li><code>searchMethod=dateRange</code></li>
        <li><code>dateRangeStart=&ltдата в формате дд.мм.гггг&gt;</code></li>
        <li><code>dateRangeEnd=&ltдата в формате дд.мм.гггг&gt;</code></li>
    </ul>
    <p>
        <strong>Пример:</strong>
        <code>http://configs.localhost/api.php?searchMethod=dateRange&AMP;dateRangeStart=01.06.2015&AMP;dateRangeEnd=01.07.2015</code>
    </p>
    
<h2>Кастомизация</h2>
    <p>
        По-умолчанию сервер ищет по табличке <u>с загруженными на коммутаторы</u> 
        конфигами и отвечает данными в формате JSON.
    </p>
    <p>
        Если нужно выполнить поиск по
        табличке <u>с выгруженными с коммутаторов</u> конфигами, то нужно добавить
        в запрос параметр <code>from_up</code> и установить ему значение 
        <code>yes</code>.
    </p>
    <p>
        Если хочется получить на выходе не JSON, а простую HTML-табличку, то
        добавляем в запрос параметр <code>format</code> и устанавливаем ему значение
        <code>htmlTable</code>. В ответ получим вот такое 
        <a href="http://configs.localhost/api.php?searchMethod=ipHash&search_input=10.99.192.19&format=htmlTable">чудо</a>.
    </p>
    <p>
        По-умолчанию ответ от сервера содержит максимум 10 результатов. Соответственно,
        если <strong>нам нужно БОЛЬШЕ РЕЗУЛЬТАТОВ</strong>, то нужно послать
        параметр <code>limitResults</code>, установив в него количество нужных
        результатов.
    </p>

<?php require_once 'view/generalFooter.php'; ?>