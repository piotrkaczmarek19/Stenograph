<?php
ini_set('display_errors', 1);
include_once "classes/Article.php";
include_once "classes/Image.php";
include_once "classes/Database.php";

# accessing db for article and available images
$id = $_GET["aid"];

$db = new Database();
$db_conn = $db->getConn();

$art_repository = new Article($db_conn);
$curr_article = $art_repository->find($id);
$content_html = $art_repository->formatContent($curr_article['content']);
include_once "header.php";
?>

<div class="container display">
    <div class="article">
        <h1><?= $curr_article['title']; ?></h1>
        <?= $content_html; ?>
    </div>
</div>

<?php
include_once "footer.php";
?>