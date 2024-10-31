<?php
/*
Plugin Name: Quicktagzmilies
Version: 2.0
Description: This space intentionally left blank
Plugin URI: http://www.zfen.de/webapplications/quicktagzmilies/
Author: Zfen
Author URI: http://www.zfen.de/
	
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation in the Version 2.
	
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/


//- Function quicktagzmilies used in comments.php
function quicktagzmilies($standalone = false) {
	global $qtz_dca;
	if(!defined('QTZ_BAR_SET') && (!$standalone || !$qtz_dca['options']['standalone'])) {
		create_qtz_quicktagbar();
		qtz_build_zmilie_images();
		define('QTZ_BAR_SET', true);
		return;
	}
	elseif(!defined('QTZ_BAR_SET') && $standalone == 'quicktag_bar' && $qtz_dca['options']['standalone']) {
		create_qtz_quicktagbar();
		return;
	}		
	elseif(!defined('QTZ_BAR_SET') && $standalone == 'smiley_bar' && $qtz_dca['options']['standalone']) {
		qtz_build_zmilie_images();
		return;
	}		
}


///- Init
add_action('init', 'qtz_init');
function qtz_init () {
	global $qtz_dca;
	
	//- Directories
	$qtz_dca['dir']['srv_zmilies'] 		= TEMPLATEPATH. '/images/zmilies';
	$qtz_dca['dir']['www_zmilies'] 		= get_bloginfo('template_url'). '/images/zmilies';
	$qtz_dca['dir']['www_wp_smilies'] = get_option('siteurl'). '/wp-includes/images/smilies';
	$qtz_dca['dir']['www_qtz_plugin'] = get_option('siteurl'). '/wp-content/plugins/quicktagzmilies';
	
	// Get options. If not set, add them to config table
	if (!get_option('plugin_quicktagzmilies')) {
		qtz_activate_plugin ();
	}
	elseif (get_option('plugin_quicktagzmilies')) {
		$qtz_dca['options'] = get_option('plugin_quicktagzmilies');
		
		// Check for updates 1.0.1 --> 2.0
		if(isset($qtz_dca['options']['usezmilies']) && isset($qtz_dca['options']['bname'])) {
			qtz_update_plugin('2.0');
		}
	}
	$qtz_dca['options']['zmiliesarray'] = unserialize($qtz_dca['options']['zmiliesarray']);
	
	//- If $qtz_dca['options']['usesmiliesbar'] is true, check if WP smilie replacement is activated and deactivate it
	if($qtz_dca['options']['usesmiliesbar']  && get_option('use_smilies')) {
		update_option('use_smilies', 0);
	}
	
	//- Include javascript file
	if( stristr($_SERVER['REQUEST_URI'], 'quicktagzmilies-js.php') ) {
		include(dirname(__FILE__). '/quicktagzmilies-js.php');
		die(); exit;
	}	
	
	//- If is_admin() 
	if(is_admin()) {
		if(qtz_page_now('edit') || qtz_page_now('options')) {
			add_action('admin_footer', 'qtz_add_javascript');
		}
		include(dirname(__FILE__). '/quicktagzmilies-admin.php');
	}
	//- Else if !is_admin() 
	else {
		add_action('wp_footer', 'qtz_add_javascript');
	}
}


//- Javascript 
function qtz_add_javascript() {
	global $qtz_dca;
	echo "\r\n". '<!-- Quicktagzmilies plugin: http://www.zfen.de/webapplications/quicktagzmilies/ -->'. "\r\n";
	echo '<script type="text/javascript" src="'. $qtz_dca['dir']['www_qtz_plugin']. '/quicktagzmilies-js.php"></script>'. "\r\n\r\n";
}


//- Make zmilie array unique
function qtz_make_zmilie_array_unique($zmilies_array) {
	$zmilies_array = array_flip($zmilies_array);
	array_unique($zmilies_array);
	$zmilies_array = array_flip($zmilies_array);	
	return $zmilies_array;	
}


//- Build Zmilie images
function qtz_build_zmilie_images($dirname = '', $zmilies_array = '') {
	global $qtz_dca;
	if($zmilies_array == '' && $dirname != 'wordpress') {
		$zmilies_array = $qtz_dca['options']['zmiliesarray'];
	}
	elseif($zmilies_array == '' && $dirname == 'wordpress') {
		$zmilies_array = $qtz_dca['wp_zmilies'];
	}
	
	if($dirname != '' && $dirname != 'wordpress') {
		$dirname = $qtz_dca['dir']['www_zmilies']. '/'. $dirname;
	}
	elseif($dirname != '' && $dirname == 'wordpress') {
		$dirname = $qtz_dca['dir']['www_wp_smilies'];
	}
	// clean the current zmilie
	elseif($dirname == '') {
		if($qtz_dca['options']['packagename'] == 'wordpress') {
			$dirname = $qtz_dca['dir']['www_wp_smilies'];
		}
		else {
			$dirname = $qtz_dca['dir']['www_zmilies']. '/'. $qtz_dca['options']['packagename'];
		}
	}
	// clean the current zmilie array
	$zmilies_array = qtz_make_zmilie_array_unique($zmilies_array);
						
	$zmilie_image_string = '';
	if(qtz_page_now('options') || qtz_page_now('edit')) {
		foreach($zmilies_array as $key => $val) {		
			// Options page
			if(qtz_page_now('options')) {				
				$zmilie_image_string .= '<img class="wp-smiley" src="'. $dirname. '/'. $val.'" alt="'. $key. '" title="'. $key. '" /> ';
			}	
			// Edit pages
			if(qtz_page_now('edit')) {				
				$zmilie_image_string .= '<img class="wp-smiley" src="'. $dirname. '/'. $val. '" alt="'. $key. '" title="'. $key. '" onclick="javascript: qtz_insert_admin_smileys(\' '. $key. ' \', \'content\');" style="cursor: pointer;"/> ';
			}
		}
	}
	// Frontend
	elseif(!is_admin()) {				
		$zmilie_image_string .= '<div id="qtz_zmilies_toggle_wrapper" style="display: none;">'. stripslashes($qtz_dca['options']['zmilies_before']);	
		foreach($zmilies_array as $key => $val) {		
		
			$zmilie_image_string .= '<img class="wp-smiley" style="cursor: pointer;" onclick="javascript: qtz_code(\''. $key. '\', \'comment\');" src="'. $dirname. '/'. $val.'" alt="'. $key. '" title="'. $key. '" /> ';	
		}
		$zmilie_image_string .= stripslashes($qtz_dca['options']['zmilies_after']). '</div>';
	}
	echo $zmilie_image_string;
}


// Create quicktagbar
function create_qtz_quicktagbar() {
	global $qtz_dca;
	echo "\r\n\r\n". stripslashes($qtz_dca['options']['tag_before']). "\r\n";	
	echo '<noscript>'; printf(__('You can use these tags: %s'), allowed_tags()); echo "<br /></noscript>\r\n";	
  echo '<script type="text/javascript">document.write(\'';
	echo '<a href="#" id="qtz_button_bold" title="'. $qtz_dca['options']['bold_title']. '">'. stripslashes($qtz_dca['options']['bold_label']). '</a>';
	
	echo $qtz_dca['options']['tag_between'];	
	echo '<a href="#" id="qtz_button_italic" title="'. $qtz_dca['options']['italic_title']. '">'. stripslashes($qtz_dca['options']['italic_label']). '</a>';	
	
	echo $qtz_dca['options']['tag_between'];	
	echo '<a href="#" id="qtz_button_underline" title="'. $qtz_dca['options']['underline_title']. '">'. stripslashes($qtz_dca['options']['underline_label']). '</a>';	

	echo $qtz_dca['options']['tag_between'];	
	echo '<a href="#" id="qtz_button_strike" title="'. $qtz_dca['options']['strike_title']. '">'. stripslashes($qtz_dca['options']['strike_label']). '</a>';	
	
	echo $qtz_dca['options']['tag_between'];	
	echo '<a href="#" id="qtz_button_link" title="'. $qtz_dca['options']['url_title']. '">'. stripslashes($qtz_dca['options']['url_label']). '</a>';	
	
	echo $qtz_dca['options']['tag_between'];	
	echo '<a href="#" id="qtz_button_quote" title="'. $qtz_dca['options']['quote_title']. '">'. stripslashes($qtz_dca['options']['quote_label']). '</a>';	

	echo $qtz_dca['options']['tag_between'];	
	echo '<a href="#" id="qtz_button_up" title="'. $qtz_dca['options']['resize_title']. '">'. stripslashes($qtz_dca['options']['resize_label']). '</a>';	

	echo $qtz_dca['options']['tag_between'];	
	echo '<a href="#" id="qtz_button_dwn" title="'. $qtz_dca['options']['downsize_title']. '">'. stripslashes($qtz_dca['options']['downsize_label']). '</a>';
	
	if($qtz_dca['options']['usesmiliesbar']) {
		echo $qtz_dca['options']['tag_between'];	
		echo '<a href="#" id="qtz_button_zmilies" title="'. $qtz_dca['options']['zmilies_title_s']. '">'. $qtz_dca['options']['zmilies_label']. '</a>';
	}
	
	echo '\'); </script>';
	echo stripslashes($qtz_dca['options']['tag_after']). "\r\n";	
}


//- Replace smileys
add_filter('the_content', qtz_replace_smileys); 
add_filter('comment_text', qtz_replace_smileys);
add_filter('recent_comment_text', qtz_replace_smileys);			
function qtz_replace_smileys($text)	{
	global $qtz_dca;
	if($qtz_dca['options']['packagename'] == 'wordpress') {
		$dirname = $qtz_dca['dir']['www_wp_smilies'];
	}
	else {
		$dirname = $qtz_dca['dir']['www_zmilies']. '/'. $qtz_dca['options']['packagename'];
	}
	foreach ($qtz_dca['options']['zmiliesarray'] as $title => $imgsrc) {
		$zmilies_S[] = '/(\s|^)'.preg_quote($title, '/').'(\s|$)/';
		$zmilies_masked = htmlspecialchars(trim($title), ENT_QUOTES);
		$zmilies_R[] = ' <img class="wp-smiley" src="'. $dirname. '/'. $imgsrc. '"  alt="'. $zmilies_masked. '" title="'. $zmilies_masked. '"/> ';
	}
	// HTML loop taken from texturize function, could possible be consolidated
	$textarr = preg_split("/(<.*>)/U", $text, -1, PREG_SPLIT_DELIM_CAPTURE); // capture the tags as well as in between
	$stop = count($textarr);// loop stuff
	for ($i = 0; $i < $stop; $i++) {
		$content = $textarr[$i];
		if ((strlen($content) > 0) && ('<' != $content{0})) // If it's not a tag
		{ 
			$content = preg_replace($zmilies_S, $zmilies_R, $content);
		}
		$output .= $content;
	}
	return $output;
}


//- Pagenow function
function qtz_page_now($area) {
	global $pagenow;
	$editpages[] = 'post.php';
	$editpages[] = 'page.php';
	$editpages[] = 'post-new.php';
	$editpages[] = 'page-new.php';
	$optionspage = 'options-general.php';	
	switch ($area) {
    case 'edit':
			$output = in_array($pagenow, $editpages);
		break;
		case 'options':
			$output = strstr($optionspage, $pagenow);
		break;
		default:
			$output = false;		
	}
	return $output;
}


//- Activate plugin
register_activation_hook( __FILE__, 'qtz_activate_plugin' );
function qtz_activate_plugin ($action = '') {
	global $qtz_dca;
		
	// Get zmiliepackage array
	if($action == 'update' && $_POST['packagename'] != 'wordpress') {
		include($qtz_dca['dir']['srv_zmilies']. '/'. $_POST['packagename']. '/'. 'zmiliepackage.php');
		$zmiliesarray = $zmilies_array;
	}
	elseif($action == 'update' && $_POST['packagename'] == 'wordpress') {
		$zmiliesarray = $qtz_dca['wp_zmilies'];
	}
	$qtz_default_options = array (
		'usesmiliesbar'		=> ($action != 'update')		? 1																				: $_POST['usesmiliesbar'],			
		'standalone'			=> ($action != 'update')		? 0																				: $_POST['standalone'],
		'packagename'			=> ($action != 'update')		? 'wordpress'															: $_POST['packagename'],
		'zmiliesarray'		=> ($action != 'update')		? serialize($qtz_dca['wp_zmilies'])				: serialize($zmiliesarray),
		'zmilies_before'	=> ($action != 'update')		? '<p id=\"qtz_zmilies_wrapper\">'				: htmlspecialchars_decode($_POST['zmilies_before']),
		'zmilies_after'		=> ($action != 'update')		? '<\/p>'																	: htmlspecialchars_decode($_POST['zmilies_after']),
		'tag_before'			=> ($action != 'update')		? '<p id=\"qtz_tagbar_wrapper\"><small>' 	: htmlspecialchars_decode($_POST['tag_before']),
		'tag_between'			=> ($action != 'update')		? '&nbsp;&nbsp;|&nbsp;&nbsp;'							: htmlspecialchars_decode($_POST['tag_between']),
		'tag_after'				=> ($action != 'update')		? '<\/small><\/p>'												: htmlspecialchars_decode($_POST['tag_after']),
		'bold_label'			=> ($action != 'update')		? 'Bold'																	: strip_tags($_POST['bold_label'], '<strong>'),
		'bold_title'			=> ($action != 'update')		? 'Bold type'															: strip_tags($_POST['bold_title']),
		'italic_label'		=> ($action != 'update')		? 'Italic'																: strip_tags($_POST['italic_label'], '<em>'),
		'italic_title'		=> ($action != 'update')		? 'Italic type'														: strip_tags($_POST['italic_title']),
		'underline_label'	=> ($action != 'update')		? 'Underline'															: strip_tags($_POST['underline_label'], '<u>'),
		'underline_title'	=> ($action != 'update')		? 'Underline'															: strip_tags($_POST['underline_title']),
		'strike_label'		=> ($action != 'update')		? 'Cross out'															: strip_tags($_POST['strike_label'], '<strike>'),
		'strike_title'		=> ($action != 'update')		? 'Cross out'															: strip_tags($_POST['strike_title']),
		'url_label'				=> ($action != 'update')		? 'URL'																		: strip_tags($_POST['url_label'], ''),
		'url_title'				=> ($action != 'update')		? 'Insert link'														: strip_tags($_POST['url_title']),
		'url_alert'				=> ($action != 'update')		? 'Link (with http://)'										: strip_tags($_POST['url_alert']),
		'quote_label'			=> ($action != 'update')		? 'Quote'																	: strip_tags($_POST['quote_label'], ''),
		'quote_title'			=> ($action != 'update')		? 'Quote'																	: strip_tags($_POST['quote_title']),
		'quote_alert'			=> ($action != 'update')		? 'Name of author (optional)'							: strip_tags($_POST['quote_alert']),
		'resize_label'		=> ($action != 'update')		? '+'																			: strip_tags($_POST['resize_label'], ''),
		'resize_title'		=> ($action != 'update')		? 'Resize commentfield'										: strip_tags($_POST['resize_title']),
		'downsize_label'	=> ($action != 'update')		? '-'																			: strip_tags($_POST['downsize_label'], ''),
		'downsize_title'	=> ($action != 'update')		? 'Downsize commentfield'									: strip_tags($_POST['downsize_title']),
		'zmilies_label'		=> ($action != 'update')		? 'Zmilies'																: strip_tags($_POST['zmilies_label'], ''),
		'zmilies_title_s'	=> ($action != 'update')		? 'Show Zmilies'													: strip_tags($_POST['zmilies_title_s']),
		'zmilies_title_h'	=> ($action != 'update')		? 'Hide Zmilies'													: strip_tags($_POST['zmilies_title_h'])
	);	
	// Add qtz default options to config table if not set
	if (!get_option('plugin_quicktagzmilies') && $action == '') {
		add_option('plugin_quicktagzmilies', $qtz_default_options);
	}
	elseif(get_option('plugin_quicktagzmilies') && ($action == 'reset' || $action == 'update')) {
		update_option('plugin_quicktagzmilies', $qtz_default_options);
		$qtz_dca['options'] = get_option('plugin_quicktagzmilies');
		$qtz_dca['options']['zmiliesarray'] = unserialize($qtz_dca['options']['zmiliesarray']);
	}
	// Deactivate WP smilie replacement
	if(get_option('use_smilies')) {
		update_option('use_smilies', 0);
	}
}


//- Update function
function qtz_update_plugin($update_to) {
	global $qtz_dca;
	if($update_to == '2.0') {		
		$new_config['usesmiliesbar']		= $qtz_dca['options']['usezmilies'];
		$new_config['standalone']				= ($qtz_dca['options']['standalone'] == '') ? 0 : $qtz_dca['options']['standalone'];
		$new_config['packagename']			= $new_config;
		$new_config['zmilies_before']		= $qtz_dca['options']['zmilies_before'];
		$new_config['zmilies_after']		= $qtz_dca['options']['zmilies_after'];
		$new_config['tag_before']				= $qtz_dca['options']['tag_before'];
		$new_config['tag_between']			= $qtz_dca['options']['tag_between'];
		$new_config['tag_after']				= $qtz_dca['options']['tag_after'];
		$new_config['bold_label']				= $qtz_dca['options']['bname'];
		$new_config['bold_title']				= $qtz_dca['options']['btitle'];
		$new_config['italic_label']			= $qtz_dca['options']['iname'];
		$new_config['italic_title']			= $qtz_dca['options']['ititle'];
		$new_config['underline_label']	= $qtz_dca['options']['uname'];
		$new_config['underline_title']	= $qtz_dca['options']['utitle'];
		$new_config['strike_label']			= $qtz_dca['options']['sname'];
		$new_config['strike_title']			= $qtz_dca['options']['stitle'];
		$new_config['url_label']				= $qtz_dca['options']['aname'];
		$new_config['url_title']				= $qtz_dca['options']['atitle'];
		$new_config['url_alert']				= $qtz_dca['options']['aalert1'];
		$new_config['quote_label']			= $qtz_dca['options']['qname'];
		$new_config['quote_title']			= $qtz_dca['options']['qtitle'];
		$new_config['quote_alert']			= $qtz_dca['options']['qalert1'];
		$new_config['resize_label']			= $qtz_dca['options']['upname'];
		$new_config['resize_title']			= $qtz_dca['options']['uptitle'];
		$new_config['downsize_label']		= $qtz_dca['options']['dwnname'];
		$new_config['downsize_title']		= $qtz_dca['options']['dwntitle'];
		$new_config['zmilies_label']		= $qtz_dca['options']['zname'];
		$new_config['zmilies_title_s']	= $qtz_dca['options']['ztitle1'];
		$new_config['zmilies_title_h']	= $qtz_dca['options']['ztitle2'];
		if($qtz_dca['options']['packagename'] != 'wordpress') {
			include($qtz_dca['dir']['srv_zmilies']. '/'. $qtz_dca['options']['packagename']. '/'. 'zmiliepackage.php');
			$new_config['zmiliesarray']	= serialize($zmilies_array);
		}
		elseif($qtz_dca['options']['packagename'] == 'wordpress') {
			$new_config['zmiliesarray']	= serialize($qtz_dca['wp_zmilies']);
		}		
		update_option('plugin_quicktagzmilies', $new_config);
		$qtz_dca['options'] = get_option('plugin_quicktagzmilies');
	}
}


//- The WordPress smiley array
//- Copied from wp-includes/functions.php. It is only available from there
//- if WordPress option use_smilies is set to true. This plugin updates this option
//- to false. Don't wan't to jump around, so use this array instead.
//- WordPress smileys are still located in wp-includes/images/smilies.
$qtz_dca['wp_zmilies'] = array (
	':-)'				=> 'icon_smile.gif',
	':)'				=> 'icon_smile.gif',
	':smile:' 	=> 'icon_smile.gif',
	':-D'				=> 'icon_biggrin.gif',
	':D'				=> 'icon_biggrin.gif',
	':grin:'		=> 'icon_biggrin.gif',
	':lol:'			=> 'icon_lol.gif',
	':-('				=> 'icon_sad.gif',
	':('				=> 'icon_sad.gif',
	':sad:'			=> 'icon_sad.gif',
	':cry:'			=> 'icon_cry.gif',
	':-P'				=> 'icon_razz.gif',
	':P'				=> 'icon_razz.gif',
	':razz:'		=> 'icon_razz.gif',
	':-x'				=> 'icon_mad.gif',
	':x'				=> 'icon_mad.gif',
	':mad:'			=> 'icon_mad.gif',
	';-)'				=> 'icon_wink.gif',
	';)'				=> 'icon_wink.gif',
	':wink:'		=> 'icon_wink.gif',
	':-|'				=> 'icon_neutral.gif',
	':|'				=> 'icon_neutral.gif',
	':evil:'		=> 'icon_evil.gif',
	':neutral:' => 'icon_neutral.gif',
	'8-)'				=> 'icon_cool.gif',
	'8)'				=> 'icon_cool.gif',
	':cool:'		=> 'icon_cool.gif',
	'8-O'				=> 'icon_eek.gif',
	'8O'				=> 'icon_eek.gif',
	':shock:' 	=> 'icon_eek.gif',
	':oops:'		 => 'icon_redface.gif',
	':-?'				=> 'icon_confused.gif',
	':?'				=> 'icon_confused.gif',
	':???:' 		=> 'icon_confused.gif',
	':-o'				=> 'icon_surprised.gif',
	':o'				=> 'icon_surprised.gif',
	':eek:'			=> 'icon_surprised.gif',
	':twisted:' => 'icon_twisted.gif',
	':mrgreen:' => 'icon_mrgreen.gif',
	':arrow:' 	=> 'icon_arrow.gif',
	':roll:'		=> 'icon_rolleyes.gif',
	':idea:'		=> 'icon_idea.gif',
	':!:'				=> 'icon_exclaim.gif',
	':?:'				=> 'icon_question.gif'
);

/*------------------------------
Just a debug function
------------------------------*/
function qtz_debug($array) {
	echo '<pre style="padding: 5px; font-size: 13px; text-align: left; background: #fff;">';
	print_r($array);	
	echo '</pre>';
}
?>