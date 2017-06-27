<?php
ini_set("display_errors", 1);
include_once("classes/Article.php");
include_once "classes/Database.php";

# displaying editable article
$db = new Database();
$db_conn = $db->getConn();

$article = new Article($db_conn);
$id = $_GET["aid"];
$response = $article->find($id);

if (isset($response))
{
	$title = $response["title"];
	$content_raw = $response["content"];

	$content_html = $article->formatContent($content_raw);
}
else
{
	header("Location: index.php");
}

# Saving modifications 
if (isset($_POST["new_article_form"]))
{
	$article = New Article($db);
	$title = $_POST["new_article_form"]["title"];
	$content = $_POST["new_article_form"]["content"];
	$article->setTitle($title);
	$article->setContent($content);

	if ($article->update())
	{
		$_POST["success"] = 1;
	}
	else
	{
		$_POST["success"] = 0;		
	}	
	header("Location: /");
}

include_once("header.php");	
?>

 <div class="admin-main">
    <div class="container">
        <div class="col-md-8 content">
            <h2>Dashboard</h2>
            <div class="toolbar_wrapper">
                <div class="format_button" data-command="bold">bold</div>
                <div class="format_button" data-command="italic">italic</div>
                <button class="add-media-button" id="video">Add video</button>
                <button class="add-media-button" id="image">Add picture</button>
            </div>
            <form name="new_article_form" method="post" action="edit.php">
                <div>
                    <label for="new_article_form_title" class="required">Title</label>

                    <input type="text" id="new_article_form_title" name="new_article_form[title]" required="required" value="<?= $title ?>"/>
                </div>
                <div class="text_zone" onpaste="return false"> 
               		<?= $content_html; ?>
                </div>
                <div style="display: none">
                    <label for="new_article_form_content" class="required">Content</label>

                    <textarea id="new_article_text" name="new_article_form[content]" required="required" id="textarea"></textarea>
                </div>
                <div>
                	<button type="submit" id="new_article_form_postArticle" name="new_article_form[postArticle]">Edit article</button>
                </div>
            </form>
        	<a href="/">Home</a>
            <div class="url-prompt url-prompt-video">
                <p class="exit-button">X</p>
                <form action="" id="url-form-video">
                    <input type="text" id="video-url" placeholder="Enter URL here">
                    <input type="submit">
                </form>
            </div>
            
		    <div class="url-prompt url-prompt-image">
		        <p class="exit-button">X</p>
		        <form id="uploadimage" action="" method="post" enctype="multipart/form-data">
		            <div id="selectImage">
		                <label>Select Your Image</label><br/>
		                <hr id="line">
		                <div id="available_images">
                            <?php foreach($available_images as $img) : ?>
                                <img src="<?= $img['path']; ?>" class="image_miniature" alt="">
                                <input type="hidden" class="image-name" value="<?= $img['name']; ?>">
                            <?php endforeach; ?>
		                </div>
		                <input type="file" name="file" id="file" data-target="upload" />
		                <input type="submit" value="Upload" class="submit" />
		            </div>
		        </form>
		        <button class="add-image hidden">Add image</button>
		    </div>
		</div>
    </div>
</div>


<?php
include_once("footer.php");
?>