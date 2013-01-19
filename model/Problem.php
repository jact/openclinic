<?php
/**
 * Problem.php
 *
 * Contains the class Problem
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Problem.php,v 1.21 2013/01/19 10:25:52 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../lib/Check.php");
require_once(dirname(__FILE__) . "/../lib/HTML.php");
require_once(dirname(__FILE__) . "/../lib/String.php");
require_once(dirname(__FILE__) . "/../lib/I18n.php");
require_once(dirname(__FILE__) . "/Query/Page/Problem.php");

/*
 * Problem represents a medical problem.
 *
 * Methods:
 *  mixed Problem(int $id = 0)
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
 *  string getHeader(void)
 *  string __toString(void)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Problem
{
  private $_idProblem = 0;
  private $_idPatient = 0;
  private $_idMember = 0;
  private $_collegiateNumber = "";
  private $_orderNumber = 0;
  private $_openingDate = "";
  private $_closingDate = "";
  private $_meetingPlace = "";
  private $_wording = "";
  private $_wordingError = "";
  private $_subjective = "";
  private $_objective = "";
  private $_appreciation = "";
  private $_actionPlan = "";
  private $_prescription = "";
  //private $_createDate = "";
  private $_lastUpdateDate = "";

  private $_trans; // to translate htmlspecialchars()

  /**
   * mixed Problem(int $id = 0)
   *
   * Constructor
   *
   * @param int $id (optional)
   * @return mixed void if not argument, null if not exists problem, object otherwise
   * @access public
   */
  function Problem($id = 0)
  {
    $this->_trans = array_flip(get_html_translation_table(HTML_SPECIALCHARS));

    if ($id)
    {
      $_problemQ = new Query_Page_Problem();
      if ( !$_problemQ->select($id) )
      {
        return null;
      }

      foreach (get_object_vars($_problemQ->fetch()) as $key => $value)
      {
        $this->$key = $value;
      }

      $_problemQ->freeResult();
      $_problemQ->close();
    }
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
  function getOpeningDate()
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

  /**
   * string getWordingPreview(void)
   *
   * Returns wording preview of the medical problem
   *
   * @return string wording preview of the medical problem
   * @access public
   * @since 0.8
   */
  function getWordingPreview()
  {
    return String::fieldPreview($this->_wording);
  }

  /**
   * string getHeader(void)
   *
   * Returns a header with medical problem information
   *
   * @return string
   * @access public
   * @since 0.8
   */
  function getHeader()
  {
    $_html = HTML::start('div', array('id' => 'problem_header', 'class' => 'clearfix'));
    $_html .= HTML::para(_("Wording") . ': ' . $this->getWordingPreview());
    $_html .= HTML::para(
      _("Opening Date") . ': ' . I18n::localDate($this->getOpeningDate()),
      array('class' => 'right')
    );
    $_html .= HTML::para(
      _("Last Update Date") . ': ' . I18n::localDate($this->getLastUpdateDate()),
      array('class' => 'right')
    );
    $_html .= HTML::end('div');

    return $_html;
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
    return $this->getWording();
  }
} // end class
?>
