#!/usr/bin/php
<?php
//setup global $_SERVER variables to keep WP from trying to redirect
$_SERVER = array(
  "HTTP_HOST" => "http://{{apache_hostname}}",
  "SERVER_NAME" => "http://{{apache_hostname}}",
  "REQUEST_URI" => "/",
  "REQUEST_METHOD" => "GET"
);

//require the WP bootstrap
require_once(dirname(__FILE__).'/wp-load.php');

require_once __DIR__.'/wp-content/themes/calrice/{{theme_name}}/'.$argv[1].'.class.php';

$mod = new $argv[1]();
var_dump(call_user_func_array( array($mod, $argv[2]), array_slice($argv,3) ));
