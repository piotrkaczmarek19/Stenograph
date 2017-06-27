<?php
ini_set('display_errors', 1);
if ($_POST)
{
	include_once "../classes/Database.php";
	include_once "../classes/Article.php";

	$db = new Database();
	$db_conn = $db->getConn();

	$id = $_POST["article_id"];
	$article = new Article($db_conn);
	$article->delete($id);
}
