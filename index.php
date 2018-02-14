<?php require_once 'view/generalHeader.php'; ?>

    <link id="bsdp-css" href="/vendor/eternicode/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css"
          rel="stylesheet">

    <p>
        Сервис позволяет получить информацию о том, когда и какой
        конфигурационный файл был загружен в коммутатор или с
        коммутатора в базу сервиса (установить галку внизу формы для
        поиска). Поиск можно выполнить путем ввода в поле ниже
        ip-адреса коммутатора либо, так называемого, хэша
        конфигурационного файла. Также можно воспользоваться поиском
        загрузок за конкретную дату.
    </p>
    <p>
        Еще один вариант поиска - указание диапазона дат. Соответственно
        будут выведены все коммутаторы, в которые осуществлялась
        загрузка в указанный период.
    </p>
    <p>
        Сервис обзавелся простым <a href="apiGuide.php">API</a> для запросов к базе без посещения
        web-странички.
    </p>
    <p>Have fun!</p>
    <form action="mongo.php" method="post" role="search">

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <div class="radio">
                    <label>
                        <input class="form-control" type="radio" name="searchMethod" value="ipHash">
                        <input type="text" name="search_input" placeholder="ip-адрес или хэш" class="form-control"
                               size="32" id="search_input">
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <div class="radio">
                    <label>
                        <input class="form-control" type="radio" name="searchMethod" value="dateRange"
                               checked="checked">
                        <div class="input-group input-daterange" id="datepickerRange">
                            <input type="text" class="form-control" name="dateRangeStart" readonly
                                   value="<?php echo date("d.m.Y"); ?>"/>
                            <span class="input-group-addon">до</span>
                            <input type="text" class="form-control" name="dateRangeEnd" readonly
                                   value="<?php echo date("d.m.Y"); ?>"/>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="from_up" value="yes"/> Поиск по выгруженным конфигам в базу
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button class="btn btn-primary btn-large" type="submit">Поиск</button>
            </div>
        </div>

    </form>

    <!-- JavaScript for DatePicker -->
    <script src="/vendor/eternicode/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="/vendor/eternicode/bootstrap-datepicker/dist/locales/bootstrap-datepicker.ru.min.js"
            charset="UTF-8"></script>
    <script src="/js/datePicker_main.js"></script>

<?php require_once 'view/generalFooter.php'; ?>