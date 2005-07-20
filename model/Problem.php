<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Problem.php,v 1.8 2005/07/20 20:24:47 jact Exp $
 */

/**
 * Problem.php
 *
 * Contains the class Problem
 *
 * Author: jact <jachavar@gmail.com>
 */

require_once("../lib/Check.php");

/*
 * Problem represents a medical problem.
 *
 * @author jact <jachavar@gmail.com>
 * @access public
 *
 * Methods:
 *  bool validateData(void)
 *  int getIdProblem(void)
 *  void setIdProblem(int $value)
 *  int getIdPatient(void)
 *  void setIdPatient(int $value)
 *  int getOrderNumber(void)
 *  void setOrderNumber(int $value)
 *  int getIdMember(void)
 *  void setIdMember(int $value)
 *  string getCollegiateNumber(void)
 *  void setCollegiateNumber(string $value)
 *  string getOpeningDate(void)
 *  void setOpeningDate(string $value)
 *  string getClosingDate(void)
 *  void setClosingDate(string $value)
 *  string getMeetingPlace(void)
 *  void setMeetingPlace(string $value)
 *  string getWording(void)
 *  void setWording(string $value)
 *  string getWordingError(void)
 *  string getSubjective(void)
 *  void setSubjective(string $value)
 *  string getObjective(void)
 *  void setObjective(string $value)
 *  string getAppreciation(void)
 *  void setAppreciation(string $value)
 *  string getActionPlan(void)
 *  void setActionPlan(string $value)
 *  string getPrescription(void)
 *  void setPrescription(string $value)
 *  string getLastUpdateDate(void)
 *  void setLastUpdateDate(string $value)
 */
class Problem
{
  var $_idProblem = 0;
  var $_idPatient = 0;
  var $_idMember = 0;
  var $_collegiateNumber = "";
  var $_orderNumber = 0;
  var $_openingDate = "";
  var $_closingDate = "";
  var $_meetingPlace = "";
  var $_wording = "";
  var $_wordingError = "";
  var $_subjective = "";
  var $_objective = "";
  var $_appreciation = "";
  var $_actionPlan = "";
  var $_prescription = "";
  //var $_createDate = "";
  var $_lastUpdateDate = "";

  var $_trans; // to translate htmlspecialchars()

  function Problem()
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

    if ($this->_wording == "")
    {
      $valid = false;
      $this->_wordingError = _("This is a required field.");
    }

    return $valid;
  }

  /**
   * int getIdProblem(void)
   *
   * @return int id problem
   * @access public
   */
  function getIdProblem()
  {
    return intval($this->_idProblem);
  }

  /**
   * void setIdProblem(int $value)
   *
   * @param int $value id problem
   * @return void
   * @access public
   */
  function setIdProblem($value)
  {
    $this->_idProblem = intval($value);
  }

  /**
   * int getIdPatient(void)
   *
   * @return string id patient
   * @access public
   */
  function getIdPatient()
  {
    return intval($this->_idPatient);
  }

  /**
   * void setIdPatient(int $value)
   *
   * @param int $value id patient
   * @return void
   * @access public
   */
  function setIdPatient($value)
  {
    $this->_idPatient = intval($value);
  }

  /**
   * int getOrderNumber(void)
   *
   * @return int order number problem relative to the id patient
   * @access public
   */
  function getOrderNumber()
  {
    return intval($this->_orderNumber);
  }

  /**
   * void setOrderNumber(int $value)
   *
   * @param int $value order number problem relative to the id patient
   * @return void
   * @access public
   */
  function setOrderNumber($value)
  {
    $this->_orderNumber = intval($value);
  }

  /**
   * int getIdMember(void)
   *
   * @return string id member
   * @access public
   */
  function getIdMember()
  {
    return intval($this->_idMember);
  }

  /**
   * void setIdMember(int $value)
   *
   * @param int $value id member
   * @return void
   * @access public
   */
  function setIdMember($value)
  {
    $this->_idMember = intval($value);
  }

  /**
   * string getCollegiateNumber(void)
   *
   * @return string collegiate number
   * @access public
   */
  function getCollegiateNumber()
  {
    return stripslashes(strtr($this->_collegiateNumber, $this->_trans));
  }

  /**
   * void setCollegiateNumber(string $value)
   *
   * @param string $value collegiate number
   * @return void
   * @access public
   */
  function setCollegiateNumber($value)
  {
    $this->_collegiateNumber = Check::safeText($value);
  }

  /**
   * string getOpeningDate(void)
   *
   * @return string opening date of the medical problem
   * @access public
   */
  function getOpeningDate($view = true)
  {
    return stripslashes(strtr($this->_openingDate, $this->_trans));
  }

  /**
   * void setOpeningDate(string $value)
   *
   * @param string $value opening date of the medical problem
   * @return void
   * @access public
   */
  function setOpeningDate($value)
  {
    $this->_openingDate = Check::safeText($value);
  }

  /**
   * string getClosingDate(void)
   *
   * @return string closing date of the medical problem
   * @access public
   */
  function getClosingDate()
  {
    return stripslashes(strtr($this->_closingDate, $this->_trans));
  }

  /**
   * void setClosingDate(string $value)
   *
   * @param string $value closing date of the medical problem
   * @return void
   * @access public
   */
  function setClosingDate($value)
  {
    $temp = Check::safeText($value);
    $this->_closingDate = (($temp == "" || $temp == "0000-00-00") ? "0000-00-00" : $temp);
  }

  /**
   * string getMeetingPlace(void)
   *
   * @return string meeting place of the medical problem
   * @access public
   */
  function getMeetingPlace()
  {
    return stripslashes(strtr($this->_meetingPlace, $this->_trans));
  }

  /**
   * void setMeetingPlace(string $value)
   *
   * @param string $value meeting place of the medical problem
   * @return void
   * @access public
   */
  function setMeetingPlace($value)
  {
    $this->_meetingPlace = Check::safeText($value);
  }

  /**
   * string getWording(void)
   *
   * @return string wording of the medical problem
   * @access public
   */
  function getWording()
  {
    return stripslashes(strtr($this->_wording, $this->_trans));
  }

  /**
   * string getWordingError(void)
   *
   * @return string wording error of the medical problem
   * @access public
   */
  function getWordingError()
  {
    return $this->_wordingError;
  }

  /**
   * void setWording(string $value)
   *
   * @param string $value wording of the medical problem
   * @return void
   * @access public
   */
  function setWording($value)
  {
    $this->_wording = Check::safeText($value);
  }

  /**
   * string getSubjective(void)
   *
   * @return string subjective of the medical problem
   * @access public
   */
  function getSubjective()
  {
    return stripslashes(strtr($this->_subjective, $this->_trans));
  }

  /**
   * void setSubjective(string $value)
   *
   * @param string $value subjective of the medical problem
   * @return void
   * @access public
   */
  function setSubjective($value)
  {
    $this->_subjective = Check::safeText($value);
  }

  /**
   * string getObjective(void)
   *
   * @return string objective of the medical problem
   * @access public
   */
  function getObjective()
  {
    return stripslashes(strtr($this->_objective, $this->_trans));
  }

  /**
   * void setObjective(string $value)
   *
   * @param string $value objective of the medical problem
   * @return void
   * @access public
   */
  function setObjective($value)
  {
    $this->_objective = Check::safeText($value);
  }

  /**
   * string getAppreciation(void)
   *
   * @return string appreciation of the medical problem
   * @access public
   */
  function getAppreciation()
  {
    return stripslashes(strtr($this->_appreciation, $this->_trans));
  }

  /**
   * void setAppreciation(string $value)
   *
   * @param string $value appreciation of the medical problem
   * @return void
   * @access public
   */
  function setAppreciation($value)
  {
    $this->_appreciation = Check::safeText($value);
  }

  /**
   * string getActionPlan(void)
   *
   * @return string action plan of the medical problem
   * @access public
   */
  function getActionPlan()
  {
    return stripslashes(strtr($this->_actionPlan, $this->_trans));
  }

  /**
   * void setActionPlan(string $value)
   *
   * @param string $value action plan of the medical problem
   * @return void
   * @access public
   */
  function setActionPlan($value)
  {
    $this->_actionPlan = Check::safeText($value);
  }

  /**
   * string getPrescription(void)
   *
   * @return string prescription of the medical problem
   * @access public
   */
  function getPrescription()
  {
    return stripslashes(strtr($this->_prescription, $this->_trans));
  }

  /**
   * void setPrescription(string $value)
   *
   * @param string $value prescription of the medical problem
   * @return void
   * @access public
   */
  function setPrescription($value)
  {
    $this->_prescription = Check::safeText($value);
  }

  /**
   * string getLastUpdateDate(void)
   *
   * @return string last update date of the medical problem
   * @access public
   */
  function getLastUpdateDate()
  {
    return stripslashes(strtr($this->_lastUpdateDate, $this->_trans));
  }

  /**
   * void setLastUpdateDate(string $value)
   *
   * @param string $value last update date of the medical problem
   * @return void
   * @access public
   */
  function setLastUpdateDate($value)
  {
    $this->_lastUpdateDate = Check::safeText($value);
  }
} // end class
?>
