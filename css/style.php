<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: style.php,v 1.6 2004/07/07 17:54:40 jact Exp $
 */

/**
 * style.php
 ********************************************************************
 * Contains the css styles of OpenClinic program pages
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

  // This method is slower than stylesheet incrustation
  //require_once("../shared/read_settings.php");
  //header("Content-Type: text/css");
?>
body {
  margin: 0;
  padding: 0;
  background: <?php echo STYLE_BODY_BG_COLOR; ?>;
  color: <?php echo STYLE_BODY_FONT_COLOR; ?>;
  font-family: <?php echo preg_replace("/;$/", "", STYLE_BODY_FONT_FAMILY); ?>;
  font-size: <?php echo STYLE_BODY_FONT_SIZE; ?>pt;
}

#header h1 {
  background: <?php echo STYLE_TITLE_BG_COLOR; ?>;
  color: <?php echo STYLE_TITLE_FONT_COLOR; ?>;
  font-family: <?php echo preg_replace("/;$/", "", STYLE_TITLE_FONT_FAMILY); ?>;
  font-size: <?php echo STYLE_TITLE_FONT_SIZE; ?>pt;
  text-align: <?php echo STYLE_TITLE_TEXT_ALIGN; ?>;
  font-weight: <?php echo (STYLE_TITLE_FONT_BOLD ? "bold" : "normal"); ?>;
  margin-top: 0;
}

.error {
  color: <?php echo STYLE_ERROR_COLOR; ?>;
  font-weight: bold;
}

#sideBar, .even {
  background: <?php echo STYLE_NAVBAR_BG_COLOR; ?>;
  color: <?php echo STYLE_NAVBAR_FONT_COLOR; ?>;
  font-family: <?php echo preg_replace("/;$/", "", STYLE_NAVBAR_FONT_FAMILY); ?>;
  font-size: <?php echo STYLE_NAVBAR_FONT_SIZE; ?>pt;
}

#tabs a, th {
  background: <?php echo STYLE_TAB_BG_COLOR; ?>;
  color: <?php echo STYLE_TAB_FONT_COLOR; ?>;
  font-family: <?php echo preg_replace("/;$/", "", STYLE_TAB_FONT_FAMILY); ?>;
  font-size: <?php echo STYLE_TAB_FONT_SIZE; ?>pt;
  font-weight: <?php echo (STYLE_TAB_FONT_BOLD ? "bold" : "normal"); ?>;
}

.advice, .message {
  font-size: <?php echo (STYLE_BODY_FONT_SIZE - 1); ?>pt;
}

img {
  border: none;
}

a:link, a:visited {
  color: <?php echo STYLE_BODY_LINK_COLOR; ?>;
}

#sideBar a:link, #sideBar a:visited, #sourceForgeLinks a {
  color: <?php echo STYLE_NAVBAR_LINK_COLOR; ?>;
}

#tabs a:link, #tabs a:visited {
  color: <?php echo STYLE_TAB_LINK_COLOR; ?>;
  text-decoration: none;
}

#tabs a:hover {
  text-decoration: underline;
}

.even a {
  font-size: <?php echo STYLE_BODY_FONT_SIZE; ?>pt;
}

table {
  border-collapse: collapse;
  padding: <?php echo STYLE_TABLE_CELL_PADDING; ?>px;
  border: <?php echo STYLE_TABLE_BORDER_WIDTH; ?>px solid <?php echo STYLE_TABLE_BORDER_COLOR; ?>;
}

td, th, td.odd {
  padding: <?php echo STYLE_TABLE_CELL_PADDING; ?>px;
  border: <?php echo STYLE_TABLE_BORDER_WIDTH; ?>px solid <?php echo STYLE_TABLE_BORDER_COLOR; ?>;
  font-size: <?php echo STYLE_BODY_FONT_SIZE; ?>pt;
}

td.odd {
  font-family: <?php echo preg_replace("/;$/", "", STYLE_NAVBAR_FONT_FAMILY); ?>;
  font-size: <?php echo STYLE_NAVBAR_FONT_SIZE; ?>pt;
}

.sqlcode {
  font-family: "Courier New", Courier, monospace;
  font-size: 10pt;
  white-space: pre;
  text-align: left;
}

.noWrap {
  white-space: nowrap;
}

/* Fancy form styles for IE and Mozilla */
input, textarea, select {
  font-family: <?php echo preg_replace("/;$/", "", STYLE_NAVBAR_FONT_FAMILY); ?>;
  font-size: <?php echo STYLE_NAVBAR_FONT_SIZE; ?>pt;
  border-width: 1px;
  border-color: <?php echo STYLE_TABLE_BORDER_COLOR; ?>;
  background: <?php echo STYLE_TAB_BG_COLOR; ?>;
  color: <?php echo STYLE_TAB_FONT_COLOR; ?>;
}
<?php
if (eregi("MSIE", $_SERVER['HTTP_USER_AGENT']))
{
?>
body {
  scrollbar-base-color: <?php echo STYLE_BODY_BG_COLOR; ?>;
  scrollbar-face-color: <?php echo STYLE_NAVBAR_BG_COLOR; ?>;
  scrollbar-arrow-color: <?php echo STYLE_BODY_FONT_COLOR; ?>;
  scrollbar-track-color: <?php echo STYLE_BODY_FONT_COLOR; ?>;
  scrollbar-shadow-color: <?php echo STYLE_TAB_BG_COLOR; ?>;
  scrollbar-lightshadow-color: <?php echo STYLE_TITLE_FONT_COLOR; ?>;
  scrollbar-darkshadow-color: <?php echo STYLE_TAB_BG_COLOR; ?>;
  scrollbar-highlight-color: <?php echo STYLE_TITLE_FONT_COLOR; ?>;
  scrollbar-3dlight-color: <?php echo STYLE_BODY_FONT_COLOR; ?>;
}
<?php
} // end if
?>
/* CSS2 styles */
input[type="submit"], input[type="button"], input[type="reset"] {
  border-width: <?php echo STYLE_TABLE_BORDER_WIDTH; ?>px;
  font-weight: <?php echo (STYLE_TAB_FONT_BOLD ? "bold" : "normal"); ?>;
}

input[type="radio"], input[type="checkbox"] {
  background: <?php echo STYLE_BODY_BG_COLOR; ?>;
}

label {
  cursor: pointer;
}

#header {
  background: <?php echo STYLE_TITLE_BG_COLOR; ?>;
  color: <?php echo STYLE_TITLE_FONT_COLOR; ?>;
  height: 1px; /* Holly hack */
}

html > body #header {
  height: auto;
}

#subHeader {
  height: 5em;
}

#headerInformation {
  position: absolute;
  top: 0;
  right: 2ex;
}

.menuBar {
  clear: both;
  margin-top: 12px;
  height: 0; /* IE 5.0 paranoid */
}

#tabs {
  margin: 0;
  padding: 0;
  font-size: <?php echo STYLE_TAB_FONT_SIZE; ?>pt;
  font-weight: <?php echo (STYLE_TAB_FONT_BOLD ? "bold" : "normal"); ?>;
}

#tabs li {
  display: inline;
  list-style-type: none;
  margin: 0;
  padding: 0;
}

li#first a, li#first span {
  border-left: <?php echo STYLE_TABLE_BORDER_WIDTH; ?>px solid <?php echo STYLE_TABLE_BORDER_COLOR; ?>;
}

#tabs a, #tabs span {
  border-right: <?php echo STYLE_TABLE_BORDER_WIDTH; ?>px solid <?php echo STYLE_TABLE_BORDER_COLOR; ?>;
  border-top: <?php echo STYLE_TABLE_BORDER_WIDTH; ?>px solid <?php echo STYLE_TABLE_BORDER_COLOR; ?>;
  margin: 0;
  padding: <?php echo STYLE_TABLE_CELL_PADDING; ?>px 10px;
  float: left;
}

#tabs span {
  font-family: <?php echo preg_replace("/;$/", "", STYLE_TAB_FONT_FAMILY); ?>;
  background: <?php echo STYLE_NAVBAR_BG_COLOR; ?>;
  color: <?php echo STYLE_NAVBAR_FONT_COLOR; ?>;
  border-bottom: <?php echo STYLE_TABLE_BORDER_WIDTH; ?>px solid <?php echo STYLE_NAVBAR_BG_COLOR; ?>;
}

#sourceForgeLinks {
  clear: both;
  text-align: right;
  background: <?php echo STYLE_NAVBAR_BG_COLOR; ?>;
  color: <?php echo STYLE_NAVBAR_FONT_COLOR; ?>;
  padding: 0 2ex 4px 0;
  margin: -<?php echo STYLE_TABLE_BORDER_WIDTH; ?>px 0 0 0;
  border: <?php echo STYLE_TABLE_BORDER_WIDTH; ?>px solid <?php echo STYLE_TABLE_BORDER_COLOR; ?>;
}

#sideBar {
  position: relative; /* IE hack */
  padding-left: 1ex;
  margin-top: -<?php echo STYLE_TABLE_BORDER_WIDTH; ?>px;
  width: 150px;
  float: left;
  clear: left;
  border-right: <?php echo STYLE_TABLE_BORDER_WIDTH; ?>px solid <?php echo STYLE_TABLE_BORDER_COLOR; ?>;
  border-bottom: <?php echo STYLE_TABLE_BORDER_WIDTH; ?>px solid <?php echo STYLE_TABLE_BORDER_COLOR; ?>;
  border-left: <?php echo STYLE_TABLE_BORDER_WIDTH; ?>px solid <?php echo STYLE_TABLE_BORDER_COLOR; ?>;
  background: <?php echo STYLE_NAVBAR_BG_COLOR; ?>;
  color: <?php echo STYLE_NAVBAR_FONT_COLOR; ?>;
  font-family: <?php echo preg_replace("/;$/", "", STYLE_NAVBAR_FONT_FAMILY); ?>;
  font-size: <?php echo STYLE_NAVBAR_FONT_SIZE; ?>pt;
}

.linkList a, .linkList a:hover, .linkList .selected {
  line-height: 1.25em;
  display: block;
  margin-bottom: 2ex;
}

.linkList a, .linkList .subnavbar a {
  background: url(../images/bullet_normal.gif) no-repeat 0 3px;
  padding: 0 2px 0 2.5ex;
}

.linkList .selected {
  background: url(../images/bullet_selected.gif) no-repeat 0 3px;
  padding-left: 2.5ex;
}

.linkList .subnavbar a, .linkList .subnavbar {
  margin-left: 2ex;
  padding: 0 2px 0 2.5ex;
}

#sideBarLogo p {
  margin-bottom: 2ex;
  text-align: center;
}

.sideBarLogin {
  margin-top: 0;
  margin-bottom: 3ex !important;
}

.sideBarLogin a {
  display: inline;
}

#mainZone {
  line-height: 1.5em;
  margin-left: 150px;
  padding: 1ex 1em 1ex 2em; /* top right bottom left */
}

#mainZone h1 img, #mainZone h2 img, #mainZone h3 img, #mainZone h4 img, #mainZone h5 img {
  vertical-align: middle;
}

#footer {
  padding: 1ex 4mm;
  margin-left: 150px;
  clear: right;
  text-align: center;
}

.subFooter {
  padding: 1ex 4mm;
  margin-left: 150px;
  text-align: center;
  line-height: 1.25em;
}

.skipLink, .noPrint {
  display: none;
}

.center {
  text-align: center;
}

.center table {
  margin: 0 auto;
  text-align: left;
}

.number {
  text-align: right;
}
