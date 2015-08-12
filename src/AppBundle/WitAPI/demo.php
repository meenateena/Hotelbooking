<?php

header('content-type: text/plain; charset=UTF-8');

ini_set('display_errors', 'on');
error_reporting(E_ALL);

require('witapi.class.php');

$wit = new WitAPI(array(
  'access_token' => 'PM4HXMY6YSB6ITKNGQW6SFYGVVWEDO2T',
  ));
  
print_r($wit->text_query('turn the lights off'));