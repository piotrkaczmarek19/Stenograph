<?php

class Database
{
	public $host = "localhost";
	public $dbname = "stenograph";
	public $user = "root";
	public $password = "";
	public $db_conn;

	public function getConn()
	{
		$this->db_conn = null;
		try {
			$this->db_conn = new PDO("mysql:host=". $this->host ."; dbname=". $this->dbname ."; charset=utf8", $this->user, $this->password);
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
		}
		return $this->db_conn;
	}
}