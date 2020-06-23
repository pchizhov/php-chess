<?php

require_once 'ChessAPI.php';


class APIEngine {

    private array $api_function_name;
    private string $api_function_params;

    function __construct($api_function_name, $api_function_params) {
        $this->api_function_params = stripcslashes($api_function_params);
        $this->api_function_name = explode('_', $api_function_name, 2);
    }

    function create_default_json() {
        $result = json_decode('{}');
        $result->response = json_decode('{}');
        return $result;
    }

    function call_api_function() {
        $result_function_call = $this->create_default_json();
        $api_name = strtolower($this->api_function_name[0]);
        if ($api_name == 'chessapi') {
            try {
                $function_name = $this->api_function_name[1];
                $json_params = json_decode($this->api_function_params);
                if (is_callable(('ChessAPI::' . $function_name))) {
                    try {
                        $result_function_call->response = call_user_func('ChessAPI::' . $function_name, $json_params);
                    } catch (Exception $e) {
                        $result_function_call->errno = 400;
                        $result_function_call->error = 'Bad Request';
                        $result_function_call->message = $e->getMessage();
                    }
                } else {
                    $result_function_call->errno = 400;
                    $result_function_call->error = 'Bad Request';
                    $result_function_call->message = "Method $function_name not found";
                }
            } catch (Exception $e) {
                $result_function_call->error = $e->getMessage();
            }
        } else {
            $result_function_call->errno = 400;
            $result_function_call->error = 'Wrong API';
            $result_function_call->message = "API $api_name not found";
        }
        return json_encode($result_function_call);
    }

}
