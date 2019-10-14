# Каталог товара

Законченый модуль для построения каталога товара или магазина. Пока не ПОДДЕРЖИВАЕТСЯ торговые предложения (хар-ки товара).

Готова админка пока.

Имеется встроеный обработчик загрузки из 1С, обрабатывает результаты загрузки из модуля masterflash-ru/commerceml

Установка composer require masterflash-ru/catalog

Есть особенность загрузки из 1С, список брендов загружается в общие параметры товара, т.е. нужно создать параметр товара с системным именем BREND.
Если его нет, то бренды никуда не загружаются.

Подробности работы и конфигурация будет позже, после обкатки на реальном сайте



Для работы с базой в конфиге приложения должно быть объявлено DefaultSystemDb:
```php
......
    "databases"=>[
        //соединение с базой + имя драйвера
        'DefaultSystemDb' => [
            'driver'=>'MysqlPdo',
            //"unix_socket"=>"/tmp/mysql.sock",
            "host"=>"localhost",
            'login'=>"root",
            "password"=>"**********",
            "database"=>"simba4",
            "locale"=>"ru_RU",
            "character"=>"utf8"
        ],
    ],
.....
```
для работы с кешем аналогично:
```php
.....
    'caches' => [
        'DefaultSystemCache' => [
            'adapter' => [
                'name'    => Filesystem::class,
                'options' => [
                    // Store cached data in this directory.
                    'cache_dir' => './data/cache',
                    // Store cached data for 3 hour.
                    'ttl' => 60*60*2 
                ],
            ],
            'plugins' => [
                [
                    'name' => Serializer::class,
                    'options' => [
                    ],
                ],
            ],
        ],
    ],
.....
```
