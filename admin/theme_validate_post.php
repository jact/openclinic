<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_validate_post.php,v 1.1 2004/02/19 18:46:04 jact Exp $
 */

/**
 * theme_validate_post.php
 ********************************************************************
 * Validate post data of a theme
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 19/02/04 19:46
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

  $theme->setThemeName($_POST["theme_name"]);
  $_POST["theme_name"] = $theme->getThemeName();


  $theme->setTitleBgColor($_POST["title_bg_color"]);
  $_POST["title_bg_color"] = $theme->getTitleBgColor();

  $theme->setTitleFontFamily($_POST["title_font_family"]);
  $_POST["title_font_family"] = $theme->getTitleFontFamily();

  $theme->setTitleFontSize($_POST["title_font_size"]);
  $_POST["title_font_size"] = $theme->getTitleFontSize();

  $theme->setTitleFontBold(isset($_POST["title_font_bold"]));

  $theme->setTitleFontColor($_POST["title_font_color"]);
  $_POST["title_font_color"] = $theme->getTitleFontColor();

  $theme->setTitleAlign($_POST["title_align"]);


  $theme->setBodyBgColor($_POST["body_bg_color"]);
  $_POST["body_bg_color"] = $theme->getBodyBgColor();

  $theme->setBodyFontFamily($_POST["body_font_family"]);
  $_POST["body_font_family"] = $theme->getBodyFontFamily();

  $theme->setBodyFontSize($_POST["body_font_size"]);
  $_POST["body_font_size"] = $theme->getBodyFontSize();

  $theme->setBodyFontColor($_POST["body_font_color"]);
  $_POST["body_font_color"] = $theme->getBodyFontColor();

  $theme->setBodyLinkColor($_POST["body_link_color"]);
  $_POST["body_link_color"] = $theme->getBodyLinkColor();


  $theme->setErrorColor($_POST["error_color"]);
  $_POST["error_color"] = $theme->getErrorColor();


  $theme->setNavbarBgColor($_POST["navbar_bg_color"]);
  $_POST["navbar_bg_color"] = $theme->getNavbarBgColor();

  $theme->setNavbarFontFamily($_POST["navbar_font_family"]);
  $_POST["navbar_font_family"] = $theme->getNavbarFontFamily();

  $theme->setNavbarFontSize($_POST["navbar_font_size"]);
  $_POST["navbar_font_size"] = $theme->getNavbarFontSize();

  $theme->setNavbarFontColor($_POST["navbar_font_color"]);
  $_POST["navbar_font_color"] = $theme->getNavbarFontColor();

  $theme->setNavbarLinkColor($_POST["navbar_link_color"]);
  $_POST["navbar_link_color"] = $theme->getNavbarLinkColor();


  $theme->setTabBgColor($_POST["tab_bg_color"]);
  $_POST["tab_bg_color"] = $theme->getTabBgColor();

  $theme->setTabFontFamily($_POST["tab_font_family"]);
  $_POST["tab_font_family"] = $theme->getTabFontFamily();

  $theme->setTabFontSize($_POST["tab_font_size"]);
  $_POST["tab_font_size"] = $theme->getTabFontSize();

  $theme->setTabFontBold(isset($_POST["tab_font_bold"]));

  $theme->setTabFontColor($_POST["tab_font_color"]);
  $_POST["tab_font_color"] = $theme->getTabFontColor();

  $theme->setTabLinkColor($_POST["tab_link_color"]);
  $_POST["tab_link_color"] = $theme->getTabLinkColor();


  $theme->setTableBorderColor($_POST["table_border_color"]);
  $_POST["table_border_color"] = $theme->getTableBorderColor();

  $theme->setTableBorderWidth($_POST["table_border_width"]);
  $_POST["table_border_width"] = $theme->getTableBorderWidth();

  $theme->setTableCellPadding($_POST["table_cell_padding"]);
  $_POST["table_cell_padding"] = $theme->getTableCellPadding();

  if ( !$theme->validateData() )
  {
    $pageErrors["theme_name"] = $theme->getThemeNameError();

    $pageErrors["title_bg_color"] = $theme->getTitleBgColorError();
    $pageErrors["title_font_family"] = $theme->getTitleFontFamilyError();
    $pageErrors["title_font_size"] = $theme->getTitleFontSizeError();
    $pageErrors["title_font_color"] = $theme->getTitleFontColorError();

    $pageErrors["body_bg_color"] = $theme->getBodyBgColorError();
    $pageErrors["body_font_family"] = $theme->getBodyFontFamilyError();
    $pageErrors["body_font_size"] = $theme->getBodyFontSizeError();
    $pageErrors["body_font_color"] = $theme->getBodyFontColorError();
    $pageErrors["body_link_color"] = $theme->getBodyLinkColorError();

    $pageErrors["error_color"] = $theme->getErrorColorError();

    $pageErrors["navbar_bg_color"] = $theme->getNavbarBgColorError();
    $pageErrors["navbar_font_family"] = $theme->getNavbarFontFamilyError();
    $pageErrors["navbar_font_size"] = $theme->getNavbarFontSizeError();
    $pageErrors["navbar_font_color"] = $theme->getNavbarFontColorError();
    $pageErrors["navbar_link_color"] = $theme->getNavbarLinkColorError();

    $pageErrors["tab_bg_color"] = $theme->getTabBgColorError();
    $pageErrors["tab_font_family"] = $theme->getTabFontFamilyError();
    $pageErrors["tab_font_size"] = $theme->getTabFontSizeError();
    $pageErrors["tab_font_color"] = $theme->getTabFontColorError();
    $pageErrors["tab_link_color"] = $theme->getTabLinkColorError();

    $pageErrors["table_border_color"] = $theme->getTableBorderColorError();
    $pageErrors["table_border_width"] = $theme->getTableBorderWidthError();
    $pageErrors["table_cell_padding"] = $theme->getTableCellPaddingError();

    $_SESSION["postVars"] = $_POST;
    $_SESSION["pageErrors"] = $pageErrors;

    header("Location: " . $errorLocation);
    exit();
  }
?>
