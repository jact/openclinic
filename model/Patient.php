<?php
/**
 * Patient.php
 *
 * Contains the class Patient
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Patient.php,v 1.18 2008/03/23 12:00:55 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../lib/Check.php");
require_once(dirname(__FILE__) . "/../lib/HTML.php");
require_once(dirname(__FILE__) . "/Query/Page/Patient.php");

/*
 * Patient contains business rules for patient data validation.
 *
 * Methods:
 *  mixed Patient(int $id = 0)
 *  bool validateData(void)
 *  int getIdPatient(void)
 *  void setIdPatient(int $value)
 *  int getIdMember(void)
 *  void setIdMember(int $value)
 *  string getCollegiateNumber(void)
 *  void setCollegiateNumber(string $value)
 *  string getNIF(void)
 *  void setNIF(string $value)
 *  string getFirstName(void)
 *  string getFirstNameError(void)
 *  void setFirstName(string $value)
 *  string getSurname1(void)
 *  string getSurname1Error(void)
 *  void setSurname1(string $value)
 *  string getSurname2(void)
 *  string getSurname2Error(void)
 *  void setSurname2(string $value)
 *  string getName(void)
 *  string getAddress(void)
 *  void setAddress(string $value)
 *  string getPhone(void)
 *  void setPhone(string $value)
 *  string getSex(void)
 *  void setSex(string $value)
 *  string getRace(void)
 *  void setRace(string $value)
 *  string getBirthDate(void)
 *  string getBirthDateError(void)
 *  void setBirthDate(string $value)
 *  void setBirthDateFromParts(string $month, string $day, string $year)
 *  int getAge(void)
 *  void setAge(string $value)
 *  string getBirthPlace(void)
 *  void setBirthPlace(string $value)
 *  string getDeceaseDate(void)
 *  string getDeceaseDateError(void)
 *  void setDeceaseDate(string $value)
 *  void setDeceaseDateFromParts(string $month, string $day, string $year)
 *  string getNTS(void)
 *  void setNTS(string $value)
 *  string getNSS(void)
 *  void setNSS(string $value)
 *  string getFamilySituation(void)
 *  void setFamilySituation(string $value)
 *  string getLabourSituation(void)
 *  void setLabourSituation(string $value)
 *  string getEducation(void)
 *  void setEducation(string $value)
 *  string getInsuranceCompany(void)
 *  void setInsuranceCompany(string $value)
 *  string getHeader(void)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Patient
{
  var $_idPatient = 0;
  var $_idMember = 0;
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

  /**
   * mixed Patient(int $id = 0)
   *
   * Constructor
   *
   * @param int $id (optional)
   * @return mixed void if not argument, null if not exists patient, object otherwise
   * @access public
   */
  function Patient($id = 0)
  {
    $this->_trans = array_flip(get_html_translation_table(HTML_SPECIALCHARS));

    if ($id)
    {
      $_patQ = new Query_Page_Patient();
      if ( !$_patQ->select($id) )
      {
        return null;
      }
      $this = $_patQ->fetch();

      $_patQ->freeResult();
      $_patQ->close();

      return $this;
    }
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
   *
   * @return int
   * @access public
   */
  function getIdPatient()
  {
    return intval($this->_idPatient);
  }

  /**
   * void setIdPatient(int $value)
   *
   * @param int $value new value to set
   * @return void
   * @access public
   */
  function setIdPatient($value)
  {
    $this->_idPatient = intval($value);
  }

  /**
   * int getIdMember(void)
   *
   * @return int
   * @access public
   */
  function getIdMember()
  {
    return intval($this->_idMember);
  }

  /**
   * void setIdMember(int $value)
   *
   * @param int $value new value to set
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
   * string getNIF(void)
   *
   * @return string
   * @access public
   */
  function getNIF()
  {
    return stripslashes(strtr($this->_nif, $this->_trans));
  }

  /**
   * void setNIF(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setNIF($value)
  {
    $this->_nif = Check::safeText($value);
  }

  /**
   * string getFirstName(void)
   *
   * @return string
   * @access public
   */
  function getFirstName()
  {
    return stripslashes(strtr($this->_firstName, $this->_trans));
  }

  /**
   * string getFirstNameError(void)
   *
   * @return string
   * @access public
   */
  function getFirstNameError()
  {
    return $this->_firstNameError;
  }

  /**
   * void setFirstName(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setFirstName($value)
  {
    $this->_firstName = Check::safeText($value);
  }

  /**
   * string getSurname1(void)
   *
   * @return string
   * @access public
   */
  function getSurname1()
  {
    return stripslashes(strtr($this->_surname1, $this->_trans));
  }

  /**
   * string getSurname1Error(void)
   *
   * @return string
   * @access public
   */
  function getSurname1Error()
  {
    return $this->_surname1Error;
  }

  /**
   * void setSurname1(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setSurname1($value)
  {
    $this->_surname1 = Check::safeText($value);
  }

  /**
   * string getSurname2(void)
   *
   * @return string
   * @access public
   */
  function getSurname2()
  {
    return stripslashes(strtr($this->_surname2, $this->_trans));
  }

  /**
   * string getSurname2Error(void)
   *
   * @return string
   * @access public
   */
  function getSurname2Error()
  {
    return $this->_surname2Error;
  }

  /**
   * void setSurname2(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setSurname2($value)
  {
    $this->_surname2 = Check::safeText($value);
  }

  /**
   * string getName(void)
   *
   * @return string
   * @access public
   * @since 0.8
   */
  function getName()
  {
    return trim(stripslashes(strtr(
      $this->_firstName . ' ' . $this->_surname1 . ' ' . $this->_surname2, $this->_trans))
    );
  }

  /**
   * string getAddress(void)
   *
   * @return string
   * @access public
   */
  function getAddress()
  {
    return stripslashes(strtr($this->_address, $this->_trans));
  }

  /**
   * void setAddress(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setAddress($value)
  {
    $this->_address = Check::safeText($value);
  }

  /**
   * string getPhone(void)
   *
   * @return string
   * @access public
   */
  function getPhone()
  {
    return stripslashes(strtr($this->_phone, $this->_trans));
  }

  /**
   * void setPhone(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setPhone($value)
  {
    $this->_phone = Check::safeText($value);
  }

  /**
   * string getSex(void)
   *
   * @return string
   * @access public
   */
  function getSex()
  {
    return stripslashes(strtr($this->_sex, $this->_trans));
  }

  /**
   * void setSex(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setSex($value)
  {
    $this->_sex = Check::safeText($value);
  }

  /**
   * string getRace(void)
   *
   * @return string
   * @access public
   */
  function getRace()
  {
    return stripslashes(strtr($this->_race, $this->_trans));
  }

  /**
   * void setRace(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setRace($value)
  {
    $this->_race = Check::safeText($value);
  }

  /**
   * string getBirthDate(void)
   *
   * @return string
   * @access public
   */
  function getBirthDate()
  {
    return $this->_birthDate;
  }

  /**
   * string getBirthDateError(void)
   *
   * @return string
   * @access public
   */
  function getBirthDateError()
  {
    return $this->_birthDateError;
  }

  /**
   * void setBirthDate(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setBirthDate($value)
  {
    $this->_birthDate = Check::safeText($value);
  }

  /**
   * void setBirthDateFromParts(string $month, string $day, string $year)
   *
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
   * int getAge(void)
   *
   * @return int
   * @access public
   */
  function getAge()
  {
    return intval($this->_age);
  }

  /**
   * void setAge(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setAge($value)
  {
    $this->_age = intval($value);
  }

  /**
   * string getBirthPlace(void)
   *
   * @return string
   * @access public
   */
  function getBirthPlace()
  {
    return stripslashes(strtr($this->_birthPlace, $this->_trans));
  }

  /**
   * void setBirthPlace(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setBirthPlace($value)
  {
    $this->_birthPlace = Check::safeText($value);
  }

  /**
   * string getDeceaseDate(void)
   *
   * @return string
   * @access public
   */
  function getDeceaseDate()
  {
    return $this->_deceaseDate;
  }

  /**
   * string getDeceaseDateError(void)
   *
   * @return string
   * @access public
   */
  function getDeceaseDateError()
  {
    return $this->_deceaseDateError;
  }

  /**
   * void setDeceaseDate(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setDeceaseDate($value)
  {
    $this->_deceaseDate = Check::safeText($value);
  }

  /**
   * void setDeceaseDateFromParts(string $month, string $day, string $year)
   *
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
   * string getNTS(void)
   *
   * @return string
   * @access public
   */
  function getNTS()
  {
    return stripslashes(strtr($this->_nts, $this->_trans));
  }

  /**
   * void setNTS(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setNTS($value)
  {
    $this->_nts = Check::safeText($value);
  }

  /**
   * string getNSS(void)
   *
   * @return string
   * @access public
   */
  function getNSS()
  {
    return stripslashes(strtr($this->_nss, $this->_trans));
  }

  /**
   * void setNSS(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setNSS($value)
  {
    $this->_nss = Check::safeText($value);
  }

  /**
   * string getFamilySituation(void)
   *
   * @return string
   * @access public
   */
  function getFamilySituation()
  {
    return stripslashes(strtr($this->_familySituation, $this->_trans));
  }

  /**
   * void setFamilySituation(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setFamilySituation($value)
  {
    $this->_familySituation = Check::safeText($value);
  }

  /**
   * string getLabourSituation(void)
   *
   * @return string
   * @access public
   */
  function getLabourSituation()
  {
    return stripslashes(strtr($this->_labourSituation, $this->_trans));
  }

  /**
   * void setLabourSituation(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setLabourSituation($value)
  {
    $this->_labourSituation = Check::safeText($value);
  }

  /**
   * string getEducation(void)
   *
   * @return string
   * @access public
   */
  function getEducation()
  {
    return stripslashes(strtr($this->_education, $this->_trans));
  }

  /**
   * void setEducation(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setEducation($value)
  {
    $this->_education = Check::safeText($value);
  }

  /**
   * string getInsuranceCompany(void)
   *
   * @return string
   * @access public
   */
  function getInsuranceCompany()
  {
    return stripslashes(strtr($this->_insuranceCompany, $this->_trans));
  }

  /**
   * void setInsuranceCompany(string $value)
   *
   * @param string $value new value to set
   * @return void
   * @access public
   */
  function setInsuranceCompany($value)
  {
    $this->_insuranceCompany = Check::safeText($value);
  }

  /**
   * string getLastUpdateDate(void)
   *
   * @return string last update date of the patient data
   * @access public
   */
/*  function getLastUpdateDate()
  {
    return stripslashes(strtr($this->_lastUpdateDate, $this->_trans));
  }*/

  /**
   * void setLastUpdateDate(string $value)
   *
   * @param string $value last update date of the patient data
   * @return void
   * @access public
   */
/*  function setLastUpdateDate($value)
  {
    $this->_lastUpdateDate = Check::safeText($value);
  }*/

  /**
   * string getHeader(void)
   *
   * Returns a header with patient information
   *
   * @return string
   * @access public
   * @since 0.8
   */
  function getHeader()
  {
    $_html = HTML::start('div', array('id' => 'patient_header', 'class' => 'clearfix'));
    $_html .= HTML::para(_("Patient") . ': ' . $this->getName());
    $_html .= HTML::para(_("Sex") . ': ' . ($this->getSex() == 'V' ? _("Male") : _("Female")));
    $_html .= HTML::para(_("Age") . ': ' . $this->getAge(), array('class' => 'right'));
    $_html .= HTML::end('div');

    return $_html;
  }
} // end class
?>
