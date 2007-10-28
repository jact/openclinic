<?php
/**
 * Description.php
 *
 * Contains the class Description
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Description.php,v 1.9 2007/10/28 19:42:58 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../lib/Check.php");

/*
 * Description ...
 *
 * Methods:
 *  bool validateData(void)
 *  string getCode(void)
 *  string getDescription(void)
 *  string getDescriptionError(void)
 *  void setCode(string $value)
 *  void setDescription(string $value)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Description
{
  var $_code = "";
  var $_description = "";
  var $_descriptionError = "";

  var $_trans; // to translate htmlspecialchars()

  function Description()
  {
    $this->_trans = array_flip(get_html_translation_table(HTML_SPECIALCHARS));
  }

  /**
   * bool validateData(void)
   *
   * @return boolean true if data is valid, otherwise false.
   * @access public
   */
  function validateData()
  {
    $valid = true;

    if ($this->_description == "")
    {
      $valid = false;
      $this->_descriptionError = _("This is a required field.");
    }

    return $valid;
  }

  /**
   * string getCode(void)
   *
   * @return string
   * @access public
   */
  function getCode()
  {
    return stripslashes(strtr($this->_code, $this->_trans));
  }

  /**
   * string getDescription(void)
   *
   * @return string
   * @access public
   */
  function getDescription()
  {
    return stripslashes(strtr($this->_description, $this->_trans));
  }

  /**
   * string getDescriptionError(void)
   *
   * @return string
   * @access public
   */
  function getDescriptionError()
  {
    return $this->_descriptionError;
  }

  /**
   * void setCode(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setCode($value)
  {
    $this->_code = Check::safeText($value);
  }

  /**
   * void setDescription(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setDescription($value)
  {
    $this->_description = Check::safeText($value);
  }
} // end class
?>
