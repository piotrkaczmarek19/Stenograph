<?php

# If request comes from submitting form, proceed to uploading article
if (isset($_POST["new_article_form"]))
{
	include_once "classes/Article.php";
	include_once "classes/Database.php";

	$db = new Database();
	$db_conn = $db->getConn();

	$article = New Article($db_conn);
	$title = $_POST["new_article_form"]["title"];
	$content = $_POST["new_article_form"]["content"];
	$article->setTitle(strip_tags($title));
	$article->setContent(strip_tags($content));

	if ($article->create())
	{
		$_POST["success"] = 1;
	}
	else
	{
		$_POST["success"] = 0;		
	}
}
