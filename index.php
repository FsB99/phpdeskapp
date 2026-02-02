<?php
// XLNZdev by Fsb, licence : MIT

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = rtrim($path, '/') ?: '/';

switch ($path) {
  case '/':
    echo "<h1>HomePage</h1>";
    echo "<a href='/'>Home</a> | <a href='/about'>About</a>";
    break;

  case '/about':
    echo '<h1>About</h1>';
    echo "<a href='/'>Home</a> | <a href='/about'>About</a>";
    break;

  case '/api/status':
    header('Content-Type: application/json');
    require __DIR__ . '/../pages/api_status.php';
    break;

  default:
    http_response_code(404);
    echo '404 Not Found';
}