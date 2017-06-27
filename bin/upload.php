<?php
ini_set("display_errors", 1);
if (isset($_FILES))
{
	require "../classes/Database.php";
	include_once "../classes/Image.php";

	$db = new Database();
	$db_conn = $db->getConn();

	$data = $_FILES['file'];
	$fileName = $_FILES['file']['name'];
	$uploadDir = "uploads/";
	$uploadfile = $uploadDir . basename($_FILES['file']['name']);

	# Saving image in db
	$image = new Image($db_conn);
	$image->setName($fileName);
	$image->setPath($uploadfile);

	if ($image->create())
	{
	    if(move_uploaded_file($_FILES['file']['tmp_name'], "../".$uploadfile))
	    {
	        echo $fileName;
	    }	
	}
	else
	{
		echo 0;	
	}
}

