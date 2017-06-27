<?php

class Article
{
	public $db_conn;
	public $table_name = "article";

	private $id;
	private $title;
	private $content;

	public function __construct($db)
	{
		$this->db_conn = $db;
	}
	public function setTitle($title)
	{
		$this->title = $title;
	}
	public function getTitle()
	{
		return $this->title;
	}
	public function setContent($content)
	{
		$this->content = $content;
	}
	public function getContent()
	{
		return $this->content;
	}
	public function create()
	{
		$sql = "INSERT INTO article SET title = :title, content = :content";
		$req = $this->db_conn->prepare($sql);

		$req->bindParam(":title", $this->title);
		$req->bindParam(":content", $this->content);

		if ($req->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function update()
	{
		$sql = "UPDATE ". $this->table_name ." SET title = :title, content = :content";
		$req = $this->db_conn->prepare($sql);

		$req->bindParam(":title", $this->title);
		$req->bindParam(":content", $this->content);

		if ($req->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function delete($id)
	{
		$sql = "DELETE FROM ". $this->table_name ." WHERE id= :id";

		$req = $this->db_conn->prepare($sql);
		$req->bindParam(':id', $id);

		if ($req->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function getKfirst($k)
	{
		$sql = "SELECT * FROM ". $this->table_name ." LIMIT ".$k;

		$req = $this->db_conn->prepare($sql);
		$req->execute();
		$response = $req->fetchAll();

		return $response;
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
    public function formatContent($content){
        $pattern = ["~\[paragraph\](.+)\[\/paragraph\]~", "~\[image\]([^\[]*)\[/image\]~", "~\[bold\](.+)\[/bold\]~", "~\[italic\](.+)\[/italic\]~", "~\[video\]([^\[]*)\[/video\]~"];
        $replacement = ["<p>$1</p>", "<img src='$1'/>", "<strong>$1</strong>", "<i>$1</i>", "<iframe src='$1' title='YouTube video player'></iframe><span>$1</span>"];
        return preg_replace($pattern, $replacement, $content);
    }
    
}