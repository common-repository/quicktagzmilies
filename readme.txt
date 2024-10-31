=== Plugin Name ===
Contributors: zfen
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6271728/
Tags: comments, quicktag, smilies, smileys
Requires at least: 2.9
Tested up to: 2.9.2
Stable tag: 2.0

Quicktagzmilies is a deluxe quicktag and smiley plugin for your WordPress blog's commentarea.

== Description ==

Quicktagzmilies is a deluxe quicktag and smiley plugin for your WordPress blog's commentarea. It offers the possibility to add a quicktag- and (optional) a smileybar to your commentarea. The quicktagbar contains the most important HTML tags (Text links, no buttons) to format a comment. Optional you can show a "hide-/show"-smileybar.

[Download now](http://downloads.wordpress.org/plugin/quicktagzmilies.2.0.zip) | [Get more information](http://www.zfen.de/webapplications/quicktagzmilies/) | [Check out the Demo](http://quicktagzmilies.zfen.de/)

**Some features:**

* Extendable through smiley packs. [Download](http://www.zfen.de/webapplications/quicktagzmilies#smileypacks) or create an own one
* Use different smileys in different themes (e.g. when you use a theme-switcher)
* User-defined labels and tooltips for the quicktagbar
* Quicktagbar is usable with or without the smileybar
* Possibility to seperate the position of quicktag- and smileybar (Standalone mode)
* Easy customization using CSS 
* Find out more on the Quicktagzmilies option page

**Don't buy the pig in a poke ...**

... and check out the [demo on my website](http://quicktagzmilies.zfen.de/).

== Installation ==

**New installation**

* Unzip into your `/wp-content/plugins/` directory. If you're uploading it make sure to upload the top-level folder. **Don't just upload all the php files and put them in "/wp-content/plugins/".**
* Activate the plugin through the plugins menu in WordPress
* Open comments.php and search for "&lt;textarea". Check the id of the HTML tag. It has to be "comment" (id="comment"). This is WordPress standard.
* Paste following PHP code in the line before "`<textarea ...`": `<?php if (function_exists('quicktagzmilies')) { quicktagzmilies(); } ?>`
* Save and upload comment.php. The quicktagbar should now appear in your comment area
* Go to Quicktagzmilies options in your admin panel (*Settings &raquo; Quicktagzmilies*) to customize the plugin

**Update from 1.0.1 to 2.0 (this version)**

* [Download this plugin](http://downloads.wordpress.org/plugin/quicktagzmilies.2.0.zip)
* Delete wp-content/plugins/quicktagzmilies/qtz.js.php, wp-content/plugins/quicktagzmilies/qtz-admin.php and wp-content/plugins/quicktagzmilies/qtz-functions.php
* Upload the files quicktagzmilies/quicktagzmilies.php, quicktagzmilies/quicktagzmilies-admin.php and quicktagzmilies/quicktagzmilies-js.php to wp-content/plugins/quicktagzmilies/
* Reload front- or backend and it should work

**Please check [Quicktagzmilies page](http://www.zfen.de/webapplications/quicktagzmilies) for further information**

== Frequently Asked Questions ==

= The quicktag bar is displayed in my commentarea, but when i click, nothing happens =

1. Check your header.php in your template folder. Search it for `<?php wp_head(); ?>`. If you can't find this, just add `<?php wp_head(); ?>` **before** `</head>`
2. Check your footer.php in your template folder. Search it for `<?php wp_footer(); ?>`. If you can't find this, just add `<?php wp_footer(); ?>` **before** `</body>`
3. If you have followed step 1 to 2 and still nothing happens when you click the quicktags, just [write me an email](http://www.zfen.de/contact/).

= Still huh? = 

If you have any questions, don't hesitate to [contact me](http://www.zfen.de/contact/)

== Screenshots == 

1. Commentarea with WordPress smileys
2. Commentarea with Black and white smileys
3. Commentarea with flauschgift&trade; smileys
4. Commentarea with Nomicons smileys