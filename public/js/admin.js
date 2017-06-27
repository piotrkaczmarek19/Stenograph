$(document).ready(function(){
    var text = [];
    var pos =[0];
    var $textarea = $("#new_article_text");
    var openingParagraph = "[paragraph]";
    var closingParagraph = "[/paragraph]";
    var newParagraph = false;
    var keycodes = [];
    var lowend = 0;
    var highend = 46;
    var iframe_src = [];
    while(lowend<highend){
        keycodes.push(lowend++);
    }
    keycodes.splice(32,1);
    function getSelectedText() {
        // Workaround for firefox -- window getSelection does not work properly in that browser
        if(navigator.userAgent.indexOf("Firefox") != -1)
        {
            if (window.getSelection) {
                try {
                    var ta = $('textarea').get(0);
                    return ta.value.substring(ta.selectionStart, ta.selectionEnd);
                } catch (e) {
                    console.log('Cant get selection text')
                }
            }
        } else {
            var text = "";
            if (typeof window.getSelection != "undefined") {
                text = window.getSelection().toString();
                textRange = window.getSelection();
            }
            if(text.length>0){
                return text;
            }
        }
    }

    function addTags(text, tag_type) {

        var textarea = $("#new_article_text");


        var openingTag = "["+tag_type+"]";
        var closingTag = "[/"+tag_type+"]";

        startingPoint = textarea[0].selectionStart;
        selectedText = text[0];
        endPoint = textarea[0].selectionEnd;

        first_half = textarea[0].value.slice(0,startingPoint);
        second_half = textarea[0].value.slice(endPoint);
        textarea[0].value = first_half.concat(openingTag,selectedText, closingTag, second_half);

        first_half = null;
        second_half = null
    }

    // Saving selected text in array
    /***$("#new_article_text").on('mouseup', function(){
        text[0] = getSelectedText();
        console.log(text[0])
    });

    // Saves cursor position when user is typing text
    $("#new_article_text").on("keydown", function(e){
        el = $(this).get(0);
        pos[0] = el.selectionStart;
        // No Risk of it being triggered more than once, or triggering when article starts with other than text, as it needs cursor to be in pos 1

        if(pos[0] === 0 && $(this).get(0).value === "" && keycodes.indexOf(e.keyCode) === -1 ){
            $textarea[0].value = openingParagraph + $textarea[0].value;
        }
    });

    // Insert closing tag </p>, but only if preceded by text
    paragraphClosed = false;
    $textarea.on('keydown', function(e){
        if (e.keyCode != 13 && e.keyCode != 8)
        {
            if(paragraphClosed){
                $textarea[0].value = $textarea[0].value + openingParagraph;
            }
            paragraphClosed = false;
            console.log(paragraphClosed);
        }
        console.log($textarea[0].value[pos[0]-2]);
        if(e.keyCode === 13 && paragraphClosed === false){
            paragraphClosed = true;
            $textarea[0].value = $textarea[0].value + closingParagraph;
            pos[0] += 12;
            console.log(paragraphClosed);
        }
    }); ***/

    // activating listener on style buttons for selected text
    $(".style_button").on('click', function(e){
        if(text[0] != undefined){
            tag_type = $(this).attr('id');
            addTags(text, tag_type);
            text[0] = undefined;
        }
    });

    // AJAX call for delete an article
    $('.delete-article-button').on('click', function(e){
        var article_id = $(this).attr('id');
        console.log(article_id)
        $.ajax({
            url: 'bin/delete.php',
            type: 'POST',
            data: {article_id: article_id},
            success: function(data){
                console.log(data);

            },
            error: function(err){
                console.log(err);
            }
        });
        $(this).parent().fadeOut();
    });
    // Handling text edit with content editable
    var $text_zone = document.querySelector(".text_zone");
    var format_button_array = document.querySelectorAll(".format_button");
    var $toolbar_wrapper = document.querySelector(".toolbar_wrapper");
    var colors = [""];
    var $textarea = document.querySelector("#new_article_text");
    var $submit_button = document.querySelector("#new_article_form_postArticle");
    var $link_provider_input = document.querySelector(".link_provider_input");
    $text_zone.contentEditable = true;

    // Section based off https://code.tutsplus.com/tutorials/create-a-wysiwyg-editor-with-the-contenteditable-attribute--cms-25657
    $('.format_button').on("mousedown", function (event)
    {
        // record the current command and choose relevant syntax
        var curr_command = event.target.getAttribute("data-command");
        if (curr_command == "h1" || curr_command == "h2" || curr_command == "p")
        {
            document.execCommand("formatBlock", false, curr_command);               
        }
        if (curr_command == "fontcolor") {
            console.log(curr_command == 'forecolor')
            document.execCommand("forecolor", false, "blue");
        }
        if (curr_command == "bckgrndcolor")
        {
            document.execCommand("backcolor", false, "blue");
        }
        else
        {
            document.execCommand(curr_command, false, null)
        }
    })
    $submit_button.addEventListener("mousedown", function (event)
    {
        // Stopping submit event
        event.preventDefault();

        // extracting url from iframes
        var ytFrames = document.querySelectorAll("iframe");
        for (var i = 0; i < ytFrames; i++)
        {   // Replace iframes by span in place
            var url_addr = document.createElement("span");
            url_addr.innerHTML = iframe_src[i];
            ytFrames[i].parentNode.replaceChild(url_addr, ytFrames[i]);
        }

        // copying value in text editor to textarea while securing tags for database insertion
        var text_value = $text_zone.innerHTML;
        var paragraph_pattern = /\<\/p\>/gi;
        var patterns_array = [new RegExp('class="(.+)"', 'g'), new RegExp('lang="(.+)"', 'g'), new RegExp('style="(.+)"', 'g'), /<p>(.+)<\/p>/gi, /<b>(.+)<\/b>/g, /<i>(.+)<\/i>/g,new RegExp('<iframe (.+)</iframe>', 'g'), /<span>(.+)<\/span>/g , /<br\>/g, /<img src\="(.+\.[a-z]{3})"\>/g, /\s+/g];
        var replace_array = ["", "", "", "[paragraph]$1[/paragraph]" + String.fromCharCode(13, 10), "[bold]$1[/bold]", "[italic]$1[/italic]", " ", "[video]$1[/video]", "", "[image]$1[/image]", " "];
        for (var i = 0; i < patterns_array.length; i++) 
        {
            text_value = text_value.replace(patterns_array[i], replace_array[i]);
        }
        $("#new_article_text").val(text_value);


        // resuming submit event
        //$submit_button.click();
    });
    $submit_button.addEventListener("mouseup", function (event)
    {
        console.log($textarea.innerHTML)
    })    
    // adding listener for url prompt button
    $(".add-media-button").on('click', function(){
        media = $(this).attr('id');
        activateClass = ".url-prompt-"+media;

        $(".url-prompt-"+media).fadeIn('slow');
    });


    // Saving value of url
    // TODO check with regex if correct url pattern
    $("#url-form-video").on('submit', function(e){
        e.preventDefault();
        var $this = $(this);

        // TODO only save the relevant part of the URL (watch=a-z0-9)
        var url = $('#video-url').val();
        if(url != ""){
            el = $(this).parent();
            el.fadeOut()

            //Old 
            //text = $("#new_article_text").get(0).value;

            //$("#new_article_text").get(0).value = text.concat("[video]",url,"[/video]\r")
            var urlReplace = url.replace("watch?v=", "embed/");
            var embed = '<iframe title="YouTube video player" src="'+urlReplace+'">';
            $text_zone.innerHTML += embed;
            // Adding hidden div to extract the src later when submitting the article
            var src_holder = document.createElement('span');
            
            src_holder.innerHTML = urlReplace;
            $text_zone.appendChild(src_holder);
        }
    });

    // adding listener on close button for prompts
    $(".exit-button").on('click', function(){
        el = $(this).parent();
        $(el).hide()
    });

    //upload image prevent default
    /*$(".uploadImageForm").submit(function(e){
        e.preventDefault();
        $('.url-prompt-image').fadeOut();
    });*/

    // adding image to text at current cursor pos
    $('.image_miniature').on('click', function(){
        $(this).toggleClass('image_miniature image_miniature-selected');
        $(this).siblings('img').attr('class', 'image_miniature');
        $(this).parent().siblings('input').toggle();

        $('.add-image').toggleClass('hidden');
    });
    $('.add-image').on('click', function(){
        $(this).parent().fadeOut();
        $imageMiniature = $('.image_miniature-selected');
        $imageName = $imageMiniature.siblings(':hidden');
        imageName = $imageMiniature.attr("src");

        // Inserting image inside content editable
        console.log(imageName)
        $text_zone.innerHTML += "<img src='"+imageName+"'>";

        $imageMiniature.parent().siblings('input').toggle();
        $imageMiniature.toggleClass('image_miniature-selected image_miniature');
        $('.add-image').toggleClass('hidden');
    });

    // AJAX call for image upload
    $('#uploadimage').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: 'bin/upload.php',
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data){

                $text_zone.innerHTML += "<img src='uploads/"+data+"'/>";

                first_half = null;
                second_half = null
            },
            error: function(error){
                console.log(error);
            }
        });
        $(this).parent().fadeOut();
    });
});
