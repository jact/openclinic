<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Theme.php,v 1.8 2004/08/12 11:39:00 jact Exp $
 */

/**
 * Theme.php
 ********************************************************************
 * Contains the class Theme
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

require_once("../lib/validator_lib.php");

$reservedCSSFiles = array(
  "style.css",
  "wizard.css",
  "scheme.css",
  "serialz.css",
  "banter.css",
  "sinorca.css",
  "gazetteer_alt.css"
);

/*
 * Theme represents a look and feel theme.
 ********************************************************************
 * @author jact <jachavar@terra.es>
 * @access public
 ********************************************************************
 * Methods:
 *  bool validateData(void)
 *  int getIdTheme(void)
 *  void setIdTheme(int $value)
 *  string getThemeName(void)
 *  string getThemeNameError(void)
 *  void setThemeName(string $value)
 *  string getCSSFile(void)
 *  string getCSSFileError(void)
 *  void setCSSFile(string $value)
 *  string getCSSRules(void)
 *  string getCSSRulesError(void)
 *  void setCSSRules(string $value)
 *  int getCount(void)
 *  void setCount(int $value)
 */
class Theme
{
  var $_idTheme = 0;
  var $_themeName = "";
  var $_themeNameError = "";
  var $_cssFile = "";
  var $_cssFileError = "";
  var $_cssRules = "";
  var $_cssRulesError = "";

  var $_count = 0;

  var $_trans; // to translate htmlspecialchars()

  function Theme()
  {
    $this->_trans = array_flip(get_html_translation_table(HTML_SPECIALCHARS));
  }

  /*
   * bool validateData(void)
   ********************************************************************
   * @global array $reservedCSSFiles
   * @return boolean true if data is valid, otherwise false.
   * @access public
   */
  function validateData()
  {
    global $reservedCSSFiles;

    $valid = true;

    if ($this->_themeName == "")
    {
      $valid = false;
      $this->_themeNameError = _("This is a required field.");
    }

    if ($this->_cssFile == "")
    {
      $valid = false;
      $this->_cssFileError = _("This is a required field.");
    }
    elseif (in_array($this->_cssFile, $reservedCSSFiles))
    {
      $valid = false;
      $this->_cssFileError = _("That filename is reserved for internal use.");
    }

    if ($this->_cssRules == "")
    {
      $valid = false;
      $this->_cssRulesError = _("This is a required field.");
    }

    return $valid;
  }

  /**
   * int getIdTheme(void)
   ********************************************************************
   * @return int id theme
   * @access public
   */
  function getIdTheme()
  {
    return intval($this->_idTheme);
  }

  /**
   * void setIdTheme(int $value)
   ********************************************************************
   * @param int $value id theme
   * @return void
   * @access public
   */
  function setIdTheme($value)
  {
    $this->_idTheme = intval($value);
  }

  /**
   * string getThemeName(void)
   ********************************************************************
   * @return string theme name
   * @access public
   */
  function getThemeName()
  {
    return stripslashes(strtr($this->_themeName, $this->_trans));
  }

  /**
   * string getThemeNameError(void)
   ********************************************************************
   * @return string theme name error text
   * @access public
   */
  function getThemeNameError()
  {
    return $this->_themeNameError;
  }

  /**
   * void setThemeName(string $value)
   ********************************************************************
   * @param string $value theme name
   * @return void
   * @access public
   */
  function setThemeName($value)
  {
    $this->_themeName = safeText($value);
  }

  /**
   * string getCSSFile(void)
   ********************************************************************
   * @return string css file
   * @access public
   */
  function getCSSFile()
  {
    return stripslashes(strtr($this->_cssFile, $this->_trans));
  }

  /**
   * string getCSSFileError(void)
   ********************************************************************
   * @return string css file error text
   * @access public
   */
  function getCSSFileError()
  {
    return $this->_cssFileError;
  }

  /**
   * void setCSSFile(string $value)
   ********************************************************************
   * @param string $value css file
   * @return void
   * @access public
   */
  function setCSSFile($value)
  {
    $value = strtolower($value); // sure?
    $this->_cssFile = safeText($value);
  }

  /**
   * string getCSSRules(void)
   ********************************************************************
   * @return string css rules
   * @access public
   */
  function getCSSRules()
  {
    return stripslashes(strtr($this->_cssRules, $this->_trans));
  }

  /**
   * string getCSSRulesError(void)
   ********************************************************************
   * @return string css rules error text
   * @access public
   */
  function getCSSRulesError()
  {
    return $this->_cssRulesError;
  }

  /**
   * void setCSSRules(string $value)
   ********************************************************************
   * @param string $value css rules
   * @return void
   * @access public
   */
  function setCSSRules($value)
  {
    $this->_cssRules = safeText($value);
  }

  /**
   * int getCount(void)
   ********************************************************************
   * @return int count
   * @access public
   */
  function getCount()
  {
    return intval($this->_count);
  }

  /**
   * void setCount(int $value)
   ********************************************************************
   * @param int $value count
   * @return void
   * @access public
   */
  function setCount($value)
  {
    $value = intval($value);
    $this->_count = $value;
  }
} // end class
?>
