<?php

if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

define("CONTROLLERS", 'app/controllers/');
define("VIEWS", 'app/views/');
define("MODELS", 'app/models/');
define("HELPERS", "system/helpers/");
define("WEBFILES", "/webfiles/");
require_once 'system/system.php';
require_once 'system/controller.php';
require_once 'system/model.php';
session_start();

spl_autoload_register(function($classe) {
    if (file_exists(MODELS . $classe . '.php'))
        require_once( MODELS . $classe . '.php' );
    else if (file_exists(HELPERS . $classe . '.php'))
        require_once( HELPERS . $classe . '.php' );
    else if (file_exists(HELPERS . "mailer/" . $classe . '.php'))
        require_once( HELPERS . "mailer/" . $classe . '.php' );
    else if (file_exists(HELPERS . "mpdf/" . $classe . '.php'))
        require_once( HELPERS . "mpdf/" . $classe . '.php' );
    else if (file_exists(HELPERS . "PHPExcel/" . $classe . '.php'))
        require_once( HELPERS . "PHPExcel/" . $classe . '.php' );
    else
        die("Model ou Helper nao encontrado. " . $classe . '.php');
});



$settings = new Settings_Model();

foreach ($settings->getSiteTitle() as $site) {
    define("TITLE", $site['title']);
    define("URL_SITE", $site['url']);
}

$start = new System();
$start->run();
