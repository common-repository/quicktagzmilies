<?php 
/* Javascript for Wordpress Quicktagzmilies Plugin

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

header('Content-Type: application/x-javascript');
include('../../../wp-config.php');
$qtz_dca['options'] = get_option('plugin_quicktagzmilies');
$qtz_dca['options']['zmiliesarray'] = unserialize($qtz_dca['options']['zmiliesarray']);
?>


//- $ - getElementById
function qtz_$(id) {
	return document.getElementById(id);
}


//- Toggle function
function qtz_toggle(id) {
	elm = qtz_$(id);
	if(elm.style.display == 'none') {
		qtz_show(id);
	}
	else {
		qtz_hide(id);
	}
}


//- Hide function
function qtz_hide(id) {
	elm = qtz_$(id);
	elm.style.display = 'none';
}

//- Show function
function qtz_show(id) {
	elm = qtz_$(id);
	elm.style.display = 'block';
}

//- Change innerHTML 
function qtz_switch_innerHTML(id, innerHTML_1, innerHTML_2) {
	elm = qtz_$(id);
	if(elm.innerHTML == innerHTML_1 || elm.innerHTML == '') {
		elm.innerHTML = innerHTML_2;
	}
	else {
		elm.innerHTML = innerHTML_1;
	}	
}


//- Bold Button
qtz_$("qtz_button_bold").onclick = function () {  
	qtz_code("B", "comment");
	return false;
}
//- Italic Button
qtz_$("qtz_button_italic").onclick = function () {
	qtz_code("I", "comment");
	return false;
}
//- Underlined Button
qtz_$("qtz_button_underline").onclick = function () {  
	qtz_code("U", "comment");
	return false;
}
//- Strike Button
qtz_$("qtz_button_strike").onclick = function () { 
	qtz_code("S", "comment");
	return false;
}
//- Link Button
qtz_$("qtz_button_link").onclick = function () {   
	qtz_code("Url", "comment");
	return false;
}
//- Quote Button
qtz_$("qtz_button_quote").onclick = function () {  
	qtz_code("Quote", "comment"); 
	return false;
}
//- Increase Button
qtz_$("qtz_button_up").onclick = function () {   
	qtz_resizeTextarea(100);
	this.blur();
	return false;
}
//- Decrease Button
qtz_$("qtz_button_dwn").onclick = function () {
	qtz_resizeTextarea(-100);
	this.blur();
	return false;
}
<?php
if($qtz_dca['options']['usesmiliesbar']) {	?>
//- Zmilie Button
qtz_$("qtz_button_zmilies").onclick = function () {
	if(this.title == "<?php echo $qtz_dca['options']['zmilies_title_s']; ?>")	{   
		this.title = "<?php echo $qtz_dca['options']['zmilies_title_h']; ?>";
	}
	else {
		this.title = "<?php echo $qtz_dca['options']['zmilies_title_s']; ?>";  
	}  
	qtz_toggle("qtz_zmilies_toggle_wrapper");
	qtz_$('comment').focus(); 
	return false;
} 
<?php } ?>

//- qtz_code
function qtz_code(qtz_tag, id) {
	var aTag, eTag;
	 
	switch (qtz_tag) {
		case 'B':
			aTag = '<strong>';
			eTag = '</strong>';
		break;	
		case 'I':
			aTag = '<em>';
			eTag = '</em>';
		break;	
		case 'U':
			aTag = '<u>';
			eTag = '</u>';
		break;	
		case 'S':
			aTag = '<strike>';
			eTag = '</strike>';
		break;
		case 'Url':
			qtz_url();
		return;
		case 'Quote':
			qtz_quote();
		return;
		default: // Smilies
			aTag = ' ' + qtz_tag + ' ';
			eTag = '';
	}	
	qtz_insert_text(aTag, eTag, id);
}

//- qtz_url
function qtz_url() {	
	var url = prompt('<?php echo $qtz_dca['options']['url_alert']; ?>', 'http://');
	if(url)	{
		qtz_insert_text('<a href="' + url + '">', '</a>', 'comment');
	}
}

//- qtz_quote
function qtz_quote() {	
	var author = prompt('<?php echo $qtz_dca['options']['quote_alert']; ?>', '');
	if(author.length > 0)	{
		var aTag = '<blockquote><strong>' + author + ': </strong>';
	}
	else {
		var aTag = '<blockquote>';
	}		
	if(author == 'null') {
	}
	qtz_insert_text(aTag, '</blockquote>', 'comment');
}

//- qtz_insert_admin_smileys
function qtz_insert_admin_smileys(tag, id) {
	if ( typeof tinyMCE != "undefined" )
		tedit = tinyMCE.get('content');

    if ( tedit == null || tedit.isHidden() == true) {
    	qtz_insert_text (tag, '', id);
    }
		else if ( (tedit.isHidden() == false) && window.tinyMCE) { 		
			window.tinyMCE.execInstanceCommand('content', 'mceInsertContent',	false, tag);
    }
}

//- qtz_insert_text
// Original source: http://aktuell.de.selfhtml.org/artikel/javascript/bbcode/
// Castrated and edited by Zfen
function qtz_insert_text(aTag, eTag, id)
{
	var input = qtz_$(id);
	input.focus();    
    
	//- Gecko Area
	if(typeof input.selectionStart != 'undefined') {
		var start = input.selectionStart;
		var end = input.selectionEnd;		
		
		var scrollTop = input.scrollTop;
		
		var insText = qtz_rTrimString(input.value.substring(start, end));
		input.value = input.value.substr(0, start) + aTag + insText + eTag + qtz_whitespace + input.value.substr(end);
        
		var pos;
		if (insText.length == 0) {
			pos = start + aTag.length;
		}
		else {
			pos = start + aTag.length + insText.length + eTag.length;
		}
		input.selectionStart = pos;
		input.selectionEnd = pos;
		input.scrollTop = scrollTop;
	}    
	//- IE Area
	else if(typeof document.selection != 'undefined') {
		var range = document.selection.createRange();
		var insText = qtz_rTrimString(range.text);
		range.text = aTag + insText + eTag + qtz_whitespace;
		
		range = document.selection.createRange();
		if (insText.length == 0) {
			range.move('character', -eTag.length);
		}
		else {
			range.moveStart('character', aTag.length + insText.length + eTag.length + qtz_whitespace.length); 
		}
		range.select();
	}
}

//- Last sign is a whitespace? remove it!
function qtz_rTrimString(myString) {
	qtz_whitespace = '';
	var lastSign = myString.substring(myString.length-1);
	if( lastSign == ' ') {
		qtz_whitespace = ' ';
		return myString.replace( /\s+$/g, "" );
	}
	else {
		return myString;	
	}
} 

//- qtz_resizeTextarea
var qtz_tAreaSize = qtz_$('comment').offsetHeight;
var qtz_actualSize = qtz_tAreaSize;
var qtz_click = 0;
//qtz_$("qtz_button_down")
function qtz_resizeTextarea(re_size) {
	if((qtz_click < 6 && re_size > 0) || (qtz_click > 0 && re_size < 0)) {	
		if(re_size > 0) { qtz_click++; }
		else { qtz_click--; }
		qtz_actualSize += re_size;
		qtz_$("comment").style.height = qtz_actualSize + "px";
	}
}


