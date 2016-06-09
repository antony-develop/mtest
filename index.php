<?php

//FRONT CONTROLLER


//General settings
ini_set('dysplay_errors',1);
error_reporting(E_ALL);

session_start();

//Set root directory
define('ROOT', dirname(__FILE__));

//Get Autoload function
require(ROOT.'/components/Autoload.php');

//Routers call 
$router = new Router();
$router->run();