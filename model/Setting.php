<?php
/**
 * Setting.php
 *
 * Contains the class Setting
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Setting.php,v 1.18 2013/01/19 10:26:23 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

define("OPEN_SETTING_SESSION_TIMEOUT", 20);
define("OPEN_SETTING_ITEMS_PER_PAGE",  10);

require_once(dirname(__FILE__) . "/../lib/Check.php");

/**
 * Setting represents the config settings.
 *
 * Methods:
 *  bool validateData(void)
 *  string getClinicName(void)
 *  void setClinicName(string $value)
 *  string getClinicHours(void)
 *  void setClinicHours(string $value)
 *  string getClinicAddress(void)
 *  void setClinicAddress(string $value)
 *  string getClinicPhone(void)
 *  void setClinicPhone(string $value)
 *  string getClinicUrl(void)
 *  void setClinicUrl(string $value)
 *  int getSessionTimeout(void)
 *  string getSessionTimeoutError(void)
 *  void setSessionTimeout(int $value)
 *  int getItemsPerPage(void)
 *  string getItemsPerPageError(void)
 *  void setItemsPerPage(int $value)
 *  string getVersion(void)
 *  void setVersion(string $value)
 *  string getLanguage(void)
 *  void setLanguage(string $value)
 *  int getIdTheme(void)
 *  void setIdTheme(int $value)
 *  string __toString(void)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Setting
{
  private $_clinicName = "";
  private $_clinicHours = "";
  private $_clinicAddress = "";
  private $_clinicPhone = "";
  private $_clinicUrl = "";
  private $_sessionTimeout = OPEN_SETTING_SESSION_TIMEOUT;
  private $_sessionTimeoutError = "";
  private $_itemsPerPage = OPEN_SETTING_ITEMS_PER_PAGE;
  private $_itemsPerPageError = "";
  private $_version = "";
  private $_lang = "en";
  private $_idTheme = 1;

  private $_trans; // to translate htmlspecialchars()

  public function Setting()
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

    if ( !is_numeric($this->_sessionTimeout) )
    {
      $valid = false;
      $this->_sessionTimeoutError = _("This field must be numeric.");
    }
    elseif (strrpos($this->_sessionTimeout, ".") || strrpos($this->_sessionTimeout, ","))
    {
      $valid = false;
      $this->_sessionTimeoutError = _("This field must not contain a decimal point.");
    }
    elseif ($this->_sessionTimeout <= 0)
    {
      $valid = false;
      $this->_sessionTimeoutError = _("This field must be greater than zero.");
    }

    if ( !is_numeric($this->_itemsPerPage))
    {
      $valid = false;
      $this->_itemsPerPageError = _("This field must be numeric.");
    }
    elseif (strrpos($this->_itemsPerPage, ".") || strrpos($this->_itemsPerPage, ","))
    {
      $valid = false;
      $this->_itemsPerPageError = _("This field must not contain a decimal point.");
    }

    return $valid;
  }

  /**
   * string getClinicName(void)
   *
   * @return string
   * @access public
   */
  public function getClinicName()
  {
    return stripslashes(strtr($this->_clinicName, $this->_trans));
  }

  /**
   * void setClinicName(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setClinicName($value)
  {
    $this->_clinicName = Check::safeText($value);
  }

  /**
   * string getClinicHours(void)
   *
   * @return string
   * @access public
   */
  public function getClinicHours()
  {
    return stripslashes(strtr($this->_clinicHours, $this->_trans));
  }

  /**
   * void setClinicHours(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setClinicHours($value)
  {
    $this->_clinicHours = Check::safeText($value);
  }

  /**
   * string getClinicAddress(void)
   *
   * @return string
   * @access public
   */
  public function getClinicAddress()
  {
    return stripslashes(strtr($this->_clinicAddress, $this->_trans));
  }

  /**
   * void setClinicAddress(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setClinicAddress($value)
  {
    $this->_clinicAddress = Check::safeText($value);
  }

  /**
   * string getClinicPhone(void)
   *
   * @return string
   * @access public
   */
  public function getClinicPhone()
  {
    return stripslashes(strtr($this->_clinicPhone, $this->_trans));
  }

  /**
   * void setClinicPhone(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setClinicPhone($value)
  {
    $this->_clinicPhone = Check::safeText($value);
  }

  /**
   * string getClinicUrl(void)
   *
   * @return string
   * @access public
   */
  public function getClinicUrl()
  {
    return stripslashes(strtr($this->_clinicUrl, $this->_trans));
  }

  /**
   * void setClinicUrl(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setClinicUrl($value)
  {
    $this->_clinicUrl = Check::safeText($value);
  }

  /**
   * int getSessionTimeout(void)
   *
   * @return int
   * @access public
   */
  public function getSessionTimeout()
  {
    return intval($this->_sessionTimeout);
  }

  /**
   * string getSessionTimeoutError(void)
   *
   * @return string
   * @access public
   */
  public function getSessionTimeoutError()
  {
    return $this->_sessionTimeoutError;
  }

  /**
   * void setSessionTimeout(int $value)
   *
   * @param int $value new value to set
   * @return void
   * @access public
   */
  public function setSessionTimeout($value)
  {
    $temp = intval($value);
    $this->_sessionTimeout = (($temp == 0) ? OPEN_SETTING_SESSION_TIMEOUT : $temp);
  }

  /**
   * int getItemsPerPage(void)
   *
   * @return int
   * @access public
   */
  public function getItemsPerPage()
  {
    return intval($this->_itemsPerPage);
  }

  /**
   * string getItemsPerPageError(void)
   *
   * @return string
   * @access public
   */
  public function getItemsPerPageError()
  {
    return $this->_itemsPerPageError;
  }

  /**
   * void setItemsPerPage(int $value)
   *
   * @param int $value new value to set
   * @return void
   * @access public
   */
  public function setItemsPerPage($value)
  {
    $temp = intval($value);
    $this->_itemsPerPage = (($temp < 0) ? OPEN_SETTING_ITEMS_PER_PAGE : $temp);
  }

  /**
   * string getVersion(void)
   *
   * @return string
   * @access public
   */
  public function getVersion()
  {
    return stripslashes(strtr($this->_version, $this->_trans));
  }

  /**
   * void setVersion(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setVersion($value)
  {
    $this->_version = Check::safeText($value);
  }

  /**
   * string getLanguage(void)
   *
   * @return string
   * @access public
   */
  public function getLanguage()
  {
    return stripslashes(strtr($this->_lang, $this->_trans));
  }

  /**
   * void setLanguage(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setLanguage($value)
  {
    $this->_lang = Check::safeText($value);
  }

  /**
   * int getIdTheme(void)
   *
   * @return int
   * @access public
   */
  public function getIdTheme()
  {
    return intval($this->_idTheme);
  }

  /**
   * void setIdTheme(int $value)
   *
   * @param int $value new value to set
   * @return void
   * @access public
   */
  public function setIdTheme($value)
  {
    $temp = intval($value);
    $this->_idTheme = (($temp == 0) ? 1 : $temp);
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
