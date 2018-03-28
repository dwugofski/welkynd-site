<?php

include_once 'mysql.php';
include_once 'errors.php';
include_once 'users.php';

class Permissions {
	static public function get_perm_id($perm_desc) {
		$sql = "SELECT id FROM perms WHERE description = ?";
		$permission = MYSQL::request_info($sql, 's', [$perm_desc]);
		if (count($permission) < 1) Errors::log(Errors::PERMISSIONS_ERROR, "No entries found for permission of description '".$perm_desc."'");
		return $permission[0]['id'];
	}
	
	static public function get_perm_desc($perm_id) {
		$sql = "SELECT description FROM perms WHERE id = ?";
		$permission = MYSQL::request_info($sql, 'i', [$perm_id]);
		if (count($permission) < 1) Errors::log(Errors::PERMISSIONS_ERROR, "No entries found for permission of id '".$perm_id."'");
		return $permission[0]['description'];
	}
	
	static public function get_role_id($role_name) {
		$sql = "SELECT id FROM roles WHERE name = ?";
		$role = MYSQL::request_info($sql, 's', [$role_name]);
		if (count($role) < 1) Errors::log(Errors::PERMISSIONS_ERROR, "No entries found for role of name '".$role_name."'");
		return $role[0]['id'];
	}
	
	static public function get_role_name($role_id) {
		$sql = "SELECT name FROM roles WHERE id = ?";
		$role = MYSQL::request_info($sql, 's', [$role_id]);
		if (count($role) < 1) Errors::log(Errors::PERMISSIONS_ERROR, "No entries found for role of id '".$role_id."'");
		return $role[0]['name'];
	}

	static public function check_user_permission($user, $perm_desc) {
		$valid = FALSE;
		$sql = "SELECT role_perm.perm_id FROM user_role 
			JOIN role_perm ON role_perm.role_id = user_role.role_id 
			WHERE user_role.user_id = ?";
		$user_perms = MYSQL::request_info($sql, 'i', [$user->id]);
		foreach ($user_perms as $perm) {
			if (self::get_perm_desc($perm['perm_id']) == $perm_desc) {
				$valid = TRUE;
				break;
			}
		}

		return $valid;
	}

	static public function authorize_user($user, $role_name) {
		self::deauthorize_user($user, $role_name);
		$role_id = self::get_role_id($role_name);
		$sql = "INSERT INTO user_role (user_id, role_id) VALUES (?, ?)";
		MYSQL::insert($sql, 'ii', [$user->id, $role_id]);
	}

	static public function deauthorize_user($user, $role_name) {
		$role_id = self::get_role_id($role_name);
		$sql = "DELETE FROM user_role WHERE user_id = ? AND role_id = ?";
		MYSQL::delete($sql, 'ii', [$user->id, $role_id]);
	}

	static public function revoke_user($user) {
		$sql = "DELETE FROM user_role WHERE user_id = ?";
		MYSQL::delete($sql, 'i', [$user->id]);
	}

	static public function associate_perm($role_name, $perm_desc) {
		self::deassociate_perm($role_name, $perm_desc);
		$role_id = self::get_role_id($role_name);
		$perm_id = self::get_perm_id($perm_desc);
		$sql = "INSERT INTO role_perm (perm_id, role_id) VALUES (?, ?)";
		MYSQL::insert($sql, 'ii', [$perm_id, $role_id]);
	}

	static public function deassociate_perm($role_name, $perm_desc) {
		$role_id = self::get_role_id($role_name);
		$perm_id = self::get_perm_id($perm_desc);
		$sql = "DELETE FROM role_perm WHERE perm_id = ? AND role_id = ?";
		MYSQL::delete($sql, 'ii', [$perm_id, $role_id]);
	}
}

function GENERATE_PERMISSION_TABLES() {
	$sql = "
	CREATE TABLE roles (
	id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, 
	name VARCHAR(50) NOT NULL, 
	PRIMARY KEY (id))";
	MYSQL::create($sql);

	$sql = "
	CREATE TABLE perms (
	id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, 
	description VARCHAR(50) NOT NULL, 
	PRIMARY KEY (id))";
	MYSQL::create($sql);

	$sql = "
	CREATE TABLE role_perm (
	role_id INT(10) UNSIGNED NOT NULL, 
	perm_id INT(10) UNSIGNED NOT NULL, 
	FOREIGN KEY (role_id) REFERENCES roles(id) 
	ON DELETE CASCADE ON UPDATE CASCADE, 
	FOREIGN KEY (perm_id) REFERENCES perms(id)
	ON DELETE CASCADE ON UPDATE CASCADE) 
	ENGINE = INNODB";
	MYSQL::create($sql);

	$sql = "
	CREATE TABLE user_role (
	user_id INT(10) UNSIGNED NOT NULL, 
	role_id INT(10) UNSIGNED NOT NULL, 
	FOREIGN KEY (user_id) REFERENCES users(id) 
	ON DELETE CASCADE ON UPDATE CASCADE, 
	FOREIGN KEY (role_id) REFERENCES roles(id) 
	ON DELETE CASCADE ON UPDATE CASCADE) 
	ENGINE = INNODB";
	MYSQL::create($sql);

	$sql = "INSERT INTO roles (name) VALUES (?)";
	MYSQL::insert($sql, 's', ['admin']);
	MYSQL::insert($sql, 's', ['officer']);
	MYSQL::insert($sql, 's', ['veteran']);
	MYSQL::insert($sql, 's', ['member']);
	MYSQL::insert($sql, 's', ['guest']);

	$sql = "INSERT INTO perms (description) VALUES (?)";
	MYSQL::insert($sql, 's', ['authorize users']);
	MYSQL::insert($sql, 's', ['delete users']);
	MYSQL::insert($sql, 's', ['make announcements']);
	MYSQL::insert($sql, 's', ['delete announcements']);
	MYSQL::insert($sql, 's', ['delete images']);
	MYSQL::insert($sql, 's', ['post images']);
	MYSQL::insert($sql, 's', ['delete videos']);
	MYSQL::insert($sql, 's', ['post videos']);
	MYSQL::insert($sql, 's', ['make albums']);
	MYSQL::insert($sql, 's', ['comment']);

	Permissions::associate_perm('admin', 'authorize users');
	Permissions::associate_perm('admin', 'delete users');
	Permissions::associate_perm('admin', 'make announcements');
	Permissions::associate_perm('admin', 'delete announcements');
	Permissions::associate_perm('admin', 'delete images');
	Permissions::associate_perm('admin', 'post images');
	Permissions::associate_perm('admin', 'delete videos');
	Permissions::associate_perm('admin', 'post videos');
	Permissions::associate_perm('admin', 'make albums');
	Permissions::associate_perm('admin', 'comment');

	Permissions::associate_perm('officer', 'make announcements');
	Permissions::associate_perm('officer', 'delete announcements');
	Permissions::associate_perm('officer', 'delete images');
	Permissions::associate_perm('officer', 'post images');
	Permissions::associate_perm('officer', 'delete videos');
	Permissions::associate_perm('officer', 'post videos');
	Permissions::associate_perm('officer', 'make albums');
	Permissions::associate_perm('officer', 'comment');

	Permissions::associate_perm('veteran', 'post images');
	Permissions::associate_perm('veteran', 'post videos');
	Permissions::associate_perm('veteran', 'make albums');
	Permissions::associate_perm('veteran', 'comment');

	Permissions::associate_perm('member', 'comment');
}


?>