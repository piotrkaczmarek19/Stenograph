<?php

class Image
{
	public $db_conn;
	public $table_name = "image";

	private $id;
	private $name;
	private $path;

	public function __construct($db)
	{
		$this->db_conn = $db;
	}
	public function setName($name)
	{
		$this->name = $name;
	}
	public function getName()
	{
		return $this->name;
	}
	public function setPath($path)
	{
		$this->path = $path;
	}
	public function getPath()
	{
		return $this->path;
	}
	public function create()
	{
		$sql = "INSERT INTO image SET name = :name, path = :path";
		$req = $this->db_conn->prepare($sql);

		$req->bindParam(":name", $this->name);
		$req->bindParam(":path", $this->path);

		if ($req->execute())
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	public function delete()
	{
		$sql = "DELETE FROM ". $this->table_name ." WHERE id= :id";;
		$rec = $this->db_conn->prepare($sql);

		$rec->bindParam(":id", $this->id);

		if ($req->execute())
		{
			unlink($this->getPath());
			return true;
		}
		else
		{
			return false;
		}
	}
	public function find($id)
	{
		$sql = "SELECT * FROM ". $this->table_name ." WHERE id = :id";

		$req = $this->db_conn->prepare($sql);
		$req->bindParam(":id", $id);
		$req->execute();
		$response = $req->fetch();

		return $response;
	}
	public function getAll()
	{
		$sql = "SELECT * FROM ". $this->table_name;

		$req = $this->db_conn->prepare($sql);
		$req->execute();
		$response = $req->fetchAll();

		return $response;		
	}
}