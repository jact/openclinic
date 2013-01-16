<?php
/**
 * History.php
 *
 * Contains the class History
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: History.php,v 1.10 2013/01/16 19:04:07 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../lib/Check.php");

/*
 * History represents personal and family antecedents of a patient.
 *
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
 *  string __toString(void)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class History
{
  private $_idPatient = 0;

  private $_birthGrowth = "";
  private $_growthSexuality = "";
  private $_feed = "";
  private $_habits = "";
  private $_peristalticConditions = "";
  private $_psychological = "";

  private $_childrenComplaint = "";
  private $_venerealDisease = "";
  private $_accidentSurgicalOperation = "";
  private $_medicinalIntolerance = "";
  private $_mentalIllness = "";

  private $_parentsStatusHealth = "";
  private $_brothersStatusHealth = "";
  private $_spouseChildsStatusHealth = "";
  private $_familyIllness = "";

  private $_trans; // to translate htmlspecialchars()

  public function History()
  {
    $this->_trans = array_flip(get_html_translation_table(HTML_SPECIALCHARS));
  }

  /*
   * bool validateData(void)
   *
   * @return boolean true if data is valid, otherwise false.
   * @access public
   */
  public function validateData()
  {
    $valid = true;

    return $valid;
  }

  /**
   * int getIdPatient(void)
   *
   * @return int
   * @access public
   */
  public function getIdPatient()
  {
    return intval($this->_idPatient);
  }

  /**
   * string getBirthGrowth(void)
   *
   * @return string
   * @access public
   */
  public function getBirthGrowth()
  {
    return stripslashes(strtr($this->_birthGrowth, $this->_trans));
  }

  /**
   * string getGrowthSexuality(void)
   *
   * @return string
   * @access public
   */
  public function getGrowthSexuality()
  {
    return stripslashes(strtr($this->_growthSexuality, $this->_trans));
  }

  /**
   * string getFeed(void)
   *
   * @return string
   * @access public
   */
  public function getFeed()
  {
    return stripslashes(strtr($this->_feed, $this->_trans));
  }

  /**
   * string getHabits(void)
   *
   * @return string
   * @access public
   */
  public function getHabits()
  {
    return stripslashes(strtr($this->_habits, $this->_trans));
  }

  /**
   * string getPeristalticConditions(void)
   *
   * @return string
   * @access public
   */
  public function getPeristalticConditions()
  {
    return stripslashes(strtr($this->_peristalticConditions, $this->_trans));
  }

  /**
   * string getPsychological(void)
   *
   * @return string
   * @access public
   */
  public function getPsychological()
  {
    return stripslashes(strtr($this->_psychological, $this->_trans));
  }

  /**
   * string getChildrenComplaint(void)
   *
   * @return string
   * @access public
   */
  public function getChildrenComplaint()
  {
    return stripslashes(strtr($this->_childrenComplaint, $this->_trans));
  }

  /**
   * string getVenerealDisease(void)
   *
   * @return string
   * @access public
   */
  public function getVenerealDisease()
  {
    return stripslashes(strtr($this->_venerealDisease, $this->_trans));
  }

  /**
   * string getAccidentSurgicalOperation(void)
   *
   * @return string
   * @access public
   */
  public function getAccidentSurgicalOperation()
  {
    return stripslashes(strtr($this->_accidentSurgicalOperation, $this->_trans));
  }

  /**
   * string getMedicinalIntolerance(void)
   *
   * @return string
   * @access public
   */
  public function getMedicinalIntolerance()
  {
    return stripslashes(strtr($this->_medicinalIntolerance, $this->_trans));
  }

  /**
   * string getMentalIllness(void)
   *
   * @return string
   * @access public
   */
  public function getMentalIllness()
  {
    return stripslashes(strtr($this->_mentalIllness, $this->_trans));
  }

  /**
   * string getParentsStatusHealth(void)
   *
   * @return string
   * @access public
   */
  public function getParentsStatusHealth()
  {
    return stripslashes(strtr($this->_parentsStatusHealth, $this->_trans));
  }

  /**
   * string getBrothersStatusHealth(void)
   *
   * @return string
   * @access public
   */
  public function getBrothersStatusHealth()
  {
    return stripslashes(strtr($this->_brothersStatusHealth, $this->_trans));
  }

  /**
   * string getSpouseChildsStatusHealth(void)
   *
   * @return string
   * @access public
   */
  public function getSpouseChildsStatusHealth()
  {
    return stripslashes(strtr($this->_spouseChildsStatusHealth, $this->_trans));
  }

  /**
   * string getFamilyIllness(void)
   *
   * @return string
   * @access public
   */
  public function getFamilyIllness()
  {
    return stripslashes(strtr($this->_familyIllness, $this->_trans));
  }

  /**
   * void setIdPatient(int $value)
   *
   * @param int $value new value to set
   * @return void
   * @access public
   */
  public function setIdPatient($value)
  {
    $this->_idPatient = intval($value);
  }

  /**
   * void setBirthGrowth(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setBirthGrowth($value)
  {
    $this->_birthGrowth = Check::safeText($value);
  }

  /**
   * void setGrowthSexuality(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setGrowthSexuality($value)
  {
    $this->_growthSexuality = Check::safeText($value);
  }

  /**
   * void setFeed(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setFeed($value)
  {
    $this->_feed = Check::safeText($value);
  }

  /**
   * void setHabits(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setHabits($value)
  {
    $this->_habits = Check::safeText($value);
  }

  /**
   * void setPeristalticConditions(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setPeristalticConditions($value)
  {
    $this->_peristalticConditions = Check::safeText($value);
  }

  /**
   * void setPsychological(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setPsychological($value)
  {
    $this->_psychological = Check::safeText($value);
  }

  /**
   * void setChildrenComplaint(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setChildrenComplaint($value)
  {
    $this->_childrenComplaint = Check::safeText($value);
  }

  /**
   * void setVenerealDisease(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setVenerealDisease($value)
  {
    $this->_venerealDisease = Check::safeText($value);
  }

  /**
   * void setAccidentSurgicalOperation(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setAccidentSurgicalOperation($value)
  {
    $this->_accidentSurgicalOperation = Check::safeText($value);
  }

  /**
   * void setMedicinalIntolerance(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setMedicinalIntolerance($value)
  {
    $this->_medicinalIntolerance = Check::safeText($value);
  }

  /**
   * void setMentalIllness(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setMentalIllness($value)
  {
    $this->_mentalIllness = Check::safeText($value);
  }

  /**
   * void setParentsStatusHealth(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setParentsStatusHealth($value)
  {
    $this->_parentsStatusHealth = Check::safeText($value);
  }

  /**
   * void setBrothersStatusHealth(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setBrothersStatusHealth($value)
  {
    $this->_brothersStatusHealth = Check::safeText($value);
  }

  /**
   * void setSpouseChildsStatusHealth(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setSpouseChildsStatusHealth($value)
  {
    $this->_spouseChildsStatusHealth = Check::safeText($value);
  }

  /**
   * void setFamilyIllness(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  public function setFamilyIllness($value)
  {
    $this->_familyIllness = Check::safeText($value);
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
