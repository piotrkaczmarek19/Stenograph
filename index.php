<?php
ini_set('display_errors', 1);
include_once "classes/Database.php";
include_once "classes/Article.php";
include_once "classes/Image.php";

include_once("create.php");

# displaying most recent articles
$db = new Database();
$db_conn = $db->getConn();

$article = new Article($db_conn);
$rct_articles = $article->getKfirst(4);

# displaying available images
$image = new Image($db_conn);
$available_images = $image->getAll();

include_once "header.php";

include_once "content.php";

# If we are being redirected from create article, show flash msge informing user of the status of the article
if (isset($_POST['success']) && $_POST['success'] == 1)
{
	echo "<img class='flash-msge' src='public/images/success.svg'>";
	#reset var to hide flash message on future page loads
	unset($_POST['success']);
}
else if (isset($_POST['success']) && $_POST['success'] == 0)
{
	echo "<img class='flash-msge' src='public/images/failure.svg'>";
	#reset var to hide flash message on future page loads
	unset($_POST['success']);
}	

include_once "footer.php";

