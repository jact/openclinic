<?php
/**
 * Description.php
 *
 * Contains the class Description
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Description.php,v 1.10 2013/01/16 19:03:48 jact Exp $
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
 *  string __toString(void)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Description
{
  private $_code = "";
  private $_description = "";
  private $_descriptionError = "";

  private $_trans; // to translate htmlspecialchars()

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
  public function validateData()
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
  public function getCode()
  {
    return stripslashes(strtr($this->_code, $this->_trans));
  }

  /**
   * string getDescription(void)
   *
   * @return string
   * @access public
   */
  public function getDescription()
  {
    return stripslashes(strtr($this->_description, $this->_trans));
  }

  /**
   * string getDescriptionError(void)
   *
   * @return string
   * @access public
   */
  public function getDescriptionError()
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
  public function setCode($value)
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
  public function setDescription($value)
  {
    $this->_description = Check::safeText($value);
  }

  /**
   * string __toString(void)
   *
   * @return string class name
   * @access public
   * @since 0.8
   */
  public function __toString()
  {
    return __CLASS__;
  }
} // end class
?>
