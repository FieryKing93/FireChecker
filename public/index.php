 <?php

$pathInfo = $_SERVER['PATH_INFO'] ?? '/';
$urlMap = [
    '/binchecker' => '../pages/binchecker.php',
    '/ccgen' => '../pages/generator.php',
    '/' => '../pages/home.php'
];

if (isset($urlMap[$pathInfo])) {
    include($urlMap[$pathInfo]);
} else { 
  header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
  include("../pages/404.php");
}