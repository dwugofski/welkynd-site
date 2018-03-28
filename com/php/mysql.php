<?php

include_once 'errors.php';

class MYSQL {
	const MYSQL_IP = "127.0.0.1"; // Do not use "localhost." Use IPv4 address.
	const WELKYND_DB = "welkynd_test";
	const ADMIN_USERNAME = "welkynd_admin";
	const ADMIN_PASSWORD = "stone";
	const GUEST_USERNAME = "welkynd_guest";
	const GUEST_PASSWORD = "stone";

	static private $admin_conn;
	static private $guest_conn;

	static public function init(){
		self::$admin_conn = new mysqli(self::MYSQL_IP, self::ADMIN_USERNAME, self::ADMIN_PASSWORD, self::WELKYND_DB);
		if (self::$admin_conn->connect_error) {
			Errors::log(Errors::MYSQL_ERROR, "Admin connection failed: ".self::$admin_conn->connect_error);
			die();
		}

		self::$guest_conn = new mysqli(self::MYSQL_IP, self::GUEST_USERNAME, self::GUEST_PASSWORD, self::WELKYND_DB);
		if (self::$guest_conn->connect_error) {
			Errors::log(Errors::MYSQL_ERROR, "Guest connection failed: ".self::$guest_conn->connect_error);
			die();
		}
	}

	static private function build_query($stmt, $types, $params) {
		if (count($params) == 0) return $stmt;
		$n = count($params);
		$a_params = array();
		$a_params[] = & $types;
		for($i=0; $i<$n; $i+=1) {
			$a_params[] = & $params[$i];
		}

		call_user_func_array(array($stmt, 'bind_param'), $a_params);
		return $stmt;
	}

	static private function run_query(&$conn, $sql, $types='', $params=[]) {
		$stmt = $conn->prepare($sql);
		if ($stmt === FALSE) {
			Errors::log(Errors::MYSQL_ERROR, "Preparing SQL query failed: ".$conn->error);
			die();
		}
		$ped_stmt = self::build_query($stmt, $types, $params);
		$exec = $ped_stmt->execute();
		if ($exec === FALSE) {
			Errors::log(Errors::MYSQL_ERROR, "Executing SQL query <".$sql."> failed: ".$ped_stmt->error);
			die();
		}

		return $ped_stmt;
	}

	static public function request_info($sql, $types='', $params=[]) {
		$stmt = self::run_query(self::$guest_conn, $sql, $types, $params);
		$res = $stmt->get_result();
		if ($res === FALSE) {
			Errors::log(Errors::MYSQL_ERROR, "Executing SQL query <".$sql."> failed: ".$stmt->error);
			die();
		}

		$rows = array();
		while($row = $res->fetch_array(MYSQLI_ASSOC)) {
			$rows[] = $row;
		}

		return $rows;
	}

	static public function repeat_query(&$conn, $sql, $types='', $param_array=[]) {
		$stmt = $conn->prepare($sql);
		if ($stmt === FALSE) {
			Errors::log(Errors::MYSQL_ERROR, "Preparing SQL query failed: ".$conn->error);
			die();
		}
		foreach ($param_array as $params) {
			if (!is_array($params)) $params = [$params];
			$ped_stmt = self::build_query($stmt, $types, $params);
			$exec = $ped_stmt->execute();
			if ($exec === FALSE) {
				Errors::log(Errors::MYSQL_ERROR, "Executing SQL query <".$sql."> failed: ".$ped_stmt->error);
				die();
			}
		}

		return $ped_stmt;
	}

	static public function create($sql, $types='', $params=[]) {
		$stmt = self::run_query(self::$admin_conn, $sql, $types, $params);
	}

	static public function delete($sql, $types='', $params=[]) {
		$stmt = self::run_query(self::$admin_conn, $sql, $types, $params);
	}

	static public function insert($sql, $types='', $params=[]) {
		$stmt = self::run_query(self::$admin_conn, $sql, $types, $params);
		return self::$admin_conn->insert_id;
	}

	static public function update($sql, $types='', $params=[]) {
		$stmt = self::run_query(self::$admin_conn, $sql, $types, $params);
	}

	static public function create_array($sql, $types='', $params_array=[]) {
		$stmt = self::repeat_query(self::$admin_conn, $sql, $types, $params_array);
	}

	static public function delete_array($sql, $types='', $params_array=[]) {
		$stmt = self::repeat_query(self::$admin_conn, $sql, $types, $params_array);
	}

	static public function insert_array($sql, $types='', $params_array=[]) {
		$stmt = self::repeat_query(self::$admin_conn, $sql, $types, $params_array);
		return self::$admin_conn->insert_id;
	}

	static public function update_array($sql, $types='', $params_array=[]) {
		$stmt = self::repeat_query(self::$admin_conn, $sql, $types, $params_array);
	}
}



?>