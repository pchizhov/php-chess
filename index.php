<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'APIEngine.php';

header('Content-type: text/html; charset=UTF-8');
if (count($_REQUEST) > 0) {
    foreach ($_REQUEST as $api_function_name => $api_function_params) {
        $api_engine = new APIEngine($api_function_name, $api_function_params);
        echo $api_engine->call_api_function();
        break;
    }
}
