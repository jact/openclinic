<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Setting.php,v 1.2 2004/04/18 14:40:46 jact Exp $
 */

/**
 * Setting.php
 ********************************************************************
 * Contains the class Setting
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

define("SETTING_SESSION_TIMEOUT", 20);
define("SETTING_ITEMS_PER_PAGE", 10);

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
 *  bool isUseImageSet(void)
 *  string getClinicHours(void)
 *  string getClinicAddress(void)
 *  string getClinicPhone(void)
 *  string getClinicUrl(void)
 *  int getSessionTimeout(void)
 *  string getSessionTimeoutError(void)
 *  int getItemsPerPage(void)
 *  string getItemsPerPageError(void)
 *  string getVersion(void)
 *  string getLanguage(void)
 *  int getIdTheme(void)
 *  void setClinicName(string $value)
 *  void setClinicImageUrl(string $value)
 *  void setUseImage(bool $value)
 *  void setClinicHours(string $value)
 *  void setClinicAddress(string $value)
 *  void setClinicPhone(string $value)
 *  void setClinicUrl(string $value)
 *  void setSessionTimeout(string $value)
 *  void setItemsPerPage(string $value)
 *  void setVersion(string $value)
 *  void setLanguage(string $value)
 *  void setIdTheme(string $value)
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
  var $_sessionTimeout = SETTING_SESSION_TIMEOUT;
  var $_sessionTimeoutError = "";
  var $_itemsPerPage = SETTING_ITEMS_PER_PAGE;
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
    elseif (strrpos($this->_sessionTimeout, "."))
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
    elseif (strrpos($this->_itemsPerPage, "."))
    {
      $valid = false;
      $this->_itemsPerPageError = _("This field must not contain a decimal point.");
    }
    elseif ($this->_itemsPerPage <= 0)
    {
      $valid = false;
      $this->_itemsPerPageError = _("This field must be greater than zero.");
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
   * void setClinicName(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setClinicName($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_clinicName = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
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
    $this->_clinicImageUrl = '../images/' . trim($value);
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
   * void setClinicHours(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setClinicHours($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_clinicHours = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
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
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_clinicAddress = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
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
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_clinicPhone = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
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
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_clinicUrl = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setSessionTimeout(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setSessionTimeout($value)
  {
    $temp = intval($value);
    $this->_sessionTimeout = (($temp == 0) ? SETTING_SESSION_TIMEOUT : $temp);
  }

  /**
   * void setItemsPerPage(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setItemsPerPage($value)
  {
    $temp = intval($value);
    $this->_itemsPerPage = (($temp == 0) ? SETTING_ITEMS_PER_PAGE : $temp);
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
    $this->_version = trim($value);
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
    $this->_lang = trim($value);
  }

  /**
   * void setIdTheme(string $value)
   ********************************************************************
   * @param string $value new value to set
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
