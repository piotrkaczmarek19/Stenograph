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
            <form name="new_article_form" method="post" action="index.php">
                <div>
                    <label for="new_article_form_title" class="required">Title</label>

                    <input type="text" id="new_article_form_title" name="new_article_form[title]" required="required" />
                </div>
                <div class="text_zone" onpaste="return false"><p>New article</p></div>
                <div style="display: none">
                    <label for="new_article_form_content" class="required">Content</label>

                    <textarea id="new_article_text" name="new_article_form[content]" required="required" id="textarea"></textarea>
                </div>
                <div>
                	<button type="submit" id="new_article_form_postArticle" name="new_article_form[postArticle]">Post article</button>
                </div>
            </form>

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
        <div class="col-md-offset-1 col-md-3 sidebar">
            <h2>Recent Articles</h2>
            <?php foreach($rct_articles as $curr_article) : ?>
                <div class="'article-preview">

                    <h3><a href="display.php?aid= <?= $curr_article['id'] ?>"><?= $curr_article["title"] ?></a></h3>
                    <button class="delete-article-button" id="<?= $curr_article['id'] ?>">Delete</button>
                    <a class="edit-article-button" href="edit.php?aid=<?= $curr_article['id'] ?>">Edit</a>
                    <a class="display-article-button" href="display.php?aid=<?= $curr_article['id'] ?>">Display</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
