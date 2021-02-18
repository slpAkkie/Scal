<?php

/*
|
|--------------------------------------------------
| Class autoloader - Scal
|--------------------------------------------------
|
| Simple class autoloader from me for you.
| This is the entry point of Scal. Here is no logic
| it's in the ScalClass.php file, but if you want
| to customize the paths of your namespaces you
| should go to the configuration file next to.
|
*/

// Determine the directory where the Scal was executed
define('SCAL_EXECUTED_IN', realpath('') . DIRECTORY_SEPARATOR);

// Define Scal paths
define('SCAL_REAL_PATH', __DIR__ . DIRECTORY_SEPARATOR);
define('SCAL_SUPPORT_PATH', SCAL_REAL_PATH . 'Support/');
define('SCAL_EXCEPTIONS_PATH', SCAL_REAL_PATH . 'Exceptions/');

// Use next lines if you developing Scal
require_once SCAL_SUPPORT_PATH . 'Debug.php';
define('SCAL_TEST_PATH', SCAL_REAL_PATH . 'Tests/');



// Including Scal files
require_once SCAL_EXCEPTIONS_PATH . 'BaseException.php';
require_once SCAL_EXCEPTIONS_PATH . 'ClassNotFoundException.php';
require_once SCAL_EXCEPTIONS_PATH . 'ConfigurationNotFoundException.php';
require_once SCAL_EXCEPTIONS_PATH . 'FileNotFoundException.php';
require_once SCAL_EXCEPTIONS_PATH . 'FolderNotFoundException.php';

require_once SCAL_REAL_PATH . 'Loader.php';

spl_autoload_register('Scal\Loader::load');
