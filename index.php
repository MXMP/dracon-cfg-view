<?php require_once 'view/generalHeader.php'; ?>

<link id="bsdp-css" href="bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">

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
            <form action="mongo.php" method="post" role="search" class="form-inline" >
                <div class="container">

                    <div class="form-group">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <input class="form-control" type="radio" name="searchMethod" value="ipHash">
                                <input type="text" name="search_input" placeholder="ip-адрес или хэш" class="form-control" size="32">
                            </div>
                            <div class="panel-footer">
                                <strong>Поиск по ip или hash</strong>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="panel panel-info">
                            <div class="panel-body">
                                <input class="form-control" type="radio" name="searchMethod" value="dateRange" checked="checked">
                                <div class="input-group input-daterange" id="datepickerRange">
                                    <input type="text"  class="form-control" name="dateRangeStart" readonly value="<?php echo date("d.m.Y"); ?>" />
                                    <span class="input-group-addon">до</span>
                                    <input type="text"  class="form-control" name="dateRangeEnd" readonly value="<?php echo date("d.m.Y"); ?>" />
                                </div>
                            </div>
                            <div class="panel-footer">
                                <strong>Поиск за диапазон дат</strong>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <div class="container">
                    <input type="checkbox" name="from_up" value="yes" /> Поиск по выгруженным конфигам в базу
                </div>
                
                <div class="container">
                    <button class="btn btn-primary btn-large btn-block" type="submit">Go!</button>
                </div>
            </form>

        <!-- JavaScript for DatePicker -->
        <script src="bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script src="bootstrap-datepicker/js/locales/bootstrap-datepicker.ru.js" charset="UTF-8"></script>
        <script src="js/datePicker_main.js"></script>
            
<?php require_once 'view/generalFooter.php'; ?>