<?php

class Errors {
	const USER_NOT_FOUND = 1;
	const INVALID_FORMAT = 2;
	const USER_ALREADY_EXISTS = 3;
	const INCORRECT_PASSWORD = 4;
	const RUNTIME_ERROR = 5;
	const MYSQL_ERROR = 6;
	const PERMISSIONS_ERROR = 7;
	const IMAGES_ERROR = 8;

	static public function log($error, $msg) {
		error_log(self::error_to_string($error).": ".$msg);
		return ['error' => $error, 'msg' => $msg];
	}

	static public function echo_json($msg) {
		$response = ['error' => $msg];
		error_log('USER error: ' . json_encode($response));
		echo json_encode($response);
		die();
	}

	static public function error_to_string($error) {
		switch($error) {
			case self::USER_NOT_FOUND:
				return "USER NOT FOUND";
				break;
			case self::INVALID_FORMAT:
				return "INVALID FORMAT";
				break;
			case self::USER_ALREADY_EXISTS:
				return "USER ALREADY EXISTS";
				break;
			case self::INCORRECT_PASSWORD:
				return "INCORRECT PASSWORD";
				break;
			case self::RUNTIME_ERROR:
				return "RUNTIME ERROR";
				break;
			case self::MYSQL_ERROR:
				return "MYSQL ERROR";
				break;
			case self::PERMISSIONS_ERROR:
				return "PERMISSIONS ERROR";
				break;
			case self::IMAGES_ERROR:
				return "IMAGES ERROR";
				break;
			default:
				return "ERROR";
		}
	}
}

?>