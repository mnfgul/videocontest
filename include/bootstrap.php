<?php 
/*Initilaize the required files and object for application*/

//set include path for Zend framework
//ini_set('include_path', 'D:/Web/Server/lcvideo/include');
set_include_path(dirname(__FILE__).PATH_SEPARATOR.get_include_path());

//start user session
session_start();

//error reporting
ini_set('error_reporting',E_ALL ^ E_NOTICE);
ini_set('display_errors', 1);

//load Zend Framework
require_once 'Zend/Loader.php';

//load the main objects of the application
require_once 'lc-video.php';

//load the main objects for YouTube of the application
include_once 'lc-youtube.php';

//define global contants
define("BASE_PATH", realpath(dirname(__FILE__).'/../'));
define("VIDEO_FOLDER", BASE_PATH.'/files/');


?>