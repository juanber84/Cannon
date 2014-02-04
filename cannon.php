<?php

	$file = ".cannon/config.json";
	
	function writefile($file,$content){
		$fp = fopen($file,"w+");
		fwrite($fp, $content . PHP_EOL);
		fclose($fp);	
	}

	function contentfile($file){
		return file_get_contents($file);
	}	

	function status($project, $port, $url, $pid, $file){
		$process = '/usr/bin/php -S localhost:' . $port . ' -t ' . $url;
		$bash   = exec('ps '.$pid);
		$pos = strpos($bash, $process);
		if ($pos !== false) {
		    $find = true;
		} else {
			$content = contentfile($file);
			$servers = json_decode($content,true);	
			$servers[$project]['pid'] = "";
			$json = json_encode($servers);
			writefile($file,$json);	
		    $find = false;
		}
		return $find;
	}

	if ($_GET) {
		switch ($_GET['action']) {
			case 'start':
					$content = contentfile($file);
					$servers = json_decode($content,true);	
					$command =  '/usr/bin/php -S localhost:' . $servers[$_GET['project']]['port'] . ' -t ' . $servers[$_GET['project']]['url'] . ' > /dev/null 2>&1 & echo $!; ';
					$pid = exec($command, $output);
					$servers[$_GET['project']]['pid'] = $pid;
					$json = json_encode($servers);
					writefile($file,$json);					
					header('Location: cannon.php');
				break;
			case 'stop':
					$content = contentfile($file);
					$servers = json_decode($content,true);	
					exec("kill -9 ".$servers[$_GET['project']]['pid']);
					$servers[$_GET['project']]['pid'] = "";
					$json = json_encode($servers);
					writefile($file,$json);					
					header('Location: cannon.php');
				break;
			case 'remove':
					$content = contentfile($file);
					$servers = json_decode($content,true);	
					exec("kill -9 ".$servers[$_GET['project']]['pid']);
					unset($servers[$_GET['project']]);
					$json = json_encode($servers);
					writefile($file,$json);					
					header('Location: cannon.php');
				break;							
		}

	}

	if ($_POST) {
		$content = contentfile($file);
		$servers = json_decode($content,true);
		$servers[$_POST['project']]=array(
			'project' => $_POST['project'], 
			'url' => $_POST['url'], 
			'port' => $_POST['port'], 
		);
		$json = json_encode($servers);
		writefile($file,$json);		
		header('Location: cannon.php');
	}

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <style type="text/css">
/*! normalize.css v1.1.2 | MIT License | git.io/normalize */

/* ==========================================================================
   HTML5 display definitions
   ========================================================================== */

/**
 * Correct `block` display not defined in IE 6/7/8/9 and Firefox 3.
 */

article,
aside,
details,
figcaption,
figure,
footer,
header,
hgroup,
main,
nav,
section,
summary {
    display: block;
}

/**
 * Correct `inline-block` display not defined in IE 6/7/8/9 and Firefox 3.
 */

audio,
canvas,
video {
    display: inline-block;
    *display: inline;
    *zoom: 1;
}

/**
 * Prevent modern browsers from displaying `audio` without controls.
 * Remove excess height in iOS 5 devices.
 */

audio:not([controls]) {
    display: none;
    height: 0;
}

/**
 * Address styling not present in IE 7/8/9, Firefox 3, and Safari 4.
 * Known issue: no IE 6 support.
 */

[hidden] {
    display: none;
}

/* ==========================================================================
   Base
   ========================================================================== */

/**
 * 1. Correct text resizing oddly in IE 6/7 when body `font-size` is set using
 *    `em` units.
 * 2. Prevent iOS text size adjust after orientation change, without disabling
 *    user zoom.
 */

html {
    font-size: 100%; /* 1 */
    -ms-text-size-adjust: 100%; /* 2 */
    -webkit-text-size-adjust: 100%; /* 2 */
}

/**
 * Address `font-family` inconsistency between `textarea` and other form
 * elements.
 */

html,
button,
input,
select,
textarea {
    font-family: sans-serif;
}

/**
 * Address margins handled incorrectly in IE 6/7.
 */

body {
    margin: 0;
}

/* ==========================================================================
   Links
   ========================================================================== */

/**
 * Address `outline` inconsistency between Chrome and other browsers.
 */

a:focus {
    outline: thin dotted;
}

/**
 * Improve readability when focused and also mouse hovered in all browsers.
 */

a:active,
a:hover {
    outline: 0;
}

/* ==========================================================================
   Typography
   ========================================================================== */

/**
 * Address font sizes and margins set differently in IE 6/7.
 * Address font sizes within `section` and `article` in Firefox 4+, Safari 5,
 * and Chrome.
 */

h1 {
    font-size: 2em;
    margin: 0.67em 0;
}

h2 {
    font-size: 1.5em;
    margin: 0.83em 0;
}

h3 {
    font-size: 1.17em;
    margin: 1em 0;
}

h4 {
    font-size: 1em;
    margin: 1.33em 0;
}

h5 {
    font-size: 0.83em;
    margin: 1.67em 0;
}

h6 {
    font-size: 0.67em;
    margin: 2.33em 0;
}

/**
 * Address styling not present in IE 7/8/9, Safari 5, and Chrome.
 */

abbr[title] {
    border-bottom: 1px dotted;
}

/**
 * Address style set to `bolder` in Firefox 3+, Safari 4/5, and Chrome.
 */

b,
strong {
    font-weight: bold;
}

blockquote {
    margin: 1em 40px;
}

/**
 * Address styling not present in Safari 5 and Chrome.
 */

dfn {
    font-style: italic;
}

/**
 * Address differences between Firefox and other browsers.
 * Known issue: no IE 6/7 normalization.
 */

hr {
    -moz-box-sizing: content-box;
    box-sizing: content-box;
    height: 0;
}

/**
 * Address styling not present in IE 6/7/8/9.
 */

mark {
    background: #ff0;
    color: #000;
}

/**
 * Address margins set differently in IE 6/7.
 */

p,
pre {
    margin: 1em 0;
}

/**
 * Correct font family set oddly in IE 6, Safari 4/5, and Chrome.
 */

code,
kbd,
pre,
samp {
    font-family: monospace, serif;
    _font-family: 'courier new', monospace;
    font-size: 1em;
}

/**
 * Improve readability of pre-formatted text in all browsers.
 */

pre {
    white-space: pre;
    white-space: pre-wrap;
    word-wrap: break-word;
}

/**
 * Address CSS quotes not supported in IE 6/7.
 */

q {
    quotes: none;
}

/**
 * Address `quotes` property not supported in Safari 4.
 */

q:before,
q:after {
    content: '';
    content: none;
}

/**
 * Address inconsistent and variable font size in all browsers.
 */

small {
    font-size: 80%;
}

/**
 * Prevent `sub` and `sup` affecting `line-height` in all browsers.
 */

sub,
sup {
    font-size: 75%;
    line-height: 0;
    position: relative;
    vertical-align: baseline;
}

sup {
    top: -0.5em;
}

sub {
    bottom: -0.25em;
}

/* ==========================================================================
   Lists
   ========================================================================== */

/**
 * Address margins set differently in IE 6/7.
 */

dl,
menu,
ol,
ul {
    margin: 1em 0;
}

dd {
    margin: 0 0 0 40px;
}

/**
 * Address paddings set differently in IE 6/7.
 */

menu,
ol,
ul {
    padding: 0 0 0 40px;
}

/**
 * Correct list images handled incorrectly in IE 7.
 */

nav ul,
nav ol {
    list-style: none;
    list-style-image: none;
}

/* ==========================================================================
   Embedded content
   ========================================================================== */

/**
 * 1. Remove border when inside `a` element in IE 6/7/8/9 and Firefox 3.
 * 2. Improve image quality when scaled in IE 7.
 */

img {
    border: 0; /* 1 */
    -ms-interpolation-mode: bicubic; /* 2 */
}

/**
 * Correct overflow displayed oddly in IE 9.
 */

svg:not(:root) {
    overflow: hidden;
}

/* ==========================================================================
   Figures
   ========================================================================== */

/**
 * Address margin not present in IE 6/7/8/9, Safari 5, and Opera 11.
 */

figure {
    margin: 0;
}

/* ==========================================================================
   Forms
   ========================================================================== */

/**
 * Correct margin displayed oddly in IE 6/7.
 */

form {
    margin: 0;
}

/**
 * Define consistent border, margin, and padding.
 */

fieldset {
    border: 1px solid #c0c0c0;
    margin: 0 2px;
    padding: 0.35em 0.625em 0.75em;
}

/**
 * 1. Correct color not being inherited in IE 6/7/8/9.
 * 2. Correct text not wrapping in Firefox 3.
 * 3. Correct alignment displayed oddly in IE 6/7.
 */

legend {
    border: 0; /* 1 */
    padding: 0;
    white-space: normal; /* 2 */
    *margin-left: -7px; /* 3 */
}

/**
 * 1. Correct font size not being inherited in all browsers.
 * 2. Address margins set differently in IE 6/7, Firefox 3+, Safari 5,
 *    and Chrome.
 * 3. Improve appearance and consistency in all browsers.
 */

button,
input,
select,
textarea {
    font-size: 100%; /* 1 */
    margin: 0; /* 2 */
    vertical-align: baseline; /* 3 */
    *vertical-align: middle; /* 3 */
}

/**
 * Address Firefox 3+ setting `line-height` on `input` using `!important` in
 * the UA stylesheet.
 */

button,
input {
    line-height: normal;
}

/**
 * Address inconsistent `text-transform` inheritance for `button` and `select`.
 * All other form control elements do not inherit `text-transform` values.
 * Correct `button` style inheritance in Chrome, Safari 5+, and IE 6+.
 * Correct `select` style inheritance in Firefox 4+ and Opera.
 */

button,
select {
    text-transform: none;
}

/**
 * 1. Avoid the WebKit bug in Android 4.0.* where (2) destroys native `audio`
 *    and `video` controls.
 * 2. Correct inability to style clickable `input` types in iOS.
 * 3. Improve usability and consistency of cursor style between image-type
 *    `input` and others.
 * 4. Remove inner spacing in IE 7 without affecting normal text inputs.
 *    Known issue: inner spacing remains in IE 6.
 */

button,
html input[type="button"], /* 1 */
input[type="reset"],
input[type="submit"] {
    -webkit-appearance: button; /* 2 */
    cursor: pointer; /* 3 */
    *overflow: visible;  /* 4 */
}

/**
 * Re-set default cursor for disabled elements.
 */

button[disabled],
html input[disabled] {
    cursor: default;
}

/**
 * 1. Address box sizing set to content-box in IE 8/9.
 * 2. Remove excess padding in IE 8/9.
 * 3. Remove excess padding in IE 7.
 *    Known issue: excess padding remains in IE 6.
 */

input[type="checkbox"],
input[type="radio"] {
    box-sizing: border-box; /* 1 */
    padding: 0; /* 2 */
    *height: 13px; /* 3 */
    *width: 13px; /* 3 */
}

/**
 * 1. Address `appearance` set to `searchfield` in Safari 5 and Chrome.
 * 2. Address `box-sizing` set to `border-box` in Safari 5 and Chrome
 *    (include `-moz` to future-proof).
 */

input[type="search"] {
    -webkit-appearance: textfield; /* 1 */
    -moz-box-sizing: content-box;
    -webkit-box-sizing: content-box; /* 2 */
    box-sizing: content-box;
}

/**
 * Remove inner padding and search cancel button in Safari 5 and Chrome
 * on OS X.
 */

input[type="search"]::-webkit-search-cancel-button,
input[type="search"]::-webkit-search-decoration {
    -webkit-appearance: none;
}

/**
 * Remove inner padding and border in Firefox 3+.
 */

button::-moz-focus-inner,
input::-moz-focus-inner {
    border: 0;
    padding: 0;
}

/**
 * 1. Remove default vertical scrollbar in IE 6/7/8/9.
 * 2. Improve readability and alignment in all browsers.
 */

textarea {
    overflow: auto; /* 1 */
    vertical-align: top; /* 2 */
}

/* ==========================================================================
   Tables
   ========================================================================== */

/**
 * Remove most spacing between table cells.
 */

table {
    border-collapse: collapse;
    border-spacing: 0;
}

/* ==========================================================================
   HTML5 Boilerplate styles - h5bp.com (generated via initializr.com)
   ========================================================================== */

html,
button,
input,
select,
textarea {
    color: #222;
}

body {
    font-size: 1em;
    line-height: 1.4;
}

::-moz-selection {
    background: #b3d4fc;
    text-shadow: none;
}

::selection {
    background: #b3d4fc;
    text-shadow: none;
}

hr {
    display: block;
    height: 1px;
    border: 0;
    border-top: 1px solid #ccc;
    margin: 1em 0;
    padding: 0;
}

img {
    vertical-align: middle;
}

fieldset {
    border: 0;
    margin: 0;
    padding: 0;
}

textarea {
    resize: vertical;
}

.chromeframe {
    margin: 0.2em 0;
    background: #ccc;
    color: #000;
    padding: 0.2em 0;
}


/* ==========================================================================
   Author's custom styles
   ========================================================================== */

@font-face {
    font-weight: normal;
    font-style: normal;
}   

.wrapper {width:100%;}
.container {width:950px;  margin:0 auto;}
header {background-color: #fff; height: 174px; text-align: center; padding-top: 40px;}
header h1 {background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQ4AAABZCAYAAADPTcpeAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MTU3OThGODg4Q0U1MTFFM0I4OTZCNDgxOUNGMEU1NjgiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MTU3OThGODk4Q0U1MTFFM0I4OTZCNDgxOUNGMEU1NjgiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDoxNTc5OEY4NjhDRTUxMUUzQjg5NkI0ODE5Q0YwRTU2OCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDoxNTc5OEY4NzhDRTUxMUUzQjg5NkI0ODE5Q0YwRTU2OCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pt50InkAACLtSURBVHja7F0JfFXF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
) no-repeat; text-indent: -10000px; width:270px; height: 89px; margin: 0 auto; width:270px; height: 89px; }
section {padding: 20px 0 40px 0;}
section.new {background-color: #3d4748; color:#fff;}
h2 {color:#fff; font-size:30px; font-family: 'death_from_aboveregular'; text-align: center; }
.icon_server {background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAnCAYAAADgpQMwAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6OEZCRTZGMkU4Q0U3MTFFMzgyMThCRDkwNkM0QkUxOEYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6OEZCRTZGMkY4Q0U3MTFFMzgyMThCRDkwNkM0QkUxOEYiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo4RkJFNkYyQzhDRTcxMUUzODIxOEJEOTA2QzRCRTE4RiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo4RkJFNkYyRDhDRTcxMUUzODIxOEJEOTA2QzRCRTE4RiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PhgorpoAAAGySURBVHja7NdNKERRGMbxO9fEgrKZRBGJJOxmx87GZoqtkrAgS2ul5GNpYSNJNjZsbWwoShZEJgtlYTGGKBaUYT6u/9ucW9Ppzp0Px27e+uk6xnPOGXPveQUcx7GKrE6M4wOXuMMTUvoLg0UGTmARzTljz7jHtZokqib6tGSlPjqw5xRXCZyhLV9YAFOIOaVXxGv7XVjFiFV6peWLnTMg19M4KjPQLcddaTdWMGwZKAkdwD4aLUMlW+43GeiGpizDZVv/UJXQSmgl1FBVSabpUDmzHkyGHmMMN0EDYd/YwrI6sq2/hsawhG0kc5/85QafYh4nXn/9K7yWEPaFDYx6BWaPvuw534M5HOK9wLm+i2q/JsTd+q2yjl4MYghh1GvriOPH/5DOP2MNwohqK10r0Cr53lHyUbnA43/cpnqvmSymmahDLTLaz+T7EFq18XbVJ8jRHsgZd6/fpLtb4GLG483PqAlDHuNx1Yx5hc7KShtK7FBsrfnVq8V22z+Dlba1LRhp+mT7O+pjo69Y3uM+TGoTJ7Apz02P54Y8pM8DBf47kZ1E0KQmlV96wYHfXfUrwADJ93IWBZn0LQAAAABJRU5ErkJggg==
) no-repeat; width: 21px; height: 39px; display:inline-block; margin-right: 20px;}
table tr td {padding: 5px 0;}
table tr td.txt {text-align: right; padding-right:30px;}
input[type="text"] {width:565px; height: 32px; border: 0; -moz-border-radius: 4px;-webkit-border-radius: 4px;border-radius: 4px; padding:2px 5px; color:#3d4748 ;}
input[type="submit"] {height: 32px; border: 0; -moz-border-radius: 4px;-webkit-border-radius: 4px;border-radius: 4px; padding:1px 25px; color:#3d4748 ;}
input[type="submit"]:hover {background-color:#85c2d9; color:#3d4748; }
section.list {background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAiYAAAE/CAYAAABy/wiIAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NzE4NzIyNTQ4Q0U4MTFFM0E5NjhBRDRFNTJCQTM2ODEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NzE4NzIyNTU4Q0U4MTFFM0E5NjhBRDRFNTJCQTM2ODEiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo3MTg3MjI1MjhDRTgxMUUzQTk2OEFENEU1MkJBMzY4MSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo3MTg3MjI1MzhDRTgxMUUzQTk2OEFENEU1MkJBMzY4MSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PvhG/eAAAB94SURBVHja7N0JW9xG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
) center 40px no-repeat #567175;}
.icon_list {background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACYAAAAfCAYAAACLSL/LAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NDJENkQ5RjI4Q0U4MTFFM0FBREVEMkFGNjYzMkZENTkiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NDJENkQ5RjM4Q0U4MTFFM0FBREVEMkFGNjYzMkZENTkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo0MkQ2RDlGMDhDRTgxMUUzQUFERUQyQUY2NjMyRkQ1OSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo0MkQ2RDlGMThDRTgxMUUzQUFERUQyQUY2NjMyRkQ1OSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PhRdXywAAAFKSURBVHja7Je7agJBFIZn12s0qGCVFxDs7O3yAHYW1japLG18CxvrQEiTLp2VpY8gmDewMsH1RtzxP3KayCxMhBnOgge+bmF+zu0/G2itJ0qpEtBKRgRgG0CYFEF/IgsoYxUQC9EUgh/KWE5ixgKhlbyU0hQ1UAUnx+9nwDdY2wijqRiCAdg7FlYEYzC63gpJwmgYyozrqPCb2qbHmqABjo5F5cESLFLT/KESGtRjL9xLkiwpEm1JU/AgTNeOMlYX2Gtx6izpkQci9rAVIrCxtaQ+6Hryyg+2JStLaoG2p6p9mSzJJIw+eGWrODgWVQBz0w69W9ItU/nMd5EkS9pTKTee7q7/REQZW/EpLekvaU0Ze+KSSirlb+osKcNoD9k5mRwmSVgPdDz9JX2CNxthIa+Qrqeq0VZ4vx6+JEua8fHow5JmppY5CzAAqjJkHA6Xjg4AAAAASUVORK5CYII=
) no-repeat; width: 40px; height: 39px; display:inline-block; margin-right: 20px;}
.iconaction {float: left; margin: 0 6px 0 11px;}
a.start {background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAATCAYAAACdkl3yAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NkQ0MTdBMEQ4Q0VFMTFFM0E3ODFDQTMyRDhBRDY1NkUiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NkQ0MTdBMEU4Q0VFMTFFM0E3ODFDQTMyRDhBRDY1NkUiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo2RDQxN0EwQjhDRUUxMUUzQTc4MUNBMzJEOEFENjU2RSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo2RDQxN0EwQzhDRUUxMUUzQTc4MUNBMzJEOEFENjU2RSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PpwUFvMAAAHuSURBVHjajJRLKIRRFMdnPkOeKU2xIIQNC4pQlMhCxIKQjazsPBez91wQFoisxMajhBQRG2WhlCTZsCTv58J7/I7O1PT5hrn169zvu+f+77nn3Hvtbrfb5qvVtrtyMdOwCc2zg31vvnyNP0TsmDpIhnLItv3RHBYCBZh32IVQL78gxkKwxbBDdDeWEeEUAK10F6AGJKJPHZb9v0AUjMMcvpm/hPgZjOmCIZ20Bh8q5mmBcKVjRTDDvHxzRPXggiOogHX97y3k0GS3QafmbgSxuB8hOok6cAdNOMv+v3wllbFHTAdMQLoIoxEjEVVBNEzhtGXzo+lCPXCrO6gUoVKQCixbzAn0SoG5wmeSdEiAEnFIhUvYtxC6UisRXJuikmLsQACkiVAESBKfTI5SvUlYgmE4tFjoXm24oR9y0JwWuZAqNkA//VcLoRi1tw7dUh7IAVu1ELv3cYUkfwV63g4MTXIkVDNo2PxvKVItOBYNmbgIp7KIXtJ/GwuGYfo0JbOwYhD6BZ0WPcUDONX9IxKLGYMy2IBRNB7snvcIh0b5qaWe1Ej3cLpkLIh+EhTqdcqBbSkE46c/d8n7YWOCrNINGfCoh+5Zz4rc/Hh1HYFeRM49c+3mFxIxp4Ytj1mWXh8p/QnIFZqXSptfy28BBgAqFqjzigyv+wAAAABJRU5ErkJggg==
)no-repeat; width:18px ; height:19px ; display: block;}
a.start:hover {color:#fff;}
a.stop {background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAYAAABWzo5XAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MEYyQTM0MDA4Q0YwMTFFMzkxRjlEMzIzMUI4NEI4NzEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MEYyQTM0MDE4Q0YwMTFFMzkxRjlEMzIzMUI4NEI4NzEiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDowRjJBMzNGRThDRjAxMUUzOTFGOUQzMjMxQjg0Qjg3MSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDowRjJBMzNGRjhDRjAxMUUzOTFGOUQzMjMxQjg0Qjg3MSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pn3DJgwAAAIqSURBVHjadJRLSFRhGIadGsrLeNeFQQkmtRARRNOViyDBoiIZcKFBLoJaFClOiRcwhCwtQUGZCgnbRepGUMSlBG0sSSJw4cIWKhZeyhEdbXw+eU+M43jgmf/MnHOe893+cYVCoZjwo6r+8TmWG6IQ0nVpC77DOIx86O6cCX/O5YgQnGC5Ci1QAjswC8sQlDAPUmEeuuE9wj//RUjcnNdAJ2TCEAzDtER7kAY54IV7kvfCc2RrbkVWAa8gDpqh3y7GHD424ScvtagCkASPYJ3fuk7+2Ahk86UPzsNT6EISiJBY1LFDE5MPOW1SmhbRKaX71SK6DkUwCn4kwSiS0ywPFG2yCv8GQorqtoluwrY68fsYSQM0ggd24Z2aclY1u2KdKoA5+BxFEs/yRJF4VPS30M5L/7IuwSfIMlGGFQxWIiQJkjSqCf/gNbQiWdJtG7BoJ27d4BKOJJHFp5Qcid/SQbIaPodgwRx8LKsLZyRJViQ+SYLqanOExI4U1elA9AUu2HbQYFoX6iBWTehVOpFzZcdFKLNJtwdH4BrcgXxrJcRri/RAB5L1Y7rpVTYfTTQGk1Auu+X9S+n0RItE+7Ia7tq0w6Cz10wwALm69yUCX5RUnEbUao6shj7u9Tt7bUodeqG8S3ngPuuM3hjUmBTrH+KW6tdm0Rz6G9HbLqljlWr5Aqxpmj3qUIIa9My2FdHsHBFJZi29rK1TrLFwq27fVNMxBAvhz+0LMACWfsd3SMJ6HQAAAABJRU5ErkJggg==
)no-repeat ; width:18px ; height:19px ; display: block;}
a.stop:hover {color:#fff;}
a.remove {background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAsAAAATCAYAAABGKffQAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MkY5M0NCRkY4Q0YwMTFFMzg1MzZENzMyQUJBMEUyOUUiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MkY5M0NDMDA4Q0YwMTFFMzg1MzZENzMyQUJBMEUyOUUiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDoyRjkzQ0JGRDhDRjAxMUUzODUzNkQ3MzJBQkEwRTI5RSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDoyRjkzQ0JGRThDRjAxMUUzODUzNkQ3MzJBQkEwRTI5RSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Plly7mkAAAFSSURBVHjanNJPK0RRHMbxe8/cwRSRKPmXpSSxIWXBgpfAO7CnWNhaKIt5AfMClA0rWyUrWWGDKCs2w4JJjX/H98lDU2bDqU/n3nOfe87vnnPTGGOitrC8Ok23gZHkq71jC2vbxc0HDQRCOT/MoxeX2MEHupEjk0rGTYmLMv2ww7e410TQauuoaCWF56Cw6jlEA8ZxhhQT7qsKF72kvGlZaPzFE+TdVzS4qLdcd/BLqUXTWFnhQZzi3IHgQFZzP6Vvyjyj6jtyvdGlqKRnFNCPgcyDPZhFH+7QhRMc+0WVEb4/ZB9XGMWFS9vjMA58YDqooeCaWtBW0zf7WkGV1qTygmuOdfxqIflD+1c4rSOpefYTjt6RqvtvrwqwI1Vvbxp8UqGORu9Gwf9Hls4vrWjmGzyiFU/eNh3OtbdxUqtp1l3MoNPLtfvEOjDmmvWPlz4FGADyuGGmxq14cQAAAABJRU5ErkJggg==
) no-repeat; width:18px ; height:19px ; display: block;}
a.remove:hover {color:#fff;}
footer {color:#3d4748; font-size: 18px; font-family: Arial; text-align: center; background-color: #659aa0; padding: 8px 0;}


/* table list */

#psdgraphics-com-table {
    margin:0 auto;
    padding: 4px;
    width: 578px;
    font: 11px Arial, Helvetica, sans-serif;
    color:#747474;
    background-color:#659aa0;
}


#psdg-header {
    margin:0;
    padding: 14px 0 0 24px;
    width: 554px;
    height: 55px;
    color:#FFF;
    font-size:13px;
}

.psdg-bold {
    font: bold 22px Arial, Helvetica, sans-serif;
    
}

#psdg-top {
    margin:0;
    padding: 0;
    width: 578px;
    height: 46px;
}

.psdg-top-cell {
    float:left;
    padding: 15px 0 0 0;
    text-align:center;
    width:105px;
    height: 31px;
    border-right: 1px solid #ced9ec;
    color:#fff;
    font: 13px Arial, Helvetica, sans-serif;
}

#psdg-middle {
    margin:0;
    padding: 0;
    width: 578px; 
}

.psdg-left {
    float:left;
    margin:0;
    padding: 10px 0 0 24px;
    width: 129px;
    text-align: left;
    height: 25px;
    border-right: 1px solid #ced9ec;
    border-bottom: 1px solid #b3c1db;
    color:#4d6568;
    font: 13px Arial, Helvetica, sans-serif;
    background: #efefef;
}



.psdg-right {
    float:left;
    margin:0;
    padding: 11px 0 0 0;
    width: 105px;
    text-align:center;
    height: 24px;
    border-right: 1px solid #ced9ec;
    border-bottom: 1px solid #b3c1db;
}

#psdg-bottom {
    clear:both;
    margin:0;
    padding: 0;
    width: 578px;
    height: 48px;
    border-top: 2px solid #FFF;
}


.psdg-bottom-cell {
    float:left;
    padding: 15px 0 0 0;
    text-align:center;
    width:105px;
    height: 33px;
    border-right: 1px solid #ced9ec;
    color:#070707;
    font: 13px Arial, Helvetica, sans-serif;
}



#psdg-footer {
    font-size: 10px;
    color:#8a8a8a;
    margin:0;
    padding: 8px 0 0px 12px;
    width: 566px;
    background: #fbfefe;  
}




/* ==========================================================================
   Media Queries
   ========================================================================== */

@media only screen and (min-width: 35em) {

}

@media print,
       (-o-min-device-pixel-ratio: 5/4),
       (-webkit-min-device-pixel-ratio: 1.25),
       (min-resolution: 120dpi) {

}

/* ==========================================================================
   Helper classes
   ========================================================================== */

.ir {
    background-color: transparent;
    border: 0;
    overflow: hidden;
    *text-indent: -9999px;
}

.ir:before {
    content: "";
    display: block;
    width: 0;
    height: 150%;
}

.hidden {
    display: none !important;
    visibility: hidden;
}

.visuallyhidden {
    border: 0;
    clip: rect(0 0 0 0);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute;
    width: 1px;
}

.visuallyhidden.focusable:active,
.visuallyhidden.focusable:focus {
    clip: auto;
    height: auto;
    margin: 0;
    overflow: visible;
    position: static;
    width: auto;
}

.invisible {
    visibility: hidden;
}

.clearfix:before,
.clearfix:after {
    content: " ";
    display: table;
}

.clearfix:after {
    clear: both;
}

.clearfix {
    *zoom: 1;
}

/* ==========================================================================
   Print styles
   ========================================================================== */

@media print {
    * {
        background: transparent !important;
        color: #000 !important; /* Black prints faster: h5bp.com/s */
        box-shadow: none !important;
        text-shadow: none !important;
    }

    a,
    a:visited {
        text-decoration: underline;
    }

    a[href]:after {
        content: " (" attr(href) ")";
    }

    abbr[title]:after {
        content: " (" attr(title) ")";
    }

    /*
     * Don't show links for images, or javascript/internal links
     */

    .ir a:after,
    a[href^="javascript:"]:after,
    a[href^="#"]:after {
        content: "";
    }

    pre,
    blockquote {
        border: 1px solid #999;
        page-break-inside: avoid;
    }

    thead {
        display: table-header-group; /* h5bp.com/t */
    }

    tr,
    img {
        page-break-inside: avoid;
    }

    img {
        max-width: 100% !important;
    }

    @page {
        margin: 0.5cm;
    }

    p,
    h2,
    h3 {
        orphans: 3;
        widows: 3;
    }

    h2,
    h3 {
        page-break-after: avoid;
    }
}
        </style>

        <!--[if lt IE 9]>
            <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
            <script>window.html5 || document.write('<script src="js/vendor/html5shiv.js"><\/script>')</script>
        <![endif]-->
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        <div class="wrapper">
            <header class="container">
                <hgroup>
                    <h1>Cannon</h1>
                </hgroup>
            </header>
            <section class="new">
                <div class="container">
                    <h2><span class="icon_server">&nbsp;</span><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAPEAAAAYCAYAAAArgBtDAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6REFBQzVDQTg4RDc4MTFFMzg4RkQ4QzEyNzYzNjFDOEUiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6REFBQzVDQTk4RDc4MTFFMzg4RkQ4QzEyNzYzNjFDOEUiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpEQUFDNUNBNjhENzgxMUUzODhGRDhDMTI3NjM2MUM4RSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpEQUFDNUNBNzhENzgxMUUzODhGRDhDMTI3NjM2MUM4RSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Ph7B4SMAAAmqSURBVHja7FyNleQm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"></h2>
                    <form action="" method="post">
                    <table border="0" cellspacing="0" cellpadding="0" align="center">
                        <tr>
                            <td class="txt">NOMBRE DEL PROYECTO</td>
                            <td><input type="text" name="project"></td>
                        </tr>
                        <tr>
                            <td class="txt">URL DEL PROYECTO</td>
                            <td><input type="text" name="url"></td>
                        </tr>
                        <tr>
                            <td class="txt">PUERTO</td>
                            <td><input type="text" name="port"></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><input type="submit" value="Enviar"></td>
                        </tr>
                    </table>

                    </form>
                </div>
                
            </section>
            <section class="list">
                <div class="container">
                    <h2><span class="icon_list">&nbsp;</span><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAVAAAAAYCAYAAACslKVsAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RUIzQzkwNkU4RDc4MTFFMzhENDJGRENEOTZEMTBBOTAiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RUIzQzkwNkY4RDc4MTFFMzhENDJGRENEOTZEMTBBOTAiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpFQjNDOTA2QzhENzgxMUUzOEQ0MkZEQ0Q5NkQxMEE5MCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpFQjNDOTA2RDhENzgxMUUzOEQ0MkZEQ0Q5NkQxMEE5MCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Ptpz4sUAAAp7SURBVHja7F2Ncewm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"></h2>
                    <div id="psdgraphics-com-table">

                    <div id="psdg-top">
                    <div class="psdg-top-cell" style="width:129px; text-align:left; padding-left: 24px;">PROYECTO</div>
                    <div class="psdg-top-cell">PUERTO</div>
                    <div class="psdg-top-cell">PID</div>
                    <div class="psdg-top-cell">STATUS</div>
                    <div class="psdg-top-cell" style="border:none;">ACCIONES</div>
                    </div>


                    <div id="psdg-middle">


				<?php
					if (!file_exists($file)) {
						mkdir(".cannon/", 0777);
						writefile($file,'');		
					} 
					$content = contentfile($file);
					$servers = json_decode($content,true);
					if (is_array($servers)):
					foreach ($servers as $value):
				?>
				<div class="psdg-left"><?= $value['project']?></div>
                <div class="psdg-right"><?= $value['port']?></div>
                <div class="psdg-right">
				<?php
					if (isset($value['pid'])) {
						echo $value['pid'];
					}
				?>                	
                </div>
                <div class="psdg-right">
				<?php
					if (isset($value['pid'])) {
						if (status($value['project'], $value['port'], $value['url'], $value['pid'], $file)) {
							echo "Running";
						} else {
							echo "Stop";
						}
					}
				?>	                	
                </div>	
                <div class="psdg-right">
                	<a href="cannon.php?project=<?=$value['project']?>&action=start" class="start iconaction"></a>
                	<a href="cannon.php?project=<?=$value['project']?>&action=stop" class="stop iconaction"></a>
                	<a href="cannon.php?project=<?=$value['project']?>&action=remove" class="remove iconaction"></a>
                </div>			
				
				<?php
					endforeach;
					endif;
				?>
                    </div>


                    <div id="psdg-footer">
                        &nbsp;
                    </div>
                    <div class="clearfix"></div>

                    </div>
                </div>                
            </section>
            <footer>
                <p>© Copyright 2014 Cannon Todos los derechos reservados.</p>
            </footer>
        </div>
    </body>
</html>