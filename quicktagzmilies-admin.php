<?php
/* Admin options page for Wordpress Quicktagzmilies Plugin
	
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation in the Version 2.
	
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/


//- Create admin option page
function qtz_create_admin_option_page() {
	global $qtz_dca;		
	
	if ($_GET['reset']) {
		qtz_activate_plugin('reset');
	}	
	elseif ($_GET['updated'] && isset($_POST['qtz_update'])) {
		qtz_activate_plugin('update');
	}
	?>
	
	<style type="text/css">
		a { cursor: pointer; }		
		table#outer { width: 100%; border: 0 none; padding:0; margin:0; }
		table#outer td.left, table#outer td.right { vertical-align:top; }
		table#outer td.left {  padding: 0 10px 0 0; }
		form {padding: 0; }
	</style>
	
	<div class="wrap">
		
		<?php if($_GET['reset']) { ?>
			<div class="updated fade">
				<p><strong>All Quicktagzmilies options have been set to default.</strong></p>
			</div>
		<?php } ?>
		
		<div id="icon-options-general" class="icon32"></div><h2>Quicktagzmilies Options</h2>  
		
		<!-- Begin Content -->
		<table id="outer"><tr><td class="left">    
			
			<p><a href="http://www.zfen.de/webapplications/quicktagzmilies/">Plugin Website</a> | <a href="http://www.zfen.de/webapplications/quicktagzmilies#Smileypacks">Download Smiley Packages</a> | <a href="http://www.zfen.de/">Author Website</a> | <a href="http://www.zfen.de/contact/">Author E-Mail</a> | <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6271728/">Donate</a></p><hr />  
			
			<!-- Begin section Code -->
			<h3>&raquo; Quicktagzmilies code</h3>
			<fieldset>
				<legend><strong>Code<?php echo ($qtz_dca['options']['standalone']) ? 's' : ''; ?> for comments.php</strong> <span style="cursor: pointer;" onclick="qtz_toggle('help_code');">( ? )</span></legend>	
				<p id="help_code" style="display: none;">
					1. Open comments.php and search for "&lt;textarea ...&gt;.<br />
					2. Check the id of the HTML tag. It has to be "comment" (id="comment"). This is WordPress standard.<br />
					3. Paste following PHP code<?php echo ($qtz_dca['options']['standalone']) ? 's' : ''; ?> before the line containing &lt;textarea ...<br />
					4. Save and upload comments.php
				</p>
				<p>				
					<?php 	
					if(!$qtz_dca['options']['standalone']) {
						$code_value = "<?php if (function_exists('quicktagzmilies')) { quicktagzmilies(); } ?>";
						echo '<p>Function for the Quicktag- and Smileybar<br />';
						echo '<input size="65" id="embed_code_0" value="'. $code_value. '" onclick="qtz_$(\'embed_code_0\').focus(); qtz_$(\'embed_code_0\').select();" readonly="readonly" type="text"></p>';
					}
					elseif($qtz_dca['options']['standalone']) {
						$code_value_1 = "<?php if (function_exists('quicktagzmilies')) { quicktagzmilies('quicktag_bar'); } ?>";
						$code_value_2 = "<?php if (function_exists('quicktagzmilies')) { quicktagzmilies('zmilie_bar'); } ?>";
						echo '<p>Function for the Quicktagbar<br />';
						echo '<input size="65" id="embed_code_0" value="'. $code_value_1. '" onclick="qtz_$(\'embed_code_0\').focus(); qtz_$(\'embed_code_0\').select();" readonly="readonly" type="text"></p>';
						echo '<p>Function for the Smileybar<br />';
						echo '<input size="65" id="embed_code_1" value="'. $code_value_2. '" onclick="qtz_$(\'embed_code_1\').focus(); qtz_$(\'embed_code_1\').select();" readonly="readonly" type="text"></p>';						
					}
					?>
				</p>    
			</fieldset><hr />
			<!-- End section Code -->
		
			<form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?page=' . $_GET['page'] ?>&updated=true"> 	
				
				<input type="hidden" name="qtz_update" value="1" />
				
				<!-- Begin section Zmilies -->
				<h3>&raquo; Zmilies</h3>
				<fieldset>
					<legend><strong>Use smileybar?</strong> <span style="cursor: pointer;" onclick="qtz_toggle('help_use_zmilies');">( ? )</span></legend>	
					<p id="help_use_zmilies" style="display: none;">
						Do you want to use the smileybar?<br />If you click "No", this plugin will no more replace smileys in your posts or comments. 
						The link to toggle the smileybar and the smileybar will be hidden.
					</p>
					<p>
						<input onclick="qtz_$('hide_zmilies_section').style.display='block';" type="radio" name="usesmiliesbar" value="1" id="usezmilies_1" <?php echo ($qtz_dca['options']['usesmiliesbar']) ? 'checked="checked"' : ''; ?> />
						<label for="usezmilies_1">Yes</label>&nbsp;
						<input onclick="qtz_$('hide_zmilies_section').style.display='none';" type="radio" name="usesmiliesbar" value="0" id="usezmilies_0" <?php echo (!$qtz_dca['options']['usesmiliesbar']) ? 'checked="checked"' : ''; ?> />
						<label for="usezmilies_0">No</label>
					</p>    
				</fieldset>
			
				<!-- Begin hide Zmilies section -->
				<div id="hide_zmilies_section" <?php echo (!$qtz_dca['options']['usesmiliesbar']) ? 'style="display: none;"' : ''; ?>>				
					<br /><fieldset>
						<legend><strong>Choose your smiley package</strong> <span style="cursor: pointer;" onclick="qtz_toggle('help_choose_zmilies');">( ? )</span></legend>			
						<p id="help_choose_zmilies" style="display: none">You can download some smiley packages <a href="http://www.zfen.de/webapplications/quicktagzmilies#Smileypacks">on my website</a>.</p>
						<?php	
						// WordPress smileys
						$qtz_checked = '';
						echo '<p>';
						if($qtz_dca['options']['packagename'] == 'wordpress') {
							$qtz_checked = 'checked="checked"';
						}
						echo '<div style="padding-bottom: 3px;"><input name="packagename" type="radio" value="wordpress" id="wordpress" '. $qtz_checked. ' />&nbsp;';
						echo '<label for="wordpress"><strong>WordPress &raquo;</strong></label></div>';
						qtz_build_zmilie_images('wordpress');
						echo '</p>';
													
						// read every zmilie directory	
						if(is_dir($qtz_dca['dir']['srv_zmilies'])) {
							if ($handle = opendir($qtz_dca['dir']['srv_zmilies'])) {										
								while (false !== ($dirname = readdir($handle))) {										
									if ($dirname != '.' && $dirname != '..') {										
										if(is_file($qtz_dca['dir']['srv_zmilies']. '/'. $dirname. '/'. 'zmiliepackage.php')) {												
											// include zmiliepackage.php from current directory
											include($qtz_dca['dir']['srv_zmilies']. '/'. $dirname. '/'. 'zmiliepackage.php');											
											// :P
											echo '<p>';					
											// set the package_name if available or set to directory name
											$package_name = (strlen($package_name) > 0) ? $package_name : $dirname;											
											// set radio button to checked if actual package is in options array
											$qtz_checked = '';
											if($qtz_dca['options']['packagename'] == $dirname) {
												$qtz_checked = 'checked="checked"';
											}												
											echo '<div style="padding-bottom: 3px;"><input name="packagename" type="radio" value="'. $dirname. '" id="'. $dirname. '" '. $qtz_checked. ' />&nbsp;';
											echo '<label for="'. $dirname. '"><strong>'. $package_name. ' &raquo;</strong></label></div>';																	
											// build the zmilie images
											qtz_build_zmilie_images($dirname, $zmilies_array);	;											
											// P:
											echo '</p>';
										}
									}
								}
								closedir($handle);
							}
							}
						?>
					</fieldset><br />
					
					<fieldset>
						<legend><strong>Smileysbar - Wrapper (qtz_zmilies_wrapper)</strong> <span style="cursor: pointer;" onclick="qtz_toggle('help_zmilies_wrapper');">( ? )</span></legend>	
							
						<p id="help_zmilies_wrapper" style="display: none;">
							Here you can define the HTML wrapper (<strong>before</strong> and <strong>after</strong>) for the smileybar. You can use the id to style the smileybar using CSS. 
						</p>
							
						<p><label for="zmilies_before">Before (Before the smileys)</label><br />
							<input size="65" id="zmilies_before" type="text" name="zmilies_before" value="<?php echo htmlspecialchars(stripslashes($qtz_dca['options']['zmilies_before'])); ?>" />						
						</p>
						<p>
							<label for="zmilies_after">After (After the smileys)</label><br />
							<input size="65" id="zmilies_after" type="text" name="zmilies_after" value="<?php echo htmlspecialchars(stripslashes($qtz_dca['options']['zmilies_after'])); ?>" />
						</p>	
					</fieldset>
					
				</div><hr />
				<!-- End hide Zmilies section -->
				<!-- End section Zmilies -->       
														
			<!-- Begin section Quicktags -->
			<h3>&raquo; Quicktags</h3>
				
			<fieldset>
				<legend><strong>Quicktagbar - Wrapper (qtz_tagbar_wrapper)</strong> <span style="cursor: pointer;" onclick="qtz_toggle('help_quickbar_wrapper');">( ? )</span></legend>	
					
				<p id="help_quickbar_wrapper" style="display: none">
					Here you can define the HTML wrapper (<strong>before</strong> and <strong>after</strong>) for the quicktagbar. You can use the id to format the quicktagbar using CSS. <strong>Between</strong> is the spacer between the quicktaglinks.
				</p>
					
				<p>
					<label for="tag_before">Before (Before the quicktaglinks)</label><br />
					<input size="55" id="tag_before" type="text" name="tag_before" value="<?php echo htmlspecialchars(stripslashes($qtz_dca['options']['tag_before'])); ?>" />
				</p>
				<p>
					<label for="tag_between">Between (Between the quicktaglinks)</label><br />
					<input size="55" id="tag_between" type="text" name="tag_between" value="<?php echo htmlspecialchars(stripslashes($qtz_dca['options']['tag_between'])); ?>" />
				</p>
				<p>
					<label for="tag_after">After (After of the quicktaglinks)</label><br />
					<input size="55" id="tag_after" type="text" name="tag_after" value="<?php echo htmlspecialchars(stripslashes($qtz_dca['options']['tag_after'])); ?>" />
				</p>
								
				<p>
					&raquo; <a id="show_example_tags" style="text-decoration: underline;" onclick="qtz_toggle('example_tags'); qtz_switch_innerHTML('show_example_tags', 'Show examples', 'Hide examples');">Show examples</a>
				</p>
				
				<div id="example_tags" style="display: none;">
					<p><strong>Example 1 (Easy wrapper):</strong></p>
					
					<p>
						<span style="color: red;"><strong>Before:</strong> &lt;p id=&quot;qtz_wrapper&quot;&gt;&lt;small&gt;</span><br />
						<span style="color: green;"><strong>Between: | </strong></span><br />
						<span style="color: blue;"><strong>After:</strong> &lt;/small&gt;&lt;/p&gt;</span> (Ende der H&uuml;lle)
					</p>			
					<p>
						<strong>Output</strong><br />
						<span style="color: red;">&lt;p id=&quot;qtz_wrapper&quot;&gt;<br />&nbsp;&lt;small&gt;</span><br />
						&nbsp;&nbsp;<small>
							Bold <strong style="color: green;"> | </strong> 
							Italic <strong style="color: green;"> | </strong>
							Underline <strong style="color: green;"> | </strong>
							Crossed out <strong style="color: green;"> | </strong>
							URL <strong style="color: green;"> | </strong>
							Quote <strong style="color: green;"> | </strong>
							+ <strong style="color: green;"> | </strong>
							- <strong style="color: green;"> | </strong>
							Zmilies 
						</small>	
						<span style="color: blue;"><br />&nbsp;&lt;/small&gt;<br />&lt;/p&gt;</span>
					</p>
								
					<p><strong>Example 2 (Complex wrapper, e.g. an unordered list):</strong></p>				
					<p>
						<span style="color: red;"><strong>Before:</strong> &lt;ul id=&quot;qtz_wrapper&quot;&gt;&lt;li&gt;</span><br />
						<span style="color: green;"><strong>Between: &lt;/li&gt;&lt;li&gt;</strong></span><br />
						<span style="color: blue;"><strong>After:</strong> &lt;/li&gt;&lt;/ul&gt;</span>
					</p>				
					<p>
						<strong>Output</strong><br />
						<span style="color: red;">&lt;ul id=&quot;qtz_wrapper&quot;&gt;<br />&nbsp;&lt;li&gt;</span>
						<small>Bold</small> <span style="color: green;">&lt;/li&gt;<br />&nbsp;&nbsp;&lt;li&gt;</span>
						<small>Italic</small> <span style="color: green;">&lt;/li&gt;<br />&nbsp;&nbsp;&lt;li&gt;</span>
						<small>Underline</small> <span style="color: green;">&lt;/li&gt;<br />&nbsp;&nbsp;&lt;li&gt;</span>
						<small>Crossed out</small> <span style="color: green;">&lt;/li&gt;<br />&nbsp;&nbsp;&lt;li&gt;</span> 
						<small>URL</small> <span style="color: green;">&lt;/li&gt;<br />&nbsp;&nbsp;&lt;li&gt;</span>
						<small>Quote</small> <span style="color: green;">&lt;/li&gt;<br />&nbsp;&nbsp;&lt;li&gt;</span>
						<small>+</small> <span style="color: green;">&lt;/li&gt;<br />&nbsp;&nbsp;&lt;li&gt;</span>
						<small>-</small> <span style="color: green;">&lt;/li&gt;<br />&nbsp;&nbsp;&lt;li&gt;</span>
						<small>Zmilies</small>
						<span style="color: blue;">&lt;/li&gt;<br />&lt;/ul&gt;</span>
					</p>
				</div>		
			</fieldset><br />		
			
			<fieldset>
				<legend><strong>Quicktagbar - Labels</strong> <span style="cursor: pointer;" onclick="qtz_toggle('help_define_labels');">( ? )</span></legend>		
				<p id="help_define_labels" style="display: none">
					Here you can define the labels for the quicktagbar. <strong>Text for ...</strong> is the visible part (the quicktag link)
					<strong>Tooltip for ...</strong> is the text for the mouseover tooltip.
					<strong>Prompt for URL</strong> und <strong>prompt for authorname</strong> is the text appearing in a javascript prompt box (only URL and quote).
				</p>				
				<p>
					<label for="bname"><strong>&raquo; Text for bold</strong> - Hint: Style it <strong>bold</strong> using &lt;strong&gt;<?php echo strip_tags($qtz_dca['options']['bold_label']); ?>&lt;/strong&gt;</label><br />
					<input size="65" id="bold_label" type="text" name="bold_label" value="<?php echo $qtz_dca['options']['bold_label']; ?>" />
				</p>									
				<p>
					<label for="btitle"><strong>Tooltip for bold</strong></label><br />
					<input size="65" id="bold_title" type="text" name="bold_title" value="<?php echo $qtz_dca['options']['bold_title']; ?>" />
				</p>					
				<p>
					<label for="iname"><strong>&raquo; Text for italic</strong> - Hint: Style it <em>italic</em> using &lt;em&gt;<?php echo strip_tags($qtz_dca['options']['italic_label']); ?>&lt;/em&gt;</label><br />
					<input size="65" id="italic_label" type="text" name="italic_label" value="<?php echo $qtz_dca['options']['italic_label']; ?>" />
				</p>								
				<p>
					<label for="ititle"><strong>Tooltip for italic</strong></label><br />
					<input size="65" id="italic_title" type="text" name="italic_title" value="<?php echo $qtz_dca['options']['italic_title']; ?>" />
				</p>					
				<p>
					<label for="uname"><strong>&raquo; Text for underline</strong> - Hint: Style it <u>underlined</u> using &lt;u&gt;<?php echo strip_tags($qtz_dca['options']['underline_label']); ?>&lt;/u&gt;</label><br />
					<input size="65" id="underline_label" type="text" name="underline_label" value="<?php echo $qtz_dca['options']['underline_label']; ?>" />
				</p>								
				<p>
					<label for="utitle"><strong>Tooltip for underline</strong></label><br />
					<input size="65" id="underline_title" type="text" name="underline_title" value="<?php echo $qtz_dca['options']['underline_title']; ?>" />
				</p>					
				<p>
					<label for="sname"><strong>&raquo; Text for cross out</strong> - Hint: Style it <strike>stroken</strike> using &lt;strike&gt;<?php echo strip_tags($qtz_dca['options']['strike_label']); ?>&lt;/strike&gt;</label><br />
					<input size="65" id="strike_label" type="text" name="strike_label" value="<?php echo $qtz_dca['options']['strike_label']; ?>" />
				</p>								
				<p>
					<label for="stitle"><strong>Tooltip for cross out</strong></label><br />
					<input size="65" id="strike_title" type="text" name="strike_title" value="<?php echo $qtz_dca['options']['strike_title']; ?>" />
				</p>					
				<p>
					<label for="aname"><strong>&raquo; Text for URL</strong></label><br />
					<input size="65" id="url_label" type="text" name="url_label" value="<?php echo $qtz_dca['options']['url_label']; ?>" />
				</p>								
				<p>
					<label for="atitle"><strong>Tooltip for URL</strong></label><br />
					<input size="65" id="url_title" type="text" name="url_title" value="<?php echo $qtz_dca['options']['url_title']; ?>" />
				</p>								
				<p>
					<label for="aalert1"><strong>Prompt for URL</strong></label><br />
					<input size="65" id="url_alert" type="text" name="url_alert" value="<?php echo $qtz_dca['options']['url_alert']; ?>" />
				</p>					
				<p>
					<label for="qname"><strong>&raquo; Text for quote</strong></label><br />
					<input size="65" id="quote_label" type="text" name="quote_label" value="<?php echo $qtz_dca['options']['quote_label']; ?>" />
				</p>								
				<p>
					<label for="qtitle"><strong>Tooltip for quote</strong></label><br />
					<input size="65" id="quote_title" type="text" name="quote_title" value="<?php echo $qtz_dca['options']['quote_title']; ?>" />
				</p>								
				<p>
					<label for="qalert1"><strong>Prompt for authorname</strong></label><br />
					<input size="65" id="quote_alert" type="text" name="quote_alert" value="<?php echo $qtz_dca['options']['quote_alert']; ?>" />
				</p>					
				<p>
					<label for="upname"><strong>&raquo; Text for resize commentfield</strong></label><br />
					<input size="65" id="resize_label" type="text" name="resize_label" value="<?php echo $qtz_dca['options']['resize_label']; ?>" />
				</p>								
				<p>
					<label for="uptitle"><strong>Tooltip for resize commentfield</strong></label><br />
					<input size="65" id="resize_title" type="text" name="resize_title" value="<?php echo $qtz_dca['options']['resize_title']; ?>" />
				</p>						
				<p>
					<label for="dwnname"><strong>&raquo; Text for downsize commentfield</strong></label><br />
					<input size="65" id="downsize_label" type="text" name="downsize_label" value="<?php echo $qtz_dca['options']['downsize_label']; ?>" />
				</p>								
				<p>
					<label for="dwntitle"><strong>Tooltip for downsize commentfield</strong></label><br />
					<input size="65" id="downsize_title" type="text" name="downsize_title" value="<?php echo $qtz_dca['options']['downsize_title']; ?>" />
				</p>				
				<p>
					<label for="zname"><strong>&raquo; Text for smileybar toggler</strong></label><br />
					<input size="65" idzmilies_label" type="text" name="zmilies_label" value="<?php echo $qtz_dca['options']['zmilies_label']; ?>" />
				</p>										
				<p>
					<label for="ztitle1"><strong>Tooltip for show smileybar</strong></label><br />
					<input size="65" id="zmilies_title_s" type="text" name="zmilies_title_s" value="<?php echo $qtz_dca['options']['zmilies_title_s']; ?>" />
				</p>									
				<p>
					<label for="ztitle2"><strong>Tooltip for hide smileybar</strong></strong></label><br />
					<input size="65" id="zmilies_title_h" type="text" name="zmilies_title_h" value="<?php echo $qtz_dca['options']['zmilies_title_h']; ?>" />
				</p>	
			</fieldset><br />
			
			<fieldset>
				<legend><strong>Quicktagbar standalone</strong> <span style="cursor: pointer;" onclick="qtz_toggle('help_quickbar_standalone');">( ? )</span></legend>	
				<p id="help_quickbar_standalone" style="display: none;">
					If you want to display the quicktagbar in another area than the smileybar (default is quicktagbar above smileybar), you can choose the
					option <strong>Standalone</strong>. On top of this options page you will find two functions: One for the quicktagbar and one for the smileysbar.
				</p>
				<p>
					<input type="radio" name="standalone" value="0" id="standalone_0" <?php echo (!$qtz_dca['options']['standalone']) ? 'checked="checked"' : ''; ?> />
					<label for="standalone_0">Default (Quicktagbar above smileysbar)</label><br />
					<input type="radio" name="standalone" value="1" id="standalone_1" <?php echo ($qtz_dca['options']['standalone']) ? 'checked="checked"' : ''; ?> />
					<label for="standalone_1">Standalone</label>
				</p>    
			</fieldset>
			
			<hr /> 
			
			<h3>&raquo; Reset</h3>
			<fieldset>
				<legend><strong>You messed it up?</strong></legend>
				<p><a class="button-secondary" style="font-weight: bold; color: #cc0000;" onclick="return window.confirm('Reset Quicktagzmilies options? Are you sure? The heaven could fall on your head!');" href="<?php echo $_SERVER['PHP_SELF'] . '?page=' . $_GET['page'] ?>&reset=true" title="Reset to default">Reset to default</a> (You have to confirm after you have clicked this link)</p>    
			</fieldset>
			
			<hr /> 
			<h3>Don't forget to &hellip;</h3>
			<input class="button-primary" type="submit" name="Update Options" value="Update Options" id="submitbutton" />
		
		</form>  		
	
		<!-- End Content -->
		</td></tr></table>
		
		<!-- Begin Footer -->
			<hr />
			<p><small>&copy; 2007 - <?php echo date('Y'); ?> by <a href="http://www.zfen.de/">Zfen</a></small></p>
		<!-- End Footer -->
	</div>
<?php	
} //- End update check


//- Apply options to admin menu
add_action('admin_menu', 'qtz_add_admin_option_page');
function qtz_add_admin_option_page() {
	if (function_exists('add_options_page')) {
		add_options_page('Quicktagzmilies', 'Quicktagzmilies', 6, __FILE__, 'qtz_create_admin_option_page');
	}
}


//- Add metabox to edit pages
if($qtz_dca['options']['usesmiliesbar']) {
	function qtz_add_meta_box() {
		if(qtz_page_now('edit')) {
			add_meta_box('qtz_edit_metabox', 'Quicktagzmilies', 'qtz_create_metabox', 'post', 'side', 'high' );
			add_meta_box('qtz_edit_metabox', 'Quicktagzmilies', 'qtz_create_metabox', 'page', 'side', 'high' );
		}
	}
	//- Create metabox 
	function qtz_create_metabox() {
		qtz_build_zmilie_images();
	}
	add_action('admin_menu', 'qtz_add_meta_box');
}





?>