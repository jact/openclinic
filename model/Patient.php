<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Patient.php,v 1.1 2004/02/18 19:44:28 jact Exp $
 */

/**
 * Patient.php
 ********************************************************************
 * Contains the class Patient
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 18/02/04 20:44
 */

/*
 * Patient contains business rules for patient data validation.
 ********************************************************************
 * @author jact <jachavar@terra.es>
 * @version 0.5
 * @access public
 ********************************************************************
 * Methods:
 *  bool validateData(void)
 *  int getIdPatient(void)
 *  string getCollegiateNumber(void)
 *  string getNIF(void)
 *  string getFirstName(void)
 *  string getFirstNameError(void)
 *  string getSurname1(void)
 *  string getSurname1Error(void)
 *  string getSurname2(void)
 *  string getSurname2Error(void)
 *  string getAddress(void)
 *  string getPhone(void)
 *  string getSex(void)
 *  string getRace(void)
 *  string getBirthDate(bool $view = true)
 *  string getBirthDateError(void)
 *  int getAge(void)
 *  string getBirthPlace(void)
 *  string getDeceaseDate(bool $view = true)
 *  string getDeceaseDateError(void)
 *  string getNTS(void)
 *  string getNSS(void)
 *  string getFamilySituation(void)
 *  string getLabourSituation(void)
 *  string getEducation(void)
 *  string getInsuranceCompany(void)
 *  void setIdPatient(int $value)
 *  void setCollegiateNumber(string $value)
 *  void setNIF(string $value)
 *  void setFirstName(string $value)
 *  void setSurname1(string $value)
 *  void setSurname2(string $value)
 *  void setAddress(string $value)
 *  void setPhone(string $value)
 *  void setSex(string $value)
 *  void setRace(string $value)
 *  void setBirthDate(string $value)
 *  void setBirthDateFromParts(string $month, string $day, string $year)
 *  void setAge(string $value)
 *  void setBirthPlace(string $value)
 *  void setDeceaseDate(string $value)
 *  void setDeceaseDateFromParts(string $month, string $day, string $year)
 *  void setNTS(string $value)
 *  void setNSS(string $value)
 *  void setFamilySituation(string $value)
 *  void setLabourSituation(string $value)
 *  void setEducation(string $value)
 *  void setInsuranceCompany(string $value)
 */
class Patient
{
  var $_idPatient = 0;
  var $_collegiateNumber = "";
  var $_nif = "";
  var $_firstName = "";
  var $_firstNameError = "";
  var $_surname1 = "";
  var $_surname1Error = "";
  var $_surname2 = "";
  var $_surname2Error = "";
  var $_address = "";
  var $_phone = "";
  var $_sex = "";
  var $_race = "";
  var $_birthDate = "";
  var $_birthDateError = "";
  var $_age = 0;
  var $_birthPlace = "";
  var $_deceaseDate = "";
  var $_deceaseDateError = "";
  var $_nts = "";
  var $_nss = "";
  var $_familySituation = "";
  var $_labourSituation = "";
  var $_education = "";
  var $_insuranceCompany = "";
  //var $_createDate = "";
  //var $_lastUpdateDate = "";

  var $_trans; // to translate htmlspecialchars()

  function Patient()
  {
    $this->_trans = array_flip(get_html_translation_table(HTML_SPECIALCHARS));
  }

  /**
   * bool validateData(void)
   ********************************************************************
   * @return boolean true if data is valid, otherwise false.
   * @access public
   */
  function validateData()
  {
    $valid = true;

    if ($this->_firstName == "")
    {
      $valid = false;
      $this->_firstNameError = _("This is a required field.");
    }

    if ($this->_surname1 == "")
    {
      $valid = false;
      $this->_surname1Error = _("This is a required field.");
    }

    if ($this->_surname2 == "")
    {
      $valid = false;
      $this->_surname2Error = _("This is a required field.");
    }

    if ($this->_birthDate != "" && $this->_birthDate != "0000-00-00")
    {
      list($year, $month, $day) = sscanf($this->_birthDate, "%d-%d-%d");
      if ( !checkdate($month, $day, $year) )
      {
        $valid = false;
        $this->_birthDateError = _("This field is not correct.");
      }
    }

    if ($this->_deceaseDate != "" && $this->_deceaseDate != "0000-00-00")
    {
      list($year, $month, $day) = sscanf($this->_deceaseDate, "%d-%d-%d");
      if ( !checkdate($month, $day, $year) )
      {
        $valid = false;
        $this->_deceaseDateError = _("This field is not correct.");
      }
    }

    if (($this->_birthDate != "" && $this->_birthDate != "0000-00-00" && $this->_deceaseDate != "" && $this->_deceaseDate != "0000-00-00") && ($this->_birthDate > $this->_deceaseDate))
    {
      $valid = false;
      $this->_deceaseDateError = sprintf(_("%s must be greater or equal than %s."), _("Decease Date"), _("Birth Date"));
    }

    return $valid;
  }

  /**
   * int getIdPatient(void)
   ********************************************************************
   * @return int
   * @access public
   */
  function getIdPatient()
  {
    return intval($this->_idPatient);
  }

  /**
   * string getCollegiateNumber(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getCollegiateNumber()
  {
    return stripslashes(strtr($this->_collegiateNumber, $this->_trans));
  }

  /**
   * string getNIF(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getNIF()
  {
    return stripslashes(strtr($this->_nif, $this->_trans));
  }

  /**
   * string getFirstName(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getFirstName()
  {
    return stripslashes(strtr($this->_firstName, $this->_trans));
  }

  /**
   * string getFirstNameError(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getFirstNameError()
  {
    return $this->_firstNameError;
  }

  /**
   * string getSurname1(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getSurname1()
  {
    return stripslashes(strtr($this->_surname1, $this->_trans));
  }

  /**
   * string getSurname1Error(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getSurname1Error()
  {
    return $this->_surname1Error;
  }

  /**
   * string getSurname2(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getSurname2()
  {
    return stripslashes(strtr($this->_surname2, $this->_trans));
  }

  /**
   * string getSurname2Error(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getSurname2Error()
  {
    return $this->_surname2Error;
  }

  /**
   * string getAddress(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getAddress()
  {
    return stripslashes(strtr($this->_address, $this->_trans));
  }

  /**
   * string getPhone(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getPhone()
  {
    return stripslashes(strtr($this->_phone, $this->_trans));
  }

  /**
   * string getSex(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getSex()
  {
    return stripslashes(strtr($this->_sex, $this->_trans));
  }

  /**
   * string getRace(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getRace()
  {
    return stripslashes(strtr($this->_race, $this->_trans));
  }

  /**
   * string getBirthDate(bool $view = true)
   ********************************************************************
   * @param bool $view (optional) to view in screen or to save in database
   * @return string
   * @access public
   */
  function getBirthDate($view = true)
  {
    if ($view)
    {
      return ($this->_birthDate != "0000-00-00") ? ereg_replace('^([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})$', '\3-\2-\1', $this->_birthDate) : "";
    }
    else
    {
      return $this->_birthDate;
    }
  }

  /**
   * string getBirthDateError(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getBirthDateError()
  {
    return $this->_birthDateError;
  }

  /**
   * int getAge(void)
   ********************************************************************
   * @return int
   * @access public
   */
  function getAge()
  {
    return intval($this->_age);
  }

  /**
   * string getBirthPlace(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getBirthPlace()
  {
    return stripslashes(strtr($this->_birthPlace, $this->_trans));
  }

  /**
   * string getDeceaseDate(bool $view = true)
   ********************************************************************
   * @param bool $view (optional) to view in screen or to save in database
   * @return string
   * @access public
   */
  function getDeceaseDate($view = true)
  {
    if ($view)
    {
      return ($this->_deceaseDate != "0000-00-00") ? ereg_replace('^([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})$', '\3-\2-\1', $this->_deceaseDate) : "";
    }
    else
    {
      return $this->_deceaseDate;
    }
  }

  /**
   * string getDeceaseDateError(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getDeceaseDateError()
  {
    return $this->_deceaseDateError;
  }

  /**
   * string getNTS(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getNTS()
  {
    return stripslashes(strtr($this->_nts, $this->_trans));
  }

  /**
   * string getNSS(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getNSS()
  {
    return stripslashes(strtr($this->_nss, $this->_trans));
  }

  /**
   * string getFamilySituation(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getFamilySituation()
  {
    return stripslashes(strtr($this->_familySituation, $this->_trans));
  }

  /**
   * string getLabourSituation(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getLabourSituation()
  {
    return stripslashes(strtr($this->_labourSituation, $this->_trans));
  }

  /**
   * string getEducation(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getEducation()
  {
    return stripslashes(strtr($this->_education, $this->_trans));
  }

  /**
   * string getInsuranceCompany(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getInsuranceCompany()
  {
    return stripslashes(strtr($this->_insuranceCompany, $this->_trans));
  }

  /**
   * void setIdPatient(int $value)
   ********************************************************************
   * @param int $value new value to set
   * @return void
   * @access public
   */
  function setIdPatient($value)
  {
    $this->_idPatient = intval($value);
  }

  /**
   * void setCollegiateNumber(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setCollegiateNumber($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_collegiateNumber = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setNIF(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setNIF($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_nif = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setFirstName(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setFirstName($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_firstName = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setSurname1(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setSurname1($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_surname1 = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setSurname2(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setSurname2($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_surname2 = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setAddress(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setAddress($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_address = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setPhone(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setPhone($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_phone = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setSex(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setSex($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_sex = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setRace(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setRace($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_race = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setBirthDate(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setBirthDate($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_birthDate = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setBirthDateFromParts(string $month, string $day, string $year)
   ********************************************************************
   * @param string $month
   * @param string $day
   * @param string $year
   * @return void
   * @access public
   */
  function setBirthDateFromParts($month, $day, $year)
  {
    if (strlen($year) > 1 && strlen($year) < 3)
    {
      $year = 1900 + intval($year);
    }
    $this->_birthDate = sprintf("%04d-%02d-%02d", intval($year), intval($month), intval($day));
  }

  /**
   * void setAge(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setAge($value)
  {
    $this->_age = intval($value);
  }

  /**
   * void setBirthPlace(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setBirthPlace($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_birthPlace = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setDeceaseDate(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setDeceaseDate($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_deceaseDate = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setDeceaseDateFromParts(string $month, string $day, string $year)
   ********************************************************************
   * @param string $month
   * @param string $day
   * @param string $year
   * @return void
   * @access public
   */
  function setDeceaseDateFromParts($month, $day, $year)
  {
    if (strlen($year) > 1 && strlen($year) < 3)
    {
      $year = 1900 + intval($year);
    }
    $this->_deceaseDate = sprintf("%04d-%02d-%02d", intval($year), intval($month), intval($day));
  }

  /**
   * void setNTS(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setNTS($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_nts = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setNSS(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setNSS($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_nss = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setFamilySituation(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setFamilySituation($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_familySituation = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setLabourSituation(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setLabourSituation($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_labourSituation = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setEducation(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setEducation($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_education = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setInsuranceCompany(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setInsuranceCompany($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_insuranceCompany = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * string getLastUpdateDate(bool $view = true)
   ********************************************************************
   * @param bool $view if true, value is showed in program, else, value is saved in db
   * @return string last update date of the patient data
   * @access public
   */
/*  function getLastUpdateDate($view = true)
  {
    if ($view)
    {
      return ($this->_lastUpdateDate != "0000-00-00") ? ereg_replace('^([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})$', '\3-\2-\1', $this->_lastUpdateDate) : "";
    }
    else
    {
      return $this->_lastUpdateDate;
    }
  }
*/
  /**
   * void setLastUpdateDate(string $value)
   ********************************************************************
   * @param string $value last update date of the patient data
   * @return void
   * @access public
   */
  /*function setLastUpdateDate($value)
  {
    $this->_lastUpdateDate = ereg_replace('^([0-9]{1,2})-([0-9]{1,2})-([0-9]{2,4})$', '\3-\2-\1', trim($value));
  }*/
} // end class
?>
