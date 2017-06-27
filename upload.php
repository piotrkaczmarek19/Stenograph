<?php
ini_set("display_errors", 1);
if ($_POST)
{
	$data = $_FILES['file'];
	$fileName = $_FILEs['file']['name'];
	$uploadDir = "../uploads/";
	$uploadfile = $uploadDir . basename($_FILES['file']['name']);

    if(move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)){
        var_dump("okk");
    }	
}

header("Location: /");