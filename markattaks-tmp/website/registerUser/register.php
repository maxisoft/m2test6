<?php

include_once "Database.php";

class Register {

	public $db;
	public $login;
	public $password;
	public $age;
	public $role;
	public $first_name;
	public $last_name;
	public $date_of_birth;
	
	public function __construct(){
		$this->db= new Database();
		$this->login = $_POST['login'];
		$this->password = $_POST['password'];
		$this->role = $_POST['role'];
		$this->first_name = $_POST['first_name'];
		$this->last_name = $_POST['last_name'];
		$this->date_of_birth = $_POST['date_of_birth'];
		$this->Add_User();
	}
	public function Add_User(){
		$this->db = new Database();
		$this->db->query("INSERT INTO `user` (`login`,`password`,`role`,`first_name`,`last_name`,`date_of_birth`) 
				         VALUES('$this->login','$this->password','$this->role','$this->first_name','$this->last_name',
						 '$this->date_of_birth')");
		$this->db->execute();
	}
}
$register = new Register();