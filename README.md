1. Скачать и установить на локальный сервер (OpenServer, Denwer) Битрикс редакции Старт https://www.1c-bitrix.by/download/cms.php Скриншот: https://prnt.sc/uyhs35
2. Перенести с заменой все файлы из папки data в корень вашей папки с битриксом
3. В панели администратора заменить шаблон на "Тестовый" Настройки -> Настройки продукта -> Сайты -> Список сайтов -> Ваш сайт Скриншот: https://prnt.sc/uyi4ox
4. Выполнить импорт инфоблока из файла "export_file_PzanTxATYNHwPZhN.csv" разделители точка с запятой
5. Проверить работоспособность задания

Возможные ошибки: Неверно задан id инфоблока в файлах ./l/index.php стр. 9 и ./data/local/templates/test/ajax.php стр. 42 
