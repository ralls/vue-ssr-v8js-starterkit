<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$frontend = __DIR__ . '/vue-ssr-starterkit';
$assets = $frontend . '/dist';

if (php_sapi_name() == 'cli-server') {
  $info = parse_url($_SERVER['REQUEST_URI']);

  $path = $info['path'];
  $resource = $assets . $path;

  if ($path != '/' && file_exists($resource)) {
    header('Content-Type: ' . mime_content_type($resource));
    echo file_get_contents($resource);
  }
  else {
    require_once('./index.php');
  }

  return true;
}
