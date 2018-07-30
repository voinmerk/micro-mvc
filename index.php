<?php

require __DIR__ . '/config/defines.php';

require DIR_BASE . '/debug.php';

// Подключаем настройки веб-приложения
$config = require DIR_CONFIG . '/app.php';

// Дополнительные библиотеки (twig)
require DIR_BASE . '/vendor/autoload.php';

// Запускаем autoload
require DIR_CORE . '/BaseCore.php';
require DIR_CORE . '/Core.php';

// Запускаем веб-приложение
(new \core\Application($config))->start();