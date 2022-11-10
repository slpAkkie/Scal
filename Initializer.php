<?php

if (!defined('APP_ROOT_PATH')) {
    define('APP_ROOT_PATH', realpath(''));
}

define('SCAL_ROOT_PATH', __DIR__);



require_once SCAL_ROOT_PATH . '/Path.php';
require_once SCAL_ROOT_PATH . '/Loader.php';

spl_autoload_register('Scal\Loader::load');
