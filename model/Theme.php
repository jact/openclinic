<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Theme.php,v 1.13 2006/03/26 16:12:44 jact Exp $
 */

/**
 * Theme.php
 *
 * Contains the class Theme
 *
 * @author jact <jachavar@gmail.com>
 */

require_once("../lib/Check.php");

// @todo should be a class constant
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
 *
 * Methods:
 *  bool validateData(void)
 *  int getId(void)
 *  void setId(int $value)
 *  string getName(void)
 *  string getNameError(void)
 *  void setName(string $value)
 *  string getCSSFile(void)
 *  string getCSSFileError(void)
 *  void setCSSFile(string $value)
 *  string getCSSRules(void)
 *  string getCSSRulesError(void)
 *  void setCSSRules(string $value)
 *  int getCount(void)
 *  void setCount(int $value)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Theme
{
  var $_id = 0;
  var $_name = "";
  var $_nameError = "";
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
   *
   * @global array $reservedCSSFiles
   * @return boolean true if data is valid, otherwise false.
   * @access public
   */
  function validateData()
  {
    global $reservedCSSFiles;

    $valid = true;

    if ($this->_name == "")
    {
      $valid = false;
      $this->_nameError = _("This is a required field.");
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
   * int getId(void)
   *
   * @return int id theme
   * @access public
   */
  function getId()
  {
    return intval($this->_id);
  }

  /**
   * void setId(int $value)
   *
   * @param int $value id theme
   * @return void
   * @access public
   */
  function setId($value)
  {
    $this->_id = intval($value);
  }

  /**
   * string getName(void)
   *
   * @return string theme name
   * @access public
   */
  function getName()
  {
    return stripslashes(strtr($this->_name, $this->_trans));
  }

  /**
   * string getNameError(void)
   *
   * @return string theme name error text
   * @access public
   */
  function getNameError()
  {
    return $this->_nameError;
  }

  /**
   * void setName(string $value)
   *
   * @param string $value theme name
   * @return void
   * @access public
   */
  function setName($value)
  {
    $this->_name = Check::safeText($value);
  }

  /**
   * string getCSSFile(void)
   *
   * @return string css file
   * @access public
   */
  function getCSSFile()
  {
    return stripslashes(strtr($this->_cssFile, $this->_trans));
  }

  /**
   * string getCSSFileError(void)
   *
   * @return string css file error text
   * @access public
   */
  function getCSSFileError()
  {
    return $this->_cssFileError;
  }

  /**
   * void setCSSFile(string $value)
   *
   * @param string $value css file
   * @return void
   * @access public
   */
  function setCSSFile($value)
  {
    $value = strtolower($value); // sure?
    $this->_cssFile = Check::safeText($value);
  }

  /**
   * string getCSSRules(void)
   *
   * @return string css rules
   * @access public
   */
  function getCSSRules()
  {
    return stripslashes(strtr($this->_cssRules, $this->_trans));
  }

  /**
   * string getCSSRulesError(void)
   *
   * @return string css rules error text
   * @access public
   */
  function getCSSRulesError()
  {
    return $this->_cssRulesError;
  }

  /**
   * void setCSSRules(string $value)
   *
   * @param string $value css rules
   * @return void
   * @access public
   */
  function setCSSRules($value)
  {
    $this->_cssRules = Check::safeText($value);
  }

  /**
   * int getCount(void)
   *
   * @return int count
   * @access public
   */
  function getCount()
  {
    return intval($this->_count);
  }

  /**
   * void setCount(int $value)
   *
   * @param int $value count
   * @return void
   * @access public
   */
  function setCount($value)
  {
    $this->_count = intval($value);
  }
} // end class
?>
