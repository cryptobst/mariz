<?php
// HTTP
define('HTTP_SERVER', 'http://localhost/mariz/www/admin/');
define('HTTP_CATALOG', 'http://localhost/mariz/www/');

// HTTPS
define('HTTPS_SERVER', 'http://localhost/mariz/www/admin/');
define('HTTPS_CATALOG', 'http://localhost/mariz/www/');

// DIR
// Paths are derived from this file's location, so a fresh clone works
// from any folder. Storage lives OUTSIDE the web root: <clone>/storage/
define('DIR_APPLICATION', str_replace('\\', '/', realpath(dirname(__FILE__))) . '/');
define('DIR_SYSTEM', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../system/')) . '/');
define('DIR_IMAGE', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../image/')) . '/');
define('DIR_STORAGE', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../../storage/')) . '/');
define('DIR_CATALOG', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../catalog/')) . '/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOGS', DIR_STORAGE . 'logs/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'mariz_USR');
define('DB_PASSWORD', 'CHANGE-ME');
define('DB_DATABASE', 'mariz');
define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');

// OpenCart API
define('OPENCART_SERVER', 'https://www.opencart.com/');
