<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: History.php,v 1.2 2004/04/18 14:40:46 jact Exp $
 */

/**
 * History.php
 ********************************************************************
 * Contains the class History
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

/*
 * History represents personal and family antecedents of a patient.
 ********************************************************************
 * @author jact <jachavar@terra.es>
 * @access public
 ********************************************************************
 * Methods:
 *  bool validateData(void)
 *  int getIdPatient(void)
 *  string getBirthGrowth(void)
 *  string getGrowthSexuality(void)
 *  string getFeed(void)
 *  string getHabits(void)
 *  string getPeristalticConditions(void)
 *  string getPsychological(void)
 *  string getChildrenComplaint(void)
 *  string getVenerealDisease(void)
 *  string getAccidentSurgicalOperation(void)
 *  string getMedicinalIntolerance(void)
 *  string getMentalIllness(void)
 *  string getParentsStatusHealth(void)
 *  string getBrothersStatusHealth(void)
 *  string getSpouseChildsStatusHealth(void)
 *  string getFamilyIllness(void)
 *  void setIdPatient(int $value)
 *  void setBirthGrowth(string $value)
 *  void setGrowthSexuality(string $value)
 *  void setFeed(string $value)
 *  void setHabits(string $value)
 *  void setPeristalticConditions(string $value)
 *  void setPsychological(string $value)
 *  void setChildrenComplaint(string $value)
 *  void setVenerealDisease(string $value)
 *  void setAccidentSurgicalOperation(string $value)
 *  void setMedicinalIntolerance(string $value)
 *  void setMentalIllness(string $value)
 *  void setParentsStatusHealth(string $value)
 *  void setBrothersStatusHealth(string $value)
 *  void setSpouseChildsStatusHealth(string $value)
 *  void setFamilyIllness(string $value)
 */
class History
{
  var $_idPatient = 0;

  var $_birthGrowth = "";
  var $_growthSexuality = "";
  var $_feed = "";
  var $_habits = "";
  var $_peristalticConditions = "";
  var $_psychological = "";

  var $_childrenComplaint = "";
  var $_venerealDisease = "";
  var $_accidentSurgicalOperation = "";
  var $_medicinalIntolerance = "";
  var $_mentalIllness = "";

  var $_parentsStatusHealth = "";
  var $_brothersStatusHealth = "";
  var $_spouseChildsStatusHealth = "";
  var $_familyIllness = "";

  var $_trans; // to translate htmlspecialchars()

  function History()
  {
    $this->_trans = array_flip(get_html_translation_table(HTML_SPECIALCHARS));
  }

  /*
   * bool validateData(void)
   ********************************************************************
   * @return boolean true if data is valid, otherwise false.
   * @access public
   */
  function validateData()
  {
    $valid = true;

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
   * string getBirthGrowth(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getBirthGrowth()
  {
    return stripslashes(strtr($this->_birthGrowth, $this->_trans));
  }

  /**
   * string getGrowthSexuality(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getGrowthSexuality()
  {
    return stripslashes(strtr($this->_growthSexuality, $this->_trans));
  }

  /**
   * string getFeed(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getFeed()
  {
    return stripslashes(strtr($this->_feed, $this->_trans));
  }

  /**
   * string getHabits(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getHabits()
  {
    return stripslashes(strtr($this->_habits, $this->_trans));
  }

  /**
   * string getPeristalticConditions(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getPeristalticConditions()
  {
    return stripslashes(strtr($this->_peristalticConditions, $this->_trans));
  }

  /**
   * string getPsychological(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getPsychological()
  {
    return stripslashes(strtr($this->_psychological, $this->_trans));
  }

  /**
   * string getChildrenComplaint(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getChildrenComplaint()
  {
    return stripslashes(strtr($this->_childrenComplaint, $this->_trans));
  }

  /**
   * string getVenerealDisease(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getVenerealDisease()
  {
    return stripslashes(strtr($this->_venerealDisease, $this->_trans));
  }

  /**
   * string getAccidentSurgicalOperation(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getAccidentSurgicalOperation()
  {
    return stripslashes(strtr($this->_accidentSurgicalOperation, $this->_trans));
  }

  /**
   * string getMedicinalIntolerance(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getMedicinalIntolerance()
  {
    return stripslashes(strtr($this->_medicinalIntolerance, $this->_trans));
  }

  /**
   * string getMentalIllness(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getMentalIllness()
  {
    return stripslashes(strtr($this->_mentalIllness, $this->_trans));
  }

  /**
   * string getParentsStatusHealth(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getParentsStatusHealth()
  {
    return stripslashes(strtr($this->_parentsStatusHealth, $this->_trans));
  }

  /**
   * string getBrothersStatusHealth(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getBrothersStatusHealth()
  {
    return stripslashes(strtr($this->_brothersStatusHealth, $this->_trans));
  }

  /**
   * string getSpouseChildsStatusHealth(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getSpouseChildsStatusHealth()
  {
    return stripslashes(strtr($this->_spouseChildsStatusHealth, $this->_trans));
  }

  /**
   * string getFamilyIllness(void)
   ********************************************************************
   * @return string
   * @access public
   */
  function getFamilyIllness()
  {
    return stripslashes(strtr($this->_familyIllness, $this->_trans));
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
   * void setBirthGrowth(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setBirthGrowth($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_birthGrowth = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setGrowthSexuality(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setGrowthSexuality($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_growthSexuality = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setFeed(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setFeed($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_feed = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setHabits(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setHabits($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_habits = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setPeristalticConditions(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setPeristalticConditions($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_peristalticConditions = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setPsychological(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setPsychological($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_psychological = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setChildrenComplaint(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setChildrenComplaint($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_childrenComplaint = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setVenerealDisease(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setVenerealDisease($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_venerealDisease = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setAccidentSurgicalOperation(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setAccidentSurgicalOperation($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_accidentSurgicalOperation = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setMedicinalIntolerance(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setMedicinalIntolerance($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_medicinalIntolerance = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setMentalIllness(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setMentalIllness($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_mentalIllness = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setParentsStatusHealth(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setParentsStatusHealth($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_parentsStatusHealth = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setBrothersStatusHealth(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setBrothersStatusHealth($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_brothersStatusHealth = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setSpouseChildsStatusHealth(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setSpouseChildsStatusHealth($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_spouseChildsStatusHealth = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }

  /**
   * void setFamilyIllness(string $value)
   ********************************************************************
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setFamilyIllness($value)
  {
    $value = trim(htmlspecialchars(strip_tags($value, ALLOWED_HTML_TAGS)));
    $this->_familyIllness = ((get_magic_quotes_gpc()) ? $value : addslashes($value));
  }
} // end class
?>
