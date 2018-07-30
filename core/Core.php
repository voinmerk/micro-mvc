<?php

/**
 * Class Core
 */
class Core extends \core\BaseCore
{
}

spl_autoload_register(array('Core', 'autoload'), true, true);
Core::$classMap = require __DIR__ . '/../config/classes.php';