<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Setting.php,v 1.7 2004/08/25 17:52:46 jact Exp $
 */

/**
 * Setting.php
 ********************************************************************
 * Contains the class Setting
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

define("OPEN_SETTING_SESSION_TIMEOUT", 20);
define("OPEN_SETTING_ITEMS_PER_PAGE",  10);

require_once("../lib/validator_lib.php");

/**
 * Setting represents the config settings.
 ********************************************************************
 * @author jact <jachavar@terra.es>
 * @access public
 ********************************************************************
 * Methods:
 *  bool validateData(void)
 *  string getClinicName(void)
 *  void setClinicName(string $value)
 *  string getClinicImageUrl(void)
 *  void setClinicImageUrl(string $value)
 *  bool isUseImageSet(void)
 *  void setUseImage(bool $value)
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
 */
class Setting
{
  var $_clinicName = "";
  var $_clinicImageUrl = "";
  var $_useImageSet = false;
  var $_clinicHours = "";
  var $_clinicAddress = "";
  var $_clinicPhone = "";
  var $_clinicUrl = "";
  var $_sessionTimeout = OPEN_SETTING_SESSION_TIMEOUT;
  var $_sessionTimeoutError = "";
  var $_itemsPerPage = OPEN_SETTING_ITEMS_PER_PAGE;
  var $_itemsPerPageError = "";
  var $_version = "";
  var $_lang = "en";
  var $_idTheme = 1;

  var $_trans; // to translate htmlspecialchars()

  function Setting()
  {
    $this->_trans = array_flip(get_html_translation_table(HTML_SPECIALCHARS));
  }

  /**
   * bool validateData(void)
   ********************************************************************
   * @return boolean true if data is valid, otherwise false.
   * @access public
   ********************************************************************
   */
  function validateData()
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
   ********************************************************************
   * @return string
   * @access public
   */
  function getClinicName()
  {
    return stripslashes(strtr($this->_clinicName, $this->_trans));
  }

  /**
   * void setClinicName(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setClinicName($value)
  {
    $this->_clinicName = safeText($value);
  }

  /**
   * string getClinicImageUrl(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getClinicImageUrl()
  {
    return stripslashes(strtr($this->_clinicImageUrl, $this->_trans));
  }

  /**
   * void setClinicImageUrl(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setClinicImageUrl($value)
  {
    $this->_clinicImageUrl = '../images/' . safeText($value);
  }

  /**
   * bool isUseImageSet(void)
   ********************************************************************
   * @return bool
   * @access public
   */
  function isUseImageSet()
  {
    return ($this->_useImageSet == true);
  }

  /**
   * void setUseImage(bool $value)
   ********************************************************************
   * @param bool $value new value to set
   * @return void
   * @access public
   */
  function setUseImage($value)
  {
    $this->_useImageSet = ($value == true);
  }

  /**
   * string getClinicHours(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getClinicHours()
  {
    return stripslashes(strtr($this->_clinicHours, $this->_trans));
  }

  /**
   * void setClinicHours(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setClinicHours($value)
  {
    $this->_clinicHours = safeText($value);
  }

  /**
   * string getClinicAddress(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getClinicAddress()
  {
    return stripslashes(strtr($this->_clinicAddress, $this->_trans));
  }

  /**
   * void setClinicAddress(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setClinicAddress($value)
  {
    $this->_clinicAddress = safeText($value);
  }

  /**
   * string getClinicPhone(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getClinicPhone()
  {
    return stripslashes(strtr($this->_clinicPhone, $this->_trans));
  }

  /**
   * void setClinicPhone(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setClinicPhone($value)
  {
    $this->_clinicPhone = safeText($value);
  }

  /**
   * string getClinicUrl(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getClinicUrl()
  {
    return stripslashes(strtr($this->_clinicUrl, $this->_trans));
  }

  /**
   * void setClinicUrl(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setClinicUrl($value)
  {
    $this->_clinicUrl = safeText($value);
  }

  /**
   * int getSessionTimeout(void)
   ********************************************************************
   * @return int
   * @access public
   */
  function getSessionTimeout()
  {
    return intval($this->_sessionTimeout);
  }

  /**
   * string getSessionTimeoutError(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getSessionTimeoutError()
  {
    return $this->_sessionTimeoutError;
  }

  /**
   * void setSessionTimeout(int $value)
   ********************************************************************
   * @param int $value new value to set
   * @return void
   * @access public
   */
  function setSessionTimeout($value)
  {
    $temp = intval($value);
    $this->_sessionTimeout = (($temp == 0) ? OPEN_SETTING_SESSION_TIMEOUT : $temp);
  }

  /**
   * int getItemsPerPage(void)
   ********************************************************************
   * @return int
   * @access public
   */
  function getItemsPerPage()
  {
    return intval($this->_itemsPerPage);
  }

  /**
   * string getItemsPerPageError(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getItemsPerPageError()
  {
    return $this->_itemsPerPageError;
  }

  /**
   * void setItemsPerPage(int $value)
   ********************************************************************
   * @param int $value new value to set
   * @return void
   * @access public
   */
  function setItemsPerPage($value)
  {
    $temp = intval($value);
    $this->_itemsPerPage = (($temp < 0) ? OPEN_SETTING_ITEMS_PER_PAGE : $temp);
  }

  /**
   * string getVersion(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getVersion()
  {
    return stripslashes(strtr($this->_version, $this->_trans));
  }

  /**
   * void setVersion(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setVersion($value)
  {
    $this->_version = safeText($value);
  }

  /**
   * string getLanguage(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getLanguage()
  {
    return stripslashes(strtr($this->_lang, $this->_trans));
  }

  /**
   * void setLanguage(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setLanguage($value)
  {
    $this->_lang = safeText($value);
  }

  /**
   * int getIdTheme(void)
   ********************************************************************
   * @return int
   * @access public
   */
  function getIdTheme()
  {
    return intval($this->_idTheme);
  }

  /**
   * void setIdTheme(int $value)
   ********************************************************************
   * @param int $value new value to set
   * @return void
   * @access public
   */
  function setIdTheme($value)
  {
    $temp = intval($value);
    $this->_idTheme = (($temp == 0) ? 1 : $temp);
  }
} // end class
?>
