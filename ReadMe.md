# [2020] Datasets for Archilab

Для обновления данных в фильтре Дата сет надо:

1. Скачать исходную таблицу с сервера (FTP). Путь к файлу:
 - /project-folder/public_html/script/dataset_filter/files/data-set_all_data.xls

2. Внести необходимые изменения. Для добавления нового года:
 - добавить новый лист
 - Названием листа должен быть год
 - Заполнить данные СТРОГО как на предыдущих листах (можно скопировать таблицу с другого личта и заменить значения)

3. Загрузить файл на сервер (FTP) с заменой предыдущего. Название файла НЕ МЕНЯТЬ (путь к файлу см.выше).

4. Запустить скрипт обновления данных. Для этого открыть страницу:
 - http://project.domain/script/dataset_filter/update.php

5. После успешного обновления данных будет выведены надпись "DATA UPDATE: " и список обновлённых данных. ГОТОВО.
