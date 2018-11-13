<?php

$lang_tags = array
(
	'text_description' => "Description:",
	'text_syntax' => "Syntax:",
	'text_example' => "Example:",
	'text_result' => "Result:",
	'text_remarks' => "Remarks:",
	'head_tags' => "Tags",
	'text_tags' => "Tags",
	'text_bb_tags_note' => "The <b>".$SITENAME."</b> forums supports a number of <i>BB tags</i> which you can embed to modify how your posts are displayed.",
	'submit_test_this_code' => "Test&nbsp;this&nbsp;code!",
	'text_bold' => "Bold",
	'text_bold_description' => "Makes the enclosed text bold.",
	'text_bold_syntax' => "[b]<i>Text</i>[/b]",
	'text_bold_example' => "[b]This is bold text.[/b]",
	'text_italic' => "Italic",
	'text_italic_description' => "Makes the enclosed text italic.",
	'text_italic_syntax' => "[i]<i>Text</i>[/i]",
	'text_italic_example' => "[i]This is italic text.[/i]",
	'text_underline' => "Underline",
	'text_underline_description' => "Makes the enclosed text underlined.",
	'text_underline_syntax' => "[u]<i>Text</i>[/u]",
	'text_underline_example' => "[u]This is underlined text.[/u]",
	'text_color_one' => "Color (alt. 1)",
	'text_color_one_description' => "Changes the color of the enclosed text.",
	'text_color_one_syntax' => "[color=<i>Color</i>]<i>Text</i>[/color]",
	'text_color_one_example' => "[color=blue]This is blue text.[/color]",
	'text_color_one_remarks' => "What colors are valid depends on the browser. If you use the basic colors (red, green, blue, yellow, pink etc) you should be safe.",
	'text_color_two' => "Color (alt. 2)",
	'text_color_two_description' => "Changes the color of the enclosed text.",
	'text_color_two_syntax' => "[color=#<i>RGB</i>]<i>Text</i>[/color]",
	'text_color_two_example' => "[color=#0000ff]This is blue text.[/color]",
	'text_color_two_remarks' => "<i>RGB</i> must be a six digit hexadecimal number.",
	'text_size' => "Size",
	'text_size_description' => "Sets the size of the enclosed text.",
	'text_size_syntax' => "[size=<i>n</i>]<i>text</i>[/size]",
	'text_size_example' => "[size=4]This is size 4.[/size]",
	'text_size_remarks' => "<i>n</i> must be an integer in the range 1 (smallest) to 7 (biggest). The default size is 2.",
	'text_font' => "Font",
	'text_font_description' => "Sets the type-face (font) for the enclosed text.",
	'text_font_syntax' => "[font=<i>Font</i>]<i>Text</i>[/font]",
	'text_font_example' => "[font=Impact]Hello world![/font]",
	'text_font_remarks' => "You specify alternative fonts by separating them with a comma.",
	'text_hyperlink_one' => "Hyperlink (alt. 1)",
	'text_hyperlink_one_description' => "Inserts a hyperlink.",
	'text_hyperlink_one_syntax' => "[url]<i>URL</i>[/url]",
	'text_hyperlink_one_example' => "[url]http://".$BASEURL."[/url]",
	'text_hyperlink_one_remarks' => "This tag is superfluous; all URLs are automatically hyperlinked.",
	'text_hyperlink_two' => "Hyperlink (alt. 2)",
	'text_hyperlink_two_description' => "Inserts a hyperlink.",
	'text_hyperlink_two_syntax' => "[url=<i>URL</i>]<i>Link text</i>[/url]",
	'text_hyperlink_two_example' => "[url=http://".$BASEURL."]".$SITENAME."[/url]",
	'text_hyperlink_two_remarks' => "You do not have to use this tag unless you want to set the link text; all URLs are automatically hyperlinked.",
	'text_image_one' => "Image (alt. 1)",
	'text_image_one_description' => "Inserts a picture.",
	'text_image_one_syntax' => "[img=<i>URL</i>]",
	'text_image_one_example' => "[img=https://$BASEURL/pic/nexus.png]",
	'text_image_one_remarks' => "The URL must end with <b>.gif</b>, <b>.jpg</b>, <b>.jpeg or <b>.png</b>.",
	'text_image_two' => "Image (alt. 2)",
	'text_image_two_description' => "Inserts a picture.",
	'text_image_two_syntax' => "[img]<i>URL</i>[/img]",
	'text_image_two_example' => "[img]https://$BASEURL/pic/nexus.png[/img]",
	'text_image_two_remarks' => "The URL must end with <b>.gif</b>, <b>.jpg</b>, <b>.jpeg or <b>.png</b>.",
	'text_quote_one' => "Quote (alt. 1)",
	'text_quote_one_description' => "Inserts a quote.",
	'text_quote_one_syntax' => "[quote]<i>Quoted text</i>[/quote]",
	'text_quote_one_example' => "[quote]I love ".$SITENAME.".[/quote]",
	'text_quote_two' => "Quote (alt. 2)",
	'text_quote_two_description' => "Inserts a quote.",
	'text_quote_two_syntax' => "[quote=<i>Author</i>]<i>Quoted text</i>[/quote]",
	'text_quote_two_example' => "[quote=".$CURUSER['username']."]I love ".$SITENAME.".[/quote]",
	'text_list' => "List",
	'text_description' => "Inserts a list item.",
	'text_list_syntax' => "[*]<i>Text</i>",
	'text_list_example' => "[*] This is item 1\n[*] This is item 2",
	'text_preformat' => "Preformat",
	'text_preformat_description' => "Preformatted (monospace) text. Does not wrap automatically.",
	'text_preformat_syntax' => "[pre]<i>Text</i>[/pre]",
	'text_preformat_example' => "[pre]This is preformatted text.[/pre]",
	'text_code' => "Code",
	'text_code_description' => "Display text in decorated format.",
	'text_code_syntax' => "[code]Text[/code]",
	'text_code_example' => "[code]This is code[/code]",
	'text_you' => "[you]",
	'text_you_description' => "Display the username of whoever viewing this",
	'text_you_syntax' => "[you]",
	'text_you_example' => "I know you are reading this, [you]",
	'text_you_remarks' => "Useful for making tricks",
	'text_site' => "[site]",
	'text_site_description' => "Display the site name",
	'text_site_syntax' => "[site]",
	'text_site_example' => "You are visiting [site] now",
	'text_siteurl' => "[siteurl]",
	'text_siteurl_description' => "Display the url of this site",
	'text_siteurl_syntax' => "[siteurl]",
	'text_siteurl_example' => "The url of [site] is [siteurl]",
	'text_flash' => "Flash (alt. 1)",
	'text_flash_description' => "Insert flash in webpages at defined width and height",
	'text_flash_syntax' => "[flash,width,height]Flash URL[/flash]",
	'text_flash_example' => "[flash,500,300]https://$BASEURL/flash.demo.swf[/flash]",
	'text_flash_two' => "Flash (alt. 2)",
	'text_flash_two_description' => "Insert flash in webpages at default width and height (500 * 300)",
	'text_flash_two_syntax' => "[flash]Flash URL[/flash]",
	'text_flash_two_example' => "[flash]https://$BASEURL/flash.demo.swf[/flash]",
	'text_flv_one' => "Flash video (alt.1)",
	'text_flv_one_description' => "Insert flash video in webpages at defined width and height",
	'text_flv_one_syntax' => "[flv,width,height]Flash video URL[/flv]",
	'text_flv_one_example' => "[flv,320,240]https://$BASEURL/flash.video.demo.flv[/flv]",
	'text_flv_two' => "Flash video (alt.2)",
	'text_flv_two_description' => "Insert flash video in webpages at default width and height (320 * 240)",
	'text_flv_two_syntax' => "[flv]Flash video URL[/flv]",
	'text_flv_two_example' => "[flv]https://$BASEURL/flash.video.demo.flv[/flv]",
	'text_youtube' => "YouTube",
	'text_youtube_description' => "Insert YouTube online video in webpages",
	'text_youtube_syntax' => "[youtube]Video URL on YouTube[/youtube]",
	'text_youtube_example' => "[youtube]http://www.youtube.com/watch?v=EsWKVcZ88Jw[/youtube]",
	'text_youku' => "YouKu",
	'text_youku_description' => "Insert YouKu online video in webpages",
	'text_youku_syntax' => "[youku]Video URL on YouKu[/youku]",
	'text_youku_example' => "[youku]http://player.youku.com/player.php/sid/XMzM1MDExODg=/v.swf[/youku]",
	'text_tudou' => "TuDou",
	'text_tudou_description' => "Insert TuDou online video in webpages",
	'text_tudou_syntax' => "[tudou]Video URL on TuDou[/tudou]",
	'text_tudou_example' => "[tudou]http://www.tudou.com/v/1jaI4LNa7sk[/tudou]",
	'text_ninety_eight_image' => "CC98 Image",
	'text_ninety_eight_image_description' => "Display image hosted at CC98 forum",
	'text_ninety_eight_image_syntax' => " [98img=[auto|number]]image file[/98img]",
	'text_ninety_eight_image_example' => "[98img=150]uploadfile/2008/10/30/2362924185.png[/98img]",
	'text_ninety_eight_image_remarks' => "CC98 is a forum at Zhejiang University",
);

?>
