<?php
/**
 * Theme.php
 *
 * Contains the class Theme
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Theme.php,v 1.16 2007/12/15 14:35:43 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../lib/Check.php");

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
 *  string getCssFile(void)
 *  string getCssFileError(void)
 *  void setCssFile(string $value)
 *  string getCssRules(void)
 *  string getCssRulesError(void)
 *  void setCssRules(string $value)
 *  int getCount(void)
 *  void setCount(int $value)
 *  bool isCssReserved(string $file)
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

  var $_reservedCssFiles = array(
    "ie6_fix.css",
    "openclinic.css",
    "print.css",
    "scheme.css",
    "style.css",
    "wizard.css"
  );

  var $_trans; // to translate htmlspecialchars()

  function Theme()
  {
    $this->_trans = array_flip(get_html_translation_table(HTML_SPECIALCHARS));
  }

  /*
   * bool validateData(void)
   *
   * @return boolean true if data is valid, otherwise false.
   * @access public
   */
  function validateData()
  {
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
    elseif ($this->isCssReserved($this->_cssFile))
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
   * string getCssFile(void)
   *
   * @return string css file
   * @access public
   */
  function getCssFile()
  {
    return stripslashes(strtr($this->_cssFile, $this->_trans));
  }

  /**
   * string getCssFileError(void)
   *
   * @return string css file error text
   * @access public
   */
  function getCssFileError()
  {
    return $this->_cssFileError;
  }

  /**
   * void setCssFile(string $value)
   *
   * @param string $value css file
   * @return void
   * @access public
   */
  function setCssFile($value)
  {
    $value = strtolower($value); // sure?
    $this->_cssFile = Check::safeText($value);
  }

  /**
   * string getCssRules(void)
   *
   * @return string css rules
   * @access public
   */
  function getCssRules()
  {
    return stripslashes(strtr($this->_cssRules, $this->_trans));
  }

  /**
   * string getCssRulesError(void)
   *
   * @return string css rules error text
   * @access public
   */
  function getCssRulesError()
  {
    return $this->_cssRulesError;
  }

  /**
   * void setCssRules(string $value)
   *
   * @param string $value css rules
   * @return void
   * @access public
   */
  function setCssRules($value)
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

  /**
   * bool isCssReserved(string $file)
   *
   * @param string $file
   * @return bool
   * @access public
   * @since 0.8
   */
  function isCssReserved($file)
  {
    return in_array($file, $this->_reservedCssFiles);
  }
} // end class
?>
